<?php 
$curr_page = $this->uri->segment(5);
if($curr_page=='')
  $curr_page = 0;
?>
<div class="row">

  <div class="col-md-12">

    <a href="<?php echo site_url('admin/realestate/newlocation/country');?>" class="btn btn-success add-location">Add Country</a>
    <a href="<?php echo site_url('admin/realestate/newlocation/state');?>" class="btn btn-info add-location">Add City</a>
    <a href="<?php echo site_url('admin/realestate/newlocation/city');?>" class="btn btn-warning add-location">Add Region</a>
    <div style="clear:both;margin-top:20px;"></div>

    <div class="box">

      <div class="box-title">

        <h3><i class="fa fa-bars"></i> All Locations</h3>

        <div class="box-tool">

          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>


        </div>

      </div>

      <div class="box-content">

        <?php echo $this->session->flashdata('msg');?>

        <?php if(count($posts)<=0){?>

        <div class="alert alert-info">No Location</div>

        <?php }else{?>

        <div id="no-more-tables">

        <table class="table table-hover">

           <thead>

               <tr>

                  <th class="numeric">#</th>

                  <th class="numeric">Name</th>

                  <th class="numeric"><?php echo lang_key('type');?></th>

                  <th class="numeric"><?php echo lang_key('parent');?></th>

                  <th class="numeric"><?php echo lang_key('actions');?></th>

               </tr>

           </thead>

           <tbody>

        	<?php $i=1;foreach($posts as $row):
                $dash = '';
                if($row->type=='state')
                  $dash = '|___';
                elseif($row->type=='city')
                  $dash = '&nbsp;&nbsp;&nbsp;&nbsp;|___';
          ?>

               <tr>

                  <td data-title="#" class="numeric"><?php echo $i;?></td>

                  <td data-title="Title" class="numeric"><?php echo $dash.' '.$row->name;?></td>

                  <td data-title="<?php echo lang_key('description');?>" class="numeric"><?php if($row->type == 'state')echo 'city';elseif ($row->type == 'city')echo 'region';else echo 'country';?></td>

                  <td data-title="<?php echo lang_key('status');?>" class="numeric">

                    <?php echo get_location_name_by_id($row->parent);?>

                  </td>

                  <td data-title="<?php echo lang_key('actions');?>" class="numeric">

                    <div class="btn-group">

                      <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <?php echo lang_key('action');?> <span class="caret"></span></a>

                      <ul class="dropdown-menu dropdown-info">

                          <li><a href="<?php echo site_url('admin/realestate/editlocation/'.$row->type.'/'.$row->id);?>" class="edit-location"><?php echo lang_key('edit');?></a></li>
                          <li><a href="<?php echo site_url('admin/realestate/deletelocation/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('delete');?></a></li>

                      </ul>

                    </div>

                  </td>

               </tr>

            <?php $i++;endforeach;?>   

           </tbody>

        </table>

        </div>

        <div class="pagination"><ul class="pagination pagination-colory"><?php echo $pages;?></ul></div>

        <?php }?>

        </div>

    </div>

  </div>

</div>


<!-- Modal -->
<div class="modal fade" id="location-model" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header" style="border-bottom:0px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
            <div class="modal-body"  style="padding-top:0px;">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
    
    jQuery(".add-location").click(function(event){
        event.preventDefault();
        var loadUrl = jQuery(this).attr("href");
        jQuery('#location-model').modal('show');
        jQuery("#location-model  .modal-body").html("Loading...");
        jQuery.get(
                loadUrl,
                {},
                function(responseText){
                    jQuery("#location-model  .modal-body").html(responseText);
                },
                "html"
            );
    });

    jQuery(".edit-location").click(function(event){
        event.preventDefault();
        var loadUrl = jQuery(this).attr("href");
        jQuery('#location-model').modal('show');
        jQuery("#location-model  .modal-body").html("Loading...");
        jQuery.get(
                loadUrl,
                {},
                function(responseText){
                    jQuery("#location-model  .modal-body").html(responseText);
                },
                "html"
            );
    });

    jQuery('#location-model').on('hidden.bs.modal', function () {
        location.reload();
    });

});
</script>
