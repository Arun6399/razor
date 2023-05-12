<?php 
$this->load->view('front/common/header');
?>

<div class=" cpm_mdl_cnt  login_page">

	<div class="container">

		
		
		
		<div class="row align-items-center">
			<?php $attributes=array('id'=>'register_user','class'=>'auth_form','autocomplete'=>"off");
						echo form_open($action,$attributes); $settings = $site_common['site_settings'];
				?>
			<div class="col-lg-7 m-auto" >
				<div class="cpm_hd_text cpm_clr_blue_b text-center">Create <span>Account</span></div>
				
				<div class="cpm_log_set bx">
					
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="cpm_log_frm_s">
								<div class="cpm_log_frm_s_lbl">User Name</div>
								<input type="text" name="username" id="username" class="cpm_log_frm_s_input">

							</div>
						</div>
						<div class="col-lg-6 col-md-12">
							<div class="cpm_log_frm_s">
								<div class="cpm_log_frm_s_lbl">Email</div>
								<input type="text" id="register_email" name="register_email" class="cpm_log_frm_s_input" >

							</div>
						</div>

						<div class="col-lg-6 col-md-12">
							<div class="cpm_log_frm_s fail_vldr">
								<div class="cpm_log_frm_s_lbl">Password</div>
								<input type="password" class="cpm_log_frm_s_input" id="register_password" name="register_password">
								<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
							</div>
						</div>
						<div class="col-lg-6 col-md-12">
							<div class="cpm_log_frm_s">
								<div class="cpm_log_frm_s_lbl">Confirm Password</div>
								<input type="password" class="cpm_log_frm_s_input"  name="register_cpassword" id="register_cpassword">
								<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
							</div>
						</div>
						<div class="col-lg-6 col-md-12">
							<div class="cpm_log_frm_s">
								<div class="cpm_log_frm_s_lbl">Select Country</div>
								<!-- <input type="text" class="cpm_log_frm_s_input" > -->
								<select class="cpm_log_frm_s_input "  name="country" id="country"  >
								<option value="">Select Country</option>
								<?php
								if(isset($countries) && !empty($countries)){
								foreach($countries as $Country){
								?>
								<option value="<?php echo $Country->id?>"><?php echo $Country->country_name;?></option>
								<?php
								}
								}
								?>
								</select>

							</div>
						</div>


						<div class="col-md-12">

							<label class="cpm_log_frm_s_chk"><input type="checkbox" name="terms" id="terms"><div class="cpm_log_frm_s_chk_bx"></div> I agree to the <a href="#">Terms &amp; Conditions</a></label>

						</div>
					</div>

					<button class="cpm_log_frm_btn" type="submit"><i class="ti-lock"></i>Register Now</button>
					
				</div>
				
				<div class="row">
					<div class="col-6">
						<a href="<?php echo base_url();?>forgot_password" class="cpm_log_frm_link">Forgot Password?</a>

					</div>
					<div class="col-6 text-right	">
						<a href="<?php echo base_url();?>login" class="cpm_log_frm_link">Login</a>
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

    var base_url='<?php echo base_url();?>';
    var front_url='<?php echo front_url();?>';

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
                    console.log(data);
                     $("input[name="+csrfName+"]").val(data);
                }
            });
        }
    });
$(document).ready(function () {
    jQuery.validator.addMethod("alphanumeric", function(value, element) {
      return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
});

    $('#register_user').validate({
        errorClass: 'invalid-feedback',
            rules: {
                username: {
                required: true
            },
                register_email: {
                    required: true,
                    email: true,
               remote: {
                    url: front_url+'email_exist',
                    type: "post",
                    csrf_token : csrfName,
                    data: {
                        email: function() {
                            return $( "#register_email" ).val();
                        }
                    }
                }
            },
           
            register_password: {
                required: true,
                minlength: 8,
                alphanumeric: true
            },
            register_cpassword: {
                required: true,
                equalTo : "#register_password"
            },
            country: {
                required: true
            },
            terms: {
                required:true
            }
        },
        messages: {
            username: {
                required:"Please enter Username"
            },
           register_email: {
                required:"Please enter email",
                //email: "<?php echo $this->lang->line('Please enter valid email address')?>"
                remote: "Entered Email Address Already Exists"
            },
            register_password: {
                required: "Please enter password",
                minlength: "Password should be Minimum 8 characters ",
                alphanumeric: "Password should contains special characters,uppercase,lowecase and numbers"
            },
            register_cpassword: {
                required: "Please enter Confirm Password",
                equalTo : "Please enter same password"
            },
            country: {
                required: "Please select country"
            }
        },
        invalidHandler: function(form, validator) {
          if(!validator.numberOfInvalids())
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
          $(element).parent().addClass('fail_vldr');
        },
        unhighlight: function (element) {
          $(element).parent().removeClass('error');
          $(element).parent().removeClass('fail_vldr');
        },
        submitHandler: function(form) 
        {
        	$('#submit_btn').prop('disabled');
        	var $form = $(form);
         	form.submit();
          
        }
    });
});
</script>

