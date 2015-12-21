<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento category_model_core model
 *
 * This class handles category_model_core management related functionality
 *
 * @package		Admin
 * @subpackage	category_model_core
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */
class Category_model_core extends CI_Model 
{
	var $category,$menu;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->category = array();
	}

	function get_all_categories_by_range($start,$limit='',$sort_by='')
	{
		$this->db->order_by($sort_by, "asc");
		$this->db->where('status',1); 
		if($start=='all')
		$query = $this->db->get('categories');
		else
		$query = $this->db->get('categories',$limit,$start);
		return $query;
	}
	
	function count_all_categories()
	{
		$this->db->where('status',1); 
		$query = $this->db->get('categories');
		return $query->num_rows();
	}
	
	function delete_category_by_id($id)
	{
		$data['status'] = 0;
		$this->db->update('categories',$data,array('id'=>$id));
	}

	function insert_category($data)
	{
		$this->db->insert('categories',$data);
		return $this->db->insert_id();
	}

	function update_category($data,$id)
	{
		$this->db->update('categories',$data,array('id'=>$id));
	}

	function get_category_by_id($id)
	{
		$query = $this->db->get_where('categories',array('id'=>$id));
		if($query->num_rows()<=0)
		{
			echo 'Invalid page id';die;
		}
		else
		{
			return $query->row();
		}
	}

}

/* End of file category_model_core.php */
/* Location: ./system/application/models/category_model_core.php */