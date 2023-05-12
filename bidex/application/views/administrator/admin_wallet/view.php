<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow"><?php echo $userdetails->username; ?>'s Wallets</h1>
	
	
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->
				
<!--Breadcrumb-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	<ol class="breadcrumb">
		<li><a href="<?php echo admin_url() . 'admin'; ?>">Home</a></li>
		<li class="active"><?php echo $userdetails->username; ?>'s Wallets</li>
	</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
				
<!--Page content-->
<!--===================================================-->
<style>
th, td{ border:0px none !important; } 
</style>
<div id="page-content">				
<div class="row">
	
						<div class="col-sm-12">
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title"><?php echo $userdetails->username; ?>'s Wallets</h3>
								</div>
					
								<!--Block Styled Form -->
								<!--===================================================-->
								<div class="panel-body">
								<table id="banners" class="table table-striped table-bordered1 text-left" cellspacing="0" width="100%">
										
									
									<tr>
									<td class="text-center" colspan="2"><h3>Balances</h3></td>
									</tr>					
									
									<tr>
									<th class="text-right" style="width:200px;">Bitcoin (BTC):</th>
									<td><?php echo to_decimal($wallets->BTC); ?> BTC</td>
									</tr>
									<tr>
									<th class="text-right">US Dollar (USD):</th>
									<td><?php echo to_decimal($wallets->USD); ?> USD</td>
									</tr>				
									
									
								
									
								<?php if(count($wallet_accounts)){	
									foreach($wallet_accounts as $wallet_account){	
										if($wallet_account['account_name'] == 'USD' || $wallet_account['account_name'] == 'NGN'){
											if(trim($wallet_account['account_details']) != '')
											 $accountdetails = 	unserialize($wallet_account['account_details']);
											 else{
												  $accountdetails['account_number'] = '';
												  $accountdetails['bank_swift_code'] = '';
												  $accountdetails['account_name'] = '';
												  $accountdetails['bank_name'] = '';
												  $accountdetails['bank_address'] = '';
												  $accountdetails['bank_postalcode'] = '';
												  $accountdetails['bank_city'] = '';
												  $accountdetails['bank_country'] = '';
											  }		
								
									 ?>	
									
									
									<tr>
									<td class="text-right" colspan="2"><br/></td>
									</tr>	
									
									<tr>
									<td class="text-center" colspan="2"><h3><?php echo $wallet_account['account_name']; ?> Bank Account Details</h3></td>
									</tr>	
									
									<tr>
									<th class="text-right">Bank Name:</th>
									<td><?php echo $accountdetails['bank_name']; ?></td>
									</tr>	
									<tr>
									<th class="text-right">Bank swift/bic Code:</th>
									<td><?php echo  $accountdetails['bank_swift_code']; ?></td>
									</tr>	
									<tr>
									<th class="text-right">Bank Account Name:</th>
									<td><?php echo  $accountdetails['account_name']; ?></td>
									</tr>	
									<tr>
									<th class="text-right">Bank Account Number:</th>
									<td><?php echo $accountdetails['account_number']; ?></td>
									</tr>	
									<tr>
									<th class="text-right">Bank Address:</th>
									<td><?php echo $accountdetails['bank_address']; ?></td>
									</tr>	
									<tr>
									<th class="text-right">Bank Postal Code:</th>
									<td><?php echo $accountdetails['bank_postalcode']; ?></td>
									</tr>	
									<tr>
									<th class="text-right">Bank City:</th>
									<td><?php echo $accountdetails['bank_city']; ?></td>
									</tr>	
									<tr>
									<th class="text-right">Bank Counrty:</th>
									<td><?php echo  $accountdetails['bank_country']; ?></td>
									</tr>	
									<?php }else{ ?>	
									<tr>
									<td class="text-right" colspan="2"><br/></td>
									</tr>	
									
									<tr>
									<td class="text-center" colspan="2"><h3><?php echo $wallet_account['account_name']; ?> Account Details</h3></td>
									</tr>	
									<tr>
									<th class="text-right">Wallet Address:</th>
									<td><?php echo $wallet_account['account_details']; ?></td>
									</tr>
									<?php } ?>
											
												<?php } } ?>	
									
									
							</table>
							<br/>
							
							
									</div>
									
								<!--===================================================-->
								<!--End Block Styled Form -->
					
							</div>	
						</div>
						
</div>
</div>

