<?php 
$curr_page = $this->uri->segment(5);
if($curr_page=='')
  $curr_page = 0;
?>
<div class="row">
   
  <div class="col-md-12">

    <div class="box">

      <div class="box-title">

        <h3><i class="fa fa-bars"></i> All Payments</h3>

        <div class="box-tool">

          <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>


        </div>

      </div>

      <div class="box-content">

        <?php echo $this->session->flashdata('msg');?>

        <?php if($posts->num_rows()<=0){?>

        <div class="alert alert-info">No Payment History</div>

        <?php }else{?>
        <div class="row">
          <form class="" action="" method="post">
          <div class="col-lg-6">
            <div class="input-group">
              <input type="text" placeholder="Transaction ID" name="transaction_id" value="<?php echo $this->input->post('transaction_id');?>" class="form-control">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-default" style="border-radius:0 3px 3px 0">Search</button>
              </span>
            </div><!-- /input-group -->
          </div>
          </form>
        </div>
        <div id="no-more-tables">

        <table class="table table-hover">

           <thead>

               <tr>

                  <th class="numeric">#</th>

                  <th class="numeric">Transaction ID</th>

                  <th class="numeric">User</th>

                  <th class="numeric">Package</th>

                  <th class="numeric">Amount</th>

                  <th class="numeric">Request Date</th>

                  <th class="numeric">Activation date</th>
                  
                  <th class="numeric">Expiration date</th>

                  <th class="numeric"><?php echo lang_key('status');?></th>

                  <th class="numeric"><?php echo lang_key('actions');?></th>

               </tr>

           </thead>

           <tbody>

        	<?php $i=$start+1;foreach($posts->result() as $row):  ?>

               <tr>

                  <td data-title="#" class="numeric"><?php echo $i;?></td>

                  <td data-title="#" class="numeric"><?php echo $row->unique_id;?></td>

                  <td data-title="User" class="numeric"><a href="<?php echo site_url('admin/users/detail/'.$row->user_id);?>"><?php echo get_user_fullname_by_id($row->user_id)?></a></td>

                  <td data-title="Package" class="numeric"><?php echo get_package_name_by_id($row->package_id);?></td>

                  <td data-title="Amount" class="numeric"><?php echo $row->amount;?></td>

                  <td data-title="Request Date" class="numeric"><?php echo $row->request_date;?></td>

                  <td data-title="Activation date" class="numeric"><?php echo $row->activation_date;?></td>
                  
                  <td data-title="Expiration date" class="numeric"><?php echo $row->expirtion_date;?></td>
                  
                  <td data-title="<?php echo lang_key('status');?>" class="numeric"><?php echo get_payment_status_title_by_value($row->is_active);?></td>

                  <td data-title="<?php echo lang_key('actions');?>" class="numeric">

                    <div class="btn-group">

                      <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <?php echo lang_key('action');?> <span class="caret"></span></a>

                      <ul class="dropdown-menu dropdown-info">

                          <li><a href="<?php echo site_url('admin/realestate/deletehistory/'.$curr_page.'/'.$row->id);?>"><?php echo lang_key('delete');?></a></li>
                          <li><a href="<?php echo site_url('admin/realestate/confirmtransaction/'.$curr_page.'/'.$row->unique_id);?>"><?php echo lang_key('confirm');?></a></li>

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


<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.filters').change(function(){
        jQuery('#filter_form').submit();
    });
});
</script>
