<?php 
$this->load->view('front/common/header');
?>

					<div class=" cpm_mdl_cnt">
						<div class="container animated" data-animation="fadeInRightShorter"
						data-animation-delay="1s">
	
	
						  <div class="cpm_rep_hd_out">
						  <div class="cpm_rep_hd">
							<div class="cpm_rep_hd_li cpm_rep_hd_li_act" data-hdrname="p2p_open">P2P Open Orders</div>
							<div class="cpm_rep_hd_li" data-hdrname="p2p_complete">P2P Completed Orders</div>
							
	
						  </div>
						  </div>
	
	
	
						  <div class="cpm_rep_body_set cpm_rep_body_act" data-bdyname="p2p_open">
						  <div class="cpm_rep_hd_btm">
							  <div class="row">
								<!-- <div class="col-md-3 col-6">
									<select class="cpm_rep_hd_btm_inp cpm_rep_hd_btm_inp_out">
										<option value="0">1 Month</option>
										<option value="0">2 Month</option>
										<option value="0">3 Month</option>
									</select>
								</div>	 -->							  
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
									  	<th scope="col">S.No</th>
										<th scope="col">User</th>
										<th scope="col">Amount</th>
										<th scope="col">Price</th>
										<th scope="col">Type</th>
										<th scope="col">Datetime</th>
										<th scope="col">Status</th>
										<th scope="col">Action</th>
									   
									  </tr>
									</thead>
									<tbody>
										<?php if(!empty($p2p_openorders)){
											$i=1;
									  		foreach($p2p_openorders as $opens){
									  		 $user = UserName($opens->user_id);
									  		 // if($opens->tradestatus=='open') {

									  			?>
										<tr>
										  <td style="text-align: center;"><?=$i;?></td>
										  <td><?=$user;?></td>
										  <td><?=$opens->trade_amount;?></td>
										  <td><?=$opens->price;?></td>
										  <td><?=$opens->type;?></td>
										  <td><?=$opens->datetime;?></td>
										  <td><?=$opens->tradestatus;?></td>
										  <td><div class="cpm_repo_tbl_stat cpm_repo_stat_danger"><a style="color: #fff;" href="<?php echo base_url();?>p2p_cancel/<?=encryptIt($opens->id);?>"> Cancel </a></div></td>
										</tr>
										<?php $i++;  //}

										 } } ?>								
									     
									  
									
									</tbody>
								  </table>
								  
							  </div>
							  </div>
						  </div>
						</div>
					
						<div class="cpm_rep_body_set " data-bdyname="p2p_complete">
							<div class="cpm_rep_hd_btm">
								<div class="row">
								  <!-- <div class="col-md-3 col-6">
									  <select class="cpm_rep_hd_btm_inp cpm_rep_hd_btm_inp_out">
										  <option value="0">1 Month</option>
										  <option value="0">2 Month</option>
										  <option value="0">3 Month</option>
									  </select>
								  </div> -->
								    
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
										  <th scope="col">S.No</th>
										  <th scope="col">Buyer</th>
										  <th scope="col">Seller</th>
										  <th scope="col">Amount Crypto</th>
										  <th scope="col">Amount Fiat</th>
										  <th scope="col">Datetime</th>
										  <th scope="col">Status</th>
										 
										</tr>
									  </thead>
									  <tbody>

									  	<?php if(!empty($p2p_orders)){
											$i=1;
									  		foreach($p2p_orders as $ordes){
									  		 $buyer = UserName($ordes->buyerid);
        									 $seller = UserName($ordes->sellerid);
        									 $crypto = getcurrency_name($ordes->cryptocurrency);
        									 $fiat = getcurrency_name($ordes->fiat_currency);

									  			?>
									  			<tr>
											  <td style="text-align: center;"><?=$i;?></td>
											  <td><?=$buyer;?>  </td>
											  <td><?=$seller;?></td>
											  <td><?=$ordes->crypto_amount;?>  <?=$crypto;?></td>
											  <td><?=$ordes->fiat_amount;?>  <?=$fiat;?></td>
											  <td><?=$ordes->tradeopentime;?></td>
											  <td><?=ucfirst($ordes->tradestatus);?></td>
											</tr>

									<?php  } } ?>
									  
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