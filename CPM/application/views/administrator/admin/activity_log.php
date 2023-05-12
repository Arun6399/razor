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
				<li class="active">Activity Log</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Activity Log <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Activity Log</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                           <th class="text-center">S.No</th>
										<th class="text-center">User/Admin Id</th>
										<th class="text-center">Ip Address</th>
										<th class="text-center">Auction</th>
										<th class="text-center">Date & Time</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								if ($log->num_rows() > 0) {
									if($this->uri->segment(4)!=''){ $i = $this->uri->segment(4)+1; }else { $i = 1; }
									foreach($log->result() as $result) {
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										if($result->user_id == 'No') {
										echo '<td>' . $result->admin_id . '</td>'; } elseif($result->user_id == '0') { echo '<td>' . 'ADMIN' . '</td>'; } else{
										echo '<td>' . $result->user_id . '</td>';
										}
										if($result->ip_address!='') {
										echo '<td>' . $result->ip_address . '</td>'; } else 
										{ echo '<td>' . '-' . '</td>'; }
										echo '<td>' . $result->auction . '</td>';
										echo '<td>' . $result->date_time . '</td>';
										echo '<td>';
										echo '<a href="' . admin_url() . 'admin/view_activitylog/' . $result->audit_id . '" title="View"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									//echo '<tr><td></td><td></td><td colspan="2" class="text-center">No Activity Log yet!</td><td></td><td></td><td></td></tr>';
								}
								?>
                                    </tbody>
                                </table>
                                <div class="pagination">
                                 <?php echo $this->pagination->create_links(); ?>
                                 </div>
                            </div>
                        </div>
					<?php } else { ?> 
					<div class="panel-body">
                        <fieldset>
                        <div class="form-group clearfix">
                                <label class="col-md-2 control-label">User/Admin</label>
                                <div class="col-md-8 control-label text-left">
								<?php if($log->admin_id!=0){ echo 'Admin'; } elseif($log->user_id==0) { echo 'Admin'; } else { echo 'User'; } ?> 
                                </div>
                        </div>
                        <div class="form-group clearfix">
                                <label class="col-md-2 control-label">UserName</label>
                                <div class="col-md-8 control-label text-left">
								<?php if($log->admin_id!=0){ echo '-'; } elseif($log->user_id==0) { echo '-'; } else { 
										$user = getUserDetails($log->user_id);
										$prefix = get_prefix();
										$usernames = $prefix.'username';
										$username = $user->$usernames;
									echo $username; } ?> 
                                </div>
                        </div>
                         <div class="form-group clearfix">
                                <label class="col-md-2 control-label">Ip Address</label>
                                <div class="col-md-8 control-label text-left">
								<?php if($log->ip_address!=''){ echo $log->ip_address; } else { echo '-'; } ?>
                                </div>
                        </div>
                      	<div class="form-group clearfix">
                                <label class="col-md-2 control-label">Auction</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $log->auction; ?>
                                </div>
                        </div>
                        <div class="form-group clearfix">
                                <label class="col-md-2 control-label">Message</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $log->message; ?>
                                </div>
                        </div>
                         <div class="form-group clearfix">
                                <label class="col-md-2 control-label">Date & Time</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $log->date_time; ?>
                                </div>
                        </div>
                        
                        </fieldset>
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
