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
				<li class="active">Trade Pair</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">KYC Settings <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
			<?php if($view=='view_all_fees'){ ?>
			<a href="<?php echo admin_url().'trade_pairs/add_fees/'.$id;?>" class="btn btn-primary">Add New</a><?php } ?>
			</p>
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
                            <h4 class="panel-title">KYC Settings</h4>
                        </div>
					
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal col-md-6','id'=>'kycform_level1');
				echo form_open_multipart($action,$attributes); 
				$level1 = $kycSettings[0];
				?>
                        <fieldset>
                            <legend>Verified</legend>
							<div class="form-group">
	                            <label class="col-md-4 control-label">Fiat Deposit and Withdrawal Limit</label>
	                            <div class="col-md-4">
								<input type="text" name="fiat_transaction_limit" id="fiat_transaction_limit" class="form-control" value="<?=$level1->fiat_transaction_limit?>" />
	                            </div>
                            </div>
							<div class="form-group">
                                <label class="col-md-4 control-label">Crypto Withdrawal Limit</label>
                                <div class="col-md-4">
								<input type="text" name="crypto_withdrawal_limit" id="crypto_withdrawal_limit" class="form-control" value="<?=$level1->crypto_withdrawal_limit?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">P2P Transaction Limit</label>
                                <div class="col-md-4">
								<select id="ptop_limit" name="ptop_limit" class="form-control">
									<option value="">Select</option>
									<option <?=($level1->ptop_limit=='limit')?'selected':''?> value="limit">Limit</option>
									<option <?=($level1->ptop_limit=='unlimited')?'selected':''?> value="unlimited">Unlimited</option>
								</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Other Features</label>
                                <div class="col-md-4">
								<select id="other_features" name="other_features" class="form-control">
									<option value="">Select</option>
									<option <?=($level1->other_features==1)?'selected':''?> value="1">LPD</option>
									<option <?=($level1->other_features==2)?'selected':''?> value="2">OTD</option>
									<option <?=($level1->other_features==3)?'selected':''?> value="3">Binance Card</option>
								</select>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" name="kyc_level1" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php echo form_close(); ?>

                    <?php $attributes=array('class'=>'form-horizontal col-md-6','id'=>'kycform_level2');
				echo form_open_multipart($action,$attributes); 
				$level2 = $kycSettings[1]; ?>
                        <fieldset>
                            <legend>Verified Plus</legend>
                           	<div class="form-group">
	                            <label class="col-md-4 control-label">Fiat Deposit and Withdrawal Limit</label>
	                            <div class="col-md-4">
								<input type="text" name="fiat_transaction_limit" id="fiat_transaction_limit" class="form-control" value="<?=$level2->fiat_transaction_limit?>" />
	                            </div>
                            </div>
							<div class="form-group">
                                <label class="col-md-4 control-label">Crypto Withdrawal Limit</label>
                                <div class="col-md-4">
								<input type="text" name="crypto_withdrawal_limit" id="crypto_withdrawal_limit" class="form-control" value="<?=$level2->crypto_withdrawal_limit?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">P2P Transaction Limit</label>
                                <div class="col-md-4">
								<select id="ptop_limit" name="ptop_limit" class="form-control">
									<option value="">Select</option>
									<option <?=($level2->ptop_limit=='limit')?'selected':''?> value="limit">Limit</option>
									<option <?=($level2->ptop_limit=='unlimited')?'selected':''?> value="unlimited">Unlimited</option>
								</select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Other Features</label>
                                <div class="col-md-4">
								<select id="other_features" name="other_features" class="form-control">
									<option value="">Select</option>
									<option <?=($level2->other_features==1)?'selected':''?> value="1">LPD</option>
									<option <?=($level2->other_features==2)?'selected':''?> value="2">OTD</option>
									<option <?=($level2->other_features==3)?'selected':''?> value="3">Binance Card</option>
								</select>
                                </div>
                            </div>
							
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit"  name="kyc_level2" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                </div>
                            </div>
                        </fieldset>
                    <?php echo form_close(); ?>


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
	<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	<?php 
		if($view!='view_all')
		{ ?>
			<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<?php   } ?>
	<!-- ================== END PAGE LEVEL JS ================== -->
<script>
$(document).ready(function() {
$('#kycform_level1').validate({
	rules: {
		fiat_transaction_limit: {
			required: true,
			number: true
		},
		crypto_withdrawal_limit: {
			required: true,
			number: true
		},
		ptop_limit: {
			required: true
		},
		other_features: {
			required: true
		},
		
	},
	highlight: function (element) {
		//$(element).parent().addClass('error')
	},
	unhighlight: function (element) {
		$(element).parent().removeClass('error')
	}
});
$('#kycform_level2').validate({
	rules: {
		fiat_transaction_limit: {
			required: true,
			number: true
		},
		crypto_withdrawal_limit: {
			required: true,
			number: true
		},
		ptop_limit: {
			required: true
		},
		other_features: {
			required: true
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
	<script>
		$(document).ready(function() {
			App.init();
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

