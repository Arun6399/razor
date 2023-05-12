<?php 
$user_id = $this->session->userdata('user_id');
$sitelogo = $site_common['site_settings']->site_logo;
$favicon = $site_common['site_settings']->site_favicon;
?>

<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from heloshape.com/html/rogan/rogan-c/html/index-seo.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 06 Jan 2022 13:19:02 GMT -->
<head>
		<meta charset="UTF-8">
		<meta name="keywords" content="Digital marketing agency, Digital marketing company, Digital marketing services">
		<meta name="author" content="creativegigs">
		<meta name="description" content="Rogan creative multipurpose is a beautiful website template designed for SEO & Digital Agency websites.">
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
		<link rel="icon" type="image/png" sizes="56x56" href="<?php echo $favicon;?>">
		<link href='https://fonts.googleapis.com/css?family=Muli' rel='stylesheet'>

		<!-- Main style sheet -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/style.css">
		<!-- responsive style sheet -->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/responsive.css">

		<link href="<?php echo $favicon;?>" rel="icon">
		<link href="<?php echo base_url();?>assets/front/img/apple-touch-icon.png" rel="apple-touch-icon">

		<!-- Google Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

		<!-- Vendor CSS Files -->
		<link href="<?php echo base_url();?>assets/front/vendor/aos/aos.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/front/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/front/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/front/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/front/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
		<link href="<?php echo base_url();?>assets/front/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

		<!-- Template Main CSS File -->
		<link href="<?php echo base_url();?>assets/front/css/style.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url();?>assets/front/css/jquery.growl.css">
    <style type="text/css">
        .error{
            color: red !important;
        }

        .break{
                word-break: break-all;
            }
        
        @media screen and (max-width: 767px) {
            .break{
                word-break: break-all;
            }
        }

        @media screen and (max-width: 767px) {
            .prof_img{
                margin-top: 0px !important;
            }
        }
        .profile_img{
			width: 50px !important;
		}
		.usercolor{
			color: #48465b;
		}
		.emailcolor{
			color: #5d78ff;
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

			<!-- ===================================================
				Loading Transition
			==================================================== -->
			<!-- Preloader -->
			<!-- <section>
				<div id="preloader">
					<div id="ctn-preloader" class="ctn-preloader">
						<div class="animation-preloader">
							<div class="spinner"></div>
							<div class="txt-loading">
								<span data-text-preloader="R" class="letters-loading">
									R
								</span>
								<span data-text-preloader="O" class="letters-loading">
									O
								</span>
								<span data-text-preloader="G" class="letters-loading">
									G
								</span>
								<span data-text-preloader="A" class="letters-loading">
									A
								</span>
								<span data-text-preloader="N" class="letters-loading">
									N
								</span>
							</div>
						</div>
					</div>
				</div>
			</section> -->


			<!--
			=============================================
				Theme Main Menu
			==============================================
			-->
			<div id="header-sroll" class="theme-main-menu theme-menu-two ">
				<div class="logo"><a href="<?php echo base_url();?>"><img src="<?php echo $sitelogo;?>" alt="" style="margin-top: 10px;"></a></div>
				<nav id="mega-menu-holder" class="navbar navbar-expand-lg">
					<div  class="ml-auto nav-container">
						<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					        <i class="flaticon-setup"></i>
					    </button>
					   <div class="collapse navbar-collapse" id="navbarSupportedContent">
					   		<ul  style="margin-left: 80px;"  class="navbar-nav">
							    <li class="nav-item active dropdown">
							    	<a href="<?php echo base_url();?>" class="nav-link dropdown-toggle">Home</a>

							    </li>
							    <li class="nav-item dropdown position-relative">
						            <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>cms/about_us">About Us</a>

						        </li>

						        <li class="nav-item dropdown position-relative">
						            <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>markets">Market</a>

						        </li>
						        
						                                 <?php 

                                $user_id=$this->session->userdata('user_id');


                                if($user_id!='') {?>

                                 <!-- <li class="nav-item dropdown position-relative">
                                    <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>swap">Instant Swap</a>

                                </li> -->


                                  <li class="nav-item dropdown position-relative">
                                    <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>wallet">Wallet</a>

                                </li>


                                          <li class="nav-item dropdown position-relative">
                                    <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>referral">Referral</a>

                                </li>

                                <?php }else { ?>




                                <?php  } ?>

						        <li class="nav-item dropdown">
						            <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>exchange">Exchange</a>

						        </li>
								<!-- <li class="nav-item dropdown position-relative">
						            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Swap</a>

						        </li> -->
							    <!-- <li class="nav-item dropdown position-relative">
						            <a class="nav-link dropdown-toggle" href="#">News</a>


						        </li> -->
								<li class="nav-item dropdown position-relative">
						            <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>contact_us">Get in Touch</a>
						        </li>
						        <?php if($user_id!='') {?>
						        <li class="nav-item dropdown position-relative">
						            <a class="nav-link dropdown-toggle" href="<?php echo base_url();?>transactions_history">Transaction History</a>
						        </li>
						    <?php }?>
						        
						   </ul>
					   </div>
					</div> <!-- /.container -->
				</nav> <!-- /#mega-menu-holder -->
<?php if($user_id=='') {?>
				<a href="<?php echo base_url();?>login" class="quote-button">Login/Signup</a>
				<?php }else {
                                      $user_id=$this->session->userdata('user_id');
              $user_email=getUserEmail($user_id);
$profile = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
              ?>
              
           <div class="header-right d-flex my-2 align-items-center">
                                <div class="dashboard_log">

                                    <div class="profile_log dropdown">
                                    	<?php if($profile->profile_picture==''){?>
                                    	<div class="user" data-toggle="dropdown">
                                            <span class="thumb"><i class="las la-user-tie"></i></span>
                                            <span class="arrow"><i class="las la-caret-down" style="color: darkgrey;"></i></span>
                                        </div>
<?php } else{?>
                                        <div class="user" data-toggle="dropdown">
                                            <span class="thumb"><img class="me-3 rounded-circle me-0 me-sm-3 prof_img"
                                                            src="<?php echo $profile->profile_picture;?>" width="30" height="30" alt="" style="margin-top: -3px;
    margin-bottom: -5px;"></span>
                                            <span class="arrow"><i class="las la-caret-down" style="color: darkgrey;"></i></span>
                                        </div>
                                    <?php }?>

                                        <div class="dropdown-menu dropdown-menu-end" style="margin-left: -185px; margin-top: 15px; min-width: 245px;">
                                            <div class="user-email">
                                                <?php //if ($this->session->userdata('login') == 'true') {
                                       
                                       if ($user_id != '') {
                                        $userid=$this->session->userdata('user_id');                                     

                                     $user_email=getUserEmail($userid);

                                     $all_currency = $this->common_model->getTableData('currency', array('status' => 1,'id'=>1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
                                     
      if(count($all_currency))
      {
        //$tot_balance = 0;
        foreach($all_currency as $cur)
        {
            $balance = getBalance($userid,$cur->id);
            $usd_balance = $balance * $cur->online_usdprice;

            // $tot_balance += $usd_balance;

            // $available_balance=abs($balance-$order_bal);
            // $total_bal = abs($available_balance * $cur->online_usdprice);
        }
      }
      
      $profile = $this->common_model->getTableData('users',array('id'=>$userid))->row();
                                    
                                     ?>
                                                <div class="user">
                                                	<?php if($profile->profile_picture==''){?>
                                                		<span class="thumb"><i class="las la-user-tie"></i></span>
                                                	<?php } else{?>
                                                    <span class="thumb"><img class="me-3 rounded-circle me-0 me-sm-3 prof_img"
                                                            src="<?php echo $profile->profile_picture;?>" width="30" height="30" alt="" style="margin-top: -3px;
    margin-bottom: -5px;"></span>
<?php } ?>
                                                    <div class="user-info">
                                                        <h6 class="usercolor"><?php echo UserName($userid);?></h6>
                                                        <span><a href="#" class="__cf_email__ break emailcolor" data-cfemail="a7d6d2cedfcbc6c589c4c8cae7c0cac6cecb89c4c8ca"><?php echo $user_email;?></a></span>
                                                    </div>
                                                </div>
                                            <?php }?>
                                            </div>

                                            <div class="user-balance">
                                                <div class="available">
                                                    <p>Available</p>
                                                    <span><?php echo number_format($balance, 2);?> BTC</span>
                                                </div>
                                                <div class="total">
                                                    <p>Equivalent USD</p>
                                                    <span><?php echo number_format($usd_balance,2);?> USD</span>
                                                </div>
                                            </div>
                                            <!-- <a href="account-overview.html" class="dropdown-item">
                                                <i class="mdi mdi-account"></i> Account
                                            </a> -->
                                            <a href="<?php echo base_url();?>wallet" class="dropdown-item">
                                                <i class="mdi mdi-account"></i> Wallet
                                            </a>

                                            <?php if($user_id!='') {?>
                                            <a href="<?php echo base_url();?>swap" class="dropdown-item">
                                                <i class="mdi mdi-swap-horizontal"></i> Instant Swap
                                            </a>	
		                            <?php }?>


                                         <a href="<?php echo base_url();?>support" class="dropdown-item">
                                                <i class="mdi mdi-history"></i> Support
                                            </a>


                                                <a href="<?php echo base_url();?>referral" class="dropdown-item">
                                                <i class="fa fa-user-plus"></i> Referral
                                            </a>



                                            <a href="<?php echo base_url();?>settings_profile" class="dropdown-item">
                                                <i class="mdi mdi-settings"></i> Settings
                                            </a>
                                            <!-- <a href="lock.html" class="dropdown-item">
                                                <i class="mdi mdi-lock"></i> Lock
                                            </a> -->
                                            <a href="<?php echo base_url();?>logout" class="dropdown-item logout">
                                                <i class="mdi mdi-logout"></i> Logout
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
<?php }?>
			</div>

			<!-- /.theme-main-menu -->