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
class Realestate_model_core extends CI_Model 
{
	var $category,$menu;

	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->category = array();
	}

	function check_post_permission($id)
	{
		$post = $this->get_estate_by_id($id);
		if(is_admin()==FALSE && $post->created_by!=$this->session->userdata('user_id'))
		{
			return FALSE;
		}
		else
			return TRUE;
	}

	function get_estate_by_id($id)
	{
		$query = $this->db->get_where('posts',array('id'=>$id,'status !='=>0));
		if($query->num_rows()>0)
		{
			return $query->row();
		}
		else
		{
			die('Estate not found');
		}
	}

	function update_estate($data,$id)
	{
		$this->db->update('posts',$data,array('id'=>$id));
	}

	function update_estate_meta($data,$id,$key)
	{
		$this->db->update('post_meta',$data,array('post_id'=>$id,'key'=>$key));
	}

	function insert_estate($data)
	{
		$this->db->insert('posts',$data);
		return $this->db->insert_id();
	}

	function insert_estate_meta($data)
	{
		$this->db->insert('post_meta',$data);
	}

	function get_all_estates_admin($start,$limit,$order_by='id',$order_type='asc')
	{

		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}
		else
		{
			$where = "(status=1 or status=2)";
			$this->db->where($where);
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'id';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'DESC';
			$this->db->order_by($order_by,$order_type);
		}
		else
		{
			$this->db->order_by('id','desc');
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));
		

		$query = $this->db->get('posts',$limit,$start);
		return $query;
	}

	function count_all_estates_admin()
	{
		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'title';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'ASC';
			$this->db->order_by($order_by,$order_type);
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));

		$query = $this->db->get('posts');
		return $query->num_rows();
	}

	function get_all_estates_agent($start,$limit,$order_by='id',$order_type='asc')
	{
		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'title';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'ASC';
			$this->db->order_by($order_by,$order_type);
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));

		$this->db->where('created_by',$this->session->userdata('user_id'));
		$query = $this->db->get_where('posts',array('status !='=>0),$limit,$start);
		return $query;
	}

	function count_all_estates_agent()
	{
		if($this->session->userdata('filter_purpose')!='')
		{
			$this->db->where('purpose',$this->session->userdata('filter_purpose'));
		}

		if($this->session->userdata('filter_type')!='')
		{
			$this->db->where('type',$this->session->userdata('filter_type'));
		}

		if($this->session->userdata('filter_condition')!='')
		{
			$this->db->where('estate_condition',$this->session->userdata('filter_condition'));
		}

		if($this->session->userdata('filter_status')!='')
		{
			$this->db->where('status',$this->session->userdata('filter_status'));
		}

		if($this->session->userdata('filter_orderby')!='')
		{
			$order_by 	= ($this->session->userdata('filter_orderby')!='')?$this->session->userdata('filter_orderby'):'title';
			$order_type = ($this->session->userdata('filter_ordertype')!='')?$this->session->userdata('filter_ordertype'):'ASC';
			$this->db->order_by($order_by,$order_type);
		}

		if($this->input->post('id_search')!='')
			$this->db->where('id',$this->input->post('id_search'));
		
		$this->db->where('created_by',$this->session->userdata('user_id'));
		$query = $this->db->get_where('posts',array('status !='=>0));
		return $query->num_rows();
	}

	function get_all_estates($start,$limit,$order_by='id',$order_type='asc')
	{
		$this->db->order_by($order_by,$order_type);
		$query = $this->db->get_where('posts',array('status !='=>0),$limit,$start);
		return $query;
	}

	function count_all_estates()
	{
		$query = $this->db->get_where('posts',array('status !='=>0));
		return $query->num_rows();
	}

	function get_location_id_by_name($name,$type,$parent)
	{
		$query = $this->db->get_where('locations',array('status'=>1,'name'=>$name,'type'=>$type,'parent'=>$parent));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			return $row->id;
		}
		else
		{
			$data = array();
			$data['type'] 	= $type;
			$data['name'] 	= $name;
			$data['parent']	= $parent;
			$this->db->insert('locations',$data);
			return $this->db->insert_id();
		}
	}
        
        
    

	function get_locations_json($term='',$type,$parent)
	{
		$this->db->like('name',$term);
		$query = $this->db->get_where('locations',array('status'=>1,'type'=>$type,'parent'=>$parent));
		$data = array();
		foreach ($query->result() as $row) {
			$val = array();
			$val['id'] = $row->id;
			$val['label'] = $row->name;
			$val['value'] = trim($row->name);
			array_push($data,$val);
		}
		return $data;
	}

	function get_all_locations_json($term='')
	{
		$this->db->like('name',$term);
		$query = $this->db->get_where('locations',array('status'=>1));
		$data = array();
		foreach ($query->result() as $row) {
			$val = array();
			$val['id'] = $row->id;
			$val['label'] = $row->name;
			$val['value'] = trim($row->name);
			array_push($data,$val);
		}
		return $data;
	}

	function get_all_locations_by_range($start,$limit='',$sort_by='')
	{
		$data = array();
		$this->db->order_by($sort_by, "asc");
		$this->db->where('status',1);
		$this->db->where('parent',0);
		$query = $this->db->get('locations');
		foreach ($query->result() as $country) {
			array_push($data,$country);
			$state_query = $this->db->get_where('locations',array('status'=>1,'parent'=>$country->id));
			foreach ($state_query->result() as $state) {
				array_push($data,$state);
				$city_query = $this->db->get_where('locations',array('status'=>1,'parent'=>$state->id));
				foreach ($city_query->result() as $city) {
					array_push($data,$city);
				}
			}
		}

		return array_slice($data,$start,$limit,true);
	}
	
	function count_all_locations()
	{
		$this->db->where('status',1); 
		$query = $this->db->get('locations');
		return $query->num_rows();
	}
	
	function insert_location($data)
	{
		$this->db->insert('locations',$data);
		return $this->db->insert_id();
	}

	function get_locations_by_type($type)
	{
		$query = $this->db->get_where('locations',array('type'=>$type,'status'=>1));
		return $query;
	}

	function get_location_by_id($id)
	{
		$query = $this->db->get_where('locations',array('id'=>$id));
		if($query->num_rows()<=0)
		{
			echo 'Invalid page id';die;
		}
		else
		{
			return $query->row();
		}
	}

	function delete_location_by_id($id)
	{
		$data['status'] = 0;
		$this->db->update('locations',$data,array('id'=>$id));
		$this->db->update('locations',$data,array('parent'=>$id));
	}


	function update_location($data,$id)
	{
		$this->db->update('locations',$data,array('id'=>$id));
	}

	function get_all_payment_history($start,$limit,$order_by='id',$order_type='asc')
	{
		if($this->input->post('transaction_id')!='')
		{
			$this->db->where('unique_id',$this->input->post('transaction_id'));
		}

		$this->db->order_by($order_by,$order_type);
		$query = $this->db->get_where('user_package',array('status !='=>0),$limit,$start);
		return $query;
	}

	function count_all_payment_history()
	{
		if($this->input->post('transaction_id')!='')
		{
			$this->db->where('unique_id',$this->input->post('transaction_id'));
		}
		
		$query = $this->db->get_where('user_package',array('status !='=>0));
		return $query->num_rows();
	}

	function deletehistory($id=0)
	{
		$this->db->update('user_package',array('status'=>0),array('id'=>$id));
	}

	function delete_post_by_id($id='')
	{
		$data['status'] = 0;
		$this->db->update('posts',$data,array('id'=>$id));
	}

	function update_post_by_id($data,$id)
	{
		$this->db->update('posts',$data,array('id'=>$id));
	}	

	function get_all_emails_admin($start,$limit)
	{
		if($start=='all')
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1));
			return $query;	
		}
		else
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1),$limit,$start);
			return $query;			
		}
	}

	function count_all_emails_admin()
	{
		$this->db->like('key','query_email');
		$query = $this->db->get_where('user_meta',array('status'=>1));
		return $query->num_rows();
	}

	function get_all_emails_agent($start,$limit)
	{
		if($start=='all')
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1,'user_id'=>$this->session->userdata('user_id')));
			return $query;
		}
		else
		{
			$this->db->like('key','query_email');
			$query = $this->db->get_where('user_meta',array('status'=>1,'user_id'=>$this->session->userdata('user_id')),$limit,$start);
			return $query;			
		}
	}

	function count_all_emails_agent()
	{
		$this->db->like('key','query_email');
		$query = $this->db->get_where('user_meta',array('status'=>1,'user_id'=>$this->session->userdata('user_id')));
		return $query->num_rows();
	}

	function get_all_emails()
	{
		if(!is_admin())
			$this->db->where('user_id',$this->session->userdata('user_id'));

		$this->db->like('key','query_email');
		$query = $this->db->get_where('user_meta',array('status'=>1));
		return $query;
	}

	function get_feature_payment_data_by_unique_id($unique_id)
	{
		$this->db->where('key','featurepayment_'.$unique_id);
		$query = $this->db->get_where('post_meta',array('status'=>1));
		return $query;
	}


	function get_all_estates_by_user_type($return_type='all')
	{

		if(!is_admin())
		$this->db->where('created_by',$this->session->userdata('user_id'));
		
		if($return_type=='all')
		{
			$query = $this->db->get('posts');
			return $query;			
		}
		else
		{
			$query = $this->db->get('posts');
			return $query->num_rows();						
		}
	}
}

/* End of file category_model_core.php */
/* Location: ./system/application/models/category_model_core.php */