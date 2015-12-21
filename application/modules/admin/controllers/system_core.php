<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento System Controller
 *
 * This class handles System management related functionality
 *
 * @package		Admin
 * @subpackage	System
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

require_once'translate.php';
class System_core extends CI_Controller {
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
							
		$this->load->model('system_model');
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');
	}
	
	public function index()
	{
		$this->allbackups();
	}
	
	#load all db backups view
	public function allbackups($start=0)
	{	
		$this->load->helper('directory');
		$map = directory_map('./assets/backups');
		$value['posts'] = $map;
        $data['title'] = 'Manage Backups';
        $data['content'] = $this->load->view('admin/system/allbackups_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);		
	}

	#create db backup
	public function createbackup()
	{
		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->system_model->create_db_backup();		
		}
		redirect(site_url('admin/system/allbackups'));
	}
	
	#restore db from a backup file
	public function restoredb($key=0)
	{
		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->load->helper('directory');
			$map = directory_map('./assets/backups');
			$file = $map[$key];
			$this->system_model->restore_db_backup($file);
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Database Restored</div>');					
		}

		redirect(site_url('admin/system/allbackups'));
	}
	
	#download a backup file
	public function dlbackup($key=0)
	{		
		$this->load->helper('directory');
		$this->load->helper('file');
		$map = directory_map('./assets/backups');
		$file = $map[$key];
		$backup = read_file('assets/backups/'.$file);
	    # Load the download helper and send the file to your desktop
        $this->load->helper('download');
        force_download($file, $backup);	
	}
	
	#delete a db backup
	public function deletebackup($key)
	{
		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->load->helper('directory');
			$map = directory_map('./assets/backups');
			$file = $map[$key];
			unlink('./assets/backups/'.$file);
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Backup Deleted</div>');					
		}
		redirect(site_url('admin/system/allbackups'));		
	}
	
	#load webadmin settings , settings are saved as json data
	public function settings($key='webadmin_email')
	{
		$this->load->model('options_model');
		
		$settings = $this->options_model->getvalues($key);
		if($settings=='')
		{
			$settings = array('contact_email'=>'','webadmin_email'=>'');
		}
		$settings = json_encode($settings);		
		$value['settings'] = $settings;
        $data['title'] = 'Admin Settings';
        $data['content'] = $this->load->view('admin/settings/default_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);			
	}
	
	#save webadmin settings
	public function savesettings($key='webadmin_email')
	{
		$this->load->model('options_model');
	
		foreach($_POST as $key=>$value)
		{
			$this->form_validation->set_rules($key,$key,'required');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->settings($key);	
		}
		else
		{	
			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				$data['values'] 	= json_encode($_POST);		
				$res = $this->options_model->getvalues($key);
				if($res=='')
				{
					$data['key']	= $key;			
					$this->options_model->addvalues($data);
				}
				else
					$this->options_model->updatevalues($key,$data);
						
				
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data Updated</div>');				
			}

			redirect(site_url('admin/system/settings/'.$key));		
		}			
	}
	#********* smtp email settings ************#
	#load webadmin settings , settings are saved as json data
	public function smtpemailsettings()
	{
		$value = array();
        $data['title'] = 'SMPT Email Settings';	
        $data['content'] = $this->load->view('admin/settings/smtp_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);			
	}
	
	#save webadmin settings
	public function savesmtpemailsettings()
	{
		$this->load->model('options_model');
	
		foreach($_POST as $key=>$value)
		{
			$this->form_validation->set_rules($key,$key,'required');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->smtpemailsettings();	
		}
		else
		{	
			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				$key = 'smtp_settings';
				$data['values'] 	= json_encode($_POST);		
				$res = $this->options_model->getvalues($key);
				if($res=='')
				{
					$data['key']	= $key;			
					$this->options_model->addvalues($data);
				}
				else
					$this->options_model->updatevalues($key,$data);
				
				if($this->input->post('smtp_email')=='Enable')
				{
					$this->load->helper('file');
					$data = 	'<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");'."\n".''
								 .'$config["protocol"]="smtp";'."\n".''
								 .'$config["smtp_host"]="'.$this->input->post('smtp_host').'";'."\n".''
								 .'$config["smtp_port"]="'.$this->input->post('smtp_port').'";'."\n".''
								 .'$config["smtp_timeout"]="'.$this->input->post('smtp_timeout').'";'."\n".''
								 .'$config["smtp_user"]="'.$this->input->post('smtp_user').'";'."\n".''
								 .'$config["smtp_pass"]="'.$this->input->post('smtp_pass').'";'."\n".''
								 .'$config["charset"]="'.$this->input->post('char_set').'";'."\n".''
								 .'$config["newline"]="'.$this->input->post('new_line').'";'."\n".''
								 .'$config["mailtype"]="'.$this->input->post('mail_type').'";'."\n".'';
 
					if ( ! write_file('./application/config/email.php', $data))
					{
					     $this->session->set_flashdata('msg', '<div class="alert alert-danger">Unable to write file[ROOT/application/config/email.php]</div>');
					}
					else
					{
					     $this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Data updated').'</div>');
					}
				}	
				else
				{
					unlink('./application/config/email.php');
				}	
				
								
			}

			redirect(site_url('admin/system/smtpemailsettings/'));		
		}			
	}


	
	#********* lang function *************#
	
	public function editasnewlang($id='')
	{
		if($this->input->post('sel_lang')!='')
		$id = $this->input->post('sel_lang');
		$all_langs = $this->system_model->get_all_langs();
		$lang = $this->system_model->get_lang_by_id($id);
		$data['title'] = 'New language';
		$data['content'] = $this->load->view('admin/langeditor/edit_as_new_lang_view',array('all_langs'=>$all_langs,'lang'=>$lang),TRUE);
		$this->load->view('admin/template/template_view',$data);
	}
	
	public function addlang()
	{
		$lang_data = array();
		foreach($_POST['lang_key'] as $key=>$value)
		{
			$lang_data[$value] = $_POST['lang_text'][$key];
		}
		
		
		$data['lang'] 		= $this->input->post('lang');
		$data['file'] 		= $this->input->post('file');
		$data['unique_id'] 	= $data['lang'].'-'.$data['file'];
		$data['values'] 	= json_encode($lang_data);
		$data['status'] 	= 1;
		
		$id = $this->system_model->addlang($data);
		
		$this->session->set_flashdata('msg', '<div class="alert alert-success">Data Inserted</div>');
		redirect(site_url('admin/system/editlang/'.$id));		
	}
	
	public function editlang($id='')
	{
		if($this->input->post('sel_lang')!='')
		$id = $this->input->post('sel_lang');
		$all_langs = $this->system_model->get_all_langs();
		$lang = $this->system_model->get_lang_by_id($id);
        $data['title'] = ($id=='')?'Manage Language':'Edit language';
        $data['content'] = $this->load->view('admin/langeditor/lang_view',array('all_langs'=>$all_langs,'lang'=>$lang),TRUE);
		$this->load->view('admin/template/template_view',$data);
	}
	
	public function updatelang()
	{
		$this->form_validation->set_rules('lang',	'Lang name', 'required');
		$this->form_validation->set_rules('short_name',	'Short name', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->editlang($this->input->post('id'));	
		}
		else
		{

			$lang_data = array();
                        //print_r($_POST['lang_text']);exit;
			foreach($_POST['lang_key'] as $key=>$value)
			{
				$lang_data[$value] = $_POST['lang_text'][$key];
			}
                        
                       // print_r($lang_data);exit;
			
			$data['lang'] 		= $this->input->post('lang');
			$data['short_name'] = $this->input->post('short_name');
			$data['values'] 	= json_encode($lang_data);
			$data['status'] 	= 1;
			
			$id = $this->input->post('id');
			$this->system_model->update_lang_data($data,$id);
			
			$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Data updated').'</div>');
			redirect(site_url('admin/system/editlang/'.$id));		
		}
	}
	
	function deletelang($id='',$confirmation='')
	{
		if($confirmation=='')
		{
			$data['content'] = $this->load->view('admin/confirmation_view',array('id'=>$id,'url'=>site_url('admin/system/deletelang')),TRUE);
			$this->load->view('admin/template/template_view',$data);
		}
		else
		{
			if($confirmation=='yes')
			{
				$this->system_model->delete_lang_by_id($id);
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data Deleted</div>');
			}
			redirect(site_url('admin/system/editlang/'));								
		}		
	}
	
	#********* lang functions for developer use ************#
	public function newlang()
	{
        $data['title'] = 'New Language';
        $data['content'] = $this->load->view('admin/langeditor/newlang_view','',TRUE);
		$this->load->view('admin/template/template_view',$data);
	}
	
	function check_unique_short_name($str)
	{
		$res  = $this->system_model->is_lang_short_name_unique($str);
		if ($res>0)
		{
			$this->form_validation->set_message('check_unique_short_name', 'Short name needs to be unique');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function savelang()
	{
		$this->form_validation->set_rules('lang',	'Lang name', 'required');
		$this->form_validation->set_rules('short_name',	'Short name', 'required|callback_check_unique_short_name');
		
		if ($this->form_validation->run() == FALSE)
		{
			$id = $this->input->post('id');
			if($id=='')
			$this->newlang();	
			else
			$this->editasnewlang($id);
		}
		else
		{
			$lang_data = array();
			foreach($_POST['lang_key'] as $key=>$value)
			{
				$lang_data[$value] = $_POST['lang_text'][$key];
			}
			
			
			$data['lang'] 		= $this->input->post('lang');
			$data['short_name'] = $this->input->post('short_name');
			$data['unique_id'] 	= $data['lang'].'-'.$data['short_name'];
			$data['values'] 	= json_encode($lang_data);
			$data['status'] 	= 1;
			
			$this->system_model->addlang($data);
			
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data Inserted</div>');
			redirect(site_url('admin/system/newlang/'));					
		}
	}

	
	#*************** site settings  *****************#
	#load site settings , settings are saved as json data
	public function sitesettings($key='site_settings',$msg='')
	{
		if($msg=='error')
			$value['msg'] = '<div class="alert alert-danger">Error occured. Please check the fields below.</div>';
		$this->load->model('options_model');
		
		$settings = $this->options_model->getvalues($key);
		if($settings=='')
		{
			$settings = array('site_title'=>'','site_lang'=>'');
		}
		$settings = json_encode($settings);		
		$value['settings'] = $settings;
		$value['langs']    = $this->system_model->get_all_lang();
        $data['title'] = 'Site Settings';
        $data['content']   = $this->load->view('admin/settings/site_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);			
	}
	
	#save site settings
	public function savesitesettings($key='site_settings')
	{
		$this->load->model('options_model');
	
		foreach($_POST as $k=>$value)
		{
			if($k!='ga_tracking_code')
			$this->form_validation->set_rules($k,$k,'required');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->sitesettings($key,'error');	
		}
		else
		{	
			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				$data['values'] 	= json_encode($_POST);		
				$res = $this->options_model->getvalues($key);
				if($res=='')
				{
					$data['key']	= $key;			
					$this->options_model->addvalues($data);
				}
				else
					$this->options_model->updatevalues($key,$data);
						
				
				$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Data updated').'</div>');
				
			}
			$current_lang =  $this->uri->segment(1);
			$url = site_url('admin/system/sitesettings/'.$key);
			$url = str_replace('/'.$current_lang.'/','/'.default_lang().'/', $url);
			redirect($url);		
		}			
	}
	
	#*************** site settings  *****************#
	#load email templates , settings are saved as json data
	public function emailtmpl($id='')
	{
		$value['email']		= $this->system_model->get_email_by_id($id);
		$value['emails']  	= $this->system_model->get_all_emails();
        $data['title'] = 'Edit Email Text';
        $data['content']   	= $this->load->view('admin/emailtmp/email_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);			
	}
	
	public function updateemail()
	{
		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$email = array();
			$email['subject'] = $this->input->post('subject');
			$email['body'] = $this->input->post('body');
			$email['avl_vars'] = $this->input->post('avl_vars');
			
			$data['values'] = json_encode($email);
			$data['status'] = 1;
			
			$id = $this->input->post('id');
			$this->system_model->update_email_tmpl($data,$id);
			
			$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Data updated').'</div>');			
		}
		redirect(site_url('admin/system/emailtmpl/'.$id));		
		
	}

	public function site_logo_uploader()
	{
		$this->load->view('admin/settings/logo_uploader_view');
	}
	

	public function upload_logo()
	{
		$config['upload_path'] = './assets/images/logo/';
		$config['allowed_types'] = 'gif|jpg|JPG|png';
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
			$media['media_url']  		= base_url().'assets/images/logo/'.$data['file_name'];
			$media['create_time'] 		= standard_date($format, $time);
			$media['status']			= 1;
			
			//create_square_thumb('./uploads/profile_photos/'.$data['file_name'],'./uploads/profile_photos/thumb/');

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

	public function translate()
	{
		$value['all_langs']	= $this->system_model->get_all_langs();
        $data['title'] 		= 'Auto translate';
		$data['content']   	= $this->load->view('admin/langeditor/translate_view',$value,TRUE);
		$this->load->view('admin/template/template_view',$data);
	}
	

	#save webadmin settings
	public function translatelang()
	{
		ini_set('max_execution_time', 1200);
		$this->load->model('system_model');
	
	
		$this->form_validation->set_rules('base_lang','base lang','required');
		$this->form_validation->set_rules('target_lang_full_name','Taget lang full name','required');
		$this->form_validation->set_rules('target_lang_short_name','Taget lang short name','required');
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->translate();	
		}

		else
		{	

			$base_lang				= $this->input->post('base_lang');
			$target_lang_full_name	= $this->input->post('target_lang_full_name');
			$target_lang_short_name	= $this->input->post('target_lang_short_name');

			$target_lang	=$this->input->post('target_lang');
			$base_seg 	= explode('-',$base_lang);

			$translator = new Translate();	

			$lang_data = $this->system_model->get_language_data($base_lang);
			$lang_data_array = json_decode($lang_data->values);
			$translate_array = $translator->get_translated_data_array($base_seg[1],$target_lang_short_name,$lang_data_array);


			$translated_data['unique_id']	= $target_lang_full_name.'-'.$target_lang_short_name;
			$translated_data['lang']		= $target_lang_full_name;
			$translated_data['short_name']	= $target_lang_short_name;
			$translated_data['values']		= json_encode($translate_array);
			$translated_data['status']		= 1;
			$this->system_model->add_or_update_lang($translated_data);
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Language transalted</div>');

			$this->load->library('yaml');
			$yaml = $this->yaml->dump($translate_array);

			$this->load->helper('file');
			$file_name = $target_lang_short_name.'.yml';

			if ( ! write_file('./dbc_config/locals/'.$file_name, $yaml))
			{
			     $this->session->set_flashdata('msg', '<div class="alert alert-danger">Unable to write file</div>');
			}
			else
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Language transalted</div>');
			}


			redirect(site_url('admin/system/translate/'));
		}			
	}

	function importlangfile($id='')
	{
		$query = $this->system_model->get_lang_by_id($id);
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$file_name = $row->short_name.'.yml';
			$this->load->library('yaml');
			$lang =  $this->yaml->parse_file('./dbc_config/locals/'.$file_name);

			$value = array();
			$value['values'] = json_encode($lang);
			$this->system_model->update_lang_data($value,$id);
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Language imported</div>');
			redirect(site_url('admin/system/editlang/'.$id));

		}
	}

	function exportlangfile($id='')
	{
		$query = $this->system_model->get_lang_by_id($id);
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$lang = json_decode($row->values);
			
			
			$this->load->library('yaml');
			$yaml = $this->yaml->dump($lang);

			$this->load->helper('file');
			$file_name = $row->short_name.'.yml';

			if ( ! write_file('./dbc_config/locals/'.$file_name, $yaml))
			{
			     $this->session->set_flashdata('msg', '<div class="alert alert-danger">Unable to write file</div>');
			}
			else
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Language Exported</div>');
			}

			redirect(site_url('admin/system/editlang/'.$id));

		}
	}

	function createimgbackup()
	{
		$value = getdate();
        $today = $value['year']."-".$value['mon']."-".$value['mday'];

		$this->zipDir('./uploads','./assets/backups/uploads('.$today.').zip');
		$this->session->set_flashdata('msg', '<div class="alert alert-success">Image backup created</div>');
		redirect(site_url('admin/system/'));
	}

	public function zipDir($sourcePath, $outZipPath)
	{
	    $pathInfo = pathInfo($sourcePath);
	    $parentPath = $pathInfo['dirname'];
	    $dirName = $pathInfo['basename'];

	    $z = new ZipArchive();
	    $z->open($outZipPath, ZIPARCHIVE::CREATE);
	    $z->addEmptyDir($dirName);
	    $this->folderToZip($sourcePath, $z, strlen("$parentPath/"));
	    $z->close();
	}

	private function folderToZip($folder, &$zipFile, $exclusiveLength) {
	    $handle = opendir($folder);
    	while (false !== $f = readdir($handle)) {
	      if ($f != '.' && $f != '..') {
	        $filePath = "$folder/$f";
	        // Remove prefix from file path before add to zip.
	        $localPath = substr($filePath, $exclusiveLength);
	        if (is_file($filePath)) {
	          $zipFile->addFile($filePath, $localPath);
	        } elseif (is_dir($filePath)) {
	          // Add sub-directory.
	          $zipFile->addEmptyDir($localPath);
	          self::folderToZip($filePath, $zipFile, $exclusiveLength);
	        }
	      }
    	}
    	closedir($handle);
  } 

  function test()
  {
  	echo '<meta charset="utf-8">';
  	$text = 'hello how are you';
  	$translator = new Translate();	
  	$translator->setLangFrom('en');
  	$translator->setLangTo('es');
  	echo $translator->mm_translate($text);
  }

    /* Generate Site Map start*/

    public function generatesitemap()
    {

        $data['title']      = 'Generate Site map';
        $data['content']   	= $this->load->view('admin/sitemap/site_map_panel_view','',TRUE);
        $this->load->view('admin/template/template_view',$data);
    }
    function get_site_map_xml()
    {
        $page_checked       = $this->input->post('pages');
        $blog_post_checked  = $this->input->post('blog_post');
        $estate_checked     = $this->input->post('estate');

        $xml_array=array();

        if($page_checked==1 or $blog_post_checked==2 or $estate_checked==3)
        {

            if($page_checked==1)
            {

                $menu = get_option('top_menu');
                $menu=json_decode($menu->values);
                $page_url_array=array();
                foreach($menu as $row)
                {
                    $id                 = $row->id;
                    $url                = $this->get_page_url_by_id($id);
                    $page_url_array[]   = $url;
                }
                if($page_url_array)
                {
                    $xml_array = $page_url_array;
                }
            }

            if($blog_post_checked == 2){
                $this->load->model('show/show_model');
                $blog_post = $this->show_model->get_all_active_blog_posts_by_range('all','all','id','desc','all');

                if($blog_post->num_rows()>0)
                {
                    $blog_post_array = array();
                    foreach($blog_post->result() as $row){
                        $post_id    = $row->id;
                        $post_title = $row->title;
                        $url        = site_url('show/postdetail/'.$post_id.'/'.dbc_url_title($post_title));
                        $blog_post_array[] = $url;
                    }
                }

                if($blog_post_array){
                    $xml_array = array_merge($xml_array,$blog_post_array);
                }

            }

            if($estate_checked==3){
                $estate_array = array();
                $this->load->model('realestate_model');

                $estate_data = $this->realestate_model->get_all_estates_admin('all','all','create_time','desc');

                if($estate_data->num_rows()>0)
                {
                    foreach($estate_data->result() as $row)
                    {
                        $curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
                        $title = get_title_for_edit_by_id_lang($row->id,$curr_lang);
                        $unique_id = $row->unique_id;
                        $site_title = dbc_url_title($title);
//                        $site_title = str_replace('&', '-', $site_title);
                        $site_title = preg_replace('/[^\p{L}\s\p{N}]+/u', '', $site_title);
                        $url=site_url('property/'.$unique_id.'/'.$site_title);
                        $estate_array[]=$url;
                    }
                }

                if($estate_array){
                    $xml_array = array_merge($xml_array,$estate_array);
                }
            }

            $xml = $this->prepare_xml($xml_array);

            $this->load->helper('file');
            if ( ! write_file('./sitemap.xml',$xml))
            {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">ROOT/sitemap.xml does not have write permission .</div>');
                redirect(site_url('admin/system/generatesitemap'));
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Site map generated successfully .</div>');
            redirect(site_url('admin/system/generatesitemap'));
        }
        else{
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Please select on or more select options .</div>');
            redirect(site_url('admin/system/generatesitemap'));
        }

    }

    function prepare_xml($xml_array='')
    {
        $xml = '';
        if(isset($xml_array) && is_array($xml_array))
        {
            $xml = '<?xml version="1.0" encoding="UTF-8" ?>'.
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
                     <url>
                         <loc>'.base_url().'</loc>
                            <priority>1.0</priority>
                        </url>';

            foreach($xml_array as $url)
            {
                $xml .= "<url>
                            <loc>".$url."</loc>
                            <priority>0.5</priority>
                        </url>";
            }
            $xml .= "</urlset>";
        }
        return $xml;
    }

    function get_page_url_by_id($id='')
    {
        $this->load->model('page_model_core');
        $page = $this->page_model_core->get_page_by_id($id);

        if($page->content_from=='Url')
        {
            $url = site_url($page->url);
        }
        else
        {
            $url = site_url('show/page/'.$page->alias);
        }
        return $url;
    }

	 public function translate_title_description_ajax(){
	  	/*during creating property*/
	  	$title = $this->input->post('title');
	  	$desc = $this->input->post('desc');
	  	$lang = $this->input->post('lang');
	  	$lang = trim($lang);
	  	//print $lang;die;
	  	$translator = new Translate();
	  	$translator->setLangFrom($lang);
	  	$array = array();
	  	$value['result'] = $this->system_model->get_all_langs();
	  	foreach($value['result']->result() as $va){
	  		
	  	 if($va->short_name!=$lang){
	  	 	$title1='';
	  		$description1 = '';
	  	 	$translator->setLangTo($va->short_name);
	  	 	$title1 =  $translator->mm_translate($title);
	  	 	$description1 = $translator->mm_translate($desc);
	  	 	array_push($array,array('lang'=>$va->short_name,'title'=>$title1,'description'=>$description1));
	  	 }
	  	}
	  	echo json_encode($array);
	  }
    /*Generate Site Map End*/

  public function add_lang_all()
  {

  	$lang_data_array = array('property_price'=>'Total Price',
  				  			 'down_payment'=>'Down Payment',
  				  			 'term_in_years'=>'No of Years',
  				  			 'annual_interest'=>'Interest Per Year(%)',
  				  			 'installments_per_year'=>'Installment Per Year',
  				  			 'monthly_payment'=>'Monthly Payment'
  						);

	$this->load->model('admin/system_model');
    $query = $this->system_model->get_all_langs();
    $translator = new Translate();

    foreach ($query->result() as $lang) {   

        $translate_array = $translator->get_translated_data_array('en',$lang->short_name,$lang_data_array);

        $prev_data = (array)json_decode($lang->values);
        $prev_data = array_merge($prev_data,$translate_array);

        $translated_data['unique_id']	= $lang->lang.'-'.$lang->short_name;
        $translated_data['lang']		= $lang->lang;
        $translated_data['short_name']	= $lang->short_name;
        $translated_data['values']		= json_encode($prev_data);
        $translated_data['status']		= 1;
        $this->system_model->add_or_update_lang($translated_data);
    }

  }
}

/* End of file system.php */
/* Location: ./application/modules/admin/controllers/admin/system.php */