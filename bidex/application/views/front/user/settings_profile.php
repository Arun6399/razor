<?php 
    $this->load->view('front/common/headerlogin');
    $user_id = $this->session->userdata('user_id');
    
?>
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
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">User Profile</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                            $attributes=array('id'=>'verification_profile_form',"autocomplete"=>"off"); 
                                              $action = front_url() . 'profile-edit';
                                            echo form_open_multipart($action,$attributes); 
                                        ?> 
                                            <div class="row">
                                                <div class="mb-3 col-xl-12">
                                                    <label class="form-label">Your Name</label>
                                                    <input type="text" class="form-control" name="fname" id="fname" placeholder="Name" value="<?php echo $users->bidex_fname;?>">
                                                </div>
                                                <div class="mb-3 col-xl-12">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <?php if($users->profile_picture==''){?>
                                                             <img class="me-3 rounded-circle me-0 me-sm-3"
                                                            src="<?php echo base_url();?>assets/images/user.png" width="50" height="50" alt="">
                                                        <?php } else{?>
                                                        <img class="me-3 rounded-circle me-0 me-sm-3"
                                                            src="<?php echo $users->profile_picture;?>" width="50" height="50" alt="">
                                                            <?php }?>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-0"><?php echo $users->bidex_fname;?> <?php echo $users->bidex_lname;?></h5>
                                                            <p class="mb-0">Max file size is 20mb
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="file-upload-wrapper" data-text="Change Photo">
                                                        <input name="profile" type="file"
                                                            class="file-upload-field" id="profile" accept=".png, .jpg, .jpeg">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" name="profile_form" id="profile_form" class="btn btn-success px-4">Save</button>
                                                </div>
                                            </div>
                                        <?php echo form_close();?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">User Profile</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                            $attributes=array('id'=>'verification_userprofile_form',"autocomplete"=>"off"); 
                                              $action = front_url() . 'profile-edit';
                                            echo form_open_multipart($action,$attributes); 
                                        ?> 
                                            <div class="row">
                                                <div class="mb-3 col-xl-12">
                                                    <?php $usermail = getUserEmail($users->id);?>
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="newemail" id="newemail" class="form-control" placeholder="Email" value="<?php echo ($usermail)?$usermail:'';?>" disabled> 
                                                </div>
                                                <div class="mb-3 col-xl-12">
                                                    <label class="form-label">New Password</label>
                                                    <input type="password" name="newpassword" id="newpassword" class="form-control"
                                                        placeholder="**********">
                                                    <p class="mt-2 mb-0">Enable two factor authencation on the security
                                                        page
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" name="email_form" id="email_form" class="btn btn-success px-4" style="margin-top: 10px;">Save</button>
                                                </div>
                                            </div>
                                        <?php echo form_close();?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Personal Information</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                            $attributes=array('name'=>"myform",'id'=>'myform',"autocomplete"=>"off",'class'=>"personal_validate"); 
                                              $action = front_url() . 'profile-edit';
                                            echo form_open_multipart($action,$attributes); 
                                        ?> 
                                        
                                            <div class="row">
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <label class="form-label">Your Name</label>
                                                    <input type="text" class="form-control" placeholder="Enter Your Name"
                                                        name="f_name" id="f_name" value="<?php echo $users->bidex_fname;?>">
                                                </div>
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <?php $usermail = getUserEmail($users->id);?>
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control"
                                                        placeholder="Hello@example.com" name="email" id="email" value="<?php echo ($usermail)?$usermail:'';?>" disabled>
                                                </div>
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <label class="form-label">Date of birth</label>
                                                    <input type="text" class="form-control" placeholder="10-10-2021"
                                                        id="datepicker" autocomplete="off" name="dob" value="<?php echo $users->dob;?>">
                                                </div>
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <label class="form-label">Present Address</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="56, Old Street, Brooklyn" name="street_address" id="street_address" value="<?php echo $users->street_address;?>">
                                                </div>
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <label class="form-label">Permanent Address</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="123, Central Square, Brooklyn"
                                                        name="street_address_2" id="street_address_2" value="<?php echo $users->street_address_2;?>">
                                                </div>
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <label class="form-label">City</label>
                                                    <input type="text" class="form-control" placeholder="New York"
                                                        name="city" id="city" value="<?php echo ($users->city)?$users->city:'';?>">
                                                </div>
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <label class="form-label">Postal Code</label>
                                                    <input type="text" class="form-control" placeholder="25481"
                                                        name="postal_code" id="postal_code" value="<?php echo ($users->postal_code)?$users->postal_code:'';?>">
                                                </div>
                                                <div class="mb-3 col-xl-6 col-md-6">
                                                    <label class="form-label">Country</label>
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
                                                </div>

                                                <div class="mb-3 col-12">
                                                    <button type="submit" name="personal_form" id="personal_form" class="btn btn-success px-4" style="margin-top: 10px;">Save</button>
                                                </div>
                                            </div>
                                        <?php echo form_close();?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  


<?php 
    $this->load->view('front/common/footerlogin');
    ?>

    <script type="text/javascript">
    function readURL1(input) {
      if (input.files && input.files[0]) {
        fileName1 = input.files[0].name;
        $("#filename1").text(fileName1);
        fileSize = input.files[0].size;
        if(fileSize > 20000000) { 
            $.growl.error({title: "Bidex", message: 'Maximum file size should be below 20mb' });
           //$('input[type="submit"]').attr('disabled','disabled');
           
        } else {
          
          $('#upload_pdf_error').html("");
          //$('input[type="submit"]').removeAttr('disabled');
          var reader = new FileReader();
            reader.onload = function(e) {
              //console.log(e);
                //$('#address_proof').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            //callValidProof1();
        }
        //callValidProof();
      }
    }
    $("#file-upload-field").change(function() {
      
        readURL1(this);
    });

      $.validator.addMethod('ZipChecker', function() {
    }, 'Invalid zip code');
         $.validator.addMethod("lettersonly", function(value) {
    return (/^[a-zA-Z\s]*$/.test(value));
});
         $.validator.addMethod("alphanumeric", function(value) {
    return (/^[A-Za-z0-9 _.-]+$/.test(value));
});

$('#verification_profile_form').validate({
    rules: {
        fname: {
            required: true
        }
    },
    messages: {
        fname: {
            required: "Please enter name"
        },
        
    }
});

$('#verification_userprofile_form').validate({
    rules: {
        newpassword: {
            required: true
        }
    },
    messages: {
        newpassword: {
            required: "Please enter password"
        },
        
    }
});

    $('#myform').validate({
      rules: {
        name: {
          required: true
        },
        lastname: {
          required: true
        },
        dob: {
          required: true
        },
        street_address: {
          required: true
        },
        street_address_2: {
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
        register_country: {
          required: true,
        },
        postal_code: {
          required: true,
          alphanumeric: true,
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
        },
        national_tax_number: {
            required: true
        }
      },
      messages: {
        name: {
          required: "Please enter name"
        },
        lastname: {
          required: "Please enter last name"
        },
        dob: {
          required: "Please enter DOB"
        },
        street_address: {
          required: "Please enter present address"
        },
        street_address_2: {
          required: "Please enter permanent address"
        },
        city: {
          required: "Please enter city",
          lettersonly: "<?=$this->lang->line('Please enter letters only');?>"
        },
        state: {
          required: "Please enter state",
          lettersonly: "Please enter letters only"
        },
        register_country: {
          required: "Please select country"
        },
        postal_code: {
          required: "Please enter postal code",
          alphanumeric: "please enter numbers and letters only"
        },
        phone: {
          required: "Please enter phone number"
        },
        national_tax_number: {
          required: "Please enter national tax number"
        }
      }
    });


    </script>