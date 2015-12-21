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

class Page_core extends CI_Controller {
	
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

		$this->load->model('page_model');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
	}
	
	public function index()
	{
		$this->manage();
	}

	public function manage($id='')
	{
		$values = array();
		$values['title'] = 'New Page';
		$data['title'] = 'New Page';
		if($id!='')
		{
			$data['title'] = 'Edit Page';
			$values['title'] = 'Edit Page';
			$values['action_type']  = 'update';
			$values['page'] 		= $this->page_model->get_page_by_id($id);
		}
        
        $data['content'] = $this->load->view('pages/page_view',$values,TRUE);
		$this->load->view('admin/template/template_view',$data);			
	}

	public function is_valid_alias($str)
	{
		$val = ($this->input->post('action_type')=='update')?1:0;

		$res = $this->page_model->check_alias($str);
		if ($res > $val)
		{
			$this->form_validation->set_message('is_valid_alias', 'The %s field needs to be unique');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function add()
	{


		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('alias', 'Menu alias', 'required|callback_is_valid_alias');
		$this->form_validation->set_rules('show_in_menu', 'Show in menu', 'required');
		$this->form_validation->set_rules('content_from', 'Content from', 'required');
		if($this->input->post('crawl_after')!='')
		$this->form_validation->set_rules('crawl_after', 'Crawl after', 'numeric|greater_than[0]');
		
		if ($this->form_validation->run() == FALSE)
		{
			if($this->input->post('action_type')=='update')
				$this->manage($this->input->post('id'));
			else
				$this->index();	
		}
		else
		{
			$data['title'] 			= $this->input->post('title');
			$data['alias'] 			= $this->input->post('alias');
			$data['show_in_menu'] 	= $this->input->post('show_in_menu');
			$data['content_from'] 	= $this->input->post('content_from');
			if($data['content_from']=='Manual')
			$data['url'] 			= 'show/page/'.$data['alias'];
			else
			$data['url'] 			= $this->input->post('url');
			
			$data['url']			= rtrim($data['url'],"/");

			$data['layout']			= $this->input->post('layout');
			$data['parent'] 		= $this->input->post('parent');
			if($data['layout']==0)
			$data['sidebar']		= $this->input->post('leftbar');
			else if($data['layout']==1)
			$data['sidebar']		= $this->input->post('rightbar');
			else
			$data['sidebar']		= '';
			$data['content']		= $this->input->post('content');
			$data['status']			= $this->input->post('action');
			
			$seo = array();
			$seo['meta_description'] 	= $this->input->post('meta_description');
			$seo['key_words'] 			= $this->input->post('key_words');
			$seo['crawl_after'] 		= $this->input->post('crawl_after');
			$data['seo_settings']		= json_encode($seo);

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				if($this->input->post('action_type')=='update')
				{
					$id = $this->input->post('id');
					// echo "<pre>";
					// print_r($_POST);
					// die;
					$this->page_model->update_page($data,$id);
					$this->session->set_flashdata('msg', '<div class="alert alert-success">Page updated</div>');				
				}
				else
				{
					$id = $this->page_model->insert_page($data);
					$this->session->set_flashdata('msg', '<div class="alert alert-success">Page Created</div>');				
				}				
			}	

			redirect(site_url('admin/page/manage/'.$id));		
		}

	}
	

	public function delete($page='0',$id='',$confirmation='')
	{
		if($confirmation=='')
		{
			$data['content'] = $this->load->view('admin/confirmation_view',array('id'=>$id,'url'=>site_url('admin/page/delete/'.$page)),TRUE);
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
					$this->page_model->delete_page_by_id($id);
					$this->session->set_flashdata('msg', '<div class="alert alert-success">Page Deleted</div>');					
				}
			}
			redirect(site_url('admin/page/all/'.$page));		
			
		}		
	}

	#load all services view with paging
	public function all($start='0')
	{
		$value['posts']  	= $this->page_model->get_all_pages_by_range($start,$this->per_page,'create_time');
		$total 				= $this->page_model->count_all_pages();
		$value['pages']		= configPagination('admin/page/all',$total,5,$this->per_page);
        $data['title'] = 'All Pages';
        $data['content'] = $this->load->view('admin/pages/allpages_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);		
	}


	public function menu($start=0)
	{
        $data['title'] = 'Menu';
        $data['content'] = $this->load->view('pages/menu_view','',TRUE);
		$this->load->view('admin/template/template_view',$data);			
	}

	public function update_menu()
	{
		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			add_option('top_menu',$this->input->post('top_menu'));
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Menu updated</div>');			
		}
		redirect(site_url('admin/page/menu'));		
	}

}

/* End of file page_core.php */
/* Location: ./application/modules/admin/controllers/page_core.php */