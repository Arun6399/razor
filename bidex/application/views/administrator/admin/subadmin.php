<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- Mirrored from seantheme.com/color-admin-v1.7/admin/html/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Apr 2015 06:48:10 GMT -->
<?php
    $prefix = get_prefix();
    $site_common = site_common();
    $favicon = $site_common['site_settings']->site_favicon;
    $sitelogo = $site_common['site_settings']->site_logo;
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta charset="utf-8" />
	<title><?php echo $title; ?></title>
    <link rel="icon" type="image/png" href="<?php echo $favicon;?>" />
	<meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <meta name="description" content="<?php echo $meta_description; ?>">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="//<?php echo admin_source(); ?>fonts.googleapis.com/cssff98.css?family=Open+Sans:300,400,600,700" rel="stylesheet">
	<link href="<?php echo admin_source(); ?>/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source(); ?>/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source(); ?>/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source(); ?>/css/animate.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source(); ?>/css/style.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source(); ?>/css/style-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source(); ?>/css/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo admin_source(); ?>/plugins/pace/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
    <style>
    .error{
        color:red !important;
    }
   </style>
</head>
<body class="pace-top">
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<div class="login-cover">
	    <div class="login-cover-image"><img src="" data-id="login-cover-image" alt="" /></div>
	    <div class="login-cover-bg"></div>
	</div>
	<!-- begin #page-container -->
	<div id="page-container" class="fade">
	    <!-- begin login -->
        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
            <!-- begin brand -->
            <div class="login-header">
                <div class="brand" style="text-align: center;">
                   <img src="<?php echo getSiteLogo();?>" style="height: 150px;width: 150px;margin-top: -81px;"/>
                </div>
                <div class="icon">
                    
                </div>
            </div>
            <!-- end brand -->
            <div class="login-content">
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
				<?php $attributes=array('class'=>'margin-bottom-0','id'=>'admin_login');
				echo form_open($action,$attributes); ?>
				<?php
				if($type=='subadmin')
				{ ?>
                    <div class="form-group m-b-20">
                        <input type="email" value="<?php if(isset($_COOKIE['admin_login_email'])) {echo $_COOKIE['admin_login_email'];}?>" name="email" class="form-control input-lg" placeholder="Email Address" />
                    </div>
                    <div class="form-group m-b-20">
                        <input type="password" name="password" value="<?php if(isset($_COOKIE['admin_login_password'])) {echo $_COOKIE['admin_login_password'];}?>" class="form-control input-lg" placeholder="Password" />
						<input type="hidden" value="" name="patterncode" id="patterncode" />
                    </div>
					<div class="form-group m-b-20 set-pattern-width">
                        <div id="patternContainer"></div>
                    </div>
                    <div class="checkbox m-b-20">
                        <label>
                            <input type="checkbox" value="1" <?php if(isset($_COOKIE['admin_login_remember'])) {echo 'checked';}?> name="remember" /> Remember Me
                        </label>
                    </div>
                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Sign me in</button>
                    </div>
                    <div class="m-t-20">
                        Forgot Password? Click <a href="<?php echo admin_url().'admin/forget_password';?>">here</a>
                    </div>
					<?php }else{ ?>
					<div class="form-group m-b-20">
                        <input type="email" value="" name="email" class="form-control input-lg" placeholder="Email Address" />
                    </div>
					<div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Reset Password</button>
                    </div>
                    <div class="m-t-20">
                        Back to Login? Click <a href="<?php echo admin_url().'admin/login';?>">here</a>
                    </div>
					<?php } ?>
                    <?php echo form_close(); ?>
            </div>
        </div>
        <!-- end login -->
        
        <ul class="login-bg-list">
            <!-- <li class="active"><a href="#" data-click="change-bg"><img src="<?php echo admin_source(); ?>/img/login-bg/bg-1.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="<?php echo admin_source(); ?>/img/login-bg/bg-2.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="<?php echo admin_source(); ?>/img/login-bg/bg-3.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="<?php echo admin_source(); ?>/img/login-bg/bg-4.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="<?php echo admin_source(); ?>/img/login-bg/bg-5.jpg" alt="" /></a></li>
            <li><a href="#" data-click="change-bg"><img src="<?php echo admin_source(); ?>/img/login-bg/bg-6.jpg" alt="" /></a></li> -->
        </ul>
        
       
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo admin_source(); ?>/plugins/jquery/jquery-1.9.1.min.js"></script>
	<script src="<?php echo admin_source(); ?>/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
	<script src="<?php echo admin_source(); ?>/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
	<script src="<?php echo admin_source(); ?>/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo admin_source();?>/js/jquery.validate.min.js"></script>
	<!--[if lt IE 9]>
		<script src="<?php echo admin_source(); ?>/crossbrowserjs/html5shiv.js"></script>
		<script src="<?php echo admin_source(); ?>/crossbrowserjs/respond.min.js"></script>
		<script src="<?php echo admin_source(); ?>/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="<?php echo admin_source(); ?>/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="<?php echo admin_source(); ?>/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="<?php echo admin_source(); ?>/js/login-v2.demo.min.js"></script>
	<script src="<?php echo admin_source(); ?>/js/apps.min.js"></script>
	<link href="<?php echo admin_source(); ?>/css/patternLock.css"  rel="stylesheet" type="text/css" />
<script src="<?php echo admin_source(); ?>/js/patternLock.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->

	<script>
		$(document).ready(function() {
			App.init();
			LoginV2.init();
		});
	</script>
    <script>
    $(document).ready(function() {
        
        $('#admin_login').validate({
            rules: {
                email: {
                    required: true
                },
                password: {
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
	<script async
src="https://www.googletagmanager.com/gtag/js?id=G-FDX8TJF8SG"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-FDX8TJF8SG');
</script>
	<script>
var lock = new PatternLock("#patternContainer",{
	 onDraw:function(pattern){
			word();
    }
});
function word()
{
	var pat=lock.getPattern();
	$("#patterncode").val(pat);
}

</script>
</body>

<!-- Mirrored from seantheme.com/color-admin-v1.7/admin/html/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Apr 2015 06:50:09 GMT -->
</html>
