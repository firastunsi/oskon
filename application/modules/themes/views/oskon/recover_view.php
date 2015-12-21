<h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-key"></i></span>&nbsp;<?php echo lang_key('recover'); ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <form action="<?php echo site_url('account/recoverpassword/');?>" method="post">
                                <?php echo $this->session->flashdata('msg');?>
                                <div class="top-margin">
                                    <label><?php echo lang_key('Email'); ?><span class="text-danger">*</span></label>
                                    <input type="text" name="user_email" value="<?php echo set_value('user_email');?>" class="form-control">
                                </div>
                                <?php echo form_error('user_email');?>
                                <hr>

                                <div class="row">
                                    <div class="col-lg-8">
                                        <label class="checkbox"> 
                                           <a target="_blank" href="<?php echo site_url('account/signup');?>"><?php echo lang_key('Register'); ?></a>
                                        </label>                        
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <button class="btn btn-action" type="submit"><?php echo lang_key('Send'); ?></button>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                    </div>

                </div>        

    </div>    
</div> <!-- /row -->
