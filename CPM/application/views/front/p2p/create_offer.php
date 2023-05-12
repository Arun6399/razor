<?php 
$this->load->view('front/common/header');
?>
    <div class=" cpm_mdl_cnt  login_page">
            <div class="container">
              <div class="row align-items-center">
                <form id="create_offer" action="<?php echo base_url();?>create_offer" method="POST">
                <div class="col-lg-12" >
                <div class="cpm_hd_text cpm_clr_blue_b text-center">Create Offer</div>
                  <div class="cpm_log_set bx">
                    <div class="row">
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Choose</div>
                          <div class="cpm_log_frm_s_input cpm_p2p_bs_li_out cpm_p2p_bs_li_infrm">
                            <label class="cpm_p2p_bs_li cpm_inptchk"><input type="radio" name="type" value="buy" class="cpm_p2p_bs_inp" checked>Buy</label>
                            <label class="cpm_p2p_bs_li cpm_bg_dang"><input type="radio" name="type" value="sell" class="cpm_p2p_bs_inp">Sell</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Coin</div>
                          <select class="cpm_log_frm_s_input" name="cryptocurrency" id="cryptocurrency">
                            
                            <?php if($currency) {
                              foreach($currency as $cur) {
                                ?>
                                <option 
                                value ="<?php echo $cur->id; ?>"><?php echo $cur->currency_symbol; ?></option>
                                <?php
                              }
                            } ?>
                          
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Payment Method</div>
                          <select class="cpm_log_frm_s_input" name="payment" id="payment">
                            <?php if($services) {
                              foreach($services as $service) {
                                ?>
                                <option 
                                value ="<?php echo $service->id; ?>"><?php echo ucfirst($service->service_name); ?></option>
                                <?php
                              }
                            } ?>
                          
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Fiat</div>
                        
                          <select class="cpm_log_frm_s_input" name="fiat_currency" id="fiat_currency">
                             <?php if($fiatcurrency) {
                              foreach($fiatcurrency as $fiat) {
                                ?>
                                <option 
                                value ="<?php echo $fiat->id; ?>"><?php echo $fiat->currency_symbol; ?></option>
                                <?php
                              }
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Country</div>
                          
                          <select class="cpm_log_frm_s_input" name="country" id="country">
                           <?php if($country) {
                              foreach($country as $co) {
                                ?>
                                <option 
                                value ="<?php echo $co->id; ?>"><?php echo $co->country_name; ?></option>
                                <?php
                              }
                            } ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Price</div>
                          <input type="text" class="cpm_log_frm_s_input" name="price" id="price" >
                        
  
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Minimum Trade Limit</div>
                          <input type="text" class="cpm_log_frm_s_input" name="minimum_limit" id="minimum_limit" >
  
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Maximum Trade Limit</div>
                          <input type="text" class="cpm_log_frm_s_input" name="maximum_limit" id="maximum_limit" >
  
                        </div>
                      </div>
                      <div class="col-lg-4 col-md-6">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Trade Amount</div>
                          <input type="text" class="cpm_log_frm_s_input" name="trade_amount" id="trade_amount" >
  
                        </div>
                      </div>
                      <div class="col-lg-12">
                        <div class="cpm_log_frm_s">
                          <div class="cpm_log_frm_s_lbl">Instruction</div>
                          <textarea class="cpm_log_frm_s_input" name="instraction" id="instraction" style="height:100px"></textarea>
                          <!-- <div class="error">Enter Instruction</div> -->
  
                        </div>
                      </div>
                    </div>
                    <button class="cpm_log_frm_btn" name="submit_create" value="submit" type="submit"><i class="ti-lock"></i>Make Offer</button>
                  </div>
                </div>
              </form>
              </div>
            </div>
          </div>
<?php 
$this->load->view('front/common/footer');
?>

<script type="text/javascript">
   $('#create_offer').validate({
        rules: {
            type: {
                required: true
            },
            crypto: {
                required: true
            },
            payment: {
                required: true
            },
            currency: {
                required: true
            },
            country: {
                required: true
            },
            price:{
              required:true,
              number:true,
            },
            minimum_limit:{
              required:true,
              number:true,
            },
            maximum_limit:{
              required:true,
              number:true,
            },
            trade_amount:{
              required:true,
              number:true,
            },
            instraction:{
              required:true
            } 

        },
        messages: {
            type: {
                required: "Please Choose Type"
            },
            crypto: {
                required: "Please Choose Coin"
            },
            payment:{
              required: " Please Choose Payment",
            },
             currency:{
              required: " Please Choose Currency",
            },
             country:{
              required: " Please Choose Country",
            },
             price:{
              required: " Please Enter Price",
              number: 'Please Enter Numbers Only'
            },
             minimum_limit:{
              required: " Please Enter Minimum Price",
              number: 'Please Enter Numbers Only'
            },
             maximum_limit:{
              required: " Please Enter Maximum Price",
              number: 'Please Enter Numbers Only'
            },
            trade_amount:{
              required: " Please Enter Trade Amount",
              number: 'Please Enter Numbers Only'
            },
             instraction:{
              required: " Please Enter Instraction",
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
</script>