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
				<li class="active">Asset Priority Settings</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Asset Priority Settings <!--<small>header small text goes here...</small>--></h1>
			<!-- end page-header -->
			<!-- begin row -->
			<div class="row">
				<!-- begin col-8 -->
				<div class="col-md-1"></div>
				<div class="col-md-10">
			        <!-- begin panel -->
                    <div class="panel panel-inverse" data-sortable-id="form-stuff-4">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                                <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>-->
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                            </div>
                            <h4 class="panel-title">Asset Priority Settings</h4>
                        </div>
                        <div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'asset_settings');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>

                                	 <div class="form-group">
                                        <label class="col-md-2 control-label">High Priority</label>
                                        <div class="col-md-3">
                                        	<label class="text-center d-block">Fees</label>
                                            <input type="text" id="english_high_cost" required name="english_high_cost" class="form-control" placeholder="High Cost" value="<?php echo $siteSettings->high_cost; ?>" />
                                        </div>                                   
                                        <div class="col-md-3">
                                        	<label class="text-center d-block">Days</label>
                                            <input type="text" id="english_high_days" required name="english_high_days" class="form-control" placeholder="High Days" value="<?php echo $siteSettings->high_time; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Standard Priority</label>
                                        <div class="col-md-3">
                                            <input type="text" id="english_standard_cost" name="english_standard_cost" class="form-control" required placeholder="Standard Cost" value="<?php echo $siteSettings->standard_cost; ?>" />
                                        </div>                                        
                                        <div class="col-md-3">
                                            <input type="text" id="english_standard_days" name="english_standard_days" class="form-control" required placeholder="Standard Days" value="<?php echo $siteSettings->standard_time; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Low Priority</label>
                                        <div class="col-md-3">
                                            <input type="text" id="english_low_cost" required required name="english_low_cost" class="form-control" placeholder="Low Cost" value="<?php echo $siteSettings->low_cost; ?>" />
                                        </div>                                        
                                        <div class="col-md-3">
                                            <input type="text" id="english_low_days" required name="english_low_days" class="form-control" placeholder="Low Days" value="<?php echo $siteSettings->low_time; ?>" />
                                        </div>
                                    </div>
	                                <div class="form-group">
	                                    <div class="col-md-8 col-md-offset-4">
	                                        <button type="submit" name="asset_pri" class="btn btn-sm btn-primary m-r-5">Submit</button>
	                                    </div>
	                                </div>
                                </fieldset>
                                <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
				<div class="col-md-1"></div>
			</div>
			<!-- end row -->
		</div>
		<!-- end #content -->
<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo admin_source();?>/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/bootstrap/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="<?php echo admin_source();?>/crossbrowserjs/html5shiv.js"></script>
		<script src="<?php echo admin_source();?>/crossbrowserjs/respond.min.js"></script>
		<script src="<?php echo admin_source();?>/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="<?php echo admin_source();?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo admin_source();?>/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo admin_source();?>/js/apps.min.js"></script>
	<script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<link href="<?php echo admin_source(); ?>/css/patternLock.css"  rel="stylesheet" type="text/css" />
<script src="<?php echo admin_source(); ?>/js/patternLock.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
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