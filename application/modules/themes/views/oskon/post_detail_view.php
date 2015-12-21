<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-53fb1205151cc4cf"></script>

<?php

if(count($blogpost)<=0){

?>

<div class="alert alert-danger"><?php echo lang_key('post_not_found'); ?></div>

<?php

}else{

?>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->

<div class="row">


  <div id="data-content" class="col-md-9" style="background:#fff;margin-top:15px;">

      <h2><?php echo $blogpost->title;?></h2>
      <p style="text-align:center;"><img src="<?php echo get_featured_photo_by_id($blogpost->featured_img);?>" alt="<?php echo $blogpost->title;?>" style="width:256px"></p>
      <div class="addthis_sharing_toolbox"></div>
      <?php echo $blogpost->description;?>

  </div>





<div class="col-md-3">

    <?php render_widgets('right_bar_blog_posts');?>

</div>



</div> <!-- /row -->
    <span class='st_sharethis_large' displayText='ShareThis'></span>
    <span class='st_facebook_large' displayText='Facebook'></span>
    <span class='st_twitter_large' displayText='Tweet'></span>
    <span class='st_linkedin_large' displayText='LinkedIn'></span>
    <span class='st_pinterest_large' displayText='Pinterest'></span>
    <span class='st_email_large' displayText='Email'></span>
<?php

}

?>

