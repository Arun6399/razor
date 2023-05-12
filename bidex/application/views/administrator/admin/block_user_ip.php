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
				<li class="active">Block User IP</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Block User IP <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Block User IP</h4>
                        </div>
						<div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'block_userip');
								echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                  
									<div class="form-group">
                                        <label class="col-md-2 control-label">Ip Address</label>
                                        <div class="col-md-8">
										<input type="text" name="ip" id="ip" class="form-control" placeholder="Ip Address" value=""  required/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-7 col-md-offset-5">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>

                            <h5>Blocked Ip's</h5>
                            <br/><br/>
                            <div class="panel-body">
		                    <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">S.No</th>
											<th class="text-center">IP</th>
											<th class="text-center">Access date</th>
											<th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    /*if(isset($block_ip) && !empty($block_ip))
                                    {
                                    	$a=0;
                                    	foreach($block_ip as $blocks)
                                    	{
                                    		$a++;
                                    	echo "<tr><td>".$a."</td>";
                                    	echo "<td>".$blocks->ip."</td>";
                                    	echo "<td>".$blocks->access_date."</td>";?>
                                    	<td><a onclick="confirm('Are you sure to Delete this IP?')" href="<?php echo admin_url()?>admin/delete_block_userip/<?php echo $blocks->id;?>" data-placement="bottom" data-toggle="popover" data-content="Delete this IP" class="poper"><i class='fa fa-trash-o text-danger'></i></a></td>
                                    		<?php echo "</tr>";
                                        }
                                    }*/
                                    ?>
                                    </tbody>
                                    </table>
                                    </div>
                                </div>
                        </div>
					
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
	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> 
    <script src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	
	<!-- ================== END PAGE LEVEL JS ================== -->
	
	<script>
		$(document).ready(function() {
			App.init();
		});


		$(document).ready(function() {

		 $.validator.addMethod('IP4Checker', function(value) {
		 	var ipformat = /^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/; 
            ipformat = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/;
            /*var ip = "^(?:(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)\.){3}" +
                "(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)$";*/
                return value.match(ipformat);
            }, 'Invalid IP address');
		
		$('#block_userip').validate({
			rules: {
				ip: {
					required: true,
					 IP4Checker: true
				}
			},
			messages : {
                ip: {
                    required: "Please enter ip address",
                    IP4Checker: "Invalid IP address"
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

	  $(document).ready(function() {
    $('#datas-table').DataTable( {
    	responsive : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"admin/ip_ajax"
    });
        });
	 
	
    </script>
