<style>
    #slider-map img { max-width: none; }
    .controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    #pac-input {
        background-color: #fff;
        padding: 0 11px 0 13px;
        width: 400px;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        text-overflow: ellipsis;
    }

    #pac-input:focus {
        border-color: #4d90fe;
        margin-left: -1px;
        padding-left: 14px;  /* Regular padding-left + 1. */
        width: 401px;
    }

    .pac-container {
        font-family: Roboto;
    }

</style>
<?php $curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en'; ?>
<script type="text/javascript">
    $(document).ready(function() {

        var iconBase = '<?php echo theme_url();?>/assets/images/map-icons/';
        var myLatitude = parseFloat('<?php echo get_settings('banner_settings','map_latitude', 37.2718745); ?>');
        var myLongitude = parseFloat('<?php echo get_settings('banner_settings','map_longitude', -119.2704153); ?>');
        var zoomLevel = parseInt('<?php echo get_settings('banner_settings','map_zoom',8); ?>');
        var map_data = jQuery.parseJSON('<?php echo json_encode(get_all_properties_map_data($curr_lang)); ?>');


        function initialize() {
            var myLatlng = new google.maps.LatLng(myLatitude,myLongitude);
            var mapOptions = {
                zoom: zoomLevel,
                center: myLatlng
            }
            var map = new google.maps.Map(document.getElementById('slider-map'), mapOptions);

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
                    + '<a href="' + map_data.estates[i].detail_link + '"></a>' + '<img class="property-header-image" src="' + map_data.estates[i].featured_image_url + '" alt="" style="width:100%">'
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
                document.getElementById('pac-input'));
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
<?php $search_box_position =  get_settings('banner_settings','search_box_position','bottom');?>

<input id="pac-input" class="controls" type="text"
       placeholder="Enter a location">
<div id="slider-map" class="slider-map-holder" style="border-top:100px solid #000;"></div>

<!-- Header -->
    <header id="head">
        <div class="container">
            <div class="row">
                <?php if( $search_box_position=='ontop'){?>
                    <div class="search-box">
                        <div style="height:20px"></div>
                        <form action="<?php echo site_url('show/advfilter');?>" method="post" style="">
                            <label><i class="fa fa-home"></i> <?php echo lang_key('find_your_place'); ?> : </label>
                            <div class="input-group">
                                <input type="text" id="search_input" name="plainkey" class="form-control" style="height:40px;" placeholder="<?php echo lang_key('search_text_banner'); ?>">
                              <span class="input-group-btn">
                                <button class="btn btn-warning" type="submit"><i class="fa fa-search"></i></button>
                              </span>
                            </div>
                            <div class="search_results"></div>
                        </form>
                        <div class="search-divider">Or</div>
                        <div class="clearfix"></div>
                        <a href="<?php echo site_url('show/search');?>" class="btn btn-info"><?php echo lang_key('find_your_place'); ?></a>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
    </header>

<?php if( $search_box_position=='bottom'){?>    
<div  class="map-search" data-stellar-background-ratio="0.5" style="margin:0;width:100%;padding:50px 0;text-align:center;background: url(<?php echo base_url('uploads/banner/'.get_settings('banner_settings','search_bg','skyline.jpg'));?>)">
            <div class="row" style="margin:0;padding:0;">
                <div class="search-box">
                    <form action="<?php echo site_url('show/advfilter');?>" method="post" style="">
                        <label class="search-label"><i class="fa fa-home"></i> <?php echo lang_key('find_your_place'); ?> : </label>
                        <div class="input-group">
                          <input type="text" id="search_input" name="plainkey" class="form-control" style="height:40px;" placeholder="<?php echo lang_key('search_text_banner'); ?>">
                          <span class="input-group-btn">
                            <button class="btn btn-warning" type="submit"><i class="fa fa-search"></i></button>
                          </span>
                        </div>
                        <div id="search_results"></div>
                    </form>
                    <div class="search-divider">Or</div>
                    <div class="clearfix"></div>
                    <a href="<?php echo site_url('show/search');?>" class="btn btn-info"><?php echo lang_key('DBC_ADVANCED_SEARCH'); ?></a>
                </div>
            </div>
    </div>
</div>
<?php }?>
    <!-- /Header -->

    <link rel="stylesheet" href="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.css">
<script type="text/javascript" src="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.js"></script>

<script type="text/javascript">
jQuery(document).ready(function(){
                
                function split( val ) {
                    return val.split( / \s*/ );
                }
                function extractLast( term ) {
                    return split( term ).pop();
                }

$( "#search_input" ).bind( "keydown", function( event ) {
    if ( event.keyCode === $.ui.keyCode.TAB &&
        $( this ).autocomplete( "instance" ).menu.active ) {
            event.preventDefault();
        }
    })
    .autocomplete({
        source: function( request, response ) {
        $.getJSON( "<?php echo site_url('show/get_locations_ajax');?>/" + extractLast( request.term ), {

        }, response );
    },
    search: function() {
        // custom minLength
        var term = extractLast( this.value );
        if ( term.length < 2 ) {
        return false;
        }
    },
    focus: function() {
        // prevent value inserted on focus
        return false;
    },

    select: function( event, ui ) {
        var terms = split( this.value );
        // remove the current input
        terms.pop();
        // add the selected item
        terms.push( ui.item.value );
        // add placeholder to get the comma-and-space at the end
        terms.push( "" );
        this.value = terms.join( " " );
        return false;
    }
});


});
</script>