<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Memento admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Show
 * @subpackage	ShowModelCore
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */



class Show_model_core extends CI_Model 

{



	function __construct()

	{

		parent::__construct();

		$this->load->database();

	}



    function get_all_active_blog_posts_by_range($start,$limit='',$sort_by='',$sort='desc',$type="all")

    {

        if($type!='all')
            $this->db->where('type',$type);

        $this->db->order_by($sort_by, $sort);

        $this->db->where('status',1); 

        if($start==='all')

        {

            $query = $this->db->get('blog');

        }

        else

        {

            $query = $this->db->get('blog',$limit,$start);

        }

        return $query;

    }

    

    function count_all_active_blog_posts($type="all")

    {

        if($type!='all')
            $this->db->where('type',$type);

        $this->db->where('status',1);

        $query = $this->db->get('blog');

        return $query->num_rows();

    }


	function get_all_active_posts_by_range($start,$limit='',$sort_by='',$sort='desc')

	{

		$this->db->order_by($sort_by, $sort);

		$this->db->where('status',1); 

		if($start==='all')

		{

			$query = $this->db->get('posts');

		}

		else

		{

			$query = $this->db->get('posts',$limit,$start);

		}

		return $query;

	}

	

	function count_all_active_posts()

	{

		$this->db->where('status',1);

		$query = $this->db->get('posts');

		return $query->num_rows();

	}



	#get all recent estates information

	#set a big number as the limit value to get all the records from start to the end

    function get_recent_estates($start,$limit='10',$order_by='id',$order_type='desc') {

    	

    	$this->db->order_by($order_by,$order_type);

		if($this->session->userdata('view_orderby')!='')

		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by($order_by,$order_type);

		}
		else
			$this->db->order_by($order_by,$order_type);



    	if($start==='all')

		{

			$query = $this->db->get_where('posts',array('status'=>1));

		}

		else

		{

			$query = $this->db->get_where('posts',array('status'=>1),$limit,$start);

		}

		

		return $query;

    }





    #get all featured estates information

	#set a big number as the limit value to get all the records from start to the end

    function get_featured_estates($start,$limit='10',$order_by='id',$order_type='desc') {

    	


		if($this->session->userdata('view_orderby')!='')

		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by($order_by,$order_type);

		}
		else
			$this->db->order_by($order_by,$order_type);

    	$this->db->where('featured',1);



    	if($start==='all')

		{

			$query = $this->db->get_where('posts');

		}

		else

		{

			$query = $this->db->get_where('posts',array('status'=>1),$limit,$start);

		}



		return $query;

    }



    function count_all_featured_estates() {

    	$this->db->where('featured',1);

    	$query = $this->db->get_where('posts',array('status'=>1));

    	return $query->num_rows();

    }



    function count_all_estates_by_agent($agent_id){

    	$this->db->where('created_by',$agent_id);

    	$query = $this->db->get_where('posts',array('status'=>1));

    	return $query->num_rows();	

    }



    function get_estates_by_agent($agent_id, $start='all', $limit='10') {

    	

    	$this->db->order_by('id','desc');

    	$this->db->where('created_by',$agent_id);



    	if($start==='all')

		{

			$query = $this->db->get_where('posts',array('status'=>1));

		}

		else

		{

			$query = $this->db->get_where('posts',array('status'=>1),$limit,$start);

		}

		return $query;

    }



    function get_plain_search_result($search_string) {

    	

    	#format the search string for fulltext search

    	$search_string = trim($search_string);

    	$search_string = explode(" ", $search_string);



		$sql = "SELECT * FROM ".$this->db->dbprefix('posts')." WHERE ";

		$flag = 0;

		foreach ($search_string as $key) {

			if($flag==0) {

				$flag = 1;

			}

			else {

				$sql .= "OR ";

			}

			$sql .= "search_meta LIKE '%".$key."%' ";



		}



		$sql .= "ORDER BY ";

		

		$flag = 0;

		foreach ($search_string as $key) {

			if($flag==0) {

				$flag = 1;

				$sql .= "case when search_meta LIKE '%".$key."%' ". "then 1 else 0 end ";

			}

			else {

				

				$sql .= "+ case when search_meta LIKE '%".$key."%' ". "then 1 else 0 end ";

			}



		}



		$sql .= "DESC LIMIT 0,8";		



	    $query = $this->db->query($sql);



	    return $query;

    }



    function get_latitude_longitude($address) {
	

		$address = trim($address);
		$address = str_replace(" ", "+", $address);

    	$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false";
 
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $details_url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = json_decode(curl_exec($ch), true);

		curl_close($ch);

		// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST

		if ($response['status'] != 'OK') {

			return null;

		}



		//print_r($response);

		//print_r($response['results'][0]['geometry']['location']);



		$latLng = $response['results'][0]['geometry']['location'];


		return $latLng;

    }



    function get_advanced_search_result($data,$start = '0',$limit = '10') {

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

			$sql .= "search_meta LIKE '%".$key."%' ";



			}



			$this->db->where($sql);

    	}



    	$string_for_lat_long = "";



    	if(isset($data['country']) && trim($data['country'])!='' && isset($data['ignor_location'])==false) {


    		if($is_radius_set) 
	    		$string_for_lat_long .= $country_name."+";
	    	else 
	    		$this->db->where('country', $data['country']);

    	}



    	if(isset($data['state']) && trim($data['state'])!='' && isset($data['ignor_location'])==false) {

    		if($is_radius_set)
	    		$string_for_lat_long .= $data['state']."+";
	    	else
	    		$this->db->where_in('state', $state_id);

    	}



    	if(isset($data['city']) && trim($data['city'])!='' && isset($data['ignor_location'])==false) {

    		if($is_radius_set)
	    		$string_for_lat_long .= $data['city']."+";
	    	else
	    		$this->db->where_in('city', $city_id);

    	}


        if(isset($data['ignor_adv'])==false)
        {

    		if(isset($data['purpose_sale']) && trim($data['purpose_sale'])!='') {

    			$this->db->where('purpose', $data['purpose_sale']);

        	}



        	if(isset($data['price_min']) && trim($data['price_min'])!='') {

        		$this->db->where('total_price >=', $data['price_min']);

        	}



        	if(isset($data['price_max']) && trim($data['price_max'])!='') {

        		$this->db->where('total_price <=', $data['price_max']);

        	}



    		if(isset($data['price_per_unit_min']) && trim($data['price_per_unit_min'])!='') {

    			$this->db->where('price_per_unit >=', $data['price_per_unit_min']);

        	}



        	if(isset($data['price_per_unit_max']) && trim($data['price_per_unit_max'])!='') {

        		$this->db->where('price_per_unit <=', $data['price_per_unit_max']);

        	}



        	if(isset($data['price_unit']) && trim($data['price_unit'])!='') {

        		$this->db->where('price_unit', $data['price_unit']);

        	}



        	if(isset($data['rent_price_min']) && trim($data['rent_price_min'])!='') {

        		$this->db->where('rent_price >=', $data['rent_price_min']);

        	}



        	if(isset($data['rent_price_max']) && trim($data['rent_price_max'])!='') {

        		$this->db->where('rent_price <=', $data['rent_price_max']);

        	}





        	if(isset($data['rent_price_unit']) && trim($data['rent_price_unit'])!='') {

        		$this->db->where('rent_price_unit', $data['rent_price_unit']);

        	}



        	if(isset($data['bedroom_min']) && trim($data['bedroom_min'])!='') {

        		$this->db->where('bedroom >=', $data['bedroom_min']);

        	}



        	if(isset($data['bedroom_max']) && trim($data['bedroom_max'])!='') {

        		$this->db->where('bedroom <=', $data['bedroom_max']);

        	}



        	if(isset($data['bath_min']) && trim($data['bath_min'])!='') {

        		$this->db->where('bath >=', $data['bath_min']);

        	}



        	if(isset($data['bath_max']) && trim($data['bath_max'])!='') {

        		$this->db->where('bath <=', $data['bath_max']);

        	}



        	if(isset($data['year_min']) && trim($data['year_min'])!='') {

        		$this->db->where('year_built >=', $data['year_min']);

        	}



        	if(isset($data['year_max']) && trim($data['year_max'])!='') {

        		$this->db->where('year_built <=', $data['year_max']);

        	}


        	

        	if(isset($data['type'])) {

        		$type = $data['type'];



        		if(is_array($type)) {



        			$this->db->where_in('type', array_filter($type));

        		}

        		else if(trim($type)!=''){

    				$this->db->where('type', $type);

        		}

        	}



        	if(isset($data['condition'])) {

        		$condition = $data['condition'];



        		if(is_array($condition)) {

        			$this->db->where_in('estate_condition', array_filter($condition));

        		}

        		else if(trim($condition)!=''){

    				$this->db->where('estate_condition', $condition);

        		}

        	}
        }



    	if($is_radius_set && isset($data['ignor_location'])==false) {



    		$string_for_lat_long = rtrim($string_for_lat_long, "+");



    		$lat_long = $this->get_latitude_longitude($string_for_lat_long);



    		if($lat_long != null) {

                $radius_in_kms = $data['radius'] * 1.60934;

                $radius_condition = "(6371.0 * 2 * ASIN(SQRT(POWER(SIN((".$lat_long['lat']." - latitude) * PI() / 180 / 2), 2) + COS(".$lat_long['lat']." * PI() / 180)
                                    * COS(latitude * PI() / 180) * POWER(SIN((".$lat_long['lng']." - longitude) * PI() / 180 / 2), 2))) <= ".$radius_in_kms.")";


				$this->db->where($radius_condition);



    		}


    	}


        if($this->session->userdata('view_orderby')!='') {

            $order_by   = ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

            $order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

            $this->db->order_by($order_by,$order_type);

        }

        $this->db->where('status','1');

    	$query = $this->db->get('posts',$limit,$start);

        
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

            $sql .= "search_meta LIKE '%".$key."%' ";



            }



            $this->db->where($sql);

        }



        $string_for_lat_long = "";



        if(isset($data['country']) && trim($data['country'])!='' && isset($data['ignor_location'])==false) {


            if($is_radius_set) 
                $string_for_lat_long .= $country_name."+";
            else 
                $this->db->where('country', $data['country']);

        }



        if(isset($data['state']) && trim($data['state'])!='' && isset($data['ignor_location'])==false) {

            if($is_radius_set)
                $string_for_lat_long .= $data['state']."+";
            else
                $this->db->where_in('state', $state_id);

        }



        if(isset($data['city']) && trim($data['city'])!='' && isset($data['ignor_location'])==false) {

            if($is_radius_set)
                $string_for_lat_long .= $data['city']."+";
            else
                $this->db->where_in('city', $city_id);

        }


        if(isset($data['ignor_adv'])==false)
        {

            if(isset($data['purpose_sale']) && trim($data['purpose_sale'])!='') {

                $this->db->where('purpose', $data['purpose_sale']);

            }



            if(isset($data['price_min']) && trim($data['price_min'])!='') {

                $this->db->where('total_price >=', $data['price_min']);

            }



            if(isset($data['price_max']) && trim($data['price_max'])!='') {

                $this->db->where('total_price <=', $data['price_max']);

            }



            if(isset($data['price_per_unit_min']) && trim($data['price_per_unit_min'])!='') {

                $this->db->where('price_per_unit >=', $data['price_per_unit_min']);

            }



            if(isset($data['price_per_unit_max']) && trim($data['price_per_unit_max'])!='') {

                $this->db->where('price_per_unit <=', $data['price_per_unit_max']);

            }



            if(isset($data['price_unit']) && trim($data['price_unit'])!='') {

                $this->db->where('price_unit', $data['price_unit']);

            }



            if(isset($data['rent_price_min']) && trim($data['rent_price_min'])!='') {

                $this->db->where('rent_price >=', $data['rent_price_min']);

            }



            if(isset($data['rent_price_max']) && trim($data['rent_price_max'])!='') {

                $this->db->where('rent_price <=', $data['rent_price_max']);

            }





            if(isset($data['rent_price_unit']) && trim($data['rent_price_unit'])!='') {

                $this->db->where('rent_price_unit', $data['rent_price_unit']);

            }



            if(isset($data['bedroom_min']) && trim($data['bedroom_min'])!='') {

                $this->db->where('bedroom >=', $data['bedroom_min']);

            }



            if(isset($data['bedroom_max']) && trim($data['bedroom_max'])!='') {

                $this->db->where('bedroom <=', $data['bedroom_max']);

            }



            if(isset($data['bath_min']) && trim($data['bath_min'])!='') {

                $this->db->where('bath >=', $data['bath_min']);

            }



            if(isset($data['bath_max']) && trim($data['bath_max'])!='') {

                $this->db->where('bath <=', $data['bath_max']);

            }



            if(isset($data['year_min']) && trim($data['year_min'])!='') {

                $this->db->where('year_built >=', $data['year_min']);

            }



            if(isset($data['year_max']) && trim($data['year_max'])!='') {

                $this->db->where('year_built <=', $data['year_max']);

            }


            

            if(isset($data['type'])) {

                $type = $data['type'];



                if(is_array($type)) {



                    $this->db->where_in('type', array_filter($type));

                }

                else if(trim($type)!=''){

                    $this->db->where('type', $type);

                }

            }



            if(isset($data['condition'])) {

                $condition = $data['condition'];



                if(is_array($condition)) {

                    $this->db->where_in('estate_condition', array_filter($condition));

                }

                else if(trim($condition)!=''){

                    $this->db->where('estate_condition', $condition);

                }

            }
        }



        if($is_radius_set && isset($data['ignor_location'])==false) {



            $string_for_lat_long = rtrim($string_for_lat_long, "+");



            $lat_long = $this->get_latitude_longitude($string_for_lat_long);



            if($lat_long != null) {

                $radius_in_kms = $data['radius'] * 1.60934;

                $radius_condition = "(6371.0 * 2 * ASIN(SQRT(POWER(SIN((".$lat_long['lat']." - latitude) * PI() / 180 / 2), 2) + COS(".$lat_long['lat']." * PI() / 180)
                                    * COS(latitude * PI() / 180) * POWER(SIN((".$lat_long['lng']." - longitude) * PI() / 180 / 2), 2))) <= ".$radius_in_kms.")";


                $this->db->where($radius_condition);



            }


        }


        if($this->session->userdata('view_orderby')!='') {

            $order_by   = ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

            $order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

            $this->db->order_by($order_by,$order_type);

        }

        $this->db->where('status','1');
        
        if($type_page == 'featured'){
            $this->db->where('featured',1);
        }

    	$query = $this->db->get('posts');

        //print $this->db->last_query();exit;


        return $query->num_rows();

    }



    function get_properties_by_range($start,$limit='',$sort_by='',$sort='desc')

	{

		if($this->session->userdata('view_orderby')!='')
		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by($order_by,$order_type);

		}
		else
		$this->db->order_by($sort_by,$sort);	



		$this->db->where('status',1); 
                

		if($start==='all')

		{

			$query = $this->db->get('posts');

		}

		else

		{

			$query = $this->db->get('posts',$limit,$start);

		}

                
                
		return $query;

	}

	

	function count_properties()

	{

		$this->db->where('status',1);

		$query = $this->db->get('posts');

		return $query->num_rows();

	}





	function get_featured_properties_by_range($start,$limit='',$sort_by='',$sort='desc')

	{

		if($this->session->userdata('view_orderby')!='')

		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by($order_by,$order_type);

		}
		else
		{
			$this->db->order_by($sort_by,$sort);
		}



		$this->db->where('featured',1);

		if($start==='all')

		{

			$query = $this->db->get('posts');

		}

		else

		{

			$query = $this->db->get('posts',$limit,$start);

		}

                
		return $query;

	}

	

	function count_featured_properties()

	{

		$this->db->where('featured',1);

		$this->db->where('status',1);

		$query = $this->db->get('posts');

		return $query->num_rows();

	}



	function get_properties_by_type_range($type,$start,$limit='',$sort_by='',$sort='desc')

	{

		if($this->session->userdata('view_orderby')!='')

		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by($order_by,$order_type);

		}
		else
			$this->db->order_by($sort_by,$sort);



		$this->db->where('type',$type);

		$this->db->where('status',1); 

		if($start==='all')

		{

			$query = $this->db->get('posts');

		}

		else

		{

			$query = $this->db->get('posts',$limit,$start);

		}

		return $query;

	}

	

	function count_properties_by_type($type)

	{

		$this->db->where('type',$type);

		$this->db->where('status',1);

		$query = $this->db->get('posts');

		return $query->num_rows();

	}



	function get_properties_by_purpose_range($purpose,$start,$limit='',$sort_by='',$sort='desc')

	{

		if($this->session->userdata('view_orderby')!='')

		{

			$order_by 	= ($this->session->userdata('view_orderby')!='')?$this->session->userdata('view_orderby'):'title';

			$order_type = ($this->session->userdata('view_ordertype')!='')?$this->session->userdata('view_ordertype'):'ASC';

			$this->db->order_by($order_by,$order_type);

		}
		else
			$this->db->order_by($sort_by,$sort);



		$this->db->where('purpose',$purpose);

		$this->db->where('status',1); 

		if($start==='all')

		{

			$query = $this->db->get('posts');

		}

		else

		{

			$query = $this->db->get('posts',$limit,$start);

		}

		return $query;

	}

	

	function count_properties_by_purpose($purpose)

	{

		$this->db->where('purpose',$purpose);

		$this->db->where('status',1);

		$query = $this->db->get('posts');

		return $query->num_rows();

	}



	function get_post_by_unique_id($unique_id)

	{

		$query = $this->db->get_where('posts',array('unique_id'=>$unique_id));

		return $query;

	}


    function get_post_by_id($id)

    {

        $query = $this->db->get_where('posts',array('id'=>$id));

        return $query;

    }



	function get_page_by_alias($alias)

    {

    	$query = $this->db->get_where('pages',array('alias'=>$alias));

    	return $query;

    }



    function get_alias_by_url($url)

    {

    	$query = $this->db->get_where('pages',array('content_from'=>'Url','url'=>$url));

    	if($query->num_rows()>0)

    	{

    		$row = $query->row();

    		return $row->alias;

    	}

    	else

    		return '';

    }



    function get_page_by_url($url)

    {

    	$query = $this->db->get_where('pages',array('url'=>$url));

    	return $query;

    }



    function get_user_by_userid($user_id)

    {

    	$query = $this->db->get_where('users',array('id'=>$user_id));

    	return $query;

    }



    function get_users_by_range($start,$limit='',$sort_by='',$sort='asc')

    {
        if($this->input->post('agent_key')!='')
        {
            $key = $this->input->post('agent_key');
            $this->db->like('first_name',$key);
            $this->db->or_like('last_name',$key);
            $this->db->or_like('user_email',$key);
        }

        $this->db->order_by($sort_by, $sort);

        $this->db->where('status',1);

        if($start==='all')

        {

            $query = $this->db->get('users');

        }

        else

        {

            $query = $this->db->get('users',$limit,$start);

        }

        return $query;

    }



    function count_users()

    {
        if($this->input->post('agent_key')!='')
        {
            $key = $this->input->post('agent_key');
            $this->db->like('first_name',$key);
            $this->db->or_like('last_name',$key);
            $this->db->or_like('user_email',$key);
        }

        $this->db->where('status',1);

        $query = $this->db->get('users');

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


		$this->db->where('created_by',$user_id);

		$query = $this->db->get_where('posts',array('status'=>1),$limit,$start);

		return $query;

	}



	function count_all_estates_agent($user_id)

	{

		$this->db->where('created_by',$user_id);

		$query = $this->db->get_where('posts',array('status'=>1));

		return $query->num_rows();

	}

	function get_location_id_by_name($name,$type)
	{
		$this->db->where(array('status'=>1,'type'=>$type));
		$this->db->like('name', $name); 
		$query = $this->db->get('locations');
        $ids = array();
		if($query->num_rows()>0)
		{
			// $row = $query->row();
			// return $row->id;
            foreach ($query->result() as $row) {
                $ids[] = $row->id;
            }
            return $ids;
		}
		else
		{
			return '';
		}
	}

	function get_country_name_by_id($id)
	{
		$query = $this->db->get_where('locations',array('id'=>$id));
		if($query->num_rows()<=0)
		{
			return '';
		}
		else
		{
			return $query->row()->name;
		}
	}

}



/* End of file install.php */

/* Location: ./application/modules/show/models/show_model_core.php */