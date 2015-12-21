<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento auth model
 *
 * This class handles user account related functionality
 *
 * @package		Account
 * @subpackage	models/auth
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */


class Auth_model_core extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	function check_login($user_email,$password,$return_as='num_rows')
	{
		$this->load->library('encrypt');
		$password = $this->encrypt->sha1($password);
		$this->db->where('user_email',$user_email);
		$query = $this->db->get_where('users',array('password'=>$password));
		if($return_as=='num_rows')
		return $query->num_rows();
		else
		return $query;
	}

	function set_login_cookie($user_email)
	{
		$val = rand(1000,9000);
		$cookie = array(
                   'name'   => 'key',
                   'value'  => $val,
                   'expire' => '86500',
                   'domain' => '.localhost',
                   'path'   => '/',
                   'prefix' => 'mycookie_',
               );

		set_cookie($cookie);
		
		$cookie = array(
                   'name'   => 'user',
                   'value'  => $user_email,
                   'expire' => '86500',
                   'domain' => '.localhost',
                   'path'   => '/',
                   'prefix' => 'mycookie_',
               );

		set_cookie($cookie);
		
		$data['remember_me_key'] = $val;
		$this->db->update('users',$data,array('user_email'=>$user_email));
	}
	
	function check_cookie_val($user,$key)
	{
		$query = $this->db->get_where('users',array('user_email'=>$user,'remember_me_key'=>$key));
		if($query->num_rows()>0)
		{
			$this->session->set_userdata('user_email',$user);
		}
	}
	
	function is_email_exists($email)
	{
		$query = $this->db->get_where('users',array('user_email'=>$email));
		return $query->num_rows();
	}

	function is_username_exists($user_name)
	{
		$query = $this->db->get_where('users',array('user_name'=>$user_name));
		return $query->num_rows();
	}

	function is_email_exists_for_edit($email,$id)
	{
		$query = $this->db->get_where('users',array('user_email'=>$email,'id !='=>$id));
		return $query->num_rows();
	}

	function is_username_exists_for_edit($user_name,$id)
	{
		$query = $this->db->get_where('users',array('user_name'=>$user_name,'id !='=>$id));
		return $query->num_rows();
	}

	
	function set_recovery_key($user_email)
	{
		$recovery_key = uniqid();
		$this->db->update('users',array('recovery_key'=>$recovery_key),array('user_email'=>$user_email));
		
		$query = $this->db->get_where('users',array('user_email'=>$user_email));
		$data = $query->row_array();
		$data['recovery_key'] = $recovery_key;
		//echo '<pre>';print_r($data);die;
		return $data;
	}

	function verify_recovery_by_username($user_name,$recovery_key)
	{
		if($user_name=='' || $recovery_key=='')
			return 0;
		else
		{
			$query = $this->db->get_where('users',array('user_name'=>$user_name,'recovery_key'=>$recovery_key));
			return $query;
		}
	}

	function verify_recovery($user_email,$recovery_key)
	{
		if($user_email=='' || $recovery_key=='')
			return 0;
		else
		{
			$query = $this->db->get_where('users',array('user_email'=>$user_email,'recovery_key'=>$recovery_key));
			return $query->num_rows();
		}
	}
	
	function get_userdata_by_email($email)
	{
		$query = $this->db->get_where('users',array('user_email'=>$email));
		return $query->row_array();
	}

	function confirm_email($email,$code)
	{
		$query = $this->db->get_where('users',array('user_email'=>$email,'confirmation_key'=>$code));
		if($query->num_rows()>0)
		{
			$this->load->helper('date');
			$datestring = "%Y-%m-%d %h:%i:%a";
			$time = time();

			$data['confirmed'] = 1;
			$data['confirmed_date'] = mdate($datestring, $time);
			$data['confirmation_key'] = '';
			$this->db->update('users',$data,array('user_email'=>$email));
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function register_user_if_not_exists($user,$network='')
	{
		$query = $this->db->get_where('users',array('user_email'=>$user['email']));
		if($query->num_rows()>0)
		{
			$row = $query->row_array();
			return $row;
		}
		else
		{
			$userdata 				= array();
			$userdata['user_type']	= ($this->session->userdata('signup_user_type')!='')?$this->session->userdata('signup_user_type'):2;//2 = users
			$userdata['first_name'] = $user['first_name'];
			$userdata['last_name'] 	= $user['last_name'];
			$userdata['gender'] 	= $user['gender'];
			if($network=='google')
			{
				$userdata['user_name'] 	= 'gp_'.$user['username'];
			}
			else
			{
                                    $userdata['user_name'] 	= 'fb_'.$user['id']; // firas change this to fb_user_id coz there is no usermame in fb and u can't retrive it
			}
			$userdata['user_email'] = $user['email'];
			$userdata['password'] 	= '';
			$userdata['confirmed'] 	= 1;
			$userdata['status']		= 1;
			$this->db->insert('users',$userdata);
			$userdata['id']			= $this->db->insert_id();
			return $userdata;		
		}
	}
}

/* End of file auth_model.php */
/* Location: ./system/application/modules/account/models/auth_model.php */