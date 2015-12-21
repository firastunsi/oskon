<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Memento System_model_core model
 *
 * This class handles System_model_core management related functionality
 *
 * @package		Admin
 * @subpackage	System_model_core
 * @author		dbcinfotech
 * @link		http://dbcinfotech.net
 */

class System_model_core extends CI_Model 
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
    function create_db_backup()
    {
        # Load the DB utility class
        $this->load->dbutil();

        $prefs = array(
                'tables'      => array(),  			// Array of tables to backup.
                'ignore'      => array(),           // List of tables to omit from the backup
                'format'      => 'txt',             // gzip, zip, txt
                'filename'    => '',    			// File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'    => TRUE,              // Whether to add DROP TABLE statements to backup file
                'add_insert'  => TRUE,              // Whether to add INSERT data to backup file
                'newline'     => "\n"               // Newline character used in backup file
              );

        # Backup your entire database and assign it to a variable
        $backup =& $this->dbutil->backup($prefs);
		
        $value = getdate();
        $today = $value['year']."-".$value['mon']."-".$value['mday'];
        # Load the file helper and write the file to your server
        $this->load->helper('file');
        write_file('assets/backups/mybackup('.$today.').sql', $backup);
    }

    function restore_db_backup($file)
    {
    	$this->load->helper('file');
        $schema = read_file('./assets/backups/'.$file);

        $query = rtrim(trim($schema), "\n;");
        $query_list = explode(";", $query);

        foreach($query_list as $query)
            $this->db->query($query);
    }
    
    #************* lang editor file ******************#
	function is_lang_short_name_unique($short_name)
	{
		$query = $this->db->get_where('language',array('short_name'=>$short_name));
		return $query->num_rows();
	}

	function add_or_update_lang($data)
	{
		$this->db->reconnect();		
		$query = $this->db->get_where('language',array('unique_id'=>$data['unique_id']));
		if($query->num_rows()>0)
		{
			$this->db->update('language',$data,array('unique_id'=>$data['unique_id']));
		}
		else
		{
			$this->db->insert('language',$data);		
			return $this->db->insert_id();
		}
	}

	function addlang($data)
	{
		$this->db->insert('language',$data);		
		return $this->db->insert_id();
	}

	function update_lang_data($data,$id)
	{
		$this->db->update('language',$data,array('id'=>$id));
	}
	
	function get_lang_by_id($id)
	{
		$query = $this->db->get_where('language',array('id'=>$id));
		return $query;
	}
	
	function get_all_langs()
	{
		$query = $this->db->get_where('language',array('status'=>1));
		return $query;
	}
	
	function delete_lang_by_id($id)
	{
		$this->db->update('language',array('status'=>0),array('id'=>$id));
	}
	
	function get_all_lang()
	{
		$this->db->distinct();
		$query = $this->db->get_where('language',array('status'=>1));
		return $query;
	}
	
	#************ email functions *************#
	function get_all_emails()
	{
		$query = $this->db->get_where('emailtmpl',array('status'=>1));
		return $query;
	}
	
	function get_email_by_id($id)
	{
		$query = $this->db->get_where('emailtmpl',array('id'=>$id));
		return $query;
	}
	
	function get_email_tmpl_by_email_name($name)
	{
		$query = $this->db->get_where('emailtmpl',array('email_name'=>$name));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$values = json_decode($row->values);
			return $values;
		}
		else
		{
			$values = array('subject'=>'Subject Not found','body'=>'body not found');
		}
		return $values;
	}
	
	function update_email_tmpl($data,$id)
	{
		$this->db->update('emailtmpl',$data,array('id'=>$id));
	}

	function get_language_data($base_lang) {

		$query = $this->db->get_where('language',array('unique_id'=>$base_lang));
		
		if($query->num_rows()>0) {
			return $query->row();
		}

	}
}

/* End of file system_model_core.php */
/* Location: ./system/application/models/system_model_core.php */