<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/css/dbcadmin.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/css/memento-responsive.css">

<!--Table-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/data-tables/bootstrap3/dataTables.bootstrap.css" />

<!--Gritter-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/gritter/css/jquery.gritter.css">

<!--Calendar-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/jquery-ui/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/fullcalendar/fullcalendar/fullcalendar.css"/>

<!--Rickh Text Editor-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admin/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />

<!--Rickh Text Editor-->

<link href="<?php echo base_url();?>assets/admin/css/no-more-table.css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/admin/css/custom-admin.css" rel="stylesheet">

<?php echo $this->session->flashdata('msg');?>
<h4>New <?php if($type == 'state')echo 'city';elseif ($type == 'city')echo 'region';else echo 'country';?></h4>
<hr/>

<form action="<?php echo site_url('admin/realestate/savelocation');?>" method="post" id="save-location-form">
<input type="hidden" name="type" value="<?php echo $type;?>" />	
<?php if($type=='state' || $type=="city"){?>
<label>Select Country</label>
<select name="country" class="form-control country-<?php echo $type;?>">
	<option value=""> Select a country</option>
	<?php 
	foreach ($countries->result() as $row) {
		$sel = (set_value('country')==$row->id)?'selected="selected"':'';
		echo '<option value="'.$row->id.'" '.$sel.'>'.$row->name.'</option>';
	}
	?>
</select>	
<?php echo form_error('country');?>
<?php }?>

<?php if($type=="city"){?>
<label>Select city</label>
<select name="state" class="form-control state-drop">
	<option value=""> Select a city</option>
	<?php 
	foreach ($states->result() as $row) {
		$sel = (set_value('state')==$row->id)?'selected="selected"':'';
		echo '<option class="country-drop country-'.$row->parent.'" value="'.$row->id.'" '.$sel.'>'.$row->name.'</option>';
	}
	?>
</select>	
<?php echo form_error('state');?>
<?php }?>


<label><?php if($type == 'state')echo 'city';elseif ($type == 'city')echo 'region';else echo 'country';?> names :</label>
<textarea class="form-control" style="height:260px;" name="locations" ></textarea>
<div class="alert alert-info">Put one or more <?php if($type == 'state')echo 'city';elseif ($type == 'city')echo 'region';else echo 'country';?> name as "," (comma) separated. Like Newyork,Dallas,idaho</div>
<?php echo form_error('locations');?>
<div class="clearfix"></div>
<input type="submit" value="Save" class="btn btn-success" style="margin-top:10px;" >
</form>


<script type="text/javascript">
	jQuery('#save-location-form').submit(function(event){
		event.preventDefault();
		var loadUrl = jQuery(this).attr('action');
		jQuery("#location-model  .modal-body").html("Updating...");
		jQuery.post(
			loadUrl,
			jQuery(this).serialize(),
			function(responseText){
				jQuery("#location-model  .modal-body").html(responseText);
			},
			"html"
		);

	});

	jQuery('.country-city').change(function(e){
		var val = jQuery(this).val();		
		jQuery('.country-drop').hide();
		jQuery('.country-'+val).show();
		jQuery('.state-drop').val("");
	});
</script>	