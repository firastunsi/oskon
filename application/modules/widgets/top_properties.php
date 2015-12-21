<?php 
$total = 3;
$curr_lang = ($CI->uri->segment(1)!='')?$CI->uri->segment(1):'en';
$CI = get_instance();
$CI->load->model('show/show_model');
$featured_esates = $CI->show_model->get_properties_by_range(0,$total,'total_view','desc');
if($featured_esates->num_rows() <= 0 ){ ?>
    <div class="alert alert-info">No Properties</div>
<?php
} else {
?>
            <div class="widget">

                <h2 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-home"></i></span> <?php echo lang_key('Top Properties');?></h2>

                <?php 
                foreach ($featured_esates->result() as $row) {

                    if(get_settings('realestate_settings','hide_posts_if_expired','No')=='Yes')
                    {
                          $is_expired = is_user_package_expired($row->created_by);
                          if($is_expired)
                            continue;                    
                    }
                ?>
                <?php $title = get_title_for_edit_by_id_lang($row->id,$curr_lang);?>
                <div class="thumbnail thumb-shadow widget-listing">
                    <div class="property-header">
                        <a href="<?php echo site_url('property/'.$row->unique_id.'/'.dbc_url_title($title));?>"></a>
                        <img class="property-header-image" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" alt="<?php echo character_limiter($title,20);?>" style="width:100%">
                            <?php if($row->estate_condition=='DBC_CONDITION_SOLD'){?>
                            <span class="property-contract-type new_st sold OS_new_s"><span><?php echo lang_key('DBC_CONDITION_SOLD'); ?></span>
                            <?php }elseif($row->estate_condition=='DBC_CONDITION_RENTED'){?>
                            <span class="property-contract-type new_st rented OS_new_s"><span><?php echo lang_key('DBC_CONDITION_RENTED'); ?></span>
                            <?php }elseif($row->purpose=='DBC_PURPOSE_SALE'){?>
                            <span class="property-contract-type new_st sale OS_new_s"><span><?php echo lang_key('DBC_PURPOSE_SALE'); ?></span>
                            <?php }else if($row->purpose=='DBC_PURPOSE_RENT'){?>
                            <span class="property-contract-type new_st rent"><span><?php echo lang_key('DBC_PURPOSE_RENT'); ?></span>
                            <?php }else if($row->purpose=='DBC_PURPOSE_BOTH'){?>
                            <span class="property-contract-type both"><span style="font-size: 11px"><?php echo lang_key('DBC_PURPOSE_BOTH'); ?></span>
                            <?php }?> 
                            </span>
                    </div>
                    <div class="caption" style="padding: 5px !important;">
                       
                     <span class="property-title OS_new-t"><?php echo character_limiter($title,20);?></span>
                        <div class="clearfix"></div>
                        
                        <p class="estate-description-car no_pad"><i class="fa fa-map-marker OS_map-icon"></i><?php echo lang_key(get_location_name_by_id($row->city)).', '.lang_key(get_location_name_by_id($row->state)).', '.lang_key(get_location_name_by_id($row->country));?></p>           
                        <div class="clearfix"></div>
                        <span class="property-price" style=""><?php echo show_price($row->total_price);?></span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <?php
                }
                ?>                
        </div>
        <div class="view-more"><a class="" href="<?php echo site_url('show/properties/top/plainkey');?>"><?php echo lang_key('view_all');?></a></div>
        <div class="clearfix"></div>
<?php } ?>