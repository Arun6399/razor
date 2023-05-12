<?php $user_id = $this->session->userdata('user_id');

$sitelogo = $site_common['site_settings']->site_logo;
$favicon = $site_common['site_settings']->site_favicon;
?>
<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from demo.quixlab.com/tradio-html/settings.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 22 Jan 2022 05:43:51 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bidex </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favicon;?>">
    
    <!-- Custom Stylesheet -->
    <!-- <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/nice-select/css/nice-select.css"> -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/vendor/waves/waves.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/style-dash.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/responsive.css">
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

        .mobile{
                display: none !important;
            }
            .mob_use{
                margin-left: 50px;
            }

             @media screen and (max-width: 767px) {
            .mob_use{
                margin-left: 0px;
            }
        }
        
        @media screen and (max-width: 767px) {
            .mobile{
                display: block !important;
            }
        }

        @media screen and (max-width: 767px) {
            .mobile_user{
                display: none !important;
            }
        }
  
    </style>
</head>

<body>

    <div id="preloader">

        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
            <div class="sk-child sk-bounce4"></div>

        </div>
    </div>

    <div id="main-wrapper">

        <div class="header dashboard">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <nav class="navbar navbar-expand-lg navbar-light px-0 justify-content-between">
                            <a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo $sitelogo;?>" alt=""></a>
                            <div class="header-right d-flex my-2 align-items-center mobile">
                                <!-- <div class="language">
                                    <div class="dropdown">
                                        <div class="icon" data-toggle="dropdown">
                                            <i class="flag-icon flag-icon-us"></i>
                                            <span>English</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#" class="dropdown-item">
                                                <i class="flag-icon flag-icon-bd"></i> Bengali
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                <i class="flag-icon flag-icon-fr"></i> French
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                <i class="flag-icon flag-icon-cn"></i> China
                                            </a>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="dashboard_log">

                                    <div class="profile_log dropdown">
                                        <?php $profile = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
                                        if($profile->profile_picture==''){
                                            ?>
                                        <div class="user" data-toggle="dropdown">
                                            <span class="thumb"><i class="las la-user-tie"></i></span>
                                            <span class="arrow"><i class="las la-caret-down" style="color: darkgrey;"></i></span>
                                        </div>
<?php } else{?>
                                        <div class="user" data-toggle="dropdown">
                                            
                                            <span class="thumb"><img class="me-3 rounded-circle me-0 me-sm-3"
                                                            src="<?php echo $profile->profile_picture;?>" width="30" height="30" alt="" style="margin-top: -6px;
    margin-bottom: -5px;"></span>
                                            <span class="arrow"><i class="las la-caret-down"></i></span>
                                        </div>
<?php } ?>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <div class="user-email">
                                                <?php //if ($this->session->userdata('login') == 'true') {
                                       
                                       if ($user_id != '') {
                                        $userid=$this->session->userdata('user_id');                                     

                                     $user_email=getUserEmail($userid);

                                     //$wherein = array('id', array('1'));
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
                                                    <span class="thumb"><img class="me-3 rounded-circle me-0 me-sm-3"
                                                            src="<?php echo $profile->profile_picture;?>" width="30" height="30" alt="" style="margin-top: -6px;
    margin-bottom: -5px;"></span>
<?php } ?>
                                                    <div class="user-info">
                                                        <h6><?php echo UserName($userid);?></h6>
                                                        <span><a href="#" class="__cf_email__ break" data-cfemail="a7d6d2cedfcbc6c589c4c8cae7c0cac6cecb89c4c8ca"><?php echo $user_email;?></a></span>
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


                            <div id="header-sroll" class="theme-main-menu theme-menu-two ">
                                <nav id="mega-menu-holder" class="navbar navbar-expand-lg">
                                    <div  class="ml-auto nav-container">
                                        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="flaticon-setup"></i>
                        </button>
                                        
                                    </div> <!-- /.container -->
                                </nav> <!-- /#mega-menu-holder -->

                                <!-- <a href="login.html" class="quote-button">Login/Signup</a> -->
                            </div>

                            <div class="collapse navbar-collapse mob_use" id="navbarSupportedContent">
                                               <ul   class="navbar-nav">
                                                <li class="nav-item">
                                                    <a href="<?php echo base_url();?>" class="nav-link">Home</a>

                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="<?php echo base_url();?>cms/about_us">About Us</a>

                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="<?php echo base_url();?>markets" >Market</a>

                                                </li>


                                <?php 

                                $user_id=$this->session->userdata('user_id');


                                if($user_id!='') {?>

  <!-- <li class="nav-item dropdown position-relative">
                                    <a class="nav-link" href="<?php echo base_url();?>swap">Instant Swap</a>

                                </li> -->

                                    <li class="nav-item dropdown position-relative">
                                    <a class="nav-link" href="<?php echo base_url();?>wallet">Wallet</a>

                                </li>


                                          <li class="nav-item dropdown position-relative">
                                    <a class="nav-link" href="<?php echo base_url();?>referral">Referral</a>

                                </li>




                                <?php } ?>
                                                <li class="nav-item dropdown">
                                                    <a class="nav-link" href="<?php echo base_url();?>exchange">Exchange</a>

                                                </li>
                                                <!-- <li class="nav-item dropdown position-relative">
                                                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Swap</a>

                                                </li> -->
                                                <!-- <li class="nav-item dropdown position-relative">
                                                    <a class="nav-link" href="#">News</a>


                                                </li> -->
                                                <li class="nav-item dropdown position-relative">
                                                    <a class="nav-link" href="<?php echo base_url();?>contact_us">Get in Touch</a>
                                                </li>
                                                <li class="nav-item dropdown position-relative">
                                                    <a class="nav-link" href="<?php echo base_url();?>transactions_history">Transaction History</a>
                                                </li>
                                           </ul>
                                       </div>

                            <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav m-auto">
                                    <li class="dropdown animation" data-animation="fadeInDown" data-animation-delay="1.1s">
                            <a  class="nav-link" href="#">Home</a>

                                    </li>

                                    <li class="animation" data-animation="fadeInDown" data-animation-delay="1.2s"><a class="nav-link page-scroll nav_item" href=" cms/about-us">About Us</a></li>
                                    <li class="dropdown animation" data-animation="fadeInDown" data-animation-delay="1.1s">
                            <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">Tools</a>
                              <div class="dropdown-menu">
                                    <ul class="list_none">
                                                   <li><a class="dropdown-item nav-link nav_item" href="price.html">Price</a></li>
                                                   <li><a class="dropdown-item nav-link nav_item" href="calc.html">Calculator</a></li>
                                            </ul>
                                          </div>
                                    </li>
                                    <li class="dropdown animation" data-animation="fadeInDown" data-animation-delay="1.1s">
                            <a class="nav-link" href="market.html">Market</a>

                                    </li>
                                    <li class="dropdown animation" data-animation="fadeInDown" data-animation-delay="1.1s">
                            <a data-toggle="dropdown" class="nav-link dropdown-toggle" href="#">Exchange</a>
                              <div class="dropdown-menu">
                                    <ul class="list_none">
                                                   <li><a class="dropdown-item nav-link nav_item" href="ex-basic.html">Basic</a></li>
                                                   <li><a class="dropdown-item nav-link nav_item" href="ex-pro.html">Advance</a></li>
                                            </ul>
                                          </div>
                                    </li>

                                </ul>
                                <ul class="navbar-nav nav_btn align-items-center">

                                                        <li class="animation" data-animation="fadeInDown" data-animation-delay="1.6s"><a class="btn btn-default btn-radius nav_item" href=" signup">Register</a></li>
                                     <li class="animation" data-animation="fadeInDown" data-animation-delay="1.7s"><a class="btn btn-default btn-radius nav_item" href=" login">Login</a></li>
                                                    </ul>

                      </div> -->

                            <div class="header-right d-flex my-2 align-items-center mobile_user">
                                <!-- <div class="language">
                                    <div class="dropdown">
                                        <div class="icon" data-toggle="dropdown">
                                            <i class="flag-icon flag-icon-us"></i>
                                            <span>English</span>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="#" class="dropdown-item">
                                                <i class="flag-icon flag-icon-bd"></i> Bengali
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                <i class="flag-icon flag-icon-fr"></i> French
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                <i class="flag-icon flag-icon-cn"></i> China
                                            </a>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="dashboard_log">

                                    <div class="profile_log dropdown">
                                        <?php if($profile->profile_picture==''){?>
                                        <div class="user" data-toggle="dropdown">
                                            <span class="thumb"><i class="las la-user-tie"></i></span>
                                            <span class="arrow"><i class="las la-caret-down" style="color: darkgrey;"></i></span>
                                        </div>
<?php } else{?>
                                        <div class="user" data-toggle="dropdown">
                                            <span class="thumb"><img class="me-3 rounded-circle me-0 me-sm-3"
                                                            src="<?php echo $profile->profile_picture;?>" width="30" height="30" alt="" style="margin-top: -6px;
    margin-bottom: -5px;"></span>
                                            <span class="arrow"><i class="las la-caret-down"></i></span>
                                        </div>
                                    <?php } ?>

                                        <div class="dropdown-menu dropdown-menu-end">
                                            <div class="user-email">
                                                <?php //if ($this->session->userdata('login') == 'true') {
                                       
                                       if ($user_id != '') {
                                        $userid=$this->session->userdata('user_id');                                     

                                     $user_email=getUserEmail($userid);

                                     //$wherein = array('id', array('1'));
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
                                                    <span class="thumb"><img class="me-3 rounded-circle me-0 me-sm-3"
                                                            src="<?php echo $profile->profile_picture;?>" width="30" height="30" alt="" style="margin-top: -6px;
    margin-bottom: -5px;"></span>
<?php } ?>
                                                    <div class="user-info">
                                                        <h6><?php echo UserName($userid);?></h6>
                                                        <span><a href="#" class="__cf_email__ break" data-cfemail="a7d6d2cedfcbc6c589c4c8cae7c0cac6cecb89c4c8ca"><?php echo $user_email;?></a></span>
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
                                            <a href="<?php echo base_url();?>settings_profile" class="dropdown-item">
                                                <i class="mdi mdi-settings"></i> Setting
                                            </a>
                                             <a href="<?php echo base_url();?>referral" class="dropdown-item">
                                                <i class="fa fa-user-plus"></i> Referral
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
                        </nav>
                    </div>
                </div>
            </div>
        </div>