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
		<li class="active">Coin Request</li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Coin Request Management <!--<small>header small text goes here...</small>--></h1>
	<!-- end page-header -->
	<!-- begin row -->
	<div class="row">
		<div class="col-md-12">
	        <!-- begin panel -->
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Coin Request Management</h4>
                </div>
                <div class="panel-body">
					<?php 
						$attributes=array('class'=>'form-horizontal','id'=>'coin_request');
						echo form_open($action,$attributes); 
					?>
		                <fieldset>
		                	
		                  	<div class="form-group">
		                        <label class="col-md-2 control-label">Email</label>
		                        <div class="col-md-8">										
									<input type="text" name="email" id="email" class="form-control" />
		                        </div>
		                  	</div>
		    				<!-- ENGLISH START -->
		                  	<div id="coin_request">
								<div class="form-group">
		                            <label class="col-md-2 control-label">Official Email Address</label>
		                            <div class="col-md-8">
										<input type="text" name="emailaddress" id="emailaddress" class="form-control" />
		                            </div>
		                      	</div>
		                        <div class="form-group">
		                            <label class="col-md-2 control-label">Contact Telegram  *</label>
		                            <div class="col-md-8">
									<input type="text" name="contact" id="contact" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Project Name *</label>
		                            <div class="col-md-8">
									<input type="text" name="project_name" id="project_name" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Coin/Token Ticker *</label>
		                            <div class="col-md-8">
									<input type="text" name="coin_name" id="coin_name" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Select Network Type *</label>
		                            <div class="col-md-8">
									<input type="checkbox" id="eth_box" name="crypto_type_other[]" onclick="showcontractDecimal(this);" value="eth" />ETH
									<input type="checkbox" id="bsc_box" name="crypto_type_other[]"  onclick="showcontractDecimal(this);" value="bsc" />BSC
									<input type="checkbox" id="trx_box" name="crypto_type_other[]" onclick="showcontractDecimal(this);" value="tron"/>TRX
									<!-- <select data-live-search="true" class="form-control" name="token_type" id="token_type" onChange="changecat(this.value);">
					                    <option class="opt" value="Select">Select</option>
					                    <option class="opt" value="ETH">ETH</option>
					                    <option class="opt" value="BSC">BSC</option>
					                    <option class="opt" value="TRX">TRX</option>
					                </select> -->
		                            </div>
		                        </div>

		                        <!-- <div class="form-group">
		                            <label class="col-md-2 control-label">Select Token Type</label>
		                            <div class="col-md-8">
									<select data-live-search="true" class="form-control" name="erc_token" id="erc_token" style="display:">
					                    <option value="" disabled selected>Select</option>
					                </select>
		                            </div>
		                        </div> -->

		                         <div class="form-group" id="eth_contract_div" style="display:none;">
		                            <label class="col-md-2 control-label">ETH smart contract address/ blockchain explorer *</label>
		                            <div class="col-md-8">
									<input type="text" name="eth_smart_address" id="eth_smart_address" class="form-control" />
		                            </div>
		                        </div>
								<div class="form-group" id="tron_contract_div" style="display:none;">
		                            <label class="col-md-2 control-label">TRX smart contract address/ blockchain explorer *</label>
		                            <div class="col-md-8">
									<input type="text" name="trx_smart_address" id="trx_smart_address" class="form-control" />
		                            </div>
		                        </div>
								<div class="form-group" id="bsc_contract_div" style="display:none;">
		                            <label class="col-md-2 control-label">BSC smart contract address/ blockchain explorer *</label>
		                            <div class="col-md-8">
									<input type="text" name="bsc_smart_address" id="bsc_smart_address" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Official Website Link *</label>
		                            <div class="col-md-8">
									<input type="text" name="webste_link" id="webste_link" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Project's White paper *</label>
		                            <div class="col-md-8">
									<input type="text" name="prjct_whitepaper" id="prjct_whitepaper" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Use-Cases of your Coin/Token *</label>
		                            <div class="col-md-8">
									<input type="text" name="coin_token" id="coin_token" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Current Market Price of your Coin/Token</label>
		                            <div class="col-md-8">
									<input type="text" name="market_price" id="market_price" class="form-control"/>
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Total Supply of your Coin/Token</label>
		                            <div class="col-md-8">
									<input type="text" name="supply_coin" id="supply_coin" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Name of the cryptocurrency Exchange Listed coin/token *</label>
		                            <div class="col-md-8">
									<input type="text" name="coin_exchange" id="coin_exchange" class="form-control" />
		                            </div>
		                        </div>
		                         <div class="form-group">
		                            <label class="col-md-2 control-label">Pay bounty/airdrop to our users *</label>
		                            <div class="col-md-8">        
				                        <div class="form-check-inline">
				                    
				                    <label class="customradio">
				                    	<input type="radio" name="bounty_user" value="yes" <?php //echo ($siteSettings->register_enable=='enable')?"checked":""; ?>>
				                        <span class="radiotextsty">Yes</span>
				                        
				                        
				                        <span class="checkmark"></span>
				                    </label> 
				                    <label class="customradio">
				                    	<input type="radio" name="bounty_user" value="no" <?php //echo ($siteSettings->register_enable=='disable')?"checked":""; ?>>
				                        <span class="radiotextsty">No</span>
				                        
				                        <span class="checkmark"></span>
				                    </label>
				                     <label class="customradio">
				                     	<input type="radio" name="bounty_user" value="maybe" <?php //echo ($siteSettings->register_enable=='disable')?"checked":""; ?>>
				                        <span class="radiotextsty">Maybe</span>
				                        
				                        <span class="checkmark"></span>
                                     </label>
                          
                                   </div>
						           </div>
		                        </div>
		                        
		                        <div class="form-group"> 
				                       <label class="col-md-2 control-label">listing fee/bounty fee(BTC) *</label>
				                    <div class="col-md-8">
									<input type="text" name="listing_btc" id="listing_btc" class="form-control" />
		                            </div>
						         </div>
						       <div class="form-group">
					            <label class="col-md-12 control-label text-center"><h4>Fees</h4></label>
					           </div>
					           <div class="form-group">
                                <label class="col-md-2 control-label">Currency Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="currency_name" id="currency_name" class="form-control" placeholder="Currency Name"/>
                                </div>
                               </div>

                               <div class="form-group">
                                <label class="col-md-2 control-label">Currency Symbol</label>
                                <div class="col-md-8">
                                    <input type="text" name="currency_symbol" id="currency_symbol" class="form-control"
                                        placeholder="Currency Symbol"/>
                                </div>
                               </div>

                               <div class="form-group">
                                <label class="col-md-2 control-label">Currency Type</label>
                                <div class="col-md-8">
                                    <select data-live-search="true" id="currency_type" name="currency_type" class="form-control"
                                        onchange="change_dep_type(this.value)">
                                        <option value="">Select</option>
                                        <option value="fiat">Fiat</option>
                                        <option value="digital">Digital</option>

                                    </select>
                                </div>
                               </div>

					           <div class="form-group">
					            <label class="col-md-2 control-label">Withdraw Fees Type</label>
					            <div class="col-md-8">
					                <select data-live-search="true" id="withdraw_fees_type" name="withdraw_fees_type" class="form-control selectpicker">
					                    <option value="Percent">Percent</option>
					                    <option value="Amount">Amount</option>
					                </select>
					            </div>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label">Withdraw Fees</label>
					            <div class="col-md-8">
					                <input type="text" name="withdraw_fees" id="withdraw_fees" class="form-control"/>
					            </div>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label">Maker Fee</label>
					            <div class="col-md-8">
					                <input type="text" name="maker_fee" id="maker_fee" class="form-control" />
					            </div>
					        </div>

					        <div class="form-group">
					            <label class="col-md-2 control-label">Taker Fee</label>
					            <div class="col-md-8">
					                <input type="text" name="taker_fee" id="taker_fee" class="form-control"/>
					            </div>
					        </div>
					          <div class="form-group">
					            <label class="col-md-2 control-label">Minimum Withdraw Limit</label>
					            <div class="col-md-8">
					                <input type="text" name="min_withdraw_limit" id="min_withdraw_limit" class="form-control" />
					            </div>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label">Maximum Withdraw Limit</label>
					            <div class="col-md-8">
					                <input type="text" name="max_withdraw_limit" id="max_withdraw_limit" class="form-control" />
					            </div>
					        </div>
					        <div id="deposit_seg" style="display: none;">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Minimum Deposit Limit</label>
                                            <div class="col-md-8">
                                                <input type="text" name="min_deposit_limit" id="min_deposit_limit" class="form-control"
                                                    placeholder="Minimum Deposit Limit" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Maximum Deposit Limit</label>
                                            <div class="col-md-8">
                                                <input type="text" name="max_deposit_limit" id="max_deposit_limit" class="form-control"
                                                    placeholder="Maximum Deposit Limit" />
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Deposit Fees Type</label>
                                            <div class="col-md-8">
                                                <select data-live-search="true" id="deposit_fees_type" name="deposit_fees_type" class="form-control selectpicker">
                                                    <option  value="Percent">
                                                        Percent</option>
                                                    <option  value="Amount">
                                                        Amount</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Deposit Fees</label>
                                            <div class="col-md-8">
                                                <input type="text" name="deposit_fees" id="deposit_fees" class="form-control"
                                                    placeholder="Deposit Fees" />
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Select asset type</label>
                                        <div class="col-md-8">
                                            <div class="form-check-inline">
                                                
                                                <label class="customradio">
                                                    <span class="radiotextsty">Coin</span>
                                                    <input type="radio" name="assets_types" value="1">
                                                    <span class="checkmark"></span>
                                                </label> 
                                                      
                                                <label class="customradio">
                                                    <span class="radiotextsty">Token</span>
                                                    <input type="radio" name="assets_types" value="0">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                
                            <div class="form-group decimal_sec" id="eth_currency_decimal_div" style="display:none;">
					            <label class="col-md-2 control-label">ETH Currency decimal</label>
					            <div class="col-md-8">
					                <input type="text" name="eth_currency_decimal" id="eth_currency_decimal" class="form-control" placeholder="" value="" />
					            </div>
					        </div>

							<div class="form-group decimal_sec" id="bsc_currency_decimal_div" style="display:none;">
					            <label class="col-md-2 control-label">BSC Currency decimal</label>
					            <div class="col-md-8">
					                <input type="text" name="bsc_currency_decimal" id="bsc_currency_decimal" class="form-control" placeholder="" value="" />
					            </div>
					        </div>

							<div class="form-group decimal_sec" id="tron_currency_decimal_div" style="display:none;">
					            <label class="col-md-2 control-label">TRX Currency decimal</label>
					            <div class="col-md-8">
					                <input type="text" name="trx_currency_decimal" id="trx_currency_decimal" class="form-control" placeholder="" value="" />
					            </div>
					        </div>

							                        <div class="form-group">
					            <label class="col-md-12 control-label text-center"><h4>Community</h4></label>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label">Coin Market Caplink</label>
					            <div class="col-md-8">
					                <input type="text" name="marketcap_link" id="marketcap_link" class="form-control"  value="" />
					            </div>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label">Coin Link</label>
					            <div class="col-md-8">
					                <input type="text" name="coin_link" id="coin_link" class="form-control"  value="" />
					            </div>
					        </div>
					        <div class="form-group">
					            <label class="col-md-2 control-label">Official Twitter Link</label>
					            <div class="col-md-8">
					                <input type="text" name="twitter_link" id="twitter_link" class="form-control"  value="" />
					            </div>
					        </div>

					        <div class="form-group">
					            <label class="col-md-2 control-label">Sort Order</label>
					            <div class="col-md-8">
					                <input type="text" name="sorting_order" id="sorting_order" class="form-control"
					                     value="" />
					            </div>
					        </div>
		                    </div>
		                  	<!--  ENGLISH END -->
		                	<!--    chinese strat         -->
		 				 	<!-- <div id="chinese" class="samelang">
								<div class="form-group">
		                        	<label class="col-md-2 control-label">Name</label>
		                            <div class="col-md-8">
									<input type="text" name="chinesename" id="name" class="form-control" placeholder="Name" value="" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="col-md-2 control-label">Page</label>
		                            <div class="col-md-8">
									<input type="text" name="chinesepage" id="page" class="form-control" placeholder="Page" value="" />
		                            </div>
		                        </div>
		                     	<div class="form-group">
		                            <label class="col-md-2 control-label">Content</label>
		                            <div class="col-md-8">
									<textarea class="form-control" id="chinesecontents" name="chinesecontents" rows="20" placeholder="Content"></textarea>
		                            </div>
		                        </div>
		                    </div> -->           
		                 	<!-- <div class="form-group">
		                        <label class="col-md-2 control-label">Image</label>
		                        <div class="col-md-8">
									<input type="file" name="image" id="image"/>
									<img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
		                        </div>
		                    </div> -->
		                    <div class="form-group">
		                        <div class="col-md-7 col-md-offset-5">
		                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
		                        </div>
		                    </div>
		                </fieldset>
		            <?php echo form_close();?>
            	</div>					
            </div>
            <!-- end panel -->
        </div>
	</div>
	<!-- end row -->
</div>
<!-- end #content -->
<!-- ================== BEGIN BASE JS ================== -->
<script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo admin_source();?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->
<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo admin_source();?>/plugins/gritter/js/jquery.gritter.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.time.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/flot/jquery.flot.resize.min.js"></script>
<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() 
	{
		//CKEDITOR.replace('contents');
		//CKEDITOR.replace('chinesecontents');
		$('#coin_request').validate({
			rules: {
				email: {
					required: true
				},
				emailaddress: {
					required: true,
				},
				contact: {
					required: true,
				},
				project_name: {
					required: true
				},
				coin_name: {
					required: true,
				},
				'crypto_type_other[]': {
					required: true
				},	
				// erc_token: {
				// 	required: true
				// },
				eth_smart_address: {
                    required: function(element){
                            return $("#eth_box").is(":checked");
                            }
                },
				bsc_smart_address: {
                    required: function(element){
                            return $("#bsc_box").is(":checked");
                            }
                },
				trx_smart_address: {
                    required: function(element){
							$("#trx_box").is(":checked");
                            }
                },
				// smart_address: {
				// 	required: true,
				// },
				webste_link: {
					required: true,
				},	
				prjct_whitepaper: {
					required: true
				},
				coin_token: {
					required: true,
				},
				market_price: {
					required: true,
				},	
				supply_coin: {
					required: true
				},
				coin_exchange: {
					required: true,
				},
				bounty_user: {
					required: true,
				},
				listing_btc: {
					required: true,
				},
				marketcap_link: {
					required: true,
				},
				coin_link: {
					required: true,
				},
				twitter_link: {
					required: true,
				},
				sorting_order: {
					required: true,
				},		
				withdraw_fees_type: {
					required: true,
				},
				withdraw_fees: {
					required: true,
				},	
				maker_fee: {
					required: true
				},
				taker_fee: {
					required: true,
				},
				min_withdraw_limit: {
					required: true,
				},
				max_withdraw_limit: {
					required: true,
				},
				currency_name: {
					required: true,
				},
				currency_symbol: {
					required: true,
				},
				currency_type: {
					required: true,
				},		
				min_deposit_limit: {
					required: true,
				},
				max_deposit_limit: {
					required: true,
				},	
				deposit_fees_type: {
					required: true
				},
				deposit_fees: {
					required: true,
				},
				assets_types: {
					required: true,
				},
				// currency_decimal: {
				// 	required: true,
				// },
				eth_currency_decimal: {
                    required: function(element){
                            return $("#eth_box").is(":checked");
                            }
                },
				bsc_currency_decimal: {
                    required: function(element){
                            return $("#bsc_box").is(":checked");
                            }
                },
				trx_currency_decimal: {
                    required: function(element){
							$("#trx_box").is(":checked");
                            }
                },
			},
			messages: {
				'crypto_type_other[]': {
					required: "You must check at least 1 box"
				},
			},
			highlight: function (element) {
				//$(element).parent().addClass('error')
			},
			unhighlight: function (element) {
				$(element).parent().removeClass('error')
			}
		});
	});
</script> 
<script type="text/javascript">
	$(document).ready(function() {
		App.init();
	});
	var admin_url='<?php echo admin_url(); ?>';
</script>
 <script async src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script type="text/javascript">
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-FDX8TJF8SG');
</script>
	<!-- LANGUAGRE DISPLAY IN CSS -->
<style>
	.samelang
	{
	 	display: none;
	}
	/*select option[disabled] {
    display: none;
}*/
</style>
<!--   LANGUAGE DISPLAY END IN CSS -->
<!--  ONCHANGE LANGUAGE  SCRIPT FUNCTION START -->

<script type="text/javascript">
var currencyByCategory = {
    ETH: ["ERC 20"],
    BSC: ["BEP 2", "BEP 20"],
    TRX: ["TRC 20"]
}

    function changecat(value) {
        if (value.length == 0) document.getElementById("erc_token").innerHTML = "<option></option>";
        else {
            var catOptions = "";
            var categoryId = "";
            for (categoryId in currencyByCategory[value]) {
                catOptions += "<option>" + currencyByCategory[value][categoryId] + "</option>";
            }
            document.getElementById("erc_token").innerHTML = catOptions;
        }
    }

	function showcontractDecimal(value)
	{
		console.log(value)
		if(value.checked)
		{
			console.log("checked")
			$("#"+value.value+"_contract_div").show();
			$("#"+value.value+"_currency_decimal_div").show();
			
		} else {
			console.log("unchecked")
			$("#"+value.value+"_contract_div").hide();
			$("#"+value.value+"_currency_decimal_div").hide();
		}
	}

    function change_dep_type(sel) {
    if (sel == 'fiat') {
        $('#deposit_seg').css('display', 'block');
    } else {
        $('#deposit_seg').css('display', 'none');
    }
}
</script>
