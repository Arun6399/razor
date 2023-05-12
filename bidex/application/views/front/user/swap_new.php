<?php $this->load->view('front/common/headerlogin')?>
        <style>
        .apexcharts-menu-icon{
            display: none!important;
        }

        .exchange-box .currency .select-country {
    background-color: white;
    border: transparent;
    border-radius: 10px;
    border: 1px solid #262261;
    position: absolute;
    width: 105px!important;
    height: 100%;
    padding: 13px;
    left: 0;
    top: 0;
    font-size: 18px;
    font-weight: 500;
    color: #262261;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
          .refer {
            height: auto;
    width: 100%;
    box-shadow: 0 10px 20px #1a488e0f;
    padding: 5vh 3.5vw 2vh;
    background: #fff;
    background-size: cover;
          }
          #chart {
  max-width: 650px;
  margin: 35px auto;
  /* height: 75vh; */
}
.swap {
    padding: 30px;
    box-shadow: 0 10px 30px 0 rgb(82 63 105 / 8%);
    border-radius: 20px;
}
.blurred-box{
    position: relative;
    /* width: 250px;
    height: 350px;
    top: calc(50% - 175px);
    left: calc(50% - 125px); */
    background: inherit;
    padding-bottom: 40px;
    border-radius: 40px;
    overflow: hidden;
    box-shadow: inset 0 0 0 200px rgba(255,255,255,0.05);
    filter: blur(0px);
  }
  .percent li {
    padding: 0px 5px;
    border: 1px solid #a7a4a4;
    border-radius: 5px;
    margin: 0px 5px;
}
  /* .blurred-box:after{
    content: '';
    width: 300px;
    height: 300px;
    background: inherit;
    position: absolute;
    left: -25px;
    right: 0;
    top: -25px;
    bottom: 0;
    box-shadow: inset 0 0 0 200px rgba(255,255,255,0.05);
    filter: blur(10px);
   } */

   .progress {
    background: rgba(255,255,255,0.1);
    justify-content: flex-start;
    border-radius: 100px;
    align-items: center;
    position: relative;
    /* padding: 0 5px; */
    display: flex;
    height: 10px;
    width: 200px;
  }
   .progress-value {
    animation: load 3s normal forwards;
    box-shadow: 0 10px 40px -10px #fff;
    border-radius: 100px;
    background: ORANGERED;
    height: 10px;
    width: 0;
  }
  .progress-value-1 {
    animation: load-1 3s normal forwards;
    box-shadow: 0 10px 40px -10px #fff;
    border-radius: 100px;
    background: ORANGERED;
    height: 10px;
    width: 0;
  }

  .progress-value-2 {
    animation: load-2 3s normal forwards;
    box-shadow: 0 10px 40px -10px #fff;
    border-radius: 100px;
    background: ORANGERED;    height: 10px;
    width: 0;
  }
  .progress-value-3 {
    animation: load-3 3s normal forwards;
    box-shadow: 0 10px 40px -10px #fff;
    border-radius: 100px;
    background: ORANGERED;
     height: 10px;
    width: 0;
  }
  @keyframes load {
    0% { width: 0; }
    100% { width: 10%; }
  }
  @keyframes load-1 {
    0% { width: 0; }
    100% { width: 60%; }
  }
  @keyframes load-2 {
    0% { width: 0; }
    100% { width: 40%; }
  }
  @keyframes load-3 {
    0% { width: 0; }
    100% { width: 90%; }
  }
  .exchange-currency-left label,
.exchange-currency-right label {
    font-size: 16px;
    color: var(--theme-color);
    font-weight: 500;
    margin-bottom: 20px;
    display: block;
}
.exchange-wrap {
    background-color: var(--white);
    box-shadow: 0px 10px 40px var(--shadow-color);
    border-radius: 10px;
    padding: 70px;
	margin-top: 100px;
}
.exchange-currency {
    display: flex;
    width: 100%;
    align-items: center;
}
.exchange-currency .list {
    height: 300px;
    overflow-y: auto;
}
.exchange-box .currency {
    display: flex;
    position: relative;
}
.exchange-box .currency input {
    height: 40px;
    width: 100%;
    background-color: white;
    border: 1px solid bl;
    border-radius: 10px;
    padding: 20px;
    outline: none;
    text-align: right;
}
.exchange-box .currency .select-country {
    background-color: white;
    border: transparent;
    border-radius: 10px;
    border: 1px solid #262261;
    position: absolute;
    width: 95px;
    height: 100%;
    padding: 13px;
    left: 0;
    top: 0;
    font-size: 18px;
    font-weight: 500;
    color: #262261;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/*.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 150px;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 50px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
    box-shadow: 0 6px 12px rgb(0 0 0 / 18%);
}*/
.dropdown-menu>li>a {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
}
.btn-primary:hover {
    color: #fff !important;
    background-color: #262261 !important;
    border-color: #262261 !important;
}

.exchange-box .currency .select-country.open::after {
    transform: translateY(-50%) rotateX(180deg);
}
.exchange-currency-left {
    width: 100%;
  
}
.exchange-currency-middle {
   
	margin: 20px;
    text-align: center;
}
.exchange-currency-right {
    width: 100%;

}
.coin {
    background: #faf9fa;
    padding: 20px;
    border-radius: 20px;
    text-align: center;
}
  
.coin a{
    padding: 20px;
}

.submit-btn, .btn {
    z-index: 1;
    color: #fff;
    border: none;
    line-height: 1;
    background: none;
    cursor: pointer;
    font-size: 14px;
    overflow: hidden;
    font-weight: 500;
    text-align: center;
    position: relative;
    background: #262261;
    letter-spacing: 1px;
    border-radius: 50px;
    display: inline-block;
   /*padding: 18px 30px 17px;*/
   
}
form {
  position: relative;
  width: 100%; }
  
  form input {
    display: block;  
    width: 100%;
   /* border: 3px solid;*/
    outline: 0;
    background: #FFF;
    font-size: 25px;
    padding: 5px 4px;
    margin-bottom: 20px;
    border-radius: 20px;
  }
  
  form button {
    display: block;
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    border: 0;
    outline: 0;
    color: #FFF;
    background: #262261;
    font-family: 'VT323', monospace;
    font-size: 25px;
    text-transform: uppercase;
    padding: 0.08em 0.8em;
    cursor: pointer;
    border-radius: 20px;
  }

        </style>


      <div class="verification section-padding mb-5 " style="padding:40px 0px">
            <div class="container h-100">
              <div class="col-12">
                <div class="refer">
                  <div class="row">
                  <div class="col-12 col-lg-8">
                      <h4>Price Trends</h4>
                    <div id="chart">
                    </div>
                  </div>

              
                  <div class="col-12 col-lg-4">
                    <h4>Instant Swap</h4>
                      <div class="swap">


                                    <?php $attributes=array('class'=>'form-horizontal','id'=>'swap');
                    $action="save_swap";
                    echo form_open_multipart($action,$attributes); ?>
                        <div class="blurred-box">
                            <div style=" margin-top: 20px;">
                              
                              </div>
                              <div class="exchange-box" style="    padding-bottom: 20px;">
                                <p style="text-align:left;font-size: 10px;padding-left: 10px;">
                                    I have  &nbsp; &nbsp;
                                    <span style="font-size: 10px;">
                                        Available Balance : <span id="select_balance">0.00</span>
                                    </span>
                                </p>
                                <!-- <div class="d-flex">
                                    <div ><p style="text-align: left;font-size: 10px;">I have </p></div>
                                    <div ><p style="text-align: right;font-size: 10px;">Available Balance : <span>0.000USD</span></p></div></div> -->


                                    <!--  <form action="test.php" Method="POST"> -->


  
                            <div class="exchange-currency-left">
                                <!-- <label style="color: orangered;text-align: center;font-weight: 700;">BNB COIN</label> -->


                                    <div class="currency">
                                        <input type="hidden" name="coin_one" id="coin_one"/>
                                        <input type="hidden" name="coin_two" id="coin_two"/>
                                        <input type="text" id="amounttwo" min="0"  style="font-size: 15px;"  name="amounttwo"  onkeyup="myFunctiontwo()">


                                        <a   style="height: 72%!important;"    class="btn btn-primary select-country select  dropdown-toggle" id="currency-one" data-toggle="dropdown" href="#"><p  style="margin-top: 12px;"  id="select_coin"></p> <span class="caret"></span></a>
                                        <ul id="myid" class="dropdown-menu">

                                           <?php foreach($currency as $currency_name) {?>

                                            <li id="<?php echo $currency_name->currency_symbol.'_'.$currency_name->id;?>"   class="active" ><a href="javascript:void(0);">
                                    <img src="<?php echo $currency_name->image;?>" style="width: 20px;"> <?php echo $currency_name->currency_symbol;?> </a>
                                            </li>
 
                                        <?php  } ?>
                                       
                                         </ul>
                                        <!-- <select class="select-country select" id="currency-one">
                                            <option value="BNB" data-image="images/bnb.png" selected >BNB </option>
                                            <option value="BUSD"> <span><img src="images/bnb.png"></span>BUSD</option>
    
                                        </select> -->

                                    </div>

                                      <label style="color:red;"  id="amounttwo-error" class="error" for="amounttwo"></label>
                                      
                                </div>

                             </div>

                           <div class="percent" style="padding-bottom: 30px;">

                               <ul class="d-flex" style="justify-content: center;">
                               <li><a href="javascript:void(0);" onclick="change_buytrade('buy','25','<?php echo $currency_name->id;?>');">25%</a></li>
                                      <li><a href="javascript:void(0);" onclick="change_buytrade('buy','50','<?php echo $currency_name->id;?>');">50%</a></li>
                                      <li><a href="javascript:void(0);" onclick="change_buytrade('buy','75','<?php echo $currency_name->id;?>');">75%</a></li>
                                      <li><a href="javascript:void(0);" onclick="change_buytrade('buy','100','<?php echo $currency_name->id;?>');">100%</a></li>
                               </ul>

                         <!--         <ul class="buy_sell_percent d-flex flex-fill">
                                      <li><a href="javascript:void(0);" onclick="change_buytrade('buy','25','limit');">25%</a></li>
                                      <li><a href="javascript:void(0);" onclick="change_buytrade('buy','50','limit');">50%</a></li>
                                      <li><a href="javascript:void(0);" onclick="change_buytrade('buy','75','limit');">75%</a></li>
                                      <li><a href="javascript:void(0);" onclick="change_buytrade('buy','100','limit');">100%</a></li>
                                    </ul>
 -->

                           </div>
                           <p style="text-align:left;font-size: 10px;padding-left: 10px;">
                            I Want </p>
                            <div class="exchange-currency-right">
                                <!-- <label style="color: orangered;text-align: center;font-weight: 700;">ATS COIN</label> -->
                                <div class="exchange-box" style="padding-bottom: 20px;">
                                  <div class="currency">

                                      <input type="hidden" id="secondcoin" name="secondcoin">
                                      <input type="hidden" id="crypto_id" name="crypto_id">
                                      <input type="hidden" id="fees" name="fees">
                                      <input type="hidden" id="update_fees" name="update_fees">

                                    <input type="text" id="amountone" name="amountone"  style="font-size: 15px;"  onkeyup="myFunction()" readonly="">
                                   <!--  <a class="btn btn-primary select-country select  dropdown-toggle" id="currency-one" data-toggle="dropdown" href="#"> <p style="margin-top: 12px;" id="second_coin"></p><span class="caret"></span></a> -->

                                               <a   style="height: 72%!important;"    class="btn btn-primary select-country select  dropdown-toggle" id="currency-one" data-toggle="dropdown" href="#"><p  style="margin-top: 12px;"  id="second_coin"></p> <span class="caret"></span></a>
                                    <ul  id="second_id" style="padding: 15.5rem 0!important;"    class="dropdown-menu">

                                            <?php foreach($currency_list as $currency_name) {?>
                                            <li id="<?php echo $currency_name->currency_symbol.'_'.$currency_name->id;?>"class="active" ><a href="javascript:void(0);">
                                          <img src="<?php echo $currency_name->image;?>" style="width: 20px;"> <?php echo $currency_name->currency_symbol;?> </a>
                                            </li>
 
                                        <?php  } ?>
                                       
                                     </ul>
                                    <!-- <select class="select-country select" id="currency-one">
                                        <option value="BNB" data-image="images/bnb.png" selected >BNB </option>
                                        <option value="BUSD"> <span><img src="images/bnb.png"></span>BUSD</option>
    
                                    </select> -->
    
                                </div>
                                    <!-- <div style="text-align: center;"> <p>Available Balance : <span>0.000USD</span></p></div> -->
                                </div>
                            </div>

                            <div>
                                 <p style="text-align: center;">1 <span  id="currenct_first_coin"></span>= <span id="currenct_one"> </span> <span  id="currenct_second_coin"></span></p> 

          <!--                       <div class="coin">
                                    <a href="#"><img src="https://s2.coinmarketcap.com/static/img/coins/64x64/1.png" style="width: 20px;"> <span  id="currenct_first_coin"></span></a>
                                    
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                            <g id="Exchange" transform="translate(-878.709 -337.198)">
                                            <rect id="Rectangle_442" data-name="Rectangle 442" width="16" height="16" transform="translate(878.709 337.198)" fill="none"/>
                                            <g id="Group_309" data-name="Group 309" transform="translate(12 -130.859)">
                                                <path id="Path_754" data-name="Path 754" d="M836.185,397.881l-1.474-1.475h7.975a2.215,2.215,0,0,0,2.213-2.213h0V392.4h-1.061v1.793a1.151,1.151,0,0,1-1.151,1.151h-7.975l1.474-1.473.078-.078-.078-.078-.594-.594-.078-.078-.077.078-2.38,2.38h0a.532.532,0,0,0,0,.75h0l2.38,2.38.077.077.078-.077.594-.6.078-.077Z" transform="translate(35.809 83.547)" fill="#00274c" stroke="#00274c" stroke-width="0.2"/>
                                                <path id="Path_755" data-name="Path 755" d="M841.615,387.686l1.475,1.475h-7.976a2.215,2.215,0,0,0-2.213,2.213h0v1.793h1.062v-1.793a1.151,1.151,0,0,1,1.153-1.151h7.975l-1.475,1.473-.078.078.078.078.594.594.078.078.078-.078,2.38-2.38h0a.532.532,0,0,0,0-.751h0l-2.38-2.38-.078-.077-.078.077-.594.6-.078.077Z" transform="translate(35.809 83)" fill="#00274c" stroke="#00274c" stroke-width="0.2"/>
                                            </g>
                                            </g>
                                        </svg>
                                        <a href="#">  <img src="	https://s2.coinmarketcap.com/static/img/coins/64x64/1027.png" style="width: 20px;"> <span  id="currenct_second_coin"></span></a>
                                </div> -->
                            </div>
                            <div style="    margin-top: 20px;text-align: center;">
                               <!--   <a href="#" class="btn radius-50 th-bg " style="padding: 20px; height: 54px;">Connect Wallet</a> 
 -->   
                                </div>
                              
    
                        </div>

                    <span id="min_valid" style="color:red;"> </span>

                          <input type="submit" name="submit"  class="btn radius-50 th-bg sakthi" style="padding: 0px !important; height: 34px; background-color:#673bb7;margin-left: 90px!important;" id="submit" value="Swap Now"/>
                                 <?php echo form_close(); ?>
                      </div>

                  </div>
   

                   
                 
                </div>
                </div>
              </div>

            </div>
        </div>

        <div class="footer dashboard">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-12 col-lg-4"></div>
                    <div class="col-sm-4 col-12 col-lg-4">
                        <div class="copyright">
                            <p>© Copyright
                                <script>
                                    var CurrentYear = new Date().getFullYear()
                                    document.write(CurrentYear)
                                </script> <a href="#">Bidex</a> I All
                                Rights Reserved
                            </p>
                        </div>
                        <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 col-12 col-lg-4">
                        <!-- <div class="footer-social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                            </ul>
                        </div> -->
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <script src="<?php echo base_url(); ?>assets/js/global.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.6.9/apexcharts.min.js"></script>
    <script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
    <script>
$(document).ready(function() {
   var type=$("#myid li.active").attr('id');
   var second=$("#second_id li.active").attr('id');
   ans = type.split ("_");
   sec = second.split("_");
   $('#secondcoin').val(sec[0]);
   $('#coin_one').val(ans[0]);
   $('#coin_two').val(sec[0]);
     var crypto = ans[1];
  $('#crypto_id').val(crypto);
   // alert(ans[0]);
   // alert(sec[0]);
     $.getJSON('https://min-api.cryptocompare.com/data/pricemulti?fsyms='+ans[0]+'&tsyms='+sec[0], function(data) {
$.each(data, function (cur, rate) {
obj = Object.entries(rate);
current = obj[0][1];

// alert(current);


      document.getElementById("currenct_first_coin").innerHTML = ans[0]; 
      document.getElementById("currenct_second_coin").innerHTML = sec[0];   
      document.getElementById("currenct_one").innerHTML = current;  

// alert(orginal_amount);
var update = orginal_amount * current;
$('#amountone').val(update.toFixed(8));
// alert(current);

// console.log(current);
 $('#current_price').val(current);
// setFun();
 //   if(price!=''&&current!=''){
 //   var final =price/current;
 //   //var final ='1';
 //   $('#cryptoprice').val(final.toFixed(8));
 // }


//fees_s = (/e/.test(val)) ? val.noExponents() : val;

//online_price = parseFloat(final_amount) * parseFloat(val);

            
          });
        }); 








   document.getElementById("select_coin").innerHTML = ans[0];
      // document.getElementById("select_coins").innerHTML = ans[0];    
   document.getElementById("second_coin").innerHTML = sec[0]; 
      // document.getElementById("second_coins").innerHTML = sec[0];  
      var crypto = ans[1];

   var wallet='<?=$wallets?>';
   var test = JSON.parse(wallet);
   var keys = Object.keys(test);
   if(jQuery.inArray(crypto, keys) != -1) {
   var balance=test[crypto];
  document.getElementById("select_balance").innerHTML = balance;

  // alert(balance);
  // document.getElementById("select_balance").innerHTML = balance;

  // if(user_amount < balance){
  //    document.getElementById("min_valid").innerHTML = " "; 
  //  $( "#submit" ).prop( "disabled", false );
  // }else{
  //  $( "#submit" ).prop( "disabled", true );
  // document.getElementById("min_valid").innerHTML = "Your wallet balance is low"; 

  // }
        
      } 


});

function myFunction() {

   // var test = $('#amount-one').val(0);
    $('#amounttwo').val(0);
    // alert(test);
    var type=$("#myid li.active").attr('id');
    // alert(type);
    var second=$("#second_id li.active").attr('id');
    var ans = type.split ("_");
    var sec = second.split("_");

$.getJSON('https://min-api.cryptocompare.com/data/pricemulti?fsyms='+ans[0]+'&tsyms='+sec[0], function(data) {
$.each(data, function (cur, rate) {
obj = Object.entries(rate);
current = obj[0][1];
document.getElementById("currenct_one").innerHTML = current;  
// alert(current);
$('#amountone').val(current);

// alert(current);

// console.log(current);
//  $('#current_price').val(current);
// // setFun();
//    if(price!=''&&current!=''){
//    var final =price/current;
//    //var final ='1';
//    $('#cryptoprice').val(final.toFixed(8));
//  }


//fees_s = (/e/.test(val)) ? val.noExponents() : val;

//online_price = parseFloat(final_amount) * parseFloat(val);

            
          });
        }); 

    }
    function myFunctiontwo() {
  //  var test = $('#amount-two').val();

    var amount = $('#amounttwo').val();

    var type=$("#myid li.active").attr('id');

    // alert(type);
    //var second=$("#second_id li.active").attr('id');
     var base_url = "<?php echo base_url(); ?>";
    // alert(second);
     var ans = type.split ("_");
        var c_two=$('#coin_two').val();
       var c_one=$('#coin_one').val();



                    $.ajax({
                    url: base_url+"get_currency_fees",
                    type: "POST",
                    data: "currency_id="+c_one,
                    success: function(data) {
                        var res = jQuery.parseJSON(data);
                        var fees= $('#fees').val(res.maker_fee);

                        // console.log(res.maker_fee);
                        // if(currency_id==5){
                        //     var sym = '&inr;';
                        // }
                        // $('#minimum_deposit').html(res.minimum_deposit+' '+sym);
                    }
                });
          var maker_fee=$('#fees').val();
          // alert(amount);
          // alert(maker_fee);
          var fees = (maker_fee/100);
          var swap_fees = fees * amount; 

           // alert(swap_fees);
         $('#update_fees').val(swap_fees);
     var orginal_amount = amount - swap_fees;   

     // alert(orginal_amount);


     var second = $('#secondcoin').val();
     var sec = second.split("_");
  

     // alert(c_one);
     // alert(c_two);

      document.getElementById("currenct_first_coin").innerHTML = c_one; 
      document.getElementById("currenct_second_coin").innerHTML = c_two;   
      // alert(ans[0]);
      // alert(second);
      // alert(ans[0]);
       // alert(c_one);



  $.getJSON('https://min-api.cryptocompare.com/data/pricemulti?fsyms='+c_one+'&tsyms='+c_two, function(data) {
$.each(data, function (cur, rate) {
obj = Object.entries(rate);
current = obj[0][1];

document.getElementById("currenct_one").innerHTML = current;  


// alert(orginal_amount);
var update = orginal_amount * current;
$('#amountone').val(update.toFixed(8));
// alert(current);

// console.log(current);
 $('#current_price').val(current);
// setFun();
 //   if(price!=''&&current!=''){
 //   var final =price/current;
 //   //var final ='1';
 //   $('#cryptoprice').val(final.toFixed(8));
 // }


//fees_s = (/e/.test(val)) ? val.noExponents() : val;

//online_price = parseFloat(final_amount) * parseFloat(val);

            
          });
        }); 

}
$("#myid li").click(function() {
    $('#amountone').val(0);
    $('#amounttwo').val(0);
  var coin=$(this).attr('id');
  // var second=$("#second_id li").attr('id');
  var second=$("#coin_two").val();
  var ans = coin.split ("_");
  var crypto = ans[1];
  $('#crypto_id').val(crypto);
  var sec = second.split("_");
  $('#coin_one').val(ans[0]);
  $('#coin_two').val(sec[0]);

  // alert(ans[0]);
  // alert(sec[0]);
      document.getElementById("currenct_first_coin").innerHTML = ans[0]; 
      document.getElementById("currenct_second_coin").innerHTML = sec[0]; 
      document.getElementById("select_coin").innerHTML = ans[0];  


// alert(ans[0]);
// alert(sec[0]);


$.getJSON('https://min-api.cryptocompare.com/data/pricemulti?fsyms='+ans[0]+'&tsyms='+sec[0], function(data) {
$.each(data, function (cur, rate) {
obj = Object.entries(rate);
current = obj[0][1];
document.getElementById("currenct_one").innerHTML = current;  
//console.log(current);
 // $('#current_price').val(current);
// setFun();
 //   if(price!=''&&current!=''){
 //   var final =price/current;
 //   //var final ='1';
 //   $('#cryptoprice').val(final.toFixed(8));
 // }
   var wallet='<?=$wallets?>';
   var test = JSON.parse(wallet);
   var keys = Object.keys(test);
   if(jQuery.inArray(crypto, keys) != -1) {
  var balance=test[crypto];
  document.getElementById("select_balance").innerHTML = balance;

  // if(user_amount < balance){
  //    document.getElementById("min_valid").innerHTML = " "; 
  //  $( "#submit" ).prop( "disabled", false );
  // }else{
  //  $( "#submit" ).prop( "disabled", true );
  // document.getElementById("min_valid").innerHTML = "Your wallet balance is low"; 

  // }
        
      }
      // alert(ans[0]);

      // alert(sec[0]);

           if(ans[0] == sec[0]){
           $("#submit").prop( "disabled", true );
           document.getElementById("min_valid").innerHTML = "From and To both are same"; 

             }else{
         $("#submit").prop( "disabled", false );
         document.getElementById("min_valid").innerHTML = ""; 

      }


//fees_s = (/e/.test(val)) ? val.noExponents() : val;

//online_price = parseFloat(final_amount) * parseFloat(val);

            
          });
        });




 // alert(type);

 // filterOffer(type);

});

$("#second_id li").click(function() {

  $('#amountone').val(0);
  $('#amounttwo').val(0);

 var test=$(this).attr('id');
 var ans = test.split ("_");

 // alert(ans[0]);

var c_one=$('#coin_one').val();

// alert(c_one);
var c_two=$('#coin_two').val();
// alert(c_one);
// alert(c_two);
// alert(ans[0]);
// alert(c_one);
  document.getElementById("currenct_first_coin").innerHTML = ans[0]; 
  document.getElementById("currenct_second_coin").innerHTML = c_one; 

$.getJSON('https://min-api.cryptocompare.com/data/pricemulti?fsyms='+ans[0]+'&tsyms='+c_one, function(data) {
$.each(data, function (cur, rate) {
obj = Object.entries(rate);
current = obj[0][1];
// alert(current);

document.getElementById("currenct_one").innerHTML = current;  
// console.log(current);
 // $('#current_price').val(current);
// setFun();
 //   if(price!=''&&current!=''){
 //   var final =price/current;
 //   //var final ='1';
 //   $('#cryptoprice').val(final.toFixed(8));
 // }


//fees_s = (/e/.test(val)) ? val.noExponents() : val;

//online_price = parseFloat(final_amount) * parseFloat(val);

            
          });
        }); 

 $('#secondcoin').val(ans[0]);
 $('#coin_two').val(ans[0]);

            if(ans[0] == c_one){

           $("#submit").prop( "disabled", true );
           document.getElementById("min_valid").innerHTML = "From and To both are same"; 

             }else{
         $("#submit").prop( "disabled", false );
         document.getElementById("min_valid").innerHTML = ""; 

      }

     // document.getElementById("currenct_first_coin").innerHTML = ans[0]; 
    // document.getElementById("currenct_second_coin").innerHTML = ans[0];  
     document.getElementById("second_coin").innerHTML = ans[0];  

});

//       (function() {
//   var copyButton = document.querySelector('.copy button');
//   var copyInput = document.querySelector('.copy input');
//   copyButton.addEventListener('click', function(e) {
//     e.preventDefault();
//     var text = copyInput.select();
//     document.execCommand('copy');
//   });

//   copyInput.addEventListener('click', function() {
//     this.select();
//   });
// })();

  function change_buytrade(orderrtype,percrens,order)
  { 

    // alert(orderrtype);
    // alert(percrens);
    // alert(order);

    // alert('test');
   var crypto_id = $('#crypto_id').val();

   // alert(crypto_id);


   var wallet='<?=$wallets?>';
   var test = JSON.parse(wallet);
   var keys = Object.keys(test);
   if(jQuery.inArray(crypto_id, keys) != -1) {
   var balance=test[crypto_id];
   var total = ((percrens/100)*balance);

   $('#amounttwo').val(total);
   var coin_one = $('#coin_one').val();
   var coin_two = $('#coin_two').val();

$.getJSON('https://min-api.cryptocompare.com/data/pricemulti?fsyms='+coin_one+'&tsyms='+coin_two, function(data) {
$.each(data, function (cur, rate) {
obj = Object.entries(rate);
current = obj[0][1];
// alert(total);
// alert(current);
var update =  total * current;
$('#amountone').val(update.toFixed(8));
document.getElementById("currenct_one").innerHTML = currenct; 

// alert(current);

            
          });
        });
     
      }
   
  }
    </script>
<script src='https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js'></script>

<script type="text/javascript">
         
         $('#swap').validate({
            
        rules: {
            amounttwo: {
             required: true

            }
                     
           
        },
        messages: {
            amounttwo: {
                required: "Please Enter Amount"
            }     
                
        }
    });
 </script>
    <?php 
   $one=date('Y-m-d');
    $two=date('Y-m-d', strtotime('-1 days'));
    $three=date('Y-m-d', strtotime('-2 days'));
    $four=date('Y-m-d', strtotime('-3 days'));
    $five=date('Y-m-d', strtotime('-4 days'));
    $six=date('Y-m-d', strtotime('-5 days'));
    $seven=date('Y-m-d', strtotime('-6 days'));
    ?>
  <script>
        var one ="<?php echo $one;  ?>";
        var two ="<?php echo $two;  ?>";
        var three ="<?php echo $three;  ?>";
        var four ="<?php echo $four;   ?>";
        var five = "<?php echo $five;?>";
        var six = "<?php echo $six;?>";
        var seven= "<?php echo $seven;?>";
        var val_one= "<?php echo count($day_one);?>";
        var val_two= "<?php echo count($day_two);?>";
        var val_three= "<?php echo count($day_three);?>";
        var val_four= "<?php echo count($day_four);?>";
        var val_five="<?php echo count($day_five);?>";
        var val_six="<?php echo count($day_six);?>";
        var val_seven="<?php echo count($day_seven);?>";
         
         var options = {
          series: [{
            name: "Swap Count",
            data: [val_one, val_two, val_three, val_four, val_five, val_six, val_seven]
        }],
          chart: {
          height: 350,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'straight'
        },
        title: {
          text: 'Product Trends',
          align: 'left'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: [one, two, three, four, five, six,seven],
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    
    </script>
 <script type="text/javascript">
$("#amounttwo").keypress(function(event) {
if ( event.which == 45 || event.which == 189 ) {
event.preventDefault();
}
});
    </script>
       <script src="<?php echo base_url();?>assets/front/js/jquery.growl.js"></script>
    <?php
    $error      = $this->session->flashdata('error');
    $success    = $this->session->flashdata('success');
    $user_id    = $this->session->userdata('user_id');
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $get_os     = $_SERVER['HTTP_USER_AGENT'];
?>

<script>
  var user_id='<?php echo $user_id;?>';
  $(document).ready(function() {
    var success = "<?php echo $this->session->flashdata('success')?>";
    var error = "<?php echo $this->session->flashdata('error')?>";
  
        if(success!=''){

          $.growl.notice({title: "Bidex", message: success });
// $.growl.notice({title: "SMdex", message: success });
//alert(success);
}
if(error!=''){
$.growl.error({title: "Bidex", message: error });
}
});
</script>

</body>


</html>