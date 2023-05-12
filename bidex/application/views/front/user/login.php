<?php
$this->load->view('front/common/header_login');
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
                    <span></span>
                    <span></span>
                </div> <!-- /.shape-wrapper -->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="signUp-illustration"><img src="<?php echo base_url();?>assets/images/login.png" alt=""></div>
                        </div>
                        <div class="col-lg-6 ml-auto">
                            <!-- <p style="text-align: center;"><a href="index.html"><img src="images/final.png" style="width: 150px;" alt=""></a></p> -->
                            <div class="signin-form-wrapper">
                                <div class="title-area" style="text-align: center;">
                                    <h3 style="font-weight: 700;
                                    font-family: 'open sans';">Login</h3>

                                </div> <!-- /.title-area -->
                                 <ul class="social-icon-wrapper row" style="margin-left: 15px;
    margin-right: 15px;">
                                <!--     <li class="col-2"></li> -->
                                    <li class="col-12 col-md-6 col-lg-6"><a href="<?=base_url()?>googlelogin" class="gmail"><i class="fa fa-envelope-o" aria-hidden="true"></i> Gmail </a></li>

                                    <li class="col-12 col-md-6 col-lg-6"><a href="<?php echo base_url();?>fblogin" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i> Facebook</a></li>
                                <!--     <li class="col-2"></li> -->
                                </ul>
                                <p class="or-text"><span>or</span></p>
                                <form action="<?php echo base_url();?>login_check" name="login-form" id="login-form" method="post">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Email</label>
                                                <input type="email" name="login_detail" id="login_detail">

                                            </div> <!-- /.input-group -->
                                           
                                        </div> <!-- /.col- -->
                                        
                                        <div class="col-12">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Password</label>
                                                <input type="password" name="login_password" id="login_password">

                                            </div> <!-- /.input-group -->
                                            
                                        </div> <!-- /.col- -->

                                        <div class="col-12" id="tfa_verify" >
                                <div class="input-group" style="margin-bottom:1.5rem !important;">
                                    <label style="font-family: 'open sans';">Google TFA</label>
                                    <input type="text" name="login_tfa" maxlength="6" id="login_tfa" class="form-control" placeholder="2FA Authentication (If Enabled)">
                                </div></div>
                                        
                                    </div> <!-- /.row -->
                                    <div class="agreement-checkbox d-flex justify-content-between align-items-center">
                                        <div>
                                            <!-- <input type="checkbox" id="remember" name="remember">
                                            <label for="remember" style="font-family: 'open sans';">Remember Me</label> -->
                                        </div>
                                        
                                        <a href="<?php echo base_url();?>forgot_password" style="font-family: 'open sans';">Forget Password?</a>
                                    </div>
                                    <p style="text-align: center;"><button class="solid-button-one" style="text-transform:uppercase;font-family: 'open sans'; ">Login</button></p>
                                </form>
                                <p class="signUp-text" style="padding-bottom: 20px;font-family: 'open sans'; " >Don’t have any account? <a href="<?php echo base_url();?>register">Sign up</a> now.</p>
                            </div> <!-- /.signin-form-wrapper -->
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

    
    $.validator.addMethod("emailcheck", function(value) {
    return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value));
},"Please enter valid email address");
$('#login-form').validate({
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
            emailcheck: "Please enter valid email address",
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
        //$(element).parent().addClass('error')
    },
    unhighlight: function (element) {
        $(element).parent().removeClass('error')
    },
    submitHandler: function(form) 
    { 
        /*var response = grecaptcha.getResponse();
        if (response.length == 0 || response.length == '') 
        {
            $('.recaptcha_error').css('display','block');
            return false;
        }
        else
        {
            $('.recaptcha_error').css('display','none');   
*/

        // console.log('user_id');return false;

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
                var d = jQuery.parseJSON(data);
                if(d.status==2)
                {
                //toastr.error(d.msg);
                $.growl.error({title: "Bidex", message: d.msg });
                return false;
                }
                if(d.status==0)
                {
                   $.growl.error({title: "Bidex", message: d.msg });
                   $('#submit_btn').prop('enabled');
                    $('.spinner-border').css('display','none');
                }
                else
                {
                    if(d.tfa_status==1)
                    {  
                        $('#submit_btn').prop('enabled');
                        //$("#tfa_verify").css('display','block');
                    }
                    else
                    {
                        if(d.login_url=='wallet')
                        {
                          window.location.href = front_url+"wallet";
                        }
                        else
                        {
                          window.location.href = front_url+"wallet";
                          // window.location.href = front_url;
                        }
                    }
                    $.growl.notice({title: "Bidex", message: d.msg });
                }
            }
        });
        return false;
       // }
    }
});
    </script>