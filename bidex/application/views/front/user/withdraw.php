<?php $this->load->view('front/common/headerlogin');
?>
<link href="<?php echo base_url();?>assets/icons/cryptofont.css"rel="stylesheet">
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
                    <!-- <div class="col-xl-12">
                        <div class="card sub-menu">
                            <div class="card-body">
                                <ul class="d-flex">
                                    <li class="nav-item">
                                        <a href="account-overview.html" class="nav-link">
                                            <i class="mdi mdi-bullseye"></i>
                                            <span>Overview</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="account-deposit.html" class="nav-link">
                                            <i class="mdi mdi-heart"></i>
                                            <span>Deposit</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="account-withdraw.html" class="nav-link">
                                            <i class="mdi mdi-pentagon"></i>
                                            <span>Withdraw</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="account-api.html" class="nav-link">
                                            <i class="mdi mdi-database"></i>
                                            <span>API</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="account-affiliate.html" class="nav-link">
                                            <i class="mdi mdi-diamond"></i>
                                            <span>Affiliate</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Withdraw</h4>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-xl-8">
                                        
                                            <?php 
                                $action = '';
                                $attributes = array('id'=>'withdrawcoin','autocomplete'=>"off",'class'=>'py-5'); 
                                echo form_open($action,$attributes); ?>

                                <?php if($sel_currency->withdraw_status!=0){?>
                                              <input type="hidden" name="fees" id="fees" value="<?php echo $fees;?>">
                                            <input type="hidden" name="fees_type" id="fees_type" value="<?php echo $fees_type;?>">
                                            <div class="mb-3 row align-items-center">
                                                <div class="col-sm-4">
                                                    <label for="inputEmail3" class="col-form-label">Destination Address
                                                        <br />
                                                        <small>Please double check this address</small>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text  bg-primary"><i
                                                                    class="mdi mdi-currency-usd fs-18 text-white"></i></label>
                                                        </div>
                                                        <input type="text" class="form-control text-end" name="address" id="address">
                                                    </div>
                                                </div>
                                            </div>
                                              
                                            <div class="mb-3 row align-items-center">
                                                <input type="hidden" name="ids" id="ids" value="<?php echo $sel_currency->id;?>">
                                                <div class="col-sm-4">
                                                    <label for="inputEmail3" class="col-form-label">Amount <?php echo $sel_currency->currency_symbol;?>
                                                        <br />
                                                        <small>Maximum amount withdrawable: <?php echo $sel_currency->max_withdraw_limit;?> <?php echo $sel_currency->currency_symbol;?></small><br>
                                                        <small>Minimum amount withdrawable: <?php echo $sel_currency->min_withdraw_limit;?> <?php echo $sel_currency->currency_symbol;?></small>
                                                    </label>
                                                </div>
                                                
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <?php if($sel_currency->id==1 || $sel_currency->id==2 || $sel_currency->id==3 || $sel_currency->id==5 || $sel_currency->id==6 || $sel_currency->id==9 || $sel_currency->id==10){?>
                                                            <label class="input-group-text bg-primary"><i
                                                                    class="cc <?php echo $sel_currency->currency_symbol;?>-alt text-white"></i></label>
                                                                <?php } ?>
                                                                    <?php if($sel_currency->id==4){?>
                                                                         <label class="input-group-text bg-primary"><i class="cf cf-trx text-white"></i></label>
                                                                <?php }?>
                                                                <?php if($sel_currency->id==7){?>
                                                                         <label class="input-group-text bg-primary"><i class="cf cf-xlm text-white"></i></label>
                                                                <?php }?>
                                                                 <?php if($sel_currency->id==8){?>
                                                                         <label class="input-group-text bg-primary"><i class="cf cf-xmr text-white"></i></label>
                                                                <?php }?>
                                                        </div>
                                                        <input type="text" class="form-control text-end" name="amount" id="amount" 
                                                            placeholder="Amount" onkeyup="calculate();" value="">
                                                       <input type="hidden" class="form-control text-end" name="payment_id" id="payment_id"  value="<?php echo $payment_id; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($sel_currency->id==6) {?>
                                            <div class="mb-3 row align-items-center" >
                                                <div class="col-sm-4">
                                                    <label for="inputEmail3" class="col-form-label">Destination Tag</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <label class="input-group-text  bg-primary"><i
                                                                    class="mdi mdi-pentagon fs-18 text-white"></i></label>
                                                        </div>
                                                        <input type="text" class="form-control text-end" name="destination_tag" id="destination_tag">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                            <div class="mb-3 row align-items-center">
                                                <div class="col-sm-6">
                                                    <label for="inputEmail3" class="col-form-label"><?php echo $sel_currency->currency_name;?> Network Fee
                                                        (<?php echo $sel_currency->currency_symbol;?>)
                                                        <br />
                                                        <small>Transactions on the <?php echo $sel_currency->currency_name;?> network are priorirized by
                                                            fees</small>
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <h4 class="text-end" id="fees_p">0 <?=$selcsym?></h4>
                                                </div>
                                            </div>
                                          
                                            <div class="text-end">
                                                <button class="btn btn-primary" type="submit" name="withdrawcoin" id="withdrawcoin">Withdraw Now</button>
                                            </div>

                                            <?php } else{?>

                                        <div class="mb-3">
                                            <label style="color:red;" class="form-label">Sorry this crypto Unavailable for withdraw</label>
                                        </div>  

                                    <?php }?> 

                                        <?php
                                        echo form_close();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Important Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="important-info">
                                    <ul>
                                        <li>
                                            <i class="mdi mdi-checkbox-blank-circle"></i>
                                            For security reasons, Bidex process withdrawals by review once a day. For
                                            more information in this policy. Please see our wallet security page.
                                        </li>
                                        <li>
                                            <i class="mdi mdi-checkbox-blank-circle"></i>
                                            Submit your withdrawals by 07:00 UTC +00 (about 11 hour) to be included in
                                            the days batch
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php $this->load->view('front/common/footerlogin');?>       


 <script type="text/javascript">

        $("#withdrawcoin").validate({
          rules: {
                  address: {
                    required: true
                  },
                  amount: {
                    required: true,
                    number:true
                  }
                },
          messages: {
                address: {
                  required: "Please enter address"
                },
                amount: {
                  required: "Please enter Amount",
                  number: "Invalid Amount"
                }
                
              }     
         });

    function getcurrency(ids,texts) {
      window.location = texts.options[texts.selectedIndex].text; 
    }    
    function getcurrency1(ids,texts)
    { 
      var txt = texts.options[texts.selectedIndex].text;
      var namer = ids.split("_");
      var id = namer[0];
      var cname = namer[1];
      var bal =  namer[2]; 
      $(".sym").html(txt);
      $(".syname").html(cname);

      if(id==6)
      {
        var bals =  parseFloat(bal).toFixed(2);
        $(".totbal").html(bals);
        $(".cryptoonly").hide();
      }
      else
      {
         var bals = parseFloat(bal).toFixed(8);
        $(".totbal").html(bals);
        $(".cryptoonly").show();
      }
    }
function calculate(){
    
    var fees_type = $('#fees_type').val();
    var fees = $('#fees').val();

    var amount = $('#amount').val();

    if(fees_type=='Percent'){
        var fees_p = ((parseFloat(amount) * parseFloat(fees))/100);
        var amount_receive = parseFloat(amount) - parseFloat(fees_p);
    }
    else{
        var fees_p = fees;
        var amount_receive = parseFloat(amount) - parseFloat(fees_p);
    }
     // $('#fees_p').html(fees_p.toFixed(10));

      $('#fees_p').html(fees_p);

    $('#amount_receive').html(amount_receive);
}
   
function calculate2(){
    
    var fees_type = '<?=$sel_currency->withdraw_fees_type?>';
    var fees = '<?=$sel_currency->withdraw_fees?>';
    var amount = $('#amount2').val();
    // console.log(fees)
    if(fees_type=='Percent'){
        var fees_p = ((parseFloat(amount) * parseFloat(fees))/100);
        var amount_receive = parseFloat(amount) - parseFloat(fees_p); 
    }
    else{
        var fees_p = fees;
        var amount_receive = parseFloat(amount) - parseFloat(fees_p);
    }
    $('#fees_p2').html(fees_p);  
    if(amount_receive<=0){
      $('#amount_receive2').html('0');
      $('#amount_receive_error2').html('Please enter valid amount');
    }
    else{
      $('#amount_receive_error2').html('');
    $('#amount_receive2').html(amount_receive);
  }
}    
    </script> 