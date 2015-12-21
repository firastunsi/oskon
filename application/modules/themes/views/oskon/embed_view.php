<?php include'includes_top.php';?>
<link rel="stylesheet" href="<?php echo theme_url();?>/assets/css/lightbox.css">
<script src="<?php echo theme_url();?>/assets/js/lightbox.min.js"></script>
<?php 

$curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';

?>

<?php if($post->num_rows()<=0){?>

    <div class="alert alert-danger">Invalid post id</div>

<?php }else{

    $row = $post->row();

    $estate_title =  get_title_for_edit_by_id_lang($row->id,$curr_lang);

    $featured_image_path = get_featured_photo_by_id($row->featured_img);

    if($row->estate_condition=='DBC_CONDITION_SOLD'){

        $property_status = lang_key('DBC_CONDITION_SOLD');

    }elseif($row->purpose=='DBC_PURPOSE_SALE'){

        $property_status = lang_key('DBC_PURPOSE_SALE');

    }else if($row->purpose=='DBC_PURPOSE_RENT'){

        $property_status = lang_key('DBC_PURPOSE_RENT');

    }else if($row->purpose=='DBC_PURPOSE_BOTH'){

        $property_status = lang_key('DBC_PURPOSE_BOTH');

    }

    $property_address_short = get_location_name_by_id($row->city).','.get_location_name_by_id($row->state).','.get_location_name_by_id($row->country);

    $property_price = number_format($row->total_price);

?>

<style>
    .tags-panel span a{
        color: #fff !important;

    }
    .tags-panel span{
        margin-right: 5px;
    }

    #details-map img { max-width: none; }

    .carousel-inner > .next, .carousel-inner > .prev{

        position: relative !important;

    }

    .item img{

        margin: 0 auto !important;

    }

    .item{

        height: 300px !important;

    }

    .left,.next{

        height: 300px !important;

    }

    #myCarousel{

        height: 300px !important;

        overflow: hidden !important;

    }

</style>
<?php 
    $curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
    $estate_title =  get_title_for_edit_by_id_lang($row->id,$curr_lang);
?>
<?php get_view_count($row->id,'detail');?>

     <div style="padding:0;margin:0;width:100%">

        <div class="orange-border panel panel-primary effect-helix in" style="margin-bottom:0px;padding-bottom:0px;">

            <div class="panel-heading orange"><?php echo $estate_title; ?></div>

            <div class="panel-body">

                <div class="info_list">

                <div class="property-header">
                    <div style="text-align:center">
                        <img class="property-header-image" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" alt="" style="width:256px">
                    </div>
                    <?php if($row->status==2){?>

                    <span class="property-contract-type sold"><span><?php echo lang_key('DBC_CONDITION_SOLD'); ?></span>

                        <?php }elseif($row->purpose=='DBC_PURPOSE_SALE'){?>

                        <span class="property-contract-type sale"><span><?php echo lang_key('DBC_PURPOSE_SALE'); ?></span>

                            <?php }else if($row->purpose=='DBC_PURPOSE_RENT'){?>

                            <span class="property-contract-type rent"><span><?php echo lang_key('DBC_PURPOSE_RENT'); ?></span>

                                <?php }else if($row->purpose=='DBC_PURPOSE_BOTH'){?>

                                <span class="property-contract-type both"><span style="font-size: 11px"><?php echo lang_key('DBC_PURPOSE_BOTH'); ?></span>

                                    <?php }?>

                      </span>

                            <div class="property-thumb-meta">

                                <span class="property-price"><?php echo $this->session->userdata('system_currency').number_format($row->total_price);?></span>

                            </div>

                </div>



                </div>

                <div class="divider"></div>



                <div class="info_list">

                    <span class="info-title" style=""><?php echo lang_key('address'); ?>:</span>

                    <span class="info-content"><?php echo $row->address;?></span>

                </div>



                <div class="divider"></div>



                <div class="info_list">

                    <span class="info-title" style=""><?php echo lang_key('city'); ?>:</span>

                    <span class="info-content"><?php echo get_location_name_by_id($row->city);?></span>

                </div>



                <div class="divider"></div>



                <div class="info_list">

                    <span class="info-title" style=""><?php echo lang_key('state_province'); ?>:</span>

                    <span class="info-content"><?php echo get_location_name_by_id($row->state);?></span>

                </div>





                <div class="divider"></div>



                <div class="info_list">

                    <span class="info-title" style=""><?php echo lang_key('country'); ?>:</span>

                    <span class="info-content"><?php echo get_location_name_by_id($row->country);?></span>

                </div>

                <div class="divider"></div>



                <div class="info_list">

                    <span class="info-title" style=""><?php echo lang_key('zip_code'); ?>:</span>

                    <span class="info-content"><?php echo $row->zip_code;?></span>

                </div>

                <?php 
                if($row->purpose=='DBC_PURPOSE_RENT'){
                ?>
                <div class="divider"></div>
                <div class="info_list">

                    <span class="info-title" style="">From:</span>

                    <span class="info-content"><?php echo get_post_meta($row->id,'from_rent_date');?></span>

                </div>
                <div class="divider"></div>
                <div class="info_list">

                    <span class="info-title" style="">To:</span>

                    <span class="info-content"><?php echo get_post_meta($row->id,'to_rent_date');?></span>

                </div>                
                <?php 
                }
                ?>

                <div class="info_list">

                    <span class="info-title" style="margin-top:10px;"><a href="<?php echo site_url('property/'.$row->unique_id.'/'.url_title($estate_title));?>" class="btn btn-success">Detail</a></span>

                </div>

            </div>

        </div>

    </div>

<script type="text/javascript">

    function getUrlVars(url) {

        var vars = {};

        var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {

            vars[key] = value;

        });

        return vars;

    }



    function showVideoPreview(url)

    {

      if(url.search("youtube.com")!=-1)

      {

        var video_id = getUrlVars(url)["v"];

        //https://www.youtube.com/watch?v=jIL0ze6_GIY

        var src = '//www.youtube.com/embed/'+video_id;

        //var src  = url.replace("watch?v=","embed/");

        var code = '<iframe class="thumbnail" width="100%" height="420" src="'+src+'" frameborder="0" allowfullscreen></iframe>';

        jQuery('#video_preview').html(code);

      }

      else if(url.search("vimeo.com")!=-1)

      {

        //http://vimeo.com/64547919

        var segments = url.split("/");

        var length = segments.length;

        length--;

        var video_id = segments[length];

        var src  = url.replace("vimeo.com","player.vimeo.com/video");

        var code = '<iframe class="thumbnail" src="//player.vimeo.com/video/'+video_id+'" width="100%" height="420" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

        jQuery('#video_preview').html(code);

      }

      else

      {

        //alert("only youtube and video url is valid");

      }

    }



jQuery(document).ready(function(){

    $('#myCarousel').carousel();

    jQuery('#video_url').change(function(){

          var url = jQuery(this).val();

          showVideoPreview(url);

        }).change();

});

</script>

<?php

}

?>



          