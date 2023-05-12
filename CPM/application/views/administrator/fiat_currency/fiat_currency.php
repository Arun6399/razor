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
				<li class="active">Fiat Currency</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Fiat Currency Management <!--<small>header small text goes here...</small>--></h1>
			<!--<p class="text-right m-b-10">
								<a href="<?php echo admin_url().'currency/add';?>" class="btn btn-primary">Add New</a>
							</p>-->
			<!-- end page-header -->
			<!-- begin row -->
			<div class="row">
				<div class="col-md-12">
			        <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Fiat Currency Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Currency Name</th>
										<th class="text-center">Currency Symbol</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($fiat_currency->num_rows() > 0) {
									$i = 1;
									foreach($fiat_currency->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->currency_name .'</td>';
										echo '<td>' . $result->currency_symbol .'</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
											$extra = array('title' => 'De-activate this fiat currency');
											$changeStatus = anchor(admin_url().'fiat_currency/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
											$extra = array('title' => 'Activate this fiat currency');
											$changeStatus = anchor(admin_url().'fiat_currency/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
										}
										echo '<td>'.$status.'</td>';
										echo '<td>';
										echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'fiat_currency/edit/' . $result->id . '" title="Edit this fiat currency"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'fiat_currency/delete/' . $result->id . '" title="Delete this fiat currency"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td colspan="2" class="text-center">No fiat currency added yet!</td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='add'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'fiat_currency');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Currency Name</label>
                                        <div class="col-md-4">
										<input type="text" name="currency_name" id="currency_name" class="form-control" placeholder="Currency Name" value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Currency Symbol</label>
                                        <div class="col-md-4">
										<input type="text" name="currency_symbol" id="currency_symbol" class="form-control" placeholder="Currency Symbol" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="col-md-4 control-label">Image</label>
                                        <div class="col-md-4">
                                        <input type="file" name="image" id="image" />
                                        </div>
                                    </div>
<!--                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum Deposit Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="min_deposit_limit" id="min_deposit_limit" class="form-control" placeholder="Minimum Deposit Limit" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum Deposit Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="max_deposit_limit" id="max_deposit_limit" class="form-control" placeholder="Maximum Deposit Limit" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum Deposit Fees</label>
                                        <div class="col-md-4">
										<input type="text" name="deposit_max_fees" id="deposit_max_fees" class="form-control" placeholder="Maximum Deposit Fees" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Deposit Fees Type</label>
                                        <div class="col-md-4">
										<select id="deposit_fees_type" name="deposit_fees_type" class="form-control">
										<option value="Percent">Percent</option>
										<option value="Amount">Amount</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Deposit Fees</label>
                                        <div class="col-md-4">
										<input type="text" name="deposit_fees" id="deposit_fees" class="form-control" placeholder="Deposit Fees" value="" />
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum Withdraw Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="min_withdraw_limit" id="min_withdraw_limit" class="form-control" placeholder="Minimum Withdraw Limit" value="" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum Withdraw Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="max_withdraw_limit" id="max_withdraw_limit" class="form-control" placeholder="Maximum Withdraw Limit" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Withdraw Fees Type</label>
                                        <div class="col-md-4">
										<select id="withdraw_fees_type" name="withdraw_fees_type" class="form-control">
										<option value="Percent">Percent</option>
										<option value="Amount">Amount</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Withdraw Fees</label>
                                        <div class="col-md-4">
										<input type="text" name="withdraw_fees" id="withdraw_fees" class="form-control" placeholder="Withdraw Fees" value="" />
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Reserve Amount</label>
                                        <div class="col-md-4">
                                        <input type="text" name="reserve_Amount" id="reserve_Amount" class="form-control" placeholder="Reserve Amount" value="" />
                                        </div>
                                    </div> -->

									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select id="status" name="status" class="form-control">
										<option value="1">Active</option>
										<option value="0">De-active</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
					<?php }else{ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'edit_fiat_currency');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Currency Name</label>
                                        <div class="col-md-4">
										<input type="text" name="currency_name" id="currency_name" class="form-control" placeholder="Currency Name" value="<?php echo $fiat_currency->currency_name; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Currency Symbol</label>
                                        <div class="col-md-4">
										<input type="text" name="currency_symbol" id="currency_symbol" class="form-control" placeholder="Commision Value" value="<?php echo $fiat_currency->currency_symbol; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Image</label>
                                        <div class="col-md-4">
                                        <input type="file" name="image" id="image"/>
                                        <?php $im = $fiat_currency->image; ?>
                                        <input type="hidden" name="oldimage" id="oldimage" value="<?php echo $fiat_currency->image; ?>" />
                                        <?php if($fiat_currency->image!='') { ?>
                                        <img src="<?php echo $im; ?>" style="width:65px;height:65px;" />
                                        <?php } ?>
                                        </div>
                                    </div>
                                     <!-- <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum Deposit Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="min_deposit_limit" id="min_deposit_limit" class="form-control" placeholder="Minimum Deposit Limit" value="<?php echo $fiat_currency->min_deposit_limit; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum Deposit Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="max_deposit_limit" id="max_deposit_limit" class="form-control" placeholder="Maximum Deposit Limit" value="<?php echo $fiat_currency->max_deposit_limit; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum Deposit Fees</label>
                                        <div class="col-md-4">
										<input type="text" name="deposit_max_fees" id="deposit_max_fees" class="form-control" placeholder="Maximum Deposit Fees" value="<?php echo $fiat_currency->deposit_max_fees; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Deposit Fees Type</label>
                                        <div class="col-md-4">
										<select id="deposit_fees_type" name="deposit_fees_type" class="form-control">
										<option value="Percent" <?php if($fiat_currency->deposit_fees_type=='Percent'){echo 'selected';}?>>Percent</option>
										<option value="Amount" <?php if($fiat_currency->deposit_fees_type=='Amount'){echo 'selected';}?>>Amount</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Deposit Fees</label>
                                        <div class="col-md-4">
										<input type="text" name="deposit_fees" id="deposit_fees" class="form-control" placeholder="Deposit Fees" value="<?php echo $fiat_currency->deposit_fees; ?>" />
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum Withdraw Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="min_withdraw_limit" id="min_withdraw_limit" class="form-control" placeholder="Minimum Withdraw Limit" value="<?php echo $fiat_currency->min_withdraw_limit; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum Withdraw Limit</label>
                                        <div class="col-md-4">
										<input type="text" name="max_withdraw_limit" id="max_withdraw_limit" class="form-control" placeholder="Maximum Withdraw Limit" value="<?php echo $fiat_currency->max_withdraw_limit; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Withdraw Fees Type</label>
                                        <div class="col-md-4">
										<select id="withdraw_fees_type" name="withdraw_fees_type" class="form-control">
										<option value="Percent" <?php if($fiat_currency->withdraw_fees_type=='Percent'){echo 'selected';}?>>Percent</option>
										<option value="Amount" <?php if($fiat_currency->withdraw_fees_type=='Amount'){echo 'selected';}?>>Amount</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Withdraw Fees</label>
                                        <div class="col-md-4">
										<input type="text" name="withdraw_fees" id="withdraw_fees" class="form-control" placeholder="Withdraw Fees" value="<?php echo $fiat_currency->withdraw_fees; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Reserve Amount</label>
                                        <div class="col-md-4">
                                        <input type="text" name="reserve_Amount" id="reserve_Amount" class="form-control" placeholder="Reserve Amount" value="<?php echo $fiat_currency->reserve_Amount; ?>" />
                                        </div>
                                    </div> -->

									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select id="status" name="status" class="form-control">
										<option <?php if($fiat_currency->status==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($fiat_currency->status==0){echo 'selected';}?> value="0">De-active</option>
										</select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
					<?php } ?> 
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
	<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	$(document).ready(function() {
		$('#fiat_currency').validate({
			rules: {
				currency_name: {
					required: true
				},
				currency_symbol: {
					required: true
				},
				// min_deposit_limit: {
				// 	required: true,
				// 	number: true
				// },
				// max_deposit_limit: {
				// 	required: true,
				// 	number: true
				// },
    //             reserve_Amount: {
    //                 required: true,
    //                 number: true
    //             },
    //             image: {
    //                  required: true,
    //              },
    //              min_withdraw_limit: {
    //                 required: true,
    //                 number: true
    //             },
    //              max_withdraw_limit: {
    //                 required: true,
    //                 number: true
    //             },
    //               deposit_fees: {
    //                 required: true,
    //                 number: true
    //             },
    //               deposit_fees_type: {
    //                 required: true,
    //             },
    //                deposit_max_fees: {
    //                 required: true,
    //             },
    //                withdraw_fees: {
    //                 required: true,
    //                 number:true,
    //             },
    //             withdraw_fees_type: {
    //                 required: true,
    //             },


			},
			highlight: function (element) {
				//$(element).parent().addClass('error')
			},
			unhighlight: function (element) {
				$(element).parent().removeClass('error')
			}
		});

		$('#edit_fiat_currency').validate({
			rules: {
				currency_name: {
					required: true
				},
				currency_symbol: {
					required: true
				},
				min_deposit_limit: {
					required: true,
					number: true
				},
				max_deposit_limit: {
					required: true,
					number: true
				},
                reserve_Amount: {
                    required: true,
                    number: true
                },
                 min_withdraw_limit: {
                    required: true,
                    number: true
                },
                 max_withdraw_limit: {
                    required: true,
                    number: true
                },
                  deposit_fees: {
                    required: true,
                    number: true
                },
                  deposit_fees_type: {
                    required: true,
                },
                   deposit_max_fees: {
                    required: true,
                },
                   withdraw_fees: {
                    required: true,
                    number:true,
                },
                withdraw_fees_type: {
                    required: true,
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
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');
    
      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');	
    </script>