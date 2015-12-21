<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<?php require_once'includes_top.php';?>
<link rel="stylesheet" href="<?php echo theme_url();?>/assets/css/lightbox.css">
<script src="<?php echo theme_url();?>/assets/js/lightbox.min.js"></script>
<style type="text/css">
@media print {
  a[href]:after {
    content: none !important;
  }

  .no-print{
    display: none !important;
  }
  .new-page{
    page-break-after: always;
  }
}
</style>
<?php 

$curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';

?>

<?php if($post->num_rows()<=0){?>

    <div class="alert alert-danger">Invalid post id</div>

<?php }else{

    $row = $post->row();

    $estate_title =  get_title_for_edit_by_id_lang($row->id,$curr_lang);

    $featured_image_path = get_featured_photo_by_id($row->featured_img);

    if($row->status==2){

        $property_status = lang_key('DBC_CONDITION_SOLD');

    }elseif($row->purpose=='DBC_PURPOSE_SALE'){

        $property_status = lang_key('DBC_PURPOSE_SALE');

    }else if($row->purpose=='DBC_PURPOSE_RENT'){

        $property_status = lang_key('DBC_PURPOSE_RENT');

    }else if($row->purpose=='DBC_PURPOSE_BOTH'){

        $property_status = lang_key('DBC_PURPOSE_BOTH');

    }

    $property_address_short = get_location_name_by_id($row->city).','.get_location_name_by_id($row->state).','.get_location_name_by_id($row->country);

    $property_price = $row->total_price;

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

<script type="text/javascript">

    $(document).ready(function() {

//        directionsMap()

        var iconBase = '<?php echo theme_url();?>/assets/images/map-icons/';

//        var directionsDisplay;

//        var directionsService = new google.maps.DirectionsService();



        var myLatitude = parseFloat('<?php echo $row->latitude; ?>');

        var myLongitude = parseFloat('<?php echo $row->longitude; ?>');

        function initialize() {

//            directionsDisplay = new google.maps.DirectionsRenderer();

            var myLatlng = new google.maps.LatLng(myLatitude,myLongitude);

            var mapOptions = {

                zoom: 12,

                center: myLatlng

            }

            var map = new google.maps.Map(document.getElementById('details-map'), mapOptions);

//            directionsDisplay.setMap(map);



            var contentString = '<div class="thumbnail thumb-shadow map-thumbnail">' + '<div class="property-header">'

                                + '<a href="#"></a>' + '<img class="property-header-image" src="<?php echo $featured_image_path;?>" alt="" style="width:100%">'

                                    + '<span class="property-contract-type sale">' + '<span><?php echo $this->session->userdata('system_currency').$property_status; ?></span>' + '</span>'

                                + '<div class="property-thumb-meta">' + '<span class="property-price"><?php echo $property_price; ?></span>' + '</div></div>'

                                + '<div class="caption">' + '<h4><?php echo $estate_title; ?></h4>' + '<p><?php echo $property_address_short; ?></p>' + '</div></div>';



            var infowindow = new google.maps.InfoWindow({

                content: contentString

            });



            var marker, i;

            var markers = [];







            <?php if($row->type == 'DBC_TYPE_COMSPACE'){ ?>

            var icon_path = iconBase + 'office.png';

            <?php } else if($row->type == 'DBC_TYPE_LAND'){ ?>

            var icon_path = iconBase + 'land.png';

            <?php } else if($row->type == 'DBC_TYPE_HOUSE' || $row->type == 'DBC_TYPE_VILLA'){ ?>

            var icon_path = iconBase + 'bighouse.png';

            <?php } else { ?>

            var icon_path = iconBase + 'apartment.png';

            <?php } ?>

//            var icon_path = iconBase + 'apartment.png';



            marker = new google.maps.Marker({

                position: myLatlng,

                map: map,

                title: '<?php echo $estate_title; ?>',

                icon: icon_path

            });



            google.maps.event.addListener(marker, 'click', (function(marker, i) {

                return function() {

//                    infowindow.setContent("Sample");

                    infowindow.open(map, marker);

                }

            })(marker, i));

            markers.push(marker);





        }





        google.maps.event.addDomListener(window, 'load', initialize);





    });

</script>

<?php get_view_count($row->id,'detail');?>

<div class="row">

    <!-- Gallery , DETAILES DESCRIPTION-->

    <div class="col-md-9 col-sm-9">

        <h1 class="detail-title"><i class="fa fa-home fa-4"></i>&nbsp;<?php echo $estate_title;?></h1>



        <!-- IMAGE SLIDER STARTS -->

        <div id="myCarousel" class="carousel slide">

            <ol class="carousel-indicators">

                <?php $i=0; $images = ($row->gallery!='')?json_decode($row->gallery):array();?>

                <?php foreach($images as $img){?>

                    <li data-target="#myCarousel" data-slide-to="<?php echo $i;?>" class="<?php echo ($i==0)?'active':'';?>"></li>

                <?php $i++; }?>

                

                <li data-target="#myCarousel" data-slide-to="1" class=""></li>

            </ol>

            <div class="carousel-inner">

                <?php $i=0; $images = ($row->gallery!='')?json_decode($row->gallery):array();?>

                <?php foreach($images as $img){?>

                <div class="item <?php echo ($i==0)?'active':'';?>">

                    <img src="<?php echo base_url('uploads/gallery/'.$img);?>" alt="">                    

                </div>

                <?php $i++; }?>



            </div>

            <a class="left carousel-control" href="#myCarousel" data-slide="prev">

                <span class="fa fa-chevron-left icon-prev"></span>

            </a>

            <a class="right carousel-control" href="#myCarousel" data-slide="next">

                <span class="fa fa-chevron-right icon-next"></span>

            </a>

        </div>

        <!-- IMAGE SLIDER ENDS -->

        <div style="clear:both;border-top:20px solid #fff;"></div>

        <div class="panel ">



            <!-- List group -->



        </div>

        <?php $detail_link = site_url('property/'.$row->unique_id.'/'.url_title($estate_title));; ?>

        <div class="share-networks clearfix">

                <div class="col-md-3 col-sm-2 col-xs-12 share-label"><i class="fa fa-share fa-lg"></i> <?php echo lang_key('share_this'); ?>:</div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option fb"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-facebook fa-lg"></i>Facebook</a></div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option twitter"><a href="https://twitter.com/share?url=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-twitter fa-lg"></i>Twitter</a></div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option gplus"><a  href="https://plus.google.com/share?url=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-google-plus fa-lg"></i>Google</a></div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option gplus"><a  href="javascript:window.print()"><i class="fa fa-print fa-lg"></i>Print</a></div>

        </div>

        <!--DESCRIPTION STARTS -->

        <div class="" id="panel">

            <ul class="list-group property-meta-list">

                <li class="list-group-item">

                    <div class="row">

                        <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><i class="fa fa-building"></i> <?php echo lang_key($row->type);?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-arrows-alt "></i> <?php echo$row->home_size.' '.show_square_unit($row->home_size_unit);?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><img src="<?php echo theme_url() ?>/assets/images/icons/bedrooms_fa2.png"> <?php echo $row->bedroom;?> <?php echo lang_key('bedrooms'); ?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span><img src="<?php echo theme_url() ?>/assets/images/icons/bathrooms_fa2.png"> <?php echo $row->bath;?> <?php echo lang_key('baths'); ?></span></div>

                    </div>

                </li>	<!-- list-group-item -->



                <li class="list-group-item">

                    <div class="row">

                        <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><i class="fa fa-briefcase"></i> <?php echo lang_key($row->purpose) ;?></span></div>

                        <?php $rent_price = ($row->rent_price>0)?$row->rent_price:'N/A';$rent_price_unit = ($row->rent_price_unit!='')?$row->rent_price_unit:'N/A';?>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-money"></i> <?php echo $this->session->userdata('system_currency').$row->total_price;?> <?php echo ($row->purpose=='DBC_PURPOSE_RENT')? lang_key($row->rent_price_unit):''; ?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-ticket"></i> <?php echo lang_key('status'); ?>: <?php echo lang_key($row->estate_condition);?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span><i class="fa fa-clock-o"></i> <?php echo lang_key('year_built'); ?>: <?php echo $row->year_built;?></span></div>

                    </div>

                </li>	<!-- list-group-item -->



<!--                <li class="list-group-item">-->

<!--                    <div class="row">-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><i class="javo-con status"></i>Home size: --><?php //echo get_location_name_by_id($row->city);?><!--</span></div>-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="javo-con price"></i>Lot size: --><?php //echo get_location_name_by_id($row->state);?><!--</span></div>-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="javo-con parking"></i>Country: --><?php //echo get_location_name_by_id($row->country);?><!--</span></div>-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span><i class="javo-con location"></i>Zip: --><?php //echo $row->zip_code;?><!--</span></div>-->

<!--                    </div>-->

<!--                </li>-->

<!---->

<!--                <li class="list-group-item">-->

<!--                    <div class="row">-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><i class="javo-con status"></i>City: --><?php //echo$row->home_size.' '.$row->home_size_unit;?><!--</span></div>-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="javo-con price"></i>Sate: --><?php //echo $row->lot_size.' '.$row->lot_size_unit;?><!--</span></div>-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="javo-con parking"></i>Address: --><?php //echo $row->address;?><!--</span></div>-->

<!--                        <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span><i class="javo-con location"></i>Condition: --><?php //echo $row->estate_condition;?><!--</span></div>-->

<!--                    </div>-->

<!--                </li>-->

            </ul>



            <div class="title"><i class="fa fa-tags fa-4"></i>&nbsp;<?php echo lang_key('Tags'); ?></div>

            <div class="panel-body tags-panel">
                <?php $tags = get_post_meta($row->id,'tags');
                      $tags = explode(',',$tags);
                      foreach ($tags as $tag) {
                          echo '<span class="label label-primary"><i class="fa fa-tags"></i> <a href="'.site_url('show/tag/'.$tag).'">'.$tag.'&nbsp;</a></span>';
                      }
                ?>
            </div>

            <div class="title"><i class="fa fa-home fa-4"></i>&nbsp;<?php echo lang_key('description'); ?></div>

            <div class="panel-body">

                <?php echo get_description_for_edit_by_id_lang($row->id,$curr_lang);?>

            </div>



            <div class="title"><i class="fa fa-rocket fa-4"></i>&nbsp;<?php echo lang_key('general_amenities'); ?></div>

            <div class="panel-body">

                <div class="row property-amenities">

                    <ul class="">

                        <?php $checked_facilities = ($row->facilities!='false')?json_decode($row->facilities):array();?>

                        <?php $facilities = get_all_facilities();?>

                        <?php foreach ($facilities->result() as $facility) {

                                $class = (in_array($facility->id,$checked_facilities))?'checked':'cross';

                            ?>

                                <li class="<?php echo $class;?> col-md-4 col-sm-4"><img style="width:16px;margin-right:5px;" src="<?php echo base_url('uploads/thumbs/'.$facility->icon);?>"/><?php echo $facility->title;?></li>

                            <?php

                        }?>

                    </ul><!-- /.span2 -->                   



                </div>

            </div>





        </div>

        <!--DESCRIPTION ENDS -->

        <div style="clear:both;margin-top:10px;"></div>

        <div class="orange-border panel panel-primary">

            <div class="panel-heading orange"><i class="fa fa-map-marker"></i> <?php echo lang_key('location_map'); ?></div>

            <div class="panel-body">

                <div id="details-map" style="width: 100%; height: 300px;"></div>

<!--                <div class="clearfix"></div>-->

<!--                <div style="margin-top: 3px">-->

<!--                    <input type="text" name="from_route" placeholder="Type your address" value="" class="form-control input-sm col-md-4" style="width: 33.3333%" id="from_route">-->

<!--                    <div style="float: left" class="btn-group btn-group-sm"><a id="route_from_button" class="btn btn-warning btn-sm" href="javascript:void(0)" >Show route</a></div>-->

<!--                    <p style="float: left; padding: 3px 0 0 5px;">(If kept blank system will use the current location.)</p>-->

<!--                </div>-->

            </div>

        </div>



        <div style="clear:both;margin-top:10px;"></div>

        <div class="orange-border panel panel-primary">

            <div class="panel-heading orange"><i class="fa fa-image"></i> <?php echo lang_key('image_gallery'); ?></div>

            <div class="panel-body">

                <?php $images = ($row->gallery!='')?json_decode($row->gallery):array();?>

                <?php foreach($images as $img){?>

                <div class="images-box col-sm-3 col-md-3">

                    <a href="<?php echo base_url('uploads/gallery/'.$img);?>" data-lightbox="detail-gallery" class="gallery-images">

                        <img width="270" height="197" alt="" src="<?php echo base_url('uploads/gallery/'.$img);?>">

                        <span class="bg-images"><i class="fa fa-search"></i></span>

                    </a>

                </div>

                <?php }?>

            </div>

        </div>



        <?php if(get_post_meta($row->id,'video_url')!='n/a'){?>

        <div style="clear:both;margin-top:10px;"></div>

        <div class="orange-border panel panel-primary">

            <div class="panel-heading orange"><i class="fa fa-image"></i> <?php echo lang_key('featured_video'); ?></div>

            <div class="panel-body">

                <span id="video_preview"></span>

                <input type="hidden" name="video_url" id="video_url" value="<?php echo get_post_meta($row->id,'video_url');?>">

            </div>

        </div>

        <?php }?>



        </div> <!-- col-md-9 -->




    <!--div class="new-page"></div-->
    <!--DETAILS SUMMARY-->

    <div class="col-md-3 col-sm-3">

        <h1 class="detail-title"><i class="fa fa-star-o"></i>&nbsp;<?php echo lang_key('summary'); ?></h1>

        <div class="orange-border panel panel-primary effect-helix in">

            <div class="panel-heading orange"><?php echo lang_key('overview'); ?></div>

            <div class="panel-body">

                <div class="info_list">

                <div class="property-header">

                    <img class="property-header-image" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" alt="" style="width:256px">

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

                                <span class="property-price"><?php echo $this->session->userdata('system_currency').$row->total_price;?></span>

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

            </div>

        </div>



        <div class="widget our-agents" id="agents_widget-2">



            <h2 class="detail-title"><i class="fa fa-user"></i>&nbsp;<?php echo lang_key('agent'); ?></h2>



            <div class="content">

                <div class="agent clearfix">

                    <div class="image">

                        <a href="<?php echo site_url('show/agentproperties/'.$row->created_by);?>">

                            <img width="140" height="141" alt="john-small" class="attachment-post-thumbnail wp-post-image" src="<?php echo get_profile_photo_by_id($row->created_by,'thumb');?>">

                        </a>

                    </div>

                    <div class="name">

                        <a href="<?php echo site_url('show/agentproperties/'.$row->created_by);?>"><?php echo get_user_fullname_by_id($row->created_by);?></a>

                    </div>

                    <div class="phone"><?php echo get_user_meta($row->created_by,'phone');?></div>

                    <div class="email"><a href="mailto:<?php echo get_user_email_by_id($row->created_by);?>"><?php echo get_user_email_by_id($row->created_by);?></a>

                    <div class="agent-properties"><a href="<?php echo site_url('show/agentproperties/'.$row->created_by);?>"><?php echo get_user_properties_count($row->created_by);?> Properties</a></div>

                    </div>

                </div><!-- /.agent -->



            </div><!-- /.content -->

        </div>



        <h2 class="detail-title no-print"><i class="fa fa-envelope-o"></i>&nbsp;<?php echo lang_key('message'); ?></h2>

        <form class="no-print" action="<?php echo site_url('show/sendemailtoagent/'.$row->created_by);?>" method="post" id="message-form">

            <?php echo $this->session->flashdata('msg');?>

            <input type="hidden" name="unique_id" value="<?php echo $row->unique_id;?>">

            <input type="hidden" name="title" value="<?php echo url_title($estate_title);?>">

            <label><?php echo lang_key('name');?>:</label>

            <input type="text" name="sender_name" value="<?php echo set_value('sender_name');?>" class="form-control">

            <?php echo form_error('sender_name');?>

            <label>Email:</label>

            <input type="text" name="sender_email" value="<?php echo set_value('sender_email');?>" class="form-control">

            <?php echo form_error('sender_email');?>

            <label><?php echo lang_key('email_subject');?>:</label>

            <input type="text" name="subject" value="<?php echo set_value('subject');?>" class="form-control">

            <?php echo form_error('subject');?>

            <label><?php echo lang_key('message'); ?>:</label>

            <textarea name="msg" class="form-control"><?php echo set_value('msg');?></textarea>

            <?php echo form_error('msg');?>

            <div style="clear:both;margin-top:10px"></div>

            <input type="submit" class="btn btn-warning" value="send">

        </form>

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

    //$('#myCarousel').carousel();

    jQuery('#video_url').change(function(){

          var url = jQuery(this).val();

          showVideoPreview(url);

        }).change();

    // window.print();
    // window.close();
});
</script>

<?php
}
?>