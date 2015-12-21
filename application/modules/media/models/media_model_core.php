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
class Media_model_core extends CI_Model 
{
	var $_table = 'media';
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_all_medias_by_range($start,$limit,$sort_by)
	{
		if($sort_by!='')
		$this->db->order_by($sort_by, "desc");
		$this->db->where('status',1); 
		$query = $this->db->get($this->_table,$limit,$start);
		return $query;
	}
	
	function count_all_medias()
	{
		$this->db->where('status',1);
		$query = $this->db->get($this->_table);
		return $query->num_rows();
	}
	
	function delete_media($id)
	{
		$data['status'] = 0;
		$this->db->update($this->_table,$data,array('id'=>$id));
	}
	
	function insert_media($data)
	{
		$this->db->insert($this->_table,$data);
	}
	
	function update_media($data,$id)
	{
		$this->db->update($this->_table,$data,array('id'=>$id));
	}
	
	function get_media_by_id($id)
	{
		$query = $this->db->get_where($this->_table,array('id'=>$id));
		return $query->row();
	}
	
	function get_id_by_username($user_name)
	{
		$query = $this->db->get_where('users',array('user_name'=>$user_name));
		$row = $query->row();
		return $row->id;
	}
	
	function checkpermission($user_id,$post_id)
	{
		$query = $this->db->get_where('themes',array('id'=>$post_id,'created_by'=>$user_id));
		return $query->num_rows();
	}
}

/* End of file install.php */
/* Location: ./application/modules/media/models/media__model_core.php */