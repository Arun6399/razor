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
			<li class="active">Contact</li>
		</ol>
		<!-- end breadcrumb -->
		<!-- begin page-header -->
		<h1 class="page-header">Contact <!--<small>header small text goes here...</small>--></h1>
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
                        <h4 class="panel-title">Contact</h4>
                    </div>
					<?php 
						if($view=='view_all')
						{ 
					?>
                    		<div class="panel-body">
                    			
								<br/>
                        		<div class="table-responsive">
                            		<table id="datas-table" class="table table-striped table-bordered">
			                            <thead>
			                                <tr>
			                                   	<th class="text-center">S.No</th>
												<th class="text-center">Date</th>
												<!-- <th class="text-center">Name</th> -->
												<th class="text-center">Email</th>
												<th class="text-center">Question / Inquiry </th>
												<th class="text-center">Status</th>
												<th class="text-center">Action</th>
												
			                                </tr>
			                            </thead>
			                            <tbody style="text-align: center;">
										
			                        	</tbody>
			                        </table>
                            		
                       			</div>
                    		</div>
					<?php 
						} else { 
					?>
							<div class="panel-body">
								<?php 
									$attributes=array('class'=>'form-horizontal','id'=>'contact');
									echo form_open_multipart($action,$attributes); 
								?>
			                        <fieldset>
			                        	<div class="form-group">
			                                <label class="col-md-2 control-label">User Name</label>
			                                <div class="col-md-8">
											<?php echo $contact->username; ?>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label class="col-md-2 control-label">Email</label>
			                                <div class="col-md-8">
											<?php echo $contact->email; ?>
			                                </div>
			                            </div>
			                        	<div class="form-group">
			                                <label class="col-md-2 control-label">Subject</label>
			                                <div class="col-md-8">
											<?php echo $contact->subject; ?>
			                                </div>
			                            </div>
			                            <div class="form-group">
			                                <label class="col-md-2 control-label">Message</label>
			                                <div class="col-md-8">
											<?php echo $contact->message; ?>
			                                </div>
			                            </div>
			                            <?php if($contact->image!=''){ 
			                            	$img = $contact->image;
                                             $extension = pathinfo($img, PATHINFO_EXTENSION);
                                             if($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg'){
			                            	?>
										<div class="view_img" style="margin-left: 60px;">
										<img src="<?php echo $contact->image; ?>" style="height:80px; width:80px;" >
         						<a href="<?php echo $contact->image; ?>" target="_blank" class="view_btn" download>Click Here To View </a>
										</div>
                                
								<?php } elseif($extension == 'pdf'){ ?>
									<div class="view_img" style="margin-left: 60px;">
									<iframe src="<?php echo $contact->image; ?>" width="100px" height="60px"></iframe>
									<a href="<?php echo $contact->image; ?>" target="_blank" class="view_btn">Click Here To View </a>
									</div>
								<?php } } ?>
			                          
			                            <div class="form-group">
			                                <label class="col-md-2 control-label">Reply Message</label>
			                                <div class="col-md-8">
											<textarea class="form-control" id="content_description" name="content_description" rows="10"></textarea>
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
			                                    <button type="submit" class="btn btn-sm btn-primary m-r-5">Send</button>
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

	<script src="<?php echo admin_source();?>plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/ckeditor/ckeditor.js"></script>
	
	<script src="<?php echo admin_source();?>plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo admin_source();?>plugins/gritter/js/jquery.gritter.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.time.min.js"></script>
	<script src="<?php echo admin_source();?>plugins/flot/jquery.flot.resize.min.js"></script>

    <script src="<?php echo admin_source();?>/plugins/DataTables/js/jquery.dataTables.min.js"></script> 
    <script src="<?php echo admin_source();?>/plugins/DataTables/js/dataTables.responsive.min.js"></script>

	<script src="<?php echo admin_source();?>js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>js/apps.min.js"></script>

	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
		$(document).ready(function(){
			App.init();

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
            "order": [[ 0, 'asc' ]],
            "language": {
              searchPlaceholder: "Search By Username... "
             },
            "ajax": admin_url+"contact/contact_ajax"
        } );
                     
        t.on( 'draw.dt', function () {
        var PageInfo = $('#datas-table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );
    } );
			
			$('#contact').validate({
				rules: {
					content_description: {
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
            CKEDITOR.replace('content_description');
        });
    </script>
		<?php 	
		}
	}
	
	?>
	<script>		

		
    </script>
     <script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>