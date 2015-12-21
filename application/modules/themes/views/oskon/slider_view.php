<?php $search_box_position =  get_settings('banner_settings','search_box_position','bottom');?>

<!-- Header -->

    <header id="head">

        <div class="container">

            <div class="row">

                <div class="alert alert-danger ie-message">Please use IE10 or updated version</div>
                <?php if( $search_box_position=='ontop'){?>

                <div class="search-box">


                    <form id="searchfrm" action="<?php echo site_url('show/advfilter');?>" method="post" style="">
                        <!--DBC_PURPOSE_RENT,DBC_PURPOSE_SALE , NULL -->
                        <input type="hidden" id="purpose_sale" name="purpose_sale" value=""/>
                        <input type="hidden" id="style_view" name="style_view" value="map"/>
                        <div class="OS_head_se">
                        <label><i class="fa fa-home"></i> <?php echo lang_key('find_your_place'); ?></label></div>
                        <div class="OS_but_all">
                            <ul>
                                <li><a class="activ" href="#" data-value=""><?php echo lang_key('All Type');?></a></li>
                                <li><a href="#" data-value="DBC_PURPOSE_RENT"><?php echo lang_key('For Rent');?></a></li>
                                <li><a href="#" data-value="DBC_PURPOSE_SALE"><?php echo lang_key('For Sale');?></a></li>
                            </ul>
                        </div>
                        <div class="input-group OS_but_pad">

                            <input type="text" id="search_input" name="plainkey" class="form-control SO_bor_se" style="height:40px;" placeholder="<?php echo lang_key('search_text_banner'); ?>">

                          <span class="input-group-btn">

                            <button class="btn btn-warning OS_but_se" type="submit"><i class="fa fa-search"></i></button>

                          </span>

                        </div><div style="height:15px"></div>

                        <div id="search_results"></div>

                    </form>

                    <?php /*<div class="search-divider">Or</div>

                    <div class="clearfix"></div>

                    <a href="<?php echo site_url('show/search');?>" class="btn btn-info"><?php echo lang_key('DBC_ADVANCED_SEARCH'); ?></a>
                     */?>
                </div>

                <?php }?>

            </div>

        </div>

    </header>

    <!-- /Header -->



<?php if( $search_box_position=='bottom'){?>    

<div  class="map-search" data-stellar-background-ratio="0.5" style="margin:0;width:100%;padding:50px 0;text-align:center;background: url(<?php echo base_url('uploads/banner/'.get_settings('banner_settings','search_bg','skyline.jpg'));?>)">

            <div class="row" style="margin:0;padding:0;">

                <div class="search-box">

                <form id="searchfrm" action="<?php echo site_url('show/advfilter');?>" method="post" style="">
                        <!--DBC_PURPOSE_RENT,DBC_PURPOSE_SALE , NULL -->
                        <input type="hidden" id="purpose_sale" name="purpose_sale" value=""/>
                        <input type="hidden" id="style_view" name="style_view" value="map"/>
                        <div class="OS_head_se">
                        <label><i class="fa fa-home"></i> <?php echo lang_key('find_your_place'); ?></label></div>
                        <div class="OS_but_all">
                            <ul>
                                <li><a class="activ" href="#" data-value="">All Type</a></li>
                                <li><a href="#" data-value="DBC_PURPOSE_RENT">For Rent</a></li>
                                <li><a href="#" data-value="DBC_PURPOSE_SALE">For Sell</a></li>
                            </ul>
                        </div>
                        <div class="input-group OS_but_pad">

                            <input type="text" id="search_input" name="plainkey" class="form-control SO_bor_se" style="height:40px;" placeholder="<?php echo lang_key('search_text_banner'); ?>">

                          <span class="input-group-btn">

                            <button class="btn btn-warning OS_but_se" type="submit"><i class="fa fa-search"></i></button>

                          </span>

                        </div><div style="height:15px"></div>

                        <div id="search_results"></div>

                    </form>


                    <?php /*<div class="search-divider">Or</div>

                    <div class="clearfix"></div>

                    <a href="<?php echo site_url('show/search');?>" class="btn btn-info"><?php echo lang_key('DBC_ADVANCED_SEARCH'); ?></a>
                    */?>
                </div>

            </div>

    </div>

</div>

<?php }?>



<script type="text/javascript">
    if(old_ie==0){
    var photo_index =0;
    var speed = <?php echo get_settings('banner_settings','slider_speed','3000')?>;
    var banner_url = '<?php echo base_url("uploads/banner/");?>'+'/';
   // var images = ['<?php echo theme_url();?>/assets/images/bg2.png','<?php echo theme_url();?>/assets/images/bg1.jpg'];
    var images = jQuery.parseJSON('<?php echo get_settings("banner_settings","sliders","[\"bg1.jpg\",\"bg2.png\"]")?>');
    var image = $('#head');
    //Initial Background image setup
    image.css('background-image', 'url('+banner_url+images [images.length-1]+')');
    //Change image at regular intervals
    setInterval(function(){  

        image.fadeTo("slow",1, function () {
            if(photo_index == images.length)
                photo_index = 0;
            image.css('background-image', 'url(' + banner_url+images [photo_index++] +')');
            image.fadeTo( 1 ,1);
            // image.fadeIn(500);
        });

    }, speed);     
}
</script>
<style type="text/css"> #head{background:#678;}</style>
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
            if ( term.length < 1 ) {
            return false;
            }
        },
        focus: function() {
            // prevent value inserted on focus
            return false;
        },

        select: function( event, ui ) {
            
            $('#search_input').val('');
            
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
    
   
    jQuery('.OS_but_all ul li a').live('click',function(event){
        var value = $(this).attr('data-value');
        $('#purpose_sale').val(value);
        setCookie('hist_purpose',value,1);
        $('.OS_but_all ul li a').removeClass('activ');
        $(this).addClass('activ');
        return event.preventDefault();
    });
    
    jQuery('#map_home_view .OS_map_but a').live('click',function(event){
        // search on all types incase map click
        $('#purpose_sale').val('');
        setCookie('hist_purpose',value,1);
        $('#searchfrm').submit();
        return event.preventDefault();
    });


});
</script>