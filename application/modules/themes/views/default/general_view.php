<?php 
$curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';
?>
<div class="row">

  <?php $current_url = base64_encode(current_url().'/#data-content');?>
  <div id="data-content" class="col-md-9"  style="-webkit-transition: all 0.7s ease-in-out; transition: all 0.7s ease-in-out;">
        <h1 class="recent-grid"><i class="fa fa-home fa-4"></i>&nbsp;<?php echo $page_title; ?>
            <?php require'switcher_view.php';?>
        </h1>

      <!-- Thumbnails container -->
      <?php             
      if($this->session->userdata('view_style')=='list')
      {
          require'list_view.php';
      }
      else if($this->session->userdata('view_style')=='map')
      {
          require'map_view.php';
      }
      else
      {
          require'grid_view.php';
      }
      ?>
      <div class="clearfix"></div>
      <div style="text-align:center">
        <ul class="pagination">
        <?php echo (isset($pages))?$pages:'';?>
        </ul>
      </div>  
      <!-- /Thumbnails container -->
  </div>


<div class="col-md-3">
    <?php render_widgets('right_bar_general');?>
</div>

</div> <!-- /row -->
