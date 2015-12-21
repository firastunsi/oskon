<h2 class="recent-grid"><i class="fa fa-puzzle-piece"></i> <?php echo lang_key('type_filers'); ?></h2>
<div class="well">
    <ul class="nav nav-pills nav-stacked">
        <?php
        $CI = get_instance();

        $filter_options = array();
        $CI->load->config('realcon');
        $custom_types = $CI->config->item('property_types');
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
                <i class="fa fa-indent"></i> <?php echo lang_key($k);?>
            </a>
        </li>
        <?php
        }
        ?>
    </ul>
</div>
<div style="clear:both"></div>