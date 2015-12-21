<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('site_url'))
{
	function site_url($uri = '',$lang='')
	{
		$CI =& get_instance();
		$lang = ($lang=='')?$CI->uri->segment(1):$lang;
		if($lang=='')
		{
			$lang=default_lang();
		}
		if($lang=='admin')
			$lang = 'en';
		if($lang=='tv')
			$lang = 'en';

		$final_url = $CI->config->site_url($lang.'/'.$uri);
		//$final_url = str_replace('http://','https://',$final_url);
		return $final_url;
	}
}

if ( ! function_exists('base_url'))
{
	function base_url($uri = '')
	{
		$CI =& get_instance();
		$base_url = $CI->config->base_url($uri);
		//$base_url = str_replace('http://','https://',$base_url);
		return $base_url;
	}
}

