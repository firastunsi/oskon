<link rel="stylesheet" href="<?php echo theme_url();?>/assets/css/chosen.min.css">
<link rel="stylesheet" href="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.min.css">
<script src="<?php echo theme_url();?>/assets/js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo theme_url();?>/assets/jquery-ui/jquery-ui.min.js"></script>
<?php 
$curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
$CI = get_instance();
?>

        <style type="text/css">
        .up,.down{color:#fff;}
        .up:hover,.down:hover{color:#fff;}
        </style>
        
        <div class="row" style="height: 100%">
          <?php $current_url = base64_encode(current_url().'/#data-content');?>
          <div class="<?php if($this->session->userdata('view_style')=='grid'):?>col-md-9<?php else:?><?php endif;?>"  style="-webkit-transition: all 0.7s ease-in-out; transition: all 0.7s ease-in-out;">
              
              <?php if($this->session->userdata('view_style') != 'map'):?>
                <h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-search fa-4"></i></span>&nbsp;<?php echo lang_key('result'); ?>
                    <?php //require'switcher_view.php';?>
                </h1>
              <?php else:?>
                <?php //require'switcher_view.php';?>
              <?php endif;?>
              
              <?php
              if($this->session->userdata('view_style')=='grid')
              {
                  require'grid_view.php';
              }
              else
              {
                  $map_id = 'search_map_view';
                  require 'map_view.php';
                  require 'list_map_filter.php';
              }
              ?>
              <div class="clearfix"></div>
              <?php if($this->session->userdata('view_style')=='grid'):?>
                <div style="text-align:center">
                  <ul class="pagination">
                        <?php echo (isset($pages))?$pages:'';?>
                  </ul>
                </div>
              <?php endif;?>
          </div>
            
            <?php if($this->session->userdata('view_style')=='grid'):?>
                <div class="col-md-3">
                    <?php render_widgets('right_bar_general');?>
                </div>
            <?php endif;?>
            
        </div> <!-- /row -->

        <script type="text/javascript">
            function updown()
            {

              $('.up').click(function(e){

                  e.preventDefault();

                  var panel = $(this).attr('href');

                  $(panel+' > .panel-body').hide();

                  $(panel+ ' .fa-chevron-up').addClass('fa-chevron-down');

                  $(panel+ ' .fa-chevron-up').removeClass('fa-chevron-up');

                  $(this).addClass('down');

                  $(this).removeClass('up');

                  updown();

                });



                $('.down').click(function(e){

                  e.preventDefault();

                  var panel = $(this).attr('href');

                  $(panel+' > .panel-body').show();

                  $(panel+ ' .fa-chevron-down').addClass('fa-chevron-up');

                  $(panel+ ' .fa-chevron-down').removeClass('fa-chevron-down');

                  $(this).addClass('up');

                  $(this).removeClass('down');

                  updown();

                });

            }

            $(function () {

                

                updown();

                jQuery('.facility-filter').click(function(){
                  jQuery('.facility').hide('slow');
                  var flag = 0;
                  jQuery('.facility-filter').each(function(){
                      var val = jQuery(this).val();
                      var sel = jQuery(this).attr('checked');
                      if(sel=='checked')
                      {
                        flag = 1;
                        jQuery('.facility-'+val).show('slow');
                      }
                  });     

                  if(flag==0)
                    jQuery('.facility').show('slow');             
                  
                });

                $('#ignor_plain').change(function() {

                    if($(this).is(":checked")) {

                        // var returnVal = confirm("Are you sure?");

                        // $(this).attr("checked", returnVal);

                        var panel = jQuery(this).attr('target');

                        jQuery(panel).hide();

                    }

                    else

                    {

                        var panel = jQuery(this).attr('target');

                        jQuery(panel).show();

                    }

                }).change();


            

                $('#ignor_location').change(function() {

                    if($(this).is(":checked")) {

                        // var returnVal = confirm("Are you sure?");

                        // $(this).attr("checked", returnVal);

                        var panel = jQuery(this).attr('target');

                        jQuery(panel).hide();

                    }

                    else

                    {

                        var panel = jQuery(this).attr('target');

                        jQuery(panel).show();

                    }

                }).change();



                $('#ignor_adv').change(function() {

                    if($(this).is(":checked")) {

                        // var returnVal = confirm("Are you sure?");

                        // $(this).attr("checked", returnVal);

                        var panel = jQuery(this).attr('target');

                        jQuery(panel).hide();

                    }

                    else

                    {

                        var panel = jQuery(this).attr('target');

                        jQuery(panel).show();

                    }

                }).change();



                jQuery('#country').change(function(){

                    jQuery('#state').val('');

                    jQuery('#selected_state').val('');

                    jQuery('#city').val('');

                    jQuery('#selected_city').val('');

                });



                jQuery('#state').change(function(){

                    jQuery('#city').val('');

                    jQuery('#selected_city').val('');

                });



                jQuery( "#state" ).bind( "keydown", function( event ) {

                    if ( event.keyCode === jQuery.ui.keyCode.TAB &&

                    jQuery( this ).data( "ui-autocomplete" ).menu.active ) {

                        event.preventDefault();

                    }

                })

                .autocomplete({

                    source: function( request, response ) {

                        

                        jQuery.post(

                            "<?php echo site_url('show/get_states_ajax');?>/",

                            {term: request.term,country: jQuery('#country').val()},

                            function(responseText){

                                response(responseText);

                                jQuery('#selected_state').val('');

                                jQuery('.state-loading').html('');

                            },

                            "json"

                        );

                    },

                    search: function() {

                        // custom minLength

                        var term = this.value ;

                        if ( term.length < 2 ) {

                            return false;

                        }

                        else

                        {

                            jQuery('.state-loading').html('Loading...');

                        }

                    },

                    focus: function() {

                        // prevent value inserted on focus

                        return false;

                    },

                    select: function( event, ui ) {

                        this.value = ui.item.value;

                        jQuery('#selected_state').val(ui.item.id);

                        jQuery('.state-loading').html('');

                        return false;

                    }

                });





                jQuery( "#city" ).bind( "keydown", function( event ) {

                    if ( event.keyCode === jQuery.ui.keyCode.TAB &&

                    jQuery( this ).data( "ui-autocomplete" ).menu.active ) {

                        event.preventDefault();

                    }

                })

                .autocomplete({

                    source: function( request, response ) {

                        

                        jQuery.post(

                            "<?php echo site_url('show/get_cities_ajax');?>/",

                            {term: request.term,state: jQuery('#selected_state').val()},

                            function(responseText){

                                response(responseText);

                                jQuery('#selected_city').val('');

                                jQuery('.city-loading').html('');

                            },

                            "json"

                        );

                    },

                    search: function() {

                        // custom minLength

                        var term = this.value ;

                        if ( term.length < 2 || jQuery('#selected_state').val()=='') {

                            return false;

                        }

                        else

                        {

                            jQuery('.city-loading').html('Loading...');

                        }

                    },

                    focus: function() {

                        // prevent value inserted on focus

                        return false;

                    },

                    select: function( event, ui ) {

                        this.value = ui.item.value;

                        jQuery('#selected_city').val(ui.item.id);

                        jQuery('.city-loading').html('');

                        return false;

                    }

                });



                $(".chzn-select").chosen();



//                var start_range = 0;
//
//                var end_range = 1000000;

//                $("#slider-range-price").slider({
//
//                    range: true,
//
//                    min: 0,
//
//                    max: 1000000,
//
//                    values: [ start_range, end_range ],
//
//                    slide: function (event, ui) {
//
//                        $("#price_min").val(ui.values[ 0 ]);
//
//                        $("#price_max").val(ui.values[ 1 ]);
//
//                    }
//
//                });


//                var start_range = 0;
//
//                var end_range = 20000;
//
//                $("#slider-range-rent-price").slider({
//
//                    range: true,
//
//                    min: 0,
//
//                    max: 20000,
//
//                    values: [ start_range, end_range ],
//
//                    slide: function (event, ui) {
//
//                        $("#rent_price_min").val(ui.values[ 0 ]);
//
//                        $("#rent_price_max").val(ui.values[ 1 ]);
//
//                    }
//
//                });



//                var start_range = 0;
//
//                var end_range = 50;
//
//                $("#slider-bedroom").slider({
//
//                    range: true,
//
//                    min: 0,
//
//                    max: 50,
//
//                    values: [ start_range, end_range ],
//
//                    slide: function (event, ui) {
//
//                        $("#bedroom_min").val(ui.values[ 0 ]);
//
//                        $("#bedroom_max").val(ui.values[ 1 ]);
//
//                    }
//
//                });


//
//                var start_range = 0;
//
//                var end_range = 50;
//
//                $("#slider-bath").slider({
//
//                    range: true,
//
//                    min: 0,
//
//                    max: 50,
//
//                    values: [ start_range, end_range ],
//
//                    slide: function (event, ui) {
//
//                        $("#bath_min").val(ui.values[ 0 ]);
//
//                        $("#bath_max").val(ui.values[ 1 ]);
//
//                    }
//
//                });



                var start_range = 1900;

                var end_range = 2020;

                $("#slider-year").slider({

                    range: true,

                    min: 1900,

                    max: 2020,

                    values: [ start_range, end_range ],

                    slide: function (event, ui) {

                        $("#year_min").val(ui.values[ 0 ]);

                        $("#year_max").val(ui.values[ 1 ]);

                    }

                });



            });



        </script>