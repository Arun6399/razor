<?php 
$this->load->view('front/common/header');
?>

					<div class=" cpm_mdl_cnt">
						<div class="container animated" data-animation="fadeInRightShorter"
						data-animation-delay="1s">
	
	
						  <div class="cpm_rep_hd_out">
						  <div class="cpm_rep_hd">
							<div class="cpm_rep_hd_li cpm_rep_hd_li_act" data-hdrname="exchange">Exchange</div>
							<!-- <div class="cpm_rep_hd_li" data-hdrname="p2p">P2P</div> -->
							<div class="cpm_rep_hd_li" data-hdrname="deposit">Deposit</div>
							<div class="cpm_rep_hd_li" data-hdrname="withdraw">Withdrawal</div>
							<div class="cpm_rep_hd_li" data-hdrname="login">Login History</div>
	
						  </div>
						  </div>
	
	
	
						  <div class="cpm_rep_body_set cpm_rep_body_act" data-bdyname="exchange">
						  <div class="cpm_rep_hd_btm">
							  <div class="row">
								<div class="col-md-3 col-6">
									<select class="cpm_rep_hd_btm_inp cpm_rep_hd_btm_inp_out">
										<option value="0">1 Month</option>
										<option value="0">2 Month</option>
										<option value="0">3 Month</option>
									</select>
								</div>								  
								<div class="col-md-3">
								  
								  <div class="cpm_rep_hd_b_st">
								  <div class="cpm_rep_hd_b_lbl">Export To</div>
									<a href="#" onclick="ExportPDF('trade_tbl')" class="cpm_rep_hd_b_btnn">PDF</a>
									  <a href="#" onclick="ExportToExcel('xlsx','trade_tbl')" class="cpm_rep_hd_b_btnn">EXCEL</a>
								  </div>
								</div>   
	
							  </div>
						  </div>
						  <div class="cpm_rep_bdy" >
							  <div class="table-responsive ">
							  <div class="cpm_repo_tbl_out ">
	
								<table class="table cpm_repo_tbl datatable" id="trade_tbl">
									<thead >
									  <tr>
										<th scope="col">Pairs</th>
										<th scope="col">Time</th>
										<th scope="col">Amount</th>
										<th scope="col">Price</th>
										<th scope="col">Transaction Id</th>
										<th scope="col">Status</th>
									   
									  </tr>
									</thead>
									<tbody>
										<?php if(!empty($exchange_history)){
									  		foreach($exchange_history as $exchange){?>
										<tr>
										  <td><div class="cpm_repo_tbl_coin"><img src="<?php echo front_img();?>aico-5.png" class="cpm_repo_tbl_coin_i"><?php $exchange->pair_symbol;?> </div></td>
										  <td><?php echo $exchange->trade_time;?></td>
										  <?php if($exchange->status=="partially"){?>
										  <td><?php echo $exchange->Amount;?>(<?php echo $exchange->totalamount;?>)</td><?php } ?>
										  <?php if($exchange->status!="partially"){?>
										  <td><?php echo $exchange->Amount;?></td><?php } ?>
										  <td><?php echo $exchange->Price;?></td>
										  <td><?php echo $exchange->Type;?></td>
										  <td>
										  	<?php if($exchange->status=="filled"){?>
										  	<div class="cpm_repo_tbl_stat"><?php echo $exchange->status;?></div><?php } ?>
										  	<?php if($exchange->status=="partially"){?>
										  	<div class="cpm_repo_tbl_stat cpm_repo_stat_pending"><?php echo $exchange->status;?></div><?php } ?>
										  	</td>
										</tr>
										<?php } } ?>								
									     
									  
									
									</tbody>
								  </table>
								  
							  </div>
							  </div>
						  </div>
						</div>
					
						<div class="cpm_rep_body_set " data-bdyname="p2p">
							<div class="cpm_rep_hd_btm">
								<div class="row">
								  <div class="col-md-3 col-6">
									  <select class="cpm_rep_hd_btm_inp cpm_rep_hd_btm_inp_out">
										  <option value="0">1 Month</option>
										  <option value="0">2 Month</option>
										  <option value="0">3 Month</option>
									  </select>
								  </div>
								    
								  <div class="col-md-3">
									   <div class="cpm_rep_hd_b_st">
								  <div class="cpm_rep_hd_b_lbl">Export To</div>
									   <a href="#" onclick="ExportPDF('p2p_tbl')" class="cpm_rep_hd_b_btnn">PDF</a>
									  <a href="#" onclick="ExportToExcel('xlsx','p2p_tbl')" class="cpm_rep_hd_b_btnn">EXCEL</a>
								  </div>
	  
								  </div>   
	  
								</div>
							</div>
							<div class="cpm_rep_bdy" >
								<div class="table-responsive ">
								<div class="cpm_repo_tbl_out ">
	  
								  <table class="table cpm_repo_tbl datatable" id="p2p_tbl">
									  <thead >
										<tr>
										  <th scope="col">Sell Coin</th>
										  <th scope="col">Quantity</th>
										  <th scope="col">Buy Coin</th>
										  <th scope="col">Quantity</th>
										  <th scope="col">Time</th>
										  <th scope="col">Transaction Id</th>
										  <th scope="col">Status</th>
										 
										</tr>
									  </thead>
									  <tbody>
										<tr>
										  <td><div class="cpm_repo_tbl_coin"><img src="<?php echo front_img();?>aico-5.png" class="cpm_repo_tbl_coin_i">ETH </div></td>
										  <td>1000 BTC</td>
										  <td><div class="cpm_repo_tbl_coin"><img src="<?php echo front_img();?>aico-4.png" class="cpm_repo_tbl_coin_i">BTC </div></td>
										  <td>1000 BTC</td>
										  <td>09-06-2022 10.38</td>
										
										  <td>DJFH8DFJHD89G9<i class="fal fa-clipboard cpm_repo_tbl_copy"></i></td>
										  <td><div class="cpm_repo_tbl_stat">Success</div></td>
										</tr>
									  
									  </tbody>
									</table>
									
								</div>
								</div>
							</div>
						  </div>
	
						  <div class="cpm_rep_body_set " data-bdyname="deposit">
							<div class="cpm_rep_hd_btm">
								<div class="row">
								  <div class="col-md-3 col-6">
									  <select class="cpm_rep_hd_btm_inp cpm_rep_hd_btm_inp_out">
										  <option value="0">1 Month</option>
										  <option value="0">2 Month</option>
										  <option value="0">3 Month</option>
									  </select>
								  </div>
								    
								  <div class="col-md-3">
									   <div class="cpm_rep_hd_b_st">
								  <div class="cpm_rep_hd_b_lbl">Export To</div>
									  <a href="#" onclick="ExportPDF('deposit_tbl')" class="cpm_rep_hd_b_btnn">PDF</a>
									  <a href="#" onclick="ExportToExcel('xlsx','deposit_tbl')" class="cpm_rep_hd_b_btnn">EXCEL</a>
								  </div>
	  
								  </div>   
	  
								</div>
							</div>
							<div class="cpm_rep_bdy" >
								<div class="table-responsive ">
								<div class="cpm_repo_tbl_out ">
	  
								  <table class="table cpm_repo_tbl datatable" id="deposit_tbl">
									  <thead >
										<tr>
										  <th scope="col">Assets</th>
										  <th scope="col">Time</th>
										  <th scope="col">Price</th>
										  <th scope="col">Fee</th>
										  <th scope="col">Transaction Id</th>
										  <th scope="col">Status</th>
										 
										</tr>
									  </thead>
									  <tbody>
									  	<?php if(!empty($deposit_history)){
									  		foreach($deposit_history as $deposit){?>
										<tr>
										  <td><div class="cpm_repo_tbl_coin"><img src="<?php echo front_img();?>aico-5.png" class="cpm_repo_tbl_coin_i"><?php getcurrencySymbol($deposit->currency_id);?> </div></td>
										  <td><?php echo date("Y-m-d H:i:s",$deposit->datetime);?></td>
										  <td><?php echo $deposit->amount.''.getcurrencySymbol($deposit->currency_id);?></td>
										  <td><?php echo $deposit->fee;?></td>
										  <td><?php echo $deposit->transaction_id;?><i class="fal fa-clipboard cpm_repo_tbl_copy"></i></td>
										  <td>
										  	<?php if($deposit->status=="Completed"){?>
										  	<div class="cpm_repo_tbl_stat"><?php echo $deposit->status;?></div><?php } ?>
										  	<?php if($deposit->status=="Pending"){?>
										  	<div class="cpm_repo_tbl_stat cpm_repo_stat_pending"><?php echo $deposit->status;?></div><?php } ?>
										  	</td>
										</tr>
										<?php } }  ?>								
									     <!-- <tr>
									     	<td></td>
									     	<td></td>
									     	<td>No History</td>
									     	<td></td>
									     	<td></td>
									     	<td></td>
									     </tr> -->
									  </tbody>
									</table>
									
								</div>
								</div>
							</div>
						  </div>
						  <div class="cpm_rep_body_set " data-bdyname="withdraw">
							<div class="cpm_rep_hd_btm">
								<div class="row">
								  <div class="col-md-3 col-6">
									  <select class="cpm_rep_hd_btm_inp cpm_rep_hd_btm_inp_out">
										  <option value="0">1 Month</option>
										  <option value="0">2 Month</option>
										  <option value="0">3 Month</option>
									  </select>
								  </div>
								    
								  <div class="col-md-3">
									   <div class="cpm_rep_hd_b_st">
								  <div class="cpm_rep_hd_b_lbl">Export To</div>
									  <a href="#" onclick="ExportPDF('withdraw_tbl')" class="cpm_rep_hd_b_btnn">PDF</a>
									  <a href="#" onclick="ExportToExcel('xlsx','withdraw_tbl')" class="cpm_rep_hd_b_btnn">EXCEL</a>
								  </div>
	  
								  </div>   
	  
								</div>
							</div>
							<div class="cpm_rep_bdy" >
								<div class="table-responsive ">
								<div class="cpm_repo_tbl_out ">
	  
								  <table class="table cpm_repo_tbl datatable" id="withdraw_tbl">
									  <thead >
										<tr>
										  <th scope="col">Assets</th>
										  <th scope="col">Time</th>
										  <th scope="col">Price</th>
										  <th scope="col">Fee</th>
										  <th scope="col">Transaction Id</th>
										  <th scope="col">Status</th>
										 
										</tr>
									  </thead>
									  <tbody>
										<?php if(!empty($withdraw_history)){
									  		foreach($withdraw_history as $withdraw){?>
										<tr>
										  <td><div class="cpm_repo_tbl_coin"><img src="<?php echo front_img();?>aico-5.png" class="cpm_repo_tbl_coin_i"><?php getcurrencySymbol($withdraw->currency_id);?> </div></td>
										  <td><?php echo date("Y-m-d H:i:s",$withdraw->datetime);?></td>
										  <td><?php echo $withdraw->amount.''.getcurrencySymbol($withdraw->currency_id);?></td>
										  <td><?php echo $withdraw->fee;?></td>
										  <td><?php echo $withdraw->transaction_id;?><i class="fal fa-clipboard cpm_repo_tbl_copy"></i></td>
										  <td>
										  	<?php if($withdraw->status=="Completed"){?>
										  	<div class="cpm_repo_tbl_stat"><?php echo $withdraw->status;?></div><?php } ?>
										  	<?php if($withdraw->status=="Pending"){?>
										  	<div class="cpm_repo_tbl_stat cpm_repo_stat_pending"><?php echo $withdraw->status;?></div><?php } ?>
										  	</td>
										</tr>
										<?php } } ?>								
									     
									  </tbody>
									</table>
									
								</div>
								</div>
							</div>
						  </div>

						  <div class="cpm_rep_body_set " data-bdyname="login">
							<div class="cpm_rep_hd_btm">
								<div class="row">
								  <div class="col-md-3 col-6">
									  <select class="cpm_rep_hd_btm_inp cpm_rep_hd_btm_inp_out">
										  <option value="0">1 Month</option>
										  <option value="0">2 Month</option>
										  <option value="0">3 Month</option>
									  </select>
								  </div>
								    
								  <div class="col-md-3">
									   <div class="cpm_rep_hd_b_st">
								  <div class="cpm_rep_hd_b_lbl">Export To</div>
									  <a href="#" onclick="ExportPDF('login_table')" class="cpm_rep_hd_b_btnn">PDF</a>
									  <a href="#" onclick="ExportToExcel('xlsx','login_table')" class="cpm_rep_hd_b_btnn">EXCEL</a>
								  </div>
	  
								  </div>   
	  
								</div>
							</div>
							<div class="cpm_rep_bdy" >
								<div class="table-responsive ">
								<div class="cpm_repo_tbl_out ">
	  
								  <table class="table cpm_repo_tbl datatable" id="login_table">
									  <thead >
										<tr>
										  <th scope="col">Date & Time</th>
										  <th scope="col">IP Address</th>
										  <th scope="col">Browser</th>
										  <th scope="col">Action</th>
										</tr>
									  </thead>
									  <tbody>
									  	<?php if(!empty($login_history)){
									  		foreach($login_history as $login){?>
										<tr>
										  <td><?php echo date("d-m-Y h:i a",$login->date);?></td>										  
										  <td><?php echo $login->ip_address;?></td>
										  <td><?php echo $login->browser_name;?></td>	
										  <td><?php echo $login->activity;?></td>
										</tr>
									 <?php } } ?>								
									     
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
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>



<script type="text/javascript">

function ExportToExcel(type,tbl, fn, dl) {

		console.log(type);
		console.log(tbl);
       var elt = document.getElementById(tbl);
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('MySheetName.' + (type || 'xlsx')));
    }	



   function ExportPDF(tbl) {
            html2canvas(document.getElementById(tbl), {
                onrendered: function (canvas) {
                    var data = canvas.toDataURL();
                    var docDefinition = {
                        content: [{
                            image: data,
                            width: 500,
                            background: 'rgba(0,0,0,0)'
                        }]
                    };
                    pdfMake.createPdf(docDefinition).download("Table.pdf");
                }
            });
        }
</script>