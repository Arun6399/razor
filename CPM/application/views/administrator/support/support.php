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
				<li class="active">Support</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Support Management <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Support Management</h4>
                        </div>
					<?php if($view=='view_all'){ ?>
                        <div class="panel-body">
                        
						<br/><br/>
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                         <th class="text-center">Email</th> 
                                        <th class="text-center">Date & Time</th>
                                       
										<th class="text-center">Subject</th>
										<th class="text-center">Status</th>
										<th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									<?php
								/*if ($support->num_rows() > 0) {
									if($this->uri->segment(4)!=''){ $i = $this->uri->segment(4)+1; }else { $i = 1; }
									foreach($support->result() as $result) {

										$user = getUserDetails($result->user_id);
                                        if($user->usertype==1)
                                        {
                                            $usertype ="Personal";
                                            $username = 'cpm_username';
                                        }
                                        else 
                                        {
                                            $usertype ="Company";
                                            $username = 'company_name';
                                        }

										echo '<tr>';
										echo '<td>' . $i . '</td>';
										echo '<td>' . gmdate("Y-m-d h:i:s", $result->created_on) . '</td>';
										echo '<td>' . $user->$username . '</td>';
										echo '<td>' . $result->subject . '</td>';
										if($result->status=='0')
										{
											echo '<td>' . 'Replied' . '</td>';
										}
										else
										{
											echo '<td>' . 'Not Replied' . '</td>';
										}
										
										
										echo '<td>';
										echo '<a href="' . admin_url() . 'support/reply/' . $result->id . '" title="Reply to this support"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
										echo '</td>';
										echo '</tr>';
										$i++;
									}					
								} else {
									echo '<tr>';
									echo '<td colspan="10">' . 'No Records Found!!' . '</td>';
									echo '</tr>';
								} */
								?>
                                    </tbody>
                                </table>
                                 
                            </div>
                        </div>
					<?php }else{ ?>
					<div class="panel-body">
					
                                <fieldset>
								<div class="message_detail">
								<?php $username = getUserDetails($support->user_id,$prefix.'username'); ?>
									<p class="r_name"><strong><?php echo $username; ?></strong></p>
									<p class="m_text"><?php echo $support->message; ?></p> 
									<?php if($support->image!=''){ ?>
										<div class="view_img">
										<img src="<?php echo $support->image; ?>" style="height:80px; width:80px;" >
         						  <a href="<?php echo $support->image; ?>" target="_blank" class="view_btn" download>Click Here To View </a>
										</div>
                                 
                                <?php } ?>
								</div>
                            
                                <?php if(!empty($replies)) { 
                                	foreach($replies as $reply) {
                                	if($reply->user_id!=0){
                                	$username = getUserDetails($reply->user_id,$prefix.'username');
                                	}
                                	else{ $username = "moonex Team"; }
									 ?>
									 <div class="message_detail">
									 <p class="r_name"><strong><?php echo $username; ?></strong></p>
                                	 <p class="m_text"><?php echo $reply->message;?></p>  
									<?php if($reply->image!=''){ ?>
										<div class="view_img">
										<img src="<?php echo $reply->image; ?>" style="height:80px; width:80px;" >
         						<a href="<?php echo $reply->image; ?>" target="_blank" class="view_btn" download>Click Here To View </a>
										</div>
                                
								<?php } ?>
									</div>
                            

                                <?php } } ?>
                                </fieldset>
                    <?php $attributes=array('class'=>'form-horizontal','id'=>'support_reply');
					echo form_open_multipart($action,$attributes); ?>

									<div class="form-group">
                                        <label class="col-md-2 control-label">Reply Message</label>
                                        <div class="col-md-8">
										<textarea class="form-control" id="message" name="message" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Image</label>
                                        <div class="col-md-8">
										<input type="file" id="image" name="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-7 col-md-offset-5">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
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
	<script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
<script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
	$(document).ready(function() {
		
		$('#support_reply').validate({
			rules: {
				message: {
					required: true
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

		var admin_url='<?php echo admin_url(); ?>';
		
$(document).ready(function() {
    $('#datas-table').DataTable( {
    	"responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"support/support_ajax"
    });
        });
	</script>
<?php
	if($view)
	{
		if($view=='view_all'){
		}
		else
		{
			?>
	<script>
        $(document).ready(function() {
            CKEDITOR.replace('message');
        });
    </script>
<?php 	
		}
	}
	
	?>

	 <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>