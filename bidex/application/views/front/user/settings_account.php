 <?php $this->load->view('front/common/headerlogin');?>
 <style type="text/css">
     .linked_account .verifys .verified span {
    background: red;
    color: #fff;
    padding: 10px;
    border-radius: 50px;
    height: 40px;
    width: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 5px;
    margin-right: 15px;
    font-weight: bold;
}

.verifys i {
    background: red;
    color: white;
    border-radius: 20px;
    padding: 2px;
}

.linked_account .verifys .verified {
    display: flex;
    align-items: center;
    font-weight: 500;
    }

 </style>
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
                                        <a href="<?php echo base_url()?>settings_profile" class="nav-link">
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
                                <h4 class="card-title">Linked Account</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table linked_account ">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <span class="me-3"><i class="fa fa-bank"></i></span>
                                                        <?php  $bank = $bank_details->bank_account_number;
                                                            $splitemail=explode("*",$bank);
                                     $email_user = $splitemail[0];
                                     $hideemail = substr($email_user, 0, 3);
                                     $hideemail .= "*****".$splitemail[1];
                                                        ?>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mt-0 mb-1"><?php echo $bank_details->bank_name;?></h5>
                                                            <p>Bank <?php echo $hideemail;?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="edit-option">
                                                        <?php if($bank_details->status == 'Pending' || $bank_details->status == 'Rejected'){?>
                                                        <a href="<?php echo base_url();?>view_bank_account"><i class="fa fa-eye"></i></a>
                                                    <?php }?>
                                                        <!-- <a href="<?php echo base_url();?>edit_bank_account"><i class="fa fa-pencil"></i></a> -->
                                                        <!-- <a href="<?php echo base_url();?>delete_bank_account/<?php echo $users->id;?>"><i class="fa fa-trash"></i></a> -->
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if($bank_details->status=='Verified'){  ?>
                                                    <div class="verify">
                                                        <div class="verified">
                                                            <span><i class="la la-check"></i></span>
                                                            <a>Verified</a>
                                                        </div>
                                                    </div>
                                                    <?php } elseif($bank_details->status=='Pending'){?>
                                                     <div class="verifys">
                                                        <div class="verified">
                                                            <span><i class="la la-times"></i></span>
                                                            <a class="pending">Pending</a>
                                                        </div>
                                                    </div>
                                                    <?php } elseif($bank_details->status=='Rejected'){?>   
                                                        <div class="verifys">
                                                        <div class="verified">
                                                            <span><i class="la la-times"></i></span>
                                                            <a class="pending">Rejected</a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                            <!-- <tr>
                                                <td>
                                                    <div class="d-flex">
                                                        <span class="me-3"><i class="fa fa-credit-card"></i></span>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mt-0 mb-1">Debit Card</h5>
                                                            <p>Prepaid Card *********5478</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="edit-option">
                                                        <a href="#"><i class="fa fa-eye"></i></a>
                                                        <a href="#"><i class="fa fa-pencil"></i></a>
                                                        <a href="#"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="verify">
                                                        <div class="not-verify">
                                                            <span><i class="la la-close"></i></span>
                                                            <a href="#">Not verified</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr> -->
                                        </tbody>
                                    </table>
<?php if($bank_details->status==''){ ?>
                                    <div class="mt-3">
                                        <a href="<?php echo base_url();?>add_bank_account" class="btn btn-primary px-4 py-2 me-3 my-2">Add
                                            Bank
                                            Account</a>
                                        <!-- <a href="verify-step-1.html" class="btn btn-success px-4 py-2 my-2">Add Debit
                                            Account</a> -->
                                    </div>

                              <?php  } elseif($bank_details->status=='Rejected'){?>
                                <div class="mt-3">
                                        <a href="<?php echo base_url();?>edit_bank_account" class="btn btn-primary px-4 py-2 me-3 my-2">Edit
                                            Bank
                                            Account</a>
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
        </div>

<?php 
    $this->load->view('front/common/footerlogin');
    ?>

    