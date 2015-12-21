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
                  if($is_expired)
                    continue;                    
            }
                
            $title = get_title_for_edit_by_id_lang($row->id,$curr_lang);

            $estate = array();
            $estate['estate_id'] = $row->id;
            $estate['estate_title'] =  $title;
            $estate['featured_image_url'] = get_featured_photo_by_id($row->featured_img);
            $estate['latitude'] = $row->latitude;
            $estate['longitude'] = $row->longitude;
            $estate['estate_type'] = $row->type;
            $estate['estate_type_lang'] = lang_key($row->type);
            $estate['estate_status'] = $row->status;
            $estate['estate_price'] = show_price($row->total_price);
            $estate['estate_short_address'] = get_location_name_by_id($row->city).','.get_location_name_by_id($row->state).','.get_location_name_by_id($row->country);
            $estate['detail_link'] = site_url('property/'.$row->unique_id.'/'.dbc_url_title($title));
            array_push($estates,$estate);
        }
         
        $data['estates'] = $estates;
    }
?>
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
        for (i = 0; i < map_data.estates.length; i++) {

        }

        var iconBase = '<?php echo theme_url();?>/assets/images/map-icons/';
        var zoomLevel = parseInt('<?php echo get_settings('banner_settings','map_zoom',8); ?>');
//        console.log(zoomLevel);
        function initialize() {
            var myLatlng = new google.maps.LatLng(map_data.estates[0].latitude,map_data.estates[0].longitude);
            var mapOptions = {
                zoom: zoomLevel,
                center: myLatlng
            }
            var map = new google.maps.Map(document.getElementById('<?php echo $map_id;?>'), mapOptions);

            var infowindow = new google.maps.InfoWindow({
                content: "Hello World"
            });


            var marker, i;
            var markers = [];
            var infoContentString = [];

            for (i = 0; i < map_data.estates.length; i++) {

                if(map_data.estates[i].estate_type == 'DBC_TYPE_COMSPACE'){
                    var icon_path = iconBase + 'office.png';
                }
                else if(map_data.estates[i].estate_type == 'DBC_TYPE_HOUSE' || map_data.estates[i].estate_type == 'DBC_TYPE_VILLA'){
                    var icon_path = iconBase + 'bighouse.png';
                }
                else if(map_data.estates[i].estate_type == 'DBC_TYPE_LAND'){
                    var icon_path = iconBase + 'land.png';
                }
                else {
                    var icon_path = iconBase + 'apartment.png';
                }


                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(map_data.estates[i].latitude, map_data.estates[i].longitude),
                    map: map,
                    title: map_data.estates[i].estate_title,
                    icon: icon_path
                });
                infoContentString[i] = '<div class="thumbnail thumb-shadow map-thumbnail">' + '<div class="property-header">'
                    + '<a href="' + map_data.estates[i].detail_link + '"></a>' + '<img class="property-header-image" src="' + map_data.estates[i].featured_image_url + '" alt="'+map_data.estates[i].estate_title+'" style="width:100%">'
                    + '<div class="property-thumb-meta">' + '<span class="property-price">' + map_data.estates[i].estate_price + '</span>' + '</div></div>'
                    + '<div class="caption">' + '<h4>'+ map_data.estates[i].estate_title + '</h4>' + '<p>' + map_data.estates[i].estate_short_address + '</p>' + '</div></div>';

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                      infowindow.setContent(infoContentString[i]);
                      infowindow.open(map, marker);
                    }
                })(marker, i));
                markers.push(marker);
//                infoContentString.push(contentString);
            }
            var markerCluster = new MarkerClusterer(map, markers);

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
<input id="pac-input-<?php echo $map_id;?>" class="controls" type="text"
       placeholder="Enter a location">
<div id="<?php echo $map_id;?>" class="map-view-holder" style="width: 100%; height: 900px;"></div>