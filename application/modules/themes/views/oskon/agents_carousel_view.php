<?php
if($query->num_rows()<=0)
{
    ?>
    <div class="alert alert-warning"><?php echo lang_key('no_agents_found'); ?></div>
<?php
}
else
{
    ?>
    <div id="owl-demo_agent">
        <?php $count = 1;?>
        <?php foreach ($query->result() as $user):?>
            <?php if ($count == 15) break;?>
            <?php
                $is_expired = is_user_package_expired($user->user_id);
                if($is_expired)
                    continue;
            ?>
            <div class="col-md-4 col-sm-4 facility OS_agent">
                <div class="thumbnail thumb-shadow">
                    <div class="property-header">
                        <a href="<?php echo site_url('show/agentproperties/'.$user->user_id);?>"></a>
                        <img width="145" height="141" alt="<?php echo get_user_fullname_by_id($user->user_id);?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo get_profile_photo_by_id($user->user_id,'thumb');?>">
                    </div>
                    <div class="caption">
                        <div class="OS_name">
                            <a href="<?php echo site_url('show/agentproperties/'.$user->user_id);?>"><?php echo get_user_fullname_by_id($user->user_id);?></a>
                        </div>
                        <div class="OS_pro_no">
                            <?php echo get_user_properties_count($user->user_id);?> <?php echo lang_key('Properties');?>
                        </div>
                        <?php $phone = get_user_meta($user->user_id,'phone');?>
                        <div class="phone"><?php if ($phone != 'n/a'):?><?php echo $phone;?><?php endif;?></div>
                        <div class="email" style="width:100%"><a href="mailto:<?php echo $user->user_email;?>"><?php echo $user->user_email;?></a></div>
                    </div>
                </div>
            </div>
             <?php $count++?>
        <?php endforeach;?>
    </div>
<?php
}
?>

<script type="text/javascript">
    $(document).ready(function() {

        $("#owl-demo_agent").owlCarousel({
            autoPlay: false, //Set AutoPlay to 3 seconds
            items : 3,
            itemsDesktop : [1199,3],
            itemsDesktopSmall : [979,3],
            navigation : true,
            slideSpeed:300,
            stopOnHover : true,
            pagination:false,
            navigationText : ["",""],
            responsive: true,
            responsiveRefreshRate : 200,
            responsiveBaseWidth: window
        });

        var maxHeight = -1;

        $('.estate-title-agent').each(function() {
            maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
        });

        $('.estate-title-agent').each(function() {
            $(this).height(maxHeight);
        });

        var maxHeight = -1;

        $('.estate-description-agent').each(function() {
            maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
        });

        $('.estate-description-agent').each(function() {
            $(this).height(maxHeight);
        });

    });


</script>