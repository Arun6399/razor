<?php 
$this->load->view('front/common/header');
?>
<style type="text/css">
  .modal-body
  {
    background: #1b1f2d !important;

  }
  .modal-header
  {
    background: #1e90ff;
    border-bottom: 1px solid #1e90ff !important;
  }
  .currency_sym
  {
    margin-top: 5px;
    font-weight: bold;
  }
</style>


          <div class=" cpm_mdl_cnt  ">

            <div class="container">
              <div class="cpm_hd_text   text-center">The easiest way to Buy & Sell instantly</div>

               
              <div class="row cpm_p2p_inp_set">
                <div class="col-12 col-md-2">
                  <div class="cpm_log_frm_s">
                    <div class="cpm_log_frm_s_lbl">Choose</div>
                    <div class="cpm_log_frm_s_input cpm_p2p_bs_li_out">
                      <label class="cpm_p2p_bs_li cpm_inptchk"><input type="radio" name="bs" class="cpm_p2p_bs_inp" checked>Buy</label>
                      <label class="cpm_p2p_bs_li cpm_bg_dang"><input type="radio" name="bs" class="cpm_p2p_bs_inp">Sell</label>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-2">
                  <div class="cpm_log_frm_s">
                    <div class="cpm_log_frm_s_lbl">Amount</div>
                    <input type="text" class="cpm_log_frm_s_input">
                      <div class="cpm_p2p_inp_s_lbl">BTC</div>
                      <a href="#" class="cpm_p2p_inp_s_a"><i class="fal fa-search"></i></a>
                  </div>
                </div>
                <div class="col-md-2 col-6">
                  <div class="cpm_log_frm_s">
                    <div class="cpm_log_frm_s_lbl">Coin</div>
                    <select class="cpm_log_frm_s_input">
                      <option value="0">BNB</option>
                      <option value="0">USDT</option>
                      <option value="0">BTC</option>
                    
                    </select>
                  </div>
                </div>
                <!-- <div class="col-md-2 col-6">
                  <div class="cpm_log_frm_s">
                    <div class="cpm_log_frm_s_lbl">Fiat</div>
                  
                    <select class="cpm_log_frm_s_input">
                      <option value="0">INR</option>
                      <option value="0">USDT</option>
                    </select>
                  </div>
                </div> -->
                <div class="col-md-2 col-6">
                  <div class="cpm_log_frm_s">
                    <div class="cpm_log_frm_s_lbl">Payment</div>
                  
                    <select class="cpm_log_frm_s_input">
                      <option value="0">All Payment</option>
                      <option value="0">Phonepe</option>
                      <option value="0">Gpay</option>
                      <option value="0">Bank Transfer</option>
                    </select>
                  </div>
                </div>
                <!-- <div class="col-md-2 col-6">
                  <div class="cpm_log_frm_s">
                    <div class="cpm_log_frm_s_lbl">Religions</div>
                    
                    <select class="cpm_log_frm_s_input">
                      <option value="0">All Religions</option>
                      <option value="0">India</option>
                      <option value="0">America</option>
                      <option value="0">Thailand</option>
                    </select>
                  </div>
                </div> -->
              
              </div>
  



<div class="cpm_p2p_tabl cpm_p2p_buy_set">
<div class="cpm_p2p_tabl_hd">
  <div class="cpm_p2p_tabl_li">
    <div class="cpm_p2p_tabl_li_in cpmp2p_1">Advertisers</div>
    <div class="cpm_p2p_tabl_li_in cpmp2p_2">Price<i class="fal fa-chevron-down"></i></div>
    <div class="cpm_p2p_tabl_li_in cpmp2p_3">Limit/Available</div>
    <div class="cpm_p2p_tabl_li_in cpmp2p_4">Payment</div>
    <div class="cpm_p2p_tabl_li_in cpmp2p_5">Trade</div>
  </div>
</div>
      <div class="cpm_p2p_tabl_body">
      <div class="cpm_p2p_tbll_scrll sbr">
                <?php
                if(!empty($p2p_trade)) {
                  foreach ($p2p_trade as $p2p) {
                    
                    $user_name = UserName($p2p->user_id);
                    $Payment = get_servicename($p2p->payment_method);
                    $crypto = getcurrency_name($p2p->cryptocurrency);
                    $fiats = getfiatcurrencydetail($p2p->fiat_currency);
                    $fiatcurrency = $fiats->currency_symbol;
                    // echo "Fiat --- ".$firstcurrency;

                    if($p2p->type=='Buy'){
                        $class ='';
                        $block_class="Buyclass";
                    }
                    else{
                      $class = 'cpm_bg_dang_a';
                      $block_class="Sellclass";
                    }
                    


                ?>
                <div class="cpm_p2p_tabl_li <?=$block_class;?>">
                  <div class="cpm_p2p_tabl_li_in cpmp2p_1">
                      <div class="cpm_p2p_h1_tx1"><?=$user_name;?></div>
                      <div class="cpm_p2p_h1_tx2_out">
                      <span class="cpm_p2p_h1_tx2"><?=$p2p->datetime?></span>
                      <span class="cpm_p2p_h1_tx2"><?=ucfirst($p2p->terms_conditions)?></span></div>
                  </div>
                <div class="cpm_p2p_tabl_li_in cpmp2p_2">
                  <div class="cpm_p2p_h2_tx1"><?=$p2p->price?>  <span> <?=$fiatcurrency;?></span></div>
                </div>
                <div class="cpm_p2p_tabl_li_in cpmp2p_3">
                    <div class="cpm_p2p_h3_tx1">Available<span> <?=$p2p->trade_amount;?> <?=$crypto;?></span></div>
                    <div class="cpm_p2p_h3_tx1 mb-0">Limit<span><?=$p2p->minimumtrade;?> - <?=$p2p->maximumtrade;?> (<?=$fiatcurrency;?>)</span></div>
                </div>
                <div class="cpm_p2p_tabl_li_in cpmp2p_4">
                    <div class="cpm_p2p_h4_tx1"><?=ucfirst($Payment);?></div>
                    <!-- <div class="cpm_p2p_h4_tx1">Phonpe</div>
                    <div class="cpm_p2p_h4_tx1">Gpay</div> -->
                </div>
                
                <div class="cpm_p2p_tabl_li_in cpmp2p_5">

                  <?php

                    if($user_id== $p2p->user_id)
                    {
                    ?>
                  <a  style="cursor: pointer;color: #fff;"  class="btn btn-primary">My Order</a>
                  <?php } else { ?>
                    <a onclick="tradeClick('<?=$p2p->price?>','<?=$crypto.'_'.$fiatcurrency.'_'.$p2p->minimumtrade.'_'.$p2p->maximumtrade.'_'.$p2p->tradeid;?>')" style="cursor: pointer;" data-toggle="modal" data-target="#submit_modal" data-dismiss="modal" aria-label="Close" class="cpm_p2p_h5_tx1_a <?=$class?>"><?=ucfirst($p2p->type);?> <?=$crypto;?></a>
                  <?php } ?>



                </div>
                </div>
            <?php  } } ?>
      </div>


      </div>

<div class="modal fade right" id="submit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true"> 
  
  <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
  <div class="modal-dialog modal-full-height modal-right iks-popup" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">P2P</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-12 cpm_p2p_inp_set">
                  <?php  $action = base_url()."p2porder";
       $attributes = array('id'=>'p2p_trade','autocomplete'=>"off"); 
        echo form_open($action,$attributes); 
                  ?>     
                  <div class="form-row">
                    <div class="col-5 col-md-5">
                      <div class="row">
                        <div class="col-12 col-md-12">
                          <label for="basic-url"><?php if($p2p->type=='Buy') echo "I Want to Pay "; else echo "I Want To Receive";?><span style="color: red;"> *</span></label>
                          <div class="mb-3">
                            <input type="number" onkeyup="price_calculation(this.value)" class="cpm_log_frm_s_input" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="cryptocurrency" id="cryptocurrency">
                            <br>
                            <span class="currency_sym" id="secondcurrency"></span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <input type="hidden" name="acPrice" id="acPrice" />
                    <input type="hidden" name="minimum" id="minimum" />
                    <input type="hidden" name="maximum" id="maximum" />
                    <input type="hidden" name="trade_id" id="trade_id" />

                    <div class="col-5 col-md-5 offset-2">
                      <div class="row">
                        <div class="col-12 col-md-12">
                          <label for="basic-url"><?php if($p2p->type=='Buy') echo "I Will to Receive "; else echo "I Will Sell"; ?> <span style="color: red;"> *</span></label>
                          <div class="mb-3">
                            <input type="number"  class="cpm_log_frm_s_input" readonly="" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="fiat_currency" id="fiat_currency" >
                            <br>
                            <span class="currency_sym" id="firstcurrency"></span>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="col-12 col-md-12">
                      <div class="row">
                        <div class="col-12 col-md-12">
                          <div class="profile-flex border-0 pt-4 pull-left">
                            <div class="text-center">
                              <button name="trade_btn" id="trade_btn" value="trade_btn"  class="btn btn-success waves-effect waves-light button" type="submit"> Trade </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php echo form_close();?> </div>
              </div>
            </div>
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
  
function tradeClick(price,currencys)
{
 
   var currency = currencys.split('_');
   var firstcurrency = currency[0];
   var secondcurrency = currency[1];
   var minimum = currency[2];
   var maximum = currency[3];
   var trade_id = currency[4];
   $('#firstcurrency').html(firstcurrency);
   $('#secondcurrency').html(secondcurrency);
   $('#minimum').val(minimum);
   $('#maximum').val(maximum);
   $('#acPrice').val(price);
   $('#trade_id').val(trade_id);
   

}

function price_calculation(val)
{

  var acPrice = $('#acPrice').val();
  var minimum = $('#minimum').val();
  var maximum = $('#maximum').val();

  var final_value = val / acPrice;
  $('#fiat_currency').val(final_value.toFixed(2));


}


$(document).ready(function () {
   $('#p2p_trade').validate({



        rules: {
            cryptocurrency: {
                required: true,
                number:true,
                min: function(){ return $('#minimum').val() }
                // max: function(){ return $('#maximum').val() }
                
            }
        },
        messages: {
            cryptocurrency: {
                required: "Please Enter Price",
                number: " Please Enter Numbers ",
                min: " Please Enter Minimum Amount"
                // max: " Please Enter Maximum Amount"
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
    submitHandler: function(form)
    {
     
      var $form = $(form);
      form.submit();
      $('#trade_btn').prop('disabled', true);

    } 

    });
});   

</script>