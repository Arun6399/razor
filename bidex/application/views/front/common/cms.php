<?php 
    $this->load->view('front/common/header_cms');
    $user_id = $this->session->userdata('user_id');

     $settings = $site_common['site_settings']; 
    $sitelan = $this->session->userdata('site_lang');
    $heading = $sitelan."_heading";
    $meta_description = $sitelan."_meta_description";
    $meta_keywords = $sitelan."_meta_keywords";
    $title = $sitelan."_title";
    $copy_right_text = $sitelan."_copy_right_text";
    $cms_title = $sitelan."_title";
    $content_description = $sitelan."_content_description";
    $lang_id = $this->session->userdata('site_lang');
?>
    <!--========================== Banner Section ============================-->
 
<!-- breadcrumb -->
<div class="solid-inner-banner">
                <h2 class="page-title" style="color: white;"><?php echo $cms->english_heading; ?></h2>
                <ul class="page-breadcrumbs">
                    <li><a href="<?php echo base_url();?>">Home</a></li>
                    <li><i class="fa fa-angle-right" aria-hidden="true"></i></li>
                    <li><?php echo $cms->english_heading; ?></li>
                </ul>
            </div> <!-- /.solid-inner-banner -->


<?php echo $cms->english_content_description; ?>



   <?php 
    $this->load->view('front/common/footer_cms');
    ?>

<!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> -->
    <!-- <script type="text/javascript">
    $(document).ready(function() {

         $.validator.methods.email = function( value, element ) {
            return this.optional( element ) || /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test( value );
          }
        $('#theform').validate({
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
                comments: {
                    required: true,
                    rangelength:[0,900]
                }
            },
            messages: {
                name: {
                    required: "<?php echo $this->lang->line('Please enter name');?>",
                },
                email: {
                    required: "<?php echo $this->lang->line('Please enter email');?>",
                    email: "<?php echo $this->lang->line('Please enter valid email address');?>",
                },
                subject: {
                    required: "<?php echo $this->lang->line('Please enter subject');?>"
                },
                comments: {
                    required: "<?php echo $this->lang->line('Please enter comments');?>",
                    rangelength: "<?php echo $this->lang->line('You are allow upto 900 characters.');?>"
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

  
</script> -->










