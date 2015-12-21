<?php
$curr_lang = ($this->uri->segment(1) != '') ? $this->uri->segment(1) : 'en';

if ($user->num_rows() <= 0) {
    ?>

    <div class="alert alert-danger"><?php echo lang_key('agent_not_found'); ?></div>

    <?php
} else {

    $user = $user->row();
    ?>

    <div class="row">

     

    <?php $current_url = base64_encode(current_url() . '/#data-content'); ?>

        <div id="data-content" class="col-md-9"  style="-webkit-transition: all 0.7s ease-in-out; transition: all 0.7s ease-in-out;">
            <h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-user"></i></span>&nbsp;<?php echo lang_key('agent_profile'); ?></h1>
            <div class="agent-container" id="panel">
        <div class="agent-holder clearfix re_agen1">
                    <div class="agent-image-holder re_ime">
                        <a href="<?php echo site_url('show/agentproperties/' . $user->id); ?>">
                            <img width="150" height="150" src="<?php echo get_profile_photo_by_id($user->id, 'thumb'); ?>">
                        </a>
                    </div>
                    <div class="detail rd-ags">
                        <h4 class="OS_name1"><a href="<?php echo site_url('show/agentproperties/' . $user->id); ?>"><?php echo $user->first_name . ' ' . $user->last_name; ?></a></h4>
                        <div class="OS_pro_no">
                            <a href="<?php echo site_url('show/agentproperties/' . $user->id); ?>" style="color:#fff;"><?php echo get_user_properties_count($user->id); ?> <?php echo lang_key('Properties'); ?></a>
                        </div>
                        <p class="OS_des_ag" id="aboutScrollDiv">
                            <?php $about_me = get_user_meta($user->id, 'about_me', '');
                                  echo ($about_me != '') ? $about_me : ''; 
                              ?>
                        </p>
               <p class="for_all-rd">
                   <span style="font-weight:bold"><?php echo lang_key('Phone')?>:</span> 
                        <?php 
                            if (get_user_meta($user->id, 'phone') != '' && get_user_meta($user->id, 'phone') != 'n/a'){
                                echo get_user_meta($user->id, 'phone');
                            }
                        ?>
                    </p>
                    
                    
                    <p class="for_all-rd">
                        <span style="font-weight:bold"><?php echo lang_key('Email');?>:</span> 
                    <a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a></p>
                        
                    <p class="for_all-rd">
                    <span style="font-weight:bold"><?php echo lang_key('company_address').": ";?></span>
                        <?php 
                            if (get_user_meta($user->id, 'company_address') != '' && get_user_meta($user->id, 'company_address') != 'n/a'){
                                echo get_user_meta($user->id, 'company_address');
                            }
                        ?>
                    </p>
                    
                        <div class="follow-agent clearfix">
                     
                            <ul class="social-networks clearfix">
                                <?php if (get_user_meta($user->id, 'fb_profile') != 'n/a' && get_user_meta($user->id, 'fb_profile') != '') { ?>
                                <li class="fb">
                                    <a href="<?php echo get_user_meta($user->id, 'fb_profile'); ?>" target="_blank"><i class="fa fa-facebook fa-lg"></i></a>
                                </li>
                                <?php } ?>
                                <?php if (get_user_meta($user->id, 'twitter_profile') != 'n/a' && get_user_meta($user->id, 'twitter_profile') != '') { ?>
                                <li class="twitter">
                                    <a href="<?php echo get_user_meta($user->id, 'twitter_profile'); ?>" target="_blank"><i class="fa fa-twitter fa-lg"></i></a>
                                </li>
                                <?php } ?>
                                <?php if (get_user_meta($user->id, 'li_profile') != 'n/a' && get_user_meta($user->id, 'li_profile') != '') { ?>
                                <li class="linkedin">
                                    <a href="<?php echo get_user_meta($user->id, 'li_profile'); ?>" target="_blank"><i class="fa fa-linkedin fa-lg"></i></a>
                                </li>
                                <?php } ?>
                                <?php if (get_user_meta($user->id, 'gp_profile') != 'n/a' && get_user_meta($user->id, 'gp_profile') != '') { ?>
                                <li class="gplus">
                                    <a href="<?php echo get_user_meta($user->id, 'gp_profile'); ?>" target="_blank"><i class="fa fa-google-plus fa-lg"></i></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div style="clear:both"></div>
                    </div>
                    
                </div>
            </div>
            <h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-home fa-4"></i></span>&nbsp;<?php echo lang_key($page_title); ?>
                <?php //require'switcher_view.php'; ?>
                <!--yazan need to redesign this switch-->
                <div class="sel_map OS_edit_ml">
                    <?php $current_url = base64_encode(current_url().'/#data-content');?>
                    <a title="<?php echo lang_key('Map');?>" href="<?php echo site_url('show/toggle/map/'.$current_url);?>" ref="map" class="map-but"><?php echo lang_key('Map');?></a>
                    <a title="<?php echo lang_key('List');?>" href="<?php echo site_url('show/toggle/grid/'.$current_url);?>" ref="grid" class="list-but"><?php echo lang_key('List');?></a>
                </div>
            </h1>
            <!-- Thumbnails container -->

            <?php
            if ($this->session->userdata('view_style') == 'map') {
                require 'map_view.php';
            } else {
                require 'grid_view.php';
            }
            ?>

            <div class="clearfix"></div>

            <?php if ($this->session->userdata('view_style') != 'map'): ?>
                <div style="text-align:center">
                    <ul class="pagination">
                        <?php echo (isset($pages)) ? $pages : ''; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <!-- /Thumbnails container -->
        </div>

        <div class="col-md-3">
            <?php render_widgets('right_bar_agent_properties'); ?>
        </div>
    </div> <!-- /row -->
    <?php
}
?>
<script type="text/javascript">
     $('#aboutScrollDiv').slimScroll({
        allowPageScroll: true,
        height: '75px'
    });
</script>