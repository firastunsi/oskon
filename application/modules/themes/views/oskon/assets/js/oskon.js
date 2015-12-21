jQuery(document).ready(function($) {
    
    var checked_id = '';
    var checked_value = '';
    
    // fill on load the category type as subs checked all or not
    $(function(){
        $('#ul_property_type li.category_type_chk input[type=checkbox]').each(function () {
            var found = 0;
            checked_id = $(this).attr('id');
            checked_value = $(this).attr('value');
            $('#ul_property_type li.sub_type_chk_'+checked_value+' input[type=checkbox]').each(function () {
                var sub_checked_id = $(this).attr('id');
                var isSubChecked = $("#"+sub_checked_id).is(':checked');
                if (isSubChecked == false){
                    found = 1;
                }
            });
            if (found == 1){
                $('#ul_property_type '+ "#"+checked_id ).prop('checked', false);
            }
            else{
                $('#ul_property_type '+ "#"+checked_id ).prop('checked', true);
            }
        });
        // check on all subs if all is checked so the main category should be checked
        
       
        var li_name_ids = '.sub_type_chk_'+checked_value;
        $(li_name_ids + ' input[type=checkbox]').each(function () {
            var checked_id = $(this).attr('id');
            var isChecked = $("#"+checked_id).is(':checked');
            if (isChecked == false){
                $('#ul_property_type '+ checked_id ).prop('checked', false);
                return false;
            }
            $('#ul_property_type '+ checked_id ).prop('checked', true);
        });
    });

    jQuery('li.fi_li1 a.lnk_list').live('click',function(event){
       var value = $(this).attr('data-value');
       $('ul.fi_ul2').slideUp( "fast", function() {});
       $('ul.fi_ul3').slideUp( "fast", function() {});
       $('#ul_'+value).show();
       return event.preventDefault();
    });
    
    
    jQuery('body').live('click',function(event){
       if($(event.target).attr('class') == "lnk_list" || $(event.target).attr('class') == "fi_ul2" || $(event.target).attr('class') == "fi_ul3")
            return;
        
       if($(event.target).closest('.fi_ul2').length || $(event.target).closest('.fi_ul3').length)
            return;             
       $('ul.fi_ul2').slideUp( "fast", function() {});
       $('ul.fi_ul3').slideUp( "fast", function() {});
    });
    
    jQuery('#ul_list_type li.fi_li2 input[type=\'checkbox\']').live('click',function(event){
        var checked_value = $(this).attr('value');
        var checked_id = $(this).attr('id');
        
        var isChecked = $("#"+checked_id).is(':checked');
        if (checked_value == '' && isChecked == true){
            $('#ul_list_type li.fi_li2 .css-checkbox').prop('checked', true);
            
        }
        else if (checked_value == '' && isChecked == false){
            $('#ul_list_type li.fi_li2 .css-checkbox').prop('checked', false);
        }
        else if (checked_value != '' && isChecked == false){
            $('#ul_list_type li.fi_li2 #purpose_sale_all').prop('checked', false);
        }
        else if (checked_value != '' && isChecked == true){
            var isChecked_1 = $("#purpose_sale_1").is(':checked');
            var isChecked_2 = $("#purpose_sale_2").is(':checked');
            if (isChecked_1 == true && isChecked_2 == true){
                $('#ul_list_type li.fi_li2 #purpose_sale_all').prop('checked', true);
            }   
        }
        
        
        
    });
    
    jQuery('#ul_property_type .category_type_chk input[type=\'checkbox\']').live('click',function(event){
        var category_value = $(this).attr('value');
        var checked_id = $(this).attr('id');
        var isChecked = $("#"+checked_id).is(':checked');
        
        if (isChecked == true){
            $('#ul_property_type .sub_type_chk_'+category_value + ' input[type=\'checkbox\']').prop('checked', true);
        }
        else{
            $('#ul_property_type .sub_type_chk_'+category_value + ' input[type=\'checkbox\']').prop('checked', false);
        }
        
        
    });
    
    jQuery('#ul_property_type .fi_li_lpad input[type=\'checkbox\']').live('click',function(event){
        var sub_value = $(this).attr('value');
        var sub_checked_id = $(this).attr('id');
        var category_value = $(this).attr('data');
        var isSubChecked = $("#"+sub_checked_id).is(':checked');
        var category_id = '#category_'+category_value;
        
        if (isSubChecked == false){
            $('#ul_property_type '+ category_id ).prop('checked', false);
        }
        else{
            // check on all subs if all is checked so the main category should be checked
            var li_name_ids = '.sub_type_chk_'+category_value;
            $(li_name_ids + ' input[type=checkbox]').each(function () {
                var checked_id = $(this).attr('id');
                var isChecked = $("#"+checked_id).is(':checked');
                if (isChecked == false){
                    $('#ul_property_type '+ category_id ).prop('checked', false);
                    return false;
                }
                $('#ul_property_type '+ category_id ).prop('checked', true);
            });
        }
        
        
    });

    
    jQuery('.OS_filter #budget_lnk').live('click',function(event){
        $('.min-box input[type="text"]').focus();
        $('#min_price_list').show();
        $('#max_price_list').hide();
        return event.preventDefault();
    });
    
    jQuery('.OS_filter #price_min').live('focus',function(event){
        $('#min_price_list').show();
        $('#max_price_list').hide();
    });
    
    jQuery('.OS_filter #price_max').live('focus',function(event){
        $('#min_price_list').hide();
        $('#max_price_list').show();
    });
    
    jQuery('.OS_filter #min_price_list li a').live('click',function(event){
        var min_price_value = $(this).attr('data');
        $('#price_min').val(min_price_value);
        $('#min_price_list').hide();
        $('#max_price_list').show();
        $('.max-box input[type="text"]').focus();
        return event.preventDefault();
    });
    
    jQuery('.OS_filter #max_price_list li a').live('click',function(event){
         var max_price_value = $(this).attr('data');
        $('#price_max').val(max_price_value);
        $('#ul_budget').slideUp( "fast", function() {});
        return event.preventDefault();
    });
    
    
    jQuery('.OS_r-map .OS_r-map-header a.pr-a').live('click',function(event){
        $('#filter_properties_results').show();
        $('#filter_agents_results').hide();
        $('.OS_r-map .OS_r-map-header a.pr-a').addClass('OS_activ-a');
        $('.OS_r-map .OS_r-map-header a.ag-a').removeClass('OS_activ-a');
        return event.preventDefault();
    });
    
     jQuery('.OS_r-map .OS_r-map-header a.ag-a').live('click',function(event){
        $('#filter_properties_results').hide();
        $('#filter_agents_results').show();
        $('.OS_r-map .OS_r-map-header a.ag-a').addClass('OS_activ-a');
        $('.OS_r-map .OS_r-map-header a.pr-a').removeClass('OS_activ-a');
        return event.preventDefault();
    });
    
    
    
     jQuery('.box4 a.OS_reset').live('click',function(event){
         var hist_purpose = getCookie('hist_purpose');
         //reset all filter controllers
         $('#ul_list_type li.fi_li2 .css-checkbox').prop('checked', false); 
         $('#ul_property_type  input[type=\'checkbox\']').prop('checked', false); 
         $('#price_min').val('');
         $('#price_max').val('')
         $('#ul_bedrooms  input[type=\'radio\']').prop('checked', false);
         $('#ul_bathrooms  input[type=\'radio\']').prop('checked', false);
         $('#hdn_agent').val('');
         
         // check if come from home page search or from featured/top listing
         var alias = $('#alias').val();
         if (alias == 'search_result'){
            if (hist_purpose == 'DBC_PURPOSE_SALE'){
                $('#ul_list_type li.fi_li2 #purpose_sale_1').prop('checked', true);
            }
            else if (hist_purpose == 'DBC_PURPOSE_RENT'){
                $('#ul_list_type li.fi_li2 #purpose_sale_2').prop('checked', true);
            }
            else{
                $('#ul_list_type li.fi_li2 #purpose_sale_all').prop('checked', true);
            }
         }
         else{
             //top/featured
             $('#ul_list_type li.fi_li2 #purpose_sale_all').prop('checked', true);
         }
         
         post_form();
         return event.preventDefault();
     });
     
     
     jQuery('.box4 a.OS_apply').live('click',function(event){
         post_form();
         return event.preventDefault();
     });
    
    
    
    function post_form(){
        if ($("#purpose_sale_all").is(':checked')){
            $('#filter_header_frm #purpose_sale').val('');
        }
        else if ($("#purpose_sale_1").is(':checked')){
            $('#filter_header_frm #purpose_sale').val($("#purpose_sale_1").val());
        }
        else if ($("#purpose_sale_2").is(':checked')){
           $('#filter_header_frm #purpose_sale').val($("#purpose_sale_2").val());
        }
        else{
             $('#filter_header_frm #purpose_sale').val('');
        }
        //remove comma form price
        var price_min = $('#price_min').val();
        var price_max = $('#price_max').val();
        var price_min_new = price_min.replace(",", "");
        var price_max_new = price_max.replace(",", "");
        $('#price_min').val(price_min_new);
        $('#price_max').val(price_max_new);
        
        $('#filter_header_frm').submit();
    }
    
    
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

function checkCookie() {
    var user = getCookie("username");
    if (user != "") {
        alert("Welcome again " + user);
    } else {
        user = prompt("Please enter your name:", "");
        if (user != "" && user != null) {
            setCookie("username", user, 365);
        }
    }
}

function find_map(){
    $('#searchfrm #purpose_sale').val('');
    $('#searchfrm #search_input').val('');
    $('#searchfrm').submit();
    return event.preventDefault();
}

