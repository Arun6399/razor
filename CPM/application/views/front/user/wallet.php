<?php 
$this->load->view('front/common/header');
?>

					<div class=" cpm_mdl_cnt">
						<div class="container animated" data-animation="fadeInRightShorter"
						data-animation-delay="1s">
	
	
						  <div class="cpm_rep_hd_out">
						  <div class="cpm_rep_hd">
							<div class="cpm_rep_hd_li cpm_rep_hd_li_act" data-hdrname="wallet">Wallet</div>
						  </div>
						  </div>
	
	
	
						  <div class="cpm_rep_body_set cpm_rep_body_act" data-bdyname="wallet">
						  
						  <div class="cpm_rep_bdy" >
							  <div class="table-responsive ">
							  <div class="cpm_repo_tbl_out ">
	
								<table class="table cpm_repo_tbl">
									<thead >
									  <tr>
										<th scope="col">Coin</th>
										<th scope="col">Name</th>
										<th scope="col">Balance</th>
										<th scope="col">Value in USD</th>
										<!-- <th scope="col">Transaction Id</th> -->
										<th scope="col">Action</th>
									   
									  </tr>
									</thead>
									<tbody>
										<?php
                                    if(count($dig_currency) >0)
                                    {

                                       foreach ($dig_currency as $digital) 
                                     {
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
                                    $coin_price_val = to_decimal($wallet['Exchange AND Trading'][$digital->id], $format);
                                    $coin_price = $coin_price_val * $digital->online_usdprice;
                                    $user_id=$this->session->userdata('user_id');
                                    $userbalance = getBalance($user_id,$digital->id);
                                    $USDT_Balance = $userbalance * $digital->online_usdprice;

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

                                                }
                                        ?>



									  <tr>
										<td><div class="cpm_repo_tbl_coin"><img src="<?php echo $digital->image;?>" class="cpm_repo_tbl_coin_i"><?php echo $digital->currency_symbol;?></div></td>
										<td><?php echo $digital->currency_name;?></td>
                                        <td><?php echo  ($userbalance > 0  ) ? $userbalance : "0"; ?> <?php echo $digital->currency_symbol;?></td>
                                        <td>$ <?php echo $USDT_Balance; ?></td>
										<!-- <td>DJFH8DFJHD89G9<i class="fal fa-clipboard cpm_repo_tbl_copy"></i></td> -->
										<td>
												<a  href="<?php echo base_url(); ?>deposit/<?=$digital->currency_symbol;?>"><div class="cpm_repo_tbl_stat">Deposit</div></a>
												<a  href="<?php echo base_url(); ?>withdraw/<?=$digital->currency_symbol;?>"><div class="cpm_repo_tbl_stat cpm_repo_stat_danger">Withdraw</div></a>
												<a  href="<?php echo base_url(); ?>trade/<?=$traDepair;?>"><div class="cpm_repo_tbl_stat cpm_repo_stat_pending">Trade</div></a>


										</td>
									  </tr>
									  
									 <?php
                                                
                                            }      
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


<?php 
$this->load->view('front/common/footer');
?>