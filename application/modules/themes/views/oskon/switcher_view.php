<div class="switcher" >
    <form action="#data-content" method="post">
      <?php if(isset($alias) && ($alias == 'type' && $this->session->userdata('view_style') != 'map') || ($alias == 'search_result' && $this->session->userdata('view_style') != 'map')) :?>
           <label class="filter-title"><?php echo lang_key('order_by');?>:</label>
            <?php $orderby = array("home_size"=>lang_key('home_size'),"lot_size"=>lang_key('lot_size'),"total_price"=>lang_key('price'),"total_view"=>lang_key('Top'));?>
            <select name="view_orderby" class="view-filters">
                    <option value=""><?php echo lang_key('none');?></option>
                    <?php foreach ($orderby as $key=>$order) { 
                        $sel = ($key==$this->session->userdata('view_orderby'))?'selected="selected"':'';
                    ?>
                    <option value="<?php echo $key;?>" <?php echo $sel;?>><?php echo lang_key($order);?></option>
                <?php } ?>
            </select>

            <?php $ordertype = array("ASC", "DESC");?>
            <select name="view_ordertype" class="view-filters">
                <?php foreach ($ordertype as $type) { 
                        $sel = ($type==$this->session->userdata('view_ordertype'))?'selected="selected"':'';
                    ?>
                    <option value="<?php echo $type;?>" <?php echo $sel;?>><?php echo lang_key($type);?></option>
                <?php } ?>
            </select>  
     <?php endif;?>
    </form>
</div>
<div class="view-filter-divider"></div>