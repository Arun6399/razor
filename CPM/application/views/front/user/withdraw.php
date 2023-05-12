<?php 
$this->load->view('front/common/header');
?>
<style type="text/css">
	.jk_btnn{
margin-top: 2px;float: right;
	}
	@media(max-width:600px){
	.jk_txtt{
		font-size: 18px
		}
		.jk_btnn{
margin-top: -3px;float: right;
	}
	}
</style>
<div class=" cpm_mdl_cnt  ">
	<div class="container">
		<div class="row ">
			<div class="col-lg-5  " >

				<div class="cpm_hd_text jk_txtt">Withdrawal Of <span>Funds</span> <a href="" class="jk_btnn btn btn-primary">Withdraw Fiat</a></div>
				<div class="cpm_dep_out cpm_with_out" >
					<?php 
                                $action = '';
                                $attributes = array('id'=>'withdrawcoin','autocomplete'=>"off",'class'=>'deposit_form'); 
                                echo form_open($action,$attributes); ?>
					<div class="cpm_dep_body">
									
									<div class="row">
												<div class="col-lg-12 col-md-4 col-5">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Asset</div>
													   
														<select class="cpm_log_frm_s_input" name="ids" onChange="change_coin(this)">
															<?php
				                                             if(count($currency) > 0)
				                                             {
				                                               foreach ($currency as $currencys) 
				                                             		{
				                                                ?>
														<option value="<?php echo $currencys->id.'_'.$currencys->type.'_'.$wallet['Exchange AND Trading'][$currencys->id].'_'.$currencys->currency_symbol;?>" <?=($sel_currency->id == $currencys->id)?'selected':''?>>
				                                              <?=$currencys->currency_symbol?>
				                                              </option>
				                                              <?php } } ?>
						
														</select>
													</div>
												</div>
												<!-- <div class="col-lg-6 col-md-4 col-7">
													<div class="cpm_log_frm_s ">
														<div class="cpm_log_frm_s_lbl">Min Withdrawal Amount</div>
														<input type="text" class="cpm_log_frm_s_input" >
						
													</div>
												</div>
												<div class="col-lg-6 col-md-4 col-12">
													<div class="cpm_log_frm_s ">
														<div class="cpm_log_frm_s_lbl">Max Withdrawal Amount</div>
														<input type="text" class="cpm_log_frm_s_input">
					
													</div>
												</div> -->
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
												<div class="col-lg-6 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Withdrawal address</div>
														<input type="text" class="cpm_log_frm_s_input"  name="address" id="address">
														<p class="cpm_with_sm_lbl">This address is only for <?=$sel_currency->currency_symbol;?> based <?php echo $sel_currency->currency_name.' ('.$sel_currency->currency_symbol.')';?>.</p>
													</div>
												</div>
												<div class="col-lg-6 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Withdrawal Amount</div>
														<input type="text" id="amount" name="amount" onkeyup="calculate();" class="cpm_log_frm_s_input" >
														<div class="cpm_log_frm_s_otp_btn"><?=$sel_currency->currency_symbol;?></div>
														<div class="error"></div>
													</div>
												</div>
												</div>
												<div class="row align-items-center mb-4">
												<div class="col-12">
													<h6 class="cpm_with_d_fe">Withdrawal Fees<span  id="fees_p">0.00000</span></h6>

													

												</div>
												<div class="col-12">
													<h6 class="cpm_with_d_fe">Amount You will receive : <span  id="amount_receive">0</span> <?=$sel_currency->currency_symbol;?> </h6>
												</div>
												<div class="col-12">
													<button class="cpm_log_frm_btn" name="withdrawcoin" type="submit"><i class="fal fa-check"></i> Withdraw</button>
												</div>
												</div>




					</div>
					<?php
                        echo form_close();
                        ?>
					<div class="cpm_dep_imp">
						<h5>Caution</h5>
						<p>This address is only for <?php echo $sel_currency->currency_name.' ('.$sel_currency->currency_symbol.')';?> Withdrawal</p>
						<p>Sending any other coin or token to this address may result in the loss of your deposit and is not eligible for recovery</p>

					</div>


				</div>
			</div>
			<div class="col-lg-7 " >

				<div class="cpm_hd_text   text-center">Withrdaw <span>History</span></div>


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



$.validator.addMethod("noSpace", function(value, element) { 
  return value.indexOf(" ") < 0 && value != ""; 
}, "No space please and don't leave it empty");

        $("#withdrawcoin").validate({
          rules: {
                  address: {
                    required: true,
                    noSpace: true
                  },
                  amount: {
                    required: true,
                    number:true
                  },
                  ids: {
                    required: true
                  },
                  destination_tag: {
                    number: true
                  }
                },
          messages: {
                address: {
                  required: "Please enter address"
                },
                amount: {
                  required: "Please enter Amount",
                  number: "Invalid Amount"
                },
                ids: {
                  required: "Please select currency"
                },
                  destination_tag: {
                    number: 'Please enter numbers only'
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
window.location.href = base_url+'withdraw/'+symbol;


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
}



</script>