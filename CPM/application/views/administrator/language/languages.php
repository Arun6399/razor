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
				<li class="active">Languages</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Languages Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Languages Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="data-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">Name</th>
										<th class="text-center">Status</th>
										<th class="text-center">Edit File</th>
										<!-- //vv -->
										<th class="text-center">Edit File2</th>
							            <!-- //vv -->
							            <th class="text-center">Add Value</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
								if ($languages->num_rows() > 0) {
									$i = 1;
									foreach($languages->result() as $result) {
										echo '<tr>';
										echo '<td class="text-center">' . $i . '</td>';
										echo '<td class="text-center">' . $result->name .'</td>';
										if ($result->status == 1) {
											$status = '<label class="label label-info">Activated</label>';
											$extra = array('title' => 'De-activate this Language');
											$changeStatus = anchor(admin_url().'language/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
										} else {
											$status = '<label class="label label-danger">De-Activated</label>';
											$extra = array('title' => 'Activate this Language');
											$changeStatus = anchor(admin_url().'language/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
										}
										echo '<td class="text-center">'.$status.'</td>';
//
										echo '<td class="text-center"><a href="' . admin_url() . 'language/edit_file/' . $result->id . '" title="Edit this Language File"><i class="fa fa-pencil text-primary"></i></a></td>';
//
//vv 
							            echo '<td class="text-center"><a href="' . admin_url() . 'language/edit_formvalid_file/' . $result->id . '" title="Edit this Language File"><i class="fa fa-pencil text-primary"></i></a></td>';
//vv


										echo '<td class="text-center"><a href="' . admin_url() . 'language/add_file/' . $result->id . '" title="Add Extra Column">Add Extra Column</a></td>';
										echo '<td class="text-center">';
										echo $changeStatus . '&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'language/edit/' . $result->id . '" title="Edit this Language"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '<a href="' . admin_url() . 'language/delete/' . $result->id . '" title="Delete this Language"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td colspan="4" class="text-center">No Language added yet!</td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php }else if($view=='add'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'language');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Language Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="Language Name" value="" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Language ISO Code</label>
                                        <div class="col-md-4">
										<input type="text" name="code" id="code" class="form-control" placeholder="Language Code" value="" />
                                        </div>
										<label class="col-md-4">Refer This Link : <a target="_blank" href="https://www.w3schools.com/tags/ref_language_codes.asp">Language Codes</a></label>
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
					<?php }else if($view=='edit'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'language');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Language Name</label>
                                        <div class="col-md-4">
										<input type="text" name="name" id="name" class="form-control" placeholder="Language Name" value="<?php echo $language->name;?>" readonly />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">Status</label>
                                        <div class="col-md-4">
										<select id="status" name="status" class="form-control">
										<option <?php if($language->status==1){echo 'selected';}?> value="1">Active</option>
										<option <?php if($language->status==0){echo 'selected';}?> value="0">De-active</option>
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

					<?php }else if($view=='edit_file'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'language');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
									<?php 
									$autoinc=0;
									foreach ($lang_input as $key => $value) {
										if($autoinc%3==0&&$autoinc!=0){	?>									
										</div><div class="row" style="margin-left:5px">
										<?php }if($autoinc==0){	?>									
										<div class="row" style="margin-left:5px">
										<?php } ?>
										 <div class="col-md-3" style="margin-left:50px">
													<div class="form-group">
														<label><?php echo ucfirst($key); ?></label>
														<input type="text" name="<?php echo $key; ?>" class="form-control" placeholder="<?php echo ucfirst($key); ?>" value="<?php echo $value; ?>">
													</div>
                                                </div>
									<?php $autoinc++; } ?>
									</div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <!-- //vv -->
                        <?php }else if($view=='edit_formvalid_file'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'language');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
									<?php 
									$autoinc=0;
									foreach ($lang_input as $key => $value) {
										if($autoinc%3==0&&$autoinc!=0){	?>									
										</div><div class="row" style="margin-left:5px">
										<?php }if($autoinc==0){	?>									
										<div class="row" style="margin-left:5px">
										<?php } ?>
										 <div class="col-md-3" style="margin-left:50px">
													<div class="form-group">
														<label><?php echo ucfirst($key); ?></label>
														<input type="text" name="<?php echo $key; ?>" class="form-control" placeholder="<?php echo ucfirst($key); ?>" value="<?php echo $value; ?>">
													</div>
                                                </div>
									<?php $autoinc++; } ?>
									</div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
					
					<!-- //vv -->
					<?php }else if($view=='add_file'){ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'language');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>								
										<div class="row" style="margin-left:5px">
										 <div class="col-md-5">
													<div class="form-group">
														<label>Key(Use _ Instead of Space & Don't Use Exisiting Keys)</label>
														<input type="text" name="key" class="form-control" placeholder="Key" value="">
													</div>
										</div>
										<div class="col-md-1"></div>
										 <div class="col-md-5">
											<div class="form-group">
												<label>Value</label>
												<input type="text" name="value" class="form-control" placeholder="Value" value="">
											</div>
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
				},
				taker: {
					required: true,
					number: true
				}
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