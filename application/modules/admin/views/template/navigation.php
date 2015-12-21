<div id="sidebar" class="navbar-collapse collapse">


    <ul class="nav nav-list">
        <?php if (is_admin()) { ?>

            <!--<li class="active"> HIGHLIGHTS MENU-->
            <li class="<?php echo is_active_menu('admin/index'); ?>">
                <a href="<?php echo site_url('admin/index'); ?>">
                    <i class="fa fa-dashboard"></i>
                    <span><?php echo lang_key("dashboard"); ?></span>
                </a>
            </li>
            <li class="<?php echo is_active_menu('admin/themes'); ?>">


                <a href="<?php echo site_url('admin/themes'); ?>">


                    <i class="fa fa-desktop"></i>


                    <span><?php echo lang_key("themes"); ?></span>


                </a>


            </li>





        <?php } ?>

        <li class="<?php echo is_active_menu('admin/realestate/'); ?>">


            <a href="#" class="dropdown-toggle">


                <i class="fa fa-plus-circle"></i>


                <span><?php echo lang_key("real_estate"); ?></span>


                <b class="arrow fa fa-angle-right"></b>


            </a>


            <ul class="submenu">


                <?php if (is_admin()) { ?>

                    <li class="<?php echo is_active_menu('admin/realestate/allestates'); ?>"><a
                            href="<?php echo site_url('admin/realestate/allestates'); ?>">


                             <?php echo lang_key('All Estates'); ?>


                        </a>


                    </li>

                    <li class="<?php echo is_active_menu('admin/realestate/newestate'); ?>"><a
                            href="<?php echo site_url('admin/realestate/newestate'); ?>">

                             <?php echo lang_key('New estate'); ?>

                        </a>

                    </li>


                <?php } else { ?>

                    <li class="<?php echo is_active_menu('admin/realestate/allestatesagent'); ?>"><a
                            href="<?php echo site_url('admin/realestate/allestatesagent'); ?>">


                             <?php echo lang_key('All Estates'); ?>


                        </a>


                    </li>

                    <li class="<?php echo is_active_menu('admin/realestate/newestate'); ?>"><a
                            href="<?php echo site_url('admin/realestate/newestate'); ?>">

                             <?php echo lang_key('New Estate'); ?>

                        </a>

                    </li>

                    <li class="<?php echo is_active_menu('account/renew'); ?>"><a
                            href="<?php echo site_url('account/renew'); ?>">


                            <?php echo lang_key('update_package'); ?>


                        </a>


                    </li>


                <?php } ?>

               <?php if (is_admin()) { ?>
                <li class="<?php echo is_active_menu('admin/realestate/emailtracker'); ?>"><a
                        href="<?php echo site_url('admin/realestate/emailtracker'); ?>">
                        <?php echo lang_key('email_tracker'); ?>
                    </a>
                </li>
                <li class="<?php echo is_active_menu('admin/realestate/bulkemailform'); ?>"><a
                        href="<?php echo site_url('admin/realestate/bulkemailform'); ?>">
                        <?php echo lang_key('bulk_email'); ?>
                    </a>
                </li>
                <?php } ?>

                <!--li class="<?php echo is_active_menu('admin/realestate/activeposts'); ?>"><a href="<?php echo site_url('admin/realestate/activeposts'); ?>">



                          Active estates



                      </a>



                  </li>





                  <li class="<?php echo is_active_menu('admin/realestate/pendingposts'); ?>"><a href="<?php echo site_url('admin/realestate/pendingposts'); ?>">



                          Pending estates



                      </a>



                  </li>



                  <li class="<?php echo is_active_menu('admin/realestate/allreported'); ?>"><a href="<?php echo site_url('admin/realestate/allreported'); ?>">



                          Reported estates



                      </a>



                  </li-->









                <?php if (is_admin()) { ?>



                    <li class="<?php echo is_active_menu('admin/realestate/locations'); ?>"><a
                            href="<?php echo site_url('admin/realestate/locations'); ?>">


                            <?php echo lang_key('locations'); ?>


                        </a>


                    </li>





                    <li class="<?php echo is_active_menu('admin/realestate/facilities'); ?>"><a
                            href="<?php echo site_url('admin/realestate/facilities'); ?>">


                            <?php echo lang_key('amenities'); ?>


                        </a>


                    </li>


                    <li class="<?php echo is_active_menu('admin/realestate/distance_fields'); ?>"><a
                            href="<?php echo site_url('admin/realestate/distance_fields'); ?>">


                            <?php echo lang_key('distance_fields'); ?>


                        </a>


                    </li>



                    <li class="<?php echo is_active_menu('admin/realestate/realestatesettings'); ?>"><a
                            href="<?php echo site_url('admin/realestate/realestatesettings'); ?>">


                            <?php echo lang_key('site_settings'); ?>


                        </a>


                    </li>



                    <li class="<?php echo is_active_menu('admin/realestate/paypalsettings'); ?>"><a
                            href="<?php echo site_url('admin/realestate/paypalsettings'); ?>">


                            <?php echo lang_key('paypal_settings'); ?>


                        </a>


                    </li>



                    <li class="<?php echo is_active_menu('admin/realestate/payments'); ?>"><a
                            href="<?php echo site_url('admin/realestate/payments'); ?>">


                            <?php echo lang_key('payment_history'); ?>


                        </a>


                    </li>



                    <li class="<?php echo is_active_menu('admin/realestate/bannersettings'); ?>"><a
                            href="<?php echo site_url('admin/realestate/bannersettings'); ?>">


                            <?php echo lang_key('Oskon Settings');?>


                        </a>


                    </li>



                <?php } ?>

            </ul>


        </li>


        <li class="<?php echo is_active_menu('admin/editprofile'); ?>">


            <a href="<?php echo site_url('admin/editprofile'); ?>">


                <i class="fa fa-user"></i>


                <span><?php echo lang_key('profile'); ?></span>


            </a>


        </li>



        <?php if (is_admin()) { ?>



            <li class="<?php echo is_active_menu('admin/package/'); ?>">


                <a href="#" class="dropdown-toggle">


                    <i class="fa fa-bars"></i>


                    <span><?php echo lang_key('packages'); ?></span>


                    <b class="arrow fa fa-angle-right"></b>


                </a>


                <ul class="submenu">


                    <li class="<?php echo is_active_menu('admin/package/all'); ?>"><a
                            href="<?php echo site_url('admin/package/all'); ?>">


                            <?php echo lang_key('all_packages'); ?>


                        </a>


                    </li>

                    <?php $urls = array('admin/package/addpackage', 'admin/package/newpackage'); ?>

                    <li class="<?php echo is_active_menu($urls); ?>"><a
                            href="<?php echo site_url('admin/package/newpackage'); ?>">


                            <?php echo lang_key('create_new_package'); ?>


                        </a>


                    </li>


                </ul>

            </li>





            <li class="<?php echo is_active_menu('admin/users'); ?>">


                <a href="<?php echo site_url('admin/users'); ?>">


                    <i class="fa fa-users"></i>


                    <span><?php echo lang_key('Agents / Users'); ?></span>


                </a>


            </li>







            <li class="<?php echo is_active_menu('admin/widgets/'); ?>">


                <a href="#" class="dropdown-toggle">


                    <i class="fa fa-bars"></i>


                    <span><?php echo lang_key('widgets'); ?></span>


                    <b class="arrow fa fa-angle-right"></b>


                </a>


                <ul class="submenu">


                    <li class="<?php echo is_active_menu('admin/widgets/all'); ?>"><a
                            href="<?php echo site_url('admin/widgets/all'); ?>">


                            <?php echo lang_key('all_widgets'); ?>


                        </a>


                    </li>


                    <li class="<?php echo is_active_menu('admin/widgets/widgetpositions'); ?>"><a
                            href="<?php echo site_url('admin/widgets/widgetpositions'); ?>">


                            <?php echo lang_key('widget_positions'); ?>


                        </a>


                    </li>


                </ul>


            </li>







            <li class="<?php echo is_active_menu('admin/plugins/all'); ?>">


                <a href="#" class="dropdown-toggle">


                    <i class="fa fa-code-fork"></i>


                    <span><?php echo lang_key('Plugins');?></span>


                    <b class="arrow fa fa-angle-right"></b>


                </a>


                <ul class="submenu">


                    <li class="<?php echo is_active_menu('admin/plugins/all'); ?>"><a
                            href="<?php echo site_url('admin/plugins/all'); ?>"><?php echo lang_key('All Plugins');?></a></li>



                    <?php $plugins = get_plugins(); ?>



                    <?php foreach ($plugins->result() as $row) {


                        $plugin = json_decode($row->plugin);



                        ?>



                        <li><a href="<?php echo site_url($plugin->access_url); ?>"><?php echo $plugin->name; ?></a></li>



                    <?php } ?>


                </ul>


            </li>







            <li class="<?php echo is_active_menu('admin/plugins/index'); ?>">


                <a href="<?php echo site_url('admin/plugins/index'); ?>">


                    <i class="fa fa-cloud-upload"></i>


                    <span><?php echo lang_key('upload'); ?></span>


                </a>


            </li>




            <!--<li class="active"> OPENS SUBMENU BY DEFAULT-->



            <li class="<?php echo is_active_menu('admin/blog/'); ?>">


                <a href="#" class="dropdown-toggle">


                    <i class="fa fa-file-o"></i>


                    <span><?php echo lang_key('blog_news_article'); ?></span>


                    <b class="arrow fa fa-angle-right"></b>


                </a>


                <ul class="submenu">


                    <!--<li class="active"> HIGHLIGHTS SUBMENU-->


                    <li class="<?php echo is_active_menu('admin/blog/all'); ?>"><a
                            href="<?php echo site_url('admin/blog/all'); ?>"><?php echo lang_key('all_posts'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/blog/manage'); ?>"><a
                            href="<?php echo site_url('admin/blog/manage'); ?>"><?php echo lang_key('new_post'); ?></a></li>


                </ul>


            </li>



            <!--<li class="active"> OPENS SUBMENU BY DEFAULT-->



            <li class="<?php echo is_active_menu('admin/page/'); ?>">


                <a href="#" class="dropdown-toggle">


                    <i class="fa fa-file-o"></i>


                    <span><?php echo lang_key('pages_and_menu'); ?></span>


                    <b class="arrow fa fa-angle-right"></b>


                </a>


                <ul class="submenu">


                    <!--<li class="active"> HIGHLIGHTS SUBMENU-->


                    <li class="<?php echo is_active_menu('admin/page/all'); ?>"><a
                            href="<?php echo site_url('admin/page/all'); ?>"><?php echo lang_key('all_pages'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/page/index'); ?>"><a
                            href="<?php echo site_url('admin/page/index'); ?>"><?php echo lang_key('new_page'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/page/menu'); ?>"><a
                            href="<?php echo site_url('admin/page/menu'); ?>"><?php echo lang_key('menu'); ?></a></li>


                </ul>


            </li>















            <!--<li class="active"> OPENS SUBMENU BY DEFAULT-->



            <li class="<?php echo is_active_menu('admin/system'); ?>">


                <a href="#" class="dropdown-toggle">


                    <i class="fa fa-cog"></i>


                    <span><?php echo lang_key('system'); ?></span>


                    <b class="arrow fa fa-angle-right"></b>


                </a>


                <ul class="submenu">


                    <!--<li class="active"> HIGHLIGHTS SUBMENU-->


                    <li class="<?php echo is_active_menu('admin/system/allbackups'); ?>"><a
                            href="<?php echo site_url('admin/system/allbackups'); ?>"><?php echo lang_key('manage_backups'); ?></a></li>

                    <li class="<?php echo is_active_menu('admin/system/smtpemailsettings'); ?>"><a
                            href="<?php echo site_url('admin/system/smtpemailsettings'); ?>"><?php echo lang_key('smtp_email_settings'); ?></a>
                    </li>


                    <!--li class="<?php echo is_active_menu('admin/system/newlang'); ?>"><a href="<?php echo site_url('admin/system/newlang'); ?>">New Language</a></li-->


                    <li class="<?php echo is_active_menu('admin/system/editlang'); ?>"><a
                            href="<?php echo site_url('admin/system/editlang'); ?>"><?php echo lang_key('manage_languages'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/system/translate'); ?>"><a
                            href="<?php echo site_url('admin/system/translate'); ?>"><?php echo lang_key('auto_translate'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/system/emailtmpl'); ?>"><a
                            href="<?php echo site_url('admin/system/emailtmpl'); ?>"><?php echo lang_key('edit_email_text'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/system/sitesettings'); ?>"><a
                            href="<?php echo site_url('admin/system/sitesettings'); ?>"><?php echo lang_key('site_settings'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/system/settings'); ?>"><a
                            href="<?php echo site_url('admin/system/settings'); ?>"><?php echo lang_key('admin_settings'); ?></a></li>


                    <li class="<?php echo is_active_menu('admin/system/generatesitemap'); ?>"><a
                            href="<?php echo site_url('admin/system/generatesitemap'); ?>"><?php echo lang_key('sitemap'); ?></a></li>


                </ul>


            </li>



        <?php } ?>


    </ul>


    <div id="sidebar-collapse" class="visible-lg">


        <i class="fa fa-angle-double-left"></i>


    </div>


</div>