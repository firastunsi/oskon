<div class="switcher" >
    <form action="#data-content" method="post">
    <label class="filter-title"><?php echo lang_key('order_by');?>:</label>
    <?php $orderby = array("home_size"=>lang_key('home_size'),"lot_size"=>lang_key('lot_size'),"total_price"=>lang_key('price'));?>
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
    
    <a href="<?php echo site_url('show/toggle/grid/'.$current_url);?>" ref="grid" class="view-type ">
        <i class="fa fa-th <?php echo ($this->session->userdata('view_style')=='grid' || $this->session->userdata('view_style')=='')?'active':'';?>"></i>
    </a>
    <a href="<?php echo site_url('show/toggle/list/'.$current_url);?>" ref="list" class="view-type">
        <i class="fa fa-th-list <?php echo ($this->session->userdata('view_style')=='list')?'active':'';?>"></i>
    </a>
    <a href="<?php echo site_url('show/toggle/map/'.$current_url);?>" ref="map" class="view-type">
        <i class="fa fa-map-marker <?php echo ($this->session->userdata('view_style')=='map')?'active':'';?>"></i>
    </a>
    </form>
</div>
<div class="view-filter-divider"></div>