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
            <!-- <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="page-title-content">
                            <p>Welcome Back,
                                <span> <?php echo $users->bidex_fname;?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <div class="content-body">
            <div class="container">
                <div class="row">
                    
                    <div class="col-xl-1 col-lg-1 col-md-1"></div>
                    
                    <div class=" col-12 col-xl-1 col-lg-1 col-md-1">
                        <div class="card acc_balance">
                            <!-- <div class="card-header">
                                <h4 class="card-title">Assets</h4>
                            </div> -->



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
                                                <tr>
                                                    <td>#<?php echo $deposit->transaction_id;?></td>
                                                    <td><?php echo $deposit->datetime;?>
                                                    </td>
                                                    <td><?php echo $deposit->type;?></td>
                                                    <td><?php echo $deposit->amount;?> <?php echo strtoupper(getcryptocurrency($deposit->currency_id));?></td>
                                                    <td><?php echo $deposit->status;?></td>
                                                    <td><?php echo $deposit->transfer_amount;?> <?php echo strtoupper(getcryptocurrency($deposit->currency_id));?></td>
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