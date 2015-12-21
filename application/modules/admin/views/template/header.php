<div id="navbar" class="navbar">
	<button type="button" class="navbar-toggle navbar-btn collapsed" data-toggle="collapse" data-target="#sidebar">
	<span class="fa fa-bars"></span>
	</button>
	<a class="navbar-brand" href="<?php echo site_url('admin');?>">
	<small>
	<i class="fa fa-desktop"></i>
	Dbcinfotech Admin </small>
	</a>
		
	<ul class="nav memento-nav pull-right">
		<li class="user-profile">
			<a data-toggle="dropdown" href="" class="user-menu dropdown-toggle">
			<i class="fa fa-user"></i>
			<span class="hhh" id="user_info"><?php echo $this->session->userdata('user_name');?></span>
			<i class="fa fa-caret-down"></i>
			</a>
			<ul class="dropdown-menu dropdown-navbar" id="user_menu">
				<li style="margin-top:10px;"></li>	
				<li>
				<a href="<?php echo site_url('admin/auth/changepass');?>">
				<i class="fa fa-cog"></i>
					<?php echo lang_key("change_password") ?> </a>
				</li>
				<li>
				<a href="<?php echo site_url('admin/editprofile');?>">
				<i class="fa fa-wrench"></i>
					<?php echo lang_key("edit_profile") ?> </a>
				</li>
				<li>			
				<li class="divider"></li>
				<li>
				<a href="<?php echo site_url('admin/auth/logout')?>">
				<i class="fa fa-sign-out"></i>
					<?php echo lang_key("logout") ?> </a>
				</li>
				<li class="divider"></li>
			</ul>
		</li>
	</ul>

	<ul class="nav memento-nav pull-right language-sel">
		<li class="user-profile">
			<a data-toggle="dropdown" href="" class="user-menu dropdown-toggle">
			<i class="fa fa-globe"></i>
                        <span class="hhh" id=""><?php echo lang_key('Language');?></span>
			<i class="fa fa-caret-down"></i>
			</a>

			<?php

            $CI         = get_instance();
            $uri        = current_url();
            $curr_lang  = ($CI->uri->segment(1)!='')?$CI->uri->segment(1):default_lang();

            if($CI->uri->segment(1)=='')

                $uri .= '/'.default_lang();

            $CI->load->model('admin/system_model');

            $query = $CI->system_model->get_all_langs();

            echo '<ul class="dropdown-menu dropdown-navbar" id="user_menu2">';

            $url = $uri;

            foreach ($query->result() as $lang) {   

                $uri = str_replace('/'.$curr_lang.'/','/'.$lang->short_name.'/',$url);

                $sel = ($curr_lang==$lang->short_name)?'active':'';

                echo '<li class="'.$sel.'"><a href="'.$uri.'">'.ucwords($lang->lang).'</a></li>';

            }
            echo '</ul>';
            ?>
		</li>
	</ul>

	<ul class="nav memento-nav pull-right visit-site">
		<li>
			<a href="<?php echo site_url();?>">
				<i class="fa fa-laptop"></i>
				<?php echo lang_key("visit_site") ?>
			</a>
		</li>
	</ul>
</div>