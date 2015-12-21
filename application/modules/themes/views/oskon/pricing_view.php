<h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-check"></i></span><?php if (isset($alias) && $alias == 'renew'): ?>
        <?php echo lang_key('update_package'); ?>
    <?php else: ?>
        <?php echo lang_key('choose_a_package'); ?>
    <?php endif; ?>

</h1>
<?php $curr_lang = ($this->uri->segment(1) != '') ? $this->uri->segment(1) : 'en'?>
<?php if ($curr_lang == 'en'):?>
    <?php $style = 'float:right;'?>
    <?php $style2 = 'rtl-left'?>
    <?php $Arrow = 'fa-arrow-right';?>
    <?php $ArrowPackag = 'fa-arrow-circle-o-right';?>
<?php else:?>
    <?php $style = 'float:left;'?>
    <?php $style2 = ''?>
    <?php $Arrow = 'fa-arrow-left';?>
    <?php $ArrowPackag = 'fa-arrow-circle-o-left';?>
<?php endif;?>
<div class="row">
    <div class="col-md-12">
           <?php echo $this->session->flashdata('msg'); ?>
           <?php if (isset($alias) && $alias == 'renew'): ?>
            <div class="col-md-4 col-sm-4" style="margin-top:15px;margin-right:15px;">
                <div class="thumbnail thumb-shadow">
                    <div class="caption">
                        <h2 class="package-title"><?php echo lang_key('Your Package');?> - <?php print lang_key($current_package_name); ?></h2> 
                        <div class="OS_pack_pa" style="height: 201px;">
                            <p style="min-height:25px;"><?php echo lang_key($current_package_desc)?></p>
                            
                            <div style="clear:both;">
                                <span class="rtl-right" tyle="float:left; font-weight:bold;"><?php echo lang_key('price'); ?>:</span>
                                <?php if ($package_price != 0): ?>
                                    <span class="<?php echo $style2?>" style="<?php echo $style?>"><?php echo show_package_price($package_price); ?></span>
                                <?php else: ?>
                                    <span class="<?php echo $style2?>" style="<?php echo $style?>"><?php echo lang_key('Free'); ?></span>
                                <?php endif; ?>

                            </div>
                            
                            <div style="clear:both; border-bottom:1px solid #ccc; margin:10px 0px;"></div>
                            <div style="clear:both;">
                                <span class="rtl-right" style="float:left; font-weight:bold;"><?php echo lang_key('Expired on')?>:</span>
                                <span class="<?php echo $style2?>" style="<?php echo $style?> ">
                                    <?php if ($package_price > 0): ?>
                                        <?php print $package_expirtion_date; ?>
                                    <?php else: ?>
                                        <?php echo lang_key('Unlimited');?>
                                    <?php endif;?>
                                </span>
                            </div>
                            
                            <div style="clear:both; border-bottom:1px solid #ccc; margin:10px 0px;"></div>
                            
                            <div style="clear:both;">
                                <span class="rtl-right" style="float:left; font-weight:bold;">
                                    <?php echo lang_key('Usage');?>:
                                </span>
                                <span class="<?php echo $style2?>" style="<?php echo $style?>; ">
                                    <?php print $usage_posts; ?> <?php echo lang_key('Posts');?>
                                </span>
                            </div>
                            
                            <div style="clear:both; border-bottom:1px solid #ccc; margin:10px 0px;"></div>
                            
                            <div style="clear:both;">
                                <span class="rtl-right" style="float:left; font-weight:bold;">
                                    <?php echo lang_key('Remaining Posts');?>:
                                </span>
                                <span class="<?php echo $style2?>" style="<?php echo $style?>; ">
                                    <?php print $remaining_posts; ?> <?php echo lang_key('Posts');?>
                                </span>
                            </div>
                       
                        </div>
                    </div>
                </div>
            </div>
            <div class="up_to_pa" style="float: left;height: 268px;width: 46px;font-size: 48px;color: #0095A9;padding-top: 15px;line-height: 268px;">
                <i class="fa <?php echo $ArrowPackag;?>"></i>
            </div>
        <?php endif;?>
        <div class="">
        <?php foreach ($packages->result() as $package): ?>
                <?php $action = (isset($alias) && $alias == 'renew') ? site_url('account/renewpackage') : site_url('account/takepackage'); ?>
                <form action="<?php echo $action; ?>" method="post">
                    <input type="hidden" name="package_id" value="<?php echo $package->id; ?>">
                    <div class="col-md-4 col-sm-4" style="margin-top:15px;margin-right:15px;">
                        <div class="thumbnail thumb-shadow">

                            <div class="caption">
                                <h2 class="package-title"><?php echo lang_key($package->title); ?></h2> 
                                <div class="OS_pack_pa">
                                    <p style="min-height:25px;"><?php echo lang_key($package->description); ?></p>                       
                                    <div style="clear:both;">
                                        <span class="rtl-right" tyle="float:left; font-weight:bold;"><?php echo lang_key('price'); ?>:</span>
                                        <?php if ($package->price != 0): ?>
                                            <span class="<?php echo $style2?>" style="<?php echo $style?>; "><?php echo show_package_price($package->price); ?></span>
                                        <?php else: ?>
                                            <span class="<?php echo $style2?>" style="<?php echo $style?>; "><?php echo lang_key('Free'); ?></span>
                                        <?php endif; ?>

                                    </div>
                                    <div style="clear:both; border-bottom:1px solid #ccc; margin:10px 0px;"></div>
                                    <div style="clear:both;">
                                        <span class="rtl-right" style="float:left; font-weight:bold;"><?php echo lang_key('Limit'); ?>:</span>
                                        <span class="<?php echo $style2?>" style="<?php echo $style?>; ">
                                        <?php if ($package->price != 0): ?>
                                                <?php echo $package->expiration_time; ?> <?php echo lang_key('days'); ?>
                                            <?php else: ?>
                                                <?php echo lang_key('Unlimited'); ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <div style="clear:both; border-bottom:1px solid #ccc; margin:10px 0px;"></div>
                                    <div style="clear:both;">
                                        <span class="rtl-right" style="float:left; font-weight:bold;"><?php echo lang_key('usage'); ?>:</span>
                                        <span class="<?php echo $style2?>" style="<?php echo $style?>; "><?php echo $package->max_post; ?> <?php echo lang_key('posts'); ?></span>
                                    </div>
                                    <div style="clear:both; border-bottom:1px solid #ccc; margin:10px 0px;"></div>
                                    <p>
                                        <button type="submit" href="<?php echo site_url('show/registerinfo'); ?>" class="btn btn-primary  btn-labeled">
                                            <?php echo lang_key('subscribe'); ?>
                                            <span class="btn-label btn-label-right">
                                                <i class="fa  <?php echo $Arrow;?>"></i>
                                            </span>
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>    
            <?php endforeach; ?>
        </div>
        </div>
    
        
     
</div> <!-- /row -->
