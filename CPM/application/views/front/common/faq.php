<?php 
$this->load->view('front/common/header');
?>

<div class=" cpm_mdl_cnt  ">

						<div class="container">
							<div class="cpm_hd_text   text-center">Faq</div>

						   
							<div class="cpm_sta_faq_set">
								<?php

									if($faqs){ $j=0; foreach($faqs as $faq){ $j++; 
								?>

								<div class="cpm_sta_faq_li">
									<div class="cpm_sta_faq_li_hd"><?=$faq->english_question;?></div>
									<div class="cpm_sta_faq_li_bdy"> <?=$faq->english_description;?>
									</div>
								</div><?php } } ?>
								
								</div>
							</div>
					</div>


<?php 
$this->load->view('front/common/footer');
?>