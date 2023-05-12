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
				<li class="active">Token request</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Token request Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Token request Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                       
						<br/> <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tokens">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Username</th>
										<th class="text-center">Date & Time</th>
										<th class="text-center">Currency</th>
										<th class="text-center">Amount</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								if ($token->num_rows() > 0) {
								$i = 1; 
									foreach($token->result() as $result) {
                                        $user = getUserDetails($result->user_id);
                                        if($user->usertype==1)
                                        {
                                            $usertype ="Personal";
                                            $username = 'bidex_username';
                                        }
                                        else 
                                        {
                                            $usertype ="Company";
                                            $username = 'company_name';
                                        }
										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . $user->$username . '</td>';
										echo '<td>' . date("d-m-Y h:i:s", strtotime($result->date_added)) . '</td>';
										echo '<td>' . $result->currency_name . '</td>';
										echo '<td>' . $result->total_amount . '</td>';
										echo '<td>' . $result->status . '</td>';
										echo '<td>';
										echo '<a href="' . admin_url() . 'token_request/view/' . $result->id . '" title="Confirm this request"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';

										echo '</tr>';
										$i++;
									}					
								}  else {
                                    echo '<tr>';
                                    echo '<td colspan="9">' . 'No Records Found!!' . '</td>';
                                    echo '</tr>';
                                }
								?>
                                    </tbody>
                                </table>
                               
                            </div>
                        </div>
					<?php }else{ ?>
					<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'token_request');
				        echo form_open_multipart($action,$attributes);
				            ?>
                                <fieldset>
                                  
								<div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo getUserDetails($token->user_id,'bidex_username'); ?>
                                </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-2 control-label">Currency</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $token->currency_name; ?>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Requested Amount</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo $token->total_amount; ?>
                                </div>
                                </div>
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Requested On</label>
                                <div class="col-md-8 control-label text-left">
								<?php echo date("d-m-Y h:i:s", strtotime($token->date_added)); ?>
                                </div>
                            	</div>
                                <?php if($token->mode=="bank_wire") { ?>
                               
                                <div class="form-group">
                                <label class="col-md-2 control-label">Transaction Proof</label>
                                <div class="col-md-8 control-label text-left">
                                <img src="<?php echo $token->transaction_proof; ?>">
                                </div>
                                </div>
                                <?php } ?>
                                <?php if($token->status=='Pending'){ ?>
                                <a class="btn btn-small btn-success" href="<?php echo admin_url();?>admin/token_confirm/<?php echo base64_encode($token->id);?>"  style='margin-left:20px;'>Confirm</a>
                                <?php } ?>
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

        $(document).ready(function() {
         $.fn.dataTableExt.sErrMode = 'throw';
         $('#tokens').DataTable();
        } );

		function search() {
    		var search = $('#search_string').val();
    		var url = '<?php echo admin_url(); ?>';
    		if(search!=''){
    		window.location.href=url+'token_request/?search_string='+search; }
    		else { window.location.href=url+'token_request'; }
		}
	</script>
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');
      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');
    </script>
