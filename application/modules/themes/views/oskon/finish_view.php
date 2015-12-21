<h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-check"></i></span>&nbsp;
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
