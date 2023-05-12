<?php 
$this->load->view('front/common/header');
?>

<div class=" cpm_mdl_cnt login_page">
	<div class="container">



		<div class="row align-items-center">
			
			<div class="col-lg-6 col-md-6 m-auto" >
				 
				<div class="cpm_hd_text cpm_clr_blue_b text-center">Reset <span>Password</span></div>
				<div class="cpm_log_set bx">
					<?php
					$action = "";
					$attributes = array('id'=>'reset_pw_user','autocomplete'=>"off",'class'=>'');
					echo form_open($action,$attributes);
					?> 

					<div class="cpm_log_frm_s">
						<div class="cpm_log_frm_s_lbl">Password</div>
						<input type="password" name="reset_password" id="reset_password" class="cpm_log_frm_s_input">
						<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
					</div>

					<div class="cpm_log_frm_s">
						<div class="cpm_log_frm_s_lbl">Confirm Password</div>
						<input type="password" name="reset_cpassword" id="reset_cpassword" class="cpm_log_frm_s_input">
						<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
					</div>




					<button class="cpm_log_frm_btn" type="submit"><i class="ti-lock"></i>Request</button>





					<?php echo form_close();?>
				</div>

				<div class="row">
					<div class="col-6">
						<a href="<?php echo base_url();?>login" class="cpm_log_frm_link">Login</a>

					</div>
					<div class="col-6 text-right	">
						<a href="<?php echo base_url();?>signup" class="cpm_log_frm_link">Create Account</a>
					</div>

				</div>
				
			</div>
			


		</div>
	</div>


</div>



<?php 
$this->load->view('front/common/footer');
?>


<script type="text/javascript">
	$(document).ready(function() {
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

		$("input[name="+csrfName+"]").val(data);
		}
		});
		}
		});

		});
		$.validator.addMethod("emailcheck", function(value) {
		return (/^\w+([.-]?\w+)@\w+([.-]?\w+)(.\w{2,3})+$/.test(value));
		},"Please enter valid email address");


		$('#reset_pw_user').validate({
		errorClass: 'invalid-feedback',
		    rules: {
		      reset_password: {
		        required: true,
		        minlength: 8
		      },
		      reset_cpassword: {
		        required: true,
		        equalTo : "#reset_password"
		      }
		    },
		    messages: {
	      reset_password: {
	        required: "Please enter password",
	        minlength: "Minimum 8 characters, including UPPER / lower case with numbers & special characters"
	      },
	      reset_cpassword: {
	        required: "Please enter Confirm Password",
	        equalTo : "Please enter same password"
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
		submitHandler: function(form)
		{

			var $form = $(form);
         	form.submit();
		
// }
	}
	});
</script>

