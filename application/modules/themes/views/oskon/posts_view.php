<div class="row">
    <!-- Gallery , DETAILES DESCRIPTION-->
    <div class="col-md-9">
        <h1 class="blog-detail-title"><i class="fa fa-home fa-4"></i>&nbsp;<?php echo $page_title;?></h1>

        <div class="agent-container" id="panel">
            <?php 
            if($posts->num_rows()<=0){
              ?>
                <div class="alert alert-warning"><?php echo lang_key('post_not_found'); ?></div>
              <?php
            }
            else
            foreach($posts->result() as $post){ ?>
            <div class="agent-holder clearfix">
                <h2 class="blog-post-subtitle"><a href="<?php echo site_url('show/postdetail/'.$post->id.'/'.dbc_url_title($post->title));?>"><?php echo $post->title;?></a></h2>
                <!--div class="agent-image-holder">
                    <a href="<?php echo site_url('show/agentproperties/'.$user->id);?>"><img width="150" height="150" src="<?php echo get_profile_photo_by_id($user->id,'thumb');?>"></a>
                </div-->
                <a href="<?php echo site_url('show/postdetail/'.$post->id.'/'.dbc_url_title($post->title));?>"><img src="<?php echo get_featured_photo_by_id($post->featured_img);?>" class="post-thumb"></a>
                <?php echo truncate(strip_tags($post->description),400,'&nbsp;<a href="'.site_url('show/postdetail/'.$post->id.'/'.dbc_url_title($post->title)).'">'.lang_key('view_more').'</a>',false);?>

                <?php $detail_link = site_url('show/postdetail/'.$post->id.'/'.dbc_url_title($post->title)); ?>
                <div class="follow-agent clearfix">
                    <ul class="social-networks clearfix">
                        <li class="fb">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-facebook fa-lg"></i></a>
                        </li>
                        <li class="twitter">
                            <a href="https://twitter.com/share?url=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-twitter fa-lg"></i></a>
                        </li>
                        <li class="gplus">
                            <a href="https://plus.google.com/share?url=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-google-plus fa-lg"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div style="text-align:center">
                <ul class="pagination">
                <?php echo (isset($pages))?$pages:'';?>
                </ul>
            </div>
        </div>
    </div> <!-- col-md-9 -->


    <!--DETAILS SUMMARY-->
    <div class="col-md-3 ">
        <?php render_widgets('right_bar_blog_posts');?>
    </div>
</div>

          