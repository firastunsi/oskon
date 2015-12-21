<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento fbauth Controller
 *
 * This class handles user account related functionality
 *
 * @package		Account
 * @subpackage	fbauth
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */


class Fbauth_core extends MX_Controller {

	public function __construct()
	{
                
		parent::__construct();
		parse_str( $_SERVER['QUERY_STRING'], $_REQUEST );
		$CI 	= & get_instance();
		$appId 	= get_settings('realestate_settings','fb_app_id','none');
		$secret = get_settings('realestate_settings','fb_secret_key','none');
                $config = array('appId'=>$appId,'secret'=>$secret);
                $this->load->library('Facebook', $config);
                
	}

	#get web admin name and email for email sending
	public function get_admin_email_and_name()
	{
		$this->load->model('admin/options_model');
		$values = $this->options_model->getvalues('webadmin_email');

		if(count($values))
		{
			$data['admin_email'] = (isset($values->webadmin_email))?$values->webadmin_email:'admin@'.$_SERVER['HTTP_HOST'];
			$data['admin_name']  = (isset($values->webadmin_name))?$values->webadmin_name:'Admin';
		}
		else
		{
			$data['admin_email'] = 'admin@'.$_SERVER['HTTP_HOST'];
			$data['admin_name']  = 'Admin';		
		}

		return $data;
	}	

	#send a payment notification email with confirmation link
	public function send_signup_notification_email($data=array(),$unique_id='')
	{
            
            
		$val = $this->get_admin_email_and_name();
		$admin_email = $val['admin_email'];
		$admin_name  = $val['admin_name'];
		$link = site_url('account/recoverpayment/'.$unique_id); 
		
		$this->load->model('admin/system_model');
		$tmpl = $this->system_model->get_email_tmpl_by_email_name('signup_notification_email');
		$subject = $tmpl->subject;
		$subject = str_replace("#username",$data['user_name'],$subject);
		$subject = str_replace("#recoverylink",$link,$subject);
		$subject = str_replace("#webadmin",$admin_name,$subject);
		$subject = str_replace("#useremail",$data['user_email'],$subject);

		
		$body = $tmpl->body;
		$body = str_replace("#username",$data['user_name'],$body);
		$body = str_replace("#recoverylink",$link,$body);
		$body = str_replace("#webadmin",$admin_name,$body);
		$body = str_replace("#useremail",$data['user_email'],$body);
				
		$this->load->library('email');
		$this->email->from($admin_email, $subject);
		$this->email->to($data['user_email']);
		$this->email->subject($subject);		
		$this->email->message($body);		
		$this->email->send();
	}

	#send a confirmation email with confirmation link
	public function send_confirmation_email($data=array('username'=>'sc mondal','useremail'=>'shimulcsedu@gmail.com','confirmation_key'=>'1234'))
	{
		$val = $this->get_admin_email_and_name();
		$admin_email = $val['admin_email'];
		$admin_name  = $val['admin_name'];
		$link = site_url('account/confirm/'.$data['user_email'].'/'.$data['confirmation_key']); 
		
		$this->load->model('admin/system_model');
		$tmpl = $this->system_model->get_email_tmpl_by_email_name('confirmation_email');
		$subject = $tmpl->subject;
		$subject = str_replace("#username",$data['user_name'],$subject);
		$subject = str_replace("#activationlink",$link,$subject);
		$subject = str_replace("#webadmin",$admin_name,$subject);
		$subject = str_replace("#useremail",$data['user_email'],$subject);

		
		$body = $tmpl->body;
		$body = str_replace("#username",$data['user_name'],$body);
		$body = str_replace("#activationlink",$link,$body);
		$body = str_replace("#webadmin",$admin_name,$body);
		$body = str_replace("#useremail",$data['user_email'],$body);

				
		$this->load->library('email');
		$this->email->from($admin_email, $subject);
		$this->email->to($data['user_email']);
		$this->email->subject($subject);		
		$this->email->message($body);		
		$this->email->send();
	}
	
        function index()
	{
                
		// Try to get the user's id on Facebook
		$userId = $this->facebook->getUser();

		// If user is not yet authenticated, the id will be zero
		if($userId == 0){
			// Generate a login url
			$data['url'] = $this->facebook->getLoginUrl(array('scope'=>'email')); 
			redirect($data['url']);
		} else {
			// Get user's data and print it
			$user = $this->facebook->api('/me');
			$this->load->model('auth_model');

			$row = $this->auth_model->register_user_if_not_exists($user);

			$enable_pricing  = get_settings('realestate_settings','enable_pricing','Yes');
			$current_package = get_user_meta($row['id'],'current_package','0');

			
			if($enable_pricing=='Yes' && $this->session->userdata('from')=='signup')
			{
				$user_id = $row['id'];
				$this->load->model('user/user_model');
				$this->load->helper('date');
				$datestring = "%Y-%m-%d";
				$time = time();
				$request_date = mdate($datestring, $time);
				$this->load->model('admin/package_model');
				if($this->session->userdata('package_id')=='')
				{
					$this->session->set_userdata('from','facebook');
					redirect(site_url('account/signup'));
				}
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

					$this->login($row);	
				}
				else
				{
					$this->session->set_userdata('unique_id',$unique_id);
					$this->session->set_userdata('amount',$package->price);
					$this->send_signup_notification_email($row,$unique_id);
					redirect(site_url('account/confirmation'));							
				}
			}

			if($enable_pricing=='Yes' && $current_package==0)
			{
				$this->session->set_userdata('from','facebook');
				redirect(site_url('account/signup'));
			}

			if(is_banned($row['user_email']))
			{
				$msg = '<div class="alert alert-danger"><button data-dismiss="alert" class="close" type="button">×</button><strong>User banned</div>';
				$this->session->set_flashdata('msg', $msg);					
				redirect(site_url('account/trylogin'));				
			}
			else
			{
				$this->login($row);			
			}
		}
	}


	function login($row)
	{
		$this->session->set_userdata('user_id',$row['id']);
		$this->session->set_userdata('user_name',$row['user_name']);
		$this->session->set_userdata('user_type',$row['user_type']);
		$this->session->set_userdata('user_email',$row['user_email']);
               
                
		if($this->session->userdata('req_url')!='')
		{
			$req_url = $this->session->userdata('req_url');
			$this->session->set_userdata('req_url','');
			redirect($req_url);
		}
		else{
                    if ($this->session->userdata('list_property') == '1' && is_loggedin()){
                        $this->session->set_userdata('list_property','0');
                        redirect(site_url('admin/realestate/newestate'));
                    }
                    else{
                        redirect(base_url());
                    }
                }
			
	}


}

?>