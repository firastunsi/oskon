<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Show
 * @subpackage	Show
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */
require_once 'show_core.php';
class Show extends Show_core {

	public function __construct()
	{
		parent::__construct();
                $curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
                if ($curr_lang == 'ar'){
                    $this->config->set_item('language', 'arabic');
                }
                
	}
        
        public function posts($start=0){
                // firas custom here : Remove recent from home page
		//$value['recents']		=  $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id','desc');
		$value['featured']		=  $this->show_model->get_featured_properties_by_range($start,$this->PER_PAGE,'id','desc');

                $value['view_style'] = 'grid';
                
                $this->load->model('admin/users_model');
                $value['topagents'] = $this->users_model->get_all_agents($start,$this->PER_PAGE,'id','desc');
                
		$data['content'] 	= load_view('home_view',$value,TRUE);
		$data['alias']	    = 'dbc_home';
                $data['sub_title']  = lang_key('Home_Title');
		load_template($data,$this->active_theme);
	}
        
        public function purpose($estate_purpose='sale',$start=0){		
            if($estate_purpose == 'rent'){
                $purpose = 'DBC_PURPOSE_RENT';
            }
            else if($estate_purpose == 'both'){
                $purpose = 'DBC_PURPOSE_BOTH';
            }
            else{
                $purpose = 'DBC_PURPOSE_SALE';
            }

            $value['page_title']	= 'All '.lang_key($purpose);
            
            
            //firas custom here : remove the pagination incase map view
            if($this->session->userdata('view_style')=='map'){
                // fixed per_page to all
                $start = 'all';
            }


            $value['query']			= $this->show_model->get_properties_by_purpose_range($purpose,$start,$this->PER_PAGE,'id');
            $total                              = $this->show_model->count_properties_by_purpose($purpose);
            $value['pages']			= configPagination('show/purpose/'.$estate_purpose,$total,5,$this->PER_PAGE);

            $value['view_style'] 	= 'grid';
            $data['content'] 		= load_view('general_view',$value,TRUE);
            $data['alias']	    	= 'purpose';
            load_template($data,$this->active_theme);

	}
        
        public function properties($type='recent',$string='',$start=0){			

                $string = rawurldecode($string);

                $data = array();

                $values = explode("|",$string);

                foreach ($values as $value) {

                        $get 	= explode("=",$value);

                        $s 		= (isset($get[1]))?$get[1]:'';

                        $val 	= explode(",",$s);

                        if(count($val)>1)

                        {

                                $data[$get[0]] = $val;

                        }

                        else

                                $data[$get[0]] = (isset($get[1]))?$get[1]:'';

                }
                

                $value 	= array();
                $value['data'] = $data;
                
                $plainkey = '';
                if (isset($data['plainkey'])){
                    $plainkey = $data['plainkey'];
                }
                
                
                if ($this->session->userdata('view_style') == ''){
                    $this->session->set_userdata('view_style','map');
                }

                if($this->session->userdata('view_style') == 'map'){
                    $start = 'all';
                }
               
                if ($type == 'top'){
                    $str_title = 'top';
                }
                else{
                    $str_title = $type;
                }
                
                $value['page_title']	= lang_key(ucfirst($str_title.' Properties' ) );
//		if($type=='recent'){
//                    $value['query']			= $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id');
//                    $total 				= $this->show_model->count_properties();			
//
//		}
		if($type=='top'){
                    $value['query']                     = $this->show_model->get_advanced_search_result($data,$start,$this->PER_PAGE,$type);
                    $total 				= $this->show_model->count_search_result($data,$type);
		}
		elseif($type=='featured'){
                    $value['query']                     = $this->show_model->get_advanced_search_result($data,$start,$this->PER_PAGE,$type);
                    $total 				= $this->show_model->count_search_result($data,$type);
		}
                else{
                    redirect('404');
                }

              
           
                $data['plainkey'] = $plainkey;
		
                
		$value['pages']			= configPagination('show/properties/'.$type.'/'.$string,$total,6,$this->PER_PAGE);
                $value['alias']                 = 'type';
		$data['content'] 		= load_view('general_view',$value,TRUE);
		$data['alias']                  = 'type';
		load_template($data,$this->active_theme);

	}
        
        
        
        public function bannerslider($start=0)
	{
                // firas custom here : Remove recent from home page
		//$value['recents']		=  $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id','desc');
		$value['featured']		=  $this->show_model->get_featured_properties_by_range($start,$this->PER_PAGE,'id','desc');

                $value['view_style'] = 'grid';

		$data['content'] 	= load_view('home_view',$value,TRUE);

		$data['alias']	    = 'bannerslider';

		load_template($data,$this->active_theme);

	}

	public function bannermap($start=0)
	{	
                // firas custom here : Remove recent from home page
		//$value['recents']		=  $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id','desc');

		$value['featured']		=  $this->show_model->get_featured_properties_by_range($start,$this->PER_PAGE,'id','desc');
                $value['view_style'] = 'grid';
		$data['content'] 	= load_view('home_view',$value,TRUE);
		$data['alias']	    = 'bannermap';
		load_template($data,$this->active_theme);
	}
      
        
        public function about()
	{

		$a = rand (1,10);
		$b = rand (1,10);
		$c = rand (1,10)%3;
		if($c==0)
		{
			$operator = '+';
			$ans = $a+$b;
		}
		else if($c==1)
		{
			$operator = 'X';
			$ans = $a*$b;
		}
		else if($c==2)
		{
			$operator = '-';
			$ans = $a-$b;
		}

		$this->session->set_userdata('security_ans',$ans);

		$value['question']  = $a." ".$operator." ".$b." = ?";


		$data['content'] 	= load_view('about_view',$value,TRUE);

		$data['alias']	    = 'about';

		load_template($data,$this->active_theme);

	}
        
        
        public function agent($start='0')

	{

            $value['query']			= $this->show_model->get_users_by_range_notfree($start,$this->PER_PAGE,'id');

            $total 				= $this->show_model->count_users_notfree();
           
            $value['pages']			= configPagination('show/agent/',$total,4,$this->PER_PAGE);

            $data['content']                    = load_view('agent_view',$value,TRUE);

            $data['alias']                      = 'agent';

            load_template($data,$this->active_theme);	

	}
        
        
    public function result($string='',$start='0')

    {
    	$string = rawurldecode($string);
    	
    	$data = array();

    	$values = explode("|",$string);

    	foreach ($values as $value) {

    		$get 	= explode("=",$value);

    		$s 		= (isset($get[1]))?$get[1]:'';

    		$val 	= explode(",",$s);

    		if(count($val)>1)

    		{

	    		$data[$get[0]] = $val;

    		}

    		else

	    		$data[$get[0]] = (isset($get[1]))?$get[1]:'';

    	}

        
        if ($this->session->userdata('view_style') == ''){
            $this->session->set_userdata('view_style','map');
        }
       
        if($this->session->userdata('view_style') == 'map'){
            $start = 'all';
        }
        else{
             // in case grid listing we should reset agent id hdn value
             $data['hdn_agent'] = '';
        }
       
        
    	$value 	= array();
    	$value['data'] = $data;
        $plainkey = $data['plainkey'];
        $purpose_sale = $data['purpose_sale'];
    
        $value['search_empty'] = 0;
        if ($plainkey == '' && $purpose_sale == ''){
            $value['search_empty'] = 1;
        }
        
    	#get estates based on the advanced search criteria
    	$value['query'] 		= $this->show_model->get_advanced_search_result($data,$start,$this->PER_PAGE);
        $total 				= $this->show_model->count_search_result($data);
        $value['pages']			= configPagination('results/'.$string,$total,4,$this->PER_PAGE);
    	$data 	= array();
        $value['alias']	    = 'search_result';
        $value['is_agents'] = 0;
    
            
        $this->load->model('admin/users_model');
        
        if($this->session->userdata('view_style') == 'map' && $total > 0){
            $agents_properties = array();
            foreach ($value['query']->result() as $row){
                // check if agent have not a free package so he is agent
                //note already the query in the loop have just the posts for users not expired and user not admin
                $is_premuim_package_count = $this->users_model->is_premuim_package($row->created_by);
                if ($is_premuim_package_count == 0){
                    continue;
                }
                if (!in_array($row->created_by, $agents_properties)){
                    $agents_properties[] = $row->created_by;
                }
            }
            
            if (count($agents_properties) > 0){
                $agents_details = $this->show_model->getAgentsbyIDs($agents_properties);
                $value['agents_details']  = $agents_details;
                $value['is_agents'] = 1;
            }
            
        }
        
        
        //print_r($agents_details);exit;
        
    	$data['content'] 	= load_view('adsearch_view',$value,TRUE);
        $data['alias']	    = 'search_result';
        $data['plainkey'] = $plainkey;
        
        
        load_template($data,$this->active_theme);

    }
    
    
    
    public function advfilter()

    {

    	$string = '';
        
    	foreach ($_POST as $key => $value) {

    		if(is_array($value))

    		{

    			$val = '';

    			foreach ($value as $row) {

    				$val .= $row.',';

    			}

    			$string .= $key.'='.$val.'|';	

    		}

    		else

			{

	    		$string .= $key.'='.$value.'|';			

			}    			

    	}

        
    	//$this->result(base64_encode($string));
        if (isset($_POST['page_name']) && ($_POST['page_name'] == 'featured' || $_POST['page_name'] == 'tags' || $_POST['page_name'] == 'top') ){
            if($_POST['page_name'] == 'featured'){
                redirect(site_url('show/properties/featured/'.$string));
            }
            else if ($_POST['page_name'] == 'top'){
                redirect(site_url('show/properties/top/'.$string));
            }
            else if ($_POST['page_name'] == 'tags'){
                $tag = $_POST['hdn_tag'];
                redirect(site_url('tags/'.$tag.'/'.$string.'/filter'));
            }
        }
        else{
            redirect(site_url('results/'.$string));
        }
        
    	

    }
    
    
    public function agentproperties($user_id='0',$start=0)

	{	

                
                if ($this->session->userdata('view_style') == ''){
                    $this->session->set_userdata('view_style','map');
                }

                if($this->session->userdata('view_style') == 'map'){
                    $start = 'all';
                }
                
		$value['user']			= $this->show_model->get_user_by_userid($user_id);	
		$value['page_title']            = lang_key('agent_estates');
		$value['query']			= $this->show_model->get_all_estates_agent($user_id,$start,$this->PER_PAGE,'id');
                $total 				= $this->show_model->count_all_estates_agent($user_id);
		$value['pages']			= configPagination('show/agentproperties/'.$user_id,$total,5,$this->PER_PAGE);
                 
                $is_user_agent                        = isAgent($user_id);
                
                if (!$is_user_agent){
                    redirect(site_url());
                }
                
                $value['alias']	    	= 'agent';
		$data['content'] 	= load_view('agent_properties_view',$value,TRUE);
		$data['alias']	    	= 'agent';
		load_template($data,$this->active_theme);

	}
        
        
    public function tag($tag = '', $string='',$type = 'standard',$start='all'){
        
        
        $data = array();
        
        if ($tag !='' && $type == 'standard'){
            $string = $tag;
            $data['plainkey'] = $tag;
        }
        else{
            $string = rawurldecode($string);
            $values = explode("|",$string);
            foreach ($values as $value) {

                    $get 	= explode("=",$value);

                    $s 		= (isset($get[1]))?$get[1]:'';

                    $val 	= explode(",",$s);

                    if(count($val)>1)

                    {

                            $data[$get[0]] = $val;

                    }

                    else

                            $data[$get[0]] = (isset($get[1]))?$get[1]:'';

            }
        }
                
       $plainkey = '';
        if (isset($data['plainkey'])){
            $plainkey = $data['plainkey'];
        }
    	
    	$value 	= array();
    	$value['data'] = $data;


        if ($this->session->userdata('view_style') == ''){
            $this->session->set_userdata('view_style','map');
        }

        if($this->session->userdata('view_style') == 'map'){
            $start = 'all';
        }
        
        $value['page_title']	= '#'.rawurldecode($plainkey);
        
        $data['plainkey'] = $plainkey;
     
    	#get estates based on the advanced search criteria
    	$value['query'] = $this->show_model->get_advanced_search_result($data,$start,$this->PER_PAGE,'tags');
        $total 					= $this->show_model->count_search_result($data,'tags');
        //$value['pages']			= configPagination('tags/'.$plainkey.'/'.$type,$total,4,$this->PER_PAGE);
         
     
    	$data 	= array();
        $value['alias']                 = 'type';
        $data['content'] 		= load_view('general_view',$value,TRUE);
        
        load_template($data,$this->active_theme);
    }
    
    
    public function detail($unique_id=''){	

		$a = rand (1,10);
		$b = rand (1,10);
		$c = rand (1,10)%3;
		if($c==0)
		{
			$operator = '+';
			$ans = $a+$b;
		}
		else if($c==1)
		{
			$operator = 'X';
			$ans = $a*$b;
		}
		else if($c==2)
		{
			$operator = '-';
			$ans = $a-$b;
		}

		$this->session->set_userdata('security_ans',$ans);

		$value['question']  = $a." ".$operator." ".$b." = ?";
		$value['post']		= $this->show_model->get_post_by_unique_id($unique_id);
		
		if($value['post']->num_rows()>0)
			$value['distance_info'] = $this->get_existing_distance_info($value['post']->row()->id);
		else
			$value['distance_info'] = array();

		$id = 0;
		if($value['post']->num_rows()>0)
		{
			$row = $value['post']->row();
			$id = $row->id;
			$seo['key_words'] = get_post_meta($row->id,'tags');
                        $created_by = $row->created_by;
                        $value['isAgent'] = isAgent($created_by);
                }       
                
                
                $data['content'] 	= load_view('detail_view',$value,TRUE);
		$data['alias']	    = 'detail';
                
		$curr_lang  	= ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
		$title 			= get_title_for_edit_by_id_lang($id,$curr_lang);
		$description 	= get_description_for_edit_by_id_lang($id,$curr_lang);
		$data['sub_title']			= $title;
		$description 	= strip_tags($description);
		$description 	= str_replace("'","",$description);
		$description 	= str_replace('"',"",$description);
		$seo['meta_description']= $description;
		$data['seo']= $seo;
                
		load_template($data,$this->active_theme);

	}
        
}
/* End of file install.php */
/* Location: ./application/modules/show/controllers/show.php */