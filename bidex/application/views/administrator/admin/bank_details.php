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
				<li class="active">Bank Details</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Bank Details <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Bank Details</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Currency</th>
										<th class="text-center">Bank Name</th>
										<th class="text-center">Account Number</th>
										<th class="text-center">Bank Account Name</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								if ($bank->num_rows() > 0) {
									$i = 1;
									foreach(array_reverse($bank->result()) as $result) {
										$cu = getfiatcurrency($result->currency);
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $cu . '</td>';
										echo '<td>' . $result->bank_name . '</td>';
										echo '<td>' . $result->bank_account_number . '</td>';
										echo '<td>' . $result->bank_account_name . '</td>';
										echo '<td>';
										echo '<a href="' . admin_url() . 'admin/edit_bank_details/' . $cu . '" data-placement="top" data-toggle="popover" data-content="Edit this Bank details." class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr><td></td><td></td><td colspan="2" class="text-center">No Records Found!</td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
					<?php } else{ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'bank_details');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                  <div class="form-group">
                                        <label class="col-md-2 control-label">Currency</label>
                                        <div class="col-md-8">
										<select id="currency" name="currency" class="form-control currency_select" Onchange="changeurl()">
										<?php $curr = $this->uri->segment(4);
										foreach($currency as $cu){ ?>
											<option <?php if($cu->currency_symbol==$curr){echo 'selected';}?>   value="<?php echo $cu->id; ?>"><?php echo $cu->currency_symbol; ?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Bank Account Number</label>
                                        <div class="col-md-8">
										<input type="text" name="bank_account_number" id="bank_account_number" class="form-control" placeholder="Bank Account Number" value="<?php echo $bank->bank_account_number; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Bank Swift</label>
                                        <div class="col-md-8">
										<input type="text" name="bank_swift" id="bank_swift" class="form-control" placeholder="Bank Swift" value="<?php echo $bank->bank_swift; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Bank Account Name</label>
                                        <div class="col-md-8">
										<input type="text" name="bank_account_name" id="bank_account_name" class="form-control" placeholder="Bank Account Name" value="<?php echo $bank->bank_account_name; ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-2 control-label">Bank Name</label>
                                        <div class="col-md-8">
										<input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Bank Name" value="<?php echo $bank->bank_name; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Address</label>
                                        <div class="col-md-8">
										<input type="text" name="bank_address" id="bank_address" class="form-control" placeholder="Bank Address" value="<?php echo $bank->bank_address; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank Postal Code</label>
                                        <div class="col-md-8">
										<input type="text" name="bank_postalcode" id="bank_postalcode" class="form-control" placeholder="Bank Postal Code" value="<?php echo $bank->bank_postalcode; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Bank City</label>
                                        <div class="col-md-8">
										<input type="text" name="bank_city" id="bank_city" class="form-control" placeholder="Bank City" value="<?php echo $bank->bank_city; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label"> Bank Country</label>
                                        <div class="col-md-8">
										<select id="bank_country" name="bank_country" class="form-control">
										<?php 
										foreach($countries as $co){ ?>
											<option <?php if($co->id==$bank->bank_country){echo 'selected';}?> value="<?php echo $co->id; ?>"><?php echo $co->country_name; ?></option>
										<?php } ?>
										</select>
                                        </div>
                                    </div>
									
								
                                    <div class="form-group">
                                        <div class="col-md-7 col-md-offset-5">
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
	<script src="<?php echo admin_source();?>/plugins/ckeditor/ckeditor.js"></script>
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
			App.init();
		});
	</script>
	<script>
	$(document).ready(function() {
		
		$('#cms').validate({
			rules: {
				bank_account_number: {
					required: true
				},
				bank_swift: {
					required: true
				},
				bank_account_name: {
					required: true,
				},
				bank_name: {
					required: true
				},
				bank_city: {
					required: true,
				},
				bank_country: {
					required: true,
				},
				bank_postalcode: {
					required: true,
				},
				bank_address: {
					required: true,
				},
				currency: {
					required: true,
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
	
    </script>

    <script>
   function changeurl()
	{
	 var sym = $(".currency_select :selected").text();
	 window.location.href="<?php echo base_url()?>bidex_admin/admin/edit_bank_details/"+sym;
	}
    </script>
