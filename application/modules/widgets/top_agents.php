<?php 
$total = 3;
$CI = get_instance();
$CI->load->database();
$CI->db->select('*');
$CI->db->from('users');
$CI->db->join('user_meta', 'user_meta.user_id = users.id');
$CI->db->where(array('users.status'=>1,'users.banned'=>0,'users.user_type <> '=>1));
$CI->db->where(array('user_meta.key'=> 'current_package','user_meta.value <>'=> '1'));
$CI->db->order_by("users.id", "asc");
$CI->db->limit($total,0);
$topusers = $CI->db->get();

if($topusers->num_rows() <= 0 ){ ?>
    <div class="alert alert-info">No Agents Found</div>
<?php
} else {
?>
<div class="widget our-agents" id="agents_widget-2">
    <h2 class="recent-grid"><i class="fa fa-user"></i> <?php echo lang_key('DBC_AGENTS'); ?></h2>
    <div class="content">
        <?php foreach ($topusers->result() as $user) {
        ?>
        <div class="agent clearfix">
            <div class="image">
                <a href="<?php echo site_url('show/agentproperties/'.$user->user_id);?>">
                    <img width="140" height="141" alt="<?php echo get_user_fullname_by_id($user->user_id);?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo get_profile_photo_by_id($user->user_id,'thumb');?>">
                </a>
            </div>
            <!-- /.image -->

            <div class="name">
                <a href="<?php echo site_url('show/agentproperties/'.$user->user_id);?>"><?php echo get_user_fullname_by_id($user->user_id);?></a>
            </div>
            <!-- /.name -->
            <div class="phone"><?php echo get_user_meta($user->user_id,'phone');?></div>
            <!-- /.phone -->
            <div class="email" style="width:100%"><a href="mailto:<?php echo $user->user_email;?>"><?php echo $user->user_email;?></a>
            </div>
            <!-- /.email -->
        </div><!-- /.agent -->
        <?php }?>

    </div><!-- /.content -->

</div>
<div class="view-more"><a class="" href="<?php echo site_url('agents');?>"><?php echo lang_key('view_all');?></a></div>
<div class="clearfix"></div>
<?php } ?>