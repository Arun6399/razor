<?php 
$this->load->view('front/common/header');

?>

<div class=" cpm_mdl_cnt">
						<div class="container">
							
						
							<div class="cpm_hd_text  text-center">Complete Your <span>KYC</span></div>
							<div class="row align-items-center">
								<?php
									$attributes = array('id'=>'verification_forms'); 
									$action = front_url() . 'kyc_verification';
									echo form_open_multipart($action,$attributes);
									?> 
								<div class="col-lg-12  animated" data-animation="fadeInLeftShorter" data-animation-delay="0.2s">
								   <div class="">
									 	<div class="row justify-content-md-center">
											<div class="col-lg-12">
													<div class="cpm_kyc_set">
														<div class="cpm_kyc_s_h1">Proof Of Address</div>
														<div class="cpm_kyc_p">Maximum file size should be below 2mb</div>
														<?php
															if(($users->photo_1_status==0 || $users->photo_1_status==2)){
														   ?>
														<img src="<?php echo front_img();?>upico.png" id="address_proof" class="cpm_kyc_img">
														<?php } else { ?>
															<img src="<?php echo $users->photo_id_1;?>" id="address_proof" class="cpm_kyc_img">

														<?php } ?>


													 
														<select class="cpm_kyc_typ">
															<option value="1">Address Proof</option>
															
														</select>
														
														<?php
															if(($users->photo_1_status==0 || $users->photo_1_status==2)){
														   ?>
														   <h4 class="mt-2 text-warning">Pending Or Rejected</h4>  
														<input type="file" name="photo_id_1" id="photo_id_1" onchange="Imgupload(this,'address_proof')" class="cpm_kyc_filin"><?php } else if($users->photo_1_status==3) { ?> <h4 class="mt-2 text-success">Completed</h4>   <?php } ?>
													</div>
											</div>
											<div class="col-lg-12">
												<div class="cpm_kyc_set">
													<div class="cpm_kyc_s_h1">Proof Of Identity</div>
													<div class="cpm_kyc_p">Maximum file size should be below 2mb</div>
														
														<?php
															if(($users->photo_2_status==0 || $users->photo_2_status==2)){
														   ?>
														<img src="<?php echo front_img();?>upico.png" id="identity_proof" class="cpm_kyc_img">
														<?php } else { ?>
															<img src="<?php echo $users->photo_id_2;?>" id="identity_proof" class="cpm_kyc_img">

														<?php } ?>


												 
														<select class="cpm_kyc_typ">
															
															<option value="0">Identity Proof</option>
														</select>
														<?php
															if(($users->photo_2_status==0 || $users->photo_2_status==2)){
														   ?>
														<h4 class="mt-2 text-warning">Pending Or Rejected</h4>
													<input type="file" name="photo_id_2" id="photo_id_2" onchange="Imgupload(this,'identity_proof')" class="cpm_kyc_filin"><?php } else if($users->photo_2_status==3) { ?> <h4 class="mt-2 text-success">Completed</h4>   <?php } ?>
												</div>
						
										</div>
										<div class="col-lg-12">
											<div class="cpm_kyc_set">
												<div class="cpm_kyc_s_h1"> Selfie Id</div>
												<div class="cpm_kyc_p">Maximum file size should be below 2mb</div>
												<?php
													if(($users->photo_3_status==0 || $users->photo_3_status==2)){
												   ?>
													<img src="<?php echo front_img();?>upico.png" id="selfie_proof" class="cpm_kyc_img">
													<?php } else { ?>
													<img src="<?php echo $users->photo_id_3;?>" id="selfie_proof" class="cpm_kyc_img">

												<?php } ?>	

											 
												<select class="cpm_kyc_typ">
													<option value="0">Selfie</option>
												  
												</select>
												<?php
													if(($users->photo_3_status==0 || $users->photo_3_status==2)){
												?>
												<h4 class="mt-2 text-warning">Pending Or Rejected</h4>
												<input type="file" name="photo_id_3" id="photo_id_3" onchange="Imgupload(this,'selfie_proof')" class="cpm_kyc_filin"><?php } else if($users->photo_3_status==3) { ?> <h4 class="mt-2 text-success">Completed</h4>   <?php } ?>
											</div>
						
									</div>
										</div>
										<div class="row justify-content-center">
										 	<?php
													if($users->photo_1_status!=1 && $users->photo_2_status!=1 && $users->photo_3_status!=1){
												?>
													<div class="col-lg-4 col-md-6"><button id="verification_btn" class="cpm_log_frm_btn gradient-grn" style="width:100%" type="submit">Submit Documents</button></div>
											<?php
												}
											?>
										
										</div>
									</div>
								</div>
								<?php echo form_close();?>
							 </div>
						</div>
					</div>


<?php 
$this->load->view('front/common/footer');
?>
<script type="text/javascript">

$(document).ready(function () {

$('form').submit(function() {
  $(this).find("button[type='submit']").prop('disabled',true);
  $('#verification_btn').html('Loading .........');

});

});


	function Imgupload(input,src)
	{	
		
		  if (input.files && input.files[0]) {
          var reader = new FileReader();
		  reader.onload = function(e) {
            $('#'+src).attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]); 
        }
	}


</script>