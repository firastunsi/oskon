<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_total_likes'))
{
	function get_total_likes($id='')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('posts',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			$total_likes = $row->like;
			if($total_likes<2)
				echo ''.$total_likes.'';
			else
			{
				echo ''.$total_likes.'';				
			}			
		}
		else
			echo '0';
	}
}

if ( ! function_exists('show_like_button'))
{
	function show_like_button($id='')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('posts',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			echo '<a href="javascript:void(0);" url="'.site_url('user/like/'.$id).'" class="like-button">Like</a>('.$row->like.')';
		}
	}
}

if ( ! function_exists('show_dislike_button'))
{
	function show_dislike_button($id='')
	{
		$CI = get_instance();
		$CI->load->database();
		$query = $CI->db->get_where('posts',array('id'=>$id));
		if($query->num_rows()>0)
		{
			$row = $query->row();
			echo '<a href="javascript:void(0);" url="'.site_url('user/dislike/'.$id).'" class="dislike-button">dislike</a>('.$row->dislike.')';
		}
	}
}

/* End of file array_helper.php */
/* Location: ./system/helpers/array_helper.php */