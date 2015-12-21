<h1 class="detail-title"><i class="fa fa-user"></i>&nbsp;
	<?php 
	if($this->session->userdata('renew')=='')
	echo lang_key('payment_finish_title');
	else
	echo lang_key('payment_renew_title');
	?>
</h1>
<div class="row">
    <div class="col-md-12" style="min-height:300px">
	<p>
		<?php 
		if($this->session->userdata('renew')=='')
		echo lang_key('payment_finish_text');
		else
		echo lang_key('payment_renew_text');
		?>
	</p>
	</div>
</div>
