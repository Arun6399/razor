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
                                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" placeholder="**********" value="<?=(($bankwire->bank_account_number)?$bankwire->bank_account_number:'')?>">
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">IFSC Code </label>
                                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="**********" value="<?=(($bankwire->ifsc_code)?$bankwire->ifsc_code:'')?>">
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Bank Account name </label>
                                            <input type="text" name="bank_account_name" id="bank_account_name" class="form-control" value="<?=(($bankwire->bank_account_name)?$bankwire->bank_account_name:'')?>">
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Bank Name </label>
                                            <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="*** bank" value="<?=(($bankwire->bank_name)?$bankwire->bank_name:'')?>"> 
                                        </div>
                                        <div class="mb-3 col-xl-12">
                                            <label class="form-label">Image </label>
                                            <div class="file-upload-wrapper" data-text="Bank statement.pdf/jpg">
                                                <input name="editscreenshot" type="file" class="file-upload-field">
                                            </div>
                                        </div>

                                        <div class="text-center col-12">
                                            <a href="<?php echo base_url();?>settings_account" class="btn btn-primary mx-2">Back</a>
                                            <button type="submit" class="btn btn-success mx-2">Save</button>
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

    

<script>
 $.validator.addMethod('ZipChecker', function() {
    }, 'Invalid zip code');

    $.validator.addMethod("lettersonly", function(value) {
        return (/^[a-zA-Z\s]*$/.test(value));
    });
             $.validator.addMethod("alphanumeric", function(value) {
    return (/^[A-Za-z0-9 _.-]+$/.test(value));
});
    $('#update_bank_details').validate({
        rules: {
            // currency: {
            //     required: true
            // },
            bank_name: {
                required: true,
                lettersonly: true
            },
            bank_account_number: {
                required: true,
                number: true
            },
            ifsc_code: {
                required: true,
                alphanumeric: true
            },
            bank_account_name: {
                required: true,
                lettersonly: true
            },
            editscreenshot: {
                required: true
            },
            // bank_address: {
            //      required: true
            // },
            // bank_city: {
            //     required: true,
            //     lettersonly: true
            // },
            // bank_country: {
            //     required: true,
            //    // lettersonly: true
            // },
            // bank_postalcode: {
            //     required: true,
            //     number: true,
            //     maxlength: 7,
            //     ZipChecker: function(element) {
            //         values=$("#postal_code").val();
            //         if( values =="0" || values =="00" || values =="000" || values =="0000" || values =="00000"  || values =="000000"   || values =="0000000" )
            //         {
            //             return true;
            //         }
            //     }
            // }
        },
        messages: {
            bank_name: {
                required: "Please enter Bank name",
                lettersonly: "please enter letters only"
            },
            bank_account_number: {
                required: "Please enter Account number",
                number: "Please enter numbers only"
            },
            // bank_account_name: {
            //     required: "<?php echo $this->lang->line('Please enter Account name');?>",
            //     lettersonly: "<?php echo $this->lang->line('Please enter letters only');?>"
            // },
            ifsc_code: {
                required: "Please enter IFSC Code",
                alphanumeric: "please enter numbers and letters only"
            },
            bank_account_name: {
                required: "Please enter Account Name",
                lettersonly: "Please enter letters only"
            },
            editscreenshot: {
                required: "Please Upload Screenshot"
                
            },
            // bank_address: {
            //     required: "<?php echo $this->lang->line('Please enter Bank Address');?>"
            // },
            // bank_city: {
            //     required: "<?php echo $this->lang->line('Please enter Bank City');?>",
            //     lettersonly: "<?php echo $this->lang->line('Please enter letters only');?>"
            // },
            // bank_country: {
            //     required: "<?php echo $this->lang->line('Please select Bank Country');?>"
            // },
            // bank_postalcode: {
            //     required: "<?php echo $this->lang->line('Please enter Postal code');?>"
            // }
        }
    });    
 </script>   