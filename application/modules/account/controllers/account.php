<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * memeshare Admin Controller
 *
 * This class handles some admin functionality
 *
 * @package		bookit
 * @subpackage	Admin
 * @author		sc mondal
 * @link		http://scmondal.dbcinfotech.com
 */
require_once'account_core.php';
class Account extends Account_core {

	public function __construct()
	{
		parent::__construct();
                parent::__construct();
                $curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
                if ($curr_lang == 'ar'){
                    $this->config->set_item('language', 'arabic');
                }
	}
        
        
        function register()
	{
                
		$user_type = $this->input->post('user_type');
                
                //firas customize here , set user_type = 'dealer' always
                $user_type = 'dealer';
                
		$this->form_validation->set_rules('first_name', lang_key('First Name'), 'required|xss_clean');
		$this->form_validation->set_rules('last_name',	lang_key('Last Name'), 'required|xss_clean');
		$this->form_validation->set_rules('gender',	lang_key('Gender'), 'required|xss_clean');
		$this->form_validation->set_rules('username', 	lang_key('Username'),'required|callback_username_check|xss_clean');

		if($user_type=='dealer'){
                    $this->form_validation->set_rules('company_name',lang_key('Company name'),'required|xss_clean');
                    $this->form_validation->set_rules('company_address',lang_key('company_address'),'required|xss_clean');
                }
        
                $this->form_validation->set_rules('phone',lang_key('Phone'),'required|xss_clean|numeric');
                $this->form_validation->set_rules('useremail',	lang_key('Email'),'required|valid_email|xss_clean|callback_useremail_check');
		$this->form_validation->set_rules('password', 	lang_key('Password'), 'required|matches[repassword]|min_length[5]|xss_clean');
		$this->form_validation->set_rules('repassword',	lang_key('Confirm_pwd'), 'required|xss_clean');
		//$this->form_validation->set_rules('terms_conditon',lang_key('Terms and condition'),'xss_clean|callback_terms_check');
		$this->form_validation->set_rules('user_type',lang_key('user_type'),'required|xss_clean');
		$enable_pricing = get_settings('realestate_settings','enable_pricing','Yes');
		if($enable_pricing=='Yes')
		{
			$this->form_validation->set_rules('package_id',	'Package id','required|xss_clean');			
		}

		if ($this->form_validation->run() == FALSE)
		{
			$this->signupform();	
		}
		else
		{

			$this->load->library('encrypt');
			if($user_type=='dealer')
			$userdata['user_type']	= 2;//2 = users (with company field)
			else
			$userdata['user_type']	= 3;//3 = private
		
			$userdata['first_name'] = $this->input->post('first_name');
			$userdata['last_name'] 	= $this->input->post('last_name');
			$userdata['gender'] 	= $this->input->post('gender');			
			$userdata['user_name'] 	= $this->input->post('username');
			$userdata['user_email'] = $this->input->post('useremail');
			$userdata['password'] 	= $this->encrypt->sha1($this->input->post('password'));
			$userdata['confirmation_key'] 	= uniqid();
			$userdata['confirmed'] 	= 0;
			$userdata['status']		= 1;

			$this->load->model('user/user_model');
			$user_id = $this->user_model->insert_user_data($userdata);
			
			if($user_type=='dealer')
			add_user_meta($user_id,'company_name',$this->input->post('company_name'));
                        add_user_meta($user_id,'company_address',$this->input->post('company_address'));
                        add_user_meta($user_id,'phone',$this->input->post('phone'));

			if($enable_pricing=='Yes')
			{
				$datestring = "%Y-%m-%d";
				$time = time();
				$request_date = mdate($datestring, $time);
				$this->load->model('admin/package_model');
				$package 	= $this->package_model->get_package_by_id($this->session->userdata('package_id'));

				$payment_data 					= array(); 
				$payment_data['unique_id'] 		= uniqid();
				$payment_data['user_id'] 		= $user_id;
				$payment_data['package_id'] 	= $package->id;
				$payment_data['amount'] 		= $package->price;
				$payment_data['request_date'] 	= $request_date;
				$payment_data['is_active'] 		= 2; #pending
				$payment_data['status'] 		= 1; #active
				$payment_data['payment_medium']	= 'paypal'; 
				$unique_id 	= $this->user_model->insert_payment_data($payment_data);

				if($payment_data['amount']<=0)
				{
					$uniqid = $unique_id;
					#$this->send_notification_mail('within update');
                                        $this->load->model('admin/package_model');
                                        $this->load->model('user/user_model');
                                        $package 	= $this->package_model->get_package_by_id($package->id);
                                        $datestring = "%Y-%m-%d";
					$time = time();
					$activation_date = mdate($datestring, $time);
					$expirtion_date  = strtotime('+'.$package->expiration_time.' days',$time);
                                        $expirtion_date = mdate($datestring, $expirtion_date);
                                        
                                        $data = array();
                                        $data['is_active'] 		 	= 1;
                                        $data['activation_date'] 	= $activation_date;
                                        $data['expirtion_date'] 	= $expirtion_date;
                                        $data['response_log']		= '';

                                        $this->user_model->update_user_payment_data_by_unique_id($data,$uniqid);
                                        add_user_meta($user_id,'current_package',$package->id);
                                        add_user_meta($user_id,'expirtion_date',$expirtion_date);
                                        add_user_meta($user_id,'active_order_id',$uniqid);
                                        add_user_meta($user_id,'post_count',0);
					$link = $this->send_confirmation_email($userdata);
					redirect(site_url('account/success'));	
				}
				else
				{
					$this->session->set_userdata('unique_id',$unique_id);
					$this->session->set_userdata('amount',$package->price);
					$link = $this->send_signup_notification_email($userdata,$unique_id);
					redirect(site_url('account/confirmation'));							
				}
			}
			else
			{
				$link = $this->send_confirmation_email($userdata);
				redirect(site_url('account/success'));	
			}
		}
	}
        
}