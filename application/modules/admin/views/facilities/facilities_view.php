<div class="row">

    <div class="col-md-12">

        <?php echo $this->session->flashdata('msg'); ?>

        <?php $page = ($this->uri->segment(5)!='')?$this->uri->segment(5):0;?>

        <div class="box">

            <div class="box-title">

                <h3><i class="fa fa-bars"></i> All Amenities</h3>



                <div class="box-tool">

                    <a href="#" data-action="collapse"><i class="fa fa-chevron-up"></i></a>



                </div>

            </div>

            <div class="box-content">

                <a class="btn btn-primary" style="margin-bottom:15px;" href="<?php echo site_url('admin/realestate/newfacility'); ?>">Create a new amenity</a>

                <?php $this->load->helper('text'); ?>

                <?php if ($facilities->num_rows() <= 0) { ?>

                    <div class="alert alert-info">No Amenity</div>

                <?php } else { ?>


                <form action="" method="post" id="all_facilities_form">

                    <div id="no-more-tables">

                        <table class="table table-hover">

                            <thead>

                            <tr>

                                <th class="numeric"><input type="checkbox" id="select_all"></th>



                                <th class="numeric">#</th>



                                <th class="numeric">Icon</th>



                                <th class="numeric"><?php echo lang_key('title');?></th>


                                <th class="numeric"><?php echo lang_key('options');?></th>

                            </tr>

                            </thead>

                            <tbody>

                            <?php $i = 1;

                            foreach ($facilities->result() as $row): 
                                ?>

                                <tr>

                                    <td data-title="#" class="numeric"><input type="checkbox" name="id[]" value="<?php echo $row->id;?>"></td>


                                    <td data-title="#" class="numeric"><?php echo $i; ?></td>


                                    <td data-title="Icon" class="numeric"><img src="<?php echo base_url('uploads/thumbs/'.$row->icon); ?>" style="height: 16px;"></td>


                                    <td data-title="Title" class="numeric"><?php echo $row->title; ?></td>


                                    <td data-title="<?php echo lang_key('actions');?>" class="numeric">
                                        <div class="btn-group">
                                          <a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog"></i> <?php echo lang_key('action');?> <span class="caret"></span></a>
                                          <ul class="dropdown-menu dropdown-info">
                                              <li><a href="<?php echo site_url('admin/realestate/edit_facility/'.$row->id);?>"><?php echo lang_key('edit');?></a></li>
                                              <li><a href="<?php echo site_url('admin/realestate/remove_facility/'.$row->id); ?>">Remove</a></li>
                                          </ul>
                                        </div>
                                    </td>

                                </tr>

                                <?php $i++;endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <a href="#" id="remove-selected" class="btn btn-danger">Remove selected</a>

                    </form>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

<script type="text/javascript">





    jQuery('#searchkey').keyup(function () {



        var val = jQuery(this).val();



        var loadUrl = '<?php echo site_url('admin/search/');?>';



        jQuery("#bookings").html(ajax_load).load(loadUrl, {'key': val});



    });





    var ajax_load = '<div class="box">loading...</div>';





    jQuery('document').ready(function () {



        jQuery.ajaxSetup({



            cache: false



        });


        jQuery('#remove-selected').click(function(e){

            e.preventDefault();

            jQuery('#all_facilities_form').attr('action','<?php echo site_url("admin/realestate/remove_bulk_facilities");?>');

            jQuery('#all_facilities_form').submit();

        });



    });



</script>