<!DOCTYPE html>

<html lang="en">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
        $page = get_current_page();

       

        if (!isset($sub_title))
            $sub_title = (isset($page['title'])) ? $page['title'] : lang_key ('Oskon');


        if (isset($seo) == FALSE)
            $seo = (isset($page['seo_settings']) && $page['seo_settings'] != '') ? (array) json_decode($page['seo_settings']) : array();

        if (!isset($meta_desc))
            $meta_desc = (isset($seo['meta_description'])) ? $seo['meta_description'] : get_settings('site_settings', 'meta_description', 'realcon property listing');

        if (!isset($key_words))
            $key_words = (isset($seo['key_words'])) ? $seo['key_words'] : get_settings('site_settings', 'key_words', 'real estate,property,listing, house');

        if (!isset($crawl_after))
            $crawl_after = (isset($seo['crawl_after'])) ? $seo['crawl_after'] : get_settings('site_settings', 'crawl_after', 3);
        ?>

        <title><?php echo translate(get_settings('site_settings', 'site_title', 'Realcon')); ?> | <?php echo translate($sub_title); ?></title>

        <?php
        if (isset($post)) {
            echo (isset($post)) ? social_sharing_meta_tags_for_post($post) : '';
        } elseif (isset($blog_meta)) {
            echo (isset($blog_meta)) ? social_sharing_meta_tags_for_blog($blog_meta) : '';
        }
        ?>        
        <?php
        $meta_desc = str_replace('"', '', $meta_desc);
        $meta_desc = (strlen($meta_desc) > 160) ? substr($meta_desc, 0, 160) : $meta_desc;
        ?>
        <meta name="description" content="<?php echo $meta_desc; ?>">
        <meta name="keywords" content="<?php echo $key_words; ?>" />
        <meta name="revisit-after" content="<?php echo $crawl_after; ?> days">


        <?php if (get_settings('realestate_settings', 'enable_fb_comment', 'No') == 'Yes') { ?>
            <meta property="fb:app_id" content="<?php echo get_settings('realestate_settings', 'fb_comment_app_id', 'No'); ?>"/>
        <?php } ?>

        <?php require_once'includes_top.php'; ?>

<?php
$bg_color = get_settings('banner_settings', 'menu_bg_color', 'rgba(241, 89, 42, .8)');

$text_color = get_settings('banner_settings', 'menu_text_color', '#ffffff');

$active_text_color = get_settings('banner_settings', 'active_menu_text_color', '#ffffff');
?>

        <style type="text/css">
            .top-nav li a{
                color: <?php echo $text_color ?> !important;
            }

            .top-nav .active a{
                color:<?php echo $active_text_color ?> !important;
            }
            .navbar-inverse{

                background:<?php echo $bg_color ?>;
            }

            @media (max-width: 767px) {

                .navbar-inverse {  background:<?php echo $bg_color; ?>; }

            }

            .orange{
                background-color: #f0ad4e !important;
                border-bottom: 1px solid  #f0ad4e !important;
            }
            .orange-border{
                border: 1px solid  #f0ad4e !important;
            }

            .btn-action,
            .btn-primary {  background-image: -webkit-linear-gradient(top, #FF9B22 0%, #FF8C00 100%); background-image: linear-gradient(to bottom, #FF9B22 0%, #FF8C00 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffFF9B22', endColorstr='#ffFF8C00', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled = false); background-repeat: repeat-x; border:0 none; }

        </style>
        <script type="text/javascript">var old_ie = 0;</script>
        <!--[if lte IE 8]> <script type="text/javascript"> old_ie = 1; </script><style type="text/css">.ie-message{display:block;}</style> < ![endif]-->

        <script type="text/javascript">var switchTo5x=true;</script>
        <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
        <script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>
        
        
    </head>


<?php
$CI = get_instance();
$curr_lang = $CI->uri->segment(1);
if ($curr_lang == '')
    $curr_lang = default_lang();
if ($curr_lang == 'ar') {
    ?>
        <link rel="stylesheet" href="<?php echo theme_url(); ?>/assets/css/rtl-fix.css">
        <link rel="stylesheet" href="<?php echo theme_url(); ?>/assets/css/media_ar.css">
        <body class="home" dir="rtl">
    <?php
} else {
    ?>
        <body class="home <?php if (isset($alias) && ($alias == 'search_result') && ($this->session->userdata('view_style')=='map')): ?> map_body <?php endif;?>" dir="<?php echo get_settings('site_settings', 'site_direction', 'ltr'); ?>">
        <?php
    }
    ?>


    <?php require_once'header.php'; ?>

<?php
if (isset($alias) && $alias == 'dbc_home') {
    ?>
            <script src="<?php echo theme_url(); ?>/assets/js/jquery.stellar.min.js"></script>

            <script type="text/javascript">

        $(function () {

            $.stellar({
                horizontalScrolling: false,
                verticalOffset: 40

            });

        });

            </script>
            <?php
            if (get_settings('banner_settings', 'banner_type', 'Slider') == 'Slider')
                require_once'slider_view.php';
            else
                require_once'slidermap_view.php';
        }
        else if (isset($alias) && $alias == 'bannerslider') {
            ?>
            <script src="<?php echo theme_url(); ?>/assets/js/jquery.stellar.min.js"></script>

            <script type="text/javascript">

        $(function () {

            $.stellar({
                horizontalScrolling: false,
                verticalOffset: 40

            });

        });

            </script>
            <?php
            require_once'slider_view.php';
        } else if (isset($alias) && $alias == 'bannermap') {
            ?>
            <script src="<?php echo theme_url(); ?>/assets/js/jquery.stellar.min.js"></script>

            <script type="text/javascript">

        $(function () {

            $.stellar({
                horizontalScrolling: false,
                verticalOffset: 40

            });

        });

            </script>
    <?php
    require_once'slidermap_view.php';
} else
    echo '<div class="clear-fix" style="min-height:65px;"></div>';
?>

        <?php if ($CI->uri->segment(2) == 'tags'):?>
            <?php $page_name = $CI->uri->segment(2); ?>
            <?php $tag = $CI->uri->segment(3); ?>
        <?php else:?>
            <?php $page_name = $CI->uri->segment(4); ?>
            <?php $tag = '';?>
        <?php endif;?>
        
        <?php if (isset($alias) && ($alias == 'search_result' || $alias == 'type' )): ?>
            
            <div class="OS_filter">
              <form id="filter_header_frm" action="<?php echo site_url('show/advfilter'); ?>" method="post">
                <?php $v = (isset($data['purpose_sale'])) ? $data['purpose_sale'] : '';?>
                <input class="form-control" type="hidden" value="<?php echo (isset($data['plainkey'])) ? rawurldecode($data['plainkey']) : ''; ?>" name="plainkey">
                <input type="hidden" value="<?php print $page_name; ?>" name="page_name">
                <input type="hidden" value="<?php echo $v;?>" name="purpose_sale" id="purpose_sale">
                <input type="hidden" value="<?php print $tag; ?>" name="hdn_tag">
                <input type="hidden" id="hdn_agent" name="hdn_agent" value="<?php echo (isset($data['hdn_agent'])) ? $data['hdn_agent'] : ''; ?>"/>
                <div class="OS_filter_in <?php if (isset($alias) && ($alias == 'search_result') && ($this->session->userdata('view_style')=='map')): ?> full_container <?php endif;?>">
                    <ul class="fi_ul1">
                        <li class="fi_li1 fi_li_pad" style="border-left: 0px;"><strong><?php echo lang_key('Advanced Filters');?></strong></li>
                        <li class="fi_li1">
                            <a href="" data-value="list_type" class="lnk_list"><?php echo lang_key('Listing Type');?></a>
                            <ul class="fi_ul2" id="ul_list_type" style="display: none;">
                                <li class="fi_li2 top_li">
                                    <input <?php if ($v == ''):?> checked="checked" <?php endif;?> value=""  id="purpose_sale_all"  class="css-checkbox med" type="checkbox">
                                    <label for="purpose_sale_all" class="css-label med elegant"><?php echo lang_key('all_listing'); ?></label>
                                </li>
                                <?php $options = array('DBC_PURPOSE_SALE', 'DBC_PURPOSE_RENT'); ?>
                                <?php
                                    $i = 1;
                                    foreach ($options as $value) {
                                        if ($v == ''){
                                            $sel = 'checked="checked"';
                                        }
                                        else{
                                            $sel = ($v == $value) ? 'checked="checked"' : '';
                                        }
                                        ?>
                                        <li class="fi_li2">
                                            <input id="purpose_sale_<?php echo $i;?>" value="<?php echo $value;?>" <?php echo $sel; ?>  class="css-checkbox med"  type="checkbox">
                                            <label for="purpose_sale_<?php echo $i;?>" class="css-label med elegant"><?php echo lang_key($value);?></label>
                                        </li> 
                                    <?php $i++;}
                                ?>
                            </ul>
                        </li>
                        <li class="fi_li1">
                            <a class="lnk_list" href="" data-value="property_type"><?php echo lang_key('Property Type');?></a>
                            <?php
                            $types = array();
                            $this->load->config('realcon');
                            $custom_types = $this->config->item('property_types');
                            $custom_categories = $this->config->item('property_categories');
                            if (is_array($custom_types))
                                $i=0;
                                foreach ($custom_types as $custom_type) {
                                    $types[$i]['title'] = $custom_type['title'];
                                    $types[$i]['category'] = $custom_type['category'];
                                    $i++;
                                }
                            ?>
                            <ul class="fi_ul2" id="ul_property_type" style="display: none;">
                                <?php $i=0;?>
                                <?php foreach ($custom_categories as $custom_category):?>
                                    <li class="category_type_chk fi_li2 <?php if($i==0):?>top_li<?php endif;?>">
                                        <input  value="<?php echo $custom_category['title'];?>" class="css-checkbox med" id="category_<?php echo $custom_category['title'];?>" type="checkbox">
                                        <label for="category_<?php echo $custom_category['title'];?>" class="css-label med elegant"><?php echo lang_key($custom_category['title']); ?></label>
                                    </li>

                                    <?php $v = (isset($data['type'])) ? $data['type'] : array(); ?>
                                    <?php foreach ($types as $type):?>
                                        <?php $sel = '';?>
                                        <?php $sel = (in_array($type['title'],$v))?'checked="checked"':'';?>
                                        <?php if ($type['category'] == $custom_category['title']):?>
                                            <li class="sub_type_chk_<?php echo $custom_category['title'];?> fi_li2 fi_li_lpad">
                                                <input <?php echo $sel; ?> data="<?php echo $custom_category['title'];?>" value="<?php echo $type['title'];?>" name="type[]" type="checkbox" id="type_<?php echo $type['title'];?>" class="css-checkbox"/>
                                                <label for="type_<?php echo $type['title'];?>" class="css-label lite-blue-check"><?php echo lang_key($type['title']); ?></label>
                                            </li>
                                        <?php endif;?>
                                    <?php endforeach;?>

                                    <?php $i++;?>
                                <?php endforeach;?>
                            </ul>
                        </li>
                        <li class="fi_li1">
                            <a class="lnk_list" id="budget_lnk" href="" data-value="budget"><?php echo lang_key('Budget'); ?></a>
                            <ul class="fi_ul3" id="ul_budget" style="display: none;">
                                <li class="fi_li3 fi_feld_in">
                                    <div class="dualboxes">
                                        <div class="box1 min-box">
                                            <input value="<?php echo (isset($data['price_min'])) ? $data['price_min'] : ''; ?>" class="fi_text_c" maxlength="10" size="10" name="price_min" id="price_min" type="text" placeholder="<?php echo lang_key('Min');?>">
                                        </div>
                                        <div class="dash"> - </div>
                                        <div class="box1 max-box">
                                            <input value="<?php echo (isset($data['price_max'])) ? $data['price_max'] : ''; ?>" id="price_max" class="fi_text_c" maxlength="10" size="10" name="price_max" type="text" placeholder="<?php echo lang_key('Max');?>">
                                        </div>
                                        <div class="OS_clere"></div>
                                    </div>
                                    <div class="OS_clere"></div>
                                </li>
                                
                                <?php $min_list = $this->config->item('min_price_list');?>
                                <?php $max_list = $this->config->item('max_price_list');?>
                                <?php if (is_array($min_list)):?>
                                    <span id="min_price_list">
                                    <?php foreach ($min_list as $min_price):?>
                                        <li class="fi_li3">
                                            <a href="#" data="<?php echo $min_price['title'];?>"><?php echo $CI->session->userdata('system_currency'); ?> <?php echo $min_price['title'];?> +</a>
                                        </li>
                                    <?php endforeach?>
                                    </span>
                                <?php endif;?>
                                
                                <?php if (is_array($max_list)):?>
                                    <span id="max_price_list" style="display:none;text-align: right;">
                                    <?php foreach ($max_list as $max_price):?>
                                        <li class="fi_li3">
                                            <a href="#" data="<?php echo $max_price['title'];?>"><?php echo $CI->session->userdata('system_currency'); ?> <?php echo $max_price['title'];?></a>
                                        </li>
                                    <?php endforeach?>
                                    </span>
                                <?php endif;?>
                            </ul>
                        </li>
                        <li class="fi_li1">
                            <a class="lnk_list" href="" data-value="bedrooms"><?php echo lang_key('bedrooms'); ?></a>
                            <ul class="fi_ul2" id="ul_bedrooms" style="display: none;">
                                <?php for ($i=0;$i<=7;$i++):?>
                                    <?php  $sel = (isset($data['bedroom_min']) && $data['bedroom_min'] == $i) ? 'checked="checked"' : '';?>
                                    <li class="fi_li2 <?php if ($i==0):?>top_li<?php endif;?>">
                                        <input value="<?php echo $i;?>" type="radio" name="bedroom_min" id="bedroom_min_<?php echo $i?>" class="css-checkbox" <?php echo $sel;?>/>
                                        <label for="bedroom_min_<?php echo $i?>" class="css-label1 radGroup2"><?php echo $i;?>+ <?php echo lang_key('Beds'); ?></label>
                                    </li>
                                <?php endfor;?>
                                <input type="hidden" value="50" name="bedroom_max" id="bedroom_max">
                            </ul>
                        </li>
                        <li class="fi_li1">
                            <a class="lnk_list" href="" data-value="bathrooms"><?php echo lang_key('bathrooms'); ?></a>
                            <ul class="fi_ul2" id="ul_bathrooms" style="display: none;">
                                <?php for ($i=0;$i<=7;$i++):?>
                                    <?php  $sel = (isset($data['bath_min']) && $data['bath_min'] == $i) ? 'checked="checked"' : '';?>
                                    <li class="fi_li2 <?php if ($i==0):?>top_li<?php endif;?>">
                                        <input value="<?php echo $i;?>" type="radio" name="bath_min" id="bath_min_<?php echo $i?>" class="css-checkbox" <?php echo $sel;?>/>
                                        <label for="bath_min_<?php echo $i?>" class="css-label1 radGroup2"><?php echo $i;?>+ <?php echo lang_key('Baths'); ?></label>
                                    </li>
                                <?php endfor;?>
                                <input type="hidden" value="50" name="bath_max" id="bath_max">
                            </ul>
                        </li>

                        <div class="OS_clere"></div>

                    </ul>
                    
                    <div class="box4">
                        <a title="<?php echo lang_key('Apply');?>" class="ap-but OS_apply" href=""><?php echo lang_key('Apply');?></a>
                        <a title="<?php echo lang_key('Reset');?>" class="ap-but OS_reset" href=""><?php echo lang_key('Reset');?></a>
                    </div>
                        <?php if(isset($alias) && ($alias == 'type' || $alias == 'search_result' || $alias == 'agents') ):?>
                            <div class="sel_map">
                                <?php $current_url = base64_encode(current_url().'/#data-content');?>
                                <a title="<?php echo lang_key('Map');?>" href="<?php echo site_url('show/toggle/map/'.$current_url);?>" ref="map" class="map-but"><?php echo lang_key('Map');?></a>
                                <a title="<?php echo lang_key('List');?>" href="<?php echo site_url('show/toggle/grid/'.$current_url);?>" ref="grid" class="list-but"><?php echo lang_key('List');?></a>
                            </div>
                        <?php endif;?>
                    
                    <div class="OS_clere"></div>
                </div>
              </form>
            </div>
        <?php endif; ?>

        <div class="clearfix"></div>

        <!-- container -->
        <div class="container <?php if (isset($alias) && ($alias == 'search_result') && ($this->session->userdata('view_style')=='map')): ?> full_container <?php endif;?>">

                            <?php render_widgets('content_top'); ?>

<?php echo (isset($content)) ? $content : ''; ?>

<?php render_widgets('content_bottom'); ?>

        </div>  
        <!-- /container -->


    <?php if (isset($alias) && ($alias == 'dbc_home')): ?>
                <div class="OS_map_down" id="map_home_view">
                    <div class="OS_in_map"><div>
                            <?php echo lang_key('MAP_SEARCHING');?>
                            <p><?php echo lang_key('MAP_FOR_APARTMENTS');?></p>
                            <p><?php echo lang_key('MAP_AROUND_YOU');?></p>
                        </div>
                        <div class="OS_map_but">
                            <a href="" onclick="find_map();"><?php echo lang_key('MAP');?></a>
                        </div>
                    </div>
                </div>
    <?php endif; ?>

    <?php if (isset($alias) && ($alias == 'search_result') && ($this->session->userdata('view_style')=='map')): ?>
    <?php else:?>
        <?php require_once'footer.php'; ?> 
    <?php endif;?>
    <?php require_once'includes_bottom.php'; ?>
    <input type="hidden" name="alias" id="alias" value="<?php echo $alias;?>">
</body>
</html>