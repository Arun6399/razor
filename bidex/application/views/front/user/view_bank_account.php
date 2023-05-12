 <?php $this->load->view('front/common/headerlogin');?>
<div class="verification mb-5">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center  my-5">
                    <div class="col-xl-5 col-md-6">
                        <div class="auth-form card">
                            <div class="card-header">
                                <h4 class="card-title">Link a bank account</h4>
                            </div>
                            <div class="card-body">
                                
                                    <?php $action = front_url() . 'update_bank_details';
                    $attributes=array('id'=>'update_bank_details','class'=>'identity-upload','autocomplete'=>"off");
                      echo form_open_multipart($action,$attributes); 
                      ?>
                                    <div class="row">
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Account Number </label>
                                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" placeholder="**********" value="<?=(($bank_details->bank_account_number)?$bank_details->bank_account_number:'')?>" disabled>
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">IFSC Code </label>
                                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="**********" value="<?=(($bank_details->ifsc_code)?$bank_details->ifsc_code:'')?>" disabled>
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Bank Account name </label>
                                            <input type="text" name="bank_account_name" id="bank_account_name" class="form-control" placeholder="user" value="<?=(($bank_details->bank_account_name)?$bank_details->bank_account_name:'')?>" disabled>
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Bank Name </label>
                                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="*** bank" value="<?=(($bank_details->bank_name)?$bank_details->bank_name:'')?>" disabled> 
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Screenshot </label>
                                            
                                                <img src="<?php echo $bank_details->bank_statement;?>" style="width: 200px; height: 90px;">
                                            
                                        </div>

                                        <div class="text-center col-12">
                                            <a href="<?php echo base_url();?>settings_account" class="btn btn-primary mx-2">Back</a>
                                            <!-- <button type="submit" class="btn btn-success mx-2">Save</button> -->
                                        </div>
                                    </div>
                                <?php echo form_close();
                    ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php 
    $this->load->view('front/common/footerlogin');
    ?>

