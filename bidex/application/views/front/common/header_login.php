<?php $sitelogo = $site_common['site_settings']->site_logo;
$favicon = $site_common['site_settings']->site_favicon;
?>
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from heloshape.com/html/rogan/rogan-c/html/login-standard.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Jan 2022 13:20:58 GMT -->
<head>
		<meta charset="UTF-8">
		<meta name="keywords" content="Digital marketing agency, Digital marketing company, Digital marketing services">
		<meta name="author" content="creativegigs">
		<meta name="description" content="#">
		<meta name='og:image' content='images/home/ogg.png'>
		<!-- For IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- For Resposive Device -->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- For Window Tab Color -->
		<!-- Chrome, Firefox OS and Opera -->
		<meta name="theme-color" content="#233D63">
		<!-- Windows Phone -->
		<meta name="msapplication-navbutton-color" content="#233D63">
		<!-- iOS Safari -->
		<meta name="apple-mobile-web-app-status-bar-style" content="#233D63">
		<title>Bidex - Explore your Money</title>
		<!-- Favicon -->
		<link rel="icon" type="image/png" sizes="56x56" href="<?=$favicon?>">
		<!-- Main style sheet -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
		<!-- responsive style sheet -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/responsive.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/css/intlTelInput.css" rel="stylesheet" media="screen">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/intlTelInput.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.9/js/utils.js"></script>
		<link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/jquery.growl.css">
    <style type="text/css">
        .error{
            color: red !important;
        }
    </style>

		<!-- Fix Internet Explorer ______________________________________-->
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
			<script src="vendor/html5shiv.js"></script>
			<script src="vendor/respond.js"></script>
		<![endif]-->
	</head>

	<body>
		<div class="main-page-wrapper">

			<div id="header-sroll" class="theme-main-menu theme-menu-two ">
				<div class="logo menu"><a href="<?php echo base_url();?>"><img src="<?php echo $sitelogo;?>" alt=""></a></div>
				<nav id="mega-menu-holder" class="navbar navbar-expand-lg">
					<div  class="ml-auto nav-container">
						<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					        <i class="flaticon-setup"></i>
					    </button>
					   <div class="collapse navbar-collapse" id="navbarSupportedContent">
					   		<ul class="navbar-nav">
							    <li class="nav-item active dropdown">
							    	<a href="<?php echo base_url();?>" class="nav-link menu dropdown-toggle">Home</a>

							    </li>
							    <li class="nav-item dropdown position-relative">
						            <a class="nav-link menu dropdown-toggle" href="<?php echo base_url();?>cms/about_us">About Us</a>

						        </li>
						        <li class="nav-item dropdown position-relative">
						            <a class="nav-link menu dropdown-toggle" href="<?php echo base_url();?>markets">Market</a>

						        </li>
						        <li class="nav-item dropdown">
						            <a class="nav-link menu dropdown-toggle" href="<?php echo base_url();?>exchange">Exchange</a>

						        </li>
								<!-- <li class="nav-item dropdown position-relative">
						            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Swap</a>

						        </li> -->
							    <!-- <li class="nav-item dropdown position-relative">
						            <a class="nav-link menu dropdown-toggle" href="#">News</a>


						        </li> -->
								<li class="nav-item dropdown position-relative">
						            <a class="nav-link menu dropdown-toggle" href="<?php echo base_url();?>contact_us">Get in Touch</a>


						        </li>
						   </ul>
					   </div>
					</div> <!-- /.container -->
				</nav> <!-- /#mega-menu-holder -->

				<a href="<?php echo base_url();?>login" class="quote-button menu nw-font" style="background: #f6b042;">Login/Signup</a>
			</div>