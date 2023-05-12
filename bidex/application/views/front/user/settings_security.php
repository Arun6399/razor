<?php $this->load->view('front/common/headerlogin');?>

<div class="page-title dashboard">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="page-title-content">
                            <p>Welcome Back,
                                <span> <?php echo $users->bidex_fname;?></span>
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        <!-- <ul class="text-end breadcrumbs list-unstyle">
                            <li><a href="settings.html">Settings </a></li>
                            <li class="active"><a href="#">Security</a></li>
                        </ul> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card sub-menu">
                            <div class="card-body">
                                <ul class="d-flex">
                                    <li class="nav-item">
                                        <a href="<?php echo base_url();?>settings_profile" class="nav-link">
                                            <i class="mdi mdi-account-settings-variant"></i>
                                            <span>Edit Profile</span>
                                        </a>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a href="settings-preferences.html" class="nav-link">
                                            <i class="mdi mdi-settings"></i>
                                            <span>Preferences</span>
                                        </a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a href="<?php echo base_url();?>settings_security" class="nav-link">
                                            <i class="mdi mdi-lock"></i>
                                            <span>Security</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url();?>settings_account" class="nav-link">
                                            <i class="mdi mdi-bank"></i>
                                            <span>Linked Account</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url();?>public_api" class="nav-link ">
                                            <i class="mdi mdi-database"></i>
                                            <span>API</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Verification</h4>
                            </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-xl-4" style="padding:25px">
                            <div>
                                <h4 class="card-title mb-3">Email Address</h4>
                                <form action="#" style="display: none;">
                                    <div class="row align-items-center">
                                        <div class="mb-3 col-xl-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    placeholder="hello@example.com ">
                                                <div class="input-group-append">
                                                    <button
                                                        class=" btn input-group-text bg-primary text-white">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="phone_verified">
                                <?php $usermail = getUserEmail($users->id);
                                      $splitemail=explode("@",$usermail);
                                      $email_user = $splitemail[0];
                                      $hideemail = substr($email_user, 0, 3);
                                      $hideemail .= "****@".substr($splitemail[1], -8);
                                ?>
                                <h5> <span><i class="fa fa-envelope"></i></span> <a href="#" class="__cf_email__" data-cfemail="167e737a7a7956736e777b667a733875797b"><?php echo ($hideemail)?$hideemail:'';?></a></h5>
                                <div class="verify">
                                    <?php if($users->verified==1){  ?>
                                    <div class="verified">
                                        <span><i class="la la-check"></i></span>
                                        <a href="#">Verified</a>
                                    </div>
                                <?php }?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4" style="padding:25px">
                           <?php if($users->bidex_phone == ''){?>
                            <div>
                                <h4 class="card-title mb-3">Phone Number</h4>
                                <form action="<?php echo base_url();?>settings_security"  style="display: block;" method="post">
                                    <div class="row align-items-center">
                                        <div class="mb-3 col-xl-12">
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    placeholder="+1 2335 2458 " name="phone_number">
                                                <div class="input-group-append">
                                                    <button
                                                        class=" btn input-group-text bg-primary text-white" name="phone_add">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                         
                        <?php } else{ ?>
                             <div class="phone_verified" style="margin-top: 33px;">
                                <h5> <span><i class="fa fa-phone"></i></span> <?php echo $users->bidex_phone;?></h5>
                                <div class="verify">
                                    <div class="verified">
                                        <span><i class="la la-check"></i></span>
                                        <a href="#">Verified</a>
                                    </div>
                                </div>
                            </div>
                        <?php }?> 
                            
                        </div>
                        <div class="col-xl-4" style="padding:25px">
                            <div>
                                <h4 class="card-title ">Google Authenticator</h4>

                            </div>
                            <div class="phone_verified">
                                <?php if($users->randcode=='enable') {  ?>
                                <div>

                                    <a href="#popup1" class="btn btn-theme px-4 py-2 me-3 my-2">
                                        <i class="fa fa-google" style="color: white;"></i> Disable</a>
                                    <!-- <a href="verify-step-1.html" class="btn btn-success px-4 py-2 my-2">Add Debit
                                        Account</a> -->
                                </div>
                                <?php } else { ?>
                                    <div>

                                    <a href="#popup1" class="btn btn-theme px-4 py-2 me-3 my-2">
                                        <i class="fa fa-google" style="color: white;"></i> Enable</a>
                                    <!-- <a href="verify-step-1.html" class="btn btn-success px-4 py-2 my-2">Add Debit
                                        Account</a> -->
                                </div>
                                <?php } ?>
                            </div>
                        </div>


                </div>
                </div>
                </div>
                </div>

                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Security</h4>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="id_info">
                                        <h4>KYC Verification </h4>   </div>
                                    <!-- <div class="col-xl-4">
                                        <div class="id_card">
                                            <img src="images/id.png" alt="" class="img-fluid">
                                        </div>
                                    </div> -->
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-4">

                                            <!-- <p class="mb-1 mt-3">ID: 0024 5687 2254 3698 </p>
                                            <p class="mb-1">Status: <span class="font-weight-bold">Verified</span></p> -->
                                            <ul style="padding: 0px;">
                                                <li class="acc"><i class="fa fa-check" aria-hidden="true" style="font-weight: 100;color: green;"></i> Fiat Deposit and Withdrawal Limit <br><strong style="margin: 16px;">$50K Daily</strong></li>
                                                <li class="acc"><i class="fa fa-check" aria-hidden="true" style="font-weight: 100;color: green;"></i> Crypto Withdrawal Limit <br><strong style="margin: 16px;">100 BTC Daily</strong></li>

                                              </ul>
                                              </div>

                                              <div class="col-xl-4">


                                                <ul style="padding: 0px;">
                                                    <li class="acc"><i class="fa fa-check" aria-hidden="true" style="font-weight: 100;color: green;"></i> P2P Transaction Limit <br><strong style="margin: 16px;">Unlimited</strong></li>
                                                    <li class="acc"><i class="fa fa-check" aria-hidden="true" style="font-weight: 100;color: green;"></i> Other Features <br><strong style="margin: 16px;">LPD /OTD/ Binance Card</strong></li>

                                                  </ul>

                                        </div>
                                        <div class="col-xl-2"></div>
                                        <div class="verify" style="padding-top: 20px;">
                                            <?php if($users->verify_level2_status=='Pending'){?>
                                            <div class="not-verify" style="text-align: center;">
                                                <span style="border-radius: 50%;background-color: red;"><i class="la la-close"></i></span>
                                                <a href="<?php echo base_url();?>kyc" style="color: red;">Pending</a>
                                            </div>
                                        <?php } elseif($users->verify_level2_status=='Rejected'){?>
                                            <div class="not-verify" style="text-align: center;">
                                                <span style="border-radius: 50%;background-color: red;"><i class="la la-close"></i></span>
                                                <a href="<?php echo base_url();?>kyc" style="color: red;">Rejected By Admin</a>
                                            </div>

                                        <?php } ?>
                                        </div>
                                    <!-- <h4 class="card-title mb-3">To Verify KYC</h4> -->
                                    <?php if($users->verify_level2_status==''){?>
                                    <p style="text-align: center;"><a href="<?php echo base_url();?>kyc" class="btn btn-success mt-3">Verify Now</a></p>
                                <?php } else if($users->verify_level2_status=='Completed'){?>
                                    <p style="text-align: center;"><a href="<?php echo base_url();?>kyc" class="btn btn-success mt-3">Completed</a></p>
                                <?php } ?>
                                    </div>
                                </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="popup1" class="overlay">
            <div class="popup">
                <h2>Enable Google 2FA</h2>
                <a class="close" href="#">&times;</a> 
                <div>
             <div class="row">
                 <div class="col-12 col-lg-12 d-flex">

                   <p style="color: black;">Scan QR code or enter secret key to link your Google Authenticator to BIDEX account.
                   </p>
                 </div>
                 <div class="space-20"></div>
                 <div class="col-12 col-lg-12" >
                     <p style="text-align: center;">
                   <img src="<?php echo $url;?>" style="width: 100px;">

                </p>
                <h4 style="color: black;text-align: center;"><?php echo $secret;?></h4>
                </div>
                                   </div>

               <div class="row">
                 <div class="col-12 col-lg-12 d-flex">

                   <p style="color: black;">Enter the 6-digit verification code on your Google Authenticator

                   </p>
                 </div>
                 <div class="space-20"></div>
<form  method="post" action="<?php echo base_url()?>settings_security" name="securityset" id="securityset">
    <input type="hidden" name="secret" id="secret" value="<?php echo $secret;?>">
                   <div class="col-12 col-lg-12" >
                 <input type="text" name="code" id="code" class="form-control" aria-describedby="emailHelp" placeholder="Google Code" style="background: #f5f5f5;width: 100%;">
                 <div style="padding: 20px;"></div>
<span id="file_error"></span>
                 <div class="form-footer">
                   <button name="tfa_sub" id="tfa_sub" class="btn btn-theme" style="width: 100%;">Submit</button>
                   </div>
                 </div>

</form>

               <div class="space-40"></div>




                   </div>



                 </div>
            </div>
        </div>

<?php 
    $this->load->view('front/common/footerlogin');
    ?>        


 
 <script type="text/javascript">
         
         $('#securityset').validate({
            errorElement: 'span',
        rules: {
            code: {
                required: true
            }
                     
           
        },
        messages: {
            code: {
                required: "Please Enter Your 2fa code"
            }
            
                
        },
      errorPlacement: function(error, element) {
      $(element).parent().parent().parent().parent().find('#file_error').html(error);
    }
    });
 </script>