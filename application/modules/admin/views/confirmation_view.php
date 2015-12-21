<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> <?php echo lang_key('delete_warning'); ?>
    <?php echo lang_key('confirm_yes');?>
</div>
<a href="<?php echo $url."/$id/yes";?>" class="btn btn-success"><?php echo lang_key('confirm_yes'); ?></a><a href="<?php echo $url."/$id/no";?>" class="btn btn-inverse" style="margin-left:10px;"><?php echo lang_key('confirm_no'); ?></a>