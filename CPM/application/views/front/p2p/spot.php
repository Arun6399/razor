<?php 
$this->load->view('front/common/header');
?>
<div class=" cpm_mdl_cnt">
						<div class="container animated" data-animation="fadeInRightShorter" data-animation-delay="1s">

							<div class="row align-items-center">
								<div class="col-md-4">
									<div class="scpm_wt_content_h1">Buy Crypto More Easier</div>
									<div class="scpm_wt_content_h2">Buy Bitcoin and 99+ cryptocurrency with 50+ fiat currencies</div>
								</div>
								
								<div class="col-md-2">&nbsp;</div>
								<div class="col-md-6">
								<div class="scpm_wt_out">
								<div class="scpm_wt_tab_head_set">
									
									<div class="scpm_wt_tab_head scpm_wt_tab_head-1 scpm_wt_tab_head_act">Buy</div>
									<div class="scpm_wt_tab_head scpm_wt_tab_head-2">Sell</div></div>
									
										<div class="scpm_wt_tab_body_set scpm_wt_tab_body_set_act scmpwc-1">
									<div class="scpm_wt_inp_set lin_box">
										<div class="scpm_wt_inp_lbl">Spend</div>
									<input type="text" class="scpm_wt_inp_input">
										
										<?php if(!empty($currency)) { 
											foreach($currency as $cur) {
												if($cur->type=='fiat') {

										 ?>
										<div class="scpm_wt_coin_set">
										<img src="<?=$cur->image;?>" class="scpm_wt_coin_img">
										<div class="scpm_wt_coin_lbl"><?=$cur->currency_name;?></div>
										<i class="fal fa-chevron-right scpm_wt_coin_lbl_i"></i>
										</div> <?php } } } ?>
										
										<div class="scpm_wt_coin_total_set">
										<div class="scpm_wt_coin_total_set_in">
										<div class="scpm_wt_coin_total_set_center">
											<div class="scpm_wt_coin_total_set_top">Select Currency <i class="fal fa-times"></i></div>
										
											<div class="scpm_wt_coin_total_set_body">
											<input type="text" class="scpm_wt_coin_total_set_body_inp" placeholder="Search Coin Here">
												
											
											</div>
											<?php if(!empty($currency)) { 
											foreach($currency as $cur) {
												if($cur->type=='fiat') {

										 ?>
										<div class="scpm_wt_coin_total_set_body_scrl">
												
												<div class="scpm_wt_coin_total_set_li">
													<img src="<?=$cur->image;?>" class="scpm_wt_coin_total_set_li_img">
													<div class="scpm_wt_coin_total_set_li_1"><?=$cur->currency_symbol;?></div>
													<div class="scpm_wt_coin_total_set_li_2"><?=$cur->currency_name;?> </div>
												</div>
												
												</div><?php } } } ?>
										
										</div>
										</div>
										</div>
									</div>
									
										<div class="scpm_wt_inp_set">
										<div class="scpm_wt_inp_lbl">Receive</div>
										<div class="scpm_wt_inp_lbl_rht">Estimated Discount : 1900</div>
									<input type="text" class="scpm_wt_inp_input">

									<?php if(!empty($currency)) { 
											foreach($currency as $cur) {
												if($cur->type=='fiat') {

										 ?>

									<div class="scpm_wt_coin_set">
										<img src="<?=$cur->image;?>" class="scpm_wt_coin_img">
										<div class="scpm_wt_coin_lbl"><?=$cur->currency_name;?></div>
										<i class="fal fa-chevron-right scpm_wt_coin_lbl_i"></i>
										</div> <?php }}}?>
										<div class="scpm_wt_coin_total_set">
										<div class="scpm_wt_coin_total_set_in">
										<div class="scpm_wt_coin_total_set_center">
											<div class="scpm_wt_coin_total_set_top">Select Currency <i class="fal fa-times"></i></div>
										
											<div class="scpm_wt_coin_total_set_body">
											<input type="text" class="scpm_wt_coin_total_set_body_inp" placeholder="Search Coin Here">
												
											
											</div>
										<?php if(!empty($currency)) { 
											foreach($currency as $cur) {
												if($cur->type=='fiat') {

										 ?>
										<div class="scpm_wt_coin_total_set_body_scrl">
												
												<div class="scpm_wt_coin_total_set_li">
													<img src="<?=$cur->image;?>" class="scpm_wt_coin_total_set_li_img">
													<div class="scpm_wt_coin_total_set_li_1"><?=$cur->currency_symbol;?></div>
													<div class="scpm_wt_coin_total_set_li_2"><?=$cur->currency_name;?> </div>
												</div>
												
												</div><?php } } } ?>
										
										</div>
										</div>
										</div>
										
									</div>
									
									
									
									<div class="scpm_wt_btm_h1">Estimated Price : 945890USDT</div>
									
									<a href="#" class="scpm_wt_btm_anchor">Buy Now</a>
										 
									
									</div>
									<div class="scpm_wt_tab_body_set  scmpwc-2">
									<div class="scpm_wt_inp_set lin_box">
										<div class="scpm_wt_inp_lbl">Spend</div>
									<input type="text" class="scpm_wt_inp_input">
									<div class="scpm_wt_coin_set">
										<img src="assets/images/aico-1.png" class="scpm_wt_coin_img">
										<div class="scpm_wt_coin_lbl">BUSD</div>
										<i class="fal fa-chevron-right scpm_wt_coin_lbl_i"></i>
										</div>
										
										<div class="scpm_wt_coin_total_set">
										<div class="scpm_wt_coin_total_set_in">
										<div class="scpm_wt_coin_total_set_center">
											<div class="scpm_wt_coin_total_set_top">Select Currency <i class="fal fa-times"></i></div>
										
											<div class="scpm_wt_coin_total_set_body">
											<input type="text" class="scpm_wt_coin_total_set_body_inp" placeholder="Search Coin Here">
												
											
											</div>
										<div class="scpm_wt_coin_total_set_body_scrl">
												<div class="scpm_wt_coin_total_set_li">
													<img src="assets/images/aico-3.png" class="scpm_wt_coin_total_set_li_img">
													<div class="scpm_wt_coin_total_set_li_1">XRP</div>
													<div class="scpm_wt_coin_total_set_li_2">Ripple </div>
												</div>
												
												</div>
										
										</div>
										</div>
										</div>
									</div>
									
										<div class="scpm_wt_inp_set">
										<div class="scpm_wt_inp_lbl">Receive</div>
										<div class="scpm_wt_inp_lbl_rht">Estimated Discount : 1900</div>
									<input type="text" class="scpm_wt_inp_input">
									<div class="scpm_wt_coin_set">
										<img src="assets/images/aico-3.png" class="scpm_wt_coin_img">
										<div class="scpm_wt_coin_lbl">XRP</div>
										<i class="fal fa-chevron-right scpm_wt_coin_lbl_i"></i>
										</div>
										<div class="scpm_wt_coin_total_set">
										<div class="scpm_wt_coin_total_set_in">
										<div class="scpm_wt_coin_total_set_center">
											<div class="scpm_wt_coin_total_set_top">Select Currency <i class="fal fa-times"></i></div>
										
											<div class="scpm_wt_coin_total_set_body">
											<input type="text" class="scpm_wt_coin_total_set_body_inp" placeholder="Search Coin Here">
												
											
											</div>
										<div class="scpm_wt_coin_total_set_body_scrl">
												<div class="scpm_wt_coin_total_set_li">
													<img src="assets/images/aico-3.png" class="scpm_wt_coin_total_set_li_img">
													<div class="scpm_wt_coin_total_set_li_1">XRP</div>
													<div class="scpm_wt_coin_total_set_li_2">Ripple </div>
												</div>

												</div>
										
										</div>
										</div>
										</div>
										
									</div>
									

									
									<div class="scpm_wt_btm_h1">Estimated Price : 945890USDT</div>
									
									<a href="#" class="scpm_wt_btm_anchor">Sell Now</a>
										 
									
									</div>
									</div>
								
								</div>
							
							</div>
								 
							
							
							
							
							
							
							
							
							
							
							
							
							
							<div class="row justify-content-center">
								<div class="col-md-8"><div class="scpm_wt_repo_tbl_hd">Trade History</div></div>
							<div class="col-md-4">
								<div class="scpm_wt_repo_tab_head_set">
							<div class="scpm_wt_repo_tab_head_li scpm_wt_repo_tab_head_act scpmwtreth-1">Buy</div>
							<div class="scpm_wt_repo_tab_head_li scpmwtreth-2">Sell</div>
							
							</div>
								</div>
							</div>
							
							
							
							
							
							
								<div class="scpm_wt_repo_tab_body_set">
							<div class="scpm_wt_repo_tab_body_li scpm_wt_repo_tab_body_act scpmwtrebdy-1"><div class="table-responsive ">
							  <div class="cpm_repo_tbl_out scpm_wt_repo_tbl">
	
								<table class="table cpm_repo_tbl">
									<thead>
									  <tr>
										<th scope="col">Coin</th>
										<th scope="col">Amount</th>
										<th scope="col">Price</th>
										<th scope="col">Fees</th>
										<th scope="col">Final Amount</th>
										<th scope="col">Date</th>
										<th scope="col">Status</th>
									   
									  </tr>
									</thead>
									<tbody>
									  <tr>
										<td><div class="cpm_repo_tbl_coin"><img src="assets/images/aico-2.png" class="cpm_repo_tbl_coin_i">USDT</div></td>
										<td>101USDT</td>
										<td>90BTC</td>
										<td>0.9BTC</td>
										<td>90.9BTC</td>
										  <td>09-06-2022 10.38</td>
										<td><div class="cpm_repo_tbl_stat">Success</div></td>
									  </tr>
										  
										
									
									</tbody>
								  </table>
								  
							  </div>
							  </div></div>
							<div class="scpm_wt_repo_tab_body_li scpmwtrebdy-2">
									<div class="table-responsive ">
							  <div class="cpm_repo_tbl_out scpm_wt_repo_tbl">
	
								<table class="table cpm_repo_tbl">
									<thead>
									  <tr>
										<th scope="col">Coin</th>
										<th scope="col">Amount</th>
										<th scope="col">Price</th>
										<th scope="col">Fees</th>
										<th scope="col">Final Amount</th>
										<th scope="col">Date</th>
										<th scope="col">Status</th>
									   
									  </tr>
									</thead>
									<tbody>
									  <tr>
										<td><div class="cpm_repo_tbl_coin"><img src="assets/images/aico-4.png" class="cpm_repo_tbl_coin_i">BTC</div></td>
										<td>101USDT</td>
										<td>90BTC</td>
										<td>0.9BTC</td>
										<td>90.9BTC</td>
										  <td>09-06-2022 10.38</td>
										<td><div class="cpm_repo_tbl_stat">Success</div></td>
									  </tr>
										 
									</tbody>
								  </table>
								  
							  </div>
							  </div>
									</div>
							
							</div>
							
							
							
							
							
						<!--23-11-2022-->	
	
					   
	
	
					</div>
					</div>
<?php 
$this->load->view('front/common/footer');
?>