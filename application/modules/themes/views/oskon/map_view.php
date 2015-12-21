<style>
    #general-map-view img { max-width: none; }
    
</style>
<?php
    $map_id = (isset($map_id))?$map_id:'general-map-view';
    if($query->num_rows()<=0)
    {
        ?>
        <div class="alert alert-warning"><?php echo lang_key('no_estates_found'); ?></div>
        <?php
    }
    else
    {
        $data = array();
        $estates = array();
         
        
      
        
        foreach ($query->result() as $row)
        {
            if(get_settings('realestate_settings','hide_posts_if_expired','No')=='Yes')
            {
                  
                  $is_expired = is_user_package_expired($row->created_by);
                  if($is_expired){
                       continue;     
                  }
                                  
            }
                
            
            $title = get_title_for_edit_by_id_lang($row->id,$curr_lang);

            $estate = array();
            $estate['estate_id'] = $row->id;
            $estate['estate_category_type'] = $row->category_type;
            $estate['estate_area_label'] = lang_key('area');
            
            
            if($row->purpose=='DBC_PURPOSE_SALE'){
                if($row->estate_condition == 'DBC_CONDITION_SOLD'){
                    $estate['estate_type_class'] = 'sold';
                    $estate['estate_type_text'] = lang_key('DBC_CONDITION_SOLD');
                }
                elseif($row->estate_condition=='DBC_CONDITION_RENTED'){
                    $estate['estate_type_class'] = 'rented';
                    $estate['estate_type_text'] = lang_key('DBC_CONDITION_RENTED');
                }
                else{
                    $estate['estate_type_class'] = 'sale';
                    $estate['estate_type_text'] = lang_key('DBC_PURPOSE_SALE');
                }
            }else if($row->purpose=='DBC_PURPOSE_RENT'){
                
                if($row->estate_condition == 'DBC_CONDITION_SOLD'){
                    $estate['estate_type_class'] = 'sold';
                    $estate['estate_type_text'] = lang_key('DBC_CONDITION_SOLD');
                }
                elseif($row->estate_condition=='DBC_CONDITION_RENTED'){
                    $estate['estate_type_class'] = 'rented';
                    $estate['estate_type_text'] = lang_key('DBC_CONDITION_RENTED');
                }
                else{
                    $estate['estate_type_class'] = 'rent';
                    $estate['estate_type_text'] = lang_key('DBC_PURPOSE_RENT');
                }
            }else if($row->purpose=='DBC_PURPOSE_BOTH'){
                $estate['estate_type_class'] = 'both';
                $estate['estate_type_text'] = lang_key('DBC_PURPOSE_BOTH');
            }
            
            
            if($row->category_type=='DBC_TYPE_LAND' || $row->category_type=='DBC_TYPE_COMMERCIAL'){
                $estate['estate_size'] = (int)$row->lot_size;
                $estate['estate_size_unit'] = lang_key(show_square_unit($row->lot_size_unit));
            }else{
                $estate['estate_size'] = (int)$row->home_size;
                $estate['estate_size_unit'] = lang_key(show_square_unit($row->home_size_unit));
            }
            
            if($row->category_type=='DBC_TYPE_RESIDENTIAL' || ($row->category_type=='DBC_TYPE_COMMERCIAL' && $row->type =='DBC_TYPE_OFFICE') ){
                $estate['estate_bathroom'] = $row->bath;
                $estate['estate_bathroom_label'] = lang_key('Baths');
            }
            else{
                $estate['estate_bathroom'] = '';
                $estate['estate_bathroom_label'] = '';
            }
            
            if($row->category_type=='DBC_TYPE_RESIDENTIAL'){
                 $estate['estate_bedroom'] = $row->bedroom;
                 $estate['estate_bedroom_label'] = lang_key('Beds');
            }
            else{
                $estate['estate_bedroom'] = '';
                $estate['estate_bedroom_label'] = '';
            }
            
            
            $estate['estate_title'] =  character_limiter($title,20);
            $estate['featured_image_url'] = get_featured_photo_by_id($row->featured_img);
            $estate['latitude'] = $row->latitude;
            $estate['longitude'] = $row->longitude;
            $estate['estate_type'] = $row->type;
            $estate['estate_type_label'] = lang_key('Type');
            $estate['estate_type_lang'] = lang_key($row->type);
            $estate['estate_status'] = $row->status;
            $estate['estate_price'] = show_price($row->total_price);
            $estate['estate_short_address'] = lang_key(get_location_name_by_id($row->city)).', '.lang_key(get_location_name_by_id($row->state)).', '.lang_key(get_location_name_by_id($row->country));
            $estate['detail_link'] = site_url('property/'.$row->unique_id.'/'.dbc_url_title($title));
            array_push($estates,$estate);
        }
         
        //print_r($estates);
        $data['estates'] = $estates;
        
    }
?>
        
            
<?php $curr_lang = ($this->uri->segment(1) != '') ? $this->uri->segment(1) : 'en';?>
<?php if ($curr_lang == 'en'):?>
    <?php $styleL = 'left'?>
    <?php $styleR = 'right'?>
    <?php $windowCss1 = '15px'?>
    <?php $windowCss2 = '115px'?>
<?php else:?>
    <?php $styleL = 'right'?>
    <?php $styleR = 'left'?>
    <?php $windowCss1 = '-15px'?>
    <?php $windowCss2 = '145px'?>
<?php endif;?>
        
<style>
    #pac-input-<?php echo $map_id;?> {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 400px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
    }

    #pac-input-<?php echo $map_id;?>:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var map_data = jQuery.parseJSON('<?php echo json_encode($data); ?>');
        //console.log(map_data);

        var iconBase = '<?php echo theme_url();?>/assets/images/map-icons/';
        var zoomLevel = parseInt('<?php echo get_settings('banner_settings','map_zoom',11); ?>');
//        console.log(zoomLevel);
        function initialize() {
            
          // check if we found one estate so the center will be on this estate
          //otherwise we will get all estate results and get the center between them
          if (typeof map_data.estates == "undefined") {
               var map_result_count = 0;
          }
          else{
              var map_result_count = map_data.estates.length;
          }
          
          var myLatlng;
          if (map_result_count == 1){
                myLatlng = new google.maps.LatLng(map_data.estates[0].latitude,map_data.estates[0].longitude);
                zoomLevel = 17;
          }
          else if (map_result_count > 1){
             var avgLat = 0;
             var avgLong = 0;
             for (i = 0; i < map_result_count; i++) {
                avgLat = parseFloat(avgLat) + parseFloat(map_data.estates[i].latitude);
                avgLong = parseFloat(avgLong) + parseFloat(map_data.estates[i].longitude);
             }
             avgLat = avgLat / map_result_count;
             avgLong = avgLong / map_result_count;
             avgLat = Math.round(avgLat*1000000000)/1000000000;
             avgLong = Math.round(avgLong*1000000000)/1000000000;
             myLatlng = new google.maps.LatLng(avgLat,avgLong);
             
             <?php if(isset($search_empty)):?>
                var search_empty = '<?php echo $search_empty;?>';
             <?php else:?>
                    var search_empty = '-1';
             <?php endif;?>
                 
                 
             if (search_empty == '1'){
                 // search on empty value
                 zoomLevel = 8;
             }
             else{
                 
                 //if -1 this is from agent properties page
                 // we will make it zoomLevel = 8; coz agent maybe have a serveral estate in diffrent places
                 if (search_empty == '-1'){
                     zoomLevel = 8;
                 }
                 else{
                     //search on value (either plainKey or pusrpose sale)
                        if (map_result_count > 1 && map_result_count <= 10){
                           zoomLevel = 13;
                       }else if(map_result_count > 10 && map_result_count <= 20){
                           zoomLevel = 12;
                       }else if(map_result_count > 20 && map_result_count <= 30){
                           zoomLevel = 11;
                       }else if(map_result_count > 30 && map_result_count <= 40){
                           zoomLevel = 10;
                       }else if(map_result_count > 40 && map_result_count <= 50){
                           zoomLevel = 9;
                       }else{
                           // more than 60
                           zoomLevel = 8;
                       }
                 }
             }
             
             
             
          }
          else{
              myLatlng = new google.maps.LatLng(0,0);
          }
        
        
             console.log(zoomLevel);
        
            var mapOptions = {
                zoom: zoomLevel,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
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
            
            var map = new google.maps.Map(document.getElementById('<?php echo $map_id;?>'), mapOptions);

            var infowindow = new google.maps.InfoWindow({
                content: "Hello World"
            });
            
            var marker, i;
            var markers = [];
            var infoContentString = [];

            
             if (typeof map_data.estates != "undefined") {
                for (i = 0; i < map_data.estates.length; i++) {

                    if(map_data.estates[i].estate_type_class == 'sale'){
                        var icon_path = iconBase + 'icon_sale.png';
                    }
                    else if(map_data.estates[i].estate_type_class == 'rent'){
                        var icon_path = iconBase + 'icon_rent.png';
                    }
                    else if(map_data.estates[i].estate_type_class == 'sold'){
                        var icon_path = iconBase + 'icon_sale.png';
                    }
                    else if(map_data.estates[i].estate_type_class == 'rented'){
                        var icon_path = iconBase + 'icon_rent.png';
                    }

                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(map_data.estates[i].latitude, map_data.estates[i].longitude),
                        map: map,
                        title: map_data.estates[i].estate_title,
                        icon: icon_path
                    });

   
                   
                     infoContentString[i] = '<div id="iw-container">' +
                                                '<div class="thumbnail thumb-shadow no_margin-bottom">'+
                                                    '<div class="property-header">'+
                                                        '<a target="_blank" href="' + map_data.estates[i].detail_link + '"></a>'+
                                                        '<img class="property-header-image" src="' + map_data.estates[i].featured_image_url + '" alt="'+map_data.estates[i].estate_title+'" style="width:225px; height:185px;">'+
                                                        '<span class="property-contract-type '+map_data.estates[i].estate_type_class+'"><span>'+map_data.estates[i].estate_type_text+'</span>'+
                                                        '</span>'+
                                                        '<div class="property-thumb-meta">'+
                                                            '<span class="property-price">'+map_data.estates[i].estate_price+'</span>'+
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="caption">'+
                                                        '<h2 class="estate-title-car" style="height: 25px;">'+map_data.estates[i].estate_title+'</h2>'+
                                                        '<p class="estate-description-car" style="height: 18px;"><i class="fa fa-map-marker OS_map-icon"></i>' + map_data.estates[i].estate_short_address + '</p>'+
                                                        '<div class="OS_pro_div" style="clear:both;">'+
                                                            '<span class="rtl-left" style="float:<?php echo $styleL;?>; font-weight:bold;">'+map_data.estates[i].estate_type_label+':</span>'+
                                                            '<span class="OS_pad-l" style="float:<?php echo $styleL;?>; ">'+map_data.estates[i].estate_type_lang+'</span>'+
                                                        '</div>'+
                                                        '<div class="OS_pro_div" style="clear:both;">'+
                                                            '<span class="rtl-right" style="float:<?php echo $styleL;?>; font-weight:bold;">'+map_data.estates[i].estate_area_label+':</span>'+
                                                            '<span class="OS_pad-l" style="float:<?php echo $styleL;?>; ">'+map_data.estates[i].estate_size+' '+map_data.estates[i].estate_size_unit+'</span>'+
                                                        '</div>'+
                                                        '<div style="clear:both;" class="property-utilities OS_dit_dow">';
                                                
                                                         if(map_data.estates[i].estate_category_type=='DBC_TYPE_RESIDENTIAL' || (map_data.estates[i].estate_category_type=='DBC_TYPE_COMMERCIAL' && map_data.estates[i].estate_category_type =='DBC_TYPE_OFFICE') ){
                                                             infoContentString[i] +='<div title="Bathrooms" class="bathrooms OS_dit_dow-icon" style="padding-top: 0px;">'+
                                                                                            '<div class="content">'+map_data.estates[i].estate_bathroom+'&nbsp;'+map_data.estates[i].estate_bathroom_label+'</div>'+
                                                                                    '</div>';
                                                         }
                                                         if(map_data.estates[i].estate_category_type=='DBC_TYPE_RESIDENTIAL'){
                                                              infoContentString[i] += '<div title="Bedrooms" class="bedrooms OS_dit_dow-icon" style="padding-top: 0px;">'+
                                                                                            '<div class="content">'+map_data.estates[i].estate_bedroom+'&nbsp;'+map_data.estates[i].estate_bedroom_label+'</div>'+
                                                                                       '</div>';
                                                         }
                                                
                                                         infoContentString[i] +=  '<div class="clearfix"></div>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="iw-bottom-gradient"></div>' +
                                               '</div>';

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                          infowindow.setContent(infoContentString[i]);
                          infowindow.maxWidth = 225;
                          infowindow.open(map, marker);
                        }
                    })(marker, i));
                    markers.push(marker);
                    //infoContentString.push(contentString);
                }
            }
            
            
            
            
            //var markerCluster = new MarkerClusterer(map, markers);

            var input = /** @type {HTMLInputElement} */(
            document.getElementById('pac-input-<?php echo $map_id;?>'));
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
            
        if (typeof map_data.estates != "undefined") {
            google.maps.event.addDomListener(window, 'load', initialize);
        }
        
     });
</script>
<input style="display: none;" id="pac-input-<?php echo $map_id;?>" class="controls" type="text" placeholder="Enter a location">
<?php if (isset($alias) && ($alias == 'search_result')): ?>
    <div class="map-holder-OS"><div id="<?php echo $map_id;?>" class="map-view-holder map_result" style="width: 100%; height:680px;"></div></div>
<?php elseif (isset($alias) && ($alias == 'type' || $alias == 'agent')):?>
    <div><div id="<?php echo $map_id;?>" class="map-view-holder" style="width: 100%;height:700px;"></div></div>
<?php endif;?>




