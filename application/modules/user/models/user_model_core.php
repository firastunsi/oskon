<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Memento admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		User
 * @subpackage	UserModelCore
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */



class User_model_core extends CI_Model 

{

	function __construct()

	{

		parent::__construct();

		$this->load->database();

	}

	function insert_user_data($data)
	{
		$this->db->insert('users',$data);
		return $this->db->insert_id();
	}

	function insert_payment_data($data)
	{
		$this->db->insert('user_package',$data);
		return $data['unique_id'];
	}

	function get_user_payment_data_by_unique_id($unique_id)
	{
		$query = $this->db->get_where('user_package',array('unique_id'=>$unique_id));
		return $query;
	}

	function update_user_payment_data_by_unique_id($data,$unique_id)
	{
            
		$this->db->update('user_package',$data,array('unique_id'=>$unique_id));
	}

	function get_user_data_array_by_id($id)
	{

		$query = $this->db->get_where('users',array('id'=>$id));

		return $query->row_array();

	}

	function get_user_profile($user_email)

	{

		$query = $this->db->get_where('users',array('user_email'=>$user_email));

		return $query->row();

	}



	function get_user_profile_by_id($id)

	{

		$query = $this->db->get_where('users',array('id'=>$id));

		return $query->row();

	}

	

	function get_user_profile_by_user_name($user_name)

	{

		$query = $this->db->get_where('users',array('user_name'=>$user_name));

		if($query->num_rows()>0)

			return $query->row();

		else

			show_error('User name not valid' , 500 );

	}



	function delete_user_by_id($id)

	{

		$this->db->update('users',array('status'=>0),array('id'=>$id));

		$this->db->update('posts',array('status'=>0),array('created_by'=>$id));

	}





	function confirm_user_by_id($id)

	{

		$this->load->model('user/user_model');
		$this->load->helper('date');
		$this->db->order_by('request_date','desc');
		$query = $this->db->get_where('user_package',array('user_id'=>$id),1,0);
		if($query->num_rows()>0)
		{
			$order 		= $query->row();
			$uniqid     = $order->unique_id;
		    $order_id 	= $order->id;
			$this->load->model('admin/package_model');
			$package 	= $this->package_model->get_package_by_id($order->package_id);
			$datestring = "%Y-%m-%d";
			$time = time();
			$activation_date = mdate($datestring, $time);
			$expirtion_date  = strtotime('+'.$package->expiration_time.' days',$time);
			$expirtion_date = mdate($datestring, $expirtion_date);

			$data = array();
			$data['is_active'] 		 	= 1;
			$data['activation_date'] 	= $activation_date;
			$data['expirtion_date'] 	= $expirtion_date;
			$data['response_log']		= 'by admin';

			$this->user_model->update_user_payment_data_by_unique_id($data,$uniqid);
			add_user_meta($order->user_id,'current_package',$package->id);
			add_user_meta($order->user_id,'expirtion_date',$expirtion_date);
			add_user_meta($order->user_id,'active_order_id',$uniqid);
			add_user_meta($order->user_id,'post_count',0);


			$this->load->helper('date');
			$datestring = "%Y-%m-%d %h:%i:%a";
			$time = time();
			$data = array();
			$data['confirmed'] = 1;
			$data['confirmed_date'] = mdate($datestring, $time);
			$data['confirmation_key'] = '';
			$this->db->update('users',$data,array('id'=>$id));

		}
		else
		{

			$this->load->helper('date');

			$datestring = "%Y-%m-%d %h:%i:%a";

			$time = time();



			$data['confirmed'] = 1;

			$data['confirmed_date'] = mdate($datestring, $time);

			$data['confirmation_key'] = '';

			$this->db->update('users',$data,array('id'=>$id));
		}
	}


	function confirm_transaction_by_id($unique_id)

	{

		$this->load->helper('date');
		$query = $this->db->get_where('user_package',array('unique_id'=>$unique_id,'is_active !='=>1,'status'=>1));

		if($query->num_rows()>0)
		{
			$order 		= $query->row();
			############
			$uniqid     = $order->unique_id;
		    $order_id 	= $order->id;
			$this->load->model('admin/package_model');
			$package 	= $this->package_model->get_package_by_id($order->package_id);
			$datestring = "%Y-%m-%d";
			$time = time();
			$activation_date = mdate($datestring, $time);
			$expirtion_date  = strtotime('+'.$package->expiration_time.' days',$time);
			$expirtion_date = mdate($datestring, $expirtion_date);

			$data = array();
			$data['is_active'] 		 	= 1;
			$data['activation_date'] 	= $activation_date;
			$data['expirtion_date'] 	= $expirtion_date;
			$data['response_log']		= 'by admin';

			$this->user_model->update_user_payment_data_by_unique_id($data,$uniqid);
			add_user_meta($order->user_id,'current_package',$package->id);
			add_user_meta($order->user_id,'expirtion_date',$expirtion_date);
			add_user_meta($order->user_id,'active_order_id',$uniqid);
			add_user_meta($order->user_id,'post_count',0);


			$this->load->helper('date');
			$datestring = "%Y-%m-%d %h:%i:%a";
			$time = time();
			$data = array();
			$data['confirmed'] = 1;
			$data['confirmed_date'] = mdate($datestring, $time);
			$data['confirmation_key'] = '';
			$this->db->update('users',$data,array('id'=>$order->user_id));

			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	function update_user_by_id($data,$id)

	{

		$this->db->update('users',$data,array('id'=>$id));

	}


	function update_profile($data,$id)

	{

		$this->db->update('users',$data,array('id'=>$id));

		$this->session->set_userdata('user_email',$data['user_email']);

	}



	/*

	function update_profile_by_username($data,$user_name)

	{

		$this->db->update('users',$data,array('user_name'=>$user_name));

		$this->session->set_userdata('user_email',$data['user_email']);

	}

	*/



	function banuser($id,$limit)

	{

		$this->load->helper('date');

		$datestring = "%Y-%m-%d %h:%i:%a";

		$time = time();

		$data['banned'] 		= ($limit=='forever')?2:1;

		$data['banned_date'] 	= mdate($datestring, $time);

		if($limit!='forever')

		$data['banned_till']	= date('Y-m-d h:i:a', strtotime(' +'.$limit.' day'));

		$this->db->update('users',$data,array('id'=>$id));

	}



	function update_password($password)

	{

		$this->load->library('encrypt');

		$user_email = $this->session->userdata('user_email');

		$data['password'] = $this->encrypt->sha1($password);

		$data['recovery_key'] = '';

		$this->db->update('users',$data,array('user_email'=>$user_email));

	}



	function insert_post($data)

	{

		if($data['posttype']=='video')

		{

			// <iframe width="420" height="315" src="//www.youtube.com/embed/jIL0ze6_GIY" frameborder="0" allowfullscreen></iframe>

			// <iframe src="http://player.vimeo.com/video/VIDEO_ID" width="WIDTH" height="HEIGHT" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>

		}

		else

		{

			if($data['posttype']=='url')

			{

			/*	$filePath = filePath($data['url']);			

				$this->load->library('upload');

				$this->upload->file_ext = '.'.$filePath['extension'];

				$name = $this->upload->set_filename('./uploads/',$filePath['basename']);

				$img = './uploads/'.$name;

				file_put_contents($img, file_get_contents($data['url']));*/

				$url = $data['url'];

			}

			else

			{

				$url = base_url().'uploads/'.$data['file'];

			}



			if (is_animated($url))

	        {

	            $data['file_type'] = 'animation';

	        }

	        else

	        {

	            $data['file_type'] = 'normal';

	        }			

		}



		$this->db->insert('posts',$data);

		return $this->db->insert_id();

	}



	function update_post($data,$id)

	{

		$post = $this->get_post_by_id($id);

		if($post!=FALSE && $post->status!=1)

		{

			$this->db->update('posts',$data,array('id'=>$id));

			return array('error'=>0,'msg'=>'post updated');			

		}

		else

			return array('error'=>1,'msg'=>'post can\'t be updated');



	}



	function get_post_by_id($id)

	{

		$query = $this->db->get_where('posts',array('id'=>$id));

		if($query->num_rows()>0)

		return $query->row();

		else 

		return FALSE;

	}



	function get_all_user_posts_by_range($start,$limit='',$sort_by='',$id)

	{

		$this->db->order_by($sort_by, "asc");

		$this->db->where('status',1); 

		$this->db->where('created_by',$id);

		if($start=='all')

		$query = $this->db->get('posts');

		else

		$query = $this->db->get('posts',$limit,$start);

		return $query;

	}

	

	function count_all_user_posts($id)

	{

		$this->db->where('status',1); 		

		$this->db->where('created_by',$id); 

		$query = $this->db->get('posts');

		return $query->num_rows();

	}



	function get_all_posts_by_range($start,$limit='',$sort_by='')

	{

		$this->db->order_by($sort_by, "asc");

		//$this->db->where('status',1); 

		if($start=='all')

		$query = $this->db->get('posts');

		else

		$query = $this->db->get('posts',$limit,$start);

		return $query;

	}

	

	function count_all_posts()

	{

		//$this->db->where('status',1); 

		$query = $this->db->get('posts');

		return $query->num_rows();

	}



	function get_all_users_by_range($start,$limit='',$sort_by='')

	{

		$this->db->order_by($sort_by, "asc");

		$this->db->where('status',1); 

		if($start==='all')

		$query = $this->db->get('users');

		else

		$query = $this->db->get('users',$limit,$start);

		return $query;

	}

	

	function count_all_users()

	{

		$this->db->where('status',1); 

		$query = $this->db->get('users');

		return $query->num_rows();

	}
        
        
        #send recovery email
        function _send_recovery_email($data)
        {

                $val = $this->get_admin_email_and_name();

                $admin_email = $val['admin_email'];

                $admin_name  = $val['admin_name'];

                $link = site_url('account/resetpassword').'/'.$data['user_name'].'/'.$data['recovery_key'];



                $this->load->model('admin/system_model');

                $tmpl = $this->system_model->get_email_tmpl_by_email_name('recovery_email');

                $subject = $tmpl->subject;

                $subject = str_replace("#username",$data['user_name'],$subject);

                $subject = str_replace("#recoverylink",$link,$subject);

                $subject = str_replace("#webadmin",$admin_name,$subject);


                $body = $tmpl->body;

                $body = str_replace("#username",$data['user_name'],$body);

                $body = str_replace("#recoverylink",$link,$body);

                $body = str_replace("#webadmin",$admin_name,$body);


                $this->load->library('email');

                $this->email->from($admin_email, $subject);

                $this->email->to($data['user_email']);

                $this->email->subject($subject);		

                $this->email->message($body);		

                $this->email->send();

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

}



/* End of file install.php */
/* Location: ./application/modules/user/models/user_model_core.php */