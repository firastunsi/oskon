<?php
$curr_lang = ($this->uri->segment(1)!='')?$this->uri->segment(1):default_lang();

$page_local_data = load_page_local_data($alias,$curr_lang);
$layout = get_page_layout($alias);
$page = $query->row_array();

if($page_local_data['status']==0)
{
	
	if(isset($page['content_from']) && $page['content_from']=='Manual')
	{
	    $sidebar = $page['sidebar'];
	    $content = $page['content'];
	    $status  = $page['status'];
	} 	
}
else
{
	$page = $page_local_data;
	$sidebar = $page['sidebar'];
    $content = $page['content'];
    $status  = $page['status'];
}
?>
 <div class="row">
 	<?php if($layout==0){?>
 	<div class="col-md-3">
 		<?php
			if(isset($sidebar) && $sidebar!='')
	        {
	            echo '<div style="min-height:300px;margin-top:20px;">'.$sidebar.'</div>';
	        }
 		?>
 	</div>
 	<?php }?>

 	<div class="<?php echo ($layout==2)?'col-md-12':'col-md-9'?>"> 
		<?php 	
			if(isset($content)==FALSE)
			{
				?>
				<div class="alert alert-info">
		        <button data-dismiss="alert" class="close" type="button">×</button>
		        <strong><?php echo lang_key('oops'); ?> :(
			    </div>
				<?php
			}
			else
			{
				if($status!=1)
				{
					?>
					<div class="alert alert-info">
			        <button data-dismiss="alert" class="close" type="button">×</button>
			        <strong><?php echo lang_key('oops'); ?> :(
				    </div>
					<?php
				}
				else

					echo '<div style="min-height:300px;margin-top:20px;">'.$content.'</div>';
			}
		?>
	</div>
	
	<?php if($layout==1){?>
	<div class="col-md-3">
 		<?php

			if(isset($sidebar) && $sidebar!='')
	        {
	        	echo '<div style="min-height:300px;margin-top:20px;">'.$sidebar.'</div>';
	        }
 		?>
	</div>
	<?php }?>		

</div>