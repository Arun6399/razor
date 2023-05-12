<!-- begin #content -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/css/lightbox.min.css">
<style>
.btn-default {
    color: #fff;
    background-color: #5f686d !important;
    border-color: #5f686d;
}    
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/js/lightbox-plus-jquery.min.js"></script>
        <div id="content" class="content">
            <?php $prefix = 'sharecoinexchange_';
        $error = $this->session->flashdata('error');
        if($error != '') {
            echo '<div class="alert alert-danger">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$error.'</div>';
        }
        $success = $this->session->flashdata('success');
        if($success != '') {
            echo '<div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$success.'</div>';
        } 
        ?>
        <!-- <div>KYC<?php echo $count_result; ?></div> -->
            <!-- begin breadcrumb -->
            <ol class="breadcrumb pull-right">
                <li><a href="<?php echo admin_url();?>">Home</a></li>
                <li class="active">Users</li>
            </ol>
            <!-- end breadcrumb -->
            <!-- begin page-header -->
            <h1 class="page-header"><?php if($this->uri->segment(3)=='') { echo "Activated Users"; } else if($this->uri->segment(3)=='unverified_users') { echo "Unverified Users"; } else if ($this->uri->segment(3)=='verification') { echo 'Users KYC Verification'; } else if($this->uri->segment(3)=='payment') { echo 'Users Payment'; } else if($this->uri->segment(3)=='company_verification') { echo 'Users Company Verification'; } ?> Management  <!--<small>header small text goes here...</small>--></h1>
            <!-- end page-header -->
            <!-- begin row -->
            <div class="row">
                <div class="col-md-12">
                    <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title"><?php if($this->uri->segment(3)=='') { echo "Activated Users"; } else if($this->uri->segment(3)=='unverified_users') { echo "Unverified Users"; } else if ($this->uri->segment(3)=='verification') { echo 'Users KYC Verification'; } else if($this->uri->segment(3)=='payment') { echo 'Users Payment'; } else if($this->uri->segment(3)=='company_verification') { echo 'Users Company Verification'; } ?> Management</h4>
                        </div>
                        <?php if($view=='view_all')
                        { ?>
                        <div class="panel-body">
                            <div class="clearfix">
                            </div>
                            <br/><br/>
                            <div class="table-responsive">
                                <table id="kyc-data" class="table table-striped table-bordered" id="view_all">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Username</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Date & Time</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <?php }
                        else if($view=='list'){ ?>
                        <div class="panel-body">
                          <div class="clearfix">
                            </div>
                            <br/><br/>
                            <div class="table-responsive">
                                <table id="test_data" class="table table-striped table-bordered" id="list">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Username</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Date & Time</th>
                                            <th class="text-center">TFA Status</th>
                                            <th class="text-center">TFA Action</th>
                                            <th class="text-center">KYC Status</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } else if($view=='unverified'){ ?>
                        <div class="panel-body">
                            <div class="clearfix">
                            </div>
                            <br/><br/>
                            <div class="table-responsive">
                                <table id="unverified_data" class="table table-striped table-bordered" id="unverified">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Username</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Date & Time</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <?php }elseif($view=='edit'){ ?>
                    <div class="panel-body">
                        <?php $attributes=array('class'=>'form-horizontal','id'=>'user_edit');
                echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                   
                                   <?php
                                   if(isset($users->country) && !empty($users->country) && $users->country!=0){
                                   ?>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Country</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            $country = get_countryname($users->country);
                                            echo $country->country_name; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                    ?>
                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Username</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            $username = $prefix.'username';
                                            echo $users->$username; ?>
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label class="col-md-2 control-label">Email</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            $email = getUserEmail($users->id);
                                            echo $email; ?>
                                        </div>
                                </div>
                                 <!-- <div class="form-group">
                                        <label class="col-md-2 control-label">Phone number</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            $phone_prefix = $users->phone_prefix;
                                            $phone = $prefix.'phone';
                                            $phonenumber = decryptIt($users->$phone);
                                            echo $phone_prefix.' '.$phonenumber; ?>
                                        </div>
                                </div> -->
                                <div class="form-group">
                                        <div class="col-md-7 col-md-offset-5">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                     <label class="col-md-2 control-label"></label>
                                    <div  class="col-md-8 control-label text-left">
                                    <?php if(!empty($bank_details)) { ?>
                                    <h4 >User Bank Details</h4>
                                    <?php foreach($bank_details as $bank){  ?>
                                    <h5><?php echo $bank->bank_name; ?></h5>
                                    Account Number : <?php echo $bank->bank_account_number; ?><br/>
                                    Account Name : <?php echo $bank->bank_account_name; ?><br/>
                                    Bank Swift code : <?php echo $bank->bank_swift; ?><br/>
                                    Bank City : <?php echo $bank->bank_city; ?><br/>
                                    Bank Postal Code : <?php echo $bank->bank_postalcode; ?><br/>
                                    Status : <?php if($bank->status=='0') { echo 'Deleted'; }else{ echo 'Active'; } ?><br/>
                                    <?php } } ?>
                                </div>
                                 </div>   
                                </fieldset>
                            </form>
                        </div>
                     <?php } else if($view=="payment") { ?>

                    <div class="panel-body">
                            <div class="clearfix">
                            </div>
                            <br/><br/>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="payment">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Username</th>
                                            <th class="text-center">3D Secure Status</th>                                 
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                if ($users->num_rows() > 0) {
                                    $i = 1;
                                    foreach($users->result() as $user) {
                                        if($user->usertype==1)
                                        {
                                            $usertype ="Personal";
                                            $username = $prefix.'username';
                                        }
                                        else 
                                        {
                                            $usertype ="Company";
                                            $username = 'company_name';
                                        }
                                        echo '<tr>';
                                        echo '<td class="text-center">' . $i . '</td>';
                                        echo '<td class="text-center" title="'.$user->$username.'">' . character_limiter($user->$username, 20) . '</td>';
                                        if ($user->shasta_secure == 1) {
                                            $status = '<label class="label label-info">Activated</label>';
                                            $extra = array('title' => 'De-activate 3D Secure');
                                            $changeStatus = anchor(admin_url().'users/payment_status/' . $user->id . '/0','Deactive',$extra);
                                        } else {
                                            $status = '<label class="label label-danger">De-Activated</label>';
                                            $extra = array('title' => 'Activate 3D Secure');
                                            $changeStatus = anchor(admin_url().'users/payment_status/' . $user->id . '/1','Activate',$extra);
                                        }
                                        echo '<td class="text-center">'.$status.'</td>';
                                        echo '<td class="text-center">';
                                        echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
                                        echo '</td>';

                                        echo '</tr>';
                                        $i++;
                                    }                   
                                }
                                else {
                                    echo '<tr>';
                                    echo '<td colspan="6">' . 'No Records Found!!' . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                    </tbody>
                                </table>
                                 
                            </div>
                        </div>
                    <?php } else if($view=="company_verification") { ?>

                    <div class="panel-body">
                            <div class="clearfix">
                            </div>
                            <br/><br/>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="company_verification">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">Company name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Company Verification Status</th>                                 
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                if ($users->num_rows() > 0) {
                                $i = 1; 
                                    foreach($users->result() as $user) {
                                        $usermail = getUserEmail($user->id);
                                        echo '<tr>';
                                        echo '<td class="text-center">' . $i . '</td>';
                                        echo '<td class="text-center" title="'.$user->company_name.'">' . character_limiter($user->company_name, 20) . '</td>';
                                        echo '<td class="text-center" title="'.$usermail.'">' . $usermail . '</td>';
                                        if ($user->company_status == 1) {
                                            $status = '<label class="label label-info">Verified</label>';
                                        } 
                                        else if ($user->company_status == 2) {
                                            $status = '<label class="label label-danger">Rejected</label>';
                                        }
                                        else {
                                            $status = '<label class="label label-danger">Pending</label>';
                                        }
                                        echo '<td class="text-center">'.$status.'</td>';
                                        echo '<td class="text-center">';
                                        echo '<a href="' . admin_url() . 'users/company_view/' . $user->id . '" title="View Company Details"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                                        echo '</td>';

                                        echo '</tr>';
                                        $i++;
                                    }                   
                                }
                                else {
                                    echo '<tr>';
                                    echo '<td colspan="5">' . 'No Records Found!!' . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                    </tbody>
                                </table>
                                 
                            </div>
                        </div>

                    <?php } else if($view=='company_view'){ ?>
                        <div class="panel-body">
                            <form>
                                <fieldset>

                                <div class="form-group">
                                        <label class="col-md-2 control-label">Shareholders directors</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $users->shareholders_directors; ?>
                                        </div>
                                </div>
                                <br>
                                <div class="form-group">
                                        <label class="col-md-2 control-label">Shareholding structure</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $users->shareholding_structure; ?>
                                        </div>
                                </div>
                                <br>
                                <div class="form-group">
                                        <label class="col-md-2 control-label">Company address</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $users->company_address; ?>
                                        </div>
                                </div>
                                <br>
                                <div class="form-group">
                                        <label class="col-md-2 control-label">Company phone</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php echo $users->company_phone; ?>
                                        </div>
                                </div>
                                <br>
                                <div class="form-group">
                                        <label class="col-md-2 control-label">Company registration document</label>

                                        <div class="col-md-8 control-label text-left">
                                        <?php 
                                        if($users->company_status==1)
                                        {
                                            $verify_status = "Verified";
                                            
                                        }
                                        else if($users->company_status==2)
                                        {
                                            $verify_status = "Rejected";
                                            
                                        } 
                                        else if($users->company_status==0)
                                        {
                                            if($users->company_reg_document !='')
                                            {
                                            $verify_status = "Pending";
                                            }
                                            else
                                            {
                                            $verify_status = "Not uploaded";    
                                            }
                                        }
                                        ?>
                                        <?php if($verify_status=='Pending' || $verify_status=='Verified') { ?>
                                        <span class="u_img">
                                        <img src="<?php echo $users->company_reg_document; ?>" > 
                                        </span>&nbsp;
                                        <?php } ?>
                                        <?php if($verify_status=='Pending' || $verify_status=='Rejected') { ?>
                                        <label class="label label-danger"> <?php } else { ?>
                                        <label class="label label-info"> <?php } ?>
                                        <?php echo $verify_status; ?></label>
                                        <br>
                                        <?php if($users->company_status==0 && $users->company_reg_document !=''){ ?>
                                        <a href="<?php echo $users->company_reg_document; ?>" class="btn btn-sm btn-primary" target="_blank" 
                                        style="margin-top:45px;">View</a>
                                        <?php if($users->company_status==0 && $users->company_reg_document !=''){
                                        $url1 = admin_url().'users/verify_company_status/'.$users->id.'/1';  ?>
                                        <a class="btn btn-sm btn-primary btn-success" href="<?php echo $url1; ?>" style="margin-top:45px;">Verify</a>
                                        <a class="btn btn-sm btn-primary btn-danger" href="#myModal3"  data-toggle="modal" style='margin-top:45px;'>Reject</a>
                                        <?php } } ?>
                                        </div>
                                </div>
                                </fieldset>
                            </form>
                        </div>
                    <?php } else if($view=='verification_view'){ ?>
                        <div class="panel-body">
                <?php $attributes=array('class'=>'form-horizontal','id'=>'users');
                echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <h4 style="text-align: center">User Details</h4>

                                <div class="form-group">
                                    <label class="col-md-2 control-label">Username</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php
                                        $username = $prefix.'username';
                                        echo $users->$username; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Surname</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php
                                        $lname = $prefix.'lname';
                                        echo $users->$lname; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Email</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php
                                        $email = getUserEmail($users->id);
                                        echo $email; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Date of birth</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php echo $users->dob; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Building number</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php echo $users->street_address; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Street</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php echo $users->street_address_2; ?>
                                    </div>
                                </div>
                               
                               
                                <div class="form-group">
                                    <label class="col-md-2 control-label">Phone Number</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php $phone = $prefix.'phone';
                                        echo $users->$phone; ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label">National TAX Number (TIN)</label>
                                    <div class="col-md-8 control-label text-left">
                                    <?php echo $users->national_tax_number; ?>
                                    </div>
                                </div>


                                <hr/><h4 style="text-align: center">Level -1  Verification</h4>
                               
                                 <!-- <div class="form-group">
                                        <label class="col-md-2 control-label">Profile picture</label>
                                        <div class="col-md-8 control-label text-left">
                                        <span class="u_img"><img src="<?php if($users->profile_picture==''){ echo base_url() . 'assets/front/images/dash-user.png'; } else { echo $users->profile_picture; }?>" > </span>&nbsp;&nbsp;&nbsp;
                                        <a data-lightbox="image-1" href="<?php if($users->profile_picture==''){ echo base_url() . 'assets/front/images/dash-user.png'; } else { echo $users->profile_picture; }?>" class="btn btn-sm btn-primary" target="_blank" style="margin-top:45px;">View Profile</a>
                                        </div>
                                </div> -->
                                <div class="form-group">
                                        <label class="col-md-2 control-label">Street Address 1</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            echo ($users->street_address!='')?$users->street_address:'-'; ?>
                                        </div>
                                </div>
                                 <div class="form-group">
                                        <label class="col-md-2 control-label">City</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            echo ($users->city!='')?$users->city:'-'; ?>
                                        </div>
                                </div>
                                 <div class="form-group">
                                        <label class="col-md-2 control-label">State</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            echo ($users->state!='')?$users->state:"-"; ?>
                                        </div>
                                </div>
                                 <div class="form-group">
                                        <label class="col-md-2 control-label">Country</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php $country = get_countryname($users->country);
                                        echo ($country!='')?$country:'-'; ?>
                                        </div>
                                </div>
                                 <div class="form-group">
                                        <label class="col-md-2 control-label">Postal Code</label>
                                        <div class="col-md-8 control-label text-left">
                                        <?php
                                            echo ($users->postal_code!="")?$users->postal_code:"-"; ?>
                                        </div>
                                </div>
                               
                                <hr/><h4 style="text-align: center">Level -2  Verification</h4>
                                
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Your id document front</label>

                                        <div class="col-lg-4">
                                        <?php if($users->photo_1_status==3)
                                        {
                                            $verify_status = "Verified";
                                            
                                        }
                                        else if($users->photo_1_status==2)
                                        {
                                            $verify_status = "Rejected";
                                            
                                        } 
                                        else if($users->photo_1_status==1)
                                        {
                                            if($users->photo_id_1 !='')
                                            {
                                            $verify_status = "Pending";
                                            }
                                            else
                                            {
                                            $verify_status = "Not uploaded";    
                                            }
                                        }
                                        else if($users->photo_1_status==0)
                                        {
                                            $verify_status = "Not uploaded";
                                        }?>
                                        <?php 
                                        if($verify_status=='Pending' || $verify_status=='Verified') 
                                        { ?>
                                            <span class="u_img">
                                            <img height="150" width="150" src="<?php echo $users->photo_id_1; ?>" > 
                                            </span>&nbsp;
                                        <?php } ?>
                                        <?php 
                                        if($verify_status=='Pending' || $verify_status=='Rejected') 
                                        { ?>
                                        <label class="label label-inline label-primary mr-2"><?php echo $verify_status; ?></label> 
                                     <?php } else { ?>
                                        <label class="label label-inline label-primary mr-2"> 

                                        <?php echo $verify_status; ?></label>
                                        <?php } ?>
                                         </div>

                                        <div class="col-lg-4">
                                        <br>
                                        <?php if($users->photo_1_status==1 && $users->photo_id_1 !='')
                                        { ?>
                                        <a data-lightbox="image-1" href="<?php echo $users->photo_id_1; ?>" class="btn btn-sm btn-primary" target="_blank" 
                                        style="margin-top:45px;">View</a>
                                        <?php 
                                        if($users->photo_1_status==1 && $users->photo_id_1 !='')
                                        {
                                        $url1 = admin_url().'users/verify_photo1_status/'.$users->id.'/3';  ?>
                                        <a class="btn btn-sm btn-primary btn-success" href="<?php echo $url1; ?>" style="margin-top:45px;">Verify</a>
                                        <a class="btn btn-sm btn-primary btn-danger" href="#myModal"  data-toggle="modal" style='margin-top:45px;'>Reject</a>
                                        <?php } } ?>
                                        </div>
                                </div>
                                
                                 <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Your id document back</label>
                                        
                                        <div class="col-lg-4">
                                        <?php if($users->photo_2_status==3)
                                        {
                                            $verify_status1 = "Verified";
                                            
                                        }
                                        else if($users->photo_2_status==2)
                                        {
                                            $verify_status1 = "Rejected"; 
                                            
                                        } 
                                        else if($users->photo_2_status==1)
                                        {
                                            if($users->photo_id_2 !='')
                                            {
                                            $verify_status1 = "Pending";
                                            }
                                            else
                                            {
                                             $verify_status1 = "Not uploaded";   
                                            }
                                        }
                                        else if($users->photo_2_status==0)
                                        {
                                            $verify_status1 = "Not uploaded";
                                        }?>
                                        <?php if($verify_status1=='Pending' || $verify_status1=='Verified') { ?>
                                        <span class="u_img">
                                        <img height="150" width="150" src="<?php echo $users->photo_id_2; ?>" > 
                                        </span>&nbsp;
                                        <?php } ?>
                                        <?php if($verify_status1=='Pending' || $verify_status1=='Rejected') { ?>
                                        <label class="label label-danger"> <?php echo $verify_status1; ?></label><?php } else { ?>
                                        <label class="label label-info"> 
                                        <?php echo $verify_status1; ?></label>
                                        <?php } ?></div>
                                        <div class="col-lg-4">
                                        <?php if($users->photo_2_status==1 && $users->photo_id_2 !=''){ ?>
                                        <a data-lightbox="image-1" href="<?php echo $users->photo_id_2; ?>" class="btn btn-sm btn-primary" target="_blank"
                                        style="margin-top:45px;">View</a>
                                        <?php if($users->photo_2_status==1 && $users->photo_id_2 !=''){
                                        $url2 = admin_url().'users/verify_photo2_status/'.$users->id.'/3';  ?>
                                        <a class="btn btn-sm btn-primary btn-success" href="<?php echo $url2; ?>" style="margin-top:45px;">Verify</a>
                                        <a class="btn btn-sm btn-primary btn-danger" href="#myModal1"  data-toggle="modal" style='margin-top:45px;'>Reject</a>
                                        <?php } } ?>
                                        </div>
                                </div>

                                <div class="form-group row">
                    <?php $deposit_history = $this->common_model->getTableData('transactions',array('user_id'=>$users->id,'type'=>'Deposit','verify_status'=>'kyc_verify'),'','','','','','',array('trans_id','DESC'))->result();
                    
                    $deposit_link = $deposit_history[0]->trans_id;
                    $trans = $deposit_history[0]->status;
                    ?>                
                                    
                                    <label class="col-lg-2 col-form-label">Bank Wire Transfer</label>
                                        
                                        <div class="col-lg-4">
                                        <?php if($users->photo_3_status==3)
                                        {
                                            $verify_status2 = "Verified";
                                            
                                        }
                                        else if($users->photo_3_status==2)
                                        {

                                            $verify_status2 = "Rejected";
                                            
                                        } 
                                        else if($users->photo_3_status==1)
                                        {
                                            if($users->photo_id_3 !='')
                                            {
                                            $verify_status2 = "Pending";
                                            }
                                            else
                                            {
                                             if($trans=='Pending') {
                                                $verify_status2 = 'Pending';
                                             } else if($trans=='Completed') {
                                                $verify_status2 = 'Verified';
                                             } else {
                                                $verify_status2 = 'Not Transfer';
                                             }  
                                            }
                                        }
                                        else if($users->photo_3_status==0)
                                        {
                                            $verify_status2 = "Not uploaded";
                                        }?>
                                       
                                        <?php if($verify_status2=='Pending' || $verify_status2=='Rejected') { ?>
                                        <label class="label label-danger"> <?php echo $verify_status2; ?></label><?php } else { ?>
                                        <label class="label label-info">
                                        <?php echo $verify_status2; ?> </label>
                                         <?php } ?></div>
                                        <div class="col-lg-4">
                                        <?php if($users->photo_3_status==1){ ?>
                                        <a class="btn btn-sm btn-primary" href="#myModal4" data-toggle="modal" style="margin-top:45px;">View</a>
                                        <?php if($users->photo_3_status==1){
                                        $url3 = admin_url().'users/verify_bankwire_status/'.$users->id.'/3/'.$deposit_link.'/Completed';  ?>
                                        <a class="btn btn-sm btn-primary btn-success" href="<?php echo $url3; ?>" style="margin-top:45px;">Verify</a>
                                        <a class="btn btn-sm btn-primary btn-danger" href="#myModal2"  data-toggle="modal" style='margin-top:45px;'>Reject</a>
                                        <?php } } ?>
                                        </div>
                                </div>
                                </fieldset>
                            </form>
                        </div>
                    <?php } ?> 
                    </div>
                    <!-- end panel -->
                </div>
            </div>
            <!-- end row -->
        </div>

<div id="myModal" class="modal fade">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="icon12 minia-icon-close"></span></button>
          <h3>Reason to reject</h3>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'users/verify_photo1_reject/'.$users->id.'/2'); ?>
            <textarea name="reject_mail_content" class="form-control" required></textarea>
            <br/>
            <button class="btn btn-small btn-danger" style='margin-left:20px;'>REJECT</button>
            <?php echo form_close(); ?></center>
            </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
    </div>
    </div>
    </div>

    <div id="myModal1" class="modal fade">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="icon12 minia-icon-close"></span></button>
          <h3>Reason to reject</h3>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'users/verify_photo2_reject/'.$users->id.'/2'); ?>
            <textarea name="reject_mail_content" class="form-control" required></textarea>
            <br/>
            <button class="btn btn-small btn-danger" style='margin-left:20px;'>REJECT</button>
            <?php echo form_close(); ?></center>
            </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
    </div>
    </div>
    </div>

    <div id="myModal2" class="modal fade">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="icon12 minia-icon-close"></span></button>
          <h3>Reason to reject</h3>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'users/verify_bankwire_reject/'.$users->id.'/2/'.$deposit_link.'/Cancelled'); ?>
            <textarea name="reject_mail_content" class="form-control" required></textarea>
            <br/>
            <button class="btn btn-small btn-danger" style='margin-left:20px;'>REJECT</button>
            <?php echo form_close(); ?></center>
            </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
    </div>
    </div>
    </div>

    <div id="myModal3" class="modal fade">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="icon12 minia-icon-close"></span></button>
          <h3>Reason to reject</h3>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'users/verify_company_reject/'.$users->id.'/2'); ?>
            <textarea name="reject_mail_content" class="form-control" required></textarea>
            <br/>
            <button class="btn btn-small btn-danger" style='margin-left:20px;'>REJECT</button>
            <?php echo form_close(); ?></center>
            </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
    </div>
    </div>
    </div>

    <div id="myModal4" class="modal fade">
    <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="icon12 minia-icon-close"></span></button>
          <h3>Fiat Deposit Management</h3>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
            <div class="form-horizontal">
                <div class="form-group">
                <label class="col-md-2 control-label">Username</label>
                <div class="col-md-8 control-label text-left">
                <?php echo getUserDetails($deposit_history[0]->user_id,'sharecoinexchange_username'); ?>
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Currency</label>
                <div class="col-md-8 control-label text-left">
                <?php echo $deposit_history[0]->currency_id; ?>
                </div>
                </div>
            
                <div class="form-group">
                <label class="col-md-2 control-label">Reference number</label>
                <div class="col-md-8 control-label text-left">
                <?php echo $deposit_history[0]->transaction_id; ?>
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Requested Amount</label>
                <div class="col-md-8 control-label text-left">
                <?php echo number_format($deposit_history[0]->amount,2); ?>
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Fees</label>
                <div class="col-md-8 control-label text-left">
                <?php echo number_format($deposit_history[0]->fee,2); ?>
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Transfer Amount</label>
                <div class="col-md-8 control-label text-left">
                <?php echo number_format($deposit_history[0]->transfer_amount,2); ?>
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Description</label>
                <div class="col-md-8 control-label text-left">
                <?php echo ($deposit_history[0]->description!='')?$deposit_history[0]->description:'-'; ?>
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Deposited On</label>
                <div class="col-md-8 control-label text-left">
                <?php echo gmdate("d-m-Y h:i a", $deposit_history[0]->datetime); ?>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-2 control-label">Pay Via</label>
                <div class="col-md-8 control-label text-left">
                <?php echo ucfirst($deposit_history[0]->payment_method); ?>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-2 control-label">Status</label>
                <div class="col-md-8 control-label text-left">
                <?php echo $deposit_history[0]->status; ?>
                </div>
                </div> 

                <div class="form-group">
                <label class="col-md-2 control-label">Status</label>
                <div class="col-md-8 control-label text-left">
                    <a target="_blank" href="<?=base_url('uploads/'.$users->photo_id_3)?>">Download PDF</a>
                </div>
                </div> 

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
    </div>
    </div>
    </div>


        <!-- end #content -->
<!-- ================== BEGIN BASE JS ================== -->
    <script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="<?php echo admin_source();?>/crossbrowserjs/html5shiv.js"></script>
        <script src="<?php echo admin_source();?>/crossbrowserjs/respond.min.js"></script>
        <script src="<?php echo admin_source();?>/crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <script src="<?php echo admin_source();?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/jquery-cookie/jquery.cookie.js"></script>
    <!-- ================== END BASE JS ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="<?php echo admin_source();?>/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.time.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.resize.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
    <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
    
    <script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
    <script src="<?php echo admin_source();?>/js/apps.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
        <script>
        $(document).ready(function() {
            App.init();
        });
        
        
    </script>
     <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>
<script>     
var admin_url='<?php echo admin_url(); ?>';
$(document).ready(function() {
    $('#test_data').DataTable( {
        "responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"users/users_ajax"
    });
    $('#unverified_data').DataTable( {
        "responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"users/unusers_ajax"

    });
    $('#kyc-data').DataTable( {
        "responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"users/kyc_ajax"

    });
});
    </script>
    
    <style>
    .u_img{width:125px; height:125px; overflow:hidden; display:inline-block; float:left;}

    .u_img img{   height: auto;
    max-height: 100%;
    width: 100%;}


    </style>