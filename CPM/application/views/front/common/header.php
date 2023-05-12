<?php
$user_id=$this->session->userdata('user_id');
$sitelan = $this->session->userdata('site_lang');
$currency = $this->common_model->getTableData('currency',array('status'=>'1','type'=>'digital'),'','','','','','', array('sort_order', 'ASC'))->result();
$favicon = $site_common['site_settings']->site_favicon;
$sitelogo = $site_common['site_settings']->site_logo;
$users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
$pairs = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
$meta_description = $sitelan."_meta_description";
$meta_keywords = $sitelan."_meta_keywords";
$title = $sitelan."_title";


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php if($meta_content): echo $meta_content->$title; else: echo 'Crypto Pool Mirror'; endif;?></title>

	<link rel="icon" type="image/png" sizes="56x56" href="<?php echo front_img();?>favicon.png">

	<link rel="stylesheet" type="text/css" href="<?php echo front_css();?>style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo front_css();?>responsive.css">
	<link rel="stylesheet" type="text/css" href="<?php echo front_css();?>color-one.css">
	<link rel="stylesheet" type="text/css" href="<?php echo front_css();?>tata.css">

	<link rel="stylesheet" type="text/css" href="<?php echo front_css();?>datatable.css">



</head>

<body >
	<div class="main-page-wrapper">


		<div id="loader-wrapper">
			<div id="loader"></div>
		</div>

		<div class="html-top-content">
			<div class="theme-top-section">
				<header class="theme-main-menu" >
					<div class="container">
						<div class="menu-wrapper clearfix">
							<div class="logo" id="logo-sec" ><a href="<?php echo base_url();?>"><img src="<?php echo $sitelogo;?>" alt="Logo">
									<div class="logotxt"><?php if($meta_content): echo $meta_content->$title; else: echo 'Crypto Pool Mirror'; endif;?></div>
								</a></div>

							<ul class="right-widget celarfix">
								<?php
                                if(empty($user_id)){
                                ?>
								
									<li class="login-button"><a href="<?php echo base_url();?>login" class="btn-primary">Login <i
											class="flaticon-right-thin text-white"></i></a></li>
									<li class="login-button"><a href="<?php echo base_url();?>signup" class="btn-success">Sign Up <i
											class="flaticon-right-thin  text-white"></i></a></li>		

								<?php } ?>
							</ul>

						<?php
                                if(isset($user_id) && !empty($user_id)){
                        ?>
						<div class="cpm_avtr_sec" style="cursor: pointer;">
                            <img src="<?php echo front_img();?>avt-dash.jpg" class="cpm_avtr_img" >
                            <div class="cpm_avtr_tot">
                            	
                                <a href="<?php echo base_url();?>profile" class="cpm_avtr_tot_li"><i class="fal fa-user"></i> <?=$users->cpm_username;?> </a>
                                 <a href="<?php echo base_url();?>dashboard" class="cpm_avtr_tot_li"><i class="fal fa-browser"></i>Dashboard</a>
                                <a href="<?php echo base_url();?>settings" class="cpm_avtr_tot_li"><i class="fal fa-browser"></i>Settings</a>

                                <a href="<?php echo base_url();?>kyc" class="cpm_avtr_tot_li"><i class="fal fa-user-circle"></i>KYC</a>

                                <a href="<?php echo base_url();?>deposit" class="cpm_avtr_tot_li"><i class="fal fa-money-bill-wave"></i>Deposit</a>
                                <a href="<?php echo base_url();?>withdraw" class="cpm_avtr_tot_li"><i class="fal fa-money-bill"></i>Withdraw</a>
                                <a href="<?php echo base_url();?>logout" class="cpm_avtr_tot_li cpm_avtr_tot_li_log"><i class="fal fa-power-off"></i> Signout</a>
                            </div>
                        </div>
                        <?php } ?>


							<nav class="navbar navbar-expand-lg" id="mega-menu-holder">
								<div class="container">
									<button class="navbar-toggler" type="button" data-toggle="collapse"
										data-target="#navbarResponsive" aria-controls="navbarResponsive"
										aria-expanded="false" aria-label="Toggle navigation">
										<i class="fa fa-bars" aria-hidden="true"></i>
									</button>
									<div class="collapse navbar-collapse" id="navbarResponsive">
										<ul class="navbar-nav">

											<?php
			                                if(isset($user_id) && !empty($user_id)){
			                                ?>

			                                <li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>exchange">Exchange</a>
											</li>

											<li class="nav-item cpm_drp_down">
												<a class="nav-link " href="#">P2P</a>
												<div class="cpm_drp_down_set">
												<a href="<?php echo base_url();?>create_offer" class="cpm_drp_down_li">Create Order</a>
												<a href="<?php echo base_url();?>offer" class="cpm_drp_down_li">All Orders</a>
												<a href="<?php echo base_url();?>p2p_history" class="cpm_drp_down_li">My Orders</a>
												</div>
											</li>


											<li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>wallet">Wallet</a>
											</li>
											<li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>support">Support</a>
											</li>
											<li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>market">Market</a>
											</li>
											<li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>history">History</a>
											</li>
											

			                            	<?php } else { ?>

											<li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>exchange">Exchange</a>
											</li>
											<li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>contact_us">Contact</a>
											</li>
											<li class="nav-item">
												<a class="nav-link js-scroll-trigger" href="<?php echo base_url();?>faq">Faq</a>
											</li>
										<?php } ?>

										</ul>
									</div>
								</div>
							</nav>
						</div>
					</div>

					<div class=cpm_the_ch_st><div class="cpm_the_ch_li cpm_lgt_cpm_btns"><img src="<?php echo front_img();?>thico-1.png"></div><div class="cpm_the_ch_li cpm_drk_cpm_btns"><img src="<?php echo front_img();?>thico-2.png"></div></div>

				</header>
			</div>
				<div class="cp_mbl_menu">
					<div class="cp_mbl_menu_scrl">
						<a href="<?php echo base_url();?>" class="cp_mbl_menu_li">
							<i class="fal fa-home cp_mbl_menu_li_i"></i>Home
						</a>

						<a href="<?php echo base_url();?>market" class="cp_mbl_menu_li">
						<i class="fal fa-chart-bar cp_mbl_menu_li_i"></i>Market
						</a>
						<a href="<?php echo base_url();?>exchange" class="cp_mbl_menu_li">
							<i class="fal fa-bolt cp_mbl_menu_li_i"></i>Buy &amp; Sell
						</a>
						<a href="<?php echo base_url();?>contact_us" class="cp_mbl_menu_li">
						<i class="fal fa-map-signs cp_mbl_menu_li_i"></i>Contact Us
						</a>


						<?php
                                if(isset($user_id) && !empty($user_id)){
                         ?>
                        <a href="<?php echo base_url();?>wallet" class="cp_mbl_menu_li">
						<i class="fal fa-wallet cp_mbl_menu_li_i"></i>Wallet
						</a> 

                         <a href="<?php echo base_url();?>deposit" class="cp_mbl_menu_li">
						<i class="fal fa-coins cp_mbl_menu_li_i"></i>Deposit
						</a>
			
						<a href="<?php echo base_url();?>withdraw" class="cp_mbl_menu_li">
							<i class="fal fa-money-bill-wave cp_mbl_menu_li_i"></i>Withdraw
						</a>
					
						<a href="<?php echo base_url();?>support" class="cp_mbl_menu_li">
							<i class="fal fa-chalkboard-teacher cp_mbl_menu_li_i"></i>Support
						</a>

						<a href="<?php echo base_url();?>support" class="cp_mbl_menu_li">
							<i class="fal fa-map-signs cp_mbl_menu_li_i"></i>History
						</a>

					<?php } else { ?>
					<a href="#" class="cp_mbl_menu_li">
						<i class="fal fa-lock cp_mbl_menu_li_i"></i>Login
					</a>
					<a href="#" class="cp_mbl_menu_li">
						<i class="fal fa-user-circle cp_mbl_menu_li_i"></i>Register
					</a>
					<?php } ?>
					
		
					
					
		
		
					</div>
					</div>
					
