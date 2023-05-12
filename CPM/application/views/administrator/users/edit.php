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
				<li class="active">User Details</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">User Details <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">User Details</h4>
                        </div>
                        <div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'site_settings');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                    <!--<legend>Change Password</legend>-->
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Site Name</label>
                                        <div class="col-md-4">
                                            <input type="text" id="site_name" name="site_name" class="form-control" placeholder="Site Name" value="<?php echo $siteSettings->site_name; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Site Logo</label>
                                        <div class="col-md-4">
                                            <input type="file" name="site_logo" id="site_logo" class="form-control"  />
                                        </div>
										<div class="col-md-4">
                                            <img src="<?php echo getSiteLogo(); ?>" class="img-responsive"  />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">SMTP Host</label>
                                        <div class="col-md-4">
                                            <input type="text" name="smtp_host" id="smtp_host" class="form-control" placeholder="SMTP Host" value="<?php echo decryptIt($siteSettings->smtp_host); ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">SMTP Port</label>
                                        <div class="col-md-4">
                                            <input type="text" name="smtp_port" id="smtp_port" class="form-control" placeholder="SMTP Port" value="<?php echo decryptIt($siteSettings->smtp_port); ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">SMTP Host</label>
                                        <div class="col-md-4">
                                            <input type="text" name="smtp_email" id="smtp_email" class="form-control" placeholder="SMTP email" value="<?php echo decryptIt($siteSettings->smtp_email); ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">SMTP Password</label>
                                        <div class="col-md-4">
                                            <input type="password" name="smtp_password" id="smtp_password" class="form-control" placeholder="SMTP Password" value="<?php echo decryptIt($siteSettings->smtp_password); ?>" />
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-md-4 control-label">CopyRight Text</label>
                                        <div class="col-md-4">
                                            <input type="text" name="copy_right_text" id="copy_right_text" class="form-control" placeholder="CopyRight Text" value="<?php echo $siteSettings->copy_right_text; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Change Password</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
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
	<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {
		$('#site_settings').validate({
			rules: {
				site_name: {
					required: true
				},
				smtp_host: {
					required: true
				},
				smtp_port: {
					required: true,
					number: true
				},
				smtp_email: {
					required: true,
					email: true
				},
				smtp_password: {
					required: true
				},
				copy_right_text: {
					required: true
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
			Dashboard.init();
		});
	</script>
	<script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','<?php echo admin_source()."www.google-analytics.com/analytics.js";?>','ga');
    
      ga('create', 'UA-53034621-1', 'auto');
      ga('send', 'pageview');
    </script>
