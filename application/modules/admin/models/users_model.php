<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento users_model model
 *
 * This class handles users_model management related functionality
 *
 * @package		Admin
 * @subpackage	users_model
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

require_once'users_model_core.php';

class Users_model extends Users_model_core
{
    function __construct()
    {
        parent::__construct();
    }
    
    function is_premuim_package($user_id){
        $this->db->where(array('user_id'=> $user_id,'user_meta.key'=> 'current_package','user_meta.value <>'=> '1'));
        $query = $this->db->get('user_meta');
        return $query->num_rows();
    }
    
    
    function get_all_agents($start,$limit='',$sort_by='' , $sort)
    {
        
            // get all agents (that have package id <> 1 (means not a free package) and not is admin)
            $this->db->select('*');
            $this->db->from('users');
            $this->db->join('user_meta', 'user_meta.user_id = users.id');
            $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
            $this->db->where(array('user_meta.key'=> 'current_package','user_meta.value <>'=> '1'));
            $this->db->order_by("users.".$sort_by, $sort);
            $query = $this->db->get();
            return $query;
    }

}

/* End of file users_model.php */
/* Location: ./system/application/models/users_model.php */