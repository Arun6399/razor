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
			<h1 class="page-header">Trade Pair Management <!--<small>header small text goes here...</small>--></h1>
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
                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Trade Pair Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
										<th class="text-center">Pair Names</th>
										<th class="text-center">Pair Symbols</th>
										<th class="text-center">Trade Buy Rate</th>
										<th class="text-center">Trade Sell Rate</th>
										<th class="text-center">Trade Minimum Amount</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='view_all_fees'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Volume</th>
										<th class="text-center">Maker</th>
										<th class="text-center">Taker</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($trade_fees->num_rows() > 0) {
									$i = 1;
									foreach($trade_fees->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->from_volume . ' to '. $result->to_volume .'</td>';
										echo '<td>' . $result->maker .'</td>';
										echo '<td>' . $result->taker .'</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
											$extra = array('title' => 'De-activate this pair fees');
											$changeStatus = anchor(admin_url().'trade_pairs/status_fees/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
											$extra = array('title' => 'Activate this pair fees');
											$changeStatus = anchor(admin_url().'trade_pairs/status_fees/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
										}
										echo '<td>'.$status.'</td>';
										echo '<td>';
										echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'trade_pairs/fees/' . $result->id . '" title="Edit this pair fees"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'trade_pairs/delete_fees/' . $result->id . '" title="Delete this pair fees"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td colspan="2" class="text-center">No Trade Pair fees added yet!</td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='add'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">From Currency</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="from_name" name="from_name" onchange="change_to_symbol(this.value)" class="selectpicker form-control" placeholder="Pair Name">
										<option value="">Select</option>
										<?php foreach($currency->result() as $cur){ ?>
										<option value="<?php echo $cur->id;?>"><?php echo $cur->currency_name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">To Currency</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="to_name" name="to_name" class="form-control " placeholder="Pair Name">
										<option value="">Select</option>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Buy Rate</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_rate_value" id="buy_rate_value" class="form-control" placeholder="Rate" value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Sell Rate</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_rate_value" id="sell_rate_value" class="form-control" placeholder="Rate" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Trade Minimum Amount</label>
                                        <div class="col-md-4">
										<input type="text" name="trade_min_amt" id="trade_min_amt" class="form-control" placeholder="Rate" value="" />
                                        </div>
                                    </div>

									<!--<div class="form-group">
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
                                    </div>-->

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">API Fetch</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="data_fetch" name="data_fetch" class="selectpicker form-control">
										<option  value="1">Active</option>
										<option  value="0">De-active</option>
										</select>
                                        </div>
                                    </div> 
                                       <div class="form-group">
                                        <label class="col-md-4 control-label">Random Amount</label>
                                        <div class="col-md-2">
										<input type="text" name="bot_min_amount" id="bot_min_amount" class="form-control" value="" />
                                        </div>
                                        <div class="col-md-2">
										<input type="text" name="bot_max_amount" id="bot_max_amount" class="form-control" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Random price % range</label>
                                        <div class="col-md-2">
										<input type="text" name="bot_minprice_per" id="bot_minprice_per" class="form-control" value="" />
                                        </div>
                                        <div class="col-md-2">
										<input type="text" name="bot_maxprice_per" id="bot_maxprice_per" class="form-control" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Random time interval seconds</label>
                                        <div class="col-md-2">
										<input type="text" name="bot_time_min" id="bot_time_min" class="form-control" value="" />
                                        </div>
                                        <div class="col-md-2">
										<input type="text" name="bot_time_max" id="bot_time_max" class="form-control" value="" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Bot Status</label>
                                        <div class="col-md-4">
										<select id="bot_status" name="bot_status" class="form-control">
										<option value="1">Active</option>
										<option value="0">De-active</option>
										</select>
                                        </div>
                                    </div>

									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="status" name="status" class="form-control selectpicker">
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
					<?php }else if($view=='edit'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'trade_pair');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                   <div class="form-group">
                                        <label class="col-md-4 control-label">From Currency</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="from_name" name="from_name" onchange="change_to_symbol(this.value)" class="selectpicker form-control" placeholder="Pair Name">
										
										<option value="">Select</option>
										<?php foreach($currency->result() as $cur){ ?>
										<option <?php if($pair->from_symbol_id==$cur->id){echo 'selected';}?> value="<?php echo $cur->id;?>"><?php echo $cur->currency_name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">To Currency</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="from_name" name="from_name" onchange="change_to_symbol(this.value)" class="selectpicker form-control" placeholder="Pair Name">
										
										<option value="">Select</option>
										<?php foreach($old_pairs->result() as $cur){ ?>
										<option <?php if($pair->to_symbol_id==$cur->id){echo 'selected';}?> value="<?php echo $cur->id;?>"><?php echo $cur->currency_name;?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Buy Rate</label>
                                        <div class="col-md-4">
										<input type="text" name="buy_rate_value" id="buy_rate_value" class="form-control" placeholder="Rate" value="<?php echo $pair->buy_rate_value; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Sell Rate</label>
                                        <div class="col-md-4">
										<input type="text" name="sell_rate_value" id="sell_rate_value" class="form-control" placeholder="Rate" value="<?php echo $pair->sell_rate_value; ?>" />
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-4 control-label">Trade Minimum Amount</label>
                                        <div class="col-md-4">
										<input type="text" name="trade_min_amt" id="trade_min_amt" class="form-control" placeholder="Rate" value="<?php echo $pair->min_trade_amount; ?>" />
                                        </div>
                                    </div>
									<!--<div class="form-group">
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
                                    </div>-->

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">API Fetch</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="from_name" name="from_name" onchange="change_to_symbol(this.value)" class="selectpicker form-control" placeholder="Pair Name">
										
										<option <?php if($pair->data_fetch==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($pair->data_fetch==0){echo 'selected';}?> value="0">De-active</option>
										</select>
                                        </div>
                                    </div>

									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select data-live-search="true" id="from_name" name="from_name" onchange="change_to_symbol(this.value)" class="selectpicker form-control" placeholder="Pair Name">
										
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
					<?php }else if($view=='edit_fees'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'','id'=>'trade_pair_fees');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
								<div class="row">
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="volume">From Volume</label>
                                        <input type="text" class="form-control" name="from_volume" value="<?php echo $fees->from_volume; ?>" id="from_volume" placeholder="Enter Volume" />
                                    </div>
								</div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="volume">To Volume</label>
                                        <input type="text" class="form-control" name="to_volume" value="<?php echo $fees->to_volume; ?>" id="to_volume" placeholder="Enter Volume" />
                                    </div>
								</div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="maker">Maker</label>
                                        <input type="text" class="form-control" id="maker" name="maker" value="<?php echo $fees->maker; ?>" placeholder="Maker Fees" />
                                    </div>
								</div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="taker">Taker</label>
                                        <input type="text" class="form-control" id="taker" name="taker" value="<?php echo $fees->taker; ?>" placeholder="Taker Fees" />
                                    </div>
								</div>
								</div>
                                    <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                </fieldset>
                            </form>
                        </div>
					<?php }else{ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'','id'=>'trade_pair_fees');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
								<div class="row">
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="volume">From Volume</label>
                                        <input type="text" class="form-control" name="from_volume" id="from_volume" placeholder="Enter Volume" />
                                    </div>
								</div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="volume">To Volume</label>
                                        <input type="text" class="form-control" name="to_volume" id="to_volume" placeholder="Enter Volume" />
                                    </div>
								</div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="maker">Maker</label>
                                        <input type="text" class="form-control" id="maker" name="maker" placeholder="Maker Fees" />
                                    </div>
								</div>
								<div class="col-md-3">
                                    <div class="form-group">
                                        <label for="taker">Taker</label>
                                        <input type="text" class="form-control" id="taker" name="taker" placeholder="Taker Fees" />
                                    </div>
								</div>
								</div>
                                    <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
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
		$('#trade_pair').validate({
			rules: {
				from_name: {
					required: true
				},
				to_name: {
					required: true
				},
				buy_rate_value: {
					required: true,
					number: true
				},
				sell_rate_value: {
					required: true,
					number: true
				},
				// commision_type: {
					// required: true
				// },
				// commision_value: {
					// required: true,
					// number: true
				// }
			},
			highlight: function (element) {
				//$(element).parent().addClass('error')
			},
			unhighlight: function (element) {
				$(element).parent().removeClass('error')
			}
		});
		$('#trade_pair_fees').validate({
			rules: {
				from_volume: {
					required: true,
					number: true
				},
				to_volume: {
					required: true,
					number: true
				},
				maker: {
					required: true,
					number: true
				}/*,
				taker: {
					required: true,
					number: true
				}*/
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
			var admin_url='<?php echo admin_url(); ?>';
		
	$(document).ready(function() {
  
        var t = $('#datas-table').DataTable( {
        "columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        responsive : true,
    	"processing" : true,
    	"lengthChange": true,
        "pageLength" : 10,
     	"serverSide": true,
     	"searching": true,
        "order": [[ 1, 'asc' ]],
        "ajax": admin_url+"trade_pairs/tradepairs_ajax"
    } );
                 
    t.on( 'draw.dt', function () {
    var PageInfo = $('#datas-table').DataTable().page.info();
         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
            cell.innerHTML = i + 1 + PageInfo.start;
        } );
    } );
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
	<script>
      
	  var admin_url='<?php echo admin_url(); ?>';
	 // alert(admin_url);
	 
	  function change_to_symbol(from_symbol)
	  {
	  	
		  if(from_symbol!='')
		  {
			 $.ajax({
					url: '<?php echo admin_url();?>'+"trade_pairs/get_to_symbol", 
					type: "POST",             
					data: 'from_symbol='+from_symbol+'&to_symbol='+to_symbol_id,
					//cache: false,             
					processData: true,      
					success: function(data) { 
						//$("#to_name").addClass("selectpicker");
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