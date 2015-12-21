<?php
if($query->num_rows()<=0)
{
    ?>
    <div class="alert alert-warning"><?php echo lang_key('no_features_found'); ?></div>
<?php
}
else
{
    ?>
    <div id="owl-demo">
        <?php foreach ($query->result() as $row):

            if(get_settings('realestate_settings','hide_posts_if_expired','No')=='Yes')
            {
                $is_expired = is_user_package_expired($row->created_by);
                if($is_expired)
                    continue;
            }

            $property_facilities = ($row->facilities!='')?(array)json_decode($row->facilities):array();
            $pclass='';
            foreach ($property_facilities as $class) {
                $pclass .= ' facility-'.$class;
            }

            ?>
            <?php $title = get_title_for_edit_by_id_lang($row->id,$curr_lang);?>
            <div class="col-md-4 col-sm-4 facility <?php echo $pclass;?>">
                <div class="thumbnail thumb-shadow">
                    <div class="property-header">
                        <a href="<?php echo site_url('property/'.$row->unique_id.'/'.dbc_url_title($title));?>"></a>
                        <img class="property-header-image" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" alt="<?php echo character_limiter($title,20);?>" style="width:255px; height:185px;">
                        <?php if($row->estate_condition=='DBC_CONDITION_SOLD'){?>
                        <span class="property-contract-type sold"><span><?php echo lang_key('DBC_CONDITION_SOLD'); ?></span>
                        <?php }elseif($row->estate_condition=='DBC_CONDITION_RENTED'){?>
                            <span class="property-contract-type sold"><span><?php echo lang_key('DBC_CONDITION_RENTED'); ?></span>
                            <?php }elseif($row->purpose=='DBC_PURPOSE_SALE'){?>
                            <span class="property-contract-type sale"><span><?php echo lang_key('DBC_PURPOSE_SALE'); ?></span>
                                <?php }else if($row->purpose=='DBC_PURPOSE_RENT'){?>
                                <span class="property-contract-type rent"><span><?php echo lang_key('DBC_PURPOSE_RENT'); ?></span>
                                    <?php }else if($row->purpose=='DBC_PURPOSE_BOTH'){?>
                                    <span class="property-contract-type both"><span style="font-size: 11px"><?php echo lang_key('DBC_PURPOSE_BOTH'); ?></span>
                                        <?php }?>
                                    </span>
                            <div class="property-thumb-meta">
                                <span class="property-price"><?php echo show_price($row->total_price,$row->id);?></span>
                            </div>
                    </div>
                    <div class="caption">
                        <h2 class="estate-title-car"><?php echo character_limiter($title,20);?></h2>
                        <p class="estate-description-car"><i class="fa fa-map-marker OS_map-icon"></i><?php echo lang_key(get_location_name_by_id($row->city)).', '.lang_key(get_location_name_by_id($row->state)).', '.lang_key(get_location_name_by_id($row->country));?></p>

                        <div class="OS_pro_div" style="clear:both;">
                        <span class="rtl-left" style="float:left; font-weight:bold;"><?php echo lang_key('type'); ?>:</span>
                            <span class="OS_pad-l"  style="float:left; "><?php echo lang_key($row->type);?></span>
                        </div>  
                        
                        <div class="OS_pro_div" style="clear:both;">
                            <span class="rtl-right"  style="float:left; font-weight:bold;"><?php echo lang_key('area'); ?>:</span>
                           <?php if($row->category_type=='DBC_TYPE_LAND' || $row->category_type=='DBC_TYPE_COMMERCIAL'){?>
                            <span class="OS_pad-l"  style="float:left; "><?php echo (int)$row->lot_size;?> <?php echo lang_key(show_square_unit($row->lot_size_unit));?></span>
                            <?php }else{?>
                                <span class="OS_pad-l"  style="float:left; "><?php echo (int)$row->home_size;?> <?php echo lang_key(show_square_unit($row->home_size_unit));?></span>
                            <?php }?>
                        </div>
                        <div style="clear:both;" class="property-utilities OS_dit_dow">
                            <?php if($row->category_type=='DBC_TYPE_RESIDENTIAL' || ($row->category_type=='DBC_TYPE_COMMERCIAL' && $row->type =='DBC_TYPE_OFFICE') ){?>
                                <div title="Bathrooms" class="bathrooms OS_dit_dow-icon" style="padding-top: 0px;">
                                    <div class="content"><?php echo $row->bath;?>&nbsp;<?php echo lang_key('Baths');?></div>
                                </div>
                            <?php }?>
                            <?php if($row->category_type=='DBC_TYPE_RESIDENTIAL'){?>
                                <div title="Bedrooms" class="bedrooms OS_dit_dow-icon" style="padding-top: 0px;">
                                    <div class="content"><?php echo $row->bedroom;?>&nbsp;<?php echo lang_key('Beds');?></div>
                                </div>
                            <?php }?>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
<?php
}
?>

<script type="text/javascript">


    $(document).ready(function() {

        $("#owl-demo").owlCarousel({
            autoPlay: false, //Set AutoPlay to 3 seconds
            items : 4,
            itemsDesktop : [1199,4],
            itemsDesktopSmall : [979,4],
            navigation : true, // in old navigation
            slideSpeed:300,
            stopOnHover : true,
            pagination:false,
            navigationText : ["",""],
            responsive: true,
            responsiveRefreshRate : 200,
            responsiveBaseWidth: window,
            //rtl: true
        });
        
     

        var maxHeight = -1;

        $('.estate-title-car').each(function() {
            maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
        });

        $('.estate-title-car').each(function() {
            $(this).height(maxHeight);
        });

        var maxHeight = -1;

        $('.estate-description-car').each(function() {
            maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
        });

        $('.estate-description-car').each(function() {
            $(this).height(maxHeight);
        });

    });


</script>