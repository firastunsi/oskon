<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Media
 * @subpackage	MediaCore
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

class Media_core extends CI_Controller {

	var $per_page = 5;
	
	public function __construct()
	{
		parent::__construct();
		is_installed(); #defined in auth helper
		checksavedlogin(); #defined in auth helper
		
		if(!is_loggedin())
		{
			if(count($_POST)<=0)
			$this->session->set_userdata('req_url',current_url());
			redirect(site_url('admin/auth'));
		}
		
		$this->load->model('media_model');	
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="alert alert-error input-xxlarge"">', '</div>');
			
	}
	
	public function index()
	{
		$value['content'] = load_view('media/newmedia_view');
		echo $value['content'];
		#$this->load->view('template_view',$value);	
	}

	public function upload()
	{
	    $img = $this->uploadImage('photoimg');
		$thumb = $this->resize($img);

		if($thumb==TRUE)
		{
			echo '<img src="'.base_url().'/assets/images/thumb/'.$img['raw_name'].'_thumb'.$img['file_ext'].'" />';
			echo "<script type=\"text/javascript\">var img = '".$img['raw_name'].'_thumb'.$img['file_ext']."';jQuery('.useimg').show();</script>";
		}
		else
		{
			print_r($thumb);
		}
	}
	
	#upload image function
    function uploadImage($filename) 
    {
		$config['upload_path'] = './assets/images/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1024';
		$config['max_width']  = '';
		$config['max_height']  = '';
		$this->load->library('upload');
		$this->upload->initialize($config);
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($filename))
		{
			$error = array('error' => $this->upload->display_errors());
			return $this->upload->display_errors();
		}
		else
		{
			$data = $this->upload->data();
			$this->load->helper('date');
			$format = 'DATE_RFC822';
			$time = time();
			
			$media['media_name'] 		= $data['raw_name'].'_thumb'.$data['file_ext'];
			$media['media_url']  		= base_url().'assets/images/thumb/'.$data['raw_name'].'_thumb'.$data['file_ext'];
			$media['create_time'] 		= standard_date($format, $time);
			$media['created_by']		= $this->media_model->get_id_by_username($this->session->userdata('user_name'));
			$media['status']			= 1;
			
			$this->media_model->insert_media($media);
			return $data;
		}
		return ;
	}
	
	#image resize function
	#need gd2 enabled
	function resize($img)
	{
		$config['image_library'] = 'gd2';
		$config['source_image'] = './assets/images/'.$img['file_name'];
		$config['new_image'] 	= './assets/images/thumb/'.$img['file_name'];
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = 256;
		$config['height'] = 256;
		
		$this->load->library('image_lib', $config);
		
	 	if ( ! $this->image_lib->resize())
		{
		    return $this->image_lib->display_errors();	
		}
		else
		{
			return TRUE;
		}
	}
	
	public function allmedias($start=0)
	{
		$value['posts']  	= $this->media_model->get_all_medias_by_range($start,$this->per_page,'create_time');
		$total 				= $this->media_model->count_all_medias();
		$value['pages']		= $this->configPagination('media/allmedias',$total,3,$this->per_page);
		
		$data['content'] = load_view('media/allmedias_view',$value,TRUE);
		echo $data['content'];	
	}
	
	public function deletemedia($id='',$confirmation='')
	{
		if($confirmation=='')
		{
			$data['content'] = load_view('media/confirmation_view',array('id'=>$id,'url'=>site_url('media/deletemedia')),TRUE);
			echo $data['content'];
		}
		else
		{
			if($confirmation=='yes')
			{
				echo '<div class="alert alert-success">Data Deleted</div>';
				$this->media_model->delete_media($id);	
			}
			
			$this->allmedias();
		}		
	}
	
	function configPagination($url,$total_rows,$segment,$per_page=10)
	{
		$this->load->library('pagination');
		$config['base_url'] 		= base_url().$url;
		$config['total_rows'] 		= $total_rows;
		$config['per_page'] 		= $per_page;
		$config['uri_segment'] 		= $segment;
		$config['num_tag_open'] 	= '<li>';
		$config['num_tag_close'] 	= '</li>';
		$config['cur_tag_open'] 	= '<li class="active"><a href="#">';
		$config['cur_tag_close']	= '</a></li>';
		$config['num_links'] 		= 1;
		$config['next_tag_open'] 	= "<li>";
		$config['next_tag_close'] 	= "</li>";
		$config['prev_tag_open'] 	= "<li>";
		$config['prev_tag_close'] 	= "</li>";
		
		$config['first_link'] 	= FALSE;
		$config['last_link'] 	= FALSE;
		$this->pagination->initialize($config);
		
		return $this->pagination->create_links();
	}
}

/* End of file install.php */
/* Location: ./application/modules/media/controllers/media_core.php */