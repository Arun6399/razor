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
				<li class="active">Withdraw</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Crypto Fiat Withdraw Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Fiat Withdraw Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                        
						<br/><br/>
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">User Email</th>
										<th class="text-center">Date & Time</th>
										<th class="text-center">Currency</th>
                                        <th class="text-center">Fiat</th>
										<th class="text-center">Amount</th>
										<th class="text-center">Fees</th>
										<th class="text-center">Send Amount</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								/*if ($withdraw->num_rows() > 0) {
									if($this->uri->segment(4)!=''){ $i = $this->uri->segment(4)+1; }else { $i = 1; }
									foreach($withdraw->result() as $result) {

										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $result->username . '</td>';
										echo '<td>' . gmdate("d-m-Y h:i:s", $result->datetime) . '</td>';
										echo '<td>' . $result->currency_symbol . '</td>';
										echo '<td>' . $result->amount . '</td>';
										echo '<td>' . $result->fee . '</td>';
										echo '<td>' . $result->transfer_amount . '</td>';
										echo '<td>' . $result->status . '</td>';
										echo '<td>';
										echo '<a href="' . admin_url() . 'withdraw/view/' . $result->trans_id . '" title="Edit this withdraw"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';

										echo '</tr>';
										$i++;
									}					
								}  */
								?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
					<?php }else{ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'withdraw');
				echo form_open_multipart($action,$attributes); 
										$country = get_countryname($user_bankdetails->bank_country);
										//print_r($country);
				?>
                                <fieldset>
                                  
								<div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $withdraw->username; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Currency</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $withdraw->currency_symbol; ?>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Requested Amount</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $withdraw->amount; ?>
                                </div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Fiat Amount</label>
                                <div class="col-md-8 control-label text-left">
                                <?php echo $withdraw->fiat_amount; ?>
                                </div>
                                </div>


                                <div class="form-group">
                                <label class="col-md-2 control-label">Fees</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $withdraw->fee; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Transfer Amount</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $withdraw->transfer_amount; ?>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Requested On</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo gmdate("d-m-Y h:i a", strtotime($withdraw->datetime)); ?>
                                </div>
                            	</div>
                                <h4 style="text-align: center"> Bank Details </h4>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Account Number</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $user_bankdetails->bank_account_number; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Swift</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $user_bankdetails->bank_swift; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Account Name</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $user_bankdetails->bank_account_name; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Name</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $user_bankdetails->bank_name; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Address</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $user_bankdetails->bank_address; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Postal code</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $user_bankdetails->bank_postalcode; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank City</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $user_bankdetails->bank_city; ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Bank Country</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $country; ?>
                                </div>
                                </div>
                                <hr>
                                <?php if($withdraw->status=='Pending' && $withdraw->payment_method=='Crypto-Fiat'){ 
                                    ?>
                                <a class="btn btn-small btn-success" href="<?php echo admin_url();?>withdraw/crypto_fiat_confirm/<?php echo $withdraw->trans_id;?>" onclick="return confirm('Are you sure you want to  Confrim?');" style='margin-left:20px;'>Confirm</a>

                                <a class="btn btn-small btn-danger" href="#myModal"  data-toggle="modal" style='margin-left:20px;'>Reject</a>
                                <!-- New code 11-5-18 -->
                                <?php }
                                ?>
                                
                                </fieldset>
                            </form>
                        </div>
					<?php } ?> 
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
             <center><?php echo form_open(admin_url().'withdraw/reject/'.$withdraw->trans_id); ?>
			<textarea name="mail_content" class="form-control" required></textarea>
			<br/>
			<button class="btn btn-small btn-danger" style='margin-left:20px;'>REJECT</button>
			<?php echo form_close(); ?></center>
            </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
    </div>
    </div>
    </div>


    <div id="myModal1" class="modal fade adm_pop">
	<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header grn_mdh">
          <button type="button" class="close" data-dismiss="modal"><span class="fa fa-close"></span></button>
          <h4>Transaction Id</h4>
        </div>
        <div class="modal-body">
            <div class="paddingT15 paddingB15"> 
             <center><?php echo form_open(admin_url().'withdraw/status/'.$withdraw->trans_id); ?>
			<textarea name="transaction_id" class="form-control" required></textarea>
			<br/>
			<button class="btn btn-small btn-success" style='margin-left:20px;'>Complete</button>
			<?php echo form_close(); ?></center>
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
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	$(document).ready(function() {
		
		$('#cms').validate({
			rules: {
				heading: {
					required: true
				},
				title: {
					required: true
				},
				meta_keywords: {
					required: true,
				},
				meta_description: {
					required: true
				},
				content_description: {
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
	<script>
		$(document).ready(function() {
			App.init();
		});
		var admin_url='<?php echo admin_url(); ?>';
		
$(document).ready(function() {
    $('#datas-table').DataTable( {
    	"responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "ordering": false,
        // "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"withdraw/cryptofiat_withdraw_ajax"
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
