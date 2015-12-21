<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento package_model model
 *
 * This class handles package_model management related functionality
 *
 * @package		Admin
 * @subpackage	package_model
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

require_once'package_model_core.php';
class Package_model extends Package_model_core {

	public function __construct()
	{
		parent::__construct();
	}
        
        
        function get_all_packages_by_range($start,$limit='',$sort_by='')
	{
               
                $this->db->where('status','1');
                
                // incase renew don't show the free packages
                $renew = $this->session->userdata('renew');
                if (isset($renew) && $renew == '1'){
                    $this->db->where('price >','0');
                }
                
		if($start=='all')
		$query = $this->db->get('packages');
		else
		$query = $this->db->get('packages',2,$start);
		return $query;  
	}
        
}