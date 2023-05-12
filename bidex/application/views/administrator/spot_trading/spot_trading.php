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
				<li class="active"><?php echo ucfirst($view); ?>Spot Trade History</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header"><?php echo ucfirst($view); ?>Spot Trade History <!--<small>header small text goes here...</small>--></h1>
			<p class="text-right m-b-10">
								
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
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title"><?php echo ucfirst($view); ?> Trade History</h4>
                        </div>
					<?php if($view=='buy'){ ?>
                        <div class="panel-body">
                       <br/>
                            <div class="table-responsive">
                                <table id="datas-table" class="table table-striped table-bordered" >
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Pair</th>
                                        <th class="text-center">Amount</th>
										<th class="text-center">Price</th>
										<th class="text-center">Fee</th>
										<th class="text-center">Total</th>
										<th class="text-center">Type</th>
										<th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									
                                    </tbody>
                                </table>
                              
                            </div>
                        </div>
					<?php } else { ?>
						<div class="panel-body">
					<br/>
                            <div class="table-responsive">
                                <table id="datas-tables" class="table table-striped table-bordered" id="sell">
                                    <thead>
                                        <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Date & Time</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Pair</th>
                                        <th class="text-center">Amount</th>
										<th class="text-center">Price</th>
										<th class="text-center">Fee</th>
										<th class="text-center">Total</th>
										<th class="text-center">Type</th>
										<th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center;">
									
								
                                    </tbody>
                                </table>
                               
                            </div>
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
		$(document).ready(function() {
			App.init();
		});
		 /*$(document).ready(function() {
         $.fn.dataTableExt.sErrMode = 'throw';
         $('#buy').DataTable();
         $('#sell').DataTable();

        } );*/

		var admin_url='<?php echo admin_url(); ?>';
		
$(document).ready(function() {
    $('#datas-table').DataTable( {
    	responsive : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"trade_history/buy_ajax"
    });
    $('#datas-tables').DataTable( {
    	"responsive" : true,
        "processing" : true,
        "pageLength" : 10,
        "serverSide": true,
        "order": [[0, "asc" ]],
        "searching": true,
        "ajax": admin_url+"trade_history/sell_ajax"
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