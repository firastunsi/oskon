        <div class="row">
            
            <!-- Article main content -->
            <article class="col-sm-9 maincontent">
                    <h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-envelope-o"></i></span><?php echo lang_key('contact_us');?></h1>
                    <?php echo $this->session->flashdata('msg');?>
                    <form action="<?php echo site_url('show/sendcontactemail');?>" method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <input class="form-control" type="text" name="sender_name" value="<?php echo set_value('sender_name');?>" placeholder="Name">
                                <?php echo form_error('sender_name');?> 
                            </div>
                            <div class="col-sm-6">
                                <input class="form-control" type="text" name="sender_email" value="<?php echo set_value('sender_email');?>" placeholder="Email">
                                <?php echo form_error('sender_email');?>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea placeholder="Type your message here..." class="form-control" rows="9" name="msg"><?php echo set_value('msg');?></textarea>
                                <?php echo form_error('msg');?>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <span style="float:left"><?php echo (isset($question))?$question:'';?></span>
                                <input type="text" name="ans" value="" style="width:60px;float:left;margin-left:10px;" class="form-control">
                                <div style="clear:both"></div><?php echo form_error('ans');?>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                            </div>
                            <div class="col-sm-6 text-right">
                                <input class="btn btn-action" type="submit" value="Send message">
                            </div>
                        </div>
                    </form>

            </article>
            <!-- /Article -->
            
            <!-- Sidebar -->
            <aside class="col-sm-3 sidebar sidebar-right">
                <?php echo render_widget('contact_text');?>
            </aside>
            <!-- /Sidebar -->

        </div>