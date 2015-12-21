<h1 class="detail-title"><i class="fa fa-user"></i>&nbsp;<?php echo lang_key('sitemap'); ?></h1>
<div class="row">
    <div class="col-md-12" style="min-height:300px">
        <ol>
       <?php foreach($links->url as $url){?>
            <li><a href="<?php echo $url->loc;?>"><?php echo $url->loc;?></a></li>
       <?php }?>
        </ol>
    </div>
</div>
