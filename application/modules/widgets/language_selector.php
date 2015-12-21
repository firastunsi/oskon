<style type="text/css">
.sel_lang{margin: 0;padding: 0;}
.sel_lang li{list-style: none;float: left;margin-right: 5px;border: 1px dotted;padding: 3px 5px;}
.sel_lang > .active {border: 1px solid #a23;}
.sel_lang > .active a{color: #a23 !important;text-decoration: underline;}
</style>
<?php
$CI  		= get_instance();
$uri 		= current_url();
$curr_lang 	= ($CI->uri->segment(1)!='')?$CI->uri->segment(1):'en';

if($CI->uri->segment(1)=='')
	$uri .= '/en';

$CI->load->model('admin/system_model');
$query = $CI->system_model->get_all_langs();
echo '<ul class="sel_lang">';
$url = $uri;
foreach ($query->result() as $lang) {	
	$uri = str_replace('/'.$curr_lang,'/'.$lang->short_name,$url);
	$sel = ($curr_lang==$lang->short_name)?'active':'';
	echo '<li class="'.$sel.'"><a href="'.$uri.'">'.strtoupper($lang->short_name).'</a></li>';
}
echo '</ul>';
echo '<div style="clear:both"></div>';
?>