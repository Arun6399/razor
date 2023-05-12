<!-- begin #content -->
<div id="content" class="content">
      <?php 
      $error = $this->session->flashdata('error');
      if($error != '') {
            echo '<div class="alert alert-danger">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$error.'</div>';
      }
      $success = $this->session->flashdata('success');
      if($success != '') {
            echo '<div class="alert alert-success">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&#10005;</button>'.$success.'</div>';
      } 
      ?>
      <!-- begin breadcrumb -->
      <ol class="breadcrumb pull-right">
            <li><a href="<?php echo admin_url();?>">Home</a></li>
            <li class="active">Coin Profit</li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header">Coin Profit <!--<small>header small text goes here...</small>--></h1>
      <p class="text-right m-b-10">
            <!--<a href="<?php echo admin_url().'pair/add';?>" class="btn btn-primary">Add New</a>-->
      </p>
      <!-- end page-header -->
      <!-- begin row -->
      <div class="row">
            <div class="col-md-12">
                  <div class="panel panel-inverse">
                        <div class="panel-heading">
                              <div class="panel-heading-btn">
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                              </div>
                              <h4 class="panel-title">Select Currency</h4>
                        </div>
                        <div class="panel-body">
                        <!--h4>Select Fiat Currency</h4>
                              <fieldset>
                                  <?php if(!empty($fi_cu)) { 
                                    $i = 1;
                                    foreach($fi_cu as $fi) { 
                                          $type="fiat";
                                          ?>
                                          <a href="<?php echo admin_url() . 'admin/coin_profit_report/'.$fi->id.'/fiat'; ?>"><font class="<?php if($currency_symbol==$fi->currency_symbol){ echo 'btn btn-small btn-danger'; } else{ echo 'btn btn-small btn-success'; } ?>" style="margin-bottom: 10px;"><?php echo $fi->currency_symbol; ?></font></a>
                                  <?php $i++; } } ?>
                              </fieldset-->
                              <h4>Select Currency</h4>
                              <fieldset>
                                    <?php if(!empty($cu)) { 
                                          foreach($cu as $cus) { 
                                                $type="crypto";
                                                ?>
                                                <a href="<?php echo admin_url() . 'admin/coin_profit_report/'.$cus->id.'/crypto'; ?>"><font class="<?php if($currency_symbol==$cus->currency_symbol){ echo 'btn btn-small btn-danger'; } else{ echo 'btn btn-small btn-success'; } ?>" style="margin-bottom: 10px;"><?php echo $cus->currency_symbol; ?></font></a>
                                                <!--<font class="btn btn-small btn-success" id="<?php echo $cus->currency_symbol; ?>" id="<?php echo $cus->currency_symbol; ?>" style="margin-bottom: 10px;" onclick="myFunction('<?php echo $cus->id; ?>', '<?php echo $type; ?>')"> <?php echo $cus->currency_symbol; ?> </font>--> 
                                          <?php } } ?>
                                    </fieldset>
                              </div>
                          </div>
                      </div>
                  </div>

                  <div class="row">

                        <!-- begin col-12 -->
                        <div class="col-md-12">
                              <div class="panel panel-inverse" data-sortable-id="index-1">
                                    <div class="panel-heading">
                                          <div class="panel-heading-btn">
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                          </div>
                                          <h4 class="panel-title">Profit Analytics</h4>
                                    </div>
                                    <div class="panel-body">
                                          <div id="interactive-chart" class="height-sm"></div>
                                    </div>
                              </div>
                        </div>
                        <!-- end col-12 -->

                        <div class="col-md-12" style="display: none;">
                              <div class="panel panel-inverse">
                                    <div class="panel-heading">
                                          <div class="panel-heading-btn">
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                          </div>
                                          <h4 class="panel-title">Daily Coin Profit - <v id="daily_currency_name"><?php echo $currency_symbol; ?></v></h4>
                                    </div>
                                    <div class="panel-body">
                                          <div class="table-responsive">
                                                <table class="table table-striped table-bordered" id="dailydata">
                                                      <thead>
                                                            <tr>
                                                                  <th class="text-center">S.No</th>
                                                                  <th class="text-center">Date</th>
                                                                  <th class="text-center">Total</th>
                                                                  <!--<th class="text-center">Total Bonus</th>-->
                                                            </tr>
                                                      </thead>
                                                      <tbody style="text-align: center;" class="daily_profit">
                                                            <?php
                                                            if ($daily->num_rows() > 0) {
                                                                  $i = 1;
                                                                  foreach(array_reverse($daily->result()) as $result) {
                                                                        $bonus=$result->total+$result->bonus;
                                                                        echo '<tr>';
                                                                        echo '<td>' . $i . '</td>';
                                                                        echo '<td>' . $result->dateval . '</td>';
                                                                        echo '<td>' . $bonus . '</td>';
                                                            //echo '<td>' . $result->bonus . '</td>';
                                                                        echo '</tr>';
                                                                        $i++;
                                                                  }                             
                                                            } else {
                                                                  echo '<tr><td colspan="3" class="text-center">No Daily Coin Profit!</td></tr>';
                                                            }
                                                            ?>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <div class="panel panel-inverse">
                                    <div class="panel-heading">
                                          <div class="panel-heading-btn">
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                          </div>
                                          <h4 class="panel-title">Weekly Coin Profit - <v id="weekly_currency_name"><?php echo $currency_symbol; ?></v></h4>
                                    </div>

                                    <div class="panel-body">
                                          <div class="table-responsive">
                                                <table class="table table-striped table-bordered" id="weeklydata">
                                                      <thead>
                                                            <tr>
                                                                  <th class="text-center">S.No</th>
                                                                  <th class="text-center">Week Number</th>
                                                                  <th class="text-center">Year</th>
                                                                  <th class="text-center">Total</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody style="text-align: center;" class="weekly_profit">
                                                            <?php
                                                      //echo "<pre>";print_r($weekly->result());
                                                            if ($weekly->num_rows() > 0) {
                                                                  $i = 1;

                                                                  if($currency_symbol == 'ETH'){
                                                                        $btc="";

                                                                  }else{
                                                                        $btc="0.06200";
                                                                        $btc1="0.05966";
                                                                  }

                                                            $weekly_btc = array('0.210836','0.210836','0.210836','0.210836','0.210836','0.210836','0.12','0.158127','0.158127','0.105418','0.056','0.06','0.1','0.068','0.02778','0.07044','0.02348','0.01174','0.03522','0.00924','0.01447','0.03033','0.0048','0.03869','0.019345','0.07738','0.019345','0.060735','0.04859','0.01213','0.05112','0.05198','0.04750','0.04166','0.04081','0.01287','0.04148','0.067','0.0118');

                                                              $weekly_eth = array('0.590311','0.590311','0.590311','0.590311','0.590311','0.1212','0.2424','0.0606','0.0757','1.7649','0.74','0.3','0.18','0.19','0.1481','0.478','0.239','0.1195','0.1195','0.0563','0.1449','0.09425','0.05135','0.7463','0.186575','0.37315','0.186575','0.4254','0.2126','0.2125','0.5634','0.1472','0.2068','0.1307','0.1286','0.1737','0.1227','0.046','0.0139');      

                                                                  $weekno = 30;     
                                                                  $count=38;
                                                                  $j = 0;
                                                                  for ($i=1; $i <= $count; $i++) { 
                                                                        
                                                                        if((($weekno+$i) > 52)){
                                                                              $j++;
                                                                              if($currency_symbol == 'BTC'){
                                                                              echo '<tr><td>'.$i.'</td><td>'.($j).'</td><td>2023</td><td>' . $weekly_btc[$i] . '</td></tr>';
                                                                        } if($currency_symbol == 'ETH'){                                                                            
                                                                              echo '<tr><td>'.$i.'</td><td>'.($j).'</td><td>2023</td><td>' . $weekly_eth[$i] . '</td></tr>';
                                                                        }
                                                                        }else{
                                                                              if($currency_symbol == 'BTC'){
                                                                              echo '<tr><td>'.$i.'</td><td>'.($weekno+$i).'</td><td>2022</td><td>' . $weekly_btc[$i] . '</td></tr>';
                                                                        } if($currency_symbol == 'ETH'){ 

                                                                              echo '<tr><td>'.$i.'</td><td>'.($weekno+$i).'</td><td>2022</td><td>' . $weekly_eth[$i] . '</td></tr>';
                                                                        }
                                                                        }
                                                                                                      

                                                                  }     


                                                            } else {
                                                                  echo '<tr><td colspan="4" class="text-center">No Monthly Coin Profit!</td></tr>';
                                                            }
                                                            ?>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <div class="panel panel-inverse">
                                    <div class="panel-heading">
                                          <div class="panel-heading-btn">
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                          </div>
                                          <h4 class="panel-title">Monthly Coin Profit - <v id="monthly_currency_name"> <?php echo $currency_symbol; ?></v></h4>
                                    </div>
                                    <div class="panel-body">
                                          <div class="table-responsive">
                                                <table class="table table-striped table-bordered" id="monthlydata">
                                                      <thead>
                                                            <tr>
                                                                  <th class="text-center">S.No</th>
                                                                  <th class="text-center">Month</th>
                                                                  <th class="text-center">Year</th>
                                                                  <th class="text-center">Total</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody style="text-align: center;" class="monthly_profit">
                                                            <?php 
                                                      // echo "<pre>";print_r(array_reverse($monthly->result()));


                                          if($currency_symbol == 'ETH'){
                                            
                                                echo '<tr><td>1</td><td>June</td><td>2022</td><td> 0.63274883 </td></tr>';
                                                echo '<tr><td>2</td><td>July</td><td>2022</td><td> 1.4292 </td></tr>';
                                                echo '<tr><td>3</td><td>August</td><td>2022</td><td> 0.3999 </td></tr>';
                                                echo '<tr><td>4</td><td>September</td><td>2022</td><td> 2.6805 </td></tr>';
                                                echo '<tr><td>5</td><td>October</td><td>2022</td><td> 1.41 </td></tr>';
                                                echo '<tr><td>6</td><td>November</td><td>2022</td><td> 0.9560 </td></tr>';
                                                echo '<tr><td>7</td><td>December</td><td>2022</td><td> 0.4663 </td></tr>';
                                                echo '<tr><td>8</td><td>January</td><td>2023</td><td> 1.4926 </td></tr>';
                                                echo '<tr><td>9</td><td>February</td><td>2023</td><td> 1.561 </td></tr>';
                                                echo '<tr><td>10</td><td>March</td><td>2023</td><td> 0.6433 </td></tr>';
                                                echo '<tr><td>11</td><td>April</td><td>2023</td><td> 0.5726 </td></tr>';

                                          } else {
                                                echo '<tr><td>1</td><td>June</td><td>2022</td><td> 0.19061299 </td></tr>';
                                                echo '<tr><td>2</td><td>July</td><td>2022</td><td> 0.619195 </td></tr>';
                                                echo '<tr><td>3</td><td>August</td><td>2022</td><td> 0.681022 </td></tr>';
                                                echo '<tr><td>4</td><td>September</td><td>2022</td><td> 0.12273 </td></tr>';
                                                echo '<tr><td>5</td><td>October</td><td>2022</td><td> 0.284 </td></tr>';
                                                echo '<tr><td>6</td><td>November</td><td>2022</td><td> 0.14088 </td></tr>';
                                                echo '<tr><td>7</td><td>December</td><td>2022</td><td> 0.09406 </td></tr>';
                                                echo '<tr><td>8</td><td>January</td><td>2023</td><td> 0.15476 </td></tr>';
                                                echo '<tr><td>9</td><td>February</td><td>2023</td><td> 0.17257 </td></tr>';
                                                echo '<tr><td>10</td><td>March</td><td>2023</td><td> 0.14285 </td></tr>';
                                                echo '<tr><td>11</td><td>April</td><td>2023</td><td> 0.1318 </td></tr>';

                                          }
                                                            

                                                            // if ($monthly->num_rows() > 0) {
                                                            //       $i = 1;

                                                            //       foreach(array_reverse($monthly->result()) as $result2) {

                                                            //             if($result2->moname == 'June'){


                                                            //                   if($currency_symbol == 'BTC')
                                                            //                         $pamount = "0.19061299";
                                                            //                   else
                                                            //                         $pamount = "0.63274883";

                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>'.$pamount.'</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             } else if($result2->moname == 'July'){

                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "1.4292";
                                                            //                   else
                                                            //                         $amount = "0.619195";

                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             } else if($result2->moname == 'August'){

                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "0.3999";
                                                            //                   else
                                                            //                         $amount = "0.681022";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             } else if($result2->moname == 'September'){

                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "2.6805";
                                                            //                   else
                                                            //                         $amount = "0.12273";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             } else if($result2->moname == 'October'){

                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "1.41";
                                                            //                   else
                                                            //                         $amount = "0.284";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             } else if($result2->moname == 'November'){

                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "0.9560";
                                                            //                   else
                                                            //                         $amount = "0.14088";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             } else if($result2->moname == 'December'){

                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "0.4663";
                                                            //                   else
                                                            //                         $amount = "0.09406";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             }
                                                            //             else if($result2->moname == 'January'){

                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "1.4926";
                                                            //                   else
                                                            //                         $amount = "0.15476";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             }  
                                                            //             else if($result2->moname == 'February'){
                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "1.561";
                                                            //                   else
                                                            //                         $amount = "0.22455";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             }  
                                                            //             else if($result2->moname == 'March'){
                                                            //                   if($currency_symbol == 'ETH')
                                                            //                         $amount = "0.2068";
                                                            //                   else
                                                            //                         $amount = "0.04750";
                                                            //                   $bonus=$result2->total+$result2->bonus;
                                                            //                   echo '<tr>';
                                                            //                   echo '<td>' . $i . '</td>';
                                                            //                   echo '<td>' . $result2->moname . '</td>';
                                                            //                   echo '<td>' . $result2->yname . '</td>';
                                                            //                   echo '<td>' . $amount . '</td>';
                                                            //                   echo '</tr>';
                                                            //                   $i++;
                                                            //             }

                                                            //       }                             
                                                            // } else {
                                                            //       echo '<tr><td colspan="4" class="text-center">No Monthly Coin Profit!</td></tr>';
                                                            // }

                                                            ?>

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <div class="panel panel-inverse">
                                    <div class="panel-heading">
                                          <div class="panel-heading-btn">
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                                          </div>
                                          <h4 class="panel-title">Yearly Coin Profit - <v id="yearly_currency_name"><?php echo $currency_symbol; ?> </v></h4>
                                    </div>
                                    <div class="panel-body">
                                          <div class="table-responsive">
                                                <table class="table table-striped table-bordered" id="yearlydata">
                                                      <thead>
                                                            <tr>
                                                                  <th class="text-center">S.No</th>
                                                                  <th class="text-center">Year</th>
                                                                  <th class="text-center">Total</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody style="text-align: center;" class="yearly_profit">
                                                            <?php
                                                            if ($yearly->num_rows() > 0) {
                                                                  $i = 1;
                                                                  foreach(array_reverse($yearly->result()) as $result3) {


                                                                        if($currency_symbol == 'ETH'){
                                                                              // $btc="1.4926";
                                                                              if($result3->yname==2022)
                                                                              $btc="7.97464883";
                                                                              else
                                                                                 $btc="6.6218";  



                                                                        }else{

                                                                              if($result3->yname==2022)
                                                                              $btc="2.13249999";
                                                                              else
                                                                                 $btc="0.77115";    
                                                                        }



                                                                        $bonus=$result3->total+$result3->bonus;
                                                                        echo '<tr>';
                                                                        echo '<td>' . $i . '</td>';
                                                                        echo '<td>' . $result3->yname . '</td>';
                                                            // echo '<td> 0.19061299 </td>';
                                                                        echo '<td>' .  $btc . '</td>';
                                                                        echo '</tr>';
                                                                        $i++;
                                                                  }                             
                                                            } else {
                                                                  echo '<tr><td colspan="3" class="text-center">No Yearly Coin Profit!</td></tr>';
                                                            }
                                                            ?>
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
              </div>
              <!-- end #content -->
              <!-- ================== BEGIN BASE JS ================== -->
              <script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
              <script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
              <script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
              <script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>
              <script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script>
      <!--[if lt IE 9]>
            <script src="<?php echo admin_source();?>/crossbrowserjs/html5shiv.js"></script>
            <script src="<?php echo admin_source();?>/crossbrowserjs/respond.min.js"></script>
            <script src="<?php echo admin_source();?>/crossbrowserjs/excanvas.min.js"></script>
      <![endif]-->
      <script src="<?php echo admin_source();?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
      <script src="<?php echo admin_source();?>/plugins/jquery-cookie/jquery.cookie.js"></script>
      <!-- ================== END BASE JS ================== -->
      <!-- ================== BEGIN PAGE LEVEL JS ================== -->
      <script src="<?php echo admin_source();?>/plugins/gritter/js/jquery.gritter.js"></script>
      <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.min.js"></script>
      <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.time.min.js"></script>
      <script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.resize.min.js"></script>
      <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
      <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
      <script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>


      <script src="<?php echo admin_source();?>/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
      <script src="<?php echo admin_source();?>/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>

      <!-- <script src="<?php echo admin_source();?>js/dashboard.min.js"></script> -->

      <script src="<?php echo admin_source();?>/js/apps.min.js"></script>
      
      <!-- ================== END PAGE LEVEL JS ================== -->
      
      <script>
            var chartdata='<?php echo json_encode($chartdata);?>';
            $(document).ready(function() {
                  App.init();
                  Profit.init();
                  /*$('#dailydata').DataTable();
                  $('#weeklydata').DataTable();
                  $('#monthlydata').DataTable();
                  $('#yearlydata').DataTable();*/
            });
      </script>
      <script async
      src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
      <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-FDX8TJF8SG');
      </script>
      

      <script>
            var admin_url = '<?php echo admin_url(); ?>';


            $("font").click(function(){
                  $('font').removeClass('btn btn-small btn-danger');
                  $('font').addClass('btn btn-small btn-success');
                  var id = $(this).attr('id');
                  $("#"+id).removeClass('');
                  $("#"+id).addClass('btn btn-small btn-danger');
            });



/*function myFunction(currency,type) {
      $('#dailydata').DataTable().destroy();
      $('#monthlydata').DataTable().destroy();
      $('#yearlydata').DataTable().destroy();
      $('#weeklydata').DataTable().destroy();
        
    $.get(admin_url+"admin/coin_profit_report_ajax/"+currency+'/'+type,function(output){
        var output = JSON.parse(output);
        $('#dailydata').DataTable();
        $('#monthlydata').DataTable();
        $('#weeklydata').DataTable();
        $('#yearlydata').DataTable();
        $('.daily_profit').html(output.input_daily);
        $('.weekly_profit').html(output.input_weekly);
        $('.monthly_profit').html(output.input_monthly);
        $('.yearly_profit').html(output.input_yearly);


        $('#daily_currency_name').html(output.currency_name);
        $('#weekly_currency_name').html(output.currency_name);
        $('#monthly_currency_name').html(output.currency_name);
        $('#yearly_currency_name').html(output.currency_name);
    });
}*/

var handleInteractiveChart = function() {
      "use strict";

      function e(e, t, n) {
            $('<div id="tooltip" class="flot-tooltip">' + n + "</div>").css({
                  top: t - 45,
                  left: e - 55
            }).appendTo("body").fadeIn(200)
      }
      if ($("#interactive-chart").length !== 0) {
            var obj = JSON.parse(chartdata);
            var t=obj[1];
            var n=obj[2];
            var r=obj[0];
            $.plot($("#interactive-chart"), [{
                  data: t,
                  label: "Profit",
                  color: 'blue',
                  lines: {
                        show: true,
                        fill: false,
                        lineWidth: 2
                  },
                  points: {
                        show: true,
                        radius: 3,
                        fillColor: "#fff"
                  },
                  shadowSize: 0
            }], {
                  xaxis: {
                        ticks: r,
                        tickDecimals: 0,
                        tickColor: "#ddd"
                  },
            // yaxis: {
                // ticks: 10,
                // tickColor: "#ddd",
                // min: 0,
                // max: 10
            // },
            grid: {
                  hoverable: true,
                  clickable: true,
                  tickColor: "#ddd",
                  borderWidth: 1,
                  backgroundColor: "#fff",
                  borderColor: "#ddd"
            },
            legend: {
                  labelBoxBorderColor: "#ddd",
                  margin: 10,
                  noColumns: 1,
                  show: true
            }
        });
            var i = null;
            $("#interactive-chart").bind("plothover", function(t, n, r) {
                  $("#x").text(n.x.toFixed(2));
                  $("#y").text(n.y.toFixed(2));
                  if (r) {
                        if (i !== r.dataIndex) {
                              i = r.dataIndex;
                              $("#tooltip").remove();
                              var s = r.datapoint[1];
                              var o = r.series.label + " " + s;
                              e(r.pageX, r.pageY, o)
                        }
                  } else {
                        $("#tooltip").remove();
                        i = null
                  }
                  t.preventDefault()
            })
      }
};

var Profit = function() {
      "use strict";
      return {
            init: function() {
                  handleInteractiveChart();
            }
      }
}()
</script>