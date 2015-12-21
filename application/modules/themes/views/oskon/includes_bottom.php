    <!-- JavaScript libs are placed at the end of the document so the pages load faster -->

    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

    <script src="<?php echo theme_url();?>/assets/js/headroom.min.js"></script>

    <script src="<?php echo theme_url();?>/assets/js/jQuery.headroom.min.js"></script>

    <script src="<?php echo theme_url();?>/assets/js/template.js"></script>
    
    <script src="<?php echo theme_url();?>/assets/js/oskon.js"></script>


    <?php $curr_lang = ($this->uri->segment(1) != '') ? $this->uri->segment(1) : 'en'?>
    <?php if ($curr_lang == 'en'):?>
        <?php $fb_img = 'facebook_login.png'?>
    <?php else:?>
        <?php $fb_img = 'facebook_login_ar.png'?>
    <?php endif;?>


    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title" id="myModalLabel"><?php echo lang_key('Login');?> </h4>

            </div>

            <div class="modal-body">


                <?php
                $fb_enabled = get_settings('realestate_settings','enable_fb_login','No');
                $gplus_enabled = get_settings('realestate_settings','enable_gplus_login','No');
                if($fb_enabled=='Yes' || $gplus_enabled=='Yes'){
                ?>

                <!-- Social Logins-->
                <div class="OS_soc">
                <div style="height: 1px; background-color: #fff; text-align: center">
                  <span style="background-color:#fff; position: relative; top: -12px; font-size:16px;padding:0px 8px;">
                    <?php echo lang_key('Login with social account');?>
                  </span>
                </div>
                <div style="text-align:center;">
                    <br>
                    <?php if($fb_enabled=='Yes'){?>
                    <a href="<?php echo site_url('account/newaccount/fb');?>">
                        <img alt="facebook-login" src="<?php echo theme_url();?>/assets/social-icons/<?php echo $fb_img;?>"
                        data-toggle="tooltip" data-placement="top" data-original-title="Login with facebook"/>
                    </a>
                    <?php }?>
                    <?php if($gplus_enabled=='Yes'){?>
                    <a href="<?php echo site_url('account/newaccount/google_plus');?>">
                        <img alt="google-login" src="<?php echo theme_url();?>/assets/social-icons/google+.png"
                        data-toggle="tooltip" data-placement="top" data-original-title="Login with google"/>
                    </a>
                    <?php }?>
                </div>
                </div>
                <div class="new_or"></div>
                <?php 
                }
                ?>
                
                <!-- Email Logins-->

                <div class="OS_lo">

                <form action="<?php echo site_url('account/login');?>" method="post">

                     <div class="row">

                        <div class="col-sm-3" style="padding-top:7px; font-weight:bold; color: #0095A9;">
                            <?php echo lang_key('Email');?>
                        </div>

                        <div class="col-sm-12">

                            <input type="text" class="form-control" name="useremail" placeholder="" autofocus>

                        </div>

                     </div>

                     <br>

                     <div class="row">

                        <div class="col-sm-3" style="padding-top:7px;font-weight:bold; color: #0095A9;">
                            <?php echo lang_key('Password');?>
                        </div>

                        <div class="col-sm-12">

                            <input type="password" class="form-control" name="password" placeholder="">

                        </div>

                     </div>

                 
                     
                     <br>

                     <div class="row">

                        <div class="col-sm-12">

                            <button type="submit" class="btn btn-primary pull-left OS_but_log"><?php echo lang_key('Login');?></button>
<div class="log-and-reg">
                            <a style="margin:10px 0 0 10px;" href="<?php echo site_url('account/signup');?>"><?php echo lang_key('Register');?></a><a style="margin-left:10px;" href="<?php echo site_url('account/recoverpassword');?>"><?php echo lang_key('Recover');?></a></div>

                        </div>

                     </div>

                </form>
</div>
            </div>

            <div class="modal-footer">

            </div>

        </div>

        <!-- /.modal-content -->

    </div>

    <!-- /.modal-dialog -->

</div>

<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery('.view-filters').change(function(){
        var val = jQuery('select[name=view_orderby]').val();
        if(val!='')
        jQuery(this).parent().submit();

    });

});

</script>
<?php
$ga_tracking_code = get_settings('site_settings','ga_tracking_code','');
print $ga_tracking_code;
if($ga_tracking_code != ''){
    echo $ga_tracking_code;
}

?>

