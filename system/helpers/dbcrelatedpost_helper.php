<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_related_post'))
{
	function get_related_post($param=array())
	{
		$name = $param[0];
		echo 'Hello '.$name.'!!!';
	}
}

/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */