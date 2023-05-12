<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
	<h1 class="page-header text-overflow">Bank Management</h1>
	
	
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->
				
<!--Breadcrumb-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	<ol class="breadcrumb">
		<li><a href="<?php echo admin_url() . 'admin'; ?>">Home</a></li>
		<li class="active">Edit Bank</li>
	</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
				
<!--Page content-->
<!--===================================================-->
<div id="page-content">				
<div class="row">
						<div class="col-sm-offset-2 col-sm-8">
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title">Edit Bank</h3>
								</div>
					
								<!--Block Styled Form -->
								<!--===================================================-->
								<form action="<?php echo $action; ?>" method="post" id="add_bank" enctype="multipart/form-data">
									<div class="panel-body">
										<div class="row">

											<div class="col-sm-12">
												<div class="form-group">
													<label class="control-label">Account Name</label>
													<input name="acc_name" id="acc_name" type="text" class="form-control" value="<?php echo $wallets->acc_name; ?>" >
												</div>
											</div>
											
										</div>
									</div>
										<div class="panel-body">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label class="control-label">Account Number</label>
													<input name="acc_number" id="acc_number" type="text" class="form-control" value="<?php echo $wallets->acc_number; ?>" >
												</div>
											</div>
										</div>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
													<label class="control-label">Branch</label>
													<input name="acc_branch" id="acc_branch" type="text" class="form-control" value="<?php echo $wallets->acc_branch; ?>" >
												</div>
											</div>
										</div>
									</div>

									<div class="panel-footer text-right">
										<button class="btn btn-info" type="submit">Save changes</button>
									</div>
								</form>
								<!--===================================================-->
								<!--End Block Styled Form -->
					
							</div>	
						</div>
						
</div>
</div>
<script>
	// jQuery validation
	$(document).ready(function() {
		$('#add_bank').validate({
			rules: {
				title: {
					required: true
				},
				content: {
					required: true
				}
			}
		});
	});
</script>