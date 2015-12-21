<?php 
    if($query->num_rows()<=0)
    {
        ?>
        <div class="alert alert-warning"><?php echo lang_key('no_estates_found'); ?></div>
        <?php
    }
    else
    {
?>        
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
            <div class="col-md-12  facility <?php echo $pclass;?>">
                <div class="thumbnail thumb-shadow">
                <div style="margin-top:10px"></div>
                    <div class="col-md-3 col-sm-3">
                        <div class="property-header">
                            <a href="<?php echo site_url('property/'.$row->unique_id.'/'.dbc_url_title($title));?>"></a>
                            <img class="property-header-image" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" alt="<?php echo character_limiter($title,20);?>" style="width:100%">
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
                    </div>
                    <div class="col-md-9 col-sm-9">
                        <div class="caption" style="padding: 2px;">                            
                            <h2 class="list-estate-title"><?php echo character_limiter($title,20);?></h2>
                            <p><?php echo get_location_name_by_id($row->city).','.get_location_name_by_id($row->state).','.get_location_name_by_id($row->country);?></p>

                            <div style="clear:both;">
                                <span style="float:left; font-weight:bold;"><?php echo lang_key('type'); ?>:</span>
                                <span style="float:right; "><?php echo lang_key($row->type);?></span>
                            </div>

                            <div style="clear:both;">
                                <span style="float:left; font-weight:bold;"><?php echo lang_key('area'); ?>:</span>
                                <?php if($row->type=='DBC_TYPE_LAND'){?>
                                <span style="float:right; "><?php echo (int)$row->lot_size;?> <?php echo show_square_unit($row->lot_size_unit);?></sup></span>
                                <?php }else{?>
                                <span style="float:right; "><?php echo (int)$row->home_size;?> <?php echo show_square_unit($row->home_size_unit);?></sup></span>
                                <?php }?>
                            </div>
                            <div style="clear:both;" class="property-utilities">
                                <div title="Bathrooms" class="bathrooms" style="float: right; padding-top: 0px;">
                                    <?php if($row->type=='DBC_TYPE_LAND'){?>
                                    <div class="content">N/A</div>
                                    <?php }else{?>
                                    <div class="content"><?php echo $row->bath;?></div>
                                    <?php }?>
                                </div>
                                <div title="Bedrooms" class="bedrooms" style="float: left; padding-top: 0px;">
                                    <?php if($row->type=='DBC_TYPE_LAND'){?>
                                    <div class="content">N/A</div>
                                    <?php }else{?>
                                    <div class="content"><?php echo $row->bedroom;?></div>
                                    <?php }?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div style="clear:both; border-bottom:1px solid #ccc; margin:10px 0px;"></div>
                            <p>
                                <a href="<?php echo site_url('property/'.$row->unique_id.'/'.dbc_url_title($title));?>" class="btn btn-primary  btn-labeled">
                                    <?php echo lang_key('details'); ?>
                                    <span class="btn-label btn-label-right">
                                       <i class="fa fa-arrow-right"></i>
                                    </span>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        <?php endforeach; ?>
<?php 
    }
?>        