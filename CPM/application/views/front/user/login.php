<?php 
$this->load->view('front/common/header');
?>

<div class=" cpm_mdl_cnt login_page">
	<div class="container">



		<div class="row align-items-center">
			
			<div class="col-lg-6 col-md-6 m-auto" >
			<?php
			$action = front_url()."login";
			$attributes = array('id'=>'loginuserFrom','autocomplete'=>"off",'class'=>'');
			echo form_open($action,$attributes);
			?> 
				<div class="cpm_hd_text cpm_clr_blue_b text-center">User <span>Login</span></div>
				<div class="cpm_log_set bx">
					<div class="cpm_log_frm_s">
						<div class="cpm_log_frm_s_lbl"> Email</div>
						<input type="text" name="login_detail" id="login_detail" class="cpm_log_frm_s_input">

					</div>

					<div class="cpm_log_frm_s">
						<div class="cpm_log_frm_s_lbl">Password</div>
						<input type="password" name="login_password" id="login_password" class="cpm_log_frm_s_input">
						<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
					</div>
					<div class="cpm_log_frm_s">
						<div class="cpm_log_frm_s_lbl">2FA </div>
						<input type="password"  name="login_tfa" id="login_tfa" class="cpm_log_frm_s_input">
						<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
					</div>
					<button class="cpm_log_frm_btn" type="submit"><i class="ti-lock"></i>Login</button>






				</div>

				<div class="row">
					<div class="col-6">
						<a href="<?php echo base_url();?>forgot_password" class="cpm_log_frm_link">Forgot Password?</a>

					</div>
					<div class="col-6 text-right	">
						<a href="<?php echo base_url();?>signup" class="cpm_log_frm_link">Create Account</a>
					</div>

				</div>
				<?php echo form_close();?>
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


		$('#loginuserFrom').validate({
		errorClass: 'invalid-feedback',
		rules: {
		login_detail: {
		required: true,
		email:true,
		emailcheck: true,
		},
		login_password: {
		required: true
		},
		login_tfa: {
		number: true,
		minlength: 6
		}
		},
		messages: {
		login_detail: {
		required: "Please enter email",
		email: "Please enter valid email address",
		emailcheck: "Please enter valid email address"
		},
		login_password: {
		required: "Please enter password"
		},
		login_tfa: {
		number: "Please enter valid tfa code",
		minlength:"Enter 6 digit valid tfa code"
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

		$('#submit_btn').prop('disabled');
		$('.spinner-border').css('display','inline-block');

		var $form = $(form);
		$.ajax({
		url: front_url+"login_check",
		type: "POST",
		data: $form.serialize(),
		cache: false,
		processData: false,
		success: function(data)
		{
		    console.log(data);
		var d = jQuery.parseJSON(data);
		if(d.status==0)
		{
		//tata.error(d.msg);
		 tata.warn(d.msg);
		$('#submit_btn').prop('enabled');
		$('.spinner-border').css('display','none');
		}
		else
		{
		if(d.tfa_status==1)
		{
		$('#submit_btn').prop('enabled');
		$('.spinner-border').css('display','none');
		}
		else
		{
		if(d.login_url=='profile')
		{
		window.location.href = front_url+"profile";
		}
		else
		{
		window.location.href = front_url+"profile";
		}
		}
		tata.info(d.msg);

		}
		}
		});
return false;
// }
}
});
</script>

