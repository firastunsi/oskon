<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento facility_model_core model
 *
 * This class handles facility_model_core management related functionality
 *
 * @package		Admin
 * @subpackage	facility_model_core
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */


class Facility_model_core extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_all_facilities_by_range($start,$limit='',$sort_by='')
	{
		$this->db->where('status','1');
		if($start=='all')
		$query = $this->db->get('facilities');
		else
		$query = $this->db->get('facilities',$limit,$start);
		return $query;
	}
	
	function get_facility_by_alias($alias)
	{
		$query 	= $this->db->get_where('facilities',array('alias'=>$alias));
		$row 	= $query->row();
		return $row;
	}

	function set_facility_status($alias,$status)
	{
		$data['status'] = $status;
		$this->db->update('facilities',$data,array('alias'=>$alias));
	}

	function create_facility($data)
	{
		$data['alias'] = $this->get_alias($data['name']);
		$this->db->insert('facilities',$data);
	}

	function get_alias($name)
	{
		$name = underscore($name);
		$query = $this->db->get_where('facilities',array('alias'=>$name));
		if($query->num_rows()>0)
		{
			$count = $query->num_rows();
			$count++;
			$name = $name.'_'.$count;
			return $name;
		}
		else
			return $name;
	}

	#bulk update the facilities table
	function bulk_update_facilities($data,$ids) {
		
		if(!isset($ids) || !isset($data))
			return;

		$this->db->where_in('id',$ids);
		$this->db->update('facilities',$data);
	}

	#update a single record in facilities table by id
	function update_facility($data,$id) {
		
		if(!isset($id) || !isset($data))
			return;
		
		$this->db->where('id',$id);
		$this->db->update('facilities',$data);
	}

	#insert facility information into the database
	function insert_facility($data) {

		$this->db->insert('facilities',$data);
		return $this->db->insert_id();
	}

	#get a single facility by id
	function get_facility_by_id($id)
	{
		$query = $this->db->get_where('facilities',array('id'=>$id));
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

/* End of file facility_model_core.php */
/* Location: ./system/application/models/facility_model_core.php */