<script src="<?php echo base_url();?>assets/admin/js/jquery.form.js"></script>

<form id="uploader-form" action="<?php echo site_url('admin/realestate/uploadgalleryfile');?>" method="post" enctype="multipart/form-data" style="display:none">
    <input type="file" name="photoimg" id="photoimg" style="height:auto;" >
</form>


<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">

    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-body">
                <div class="progress span3" style="display: block;height: 22px;margin: 2px;padding: 2px;">
                    <div class="bar"></div >
                    <div class="percent">0%</div >
                </div>
            </div>
        </div>    
    </div>    
</div>    

<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery('#photoimg').change(function(){
        jQuery('#uploader-form').submit();
    });
    jQuery('#uploader-form').ajaxForm({
        beforeSend: function() {
            jQuery('#myModal').modal('show');
            var percentVal = '0%';
            jQuery('#myModal .bar').width(percentVal);
            jQuery('#myModal .percent').html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            console.log(percentComplete);
            var percentVal = percentComplete + '%';
            jQuery('#myModal .bar').width(percentVal);
            jQuery('#myModal .percent').html(percentVal);
        },
        success: function() {
            var percentVal = '100%';
            jQuery('#myModal .bar').width(percentVal);
            jQuery('#myModal .percent').html(percentVal);
        },
        complete: function(xhr) {
            //alert(xhr.responseText);
            var response = jQuery.parseJSON(xhr.responseText);
           
            if(response.error==0)
            {
                    var base_url  = '<?php echo base_url();?>';
                    var target = jQuery('#photoimg').attr('target');
                    var input  = jQuery('#photoimg').attr('input');

                    var image_url = base_url+'uploads/gallery/'+response.name;
                    var html = '<li style="margin:10px 10px 0 0;overflow:hidden">'+
                                '<input type="hidden" name="'+input+'[]" value="'+response.name+'" />'+
                                '<image src="'+image_url+'" style="height:100%"/>'+
                                '<div class="remove-image" onclick="jQuery(this).parent().remove();">X</div>'+
                                '</li>';
                    jQuery( target ).prepend(html);
                    jQuery(target+'-input').val
                    jQuery('#gallery-photo-error').html('');
                    jQuery('#gallery-photo-error').hide();
                    jQuery('#myModal').modal('hide');
            }
            else
            {
                    if(response.error=='upload_invalid_min_dimensions')
                            response.error = 'Gallery image needs to be minimum 840x440 px';
                    var error = '<label class="col-sm-3 col-lg-2">&nbsp;</label><div class="col-sm-4 col-lg-5"><div class="alert alert-danger" style="margin-bottom:0;">'+response.error+'</div></div>';
                    jQuery('#gallery-photo-error').html(error);
                    jQuery('#gallery-photo-error').show();
                    jQuery('#myModal').modal('hide');
                    
            }
            
            
           
        }
    });
});
</script>
<style type="text/css">
.bar{
    background: none repeat scroll 0 0 #78a;
    border-radius: 3px;
    height: 17px;
}
</style>