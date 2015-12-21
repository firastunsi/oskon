<?php 
$curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
?>
<div class="row">

  <?php $current_url = base64_encode(current_url().'/#data-content');?>
  <div id="data-content" class="col-md-9"  style="-webkit-transition: all 0.7s ease-in-out; transition: all 0.7s ease-in-out;">
      <h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-home fa-4"></i></span><?php echo lang_key($page_title); ?>
            <?php //require'switcher_view.php';?>
        </h1>

      <!-- Thumbnails container -->
      <?php             
      if($this->session->userdata('view_style')=='map')
      {
          require'map_view.php';
      }
      else{
            require'grid_view.php';
      }
      ?>
      <?php /*
      else
      {
          require'list_view.php';
      }*/?>
     
     
      
      <div class="clearfix"></div>
      
      <?php if($this->session->userdata('view_style')!= 'map'):?>
        <div style="text-align:center">
              <ul class="pagination">
                  <?php echo (isset($pages))?$pages:'';?>
              </ul>
        </div>  
      <?php endif;?>
      
      <!-- /Thumbnails container -->
  </div>


<div class="col-md-3">
    <?php render_widgets('right_bar_general');?>
</div>

</div> <!-- /row -->
