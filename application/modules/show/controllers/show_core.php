<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Memento admin Controller
 *
 * This class handles user account related functionality
 *
 * @package		Show
 * @subpackage	ShowCore
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */



class Show_core extends CI_controller {



	var $PER_PAGE = 2;

	var $active_theme = '';

	public function __construct()

	{

		parent::__construct();

		is_installed(); #defined in auth helper		
		
		remove_featured_if_expired();

		$this->PER_PAGE = get_per_page_value();#defined in auth helper
		

		$this->active_theme = get_active_theme();

		$this->load->model('show_model');

                $this->load->model('user/user_model');

		$this->load->library('encrypt');
		$this->load->helper('text');


		if(isset($_POST['view_orderby']))

		{

			$this->session->set_userdata('view_orderby',$this->input->post('view_orderby'));

		}



		if(isset($_POST['view_ordertype']))

		{

			$this->session->set_userdata('view_ordertype',$this->input->post('view_ordertype'));

		}

        $system_currency_type = get_settings('realestate_settings','system_currency_type',0);

        if($system_currency_type == 0){

            $system_currency = get_currency_icon(get_settings('realestate_settings','system_currency','USD'));

        }

        else{

            $system_currency = get_settings('realestate_settings','system_currency','USD');

        }


        $this->session->set_userdata('system_currency',$system_currency);

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');

	}



	public function index()

	{

		$this->posts();	

	}

	

	public function post($type='all',$start=0)

	{			
		$this->config->load('realcon');
		$options 				= $this->config->item('blog_post_types');
		$value['posts']			= $this->show_model->get_all_active_blog_posts_by_range($start,$this->PER_PAGE,'id','desc',$type);
		$total 					= $this->show_model->count_all_active_blog_posts($type);
		$value['pages']			= configPagination('show/post/'.$type,$total,5,$this->PER_PAGE);
		$value['page_title']	= (isset($options[$type]))?$options[$type]:$type;
		$data['sub_title']		= (isset($options[$type]))?$options[$type]:$type;
		$data['content'] 		= load_view('posts_view',$value,TRUE);
		load_template($data,$this->active_theme);

	}

	public function postdetail($id='')

	{			
		$this->load->model('admin/blog_model');
		$value['blogpost']			= $this->blog_model->get_post_by_id($id);
        $data['blog_meta']=$value['blogpost'];
		$data['sub_title']			= $value['blogpost']->title;
		$data['content'] 		= load_view('post_detail_view',$value,TRUE);
		load_template($data,$this->active_theme);

	}


	public function posts($start=0)

	{			
		$value['recents']		=  $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id','desc');

		$value['featured']		=  $this->show_model->get_featured_properties_by_range($start,$this->PER_PAGE,'id','desc');

                $value['view_style'] = 'grid';

		$data['content'] 	= load_view('home_view',$value,TRUE);

		$data['alias']	    = 'dbc_home';

		load_template($data,$this->active_theme);

	}

	public function bannerslider($start=0)

	{			
		$value['recents']		=  $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id','desc');

		$value['featured']		=  $this->show_model->get_featured_properties_by_range($start,$this->PER_PAGE,'id','desc');

                $value['view_style'] = 'grid';

		$data['content'] 	= load_view('home_view',$value,TRUE);

		$data['alias']	    = 'bannerslider';

		load_template($data,$this->active_theme);

	}

	public function bannermap($start=0)

	{			
		$value['recents']		=  $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id','desc');

		$value['featured']		=  $this->show_model->get_featured_properties_by_range($start,$this->PER_PAGE,'id','desc');

        $value['view_style'] = 'grid';

		$data['content'] 	= load_view('home_view',$value,TRUE);

		$data['alias']	    = 'bannermap';

		load_template($data,$this->active_theme);

	}



	public function properties($type='recent',$start=0)

	{			

               
		$value['page_title']	= 'All '.ucfirst($type);

		if($type=='recent')

		{

			$value['query']			= $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id');

	        $total 					= $this->show_model->count_properties();			

		}

		elseif($type=='top')

		{

			$value['query']			= $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'total_view','desc');

	        $total 					= $this->show_model->count_properties();			

		}

		elseif($type=='featured')

		{

			$value['query']			= $this->show_model->get_featured_properties_by_range($start,$this->PER_PAGE,'id');

	        $total 					= $this->show_model->count_featured_properties();			

		}

		

		$value['pages']			= configPagination('show/properties/'.$type,$total,5,$this->PER_PAGE);



                $value['view_style'] 	= 'grid';
                
                
		$data['content'] 		= load_view('general_view',$value,TRUE);

		$data['alias']	    	= 'type';

		load_template($data,$this->active_theme);

	}



	public function type($estate_type='apartment',$start=0)
	{		
        if($estate_type == 'house'){
            $type = 'DBC_TYPE_HOUSE';
        }
        else if($estate_type == 'land'){
            $type = 'DBC_TYPE_LAND';
        }
        else if($estate_type == 'com_space'){
            $type = 'DBC_TYPE_COMSPACE';
        }
        else if($estate_type == 'condo'){
            $type = 'DBC_TYPE_CONDO';
        }
        else if($estate_type == 'villa'){
            $type = 'DBC_TYPE_VILLA';
        }
        else if($estate_type == 'apartment') {
            $type = 'DBC_TYPE_APARTMENT';
        }
        else {
        	$type = urldecode($estate_type);
        }


		$value['page_title']	= 'All '.lang_key($type);

		$value['query']			= $this->show_model->get_properties_by_type_range($type,$start,$this->PER_PAGE,'id');

        $total 					= $this->show_model->count_properties_by_type($type);

		$value['pages']			= configPagination('show/type/'.$estate_type,$total,5,$this->PER_PAGE);



        $value['view_style'] 	= 'grid';

		$data['content'] 		= load_view('general_view',$value,TRUE);

		$data['alias']	    	= 'type';

		load_template($data,$this->active_theme);

	}



	public function purpose($estate_purpose='sale',$start=0)

	{		
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

		$value['query']			= $this->show_model->get_properties_by_purpose_range($purpose,$start,$this->PER_PAGE,'id');

                $total                          = $this->show_model->count_properties_by_purpose($purpose);

		$value['pages']			= configPagination('show/purpose/'.$estate_purpose,$total,5,$this->PER_PAGE);



                $value['view_style'] 	= 'grid';

		$data['content'] 		= load_view('general_view',$value,TRUE);

		$data['alias']	    	= 'purpose';

		load_template($data,$this->active_theme);

	}

	#get all estate information by term

	public function all($term ='recent', $start = '') {



		if($term=='recent') {

			

			$query = $this->show_model->get_recent_estates($start);

			echo '<pre/>';

			print_r($query->result());

			if($query->num_rows()>0) {

				

				foreach($query->result() as $row) {

					print_r($row);

				}



			}



			else {

				echo lang_key('no_estates_found');

			}

 			

		}



		else if($term=='featured') {

			

			$query = $this->show_model->get_featured_estates($start);

			echo '<pre/>';

			

			if($query->num_rows()>0) {

				

				foreach($query->result() as $row) {

					print_r($row);

				}



			}



			else {

				echo lang_key('no_estates_found');;

			}

		}



		else {

			//undefined term

		}

	}



	public function all_by_agent($agent_id='', $start='') {



		if(!isset($agent_id) || $agent_id=='') {

			

    		return ;

		}



		$query = $this->show_model->get_estates_by_agent($agent_id,$start);



		echo '<pre/>';

			

		if($query->num_rows()>0) {

			

			foreach($query->result() as $row) {

				print_r($row);

			}



		}



		else {

			echo lang_key('no_estates_found');;

		}

	}



	public function detail($unique_id='')

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
		$value['post']		= $this->show_model->get_post_by_unique_id($unique_id);
		
		if($value['post']->num_rows()>0)
			$value['distance_info'] = $this->get_existing_distance_info($value['post']->row()->id);
		else
			$value['distance_info'] = array();

		$data['content'] 	= load_view('detail_view',$value,TRUE);

		$data['alias']	    = 'detail';

		$id = 0;
		if($value['post']->num_rows()>0)
		{
			$row = $value['post']->row();
			$id = $row->id;
			$seo['key_words'] = get_post_meta($row->id,'tags');

		}
		$curr_lang  	= ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
		$title 			= get_title_for_edit_by_id_lang($id,$curr_lang);
		$description 	= get_description_for_edit_by_id_lang($id,$curr_lang);
		$data['sub_title']			= $title;
		$description 	= strip_tags($description);
		$description 	= str_replace("'","",$description);
		$description 	= str_replace('"',"",$description);
		$seo['meta_description']	= $description;
		$data['seo']				= $seo;
		load_template($data,$this->active_theme);

	}

	public function printview($unique_id='')

	{	

		$value['post']		= $this->show_model->get_post_by_unique_id($unique_id);

		$data['content'] 	= load_view('print_view',$value,TRUE);

		echo $data['content'];

	}


	public function embed($unique_id='')

	{	

		$value['post']		= $this->show_model->get_post_by_unique_id($unique_id);

		load_view('embed_view',$value);

	}


	public function contact()

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


		$data['content'] 	= load_view('contact_view',$value,TRUE);

		$data['alias']	    = 'contact';

		load_template($data,$this->active_theme);

	}
        
        
        
        public function about()

	{


		$data['content'] 	= load_view('about_view',$value,TRUE);
		$data['alias']	    = 'about';
		load_template($data,$this->active_theme);

	}



	public function search($start=0)

	{

		$value['data']		= array();

		$value['query']			= $this->show_model->get_properties_by_range($start,$this->PER_PAGE,'id');

	    $total 					= $this->show_model->count_properties();	

	    $value['pages']			= configPagination('advancesearch/',$total,3,$this->PER_PAGE);

		$data['content'] 	= load_view('adsearch_view',$value,TRUE);

		$data['alias']	    = 'search';

		load_template($data,$this->active_theme);		

	}



	public function instant_search_ajax()

	{

	

		$this->load->helper('html');

		$this->load->helper('url');



		$response = '';



		$search_string =  $this->input->post('query');

		if(strlen($search_string)>3) {



			$search_result = $this->show_model->get_plain_search_result($search_string);

			if($search_result->num_rows()>0) {

				foreach ($search_result->result() as $row) {

					

					$anchor_text = substr($row->address, 0, 100);

					$response .= anchor("#", $anchor_text, "class = form-control");

				}

				echo $response;

			}



			else

				echo 'hide';

		}

		

		else 

			echo 'hide';



	}



    public function list_view()

    {

        $value['view_style'] = 'list';

        $data['content'] 	= load_view('home_view',$value,TRUE);

        $data['alias']	    = 'home';

        load_template($data,$this->active_theme);

    }



    public function toggle($type='grid',$url='')

    {

    	$this->session->set_userdata('view_style',$type);

    	$url = base64_decode($url);

    	redirect($url);

    }



    public function terms()

    {

        $data['content'] 	= load_view('termscondition_view','',TRUE);

        $data['alias']	    = 'terms';

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
    	redirect(site_url('results/'.$string));

    }


    public function tag($string='',$start='0')

    {

    	$data = array();

    	$data['plainkey'] = $string;

    	$value 	= array();

    	$value['data'] = $data;



    	#get estates based on the advanced search criteria

    	

    	$value['query'] = $this->show_model->get_advanced_search_result($data,$start,$this->PER_PAGE);

		$total 					= $this->show_model->count_search_result($data);

        $value['pages']			= configPagination('tags/'.$string,$total,4,$this->PER_PAGE);



    	$data 	= array();

    	$data['content'] 	= load_view('adsearch_view',$value,TRUE);

		$data['alias']	    = 'contact';

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


        $start = 'all';
    	$value 	= array();
    	$value['data'] = $data;
          
    	#get estates based on the advanced search criteria
    	$value['query'] 		= $this->show_model->get_advanced_search_result($data,$start,$this->PER_PAGE);
        $total 				= $this->show_model->count_search_result($data);
       
        $value['pages']			= configPagination('results/'.$string,$total,4,$this->PER_PAGE);
    	$data 	= array();

    	$data['content'] 	= load_view('adsearch_view',$value,TRUE);
        $data['alias']	    = 'contact';
       
        load_template($data,$this->active_theme);

    }



    public function get_states_ajax($term='')

	{

		$this->load->model('admin/realestate_model');

		if($term == '')

			$term = $this->input->post('term');

		$country = $this->input->post('country');

		$data = $this->realestate_model->get_locations_json($term,'state',$country);	

		echo json_encode($data);

	}


	public function get_locations_ajax($term='')

	{

		$this->load->model('admin/realestate_model');

		if($term=='')
            $term = $this->input->post('term');


		$data = $this->realestate_model->get_all_locations_json($term);	

		echo json_encode($data);

	}



	public function get_cities_ajax($term='')

	{

		$this->load->model('admin/realestate_model');

		if($term=='')

			$term = $this->input->post('term');

		$state = $this->input->post('state');

		$data = $this->realestate_model->get_locations_json($term,'city',$state);	

		echo json_encode($data);

	}



	public function agent($start='0')

	{

            $value['query']			= $this->show_model->get_users_by_range($start,$this->PER_PAGE,'id');

            $total 				= $this->show_model->count_users();
           
            $value['pages']			= configPagination('show/agent/',$total,4,$this->PER_PAGE);

            $data['content']                    = load_view('agent_view',$value,TRUE);

            $data['alias']                      = 'agent';

            load_template($data,$this->active_theme);	

	}



	public function agentproperties($user_id='0',$start=0)

	{	

		$value['user']			= $this->show_model->get_user_by_userid($user_id);	

		$value['page_title']	= lang_key('agent_estates');

		$value['query']			= $this->show_model->get_all_estates_agent($user_id,$start,$this->PER_PAGE,'id');

                $total 					= $this->show_model->count_all_estates_agent($user_id);

		$value['pages']			= configPagination('show/agentproperties/'.$user_id,$total,5,$this->PER_PAGE);



                 $value['view_style'] 	= 'grid';
                 $value['alias']	    	= 'agent';
                 
		$data['content'] 		= load_view('agent_properties_view',$value,TRUE);

		$data['alias']	    	= 'agent';

		load_template($data,$this->active_theme);

	}



	public function page($alias='')

	{	

		$value['alias']   = $alias;

		$value['query']  = $this->show_model->get_page_by_alias($alias);

		$data['content'] = load_view('page_view',$value,TRUE,$this->active_theme);

		$data['alias']   = $alias;

		load_template($data,$this->active_theme);

	}

	function check_code($str)
	{
		if ($str != $this->session->userdata('security_ans'))
		{
			$this->form_validation->set_message('check_code', 'The %s is not correct');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function sendemailtoagent($agent_id='0')

	{

                
		$this->form_validation->set_rules('sender_name', lang_key('Name'), 'required');

		$this->form_validation->set_rules('sender_email', lang_key('Email'), 'required|valid_email');

		$this->form_validation->set_rules('subject', lang_key('Subject'), 'required');

		$this->form_validation->set_rules('msg', lang_key('Message'), 'required');
		
		$this->form_validation->set_rules('ans', lang_key('Code'), 'required|callback_check_code');

		$unique_id 	= $this->input->post('unique_id');		

		$title 		= $this->input->post('title');		

		if ($this->form_validation->run() == FALSE)

		{

			$this->detail($unique_id,$title);	

		}

		else

		{

			$data['sender_email'] = $this->input->post('sender_email');
			$data['sender_name']  = $this->input->post('sender_name');
			$data['subject']  	  = $this->input->post('subject');
			$data['msg']  		  = $this->input->post('msg');
			
			$data['msg']		 .= "<br /><br /> This email was sent from the following page:<br /><a href=\"".site_url('property/'.$unique_id.'/'.$title)."\" target=\"_blank\">".site_url('property/'.$unique_id.'/'.$title)."</a>";

			add_user_meta($agent_id,'query_email#'.time(),json_encode($data));

			$this->load->library('email');

			$config['mailtype'] = "html";

			$config['charset'] 	= "utf-8";

			$config['newline'] = '\r\n';

			$this->email->initialize($config);



			$this->email->from($this->input->post('sender_email'),$this->input->post('sender_name'));

			$this->email->to(get_user_email_by_id($agent_id));



			$detail_link = site_url('show/detail/'.$unique_id);
			$msg = $this->input->post('msg');
			$msg .= "<br/><br/>Email sent from : ".'<a href="'.$detail_link.'">'.$detail_link.'</a>';

			$this->email->subject($this->input->post('subject'));

			$this->email->message($msg);


			$this->email->send();



			$this->session->set_flashdata('msg', '<div class="alert alert-success">Email sent</div>');

			redirect(site_url('property/'.$unique_id.'/'.$title));			

		}

	}



	public function sendcontactemail()

	{

		$this->form_validation->set_rules('sender_name', lang_key('Name'), 'required');

		$this->form_validation->set_rules('sender_email', lang_key('Email'), 'required|valid_email');

		$this->form_validation->set_rules('msg', lang_key('Message'), 'required');

		$this->form_validation->set_rules('ans', lang_key('Code'), 'required|callback_check_code');


		if ($this->form_validation->run() == FALSE)

		{

			$this->contact();	

		}

		else

		{



			$this->load->library('email');

			$config['mailtype'] = "html";

			$config['charset'] 	= "utf-8";

			$this->email->initialize($config);



			$this->email->from($this->input->post('sender_email'),$this->input->post('sender_name'));

			$this->email->to(get_settings('webadmin_email','contact_email','support@example.com'));



			$this->email->subject(lang_key('contact_subject'));

			$this->email->message($this->input->post('msg'));



			$this->email->send();



			$this->session->set_flashdata('msg', '<div class="alert alert-success">Email sent</div>');

			redirect(site_url('show/contact/'));			

		}

	}

	public function get_existing_distance_info($post_id) {

		$this->load->model('admin/distance_info_model');
		$info = $this->distance_info_model->get_existing_distance_info_of_a_post($post_id);
		
		if($info=='error')
			return array();
		
		return $info;
	}

	public function rss()
	{
		$this->load->helper('xml');
		$curr_lang 	= $this->uri->segment(1);
		if($curr_lang=='')
		$curr_lang = default_lang();

		$value = array();	
		$value['curr_lang'] = $curr_lang;	
		$value['feed_name'] = translate(get_settings('site_settings','site_title','Realcon'));
        $value['encoding'] = 'utf-8';
        $value['feed_url'] = site_url('show/rss');
        $value['page_description'] = lang_key('your web description');
        $value['page_language'] = $curr_lang.'-'.$curr_lang;
        $value['creator_email'] = get_settings('webadmin_email','contact_email','');
        $value['posts']	=  $this->show_model->get_properties_by_range(0,$this->PER_PAGE,'id','desc');
       # header("Content-Type: application/rss+xml");
		load_view('rss_view',$value,FALSE,$this->active_theme);
	}

    public function sitemap(){
        $this->load->helper('xml');
        $this->load->helper('file');
        $xml = read_file('./sitemap.xml');

        $value['title']='site map';

        $value['links'] = simplexml_load_string($xml);

        $data['content'] = load_view('sitemap_view',$value,TRUE,$this->active_theme);

        $data['alias']   = 'sitemap';

        load_template($data,$this->active_theme);
    }



}





/* End of file install.php */

/* Location: ./application/modules/show/controllers/show_core.php */