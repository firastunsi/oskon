    <div class="col-md-9">
        <h1 class="recent-grid"><span class="OS_icon_f"><i class="fa fa-user"></i></span>&nbsp;<?php echo lang_key('DBC_ABOUT'); ?></h1>
        
        <?php $curr_lang = ($this->uri->segment(1) != '') ? $this->uri->segment(1) : 'en';?>
        <?php if ($curr_lang == 'en'):?>
             <p>
                 Oskon is a map based real estate search engine that provides you with an easy and quick platform to search for, sell or buy your desired property, connecting the seller and the buyer directly without the need of a middleman, with the map based search, Oskon gives you the exact location, the area and the owners information saving you time and effort! You can even customize your search with the advanced filters to narrow it down to your perfect choice…
             </p>
        <?php else:?>
            <p>
                اسكن هو محرك بحث عقاري قائم على أساس تحديد الموقع على الخريطة والذي يوفر لك منصة سهلة وسريعة للبحث عن , بيع أو شراء العقارات المطلوبة، مما يربط بين البائع والمشتري مباشرة من دون الحاجة إلى وسيط، مع خاصية البحث القائم على أساس تحديد الموقع على الخريطة ، اسكن يحدد لك المكان والمنطقة, بالاضافة الى معلومات المالك مما يوفر لك الوقت والجهد! يمكنك حتى تخصيص بحثك مع شروط البحث المتقدمة للحصول على اختيارك الأمثل ...
            </p>
        <?php endif;?>
    </div>
    <!--DETAILS SUMMARY-->
    <div class="col-md-3 ">
        <?php render_widgets('right_bar_all_agents'); ?>
    </div>
</div>