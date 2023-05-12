<?php 
$this->load->view('front/common/header');
?>

				<div class=" cpm_mdl_cnt  login_page">
					<div class="container">
						<div class="row align-items-center">
							 <div class="col-lg-12" >
								<div class="cpm_hd_text cpm_clr_blue_b text-center">Profile</div>
									<?php 
	                                    $attributes=array('id'=>'verification_form',"autocomplete"=>"off"); 
	                                      $action = front_url() . 'profile-edit';
	                                    echo form_open_multipart($action,$attributes); 
	                                ?>
									<div class="cpm_log_set bx" id="box">
										<div class="row">
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Full Name</div>
													<input type="text" id="firstname" name="firstname" value="<?php echo $users->cpm_fname; ?>" class="cpm_log_frm_s_input">
	
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Last Name</div>
													<input type="text" id="lastname" name="lastname" value="<?php echo $users->cpm_lname; ?>" class="cpm_log_frm_s_input">
	
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<?php $usermail = getUserEmail($users->id);?>
													<div class="cpm_log_frm_s_lbl">Email</div>
													<input type="text" d="email" name="email" disabled value="<?php echo ($usermail)?$usermail:'';?>" class="cpm_log_frm_s_input" >
	
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Phone Number</div>
													<input type="text" id="phone" name="phone" value="<?php echo ($users->cpm_phone)?$users->cpm_phone:'';?>" class="cpm_log_frm_s_input" >
												
	
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Address</div>
													<input type="text" id="address" name="address" value="<?php echo $users->street_address;?>" class="cpm_log_frm_s_input" >
	
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">City</div>
													<input type="text" id="city" name="city" value="<?php echo ($users->city)?$users->city:'';?>" class="cpm_log_frm_s_input" >
	
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Country</div>
													
													<select class="cpm_log_frm_s_input" name="register_country" id="register_country">
													<!-- 	<option value="0">India</option>
														<option value="0">America</option> -->
														<?php if($countries) {
				                                            foreach($countries as $co) {
				                                              ?>
				                                              <option <?php if($co->id==$users->country) { echo "selected"; } ?>
				                                              value ="<?php echo $co->id; ?>"><?php echo $co->country_name; ?></option>
				                                              <?php
				                                            }
				                                          } ?>


													</select>
	
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Postal Code</div>
													<input type="text" id="postal_code" name="postal_code" value="<?php echo ($users->postal_code)?$users->postal_code:'';?>" class="cpm_log_frm_s_input" >
													
	
												</div>
											</div>
											
											<div class="col-lg-12 col-md-12">
												<div class="cpm_log_frm_s">
													<div class="cpm_log_frm_s_lbl">Photo</div>
													<input type="file" name="profile_photo" onchange="Imgupload(this,'profile_img')"  id="profile-picture" value="<?php echo $users->profile_picture; ?>" class="cpm_log_frm_s_input" >
													<div class="error"></div>
	
												</div>
											</div>

											<div class="col-lg-6 col-md-6" id="pro-img">
												<div class="cpm_log_frm_s">
													<?php if(!empty($users->profile_picture)) { ?>
													<img src="<?php echo $users->profile_picture;?>" id="profile_img"  style="height: 100px;width: 100px;" ><?php } else { ?>

														<img src="" id="profile_img"  style="height: 100px;width: 100px;" >

													<?php } ?>
												</div>
											</div>


										</div>

										<div id="image-for-crop">

										</div>


										<a class="cpm_avtr_tot_li cpm_avtr_tot_li_log" onclick='screenshot();' >Screen Shot</a>


											<button class="cpm_log_frm_btn" id="submit_btn" type="submit"><i class="ti-lock"></i>Update Now</button>
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


function screenshot(){
         html2canvas(document.getElementById('box')).then(function(canvas) {
            document.body.appendChild(canvas);
            $('.scr-shot').append(canvas); 

            console.log(canvas);
            const image = new Image();
			image.id = "pic";
			image.src = canvas.toDataURL();
			document.getElementById("image-for-crop").appendChild(image);




         });
      }




	  $('#verification_form').validate({
      rules: {
        firstname: {
          required: true
        },
        lastname: {
          required: true
        },
        address: {
          required: true
        },
        city: {
          required: true,
          lettersonly: true
        },
        state: {
          required: true,
          lettersonly: true
        },
        postal_code: {
          required: true,
          number: true,
          maxlength: 7,
          ZipChecker: function(element) {
          values=$("#postal_code").val();

           if( values =="0" || values =="00" || values =="000" || values =="0000" || values =="00000"  || values =="000000"   || values =="0000000" )
           {
              return true;
              
           }
           
           }

        },
        phone: {
          required: true
        }
      },
      messages: {
        firstname: {
          required: "Please enter first name"
        },
        lastname: {
          required: "Please enter last name"
        },
        address: {
          required: "Please enter address"
        },
        city: {
          required: "Please enter city",
          lettersonly: "Please enter letters only"
        },
        state: {
          required: "Please enter state",
          lettersonly: "Please enter letters only"
        },
        postal_code: {
          required: "Please enter postal code"
        },
        phone: {
          required: "Please enter phone number"
        }
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
          $(element).parent().addClass('fail_vldr');
        },
        unhighlight: function (element) {
          $(element).parent().removeClass('error');
          $(element).parent().removeClass('fail_vldr');
        },
        submitHandler: function(form) 
        {
        	$('#submit_btn').prop('disabled');
        	var $form = $(form);
         	form.submit();
          
        }
    });

	function Imgupload(input,src)
	{	
		
		  if (input.files && input.files[0]) {
          var reader = new FileReader();
		  reader.onload = function(e) {
            $('#'+src).attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]); 
        }
	}


</script>