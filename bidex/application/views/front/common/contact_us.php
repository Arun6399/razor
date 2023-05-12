<?php 
    $this->load->view('front/common/header_cms');
$user_id = $this->session->userdata('user_id');
$settings = $site_common['site_settings']; 

?>
    

<div class="solid-inner-banner">
        <h2 class="page-title" style="color: white;">Contact Us</h2>
        <ul class="page-breadcrumbs">
          <li><a href="<?php echo base_url();?>">Home</a></li>
          <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
          <li>Contact</li>
        </ul>
      </div> <!-- /.solid-inner-banner -->



      <!--
      =============================================
        Contact Us
      ==============================================
      -->
      <div class="contact-us-section pt-150 mb-200">
        <div class="container">
          <div class="row">
            <div class="col-lg-6">
              <div class="contact-form">
                        <form class="form" id="contact-form" action="<?php echo base_url();?>contact_us" data-toggle="validator" method="post">
                          <div class="messages"></div>
                          <div class="controls">

                            <div class="form-group">
                                  <input id="name" type="text" name="name" placeholder="Name*" >
                                  
                              </div>
                              <div class="form-group">
                                  <input id="email" type="email" name="email" placeholder="Email Address*" >
                                  
                              </div>

                              <div class="form-group">
                                  <input id="subject" type="text" name="subject" placeholder="Subject*" >
                                  
                              </div>

                              <div class="form-group">
                                <textarea id="message" name="message" class="form_message" placeholder="Your Message*" ></textarea>
                                
                              </div>

                              <div class="form-group text-left">
                                    <div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LdKDyEfAAAAABa2nRE4xeBT1ml4ggKrR4J2R6uk"></div>
                                </div>

                                <label id="cp_error" class="error"></label>

                              <button type="submit" class="theme-button-two" name="contact" id="contact">Send Message</button>
                          </div> <!-- /.controls -->
                        </form>
                    </div> <!-- /.contact-form -->
            </div> <!-- /.col- -->

            <div class="col-lg-6">
              <div class="contact-info">
                <h2 class="title">Don’t Hesitate to contact with us for any kind of information</h2>
                <p>Call us for immediate support to this number</p>
                <a href="#" class="call">088 130 629 8615</a>
                <ul>
                  <li><a href="<?php echo $site_common['site_settings']->facebooklink;?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                  <li><a href="<?php echo $site_common['site_settings']->twitterlink;?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                  <li><a href="<?php echo $site_common['site_settings']->linkedin_link;?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                </ul>
              </div> <!-- /.contact-info -->
            </div>
          </div> <!-- /.row -->
        </div> <!-- /.container -->
      </div> <!-- /.contact-us-section -->

<?php $this->load->view('front/common/footer_cms');?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {

        $('#contact-form').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                },
                subject: {
                    required: true
                },
                message: {
                    required: true,
                    rangelength:[0,900]
                }
            },
            messages: {

                name: {
                    required: "Please enter name"
                },
                email: {
                    required: "Please enter email",
                    email: "Please enter valid email address",
                },
                subject: {
                    required: "Please enter subject"
                },
                message: {
                    required: "Please enter comments",
                    rangelength: "You are allow upto 900 characters."
                }
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



<script type="text/javascript">

    
    var base_url='<?php echo base_url();?>';
    var front_url='<?php echo front_url();?>';
    var user_id='<?php echo $user_id;?>';
    var ip_address = '<?php echo $ip_address;?>';
    var get_os     = '<?php echo $get_os;?>';

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

                     $("input[name="+csrfName+"]").val(data);
                }
            });
        }
    });
</script>