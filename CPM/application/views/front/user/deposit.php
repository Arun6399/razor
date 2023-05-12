<?php 
$this->load->view('front/common/header');
?>

<div class=" cpm_mdl_cnt  ">
	<div class="container">
		<div class="row ">
			<div class="col-lg-5  " >
				<div class="cpm_hd_text  text-center">Deposit  <span>Your Asset</span></div>
				<div class="cpm_dep_out " >
					<div class="cpm_dep_body">
						<div class="row justify-content-center align-items-center">
							<div class="col-md-12 justify-content-center text-center">
								<div class="cpm_dep_h1" style="font-size: 15px;">For <?=$sel_currency->currency_symbol;?> DEPOSIT ONLY</div>
								<img src="<?php echo $First_coin_image;?>" class="cpm_dep_qr_img">
							</div>
							<div class="col-md-12">
								<div class="cpm_dep_in_sel_set">
									<select class="cpm_dep_in_sel" onChange="change_address(this)">
										 <?php
                                             if(count($all_currency) > 0)
                                             {
                                               foreach ($all_currency as $currency) 
                                             		{
                                                ?>
                                              <option value="<?php echo $currency->id.'#'.$currency->type.'#'.$currency->currency_symbol;?>" <?=($sel_currency->id == $currency->id)?'selected':''?>>
                                              <?=$currency->currency_symbol?>
                                              </option>
                                              <?php } } ?>
									</select>
									<input type="text" class="cpm_dep_in" id="copy_addr" value="<?php echo $crypto_address;?>" readonly="">
									<a  class="cpm_log_frm_s_otp_btn" onclick="copy_function()">Copy</a>
								</div>
								<div class="cpm_dep_ftr">
									<div class="row">
										<div class="col-6">
											<div class="cpm_dep_ftr_h1">
												Total balance
												<span><?=$user_balance;?></span>

											</div>

										</div>
										<div class="col-6">
											<div class="cpm_dep_ftr_h1">
												 Balance in USD
												<span>$ <?php echo $balance_in_usd;?></span>

											</div>
										</div>
									</div>


								</div>

								<!-- <div style="float: right; width: 100%;">

									<a href="#" class="cpm_dep_btnns cpm_dep_clr_suc">Share Address</a>
									<a href="#" class="cpm_dep_btnns">Save Address</a>
								</div> -->
							</div>

						</div>
					</div>
					<div class="cpm_dep_imp">
						<h5>Important</h5>
						<p>This address is only for <?php echo $sel_currency->currency_name.' ('.$sel_currency->currency_symbol.')';?> deposits</p>
						<p>Sending any other coin or token to this address may result in the loss of your deposit and is not eligible for recovery</p>
						<p>Deposit confirmations: 3</p>

					</div>


				</div>
			</div>
			<div class="col-lg-7 " >

				<div class="cpm_hd_text   text-center">Deposit <span>History</span></div>


				<div class="cpm_depo_tabl_s">
					<div class="cpm_depo_tabl_s_li_out cpm_depo_tabl_s_li_hds">
						<div class="cpm_depo_tabl_s_li">Coins</div>
						<div class="cpm_depo_tabl_s_li">Transaction Id</div>
						<div class="cpm_depo_tabl_s_li">Date</div>
						<div class="cpm_depo_tabl_s_li">Volume</div>
					</div>
					<?php
					 if(isset($deposit_history) && !empty($deposit_history))
                        {
                        	$a=0;
                        	foreach($deposit_history as $deposit)
                          {

                          	$cur_details = getcryptocurrencydetail($deposit->currency_id);
                          	if(empty($deposit->transaction_id))
                                {
                                  $transaction_id = '-';
                                }
                                else
                                {
                                  $transaction_id = $deposit->transaction_id;
                                } 


					?>
					<div class="cpm_depo_tabl_s_li_out ">
						<div class="cpm_depo_tabl_s_li">
							<div class="cpm_ast_hd_set">
								<img src="<?php echo $cur_details->image;?>" class="cpm_ast_ico"> 
								<div class="cpm_ast_h1"><?php echo $cur_details->currency_symbol;?></div>
								<div class="cpm_ast_h2"><?php echo $cur_details->currency_name;?></div></div>
							</div>


							<div class="cpm_depo_tabl_s_li"><div class="cpm_with_copy_tran"><i class="fal fa-file cpm_with_copy_ico"></i> <?php echo $transaction_id;?> </div> </div>

							<div class="cpm_depo_tabl_s_li"><?php echo date('d-M-Y H:i',$deposit->datetime);?></div>
							<div class="cpm_depo_tabl_s_li"><?php echo number_format($deposit->amount,8);?></div>
							<div class="cpm_dep_stat ">Completed <div class="cpm_dep_stat_txt">Address : <?=$deposit->crypto_address;?></div></div>
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
	function copy_function() 
	{
	var copyText = document.getElementById("copy_addr");
	copyText.select();
	document.execCommand("COPY");
	tata.info('CPM! ','Copied');
	}
function change_address(sel)
{


var arr1 = sel.value;
var arr = arr1.split('#');
var currency_id = arr[0];
var type = arr[1];
var symbol = arr[2];
window.location.href = base_url+'deposit/'+symbol;

}        	



</script>