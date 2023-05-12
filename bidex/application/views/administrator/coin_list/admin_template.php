<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<?php $tt = $this->uri->segment_array();
$page_name = $tt[2];

    $prefix = get_prefix();
    $site_common = site_common();
    $favicon = $site_common['site_settings']->site_favicon;
    $sitelogo = $site_common['site_settings']->site_logo;
?>
<!-- Mirrored from seantheme.com/color-admin-v1.7/admin/html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Apr 2015 06:34:52 GMT -->
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo $title; ?></title>
	<link rel="icon" type="image/png" href="<?php echo $favicon;?>" />
	<meta name="keywords" content="<?php echo $meta_keywords; ?>">
    <meta name="description" content="<?php echo $meta_description; ?>">
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">	
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="<?php echo admin_source();?>fonts.googleapis.com/cssff98.css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="<?php echo admin_source();?>/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />

	<link href="<?php echo admin_source();?>/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source();?>/css/animate.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source();?>/css/style.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source();?>/css/style-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo admin_source();?>/css/theme/default.css" rel="stylesheet" id="theme" />
	<link href="<?php echo admin_source();?>/plugins/DataTables/css/data-table.css" rel="stylesheet" />
	<link href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css" rel="stylesheet" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap-editable/css/bootstrap-editable.css" type="text/css"/>


	
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="<?php echo admin_source();?>/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
	<link href="<?php echo admin_source();?>/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="<?php echo admin_source();?>/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <link href="<?php echo admin_source();?>/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />

	<!-- ================== END PAGE LEVEL STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="<?php echo admin_source();?>/plugins/pace/pace.min.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.min.css">
	
	<!-- ================== END BASE JS ================== -->
<style>
.error{
	color:red;
}
</style>
</head>
<body>
<?php 
$admin = getAdminDetails($this->session->userdata('loggeduser')); 
$aid = $this->session->userdata('loggeduser');
?>
	<!-- begin #page-loader -->
	<!-- <div id="page-loader" class="fade in"><span class="spinner"></span></div> -->
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				
				<div class="navbar-header">
					<a href="<?php echo admin_url();?>" class="navbar-brand">
						<img style="background-color: #3382c0" width="80" height="" src="<?php echo getSiteLogo();?>" /> </a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
			
				
				<!-- end mobile sidebar expand / collapse button -->
				
				<!-- begin header navigation right -->
				<?php if($aid=='1') { ?>
				<ul class="nav navbar-nav navbar-right">
				<?php $this->load->view('administrator/common/support_pending'); ?>
                     <?php $this->load->view('administrator/common/kyc_pending_list'); ?> 

					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<!-- <img src="<?php echo admin_source();?>/img/user-13.jpg" alt="" />  -->
							<span class="hidden-xs"><?php echo $admin->admin_name; ?>
							</span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<li><a href="<?php echo admin_url();?>admin/site_settings">Site Setting</a></li>
							<li><a href="<?php echo admin_url();?>admin/change_password">Change Password</a></li>
							<li><a href="<?php echo admin_url();?>admin/login_history">Admin Login History</a></li>
							<!-- <li><a href="<?php echo admin_url();?>admin/activity_log">Site Activity Log</a></li> -->
							<li class="divider"></li>
							<li><a href="<?php echo admin_url().'admin/logout';?>">Log Out</a></li>
						</ul>
					</li>
				</ul>

				<?php } ?>


				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">
				<!-- begin sidebar user -->
				<ul class="nav">
					<li class="nav-profile">
						<!-- <div class="image">
							<a href="javascript:;"><img src="<?php echo admin_source();?>/img/user-13.jpg" alt="" /></a>
						</div> -->
						<div class="info">
							<?php echo $admin->admin_name; ?>
							<!-- <small>Front end developer</small> -->
						</div>
					</li>
				</ul>
				<!-- end sidebar user -->
				<!-- begin sidebar nav -->
				<ul class="nav">


					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/dashboard')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>admin/dashboard">
						    <i class="fa fa-laptop"></i>
						    <span>Dashboard</span>
					    </a>
					</li>

					<!-- <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/banner')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>banner">
						    <i class="fa fa-picture-o"></i>
						    <span>Banners</span>
					    </a>
					</li> -->


					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/site_settings') || strpos($_SERVER['REQUEST_URI'],'zonexh_admin/email_list') || strpos($_SERVER['REQUEST_URI'],'zonexh_admin/email_template') || strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/asset_list')) { echo 'active'; } ?>">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-cog"></i>
					        <span>Settings</span>
					    </a>
					    <ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/site_settings')) { echo 'active';}?>"> 
								<a href="<?php echo admin_url();?>admin/site_settings">
								    <span>Site Settings</span>
							    </a>
							</li>
							
							

							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/email_template')) { echo 'active';}?>"> 
								<a href="<?php echo admin_url();?>email_template">
								    <span>Email Template</span>
							    </a>
							</li>

						</ul>
					</li>


				<!-- //vv -->
				

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/payment')) { echo ''; } else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users')) { echo 'active'; } ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-users"></i>
					        <span>Users</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/verification')) { echo ''; } 
							 else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/unverified_users')) { echo ''; }
							 else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/payment')) { echo ''; }
							 else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/subscribe')) { echo ''; }
							 else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/company_verification')) { echo ''; }  
							 else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>users">Activated Users</a></li>

                            <li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/unverified_users')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>users/unverified_users">Unverified Users</a></li>

							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/verification')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>users/verification">KYC User Verification</a></li>

							<!-- <li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/company_verification')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>users/company_verification">Company Verification</a></li> -->

							<!-- <li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/subscribe')) { echo 'active'; } ?>">
                                <a href="<?php echo admin_url();?>users/subscribe">Subscribers List</a></li> -->
						</ul>
					</li>
					
					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/users/payment')) { echo 'active'; } ?>">
						<!-- <a href="<?php echo admin_url();?>users/payment">
						    <i class="fa fa-money"></i>
						    <span>Payment Management</span>
					    </a> -->
					</li>

					

                    <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/wallet')) { echo 'active'; }elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin_wallet')) { echo 'active'; } ?> ">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-money"></i>
					        <span>Wallet Management</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/wallet')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>wallet">Users Wallet</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin_wallet')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>admin_wallet">Admin Wallet</a></li>
						</ul>
					</li>

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/currency')) { echo 'active'; }elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/coin_request')) { echo 'active'; } ?> ">
					    <a href="<?php echo admin_url();?>currency">
					        <i class="fa fa-credit-card"></i>
					        <span>Currency</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/currency/add')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/currency')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>currency">Currency</a></li>

							<!--<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/currency/add')){ echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/currency')) { echo ''; } ?>"><a href="<?php echo admin_url();?>currency/add">Add ERC20 Tokens</a></li>-->

							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/coin_request/')){ echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/currency')) { echo ''; } ?>"><a href="<?php echo admin_url();?>coin_request">Requested Coins / Tokens</a></li> 

						</ul>	
					</li>
					
					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_pairs')) { echo 'active'; } ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-exchange"></i>
					        <span>Trade Pairs</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_pairs/add')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_pairs')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>trade_pairs">Trade Pair</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_pairs/add')){ echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_pairs')) { echo ''; } ?>"><a href="<?php echo admin_url();?>trade_pairs/add">Add Trade Pair</a></li>
							
						</ul>
					</li>

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/deposit')) { echo 'active'; } ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-cloud"></i>
					        <span>Deposit</span>
					    </a>
						<ul class="sub-menu">
							<!--<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/deposit/crypto_deposit')) { echo ''; }elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/deposit/')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>deposit/">Fiat Deposit</a></li>-->
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/deposit/crypto_deposit')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>deposit/crypto_deposit">Crypto Deposit</a></li>
							
						</ul>
					</li>

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/withdraw')) { echo 'active'; } ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-share-square-o"></i>
					        <span>Withdraw</span>
					    </a>
						<ul class="sub-menu">

							<!--<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/withdraw/crypto_withdraw')) { echo ''; }elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/withdraw/')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>withdraw/">Fiat Withdraw</a></li>-->

							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/withdraw/crypto_withdraw')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>withdraw/crypto_withdraw">Crypto Withdraw</a></li>
							
						</ul>
					</li>
				
									
					<!-- <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/token_request') || strpos($_SERVER['REQUEST_URI'],'zonexh_admin/token_request')) { echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/token_request')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>token_request">
						    <i class="fa fa-cloud"></i>
						    <span>Token request</span>
					    </a>
					</li> -->
					
					
					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_history/sell')) { echo 'active'; } else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_history/buy')) { echo 'active'; }  ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-history"></i>
					        <span>Trade History</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_history/sell')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_history/buy')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>trade_history/buy">Buy History</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/trade_history/sell')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>trade_history/sell">Sell History</a></li>
						</ul>
					</li>

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/coin_profit')) { echo 'active'; } else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/coin_profit_report')) { echo 'active'; }  ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-bar-chart"></i>
					        <span>Profit</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/coin_profit_report')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/coin_profit')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>admin/coin_profit">Coin Profit</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/coin_profit_report')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>admin/coin_profit_report">Coin Profit Report</a></li>
						</ul>
					</li>
					
					<!-- <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/tfa')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>tfa">
						    <i class="fa fa-user-secret"></i>
						    <span>Users TFA</span>
					    </a>
					</li> -->

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/airdrops')) { echo 'active'; } else if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/airdrops')) { echo 'active'; }  ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-bar-chart"></i>
					        <span>Airdrops</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/airdrops')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/airdrops')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>airdrops">Airdrops</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/airdrops/add')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>airdrops/add">Add Airdrops</a></li>
						</ul>
					</li>
	
						<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/contact')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>contact">
					        <i class="fa fa fa-reply"></i>
						    <span>Contact Us</span>
					    </a>
					</li>
					
					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/support')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>support">
						    <i class="fa fa-ticket"></i>
						    <span>Support</span>
					    </a>
					</li>

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/bank_details') || strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/edit_bank_details') || strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/view')) { echo 'active'; }elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/user_bank_details')) { echo 'active'; } ?> ">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-bank"></i>
					        <span>Bank</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/bank_details') || strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/edit_bank_details')){ echo 'active'; }?>"><a href="<?php echo admin_url();?>admin/bank_details">Admin Bank Details</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/user_bank_details')){ echo 'active'; }?>"><a href="<?php echo admin_url();?>admin/user_bank_details">User Bank Details</a></li>
						</ul>
					</li> 
					
			
					
					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/faq')) { echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/news')) { echo 'active'; }elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/cms')) { echo 'active'; }elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/static_content')) { echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/meta_content')) { echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/partners')) { echo 'active'; }?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-question-circle"></i>
					        <span>Content Management</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/faq/add')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/faq')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>faq">Faq</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/faq/add')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>faq/add">Add Faq</a></li>
						
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/cms')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>cms">CMS Management</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/static_content')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>static_content">Static Content</a></li>

							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/meta_content')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>meta_content">Meta Content</a></li>

							<!--<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/news')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>news">News Management</a></li>-->

							<!-- <li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/partners/add')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/partners')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>partners" data-placement="top" data-toggle="popover" data-content="View Partners List (It can be Enable / Disable from site settings page)" class="poper">Partners</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/partners/add')){ echo 'active'; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/partners')) { echo ''; } ?>"><a href="<?php echo admin_url();?>partners/add" data-placement="top" data-toggle="popover" data-content="Add New Partner to display" class="poper">Add Partner</a></li> -->
						</ul>

					</li>

					

					<!-- <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/news')) { echo 'active'; } ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-newspaper-o"></i>
					        <span>News Management</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/news/add')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/news')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>news">News</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/news/add')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>news/add">Add News</a></li>
						</ul>
					</li> -->

					<!-- <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/team')) { echo 'active'; } ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-newspaper-o"></i>
					        <span>Team Management</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/team/add')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/team')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>team">Team</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/team/add')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>team/add">Add team</a></li>
						</ul>
					</li> -->

					<!-- <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/testimonials')) { echo 'active'; } ?>">
					    <a href="javascript:;">
						    <b class="caret pull-right"></b>
					        <i class="fa fa-newspaper-o"></i>
					        <span>Testimonials Management</span>
					    </a>
						<ul class="sub-menu">
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/testimonials/add')){ echo ''; } elseif(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/testimonials')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>testimonials">Testimonials</a></li>
							<li class="<?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/testimonials/add')) { echo 'active'; } ?>"><a href="<?php echo admin_url();?>testimonials/add">Add testimonials</a></li>
						</ul>
					</li> -->

					
					

					
					<!-- <li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/email_template')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>email_template">
						    <i class="fa fa-envelope"></i>
						    <span>Email Template</span>
					    </a>
					</li> -->
					
				
					
					
					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'zonexh_admin/admin/block_userip')) { echo 'active'; } ?>">
						<a href="<?php echo admin_url();?>admin/block_userip">
						    <i class="fa fa-ban"></i>
						    <span>Block User IP</span>
					    </a>
					</li>
					

					<li class="has-sub <?php if(strpos($_SERVER['REQUEST_URI'],'admin/logout')) { echo 'active'; } ?>">
							<a href="<?php echo admin_url();?>admin/logout">
						        <i class="fa fa-sign-out"></i>
							    <span>Logout</span>
						    </a>
						</li>

					
			        <!-- begin sidebar minify button -->
					<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
			        <!-- end sidebar minify button -->
				</ul>
				<!-- end sidebar nav -->
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		<?php
		$this->load->view('administrator/'.$main_content);
		?>	
		
        <!-- begin theme-panel -->
        <div class="theme-panel">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <h5 class="m-t-0">Color Theme</h5>
               
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Header Styling</div>
                    <div class="col-md-7">
                        <select name="header-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">inverse</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label">Header</div>
                    <div class="col-md-7">
                        <select name="header-fixed" class="form-control input-sm">
                            <option value="1">fixed</option>
                            <!-- <option value="2">default</option> -->
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Sidebar Styling</div>
                    <div class="col-md-7">
                        <select name="sidebar-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">grid</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label">Sidebar</div>
                    <div class="col-md-7">
                        <select name="sidebar-fixed" class="form-control input-sm">
                            <option value="1">fixed</option>
                            <option value="2">default</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Sidebar Gradient</div>
                    <div class="col-md-7">
                        <select name="content-gradient" class="form-control input-sm">
                            <option value="1">disabled</option>
                            <option value="2">enabled</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-5 control-label double-line">Content Styling</div>
                    <div class="col-md-7">
                        <select name="content-styling" class="form-control input-sm">
                            <option value="1">default</option>
                            <option value="2">black</option>
                        </select>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <a href="#" class="btn btn-inverse btn-block btn-sm" data-click="reset-local-storage"><i class="fa fa-refresh m-r-3"></i> Reset Local Storage</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end theme-panel -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	
</body>

<script>
var base_url='<?php echo base_url();?>';
var front_url='<?php echo front_url();?>';
var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
$(document).ready(function() {
$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
		if (options.type.toLowerCase() == 'post') {
			options.data += '&'+csrfName+'='+$("input[name="+csrfName+"]").val();
			if (options.data.charAt(0) == '&') {
				options.data = options.data.substr(1);
			}
		}
	});
$( document ).ajaxComplete(function( event, xhr, settings ) {
	 var n = settings.url.includes("get_csrf_token"); 
  if (!n) {
 $.ajax({
				url: front_url+"get_csrf_token", 
				type: "GET",
				cache: false,             
				processData: false,      
				success: function(data) {
					 $("input[name="+csrfName+"]").val(data);
				}
			});
  }
});
});
</script>
<script>

$(document).ready(function() {
        $(document).on('mouseenter', '.poper', function(){
            $(this).popover("show");
            $(this).on("mouseleave", function () {
                $(this).popover('hide');
            });
        });

        $(".sidebar .nav > .has-sub > a").click(function() {
        	$(".sidebar .nav > li.has-sub").removeClass("expand");
        	$(".sidebar .nav > li.has-sub").removeClass("active");

        	var _this = this;
        	//alert(_this)
        	$(this).closest("li").addClass("active expand");
        	$(this).closest("li .sub-menu").show("");
        });
});

function deleteaction(link)
{
	
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You won't be able to revert this!",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) { 
	    window.location.href=link;
	  }
	})
}



</script>

</html>
