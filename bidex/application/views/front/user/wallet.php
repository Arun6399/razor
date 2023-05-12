<?php $this->load->view('front/common/headerlogin');
$user_id = $this->session->userdata('user_id');
$all_currency = $this->common_model->getTableData("currency",array("status"=>1))->result();
      if(count($all_currency))
      {
        $tot_balance = 0;
        foreach($all_currency as $cur)
        {
            $balance = getBalance($user_id,$cur->id);
            $usd_balance = $balance * $cur->online_usdprice;

            $tot_balance += $usd_balance;
        }
      }

?>
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
                    <!-- <div class="col-6">
                        <ul class="text-end breadcrumbs list-unstyle">
                            <li><a href="settings.html">Settings </a></li>
                            <li class="active"><a href="#">Security</a></li>
                        </ul>
                    </div> -->
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
                                    <li>
                                        <a href="account-overview.html" >
                                            <i class="mdi mdi-bullseye"></i>
                                            <span>Wallet</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="account-deposit.html" >
                                            <i class="mdi mdi-heart"></i>
                                            <span>Deposit</span>
                                        </a>
                                    </li>
                                    <li >
                                        <a href="account-withdraw.html" >
                                            <i class="mdi mdi-pentagon"></i>
                                            <span>Withdraw</span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-xl-1 col-lg-1 col-md-1"></div>
                    <div class="col-xl-10 col-lg-10 col-md-10">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Cryptos Dashboard</h4>
                            </div>
                            <div class="card-body">
                                <div class="transaction-table">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0 table-responsive-sm">
                                            <thead>
                                                <th>Asset Type</th>
                                                <th>Total Balance</th>
                                                <th>Asset Value</th>
                                                <th>Available Balance</th>
                                                <!-- <th>Asset Type</th> -->
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </thead>
                                            <tbody>
                                                <?php
                                    if(count($dig_currency) >0)
                                    {
                                      $i=0;

                                       foreach ($dig_currency as $key => $digital) 
                                     {
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
                                     $crypto_address = getAddress($user_id,$digital->id);

                                    $USDT_Balance = abs($userbalance * $digital->online_usdprice);
                                    $available_balance=abs($userbalance-$order_bal);
                                    
                                    

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
                                                <tr>

                                                    <td>
                                                        <img src="<?php echo $digital->image;?>" alt="" class="table-cryp" style="width: 20px;"> <?php echo $digital->currency_symbol;?>
                                                    </td>
                                                    <td>
                                                        <?php echo number_format($userbalance,8,'.',''); ?>
                                                    </td>
                                                    <td>$ <?php echo number_format($USDT_Balance,2,'.','')?></td>
                                                    <td><?php echo number_format($available_balance,8,'.',''); ?></td>
                                                    <td>
                                                        <a href="<?php echo base_url();?>deposit/<?php echo $digital->currency_symbol;?>"> <span class="badge bg-success p-2">Deposit</span></a>
                                                    </td>

                                                    <td>
                                                        <a href="<?php echo base_url();?>withdraw/<?php echo $digital->currency_symbol;?>"><span class="badge bg-danger p-2">Withdraw</span></a>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url();?>

exchange"><span class="badge bg-warning p-2">Trade</span></a>
                                                    </td>
                                                </tr>
                                                <!-- <tr>

                                                    <td>
                                                        <i class="cc XRP"></i> XRP
                                                    </td>
                                                    <td>
                                                        0.000000
                                                    </td>
                                                    <td>$ 0.00</td>
                                                    <td>0.000000</td>
                                                    <td>
                                                        <a href="account-deposit.html"> <span class="badge bg-success p-2">Deposit</span></a>
                                                    </td>

                                                    <td>
                                                        <a href="account-withdraw.html"><span class="badge bg-danger p-2">Withdraw</span></a>
                                                    </td>
                                                    <td>
                                                        <a href="exchange.html"><span class="badge bg-warning p-2">Trade</span></a>
                                                    </td>
                                                </tr> -->
                                                <!-- <tr>

                                                    <td>
                                                        <i class="cc LTC"></i> LTC
                                                    </td>
                                                    <td>
                                                        0.000000
                                                    </td>
                                                    <td>$ 0.00</td>
                                                    <td>0.000000</td>
                                                    <td>
                                                        <a href="account-deposit.html"> <span class="badge bg-success p-2">Deposit</span></a>
                                                    </td>

                                                    <td>
                                                        <a href="account-withdraw.html"><span class="badge bg-danger p-2">Withdraw</span></a>
                                                    </td>
                                                    <td>
                                                        <a href="exchange.html"><span class="badge bg-warning p-2">Trade</span></a>
                                                    </td>
                                                </tr> -->
                                                <!-- <tr>

                                                    <td>
                                                        <i class="cc ADA"></i> ADA
                                                    </td>
                                                    <td>
                                                        0.000000
                                                    </td>
                                                    <td>$ 0.00</td>
                                                    <td>0.000000</td>
                                                    <td>
                                                        <a href="account-deposit.html"> <span class="badge bg-success p-2">Deposit</span></a>
                                                    </td>

                                                    <td>
                                                        <a href="account-withdraw.html"><span class="badge bg-danger p-2">Withdraw</span></a>
                                                    </td>
                                                    <td>
                                                        <a href="exchange.html"><span class="badge bg-warning p-2">Trade</span></a>
                                                    </td>
                                                </tr> -->
                                                <!-- <tr>

                                                    <td>
                                                        <i class="cc ETH"></i> ETH
                                                    </td>
                                                    <td>
                                                        0.000000
                                                    </td>
                                                    <td>$ 0.00</td>
                                                    <td>0.000000</td>
                                                    <td>
                                                        <a href="account-deposit.html"> <span class="badge bg-success p-2">Deposit</span></a>
                                                    </td>

                                                    <td>
                                                        <a href="account-withdraw.html"><span class="badge bg-danger p-2">Withdraw</span></a>
                                                    </td>
                                                    <td>
                                                        <a href="exchange.html"><span class="badge bg-warning p-2">Trade</span></a>
                                                    </td>
                                                </tr> -->
                                                <!-- <tr>

                                                    <td>
                                                        <i class="cc DOGE"></i> DOGE
                                                    </td>
                                                    <td>
                                                        0.000000
                                                    </td>
                                                    <td>$ 0.00</td>
                                                    <td>0.000000</td>
                                                    <td>
                                                        <a href="account-deposit.html"> <span class="badge bg-success p-2">Deposit</span></a>
                                                    </td>

                                                    <td>
                                                        <a href="account-withdraw.html"><span class="badge bg-danger p-2">Withdraw</span></a>
                                                    </td>
                                                    <td>
                                                        <a href="exchange.html"><span class="badge bg-warning p-2">Trade</span></a>
                                                    </td>
                                                </tr> -->

                                            <?php }} ?>

                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class=" col-12 col-xl-1 col-lg-1 col-md-1">
                        <div class="card acc_balance">
                            <!-- <div class="card-header">
                                <h4 class="card-title">Assets</h4>
                            </div> -->


<div style="padding-top: 25px;"></div>
                              <div class="contentWrapper" >



                            </div>









                        </div>
                    </div>



                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Transactions History</h4>
                            </div>
                            <div class="card-body">
                                <div class="transaction-table">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0 table-responsive-sm">
                                            <thead>
                                                <tr>
                                                    <th>Transaction ID</th>
                                                    <th>Time</th>
                                                    <th>Type</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Transfer Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($deposit_history) && !empty($deposit_history)) {
                                              $a=0;
                                              foreach($deposit_history as $deposit) {
                                                  $a++;
                                                  if(empty($deposit->transaction_id))
                                                  {
                                                    $transaction_id = '-';
                                                  }
                                                  else
                                                  {
                                                    $transaction_id = $deposit->transaction_id;
                                                  }
                                                    if($deposit->status =='Completed')
                        {
                            $clr_class = 'green';
                        }
                        else
                        {
                            $clr_class = 'red';
                        } ?> 


                                 <?php  

                                 if($deposit->currency_id == '1'){
                                    $link="https://live.blockcypher.com/btc/address/".$deposit->crypto_address;

                                 } else if($deposit->currency_id =='2'){
                                    $link="https://etherscan.io/address/".$deposit->crypto_address;
                                 } else if($deposit->currency_id =='3'){
                                    $link="https://bscscan.com/address/".$deposit->crypto_address;
                                 } else if($deposit->currency_id =='4') {
                                    $link="https://tronscan.org/#/address/".$deposit->crypto_address;
                                 } else if($deposit->currency_id =='5') {
                                    $link="https://blockchair.com/dogecoin/address/".$deposit->crypto_address;
                                  
                                 } else if($deposit->currency_id =='6') {
                                    $link="https://xrpscan.com/address/".$deposit->crypto_address;
                                    
                                 } else if($deposit->currency_id =='8') {
                                    $link="https://etherscan.io/address/".$deposit->crypto_address;
                                    
                                 } else if($deposit->currency_id =='9') {
                                    $link='https://blockchair.com/bitcoin-cash';
                                    
                                 } else if($deposit->currency_id =='9') {
                                    $link="https://live.blockcypher.com/ltc/address/".$deposit->crypto_address;
                                    
                                 }  else  {
                                    $link="https://etherscan.io/address/".$deposit->crypto_address;
                                    
                                 }        


                                 ?>

                                                <tr>
                                                    <td>#<?php echo $deposit->transaction_id;?></td>
                                                    <td><?php echo $deposit->datetime;?>
                                                    </td>
                                                    <td><?php echo $deposit->type;?></td>
                                                    <td><?php echo $deposit->amount;?> <?php echo strtoupper(getcryptocurrency($deposit->currency_id));?></td>
                                                    <td><?php echo $deposit->status;?></td>
                                                    <td><?php echo $deposit->transfer_amount;?> <?php echo strtoupper(getcryptocurrency($deposit->currency_id));?> <br> <a href="<?php echo $link; ?>" target="_blank">View Status </a></td>
                                                </tr>
                                               
                                            <?php } } else { ?>
                                          <tr>
                                          <td colspan="8" style="text-align: center;">No Records Found</td>
                                          </tr>
                                          <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php $this->load->view('front/common/footerlogin');?>