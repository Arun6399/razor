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
$front_prefix												= 'bidex_front/';
$route['default_controller'] 								= $front_prefix.'common';
$route['404_override'] 										= '';
$route['translate_uri_dashes'] 								= FALSE;
$route['bidex_admin'] 										= 'bidex_admin/admin';
$route['bidex_admin/(:any)'] 								= 'bidex_admin/$1';
$route['bidex_admin/(:any)/(:any)'] 						    = 'bidex_admin/$1/$2';
$route['bidex_admin/(:any)/(:any)/(:any)'] 					= 'bidex_admin/$1/$2/$3';
$route['bidex_admin/(:any)/(:any)/(:any)/(:any)'] 			= 'bidex_admin/$1/$2/$3/$4';
$route['bidex_admin/admin/(:any)'] 							= 'bidex_admin/admin/$1';
$route['home'] 												= $front_prefix.'common/index';

//User
$route['register'] 											= $front_prefix.'user/register';
$route['signup'] 											= $front_prefix.'user/signup';

$route['login'] 											= $front_prefix.'user/login';
$route['login_check'] 										= $front_prefix.'user/login_check';

// Temp Routes
$route['signup_pilagsfdkuafjdskghfdgukjfhgduyifdsahkfdsiluyfdskh'] = $front_prefix.'user/signup_pila';
$route['login_pilagsfdkuafjdskghfdgukjfhgduyifdsahkfdsiluyfdskh'] 										= $front_prefix.'user/login_pila';



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
// $route['support'] 										    = $front_prefix.'user/support';
$route['support'] 										    = $front_prefix.'common/support_new';

$route['support'] 										    = $front_prefix.'user/support';
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
$route['contact_us']   =	$front_prefix.'common/contact_us';
$route['contac_home']   =	$front_prefix.'common/contac_home';


$route['airdrops']                                          = $front_prefix.'common/airdrops';
$route['deposit'] 								            = $front_prefix.'user/deposit';
$route['deposit/(:any)'] 								    = $front_prefix.'user/deposit/$1';
$route['wallet'] 								            = $front_prefix.'user/wallet';
$route['withdraw'] 										    = $front_prefix.'user/withdraw';
$route['withdraw/(:any)'] 									= $front_prefix.'user/withdraw/$1';
$route['change_address'] 									= $front_prefix.'user/change_address';
$route['change_chain_address']                              = $front_prefix.'user/change_chain_address';
$route['withdraw_coin_user_confirm/(:any)']					= $front_prefix.'user/withdraw_coin_user_confirm/$1';
$route['withdraw_coin_user_cancel/(:any)']			        = $front_prefix.'user/withdraw_coin_user_cancel/$1';
$route['update_user_address'] 									= $front_prefix.'user/update_user_address';

$route['update_crypto_deposits/(:any)'] = $front_prefix.'user/update_crypto_deposits/$1';

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

$route['trade/getPairs2/(:any)']							= $front_prefix.'trade/getPairs2/$1';

$route['trade/gettradeopenOrders2/(:any)/(:any)']			= $front_prefix.'trade/gettradeopenOrders2/$1/$2';

$route['trade/getOrderHistory/(:any)/(:any)']				= $front_prefix.'trade/getOrderHistory/$1/$2';

$route['trade/getSession/(:any)']							= $front_prefix.'trade/getSession/$1';

$route['trade/loginCheck/(:any)/(:any)/(:any)']				= $front_prefix.'trade/login_check/$1/$2/$3';

$route['trade/registerCheck/(:any)/(:any)/(:any)']	= $front_prefix.'trade/register_check/$1/$2/$3/';

$route['trade/forgotPwdCheck/(:any)']						= $front_prefix.'trade/forgot_check/$1';

$route['trade/getCurrencies/(:any)/(:any)']					= $front_prefix.'trade/getCurrencies/$1/$2';

$route['trade/getTokenPrice/(:any)']						= $front_prefix.'trade/getTokenPrice/$1';

$route['trade/setLogin/(:any)/(:any)']						= $front_prefix.'trade/setLogin/$1/$2';

$route['trade/logout/(:any)']								= $front_prefix.'trade/logout/$1';

$route['trade/execute_order/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = $front_prefix.'trade/execute_order/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10/$11';

$route['trade/close_active_order/(:any)/(:any)/(:any)'] = $front_prefix.'trade/cancel_active_order/$1/$2/$3';

$route['common/trade_integration/(:any)/(:any)/(:any)/(:any)'] = $front_prefix.'common/trade_integration/$1/$2/$3/$4';

$route['trade/market_api_trades/(:any)'] 					= $front_prefix.'trade/market_api_trades2/$1';

$route['trade/getLanguage/(:any)'] 							= $front_prefix.'trade/getLanguage/$1';

$route['trade/setLanguage/(:any)'] 							= $front_prefix.'trade/setLanguage/$1';

$route['trade/getRecentPrice/(:any)'] 						= $front_prefix.'trade/getRecentPrice/$1';
$route['trade/get_favourite/(:any)'] 						= $front_prefix.'trade/get_favourite/$1';
$route['trade/add_favourite/(:any)/(:any)'] 				= $front_prefix.'trade/add_favourite/$1/$2';
$route['trade/check_favourite/(:any)/(:any)'] 				= $front_prefix.'trade/check_favourite/$1/$2';
$route['add_referral_on_trade'] 				            = $front_prefix.'common/add_referral_on_trade';

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
//$route['newget_chart_record/(:any)/(:any)'] 	= 	$front_prefix.'common/newget_chart_record/$1/$2';
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
$route['move_to_admin_wallet/(:any)/(:any)'] = $front_prefix.'user/move_to_admin_wallet/$1/$2';

// $route['move_to_admin_wallet/(:any)'] = $front_prefix.'user/move_to_admin_wallet/$1';
$route['token'] = $front_prefix.'common/token';

$route['trade/close_allactive_order/(:any)'] 					= 	$front_prefix.'trade/close_allactive_order/$1';

$route['close_active_order'] 						= 	$front_prefix.'trade/close_active_order';
$route['close_active_orders'] 						= 	$front_prefix.'common/close_active_order';

$route['common_test_details'] 						= 	$front_prefix.'common/common_test_details';
$route['add_newtoken_adminwallet'] 					= 	$front_prefix.'common/add_newtoken_adminwallet';

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
$route['history']=$front_prefix.'user/history';
$route['referral']=$front_prefix.'user/referral';
$route['test']=$front_prefix.'user/test';
$route['email']=$front_prefix.'user/email';
$route['mail_check']=$front_prefix.'user/mail_check';
$route['kyc_profile']=$front_prefix.'user/kyc_profile';
// $route['invite'] = $front_prefix.'user/signup';
$route['invite']                   = $front_prefix.'user/register';
$route['kyc']=$front_prefix.'user/kyc';
$route['kyc_verified_plus']=$front_prefix.'user/kyc_verified_plus';
$route['price_ajaxupdate']		=	$front_prefix.'common/price_ajaxupdate';
$route['get_pairinfo']		=	$front_prefix.'common/get_pairinfo';
$route['create_offer']=$front_prefix.'Ptwoptrade/create_offer';
$route['p2p']=$front_prefix.'Ptwoptrade';
$route['p2p_gettrade']=$front_prefix.'Ptwoptrade/p2p_gettrade';
$route['purchase_bitcoin/(:any)']=$front_prefix.'Ptwoptrade/purchase_bitcoin/$1';
$route['p2ptrade/(:any)/(:any)/(:any)'] =$front_prefix.'Ptwoptrade/buytrade/$1/$1/$1';
$route['p2ptrade_sell/(:any)/(:any)'] =$front_prefix.'Ptwoptrade/selltrade/$1/$1';
$route['release_bitcoins/(:any)/(:any)/(:any)']	=	$front_prefix.'Ptwoptrade/release_bitcoins/$1/$2/$3';
$route['user_bank_details']=$front_prefix.'user/user_bank_details';
$route['announcement'] = $front_prefix.'user/announcement';
$route['add_chat_data']=$front_prefix.'Ptwoptrade/add_chat_data';
$route['chatimgupload']=$front_prefix.'Ptwoptrade/chatimgupload';
$route['canceloffertime']=$front_prefix.'Ptwotrade/canceloffertime';
$route['p2ptrade_history']=$front_prefix.'Ptwoptrade/trade_history';
$route['ajax_history']=$front_prefix.'Ptwoptrade/ajax_history';
$route['myoffer']=$front_prefix.'Ptwoptrade/myoffer';
$route['ajax_myoffer']=$front_prefix.'Ptwoptrade/ajax_myoffer';
$route['editoffer/(:any)']=$front_prefix.'Ptwoptrade/editoffer/$1';
$route['activeorder']=$front_prefix.'Ptwoptrade/activeorder';
$route['ajax_activeorder']=$front_prefix.'Ptwoptrade/ajax_activeorder';
$route['payment_status']=$front_prefix.'Ptwoptrade/payment_status';


$route['fiat_deposit'] 				 = $front_prefix.'user/fiat_deposit';
$route['bot_order']		=	$front_prefix.'common/bot_order';

$route['settings_account']								=	$front_prefix.'user/settings_account';
$route['settings_security']								=	$front_prefix.'user/settings_security';

$route['edit_bank_account']					=	$front_prefix.'user/update_bank_details';
$route['add_bank_account']					=	$front_prefix.'user/add_bank_details';
$route['view_bank_account']					=	$front_prefix.'user/view_bank_details';
$route['delete_bank_account/(:any)']					=	$front_prefix.'user/delete_bank_account/$1';

$route['update_bank_details']					            = $front_prefix.'user/update_bank_details';
$route['add_bank_details']					            = $front_prefix.'user/add_bank_details';
$route['view_bank_details']					            = $front_prefix.'user/view_bank_details';

$route['public_api'] = $front_prefix.'common/public_api';

$route['settings_profile'] 				= 	$front_prefix.'user/settings_profile';

$route['profile-edit'] 										= $front_prefix.'user/editprofile';

$route['markets'] = 	$front_prefix.'common/markets';
$route['address_verification'] 								= $front_prefix.'user/address_verification';
$route['id_verification'] 								= $front_prefix.'user/id_verification';
$route['photo_verification'] 								= $front_prefix.'user/photo_verification';

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


$route['googlelogin'] 				= 	$front_prefix.'user/googlelogin';
$route['fblogin'] 				= 	$front_prefix.'user/fblogin';

$route['fbcallback'] 				= 	$front_prefix.'user/fbcallback';

$route['googlesignup'] 				= 	$front_prefix.'user/googlesignup';
$route['fbsignup'] 				= 	$front_prefix.'user/fbsignup';

$route['fbsignupcallback'] 				= 	$front_prefix.'user/fbsignupcallback';

// Angular Call
$route['trade/getSession/(:any)']							= $front_prefix.'trade/getSession/$1';
$route['trade/getUserEmail/(:any)']							= $front_prefix.'trade/getUserEmail/$1';
$route['trade/update_balance_node/(:any)/(:any)/(:any)'] = $front_prefix.'trade/updateBalance/$1/$2/$3';
$route['trade/updateAdminBalance/(:any)/(:any)']							= $front_prefix.'trade/updateAdminBalance/$1/$2';
$route['newtradechart_check/(:any)'] 	= 	$front_prefix.'common/newtradechart_check/$1';


// <--------------- app api--------------->
$route['signup_app']=$front_prefix.'User_app/signup_app';

$route['login_check_app']=$front_prefix.'User_app/login_check_app';

$route['forgot_check_app']=$front_prefix.'User_app/forgot_check_app';

$route['reset_pw_user_app']=$front_prefix.'User_app/reset_pw_user_app';

$route['editprofile_app']=$front_prefix.'User_app/editprofile_app';

$route['address_verification_app']=$front_prefix.'User_app/address_verification_app';

$route['id_verification_app']=$front_prefix.'User_app/id_verification_app';

$route['photo_verification_app']=$front_prefix.'User_app/photo_verification_app';

$route['settings_security_app']=$front_prefix.'User_app/settings_security_app';

$route['countries']=$front_prefix.'User_app/countries';

$route['currency_home']=$front_prefix.'User_app/currency_home';

$route['currency_wallet']=$front_prefix.'User_app/currency_wallet';
$route['markets_web']                                           = $front_prefix.'user_app/markets';

$route['newtradechart_check_web/(:any)']     = $front_prefix.'user_app/newtradechart_check/$1';

$route['deposits_web']                                           = $front_prefix.'user_app/deposits';

$route['withdraw_web']                                           = $front_prefix.'user_app/withdraw';
$route['withdraw_web_confirm']                                           = $front_prefix.'user_app/withdraw_web_coin';

$route['wallets_web']                                           = $front_prefix.'user_app/wallets';
$route['usd_balance'] = $front_prefix.'user_app/usd_balance';
$route['transaction_history_app'] = $front_prefix.'user_app/transaction_history_app';
$route['currency_usd_balance'] = $front_prefix.'user_app/currency_usd_balance';
$route['trades'] = $front_prefix.'user_app/trades';
$route['check_profile_app'] = $front_prefix.'user_app/check_profile_app';

$route['security_app'] = $front_prefix.'user_app/security';
$route['social_app'] = $front_prefix.'user_app/social_app';
$route['social_login_app'] = $front_prefix.'user_app/social_login_app';
$route['support_app'] = $front_prefix.'user_app/support_app';
$route['contact_us_app'] = $front_prefix.'user_app/contact_us_app';
$route['favorite_app'] = $front_prefix.'user_app/favorite_app';
$route['add_bank_details_app'] = $front_prefix.'user_app/add_bank_details_app';
$route['update_bank_details_app'] = $front_prefix.'user_app/update_bank_details_app';
$route['kyc_photo_status_app'] = $front_prefix.'user_app/kyc_photo_status';
$route['swap']                 = $front_prefix.'user/swap';
$route['save_swap']            = $front_prefix.'user/save_swap'; 
$route['get_currency_fees']  = $front_prefix.'user/get_currency_fees';
// Trade Order API
$route['execute_order_web']				=	$front_prefix.'user_app/execute_order';
$route['trade_integration_web']				=	$front_prefix.'user_app/trade_integration';
$route['trade_basic_web']				=	$front_prefix.'user_app/trade_basic';
$route['close_active_order_web']				=	$front_prefix.'user_app/close_active_order';
$route['trade/sitevisits/(:any)']							= $front_prefix.'trade/sitevisits/$1';


$route['check_currency_status_app']				=	$front_prefix.'user_app/check_currency_status';

$route['get_crypto_user_address_app']				=	$front_prefix.'user_app/get_crypto_user_address';

$route['update_crypto_user_address_app']				=	$front_prefix.'user_app/update_crypto_user_address';



$route['faq_list']                                               = $front_prefix.'user/faq'; 

$route['test_email'] = $front_prefix.'common/test_email';
$route['transactions_history'] 						= $front_prefix.'user/transactions_history';
$route['privacy_policy']=$front_prefix.'user/privacy_policy';




$route['tester']=$front_prefix.'common/tester';


$route['gen']=$front_prefix.'user/gen';
