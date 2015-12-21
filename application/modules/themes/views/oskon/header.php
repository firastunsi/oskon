<!-- Fixed navbar -->

<div class="navbar navbar-inverse navbar-fixed-top headroom" >

    <div class="container <?php if (isset($alias) && ($alias == 'search_result') && ($this->session->userdata('view_style')=='map') ): ?> full_container <?php endif;?>">

        <div class="navbar-header ar_rtl_logo">

            <!-- Button for smallest screens -->

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>

            <a class="navbar-brand" href="<?php echo site_url(); ?>"><img src="<?php echo get_site_logo(); ?>" alt="Oskon.me"></a>

        </div>

        <?php if (isset($alias) && $alias != 'dbc_home'): ?>
            <div class="navbar-header OS_se">
                <form action="<?php echo site_url('show/advfilter'); ?>" method="post" style="">
                    <!--DBC_PURPOSE_RENT,DBC_PURPOSE_SALE , NULL -->
                    <input type="hidden" id="purpose_sale" name="purpose_sale" value=""/>
                    <input type="hidden" id="style_view" name="style_view" value="map"/>
                    <input type="text" id="search_input" name="plainkey" class="form-control OS_se_t"  placeholder="<?php echo lang_key('search_text_banner'); ?>" value="<?php echo (isset($plainkey))?rawurldecode($plainkey):'';?>">
                </form>
            </div>
        <?php endif; ?>

        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav pull-right top-nav">
                <?php
                $CI = get_instance();
                $CI->load->model('admin/page_model');
                $CI->page_model->init();
                ?>
                <?php
                $alias = (isset($alias)) ? $alias : '';
                foreach ($CI->page_model->get_menu() as $li) {
                    if ($li->parent == 0)
                        $CI->page_model->render_top_menu($li->id, 0, $alias);
                }
                ?>



                <?php /* <li class="dropdown">

                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                  <?php echo lang_key('type');?> <b class="caret"></b>

                  </a>

                  <ul class="dropdown-menu">

                  <?php

                  $filter_options = array();

                  $this->load->config('realcon');
                  $custom_types = $this->config->item('property_types');

                  if(is_array($custom_types)) foreach ($custom_types as $key => $custom_type) {
                  if($custom_type['title']=='DBC_TYPE_APARTMENT')
                  $filter_options[$custom_type['title']] = 'apartment';
                  else if($custom_type['title']=='DBC_TYPE_HOUSE')
                  $filter_options[$custom_type['title']] = 'house';
                  else if($custom_type['title']=='DBC_TYPE_LAND')
                  $filter_options[$custom_type['title']] = 'land';
                  else if($custom_type['title']=='DBC_TYPE_COMSPACE')
                  $filter_options[$custom_type['title']] = 'com_space';
                  else if($custom_type['title']=='DBC_TYPE_CONDO')
                  $filter_options[$custom_type['title']] = 'condo';
                  else if($custom_type['title']=='DBC_TYPE_VILLA')
                  $filter_options[$custom_type['title']] = 'villa';
                  else
                  $filter_options[$custom_type['title']] = urlencode($custom_type['title']);
                  }

                  foreach ($filter_options as $k=>$v) {

                  ?>

                  <li class="<?php echo is_active_menu('show/type/'.$v);?>">

                  <a href="<?php echo site_url('show/type/'.$v);?>">

                  <i>&nbsp;</i><?php echo lang_key($k);?>

                  </a>

                  </li>

                  <?php

                  }

                  ?>
                  </ul>

                  </li> */ ?>

                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <i class="fa fa-globe"></i> <b class="caret"></b>

                    </a>



                    <?php
                    $CI = get_instance();

                    $uri = current_url();

                    $curr_lang = ($CI->uri->segment(1) != '') ? $CI->uri->segment(1) : default_lang();



                    if ($CI->uri->segment(1) == '')
                        $uri .= '/' . default_lang();



                    $CI->load->model('admin/system_model');

                    $query = $CI->system_model->get_all_langs();

                    echo '<ul class="dropdown-menu lang-menu">';

                    $url = $uri;

                    foreach ($query->result() as $lang) {

                        $uri = str_replace('/' . $curr_lang, '/' . $lang->short_name, $url);

                        $sel = ($curr_lang == $lang->short_name) ? 'active' : '';
                        if ($lang->lang == 'Arabic')
                            $lang->lang = lang_key('Arabic');
                        echo '<li class="' . $sel . '"><a href="' . $uri . '">' . ucwords($lang->lang) . '</a></li>';
                    }

                    echo '</ul>';
                    ?>

                </li>


                    <?php if (!is_loggedin()) { ?>

    <?php if (get_settings('realestate_settings', 'enable_signup', 'Yes') == 'Yes') { ?>

                        <li><a class="btn OS_loging_c" data-toggle="modal" href="#myModal"><i class="fa fa-user OS_icon_size"></i><?php echo lang_key('Login') . ' / ' . lang_key('Register'); ?></a></li>

                        <li><a class="btn OS_list_c" href="<?php echo site_url('account/listsignup'); ?>"><i class="fa fa-map-marker OS_icon_size"></i><?php echo lang_key('list_property'); ?></a></li>

                    <?php } ?>

<?php } else { ?>

    <?php if (!is_admin()) { ?>
                          <?php if (isAgent($this->session->userdata('user_id')) == 1):?>
                            <?php $agentlbl = 'Agent';?>
                        <?php else:?>
                            <?php $agentlbl = 'User';?>
                        <?php endif;?>   
                       
                        <li><a class="btn OS_loging_c" href="<?php echo site_url('admin/realestate/allestatesagent'); ?>"><i class="fa fa-user OS_icon_size"></i><?php echo lang_key($agentlbl.' Panel'); ?></a></li>
                        
                    <?php } else { ?>

                        <li><a class="btn OS_loging_c" href="<?php echo site_url('admin'); ?>"><i class="fa fa-user OS_icon_size"></i><?php echo lang_key('admin_panel'); ?></a></li>

    <?php } ?>

                    <li><a class="btn OS_loging_c" href="<?php echo site_url('account/logout'); ?>"><i class="fa fa-sign-out OS_icon_size"></i><?php echo lang_key('logout'); ?></a></li>
                    <li><a class="btn OS_list_c" href="<?php echo site_url('admin/realestate/newestate'); ?>"><i class="fa fa-map-marker OS_icon_size"></i><?php echo lang_key('list_property'); ?></a></li>
                    

<?php } ?>



            </ul>

        </div><!--/.nav-collapse -->

    </div>
</div> 

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


});
</script>

<!-- /.navbar -->