<div class="row">
    <!-- Gallery , DETAILES DESCRIPTION-->
    <div class="col-md-9">
        <h2 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-user"></i></span>&nbsp;<?php echo lang_key('all_agents');?></h2>
        <form style="" method="post" action="">

            <div class="input-group" style="width: 100%; margin-bottom: 20px">
                <input type="text" placeholder="<?php echo lang_key('Agent Name, Email');?>" style="height:40px;" class="form-control" name="agent_key" value="<?php echo $this->input->post('agent_key');?>">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-warning OS_but_se"><i class="fa fa-search"></i></button>
                  </span>
            </div>
        </form>
        <div class="clearfix"></div>
        <div class="agent-container" id="panel">
            <?php foreach($query->result() as $user){ 
   
                if(get_settings('realestate_settings','show_admin_agent','Yes')=='No' && $user->user_type==1)
                    continue;
                ?>
            
                <?php
                    $is_expired = is_user_package_expired($user->user_id);
                    if($is_expired)
                        continue;
                ?>
            
            <div class="agent-holder clearfix ">
                
                <div class="agent-image-holder ">
                    <a href="<?php echo site_url('show/agentproperties/'.$user->user_id);?>"><img width="150" height="150" src="<?php echo get_profile_photo_by_id($user->user_id,'thumb');?>"></a>
                </div>

                <div class="detail">
                    <h4 class="OS_name1"><a href="<?php echo site_url('show/agentproperties/'.$user->user_id);?>"><?php echo $user->first_name.' '.$user->last_name; ?></a></h4>
                    <div class="OS_pro_no">
                            <a href="<?php echo site_url('show/agentproperties/'.$user->user_id);?>" style="color:#fff;"><?php echo get_user_properties_count($user->user_id);?> <?php echo lang_key('Properties');?></a>
                    </div>
                    <p class="OS_des_ag" style="overflow-y:hidden !important;"><?php $about_me = get_user_meta($user->user_id, 'about_me','');echo ($about_me!='n/a')? character_limiter($about_me,200):''; ?></p>
                </div>
                <div class="OS_ag_detail">
                    <?php $phone = get_user_meta($user->user_id,'phone');?>
                    <p class="phone"><?php if ($phone != 'n/a'):?><?php echo $phone;?><?php endif;?></p>
                    <p class="email">
                    <a href="mailto:<?php echo $user->user_email; ?>"><?php echo $user->user_email; ?></a>
                    </p>
                   
                    <div class="follow-agent clearfix">
                    <ul class="social-networks clearfix">
                        <?php if(get_user_meta($user->user_id, 'fb_profile')!='n/a'){?>
                        <li class="fb">
                            <a href="<?php echo get_user_meta($user->user_id, 'fb_profile'); ?>" target="_blank"><i class="fa fa-facebook fa-lg"></i></a>
                        </li>
                        <?php }?>
                        <?php if(get_user_meta($user->user_id, 'twitter_profile')!='n/a'){?>
                        <li class="twitter">
                            <a href="<?php echo get_user_meta($user->user_id, 'twitter_profile'); ?>" target="_blank"><i class="fa fa-twitter fa-lg"></i></a>
                        </li>
                        <?php }?>
                        <?php if(get_user_meta($user->user_id, 'li_profile')!='n/a'){?>
                        <li class="linkedin">
                            <a href="<?php echo get_user_meta($user->user_id, 'li_profile'); ?>" target="_blank"><i class="fa fa-linkedin fa-lg"></i></a>
                        </li>
                        <?php }?>
                        <?php if(get_user_meta($user->user_id, 'gp_profile')!='n/a'){?>
                        <li class="gplus">
                            <a href="<?php echo get_user_meta($user->user_id, 'gp_profile'); ?>" target="_blank"><i class="fa fa-google-plus fa-lg"></i></a>
                        </li>
                        <?php }?>
                    </ul>
                </div>
                </div>

                
            </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div style="text-align:center">
                <ul class="pagination">
                <?php echo (isset($pages))?$pages:'';?>
                </ul>
            </div>
        </div>
    </div> <!-- col-md-9 -->


    <!--DETAILS SUMMARY-->
    <div class="col-md-3 ">
        <?php render_widgets('right_bar_all_agents');?>
    </div>
</div>

          