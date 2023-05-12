<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title; ?></title>
	<meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <meta name="description" content="<?php echo $meta_description; ?>">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
		
	<!--STYLESHEET-->
	<!--=================================================-->

	<!--Open Sans Font [ OPTIONAL ] -->
 	<link href="//http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin" rel="stylesheet">


	<!--Bootstrap Stylesheet [ REQUIRED ]-->
	<link href="<?php echo admin_source(); ?>css/bootstrap.min.css" rel="stylesheet">


	<!--Nifty Stylesheet [ REQUIRED ]-->
	<link href="<?php echo admin_source(); ?>css/nifty.min.css" rel="stylesheet">

	
	<!--Font Awesome [ OPTIONAL ]-->
	<link href="<?php echo admin_source(); ?>plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">


	<!--Demo [ DEMONSTRATION ]-->
	<link href="<?php echo admin_source(); ?>css/demo/nifty-demo.min.css" rel="stylesheet">




	<!--SCRIPT-->
	<!--=================================================-->

	<!--Page Load Progress Bar [ OPTIONAL ]-->
	<link href="<?php echo admin_source(); ?>plugins/pace/pace.min.css" rel="stylesheet">
	<script src="<?php echo admin_source(); ?>plugins/pace/pace.min.js"></script>


	
	<!--

	REQUIRED
	You must include this in your project.

	RECOMMENDED
	This category must be included but you may modify which plugins or components which should be included in your project.

	OPTIONAL
	Optional plugins. You may choose whether to include it in your project or not.

	DEMONSTRATION
	This is to be removed, used for demonstration purposes only. This category must not be included in your project.

	SAMPLE
	Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


	Detailed information and more samples can be found in the document.

	-->
		

</head>

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->

<body>
	<div id="container" class="cls-container">
		
		<!-- BACKGROUND IMAGE -->
		<!--===================================================-->
		<div id="bg-overlay" class="bg-img img-balloon"></div>
		
		
		<!-- HEADER -->
		<!--===================================================-->
		<div class="cls-header cls-header-lg">
			<div class="cls-brand">
				<a class="box-inline" href="<?php echo base_url(); ?>">
					<!-- <img alt="Nifty Admin" src="img/logo.png" class="brand-icon"> -->
					<span class="brand-title"><?php echo getSiteName(); ?> <span class="text-thin">Admin</span></span>
				</a>
			</div>
		</div>
		<!--===================================================-->
		
		<div class="cls-container">
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
		</div>
		
		<!-- PASSWORD RESETTING FORM -->
		<!--===================================================-->
		<div class="cls-content">
			<div class="cls-content-sm panel">
				<div class="panel-body">
					<p class="pad-btm">Enter your email address to recover your password. </p>
					<form action="<?php echo $action; ?>" method="post">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon"><i class="fa fa-envelope"></i></div>
								<input type="email" required name="email" class="form-control" placeholder="Email">
							</div>
						</div>
						<div class="form-group text-right">
							<button class="btn btn-success text-uppercase" type="submit">Reset Password</button>
						</div>
					</form>
				</div>
			</div>
			<div class="pad-ver">
				<a href="<?php echo admin_url() . 'admin'; ?>" class="btn-link mar-rgt">Back to Login</a>
			</div>
		</div>
		<!--===================================================-->
		
		
		<!-- DEMO PURPOSE ONLY -->
		<!--===================================================-->
		<div class="demo-bg">
			<div id="demo-bg-list">
				<div class="demo-loading"><i class="fa fa-refresh"></i></div>
				<img class="demo-chg-bg bg-trans" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-trns.jpg" alt="Background Image">
				<img class="demo-chg-bg" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-img-1.jpg" alt="Background Image">
				<img class="demo-chg-bg active" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-img-2.jpg" alt="Background Image">
				<img class="demo-chg-bg" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-img-3.jpg" alt="Background Image">
				<img class="demo-chg-bg" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-img-4.jpg" alt="Background Image">
				<img class="demo-chg-bg" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-img-5.jpg" alt="Background Image">
				<img class="demo-chg-bg" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-img-6.jpg" alt="Background Image">
				<img class="demo-chg-bg" src="<?php echo admin_source(); ?>img/bg-img/thumbs/bg-img-7.jpg" alt="Background Image">
			</div>
		</div>
		<!--===================================================-->
		
		
		
	</div>
	<!--===================================================-->
	<!-- END OF CONTAINER -->


		
	<!--JAVASCRIPT-->
	<!--=================================================-->

	<!--jQuery [ REQUIRED ]-->
	<script src="<?php echo admin_source(); ?>js/jquery-2.1.1.min.js"></script>


	<!--BootstrapJS [ RECOMMENDED ]-->
	<script src="<?php echo admin_source(); ?>js/bootstrap.min.js"></script>


	<!--Fast Click [ OPTIONAL ]-->
	<script src="<?php echo admin_source(); ?>plugins/fast-click/fastclick.min.js"></script>

	
	<!--Nifty Admin [ RECOMMENDED ]-->
	<script src="<?php echo admin_source(); ?>js/nifty.min.js"></script>


	<!--Background Image [ DEMONSTRATION ]-->
	<script src="<?php echo admin_source(); ?>js/demo/bg-images.js"></script>

	
	<!--

	REQUIRED
	You must include this in your project.

	RECOMMENDED
	This category must be included but you may modify which plugins or components which should be included in your project.

	OPTIONAL
	Optional plugins. You may choose whether to include it in your project or not.

	DEMONSTRATION
	This is to be removed, used for demonstration purposes only. This category must not be included in your project.

	SAMPLE
	Some script samples which explain how to initialize plugins or components. This category should not be included in your project.


	Detailed information and more samples can be found in the document.

	-->
		

</body>
</html>
