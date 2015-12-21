<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		User
 * @subpackage	UserCore
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

class User_core extends CI_Controller {
	
	var $active_theme = '';
	var $per_page = 2;
	public function __construct()
	{
		parent::__construct();
		is_installed(); #defined in auth helper
		
		if(!is_loggedin())
		{
			if(count($_POST)<=0)
			$this->session->set_userdata('req_url',current_url());
			redirect(site_url('account/trylogin'));
		}
		//$this->per_page = get_per_page_value();

		$this->load->database();
		$this->active_theme = get_active_theme();
		$this->load->model('user_model');
		$this->load->model('show/post_model');
		$this->load->helper('dbcvote');
		$this->form_validation->set_error_delimiters('<label class="col-lg-2 control-label">&nbsp;</label><div class="col-lg-8"><div class="alert alert-danger" style="margin-bottom:0;">', '</div></div>');
		//$this->output->enable_profiler(TRUE);

	}
	
	public function index()
	{
		$this->post();	
	}
	
	
	function changepass()
	{
		$data['content'] 	= load_view('user/changepassword_view','',TRUE,$this->active_theme);
		load_template($data,$this->active_theme);
	}
	
	#current password validation function for password changing
	function currentpass_check($str)
	{
		$this->load->model('account/auth_model');
		$user_email = $this->session->userdata('user_email');
		$res = $this->auth_model->check_login($user_email,$str);
		if ($res<=0)
		{
			$this->form_validation->set_message('currentpass_check', 'Current password Didn\'t match');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	#update password function
	function update_password()
	{
		if($this->session->userdata('recovery')!='yes')
		$this->form_validation->set_rules('current_password', 'Current Password', 'required|callback_currentpass_check');
		
		$this->form_validation->set_rules('new_password', 'New Password', 'required|matches[re_password]');
		$this->form_validation->set_rules('re_password', 'Password Confirmation', 'required');
			
		if ($this->form_validation->run() == FALSE)
		{
			$this->changepass();	
		}
		else
		{
			$password = $this->input->post('new_password');
			$this->user_model->update_password($password);
			$this->session->set_userdata('recovery',"no");
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Password changed successfully</div>');
			redirect(site_url('user/changepass'));		
		}
	
	}




	public function post($type='photo',$id='')
	{

		$value 				= array('post'=>false);
		$value['type'] 		= $type;
		if($id!='')
		$value['post']		= $this->user_model->get_post_by_id($id);
		$data['content'] 	= load_view('user/newpost_view',$value,TRUE);
		$data['subtitle']	= lang_key('add_'.$type);
		$data['alias']		= get_alias_by_url();
		load_template($data,$this->active_theme,'template_view');
	}

	function is_valid($url)
	{
		list($width, $height, $type, $attr) = @getimagesize($url);

		if ($width=='')
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	function url_check($url='')
	{

		$data['posttype']	= $this->input->post('posttype');
		if($data['posttype']=='upload')
		{
			$data['file']		= $this->input->post('file_name');
			$data['url'] 		= base_url('uploads/'.$data['file']);
			$data['thumb_url'] 	= base_url('uploads/'.$data['file']);
			$type 				= 'photo';
			$res 				= $this->is_valid($data['url']);
		}
		else if($data['posttype']=='video' || $data['posttype']=='vine')
		{
			$data['url'] 		= $this->input->post('url');								
			$data['thumb_url'] 	= $this->get_video_thumb($data['url']);
			if($data['thumb_url']=='na.jpg')
			{
				$this->form_validation->set_message('url_check', 'Can\'t fetch thubnail from the url,pleae submit again');
				return FALSE;
			}
			$type 				= $data['posttype'];
			$res 				= $this->is_valid($data['thumb_url']);
		}
		else
		{
			$data['url']		= $this->input->post('url');				
			$data['thumb_url']	= $data['url'];
			$type 				= 'photo';
			$res 				= $this->is_valid($data['url']);
		}

		if ($res==FALSE)
		{
			$this->form_validation->set_message('url_check', 'This url is not valid');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function addpost()
	{
		$this->form_validation->set_rules('posttype', 	'Post type', 	'required|xss_clean');		

		if($this->input->post('posttype')=='upload')
		$this->form_validation->set_rules('file_name', 	'File', 		'required|callback_url_check|xss_clean');	
		else
		$this->form_validation->set_rules('url', 		'Url', 			'required|callback_url_check|xss_clean');
		$this->form_validation->set_rules('title', 		'Title', 		'required|xss_clean');
		$this->form_validation->set_rules('categories', 'Categories', 	'required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->post($this->input->post('type'));	
		}
		else
		{
			$this->load->helper('date');
			$format = 'DATE_RFC822';
			$time = time();

			$data['title'] 				= $this->filter_words($this->input->post('title'));
			$data['categories'] 		= json_encode($_POST['categories']);
			$data['unique_id']			= uniqid();
			//$data['description']		= $this->input->post('description');
			$data['tags']				= $this->input->post('tags');			
			$data['posttype']			= $this->input->post('posttype');
			$type = '';
			if($data['posttype']=='upload')
			{
				$data['file']	= $this->input->post('file_name');
				$data['url'] 	= base_url('uploads/'.$data['file']);

				$do_water_mark = get_settings('memento_settings','do_water_mark','Yes');
				if($do_water_mark=='Yes' && is_animated($data['url'])==FALSE)
				{
					//resized_to_fixed_width('./uploads/'.$data['file'],500);
					$text = get_settings('memento_settings','water_mark_text','N/A');
					put_watermark('./uploads/'.$data['file'],$text);
				}
				$data['thumb_url'] 	= base_url('uploads/'.$data['file']);
				$type = 'photo';
			}
			else if($data['posttype']=='video' || $data['posttype']=='vine')
			{
				$data['url'] 		= $this->input->post('url');								
				$data['thumb_url'] 	= $this->get_video_thumb($data['url']);
				$data['thumb_url'] 	= image_from_url($data['thumb_url'],rand(1,100).time().'.jpg');
				$type = 'video';
			}
			else
			{
				$url = image_from_url($this->input->post('url'));
				$data['url'] = $url;				
				$data['thumb_url']	= $data['url'];
				$type = 'photo';
			}

			if(is_animated(str_replace(base_url(),'./',$data['url'])))
			{
				$fileinfo = fileinfo_from_url($data['url']);
				gif2jpeg(str_replace(base_url(),'./',$data['url']),'./uploads/still/'.$fileinfo['filename'].'.jpg',6);
				$data['thumb_url'] = base_url('uploads/still/'.$fileinfo['filename'].'.jpg');
			}

			$fileinfo = fileinfo_from_url($data['thumb_url']);
			create_rect_thumb(str_replace(base_url(),'./',$data['thumb_url']),'./uploads/rect_thumbs/');
			$data['rect_thumb'] = base_url('/uploads/rect_thumbs/'.$fileinfo['filename'].'.jpg');
			$publish_directly = get_settings('memento_settings','publish_directly','Yes');
			
			$data['status']				= ($publish_directly=='Yes')?1:2; // 2 = pending
			$data['need_safety_filter']	= $this->input->post('need_safety_filter');
			$data['create_time'] 		= $time;
			$data['publish_time']		= $time;
			$user 						= $this->user_model->get_user_profile($this->session->userdata('user_email')); 
			$data['created_by']			= $user->id;

			$id = $this->user_model->insert_post($data);

			if($data['posttype']=='video' && $publish_directly=='Yes')
			redirect(site_url('video/'.$data['unique_id'].'/'.url_title($data['title'])));		
			else
			redirect(site_url('meme/'.$data['unique_id'].'/'.url_title($data['title'])));		
		}
	}

	public function filter_words($string)
	{
		$row = get_option('wordfilters');
		$wordfilters = '';
		if(!is_array($row))
		{
			$words = json_decode($row->values);
			foreach ($words as $key => $value) {
				$string = str_ireplace($key,$value,$string);
			}
			return $string;
		}
		else
			return $string;
	}

	public function updatepost()
	{
		$this->form_validation->set_rules('posttype', 'Post type', 'required|xss_clean');		
		$this->form_validation->set_rules('url', 'Url/File', 'required|xss_clean');
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$id = $this->input->post('id');
			$this->post($id);	
		}
		else
		{
			$id = $this->input->post('id');

			$this->load->helper('date');
			$format = 'DATE_RFC822';
			$time = time();

			$data['title'] 				= $this->input->post('title');
			$data['description']		= $this->input->post('description');
			$data['posttype']			= $this->input->post('posttype');
			$data['url']				= $this->input->post('url');
			$data['status']				= $this->input->post('publish');
			$data['file']				= $this->input->post('file_name');
			$data['need_safety_filter']	= $this->input->post('need_safety_filter');
			if($this->input->post('publish')=='1')
			$data['publish_time']		= $time;
		
			$res = $this->user_model->update_post($data,$id);
			if($res['error']==1)
			$this->session->set_flashdata('msg', '<div class="alert alert-error">Post can\'t be updated.</div>');
			else	
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Updated.Click <a href="">here</a> to publish it.</div>');
			redirect(site_url('user/post/'.$id));		
		}
	}

	function vinetest()
	{

		 $url =  $this->get_video_thumb('https://vine.co/v/MII2Mtt25bx');
		 echo $url;
//		 echo image_from_url($url,'vimeo.jpg');

//		echo $this->get_video_thumb('https://www.youtube.com/watch?v=vXf-m_vBykc');
	}

	public function get_video_thumb($url='')
	{
		// Handle Youtube
		if (strpos($url, "youtube.com")) 
		{
			$url = parse_url($url);
			$vid = parse_str($url['query'], $output);
			$video_id = $output['v'];
			$data['video_type'] = 'youtube';
			$data['video_id'] = $video_id;
			return 'http://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg';				 

		} // End Youtube 
		else if (strpos($url, "vimeo.com")) 
		{
			// Handle Vimeo
			$segments = explode('/', $url);
			$length = count($segments);
			$length--;
			$video_id=$segments[$length];
			$data['video_type'] = 'vimeo';
			$data['video_id'] = $video_id;
			$xml = @file_get_contents("http://vimeo.com/api/v2/video/$video_id.json");			
			if($xml=='')return '';
			else $xml= json_decode($xml);
			$xml = $xml[0];

			return (isset($xml->thumbnail_medium))?$xml->thumbnail_medium:'na.jpg';
		} // End Vimeo
		else if (strpos($url, "vine.co"))
		{
			$video_id=explode('vine.co/v/', $url);
			$video_id=$video_id[1];
			$data['video_type'] = 'vine';
			$data['video_id'] = $video_id;

			$vine = @file_get_contents("http://vine.co/v/{$video_id}");
			preg_match('/property="og:image" content="(.*?)"/', $vine, $matches);
			 
			return (isset($matches[1])) ? str_replace('https:','http:',$matches[1]) : 'na.jpg';
		} 		 
		// Set false if invalid URL
		else { $data = false; }
		 
		return $data;
		 
	}

	public function create_date_directory()
	{
		$year = date('Y');
		$mon  = date('M');
		if (!file_exists('./uploads/'.$year)) 
		{
    		mkdir('./uploads/'.$year);
		}
		if (!file_exists('uploads/'.$year.'/'.$mon)) 
		{
    		mkdir('./uploads/'.$year.'/'.$mon);
		}

		return $year.'/'.$mon.'/';
	}

	public function uploadfile()
	{
		$date_dir = $this->create_date_directory();
		$config['upload_path'] = './uploads/'.$date_dir;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '5120';
		
		$this->load->library('upload', $config);
		$this->upload->display_errors('', '');	
		if($this->upload->do_upload('photoimg'))
		{
			$data = $this->upload->data();
			$this->load->helper('date');
			$format = 'DATE_RFC822';
			$time = time();
			
			$media['media_name'] 		= $data['file_name'];
			$media['media_url']  		= base_url().'uploads/'.$date_dir.$data['file_name'];
			$media['create_time'] 		= standard_date($format, $time);
			$media['status']			= 1;
			
			$status['error'] 	= 0;
			$status['name']	= $date_dir.$data['file_name'];
		}
		else
		{
			$errors = $this->upload->display_errors();
			$errors = str_replace('<p>','',$errors);
			$errors = str_replace('</p>','',$errors);
			$status = array('error'=>$errors,'name'=>'');
		}

		echo json_encode($status);
		die;
	}

	public function uploader()
	{
		load_view('user/uploader_view');
	}

	
	public function upload_profile_photo()
	{
		$date_dir = 'profile_photos/';
		$config['upload_path'] = './uploads/profile_photos/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '5120';
		
		$this->load->library('upload', $config);
		$this->upload->display_errors('', '');	

		if($this->upload->do_upload('photoimg'))
		{
			$data = $this->upload->data();
			$this->load->helper('date');
			$format = 'DATE_RFC822';
			$time = time();
			
			$media['media_name'] 		= $data['file_name'];
			$media['media_url']  		= base_url().'uploads/profile_photos/'.$data['file_name'];
			$media['create_time'] 		= standard_date($format, $time);
			$media['status']			= 1;
			
			create_square_thumb('./uploads/profile_photos/'.$data['file_name'],'./uploads/profile_photos/thumb/');

			$status['error'] 	= 0;
			$status['name']	= $data['file_name'];
		}
		else
		{
			$errors = $this->upload->display_errors();
			$errors = str_replace('<p>','',$errors);
			$errors = str_replace('</p>','',$errors);
			$status = array('error'=>$errors,'name'=>'');
		}
		echo json_encode($status);
		die;
	}

	public function profile_photo_uploader()
	{
		load_view('user/profile_photo_uploader_view');
	}

	public function detail($user_name,$start='0')
	{
		$user_name = $this->uri->segment(4);
		$value['profile']	= $this->user_model->get_user_profile_by_user_name($user_name);
		$value['posts']		= $this->post_model->get_all_user_posts_by_range($start,$this->per_page,'publish_time','desc',$this->session->userdata('user_id')); 
		$total 				= $this->post_model->count_all_user_posts($this->session->userdata('user_id'));
		$value['pages']		= configPagination('user/detail/'.$user_name,$total,5,$this->per_page);	
		$data['content'] 	= load_view('default_view',$value,TRUE,$this->active_theme);

		load_template($data,$this->active_theme,'template_userprofile_view');
	}

	public function upvotes($user_name,$start='0')
	{
		$user_name = $this->uri->segment(4);
		$value['profile']	= $this->user_model->get_user_profile_by_user_name($user_name);
		$likes = json_decode($value['profile']->liked); 
		$ids = array();
		foreach ($likes as $key => $row) {
		 	array_push($ids,str_replace('post_','',$key));
		}
		$value['posts']		= $this->post_model->get_all_liked_posts_by_range($start,$this->per_page,'publish_time','desc',$ids); 
		$total 				= $this->post_model->count_all_liked_posts($ids);
		$value['pages']		= configPagination('user/detail/'.$user_name,$total,5,$this->per_page);	
		$data['content'] 	= load_view('default_view',$value,TRUE,$this->active_theme);
		$data['tab']		= 'upvotes';
		load_template($data,$this->active_theme,'template_userprofile_view');
	}

	public function is_animated($url)
	{
		if(is_animated($url))
		{
			echo 'yes';
		}
		else
			echo 'no';
	}

	public function info($user_name='')
	{
		if($user_name=='')
			$user_name = $this->session->userdata('username');

		$value['profile']	= $this->user_model->get_user_profile_by_user_name($user_name);  
		$data['content'] 	= load_view('user/info_view',$value,TRUE);
		$data['tab']		= 'info';
		load_template($data,$this->active_theme,'template_userprofile_view');

	}

	public function settings($user_name='')
	{
		if($user_name=='' || $user_name!=$this->session->userdata('user_name'))
		{
			$user_name = $this->session->userdata('username');
			$data['content'] 	= ' <div class="alert alert-danger">
								        <button data-dismiss="alert" class="close" type="button">×</button>
								        <strong>You don\'t have permission to view this page :(
								    </div>';		
		}
		else
		{
			$value['profile']	= $this->user_model->get_user_profile_by_user_name($user_name);  
			$data['content'] 	= load_view('user/settings_view',$value,TRUE);			
		}

		$data['tab']		= 'settings';
		load_template($data,$this->active_theme,'template_userprofile_view');
	}


	#update profile 
	public function updateprofile()
	{
		$this->form_validation->set_rules('first_name',	'First name', 'required');
		$this->form_validation->set_rules('last_name',	'Last name', 'required');
		$this->form_validation->set_rules('gender',	'Gender', 'required');
		$this->form_validation->set_rules('user_email',	'Email', 'required|valid_email');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->settings($this->session->userdata('user_name'));	
		}
		else
		{
			$id = $this->session->userdata('user_id');
			$data['profile_photo'] = $this->input->post('profile_photo'); 
			$data['first_name'] 	= $this->input->post('first_name');
			$data['last_name'] 	= $this->input->post('last_name');
			$data['gender'] 	= $this->input->post('gender');
			$data['user_email']	= $this->input->post('user_email');					
			$this->user_model->update_profile($data,$id);
			$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Data updated').'</div>');
			redirect(site_url('user/settings/'.$this->session->userdata('user_name')));		
		}
	}

	function test()
	{
		if(is_animated("./uploads/2014/Jun/post-13204-13360912693.gif"))
			echo 'animated';
		else
			echo 'Not animated';
	}
}

/* End of file install.php */
/* Location: ./application/modules/user/controllers/user_core.php */