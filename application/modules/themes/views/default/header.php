<!-- Fixed navbar -->

    <div class="navbar navbar-inverse navbar-fixed-top headroom" >

        <div class="container">

            <div class="navbar-header">

                <!-- Button for smallest screens -->

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>

                <a class="navbar-brand" href="<?php echo site_url();?>"><img src="<?php echo get_site_logo();?>" alt="Logo" style="height:50px"></a>

            </div>

            <div class="navbar-collapse collapse">



                <ul class="nav navbar-nav pull-right top-nav">

                

                        <?php
                            $CI = get_instance();
                            $CI->load->model('admin/page_model');
                            $CI->page_model->init();
                        ?>



                        <?php 
                            $alias = (isset($alias))?$alias:'';
                            foreach ($CI->page_model->get_menu() as $li) 
                            {
                                if($li->parent==0)
                                $CI->page_model->render_top_menu($li->id,0,$alias);
                            }
                        ?>



                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                                <?php echo lang_key('type');?> <b class="caret"></b>

                            </a>

                            <ul class="dropdown-menu">

                                <?php

                                $filter_options = array();

                                $this->load->config('realcon');
                                $custom_types = $this->config->item('property_types');
                                if(is_array($custom_types)) foreach ($custom_types as $key => $custom_type) {
                                    if($custom_type['title']=='DBC_TYPE_APARTMENT')
                                        $filter_options[$custom_type['title']] = 'apartment';
                                    else if($custom_type['title']=='DBC_TYPE_HOUSE')
                                        $filter_options[$custom_type['title']] = 'house';
                                    else if($custom_type['title']=='DBC_TYPE_LAND')
                                        $filter_options[$custom_type['title']] = 'land';
                                    else if($custom_type['title']=='DBC_TYPE_COMSPACE')
                                        $filter_options[$custom_type['title']] = 'com_space';
                                    else if($custom_type['title']=='DBC_TYPE_CONDO')
                                        $filter_options[$custom_type['title']] = 'condo';
                                    else if($custom_type['title']=='DBC_TYPE_VILLA')
                                        $filter_options[$custom_type['title']] = 'villa';
                                    else
                                      $filter_options[$custom_type['title']] = urlencode($custom_type['title']);
                                }

                                foreach ($filter_options as $k=>$v) {

                                ?>

                                <li class="<?php echo is_active_menu('show/type/'.$v);?>">

                                    <a href="<?php echo site_url('show/type/'.$v);?>">

                                        <i>&nbsp;</i><?php echo lang_key($k);?>

                                    </a>

                                </li>

                                <?php

                                }

                                ?>

                                <li style="border-bottom:1px solid #fff;height:0px;"></li>

                                <?php

                                $filter_options = array('DBC_PURPOSE_SALE'=>'sale',
                                    'DBC_PURPOSE_RENT'=>'rent',
                                    'DBC_PURPOSE_BOTH'=>'sale_rent');



                                foreach ($filter_options as $k=>$v) {

                                ?>

                                <li class="<?php echo is_active_menu('show/purpose/'.$v);?>">

                                    <a href="<?php echo site_url('show/purpose/'.$v);?>">

                                        <i>&nbsp;</i><?php echo lang_key($k);?>

                                    </a>

                                </li>

                                <?php

                                }

                                ?>

                            </ul>    

                        </li>    

                        <li class="dropdown">

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                                <i class="fa fa-globe"></i> <b class="caret"></b>

                            </a>



                            <?php

                            $CI         = get_instance();

                            $uri        = current_url();

                            $curr_lang  = ($CI->uri->segment(1)!='')?$CI->uri->segment(1):default_lang();



                            if($CI->uri->segment(1)=='')

                                $uri .= '/'.default_lang();



                            $CI->load->model('admin/system_model');

                            $query = $CI->system_model->get_all_langs();

                            echo '<ul class="dropdown-menu lang-menu">';

                            $url = $uri;

                            foreach ($query->result() as $lang) {   

                                $uri = str_replace('/'.$curr_lang,'/'.$lang->short_name,$url);

                                $sel = ($curr_lang==$lang->short_name)?'active':'';

                                echo '<li class="'.$sel.'"><a href="'.$uri.'">'.ucwords($lang->lang).'</a></li>';

                            }

                            echo '</ul>';



                            ?>

                        </li>

                        
                    <?php if(!is_loggedin()){?>

                    <?php if(get_settings('realestate_settings','enable_signup','Yes')=='Yes'){?>

                    <li><a class="btn" data-toggle="modal" href="#myModal"><?php echo lang_key('sign_in'); ?></a></li>

                    <li><a class="btn" href="<?php echo site_url('account/signup');?>"><?php echo lang_key('sign_up'); ?></a></li>

                    <?php }?>

                    <?php }else{?>

                        <?php if(!is_admin()){?>

                        <li><a class="btn" href="<?php echo site_url('admin/realestate/allestatesagent');?>"><?php echo lang_key('agent_panel'); ?></a></li>

                        <?php }else{?>

                        <li><a class="btn" href="<?php echo site_url('admin');?>"><?php echo lang_key('admin_panel'); ?></a></li>

                        <?php }?>

                    <li><a class="btn" href="<?php echo site_url('account/logout');?>"><?php echo lang_key('logout'); ?></a></li>

                    <?php }?>

                </ul>

            </div><!--/.nav-collapse -->

        </div>

    </div> 

    <!-- /.navbar -->