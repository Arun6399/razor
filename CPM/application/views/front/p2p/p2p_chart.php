<?php 
$this->load->view('front/common/header');
?>



		<div class=" cpm_mdl_cnt  ">

						<div class="container">
						
							<?php

							// print_r($gettrade);

							?>
						   
							<div class="row">
								<div class="col-md-8">
									<div class="cpm_hd_text   text-center">P2P Chat </div>

									<div class="cpm_chat_set">
										<div class="cpm_exp_ch_bdy_out cpm_exp_pag_scroll">
											<div class="cpm_exp_ch_bdy  ">
													

												<div class="cpm_exp_ch_li_blk">
												<div class="cpm_exp_ch_li">
													<img src="<?php echo front_img();?>avt-1.jpg" class="cpm_exp_ch_li_img">
													Admin Message
												</div>
												</div>
												<div class="cpm_exp_ch_li_blk mb-5">
												<div class="cpm_exp_ch_li cht_me">
													<img src="<?php echo front_img();?>avt-2.jpg" class="cpm_exp_ch_li_img">
													Test Meaasge
												</div>
												</div>
												
												
											</div>
											</div>
											
											<div class="cpm_exp_ch_btm">
												<input data-emojiable="true"
							data-emoji-input="unicode" type="text" class="cpm_exp_ch_text" name="message" id="message" placeholder="Type Message">
												<button type="submit" class="d-block"><i class=" cpm_exp_ch_text_i fal fa-arrow-right"></i></a>
											</div>
										
									</div>
								</div>

								<div class="col-md-4">
									<div class="cpm_hd_text   text-center">P2P Order Information</div>
									
									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1">
										
										<?php
										echo UserName($gettrade->user_id);

										$second_currency = getcurrency_name($gettrade->currency);

										?>

									</div> </div></a>
									
									

									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?php echo  $tradeorder->first_amount.' - '.$second_currency;?></div> </div></a>

									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?php echo $tradeorder->second_amount.' - '.getcurrency_name($gettrade->cryptocurrency);?></div> </div></a>

									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?=$payment;?></div> </div></a>
									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?=$gettrade->datetime;?></div> </div></a>

									<?php if($gettrade->type=='Buy') {
										$trderid = $tradeorder->buyerid;
										?>

									<a href="<?php echo base_url();?>p2p_orderconfirm/<?=$trderid;?>/<?=$gettrade->tradeid;?>" class="d-block"><div style="background: #16b786;" class="cpm_suppo_list"> <div  class="cpm_suppo_list_h1"> Click To Confirm </div> </div></a><?php } else if($gettrade->type=='Sell' && $tradeorder->tradestatus=='open') {
									?>

									<a href="<?php echo base_url();?>p2p_release/<?=encryptIt($gettrade->tradeid);?>/<?=encryptIt($tradeorder->id);?>" class="d-block "><div class="cpm_suppo_list cpm_repo_stat_danger"> <div  class="cpm_suppo_list_h1"> Relase Cryptos (<?=$second_currency;?>)</div> </div></a>	

									<?php } ?>



								</div>
	</div>

<?php 
$this->load->view('front/common/footer');
?>


<script type="text/javascript">

$(document).ready(function() {

$(function () {
    // Initializes and creates emoji set from sprite sheet
    window.emojiPicker = new EmojiPicker({
        emojiable_selector: '[data-emojiable=true]',
        assetsPath: 'vendor/emoji-picker/lib/img/',
        popupButtonClasses: 'icon-smile'
    });

    window.emojiPicker.discover();
});
});


$('#reply').validate({
        rules: {
            
            message: {
                required: true
            }
        },
        messages: {
            
            message: {
                required: "'Please enter message"
            }
        },
    });
</script>