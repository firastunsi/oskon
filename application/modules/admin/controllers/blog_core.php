<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento page Controller
 *
 * This class handles page management related functionality
 *
 * @package		Admin
 * @subpackage	Page
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

class Blog_core extends CI_Controller {
	
	var $per_page = 10;
	
	public function __construct()
	{
		parent::__construct();
		is_installed(); #defined in auth helper
		checksavedlogin(); #defined in auth helper
		
		if(!is_admin())
		{
			if(count($_POST)<=0)
			$this->session->set_userdata('req_url',current_url());
			redirect(site_url('admin/auth'));
		}

		$this->per_page = get_per_page_value();#defined in auth helper

		$this->load->model('blog_model');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
	}
	
	public function index()
	{
		$this->all();
	}

		#load all services view with paging
	public function all($start='0')
	{
		$value['posts']  	= $this->blog_model->get_all_posts_by_range($start,$this->per_page,'create_time');
		$total 				= $this->blog_model->count_all_posts();
		$value['pages']		= configPagination('admin/blog/all',$total,5,$this->per_page);
        $data['title'] = 'All Posts';
        $data['content'] = $this->load->view('admin/blog/allposts_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);		
	}


	public function manage($id='')
	{
		$values 		= array();
		$values['title'] = 'New Post';
		if($id!='')
		{
			$values['title']  		= 'Update Post';
			$values['action_type']  = 'update';
			$values['page'] 		= $this->blog_model->get_post_by_id($id);
		}

        $data['content'] = $this->load->view('blog/post_view',$values,TRUE);
		$this->load->view('admin/template/template_view',$data);			
	}


	public function add()
	{

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('type', 'Type', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			if($this->input->post('action_type')=='update')
				$this->manage($this->input->post('id'));
			else
				$this->manage();	
		}
		else
		{
			$data['featured_img'] 	= $this->input->post('featured_img');
			$data['type'] 			= $this->input->post('type');
			$data['title'] 			= $this->input->post('title');
			$data['description'] 	= $this->input->post('description');
			$data['created_by']		= $this->session->userdata('user_id');
			$data['create_time']	= time();
			$data['status']			= $this->input->post('action');
			

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				if($this->input->post('action_type')=='update')
				{
					$id = $this->input->post('id');
					$this->blog_model->update_post($data,$id);
					$this->session->set_flashdata('msg', '<div class="alert alert-success">Post updated</div>');				
				}
				else
				{
					$id = $this->blog_model->insert_post($data);
					$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Created</div>');				
				}				
			}	

			redirect(site_url('admin/blog/manage/'.$id));		
		}

	}
	

	public function delete($page='0',$id='',$confirmation='')
	{
		if($confirmation=='')
		{
			$data['content'] = $this->load->view('admin/confirmation_view',array('id'=>$id,'url'=>site_url('admin/blog/delete/'.$page)),TRUE);
			$this->load->view('admin/template/template_view',$data);
		}
		else
		{
			if($confirmation=='yes')
			{
				if(constant("ENVIRONMENT")=='demo')
				{
					$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
				}
				else
				{
					$this->blog_model->delete_post_by_id($id);
					$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Deleted</div>');					
				}
			}
			redirect(site_url('admin/blog/all/'.$page));		
			
		}		
	}


	public function featuredimguploader()

	{

		$this->load->view('admin/blog/featured_img_uploader_view');

	}

	public function uploadfeaturedfile()

	{

		$date_dir = 'thumbs/';

		$config['upload_path'] = './uploads/'.$date_dir;

		$config['allowed_types'] = 'gif|jpg|JPG|png';

		$config['max_size'] = '5120';

		$config['min_width'] = '256';

		$config['min_height'] = '256';



		$this->load->library('dbcupload', $config);

		$this->dbcupload->display_errors('', '');	

		if($this->dbcupload->do_upload('photoimg'))

		{

			$data = $this->dbcupload->data();

			$this->load->helper('date');

			$format = 'DATE_RFC822';

			$time = time();

			create_square_thumb('./uploads/'.$date_dir.$data['file_name'],'./uploads/thumbs/');

			$media['media_name'] 		= $data['file_name'];

			$media['media_url']  		= base_url().'uploads/'.$date_dir.$data['file_name'];

			$media['create_time'] 		= standard_date($format, $time);

			$media['status']			= 1;

			

			$status['error'] 	= 0;

			$status['name']	= $data['file_name'];

		}

		else

		{

			$errors = $this->dbcupload->display_errors();

			$errors = str_replace('<p>','',$errors);

			$errors = str_replace('</p>','',$errors);

			$status = array('error'=>$errors,'name'=>'');

		}



		echo json_encode($status);

		die;

	}



}

/* End of file page_core.php */
/* Location: ./application/modules/admin/controllers/page_core.php */