<?php $this->load->view('front/common/header_login.php');?>
<!--
            =============================================
                Signup Page
            ==============================================
            -->
            <div class="signUp-page signUp-standard pb-100">
                <div class="shape-wrapper">
                    <span></span>
                    <span></span>
                    <span></span>
                </div> <!-- /.shape-wrapper -->
                <!-- <div class="signUp-illustration"><img src="images/home/sign-up.svg" alt=""></div> -->
                <div class="container">
                    <div class="row"  >
                        <div class="col-lg-6">
                            <div class="signUp-illustration"><img src="<?php echo base_url();?>assets/images/sign-up.png" alt=""></div>

                        </div>
                        <div class="col-lg-6 ml-auto">
                            <!-- <p style="text-align: center;"><a href="index.html"><img src="images/final.png" style="width: 150px;" alt=""></a></p> -->
                            <div class="sign-up-form-wrapper">
                                <div class="title-area" style="text-align: center;">
                                    <h3 style="font-weight: 700;
                                    font-family: 'open sans';" >Signup Now!</h3>
                                    <p style="font-family: 'open sans';">Please give us a few more details and we’ll add you to  our early access list.</p>
                                </div> <!-- /.title-area -->
                                <ul class="social-icon-wrapper row">
                                    <li class="col-6"><a href="<?=base_url()?>googlelogin" class="gmail"><i class="fa fa-envelope-o" aria-hidden="true"></i> Gmail </a></li>
                                    <li class="col-6"><a href="<?php echo base_url();?>fblogin" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a></li>
                                </ul>
                                <p class="or-text"><span>or</span></p>

                                <form action="<?php echo base_url();?>signup" name="signup-FORM" id="signup-FORM" method="post">
                                    <div class="row">
                                        <!-- <div class="col-md-6">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Username</label>
                                                <input type="text" required>

                                            </div>
                                        </div> -->
                                        <!-- /.col- -->
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Email</label>
                                                <input type="email" name="register_email" id="register_email" class="email_detail">

                                            </div> <!-- /.input-group -->
                                            <span></span>
                                        </div> <!-- /.col- -->



                                        <?php if($ref!='')  {   ?>

                                                 

                                          <div class="col-md-12">
                                               <div class="input-group">
                                                <label style="font-family: 'open sans';">Referral Id (option) </label>

                                                 <input type="text" name="parentid" id="parentid" class="email_detail"  value="<?php echo $ref; ?>" readonly>

                                            </div>
                                        </div>

                                    <?php } else {?>

                                             <div class="col-md-12">
                                               <div class="input-group">
                                                <label style="font-family: 'open sans';">Referral Id (option) </label>
                                              <input type="text" name="parentid" id="parentid" class="email_detail"  value="">

                                            </div>
                                        </div>



                                    <?php } ?>

                                   
                                        
                                    </div>
                                    <!-- /.row -->

                                    <div class="agreement-checkbox1" style="padding-left: 22px;margin: 30px 0px;">
                                        <input type="checkbox" id="agreement" name="agreement">
                                        <label for="agreement" style="font-family: 'open sans';">I agree to the terms and policy from the Bidex</label>
                                    </div>
                                    <span></span>
                                    <p style="text-align: center;"><a href="<?php echo base_url();?>signup"><button class="solid-button-one" style="text-transform:uppercase;font-family: 'open sans';" name="register">Sign Up</button></a></p>
                                </form>
                            </div> <!-- /.sign-up-form-wrapper -->
                        </div>
                    </div>
                </div>
            </div> <!-- /.signUp-page -->
<script src="<?php echo base_url();?>assets/front/js/jquery.growl.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <?php
    $error      = $this->session->flashdata('error');
    $success    = $this->session->flashdata('success');
    $user_id    = $this->session->userdata('user_id');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $get_os     = $_SERVER['HTTP_USER_AGENT'];
?>

<script type="text/javascript">
        var base_url='<?php echo base_url();?>';
    var front_url='<?php echo front_url();?>';
    var user_id='<?php echo $user_id;?>';
    var ip_address = '<?php echo $ip_address;?>';
    var get_os     = '<?php echo $get_os;?>';

    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';

     var success = "<?php echo $this->session->flashdata('success')?>";
    var error = "<?php echo $this->session->flashdata('error')?>";
  
        if(success!=''){
$.growl.notice({title: "Bidex", message: success });
//alert(success);
}
if(error!=''){
$.growl.error({title: "Bidex", message: error });
}


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

jQuery.validator.addMethod("mail", function(value, element) {
return this.optional(element) || /^([a-zA-Z0-9_.+-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/.test(value);
});
    
    $('#signup-FORM').validate({
        errorElement: 'span',
        rules: {
         
            register_email: {
                required: true,
                email: true,
                mail: true,
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
        agreement: {
            required:true
        }
    },
    messages: {
        
       register_email: {
            required:"Please enter email",
            email: "Please enter valid email address",
            remote: "Entered Email Address Already Exists"
        },
        agreement: {
            required: "Please select terms & policy"
        }
    },
    errorPlacement: function(error, element) {
        //console.log(error);
      $(element).parent().next('span').html(error);
      
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
      //$(element).parent().addClass('error')
    },
    unhighlight: function (element) {
      $(element).parent().removeClass('error')
    },
    submitHandler: function(form)
    {

      //  var response = grecaptcha.getResponse();
   /* if (response.length == 0) {
        $('.recaptcha_error').css('display','block');
        return false;
    }
    else{
        $('.recaptcha_error').css('display','none');
    }*/
    $('#submit_btn').prop('disabled');
    $('.spinner-border').css('display','inline-block');

      var $form = $(form);
      form.submit();
    }
});
    </script>
