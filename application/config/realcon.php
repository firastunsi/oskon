<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['blog_post_types']	= array('blog'=>'Blog Posts','article'=>'Articles','news'=>'News');

$config['enable_custom_fields']	= 'Yes';
$config['custom_fields']		= array(
										array('title'=>  lang_key('Floor No'),'name'=>'floor_no','type'=>'select','value'=>array(''=>  lang_key('Select Floor'),'1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6'),'validation'=>'','show_on_detail_page'=>'yes','others'=>array()),
										array('title'=>lang_key('Furnished'),'name'=>'is_furnished','type'=>'select',
											  'value'=>array('No'=>lang_key('No'),'Yes'=>lang_key('Yes')),'validation'=>'','show_on_detail_page'=>'yes','others'=>array())
										);

$config['property_types']		= array(                                
                                                                                array('title'=>'DBC_TYPE_OFFICE','category'=>'DBC_TYPE_COMMERCIAL'),
                                                                                array('title'=>'DBC_TYPE_SHOPE','category'=>'DBC_TYPE_COMMERCIAL'),
                                                                                array('title'=>'DBC_TYPE_BUILDING','category'=>'DBC_TYPE_COMMERCIAL'),
                                                                                array('title'=>'DBC_TYPE_LEISURE','category'=>'DBC_TYPE_COMMERCIAL'),
                                                                                array('title'=>'DBC_TYPE_INDUSTRIAL','category'=>'DBC_TYPE_COMMERCIAL'),
										array('title'=>'DBC_TYPE_APARTMENT','category'=>'DBC_TYPE_RESIDENTIAL'),
                                                                                array('title'=>'DBC_TYPE_VILLA','category'=>'DBC_TYPE_RESIDENTIAL'),
										array('title'=>'DBC_TYPE_HOUSE','category'=>'DBC_TYPE_RESIDENTIAL'),
                                                                                array('title'=>'DBC_TYPE_STUDIO','category'=>'DBC_TYPE_RESIDENTIAL'),
										array('title'=>'DBC_TYPE_LAND','category'=>'DBC_TYPE_LAND')										
									);

$config['property_categories']		= array(                                
                                                                                array('title'=>'DBC_TYPE_COMMERCIAL'),
										array('title'=>'DBC_TYPE_RESIDENTIAL'),
										array('title'=>'DBC_TYPE_LAND')										
									);

$config['property_status']		= array(
										array('title'=>'DBC_CONDITION_AVAILABLE'),
										array('title'=>'DBC_CONDITION_SOLD'),
										array('title'=>'DBC_CONDITION_RENTED')
										
									);


$config['min_price_list']		= array(
                                                                                array('title'=>'0'),
                                                                                array('title'=>'500'),
										array('title'=>'2000'),
										array('title'=>'5000'),
                                                                                array('title'=>'10,000'),
                                                                                array('title'=>'20,000'),
                                                                                array('title'=>'40,000'),
										array('title'=>'50,000'),
										array('title'=>'100,000'),
										array('title'=>'150,000'),
										array('title'=>'200,000'),
                                                                                array('title'=>'300,000'),
                                                                                array('title'=>'400,000'),
                                                                                array('title'=>'500,000')
									);


$config['max_price_list']		= array(
                                                                                
                                                                                array('title'=>'2000'),
										array('title'=>'5000'),
										array('title'=>'10,000'),
                                                                                array('title'=>'20,000'),
										array('title'=>'50,000'),
										array('title'=>'75,000'),
										array('title'=>'100,000'),
                                                                                array('title'=>'200,000'),
										array('title'=>'300,000'),
										array('title'=>'400,000'),
                                                                                array('title'=>'500,000'),
                                                                                array('title'=>'600,000'),
                                                                                array('title'=>'700,000')
									);


$config['decimal_point'] = '.';
$config['thousand_separator'] = ',';

# to create a new property just add another element to the $config['property_types'] array
# be carefull that each element of the array must end with a comma (,) execpt the last one
# the last element should not have any trailing comma
# to remove any property type just delete the line of that type, again be careful about the comma