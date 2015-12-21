    ﻿<div class="OS_r-map">
        <div class="OS_r-map-header">
            <a class="pr-a OS_activ-a" href=""><?php echo lang_key('Properties_Left');?></a>
            <a class="ag-a" href=""><?php echo lang_key('Agents');?></a>
            <div class="OS_clere"></div>
        </div>
        <div class="OS_r-map-cnter" id="filter_properties_results">
            <div class="hed-text-map"><?php echo lang_key('Properties');?> (<?php echo $query->num_rows();?>)</div>
            <div class="OS_pro-holder" id="wrapper">
                <?php if ($query->num_rows() <= 0):?>
                    <div class="OS_no-sea">
                        <?php echo lang_key('No properties found ...');?>
                    </div>
                <?php else:?>
                    <div id="divScroll1">
                        <?php foreach ($query->result() as $row):?>
                            <?php
                                $title = get_title_for_edit_by_id_lang($row->id, $curr_lang);
                              ?>
                            <div class="thumbnail OS_padd_10 thumb-shadow widget-listing">
                                <div class="property-header">
                                    <a target="_blank" href="<?php echo site_url('property/' . $row->unique_id . '/' . dbc_url_title($title)); ?>"></a>
                                    <img class="property-header-image" src="<?php echo get_featured_photo_by_id($row->featured_img); ?>" alt="<?php echo character_limiter($title, 20); ?>" style="width:100%">

                                    <?php if ($row->estate_condition == 'DBC_CONDITION_SOLD'):?>
                                        <span class="property-contract-type new_st sold OS_new_s"><span><?php echo lang_key('DBC_CONDITION_SOLD'); ?></span></span>
                                    <?php elseif ($row->estate_condition == 'DBC_CONDITION_RENTED'):?>
                                        <span class="property-contract-type new_st sold OS_new_s"><span><?php echo lang_key('DBC_CONDITION_RENTED'); ?></span></span>
                                    <?php elseif ($row->purpose == 'DBC_PURPOSE_SALE'):?>
                                        <span class="property-contract-type new_st sale OS_new_s"><span><?php echo lang_key('DBC_PURPOSE_SALE'); ?></span></span>
                                    <?php elseif ($row->purpose == 'DBC_PURPOSE_RENT'):?>
                                        <span class="property-contract-type new_st rent OS_new_s"><span><?php echo lang_key('DBC_PURPOSE_RENT'); ?></span></span>
                                    <?php endif;?>
                                        
                                </div>
                                <div class="caption" style="padding: 5px !important;"> 
                                    <span class="property-title OS_new-t"><?php echo character_limiter($title, 20); ?></span>
                                    <div class="clearfix"></div>
                                    <p class="estate-description-car no_pad"><i class="fa fa-map-marker OS_map-icon"></i><?php echo lang_key(get_location_name_by_id($row->city)) . ', ' . lang_key(get_location_name_by_id($row->state)) . ', ' . lang_key(get_location_name_by_id($row->country)); ?></p>           
                                    <div class="clearfix"></div>
                                    <span class="property-price" style=""><?php echo show_price($row->total_price, $row->id); ?></span>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endif;?>
            </div>
            <div class="OS_clere"></div>
        </div>
        
        <div class="OS_r-map-cnter" id="filter_agents_results" style="display:none;">
            <div class="hed-text-map"><?php echo lang_key('Agents');?> <?php if (isset($is_agents) && $is_agents == 1): ?>(<?php echo $agents_details->num_rows();?>) &nbsp;&nbsp;&nbsp;&nbsp; <a href="" id="reset_agents"><?php echo lang_key('All Agents');?></a> <?php else:?>(0)<?php endif;?></div>
            <div class="OS_pro-holder">
                <?php if (isset($is_agents) && $is_agents == 1): ?>
                    <?php if ($agents_details->num_rows() <= 0):?>
                        <div class="OS_no-sea">
                            <?php echo lang_key('No agents found ...');?>
                        </div>
                    <?php else:?>
                            <div id="divScroll2">
                                <?php foreach ($agents_details->result() as $user):?>
                                    <?php if (get_settings('realestate_settings', 'show_admin_agent', 'Yes') == 'No' && $user->user_type == 1):?>
                                        <?php continue;?>
                                    <?php endif;?>
                                    <a target="_blank" href="" data="<?php echo $user->id; ?>">
                                        <div class="thumbnail OS_padd_10 thumb-shadow widget-listing">
                                            <div class="property-header">
                                                  <img class="property-header-image" src="<?php echo get_profile_photo_by_id($user->id, 'thumb'); ?>" alt="<?php echo $user->first_name . ' ' . $user->last_name; ?>" style="width:100%">        
                                            </div>
                                             <div class="caption" style="padding: 5px !important;"> 
                                                  <span class="property-title OS_new-t" style="margin-bottom: 3px !important; float: left;"><?php echo $user->first_name . ' ' . $user->last_name; ?></span>
                                                  <div class="clearfix"></div>
                                                  <span class="property-price" style="margin-top: 70px;"><?php echo get_user_properties_count($user->id);?> <?php echo lang_key('Properties');?></span>
                                             </div>
                                             <div class="clearfix"></div>
                                        </div>
                                    </a>
                                <?php endforeach;?>
                            </div>
                    <?php endif;?>
                <?php else:?>
                     <div class="OS_no-sea">
                        <?php echo lang_key('No agents found ...');?>
                    </div>
                <?php endif;?>
            </div>
            <div class="OS_clere"></div>
        </div>
        <div class="OS_clere"></div>
    </div>
    
<?php $curr_lang = ($this->uri->segment(1) != '') ? $this->uri->segment(1) : 'en';?>
<?php if ($curr_lang == 'en'):?>
    <?php $position = 'right'?>
<?php else:?>
    <?php $position = 'left'?>
<?php endif;?>
   
<script type="text/javascript">
    $('#divScroll2 a').click(function (e) {
        e.preventDefault();
        var agent_id = $(this).attr('data');
        $('#filter_header_frm #hdn_agent').val(agent_id);
        $('#filter_header_frm').submit();
    });
    
    $('#reset_agents').click(function (e) {
        e.preventDefault();
        $('#filter_header_frm #hdn_agent').val('');
        $('#filter_header_frm').submit();
    });
    
    
 
    $('#divScroll1').slimScroll({
        allowPageScroll: true,
        height: '550px',
        position: '<?php echo $position;?>'
    });

    $('#divScroll2').slimScroll({
        allowPageScroll: true,
        height: '550px',
        position: '<?php echo $position;?>'
    });
    
    
//    $(function(){
//        var newH = 0;
//        var windowH = $(window).height();
//        var wrapperH = $('#wrapper').height();
//        
//        if(wrapperH > windowH) {
//            newH = $(window).height();
//            $('#wrapper').css({'height':($(window).height())+'px'});
//            $('#divScroll1').slimScroll({
//                allowPageScroll: true,
//                height: newH-400
//            });
//        }
//        
//        $(window).resize(function(){
//            var windowH = $(window).height();
//            var wrapperH = $('#wrapper').height();
//            var differenceH = windowH - wrapperH;
//            newH = wrapperH + differenceH;
//            var truecontentH = $('#divScroll1').height();
//            
//            if(windowH > truecontentH) {
//                $('#wrapper').css('height', (newH)+'px');
//                 $('#divScroll1').slimScroll({
//                    allowPageScroll: true,
//                    height: newH
//                });
//                
//                
//            }
//        });
//    }); 
//    
    
    
</script>
