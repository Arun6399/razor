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
		<li class="active">User Bank Details</li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">User Bank Details <!--<small>header small text goes here...</small>--></h1>
	<p class="text-right m-b-10"></p>
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
                    <h4 class="panel-title">User Bank Details</h4>
                </div>
				<?php 
					if($view=='view_all')
					{ 
				?>
		                <div class="panel-body">
		                    <div class="table-responsive">
		                        <table id="datas-table" class="table table-striped table-bordered">
		                            <thead>
		                                <tr>
		                                   	<th class="text-center">S.Nox</th>
		                                   	<th class="text-center">User Email</th>
		                                   	<th class="text-center">Currency</th>
											<th class="text-center">Bank Name</th>
											<th class="text-center">Account Number</th>
											<th class="text-center">Bank Account Name</th>
											<th class="text-center">Status</th>
											<!-- <th class="text-center">Reason</th> -->
											<th class="text-center">Action</th>
		                                </tr>
		                            </thead>
		                            <tbody style="text-align: center;">
									<?php
										/*if ($user_bank->num_rows() > 0) 
										{
											$i = 1;
											foreach(array_reverse($user_bank->result()) as $result) 
											{

                                               $status = $result->status;

												$currency_symbol = getfiatcurrency($result->currency);
												echo '<tr>';
												echo '<td>' . $i . '</td>';
												echo '<td>' . $result->username . '</td>';
												echo '<td>' . $currency_symbol . '</td>';
												echo '<td>' . $result->bank_name . '</td>';
												echo '<td>' . $result->bank_account_number . '</td>';
												echo '<td>' . $result->bank_account_name . '</td>';
												echo '<td>' . $status . '</td>';
												//echo '<td>' . $result->reason . '</td>';
												echo '<td>';
												echo '<a href="' . admin_url() . 'admin/view/' . $result->id . '" title="Edit this Bank"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
												echo '</td>';
												echo '</tr>';
												$i++;
											}					
										} */
									?>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
				<?php 
					}  else {
				?>
						<div class="panel-body">
							<?php 
								$attributes=array('class'=>'form-horizontal','id'=>'user_bank_details');
								echo form_open_multipart($action,$attributes); 
							?>
                        		<fieldset>
                          			<div class="form-group">
		                                <label class="col-md-2 control-label">Currency</label>
		                                <div class="col-md-8">
		  <?php $get_users = $this->common_model->getTableData("currency",array('id'=>$user_bankdetails->currency))->row();
		  echo strtoupper($get_users->currency_symbol);

		  ?>

                                		</div>
                            		</div>
									<div class="form-group">
		                                <label class="col-md-2 control-label">Bank Account Number</label>
		                                <div class="col-md-8">
											<?php echo $user_bankdetails->bank_account_number; ?>
		                                </div>
		                            </div>
								<!-- 	<div class="form-group">
		                                <label class="col-md-2 control-label">Bank Swift</label>
		                                <div class="col-md-8">
											<?php echo $user_bankdetails->bank_swift; ?>
		                                </div>
		                            </div> -->
									<div class="form-group">
		                                <label class="col-md-2 control-label">Bank Account Name</label>
		                                <div class="col-md-8">
											<?php echo $user_bankdetails->bank_account_name; ?>
		                                </div>
		                            </div>
									<div class="form-group">
		                                <label class="col-md-2 control-label">Bank Name</label>
		                                <div class="col-md-8">
											<?php echo $user_bankdetails->bank_name; ?>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <label class="col-md-2 control-label">Bank Address</label>
		                                <div class="col-md-8">
											<?php echo $user_bankdetails->bank_address; ?>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <label class="col-md-2 control-label">Bank Postal Code</label>
		                                <div class="col-md-8">
											<?php echo $user_bankdetails->bank_postalcode; ?>
		                                </div>
		                            </div>
		                            <div class="form-group">
		                                <label class="col-md-2 control-label">Bank City</label>
		                                <div class="col-md-8">
		                              		<?php echo $user_bankdetails->bank_city; ?>
		                                </div>
		                            </div>
                            		<div class="form-group">
		                                <label class="col-md-2 control-label">Country</label>
		                                <div class="col-md-8 control-label text-left">
		                                	<?php 
		                                		$country = get_countryname($user_bankdetails->bank_country);
		                                		echo $country;  
		                                	?>
		                                </div>
                        			</div>
			                         <?php
			                    
                                        if($user_bankdetails->status == 'Pending')
                                        { 
                                    ?>
                                            <a class="btn btn-small btn-success" href="<?php echo admin_url();?>admin/user_bank_status/<?php echo base64_encode($user_bankdetails->id);?>/1"  data-toggle="modal"  style='margin-left:20px;'>Confirm</a>
                                            <a class="btn btn-small btn-danger" href="#myModal"  data-toggle="modal" style='margin-left:20px;'>Reject</a>
                                    <?php 
                                        } 
                                    ?>
			                        <a href="<?php echo admin_url();?>admin/user_bank_details" class="btn btn-sm btn-warning m-r-5" data-toggle="modal"  style='margin-left:20px;'>Back</a>
                        		</fieldset>
                    		<?php echo form_close(); ?>
                		</div>
				<?php 
					} 
				?> 
            </div>
            <!-- end panel -->
        </div>
	</div>
	<!-- end row -->	
    <div id="myModal" class="modal fade adm_pop">
	    <div class="modal-dialog">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span class="fa fa-close"></span></button>
                    <h4>Reason to reject</h4>
                </div>
                <div class="modal-body">
                    <div class="paddingT15 paddingB15"> 
                        <center><?php echo form_open(admin_url().'admin/user_bank_status/'.base64_encode($user_bankdetails->id).'/0'); 
                        ?>
                       		<textarea name="mail_content" class="form-control" required></textarea>
                			<br/>
                			<button class="btn btn-small btn-danger" style='margin-left:20px;'>REJECT</button>
                			<?php echo form_close(); ?>
                        </center>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
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
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
    <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script type="text/javascript">
	$(document).ready(function() {
		App.init();
	});
</script>
<script type="text/javascript">
	$(document).ready(function() 
	{
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
<script type="text/javascript">
  
    var admin_url='<?php echo admin_url(); ?>';
		
$(document).ready(function() {
    $('#datas-table').DataTable( {
    	responsive : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"admin/userbank_ajax"
    });
        });
      
</script>