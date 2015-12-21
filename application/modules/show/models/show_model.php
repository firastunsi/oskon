<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Show
 * @subpackage	ShowModel
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */
require_once'show_model_core.php';
class Show_model extends Show_model_core {

	public function __construct()
	{
		parent::__construct();
	}
        
        function get_users_by_range_notfree($start,$limit='',$sort_by='',$sort='desc')
        {
            
            // get all agents (that have package id <> 1 (means not a free package) and not is admin)
            $this->db->select('*');
            $this->db->from('users');
            $this->db->join('user_meta', 'user_meta.user_id = users.id');
            $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
            $this->db->where(array('user_meta.key'=> 'current_package','user_meta.value <>'=> '1'));
                
            if($this->input->post('agent_key')!='')
            {
                $key = $this->input->post('agent_key');
                $this->db->where("( `dbc_users`.`first_name` LIKE '%$key%' OR `dbc_users`.`last_name` LIKE '%$key%' OR `dbc_users`.`user_email` LIKE '%$key%' )");
            }
            
            $this->db->order_by("users.".$sort_by,$sort);
            $query = $this->db->get();
            
            //print $this->db->last_query();exit;
            return $query;

        }
        
        
        
      function count_users_notfree(){
            // get all agents (that have package id <> 1 (means not a free package) and not is admin)
            $this->db->select('*');
            $this->db->from('users');
            $this->db->join('user_meta', 'user_meta.user_id = users.id');
            $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
            $this->db->where(array('user_meta.key'=> 'current_package','user_meta.value <>'=> '1'));
                
            if($this->input->post('agent_key')!='')
            {
                $key = $this->input->post('agent_key');
                $this->db->where("( `dbc_users`.`first_name` LIKE '%$key%' OR `dbc_users`.`last_name` LIKE '%$key%' OR `dbc_users`.`user_email` LIKE '%$key%' )");
            }
          
            $query = $this->db->get();
            
            $total = 0;
            foreach($query->result() as $user){ 
                    $is_expired = is_user_package_expired($user->user_id);
                    if($is_expired){
                        continue;
                    }
                    else{
                        $total++;
                    }
            }
            
            return $total;

    }
    

    
    
        function get_properties_by_purpose_range($purpose,$start,$limit='',$sort_by='',$sort='desc')

	{

		if($this->session->userdata('view_orderby')!='')

		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by("posts".$order_by,$order_type);

		}
		else
			$this->db->order_by("posts".$sort_by,$sort);


                if ($purpose != 'DBC_PURPOSE_BOTH'){
                    $this->db->where('posts.purpose',$purpose);
                }
                
		

		$this->db->where('status',1); 
                // don't get the admin properties
                $this->db->join('users', 'users.id = posts.created_by');
                $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));

		if($start==='all')

		{

			$query = $this->db->get('posts');

		}

		else

		{

			$query = $this->db->get('posts',$limit,$start);

		}
                
                
                //print $this->db->last_query();
		return $query;

	}
        
        
        
        function count_properties_by_purpose($purpose)

	{
                if ($purpose != 'DBC_PURPOSE_BOTH'){
                    $this->db->where('posts.purpose',$purpose);
                }
		

		$this->db->where('posts.status',1);
                // don't get the admin properties
                $this->db->join('users', 'users.id = posts.created_by');
                $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));

		$query = $this->db->get('posts');

		return $query->num_rows();

	}
        
        function get_advanced_search_result($data,$start = '0',$limit = '10' , $type_page = '') {

    	#if the radius is not set then the country,city,state will be matched exactly
    	#if the radius is set then the country,city,state will be used to get the lat/long of the location

            
          
    	$is_radius_set = isset($data['radius']) && trim($data['radius'])!='';

    	$state_id;$city_id;$country_name;

    	if(isset($data['country']) && trim($data['country'])!='') {
	    	$country_name = $this->get_country_name_by_id($data['country']);
    	}

    	if(isset($data['state']) && trim($data['state'])!='') {
    		$state_id = $this->get_location_id_by_name($data['state'],'state');
    	}

    	if(isset($data['city']) && trim($data['city'])!='') {
    		$city_id = $this->get_location_id_by_name($data['city'],'city');
    	}

    	if(isset($data['plainkey']) && trim($data['plainkey'])!='' && isset($data['ignor_plain'])==false) {
          
    		$search_string = rawurldecode($data['plainkey']);

    		$search_string = trim($search_string);

			$search_string = explode(" ", $search_string);

    		

    		$sql = "";

    		$flag = 0;


                  
    		foreach ($search_string as $key) {
                    if($flag==0) {
                            $flag = 1;
                    }
                    else {
                            $sql .= "AND ";
                    }
                    $sql .= "`search_meta` LIKE '%".$key."%' ";
                }
                $this->db->where($sql);

    	}

    	$string_for_lat_long = "";

    	if(isset($data['country']) && trim($data['country'])!='' && isset($data['ignor_location'])==false) {
    		if($is_radius_set) 
	    		$string_for_lat_long .= $country_name."+";
	    	else 
	    		$this->db->where('posts.country', $data['country']);
    	}


    	if(isset($data['state']) && trim($data['state'])!='' && isset($data['ignor_location'])==false) {
    		if($is_radius_set)
	    		$string_for_lat_long .= $data['state']."+";
	    	else
	    		$this->db->where_in('posts.state', $state_id);
    	}

    	if(isset($data['city']) && trim($data['city'])!='' && isset($data['ignor_location'])==false) {

    		if($is_radius_set)
	    		$string_for_lat_long .= $data['city']."+";
	    	else
	    		$this->db->where_in('posts.city', $city_id);

    	}



        if(isset($data['purpose_sale']) && trim($data['purpose_sale'])!='') {
                $this->db->where('posts.purpose', $data['purpose_sale']);
        }

        //SALE
        if(isset($data['purpose_sale']) && trim($data['purpose_sale'])=='DBC_PURPOSE_SALE'){
           if(isset($data['price_min']) && trim($data['price_min'])!='') {
                $this->db->where('posts.total_price >=', $data['price_min']);
           }
           if(isset($data['price_max']) && trim($data['price_max'])!='') {
                $this->db->where('posts.total_price <=', $data['price_max']);
           }
        }
        //RENT
        else if(isset($data['purpose_sale']) && trim($data['purpose_sale'])=='DBC_PURPOSE_RENT'){
            if(isset($data['price_min']) && trim($data['price_min'])!='') {
                $this->db->where('posts.rent_price >=', $data['price_min']);
            }
            if(isset($data['price_max']) && trim($data['price_max'])!='') {
                $this->db->where('posts.rent_price <=', $data['price_max']);
            }
//            if(isset($data['rent_price_unit']) && trim($data['rent_price_unit'])!='') {
//                $this->db->where('posts.rent_price_unit', $data['rent_price_unit']);
//            }
        }
        else{ //ALL
           //SALE
//                   if(isset($data['price_min']) && trim($data['price_min'])!='') {
//        		$this->db->where('total_price >=', $data['price_min']);
//                   }
//                   if(isset($data['price_max']) && trim($data['price_max'])!='') {
//        		$this->db->where('total_price <=', $data['price_max']);
//                   }
//                   //RENT
//                   if(isset($data['rent_price_min']) && trim($data['rent_price_min'])!='') {
//        		$this->db->or_where('rent_price >=', $data['rent_price_min']);
//                   }
//                   if(isset($data['rent_price_max']) && trim($data['rent_price_max'])!='') {
//                        $this->db->where('rent_price <=', $data['rent_price_max']);
//                   }
//                   if(isset($data['rent_price_unit']) && trim($data['rent_price_unit'])!='') {
//        		$this->db->where('rent_price_unit', $data['rent_price_unit']);
//                   }
        }


//    		if(isset($data['price_per_unit_min']) && trim($data['price_per_unit_min'])!='') {
//    			$this->db->where('price_per_unit >=', $data['price_per_unit_min']);
//        	}
//        	if(isset($data['price_per_unit_max']) && trim($data['price_per_unit_max'])!='') {
//        		$this->db->where('price_per_unit <=', $data['price_per_unit_max']);
//        	}
//        	if(isset($data['price_unit']) && trim($data['price_unit'])!='') {
//        		$this->db->where('price_unit', $data['price_unit']);
//        	}



        if(isset($data['bedroom_min']) && trim($data['bedroom_min'])!='') {
                $this->db->where('posts.bedroom >=', $data['bedroom_min']);
        }

        if(isset($data['bedroom_max']) && trim($data['bedroom_max'])!='') {
                $this->db->where('posts.bedroom <=', $data['bedroom_max']);

        }

        if(isset($data['bath_min']) && trim($data['bath_min'])!='') {
                $this->db->where('posts.bath >=', $data['bath_min']);
        }

        if(isset($data['bath_max']) && trim($data['bath_max'])!='') {
                $this->db->where('posts.bath <=', $data['bath_max']);
        }


//        	if(isset($data['year_min']) && trim($data['year_min'])!='') {
//        		$this->db->where('year_built >=', $data['year_min']);
//        	}
//
//        	if(isset($data['year_max']) && trim($data['year_max'])!='') {
//        		$this->db->where('year_built <=', $data['year_max']);
//        	}


        if(isset($data['hdn_agent']) && trim($data['hdn_agent'])!='') {
            $this->db->where('posts.created_by', $data['hdn_agent']);
        }


        if(isset($data['type'])) {
                $type = $data['type'];
                if(is_array($type)) {
                        $this->db->where_in('posts.type', array_filter($type));
                }
                else if(trim($type)!='' && trim($type)!='DBC_TYPE_ALL'){
                        $this->db->where('posts.type', $type);
                }
        }


//        	if(isset($data['condition'])) {
//        		$condition = $data['condition'];
//        		if(is_array($condition)) {
//        			$this->db->where_in('estate_condition', array_filter($condition));
//        		}
//        		else if(trim($condition)!=''){
//    				$this->db->where('estate_condition', $condition);
//        		}
//        	}




    	if($is_radius_set && isset($data['ignor_location'])==false) {
    		$string_for_lat_long = rtrim($string_for_lat_long, "+");
    		$lat_long = $this->get_latitude_longitude($string_for_lat_long);
    		if($lat_long != null) {
                $radius_in_kms = $data['radius'] * 1.60934;
                $radius_condition = "(6371.0 * 2 * ASIN(SQRT(POWER(SIN((".$lat_long['lat']." - posts.latitude) * PI() / 180 / 2), 2) + COS(".$lat_long['lat']." * PI() / 180)
                                    * COS(posts.latitude * PI() / 180) * POWER(SIN((".$lat_long['lng']." - posts.longitude) * PI() / 180 / 2), 2))) <= ".$radius_in_kms.")";

				$this->db->where($radius_condition);

    		}
    	}

       
        
        if($this->session->userdata('view_orderby')!='') {
            $order_by   = ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';
            $order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';
            $this->db->order_by('posts.'.$order_by,$order_type);
        }
        else{
            if ($type_page == 'top'){
                $this->db->order_by('posts.total_view','DESC');
            }
            else{
                $this->db->order_by('posts.id','DESC');
            }
        }
       
        
        $this->db->where('posts.status','1');
        
        // don't get the admin properties
        $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
        
        // don't get the expired users
         $this->db->where(array('user_meta.key'=>'expirtion_date','user_meta.value >' => date('Y-m-d',now())));
        
        if ($type_page == 'featured'){
            $this->db->where('posts.featured',1);
        }

       
       
        if ($start === 'all'){
            //firas custom here : get all posts in case result search and the view always is map view
            $this->db->select('posts.*');
            $this->db->select('users.id as `uid`, users.user_type');
            //if (get_settings('realestate_settings', 'hide_posts_if_expired', 'No') == 'Yes') {
                $this->db->select('user_meta.value');
            //}
            $this->db->from('posts');
            $this->db->join('users', 'users.id = posts.created_by');
            //if (get_settings('realestate_settings', 'hide_posts_if_expired', 'No') == 'Yes') {
                $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
            //}
            $query = $this->db->get('');
        }
        else{
            $this->db->select('posts.*');
            $this->db->select('users.id as `uid`, users.user_type');
            $this->db->select('user_meta.value');
            $this->db->from('posts');
            $this->db->join('users', 'users.id = posts.created_by');
            $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
            $query = $this->db->get('',$limit,$start);
        }
        
      
        //print $this->db->last_query();exit;
	return $query;

    }
    
    
       function count_search_result($data,$type_page ='') {


        #if the radius is not set then the country,city,state will be matched exactly
        #if the radius is set then the country,city,state will be used to get the lat/long of the location

        $is_radius_set = isset($data['radius']) && trim($data['radius'])!='';

        $state_id;$city_id;$country_name;

        if(isset($data['country']) && trim($data['country'])!='') {
            $country_name = $this->get_country_name_by_id($data['country']);
        }

        if(isset($data['state']) && trim($data['state'])!='') {
            $state_id = $this->get_location_id_by_name($data['state'],'state');
        }

        if(isset($data['city']) && trim($data['city'])!='') {
            $city_id = $this->get_location_id_by_name($data['city'],'city');
        }

        if(isset($data['plainkey']) && trim($data['plainkey'])!='' && isset($data['ignor_plain'])==false) {

            $search_string = rawurldecode($data['plainkey']);

            $search_string = trim($search_string);

            $search_string = explode(" ", $search_string);

            

            $sql = "";

            $flag = 0;



            foreach ($search_string as $key) {

            if($flag==0) {

                $flag = 1;

            }

            else {

                $sql .= "AND ";

            }

            $sql .= "`search_meta` LIKE '%".$key."%' ";


            }



            $this->db->where($sql);

        }



        $string_for_lat_long = "";



        if(isset($data['country']) && trim($data['country'])!='' && isset($data['ignor_location'])==false) {


            if($is_radius_set) 
                $string_for_lat_long .= $country_name."+";
            else 
                $this->db->where('posts.country', $data['country']);

        }



        if(isset($data['state']) && trim($data['state'])!='' && isset($data['ignor_location'])==false) {

            if($is_radius_set)
                $string_for_lat_long .= $data['state']."+";
            else
                $this->db->where_in('posts.state', $state_id);

        }



        if(isset($data['city']) && trim($data['city'])!='' && isset($data['ignor_location'])==false) {

            if($is_radius_set)
                $string_for_lat_long .= $data['city']."+";
            else
                $this->db->where_in('posts.city', $city_id);

        }


        

        if(isset($data['purpose_sale']) && trim($data['purpose_sale'])!='') {
            $this->db->where('posts.purpose', $data['purpose_sale']);
        }
            
              //SALE
        if(isset($data['purpose_sale']) && trim($data['purpose_sale'])=='DBC_PURPOSE_SALE'){
           if(isset($data['price_min']) && trim($data['price_min'])!='') {
                $this->db->where('posts.total_price >=', $data['price_min']);
           }
           if(isset($data['price_max']) && trim($data['price_max'])!='') {
                $this->db->where('posts.total_price <=', $data['price_max']);
           }
        }
        //RENT
        else if(isset($data['purpose_sale']) && trim($data['purpose_sale'])=='DBC_PURPOSE_RENT'){
            if(isset($data['price_min']) && trim($data['price_min'])!='') {
                $this->db->where('posts.rent_price >=', $data['price_min']);
            }
            if(isset($data['price_max']) && trim($data['price_max'])!='') {
                $this->db->where('posts.rent_price <=', $data['price_max']);
            }
//            if(isset($data['rent_price_unit']) && trim($data['rent_price_unit'])!='') {
//                $this->db->where('posts.rent_price_unit', $data['rent_price_unit']);
//            }
        }
        else{ //ALL
                    //SALE
//                   if(isset($data['price_min']) && trim($data['price_min'])!='') {
//        		$this->db->where('total_price >=', $data['price_min']);
//                   }
//                   if(isset($data['price_max']) && trim($data['price_max'])!='') {
//        		$this->db->where('total_price <=', $data['price_max']);
//                   }
//                   //RENT
//                   if(isset($data['rent_price_min']) && trim($data['rent_price_min'])!='') {
//        		$this->db->or_where('rent_price >=', $data['rent_price_min']);
//                   }
//                   if(isset($data['rent_price_max']) && trim($data['rent_price_max'])!='') {
//                        $this->db->where('rent_price <=', $data['rent_price_max']);
//                   }
//                   if(isset($data['rent_price_unit']) && trim($data['rent_price_unit'])!='') {
//        		$this->db->where('rent_price_unit', $data['rent_price_unit']);
//                   }
        }



//            if(isset($data['price_per_unit_min']) && trim($data['price_per_unit_min'])!='') {
//                $this->db->where('price_per_unit >=', $data['price_per_unit_min']);
//
//            }
//            if(isset($data['price_per_unit_max']) && trim($data['price_per_unit_max'])!='') {
//                $this->db->where('price_per_unit <=', $data['price_per_unit_max']);
//            }
//            if(isset($data['price_unit']) && trim($data['price_unit'])!='') {
//                $this->db->where('price_unit', $data['price_unit']);
//            }


            if(isset($data['bedroom_min']) && trim($data['bedroom_min'])!='') {
                $this->db->where('posts.bedroom >=', $data['bedroom_min']);
            }

            if(isset($data['bedroom_max']) && trim($data['bedroom_max'])!='') {
                $this->db->where('posts.bedroom <=', $data['bedroom_max']);
            }

            if(isset($data['bath_min']) && trim($data['bath_min'])!='') {
                $this->db->where('posts.bath >=', $data['bath_min']);
            }

            if(isset($data['bath_max']) && trim($data['bath_max'])!='') {
                $this->db->where('posts.bath <=', $data['bath_max']);
            }

//            if(isset($data['year_min']) && trim($data['year_min'])!='') {
//                $this->db->where('year_built >=', $data['year_min']);
//            }
//            if(isset($data['year_max']) && trim($data['year_max'])!='') {
//                $this->db->where('year_built <=', $data['year_max']);
//            }
            
            
            if(isset($data['hdn_agent']) && trim($data['hdn_agent'])!='') {
                    $this->db->where('posts.created_by', $data['hdn_agent']);
            }


            if(isset($data['type'])) {
                $type = $data['type'];
                if(is_array($type)) {
                    $this->db->where_in('posts.type', array_filter($type));
                }
                else if(trim($type)!='' && trim($type)!='DBC_TYPE_ALL'){
                    $this->db->where('posts.type', $type);
                }
            }


//            if(isset($data['condition'])) {
//                $condition = $data['condition'];
//                if(is_array($condition)) {
//                    $this->db->where_in('estate_condition', array_filter($condition));
//                }
//                else if(trim($condition)!=''){
//                    $this->db->where('estate_condition', $condition);
//                }
//            }
        



        if($is_radius_set && isset($data['ignor_location'])==false) {
            $string_for_lat_long = rtrim($string_for_lat_long, "+");
            $lat_long = $this->get_latitude_longitude($string_for_lat_long);
            if($lat_long != null) {
                $radius_in_kms = $data['radius'] * 1.60934;
                $radius_condition = "(6371.0 * 2 * ASIN(SQRT(POWER(SIN((".$lat_long['lat']." - posts.latitude) * PI() / 180 / 2), 2) + COS(".$lat_long['lat']." * PI() / 180)
                                    * COS(posts.latitude * PI() / 180) * POWER(SIN((".$lat_long['lng']." - posts.longitude) * PI() / 180 / 2), 2))) <= ".$radius_in_kms.")";

                $this->db->where($radius_condition);
            }
        }


        if($this->session->userdata('view_orderby')!='') {
            $order_by   = ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';
            $order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';
            $this->db->order_by('posts.'.$order_by,$order_type);
        }

        $this->db->where('posts.status','1');
        // don't get the admin properties
        $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
        // don't get the expired users
        $this->db->where(array('user_meta.key'=>'expirtion_date','user_meta.value >' => date('Y-m-d',now())));       
        
        if($type_page == 'featured'){
            $this->db->where('posts.featured',1);
        }
         
        //firas custom here : get all posts in case result search and the view always is map view
        $this->db->select('posts.*');
        $this->db->select('users.id as `uid`, users.user_type');
        $this->db->select('user_meta.value');	
        $this->db->from('posts');
        $this->db->join('users', 'users.id = posts.created_by');
        $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
        $query = $this->db->get('');

    	
        //print $this->db->last_query();exit;
        return $query->num_rows();

    }
    
    
    function getAgentsbyIDs($agentsIDs){
        $strAgentsIDs = implode(",", $agentsIDs);
        $this->db->where_in('id',$agentsIDs); 
        $query = $this->db->get('users');
        return $query;
    }
    
    function get_properties_by_range($start,$limit='',$sort_by='',$sort='desc'){

		if($this->session->userdata('view_orderby')!='')
		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';
			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';
			$this->db->order_by("posts.".$order_by,$order_type);
		}
		else
		$this->db->order_by("posts.".$sort_by,$sort);	

                 $this->db->where('posts.status','1');
		// don't get the admin properties
                $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
                // don't get the expired users
                $this->db->where(array('user_meta.key'=>'expirtion_date','user_meta.value >' => date('Y-m-d',now())));       
                
                
		if($start==='all')

		{
                        $this->db->select('posts.*');
                        $this->db->select('users.id as `uid`, users.user_type');
                        $this->db->select('user_meta.value');	
                        $this->db->from('posts');
                        $this->db->join('users', 'users.id = posts.created_by');
                        $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                        $query = $this->db->get('');
                }
		else
		{
                        $this->db->select('posts.*');
                        $this->db->select('users.id as `uid`, users.user_type');
                        $this->db->select('user_meta.value');
                        $this->db->from('posts');
                        $this->db->join('users', 'users.id = posts.created_by');
                        $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                        $query = $this->db->get('',$limit,$start);

		}

                //print $this->db->last_query();
                
		return $query;

	}
        
        
        
        function get_featured_properties_by_range($start,$limit='',$sort_by='',$sort='desc')

	{

		if($this->session->userdata('view_orderby')!='')

		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by("posts.".$order_by,$order_type);

		}
		else
		{
			$this->db->order_by("posts.".$sort_by,$sort);
		}


		$this->db->where('posts.featured',1);
                $this->db->where('posts.status',1);
                $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
                // don't get the expired users
                $this->db->where(array('user_meta.key'=>'expirtion_date','user_meta.value >' => date('Y-m-d',now())));
                
                
		if($start==='all')
		{

			$this->db->select('posts.*');
                        $this->db->select('users.id as `uid`, users.user_type');
                        $this->db->select('user_meta.value');	
                        $this->db->from('posts');
                        $this->db->join('users', 'users.id = posts.created_by');
                        $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                        $query = $this->db->get('');
		}
		else
		{
			$this->db->select('posts.*');
                        $this->db->select('users.id as `uid`, users.user_type');
                        $this->db->select('user_meta.value');	
                        $this->db->from('posts');
                        $this->db->join('users', 'users.id = posts.created_by');
                        $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                        $query = $this->db->get('',$limit,$start);
		}

                //print $this->db->last_query();exit;
		return $query;

	}

	

	function count_featured_properties()

	{

		$this->db->where('posts.featured',1);
		$this->db->where('posts.status',1);
                $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
                // don't get the expired users
                $this->db->where(array('user_meta.key'=>'expirtion_date','user_meta.value >' => date('Y-m-d',now())));
                
               
		$this->db->select('posts.*');
                $this->db->select('users.id as `uid`, users.user_type');
                $this->db->select('user_meta.value');
                $this->db->from('posts');
                $this->db->join('users', 'users.id = posts.created_by');
                $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                 
                $query = $this->db->get('');
		return $query->num_rows();

	}
        
        
        function get_all_estates_agent($user_id,$start,$limit,$order_by='id',$order_type='asc')
	{

		if($this->session->userdata('view_orderby')!='')
		{
                    $order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';
                    $order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';
                    $this->db->order_by($order_by,$order_type);
		}
		else
                    $this->db->order_by($order_by,$order_type);

                
                $this->db->where('posts.created_by',$user_id);
		$this->db->where('posts.status',1);
                $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
                // don't get the expired users
                $this->db->where(array('user_meta.key'=>'expirtion_date','user_meta.value >' => date('Y-m-d',now())));
                
                if($start==='all'){
                    $this->db->select('posts.*');
                    $this->db->select('users.id as `uid`, users.user_type');
                    $this->db->select('user_meta.value');
                    $this->db->from('posts');
                    $this->db->join('users', 'users.id = posts.created_by');
                    $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                    $query = $this->db->get('');
                }
                else{
                    $this->db->select('posts.*');
                    $this->db->select('users.id as `uid`, users.user_type');
                    $this->db->select('user_meta.value');
                    $this->db->from('posts');
                    $this->db->join('users', 'users.id = posts.created_by');
                    $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                    $query = $this->db->get('',$limit,$start);
                }
               
                
                //print $this->db->last_query();
		return $query;

	}
        
        
        function count_all_estates_agent($user_id)

	{

		$this->db->where('posts.created_by',$user_id);
		$this->db->where('posts.status',1);
                $this->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
                // don't get the expired users
                $this->db->where(array('user_meta.key'=>'expirtion_date','user_meta.value >' => date('Y-m-d',now())));
                
                $this->db->select('posts.*');
                $this->db->select('users.id as `uid`, users.user_type');
                $this->db->select('user_meta.value');
                $this->db->from('posts');
                $this->db->join('users', 'users.id = posts.created_by');
                $this->db->join('user_meta', 'user_meta.user_id = posts.created_by');
                 
                $query = $this->db->get('');
		return $query->num_rows();

	}
        
        
}
/* End of file install.php */
/* Location: ./application/modules/show/models/show_model.php */