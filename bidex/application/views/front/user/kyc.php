<?php $this->load->view('front/common/headerlogin')?>
<style>
.kyc-status-Rejected {
  /*margin-bottom: 25px !important;*/
  color: red !important;
  font-weight: bold;
} 


.kyc-status-Completed {
 /* margin-bottom: 25px !important;*/
  color: #28a745 !important;
  font-weight: bold;
} 


.kyc-status-Pending {
  /*margin-bottom: 25px !important;*/
  color: red !important;
  font-weight: bold;
}  



</style>
<div class="verification section-padding">
            <div class="container h-100">
                
                  
                <div class="row h-100 align-items-center">
                
<?php  


                 if($users->photo_1_status==1) { $front_id_status = 'Pending'; } 
                 else if($users->photo_1_status==2) { $front_id_status = 'Rejected'; } 
                 else if($users->photo_1_status==3) { $front_id_status = 'Completed'; }

                 if($users->photo_2_status==1) { $back_id_status = 'Pending'; } 
                 else if($users->photo_2_status==2) { $back_id_status = 'Rejected'; } 
                 else if($users->photo_2_status==3) { $back_id_status = 'Completed'; }

                 if($users->photo_3_status==1) { $front_add_status = 'Pending'; } 
                 else if($users->photo_3_status==2) { $front_add_status = 'Rejected'; } 
                 else if($users->photo_3_status==3) { $front_add_status = 'Completed'; }

                 if($users->photo_4_status==1) { $back_add_status = 'Pending'; } 
                 else if($users->photo_4_status==2) { $back_add_status = 'Rejected'; } 
                 else if($users->photo_4_status==3) { $back_add_status = 'Completed'; }

                 if($users->photo_5_status==1) { $selfie_status = 'Pending'; } 
                 else if($users->photo_5_status==2) { $selfie_status = 'Rejected'; } 
                 else if($users->photo_5_status==3) { $selfie_status = 'Completed'; }
              
            ?>
                        <div class="col-xl-4 col-md-4">
                            <div class="auth-form card">
                                <div class="card-body" style="height: 600px;">

                                        <?php $attributes=array('id'=>'verification_forms2','class'=>'identity-upload'); 
                        $action = front_url() . 'id_verification';
                        echo form_open_multipart($action,$attributes); ?> 
                                        <div class="identity-content">
                                            <h4>Upload your ID Proof</h4>
                                            <span>(Driving License or Government ID card)</span>

                                            <p>Uploading your ID helps as ensure the safety and security of your funds</p>
                                        </div>
                                        <p class="kyc-status-<?=(($front_id_status=='Pending')?str_replace(' ','-',$front_id_status):$front_id_status)?>"><?php echo $front_id_status;?></p>
                                        <div class="mb-3">
                                            <label class="form-label">Upload Front ID </label>
                                            <span class="float-right">Maximum file size is 2mb</span>
                                              <?php 
                        $img = front_img().'user.png';
                        
 
                        if(!empty(trim($users->photo_id_1)) && ($users->photo_1_status==3 || $users->photo_1_status==1)){
                            $img = $users->photo_id_1;
                        }
                        ?>
                        <?php 
                       $img = $users->photo_id_1;
                       $extension = pathinfo($img, PATHINFO_EXTENSION);
                       if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
                    ?>
                      <img id="front_id_proof" src="<?=$img?>" alt="Address Proof" class="img-fluid mb-3 proof_img" style="width: 125px; height: 65px;">
                     <input type="hidden" id="photo_ids_1" name="photo_ids_1" value="<?php echo $users->photo_id_1;?>">
                     <?php 
                         $img = $users->photo_id_1;
                         $extension = pathinfo($img, PATHINFO_EXTENSION);
                         }else if($extension == 'pdf'){
                    ?>
                    <iframe src="<?php echo $img;?>" width="100px" height="60px"></iframe>
                  <?php }?>
                      <?php if(($users->photo_1_status==0 ||  $users->photo_1_status==2)) {
                        // if($users->photo_1_status==0) $fileCls = 'imageInput';
                        // else if($users->photo_1_status==2) $fileCls = 'imageInput1';
                       ?>
                                            <div class="file-upload-wrapper" data-text="front.pdf/jpg">
                                                <input name="photo_id_1" id="photo_id_1" type="file" class="file-upload-field">
                                                
                                            </div>
                                          <?php } ?>
                                        </div>
                                        <p class="kyc-status-<?=(($back_id_status=='Pending')?str_replace(' ','-',$back_id_status):$back_id_status)?>"><?php echo $back_id_status;?></p>
                                        <div class="mb-3">
                                            <label class="form-label">Upload Back ID </label>
                                            <span class="float-right">Maximum file size is 2mb</span>
                                                                       <?php 
                        $img1 = front_img().'user.png';
                        
 
                        if(!empty(trim($users->photo_id_2)) && ($users->photo_2_status==3 || $users->photo_2_status==1)){
                            $img1 = $users->photo_id_2;
                        }
                        ?>
                            <?php 
                       $img1 = $users->photo_id_2;
                       $extension = pathinfo($img1, PATHINFO_EXTENSION);
                       if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
                    ?>
                      <img id="back_id_proof" src="<?=$img1?>" alt="Address Proof" class="img-fluid mb-3 proof_img" style="width: 125px; height: 65px;">
                       <input type="hidden" id="photo_ids_2" name="photo_ids_2" value="<?php echo $users->photo_id_2;?>">
                        <?php 
                         $img1 = $users->photo_id_2;
                         $extension = pathinfo($img1, PATHINFO_EXTENSION);
                         } else if($extension == 'pdf'){
                    ?>
                    <iframe src="<?php echo $img1;?>" width="100px" height="60px"></iframe>
                  <?php }?>
                      <?php if(($users->photo_2_status==0 || $users->photo_2_status==2)) {
                        // if($users->photo_1_status==0) $fileCls = 'imageInput';
                        // else if($users->photo_1_status==2) $fileCls = 'imageInput1';
                       ?>
                                            <div class="file-upload-wrapper" data-text="back.pdf/jpg">
                                                <input name="photo_id_2" id="photo_id_2" type="file" class="file-upload-field">
                                               
                                            </div>
                                          <?php } ?>
                                        </div>
                                        <?php  if(($users->photo_1_status==0 || $users->photo_1_status==2 || $users->photo_2_status==0 || $users->photo_2_status==2)){ ?>
                                          <div class="text-center">
                                                <button type="submit" class="btn btn-success ps-5 pe-5">Submit</button>
                                            </div>
                                          <?php }?>
                                      <?php echo form_close(); ?>   


                                </div>
                            </div>
                            </div>
                           

                            <div class="col-xl-4 col-md-4">
                                <div class="auth-form card">
                                    <div class="card-body" style="height: 600px;">
                                        <!-- <form action="kyc-3.html" class="identity-upload"> -->
                        <?php $attributes=array('id'=>'verification_forms3','class'=>'identity-upload'); 
                        $action = front_url() . 'address_verification';
                        echo form_open_multipart($action,$attributes); ?> 
                                            <div class="identity-content">
                                                <h4>Upload your Address Proof</h4>
                                                <span>(Driving License or Government ID card)</span>

                                                <p>Uploading your ID helps as ensure the safety and security of your funds</p>
                                            </div>
                                              <p class="kyc-status-<?=(($front_add_status=='Pending')?str_replace(' ','-',$front_add_status):$front_add_status)?>"><?php echo $front_add_status;?></p>
                                            <div class="mb-3">
                                                <label class="form-label">Upload Front ID </label>
                                                <span class="float-right">Maximum file size is 2mb</span>
                                                <?php 
                        $img2 = front_img().'user.png';
                        
 
                        if(!empty(trim($users->photo_id_3)) && ($users->photo_3_status==3 || $users->photo_3_status==1)){
                            $img2 = $users->photo_id_3;
                        }
                        ?>
                                <?php 
                       $img2 = $users->photo_id_3;
                       $extension = pathinfo($img2, PATHINFO_EXTENSION);
                       if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
                    ?>
                      <img id="front_address_proof" src="<?=$img2?>" alt="Address Proof" class="img-fluid mb-3 proof_img" style="width: 125px; height: 65px;">
                      <input type="hidden" id="photo_ids_3" name="photo_ids_3" value="<?php echo $users->photo_id_3;?>">
                       <?php 
                         $img2 = $users->photo_id_3;
                         $extension = pathinfo($img2, PATHINFO_EXTENSION);
                         } else if($extension == 'pdf'){
                    ?>
                    <iframe src="<?php echo $img2;?>" width="100px" height="60px"></iframe>
                  <?php }?>
                      <?php if(($users->photo_3_status==0 || $users->photo_3_status==2)) {
                        if($users->photo_3_status==0) $fileCls = 'imageInput';
                        else if($users->photo_3_status==2) $fileCls = 'imageInput1';
                       ?>
                                                <div class="file-upload-wrapper" data-text="front.pdf/jpg">
                                                    <input name="photo_id_3" id="photo_id_3" type="file" class="file-upload-field <?=$fileCls?>">
                                                    
                                                </div>
                                              <?php } ?>
                                            </div>
                                            <p class="kyc-status-<?=(($back_add_status=='Pending')?str_replace(' ','-',$back_add_status):$back_add_status)?>"><?php echo $back_add_status;?></p>
                                            <div class="mb-3">
                                                <label class="form-label">Upload Back ID </label>
                                                <span class="float-right">Maximum file size is 2mb</span>
                                                 <?php 
                        $img3 = front_img().'user.png';
                        
 
                        if(!empty(trim($users->photo_id_4)) && ($users->photo_4_status==3 || $users->photo_4_status==1)){
                            $img3 = $users->photo_id_4;
                        }
                        ?>
                               <?php 
                       $img3 = $users->photo_id_4;
                       $extension = pathinfo($img3, PATHINFO_EXTENSION);
                       if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
                    ?>
                      <img id="back_address_proof" src="<?=$img3?>" alt="Address Proof" class="img-fluid mb-3 proof_img" style="width: 125px; height: 65px;">
                      <input type="hidden" id="photo_ids_4" name="photo_ids_4" value="<?php echo $users->photo_id_4;?>">
                       <?php 
                         $img3 = $users->photo_id_4;
                         $extension = pathinfo($img3, PATHINFO_EXTENSION);
                        }else if($extension == 'pdf'){
                    ?>
                    <iframe src="<?php echo $img3;?>" width="100px" height="60px"></iframe>
                  <?php }?>
                      <?php if(($users->photo_4_status==0 || $users->photo_4_status==2)) {
                        if($users->photo_4_status==0) $fileCls = 'imageInput';
                        else if($users->photo_4_status==2) $fileCls = 'imageInput1';
                       ?>
                                                <div class="file-upload-wrapper" data-text="back.pdf/jpg">
                                                    <input name="photo_id_4" id="photo_id_4" type="file" class="file-upload-field <?=$fileCls?>">
                                                    
                                                </div>
                                              <?php } ?>
                                            </div>
                                            <?php  if(($users->photo_3_status==0 || $users->photo_3_status==2 || $users->photo_4_status==0 || $users->photo_4_status==2)){ ?>
                                            <div class="text-center address_submit_btn">
                                                <button type="submit" class="btn btn-success ps-5 pe-5">Submit</button>
                                            </div>
                                          <?php } ?>
                                        <?php echo form_close(); ?> 
                                    </div>
                                </div>
                    </div>



                    <div class="col-xl-4 col-md-4">
                        <div class="auth-form card">
                            <div class="card-body" style="height: 600px;">
                                <!-- <form action="kyc-3.html" class="identity-upload"> -->
 <?php $attributes=array('id'=>'verification_forms4','class'=>'identity-upload'); 
                        $action = front_url() . 'photo_verification';
                        echo form_open_multipart($action,$attributes); ?> 
                                    <div class="identity-content">
                                        <h4>Upload your Selfie Photo</h4>
                                        <span>Photo of you</span>

                                        <p>Uploading your Photo helps as ensure the safety and security of your funds</p>
                                    </div>
                                    <p class="kyc-status-<?=(($selfie_status=='Pending')?str_replace(' ','-',$selfie_status):$selfie_status)?>"><?php echo $selfie_status;?></p>
                                    <div class="mb-3">
                                        <label class="form-label">Upload Your Selfie </label>
                                        <span class="float-right">Maximum file size is 2mb</span>
                                         <?php 
                        $img4 = front_img().'user.png';
                        
 
                        if(!empty(trim($users->photo_id_5)) && ($users->photo_5_status==3 || $users->photo_5_status==1)){
                            $img4 = $users->photo_id_5;
                        }
                        ?>
                               <?php 
                       $img4 = $users->photo_id_5;
                       $extension = pathinfo($img4, PATHINFO_EXTENSION);
                       if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
                    ?>
                      <img id="photo_proof" src="<?=$img4?>" alt="Address Proof" class="img-fluid mb-3 proof_img" style="width: 125px; height: 65px;">
                      <input type="hidden" id="photo_ids_5" name="photo_ids_5" value="<?php echo $users->photo_id_5;?>">
                       <?php 
                         $img4 = $users->photo_id_5;
                         $extension = pathinfo($img4, PATHINFO_EXTENSION);
                         }else if($extension == 'pdf'){
                    ?>
                    <iframe src="<?php echo $img4;?>" width="100px" height="60px"></iframe>
                  <?php }?>
                      <?php if(($users->photo_5_status==0 || $users->photo_5_status==2)) {
                        // if($users->photo_1_status==0) $fileCls = 'imageInput';
                        // else if($users->photo_1_status==2) $fileCls = 'imageInput1';
                       ?>
                                        <div class="file-upload-wrapper" data-text="photo.jpg/png">
                                            <input name="photo_id_5" id="photo_id_5" type="file" class="file-upload-field">
                                            
                                        </div>
                                      <?php }?>
                                    </div>

                                    <?php  if(($users->photo_5_status==0 || $users->photo_5_status==2 )){ ?>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success ps-5 pe-5">Submit</button>
                                    </div>
                                  <?php }?>
                                <?php echo form_close(); ?>  
                            </div>
                        </div>
            </div>
        
          
        </div>
           

            </div>
        </div>

<?php $this->load->view('front/common/footerlogin');?>        


<script type="text/javascript">
var base_url='<?php echo base_url();?>';
  $('#verification_forms2').validate({
    rules: {
        photo_id_1: {
            required: true
           
        },

           photo_id_2: {
            required: true
           
        }
    },
    messages: {
        photo_id_1: {
            required: "Please Choose Front Id Proof"
          
        },

          photo_id_2: {
            required: "Please Choose Back Id Proof"
          
        },

        
    }
});

  $('#verification_forms3').validate({
    rules: {

           photo_id_3: {
            required: true
           
        },
           photo_id_4: {
            required: true
           
        }

    },
    messages: {


            photo_id_3: {
            required: "Please Choose Front Address Proof"
          
        },
           photo_id_4: {
            required: "Please Choose Back Address Proof"
          
        },

        
    }
});

  $('#verification_forms4').validate({
    rules: {
       
           photo_id_5: {
            required: true
           
        }
    },
    messages: {
    
           photo_id_5: {
            required: "Please Choose Selfie Proof"
          
        },
        
    }
});

  function readURL1(input) {
      if (input.files && input.files[0]) {
        fileName1 = input.files[0].name;
        $("#filename1").text(fileName1);
        fileSize = input.files[0].size;
        if(fileSize > 2000000) { 
            $.growl.error({title: "Bidex", message: 'Maximum file size should be below 2mb' });
           //$('input[type="submit"]').attr('disabled','disabled');
           
        } else {
          
          $('#upload_pdf_error').html("");
          //$('input[type="submit"]').removeAttr('disabled');
          var reader = new FileReader();
            reader.onload = function(e) {
              var url = input.value;
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpeg' || ext == 'jpg' || ext == 'png'){
                  
                  $('#front_id_proof').attr('src', e.target.result);
                }
                if(ext == 'pdf'){

                  $('#front_id_proof').attr('src', base_url+'assets/images/pdf-icon.png');
                }
            }
            reader.readAsDataURL(input.files[0]);
            //callValidProof1();
        }
        //callValidProof();
      }
    }
    $(document).on('change','#photo_id_1',function() {
      
        readURL1(this);
    });

    function readURL2(input) {
      if (input.files && input.files[0]) {

        fileName2 = input.files[0].name;
        $("#filename2").text(fileName2);

        fileSize = input.files[0].size;
        if(fileSize > 2000000) { 
          $.growl.error({title: "Bidex", message: 'Maximum file size should be below 2mb' });
          //$('input[type="submit"]').attr('disabled','disabled');
        } else {
          $('#upload_pdf_error1').html("");
              //$('input[type="submit"]').removeAttr('disabled');
          var reader = new FileReader();
            reader.onload = function(e) {
                var url = input.value;
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpeg' || ext == 'jpg' || ext == 'png'){
                  
                  $('#back_id_proof').attr('src', e.target.result);
                }
                if(ext == 'pdf'){

                  $('#back_id_proof').attr('src', base_url+'assets/images/pdf-icon.png');
                }
            }
            reader.readAsDataURL(input.files[0]);
            //callValidProof1();
        }
        //callValidProof();
      }
    }
    $(document).on('change','#photo_id_2',function() {
    
        readURL2(this);
    });

    function readURL3(input) {
      if (input.files && input.files[0]) {

        fileName3 = input.files[0].name;
        $("#filename3").text(fileName3);

        fileSize = input.files[0].size;
        if(fileSize > 2000000) { 
         $.growl.error({title: "Bidex", message: 'Maximum file size should be below 2mb' });
          //$('input[type="submit"]').attr('disabled','disabled');
        } else {
          $('#upload_pdf_error2').html("");
              //$('input[type="submit"]').removeAttr('disabled');
          var reader = new FileReader();
            reader.onload = function(e) {
                var url = input.value;
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpeg' || ext == 'jpg' || ext == 'png'){
                  
                  $('#front_address_proof').attr('src', e.target.result);
                }
                if(ext == 'pdf'){

                  $('#front_address_proof').attr('src', base_url+'assets/images/pdf-icon.png');
                }
            }
            reader.readAsDataURL(input.files[0]);
            //callValidProof1();
        }
        //callValidProof();
      }
    }
 $(document).on('change','#photo_id_3',function() {
    
        readURL3(this);
    });


    function readURL4(input) {
      if (input.files && input.files[0]) {

        fileName4 = input.files[0].name;
        $("#filename4").text(fileName4);

        fileSize = input.files[0].size;
        if(fileSize > 2000000) { 
          $.growl.error({title: "Bidex", message: 'Maximum file size should be below 2mb' });
          //$('input[type="submit"]').attr('disabled','disabled');
        } else {
          $('#upload_pdf_error3').html("");
              //$('input[type="submit"]').removeAttr('disabled');
          var reader = new FileReader();
            reader.onload = function(e) {
                var url = input.value;
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpeg' || ext == 'jpg' || ext == 'png'){
                  
                  $('#back_address_proof').attr('src', e.target.result);
                }
                if(ext == 'pdf'){

                  $('#back_address_proof').attr('src', base_url+'assets/images/pdf-icon.png');
                }
            }
            reader.readAsDataURL(input.files[0]);
            //callValidProof1();
        }
        //callValidProof();
      }
    }
$(document).on('change','#photo_id_4',function() {
    
        readURL4(this);
    });


    function readURL5(input) {
      if (input.files && input.files[0]) {

        fileName5 = input.files[0].name;
        $("#filename5").text(fileName5);

        fileSize = input.files[0].size;
        if(fileSize > 2000000) { 
          $.growl.error({title: "Bidex", message: 'Maximum file size should be below 2mb' });
          //$('input[type="submit"]').attr('disabled','disabled');
        } else {
          $('#upload_pdf_error4').html("");
              //$('input[type="submit"]').removeAttr('disabled');
          var reader = new FileReader();
            reader.onload = function(e) {
                 var url = input.value;
                var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
                if(ext == 'jpeg' || ext == 'jpg' || ext == 'png'){
                  
                  $('#photo_proof').attr('src', e.target.result);
                }
                if(ext == 'pdf'){

                  $('#photo_proof').attr('src', base_url+'assets/images/pdf-icon.png');
                }
            }
            reader.readAsDataURL(input.files[0]);
            //callValidProof1();
        }
        //callValidProof();
      }
    }
 $(document).on('change','#photo_id_5',function() {
    
        readURL5(this);
    });

        function initializeFileUploads() {
    $('.file-upload-field').change(function () {
        var file = $(this).val();
        $(this).closest('.input-group').find('.file-upload-wrapper').val(file);
    });
    
}


// On document load:
$(function() {
    initializeFileUploads();
});
</script>