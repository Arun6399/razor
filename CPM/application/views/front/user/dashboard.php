<?php 
$this->load->view('front/common/header');
     if($users->verify_level2_status=="" || $users->verify_level2_status=="Pending" || $users->verify_level2_status=="Rejected")
       {
        $kyc_status = '⚠ KYC Not Verified';
        $background = 'rgb(236 74 74)';
       }
       else
       {
         $kyc_status = 'KYC Verified';
         $background = '#16b786';
       }
?>

					<div class=" cpm_mdl_cnt cpm_dashb_row">

						<div class="container">
							
							<div class="row ">
								<div class="col-md-12 col-lg-4">
									<div class="cpm_dash_pr">
										<div class="cpm_dash_pr_top">
											<a href="<?php echo base_url();?>kyc" class="cpm_dash_pr_top_btn" style="background: <?=$background;?>"><?=$kyc_status;?></a>
											<a href="<?php echo base_url();?>profile" class="cpm_dash_pr_lft_a"><i class="fal fa-pencil"></i></a>

											<?php if($users->profile_picture) { ?>
													<img src="<?php echo $users->profile_picture;?>" class="cpm_dash_pr_img">
													<?php } else { ?>
													<img src="<?php echo front_img();?>avt-dash.jpg" class="cpm_dash_pr_img">
											<?php } ?>

											<div class="cpm_dash_pr_rht">
											<div class="cpm_dash_pr_rht_h1"><?php echo $users->cpm_username;?></div>
											<div class="cpm_dash_pr_rht_h2"><?php echo getUserEmail($users->id);?></div>
		
											</div>
										</div>
										<div class="cpm_dash_pr_btm">
											
										<div class="cpm_dash_pr_btm_h1">My Assets
											<div class="cpm_dash_pr_btm_h2">Total : <span>$ <?=number_format($tot_balance);?></span></div>
										</div>
										<?php if(!empty($dig_currency)) {
											foreach ($dig_currency as $currency) {
											 $userbalance = getBalance($users->id,$currency->id);
											 $USD_Balance = $userbalance * $currency->online_usdprice;

										 ?>
										<div class="cpm_dash_pr_btm_li_scrl sbr">
						
											<div class="cpm_dash_pr_btm_li">
												<img src="<?php echo $currency->image;?>" class="cpm_dash_pr_btm_li_img">
												<div class="cpm_dash_pr_btm_li_l_out">
												<div class="cpm_dash_pr_btm_li_l_1"><?=$currency->currency_symbol;?><span>USD</span></div>
												<div class="cpm_dash_pr_btm_li_l_2"><?= number_format($USD_Balance,2);?> </div>
											</div>
												<div class="cpm_dash_pr_btm_li_r_out">
												<div class="cpm_dash_pr_btm_li_r_1"><?=$userbalance;?> <?=$currency->currency_symbol;?></div>
												
											</div>
												<div class="cpm_dash_pr_btm_ftr">
												<a href="<?php echo base_url();?>deposit/<?=$currency->currency_symbol;?>" class="cpm_dash_pr_btm_ftr_li"><i class="fal fa-wallet"></i>Deposit</a>
												<a href="<?php echo base_url();?>withdraw/<?=$currency->currency_symbol;?>" class="cpm_dash_pr_btm_ftr_li"><i class="fal fa-money-bill"></i>Withdraw</a>
												<a href="<?php echo base_url();?>exchange" class="cpm_dash_pr_btm_ftr_li"><i class="fal fa-chart-line"></i>Trade</a>
											</div>
												</div>
											</div>
											<?php } } ?>

											</div>
										</div>
									</div>


									<div class="col-md-12 col-lg-8">
										<div class="row">
											<div class="col-md-12">
												<div class="cpm_hd_text text-center">Transaction History</div>
											
												<div class="cpm_depo_tabl_s_li_inset">
													<div class="row">
														<div class="col-5 col-md-4">
														<!-- 	<div class="cpm_log_frm_s">
																<div class="cpm_log_frm_s_lbl">Rows</div>
																	
																	<select class="cpm_log_frm_s_input">
																		<option value="0">10</option>
																		<option value="0">20</option>
																		<option value="0">50</option>
																		<option value="0">100</option>
																	</select>
													
																</div> -->
														</div>
														<div class="col-7 col-md-8">
															<div class="cpm_log_frm_s">
																<div class="cpm_log_frm_s_lbl">Search</div>
																	<input type="text" id="trans_input" placeholder="Search with Type" class="cpm_log_frm_s_input"  onkeyup="myFunction('trans_input','transtable')" >
													<a href="#" class="cpm_log_frm_s_aa"><i class="fal fa-search"></i></a>
																</div>
														</div>
													</div>


													<div class="table-responsive ">
														<div class="cpm_repo_tbl_out cpm_dash_repo_tbl">
															
							 							 <div class="cpm_dash_tbll_scrll sbr">
														  <table class="table cpm_repo_tbl" >
															  <thead >
																<tr>
																  <th scope="col">Coins</th>
																  <th scope="col">Status / Type</th>
																  <th scope="col">Date</th>
																  <th scope="col">Volume</th>
																 
																 
																</tr>
															  </thead>
															  <tbody id="transtable">
															  	<?php
					                                        $style = (count($trans_history)>0)?"display:block":"display:none";
					                                        if(count($trans_history) > 0)
					                                        {
					                                            foreach($trans_history as $trans)
					                                            {
					                                                if($trans->type=="Deposit" && $trans->admin_status==1)
					                                                {
					                                                    $style="display:none";
					                                                    $txt_clr = 'text-green';
					                                                }
					                                                else
					                                                {
					                                                    $style="display:block";
					                                                    $txt_clr = 'text-red';
					                                                }
					                                                $cursym = $this->common_model->getTableData('currency',array('status'=>1, 'id'=>$trans->currency_id))->row();
					                                                $sym = strtoupper($cursym->currency_symbol);
					                                                if($trans->status=='Pending')
					                                                    $sts = 'cpm_dep_stat';
					                                                else
					                                                   $sts = 'cpm_dep_stat cpm_dep_sta_cancel'; 
					                                    		?>
																<tr>
																    <td><?php echo $sym;?></td>
											                        <td><?=$trans->status?> - <?=$trans->type?></td>
											                        <td><?php echo $trans->datetime;?></td>
											                        <td><?php echo number_format($trans->amount,8);?></td>
																</tr>
																<?php } 
               													 }?> 
																
															  </tbody>
															</table>
														</div>
														</div>
												
												</div>	







													
													<!-- <div class="cpm_depo_tabl_s cpm_dash_tbll">
													<div class=" cpm_dash_tbll_scrll sbr">
														
														<?php
					                                        $style = (count($trans_history)>0)?"display:block":"display:none";
					                                        if(count($trans_history) > 0)
					                                        {
					                                            foreach($trans_history as $trans)
					                                            {
					                                                if($trans->type=="Deposit" && $trans->admin_status==1)
					                                                {
					                                                    $style="display:none";
					                                                    $txt_clr = 'text-green';
					                                                }
					                                                else
					                                                {
					                                                    $style="display:block";
					                                                    $txt_clr = 'text-red';
					                                                }
					                                                $cursym = $this->common_model->getTableData('currency',array('status'=>1, 'id'=>$trans->currency_id))->row();
					                                                $sym = strtoupper($cursym->currency_symbol);
					                                                if($trans->status=='Pending')
					                                                    $sts = 'cpm_dep_stat';
					                                                else
					                                                   $sts = 'cpm_dep_stat cpm_dep_sta_cancel'; 
					                                    ?>	

														<div class="cpm_depo_tabl_s_li_out ">
															<div class="cpm_depo_tabl_s_li">
																<div class="cpm_ast_hd_set">
																	<img src="<?php echo front_img();?>aico-1.png" class="cpm_ast_ico"> 
																   <div class="cpm_ast_h1"><?php echo $sym;?></div>
																   <div class="cpm_ast_h2"><?=$cursym->currency_name;?></div></div>
															</div>
															
															
															<div class="cpm_depo_tabl_s_li"><div class="<?=$sts;?> "><?=$trans->status?> - <?=$trans->type?></div></div>
															


															<div class="cpm_depo_tabl_s_li"><?php echo date('d-m-y H:i A',$trans->datetime);?></div>
															<div class="cpm_depo_tabl_s_li"><?php echo number_format($trans->amount,8);?></div>
															
															</div><?php } } ?>
														</div>
												</div> -->
											</div>

											
											</div>
											<div class="col-md-12">
												<div class="cpm_hd_text text-center">Activities</div>
											
												<div class="cpm_depo_tabl_s_li_inset">
													<div class="row">
														<div class="col-5 col-md-4">
															<!-- <div class="cpm_log_frm_s">
																<div class="cpm_log_frm_s_lbl">Rows</div>
																	
																	<select class="cpm_log_frm_s_input">
																		<option value="0">10</option>
																		<option value="0">20</option>
																		<option value="0">50</option>
																		<option value="0">100</option>
																	</select>
													
																</div> -->
														</div>
														<div class="col-7 col-md-8">
															<div class="cpm_log_frm_s">
																<div class="cpm_log_frm_s_lbl">Search</div>
																	<input type="text" id="myInput" class="cpm_log_frm_s_input"  onkeyup="myFunction('myInput','myTable')" placeholder="Search with Date">
													<a href="#" class="cpm_log_frm_s_aa"><i class="fal fa-search"></i></a>
																</div>
														</div>
													</div>


													<div class="table-responsive ">
														<div class="cpm_repo_tbl_out cpm_dash_repo_tbl">
															
							 							 <div class="cpm_dash_tbll_scrll sbr">
														  <table class="table cpm_repo_tbl" >
															  <thead >
																<tr>
																  <th scope="col">No</th>
																  <th scope="col">Date / Time</th>
																  <th scope="col">IP Address</th>
																  <th scope="col">Browser</th>
																 
																 
																</tr>
															  </thead>
															  <tbody id="myTable">
															  	<?php
											                        if(count($login_history) >0) 
											                        {
											                            $i=1;
											                            foreach($login_history as $login)
											                            {
											                    ?>
																<tr>
																    <td><?php echo $i++;?></td>
											                        <td><?php echo date('d-m-y H:i A',$login->date);?></td>
											                        <td><?php echo substr($login->ip_address,0,14);?></td>
											                        <td><?php echo $login->browser_name;?></td>
																</tr>
																<?php } 
               													 }?> 
																
															  </tbody>
															</table>
														</div>
														</div>
												<!-- <div class="cpm_depo_tabl_s_pagi">
														<ul class="pagination"><li class="paginate_button page-item previous disabled" id="example_previous"><a href="#" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="example" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="3" tabindex="0" class="page-link">3</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="4" tabindex="0" class="page-link">4</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="5" tabindex="0" class="page-link">5</a></li><li class="paginate_button page-item disabled" id="example_ellipsis"><a href="#" aria-controls="example" data-dt-idx="6" tabindex="0" class="page-link">…</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="7" tabindex="0" class="page-link">11</a></li><li class="paginate_button page-item next" id="example_next"><a href="#" aria-controls="example" data-dt-idx="8" tabindex="0" class="page-link">Next</a></li></ul>
													</div> -->
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

function myFunction(input,id) {
var input, filter, table, tr, td, i, txtValue;
input = document.getElementById(input);
filter = input.value.toUpperCase();
table = document.getElementById(id);
tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
td = tr[i].getElementsByTagName("td")[1];



if (td) {
txtValue = td.textContent || td.innerText;
if (txtValue.toUpperCase().indexOf(filter) > -1) {
tr[i].style.display = "";
} else {
tr[i].style.display = "none";
}
}
}
}



</script>