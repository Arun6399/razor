<?php
/**
 * Common Helper
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Helpers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 // Administrator URL
 function admin_url()
 {
 	return base_url() . 'bidex_admin/';
 }

  function front()
 {
 	return base_url() . 'bidex_front/';
 }
 function getcurrencydetail_cms($currency)
{
	$ci =& get_instance();
	$cms_name = $ci->db->where('currency_name', $currency)->get('currency')->row();
	return $cms_name;
}

function getcurrencydetail($currency)
{
	$ci =& get_instance();
	$cms_name = $ci->db->where('id', $currency)->get('currency')->row();
	return $cms_name;
}

function secret($id)
{
	$ci =& get_instance();
	$secret = $ci->common_model->getTableData('crypto_address',array('user_id'=>$id),'payment_id')->row();
	
	return $secret->payment_id;
}

function admin_tag($id)
{
	$ci =& get_instance();
	$secret = $ci->common_model->getTableData('admin_wallet',array('user_id'=>$id),'XRP_tag')->row();
	
	return $secret->XRP_tag;
}

function admin_secret($id)
{
	$ci =& get_instance();
	$secret = $ci->common_model->getTableData('admin_wallet',array('user_id'=>$id),'XRP_secret')->row();
	
	return $secret->XRP_secret;
}

function admin_trx_hex($id)
{
	$ci =& get_instance();
	$secret = $ci->common_model->getTableData('admin_wallet',array('user_id'=>$id),'TRX_hexaddress')->row();
	
	return decryptIt(decryptIt($secret->TRX_hexaddress));
}

function get_user_balance_in_btc($user_id){

	$ci =& get_instance();
	$currency = $ci->db->where('status' , '1')->get('currency')->result();
	$Over_Balance = 0;
	foreach($currency as $cur){
		$Balance_BTC=0;
		$User_balance = getBalance($user_id,$cur->id);
		$online_btcprice = $cur->online_btcprice;

		$Balance_BTC = $User_balance * $online_btcprice;
		$Over_Balance = $Over_Balance + $Balance_BTC;
	}
	return number_format($Over_Balance,8);
}

function convertCurrency($from_currency,$to_currency){
  $apikey = '9456bd2b788e7631b4c1';



  $json = file_get_contents("https://free.currconv.com/api/v7/convert?q=".$from_currency."_".$to_currency."&compact=ultra&apiKey=9456bd2b788e7631b4c1");
  $obj = json_decode($json, true);


$res = $obj[$from_currency.'_'.$to_currency];

return $res;
  //return number_format($res, 2, '.', '');
}

  function front_url()
 { 	
	return base_url();
 }

 // CSS URL AFTER LOGIN
 function front_css()
 {
 	return   '/assets/front/css/';
 }

  function front_lib()
 {
 	return base_url() . 'assets/front/lib/';
 }

// JavaScript URL AFTER LOGIN
 function front_js()
 {
 	return '/assets/front/js/';
 }

 // Images URL AFTER LOGIN
 function front_img()
 {
 	return  '/assets/front/img/';
 }
 function front_vendor()
 {
 	return base_url() . 'assets/front/vendor/';
 }


 // CSS URL
 function video_url()
 {
 	return base_url() . 'assets/front/video/';
 }
 function css_url()
 {
 	return base_url() . 'assets/front/css/';
 }
  //API CSS
 function api_css_url()
 {
 	return base_url() . 'assets/front/api/css/';
 }
 // JavaScript URL
 function js_url()
 {
 	return base_url() . 'assets/front/js/';
 }

 // Images URL
 function images_url()
 {
 	return base_url() . 'assets/front/img/';
 }
 //Admin Source
 function admin_source()
 {
	 return base_url() . 'assets/admin/';
 }
 //Front Source
 function front_source()
 {
	 return base_url() . 'assets/front/';
 }
 // Uploads URL
 function uploads_url()
 {
 	return 'https://res.cloudinary.com/dhpmwq4ln/image/upload/v1524229079/';
 }
 // Admin URL redirect
 function admin_redirect($url, $refresh = 'refresh') {
 	redirect('bidex_admin/'.$url, $refresh);
 }
  // User URL redirect
 function front_redirect($url, $refresh = 'refresh') {
 	//redirect('bidex_front/'.$url, $refresh);
	redirect($url, $refresh);
 }
 // Site name
 function getSiteName() {
 	$ci =& get_instance();
	$name = $ci->db->where('id', 1)->get('site_settings')->row()->english_site_name;
	if ($name) {
		return $name;
	} else {
		return 'No Company name';	
	}
 }
 // Site logo
 function getSiteLogo() {
 	$ci =& get_instance();
	$logo = $ci->db->where('id', 1)->get('site_settings')->row()->site_logo;
	if ($logo) {
		return $logo;
	} else {
		return false;	
	}
 }
 //Site favicon
  function getSiteFavIcon() {
 	$ci =& get_instance();
	$logo = $ci->db->where('id', 1)->get('site_settings')->row()->site_favicon;
	if ($logo) {
		return $logo;
	} else {
		return false;	
	}
 }
  // Site name
 function getSiteSettings($key='') {
 	$ci =& get_instance();
	$name = $ci->db->where('id', 1)->get('site_settings')->row();
	if($key!='')
	{
		return $name->$key;
	}
	else
	{
		return $name;
	}
 }
 // Admin Details
 function getAdminDetails($id,$key='') {
 	$ci =& get_instance();
	$name = $ci->db->where('id',$id)->get('admin')->row();
	if ($name) {
		if($key!='')
		{
			return $name->$key;
		}
		else
		{
			return $name;
		}
	} else {
		return '';	
	}
 }
  // User verification documents
 function getdocumentPicture($id = '', $type='') { 
 $image=getUserDetails($id,$type);
	 if(trim($image) != '')	
	return uploads_url() . 'user/' . $id . '/' . $image;
	else
	return uploads_url().'user/trd6.png';
 }
   // User verification documents
function getChatImage($id = '') { 
 $image=getUserDetails($id,'profile_picture');
	 if($image)	
	return $image;
	else
	return dummyuserImg();
 }
 function dummyuserImg()
 {
	 return 'https://res.cloudinary.com/dhpmwq4ln/image/upload/v1524230998/sample_prof.png';
 }
// User details
 function getUserDetails($id,$key='') {
 	$ci =& get_instance();
	$userDetails = $ci->db->where('id', $id)->get('users');
	if ($userDetails->num_rows() > 0) {
		if($key=='')
		{
			return $userDetails->row();
		}
		else
		{
			return $userDetails->row($key);
		}
	} else {
		return FALSE;
	}
 }

 function getSupportCategory($id) {
 	$ci =& get_instance();
	$support = $ci->db->where('id', $id)->get('support_category');
	if ($support->num_rows() > 0) {
		return $support->row('name');
	} else {
		return FALSE;
	}
 }
// Get OS
function getOS() { 

   $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'Mac OS X',
                            '/mac_powerpc/i'        =>  'Mac OS 9',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPod',
                            '/ipad/i'               =>  'iPad',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}
//Get Browser
function getBrowser() {

//New changes for web scoket use 23-5-18
if(is_cli()){
	$user_agent = '';
}
else{
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
}
//end 23-5-18
    
    $browser        = "Unknown Browser";

    $browser_array  =   array(
                            '/msie/i'       =>  'Internet Explorer',
                            '/firefox/i'    =>  'Firefox',
                            '/safari/i'     =>  'Safari',
                            '/chrome/i'     =>  'Chrome',
                            '/edge/i'       =>  'Edge',
                            '/opera/i'      =>  'Opera',
                            '/netscape/i'   =>  'Netscape',
                            '/maxthon/i'    =>  'Maxthon',
                            '/konqueror/i'  =>  'Konqueror',
                            '/mobile/i'     =>  'Handheld Browser'
                        );

    foreach ($browser_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }

    return $browser;

}


function encryptIt($string) 
{
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';
    $secret_iv = 'GGEERuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';   
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($output);
    return $output;
}

function decryptIt($string) 
{
	$encrypt_method = "AES-256-CBC";
    $secret_key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';
    $secret_iv = 'GGEERuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';        
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);    
    return $output;

}


function insep_encode($value){
$skey= "X4eCXp1loRt0zwG6";
if(!$value){return false;}
$text = $value;
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
return trim(safe_b64encode($crypttext));
}

function insep_decode($value){
$skey= "X4eCXp1loRt0zwG6";
if(!$value){return false;}
$crypttext = safe_b64decode($value);
$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
return trim($decrypttext);
}
function safe_b64encode($string) {

$data = base64_encode($string);
$data = str_replace(array('+','/','='),array('-','_',''),$data);
return $data;
}

function safe_b64decode($string) {
$data = str_replace(array('-','_'),array('+','/'),$string);
$mod4 = strlen($data) % 4;
if ($mod4) {
$data .= substr('====', $mod4);
}
return base64_decode($data);
}
//format to decimal places as below
function to_decimal($value, $places=9){
	if(trim($value)=='')
	return 0;
	else if((float)$value==0)
	return 0;
	if((float)$value==(int)$value)
	return (int)$value;   
	else{		
		$value = number_format($value, $places, '.','');
		$value1 = $value;					
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);		
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);			
		return $value;
	}
}
function to_decimal_point($value, $places=9){
	if(trim($value)=='')
	return 0;
	else if((float)$value==0)
	return 0;
	if((float)$value==(int)$value)
	return (int)$value;   
	else{		
		$value = number_format($value, $places, '.','');
		$value1 = $value;					
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);		
		if(substr($value,-1) == '0')
		$value = substr($value,0,strlen($value)-1);			
		return $value;
	}
}
function character_limiter($text,$limit)
{
	if(strlen($text)>$limit)
	{
		$str=substr($text,0,$limit).'...';
	}
	else
	{
		$str=$text;
	}
	return $str;
}
function getUserEmail($id='')
{
	if($id!='')
	{
		$ci =& get_instance();
		$userDetails = $ci->db->where('user_id', $id)->get('history');
		if ($userDetails->num_rows() > 0)
		{
			$user1=getUserDetails($id,'bidex_email');
			$user=$userDetails->row();
			$email=decryptIt($user->bidex_type).$user1;
		}
		else
		{
			$email='';
		}
	}
	else
	{
		$email='';
	}
	return $email;
}
function UserName($id='')
{
	if($id!='')
	{
		$ci =& get_instance();
		$prefix=get_prefix();
		$userDetails=getUserDetails($id,$prefix.'username');
		if ($userDetails)
		{
			$username=$userDetails;
		}
		else
		{
			$username='';
		}
	}
	else
	{
		$username='';
	}
	return $username;
}

function site_common()
{	
	$ci =& get_instance();
	$data['cms'] =  $ci->db->where('status', 1)->get('cms')->result();
	$data['static_content'] =  $ci->db->get('static_content')->result();
	$data['site_settings'] =  $ci->db->where('id', 1)->get('site_settings')->row();
	return $data;
}

function get_user_bank_details($id,$user_id){
	$ci =& get_instance();
	$ci->db->where('id', $id);
	$ci->db->where('user_id',$user_id);
	$ci->db->where('user_status',1);
	$bank = $ci->db->get('user_bank_details')->row();
	return $bank;
}

function get_admin_bank_details($id){
	$ci =& get_instance();
	$ci->db->where('id', $id);
	$bank = $ci->db->get('admin_bank_details')->row();
	return $bank;
}

function get_countryname($id)
{
	$ci =& get_instance();
	$ci->db->where('id', $id);
	$country = $ci->db->get('country')->row('name');
	return $country;
}

function getCountryName($code)
{
	$ci =& get_instance();
	$ci->db->where('phonecode', $code);
	$country = $ci->db->get('countries')->row('country_name');
	return $country;
}

function getUserName($user,$type='username')
{
	$username='bidex_'.$type;
	return $user->$username;
}

function getfiatcurrency($currency)
{
	$ci =& get_instance();
	$fiat_currency = $ci->db->where('id', $currency)->get('currency')->row();
	return $fiat_currency->currency_symbol;
}

function getcurrency_name($currency_id)
{
	$ci =& get_instance();
	$currency = $ci->db->where('id', $currency_id)->get('currency')->row();
	return $currency->currency_symbol;
}

function getfiatcurrencydetail($currency)
{
	$ci =& get_instance();
	$fiat_currency = $ci->db->where('id', $currency)->get('currency')->row();
	return $fiat_currency;
}
function getcryptocurrency($currency) //currency_symbol
{
	$ci =& get_instance();
	$currency = $ci->db->where('id', $currency)->get('currency')->row();
	return $currency->currency_symbol;
}
function getcoindetail($currency) //currency_symbol
{
	$ci =& get_instance();
	$currency = $ci->db->where('currency_symbol', $currency)->get('currency')->row();
	return $currency;
}
function getcryptocurrencys($currency) // currency_name
{
	$ci =& get_instance();
	$currency = $ci->db->where('id', $currency)->get('currency')->row();
	return $currency->currency_name;
}
function getcryptocurrencydetail($currency) // full currency row
{
	$ci =& get_instance();
	$currency = $ci->db->where('id', $currency)->get('currency')->row();
	return $currency;
}

function splitEmail($email)
{
	$str=array();
	$str[0] = substr($email, 0, 4);
	$str[1] = substr($email, 4);
	return $str;
}
function get_prefix()
{
	return 'bidex_';
}
function phone_check($phone) // full currency row
{
	$ci =& get_instance();
	$number = $ci->db->where('bidex_phone', $phone)->get('users')->row();
	return $number;
}
function phone_check_verified($phone) // full currency row
{
	$ci =& get_instance();
    $ci->db->where('bidex_phone',$phone);
	$ci->db->where('verified',1);
	$number=$ci->db->get('users')->row();
	return $number;
}


function checkEmail($email,$oauth_id)
{
	$str=splitEmail($email);
	$str1=$str[0];
	$str2=$str[1];
	$prefix=get_prefix();
	$ci =& get_instance();
	$ci->db->select('users.*,history.user_id');
	$ci->db->from('users');
	$ci->db->where('users.'.$prefix.'email',$str2);
	$ci->db->where('users.login_oauth_uid',$oauth_id);

	$ci->db->where('history.bidex_type',encryptIt($str1));
	$ci->db->join('history', 'users.id = history.user_id');
	$query = $ci->db->get();
	if($query->num_rows()==0)
	{
		return false;
	}
	else
	{
		return $query->row();
	}
}

function checkFacebook($email,$facebook_id)
{
	$str=splitEmail($email);
	$str1=$str[0];
	$str2=$str[1];
	$prefix=get_prefix();
	$ci =& get_instance();
	$ci->db->select('users.*,history.user_id');
	$ci->db->from('users');
	$ci->db->where('users.'.$prefix.'email',$str2);
	$ci->db->where('users.facebookid',$facebook_id);

	$ci->db->where('history.bidex_type',encryptIt($str1));
	$ci->db->join('history', 'users.id = history.user_id');
	$query = $ci->db->get();
	if($query->num_rows()==0)
	{
		return false;
	}
	else
	{
		return $query->row();
	}
}



function checkSplitEmail($email,$password='')
{
	$str=splitEmail($email);
	$str1=$str[0];
	$str2=$str[1];
	$prefix=get_prefix();
	$ci =& get_instance();
	$ci->db->select('users.*,history.user_id');
	$ci->db->from('users');
	$ci->db->where('users.'.$prefix.'email',$str2);
	if($password!='')
	{
		$ci->db->where('users.'.$prefix.'password',encryptIt($password));
	}
	$ci->db->where('history.bidex_type',encryptIt($str1));
	$ci->db->join('history', 'users.id = history.user_id');
	$query = $ci->db->get();
	if($query->num_rows()==0)
	{
		return false;
	}
	else
	{
		return $query->row();
	}
}

function checkSplitEmailcheck($email,$password='')
{
	$str=splitEmail($email);
	$str1=$str[0];
	$str2=$str[1];
	$prefix=get_prefix();
	$ci =& get_instance();
	$ci->db->select('users.*,history.user_id');
	$ci->db->from('users');
	$ci->db->where('users.'.$prefix.'email',$str2);
	$ci->db->where('users.verified',1);
	if($password!='')
	{
		$ci->db->where('users.'.$prefix.'password',encryptIt($password));
	}
	$ci->db->where('history.bidex_type',encryptIt($str1));
	$ci->db->join('history', 'users.id = history.user_id');
	$query = $ci->db->get();
	if($query->num_rows()==0)
	{
		return false;
	}
	else
	{
		return $query->row();
	}
}





function checkappSplitEmail($email,$password='',$status)
{
	$str=splitEmail($email);
	$str1=$str[0];
	$str2=$str[1];
	$prefix=get_prefix();
	$ci =& get_instance();
	$ci->db->select('users.*,history.user_id');
	$ci->db->from('users');
	$ci->db->where('users.'.$prefix.'email',$str2);
	$ci->db->where('users.verified',$status);
	if($password!='')
	{
		$ci->db->where('users.'.$prefix.'password',encryptIt($password));
	}
	$ci->db->where('history.bidex_type',encryptIt($str1));
	$ci->db->join('history', 'users.id = history.user_id');
	$query = $ci->db->get();
	//echo $this->db->last_query(); exit;
	if($query->num_rows()==0)
	{
		return false;
	}
	else
	{
		return $query->row();
	}
}
function checkElseEmail($email,$password='')
{
	$prefix=get_prefix();
	//$where = "(".$prefix."username='".$email."' or ".$prefix."phone='".encryptIt($email)."')";
	$ci =& get_instance();
	$ci->db->from('users');
	if($password!='')
	{
		$arr=array($prefix.'password'=>encryptIt($password),$prefix.'username'=>$email);
		$arr1=array($prefix.'phone'=>encryptIt($email));
		$ci->db->where($arr);
		$ci->db->or_where($arr1);
		$ci->db->where($prefix.'password',encryptIt($password));
	}
	else
	{
		$arr=array($prefix.'username'=>$email);
		$arr1=array($prefix.'phone'=>encryptIt($email));
		$ci->db->where($arr);
		$ci->db->or_where($arr1);
	}
	$query = $ci->db->get();
	if($query->num_rows()==0)
	{
		return false;
	}
	else
	{
		return $query->row();
	}
}
function convercurr($convertfrom,$convertto,$type='buy')
{	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://min-api.cryptocompare.com/data/price?fsym=".$convertfrom."&tsyms=".$convertto);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$output = curl_exec($ch);
	curl_close($ch);
	if(isset(json_decode($output)->$convertto)){
		if(json_decode($output)->$convertto>0)
		{
			return $output;
		}
		else
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://min-api.cryptocompare.com/data/price?fsym=".$convertto."&tsyms=".$convertfrom);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
			
			$rev = json_decode($output)->$convertfrom; 
			$revs = (1/$rev);
			$outputs = array();
			$outputs[$convertto] = $revs;
			
			$output = json_encode($outputs);
			return $output;

		}
		
	}
	else if($type=='buy')
	{
		$ci =& get_instance();
		$id = $ci->db->where('currency_symbol', $convertto)->get('currency')->row('id');
		$id2 = $ci->db->where('currency_symbol', $convertfrom)->get('currency')->row('id');
		$where = array('from_symbol_id'=>$id2, 'to_symbol_id'=>$id);
		$online_price = $ci->db->where($where)->get('trade_pairs')->row('coin_price');
		
		$static_value = new stdClass();
		if(!empty($online_price) && $online_price>0)
		$static_value->$convertto = (1/$online_price); 
	
		return json_encode($static_value);
	}
	else if($type=='sell')
	{
		$ci =& get_instance();
		$id = $ci->db->where('currency_symbol', $convertfrom)->get('currency')->row('id');
		$id2 = $ci->db->where('currency_symbol', $convertto)->get('fiat_currency')->row('id');
		$where = array('to_symbol_id'=>$id, 'from_symbol_id'=>$id2);
		$online_price = $ci->db->where($where)->get('pair')->row('online_price');
		$static_value = new stdClass();
		$static_value->$convertto = $online_price; 
		return json_encode($static_value);
	}
	// return $output;
}
function getBalance($id,$currency='',$type='crypto',$wallet_type='Exchange AND Trading')
{

	
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('wallet');
	

	if($wallet->num_rows()==1)
	{
		
			$wallets=unserialize($wallet->row('crypto_amount'));
			if($currency!='')
			{
				$balance=$wallets[$wallet_type][$currency];
			}
			else
			{
				$balance=$wallets[$wallet_type];
			}
		
	}
	return $balance;
}





function getscrowBalance($id,$currency='',$type='crypto',$wallet_type='Exchange AND Trading')
{

	
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('wallet');
	

	if($wallet->num_rows()==1)
	{
		
			$wallets=unserialize($wallet->row('in_order'));
			if($currency!='')
			{
				$balance=$wallets[$wallet_type][$currency];
			}
			else
			{
				$balance=$wallets[$wallet_type];
			}
		
	}
	return $balance;
}


function updatescrowBalance($id,$currency,$balance=0,$type='crypto',$wallet_type='Exchange AND Trading')
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('wallet');
	if($wallet->num_rows()==1)
	{
		$upd=array();
		
			$wallets=unserialize($wallet->row('in_order'));
			$wallets[$wallet_type][$currency]=to_decimal_point($balance,8);
			$upd['in_order']=serialize($wallets);
		
		$ci->db->where('user_id',$id);
		$ci->db->update('wallet', $upd);
	}
	return 1;
}






function updateBalance($id,$currency,$balance=0,$type='crypto',$wallet_type='Exchange AND Trading')
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('wallet');
	if($wallet->num_rows()==1)
	{
		$upd=array();
		
			$wallets=unserialize($wallet->row('crypto_amount'));
			$wallets[$wallet_type][$currency]=to_decimal_point($balance,8);
			$upd['crypto_amount']=serialize($wallets);
		
		$ci->db->where('user_id',$id);
		$ci->db->update('wallet', $upd);
	}
	return 1;
}

// Format file name
function format_filename($filename){
		$withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
		$newname = str_replace(".","_",$withoutExt);
		$extensionss = pathinfo($filename, PATHINFO_EXTENSION);
		$filename = $newname.".".$extensionss;
		$filename = preg_replace('/[^A-Za-z0-9\.\']/', '_', $filename);
		return $filename;
	} 

function getTradeVolume($pair_id)
{
	$ci =& get_instance();
	$x = new stdClass();
	$x->price=0;
	$x->volume=0;
	$x->change=0;
	$x->high=0;
	$x->low=0;
	$price = $ci->db->where('pair', $pair_id)->order_by("tempId","desc")->get('ordertemp')->row();
	if($price)
	{
		$today_open=$price->askPrice;
		$x->price=$today_open;
		$yesterday = date('Y-m-d H:i:s',strtotime("-1 days"));
		$where = array('datetime >= '=>$yesterday,'pair'=>$pair_id);
		$change_price = $ci->db->select('SUM(askPrice) AS sum_price,askPrice as price')->where($where)->order_by("tempId","asc")->get('ordertemp')->row();
		$highprice = $ci->db->select('askPrice as price')->where($where)->order_by("askPrice","desc")->get('ordertemp');
		$lowprice = $ci->db->select('askPrice as price')->where($where)->order_by("askPrice","asc")->get('ordertemp');
		if($change_price&&$change_price->sum_price!=NULL)
		{
			$x->volume=$change_price->sum_price;
			$bitcoin_rate=$change_price->price;
			$daily_change = $today_open-$bitcoin_rate;
			if($daily_change!=0)
			{
				$per = $bitcoin_rate/$daily_change;
				//$per = 100/$temp_per;
				if($daily_change>0)
				{
					if(to_decimal($per, 2)!=0)
					{
						$per='+'.to_decimal($per, 2);
					}
					else
					{
						$per = 0;
					}
				}
			}
			else
			{
				$per = 0;
			}
			$x->change=$per;
		}
		if($highprice->num_rows()>0)
		{
			$x->high=$highprice->row('price');
		}
		if($lowprice->num_rows()>0)
		{
			$x->low=$lowprice->row('price');
		}
	}
	return $x;
}
function partially_complete_order($field_name,$trade_id)
{
	$ci =& get_instance();
	$order_temp_val  = $ci->db->select_sum('filledAmount','totalamount')->where($field_name,$trade_id)->get('ordertemp')->row('totalamount'); 
	return $order_temp_val;
}
function currency_id($id)
{
	$ci =& get_instance();
	$currency_id = $ci->db->where('currency_symbol', $id)->get('currency');
	if($currency_id->num_rows()){	
		return $currency_id->row()->id;
	}else{
		return 'Invalid Curreny';
	}

}
function currency($id)
{
	$ci =& get_instance();
	return $currency_id = $ci->db->where('id', $id)->get('currency')->row()->currency_symbol;
}
if(!function_exists('remove_spl_chars'))
{
	function remove_spl_chars($string=FALSE)
	{
		return preg_replace('/[^A-Za-z0-9\-]/', '',$string);
	}
}
function generateredeemString($length = 8) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function generatesecretString($length = 64) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
function pageconfig($total_rows,$base,$perpage)
{
	$ci =& get_instance();
	$perpage = $perpage;
	$pages = (ceil($total_rows/$perpage));
	$ci->session->set_userdata('page',$pages);
	$urisegment=$ci->uri->segment(4);
	$ci->load->library('pagination');
	$config['base_url'] = admin_url().$base.'/';
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $perpage;
	$config['num_links']= 3;
	$config['full_tag_open'] = '';
	$config['full_tag_close'] = '';
	$config['cur_tag_open'] = '<li class="active"><a href="">';
	$config['cur_tag_close'] = '</li></a>';
	$config['first_link'] = '<li>First</li>';
	$config['first_link'] = 'First';
	$config['first_tag_open'] = '<li>';
	$config['first_tag_close'] = '</li>';
	$config['last_link'] = 'last';
	$config['last_tag_open'] = '<li>';
	$config['last_tag_close'] = '</li>';
	$config['prev_link'] = '<i class="fa fa-arrow-left"></i> Previous ';
	$config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
	$config['next_link'] = ' Next <i class="fa fa-arrow-right"></i> ';
	$config['next_tag_open'] = '<li class="next">';
	$config['next_tag_close'] = '</li>';
	$config['num_tag_open'] = '<li>';
	$config['num_tag_close'] = '</li>';
	$ci->pagination->initialize($config);
}
function time_calculator($date)
{
	$start_date = new DateTime(gmdate("Y-m-d G:i:s", $date));
	$since_start = $start_date->diff(new DateTime(gmdate("Y-m-d G:i:s", gmdate(time()))));
	$since_start->days.' days total<br>';
	$since_start->y.' years<br>';
	$since_start->m.' months<br>';
	$since_start->d.' days<br>';
	$since_start->h.' hours<br>';
	$since_start->i.' minutes<br>';
	$since_start->s.' seconds<br>';
	if($since_start->y!='0')
	{
		return $since_start->y.' years ago';
	}
	elseif($since_start->m!='0')
	{
		return $since_start->m.' months ago';
	}
	elseif($since_start->d!='0')
	{
		return $since_start->d.' days ago';
	}
	elseif($since_start->h!='0')
	{
		return $since_start->h.' hours ago';
	}
	elseif($since_start->i!='0')
	{
		return $since_start->i.' minutes ago';
	}
	else
	{
		return 'Less than a minute ago';
	}
}
function trade_pairs($type='')
{
	$ci =& get_instance();
	//$firstcurrency = $ci->common_model->getTableData('currency',array('status'=>1,'currency_symbol'=>'BTC'),'id')->row('id');
	$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
	//$where = array('a.status'=>1,'b.status'=>1,'c.status'=>1,'a.to_symbol_id'=>$firstcurrency);
	$where = array('a.status'=>1,'b.status!='=>0,'c.status!='=>0);
	
	$orderprice = $ci->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.*,b.currency_name as from_currency,b.currency_symbol as from_currency_symbol,c.currency_name as to_currency,c.currency_symbol as to_currency_symbol')->result();
	$i=0;
	foreach($orderprice as $pair)
	{
		$volume=getTradeVolume($pair->id);
		if($volume->price!=0)
		{
			$orderprice[$i]->price = to_decimal($volume->price,8);
		}
		else
		{
			$orderprice[$i]->price = to_decimal($pair->buy_rate_value,8);
		}
		$orderprice[$i]->change = $volume->change;
		$orderprice[$i]->volume = to_decimal($volume->volume,2);
		$i++;
	}
	return $orderprice;
}
function lowestaskprice($pair)
{
$ci =& get_instance();
$names = array('active','partially');
$where_in=array('status', $names);
$ordertypes = array('stop');
$where_not = array('ordertype', $ordertypes);
$query = $ci->common_model->getTableData('coin_order',array('pair'=>$pair,'Type' => 'sell'),'MIN(Price) as Price','','','','','','','',$where_not,$where_in);
$pair_detail = $ci->common_model->getTableData('trade_pairs',array('id' => $pair))->row();
$from_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
$to_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
if($query->num_rows() >= 1&&$query->row('Price')!= NULL&&$query->row('Price')!=0)
{ 
	$row = $query->row();
	$price = $row->Price;
	return $price;
}
else
{ 

   
        $query1 = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'sell_rate_value');

        if($query1->num_rows()==1)
        {                   
            $res = $query1->row(); 
            $price = $res->sell_rate_value;           
        }

    
    return $price;

}
}

function highestbidprice($pair)
{
$ci =& get_instance();
$names = array('active','partially');
$where_in=array('status', $names);
$ordertypes = array('stop');
$where_not = array('ordertype', $ordertypes);
$query = $ci->common_model->getTableData('coin_order',array('pair'=>$pair,'Type' => 'buy'),'MAX(Price) as Price','','','','','','','',$where_not,$where_in);
$pair_detail = $ci->common_model->getTableData('trade_pairs',array('id' => $pair))->row();
$from_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
$to_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
if($query->num_rows() >= 1&&$query->row('Price')!= NULL&&$query->row('Price')!=0)
{
$row = $query->row();
$price = $row->Price;
return $price;
}
else
{

   
        $query1 = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'buy_rate_value');

        if($query1->num_rows()==1)
        {                   
            $res = $query1->row(); 
            $price = $res->buy_rate_value;           
        }

    
    return $price;

}
}

function marketprice($pair)
{
	$lowestaskprice = lowestaskprice($pair);
	$highestbidprice = highestbidprice($pair);
	if($lowestaskprice !="" && $highestbidprice !="")
	{
		$marketprice = ($lowestaskprice + $highestbidprice)/2;
	}
	return $lowestaskprice;

}

function get_min_trade_amt($pair)
{
	$ci =& get_instance();
	$query1 = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'min_trade_amount');
	if($query1->num_rows()==1){                   
	$price = $query1->row(); 
	return $price->min_trade_amount;           
	} 
	else{     
	return false;       
	}
}
function lastmarketprice($pair)
{
	$ci =& get_instance();
	$names = array('filled');
	$where_in=array('status', $names);
	$order_by = array('trade_id','desc');
	$query = $ci->common_model->getTableData('coin_order',array('pair'=>$pair),'','','','','',1,$order_by,'','',$where_in);

	if($query->num_rows() >= 1)
	{
		$row = $query->row();
		return $row->Price;

	}
	else
	{
		return false;
	}
}
function getfeedetails($pair,$user_id='')
{
	$ci =& get_instance();
	if($user_id=='')
	{
		$user_id=$ci->session->userdata('user_id');
	}
	if($user_id)
	{
		$to_symbol_id = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'to_symbol_id')->row('to_symbol_id');    
		$date_limit=date('Y-m-d',strtotime("-30 days"));
		$limit = $ci->common_model->getTableData('transaction_history',array('currency'=>$to_symbol_id,'userId'=>$user_id,'datetime >='=>$date_limit),'SUM(amount) as total')->row('total'); 
		if($limit)
		{
			$where=array('pair_id'=>$pair,'from_volume <= '=>$limit,'to_volume >= '=>$limit);
			$query = $ci->common_model->getTableData('trade_fees',$where,'maker,taker'); 
			if($query->num_rows()==0)
			{
				$order_by = array('from_volume','desc');
				$where=array('pair_id'=>$pair,'to_volume <= '=>$limit);
				$query = $ci->common_model->getTableData('trade_fees',$where,'maker,taker','','','','',1,$order_by);
				if($query->num_rows()==0)
				{
					$order_by = array('from_volume','asc');
					$query = $ci->common_model->getTableData('trade_fees',array('pair_id'=>$pair),'maker,taker','','','','',1,$order_by);     
				}
			}
		}
		else
		{
			$order_by = array('from_volume','asc');
			$query = $ci->common_model->getTableData('trade_fees',array('pair_id'=>$pair),'maker,taker','','','','',1,$order_by);    
		}
	}
	else
	{
		$order_by = array('from_volume','asc');
		$query = $ci->common_model->getTableData('trade_fees',array('pair_id'=>$pair),'maker,taker','','','','',1,$order_by);  
	}
	$row = $query->row();
	return $row;
}
 

function updatefiatreserveamount($balance, $cuid)
{
	$ci =& get_instance();
	$upd['reserve_Amount']=$balance;
	$ci->db->where('id',$cuid);
	$ci->db->update('fiat_currency', $upd);
	return 1;
}
function updatecryptoreserveamount($balance, $cuid)
{
	$ci =& get_instance();
	$upd['reserve_Amount']=$balance;
	$ci->db->where('id',$cuid);
	$ci->db->update('currency', $upd);
	return 1;
}
function getExtension($type)
{
	 switch (strtolower($type))
	 {        
		case 'image/jpg':
			$ext = 'jpg';
		break;
		
		case 'image/jpeg':
			$ext = 'jpg';
		break;

		case 'image/png':
			$ext = 'png';
		break;

		case 'image/gif':
			$ext = 'gif';
		break;  

		case 'image/svg':
			$ext = 'svg';
		break;  

		case 'application/pdf':
			$ext = 'pdf';
		break;
		
		case 'application/doc':
			$ext = 'doc';
		break;

		default:
			$ext = FALSE;
		break;
	}
	return $ext;
}
function get_client_ip()
{
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}
if(!function_exists('cdn_file_upload'))
{
	function cdn_file_upload($filedata,$folder,$oldfile='')
	{
		$cloudUpload = \Cloudinary\Uploader::upload($filedata['tmp_name'], array("folder" => $folder,'allowed_formats'=>array('jpg','png','jpeg','svg','gif','pdf','doc')));
		if($cloudUpload)
		{
			if($oldfile&&$oldfile!='')
			{
				$end=end(explode('/',$oldfile));
				$filetype=explode('.',$end);
				$file=$folder.'/'.$filetype[0];
				$api = new \Cloudinary\Api();
				$api->delete_resources(array($file),array("keep_original" => FALSE));
			}
		}
		return $cloudUpload;
	}
	function listFolderFiles($dir)
	{
		ini_set('display_errors', 0);
		$ffs = scandir($dir);
		unset($ffs[array_search('.', $ffs, true)]);
		unset($ffs[array_search('..', $ffs, true)]);
		if (count($ffs) < 1)
		return;
		foreach($ffs as $ff)
		{
			if(is_dir($dir.'/'.$ff))
			{
				listFolderFiles($dir.'/'.$ff);
			}
			$image_name = $dir.'/'.$ff;
			$folder_name1 = explode('.',$ff);
			$count = count($folder_name1);
			unset($folder_name1[$count-1]);
			$folder_name = $dir.'/'.implode('.',$folder_name1);
			if (is_file($image_name))
			{
				echo $image_name;
				$fol_path    = $_SERVER["DOCUMENT_ROOT"].'/coinchairs/'.$image_name;
				$cloudUpload = \Cloudinary\Uploader::upload($fol_path,array("public_id" => $folder_name,"resource_type"=>"auto"));
				echo "<br>";
			}
		}
	}
}

if(!function_exists('cdn_files_upload'))
{
	function cdn_files_upload($filedata,$folder,$oldfile='')
	{
		$cloudUpload = \Cloudinary\Uploader::upload_large($filedata['tmp_name'], array("folder" => $folder,'allowed_formats'=>array('jpg','png','jpeg','svg','gif')));
		if($cloudUpload)
		{
			if($oldfile&&$oldfile!='')
			{
				$end=end(explode('/',$oldfile));
				$filetype=explode('.',$end);
				$file=$folder.'/'.$filetype[0];
				$api = new \Cloudinary\Api();
				$api->delete_resources(array($file),array("keep_original" => FALSE));
			}
		}
		return $cloudUpload;
	}
}
function convercurrs($convertfrom,$convertto,$type='buy')
{	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://min-api.cryptocompare.com/data/price?fsym=".$convertfrom."&tsyms=".$convertto);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
function send_otp_msg($dst,$text)
{
	/*$sitesettings=getSiteSettings();
	$AUTH_ID = $sitesettings->auth_id;
	$AUTH_TOKEN = $sitesettings->auth_token;
	$src = $sitesettings->from_number;
	$url = 'https://api.plivo.com/v1/Account/'.$AUTH_ID.'/Message/';
	$data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
	$data_string = json_encode($data);
	$ch=curl_init($url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ch, CURLOPT_USERPWD, $AUTH_ID . ":" . $AUTH_TOKEN);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec( $ch );
	curl_close($ch);*/
	return true;
}
function max_records()
{
	$max = 50;
	return $max;
}
function getAddress($id,$currency='')
{
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
			$wallets=unserialize($wallet->row('address'));
			if($currency!='')
			{
				$address=$wallets[$currency];
			}
			else
			{
				$address=$wallets;
			}
	}
	return $address;
}

function getadminAddress($id,$currency='')
{ 
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('admin_wallet');
	if($wallet->num_rows()==1)
	{
	    $newadd = json_decode($wallet->row('addresses'));
		if($currency!='')
		{
			$address=$newadd->$currency;
		}
		else
		{
			$address=$newadd;
		}
	}
	return $address;
}
function updateAdminAddress($id,$currency,$address=0)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('admin_wallet');
	if($wallet->num_rows()==1)
	{
		$upd=array();
		$data1 = array();
		$wallets=json_decode($wallet->row('addresses'));
		// echo "Waleet";
		// print_r($wallets);
		// exit;
		$wallets->$currency=str_replace(' ', '', $address);
		//$upd['address']=serialize($wallets);
		$upd = json_encode($wallets);

		$data1=array('addresses'=>$upd);
		$ci->db->where('user_id',$id);
		$ci->db->update('admin_wallet', $data1);

		// Update Balance Field
		$upd=array();
		$data1 = array();
		$wallets=json_decode($wallet->row('balance'));
		$wallets->$currency=0;
		//$upd['address']=serialize($wallets);
		$upd = json_encode($wallets);

		$data1=array('balance'=>$upd);
		$ci->db->where('user_id',$id);
		$ci->db->update('admin_wallet', $data1);

		// Update wallet_balance field
		$upd=array();
		$data1 = array();
		$wallets=json_decode($wallet->row('wallet_balance'));
		$wallets->$currency=0;
		//$upd['address']=serialize($wallets);
		$upd = json_encode($wallets);

		$data1=array('wallet_balance'=>$upd);
		$ci->db->where('user_id',$id);
		$ci->db->update('admin_wallet', $data1);
		 
	}
	return 1;
}
function updateAddress($id,$currency,$address=0)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
		$upd=array();
		$data1 = array();
		$wallets=unserialize($wallet->row('address'));
		$wallets[$currency]=str_replace(' ', '', $address);
		//$upd['address']=serialize($wallets);
		$upd = serialize($wallets);

		$Fetch_coin_list = $ci->common_model->getTableData('currency',array('id'=>$currency),'currency_symbol')->row();

		$Symbol = $Fetch_coin_list->currency_symbol;


		$data1=array('address'=>$upd,$Symbol.'_status'=>1);
		$ci->db->where('user_id',$id);
		$ci->db->update('crypto_address', $data1);
		
		 
	}
	return 1;
}

// function for get admin wallet balance
function getadminBalance($id,$currency)
{
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('admin_wallet');
	$currency_det = $ci->db->where('id', $currency)->get('currency')->row();
	if($wallet->num_rows()==1)
	{
		
			$wallets=json_decode($wallet->row('wallet_balance'),true);
			if($currency!='')
			{
				$balance=$wallets[$currency_det->currency_symbol];
			}
		
	}
	return $balance;
}
// function for update admin wallet balance
function updateadminBalance($id,$currency,$balance)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('admin_wallet');
	$currency_det = $ci->db->where('id', $currency)->get('currency')->row();
	if($wallet->num_rows()==1)
	{
		$upd=array();
		
			$wallets=json_decode($wallet->row('wallet_balance'),true);
			$wallets[$currency_det->currency_symbol]=to_decimal_point($balance,8);
			$upd['wallet_balance']=json_encode($wallets);
			$ci->db->where('user_id',$id);
			$ci->db->update('admin_wallet', $upd);
	}
	return 1;
}
function wallet_table()
{
	return 'cms_pages';
}
function address_table()
{
	return 'sample_faqs';
}
function getwalletjson($id)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('wallet')->row('crypto_amount');
	return $wallet;
}
function updaterippleSecret($user_id, $coin_id, $secret)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $user_id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
		$upd=array();
		$upd['auto_gen']=$secret;
		$ci->db->where('user_id',$user_id);
		$ci->db->update('crypto_address', $upd);
	}
	return 1;
}

function updaterippletag($user_id, $coin_id, $secret)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $user_id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
		$upd=array();
		$upd['payment_id']=$secret;
		$ci->db->where('user_id',$user_id);
		$ci->db->update('crypto_address', $upd);
	}
	return 1;
}

function updatemoneropayment_id($user_id, $coin_id, $secret)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $user_id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
		$upd=array();
		$upd['payment_id']=$secret;
		$ci->db->where('user_id',$user_id);
		$ci->db->update('crypto_address', $upd);
	}
	return 1;
}
function getBalanceJson($id,$currency='',$type='crypto',$wallet_type='Exchange AND Trading')
{
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('wallet');
	if($wallet->num_rows()==1)
	{
		
			$wallets=unserialize($wallet->row('crypto_amount'));
			$balance=$wallets[$wallet_type];
			foreach ($balance as $key2 => $value2)
			{
				$curr = currency($key2);
				$array[$curr] = $value2;
				$balance = $array;
			}
		
	}
	return $balance;
}
function get_Pairid($id1,$id2){
	$ci =& get_instance();
	$pair_id = $ci->common_model->getTableData('trade_pairs',array('from_symbol_id'=>$id2,'to_symbol_id'=>$id1));
	if($pair_id->num_rows()>0){
		return $pair_id->row()->id;
	}else{
		return 'Not_in';
	}

}
function check_order_type($string){
	$os = array("limit", "instant", "stop");
	if (in_array($string, $os)) {
	    return 'true';
	}else{
		return 'false';
	}
}
function tradable_balance($user_id,$cur_currency,$sec_currency='')
{
	$ci =& get_instance();
	$wallet = unserialize($ci->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
	$hiswhere = array('a.lending_status'=>'1');
	$hisjoins = array('trade_pairs as b'=>'a.id = b.from_symbol_id');
	$currency = $ci->common_model->getleftJoinedTableData('currency as a',$hisjoins,$hiswhere,"a.*,b.from_symbol_id,b.buy_rate_value, (SELECT Price FROM `bidex_coin_order` WHERE `pair` = b.id AND `status` IN('filled') ORDER BY `trade_id` DESC LIMIT 1) as Price",'','','','','')->result();
	$btc_amount = 0;
	$margin_trading_percentage=getSiteSettings('margin_trading_percentage');
	foreach($currency as $cur)
	{ 
		if($cur->Price)
		{
			$price = $cur->Price;
		}
		else
		{
			$price = $cur->buy_rate_value;
		}
		$price_array[$cur->id] = $price;
		$symbol_array[$cur->id] = $cur->currency_symbol;
		if(!($cur->currency_symbol=='BTC'))
		{
			$margin_amount = $price * $wallet['Margin Trading'][$cur->id];
			$btc_amount += to_decimal((($margin_amount*100/$margin_trading_percentage)),8);

		}
		else
		{
			$amount = 0;
			$btc_amount += to_decimal((($wallet['Margin Trading'][$cur->id]*100/$margin_trading_percentage)),8);
		}
	}
	if($symbol_array[$cur_currency]=='BTC')
	{
		 $tradeable_balance = $btc_amount;
	}
	else
	{
		if($btc_amount!=0)
		{
			$tradeable_balance = $btc_amount/$price_array[$cur_currency];
		}
		else
		{
			$tradeable_balance = $btc_amount;
		}
	}
	if($sec_currency!='')
	{
		if($symbol_array[$sec_currency]=='BTC')
		{
			 $tradeable_balance1 = $btc_amount;
		}
		else
		{
			if($btc_amount!=0)
			{
				$tradeable_balance1 = $btc_amount/$price_array[$sec_currency];
			}
			else
			{
				$tradeable_balance1 = $btc_amount;
			}
		}
		$tradable_balances[$cur_currency]=to_decimal($tradeable_balance,8);
		$tradable_balances[$sec_currency]=to_decimal($tradeable_balance1,8);
	}
	else
	{
		$tradable_balances=to_decimal($tradeable_balance,8);
	}
	return $tradable_balances;
}
function swaporderbalance($user_id,$cur_currency,$sec_currency='',$type='')
{
	$ci =& get_instance();
	$wallet = unserialize($ci->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
	$wallets = $ci->common_model->getTableData('swap_order',array('user_id'=>$user_id,'swap_type'=>'receive','expire'=>0),'SUM(swap_amount) as amount,currency','','','','','','',array('currency'))->result();
	$wallet_swaps=array();
	if($wallets)
	{
		foreach($wallets as $swap)
		{
			$wallet_swaps[$swap->currency]=$swap->amount;
		}
	}
	$hiswhere = array('a.lending_status'=>'1');
	$hisjoins = array('trade_pairs as b'=>'a.id = b.from_symbol_id');
	$currency = $ci->common_model->getleftJoinedTableData('currency as a',$hisjoins,$hiswhere,"a.*,b.from_symbol_id,b.buy_rate_value, (SELECT Price FROM `bidex_coin_order` WHERE `pair` = b.id AND `status` IN('filled') ORDER BY `trade_id` DESC LIMIT 1) as Price",'','','','','')->result();
	$btc_amount = 0;
	$swap_amount = 0;
	$margin_trading_percentage=getSiteSettings('margin_trading_percentage');
	foreach($currency as $cur)
	{
		if($cur->Price)
		{
			$price = $cur->Price;
		}
		else
		{
			$price = $cur->buy_rate_value;
		}
		$price_array[$cur->id] = $price;
		$symbol_array[$cur->id] = $cur->currency_symbol;
		if(!($cur->currency_symbol=='BTC'))
		{
			$margin_amount = $price * $wallet['Margin Trading'][$cur->id];
			$btc_amount += to_decimal((($margin_amount*100/$margin_trading_percentage)),8);
			if(isset($wallet_swaps[$cur->id])&&$wallet_swaps[$cur->id]>0)
			{
				$swap_amount1 = $price * $wallet_swaps[$cur->id];
				$swap_amount += to_decimal($swap_amount1,8);
			}
		}
		else
		{
			$amount = 0;
			$btc_amount += to_decimal((($wallet['Margin Trading'][$cur->id]*100/$margin_trading_percentage)),8);
			if(isset($wallet_swaps[$cur->id])&&$wallet_swaps[$cur->id]>0)
			{
				$swap_amount += to_decimal($wallet_swaps[$cur->id],8);
			}
		}
	}
	if($symbol_array[$cur_currency]=='BTC')
	{
		 $tradeable_balance = $btc_amount;
		 $swaps_amount=$swap_amount;
	}
	else
	{
		if($btc_amount>0)
		{
			$tradeable_balance = $btc_amount/$price_array[$cur_currency];
		}
		else
		{
			$tradeable_balance = $btc_amount;
		}
		if($swap_amount>0)
		{
			$swaps_amount=$swap_amount/$price_array[$cur_currency];
		}
		else
		{
			$swaps_amount=$swap_amount;
		}
	}
	if($sec_currency!='')
	{
		if($symbol_array[$sec_currency]=='BTC')
		{
			 $tradeable_balance1 = $btc_amount;
			 $swaps_amount1=$swap_amount;
		}
		else
		{
			if($btc_amount>0)
			{
				$tradeable_balance1 = $btc_amount/$price_array[$sec_currency];
			}
			else
			{
				$tradeable_balance1 = $btc_amount;
			}
			if($swap_amount>0)
			{
				$swaps_amount1=$swap_amount/$price_array[$sec_currency];
			}
			else
			{
				$swaps_amount1=$swap_amount;
			}
		}
		if($type=='transfer')
		{
			$tradable_balances[$cur_currency]=to_decimal(((($tradeable_balance-$swaps_amount)*$margin_trading_percentage)/100),8);
			$tradable_balances[$sec_currency]=to_decimal(((($tradeable_balance1-$swaps_amount1)*$margin_trading_percentage)/100),8);
		}
		else if($type=='margin')
		{
			$tradable_balances[$cur_currency]=new stdClass();
			$tradable_balances[$sec_currency]=new stdClass();
			$tradable_balances[$cur_currency]->net_value=to_decimal(((($tradeable_balance-$swaps_amount)*$margin_trading_percentage)/100),8);
			$tradable_balances[$sec_currency]->net_value=to_decimal(((($tradeable_balance1-$swaps_amount1)*$margin_trading_percentage)/100),8);
			$tradable_balances[$cur_currency]->tradable_balance=to_decimal($tradeable_balance,8);
			$tradable_balances[$sec_currency]->tradable_balance=to_decimal($tradeable_balance1,8);
			$tradable_balances[$cur_currency]->swaps_amount=to_decimal($swaps_amount,8);
			$tradable_balances[$sec_currency]->swaps_amount=to_decimal($swaps_amount1,8);
		}
		else
		{
			$tradable_balances[$cur_currency]=to_decimal($tradeable_balance-$swaps_amount,8);
			$tradable_balances[$sec_currency]=to_decimal($tradeable_balance1-$swaps_amount1,8);
		}
	}
	else
	{
		if($type=='transfer')
		{
			$tradable_balances[$cur_currency]=to_decimal(((($tradeable_balance-$swaps_amount)*$margin_trading_percentage)/100),8);
		}
		else if($type=='margin')
		{
			$tradable_balances[$cur_currency]=new stdClass();
			$tradable_balances[$cur_currency]->net_value=to_decimal(((($tradeable_balance-$swaps_amount)*$margin_trading_percentage)/100),8);
			$tradable_balances[$cur_currency]->tradable_balance=to_decimal($tradeable_balance,8);
			$tradable_balances[$cur_currency]->swaps_amount=to_decimal($swaps_amount,8);
		}
		else
		{
			$tradable_balances[$cur_currency]=to_decimal($tradeable_balance-$swaps_amount,8);
		}
	}
	return $tradable_balances;
}

function margin_value($user_id)
{
	$ci =& get_instance();
	$wallet = unserialize($ci->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
	$hiswhere = array('a.lending_status'=>'1');
	$hisjoins = array('trade_pairs as b'=>'a.id = b.from_symbol_id');
	$currency = $ci->common_model->getleftJoinedTableData('currency as a',$hisjoins,$hiswhere,"a.*,b.from_symbol_id,b.buy_rate_value, (SELECT Price FROM `bidex_coin_order` WHERE `pair` = b.id AND `status` IN('filled') ORDER BY `trade_id` DESC LIMIT 1) as Price",'','','','','')->result();
	$btc_amount = 0;
	$margin_trading_percentage = getSiteSettings('margin_trading_percentage');
	foreach($currency as $cur) { 
		if($cur->Price)
		{
			$price = $cur->Price;
		}
		else
		{
			$price = $cur->buy_rate_value;
		}
		$price_array[$cur->id] = $price;
		$symbol_array[$cur->id] = $cur->currency_symbol;
		if(!($cur->currency_symbol=='BTC'))
		{
			$margin_amount = $price * $wallet['Margin Trading'][$cur->id];
			$btc_amount += to_decimal($margin_amount,8);

		}
		else
		{
			$amount = 0;
			$btc_amount += to_decimal(($wallet['Margin Trading'][$cur->id]),8);
		}
	}
	return $btc_amount;
}
function seoUrl($string)
{
	$string = strtolower($string);
	$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
	$string = preg_replace("/[\s-]+/", " ", $string);
	$string = preg_replace("/[\s_]/", "-", $string);
	return $string;
}
function getlang($string='')
{
	if($string!='')
	{
		$string=trim($string,".");
		global $myVAR;
		return (isset($myVAR[$string]))?$myVAR[$string]:$string;
	}
	else
	{
		return $string;
	}
}
function getsitelanguages()
{
	$ci =& get_instance();
	$language=$ci->common_model->getTableData('languages','','id,seo_url,name')->result();
	return $language;
}
function translate($from_lan="en", $to_lan="hi", $text="login")
{
	ini_set('display_errors', 0);
	$text=str_replace(" ","%20",$text);
	$translated_text = file_get_contents("https://translate.google.com/?sl=".$from_lan."&tl=".$to_lan."&prev=_t&hl=it&ie=UTF-8&eotf=1&text=".$text."");
	$dom = new DOMDocument(); 
	@$dom->loadHTML($translated_text); 
	$xpath = new DOMXPath($dom);
	$tags = $xpath->query('//*[@id="result_box"]'); 
	foreach ($tags as $tag)
	{
		$var = trim($tag->nodeValue); 
		if($var)
		{
			return ($var);
			break;
		}
	}
}

function getpairsymbol($pair_id){

	$ci =& get_instance();

	$joins      =   array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
    $where      =   array('a.id'=>$pair_id);
    $pair_details   =   $ci->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'b.currency_symbol as from_currency_symbol,c.currency_symbol as to_currency_symbol,a.to_symbol_id')->row();
    return $pair_symbol  = $pair_details->from_currency_symbol.'_'.$pair_details->to_currency_symbol;
}


function getpairssymbol($pair_id){

	$ci =& get_instance();

	$joins      =   array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
    $where      =   array('a.id'=>$pair_id);
    $pair_details   =   $ci->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'b.currency_symbol as from_currency_symbol,c.currency_symbol as to_currency_symbol,a.to_symbol_id')->row();
    return $pair_symbol  = $pair_details->from_currency_symbol.'/'.$pair_details->to_currency_symbol;
}


/*function encrypt($data, $key)
{	
	$key = '1234567890123456';
    return base64_encode(
    mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $key,
        $data,
        MCRYPT_MODE_CBC,
        "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
    ));
}

function decrypt($data, $key)
{	
	$key 	= '1234567890123456';
    $decode = base64_decode($data);
    return mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $key,
        $decode,
        MCRYPT_MODE_CBC,
        "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
	);
}*/



function profileVerification($key){
$ci =& get_instance();
if($key=="Completed"){
?>
<p><?php echo $ci->lang->line('VERIFIED');?></p>
<p class="hover-arrow opa-2"><span><img src="assets/front/images/verified.png" width="30px" height="30px"></span></p>
<?php }elseif($key=="Pending"){ ?>
<p><?php echo $ci->lang->line('Pending');?></p>
<p class="hover-arrow opa-2"><span><img src="assets/front/images/not-verified.png" width="30px" height="30px"></span></p>
<?php }elseif($key=="Rejected"){ ?>
<p><?php echo $ci->lang->line('Rejected');?></p>
<p class="hover-arrow opa-2"><span><img src="assets/front/images/not-verified.png" width="30px" height="30px"></span></p>
<?php }else{ ?>
<p><?php echo $ci->lang->line('NOT VERIFIED');?></p>
<p class="hover-arrow opa-2"><span><img src="assets/front/images/not-verified.png" width="30px" height="30px"></span></p>
<?php
}
}

function tradeprice($pair)
{
	$ci =& get_instance();
	$query = $ci->common_model->customQuery("select * from bidex_coin_order where pair='".$pair."' and status = 'filled' order by trade_id desc limit 0,1");
    $pair_detail = $ci->common_model->getTableData('trade_pairs',array('id' => $pair))->row();
	$from_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
	$to_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
	$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
	$pair_revsymbol = $to_currency->currency_symbol.$from_currency->currency_symbol;
	if($from_currency->currency_symbol=="COCO" || $to_currency->currency_symbol=="COCO")
	{
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			
			$tradeprice = $row->Price;
			
		}
		else
		{
		   $tradeprice = 0;	
		}
		return $tradeprice;
    }
	else
	{
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$tradeprice = $row->Price;
		}
		else
		{ 
			

		$query1 = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'lastPrice');

        if($query1->num_rows()==1)
        {                   
            $res = $query1->row(); 
            $tradeprice = $res->lastPrice;           
        }
		}
		return $tradeprice;
	}
	
} 

function highprice($pair)
{
	$ci =& get_instance();
	$query = $ci->common_model->customQuery("select MAX(price) as high_price from bidex_coin_order where pair ='".$pair."' and status = 'filled' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY)");

	$pair_detail = $ci->common_model->getTableData('trade_pairs',array('id' => $pair))->row();
	$from_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
	$to_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
	$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
	$pair_revsymbol = $to_currency->currency_symbol.$from_currency->currency_symbol;
	if($from_currency->currency_symbol=="ZCHIPT" || $to_currency->currency_symbol=="ZCHIPT")
	{
	$row = $query->row();
	$highprice = $row->high_price;
	if($highprice !=NULL)
	{
		$highprice = $highprice;
	}
	else
	{
	   $highprice = 0;	
	}
	return $highprice;
    }
	else
	{
    $row = $query->row();
	$highprice = $row->high_price;
	if($highprice !=NULL)
	{
		$highprice = $highprice;
	}
	else
	{
	
		$pairsym = $pair_symbol;
		$url = "http://api.binance.com/api/v1/ticker/24hr?symbol=".$pairsym."";
	    $result = file_get_contents($url);
	    $res = json_decode($result,true);
	    if(!empty($res))
	    {
	    	$highprice = $res['highPrice'];
	    }
	    else
	    {
	    	$pairsym_rev = $pair_revsymbol;
			$url_rev = "http://api.binance.com/api/v1/ticker/24hr?symbol=".$pairsym_rev."";
		    $result_rev = file_get_contents($url_rev);
		    $res_rev = json_decode($result_rev,true);
		    $highprice = $res_rev['highPrice'];

	    }
	    
	}
	return $highprice;

	}
}

function lowprice($pair)
{
	$ci =& get_instance();
	$query = $ci->common_model->customQuery("select MIN(price) as low_price from bidex_coin_order where pair ='".$pair."' and status = 'filled' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY)");

	$pair_detail = $ci->common_model->getTableData('trade_pairs',array('id' => $pair))->row();
	$from_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
	$to_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
	$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
	$pair_revsymbol = $to_currency->currency_symbol.$from_currency->currency_symbol;
	if($from_currency->currency_symbol=="ZCHIPT" || $to_currency->currency_symbol=="ZCHIPT")
	{
	$row = $query->row();
	$lowprice = $row->low_price;
	if($lowprice !=NULL)
	{
		$lowprice = $lowprice;
	}
	else
	{
	   $lowprice = 0;	
	}
	return $lowprice;
    }
	else
	{
    $row = $query->row();
	$lowprice = $row->low_price;
	if($lowprice !=NULL)
	{
		$lowprice = $lowprice;
	}
	else
	{
		$pairsym = $pair_symbol;
		$url = "http://api.binance.com/api/v1/ticker/24hr?symbol=".$pairsym."";
	    $result = file_get_contents($url);
	    $res = json_decode($result,true);
	    if(!empty($res))
	    {
	    	$lowprice = $res['lowPrice'];
	    }
	    else
	    {
	    	$pairsym_rev = $pair_revsymbol;
			$url_rev = "http://api.binance.com/api/v1/ticker/24hr?symbol=".$pairsym_rev."";
		    $result_rev = file_get_contents($url_rev);
		    $res_rev = json_decode($result_rev,true);
		    $lowprice = $res_rev['lowPrice'];

	    }

	}
	return $lowprice;
	}
	
}

function volume($pair)
{
	$ci =& get_instance();
	$query = $ci->common_model->customQuery("select sum(Amount) as volume from bidex_coin_order where pair ='".$pair."' and status = 'filled' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY)");

	$pair_detail = $ci->common_model->getTableData('trade_pairs',array('id' => $pair))->row();
	$from_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
	$to_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
	$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
	$pair_revsymbol = $to_currency->currency_symbol.$from_currency->currency_symbol;
	if($from_currency->currency_symbol=="COCO" || $to_currency->currency_symbol=="COCO")
	{
	$row = $query->row();
	$volume = $row->volume;
	if($volume !=NULL)
	{
		$volume = $volume;
	}
	else
	{
	   $volume = 0;	
	}
	return $volume;
    }
	else
	{
    $row = $query->row();
	$volume = $row->volume;
	if($volume !=NULL)
	{
		$volume = $volume;
	}
	else
	{
		

	    $query1 = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'volume');

        if($query1->num_rows()==1)
        {                   
            $res = $query1->row(); 
            $volume = $res->volume;           
        }
	}
	return $volume;

	}
}

function output_s($x, $y)
{
   $f = sprintf($y, $x);
   $f = rtrim($f, '0');
   $f = rtrim($f, '.');

   return $f;
}

function pricechangepercent($pair)
{
	  $ci =& get_instance();
	

	  $get_24_data_buy = $ci->common_model->customQuery("select SUM(price) as buy_price from bidex_coin_order where pair ='".$pair."' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY) and type='buy' and status = 'filled' ")->row();

	  $get_details_buy = $ci->common_model->customQuery("select * from bidex_coin_order where pair ='".$pair."' and type='buy' and status = 'filled' order by trade_id desc limit 0,1")->row();

	  $get_24_data_sell = $ci->common_model->customQuery("select SUM(price) as sell_price from bidex_coin_order where pair ='".$pair."' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY) and type='sell' and status = 'filled' ")->row();

	  $get_details_sell = $ci->common_model->customQuery("select * from bidex_coin_order where pair ='".$pair."' and type='sell' and status = 'filled' order by trade_id desc limit 0,1")->row();

	  $buy_data = $get_details_buy->price + $get_24_data_buy->buy_price;
	  $sell_data = $get_details_sell->price + $get_24_data_sell->sell_price;

	  $total = $buy_data - $sell_data;

	  $change_24 = $total;

	  $e_s = explode('E', $change_24);

	  $replace = abs($e_s[1]);
	  if ($e_s[1] != '') {
	      $change_24s = output_s($change_24, '%0.0'.$replace.'f');
	  } else {
	      $change_24s = $change_24;
	  }

	  if (round($change_24s) > 0 || round($change_24s) == 0) {
	      if ($change_24s == 0) {
	          $change_value_24 = number_format($change_24s, 6);
	          $percent_24 = round($change_24s) / 100;
	          $symbol = '+';
	          $per24 = $percent_24;
	      } else {
	          $change_value_24 = $change_24s;
	          $percent_24 = round($change_24s) / 100;
	          $symbol = '+';
	          $per24 = $percent_24;
	      }
	  } else {
	      $change_value_24 = $change_24s;
	      $percent_24 = round($change_24s) / 100;
	      $symbol = '-';
	      $per24 = $percent_24;
	  }
	$pair_detail = $ci->common_model->getTableData('trade_pairs',array('id' => $pair))->row();
	$from_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->from_symbol_id))->row();
	$to_currency = $ci->common_model->getTableData('currency',array('id' => $pair_detail->to_symbol_id))->row();
	$pair_symbol = $from_currency->currency_symbol.$to_currency->currency_symbol;
	$pair_revsymbol = $to_currency->currency_symbol.$from_currency->currency_symbol;
	if($from_currency->currency_symbol=="COCO" || $to_currency->currency_symbol=="COCO")
	{
		if($per24!="")
		{
			$per24 = $per24;
		}
		else
		{
		   $per24 = 0;	
		}
		return $per24;
    }
	else
	{ 
	    if($per24!="")
		{
			$per_24 = $per24;
		}
		else
		{
			

		$query1 = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair),'priceChangePercent');

        if($query1->num_rows()==1)
        {                   
            $res = $query1->row(); 
            $price = $res->priceChangePercent;           
        }
		}
		return $per_24;
	}
}

function pricechange($pair)
{
	  $ci =& get_instance();
	

	  $get_24_data_buy = $ci->common_model->customQuery("select SUM(price) as buy_price from bidex_coin_order where pair ='".$pair."' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY) and type='buy' and status = 'filled' ")->row();

	  $get_details_buy = $ci->common_model->customQuery("select * from bidex_coin_order where pair ='".$pair."' and type='buy' and status = 'filled' order by trade_id desc limit 0,1")->row();

	  $get_24_data_sell = $ci->common_model->customQuery("select SUM(price) as sell_price from bidex_coin_order where pair ='".$pair."' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY) and type='sell' and status = 'filled' ")->row();

	  $get_details_sell = $ci->common_model->customQuery("select * from bidex_coin_order where pair ='".$pair."' and type='sell' and status = 'filled' order by trade_id desc limit 0,1")->row();

	  $buy_data = $get_details_buy->price + $get_24_data_buy->buy_price;
	  $sell_data = $get_details_sell->price + $get_24_data_sell->sell_price;

	  $total = $buy_data - $sell_data;

	  $change_24 = $total;

	  $e_s = explode('E', $change_24);

	  $replace = abs($e_s[1]);
	  if ($e_s[1] != '') {
	      $change_24s = output_s($change_24, '%0.0'.$replace.'f');
	  } else {
	      $change_24s = $change_24;
	  }

	  if (round($change_24s) > 0 || round($change_24s) == 0) {
	      if ($change_24s == 0) {
	          $change_value_24 = number_format($change_24s, 6);
	      } else {
	          $change_value_24 = $change_24s;
	      }
	  } else {
	      $change_value_24 = $change_24s;
	  }


	if($change_value_24)
	{
		return $change_value_24;

	}
	else
	{
		return false;
	}
}

function getfeedetails_buy($pair)
{
	$ci =& get_instance();
	$from_det = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair))->row();
	$query = $ci->common_model->getTableData('currency',array('id'=>$from_det->from_symbol_id)); 

	$row = $query->row();
	return $row->maker_fee;
}

function getfeedetails_sell($pair)
{
	$ci =& get_instance();
	$to_det = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair))->row();
	$query = $ci->common_model->getTableData('currency',array('id'=>$to_det->to_symbol_id)); 
	$row = $query->row();
	return $row->taker_fee;
}

function get_decimalpairs($pair)
{
	$ci =& get_instance();
	$details = $ci->common_model->getTableData('trade_pairs',array('id'=>$pair))->row();
	return ($details->decimal_format>0)?$details->decimal_format:8;
}
function marketprice_pair($pair_symbol)
{
	
	$ci =& get_instance();
	$pair = explode("_",$pair_symbol);
	$from_cur = $ci->common_model->getTableData("currency",array('currency_symbol'=>$pair[0]))->row('id');
	$to_cur = $ci->common_model->getTableData("currency",array('currency_symbol'=>$pair[1]))->row('id');
	$pair_id = $ci->common_model->getTableData("trade_pairs",array('from_symbol_id'=>$from_cur,'to_symbol_id'=>$to_cur))->row('id');
	$lowestaskprice = lowestaskprice($pair_id);
	$highestbidprice = highestbidprice($pair_id);
	if($lowestaskprice !="" && $highestbidprice !="")
	{
		$marketprice = ($lowestaskprice + $highestbidprice)/2;
	}
	return $marketprice;

}

function marketprice_change($pair_symbol)
{
	$ci =& get_instance();
	$pair = explode("_",$pair_symbol);
	$from_cur = $ci->common_model->getTableData("currency",array('currency_symbol'=>$pair[0]))->row('id');
	$to_cur = $ci->common_model->getTableData("currency",array('currency_symbol'=>$pair[1]))->row('id');
	$pair_id = $ci->common_model->getTableData("trade_pairs",array('from_symbol_id'=>$from_cur,'to_symbol_id'=>$to_cur))->row('id');
	$per_24 = pricechangepercent($pair_id);
	return $per_24;

}

function Overall_USD_Balance($user_id){
	$ci =& get_instance();
	$Currency = $ci->common_model->getTableData("currency",array('status'=>'1'))->result();
	$User_Balance = 0;
	if(isset($Currency) && !empty($Currency)){
	foreach($Currency as $Currency_list){

		$User_Balance = $User_Balance + (getBalance($user_id,$Currency_list->id) * $Currency_list->online_usdprice);
	}
}
return $User_Balance;
	
}

function checkapi($pair)
{

	$ci =& get_instance();
	$query = $ci->common_model->customQuery("select * from bidex_trade_pairs where id ='".$pair."' and status = 1");
	$result = $query->row();
	return $result->api_status;

}

function shuffle_assoc($list) { 
  if (!is_array($list)) return $list; 

  $keys = array_keys($list); 
  shuffle($keys); 
  $random = array(); $i=0;
  foreach ($keys as $key) { 
  	$i++;
    $random[$i] = $list[$key]; 
  }
  return $random; 
} 

function getEmails($type,$key='') {
 	$ci =& get_instance();
	$name = $ci->db->where('type',$type)->get('email_list')->row();
	if ($name) {
		if($key!='')
		{
			return $name->$key;
		}
		else
		{
			return $name;
		}
	} else {
		return '';	
	}
 }

 function getfavourites($type,$var,$currency_id) {
 	$ci =& get_instance();
	$name = $ci->db->where($type,$var)->where('currency_id',$currency_id)->get('favourite_currency')->num_rows();

	if($name>0){
		return true;
	}
	else{
		return false;
	}
 }

function coin_decimal($decimal)
{
	for($start=0;$start<$decimal;$start++)
	{
		$append .= '0';
	}
	if($append!='')
	{
		$coin_decimal = "1".$append;
	}
	else
	{
		$coin_decimal = 0;
	}
	return $coin_decimal;

}

function updatetronAddress($id,$currency,$address=0,$hex_address,$s_key,$p_key)
{
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
	$upd=array();
	$data1 = array();
	$wallets=unserialize($wallet->row('address'));
	$wallets[$currency]=str_replace(' ', '', $address);
	//$upd['address']=serialize($wallets);
	$upd = serialize($wallets);

		$Fetch_coin_list = $ci->common_model->getTableData('currency',array('id'=>$currency),'currency_symbol')->row();

		$Symbol = $Fetch_coin_list->currency_symbol;


		$data1=array('address'=>$upd,$Symbol.'_status'=>1,'TRX_hexaddress'=>encryptIt(encryptIt($hex_address)),'TRX_skey'=>encryptIt(encryptIt($s_key)),'TRX_pkey'=>encryptIt(encryptIt($p_key)));
		$ci->db->where('user_id',$id);
		$ci->db->update('crypto_address', $data1);
		
		
	}
	return 1;
}

function gettronAddress($id)
{
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
		$address=decryptIt(decryptIt($wallet->row('TRX_hexaddress')));
	}
	return $address;
}

function gettronPrivate($id)
{
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('crypto_address');
	if($wallet->num_rows()==1)
	{
		$address=decryptIt(decryptIt($wallet->row('TRX_skey')));
	}
	return $address;
}

function getadmintronAddress($id)
{
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('admin_wallet');
	if($wallet->num_rows()==1)
	{
		$address = decryptIt(decryptIt($wallet->row('TRX_hexaddress')));
	}
	return $address;
}

function getadmintronPrivate($id)
{
	$balance=0;
	$ci =& get_instance();
	$wallet = $ci->db->where('user_id', $id)->get('admin_wallet');
	if($wallet->num_rows()==1)
	{
		$address = decryptIt(decryptIt($wallet->row('TRX_skey')));
	}
	return $address;
}

function TrimTrailingZeroes($nbr) {
    if(strpos($nbr,'.')!==false) $nbr = rtrim($nbr,'0');
    return rtrim($nbr,'.') ?: '0';
}

function trailingZeroes($num) {

    if(strpos($num,'.')!==false) $num = rtrim($num,'0');
    
    $converted = rtrim($num,'.') ?: '0';
    return $converted;
}

function numberFormatPrecision($number, $precision = 8, $separator = '.')
{
    $numberParts = explode($separator, $number);
    $response = $numberParts[0];
    if (count($numberParts)>1 && $precision > 0) {
        $response .= $separator;
        $response .= substr($numberParts[1], 0, $precision);
    }
    return $response;
}

function getPair($pair_symbol){

	$ci =& get_instance();

	$pair_array = explode("_",$pair_symbol);
	$from_symbol_id = getcoindetail($pair_array[0])->id;
	$to_symbol_id = getcoindetail($pair_array[1])->id;
    $where      =   array('from_symbol_id'=>$from_symbol_id,'to_symbol_id'=>$to_symbol_id);
    $pair_details   =   $ci->common_model->getTableData('trade_pairs',$where)->row();
    return $pair_details;
}

function change_high($pair){
	$ci =& get_instance();
	$query = $ci->common_model->customQuery("select MAX(Price) as high from bidex_coin_order where pair ='".$pair."' and status = 'filled' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
	 $row = $query->row();
	$high = $row->high;
	/*echo $ci->db->last_query();
	exit();*/
	return $high;
}

function change_low($pair){
	$ci =& get_instance();
	$query = $ci->common_model->customQuery("select MIN(Price) as low from bidex_coin_order where pair ='".$pair."' and status = 'filled' and datetime > DATE_SUB(CURDATE(), INTERVAL 1 DAY)");
	 $row = $query->row();
	$low = $row->low;
	return $low;
}

function apply_referral_fees_deduction($user_id,$fees)
{
	$ci =& get_instance();
	$referral_commision = $ci->common_model->getTableData('referral_commission',array('user_id'=>$user_id, 'status'=>'0'))->row();
	if(count($referral_commision) > 0)
	{
		$sitesettings = $ci->common_model->getTableData('site_settings',array('id'=>1))->row(); 
		$cut_fees = $fees * $sitesettings->referral_bonus/100;
		$final_fees = $fees - $cut_fees;
		$updateTableData = array('status'=>'1');
		$ci->common_model->updateTableData('referral_commission', array('id' => $referral_commision->id), $updateTableData);
		return $final_fees;
	} else {
		return $fees;
	}
}

function coinprice($coin_symbol)
{
    $ci =& get_instance();
	$usd_price = $ci->db->where('currency_symbol' , $coin_symbol)->get('currency')->row('online_usdprice');
	return $usd_price;
}
function IPtoLocation($ip){ 
    $apiURL = 'https://freegeoip.app/json/'.$ip; 
     
    // Make HTTP GET request using cURL 
    $ch = curl_init($apiURL); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $apiResponse = curl_exec($ch); 
    if($apiResponse === FALSE) { 
        $msg = curl_error($ch); 
        curl_close($ch); 
        return false; 
    } 
    curl_close($ch); 
     
    // Retrieve IP data from API response 
    $ipData = json_decode($apiResponse, true); 

    $city = $ipData['city'];

     return  $city;
    // Return geolocation data 
    //return !empty($ipData)?$ipData:false;

}
function get_servicename($id)
{
	$ci =& get_instance();
	$ci->db->where('id', $id);
	$service = $ci->db->get('service')->row('service_name');
	return $service;
}

function get_postivefeedback($id)
{
	$ci =& get_instance();
	$ci->db->where('user_id', $id);
	$ci->db->where('rating', 1);
	$service = $ci->db->get('tradefeedback')->result();

	return count($service);
}


function get_negativefeedback($id)
{
	$ci =& get_instance();
	$ci->db->where('user_id', $id);
	$ci->db->where('rating', 3);
	$service = $ci->db->get('tradefeedback')->result();

	return count($service);
}



function getReferer($parent=""){
	 	$ci =& get_instance();
	$referer = $ci->db->where('referralid', $parent)->get('users');
	return getUserEmail($referer->row('id'));
}
function getReferredUsers($referral=""){
	//  	$ci =& get_instance();
	// $referer = $ci->db->where('parent_referralid', $referral)->get('users');
	// return getUserEmail($referer->row('id'));
	 	$ci =& get_instance();
	$referer = $ci->db->where('parent_referralid',$referral)->get('users')->result();
	$users=array();
	foreach($referer as $ref){
		$users[]=getUserEmail($ref->id);
		// echo $users;
	}
	 return implode(',',$users);
}

function getUserUniqueId($id='') {
	$ci =& get_instance();
	$getuser = $ci->db->where('id', $id)->get('users')->row();
	return $getuser->unique_id;
}
function getcurrencydetails($sym)
{
	$ci =& get_instance();
	$cms_name = $ci->db->where('currency_symbol', $sym)->get('currency')->row();
	return $cms_name;
}

function coinprice_hitbtc($coin_symbol)
    {
        //$api_key = getSiteSettings('cryptocompare_apikey');
        //$api_key="c8bdfa132ecbee56d8c1fbae4e86a19ad7faf610604d48b29fa5703c07614fc3";

        //$url = "https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD&api_key=".$api_key;
        //echo $url;
		$url = "https://api.hitbtc.com/api/2/public/ticker/?symbols=".$coin_symbol;
		$curres = $coin_symbol;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		//$result = convercurr($coin_symbol,'USD');
		$res = json_decode($result);
		$result = $res[0];
		return $result->last;
       
    }

    function check_fav($id){
	$ci =& get_instance();
	$fav = $ci->db->where('pair_id', $id)->get('favoritepairs');
	// print_r($wallet);die;
	if($fav->num_rows()==1)
	{		
		$fav_id=$fav->row('pair_id');
		if($fav_id!='')
		{
			$fav_id['pair_id'];
		}
		else
		{
			$fav_id['pair_id'];
		}
		
	}
	return $fav_id;
}

// textbox sql injection validation
function validateTextBox($input)
{
//@,/,,,_,#,(,),~,!,<,>,=,',",&,|,\n,\t
$input = str_replace('@', 'XXXSYMBOLXXX', $input);
$input = str_replace('/', 'XXXSYMBOLXXX', $input);
$input = str_replace('', 'XXXSYMBOLXXX', $input);
$input = str_replace('_', 'XXXSYMBOLXXX', $input);
$input = str_replace('#', 'XXXSYMBOLXXX', $input);
$input = str_replace('(', 'XXXSYMBOLXXX', $input);
$input = str_replace(')', 'XXXSYMBOLXXX', $input);
$input = str_replace('`', 'XXXSYMBOLXXX', $input);
$input = str_replace('!', 'XXXSYMBOLXXX', $input);
$input = str_replace('<', 'XXXSYMBOLXXX', $input);
$input = str_replace('>', 'XXXSYMBOLXXX', $input);
$input = str_replace('=', 'XXXSYMBOLXXX', $input);
$input = str_replace("'", 'XXXSYMBOLXXX', $input);
$input = str_replace('"', 'XXXSYMBOLXXX', $input);
$input = str_replace('&', 'XXXSYMBOLXXX', $input);
$input = str_replace('|', 'XXXSYMBOLXXX', $input);
$input = str_replace('\n', 'XXXSYMBOLXXX', $input);
$input = str_replace('\t', 'XXXSYMBOLXXX', $input);
return $input;
}

function validateEmail($input)
{
//@,/,,,_,#,(,),~,!,<,>,=,',",&,|,\n,\t
$input = str_replace('/', 'XXXSYMBOLXXX', $input);
$input = str_replace('', 'XXXSYMBOLXXX', $input);
$input = str_replace('_', 'XXXSYMBOLXXX', $input);
$input = str_replace('#', 'XXXSYMBOLXXX', $input);
$input = str_replace('(', 'XXXSYMBOLXXX', $input);
$input = str_replace(')', 'XXXSYMBOLXXX', $input);
$input = str_replace('`', 'XXXSYMBOLXXX', $input);
$input = str_replace('!', 'XXXSYMBOLXXX', $input);
$input = str_replace('<', 'XXXSYMBOLXXX', $input);
$input = str_replace('>', 'XXXSYMBOLXXX', $input);
$input = str_replace('=', 'XXXSYMBOLXXX', $input);
$input = str_replace("'", 'XXXSYMBOLXXX', $input);
$input = str_replace('"', 'XXXSYMBOLXXX', $input);
$input = str_replace('&', 'XXXSYMBOLXXX', $input);
$input = str_replace('|', 'XXXSYMBOLXXX', $input);
$input = str_replace('\n', 'XXXSYMBOLXXX', $input);
$input = str_replace('\t', 'XXXSYMBOLXXX', $input);
return $input;
}

// function getcurrentprice($crypto,$currency){

// 		$url = "https://min-api.cryptocompare.com/data/price?fsym='".$crypto."'&tsyms='".$currency."'";
// 		$curres = $coin_symbol;
// 		$ch = curl_init();
// 		curl_setopt($ch, CURLOPT_URL, $url);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// 		$result = curl_exec($ch);
// 		//$result = convercurr($coin_symbol,'USD');
// 		$res = json_decode($result);

// 		print_r($res);
// 		// $result = $res[0];
// 		// return $result->last;



// }
