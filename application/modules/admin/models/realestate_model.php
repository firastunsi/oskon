<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento category model
 *
 * This class handles category management related functionality
 *
 * @package		Admin
 * @subpackage	category
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

require_once'realestate_model_core.php';

class Realestate_model extends Realestate_model_core 
{
	function __construct()
	{
		parent::__construct();
	}
        
        
        function get_types_json($category){
                $types = array();
                $this->load->config('realcon');
                $custom_types = $this->config->item('property_types');
                
                $str = '<option value="">'.lang_key('Select Type').'</option>';
                foreach ($custom_types as $type) { 
                        if ($type['category'] == $category){
                            $str .='<option value="'.$type['title'].'">'.lang_key($type['title']).'</option>';
                        }
                }
                return $str;
        }
        
        
        function get_locations_state_json($type,$parent)
	{
                $this->db->select('*');
                $this->db->from('locations');
                $this->db->where(array('status'=>1,'type'=>$type,'parent'=>$parent));
                $this->db->order_by('name','ASC');
                $query = $this->db->get();
                
		$data = array();
                
                $str = '<option value="" selected="selected">'.lang_key('Select City').'</option>';
		foreach ($query->result() as $row) {
			$val = array();
			$val['id'] = $row->id;
			$val['label'] = $row->name;
			$val['value'] = trim($row->name);
                        $str .='<option data-id="'.$val['id'].'" value="'.$val['value'].'">'.lang_key($val['label']).'</option>';
		}
		return $str;
	}
        
        
        function get_locations_region_json($type,$parent)
	{
                $this->db->select('*');
                $this->db->from('locations');
                $this->db->where(array('status'=>1,'type'=>$type,'parent'=>$parent));
                $this->db->order_by('name','ASC');
                $query = $this->db->get();
                
		$data = array();
                $str = '<option value="" selected="selected">'.lang_key('Select Region').'</option>';
		foreach ($query->result() as $row) {
			$val = array();
			$val['id'] = $row->id;
			$val['label'] = $row->name;
                        $str .='<option value="'.$val['label'].'">'.lang_key($val['label']).'</option>';
		}
		return $str;
	}
        
        
        

}

/* End of file category.php */
/* Location: ./system/application/models/category.php */