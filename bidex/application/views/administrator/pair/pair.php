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
				<li class="active">Pair</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Pair Management <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
								<!--<a href="<?php echo admin_url().'pair/add';?>" class="btn btn-primary">Add New</a>-->
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
                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Pair Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Pair Names</th>
										<th class="text-center">Pair Symbols</th>
										<!-- <th class="text-center">Exchange Rate</th> -->
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($pair->num_rows() > 0) {
									$i = 1;
									foreach($pair->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->from_currency . '/'. $result->to_currency .'</td>';
										echo '<td>' . $result->from_currency_symbol . '/'. $result->to_currency_symbol .'</td>';
										// echo '<td>1 ' . $result->from_currency . '='.to_decimal($result->rate_value).' '.$result->to_currency.'</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
											$extra = array('title' => 'De-activate this pair');
											$changeStatus = anchor(admin_url().'pair/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
											$extra = array('title' => 'Activate this pair');
											$changeStatus = anchor(admin_url().'pair/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
										}
										echo '<td>'.$status.'</td>';
										echo '<td>';
										echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'pair/edit/' . $result->id . '" title="Edit this pair"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'pair/delete/' . $result->id . '" title="Delete this pair"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td colspan="2" class="text-center">No Pair added yet!</td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='add'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'exchange_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">From Symbol</label>
                                        <div class="col-md-4">
										<select id="from_name" name="from_name" onchange="change_to_symbol(this.value)" class="form-control" placeholder="Pair Name">
										<option value="">Select</option>
										<?php foreach($currency->result() as $cur){ ?>
										<option value="<?php echo $cur->id;?>"><?php echo $cur->currency_name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">To Symbol</label>
                                        <div class="col-md-4">
										<select id="to_name" name="to_name" class="form-control" placeholder="Pair Name">
										<option value="">Select</option>
										</select>
                                        </div>
                                    </div>
									
									<div class="form-group">
                                        <label class="col-md-4 control-label">Commision Type</label>
                                        <div class="col-md-4">
										<select id="commision_type" name="commision_type" class="form-control" placeholder="Pair Name">
										<option value="">Select</option>
										<option value="Percent">Percent</option>
										<option value="Amount">Amount</option>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Commision Value</label>
                                        <div class="col-md-4">
										<input type="text" name="commision_value" id="commision_value" class="form-control" placeholder="Commision Value" value="" />
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">Buy Offer Prices</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 1</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_1" id="buy_offer_1" class="form-control" placeholder="Buy Offer 1"  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 2</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_2" id="buy_offer_2" class="form-control" placeholder="Buy Offer 2" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 3</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_3" id="buy_offer_3" class="form-control" placeholder="Buy Offer 3" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 4</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_4" id="buy_offer_4" class="form-control" placeholder="Buy Offer 4"  />
                                        </div>
                                    </div>

                                    <h4 style="text-align: center;">Sell Offer Prices</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 1</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_1" id="sell_offer_1" class="form-control" placeholder="Sell Offer 1"  />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 2</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_2" id="sell_offer_2" class="form-control" placeholder="Sell Offer 2" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 3</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_3" id="sell_offer_3" class="form-control" placeholder="Sell Offer 3" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 4</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_4" id="sell_offer_4" class="form-control" placeholder="Sell Offer 4"  />
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">Range for From Currency</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum value - From currency</label>
                                        <div class="col-md-4">
										<input type="text" name="minval_from_currency" id="minval_from_currency" class="form-control" placeholder="Minimum value - From currency" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum value - From currency</label>
                                        <div class="col-md-4">
										<input type="text" name="maxval_from_currency" id="maxval_from_currency" class="form-control" placeholder="Maximum value - From currency" value="" />
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">Range for To Currency</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum value - To currency</label>
                                        <div class="col-md-4">
										<input type="text" name="minval_to_currency" id="minval_to_currency" class="form-control" placeholder="Minimum value - To currency" value="" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum value - To currency</label>
                                        <div class="col-md-4">
										<input type="text" name="maxval_to_currency" id="maxval_to_currency" class="form-control" placeholder="Maximum value - To currency" value="" />
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Online Price</label>
                                        <div class="col-md-4">
										<input type="text" name="online_price" id="online_price" class="form-control" placeholder="Online Price"  />
                                        </div>
                                    </div>
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
						<?php $attributes=array('class'=>'form-horizontal','id'=>'exchange_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                   <div class="form-group">
                                        <label class="col-md-4 control-label">From Symbol</label>
                                        <div class="col-md-4">
										<select id="from_name" name="from_name" onchange="change_to_symbol(this.value)" class="form-control" placeholder="Pair Name">
										<option value="">Select</option>
										<?php foreach($currency->result() as $cur){ ?>
										<option <?php if($pair->from_symbol_id==$cur->id){echo 'selected';}?> value="<?php echo $cur->id;?>"><?php echo $cur->currency_name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">To Symbol</label>
                                        <div class="col-md-4">
										<select id="to_name" name="to_name" class="form-control" placeholder="Pair Name">
										<option value="">Select</option>
										<?php foreach($old_pairs->result() as $cur){ ?>
										<option <?php if($pair->to_symbol_id==$cur->id){echo 'selected';}?> value="<?php echo $cur->id;?>"><?php echo $cur->currency_name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<!-- <div class="form-group">
                                        <label class="col-md-4 control-label">Rate</label>
                                        <div class="col-md-4">
										<input type="text" name="rate_value" id="rate_value" class="form-control" placeholder="Rate" value="<?php echo $pair->rate_value; ?>" />
                                        </div>
                                    </div> -->
									<div class="form-group">
                                        <label class="col-md-4 control-label">Commision Type</label>
                                        <div class="col-md-4">
										<select id="commision_type" name="commision_type" class="form-control" placeholder="Pair Name">
										<option value="">Select</option>
										<option <?php if($pair->commision_type=='Percent'){echo 'selected';}?> value="Percent">Percent</option>
										<option <?php if($pair->commision_type=='Amount'){echo 'selected';}?> value="Amount">Amount</option>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Commision Value</label>
                                        <div class="col-md-4">
										<input type="text" name="commision_value" id="commision_value" class="form-control" placeholder="Commision Value" value="<?php echo $pair->commision_value; ?>" />
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">Buy Offer Prices</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 1</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_1" id="buy_offer_1" class="form-control" placeholder="Buy Offer 1" value="<?php echo $pair->buy_offer_1; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 2</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_2" id="buy_offer_2" class="form-control" placeholder="Buy Offer 2" value="<?php echo $pair->buy_offer_2; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 3</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_3" id="buy_offer_3" class="form-control" placeholder="Buy Offer 3" value="<?php echo $pair->buy_offer_3; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Buy Offer 4</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_offer_4" id="buy_offer_4" class="form-control" placeholder="Buy Offer 4" value="<?php echo $pair->buy_offer_4; ?>" />
                                        </div>
                                    </div>

                                    <h4 style="text-align: center;">Sell Offer Prices</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 1</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_1" id="sell_offer_1" class="form-control" placeholder="Sell Offer 1" value="<?php echo $pair->sell_offer_1; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 2</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_2" id="sell_offer_2" class="form-control" placeholder="Sell Offer 2" value="<?php echo $pair->sell_offer_2; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 3</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_3" id="sell_offer_3" class="form-control" placeholder="Sell Offer 3" value="<?php echo $pair->sell_offer_3; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Sell Offer 4</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_offer_4" id="sell_offer_4" class="form-control" placeholder="Sell Offer 4" value="<?php echo $pair->sell_offer_4; ?>" />
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">Range for From Currency</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum value - From currency</label>
                                        <div class="col-md-4">
										<input type="text" name="minval_from_currency" id="minval_from_currency" class="form-control" placeholder="Minimum value - From currency" value="<?php echo $pair->minval_from_currency; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum value - From currency</label>
                                        <div class="col-md-4">
										<input type="text" name="maxval_from_currency" id="maxval_from_currency" class="form-control" placeholder="Maximum value - From currency" value="<?php echo $pair->maxval_from_currency; ?>" />
                                        </div>
                                    </div>
                                    <h4 style="text-align: center;">Range for To Currency</h4>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Minimum value - To currency</label>
                                        <div class="col-md-4">
										<input type="text" name="minval_to_currency" id="minval_to_currency" class="form-control" placeholder="Minimum value - To currency" value="<?php echo $pair->minval_to_currency; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Maximum value - To currency</label>
                                        <div class="col-md-4">
										<input type="text" name="maxval_to_currency" id="maxval_to_currency" class="form-control" placeholder="Maximum value - To currency" value="<?php echo $pair->maxval_to_currency; ?>" />
                                        </div>
                                    </div>
                                    <br/>

                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Online Price</label>
                                        <div class="col-md-4">
										<input type="text" name="online_price" id="online_price" class="form-control" placeholder="Online Price" value="<?php echo $pair->online_price; ?>" />
                                        </div>
                                    </div>

									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select id="status" name="status" class="form-control">
										<option <?php if($pair->status==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($pair->status==0){echo 'selected';}?> value="0">De-active</option>
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
		$('#exchange_pair').validate({
			rules: {
				from_name: {
					required: true
				},
				to_name: {
					required: true
				},
				rate_value: {
					required: true,
					number: true
				},
				commision_type: {
					required: true
				},
				commision_value: {
					required: true,
					number: true
				},
				buy_offer_1: {
					required: true,
					number: true
				},
				buy_offer_2: {
					required: true,
					number: true
				},
				buy_offer_3: {
					required: true,
					number: true
				},
				buy_offer_4: {
					required: true,
					number: true
				},
				sell_offer_1: {
					required: true,
					number: true
				},
				sell_offer_2: {
					required: true,
					number: true
				},
				sell_offer_3: {
					required: true,
					number: true
				},
				sell_offer_4: {
					required: true,
					number: true
				},
				minval_from_currency: {
					required: true,
					number: true
				},
				minval_to_currency: {
					required: true,
					number: true,
				},
				maxval_from_currency: {
					required: true,
					number: true,
                    min: function() {
                            return parseFloat($('#minval_from_currency').val())+parseFloat(0.01);
                    },
				},
				maxval_to_currency: {
					required: true,
					number: true,
                    min: function() {
                            return parseFloat($('#minval_to_currency').val())+parseFloat(0.01);
                    },
				},
				online_price: {
					number: true
				},
			},
			highlight: function (element) {
				//$(element).parent().addClass('error')
			},
			unhighlight: function (element) {
				$(element).parent().removeClass('error')
			}
		});

        $.validator.addMethod("from_greater", function(value, element) {
            var min = $('#minval_from_currency').val();
            var max = $('#maxval_from_currency').val();
            return max > min
        }, "Must be greater than Minimum value - From currency");

        $.validator.addMethod("to_greater", function(value, element) {
            var mins = $('#minval_to_currency').val();
            var maxs = $('#maxval_to_currency').val();
            return maxs > mins
        }, "Must be greater than Minimum value - To currency");

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
	  var admin_url='<?php echo admin_url(); ?>';
	 // alert(admin_url);
	 
	  function change_to_symbol(from_symbol)
	  {
		  if(from_symbol!='')
		  {
			 $.ajax({
					url: '<?php echo admin_url();?>'+"pair/get_to_symbol", 
					type: "POST",             
					data: 'from_symbol='+from_symbol+'&to_symbol='+to_symbol_id,
					cache: false,             
					processData: false,      
					success: function(data) {
						$("#to_name").html(data);
					}
				});
		  }
		  else
		  {
			  $("#to_name").html('<option value="">Select</option>');
		  }
	  }
	
    </script>
	<?php
	if(isset($to_symbol)){
	?>
	<script>
	var to_symbol_id='<?php echo $to_symbol;?>';
	</script>
	<?php	
	}else{
	?>
	<script>
	var to_symbol_id=0;
	</script>
	<?php } ?>