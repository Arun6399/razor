<?php
$this->load->view('front/common/header_login.php');

$favicon = $site_common['site_settings']->site_favicon;
$sitelogo = $site_common['site_settings']->site_logo;
$user_id = $this->session->userdata('user_id');
?>

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
                        <!-- <div class="col-lg-6">
                            <div class="signUp-illustration"><img src="images/sign-up.png" alt=""></div>

                        </div> -->
                        <div class="col-lg-12 ml-auto">
                            <!-- <p style="text-align: center;"><a href="index.html"><img src="images/final.png" style="width: 150px;" alt=""></a></p> -->
                            <div class="sign-up-form-wrapper">
                                <div class="title-area" style="text-align: center;">
                                    <h3 style="font-weight: 700;
                                    font-family: 'open sans';" >Signup Now!</h3>
                                    <p style="font-family: 'open sans';">Please give us a few more details and we’ll add you to  our early access list.</p>
                                </div> <!-- /.title-area -->
                                <!-- <ul class="social-icon-wrapper row">
                                    <li class="col-6"><a href="#" class="gmail"><i class="fa fa-envelope-o" aria-hidden="true"></i> Gmail</a></li>
                                    <li class="col-6"><a href="#" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a></li>
                                </ul>
                                <p class="or-text"><span>or</span></p> -->

                                <form action="<?php echo base_url();?>signup" name="signup" id="signup" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">First Name</label>
                                                <input type="text" name="firstname" id="firstname">

                                            </div> <!-- /.input-group -->
                                            <span></span> 
                                        </div> <!-- /.col- -->
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Last Name</label>
                                                <input type="text" name="lastname" id="lastname">

                                            </div> <!-- /.input-group -->
                                            <span></span> 
                                        </div> <!-- /.col- -->
                                        <div class="col-md-6">
                                            
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Email</label>
                                                <input type="email" name="register_email" id="register_email" value="<?php echo $register_email;?>">

                                            </div> <!-- /.input-group -->
                                            <span></span> 
                                        </div> <!-- /.col- -->
                                        <div class="col-md-6">
                                            <label style="color: #bcbcbc; font-family:'open sans';" >Select Country</label>
                                            <div class="input-group">
                                                <select class="theme-select-menu form-control" name="country" id="country">
                                                    <option value="">Select Country</option>
                                            <?php if($countries) {
                                  foreach($countries as $co) {
                                    ?>
                                    <option <?php if($co->id==$users->country) { echo "selected"; } ?>
                                    value ="<?php echo $co->id; ?>"><?php echo $co->name; ?></option>
                                    <?php
                                  }
                                } ?>
                                                </select>
                                            </div> <!-- /.input-group -->
                                            <span></span> 
                                        </div> <!-- /.col- -->
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Phone Number</label>
                                                <!-- <input type="number" required> -->
                                                <input id="phone" name="phone" type="tel">
<!-- <span id="valid-msg" class="hide">Valid</span>
<span id="error-msg" class="hide">Invalid number</span> -->

                                            </div> <!-- /.input-group -->
                                            <span></span> 
                                            
                                        </div>


                                                                <?php if($parentid!=''){?>
                                                                    <div class="col-md-6">
                                                 <div class="input-group">
                                                <label style="font-family: 'open sans';">Referral</label>
                                                <input type="text" name="parentid" id="parentid" value="<?php echo $parentid;?>" readonly>

                                            </div> <!-- /.input-group -->
                                        </div>
                                        <?php } ?> 




                                         <!-- /.col- -->
                                        <!-- <div class="col-md-6">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Referral Code (Optional)</label>
                                                <input type="text" name="referral_code" id="referral_code">

                                            </div>  
                                        </div>   -->

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Password</label>
                                                <input type="password" name="register_password" id="register_password">

                                            </div> <!-- /.input-group -->
                                            <span></span> 
                                        </div> <!-- /.col- -->
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Confirm Password</label>
                                                <input type="password" name="register_cpassword" id="register_cpassword">

                                            </div> <!-- /.input-group -->
                                            <span></span> 
                                        </div> <!-- /.col- -->

                                    </div> <!-- /.row -->

                                    <div class="form-group text-left">
                                        <div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LdKDyEfAAAAABa2nRE4xeBT1ml4ggKrR4J2R6uk"></div>
                                    </div>

                                    <label id="cp_error" class="error"></label>

                                    <div class="agreement-checkbox1" style="padding-left: 22px;margin: 30px 0px;">
                                        <input type="checkbox" id="terms_condition" name="terms_condition">
                                        <label for="agreement" style="font-family: 'open sans';">I agree to the terms and policy from the Bidex</label>
                                    </div>
                                    <span></span> 
                                    <p style="text-align: center;"><button class="solid-button-one" name="signup" style="text-transform:uppercase;font-family: 'open sans';">Signup</button></p>
                                </form>
                            </div> <!-- /.sign-up-form-wrapper -->
                        </div>
                    </div>
                </div>
            </div> <!-- /.signUp-page -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?php echo base_url();?>assets/vendor/selectize.js/selectize.min.js"></script>

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
     $.validator.addMethod("checkPassword", function(value, element) {
    return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$@!%&*?])[A-Za-z\d#$@!%&*?]{8,30}$/.test(value);
}, "Minimum 8 characters, including UPPER / lower case with numbers & special characters");   
  $('#signup').validate({
    errorElement: 'span',
        rules: {
            firstname: {
            required: true
        },
         lastname: {
            required: true
        },
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

        register_password: {
            required: true,
            checkPassword:true,
            minlength: 8
        },
        register_cpassword: {
            required: true,
            equalTo : "#register_password"
        },
        country: {
            required: true
        },
        phone: {
            required: true,
            number: true
        },
        terms_condition: {
            required: true
        }
       
    },
    messages: {
        firstname: {
            required:"Please enter firstname"
        },
        lastname: {
            required:"Please enter lastname"
        },
       register_email: {
            required:"Please enter email",
            email: "Please enter valid email address",
            remote: "Entered Email Address Already Exists"
        },
        register_password: {
            required: "Please enter password",
            minlength: "Minimum 8 characters, including UPPER / lower case with numbers & special characters"
        },
        register_cpassword: {
            required: "Please enter Confirm Password",
            equalTo : "Please enter same password"
        },
        country: {
            required: "Please select country"
        },
        phone: {
            required: "Please enter phone",
            number: "Please enter numbers only"
        },
        terms_condition: {
            required: "Please select terms & policy"
        }
    },
    errorPlacement: function(error, element) {
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
    $('#submit_btn').prop('disabled');
    $('.spinner-border').css('display','inline-block');

      var $form = $(form);
      form.submit();
    }
});
    </script>


