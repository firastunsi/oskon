<h2 class="recent-grid"><i class="fa fa-puzzle-piece"></i>&nbsp;<?php echo lang_key('purpose_filters'); ?></h2>
<div class="well">
    <ul class="nav nav-pills nav-stacked">
        <?php 
        $filter_options = array('DBC_PURPOSE_SALE'=>'sale',
                                'DBC_PURPOSE_RENT'=>'rent',
                                'DBC_PURPOSE_BOTH'=>'both');

        foreach ($filter_options as $k=>$v) {
        ?>
        <li class="<?php echo is_active_menu('show/purpose/'.$v);?>">
            <a href="<?php echo site_url('show/purpose/'.$v);?>">
                <i class="fa fa-indent"></i>&nbsp;<?php echo lang_key($k);?>
            </a>
        </li>
        <?php
        }
        ?>
    </ul>
</div>
<div style="clear:both"></div>