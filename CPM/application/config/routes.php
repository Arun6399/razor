<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$front_prefix												= 'cpm_front/';
$route['default_controller'] 								= $front_prefix.'common';
$route['404_override'] 										= '';
$route['translate_uri_dashes'] 								= FALSE;
$route['cpm_admin'] 										= 'cpm_admin/admin';
$route['cpm_admin/(:any)'] 								= 'cpm_admin/$1';
$route['cpm_admin/(:any)/(:any)'] 						    = 'cpm_admin/$1/$2';
$route['cpm_admin/(:any)/(:any)/(:any)'] 					= 'cpm_admin/$1/$2/$3';
$route['cpm_admin/(:any)/(:any)/(:any)/(:any)'] 			= 'cpm_admin/$1/$2/$3/$4';
$route['cpm_admin/admin/(:any)'] 							= 'cpm_admin/admin/$1';
$route['home'] 												= $front_prefix.'common/index';

//User
$route['signup'] 											= $front_prefix.'user/signup';


$route['login'] 											= $front_prefix.'user/login';
$route['login_check'] 										= $front_prefix.'user/login_check';

// Temp Routes
// $route['signup_pilagsfdkuafjdskghfdgukjfhgduyifdsahkfdsiluyfdskh'] = $front_prefix.'user/signup_pila';
// $route['login_pilagsfdkuafjdskghfdgukjfhgduyifdsahkfdsiluyfdskh'] 										= $front_prefix.'user/login_pila';


$route['email_exist'] 										= $front_prefix.'user/email_exist';
$route['phone_exist'] 										= $front_prefix.'user/phone_exist';
$route['get_csrf_token'] 									= $front_prefix.'user/get_csrf_token';
$route['logout'] 											= $front_prefix.'user/logout';
$route['forgot_password'] 		                            = $front_prefix.'user/forgot_user';
$route['forgot_check'] 		                                = $front_prefix.'user/forgot_check';
$route['reset_pw_user/(:any)'] 	= 	$front_prefix.'user/reset_pw_user/$1';
$route['profile'] 										    = $front_prefix.'user/profile';
$route['kyc_verification'] 									= $front_prefix.'user/kyc_verification';
$route['bank-details'] 									    = $front_prefix.'user/bank_details';
$route['update_bank_details']					            = $front_prefix.'user/update_bank_details';
$route['profile-edit'] 										= $front_prefix.'user/editprofile';
$route['changepassword'] 									= $front_prefix.'user/changepassword';
$route['change_password'] 									= $front_prefix.'user/changepassword';
$route['2fa'] 									            = $front_prefix.'user/authentication';
$route['two_factor'] 									    = $front_prefix.'user/two_factor';
$route['security'] 									        = $front_prefix.'user/security';
$route['login_history'] 									= $front_prefix.'user/login_history';
$route['support'] 										    = $front_prefix.'user/support';
// $route['support'] 										    = $front_prefix.'common/support_new';
$route['support_reply/(:any)']						        = $front_prefix.'user/support_reply/$1';
$route['verify_user/(:any)'] 								= $front_prefix.'user/verify_user/$1';
$route['oldpassword_exist'] 								= $front_prefix.'user/oldpassword_exist';
$route['localpair_details']                                = $front_prefix.'common/localpair_details';
$route['oneweek_localpair_details']          = $front_prefix.'common/oneweek_localpair_details';

$route['account'] = $front_prefix.'user/account';
$route['account'] = $front_prefix.'user/account';
$route['ajax_wallet'] = $front_prefix.'user/ajax_wallet';
$route['activity_delete/(:any)/(:any)'] = $front_prefix.'user/activity_delete/$1/$1';

$route['trade_history']                                        = $front_prefix.'user/trade_history';

//Common
$route['cms/(:any)']										= $front_prefix.'common/cms/$1';
$route['faq']												= $front_prefix.'common/faq';
$route['contact_us']                                        = $front_prefix.'common/contact_us';

$route['markets']                                           = $front_prefix.'common/markets';
$route['airdrops']                                          = $front_prefix.'common/airdrops';
$route['deposit'] 								            = $front_prefix.'user/deposit';
$route['deposit/(:any)'] 								    = $front_prefix.'user/deposit/$1';
$route['wallet'] 								            = $front_prefix.'user/wallet';
$route['withdraw'] 										    = $front_prefix.'user/withdraw';
$route['withdraw/(:any)'] 									= $front_prefix.'user/withdraw/$1';
$route['change_address'] 									= $front_prefix.'user/change_address';
$route['change_bank'] 										= $front_prefix.'user/change_bank';


$route['withdraw_coin_user_confirm/(:any)']					= $front_prefix.'user/withdraw_coin_user_confirm/$1';
$route['withdraw_coin_user_cancel/(:any)']			        = $front_prefix.'user/withdraw_coin_user_cancel/$1';

// Crypto Fiat Withdraw

$route['crypto_fiatwithdraw'] 								= $front_prefix.'user/crypto_fiatwithdraw';
$route['crypto_fiatwithdraw/(:any)'] 						= $front_prefix.'user/crypto_fiatwithdraw/$1';




//razor//===============

	 $route['razor'] = $front_prefix.'razorpay/index';
     $route['checkout/(:any)'] = $front_prefix."razorpay/checkout/$1";



$route['update_user_address'] 									= $front_prefix.'user/update_user_address';

$route['update_crypto_deposits'] = $front_prefix.'user/update_crypto_deposits';

$route['update_crypto_deposits/(:any)'] = $front_prefix.'user/update_crypto_deposits/$1'; 


$route['update_adminaddress'] 				    = 	$front_prefix.'common/update_adminaddress';
$route['update_adminbalance'] 				    = 	$front_prefix.'common/admin_wallet_balance';
$route['update_usd_price'] 									= $front_prefix.'common/update_usd_price';


$route['trade'] 									        = $front_prefix.'trade';
$route['trade/pro'] 								        = $front_prefix.'trade/pro';
$route['trade/fees'] 				                        = $front_prefix.'trade/fees';
$route['trade/updateModalOpenStatus'] 				        = $front_prefix.'trade/updateModalOpenStatus';
$route['trade/(:any)'] 										= $front_prefix.'trade/trade/$1';
$route['angtrade/(:any)'] 									= $front_prefix.'trade/angtrade/$1';
$route['trade/pro/(:any)'] 									= $front_prefix.'trade/trade_pro/$1';

$route['trade_integration/(:any)/(:any)'] 					= $front_prefix.'trade/trade_integration/$1/$2';

$route['trade_integration/(:any)/(:any)/(:any)'] 			= $front_prefix.'trade/trade_integration/$1/$2/$3';

$route['trade_integration/(:any)/(:any)/(:any)/(:any)'] 	= $front_prefix.'trade/trade_integration/$1/$2/$3/$4';

// api routes start
$route['trade/gettradeapiOrders/(:any)']				    = $front_prefix.'trade/gettradeapiOrders/$1';

$route['trade/getpairdetails/(:any)']				    = $front_prefix.'trade/getpairdetails/$1';
$route['trade/pairdetails/(:any)']				    = $front_prefix.'trade/pairdetails/$1';



$route['trade/getPairs2/(:any)']							= $front_prefix.'trade/getPairs2/$1'; 

$route['trade/gettradeopenOrders2/(:any)/(:any)/(:any)']			= $front_prefix.'trade/gettradeopenOrders2/$1/$2/$3';

$route['trade/getOrderHistory/(:any)/(:any)']				= $front_prefix.'trade/getOrderHistory/$1/$2';

$route['trade/getSession/(:any)']							= $front_prefix.'trade/getSession/$1';

// Node Balance Update

$route['trade/update_balance_node/(:any)/(:any)/(:any)'] = $front_prefix.'trade/updateBalance/$1/$2/$3';
$route['trade/updateAdminBalance/(:any)/(:any)'] = $front_prefix.'trade/getSession/$1/$2';



$route['trade/loginCheck/(:any)/(:any)/(:any)']				= $front_prefix.'trade/login_check/$1/$2/$3';

$route['trade/registerCheck/(:any)/(:any)/(:any)']	= $front_prefix.'trade/register_check/$1/$2/$3/';

$route['trade/forgotPwdCheck/(:any)']						= $front_prefix.'trade/forgot_check/$1';

$route['trade/getCurrencies/(:any)/(:any)']					= $front_prefix.'trade/getCurrencies/$1/$2';

$route['trade/getTokenPrice/(:any)']						= $front_prefix.'trade/getTokenPrice/$1';

$route['trade/setLogin/(:any)/(:any)']						= $front_prefix.'trade/setLogin/$1/$2';

$route['trade/logout/(:any)']								= $front_prefix.'trade/logout/$1';

$route['trade/execute_order/execute'] 								= $front_prefix.'trade/execute_order/$1';


$route['trade/close_active_order/(:any)/(:any)/(:any)'] = $front_prefix.'trade/cancel_active_order/$1/$2/$3';

$route['common/trade_integration/(:any)/(:any)/(:any)/(:any)'] = $front_prefix.'common/trade_integration/$1/$2/$3/$4';

$route['trade/market_api_trades/(:any)'] 					= $front_prefix.'trade/market_api_trades/$1';

$route['trade/getLanguage/(:any)'] 							= $front_prefix.'trade/getLanguage/$1';

$route['trade/setLanguage/(:any)'] 							= $front_prefix.'trade/setLanguage/$1';

$route['trade/getRecentPrice/(:any)'] 						= $front_prefix.'trade/getRecentPrice/$1';
$route['trade/get_favourite/(:any)'] 						= $front_prefix.'trade/get_favourite/$1';
$route['trade/add_favourite/(:any)/(:any)'] 				= $front_prefix.'trade/add_favourite/$1/$2';
$route['trade/check_favourite/(:any)/(:any)'] 				= $front_prefix.'trade/check_favourite/$1/$2';
$route['add_referral_on_trade'] 				            = $front_prefix.'common/add_referral_on_trade';

// Paypal

$route['paypal']				        					=	$front_prefix.'user/paypal';
$route['paypal_withdraw']				=	$front_prefix.'user/paypal_withdraw';
$route['paypal_success']				=	$front_prefix.'user/paypal_success';
$route['paypal_success_withdraw']		=	$front_prefix.'user/paypal_success_withdraw';
$route['paypal_cancel']				=	$front_prefix.'user/paypal_cancel';
 


// api routes end
$route['get_chart_record'] 									= 	$front_prefix.'common/get_chart_record';
$route['get_chart_record_month'] 							= 	$front_prefix.'common/get_chart_record_month';
$route['get_chart_record_hour/(:any)'] 						= 	$front_prefix.'common/get_chart_record_hour/$1';
$route['get_depthchart'] 									= 	$front_prefix.'common/get_depthchart';
$route['localpair_details'] 								= $front_prefix.'common/localpair_details';
$route['getcurrency_localdetails'] = $front_prefix.'common/getcurrency_localdetails';
$route['force_logout'] 										= $front_prefix.'user/force_logout';


//$route['newget_chart_record'] 					= 	$front_prefix.'common/newget_chart_record';
$route['newget_chart_record/(:any)/(:any)'] 	= 	$front_prefix.'common/newget_chart_record/$1/$2';
// $route['newget_chart_record/(:any)'] 				= 	$front_prefix.'common/newget_chart_record/$1'; 
$route['local_coin_order_chart'] 	= 	$front_prefix.'common/local_coin_order_chart';
$route['local_coin_order_chart_hour/(:any)'] 	= 	$front_prefix.'common/local_coin_order_chart_hour/$1';



$route['newdepthchart'] = $front_prefix.'common/newdepthchart';
$route['newdepthchart/(:any)'] = $front_prefix.'common/newdepthchart/$1';

$route['add_favpair'] = $front_prefix.'trade/add_favpair';

$route['remove_favpair'] = $front_prefix.'trade/remove_favpair';

$route['rem_favpair_trade'] = $front_prefix.'trade/rem_favpair';

$route['add_favpair_trade'] = $front_prefix.'trade/add_favpair';

$route['search_pair'] = $front_prefix.'common/search_pair';
$route['favsearch_pair'] = $front_prefix.'common/favsearch_pair';

$route['search_pair_trade'] = $front_prefix.'trade/search_pair';
$route['favsearch_pair_trade'] = $front_prefix.'trade/favsearch_pair';

$route['appendfav'] = $front_prefix.'trade/appendfav';

$route['buytoken'] = $front_prefix.'common/buytoken';

// $route['move_to_admin_wallet/(:any)'] = $front_prefix.'user/move_to_admin_wallet/$1';

$route['move_to_admin_wallet'] = $front_prefix.'user/move_to_admin_wallet';
 

$route['token'] = $front_prefix.'common/token';

$route['trade/close_allactive_order/(:any)'] 					= 	$front_prefix.'trade/close_allactive_order/$1';

$route['close_active_order'] 						= 	$front_prefix.'trade/close_active_order';
$route['close_active_orders'] 						= 	$front_prefix.'common/close_active_order';

$route['common_test_details'] 						= 	$front_prefix.'common/common_test_details';
$route['add_newtoken_adminwallet'] 					= 	$front_prefix.'common/add_newtoken_adminwallet';
$route['add_newtoken_userwallet'] 					= 	$front_prefix.'common/add_newtoken_userwallet'; 




$route['update_externalpairs'] 						= 	$front_prefix.'common/update_externalpairs';
$route['pairs_ajaxupdate'] 						    = 	$front_prefix.'common/pairs_ajaxupdate';
$route['update_wizard/(:any)'] 						= 	$front_prefix.'common/update_wizard/$1';
$route['add_coin']								=	$front_prefix.'user/add_coin';
$route['buycrpy'] = $front_prefix.'user/buycrpy';
$route['getresponse_wyre/(:any)'] = $front_prefix.'user/getresponse_wyre/$1';
$route['getfailureresponse_wyre/(:any)'] = $front_prefix.'user/getfailureresponse_wyre/$1';
$route['transfer_to_admin_wallet/(:any)'] = $front_prefix.'user/transfer_to_admin_wallet/$1';
$route['api_orders'] = $front_prefix.'common/market_api_orders';
$route['fees'] = $front_prefix.'common/fees';
$route['public_api'] = $front_prefix.'common/public_api';
$route['coin_list'] = $front_prefix.'common/coin_list';
$route['coin_list/(:any)'] = $front_prefix.'common/coin_list/$1';
$route['news'] = $front_prefix.'common/news';

$route['applytolist'] = $front_prefix.'user/apply_to_list';
$route['phone_verification'] = $front_prefix.'user/phone_verification';
$route['phoneupdate']=$front_prefix.'user/phoneupdate';
$route['history']=$front_prefix.'user/report';
$route['referral']=$front_prefix.'user/referral';
$route['test']=$front_prefix.'user/test';
$route['email']=$front_prefix.'user/email';
$route['mail_check']=$front_prefix.'user/mail_check';
$route['kyc_profile']=$front_prefix.'user/kyc_profile';
$route['invite'] = $front_prefix.'user/signup';
$route['kyc']=$front_prefix.'user/kyc';
$route['kyc_verified_plus']=$front_prefix.'user/kyc_verified_plus';
$route['price_ajaxupdate']		=	$front_prefix.'common/price_ajaxupdate';
$route['get_pairinfo']		=	$front_prefix.'common/get_pairinfo';
// $route['create_offer']=$front_prefix.'Ptwoptrade/create_offer';
// $route['p2p']=$front_prefix.'Ptwoptrade';
// $route['p2p_gettrade']=$front_prefix.'Ptwoptrade/p2p_gettrade';
// $route['purchase_bitcoin/(:any)']=$front_prefix.'Ptwoptrade/purchase_bitcoin/$1';
// $route['p2ptrade/(:any)/(:any)/(:any)'] =$front_prefix.'Ptwoptrade/buytrade/$1/$1/$1';
// $route['p2ptrade_sell/(:any)/(:any)'] =$front_prefix.'Ptwoptrade/selltrade/$1/$1';
// $route['release_bitcoins/(:any)/(:any)/(:any)']	=	$front_prefix.'Ptwoptrade/release_bitcoins/$1/$2/$3';
// $route['user_bank_details']=$front_prefix.'user/user_bank_details';
// $route['announcement'] = $front_prefix.'user/announcement';
// $route['add_chat_data']=$front_prefix.'Ptwoptrade/add_chat_data';
// $route['chatimgupload']=$front_prefix.'Ptwoptrade/chatimgupload';
// $route['canceloffertime']=$front_prefix.'Ptwotrade/canceloffertime';
// $route['p2ptrade_history']=$front_prefix.'Ptwoptrade/trade_history';
// $route['ajax_history']=$front_prefix.'Ptwoptrade/ajax_history';
// $route['myoffer']=$front_prefix.'Ptwoptrade/myoffer';
// $route['ajax_myoffer']=$front_prefix.'Ptwoptrade/ajax_myoffer';
// $route['editoffer/(:any)']=$front_prefix.'Ptwoptrade/editoffer/$1';
// $route['activeorder']=$front_prefix.'Ptwoptrade/activeorder';
// $route['ajax_activeorder']=$front_prefix.'Ptwoptrade/ajax_activeorder';
// $route['payment_status']=$front_prefix.'Ptwoptrade/payment_status';


$route['fiat_deposit'] 				 = $front_prefix.'user/fiat_deposit';
$route['bot_order']		=	$front_prefix.'common/bot_order';

$route['api/v1/assets'] 	= 	$front_prefix.'common/assets';
$route['api/v1/orderbook/(:any)'] 	= 	$front_prefix.'common/market_api_depth/$1';
// $route['api/v1/orderbook/(:any)/(:any)'] 	= 	$front_prefix.'common/market_api_depth/$1/$2';
// $route['api/v1/orderbook/(:any)/(:any)'] 	= 	$front_prefix.'common/market_api_depth/$1/$3';
$route['api/v1/trade_pairs'] 			= 	$front_prefix.'common/market_api_list';
$route['api/v1/trades/(:any)'] 	= 	$front_prefix.'common/trades/$1';
$route['api/v1/ticker'] 	= 	$front_prefix.'common/ticker';
$route['api/v1/availablepairs'] 	= 	$front_prefix.'common/available_pairs';
$route['api/v1/price/(:any)/(:any)'] 	= 	$front_prefix.'common/price_conversion/$1/$2';
$route['phonecheck']=$front_prefix.'user/phonecheck';

$route['reg_subscribe'] 	= $front_prefix.'common/reg_subscribe';

$route['market']				        =	$front_prefix.'common/market';
$route['unsubscribe/(:any)'] = $front_prefix.'common/unsubscribe/$1'; 
$route['settings'] 			= $front_prefix.'user/settings';
$route['address_verification'] 	= $front_prefix.'user/address_verification';
$route['id_verification'] 		= $front_prefix.'user/id_verification';
$route['photo_verification'] 	= $front_prefix.'user/photo_verification'; 
$route['dashboard'] 	= $front_prefix.'user/dashboard';

$route['customer_delete/(:any)']	=	$front_prefix.'user/customer_delete/$1';

$route['newtradechart_check/(:any)'] 	= 	$front_prefix.'common/newtradechart_check/$1';
$route['trade/getSiteSetting/(:any)']							= $front_prefix.'trade/getSiteSetting/$1';
$route['move_to_admin_wallet/(:any)/(:any)'] 					= 	$front_prefix.'user/move_to_admin_wallet/$1/$2'; 


$route['trade/get_active_order/(:any)/(:any)'] = $front_prefix.'trade/get_active_order/$1/$2';
$route['trade/get_close_orders/(:any)/(:any)'] = $front_prefix.'trade/get_close_orders/$1/$2'; 


// P2P Routes

$route['create_offer']=$front_prefix.'P2p/create_offer';
$route['p2p_gettrade']=$front_prefix.'P2p/p2p_gettrade';
$route['offer']=$front_prefix.'P2p/offer';
$route['p2porder']=$front_prefix.'P2p/p2porder';
$route['p2p_chart/(:any)/(:any)/(:any)'] = $front_prefix.'P2p/chat/$1/$2/$3';
// $route['p2p_orderconfirm/(:any)/(:any)'] = $front_prefix.'P2p/p2p_orderconfirm/$1/$2';

$route['trade/p2p_orderconfirm/(:any)/(:any)'] = $front_prefix.'P2p/p2p_orderconfirm/$1/$2';


//Angular P2P
$route['trade/p2p_release/(:any)/(:any)'] 		 = $front_prefix.'P2p/p2p_release/$1/$2';
$route['trade/p2p_chat/(:any)/(:any)'] 		     = $front_prefix.'P2p/p2p_chat/$1/$2';
$route['trade/chat/p2psend_message'] 		     = $front_prefix.'P2p/p2psend_message';
$route['trade/chat/dispute'] 		    		 = $front_prefix.'P2p/dispute';
$route['p2p_history'] 		    			 	 = $front_prefix.'P2p/history';
$route['p2p_cancel/(:any)'] 		    		 = $front_prefix.'P2p/cancel/$1';



$route['test_chat'] 		 = $front_prefix.'P2p/test_chat';

$route['test_signup'] 		 = $front_prefix.'User/test_signup';

$route['bank_details/(:any)'] 	 = $front_prefix.'user/bank_details/$1';
$route['currency_convert'] 	     = $front_prefix.'user/currency_convert';


//---------------CPM APP ------------->>>>>>>>>>>>>>>>>>>>>>>>>>>------------>>>>>>>>>>>>>

$route['signup_app'] 	= $front_prefix.'user_app/signup_app';

$route['login_check_app'] 	= $front_prefix.'user_app/login_check_app';

$route['forgot_check_app'] 	= $front_prefix.'user_app/forgot_check_app';

$route['changepassword'] 	= $front_prefix.'user_app/changepassword';
$route['pwd'] 	= $front_prefix.'user/pwd';

$route['editprofile'] 	= $front_prefix.'user_app/editprofile';

$route['update_bank_details_app'] 	= $front_prefix.'user_app/update_bank_details_app';

$route['security_app'] 	= $front_prefix.'user_app/security_app';

$route['kyc_verification_app'] 	= $front_prefix.'user_app/kyc_verification_app';

// Address


$route['kyc_address'] 	= $front_prefix.'user_app/kyc_address';
$route['kyc_identity'] 	= $front_prefix.'user_app/kyc_identity';
$route['kyc_selfie'] 	= $front_prefix.'user_app/kyc_selfie';




$route['countries_app'] 	= $front_prefix.'user_app/countries_app';

$route['userdetails'] 	= $front_prefix.'user_app/userdetails';

$route['market_app'] 	= $front_prefix.'user_app/market_app';

$route['support_app'] 	= $front_prefix.'user_app/support_app';

$route['category'] 	= $front_prefix.'user_app/category';

$route['user_kyc_details'] 	= $front_prefix.'user_app/user_kyc_details';

$route['support_list'] 	= $front_prefix.'user_app/support_list';

$route['support_reply'] 	= $front_prefix.'user_app/support_reply';

$route['support_reply_list'] 	= $front_prefix.'user_app/support_reply_list';

$route['currency'] 	= $front_prefix.'user_app/currency';

$route['tfa_status'] 	= $front_prefix.'user_app/tfa_status';

$route['user_bank_list'] 	= $front_prefix.'user_app/user_bank_list';

$route['currency_deposits'] 	= $front_prefix.'user_app/currency_deposits';

$route['deposit_history'] 	= $front_prefix.'user_app/deposit_history';

$route['withdraw_app'] 	= $front_prefix.'user_app/withdraw_app';

$route['crypto_fiat_withdraw'] 	= $front_prefix.'user_app/crypto_fiat_withdraw';

$route['withdraw_assets'] 	= $front_prefix.'user_app/withdraw_assets';

$route['withdraw_history'] 	= $front_prefix.'user_app/withdraw_history';


//----------------------------- P2P -------------------------////------------------------



$route['create_offer_app'] 	= $front_prefix.'user_app/create_offer_app';

$route['create_offer_userdetails'] 	= $front_prefix.'user_app/create_offer_userdetails';

$route['p2p_openorder'] 	= $front_prefix.'user_app/p2p_history_order';

$route['p2p_open_order_cancel'] 	= $front_prefix.'user_app/p2p_open_order_cancel';
$route['user_balance_app'] 	= $front_prefix.'user_app/user_balance_app';

$route['p2p_completeorder_history'] 	= $front_prefix.'user_app/p2p_create_order_history';

$route['p2porder_map'] 	= $front_prefix.'user_app/p2porder_map';

$route['p2p_allorders'] 	= $front_prefix.'user_app/p2p_allorders'; 

$route['p2p_chat_app'] 		= $front_prefix.'user_app/p2p_chat_app'; 

$route['p2p_chat_records'] 		= $front_prefix.'user_app/p2p_chat_records'; 

$route['p2psend_message_app'] 		= $front_prefix.'user_app/p2psend_message_app'; 

$route['p2p_orderconfirm_app'] 		= $front_prefix.'user_app/p2p_orderconfirm_app'; 


$route['dispute_app'] =  $front_prefix.'user_app/dispute_app'; 


//----------------------------- P2P END -------------------------////------------------------



//-----------------------------Exchange-------------------------////------------------------

$route['execute_order_app'] 	= $front_prefix.'user_app/execute_order_app';

$route['close_active_order_app'] 	= $front_prefix.'user_app/close_active_order_app';

$route['trade_basic_web'] = $front_prefix.'user_app/trade_basic';

$route['user_tradeorders'] = $front_prefix.'user_app/user_tradeorders';

// Wallet 
$route['wallet_app'] = $front_prefix.'user_app/wallet_app';

$route['favorite_app'] = $front_prefix.'user_app/favorite_app';


// Ethers

$route['crypto_deposit/(:any)'] = $front_prefix.'user/crypto_deposit/$1';

//stacking

$route['staking'] 	= $front_prefix.'user/staking';

$route['redeem'] 	= $front_prefix.'user/redeem';



// Angular SPOT Fiat Trade
$route['trade/spot/spotorder'] 		    		 = $front_prefix.'P2p/spotorder';
$route['trade/spot/userCPMBankList/(:any)'] 		     = $front_prefix.'P2p/userCPMBankList/$1';
$route['trade/spot/userSpotOrders/(:any)'] 		     = $front_prefix.'P2p/userSpotOrders/$1';
$route['trade/spot/Notify_seller'] 		    		 = $front_prefix.'P2p/Notify_seller';


$route['trade/spot/p2pMapping'] 		    		 = $front_prefix.'Spot/p2porder';
// $route['trade/spot/GetP2pOrders'] 		    		 = $front_prefix.'Spot/GetP2pOrders';
$route['trade/spot/GetP2pOrders/(:any)/(:any)/(:any)'] 		    		 = $front_prefix.'Spot/GetP2pOrders/$1/$2/$3';


$route['trade/spot/FiatP2pCurrencieslist'] 	 = $front_prefix.'Spot/FiatP2pCurrencieslist';
$route['trade/spot/CountryFiat'] 	 = $front_prefix.'Spot/CountryFiat';


// Convert

$route['trade/convert/marketprice/(:any)'] 		    		 = $front_prefix.'P2p/marketprice/$1';

