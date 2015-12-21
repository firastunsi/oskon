<div class="widget">

    <h2 class="recent-grid"><i class="fa fa-home"></i>&nbsp;<?php echo lang_key('mortgage_calculation')?></h2>
    <div class="well">
        <form class="mortgage-form">

            <label><?php echo lang_key('property_price');?>*</label>
            <input type="text" id="" name="price"  class="price tb form-control" value=""  />
            <div style="margin:5px 0"></div>

            <label><?php echo lang_key('down_payment')?>*</label>
            <input type="text" id="" name="down-payment" class="down-payment tb form-control" value="" />
            <div style="margin:5px 0"></div>

            <label><?php echo lang_key('term_in_years')?>*</label>
            <input type="text" id="" name="years" class="years tb form-control" value="" />
            <div style="margin:5px 0"></div>

            <label><?php echo lang_key('annual_interest')?> *</label>
            <input type="text" id="" name="rate" class="rate tb form-control" value="" />
            <div style="margin:5px 0"></div>

            <label><?php echo lang_key('installments_per_year')?>*</label>
            <input type="text" id="" name="peryear" class="peryear tb form-control" value="" />
            <div style="margin:5px 0"></div>

            <div style="margin:15px 0">
            <input  type="reset" value="Reset" class="btn btn-warning" style="width:100px" />&nbsp;&nbsp;
            <input type="submit" value="Calculate" class="b js-calculate-button btn btn-warning"  style="width:100px"/>
            </div>

            <div class="clearfix"></div>
           <label><?php echo lang_key('monthly_payment')?></label>
           <input type="text" id="" class="monthly tb form-control" value="" />
        </form>
    </div>

</div>

<script type="text/javascript">

    function calculation(price, down, term, rate, peryear){
        if(rate<=0)
        {
            loan = price - down;
            //rate = (rate/100) / 12;
            month = term * peryear;
            payment = ((loan / month));
            payment = payment.toFixed(2);
        }
        else
        {
            loan = price - down;
            rate = (rate/100) / 12;
            month = term * peryear;
            payment = (((loan * rate / (1 - Math.pow(1+rate,(-1*month)))) * 100) / 100); 
            payment = payment.toFixed(2);           
        }
        return payment;
    }
    $(document).ready(function(){

        $('.mortgage-form').submit(function(e){
            e.preventDefault();
            var msg = '';
            if(isNaN($(this).find('.price').val())){
                msg += "property price will be only number\n";
            }
            if(isNaN($(this).find('.down-payment').val())){
                msg += "Down payment will be only number\n";
            }
            if(isNaN($(this).find('.years').val())){
                msg += "Term in years will be only number\n";
            }
            if(isNaN($(this).find('.rate').val())){
                msg += "Annual interest  will be only number\n";
            }
            if(isNaN($(this).find('.peryear').val())){
                msg += "Installments per year will be only number\n";
            }

            if(msg==''){
                var price,down_payment,years,rate,peryear;

                price= $(this).find('.price').val();
                down_payment = $(this).find('.down-payment').val();
                years = $(this).find('.years').val();
                rate = $(this).find('.rate').val();
                peryear = $(this).find('.peryear').val();


                if(price!='' && down_payment!='' && years!='' && rate!='' && peryear!='')
                {
                    var monthly_calculation = calculation(price,down_payment,years,rate,peryear);
                    if(monthly_calculation !=''){
                        $(this).find('.monthly').val(monthly_calculation);
                    }
                }
                else{
                    alert('please fill all fields.')
                }
            }
            else
            {
                alert(msg);
            }


        });
    });
</script>