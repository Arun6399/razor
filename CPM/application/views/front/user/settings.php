<?php 
$this->load->view('front/common/header');
?>

					<div class=" cpm_mdl_cnt">
						<div class="container">
							<div class="row ">
								<div class="col-lg-12">
									<div class="cpm_set_ul">
									<div class="cpm_set_ul_scrl">
										<div data-sett="#2fa" class="cpm_set_li act_th"><i class="fal fa-lock-alt"></i>2FA Authentication</div>
										<div data-sett="#change" class="cpm_set_li "><i class="fal fa-key"></i>Change Password</div>
										<div data-sett="#bank" id="bank_section" class="cpm_set_li"><i class="fal fa-briefcase"></i>Bank Details</div>
									</div>
									</div>
								</div>
								<div class="col-lg-12">
								   <div class="cpm_setting_set ">
								   	 		<?php 
                                            $attributes1=array('id'=>'security','class'=>'deposit_form');
                                            $action1=base_url().'security';
                                            echo form_open($action1,$attributes1); 
                                            if($users->randcode=='' || $users->randcode=='disable')
                                            {
                                              $btn_content = $this->lang->line('ENABLE');
                                            }
                                             else{
                                              $btn_content = $this->lang->line('DISABLE');
                                            }                                     
                                          ?>
										<div class="cpm_set_tabs act_tab cpm_2fa_sec" data-settab="#2fa">
											<div class="row">
												<div class="col-lg-4 col-md-12">
													<div class="cpm_hd_text  ">Download Application</div>
											<p style="font-size:14px; color:#666 ">Download Google Authenticator from Playstore or Apple store</p>
											<img src="<?php echo front_img();?>gogauth.png" class="cpm_gog_ico">
												</div>
												<div class="col-lg-4 col-md-12 cpm_2fa_cntr_sec">
													<div class="cpm_hd_text  ">Setup Authenticator</span></div>
											<p style="font-size:14px; color:#666 ">Scan qr code or type code to setup your Google Authenticator </p>
											<div class="row align-items-center">
												<div class="col-5 col-md-12">
												  
													   <img src="<?php echo $url;?>" class="cpm_dep_qr_img">
													   <div class="cpm_2fa_text">(or)</div>
												   </div>
												<div class="col-7 col-md-12">
													<div class="cpm_log_frm_s cpm_goog_code_out" style="margin-bottom: 30px">
														<div class="cpm_log_frm_s_lbl">Type The Key Manually</div>
														<div class="cpm_log_frm_s_input cpm_goog_code "><?php echo $secret;?></div>
													   
													</div>
												</div>
												
												</div>
												</div>
												<div class="col-lg-4 col-md-12">
													<div class="cpm_hd_text  ">Use Authenticator</div>
													<p style="font-size:14px; color:#666 ">Enter the 6 digits authentication code provided by Google Authenticator</p>
													<div class="row mt-2 align-items-center">
														<div class="col-md-12">
															<div class="cpm_log_frm_s ">
																<div class="cpm_log_frm_s_lbl">Enter Authenticator Code</div>
																<input type="text" class="cpm_log_frm_s_input"id="code" name="code" placeholder="Verification Code">
																 <input type="hidden"  name="secret" id="secret" value="<?php echo $secret;?>">
															
														</div>
														<button class="cpm_log_frm_btn" type="submit"><?php echo $btn_content;?> 2FA</button>
														</div>
														</div>
												</div>

											</div>
										</div>   
										<?php echo form_close(); ?>    
							
										
										<div class="cpm_set_tabs " data-settab="#change">
											<?php 
		                                     $attributes=array('id'=>'change_password1','class'=>'change_password_form');
		                                     $action=base_url().'settings';
		                                     echo form_open($action,$attributes); ?>
											<div class="cpm_hd_text  ">Change <span>Password</span></div>
												<div class="row">
													<div class="col-md-12">
														<div class="cpm_log_frm_s ">
															<div class="cpm_log_frm_s_lbl">Old Password</div>
															<input type="password" class="cpm_log_frm_s_input" name="oldpass" id="oldpass" placeholder="Enter Your Current Password">
															<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
														</div>
													</div>
													<div class="col-lg-6 col-md-12">
														<div class="cpm_log_frm_s ">
															<div class="cpm_log_frm_s_lbl">New Password</div>
															<input type="password" class="cpm_log_frm_s_input" name="newpass" id="newpass" placeholder="Enter Your New Password">
															<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
															<div class="error"></div>
														</div>
													</div>
													<div class="col-lg-6 col-md-12">
														<div class="cpm_log_frm_s">
															<div class="cpm_log_frm_s_lbl">Confirm Password</div>
															<input type="password" class="cpm_log_frm_s_input" name="confirmpass" id="confirmpass" placeholder="Enter Your Confirm New Password">
															<i class="fal fa-eye cpm_log_frm_s_input_pass_ico"></i>
														</div>
													</div>
						
												</div>
										<div class="row mt-2">
											<div class="col-md-12">
												<button class="cpm_log_frm_btn" name="chngpass" type="submit">Submit</button>
												<!-- <button class="cpm_log_frm_btn cancel_btnn" type="submit">Cancel</button> -->
											</div>
									
									</div>
									<?php echo form_close();?>
								</div>
								
										<div class="cpm_set_tabs" data-settab="#bank">
											<?php
		                                      $attributes=array('id'=>'bankwire',"autocomplete"=>"off","class"=>"mt-4");
		                                      $action = front_url() . 'update_bank_details';
		                                      echo form_open_multipart($action,$attributes);
		                                  ?>
											<div class="cpm_hd_text  ">Bank <span>Details</span></div>
						
						
											<div class="row">


												<div class="col-lg-4 col-md-4">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Fiat Currency</div>
														<select class="cpm_log_frm_s_input" onChange="change_bank(this)" id="currency" name ="currency">
															 <?php
				                                            if(count($currencies)>0)
				                                            {
				                                                foreach($currencies as $cur)
				                                                {
				                                                	if(!empty($user_bank))
				                                                       

				                                        ?>
				                                                    <option value="<?php echo $cur->id;?>" <?php if($act_cur==$cur->id){ echo "selected"; } ?> >
				                                                        <?php echo $cur->currency_symbol;?>   
				                                                    </option>
				                                        <?php


				                                                }
				                                            }
				                                        ?>
														</select>
													</div>
												</div>


												<div class="col-lg-4 col-md-4">
													<div class="cpm_log_frm_s ">
														<div class="cpm_log_frm_s_lbl">Account Holder Name</div>
														<input type="text" class="cpm_log_frm_s_input" id="bank_account_name" name="bank_account_name" value="<?php echo $user_bank->bank_account_name;?>" placeholder="<?php echo $this->lang->line('Account Holder Name')?>">
													  
													</div>
												</div>
												<div class="col-lg-4 col-md-4">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Account Number</div>
														<input type="number" class="cpm_log_frm_s_input" id="bank_account_number" name="bank_account_number"  value="<?php echo $user_bank->bank_account_number;?>"  placeholder="<?php echo $this->lang->line('Account Number')?>">
														<div class="error"></div>
													</div>
												</div>
											 
						
												<div class="col-lg-4 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Bank Swift / Ifsc</div>
														<input type="text" class="cpm_log_frm_s_input" value="<?php echo $user_bank->bank_swift;?>" id="bank_swift" name="bank_swift" placeholder="IFSC / Swift">
													  
													</div>
												</div>



												<!-- <div class="col-lg-3 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">BIC</div>
														<input type="text" class="cpm_log_frm_s_input" >
													  
													</div>
												</div>
												<div class="col-lg-3 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">IBAN</div>
														<input type="text" class="cpm_log_frm_s_input" >
													  
													</div>
												</div> -->
												
												<div class="col-lg-4 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Bank Name</div>
														<input type="text" class="cpm_log_frm_s_input" value="<?php echo $user_bank->bank_name;?>" id="bank_name" name="bank_name" placeholder="<?php echo $this->lang->line('Bank Name')?>">
													  
													</div>
												</div>
												<div class="col-lg-4 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Bank Address</div>
														<input type="text" value="<?php echo $user_bank->bank_address;?>" class="cpm_log_frm_s_input" id="bank_address" name="bank_address" placeholder="<?php echo $this->lang->line('Bank Address')?>">
													  
													</div>
												</div>
												<div class="col-lg-3 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Bank City</div>
														<input type="text" value="<?php echo $user_bank->bank_city;?>" class="cpm_log_frm_s_input" id="bank_city" name="bank_city" placeholder="Bank City">
													
													</div>
												</div>
												<div class="col-lg-3 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Country</div>
														<select class="cpm_log_frm_s_input" name="bank_country" id="bank_country">
															<?php if($countries) {
				                                            $banks = ($user_bank->bank_country!='')?$user_bank->bank_country:'';
				                                            foreach($countries as $co) {
				                                              ?>
				                                              <option <?php if($co->id==$banks) { echo "selected"; } else { } ?> value="<?php echo $co->id; ?>"><?php echo $co->country_name; ?></option>
				                                              <?php
				                                            }
				                                          } ?>
														</select>
						
													
													</div>
												</div>
												<div class="col-lg-3 col-md-6">
													<div class="cpm_log_frm_s">
														<div class="cpm_log_frm_s_lbl">Postal Code</div>
														<input type="number" class="cpm_log_frm_s_input" value="<?php echo $user_bank->bank_postalcode;?>" id="bank_postalcode" name="bank_postalcode" placeholder="Zip Code">
													
													</div>
												</div>
											</div>
											<div class="row mt-2">
											<div class="col-md-12">
											<?php if($user_bank->status!='Verified'){ ?>
											<button class="cpm_log_frm_btn" type="submit">Update</button>
											<!-- <button class="cpm_log_frm_btn cancel_btnn" type="submit">Cancel</button> -->
											<?php }
											else
											{
												echo "<a  class='cpm_log_frm_btn cancel_btnn'>Your Account Verified!</a>";
											}
											 ?>
											
											</div>
											</div>

											 <?php echo form_close();?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
<?php 
$this->load->view('front/common/footer');
?>

<script type="text/javascript">
	


$(document).ready(function() {

	var uri_seg = '<?php echo $this->uri->segment(2);?>';
	if(uri_seg!='')
	{
		// console.log(uri_seg);	
		$('#bank_section').click();
	}
	

});


   $('#security').validate({
        rules: {
            code: {
                required: true,
                number: true,
                minlength: 6
            }
        },
        messages: {
            code: {
                required: 'Please enter code',
                number: 'Please enter valid code',
                minlength:'Please 6 digit valid code'
            }
        }
    });

  $('#change_password1').validate({
      rules: {
        oldpass: {
          required: true,
          remote: {
                    url: front_url+'oldpassword_exist',
                    type: "post",
                    csrf_token : csrfName,
                    data: {
                        oldpass: function() {
                        return $( "#oldpass" ).val();
                        }
                    }
                }
        },
       newpass: {
          required: true
        },
        confirmpass: {
          required: true,
          equalTo : "#newpass"
        }
    },
     messages: {
        oldpass: {
          required: "Please enter Old Password",
           remote: "Invalid Old Password"
        },
        newpass: {
          required: "Please enter New Password"
        },
        confirmpass: {
          required: "Please enter Confirm Password",
          equalTo : "Confirm Password not matches with New Password"
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
			var $form = $(form);
         	form.submit();
		}	
});  

   $('#bankwire').validate({
    rules: {
       
        bank_name: {
            required: true
        },
        bank_account_number: {
            required: true
        },
        bank_account_name: {
            required: true,
            lettersonly: true
        },
        bank_swift: {
            required: true
        },
        bank_address: {
             required: true
        },
        bank_city: {
            required: true,
            lettersonly: true
        },
        bank_country: {
            required: true,
           // lettersonly: true
        },
        bank_postalcode: {
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
        }
    },
    messages: {
        bank_name: {
            required: "Please enter bank name"
        },
        bank_account_number: {
            required: "Please enter bank account number"
        },
        bank_account_name: {
            required: "Please enter bank account name",
            lettersonly: "Please enter letters only"
        },
        bank_swift: {
            required: "Please enter bank swift"
        },
        bank_address: {
            required: "Please enter bank address"
        },
        bank_city: {
            required: "Please enter bank city",
            lettersonly: "Please enter letters only"
        },
        bank_country: {
            required: "Please enter bank bank country"
        },
        bank_postalcode: {
            required: "Please enter postal code"
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
			var $form = $(form);
         	form.submit();
		}
});

function copyToClipboard(text) {
    var copyText = document.getElementById("authenticator_key");  
    var input = document.createElement("textarea");
    input.value = copyText.textContent;
    document.body.appendChild(input);
    input.select();
    document.execCommand("Copy");
    input.remove();
}

// var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';

//         $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
//             if (options.type.toLowerCase() == 'post') {
//                 options.data += '&'+csrfName+'='+$("input[name="+csrfName+"]").val();
//                 if (options.data.charAt(0) == '&') {
//                     options.data = options.data.substr(1);
//                 }
//             }
//         });

//         $( document ).ajaxComplete(function( event, xhr, settings ) {
//             if (settings.type.toLowerCase() == 'post') {
//                 $.ajax({
//                     url: front_url+"get_csrf_token", 
//                     type: "GET",
//                     cache: false,             
//                     processData: false,      
//                     success: function(data) {
//                             var dataaa = $.trim(data);
//                          $("input[name="+csrfName+"]").val(dataaa);
//                     }
//                 });
//             }
//         });

 function change_bank(coin)
 	{

 		var currency = coin.value;

 		// console.log(currency);

 		var base_url='<?php echo base_url();?>';
		window.location.href = base_url+'bank_details/'+currency;
	}



</script>