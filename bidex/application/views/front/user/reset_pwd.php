<?php $this->load->view('front/common/header_login.php');?>

            <!-- ===================================================
                Loading Transition
            ==================================================== -->
            <!-- Preloader -->



            <!--
            =============================================
                Sidebar Menu
            ==============================================
            -->
            <!-- <div id="sidebar-menu" class="eCommerce-side-menu">
                <div class="inner-wrapper">
                    <div class="logo-wrapper">
                        <button class="close-button"><img src="images/icon/icon43.svg" alt=""></button>
                        <img src="images/final.png" style="width: 50px;" alt="">
                    </div>

                    <div class="main-menu-list">
                        <ul>
                        <li class="nav-item active ">
                            <a href="index.html" class="nav-link " >Home</a>

                        </li>
                        <li class="nav-item  position-relative">
                            <a class="nav-link " href="#">About Us</a>

                        </li>
                        <li class="nav-item  position-relative">
                            <a class="nav-link" href="#">Market</a>

                        </li>
                        <li class="nav-item ">
                            <a class="nav-link " href="#" >Exchange</a>

                        </li>
                        <li class="nav-item  position-relative">
                            <a class="nav-link" href="#" >Swap</a>

                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link " href="#" >News</a>

                        </li >

                            <li class="nav-item position-relative"><a class="nav-link " href="#">Contact</a></li>
                        </ul>
                    </div>
                    <form action="#" class="eCommerce-search">
                        <input type="text" placeholder="Search here">
                        <i class="fa fa-search icon" aria-hidden="true"></i>
                    </form>
                    <p class="copy-right">&copy; 2022 All Right Reserved by Bidex</p>
                </div>
            </div> -->

            <!-- #sidebar-menu -->


            <!--
            =============================================
                Theme E-Commerce Menu
            ==============================================
            -->
            <!-- <div class="theme-Ecommerce-menu">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="left-content">
                        <ul>
                            <li>
                                <button class="menu-button sidebar-menu-open"><img src="images/logo/menu.svg" alt=""></button>
                            </li>
                            <li class="logo"><a href="index.html"><img src="images/final.png" style="width: 80px;" alt=""></a></li>
                        </ul>
                    </div>


                </div>
            </div> -->
             <!-- /.theme-Ecommerce-menu -->

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
                            <div class="signUp-illustration"><img src="<?php echo base_url();?>assets/images/forget.png" alt=""></div>

                        </div>
                        <div class="col-lg-6 ml-auto">
                            <!-- <p style="text-align: center;"><a href="index.html"><img src="images/final.png" style="width: 150px;" alt=""></a></p> -->
                            <div class="signin-form-wrapper">
                                <div class="title-area" style="text-align: center;">
                                    <h3 style="font-weight: 700;
                                    font-family: 'open sans';">Reset Password</h3>

                                </div> <!-- /.title-area -->
                                <?php 
                        $action1 =""; $attributes=array('id'=>'login-form'); 
                        echo form_open($action1,$attributes);
                ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Reset Password</label>
                                                <input type="password" name="reset_password" id="reset_password">

                                            </div> <!-- /.input-group -->
                                        </div> <!-- /.col- -->
                                        <div class="col-12">
                                            <div class="input-group">
                                                <label style="font-family: 'open sans';">Confirm Reset Password</label>
                                                <input type="password" id="reset_cpassword" name="reset_cpassword">

                                            </div>
                                        </div> 
                                    </div>
                                    <!-- <div class="agreement-checkbox d-flex justify-content-between align-items-center">
                                        <div>
                                            <input type="checkbox" id="remember">
                                            <label for="remember" style="font-family: 'open sans';">Remember Me</label>
                                        </div>
                                        <a href="#" style="font-family: 'open sans';">Forget Password?</a>
                                    </div> -->
                                    <p style="text-align: center;"><button class="solid-button-one" style="text-transform:uppercase;font-family: 'open sans'; ">Request</button></p>
                                <?php echo form_close();?>
                                
                            </div> <!-- /.signin-form-wrapper -->
                        </div>
                    </div>
                </div>
            </div> <!-- /.signUp-page -->









 
        </div> <!-- /.main-page-wrapper -->



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
    
    $.validator.addMethod("emailcheck", function(value) {
        return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value));
    },"Please enter valid email address"); 

      $('#login-form').validate({
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
    } 
});
    </script>