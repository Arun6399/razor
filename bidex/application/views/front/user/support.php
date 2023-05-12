<?php 
    $this->load->view('front/common/headerlogin');
    $user_id = $this->session->userdata('user_id');
?>
<div class="verification mb-5">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center  my-5">
                    <div class="col-xl-5 col-md-6">
                        <div class="auth-form card">
                            <div class="card-header">
                                <h4 class="card-title">Support</h4>
                            </div>
                            <div class="card-body">
                                
                                    <?php $attributes=array('id'=>'support_form','class'=>'identity-upload'); echo form_open_multipart($action,$attributes); ?>  
                                    <div class="row">
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Name </label>
                                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Your Name Here">
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Mail ID </label>
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Mail ID Here">
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Subject </label>
                                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter Your Subject Here">
                                        </div>


                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Image </label>
                                            <div class="file-upload-wrapper" data-text="Screenshot.pdf/jpg">
                                                <input name="supportpic" type="file" class="file-upload-field" accept=".jpg, .jpeg, .png, .pdf">
                                            </div>
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Comments </label>
                                            <textarea type="textarea" name="comments" id="comments" class="form-control" aria-describedby="emailHelp" placeholder="Enter your Comments" style="height: 120px;"></textarea>
                                        </div>

                                        <div class="form-group text-left">
                                            <div class="g-recaptcha" id="g-recaptcha" data-sitekey="6LdKDyEfAAAAABa2nRE4xeBT1ml4ggKrR4J2R6uk"></div>
                                        </div>

                                            <label id="cp_error" class="error"></label>

                                        <div class="text-center col-12">
                                            <!-- <a href="settings-account.html" class="btn btn-primary mx-2">Back</a>. -->
                                            <button type="submit" name="submit_tick" class="btn btn-theme mx-2">Submit</button>
                                        </div>
                                    </div>
                                 <?php echo form_close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


  


    <!--========================== Footer ============================-->
   <?php 
    $this->load->view('front/common/footerlogin');
    $user_id    = $this->session->userdata('user_id');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $get_os     = $_SERVER['HTTP_USER_AGENT'];
    ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

     $('#support_form').validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true
            },
            subject: {
                required: true
            },
            comments: {
                required: true
            }
        },
        messages: {
            name: {
                required: "Please enter name"
            },
            email: {
                required: "Please enter email"
            },
            subject: {
                required: "Please enter subject"
            },
            comments: {
                required: "Please enter comments"
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
 $('input[type=file]#image').change(function(){ 
    var file_name = $("#image").val();
    $('#image_name').html(file_name);
    var ext = $('#image').val().split('.').pop().toLowerCase();
    if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
        $("#img_error").html("Please upload proper file format");
        $(':button[type="submit"]').prop('disabled', true);
    }
    else{  
     $("#img_error").html('');
     $(':button[type="submit"]').prop('disabled', false); 
    }
  });

 $('#image').change(function() {
    var filename = $('#image').val();
    if (filename.substring(3,11) == 'fakepath') {
        filename = filename.substring(12);
    } 
    $("label[for='inputGroupFile02']").html(filename);
    
});
</script>