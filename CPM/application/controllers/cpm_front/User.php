<?php
use Twilio\Rest\Client;

class User extends CI_Controller {
	public function __construct()
	{	
		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation'));
		$this->load->library('session');
		$this->load->helper(array('url', 'language'));
		$lang_id = $this->session->userdata('site_lang');

		// $this->load->library('API/mollie_api_autoloader');
		// $this->load->library('API/mollie_api_client');



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
		if($user_id!="")
		{	
			front_redirect('', 'refresh');
		}

		

		$data['site_common'] = site_common();
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
		$data['login_content'] = $this->common_model->getTableData('static_content',array('slug'=>'login_content'))->row();
		$data['footer'] = $this->common_model->getTableData('static_content',array('slug'=>'footer'))->row();

		$data['action'] = front_url() . 'login_user';		
		$this->load->view('front/user/login', $data);
	}
	public function login_check()
    {
        $ip_address = get_client_ip();
        $array = array('status' => 0, 'msg' => '');
        $this->form_validation->set_rules('login_detail', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('login_password', 'Password', 'trim|required|xss_clean');
        // When Post

        if($this->block() == 1)
		{ 
			
			$array['msg'] = 'Your ip is blocked. Please Contact Admin!';
			die(json_encode($array));

		}


        if ($this->input->post()) {

            if ($this->form_validation->run()) {

                $email = lcfirst($this->input->post('login_detail'));
                $password = $this->input->post('login_password');
                $prefix = get_prefix();
                // Validate email
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $check = checkSplitEmail($email, $password);
                }
                if (!$check) {
                    //vv
                    $array['msg'] = 'Enter Valid Login Details Or Your Account may be deactivated by Admin!';
                } else {
                    if ($check->verified != 1) {

                        $array['msg'] = 'Please check your email to activate CryptoPool account';
                      
                    } else {
                        $array['status'] = 1;
                        if ($check->randcode == 'enable' && $check->secret != '') { 
                            $array['tfa_status'] = 1;
                            $login_tfa = $this->input->post('login_tfa');
                            $check1 = $this->checktfa($check->id, $login_tfa);
                            if ($check1) {
                                $session_data = array(
                                    'user_id' => $check->id,
                                );
                                $this->session->set_userdata($session_data);
                                // $this->common_model->last_activity('Login', $check->id);
                                $this->common_model->last_activity('Login', $check->id, "", $ip_address);  
                                $this->session->set_flashdata('success', $this->lang->line('Welcome back . Logged in Successfully'));
                                $array['msg'] = $this->lang->line('Welcome back . Logged in Successfully');
                                if ($check->verify_level2_status == 'Completed') {
                                    $array['login_url'] = 'dashboard';
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
                            $this->session->set_flashdata('success', 'Welcome back . Logged in Successfully');
                            $array['msg'] = 'Welcome back . Logged in Successfully';
                            $array['login_url'] = 'dashboard';
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
		if($user_id!="")
		{	
			front_redirect('', 'refresh');
		}
		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link' => 'forgot_password'))->row();
		$data['action'] = front_url() . 'forgot_user';
		$data['js_link'] = 'forgot';
		$this->load->view('front/user/forgot_password', $data);
	}
	function forgot_check()
	{
		$ip_address = get_client_ip();
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
					$check=checkEmailfun($email);
					$type=1;
				} 
				else
				{
					$check=checkElseEmail($email);
					$type=2;
				}
				if (!$check)
				{
					$array['msg']=$this->lang->line('User does not Exists');
				}
				else
				{

						$array['status']=1;
						$key = sha1(mt_rand() . microtime());
						$update = array(
						'forgotten_password_code' => $key,
						'forgotten_password_time' => time(),
						'forgot_url'=>0
						);

				
				$this->common_model->last_activity('Forgot Password',$check->id,'',$ip_address);
				$this->common_model->updateTableData('users',array('id'=>$check->id),$update);			

				$to      	= getUserEmail($check->id);
				$email_template = 3;
				$username=$prefix.'username';
				$link=front_url().'reset_pw_user/'.$key;
				$site_common      =   site_common();
				
				$special_vars = array(					
					'###USERNAME###' => $check->$username,
					'###LINK###' => $link
				);


				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

				$array['msg']= 'Password reset link is sent to your email';

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
		$data['site_common'] = site_common();
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
							front_redirect('login','refresh');
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
					//front_redirect('', 'refresh');
					$this->load->view('front/user/reset_pwd', $data);
				}
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('Already reset password using this link'));
				//front_redirect('', 'refresh');
				$this->load->view('front/user/reset_pwd', $data);
			}
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('Not a valid link'));
			//front_redirect('', 'refresh');
			$this->load->view('front/user/reset_pwd', $data);
		}
	}
	function signup()
	{		
		
		$data['site_common'] = site_common();
		
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'signup'))->row();
		$data['signup_content'] = $this->common_model->getTableData('static_content',array('slug'=>'signup_content'))->row();
		$newuser_reg_status = getSiteSettings('newuser_reg_status');
		$user_id=$this->session->userdata('user_id');
		// echo $userid;
		// exit();
		if($user_id!="")
		{	
			front_redirect('', 'refresh');
		}
		if($this->block() == 1)
		{ 
			front_redirect('block_ip');
		}
		
		// When Post		
		if(!empty($_POST))
		{ 
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('register_email', 'Email Address', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('register_password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('country', 'country', 'trim|required|xss_clean');

			if ($this->form_validation->run())
			{ 
				$email = $this->db->escape_str(lcfirst($this->input->post('register_email')));
				$password = $this->db->escape_str($this->input->post('register_password'));
				$uname = $this->db->escape_str($this->input->post('register_uname'));
				$country = $this->db->escape_str($this->input->post('country'));
				//$usertype = $this->db->escape_str($this->input->post('usertype'));
				$check=checkSplitEmail($email);
				$prefix=get_prefix();
				//$check1=$this->common_model->getTableData('users',array($prefix.'username'=>$uname));
				if($check)
				{
					$this->session->set_flashdata('error', $this->lang->line('Entered Email Address Already Exists'));
					front_redirect('signup', 'refresh');
				}
				else
				{				

					$permitted_chars = '8514890089abcdefghijklmnopytqjpstuvwxyz';
	                $refferalid=substr(str_shuffle($permitted_chars), 0, 10);

					$Exp = explode('@', $email);
					$User_name = $Exp[0];

					$activation_code = time().rand(); 
					$str=splitEmail($email);
					$ip_address = get_client_ip();
					$refferalids = $this->input->post('referral_id');
					$ref_check=$this->common_model->getTableData('users',array('referralid'=>$refferalids))->row();
                    if($refferalids != '' && count($ref_check)>0){
                        $ref = $refferalids;
                    }else{
                    	$ref = 0;
                    }
                    

					$user_data = array(
					'usertype' => '1',
					$prefix.'email'    => $str[1],
					'country' => $country,
					$prefix.'username'	=> $uname,
					$prefix.'password' => encryptIt($password),
					'activation_code'  => $activation_code,
					'verified'         =>'0',
					'register_from'    =>'Website',
					'ip_address'       =>$ip_address,
					'browser_name'     =>getBrowser(),
					'verification_level' =>'1',
					'created_on' =>gmdate(time())
					// 'parent_referralid'=>$ref,
					// 'referralid' => $refferalid
					);
					 
					$user_data_clean = $this->security->xss_clean($user_data);
					$id=$this->common_model->insertTableData('users', $user_data_clean);
					if($ref!= '0'){
			        $ref_count = $this->db->select('COUNT(parent_referralid) as total')->from('users')->where('parent_referralid',$ref)->get()->row();
			       $ref_update=$this->common_model->updateTableData('users',array('referralid'=>$ref),array('successful_referral'=>$ref_count->total));
			     //    if($ref_count->total > 0 && $ref_update){
			     //    $this->referral_commission($ref);
				    // } 
				    }

					$usertype=$prefix.'type';
					$this->common_model->insertTableData('history', array('user_id'=>$id, $usertype=>encryptIt($str[0])));
					// $this->common_model->last_activity('Registration',$id);

					$this->common_model->last_activity('Registration', $id, "", $ip_address);  

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
					'###EMAIL###' => $this->input->post('register_email'), 
					'###LINK###' => front_url().'verify_user/'.$activation_code
					);
					
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					$this->session->set_flashdata('success','Thank you for Signing up. Please check your e-mail and click on the verification link.');
					front_redirect('login', 'refresh');
				}
			}
			else 
			{

				$this->session->set_flashdata('error', validation_errors());
				front_redirect('signup', 'refresh');
			}	
		}

		$data['footer'] = $this->common_model->getTableData('static_content',array('slug'=>'footer'))->row();
		
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['site_common'] = site_common();
		$data['action'] = front_url() . 'signup';	
		$this->load->view('front/user/register', $data);
	}
	public function oldpassword_exist()
	{



		$oldpass = $this->db->escape_str($this->input->post('oldpass'));
		$prefix=get_prefix();
		$check=$this->common_model->getTableData('users',array($prefix.'password'=>encryptIt($oldpass)))->result();
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
		$check=checkEmailExist($email);
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

		$ip_address = get_client_ip();
		$activation_code=$this->db->escape_str($activation_code);
		$activation=$this->common_model->getTableData('users',array('activation_code'=>urldecode($activation_code)));
		// echo "<pre>";print_r($activation->num_rows());die;
		if ($activation->num_rows()>0)
		{
			$detail=$activation->row(); 
			if($detail->verified==1)
			{
				$this->session->set_flashdata('error', $this->lang->line('Your Email is already verified.'));
				front_redirect("", 'refresh');
			}
			else
			{
				$this->common_model->updateTableData('users',array('id'=>$detail->id),array('verified'=>1));
				// $this->common_model->last_activity('Email Verification',$detail->id);

				$this->common_model->last_activity('Email Verification', $detail->id, "", $ip_address); 

				$this->session->set_flashdata('success', $this->lang->line('Your Email is verified now.'));
				front_redirect("", 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('Activation link is not valid'));
			front_redirect("", 'refresh');
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
		$this->load->library('Googleauthenticator');
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link'=>'settings'))->row();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['user_bank'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id))->row();
		$data['category'] = $this->common_model->getTableData('support_category', array('status' => '1'))->result();
		$data['support'] = $this->common_model->getTableData('support', array('user_id' => $user_id, 'parent_id'=>0))->result();

		if($data['users']->randcode=="enable" || $data['users']->secret!="")
		{	
			$secret = $data['users']->secret; 
			$data['secret'] = $secret;
        	$ga     = new Googleauthenticator();
			$data['url'] = $ga->getQRCodeGoogleUrl('Bitwhalex', $secret);
		}
		else
		{
			$ga = new Googleauthenticator();
			$data['secret'] = $ga->createSecret();
			$data['url'] = $ga->getQRCodeGoogleUrl('Bitwhalex', $data['secret']);
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
					front_redirect('profile', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Confirm password must be same as new password'));
					front_redirect('profile', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('Your old password is wrong'));
				front_redirect('profile', 'refresh');
			}			
		}

		if(isset($_POST['tfa_sub']))
		{
			$ga = new Googleauthenticator();
			$secret_code = $this->db->escape_str($this->input->post('secret'));
			$onecode = $this->db->escape_str($this->input->post('code'));
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
					front_redirect('profile?page=tfa', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error','Please Enter correct code to enable TFA');
					front_redirect('profile?page=tfa', 'refresh');
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
					front_redirect('profile?page=tfa', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error','Please Enter correct code to disable TFA');
					front_redirect('profile?page=tfa', 'refresh');
				}
			}
		}

		
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['currencies'] = $this->common_model->getTableData('currency',array('status'=>1,'type'=>'fiat'))->result();
		$data['site_common'] = site_common();
		$this->load->view('front/user/profile', $data); 
	}
	function editprofile()
	{		 
		$this->load->library('session','form_validation');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', 'Please Login');
			redirect(base_url().'home');
		}
		if($_POST)
		{
			$this->form_validation->set_rules('address', 'Address', 'required|xss_clean');
			if($this->form_validation->run())
			{
				$insertData['cpm_fname'] = $this->db->escape_str($this->input->post('firstname'));
				$insertData['cpm_lname'] = $this->db->escape_str($this->input->post('lastname'));
				$insertData['street_address'] = $this->db->escape_str($this->input->post('address'));
				$insertData['city'] = $this->db->escape_str($this->input->post('city'));
				$insertData['state'] = $this->db->escape_str($this->input->post('state'));
				$insertData['postal_code'] = $this->db->escape_str($this->input->post('postal_code'));

				// $insertData['perfect_account']      = $this->db->escape_str($this->input->post('perfect_account'));
				// $insertData['perfect_account_name'] = $this->db->escape_str($this->input->post('perfect_account_name'));

				$paypal_email = $this->input->post('paypal_email');
				if(isset($paypal_email) && !empty($paypal_email)){
				$insertData['paypal_email'] = $this->db->escape_str($paypal_email);
			}				
				$insertData['verification_level'] = '2';
				$insertData['verify_level2_date'] = gmdate(time());
				$insertData['country']	 	   = $this->db->escape_str($this->input->post('register_country'));
				$insertData['cpm_phone']	= $this->db->escape_str($this->input->post('phone'));
				$condition = array('id' => $user_id);
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);



				if ($_FILES['profile_photo']['name']!="") 
				{
					$imagepro = $_FILES['profile_photo']['name'];
					if($imagepro!="" && getExtension($_FILES['profile_photo']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["profile_photo"],'uploads/user/'.$user_id,$this->input->post('profile_photos'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
						$insertData['profile_picture']=$imagepro;
					}
					else
					{
						$this->session->set_flashdata('error', 'Please upload png,jpeg,jpg,gif,svg format file only!');
						front_redirect('profile', 'refresh');
					}				
					
				}
				
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
				if ($insert) {
					$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));
					$this->session->set_flashdata('success', 'Profile details Updated Successfully');
					front_redirect('profile', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Something ther is a Problem .Please try again later');
					front_redirect('profile', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error','Some datas are missing');
				front_redirect('profile', 'refresh');
			}
		}		
		front_redirect('profile', 'refresh'); 
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
					$this->session->set_flashdata('error', $this->lang->line('Something ther is a Problem .Please try again later'));
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


	function kyc_verification()
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
		}
		$photo_1_status = getUserDetails($user_id,'photo_1_status');
		if($_FILES['photo_id_1']['name'] && $photo_1_status!=1){				
		$prefix=get_prefix();


					// Address
					$image = $_FILES['photo_id_1']['name'];
					if(isset($_FILES['photo_id_1']['name']) && getExtension($_FILES['photo_id_1']['type']))
					{		
						if($image!=""){
						$Img_Size = $_FILES['photo_id_1']['size'];
						if($Img_Size>3000000){
							$this->session->set_flashdata('error','File Size Should be less than 3 MB');
						}
						$uploadimage=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_1')));
						if(is_array($uploadimage))
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							
							$this->session->set_flashdata('error','Problem with your scan of photo id');
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
						$this->session->set_flashdata('success','Your details have been sent to our team for verification');
					} 
	                elseif($insert !='' && $_FILES["photo_id_1"]['name'] =='') {
						$this->session->set_flashdata('success', 'Your Address proof cancelled successfully');
					}
					else {
						$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
					}
				}else{
					$this->session->set_flashdata('error','Please upload png,jpeg,jpg,gif,svg format file only!');
				}

				// Identity 

		}
		// else if($_FILES['photo_id_2']['name']){	

		

		$photo_2status = getUserDetails($user_id,'photo_2_status');
		$image = $_FILES['photo_id_2']['name'];
		if($photo_2status!=1 && $image!='') {
 
			

					if(getExtension($_FILES['photo_id_2']['type']))
					{
					if($image!=""){		

						$Img_Size = $_FILES['photo_id_2']['size'];
						if($Img_Size>3000000){
							$this->session->set_flashdata('error','File Size Should be less than 3 MB');
						}

						$uploadimage=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_2')));
						if(is_array($uploadimage))
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							$this->session->set_flashdata('error','Problem with your scan of photo id');
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
						$this->session->set_flashdata('success','Your details have been sent to our team for verification');
					} 
	                elseif($insert !='' && $_FILES["photo_id_2"]['name'] =='') {
						$this->session->set_flashdata('success', 'Your ID proof cancelled successfully');
					}
					else {
						$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
					}
				}else{
					$this->session->set_flashdata('error','Please upload png,jpeg,jpg,gif,svg format file only!');
				}

			}	

		// }

		// Selfie 
		// else if($_FILES['photo_id_3']['name']){	

			$image = $_FILES['photo_id_3']['name'];

			if($image!='') {
					if(getExtension($_FILES['photo_id_3']['type']))
					{		
						if($image!=""){
						$Img_Size = $_FILES['photo_id_3']['size'];
						if($Img_Size>3000000){
							$this->session->set_flashdata('error','File Size Should be less than 3 MB');
						}

						$uploadimage=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
						if(is_array($uploadimage))
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							
							$this->session->set_flashdata('error', 'Problem with your scan of photo id');
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
						$this->session->set_flashdata('success','Your details have been sent to our team for verification');
					} 
	                elseif($insert !='' && $_FILES["photo_id_3"]['name'] =='') {
						$this->session->set_flashdata('success', 'Your Photo cancelled successfully');
					}
					else {
						$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
					}
				}else{
					$this->session->set_flashdata('error','Please upload png,jpeg,jpg,gif,svg format file only!');
				}

			}


		// }
		front_redirect('kyc', 'refresh');



	}






	
	function settings()
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', 'Please Login');
			redirect(base_url().'home');
		}
		$this->load->library('Googleauthenticator');
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link'=>'settings'))->row();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		/*echo "<pre>";
		print_r($data['users']);
		exit();*/
		$data['user_bank'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id))->row();
		if($data['users']->randcode=="enable" || $data['users']->secret!="")
		{	
			$secret = $data['users']->secret; 
			$data['secret'] = $secret;
        	$ga     = new Googleauthenticator();
			$data['url'] = $ga->getQRCodeGoogleUrl('CryptoPool', $secret);
		}
		else
		{
			$ga = new Googleauthenticator();
			$data['secret'] = $ga->createSecret();
			$data['url'] = $ga->getQRCodeGoogleUrl('CryptoPool', $data['secret']);
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
					front_redirect('settings', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Confirm password must be same as new password'));
					front_redirect('settings', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('Your old password is wrong'));
				front_redirect('settings', 'refresh');
			}			
		}
		
		$data['site_common'] = site_common();

		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['currencies'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1))->result();

		$this->load->view('front/user/settings', $data);
	}



	// Bank Details Change

	function bank_details($coin='')
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', 'Please Login');
			redirect(base_url().'home');
		}
		$this->load->library('Googleauthenticator');
		$data['meta_content'] = $this->common_model->getTableData('meta_content', array('link'=>'settings'))->row();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		




	if($coin > 0) {

		$data['user_bank'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id,'currency'=>$coin))->row();
		$data['act_cur'] = $coin;

		// Fiat Check 
		$currency=$this->common_model->getTableData('currency',array('id'=>$coin,'type'=>'fiat'))->row();
		if(empty($currency))
		{
			$this->session->set_flashdata('error','This is Not Fiat Currency');
			front_redirect('settings', 'refresh');	
		}

			
	}


		if($data['users']->randcode=="enable" || $data['users']->secret!="")
		{	
			$secret = $data['users']->secret; 
			$data['secret'] = $secret;
        	$ga     = new Googleauthenticator();
			$data['url'] = $ga->getQRCodeGoogleUrl('CryptoPool', $secret);
		}
		else
		{
			$ga = new Googleauthenticator();
			$data['secret'] = $ga->createSecret();
			$data['url'] = $ga->getQRCodeGoogleUrl('CryptoPool', $data['secret']);
			$data['oneCode'] = $ga->getCode($data['secret']);
		}

		// print_r($data['act_cur']);
		// exit();
		
		$data['site_common'] = site_common();

		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['currencies'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1))->result();

		$this->load->view('front/user/settings', $data);
	}






	function support()
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'support'))->row();
		if(isset($_POST['submit_tick']))
		{
			
			$image = $_FILES['image']['name']; 
			if($image!="") {
				if(getExtension($_FILES['image']['type']))
				{		

					$Img_Size = $_FILES['image']['size'];
						if($Img_Size>2000000){
							$this->session->set_flashdata('error','File Size Should be less than 2 MB');
							front_redirect('support', 'refresh');
						}
					
					$uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$user_id);
					if(is_array($uploadimage1))
					{
						$image=$uploadimage1['secure_url'];

					}
					else
					{
						$errorMsg = current( (Array)$uploadimage1 );
						$this->session->set_flashdata('error', $errorMsg);
						front_redirect('support', 'refresh');
						$this->session->set_flashdata('error', 'Error occur!! Please try again');
						front_redirect('support', 'refresh');
					}
					$image=$image;
				}
				else
				{
					$this->session->set_flashdata('error','Please upload proper image format');
					front_redirect('support', 'refresh');	
				}
			} 
			else 
			{ 
				$image = "";
			}
			$insertData['user_id'] = $user_id;
			// $insertData['subject'] = $this->input->post('subject');

			$insertData['subject'] = strip_tags(trim($this->input->post('subject')));


			$insertData['message'] = strip_tags(trim($this->input->post('message')));
			$insertData['category'] = $this->input->post('category');
			$insertData['image'] = $image;
			$insertData['created_on'] = gmdate(time());
			$insertData['ticket_id'] = 'TIC-'.encryptIt(gmdate(time()));
			$insertData['status'] = '1';

			// print_r($insertData);exit();
			$insert = $this->common_model->insertTableData('support', $insertData);
			if ($insert) {

				$email_template   	= 'Support_admin';
				$email_template_user   	= 'Support_user';
				$site_common      	=   site_common();

                $enc_email = getAdminDetails('1','email_id');
                $adminmail = decryptIt($enc_email);
                $usermail = getUserEmail($user_id);
                $username = getUserDetails($user_id,'cpm_username');
                $message = strip_tags(trim($this->input->post('message')));
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

				$this->session->set_flashdata('success', 'Your message successfully sent to our team');
				front_redirect('support', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Error occur!! Please try again');
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
		$data['support_reply'] = $this->db->query("SELECT * FROM `cpm_support` WHERE `parent_id` = '".$id."'")->result();
		if($_POST)
		{
			$image = $_FILES['image']['name'];
			if($image!="") {
				if(getExtension($_FILES['image']['type']))
				{			
					$uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$user_id);
					if(is_array($uploadimage1))
					{
						$image=$uploadimage1['secure_url'];
					}
					else
					{
						$errorMsg = current( (Array)$uploadimage1 );
						$this->session->set_flashdata('error', $errorMsg);
						front_redirect('support_reply/'.$code, 'refresh');
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
				$insertData['message'] = strip_tags(trim($this->input->post('message')));
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
	                $username = getUserDetails($user_id,'cpm_username');
	                $message = strip_tags(trim($this->input->post('message')));
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

					$this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars);
					$this->email_model->sendMail($usermail, '', '', $email_template_user, $special_vars_user);

					$this->session->set_flashdata('success', 'Your message successfully sent to our team');
					front_redirect('support_reply/'.$code, 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Error occur!! Please try again');
					front_redirect('support_reply/'.$code, 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error', 'Error occur!! Please try again');
				front_redirect('support_reply/'.$code, 'refresh');
			}
		}
		$data['code'] = $code;
		$data['user_detail'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
        $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['action'] = front_url() . 'support_reply/'.$code;
		$this->load->view('front/user/support_reply', $data);
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
		
		$coin_balance = number_format(getBalance($user_id,$currency_id),$format);
		$data['coin_name'] = ucfirst($currency_det->currency_name);
		$data['coin_balance'] = $coin_balance;
		$data['withdraw_fees'] = $currency_det->withdraw_fees;
		$data['withdraw_limit'] = $currency_det->max_withdraw_limit;
		$data['withdraw_type'] = $currency_det->withdraw_fees_type; 
		echo json_encode($data);
    } 



    function change_bank()
	{	
		$user_id=$this->session->userdata('user_id');
		$currency_id = $this->input->post('currency_id');
		if($currency_id!='')
		{
			
			$data['banks'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id,'currency'=>$currency_id))->row();
			$data['country'] = get_countryname($data['banks']->bank_country);
			$data['symbol'] = getcryptocurrency($data['banks']->currency);
			if(isset($data['banks']))
			{
				$data['status'] =1;
			}
			else
			{	$data['status'] =0;
				
			}
			echo json_encode($data);

			
		}
	} 


    	    function currency_convert()
	{	
		$user_id=$this->session->userdata('user_id');
		// $currency_id = $this->input->post('currency_id');

		$crypto = $this->input->post('crypto');
		$fiat = $this->input->post('fiat');

		if($crypto!='' && $fiat!='')
		{
			
			$crypto_currency = getcurrencySymbol($crypto);
			$fiat_currency = getcurrencySymbol($fiat);
			$data = convercurrs($crypto_currency,$fiat_currency,'');				
			
			// echo $decode;
			echo $data;

		}
	} 



    function update_user_address()
    {
		// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    	$Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result(); 
		// print_r($Fetch_coin_list); exit;
			
			    	// exit();


		foreach($Fetch_coin_list as $coin_address)
		{
    		$userdetails = $this->common_model->getTableData('users',array('status'=>1),'','','','','','',array('id','ASC'))->result();

    		
    		// exit();

	    	foreach($userdetails as $user_details) 
	    	{
	    		

	    		$User_Address = $this->common_model->getTableData('ethers_wallet',array('user_id'=>$user_details->id,'type'=>'ethers'))->row();

	    		


	    		if(empty($User_Address) || $User_Address==0)
	    		{



					$parameter = '';



	                if($coin_address->coin_type=="coin")
	                {

	                	if($coin_address->currency_symbol!='')
						{   
							
							$ethers = ethers('address_genration','Kc7lWwSe5BhywcCvgtM');
							$address = $ethers['address'];
		            		$encry_key = encryptIt(encryptIt($ethers['addrkey']));

							if($address!='' && $encry_key!='')
		            		{
		            			ethers_addressupdate($user_details->id,$address,$encry_key); 
		            		}
						}
		            }

		            else
		            { 

		            	// if($coin_address->crypto_type=='bsc' || $coin_address->crypto_type=='erc20'){



		     //        		$web3 = web3('address_genration','Kc7lWwSe5BhywcCvgtM');
							// $address = $web3['address'];
		     //        		$encry_key = encryptIt(encryptIt($web3['addrkey']));

							// if($address!='' && $encry_key!='')
		     //        		{
		     //        			web3_addressupdate($user_details->id,$address,$encry_key); 
		     //        		}

		            	// } 
						   
					
		            
		            }
		           
				}
			}
		}		
    }

 

    function get_user_list_coin($curr_id,$crypto_type)
	{


	
		$currency=$this->common_model->getTableData('currency',array('status'=>1, 'type'=>'digital','id'=>$curr_id),'','','','','',1)->row();
		$curr_symbol = $currency->currency_symbol;
    $selectFields='US.id as id,CA.address as address,HI.cpm_type as cpm_type,US.cpm_email as email';
  $where=array('US.verified'=>1,$curr_symbol.'_status'=>1);
  //$where=array('US.verified'=>1,'US.id'=>9429);
  $orderBy=array('US.id','asc');
  $joins = array('crypto_address as CA'=>'CA.user_id = US.id','history as HI'=>'HI.user_id = US.id');
  $users = $this->common_model->getJoinedTableData('users as US',$joins,$where,$selectFields,'','','','','',$orderBy)->result();

		$rude = array();

        //Binance Usd

		if($crypto_type == 'bsc' || $crypto_type == 'tron'|| $crypto_type == 'eth') {
			// for eth,trx and bsc
			// echo "get_user_list_coin_final bsc tron and eth<br/>";
			// echo $crypto_type."<br/>";
			// print_r($users);
			foreach($users as $user)
			{	
				// echo "USER".$user->id."<br/>";
				/*$wallet = unserialize($this->common_model->getTableData('crypto_address',array('user_id'=>$user->id),'address','','','','',1)->row('address'));	
				
				$email = getUserEmail($user->id);*/
        $wallet = unserialize($user->address);

        $email = decryptIt($user->cpm_type).$user->email;

				//$currency=$this->common_model->getTableData('currency',array('status'=>1, 'type'=>'digital','id'=>$curr_id))->result();			

				/*$i = 0;
				foreach($currency as $cu)
				{*/

						$count = strlen($wallet[$currency->id]);
						//echo $count."<br>";

						
						
						if(!empty($wallet[$currency->id]) && $count!=1)
						{
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
										echo $val;
										if($currency->coin_type=="token" && $val=='tron')
										{
											$tron_private = gettronPrivate($user->id);
											$crypto_type_other = array('crypto'=>$val,'tron_private'=>$tron_private);
											$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[$currency->id],$crypto_type_other);
											// echo "<br/>".$wallet[$currency->id]."<br/>".$Wallet_balance."<br/>";

											// if($Wallet_balance>0){
												$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
													'currency_name'=>$currency->currency_name,
													'currency_id'=>$curr_id,
													'address'=>$wallet[$currency->id],
													'user_id'=>$user->id,
													'user_email'=>$email);
												array_push($rude, $balance[$user->id]); 
											// }
										} 
										else if($currency->coin_type=="token" && $val=='bsc')
										{

											$crypto_type_other = array('crypto'=>$val);
											$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[$currency->id],$crypto_type_other);
											// echo "<br/>".$wallet[$currency->id]."<br/>".$Wallet_balance."<br/>";

											// if($Wallet_balance>0){
												$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
													'currency_name'=>$currency->currency_name,
													'currency_id'=>$curr_id,
													'address'=>$wallet[$currency->id],
													'user_id'=>$user->id,
													'user_email'=>$email);
												array_push($rude, $balance[$user->id]); 

												


											// }
										}
										else
										{
											$crypto_type_other = array('crypto'=>$val);
											$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[$currency->id],$crypto_type_other);
											// echo "<br/>Address".$wallet[$currency->id]."<br/>".$Wallet_balance."<br/>";

											// if($Wallet_balance>0){
												$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
													'currency_name'=>$currency->currency_name,
													'currency_id'=>$currency->id,
													'address'=>$wallet[$currency->id],
													'user_id'=>$user->id,
													'user_email'=>$email);
												array_push($rude, $balance[$user->id]); 
											// }
										}
									}
								}
								//exit;
							} else {
								echo "Normal CRYPTO Type";
								echo "<br/>";
								if($currency->coin_type=="token" && $crypto_type=='tron')
								{

									
									$tron_private = gettronPrivate($user->id);
									$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[$currency->id],$tron_private);
									echo $wallet[$currency->id]."<br/>".$Wallet_balance."<br/>";

									// if($Wallet_balance>0){
										$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
											'currency_name'=>$currency->currency_name,
											'currency_id'=>$currency->id,
											'address'=>$wallet[$currency->id],
											'user_id'=>$user->id,
											'user_email'=>$email);
										array_push($rude, $balance[$user->id]); 
									// }
								}
								else
								{
									$Wallet_balance = $this->local_model->wallet_balance($currency->currency_name,$wallet[$currency->id]);
									// echo $wallet[$currency->id]."<br/>".$Wallet_balance."<br/>";

									// if($Wallet_balance>0){
										$balance[$user->id] = array('currency_symbol'=>$currency->currency_symbol, 
											'currency_name'=>$currency->currency_name,
											'currency_id'=>$currency->id,
											'address'=>$wallet[$currency->id],
											'user_id'=>$user->id,
											'user_email'=>$email);
										array_push($rude, $balance[$user->id]); 
									// }
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
				// echo "USER".$user->id."<br/>";
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



		return $rude;	 
	}

public function get_user_with_dep_det($curr_id,$crypto_type)
	{
       

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


	// cronjob for deposit -  new method
	public function update_crypto_deposits($coin='') // cronjob for deposit
	{ 


		// Modified this method to accomodate dynamic USDT deposits(erc20,trc20 and beb20) for single token
		// modified in get_user_with_dep_det method with crypto_type_other field

		//$currencies = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','')->row();
		$currencies   =  $this->db->query("select * from cpm_currency where  status = 1 AND deposit_status = 1 ")->result(); // get user addresses
		// echo "<pre>";
		// print_r($currencies); exit;

		if(count($currencies) > 0)
		{
			// echo "Process begins<br/>";
			foreach($currencies as $curr)
			{
				// echo "<pre>";
				// echo $curr->currency_name;
				// echo "<br/>";
				// dynamic call for currencies
				// echo "<pre>";
				// print_r($curr); exit;
				
				$crypto_type = $curr->crypto_type_other;
				if($crypto_type != '')
				{
					// echo $crypto_type;
					// echo "<br/>";
					// ERC, TRX and BSC Tokens
					$crypto_type_arr = explode("|",$crypto_type);
					foreach($crypto_type_arr as $val)
					{
						
						$crypto_type = $curr->crypto_type_other;
						$this->coinpayments_crypto_deposit($curr,$val);
					}

				} else {

					if($curr->currency_symbol=='DOGE') { 
					// Other coin
					$crypto_type = $curr->crypto_type;
					$this->coinpayments_crypto_deposit($curr,$crypto_type);

					}

				}

			}
		}

		
	}



public function coinpayments_crypto_deposit($curr,$crypto_type)
	{


		echo " Coinpayments.. ";
		// exit(); 

		$curr_id = $curr->id;
		$coin_name =  $curr->currency_name;
		$curr_symbol = $curr->currency_symbol;
		$coin_type = $curr->coin_type;
		
		$Deposit_Fees_type = $curr->deposit_fees_type;
		$Deposit_Fees = $curr->deposit_fees;
		$Deposit_Fees_Update = 0;
		$coin_name1 =  $this->common_model->getTableData('currency',array('currency_symbol'=>$curr_symbol),'','','','','',1)->row('currency_name');


		//Db Call based on coin - retrieve
			// crypto_type_other -


		// echo $curr_id.'<br>';
		// echo $curr_symbol.'<br>';
		// echo $coin_type.'<br>';
		// echo $crypto_type.'<br>';
		// exit; 
		$user_trans_res   = $this->get_user_with_dep_det($curr_id,$crypto_type);


		$address_list     = $user_trans_res['address_list'];
		$transactionIds   = $user_trans_res['transactionIds'];
		$tot_transactions = array();

		// echo "<pre>";
		// print_r($user_trans_res);
		// echo "<pre>"; 
		// exit(); 
		

		//$valid_server = $this->local_model->get_valid_server();
		$valid_server=1;

		/*$coin_type = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name1),'','','','','',1)->row('coin_type');

		$crypto_type = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name1))->row('crypto_type');*/
		


		if($valid_server)
		{


			if($coin_type=="coin")
			{
				echo " Coin.. "."<br>";
				
				$model_name = 'Coinpayments_wallet_model';
				$model_location = 'wallets/Coinpayments_wallet_model';           
				$this->load->model($model_location,$model_name);	

				// Coinpayments Blochcypher API Request

				$coin_sym = strtolower($curr_symbol); 
				$transactions = $this->$model_name->listalltransactions($user_trans_res,$coin_sym);


				 

			}
			

			else
			{ 
			// Token Logic   
			if($coin_name=='CPM')
			{
		
				// $transactions 	 = $this->local_model->get_transactions($coin_name1,$user_trans_res,$crypto_type);
			}
			                

			
		}
			// echo $coin_name1;
			// echo "<pre>mm"; print_r($user_trans_res); echo "</pre>"; //exit(); 

			// echo "<pre> TT"; print_r($transactions); echo "</pre>";
			// exit();       

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
					if(isset($Deposit_Fees_type) && !empty($Deposit_Fees_type) && $Deposit_Fees!=0){

						if($Deposit_Fees_type=='Percent'){
							$Deposit_Fee = ($amount1 * ($Deposit_Fees/100));
							$amount = $amount1 - $Deposit_Fee;
							$Deposit_Fees_Update = $Deposit_Fee;
						}
						else{

							// echo "Flat  ";
							// echo "<br>";
							$amount = $amount1 - $Deposit_Fees;
							$Deposit_Fees_Update = $Deposit_Fees;
						}

					}else{

						// echo "<br>";
						// echo "Empty Fee";
						// echo $amount1.' --- Amount ';
						$amount = $amount1;
						$Deposit_Fees_Update = 0;
					}
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
					// echo "<pre>";
					// print_r($row);
					// echo "<pre>";	

			
					$counts_tx = $this->db->query('select * from cpm_transactions where information="'.$row['blockhash'].'" and wallet_txid="'.$row['blockhash'].'" limit 1')->row();
					/*echo count($counts_tx);
					echo "<br>";*/
					// echo "<br>";
					// echo "Fees Type ".$Deposit_Fees_type;
					// echo "<br>";
					// echo "Fees  ".$Deposit_Fees;
					// echo "<br>";
					// echo  " Count TX ".count($counts_tx);
					// echo "<br>";
					// echo  " Amt Det ".$amount;
					// echo "<br>";
					// echo  " Confirm Det ".count($confirmations);
					// echo "<br>";
					// exit();
					// echo $counts_tx;
					// exit;
					// echo " Cate---> ".$category;
					// echo "<br>";
					// echo $row['blockhash'];
					// echo "<br>";
					// echo $counts_tx;
					// echo "<br>";
					// echo $confirmations;
					// echo "<br>";
					// exit(); 
					if($category == 'receive' && $confirmations > 0 && count($counts_tx) == 0 && $amount>0)
					{



						// echo "Receive";
						// echo "<br>";
	
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

							// if(trim($from_address)!= trim($admin_address))
							// {
								// echo "<pre>";
								// print_r($address_list); 
								// echo "<pre>";

								
								
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
										'fee'				=> $Deposit_Fees_Update,
										'information'		=> $blockhash, 
										'wallet_txid'       => $blockhash,
										'crypto_address'	=> $address,
										'status'     		=> "Completed",
										'datetime' 			=> $time_st,
										'user_status'		=> "Completed",
										'crypto_type'       => $crypto_type,
										'transaction_id'	=> rand(100000000,10000000000),
										'datetime' 		=> (empty($txid))?$time_st:time()
									);
									// echo "<pre>";
									// print_r($dep_data);
									// echo "<pre>";
									//  exit;
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

									$result = array('status'=>'success','message'=>'Update Deposit Successed');
									
									
									 
								
								}
							//}
						}
						// else
						// {
						// 	if(isset($user_id) && !empty($user_id)){
						// 			$balance = getBalance($user_id,$cur_ids,'crypto'); // get user bal
						// 			$finalbalance = $balance+$amount; // bal + dep amount
						// 			//echo "Final".$finalbalance;
						// 			$updatebalance = updateBalance($user_id,$cur_ids,$finalbalance,'crypto'); // Update balance

						// 			// Add to reserve amount
						// 			$reserve_amount = getcryptocurrencydetail($cur_ids);
						// 			$final_reserve_amount = (float)$amount + (float)$reserve_amount->reserve_Amount;
						// 			$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $cur_ids);

						// 			// insert the data for deposit details
						// 			$dep_data = array(
						// 				'user_id'    		=> $user_id,
						// 				'currency_id'   	=> $cur_ids,
						// 				'type'       		=> "Deposit",
						// 				'currency_type'		=> "crypto",
						// 				'description'		=> $coin_name." Payment",
						// 				'amount'     		=> $amount,
						// 				'transfer_amount'	=> $amount,
						// 				'information'		=> $blockhash,
						// 				'wallet_txid'       => $blockhash,
						// 				'crypto_address'	=> $address,
						// 				'status'     		=> "Completed",
						// 				'datetime' 			=> $time_st,
						// 				'user_status'		=> "Completed",
						// 				'crypto_type'       => $crypto_type,
						// 				'transaction_id'	=> rand(100000000,10000000000),
						// 				'datetime' 		=> (empty($txid))?$time_st:time()
						// 			);
						// 			// echo "DEP DATA2";
						// 			// echo $address; echo "<br/>";
						// 			// print_r($dep_data);
						// 			$ins_id = $this->common_model->insertTableData('transactions',$dep_data);

						// 			$prefix = get_prefix();
						// 			$userr = getUserDetails($user_id);
						// 			$usernames = $prefix.'username';
						// 			$username = $userr->$usernames;
						// 			$sitename = getSiteSettings('site_name');
						// 			// check to see if we are creating the user
						// 			$site_common      =   site_common();
						// 			$email_template = 'Deposit_Complete';
						// 			$special_vars	=	array(
						// 				'###SITENAME###'  =>  $sitename,
						// 				'###USERNAME###'    => $username,
						// 				'###AMOUNT###' 	  	=> $amount,
						// 				'###CURRENCY###'    => $cur_sym,
						// 				'###HASH###'        => $blockhash,
						// 				'###TIME###'        => date('Y-m-d H:i:s',$time_st),
						// 				'###TRANSID###' 	=> $txid,
						// 			);
									
						// 			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
						// 		}

						// }
						
						
					}
					

					if($crypto_type=='eth' || $crypto_type=='bsc' || $crypto_type=='tron'){
									
						//$this->move_to_admin_wallet($coin_name1,$crypto_type);
					}
					
				/*}*/
				}
				/*26-6-18*/
				
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





	public function transfer_to_admin_wallet($coinname)
	{
	    $coinname = str_replace("%20"," ",$coinname);
	    $currency_det =   $this->db->query("select * from cpm_currency where currency_name = '".$coinname."' ")->row(); // get currency detail
	    $currency_status = $currency_det->currency_symbol.'_status';
	    $address_list   =  $this->db->query("select * from cpm_crypto_address where ".$currency_status." = 1")->result(); // get user addresses
	    $fetch          =  $this->db->query("select * from cpm_admin_wallet where id='1'")->row(); // get admin wallet
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


  public function move_to_admin_wallet($coinname,$crypto_type='')
	{
		echo "MOVE To Admin Wallet Begins";
		echo "<br/>";
		echo $coinname."----".$crypto_type;
		echo "<br/>";
	    $coinname =  str_replace("%20"," ",$coinname);
        
	    $currency_det    =   $this->db->query("select * from cpm_currency where currency_name = '".$coinname."' limit 1")->row(); 



	    if($currency_det->move_admin==1 && $currency_det->coinbase_status!=1)
	    {
			//echo "inn";
	    $currency_status = $currency_det->currency_symbol.'_status';
	   //$address_list    =  $this->db->query("select * from tarmex_crypto_address where ".$currency_status." = '1' ")->result(); 
	   $address_list    =  $this->db->query("select * from cpm_transactions where type = 'Deposit' and status = 'Completed' and currency_id = ".$currency_det->id." and crypto_type = '".$crypto_type."' and amount > '".$currency_det->move_coin_limit."' and admin_move = 0 ")->result(); 

	   // echo $this->db->last_query();

	   // echo "<pre>";
	   // print_r($address_list);
	   // echo "<pre>";
	   // exit();



		echo "Total Transaction pending".count($address_list);
		echo "<br/>";
	    $fetch           =  $this->db->query("select * from cpm_admin_wallet where id='1' limit 1")->row(); 
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
					// else if($value->crypto_type == 'eth')
					// 	$curr_symbol = 'ETH';


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

	                 echo ' From  -->  '.$from;
	                 echo "<br>"; 
	                 echo $coin_type; 
	                echo "<br>"; 
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


	                 	$amount    = $this->local_model->wallet_balance($coinname,$from);
	                 }

	                 // echo " Amount --- > ".$amount;


	                $minamt    = $currency_det->min_withdraw_limit;
	                $from_address = trim($from); 
	                $to = trim($toaddress);
	        
	                if($from_address!='0') {
	                	/*echo "Address - ".$from_address;
	                	echo "Balance - ".$amount;*/

	                	// echo "Amount".$amount;
	                	// echo "<br>";
	                	// echo "MIN Amount".$min_deposit_limit;
	                	// exit(); 

	                if($amount>=$min_deposit_limit) 
	                {
	                	echo $amount."<br/>";

	     //            	echo "transfer";
						// echo "<br/>";
						// echo "CRYPTO TYPE".$crypto_type;
						// echo "<br/>";
						// echo "COIN TYPE".$coin_type;
						// echo "<br/>";
	                		

		                if($coin_type=="token")
		                {
							if($crypto_type=='eth')
							{


								// $GasLimit = 70000;
		      //                   $GasPrice = $this->check_ethereum_functions('eth_gasPrice','Ethereum');		                        
		      //                   $amount_send = $amount;
		      //                   $amount1 = $amount_send * $coin_decimal;
		      //                   echo "<br/>".$GasPrice."<br/>";
		      //                   $trans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
							}
							elseif($crypto_type=='bsc')
							{



								$GasLimit = 500000;

		                        //$GasPrice = $this->check_ethereum_functions('eth_gasPrice','BNB');

		                        $GasPrice = 6000000000;  
		                        			

		                        $amount_send = $amount;
								// echo $amount_send;
								// echo "<br/>";
								echo ' Coin Decimal '.$coin_decimal; 
								echo "<br/>";
		                        $tok_amount1 = $amount_send * $coin_decimal;

		                        $amt = sprintf('%.0f',$tok_amount1);
								
	                            echo "<br/>".$GasPrice."<br/>";
	                            echo "<br/>".$amt."<br/>";

 
	                           


		                        $trans_det = array('from'=>$from_address,'to'=>$to,'value'=>$amt,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);

		                        // echo "<pre>";print_r($trans_det);
		                        // exit();
							}
							else
							{
					            $amount1 = $amount * $coin_decimal;
					            $fee_limit = 2000000;

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


		                		$eth_balance = $this->local_model->wallet_balance("BNB",$from_address); 
								$transfer_currency = "BNB"; 
		                		$check_amount = "0.004";   
		                	}

		     //            	echo "<br>";
		     //            	echo "Balan -- ".$eth_balance;
		                	
		     //            	echo "<br>"; 
 						// 	echo ' From Addr --->  '.$from_address; 
		     //            	echo "<br>"; 
							// echo "Balance".$eth_balance;
							// echo "<br/>";
							// echo "Check Amount".$check_amount;
							// echo "<br>";
							// print_r($trans_det);
							// echo "<br>";
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
		                			
		                		echo "<br>";
		                		
		                		print_r($txn_count);

		                		echo "<br>";	


		                		if($txn_count==0)
		                		{ 
									echo $coinname;
		                			print_r($trans_det);
									echo $crypto_type;
									//exit;
		                			$send_money_res_token = $this->local_model->make_transfer($coinname,$trans_det,$crypto_type); // transfer to admin
		                			echo "<br>";
									echo "inini";
									echo "<br>";
									print_r($send_money_res_token);
									echo "<br>"; 
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
                                $GasLimit1 = 21000;
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
							
							// Coin deposit transfer from user wallet to admin wallet 
							$coin_transfer = '';
							if($crypto_type=='eth')
							{
							// $GasLimit = 70000;
	      //                   $Gas_calc = $this->check_ethereum_functions('eth_gasPrice','Ethereum');
	      //                   echo "<br/>".$Gas_calc."<br/>";
	      //                   $Gwei = $Gas_calc;
	      //                   $GasPrice = $Gwei;
	      //                   $Gas_res = $Gas_calc / 1000000000;
	      //                   $Gas_txn = $Gas_res / 1000000000;
	      //                   $txn_fee = $GasLimit * $Gas_txn;
	      //                   echo "Transaction Fee".$txn_fee."<br/>";
	      //                   $amount_send = ($amount - $txn_fee)-0.0005;
	      //                   echo "Amount Send ".$amount_send."<br/>";

	      //                   echo "Total Amount ".($txn_fee+$amount_send)."<br/>";
	      //                   $amount1 = ($amount_send * 1000000000000000000);

	      //                   echo sprintf("%.40f", $amount1)."<br/>";
	      //                   $coin_transfer = "Ethereum";
	      //                   $cointrans_det = array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);

	                       /* echo "<pre>";
	                        print_r($cointrans_det);*/
							}
							elseif($crypto_type=='bsc')
							{

								
							$GasLimit = 100000;
	                        $Gas_calc = $this->check_ethereum_functions('eth_gasPrice','BNB');

	                        // print_r($Gas_calc);
	                        // exit();

	                        //$Gas_calc = 30000000000;
			                echo "<br/>".$Gas_calc."<br/>";
	                        $Gwei = $Gas_calc;
	                        $GasPrice = $Gwei;
	                        $Gas_res = $Gas_calc / 1000000000;
	                        $Gas_txn = $Gas_res / 1000000000;
	                        $txn_fee = $GasLimit * $Gas_txn;
							echo "Transaction Fee".$txn_fee."<br/>";
	                        $amount_send = ($amount - $txn_fee);
							echo "Amount Send ".$amount_send."<br/>";
	                        $amount1 = ($amount_send * 1000000000000000000);
								                        
							echo sprintf("%.40f", $amount1)."<br/>";
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
								echo $coin_transfer;
								echo "<br/>";
								print_r($cointrans_det);
								//exit;
                            $send_money_res_coin = $this->local_model->make_transfer($coin_transfer,$cointrans_det); // transfer to admin

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
		echo $coin_name = 'Ethereum';
		echo '<br>';
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


	function test()
	{
	   $json_pair         = base_url().'BNBpairs.json';
	   $Jsons = file_get_contents($json_pair);
	   $decode = json_decode($Jsons);	
	   
	   // print_r($decode);

	   $i=1;
		foreach (array_unique($decode) as $json) {

		if($json!='') {	

		$checkcurrency = $this->common_model->getTableData('currency',array('currency_symbol'=>$json))->row();
		


		if(empty($checkcurrency)) {



			$data = array(
				"No" => $i,
				"currency" => $json
			);

			echo "<pre>";
			print_r($data);
			echo "<pre>";
			$i++;
		}
		else
		{
			// echo "No Records Found!";
		}


	}

		
		}				


	}	

	function testing(){
	   
	   $json_pair         = base_url().'USDTpairs.json';
	   $Jsons = file_get_contents($json_pair);
	   $decode = json_decode($Jsons);	
	   $i=1;
		
		foreach (array_unique($decode) as $json) {

		$sym = substr($json,0,3);	

		if($json!='') {	

		$checkcurrency = $this->common_model->getTableData('currency',array('currency_symbol'=>$sym))->row();
		


		if(empty($checkcurrency)) {

			

			$data = array(
				"No" => $i,
				"currency" => $sym
			);

			echo "<pre>";
			print_r($data);
			echo "<pre>";
			$i++;
		}


	}

		
		}
	}


	function withdraw_coin_user_confirm($id)
	{
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('error', 'Unable to confirm your withdraw request without login.Please Login');
			front_redirect('login', 'refresh'); 
		}
		// print_r($id);
		$id = decryptIt($id);  
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		// print_r($withdraw);exit();
		if($isValid > 0)
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
			elseif($withdraw->user_id != $user_id)
			{
				$this->session->set_flashdata('error','Your are not the owner of this withdraw request');
				front_redirect('wallet', 'refresh');
			}
			else {
				$updateData['user_status'] = 'Completed';
				$condition = array('trans_id' => $id,'type' => 'withdraw');
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
		$id = decryptIt($id);

		// print_r($id);
		// exit();

		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if($isValid > 0)
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
			elseif($withdraw->user_id != $user_id)
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
				$condition = array('trans_id' => $id,'type' => 'withdraw');
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







	function wallet()
	{	
		$data['site_common'] = site_common();	 
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
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
		$this->load->view('front/user/wallet', $data);
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
		$data['all_currency'] = $this->common_model->getTableData("currency",array("status"=>1))->result();
		if(count($data['all_currency']))
		{
		$tot_balance = 0;
		foreach($data['all_currency'] as $cur)
		{
		   $balance = getBalance($user_id,$cur->id);
		   $usd_balance = $balance * $cur->online_usdprice;

		   $data['tot_balance'] += $usd_balance;
		}
		}


		$data['trans_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id),'','','','','','',array('trans_id','DESC'))->result();

		$this->load->view('front/user/dashboard', $data);
	}

	function market()
	{
		$data['site_common'] = site_common();
		$data['pairs'] = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
		$this->load->view('front/user/market', $data);
	}

	function report()
{
	$data['site_common'] = site_common();
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
	$data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>"Deposit"),'','','','','','',array('trans_id','DESC'))->result();
	$data['withdraw_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>"Withdraw"),'','','','','','',array('trans_id','DESC'))->result();

	    $selectFields='CO.*,SUM(CO.Amount) as TotAmount,date_format(CO.datetime,"%d-%m-%Y %H:%i") as trade_time,sum(OT.filledAmount) as totalamount';
	    $names = array('filled','partially','cancelled');
	    $where=array('CO.userId'=>$user_id);
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
	        $result = 0;
	    }
	    if($result&&$result!=0)
	    {
	        foreach($result as $val)
	        {
	            if($val->status == 'partially')
	            {
	                $val->balance = $val->Amount - $val->totalamount;
	            } else {
	                $val->balance = '-';
	            }
	        }
	        $data['exchange_history']=$result;
	    }
	    else
	    {
	        $data['exchange_history']=[];
	    }
	    $data['login_history'] = $this->common_model->getTableData('user_activity',array('user_id'=>$user_id),'','','','','','',array('act_id','DESC'))->result();
	    // print_r($data['deposit_history']);exit;
	    $data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
	    $this->load->view('front/user/report', $data);
}

	 function update_adminaddress($coin_symbol)
    {
        $Fetch_coin_list = $this->common_model->getTableData('currency',array('currency_symbol'=>$coin_symbol,'status'=>'1'))->result();

        $whers_con = "id='1'";

        // $get_admin  =   $this->common_model->getrow("bluerico_admin", $whers_con);
        // print_r($get_admin); exit();

        $admin_id = "1";

        $enc_email = getAdminDetails($admin_id, 'email_id');

		$email = decryptIt($enc_email);


        $get_admin = $this->common_model->getrow("cpm_admin_wallet", $whers_con);
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
				$this->session->set_flashdata('error','Problem with your coin image');
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
		    if($this->input->post('coin_type') == 0)
		    {
            // $insertData['token_type'] = $this->input->post('token_type');
            $template = 'Token_request';
            } else{
            	$template = 'Coin_request';
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
			$email_template = $template;
			$special_vars = array(
			'###USERNAME###' => $username,
			'###COIN###' => $coin_name
			);
			//-----------------
			$this->email_model->sendMail($user_mail, '', '', $email_template, $special_vars);
			if ($insert) {

				$this->session->set_flashdata('success', 'Your add coin request successfully sent to our team');
				front_redirect('add_coin', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Error occur!! Please try again');
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


	function update_bank_details()
	{		 
		$this->load->library('session','form_validation');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', 'Please Login');
			redirect(base_url().'home');
		}
		if($_POST)
		{
			$this->form_validation->set_rules('bank_account_number', 'Bank Account number', 'required|xss_clean');
			if($this->form_validation->run())
			{

				$cur_id = $this->db->escape_str($this->input->post('currency'));

				$insertData['user_id'] = $user_id;
				$insertData['currency'] = $this->db->escape_str($this->input->post('currency'));
				$insertData['bank_account_name'] = $this->db->escape_str($this->input->post('bank_account_name'));
				$insertData['bank_account_number'] = $this->db->escape_str($this->input->post('bank_account_number'));
				$insertData['bank_swift'] = $this->db->escape_str($this->input->post('bank_swift'));
				$insertData['bank_name'] = $this->db->escape_str($this->input->post('bank_name'));
				$insertData['bank_address'] = $this->db->escape_str($this->input->post('bank_address'));
				$insertData['bank_city'] = $this->db->escape_str($this->input->post('bank_city'));
				$insertData['bank_country'] = $this->db->escape_str($this->input->post('bank_country'));
				$insertData['bank_postalcode'] = $this->db->escape_str($this->input->post('bank_postalcode'));
				$insertData['added_date'] = date("Y-m-d H:i:s");				
				$insertData['status'] = 'Pending';
				$insertData['user_status'] = '1';
				
				$insertData_clean = $this->security->xss_clean($insertData);

				$user_bank_det = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id,'currency'=>$cur_id))->row();

				// print_r($user_bank_det);
				// exit();

				if(isset($user_bank_det))
				{	
					// $insert=$this->common_model->insertTableData('user_bank_details', $insertData_clean);
					$insert= $this->common_model->updateTableData('user_bank_details', array('id' => $user_bank_det->id), $insertData_clean);

				}
				else
				{
					$insert=$this->common_model->insertTableData('user_bank_details', $insertData_clean);
				}


				
				if ($insert) {
					//$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));
					$this->session->set_flashdata('success', 'Bank details Updated Successfully');

					front_redirect('settings', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Something ther is a Problem .Please try again later');
					front_redirect('settings', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error','Some datas are missing');
				front_redirect('settings', 'refresh');
			}
		}		
		front_redirect('settings', 'refresh');
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
			$data['url'] = $ga->getQRCodeGoogleUrl('CryptoPool', $secret);
		}
		else
		{
			$ga = new Googleauthenticator();
			$data['secret'] = $ga->createSecret();
			$data['url'] = $ga->getQRCodeGoogleUrl('CryptoPool', $data['secret']);
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
					$this->session->set_flashdata('success', 'TFA Enabled successfully');
					front_redirect('settings', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error', 'Please Enter correct code to enable TFA');
					front_redirect('settings', 'refresh');
					
				}
			}
			else
			{
				if($code=='1')
				{
					$this->db->where('id',$user_id);
					$data1=array('secret'  => $secret_code,'randcode'  => "disable");
					$this->db->update('users',$data1);	
					$this->session->set_flashdata('success', 'TFA Disabled successfully');
					front_redirect('settings', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error', 'Please Enter correct code to disable TFA');
					/*echo $secret_code."<br/>";
					echo $code."Pila<br/>";
					echo $onecode;
					exit();*/
					front_redirect('settings', 'refresh');
				}
			}
		}

		front_redirect('settings', 'refresh');
	}

	function deposit($cur='')
	{
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		$kyc_status = getUserDetails($user_id,'verify_level2_status');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		
		else if($this->block() == 1)
		{ 
		front_redirect('block_ip');
		}
		// else if($kyc_status!='Completed')
		// {
		// 	$this->session->set_flashdata('error', 'Please Complete KYC'); 
		// 	front_redirect('kyc', 'refresh');
		// } 

		if($cur=='')
		{
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('id','ASC'))->row();
		}
		else
		{
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('currency_symbol'=>$cur),'','','','','','',array('id','ASC'))->row();
		}
		

		$data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['fiat_currency'] = $this->common_model->getTableData('currency',array('status'=>1,'type'=>'fiat'))->row();

		$data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details', array('currency'=>$data['fiat_currency']->id))->row();

		$data['user_bank'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'status'=>'1'))->row();
		$data['dig_currency'] = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1),'','','','','','',array('id','ASC'))->result();
		

		$cur_id = $data['sel_currency']->id;

		
		$data['all_currency'] = $this->common_model->getTableData('currency',array('status'=>1,'deposit_status' =>1),'','','','','','',array('id','ASC'))->result(); 

		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));

		$data['balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

		 $data['deposit_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Deposit'),'','','','','','',array('trans_id','DESC'))->result();
		
		$data['user_balance'] = getBalance($user_id,$cur_id);
		// echo $cur_id;
		// print_r($data['sel_currency']);
		// exit();
		 

		
	if($data['sel_currency']->ethers_status==1)
	{
		$Fetch_coin_list = $this->common_model->getTableData('ethers_wallet',array('type'=>'ethers','user_id'=>$user_id))->row();
		$coin_address = $Fetch_coin_list->address;
	}
	else
	{
		// $coin_address = getAddress($user_id,$cur_id);
	}


		$data['user_id'] = $user_id;
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
		
	
		$this->load->view('front/user/deposit', $data); 
	}









function referral(){
$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{
		$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
		redirect(base_url().'home');
		}
		$data['site_common'] = site_common();
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['referral_history'] = $this->common_model->getTableData('transaction_history',array('userId'=>$user_id,'type'=>'Referral'),'','','','','','',array('id','DESC'))->result(); 

		$total_referral= $this->common_model->customQuery("SELECT SUM(amount) AS amount FROM `cpm_transaction_history` WHERE userId = ".$user_id." and type = 'Referral' ")->result();

		$data['total_referral'] = $total_referral[0]->amount; 


		 

		$this->load->view('front/user/referral',$data);
} 


	
 

	function withdraw($cur=''){

		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		} 
		else if($this->block() == 1)
		{ 
			front_redirect('block_ip');
		}

		$kyc = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		if(($kyc->verify_level2_status!='Completed'))
		{
			$this->session->set_flashdata('error', "Your kyc not verified");
			redirect(base_url().'kyc');
		}


		$data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['site_common'] = site_common();	
		$data['currency'] = $this->common_model->getTableData('currency',array('status'=>1,'withdraw_status'=>1),'','','','','','',array('id','ASC'))->result();	 
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		if(isset($cur) && !empty($cur)){
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('currency_symbol'=>$cur),'','','','','','',array('id','ASC'))->row();

				if($data['sel_currency']->withdraw_status==0)
				{
					$this->session->set_flashdata('error', 'Withdraw Disabled Please Contact admin');
						front_redirect('wallet','refresh');	
				} 


			$data['selcsym'] = $cur;

			$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
			$data['fees'] = $data['sel_currency']->withdraw_fees;
		}
		else{
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('status' => 1),'','','','','','',array('id','ASC'))->row();
			$data['selcsym'] = $data['sel_currency']->currency_symbol;
			
			$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
			$data['fees'] = $data['sel_currency']->withdraw_fees;
		}

		$cur_id = $data['sel_currency']->id;
		$data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details')->row(); 
		$data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

		$data['balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

		$data['user_balance'] = getBalance($user_id,$cur_id);

		
		if(isset($_POST['withdrawcoin']))
	    {


			$this->form_validation->set_rules('ids', 'ids', 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
			$passinp = $this->db->escape_str($this->input->post('ids'));
			$myval = explode('_',$passinp);
			$id = $myval[0]; 
			$name = $myval[1];
			$bal = $myval[2];
			$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
		    $userbalance = getBalance($user_id,$id);
		    
			/*if ($this->form_validation->run()!= FALSE)
			{ echo 'dddd'; exit;*/
				$amount = $this->db->escape_str($this->input->post('amount'));
				
				$address = $this->db->escape_str($this->input->post('address'));
				$Payment_Method = 'crypto';
				$Currency_Type = 'crypto';
				$Bank_id = '';				
	 			
	 			$balance = getBalance($user_id,$id,'crypto');
				$currency = getcryptocurrencydetail($id);
				$w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Pending','currency_id'=>$id));
				$count        = $w_isValids->num_rows();
	            $withdraw_rec = $w_isValids->row();
                $final = 1;

				if(is_numeric($id)==1 && $userbalance==$bal)
				{	
					if($count>0)
					{							
						$this->session->set_flashdata('error', 'Sorry!!! Your previous ') . $currency->currency_symbol . $this->lang->line('withdrawal is waiting for admin approval. Please use other wallet or be patience');
						front_redirect('withdraw/'.$currency->currency_symbol, 'refresh');	
					}
					else
					{
						if($amount>$balance)
						{ 
							$this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
							front_redirect('withdraw/'.$currency->currency_symbol, 'refresh');
						}
						if($amount < $currency->min_withdraw_limit)
						{
							$this->session->set_flashdata('error','Amount you have entered is less than minimum withdrawl limit');
							front_redirect('withdraw/'.$currency->currency_symbol, 'refresh');
						}
						elseif($amount>$currency->max_withdraw_limit)
						{
							$this->session->set_flashdata('error', 'Amount you have entered is more than maximum withdrawl limit');
							front_redirect('withdraw/'.$currency->currency_symbol, 'refresh');	
						}
						elseif($final!=1)
						{
							$this->session->set_flashdata('error','Invalid address');
							front_redirect('withdraw/'.$currency->currency_symbol, 'refresh');
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
							$user_status = 'Pending';
							$insertData = array(
								'user_id'=>$user_id,
								'payment_method'=>$Payment_Method,
								'currency_id'=>$id,
								'amount'=>$amount,
								'fee'=>$fees,
								'bank_id'=>$Bank_id,
								'crypto_address'=>$address,
								'transfer_amount'=>$total,
								'datetime'=>date("Y-m-d h:i:s"),
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
								$link_ids = encryptIt($insert);
								$sitename = getSiteSettings('english_english_site_name');
								$site_common      =   site_common();		                    

								
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
					$this->session->set_flashdata('error', 'Incorrect Values!'); 
					front_redirect('withdraw/'.$cur, 'refresh');
				}	
	    }




		$data['user_id'] = $user_id;
		$data['selcur_id'] = $data['sel_currency']->id;
		$data['currency_balance'] = getBalance($user_id,$data['selcur_id']);
		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
		$data['withdraw_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();
		$this->load->view('front/user/withdraw', $data);

}  


// Crypto Fiat Withdraw


	function crypto_fiatwithdraw($cur=''){

		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}  
		$data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['site_common'] = site_common();	
		$data['currency'] = $this->common_model->getTableData('currency',array('status'=>1,'withdraw_status'=>1),'','','','','','',array('id','ASC'))->result();	 
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		$data['defaultfiat'] = $this->common_model->getTableData('currency',array('status' => 1,'type'=>'fiat'),'','','','','','',array('id','ASC'))->row();

		// print_r($data[])

		$data['defaultbank'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'currency'=>$data['defaultfiat']->id),'','','','','','',array('id','ASC'))->row();

		if(isset($cur) && !empty($cur)){
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('currency_symbol'=>$cur),'','','','','','',array('id','ASC'))->row();

				if($data['sel_currency']->withdraw_status==0)
				{
					$this->session->set_flashdata('error', 'Withdraw Disabled Please Contact admin');
						front_redirect('wallet','refresh');	
				} 


			$data['selcsym'] = $cur;

			$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
			$data['fees'] = $data['sel_currency']->withdraw_fees;
		}
		else{
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('status' => 1),'','','','','','',array('id','ASC'))->row();
			$data['selcsym'] = $data['sel_currency']->currency_symbol;


			
			$data['fees_type'] = $data['sel_currency']->withdraw_fees_type;
			$data['fees'] = $data['sel_currency']->withdraw_fees;
		}

		$cur_id = $data['sel_currency']->id;
		$data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details')->row(); 
		$data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

		$data['balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

		$data['user_balance'] = getBalance($user_id,$cur_id);

		
		if(isset($_POST['withdrawcoinfiat']))
	    {


			$this->form_validation->set_rules('ids', 'ids', 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
			$passinp = $this->db->escape_str($this->input->post('ids'));
			$myval = explode('_',$passinp);
			$id = $myval[0]; 
			$name = $myval[1];
			$bal = $myval[2];




			$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
		    $userbalance = getBalance($user_id,$id);
		    
			/*if ($this->form_validation->run()!= FALSE)
			{ echo 'dddd'; exit;*/
				$amount = $this->db->escape_str($this->input->post('amount'));
				
				// $address = $this->db->escape_str($this->input->post('address'));

				$fiat_amount = $this->db->escape_str($this->input->post('fiat_amount'));
				$fiat_currency = $this->db->escape_str($this->input->post('fiat_currency'));

				$Payment_Method = 'Crypto-Fiat';
				$Currency_Type = 'crypto';
				$Bank_id = '';				
	 			
	 			$balance = getBalance($user_id,$id,'crypto');
				$currency = getcryptocurrencydetail($id);
				$fiat_cur = getcryptocurrencydetail($fiat_currency);
				$w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Pending','currency_id'=>$id));

				$fiat_bank_det = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'currency'=>$fiat_currency))->row();

				$count        = $w_isValids->num_rows();
	            $withdraw_rec = $w_isValids->row();
                $final = 1;


    //             print_r($currency);
    //             echo "<br>";
    //             echo $amount;
				// exit();

				if(is_numeric($id)==1 && $userbalance==$bal)
				{	
					if($count>0)
					{							
						$this->session->set_flashdata('error', 'Sorry!!! Your previous ') . $currency->currency_symbol . $this->lang->line('withdrawal is waiting for admin approval. Please use other wallet or be patience');
						front_redirect('crypto_fiatwithdraw/'.$currency->currency_symbol, 'refresh');	
					}
					else
					{
						if($amount>$balance)
						{ 
							$this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
							front_redirect('crypto_fiatwithdraw/'.$currency->currency_symbol, 'refresh');
						}
						if($amount < $currency->min_withdraw_limit)
						{
							$this->session->set_flashdata('error','Amount you have entered is less than minimum withdrawl limit');
							front_redirect('crypto_fiatwithdraw/'.$currency->currency_symbol, 'refresh');
						}
						elseif($amount>$currency->max_withdraw_limit)
						{
							$this->session->set_flashdata('error', 'Amount you have entered is more than maximum withdrawl limit');
							front_redirect('crypto_fiatwithdraw/'.$currency->currency_symbol, 'refresh');	
						}
						elseif($fiat_bank_det->status!='Verified')
						{
							$this->session->set_flashdata('error', 'Your Bank Account Not Verified.');
							front_redirect('settings','refresh');	
						}
						else
						{
							// if($currency->crypto_type_other != '')
							// {
							// 	if($this->input->post('network_type') == 'tron')
							// 	{
							// 		$withdraw_fees_type = $currency->withdraw_trx_fees_type;
					  //       		$withdraw_fees = $currency->withdraw_trx_fees;
							// 	} else if($this->input->post('network_type') == 'bsc') {
							// 		$withdraw_fees_type = $currency->withdraw_bnb_fees_type;
					  //       		$withdraw_fees = $currency->withdraw_bnb_fees;
							// 	} else {
							// 		$withdraw_fees_type = $currency->withdraw_fees_type;
					  //       		$withdraw_fees = $currency->withdraw_fees;
							// 	}
							// } else {
							// 	$withdraw_fees_type = $currency->withdraw_fees_type;
					  //       	$withdraw_fees = $currency->withdraw_fees;
							// }

							$withdraw_fees_type = $currency->withdraw_fees_type;
					        $withdraw_fees = $currency->withdraw_fees;

					        if($withdraw_fees_type=='Percent') { $fees = (($amount*$withdraw_fees)/100); }
					        else { $fees = $withdraw_fees; }
							//$fees = apply_referral_fees_deduction($user_id,$fees);
					        $total = $amount-$fees;
							$user_status = 'Pending';
							$insertData = array(
								'user_id'=>$user_id,
								'payment_method'=>$Payment_Method,
								'currency_id'=>$id,
								'fiat_currency'=>$fiat_currency,
								'amount'=>$amount,
								'fee'=>$fees,
								'bank_id'=>$Bank_id,
								'crypto_address'=>'',
								'transfer_amount'=>$total,
								'fiat_amount' =>$fiat_amount,
								'datetime'=>date("Y-m-d h:i:s"),
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
								$link_ids = encryptIt($insert);
								$sitename = getSiteSettings('english_english_site_name');
								$site_common      =   site_common();		                    

								
								    $email_template = 'Withdraw_cryptofiat_User_Complete';
									$special_vars = array(
									'###SITENAME###' => $sitename,
									'###USERNAME###' => $username,
									'###AMOUNT###'   => (float)$amount,
									'###CURRENCY###' => $currency_name,
									'###FEES###' => $fees,
									'###FIATAMOUNT###' => $fiat_amount,
									'###FIAT###' => $fiat_cur->currency_symbol,
									'###CONFIRM_LINK###' => base_url().'withdraw_coin_user_confirm/'.$link_ids,
									'###CANCEL_LINK###' => base_url().'withdraw_coin_user_cancel/'.$link_ids
									);
								
							    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);
								$this->session->set_flashdata('success','Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!');
								front_redirect('crypto_fiatwithdraw', 'refresh');
							} 
							else 
							{
								$this->session->set_flashdata('error','Unable to submit your withdraw request. Please try again');
								front_redirect('crypto_fiatwithdraw/'.$cur, 'refresh');
							}
						}
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Incorrect Values!'); 
					front_redirect('crypto_fiatwithdraw/'.$cur, 'refresh');
				}	
	    }




		$data['user_id'] = $user_id;
		$data['selcur_id'] = $data['sel_currency']->id;
		$data['currency_balance'] = getBalance($user_id,$data['selcur_id']);
		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
		$data['withdraw_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();
		$this->load->view('front/user/crypto_fiatwithdraw', $data);

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
			$this->session->set_flashdata('success','Ticket Closed');
			front_redirect('support', 'refresh');
		}
		else{
			$this->session->set_flashdata('error','Something Went Wrong. Please try again.');
			front_redirect('support_reply/'.$code, 'refresh');
		}

	}

function test_signup()
	{		
		
		$data['site_common'] = site_common();
		
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'signup'))->row();
		$data['signup_content'] = $this->common_model->getTableData('static_content',array('slug'=>'signup_content'))->row();
		$newuser_reg_status = getSiteSettings('newuser_reg_status');
		$user_id=$this->session->userdata('user_id');


		$backup_users = $this->common_model->getTableData('backup_users',array('user_status'=>0),'','','','','','100',array('id','ASC'))->result(); 
		
		// When Post		
		if(!empty($backup_users))
		{ 

			foreach ($backup_users as $users) {
				# code...
			
				echo "In The Loop ";
				echo "<br>";

				$pwd_chars = 'TBPT82OVAXQPFDabcdefghijklmnopytqjpstuvwxyz';
	            $password=substr(str_shuffle($pwd_chars), 0, 10);

	            $country = 42;


				$email = $this->db->escape_str(lcfirst($users->email));
				$password = $this->db->escape_str($password);
				$uname = $this->db->escape_str($users->name);
				$country = $this->db->escape_str($country);
				
				$check=checkSplitEmail($email);
				$prefix=get_prefix();
				
				if($check)
				{
					echo "<br>";
					echo "Entered Email Address Already Exists ";
					echo "<br>";
					$this->session->set_flashdata('error', $this->lang->line('Entered Email Address Already Exists'));
					// front_redirect('signup', 'refresh');
				}
				else
				{				

					$permitted_chars = '8514890089abcdefghijklmnopytqjpstuvwxyz';
	                $refferalid=substr(str_shuffle($permitted_chars), 0, 10);

					$Exp = explode('@', $email);
					$User_name = $Exp[0];

					$activation_code = time().rand(); 
					$str=splitEmail($email);
					$ip_address = get_client_ip();
					
                    

					$user_data = array(
					'usertype' => '1',
					$prefix.'email'    => $str[1],
					'country' => $country,
					$prefix.'username'	=> $uname,
					$prefix.'password' => encryptIt($password),
					'activation_code'  => $activation_code,
					'verified'         =>'0',
					'register_from'    =>'Website',
					'ip_address'       =>$ip_address,
					'browser_name'     =>getBrowser(),
					'verification_level' =>'1',
					'created_on' =>gmdate(time())
					// 'parent_referralid'=>$ref,
					// 'referralid' => $refferalid
					);
					 
				$user_data_clean = $this->security->xss_clean($user_data);
				$id=$this->common_model->insertTableData('users', $user_data_clean);
				if($id) {
					

					$userupdate=$this->common_model->updateTableData('backup_users',array('id'=>$users->id),array('user_status'=>1));


					$usertype=$prefix.'type';
					$this->common_model->insertTableData('history', array('user_id'=>$id, $usertype=>encryptIt($str[0])));
					// $this->common_model->last_activity('Registration',$id);
					$this->common_model->last_activity('Registration', $id, "", $ip_address);
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
					
					echo " User Name ".$uname.' Id ->  '.$id;
					echo "<br>";


					// check to see if we are creating the user
					// $email_template = 'Registration';
					// $site_common      =   site_common();
					// $special_vars = array(
					// '###EMAIL###' => $this->input->post('register_email'), 
					// '###LINK###' => front_url().'verify_user/'.$activation_code
					// );
					
					// $this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					$this->session->set_flashdata('success','Thank you for Signing up. Please check your e-mail and click on the verification link.');



					

					}
					

					
					// front_redirect('login', 'refresh');
				}

			}	
				
		}

	}






}