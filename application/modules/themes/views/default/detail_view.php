<link rel="stylesheet" href="<?php echo theme_url();?>/assets/css/lightbox.min.css">
<script src="<?php echo theme_url();?>/assets/js/jquery.slides.min.js"></script>
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

    }
    elseif($row->estate_condition=='DBC_CONDITION_RENTED'){
        $property_status = 'DBC_CONDITION_RENTED';

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
    #pac-input-details {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 400px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
    }

    #pac-input-details:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
    }
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
    .gold{
        color: #FFC400;
    }
    .custom-fields{
        list-style: none;
        margin: 10px 0 10px 25px;
        padding: 0px;
    }

</style>

<script type="text/javascript">

    $(document).ready(function() {

        var iconBase = '<?php echo theme_url();?>/assets/images/map-icons/';

        var myLatitude = parseFloat('<?php echo $row->latitude; ?>');

        var myLongitude = parseFloat('<?php echo $row->longitude; ?>');

        function initialize() {


            var myLatlng = new google.maps.LatLng(myLatitude,myLongitude);

            var mapOptions = {

                zoom: 12,

                center: myLatlng

            }

            var map = new google.maps.Map(document.getElementById('details-map'), mapOptions);




            var contentString = '<div class="thumbnail thumb-shadow map-thumbnail">' + '<div class="property-header">'

                                + '<a href="#"></a>' + '<img class="property-header-image" src="<?php echo $featured_image_path;?>" alt="<?php echo $estate_title; ?>" style="width:100%">'
                             + '<div class="property-thumb-meta">' + '<span class="property-price"><?php echo show_price($row->total_price,$row->id);?></span>' + '</div></div>'
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
            var input = /** @type {HTMLInputElement} */(
                document.getElementById('pac-input-details'));
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);  // Why 17? Because it looks good.
                }
                marker.setIcon(/** @type {google.maps.Icon} */({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);

    });

</script>

<?php get_view_count($row->id,'detail');?>

<div class="row">

    <!-- Gallery , DETAILES DESCRIPTION-->

    <div class="col-md-9 col-sm-9">

        <h1 class="detail-title"><i class="fa fa-home fa-4"></i>&nbsp;<?php echo $estate_title;?></h1>


        <?php $i=0; $images = ($row->gallery!='')?json_decode($row->gallery):array();?>
        <?php $image_count_flag = count($images);
           
        ?>
        <?php if(count($images)>0 && $images[0]!=''){?>


            <div id="slides">
                <?php foreach ($images as $img) { ?>
                    <img src="<?php echo base_url('uploads/gallery/' . $img); ?>" alt="Gallery Image">
                    <?php $i++;
                } ?>
            </div>
            <div style="clear:both; width: 100%; height: 10px"></div>
        <?php } ?>

    

        <?php $detail_link = site_url('property/'.$row->unique_id.'/'.url_title($estate_title)); ?>

        <div class="share-networks clearfix">

                <div class="col-md-2 col-sm-2 col-xs-12 share-label"><i class="fa fa-share fa-lg"></i> <?php echo lang_key('Share'); ?>:</div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option fb"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-facebook fa-lg"></i>Facebook</a></div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option twitter"><a href="https://twitter.com/share?url=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-twitter fa-lg"></i>Twitter</a></div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option gplus"><a  href="https://plus.google.com/share?url=<?php echo $detail_link;?>" target="_blank"><i class="fa fa-google-plus fa-lg"></i>Google</a></div>

                <div class="col-md-2 col-sm-2 col-xs-4 share-option gplus"><a  href="<?php echo site_url('show/printview/'.$row->unique_id);?>" target="_blank"><i class="fa fa-print fa-lg"></i>Print</a></div>
                
                <div class="col-md-2 col-sm-2 col-xs-4 share-option gplus"><a  href="#" uniqid="<?php echo $row->unique_id;?>" class="show-embed-preview"><i class="fa fa-code fa-lg"></i>Embed</a></div>

        </div>

        <!--DESCRIPTION STARTS -->

        <div class="" id="panel">

            <ul class="list-group property-meta-list">

                <li class="list-group-item">

                    <div class="row">

                        <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><i class="fa fa-building"></i> <?php echo lang_key($row->type);?></span></div>

                        <?php if($row->type=='DBC_TYPE_LAND'){?>
                            <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-arrows-alt "></i> <?php echo (int)$row->lot_size.' '.show_square_unit($row->lot_size_unit);?></span></div>
                        <?php }else{?>
                            <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-arrows-alt "></i> <?php echo (int)$row->home_size.' '.show_square_unit($row->home_size_unit);?></span></div>
                        <?php }?>


                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><img alt="bedroom" src="<?php echo theme_url() ?>/assets/images/icons/bedrooms_fa2.png"> <?php echo $row->bedroom;?> <?php echo lang_key('bedrooms'); ?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span><img alt="bathroom" src="<?php echo theme_url() ?>/assets/images/icons/bathrooms_fa2.png"> <?php echo $row->bath;?> <?php echo lang_key('baths'); ?></span></div>

                    </div>

                </li>	<!-- list-group-item -->



                <li class="list-group-item">

                    <div class="row">

                        <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><i class="fa fa-briefcase"></i> <?php echo lang_key($row->purpose) ;?></span></div>

                        <?php $rent_price = ($row->rent_price>0)?$row->rent_price:'N/A';$rent_price_unit = ($row->rent_price_unit!='')?$row->rent_price_unit:'N/A';?>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-money"></i> <?php echo show_price($row->total_price,$row->id);?> <?php echo ($row->purpose=='DBC_PURPOSE_RENT')? lang_key($row->rent_price_unit):''; ?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-ticket"></i> <?php echo lang_key('status'); ?>: <?php echo lang_key($row->estate_condition);?></span></div>

                        <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span><i class="fa fa-clock-o"></i> <?php echo lang_key('year_built'); ?>: <?php echo $row->year_built;?></span></div>

                    </div>

                </li>	

            </ul>


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
                                
                                if($class=='cross')
                                    continue;
                            ?>

                                <li class="<?php echo $class;?> col-md-4"><img alt="<?php echo $facility->title;?>" style="width:16px;margin-right:5px;" src="<?php echo base_url('uploads/thumbs/'.$facility->icon);?>"/><?php echo $facility->title;?></li>

                            <?php

                        }?>

                    </ul><!-- /.span2 -->
                </div>
            </div>

            <?php if(get_settings('realestate_settings','enable_distance_fields','Yes')=='Yes'){?>

            <?php $distance_data = $distance_info; ?>
            <?php if(is_array($distance_data) && count($distance_data)>0){?>
            <div class="title"><i class="fa fa-road fa-4"></i>&nbsp;<?php echo lang_key('distance'); ?></div>

            <div class="panel-body">

                <div class="row property-amenities">

                    <ul class="">
                        <?php foreach($distance_data as $d){
                            ?>
                            <?php $distance =  json_decode($d);
                            $distance_field = json_decode(get_distance_info_by_id($distance->id));
                            if($distance->value!=''){
                            ?>
                            <li class="col-md-4"><img alt="<?php echo $distance_field->title;?>" style="width:16px;margin-right:5px;" src="<?php echo base_url('uploads/thumbs/'.$distance_field->icon);?>"/><?php echo $distance_field->title;?>: <?php echo ($distance->value!='')?$distance->value:'N/A';?> <?php echo ($distance->value!='')?$distance->units:'';?></li>
                        <?php
                            }} ?>
                    </ul>
                </div>
            </div>
            <?php }?>

            <?php }?>

            <?php $energy_efficiency = get_post_meta($row->id,'energy_efficiency',0); ?>
            <p class="details-title-head"><i class="fa fa-lightbulb-o fa-4"></i>&nbsp;<?php echo lang_key('energy_efficiency')?>: 
                <?php for($i=1; $i <= 5; $i++){ ?>
                    <i class="fa fa-star <?php echo ($i<=intval($energy_efficiency))?'gold':'';?>"></i> 
                <?php } ?>
            </p>

            <?php
            $this->config->load('realcon');
            $enable_custom_fields = $this->config->item('enable_custom_fields');
            if($enable_custom_fields=='Yes')
            {
            ?>
                <div class="title"><i class="fa fa-bullhorn fa-4"></i>&nbsp;<?php echo lang_key('others'); ?></div>
                <ul class="custom-fields">
                    <?php 
                    $fields = $this->config->item('custom_fields');
                    foreach ($fields as $field) {
                    ?>
                    <li><b><?php echo lang_key($field['title']);?>: </b><?php echo get_post_custom_value($row->id,'custom_values',$field['name'],'N/A');?></li>
                    <?php
                    }
                    ?>
                </ul>
                <div style="padding-bottom:10px;"></div>    
            <?php
            }
            ?>

            <?php $tags = get_post_meta($row->id,'tags'); ?>
            <?php if($tags != 'n/a' && $tags != ''){ ?>
            <div class="title"><i class="fa fa-tags fa-4"></i>&nbsp;<?php echo lang_key('tags'); ?></div>

            <div class="panel-body tags-panel">
                <?php
                $tags = explode(',',$tags);
                foreach ($tags as $tag) {
                    echo '<span class="label label-primary"><i class="fa fa-tags"></i> <a href="'.site_url('tags/'.$tag).'">'.$tag.'&nbsp;</a></span>';
                }
                ?>
            </div>
            <?php } ?>
        </div>

        <!--DESCRIPTION ENDS -->

        <div style="clear:both;margin-top:10px;"></div>

        <div class="orange-border panel panel-primary">

            <div class="panel-heading orange"><i class="fa fa-map-marker"></i> <?php echo lang_key('location_map'); ?></div>

            <div class="panel-body">
                <input id="pac-input-details" class="controls" type="text"
                       placeholder="Enter a location">
                <div id="details-map" style="width: 100%; height: 300px;"></div>

            </div>

        </div>


        <?php $images = ($row->gallery!='')?json_decode($row->gallery):array();?>
        <?php if(count($images)>0 && $images[0]!=''){?>
        <div style="clear:both;margin-top:10px;"></div>

        <div class="orange-border panel panel-primary">

            <div class="panel-heading orange"><i class="fa fa-image"></i> <?php echo lang_key('image_gallery'); ?></div>

            <div class="panel-body">

                

                <?php foreach($images as $img){?>

                <div class="images-box col-sm-3 col-md-3">

                    <a href="<?php echo base_url('uploads/gallery/'.$img);?>" data-lightbox="detail-gallery" class="gallery-images">

                        <img width="270" height="197" alt="Gallery Image" src="<?php echo base_url('uploads/gallery/'.$img);?>">

                        <span class="bg-images"><i class="fa fa-search"></i></span>

                    </a>

                </div>

                <?php }?>

            </div>

        </div>
        <?php }?>


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

        <?php
        if(get_settings('realestate_settings','enable_fb_comment','No')=='Yes'){
        ?>

        <!--facebook comment review start-->
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1510845559191569&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

        <div style="clear:both;margin-top:10px;"></div>

        <div class="orange-border panel panel-primary">

            <div class="panel-heading orange"><i class="fa fa-image"></i> <?php echo lang_key('Review'); ?></div>

            <div class="panel-body" style="width: 100%; height: auto">
                <div class="fb-comments" data-href=" <?php echo current_url();?>" data-numposts="10" data-colorscheme="light"></div>
            </div>
        </div>
        <!--facebook comment review end-->

        <?php 
        }
        ?>

        </div> <!-- col-md-9 -->





    <!--DETAILS SUMMARY-->

    <div class="col-md-3 col-sm-3">

        <h1 class="detail-title"><i class="fa fa-star-o"></i>&nbsp;<?php echo lang_key('summary'); ?></h1>

        <div class="orange-border panel panel-primary effect-helix in">

            <div class="panel-heading orange"><?php echo lang_key('overview'); ?></div>

            <div class="panel-body">

                <div class="info_list">

                <div class="property-header">

                    <img class="property-header-image" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" alt="<?php echo $estate_title;?>" style="width:256px">

                    <?php if($row->estate_condition=='DBC_CONDITION_SOLD'){?>
                            
                            <span class="property-contract-type sold"><span><?php echo lang_key('DBC_CONDITION_SOLD'); ?></span>
                            
                            <?php }elseif($row->estate_condition=='DBC_CONDITION_RENTED'){?>
                            
                            <span class="property-contract-type sold"><span><?php echo lang_key('DBC_CONDITION_RENTED'); ?></span>

                        <?php }elseif($row->purpose=='DBC_PURPOSE_SALE'){?>

                        <span class="property-contract-type sale"><span><?php echo lang_key('DBC_PURPOSE_SALE'); ?></span>

                            <?php }else if($row->purpose=='DBC_PURPOSE_RENT'){?>

                            <span class="property-contract-type rent"><span><?php echo lang_key('DBC_PURPOSE_RENT'); ?></span>

                                <?php }else if($row->purpose=='DBC_PURPOSE_BOTH'){?>

                                <span class="property-contract-type both"><span style="font-size: 11px"><?php echo lang_key('DBC_PURPOSE_BOTH'); ?></span>

                                    <?php }?>

                      </span>

                            <div class="property-thumb-meta">

                                <span class="property-price"><?php echo show_price($row->total_price,$row->id);?></span>

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

            </div>

        </div>



        <div class="widget our-agents" id="agents_widget-2">



            <h1 class="detail-title"><i class="fa fa-user"></i>&nbsp;<?php echo lang_key('agent'); ?></h1>



            <div class="content">

                <div class="agent clearfix">

                    <div class="image">

                        <a href="<?php echo site_url('show/agentproperties/'.$row->created_by);?>">

                            <img width="140" height="141" alt="<?php echo get_user_fullname_by_id($row->created_by);?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo get_profile_photo_by_id($row->created_by,'thumb');?>">

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


        <?php render_widgets('right_bar_detail');?>

        <h1 class="detail-title"><i class="fa fa-envelope-o"></i>&nbsp;<?php echo lang_key('message'); ?></h1>

        <form action="<?php echo site_url('show/sendemailtoagent/'.$row->created_by);?>" method="post" id="message-form">

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
            <?php echo (isset($question))?$question:'';?>
            <input type="text" name="ans" value="" style="width:60px" class="form-control">
            <?php echo form_error('ans');?>
            
            <div style="clear:both;margin-top:10px"></div>

            <input type="submit" class="btn btn-warning" value="send">

        </form>
        <div class="clearfix"></div>
        <?php if(get_post_meta($row->id,'estate_brochure')!='n/a'){?>
            <div style="text-align: center; margin-top: 10px">
                <a href="<?php echo base_url('uploads/gallery/'.get_post_meta($row->id,'estate_brochure'));?>" target="_blank"><img alt="brochure" src="<?php echo theme_url() ?>/assets/images/download-brochure.png"></a>
            </div>
        <?php } ?>
    </div>

</div>


<div id="embed-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h4 class="modal-title" id="myModalLabel"><?php echo lang_key('embed_preview'); ?>: </h4>

            </div>

            <div class="modal-body" style="min-height:450px">
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

    jQuery('.show-embed-preview').click(function(e){
        e.preventDefault();
        var uniqid = jQuery(this).attr('uniqid');
        jQuery('#embed-modal').modal('show');
        var code = '&lt;iframe src="<?php echo site_url("embed");?>/'+uniqid+'" style="border:0;width:300px;height:460px;"></iframe>';
        var content = '<label>width: <input type="text" class="form-control" id="width" value="300"/></label>'+
                      '<div style="clear:both;margin-top:10px;"></div>'+
                      '<label>Height: <input type="text" class="form-control" id="height" value="460"/></label>'+
                      '<div style="clear:both;margin-top:10px;"></div>'+
                      '<label>Code <spna style="font-weight:normal;font-size:12px;">(Copy and paste this code to your website)</span>: <div class="embed-code" style="font-weight:normal;border:1px solid #aaa;padding:3px;border-radius:3px">'+code+'</div></label>'+
                      '<div style="clear:both;margin-top:10px;margin-bottom:10px;border-bottom:1px solid;">Preview:</div>'+
                      '<iframe src="<?php echo site_url("embed");?>/'+uniqid+'" style="border:0;width:300px;height:460px;"></iframe>';
        jQuery('#embed-modal .modal-body').html(content);
        jQuery('#width').keyup(function(){
            jQuery('#embed-modal iframe').css('width',jQuery('#width').val());
            var ucode = '&lt;iframe src="<?php echo site_url("embed");?>/'+uniqid+'" style="border:0;width:'+jQuery('#width').val()+'px;height:'+jQuery('#height').val()+'px;"></iframe>';
            jQuery('.embed-code').html(ucode);
        });

        jQuery('#height').keyup(function(){
            jQuery('#embed-modal iframe').css('height',jQuery('#height').val());
            var ucode = '&lt;iframe src="<?php echo site_url("embed");?>/'+uniqid+'" style="border:0;width:'+jQuery('#width').val()+'px;height:'+jQuery('#height').val()+'px;"></iframe>';
            jQuery('.embed-code').html(ucode);
        });
    });

    $('#myCarousel').carousel();

    jQuery('#video_url').change(function(){

          var url = jQuery(this).val();

          showVideoPreview(url);

        }).change();
    var auto_play = true;
    <?php if($image_count_flag > 1){ ?>
    $('#slides').slidesjs({
        height: 500,
        play: {
            active: true,
            auto: auto_play,
            interval: 4000,
            swap: true
        },
        navigation: {
            effect: "fade"
        },
        pagination: {
            effect: "fade"
        },
        effect: {
            fade: {
                speed: 400
            }
        }
    });

    <?php }else{ ?>
        jQuery('#slides').show();
        jQuery('#slides > img').css('width','100%');
    <?php }?>
});

</script>

<?php

}

?>


<style type="text/css">
    .fb-comments, .fb-comments span, .fb-comments iframe { width: 100% !important; }
</style>