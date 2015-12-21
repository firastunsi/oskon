<link href="<?php echo base_url();?>assets/datatable/dataTables.bootstrap.css" rel="stylesheet">
<style type="text/css">
  #all-posts_filter input{
  width: 200px;
  }
</style>
<?php 
$curr_page = $this->uri->segment(5);
if($curr_page=='')
  $curr_page = 0;
$dl = default_lang();
?>
<div class="row">
   
  <div class="col-md-12">

    <div class="box">

      <div class="box-title">
          
        <h3><i class="fa fa-bars"></i> <?php echo lang_key('all_estates');?></h3>

        <div class="box-tool">

          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>


        </div>

      </div>

      <div class="box-content">

        <?php echo $this->session->flashdata('msg');?>

        <?php if($posts->num_rows()<=0){?>

        <div class="alert alert-info"><?php echo lang_key('no_estates_found');?></div>

        <?php }else{?>       
        <div id="no-more-tables">

        <table id="all-posts" class="table table-hover">

           <thead>

               <tr>

                   <th class="numeric"><?php echo lang_key('#ID');?></th>

                  <th class="numeric"><?php echo lang_key('image');?></th>

                  <th class="numeric"><?php echo lang_key('title');?></th>

                  <th class="numeric"><?php echo lang_key('type');?></th>

                  <th class="numeric"><?php echo lang_key('purpose');?></th>

                  <th class="numeric"><?php echo lang_key('condition');?></th>
                  
                  <th class="numeric"><?php echo lang_key('price');?></th>

                  <th class="numeric"><?php echo lang_key('status');?></th>

                  <th class="numeric"><?php echo lang_key('Featured');?></th>

                  <th class="numeric"><?php echo lang_key('actions');?></th>

               </tr>

           </thead>

           <tbody>

        	<?php foreach($posts->result() as $row):  ?>

               <tr>

                  <td data-title="#ID" class="numeric"><?php echo $row->id;?></td>

                  <td data-title="Thumb" class="numeric"><img class="thumbnail" style="width:50px;margin-bottom:0px;" src="<?php echo get_featured_photo_by_id($row->featured_img);?>" /></td>

                  <td data-title="Title" class="numeric"><?php echo get_title_for_edit_by_id_lang($row->id,$dl);?></td>

                  <td data-title="Type" class="numeric"><?php echo lang_key($row->type);?></td>

                  <td data-title="Purpose" class="numeric"><?php echo lang_key($row->purpose)?></td>

                  <td data-title="Condition" class="numeric"><?php echo lang_key($row->estate_condition)?></td>
                  
                  <td data-title="Price" class="numeric"><?php echo $row->total_price;?></td>
                  
                  <td data-title="<?php echo lang_key('status');?>" class="numeric"><?php echo get_status_title_by_value($row->status);?></td>
                  
                  <td data-title="Featured" class="numeric"><?php echo ($row->featured==1)?'<span class="label label-success">'.lang_key('Yes').'</span>':'<span class="label label-info">'.lang_key('No').'</span>';?></td>

                  <td data-title="<?php echo lang_key('actions');?>" class="numeric">

                    <div class="btn-group">

                      <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <?php echo lang_key('action');?> <span class="caret"></span></a>

                      <ul class="dropdown-menu dropdown-info">

                          <li><a href="<?php echo site_url('admin/realestate/editestate/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('edit');?></a></li>
                          <?php if($row->status==0){?>
                          <li><a href="<?php echo site_url('admin/realestate/reactivateestate/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('reactivate');?></a></li>
                          <?php }else{?>
                          <li><a href="<?php echo site_url('admin/realestate/deleteestate/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('delete');?></a></li>
                          <?php }?>
                          <?php if(is_admin()){?>
                            <?php if($row->status==2){?>
                            <li><a href="<?php echo site_url('admin/realestate/approveestate/'.$curr_page.'/'.$row->id);?>">Approve</a></li>
                            <?php }?>
                            <?php if($row->featured==0){?>
                            <li><a href="<?php echo site_url('admin/realestate/featurepost/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('Make Featured');?></a></li>
                            <?php }else{?>
                            <li><a href="<?php echo site_url('admin/realestate/removefeaturepost/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('Remove Featured');?></a></li>
                            <?php }?>
                          <?php }else{?>
                            <?php if(get_settings('realestate_settings','enable_feature_payment','No')=='Yes' && $row->featured==0){?>
                            <li><a href="<?php echo site_url('admin/realestate/featurepayment/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('Make Featured');?></a></li>
                            <?php }?>
                          <?php }?>
                      </ul>

                    </div>

                  </td>

               </tr>

            <?php endforeach;?>   

           </tbody>

        </table>

        </div>


        <?php }?>

        </div>

    </div>

  </div>

</div>
<?php $curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):'en';?>
<?php if ($curr_lang == 'ar'):?>
    <script src="<?php echo base_url();?>assets/datatable/jquery.dataTables_ar.min.js"></script>
<?php else:?>
    <script src="<?php echo base_url();?>assets/datatable/jquery.dataTables.min.js"></script>
<?php endif;?>

<script src="<?php echo base_url();?>assets/datatable/dataTables.bootstrap.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#all-posts').dataTable();
});
</script>
