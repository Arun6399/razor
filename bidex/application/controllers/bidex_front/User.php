<?php
require_once APPPATH.'third_party/src/Google_Client.php';
require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';
require_once (APPPATH .'third_party/facebook-php-sdk/facebook-php-sdk/autoload.php');  
use Facebook\Facebook as FB; 
use Facebook\Authentication\AccessToken; 
use Facebook\Exceptions\FacebookResponseException; 
use Facebook\Exceptions\FacebookSDKException; 
use Facebook\Helpers\FacebookJavaScriptHelper; 
use Facebook\Helpers\FacebookRedirectLoginHelper; 

class User extends CI_Controller {
	public $outputData;
	public function __construct()
	{	
		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation'));
		$this->load->library('session');
		$this->load->helper(array('url', 'language'));
		$lang_id = $this->session->userdata('site_lang');
		if($lang_id == '')
		{
			$this->lang->load('content','english');
			$this->session->set_userdata('site_lang','english');
		}
		else
		{
			$this->lang->load('content',$lang_id);	
			$this->session->set_userdata('site_lang',$lang_id);
		}
		$sitelan = $this->session->userdata('site_lang'); 
	}
	function switchLang($language = "") 
    {
       $language = ($language != "") ? $language : "english";
       $this->session->set_userdata('site_lang', $language);
       redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }
	public function block()
	{
		$cip = get_client_ip();
		$match_ip = $this->common_model->getTableData('page_handling',array('ip'=>$cip))->row();
		if($match_ip > 0)
		{
		return 1;
		}
		else
		{
		return 0;
		}
	}

	public function privacy_policy(){

$this->load->view('front/user/privacy_policy');

	}

	public function block_ip()
    {
        $this->load->view('front/common/blockips');
    }
	function login()
	{		
		$user_id=$this->session->userdata('user_id');
		if($user_id!="") {	
			front_redirect('', 'refresh');
		}
		$data['site_common'] = site_common();
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
		$data['action'] = front_url() . 'login_user';		
		$this->load->view('front/user/login', $data);
	}

		function faq(){
	   $data['site_common'] = site_common();
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
        $data['faq'] = $this->common_model->getTableData('faq',array('status'=>'1'))->result();

   $this->load->view('front/user/faq',$data);
	}

  


	public function login_check()
    {
        $ip_address = get_client_ip();
        $array = array('status' => 0, 'msg' => '');
        $this->form_validation->set_rules('login_detail', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('login_password', 'Password', 'trim|required|xss_clean');
       // $this->form_validation->set_rules('remember', 'Remember', 'trim|required|xss_clean');
        // When Post

        if ($this->input->post()) {

            if ($this->form_validation->run()) {
            	
                $email = lcfirst($this->input->post('login_detail'));
                $password = $this->input->post('login_password');
                //$remember = $this->input->post('remember');
                if($this->block() == 1)
				{
				$array['status'] = 2;
				$array['msg'] = 'Your IP is blocked contact admin!';
				die(json_encode($array));
				}
                $prefix = get_prefix();
                // Validate email
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $check = checkSplitEmail($email, $password);
                }
                if (!$check) {
                    //vv
                    $array['msg'] = 'Enter Valid Login Details';
                } else {
                    if ($check->verified != 1) {

                        $array['msg'] = 'Please check your email to activate Bidex account';
                    } else { 
                        $array['status'] = 1;
                        if ($check->randcode == 'enable' && $check->secret != '') { 
                            $array['tfa_status'] = 1;
                            $login_tfa = $this->input->post('login_tfa');
                            $check1 = $this->checktfa($check->id, $login_tfa);
                            // echo "<pre>"; print_r($check1);die;
                            if ($check1) {
                                $session_data = array(
                                    'user_id' => $check->id,
                                );
                                $this->session->set_userdata($session_data);
                                $this->common_model->last_activity('Login', $check->id);
                                $this->session->set_flashdata('success', $this->lang->line('Welcome back . Logged in Successfully'));
                                $array['msg'] = $this->lang->line('Welcome back . Logged in Successfully');
                                if ($check->verify_level2_status == 'Completed') {
                                    $array['login_url'] = 'wallet';
                                }
                                $array['tfa_status'] = 0;
                            } else {
                                $array['msg'] = 'Enter Valid TFA Code';
                            }
                        } else { 
                            $session_data = array(
                                'user_id' => $check->id,
                            );
                            $this->session->set_userdata($session_data);
                            $this->common_model->last_activity('Login', $check->id, "", $ip_address);
                            $array['tfa_status'] = 0;
                            //if($check->verify_level2_status=='Completed')
                            //{
                            //$this->session->set_flashdata('success', 'Welcome back . Logged in Successfully');
                            $array['msg'] = 'Welcome back . Logged in Successfully';
                            $array['login_url'] = 'wallet';
                            //}
                        }
                    }    
                 
                }
            } else {
                $array['msg'] = validation_errors();
            }
        } else {
            $array['msg'] = $this->lang->line('Login error');
        }
        die(json_encode($array));
    }
    function checktfa($user_id,$code)
    {
        $this->load->library('Googleauthenticator');
        $ga     = new Googleauthenticator();
        $result = $this->common_model->getTableData('users', array('id' => $user_id))->row_array();
        if(count($result)){
			$secret = $result['secret'];
			$oneCode = $ga->verifyCode($secret,$code,$discrepancy = 3);
			if($oneCode==1)
			{
				return true;
			}
			else
			{
				return false;
			}
	   }else
	   return false;
    }
    function forgot_user()
	{
		//If Already logged in
		$user_id=$this->session->userdata('user_id');
		// if($user_id!="")
		// {	
		// 	front_redirect('', 'refresh');
		// }
		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'forgot_password'))->row();
		$data['action'] = front_url() . 'forgot_user';
		$data['js_link'] = 'forgot';
		$this->load->view('front/user/forgot_password', $data);
	}

	  function gen()
	{
echo (rand(10, 30) . "<br>");
echo (rand(1, 1000000) . "<br>");
echo (rand());

	}
	
	function forgot_check()
	{ 
		$array=array('status'=>0,'msg'=>'');
		$this->form_validation->set_rules('forgot_detail', 'Email or Phone', 'trim|required|xss_clean');
		// When Post
		if ($this->input->post())
		{ 
			if ($this->form_validation->run())
			{
				$email = $this->input->post('forgot_detail');
				$prefix=get_prefix();
				// Validate email
				if (filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$check=checkSplitEmail($email);
					$type=1;
				}
				else
				{
					$check=checkElseEmail($email);
					$type=2;
				}
				if (!$check)
				{
					$array['msg']='User does not Exists';
				}
				else
				{
					    if ($check->verified != 1) {
                            
                        $array['msg'] = 'Please check your email to activate Bidex account';

                        }else{
						$array['status']=1;
						$key = sha1(mt_rand() . microtime());
						$update = array(
						'forgotten_password_code' => $key,
						'forgotten_password_time' => time(),
						'forgot_url'=>0
						);
						$link=front_url().'reset_pw_user/'.$key;
						$this->common_model->last_activity('Forgot Password',$check->id);
						$this->common_model->updateTableData('users',array('id'=>$check->id),$update);

							$to      	= getUserEmail($check->id);
							$email_template = 3;
							$username=$prefix.'username';
							$site_common      =   site_common();

							$special_vars = array(					
							'###USERNAME###' => $check->$username,
							'###LINK###' => $link
							);
							$this->email_model->sendMail($to, '', '', $email_template,$special_vars);

							$array['msg']= 'Password reset link is sent to your email';
						}

				}
			}
			else
			{
				$array['msg']=validation_errors();
			}	
		}
		else
		{
			$array['msg']=$this->lang->line('Login error');
		}	
		die(json_encode($array));
	}
	function reset_pw_user($code = NULL)
	{
		if (!$code)
		{
			front_redirect('', 'refresh');
		}
		$profile = $this->common_model->getTableData('users', array('forgotten_password_code' => $code))->row(); 
		if($profile)
		{
			if($profile->forgot_url!=1)
			{
				$expiration=15*60;
				if (time() - $profile->forgotten_password_time < $expiration)
				{
					$this->form_validation->set_rules('reset_password', 'Password', 'trim|required|xss_clean');
					// When Post
					if ($this->input->post())
					{
						if ($this->form_validation->run())
						{
							$prefix=get_prefix();
							$password=$this->input->post('reset_password');
							$data = array(
							$prefix.'password'                => encryptIt($password),
							'forgotten_password_code' => NULL,
							'verified'                  => 1,
							'forgot_url'                  => 1
							);
							$this->common_model->last_activity('Password Reset',$profile->id);
							$this->common_model->updateTableData('users',array('forgotten_password_code'=>$code),$data);
							$this->session->set_flashdata('success',$this->lang->line('Password reset successfully'));
							front_redirect('','refresh');
						}
						else
						{
							$this->session->set_flashdata('error', $this->lang->line('Enter Password and Confirm Password'));
							front_redirect('reset_pw_user/'.$code,'refresh');
						}	
					}
					$data['action'] = front_url() . 'reset_pw_user/'.$code;
					$data['site_common'] = site_common();
					$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'reset_password'))->row();
					$data['js_link'] = 'reset_password';
					$this->load->view('front/user/reset_pwd', $data);
				}
				else
				{
					$this->session->set_flashdata('error', $this->lang->line('Link Expired'));
					front_redirect('', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('Already reset password using this link'));
				front_redirect('', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('Not a valid link'));
			front_redirect('', 'refresh');
		}
	}

	function referral(){

	 $user_id=$this->session->userdata('user_id');
	 $data['site_common'] = site_common();
	 $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'forgot_password'))->row();
     $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
     $referralid=$data['users']->referralid;
     $get_child_users= $this->common_model->getTableData('users',array('parent_referralid'=>$referralid))->result();
     $data['referral_count']=count($get_child_users);

     $this->load->view('front/user/referral',$data);
}

	function register()
	{		
		 $data['ref']=$_GET['ref'];
		$data['site_common'] = site_common();
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
		$data['action'] = front_url() . 'signup';		
		$this->load->view('front/user/register', $data);
	}

public function swap(){
$data['site_common'] = site_common();
$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
// $data['currency'] = $this->common_model->getTableData("currency")->result();
$data['currency']=$this->common_model->customQuery("SELECT * FROM bidex_currency  where status='1'  order by id desc")->result();
$data['currency_list']=$this->common_model->customQuery("SELECT * FROM bidex_currency where  status='1'  order by id asc")->result();
$user_id=$this->session->userdata('user_id');

$check_balance = $this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row();
$data['wallets'] = json_encode( (unserialize($check_balance->crypto_amount))['Exchange AND Trading'] );
    $day_one=date('Y-m-d');
    $day_two=date('Y-m-d', strtotime('-1 days'));
    $day_three=date('Y-m-d', strtotime('-2 days'));
    $day_four=date('Y-m-d', strtotime('-3 days'));
    $day_five=date('Y-m-d', strtotime('-4 days'));
    $day_six=date('Y-m-d', strtotime('-5 days'));
    $day_seven=date('Y-m-d', strtotime('-6 days'));
 $data['day_one'] = $this->common_model->getTableData('instant_swap',array('date'=>$day_one,'user_id'=>$user_id))->result();
 $data['day_two'] = $this->common_model->getTableData('instant_swap',array('date'=>$day_two,'user_id'=>$user_id))->result();
 $data['day_three'] = $this->common_model->getTableData('instant_swap',array('date'=>$day_three,'user_id'=>$user_id))->result();
 $data['day_four'] = $this->common_model->getTableData('instant_swap',array('date'=>$day_four,'user_id'=>$user_id))->result();
 $data['day_five'] = $this->common_model->getTableData('instant_swap',array('date'=>$day_five,'user_id'=>$user_id))->result();
 $data['day_six'] = $this->common_model->getTableData('instant_swap',array('date'=>$day_six,'user_id'=>$user_id))->result();
 $data['day_seven'] = $this->common_model->getTableData('instant_swap',array('date'=>$day_seven,'user_id'=>$user_id))->result();

$this->load->view('front/user/swap_new',$data);
  }
 public function save_swap(){

  $user_id=$this->session->userdata('user_id');

 	if($this->input->post()){

         $amount_sell=$this->input->post('amountone');
         $amount_buy=$this->input->post('amounttwo');
 	     $amount_sell_coin=$this->input->post('coin_one');
 	     $amount_buy_coin=$this->input->post('coin_two');
 	     $fees=$this->input->post('update_fees');
 	     $date=date("Y-m-d");
 	     $data=array(
            'user_id'=>$user_id,
 	     	'sell_currency'=>$amount_sell_coin,
 	     	'buy_currency'=> $amount_buy_coin,
 	     	'sell_price'=>$amount_sell,
 	     	'buy_price'=> $amount_buy,
 	     	'admin_fees'=> $fees,
 	     	'status'=>'completed',
 	     	'date'=>$date
 	     );

 	    $amount_sell_coin_id= $this->common_model->getTableData("currency",array('currency_symbol'=>$amount_sell_coin))->row();
         $format='8';
        $buyer_balance=getBalance($user_id,$amount_sell_coin_id->id,$format);


        if($buyer_balance <  $amount_buy){

              $this->session->set_flashdata('error','Your Wallet Balance is low');
          // $this->session->set_flashdata('success','Order Placed successfully');
							front_redirect('swap','refresh');

        }
            if($amount_buy==='0'){

              $this->session->set_flashdata('error','Please Enter Valid Amount');
          // $this->session->set_flashdata('success','Order Placed successfully');
							front_redirect('swap','refresh');

        }


 	     // print_r($data);


 	     // exit();

$result=$this->common_model->insertTableData('instant_swap',$data);

if($result){

         $amount_sell_coin_id= $this->common_model->getTableData("currency",array('currency_symbol'=>$amount_sell_coin))->row();
         $format='8';
        $buyer_balance=getBalance($user_id,$amount_sell_coin_id->id,$format);
    $adminbalance = getadminBalance(1,$amount_sell_coin_id->id);
	$finaladmin_balance = $adminbalance+$fees;
	$updateadmin_balance = updateadminBalance(1,$amount_sell_coin_id->id,$finaladmin_balance);


        $finalbalance=$buyer_balance - $amount_buy;
        $updatebalance = updateBalance($user_id,$amount_sell_coin_id->id,$finalbalance,'crypto'); // Update balance
    
        $amount_buy_coin_id= $this->common_model->getTableData("currency",array('currency_symbol'=>$amount_buy_coin))->row();

            $buyer_balance_buy=getBalance($user_id,$amount_buy_coin_id->id,$format);

             $finalbalance_buy=$buyer_balance_buy + $amount_sell;
            $updatebalance = updateBalance($user_id,$amount_buy_coin_id->id,$finalbalance_buy,'crypto'); // Update balance

              $this->session->set_flashdata('success','Swapped successfully');
          // $this->session->set_flashdata('success','Order Placed successfully');
							front_redirect('swap','refresh');
}else{



	$this->session->set_flashdata('error','Try Again');

    // $this->session->set_flashdata('error','Try Again');
							front_redirect('swap','refresh');
}



 	}




 } 


	function signup()
	{		
		$data['site_common'] = site_common();
		
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'signup'))->row();
		
		
				
		if(!empty($_POST))
		{ 
            $this->form_validation->set_rules('firstname', 'Firstname', 'trim|required|xss_clean');
             $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required|xss_clean');
             $this->form_validation->set_rules('phone', 'Phone', 'trim|required|xss_clean');
             $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean');
            $this->form_validation->set_rules('register_email', 'Email Address', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('register_password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('register_cpassword', 'Password', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('agreement', 'Agreement', 'trim|required|xss_clean');
			
			if ($this->form_validation->run())
			{ 
				$email = strip_tags(trim($this->db->escape_str(lcfirst($this->input->post('register_email')))));
				$password = strip_tags(trim($this->db->escape_str($this->input->post('register_password'))));
				$cpassword = strip_tags(trim($this->db->escape_str($this->input->post('register_cpassword'))));
				$firstname = strip_tags(trim($this->db->escape_str($this->input->post('firstname'))));
				$lastname = strip_tags(trim($this->db->escape_str($this->input->post('lastname'))));
				$phone = strip_tags(trim($this->db->escape_str($this->input->post('phone'))));
				$country = strip_tags(trim($this->db->escape_str($this->input->post('country'))));
				$parent_id = $this->db->escape_str($this->input->post('parentid'));
				//$agreement = $this->db->escape_str($this->input->post('agreement'));
				//$usertype = $this->db->escape_str($this->input->post('usertype'));
				$check=checkSplitEmail($email);
				$prefix=get_prefix();
				
				//$check1=$this->common_model->getTableData('users',array($prefix.'username'=>$uname));
				if($check)
				{
					$this->session->set_flashdata('error',$this->lang->line('Entered Email Address Already Exists'));
					front_redirect('signup', 'refresh');
				}
				else
				{				
					$Exp = explode('@', $email);
					$User_name = $Exp[0];

					$activation_code = time().rand(); 
					$str=splitEmail($email);
					$ip_address = get_client_ip();
					$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
					$refferalid=substr(str_shuffle($permitted_chars), 0, 10);

					$user_data = array(
					'usertype' => '1',
					$prefix.'email'    => $str[1],
					$prefix.'username'	=> $this->input->post('firstname'),
					$prefix.'fname'	=> $this->input->post('firstname'),
					$prefix.'lname'	=> $this->input->post('lastname'),
					$prefix.'password' => encryptIt($password),
					$prefix.'cpassword' => encryptIt($cpassword),
					$prefix.'phone' => $this->input->post('phone'),
					'country' => $country,
					'activation_code'  => $activation_code,
					'verified'         =>'0',
					'register_from'    =>'Website',
					'ip_address'       =>$ip_address,
						'referralid' => $refferalid,
                    'parent_referralid' => $parent_id,
					'browser_name'     =>getBrowser(),
					'verification_level' =>'1',
					'created_on' =>gmdate(time())
					);
					$user_data_clean = $this->security->xss_clean($user_data);
					$id=$this->common_model->insertTableData('users', $user_data_clean);

					$usertype=$prefix.'type';
					$this->common_model->insertTableData('history', array('user_id'=>$id, $usertype=>encryptIt($str[0])));
					$this->common_model->last_activity('Registration',$id);
					$a=$this->common_model->getTableData('currency','id')->result_array();
					$currency = array_column($a, 'id');
					$currency = array_flip($currency);
					$currency = array_fill_keys(array_keys($currency), 0);
					$wallet=array('Exchange AND Trading'=>$currency);
					
					$this->common_model->insertTableData('wallet', array('user_id'=>$id, 'crypto_amount'=>serialize($wallet)));

					$b=$this->common_model->getTableData('currency',array('type'=>'digital'),'id')->result_array();
					$currency1 = array_column($b, 'id');
					$currency1 = array_flip($currency1);
					$currency1 = array_fill_keys(array_keys($currency1), 0);

					$this->common_model->insertTableData('crypto_address', array('user_id'=>$id,'status'=>0, 'address'=>serialize($currency1)));
					$reg=$this->common_model->getTableData('email_template',array('id'=>'2','status'=>1))->result();
					if($reg){
					// check to see if we are creating the user
					$email_template = 'Registration';
					$site_common      =   site_common();
					$special_vars = array(
					'###USERNAME###' => $firstname,
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					}
					$this->session->set_flashdata('success',$this->lang->line('Thank you for Signing up. Please check your e-mail and click on the verification link.'));
					front_redirect('login', 'refresh');
				}
			}
			else
			{
				$email = $this->db->escape_str(lcfirst($this->input->post('register_email')));
				$parent_id = $this->db->escape_str(lcfirst($this->input->post('parentid')));


				$result=$this->common_model->getTableData("users",array('referralid'=>$parent_id))->num_rows();

				if($result=='0'){

            $this->session->set_flashdata('error','Please Enter Valid Referral Id');
					front_redirect('register', 'refresh');

				}

				$data['register_email'] =$email;
				$data['parentid'] =$parent_id ; 


				// print_r($data['parentid']);
				// exit();

			}	
		}

		$data['countries'] = $this->common_model->getTableData('country')->result();
		$data['site_common'] = site_common();
		$data['action'] = front_url() . 'signup';	
		$this->load->view('front/user/signup', $data);
		//front_redirect('home', 'refresh');
	}
	function oldpassword_exist()
	{
		$oldpass = $this->db->escape_str($this->input->post('oldpass'));
		$prefix=get_prefix();
		$check=$this->common_model->getTableData('users',array($prefix.'password'=>encryptIt($oldpass)))->result();
		//echo count($check);
		if (count($check)>0)
		{
			echo "true";
		}
		else
		{
			echo "false";
		}
	}
	function email_exist()
	{
		$email = $this->db->escape_str($this->input->get_post('email'));
		$check=checkSplitEmail($email);
		if (!$check)
		{
			echo "true";
		}
		else
		{
			echo "false";
		}
	}

	function username_exist()
	{
		$username = $this->db->escape_str($this->input->get_post('username'));
		$prefix=get_prefix();
		$check=$this->common_model->getTableData('users',array($prefix.'username'=>$username));
		if ($check->num_rows()==0)
		{
			echo "true";
		}
		else
		{
			echo "false";
		}
	}	
	function get_csrf_token()
	{
		echo $this->security->get_csrf_hash();
	}	
	function logout(){
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('pass_changed');
		//$this->session->unset_userdata('access_token');
		//$this->google->revokeToken();
		$this->session->sess_destroy();

		$this->session->set_flashdata('success', $this->lang->line('Logged Out successfully'));
		front_redirect('home','refresh');
	}
	function verify_user($activation_code){
		$activation_code=$this->db->escape_str($activation_code);	
		$user_id=$this->session->userdata('user_id');
		if($user_id!="") {	
			front_redirect('', 'refresh');
		}
		$activation=$this->common_model->getTableData('users',array('activation_code'=>urldecode($activation_code)));
		// echo "<pre>";print_r($activation->num_rows());die;
		if ($activation->num_rows()>0)
		{
			$detail=$activation->row();
			if($detail->verified==1)
			{
				$this->session->set_flashdata('error', $this->lang->line('Your Email is already verified.'));
				front_redirect("login", 'refresh');
			}
			else
			{

				$this->common_model->updateTableData('users',array('id'=>$detail->id),array('verified'=>1));
				$this->common_model->last_activity('Email Verification',$detail->id);
				$this->session->set_flashdata('success', $this->lang->line('Your Email is verified now.'));
				front_redirect("login", 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('Activation link is not valid'));
			front_redirect("login", 'refresh');
		}
	}
	function profile()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['country']= $this->common_model->getTableData('countries',array('id'=>$data['users']->country))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['site_common'] = site_common();
		$this->load->view('front/user/profile', $data); 
	}
	function editprofile()
	{		 
		$this->load->library('session','form_validation');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('Please Login'));
			redirect(base_url().'home');
		}
		if($_POST)
		{
			if(isset($_POST['profile_form'])) {
				
				$insertData['bidex_fname'] = strip_tags(trim($this->db->escape_str($this->input->post('fname'))));
				if ($_FILES['profile']['name']!="") 
				{
					$imagepro = $_FILES['profile']['name'];
					if($imagepro!="" && getExtension($_FILES['profile']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["profile"],'uploads/user/'.$user_id,$this->input->post('profile'));
						if($uploadimage1)
						{
							//$imagepro=$uploadimage1['secure_url'];
							if(is_array($uploadimage1)) {
							$imagepro=$uploadimage1['secure_url'];
							} else {
								$errorMsg = current( (Array)$uploadimage1 );
								$this->session->set_flashdata('error', $errorMsg);
								front_redirect('settings_profile', 'refresh');
							}
						}
						else
						{
							$this->session->set_flashdata('error', $this->lang->line('Problem with profile picture'));
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['profile_picture']=$imagepro;
				}
				$condition = array('id' => $user_id);
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);

				if ($insert) {
					$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));
					$this->session->set_flashdata('success', $this->lang->line('Profile details Updated Successfully'));
					front_redirect('settings_profile', 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->lang->line('Something there is a Problem .Please try again later'));
					front_redirect('settings_profile', 'refresh');
				}
			} else if(isset($_POST['email_form'])) {
				
				//$insertData['bidex_email'] = $this->db->escape_str($this->input->post('newemail'));
				$password= strip_tags(trim($this->db->escape_str($this->input->post('newpassword'))));
				$cpassword= strip_tags(trim($this->db->escape_str($this->input->post('newpassword'))));
				$insertData['bidex_password']=encryptIt($password);
				$insertData['bidex_cpassword']=encryptIt($cpassword);
				$condition = array('id' => $user_id);
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);

				if ($insert) {
					$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));
					$this->session->set_flashdata('success', $this->lang->line('Profile details Updated Successfully'));
					front_redirect('settings_profile', 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->lang->line('Something there is a Problem .Please try again later'));
					front_redirect('settings_profile', 'refresh');
				}
			}
			else if(isset($_POST['personal_form'])) {
			
				// echo "<pre>";print_r($_POST);
				$this->form_validation->set_rules('street_address', 'Address', 'required|xss_clean');
				if($this->form_validation->run())
				{
					$insertData['bidex_fname'] = strip_tags(trim($this->db->escape_str($this->input->post('f_name'))));
					//$insertData['bidex_lname'] = $this->db->escape_str($this->input->post('lastname'));
					$insertData['dob'] = strip_tags(trim($this->db->escape_str($this->input->post('dob'))));
					$insertData['street_address'] = strip_tags(trim($this->db->escape_str($this->input->post('street_address'))));
					$insertData['street_address_2'] = strip_tags(trim($this->db->escape_str($this->input->post('street_address_2'))));
					$insertData['city'] = strip_tags(trim($this->db->escape_str($this->input->post('city'))));
					//$insertData['state'] = $this->db->escape_str($this->input->post('state'));
					$insertData['postal_code'] = strip_tags(trim($this->db->escape_str($this->input->post('postal_code'))));
					//$insertData['bidex_phone'] = $this->db->escape_str($this->input->post('phone'));
					//$insertData['national_tax_number'] = $this->db->escape_str($this->input->post('national_tax_number'));

				// 	$paypal_email = $this->input->post('paypal_email');
				// 	if(isset($paypal_email) && !empty($paypal_email)){
				// 	$insertData['paypal_email'] = $this->db->escape_str($paypal_email);
				// }				
					$insertData['verification_level'] = '2';
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['country']	 	   = strip_tags(trim($this->db->escape_str($this->input->post('country'))));
					//$insertData['bidex_phone']	= $this->db->escape_str($this->input->post('phone'));
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);

					
					if ($insert) {
						$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));
						$this->session->set_flashdata('success', $this->lang->line('Profile details Updated Successfully'));
						front_redirect('settings_profile', 'refresh');
					} else {
						$this->session->set_flashdata('error', $this->lang->line('Something there is a Problem .Please try again later'));
						front_redirect('settings_profile', 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Some datas are missing'));
					front_redirect('settings_profile', 'refresh');
				}
			}
		}		
		front_redirect('settings_profile', 'refresh'); 
	}
	function update_profileimage()
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
		}
		if($_FILES)
		{			
				$prefix=get_prefix();
				$imagepro = $_FILES['profile_photo']['name'];
				if($imagepro!="" && getExtension($_FILES['profile_photo']['type']))
				{
					$uploadimage1=cdn_file_upload($_FILES["profile_photo"],'uploads/user/'.$user_id,$this->input->post('profile_photo'));
					if($uploadimage1)
					{
						$imagepro=$uploadimage1['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error', $this->lang->line('Problem with yourself holding photo ID'));
						front_redirect('profile', 'refresh');
					} 
				}
				else 
				{
					$imagepro='';
				}

				$insertData = array();
				$insertData['profile_picture']=$imagepro;				
				$condition = array('id' => $user_id);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData);
				if ($insert) {
					$this->session->set_flashdata('success',$this->lang->line('Profile image Updated Successfully'));
					front_redirect('profile', 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->lang->line('Something there is a Problem .Please try again later'));
					front_redirect('profile', 'refresh');
				}			
		}
    }
    function kyc()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', 'you are not logged in');
			redirect(base_url().'home');
		}
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'kyc'))->row();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['site_common'] = site_common();	

		$this->load->view('front/user/kyc', $data); 
	}
	function address_verifications1()	{
	
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
		}
		if($_FILES)	{	
			$insertData = array();			
			$prefix=get_prefix();

			$image = $_FILES['photo_id_1']['name'];
			$image1 = $_FILES['photo_id_2']['name'];
			$image2 = $_FILES['photo_id_3']['name'];
			$image3 = $_FILES['photo_id_4']['name'];
			$image4 = $_FILES['photo_id_5']['name'];
			if($image!="" && getExtension($_FILES['photo_id_1']['type']))
			{	
				$ext = getExtension($_FILES['photo_id_1']['type']);
				$Img_Size = $_FILES['photo_id_1']['size'];
				
				if($Img_Size>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}
				$ext = getExtension($_FILES['photo_id_1']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_1')));
				$uploadimage=$upload_image['secure_url'];
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_1')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage=$image_path;
						
					}

				}
				if($uploadimage)
				{
					$image=$uploadimage;
					
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document front id'));
					front_redirect('kyc', 'refresh');
				}
				$insertData['photo_id_1'] = $image;	
				// print_r($image);
				// exit();

			} 
			
			if($image1!="" && getExtension($_FILES['photo_id_2']['type']))
			{		
				$Img_Size1 = $_FILES['photo_id_2']['size'];
				if($Img_Size1>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_2']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_2')));
				$uploadimage1=$upload_image['secure_url'];
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_2')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage1=$image_path;
						
					}

				}
				if($uploadimage1)
				{
					$image1=$uploadimage1;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document back id'));
					front_redirect('kyc', 'refresh');
				}
				$insertData['photo_id_2'] = $image1;
			} 

			if($image2!="" && getExtension($_FILES['photo_id_3']['type']))
			{		
				$Img_Size2 = $_FILES['photo_id_3']['size'];
				if($Img_Size2>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_3']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
				$uploadimage2=$upload_image['secure_url'];
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_3')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage2=$image_path;
						
					}

				}
				if($uploadimage2)
				{
					$image2=$uploadimage2;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document front id'));
					front_redirect('kyc', 'refresh');
				}
				$insertData['photo_id_3'] = $image2;
			}

			if($image3!="" && getExtension($_FILES['photo_id_4']['type']))
			{		
				$Img_Size3 = $_FILES['photo_id_4']['size'];
				if($Img_Size3>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_4']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_4"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_4')));
				$uploadimage3=$upload_image['secure_url'];
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_4')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage3=$image_path;
						
					}

				}
				if($uploadimage3)
				{
					$image3=$uploadimage3;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document back id'));
					front_redirect('kyc', 'refresh');
				}
				$insertData['photo_id_4'] = $image3;
			}

			if($image4!="" && getExtension($_FILES['photo_id_5']['type']))
			{		
				$Img_Size4 = $_FILES['photo_id_5']['size'];
				if($Img_Size4>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_5']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_5"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_5')));
				$uploadimage4=$upload_image['secure_url'];
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_5')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage4=$image_path;
						
					}

				}
				if($uploadimage4)
				{
					$image4=$uploadimage4;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your selfie document'));
					front_redirect('kyc', 'refresh');
				}
				$insertData['photo_id_5'] = $image4;
			}
							
			$insertData['verify_level2_date'] = gmdate(time());
			$insertData['verify_level2_status'] = 'Pending';
			$insertData['photo_1_status'] = 1;	   
			$insertData['photo_2_status'] = 1;
			$insertData['photo_3_status'] = 1;
			$insertData['photo_4_status'] = 1;
			$insertData['photo_5_status'] = 1;

			// echo "<pre>";print_r($insertData);die;
			$condition = array('id' => $user_id);
			$insertData_clean = $this->security->xss_clean($insertData);
			$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
			if($insert !='') {
				$this->session->set_flashdata('success','Your details have been sent to our team for verification');
				front_redirect('kyc', 'refresh');
			} 
            elseif($insert !='') {
				$this->session->set_flashdata('success', 'Your Kyc Verification cancelled successfully');
				front_redirect('kyc', 'refresh');
			}
			else {
				$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
				front_redirect('kyc', 'refresh');
			}
		}
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$this->load->view('front/user/kyc', $data); 
	}



  public function move_to_admin_wallet($coinname,$crypto_type='')
	{
		echo "MOVE To Admin Wallet Begins";
		echo "<br/>";
		echo $coinname."----".$crypto_type;
		echo "<br/>";
	    $coinname =  str_replace("%20"," ",$coinname);

	    $currency_det   =  $this->db->query("select * from bidex_currency where currency_name = '".$coinname."' limit 1")->row(); 

      // echo $this->db->last_query();

      // exit();

	    // echo $currency_det->move_admin;

	    // exit();

	    if($currency_det->move_admin==1)
	    {

			//echo "inn";
	    $currency_status = $currency_det->currency_symbol.'_status';
	   //$address_list    =  $this->db->query("select * from tarmex_crypto_address where ".$currency_status." = '1' ")->result(); 
	  $address_list    =  $this->db->query("select * from bidex_transactions where type = 'Deposit' and status = 'Completed' and currency_id = ".$currency_det->id." and crypto_type = '".$crypto_type."' and amount > '".$currency_det->move_coin_limit."' and admin_move = 0 ")->result();

   // echo $this->db->last_query();

	  // exit(); 


		echo "Total Transaction pending".count($address_list);

		echo "<br/>";
	    $fetch           =  $this->db->query("select * from bidex_admin_wallet where id='1' limit 1")->row(); 
	    $get_addr        =  json_decode($fetch->addresses,true);
	    
        
        $coin_type = $currency_det->coin_type;
		// Added to make currency_decimal dynamic
		$currency_decimal = $currency_det->currency_decimal;
		if($crypto_type == 'tron' && $currency_det->trx_currency_decimal != '')
		{

			$currency_decimal = $currency_det->trx_currency_decimal;
		} else if($crypto_type == 'bsc' && $currency_det->bsc_currency_decimal != '')
		{
			$currency_decimal = $currency_det->bsc_currency_decimal;
		}

    
		//echo $currency_decimal; exit;
		
	    $coin_decimal = coin_decimal($currency_decimal);
	    
	    $min_deposit_limit = $currency_det->move_coin_limit;


	    if($coinname!="")
	    {
	        $i =1;
            if(!empty($address_list)){
	        foreach ($address_list as $key => $value) {
				echo $value->trans_id."starts";
				echo "<br/>";
	        	$from='';
	                //$arr       = unserialize($value->address);
	                //$from      = $arr[$currency_det->id];

					$crypto_type = $value->crypto_type; // modifying this for making crypto_type dynamic
					if($value->crypto_type == 'tron')
						$curr_symbol = 'TRX';
					else if($value->crypto_type == 'bsc')
						$curr_symbol = 'BNB';
					else if($value->crypto_type == 'eth')
						$curr_symbol = 'ETH';
					$currency_decimal = $currency_det->currency_decimal;
					if($crypto_type == 'tron' && $currency_det->trx_currency_decimal != '')
					{
						$currency_decimal = $currency_det->trx_currency_decimal;
					} else if($crypto_type == 'bsc' && $currency_det->bsc_currency_decimal != '')
					{
						$currency_decimal = $currency_det->bsc_currency_decimal;
					}
					$coin_decimal = coin_decimal($currency_decimal);

					$toaddress       =  $get_addr[$curr_symbol];  // modifying this for making crypto_type dynamic
	        	    $from = $value->crypto_address;

	                $user_id = $value->user_id;
	                $trans_id = $value->trans_id;
	                 $from_address='';$amount=0;
	                 if($coin_type=="token" && $crypto_type=='tron')
	                 {
	                 	$tron_private = gettronPrivate($user_id);
						$crypto_type_other = array('crypto'=>$crypto_type,'tron_private'=>$tron_private);
	                 	$amount    = $this->local_model->wallet_balance($coinname,$from,$crypto_type_other);
	                 } 
					 else if($coin_type == 'token')
					 {
						$crypto_type_other = array('crypto'=>$crypto_type);
						$amount    = $this->local_model->wallet_balance($coinname,$from,$crypto_type_other);
					 }
	                 else
	                 {


                     // echo $coinname;

                     // echo "--------";
                     // echo $from;

                     // exit();


	              	$amount = $this->local_model->wallet_balance($coinname,$from);

	                 }
    
	                $minamt    = $currency_det->min_withdraw_limit;
	                $from_address = trim($from); 
	                $to = trim($toaddress);
	        
	                if($from_address!='0') {
	                	echo "Address - ".$from_address;
	                	echo "----";
	                	echo "Balance - ".$amount;


	                	// exit();

	                if($amount>=$min_deposit_limit) 
	                {
	                	echo $amount."<br/>";
	                	echo "transfer";
						echo "<br/>";
						echo "CRYPTO TYPE".$crypto_type;
						echo "<br/>";
						echo "COIN TYPE".$coin_type;
					   	
		                if($coin_type=="token")
		                {
		                	
							if($crypto_type=='eth')
							{


								$GasLimit = 50000;
		                        $GasPrice = $this->check_ethereum_functions('eth_gasPrice','Ethereum');


		                        //$GasPrice = 185 * 1000000000;
		                        
		                        $amount_send = $amount;
								// echo $amount; 
								// echo "<br/>";
								// echo $coin_decimal;
								// exit;
		                        $amount1 = $amount_send * $coin_decimal;

		                        echo "<br/>".$GasPrice."<br/>";

		                        $trans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
		                        echo "<pre>";
		                        print_r($trans_det);
							}
							elseif($crypto_type=='bsc')
							{

								echo $GasLimit = 80000;
		                        //$GasPrice = $this->check_ethereum_functions('eth_gasPrice','BNB');

		                       echo  $GasPrice = 6000000000;



		                        // echo "test";


		                        // exit();

		                        $amount_send = $amount;
								// echo $amount_send;
								// echo "<br/>";
								// echo $coin_decimal;
		                       $amount1 = $amount_send * $coin_decimal;
								
	                            echo "<br/>".$GasPrice."<br/>";

		                      $trans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);


		                                      // $trans_det = array('from'=>$from_address,'to'=>$to,'value'=>'10000','gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);

		                        // echo "<pre>";print_r($trans_det);
		                        // exit();
							}
							else
							{
					           echo $amount1 = $amount * $coin_decimal;
					            $fee_limit = 2000000;


                 //               echo "test";

					            // exit();

					            $privateKey = gettronPrivate($user_id);
								//$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),'privateKey'=>$privateKey);

								$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>(float)$amount1,'privateKey'=>$privateKey);

							}
		                	
                            if($crypto_type=='eth')
		                	{
		                		$eth_balance = $this->local_model->wallet_balance("Ethereum",$from_address); // get balance from blockchain
		                		$transfer_currency = "Ethereum";
		                		$check_amount = "0.005";
		                		//$check_amount = "0.01";
		                	}
		                	elseif($crypto_type=='tron')
		                	{
		                		$eth_balance = $this->local_model->wallet_balance("Tron",$from_address); // get balance from blockchain
		                		$transfer_currency = "Tron";
		                		$check_amount = "5";
		                	}
		                	else
		                	{
		                		$eth_balance = $this->local_model->wallet_balance("BNB",$from_address); // get balance from blockchain
		                		$transfer_currency = "BNB";
		                		$check_amount = "0.004";
		                	}
							echo "Balance".$eth_balance;
							echo "<br/>";
							echo "Check Amounts".$check_amount;

							// exit();

		                	if($eth_balance >= $check_amount)
		                	{
								echo "MOVEADMINWALLET_IF";
								//exit;
		                		if($crypto_type=='eth' || $crypto_type=='bsc')
		                		{
		                			$txn_count = $this->get_pendingtransaction($from_address,$coinname);
		                		}
		                		else
		                		{
		                			$txn_count = 0;
		                		}


		                		// echo $txn_count;

		                		// exit();
		                		
		                		if($txn_count==0)
		                		{
									echo $coinname;
		                			print_r($trans_det);
									echo $crypto_type;

		                			$send_money_res_token = $this->local_model->make_transfer($coinname,$trans_det,$crypto_type); // transfer to admin

		       //          				echo "test-test";
									// exit;
									// echo "inini";
									print_r($send_money_res_token);
									// exit;
                                   if($send_money_res_token !="" || $send_money_res_token !="error")
                                    {
                                    	$tnx_data = array(
											'userid'=>$value->user_id,
											'crypto_address' => $from_address,
											'amount'=>(float)$amount,
											'currency_symbol'=>$currency_det->currency_symbol,
											'currency_id'=>$value->currency_id,
											'type'=>'User to Admin Wallet',
											'status'=>'Completed',
											'date_created'=>date('Y-m-d H:i:s'),
											'txn_id'=>$send_money_res_token
										);
										$ins = $this->common_model->insertTableData('admin_wallet_logs',$tnx_data);
	                                    $update = $this->common_model->updateTableData("transactions",array("admin_move"=>0,"trans_id"=>$trans_id),array("admin_move"=>1));
			                			
                                    }
		                			
		                		}
		                		
                                
		                	}
		                	else
		                	{
								echo "else";
								//exit;
		                		if($crypto_type=='eth')
		                		{
		                		$eth_amount = 0.005;
                                $GasLimit1 = 21000;
                                $Gas_calc1 = $this->check_ethereum_functions('eth_gasPrice','Ethereum');
		                        $Gwei1 = $Gas_calc1;
		                        $GasPrice1 = $Gwei1;
		                        $Gas_res1 = $Gas_calc1 / 1000000000;
		                        $Gas_txn1 = $Gas_res1 / 1000000000;
                                $txn_fee = $GasLimit1 * $Gas_txn1;
                                //$send_amount = $eth_amount + $txn_fee;
		                		$eth_amount1 = $eth_amount * 1000000000000000000;
		                        $nonce1 = $this->get_transactioncount($to,$coinname);
		                        $eth_trans = array('from'=>$to,'to'=>$from_address,'value'=>(float)$eth_amount1,'gas'=>(float)$GasLimit1,'gasPrice'=>(float)$GasPrice1);

		                		}
		                		elseif($crypto_type=='bsc')
		                		{
		                		$eth_amount = 0.005;
                                $GasLimit1 = 120000;
                                //$Gas_calc1 = $this->check_ethereum_functions('eth_gasPrice','BNB');
                                $Gas_calc1 = 30000000000;
		                        $Gwei1 = $Gas_calc1;
		                        $GasPrice1 = $Gwei1;
		                        $Gas_res1 = $Gas_calc1 / 1000000000;
		                        $Gas_txn1 = $Gas_res1 / 1000000000;
                                $txn_fee = $GasLimit1 * $Gas_txn1;
                                //$send_amount = $eth_amount + $txn_fee;
		                		$eth_amount1 = $eth_amount * 1000000000000000000;
		                        $nonce1 = $this->get_transactioncount($to,$coinname);
		                        $eth_trans = array('from'=>$to,'to'=>$from_address,'value'=>(float)$eth_amount1,'gas'=>(float)$GasLimit1,'gasPrice'=>(float)$GasPrice1);

		                		}
		                		else
		                		{
		                		
					                $amount1 = 5 * 1000000;
					                $privateKey = getadmintronPrivate(1);
									$eth_trans 		= array('fromAddress'=>$to,'toAddress'=>$from_address,'amount'=>(float)$amount1,"privateKey"=>$privateKey);

		                		}

		                		if($crypto_type=='eth' || $crypto_type=='bsc')
		                		{
		                			$txn_count = $this->get_pendingtransaction($to,$transfer_currency);
		                		}
		                		else
		                		{
		                			$txn_count = 0;
		                		}
								// echo "innn";
								// echo $txn_count;
								// print_r($eth_trans); exit;
                                
                               if($txn_count==0)
                               {
								 
                               	$send_money_res = $this->local_model->make_transfer($transfer_currency,$eth_trans); // admin to user wallet

                               	if($send_money_res !="" || $send_money_res !="")
                               	{
                               		 $tnx_data = array(
		                                        'userid'=>$value->user_id,
		                                        'crypto_address' => $from_address,
		                                        'amount'=>(float)$amount,
		                                        'currency_symbol'=>$currency_det->currency_symbol,
												'currency_id'=>$value->currency_id,
												'type'=>'Admin to User Wallet',
		                                        'status'=>'Completed',
		                                        'date_created'=>date('Y-m-d H:i:s'),
		                                        'txn_id'=>$send_money_res
		                                    );
		                           $ins = $this->common_model->insertTableData('admin_wallet_logs',$tnx_data);
                               	}
                               }
                              
		                	}
		                }
		                 else
		                {

		                	// echo "test_new_one";

		                	// exit();
							
							// Coin deposit transfer from user wallet to admin wallet 
							$coin_transfer = '';
							if($crypto_type=='eth')
							{
								$GasLimit = 70000;
		                        $Gas_calc = $this->check_ethereum_functions('eth_gasPrice','Ethereum');
		                        echo "<br/>".$Gas_calc."<br/>";
		                        $Gwei = $Gas_calc;
		                        $GasPrice = $Gwei;
		                        $Gas_res = $Gas_calc / 1000000000;
		                        $Gas_txn = $Gas_res / 1000000000;
		                        $txn_fee = $GasLimit * $Gas_txn;
		                        echo "Transaction Fee".$txn_fee."<br/>";
		                        $amount_send = ($amount - $txn_fee)-0.0005;
		                        echo "Amount Send ".$amount_send."<br/>";

		                        echo "Total Amount ".($txn_fee+$amount_send)."<br/>";
		                        $amount1 = ($amount_send * 1000000000000000000);

		                        echo sprintf("%.40f", $amount1)."<br/>";
		                        $coin_transfer = "Ethereum";
		                        $cointrans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);

		                       /* echo "<pre>";
		                        print_r($cointrans_det);*/
							}
							elseif($crypto_type=='bsc')
							{

						    // echo "test_new";
							// exit();
							$GasLimit = 50000;
		                    //$GasPrice = $this->check_ethereum_functions('eth_gasPrice','BNB');
		                    $Gas_calc = 50000000000;
			                echo "<br/>".$Gas_calc."<br/>";
	                        $Gwei = $Gas_calc;
	                        $GasPrice = $Gwei;
	                        $Gas_res = $Gas_calc / 1000000000;
	                        $Gas_txn = $Gas_res / 1000000000;
	                        $txn_fee = $GasLimit * $Gas_txn;
							echo "Transaction Fee".$txn_fee."<br/>";
                                    $amount;
							// exit();
	                        $amount_send = ($amount - $txn_fee);
							echo "Amount Send ".$amount_send."<br/>";

							// exit();
	                        $amount1 = ($amount_send * 1000000000000000000);
								                        
							echo sprintf("%.40f", $amount1)."<br/>";
	                        $coin_transfer = "BNB";

	                        // echo $amount1;
	                        $cointrans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
 

                         //       print_r($cointrans_det);
	                        // exit();
							}
							else
							{
								$from_address = trim($from_address);
								$to = trim($to);	
				                $amount1 = $amount * 1000000;
				                $privateKey = gettronPrivate($user_id);
				                $coin_transfer = "Tron";
								$cointrans_det = array('fromAddress'=>$from_address,'toAddress'=>$to,'amount'=>(float)$amount1,"privateKey"=>$privateKey);
							}
		                	
		                    if($crypto_type=='eth' || $crypto_type=='bsc')
	                		{


	                			$txn_count = $this->get_pendingtransaction($from_address,$coin_transfer);

	                		}
	                		else
	                		{
	                			$txn_count = 0;
	                		}
	                		
                            echo "txn count";
                             echo "<br>";
                            echo $txn_count;
                            echo "<br>";

                            // exit();
	                		if($txn_count==0)
	                		{
								echo $coin_transfer;
								echo "<br/>";
								print_r($cointrans_det);
								echo "dsd";
								 // exit;
                            $send_money_res_coin = $this->local_model->make_transfer($coin_transfer,$cointrans_det); 

                            // transfer to admin
							// echo "<pre>";
							// print_r($send_money_res_coin);
							// exit;

                            if($send_money_res_coin !="" || $send_money_res_coin !="")
                           	{
								$tnx_data = array(
									'userid'=>$value->user_id,
									'crypto_address' => $from_address,
									'amount'=>(float)$amount,
									'currency_symbol'=>$currency_det->currency_symbol,
									'currency_id'=>$value->currency_id,
									'status'=>'Completed',
									'type'=>'User to Admin Wallet',
									'date_created'=>date('Y-m-d H:i:s'),
									'txn_id'=>$send_money_res_coin
								);
								$ins = $this->common_model->insertTableData('admin_wallet_logs',$tnx_data);
                				$update = $this->common_model->updateTableData("transactions",array("admin_move"=>0,"trans_id"=>$trans_id),array("admin_move"=>1));
                		    }
	                			
	                			
	                			
	                		}
	                		

                        
		                	
		                }
		       
                        /*if($send_money_res!="" || $send_money_res!="error")
                        {
		                    $trans_data = array(
		                                        'userid'=>$value->user_id,
		                                        'crypto_address' => $from_address,
		                                        'type'=>'deposit',
		                                        'amount'=>(float)$amount,
		                                        'currency_symbol'=>$currency_det->currency_symbol,
		                                        'status'=>'Completed',
		                                        'date_created'=>date('Y-m-d H:i:s'),
		                                        'currency_id'=>$currency_det->id,
		                                        'txn_id'=>$send_money_res
		                                    );
		                    $insert = $this->common_model->insertTableData('admin_wallet_logs',$trans_data);*/
		                  
		                    $result = array('status'=>'success','message'=>'update deposit success');
	                    //}
	                }
	                else
	                {
                      $result = array('status'=>'failed','message'=>'update deposit failed insufficient balance');
	                }

	            }
	            else
	            {
	                $result = array('status'=>'failed','message'=>'invalid address');	
	            }

	        $i++;}
	       }
	       else
	       	{
	       		$result = array("status"=>"failed","message"=>"transactions not found for admin wallet");
	       	}

	    }
	    echo json_encode($result);

	    }
	    
	}




	function address_verification() {
		$user_id=$this->session->userdata('user_id');
			if($user_id=="")
			{	
				front_redirect('', 'refresh');
			}
							
				$prefix=get_prefix();

				$image = $_FILES['photo_id_3']['name'];
				$image1 = $_FILES['photo_id_4']['name'];
					if($image!="" && getExtension($_FILES['photo_id_3']['type']))
			{	
				$ext = getExtension($_FILES['photo_id_3']['type']);
				$Img_Size = $_FILES['photo_id_3']['size'];
				
				if($Img_Size>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}
				$ext = getExtension($_FILES['photo_id_3']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
				//$uploadimage=$upload_image['secure_url'];
				if(is_array($upload_image)) {
							$uploadimage=$upload_image['secure_url'];
						} else {
							$errorMsg = current( (Array)$upload_image );
							$this->session->set_flashdata('error', $errorMsg);
							front_redirect('kyc', 'refresh');
						}
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_3')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage=$image_path;
						
					}

				}
				if($uploadimage)
				{
					$image=$uploadimage;
					
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document front id'));
					front_redirect('kyc', 'refresh');
				}
				

			} 
			else 
			{ 
				$image = $this->db->escape_str($this->input->post('photo_ids_3'));
			}


					if($image1!="" && getExtension($_FILES['photo_id_4']['type']))
			{		
				$Img_Size1 = $_FILES['photo_id_4']['size'];
				if($Img_Size1>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_4']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_4"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_4')));
				//$uploadimage1=$upload_image['secure_url'];
				if(is_array($upload_image)) {
							$uploadimage1=$upload_image['secure_url'];
						} else {
							$errorMsg = current( (Array)$upload_image );
							$this->session->set_flashdata('error', $errorMsg);
							front_redirect('kyc', 'refresh');
						}
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_4')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage1=$image_path;
						
					}

				}
				if($uploadimage1)
				{
					$image1=$uploadimage1;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document back id'));
					front_redirect('kyc', 'refresh');
				}
				//$insertData['photo_id_2'] = $image1;
			} 
			else 
			{ 
				$image1 = $this->db->escape_str($this->input->post('photo_ids_4'));
			}

					$insertData = array();
					$insertData['photo_id_3'] = $image;	
					$insertData['photo_id_4'] = $image1;				
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_3_status'] = 1;
					$insertData['photo_4_status'] = 1;	                
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert) {
						$this->session->set_flashdata('success',$this->lang->line('Your details have been sent to our team for verification'));
						front_redirect('kyc', 'refresh');
					} 
	                else {
						$this->session->set_flashdata('error',$this->lang->line('Unable to send your details to our team for verification. Please try again later!'));
						front_redirect('kyc', 'refresh');
					}
			
	}
	function id_verification()	{
		$user_id=$this->session->userdata('user_id');
			if($user_id=="")
			{	
				front_redirect('', 'refresh');
			}
			
				$image2 = $_FILES['photo_id_1']['name'];
				$image3 = $_FILES['photo_id_2']['name'];
					if($image2!="" && getExtension($_FILES['photo_id_1']['type']))
			{		
				$Img_Size2 = $_FILES['photo_id_1']['size'];
				if($Img_Size2>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_1']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_1')));
				//$uploadimage2=$upload_image['secure_url'];
				if(is_array($upload_image)) {
							$uploadimage2=$upload_image['secure_url'];
						} else {
							$errorMsg = current( (Array)$upload_image );
							$this->session->set_flashdata('error', $errorMsg);
							front_redirect('kyc', 'refresh');
						}
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_1')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage2=$image_path;
						
					}

				}
				if($uploadimage2)
				{
					$image2=$uploadimage2;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document front id'));
					front_redirect('kyc', 'refresh');
				}
				//$insertData['photo_id_3'] = $image2;
			} 
			else 
			{ 
				$image2 = $this->db->escape_str($this->input->post('photo_ids_1'));
			}



					if($image3!="" && getExtension($_FILES['photo_id_2']['type']))
			{		
				$Img_Size3 = $_FILES['photo_id_2']['size'];
				if($Img_Size3>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_2']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_2')));
				//$uploadimage3=$upload_image['secure_url'];
				if(is_array($upload_image)) {
							$uploadimage3=$upload_image['secure_url'];
						} else {
							$errorMsg = current( (Array)$upload_image );
							$this->session->set_flashdata('error', $errorMsg);
							front_redirect('kyc', 'refresh');
						}
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_2')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage3=$image_path;
						
					}

				}
				if($uploadimage3)
				{
					$image3=$uploadimage3;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your document back id'));
					front_redirect('kyc', 'refresh');
				}
				//$insertData['photo_id_4'] = $image3;
			}
			else 
			{ 
				$image3 = $this->db->escape_str($this->input->post('photo_ids_2'));
			}

					$insertData = array();
					$insertData['photo_id_1'] = $image2;
					$insertData['photo_id_2'] = $image3;
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_1_status'] = 1;
					$insertData['photo_2_status'] = 1;
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert) {
						$this->session->set_flashdata('success',$this->lang->line('Your details have been sent to our team for verification'));
						front_redirect('kyc', 'refresh');
					} 
	                else {
						$this->session->set_flashdata('error',$this->lang->line('Unable to send your details to our team for verification. Please try again later!'));
						front_redirect('kyc', 'refresh');
					}
			
	}
	function photo_verification(){
		$user_id=$this->session->userdata('user_id');
			if($user_id=="")
			{	
				front_redirect('', 'refresh');
			}
			
				$image4 = $_FILES['photo_id_5']['name'];
					if($image4!="" && getExtension($_FILES['photo_id_5']['type']))
			{		
				$Img_Size4 = $_FILES['photo_id_5']['size'];
				if($Img_Size4>2000000){
					$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					front_redirect('kyc', 'refresh');
				}

				$ext = getExtension($_FILES['photo_id_5']['type']);
				if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
				$upload_image=cdn_file_upload($_FILES["photo_id_5"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_5')));
				//$uploadimage4=$upload_image['secure_url'];
				if(is_array($upload_image)) {
							$uploadimage4=$upload_image['secure_url'];
						} else {
							$errorMsg = current( (Array)$upload_image );
							$this->session->set_flashdata('error', $errorMsg);
							front_redirect('kyc', 'refresh');
						}
				}
				elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('photo_id_5')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$upload_image = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$upload_image;
						$uploadimage4=$image_path;
						
					}

				}
				if($uploadimage4)
				{
					$image4=$uploadimage4;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Problem with your selfie document'));
					front_redirect('kyc', 'refresh');
				}
				//$insertData['photo_id_5'] = $image4;
			} 
			else 
			{ 
				$image4 = $this->db->escape_str($this->input->post('photo_ids_5'));
			}
					$insertData['photo_id_5'] = $image4;
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_5_status'] = 1;
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert) {
						$this->session->set_flashdata('success',$this->lang->line('Your details have been sent to our team for verification'));
						front_redirect('kyc', 'refresh');
					} 
	                else {
						$this->session->set_flashdata('error',$this->lang->line('Unable to send your details to our team for verification. Please try again later!'));
						front_redirect('kyc', 'refresh');
					}
			
	}
	function pwcheck(){
        $pwd = $_POST['oldpass'];
        $epwd = encryptIt($pwd);
        $Cnt_Row = $this->common_model->getTableData('users', array('bidex_password' => $epwd,'id'=>$this->session->userdata('user_id')))->num_rows();    
        if($Cnt_Row > 0){
            echo '0';
        }
        else{
            echo '1';
        }
    }

     public function settings_security(){

        if($this->block() == 1)
                { 
                front_redirect('block_ip');
                }
        $user_id=$this->session->userdata('user_id');


        if($user_id=="")
        {   
            $this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
            redirect(base_url().'home');
        }
        
        $this->load->library('Googleauthenticator');
        $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
   
    if($data['users']->randcode=="enable" || $data['users']->secret!="")
    { 
      $secret = $data['users']->secret; 
      $data['secret'] = $secret;
      $ga     = new Googleauthenticator();
      $data['url'] = $ga->getQRCodeGoogleUrl('Bidex', $secret);
    }
    else
    {
      $ga = new Googleauthenticator();
      $data['secret'] = $ga->createSecret();
      $data['url'] = $ga->getQRCodeGoogleUrl('Bidex', $data['secret']);
      $data['oneCode'] = $ga->getCode($data['secret']);
    }

      


    if(isset($_POST['tfa_sub']))
    {
      $ga = new Googleauthenticator();
      $secret_code = $this->db->escape_str($this->input->post('secret'));
      $onecode = strip_tags(trim($this->db->escape_str($this->input->post('code'))));

      $code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 6);
      

      if($data['users']->randcode != "enable")
      {

     
        if($code==1)
        {
          
          $this->db->where('id',$user_id);
          $data1=array('secret'  => $secret_code,'randcode'  => "enable");
          $data1_clean = $this->security->xss_clean($data1);
          $this->db->update('users',$data1_clean);
              
          $this->session->set_flashdata('success','TFA Enabled successfully');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          front_redirect('settings_security', 'refresh');
        }
        else
        {
       
          $this->session->set_flashdata('error','Please Enter correct code to enable TFA');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          front_redirect('settings_security', 'refresh');
        }
      }
      else
      {

        if($code==1)
        {

          $this->db->where('id',$user_id);
          $data1=array('secret'  => $secret_code,'randcode'  => "disable");
          $data1_clean = $this->security->xss_clean($data1);
          $this->db->update('users',$data1_clean);  
          $this->session->set_flashdata('success','TFA Disabled successfully');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          front_redirect('settings_security', 'refresh');
        }
        else
        {

          
          $this->session->set_flashdata('error','Please Enter correct code to disable TFA');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          front_redirect('settings_security', 'refresh');
        }
      }
    }

    if(isset($_POST['phone_add'])) {
			
				// echo "<pre>";print_r($_POST);
				$this->form_validation->set_rules('phone_number', 'Phone', 'required|xss_clean');
				if($this->form_validation->run())
				{
					//$insertData['bidex_fname'] = $this->db->escape_str($this->input->post('f_name'));
					
					$insertData['bidex_phone']	= strip_tags(trim($this->db->escape_str($this->input->post('phone_number'))));
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);

					
					if ($insert) {
						
						$this->session->set_flashdata('success', $this->lang->line('Phone Number Updated Successfully'));
						front_redirect('settings_security', 'refresh');
					} else {
						$this->session->set_flashdata('error', $this->lang->line('Something there is a Problem .Please try again later'));
						front_redirect('settings_security', 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Some datas are missing'));
					front_redirect('settings_security', 'refresh');
				}
			}

        $data['bank_details'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

        $data['site_common'] = site_common();
        $data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'profile-edit'))->row();
        $data['countries'] = $this->common_model->getTableData('countries')->result();  



        $this->load->view('front/user/settings_security', $data); 
    }

	function settings($tab=null)
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('Please Login'));
			redirect(base_url().'home');
		}

		$data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Deposit','verify_status'=>'kyc_verify'),'','','','','','',array('trans_id','DESC'))->result();
		$this->load->library('Googleauthenticator');
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link'=>'settings'))->row();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		// echo "<pre>";
		// print_r($data['deposit_history']);
		// exit();
		$data['user_bank'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id))->row();
		if($data['users']->randcode=="enable" || $data['users']->secret!="")
		{	
			$secret = $data['users']->secret; 
			$data['secret'] = $secret;
        	$ga     = new Googleauthenticator();
			$data['url'] = $ga->getQRCodeGoogleUrl('bidex', $secret);
		}
		else
		{
			$ga = new Googleauthenticator();
			$data['secret'] = $ga->createSecret();
			$data['url'] = $ga->getQRCodeGoogleUrl('bidex', $data['secret']);
			$data['oneCode'] = $ga->getCode($data['secret']);
		}
		if(isset($_POST['chngpass']))
		{
			$prefix = get_prefix();
			$oldpassword = encryptIt($this->input->post("oldpass"));
			$newpassword = encryptIt($this->input->post("newpass"));
			$confirmpassword = encryptIt($this->input->post("confirmpass"));
			
			// Check old pass is correct/not
			$password = $prefix.'password';
			if($oldpassword == $data['users']->$password)
			{ 
				//check new pass is equal to confirm pass
				if($newpassword==$confirmpassword)
				{ 
					$this->db->where('id',$user_id);
					$data=array($prefix.'password'  => $newpassword);
					$this->db->update('users',$data);
					$this->session->set_flashdata('success',$this->lang->line('Password changed successfully'));
					front_redirect('settings/account-change-password', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Confirm password must be same as new password'));
					front_redirect('settings/account-change-password', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('Your old password is wrong'));
				front_redirect('settings/account-change-password', 'refresh');
			}			
		}
		
		$data['site_common'] = site_common();

		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['currencies'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1))->result();
		$this->load->view('front/user/settings', $data);
	}
	function support()
	{
		// echo encryptIt('Spiegel@123');die;
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'support'))->row();
		if(isset($_POST['submit_tick']))
		{
			$image = $_FILES['supportpic']['name'];
			if($image!="") {
				if(getExtension($_FILES['supportpic']['type']))
				{			
					$ext = getExtension($_FILES['supportpic']['type']);
				    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
					$uploadimage1=cdn_file_upload($_FILES["supportpic"],'uploads/user/'.$user_id);
					//$upload_image=$uploadimage1['secure_url'];
					if(is_array($uploadimage1)) {
							$upload_image=$uploadimage1['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage1 );
							$this->session->set_flashdata('error', $errorMsg);
							front_redirect('support', 'refresh');
						}
				}elseif($ext == 'pdf'){
					
					$config['upload_path'] = './uploads/';
                    $config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('file-upload-field')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$uploadimage1 = $this->upload->data('file_name');
						$image_path = base_url(). 'uploads/'.$uploadimage1;
						$upload_image=$image_path;
						
					}

				}
					if($upload_image)
					{
						$image=$upload_image;
					}
					else
					{
						$this->session->set_flashdata('error', $this->lang->line('Error occur!! Please try again'));
						front_redirect('support', 'refresh');
					}
					$image=$image;
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Please upload proper image format'));
					front_redirect('support', 'refresh');	
				}
			} 
			else 
			{ 
				$image = "";
			}
			$insertData['user_id'] = $user_id;
			$insertData['subject'] = strip_tags(trim($this->input->post('subject')));
			$insertData['message'] = strip_tags(trim($this->input->post('comments')));
			$insertData['name'] = strip_tags(trim($this->input->post('name')));
			$insertData['email'] = strip_tags(trim($this->input->post('email')));
			//$insertData['category'] = $this->input->post('category');
			$insertData['image'] = $image;
			$insertData['created_on'] = gmdate(time());
			$insertData['ticket_id'] = 'TIC-'.encryptIt(gmdate(time()));
			$insertData['status'] = '1';
			$insert = $this->common_model->insertTableData('support', $insertData);
			if ($insert) {

				$email_template   	= 'Support_admin';
				$email_template_user   	= 'Support_user';
				$site_common      	=   site_common();

                $enc_email = getAdminDetails('1','email_id');
                $adminmail = decryptIt($enc_email);
                $usermail = getUserEmail($user_id);
                $username = getUserDetails($user_id,'bidex_username');
                $message = $this->input->post('message');
				$special_vars 		= array(
						'###SITELINK###' 		=> front_url(),
						'###SITENAME###' 		=> $site_common['site_settings']->english_site_name,
						'###USERNAME###' 		=> $username,
						'###MESSAGE###'  		=> "<span style='color: #500050;'>".$message . "</span><br>",
						'###LINK###' 			=> admin_url().'support/reply/'.$insert
				);
				
				$special_vars_user 		= array(
						'###SITELINK###' 		=> front_url(),
						'###SITENAME###' 		=> $site_common['site_settings']->english_site_name,
						'###USERNAME###' 		=> $username,
						'###MESSAGE###'  		=> "<span style='color: #500050;'>".$message . "</span><br>"
				);

				$this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars);
				$this->email_model->sendMail($usermail, '', '', $email_template_user, $special_vars_user);

				$this->session->set_flashdata('success', $this->lang->line('Your message successfully sent to our team'));
				front_redirect('support', 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->lang->line('Error occur!! Please try again'));
				front_redirect('support', 'refresh');
			}
		}

		$data['site_common'] = site_common();		
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['action'] = front_url() . 'support';

		$data['category'] = $this->common_model->getTableData('support_category', array('status' => '1'))->result();
		$data['support'] = $this->common_model->getTableData('support', array('user_id' => $user_id, 'parent_id'=>0))->result();

		$data['prefix'] = get_prefix();

		$this->load->view('front/user/support', $data);

	}
	function support_reply($code='')
	{ 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'support'))->row();
		$data['prefix'] = get_prefix();
		$data['support'] = $this->common_model->getTableData('support', array('user_id' => $user_id, 'ticket_id'=>$code))->row();
		$id = $data['support']->id;
		//$data['support_reply'] = $this->common_model->getTableData('support', array('parent_id'=>$data['support']->id,'id'=>$id))->result();
		$data['support_reply'] = $this->db->query("SELECT * FROM `bidex_support` WHERE `parent_id` = '".$id."'")->result();
		if($_POST)
		{
			$image = $_FILES['image']['name'];
			if($image!="") {
				if(getExtension($_FILES['image']['type']))
				{			
					$uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$user_id);
					if($uploadimage1)
					{
						$image=$uploadimage1['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error', 'Please upload proper image format');
						front_redirect('support_reply/'.$code, 'refresh');
					}
					$image=$image;
				}
				else
				{
					$this->session->set_flashdata('error','Please upload proper image format');
					front_redirect('support_reply/'.$code, 'refresh');	
				}
			} 
			else 
			{ 
				$image = "";
			}
			$insertsData['status'] = '1';
			$update = $this->common_model->updateTableData('support',array('ticket_id'=>$code),$insertsData);
			if($update){
				$insertData['parent_id'] = $data['support']->id;
				$insertData['user_id'] = $user_id;
				$insertData['message'] = $this->input->post('message');
				$insertData['image'] = $image;
				$insertData['created_on'] = gmdate(time());
				$insert = $this->common_model->insertTableData('support', $insertData);
				if ($insert) {

					$email_template   	= 'Support_admin';
					$email_template_user   	= 'Support_user';
					$site_common      	=   site_common();
	                $enc_email = getAdminDetails('1','email_id');
	                $adminmail = decryptIt($enc_email);
	                $usermail = getUserEmail($user_id);
	                $username = getUserDetails($user_id,'bidex_username');
	                $message = $this->input->post('message');
					$special_vars 		= array(
							'###SITELINK###' 		=> front_url(),
							'###SITENAME###' 		=> $site_common['site_settings']->site_name,
							'###USERNAME###' 		=> $username,
							'###MESSAGE###'  		=> "<span style='color: #500050;'>".$message . "</span><br>",
							'###LINK###' 			=> admin_url().'support/reply/'.$data['support']->id
					);
					
					$special_vars_user 		= array(
							'###SITELINK###' 		=> front_url(),
							'###SITENAME###' 		=> $site_common['site_settings']->site_name,
							'###USERNAME###' 		=> $username,
							'###MESSAGE###'  		=> "<span style='color: #500050;'>".$message . "</span><br>"
					);

					// echo $adminmail.'--'.$usermail;die;

					$this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars);
					$this->email_model->sendMail($usermail, '', '', $email_template_user, $special_vars_user);

					$this->session->set_flashdata('success', $this->lang->line('Your message successfully sent to our team'));
					front_redirect('support_reply/'.$code, 'refresh');
				} else {
					$this->session->set_flashdata('error', $this->lang->line('Error occur!! Please try again'));
					front_redirect('support_reply/'.$code, 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('Error occur!! Please try again'));
				front_redirect('support_reply/'.$code, 'refresh');
			}
		}
		$data['code'] = $code;
		$data['user_detail'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
        $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['action'] = front_url() . 'support_reply/'.$code;
		$this->load->view('front/user/support_reply', $data);
	}
	function dashboard()
	{ 	 
		$user_id = $this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url('home'));
		}
		
		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link'=>'dashboard'))->row();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['login_history'] = $this->common_model->getTableData('user_activity',array('activity' => 'Login','user_id'=>$user_id),'','','','','','',array('act_id','DESC'))->result();

		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));

		$data['dig_currency'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('sort_order','ASC'))->result();
		/*$data['banners'] = $this->common_model->getTableData('banners',array('status'=>1),'','','','','','', array('id', 'ASC'))->result();*/

		$today = date("Y-m-d");

		$data['banners'] = $this->common_model->getTableData('banners',array('status'=>1,'position'=>'dashboard','expiry_date>='=>$today),'','','','','','', array('id', 'ASC'))->row();

		$data['trans_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id),'','','','','','',array('trans_id','DESC'))->result();
		
		$this->load->view('front/user/dashboard', $data);
	}   
function change_address()
	{
		$user_id=$this->session->userdata('user_id');
		$currency_id = $this->input->post('currency_id');

		$generate_address = $this->update_user_address_by_currency($user_id,$currency_id);
		$coin_address = getAddress($user_id,$currency_id);
		$data['img'] =	"https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$coin_address&choe=UTF-8&chld=L";
		$data['address'] = $coin_address;
		
		$currency_det = $this->common_model->getTableData("currency",array('id'=>$currency_id))->row();
		$data['coin_symbol'] = $currency_det->currency_symbol;
		if($data['coin_symbol']=="INR")
		{
			$format = 2;
		}
		else
		{
			$format = 8;
		}
		if($currency_id==6){
			$data['destination_tag'] = secret($user_id);
		}
		$coin_balance = number_format(getBalance($user_id,$currency_id),$format);
		$data['coin_name'] = ucfirst($currency_det->currency_name);
		$data['coin_balance'] = $coin_balance;
		$data['withdraw_fees'] = $currency_det->withdraw_fees;
		$data['withdraw_limit'] = $currency_det->max_withdraw_limit;
		echo json_encode($data);
    }



    function get_currency_fees(){
    	$user_id=$this->session->userdata('user_id');
    	$currency_id = $this->input->post('currency_id');
        $currency_det = $this->common_model->getTableData("currency",array('currency_symbol'=>$currency_id))->row();
         $maker_fee=$currency_det->maker_fee;
          $data['maker_fee']= $maker_fee;
		echo json_encode($data);

    }







        function change_chain_address()
	{
	    $user_id=$this->session->userdata('user_id');
		$currency_id = $this->input->post('currency_id');
        $data['user_id']=$this->session->userdata('user_id');
		$data['currency_id']=$this->session->userdata('user_id');
        $coin_address = getAddress($user_id,$currency_id);
        $data['img'] =	"https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$coin_address&choe=UTF-8&chld=L";
		$data['address'] = $coin_address;
		$currency_det = $this->common_model->getTableData("currency",array('id'=>$currency_id))->row();
		$data['coin_symbol'] = $currency_det->currency_symbol;
		if($data['coin_symbol']=="INR")
		{
			$format = 2;
		}
		else
		{
			$format = 8;
		}
		if($currency_id==8){
			$data['destination_tag'] = '';
		}
		$coin_balance = number_format(getBalance($user_id,$currency_id),$format);
		$data['coin_name'] = ucfirst($currency_det->currency_name);
		$data['coin_balance'] = $coin_balance;
		$data['withdraw_fees'] = $currency_det->withdraw_fees;
		$data['withdraw_limit'] = $currency_det->max_withdraw_limit;
		$data['test']='test';
		echo json_encode($data); 
    }


    function update_user_address_by_currency($user_id,$currency_id)
    {
    	$coin_address = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1','id'=>$currency_id))->row();
    	if($coin_address){
    	$user_details = $this->common_model->getTableData('crypto_address',array($coin_address->currency_symbol.'_status'=>'0','user_id'=>$user_id),'','','','','','',array('id','DESC'))->row();

		$User_Address = getAddress($user_details->user_id,$coin_address->id);
		// print_r($user_details);die;
	    		if(empty($User_Address) || $User_Address==0)
	    		{
					$parameter = '';
	                if($coin_address->coin_type=="coin")
	                {
	                	if($coin_address->currency_symbol=='ETH')
						{ 
							$parameter='create_eth_account';
							$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account',getUserEmail($user_details->user_id));

							if(!empty($Get_First_address) || $Get_First_address!=0)
							{

								updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
								// echo $coin_address->currency_symbol.' Success1 <br/>';
							}
							else{
								$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);
								if($Get_First_address){
									updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
									// echo $coin_address->currency_symbol.' Success2 <br/>';
								}
							}
						}
						elseif($coin_address->currency_symbol=='BNB')
						{
							$parameter='create_eth_account';

							$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account',getUserEmail($user_details->user_id));
							if(!empty($Get_First_address) || $Get_First_address!=0)
							{
								updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
							}
							else{
								$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);
								if($Get_First_address){
									updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
								}
							}
						}
						elseif($coin_address->currency_symbol=='TRX')
						{
							$parameter='create_tron_account';

							$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_tron_account',$user_details->user_id);

							$tron_private_key = $Get_First_address['privateKey'];
							$tron_public_key = $Get_First_address['publicKey'];
							$tron_address = $Get_First_address['address']['base58'];
							$tron_hex = $Get_First_address['address']['hex'];
							if(!empty($Get_First_address) || $Get_First_address!=0)
							{
								updatetronAddress($user_details->user_id,$coin_address->id,$tron_address,$tron_hex,$tron_private_key,$tron_public_key);
							}
							else{
								$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);
								if($Get_First_address){
									updatetronAddress($user_details->user_id,$coin_address->id,$tron_address,$tron_hex);
								}
							}
						}
						else
						{
							$parameter='getnewaddress';
							$Get_First_address1 = $this->local_model->access_wallet($coin_address->id,'getnewaddress',getUserEmail($user_details->user_id));

							// echo "<pre>";print_r($Get_First_address1);
							
							if(!empty($Get_First_address1) || $Get_First_address1!=0){

								if($coin_address->currency_symbol=='XRP'){
									// echo "Success<br/>";

								$Get_First_address = $Get_First_address1->address;
                                $Get_First_secret  = $Get_First_address1->secret;
                                $Get_First_tag = $Get_First_address1->tag;

                                updaterippleSecret($user_details->user_id,$coin_address->id,$Get_First_secret);
                                // echo "Success2<br/>";
                                updaterippletag($user_details->user_id,$coin_address->id,$Get_First_tag);
                                // echo "Success3<br/>";
								}
								else{
									$Get_First_address = $Get_First_address1;
								}
								updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
							}
							else{ 
								if($Get_First_address1){
									$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);

									updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
									// echo $coin_address->currency_symbol.' Success2 <br/>';
								}
							}
						}
		            }
		            else
		            {

		            	// echo "test";

		            	if($coin_address->crypto_type=='eth'){

		            		// echo "test2";
		            	$eth_id = $this->common_model->getTableData('currency',array('currency_symbol'=>'ETH'))->row('id');
						 $eth_address = getAddress($user_details->user_id,$eth_id);

						 // echo  $eth_address;
					}

					updateAddress($user_details->user_id,$coin_address->id,$eth_address);
		  
		            }
				}



    	}
	
    } 




    function update_user_address()
    {

    	exit();
    	$Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result();
		foreach($Fetch_coin_list as $coin_address)
		{
    		$userdetails = $this->common_model->getTableData('crypto_address',array($coin_address->currency_symbol.'_status'=>'0'),'','','','','','',array('id','DESC'))->result();
    		// echo "<pre>";print_r($userdetails);
	    	foreach($userdetails as $user_details) 
	    	{
	    		$User_Address = getAddress($user_details->user_id,$coin_address->id);
	    		if(empty($User_Address) || $User_Address==0)
	    		{
					$parameter = '';
	                if($coin_address->coin_type=="coin")
	                {
	                	if($coin_address->currency_symbol=='ETH')
						{ 
							$parameter='create_eth_account';
							$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account',getUserEmail($user_details->user_id));

							if(!empty($Get_First_address) || $Get_First_address!=0)
							{

								updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
								echo $coin_address->currency_symbol.' Success1 <br/>';
							}
							else{
								$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);
								if($Get_First_address){
									updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
									echo $coin_address->currency_symbol.' Success2 <br/>';
								}
							}
						}
						elseif($coin_address->currency_symbol=='BNB')
						{
							$parameter='create_eth_account';

							$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account',getUserEmail($user_details->user_id));
							if(!empty($Get_First_address) || $Get_First_address!=0)
							{
								updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
							}
							else{
								$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);
								if($Get_First_address){
									updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
								}
							}
						}
						elseif($coin_address->currency_symbol=='TRX')
						{
							$parameter='create_tron_account';

							$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_tron_account',$user_details->user_id);

							$tron_private_key = $Get_First_address['privateKey'];
							$tron_public_key = $Get_First_address['publicKey'];
							$tron_address = $Get_First_address['address']['base58'];
							$tron_hex = $Get_First_address['address']['hex'];
							if(!empty($Get_First_address) || $Get_First_address!=0)
							{
								updatetronAddress($user_details->user_id,$coin_address->id,$tron_address,$tron_hex,$tron_private_key,$tron_public_key);
							}
							else{
								$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);
								if($Get_First_address){
									updatetronAddress($user_details->user_id,$coin_address->id,$tron_address,$tron_hex);
								}
							}
						}
						else
						{
							$parameter='getnewaddress';
							$Get_First_address1 = $this->local_model->access_wallet($coin_address->id,'getnewaddress',getUserEmail($user_details->user_id));

							// echo "<pre>";print_r($Get_First_address1);
							
							if(!empty($Get_First_address1) || $Get_First_address1!=0){

								if($coin_address->currency_symbol=='XRP'){
									echo "Success<br/>";

								$Get_First_address = $Get_First_address1->address;
                                $Get_First_secret  = $Get_First_address1->secret;
                                $Get_First_tag = $Get_First_address1->tag;

                                updaterippleSecret($user_details->user_id,$coin_address->id,$Get_First_secret);
                                echo "Success2<br/>";
                                updaterippletag($user_details->user_id,$coin_address->id,$Get_First_tag);
                                echo "Success3<br/>";
								}
								else{
									$Get_First_address = $Get_First_address1;
								}
								// echo "<pre>";print_r($Get_First_address);die;
								updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
							}
							else{ 
								if($Get_First_address1){
									$Get_First_address = $this->common_model->update_address_again($user_details->user_id,$coin_address->id,$parameter);

									updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
									echo $coin_address->currency_symbol.' Success2 <br/>';
								}
							}
						}
		            }
		            else
		            { 		
		            	if($coin_address->crypto_type=='eth'){
		            	$eth_id = $this->common_model->getTableData('currency',array('currency_symbol'=>'ETH'))->row('id');
						$eth_address = getAddress($user_details->user_id,$eth_id);
					}

					updateAddress($user_details->user_id,$coin_address->id,$eth_address);


		  
		            }
				}
			}
		}		
    } 
     function get_user_list_coin($curr_id,$crypto_type)
	{



	
		$currency=$this->common_model->getTableData('currency',array('status'=>1, 'type'=>'digital','id'=>$curr_id),'','','','','',1)->row();
		$curr_symbol = $currency->currency_symbol;
    $selectFields='US.id as id,CA.address as address,HI.bidex_type as bidex_type,US.bidex_email as email';
    $where=array('US.verified'=>1,$curr_symbol.'_status'=>1);
  // $where=array('US.verified'=>1,$curr_symbol.'_status'=>1,'US.id'=>'110');
  //$where=array('US.verified'=>1,'US.id'=>1030731);
  $orderBy=array('US.id','asc');
  $joins = array('crypto_address as CA'=>'CA.user_id = US.id','history as HI'=>'HI.user_id = US.id');
  $users = $this->common_model->getJoinedTableData('users as US',$joins,$where,$selectFields,'','','','','',$orderBy)->result();

		$rude = array();

        //Binance Usd


		if($crypto_type == 'bsc' || $crypto_type == 'tron'|| $crypto_type == 'eth') {
			// for eth,trx and bsc
			echo "get_user_list_coin_final bsc tron and eth<br/>";
			echo $crypto_type."<br/>";
              // echo "test-new";
			// print_r($users);

			// exit();

			foreach($users as $user)
			{	
				echo "USER".$user->id."<br/>";
				/*$wallet = unserialize($this->common_model->getTableData('crypto_address',array('user_id'=>$user->id),'address','','','','',1)->row('address'));	
				
				$email = getUserEmail($user->id);*/
        $wallet = unserialize($user->address);



        $email = decryptIt($user->bidex_type).$user->email;

				//$currency=$this->common_model->getTableData('currency',array('status'=>1, 'type'=>'digital','id'=>$curr_id))->result();			

				/*$i = 0;
				foreach($currency as $cu)
				{*/

						$count = strlen($wallet[$currency->id]);
						//echo $count."<br>";

						
						
						if(!empty($wallet[$currency->id]) && $count!=1)
						{
							//echo $wallet[$currency->id]; exit;
							//echo "here";
							/*echo $count."<br>";
							echo "here";
							echo $wallet[$cu->id]."<br>";*/
							//echo $currency->crypto_type_other; exit;

							if($currency->crypto_type_other != '')
							{



								$crypto_other_type_arr = explode('|',$currency->crypto_type_other);
								foreach($crypto_other_type_arr as $val)
								{
									$Wallet_balance = 0;
									if($val == $crypto_type)
									{
                                          

          //                               echo  $currency->coin_type;
          //                               echo "test-new";
          //                               echo $val;
										
										// exit();
								
										if($currency->coin_type=="token" && $val=='tron')
										{
											$spec_address_count = strlen($wallet[4]);
											if($spec_address_count!=1)
											{
												$tron_private = gettronPrivate($user->id);
												$crypto_type_other = array('crypto'=>$val,'tron_private'=>$tron_private);
												$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[4],$crypto_type_other);
												echo "<br/>".$wallet[4]."<br/>".$Wallet_balance." TRXX <br/>";

												echo " Wallet Balance  "; 
													echo "<br>"; 

												if($Wallet_balance>0){



													$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
														'currency_name'=>$currency->currency_name,
														'currency_id'=>$curr_id,
														'address'=>$wallet[4],
														'user_id'=>$user->id,
														'user_email'=>$email);
													array_push($rude, $balance[$user->id]); 
												} 
											}
										} 
										else if($currency->coin_type=="token" && $val=='bsc')
										{


											// echo "test-news";
											// exit();

                                   
											$crypto_type_other = array('crypto'=>$val);
											$spec_address_count = strlen($wallet[3]);
											if($spec_address_count!=1)
											{

												$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[3],$crypto_type_other);
												echo "<br/>".$wallet[3]."<br/>".$Wallet_balance." BSCS  <br/>";

												// if($Wallet_balance>0){
													$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
														'currency_name'=>$currency->currency_name,
														'currency_id'=>$curr_id,
														'address'=>$wallet[3],
														'user_id'=>$user->id,
														'user_email'=>$email);
													array_push($rude, $balance[$user->id]); 
												// }
											}
										}
										else
										{


											
									
										   $crypto_type_other = array('crypto'=>$val);

											$spec_address_count = strlen($wallet[2]);
											if($spec_address_count!=1)
											{
              //                               echo "-------------";
										    // echo "<pre>";

                                            // echo $currency->currency_name;

                                             // echo "<pre>";


                                             // echo $wallet[2];
                                             // echo "<pre>";
                                             //  // print_r($crypto_type_other);
                                             //  echo "-------------";
                                             // exit();


												$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[2],$crypto_type_other);

												// echo "COINSS"; 
												 // print_r($Wallet_balance);
												// exit(); 

                                    


												echo "<br/>Address".$wallet[2]."<br/>".$Wallet_balance." ETHS <br/>";



												// if($Wallet_balance>0){
													$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
														'currency_name'=>$currency->currency_name,
														'currency_id'=>$currency->id,
														'address'=>$wallet[2],
														'user_id'=>$user->id,
														'user_email'=>$email);
													array_push($rude, $balance[$user->id]); 
												// }
											}
										}
									}
								}
								//exit;
							} else {

								// echo "Normal CRYPTO Type";
								// echo "<br/>";
								if($currency->coin_type=="token" && $crypto_type=='tron')
								{

									
									$tron_private = gettronPrivate($user->id);
									$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[$currency->id],$tron_private);
									echo $wallet[$currency->id]."<br/>".$Wallet_balance."<br/>";

									if($Wallet_balance>0){
										$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
											'currency_name'=>$currency->currency_name,
											'currency_id'=>$currency->id,
											'address'=>$wallet[$currency->id],
											'user_id'=>$user->id,
											'user_email'=>$email);
										array_push($rude, $balance[$user->id]); 
									}
								}
								else
								{
									$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[$currency->id]);


                            
									echo $wallet[$currency->id]."<br/>".$Wallet_balance."<br/>";

									if($Wallet_balance>0){
										$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
											'currency_name'=>$currency->currency_name,
											'currency_id'=>$currency->id,
											'address'=>$wallet[$currency->id],
											'user_id'=>$user->id,
											'user_email'=>$email);
										array_push($rude, $balance[$user->id]); 
									}
								}
							}

							//exit;
								
							//echo $Wallet_balance."#".$currency->currency_symbol."<br/>";

							
						}
						/*if($currency->currency_symbol=='XRP'){
							break;
						}*/		
					/*$i++;
				}*/
			}
			//print_r($rude); exit;

        } else {

			// for other
            foreach($users as $user)
			{	
				echo "USER".$user->id."<br/>";
				$wallet = unserialize($this->common_model->getTableData('crypto_address',array('user_id'=>$user->id),'address')->row('address'));

				//echo "<pre>"; print_r($wallet); echo "</pre>";
				
				$email = getUserEmail($user->id);
				$currency=$this->common_model->getTableData('currency',array('status'=>1, 'type'=>'digital','id'=>$curr_id))->result();

				//echo "<pre>"; print_r($currency); echo "</pre>";
				$i = 0;
				foreach($currency as $cu)
				{
						if(($wallet[$cu->id]!='') || ($wallet[$cu->id]!=0))
						{
							$balance[$user->id][$i] = array('currency_symbol'=>$cu->currency_symbol, 
								'currency_name'=>$cu->currency_name,
								'currency_id'=>$cu->id,
								'address'=>$wallet[$cu->id],
								'user_id'=>$user->id,
								'user_email'=>$email);
							array_push($rude, $balance[$user->id][$i]); 
						}		
					$i++;
				}
			}
 

        }

        // echo "<pre>";
        // print_r($rude);
        // echo "<pre>";
		return $rude;	
	}



		public function get_user_with_dep_det($curr_id,$crypto_type)
	{


		// echo $curr_id;

		// echo $crypto_type;

		// echo "---test";

  //        exit();
       

		$users 	= $this->get_user_list_coin($curr_id,$crypto_type);

		$currencydet = $this->common_model->getTableData('currency', array('id'=>$curr_id))->row();

		//$currencydet = $this->common_model->getTableData('currency', array('id'=>$curr_id),'','','','','',1)->row();

		$orders = $this->common_model->getTableData('transactions', array('type'=>'Deposit', 'user_status'=>'Completed','currency_type'=>'crypto','currency_id'=>$curr_id))->result_array();

		$address_list = $transactionIds = array();


		if(count($users)){
			foreach($users as $user){
				if( $user['address'] != '')
				{
					$address_list[(string)$user['address']] = $user;
				}
			}
		}
		
		if(count($orders)){
			foreach($orders as $order){
				if(trim($order['wallet_txid']) != '')
				$transactionIds[$order['wallet_txid']] = $order;
			}
		}
		// echo "CRYPTO Type".$crypto_type;
		// echo "<br/>";
		// echo "USERSSS";
		// print_r($users);
		// echo "ORDERS";
		// print_r($orders);
		// echo "<br/>";
		//print_r($address_list);
		$currency_decimal = $currencydet->currency_decimal;
		if($crypto_type == 'tron' && $currencydet->trx_currency_decimal != '')
		{
			$currency_decimal = $currencydet->trx_currency_decimal;
		} else if($crypto_type == 'bsc' && $currencydet->bsc_currency_decimal != '')
		{
			$currency_decimal = $currencydet->bsc_currency_decimal;
		}
		
		return array('address_list'=>$address_list,'transactionIds'=>$transactionIds,'currency_decimal'=>$currency_decimal);
	

	}
	// public function get_user_with_dep_det($curr_id)
	// {
	// 	$users 	= $this->get_user_list_coin($curr_id);

	// 	//echo "<pre>";print_r($users); exit;

	// 	$currencydet = $this->common_model->getTableData('currency', array('id'=>$curr_id))->row();

	// 	$orders = $this->common_model->getTableData('transactions', array('type'=>'Deposit', 'user_status'=>'Completed','currency_type'=>'crypto','currency_id'=>$curr_id))->result_array();
	// 	$address_list = $transactionIds = array();
	// 	//collect all users wallet address list
	// 	if(count($users)){
	// 		foreach($users as $user){
	// 			if( $user['address'] != '')
	// 			{
	// 				$address_list[(string)$user['address']] = $user;
	// 			}
	// 		}
	// 	}
		
	// 	if(count($orders)){
	// 		foreach($orders as $order){
	// 			if(trim($order['wallet_txid']) != '')
	// 			$transactionIds[$order['wallet_txid']] = $order;
	// 		}
	// 	}

	// 	return array('address_list'=>$address_list,'transactionIds'=>$transactionIds,'currency_decimal'=>$currencydet->currency_decimal);
	// }

	public function update_crypto_deposits($coin='BTC') // cronjob for deposit
	{

		// Modified by Ram Nivas
		// Modified this method to accomodate dynamic USDT deposits(erc20,trc20 and beb20) for single token
		// modified in get_user_with_dep_det method with crypto_type_other field

		//$currencies = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','')->row();
		$currencies   =  $this->db->query("select * from bidex_currency where currency_symbol='$coin' AND status = 1")->result(); // get user addresses
		//  echo "<pre>";
		// print_r($currencies); exit;

		if(count($currencies) > 0)
		{
			echo "Process begins<br/>";
			foreach($currencies as $curr)
			{
				echo "<pre>";
				echo $curr->currency_name;
				echo "<br/>";
				// dynamic call for currencies
				// echo "<pre>";
				// print_r($curr); exit;
				
				$crypto_type = $curr->crypto_type_other;
				if($crypto_type != '')
				{
					echo $crypto_type;
					echo "<br/>";
					// ERC, TRX and BSC Tokens
					$crypto_type_arr = explode("|",$crypto_type);
					foreach($crypto_type_arr as $val)
					{
						echo "crypto type other<br/>";
						print_r($crypto_type_arr);
						echo "<br/>";
						echo "In That, checking".$val;
						echo "<br/>";
						$crypto_type = $curr->crypto_type_other;
						$this->crypto_deposit($curr,$val);
					}

				} else {
					// Other coin
					$crypto_type = $curr->crypto_type;
					$this->crypto_deposit($curr,$crypto_type);

				}

			}
		}
		
	}

	public function crypto_deposit($curr,$crypto_type)
	{

  //        echo "--------";
	 //    print_r($curr);
		// echo "-------";

		// echo $crypto_type;

		// exit();




		$curr_id = $curr->id;
		$coin_name =  $curr->deposit_currency;
		$curr_symbol = $curr->currency_symbol;
		$coin_type = $curr->coin_type;
		$Deposit_Fees_type = $curr->deposit_fees_type;
		$Deposit_Fees = $curr->deposit_fees;
		$Deposit_Fees_Update = 0;
        $coin_name1 =  $this->common_model->getTableData('currency',array('deposit_currency'=>$coin_name),'','','','','',1)->row('currency_name');


		// echo $this->db->last_query();


         // print_r($coin_name1);

         // exit();


		//Db Call based on coin - retrieve
			// crypto_type_other -


		// echo $curr_id.'<br>';
		// echo $curr_symbol.'<br>';
		// echo $coin_type.'<br>';
		// echo $crypto_type.'<br>';
		// exit;



		$user_trans_res   = $this->get_user_with_dep_det($curr_id,$crypto_type);


		// echo "<pre>";
		// print_r($user_trans_res);
		// echo "<pre>";
		// exit(); 
 

		$address_list     = $user_trans_res['address_list'];
		$transactionIds   = $user_trans_res['transactionIds'];
		$tot_transactions = array();

		//$valid_server = $this->local_model->get_valid_server();
		$valid_server=1;

		/*$coin_type = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name1),'','','','','',1)->row('coin_type');

		$crypto_type = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name1))->row('crypto_type');*/
		
	
		if($valid_server)
		{

   //         	echo $coin_type;

			// exit();


			if($coin_type=="coin")
			{
			
			switch ($coin_name) 
			{
				case 'Bitcoin':
					$transactions   = $this->local_model->get_transactions('Bitcoin');
					break;

				case 'BNB':
					$transactions 	 = $this->local_model->get_transactions('BNB',$user_trans_res);
					break;

				case 'Tron':
					$transactions 	 = $this->local_model->get_transactions('Tron',$user_trans_res);
					break;

				case 'Ethereum':
					$transactions 	 = $this->local_model->get_transactions('Ethereum',$user_trans_res);
					break;

				case 'Ripple':
					$transactions   = $this->local_model->get_transactions('Ripple',$user_trans_res);
					break;

				case 'Doge':
					$transactions   = $this->local_model->get_transactions('Doge');
					break;

				case 'Litecoin':
					$transactions   = $this->local_model->get_transactions('Litecoin');
					break;

				case 'Dash':
					$transactions   = $this->local_model->get_transactions('Dash');
					break;

				case 'Monero':
					$transactions   = $this->local_model->get_transactions('Monero');
					break;

                case 'BitcoinCash':
				   $transactions   = $this->local_model->get_transactions('BitcoinCash');
					break;			
				
				default:
					show_error('No directory access');
					break;
			}
		}
		else
		{ 

		


			// Token Logic        

			 
				 $transactions 	 = $this->local_model->get_transactions($coin_name1,$user_trans_res,$crypto_type);


				 
     

// echo "test_new";

// exit(); 



		}





         // print_r($transactions);

         // exit();
    	// echo $coin_name1;
			// echo "<pre>mm"; print_r($transactions); echo "</pre>"; exit();
			//exit(); 

			if(count($transactions)>0 || $transactions!='')
			{

				$i=0;
				foreach ($transactions as $key => $value) 
				{
					/*26-6-18*/
					$i++;
					$index = $value['address'].'-'.$value['confirmations'].'-'.$i;
					/*26-6-18*/
					
					$tot_transactions[$index] = $value;
				}
			}
			// print_r($tot_transactions); exit;


			if(!empty($tot_transactions) && count($tot_transactions)>0)
			{
				// echo "<pre>";
				// print_r($tot_transactions);

				// echo "test_new";
				// exit();
				
				$a=0;
				foreach($tot_transactions as $row) 
				{
					$a++;
					// $account       = $row['account'];		
					$address       = $row['address'];
					$confirmations = $row['confirmations'];	
					//$txid          = $row['txid'];
					$txid          = $row['txid'].'#'.$row['time'];

					$time_st       = $row['time'];			
					$amount1        = $row['amount'];
					echo "Deposit Type".$Deposit_Fees_type;
					echo "<br/>";
					echo "Deposit fees".$Deposit_Fees;
					echo "<br/>";



					if(isset($Deposit_Fees_type) && !empty($Deposit_Fees_type) && $Deposit_Fees!=0){

						if($Deposit_Fees_type=='Percent'){
							$Deposit_Fee = ($amount1 * ($Deposit_Fees/100));
							$amount = $amount1 - $Deposit_Fee;
							$Deposit_Fees_Update = $Deposit_Fee;
						}
						else{
							$amount = $amount1 - $Deposit_Fees;
							$Deposit_Fees_Update = $Deposit_Fees;
						}

					}else{
						$amount = $amount1;
						$Deposit_Fees_Update = 0;
					}
					echo "Amount".$amount;
					echo "<br/>";
					echo "Deposit Fees Update".$Deposit_Fees_Update;
					echo "<br/>";
					$category      = $row['category'];		
					$blockhash 	   = (isset($row['blockhash']))?$row['blockhash']:'';
					$ind_val 	   = $address.'-'.$confirmations.'-'.$a;
					$from_address = $row['from'];
					
					
						$admin_address = getadminAddress(1,$curr_symbol);
					
				//echo $admin_address."<br/>";
					// echo $row['blockhash'];
					// echo "<br/>";
					// echo $txid; 
					// echo "<br/>";
					// echo $curr_id;
					// echo "<br/>";
					
			
					$counts_tx = $this->db->query('select * from bidex_transactions where information="'.$row['blockhash'].'" and wallet_txid="'.$txid.'" limit 1')->row();
					/*echo count($counts_tx);
					echo "<br>";*/

					// echo $counts_tx;
					//exit;
					
					// echo $row['blockhash'];
					// echo "<br>";
					// echo $counts_tx;
					// echo "<br>";


					if($category == 'receive' && $confirmations > 0 && count($counts_tx) == 0 && $amount>0)
					{
	

						if(isset($address_list[$address]))
						{
							if($coin_name=='Ripple'){

							$user_id = $row['user_id'];
						}
						else{
							
							$user_id   = $address_list[$address]['user_id'];
						}
							
							$coin_name = $address_list[$address]['currency_name'];
							$cur_sym   = $address_list[$address]['currency_symbol'];
							$cur_ids   = $address_list[$address]['currency_id'];
							$email 	   = $address_list[$address]['user_email'];
						}
						else
						{
							foreach ($address_list as $key => $value) 
							{
							
								if(($value['currency_symbol'] == 'ETH') && strtolower($address) ==  strtolower($value['address']))	
								{
									$user_id   = $value['user_id'];
									$coin_name = "else ".$value['currency_name'];
									$cur_sym   = $value['currency_symbol'];
									$cur_ids   = $value['currency_id'];
									$email 	   = $value['user_email'];
								}
							}
						}
						

						if($coin_type=="coin")
						{

							// echo "test";
							// exit();
							if(trim($from_address)!= trim($admin_address))
							{
								if($coin_name=='Tron'){
									$TRX_hexaddress = admin_trx_hex('1');
									if(trim($from_address)==trim(strtolower($TRX_hexaddress))){
										$user_id='41';
									}
									echo $from_address." =#= Pila".trim(strtolower($TRX_hexaddress))."<br/>";
								}
								
								if(isset($user_id) && !empty($user_id)){
									if(($coin_name=='Tron' && ($amount==0.000001 || $amount==0.000007 || $amount==2 || $amount==5 || $amount==9 || $amount==10 || $amount==0.000003 || $amount==0.000045))){
										echo "TRON Min Amount 0.000001 and 2 Not Inserting<br/>";
									}
									else{
									 $balance = getBalance($user_id,$cur_ids,'crypto'); // get user bal
                                    
									$finalbalance = $balance+$amount; // bal + dep amount
									//echo "Final".$finalbalance;
									$updatebalance = updateBalance($user_id,$cur_ids,$finalbalance,'crypto'); // Update balance

									// Add to reserve amount
									$reserve_amount = getcryptocurrencydetail($cur_ids);
									$final_reserve_amount = (float)$amount + (float)$reserve_amount->reserve_Amount;
									$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $cur_ids);

									// insert the data for deposit details
									$dep_data = array(
										'user_id'    		=> $user_id,
										'currency_id'   	=> $cur_ids,
										'type'       		=> "Deposit",
										'currency_type'		=> "crypto",
										'description'		=> $coin_name." Payment",
										'amount'     		=> $amount,
										'transfer_amount'	=> $amount,
										'fee'				=> $Deposit_Fees_Update,
										'information'		=> $blockhash,
										'wallet_txid'       => $txid,
										'crypto_address'	=> $address,
										'status'     		=> "Completed",
										'datetime' 			=> $time_st,
										'user_status'		=> "Completed",
										'crypto_type'       => $crypto_type,
										'transaction_id'	=> rand(100000000,10000000000),
										'datetime' 		=> (empty($txid))?$time_st:time()
									);
									//print_r($dep_data); exit;
									$ins_id = $this->common_model->insertTableData('transactions',$dep_data);
									$prefix = get_prefix();
									$userr = getUserDetails($user_id);
									$usernames = $prefix.'username';
									$username = $userr->$usernames;
									$sitename = getSiteSettings('site_name');
									// check to see if we are creating the user
									$site_common      =   site_common();
									$email_template = 'Deposit_Complete';
									$special_vars	=	array(
										'###SITENAME###'  =>  $sitename,
										'###USERNAME###'    => $username,
										'###AMOUNT###' 	  	=> $amount,
										'###CURRENCY###'    => $cur_sym,
										'###HASH###'        => $blockhash,
										'###TIME###'        => date('Y-m-d H:i:s',$time_st),
										'###TRANSID###' 	=> $txid,
									);
									
									$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
									
									
									}
								
								}
							}
						}
						else
						{
							if(isset($user_id) && !empty($user_id)){
								    $balance = getBalance($user_id,$cur_ids,'crypto'); // get user bal
                                	$finalbalance = $balance+$amount; // bal + dep amount
									//echo "Final".$finalbalance;
									$updatebalance = updateBalance($user_id,$cur_ids,$finalbalance,'crypto'); // Update balance

									// Add to reserve amount
									$reserve_amount = getcryptocurrencydetail($cur_ids);
									$final_reserve_amount = (float)$amount + (float)$reserve_amount->reserve_Amount;
									$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $cur_ids);

									// insert the data for deposit details
									$dep_data = array(
										'user_id'    		=> $user_id,
										'currency_id'   	=> $cur_ids,
										'type'       		=> "Deposit",
										'currency_type'		=> "crypto",
										'description'		=> $coin_name." Payment",
										'amount'     		=> $amount,
										'transfer_amount'	=> $amount,
										'information'		=> $blockhash,
										'wallet_txid'       => $txid,
										'crypto_address'	=> $address,
										'status'     		=> "Completed",
										'datetime' 			=> $time_st,
										'user_status'		=> "Completed",
										'crypto_type'       => $crypto_type,
										'transaction_id'	=> rand(100000000,10000000000),
										'datetime' 		=> (empty($txid))?$time_st:time()
									);
									// echo "DEP DATA2";
									// echo $address; echo "<br/>";
									// print_r($dep_data);
									$ins_id = $this->common_model->insertTableData('transactions',$dep_data);

									$prefix = get_prefix();
									$userr = getUserDetails($user_id);
									$usernames = $prefix.'username';
									$username = $userr->$usernames;
									$sitename = getSiteSettings('site_name');
									// check to see if we are creating the user
									$site_common      =   site_common();
									$email_template = 'Deposit_Complete';
									$special_vars	=	array(
										'###SITENAME###'  =>  $sitename,
										'###USERNAME###'    => $username,
										'###AMOUNT###' 	  	=> $amount,
										'###CURRENCY###'    => $cur_sym,
										'###HASH###'        => $blockhash,
										'###TIME###'        => date('Y-m-d H:i:s',$time_st),
										'###TRANSID###' 	=> $txid,
									);
									
									$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
								}

						}
						
						
					}
					
					if($crypto_type=='eth' || $crypto_type=='bsc' || $crypto_type=='tron'){
									
						//$this->move_to_admin_wallet($coin_name1,$crypto_type);
					}
					
				/*}*/
				}
				/*26-6-18*/
				$result = array('status'=>'success','message'=>'update deposit successed');
				/*26-6-18*/
			}
			else
			{
				/*26-6-18*/
				$result = array('status'=>'success','message'=>'update failed1');
			}
		}
		else
		{
			$result = array('status'=>'error','message'=>'update failed');
		}
		echo json_encode($result);

	}







	// cronjob for deposit
	// public function update_crypto_deposits($coin_symbol) // Ethereum
	// {



	// 	// error_reporting(E_ALL);

	// 	$curr = 	$this->common_model->getTableData('currency',array('currency_symbol'=>$coin_symbol))->row();
	//     $coin_name = $curr->currency_name;
	// 	$coin_name1 = $coin_name;
 //       $user_trans_res   = $this->get_user_with_dep_det($curr->id);

  
	// 	$address_list     = $user_trans_res['address_list'];
	// 	$transactionIds   = $user_trans_res['transactionIds'];
	// 	$tot_transactions = array();


	// 	$curr_symbol = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('currency_symbol');

	// 	$valid_server =1;
	// 	$coin_type = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('coin_type');
	// 	$coinDetails = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('admin_move');

	// 	// echo "<pre>Transactions"; print_r($user_trans_res);

	// 	if($valid_server)
	// 	{
	// 		if($coin_type=="coin") // COIN PROCESS
	// 		{ 


	// 			switch ($coin_name) 
	// 			{
	// 				case 'Bitcoin':
	// 					$transactions   = $this->local_model->get_transactions('Bitcoin');
	// 					break;
	// 				case 'BitcoinCash':
	// 					$transactions   = $this->local_model->get_transactions('BitcoinCash');
	// 					break;
	// 				case 'Ripple':
	// 					$transactions   = $this->local_model->get_transactions('Ripple',$user_trans_res);
	// 					break;
	// 				case 'Ethereum':
	// 					$transactions 	 = $this->local_model->get_transactions('Ethereum',$user_trans_res);
	// 					break;	
	// 				case 'BNB':
	// 				$transactions 	 = $this->local_model->get_transactions('BNB',$user_trans_res);
	// 				break;
	// 			       default:
	// 					show_error('No directory access');
	// 					break;
	// 		    }
	// 	   }
	// 	   else // TOKEN PROCESS
 //           { 
 //           		$transactions 	 = $this->local_model->get_transactions($coin_name,$user_trans_res);
 //           }

	// 		// echo "<pre>Transactions"; print_r($transactions); echo "</pre>";			
	// 		if(count($transactions)>0 || $transactions!='')
	// 		{
	// 			$i=0;
	// 			foreach ($transactions as $key => $value) 
	// 			{
	// 				/*26-6-18*/
	// 				$i++;
	// 				$index = $value['address'].'-'.$value['confirmations'].'-'.$i;
	// 				/*26-6-18*/
					
	// 				$tot_transactions[$index] = $value;
	// 			}
	// 		}
			
	// 		if(!empty($tot_transactions) && count($tot_transactions)>0)
	// 		{
	// 			$a=0;
	// 			foreach ($tot_transactions as $row) 
	// 			{
	// 				$a++;$from_address='';
	// 				// $account       = $row['account'];		
	// 				$address       = $row['address'];
	// 				$confirmations = $row['confirmations'];	
	// 				 //$txid          = $row['txid'];
	// 				$txid          = $row['txid'].'#'.$row['time'];
	// 				//$time_st       = $row['time'];
	// 				$time_st       = date("Y-m-d h:i:s",$row['time']);			
	// 				$amount        = $row['amount'];
	// 				$category      = $row['category'];		
	// 				$blockhash 	   = (isset($row['blockhash']))?$row['blockhash']:'';
	// 				$ind_val 	   = $address.'-'.$confirmations.'-'.$a;
	// 				if($coin_name1=='Ethereum' || $coin_name1=='Tether' || $coin_name1=='eLira'){
	// 				$from_address = $row['from'];
	// 			}

	// 			else{
	// 				$from_address = '';
	// 			}

	// 				$admin_address = getadminAddress(1,$curr_symbol);


	// 				$counts_tx = $this->db->query('select * from bidex_transactions where information="'.$row['blockhash'].'" and wallet_txid="'.$txid.'"')->num_rows();

					
	// 				if( $category == 'receive' && $confirmations > 0 && $counts_tx == 0)
	// 				{	

	// 					if(isset($address_list[$address]))
	// 					{
	// 						if($coin_name1!='Ripple'){
	// 						$user_id   = $address_list[$address]['user_id'];
	// 					}
	// 					else{
	// 						$user_id = $row['user_id'];
							
	// 					}
	// 						$coin_name = "if".$address_list[$address]['currency_name'];
	// 						$cur_sym   = $address_list[$address]['currency_symbol'];
	// 						$cur_ids   = $address_list[$address]['currency_id'];
	// 						$email 	   = $address_list[$address]['user_email'];
	// 					}
	// 					else
	// 					{
	// 						foreach ($address_list as $key => $value) 
	// 						{							
	// 							if(($value['currency_symbol'] == 'ETH') && strtolower($address) ==  strtolower($value['address']))	
	// 							{
	// 								$user_id   = $value['user_id'];
	// 								$coin_name = "else".$value['currency_name'];
	// 								$cur_sym   = $value['currency_symbol'];
	// 								$cur_ids   = $value['currency_id'];
	// 								$email 	   = $value['user_email'];
	// 							}
	// 						}
	// 					}

	// 					if(trim($from_address)!=trim($admin_address))
	// 					{ 

	// 					    if(isset($user_id) && !empty($user_id))
	// 						{
	// 							$balance = getBalance($user_id,$cur_ids,'crypto'); // get user bal
	// 							$finalbalance = $balance+$amount; // bal + dep amount
	// 							//echo "Final".$finalbalance;
	// 							$updatebalance = updateBalance($user_id,$cur_ids,$finalbalance,'crypto'); // Update balance

	// 							// Add to reserve amount
	// 							$reserve_amount = getcryptocurrencydetail($cur_ids);
	// 							$final_reserve_amount = (float)$amount + (float)$reserve_amount->reserve_Amount;
	// 							$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $cur_ids);

	// 							// insert the data for deposit details
	// 							$dep_data = array(
	// 								'user_id'    		=> $user_id,
	// 								'currency_id'   	=> $cur_ids,
	// 								'type'       		=> "Deposit",
	// 								'currency_type'		=> "crypto",
	// 								'description'		=> $coin_name1." Payment",
	// 								'amount'     		=> $amount,
	// 								'transfer_amount'	=> $amount,
	// 								'information'		=> $blockhash,
	// 								'wallet_txid'       => $txid,
	// 								'crypto_address'	=> $address,
	// 								'status'     		=> "Completed",
	// 								'datetime' 			=> $time_st,
	// 								'user_status'		=> "Completed",
	// 								'transaction_id'	=> rand(100000000,10000000000),
	// 								'datetime' 		=> (empty($txid))?$time_st:time()
	// 							);
	// 							$ins_id = $this->common_model->insertTableData('transactions',$dep_data);

	// 							$prefix = get_prefix();
	// 							$userr = getUserDetails($user_id);
	// 							$usernames = $prefix.'username';
	// 							$username = $userr->$usernames;
	// 							$sitename = getSiteSettings('english_site_name');
	// 							// check to see if we are creating the user
	// 							$site_common      =   site_common();
	// 					       	$email_template = 'Deposit_Complete';
	// 					       	// echo $time_st.'----';
	// 					       	// date('Y-m-d H:i:s',$time_st);
	// 					       	// die;
	// 							$special_vars	=	array(
	// 								'###SITENAME###'  =>  $sitename,
	// 								'###USERNAME###'    => $username,
	// 								'###AMOUNT###' 	  	=> $amount,
	// 								'###CURRENCY###'    => $cur_sym,
	// 								'###HASH###'        => $blockhash,
	// 								'###TIME###'        => $time_st,
	// 								'###TRANSID###' 	=> $txid,
	// 							);
						       
	// 					       	if($ins_id !="" && $coinDetails==1 ) // ETH and Token
	// 							{

	// 								$this->transfer_to_admin_wallet($coin_name1);
	// 							}
	// 					    }
	// 					} 
	// 					elseif($from_address == $admin_address)
	// 					{
	// 					    if($coinDetails==1)
	// 						{
	// 							$this->transfer_to_admin_wallet($coin_name1);
	// 						}
	// 					}   
	// 				}
	// 				else
	// 				{
	// 					//echo"false";
	// 				}
	// 			/*}*/
	// 			}
	// 			/*26-6-18*/
	// 			$result = array('status'=>'success','message'=>'update deposit successed');
	// 			/*26-6-18*/
	// 		}
	// 		else
	// 		{
	// 			/*26-6-18*/
	// 			$result = array('status'=>'success','message'=>'update failed1');
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$result = array('status'=>'error','message'=>'update failed');
	// 	}
	// 	die(json_encode($result));
	// }

	public function transfer_to_admin_wallet($coinname)
	{
	    $currency_det    =   $this->db->query("select * from bidex_currency where currency_name = '".$coinname."' limit 1")->row(); 

	    // print_r($currency_det);die;

	    if($currency_det->admin_move==1)
	    {
	    $currency_status = $currency_det->currency_symbol.'_status';
	   //$address_list    =  $this->db->query("select * from ixtokens_crypto_address where ".$currency_status." = '1' ")->result(); 
	   $address_list    =  $this->db->query("select * from bidex_transactions where type = 'Deposit' and status = 'Completed' and currency_id = ".$currency_det->id." and admin_status = 0 and wallet_type = '0'")->result(); 
	    /*echo $this->db->last_query();
	    exit();*/
	    $fetch           =  $this->db->query("select * from bidex_admin_wallet where id='1' limit 1")->row(); 
	    $get_addr        =  json_decode($fetch->addresses,true);
	    $toaddress       =  $get_addr[$currency_det->currency_symbol]; 
        
        $coin_type = $currency_det->coin_type;
        // echo 'Coin decimal--'.$currency_det->currency_decimal;
	    $coin_decimal = coin_decimal($currency_det->currency_decimal);
	    $crypto_type = $currency_det->crypto_type;
	    $min_deposit_limit = $currency_det->move_coin_limit;


	    if($coinname!="")
	    {
	        $i =1;
            if(!empty($address_list)){
	        foreach ($address_list as $key => $value) {
	        	$from='';
	                //$arr       = unserialize($value->address);
	                //$from      = $arr[$currency_det->id];
	        	    $from = $value->crypto_address;
	                $user_id = $value->user_id;
	                $trans_id = $value->trans_id;
	                 $from_address='';$amount=0;
	                 if($coin_type=="token" && $crypto_type=='tron')
	                 {
	                 	$tron_private = gettronPrivate($user_id);
	                 	$amount    = $this->local_model->wallet_balance($coinname,$from,$tron_private);
	                 }
	                 else
	                 {
	                 	
	                 	$amount    = $this->local_model->wallet_balance($coinname,$from);
	                 }

	                 


	                $minamt    = $currency_det->min_withdraw_limit;
	                $from_address = trim($from); 
	                $to = trim($toaddress);
	        
	                if($from_address!='0') {
	                	/*echo "Address - ".$from_address;
	                	echo "Balance - ".$amount;*/
	                if($amount>=$min_deposit_limit) 
	                {
	                	echo $amount."<br/>";
	                	echo "transfer";
	                	
		                if($coin_type=="token")
		                {
							if($crypto_type=='eth')
							{
								$GasLimit = 70000;
		                        $GasPrice = $this->check_ethereum_functions('eth_gasPrice','Ethereum');
		                        //$GasPrice = 100 * 1000000000;
		                        
		                        $amount_send = $amount;
		                        $amount1 = $amount_send * $coin_decimal;

		                        echo "<br/>".$GasPrice."<br/>";

		                        $trans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
							}
							elseif($crypto_type=='bsc')
							{
								$GasLimit = 50000;
		                        //$GasPrice = $this->check_ethereum_functions('eth_gasPrice','BNB');

		                        $GasPrice = 31000000000;

		                        $amount_send = $amount;
		                        $amount1 = $amount_send * $coin_decimal;

		                        $trans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);

		                        /*echo "<pre>";print_r($trans_det);
		                        exit();*/
							}
							else
							{
					            $amount1 = $amount * $coin_decimal;
					            $fee_limit = 5000000;

					            $privateKey = gettronPrivate($user_id);
								//$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),'privateKey'=>$privateKey);

								$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>(float)$amount1,'privateKey'=>$privateKey);

							}
		                	
                            if($crypto_type=='eth')
		                	{
		                		$eth_balance = $this->local_model->wallet_balance("Ethereum",$from_address); // get balance from blockchain
		                		$transfer_currency = "Ethereum";
		                		$check_amount = "0.005";
		                		//$check_amount = "0.01";
		                	}
		                	elseif($crypto_type=='tron')
		                	{
		                		$eth_balance = $this->local_model->wallet_balance("Tron",$from_address); // get balance from blockchain
		                		$transfer_currency = "Tron";
		                		$check_amount = "5";
		                	}
		                	else
		                	{
		                		$eth_balance = $this->local_model->wallet_balance("BNB",$from_address); // get balance from blockchain
		                		$transfer_currency = "BNB";
		                		$check_amount = "0.004";
		                	}

		                	if($eth_balance >= $check_amount)
		                	{
		                		if($crypto_type=='eth' || $crypto_type=='bsc')
		                		{
		                			$txn_count = $this->get_pendingtransaction($from_address,$coinname);
		                		}
		                		else
		                		{
		                			$txn_count = 0;
		                		}
		                		
		                		if($txn_count==0)
		                		{
		                			$send_money_res_token = $this->local_model->make_transfer($coinname,$trans_det); // transfer to admin
                                   if($send_money_res_token !="" || $send_money_res_token !="error")
                                    {
	                                    $update = $this->common_model->updateTableData("transactions",array("admin_move"=>'0',"trans_id"=>$trans_id),array("admin_move"=>'1'));
                                    }
		                		}
		                	}
		                	else
		                	{

		                		if($crypto_type=='eth')
		                		{
		                		$eth_amount = 0.005;
                                $GasLimit1 = 21000;
                                $Gas_calc1 = $this->check_ethereum_functions('eth_gasPrice','Ethereum');
		                        $Gwei1 = $Gas_calc1;
		                        $GasPrice1 = $Gwei1;
		                        $Gas_res1 = $Gas_calc1 / 1000000000;
		                        $Gas_txn1 = $Gas_res1 / 1000000000;
                                $txn_fee = $GasLimit1 * $Gas_txn1;
                                //$send_amount = $eth_amount + $txn_fee;
		                		$eth_amount1 = $eth_amount * 1000000000000000000;
		                        $nonce1 = $this->get_transactioncount($to,$coinname);
		                        $eth_trans = array('from'=>$to,'to'=>$from_address,'value'=>(float)$eth_amount1,'gas'=>(float)$GasLimit1,'gasPrice'=>(float)$GasPrice1);

		                		}
		                		elseif($crypto_type=='bsc')
		                		{
		                		$eth_amount = 0.005;
                                $GasLimit1 = 50000;
                                //$Gas_calc1 = $this->check_ethereum_functions('eth_gasPrice','BNB');
                                $Gas_calc1 = 31000000000;
		                        $Gwei1 = $Gas_calc1;
		                        $GasPrice1 = $Gwei1;
		                        $Gas_res1 = $Gas_calc1 / 1000000000;
		                        $Gas_txn1 = $Gas_res1 / 1000000000;
                                $txn_fee = $GasLimit1 * $Gas_txn1;
                                //$send_amount = $eth_amount + $txn_fee;
		                		$eth_amount1 = $eth_amount * 1000000000000000000;
		                        $nonce1 = $this->get_transactioncount($to,$coinname);
		                        $eth_trans = array('from'=>$to,'to'=>$from_address,'value'=>(float)$eth_amount1,'gas'=>(float)$GasLimit1,'gasPrice'=>(float)$GasPrice1);

		                		}
		                		else
		                		{
		                		
					                $amount1 = 5 * 1000000;
					                $privateKey = getadmintronPrivate(1);
									$eth_trans 		= array('fromAddress'=>$to,'toAddress'=>$from_address,'amount'=>(float)$amount1,"privateKey"=>$privateKey);

		                		}

		                		if($crypto_type=='eth' || $crypto_type=='bsc')
		                		{
		                			$txn_count = $this->get_pendingtransaction($to,$transfer_currency);
		                		}
		                		else
		                		{
		                			$txn_count = 0;
		                		}
                                
                               if($txn_count==0)
                               {
                               	$send_money_res = $this->local_model->make_transfer($transfer_currency,$eth_trans); // admin to user wallet

                               	if($send_money_res !="" || $send_money_res !="")
                               	{
                               		 $tnx_data = array(
		                                        'user_id'=>$value->user_id,
		                                        'address' => $from_address,
		                                        'amount'=>(float)$amount,
		                                        'currency_symbol'=>$currency_det->currency_symbol,
		                                        'status'=>0,
		                                        'created_at'=>date('Y-m-d H:i:s'),
		                                        'txn_id'=>$send_money_res
		                                    );
		                           //$ins = $this->common_model->insertTableData('admin_move_logs',$tnx_data);
                               	}
                               }
                              
		                	}
		                }
		                 else
		                {
							$coin_transfer = '';
							if($crypto_type=='eth')
							{
							$GasLimit = 21000;
	                        $Gas_calc = $this->check_ethereum_functions('eth_gasPrice','Ethereum');
	                        echo "<br/>".$Gas_calc."<br/>";
	                        $Gwei = $Gas_calc;
	                        $GasPrice = $Gwei;
	                        $Gas_res = $Gas_calc / 1000000000;
	                        $Gas_txn = $Gas_res / 1000000000;
	                        $txn_fee = $GasLimit * $Gas_txn;
	                        echo "Transaction Fee".$txn_fee."<br/>";
	                        $amount_send = ($amount - $txn_fee)-0.0005;
	                        echo "Amount Send ".$amount_send."<br/>";

	                        echo "Total Amount ".($txn_fee+$amount_send)."<br/>";
	                        $amount1 = ($amount_send * 1000000000000000000);

	                        echo sprintf("%.40f", $amount1)."<br/>";
	                        $coin_transfer = "Ethereum";
	                        $cointrans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);

	                       /* echo "<pre>";
	                        print_r($cointrans_det);*/
							}
							elseif($crypto_type=='bsc')
							{
							$GasLimit = 50000;
	                        $Gas_calc = $this->check_ethereum_functions('eth_gasPrice','BNB');

	                        $Gas_calc = 31000000000;
	                        $Gwei = $Gas_calc;
	                        $GasPrice = $Gwei;
	                        $Gas_res = $Gas_calc / 1000000000;
	                        $Gas_txn = $Gas_res / 1000000000;
	                        $txn_fee = $GasLimit * $Gas_txn;
	                        $amount_send = $amount - $txn_fee;
	                        $amount1 = $amount_send * 1000000000000000000;
	                        $coin_transfer = "BNB";
	                        $cointrans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
							}
							else
							{
								$from_address = trim($from_address);
								$to = trim($to);	
				                $amount1 = $amount * 1000000;
				                $privateKey = gettronPrivate($user_id);
				                $coin_transfer = "Tron";
								$cointrans_det = array('fromAddress'=>$from_address,'toAddress'=>$to,'amount'=>(float)$amount1,"privateKey"=>$privateKey);
							}
		                	
		                    if($crypto_type=='eth' || $crypto_type=='bsc')
	                		{
	                			$txn_count = $this->get_pendingtransaction($from_address,$coin_transfer);
	                		}
	                		else
	                		{
	                			$txn_count = 0;
	                		}
	                		
                            echo "txn count";
                             echo "<br>";
                            echo $txn_count;
                            echo "<br>";
	                		if($txn_count==0)
	                		{
	                		
                            $send_money_res_coin = $this->local_model->make_transfer($coin_transfer,$cointrans_det); // transfer to admin

                            if($send_money_res_coin !="" || $send_money_res_coin !="")
                           	{
                			$update = $this->common_model->updateTableData("transactions",array("admin_status"=>0,"trans_id"=>$trans_id),array("admin_status"=>'1'));
                		    }
	                			
	                			
	                			
	                		}
	                		

                          
                           
		                	
		                }
		       
                       
		                  
		                    $result = array('status'=>'success','message'=>'update deposit success');
	                   
	                }
	                else
	                {
                      $result = array('status'=>'failed','message'=>'update deposit failed insufficient balance');
	                }

	            }
	            else
	            {
	                $result = array('status'=>'failed','message'=>'invalid address');	
	            }

	        $i++;}
	       }
	       else
	       	{
	       		$result = array("status"=>"failed","message"=>"transactions not found for admin wallet");
	       	}

	    }
	    echo json_encode($result);

	    }

	
	}

	public function transfer_to_admin_wallet1($coinname)
	{
	    $coinname = str_replace("%20"," ",$coinname);
	    $currency_det =   $this->db->query("select * from bidex_currency where currency_name = '".$coinname."' ")->row(); // get currency detail
	    $currency_status = $currency_det->currency_symbol.'_status';
	    $address_list   =  $this->db->query("select * from bidex_crypto_address where ".$currency_status." = 1")->result(); // get user addresses
	    $fetch          =  $this->db->query("select * from bidex_admin_wallet where id='1'")->row(); // get admin wallet
	    $get_addr       =  json_decode($fetch->addresses,true);
	    $toaddress      =  $get_addr[$currency_det->currency_symbol]; // get admin address

	    $min_deposit_limit = $currency_det->move_coin_limit;

	    if($coinname!="")
	    {
	        $i =1;

	        foreach ($address_list as $key => $value) {

	                $arr       = unserialize($value->address);
	                $from      = $arr[$currency_det->id];
	                echo 'from'.$from.'<br>';

	                $amount    = $this->local_model->wallet_balance($coinname,$from); // get balance 
					echo 'amount'.$amount.'<br>';
	                $minamt       = $currency_det->min_withdraw_limit; // get minimum withdraw limit
	                $from_address = trim($from); // get user address- from address
	                $to = trim($toaddress); // get admin address - to address
                   
                   echo 'to'.$to.'<br>';

	                if($from_address!='0') { // check user address to be valid

	                if($amount>$min_deposit_limit) // check transfer amount with min withdraw limit and to be valid
	                {
	                    switch ($coinname) 
	                    {
	                        case 'Ethereum': // get transcation details for eth
	                        $GasLimit = 21000;
	                        $Gas_calc = $this->check_ethereum_functions('eth_gasPrice');
	                        $Gwei = $Gas_calc;
	                        $GasPrice = $Gwei;
	                        $Gas_res = $Gas_calc / 1000000000;
	                        $Gas_txn = $Gas_res / 1000000000;
	                        $txn_fee = $GasLimit * $Gas_txn;
	                        $amount_send = $amount - $txn_fee;
	                        $amounts = $amount_send * 1000000000000000000;
	                        $amount1 = rtrim(sprintf("%u", $amounts), ".");
	                        $nonce = $this->get_transactioncount($from_address);
	                        $trans_det      = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice,'nonce'=>$nonce);
	                        break;

	                        case 'Tether': // get transcation details for usdt
	                        $GasLimit = 50000;
	                        $Gas_calc = $this->check_ethereum_functions('eth_gasPrice');
	                        $Gwei = $Gas_calc;
	                        $GasPrice = $Gwei;
	                        $Gas_res = $Gas_calc / 1000000000;
	                        $Gas_txn = $Gas_res / 1000000000;
	                        $txn_fee = $GasLimit * $Gas_txn;
	                        $amount_send = $amount;
	                        $amounts = $amount_send * 1000000;
	                        $amount1 = rtrim(sprintf("%u", $amounts), ".");
	                        $nonce = $this->get_transactioncount($from_address);
	                        $contract_address = $currency_det->contract_address;
	                        $trans_det      = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice,'nonce'=>$nonce);
	                        break;

	                        
	                    } 

	                    //print_r($trans_det); exit;

                        if($coinname=="Tether") // check eth balance for usdt transfer
		                {
		                	$eth_balance = $this->local_model->wallet_balance("Ethereum",$from_address); // get balance from blockchain
		                	//$eth_balance = getBalance($value->user_id,3); // get balance from db
		                	if($eth_balance >= "0.001")
		                	{
                                $send_money_res = $this->local_model->make_transfer($coinname,$trans_det); // transfer to admin
		                		//$send_money_res = "test";
		                	}
		                	else
		                	{
                                $eth_amount = 0.002;
                                $GasLimit1 = 50000;
                                $Gas_calc1 = $this->check_ethereum_functions('eth_gasPrice');
		                        $Gwei1 = $Gas_calc1;
		                        $GasPrice1 = $Gwei1;
		                        $Gas_res1 = $Gas_calc1 / 1000000000;
		                        $Gas_txn1 = $Gas_res1 / 1000000000;
                                $txn_fee = $GasLimit1 * $Gas_txn1;
                                $send_amount = $eth_amount + $txn_fee;
		                		$eth_amounts = $send_amount * 1000000000000000000;
		                        $eth_amount1 =  rtrim(sprintf("%u", $eth_amounts), ".");
		                        $nonce1 = $this->get_transactioncount($to);
		                        $eth_trans = array('from'=>$to,'to'=>$from_address,'value'=>(float)$eth_amount1,'gas'=>(float)$GasLimit1,'gasPrice'=>(float)$GasPrice1,'nonce'=>$nonce1);
                                $send_money_res1 = $this->local_model->make_transfer("Ethereum",$eth_trans); 
                               /* updateBalance($value->user_id,2,$eth_amount);
                                $admin_ethbalance = getadminBalance(1,2); // get admin eth balance
				                $eth_bal = $admin_ethbalance - $eth_amount; // calculate remaining eth amount in admin wallet
				                updateadminBalance(1,2,$eth_bal); // update eth balance in admin wallet*/
		                	}
		                }
		                else if($coinname=="Ripple") // check eth balance for usdt transfer
		                {
		                	echo "Ripple";
		                }

		                else
		                {
		                	$send_money_res = $this->local_model->make_transfer($coinname,$trans_det); // transfer to admin
		                	//$send_money_res = "test";
		                }

	                    // add to admin wallet logs
                        if($send_money_res!="" || $send_money_res!="error")
                        {
	                    $trans_data = array(
	                                        'userid'=>$value->user_id,
	                                        'crypto_address' => $from_address,
	                                        'type'=>'deposit',
	                                        'amount'=>(float)$amount,
	                                        'currency_symbol'=>$currency_det->currency_symbol,
	                                        'status'=>'Completed',
	                                        'date_created'=>date('Y-m-d H:i:s'),
	                                        'currency_id'=>$currency_det->id,
	                                        'txn_id'=>$send_money_res
	                                    );
	                    $insert = $this->common_model->insertTableData('admin_wallet_logs',$trans_data);
	                    $result = array('status'=>'success','message'=>'update deposit success');
	                    }

	                }
	                else
	                {
                       $result = array('status'=>'failed','message'=>'update deposit failed insufficient balance');
	                }

	            }
	            else
	            {
	                  $result = array('status'=>'failed','message'=>'invalid address');	
	            }

	        $i++;}

	    }
	    die(json_encode($result));

	}

		function get_pendingtransaction($address,$coin_name)
	{
           
      $ctype = $this->db->select('*')->where(array('currency_name'=>$coin_name,'status'=>'1'))->get('currency')->row();
      if($ctype->coin_type=="coin")
      {
        $model_currency = $coin_name;

      }
      else
      {
      	if($ctype->crypto_type=='eth'){
			$model_currency = "token";
			
		}
		else{
			$model_currency = "token_bnb";
		}

      } 
      
 
     $model_name = strtolower($model_currency).'_wallet_model';
	   $model_location = 'wallets/'.strtolower($model_currency).'_wallet_model';
	   
	   $this->load->model($model_location,$model_name);
	   $pending = $this->$model_name->eth_pendingTransactions();
	   $txn_count = 0;
	   if(count($pending) >0)
	   {
	   	foreach($pending as $txn)
	   	{
	   		if($address==$txn->from)
	   		{
              $txn_count++;
	   		}
	   	}
	   }
	   return $txn_count;
	}
	
	function get_transactioncount($address)
	{
       $coin_name = 'Ethereum';
       $model_name = strtolower($coin_name).'_wallet_model';
	   $model_location = 'wallets/'.strtolower($coin_name).'_wallet_model';
	   $this->load->model($model_location,$model_name);
	   $getcount = $this->$model_name->eth_getTransactionCount($address);
	   //echo "Get TransactionCount ===========> ".$getcount;
	   return $getcount;
	}

	function check_ethereum_functions($value,$coin)
	{
		$coin_name = $coin;
		$model_name = strtolower($coin_name).'_wallet_model';
		$model_location = 'wallets/'.strtolower($coin_name).'_wallet_model';
		$this->load->model($model_location,$model_name);
		if($value=='eth_accounts')
		{
		$parameter = "";
		$get_account = $this->$model_name->eth_accounts($parameter);
		echo "Get Account ===========> ".$get_account;
		}
		else if($value=='eth_blockNumber')
		{
		$parameter = "";
		$get_blockNumber = $this->$model_name->eth_blockNumber($parameter);
		echo "Get Block Number ===========> ".$get_blockNumber;
		}
		else if($value=='eth_getLogs')
		{
		$parameter = "";
		$getLogs = $this->$model_name->eth_getLogs($parameter);
		echo "Get Logs ===========> ".$getLogs;
		}
		else if($value=='eth_getBalance')
		{
		$parameter = "0x8936c1af634e0a1c3c6ac6bf4af7f1e37a565d14";
		$getBalance = $this->$model_name->eth_getBalance($parameter);
		echo "Get Balance ===========> ".$getBalance;
		}
		else if($value=='eth_getTransactionCount')
		{
		$parameter = "0x8936c1af634e0a1c3c6ac6bf4af7f1e37a565d14";
		$getcount = $this->$model_name->eth_getTransactionCount($parameter);
		echo "Get TransactionCount ===========> ".$getcount;
		}
		else if($value=='eth_gasPrice')
		{
			$parameter = "";
			$gas_price = $this->$model_name->eth_gasPrice($parameter);
			return $gas_price;
		}
		else if($value=='eth_pending')
		{
			//$txn_count = $this->$model_name->eth_getTransactionCount("0x2f460786e12e7720bed76ffcf1f31eb2ad303e49","pending");
			$txn_count = $this->$model_name->eth_pendingTransactions();
			return $txn_count;
		}

	}



 //    function check_ethereum_functions($value)
	// {
	// 	$coin_name = 'Ethereum';
	// 	$model_name = strtolower($coin_name).'_wallet_model';
	// 	$model_location = 'wallets/'.strtolower($coin_name).'_wallet_model';
	// 	$this->load->model($model_location,$model_name);
	// 	if($value=='eth_gasPrice')
	// 	{
	// 		$parameter = "";
	// 		$gas_price = $this->$model_name->eth_gasPrice($parameter);

	// 		return $gas_price;
	// 	}
	// 	else
	// 	{
	// 		return '1';
	// 	}

	// }
	function withdraw_coin_user_confirm($id)
	{
		// echo $id;

		// exit();
	
		 $user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('error','Your Not Login Please Login and try');
			front_redirect('', 'refresh');
		}
		 $id = base64_decode($id);


		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending'));

		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if($isValid > 0)
		{
			  $datetime =  $withdraw->datetime;
          
               $time = strtotime($datetime);

            $withdraw_timestamp = strtotime(date("Y-m-d H:i:s", strtotime("+10 minutes", $time))); // Check whether the time exceeds 10 mins
			$current_time = date('Y-m-d H:i:s', time());
            $current_date_timestamp = strtotime($current_time);
			if($withdraw->user_status=='Completed')
			{
				$this->session->set_flashdata('error','Your withdraw request already confirmed');
				front_redirect('wallet', 'refresh');
			}
			else if($withdraw->user_status=='Cancelled')
			{
				$this->session->set_flashdata('error','Your withdraw request already cancelled');
				front_redirect('wallet', 'refresh');
			}
			else if($withdraw->user_id != $user_id)
			{
				$this->session->set_flashdata('error','Your are not the owner of this withdraw request');
				front_redirect('wallet', 'refresh');
			}
			elseif($withdraw_timestamp < $current_date_timestamp)
			{
				$currency = $withdraw->currency_id;
				$amount = $withdraw->amount;
				$balance = getBalance($user_id,$currency,'crypto');
				$finalbalance = $balance+$amount;
				$updatebalance = updateBalance($user_id,$currency,$finalbalance,'crypto');
				$updateData['user_status'] = 'Cancelled';
				$updateData['status'] = 'Cancelled';
				$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'crypto');
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
				$this->session->set_flashdata('error','Withdraw Declined!. Balance reverted to your account.');
				front_redirect('wallet', 'refresh');
			}
			else {
				$updateData['user_status'] = 'Completed';
				$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'crypto');
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
						$link_ids = base64_encode($id);
						$enc_email = getAdminDetails('1','email_id');
						$email = decryptIt($enc_email);
						$prefix = get_prefix();
						$user = getUserDetails($user_id);
						$usernames = $prefix.'username';
						$username = $user->$usernames;
						$currency_name = getcryptocurrency($withdraw->currency_id);
						$sitename = getSiteSettings('site_name');
	                    
							$email_template = 'Withdraw_User_Complete';
								$special_vars = array(
	                            '###SITENAME###' => $sitename,
								'###USERNAME###' => 'Admin',
								'###AMOUNT###'   => $withdraw->amount,
								'###CURRENCY###' => $currency_name,
								'###FEES###' => $withdraw->fee,
								'###CRYPTOADDRESS###' => $withdraw->crypto_address,
								'###CONFIRM_LINK###' => admin_url().'admin/withdraw_coin_confirm/'.$link_ids,
								'###CANCEL_LINK###' => admin_url().'admin/withdraw_coin_cancel/'.$link_ids,
								);
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

				$this->session->set_flashdata('success','Successfully placed your withdraw request. Our team will also confirm this request');
				front_redirect('wallet', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error','Invalid withdraw confirmation');
			front_redirect('wallet', 'refresh');
		}
	}
	function withdraw_coin_user_cancel($id)
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
		}
		 $id = base64_decode($id);

		 // exit();
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending','currency_type'=>'crypto'));

  // echo $this->db->last_query();
		// exit();
		$isValid = $isValids->num_rows();

		$withdraw = $isValids->row();
		if($isValid)
		{
			if($withdraw->user_status=='Completed')
			{
				$this->session->set_flashdata('error','Your withdraw request already confirmed');
				front_redirect('wallet', 'refresh');
			}
			else if($withdraw->user_status=='Cancelled')
			{
				$this->session->set_flashdata('error','Your withdraw request already cancelled');
				front_redirect('wallet', 'refresh');
			}
			else if($withdraw->user_id != $user_id)
			{
				$this->session->set_flashdata('error','Your are not the owner of this withdraw request');
				front_redirect('wallet', 'refresh');
			}
			else {
				$currency = $withdraw->currency_id;
				$amount = $withdraw->amount;
				$balance = getBalance($user_id,$currency,'crypto');
				$finalbalance = $balance+$amount;
				$updatebalance = updateBalance($user_id,$currency,$finalbalance,'crypto');
				$updateData['user_status'] = 'Cancelled';
				$updateData['status'] = 'Cancelled';
				$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'crypto');
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
				$this->session->set_flashdata('success','Successfully cancelled your withdraw request');
				front_redirect('wallet', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error','Invalid withdraw confirmation');
			front_redirect('wallet', 'refresh');
		}
	}
	function getValue()
	{
        $currency_id = $_POST['currency_id'];
        $currency_det = $this->common_model->getTableData('currency', array('id' => $currency_id))->row();    
        if(count($currency_det) > 0){
           $response = array('usd_value'=>$currency_det->online_usdprice,'status'=>'success');
        }
        else{
            $response = array('status'=>'failed');
        }
        echo json_encode($response);

    }	
	function transaction()
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url());
		}
		

		if(isset($_POST))
	    {

			$this->form_validation->set_rules('ids', 'ids', 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');

			$id = $this->db->escape_str($this->input->post('ids'));

			if($id!=7){
			$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
		}

			if ($this->form_validation->run())
			{

				$id = $this->db->escape_str($this->input->post('ids'));
				$amount = $this->db->escape_str($this->input->post('amount'));
				if($id!=7){
				$address = $this->db->escape_str($this->input->post('address'));
				$Payment_Method = 'crypto';
				$Currency_Type = 'crypto';
				$Bank_id = '';
			}
			else{
				$address = '';
				$Payment_Method = 'bank';
				$Currency_Type = 'fiat';
				$Bank_id = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'status'=>'Verified'))->row('id');
			}
	 			$balance = getBalance($user_id,$id,'crypto');
				$currency = getcryptocurrencydetail($id);
				$w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Pending','currency_id'=>$id));
				 $count        = $w_isValids->num_rows();
	             $withdraw_rec = $w_isValids->row();
                $final = 1;
                $Validate_Address = 1;
				if($Validate_Address==1)
				{	
					if($count>0)
					{
							
						$this->session->set_flashdata('error', $this->lang->line('Sorry!!! Your previous ') . $currency->currency_symbol . $this->lang->line('withdrawal is waiting for admin approval. Please use other wallet or be patience'));
						front_redirect('withdraw', 'refresh');	
					}
					else
					{
						if($amount>$balance)
						{
							$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than your current balance'));
							front_redirect('withdraw', 'refresh');	
						}
						if($amount < $currency->min_withdraw_limit)
						{
							$this->session->set_flashdata('error',$this->lang->line('Amount you have entered is less than minimum withdrawl limit'));
							front_redirect('withdraw', 'refresh');	
						}
						elseif($amount>$currency->max_withdraw_limit)
						{
							$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than maximum withdrawl limit'));
							front_redirect('withdraw', 'refresh');	
						}
						elseif($final!=1)
						{
							$this->session->set_flashdata('error',$this->lang->line('Invalid address'));
							front_redirect('withdraw', 'refresh');	
						}
						else
						{
							$withdraw_fees_type = $currency->withdraw_fees_type;
					        $withdraw_fees = $currency->withdraw_fees;

					        if($withdraw_fees_type=='Percent') { $fees = (($amount*$withdraw_fees)/100); }
					        else { $fees = $withdraw_fees; }
					        $total = $amount-$fees;
							$user_status = 'Pending';
							$insertData = array(
								'user_id'=>$user_id,
								'payment_method'=>$Payment_Method,
								'currency_id'=>$id,
								'amount'=>$amount,
								'fee'=>$fees,
								'crypto_address'=>$address,
								'transfer_amount'=>$total,
								'datetime'=>gmdate(time()),
								'type'=>'Withdraw',
								'status'=>'Pending',
								'currency_type'=>$Currency_Type,
								'user_status'=>$user_status
								);
							$finalbalance = $balance - $amount;
							$updatebalance = updateBalance($user_id,$id,$finalbalance,'crypto');
							$insertData_clean = $this->security->xss_clean($insertData);
							$insert = $this->common_model->insertTableData('transactions', $insertData_clean);
							if($insert) 
							{
								$prefix = get_prefix();
								$user = getUserDetails($user_id);
								$usernames = $prefix.'username';
								$username = $user->$usernames;
								$email = getUserEmail($user_id);
								$currency_name = getcryptocurrency($id);
								$link_ids = base64_encode($insert);
								$sitename = getSiteSettings('site_name');
								$site_common      =   site_common();		                    

								if($id!=7){
								$email_template = 'Withdraw_User_Complete';
									$special_vars = array(
									'###SITENAME###' => $sitename,
									'###USERNAME###' => $username,
									'###AMOUNT###'   => (float)$amount,
									'###CURRENCY###' => $currency_name,
									'###FEES###' => $fees,
									'###CRYPTOADDRESS###' => $address,
									'###CONFIRM_LINK###' => base_url().'withdraw_coin_user_confirm/'.$link_ids,
									'###CANCEL_LINK###' => base_url().'withdraw_coin_user_cancel/'.$link_ids
									);
								}
								else{
	                               $email_template = 'Withdraw_User_Complete_fiat';
									$special_vars = array(
									'###SITENAME###' => $sitename,
									'###USERNAME###' => $username,
									'###AMOUNT###'   => (float)$amount,
									'###CURRENCY###' => $currency_name,
									'###FEES###' => $fees,
									'###CONFIRM_LINK###' => base_url().'withdraw_coin_user_confirm/'.$link_ids,
									'###CANCEL_LINK###' => base_url().'withdraw_coin_user_cancel/'.$link_ids
									);
								}
							    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);								
								$this->session->set_flashdata('success',$this->lang->line('Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!'));
								front_redirect('wallet', 'refresh');
							} 
							else 
							{
								$this->session->set_flashdata('error',$this->lang->line('Unable to submit your withdraw request. Please try again'));
								front_redirect('wallet', 'refresh');
							}
						}
					}
				}
				else
				{

					$this->session->set_flashdata('error', $this->lang->line('Please check the address'));
					front_redirect('wallet', 'refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('error', validation_errors());
				front_redirect('wallet', 'refresh');
			}
	    }

	    else{
	    	front_redirect('wallet', 'refresh');
	    }
	}
	function wallet()
	{		 
        $this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}

		$data['site_common'] = site_common();
		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
		
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['dig_currency'] = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
		$data['dig_currencys'] = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
		$data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id),'','','','','','',array('trans_id','DESC'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
		$this->load->view('front/user/wallet', $data);
	}


	function transactions_history(){


		 $this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}

		$data['site_common'] = site_common();

		$data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id),'','','','','','',array('trans_id','DESC'))->result();



		$this->load->view('front/user/transactions', $data);
	}




	function history()
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['site_common'] = site_common();
		$data['user_id'] = $user_id;		

		$data['login_history'] = $this->common_model->getTableData('user_activity',array('user_id'=>$user_id),'','','','','','',array('act_id','DESC'))->result();
				

		$data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Deposit'),'','','','','','',array('trans_id','DESC'))->result();

		$data['withdraw_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();

		$data['buycrypto_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'buy_crypto'),'','','','','','',array('trans_id','DESC'))->result();

		$data['trade_history'] = $this->common_model->getTableData('coin_order',array('userId'=>$user_id),'','','','','','',array('trade_id','DESC'))->result();

		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['action'] = front_url() . 'history';
		$data['js_link'] = '';
		$meta = $this->common_model->getTableData('meta_content', array('link' => 'coin_request'))->row();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'history'))->row();
		$this->load->view('front/user/history', $data); 
	}

	 function update_adminaddress($coin_symbol)
    {
echo $coin_symbol;
exit();
        $Fetch_coin_list = $this->common_model->getTableData('currency',array('currency_symbol'=>$coin_symbol,'status'=>'1'))->result();

        $whers_con = "id='1'";

        // $get_admin  =   $this->common_model->getrow("bluerico_admin", $whers_con);
        // print_r($get_admin); exit();

        $admin_id = "1";

        $enc_email = getAdminDetails($admin_id, 'email_id');

		$email = decryptIt($enc_email);


        $get_admin = $this->common_model->getrow("bidex_admin_wallet", $whers_con);
        echo "<pre>";
        print_r($get_admin);
        exit();
        if(!empty($get_admin)) 
        {
            $get_admin_det = json_decode($get_admin->addresses, true);

			foreach($Fetch_coin_list as $coin_address)
			{			
				//$currency_exit =  array_key_exists($coin_address->currency_symbol, $get_admin_det)?true:false;
				
				if(array_key_exists($coin_address->currency_symbol, $get_admin_det))
				{
					//$currency_address_checker = (!empty($get_admin_det[$coin_address->currency_symbol]))?true:false;

		    		if(empty($get_admin_det[$coin_address->currency_symbol]))
		    		{
						$parameter = '';

						switch ($coin_address->coin_type) {
							case 'coin':
								
								switch ($coin_address->currency_symbol) {
									case 'ETH':
										$parameter='create_eth_account';
								
										$Get_First_address = $this->local_model->access_wallet($coin_address->id,'create_eth_account', $email);
										
											$get_admin_det[$coin_address->currency_symbol] = $Get_First_address;

											$update['addresses'] = json_encode($get_admin_det);

				        					$this->common_model->updateTableData("admin_wallet",array('user_id' => $admin_id),$update);
										
										

										break;
									
									default:
										$parameter='getnewaddress';

										$Get_First_address = $this->local_model->access_wallet($coin_address->id,'getnewaddress', $email);

							

											$get_admin_det[$coin_address->currency_symbol] = $Get_First_address;

											$update['addresses'] = json_encode($get_admin_det);

				        					$this->common_model->updateTableData("admin_wallet",array('user_id'=>$admin_id),$update);
										
									
										break;
								}

								break;
							case 'token':

								$get_admin_det[$coin_address->currency_symbol] = $get_admin_det['ETH'];

								$update['addresses'] = json_encode($get_admin_det);
								
								$this->common_model->updateTableData("admin_wallet",array('user_id'=>$admin_id),$update);

								break;
							default:
								break;
						}	               
					}
				}
			}
		}
    }


    function add_coin()
	{
		if($this->block() == 1)
{ 
front_redirect('block_ip');
}
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('login', 'refresh');
		}
		if($this->input->post())
		{
			$image = $_FILES['coin_logo']['name'];
			if($image!="") {
			$uploadimage=cdn_file_upload($_FILES["coin_logo"],'uploads/coin_request');
			if($uploadimage)
			{
				$image=$uploadimage['secure_url'];
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('Problem with your coin image'));
				front_redirect('add_coin', 'refresh');
			}
			} 
			else 
			{ 
				$image=""; 
			}
			$insertData['user_id'] = $user_id;
			$insertData['coin_type'] = $this->input->post('coin_type');
			$insertData['coin_name'] = $this->input->post('coin_name');
			$insertData['coin_symbol'] = $this->input->post('coin_symbol');
			$insertData['coin_logo'] = $image;
			$insertData['max_supply'] = $this->input->post('max_supply');
			$insertData['coin_price'] = $this->input->post('coin_price');
			$insertData['priority'] = $this->input->post('priority');
			if($this->input->post('crypto_type') !='')
			{
			$insertData['crypto_type'] = $this->input->post('crypto_type');
		    }
		    if($this->input->post('token_type') !='')
		    {
            $insertData['token_type'] = $this->input->post('token_type');
            }
            $insertData['marketcap_link'] = $this->input->post('marketcap_link');
            $insertData['coin_link'] = $this->input->post('coin_link');
            $insertData['twitter_link'] = $this->input->post('twitter_link');
            $insertData['username'] = $this->input->post('username');
            $insertData['email'] = $this->input->post('email');
			$insertData['status'] = '0';
			$insertData['added_by'] = 'user';
			$insertData['added_date'] = date('Y-m-d h:i:s');
            /*$insertData['type'] = 'digital';
            $insertData['verify_request'] = 0;*/
            $username = $this->input->post('username');
			$user_mail = $this->input->post('email');
			$coin_name = $this->input->post('coin_name');
			$insert = $this->common_model->insertTableData('add_coin', $insertData);
			$email_template = 'Coin_request';
			$special_vars = array(
			'###USERNAME###' => $username,
			'###COIN###' => $coin_name
			);
			//-----------------
			$this->email_model->sendMail($user_mail, '', '', $email_template, $special_vars);
			if ($insert) {

				$this->session->set_flashdata('success', $this->lang->line('Your add coin request successfully sent to our team'));
				front_redirect('add_coin', 'refresh');
			} else {
				$this->session->set_flashdata('error', $this->lang->line('Error occur!! Please try again'));
				front_redirect('add_coin', 'refresh');
			}
		}
		$data['site_common'] = site_common();
		$meta = $this->common_model->getTableData('meta_content', array('link' => 'coin_request'))->row();
		$data['action'] = front_url() . 'add_coin';
		$data['heading'] = $meta->heading;
		$data['title'] = $meta->title;
		$data['meta_keywords'] = $meta->meta_keywords;
		$data['meta_description'] = $meta->meta_description;
		$this->load->view('front/user/add_coin', $data);
	}

	public function account(){
		if($this->block() == 1)
				{ 
				front_redirect('block_ip');
				}
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}

		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['bank_details'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'profile-edit'))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$this->load->view('front/user/account', $data); 
	}
    
    public function settings_account(){
		if($this->block() == 1)
				{ 
				front_redirect('block_ip');
				}
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}

		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['bank_details'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'profile-edit'))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$this->load->view('front/user/settings_account', $data); 
	}

	public function view_bank_details(){
		if($this->block() == 1)
				{ 
				front_redirect('block_ip');
				}
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}

		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['bank_details'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'profile-edit'))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$this->load->view('front/user/view_bank_account', $data); 
	}

	function add_bank_details()
	{		 
		$this->load->library('session','form_validation');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('Please Login'));
			redirect(base_url().'home');
		}
		if($_POST)
		{
			$this->form_validation->set_rules('bank_account_number', 'Bank Account number', 'required|xss_clean');
			if($this->form_validation->run())
			{
				$insertData['user_id'] = $user_id;
				//$insertData['currency'] = $this->db->escape_str($this->input->post('currency'));
				$insertData['bank_account_name'] = strip_tags(trim($this->db->escape_str($this->input->post('bank_account_name'))));
				$insertData['bank_account_number'] = strip_tags(trim($this->db->escape_str($this->input->post('bank_account_number'))));
				$insertData['bank_name'] = strip_tags(trim($this->db->escape_str($this->input->post('bank_name'))));
				$insertData['ifsc_code'] = strip_tags(trim($this->db->escape_str($this->input->post('ifsc_code'))));
				if ($_FILES['bankscreenshot']['name']!="") 
				{
					$imagepro = $_FILES['bankscreenshot']['name'];
					if($imagepro!="" && getExtension($_FILES['bankscreenshot']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["bankscreenshot"],'uploads/user/'.$user_id,$this->input->post('bankscreenshot'));
						if($uploadimage1)
						{
							//$imagepro=$uploadimage1['secure_url'];
							if(is_array($uploadimage1)) {
							$imagepro=$uploadimage1['secure_url'];
							} else {
								$errorMsg = current( (Array)$uploadimage1 );
								$this->session->set_flashdata('error', $errorMsg);
								front_redirect('add_bank_account', 'refresh');
							}
						}
						else
						{
							$this->session->set_flashdata('error', $this->lang->line('Problem with profile picture'));
							front_redirect('add_bank_account', 'refresh');
						} 
					}				
					$insertData['bank_statement']=$imagepro;
				}
				//$insertData['bank_swift'] = $this->db->escape_str($this->input->post('bank_swift'));
				
				// $insertData['bank_address'] = $this->db->escape_str($this->input->post('bank_address'));
				// $insertData['bank_city'] = $this->db->escape_str($this->input->post('bank_city'));
				// $insertData['bank_country'] = $this->db->escape_str($this->input->post('bank_country'));
				// $insertData['bank_postalcode'] = $this->db->escape_str($this->input->post('bank_postalcode'));
				$insertData['added_date'] = date("Y-m-d H:i:s");				
				$insertData['status'] = 'Pending';
				$insertData['user_status'] = '1';
				
				// echo "<pre>";print_r($insertData);die;
				$insertData_clean = $this->security->xss_clean($insertData);
				//$get = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();
				
					$insert=$this->common_model->insertTableData('user_bank_details',$insertData_clean);
				

				
				if ($insert) {
					$this->session->set_flashdata('success', 'Bank details Added Successfully');

					front_redirect('add_bank_account', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Something ther is a Problem .Please try again later');
					front_redirect('add_bank_account', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error','Some datas are missing');
				front_redirect('add_bank_account', 'refresh');
			}
		}		

		//$data['fiat_currency'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1),'','','','','','',array('id','ASC'))->result();
		//$data['bankwire'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();
		//$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['site_common'] = site_common();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$this->load->view('front/user/add_bank_account', $data); 
		
		// echo "<pre>";print_r($data['bankwire']);die;
	}

	function update_bank_details()
	{		 
		$this->load->library('session','form_validation');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('Please Login'));
			redirect(base_url().'home');
		}
		if($_POST)
		{
			$this->form_validation->set_rules('bank_account_number', 'Bank Account number', 'required|xss_clean');
			if($this->form_validation->run())
			{
				$insertData['user_id'] = $user_id;
				//$insertData['currency'] = $this->db->escape_str($this->input->post('currency'));
				$insertData['bank_account_name'] = strip_tags(trim($this->db->escape_str($this->input->post('bank_account_name'))));
				$insertData['bank_account_number'] = strip_tags(trim($this->db->escape_str($this->input->post('bank_account_number'))));
				$insertData['bank_name'] = strip_tags(trim($this->db->escape_str($this->input->post('bank_name'))));
				$insertData['ifsc_code'] = strip_tags(trim($this->db->escape_str($this->input->post('ifsc_code'))));
				if ($_FILES['editscreenshot']['name']!="") 
				{
					$imagepro = $_FILES['editscreenshot']['name'];
					if($imagepro!="" && getExtension($_FILES['editscreenshot']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["editscreenshot"],'uploads/user/'.$user_id,$this->input->post('editscreenshot'));
						if($uploadimage1)
						{
							//$imagepro=$uploadimage1['secure_url'];
							if(is_array($uploadimage1)) {
								$imagepro=$uploadimage1['secure_url'];
								} else {
									$errorMsg = current( (Array)$uploadimage1 );
									$this->session->set_flashdata('error', $errorMsg);
									front_redirect('edit_bank_account', 'refresh');
								}
						}
						else
						{
							$this->session->set_flashdata('error', $this->lang->line('Problem with profile picture'));
							front_redirect('edit_bank_account', 'refresh');
						} 
					}				
					$insertData['bank_statement']=$imagepro;
				}
				//$insertData['bank_swift'] = $this->db->escape_str($this->input->post('bank_swift'));
				//$insertData['bank_name'] = $this->db->escape_str($this->input->post('bank_name'));
				// $insertData['bank_address'] = $this->db->escape_str($this->input->post('bank_address'));
				// $insertData['bank_city'] = $this->db->escape_str($this->input->post('bank_city'));
				// $insertData['bank_country'] = $this->db->escape_str($this->input->post('bank_country'));
				// $insertData['bank_postalcode'] = $this->db->escape_str($this->input->post('bank_postalcode'));
				$insertData['added_date'] = date("Y-m-d H:i:s");				
				$insertData['status'] = 'Pending';
				$insertData['user_status'] = '1';
				
				// echo "<pre>";print_r($insertData);die;
				$insertData_clean = $this->security->xss_clean($insertData);
				
				$insert = $this->common_model->updateTableData('user_bank_details',array('user_id'=>$user_id),$insertData_clean);
				

				
				if ($insert) {
					$this->session->set_flashdata('success', 'Bank details Updated Successfully');

					front_redirect('edit_bank_account', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Something ther is a Problem .Please try again later');
					front_redirect('edit_bank_account', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error','Some datas are missing');
				front_redirect('edit_bank_account', 'refresh');
			}
		}		

		//$data['fiat_currency'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1),'','','','','','',array('id','ASC'))->result();
		$data['site_common'] = site_common();
		$data['bankwire'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$this->load->view('front/user/edit_bank_account', $data); 
		
		// echo "<pre>";print_r($data['bankwire']);die;
	}

	function delete_bank_account($id) 
	{
		$data = array('id'=>$id);
		$this->common_model->deleteTableData('user_bank_details', $data);
		$this->session->set_flashdata('success', $this->lang->line('Deleted successfully'));
		front_redirect('settings_account', 'refresh'); 
	}

	function security()
	{	
		$this->load->library('session','form_validation');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
		}
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$this->load->library('Googleauthenticator');
		if($data['users']->randcode=="enable" || $data['users']->secret!="")
		{	
			$secret = $data['users']->secret; 
			$data['secret'] = $secret;
        	$ga     = new Googleauthenticator();
			$data['url'] = $ga->getQRCodeGoogleUrl('bidex', $secret);
		}
		else
		{
			$ga = new Googleauthenticator();
			$data['secret'] = $ga->createSecret();
			$data['url'] = $ga->getQRCodeGoogleUrl('bidex', $data['secret']);
			$data['oneCode'] = $ga->getCode($data['secret']);
		}

		if($_POST)
		{

			$secret_code = $this->db->escape_str($this->input->post('secret'));
			$onecode = $this->db->escape_str($this->input->post('code'));
			$code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 3);

			if($data['users']->randcode != "enable")
			{

				if($code=='1')
				{
					$this->db->where('id',$user_id);
					$data1=array('secret'  => $secret_code,'randcode'  => "enable");
					$this->db->update('users',$data1);					
					$this->session->set_flashdata('success', $this->lang->line('TFA Enabled successfully'));
					front_redirect('settings/twostep-verification', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error', $this->lang->line('Please Enter correct code to enable TFA'));
					
					front_redirect('settings/twostep-verification', 'refresh');
					
				}
			}
			else
			{
				if($code=='1')
				{
					$this->db->where('id',$user_id);
					$data1=array('secret'  => $secret_code,'randcode'  => "disable");
					$this->db->update('users',$data1);	
					$this->session->set_flashdata('success', $this->lang->line('TFA Disabled successfully'));
					front_redirect('settings/twostep-verification', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error', $this->lang->line('Please Enter correct code to disable TFA'));
					/*echo $secret_code."<br/>";
					echo $code."Pila<br/>";
					echo $onecode;
					exit();*/
					front_redirect('settings/twostep-verification', 'refresh');
				}
			}
		}

		front_redirect('settings/twostep-verification', 'refresh');
	}

	function deposit($cur='')
	{
		if($this->block() == 1){ 
			front_redirect('block_ip');
		}
		$user_id=$this->session->userdata('user_id');
		if($user_id=="") {	
			front_redirect('', 'refresh');
		}
		$bankwire = $this->common_model->getTableData('admin_bank_details',array('id'=>1))->row();
		if(!empty($bankwire)) {
			$data['bankwire'] = $bankwire;
		}
		$data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['fiat_currency'] = $this->common_model->getTableData('currency',array('status'=>1,'type'=>'fiat'))->row();

		$data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details', array('currency'=>$data['fiat_currency']->id))->row();

		$data['user_bank'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'status'=>'1'))->row();
		
		$data['dig_currency'] = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1),'','','','','','',array('id','ASC'))->result();
		$data['sel_currency'] = $this->common_model->getTableData('currency',array('currency_symbol'=>$cur),'','','','','','',array('id','ASC'))->row();
		$cur_id = $data['sel_currency']->id;

		if($data['sel_currency']->currency_symbol=='XRP')
		{
			$secret = $this->common_model->getTableData('crypto_address',array('user_id'=>$user_id),'payment_id')->row();
			$data['destination_tag'] = $secret->payment_id;
		}

		$data['all_currency'] = $this->common_model->getTableData('currency',array('status'=>1),'currency_symbol,image','','','','','',array('id','ASC'))->result();

		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));

		$data['balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

		$data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Deposit'),'','','','','','',array('trans_id','DESC'))->result();
		 
		if(isset($_POST['deposit_mobile']))
		{
			 $Currency_Id = $this->input->post('currency');
			$data['slct_fiat_currency'] = $this->common_model->getTableData('currency',array('status'=>1, 'id'=>$this->input->post('currency')))->row();
				$slct_fiat_currency = $data['slct_fiat_currency'];
				$value = $this->db->escape_str($this->input->post('amount'));
				 
				if($value < $slct_fiat_currency->min_deposit_limit)
				{
					$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is less than the minimum deposit limit'));
					front_redirect('deposit', 'refresh');	
				}
				elseif($value>$slct_fiat_currency->max_deposit_limit)
				{
				$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than the maximum deposit limit'));
				front_redirect('deposit', 'refresh');	
				}
				$deposit_max_fees = $data['slct_fiat_currency']->deposit_max_fees;
		        $deposit_fees_type = $data['slct_fiat_currency']->deposit_fees_type;
		        $deposit_fees = $data['slct_fiat_currency']->deposit_fees;
		        if($deposit_fees_type=='Percent') { $fees = (($value*$deposit_fees)/100); }
		        else { $fees = $deposit_fees; }
		        if($fees>$deposit_max_fees) { $final_fees = $deposit_max_fees; }
		        else { $final_fees = $fees; }
		        $total = $value-$final_fees;
				
				// Added to reserve amount
				
			$mobile_number 	   = $this->db->escape_str($this->input->post('mobile_number'));
			$pay_name 	   = $this->db->escape_str($this->input->post('pay_name'));
			$product_code = $this->db->escape_str($this->input->post('productcode'));
			$payment_types = $this->db->escape_str($this->input->post('payment_types'));
			$narration = $this->db->escape_str($this->input->post('narration'));

			$PayPeaks = array();
			$PayPeaks['Ref'] = 'IX'.$user_id.'#'.strtotime(date('d-m-Y h:i:s'));
			$PayPeaks['Msisdn'] = $mobile_number;
			$PayPeaks['Name'] = $pay_name;
			$PayPeaks['Narration'] = $narration;
			$PayPeaks['Product'] = $product_code;
			$PayPeaks['Amount'] = $value;
			$PayPeaks['Currency'] = $slct_fiat_currency->currency_symbol;
			$PayPeaks['MerchantId'] = getUserDetails($user_id,'merchant_id');
			$PayPeaks['Channel'] = 'WA';

			$PayPeaks_Response = paypeaks_receive_money($PayPeaks);
			
			if(isset($PayPeaks_Response) && $PayPeaks_Response!='0'){
				$Response_Code = $PayPeaks_Response->ResponseCode;
				if($Response_Code=='00'){

				$insertData = array(
				'user_id'=>$user_id,
				'payment_method'=>'Mobile Wallet',
				'currency_id'=>$this->db->escape_str($this->input->post('currency')),
				'amount'=>$this->db->escape_str($this->input->post('amount')),
				'transaction_id'=>$PayPeaks['Ref'],
				'description'=>$narration,
				'fee'=>$final_fees,
				'transfer_amount'=>$total,
				'datetime'=>gmdate(time()),
				'type'=>'Deposit',
				'status'=>'Pending',
				'user_status'=>'Completed',
				'currency_type'=>'fiat',
				'merchant_id'=>$PayPeaks['MerchantId'],
				'payment_mode'=>'1',
				'product_code'=>$PayPeaks['Product']
				);

			$insert = $this->common_model->insertTableData('transactions', $insertData);
			if ($insert) {
				$this->session->set_flashdata('success', $this->lang->line('Funds via Mobile Wallet has been Received. Will Process your Payments within few Minutes'));
				front_redirect('deposit/GHS', 'refresh');
			}
			else {
				$this->session->set_flashdata('error', $this->lang->line('Unable to Process your Deposit. Please contact Admin.'));
				front_redirect('deposit/GHS', 'refresh');
			}
			
		}else {
			if($Response_Code=='02'){
				$Error_Message = 'Duplicate transaction';
			}
			elseif($Response_Code=='07'){
				$Error_Message = 'Error processing transaction';
			}
			elseif($Response_Code=='09'){
				$Error_Message = 'Transaction/entry failed';
			}
			elseif($Response_Code=='10'){
				$Error_Message = 'Insufficient Account Balance';
			}
			else{
				$Error_Message = 'Invalid Request';
			}
				$this->session->set_flashdata('error', $this->lang->line('Unable to Process your Deposit.').$Error_Message);
				front_redirect('deposit/GHS', 'refresh');
			}
			}
			else {
				$this->session->set_flashdata('error', $this->lang->line('Unable to Process your Deposit. Please contact Admin.'));
				front_redirect('deposit/GHS', 'refresh');
			}
		} 

		if(isset($_POST['deposit_bank']))
		{
			if($this->input->post('kyc_verify')) {

				if($_FILES['upload_pdf']['name']!='') {

					$file_size = $_FILES['upload_pdf']['size'];
					$config['upload_path'] = './uploads/';
					$config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('upload_pdf')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$fname = $this->upload->data('file_name');
						$update = $this->common_model->updateTableData('users',array('id'=>$user_id),array('photo_id_3'=>$fname));
					}
				} else{
					$this->session->set_flashdata('error', 'Please upload the bankwire receipt pdf and try again!!!');
					front_redirect('settings', 'refresh');
				}
			} else {
				if($_FILES['upload_pdf_deposit']['name']!='') {
					$config['upload_path'] = './uploads/';
					$config['allowed_types'] = 'pdf'; 		
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('upload_pdf_deposit')) {
					 	$this->data['error'] = $this->upload->display_errors();
					    print_r($this->data['error']);  
					} else {
						$fname = $this->upload->data('file_name');
					}
				} else{
					$this->session->set_flashdata('error', 'Please upload the bankwire receipt pdf and try again!!!');
					front_redirect('deposit/EUR', 'refresh');
				}
			}	 
           

			$Currency_Id = $this->input->post('currency');	 
			$coin_name = $this->input->post('coin_name');	 
			
			$data['slct_fiat_currency'] = $this->common_model->getTableData('currency',array('status'=>1, 'id'=>$this->input->post('currency')))->row();
			$slct_fiat_currency = $data['slct_fiat_currency'];
			$value = $this->db->escape_str($this->input->post('amount'));

			if($value < $slct_fiat_currency->min_deposit_limit)
			{
				$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is less than the minimum deposit limit'));
				front_redirect('deposit', 'refresh');	
			}
			elseif($value>$slct_fiat_currency->max_deposit_limit)
			{
				$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than the maximum deposit limit'));
				front_redirect('deposit', 'refresh');	
			}
			$deposit_max_fees = $data['slct_fiat_currency']->deposit_max_fees;
	        $deposit_fees_type = $data['slct_fiat_currency']->deposit_fees_type;
	        $deposit_fees = $data['slct_fiat_currency']->deposit_fees;
	        if($deposit_fees_type=='Percent') { $fees = (($value*$deposit_fees)/100); }
	        else { $fees = $deposit_fees; }
	        if($fees>$deposit_max_fees) { $final_fees = $deposit_max_fees; }
	        else { $final_fees = $fees; }
	        $total = $value-$final_fees;
			
			// Added to reserve amount
			$reserve_amount = getcryptocurrencydetail($this->input->post('currency'));
			$final_reserve_amount = (float)$this->input->post('amount') + (float)$reserve_amount->reserve_Amount;
			$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $this->input->post('currency'));

			// $ref_no 	   = $this->db->escape_str($this->input->post('ref_no'));
			$bank_no 	   = $this->db->escape_str($this->input->post('bank'));
			$payment_types = $this->db->escape_str($this->input->post('payment_types'));
			$description = $this->db->escape_str($this->input->post('description'));
			$kyc_bank_status = $this->db->escape_str($this->input->post('kyc_verify')); 
			$account_number = $this->db->escape_str($this->input->post('account_number'));
			$account_name = $this->db->escape_str($this->input->post('account_name'));
			$bank_name = $this->db->escape_str($this->input->post('bank_name'));
			$bank_swift = $this->db->escape_str($this->input->post('bank_swift'));
			$bank_country = $this->db->escape_str($this->input->post('bank_country'));
			$bank_city = $this->db->escape_str($this->input->post('bank_city'));
			$bank_address = $this->db->escape_str($this->input->post('bank_address'));
			$bank_postalcode = $this->db->escape_str($this->input->post('bank_postalcode'));

			if($kyc_bank_status!='') {
				$kyc_verify = $kyc_bank_status;	
				$update = $this->common_model->updateTableData('users',array('id'=>$user_id),array('photo_3_status'=>1));
			} else {
			 	$kyc_verify = '';
			}
			$Ref = $user_id.'#'.strtotime(date('d-m-Y h:i:s'));		
			
			if($_POST['admin_id']) {
				$admin_id = $_POST['admin_id'];
			} else {
				$admin_id = 0;
			}	
			$insertData = array(
				'user_id'=>$user_id,
				'admin_id'=>$admin_id,
				'payment_method'=>$payment_types,
				'currency_id'=>$this->db->escape_str($this->input->post('currency')),
				'amount'=>$this->db->escape_str($this->input->post('amount')),
				'transaction_id'=>$Ref,
				'bank_id'=>$bank_no,
				'fee'=>$final_fees,
				'transfer_amount'=>$total,
				'datetime'=>gmdate(time()),
				'type'=>'Deposit',
				'status'=>'Pending',
				'currency_type'=>'fiat',
				'verify_status'=>$kyc_verify,
				'account_number'=>$account_number,
				'account_name'=>$account_name,
				'bank_name'=>$bank_name,
				'bank_swift_code'=>$bank_swift,
				'bank_country'=>$bank_country,
				'bank_city'=>$bank_city,
				'bank_address'=>$bank_address,
				'bank_postalcode'=>$bank_postalcode,
				'upload_pdf_deposit'=>$fname,
				);

			$insert = $this->common_model->insertTableData('transactions', $insertData);
			$usersvalid = $this->common_model->getTableData('users', array('id' => $user_id));
			if ($insert) { 		                    
				if($kyc_verify=='') {
					$users = $usersvalid->row();
                    $prefix = get_prefix();
                    $user = getUserDetails($user_id);
                    $usernames = $prefix.'username';
	                $username = $user->$usernames;
				 	$enc_email = getAdminDetails('1','email_id');
					$adminmail = decryptIt($enc_email);
					$email_template = 'Deposit_request';
					$special_vars = array(
						'###USERNAME###' => $username,
						'###COIN###' => $coin_name,
						'###AMOUNT###' => $total,
						'###CONFIRM_LINK###' => front_url().'bidex_admin/deposit/view/'.$insert,
					);
					$this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars);
				} else {
					$users = $usersvalid->row();
					$email = getUserEmail($users->id);
					$prefix = get_prefix();
					$user = getUserDetails($user_id);
					$usernames = $prefix.'username';
					$username = $user->$usernames;
					$email_template = 'user_Deposit_Process_AML';
					$special_vars = array(
						'###USERNAME###' => $username
					);
                	$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				}
				$this->session->set_flashdata('success', $this->lang->line('Your deposit request placed successfully'));
				if($payment_types == 'paypal')
				{
					front_redirect('pay/'.$insert, 'refresh');
				}
				else
				{
					if($kyc_verify=='kyc_verify') front_redirect('settings', 'refresh');
						else front_redirect('deposit/EUR', 'refresh');
				}
			} else {
				$this->session->set_flashdata('error', $this->lang->line('Unable to submit your deposit request. Please try again'));
				if($kyc_verify=='kyc_verify') front_redirect('settings', 'refresh');
					else front_redirect('deposit/EUR', 'refresh');
			}

		}

		if(isset($_POST['deposit_card']))
		{
			 $Currency_Id = $this->input->post('currency');
			$data['slct_fiat_currency'] = $this->common_model->getTableData('currency',array('status'=>1, 'id'=>$this->input->post('currency')))->row();
				$slct_fiat_currency = $data['slct_fiat_currency'];
				$value = $this->db->escape_str($this->input->post('amount'));
				 
				if($value < $slct_fiat_currency->min_deposit_limit)
				{
					$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is less than the minimum deposit limit'));
					front_redirect('deposit', 'refresh');	
				}
				elseif($value>$slct_fiat_currency->max_deposit_limit)
				{
				$this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than the maximum deposit limit'));
				front_redirect('deposit', 'refresh');	
				}
				$deposit_max_fees = $data['slct_fiat_currency']->deposit_max_fees;
		        $deposit_fees_type = $data['slct_fiat_currency']->deposit_fees_type;
		        $deposit_fees = $data['slct_fiat_currency']->deposit_fees;
		        if($deposit_fees_type=='Percent') { $fees = (($value*$deposit_fees)/100); }
		        else { $fees = $deposit_fees; }
		        if($fees>$deposit_max_fees) { $final_fees = $deposit_max_fees; }
		        else { $final_fees = $fees; }
		        $total = $value-$final_fees;
				
				// Added to reserve amount
				

			$card_number 	   = $this->db->escape_str($this->input->post('card_number'));
			$card_type 	   = $this->db->escape_str($this->input->post('card_type'));
			$product_code = $this->db->escape_str($this->input->post('productcode'));
			$payment_types = $this->db->escape_str($this->input->post('payment_types'));
			$ex_date = $this->db->escape_str($this->input->post('ex_date'));
			$ex_year = $this->db->escape_str($this->input->post('ex_year'));
			$card_ver_num = $this->db->escape_str($this->input->post('card_ver_num'));

			$PayPeaks = array();
			$PayPeaks['Ref'] = 'IX'.$user_id.'#'.strtotime(date('d-m-Y h:i:s'));
			$PayPeaks['CardNumber'] = $card_number;
			$PayPeaks['Cvv'] = $card_ver_num;
			$PayPeaks['ExpiryMonth'] = $ex_date;
			$PayPeaks['ExpiryYear'] = $ex_year;
			$PayPeaks['Amount'] = $value;$PayPeaks['Email'] = getUserEmail($user_id);
			$PayPeaks['Pin'] = '';
			$PayPeaks['Product'] = $product_code;
			$PayPeaks['MerchantId'] = getUserDetails($user_id,'merchant_id');
			$PayPeaks['Channel'] = 'WA';

			$PayPeaks_Response = paypeaks_receive_money_card($PayPeaks);

			/*echo "<pre>";
			print_r(json_encode($PayPeaks));
			print_r($PayPeaks_Response);
			exit();*/

			if(isset($PayPeaks_Response) && $PayPeaks_Response!='0'){
				$Response_Code = $PayPeaks_Response->ResponseCode;
				$Card_Redirect_URL = $PayPeaks_Response->url;
				if($Response_Code=='00'){

				$insertData = array(
				'user_id'=>$user_id,
				'payment_method'=>'Card Processing',
				'currency_id'=>$this->db->escape_str($this->input->post('currency')),
				'amount'=>$this->db->escape_str($this->input->post('amount')),
				'transaction_id'=>$PayPeaks['Ref'],
				'description'=>'Credit Card Processing',
				'fee'=>$final_fees,
				'transfer_amount'=>$total,
				'datetime'=>gmdate(time()),
				'type'=>'Deposit',
				'status'=>'Pending',
				'user_status'=>'Completed',
				'currency_type'=>'fiat',
				'merchant_id'=>$PayPeaks['MerchantId'],
				'payment_mode'=>'1',
				'product_code'=>$PayPeaks['Product'],
				'card_number'=>$card_number,
				'cvv'=>$card_ver_num,
				'exp_date'=>$ex_date,
				'exp_year'=>$ex_year,
				'card_url'=>$Card_Redirect_URL
				);

			$insert = $this->common_model->insertTableData('transactions', $insertData);
			if ($insert) {

				//echo 'window.open('.$Card_Redirect_URL.')';

				//header("location: ".$Card_Redirect_URL."?pop=yes");
				echo "<script type='text/javascript'>window.open('".$Card_Redirect_URL."');</script>";

				$this->session->set_flashdata('success', $this->lang->line('Payment has been Processing. Please proceed with the popup link'));
				front_redirect('deposit/GHS', 'refresh');
			}
			else {
				$this->session->set_flashdata('error', $this->lang->line('Unable to Process your Deposit. Please contact Admin.'));
				front_redirect('deposit/GHS', 'refresh');
			}
			
		}else {
			if($Response_Code=='02'){
				$Error_Message = 'Duplicate transaction';
			}
			elseif($Response_Code=='07'){
				$Error_Message = 'Error processing transaction';
			}
			elseif($Response_Code=='09'){
				$Error_Message = 'Transaction/entry failed';
			}
			elseif($Response_Code=='10'){
				$Error_Message = 'Insufficient Account Balance';
			}
			else{
				$Error_Message = 'Invalid Request';
			}
				$this->session->set_flashdata('error', $this->lang->line('Unable to Process your Deposit.').$Error_Message);
				front_redirect('deposit/GHS', 'refresh');
			}

			}
			else {
				$this->session->set_flashdata('error', 'Unable to Process your Deposit. Please contact Admin.');
				front_redirect('deposit/GHS', 'refresh');
			}

		}

		
		if($cur=='')
		{
			$Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'),'id')->row();
			
			
				$coin_address = getAddress($user_id,$Fetch_coin_list->id);
				
						
		}
		else
		{
			$coin_address = getAddress($user_id,$cur_id);
		}

		// print_r($user_id);die;
		$data['First_coin_image'] =	"https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$coin_address&choe=UTF-8&chld=L";
		$data['crypto_address'] = $coin_address;
		$data['site_common'] = site_common();
		$data['action'] = front_url() . 'deposit';
		$data['js_link'] = 'deposit';
		$meta = $this->common_model->getTableData('meta_content', array('link' => 'deposit'))->row();
		$data['heading'] = $meta->heading;
		$data['title'] = $meta->title;
		$data['meta_keywords'] = $meta->meta_keywords;
		$data['meta_description'] = $meta->meta_description;
		$data['selcur_id'] = $data['sel_currency']->id;
		$data['currency_balance'] = getBalance($user_id,$data['selcur_id']);
		// echo "<pre>";print_r($data['currency_balance']);die;
		$this->load->view('front/user/deposit', $data); 
	}

	function withdraw($cur='')
	{

    error_reporting(0);
    $this->load->library(array('form_validation','session'));


		$user_id=$this->session->userdata('user_id');

	// exit();
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}



		/*$kyc = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		if(($kyc->photo_2_status != 3 && $kyc->photo_2_status != 2) || ($kyc->photo_3_status != 3 && $kyc->photo_3_status != 2))
		{
			$this->session->set_flashdata('error', "Please verify your kyc");
			redirect(base_url().'settings?page=kyc');
		}
		else if(($kyc->photo_2_status != 3 && $kyc->photo_2_status == 2) || ($kyc->photo_3_status != 3 && $kyc->photo_3_status == 2))
		{
			$this->session->set_flashdata('error', "Your kyc rejected by our team, please update kyc");
			redirect(base_url().'settings?page=kyc');
		}
		else if(($kyc->photo_2_status != 3 && $kyc->photo_2_status == 1) || ($kyc->photo_3_status != 3 && $kyc->photo_3_status == 1))
		{
			$this->session->set_flashdata('error', "Your kyc not verified");
			redirect(base_url().'settings?page=kyc');
		}
		else
		{

		}*/

		$data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		/*if($data['user']->randcode!='enable')
		{
			$this->session->set_flashdata('error', 'Please Enable 2 Step Verification.');
			front_redirect('settings', 'refresh');
		}*/
    $bankwire = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();
    if(!empty($bankwire)) {
      $data['bankwire'] = $bankwire;
    }
		$data['site_common'] = site_common();	
		$data['currency'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('id','ASC'))->result();	
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		if(isset($cur) && !empty($cur)){
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('currency_symbol'=>$cur),'','','','','','',array('id','ASC'))->row();

	   $test=$data['sel_currency']->withdraw_status;



      if($data['sel_currency']->withdraw_status==0) {   
        front_redirect('', 'refresh');
      }

	 //  if($data['users']->phoneverified == '0' || $data['users']->email_verified == '0')
	 //  {
		// $this->session->set_flashdata('success', $this->lang->line('Enable both Email and Phone Verification to continue Withdraw.'));
		// redirect(base_url().'wallet');
	 //  }

			$data['selcsym'] = $cur;
			if($data['sel_currency']->crypto_type_other != '')
			{
				$crypto_type_other_arr =explode('|',$data['sel_currency']->crypto_type_other);
				if($crypto_type_other_arr[0] == 'eth')
				{
					$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
					$data['fees'] = $data['sel_currency']->withdraw_fees;
					$data['min_withdraw_limit'] = $data['sel_currency']->min_withdraw_limit;
					$data['max_withdraw_limit'] = $data['sel_currency']->max_withdraw_limit;
				} 
				else if($crypto_type_other_arr[0] == 'bsc')
				{
					$data['fees_type'] = $data['sel_currency']->withdraw_bnb_fees_type;
					$data['fees'] = $data['sel_currency']->withdraw_bnb_fees;
					$data['min_withdraw_limit'] = $data['sel_currency']->min_bnb_withdraw_limit;
					$data['max_withdraw_limit'] = $data['sel_currency']->max_bnb_withdraw_limit;
				} else {
					$data['fees_type'] = $data['sel_currency']->withdraw_trx_fees_type;
					$data['fees'] = $data['sel_currency']->withdraw_trx_fees;
					$data['min_withdraw_limit'] = $data['sel_currency']->min_trx_withdraw_limit;
					$data['max_withdraw_limit'] = $data['sel_currency']->max_trx_withdraw_limit;
				}
			} else {
				$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
				$data['fees'] = $data['sel_currency']->withdraw_fees;
				$data['min_withdraw_limit'] = $data['sel_currency']->min_withdraw_limit;
				$data['max_withdraw_limit'] = $data['sel_currency']->max_withdraw_limit;
			}
            //$data['fees'] = apply_referral_fees_deduction($user_id,$data['sel_currency']->withdraw_fees);
		}
		else{
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('status' => 1),'','','','','','',array('id','ASC'))->row();
			$data['selcsym'] = $data['sel_currency']->currency_symbol;
			
			$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
			$data['fees'] = $data['sel_currency']->withdraw_fees;
            //$data['fees'] = apply_referral_fees_deduction($user_id,$data['sel_currency']->withdraw_fees);
		}
		
		$data['user_id'] = $user_id;
		
		$data['selcur_id'] = $data['sel_currency']->id;
		
		$data['currency_balance'] = getBalance($user_id,$data['selcur_id']);
		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));

		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'withdraw'))->row();
		$data['withdraw_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();

		$payment_id = $this->common_model->getTableData('crypto_address', array('user_id'=>$user_id))->row();
		$data['payment_id']=$payment_id->payment_id;


		if(isset($_POST['withdrawcoin']))
	    {

			$this->form_validation->set_rules('ids', 'ids', 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
			$passinp = $this->db->escape_str($this->input->post('ids'));
			$myval = explode('_',$passinp);
			$id = $myval[0]; 
			$name = $myval[1];
			$bal = $myval[2];

			if($id!=7)
			{ 
			   $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
		    }
		    else
		    { 
		    	$user_bank = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row(); 
				if(count($user_bank) == 0) 
		        { 
		        	$this->session->set_flashdata('error', "Please Fill your Bank Details");
					front_redirect('withdraw/'.$cur, 'refresh');
		        }	        
		        else 
		        {
		        	if($user_bank->status =='Pending'){
			        	$this->session->set_flashdata('error', "Please Wait for verification by our team");
						front_redirect('withdraw/'.$cur, 'refresh');
			        }
			        else if($user_bank->status =='Rejected'){
			        	$this->session->set_flashdata('error', "Your Bank details rejected by our team, Please contact support");
						front_redirect('withdraw/'.$cur, 'refresh');
			        }
			        else{
			        	$Bank = $user_bank->id; 
			        }	
		        	
		        }
		    }
		   
			/*if ($this->form_validation->run()!= FALSE)
			{ echo 'dddd'; exit;*/
				$amount = $this->db->escape_str($this->input->post('amount'));
				if($id!=7)
				{
					$address = $this->db->escape_str($this->input->post('address'));
					$Payment_Method = 'crypto';
					$Currency_Type = 'crypto';
					$Bank_id = '';
				}
				else
				{
					$address = '';
					$Payment_Method = 'bank';
					$Currency_Type = 'fiat';
					$Bank_id = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'status'=>'Verified'))->row('id');
				}
	 			$balance = getBalance($user_id,$id,'crypto');
				$currency = getcryptocurrencydetail($id);
				$w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Pending','currency_id'=>$id));
				$count        = $w_isValids->num_rows();
	            $withdraw_rec = $w_isValids->row();
                $final = 1;
                $Validate_Address = 1;
				if($Validate_Address==1)
				{	
					if($count>0)
					{							
						$this->session->set_flashdata('error', 'Sorry!!! Your previous ') . $currency->currency_symbol . $this->lang->line('withdrawal is waiting for admin approval. Please use other wallet or be patience');
						front_redirect('withdraw/'.$cur, 'refresh');	
					}
					else
					{
						// Min and Max withdraw fees limit set
						if($currency->crypto_type_other != '')
						{
							$crypto_type_other_arr =explode('|',$currency->crypto_type_other);
							if($crypto_type_other_arr[0] == 'eth')
							{
								$min_withdraw_limit = $currency->min_withdraw_limit;
								$max_withdraw_limit = $currency->max_withdraw_limit;
							} 
							else if($crypto_type_other_arr[0] == 'bsc')
							{
								$min_withdraw_limit = $currency->min_bnb_withdraw_limit;
								$max_withdraw_limit = $currency->max_bnb_withdraw_limit;
							} else {
								$min_withdraw_limit = $currency->min_trx_withdraw_limit;
								$max_withdraw_limit = $currency->max_trx_withdraw_limit;
							}
						} else {
							$min_withdraw_limit = $currency->min_withdraw_limit;
							$max_withdraw_limit = $currency->max_withdraw_limit;
						}


						// echo $amount;
						// echo "--limit---";
						// echo $max_withdraw_limit;

						// exit();

						if($amount>$balance)
						{ 
							$this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
							front_redirect('withdraw/'.$cur, 'refresh');
						}
						if($amount < $min_withdraw_limit)
						{
							$this->session->set_flashdata('error','Amount you have entered is less than minimum withdrawl limit');
							front_redirect('withdraw/'.$cur, 'refresh');
						}
						elseif($amount > $max_withdraw_limit)
						{
							$this->session->set_flashdata('error', 'Amount you have entered is more than maximum withdrawl limit');
							front_redirect('withdraw/'.$cur, 'refresh');	
						}
						elseif($final!=1)
						{
							$this->session->set_flashdata('error','Invalid address');
							front_redirect('withdraw/'.$cur, 'refresh');
						}
						else
						{
							if($currency->crypto_type_other != '')
							{
								if($this->input->post('network_type') == 'tron')
								{
									$withdraw_fees_type = $currency->withdraw_trx_fees_type;
					        		$withdraw_fees = $currency->withdraw_trx_fees;
								} else if($this->input->post('network_type') == 'bsc') {
									$withdraw_fees_type = $currency->withdraw_bnb_fees_type;
					        		$withdraw_fees = $currency->withdraw_bnb_fees;
								} else {
									$withdraw_fees_type = $currency->withdraw_fees_type;
					        		$withdraw_fees = $currency->withdraw_fees;
								}
							} else {
								$withdraw_fees_type = $currency->withdraw_fees_type;
					        	$withdraw_fees = $currency->withdraw_fees;
							}

					        if($withdraw_fees_type=='Percent') { $fees = (($amount*$withdraw_fees)/100); }
					        else { $fees = $withdraw_fees; }
							//$fees = apply_referral_fees_deduction($user_id,$fees);
					        $total = $amount-$fees;
					        $Ref = $user_id.'#'.strtotime(date('d-m-Y h:i:s'));
							$user_status = 'Pending';
							$ip_address = get_client_ip();

							$payment_id=$this->input->post('payment_id');
							$insertData = array(
								'user_id'=>$user_id,
								// 'ip_address'=>$ip_address,
								'destination_tag'=>$payment_id,
								'payment_method'=>$Payment_Method,
								'currency_id'=>$id,
								'amount'=>$amount,
								'transaction_id'=>$Ref,
								'fee'=>$fees,
								'bank_id'=>$Bank_id,
								'crypto_address'=>$address,
								'transfer_amount'=>$total,
								'datetime'=>date("Y-m-d H:i:s"),
								'type'=>'Withdraw',
								'status'=>'Pending',
								'currency_type'=>$Currency_Type,
								'user_status'=>$user_status,
								'crypto_type'=>($this->input->post('network_type') != '')?$this->input->post('network_type'):$currency->currency_symbol
								);
							$finalbalance = $balance - $amount;
							$updatebalance = updateBalance($user_id,$id,$finalbalance,'crypto');
							$insertData_clean = $this->security->xss_clean($insertData);
							$insert = $this->common_model->insertTableData('transactions', $insertData_clean);
							if($insert) 
							{
								$prefix = get_prefix();
								$user = getUserDetails($user_id);
								$usernames = $prefix.'username';
								$username = $user->$usernames;
								$email = getUserEmail($user_id);
								$currency_name = getcryptocurrency($id);
								$link_ids = base64_encode($insert);
								$sitename = getSiteSettings('english_english_site_name');
								$site_common      =   site_common();		                    

								if($id!=7)
								{
								    $email_template = 'Withdraw_User_Complete';
									$special_vars = array(
									'###SITENAME###' => $sitename,
									'###USERNAME###' => $username,
									'###AMOUNT###'   => (float)$amount,
									'###CURRENCY###' => $currency_name,
									'###FEES###' => $fees,
									'###CRYPTOADDRESS###' => $address,
									'###CONFIRM_LINK###' => base_url().'withdraw_coin_user_confirm/'.$link_ids,
									'###CANCEL_LINK###' => base_url().'withdraw_coin_user_cancel/'.$link_ids
									);
								}
								else
								{
	                                $email_template = 'Withdraw_Fiat_Complete';
									$special_vars = array(
									'###SITENAME###' => $sitename,
									'###USERNAME###' => $username,
									'###AMOUNT###'   => (float)$amount,
									'###CURRENCY###' => $currency_name,
									'###FEES###' => $fees,
									'###CONFIRM_LINK###' => base_url().'withdraw_confirm/'.$link_ids,
									'###CANCEL_LINK###' => base_url().'withdraw_cancel/'.$link_ids,
									);
								}
							    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);
								$this->session->set_flashdata('success','Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!');
								front_redirect('wallet', 'refresh');
							} 
							else 
							{
								$this->session->set_flashdata('error','Unable to submit your withdraw request. Please try again');
								front_redirect('withdraw/'.$cur, 'refresh');
							}
						}
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Please check the address');
					front_redirect('withdraw/'.$cur, 'refresh');
				}	
			/*}
			else
			{ 
				$this->session->set_flashdata('error', 'Please fill the correct values');
				front_redirect('withdraw/'.$cur, 'refresh');
			}*/
	    }
    if(isset($_POST['withdraw_bank']))
    {

        $this->form_validation->set_rules('currency', 'Currency', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount2', 'Amount', 'trim|required|xss_clean');


        // echo "<pre>"; print_r($_POST);die;
      if($this->form_validation->run()) {
        $Payment_Method = 'Bankwire';
        $Currency_Type = 'fiat';

      $Currency_Id = $this->db->escape_str($this->input->post('currency'));
      $account_number = $this->db->escape_str($this->input->post('account_number'));
      $account_name = $this->db->escape_str($this->input->post('account_name'));
      $bank_name = $this->db->escape_str($this->input->post('bank_name'));
      $bank_swift = $this->db->escape_str($this->input->post('bank_swift'));
      $bank_country = $this->db->escape_str($this->input->post('bank_country'));
      $payment_types = $this->db->escape_str($this->input->post('payment_types'));
      $amount = $this->db->escape_str($this->input->post('amount2'));
      $bank_city = $this->db->escape_str($this->input->post('bank_city'));
      $bank_address = $this->db->escape_str($this->input->post('bank_address'));
      $bank_postalcode = $this->db->escape_str($this->input->post('bank_postalcode'));

      $balance = getBalance($user_id,$Currency_Id,'fiat');
      $currency = getcryptocurrencydetail($Currency_Id);
      $w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Completed','currency_id'=>$Currency_Id));
        $count        = $w_isValids->num_rows();
              $withdraw_rec = $w_isValids->row();
                $final = 1;
                
           if($count>0)
      { 
        $this->session->set_flashdata('error', $this->lang->line('Sorry!!! Your previous '). $currency->currency_symbol .' withdrawal is Pending. Please use other wallet or be patience');
        front_redirect('withdraw/'.$cur, 'refresh');  
      }
      else{
        if($amount>$balance)
        { 
          $this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than your current balance'));
          front_redirect('withdraw/'.$cur, 'refresh');
        }
        if($amount < $currency->min_withdraw_limit)
        {
          $this->session->set_flashdata('error',$this->lang->line('Amount you have entered is less than minimum withdrawl limit'));
          front_redirect('withdraw/'.$cur, 'refresh');
        }
        elseif($amount>$currency->max_withdraw_limit)
        {
          $this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than maximum withdrawl limit'));
          front_redirect('withdraw/'.$cur, 'refresh');  
        }
        elseif($final!=1)
        {
          $this->session->set_flashdata('error',$this->lang->line('Invalid address'));
          front_redirect('withdraw/'.$cur, 'refresh');
        }
        else{
          $withdraw_fees_type = $currency->withdraw_fees_type;
              $withdraw_fees = $currency->withdraw_fees;

              if($withdraw_fees_type=='Percent') { $fees = (($amount*$withdraw_fees)/100); }
              else { $fees = $withdraw_fees; }
              $total = $amount-$fees;
          $user_status = 'Pending';

      $Ref = $user_id.'#'.strtotime(date('d-m-Y h:i:s'));   
      $insertData = array(
        'user_id'=>$user_id,
        'payment_method'=>$Payment_Method,
        'currency_id'=>$Currency_Id,
        'amount'=>$amount,
        'transaction_id'=>$Ref,
        'fee'=>$fees,
        'transfer_amount'=>$total,
        'datetime'=>gmdate(time()),
        'type'=>'Withdraw',
        'status'=>'Pending',
        'user_status'=>'Completed',
        'currency_type'=>'fiat',
        'payment_mode'=>'1',
        'account_number'=>$account_number,
        'account_name'=>$account_name,
        'bank_name'=>$bank_name,
        'bank_swift_code'=>$bank_swift,
        'bank_country'=>$bank_country,
        'bank_city'=>$bank_city,
        'bank_address'=>$bank_address,
        'bank_postalcode'=>$bank_postalcode,
        );
        
      $insertData_clean = $this->security->xss_clean($insertData);
      $insert = $this->common_model->insertTableData('transactions', $insertData_clean);
      if ($insert) {
        $finalbalance = $balance - $amount;
        $updatebalance = updateBalance($user_id,$Currency_Id,$finalbalance,'fiat');
        $insertData_clean = $this->security->xss_clean($insertData);
        
        $enc_email = getAdminDetails('1','email_id');
        $adminmail = decryptIt($enc_email);
        $prefix = get_prefix();
        $user = getUserDetails($user_id);
        $usernames = $prefix.'username';
        $username = $user->$usernames;
        // $email = getUserEmail($user_id);
        $currency_name = getcryptocurrency($Currency_Id);
        // $link_ids = encryptIt($insert);
        // $sitename = getSiteSettings('site_name');
        // $site_common      =   site_common();

        $email_template = 'Withdraw_request_fiat';
        $special_vars = array(
        '###USERNAME###' => $username,
        '###AMOUNT###'   => (float)$amount,
        '###CURRENCY###' => $currency_name,
        '###CONFIRM_LINK###' => front_url().'Th3D6rkKni8ht_2O22/withdraw/view/'.$insert,
        );
        $this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars); 

        $this->session->set_flashdata('success', 'Bank Wire withdrawl request has been received. Will Process your Payment within few Minutes');
        front_redirect('withdraw/'.$cur, 'refresh');
      }
      else {
        $this->session->set_flashdata('error', 'Unable to Process your Withdraw. Please contact Admin.');
        front_redirect('withdraw/'.$cur, 'refresh');
      }

      }

      }
    }
    

    }

    
	$this->load->view('front/user/withdraw', $data);
	
	

	}
	  
	
	function change_address_withdraw(){
		$user_id=$this->session->userdata('user_id');
		$currency_id = $this->input->post('currency_id');

		$Currency_detail = getcurrencydetail($currency_id);
		$data['balance']	=	getBalance($user_id,$currency_id);
		$data['symbol']		=	currency($currency_id);
		$data['transaction_fee']	=	(float)$Currency_detail->withdraw_fees;
		$data['minimum_withdrawal']	=	(float)$Currency_detail->min_withdraw_limit;		
		echo json_encode($data);
	}

    function buy_crypto()
	{ 	 
		$user_id = $this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url('home'));
		}
		
		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link'=>'dashboard'))->row();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['dig_currency'] = $this->common_model->getTableData('currency',array('wyre_currency'=>1,'status'=>1),'','','','','','',array('sort_order','ASC'))->result();

		$this->load->view('front/user/buy_crypto', $data);
	} 

	function buycrpy()
	{
		$user_id = $this->session->userdata('user_id');
		$currency_id = $this->input->post("currency");
		$amount = $this->input->post("amount");
		$currency_symbol	=	currency($currency_id);
		$Currency_detail = getcurrencydetail($currency_id);
		$currency_name = strtolower($Currency_detail->currency_name);
		$wyre_settings = $this->common_model->getTableData('wyre_settings',array('id'=>1))->row();
		$address = $currency_symbol."_address"; 
		$admincoin_address = $wyre_settings->$address;
		$userinfo = getUserDetails($user_id); 
		$country_id = $userinfo->country;
		$user_countries = $this->common_model->getTableData('countries',array('id'=>$country_id))->row();
		
		$useremal = getUserEmail($user_id);
		$user_countries->country_code;

		 $secert_key = decryptIt($wyre_settings->secret_key);
		 $referrerAccountId = decryptIt($wyre_settings->account_id);

			$postg = '{
    "amount":'.$amount.',
    "sourceCurrency":"USD",
    "destCurrency":"'.$currency_symbol.'",
    "referrerAccountId":"'.$referrerAccountId.'",
    "email":"'.$useremal.'",
    "dest":"'.$currency_name.':'.$admincoin_address.'",
    "firstName":"'.$userinfo->bidex_fname.'",
    "city":"'.$userinfo->city.'",
    "phone":"+'.$user_countries->phone_number.$userinfo->bidex_phone.'",
    "street1":"'.$userinfo->street_address.'",
    "country":"'.$user_countries->country_code.'",
    "redirectUrl":"'.$wyre_settings->redirect_url.'/'.base64_encode($user_id).'",
    "failureRedirectUrl":"'.$wyre_settings->failure_url.'/'.base64_encode($user_id).'",
    "paymentMethod":"debit-card",
    "state":"'.$userinfo->state.'",
    "postalCode":"'.$userinfo->postal_code.'",
    "lastName":"'.$userinfo->bidex_lname.'",
    "lockFields":[]
}';

$url = ($wyre_settings->mode==0)?'https://api.testwyre.com/v3/orders/reserve':'https://api.sendwyre.com/v3/orders/reserve';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postg);

			$headers = array();
			$headers[] = 'Authorization:Bearer '.$secert_key;
			$headers[] = 'Content-Type:application/json';
			//$headers[] = 'Postman-Token:7ad1cd47-a7bc-4126-9333-4983f4c6da5d';
			$headers[] = 'Cache-Control:no-cache';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch); 
			//$resp = json_decode($result); 			
			curl_close($ch);
			print_r($result);exit;
	} 


	function getresponse_wyre($userid)
	{
		$myarray = $_REQUEST;
		if(empty($myarray))
		{
			$this->session->set_flashdata('error',$this->lang->line('Something Went Wrong. Please try again.'));
			front_redirect('buy_crypto', 'refresh');
		}
		$status = $_REQUEST['status'];
		$user_id = base64_decode($userid);
		$userId = $user_id;
		$wyre_settings = $this->common_model->getTableData('wyre_settings',array('id'=>1))->row();
		if(strtoupper($status)=='COMPLETE' || strtoupper($status)=='PROCESSING')
        {
        	$amount = $_REQUEST['purchaseAmount'];
        	$source_amount = $_REQUEST['sourceAmount'];
        	$destination_currency = $_REQUEST['destCurrency'];
        	$source_currency = $_REQUEST['sourceCurrency'];
        	$transaction_id = $_REQUEST['transferId'];
        	$date_occur = $_REQUEST['createdAt'];
        	$payment_method = 'Wyre';
        	$description = $_REQUEST['dest'];
        	$pay_status = 'Completed';
	        $payment_status = 'Paid';

	        if($transaction_id!='')
	        { 
	        	$ch = curl_init();
	        	$url = ($wyre_settings->mode==0)?'https://api.testwyre.com/v2/transfer/':'https://sendwyre.com/v2/transfer/';

				curl_setopt($ch, CURLOPT_URL, $url.$transaction_id.'/track');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$headers = array();
				$headers[] = 'Content-Type:application/json';
				$headers[] = 'Cache-Control:no-cache';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$result = curl_exec($ch); 
				$resp = json_decode($result); 			
				curl_close($ch);
				//echo '<pre>';print_r($resp);
				if($resp->transferId == $transaction_id)
				{
					$fee = $resp->fee;
					$crypto_amount = $resp->destAmount;
					$rate = $resp->rate;

					$currency = $this->common_model->getTableData('currency',array('currency_symbol'=>$destination_currency))->row();
			        $userbalance = getBalance($userId,$currency->id);
				    $finalbalance = $crypto_amount+$userbalance;
				    // Update user balance	
				    $updatebalance = updateBalance($userId,$currency->id,$finalbalance,'');

				    $dataInsert = array(
					'user_id' => $user_id,
					'currency_id' => $currency->id,
					'currency_name' => $destination_currency,
					'amount' => $amount,
					'description' => 'Paid for '.$source_amount.' '.$source_currency,
					'type' => 'buy_crypto',
					'payment_method' => 'Wyre',
					'transfer_amount' => $crypto_amount,
					'transfer_fee' => $rate,
					'paid_amount' => $crypto_amount,
					'transaction_id'=>$transaction_id,
					'status' => $pay_status,
					'payment_status' => $payment_status,
					'currency_type' => 'crypto',
					'payment_type' => 'fiat',
					'datetime' => date("Y-m-d h:i:s")
					);				 
					$ins_id = $this->common_model->insertTableData('transactions', $dataInsert); 
					if($ins_id) 
					{
						$prefix = get_prefix();
						$user = getUserDetails($userId);
						$usernames = $prefix.'username';
						$username = $user->$usernames;
						$email = getUserEmail($userId);
						$link_ids = base64_encode($ins_id);
						$sitename = getSiteSettings('site_name');
						$site_common      =   site_common();
						$email_template   = 'Deposit_Complete';		
							$special_vars = array(
							'###SITENAME###' => $sitename,			
							'###USERNAME###' => $username,
							'###AMOUNT###'   => number_format($crypto_amount,8),
							'###CURRENCY###' => $destination_currency,
							'###MSG###' => $msg,
							'###STATUS###'	 =>	ucfirst($pay_status)
							);
						// USER NOTIFICATION
						$email = 'manimegalai@spiegeltechnologies.com';
						$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
						if($pay_status=='Pending')
						{
							$this->session->set_flashdata('error',$this->lang->line('Your Crypto Deposit Failed. Please try again.'));
						}
						
						else
						{
							$this->session->set_flashdata('success',$this->lang->line('Your Crypto Deposit successfully completed'));
						}
						front_redirect('buy_crypto', 'refresh');
					} 
					else 
					{
						$this->session->set_flashdata('error', $this->lang->line('Unable to submit your Fiat Deposit request. Please try again'));
						front_redirect('buy_crypto', 'refresh');
					}
				}
	        }
        }
        else
        {
        	$this->session->set_flashdata('error',$this->lang->line('Something Went Wrong. Please try again.'));
			front_redirect('buy_crypto', 'refresh');
        }
	} 

	function getfailureresponse_wyre($userid)
	{		
		$this->session->set_flashdata('error',$this->lang->line('Something Went Wrong. Please try again.'));
		front_redirect('buy_crypto', 'refresh');
	}  

function close_ticket($code='')
	{
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}

		$support= $this->common_model->getTableData('support', array('user_id' => $user_id, 'ticket_id'=>$code))->row();
		$id = $support->id;

		$updateData['close'] = '1';
		$condition = array('id' => $id);
		$update = $this->common_model->updateTableData('support', $condition, $updateData);
		if($update){
			$this->session->set_flashdata('success',$this->lang->line('Ticket Closed'));
			front_redirect('support', 'refresh');
		}
		else{
			$this->session->set_flashdata('error',$this->lang->line('Something Went Wrong. Please try again.'));
			front_redirect('support_reply/'.$code, 'refresh');
		}

	}

function paypeaks_status(){
	$Transactions= $this->common_model->getTableData('transactions', array('status'=>'Pending','user_status'=>'Completed', 'payment_mode'=>'1'))->result();

	foreach($Transactions as $trans){
		$PayPeaks = array();
		$PayPeaks['merchantid'] = $trans->merchant_id;
		$PayPeaks['refno'] = $trans->transaction_id;
		$PayPeaks['productcode'] = $trans->product_code;
		$user_id = $trans->user_id;
		$Currency_Id = $trans->currency_id;
		$Amount = $trans->amount;
		//echo $PayPeaks['refno']."<br/>";
		echo "<pre>";
		print_r(json_encode($PayPeaks));

		$Paypeaks_Response = paypeaks_status($PayPeaks);
		echo "<pre>";
		print_r($Paypeaks_Response);
		//exit();

		if(isset($Paypeaks_Response) && !empty($Paypeaks_Response)){
		if(isset($Paypeaks_Response->Responsecode) && $Paypeaks_Response->Responsecode=='01'){
			if($trans->type=='Deposit'){
				$userbalance = getBalance($user_id,$Currency_Id); 
			    $finalbalance = ($Amount)+($userbalance);
			    $updatebalance = updateBalance($user_id,$Currency_Id,$finalbalance,'');

			    $reserve_amount = getcryptocurrencydetail($Currency_Id);
				$final_reserve_amount = (float)$Amount + (float)$reserve_amount->reserve_Amount;
				$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $Currency_Id);

				$updateData['status'] = 'Completed';
				$condition = array('trans_id' => $trans->trans_id);
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);

				$prefix = get_prefix();
				$user = getUserDetails($user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($user_id);
				$sitename = getSiteSettings('site_name');
				$site_common      =   site_common();
				$email_template   = 'Fiat_Deposit';		
					$special_vars = array(
					'###SITENAME###' => $sitename,			
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $Amount,
					'###CURRENCY###' => 'GHS',
					'###MSG###' => 'Funds has been deposited successfully',
					'###STATUS###'	 =>	ucfirst('Completed')
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

				if($update){
					echo 'Deposit Transaction ID - '.$trans->trans_id.' Completed';
				}
			}
			else{
				$updateData['status'] = 'Completed';
				$condition = array('trans_id' => $trans->trans_id);
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);

				if($update){
					$prefix = get_prefix();
				$user = getUserDetails($user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($user_id);
				$sitename = getSiteSettings('site_name');
				$site_common      =   site_common();
				$email_template   = 'Withdraw_Complete';		
					$special_vars = array(
					'###SITENAME###' => $sitename,			
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $Amount,
					'###CURRENCY###' => 'GHS',
					'###TX###' => $trans->transaction_id
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

				echo 'Withdraw Transaction ID - '.$trans->trans_id.' Completed';
				}
			}
		}
		if($Paypeaks_Response->Responsecode=='9' || $Paypeaks_Response->Responsecode=='7' || $Paypeaks_Response->Responsecode=='8' || $Paypeaks_Response->Responsecode=='6'){
			$updateData['status'] = 'Cancelled';
				$condition = array('trans_id' => $trans->trans_id);
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);

				if($update){
					if($trans->type=='Withdraw'){
						$balance = getBalance($trans->user_id,$trans->currency_id,'fiat');

						$finalbalance = $balance+$trans->amount;
				$updatebalance = updateBalance($trans->user_id,$trans->currency_id,$finalbalance,'fiat');
					}
				}
		}
	}
	else{
		echo "<pre>";
		print_r($Paypeaks_Response);
	}
	}
}

function paypeaks_product(){

	$type = array('DEBIT','CREDIT','CARD');

	foreach($type as $value){

	$PayPeaks['ProductType'] = $value;

	$Paypeaks_Response = paypeaks_product($PayPeaks);
	echo "<pre>";
	print_r($Paypeaks_Response);

	if($Paypeaks_Response->ResponseCode=='00'){
		foreach($Paypeaks_Response->data as $data_val){
			$Product_Code = $data_val->Code;
			$Description = $data_val->Description;

			$Exp = explode(' - ', $Description);
			$Product_name = $Exp[0];
			$Product_mode = $Exp[1];

			$Row_Count = $this->common_model->getTableData('products',array('product_type'=>$value,'product_mode'=>$Product_mode,'product_code'=>$Product_Code,'product_name'=>$Product_name))->num_rows();
			if($Row_Count==0){

				$Product_data = array(
					'product_type'  => $value,
					'product_mode'         =>trim($Product_mode),
					'product_code'    =>trim($Product_Code),
					'product_name'       =>trim($Product_name)
					);
					$Product_data_clean = $this->security->xss_clean($Product_data);
					$Insert=$this->common_model->insertTableData('products', $Product_data_clean);
					if($Insert){
						echo "Product Code - ".$Product_Code." - Inserted<br/>";
					}


			}
			else{
						echo "Product Code - ".$Product_Code." - Already Exists<br/>";
					}
		}
	}


	}
	
}

function payment_status(){

	$user_id=$this->session->userdata('user_id');
	$trans_id = decryptIt($this->input->post('id'));
	//echo $trans_id;

	$Transactions= $this->common_model->getTableData('transactions', array('trans_id'=>$trans_id,'user_id'=>$user_id,'status'=>'Pending', 'payment_mode'=>'1'))->row();

	if(isset($Transactions) && !empty($Transactions)){

		$PayPeaks = array();
		$PayPeaks['merchantid'] = $Transactions->merchant_id;
		$PayPeaks['refno'] = $Transactions->transaction_id;
		$PayPeaks['productcode'] = $Transactions->product_code;
		$user_id = $Transactions->user_id;
		$Currency_Id = $Transactions->currency_id;
		$Amount = $Transactions->amount;

		$Paypeaks_Response = paypeaks_status($PayPeaks);

		if(isset($Paypeaks_Response) && !empty($Paypeaks_Response)){
		if(isset($Paypeaks_Response->Responsecode) && $Paypeaks_Response->Responsecode=='01'){
			if($trans->type=='Deposit'){
				$userbalance = getBalance($user_id,$Currency_Id); 
			    $finalbalance = ($Amount)+($userbalance);
			    $updatebalance = updateBalance($user_id,$Currency_Id,$finalbalance,'');

			    $reserve_amount = getcryptocurrencydetail($Currency_Id);
				$final_reserve_amount = (float)$Amount + (float)$reserve_amount->reserve_Amount;
				$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $Currency_Id);

				$updateData['status'] = 'Completed';
				$condition = array('trans_id' => $trans->trans_id);
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);

				

				if($update){

				$prefix = get_prefix();
				$user = getUserDetails($user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($user_id);
				$sitename = getSiteSettings('site_name');
				$site_common      =   site_common();
				$email_template   = 'Fiat_Deposit';		
					$special_vars = array(
					'###SITENAME###' => $sitename,			
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $Amount,
					'###CURRENCY###' => 'GHS',
					'###MSG###' => 'Funds has been deposited successfully',
					'###STATUS###'	 =>	ucfirst('Completed')
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

					$data['msg'] = 'Updated Successfully';
					$data['status'] = 'Completed';
				}
			}
			else{
				$updateData['status'] = 'Completed';
				$condition = array('trans_id' => $trans->trans_id);
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);

				if($update){
					$prefix = get_prefix();
				$user = getUserDetails($user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($user_id);
				$sitename = getSiteSettings('site_name');
				$site_common      =   site_common();
				$email_template   = 'Withdraw_Complete';		
					$special_vars = array(
					'###SITENAME###' => $sitename,			
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $Amount,
					'###CURRENCY###' => 'GHS',
					'###TX###' => $trans->transaction_id
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

				$data['msg'] = 'Updated Successfully';
				$data['status'] = 'Completed';
				}
			}
		}
		else if($Paypeaks_Response->Responsecode=='9' || $Paypeaks_Response->Responsecode=='7' || $Paypeaks_Response->Responsecode=='8' || $Paypeaks_Response->Responsecode=='6'){
			$updateData['status'] = 'Cancelled';
				$condition = array('trans_id' => $trans->trans_id);
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);

				if($update){
					if($trans->type=='Withdraw'){
						$balance = getBalance($trans->user_id,$trans->currency_id,'fiat');

						$finalbalance = $balance+$trans->amount;
				$updatebalance = updateBalance($trans->user_id,$trans->currency_id,$finalbalance,'fiat');
					}
				}
				$data['msg'] = $Paypeaks_Response->ResponseDesc;
				$data['status'] = 'Cancelled';
		}
		else{
			$data['msg'] = $Paypeaks_Response->ResponseDesc;
			$data['status'] = 'Pending';
		}
	}
	}
	else{
		$data['msg'] = 'No Transaction found';
		$data['status'] = 'Pending';
	}
	echo json_encode($data);

	}

	function settings_profile()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['site_common'] = site_common();
		$this->load->view('front/user/settings_profile', $data); 
	}

	function verify_email()
	{	
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		
		$activation_code = time().rand(); 
		$users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$uname = $users->bidex_username;
		$email = getUserEmail($users->id);
		
		$email_template = 'Registration';
		$site_common      =   site_common();
		$special_vars = array(
		'###USERNAME###' => $uname,
		'###LINK###' => front_url().'verify_user/'.$users->activation_code
		);
		$this->email_model->sendMail($email, '', '', $email_template, $special_vars);	
		$this->session->set_flashdata('success',$this->lang->line('Thank you for Verify. Please check your e-mail and click on the verification link.'));
		front_redirect('profile', 'refresh');
	}

	function download_pdf($coin, $adr) {
		$coin_address = base64_decode($adr);
		$data['First_coin_image'] =	"https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$coin_address&choe=UTF-8&chld=L";
		$data['coin_image'] = base_url() . 'assets/front/coins/'.$coin.'.jpg';
		$data['address'] = $coin_address;
		$data['coin_name'] = $coin;
		$html = $this->load->view('front/user/download_qr_pdf',$data, true);
		// echo $html;die;
		$pdfFilename = time().".pdf";
		$this->load->library('m_pdf');
		$this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($pdfFilename, "D");
	}

	function validaddress() {
		
		$address = $this->input->post('address');
		$result=0;
		$adrDet = $this->common_model->getRecord($address)->row();
		if(!empty($adrDet)){			
			$unserialize = unserialize($adrDet->address);
			if(in_array($address, $unserialize)) {
				$result = $address;
			} 
		}
			echo $result;
	}

	function crypto_address()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="") {	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		$data['crypto_address'] = $this->common_model->getTableData('address_book',array('user_id'=>$user_id))->result();
		$data['currency']=$this->common_model->getTableData('currency',array('type'=>'digital'),'currency_symbol')->result();
		// echo "<pre>";print_r($data['currency']);die;
		$data['site_common'] = site_common();
		$this->load->view('front/user/crypto_address', $data); 
	}
	function ajax_email()
	{		 
		$this->load->library('session');
			
		$user_id=$this->session->userdata('user_id');
		$email = getalluserEmail($user_id);
	
		
		
	}

	function transaction_history($currency_id)
	{	
		$currency = decryptIt($currency_id);
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="") {	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		$data['history']=$this->common_model->getTableData('transactions',array('user_id'=>$user_id,'currency_id'=>$currency),'','','','','','',array('trans_id','DESC'),'','',array('type'=>'Deposit,Withdraw'))->result();
		$data['site_common'] = site_common();
		$this->load->view('front/user/transaction_history', $data); 
	}

	function trade_ajax($currency)
	{
		$user_id = $this->session->userdata('user_id');
		$sym = decryptIt($currency);
		$get=$this->common_model->getTableData('currency',array('id'=>$sym),array('currency_symbol'))->row();
		$cur = $get->currency_symbol;
		
		$draw = $this->input->get('draw');
		$start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
		$search= $this->input->get("search");
        $search = $search['value'];
        $col = 0;
        $dir = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }
		$valid_columns = array(
            0=>'id',
            1=>'datetime',
            3=>'currency_name', 
            4=>'amount',
            5=>'Price',
            6=>'Fee',
            7=>'Total',
            8=>'Type',
            9=>'status'
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }

        if(!empty($search))
        { 
        	$like = " AND (d.currency_symbol LIKE '%".$search."%' OR e.currency_symbol LIKE '%".$search."%' OR a.amount LIKE '%".$search."%' OR a.Price LIKE '%".$search."%' OR a.Fee LIKE '%".$search."%' OR a.Total LIKE '%".$search."%' OR a.status LIKE '%".$search."%')";

        	$query = "SELECT a.*, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.bidex_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol FROM bidex_coin_order as a JOIN bidex_trade_pairs as b ON a.pair = b.id JOIN bidex_users as c ON a.userId = c.id JOIN bidex_currency as d ON b.from_symbol_id = d.id JOIN bidex_currency as e ON b.to_symbol_id = e.id WHERE a.userId = ".$user_id." AND a.pair_symbol LIKE '%".$cur."%' ".$like." ORDER BY a.tradetime DESC LIMIT ".$start.",".$length;

        	$countquery = $this->db->query("SELECT a.*, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.bidex_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol FROM bidex_coin_order as a JOIN bidex_trade_pairs as b ON a.pair = b.id JOIN bidex_users as c ON a.userId = c.id JOIN bidex_currency as d ON b.from_symbol_id = d.id JOIN bidex_currency as e ON b.to_symbol_id = e.id WHERE a.userId = ".$user_id." AND a.pair_symbol LIKE '%".$cur."%' ".$like." ORDER BY a.tradetime DESC");

        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();

        } else {
        	$query = "SELECT a.*, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.bidex_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol FROM bidex_coin_order as a JOIN bidex_trade_pairs as b ON a.pair = b.id JOIN bidex_users as c ON a.userId = c.id JOIN bidex_currency as d ON b.from_symbol_id = d.id JOIN bidex_currency as e ON b.to_symbol_id = e.id WHERE a.userId = ".$user_id." AND a.pair_symbol LIKE '%".$cur."%' ORDER BY a.tradetime DESC LIMIT ".$start.",".$length;

			$countquery = $this->db->query("SELECT a.*, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.bidex_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol FROM bidex_coin_order as a JOIN bidex_trade_pairs as b ON a.pair = b.id JOIN bidex_users as c ON a.userId = c.id JOIN bidex_currency as d ON b.from_symbol_id = d.id JOIN bidex_currency as e ON b.to_symbol_id = e.id WHERE a.userId = ".$user_id." AND a.pair_symbol LIKE '%".$cur."%' ORDER BY a.tradetime DESC");
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows(); 
        }
        $tt = $query;
        if($num_rows>0)
		{
            $basic_pairid =array(4,7,8,9,10);
			foreach($users_history->result() as $result){
                if($result->exchange_type==0){
                    $extype="Advance";
                } else{
                    $extype ="Basic";
                }
				$i++;
                if((in_array($result->pair, $basic_pairid))&&($result->ordertype=='instant')) {
                    $sym = $result->to_currency_symbol;
                    $sym1 = $result->from_currency_symbol;

                }else{
                    $sym = $result->from_currency_symbol;
                    $sym1 = $result->to_currency_symbol;

                }				
					$data[] = array(
					    $i, 
					    gmdate("d-m-Y h:i a", strtotime($result->datetime)),
						$result->from_currency_symbol.'-'.$result->to_currency_symbol,
						$result->Amount." ".$sym,
						$result->Price." ".$result->to_currency_symbol,
						$result->Fee." ".$result->to_currency_symbol,
						$result->Total." ".$sym1,
						$result->Type,
                        $extype,
						ucfirst($result->status)
					);
			    }
		}
		else
		{
			$data = array();
		}
		$output = array(
            "draw" => $draw,
            "recordsTotal" => $num_rows,
            "recordsFiltered" => $num_rows,
            "data" => $data,
            "query"=> $tt
        );
		echo json_encode($output);
	}

	function transaction_history_pdf($currency_id)
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="") {	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		$data['history']=$this->common_model->getTableData('transactions',array('user_id'=>$user_id,'currency_id'=>$currency_id),'','','','','','',array('trans_id','DESC'),'','',array('type'=>'Deposit,Withdraw'))->result();
		$html = $this->load->view('front/user/transaction_history_pdf', $data, true); 
		// echo $html;die;
		$pdfFilename = "Transaction_History-".time().".pdf";
		$this->load->library('m_pdf');
		$this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($pdfFilename, "D");
	}
	
	function address_book()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="") {	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		if ($this->input->post()) {
			$coin = $this->input->post('coin');
			$adr = $this->input->post('address');
			$filename = $this->input->post('filename');

			$bookArr = array('filename'=>$filename,'user_id'=>$user_id,'coin'=>$coin,'address'=>$adr);

			$get=$this->common_model->getTableData('address_book',array('user_id'=>$user_id,'coin'=>$coin,'address'=>$adr))->row();	
			if(empty($get)) {
				$this->common_model->insertTableData('address_book', $bookArr);
			} else {
				$fname = array('filename'=>$filename);
				$this->common_model->updateTableData('address_book',array('id'=>$get->id),$fname);
			}
			$this->session->unset_userdata('coin');
  			$this->session->unset_userdata('address');
			// echo "string";print_r($_POST);die;
		}
		front_redirect('wallet', 'refresh');
	}
	
	function add_address_book()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="") {	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		if ($this->input->post()) {
			$coin = $this->input->post('coin');
			$adr = $this->input->post('address');
			$filename = $this->input->post('filename');
			$coin_id = $this->input->post('coin_id');
			$wallet_type = $this->input->post('wallet_type');
			$email_address = $this->input->post('email_address');

			if($this->input->post('destination_tag')) $des_tag = $this->input->post('destination_tag');
			  else $des_tag = '';

			$bookArr = array('filename'=>$filename,'user_id'=>$user_id,'coin'=>$coin,'destination_tag'=>$des_tag,'address'=>$adr,'wallet_type'=>$wallet_type,'email_address'=>$email_address);
			// $get=$this->common_model->getTableData('address_book',array('user_id'=>$user_id,'coin'=>$coin,'address'=>$adr))->row();	
		
			if(empty($coin_id)) {
				$this->common_model->insertTableData('address_book', $bookArr);
				$this->session->set_flashdata('success', $this->lang->line('Added successfully')); 
			} else {
				$fname = array('filename'=>$filename);
				$this->common_model->updateTableData('address_book',array('id'=>$coin_id),$fname);
				$this->session->set_flashdata('success', $this->lang->line('Changed successfully')); 
			}
		}
		front_redirect('crypto_address', 'refresh');
	}

	function address_destroy() 
	{
  		$this->session->unset_userdata('coin');
  		$this->session->unset_userdata('address');
	}

	function delete_address($id) 
	{
		$data = array('id'=>$id);
		$this->common_model->deleteTableData('address_book', $data);
		$this->session->set_flashdata('success', $this->lang->line('Deleted successfully'));
		front_redirect('crypto_address', 'refresh'); 
	}


	function basic_trade()
	{
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['mycontroller'] = $this->outputData;
		$data['site_common'] = site_common();
		//$data['dig_currency'] = $this->common_model->getTableData('currency', array('status' => 1,'type'=>'digital'), '', '', '', '', '', '', array('id', 'ASC'))->result();
		$data['dig_currency'] = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('id', 'ASC'))->result();
		
		$currency_info=[];
		foreach ($data['dig_currency'] as $key => $cur) {
			
			$getInfo = $this->callAPI($cur->currency_symbol);
			$currency_info[$cur->currency_name] = $getInfo;
		}
		$data['currency_info'] = $currency_info;
		// echo "<pre>";  print_r($currency_info);die;

		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
		$this->load->view('front/user/cb_trade', $data);
	}

	function callAPI($currency) 
	{
		$url = 'https://api.pro.coinbase.com/products/'.$currency.'-EUR/stats';
		$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
	    $curlSession = curl_init();
	    curl_setopt($curlSession, CURLOPT_URL, $url);
	    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
	    curl_setopt($curlSession, CURLOPT_USERAGENT, $agent);
	    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
	    $jsonData = json_decode(curl_exec($curlSession));
	    curl_close($curlSession);
	    return $jsonData;
		// echo "<pre>";print_r($jsonData);die;
	}
	function whitepaper($currency) 
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="") {
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$filepath = FCPATH.'uploads/whitepaper/'.$currency.'_white_paper.pdf';
		if (!file_exists($filepath)) {
		    throw new Exception("File $filepath does not exist");
		}
		if (!is_readable($filepath)) {
		    throw new Exception("File $filepath is not readable");
		}
		http_response_code(200);
		header('Content-Length: '.filesize($filepath));
		header("Content-Type: application/pdf");
		// header('Content-Disposition: attachment; filename="downloaded.pdf"'); 
		readfile($filepath);
		die;
	}
	function basic_portfolio() 
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['site_common'] = site_common();
		$this->load->view('front/user/basic_portfolio', $data);
	}
	function basic_notification() 
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['site_common'] = site_common();
		$this->load->view('front/user/basic_notification', $data);
	}
	function basic_home() 
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['mycontroller'] = $this->outputData;
		$data['site_common'] = site_common();
		$currency_sym = array('BTC','ETH','BCH','USDT','LIR');
		$where_in = array('currency_symbol', $currency_sym);
		$currencyDet = $this->common_model->getTableData('currency','',array('id','currency_symbol','currency_name','image','type','online_usdprice','online_europrice'),'','','','','','','','',$where_in)->result();
		foreach ($currencyDet as $key => $cur) {
			$getInfo = $this->callAPI($cur->currency_symbol);
			$currencyDet[$key]->euro_price = $getInfo->last;
			$change = (($getInfo->last-$getInfo->open)/$getInfo->last)*100;
			$currencyDet[$key]->change_price = $change;
		}
		$data['currencyDet'] = $currencyDet;
		$data['history']=$this->common_model->getTableData('transactions',array('user_id'=>$user_id),'','','','','',5,array('trans_id','DESC'),'','',array('type'=>'Deposit,Withdraw'))->result();
		// echo "<pre>";print_r($data['history']);die;
		$this->load->view('front/user/basic_home', $data);
	}
	function get_chartData() 
	{
		$currency_sym = array('BTC','ETH','BCH','USDT');
		$where_in = array('currency_symbol', $currency_sym);
		$currencyDet = $this->common_model->getTableData('currency','','','','','','','','','','',$where_in)->result();

		$endDate = new \DateTime();
		$startDate = new \DateTime('-30 days');
		$charts=[];
		foreach ($currencyDet as $ckey => $cur) { 
			$symbol = $cur->currency_symbol;
			
			$end = $endDate->format('Y-m-d');
			$start = $startDate->format('Y-m-d');

			$url = 'https://api.pro.coinbase.com/products/'.$symbol.'-EUR/candles?start='.$start.'&end='.$end.'&granularity=86400';
			$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)";
		    $curlSession = curl_init();
		    curl_setopt($curlSession, CURLOPT_URL, $url);
		    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		    curl_setopt($curlSession, CURLOPT_USERAGENT, $agent);
		    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
		    $jsonData = json_decode(curl_exec($curlSession));
		    curl_close($curlSession);

		    $chartDet=[];
		    foreach ($jsonData as $key => $val) { 
		    	$time = date('Y-m-d', $val[0]);
		    	$price = $val[4];
		    	$chartDet[] = array($time, $price);
		    }
		    $Json_chartDet = json_encode($chartDet,true);
		    $charts[$cur->currency_name] = $Json_chartDet;
		    
		}
		$data['charts'] = json_encode($charts);
		echo $data['charts'];
		// echo "<pre>"; print_r($charts);
		
	}
	
	

	

	

	function basic_exchange($pair_symbol='') 
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}

		$data['user_id'] = $user_id;
	$data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
	$pair=explode('_',$pair_symbol);
	$pair_id=0;
	if(count($pair)==2)
	{
		$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$where = array('a.status'=>1,'b.status!='=>0,'c.status!='=>0,'b.currency_symbol'=>$pair[0],'c.currency_symbol'=>$pair[1]);
		$orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.*');
		if($orderprice->num_rows()==1)
		{
			$pair_details=$orderprice->row();
			$pair_id=$pair_details->id;
		}
	}
	if($pair_id==0)
	{
		$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$where = array('a.status'=>1,'b.status!='=>0,'c.status!='=>0);
		$orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.id,b.currency_symbol as fromcurrency,c.currency_symbol as tocurrency','','','','','',array('a.id','asc'))->row();
		$pair_url=$orderprice->fromcurrency.'_'.$orderprice->tocurrency;
		front_redirect('basic-exchange/'.$pair_url, 'refresh');
	}
	$data['tradeInfo'] = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_details->id))->row();
	
	// echo $pair_id;die;
	$data['pair']=$pair;
	$data['pair_id']=$pair_id;
	$data['pair_symbol']=$pair[0].'/'.$pair[1];
    $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
	$to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
	$data['from_currdet'] = $from_currency;
	$data['to_currdet'] = $to_currency;
	$data['apicheck'] = checkapi($pair_id);
	$data['pair_details'] = $pair_details;
	if ($user_id != 0) { 
	  $data['from_cur'] = number_format(getBalance($user_id,$pair_details->from_symbol_id), 8);
	  $data['to_cur'] = number_format(getBalance($user_id,$pair_details->to_symbol_id), 8);
	} else {
	  $data['from_cur'] = 0;
	  $data['to_cur'] = 0;
	}
    
	$this->trade_prices($pair_id,'trade');

	$data['pagetype'] = $this->uri->segment(1);
	$tradesym = $pair[0].'_'.$pair[1];
	
	$pair_currency = $this->common_model->customQuery("select id,from_symbol_id,to_symbol_id,lastPrice,priceChangePercent from bidex_trade_pairs where status='1' order by id DESC")->result();
	if(isset($pair_currency) && !empty($pair_currency))
	{
		$Pairs_List = array();
		foreach($pair_currency as $Pair_Currency)
		{
			$from_currency_det = getcryptocurrencydetail($Pair_Currency->from_symbol_id);
            $to_currency_det = getcryptocurrencydetail($Pair_Currency->to_symbol_id);
            $pairname = $from_currency_det->currency_symbol."/".$to_currency_det->currency_symbol;
            $pairurl = $from_currency_det->currency_symbol."_".$to_currency_det->currency_symbol;

            $Site_Pairs[$Pair_Currency->id] = array(
         		"currency_pair"	=> $pairname,
         		"price"	=>	($Pair_Currency->lastPrice!='')?$Pair_Currency->lastPrice:'0.000',
         		"change"	=> ($Pair_Currency->priceChangePercent!='')?$Pair_Currency->priceChangePercent:'0.000',
         		"pairurl"	=> $pairurl
            );
		}
		$data['Site_Pairs'] = array_reverse($Site_Pairs);
	}
	$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'trade'))->row();
	$data['currencies'] = $this->common_model->customQuery("select * from bidex_currency where status='1' and currency_symbol in ('BTC','ETH','BCH','XRP','BNB','EURO')")->result();
	$data['allcurrencies'] = $this->common_model->customQuery("select * from bidex_currency where status='1' ")->result();

		$data['site_common'] = site_common();
		$this->load->view('front/user/basic_exchange', $data);
	}

	function trade_prices($pair,$pagetype='')
	{
		$this->marketprice = marketprice($pair);
		$this->lastmarketprice = lastmarketprice($pair);
		$this->minimum_trade_amount = get_min_trade_amt($pair);
		$this->maker=getfeedetails_buy($pair);
		$this->taker=getfeedetails_sell($pair);
		$user_id=$this->session->userdata('user_id');
		if($user_id)
		{
			$this->user_id = $user_id;
			$this->user_balance = getBalance($user_id);
		} else {
			$this->user_id = 0;
			$this->user_balance = 0;
		}
	}

	// function basic_execute_order() 
	// {
	// 	$pair_id = $_POST['pair_id'];
	// 	$user_id = $_POST['user_id'];
	// 	$price = $_POST['price'];
	// 	$amount = $_POST['amount'];
	// 	$total = $_POST['total'];
	// 	$fee = $_POST['fee'];
	// 	$pagetype = $_POST['pagetype'];
	// 	$pair = $_POST['pair'];
	// 	$pair_id = $_POST['pair_id'];
	// 	$type = $_POST['type'];

	// 	$tradeInfo = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_id))->row();
	// 	$gettoBalance = getBalance($user_id,$tradeInfo->to_symbol_id);
	// 	$getfromBalance = getBalance($user_id,$tradeInfo->from_symbol_id);

	// 	if($type=='buy') {
	// 		$to_sum = $gettoBalance - $amount; 
	// 		$from_sum = $getfromBalance + $total;
	// 	} else {
	// 		$to_sum = $gettoBalance + $amount; 
	// 		$from_sum = $getfromBalance - $total;
	// 	}

	// 	// print_r($_POST);
	// 	// echo $to_sum.'----';
	// 	// echo $from_sum;
	// 	// die;

	// 	updateBalance($user_id, $tradeInfo->to_symbol_id, $to_sum); 
	// 	updateBalance($user_id, $tradeInfo->from_symbol_id, $from_sum);
	// 	$insertData = array(
	// 		'userId'    	=> $user_id,
	// 		'Amount'   		=> $total,
	// 		'Price'      => $price,
	// 		'Type'		=> 'buy',
	// 		'Fee'	=> $fee,
	// 		'Total'	=> $total,
	// 		'wallet'	=> $pagetype,
	// 		'orderDate'		=> date('Y-m-d h:i:s'),
	// 		'ordertype'		=> 'instant',
	// 		'pair'     		=> $pair,
	// 		'pair_symbol'	=> $pair_id,
	// 		'status' => 'active',
	// 	);
	// 	$this->common_model->insertTableData('bidex_basic_coin_order',$insertData);	
	// 	// print_r($insertData);
	// }

public function googlelogin()
	{
	// echo $this->session->userdata('user_id');die;


		$clientId ='226772384770-35k57c575lpg790v1lile3gtmp2q10pu.apps.googleusercontent.com'; //Google client ID
		$clientSecret ='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'; //Google client secret
		$redirectURL = 'https://bidexcrypto.com/googlelogin';
		
		//https://curl.haxx.se/docs/caextract.html

		//Call Google API
		$gClient = new Google_Client();
		$gClient->setApplicationName('bidex');
		$gClient->setClientId($clientId);
		$gClient->setClientSecret($clientSecret);
		$gClient->setRedirectUri($redirectURL);
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		
		if(isset($_GET['code']))
		{
			$gClient->authenticate($_GET['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
		}

		if (isset($_SESSION['token'])) 
		{
			$gClient->setAccessToken($_SESSION['token']);
		}
		
		if ($gClient->getAccessToken()) {
            $data = $google_oauthV2->userinfo->get();
			// echo "<pre>";
			// print_r($data);
			// die;

			$current_datetime = date('Y-m-d H:i:s');
			      
      $out=$data['id'];
      $email=$data['email'];
      $str=checkEmail($email,$out);
// echo "<pre>";
// 		 print_r($str);
// 		 exit();
      //$status= $this->common_model->getTableData("users",array("bidex_email"=>$str,"login_oauth_uid"=>$out))->row();
      if($str) {
      	$email=$data['email'];
      	$check = checkSplitEmail($email);
      	if ($check->verified != 1) {

                        $this->session->set_flashdata('error','Please check your email to activate Bidex account');
					front_redirect('login', 'refresh');
                    }else{

      	   $email=$data['email'];
	       $str=splitEmail($email);
	       $userdata = array(
	      'bidex_fname' => $data['given_name'],
	      'bidex_lname'  => $data['family_name'],
	      'bidex_email' => $str[1],
	      'profile_picture'=> $data['picture'],
	      'created_on' => $current_datetime
	     );
	    // echo "<pre>"; print_r($status);die;
	       $email=$str[1];
	       $result= $this->common_model->getTableData("users",array("bidex_email"=>$email))->row();
	       // print_r($id);
	       // exit();
	       $session_data = array('user_id' => $result->id); 
	       // print_r($session_data);
	       // exit();
	      $this->session->set_userdata($session_data);
	      // exit();
	     front_redirect('wallet', 'refresh');
	     }
      }
    
	else {
		$email=$data['email'];
		$check=checkSplitEmail($email);
		if($check)
		{
					$this->session->set_flashdata('error',$this->lang->line('Entered Email Address Already Exists'));
					front_redirect('signup', 'refresh');
		}else{
			$firstname=$data['given_name'];
		$activation_code = time().rand();
      	$email=$data['email'];
       $str=splitEmail($email);
       $ip_address = get_client_ip();
          $data = array(
	       'login_oauth_uid' => $data['id'],
	       'bidex_fname'  => $data['given_name'],
	       'bidex_lname'   => $data['family_name'],
	       'bidex_email'  => $str[1],
	       'profile_picture' => $data['picture'],
	       'created_on' =>gmdate(time()),
	       'activation_code'  => $activation_code,
	       'verified'    =>'0',
	       'register_from' =>'Website',
	       'ip_address'       =>$ip_address,
	       'browser_name'     =>getBrowser(),
	       'verification_level' =>'1'
	       );
       $user_data_clean = $this->security->xss_clean($data);
       $id=$this->common_model->insertTableData('users', $user_data_clean);
       $prefix='bidex_';
       $usertype=$prefix.'type';
       $id= $this->common_model->insertTableData('history', array('user_id'=>$id, $usertype=>encryptIt($str[0])));
       $this->common_model->last_activity('Registration',$id);
       $a=$this->common_model->getTableData('currency','id')->result_array();
					$currency = array_column($a, 'id');
					$currency = array_flip($currency);
					$currency = array_fill_keys(array_keys($currency), 0);
					$wallet=array('Exchange AND Trading'=>$currency);
					
					$this->common_model->insertTableData('wallet', array('user_id'=>$id, 'crypto_amount'=>serialize($wallet)));

					$b=$this->common_model->getTableData('currency',array('type'=>'digital'),'id')->result_array();
					$currency1 = array_column($b, 'id');
					$currency1 = array_flip($currency1);
					$currency1 = array_fill_keys(array_keys($currency1), 0);

					$this->common_model->insertTableData('crypto_address', array('user_id'=>$id,'status'=>0, 'address'=>serialize($currency1)));
					
					//$firstname=$data['given_name'];
					// check to see if we are creating the user
					$email_template = 'Registration';
					$site_common      =   site_common();
					$special_vars = array(
					'###USERNAME###' => $firstname,
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					$this->session->set_flashdata('success',$this->lang->line('Thank you for Signing up. Please check your e-mail and click on the verification link.'));
    //    $email=$str[1];
      
	   // $result= $this->common_model->getTableData("users",array("bidex_email"=>$email))->row();
    //    $session_data = array( 'user_id'  => $result->id); 

    //    $this->session->set_userdata($session_data);
       front_redirect('login', 'refresh');
      }

}

        } 
		else 
		{
            $url = $gClient->createAuthUrl();
		    header("Location: $url");
            exit;
        }
	}

	

	function fblogin(){

$sitesettings = $this->common_model->getTableData('site_settings', array('id'=>1))->row();
   $facebookappid='310946887745542';
   $facebooksecretkey='1b566483363a8cf3a383574ccff50820';
   $fb = new Facebook\Facebook([
          'app_id' => $facebookappid,
          'app_secret' => $facebooksecretkey,
          'default_graph_version' => 'v3.2',
        ]);

   $helper = $fb->getRedirectLoginHelper();

   $permissions = ['email']; 
// For more permissions like user location etc you need to send your application for review

   $loginUrl = $helper->getLoginUrl('https://www.bidexcrypto.com/fbcallback', $permissions);

    // $_SESSION['FBRLH_state']='cc0240ef5c87812ea24e499c41915919';

   // echo '<pre>';print_r($loginUrl ); die;
   header("location: ".$loginUrl);
}

function fbcallback(){
         $sitesettings = $this->common_model->getTableData('site_settings', array('id'=>1))->row();
        $facebookappid='310946887745542';
        $facebooksecretkey='1b566483363a8cf3a383574ccff50820';
        $fb = new Facebook\Facebook([
        'app_id' => $facebookappid,
        'app_secret' => $facebooksecretkey,
        'default_graph_version' => 'v3.2',
        ]);
        
        $helper = $fb->getRedirectLoginHelper();  
  
        try {  
            
            $accessToken = $helper->getAccessToken();  
            
        }catch(Facebook\Exceptions\FacebookResponseException $e) {  
          // When Graph returns an error  
          echo 'Graph returned an error: ' . $e->getMessage();  
          exit;  
        } catch(Facebook\Exceptions\FacebookSDKException $e) {  
          // When validation fails or other local issues  
          echo 'Facebook SDK returned an error: ' . $e->getMessage();  
          exit;  
        }  
 
 
        try {
          // Get the Facebook\GraphNodes\GraphUser object for the current user.
          // If you provided a 'default_access_token', the '{access-token}' is optional.
          $response = $fb->get('/me?fields=id,name,email,first_name,last_name,birthday,location,gender', $accessToken);
          //print_r($response);
          //exit;
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'ERROR: Graph ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'ERROR: validation fails ' . $e->getMessage();
          exit;
        }

         $prefix=get_prefix();
    
        // User Information Retrival begins................................................
        $me = $response->getGraphUser();



        /*$location = $me->getProperty('location');
        echo "Full Name: ".$me->getProperty('name')."<br>";
        echo "First Name: ".$me->getProperty('first_name')."<br>";
        echo "Last Name: ".$me->getProperty('last_name')."<br>";
        echo "Gender: ".$me->getProperty('gender')."<br>";
        echo "Email: ".$me->getProperty('email')."<br>";
        echo "Password: ".$me->getProperty('password')."<br>";
        echo "location: ".$location['name']."<br>";
        //echo "Birthday: ".$me->getProperty('birthday')->format('d/m/Y')."<br>";
        echo "Facebook ID: <a href='https://www.facebook.com/".$me->getProperty('id')."' target='_blank'>".$me->getProperty('id')."</a>"."<br>";
        $profileid = $me->getProperty('id');
        echo "</br><img src='//graph.facebook.com/$profileid/picture?type=large'> ";
        echo "</br></br>Access Token : </br>".$accessToken;*/

        $fbid=$me->getProperty('id');
        $fbemail=$me->getProperty('email');
        $firstname=$me->getProperty('first_name');
            if($fbemail!=''){
         $email=$me->getProperty('email');
         // $emailjoin=$email[1];
         $str=checkFacebook($email,$fbid);
         //$check=$this->common_model->getTableData('users',array('paypeaks_email'=>$emailjoin));
//$status= $this->common_model->getTableData("users",array("bidex_email"=>$emailjoin))->row();

    if($str) {
    	$email=$me->getProperty('email');
      	$check = checkSplitEmail($email);
      	if ($check->verified != 1) {

                        $this->session->set_flashdata('error','Please check your email to activate Bidex account');
					front_redirect('login', 'refresh');
                    }
      	else{
      	   $str=splitEmail($me->getProperty('email'));
	       $userdata = array(
	      'bidex_fname' => $me->getProperty('first_name'),
	      'bidex_lname'  => $me->getProperty('last_name'),
	      'bidex_email' => $str[1],
	      //'profile_picture'=> $data['picture'],
	      'created_on' => $current_datetime
	     );
	    // echo "<pre>"; print_r($status);die;
	       $email=$str[1];
	       $result= $this->common_model->getTableData("users",array("bidex_email"=>$email))->row();
	       $session_data = array('user_id'  => $result->id); 
	      $this->session->set_userdata($session_data);
	     front_redirect('wallet', 'refresh');
	     }
      } 

      else {
      	$femail=$me->getProperty('email');
		$check=checkSplitEmail($femail);
		if($check)
		{
					$this->session->set_flashdata('error',$this->lang->line('Entered Email Address Already Exists'));
					front_redirect('signup', 'refresh');
		}else{
      	$str=splitEmail($me->getProperty('email'));
      	$firstname=$me->getProperty('first_name');
       $ip_address = get_client_ip();
       $activation_code = time().rand();
          $data = array(
	       //'login_oauth_uid' => $data['id'],
	       'bidex_fname'  => $me->getProperty('first_name'),
	       'bidex_lname'   => $me->getProperty('last_name'),
	       'bidex_email'  => $str[1],
	       'bidex_password'   => $me->getProperty('password'),
	       //'profile_picture' => $data['picture'],
	       'activation_code'  => $activation_code,
	       'created_on' =>gmdate(time()),
	       'verified'    =>'0',
	       'register_from' =>'Website',
	       'ip_address'       =>$ip_address,
	       'browser_name'     =>getBrowser(),
	       'verification_level' =>'1',
	       'facebookid' => $me->getProperty('id')
	       );
       $user_data_clean = $this->security->xss_clean($data);
       $id=$this->common_model->insertTableData('users', $user_data_clean);
       $prefix='bidex_';
       $usertype=$prefix.'type';
       $id= $this->common_model->insertTableData('history', array('user_id'=>$id, $usertype=>encryptIt($str[0])));
       $this->common_model->last_activity('Registration',$id);
       $a=$this->common_model->getTableData('currency','id')->result_array();
					$currency = array_column($a, 'id');
					$currency = array_flip($currency);
					$currency = array_fill_keys(array_keys($currency), 0);
					$wallet=array('Exchange AND Trading'=>$currency);
					
					$this->common_model->insertTableData('wallet', array('user_id'=>$id, 'crypto_amount'=>serialize($wallet)));

					$b=$this->common_model->getTableData('currency',array('type'=>'digital'),'id')->result_array();
					$currency1 = array_column($b, 'id');
					$currency1 = array_flip($currency1);
					$currency1 = array_fill_keys(array_keys($currency1), 0);

					$this->common_model->insertTableData('crypto_address', array('user_id'=>$id,'status'=>0, 'address'=>serialize($currency1)));
					$firstname=$me->getProperty('first_name');

					// check to see if we are creating the user
					$email_template = 'Registration';
					$site_common      =   site_common();
					$special_vars = array(
					'###USERNAME###' => $firstname,
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					$this->session->set_flashdata('success',$this->lang->line('Thank you for Signing up. Please check your e-mail and click on the verification link.'));
       // $email=$str[1];
       
       // $session_data = array( 'user_id'  => $id); 

       // $this->session->set_userdata($session_data);
       front_redirect('login', 'refresh');
   }
      }



       // redirect('profile_settings', 'refresh');
         } 
  



 }

 


}