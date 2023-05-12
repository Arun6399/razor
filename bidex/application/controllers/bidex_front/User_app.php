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

class User_app extends CI_Controller {
	public $outputData;
	public function __construct()
	{	
		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation'));
		$this->load->library('session');
		$this->site_api = new Tradelib();
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
	public function block_ip()
    {
        $this->load->view('front/common/blockips');
    }
	function login()
	{		
		$user_id=$this->session->userdata('user_id');
		// if($user_id!="")
		// {	
		// 	front_redirect('', 'refresh');
		// }
		$data['site_common'] = site_common();
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
		$data['action'] = front_url() . 'login_user';		
		$this->load->view('front/user/login', $data);
	}
	public function login_check_app()
    {
        $ip_address = get_client_ip();
        $array = array('status' => 0, 'msg' => '');
        $this->form_validation->set_rules('login_detail', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('login_password', 'Password', 'trim|required|xss_clean');
        
        // When Post

        if ($this->input->post()) {

            //if ($this->form_validation->run()) {
            	
                $email = lcfirst($this->input->post('login_detail'));
                $password = $this->input->post('login_password');
                //$remember = $this->input->post('remember');
                $prefix = get_prefix();
                // Validate email
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $check = checkSplitEmail($email, $password);
                }
                if (!$check) {
                    //vv
                    $array['status'] = 0;
                    $array['msg'] = 'Enter Valid Login Details';
                } else {
                    if ($check->verified != 1) {
                    	$array['status'] = 0;
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
                                $array['msg'] = 'Welcome back . Logged in Successfully';
                                $array['user_id'] = $check->id;
                                $array['username'] = $check->bidex_username;
                                $array['profilepic'] = $check->profile_picture;
                                
                                $array['tfa_status'] = 0;
                            } else {
                            	$array['status'] = 0;
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
                            $array['user_id'] = $check->id;
                            $array['username'] = $check->bidex_username;
                            $array['profilepic'] = $check->profile_picture;
                            //$array['login_url'] = 'settings_profile';
                            //}
                        }
                    }    
                 
                }
            // } else {
            //     $array['msg'] = validation_errors();
            // }
        } else {
        	$array['status'] = '0';
            $array['msg'] = 'Login error';
        }
         echo json_encode($array);
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
	function forgot_check_app()
	{ 
		//$array=array('status'=>0,'msg'=>'');
		//$this->form_validation->set_rules('forgot_detail', 'Email or Phone', 'trim|required|xss_clean');
		// When Post
		if ($this->input->post())
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
					$array['status']='0';
					$array['msg']='User does not Exists';
				}
				else
				{
					    if ($check->verified != 1) {
                        
                        $array['status']='0';  
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
							$array['status']='1';
							$array['msg']= 'Password reset link is sent to your email';
						}

				}
				
		}
		else
		{
			$array['status']='0';
			$array['msg']='Login error';
		}	
		die(json_encode($array));
	}
	function reset_pw_user_app($code = NULL)
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
					//$this->form_validation->set_rules('reset_password', 'Password', 'trim|required|xss_clean');
					// When Post
					if ($this->input->post())
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
							//$this->session->set_flashdata('success',$this->lang->line('Password reset successfully'));
							$array['status']='1'; 
							$array['msg']= 'Password reset successfully';
							front_redirect('','refresh');
							
					}
					$data['action'] = front_url() . 'reset_pw_user/'.$code;
					
					$data['js_link'] = 'reset_password';
					//$this->load->view('front/user/reset_pwd', $data);
				}
				else
				{
					//$this->session->set_flashdata('error', $this->lang->line('Link Expired'));
					$array['status']='0'; 
					$array['msg']= 'Link Expired';
					front_redirect('', 'refresh');
				}
			}
			else
			{
				//$this->session->set_flashdata('error', $this->lang->line('Already reset password using this link'));
				$array['status']='0'; 
				$array['msg']= 'Already reset password using this link';
				front_redirect('', 'refresh');
			}
		}
		else
		{
			//$this->session->set_flashdata('error', $this->lang->line('Not a valid link'));
			$array['status']='0'; 
			$array['msg']= 'Not a valid link';
			front_redirect('', 'refresh');
		}
		echo json_encode($array);
	}
	function register()
	{		
		
		$data['site_common'] = site_common();
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
		$data['action'] = front_url() . 'signup';		
		$this->load->view('front/user/register', $data);
	}
	function signup_app()
	{		
				

		
					
				
		if(!empty($_POST))
		{ 
		
          
				$email = $this->db->escape_str(lcfirst($this->input->post('register_email')));
				$password = $this->db->escape_str($this->input->post('register_password'));
				$cpassword = $this->db->escape_str($this->input->post('register_cpassword'));
				$firstname = $this->db->escape_str($this->input->post('firstname'));
				$lastname = $this->db->escape_str($this->input->post('lastname'));
				$phone = $this->db->escape_str($this->input->post('phone'));
				$country = $this->db->escape_str($this->input->post('country'));
				//$agreement = $this->db->escape_str($this->input->post('agreement'));
				//$usertype = $this->db->escape_str($this->input->post('usertype'));
				$check=checkSplitEmail($email);
				$prefix=get_prefix();
				$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                 
                $refferalid=substr(str_shuffle($permitted_chars), 0, 10);
				//$check1=$this->common_model->getTableData('users',array($prefix.'username'=>$uname));
				if($check)
				{
					

					$array['msg']='Entered Email Address Already Exists';
					$array['status']='0';




				}
				else
				{				
					$Exp = explode('@', $email);
					$User_name = $Exp[0];

					$activation_code = time().rand(); 
					$str=splitEmail($email);
					$ip_address = get_client_ip();

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
					'referralid' => $refferalid,
					'activation_code'  => $activation_code,
					'verified'         =>'0',
					'register_from'    =>'Website',
					'ip_address'       =>$ip_address,
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
					

					// check to see if we are creating the user
					$email_template = 'Registration';
					$site_common      =   site_common();
					$special_vars = array(
					'###USERNAME###' => $firstname,
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
		

						$array['msg']='Thank you for Signing up. Please check your e-mail and click on the verification link';
				        $array['status']='1';

					
				}
			}
			else
			{
			

				$array['msg']='Please Enter Valid data';
				$array['status']='0';




			}	
			    echo json_encode($array);
		}
        
     

  
	
		//front_redirect('home', 'refresh');
	
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
		$tokenvalues = $this->session->userdata('tokenvalues');
		$depositvalues = $this->session->userdata('depositvalues');
		if(isset($tokenvalues) && !empty($tokenvalues))
		{
			$this->session->unset_userdata('tokenvalues');
		}
		if(isset($depositvalues) && !empty($depositvalues))
		{
			$this->session->unset_userdata('depositvalues');
		}
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
				
				$insertData['bidex_fname'] = $this->db->escape_str($this->input->post('fname'));
				if ($_FILES['file-upload-field']['name']!="") 
				{
					$imagepro = $_FILES['file-upload-field']['name'];
					if($imagepro!="" && getExtension($_FILES['file-upload-field']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["file-upload-field"],'uploads/user/'.$user_id,$this->input->post('file-upload-field'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
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
				$password= $this->db->escape_str($this->input->post('newpassword'));
				$insertData['bidex_password']=encryptIt($password);
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
					$insertData['bidex_fname'] = $this->db->escape_str($this->input->post('f_name'));
					//$insertData['bidex_lname'] = $this->db->escape_str($this->input->post('lastname'));
					$insertData['dob'] = $this->db->escape_str($this->input->post('dob'));
					$insertData['street_address'] = $this->db->escape_str($this->input->post('street_address'));
					$insertData['street_address_2'] = $this->db->escape_str($this->input->post('street_address_2'));
					$insertData['city'] = $this->db->escape_str($this->input->post('city'));
					//$insertData['state'] = $this->db->escape_str($this->input->post('state'));
					$insertData['postal_code'] = $this->db->escape_str($this->input->post('postal_code'));
					//$insertData['bidex_phone'] = $this->db->escape_str($this->input->post('phone'));
					//$insertData['national_tax_number'] = $this->db->escape_str($this->input->post('national_tax_number'));

				// 	$paypal_email = $this->input->post('paypal_email');
				// 	if(isset($paypal_email) && !empty($paypal_email)){
				// 	$insertData['paypal_email'] = $this->db->escape_str($paypal_email);
				// }				
					$insertData['verification_level'] = '2';
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['country']	 	   = $this->db->escape_str($this->input->post('country'));
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

    function editprofile_app()
	{		 
		
		$user_id = $this->input->post('user_id');
		//$user_id = 1;
		$get_users = $this->common_model->getTableData('users', array('id' => $user_id))->row();
		// print_r($get_users);
		// exit();
//$user_id = 1;
		//$data = array();
		if($user_id=="")
		{	
			
			$data['status'] = '0';
			$data['msg'] = 'Please Login';
			
		}
		else
		{
			
				if ($_FILES['profilepic']) 
				{
					$imagepro = $_FILES['profilepic']['name'];
					if($imagepro!="")
					{
						$uploadimage1=cdn_file_upload($_FILES["profilepic"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('profilepic')));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							
							$data['status'] = '0';
							$data['msg'] = 'Problem with profile picture';
							
						} 
					}				
					
				}
				else{ 
					$imagepro = $this->db->escape_str($this->input->post('profilepic'));
				}
				
				
			 
				$fname = $this->db->escape_str($this->input->post('fname'));
				$lname = $this->db->escape_str($this->input->post('lname'));
				$phone = $this->db->escape_str($this->input->post('phone'));
				$password= encryptIt($this->input->post('password'));
				//echo $password;
				$newpassword= encryptIt($this->input->post('newpassword'));
				$address= $this->db->escape_str($this->input->post('address'));
				$city= $this->db->escape_str($this->input->post('city'));
				$state= $this->db->escape_str($this->input->post('state'));
				$country= $this->db->escape_str($this->input->post('country'));
				$pincode= $this->db->escape_str($this->input->post('pincode'));
				// $insertData['profile_picture']=
				
				// $insertData['bidex_password']=encryptIt($password);
				// $insertData['bidex_cpassword']=encryptIt($newpassword);

					if($password==$get_users->bidex_password)
					{	
					
					$verification_level = '2';
					$verify_level2_date = gmdate(time());

					$insertData = array('bidex_fname'=>$fname,
                                        'bidex_lname'=>$lname,
                                        'bidex_phone'=>$phone,
                                        'bidex_password'=>$newpassword,
                                        'bidex_cpassword'=>$newpassword,
                                        'street_address'=>$address,
                                        'city'=>$city,
                                        'state'=>$state,
                                        'country'=>$country,
                                        'postal_code'=>$pincode,
                                        'verification_level'=>$verification_level,
                                        'verify_level2_date'=>$verify_level2_date,
                                        'profile_picture'=>$imagepro );
				//echo '<pre>';print_r($insertData);
				
					//$insertData_clean = $this->security->xss_clean($insertData);
					$insert=$this->common_model->updateTableData('users',array('id'=>$user_id), $insertData);	
					if ($insert){
						$data['status'] = '1';
						$data['msg'] = 'Profile details Updated Successfully';
						
					} else {
					
						$data['status'] = '0';
						$data['msg'] = 'Something there is a Problem .Please try again later';
					}
				
					}else{
						
					    $data['status'] = '0';
						$data['msg'] = 'Your old password is incorrect';
					}
			
		}

				echo json_encode($data);
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
	function address_verification()	{
	
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

   function address_verification_apps()	{
	
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
			
			if($image!="" && getExtension($_FILES['photo_id_1']['type']))
			{	
				$ext = getExtension($_FILES['photo_id_1']['type']);
				$Img_Size = $_FILES['photo_id_1']['size'];
				
				if($Img_Size>2000000){
					// $this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					// front_redirect('kyc', 'refresh');

					$array['status']='0';        
				    $array['msg']='File Size Should be less than 2 MB';
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
					// $this->session->set_flashdata('error',$this->lang->line('Problem with your document front id'));
					// front_redirect('kyc', 'refresh');

					$array['status']='0';        
				    $array['msg']='Problem with your document front';


				}
				$insertData['photo_id_1'] = $image;	
				// print_r($image);
				// exit();

			} 
			
			if($image1!="" && getExtension($_FILES['photo_id_2']['type']))
			{		
				$Img_Size1 = $_FILES['photo_id_2']['size'];
				if($Img_Size1>2000000){
					// $this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					// front_redirect('kyc', 'refresh');

					$array['status']='0';        
				    $array['msg']='File Size Should be less than 2 MB';
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
					// $this->session->set_flashdata('error',$this->lang->line('Problem with your document back id'));
					// front_redirect('kyc', 'refresh');

					$array['status']='0';        
				    $array['msg']='Problem with your document back';
				}
				$insertData['photo_id_2'] = $image1;
			} 

			if($image2!="" && getExtension($_FILES['photo_id_3']['type']))
			{		
				$Img_Size2 = $_FILES['photo_id_3']['size'];
				if($Img_Size2>2000000){
					// $this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
					// front_redirect('kyc', 'refresh');
					$array['status']='0';        
				    $array['msg']='File Size Should be less than 2 MB';
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
					// $this->session->set_flashdata('error',$this->lang->line('Problem with your document front id'));
					// front_redirect('kyc', 'refresh');
					$array['status']='0';        
				    $array['msg']='Problem with your document front';
				}
				$insertData['photo_id_3'] = $image2;
			}

			

			
							
			$insertData['verify_level2_date'] = gmdate(time());
			$insertData['verify_level2_status'] = 'Pending';
			$insertData['photo_1_status'] = 1;	   
			$insertData['photo_2_status'] = 1;
			$insertData['photo_3_status'] = 1;
			

			// echo "<pre>";print_r($insertData);die;
			$condition = array('id' => $user_id);
			$insertData_clean = $this->security->xss_clean($insertData);
			$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
			if($insert !='') {
				//$this->session->set_flashdata('success','Your details have been sent to our team for verification');
				//front_redirect('kyc', 'refresh');
				$array['status']='1';        
				$array['msg']='Your details have been sent to our team for verification';
			} 
            elseif($insert !='') {
				//$this->session->set_flashdata('success', 'Your Kyc Verification cancelled successfully');
				//front_redirect('kyc', 'refresh');
				$array['status']='0';        
				$array['msg']='Your Kyc Verification cancelled successfully';
			}
			else {
				//$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
				//front_redirect('kyc', 'refresh');
				$array['status']='0';        
				$array['msg']='Unable to send your details to our team for verification. Please try again later!';
			}
		}
		//$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		//$this->load->view('front/user/kyc', $data); 
		echo json_encode($array);
	}

	

	function address_verification_app()	{
	 $user_id=$this->input->post('user_id');

		$data=array();
		if($user_id=="")
		{	
			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
		}else{
	

            $image = $_FILES['photo_id_3']['name'];
                if($image!="")
                {        
                    $Img_Size = $_FILES['photo_id_3']['size'];
                    if($Img_Size>2000000){
                        $data['status']='0';
                        $data['msg'] = 'Front Address Proof Should be less than 2 MB';
                    }
                    $uploadimage=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
                    if($uploadimage)
                    {
                        $image=$uploadimage['secure_url'];
                    }
                    else
                    {
                        $data['status']='0';
                        $data['msg'] = 'Problem with your Front Address Proof';
                    }
                } 
                else
                {
                    $image = $this->input->post('photo_id_3');
                }
                

        




            $image2 = $_FILES['photo_id_4']['name'];
                if($image2!="")
                {        
                    $Img_Size2 = $_FILES['photo_id_4']['size'];
                    if($Img_Size2>2000000){
                        $data['status']='0';
                        $data['msg'] = 'Back Address Proof Should be less than 2 MB';
                    }
                    $uploadimage2=cdn_file_upload($_FILES["photo_id_4"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_2')));
                    if($uploadimage2)
                    {
                        $image2=$uploadimage2['secure_url'];
                    }
                    else
                    {
                        $data['status']='0';
                        $data['msg'] = 'Problem with your Back Address Proof';
                    }
                } 
                else
                {
                    $image2 = $this->input->post('photo_id_4');
                }
                
                $insertData = array();
                $insertData['photo_id_3'] = $image;
                $insertData['photo_id_4'] = $image2;                    
                $insertData['verify_level2_date'] = gmdate(time());
                $insertData['verify_level2_status'] = 'Pending';
                $insertData['photo_3_status'] = 1;
                $insertData['photo_4_status'] = 1;                    
                $condition = array('id' => $user_id);
                $insert2 = $this->common_model->updateTableData('users',$condition, $insertData);
                if($insert2) {
                    $data['status']='1';
                    $data['msg'] = 'Address Proof has been updated successfully';
                }
                else {

                    $data['status']='0';
                    $data['msg'] = 'Unable to send your  Address Proof to our team for verification. Please try again later!';
                }
}

		//$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		//$this->load->view('front/user/kyc', $data); 
		echo json_encode($data);
	}

	function id_verification_app()	{
	
		$user_id=$this->input->post('user_id');
$data=array();
		if($user_id=="")
		{	
			$array['status'] = 0;
			$array['msg'] = "You are not Logged in";
		}
		



            $image3 = $_FILES['photo_id_1']['name'];
                if($image3!="")
                {        
                    $Img_Size3 = $_FILES['photo_id_1']['size'];
                    if($Img_Size3>2000000){
                        $data['status']='0';
                        $data['msg'] = 'Front Id Proof Should be less than 2 MB';
                    }
                    $uploadimage3=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
                    if($uploadimage3)
                    {
                        $image3=$uploadimage3['secure_url'];
                    }
                    else
                    {
                        $data['status']='0';
                        $data['msg'] = 'Problem with your Front Id Proof';
                    }
                } 
                else
                {
                    $image3 = $this->db->escape_str($this->input->post('photo_id_1'));
                }
                


        


            $image4 = $_FILES['photo_id_2']['name'];
                if($image4!="")
                {        
                    $Img_Size4 = $_FILES['photo_id_2']['size'];
                    if($Img_Size4>2000000){
                        $data['status']='0';
                        $data['msg'] = 'Back Id Proof Should be less than 2 MB';
                    }
                    $uploadimage4=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_4')));
                    if($uploadimage4)
                    {
                        $image4=$uploadimage4['secure_url'];
                    }
                    else
                    {
                        $data['status']='0';
                        $data['msg'] = 'Problem with your Back Id Proof';
                    }
                } 
                else
                {
                    $image4 = $this->db->escape_str($this->input->post('photo_id_2'));
                }
                
                $insertData = array();
                $insertData['photo_id_1'] = $image3;
                $insertData['photo_id_2'] = $image4;                    
                $insertData['verify_level2_date'] = gmdate(time());
                $insertData['verify_level2_status'] = 'Pending';
                $insertData['photo_1_status'] = 1;  
                $insertData['photo_2_status'] = 1;                    
                $condition = array('id' => $user_id);
                $insert4 = $this->common_model->updateTableData('users',$condition, $insertData);
                if($insert4) {
                    $data['status']='1';
                    $data['msg'] = ' Id Proof has been updated successfully';
                }
                else {

                    $data['status']='0';
                    $data['msg'] = 'Unable to send your  Id Proof to our team for verification. Please try again later!';
                }

        

		//$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		//$this->load->view('front/user/kyc', $data); 
		echo json_encode($data);
	}

	function photo_verification_app()	{
	
		$user_id=$this->input->post('user_id');
		$data=array();
		if($user_id=="")
		{	
			$array['status'] = 0;
			$array['msg'] = "You are not Logged in";
		}
		

		if($_FILES['photo_id_5'])    {

            $image5 = $_FILES['photo_id_5']['name'];
                if($image5!="")
                {        
                    $Img_Size5 = $_FILES['photo_id_5']['size'];
                    if($Img_Size5>2000000){
                        $data['status']='0';
                        $data['msg'] = 'Selfie Photo Proof Should be less than 2 MB';
                    }
                    $uploadimage5=cdn_file_upload($_FILES["photo_id_5"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_5')));
                    if($uploadimage5)
                    {
                        $image5=$uploadimage5['secure_url'];
                    }
                    else
                    {
                        $data['status']='0';
                        $data['msg'] = 'Problem with your Selfie Photo Proof';
                    }
                } 
                else
                {
                    $image5 = $this->input->post('photo_id_5');
                }

                $insertData = array();
                $insertData['photo_id_5'] = $image5;                    
                $insertData['verify_level2_date'] = gmdate(time());
                $insertData['verify_level2_status'] = 'Pending';
                $insertData['photo_5_status'] = 1;                    
                $condition = array('id' => $user_id);
                $insert5 = $this->common_model->updateTableData('users',$condition, $insertData);
                if($insert5) {
                    $data['status']='1';
                    $data['msg'] = 'Selfie Photo Proof has been updated successfully';
                }
                else {

                    $data['status']='0';
                    $data['msg'] = 'Unable to send your Selfie Photo Proof to our team for verification. Please try again later!';
                }
            }
		//$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		//$this->load->view('front/user/kyc', $data); 
		echo json_encode($data);
	}

	function address_verification1() {
		$user_id=$this->session->userdata('user_id');
			if($user_id=="")
			{	
				front_redirect('', 'refresh');
			}
			if($_FILES)	{				
				$prefix=get_prefix();

				$image = $_FILES['photo_id_1']['name'];
					if($image!="" && getExtension($_FILES['photo_id_1']['type']))
					{		
						$Img_Size = $_FILES['photo_id_1']['size'];
						if($Img_Size>2000000){
							$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
							front_redirect('settings', 'refresh');
						}
						$uploadimage=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_1')));
						if($uploadimage)
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							
							$this->session->set_flashdata('error',$this->lang->line('Problem with your document front'));
							front_redirect('settings', 'refresh');
						}
					} 
					elseif($this->input->post('photo_ids_1')=='')
					{
						$image = $this->db->escape_str($this->input->post('photo_ids_1'));
					}
					else 
					{ 
						$image='';
					}
					$insertData = array();
					$insertData['photo_id_1'] = $image;					
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_1_status'] = 1;	                
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='' && $_FILES["photo_id_1"]['name'] !='') {
						$this->session->set_flashdata('success',$this->lang->line('Your details have been sent to our team for verification'));
						front_redirect('settings', 'refresh');
					} 
	                elseif($insert !='' && $_FILES["photo_id_1"]['name'] =='') {
						$this->session->set_flashdata('success', $this->lang->line('Your Address proof cancelled successfully'));
						front_redirect('settings', 'refresh');
					}
					else {
						$this->session->set_flashdata('error',$this->lang->line('Unable to send your details to our team for verification. Please try again later!'));
						front_redirect('settings', 'refresh');
					}
			}
	}
	function id_verification()	{
		$user_id=$this->session->userdata('user_id');
			if($user_id=="")
			{	
				front_redirect('', 'refresh');
			}
			if($_FILES)
			{
				$image = $_FILES['photo_id_2']['name'];
					if($image!="" && getExtension($_FILES['photo_id_2']['type']))
					{		

						$Img_Size = $_FILES['photo_id_2']['size'];
						if($Img_Size>2000000){
							$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
							front_redirect('settings', 'refresh');
						}

						$uploadimage=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_2')));
						if($uploadimage)
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error',$this->lang->line('Problem with your document back'));
							front_redirect('settings', 'refresh');
						}
					} 
					elseif($this->input->post('photo_ids_2')=='')
					{
						$image = $this->db->escape_str($this->input->post('photo_ids_2'));
					}
					else 
					{ 
						$image='';
					}
					$insertData = array();
					$insertData['photo_id_2'] = $image;
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_2_status'] = 1;
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='' && $_FILES["photo_id_2"]['name'] !='') {
						$this->session->set_flashdata('success',$this->lang->line('Your details have been sent to our team for verification'));
						front_redirect('settings', 'refresh');
					} 
	                elseif($insert !='' && $_FILES["photo_id_2"]['name'] =='') {
						$this->session->set_flashdata('success', $this->lang->line('Your ID proof cancelled successfully'));
						front_redirect('settings', 'refresh');
					}
					else {
						$this->session->set_flashdata('error',$this->lang->line('Unable to send your details to our team for verification. Please try again later!'));
						front_redirect('settings', 'refresh');
					}
			}
	}
	function photo_verification(){
		$user_id=$this->session->userdata('user_id');
			if($user_id=="")
			{	
				front_redirect('', 'refresh');
			}
			if($_FILES)
			{
				$image = $_FILES['photo_id_3']['name'];
					if($image!="" && getExtension($_FILES['photo_id_3']['type']))
					{		
						$Img_Size = $_FILES['photo_id_3']['size'];
						if($Img_Size>2000000){
							$this->session->set_flashdata('error',$this->lang->line('File Size Should be less than 2 MB'));
							front_redirect('settings', 'refresh');
						}

						$uploadimage=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
						if($uploadimage)
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', $this->lang->line('Problem with your scan of photo id'));
							front_redirect('settings', 'refresh');
						}
					} 
					elseif($this->input->post('photo_ids_3')=='')
					{
						$image = $this->db->escape_str($this->input->post('photo_ids_3'));
					}
					else 
					{ 
						$image='';
					}
					$insertData['photo_id_3'] = $image;
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_3_status'] = 1;
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='' && $_FILES["photo_id_3"]['name'] !='') {
						$this->session->set_flashdata('success',$this->lang->line('Your details have been sent to our team for verification'));
						front_redirect('settings', 'refresh');
					} 
	                elseif($insert !='' && $_FILES["photo_id_3"]['name'] =='') {
						$this->session->set_flashdata('success', $this->lang->line('Your Photo cancelled successfully'));
						front_redirect('settings', 'refresh');
					}
					else {
						$this->session->set_flashdata('error',$this->lang->line('Unable to send your details to our team for verification. Please try again later!'));
						front_redirect('settings', 'refresh');
					}
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

    //  public function settings_security_app(){

    //     if($this->block() == 1)
    //             { 
    //             front_redirect('block_ip');
    //             }
    //     $user_id=$this->session->userdata('user_id');


    //     if($user_id=="")
    //     {   
    //         //$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
    //         $data['status'] = 0;
    //         $data['msg'] = "You are not Logged in";
    //         //redirect(base_url().'home');
    //     }
        
    //     $this->load->library('Googleauthenticator');
    //     $users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
    //     if($users->randcode=="enable" || $users->secret!="")
    // { 
    //   $secret = $users->secret; 
    //   $data['secret'] = $secret;
    //   $ga     = new Googleauthenticator();
    //   $data['url'] = $ga->getQRCodeGoogleUrl('Bidex', $secret);

    // }
    // else
    // {
    //   $ga = new Googleauthenticator();
    //   $data['secret'] = $ga->createSecret();
    //   $data['url'] = $ga->getQRCodeGoogleUrl('Bidex', $data['secret']);
    //   $data['oneCode'] = $ga->getCode($data['secret']);
    // }
    
    // if(isset($_POST['tfa_sub']))
    // {

    //   $ga = new Googleauthenticator();
    //   $secret_code = $this->db->escape_str($this->input->post('secret'));
    //   $onecode = $this->db->escape_str($this->input->post('code'));

    //   $code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 6);
      

    //   if($users->randcode != "enable")
    //   {

     
    //     if($code==1)
    //     {
          
    //       $this->db->where('id',$user_id);
    //       $data1=array('secret'  => $secret_code,'randcode'  => "enable");
    //       $data1_clean = $this->security->xss_clean($data1);
    //       $this->db->update('users',$data1_clean);
              
    //       //$this->session->set_flashdata('success','TFA Enabled successfully');
    //       $data['status'] = 1;
    //       $data['msg'] = "TFA Enabled successfully";
    //       //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
    //       //front_redirect('settings_security', 'refresh');
    //     }
    //     else
    //     {
       
    //       //$this->session->set_flashdata('error','Please Enter correct code to enable TFA');
    //       $data['status'] = 0;
    //       $data['msg'] = "Please Enter correct code to enable TFA";
    //       //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
    //       //front_redirect('settings_security', 'refresh');
    //     }
    //   }
    //   else
    //   {

    //     if($code==1)
    //     {

    //       $this->db->where('id',$user_id);
    //       $data1=array('secret'  => $secret_code,'randcode'  => "disable");
    //       $data1_clean = $this->security->xss_clean($data1);
    //       $this->db->update('users',$data1_clean);  
    //       //$this->session->set_flashdata('success','TFA Disabled successfully');
    //       $data['status'] = 1;
    //       $data['msg'] = "TFA Disabled successfully";
    //       //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
    //       //front_redirect('settings_security', 'refresh');
    //     }
    //     else
    //     {
    //       $data['status'] = 0;
    //       $data['msg'] = "Please Enter correct code to disable TFA";
          
    //       //$this->session->set_flashdata('error','Please Enter correct code to disable TFA');
    //       //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
    //       //front_redirect('settings_security', 'refresh');
    //     }
    //   }
    // }

    //     echo json_encode($data);

    //    // $this->load->view('front/user/settings_security', $data); 
    // }

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
			$image = $_FILES['file-upload-field']['name'];
			if($image!="") {
				if(getExtension($_FILES['file-upload-field']['type']))
				{			
					$ext = getExtension($_FILES['file-upload-field']['type']);
				    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
					$uploadimage1=cdn_file_upload($_FILES["file-upload-field"],'uploads/user/'.$user_id);
					$upload_image=$uploadimage1['secure_url'];
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
			$insertData['subject'] = $this->input->post('subject');
			$insertData['message'] = $this->input->post('comments');
			$insertData['name'] = $this->input->post('name');
			$insertData['email'] = $this->input->post('email');
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
    function update_user_address()
    {
    	$Fetch_coin_list = $this->common_model->getTableData('bidex_currency use index (address)',array('type'=>'digital','status'=>'1'))->result();
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
    function get_user_list_coin($curr_id)
	{
		$users = $this->common_model->getTableData('users',array('verified'=>1), 'id','','','')->result();
		$rude = array();
		foreach($users as $user)
		{	
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
							'destination_tag'=>secret($user->id),
							'user_id'=>$user->id,
							'user_email'=>$email);
						array_push($rude, $balance[$user->id][$i]); 
					}		
				$i++;
			}
		}
		return $rude;	
	}
	public function get_user_with_dep_det($curr_id)
	{
		$users 	= $this->get_user_list_coin($curr_id);

		//echo "<pre>";print_r($users); exit;

		$currencydet = $this->common_model->getTableData('currency', array('id'=>$curr_id))->row();

		$orders = $this->common_model->getTableData('transactions', array('type'=>'Deposit', 'user_status'=>'Completed','currency_type'=>'crypto','currency_id'=>$curr_id))->result_array();
		$address_list = $transactionIds = array();
		//collect all users wallet address list
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

		return array('address_list'=>$address_list,'transactionIds'=>$transactionIds,'currency_decimal'=>$currencydet->currency_decimal);
	}
	// cronjob for deposit
	public function update_crypto_deposits($coin_name) // Ethereum
	{
		// error_reporting(E_ALL);
		$coin_name1 = $coin_name;
		$curr_id = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('id');
		$user_trans_res   = $this->get_user_with_dep_det($curr_id);
		$address_list     = $user_trans_res['address_list'];
		$transactionIds   = $user_trans_res['transactionIds'];
		$tot_transactions = array();


		$curr_symbol = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('currency_symbol');

		$valid_server =1;
		$coin_type = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('coin_type');
		$coinDetails = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('admin_move');

		// echo "<pre>Transactions"; print_r($user_trans_res);

		if($valid_server)
		{
			if($coin_type=="coin") // COIN PROCESS
			{ 
				switch ($coin_name) 
				{
					case 'Bitcoin':
						$transactions   = $this->local_model->get_transactions('Bitcoin');
						break;
					case 'BitcoinCash':
						$transactions   = $this->local_model->get_transactions('BitcoinCash');
						break;
					case 'Ripple':
						$transactions   = $this->local_model->get_transactions('Ripple',$user_trans_res);
						break;
					case 'Ethereum':
						$transactions 	 = $this->local_model->get_transactions('Ethereum',$user_trans_res);
						break;					
					default:
						show_error('No directory access');
						break;
			    }
		   }
		   else // TOKEN PROCESS
           { 
           		$transactions 	 = $this->local_model->get_transactions($coin_name,$user_trans_res);
           }

			// echo "<pre>Transactions"; print_r($transactions); echo "</pre>";			
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
			
			if(!empty($tot_transactions) && count($tot_transactions)>0)
			{
				$a=0;
				foreach ($tot_transactions as $row) 
				{
					$a++;$from_address='';
					// $account       = $row['account'];		
					$address       = $row['address'];
					$confirmations = $row['confirmations'];	
					 //$txid          = $row['txid'];
					$txid          = $row['txid'].'#'.$row['time'];
					//$time_st       = $row['time'];
					$time_st       = date("Y-m-d h:i:s",$row['time']);			
					$amount        = $row['amount'];
					$category      = $row['category'];		
					$blockhash 	   = (isset($row['blockhash']))?$row['blockhash']:'';
					$ind_val 	   = $address.'-'.$confirmations.'-'.$a;
					if($coin_name1=='Ethereum' || $coin_name1=='Tether' || $coin_name1=='eLira'){
					$from_address = $row['from'];
				}

				else{
					$from_address = '';
				}

					$admin_address = getadminAddress(1,$curr_symbol);


					$counts_tx = $this->db->query('select * from bidex_transactions where information="'.$row['blockhash'].'" and wallet_txid="'.$txid.'"')->num_rows();

					
					if( $category == 'receive' && $confirmations > 0 && $counts_tx == 0)
					{	

						if(isset($address_list[$address]))
						{
							if($coin_name1!='Ripple'){
							$user_id   = $address_list[$address]['user_id'];
						}
						else{
							$user_id = $row['user_id'];
							
						}
							$coin_name = "if".$address_list[$address]['currency_name'];
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
									$coin_name = "else".$value['currency_name'];
									$cur_sym   = $value['currency_symbol'];
									$cur_ids   = $value['currency_id'];
									$email 	   = $value['user_email'];
								}
							}
						}

						if(trim($from_address)!=trim($admin_address))
						{ 

						    if(isset($user_id) && !empty($user_id))
							{
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
									'description'		=> $coin_name1." Payment",
									'amount'     		=> $amount,
									'transfer_amount'	=> $amount,
									'information'		=> $blockhash,
									'wallet_txid'       => $txid,
									'crypto_address'	=> $address,
									'status'     		=> "Completed",
									'datetime' 			=> $time_st,
									'user_status'		=> "Completed",
									'transaction_id'	=> rand(100000000,10000000000),
									'datetime' 		=> (empty($txid))?$time_st:time()
								);
								$ins_id = $this->common_model->insertTableData('transactions',$dep_data);

								$prefix = get_prefix();
								$userr = getUserDetails($user_id);
								$usernames = $prefix.'username';
								$username = $userr->$usernames;
								$sitename = getSiteSettings('english_site_name');
								// check to see if we are creating the user
								$site_common      =   site_common();
						       	$email_template = 'Deposit_Complete';
						       	// echo $time_st.'----';
						       	// date('Y-m-d H:i:s',$time_st);
						       	// die;
								$special_vars	=	array(
									'###SITENAME###'  =>  $sitename,
									'###USERNAME###'    => $username,
									'###AMOUNT###' 	  	=> $amount,
									'###CURRENCY###'    => $cur_sym,
									'###HASH###'        => $blockhash,
									'###TIME###'        => $time_st,
									'###TRANSID###' 	=> $txid,
								);
						       
						       	if($ins_id !="" && $coinDetails==1 ) // ETH and Token
								{

									$this->transfer_to_admin_wallet($coin_name1);
								}
						    }
						} 
						elseif($from_address == $admin_address)
						{
						    if($coinDetails==1)
							{
								$this->transfer_to_admin_wallet($coin_name1);
							}
						}   
					}
					else
					{
						//echo"false";
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
		die(json_encode($result));
	}

	public function transfer_to_admin_wallet($coinname)
	{
	    $currency_det    =   $this->db->query("select * from bidex_currency where currency_name = '".$coinname."' limit 1")->row(); 

	    // print_r($currency_det);die;

	    if($currency_det->admin_move==1)
	    {
	    $currency_status = $currency_det->currency_symbol.'_status';
	   //$address_list    =  $this->db->query("select * from bidex_crypto_address where ".$currency_status." = '1' ")->result(); 
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
    function check_ethereum_functions($value)
	{
		$coin_name = 'Ethereum';
		$model_name = strtolower($coin_name).'_wallet_model';
		$model_location = 'wallets/'.strtolower($coin_name).'_wallet_model';
		$this->load->model($model_location,$model_name);
		if($value=='eth_gasPrice')
		{
			$parameter = "";
			$gas_price = $this->$model_name->eth_gasPrice($parameter);

			return $gas_price;
		}
		else
		{
			return '1';
		}

	}
	function withdraw_coin_user_confirm($id)
	{	
		// $trans_det = array('from'=>'0x0c5d2753c1b948c0d7d5518c25f14e1cd4442010','to'=>'0x318bd492695b20e5bc7e4f4e010ee41c9d467a4b','value'=>'4,151.25','gas'=>(float)'70000','gasPrice'=>'0.000000041');

		// $send_money_res = $this->local_model->make_transfer('Ethereum',$trans_det);
		// print_r($send_money_res);

		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
		}
		$id = decryptIt($id);
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'user_status'=>'Pending','status'=>'Pending'));

		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if($isValid > 0)
		{
			
			$fromid 	= $withdraw->user_id;
			$fromuser  = $this->common_model->getTableData('users',array('id'=>$fromid))->row();
			$fromacc   = getUserEmail($fromid);

			if($withdraw->user_status=='Completed')
			{
				$this->session->set_flashdata('error',$this->lang->line('Your withdraw request already confirmed'));
				front_redirect('wallet', 'refresh');
			}
			else if($withdraw->user_status=='Cancelled')
			{
				$this->session->set_flashdata('error',$this->lang->line('Your withdraw request already cancelled'));
				front_redirect('wallet', 'refresh');
			}
			elseif($withdraw->user_id != $user_id)
			{ 
				$this->session->set_flashdata('error',$this->lang->line('Your are not the owner of this withdraw request'));
				front_redirect('wallet', 'refresh');
			}
			else {
				if($withdraw->currency_id!=5){ 

					$amount 		= $withdraw->transfer_amount;
					$address 		= $withdraw->crypto_address;
					$currency 		= $withdraw->currency_id;
					$tagid = $withdraw->destination_tag;
					$coin_name  	= getcryptocurrencys($currency);
					$coin_symbol  	= getcryptocurrency($currency);
					$currency_det = getcryptocurrencydetail($currency);
				    $coin_type = $currency_det->coin_type;

					if($coin_type == "token" && $withdraw->wallet_type==0) // TOKEN
		            {

		            	$eth_id = $this->common_model->getTableData("currency",array("currency_symbol"=>"ETH"))->row('id');

        				$eth_admin_balance = getadminbalance(1,$eth_id);
						$mini_balance = "0.005";

						// echo "<pre>";print_r($eth_admin_balance);
						// echo $eth_admin_balance.'--'.$mini_balance;
						// die;
		                if($eth_admin_balance <= $mini_balance)
		                { 
		                    $this->session->set_flashdata('error',$this->lang->line('Your Ethereum Balance is low so you did not able to withdraw for Tether Token'));
		                    front_redirect('withdraw/'.$coin_symbol, 'refresh');
		                }
		            }
 
					$from_address1 = getadminAddress(1,$coin_symbol);
					$user_address = getAddress($withdraw->user_id,$withdraw->currency_id);

					$wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1); 
					
                    $currency_det = getcryptocurrencydetail($currency);
                    $coin_type = $currency_det->coin_type;
                    $coin_decimal = $currency_det->currency_decimal;
                    $decimal_places = coin_decimal($coin_decimal);

                    $coinDetails = $currency_det->admin_move;

					$wallet_bal = number_format((float)$wallet_bal,8);
					$amount = number_format($amount,8);
					$wallet_bal = str_replace(',', '', $wallet_bal);
					
					if($wallet_bal >= $amount)
					{
						if($coin_type=="coin")
						{
							switch ($coin_name) 
							{
								case 'Ethereum':
									$from_address = trim($from_address1);
									$to = trim($address);	
									$GasPrice = $this->check_ethereum_functions('eth_gasPrice');
					                $amount1 = $amount * 1000000000000000000;
					                $GasLimit = 70000;									
									$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
									
								break;
								case "Ripple":
								$xrp_tag_det = $this->common_model->getTableData('crypto_address', array('user_id' => $fromid))->row();
								$from_address = trim($from_address1);
									$to = trim($address);
								$trans_det = array('fromacc' => $fromacc, 'toaddress' => $to, 'amount' => (float) $amount, 'tagid' => $xrp_tag_det->payment_id, 'destag' => $tagid, 'secret' => $xrp_tag_det->auto_gen, 'comment' => 'User Confirms Withdraw', 'comment_to' => 'Completed');
								break;															
								default:
									$trans_det 		= array('address'=>$address,'amount'=>(float)$amount,'comment'=>'User Confirms Withdraw');
								break;
							}
					    }
					    else
					    {
							$from_address = trim($from_address1);
							$to = trim($address);	
							$GasPrice = $this->check_ethereum_functions('eth_gasPrice');
							// $GasPrice = 120 * 1000000000;
			                $amount1 = $amount * $decimal_places;
			                $GasLimit = 70000;	
							$trans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
					    }
					    /*echo "<pre>";
					    print_r($trans_det);
					    exit();*/
						
						if($withdraw->wallet_type==0){ // External
							$send_money_res = $this->local_model->make_transfer($coin_name,$trans_det);
							
						}
						else{ // Internal
							$uid = $withdraw->user_id;
							$cid = $withdraw->currency_id;
							$coin_adr = getAddress($uid,$cid);

							$send_money_res = 'Internal Transfer';
							$currency_sym = $currency_det->currency_symbol;
							$getuserid = $this->common_model->getTableData('crypto_address','','user_id',array('address'=>$withdraw->crypto_address))->row();

							$insertInternalData = array(
								'transaction_id'=>strtotime(date('d-m-Y h:i:s')),
								'user_id'=>$getuserid->user_id,
								'payment_method'=>'crypto',
								'currency_id'=>$currency,
								'amount'=>$amount,
								'transfer_amount'=>$amount,
								'datetime'=>gmdate(time()),
								'type'=>'Deposit',
								'crypto_address'=>$coin_adr,
								'status'=>'Completed',
								'user_status'=>'Completed',
								'currency_type'=>'crypto',
								'payment_mode'=>'0',
								);

							// echo "<pre>";print_r($getuserid);die;
							$insertdatas = $this->common_model->insertTableData('transactions', $insertInternalData);
							if ($insertdatas) {

								$userid = $getuserid->user_id;
								$balance = getBalance($userid,$currency,'crypto');
								$finalbalance = $balance+$amount;
								$updatebalance =updateBalance($userid,$currency,$finalbalance,'crypto');

								$prefix = get_prefix();
								$userr = getUserDetails($userid);
								$usernames = $prefix.'username';
								$username = $userr->$usernames;

								$email = getUserEmail($userid);
								$sitename = getSiteSettings('english_site_name');
								$site_common      =   site_common();
								$email_template   = 'Deposit_Complete';		
									$special_vars = array(
									'###SITENAME###' => $sitename,			
									'###USERNAME###' => $username,
									'###AMOUNT###'   => number_format($amount,8),
									'###CURRENCY###' => $currency_sym,
									'###MSG###' => '',
									'###STATUS###'	 =>	ucfirst('Completed')
									);
								$this->email_model->sendMail($email, '', '', $email_template, $special_vars);		
							}
						}
					// print_r($send_money_res);
					// die;	
						if($send_money_res){
							$updateData  = array('user_status'=>"Completed",'status'=>"Completed",'wallet_txid'=>$send_money_res);
						$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'crypto');
						$update = $this->common_model->updateTableData('transactions', $condition, $updateData);

						// Reserve amount
						$reserve_amount = getcryptocurrencydetail($withdraw->currency_id);
						$final_reserve_amount = (float)$reserve_amount->reserve_Amount + (float)$amount;
						$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $withdraw->currency_id);


						if($coinDetails==1 ) // Eth or Tokens
                        {
			                $admin_balance = getadminBalance(1,$withdraw->currency_id); // get admin balance
			                $admin_bal = $admin_balance - $withdraw->transfer_amount;
			                updateadminBalance(1,$withdraw->currency_id,$admin_bal); // update balance in admin wallet
		                }

						// add to transaction history
						$trans_data = array(
							'userId'=>$withdraw->user_id,
							'type'=>'Withdraw',
							'currency'=>$withdraw->currency_id,
							'amount'=>$withdraw->amount,
							'profit_amount'=>$withdraw->fee,
							'comment'=>'Withdraw #'.$withdraw->trans_id,
							'datetime'=>date('Y-m-d h:i:s'),
							'currency_type'=>'crypto',
						);
						$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);

							$prefix = get_prefix();
							$user = getUserDetails($user_id);
							$usernames = $prefix.'username';
							$username = $user->$usernames;
							$email = getUserEmail($user_id);
							$currency_name = getcryptocurrency($id);
							$sitename = getSiteSettings('english_site_name');
							$site_common      =   site_common();	
	                    
							$email_template = 'Withdraw_Complete';
							$special_vars = array(
								'###SITENAME###' => $sitename,			
								'###USERNAME###' => $username,
								'###AMOUNT###'   => $amount,
								'###CURRENCY###' => $reserve_amount->currency_symbol,
								'###TX###' => $isValids->transaction_id
							);
							
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

				$this->session->set_flashdata('success',$this->lang->line('Your withdraw request has been placed successfully.'));

				$sesArray = array('coin'=>$currency_det->currency_symbol, 'address'=>$withdraw->crypto_address);  
				$getAdrInfo=$this->common_model->getTableData('address_book',array('user_id'=>$user_id,'coin'=>$currency_det->currency_symbol,'address'=>$withdraw->crypto_address))->row();
				if(empty($getAdrInfo)) { 
					$this->session->set_userdata($sesArray);
				}
				front_redirect('wallet', 'refresh');
						}
						else{
							$this->session->set_flashdata('error',$this->lang->line('Please try again after some time or contact Admin.'));
							front_redirect('wallet', 'refresh');
						}

			} else {
				$this->session->set_flashdata('error',$this->lang->line('Your Balance is low so you did not able to withdraw'));
				front_redirect('wallet', 'refresh');
			}
		
		} else {

			// Bankwire Withdraw
			$updateData['user_status'] = 'Completed';
			$updateData['status'] = 'Pending';
			$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'fiat');
			$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
			$this->session->set_flashdata('success',$this->lang->line('Your withdraw request has been placed successfully.'));
			front_redirect('wallet', 'refresh');
		}
	  }
	
	}
	else
		{
			$this->session->set_flashdata('error',$this->lang->line('Invalid withdraw confirmation'));
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
		$id = decryptIt($id);
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'user_status'=>'Pending' ,'status'=>'Pending','currency_type'=>'crypto'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if($isValid > 0)
		{
			if($withdraw->user_status=='Completed')
			{
				$this->session->set_flashdata('error',$this->lang->line('Your withdraw request already confirmed'));
				front_redirect('wallet', 'refresh');
			}
			else if($withdraw->user_status=='Cancelled')
			{
				$this->session->set_flashdata('error',$this->lang->line('Your withdraw request already cancelled'));
				front_redirect('wallet', 'refresh');
			}
			elseif($withdraw->user_id != $user_id)
			{
				$this->session->set_flashdata('error',$this->lang->line('Your are not the owner of this withdraw request'));
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
				$this->session->set_flashdata('success',$this->lang->line('Successfully cancelled your withdraw request'));
				front_redirect('wallet', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error',$this->lang->line('Invalid withdraw confirmation'));
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
		$data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Deposit'),'','','','','','',array('trans_id','DESC'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
		$this->load->view('front/user/wallet', $data);
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
				$insertData['bank_account_name'] = $this->db->escape_str($this->input->post('bank_account_name'));
				$insertData['bank_account_number'] = $this->db->escape_str($this->input->post('bank_account_number'));
				$insertData['bank_name'] = $this->db->escape_str($this->input->post('bank_name'));
				$insertData['ifsc_code'] = $this->db->escape_str($this->input->post('ifsc_code'));
				if ($_FILES['file-upload-field']['name']!="") 
				{
					$imagepro = $_FILES['file-upload-field']['name'];
					if($imagepro!="" && getExtension($_FILES['file-upload-field']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["file-upload-field"],'uploads/user/'.$user_id,$this->input->post('file-upload-field'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
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

					front_redirect('settings_account', 'refresh');
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
				$insertData['bank_account_name'] = $this->db->escape_str($this->input->post('bank_account_name'));
				$insertData['bank_account_number'] = $this->db->escape_str($this->input->post('bank_account_number'));
				$insertData['bank_name'] = $this->db->escape_str($this->input->post('bank_name'));
				$insertData['ifsc_code'] = $this->db->escape_str($this->input->post('ifsc_code'));
				if ($_FILES['file-upload-field']['name']!="") 
				{
					$imagepro = $_FILES['file-upload-field']['name'];
					if($imagepro!="" && getExtension($_FILES['file-upload-field']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["file-upload-field"],'uploads/user/'.$user_id,$this->input->post('file-upload-field'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
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
				
				$insert = $this->common_model->updateTableData('user_bank_details',array('id'=>$user_id),$insertData_clean);
				

				
				if ($insert) {
					$this->session->set_flashdata('success', 'Bank details Updated Successfully');

					front_redirect('settings_account', 'refresh');
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
			$data['destination_tag'] = secret($user_id);
		}

		$data['all_currency'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('id','ASC'))->result();

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


		$clientId = '226772384770-35k57c575lpg790v1lile3gtmp2q10pu.apps.googleusercontent.com'; //Google client ID
		$clientSecret = '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'; //Google client secret
		$redirectURL = base_url() .'googlelogin';
		
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

      $status= $this->common_model->getTableData("users",array("login_oauth_uid"=>$out))->row();
      if($status) {
      	
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
	       $session_data = array('user_id'  => $status->id); 
	      $this->session->set_userdata($session_data);
	     front_redirect('settings_profile', 'refresh');
      } else {
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
	       'verified'    =>'0',
	       'register_from' =>'Website',
	       'ip_address'       =>$ip_address,
	       'browser_name'     =>getBrowser(),
	       'verification_level' =>'1',
	       'verified' => '1'
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
					

					// check to see if we are creating the user
					$email_template = 'Registration';
					$site_common      =   site_common();
					$special_vars = array(
					'###USERNAME###' => $firstname,
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					$this->session->set_flashdata('success',$this->lang->line('Thank you for Signing up. Please check your e-mail and click on the verification link.'));
       $email=$str[1];
       
       $session_data = array( 'user_id'  => $id); 

       $this->session->set_userdata($session_data);
       front_redirect('login', 'refresh');
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

   $loginUrl = $helper->getLoginUrl('https://spiegeltechnologies.org/bidex/fbcallback', $permissions);

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

        $fbemail=$me->getProperty('email');
            if($fbemail!=''){
         $email=splitEmail($me->getProperty('email'));
         $emailjoin=$email[1];
         //$check=$this->common_model->getTableData('users',array('paypeaks_email'=>$emailjoin));
$status= $this->common_model->getTableData("users",array("bidex_email"=>$emailjoin))->row();

    if($status) {
      	
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
	       $session_data = array('user_id'  => $status->id); 
	      $this->session->set_userdata($session_data);
	     front_redirect('settings_profile', 'refresh');
      } else {
      	$str=splitEmail($me->getProperty('email'));
       $ip_address = get_client_ip();
          $data = array(
	       //'login_oauth_uid' => $data['id'],
	       'bidex_fname'  => $me->getProperty('first_name'),
	       'bidex_lname'   => $me->getProperty('last_name'),
	       'bidex_email'  => $str[1],
	       'bidex_password'   => $me->getProperty('password'),
	       //'profile_picture' => $data['picture'],
	       'created_on' =>gmdate(time()),
	       'verified'    =>'0',
	       'register_from' =>'Website',
	       'ip_address'       =>$ip_address,
	       'browser_name'     =>getBrowser(),
	       'verification_level' =>'1',
	       'facebookid' => $me->getProperty('id'),
	       'verified' => '1'
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
					

					// check to see if we are creating the user
					$email_template = 'Registration';
					$site_common      =   site_common();
					$special_vars = array(
					'###USERNAME###' => $firstname,
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					$this->session->set_flashdata('success',$this->lang->line('Thank you for Signing up. Please check your e-mail and click on the verification link.'));
       $email=$str[1];
       
       $session_data = array( 'user_id'  => $id); 

       $this->session->set_userdata($session_data);
       front_redirect('login', 'refresh');
      }



       // redirect('profile_settings', 'refresh');
         } 
  



 }


 function social_app()
	{		
				

		
          
				$email = $this->input->post('email');
				
				$check=checkSplitEmail($email);
				$prefix=get_prefix();

				if($check)
				{
					
					$array['msg']='Entered Email Address Already Exists';
					$array['status']='0';




				}
				else
				{				
					$Exp = explode('@', $email);
					$User_name = $Exp[0];

					$activation_code = time().rand(); 
					$str=splitEmail($email);
					$ip_address = get_client_ip();

					$user_data = array(
					'usertype' => '1',
					$prefix.'email'    => $str[1],
					//$prefix.'username'	=> $this->input->post('firstname'),
					$prefix.'fname'	=> $this->input->post('firstname'),
					$prefix.'lname'	=> $this->input->post('lastname'),
					
					'profile_picture' => $this->input->post('profile'),
					'social_type' => $this->input->post('type'),
					'social_token' => $this->input->post('token'),
					'activation_code'  => $activation_code,
					'verified'         =>'0',
					'register_from'    =>'Website',
					'ip_address'       =>$ip_address,
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
					

					// check to see if we are creating the user
					$email_template = 'Registration';
					$site_common      =   site_common();
					$special_vars = array(
					'###USERNAME###' => $firstname,
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
		

						$array['msg']='Thank you for Signing up. Please check your e-mail and click on the verification link';
				        $array['status']='1';

					
				}
			
				
			    echo json_encode($array);
		}

		function social_login_app()
	{		
				

		
          
				$email = $this->input->post('email');
				
				$check=checkSplitEmail($email);
				$prefix=get_prefix();

				if(!$check)
				{
					

					$array['status'] = 0;
                    $array['msg'] = 'Enter Valid Login Details';




				}else{
					$result= $this->common_model->getTableData("users",array("bidex_email"=>$email))->row();
					$session_data = array(
                                'user_id' => $check->id,
                            );
                    $this->session->set_userdata($session_data);
                    $array['msg'] = 'Welcome back . Logged in Successfully';
                            $array['user_id'] = $check->id;
                            $array['firstname'] = $check->bidex_fname;
                            $array['profilepic'] = $check->profile_picture;

				}
				
			
				
			    echo json_encode($array);
		}

	function countries(){
		$data = array();
		$rude = array();

		$countries = $this->common_model->getTableData('country')->result();
		if(isset($countries)){
			$data['status'] = '1';
			$i=0;
			foreach($countries as $result){
                $countries_out[$i] = array(
                	'id'=>$result->id , 
                	'name'=>$result->name , 
                	'code'=>$result->phonecode  );
             array_push($rude, $countries_out[$i]);
				$i++;
			}
			$data['countries'] = $rude;
		}
		else{
			$data['status'] = '0';
			$data['countries'] = '';
		}

		echo json_encode($data);
	}


	function currency_home(){
			//$user_id=$this->session->userdata('user_id');
		$data = array();
		$rude = array();

		$trade_pairs_app = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
		if(isset($trade_pairs_app)){
			$data['status'] = '1';
			$i=0;
			foreach($trade_pairs_app as $result){

				$from_currency = $this->common_model->getTableData('currency',array('id' => $result->from_symbol_id))->row();
				$to_currency = $this->common_model->getTableData('currency',array('id' => $result->to_symbol_id))->row();
				$pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
				$currency = getcryptocurrencydetail($from_currency->id);

                $trade_pairs_app_out[$i] = array('id'=>$result->id , 'currency_image'=>$currency->image, 'pair_symbol'=>$pair_symbol, 'priceChangePercent'=>$result->priceChangePercent, 'lastprice'=>$result->lastPrice);
             array_push($rude, $trade_pairs_app_out[$i]);
				$i++;
			}
			$data['trade_pairs_app'] = $rude;
		}
		else{
			$data['status'] = '0';
			$data['trade_pairs_app'] = '';
		}

		echo json_encode($data);
	}

		function currency_wallet(){
			$user_id=$this->input->post('user_id');
		$data = array();
		$rude = array();

		$trade_pairs_app = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1),'','','','','','',array('id','ASC'))->result();
		if(isset($trade_pairs_app)){
			$data['status'] = '1';
			$i=0;
			foreach($trade_pairs_app as $result){
					$userbalance = abs(getBalance($user_id,$result->id));
				$USDT_Balance = abs($userbalance * $result->online_usdprice);
				
                $trade_pairs_app_out[$i] = array('id'=>$result->id , 'currency_image'=>$result->image, 'currency_name'=>$result->currency_name, 'currency_symbol'=>$result->currency_symbol,  'balance'=>$userbalance, 'online_usdprice'=>$USDT_Balance);
             array_push($rude, $trade_pairs_app_out[$i]);
				$i++;
			}
			$data['trade_pairs_app'] = $rude;
		}
		else{
			$data['status'] = '0';
			$data['trade_pairs_app'] = '';
		}

		echo json_encode($data);
	}
	public function newtradechart_check($pair_val)
    {
        $pair_val_file     = strtolower($pair_val);
	    $json_pair         = $pair_val_file.'.json';


	    	$str = file_get_contents(FCPATH."chart/".$json_pair);
	    // print_r($str);exit();
	    	return json_decode($str, true);
	    	// return $str;
	    // echo $str; exit;
    }

public function markets(){
$data=array();
$pairs = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
$pair= $this->common_model->getTableData('currency',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();

if(isset($pairs) && !empty($pairs)){
$json_data;	
$rude = array();$i=0;
foreach($pairs as $pair_details){
	$chart_data = array();
    $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
    $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
    $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
    $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
    $currency = getcryptocurrencydetail($from_currency->id);
    $pair_id = $pair_details->id;

        $timestamp = strtotime('today midnight');
        $end_date = date("Y-m-d H:i:s", $timestamp);
        $start_date = date('Y-m-d H:i:s', strtotime($end_date . '- 15 days'));
        
        $start = strtotime($start_date);

        $end = time();
        $enddate = date('Y-m-d H:i:s', $end);
        $interval = 1 / 2;
        $int = 1 * 60 * 60 * $interval;
        $chart = "";
        $chart1 = "";
        $chart2 = "";
        $chart3 = "";
        $chart4 = "";
        $chart5 = "";
        $chartdata = "";
        $pair_value = explode('_', $pair_url);
        $first_pair = $pair_value[0];
        $second_pair = $pair_value[1];

        $taken = date('Y-m-d H:i:s', strtotime($taken . ' - 15 days'));
        $startTime = strtotime($taken) * 1000;

        $destination = date('Y-m-d H:i:s');

        $endTime = strtotime($destination) * 1000;

//CALL API BINANCE
            $sec_pair = $second_pair;
            if ($sec_pair == "USD") {
                $sec_pair = "USDC";
            }
            $pairss = $first_pair . $sec_pair;
            $datetime = "";
            $close = "";
            $datetime1 = "";
            $close1 = "";
            $url = "https://api.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m&limit=100";
           // echo $url;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $res = json_decode($result, true);
            
            if ($res['code'] != '-1121') 
            {
                foreach ($res as $row) {
                    $datetime = substr($row['0'], 0, -3);
                    $datetime1 = $datetime;

                    $lastprice[$j] = array("last_price"=>$row['4'],
                							"datetime"=>date('d/m/Y H:i',$datetime1));
                    // dd/mm/yyyy hh:mm
                    array_push($chart_data,$lastprice[$j]);
                    $j++;
                }
               
            } 
                     $markets[$i] = array("symbol"=>$pair_symbol,
                     "pair_id" =>$pair_id,
                     "last_price"=>$pair_details->lastPrice,
                     "price_change"=>$pair_details->priceChangePercent,
                     "change_low"=>$pair_details->change_low,
                     "change_high"=>$pair_details->change_high,
                     "image"=>$from_currency->image, 
                     "from_cur" =>$from_currency->currency_symbol, 
                     "to_cur" =>$to_currency->currency_symbol, 
                     "volume"=>$pair_details->volume,
                     "linechart"=>$chart_data,
                     "trade_url"=>base_url().'trade/'.$pair_url);
		            array_push($rude, $markets[$i]); 

		            $i++;
        
            // $j=0;$rude=array();
  //       $names = array('filled');
  //       $where_in = array('status', $names);
		// $coinorder_data = $this->common_model->getTableData('coin_order', array('pair' => $pair_id), '', '', '', '', '', '', '', '', '', $where_in)->result();
		// // echo '<pre>';
		// // print_r($coinorder_data);
		// if (count($coinorder_data) > 5) 
  //       {
  //           $sec_pair = $second_pair;
  //           if ($sec_pair == "USD") {
  //               $sec_pair = "USDC";
  //           }
  //           $pairss = $first_pair . $sec_pair;
  //           // echo '<pre>';
  //           // echo $pairss;
  //           $datetimes = "";
  //           $closes = "";
  //           $datetimes1 = "";
  //           $closes1 = "";
  //           for ($i = $start; $i <= $end; $i += $int) {
  //               $taken = date('Y-m-d H:i:s', $i);
  //               $exp = explode(' ', $taken);
  //               $curdate = $exp[0];
  //               $time = $exp[1];
  //               $datetime = strtotime($taken);
  //               $date_time = strtotime($taken);
  //               $destination = date('Y-m-d H:i:s', strtotime($taken . ' +30 minutes'));
             
  //               $api_ClosechartResult = $this->common_model->getTableData('coin_order', array('datetime >= ' => $taken, 'datetime <= ' => $destination, 'pair' => $pair_id), 'Price as close,datetime', '', '', '', '', '', array('trade_id', 'DESC'), '', '', $where_in)->row();              
  //               if (isset($api_ClosechartResult)) {
  //                   $Close = $api_ClosechartResult->close;
  //                   $lastprice[$j] = array("last_price"=>$Close,
  //               							"datetime"=>date('d/m/Y H:i',$date_time));
  //                   array_push($chart_data,$lastprice[$j]);
  //                   $j++;
  //               }
  //           }
  //               $markets[$i] = array("symbol"=>$pair_symbol,
  //                        "pair_id" =>$pair_id,
  //                        "last_price"=>$pair_details->lastPrice,
  //                        "price_change"=>$pair_details->priceChangePercent,
  //                        "change_low"=>$pair_details->change_low,
  //                        "change_high"=>$pair_details->change_high,
  //                        "image"=>$from_currency->image, 
  //                        "from_cur" =>$from_currency->currency_symbol, 
  //                        "to_cur" =>$to_currency->currency_symbol, 
  //                        "volume"=>$pair_details->volume,
  //                        "linechart"=>$chart_data,
  //                        "trade_url"=>base_url().'trade/'.$pair_url);
  //               //echo json_encode($markets[$i]);
		// 	            array_push($rude, $markets[$i]); 

		// 	            $i++;
			
  //       } 
  //        else 
  //       { //CALL API BINANCE
  //           $sec_pair = $second_pair;
  //           if ($sec_pair == "USD") {
  //               $sec_pair = "USDC";
  //           }
  //           $pairss = $first_pair . $sec_pair;
  //           $datetime = "";
  //           $close = "";
  //           $datetime1 = "";
  //           $close1 = "";
  //           $url = "https://api.binance.com/api/v1/klines?symbol=" . $pairss . "&interval=1m&limit=100";
  //          // echo $url;
  //           $ch = curl_init();
  //           curl_setopt($ch, CURLOPT_URL, $url);
  //           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  //           $result = curl_exec($ch);
  //           $res = json_decode($result, true);
            
  //           if ($res['code'] != '-1121') 
  //           {
  //               foreach ($res as $row) {
  //                   $datetime = substr($row['0'], 0, -3);
  //                   $datetime1 = $datetime;

  //                   $lastprice[$j] = array("last_price"=>$row['4'],
  //               							"datetime"=>date('d/m/Y H:i',$datetime1));
  //                   // dd/mm/yyyy hh:mm
  //                   array_push($chart_data,$lastprice[$j]);
  //                   $j++;
  //               }
               
  //           } 
  //                    $markets[$i] = array("symbol"=>$pair_symbol,
  //                    "pair_id" =>$pair_id,
  //                    "last_price"=>$pair_details->lastPrice,
  //                    "price_change"=>$pair_details->priceChangePercent,
  //                    "change_low"=>$pair_details->change_low,
  //                    "change_high"=>$pair_details->change_high,
  //                    "image"=>$from_currency->image, 
  //                    "from_cur" =>$from_currency->currency_symbol, 
  //                    "to_cur" =>$to_currency->currency_symbol, 
  //                    "volume"=>$pair_details->volume,
  //                    "linechart"=>$chart_data,
  //                    "trade_url"=>base_url().'trade/'.$pair_url);
		//             array_push($rude, $markets[$i]); 

		//             $i++;
            
  //       }
}
$data['status'] = 1;
$data['msg'] = 'success';
$data['response'] = $rude;
 echo json_encode($data,JSON_UNESCAPED_SLASHES);
}	

}

// public function markets(){
// 	 // echo('fdfdf');exit();
// $data=array();
// $pairs = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
// $pair= $this->common_model->getTableData('currency',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
// $rude = array();$i=0;
// if(isset($pairs) && !empty($pairs)){
// foreach($pairs as $pair_details){
//     $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
//     $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
//     $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
//     $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
//     $currency = getcryptocurrencydetail($from_currency->id);
//     $pair_id = $pair_details->id;
//     $chart_recs = $this->newtradechart_check($pair_url);
//     echo "<pre>";
//     print_r($chart_recs); 
//     echo "<pre>";

//     // exit();
//     if($chart_recs){
//     	 foreach ($chart_recs as $row) {
//          // $datetime = substr($row['0'], 0, -3);
//                     $datetime1 = $row['0'];
//                     $lastprice[$j] = array("last_price"=>$row['4'],
//                 				"datetime"=>date('d/m/Y H:i',$datetime1));
//                     array_push($rude,$lastprice[$j]);
//                     $j++;
//     	 }
//     }


//     // $markets[$i] = array("symbol"=>$pair_symbol,
//     //                      "pair_id" =>$pair_id,
//     //                      "last_price"=>$pair_details->lastPrice,
//     //                      "price_change"=>$pair_details->priceChangePercent,
//     //                      "change_low"=>$pair_details->change_low,
//     //                      "change_high"=>$pair_details->change_high,
//     //                      "image"=>$from_currency->image, 
//     //                      "from_cur" =>$from_currency->currency_symbol, 
//     //                      "to_cur" =>$to_currency->currency_symbol, 
//     //                      "volume"=>$pair_details->volume,
//     //                      'current_price' =>$chart_recs['c'],
//     //                      'datetime' =>$chart_recs['t'],
//     //                      "trade_url"=>base_url().'trade/'.$pair_url);
//     //         array_push($rude, $markets[$i]); 

//     //         $i++;

//                             }

//                             $data['status']=1;
//                             $data['msg']='success';
//                             $data['linechart'] = $rude;
//                             // $data['markets']=$markets;
//                         }
//                         else{
//                         $data['status']=0;
//                             $data['msg']='No records found';    
//                         }

//             // echo json_encode($data);

// }

function deposits(){
		$user_id=$this->input->post('user_id');
		$currency_id=$this->input->post('currency_id');

		//$user_id = 1;
		$currency = $this->common_model->getTableData('currency', array('status' => 1,'id'=>$currency_id))->row();
  
    	$deposit_status = $currency->deposit_status;
		$data = array();
		if(!isset($user_id) && empty($user_id))
		{	
			

			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			//echo json_encode($data);   
			// exit();
		}
		else if($deposit_status == 0 ){
        	$data['status'] = 1;
			$data['msg'] = "Sorry this crypto Unavailable for Deposit";
        }
		else{
			$Users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

				$currency_id=$this->input->post('currency_id');

			if($currency_id!='')
			{
				$Currency = $this->common_model->getTableData('currency',array('status'=>'1','id' =>$currency_id))->row();

				$Balance = getBalance($user_id,$Currency->id); 
			$USD_balance = $Balance * $Currency->online_usdprice;
			$Crypto_address = getAddress($user_id,$Currency->id);
			$QR_Code = "https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$Crypto_address&choe=UTF-8&chld=L";

				$rude = array(
											"currency_id"=>$Currency->id,
												"currency_name"=>$Currency->currency_name,
												"currency_symbol"=>$Currency->currency_symbol,
												"currency_image"=>$Currency->image,
												"currency_type"=>$Currency->type,
												// "balance"=>(string)$Balance,
												// "balance_in_usd"=>(string)$USD_balance,
												"crypto_address"=>$Crypto_address,
												"qrcode"=>$QR_Code


				);

			}
			else {

			$Currency = $this->common_model->getTableData('currency',array('status'=>'1'))->result();

			$data['overall_balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

			$rude = array();$i=0;
			foreach($Currency as $Currency_list){
				

			$Balance = getBalance($user_id,$Currency_list->id);
			$USD_balance = $Balance * $Currency_list->online_usdprice;
			$Crypto_address = getAddress($user_id,$Currency_list->id);
			$QR_Code = "https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$Crypto_address&choe=UTF-8&chld=L";

			if($Currency_list->currency_symbol=='XRP')
				{
					$destination_tag = secret($user_id);
				}
				else{
					$destination_tag = 0;
				}

				$Currency_list_Val[$i] = array(	"currency_id"=>$Currency_list->id,
												"currency_name"=>$Currency_list->currency_name,
												"currency_symbol"=>$Currency_list->currency_symbol,
												"currency_image"=>$Currency_list->image,
												"currency_type"=>$Currency_list->type,
												// "balance"=>(string)$Balance,
												// "balance_in_usd"=>(string)$USD_balance,
												"crypto_address"=>$Crypto_address,
												"qrcode"=>$QR_Code);
												// "destination_tag"=>(string)$destination_tag);
				array_push($rude, $Currency_list_Val[$i]); 

				$i++;$Balance=0;$USD_balance=0;$Crypto_address=0;$QR_Code='';
			}
		}
			$data['status'] = '1';
			$data['msg']	=	'success';
			$data['deposit'] = $rude;
			$j=0;$rude1=array();
			

			// $data['deposit_history'] = $rude1;

			// $data['username'] = $Users->cripyic_username;
		}
		echo json_encode($data);
	}



function withdrawold(){


    error_reporting(0);
   // $this->load->library(array('form_validation','session'));

		// $user_id=$this->session->userdata('user_id');
    // $user_id=$this->input->post('user_id');
    $user_id='1';
    $currency=$this->input->post('currency_id');

	// exit();
		if($user_id=="")
		{	
		
			$data['msg']='you are not logged in';
			$data['status']='0';
		}



		// echo json_encode($data);
		// exit();

// echo "test";

// 		exit();


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
		$site_common= site_common();	
		$currency = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('id','ASC'))->result();	
		$users= $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		if(isset($cur) && !empty($cur)){
			$sel_currency = $this->common_model->getTableData('currency',array('currency_symbol'=>$cur),'','','','','','',array('id','ASC'))->row();

	   $test=$data['sel_currency']->withdraw_status;


      if($sel_currency->withdraw_status==0) {   
        // front_redirect('', 'refresh');
        $data['msg']='Withdraw Disabled';
        $data['status']='0';
      }

	 //  if($data['users']->phoneverified == '0' || $data['users']->email_verified == '0')
	 //  {
		// $this->session->set_flashdata('success', $this->lang->line('Enable both Email and Phone Verification to continue Withdraw.'));
		// redirect(base_url().'wallet');
	 //  }

			$selcsym = $cur;
			if($sel_currency->crypto_type_other != '')
			{
				$crypto_type_other_arr =explode('|',$sel_currency->crypto_type_other);
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
			$sel_currency = $this->common_model->getTableData('currency',array('status' => 1),'','','','','','',array('id','ASC'))->row();
			$selcsym = $sel_currency->currency_symbol;
			
			$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
			$data['fees'] = $data['sel_currency']->withdraw_fees;
            //$data['fees'] = apply_referral_fees_deduction($user_id,$data['sel_currency']->withdraw_fees);
		}
		
		$data['user_id'] = $user_id;
		
		$selcur_id = $sel_currency->id;
		
		$currency_balance = getBalance($user_id,$data['selcur_id']);
		$wallet = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));

		
		$withdraw_history = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();

		$payment_id = $this->common_model->getTableData('crypto_address', array('user_id'=>$user_id))->row();
		$dpayment_id=$payment_id->payment_id;


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
		   //      	$this->session->set_flashdata('error', "Please Fill your Bank Details");
					// front_redirect('withdraw/'.$cur, 'refresh');

					$data['msg']='Please Fill your Bank Details';
					$data['status']='0';




		        }	        
		        else 
		        {
		        	if($user_bank->status =='Pending'){
			   //      	$this->session->set_flashdata('error', "Please Wait for verification by our team");
						// front_redirect('withdraw/'.$cur, 'refresh');


						$data['msg']='Please Wait for verification by our team';
						$data['status']='1';



			        }
			        else if($user_bank->status =='Rejected'){
			   //      	$this->session->set_flashdata('error', "");
						// front_redirect('withdraw/'.$cur, 'refresh');


							$data['msg']='Your Bank details rejected by our team, Please contact support';
						    $data['status']='0';

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
						// $this->session->set_flashdata('error', 'Sorry!!! Your previous ') . $currency->currency_symbol . $this->lang->line('withdrawal is waiting for admin approval. Please use other wallet or be patience');
						// front_redirect('withdraw/'.$cur, 'refresh');

						$data['msg']='Sorry!!! Your previous withdrawal is waiting for admin approval. Please use other wallet or be patience';
						$data['status']='0';

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

							$data['msg']='Amount you have entered is more than your current balance';
							$data['status']='0';
							// $this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
							// front_redirect('withdraw/'.$cur, 'refresh');
						}
						if($amount < $min_withdraw_limit)
						{

                            $data['msg']='Amount you have entered is less than minimum withdrawl limit';
							$data['status']='0';

							// $this->session->set_flashdata('error','Amount you have entered is less than minimum withdrawl limit');
							// front_redirect('withdraw/'.$cur, 'refresh');
						}
						elseif($amount > $max_withdraw_limit)
						{ 

							 $data['msg']='Amount you have entered is more than maximum withdrawl limit';
							 $data['status']='0';

							// $this->session->set_flashdata('error', 'Amount you have entered is more than maximum withdrawl limit');
							// front_redirect('withdraw/'.$cur, 'refresh');	
						}
						elseif($final!=1)
						{   

								 $data['msg']='Invalid address';
							     $data['status']='0';

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


							    $data['msg']='Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!';
							    $data['status']='1';



								// $this->session->set_flashdata('success','Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!');
								// front_redirect('wallet', 'refresh');
							} 
							else 
							{

								    $data['msg']='Unable to submit your withdraw request. Please try again';
							        $data['status']='0';

								// $this->session->set_flashdata('error','Unable to submit your withdraw request. Please try again');
								// front_redirect('withdraw/'.$cur, 'refresh');
							}
						}
					}
				}
				else
				{

					 $data['msg']='Please check the address';
					 $data['status']='0';
					// $this->session->set_flashdata('error', 'Please check the address');
					// front_redirect('withdraw/'.$cur, 'refresh');
				}	
			/*}
			else
			{ 
				$this->session->set_flashdata('error', 'Please fill the correct values');
				front_redirect('withdraw/'.$cur, 'refresh');
			}*/
	    }
    // if(isset($_POST['withdraw_bank']))
    // {

    //     $this->form_validation->set_rules('currency', 'Currency', 'trim|required|xss_clean');
    //     $this->form_validation->set_rules('amount2', 'Amount', 'trim|required|xss_clean');

    //     // echo "<pre>"; print_r($_POST);die;
    //   if($this->form_validation->run()) {
    //     $Payment_Method = 'Bankwire';
    //     $Currency_Type = 'fiat';

    //   $Currency_Id = $this->db->escape_str($this->input->post('currency'));
    //   $account_number = $this->db->escape_str($this->input->post('account_number'));
    //   $account_name = $this->db->escape_str($this->input->post('account_name'));
    //   $bank_name = $this->db->escape_str($this->input->post('bank_name'));
    //   $bank_swift = $this->db->escape_str($this->input->post('bank_swift'));
    //   $bank_country = $this->db->escape_str($this->input->post('bank_country'));
    //   $payment_types = $this->db->escape_str($this->input->post('payment_types'));
    //   $amount = $this->db->escape_str($this->input->post('amount2'));
    //   $bank_city = $this->db->escape_str($this->input->post('bank_city'));
    //   $bank_address = $this->db->escape_str($this->input->post('bank_address'));
    //   $bank_postalcode = $this->db->escape_str($this->input->post('bank_postalcode'));

    //   $balance = getBalance($user_id,$Currency_Id,'fiat');
    //   $currency = getcryptocurrencydetail($Currency_Id);
    //   $w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Completed','currency_id'=>$Currency_Id));
    //     $count        = $w_isValids->num_rows();
    //           $withdraw_rec = $w_isValids->row();
    //             $final = 1;
                
    //        if($count>0)
    //   { 
    //     // $this->session->set_flashdata('error', $this->lang->line('Sorry!!! Your previous '). $currency->currency_symbol .' withdrawal is Pending. Please use other wallet or be patience');
    //     // front_redirect('withdraw/'.$cur, 'refresh');  

    //                  $data['msg']='Sorry!!! Your previous withdrawal is Pending. Please use other wallet or be patience';
				// 	 $data['status']='0';


    //   }
    //   else{
    //     if($amount>$balance)
    //     { 

    //     	         $data['msg']='Amount you have entered is more than your current balance';
				// 	 $data['status']='0';

    //       // $this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than your current balance'));
    //       // front_redirect('withdraw/'.$cur, 'refresh');

    //     }
    //     if($amount < $currency->min_withdraw_limit)
    //     {

    //     	         $data['msg']='Amount you have entered is less than minimum withdrawl limit';
				// 	 $data['status']='0';

    //       // $this->session->set_flashdata('error',$this->lang->line('Amount you have entered is less than minimum withdrawl limit'));
    //       // front_redirect('withdraw/'.$cur, 'refresh');
    //     }
    //     elseif($amount>$currency->max_withdraw_limit)
    //     {

    //     	         $data['msg']='Amount you have entered is more than maximum withdrawl limit';
				// 	 $data['status']='0';

    //       // $this->session->set_flashdata('error', $this->lang->line('Amount you have entered is more than maximum withdrawl limit'));
    //       // front_redirect('withdraw/'.$cur, 'refresh');  
    //     }
    //     elseif($final!=1)
    //     {

    //     	        $data['msg']='Invalid address';
				// 	 $data['status']='0';
    //       // $this->session->set_flashdata('error',$this->lang->line('Invalid address'));
    //       // front_redirect('withdraw/'.$cur, 'refresh');
    //     }
    //     else{
    //       $withdraw_fees_type = $currency->withdraw_fees_type;
    //           $withdraw_fees = $currency->withdraw_fees;

    //           if($withdraw_fees_type=='Percent') { $fees = (($amount*$withdraw_fees)/100); }
    //           else { $fees = $withdraw_fees; }
    //           $total = $amount-$fees;
    //       $user_status = 'Pending';

    //   $Ref = $user_id.'#'.strtotime(date('d-m-Y h:i:s'));   
    //   $insertData = array(
    //     'user_id'=>$user_id,
    //     'payment_method'=>$Payment_Method,
    //     'currency_id'=>$Currency_Id,
    //     'amount'=>$amount,
    //     'transaction_id'=>$Ref,
    //     'fee'=>$fees,
    //     'transfer_amount'=>$total,
    //     'datetime'=>gmdate(time()),
    //     'type'=>'Withdraw',
    //     'status'=>'Pending',
    //     'user_status'=>'Completed',
    //     'currency_type'=>'fiat',
    //     'payment_mode'=>'1',
    //     'account_number'=>$account_number,
    //     'account_name'=>$account_name,
    //     'bank_name'=>$bank_name,
    //     'bank_swift_code'=>$bank_swift,
    //     'bank_country'=>$bank_country,
    //     'bank_city'=>$bank_city,
    //     'bank_address'=>$bank_address,
    //     'bank_postalcode'=>$bank_postalcode,
    //     );
        
    //   $insertData_clean = $this->security->xss_clean($insertData);
    //   $insert = $this->common_model->insertTableData('transactions', $insertData_clean);
    //   if ($insert) {
    //     $finalbalance = $balance - $amount;
    //     $updatebalance = updateBalance($user_id,$Currency_Id,$finalbalance,'fiat');
    //     $insertData_clean = $this->security->xss_clean($insertData);
        
    //     $enc_email = getAdminDetails('1','email_id');
    //     $adminmail = decryptIt($enc_email);
    //     $prefix = get_prefix();
    //     $user = getUserDetails($user_id);
    //     $usernames = $prefix.'username';
    //     $username = $user->$usernames;
    //     // $email = getUserEmail($user_id);
    //     $currency_name = getcryptocurrency($Currency_Id);
    //     // $link_ids = encryptIt($insert);
    //     // $sitename = getSiteSettings('site_name');
    //     // $site_common      =   site_common();

    //     $email_template = 'Withdraw_request_fiat';
    //     $special_vars = array(
    //     '###USERNAME###' => $username,
    //     '###AMOUNT###'   => (float)$amount,
    //     '###CURRENCY###' => $currency_name,
    //     '###CONFIRM_LINK###' => front_url().'Th3D6rkKni8ht_2O22/withdraw/view/'.$insert,
    //     );
    //     $this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars); 
    //    $data['msg']='Bank Wire withdrawl request has been received. Will Process your Payment within few Minutes';
    //    $data['status']='1';

    //     // $this->session->set_flashdata('success', 'Bank Wire withdrawl request has been received. Will Process your Payment within few Minutes');
    //     // front_redirect('withdraw/'.$cur, 'refresh');
    //   }
    //   else {

    //   	       $data['msg']='Unable to Process your Withdraw. Please contact Admin.';
    //           $data['status']='0';

    //     // $this->session->set_flashdata('error', 'Unable to Process your Withdraw. Please contact Admin.');
    //     // front_redirect('withdraw/'.$cur, 'refresh');



    //   }

    //   }

    //   }
    // }
    

    // }

    
	// $this->load->view('front/user/withdraw', $data);
	
	echo json_encode($data);

}



function withdraw()
	{
 

		$user_id=$this->input->post('user_id');

		$currency_id=$this->input->post('currency_id');

		// echo json_encode($currency_id);
		// exit();	

		// $user_id =80;
		$currency = $this->common_model->getTableData('currency', array('status'=>1,'id'=>$currency_id))->row();
		// echo json_encode($currency);
		// exit();
  		$Users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
  		if($Users->verify_level2_status=="Completed"){
  			$stat=1;
  		}else{
  			$stat=0;
  		}
        $withdraw_status = $currency->withdraw_status;
		$data = array();
		if(!isset($user_id) && empty($user_id))
		{	
			

			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			echo json_encode($data);
			exit();
		}
		else if($withdraw_status == 0 ){
        	$data['status'] = 1;
			$data['msg'] = "Sorry this crypto Unavailable for withdraw";
        }
		else{
						
 
			$Currency_list = $this->common_model->getTableData('currency',array('status'=>'1','id'=>$currency_id))->row();
			$Currency_list_Val = array(	"currency_id"=>$Currency_list->id,
										"currency_name"=>$Currency_list->currency_name,
										"currency_symbol"=>$Currency_list->currency_symbol,
										"fees_type"   =>$Currency_list->withdraw_fees_type,
										"fees"        =>$Currency_list->withdraw_fees,
										"currency_image"=>$Currency_list->image,
										"currency_type"=>$Currency_list->type,
										"min_limit"=>$Currency_list->min_withdraw_limit,
										"max_limit"=>$Currency_list->max_withdraw_limit,
										'status'=>$stat
										// "balance"=>$Balance,
										// "balance_in_usd"=>$USD_balance,
												);

			// $data['overall_balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

			
			$data['status'] = '1';
			$data['msg']	=	'success';
			$data['withdraw'] = $Currency_list_Val;
			// $j=0;$rude1=array();
			// $withdraw_history = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();			
			// foreach($withdraw_history as $withdraw){
   //                        $Currency_Image = getcryptocurrencydetail($withdraw->currency_id)->image;
   //                        $Currency_Symbol = getcryptocurrency($withdraw->currency_id);

			// 	$deposit_history_list[$j]=array("currency_image"=>$Currency_Image,
			// 									"amount"=>$withdraw->amount,
			// 									"currency_symbol"=>$Currency_Symbol,
			// 									"status"=>$withdraw->status,
			// 									"datetime"=>date('d-M-Y H:i',$withdraw->datetime));

												

			// 	array_push($rude1, $deposit_history_list[$j]);
			// 	$j++;
			// }

			// $data['withdraw_history'] = $rude1;

			$data['username'] = $Users->bidex_username;
		}
		echo json_encode($data);

	 }



function wallets()
	{		 
        $user_id=$this->input->post('user_id');
        // $user_id=1;
		$data = array();

		$get_users = $this->common_model->getTableData('users', array('id' => $user_id), '', '', '', '', '', '', '')->result();		
		if(!isset($user_id) && empty($user_id))
		{	
		
			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			
		}
		else
		{
		$currency = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
		$rude = array();$i=0;
		// $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);
		
		foreach($currency as $Currency_list){

			// print_r($currency);exit()
			$Balance = getBalance($user_id,$Currency_list->id);
			$USD_balance = $Balance * $Currency_list->online_usdprice;
			$Currency_list_Val[$i] = array(	"currency_id"=>$Currency_list->id,
											"currency_name"=>$Currency_list->currency_name,
											"currency_symbol"=>$Currency_list->currency_symbol,
											"currency_image"=>$Currency_list->image,
											"currency_type"=>$Currency_list->type,
											"balance"=> (string)$Balance,
											"balance_in_usd"=>(string)$USD_balance
												);
				array_push($rude, $Currency_list_Val[$i]); 
				$i++;
			}
			$data['status'] = "1";
			$data['msg'] = "success";
			$data['wallet'] = $rude;

		}
		echo json_encode($data);


	}



	function usd_balance()
	{		 
  //       $user_id=$this->input->post('user_id');
  //       // $user_id=1;
		// $data = array();

		// $get_users = $this->common_model->getTableData('users', array('id' => $user_id), '', '', '', '', '', '', '')->result();		
		// if(!isset($user_id) && empty($user_id))
		// {	
		
		// 	$data['status'] = 0;
		// 	$data['msg'] = "You are not Logged in";
			
		// }
		// else
		// {
		// $currency = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
	 //        $i=0;
		// // $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);
		
		// foreach($currency as $Currency_list){

		// 	// print_r($currency);exit()
		// 	$Balance = getBalance($user_id,$Currency_list->id);

		// 	$USD_balance = $Balance * $Currency_list->online_usdprice;

		// 	$sum += $USD_balance;
		// 		$i++;
		// 	}
		

		// 	$data['usd_balance'] = $sum;

		// }
		// echo json_encode($data);


		     $user_id=$this->input->post('user_id');
        // $user_id=1;
		$data = array();

		$get_users = $this->common_model->getTableData('users', array('id' => $user_id), '', '', '', '', '', '', '')->result();		
		if(!isset($user_id) && empty($user_id))
		{	
		
			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			
		}
		else
		{
		$currency = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
		$rude = array();$i=0;
		// $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);
		
		foreach($currency as $Currency_list){
if($Currency_list->id=='1'){



			// print_r($currency);exit()
			$Balance = getBalance($user_id,$Currency_list->id);
			$USD_balance = $Balance * $Currency_list->online_usdprice;
			                  $Currency_list_Val[$i] = array(	
			                                    //"currency_id"=>$Currency_list->id,
												// "currency_name"=>$Currency_list->currency_name,
												"currency_symbol"=>$Currency_list->currency_symbol,
												// "currency_image"=>$Currency_list->image,
												// "currency_type"=>$Currency_list->type,
												"balance"=> (string)$Balance,
												"balance_in_usd"=>(string)$USD_balance
												);
				array_push($rude, $Currency_list_Val[$i]); 
				$i++;


			$data['status'] = "1";
			$data['msg'] = "success";
			$data['currency_symbol'] =$Currency_list->currency_symbol;
			$data['balance'] =(string)$Balance;
			$data['balance_in_usd'] =(string)$USD_balance;
}
			}
		
		}
		echo json_encode($data);



	}

	function currency_usd_balance()
	{		 
  //       $user_id=$this->input->post('user_id');
  //       // $user_id=1;
		// $data = array();

		// $get_users = $this->common_model->getTableData('users', array('id' => $user_id), '', '', '', '', '', '', '')->result();		
		// if(!isset($user_id) && empty($user_id))
		// {	
		
		// 	$data['status'] = 0;
		// 	$data['msg'] = "You are not Logged in";
			
		// }
		// else
		// {
		// $currency = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
	 //        $i=0;
		// // $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);
		
		// foreach($currency as $Currency_list){

		// 	// print_r($currency);exit()
		// 	$Balance = getBalance($user_id,$Currency_list->id);

		// 	$USD_balance = $Balance * $Currency_list->online_usdprice;

		// 	$sum += $USD_balance;
		// 		$i++;
		// 	}
		

		// 	$data['usd_balance'] = $sum;

		// }
		// echo json_encode($data);


		    $user_id=$this->input->post('user_id');
		    $currency_id=$this->input->post('currency_id');
        //$user_id=1;
        //$currency_id=1;
		$data = array();

		$get_users = $this->common_model->getTableData('users', array('id' => $user_id), '', '', '', '', '', '', '')->result();		
		if(!isset($user_id) && empty($user_id))
		{	
		
			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			
		}
		else
		{
		$currency = $this->common_model->getTableData('currency', array('status' => 1, 'id'=>$currency_id), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
		$rude = array();$i=0;
		// $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);
		
		foreach($currency as $Currency_list){




			// print_r($currency);exit()
			$Balance = getBalance($user_id,$Currency_list->id);
			$USD_balance = $Balance * $Currency_list->online_usdprice;
			                  $Currency_list_Val[$i] = array(	
			                                    //"currency_id"=>$Currency_list->id,
												"currency_name"=>$Currency_list->currency_name,
												"currency_symbol"=>$Currency_list->currency_symbol,
												"currency_image"=>$Currency_list->image,
												// "currency_type"=>$Currency_list->type,
												"balance"=> (string)$Balance,
												"balance_in_usd"=>(string)$USD_balance
												);
				array_push($rude, $Currency_list_Val[$i]); 
				$i++;


			$data['status'] = "1";
			$data['msg'] = "success";
			$data['currency_name'] =$Currency_list->currency_name;
			$data['currency_symbol'] =$Currency_list->currency_symbol;
			$data['currency_image'] =$Currency_list->image;
			$data['balance'] =(string)$Balance;
			$data['balance_in_usd'] =(string)$USD_balance;

			}
		
		}
		echo json_encode($data);



	}

	function transaction_history_app()
	{		 
        $user_id=$this->input->post('user_id');
        //$user_id=1;
		$data = array();

		$get_users = $this->common_model->getTableData('users', array('id' => $user_id), '', '', '', '', '', '', '')->result();		
		if(!isset($user_id) && empty($user_id))
		{	
		
			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			
		}
		else
		{
		$currency = $this->common_model->getTableData('transactions',array('user_id'=>$user_id),'','','','','','',array('trans_id','DESC'))->result();
		$rude = array();$i=0;
		// $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);

		$currency_total = $this->common_model->getTableData('transactions',array('user_id'=>$user_id),'','','','','','',array('trans_id','DESC'))->result();

	$total = count($currency_total);
// print_r($completed);
// exit();
			if($total == 0){
				$total = 0;
			}else{
				$total == $total;
			}

$currency_completed = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'status'=>'Completed'),'','','','','','',array('trans_id','DESC'))->result();

	$completed = count($currency_completed);
// print_r($completed);
// exit();
			if($completed == 0){
				$completed = 0;
			}else{
				$completed == $completed;
			}

			$currency_pending = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'status'=>'Pending'),'','','','','','',array('trans_id','DESC'))->result();
			// print_r($currency_pending);
			// exit();
	$pending = count($currency_pending);

			if($pending == 0){
				$pending = 0;
			}else{
				$pending == $pending;
			}

	$currency_cancel = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'status'=>'Cancelled'),'','','','','','',array('trans_id','DESC'))->result();
	$cancel = count($currency_cancel);

			if($cancel == 0){
				$cancel = 0;
			}else{
				$cancel == $cancel;
			}
		
		foreach($currency as $Currency_list){

$from_currency = $this->common_model->getTableData('currency',array('id' => $Currency_list->currency_id))->row();
		
			$Currency_list_Val[$i] = array(	"transaction_id"=>$Currency_list->transaction_id,
												"datetime"=>date('d-M-Y H:i',$Currency_list->datetime),
												"type"=>$Currency_list->type,
												"currency_symbol"=>getcryptocurrency($Currency_list->currency_id),
												"currency_image"=>$from_currency->image,
												"amount"=>$Currency_list->amount,
												"balance"=>$Currency_list->transfer_amount,
												"status"=> $Currency_list->status
												
												);
				array_push($rude, $Currency_list_Val[$i]); 
				$i++;
			}
			$data['status'] = "1";
			$data['msg'] = "success";
			$data['transaction_history'] = $rude;
			$data['total_count'] = $total;
			$data['success_count'] = $completed;
			$data['pending_count'] = $pending;
			$data['cancel_count'] = $cancel;

		}
		echo json_encode($data);


	}

	public function trades(){
        
 $data=array();
		$pairs = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
		$pair= $this->common_model->getTableData('currency',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
		$user_id = $this->input->post('user_id');
		
	$rude = array();$i=0;$j=0;

	if(isset($pairs) && !empty($pairs)){
    foreach($pairs as $pair_details){

    	$from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
        $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
        $order = $this->common_model->getTableData('currency')->result();
        $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
        $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
        $currency = getcryptocurrencydetail($from_currency->id);
		$pair_id = $pair_details->id;
		$favourites = $this->common_model->getTableData('favourite_pairs',array('user_id'=>$user_id,'pair_id'=>$pair_id))->row();
		if(!empty($favourites)){
			$fav_status = "1";
		}else{
			$fav_status = "0";
		}


     
			

        $markets[$i] = array("symbol"=>$pair_symbol,
							 "pair_id" =>$pair_id,
							 "last_price"=>$pair_details->lastPrice,
							 "price_change"=>$pair_details->priceChangePercent,
							 "image"=>$from_currency->image, 
							 "from_cur" =>$from_currency->currency_symbol, 
							 "to_cur" =>$to_currency->currency_symbol, 
							 "volume"=>$pair_details->volume,
							 "trade_url"=>base_url().'trade/'.$pair_url,
							 "fav_status"=>$fav_status);
				array_push($rude, $markets[$i]); 

				$i++;

                                }
                      foreach($order as $res){


			$coin[$j] = array( "currency_name"=>$res->currency_name,
								"currency_symbol"=>$res->currency_symbol

			);
			array_push($rude,$coin[$j]); 

				$j++;

     }

                                $data['status']=1;
                                $data['msg']='success';
                                $data['response']=$markets;
                                $data['list']=$coin;
                            }
                            else{
                            $data['status']=0;
                                $data['msg']='No records found';	
                            }

                echo json_encode($data);

}
function withdraw_web_coin()
{

	$user_id=$this->input->post('user_id');
		$data = array();
		if(!isset($user_id) && empty($user_id))
		{	
					

					$data['status'] = 0;
					$data['msg'] = "You are not Logged in";
					
		}
		else {
			$id = $this->db->escape_str($this->input->post('ids'));
			$amount = $this->db->escape_str($this->input->post('amount'));
			$address = $this->db->escape_str($this->input->post('address'));
			$Payment_Method = 'crypto';
			$Currency_Type = 'crypto';
			$Bank_id = '';
					if($id==6){
						$Destination_Tag = $this->db->escape_str($this->input->post('destination_tag'));
					}
					else{
								$Destination_Tag = '';
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

						$data['status'] = 0;
						$data['msg']	 = "Sorry!!! Your previous Withdrawl is in pending";
						echo json_encode($data);
						exit();

						
					}
					else
					{
						if($amount>$balance)
						{ 

							$data['status'] = 0;
							$data['msg']	 = "Amount you have entered is more than your current balance";
							echo json_encode($data);
							exit();

							$this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
							front_redirect('withdraw/'.$cur, 'refresh');
						}
						if($amount < $currency->min_withdraw_limit)
						{
							$data['status'] = 0;
							$data['msg']	 = "Amount you have entered is less than minimum withdrawl limit";
							echo json_encode($data);
							exit();

							
						}
						elseif($amount>$currency->max_withdraw_limit)
						{

							$data['status'] = 0;
							$data['msg']	 = "Amount you have entered is more than maximum withdrawl limit";
							echo json_encode($data);
							exit();

							
						}
						elseif($final!=1)
						{

							$data['status'] = 0;
							$data['msg']	 = "Invalid address";
							echo json_encode($data);
							exit();

							
						}
						else
						{
							$withdraw_fees_type = $currency->withdraw_fees_type;
					        $withdraw_fees = $currency->withdraw_fees;

					        if($withdraw_fees_type=='Percent') { $fees = (($amount*$withdraw_fees)/100); }
					        else { $fees = $withdraw_fees; }
					        $total = $amount-$fees;
					        $Ref = $user_id.'#'.strtotime(date('d-m-Y h:i:s'));
							$user_status = 'Pending';
							$insertData = array(
								'user_id'=>$user_id,
								'payment_method'=>$Payment_Method,
								'currency_id'=>$id,
								'amount'=>$amount,
								'transaction_id'=>$Ref,
								'fee'=>$fees,
								'bank_id'=>$Bank_id,
								'crypto_address'=>$address,
								'destination_tag'=>$Destination_Tag,
								'transfer_amount'=>$total,
								'datetime'=>date("Y-m-d H:i:s"),
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
								$sitename = getSiteSettings('english_english_site_name');
								$site_common      =   site_common();		                    

									$email_template = 'Withdraw_User_Complete';
									$special_vars = array(
									'###SITENAME###' => $sitename,
									'###USERNAME###' => $username,
									'###AMOUNT###'   => (float)$amount,
									'###CURRENCY###' => $currency_name,
									'###FEES###' => $fees,
									'###CONFIRM_LINK###' => base_url().'withdraw_coin_user_confirm/'.$link_ids,
									'###CANCEL_LINK###' => base_url().'withdraw_coin_user_cancel/'.$link_ids
									);

									$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

								$data['status'] = 1;
								$data['msg']	 = "Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!";

								
							} 
							else 
							{
								$data['status'] = 0;
								$data['msg']	 = "Unable to submit your withdraw request. Please try again";
								
							}
						}
					}
				}
				else
				{
					$data['status'] = 0;
					$data['msg']	 = "Please check the address";
				}	
		}
		echo json_encode($data);  	

}

// withdraw_web_coin

// function withdraw_web_coin(){


//    //          $this->form_validation->set_rules('ids', 'ids', 'trim|required|xss_clean|numeric');
// 			// $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
// 			$passinp = $this->db->escape_str($this->input->post('ids'));
// 			$myval = explode('_',$passinp);
// 			$id = $myval[0]; 
// 			$name = $myval[1];
// 			$bal = $myval[2];

// 			if($id!=7)
// 			{ 
// 			   $this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
// 		    }
// 		    else
// 		    { 
// 		    	$user_bank = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row(); 
// 				if(count($user_bank) == 0) 
// 		        { 
// 		   //      	$this->session->set_flashdata('error', "Please Fill your Bank Details");
// 					// front_redirect('withdraw/'.$cur, 'refresh');

// 					$data['msg']='Please Fill your Bank Details';
// 					$data['status']='0';




// 		        }	        
// 		        else 
// 		        {
// 		        	if($user_bank->status =='Pending'){
// 			   //      	$this->session->set_flashdata('error', "Please Wait for verification by our team");
// 						// front_redirect('withdraw/'.$cur, 'refresh');


// 						$data['msg']='Please Wait for verification by our team';
// 						$data['status']='1';



// 			        }
// 			        else if($user_bank->status =='Rejected'){
// 			   //      	$this->session->set_flashdata('error', "");
// 						// front_redirect('withdraw/'.$cur, 'refresh');


// 							$data['msg']='Your Bank details rejected by our team, Please contact support';
// 						    $data['status']='0';

// 			        }
// 			        else{
// 			        	$Bank = $user_bank->id; 
// 			        }	
		        	
// 		        }
// 		    // }
		   
// 			/*if ($this->form_validation->run()!= FALSE)
// 			{ echo 'dddd'; exit;*/
// 				$amount = $this->db->escape_str($this->input->post('amount'));
// 				if($id!=7)
// 				{
// 					$address = $this->db->escape_str($this->input->post('address'));
// 					$Payment_Method = 'crypto';
// 					$Currency_Type = 'crypto';
// 					$Bank_id = '';
// 				}
// 				else
// 				{
// 					$address = '';
// 					$Payment_Method = 'bank';
// 					$Currency_Type = 'fiat';
// 					$Bank_id = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'status'=>'Verified'))->row('id');
// 				}
// 	 			$balance = getBalance($user_id,$id,'crypto');
// 				$currency = getcryptocurrencydetail($id);
// 				$w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Pending','currency_id'=>$id));
// 				$count        = $w_isValids->num_rows();
// 	            $withdraw_rec = $w_isValids->row();
//                 $final = 1;
//                 $Validate_Address = 1;
// 				if($Validate_Address==1)
// 				{	
// 					if($count>0)
// 					{							
// 						// $this->session->set_flashdata('error', 'Sorry!!! Your previous ') . $currency->currency_symbol . $this->lang->line('withdrawal is waiting for admin approval. Please use other wallet or be patience');
// 						// front_redirect('withdraw/'.$cur, 'refresh');

// 						$data['msg']='Sorry!!! Your previous withdrawal is waiting for admin approval. Please use other wallet or be patience';
// 						$data['status']='0';

// 					}
// 					else
// 					{
// 						// Min and Max withdraw fees limit set
// 						if($currency->crypto_type_other != '')
// 						{
// 							$crypto_type_other_arr =explode('|',$currency->crypto_type_other);
// 							if($crypto_type_other_arr[0] == 'eth')
// 							{
// 								$min_withdraw_limit = $currency->min_withdraw_limit;
// 								$max_withdraw_limit = $currency->max_withdraw_limit;
// 							} 
// 							else if($crypto_type_other_arr[0] == 'bsc')
// 							{
// 								$min_withdraw_limit = $currency->min_bnb_withdraw_limit;
// 								$max_withdraw_limit = $currency->max_bnb_withdraw_limit;
// 							} else {
// 								$min_withdraw_limit = $currency->min_trx_withdraw_limit;
// 								$max_withdraw_limit = $currency->max_trx_withdraw_limit;
// 							}
// 						} else {
// 							$min_withdraw_limit = $currency->min_withdraw_limit;
// 							$max_withdraw_limit = $currency->max_withdraw_limit;
// 						}


// 						// echo $amount;
// 						// echo "--limit---";
// 						// echo $max_withdraw_limit;

// 						// exit();

// 						if($amount>$balance)
// 						{ 

// 							$data['msg']='Amount you have entered is more than your current balance';
// 							$data['status']='0';
// 							// $this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
// 							// front_redirect('withdraw/'.$cur, 'refresh');
// 						}
// 						if($amount < $min_withdraw_limit)
// 						{

//                             $data['msg']='Amount you have entered is less than minimum withdrawl limit';
// 							$data['status']='0';

// 							// $this->session->set_flashdata('error','Amount you have entered is less than minimum withdrawl limit');
// 							// front_redirect('withdraw/'.$cur, 'refresh');
// 						}
// 						elseif($amount > $max_withdraw_limit)
// 						{ 

// 							 $data['msg']='Amount you have entered is more than maximum withdrawl limit';
// 							 $data['status']='0';

// 							// $this->session->set_flashdata('error', 'Amount you have entered is more than maximum withdrawl limit');
// 							// front_redirect('withdraw/'.$cur, 'refresh');	
// 						}
// 						elseif($final!=1)
// 						{   

// 								 $data['msg']='Invalid address';
// 							     $data['status']='0';

// 						}
// 						else
// 						{
// 							if($currency->crypto_type_other != '')
// 							{
// 								if($this->input->post('network_type') == 'tron')
// 								{
// 									$withdraw_fees_type = $currency->withdraw_trx_fees_type;
// 					        		$withdraw_fees = $currency->withdraw_trx_fees;
// 								} else if($this->input->post('network_type') == 'bsc') {
// 									$withdraw_fees_type = $currency->withdraw_bnb_fees_type;
// 					        		$withdraw_fees = $currency->withdraw_bnb_fees;
// 								} else {
// 									$withdraw_fees_type = $currency->withdraw_fees_type;
// 					        		$withdraw_fees = $currency->withdraw_fees;
// 								}
// 							} else {
// 								$withdraw_fees_type = $currency->withdraw_fees_type;
// 					        	$withdraw_fees = $currency->withdraw_fees;
// 							}

// 					        if($withdraw_fees_type=='Percent') { $fees = (($amount*$withdraw_fees)/100); }
// 					        else { $fees = $withdraw_fees; }
// 							//$fees = apply_referral_fees_deduction($user_id,$fees);
// 					        $total = $amount-$fees;
// 					        $Ref = $user_id.'#'.strtotime(date('d-m-Y h:i:s'));
// 							$user_status = 'Pending';
// 							$ip_address = get_client_ip();

// 							$payment_id=$this->input->post('payment_id');
// 							$insertData = array(
// 								'user_id'=>$user_id,
// 								// 'ip_address'=>$ip_address,
// 								'destination_tag'=>$payment_id,
// 								'payment_method'=>$Payment_Method,
// 								'currency_id'=>$id,
// 								'amount'=>$amount,
// 								'transaction_id'=>$Ref,
// 								'fee'=>$fees,
// 								'bank_id'=>$Bank_id,
// 								'crypto_address'=>$address,
// 								'transfer_amount'=>$total,
// 								'datetime'=>date("Y-m-d H:i:s"),
// 								'type'=>'Withdraw',
// 								'status'=>'Pending',
// 								'currency_type'=>$Currency_Type,
// 								'user_status'=>$user_status,
// 								'crypto_type'=>($this->input->post('network_type') != '')?$this->input->post('network_type'):$currency->currency_symbol
// 								);
// 							$finalbalance = $balance - $amount;
// 							$updatebalance = updateBalance($user_id,$id,$finalbalance,'crypto');
// 							$insertData_clean = $this->security->xss_clean($insertData);
// 							$insert = $this->common_model->insertTableData('transactions', $insertData_clean);
// 							if($insert) 
// 							{
// 								$prefix = get_prefix();
// 								$user = getUserDetails($user_id);
// 								$usernames = $prefix.'username';
// 								$username = $user->$usernames;
// 								$email = getUserEmail($user_id);
// 								$currency_name = getcryptocurrency($id);
// 								$link_ids = base64_encode($insert);
// 								$sitename = getSiteSettings('english_english_site_name');
// 								$site_common      =   site_common();		                    

// 								if($id!=7)
// 								{
// 								    $email_template = 'Withdraw_User_Complete';
// 									$special_vars = array(
// 									'###SITENAME###' => $sitename,
// 									'###USERNAME###' => $username,
// 									'###AMOUNT###'   => (float)$amount,
// 									'###CURRENCY###' => $currency_name,
// 									'###FEES###' => $fees,
// 									'###CRYPTOADDRESS###' => $address,
// 									'###CONFIRM_LINK###' => base_url().'withdraw_coin_user_confirm/'.$link_ids,
// 									'###CANCEL_LINK###' => base_url().'withdraw_coin_user_cancel/'.$link_ids
// 									);
// 								}
// 								else
// 								{
// 	                                $email_template = 'Withdraw_Fiat_Complete';
// 									$special_vars = array(
// 									'###SITENAME###' => $sitename,
// 									'###USERNAME###' => $username,
// 									'###AMOUNT###'   => (float)$amount,
// 									'###CURRENCY###' => $currency_name,
// 									'###FEES###' => $fees,
// 									'###CONFIRM_LINK###' => base_url().'withdraw_confirm/'.$link_ids,
// 									'###CANCEL_LINK###' => base_url().'withdraw_cancel/'.$link_ids,
// 									);
// 								}
// 							    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);


// 							    $data['msg']='Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!';
// 							    $data['status']='1';



// 								// $this->session->set_flashdata('success','Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!');
// 								// front_redirect('wallet', 'refresh');
// 							} 
// 							else 
// 							{

// 								    $data['msg']='Unable to submit your withdraw request. Please try again';
// 							        $data['status']='0';

// 								// $this->session->set_flashdata('error','Unable to submit your withdraw request. Please try again');
// 								// front_redirect('withdraw/'.$cur, 'refresh');
// 							}
// 						}
// 					}
// 				}
// 				else
// 				{

// 					 $data['msg']='Please check the address';
// 					 $data['status']='0';
// 					// $this->session->set_flashdata('error', 'Please check the address');
// 					// front_redirect('withdraw/'.$cur, 'refresh');
// 				}	






// }




function check_profile_app()
	{		 
        $user_id=$this->input->post('user_id');
        //$user_id=15;
		$data = array();

		$get_users = $this->common_model->getTableData('users', array('id' => $user_id))->row();
		$get_users_bank = $this->common_model->getTableData('user_bank_details', array('user_id' => $user_id))->row();	
 
		if(!isset($user_id) && empty($user_id))
		{	
		
			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			
		}
		else
		{
	
		$rude = array();
		$i=0;

			if($get_users->randcode == 'enable'){
	           $randcode = '1';
			    }
		   else{
			   $randcode = '0';
		       }

			// if($get_users->verify_level2_status=='Completed')
   //          {
   //              $verify_status = "Verified";
                                            
   //          }
   //          else if($get_users->verify_level2_status=='Rejected')
   //          {
   //             $verify_status = "Rejected";
                                            
   //          } 
   //          else if($get_users->verify_level2_status=='Pending')
   //          {
                                            
   //            $verify_status = "Pending";
                                            
                                      
   //          }
   //          else{
   //              $verify_status = "Not Uploaded";
   //          }
		       if($get_users->photo_1_status==1 && $get_users->photo_2_status==1 && $get_users->photo_3_status==1 && $get_users->photo_4_status==1 && $get_users->photo_5_status==1){
		       	 $verify_status = "Pending";

		       }else if($get_users->photo_1_status==2 && $get_users->photo_2_status==2 && $get_users->photo_3_status==2 && $get_users->photo_4_status==2 && $get_users->photo_5_status==2) {
		       	$verify_status = "Rejected";
		       }else if($get_users->photo_1_status==3 && $get_users->photo_2_status==3 && $get_users->photo_3_status==3 && $get_users->photo_4_status==3 && $get_users->photo_5_status==3) {
		       	$verify_status = "Verified";
		       }
		       else{
		       	$verify_status = "Not Uploaded";
		       }


                                       if($get_users_bank->status=='Verified')
                                        {
                                            $bank_status = "Verified";
                                            
                                        }
                                        else if($get_users_bank->status=='Rejected')
                                        {
                                            $bank_status = "Rejected";
                                            
                                        } 
                                        else if($get_users_bank->status=='Pending')
                                        {
                                            
                                            $bank_status = "Pending";
                                            
                                      
                                        }


//$from_currency = $this->common_model->getTableData('currency',array('id' => $Currency_list->currency_id))->row();
$pass = decryptIt($get_users->bidex_password);
$cpass = decryptIt($get_users->bidex_cpassword);
$email = getUserEmail($get_users->id);
$country = get_countryname($get_users->country);
			$Currency_list_Val[$i] = array("email"=>$email,
												"image"=>$get_users->profile_picture,
												"firstname"=>$get_users->bidex_fname,
												"lastname"=>$get_users->bidex_lname,
												"phone"=>$get_users->bidex_phone,
												"address"=>$get_users->street_address,
												"city"=>$get_users->city,
												"state"=>$get_users->state,
												"country"=>$country,
												"pincode"=>$get_users->postal_code,
												"password"=>$pass,
												"cpassword"=>$cpass,
												"randcodestatus"=>$randcode,
												"kycstatus"=> $verify_status,
												"bankstatus"=> $bank_status
												
												);
				array_push($rude, $Currency_list_Val[$i]); 
				$object = (object) $Currency_list_Val[$i];
				
				$i++;
		
			$data['status'] = "1";
			$data['msg'] = "success";
			$data['check_profile'] = $object;
			//$data['randcode'] = $randcode;
			

		}
		echo json_encode($data);


	}

	function security()
	{	
		$user_id=$this->input->post('user_id');

	   // $user_id='136';
		$data = array();
		if(!isset($user_id) && empty($user_id))
		{	
			
			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
		}
		else{
			$data['status'] = 1;
			$data['msg'] = "success";


			$users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
            $code=$users->randcode;

            if($code=='enable'){
            	$rand='enable';

            }else{
            	$rand="disable";
            }



	  $this->load->library('Googleauthenticator');
		if($users->randcode=="enable" || $users->secret!="")
		{	
			$secret = $users->secret; 
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
			$data['tfa_status'] = $rand;
		}
		}

		echo json_encode($data);
	}

	function settings_security_app(){

			//$user_id='223';

		$user_id=$this->input->post('user_id');
			$data = array();
			if(!isset($user_id) && empty($user_id))
			{	
				

				$data['status'] = 0;
				$data['msg'] = "You are not Logged in";
			}

			else{
		   $this->load->library('Googleauthenticator');
		    $ga = new Googleauthenticator();
			$secret_code = $this->db->escape_str($this->input->post('secret'));
			$onecode = $this->db->escape_str($this->input->post('code'));
			$code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 3);

			$users= $this->common_model->getTableData('users',array('id'=>$user_id))->row();

			if($users->randcode != "enable")
			{

				if($code=='1')
				{
					$this->db->where('id',$user_id);
					$data1=array('secret'  => $secret_code,'randcode'  => "enable");
					$this->db->update('users',$data1);

					$data['status'] = 1;
					$data['msg'] = "TFA Enabled successfully";
				}
				else
				{
					$data['status'] = 0;
					$data['msg'] = "Please Enter correct code to enable TFA";

					
				}
			}
			else
			{
				if($code=='1')
				{
					$this->db->where('id',$user_id);
					$data1=array('secret'  => $secret_code,'randcode'  => "disable");
					$this->db->update('users',$data1);

					$data['status'] = 1;
					$data['msg'] = "TFA Disabled successfully";
				}
				else
				{
					$data['status'] = 0;
					$data['msg'] = "Please Enter correct code to disable TFA";
				}
			}
		}
		echo json_encode($data);
		
	}

	function add_bank_details_app()
	{		 
		
		$user_id = $this->input->post('user_id');
//$user_id = 11;
		//$data = array();
		$bank_details_check=$this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();
		if($user_id=="")
		{	
			
			$data['status'] = '0';
			$data['msg'] = 'Please Login';
			
		}
		else
		{
			
				if ($_FILES['bankpic']) 
				{
					$imagepro = $_FILES['bankpic']['name'];
					if($imagepro!="")
					{
						$uploadimage1=cdn_file_upload($_FILES["bankpic"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('bankpic')));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							
							$data['status'] = '0';
							$data['msg'] = 'Problem with profile picture';
							
						} 
					}				
					
				}
				else{ 
					$imagepro = $this->db->escape_str($this->input->post('bankpic'));
				}
				
				
			 
				$bank_account_name = $this->db->escape_str($this->input->post('bank_account_name'));
				$bank_account_number = $this->db->escape_str($this->input->post('bank_account_number'));
				$bank_name = $this->db->escape_str($this->input->post('bank_name'));
				$ifsc_code= $this->db->escape_str($this->input->post('ifsc_code'));
				$user_id= $this->db->escape_str($this->input->post('user_id'));
				
				$added_date = date("Y-m-d H:i:s");				
				$status = 'Pending';
				$user_status = '1';
					$insertData = array('bank_account_name'=>$bank_account_name,
                                        'bank_account_number'=>$bank_account_number,
                                        'bank_name'=>$bank_name,
                                        'ifsc_code'=>$ifsc_code,
                                        'user_id'=>$user_id,
                                        'added_date'=>$added_date,
                                        'status'=>$status,
                                        'user_status'=>$user_status,
                                        'bank_statement'=>$imagepro );
					if(!$bank_details_check){

				

				$insertData_clean = $this->security->xss_clean($insertData);
			
				
					$insert=$this->common_model->insertTableData('user_bank_details',$insertData_clean);
					if ($insert){
						$data['status'] = '1';
						$data['msg'] = 'Bank details Added Successfully';
						
					} else {
					
						$data['status'] = '0';
						$data['msg'] = 'Something there is a Problem .Please try again later';
					}
				}else{
					// print_r($insertData);exit;
					$update=$this->common_model->updateTableData('user_bank_details',array('user_id'=>$user_id),$insertData);
					if($update){
						$data['status'] = '1';
						$data['msg'] = 'Bank details Added Successfully';
					}else{
						$data['status'] = '0';
						$data['msg'] = 'Something there is a Problem .Please try again later';
					}
					
				}
				
				
			
		}

				echo json_encode($data);
	}

	function update_bank_details_app()
	{		 
		
		$user_id = $this->input->post('user_id');
//$user_id = 11;
		//$data = array();
		if($user_id=="")
		{	
			
			$data['status'] = '0';
			$data['msg'] = 'Please Login';
			
		}
		else
		{
			
				if ($_FILES['bankpic']) 
				{
					$imagepro = $_FILES['bankpic']['name'];
					if($imagepro!="")
					{
						$uploadimage1=cdn_file_upload($_FILES["bankpic"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('bankpic')));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							
							$data['status'] = '0';
							$data['msg'] = 'Problem with bank picture';
							
						} 
					}				
					
				}
				else{ 
					$imagepro = $this->db->escape_str($this->input->post('bankpic'));
				}
				
				
			 
				$bank_account_name = $this->db->escape_str($this->input->post('bank_account_name'));
				$bank_account_number = $this->db->escape_str($this->input->post('bank_account_number'));
				$bank_name = $this->db->escape_str($this->input->post('bank_name'));
				$ifsc_code= $this->db->escape_str($this->input->post('ifsc_code'));
				$user_id= $this->db->escape_str($this->input->post('user_id'));
				
				$added_date = date("Y-m-d H:i:s");				
				$status = 'Pending';
				$user_status = '1';
					
					

					$insertData = array('bank_account_name'=>$bank_account_name,
                                        'bank_account_number'=>$bank_account_number,
                                        'bank_name'=>$bank_name,
                                        'ifsc_code'=>$ifsc_code,
                                        'user_id'=>$user_id,
                                        'added_date'=>$added_date,
                                        'status'=>$status,
                                        'user_status'=>$user_status,
                                        'bank_statement'=>$imagepro );
				

				$insertData_clean = $this->security->xss_clean($insertData);
			
				
					$insert = $this->common_model->updateTableData('user_bank_details',array('user_id'=>$user_id),$insertData_clean);
					if ($insert){
						$data['status'] = '1';
						$data['msg'] = 'Bank details Updated Successfully';
						
					} else {
					
						$data['status'] = '0';
						$data['msg'] = 'Something there is a Problem .Please try again later';
					}
				
				
			
		}

				echo json_encode($data);
	}

	// Trade API
	    function execute_order()
	{	
		$amount = $this->input->post('amount');
		$price = $this->input->post('price');
		$limit_price = $this->input->post('limit_price');
		$total = $this->input->post('total');
		$fee = $this->input->post('fee');
		$ordertype = $this->input->post('ordertype');
		$pair = $this->input->post('pair_id');
		$type = $this->input->post('type');
		$loan_rate = 0;
		$pagetype = 'trade';
		$user_id = $this->input->post('user_id');
		
		

		

		// $amount = '1335.34525352';
		// $price = '0.07488700';
		// $limit_price = 0;
		// $total = '100';
		// $fee = '4.95';
		// $ordertype = 'limit';
		// $pair = 1;
		// $type = 'buy';
		// $loan_rate = 0;
		// $pagetype = 'trade';
		// $user_id = 90;

	// 	$data_array = array(
	// 		'amount' => $amount,
	// 		'price' => $price,
	// 		'limit_price'=> $limit_price,
	// 		'total' => $total,
	// 		'fee' => $fee,
	// 		'ordertype' => $ordertype,
	// 		'pair' => $pair,
	// 		'type' => $type,
	// 		'loan_rate' => 0,
	// 		'pagetype' => 'trade',
	// 		'user_id' => $user_id
	// 	);

	// 	$pair_details = $this->common_model->getTableData('trade_pairs',array('id'=>$pair))->row();
	// 	$pair1 = $pair_details->from_symbol_id;
	// 	$pair2 = $pair_details->to_symbol_id;
		
	// 	$first_balance = getBalance($user_id,$pair1);
	// 	$second_balance = getBalance($user_id,$pair2);

	// 	// echo json_encode($pair1);
	// 	// exit();
	// 	// $response = array('status'=>'','msg'=>'');
	// 	if($this->input->post()){
		
	// 	if($user_id !="")
	// 	{	
	// 		// $res=$this->site_api->createOrder($user_id,$amount,$price,$limit_price,$total,$fee,$pair,$ordertype,$type,$loan_rate,$pagetype);
	// 		// print_r($res);exit;
	// 		if($type=="buy" && $total > $second_balance){
	// 		$response['status'] = 0;
	// 		$response['msg'] = 'Insufficient Balance';
	// 		}elseif($type=="sell" && $total > $first_balance){
	// 			$response['status'] = 0;
	// 			$response['msg'] = 'Insufficient Balance';
	// 		}else{

	// 		 if($this->site_api->createOrder($user_id,$amount,$price,$limit_price,$total,$fee,$pair,$ordertype,$type,$loan_rate,$pagetype)){
	// 			$response['status'] =1;
	// 			if($type=='buy'){
	// 			$response['msg'] = "Buy Order Success";
	// 		}
	// 		if($type=='sell'){
	// 			$response['msg'] = "Sell Order Success";
	// 		}
	// 		 }else{
	// 			$response['status'] =0;
	// 			$response['msg'] = "Order Failed";
	// 		 }
	// 		}
	// 	}
	// 	else
	// 	{
	// 		$response['status'] = 0;
	// 		$response['msg'] = "Please login";
	// 	}
	// }else{
	// 	$response['status'] = 0;
	// 	$response['msg'] = "order values were empty";
	// }
	// 	echo json_encode($response);
	// 	$amount = $this->input->post('amount');
	// $price = $this->input->post('price');
	// $limit_price = $this->input->post('limit_price');
	// $total = $this->input->post('total');
	// $fee = $this->input->post('fee');
	// $ordertype = $this->input->post('ordertype');
	// $pair = $this->input->post('pair');
	// $type = $this->input->post('type');
	// // $loan_rate = $this->input->post('loan_rate');
	// $loan_rate=0;
	// // $pagetype = $this->input->post('pagetype');
	// $pagetype = 'trade';
	// $user_id = $this->input->post('user_id');
	// $response = array('status'=>'','msg'=>'');
	// 	$amount = $this->input->post('amount');
	// 	$price = $this->input->post('price');
	// 	$limit_price = $this->input->post('limit_price');
	// 	$total = $this->input->post('total');
	// 	$fee = $this->input->post('fee');
	// 	$ordertype = $this->input->post('ordertype');
	// 	$pair = $this->input->post('pair_id');
	// 	$type = $this->input->post('type');
	// 	$loan_rate = 0;
	// 	$pagetype = 'trade';
	// 	$user_id = $this->input->post('user_id');
	if($user_id !="")
	{	
	// echo $user_id;exit;	
		$response 	= $this->site_api->createOrder($user_id,$amount,$price,$limit_price,$total,$fee,$pair,$ordertype,$type,$loan_rate,$pagetype);
		if($response['status'] == "success"){
			$array['status'] = "1";
			$array['msg'] = "Order Placed successfully";
		}elseif($response['status'] == "balance"){
			$array['status'] = "0";
			$array['msg'] = "Insufficient Balance";
		}elseif($response['status'] == "minimum_amount"){
			$array['status'] = "0";
			$array['msg'] = "Minimum Trade";
		}

	}
	else
	{
		$response['status'] = "login";
	}
	echo json_encode($array);
	}


	function trade_basic(){
	$data = array();

	$pair_id = $this->input->post('pair_id');
	$pair = $this->input->post('pair_symbol');
	$user_id = $this->input->post('user_id');

		// $pair = 'ETH_BTC';
		// $pair_id = 1;
		// $user_id = 15; 

		$explode_pair = explode('_',$pair);
		$from_currency = $explode_pair[0];
		$to_currency = $explode_pair[1];
		$first_currency = $this->common_model->getTableData('currency',array('currency_symbol'=>$from_currency),'','','','','','', '')->result();
		$second_currency = $this->common_model->getTableData('currency',array('currency_symbol'=>$to_currency),'','','','','','', '')->result();

 
	if(!isset($pair_id) && empty($pair_id)){

		$data['status'] = 0;
		$data['msg'] = "Pair cannot be empty";
	}
	else{

		$data['status'] = 1;
		$data['msg'] = "success";
		$trade_pair_data = $this->common_model->customQuery("SELECT * FROM bidex_trade_pairs WHERE id='".$pair_id."'")->result();
		$data['last_price'] =$trade_pair_data[0]->lastPrice;
		$data['priceChangePercent'] =$trade_pair_data[0]->priceChangePercent;
		$data['min_trade_amount'] =$trade_pair_data[0]->min_trade_amount;
		// $data['maker_fee'] =getfeedetails_buy($pair,1);
		$data['maker_fee'] = $this->maker=getfeedetails_buy($pair_id);
		$data['taker_fee'] =$this->taker=getfeedetails_sell($pair_id);
		// $user_balance = getBalance($user_id);
		$data['first_balance']  = getBalance($user_id,$second_currency[0]->id);
		$data['second_balance']  = getBalance($user_id,$first_currency[0]->id);
		$data['lastmarketprice']=number_format(marketprice_pair($pair),8); 

		// $pair_details = getPairdetails($pair_id);

		$data['change_high'] = $trade_pair_data[0]->change_high;
		$data['change_low'] = $trade_pair_data[0]->change_low;

		$opens = $this->get_active_order($user_id);
		if($opens > 0)
		{
			$data['open_orders']=$this->get_active_order($user_id);
		}
		else
		{
			$data['open_orders']=[];
		}

		// $order_history = $this->getOrderHistoryApi($pair,$user_id);
		// if($order_history > 0)
		// {
		// 	$data['history']=$order_history;
		// }
		// else
		// {
		// 	$data['history']=[];
		// }

		// $data['open_orders']=$this->get_active_order($user_id);
		$data['open_orders_limit']=$this->get_active_limitorder($user_id);
		$data['open_orders_market']=$this->get_active_marketorder($user_id);
		$data['open_orders_stop']=$this->get_active_stoporder($user_id);

		$cancels = $this->get_cancel_order($pair_id,$user_id);
		if($cancels > 0)
			$data['cancel_orders'] = $cancels;
		else
			$data['cancel_orders'] = [];

		$data['stop_orders']=$this->get_stop_order($pair_id,$user_id);
		$active_orders = $this->get_active_userorder($pair_id,$user_id);
		if($active_orders > 0)
			$data['active_orders'] = $active_orders;
		else
			$data['active_orders'] = [];

		$trade_history = $this->getOrderHistoryApi($pair,$user_id);
	$Check_api = checkapi($pair_id);
	if($Check_api==0){
	    $data['sellResult'] = $this->gettradeopenOrders('Sell',$pair_id);
		$data['buyResult'] = $this->gettradeopenOrders('Buy',$pair_id);
	}
	else{
		$data['sellResult'] = $this->gettradeapisellOrders($pair);
		$data['buyResult'] = $this->gettradeapibuyOrders($pair);
	}

	if($trade_history > 0)
		$data['trade_history'] = $trade_history;
	else
		$data['trade_history'] =[];


	//$data['depth_chart'] = $this->depthchart_check($pair_id,$pair);
}
	echo json_encode($data);
	exit();
		
}




		function trade_integration()
	{
		$pair_id = $this->input->post('pair_id');
		$user_id = $this->input->post('user_id');
		$type = $this->input->post('type');
		$pair = $this->input->post('pair');

		$data['pairs'] = trade_pairs($type);
		$this->newtrade_prices($pair_id,$type,$user_id);
		$data['transactionhistory'] = $this->transactionhistory($pair_id,$user_id);
		$data['sellResult'] = $this->gettradeopenOrders('Sell',$pair_id);
		$data['buyResult'] = $this->gettradeopenOrders('Buy',$pair_id);
		$data['api_sellResult'] = $this->gettradeapisellOrders($pair);
		$data['api_buyResult'] = $this->gettradeapibuyOrders($pair);
		$data['market_trades'] = $this->market_trades($pair_id);
		$data['market_api_trades'] = $this->market_api_trades($pair);
		$data['current_trade'] = $this->current_trade_pair($pair_id);
		$pair_details = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_id),'from_symbol_id,to_symbol_id')->row();
		$fromID = $pair_details->from_symbol_id;
        $toID = $pair_details->to_symbol_id;
        $getfrom_symbols= $this->common_model->getTableData('currency',array('id'=>$fromID),'','',array())->row();
        $getto_symbols= $this->common_model->getTableData('currency',array('id'=>$toID),'','',array())->row();
        if($getfrom_symbols->currency_symbol =='USD')
         $format = 2;
        else if($getfrom_symbols->currency_symbol =='USDT')  
         $format = 6; 
        else
          $format = 8; 
        if($getto_symbols->currency_symbol =='USD')
         $format1 = 2;
        else if($getto_symbols->currency_symbol =='USDT')  
         $format1 = 6; 
        else
         $format1 = 8; 
		if($type!='home')
		{
			if($user_id&&$user_id!=0)
			{
				$data['open_orders']=$this->get_active_order($user_id);
				$data['open_orders_limit']=$this->get_active_limitorder($user_id);
				$data['open_orders_market']=$this->get_active_marketorder($user_id);
				$data['open_orders_stop']=$this->get_active_stoporder($user_id);
				$data['cancel_orders']=$this->get_cancel_order($pair_id,$user_id);
				$data['stop_orders']=$this->get_stop_order($pair_id,$user_id);
				$data['active_orders'] = $this->get_active_userorder($pair_id,$user_id);
			}
			else
			{
				$data['open_orders']=0;
				$data['cancel_orders']=0;
				$data['stop_orders']=0;
				$data['active_orders'] = 0;
			}
		}
		
		if($this->user_balance!=0)
		{
			$balance=$this->user_balance;
			$data['from_currency'] = to_decimal($balance[$pair_details->from_symbol_id], $format);
            $data['to_currency'] = to_decimal($balance[$pair_details->to_symbol_id], $format1);
			$data['from_symbol'] = $getfrom_symbols->currency_symbol;
            $data['to_symbol'] = $getto_symbols->currency_symbol;			
		}
		else
		{
			$data['from_currency']=0;
			$data['to_currency']=0;	
			$data['from_symbol'] = 0;
            $data['to_symbol'] = 0;
		}
		$data['bidex_userid']=$this->user_id;
		$data['current_buy_price']=to_decimal($this->marketprice,8);
		$data['current_sell_price']=to_decimal($this->marketprice,8);
		$data['lastmarketprice']=to_decimal($this->lastmarketprice,8);

		$pair_details1 = $this->common_model->getTableData('trade_pairs',array('id'=>$pair_id))->row();

		$data['change']=to_decimal($pair_details1->priceChangePercent,2);
		$data['high']=to_decimal($pair_details1->change_high,8);
		$data['low']=to_decimal($pair_details1->change_low,8);
		$data['volume']=to_decimal($pair_details1->volume,2);

		$data['web_trade'] = '1';
		$result = json_encode($data);
		return  $result;
	}


function trade_pairs($type='')
	{
		$ci =& get_instance();
		$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$where = array('a.status'=>1,'b.status'=>1,'c.status'=>1);
		
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

	function newtrade_prices($pair,$pagetype='',$user_id)
	{
		$this->marketprice = marketprice($pair);
		$this->lowestaskprice = lowestaskprice($pair);
		$this->highestbidprice = lowestaskprice($pair);
		$this->lastmarketprice = lastmarketprice($pair);
		$this->minimum_trade_amount = get_min_trade_amt($pair);
		$this->maker=getfeedetails_buy($pair);
		$this->taker=getfeedetails_sell($pair);
		$this->myfrmt = get_decimalpairs($pair);
		if($user_id)
		{
			$this->user_id = $user_id;
			$this->user_balance = getBalance($user_id);
		}
		else
		{
			$this->user_id = 0;
			$this->user_balance = 0;
		}
	}

		public function transactionhistory($pair_id,$user_id)
	{
		$user_id = $user_id;
		$joins = array('coin_order as b'=>'a.sellorderId = b.trade_id','coin_order as c'=>'a.buyorderId = c.trade_id');
		$where = array('a.pair'=>$pair_id,'b.userId'=>$user_id);
		$where_or = array('c.userId'=>$user_id);
		$transactionhistory = $this->common_model->getJoinedTableData('ordertemp as a',$joins,$where,'a.*,
			 date_format(b.datetime,"%d-%m-%Y %H:%i %p") as sellertime,b.trade_id as seller_trade_id,date_format(c.datetime,"%d-%m-%Y %H:%i %p") as buyertime,c.trade_id as buyer_trade_id,a.askPrice as sellaskPrice,c.Price as buyaskPrice,b.Fee as sellerfee,c.Fee as buyerfee,b.Total as sellertotal,c.Total as buyertotal','',$where_or,'','','',array('a.tempId','desc'))->result();
		
        $newquery = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%m-%Y %H:%i %p") as tradetime from bidex_coin_order where userId = '.$user_id.' and pair = '.$pair_id.' and status = "cancelled"')->result();
		if(count($transactionhistory)>0 || count($newquery))
		{
		    $transactionhistory_1 = array_merge($transactionhistory,$newquery);
		    $historys = $transactionhistory_1;
		}
		else
		{
		    $historys=0;
		}
		return $historys;
	}

		public function gettradeopenOrders($type,$pair_id)
	{
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount,CO.Amount as quantity,CO.Price as price';
		$names = array('active', 'partially', 'margin');
		$where=array('CO.Type'=>$type,'CO.pair'=>$pair_id);
		$orderBy=array('CO.trade_id','desc');
		$groupBy=array('CO.trade_id');
		$where_in=array('CO.status', $names);
		$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
		$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
		if($query->num_rows() >= 1)
		{
			$result = $query->result();
		}
		else
		{
			$result = $query->result();
		}
		if($result&&$result!=0)
		{
			$orders=$result;
		}
		else
		{
			$orders=$result;
		}
		return $orders;
	}

	    	function gettradeapisellOrders($pair)
{
	$orderBy=array('price','desc');
  $sellresult = $this->common_model->getTableData("api_orders",array("pair_symbol"=>$pair,'type'=>'sell'),'price,quantity','','','','',20,$orderBy)->result();
 
        if(isset($sellresult)>0 && !empty($sellresult))
        { 
          $sell_res = array();
          $i=1;
          foreach($sellresult as $sell)
          {
            $sellData['id'] = $i;
            $sellData['price'] = $sell->price;
            $sellData['quantity'] = $sell->quantity;
              $sell_res[] = $sellData;
              $i++;
          }
          return $sell_res;
      }
      else
      {
        return $sell_res = [];
      }
}

	function gettradeapibuyOrders($pair)
{
  $buyresult = $this->common_model->getTableData("api_orders",array("pair_symbol"=>$pair,'type'=>'buy'),'price,quantity','','','','',20)->result();
        
        if(count($buyresult)>0 && !empty($buyresult))
        { 
          $buy_res = array();
          $i=1;
          foreach($buyresult as $buy)
          {
            $buyData['id'] = $i;
            $buyData['price'] = $buy->price;
            $buyData['quantity'] = $buy->quantity;
              $buy_res[] = $buyData;
              $i++;
          }
          return $buy_res;
      }
      else
      {
        return $buy_res = [];
      }
}

    public function market_trades($pair_id)
{
	/*$tradehistory_via_api = $this->common_model->getTableData('site_settings',array('tradehistory_via_api'=>1))->row('tradehistory_via_api');
	if($tradehistory_via_api ==0){*/
	$selectFields='CO.*,date_format(CO.datetime,"%H:%i:%s") as trade_time,sum(OT.filledAmount) as totalamount,CO.Type as ordertype,CO.Price as price,CO.Amount as quantity';
	$names = array('active', 'partially', 'margin');
	$where=array('CO.pair'=>$pair_id);
	$orderBy=array('CO.trade_id','desc');
	$groupBy=array('CO.trade_id');
	$where_in=array('CO.status', $names);
	$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
	$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
	if($query->num_rows() >= 1)
	{
		$result = $query->result();
	}
	else
	{
		$result = $query->result();
	}
	if($result&&$result!=0)
	{
		$orders=$result;
	}
	else
	{
		$orders=$result;
	}

	// echo "<pre>";
	// print_r($orders);
	// echo "<pre>";
	// exit(); 

	return $orders;
}

function market_api_trades($pair){
	$pair_value=explode('_',$pair);
  if(count($pair_value) > 0) 
  {
    $first_pair  = strtoupper($pair_value[0]);
    $second_pair1 = strtoupper($pair_value[1]);
    if($second_pair1=='USD'){
    	$second_pair = 'USDC';
    }
    else{
    	$second_pair = $second_pair1;
    }
    $coin_pair = $first_pair.$second_pair;
	$json  		= file_get_contents('http://api.binance.com/api/v1/depth?symbol='.$coin_pair.'&limit=20');
	$newresult = json_decode($json,true);
    if(count($newresult)>0 && !empty($newresult))
    {
    	$buy_orders = $newresult['bids'];
        $sell_orders = $newresult['asks'];
        $res_data = array();
        $i=1;
        foreach($sell_orders as $sell)
        {
        	$sellData['id'] = $i;
	        $sellData['price'] = $sell[0];
	        $sellData['quantity'] = $sell[1];
	        $sellData['ordertype'] = 'Sell';
	        $res_data[] = $sellData;
            $i++;
        }
        foreach($buy_orders as $buy)
        {
        	$buyData['id'] = $i;
	        $buyData['price'] = $buy[0];
	        $buyData['quantity'] = $buy[1];
	        $buyData['ordertype'] = 'Buy';
	        $res_data[] = $buyData;
            $i++;
        }
    }
    else
    {
    	$coin_pairrev = $second_pair.$first_pair;
    	$json_rev  		= file_get_contents('http://api.binance.com/api/v1/depth?symbol='.$coin_pairrev.'&limit=20');
	    $newresult_rev = json_decode($json_rev,true);
	    if(count($newresult_rev)>0 && !empty($newresult_rev))
        {
        	$buy_orders = $newresult_rev['bids'];
	        $sell_orders = $newresult_rev['asks'];
	        $res_data = array();
	        $i=1;
        foreach($sell_orders as $sell)
        {
        	$sellData['id'] = $i;
	        $sellData['price'] = $sell[0];
	        $sellData['Amount'] = $sell[1];
	        $sellData['ordertype'] = 'Sell';
	        $res_data[] = $sellData;
            $i++;
        }
        foreach($buy_orders as $buy)
        {
        	$buyData['id'] = $i;
	        $buyData['price'] = $buy[0];
	        $buyData['Amount'] = $buy[1];
	        $buyData['ordertype'] = 'Buy';
	        $res_data[] = $buyData;
            $i++;
        }
        } 
    }
    
	
    if($res_data&&$res_data!=0)
    {
    	//$res_data = shuffle_assoc($res_data);
    	$res_data = $res_data;
    }
    else
    {
         $res_data = 0;
    }
    
   return $res_data;
}
}

	public function current_trade_pair($pair_id)
	{
		$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$where = array('a.status'=>1);
		$orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.*,b.currency_name as from_currency,b.currency_symbol as from_currency_symbol,c.currency_name as to_currency,c.currency_symbol as to_currency_symbol')->result();
		$pair=$this->common_model->getTableData('trade_pairs', array('id' => $pair_id))->row();
		$trade_prices=array();
		$volume=getTradeVolume($pair->id);
		if($volume->price!=0)
		{
			$trade_prices['price'] = to_decimal($volume->price,8);
		}
		else
		{
			$trade_prices['price'] = to_decimal($pair->buy_rate_value,8);
		}
		$trade_prices['volume'] = $volume->volume;
		$trade_prices['high'] = $volume->high;
		$trade_prices['low'] = $volume->low;
		return $trade_prices;
	}


		function get_active_order($user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
		$names = array('active', 'partially', 'margin','stoporder');
		$where=array('CO.userId'=>$user_id);
		$orderBy=array('CO.trade_id','desc');
		$groupBy=array('CO.trade_id');
		$where_in=array('CO.status', $names);
		$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
		$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
		if($query->num_rows() >= 1)
		{
			$open_orders = $query->result();
		}
		else
		{
			$open_orders = 0;
		}
		if($open_orders&&$open_orders!=0)
		{
			$open_orders_text=$open_orders;
			// foreach($open_orders as $getOrder){
			// 	$activefilledAmount=$getOrder->totalamount;
	  //         $activePrice=$getOrder->Price;
	  //         $Fee=$getOrder->Fee;
	  //         $activeAmount  = $getOrder->Amount;
	  //         if($activefilledAmount)
	  //         {
	  //           $activefilledAmount = $activeAmount-$activefilledAmount;
	  //         }
	  //         else
	  //         {
	  //           $activefilledAmount = $activeAmount;
	  //         }
	  //         $activefilledAmount=$activefilledAmount;
	  //         $trade_id = $getOrder->trade_id;
	  //         $odr_type = $getOrder->Type;
	  //         $odr_status = $getOrder->status;
	  //         if($odr_type=='buy')
	  //         {
	  //           $odr_color = 'text-success';
	  //           $ordtype1 = 'Buy';
	  //           // var activeCalcTotal = Number(activefilledAmount*activePrice) + Number(Fee);
	  //           $activeCalcTotal = $activefilledAmount * $activePrice;
	  //             // $activeCalcTotal=$activeCalcTotal;
	  //         }
	  //         else
	  //         {
	  //           $odr_color = 'text-danger';
	  //           $ordtype1 = 'Sell';
	  //           // var activeCalcTotal = Number(activefilledAmount*activePrice) - Number(Fee);
	  //           $activeCalcTotal = $activefilledAmount * $activePrice;
	  //           // $activeCalcTotal=$activeCalcTotal;
	  //         }
	  //         $time = $getOrder->trade_time;
	  //         $pair_symbol = $getOrder->pair_symbol; 
	  //         $pairy  = $getOrder->pair;               
	  //         $ordtypes = $getOrder->ordertype;
	  //         if($ordtypes == 'limit') $ordtype = 'Limit';
	  //         else if($ordtypes == 'stop') $ordtype = 'Stop Order';
	  //         else if($ordtypes == 'instant') $ordtype = 'Market';
	  //         else $ordtype = '-';

	  //         $open_orders_text=array(
	  //         	'trade_time'=>$time,
	  //         	'pair_symbol'=>$pair_symbol,
	  //           'Type'=>$ordtype1,
			//    // 'class'=>$odr_color,
			//     'ordertype'=>$ordtype,
			//     'Price':$activePrice,
			//     'Amount'=>$activefilledAmount,
		 //        'Total'=>$activeCalcTotal,
		 //        'userId'=>$user_id,
		 //        'trade_id'=>$trade_id,
		 //        'pair_id'=>$pairy);

			// }
		}
		else
		{
			$open_orders_text=0;
		}
		return $open_orders_text;
	}
	function get_active_limitorder($user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
		$names = array('active', 'partially', 'margin');
		$where=array('CO.userId'=>$user_id,'CO.ordertype'=>'limit');
		$orderBy=array('CO.trade_id','desc');
		$groupBy=array('CO.trade_id');
		$where_in=array('CO.status', $names);
		$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
		$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
		if($query->num_rows() >= 1)
		{
			$open_orders = $query->result();
		}
		else
		{
			$open_orders = 0;
		}
		if($open_orders&&$open_orders!=0)
		{
			$open_orders_text=$open_orders;
		}
		else
		{
			$open_orders_text=0;
		}
		return $open_orders_text;
	}
	function get_active_marketorder($user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
		$names = array('active', 'partially', 'margin');
		$where=array('CO.userId'=>$user_id,'CO.ordertype'=>'instant');
		$orderBy=array('CO.trade_id','desc');
		$groupBy=array('CO.trade_id');
		$where_in=array('CO.status', $names);
		$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
		$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
		if($query->num_rows() >= 1)
		{
			$open_orders = $query->result();
		}
		else
		{
			$open_orders = 0;
		}
		if($open_orders&&$open_orders!=0)
		{
			$open_orders_text=$open_orders;
		}
		else
		{
			$open_orders_text=0;
		}
		return $open_orders_text;
	}
	function get_active_stoporder($user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
		$names = array('active', 'partially', 'margin');
		$where=array('CO.userId'=>$user_id,'CO.ordertype'=>'stop');
		$orderBy=array('CO.trade_id','desc');
		$groupBy=array('CO.trade_id');
		$where_in=array('CO.status', $names);
		$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
		$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
		if($query->num_rows() >= 1)
		{
			$open_orders = $query->result();
		}
		else
		{
			$open_orders = 0;
		}
		if($open_orders&&$open_orders!=0)
		{
			$open_orders_text=$open_orders;
		}
		else
		{
			$open_orders_text=0;
		}
		return $open_orders_text;
	}
	function get_active_userorder($pair_id,$user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
		$names = array('active', 'partially', 'margin');
		$where=array('CO.pair'=>$pair_id,'CO.userId'=>$user_id);
		$orderBy=array('CO.trade_id','desc');
		$groupBy=array('CO.trade_id');
		$where_in=array('CO.status', $names);
		$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
		$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);
		if($query->num_rows() >= 1)
		{
			$open_orders = $query->result();
		}
		else
		{
			$open_orders = $query->result();
		}
		if($open_orders&&$open_orders!=0)
		{
			$open_orders_text=$open_orders;
		}
		else
		{
			$open_orders_text=$query->result();
		}
		return $open_orders_text;
	}
	function get_cancel_order($pair_id,$user_id)
	{
		$user_id = $user_id;
		$selectFields='CO.*,OT.filledAmount as totalamount';
		$where=array('CO.pair'=>$pair_id,'CO.userId'=>$user_id,'CO.status'=>'cancelled');
		$orderBy=array('CO.trade_id','desc');
		$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
		$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy);
		if($query->num_rows() >= 1)
		{
			$cancel_orders = $query->result();
		}
		else
		{
			$cancel_orders = '';
		}
		if($cancel_orders&&$cancel_orders[0]->trade_id!='')
		{
			$cancel_orders_text=$cancel_orders;
		}
		else
		{
			$cancel_orders_text=0;
		}
		return $cancel_orders_text;
	}
	function get_stop_order($pair_id,$user_id)
	{
		$user_id = $user_id;
		$query = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%m-%Y %H:%i %a") as tradetime from bidex_coin_order where userId = '.$user_id.' and status = "stoporder" and pair = '.$pair_id.'');
		if($query->num_rows() >= 1)
		{
			$stop_orders = $query->result();
		}
		else
		{
			$stop_orders='';
		}
		if($stop_orders)
		{
			$stoporder=$stop_orders;
		}
		else
		{
			$stoporder=0;
		}
		return $stoporder;
	}
		function close_active_order()
	{
		$tradeid = $this->input->post('trade_id');
		$user_id = $this->input->post('user_id');
		$pair_id = $this->input->post('pair_id');

		$response = array('status'=>'','msg'=>'');
		if(!isset($user_id) && empty($user_id)){

			$array['status'] = 0;
			$array['msg'] = "User ID cannot be empty";
		}
		else if(empty($tradeid)){

			$array['status'] = 0;
			$array['msg'] = "Trade ID cannot be empty";
		}else{
		$response=$this->site_api->close_active_order($tradeid,$pair_id,$user_id);
		if($response['msg']=="success"){
			$array['status'] = 1;
			$array['msg'] = "Order Cancelled Successfully";
		}elseif($response['msg']=="error"){
			$array['status'] = 0;
			$array['msg'] = "Order Cancelled Failed";
		}else{
			$array['status'] = 0;
			$array['msg'] = "Unknown Error";
		}

	}
		echo json_encode($array);
	}

	function support_app()
	{
		
		$user_id=$this->input->post('user_id');
		$data = array();
		if(!isset($user_id) && empty($user_id))
		{	
			

			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
		}
		else{

			if ($_FILES['supportpic']) 
				{
					$imagepro = $_FILES['supportpic']['name'];
					if($imagepro!="")
					{
						$uploadimage1=cdn_file_upload($_FILES["supportpic"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('supportpic')));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							
							$data['status'] = '0';
							$data['msg'] = 'Problem with profile picture';
							
						} 
					}				
					
				}
				else{ 
					$imagepro = $this->db->escape_str($this->input->post('supportpic'));
				}

			$user_id = $user_id;
			$subject = $this->input->post('subject');
			$message = $this->input->post('support_comments');
			$name = $this->input->post('support_name');
			$email = $this->input->post('support_email');
			//$insertData['category'] = $this->input->post('category');
			$image = $imagepro;
			$created_on = gmdate(time());
			$ticket_id = 'TIC-'.encryptIt(gmdate(time()));
			$status = '1';

			  $insertData = array(
                    'user_id' => $user_id,
                    'email' => $email,
                    'message' => $message,
                    'subject' => $subject,
                    'image' => $image,
                    'name' => $name,
                    'ticket_id' => $ticket_id,
                    'status' => $status,
                    'created_on' => $created_on
                );

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
						'###LINK###' 			=> admin_url().'support/reply/'.$insert
				);
				
				$special_vars_user 		= array(
						'###SITELINK###' 		=> front_url(),
						'###SITENAME###' 		=> $site_common['site_settings']->site_name,
						'###USERNAME###' 		=> $username,
						'###MESSAGE###'  		=> "<span style='color: #500050;'>".$message . "</span><br>"
				);

				$this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars);
				$this->email_model->sendMail($usermail, '', '', $email_template_user, $special_vars_user);

				$data['status'] = '1';
				$data['msg'] = 'Your message successfully sent to our team';
			} else {
				
				$data['status'] = '0';
				$data['msg'] = 'Error occur!! Please try again';
			}
		}
       echo json_encode($data);
	}

	public function contact_us_app()
    { 
  $user_id=$this->input->post('user_id');
		$data = array();
		if(!isset($user_id) && empty($user_id))
		{	
			

			$data['status'] = 0;
			$data['msg'] = "You are not Logged in";
			
			
		}
		else{

            
                $name = $this->db->escape_str($this->input->post('name'));
                $email = $this->db->escape_str($this->input->post('email'));
                $subject = $this->db->escape_str($this->input->post('subject'));
                $comments = $this->db->escape_str($this->input->post('message'));
                $phone = $this->db->escape_str($this->input->post('phone'));
                $status = 0;
                $contact_data = array(
                    'username' => $name,
                    'email' => $email,
                    'message' => $comments,
                    'subject' => $subject,
                    'phone' => $phone,
                    'status' => $status,
                    'created_on' => date("Y-m-d h:i:s")
                );
                $id = $this->common_model->insertTableData('contact_us', $contact_data);
                $email_template = 'Contact_user';
                $email_template1 = 'Contact_admin';
				$username=$this->input->post('name');
				$message = $this->input->post('message');
				$link = base_url().'bidex_admin/contact';
				$site_common      =   site_common();
				$admin_admin = site_common()['site_settings']->site_email;
				$special_vars = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message
				);
				$special_vars1 = array(					
				'###USERNAME###' => $username,
				'###MESSAGE###' => $message,
				'###LINK###' => $link
				);

				// print_r($email_template);
				// echo "------------------";
				// print_r($special_vars1); die;

				    
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				$this->email_model->sendMail($admin_admin, '', '', $email_template1, $special_vars1);
                if ($id) 
                {
                
                   $data['status'] =  "1";
			       $data['msg'] = "Your message successfully sent to our team";
                } 
                else 
                {
                    
                   $$data['status'] =  "0";
			       $data['msg'] = "Error occur!! Please try again";
                }
            
        
            echo json_encode($data);
       }
       
    }

    function favorite_app(){
    	$user_id=$this->input->post('user_id');
    	$pair_id=$this->input->post('pair_id');
    	if($user_id){
		$insert_data['user_id'] = $user_id;
		$insert_data['pair_id'] = $pair_id;
		$insert_data['user_ip'] = $_SERVER['REMOTE_ADDR'];
		$insert_data['created'] = gmdate(time());;
		$get_favourites = $this->common_model->getTableData('favourite_pairs', array('user_id' => $user_id,'pair_id'=>$pair_id))->row();
		if($get_favourites==0){
			$fav_ins=$this->common_model->insertTableData('favourite_pairs',$insert_data);
			if($fav_ins){
			$data['msg'] = 'Favourite added successfully';
			$data['status'] ='1';
			}else{
			$data['msg'] = 'Favourite added Error';
			$data['status'] ='0';
			}
					
		}else{
			$fav_del=$this->common_model->deleteTableData('favourite_pairs',array('id'=>$get_favourites->id));
			if($fav_del){
			$data['msg'] = 'Favourite removed successfully';
			$data['status'] ='1';
			}else{
			$data['msg'] = 'Favourite removed Error';
			$data['status'] ='0';
			}
				
		}
		// $data['result']= $this->common_model->getTableData('favourite_pairs', array('user_id' => $user_id))->result();	
		}

        echo json_encode($data);
			}
    
function kyc_photo_status(){
	$user_id=$this->input->post('user_id');
	$param=$this->input->post('photo_id');
	$photo=$this->common_model->getTableData('users',array('id'=>$user_id))->row();
	if($user_id=="" || $param==""){
		$array['status']=0;
		$array['msg']="User Id cannot Empty";
	}
	if($user_id!="" && $param=="address"){
		if($photo->photo_3_status==0 && $photo->photo_4_status==0){
			$array['status']=0;
			$array['img1']="";
			$array['img2']="";
			$array['msg']="Not Uploaded";
		}elseif($photo->photo_3_status==1 && $photo->photo_4_status==1){
			$array['status']=1;
			$array['img1']=$photo->photo_id_3;
			$array['img2']=$photo->photo_id_4;
			$array['msg']="Pending";
		}elseif($photo->photo_3_status==2 && $photo->photo_4_status==2){
			$array['status']=0;
			$array['img1']="";
			$array['img2']="";
			$array['msg']="Rejected";
		}else{
			$array['status']=1;
			$array['img1']=$photo->photo_id_3;
			$array['img2']=$photo->photo_id_4;
			$array['msg']="Verified";
		}
	}
	if($user_id!="" && $param=="id"){
		if($photo->photo_1_status==0 && $photo->photo_2_status==0){
			$array['status']=0;
			$array['img1']="";
			$array['img2']="";
			$array['msg']="Not Uploaded";
		}elseif($photo->photo_1_status==1 && $photo->photo_2_status==1){
			$array['status']=1;
			$array['img1']=$photo->photo_id_1;
			$array['img2']=$photo->photo_id_2;
			$array['msg']="Pending";
		}elseif($photo->photo_1_status==2 && $photo->photo_2_status==2){
			$array['status']=0;
			$array['img1']="";
			$array['img2']="";
			$array['msg']="Rejected";
		}else{
			$array['status']=1;
			$array['img1']=$photo->photo_id_1;
			$array['img2']=$photo->photo_id_2;
			$array['msg']="Verified";
		}
	}
	if($user_id!="" && $param=="selfie"){
		if($photo->photo_5_status==0){
			$array['status']=0;
			$array['img1']="";
			$array['img2']="";			
			$array['msg']="Not Uploaded";
		}elseif($photo->photo_5_status==1){
			$array['status']=1;
			$array['img1']=$photo->photo_id_5;
			$array['img2']="";
			$array['msg']="Pending";
		}elseif($photo->photo_5_status==2){
			$array['status']=0;
			$array['img1']="";
			$array['img2']="";
			$array['msg']="Rejected";
		}else{
			$array['status']=1;
			$array['img1']=$photo->photo_id_5;
			$array['img2']="";
			$array['msg']="Verified";
		}
	}
echo json_encode($array);    

}


    function check_currency_status(){
    	if($this->input->post()){
    	$user_id=$this->input->post('user_id');
    	$currency_id=$this->input->post('currency_id');
    	$transaction_type=$this->input->post('transaction_type');
    	if($transaction_type && $currency_id){
    		if($transaction_type=='deposit'){
			$type = 'deposit_status';
    		}
    		if($transaction_type=='withdraw'){
    		$type = 'withdraw_status';
    		}
    		else{
    		$data['msg'] = 'transaction type is empty';
			$data['status'] ='0';
    		}
    	$get_currency = $this->common_model->getTableData('currency', array('id' => $currency_id,$type=>0))->row();
    	if($get_currency){
    		if($user_id){
    		$crypto_address_data = getAddress($user_id,$currency_id);
    		if($crypto_address_data){
    			$crypto_address = $crypto_address_data;	
    		}else{
    			$crypto_address = 0;
    		}
    		$data['crypto_address'] = $crypto_address;
    		}
    		$data['msg'] = 'currency available';
			$data['status'] ='1';
    	}else{
    		if($transaction_type=='deposit'){
    		$data['msg'] = 'Sorry this crypto Unavailable for deposit';
    		}
    		if($transaction_type=='withdraw'){
    		$data['msg'] = 'Sorry this crypto Unavailable for withdraws';
    		}
			$data['status'] ='0';
    	}
    

    	}
	    }else{
	    	$data['msg'] = 'Currency and Type is Empty';
				$data['status'] ='0';
	    }
        echo json_encode($data);
	 }


	 function get_crypto_user_address(){
	 	$user_id=$this->input->post('user_id');
    	$currency_id=$this->input->post('currency_id');
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
		}else{
			$data['destination_tag'] = '';
		}
		$coin_balance = number_format(getBalance($user_id,$currency_id),$format);
		$data['coin_name'] = ucfirst($currency_det->currency_name);

		echo json_encode($data);



	 }


	 function update_crypto_user_address(){
	 	$user_id=$this->input->post('user_id');
		$currency_id = $this->input->post('currency_id');
		$generate_address = $this->update_user_address_by_currency($user_id,$currency_id);
		if($this->input->post()){
		$coin_address = getAddress($user_id,$currency_id);
		$data['msg'] = 'Address Created Successfully';
		$data['status'] ='1';
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
		}else{
			$data['destination_tag'] = '';
		}
		$coin_balance = number_format(getBalance($user_id,$currency_id),$format);
		$data['coin_name'] = ucfirst($currency_det->currency_name);
		$data['coin_balance'] = $coin_balance;
	}else{
		$data['msg'] = 'Address Created Failed';
		$data['status'] ='0';
	}
		echo json_encode($data);
	 }


    function update_user_address_by_currency($user_id,$currency_id)
    {
    	$coin_address = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1','id'=>$currency_id))->row();
    	if($coin_address){
    	$user_details = $this->common_model->getTableData('crypto_address',array($coin_address->currency_symbol.'_status'=>'0','user_id'=>$user_id),'','','','','','',array('id','DESC'))->row();
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
		            	if($coin_address->crypto_type=='eth'){
		            	$eth_id = $this->common_model->getTableData('currency',array('currency_symbol'=>'ETH'))->row('id');
						$eth_address = getAddress($user_details->user_id,$eth_id);
					}

					updateAddress($user_details->user_id,$coin_address->id,$eth_address);
		  
		            }
				}



    	}
	
    } 
public function getOrderHistoryApi($pair_symbol,$user_id)
{
	//$user_id = $this->session->userdata('user_id');
$orders=array();
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
	$selectFields='CO.*,SUM(CO.Amount) as TotAmount,date_format(CO.datetime,"%d-%m-%Y %H:%i") as trade_time,sum(OT.filledAmount) as totalamount';

	$names = array('filled','partially');
	$where=array('CO.pair'=>$pair_id);
	$where=array('CO.userId'=>$user_id);
	$orderBy=array('CO.trade_id','desc');
	//$groupBy=array('CO.Price'); // commented due to live server group by issue
	$groupBy=array('CO.trade_id');
	$where_in=array('CO.status', $names);
	$joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
	$query = $this->common_model->getleftJoinedTableData('coin_order as CO',$joins,$where,$selectFields,'','','','','',$orderBy,$groupBy,$where_in);


	if($query->num_rows() >= 1)
	{
		$result = $query->result();
	}
	else
	{
		$result = 0;
	}
	if($result&&$result!=0)
	{
		// foreach($result as $val)
		// {
		// 	if($val->status == 'partially')
		// 	{
		// 		$val->balance = $val->Amount - $val->totalamount;
		// 	} else {
		// 		$val->balance = '-';
		// 	}
		// }
		$orders=$result;
	}
	else
	{
		$orders=[];
	}
   return $orders;

}




}