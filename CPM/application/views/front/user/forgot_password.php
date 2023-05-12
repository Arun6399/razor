<?php 
$this->load->view('front/common/header');
?>

<div class=" cpm_mdl_cnt login_page">
	<div class="container">



		<div class="row align-items-center">
			
			<div class="col-lg-6 col-md-6 m-auto" >
				  <?php 
                        $action = front_url()."login";
                        $attributes = array('id'=>'forgot_user','autocomplete'=>"off",'class'=>'auth_form'); 
                        echo form_open($action,$attributes);
                        ?>
				<div class="cpm_hd_text cpm_clr_blue_b text-center">Forgot <span>Password</span></div>
				<div class="cpm_log_set bx">
					<div class="cpm_log_frm_s">
						<div class="cpm_log_frm_s_lbl"> Email</div>
						<input type="text" name="forgot_detail" id="forgot_detail" class="cpm_log_frm_s_input">

					</div>

					
					<button id="submit_btn" class="cpm_log_frm_btn" type="submit"><i class="ti-lock"></i>Request</button>






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


   $('#forgot_user').validate({
        errorClass: 'invalid-feedback',
        rules: {
            forgot_detail: {
                required: true,
                email:true,
                emailcheck: true,
            }
        },
        messages: {
            forgot_detail: {
                required: "Please enter email",
                email: "Please enter valid email address",
                emailcheck: "Please enter valid email address"
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
          //$(element).parent().addClass('error')
        },
        unhighlight: function (element) {
          $(element).parent().removeClass('error')
        },
        submitHandler: function(form) {

            $('#submit_btn').prop('disabled',true);
            var $form = $(form);
        
            $.ajax({
            url: front_url+"forgot_check", 
            type: "POST",             
            data: $form.serialize(),
            cache: false,             
            processData: false,    
            beforeSend: function() {
                $(':input[type="submit"]').prop('disabled', true);
            },
            success: function(data) {
                //console.log(data);
                var d = jQuery.parseJSON(data);
                if(d.status==0)
                {
                    $('#forgot_detail').val('');
                    $(':button[type="submit"]').prop('disabled', false);

                   
                     tata.error(d.msg,"Error");
                }
                else
                { 
                   
                    $('#forgot_detail').val('');
                    $(':input[type="submit"]').prop('disabled', false);

                    
                    tata.success('success',d.msg);

                }
            }
        });
        return false;
        }
    });
</script>

