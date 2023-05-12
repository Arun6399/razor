<?php 
$this->load->view('front/common/header');
?>





					<div class=" cpm_mdl_cnt  ">
						<div class="container">
							<div class="cpm_hd_text   text-center">Contact Us</div>

						    <?php 
				            $attributes=array('role'=>'form','id'=>'contactform',"autocomplete"=>"off",'action'=>$action,'class'=>'deposit_form'); 
				            echo form_open($action,$attributes); 
				            ?>
							<div class="row">
								<div class="col-md-6">
									<div class="cpm_cont_set">
										<i class="fal fa-phone cpm_cont_i"></i>
									<div class="cpm_cont_h1">Customer Care</div>
									<div class="cpm_cont_h2">+1</div>

									</div>
								</div>
							
								<div class="col-md-6">
									<div class="cpm_cont_set">
										<i class="fal fa-envelope cpm_cont_i"></i>
									<div class="cpm_cont_h1">Mail Us</div>
									<div class="cpm_cont_h2"><?php echo  $site_common['site_settings']->site_email;?></div>

									</div>
								</div>
								<div class="col-md-12 m-auto">
									<div class="cpm_log_set bx">
										<div class="row">
											<div class="col-lg-6 col-md-12">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Name</div>
													<input type="text"  id="name" name="name" class="cpm_log_frm_s_input">
		
												</div>
											</div>
											<div class="col-lg-6 col-md-12">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Email</div>
													<input type="text" id="email" name="email" class="cpm_log_frm_s_input" >
		
												</div>
											</div>

											<div class="col-lg-6 col-md-12">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Subject</div>
													<input type="text" id="subject" name="subject" class="cpm_log_frm_s_input" >
		
												</div>
											</div>
		
											<div class="col-lg-12 col-md-12">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Comments</div>
													<textarea class="cpm_log_frm_s_input" id="comments" name="comments" style="height: 100px; line-height: 1.3;"></textarea>
												
													<!-- <div class="error">Please Enter Comments</div> -->
												</div>
											</div>
											<div class="col-lg-3 ml-auto" >
											<button class="cpm_log_frm_btn" type="submit"><i class="ti-lock"></i>Enquire Now</button>	</div>
		
										</div>
									</div>
								</div>
							</div>
							<?php
		                      echo form_close();
		                    ?>
						</div>
					</div>



<?php 
$this->load->view('front/common/footer');
?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script type="text/javascript">
    $(document).ready(function() {

         $.validator.methods.email = function( value, element ) {
            return this.optional( element ) || /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test( value );
          }    
        $('#contactform').validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true
            },
            subject: {
                required: true
            },
            comments: {
                required: true
            }
           
        },
        messages: {
            name: {
                required: "Please enter name"
            },
            email: {
                required: "Please enter email"
            },
            subject: {
                required: "Please enter subject"
            },
            comments: {
                required: "Please enter message"
            }
        },
        invalidHandler: function(form, validator) {
		if (!validator.numberOfInvalids())
		{
		return;
		}
		else
		{
		var error_element=validator.errorList[0].element;
		error_element.focus();
		}
		},
		highlight: function (element) {
		$(element).parent().addClass('fail_vldr')
		},
		unhighlight: function (element) {
		$(element).parent().removeClass('error');
		$(element).parent().removeClass('fail_vldr');
		},
           submitHandler: function(form) {
                var response = grecaptcha.getResponse(); 
               // console.log(response);

                //recaptcha failed validation
                if (response.length == 0 || response.length=='') {
                    $('#cp_error').css('display','block');
                    $('#cp_error').html('Please Verify here');
                    return false;
                }
                //recaptcha passed validation
                else {
                    $('#cp_error').html('');
                    form.submit();
                }
                //
            }
    });
      });
</script>