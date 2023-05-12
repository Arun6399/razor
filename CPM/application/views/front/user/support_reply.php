<?php 
$this->load->view('front/common/header');
?>



		<div class=" cpm_mdl_cnt  ">

						<div class="container">
						

						   
							<div class="row">
								<div class="col-md-8">
									<div class="cpm_hd_text   text-center">Chat Support</div>

									<div class="cpm_chat_set">
										<div class="cpm_exp_ch_bdy_out cpm_exp_pag_scroll">
											<div class="cpm_exp_ch_bdy  ">
												<!-- <div class="cpm_exp_ch_li_blk">
												<div class="cpm_exp_ch_li">
													<img src="<?php echo front_img();?>avt-1.jpg" class="cpm_exp_ch_li_img">
													Hi
												</div>
												</div> -->
													
												 <?php
                                                    if(isset($support_reply) && !empty($support_reply)){
                                                        $i=0;
                                                         foreach($support_reply as $reply) 
                                                    {  $i++;

                                                        $time = time_calculator($reply->created_on);
                                                          $reply_msg = $reply->message;
                                                          $reply_file = $reply->image;



                                                        if($reply->user_id ==0) 
                                                        {
                                                 ?>	

												<div class="cpm_exp_ch_li_blk">
												<div class="cpm_exp_ch_li">
													<img src="<?php echo front_img();?>avt-1.jpg" class="cpm_exp_ch_li_img">
													<?php echo $reply_msg;?>
												</div>
												</div><?php } else { ?>
												<div class="cpm_exp_ch_li_blk mb-5">
												<div class="cpm_exp_ch_li cht_me">
													<img src="<?php echo front_img();?>avt-2.jpg" class="cpm_exp_ch_li_img">
													<?php echo $reply_msg;?>
												</div>
												</div><?php } } } ?>
												
												
											</div>
											</div>
											<?php
											$attributes=array('id'=>'reply');
	                                        echo form_open_multipart($action,$attributes);
	                                        ?>
											<div class="cpm_exp_ch_btm">
												<input data-emojiable="true"
							data-emoji-input="unicode" type="text" class="cpm_exp_ch_text" name="message" id="message" placeholder="Type Message">
												<button type="submit" class="d-block"><i class=" cpm_exp_ch_text_i fal fa-arrow-right"></i></button>
											</div>
											<?php
                                                echo form_close();
                                        ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="cpm_hd_text   text-center">Ticket Details</div>

										
									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?php echo ucfirst($support->subject);?></div> </div></a>
									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?php echo time_calculator($support->created_on); ?></div> </div></a>

									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?php echo getSupportCategory($support->category);?></div> </div></a>

									<a href="#" class="d-block"><div class="cpm_suppo_list"> <div class="cpm_suppo_list_h1"><?php echo  ucfirst(htmlentities($support->message)); ?></div> </div></a>

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