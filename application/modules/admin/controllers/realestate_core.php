<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * realestate realestate Controller
 *
 * This class handles only realestate related functionality
 *
 * @package		Admin
 * @subpackage	realestate
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */





class Realestate_core extends CI_Controller {

	var $per_page = 10;

		

	public function __construct()

	{

		parent::__construct();

                
                $curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
                if ($curr_lang == 'ar'){
                    $this->config->set_item('language', 'arabic');
                }
                
		is_installed(); #defined in auth helper

		remove_featured_if_expired();
		
		checksavedlogin(); #defined in auth helper

		if(!is_admin() && !is_agent())

		{

			if(count($_POST)<=0)

			$this->session->set_userdata('req_url',current_url());

			redirect(site_url('admin/auth'));

		}



		$this->per_page = get_per_page_value();#defined in auth helper

		$this->load->helper('text');

		$this->load->model('show/show_model');

		$this->load->model('admin/realestate_model');

		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

	}

	

	public function index()

	{

		if(!is_admin())
		{
			$this->allestatesagent();
		}
		else
			$this->allestates();

	}



	

	#approve a post

	public function approvepost($page='0',$from='activeposts',$id='',$confirmation='')

	{
		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->post_model->update_post_by_id(array('status'=>1),$id);

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Approved</div>');			
		}

		redirect(site_url('admin/memento/'.$from.'/'.$page));		

	}



	#delete a properties

	public function deleteestate($page='0',$id='',$confirmation='')

	{

		if(!is_admin() && !is_agent())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		if($confirmation=='')

		{

			$data['content'] = $this->load->view('admin/confirmation_view',array('id'=>$id,'url'=>site_url('admin/realestate/deleteestate/'.$page)),TRUE);

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
					$this->realestate_model->delete_post_by_id($id);

					$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Deleted</div>');					
				}

			}

			if(is_admin())
			redirect(site_url('admin/realestate/allestates/'.$page));		
			else
			redirect(site_url('admin/realestate/allestatesagent/'.$page));		

			

		}		

	}



	public function approveestate($page='0',$id='',$confirmation='')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission for this action';
			die;
		}

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->realestate_model->update_post_by_id(array('status'=>1),$id);

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Approved</div>');			
		}

		redirect(site_url('admin/realestate/allestates/'.$page));		

	}

	public function reactivateestate($page='0',$id='',$confirmation='')

	{

		if(!is_admin() && !is_agent())
		{
			echo 'You don\'t have permission for this action';
			die;
		}

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$data = array();
			if(is_admin())
			{
				$data['status'] = 1;
			}
			else
			{
				$publish_directly 		= get_settings('realestate_settings','publish_directly','Yes');			
				$data['status']			= ($publish_directly=='Yes')?1:2; // 2 = pending				
			}

			$this->realestate_model->update_post_by_id($data,$id);

			if($data['status']==1)
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Approved</div>');	
			else		
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Post activated and now waiting for admin approval</div>');	
		}

		if(is_admin())
		redirect(site_url('admin/realestate/allestates/'.$page));		
		else
		redirect(site_url('admin/realestate/allestatesagent/'.$page));		

	}




	#feature a service

	public function featurepost($page='0',$id='',$confirmation='')

	{
		if(!is_admin())
		{
			echo 'You don\'t have permission for this action';
			die;
		}


		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->realestate_model->update_post_by_id(array('featured'=>1),$id);

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Featured</div>');			
		}

		redirect(site_url('admin/realestate/allestates/'.$page));		

	}


	public function featurepayment($page='0',$id='')

	{
		$this->load->helper('date');
		$datestring = "%Y-%m-%d";
		$time = time();
		$request_date = mdate($datestring, $time);

		$data = array();
		$data['unique_id']      = uniqid();
		$data['amount'] 		= get_settings('realestate_settings','feature_charge','0');
		$data['currency']   	= get_settings('paypalsettings','currency','USD');
		$data['daylimit']   	= get_settings('realestate_settings','feature_day_limit','0');
		$data['requestdate']    = $request_date;
		$data['activation_date']= '';
		$data['expirtion_date'] = '';
		$data['user_id']      	= $this->session->userdata('user_id');
		$data['medium']      	= 'paypal';
		$data['is_active']      = 0;
		
		$this->session->set_userdata('unique_id',$data['unique_id']);
		add_post_meta($id,'featurepayment_'.$data['unique_id'],json_encode($data));

		$value['post'] 		= $this->realestate_model->get_estate_by_id($id);

	    $data['title'] 		= 'Pay for feature';

        $data['content']  	= $this->load->view('admin/estate/feature_payment_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);			

	}


	#feature a service

	public function removefeaturepost($page='0',$id='',$confirmation='')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission for this action';
			die;
		}

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->realestate_model->update_post_by_id(array('featured'=>0),$id);

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Post Un-Featured</div>');			
		}

		redirect(site_url('admin/realestate/allestates/'.$page));		

	}





	public function bulkapprove($from='activeposts')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}


		$data['status'] = 1;

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->post_model->bulk_update_post($data,$this->input->post('id'));

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Posts approved</div>');			
		}

		redirect(site_url('admin/memento/'.$from));			

	}



	public function bulkdelete($from='activeposts')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$data['status'] = 0;

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->post_model->bulk_update_post($data,$this->input->post('id'));

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Posts deleted</div>');			
		}

		redirect(site_url('admin/memento/'.$from));			

	}


	#load site settings , settings are saved as json data

	public function realestatesettings($key='realestate_settings')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->load->model('admin/system_model');

		$this->load->model('options_model');

		

		$settings = $this->options_model->getvalues($key);

		$settings = json_encode($settings);		

		$value['settings'] 	= $settings;

	    $data['title'] 		= 'realestate Settings';

        $data['content']  	= $this->load->view('admin/estate/settings_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);			

	}

	

	#save site settings

	public function saverealestatesettings($key='realestate_settings')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->load->model('admin/system_model');

		$this->load->model('options_model');

	

		foreach($_POST as $k=>$value)

		{

			$rules = $this->input->post($k.'_rules');

			if($rules!='')

			$this->form_validation->set_rules($k,$k,$rules);

		}

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->realestatesettings($key);	

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

			redirect(site_url('admin/realestate/realestatesettings/'.$key));		

		}			

	}





	#load site settings , settings are saved as json data

	public function paypalsettings($key='paypal_settings')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->load->model('admin/system_model');

		$this->load->model('options_model');

		

		$settings = $this->options_model->getvalues($key);

		$settings = json_encode($settings);		

		$value['settings'] 	= $settings;

	    $data['title'] 		= 'Paypal Settings';

        $data['content']  	= $this->load->view('admin/estate/paypalsettings_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);			

	}

	

	#save site settings

	public function savepaypalsettings($key='paypal_settings')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->load->model('admin/system_model');

		$this->load->model('options_model');

	

		foreach($_POST as $k=>$value)

		{

			$rules = $this->input->post($k.'_rules');

			if($rules!='')

			$this->form_validation->set_rules($k,$k,$rules);

		}

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->paypalsettings($key);	

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

			redirect(site_url('admin/realestate/paypalsettings/'.$key));		

		}			

	}



	#load allestates view

	public function allestates($start=0)

	{
		if(!is_admin())
		{
			echo 'You dont have permission to access this page';
		}

		
		$value['posts']  	= $this->realestate_model->get_all_estates_by_user_type('all');

		$total 				= $this->realestate_model->get_all_estates_by_user_type('total');

                $data['title'] = lang_key('All estates');

		$data['content'] = $this->load->view('admin/estate/all_estates_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);		

	}



	public function finish_url()
	{
		$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('feature_payment_finish').'</div>');
		redirect(site_url('admin/realestate/allestatesagent/'));
	}

	public function cancel_url()
	{
		$this->session->set_flashdata('msg', '<div class="alert alert-warning">'.lang_key('feature_payment_cancel').'</div>');
		redirect(site_url('admin/realestate/allestatesagent/'));
	}

	#load allestates view

	public function allestatesagent($start=0)

	{

		$value['posts']  	= $this->realestate_model->get_all_estates_by_user_type('all');

		$total 				= $this->realestate_model->get_all_estates_by_user_type('total');

        $data['title'] 		= lang_key('All Estates');

		$data['content'] 	= $this->load->view('admin/estate/all_estates_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);		

	}



	#load edit estate form

	function editestate($page=0,$id='',$msg='')

	{
		if($msg=='error')
			$msg = '<div class="alert alert-danger">'.lang_key ('Missing required feilds. Please check the form below.').'</div>';



        $value['page']	 = $page;
        $value['msg']	 = $msg;
        $value['estate'] = $this->realestate_model->get_estate_by_id($id);

        $value['distance_info'] = $this->get_distance_info($id);

		$curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):default_lang();
        $title = get_title_for_edit_by_id_lang($value['estate']->id,$curr_lang);
		$detail_link = site_url('property/'.$value['estate']->unique_id.'/'.dbc_url_title($title));
        $data['title'] 	 = 'Edit estate (<a href="'.$detail_link.'" target="_blank">View Detail</a>)';

        $data['content'] = $this->load->view('admin/estate/edit_estate_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);

	}



	#insert estate

	public function updateestate()

	{

		$id 	= $this->input->post('id');

		$page 	= $this->input->post('page');

		if(!$this->realestate_model->check_post_permission($id))

		{

			$this->session->set_flashdata('msg', '<div class="alert alert-danger">'.lang_key('You don\'t have permission to update this').'</div>');

			redirect(site_url('admin/realestate/editestate/'.$page.'/'.$id));

		}



		$dl = default_lang();

		$this->config->load('realcon');
	    $enable_custom_fields = $this->config->item('enable_custom_fields');
	    if($enable_custom_fields=='Yes')
	    {
	    	$fields = $this->config->item('custom_fields');
	    	foreach ($fields as $field) 
	    	{
	    		if($field['validation']!='')
	    			$this->form_validation->set_rules($field['name'], $field['title'], $field['validation']);
	    	}
	    }

		$this->form_validation->set_rules('id', 'Id', 'required');

		$this->form_validation->set_rules('page', 'Page', 'required');		

		$this->form_validation->set_rules('title'.$dl, lang_key('title_estate'), 'required');

		$this->form_validation->set_rules('description'.$dl, lang_key('Description'), 'required');

                $this->form_validation->set_rules('category_type', lang_key('Category Type'), 'required');
                
		$this->form_validation->set_rules('type', lang_key('Type'), 'required');

		$this->form_validation->set_rules('purpose', lang_key('Purpose'), 'required');

		$purpose 	= $this->input->post('purpose');		

                $category_type 		= $this->input->post('category_type');
                
		$type 		= $this->input->post('type');



		$meta_search_text = '';		//meta information for simple searching



		if($purpose=='DBC_PURPOSE_SALE')

		{

			$this->form_validation->set_rules('total_price', lang_key('Sales Price'), 'required');

			//$this->form_validation->set_rules('price_per_unit', 'Price per Unit', 'required');

			//$this->form_validation->set_rules('price_unit', 'Price unit', 'required');	



			$meta_search_text .= 'sale'.' ';			

		}

		elseif($purpose=='DBC_PURPOSE_RENT')

		{

			$this->form_validation->set_rules('rent_price', lang_key('Rent Price'), 'required');				

			$this->form_validation->set_rules('rent_price_unit', lang_key('Rent Price unit'), 'required');				



			$meta_search_text .= 'sale'.' ';

		}

	
		#price validation end



		if($category_type=='DBC_TYPE_RESIDENTIAL')
		{

                        $this->form_validation->set_rules('home_size', lang_key('Home size'), 'required');
                        $this->form_validation->set_rules('home_size_unit', lang_key('Home size unit'), 'required');
                        $this->form_validation->set_rules('bedroom', lang_key('Bedrooms'), 'required');
                        $this->form_validation->set_rules('bath', lang_key('Bathroom'), 'required');
                        $this->form_validation->set_rules('year_built', lang_key('Year Built'), 'xss_clean');
			$meta_search_text .= 'residential'.' ' . lang_key($this->input->post('type')) . ' ';
		}
		else if($category_type=='DBC_TYPE_COMMERCIAL')
		{
			$this->form_validation->set_rules('lot_size', lang_key('Lot size'), 'required');
			$this->form_validation->set_rules('lot_size_unit', lang_key('Lot size unit'), 'required');
			$this->form_validation->set_rules('year_built', lang_key('Year Built'), 'xss_clean');
                        
                        if($type=='DBC_TYPE_OFFICE'){
                            $this->form_validation->set_rules('bath', lang_key('Bathroom'), 'required');
                        }
                        
			$meta_search_text .= 'commercial'.' ' . lang_key($this->input->post('type')) . ' ';
		}
		else if($category_type=='DBC_TYPE_LAND')
		{
			$this->form_validation->set_rules('lot_size', lang_key('Lot size'), 'required');
			$this->form_validation->set_rules('lot_size_unit', lang_key('Lot size unit'), 'required');
			$meta_search_text .= 'land'.' '.lang_key($this->input->post('type')) . ' ';
		}
                
                
                
                






		$this->form_validation->set_rules('condition', lang_key('Condition'), 'required');

		$this->form_validation->set_rules('address', lang_key('Address'), 'required');

		$this->form_validation->set_rules('country', lang_key('Country'), 'required');

		$this->form_validation->set_rules('selected_state', lang_key('City'), 'required');

		$this->form_validation->set_rules('city', lang_key('Region'), 'required');

		$this->form_validation->set_rules('latitude', lang_key('Latitude'), 'required');

		$this->form_validation->set_rules('longitude', lang_key('Longitude'), 'required');

		$this->form_validation->set_rules('featured_img', lang_key('Featured image'), 'required');





		if ($this->form_validation->run() == FALSE)

		{

			$this->editestate($page,$id,'error');	

		}

		else

		{

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{

				$data = array();

				$data['type'] 		= $this->input->post('type');

				$data['purpose'] 	= $this->input->post('purpose');

			
				if($purpose=='DBC_PURPOSE_SALE')

				{

					$data['total_price'] 		= $this->input->post('total_price');

					//$data['price_per_unit'] 	= $this->input->post('price_per_unit');

					//$data['price_unit'] 		= $this->input->post('price_unit');				

				}

				elseif($purpose=='DBC_PURPOSE_RENT')

				{

					$data['total_price'] 		= $this->input->post('rent_price');

					$data['rent_price'] 		= $this->input->post('rent_price');				

					$data['rent_price_unit'] 	= $this->input->post('rent_price_unit');				

				}

				else

				{

					$data['total_price'] 		= $this->input->post('total_price');

					//$data['price_per_unit'] 	= $this->input->post('price_per_unit');

					//$data['price_unit'] 		= $this->input->post('price_unit');				

					$data['rent_price'] 		= $this->input->post('rent_price');				

					$data['rent_price_unit'] 	= $this->input->post('rent_price_unit');								

				}

				#price validation end



				if($category_type=='DBC_TYPE_RESIDENTIAL')

				{

					$data['home_size'] 		= $this->input->post('home_size');
					$data['home_size_unit']         = $this->input->post('home_size_unit');
					$data['bedroom'] 		= $this->input->post('bedroom');
					$data['bath'] 			= $this->input->post('bath');
					$data['year_built'] 	= $this->input->post('year_built');
					$meta_search_text		.= 'bedroom '.$data['bedroom'].' bathroom '.$data['bath'].' year built '.$data['year_built'] . ' ';

				}
				else if($category_type=='DBC_TYPE_COMMERCIAL')
				{
                                        $data['lot_size'] 		= $this->input->post('lot_size');
					$data['lot_size_unit']          = $this->input->post('lot_size_unit');
					$data['bedroom'] 		= $this->input->post('bedroom');
					$data['bath'] 			= $this->input->post('bath');
					$data['year_built'] 	= $this->input->post('year_built');
					$meta_search_text		.= ' bedroom '.$data['bedroom'].' bathroom '.$data['bath'].' year built '.$data['year_built']  . ' ';

				}
				else if($type=='DBC_TYPE_LAND')
				{
					$data['lot_size'] 		= $this->input->post('lot_size');
					$data['lot_size_unit'] 	= $this->input->post('lot_size_unit');
				}






				$data['estate_condition'] 		= $this->input->post('condition');
				$meta_search_text		.= ' '.lang_key($data['estate_condition']);
				$data['address'] 		= $this->input->post('address');
				$meta_search_text		.= ' '.$data['address'];
				$data['country'] 		= $this->input->post('country');
				$meta_search_text		.= ' '.get_location_name_by_id($data['country']);
				$state_id 				= $this->realestate_model->get_location_id_by_name($this->input->post('selected_state'),'state',$data['country']);
				$data['state'] 			= $state_id;
				$meta_search_text		.= ' '.$this->input->post('selected_state');
				$city_name 				= $this->input->post('city');
                                $city_id                                = $this->realestate_model->get_location_id_by_name($this->input->post('city'),'city',$state_id);
				$data['city'] 			= $city_id;
				$meta_search_text		.= ' '.$city_name . ' ';


				$data['latitude'] 		= $this->input->post('latitude');

				$data['longitude'] 		= $this->input->post('longitude');

				$facilities = ($this->input->post('facilities')=='')?json_encode(array()):json_encode($this->input->post('facilities'));

				$data['facilities'] 	= $facilities;

				$data['featured_img'] 	= $this->input->post('featured_img');

				$data['gallery'] 		= json_encode($this->input->post('gallery'));



				$this->load->helper('date');

				$format = 'DATE_RFC822';

				$time 	= time();

				$datestring = "%Y-%m-%d";

				//$data['create_time'] 	= $time;
				$data['publish_time'] 	= mdate($datestring, $time);

                                $data['created_by']		= ($this->input->post('created_by') != '') ? $this->input->post('created_by') :$this->session->userdata('user_id');

				$data['status']			= 1;



				$this->realestate_model->update_estate($data,$id);



				$default_title 			= $this->input->post('title'.$dl);

				$meta_search_text		.= ' '.$default_title;



				$default_description 	= $this->input->post('description'.$dl);

				$meta_search_text		.= ' '.$default_description;


				$meta_search_text		.= $this->input->post('tags');

				#collecting meta information for simple searching is complete

				#now update the post table with the information

				$data = array();

	        	$data['search_meta'] = $meta_search_text;

	        	$this->realestate_model->update_estate($data,$id);



	            $this->load->model('admin/system_model');

	            $query = $this->system_model->get_all_langs();

	            $active_languages = $query->result();



	        	$data = array();

	        	$data['post_id'] 	= $id;

	        	$data['key']		= 'title';

	        	$data['status']	= 1;

	       

	       		$value = array();     

	            foreach ($active_languages as $row) {

	            	

	            	$title = $this->input->post('title'.$row->short_name);

	            	$value[$row->short_name] = $title;

	            }



	            $data['value'] = json_encode($value);

	            $this->realestate_model->update_estate_meta($data,$id,'title');



	        	$data = array();

	        	$data['post_id'] 	= $id;

	        	$data['key']		= 'description';

	        	$data['status']	= 1;

	       

	       		$value = array();     

	            foreach ($active_languages as $row) {

	            	

	            	$description = $this->input->post('description'.$row->short_name);

	            	$value[$row->short_name] = $description;

	            }



	            $data['value'] = json_encode($value);

	            $this->realestate_model->update_estate_meta($data,$id,'description');


	            add_post_meta($id,'tags',$this->input->post('tags'));
	            add_post_meta($id,'video_url',$this->input->post('video_url'));
                add_post_meta($id,'estate_brochure',$this->input->post('estate_brochure'));


	            if($purpose=='DBC_PURPOSE_RENT')
	            {
		            add_post_meta($id,'from_rent_date',$this->input->post('from_date'));
		            add_post_meta($id,'to_rent_date',$this->input->post('to_date'));            	
	            }

                #adding distance information
                $distance_ids = $this->input->post('distance_id');
                $distance_titles = $this->input->post('distance_title');
                $distance_icons = $this->input->post('distance_icon');
                $distance_values = $this->input->post('distance_value');
                $distance_units = $this->input->post('distance_unit');
                $i = 0;
                $vals = array();
                foreach ($distance_ids as $key => $value) {
                	$dis_info = array();
                	$dis_info['id'] = $distance_ids[$key];
                	$dis_info['title'] = $distance_titles[$key];
                	$dis_info['icon'] = $distance_icons[$key];
                	$dis_info['value'] = $distance_values[$key];
                	$dis_info['units'] = $distance_units[$key];
                	$vals[$i++] = json_encode($dis_info);
                }
                add_post_meta($id,'distance_info',json_encode($vals));

                if($enable_custom_fields=='Yes')
			    {
			    	$fields = $this->config->item('custom_fields');
			    	$data = array();
			    	foreach ($fields as $field) 
			    	{
			    		$data[$field['name']] = $this->input->post($field['name']);
			    	}
			    }
			    add_post_meta($id,'custom_values',json_encode($data));

			    
				$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Property Updated').'</div>');

			}

			redirect(site_url('admin/realestate/editestate/'.$page.'/'.$id));		

		}

	}



	function if_have_create_permission()

	{

		if(is_admin())

			return 1;



		if(get_settings('realestate_settings','enable_pricing','Yes')=='Yes')

		{

			$this->load->helper('date');

			$user_id = $this->session->userdata('user_id');

			$datestring = "%Y-%m-%d";

			$time  = time();

			$today = mdate($datestring, $time);

			if(strtotime($today)>strtotime(get_user_meta($user_id,'expirtion_date')))

				return 2;



			$user_package = get_user_meta($user_id,'current_package','');

			if($user_package=='')

				return 3;



			$this->load->model('admin/package_model');

			$package = $this->package_model->get_package_by_id($user_package);	


			if(get_user_meta($user_id,'post_count',0)+1>$package->max_post)

				return 4; 

			return 1;
		}

		else

			return 1;

	}



	#load new estate form

	function newestate($msg='')

	{
		if($msg=='error')
			$msg = '<div class="alert alert-danger">'.lang_key('Missing required fields. Please check the form below.').'</div>';

		$res = $this->if_have_create_permission();

        if($res!=1)

        {

        	if($res==2)

        		$this->session->set_flashdata('msg','<div class="alert alert-danger">You\'re package is expired. Please renew</div>');

        	elseif($res==3)

        		$this->session->set_flashdata('msg','<div class="alert alert-danger">No package data found. Please choose a package.</div>');

        	elseif($res==4)

        		$this->session->set_flashdata('msg','<div class="alert alert-danger">Your maximum posting limit is over. Please renew.</div>');

        	redirect(site_url('account/renew'));

        }

        

        $data['title'] = lang_key('Create New estate');

        $data['content'] = $this->load->view('admin/estate/new_estate_view',array('msg'=>$msg),TRUE);

		$this->load->view('admin/template/template_view',$data);



	}



	#insert estate

	public function addestate()

	{

		$dl = default_lang();

		$this->config->load('realcon');
                $enable_custom_fields = $this->config->item('enable_custom_fields');
                if($enable_custom_fields=='Yes')
                {
                    $fields = $this->config->item('custom_fields');
                    foreach ($fields as $field) 
                    {
                            if($field['validation']!='')
                                    $this->form_validation->set_rules($field['name'], $field['title'], $field['validation']);
                    }
                }

		$this->form_validation->set_rules('title'.$dl, lang_key('title_estate'), 'required');

		$this->form_validation->set_rules('description'.$dl, lang_key('Description'), 'required');

                $this->form_validation->set_rules('category_type', lang_key('Category Type'), 'required');
                
		$this->form_validation->set_rules('type', lang_key('Type'), 'required');

		$this->form_validation->set_rules('purpose', lang_key('Purpose'), 'required');

		$purpose 	= $this->input->post('purpose');		

		$type 		= $this->input->post('type');
                
                $category_type 		= $this->input->post('category_type');



		$meta_search_text = '';		//meta information for simple searching



		if($purpose=='DBC_PURPOSE_SALE')

		{

			$this->form_validation->set_rules('total_price', lang_key('Sales Price'), 'required');

			//$this->form_validation->set_rules('price_per_unit', 'Price per Unit', 'required');

			//$this->form_validation->set_rules('price_unit', 'Price unit', 'required');



			$meta_search_text .= 'sale'.' ';				

		}

		elseif($purpose=='DBC_PURPOSE_RENT')

		{

			$this->form_validation->set_rules('rent_price', lang_key('Rent Price'), 'required');				

			$this->form_validation->set_rules('rent_price_unit', lang_key('Rent Price unit'), 'required');



			$meta_search_text .= 'rent'.' ';				

		}

	

		#price validation end



		if($category_type=='DBC_TYPE_RESIDENTIAL')
		{

                        $this->form_validation->set_rules('home_size', lang_key('Home size'), 'required');
                        $this->form_validation->set_rules('home_size_unit', lang_key('Home size unit'), 'required');
                        $this->form_validation->set_rules('bedroom', lang_key('Bed rooms'), 'required');
                        $this->form_validation->set_rules('bath', lang_key('Bathroom'), 'required');
                        $this->form_validation->set_rules('year_built', lang_key('Year Built'), 'xss_clean');
			$meta_search_text .= 'residential'.' ' . lang_key($this->input->post('type')) . ' ';
		}
		else if($category_type=='DBC_TYPE_COMMERCIAL')
		{
			$this->form_validation->set_rules('lot_size', lang_key('Lot size'), 'required');
			$this->form_validation->set_rules('lot_size_unit', lang_key('Lot size unit'), 'required');
			$this->form_validation->set_rules('year_built', lang_key('Year Built'), 'xss_clean');
                        
                        if($type=='DBC_TYPE_OFFICE'){
                            $this->form_validation->set_rules('bath', lang_key('Bathroom'), 'required');
                        }
                        
			$meta_search_text .= 'commercial'.' ' . lang_key($this->input->post('type')) . ' ';
		}
		else if($category_type=='DBC_TYPE_LAND')
		{
			$this->form_validation->set_rules('lot_size', lang_key('Lot size'), 'required');
			$this->form_validation->set_rules('lot_size_unit', lang_key('Lot size unit'), 'required');
			$meta_search_text .= 'land'.' '.lang_key($this->input->post('type')) . ' ';
		}



		$this->form_validation->set_rules('condition', lang_key('Condition'), 'required');
		$this->form_validation->set_rules('address', lang_key('Address'), 'required');
		$this->form_validation->set_rules('country', lang_key('Country'), 'required');
		$this->form_validation->set_rules('selected_state', lang_key('City'), 'required');
		$this->form_validation->set_rules('city', lang_key('Region'), 'required');
		$this->form_validation->set_rules('latitude', lang_key('Latitude'), 'required');
		$this->form_validation->set_rules('longitude', lang_key('Longitude'), 'required');
		$this->form_validation->set_rules('featured_img', lang_key('Featured image'), 'required');


		if ($this->form_validation->run() == FALSE)
		{
			$this->newestate('error');	
		}
		else
		{
			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{

				$data = array();
				$data['unique_id']	= uniqid();
				$data['type'] 		= $this->input->post('type');
                                $data['category_type'] 	= $this->input->post('category_type');
				$data['purpose'] 	= $this->input->post('purpose');

				
				if($purpose=='DBC_PURPOSE_SALE')
				{
					$data['total_price'] 		= $this->input->post('total_price');
					//$data['price_per_unit'] 	= $this->input->post('price_per_unit');
					//$data['price_unit'] 		= $this->input->post('price_unit');				
				}
				elseif($purpose=='DBC_PURPOSE_RENT')

				{
					$data['total_price'] 		= $this->input->post('rent_price');
					$data['rent_price'] 		= $this->input->post('rent_price');				
					$data['rent_price_unit'] 	= $this->input->post('rent_price_unit');				
				}
				else
				{
					$data['total_price'] 		= $this->input->post('total_price');
					//$data['price_per_unit'] 	= $this->input->post('price_per_unit');
					//$data['price_unit'] 		= $this->input->post('price_unit');				
					$data['rent_price'] 		= $this->input->post('rent_price');				
					$data['rent_price_unit'] 	= $this->input->post('rent_price_unit');								
				}
				#price validation end



				if($category_type=='DBC_TYPE_RESIDENTIAL')

				{

					$data['home_size'] 		= $this->input->post('home_size');
					$data['home_size_unit']         = $this->input->post('home_size_unit');
					$data['bedroom'] 		= $this->input->post('bedroom');
					$data['bath'] 			= $this->input->post('bath');
					$data['year_built'] 	= $this->input->post('year_built');
					$meta_search_text		.= 'bedroom '.$data['bedroom'].' bathroom '.$data['bath'].' year built '.$data['year_built'] . ' ';

				}
				else if($category_type=='DBC_TYPE_COMMERCIAL')
				{
                                        $data['lot_size'] 		= $this->input->post('lot_size');
					$data['lot_size_unit']          = $this->input->post('lot_size_unit');
					$data['bedroom'] 		= $this->input->post('bedroom');
					$data['bath'] 			= $this->input->post('bath');
					$data['year_built'] 	= $this->input->post('year_built');
					$meta_search_text		.= ' bedroom '.$data['bedroom'].' bathroom '.$data['bath'].' year built '.$data['year_built']  . ' ';

				}
				else if($type=='DBC_TYPE_LAND')
				{
					$data['lot_size'] 		= $this->input->post('lot_size');
					$data['lot_size_unit'] 	= $this->input->post('lot_size_unit');
				}



				$data['estate_condition'] 		= $this->input->post('condition');
				$meta_search_text		.= ' '.lang_key($data['estate_condition']);
				$data['address'] 		= $this->input->post('address');
				$meta_search_text		.= ' '.$data['address'];
				$data['country'] 		= $this->input->post('country');
				$meta_search_text		.= ' '.get_location_name_by_id($data['country']);
				$state_id 				= $this->realestate_model->get_location_id_by_name($this->input->post('selected_state'),'state',$data['country']);
				$data['state'] 			= $state_id;
				$meta_search_text		.= ' '.$this->input->post('selected_state');
				$city_name 				= $this->input->post('city');
                                $city_id                                = $this->realestate_model->get_location_id_by_name($this->input->post('city'),'city',$state_id);
				$data['city'] 			= $city_id;
				$meta_search_text		.= ' '.$city_name . ' ';
                                
                                
                           
                                
				
				$data['latitude'] 		= $this->input->post('latitude');
				$data['longitude'] 		= $this->input->post('longitude');
				$data['facilities'] 	= json_encode($this->input->post('facilities'));
				$data['featured_img'] 	= $this->input->post('featured_img');

				$this->load->helper('date');

				$format = 'DATE_RFC822';

				$time = time();

				$datestring = "%Y-%m-%d";


				$data['create_time'] 	= $time;
				$data['publish_time'] 	= mdate($datestring, $time);

				$data['created_by']		= ($this->input->post('created_by') != '') ? $this->input->post('created_by') :$this->session->userdata('user_id');

				$publish_directly 		= get_settings('realestate_settings','publish_directly','Yes');			

				$data['status']			= ($publish_directly=='Yes')?1:2; // 2 = pending

				$id = $this->realestate_model->insert_estate($data);

				$default_title 			= $this->input->post('title'.$dl);

				$meta_search_text		.= ' '.$default_title;

				$default_description 	= $this->input->post('description'.$dl);

				$meta_search_text		.= ' '.$default_description;


				$meta_search_text		.= ' '.$this->input->post('tags');
				#collecting meta information for simple searching is complete

				#now update the post table with the information

				$data = array();

	        	$data['search_meta'] = $meta_search_text;

	        	$this->realestate_model->update_estate($data,$id);

	            $this->load->model('admin/system_model');

	            $query = $this->system_model->get_all_langs();

	            $active_languages = $query->result();

	        	$data = array();

	        	$data['post_id'] 	= $id;

	        	$data['key']		= 'title';

	        	$data['status']	= 1;

	       

	       		$value = array();     

	            foreach ($active_languages as $row) {
	            	$title = $this->input->post('title'.$row->short_name);
	            	$value[$row->short_name] = $title;
	            }

	            $data['value'] = json_encode($value);
	            $this->realestate_model->insert_estate_meta($data);

	        	$data = array();

	        	$data['post_id'] 	= $id;

	        	$data['key']		= 'description';

	        	$data['status']	= 1;

	       		$value = array();     

	            foreach ($active_languages as $row) {
	            	$description = $this->input->post('description'.$row->short_name);
	            	$value[$row->short_name] = $description;
	            }

	            $data['value'] = json_encode($value);
	            $this->realestate_model->insert_estate_meta($data);
	            add_post_meta($id,'tags',$this->input->post('tags'));

	            if($purpose=='DBC_PURPOSE_RENT')
	            {
		            add_post_meta($id,'from_rent_date',$this->input->post('from_date'));
		            add_post_meta($id,'to_rent_date',$this->input->post('to_date'));            	
	            }
                    
	            #increase users post count

	            $user_id = $this->session->userdata('user_id');
	            $post_count = get_user_meta($user_id,'post_count',0);
	            $post_count++;
	            add_user_meta($user_id,'post_count',$post_count);
                    #adding distance information
                    $distance_ids = $this->input->post('distance_id');
                    $distance_titles = $this->input->post('distance_title');
                    $distance_icons = $this->input->post('distance_icon');
                    $distance_values = $this->input->post('distance_value');
                    $distance_units = $this->input->post('distance_unit');
                    $i = 0;
                    $vals = array();
                    foreach ($distance_ids as $key => $value) {
                            $dis_info = array();
                            $dis_info['id'] = $distance_ids[$key];
                            $dis_info['title'] = $distance_titles[$key];
                            $dis_info['icon'] = $distance_icons[$key];
                            $dis_info['value'] = $distance_values[$key];
                            $dis_info['units'] = $distance_units[$key];
                            $vals[$i++] = json_encode($dis_info);
                    }
                    add_post_meta($id,'distance_info',json_encode($vals));


                    if($enable_custom_fields=='Yes')
                    {
                        $fields = $this->config->item('custom_fields');
                        $data = array();
                        foreach ($fields as $field) 
                        {
                                $data[$field['name']] = $this->input->post($field['name']);
                        }
                    }
                    add_post_meta($id,'custom_values',json_encode($data));

                 
                    if($publish_directly=='Yes')
                        $this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Property added').'</div>');
                        else
                        $this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Property added and waiting for admin approval.').'</div>');
                }

                    redirect(site_url('admin/realestate/editestate/0/'.$id));		

            }

	}



	public function get_states_ajax()

	{
		$country = $this->input->post('country');
		$data = $this->realestate_model->get_locations_state_json('state',$country);	
		echo json_encode($data);

	}

        
        public function get_regions_ajax()

	{
		$state = $this->input->post('state');
		$data = $this->realestate_model->get_locations_region_json('city',$state);	
		echo json_encode($data);

	}


	public function get_cities_ajax($term='')

	{

		if($term=='')

			$term = $this->input->post('term');

		$state = $this->input->post('state');

		$data = $this->realestate_model->get_locations_json($term,'city',$state);	

		echo json_encode($data);

	}
        
        
        public function get_types_ajax()

	{

		$category = $this->input->post('category');
		$data = $this->realestate_model->get_types_json($category);
		echo json_encode($data);
	}



	public function locations($start='0')

	{

        $data['title'] = 'All locations';

        $value['posts'] = $this->realestate_model->get_all_locations_by_range($start,$this->per_page,'id');

        $total 				= $this->realestate_model->count_all_locations();

		$value['pages']		= configPagination('admin/realestate/locations',$total,5,$this->per_page);



        $data['content'] = $this->load->view('admin/estate/all_locations_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);		

	}

	



	public function newlocation($type='country')

	{

		$value['type'] = $type;

		$value['countries'] = $this->realestate_model->get_locations_by_type('country');

		$value['states'] 	= $this->realestate_model->get_locations_by_type('state');

		$this->load->view('admin/estate/new_location_view',$value);

	}



	public function savelocation()

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->form_validation->set_rules('type', 'Type', 'required');

		$type = $this->input->post('type');

		if($type=='state' || $type=='city')

		$this->form_validation->set_rules('country', 'Country', 'required');



		if($type=='city')

		{

			$this->form_validation->set_rules('country', 'Country', 'required');

			$this->form_validation->set_rules('state', 'State', 'required');

		}

		

		$this->form_validation->set_rules('locations', 'Names', 'required');

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->newlocation($type);	

		}

		else

		{

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				$locations = $this->input->post('locations');

				$locations_array = explode(',',$locations);

				if($type=='country')

					$parent = 0;

				elseif($type=='state')

					$parent = $this->input->post('country');

				elseif($type=='city')

					$parent = $this->input->post('state');



				foreach ($locations_array as $location) 

				{

					$data = array();			

					$data['name'] 	= $location;

					$data['type'] 	= $type;

					$data['parent'] = $parent;

					$data['status']	= 1;

					$this->realestate_model->insert_location($data);

				}

				



				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data inserted</div>');

			}
			

			redirect(site_url('admin/realestate/newlocation'));		

		}

	}



	public function editlocation($type='country',$id='')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$value['type'] = $type;

		$value['editlocation'] 	= $this->realestate_model->get_location_by_id($id);

		$value['countries'] 	= $this->realestate_model->get_locations_by_type('country');

		$value['states'] 		= $this->realestate_model->get_locations_by_type('state');

		$this->load->view('admin/estate/edit_location_view',$value);

	}



	public function updatelocation()

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->form_validation->set_rules('type', 'Type', 'required');

		$id = $this->input->post('id');

		$type = $this->input->post('type');

		if($type=='state' || $type=='city')

		$this->form_validation->set_rules('country', 'Country', 'required');



		if($type=='city')

		{

			$this->form_validation->set_rules('country', 'Country', 'required');

			$this->form_validation->set_rules('state', 'City', 'required');

		}

		

		$this->form_validation->set_rules('location', 'Name', 'required');

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->editlocation($type,$id);	

		}

		else

		{

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				if($type=='country')

					$parent = 0;

				elseif($type=='state')

					$parent = $this->input->post('country');

				elseif($type=='city')

					$parent = $this->input->post('state');



				$data = array();			

				$data['name'] 	= $this->input->post('location');

				$data['type'] 	= $type;

				$data['parent'] = $parent;

				$data['status']	= 1;

				$this->realestate_model->update_location($data,$id);

				



				$this->session->set_flashdata('msg', '<div class="alert alert-success">'.lang_key('Data updated').'</div>');
				
			}

			redirect(site_url('admin/realestate/editlocation/'.$type.'/'.$id));		

		}

	}



	#delete a location

	public function deletelocation($page='0',$id='',$confirmation='')

	{

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}
		
		if($confirmation=='')

		{

			$data['content'] = $this->load->view('admin/confirmation_view',array('id'=>$id,'url'=>site_url('admin/realestate/deletelocation/'.$page)),TRUE);

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
					$this->realestate_model->delete_location_by_id($id);

					$this->session->set_flashdata('msg', '<div class="alert alert-success">Location Deleted</div>');					
				}

			}

			redirect(site_url('admin/realestate/locations/'.$page));		

			

		}		

	}



	#get and display facility information

	public function facilities() {



		$this->load->model('admin/facility_model');



		$value['facilities']		= $this->facility_model->get_all_facilities_by_range('all');

		$data['title'] 				= 'facilities';

		$data['content'] 			= $this->load->view('admin/facilities/facilities_view',$value,TRUE);



		$this->load->view('admin/template/template_view',$data);

	}



	#remove a single facility by its id

	public function remove_facility($id) {

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		if(!isset($id))

			redirect(site_url('admin/realestate/facilities'));



		$this->load->model('admin/facility_model');



		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$data['status'] = 0;

			$this->facility_model->update_facility($data,$id);

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Facility removed</div>');
			
		}

		redirect(site_url('admin/realestate/facilities'));

	}



	#edit a single facility by its id

	public function edit_facility($id) {



		if(!isset($id))

			redirect(site_url('admin/realestate/facilities'));



		$this->load->model('admin/facility_model');



		$value['post']  = $this->facility_model->get_facility_by_id($id);

		$data['content'] = $this->load->view('admin/facilities/edit_facility_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);





	}



	#save the updated facility information

	public function update_facility() {

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->load->model('admin/facility_model');



		$this->form_validation->set_rules('title', 'Title', 'required');

							

		if ($this->form_validation->run() == FALSE)

		{

			$id = $this->input->post('id');

			$this->edit_facility($id);	

		}

		else

		{

			$id = $this->input->post('id');



			$data 					= array();			

			$data['title'] 			= $this->input->post('title');

			$data['icon'] 			= $this->input->post('icon');			

			

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{

				$this->facility_model->update_facility($data,$id);

				$this->session->set_flashdata('msg', '<div class="alert alert-success">Facility information updated</div>');
			}		

			//redirect(site_url('admin/category/edit/'.$id));

			$this->edit_facility($id);

		}

	}



	#delete multiple facilities

	public function remove_bulk_facilities()

	{

		$this->load->model('admin/facility_model');



		$data['status'] = 0;

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->facility_model->bulk_update_facilities($data,$this->input->post('id'));

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Facilities removed</div>');			
		}

		redirect(site_url('admin/realestate/facilities'));			

	}





	#load new facility page

	function newfacility()

	{



        $data['title'] = 'Create New Facility';

        $data['content'] = $this->load->view('admin/facilities/create_facility_view','',TRUE);

		$this->load->view('admin/template/template_view',$data);



	}



	#add facility information to the database

	function addfacility()

	{

		$this->form_validation->set_rules('title', 'Title', 'required');

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->newfacility();	

		}

		else

		{

			$data 						= array();			

			$data['title'] 				= $this->input->post('title');

			$data['icon'] 				= $this->input->post('icon');

			$data['status']				= 1;

			
			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				$this->load->model('facility_model');

				$id = $this->facility_model->insert_facility($data);

				if($id>0) {



					$this->session->set_flashdata('msg', '<div class="alert alert-success">New facility created</div>');

				}

				else {



					$this->session->set_flashdata('msg', '<div class="alert alert-success">Error occured facility could not be created</div>');	

				}
				
			}


			redirect(site_url('admin/realestate/newfacility'));		

		}



	}


	#get and display all distance fields information

	public function distance_fields() {



		$this->load->model('admin/distance_info_model');



		$value['distance_infos']	= $this->distance_info_model->get_all_distance_info();

		$data['title'] 				= 'Distance Fields';

		$data['content'] 			= $this->load->view('admin/distance_info/distance_info_view',$value,TRUE);



		$this->load->view('admin/template/template_view',$data);

	}

	#load new distance info page

	function new_distance_info()

	{



        $data['title'] = 'Create New Distance Field';

        $data['content'] = $this->load->view('admin/distance_info/create_distance_field_view','',TRUE);

		$this->load->view('admin/template/template_view',$data);



	}

	#add distance information to the database

	function add_new_distance_field()

	{

		$this->form_validation->set_rules('title', 'Title', 'required');

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->new_distance_info();	

		}

		else

		{

			$info 						= array();

			$data 						= array();

			$info['title'] 				= $this->input->post('title');

			$info['icon'] 				= $this->input->post('icon');

			$data['key']				= 'distance_field';

			$data['values']				= json_encode($info);

			$data['status']				= 1;

			
			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				$this->load->model('distance_info_model');

				$id = $this->distance_info_model->insert_distance_field($data);

				if($id>0) {



					$this->session->set_flashdata('msg', '<div class="alert alert-success">New Distance field created</div>');

				}

				else {



					$this->session->set_flashdata('msg', '<div class="alert alert-success">Error occured distance field could not be created</div>');	

				}
				
			}


			redirect(site_url('admin/realestate/new_distance_info'));		

		}



	}

	#remove a single facility by its id

	public function remove_distance_field($id) {

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		if(!isset($id))

			redirect(site_url('admin/realestate/distance_fields'));



		$this->load->model('admin/distance_info_model');



		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$data['status'] = 0;

			$this->distance_info_model->update_distance_info($data,$id);

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Distance field removed</div>');
			
		}

		redirect(site_url('admin/realestate/distance_fields'));

	}



	#edit a single facility by its id

	public function edit_distance_field($id) {



		if(!isset($id))

			redirect(site_url('admin/realestate/distance_fields'));



		$this->load->model('admin/distance_info_model');



		$value['post']  = $this->distance_info_model->get_distance_info_by_id($id);

		$data['content'] = $this->load->view('admin/distance_info/edit_distance_field_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);

	}



	#save the updated facility information

	public function update_distance_field() {

		if(!is_admin())
		{
			echo 'You don\'t have permission to access this page';
			die;
		}

		$this->load->model('admin/distance_info_model');



		$this->form_validation->set_rules('title', 'Title', 'required');

							

		if ($this->form_validation->run() == FALSE)

		{

			$id = $this->input->post('id');

			$this->edit_distance_field($id);	

		}

		else

		{

			$id = $this->input->post('id');

			$info 						= array();

			$data 						= array();

			$info['title'] 				= $this->input->post('title');

			$info['icon'] 				= $this->input->post('icon');

			$data['key']				= 'distance_field';

			$data['values']				= json_encode($info);
			

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{

				$this->distance_info_model->update_distance_info($data,$id);

				$this->session->set_flashdata('msg', '<div class="alert alert-success">Distance field information updated</div>');
			}		

			redirect(site_url('admin/realestate/edit_distance_field/'.$id));

		}

	}



	#delete multiple facilities

	public function remove_bulk_distance_fields()

	{

		$this->load->model('admin/distance_info_model');



		$data['status'] = 0;

		if(constant("ENVIRONMENT")=='demo')
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
		}
		else
		{
			$this->distance_info_model->bulk_update_distance_info($data,$this->input->post('id'));

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Distance fields removed</div>');			
		}

		redirect(site_url('admin/realestate/distance_fields'));			

	}



	# image upload functions

	public function create_date_directory()

	{

		$orginal = 'original';

		if (!file_exists('./uploads/'.$orginal)) 

		{

                    mkdir('./uploads/'.$orginal);

		}

		return $orginal.'/';

	}





	public function iconuploader()

	{

		$this->load->view('admin/facilities/icon_uploader_view');

	}



	public function featuredimguploader()

	{

		$this->load->view('admin/estate/featured_img_uploader_view');

	}

    public function brochureuploader()

    {

        $this->load->view('admin/estate/brochure_uploader_view');

    }


	public function searchbguploader()

	{

		$this->load->view('admin/estate/searchbg_uploader_view');

	}



	public function galleryimguploader($count=1)

	{

		$value['count'] = $count;

		$this->load->view('admin/estate/gallery_img_uploader_view',$value);

	}



	public function bannerimguploader($count=1)

	{

		$value['count'] = $count;

		$this->load->view('admin/estate/banner_img_uploader_view',$value);

	}



	public function profile_photo_uploader()

	{

		$this->load->view('users/profile_photo_uploader_view');

	}



	public function upload_profile_photo()

	{

		$date_dir = 'profile_photos/';

		$config['upload_path'] = './uploads/profile_photos/';

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



	

	public function uploadiconfile()

	{

		$date_dir = $this->create_date_directory();

		$config['upload_path'] = './uploads/'.$date_dir;

		$config['allowed_types'] = 'gif|jpg|JPG|png';

		$config['max_size'] = '1000';

		// $config['max_width'] = '32';

		// $config['max_height'] = '32';

		$config['min_width'] = '32';

		$config['min_height'] = '32';



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



	public function uploadsearchbgfile()

	{

		//$date_dir = $this->create_date_directory();

		$config['upload_path'] = './uploads/banner/';

		$config['allowed_types'] = 'gif|jpg|JPG|png';

		$config['max_size'] = '5120';

		$config['min_width'] = '1024';

		$config['min_height'] = '600';



		$this->load->library('dbcupload', $config);

		$this->dbcupload->display_errors('', '');	

		if($this->dbcupload->do_upload('photoimg'))

		{

			$data = $this->dbcupload->data();

			$this->load->helper('date');

			$format = 'DATE_RFC822';

			$time = time();

			//create_square_thumb('./uploads/'.$date_dir.$data['file_name'],'./uploads/thumbs/');

			$media['media_name'] 		= $data['file_name'];

			$media['media_url']  		= base_url().'uploads/banner/'.$data['file_name'];

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



	public function uploadfeaturedfile()

	{

		$date_dir = $this->create_date_directory();

		$config['upload_path'] = './uploads/'.$date_dir;

		$config['allowed_types'] = 'gif|jpg|JPG|png';

		$config['max_size'] = '10120';

		$config['min_width'] = '840'; 

		$config['min_height'] = '440';



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

    public function uploadbrochure()

    {

        $date_dir = $this->create_date_directory();

        $config['upload_path'] = './uploads/gallery';

        $config['allowed_types'] = 'pdf|doc|docx';

        $config['max_size'] = '5120';





        $this->load->library('dbcupload', $config);

        $this->dbcupload->display_errors('', '');

        if($this->dbcupload->do_upload('photoimg'))

        {

            $data = $this->dbcupload->data();


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

	public function uploadgalleryfile()

	{

		//$date_dir = $this->create_date_directory();

		$config['upload_path'] = './uploads/gallery/';

		$config['allowed_types'] = 'gif|jpg|JPG|png';

		$config['max_size'] = '10120';

		$config['min_width'] = '840';

		$config['min_height'] = '440';



		$this->load->library('dbcupload', $config);

		$this->dbcupload->display_errors('', '');	

		if($this->dbcupload->do_upload('photoimg'))

		{

			$data = $this->dbcupload->data();

			$this->load->helper('date');

			$format = 'DATE_RFC822';

			$time = time();


			$media['media_name'] 		= $data['file_name'];

			$media['media_url']  		= base_url().'uploads/gallery/'.$data['file_name'];

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



	public function uploadbannerfile()

	{

		//$date_dir = $this->create_date_directory();

		$config['upload_path'] = './uploads/banner/';

		$config['allowed_types'] = 'gif|jpg|JPG|png';

		$config['max_size'] = '5120';

		$config['min_width'] = '1024';

		$config['min_height'] = '600';



		$this->load->library('dbcupload', $config);

		$this->dbcupload->display_errors('', '');	

		if($this->dbcupload->do_upload('photoimg'))

		{

			$data = $this->dbcupload->data();

			$this->load->helper('date');

			$format = 'DATE_RFC822';

			$time = time();

			//create_square_thumb('./uploads/'.$date_dir.$data['file_name'],'./uploads/thumbs/');

			$media['media_name'] 		= $data['file_name'];

			$media['media_url']  		= base_url().'uploads/banner/'.$data['file_name'];

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



	public function crop($src='',$width=256,$height=256)

	{

		$config['image_library'] = 'gd2';

		$config['source_image'] = $src;

		$config['width'] = $width;

		$config['height'] = $height;



		$this->load->library('image_lib', $config);



		$this->image_lib->resize();

	}


	public function emailtracker($start='0')
	{
		if(is_admin())
		{
			$value['posts']  	= $this->realestate_model->get_all_emails_admin($start,$this->per_page);

			$total 				= $this->realestate_model->count_all_emails_admin();			
		}
		else
		{
			$value['posts']  	= $this->realestate_model->get_all_emails_agent($start,$this->per_page);

			$total 				= $this->realestate_model->count_all_emails_agent();						
		}


		$value['pages']		= configPagination('admin/realestate/emailtracker',$total,5,$this->per_page);

		$value['start']     = $start;

        $data['title'] = 'Email Tracker';

		$data['content'] = $this->load->view('admin/estate/all_emails_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);
	}


	public function bulkemailform()
	{
		if(is_admin())
		{
			$value['posts']  	= $this->realestate_model->get_all_emails_admin('all',$this->per_page);

			$total 				= $this->realestate_model->count_all_emails_admin();			
		}
		else
		{
			$value['posts']  	= $this->realestate_model->get_all_emails_agent('all',$this->per_page);

			$total 				= $this->realestate_model->count_all_emails_agent();						
		}

		

		$data['title'] = 'Bulk Email';

		$data['content'] = $this->load->view('admin/estate/bulk_email_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);
	}

	public function sendbulkemail($agent_id='0')

	{

		$this->form_validation->set_rules('to', 'To', 'required');

		$this->form_validation->set_rules('subject', 'Subject', 'required');

		$this->form_validation->set_rules('message', 'Message', 'required');

		if ($this->form_validation->run() == FALSE)

		{

			$this->bulkemailform();	

		}

		else

		{

			if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				$to 		= (isset($_POST['to']) && is_array($_POST['to']))?$_POST['to']:array();
				$subject 	= $this->input->post('subject');
				$message 	= $this->input->post('message');
				
				$this->load->library('email');

				$config['mailtype'] = "html";

				$config['charset'] 	= "utf-8";

				$this->email->initialize($config);



				$this->email->from($this->session->userdata('user_email'),$this->session->userdata('user_name'));

				$this->email->to($to);



				$this->email->subject($subject);

				$this->email->message($message);



				$this->email->send();



				$this->session->set_flashdata('msg', '<div class="alert alert-success">Email sent</div>');				
			}

			redirect(site_url('admin/realestate/bulkemailform'));			

		}

	}

    public function test_filter(){

        $data['title'] = 'Test Filter';

        $data['content'] = $this->load->view('admin/estate/test_filter_view','',TRUE);

        $this->load->view('admin/template/template_view',$data);

    }



    public function cutomfields()

    {

        $data['title'] = 'Custom field manager';

        $data['content'] = $this->load->view('admin/estate/test_filter_view','',TRUE);

        $this->load->view('admin/template/template_view',$data);    	

    }



	#load banner settings

	public function bannersettings()

	{		

        $data['title'] = lang_key('All Estates');

		$data['content'] = $this->load->view('admin/estate/banner_settings_view','',TRUE);

		$this->load->view('admin/template/template_view',$data);		

	}



	#slider validation function

	public function slider_required($str)

	{

		$flag = FALSE;

		foreach ($_POST['banner'] as $value) {

			if($value!='')

				$flag=TRUE;

		}



		if($flag==FALSE)	

		{

			$this->form_validation->set_message('slider_required', 'You must set atleast one slider image');

			return FALSE;

		}

		else

		{

			return TRUE;

		}

	}



	#save banner settings

	public function savebannersettings($key='banner_settings')

	{

		if($this->input->post('banner_type')=='Slider')

		{

			$rule = '|callback_slider_required';

			$this->form_validation->set_rules('slider_speed', 'Slider speed', 'required');

			

		}

		else

		{

			$rule = '';

		}

		$this->form_validation->set_rules('banner_type', 'Banner type', 'required'.$rule);

		$this->form_validation->set_rules('search_box_position', 'Search box position', 'required');

		$this->form_validation->set_rules('search_bg', 'BG image', 'required');

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->bannersettings();	

		}

		else

		{

			$data = array();

			$data['menu_bg_color'] 			= $this->input->post('menu_bg_color');

			$data['menu_text_color'] 		= $this->input->post('menu_text_color');

            $data['active_menu_text_color'] = $this->input->post('active_menu_text_color');

			$data['banner_type'] 			= $this->input->post('banner_type');

			$data['slider_speed'] 			= $this->input->post('slider_speed');

			$data['sliders'] 				= json_encode($_POST['banner']);

			$data['search_box_position'] 	= $this->input->post('search_box_position');

			$data['search_bg'] 				= $this->input->post('search_bg');

            $data['map_latitude'] 				= $this->input->post('map_latitude');

            $data['map_longitude'] 				= $this->input->post('map_longitude');

            $data['map_zoom'] 				= $this->input->post('map_zoom');

            if(constant("ENVIRONMENT")=='demo')
			{
				$this->session->set_flashdata('msg', '<div class="alert alert-success">Data updated.[NOT AVAILABLE ON DEMO]</div>');
			}
			else
			{
				add_option('banner_settings',json_encode($data));

				$this->session->set_flashdata('msg', '<div class="alert alert-success">Settings updated</div>');					
			}

			redirect(site_url('admin/realestate/bannersettings'));	

		}			



	}



	public function payments($start=0)

	{

		$this->load->model('admin/realestate_model');

		$value['start']		= $start;

		$value['posts']  	= $this->realestate_model->get_all_payment_history($start,$this->per_page,'id','desc');

		$total 				= $this->realestate_model->count_all_payment_history();

		$value['pages']		= configPagination('admin/realestate/payments',$total,5,$this->per_page);



        $data['title'] = 'Payment History';

		$data['content'] = $this->load->view('admin/estate/all_payments_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);

	}



	#delete a service

	public function deletehistory($page='0',$id='',$confirmation='')

	{

		if($confirmation=='')

		{

			$data['content'] = $this->load->view('admin/confirmation_view',array('id'=>$id,'url'=>site_url('admin/realestate/deletehistory/'.$page.'/')),TRUE);

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
					$this->realestate_model->deletehistory($id);

					$this->session->set_flashdata('msg', '<div class="alert alert-success">Data Deleted</div>');					
				}

			}

			redirect(site_url('admin/realestate/payments/'.$page));		
		}		

	}	

	public function confirmtransaction($page='0',$unique_id='')
	{
		if(!is_admin())
		{
			echo 'You don\'t have permission to access this';
		}

		$this->load->model('user/user_model');
		$res = $this->user_model->confirm_transaction_by_id($unique_id);
		if($res)
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-success">Payment confirmed.</div>');
			redirect(site_url('admin/realestate/payments/'.$page));		
		}
		else
		{
			$this->session->set_flashdata('msg', '<div class="alert alert-danger">Transaction id not valid or already confirmed.</div>');
			redirect(site_url('admin/realestate/payments/'.$page));					
		}
	}


	#word filter functions#
	public function wordfilter()

	{

		$row = get_option('wordfilters');

		$wordfilters = '';

		if(!is_array($row))

		{

			$words = json_decode($row->values);

			foreach ($words as $key => $value) {

				$wordfilters .= $key.'|'.$value.',';

			}



			$wordfilters .= '#';

			$wordfilters = str_replace(',#','',$wordfilters);

		}



		$value = array('wordfilters'=>$wordfilters);

        $data['title'] = 'Word Filter';



        $data['content'] = $this->load->view('admin/memento/wordfilter_view',$value,TRUE);

		$this->load->view('admin/template/template_view',$data);				

	}



	public function addwordfilters()

	{

		$this->form_validation->set_rules('wordfilters', 'Words', 'required');

		

		if ($this->form_validation->run() == FALSE)

		{

			$this->wordfilter();	

		}

		else

		{

			$pairs = explode(',',$this->input->post('wordfilters'));

			$words = array();

			foreach ($pairs as $pair) 

			{

				$pair = explode('|',$pair);

				$words[$pair[0]] = $pair[1];

			}



			add_option('wordfilters',json_encode($words));

			$this->filterposts($words);

			$this->session->set_flashdata('msg', '<div class="alert alert-success">Filter added</div>');

			redirect(site_url('admin/memento/wordfilter'));			

		}

	}



	public function filterposts($words)

	{

		$this->load->model('show/post_model');

		$query = $this->post_model->get_all_posts_by_range('all','','id');

		foreach ($query->result_array() as $post) {

			foreach ($words as $key => $value) {

				$post['title'] = str_replace($key,$value,$post['title']);

			}

			$this->post_model->update_post_by_id($post,$post['id']);

		}

	}

	public function get_distance_info($post_id) {

		$this->load->model('distance_info_model');
		$info = $this->distance_info_model->get_distance_info_of_a_post($post_id);
		
		if($info=='error')
			return array();
		
		return $info;
	}


}



/* End of file realestate_core.php */

/* Location: ./application/modules/admin/controllers/realestate_core.php */