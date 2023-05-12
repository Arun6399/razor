<?php $this->load->view('front/common/headerlogin');
$user_id = $this->session->userdata('user_id');
$symbol = $_GET['sym'];
?>

<style>
.form-selectgroup-input {
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    opacity: 0;
}
.form-selectgroup-input:checked+.form-selectgroup-label {
    z-index: 1;
    color: #ffffff;
    background: hsl(169deg 75% 39%);
    border-color: #19ad91;
}


.form-selectgroup-input:checked+.form-selectgroup-labels {
    z-index: 1;
    color: #ffffff;
    background: hsl(169deg 75% 39%);
    border-color: #19ad91;
}

.form-selectgroup-labels {
    position: relative;
    display: block;
    min-width: calc(1.4285714em + .875rem + 2px);
    margin: 0;
    padding: .4375rem .75rem;
    font-size: .875rem;
    line-height: 1.4285714;
    color: #656d77;
    background: #fff;
    text-align: center;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid #dadcde;
    border-radius: 3px;
    transition: border-color .3s, background .3s, color .3s;
}


.form-selectgroup-label {
    position: relative;
    display: block;
    min-width: calc(1.4285714em + .875rem + 2px);
    margin: 0;
    padding: .4375rem .75rem;
    font-size: .875rem;
    line-height: 1.4285714;
    color: #656d77;
    background: #fff;
    text-align: center;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid #dadcde;
    border-radius: 3px;
    transition: border-color .3s, background .3s, color .3s;
}
</style>
<div class="page-title dashboard">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="page-title-content">
                            <p>Welcome Back,
                                <span> <?php echo $user->bidex_fname;?></span>
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
                                <h4 class="card-title"> Wallet Deposit Address</h4>
                            </div>
                            <div class="card-body" id="deposits">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <?php 
                                    
                                    if(count($all_currency) > 0) {
                                        $curr = array_column($all_currency, 'currency_symbol');
                                            $i=1;
                                            foreach ($all_currency as $key => $currency) {
                                                if($this->uri->segment(2)==$currency->currency_symbol){ 
                                                    
                                                    $cls="active"; 
                                                
                                                } else { 
                                                    $cls="";
                                                }     
                                            $i++;
                                                ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $cls;?>" data-toggle="tab" href="#tab<?php echo $i;?>"><img src="<?php echo $currency->image;?>" width="16px"><?php echo $currency->currency_symbol;?></a>
                                    </li>
                                <?php } }?>
                                </ul>
                                
                                <div class="tab-content">
                                    <?php
                                    
                                    if(count($dig_currency) >0)
                                    {
                                      $i=1;

                                       foreach ($dig_currency as $key => $digital) 
                                     {
                                        // if($i==1){ $cls="active"; } else { $cls="";}
                                 if($this->uri->segment(2)==$digital->currency_symbol){ 
                                                    
                                                    $cls="active"; 
                                                
                                                } else { 
                                                    $cls="";
                                                } 
                                     
                                       $i++;
                                      
                                    if($digital->type=="fiat")
                                    {
                                        $format = 2;
                                    }
                                    elseif($digital->currency_symbol=="USDT")
                                    {
                                        $format = 6;
                                    }
                                    else
                                    {
                                        $format = 6;
                                    }
                                    // $smd_bal = $this->common_model->getTableData('users',array('id'=>$user_id));
                                    $order_balance = $this->common_model->customQuery("SELECT SUM(Total) as Total FROM `bidex_coin_order` WHERE `pair_symbol` LIKE '%$curr_symbol%' AND 'userId' =$user_id")->row();    
                                    $order= $order_balance->Total;
                                      if($order==""){
                                        $order_bal="0";
                                      }
                                      else
                                      {
                                        $order_bal = $order_balance->Total;
                                      }
                                    $coin_price_val = to_decimal($wallet['Exchange AND Trading'][$digital->id], $format);

                                    $coin_price = $coin_price_val * $digital->online_usdprice;
                                       
                                     $userbalance = abs(getBalance($user_id,$digital->id));
                                     $inorderbalance = abs(getscrowBalance($user_id,$digital->id));
                             

                                      // $crypto_address='test';



                                     if($digital->id == '9'){

                                        $bch_address=getAddress($user_id,$digital->id);
                                        $bch=explode(":",$bch_address);
                                        $crypto_address=$bch[1];



                                     }else{

                                    $crypto_address = getAddress($user_id,$digital->id);
                                     }

                                    $USDT_Balance = abs($userbalance * $digital->online_usdprice);
                                    $available_balance=abs($userbalance-$order_bal);
                                    if($crypto_address){
                                          $img =  "https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$crypto_address&choe=UTF-8&chld=L";
                                      }else{
                                        $img = base_url('assets/images/empty_qr.png');
                                      }
                                  

                                    $pairing = $this->common_model->getTableData('trade_pairs',array('from_symbol_id'=>$digital->id,'status'=>1))->row();
                                if(!empty($pairing))
                                {
                                    $fromid = $pairing->from_symbol_id;
                                    $fromcurr = $this->common_model->getTableData('currency',array('id'=>$fromid,'status'=>1))->row();
                                    $fromSYM = $fromcurr->currency_symbol;
                                    $toid = $pairing->to_symbol_id;
                                    $tocurr = $this->common_model->getTableData('currency',array('id'=>$toid,'status'=>1))->row();
                                    $toSYM = $tocurr->currency_symbol;

                                    $traDepair = $fromSYM."_".$toSYM; 

                                }
                                else
                                {
                                   $pairing = $this->common_model->getTableData('trade_pairs',array('to_symbol_id'=>$digital->id,'status'=>1))->row();
                                   if(!empty($pairing))
                                {
                                    $fromid = $pairing->to_symbol_id;
                                    $fromcurr = $this->common_model->getTableData('currency',array('id'=>$fromid,'status'=>1))->row();
                                    $fromSYM = $fromcurr->currency_symbol;

                                    $toid = $pairing->from_symbol_id;
                                    $tocurr = $this->common_model->getTableData('currency',array('id'=>$toid,'status'=>1))->row();
                                    $toSYM = $tocurr->currency_symbol;

                                    $traDepair = $toSYM."_".$fromSYM;
                                }

                                }?>
                                    <div class="tab-pane fade show <?php echo $cls;?>" id="tab<?php echo $i;?>">

                                        <!-- <h2>test</h2> -->


                                         
                                          <?php if($digital->deposit_status !=1){?>
                                        <div class="qrcode">
                                            <img src="<?php echo $img;?>" alt="" width="150" id="crypto_img<?php echo $i;?>">
                                        </div>
                                        <!-- <form> -->

                                             <div id="pre_address_div_<?php echo $i;?>" <?php if($crypto_address != '0') { ?>style="display:none" <?php } ?>>
                                            <div class="mb-3">
                                              <label class="form-label address_gen">To Generate Address</label>
                                              <div class="form-selectgroup">
                                                <label class="form-selectgroup-item">
                                                  <button onclick="generate_address_2('<?php echo $digital->id;?>','<?php echo $digital->currency_symbol;?>','<?php echo $i; ?>',this);" class="form-selectgroup-label btn btn-success px-4" >Click Here</button>
                                                </label>

                                              </div>
                                            </div>
                                          </div>

                                            <div id="address_<?php echo $i;?>" <?php if($crypto_address == '0') { ?>style="display:none" <?php } ?>>
                                            <div class="input-group">
                                                <input type="text" class="form-control"
                                                    value="<?php echo $crypto_address;?>" name="crypto_address" id="crypto_address<?php echo $i;?>" readonly>
                                                <div class="input-group-append" style="cursor: pointer;" onclick="copy_function('<?php echo $i; ?>');">
                                                    <span class="input-group-text bg-primary text-white">Copy</span>
                                                </div>
                                            </div>



                                               <?php if($digital->id == 8) {  ?>

                                              <div class="mb-3">
                                              <label class="form-label">Chain</label>
                               <br>

                                              <div class="form-selectgroup">

                                                                 <label class="form-selectgroup-item">
                                                  <input type="checkbox" name="selctcheck" id="selctcheck5" class="form-selectgroup-input" value="5" onclick="selectcurrency(2,'<?php echo $i; ?>')">
                                                  <span class="form-selectgroup-labels">ERC 20</span>
                                                </label>
                                                                     <label class="form-selectgroup-item">
                                                  <input type="checkbox" name="selctcheck" id="selctcheck5" class="form-selectgroup-input" value="5" onclick="selectcurrency(4,'<?php echo $i; ?>')">
                                                  <span class="form-selectgroup-labels">TRC 20</span>
                                                </label>
                                                <label class="form-selectgroup-item">
                                                  <input type="checkbox" name="selctcheck" id="selctcheck4"  class="form-selectgroup-input" value="4" onclick="selectcurrency(3,'<?php echo $i; ?>')">
                                                  <span class="form-selectgroup-label">BEP 20(BSC)</span>
                                                </label>

                            
                                              </div>
                                            </div>
                                               <?php } ?>





                                            <?php if($digital->id == 11) {  ?>

                                              <div class="mb-3">
                                              <label class="form-label">Select Chain</label>
                                              <div class="form-selectgroup">

                                                                 <label class="form-selectgroup-item">
                                                  <input type="checkbox" name="selctcheck" id="selctcheck5" class="form-selectgroup-input" value="5" onclick="selectcurrency(2,'<?php echo $i; ?>')">
                                                  <span class="form-selectgroup-labels">ERC 20</span>
                                                </label>
                                                <label class="form-selectgroup-item">
                                                  <input type="checkbox" name="selctcheck" id="selctcheck5" class="form-selectgroup-input" value="5" onclick="selectcurrency(4,'<?php echo $i; ?>')">
                                                  <span class="form-selectgroup-labels">TRC 20</span>
                                                </label>
                                                <label class="form-selectgroup-item">
                                                  <input type="checkbox" name="selctcheck" id="selctcheck4"  class="form-selectgroup-input" value="4" onclick="selectcurrency(3,'<?php echo $i; ?>')">
                                                  <span class="form-selectgroup-label">BEP 20(BSC)</span>
                                                </label>

                                              </div>
                                            </div>
                                               <?php } ?>


                                            <?php if($digital->id == 6){?>
                                                <br>
                                            <div class="input-group" id="det_tag" <?php if($digital->id != 6) { ?>style="display:none" <?php } ?>>
                                                <input type="text" class="form-control" id="destination_tag"
                                                    value="<?php echo $destination_tag;?>" name="destination_tag" id="destination_tag" readonly>
                                                        <div class="input-group-append" style="cursor: pointer;" onclick="copy_tag();">
                                                    <span class="input-group-text bg-primary text-white">Copy</span>
                                                </div>
 
                                            </div>
                                            <?php }?>
                                        </div>

                                        <?php } else {?>

                                        <div class="mb-3">
                                        <label style="color:red;" class="form-label">Sorry this crypto Unavailable for deposit</label>
                                      </div>  

                                    <?php }?> 
                                       
                                      <!-- </form> -->
                                        <ul>
                                            <li>
                                                <i class="mdi mdi-checkbox-blank-circle"></i>
                                               Network transfers will be credited to your Bidex account after
                                                25 network confirmations.
                                            </li>
                                            <li>
                                                <i class="mdi mdi-checkbox-blank-circle"></i>
                                                Deposits to this address are unlimited. Note that you may not
                                                be able to withdraw all of your funds at once if you deposit more
                                                than your daily withdrawal limit.
                                            </li>
                                        </ul>
                                    </div>
                                    <?php } }?>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
<?php $this->load->view('front/common/footerlogin');?>        

<script type="text/javascript">

        function selectcurrency(val,i){
        
            var currency_id = val;
            var type = "digital";
            if(type=='fiat')
            {
                //alert(arr[0]);
                $(".grey-box").css('display','none');
                $(".cryp_add").css('display','none');
                $(".dig_button").css('display','none');
                $(".fiat_div").css('display','block');
                $(".fiat_deposit").css('display','block');
                $.ajax({
                    url: base_url+"change_chain_address",
                    type: "POST",
                    data: "currency_id="+currency_id,
                    success: function(data) {

                        alert('test');
                        var res = jQuery.parseJSON(data);
                        if(currency_id==5){
                            var sym = '&inr;';
                        }
                        $('#minimum_deposit').html(res.minimum_deposit+' '+sym);
                    }
                });
                $("#currency").val(currency_id);
                $(".currency").val(currency_id);
            }
            else
            { 
              $(":checkbox").attr("checked", false);
              $("#selctcheck" + val).prop('checked', true);

              $(".grey-box").css('display','block');
                $(".fiat_div").css('display','none');
                $(".cryp_add").css('display','block');
                $(".fiat_deposit").css('display','none');
                $('.bank_wire').css('display','none');
                $('.paypal_form').css('display','none');
                $(".dig_button").css('display','block');
                $("#wallet_deposit").css('display','none');
                $.ajax({
                    url: base_url+"change_chain_address",
                    type: "POST",
                    data: "currency_id="+currency_id,
                    success: function(data) {
                        var res = jQuery.parseJSON(data);
                        console.log(res)
                      
                        $('#crypto_address'+i).val(res.address);
                        $("#crypto_img"+i).attr("src",res.img);
                        $('#det_tag').css('display','none');
                        $('#minimum_withdrawal').html(res.minimum_deposit);
                        $('.syname').html(res.coin_name);
                        $('.sym').html(res.coin_symbol);
                         
                        if(currency_id==8){
                            $('#det_tag').css('display','');
                            $('#destination_tag').val(res.destination_tag);
                        }


                        $('#addresscryp').keyup(function(e) {
                                var txtVal = $(this).val();
                                $('#crypto_address').val(txtVal);
                                  });
                    }
                });
            }


}




         var base_url='<?php echo base_url();?>';
    var front_url='<?php echo front_url();?>';

         var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';


        $.ajaxPrefilter(function (options, originalOptions, jqXHR) {
            if (options.type.toLowerCase() == 'post') {
                options.data += '&'+csrfName+'='+$("input[name="+csrfName+"]").val();
                if (options.data.charAt(0) == '&') {
                    options.data = options.data.substr(1);
                }
            }
        });

        $( document ).ajaxComplete(function( event, xhr, settings ) {
            if (settings.type.toLowerCase() == 'post') {
                $.ajax({
                    url: front_url+"get_csrf_token", 
                    type: "GET",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                            var dataaa = $.trim(data);
                         $("input[name="+csrfName+"]").val(dataaa);
                    }
                });
            }
        });
        function copy_function(id) 
        {
            var copyText = document.getElementById("crypto_address"+id);
            copyText.select();
            document.execCommand("COPY");
            //$('.copy_but').html("COPIED");
            $.growl.notice({title: "Bidex", message: 'Copied Successfully' });
        }
             function copy_tag(id) 
        {
            var copyText = document.getElementById("destination_tag");
            copyText.select();
            document.execCommand("COPY");
            //$('.copy_but').html("COPIED");
            $.growl.notice({title: "Bidex", message: 'Copied Successfully' });
        }

        function change_address(sel)
        {
            //alert("asd");
            //console.log(sel);
            var arr1 = sel.value;
            var arr = arr1.split('#');
            var currency_id = arr[0];
            var type = arr[1];

            /*if(currency_id==3){
               $("#myModal").modal('show');

            }*/
            if(type=='fiat')
            {
                //alert(arr[0]);
                $(".grey-box").css('display','none');
                $(".cryp_add").css('display','none');
                $(".dig_button").css('display','none');
                $(".fiat_div").css('display','block');
                $(".fiat_deposit").css('display','block');
                $.ajax({
                    url: base_url+"change_address",
                    type: "POST",
                    data: "currency_id="+currency_id,
                    success: function(data) {
                        var res = jQuery.parseJSON(data);
                        if(currency_id==5){
                            var sym = '&inr;';
                        }
                        $('#minimum_deposit').html(res.minimum_deposit+' '+sym);
                    }
                });
                $("#currency").val(currency_id);
                $(".currency").val(currency_id);
            }
            else
            { 
              $(".grey-box").css('display','block');
                $(".fiat_div").css('display','none');
                $(".cryp_add").css('display','block');
                $(".fiat_deposit").css('display','none');
                $('.bank_wire').css('display','none');
                $('.paypal_form').css('display','none');
                $(".dig_button").css('display','block');
                $("#wallet_deposit").css('display','none');
                $.ajax({
                    url: base_url+"change_address",
                    type: "POST",
                    data: "currency_id="+currency_id,
                    success: function(data) {
                        console.log(data);
                        var res = jQuery.parseJSON(data);
                        $('#crypto_address'+i).val(res.address);
                        $("#crypto_img"+i).attr("src",res.img);
                        $('#det_tag').css('display','none');
                        $('#minimum_withdrawal').html(res.minimum_deposit);
                        $('.syname').html(res.coin_name);
                        $('.sym').html(res.coin_symbol);
                        if(currency_id==6){
                            $('#det_tag').css('display','');
                            $('#destination_tag').val(res.destination_tag);
                        }
                    }
                });
            }
        }

        function show_bank(){
            $('.paypal_form').css('display','none');
            $('.bank_wire').css('display','');
        }
        function show_paypal(){
            $(".bank_wire").css("display","none");
            $('.paypal_form').css('display','');
        }


function generate_address_2(currency_id,currency_symbol,i,thisId)
{

$.ajax({
            url: base_url+"change_address",
            type: "POST",
            data: "currency_id="+currency_id,
            success: function(data) {
                // console.log(data);
                // return false;
                var res = jQuery.parseJSON(data);
                $('#crypto_address'+i).val(res.address);
                $('#address_'+i).css('display','block');
                $("#crypto_img"+i).attr("src",res.img);
                $('#det_tag').css('display','none');
                $('#minimum_withdrawal').html(res.minimum_deposit);
                $('.syname').html(res.coin_name);
                $('.sym').html(res.coin_symbol);
                // $('.address_gen').hide();
                $(thisId).hide();
                
                parent = $(thisId).closest('div').parent().hide();
                $.growl.notice({title: "Bidex", message: res.coin_name+' Address Generate Successfully' });
                // console.log( parent )    

                if(currency_id==6){
                    $('#det_tag').css('display','');
                    $('#destination_tag').val(res.destination_tag);
                }
            }
        });

  // $.ajax({
  //       type: "POST",
  //       url: "<?php echo base_url();?>updateaddress",
  //       data: { 
  //         currency_symbol: currency_symbol
                    
  //       },
        
  //       success: function(result) {
  //         var res = JSON.parse(result);
  //         // if(res.status == 'success')
  //         // {
  //         //   $.growl.notice({message:res.msg});
  //         //   location.reload();
  //         // } else {
  //         //   $.growl.error({message:res.msg});
  //         // }

  //         // $.growl.notice({message:"Address generated successfully"});
  //         // location.reload();
  //         console.log(res)

  //         if(res.status == 'success')
  //         {
  //           $.growl.notice({message:res.msg});
  //           $("#pre_address_div_"+i).hide();
  //           $("#address_div_"+i).show();
  //           if(i == 3)
  //           {
  //             $("#pre_address_div_2").hide();
  //             $("#address_div_2").show();
  //             $("#crypto_address2").val(res.address_ETH);
  //             $("#crypto_img2").attr("src",res.img_ETH);
  //             $("#pre_address_div_4").hide();
  //             $("#address_div_4").show();
  //             $("#crypto_address4").val(res.address_BNB);
  //             $("#crypto_img4").attr("src",res.img_BNB);
  //             $("#pre_address_div_5").hide();
  //             $("#address_div_5").show();
  //             $("#crypto_address5").val(res.address_TRX);
  //             $("#crypto_img5").attr("src",res.img_TRX);
  //             $("#address_chaindiv_"+i).show();
  //           }
  //           $("#crypto_address"+i).val(res.address);
  //           console.log(res.img)
  //           $("#crypto_img"+i).attr("src",res.img);
  //           //location.reload();
  //           //window.location.href="<?php echo base_url()?>wallet";
  //         } else {
  //           $.growl.error({message:res.msg});
  //         }
          

  //       },
  //       error: function(result) {
          
  //       }
  //   });
}

</script>

