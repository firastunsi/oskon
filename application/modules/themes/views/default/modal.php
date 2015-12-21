<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Login </h4>
            </div>
            <div class="modal-body">
                <?php
                $fb_enabled = get_settings('memento_settings','enable_fb_login','No');
                $gplus_enabled = get_settings('memento_settings','enable_gplus_login','No');
                if($fb_enabled=='Yes' || $gplus_enabled=='Yes'){
                ?>

                <!-- Social Logins-->
                <div style="height: 1px; background-color: #fff; text-align: center">
                  <span style="background-color:#fff; position: relative; top: -12px; font-size:16px;padding:0px 8px;">
                    Login with social account
                  </span>
                </div>
                <div style="text-align:center;">
                    <br>
                    <?php if($fb_enabled=='Yes'){?>
                    <a href="<?php echo site_url('account/newaccount/fb');?>">
                        <img src="<?php echo theme_url();?>/assets/social-icons/facebook_login.png"
                        data-toggle="tooltip" data-placement="top" data-original-title="Login with facebook"/>
                    </a>
                    <?php }?>
                    <?php if($gplus_enabled=='Yes'){?>
                    <a href="<?php echo site_url('account/newaccount/google_plus');?>">
                        <img src="<?php echo theme_url();?>/assets/social-icons/google+.png"
                        data-toggle="tooltip" data-placement="top" data-original-title="Login with google"/>
                    </a>
                    <?php }?>
                </div>
                <hr>
                <?php 
                }
                ?>
                <!-- Email Logins-->
                <div style="height: 1px; background-color: #fff; text-align: center">
                  <span style="background-color:#fff; position: relative; top: -12px; font-size:16px;padding:0px 8px;">
                    Login with email
                  </span>
                </div>
                <br>
                <form action="<?php echo site_url('account/login');?>" method="post">
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px; font-weight:bold;">
                            Email
                        </div>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="useremail" placeholder="" autofocus>
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px;font-weight:bold;">
                            Password
                        </div>
                        <div class="col-sm-12">
                            <input type="password" class="form-control" name="password" placeholder="">
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary pull-left"> <?php echo lang_key('login'); ?></button>
                            <a style="margin:10px 0 0 10px;" href="<?php echo site_url('account/register');?>"><?php echo lang_key('sign_up'); ?></a><a style="margin-left:10px;" href="<?php echo site_url('account/recoverpassword');?>">Recover</a>
                        </div>
                     </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Signup </h4>
            </div>
            <div class="modal-body">
                <?php 
                 if($fb_enabled=='Yes' || $gplus_enabled=='Yes'){
                ?>
                <!-- Social Logins-->
                <div style="height: 1px; background-color: #fff; text-align: center">
                  <span style="background-color:#fff; position: relative; top: -12px; font-size:16px;padding:0px 8px;">
                    Signup with social account
                  </span>
                </div>
                <div style="text-align:center;">
                    <br>
                    <?php if($fb_enabled=='Yes'){?>
                    <a href="<?php echo site_url('account/newaccount/fb');?>">
                        <img src="<?php echo theme_url();?>/assets/social-icons/facebook_login.png"
                        data-toggle="tooltip" data-placement="top" data-original-title="Signup with facebook"/>
                    </a>
                    <?php }?>
                    <?php if($gplus_enabled=='Yes'){?>
                    <a href="<?php echo site_url('account/newaccount/google_plus');?>">
                        <img src="<?php echo theme_url();?>/assets/social-icons/google+.png"
                        data-toggle="tooltip" data-placement="top" data-original-title="Signup with google"/>
                    </a>
                    <?php }?>
                </div>
                <hr>
                <?php }?>
                <!-- Email Signups-->
                <div style="height: 1px; background-color: #fff; text-align: center">
                  <span style="background-color:#fff; position: relative; top: -12px; font-size:16px;padding:0px 8px;">
                    Signup with email
                  </span>
                </div>
                <br>
                <form action="<?php echo site_url('account/register');?>" method="post">
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px; font-weight:bold;">
                            <?php echo lang_key('first_name'); ?>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" name="first_name" class="form-control" placeholder="" autofocus>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px; font-weight:bold;">
                            <?php echo lang_key('last_name'); ?>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" name="last_name" class="form-control" placeholder="">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px; font-weight:bold;">
                            Gender
                        </div>
                        <div class="col-sm-12">
                            <select name="gender" class="form-control">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>    
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px; font-weight:bold;">
                            <?php echo lang_key('username'); ?>
                        </div>
                        <div class="col-sm-12">
                            <input type="text" name="username" class="form-control" placeholder="">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px; font-weight:bold;">
                            Email
                        </div>
                        <div class="col-sm-12">
                            <input type="text" name="useremail" class="form-control" placeholder="">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px;font-weight:bold;">
                            Password
                        </div>
                        <div class="col-sm-12">
                            <input type="password" name="password" class="form-control" placeholder="">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-sm-3" style="padding-top:7px;font-weight:bold;">
                            Retype Password
                        </div>
                        <div class="col-sm-12">
                            <input type="password" name="repassword" class="form-control" placeholder="">
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary pull-left"> <?php echo lang_key('sign_up'); ?></button>
                            <a style="margin:10px 0 0 10px;" href="<?php echo site_url('account/trylogin');?>"><?php echo lang_key('sign_in'); ?></a><a style="margin-left:10px;" href="<?php echo site_url('account/recoverpassword');?>">Recover</a>
                        </div>
                     </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>