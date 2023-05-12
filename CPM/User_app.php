<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: PUT, GET, POST, FILES");
use Twilio\Rest\Client;

class User_app extends CI_Controller {
	public function __construct()
	{	
		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation'));
		$this->load->library('session');
		$this->load->helper(array('url', 'language'));
		$lang_id = $this->session->userdata('site_lang');
		$this->site_api = new Tradelib();

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
	public function login_check_app()
    {
    	// $user_id = $this->input->post('user_id');


    	
		    // number_format($tot_balance)

        $ip_address = get_client_ip();
        $array = array('status' => '0', 'msg' => '');
        $this->form_validation->set_rules('login_detail', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('login_password', 'Password', 'trim|required|xss_clean');
        // When Post
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
                        $array['status'] = '1';
                        if ($check->randcode == 'enable' && $check->secret != '') { 
                            $array['tfa_status'] = '1';
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
                                 $all_currency = $this->common_model->getTableData("currency",array("status"=>1))->result();
								if(count($all_currency))
								{
								// $tot_balance = 0;/
								foreach($all_currency as $cur)
								{
								   $balance = getBalance($check->id,$cur->id);
								   $usd_balance = $balance * $cur->online_usdprice;

								 $tot_balance   += $usd_balance;
								}
								}

								$bal = number_format($tot_balance,2);

								$array['user_detail'] = array(

                            	'kyc_status' =>$check->kyc_status,
                            	'user_name' => $check->cpm_username,
                            	'user_email' => getUserEmail($check->id),
                            	'phone' => $check->cpm_phone,
                            	'user_id' =>$check->id,
                            	'total_balance' =>$bal,
                            	'photo'=>$check->profile_picture

                            );
                                if ($check->verify_level2_status == 'Completed') {
                                    // $array['login_url'] = 'dashboard';
                                }
                                $array['tfa_status'] = '0';
                            } else {
                                $array['msg'] = 'Enter Valid TFA Code';
                            }
                        }  else { 

                            $session_data = array(
                                'user_id' => $check->id,
                            );
                            $this->session->set_userdata($session_data);
                            $this->common_model->last_activity('Login', $check->id, "", $ip_address);
                            // $array['tfa_status'] = '0';
                            //if($check->verify_level2_status=='Completed')
                            //{
                            $this->session->set_flashdata('success', 'Welcome back . Logged in Successfully');

                            
                            $all_currency = $this->common_model->getTableData("currency",array("status"=>1))->result();
								if(count($all_currency))
								{
								// $tot_balance = 0;/
								foreach($all_currency as $cur)
								{
								   $balance = getBalance($check->id,$cur->id);
								   $usd_balance = $balance * $cur->online_usdprice;

								 $tot_balance   += $usd_balance;
								}
								}

								$bal = number_format($tot_balance,2);

								$array['user_detail'] = array(

                            	'kyc_status' =>$check->kyc_status,
                            	'user_name' => $check->cpm_username,
                            	'user_email' => getUserEmail($check->id),
                            	'phone' => $check->cpm_phone,
                            	'user_id' =>$check->id,
                            	'total_balance' =>$bal,
                            	'photo'=>$check->profile_picture

                            );
     						
                            $array['msg'] = 'Welcome back . Logged in Successfully';
                            // $array['login_url'] = 'dashboard';
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
	function forgot_check_app()
	{

		// print($_POST);

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

						$array['status']='1';
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
	function signup_app()
	{		
			$data=array();
		
		// When Post		
		if(!empty($_POST))
		{ 
         

			// print_r($_POST);

		
				// echo "hii";
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
					$data['status'] = '0';
					$data['msg'] = 'Entered Email Address Already Exists';
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
					$data['status'] = '1';
					$data['msg'] = 'Thank you for Signing up. Please check your e-mail and click on the verification link.';
				}
			
		}else{
			$data['status'] = '0';
			$data['msg'] = 'Please try again2';
		}
		echo json_encode($data);

	
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
		// $this->load->library('session','form_validation');
		$data=array();

      $user_id=$this->input->post('user_id');
				if($user_id=="")
		{
		$data['msg']= ('Please Login');
		$data['status']='0';
		}
		if($_POST)
		{
		$this->form_validation->set_rules('firstname', 'firstname', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastname', 'lastname', 'trim|required|xss_clean');
        $this->form_validation->set_rules('address', 'address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'city', 'trim|required|xss_clean');
	   // $this->form_validation->set_rules('state', 'state', 'trim|required|xss_clean');
	   $this->form_validation->set_rules('postal_code', 'postal_code', 'trim|required|xss_clean');
	   $this->form_validation->set_rules('phone', 'phone', 'trim|required|xss_clean');
	   $this->form_validation->set_rules('register_country', 'register_country', 'trim|required|xss_clean');


			if($this->form_validation->run())
			{
				$insertData['cpm_fname'] = $this->db->escape_str($this->input->post('firstname'));
				$insertData['cpm_lname'] = $this->db->escape_str($this->input->post('lastname'));
				$insertData['street_address'] = $this->db->escape_str($this->input->post('address'));
				$insertData['city'] = $this->db->escape_str($this->input->post('city'));
				// $insertData['state'] = $this->db->escape_str($this->input->post('state'));
			$insertData['postal_code'] = $this->db->escape_str($this->input->post('postal_code'));
			// $paypal_email = $this->input->post('paypal_email');
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
					
						$uploadimage1=cdn_file_upload($_FILES["profile_photo"],'uploads/user/'.$user_id,$this->input->post('profile_photos'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							$data['msg']= ('error Problem with profile picture');
							$data['status']='0';
						} 
						$insertData['profile_picture']=$imagepro;
								
					
				}
				
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
				if ($insert) {
					$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));

					$data['status']='1';
					$data['msg']= ('success Profile details Updated Successfully');
                 
				} else {
					$data['msg']= ('error Something ther is a Problem .Please try again later');
					$data['status']='0';
				}
			}
			else
			{
				// $data['status'] = '0';
				$data['msg'] = validation_errors();
				// $this->session->set_flashdata('error','Some datas are missing');
				// front_redirect('profile', 'refresh');
			}
		}		
		echo json_encode($data);
	}


		function userdetails()
	{

		$data = array();
		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{
		$data['msg']='please login';
		$data['status']='0';
		}

		$users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();


		// print_r($users);

		$data['detail'] = array(
			'firstname'=>$users->cpm_username,
			'lastname'=>$users->cpm_lname,
			'user_email' => getUserEmail($user_id),
			'country' => getCountryName($users->country),
			'code'=> $users->country,
			'address' => $users->street_address,
			'phone' => $users->cpm_phone,
			'city'=>$users->city,
			'postal_code'=>$users->postal_code,
			'photo'=>$users->profile_picture

		);  

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



	function kyc_address()
	{
		$data=array();
		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			$data['status']='0';
			$data['msg']='Please Login';
		}
		else
		{
			$address_image = $_FILES['photo_id_1']['name']; 
			if($address_image!="") {
				

			$Img_Size = $_FILES['photo_id_1']['size'];
				if($Img_Size>2000000){
				$data['status'] = '0';
				$data['msg'] =  'File Size Should be less than 2 MB';
				
			}
					
			$uploadimage=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id);
			if($uploadimage)
			{
				$address=$uploadimage['secure_url'];

					$insertData = array();
					$insertData['photo_id_1'] = $address;					
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_1_status'] = 1;	                
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='') {
						$data['status'] = '1';
						$data['msg']='Your details have been sent to our team for verification';
						echo json_encode($data); 
						exit();

					} 
	                
					else {
						$data['status'] = '0';
						$data['msg']='Unable to send your details to our team for verification. Please try again later!';
					} 




			}
			else
			{
				// $errorMsg = current( (Array)$uploadimage );
				$data['status'] = '0';
				$data['msg'] =  'Cloud Error'; 
				// $address= $errorMsg;  
			}
			
			}
			else
			{
				$data['status'] = '0';
				$data['msg'] =  'Image Empty!! Please try again';

			}
		}
		echo json_encode($data);  


	}


// Identity 


	function kyc_identity()
	{
		$data=array();
		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			$data['status']='0';
			$data['msg']='Please Login';
		}
		else
		{
			$address_image = $_FILES['photo_id_2']['name']; 
			if($address_image!="") {
				

			$Img_Size = $_FILES['photo_id_2']['size'];
				if($Img_Size>2000000){
				$data['status'] = '0';
				$data['msg'] =  'File Size Should be less than 2 MB';
				
			}
					
			$uploadimage=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id);
			if($uploadimage)
			{
				    $identity=$uploadimage['secure_url'];
					$insertData = array();
					$insertData['photo_id_2'] = $identity;					
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_2_status'] = 1;	                
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='' && $_FILES["photo_id_2"]['name'] !='') {
						$data['status'] = '1';
						$data['msg']='Your details have been sent to our team for verification';
					} 
	                
					else {
						$data['status'] = '0';
						$data['msg']='Unable to send your details to our team for verification. Please try again later!';
					} 

			}
			else
			{
				$errorMsg = current( (Array)$uploadimage );
				$data['status'] = '0';
				$data['msg'] =  'Cloud Error';
			}
					
		}
			else
			{
				$data['status'] = '0';
				$data['msg'] =  'Image Empty!! Please try again';

			}


			
		}
		echo json_encode($data);  


	} 


// Selfie


	function kyc_selfie()
	{ 
		$data=array();
		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			$data['status']='0';
			$data['msg']='Please Login';
		}
		else
		{
			$address_image = $_FILES['photo_id_3']['name']; 
			if($address_image!="") {
				

			$Img_Size = $_FILES['photo_id_3']['size'];
				if($Img_Size>2000000){
				$data['status'] = '0';
				$data['msg'] =  'File Size Should be less than 2 MB';
				
			}
					
			$uploadimage=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id);
			if($uploadimage)
			{
				$selfie=$uploadimage['secure_url'];
					$insertData = array();
					$insertData['photo_id_3'] = $selfie;					
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_3_status'] = 1;	                
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='' && $_FILES["photo_id_3"]['name'] !='') {
						$data['status'] = '1';
						$data['msg']='Your details have been sent to our team for verification';
					} 
	                
					else {
						$data['status'] = '0';
						$data['msg']='Unable to send your details to our team for verification. Please try again later!';
					} 

			}
			else
			{
				// $errorMsg = current( (Array)$uploadimage );
				$data['status'] = '0';
				$data['msg'] =  'Cloud Error';
			}
			
		}
			else
			{
				$data['status'] = '0';
				$data['msg'] =  'Image Empty!! Please try again';

			}
		}
		echo json_encode($data);  
	} 




	function kyc_verification_app()
	{
		$data=array();

		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			$data['status']='0';
			$data['msg']='Please Login';
		}

		$photo_1_status = getUserDetails($user_id,'photo_1_status');
		if($_FILES['photo_id_1']['name'] && $photo_1_status!=1){				
		$prefix=get_prefix();

			
					// Address
					$addressimage = $_FILES['photo_id_1']['name'];
						
						if($addressimage!=""){
						$Img_Size = $_FILES['photo_id_1']['size'];
						if($Img_Size>2000000){
							$this->session->set_flashdata('error','File Size Should be less than 2 MB');
						}
						$uploadimage=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_1')));
						if(is_array($uploadimage))
						{
							$addressimage=$uploadimage['secure_url'];
						}
						else
						{
							$errorMsg = current( (Array)$uploadimage );
							$data['status'] = '0';
							$data['msg'] ='Problem with your scan of photo id';
						}
					} 
					elseif($this->input->post('photo_id_1')=='')
					{
						$addressimage = $this->db->escape_str($this->input->post('photo_id_1'));
					}
					else 
					{ 
						$addressimage='';
					}
					$insertData = array();
					$insertData['photo_id_1'] = $addressimage;					
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_1_status'] = 1;	                
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='' && $_FILES["photo_id_1"]['name'] !='') {
						$data['status'] = '1';
						$data['msg']='Your details have been sent to our team for verification';
					} 
	                elseif($insert !='' && $_FILES["photo_id_1"]['name'] =='') {
	                	$data['status'] = '1';
						$data['msg']='Your Address proof cancelled successfully';
					}
					else {
						$data['status'] = '0';
						$data['msg']='Unable to send your details to our team for verification. Please try again later!';
					}
				
				// Identity 

		}

		

		$photo_2status = getUserDetails($user_id,'photo_2_status');
		$ver_img = $_FILES['photo_id_2']['name'];
		if($photo_2status!=1 && $ver_img!='') {
 
			

					
					if($ver_img!=""){		

						$Img_Size = $_FILES['photo_id_2']['size'];
						if($Img_Size>2000000){
							$this->session->set_flashdata('error','File Size Should be less than 2 MB');
						}

						$uploadimage=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_2')));
						if(is_array($uploadimage))
						{
							$ver_img=$uploadimage['secure_url'];
						}
						else
						{
							$errorMsg = current( (Array)$uploadimage );
							$data['status'] = '0';
							$data['msg'] ='Problem with your scan of photo id';
						}
					} 
					elseif($this->input->post('photo_id_2')=='')
					{
						$ver_img = $this->db->escape_str($this->input->post('photo_id_2'));
					}
					else 
					{ 
						$ver_img='';
					}
					$insertData = array();
					$insertData['photo_id_2'] = $ver_img;
					$insertData['verify_level2_date'] = gmdate(time());
					$insertData['verify_level2_status'] = 'Pending';
					$insertData['photo_2_status'] = 1;
					$condition = array('id' => $user_id);
					$insertData_clean = $this->security->xss_clean($insertData);
					$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
					if($insert !='' && $_FILES["photo_id_2"]['name'] !='') {
						$data['status'] = '1';
						$data['msg']='Your details have been sent to our team for verification';
					} 
	                elseif($insert !='' && $_FILES["photo_id_2"]['name'] =='') {
	                	$data['status'] = '1';
						$data['msg']= 'Your ID proof cancelled successfully';
					}
					else {
						$data['status'] = '0';
						$data['msg']='Unable to send your details to our team for verification. Please try again later!';
					}
			

			}	
			

						$selfieimage = $_FILES['photo_id_3']['name'];
						if($selfieimage!=""){
						$Img_Size = $_FILES['photo_id_3']['size'];
						if($Img_Size>3000000){
							$data['status']='1';
							$data['msg']='File Size Should be less than 3 MB';
						}

						$uploadimage=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
						if($uploadimage)
						{
							$self_image=$uploadimage['secure_url'];
							$insertData['photo_id_3'] = $self_image;
							$insertData['verify_level2_date'] = gmdate(time());
							$insertData['verify_level2_status'] = 'Pending';
							$insertData['photo_3_status'] = 1;
							$condition = array('id' => $user_id);
							$insertData_clean = $this->security->xss_clean($insertData);
							$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
							if($insert !='' && $_FILES["photo_id_3"]['name'] !='') {
								$data['status']='1';
								$data['msg']='Your details have been sent to our team for verification';
							} 
			                elseif($insert !='' && $_FILES["photo_id_3"]['name'] =='') {
			                	$data['status']='1';
								$data['msg']='Your Photo cancelled successfully';
							}
							else {
								$data['status']='0';
								$data['msg']='Unable to send your details to our team for verification. Please try again later!';
							}



						} 
						
					}
					
		
		echo json_encode($data);



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


	function changepassword()
	{

     $user_id=$this->input->post('user_id');
		

		if(!empty($user_id) && $user_id >0 )
		{

		if(isset($_POST['oldpass']) && ($_POST['newpass']))
		{
			$prefix = get_prefix();
			$oldpassword = encryptIt($this->input->post("oldpass"));
			$newpassword = encryptIt($this->input->post("newpass"));
			$confirmpassword = encryptIt($this->input->post("confirmpass"));

			$users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

			
			
			// Check old pass is correct/not
			$password = $prefix.'password';
			if($oldpassword == $users->$password)
			{

				// print_r($users->password);exit;
				//check new pass is equal to confirm pass
				if($newpassword==$confirmpassword)
				{
					$this->db->where('id',$user_id);
					$data=array($prefix.'password'  => $newpassword);
					$this->db->update('users',$data);
					$array['status'] = '1';
					$array['msg'] = 'password update successfully';
				}
				else
				{
					$array['status'] = '0';
					$array['msg'] = 'Confirm password must be same as new password';
					// front_redirect('settings', 'refresh');
				}
			}
			else
			{
				$array['status'] = '0';
				 $array['msg'] = 'Your old password is wrong';
				// front_redirect('settings', 'refresh');
			}			
		}
		else {

			$array['status'] = '0';
			$array['msg'] = 'Some datas are missing';
		}
	}
	else
	{
		$array['status'] = '0';
			$array['msg'] = ' You are Not Login';
	}

			echo json_encode($array);

	}






	function support_app()
	{
		 $data = array();

		$user_id=$this->input->post('user_id');
		// print_r($user_id);
		if(!empty($user_id) && $user_id >0)
		{	
			
            
		
		// $data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'support'))->row();
		if(isset($_POST['message']))
		{
			
			$image = $_FILES['image']['name']; 
			if($image!="") {
				

					$Img_Size = $_FILES['image']['size'];
						if($Img_Size>3000000){
							$data['status'] = '0';
							$data['msg'] =  'File Size Should be less than 3 MB';
							
						}
					
					$uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$user_id);
					if($uploadimage1)
					{
						$image=$uploadimage1['secure_url'];

					}
					else
					{

						$data['status'] = '0';
						$data['msg'] =  'Error occur!! Please try again';

						
					}
					$image=$image;
				
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

				$data['status'] = '1';
				$data['msg'] =  'Your message successfully sent to our team';
				// echo json_encode($data);

				
			} else {
				  $data['status'] = '0';
				  $data['msg'] =  'Error occur!! Please try again';
				
		} 

			}
			 $users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

			 // $data = array(
			 // 	'user_id'=>$users->id,
			 // 	'username'=>$users->cpm_username,
			 // 	'email'=> getUserEmail($users->id)

			 // );
		// $data['action'] = front_url() . 'support';

		// $data['category'] = $this->common_model->getTableData('support_category', array('status' => '1'))->result();
		// $data['support'] = $this->common_model->getTableData('support', array('user_id' => $user_id, 'parent_id'=>0))->result();

		    }
		    else
		      {
	   $data['status'] = '0';
         $data['msg'] = "You are not Logged in";

    }
   

		echo json_encode($data);

	}


	function support_list()
	{
		$data = array();
		$user_id = $this->input->post('user_id');

		if(!empty($user_id) && $user_id >0)
		{

			$data['support'] = $this->common_model->getTableData('support', array('user_id' => $user_id, 'parent_id'=>0),'ticket_id,subject')->result();

			$data['status'] = '1';
			$data['msg'] = 'success';


		}else
		{

			$data['status'] = '0';
            $data['msg'] = "You are not Logged in";
		}

		echo json_encode($data);

	}


	function support_reply()
	{
		$data = array();
		$user_id=$this->input->post('user_id');
		if(!empty($user_id) && $user_id >0)
		{	
			
		
		// $data['site_common'] = site_common();
		// $data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'support'))->row();
		// $data['prefix'] = get_prefix();
		$code = $this->input->post('ticket_id');
		$support = $this->common_model->getTableData('support', array('user_id' => $user_id, 'ticket_id'=>$code))->row();
		$id = $support->id;
		//$data['support_reply'] = $this->common_model->getTableData('support', array('parent_id'=>$data['support']->id,'id'=>$id))->result();
		// $data['support_reply'] = $this->db->query("SELECT * FROM `cpm_support` WHERE `parent_id` = '".$id."'")->result();
		if($_POST)
		{
			$image = $_FILES['image']['name'];
			if($image!="") {
							
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
						$data['status'] = '0';
						$data['msg'] = 'Please upload proper image format';
						
					}
					$image=$image;
				
			} 
			 else 
			 { 
				$image = "";
			 }
			$insertsData['status'] = '1';
			$update = $this->common_model->updateTableData('support',array('ticket_id'=>$code),$insertsData);
			if($update){
				$insertData['parent_id'] = $support->id;
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
							'###LINK###' 			=> admin_url().'support/reply/'.$support->id
					);
					
					$special_vars_user 		= array(
							'###SITELINK###' 		=> front_url(),
							'###SITENAME###' 		=> $site_common['site_settings']->site_name,
							'###USERNAME###' 		=> $username,
							'###MESSAGE###'  		=> "<span style='color: #500050;'>".$message . "</span><br>"
					);

					$this->email_model->sendMail($adminmail, '', '', $email_template, $special_vars);
					$this->email_model->sendMail($usermail, '', '', $email_template_user, $special_vars_user);
    // $data['reply'] = $this->common_model->getTableData('support',array('parent_id'=>$id))->result();
					$data['status'] = '1';
					$data['msg'] =  'Your message successfully sent to our team';
					// echo json_encode($data);
				} else {
					$data['status'] = '0';
					$data['msg'] =  'Error occur!! Please try again';
				
				}
			}
			else
			{
				$data['status'] = '0';
				$data['msg'] =  'Error occur!! Please try again';
				
			}

			// $data['code'] = $code;
		// $data['user_detail'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
        // $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		}
	}
	else 
	{
		 $data['status'] = '0';
             $data['msg'] = "You are not Logged in";
	}
		
		
		echo json_encode($data);
	}

	function support_reply_list()
	{
		$data = array();
		$user_id = $this->input->post('user_id');

		if(!empty($user_id) && $user_id >0)
		{
			$code = $this->input->post('ticket_id');
		$support = $this->common_model->getTableData('support', array('user_id' => $user_id, 'ticket_id'=>$code))->row();
		$id = $support->id;

    $data['reply_list'] = $this->common_model->getTableData('support',array('parent_id'=>$id))->result();

		}
		else{
			 $data['status'] = '0';
             $data['msg'] = "You are not Logged in";
		}
		echo json_encode($data);
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


		foreach($Fetch_coin_list as $coin_address)
		{
    		$userdetails = $this->common_model->getTableData('crypto_address',array($coin_address->currency_symbol.'_status'=>'0'),'','','','','','',array('id','DESC'))->result();

    		
    		// exit();

	    	foreach($userdetails as $user_details) 
	    	{
	    		

	    		$User_Address = getAddress($user_details->user_id,$coin_address->id);

	    			// echo "<pre>";
			    	// print_r($User_Address);
			    	// echo "<pre>";


	    		if(empty($User_Address) || $User_Address==0)
	    		{



					$parameter = '';




	                if($coin_address->coin_type=="coin")
	                {

	                	if($coin_address->currency_symbol!='')
						{   
							echo $coin_address->currency_symbol;
							echo "<br>";
							$coinpayments = coinpayments_api_call('get_callback_address',$coin_address->currency_symbol);

							// echo "<pre>";
							// print_r($coinpayments);
							// echo "<pre>";
							
							$Get_First_address = $coinpayments['result']['address'];

							if($Get_First_address!='' || $Get_First_address!=0) {

							
							echo " Coin -->  ".$coin_address->currency_symbol.' - Address - '.$Get_First_address;
							echo "<br>";
							updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);

							}


						}
						
						 
		            }

		            else
		            {
		            	

		            	if($coin_address->currency_symbol=='CPM'){

		            		// $eth_address = coinbase('createaddress','USDT'); 
		            		// updateAddress($user_details->user_id,$coin_address->id,$eth_address); 

						} 
						   
					
						 // updateAddress($user_details->user_id,$coin_address->id,$eth_address);
		            
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
	public function update_crypto_deposits($coin) // cronjob for deposit
	{ 


		// Modified this method to accomodate dynamic USDT deposits(erc20,trc20 and beb20) for single token
		// modified in get_user_with_dep_det method with crypto_type_other field

		//$currencies = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','')->row();
		$currencies   =  $this->db->query("select * from cpm_currency where currency_symbol='$coin' AND status = 1")->result(); // get user addresses
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
						// echo "crypto type other<br/>";
						// print_r($crypto_type_arr);
						// echo "<br/>";
						// echo "In That, checking".$val;
						// echo "<br/>";
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




		$curr_id = $curr->id;
		$coin_name =  $curr->currency_name;
		$curr_symbol = $curr->currency_symbol;
		$coin_type = $curr->coin_type;
		
		$Deposit_Fees_type = $curr->deposit_fees_type;
		$Deposit_Fees = $curr->deposit_fees;
		$Deposit_Fees_Update = 0;
		$coin_name1 =  $this->common_model->getTableData('currency',array('deposit_currency'=>$coin_name),'','','','','',1)->row('currency_name');


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
			
			switch ($coin_name) 
			{
				case 'Bitcoin':
					$transactions   = coinbase_deposit('getAccountTransfers','BTC',$user_trans_res);
					break;

				case 'BNB':
					$transactions 	 = $this->local_model->get_transactions('BNB',$user_trans_res);
					break;

				case 'Tron':
					$transactions 	 = $this->local_model->get_transactions('Tron',$user_trans_res);
					break;

				case 'Ethereum':
					$transactions   = coinbase_deposit('getAccountTransfers','ETH',$user_trans_res);
					break;				

				case 'Litecoin':
					$transactions   = coinbase_deposit('getAccountTransfers','LTC',$user_trans_res);
					break;
				
				default:
					show_error('No directory access');
					break;
			}
		}
		else
		{ 
			// Token Logic   
			if($coin_name=='USDT')
			{
				$transactions   = coinbase_deposit('getAccountTransfers','USDT',$user_trans_res);
			}
			else if($coin_name=='SHIB')
			{
				$transactions   = coinbase_deposit('getAccountTransfers','SHIB',$user_trans_res);
			}
			else
			{
				$transactions 	 = $this->local_model->get_transactions($coin_name1,$user_trans_res,$crypto_type);
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
				// echo "<pre>";
				// print_r($tot_transactions);
				
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
							$amount = $amount1 - $Deposit_Fees;
							$Deposit_Fees_Update = $Deposit_Fees;
						}

					}else{
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
					
			
					$counts_tx = $this->db->query('select * from cpm_transactions where information="'.$row['blockhash'].'" and wallet_txid="'.$row['blockhash'].'" limit 1')->row();
					/*echo count($counts_tx);
					echo "<br>";*/

					// echo $counts_tx;
					//exit;
					
					// echo $row['blockhash'];
					// echo "<br>";
					// echo $counts_tx;
					// echo "<br>";
					// exit(); 
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
										'wallet_txid'       => $blockhash,
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
										'wallet_txid'       => $blockhash,
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


	function testing()
	{
		

	}	

	function test_email(){
		
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



	function market_app()
	{

		$data = array();

		$user_id = $this->input->post('user_id');

		$pairs = $this->common_model->getTableData('trade_pairs',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
		$pair= $this->common_model->getTableData('currency',array('status'=>'1'),'','','','','','', array('id', 'ASC'))->result();
		$rude = array();$i=0;
		if(isset($pairs) && !empty($pairs)){
foreach($pairs as $pair_details){

    $from_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->from_symbol_id))->row();
    $to_currency = $this->common_model->getTableData('currency',array('id' => $pair_details->to_symbol_id))->row();
    $pair_symbol = $from_currency->currency_symbol.'/'.$to_currency->currency_symbol;
    $pair_url = $from_currency->currency_symbol.'_'.$to_currency->currency_symbol;
    $currency = getcryptocurrencydetail($from_currency->id);
    $pair_id = $pair_details->id;

    $favourite_pairs = $this->common_model->getTableData('favourite_pairs',array('user_id'=>$user_id,'pair_id'=>$pair_id))->row();
    

    // $data['favs'] = ;

    if(!empty($favourite_pairs))
    $fav = '1';
    else
    $fav = '0';

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
                         "trade_url"=>base_url().'trade/'.$pair_url,
                         "fav_pair" => $fav

                     );
            array_push($rude, $markets[$i]); 

            $i++;

                            }

                            $data['status']='1';
                            $data['msg']='success';
                            $data['markets']=$markets;
                        }
                        else{
                        $data['status']='0';
                            $data['msg']='No records found';    
                        }

            echo json_encode($data);

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



    function category()
    {
    	$data = array();
    	$data['category'] = $this->common_model->getTableData('support_category', array('status' => '1'))->result();

    	// $data['status'] = '1';
    	// $data['msg'] = 'success';
    	echo json_encode($data);
    }

    function user_kyc_details()
    {
    	$data = array();

    	$user_id = $this->input->post('user_id');

    	if(!empty($user_id) && $user_id >0)
    	{



    	$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id),'photo_id_1,photo_id_2,photo_id_3')->row();

    	$users = $this->common_model->getTableData('users',array('id'=>$user_id),'photo_1_status,photo_2_status,photo_3_status,verify_level2_status')->row();

    	// print_r($users);
    	if($users->photo_1_status ==0)
    	{
    		$det = 'Not Uploaded';
    	}
    	elseif($users->photo_1_status ==1)
    	{
    		$det = 'Pending';
    	}
    	elseif($users->photo_1_status ==2)
    	{
    		$det = 'Rejected';
    	}
    	elseif($users->photo_1_status ==3)
    	{
    		$det = 'Completed';
    	}
//----------------------
    	if($users->photo_2_status ==0)
    	{
    		$detile = 'Not Uploaded';
    	}
    	elseif($users->photo_2_status ==1)
    	{
    		$detile = 'Pending';
    	}
    	elseif($users->photo_2_status ==2)
    	{
    		$detile = 'Rejected';
    	}
    	elseif($users->photo_2_status ==3)
    	{
    		$detile = 'Completed';
    	}

    	//------------------------


    	if($users->photo_3_status ==0)
    	{
    		$detiles = 'Not Uploaded';
    	}
    	elseif($users->photo_3_status ==1)
    	{
    		$detiles = 'Pending';
    	}
    	elseif($users->photo_3_status ==2)
    	{
    		$detiles = 'Rejected';
    	}
    	elseif($users->photo_3_status ==3)
    	{
    		$detiles = 'Completed';
    	}



    	if($users->verify_level2_status =='Completed'){
    		$verify = 'Completed';
    	}elseif($users->verify_level2_status =='Rejected')
    	{
    		$verify = 'Rejected';
    	}
    	elseif($users->verify_level2_status =='Pending')
    	{
    		$verify = 'Pending';
    	}
    	else
    	{
    		$verify = 'Not Uploaded';
    	}

    	$verify_state = $verify;
    	$status2 = $detiles;
    	$status1 = $detile;
    	$status = $det;


    	$data['address_status'] = $status;
    	$data['id_status'] = $status1;
    	$data['selfie_status'] = $status2;

    	$data['verify_level2_status'] = $verify_state;

    	$data['status'] = '1';
    	$data['msg'] = 'success';
    		}else
    		{
    			$data['status'] = '0';
    			$data['msg'] = 'you are not logged in';
    		}

    	echo json_encode($data);
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


	function update_bank_details_app()
	{		 
		$data=array();

		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			$data['status'] = '0';
			$data['msg'] = 'Please Login';
		}
		if($_POST)
		{
			$this->form_validation->set_rules('bank_account_number', 'Bank Account number', 'required|xss_clean');
			$this->form_validation->set_rules('currency', 'currency', 'required|xss_clean');
			$this->form_validation->set_rules('bank_account_name', 'bank_account_name', 'required|xss_clean');
			$this->form_validation->set_rules('bank_swift', 'bank_swift', 'required|xss_clean');
			$this->form_validation->set_rules('bank_name', 'bank_name', 'required|xss_clean');
			$this->form_validation->set_rules('bank_address', 'bank_address', 'required|xss_clean');
			$this->form_validation->set_rules('bank_country', 'bank_country', 'required|xss_clean');
			$this->form_validation->set_rules('bank_city', 'bank_city', 'required|xss_clean');
			$this->form_validation->set_rules('bank_postalcode', 'bank_postalcode', 'required|xss_clean');


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
					$data['status'] = '1';
					 $data['msg'] = 'Bank details Updated Successfully';

					
				} else {
					$data['status'] = '0';
			  		 $data['msg'] = 'Something ther is a Problem .Please try again later';
					
				}
			}
			else
			{
				
				 $data['msg'] = validation_errors();
				
			}
		}		
		echo json_encode($data);
	}

	function security_app()
	{	
		$data=array();
		// $this->load->library('session','form_validation');
		$user_id=$this->input->post('user_id');
		if(!empty($user_id) && $user_id >0)
		{	
			
		
		$users= $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$this->load->library('Googleauthenticator');
		if($data['users']->randcode=="enable" || $data['users']->secret!="")
		{	
			$secret = $users->secret; 
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

		// if($_POST)
		// {

		// 	$secret_code = $this->db->escape_str($this->input->post('secret'));
		// 	$onecode = $this->db->escape_str($this->input->post('code'));
		// 	$code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 3);

		// 	if($users->randcode != "enable")
		// 	{

		// 		if($code=='1')
		// 		{
		// 			$this->db->where('id',$user_id);
		// 			$data1=array('secret'  => $secret_code,'randcode'  => "enable");
		// 			$this->db->update('users',$data1);

		// 			 $data['status']='1';
		// 			 $data['msg'] ='TFA Enabled successfully';
					
		// 		}
		// 		else
		// 		{
		// 			$data['status'] = '0';
		// 		$data['msg']	='Please Enter correct code to enable TFA';
					
					
					
		// 		}
		// 	}
		// 	else
		// 	{
		// 		if($code=='1')
		// 		{
		// 			$this->db->where('id',$user_id);
		// 			$data1=array('secret'  => $secret_code,'randcode'  => "disable");
		// 			$this->db->update('users',$data1);	
		// 			$data['status'] = '1';
		// 			  $data['msg'] ='TFA Disabled successfully';
					
		// 		}
		// 		else
		// 		{
		// 			$data['status'] = '0';
		// 			 $data['msg']='Please Enter correct code to disable TFA';
		// 			/*echo $secret_code."<br/>";
		// 			echo $code."Pila<br/>";
		// 			echo $onecode;
		// 			exit();*/
					
		// 		}
		// 	}
		// }else
		// {
		// 	$data['status'] = '0';
		// 	$data['msg'] = 'Some datas are missing';
		// }
	}else
	{
		$data['status']='0';
			$data['msg'] = 'Please Login';
	}

		echo json_encode($data);
	}


	// function tfa_status()
	// {
	// 	$data = array();
	// 	$user_id = $this->input->post('user_id');
	// 	if(!empty($user_id) && $user_id >0)
	// 	{

 //      $users= $this->common_model->getTableData('users',array('id'=>$user_id))->row();
	// 		// if($_POST)
	// 	 //   {

	// 		$secret_code = $this->db->escape_str($this->input->post('secret'));
	// 		$onecode = $this->db->escape_str($this->input->post('code'));
	// 		$code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 3);

	// 		if($users->randcode != "enable")
	// 		{

	// 			if($code=='1')
	// 			{
	// 				$this->db->where('id',$user_id);
	// 				$data1=array('secret'  => $secret_code,'randcode'  => "enable");
	// 				$this->db->update('users',$data1);

	// 				 $data['status']='1';
	// 				 $data['msg'] ='TFA Enabled successfully';
					
	// 			}
	// 			else
	// 			{
	// 				$data['status'] = '0';
	// 			$data['msg']	='Please Enter correct code to enable TFA';
					
					
					
	// 			}
	// 		}
	// 		else
	// 		{
	// 			if($code=='1')
	// 			{
	// 				$this->db->where('id',$user_id);
	// 				$data1=array('secret'  => $secret_code,'randcode'  => "disable");
	// 				$this->db->update('users',$data1);	
	// 				$data['status'] = '1';
	// 				  $data['msg'] ='TFA Disabled successfully';
					
	// 			}
	// 			else
	// 			{
	// 				$data['status'] = '0';
	// 				 $data['msg']='Please Enter correct code to disable TFA';
					
					
	// 			}
	// 		}
	// 	// }else
	// 	// {
	// 	// 	$data['status'] = '0';
	// 	// 	$data['msg'] = 'Some datas are missing';
	// 	// }
	// 	}
	// 	else
	// 	{
	// 		$data['status'] = '0';
	// 		$data['msg'] = 'You are not Logged in';
	// 	}
	// 	echo json_encode($data);
	// }




	function tfa_status(){

			$user_id=$this->input->post('user_id');
			$data = array();
			if(!isset($user_id) && empty($user_id))
			{	
				

				$data['status'] = '0';
				$data['msg'] = "You are not Logged in";
			}

			else{
			$secret_code = $this->db->escape_str($this->input->post('secret'));
			$onecode = $this->db->escape_str($this->input->post('code'));
			$this->load->library('Googleauthenticator');
			$ga     = new Googleauthenticator();
			$code = $ga->verifyCode($secret_code,$onecode,$discrepancy = 3);

			$users= $this->common_model->getTableData('users',array('id'=>$user_id))->row();

			if($users->randcode != "enable")
			{

				if($code=='1')
				{
					$this->db->where('id',$user_id);
					$data1=array('secret'  => $secret_code,'randcode'  => "enable");
					$this->db->update('users',$data1);

					$data['status'] = '1';
					$data['msg'] = "TFA Enabled successfully";
				}
				else
				{
					$data['status'] = '0';
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

					$data['status'] = '1';
					$data['msg'] = "TFA Disabled successfully";
				}
				else
				{
					$data['status'] = '1';
					$data['msg'] = "Please Enter correct code to disable TFA";
				}
			}
		}
		echo json_encode($data);
		
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
		 

		
		if($cur=='')
		{
			$Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'),'id')->row();
			$coin_address = getAddress($user_id,$Fetch_coin_list->id);
				
						
		}
		else
		{
			$coin_address = getAddress($user_id,$cur_id);
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



	function currency_deposits(){

    $user_id=$this->input->post('user_id');
    //$user_id = 1;
    $data = array();
    if(!isset($user_id) && empty($user_id))
    { 
      

      $data['status'] = 0;
      $data['msg'] = "You are not Logged in";
      echo json_encode($data);   
      // exit();
    }
    else{
      $Users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

        $currency_id=$this->input->post('currency_id');

      if($currency_id!='')
      {
        $Currency = $this->common_model->getTableData('currency',array('status'=>'1','id' =>$currency_id ,'type'=>'digital','deposit_status'=>1))->row();

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

      $Currency = $this->common_model->getTableData('currency',array('status'=>'1','type'=>'digital','deposit_status'=>1))->result();

      $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);

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

        $Currency_list_Val[$i] = array( "currency_id"=>$Currency_list->id,
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
      $data['msg']  = 'success';
      $data['deposit'] = $rude;
      $j=0;$rude1=array();
      

      // $data['deposit_history'] = $rude1;

      // $data['username'] = $Users->cripyic_username;
    }
    echo json_encode($data);
  }


  function deposit_history()
  {

  	$data = array();
  	$user_id = $this->input->post('user_id');
  	if(empty($user_id) && $user_id <0)
  	{
  		  $data['status'] = '0';
      $data['msg'] = "You are not Logged in";
  	}
  	else
  	{

  		$j=0;$rude1=array();
			$deposit_history = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Deposit'),'','','','','','',array('trans_id','DESC'))->result();
			foreach($deposit_history as $deposit){

				 

                          $Currency_Image = getcryptocurrencydetail($deposit->currency_id)->image;
                          $Currency_Symbol = getcryptocurrency($deposit->currency_id);

				$deposit_history_list[$j]=array("currency_image"=>$Currency_Image,
												"amount"=>$deposit->amount,
												"currency_symbol"=>$Currency_Symbol,
												"status"=>$deposit->status,
												"datetime"=>date('d-M-Y H:i',$deposit->datetime));

				array_push($rude1, $deposit_history_list[$j]);
				$j++;
			}

			$data['deposit_history'] = $rude1;
  	}

  	echo json_encode($data);


  }



	function currency()
	{
		$data = array();
		$user_id = $this->input->post('user_id');
		if(!empty($user_id) && $user_id >0)
		{
			$data['currency'] = $this->common_model->getTableData('currency',array('status'=>1,'deposit_status'=>1),'id,currency_name,currency_symbol,type')->result();

			// $data = array(

			// 		'currency_symbol'=>$currency->currency_symbol,
			// 		'currency_name'=>$currency->currency_name,
			// 		'type'=>$currency->type,
			// 		'currency_id'=>$currency->currency_id
			// );
		}
		else
		{
			$data['status']='0';
			$data['msg'] = 'Please Login';
		}
		echo json_encode($data);
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


	
 

	function withdraw_app(){

		$data = array();

		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			  $data['msg'] =  'you are not logged in';
			$data ['status'] = '0';
		} 
		

		$id = $this->input->post('currency_id');

		// $data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		// $data['site_common'] = site_common();	
		// $data['currency'] = $this->common_model->getTableData('currency',array('status'=>1,'withdraw_status'=>1),'','','','','','',array('id','ASC'))->result();	 
		// $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		if(isset($id) && !empty($id)){
			$sel_currency = $this->common_model->getTableData('currency',array('id'=>$id),'','','','','','',array('id','ASC'))->row();

			// echo $this->db->last_query();
			// exit;

				if($sel_currency->withdraw_status==0)
				{
					$data['msg'] =  'Withdraw Disabled Please Contact admin';
						  $data ['status'] = '0';	
				} 


			$data['selcsym'] = $id;

			$data['fees_type'] = $sel_currency->withdraw_fees_type;
			$data['fees'] = $sel_currency->withdraw_fees;
		}
		else{
			$sel_currency = $this->common_model->getTableData('currency',array('status' => 1),'','','','','','',array('id','ASC'))->row();
			$data['selcsym'] = $sel_currency->currency_symbol;
			
			$data['fees_type'] = $sel_currency->withdraw_fees_type;
			$data['fees'] = $sel_currency->withdraw_fees;
		}

		$cur_id = $sel_currency->id;
		// $data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details')->row(); 
		// $data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

		$data['balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

		$data['user_balance'] = getBalance($user_id,$cur_id);
		$data['currency'] = $sel_currency->currency_symbol;
		
		if(isset($_POST))
	    {
	    	// echo 'hii';
	    	// exit;

			
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
			// $passinp = $this->db->escape_str($this->input->post('ids'));
			// $myval = explode('_',$passinp);
			$id= $this->input->post('currency_id');

			// $name = $myval[1];
			$bal =  getBalance($user_id,$id);
			$this->form_validation->set_rules('address', 'Address', 'trim|required|xss_clean');
		    $userbalance = getBalance($user_id,$id);
		    
			/*if ($this->form_validation->run()!= FALSE)
			{ echo 'dddd'; exit;*/
				$amount = $this->db->escape_str($this->input->post('amount'));
				
				$address = $this->db->escape_str($this->input->post('address'));

			

				$Payment_Method = 'crypto';
				$Currency_Type = 'crypto';
				$Bank_id = '';				
	 			
	 			$balance= getBalance($user_id,$id,'crypto');



				$currency= getcryptocurrencydetail($id);

				// print_r($balance);
				// exit;

				
				$w_isValids   = $this->common_model->getTableData('transactions', array('user_id' => $user_id, 'type' =>'Withdraw', 'status'=>'Pending','user_status'=>'Pending','currency_id'=>$id));
				$count        = $w_isValids->num_rows();
	            $withdraw_rec = $w_isValids->row();
                $final = 1;

				if($userbalance==$bal)
				{	
						
					if($count>0)
					{							
						$data['msg'] =  'Sorry!!! Your previous withdrawal is waiting for admin approval. Please use other wallet or be patience';
							$data ['status'] = '0';
					}
					else
					{
						


						if($amount>$balance)
						{
							 $data['msg'] =  'Amount you have entered is more than your current balance';
							$data ['status'] = '0';
							echo json_encode($data);
							exit;
						}
						if($amount < $currency->min_withdraw_limit)
						{
							// print_r($amount);exit

							$data['msg'] = 'Amount you have entered is less than minimum withdrawl limit';
							$data ['status'] = '0';
						}
						elseif($amount > $currency->max_withdraw_limit)
						{

							$data['msg'] = 'Amount you have entered is more than maximum withdrawl limit'.$currency;
							$data ['status'] = '0';
						}
						elseif($final!=1)
						{
							$data['msg'] = 'Invalid address';
							$data ['status'] = '0';
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
								$data['msg'] ='Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!';
								$data ['status'] = '1';

								// echo json_encode($data);
								// exit;
							} 
							else 
							{
								$data['msg'] ='Unable to submit your withdraw request. Please try again';
								$data ['status'] = '0';
							}
						}
					}
				}
				else
				{
					$data['msg'] ='Incorrect Values!'; 
					$data ['status'] = '0';
				}	
	    }




		$data['user_id'] = $user_id;
		
		echo json_encode($data);
}  


	public function create_offer_app(){

		$data = array();

  $user_id=$this->input->post('user_id');
  if($user_id=="")
  { 
    	$data['msg']='you are not logged in';
        $data['status']='0';
  }




  // $data['site_common'] = site_common(); 
  // $data['action'] = front_url() . 'create_offer';

$kyc = getUserDetails($user_id);
$kyc_status=$kyc->verify_level2_status;
  if($kyc_status != 'Completed'){

     $data['msg'] =  'KYC is Not Completed';
   		 $data['status']='0';

  }


  
  

   if($_POST)
   {
  
  // echo "<pre>";
  // print_r($this->input->post());
  // echo "<pre>";
  // exit();




    $minimum = $this->input->post('minimum_limit');
    $maximum = $this->input->post('maximum_limit');
    $trde_amt = $this->input->post('trade_amount');

    $total_cal = $this->input->post('price') *  $maximum;



    if($minimum > $maximum)
    {
     $data['msg'] = 'Maximum Amount you have entered is less than minimum p2p limit';
      $data['status']='0';
    }
    else if($trde_amt > $total_cal)
    {

     $data['msg'] = 'Please Increase Your Trade Amount with Maximum Price';
     $data['status']='0';
	}




    else {






  $user_id=$this->input->post('user_id');
  $crypto = $this->db->escape_str($this->input->post('cryptocurrency'));
  $currency = $this->db->escape_str($this->input->post('fiat_currency'));
  // $commission = $this->db->escape_str($this->input->post('commission'));
  $trade_amount = $this->db->escape_str($this->input->post('trade_amount'));
  $minimum_limit = $this->db->escape_str($this->input->post('minimum_limit'));
  $maximum_limit = $this->db->escape_str($this->input->post('maximum_limit'));
  $instraction = $this->db->escape_str($this->input->post('instraction'));
  $price = $this->db->escape_str($this->input->post('price'));
  $actualtrade = $this->db->escape_str($this->input->post('type'));
  $payment = $this->db->escape_str($this->input->post('payment'));
  $country = $this->db->escape_str($this->input->post('country'));
  $traderandom = mt_rand(10000000,99999999);

   $userbalance = getBalance($user_id,$crypto);



   if($actualtrade=='sell')
   {
    // Seller Get Balance 
   
    if($trade_amount > $userbalance)
    {
        $data['msg'] = 'Amount you have entered is more than your current balance';
        $data['status']='0';
         echo json_encode($data);
         exit();

     }

   }


if($actualtrade=='buy'){
  $type="Sell";
}else{
  $type="Buy";
}

  $insert_offer=array(
    'cryptocurrency'=>$crypto,
    'country'=>$country,
    'price'=>$price,
    'trade_amount'=>$trade_amount,
    'payment_method'=>$payment,
    'minimumtrade'=>$minimum_limit, 
    'maximumtrade'=>$maximum_limit,
    'fiat_currency'=>$currency,
    // 'comission'=>$commission,
    'addtional_info'=>$addinstraction,
    'terms_conditions'=>$instraction,
    'type'=>$type,
    'status'=>1,
    'tradestatus'=>'open',
    'paid_status'=>'open',
    'actualtradebuy'=>$actualtrade,
    'user_id'=>$user_id,
    'tradeid'=>$traderandom,
    'datetime'=>date("Y-m-d h:i:s"),
  );
   // print_r($insert_offer);
   //  exit();
   $user_data_clean = $this->security->xss_clean($insert_offer);
   $id=$this->common_model->insertTableData('p2p_trade', $user_data_clean);

    if($actualtrade=='sell')
   {
    // Seller Get Balance 
    if($userbalance >= $trade_amount)
    {
          $finalbalance=$userbalance-$trade_amount;
          // Update user balance  
          $updatebalance = updateBalance($user_id,$crypto,$finalbalance);
    }
    

   }

   $data['msg'] ='Order Created successfully';
   $data['status'] = '1';

 }

	echo json_encode($data);

  }


}

function create_offer_userdetails()
{
	$data = array();
	$user_id = $this->input->post('user_id');

	$ip_address = $this->input->post('ip_address');
	$details =  json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip_address));
	$country_row = $this->common_model->getTableData('countries',array('country_name'=>$details->geoplugin_countryName))->row();
	$country = $country_row->id;




	if($user_id=="")
	{
			$data['msg']='you are not logged in';
             $data['status']='0';
	}
	else
	{


	$default_cur = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('id','ASC'))->row();
	$data['user_balance'] =  (string)getBalance($user_id,$default_cur->id,'crypto'); 
	if($ip_address!='' && $country!='')
	{
		$data['fiatcurrency']=$this->common_model->getTableData('fiat_currency',array('status' => 1,'country'=>$country))->result();
		$data['services'] = $this->common_model->getTableData('service',array('status' => 1,'country'=>$country))->result();
	}
	else
	{
		$data['fiatcurrency']=$this->common_model->getTableData('fiat_currency',array('status' => 1))->result();
		$data['services'] = $this->common_model->getTableData('service',array('status' => 1))->result();
	}

	
	




}
	echo json_encode($data);
}


function p2p_history_order()
{
	$data = array();
	$user_id = $this->input->post('user_id');
	if($user_id=="")
	{
		  $data['msg'] =  'you are not logged in';
			$data ['status'] = '0';
	}
	else
	{	$j=0;$rude1=array();
		$history = $this->common_model->getTableData('p2p_trade',array('user_id'=>$user_id,'tradestatus'=>'open'))->result();
		foreach($history as $p2p_history){
			$coindetails = getcurrencydetail($p2p_history->cryptocurrency);
			$p2p[$j] = array(

							'id' => $p2p_history->id, 
							'price'=>$p2p_history->price,
							'Amount'=>$p2p_history->trade_amount,
							'crypto' => $coindetails->currency_symbol,
							'trade_id' => $p2p_history->tradeid,
							// 'tradestatus'=>$p2p_history->trades,
							// 'amount'=>$p2p_history->amount,
							'type'=>$p2p_history->type,
							'status'=>$p2p_history->tradestatus,
							'datetime'=>$p2p_history->datetime
			);

			array_push($rude1, $p2p[$j]);
				$j++;

		}
		$data['p2p_open_order_history'] = $rude1;
	}
	echo json_encode($data);
}


function p2p_open_order_cancel(){

	$data = array();

  $user_id = $this->input->post('user_id');
  if($user_id=="")
  { 
       $data['msg'] =  'you are not logged in';
			$data ['status'] = '0';
    
  }

  $id = $this->input->post('id');

  if($id!='')
  {

    // $id = decryptIt($id);

    $gettrade    = $this->common_model->getTableData('p2p_trade', array('id' => $id))->row();
    if($gettrade->tradestatus=='open')
    {

      if($gettrade->actualtradebuy=='sell')
      {
            $trade_amount = $gettrade->trade_amount;
            $userbalance = getBalance($user_id,$gettrade->cryptocurrency);
            if($userbalance >= $trade_amount)
            {
                  $finalbalance=$userbalance+$trade_amount;
                  // Update user balance  
                  $updatebalance = updateBalance($user_id,$gettrade->cryptocurrency,$finalbalance);
            }
      }

      $condition=array('id'=>$id);
      $updateData=array('tradestatus'=>'cancelled');
      $update = $this->common_model->updateTableData('p2p_trade',$condition,$updateData);
      $data['msg'] ='Order Cancelled Successfully';
     $data ['status'] = '1';
}
    else
    {
     $data['msg'] ='Unable To cancel the Order. Please Try Again';
       $data ['status'] = '0';

    }


  }
echo json_encode($data);
 
 }
//exchange

function execute_order_app()
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

if($user_id !="")
{    
// echo $user_id;exit;    
    $response     = $this->site_api->createOrder($user_id,$amount,$price,$limit_price,$total,$fee,$pair,$ordertype,$type,$loan_rate,$pagetype);
    if($response['status'] == "success"){
        $array['status'] = "1";
        $array['msg'] = "Order Placed successfully";
    }elseif($response['status'] == "balance"){
        $array['status'] = "0";
        $array['msg'] = "Insufficient Balance";
    }

    elseif($response['status'] == "empty"){
        $array['status'] = "0";
        $array['msg'] = "Some Fields Empty";
    }

    elseif($response['status'] == "minimum_amount"){
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



  function close_active_order_app()
{
    $tradeid = $this->input->post('trade_id');
    $user_id = $this->input->post('user_id');
    $pair_id = $this->input->post('pair_id');

    $response = array('status'=>'','msg'=>'');
    if(!isset($user_id) && empty($user_id)){

        $array['status'] = '0';
        $array['msg'] = "User ID cannot be empty";
    }
    else if(empty($tradeid)){

        $array['status'] = '0';
        $array['msg'] = "Trade ID cannot be empty";
    }else{
    $response=$this->site_api->close_active_order($tradeid,$pair_id,$user_id);
    if($response['msg']=="success"){
        $array['status'] = '1';
        $array['msg'] = "Order Cancelled Successfully";
    }elseif($response['msg']=="error"){
        $array['status'] = '0';
        $array['msg'] = "Order Cancelled Failed";
    }else{
        $array['status'] = '0';
        $array['msg'] = "Unknown Error";
    }

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

    $data['status'] = '0';
    $data['msg'] = "Pair cannot be empty";
}
else{

    $data['status'] = '1';
    $data['msg'] = "success";
    $trade_pair_data = $this->common_model->customQuery("SELECT * FROM cpm_trade_pairs WHERE id='".$pair_id."'")->result();
    $data['last_price'] =$trade_pair_data[0]->lastPrice;
    $data['priceChangePercent'] =$trade_pair_data[0]->priceChangePercent;
    $data['min_trade_amount'] =$trade_pair_data[0]->min_trade_amount;

     $data['volume'] =$trade_pair_data[0]->volume;
    // $data['maker_fee'] =getfeedetails_buy($pair,1);
    $data['maker_fee'] = $this->maker=getfeedetails_buy($pair_id);
    $data['taker_fee'] =$this->taker=getfeedetails_sell($pair_id);
    // $user_balance = getBalance($user_id);
    $data['first_balance']  =  (string)getBalance($user_id,$second_currency[0]->id);
    $data['second_balance']  = (string)getBalance($user_id,$first_currency[0]->id);
    $data['lastmarketprice']=number_format(marketprice_pair($pair),8); 

    // $pair_details = getPairdetails($pair_id);

    $data['change_high'] = $trade_pair_data[0]->change_high;
    $data['change_low'] = $trade_pair_data[0]->change_low;
}
echo json_encode($data);
exit();

}


function user_tradeorders()

{
	$data = array();
	$user_id = $this->input->post('user_id');
	// $pair = $this->input->post('pair');
	// $pair_id = $this->input->post('pair_id');

  if($user_id=="")
  { 
       $data['msg'] =  'you are not logged in';
	   $data ['status'] = '0';
    
  }
  else
  {

  		$opens = $this->get_active_order($user_id);
		if($opens > 0)
			$data['open_orders']=$this->get_active_order($user_id);
		else
			$data['open_orders']=[];

	$trade_history = $this->getOrderHistoryApi($user_id);


	if($trade_history > 0)
		$data['trade_history'] = $trade_history;
	else
		$data['trade_history'] =[];

 

  }

  echo json_encode($data);


}





function get_active_order($user_id)
{
    $user_id = $user_id;

    // $sql = "SELECT `CO`.*, date_format(CO.datetime, '%d-%b-%Y %h:%i %p') as trade_time, sum(OT.filledAmount) as totalamount FROM `cpm_coin_order` as `CO` LEFT JOIN `cpm_ordertemp` as `OT` ON `CO`.`trade_id` = `OT`.`sellorderId` OR `CO`.`trade_id` = `OT`.`buyorderId` LEFT JOIN `cpm_trade_pairs` as `TP` ON `CO`.`pair` = `TP`.`id` WHERE `CO`.`userId` = '".$user_id."' AND `CO`.`status` IN('active', 'partially', 'margin', 'stoporder') GROUP BY `CO`.`trade_id` ORDER BY `CO`.`trade_id` DESC";


    // $selectFields='CO.*,date_format(CO.datetime,"%d-%m-%Y %H:%i %p") as trade_time,sum(OT.filledAmount) as totalamount';
    // $names = array('active', 'partially', 'margin','stoporder');
    // $where=array('CO.userId'=>$user_id);
    // $orderBy=array('CO.trade_id','desc');
    // $groupBy=array('CO.trade_id');
    // $where_in=array('CO.status', $names);
    // $joins = array('ordertemp as OT'=>'CO.trade_id = OT.sellorderId OR CO.trade_id = OT.buyorderId');
    $query = $this->common_model->customQuery("SELECT `CO`.*, date_format(CO.datetime, '%d-%b-%Y %h:%i %p') as trade_time, sum(OT.filledAmount) as totalamount FROM `cpm_coin_order` as `CO` LEFT JOIN `cpm_ordertemp` as `OT` ON `CO`.`trade_id` = `OT`.`sellorderId` OR `CO`.`trade_id` = `OT`.`buyorderId` LEFT JOIN `cpm_trade_pairs` as `TP` ON `CO`.`pair` = `TP`.`id` WHERE `CO`.`userId` = '".$user_id."' AND `CO`.`status` IN('active', 'partially', 'margin', 'stoporder') GROUP BY `CO`.`trade_id` ORDER BY `CO`.`trade_id` DESC");



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
        $arr = array();
        foreach ($open_orders as $getOrder) {
        	
        	$activefilledAmount = $getOrder->totalamount;
        	$activePrice = $getOrder->Price;
        	$activeAmount  = $getOrder->Amount;

          if($activefilledAmount)
          {
            $activefilledAmount = $activeAmount-$activefilledAmount;
          }
          else
          {
            $activefilledAmount = $activeAmount;
          }

          $activefilledAmount= number_format($activefilledAmount,6);
          $trade_id = $getOrder->trade_id;
          $odr_type = $getOrder->Type;
          $odr_status = $getOrder->status;
          if($odr_type=='buy')
          {
            $odr_color = 'text-success';
            $ordtype1 = 'Buy';
            // var activeCalcTotal = Number(activefilledAmount*activePrice) + Number(Fee);
            $activeCalcTotal = $activefilledAmount* $activePrice ;
            $activeCalcTotal=number_format($activeCalcTotal,6);
          }
          else
          {
            $odr_color = 'text-danger';
            $ordtype1 = 'Sell';
            // var activeCalcTotal = Number(activefilledAmount*activePrice) - Number(Fee);
            $activeCalcTotal = $activefilledAmount*$activePrice;
            $activeCalcTotal=number_format($activeCalcTotal,6);

           } 

          $time = $getOrder->trade_time;
          // $pair_symbol =  str_replace("_","/",$getOrder->pair_symbol);

          $pairy  = $getOrder->pair;               
          $ordtypes = $getOrder->ordertype;
          if($ordtypes == 'limit') $ordtype = 'Limit';
          else if($ordtypes == 'stop') $ordtype = 'Stop Order';
          else if($ordtypes == 'instant') $ordtype = 'Market';
          else $ordtype = '-';

           $object_data = array(
            // 'trade_time'=>$time,
            // 'pair_symbol'=>$pair_symbol,
            // 'Type'=>$ordtype1,
            // 'class'=>$odr_color,
            // 'ordertype'=>$ordtype,
            // 'Price'=>$activePrice,
            // 'Amount'=>$activefilledAmount,
            // 'Total'=>$activeCalcTotal,
            // 'userId'=>$id,
            // 'trade_id'=>$trade_id,
            // 'pair'=>$pairy

            'pairSymbol' => $getOrder->pair_symbol,
            'pair' => $getOrder->pair,
            'tradeId' => $getOrder->trade_id,
            'type' => $getOrder->Type,
            'amount' => $activefilledAmount,
            'price' => $getOrder->Price,
            'ordertype' => $getOrder->ordertype,
            'ordertype' => $getOrder->ordertype,
            'datetime'  => $getOrder->datetime  


          );

           array_push($arr, $object_data);


          }
          $open_orders_text = $arr;


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
    $query = $this->common_model->customQuery('select trade_id, Type, Price, Amount, Fee, Total, status, date_format(datetime,"%d-%m-%Y %H:%i %a") as tradetime from cpm_coin_order where userId = '.$user_id.' and status = "stoporder" and pair = '.$pair_id.'');
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



	public function getOrderHistoryApi($user_id)
{
//$user_id = $this->session->userdata('user_id');
$orders=array();
// $pair=explode('_',$pair_symbol);

// $pair_id=0;
// if(count($pair)==2)
// {
//     $joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
//     $where = array('a.status'=>1,'b.status!='=>0,'c.status!='=>0);
//     $orderprice = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,$where,'a.*');

//     if($orderprice->num_rows()==1)
//     {
//         $pair_details=$orderprice->row();
//         $pair_id=$pair_details->id;
//     }

// }

    // $sql = "SELECT a.*, b.ordertype as ordertype, date_format(b.datetime, '%H:%i:%s') as sellertime, b.trade_id as seller_trade_id, date_format(c.datetime, '%H:%i:%s') as buyertime, c.pair_symbol as pair_symbol, c.trade_id as buyer_trade_id, a.askPrice as sellaskPrice, c.Price as buyaskPrice, b.Fee as sellerfee, c.Fee as buyerfee, b.Total as sellertotal, b.Amount as selleramount,c.Total as buyertotal,c.Amount as buyeramount, c.status as status, b.status as statuss FROM `cpm_ordertemp` as `a` JOIN `cpm_coin_order` as `b` ON `a`.`sellorderId` = `b`.`trade_id` JOIN `cpm_coin_order` as `c` ON `a`.`buyorderId` = `c`.`trade_id` WHERE  `c`.`userId` = '".$user_id."' OR  `b`.`userId` = '".$user_id."' ORDER BY `a`.`tempId` DESC LIMIT 40";


$selectFields='CO.*,SUM(CO.Amount) as TotAmount,date_format(CO.datetime,"%d-%m-%Y %H:%i") as trade_time,sum(OT.filledAmount) as totalamount,sum(OT.filledAmount) as Amount';

$names = array('filled','partially');
// $where=array('CO.pair'=>$pair_id);
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
    //     if($val->status == 'partially')
    //     {
    //         $val->balance = $val->Amount - $val->totalamount;
    //     } else {
    //         $val->balance = '-';
    //     }
    // }
    $orders=$result;
}
else
{
    $orders=[];
}
return $orders;

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



// Exchange END---------------------////-------------------

// Crypto Fiat Withdraw

	 function withdraw_history(){

	 	$data = array();
		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			  $data['msg'] =  'you are not logged in';
			$data ['status'] = '0';
		} 
		else{
			$Users = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

			// $data['Products'] = Get_Paypeaks_Products('DEBIT','MOBILE MONEY');
			// $datas['card_products'] = Get_Paypeaks_Products('CARD','CARD');
 
			$Currency = $this->common_model->getTableData('currency',array('status'=>'1'))->result();

			$data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);

			$j=0;$rude1=array();
			$withdraw_history = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();			
			foreach($withdraw_history as $withdraw){
                          $Currency_Image = getcryptocurrencydetail($withdraw->currency_id)->image;
                          $Currency_Symbol = getcryptocurrency($withdraw->currency_id);

				$deposit_history_list[$j]=array("currency_image"=>$Currency_Image,
												"amount"=>$withdraw->amount,
												"currency_symbol"=>$Currency_Symbol,
												"status"=>$withdraw->status,
												"datetime"=>date("d-m-Y H:i", strtotime($withdraw->datetime)),
												"type"=>$withdraw->payment_method
											);

												

				array_push($rude1, $deposit_history_list[$j]);
				$j++;
			}

			$data['withdraw_history'] = $rude1;

			// $data['username'] = $Users->cripyic_username;
		}
		echo json_encode($data);

	 }



	 public function p2porder_map()
{
    

    $user_id=$this->input->post('user_id');
    if($user_id=="")
    { 
      $data['msg'] ='you are not logged in';
      $data ['status'] = '0';
    }

    if($_POST)
    {



        $id=$this->db->escape_str($this->input->post('trade_id'));
        $gettrade = $this->common_model->getTableData('p2p_trade', array('tradeid' => $id))->row();

        $gettradeorder_amt = $this->common_model->getTableData('p2p_trade', array('tradeid' => $id))->row();



        $fiat_amt = $this->db->escape_str($this->input->post('fiat_currency'));
        

        if($fiat_amt > 0) {


        $crypto_amt =  $this->db->escape_str($this->input->post('cryptocurrency'));
        $insertData['tradeid'] = $this->db->escape_str($this->input->post('trade_id'));
        
        $insertData['crypto_amount'] =   $this->db->escape_str($this->input->post('cryptocurrency'));
        $insertData['fiat_amount'] =  $this->db->escape_str($this->input->post('fiat_currency'));

        $insertData['fiat_currency']=  $gettrade->fiat_currency;
        $insertData['cryptocurrency']= $gettrade->cryptocurrency;

        $insertData['escrowamount'] = $this->db->escape_str($gettrade->trade_amount);
        $insertData['tradeopentime'] =  date("Y-m-d H:i:s");
        $insertData['escrowstatus'] = 'waiting';             
        $insertData['type'] = $gettrade->type;

        if($gettrade->actualtradebuy == 'sell'){
          $insertData['sellerid'] =   $gettrade->user_id;
          $insertData['buyerid'] = $user_id;
        } else {
          $insertData['sellerid'] =  $user_id;
          $insertData['buyerid'] =  $gettrade->user_id;
        }
        
        $insertData['user_id']= $user_id;
        $insertData['tradestatus'] = 'open';
        
      



            if($fiat_amt < $gettrade->minimumtrade)
            {
            	 $data['msg'] =  'Amount you have entered is less than minimum p2p limit --- ';
				 $data ['status'] = '0';
             
            }
            elseif($fiat_amt > $gettrade->maximumtrade)
            {
              
              $data['msg'] =  'Amount you have entered is more than maximum p2p limit';
			  $data ['status'] = '0';

            }

            
            

        else {



          if($gettradeorder_amt > 0 && $crypto_amt > $gettradeorder_amt->trade_amount)
            {
              	
              $data['msg'] =  'Amount you have entered is more than Trade Amount';
			  $data ['status'] = '0';

            }


        $insert = $this->common_model->insertTableData('p2ptradeorder', $insertData);
        if($insert)
        {
         
         $link=base_url().'p2ptrade/'.$gettrade->type.'/'.$id.'/'.$insert;
         $this->common_model->updateTableData('p2ptradeorder',array('id'=>$insert),array('link'=>$link));
         $this->common_model->updateTableData('p2p_trade',array('tradeid'=>$id),array('tradestatus'=>'filled','paid_status' =>'open'));
         $checktrade = $this->common_model->getTableData('p2p_trade', array('tradeid' => $id))->row();
         $checktradeorder=  $this->common_model->getTableData('p2ptradeorder', array('id' => $insert))->row();
        
         $buyerusername = $this->common_model->getTableData('users', array('id' => $user_id))->row('cpm_username');
         $sellername = $this->common_model->getTableData('users', array('id' => $gettrade->user_id))->row('cpm_username');
        
        $insopenchat['added'] =  date("Y-m-d H:i:s");
        $insopenchat['user_id'] = $user_id;
        $insopenchat['tradeuser_id'] = $gettrade->user_id;
        $insopenchat['tradeid'] = $gettrade->tradeid;
        $insopenchat['username'] = $buyerusername;
        $insopenchat['tradeorderid'] = $insert;
        $insopenchat['imagetype'] = "alertmsg";
        $insopenchat['tradestatus'] = "open";
        $insopenchat['comment'] = "The Buyer will have ".$gettrade->offertime." minutes to make their payment and click on the PAID button before the trade expires.";

        $this->common_model->insertTableData('p2pchat_history', $insopenchat);   
        
        $getcrypto=$this->common_model->getTableData('currency', array('id' => $gettrade->cryptocurrency))->row();
        $buyeremail=getUserEmail($user_id);
        $selleremail=getUserEmail($gettrade->user_id);                                  

           // Update user balance  
           // $updatebalance = updateBalance($checktrade->user_id,$checktrade->cryptocurrency,$finalbalance,'');
           // check to see if we are Seller
           $email_templats = 'Trade_user_requests_buyer';
           $special_varsseller = array(
           '###USERNAME###' => $sellername,
           '###BUYER###' => $buyerusername,
           '###AMOUNT###'   =>  $checktradeorder->amount,
           '###COIN###'  =>   $checktradeorder->amtofbtc,
           '###TRADCURRENCY###' =>  $gettrade->currency,
           '###TRADEID###'=> "#".$id,
           '###CRYPTO###'=> $getcrypto->currency_symbol,
           '###LINK###' => base_url().'exchange/#/chat/'.$id.'/'.$insert);

            $this->email_model->sendMail($selleremail, '', '', $email_templats, $special_varsseller);

            // check to see if we are Buyer
            $email_template = 'Trade_user_requests_seller';
         
             $special_varsbuyer = array(
           '###USERNAME###' => $buyerusername, 
           '###SELLER###' => $sellername,
           '###AMOUNT###'   =>  $checktradeorder->amount,
           '###COIN###'  =>   $checktradeorder->amtofbtc,
           '###TRADCURRENCY###' =>  $gettrade->currency,
           '###TRADEID###'=> "#".$id,
           '###CRYPTO###'=> $getcrypto->currency_symbol,
           '###LINK###' => base_url().'exchange/#/chat/'.$id.'/'.$insert);

            $this->email_model->sendMail($buyeremail, '', '', $email_template, $special_varsbuyer);
           
            if($gettrade->type=='Buy'){

            	$data['msg'] =  'P2P Trade Started.Successfully Placed !';
			    $data ['status'] = '1';
			    $data['chat_details'] = array(

			    	'trade_id' => $id,
			    	'tradeorderid' => (string)$insert
			    );

                } else {

                  

                  $data['msg'] =  'P2P Trade Started.Successfully Placed !';
			      $data ['status'] = '1';
			       $data['chat_details'] = array(

			    	'trade_id' => $id,
			    	'tradeorderid' => (string)$insert
			    );
                  
                }
        }

       } 



    }
    else
    {
    	$data['msg'] =  'Amount Empty! Please Try Again!';
		$data ['status'] = '0';
    }

}

    echo json_encode($data);
} 


public function p2p_allorders(){
 

  $user_id=$this->input->post('user_id');
  if($user_id=="")
  { 
      $data['msg'] ='you are not logged in';
     $data ['status'] = '0';
  }
 $data['user_id'] = $user_id; 

$ip_address = $this->input->post('ip_address');
$details =  json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip_address));
$country_row = $this->common_model->getTableData('countries',array('country_name'=>$details->geoplugin_countryName))->row();
$country = $country_row->id;

if($ip_address!='' && $country!='')
{
	$condi = array('tradestatus!='=>'cancelled','country'=>$country);
}
else
{
	$condi = array('tradestatus!='=>'cancelled');
}



 $p2p_trade = $this->common_model->getTableData('p2p_trade',$condi)->result();


$i=0;$rude=array();
 foreach($p2p_trade as $p2p)
 {
 	$user_name = UserName($p2p->user_id);
	$Payment = get_servicename($p2p->payment_method);
	$crypto = getcurrency_name($p2p->cryptocurrency);
	$fiats = getfiatcurrencydetail($p2p->fiat_currency);
	$fiatcurrency = $fiats->currency_symbol;

	if($user_id==$p2p->user_id)
		$order_type = 'My Offer';
	else
		$order_type = 'Other';

	if($p2p->trade_amount > 0 ) {

	$p2p_orders[$i] = array(
			'username'=>$user_name,
			'payment'=> ucfirst($Payment),
			'crypto'=>$crypto,
			'fiat'=>$fiatcurrency,
			'terms' => $p2p->terms_conditions,
			'price' => $p2p->price,
			'type' => $p2p->type,
			'tradestatus' => $p2p->tradestatus,
			'paid_status' => $p2p->paid_status,
			'datetime' => $p2p->datetime,
			'status' => $p2p->status,
			'order_type' => $order_type,
			'tradeid' => $p2p->tradeid,
			'trade_amount' => $p2p->trade_amount,
			'minimumtrade' => $p2p->minimumtrade,
			'maximumtrade' => $p2p->maximumtrade,
	);

array_push($rude, $p2p_orders[$i]);
$i++;
$data['p2p_orders'] = $rude;

}

 }


 echo json_encode($data);

}


	 function p2p_create_order_history()
	 {
	 	$data = array();
		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			  $data['msg'] =  'you are not logged in';
			$data ['status'] = '0';
		} 
		else
		{

					
		  $where   = array('buyerid'=>$user_id);

		  $whereor = array('sellerid'=>$user_id);
		  $j=0;$rude1=array();
		  $p2p_orders = $this->common_model->getTableData('p2ptradeorder',$where,'','',$whereor,'','','',array('id','DESC'))->result();
		  foreach($p2p_orders as $orders){

		  			 $buyer = UserName($orders->buyerid);
					 $seller = UserName($orders->sellerid);
					 $crypto = getcurrency_name($orders->cryptocurrency);
					 // $fiat = getcurrency_name($orders->fiat_currency);

					 $fiats = getfiatcurrencydetail($orders->fiat_currency);
					 $fiatcurrency = $fiats->currency_symbol;

		  			$oredrs_history[$j]= array(
		  				'buyer'=>$buyer,
		  				'seller'=>$seller,
		  				'crypto'=>$crypto,
		  				'crypto_amount'=>$orders->crypto_amount,
		  				'fiat'=>$fiatcurrency,
		  				'tradeid'=>$orders->tradeid,
		  				'trade_order_id'=>$orders->id,
		  				'fiat_amount'=>$orders->fiat_amount,
		  				'datetime'=>$orders->tradeopentime,
		  				'status'=>$orders->tradestatus
		  			);

		  			array_push($rude1, $oredrs_history[$j]);
				$j++;

		  }
		  $data['create_oredrs_history'] = $rude1;

		}




		echo json_encode($data);
	 }


// P2p Chat   

 function p2p_chat_app(){

 	$data = array();
	$user_id=$this->input->post('user_id');
	$trade_id = $this->input->post('trade_id');
	$tradeorderid = $this->input->post('tradeorderid');

	if($user_id=="")
	{	
		  $data['msg'] =  'you are not logged in';
		$data ['status'] = '0';
	}
	else { 

    // $data['site_common'] = site_common();
    $tradeorderdetails  = $this->common_model->getTableData('p2ptradeorder', array('id' => $tradeorderid))->row();
    $trade_detais    = $this->common_model->getTableData('p2p_trade', array('tradeid' => $trade_id))->row();
    
    $crypto = getcurrency_name($trade_detais->cryptocurrency);
    $fiats = getfiatcurrencydetail($trade_detais->fiat_currency);	
    $fiatcurrency = $fiats->currency_symbol;
    $data['chat_details'] = array(

    	'id' => $trade_detais->id,
    	'trade_id' => $trade_detais->tradeid,
    	'price' => $trade_detais->price,
    	'minimumtrade' => $trade_detais->minimumtrade,
    	'maximumtrade' => $trade_detais->maximumtrade, 
    	'crypto_amount' => $tradeorderdetails->crypto_amount,
    	'fiat_amount' => $tradeorderdetails->fiat_amount,
    	'buyer_status' => $tradeorderdetails->tradestatus,
    	'price' => $trade_detais->price,
    	'datetime' => $trade_detais->datetime,
    	'crypto_symbol' => $crypto,
    	'fiat_symbol' => $fiatcurrency,
    	'tradestatus' => $tradeorderdetails->tradestatus,
    	'tradeorderid' => $tradeorderdetails->id,
    	'sellerid' => $tradeorderdetails->sellerid,
    	'buyerid' => $tradeorderdetails->buyerid 

    );	

	}
	echo json_encode($data);



    }	 


function p2p_chat_records()
{


 	$data = array();
	$user_id=$this->input->post('user_id');
	$tradeid = $this->input->post('trade_id');
	$tradeorderid = $this->input->post('tradeorderid');

	if($user_id=="")
	{	
		$data['msg'] =  'you are not logged in';
		$data ['status'] = '0';
	}
	else { 
	if($tradeid!='' && $tradeorderid!='')
  	{
    
    $chats=$this->common_model->getTableData('p2pchat_history',array('tradeid'=>$tradeid,'tradeorderid' => $tradeorderid,'imagetype' =>'real'))->result();
    $data['chats'] = $chats; 

    // print_r($chats);exit;

    }

 }
	echo json_encode($data);	
}



public function p2psend_message_app()
{

$data = array();
$user_id = $this->input->post('user_id');
if($user_id=="")
{	
	$data['msg'] =  'you are not logged in';
	$data ['status'] = '0';
}

else { 

 if($this->input->post())
  {
    
    $trade_id = $this->input->post('trade_id');
    $tradeorderid = $this->input->post('tradeorderid');
    $admin_status = $this->input->post('admin_status');
    $messsage = strip_tags(trim($this->input->post('chat_message')));

    if($messsage!='') {

      $image = $_FILES['image']['name']; 
      if($image!="") {
        // if(getExtension($_FILES['image']['type']))
        // {   

          $Img_Size = $_FILES['image']['size'];
          $uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$user_id);
          if(is_array($uploadimage1))
          {
            $image=$uploadimage1['secure_url'];

          }
          else
          {
            $image = 'error First';
             $data['status'] ='error';
            $data['msg'] ='Not Uploaded Please try Again!---';
          }
          $image=$image;
        // }
        // else
        // {

        //   $data['status'] ='error';
        //   $data['msg'] ='Please upload proper image format';
        //   $image = 'error';

        // }
      } 
      else 
      { 
        $image = "";
      }

    $trade_details = $this->common_model->getTableData('p2p_trade',array('tradeid'=>$trade_id))->row();
    $tradeorder_details = $this->common_model->getTableData('p2ptradeorder',array('id'=>$tradeorderid))->row();

    if($admin_status=='1')
       $user_type = 'Admin';
    else if($tradeorder_details->buyerid==$user_id)
      $user_type = 'Buyer';
    else if($tradeorder_details->sellerid==$user_id)
      $user_type = 'Seller';
    

    if($admin_status=='1')
    {
     
      $username = 'Admin'; 
    }
    else
    {
       $username = UserName($user_id);  
    }
    

    $message_data = array(

      "user_id" => $user_id,
      "username" => $username,
      "user_type" => $user_type,
      "comment" => $messsage,
      "user_id" => $user_id,
      "admin_status" => $admin_status,
      "added" => date('Y-m-d H:i:s'),
      "tradestatus" => $tradeorder_details->tradestatus,
      "tradeuser_id" => $trade_details->user_id,
      "tradeid" => $trade_id,
      "imagetype" => 'real',
      "tradeorderid" => $tradeorderid,
      "sellerid" => '0',
      "image" => $image

    );

     $send = $this->common_model->insertTableData('p2pchat_history', $message_data);
     if($send)
     {
      $data['status'] ='success';
      $data['msg'] ='Message Send Successfully';
     }
     else
     {
      $data['status'] ='error';
      $data['msg'] ='Wrong Please try Again!';
     }

    }
    else
    {
      $data['status'] ='error';
      $data['msg'] ='Please Enter Message!';

    } 
  }

}

	echo json_encode($data);

}



public function p2p_orderconfirm_app()
{
                    

$data = array();
$user_id = $this->input->post('user_id');



if($user_id=="")
{	
	$data['msg'] =  'you are not logged in';
	$data ['status'] = '0';
}

else { 
	
	$tradeuser = $user_id;
	$tradeid = $this->input->post('trade_id');
	if($tradeuser!='' && $tradeid!=''){

      $tradebuyerid = $tradeuser;
      $gettrade=  $this->common_model->getTableData('p2p_trade',array('tradeid'=>$tradeid))->row();
      $chart_det =  $this->common_model->getTableData('p2pchat_history',array('tradeid'=>$tradeid))->row();
      $get_user_id = $chart_det->tradeuser_id;
      
      $tradeordid= $tradeid;
      $sellertradeorder=$this->common_model->customQuery("select * from cpm_p2ptradeorder where tradeid='".$tradeordid."'  and buyerid='".$tradebuyerid."' order by id desc limit 1")->row();
        $confirmtrade = 1;
        $condition = array('id' => $sellertradeorder->id,'buyerid'=>$tradebuyerid); 
        $updateData=array();
        $updateData['paymentconfirm'] = $confirmtrade;
        $updateData['tradestatus'] = 'paid';
        $updateData['paidtime'] = date("Y-m-d H:i:s");
        $update = $this->common_model->updateTableData('p2ptradeorder',$condition,$updateData);
        if($update){ 
        $buyerdet = $this->common_model->getTableData('users', array('id' => $tradebuyerid))->row();
        
        $inspaidchat['added'] =  date("Y-m-d H:i:s");
        $inspaidchat['user_id'] = $buyerdet->id;
        $inspaidchat['tradeuser_id'] = $get_user_id;
        $inspaidchat['tradeid'] = $gettrade->tradeid;
        $inspaidchat['username'] = $buyerdet->cpm_username;
        $inspaidchat['tradeorderid'] = $sellertradeorder->id;
        $inspaidchat['imagetype'] = "alertmsg";
        $inspaidchat['tradestatus'] = "paid";
        $inspaidchat['comment'] = "The vendor is now verifying your payment.If you have not paid then you may be reported for coin locking and your account suspended.Once the vendor confirms payment bitcoins will be released escrow to your wallet";

        $insertdata = $this->common_model->insertTableData('p2pchat_history', $inspaidchat);

        if($insertdata)
        {
          $data['status'] = '1';
          $data['msg'] = 'Seller has confirmed your payment, they will cryptos released to your Wallet..';
        }
        else
        {
          $data['status'] = '0';
          $data['msg'] = 'Unable to submit your  request. Please try again ';
        }

      }

     }
     else
     {
         $data['status'] = '0';
         $data['msg'] = 'Unable to submit your  request. Please try again ';
     } 


	}

      echo json_encode($data);


}





   public function dispute_app(){
  

	$data = array();
	$user_id = $this->input->post('user_id');

	if($user_id=="")
	{	
		$data['msg'] =  'you are not logged in';
		$data ['status'] = '0';
	}


      if($this->input->post())
      {


      
        $traderandom = mt_rand(10000000,99999999);

        $updateadmin = array('rand_code'=>$traderandom);

        $this->common_model->updateTableData('admin',array('id'=>1),$updateadmin);  
        
        $message=$this->input->post('reason');
        $tradeid=$this->input->post('tradeid');
        $tradeorderid=$this->input->post('tradeorderid');
        // $type=$this->input->post('type');
        // $user_id = $this->input->post('user_id');
        // $username=$this->db->escape_str($this->input->post('username'));

        $gettradeorder = $this->common_model->getTableData('p2ptradeorder', array('id' => $tradeorderid))->row();

        $gettrade = $this->common_model->getTableData('p2p_trade', array('tradeid' => $tradeid))->row();




        $buyer = UserName($gettradeorder->buyerid);
        $seller = UserName($gettradeorder->sellerid);

        $username = $this->common_model->getTableData('users', array('id' => $user_id))->row('cpm_username');

        $admin='admin';
        $url=front_url() . 'p2ptrade/'.$tradeorderid.'/'.$tradeid.'/'.$admin;

        $dispute=array(
        'tradeorderid'=>$tradeorderid,
        'tradeid'=>$tradeid,
        'type'=>$gettrade->actualtradebuy,
        'link'=>$url,
        'status'=>'0',
        'message'=>$message,
        'username'=>$username

       );
		$insert = $this->common_model->insertTableData('dispute', $dispute);
     	if($insert){

        $condition=array(
            'id'=>$tradeid,
        );

        $updateData=array(
            'dispute_status'=>'1',

        );

         $enc_email = getSiteSettings('site_email');
         $adminemail = decryptIt($enc_email);
         // $adminemail = 'sriayyappan@spiegeltechnologies.com';

          $update = $this->common_model->updateTableData('p2ptradeorder',$condition,$updateData);
          $admin='admin';
          $email_templats = 'Dispute_user_request';
          $special_varsseller = array(
         '###SENDER###' => $username,  
         '###BUYER###' => $buyer,
         '###SELLER###' => $seller,
         '###TRADEID###'=> "#".$tradeid,
         '###REASON###'=> $message,
         '###LINK###' => base_url().'exchange/#/chat/'.$tradeid.'/'.$tradeorderid.'/'.$traderandom); 
          
  

          $this->email_model->sendMail($adminemail, '', '', $email_templats, $special_varsseller);
          // $this->session->set_flashdata('success', 'Please wait for admin will chat with you');
          
          $data['msg'] = 'Please wait for admin will chat with you';
          $data['status'] = '1';  


         }else{
          
          $data['msg'] = 'Invalid Try Again1';
          $data['status'] = '0';


         }
      }
      else
      {
         $data['msg'] = 'Invalid Try Again-- Post';
         $data['status'] = '0';
      } 

      echo json_encode($data);            


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



	function withdraw_assets()
	{


		$data = array();
		$user_id = $this->input->post('user_id');
		if(!empty($user_id) && $user_id >0)
		{

			$cur = $this->input->post('currency_id');
			
		if($cur=='')
		{
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('id','ASC'))->row();
		}
		else
		{
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('id'=>$cur),'','','','','','',array('id','ASC'))->row();
		}

		$cur_id = $data['sel_currency']->id;
		$data['currency_symbol'] = $data['sel_currency']->currency_symbol;
		$balance_in_usd = (string)to_decimal(Overall_USD_Balance($user_id),2);
		$data['user_balance'] = (string)getBalance($user_id,$cur_id); 

		$usd_balance = $data['user_balance'] * $data['sel_currency']->online_usdprice;

		$data['balance_usd'] = (string)$usd_balance;

			$j=0;$rude1=array();
			$deposit_history = $this->common_model->getTableData('currency')->result();
			foreach($deposit_history as $deposit){

				 

                        

				$deposit_history_list[$j]=array("currency_name"=>$deposit->currency_name,
												"currency_symbol"=>$deposit->currency_symbol,
												"withdraw_fees_type"=>$deposit->withdraw_fees_type,
												"fees"=>$deposit->withdraw_fees
												);

				array_push($rude1, $deposit_history_list[$j]);
				$j++;
			}

			// $data['assets'] = $rude1;
		}
		else
		{
			 $data['msg'] =  'you are not logged in';
			$data ['status'] = '0';
		}


		echo json_encode($data);
	}



	function user_balance_app()
	{
		$data = array();
		$user_id = $this->input->post('user_id');
		if($user_id=="")
		{
				 $data['msg'] =  'you are not logged in';
			     $data ['status'] = '0';

		}else
		{
				$cur = $this->input->post('currency_id');

		$sel_currency = $this->common_model->getTableData('currency',array('id'=>$cur),'','','','','','',array('id','ASC'))->row();

			$cur_id = $sel_currency->id;
			$data['currency_symbol'] = $sel_currency->currency_symbol;
		// $data['balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);
		$data['user_balance'] = (string)getBalance($user_id,$cur_id); 
		}
		echo json_encode($data);
	}


	function user_bank_list()
	{
		$data = array();
		$user_id = $this->input->post('user_id');

		if(!empty($user_id) && $user_id >0)
		{

			$cur_id = $this->db->escape_str($this->input->post('currency'));

			$user_bankdetails = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'currency'=>$cur_id))->row();

			if($user_bankdetails->status=='Verified')
				$ban_status = '1';
			else if($user_bankdetails->status=='Rejected')
				$ban_status = '0';
			else
				$ban_status = '0';

			
				$data['details'] = array(

					'bank_account_number'=>($user_bankdetails->bank_account_number!='' ? $user_bankdetails->bank_account_number : ''),
					'bank_account_name'=>($user_bankdetails->bank_account_name!='' ? $user_bankdetails->bank_account_name : ''),
					'bank_swift'=>($user_bankdetails->bank_swift!='' ? $user_bankdetails->bank_swift : ''),
					'bank_name'=>($user_bankdetails->bank_name!='' ? $user_bankdetails->bank_name : ''),
					'bank_address'=>($user_bankdetails->bank_address!='' ? $user_bankdetails->bank_address : ''),
					'bank_postalcode'=>($user_bankdetails->bank_postalcode!='' ? $user_bankdetails->bank_postalcode : ''),
					'bank_city'=>($user_bankdetails->bank_city!='' ? $user_bankdetails->bank_city : ''),
					'status'=>($user_bankdetails->status!='' ? $user_bankdetails->status : ''),
				'bank_country'=>($user_bankdetails->bank_country!='' ? $user_bankdetails->bank_country : ''),

					// 'country_name'=>getCountryName($user_bankdetails->bank_country),

				// 'country_name'=>getCountryName($user_bankdetails->bank_country)!='' ? getCountryName($user_bankdetails->bank_country : []),


					'user_status'=>($user_bankdetails->user_status!='' ? $user_bankdetails->user_status : ''),
					'added_date'=>($user_bankdetails->added_date!='' ? $user_bankdetails->added_date : ''),
					'bank_status' => $ban_status




				);
			// }
			// else
			// {
			// 	$data['details'] = array(
			// 		'bank_account_number'=>($user_bankdetails->bank_account_name!='' ? $user_bankdetails->bank_account_name : [])
			// 	);
			// }


		}
		else
		{
			$data['status'] = '0';
			$data['msg'] = 'You are not Logged in';
		}


		echo json_encode($data);

	}



	function crypto_fiat_withdraw(){

		$user_id=$this->input->post('user_id');
		if($user_id=="")
		{	
			  $data['msg'] =  'you are not logged in';
			$data['status'] = '0';
		}  

		$id = $this->input->post('currency_id');

		// $data['user'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		// $data['site_common'] = site_common();	
		// $data['currency'] = $this->common_model->getTableData('currency',array('status'=>1,'withdraw_status'=>1),'','','','','','',array('id','ASC'))->result();	 
		// $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();

		// $data['defaultfiat'] = $this->common_model->getTableData('currency',array('status' => 1,'type'=>'fiat'),'','','','','','',array('id','ASC'))->row();

		// print_r($data[])

		// $data['defaultbank'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id,'currency'=>$data['defaultfiat']->id),'','','','','','',array('id','ASC'))->row();

		if(isset($id) && !empty($id)){
			$sel_currency = $this->common_model->getTableData('currency',array('id'=>$id),'','','','','','',array('id','ASC'))->row();

				if($sel_currency->withdraw_status==0)
				{
					$data['msg'] ='Withdraw Disabled Please Contact admin';
						$data['status'] = '0';
				} 


			$data['selcsym'] = $id;

			$data['fees_type'] = $sel_currency->withdraw_fees_type;
			$data['fees'] = $sel_currency->withdraw_fees;
			$data['currency'] = $sel_currency->currency_symbol;
		}
		else{
			$sel_currency = $this->common_model->getTableData('currency',array('status' => 1),'','','','','','',array('id','ASC'))->row();
			$data['selcsym'] = $sel_currency->currency_symbol;


			
			$data['fees_type'] = $sel_currency->withdraw_fees_type;
			$data['fees'] = $sel_currency->withdraw_fees;
		}

		$cur_id = $sel_currency->id;
		// $data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details')->row(); 
		// $data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();

		$data['balance_in_usd'] = to_decimal(Overall_USD_Balance($user_id),2);

		$data['user_balance'] = getBalance($user_id,$cur_id);

		
		if(isset($_POST))
	    {


	    	$id = $this->input->post('currency_id');
			// $this->form_validation->set_rules('ids', 'ids', 'trim|required|xss_clean|numeric');
			// $this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
			// $passinp = $this->db->escape_str($this->input->post('ids'));
			// $myval = explode('_',$passinp);
			// $id = 
			// $name = $myval[1];

		    
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


      //           if($amount > $balance)
						// { 
						// 	   $data['msg'] ='Testing Amount ---> '.$amount.' ---> Balance '.$balance;
						// 		$data['status'] = '0';
						// 		echo json_encode($data);
						// 		exit();
						// } 

             



    //             print_r($currency);
    //             echo "<br>";
    //             echo $amount;
				// exit();

				if($id > 0)
				{	
					if($count>0)
					{							
						$data['msg'] ='Sorry!!! Your previous withdrawal is waiting for admin approval. Please use other wallet or be patience';
						$data['status'] = '0';	
					}
					else
					{
						if($amount > $balance)
						{ 
							$data['msg'] ='Amount you have entered is more than your current balance';
							$data['status'] = '0';
						}
						elseif($amount < $currency->min_withdraw_limit)
						{
							$data['msg'] ='Amount you have entered is less than minimum withdrawl limit';
							$data['status'] = '0';
						}
						elseif($amount>$currency->max_withdraw_limit)
						{
							$data['msg'] ='Amount you have entered is more than maximum withdrawl limit';
							$data['status'] = '0';
						}
						elseif($fiat_bank_det->status!='Verified')
						{
							$data['msg'] ='Your Bank Account Not Verified.';
							$data['status'] = '0';
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
								$data['msg'] ='Your withdraw request placed successfully. Please make confirm from the mail you received in your registered mail!';
								$data['status'] = '1';
							} 
							else 
							{
								$data['msg'] ='Unable to submit your withdraw request. Please try again';
								$data['status'] = '0';
							}
						}
					}
				}
				else
				{
					$data['msg'] ='Incorrect Values!'; 
					$data['status'] = '0';
				}	
	    }




		$data['user_id'] = $user_id;
		$data['selcur_id'] = $sel_currency->id;
		// $data['currency_balance'] = getBalance($user_id,$data['selcur_id']);
		// $data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));
		// $data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'wallet'))->row();
		// $data['withdraw_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();
		echo json_encode($data);

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


function countries_app(){
		// echo('arg1');
		$data = array();
		$rude = array();

	$ip_address = $this->input->get('ip_address');
	$details =  json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip_address));
	// $country_row = $this->common_model->getTableData('countries',array('country_name'=>$details->geoplugin_countryName))->row();
	// $country = $country_row->id;
	if($ip_address!='' && $details->geoplugin_countryName!='')
	{
		$condi = array('country_name'=>$details->geoplugin_countryName);
	}
	else
	{
		$condi = '';
	} 



		$countries = $this->common_model->getTableData('countries',$condi)->result();
		if(isset($countries)){
			$data['status'] = '1';
			$i=0;
			foreach($countries as $result){


				$countries_out[$i] = array('id'=>$result->id , 'name'=>$result->country_name);

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




// Wallet 

function wallet_app()
{

$user_id=$this->input->post('user_id');
$data = array();

    $get_users = $this->common_model->getTableData('users', array('id' => $user_id), '', '', '', '', '', '', '')->result();        
    if(!isset($user_id) && empty($user_id))
    {    
    
        $data['status'] = '0';
        $data['msg'] = "You are not Logged in";
        
    }
    else
    {
    $currency = $this->common_model->getTableData('currency', array('status' => 1), '', '', '', '', '', '', array('sort_order', 'ASC'))->result();
    $rude = array();$i=0;
    $data['overall_balance_in_usd'] = (string)to_decimal(Overall_USD_Balance($user_id),2);
    
    foreach($currency as $Currency_list){


        $Balance = getBalance($user_id,$Currency_list->id);
        $USD_balance = $Currency_list->online_usdprice * $Balance;


// print_r($USD_balance);exit;
    

        $Currency_list_Val[$i] = array(    "currency_id"=>$Currency_list->id,
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




}