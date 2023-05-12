<?php 
$this->load->view('front/common/header');
?>
<style>
	.cpm_log_frm_s_dtll_box{
	display: block;
    width: 100%;
    padding: 6px 17px 0px;
    border-radius: 10px;
    margin-bottom: 1px;
    background-color: rgba(0,0,0,0.1);
	}
	.cpm_log_frm_s_dtll{
	display: block;
	width:100%;
	margin-bottom: 10px;
	/* border: 1px solid #ffffff40; */
	/* padding: 13px 15px; */
	border-radius: 8px;
	}
	.cpm_log_frm_s_lbl_small{
	display: inline-block;
	font-size: 12px;
	color: rgb(255 255 255 / 43%);
	font-weight: 400;
	line-height: 1.3;


	}
	.cpm_log_frm_s_lbl_biggg{
	display: inline-block;
	font-size: 12px;
	color:#fff;
	font-weight: 500;
	padding-left: 15px;
	line-height: 1.3;

	}
body:not(.cpm_dark_mode) .cpm_log_frm_s_lbl_small{color:rgba(0,0,0,0.4);}
body:not(.cpm_dark_mode) .cpm_log_frm_s_lbl_biggg{color:rgba(0,0,0,1);}
body:not(.cpm_dark_mode) .cpm_log_frm_s_dtll_box{background-color: #f9f9f9}
@media (min-width: 600px){
	.cpm_depo_tabl_s_li_out:hover .cpm_dep_stat {
    right: 20%;
}
	}
@media (max-width: 600px){
	.cpm_dep_stat_txt {
    width: 100%;
}}
</style>


					<div class=" cpm_mdl_cnt">
						<div class="container">
							<div class="row ">
								<div class="col-lg-12">
									<div class="cpm_hd_text  text-center">Withdrawal Crypto To <span>Fiat</span></div>
								   
									<div class="cpm_dep_out cpm_with_out ">
										<?php 
			                                $action = '';
			                                $attributes = array('id'=>'withdrawcoin','autocomplete'=>"off",'class'=>'deposit_form'); 
			                                echo form_open($action,$attributes); ?>
									  
										<div class="cpm_dep_body">
						
											<div class="row">
												<div class="col-lg-6 col-md-6 col-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Asset</div>
													   
														<select class="cpm_log_frm_s_input" name="ids" onChange="change_coin(this)">
															<?php
				                                             if(count($currency) > 0)
				                                             {
				                                               foreach ($currency as $currencys) 
				                                             		{
				                                             			if($currencys->type!='fiat') {
				                                                ?>
														<option value="<?php echo $currencys->id.'_'.$currencys->type.'_'.$wallet['Exchange AND Trading'][$currencys->id].'_'.$currencys->currency_symbol;?>" <?=($sel_currency->id == $currencys->id)?'selected':''?>>
				                                              <?=$currencys->currency_symbol?>
				                                              </option>
				                                              <?php } } } ?>
						
														</select>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Fiat</div>


														<input type="hidden" name="fiat_sym" id="fiat_sym" value="<?php echo  $defaultfiat->currency_symbol;?>" />
													   
														<select class="cpm_log_frm_s_input" onChange="change_fiat(this)" id="fiat_currency" name="fiat_currency">
															<?php
				                                             if(count($currency) > 0)
				                                             {
				                                               foreach ($currency as $fiats) 
				                                             		{
				                                                	if($fiats->type=='fiat') {
				                                                ?>
																<option value="<?php echo $fiats->id;?>">
				                                             	 <?=$fiats->currency_symbol?>
				                                              </option>
				                                              <?php } } } ?>
						
														</select>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-12">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Withdrawal Asset Amount</div>
														<input type="text" id="amount" name="amount" onkeyup="calculate();" class="cpm_log_frm_s_input" >
														<div class="cpm_log_frm_s_otp_btn"><?=$sel_currency->currency_symbol;?></div>
														<!-- <div class="error">Enter Correct Amount</div> -->
												
													</div>
												</div>
												<div class="col-lg-6 col-md-6 col-12">
													<div class="cpm_log_frm_s ">
														<div class="cpm_log_frm_s_lbl">Withdrawal Fiat Amount</div>
														<input type="text" name="fiat_amount" readonly id="fiat_amount" class="cpm_log_frm_s_input">
														<div class="cpm_log_frm_s_otp_btn fiatcur">USD</div>
													</div>
												</div>
												<div class="col-lg-12">
													<div class="cpm_dep_ftr">
														<div class="row">
															<div class="col-6">
															<div class="cpm_dep_ftr_h1">
																Total balance
																<span><?=$user_balance;?> <?=$sel_currency->currency_symbol;?></span>
									
															</div>
									
															</div>
															<div class="col-6">
																<div class="cpm_dep_ftr_h1">
																	Balance in USD
																<span>$ <?=$balance_in_usd;?></span>
										
																</div>
															</div>
															</div>
									
									
													</div>
												</div>
												</div>

					<div class="cpm_hd_text text-center mt-2 mb-2">Bank <span>Details</span></div>
						<div class="cpm_log_frm_s_dtll_box">
												<div class="row">
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Account Holder Name</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_account_name"><?=$defaultbank->bank_account_name;?></div>
															
														  
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Account Number</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_account_number"> <?=$defaultbank->bank_account_number;?> </div>
														</div>
													</div>
												 
							
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Bank Swift / Ifsc</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_swift"><?=$defaultbank->bank_swift;?></div>
														</div>
													</div>
													<!-- <div class="col-lg-3 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">BIC</div>
															<div class="cpm_log_frm_s_lbl_biggg">58376987</div>
														</div>
													</div> -->
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Bank Name</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_name"><?=$defaultbank->bank_name;?></div>
														</div>
													</div>
													
													
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Bank Address</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_address"><?=$defaultbank->bank_address;?></div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Bank City</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_city"><?=$defaultbank->bank_city;?></div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Country</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_country">Canada</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="cpm_log_frm_s_dtll">
															<div class="cpm_log_frm_s_lbl_small">Postal Code</div>
															<div class="cpm_log_frm_s_lbl_biggg" id="bank_postal"><?=$defaultbank->bank_postalcode;?></div>
														</div>
													</div>
												</div></div>
												<div class="row align-items-center ">
												<div class="col-12">
													<h6 class="cpm_with_d_fe">Withdrawal Fees<span id="fees_p">0.00000</span> <p id="fees_c"></p> </h6>
													<!-- <h6 class="cpm_with_d_fe">Withdrawal Fees<span id="fees_c">0.00000</span></h6> -->
												</div>
												<div class="col-12">
													<button class="cpm_log_frm_btn" name="withdrawcoinfiat" type="submit"><i class="fal fa-check"></i> Withdraw</button>
												</div>
												</div>
										</div>
										<?php
					                        echo form_close();
					                     ?>
									</div>
								</div>



								<div class="col-lg-12">
						<div class="cpm_hd_text   text-center">Withdrawal <span>History</span></div>
							<div class="cpm_depo_tabl_s">
								<div class="cpm_depo_tabl_s_li_out cpm_depo_tabl_s_li_hds">
									<div class="cpm_depo_tabl_s_li">Coins</div>
									<div class="cpm_depo_tabl_s_li">Transaction Id</div>
									<div class="cpm_depo_tabl_s_li">Date</div>
									<div class="cpm_depo_tabl_s_li">Volume</div>
								</div>
								<?php
								 if(isset($withdraw_history) && !empty($withdraw_history))
			                        {
			                        	$a=0;
			                        	foreach($withdraw_history as $withrdaw)
			                          {

			                          	$cur_details = getcryptocurrencydetail($withrdaw->currency_id);
			                          	if(empty($withrdaw->transaction_id))
			                                {
			                                  $transaction_id = '-';
			                                }
			                                else
			                                {
			                                  $transaction_id = $withrdaw->transaction_id;
			                                } 


								?>
								<input type="hidden" name="hidden" id="copy" value="<?=$withrdaw->crypto_address;?>">
								<div class="cpm_depo_tabl_s_li_out ">
									<div class="cpm_depo_tabl_s_li">
										<div class="cpm_ast_hd_set">
											<img src="<?php echo $cur_details->image;?>" class="cpm_ast_ico"> 
											<div class="cpm_ast_h1"><?php echo $cur_details->currency_symbol;?></div>
											<div class="cpm_ast_h2"><?php echo $cur_details->currency_name;?></div></div>
										</div>

										
										<div class="cpm_depo_tabl_s_li"><div class="cpm_with_copy_tran"><i class="fal fa-file cpm_with_copy_ico" onclick="copy_function()"></i> <?php echo $transaction_id;?> </div> </div>

										<div class="cpm_depo_tabl_s_li"><?php echo $withrdaw->datetime;?></div>
										<div class="cpm_depo_tabl_s_li"><?php echo number_format($withrdaw->amount,8);?></div>
										<div class="cpm_dep_stat ">Completed <div class="cpm_dep_stat_txt">Address : <?=$withrdaw->crypto_address;?></div></div>
									</div>
								<?php } } ?>



												</div>
								</div>
							</div>
						</div>
					</div>




<?php 
$this->load->view('front/common/footer');
?>
<script type="text/javascript">


var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';


$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    if (options.type.toLowerCase() == 'post') {
        options.data += '&'+csrfName+'='+$("input[name="+csrfName+"]").val();
        if (options.data.charAt(0) == '&') {
            options.data = options.data.substr(1);
        }
    }
});

$( document ).ajaxComplete(function( event, xhr, settings ) {
    if (settings.type.toLowerCase() == 'post') {
        $.ajax({
            url: front_url+"get_csrf_token", 
            type: "GET",
            cache: false,             
            processData: false,      
            success: function(data) {
                    var dataaa = $.trim(data);
                 $("input[name="+csrfName+"]").val(dataaa);
            }
        });
    }
});


$.validator.addMethod("noSpace", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "No space please and don't leave it empty");

        $("#withdrawcoin").validate({
          rules: {
                  
                  amount: {
                    required: true,
                    number:true
                  },
                  fiat_amount: {
                    required: true,
                    number:true
                  }
                },
          messages: {
                address: {
                  required: "Please enter address",
                   number: "Invalid Amount"
                },
                amount: {
                  required: "Please enter Amount",
                  number: "Invalid Amount"
                }
              },
             highlight: function (element) {
			$(element).parent().addClass('fail_vldr')
			},
			unhighlight: function (element) {
			$(element).parent().removeClass('fail_vldr');
			},
              submitHandler: function(form) 
        { 

    var fees_type = '<?php echo $fees_type;?>';
    var fees = '<?php echo $fees;?>';


    var amount = $('#amount').val();

    if(fees_type=='Percent'){
        var fees_p = ((parseFloat(amount) * parseFloat(fees))/100);
        var amount_receive = parseFloat(amount) - parseFloat(fees_p);
    }
    else{
        var fees_p = fees;
        var amount_receive = parseFloat(amount) - parseFloat(fees_p);
    }
    if(parseFloat(amount_receive)<=0){
      tata.warn({ message: 'Please enter valid amount' });
      return false;
    }
    else if(parseFloat(amount)<=parseFloat(fees_p)){
     
      tata.warn({ message: 'Please enter valid amount' });
      return false;
    }
    else{
      form.submit();
    }
        }     
});





function copy_function() 
{
	var copyText = document.getElementById('copy');
	copyText.select();
	document.execCommand("COPY");
	tata.info('CPM! ','Copied');
}
function change_coin(sel)
{


var arr1 = sel.value;
var arr = arr1.split('_');
var currency_id = arr[0];
var type = arr[1];
var symbol = arr[3];
// console.log(symbol);
window.location.href = base_url+'crypto_fiatwithdraw/'+symbol;


}        	

function change_fiat(cur)
{

	var currency = cur.value;
	$.ajax({
        url: base_url+"change_bank",
        type: "POST",
        data: "currency_id="+currency,
        success: function(data) {
            var rest = jQuery.parseJSON(data);
            var res = rest['banks'];

			$('#bank_account_name').html(res.bank_account_name);
            $('#bank_account_number').html(res.bank_account_number);
            $('#bank_address').html(res.bank_address);
            $('#bank_city').html(res.bank_city);
            $('#bank_name').html(res.bank_name);
            $('#bank_postal').html(res.bank_postalcode);
            $('#bank_swift').html(res.bank_swift);
            $('#bank_country').html(rest['country']);
            $('.fiatcur').html(rest['symbol']);
			$('#fiat_sym').val(rest['symbol']);        
        }
    });

}


function calculate(){
    
    var fees_type = '<?php echo $fees_type;?>';
    var fees = '<?php echo $fees;?>';

    var amount = $('#amount').val();

    if(fees_type=='Percent'){
        var fees_p = ((parseFloat(amount) * parseFloat(fees))/100);
        var amount_receive = parseFloat(amount) - parseFloat(fees_p);
    }
    else{
        var fees_p = fees;
        var amount_receive = parseFloat(amount) - parseFloat(fees_p);
    }
    $('#fees_p').html(fees_p);
    if(amount_receive<=0){
      $('.error').html('Please enter valid amount');
      $('.cpm_log_frm_s_lbl').addClass('fail_vldr');
      $('#amount_receive').html('0');
    }
    else{
      $('.error').html('');
      $('.cpm_log_frm_s_lbl').removeClass('fail_vldr');
      $('#amount_receive').html(amount_receive);
  }

  	var crypto = '<?php echo $sel_currency->id;?>';
  	var fiat   = $('#fiat_currency').val();  

	$.ajax({
        url: base_url+"currency_convert",
        type: "POST",
        data: "crypto="+crypto+'&fiat='+fiat,
        success: function(data) {
            var rest = jQuery.parseJSON(data);
            var fiat = $('#fiat_sym').val();
            if(fiat!='')
            {
            	var amount = rest[fiat];
            	var crypto_amt = $('#amount').val();

            	var resu = crypto_amt * amount;
            	$('#fiat_amount').val(resu.toFixed(2));

            	var conver_fee = fees_p * amount;
            	$('#fees_c').html('( '+conver_fee.toFixed(2)+' '+fiat+' )');


            }


        }
    });




}

$(document).ready(function() {

	$("#fiat_amount").prop("readonly",true);


});



</script>