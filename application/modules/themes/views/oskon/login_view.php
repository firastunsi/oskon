<h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-user"></i></span>&nbsp;<?php echo lang_key('Login'); ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <form action="<?php echo site_url('account/login');?>" method="post">
                                <?php echo $this->session->flashdata('msg');?>
                                <div class="top-margin">
                                    <label><?php echo lang_key('Email'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="useremail" value="<?php echo set_value('useremail');?>" class="form-control">
                                </div>
                                <?php echo form_error('useremail');?>
                                <div class="top-margin">
                                    <label><?php echo lang_key('Password'); ?> <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <?php echo form_error('password');?>
                                <hr>

                                <div class="row">
                                    <div class="col-lg-8">
                                        <label class="checkbox"> 
                                           <a target="_blank" href="<?php echo site_url('account/signup');?>"><?php echo lang_key('Register'); ?></a>
                                        </label>                        
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <button class="btn btn-action" type="submit"><?php echo lang_key('Login'); ?></button>
                                    </div>
                                </div>                                
                            </form>
                        </div>
                    </div>

                </div>        

    </div>    
</div> <!-- /row -->
