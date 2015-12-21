    <footer id="footer" class="top-space OS_pad_top">

        <div class="footer1">
            <div class="container">
                <div class="row">
                    
                    <div class="col-md-4 widget">
                        <?php render_widgets('footer_first_column');?>
                    </div>

                    <div class="col-md-4 widget">
                        <?php render_widgets('footer_second_column');?>
                    </div>

                    <div class="col-md-4 widget">
                        <?php render_widgets('footer_third_column');?>
                    </div>

                </div> <!-- /row of widgets -->
            </div>
        </div>

        <div class="footer2">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 widget">
                        <div class="widget-body">
                            <p class="simplenav">
                                <a href="<?php echo site_url();?>"><?php echo lang_key('Home_Title');?></a> | 
                                <a href="<?php echo site_url('en/agents');?>"><?php echo lang_key('Agents');?></a> |
                                <a href="<?php echo site_url('show/about');?>"><?php echo lang_key('About Us');?></a> |
                                <a href="<?php echo site_url('show/contact');?>"><?php echo lang_key('Contact Us');?></a> |
                                <b><a href="<?php echo site_url('account/signup');?>"><?php echo lang_key('Sign up!');?></a></b>
                            </p>
                    </div>
                    </div>
                    <div class="col-md-6 widget">
                        <div class="widget-body">
                            <p class="text-right">
                                  @<?php echo lang_key(translate(get_settings('site_settings','footer_text','copyright@')));?>
                                | <?php echo lang_key('Powered by');?> <a href="http://boloin.com/" target="_blank"><?php echo lang_key('Boloin.com');?></a>
                            </p>
                        </div>
                    </div>
                </div> <!-- /row of widgets -->
                
            </div>
        </div>

    </footer>
    <a href="#" class="scrollToTop">Top</a>
    <style type="text/css">
    .scrollToTop{
        width:50px; 
        height:40px;
        padding:10px; 
        text-align:center; 
        background: whiteSmoke;
        font-weight: bold;
        color: #444;
        text-decoration: none;
        position:fixed;
        top:75px;
        right:10px;
        display:none;
        background: #222;
        opacity: .9;
        color: #fff;
        border-radius: 4px;
    }
    .scrollToTop:hover{
        text-decoration:none;
    }
    </style> 

    <script type="text/javascript">
     jQuery(document).ready(function(){
    
        //Check to see if the window is top if not then display button
        jQuery(window).scroll(function(){
            if (jQuery(this).scrollTop() > 150) {
                jQuery('.scrollToTop').css('top',jQuery(window).height()-70);                
                jQuery('.scrollToTop').fadeIn();
            } else {
                jQuery('.scrollToTop').fadeOut();
            }
        });
        
        //Click event to scroll to top
        jQuery('.scrollToTop').click(function(){
            jQuery('html, body').animate({scrollTop : 0},800);
            return false;
        });
    
    });
    </script>