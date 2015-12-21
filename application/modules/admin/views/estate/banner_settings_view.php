<style type="text/css">
.file-upload{
    margin:0 !important;
    padding:0 !important;
    list-style: none;
}
.file-upload li{
    clear: both;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/bootstrap-colorpicker/css/colorpicker.css" />
<?php

?>
<div class="row">
  <div class="col-md-12">
    <?php echo $this->session->flashdata('msg');?>
    <form class="form-horizontal" id="addpackage" action="<?php echo site_url('admin/realestate/savebannersettings');?>" method="post">

    <div class="box">

      <div class="box-title">
        <h3><i class="fa fa-bars"></i>Banner settings</h3>
        <div class="box-tool">
          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
        </div>
      </div>

      <div class="box-content">

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Menu background:</label>
            <div class="col-sm-5 col-lg-3 controls">
              <?php $v = (set_value('menu_bg_color')!='')?set_value('menu_bg_color'):get_settings('banner_settings','menu_bg_color', 'rgba(241, 89, 42, .8)');?>
              <input type="text" name="menu_bg_color" class="form-control colorpicker-rgba" value="<?php echo $v;?>" data-color-format="rgba"/>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Menu text color:</label>
              <div class="col-sm-5 col-lg-3 controls">
                  <?php $v = (set_value('menu_text_color')!='')?set_value('menu_text_color'):get_settings('banner_settings','menu_text_color', '#ffffff');?>
                  <div class="input-group color colorpicker-default" data-color="<?php echo $v;?>" data-color-format="rgba">
                    <span class="input-group-addon"><i style="background-color: <?php echo $v;?>;"></i></span>
                    <input type="text" name="menu_text_color" class="form-control" value="<?php echo $v;?>" readonly>
                  </div>
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 col-lg-2 control-label">Activated menu color:</label>
              <div class="col-sm-5 col-lg-3 controls">
                  <?php $v = (set_value('active_menu_text_color')!='')?set_value('active_menu_text_color'):get_settings('banner_settings','active_menu_text_color', '#ffffff');?>
                  <div class="input-group color colorpicker-default" data-color="<?php echo $v;?>" data-color-format="rgba">
                    <span class="input-group-addon"><i style="background-color: <?php echo $v;?>;"></i></span>
                    <input type="text" name="active_menu_text_color" class="form-control" value="<?php echo $v;?>" readonly>
                  </div>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-3 col-lg-2 control-label">Banner type :</label>
              <div class="col-sm-4 col-lg-5 controls">
                  <?php $options = array("Slider","Google Map");?>
                  <select id="banner_type" name="banner_type" class="form-control input-sm">
                      <?php $v = (set_value('banner_type')!='')?set_value('banner_type'):get_settings('banner_settings','banner_type','Slider');?>
                      <?php foreach ($options as $option) { 
                            $sel = ($option==$v)?'selected="selected"':'';
                        ?>
                          <option value="<?php echo $option;?>" <?php echo $sel;?>><?php echo lang_key($option);?></option>
                      <?php } ?>
                  </select>
                  <span class="help-inline">&nbsp;</span>
                  <?php echo form_error('banner_type'); ?>
              </div>
          </div>

           <div id="slider-panel" style="display:none">
            <h4 style="margin-left:30px;border-bottom:1px solid #ccc;padding-bottom:10px;">Slider settings</h4>
            <div class="form-group">
              <label class="col-sm-3 col-lg-2 control-label">Slider speed :</label>
              <div class="col-sm-4 col-lg-5 controls">
                  <?php $options = array("1000"=>"1 sec","2000"=>"2 sec","3000"=>"3 sec",
                                         "4000"=>"4 sec","5000"=>"5 sec","6000"=>"6 sec",
                                         "7000"=>"7 sec","8000"=>"8 sec","9000"=>"9 sec",
                                         "10000"=>"10 sec");?>
                  <select id="slider_speed" name="slider_speed" class="form-control input-sm">
                    <?php $v = (set_value('slider_speed')!='')?set_value('slider_speed'):get_settings('banner_settings','slider_speed','3000');?>
                      <?php foreach ($options as $key=>$option) { 
                            $sel = ($key==$v)?'selected="selected"':'';
                        ?>
                          <option value="<?php echo $key;?>" <?php echo $sel;?>><?php echo $option;?></option>
                      <?php } ?>
                  </select>
                  <span class="help-inline">&nbsp;</span>
                  <?php echo form_error('slider_speed'); ?>
              </div>
            </div>

            <?php 
            $images = (isset($_POST['banner']) && count($_POST['banner'])>0)?json_encode($_POST['banner']):get_settings('banner_settings','sliders','["bg1.jpg","bg2.png"]');           
            $banner = json_decode($images);            
            ?>
            <ul class="file-upload">
                <?php
                if(empty($banner))
                {
                  ?>
                    <li>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">
                                <img class="thumbnails thumb1" src="<?php echo base_url('uploads/banner/banner-preview.jpg');?>" style="width:80px;">
                            </label>
                            <div class="col-sm-2 col-lg-3 controls">
                                <input type="hidden" name="banner[]" class="banner_photo1 banner" preview="thumb1" value="banner-preview.jpg">                    
                                <iframe src="<?php echo site_url('admin/realestate/bannerimguploader/1');?>" style="border:0;margin:0;padding:0;height:130px;"></iframe>
                                <span class="help-inline banner-error1"></span>                            
                            </div>
                            <div class="col-sm-2 col-lg-1 controls">
                                <a href="javascript:void(0);" style="color:red" onclick="jQuery(this).parent().parent().parent().remove();">X Remove</a>
                            </div>
                        </div>
                    </li>

                  <?php
                   echo '<script type="text/javascript">var no_of_images=2;</script>';
                } 
                else
                {

                    $i=1;
                    foreach ($banner as $img) 
                    {
                      $img = ($img=='')?'banner-preview.jpg':$img;
                     ?>
                    <li>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">
                                <img class="thumbnails thumb<?php echo $i;?>" src="<?php echo base_url('uploads/banner/'.$img);?>" style="width:80px;">
                            </label>
                            <div class="col-sm-2 col-lg-3 controls">
                                <input type="hidden" name="banner[]" class="banner_photo<?php echo $i;?> banner" preview="thumb<?php echo $i;?>" value="<?php echo $img;?>">                    
                                <iframe src="<?php echo site_url('admin/realestate/bannerimguploader/'.$i);?>" style="border:0;margin:0;padding:0;height:130px;"></iframe>
                                <span class="help-inline banner-error<?php echo $i;?>"></span>                            
                            </div>
                            <div class="col-sm-2 col-lg-1 controls">
                                <a href="javascript:void(0);" style="color:red" onclick="jQuery(this).parent().parent().parent().remove();">X Remove</a>
                            </div>
                        </div>
                    </li>
                    <?php
                      $i++;
                    }
                    echo '<script type="text/javascript">var no_of_images='.$i.';</script>';
                }
                ?>
            </ul>        
             <div class="clearfix"></div>
            <div class="form-group">
              <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>
              <div class="col-sm-4 col-lg-5 controls">
                <a href="#" class="btn btn-info add-another"><?php echo lang_key('add_another');?></a>
              </div>
            </div>
             <div class="clearfix"></div>

           </div> 
      </div>
    </div>
    <!-- end image box -->
    <div class="box">

        <div class="box-title">
            <h3><i class="fa fa-bars"></i>Map  Settings</h3>
            <div class="box-tool">
                <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="box-content">
            <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">Latitude:</label>
                <div class="col-sm-4 col-lg-5 controls">
                    <?php $v = (set_value('map_latitude')!='')?set_value('map_latitude'):get_settings('banner_settings','map_latitude', 37.2718745);?>
                    <input class="form-control" type="text" name="map_latitude" id="map_latitude" value="<?php echo $v;?>">
                    <span class="help-inline">&nbsp;</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">Longitude:</label>
                <div class="col-sm-4 col-lg-5 controls">
                    <?php $v = (set_value('map_longitude')!='')?set_value('map_longitude'):get_settings('banner_settings','map_longitude',-119.2704153);?>
                    <input class="form-control" type="text" name="map_longitude" id="map_longitude" value="<?php echo $v;?>">
                    <span class="help-inline">&nbsp;</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">Map Zoom:</label>
                <div class="col-sm-4 col-lg-5 controls">
                    <?php $v = (set_value('map_zoom')!='')?set_value('map_zoom'):get_settings('banner_settings','map_zoom', 8);?>
                    <select id="map_zoom" name="map_zoom" class="form-control input-sm">
                        <?php for($i=1;$i<=18; $i++){
                            $sel = ($i==$v)?'selected="selected"':''; ?>
                        <option value="<?php echo $i; ?>" <?php echo $sel;?>><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
<!--                    <input class="form-control" type="text" name="map_zoom" id="map_zoom" value="--><?php //echo $v;?><!--">-->
                    <span class="help-inline">&nbsp;</span>
                </div>
            </div>
        </div>
    </div>

    <div class="box">

      <div class="box-title">
        <h3><i class="fa fa-bars"></i>Search panel settings</h3>
        <div class="box-tool">
          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>
        </div>
      </div>

      <div class="box-content">


          <div class="form-group">
              <label class="col-sm-3 col-lg-2 control-label">Search box positon :</label>
              <div class="col-sm-4 col-lg-5 controls">
                  <?php $options = array("ontop"=>"Over the banner","bottom"=>"Under the banner");?>
                  <select id="search_box_position" name="search_box_position" class="form-control input-sm">
                      <?php $v = (set_value('search_box_position')!='')?set_value('search_box_position'):get_settings('banner_settings','search_box_position','ontop');?>
                      <?php foreach ($options as $key=>$option) { 
                            $sel = ($key==$v)?'selected="selected"':'';
                        ?>
                          <option value="<?php echo $key;?>" <?php echo $sel;?>><?php echo lang_key($option);?></option>
                      <?php } ?>
                  </select>
                  <span class="help-inline">&nbsp;</span>
                  <?php echo form_error('search_box_position'); ?>
              </div>
          </div>

          <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>
                <div class="col-sm-4 col-lg-5 controls">
                    <?php $featured_img = (set_value('search_bg')!='')?set_value('search_bg'):get_settings('banner_settings','search_bg','skyline.jpg');?>
                    <img class="" id="search_bg_preview" src="" style="width:300px;">
                    <span id="search_bg-error"><?php echo form_error('search_bg')?></span> 
                </div>
                <div class="clearfix"></div>                   
            </div>

            <div class="form-group">
                <label class="col-sm-3 col-lg-2 control-label">BG image:</label>
                <div class="col-sm-4 col-lg-5 controls">                    
                    <input type="hidden" name="search_bg" id="search_bg" value="<?php echo $featured_img;?>">                    
                    <iframe src="<?php echo site_url('admin/realestate/searchbguploader');?>" style="border:0;margin:0;padding:0;height:130px;"></iframe>
                    <span class="help-inline">&nbsp;</span>
                </div>          
            </div>
            <div class="clearfix"></div>

      </div> 
    </div>
    <input type="submit" value="Update" class="btn btn-success">     
    </form>

  </div>
</div>
<script type="text/javascript">
var base_url = '<?php echo base_url();?>';
jQuery(document).ready(function(){
    jQuery('#banner_type').change(function(){
        var val = jQuery(this).val();
        if(val=='Slider')
        {
            jQuery('#slider-panel').show();
            jQuery('#map-panel').hide();
            sliderinit();
        }
        else
        {
            jQuery('#map-panel').show();
            jQuery('#slider-panel').hide();
        }
    }).change();

    jQuery('#search_bg').change(function(){
            var val = jQuery(this).val();
            var src = base_url+'uploads/banner/'+val;
            jQuery('#search_bg_preview').attr('src',src);
    }).change();
});

function sliderinit ()
{
        jQuery('.add-another').click(function(e){
            e.preventDefault();
            var length = no_of_images++;
            var html = '<li>'+
                            '<div class="form-group">'+
                                '<label class="col-sm-3 col-lg-2 control-label">'+
                                    '<img class="thumbnails thumb'+length+'" src="<?php echo base_url("assets/admin/img/banner-preview.jpg");?>" style="width:80px;">'+
                                '</label>'+
                                '<div class="col-sm-2 col-lg-3 controls">'+
                                    '<input type="hidden" name="banner[]" class="banner_photo'+length+' banner" preview="thumb'+length+'" value="">                    '+
                                    '<iframe src="<?php echo site_url("admin/realestate/bannerimguploader");?>/'+length+'" style="border:0;margin:0;padding:0;height:130px;"></iframe>'+
                                    '<span class="help-inline banner-error'+length+'"></span>'+
                                '</div>'+
                                '<div class="col-sm-2 col-lg-1 controls">'+
                                   ' <a href="javascript:void(0);" style="color:red" onclick="jQuery(this).parent().parent().parent().remove();">X Remove</a>'+
                                '</div>'+
                            '</div>'+
                        '</li>';
            jQuery('.file-upload').append(html);

            jQuery('.banner').change(function(){
                var val = jQuery(this).val();
                var src = base_url+'uploads/banner/'+val;
                var preview = jQuery(this).attr('preview');
                jQuery('.'+preview).attr('src',src);
            });
        });

        jQuery('.banner').change(function(){
            var val = jQuery(this).val();
            var src = base_url+'uploads/banner/'+val;
            var preview = jQuery(this).attr('preview');
            jQuery('.'+preview).attr('src',src);
        });

}
</script>
<script src="<?php echo base_url();?>assets/admin/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
