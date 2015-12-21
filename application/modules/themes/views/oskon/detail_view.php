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

     if($row->purpose=='DBC_PURPOSE_SALE'){
        if($row->estate_condition == 'DBC_CONDITION_SOLD'){
             $property_status = lang_key('DBC_CONDITION_SOLD');
             $purpose_class = 'sold';
        }
        elseif($row->estate_condition=='DBC_CONDITION_RENTED'){
            $property_status = lang_key('DBC_CONDITION_RENTED');
            $purpose_class = 'rented';
        }
        else{
            $property_status = lang_key('DBC_PURPOSE_SALE');
            $purpose_class = 'sale';
        }
    }else if($row->purpose=='DBC_PURPOSE_RENT'){
        if($row->estate_condition == 'DBC_CONDITION_SOLD'){
            $property_status = lang_key('DBC_CONDITION_SOLD');
            $purpose_class = 'sold';
        }
        elseif($row->estate_condition=='DBC_CONDITION_RENTED'){
            $property_status = lang_key('DBC_CONDITION_RENTED');
            $purpose_class = 'rented';
        }
        else{
            $property_status = lang_key('DBC_PURPOSE_RENT');
            $purpose_class = 'rent';
        }
    }else if($row->purpose=='DBC_PURPOSE_BOTH'){
        $property_status = lang_key('DBC_PURPOSE_BOTH');
        $purpose_class = 'both';
    }
    
   
    
    if($row->category_type=='DBC_TYPE_LAND' || $row->category_type=='DBC_TYPE_COMMERCIAL'){
        $estate_size = (int)$row->lot_size;
        $estate_size_unit = lang_key(show_square_unit($row->lot_size_unit));
    }else{
        $estate_size = (int)$row->home_size;
        $estate_size_unit = lang_key(show_square_unit($row->home_size_unit));
    }

    if($row->category_type=='DBC_TYPE_RESIDENTIAL' || ($row->category_type=='DBC_TYPE_COMMERCIAL' && $row->type =='DBC_TYPE_OFFICE') ){
        $estate_bathroom = $row->bath;
        $estate_bathroom_label = lang_key('Baths');
    }
    else{
        $estate_bathroom = '';
        $estate_bathroom_label = '';
    }

    if($row->category_type=='DBC_TYPE_RESIDENTIAL'){
         $estate_bedroom = $row->bedroom;
         $estate_bedroom_label = lang_key('Beds');
    }
    else{
        $estate_bedroom = '';
        $estate_bedroom_label = '';
    }
    
    $estate_type = $row->type;
    $estate_type_label = lang_key('Type');
    $estate_type_lang = lang_key($row->type);

    $property_address_short = lang_key(get_location_name_by_id($row->city)).', '.lang_key(get_location_name_by_id($row->state)).', '.lang_key(get_location_name_by_id($row->country));
    $property_price = number_format($row->total_price);
    $detail_link = site_url('property/'.$row->unique_id.'/'.dbc_url_title(character_limiter($estate_title,20)));
    
    $estate_category_type= $row->category_type;
    $estate_area_label = lang_key('area');
    
    if ($isAgent == 1){
         $agentlbl = lang_key('agent');
    }
    else{
       $agentlbl = lang_key('User');
     }
   
    
?>
    
<?php $curr_lang = ($this->uri->segment(1) != '') ? $this->uri->segment(1) : 'en';?>
<?php if ($curr_lang == 'en'):?>
    <?php $styleL = 'left'?>
    <?php $styleR = 'right'?>
    <?php $windowCss1 = '15px'?>
    <?php $windowCss2 = '115px'?>
    <?php $rtl = false;?>
<?php else:?>
    <?php $styleL = 'right'?>
    <?php $styleR = 'left'?>
    <?php $windowCss1 = '-15px'?>
    <?php $windowCss2 = '145px'?>
    <?php $rtl = true;?>
<?php endif;?>

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
                zoom: 18,
                center: myLatlng,
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                    position: google.maps.ControlPosition.TOP_LEFT
                },
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                scaleControl: true,
                streetViewControl: true,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.TOP_RIGHT
                }
            }

            var map = new google.maps.Map(document.getElementById('details-map'), mapOptions);
                        
                        
            var contentString  = '<div id="iw-container">' +
                                    '<div class="thumbnail thumb-shadow no_margin-bottom">'+
                                        '<div class="property-header">'+
                                            '<a target="_blank" href="<?php echo $detail_link;?>"></a>'+
                                            '<img class="property-header-image" src="<?php echo $featured_image_path;?>" alt="<?php echo $estate_title; ?>" style="width:225px; height:185px;">'+
                                            '<span class="property-contract-type <?php echo $purpose_class; ?>"><span><?php echo $property_status;?></span>'+
                                            '</span>'+
                                            '<div class="property-thumb-meta">'+
                                                '<span class="property-price"><?php echo show_price($row->total_price,$row->id);?></span>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="caption">'+
                                            '<h2 class="estate-title-car" style="height: 25px;"><?php echo $estate_title; ?></h2>'+
                                            '<p class="estate-description-car" style="height: 18px;"><i class="fa fa-map-marker OS_map-icon"></i><?php echo $property_address_short; ?></p>'+
                                            '<div class="OS_pro_div" style="clear:both;">'+
                                                '<span class="rtl-left" style="float:<?php echo $styleL;?>; font-weight:bold;"><?php echo $estate_type_label ;?>:</span>'+
                                                '<span class="OS_pad-l" style="float:<?php echo $styleL;?>; "><?php echo $estate_type_lang;?></span>'+
                                            '</div>'+
                                            '<div class="OS_pro_div" style="clear:both;">'+
                                                '<span class="rtl-right" style="float:<?php echo $styleL;?>; font-weight:bold;"><?php echo $estate_area_label;?>:</span>'+
                                                '<span class="OS_pad-l" style="float:<?php echo $styleL;?>; "> <?php echo $estate_size;?> <?php echo $estate_size_unit;?></span>'+
                                            '</div>'+
                                            '<div style="clear:both;" class="property-utilities OS_dit_dow">';

                                             <?php if($estate_category_type =='DBC_TYPE_RESIDENTIAL' || ($estate_category_type=='DBC_TYPE_COMMERCIAL' && $estate_category_type =='DBC_TYPE_OFFICE') ):?>
                                                 contentString +='<div title="Bathrooms" class="bathrooms OS_dit_dow-icon" style="padding-top: 0px;">'+
                                                                                '<div class="content"><?php echo $estate_bathroom ;?> <?php echo $estate_bathroom_label ;?></div>'+
                                                                        '</div>';
                                             <?php endif;?>
                                             <?php if($estate_category_type=='DBC_TYPE_RESIDENTIAL'):?>
                                                  contentString += '<div title="Bedrooms" class="bedrooms OS_dit_dow-icon" style="padding-top: 0px;">'+
                                                                                '<div class="content"><?php echo $estate_bedroom ;?> <?php echo $estate_bedroom_label ;?></div>'+
                                                                           '</div>';
                                             <?php endif;?>

                                             contentString +=  '<div class="clearfix"></div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="iw-bottom-gradient"></div>' +
                                   '</div>';

                        
                        

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            var marker, i;
            var markers = [];
            
            <?php if($purpose_class == 'sale'){ ?>
                var icon_path = iconBase + 'icon_sale.png';
            <?php } elseif($purpose_class == 'rent'){  ?>
                var icon_path = iconBase + 'icon_rent.png';
            <?php } elseif($purpose_class == 'sold'){  ?>
                var icon_path = iconBase + 'icon_sale.png';
            <?php } elseif($purpose_class == 'rented') { ?>
                var icon_path = iconBase + 'icon_rent.png';
            <?php } ?>

            marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: '<?php echo $estate_title; ?>',
                icon: icon_path
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                  infowindow.setContent(contentString);
                  infowindow.maxWidth = 225;
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
            
            
            // Event that closes the Info Window with a click on the map
            google.maps.event.addListener(map, 'click', function() {
              infowindow.close();
            });
            
            // custom windowInfo
            google.maps.event.addListener(infowindow, 'domready', function() {

            // Reference to the DIV that wraps the bottom of infowindow
            var iwOuter = $('.gm-style-iw');

            /* Since this div is in a position prior to .gm-div style-iw.
             * We use jQuery and create a iwBackground variable,
             * and took advantage of the existing reference .gm-style-iw for the previous div with .prev().
            */
            var iwBackground = iwOuter.prev();

            // Removes background shadow DIV
            iwBackground.children(':nth-child(2)').css({'display' : 'none'});

            // Removes white background DIV
            iwBackground.children(':nth-child(4)').css({'display' : 'none'});

            // Moves the infowindow 115px to the right.
            iwOuter.parent().parent().css({left: '<?php echo $windowCss1;?>'});

            // Moves the shadow of the arrow 76px to the left margin.
            iwBackground.children(':nth-child(1)').attr('style', function(i,s){ return s + 'left: <?php echo $windowCss2;?> !important;'});

            // Moves the arrow 76px to the left margin.
            iwBackground.children(':nth-child(3)').attr('style', function(i,s){ return s + 'left: <?php echo $windowCss2;?> !important;'});

            // Changes the desired tail shadow color.
            iwBackground.children(':nth-child(3)').find('div').children().css({'box-shadow': '0 1px 6px rgba(178, 178, 178, 0.6)','background-color' : '#F3F3F3' ,'z-index' : '1'});

            // Reference to the div that groups the close button elements.
            var iwCloseBtn = iwOuter.next();

            // Apply the desired effect to the close button
            iwCloseBtn.css({opacity: '1', <?php echo $styleR;?>: '44px', top: '19px'});

            // If the content of infowindow not exceed the set maximum height, then the gradient is removed.
            if($('.iw-content').height() < 140){
              $('.iw-bottom-gradient').css({display: 'none'});
            }

            // The API automatically applies 0.7 opacity to the button after the mouseout event. This function reverses this event to the desired value.
            iwCloseBtn.mouseout(function(){
              $(this).css({opacity: '1'});
            });
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
        <h1 class="recent-grid">
            <span class="OS_icon_f"><i class="fa fa-home fa-4"></i></span>&nbsp;<?php echo $estate_title;?>
        </h1>
        
        <?php $i=0; $images = ($row->gallery!='')?json_decode($row->gallery):array();?>
        <?php $image_count_flag = count($images);?>
        <!-- main slider-->
        <?php if(count($images)>0 && $images[0]!=''){?>
             <div id="slider" class="flexslider">
                 <span class="property-contract-type <?php echo $purpose_class; ?>"><span><?php echo $property_status;?></span></span>
                 <div class="property-thumb-meta"><span class="property-price"><?php echo show_price($row->total_price,$row->id);?></span></div>
                 <ul class="slides">
                    <li><img style="width:840px;height:440px;" src="<?php echo base_url('uploads/original/'.$row->featured_img); ?>"/></li>
                    <?php foreach ($images as $img) { ?>
                        <li><img style="width:840px;height:440px;" src="<?php echo base_url('uploads/gallery/' . $img); ?>"/></li>
                        <?php $i++;
                     }?>
                 </ul>
            </div>
            <div style="clear:both; width: 100%; height: 0px"></div>
        <?php }
            else{?>
                <div id="slider" class="flexslider">
                    <span class="property-contract-type <?php echo $purpose_class; ?>"><span><?php echo $property_status;?></span></span>
                    <div class="property-thumb-meta"><span class="property-price"><?php echo show_price($row->total_price,$row->id);?></span></div>
                    <ul class="slides">
                        <li><img style="width:840px;height:440px;" src="<?php echo base_url('uploads/original/'.$row->featured_img); ?>"/></li>
                    </ul>
                </div>
                <div style="clear:both; width: 100%; height: 0px"></div>
         <?php }?>
            
        <!-- thumb slider slider-->
        <?php $images = ($row->gallery!='')?json_decode($row->gallery):array();?>
        <?php if(count($images)>0 && $images[0]!=''){?>
            <div style="clear:both;margin-top:0px;"></div>
            <div id="carousel" class="flexslider">
                <ul class="slides">
                    <li style="<?php echo $rtl;?>"><img style="width: 155px;height: 100px;" src="<?php echo base_url('uploads/original/'.$row->featured_img); ?>" /></li>
                    <?php foreach($images as $img){?>
                        <li><img style="width: 155px;height: 100px;" src="<?php echo base_url('uploads/gallery/'.$img);?>" /></li>
                    <?php }?>
                </ul>
            </div>
        <?php }
            else{?>
                <div style="clear:both;margin-top:0px;"></div>
                <div id="carousel" class="flexslider">
                    <ul class="slides">
                        <li><img style="width: 155px;height: 100px;" src="<?php echo base_url('uploads/original/'.$row->featured_img); ?>" /></li>
                    </ul>
                </div>
        <?php }?>
            
       
        <div id="panel">
            <!-- top items -->
            <ul class="list-group property-meta-list">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><i class="fa fa-briefcase"></i> <?php echo lang_key($row->purpose) ;?></span></div>
                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-building"></i> <?php echo lang_key($row->type);?></span></div>
                        <?php if($row->category_type=='DBC_TYPE_LAND' || $row->category_type=='DBC_TYPE_COMMERCIAL'){?>
                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-arrows-alt "></i> <?php echo (int)$row->lot_size.' '.  lang_key(show_square_unit($row->lot_size_unit));?></span></div>
                        <?php }else{?>
                                <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-arrows-alt "></i> <?php echo (int)$row->home_size.' '. lang_key(show_square_unit($row->home_size_unit));?></span></div>
                        <?php }?>
                        <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span><i class="fa fa-money"></i> <?php echo show_price($row->total_price,$row->id);?> <?php echo ($row->purpose=='DBC_PURPOSE_RENT')? lang_key($row->rent_price_unit):''; ?></span></div>
                    </div>
                </li>	<!-- list-group-item -->
                <li class="list-group-item">
                    <div class="row">
                         <?php if ($row->category_type=='DBC_TYPE_RESIDENTIAL'){?>
                                <div class="col-md-3 col-sm-3 col-xs-5 titles first"><span><img alt="bedroom" src="<?php echo theme_url() ?>/assets/images/icons/bedrooms_fa_new_color.png"> <?php echo $row->bedroom;?> <?php echo lang_key('Bedrooms'); ?></span></div>
                         <?php }?> 
                                
                         <?php if ( ($row->category_type=='DBC_TYPE_COMMERCIAL' && $row->type=='DBC_TYPE_OFFICE') || $row->category_type=='DBC_TYPE_RESIDENTIAL' ){?>
                                <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><img alt="bathroom" src="<?php echo theme_url() ?>/assets/images/icons/bathrooms_fa2_new_color.png"> <?php echo $row->bath;?> <?php echo lang_key('Baths'); ?></span></div>
                         <?php }?>
                       
                        <div class="col-md-3 col-sm-3 col-xs-5 titles"><span><i class="fa fa-ticket"></i> <?php echo lang_key('status'); ?>: <?php echo lang_key($row->estate_condition);?></span></div>
                        <?php if($row->category_type != 'DBC_TYPE_LAND'){?>
                            <div class="col-md-3 col-sm-3 col-xs-5 titles last"><span> <?php if($row->year_built != '' && $row->year_built != 0):?>  <i class="fa fa-clock-o"></i>  <?php echo lang_key('year_built'); ?>: <?php echo $row->year_built;?> <?php endif;?></span></div>
                        <?php }?>
                        
                    </div>
                </li>	
            </ul>

            <!-- description -->
            <div class="title"><i class="fa fa-home fa-4"></i>&nbsp;<?php echo lang_key('description'); ?></div>
            <div class="panel-body">
                <?php echo get_description_for_edit_by_id_lang($row->id,$curr_lang);?>
            </div>
            
            
           <!-- information -->
           <div class="title"><i class="fa fa-info-circle"></i>&nbsp;<?php echo lang_key('Property Information'); ?></div>
           <div class="panel-body no_r_l_pad">
                <div class="fromrent"><?php echo lang_key('Property ID'); ?> : <span class="OS_bold"><?php echo $row->id;?></span></div>
                <?php $from_rent_date = get_post_meta($row->id,'from_rent_date'); ?>
                <?php $to_rent_date = get_post_meta($row->id,'to_rent_date'); ?>
                <?php if( ( $row->estate_condition=='DBC_CONDITION_RENTED' || $row->purpose=='DBC_PURPOSE_RENT') && ($from_rent_date != 'n/a' && $from_rent_date != '' && $to_rent_date != 'n/a' && $to_rent_date != '') ):?>
                    <div class="fromrent"><span><?php echo lang_key('from');?> : </span> <span class="OS_bold"><?php echo $from_rent_date;?></span></div>
                    <div class="fromrent"><?php echo lang_key('to');?> : <span class="OS_bold"> <?php echo $to_rent_date;?></span></div>
                <?php endif;?>
               
                <div class="fromrent"><?php echo lang_key('address'); ?> : <span class="OS_bold"><?php echo $row->address;?></span></div>
                <div class="fromrent"><?php echo lang_key('city'); ?> :  <span class="OS_bold"><?php echo lang_key(get_location_name_by_id($row->city));?></span></div>
                <div class="fromrent"><?php echo lang_key('state_province'); ?> : <span class="OS_bold"><?php echo lang_key(get_location_name_by_id($row->state));?></span></div>
                <div class="fromrent"><?php echo lang_key('country'); ?> : <span class="OS_bold"> <?php echo lang_key(get_location_name_by_id($row->country));?></span></div>
                
                <?php
                 $this->config->load('realcon');
                 $enable_custom_fields = $this->config->item('enable_custom_fields');
                 if($enable_custom_fields=='Yes'){?>
                <?php 
                    $fields = $this->config->item('custom_fields');
                    foreach ($fields as $field) {?>
                        <?php if ($row->category_type == 'DBC_TYPE_LAND' && ($field['title'] == 'Furnished' || $field['title'] == 'Floor No') ):?>
                            <?php continue;?>
                        <?php endif;?>
                        <?php if (get_post_custom_value($row->id,'custom_values',$field['name']) != '' && get_post_custom_value($row->id,'custom_values',$field['name']) != 'n/a'):?>
                            <div class="fromrent"><?php echo lang_key($field['title']);?>: <span class="OS_bold"><?php echo lang_key(get_post_custom_value($row->id,'custom_values',$field['name'],'N/A'));?></span></div>
                        <?php endif;?>
                    <?php }
                 ?>
                 <?php }?>
                        
                <?php if(get_post_meta($row->id,'estate_brochure')!='n/a'){?>
                    <div style="margin-top: 15px">
                        <a class="OS_a-downlaod" href="<?php echo base_url('uploads/gallery/'.get_post_meta($row->id,'estate_brochure'));?>" target="_blank"><?php echo lang_key('Click here to download our estate brochure');?></a>
                    </div>
                <?php } ?> 
            </div>
            
            <?php $checked_facilities = ($row->facilities!='false')?json_decode($row->facilities):array();?>
            <?php if (count($checked_facilities) > 0):?>
                <div class="title"><i class="fa fa-rocket fa-4"></i>&nbsp;<?php echo lang_key('general_amenities'); ?></div>
                <div class="panel-body">
                    <div class="row property-amenities">
                        <ul class="">
                            <?php $facilities = get_all_facilities();?>
                            <?php foreach ($facilities->result() as $facility) {
                                    $class = (in_array($facility->id,$checked_facilities))?'checked':'cross';
                                    if($class=='cross')
                                        continue;
                                ?>
                            <li class="<?php echo $class;?> col-md-4"><img alt="<?php echo $facility->title;?>" class="img-amenities" src="<?php echo base_url('uploads/thumbs/'.$facility->icon);?>"/><?php echo lang_key($facility->title);?></li>
                                <?php
                            }?>
                        </ul><!-- /.span2 -->
                    </div>
                </div>
            <?php endif;?>
          
             <!-- Tags -->
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
            
            
            <div class="title"><i class="fa fa fa-user fa-4"></i>&nbsp;<?php echo $agentlbl; ?></div>
            <div class="panel-body">
            <div class="agent-container" id="panel">
                <div class="agent-holder clearfix">
                    <div class="agent-image-holder">
                        <?php  if ($isAgent == 1):?>
                            <a href="<?php echo site_url('show/agentproperties/' .  $row->created_by); ?>">
                                <img width="150" height="150" src="<?php echo get_profile_photo_by_id( $row->created_by, 'thumb'); ?>">
                            </a>
                        <?php else:?>
                                <img width="150" height="150" src="<?php echo get_profile_photo_by_id( $row->created_by, 'thumb'); ?>">
                        <?php endif;?>
                    </div>
                    <div class="detail">
                        <h4 class="OS_name1">
                            <?php  if ($isAgent == 1):?>
                                <a href="<?php echo site_url('show/agentproperties/' .  $row->created_by); ?>"><?php echo get_user_fullname_by_id($row->created_by); ?></a>
                            <?php else:?>
                               <?php echo get_user_fullname_by_id($row->created_by); ?>
                            <?php endif;?>
                        </h4>
                        <div class="OS_pro_no">
                             <?php  if ($isAgent == 1):?>
                                <a href="<?php echo site_url('show/agentproperties/' .  $row->created_by); ?>" style="color:#fff;"><?php echo get_user_properties_count( $row->created_by); ?> <?php echo lang_key('Properties'); ?></a>
                            <?php else:?>
                               <?php echo get_user_properties_count( $row->created_by); ?> <?php echo lang_key('Properties'); ?>
                            <?php endif;?>
                        </div>
                        <p class="OS_des_ag" id="aboutScrollDiv">
                            <?php $about_me = get_user_meta( $row->created_by, 'about_me', '');
                                  echo ($about_me != '') ? $about_me : ''; 
                              ?>
                        </p>
                    </div>
                    <div class="OS_ag_detail">
                        <p class="phone">
                          <?php 
                            if (get_user_meta($row->created_by, 'phone') != '' && get_user_meta($row->created_by, 'phone') != 'n/a'){
                                echo get_user_meta($row->created_by, 'phone');
                            }
                        ?>
                        </p>
                        <p class="email"><a href="mailto:<?php echo get_user_email_by_id($row->created_by);?>"><?php echo get_user_email_by_id($row->created_by);?></a></p>
                        <div class="follow-agent clearfix">
                            <ul class="social-networks clearfix">
                                <?php if (get_user_meta( $row->created_by, 'fb_profile') != 'n/a' && get_user_meta( $row->created_by, 'fb_profile') != '') { ?>
                                <li class="fb">
                                    <a href="<?php echo get_user_meta( $row->created_by, 'fb_profile'); ?>" target="_blank"><i class="fa fa-facebook fa-lg"></i></a>
                                </li>
                                <?php } ?>
                                <?php if (get_user_meta( $row->created_by, 'twitter_profile') != 'n/a' && get_user_meta( $row->created_by, 'twitter_profile') != '') { ?>
                                <li class="twitter">
                                    <a href="<?php echo get_user_meta( $row->created_by, 'twitter_profile'); ?>" target="_blank"><i class="fa fa-twitter fa-lg"></i></a>
                                </li>
                                <?php } ?>
                                <?php if (get_user_meta( $row->created_by, 'li_profile') != 'n/a' && get_user_meta( $row->created_by, 'li_profile') != '') { ?>
                                <li class="linkedin">
                                    <a href="<?php echo get_user_meta( $row->created_by, 'li_profile'); ?>" target="_blank"><i class="fa fa-linkedin fa-lg"></i></a>
                                </li>
                                <?php } ?>
                                <?php if (get_user_meta( $row->created_by, 'gp_profile') != 'n/a' && get_user_meta( $row->created_by, 'gp_profile') != '') { ?>
                                <li class="gplus">
                                    <a href="<?php echo get_user_meta( $row->created_by, 'gp_profile'); ?>" target="_blank"><i class="fa fa-google-plus fa-lg"></i></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        
        <div style="clear:both;margin-top:10px;"></div>
        
        <!-- MAP-->
        <div>
            <div class="title22"><i class="fa fa-map-marker"></i> <?php echo lang_key('location_map'); ?></div>
            <div class="panel-body">
                <input style="display: none;"  id="pac-input-details" class="controls" type="text" placeholder="Enter a location">
                <div id="details-map" style="width: 100%; height: 500px;"></div>
            </div>
        </div>

        <!-- Vedio-->
        <?php if(get_post_meta($row->id,'video_url')!='n/a' && get_post_meta($row->id,'video_url') !=''){?>
            <div style="clear:both;margin-top:10px;"></div>
            <div class="">
                <div class="title22  "><i class="fa fa-video-camera"></i> <?php echo lang_key('featured_video'); ?></div>
                <div class="panel-body">
                    <span id="video_preview"></span>
                    <input type="hidden" name="video_url" id="video_url" value="<?php echo get_post_meta($row->id,'video_url');?>">
                </div>
            </div>
        <?php }?>

           
        <!-- FB comments-->
        <?php
            if(get_settings('realestate_settings','enable_fb_comment','No')=='Yes'){
        ?>
            <!--facebook comment review start-->
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=<?php echo get_settings('realestate_settings','fb_comment_app_id','No');?>&version=v2.0";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            </script>
            <div style="clear:both;margin-top:10px;"></div>
            <div>
                <div class="panel-heading  "><i class="fa fa-image"></i> <?php echo lang_key('Review'); ?></div>
                <div class="panel-body" style="width: 100%; height: auto">
                    <div class="fb-comments" data-href=" <?php echo current_url();?>" data-numposts="10" data-colorscheme="light"></div>
                </div>
            </div>
            <!--facebook comment review end-->
            <?php 
        }
        ?>

        </div> <!-- col-md-9 -->

    <!--Left side-->
    <div class="col-md-3 col-sm-3">
        <?php render_widgets('right_bar_detail');?>
        <div class="widget our-agents" id="agents_widget-2">
            
            
            
            <h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-user"></i></span>&nbsp;<?php echo $agentlbl; ?></h1>
            <div class="content">
                <div class="agent clearfix">
                    <div class="image">
                        <?php if ($isAgent == 1):?>
                            <a href="<?php echo site_url('show/agentproperties/'.$row->created_by);?>">
                                <img width="140" height="141" alt="<?php echo get_user_fullname_by_id($row->created_by);?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo get_profile_photo_by_id($row->created_by,'thumb');?>">
                            </a>
                        <?php else:?>
                            <img width="140" height="141" alt="<?php echo get_user_fullname_by_id($row->created_by);?>" class="attachment-post-thumbnail wp-post-image" src="<?php echo get_profile_photo_by_id($row->created_by,'thumb');?>">
                        <?php endif;?>
                    </div>
                    <div class="OS_ag_dit">
                        <div class="name">
                            <?php if ($isAgent == 1):?>
                                <a href="<?php echo site_url('show/agentproperties/'.$row->created_by);?>"><?php echo get_user_fullname_by_id($row->created_by);?></a>
                            <?php else:?>
                                <?php echo get_user_fullname_by_id($row->created_by);?>
                            <?php endif;?>
                        </div>
                        
                        <div class="phone">
                            <?php if (get_user_meta($row->created_by,'phone') != 'n/a' && get_user_meta($row->created_by,'phone') != ''):?>
                                <?php echo get_user_meta($row->created_by,'phone');?>
                            <?php else:?>
                                &nbsp;
                            <?php endif?>
                        </div>
                        
                        <div class="email"><a href="mailto:<?php echo get_user_email_by_id($row->created_by);?>"><?php echo get_user_email_by_id($row->created_by);?></a>

                        </div>
                        <div class="agent-properties">
                            <?php if ($isAgent == 1):?>
                                <a href="<?php echo site_url('show/agentproperties/'.$row->created_by);?>"><?php echo get_user_properties_count($row->created_by);?> <?php echo lang_key('Properties'); ?></a>
                             <?php else:?>
                                <?php echo get_user_properties_count($row->created_by);?> <?php echo lang_key('Properties'); ?>
                             <?php endif;?>
                            
                        </div>
                    </div>
                    
                    <h1 class="detail-title"><i class="fa fa-envelope-o"></i>&nbsp;<?php echo lang_key('message'); ?></h1>
                    <form action="<?php echo site_url('show/sendemailtoagent/'.$row->created_by);?>" method="post" id="message-form">
                        <?php echo $this->session->flashdata('msg');?>
                        <input type="hidden" name="unique_id" value="<?php echo $row->unique_id;?>">
                        <input type="hidden" name="title" value="<?php echo url_title($estate_title);?>">
                        <label><?php echo lang_key('name');?>:</label>
                        <input type="text" name="sender_name" value="<?php echo set_value('sender_name');?>" class="form-control">
                        <?php echo form_error('sender_name');?>
                        <label><?php echo lang_key('Email')?>:</label>
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
                        <input type="submit" class="btn btn-warning" value="<?php echo lang_key('Send')?>">
                    </form>
                </div><!-- /.agent -->
            </div><!-- /.content -->
        </div>
        <div class="clearfix"></div>
    </div>
</div>


<!--<div id="embed-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><?php //echo lang_key('embed_preview'); ?>: </h4>
            </div>
            <div class="modal-body" style="min-height:450px">
            </div>
        </div>
    </div>
</div>                -->
<script type="text/javascript">
    function getUrlVars(url) {
        var vars = {};
        var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }

    function showVideoPreview(url){
      if(url.search("youtube.com")!=-1){
        var video_id = getUrlVars(url)["v"];
        //https://www.youtube.com/watch?v=jIL0ze6_GIY
        var src = '//www.youtube.com/embed/'+video_id;
        //var src  = url.replace("watch?v=","embed/");
        var code = '<iframe class="thumbnail" width="100%" height="450" src="'+src+'" frameborder="1" style="border:2px solid #F16A3D;" allowfullscreen></iframe>';
        jQuery('#video_preview').html(code);
      }
      else if(url.search("vimeo.com")!=-1){
        //http://vimeo.com/64547919
        var segments = url.split("/");
        var length = segments.length;
        length--;
        var video_id = segments[length];
        var src  = url.replace("vimeo.com","player.vimeo.com/video");
        var code = '<iframe class="thumbnail" src="//player.vimeo.com/video/'+video_id+'" width="100%" height="450" style="border:2px solid #F16A3D;" frameborder="1" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
        jQuery('#video_preview').html(code);
      }
      else{
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


    jQuery('#video_url').change(    function(){
          var url = jQuery(this).val();
          showVideoPreview(url);
    }).change();
  
  
    // The slider being synced must be initialized first
    $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: true,
        slideshow: true,
        itemWidth: 155,
        itemMargin: 5,
        asNavFor: '#slider',
        rtl:true
    });

    $('#slider').flexslider({
        animation: "fade",
        animationLoop: true,
        slideshow: true,
        sync: "#carousel",
        slideshowSpeed: 4000,         
        animationSpeed: 500,
        rtl:true
    });
  });
</script>
<?php
}
?>
<script type="text/javascript">stLight.options({publisher: "2ddc78f9-44b2-4159-9334-d4051a033172", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<script>
var options={ "publisher": "2ddc78f9-44b2-4159-9334-d4051a033172", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["facebook", "twitter", "linkedin", "googleplus", "whatsapp", "email"]}};
var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
</script>
<style type="text/css">
    .fb-comments, .fb-comments span, .fb-comments iframe { width: 100% !important; }
</style>
<script type="text/javascript">
     $('#aboutScrollDiv').slimScroll({
        allowPageScroll: true,
        height: '75px'
    });
</script>