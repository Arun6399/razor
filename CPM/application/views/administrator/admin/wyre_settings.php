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
				<li class="active">Wyre Settings</li>
			</ol>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			<h1 class="page-header">Wyre Settings <!--<small>header small text goes here...</small>--></h1>
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
                            <h4 class="panel-title">Wyre Settings</h4>
                        </div>
                        <div class="panel-body">
						<?php $attributes=array('class'=>'form-horizontal','id'=>'wyre_settings');
				echo form_open_multipart($action,$attributes); ?>
                                <fieldset>
                                  
									 <h4 style="text-align: center;">General Settings</h4>
                                     
                                     
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Wyre Public Key</label>
                                        <div class="col-md-4">
                                            <input type="password" id="public_key" name="public_key" class="form-control" placeholder="Public Key" value="<?php echo decryptIt($wyreSettings->public_key); ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Wyre Secret Key</label>
                                        <div class="col-md-4">
                                            <input type="password" id="secret_key" name="secret_key" class="form-control" placeholder="Secret Key" value="<?php echo decryptIt($wyreSettings->secret_key); ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Wyre Account ID</label>
                                        <div class="col-md-4">
                                            <input type="password" id="account_id" name="account_id" class="form-control" placeholder="Account ID" value="<?php echo decryptIt($wyreSettings->account_id); ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Wyre Redirect Url</label>
                                        <div class="col-md-4">
                                            <input type="text" id="redirect_url" name="redirect_url" class="form-control" placeholder="Redirect Url" value="<?php echo $wyreSettings->redirect_url; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Wyre Failure Url</label>
                                        <div class="col-md-4">
                                            <input type="text" id="failure_url" name="failure_url" class="form-control" placeholder="Failure Url" value="<?php echo $wyreSettings->failure_url; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Wyre Mode</label>
                                        <div class="col-md-4">
                                            <select data-live-search="true" class="selectpicker form-control" name="mode" id="mode">
                                            <option value="0" <?php if($wyreSettings->mode==0){echo 'selected';} ?>>Test</option>
                                            <option value="1" <?php if($wyreSettings->mode==1){echo 'selected';} ?>>Live</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-sm btn-primary m-r-5">Submit</button>
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

	<!-- ================== END PAGE LEVEL JS ================== -->
<script>
	$(document).ready(function() {
		$('#wyre_settings').validate({
			rules: {
				public_key: {
					required: true
				},
				secret_key: {
					required: true
				},
				account_id: {
					required: true
				},
				redirect_url: {
					required: true
				},
				failure_url: {
					required: true
				},
				mode: {
					required: true
				}
			},
            messages : {
                public_key: {
                    required: "Please enter public Key"
                },
                secret_key: {
                    required: "Please enter Secre Key"
                },
                account_id: {
                    required: "Please enter Account Id"
                },
                redirect_url: {
                    required: "Please enter redirect Url"
                },
                failure_url: {
                    required: "Please enter Failre Url"
                },
                mode: {
                    required: "Please enter Mode"
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
			//Dashboard.init();
		});
	</script>
	

