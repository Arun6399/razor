<?php
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
		$data['action'] = front_url() . 'login_user';		
		$this->load->view('front/user/login', $data);
	}
	public function login_check()
    {
         

              $ip_address = get_client_ip();

        $array = array('status' => 0, 'msg' => '');

        $this->form_validation->set_rules('login_detail', 'Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('login_password', 'Password', 'trim|required|xss_clean');  

        if ($this->input->post()) {

            if ($this->form_validation->run()) {

                $email = lcfirst($this->input->post('login_detail'));
                $password = $this->input->post('login_password');

                $prefix = get_prefix();
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $check = checkSplitEmail($email, $password);               

                }
                   
                  
                if (!$check) {
                    $array['msg'] = 'Enter Valid Login Details';
                } else {
                    if ($check->verified != 1) {
                            
                        $array['msg'] = 'Please check your email to activate smdex PRIME account';

                    } else {

                        $array['status'] = 1;

                        if ($check->randcode == 'enable' && !empty($check->secret)) {                   

                            $array['tfa_status'] = 1;
                            $login_tfa = $this->input->post('login_tfa');

                            $check1 = $this->checktfa($check->id, $login_tfa); 

                            if ($check1) {
                              

                                $session_data = array(
                                    'user_id' => $check->id,
                                    'uid' => $check->id,
                                    'login' => 'true',
                                );

                                $this->session->set_userdata($session_data);
                                $this->common_model->last_activity('Login', $check->id, "", $ip_address);
                                $this->session->set_flashdata('success', 'Welcome back . Logged in Successfully');
                                $array['msg'] = 'Welcome back . Logged in Successfully';
                                                              
                                if ($check->verify_level2_status == 'Completed') {
                                
                                    $array['login_url'] = 'account';
                                }
                                $array['tfa_status'] = 0;
                                
                            } else {
                                                       
                                //$array['msg'] = $this->lang->line('Enter Valid TFA Code');
                             $array['msg'] = 'Enter Valid TFA Code';
                            }
                        } else {
                           
                            $session_data = array(
                                'user_id' => $check->id,
                                'login' => 'true',
                            );
                            $this->session->set_userdata($session_data);
                            $this->common_model->last_activity('Login', $check->id, "", $ip_address);
                            $array['tfa_status'] = 0;                          
                            


                               $this->session->set_flashdata('success', 'Welcome back . Logged in Successfully');
                            $array['msg'] = 'Welcome back . Logged in Successfully';

                                 $array['login_url'] = 'account';

                                          

                        } 

                        /*if($this->input->post('remember_me')==1)
                        {
                        $this->session->set_userdata('remember_me', 1);
                        $sess_data = array(
                            'login_detail' => $email,
                            'login_password' => $password,
                        );
                        $this->session->set_userdata('logged_in_user', $sess_data);
                        }
                        else
                        {
                          $this->session->unset_userdata('remember_me');
                          $this->session->unset_userdata('logged_in_user');
                        } */

                    }
                }
            } else {
                $array['msg'] = validation_errors();
            }
        } else {
            $array['msg'] = 'Login error';
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
		$array=array('status'=>0,'msg'=>'');
		$this->form_validation->set_rules('forgot_detail', 'Email', 'trim|required|xss_clean');
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
							$this->session->set_flashdata('success',$this->lang->line('Password reset successfully Please Login'));
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
	function signup()
	{	
     
      $data['site_common'] = site_common();
		$static_content  = $this->common_model->getTableData('static_content',array('english_page'=>'home'))->result();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login'))->row();
           
           // When Post		
		if(!empty($_POST))
		{ 

            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
            $this->form_validation->set_rules('register_email', 'Email', 'trim|required|valid_email|xss_clean');
			$this->form_validation->set_rules('register_password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('register_cpassword', 'Password', 'trim|required|xss_clean');

			if ($this->form_validation->run())
			{ 

			    $username = $this->db->escape_str(($this->input->post('username')));
				$email = $this->db->escape_str(lcfirst($this->input->post('register_email')));
				$password = $this->db->escape_str($this->input->post('register_password'));
				//$uname = $this->db->escape_str($this->input->post('register_uname'));
				$cpassword = $this->db->escape_str($this->input->post('register_cpassword'));
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

					$Exp = explode('@', $email);
					$User_name = $Exp[0];

					$activation_code = time().rand(); 
					$str=splitEmail($email);
					$ip_address = get_client_ip();

					$user_data = array(
					'usertype' => '1',
					$prefix.'email'    => $str[1],
					'country' => $country,
					$prefix.'username'	=> $this->input->post('username'),
					$prefix.'password' => encryptIt($password),
					$prefix.'cpassword' => encryptIt($cpassword),
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
					$fb_link = $site_common['site_settings']->facebooklink;
                    $tw_link = $site_common['site_settings']->twitterlink;               
                    $md_link = $site_common['site_settings']->youtube_link;
                    $ld_link = $site_common['site_settings']->linkedin_link;

					$special_vars = array(
					'###USERNAME###' => $username,
					'###LINK###' => front_url().'verify_user/'.$activation_code,
					'###FB###' => $fb_link,
                    '###TW###' => $tw_link,                   
                    '###LD###' => $ld_link,
                    '###MD###' => $md_link

					);


					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
					$this->session->set_flashdata('success',$this->lang->line('Thank you for Signing up. Please check your e-mail and click on the verification link.'));
					front_redirect('login', 'refresh');
				}
			}
			else
			{

				$this->session->set_flashdata('error', validation_errors());
				front_redirect('signup', 'refresh');
			}	
		
		}

		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['site_common'] = site_common();
		$data['action'] = front_url() . 'signup';
			
		$this->load->view('front/user/register', $data);

    	}
	function oldpassword_exist()
	{
		$oldpass = $this->db->escape_str($this->input->post('currentpassword'));
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
		$this->session->unset_userdata('login');
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
		if($user_id!="")
		{	
			front_redirect('', 'refresh');
		}
		$activation=$this->common_model->getTableData('users',array('activation_code'=>urldecode($activation_code)));
		if ($activation->num_rows()>0)
		{
			$detail=$activation->row();
			if($detail->verified==1)
			{
				$this->session->set_flashdata('error', $this->lang->line('Your Email is already verified.'));
				front_redirect('login', 'refresh');
			}
			else
			{
				$this->common_model->updateTableData('users',array('id'=>$detail->id),array('verified'=>1));
				$this->common_model->last_activity('Email Verification',$detail->id);
				$this->session->set_flashdata('success', $this->lang->line('Your Email is verified now.'));
				front_redirect('login', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error', $this->lang->line('Activation link is not valid'));
			front_redirect('login', 'refresh');
		}
	}
	function profile()
	{		 
		// $this->load->library('session');
		$this->load->library('session','form_validation');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'profile'))->row();	
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['users_bank'] = $this->common_model->getTableData('user_bank_details',array('user_id'=>$user_id))->row();
		$data['users_history'] = $this->common_model->getTableData('user_activity',array('user_id'=>$user_id))->result();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['currency'] = $this->common_model->getTableData('currency')->result();
		$data['site_common'] = site_common();


		if(isset($_POST['change_pwd']))
		{
            

			$prefix = get_prefix();
			$oldpassword = encryptIt($this->input->post("currentpassword"));
			$newpassword = encryptIt($this->input->post("password"));
			$confirmpassword = encryptIt($this->input->post("cpassword"));
			
			// Check old pass is correct/not
			$password = $prefix.'password';
			if($oldpassword == $data['users']->$password)
			{
				//check new pass is equal to confirm pass
				if($newpassword == $confirmpassword)
				{
					//$this->db->where('id',$user_id);
					$data=array($prefix.'password'  => $newpassword);
					$this->common_model->updateTableData('users',array('id'=>$user_id),$data);
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
		if($this->input->post('bank_details'))
		{
			// print_r($this->input->post());exit();
			$this->form_validation->set_rules('account_number', 'Bank Account number', 'required|xss_clean');
			$this->form_validation->set_rules('account_name', 'Bank Account Name', 'required|xss_clean');
			$this->form_validation->set_rules('ifsc', 'IFSC Code', 'required|xss_clean');
			$this->form_validation->set_rules('bank_branch', 'Bank Branch Location', 'required|xss_clean');
			$this->form_validation->set_rules('bank_name', 'Bank Name', 'required|xss_clean');
			$this->form_validation->set_rules('account_type', 'Account Type', 'required|xss_clean'); 
			
			if($this->form_validation->run())
			{
				// print_r($this->input->post());exit();
				$insertData['user_id'] = $user_id;
				//$insertData['currency'] = $this->db->escape_str($this->input->post('currency'));
				$insertData['bank_account_name'] = $this->db->escape_str($this->input->post('account_name'));
				$insertData['bank_account_number'] = $this->db->escape_str($this->input->post('account_number'));
				$insertData['bank_swift'] = $this->db->escape_str($this->input->post('ifsc'));
				$insertData['bank_name'] = $this->db->escape_str($this->input->post('bank_name'));
				$insertData['bank_address'] = $this->db->escape_str($this->input->post('bank_branch'));
				$insertData['account_type'] = $this->db->escape_str($this->input->post('account_type'));
				//$insertData['bank_city'] = $this->db->escape_str($this->input->post('bank_city'));
				//$insertData['bank_country'] = $this->db->escape_str($this->input->post('bank_country'));
				//$insertData['bank_postalcode'] = $this->db->escape_str($this->input->post('bank_postalcode'));
				$insertData['added_date'] = date("Y-m-d H:i:s");				
				$insertData['status'] = 'Pending';
				$insertData['user_status'] = '1';
				if ($_FILES['bank_statement']['name']!="") 
				{
					$imagepro = $_FILES['bank_statement']['name'];
					if($imagepro!="" && getExtension($_FILES['bank_statement']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["bank_statement"],'uploads/user/'.$user_id,$this->input->post('bank_statement'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['bank_statement']=$imagepro;
				}
				$insertData_clean = $this->security->xss_clean($insertData);
				

				// $insert=$this->common_model->insertTableData('user_bank_details', $insertData_clean);
				// if ($insert) {
				// 	//$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));
				// 	$this->session->set_flashdata('success', 'Bank details Updated Successfully');

				// 	front_redirect('profile', 'refresh');
				// } else {
				// 	$this->session->set_flashdata('error', 'Something ther is a Problem .Please try again later');
				// 	front_redirect('profile', 'refresh');
				// }
				$check = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id))->row();

            if(count($check)>0)
            {
            
                $update = $this->common_model->updateTableData('user_bank_details',array('user_id'=>$user_id),$insertData_clean);
                if($update)
                {
                $this->session->set_flashdata('success','Your bank details updated successfully');
                front_redirect('profile','refresh');
                }

            }
            else
            {
                // $Data['user_id'] = $user_id;
                $insert = $this->common_model->insertTableData('user_bank_details',$insertData_clean);
                if($insert)
                {
                $this->session->set_flashdata('success','Your bank details added successfully');
                front_redirect('profile', 'refresh');
                }
            }
			}
			else
			{
				$this->session->set_flashdata('error', validation_errors());

				$this->session->set_flashdata('error','Some datas are missing');
				front_redirect('profile', 'refresh');
			}
		}	

		$this->load->view('front/user/profile', $data); 
	}
	function login_history()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}		
		$data['users_history'] = $this->common_model->getTableData('user_activity',array('user_id'=>$user_id))->result(); 
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'login_history'))->row();
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['site_common'] = site_common();
		$this->load->view('front/user/profile', $data); 
	}
	/*function authentication()
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
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'2fa'))->row();
		$this->load->view('front/user/2fa', $data); 
	} */
	function two_factor()
	{
  
        
    $user_id=$this->session->userdata('user_id');

    if($user_id=="")
    { 
      $this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
      redirect(base_url().'');
    }

    $this->load->library('Googleauthenticator');
    $data['site_common'] = site_common();
    $data['meta_content'] = $this->common_model->getTableData('meta_content', array('link'=>'settings'))->row();
    $data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
    //$data['user_bank'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id))->row();
    // print_r($data['user_bank']);die;

    if($data['users']->randcode=="enable" || $data['users']->secret!="")
    { 
      $secret = $data['users']->secret; 
      $data['secret'] = $secret;
      $ga     = new Googleauthenticator();
      $data['url'] = $ga->getQRCodeGoogleUrl('smdex', $secret);

    }
    else
    {
      $ga = new Googleauthenticator();
      $data['secret'] = $ga->createSecret();
      $data['url'] = $ga->getQRCodeGoogleUrl('smdex', $data['secret']);
      $data['oneCode'] = $ga->getCode($data['secret']);
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
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          front_redirect('two_factor', 'refresh');
        }
        else
        {
       
          $this->session->set_flashdata('error','Please Enter correct code to enable TFA');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          front_redirect('two_factor', 'refresh');
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
          front_redirect('two_factor', 'refresh');
        }
        else
        {

          
          $this->session->set_flashdata('error','Please Enter correct code to disable TFA');
          //front_redirect('Front/two_factor_authentication?page=tfa', 'refresh');
          front_redirect('two_factor', 'refresh');
        }
      }
    }
    //$data['site_common'] = site_common();
    //$data['countries'] = $this->common_model->getTableData('countries')->result();
    //$data['currencies'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1))->result();


       $this->load->view('front/user/2fa',$data);

	

	}
	function bank_details()
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
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'bank-details'))->row();
		$this->load->view('front/user/bank-details', $data); 
	}
	function change_password()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', $this->lang->line('you are not logged in'));
			redirect(base_url().'home');
		}
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'change_password'))->row();
		if(isset($_POST['change_pwd']))
		{
			$prefix = get_prefix();
			$oldpassword = encryptIt($this->input->post("currentpassword"));
			$newpassword = encryptIt($this->input->post("password"));
			$confirmpassword = encryptIt($this->input->post("cpassword"));
			
			// Check old pass is correct/not
			$password = $prefix.'password';
			if($oldpassword == $data['users']->$password)
			{
				//check new pass is equal to confirm pass
				if($newpassword == $confirmpassword)
				{
					//$this->db->where('id',$user_id);
					$data=array($prefix.'password'  => $newpassword);
					$this->common_model->updateTableData('users',array('id'=>$user_id),$data);
					$this->session->set_flashdata('success',$this->lang->line('Password changed successfully'));
					front_redirect('change_password', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('error',$this->lang->line('Confirm password must be same as new password'));
					front_redirect('change_password', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error',$this->lang->line('Your old password is wrong'));
				front_redirect('change_password', 'refresh');
			}			
		}		
		$data['site_common'] = site_common();
		$this->load->view('front/user/change_password', $data); 
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
				$insertData['bidex_fname'] = $this->db->escape_str($this->input->post('firstname'));
				$insertData['bidex_lname'] = $this->db->escape_str($this->input->post('lastname'));
				$insertData['currency'] = $this->db->escape_str($this->input->post('currency'));
				$insertData['reference_currency'] = $this->db->escape_str($this->input->post('ref_currency'));
				$insertData['street_address'] = $this->db->escape_str($this->input->post('address'));
				//$insertData['city'] = $this->db->escape_str($this->input->post('city'));
				//$insertData['state'] = $this->db->escape_str($this->input->post('state'));
				//$insertData['postal_code'] = $this->db->escape_str($this->input->post('postal_code'));

				$paypal_email = $this->input->post('paypal_email');
				if(isset($paypal_email) && !empty($paypal_email)){
				$insertData['paypal_email'] = $this->db->escape_str($paypal_email);
			}				
				$insertData['verification_level'] = '2';
				$insertData['verify_level2_date'] = gmdate(time());
				$insertData['country']	 	   = $this->db->escape_str($this->input->post('country'));
				//$insertData['bidex_phone']	= $this->db->escape_str($this->input->post('phone'));
				$condition = array('id' => $user_id);
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);

				if ($_FILES['profile']['name']!="") 
				{
					$imagepro = $_FILES['profile']['name'];
					if($imagepro!="" && getExtension($_FILES['profile']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["profile"],'uploads/user/'.$user_id,$this->input->post('profile'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['profile_picture']=$imagepro;
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
				$imagepro = $_FILES['profile']['name'];
				if($imagepro!="" && getExtension($_FILES['profile']['type']))
				{
					$uploadimage1=cdn_file_upload($_FILES["profile"],'uploads/user/'.$user_id,$this->input->post('profile'));
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
    function kyc_verification()
	{		 
		$this->load->library('session');
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			$this->session->set_flashdata('success', 'you are not logged in');
			redirect(base_url().'home');
		}
		if($_POST)
		{
			$this->form_validation->set_rules('proof_number', 'Proof Number', 'required|xss_clean');
			$this->form_validation->set_rules('pan_number', 'Pan Number', 'required|xss_clean');
			if($this->form_validation->run())
			{
				$insertData['id_type'] = $this->db->escape_str($this->input->post('id_type'));
				$insertData['proof_number'] = $this->db->escape_str($this->input->post('proof_number'));
				$insertData['pan_number'] = $this->db->escape_str($this->input->post('pan_number'));
				$insertData['bidex_phone'] = $this->db->escape_str($this->input->post('mobile'));
				//$insertData['reference_currency'] = $this->db->escape_str($this->input->post('ref_currency'));
				//$insertData['street_address'] = $this->db->escape_str($this->input->post('address'));
				//$insertData['city'] = $this->db->escape_str($this->input->post('city'));
				//$insertData['state'] = $this->db->escape_str($this->input->post('state'));
				//$insertData['postal_code'] = $this->db->escape_str($this->input->post('postal_code'));

				//$paypal_email = $this->input->post('paypal_email');
				//if(isset($paypal_email) && !empty($paypal_email)){
				//$insertData['paypal_email'] = $this->db->escape_str($paypal_email);
			//}				
				//$insertData['verification_level'] = '2';
				//$insertData['verify_level2_date'] = gmdate(time());
				//$insertData['country']	 	   = $this->db->escape_str($this->input->post('country'));
				$insertData['bidex_phone']	= $this->db->escape_str($this->input->post('phone'));
				$condition = array('id' => $user_id);
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);

				if ($_FILES['photo_id_1']['name']!="") 
				{
					$imagepro = $_FILES['photo_id_1']['name'];
					if($imagepro!="" && getExtension($_FILES['photo_id_1']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->input->post('photo_id_1'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['photo_id_1']=$imagepro;
				}
				if ($_FILES['photo_id_2']['name']!="") 
				{
					$imagepro1 = $_FILES['photo_id_2']['name'];
					if($imagepro1!="" && getExtension($_FILES['photo_id_2']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->input->post('photo_id_2'));
						if($uploadimage1)
						{
							$imagepro1=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['photo_id_2']=$imagepro1;
				}
				if ($_FILES['photo_id_3']['name']!="") 
				{
					$imagepro2 = $_FILES['photo_id_3']['name'];
					if($imagepro2!="" && getExtension($_FILES['photo_id_3']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->input->post('photo_id_3'));
						if($uploadimage1)
						{
							$imagepro2=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['photo_id_3']=$imagepro2;
				}
				if ($_FILES['photo_id_4']['name']!="") 
				{
					$imagepro3 = $_FILES['photo_id_4']['name'];
					if($imagepro3!="" && getExtension($_FILES['photo_id_4']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["photo_id_4"],'uploads/user/'.$user_id,$this->input->post('photo_id_4'));
						if($uploadimage1)
						{
							$imagepro3=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['photo_id_4']=$imagepro3;
				}
				$insertData_clean = $this->security->xss_clean($insertData);
				$insert = $this->common_model->updateTableData('users',$condition, $insertData_clean);
				if ($insert) {
					$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('kyc_status'=>1));
					$this->session->set_flashdata('success', 'Your details have been sent to our team for verification');
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
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		$data['site_common'] = site_common();
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'kyc_verification'))->row();		
		$this->load->view('front/user/profile', $data); 
	}
	function address_verification()	{
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
						$uploadimage=cdn_file_upload($_FILES["photo_id_1"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_1')));
						if($uploadimage)
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error','Problem with your scan of photo id');
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
						$this->session->set_flashdata('success','Your details have been sent to our team for verification');
						front_redirect('settings', 'refresh');
					} 
	                elseif($insert !='' && $_FILES["photo_id_1"]['name'] =='') {
						$this->session->set_flashdata('success', 'Your Address proof cancelled successfully');
						front_redirect('settings', 'refresh');
					}
					else {
						$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
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
						$uploadimage=cdn_file_upload($_FILES["photo_id_2"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_2')));
						if($uploadimage)
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error','Problem with your scan of photo id');
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
						$this->session->set_flashdata('success','Your details have been sent to our team for verification');
						front_redirect('settings', 'refresh');
					} 
	                elseif($insert !='' && $_FILES["photo_id_2"]['name'] =='') {
						$this->session->set_flashdata('success', 'Your ID proof cancelled successfully');
						front_redirect('settings', 'refresh');
					}
					else {
						$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
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
						$uploadimage=cdn_file_upload($_FILES["photo_id_3"],'uploads/user/'.$user_id,$this->db->escape_str($this->input->post('photo_id_3')));
						if($uploadimage)
						{
							$image=$uploadimage['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with your scan of photo id');
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
						$this->session->set_flashdata('success','Your details have been sent to our team for verification');
						front_redirect('settings', 'refresh');
					} 
	                elseif($insert !='' && $_FILES["photo_id_3"]['name'] =='') {
						$this->session->set_flashdata('success', 'Your Photo cancelled successfully');
						front_redirect('settings', 'refresh');
					}
					else {
						$this->session->set_flashdata('error','Unable to send your details to our team for verification. Please try again later!');
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
		$data['user_bank'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$user_id))->row();
		if($data['users']->randcode=="enable" || $data['users']->secret!="")
		{	
			$secret = $data['users']->secret; 
			$data['secret'] = $secret;
        	$ga     = new Googleauthenticator();
			$data['url'] = $ga->getQRCodeGoogleUrl('smdex', $secret);
		}
		else
		{
			$ga = new Googleauthenticator();
			$data['secret'] = $ga->createSecret();
			$data['url'] = $ga->getQRCodeGoogleUrl('smdex', $data['secret']);
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
					$uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$user_id);
					if($uploadimage1)
					{
						$image=$uploadimage1['secure_url'];
					}
					else
					{
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
			$insertData['subject'] = $this->input->post('subject');
			$insertData['message'] = $this->input->post('message');
			$insertData['category'] = $this->input->post('category');
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
		if($currency_id==8){
			$data['destination_tag'] = '';
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
    	$Fetch_coin_list = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>'1'))->result(); 

		foreach($Fetch_coin_list as $coin_address)
		{
    		$userdetails = $this->common_model->getTableData('crypto_address',array($coin_address->currency_symbol.'_status'=>'0'),'','','','','','',array('id','DESC'))->result();

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
						else
						{
							
							
								$parameter = 'getaccountaddress';

							$Get_First_address1 = $this->local_model->access_wallet($coin_address->id,$parameter,getUserEmail($user_details->user_id));



							if(!empty($Get_First_address1) || $Get_First_address1!=0){
								
								$Get_First_address = $Get_First_address1;
								
								updateAddress($user_details->user_id,$coin_address->id,$Get_First_address);
								echo $coin_address->currency_symbol.' Success1 <br/>';
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
		            	$eth_id = $this->common_model->getTableData('currency',array('currency_symbol'=>'ETH'))->row('id');
						$eth_address = getAddress($user_details->user_id,$eth_id);
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

		//print_r($users); exit;

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
		$coin_name1 = $coin_name;
		$curr_id = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('id');
		$user_trans_res   = $this->get_user_with_dep_det($curr_id);
		$address_list     = $user_trans_res['address_list'];
		$transactionIds   = $user_trans_res['transactionIds'];
		$tot_transactions = array();

		$valid_server = $this->local_model->get_valid_server();
		$coin_type = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('coin_type');
		$coinDetails = $this->common_model->getTableData('currency',array('currency_name'=>$coin_name))->row('move_process_admin');		
		if($valid_server)
		{
			if($coin_type=="coin") // COIN PROCESS
			{ 
				switch ($coin_name) 
				{
					case 'Bitcoin':
						$transactions   = $this->local_model->get_transactions('Bitcoin');
						break;
					case 'COCO':
						$transactions   = $this->local_model->get_transactions('COCO');
						break;	
					case 'Litecoin':
						$transactions   = $this->local_model->get_transactions('Litecoin');
						break;
					case 'Ripple':
						$transactions   = $this->local_model->get_transactions('Ripple');
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
			 
			echo "<pre>mm"; print_r($transactions); echo "</pre>";

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
					$a++;
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
					$from_address = $row['from'];
					$admin_address = getadminAddress(1,$curr_symbol);

					$counts_tx = $this->db->query('select * from bidex_transactions where information="'.$row['blockhash'].'" and wallet_txid="'.$txid.'"')->num_rows();

					if( $category == 'receive' && $confirmations > 0 && $counts_tx == 0)
					{	

						if(isset($address_list[$address]))
						{
							$user_id   = $address_list[$address]['user_id'];
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

						if($from_address != $admin_address)
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
									'description'		=> $coin_name." Payment",
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
	    $coinname = str_replace("%20"," ",$coinname);
	    $currency_det =   $this->db->query("select * from bidex_currency where currency_name = '".$coinname."' ")->row(); // get currency detail
	    $currency_status = $currency_det->currency_symbol.'_status';
	    $address_list   =  $this->db->query("select * from bidex_crypto_address where ".$currency_status." = 1")->result(); // get user addresses
	    $fetch          =  $this->db->query("select * from bidex_admin_wallet where id='1'")->row(); // get admin wallet
	    $get_addr       =  json_decode($fetch->addresses,true);
	    $toaddress      =  $get_addr[$currency_det->currency_symbol]; // get admin address

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

	                if($amount>0) // check transfer amount with min withdraw limit and to be valid
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
	                        $GasLimit = 30000;
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
		                	$eth_balance = $this->local_model->wallet_balance("ethereum",$from_address); // get balance from blockchain
		                	//$eth_balance = getBalance($value->user_id,3); // get balance from db
		                	if($eth_balance >= "0.001")
		                	{
                                $send_money_res = $this->local_model->make_transfer($coinname,$trans_det); // transfer to admin
		                		//$send_money_res = "test";
		                	}
		                	else
		                	{
                                $eth_amount = 0.001;
                                $GasLimit1 = 21000;
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
                                $send_money_res1 = $this->local_model->make_transfer("ethereum",$eth_trans); 
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
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
		}
		$id = base64_decode($id);
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if($isValid > 0)
		{
			if($withdraw->user_status=='Completed')
			{
				$this->session->set_flashdata('error','Your withdraw request already confirmed');
				front_redirect('account', 'refresh');
			}
			else if($withdraw->user_status=='Cancelled')
			{
				$this->session->set_flashdata('error','Your withdraw request already cancelled');
				front_redirect('account', 'refresh');
			}
			elseif($withdraw->user_id != $user_id)
			{
				$this->session->set_flashdata('error','Your are not the owner of this withdraw request');
				front_redirect('account', 'refresh');
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
				front_redirect('account', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error','Invalid withdraw confirmation');
			front_redirect('account', 'refresh');
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
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending','currency_type'=>'crypto'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if($isValid > 0)
		{
			if($withdraw->user_status=='Completed')
			{
				$this->session->set_flashdata('error','Your withdraw request already confirmed');
				front_redirect('account', 'refresh');
			}
			else if($withdraw->user_status=='Cancelled')
			{
				$this->session->set_flashdata('error','Your withdraw request already cancelled');
				front_redirect('account', 'refresh');
			}
			elseif($withdraw->user_id != $user_id)
			{
				$this->session->set_flashdata('error','Your are not the owner of this withdraw request');
				front_redirect('account', 'refresh');
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
				front_redirect('account', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error','Invalid withdraw confirmation');
			front_redirect('account', 'refresh');
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
								front_redirect('account', 'refresh');
							} 
							else 
							{
								$this->session->set_flashdata('error',$this->lang->line('Unable to submit your withdraw request. Please try again'));
								front_redirect('account', 'refresh');
							}
						}
					}
				}
				else
				{

					$this->session->set_flashdata('error', 'Please check the address');
					front_redirect('account', 'refresh');
				}	
			}
			else
			{
				$this->session->set_flashdata('error', validation_errors());
				front_redirect('account', 'refresh');
			}
	    }

	    else{
	    	front_redirect('account', 'refresh');
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

        $Fetch_coin_list = $this->common_model->getTableData('currency',array('currency_symbol'=>$coin_symbol,'status'=>'1'))->result();

        $whers_con = "id='1'";

        // $get_admin  =   $this->common_model->getrow("bluerico_admin", $whers_con);
        // print_r($get_admin); exit();

        $admin_id = "1";

        $enc_email = getAdminDetails($admin_id, 'email_id');

		$email = decryptIt($enc_email);


        $get_admin = $this->common_model->getrow("bidex_admin_wallet", $whers_con);

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
			$this->form_validation->set_rules('account_number', 'Bank Account number', 'required|xss_clean');
			$this->form_validation->set_rules('account_name', 'Bank Account Name', 'required|xss_clean');
			$this->form_validation->set_rules('ifsc', 'IFSC Code', 'required|xss_clean');
			$this->form_validation->set_rules('bank_branch', 'Bank Branch Location', 'required|xss_clean');
			$this->form_validation->set_rules('bank_name', 'Bank Name', 'required|xss_clean');
			$this->form_validation->set_rules('account_type', 'Account Type', 'required|xss_clean');
			if($this->form_validation->run())
			{
				$insertData['user_id'] = $user_id;
				//$insertData['currency'] = $this->db->escape_str($this->input->post('currency'));
				$insertData['bank_account_name'] = $this->db->escape_str($this->input->post('account_name'));
				$insertData['bank_account_number'] = $this->db->escape_str($this->input->post('account_number'));
				$insertData['bank_swift'] = $this->db->escape_str($this->input->post('ifsc'));
				$insertData['bank_name'] = $this->db->escape_str($this->input->post('bank_name'));
				$insertData['bank_address'] = $this->db->escape_str($this->input->post('bank_branch'));
				$insertData['account_type'] = $this->db->escape_str($this->input->post('account_type'));
				//$insertData['bank_city'] = $this->db->escape_str($this->input->post('bank_city'));
				//$insertData['bank_country'] = $this->db->escape_str($this->input->post('bank_country'));
				//$insertData['bank_postalcode'] = $this->db->escape_str($this->input->post('bank_postalcode'));
				$insertData['added_date'] = date("Y-m-d H:i:s");				
				$insertData['status'] = 'Pending';
				$insertData['user_status'] = '1';
				if ($_FILES['bank_statement']['name']!="") 
				{
					$imagepro = $_FILES['bank_statement']['name'];
					if($imagepro!="" && getExtension($_FILES['bank_statement']['type']))
					{
						$uploadimage1=cdn_file_upload($_FILES["bank_statement"],'uploads/user/'.$user_id,$this->input->post('bank_statement'));
						if($uploadimage1)
						{
							$imagepro=$uploadimage1['secure_url'];
						}
						else
						{
							$this->session->set_flashdata('error', 'Problem with profile picture');
							front_redirect('profile', 'refresh');
						} 
					}				
					$insertData['bank_statement']=$imagepro;
				}
				$insertData_clean = $this->security->xss_clean($insertData);

				$insert=$this->common_model->insertTableData('user_bank_details', $insertData_clean);
				if ($insert) {
					//$profileupdate = $this->common_model->updateTableData('users',array('id' => $user_id), array('profile_status'=>1));
					$this->session->set_flashdata('success', 'Bank details Updated Successfully');

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

	

	function deposit($cur='')
	{
if($this->block() == 1)
{ 
front_redirect('block_ip');
}
		
		$user_id=$this->session->userdata('user_id');
		if($user_id=="")
		{	
			front_redirect('', 'refresh');
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

		

		if(isset($_POST['depsoit']))
		{
			 
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

			$ref_no 	   = $this->db->escape_str($this->input->post('ref_no'));
			$bank_no 	   = $this->db->escape_str($this->input->post('bank'));
			$payment_types = $this->db->escape_str($this->input->post('payment_types'));
			$description = $this->db->escape_str($this->input->post('description'));

			if($ref_no == '' && $description == '')
			{
				$ref_no 	 = '-';
				$description = '-';
			}


				
			$insertData = array(
				'user_id'=>$user_id,
				'payment_method'=>$payment_types,
				'currency_id'=>$this->db->escape_str($this->input->post('currency')),
				'amount'=>$this->db->escape_str($this->input->post('amount')),
				'transaction_id'=>$ref_no,
				'description'=>$description,
				'bank_id'=>$bank_no,
				'fee'=>$final_fees,
				'transfer_amount'=>$total,
				'datetime'=>gmdate(time()),
				'type'=>'Deposit',
				'status'=>'Pending',
				'currency_type'=>'fiat',
				);

			$insert = $this->common_model->insertTableData('transactions', $insertData);
			if ($insert) {
				$this->session->set_flashdata('success', 'Your deposit request placed successfully');
				
				if($payment_types == 'paypal')
				{
					front_redirect('pay/'.$insert, 'refresh');
				}
				else
				{
					front_redirect('deposit', 'refresh');
				}
				//front_redirect('deposit', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Unable to submit your deposit request. Please try again');
				front_redirect('deposit', 'refresh');
			}

		}

		$userId = $this->session->userdata('user_id');
		$getuser_details = $this->common_model->getTableData('users',array('id'=>$userId))->row();
		//GET PAYPAL ACCOUNT DETAILS
		$sitesettings = $this->common_model->getTableData('site_settings',array('id'=>1))->row(); 
		$paypal_username = decryptIt($sitesettings->paypal_username);
		$paypal_password = decryptIt($sitesettings->paypal_password);
		$paypal_signature = decryptIt($sitesettings->paypal_signature);
		$paypal_clientid = $sitesettings->paypal_clientid;
		$paypal_secretid = $sitesettings->paypal_secretid;
		$paypal_mode = $sitesettings->paypal_mode; // TRUE OR FALSE		

        // Paypal Deposit
		if(isset($_POST['depist']))
		{
			$data['slct_fiat_currency'] = $this->common_model->getTableData('currency',array('status'=>1, 'id'=>$this->input->post('currency')))->row();
			$slct_fiat_currency = $data['slct_fiat_currency'];
			$value = $this->db->escape_str($this->input->post('amount_paypal'));
			$amount = $this->input->post("amount_paypal");

			if($value < $slct_fiat_currency->min_deposit_limit)
			{
				$this->session->set_flashdata('error', 'Amount you have entered is less than the minimum deposit limit');
				front_redirect('deposit', 'refresh');	
			}
			elseif($value>$slct_fiat_currency->max_deposit_limit)
			{
			$this->session->set_flashdata('error', 'Amount you have entered is more than the maximum deposit limit');
			front_redirect('deposit', 'refresh');	
			}
			$deposit_max_fees = $data['slct_fiat_currency']->deposit_max_fees;
	        $deposit_fees_type = $data['slct_fiat_currency']->deposit_fees_type;
	        $deposit_fees = $data['slct_fiat_currency']->deposit_fees;
	        if($deposit_fees_type=='Percent') 
	        { 
	        	$fees = (($value*$deposit_fees)/100); 
	        }
	        else 
	        { 
	        	$fees = $deposit_fees; 
	        }

	        if($fees>$deposit_max_fees) { $final_fees = $deposit_max_fees; }

	        else { $final_fees = $fees; }

	        //$total = $value-$final_fees; // for deposit fee apply condition
	        $total = $value;

			$this->load->library('Paypalp');
	   		$paypalPro = new paypalp();
	   		

	        // POST VARIABLES FROM FORM
	   		$expDateMonth = urlencode($this->input->post('ex_date'));
		    $padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
		    $expDateYear = urlencode($this->input->post('ex_year'));
		   
		    $creditCardNumber = urlencode($this->input->post('card_num'));
		    $cvv2Number = urlencode($this->input->post('card_ver_num'));
		    $firstName = $getuser_details->bidex_fname;
		    $lastName = $getuser_details->bidex_lname;
		    $address1 = $getuser_details->street_address.$getuser_details->street_address_2;
		    $city = $getuser_details->city;
		    $state = $getuser_details->state;
		    $zip = $getuser_details->postal_code;
		    $currencyCode = "USD";
		    $creditCardType = $this->input->post('card_type');

		    $currencyId = $this->input->post('currency');
		    
		    // RECURRING = FALSE
		    $nvpRecurring = '';
		    $methodToCall = 'doDirectPayment';
		    $paymentAction = urlencode("Sale");
		    $amount = $total;

		    $nvpstr='&PAYMENTACTION='.$paymentAction.'&AMT='.$amount.'&CREDITCARDTYPE='.$creditCardType.'&ACCT='.$creditCardNumber.'&EXPDATE='.$padDateMonth.$expDateYear.'&CVV2='.$cvv2Number.'&FIRSTNAME='.$firstName.'&LASTNAME='.$lastName.'&STREET='.$address1.'&CITY='.$city.'&STATE='.$state.'&ZIP='.$zip.'&COUNTRYCODE=US&CURRENCYCODE='.$currencyCode.$nvpRecurring;

		    // CALL PAYPAL PRO LIBRARY
		    $paypalPros = $paypalPro->details($paypal_username, $paypal_password, $paypal_signature, '', '', $paypal_mode, FALSE);
		    // GET Paypal Response
		    /*echo "<pre>";
		    print_r($paypalPros);*/
		    $resArray = $paypalPro->hash_call($methodToCall,$nvpstr); 
		    $ack = strtoupper($resArray["ACK"]);
		    /*echo "<pre>";
		    print_r($resArray);
		    exit();*/
		    if($ack!="SUCCESS")
	        {
	        	$trans_id = '';
	        	$payamount   = '';
	        	$pay_status = 'Pending';
	        	$payment_status = 'Not Paid';
	        	$msg = 'not';
	        }
	        else
	        { 
	        	$trans_id   = $resArray["TRANSACTIONID"];
	        	$payamount  = $resArray['AMT'];
	        	$pay_status = 'Completed';
	        	$payment_status = 'Paid';
	        	$msg = '';

	        	$userbalance = getBalance($userId,$currencyId); 
			    $finalbalance = ($amount)+($userbalance); 
			    // Update user balance	
			    $updatebalance = updateBalance($userId,$currencyId,$finalbalance,''); 
			   

	        }
	        // STORE PAYPAL RESPONSE IN DB
	        $dataInsert = array(
				'user_id' => $userId,
				'currency_id' => $currencyId,
				'currency_name' => $currencyCode,
				'amount' => $amount,
				'type' => 'deposit',
				'payment_method' => 'Paypal',
				'transfer_amount' => $payamount,
				'paid_amount' => $payamount,
				'transaction_id'=>$trans_id,
				'status' => $pay_status,
				'payment_status' => $payment_status,
				'currency_type' => 'fiat',
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
				$email_template   = 'Fiat_Deposit';		
					$special_vars = array(
					'###SITENAME###' => $sitename,			
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $amount,
					'###CURRENCY###' => 'USD',
					'###MSG###' => $msg,
					'###STATUS###'	 =>	ucfirst($pay_status)
					);
				// USER NOTIFICATION
				//$email = 'manimegalai@spiegeltechnologies.com';
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				if($pay_status=='Pending')
				{
					$this->session->set_flashdata('error','Your Fiat Deposit Failed. Please try again.');
				}
				
				else
				{
					$this->session->set_flashdata('success','Your Fiat Deposit successfully completed');
				}
				front_redirect('deposit', 'refresh');
			} 
			else 
			{
				$this->session->set_flashdata('error', 'Unable to submit your Fiat Deposit request. Please try again');
				front_redirect('deposit', 'refresh');
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
		$data['First_coin_image'] =	"https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$coin_address&choe=UTF-8&chld=L";
		$data['crypto_address'] = $coin_address;
		$data['site_common'] = site_common();
		$data['action'] = front_url() . 'deposit';
		$data['js_link'] = 'deposit';
		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'deposit'))->row();
		/*$meta = $this->common_model->getTableData('meta_content', array('link' => 'deposit'))->row();
		$data['heading'] = $meta->heading;
		$data['title'] = $meta->title;
		$data['meta_keywords'] = $meta->meta_keywords;
		$data['meta_description'] = $meta->meta_description;*/
		$this->load->view('front/user/deposit', $data); 
	}

	function withdraw($cur='')
	{
			 
       error_reporting(0);
        $this->load->library(array('form_validation','session'));
		$user_id=$this->session->userdata('user_id');
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
		$data['site_common'] = site_common();	
		$data['currency'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','',array('id','ASC'))->result();	
		$data['users'] = $this->common_model->getTableData('users',array('id'=>$user_id))->row();
		if(isset($cur) && !empty($cur)){
			$data['sel_currency'] = $this->common_model->getTableData('currency',array('currency_symbol'=>$cur),'','','','','','',array('id','ASC'))->row();
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
		
		$data['user_id'] = $user_id;
		
		$data['selcur_id'] = $data['sel_currency']->id;
		
		$data['currency_balance'] = getBalance($user_id,$data['selcur_id']);
		$data['wallet'] = unserialize($this->common_model->getTableData('wallet',array('user_id'=>$user_id),'crypto_amount')->row('crypto_amount'));

		$data['meta_content'] = $this->common_model->getTableData('meta_content',array('link'=>'withdraw'))->row();
		$data['withdraw_history'] = $this->common_model->getTableData('transactions',array('user_id'=>$user_id,'type'=>'Withdraw'),'','','','','','',array('trans_id','DESC'))->result();


		if(isset($_POST['withdrawcoin']))
	    {

			$this->form_validation->set_rules('ids', 'ids', 'trim|required|xss_clean|numeric');
			$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
			$passinp = $this->db->escape_str($this->input->post('ids'));
			$myval = explode('_',$passinp);
			$id = $myval[0]; 
			$name = $myval[1];
			$bal = $myval[2];

			if($id!=6)
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
				if($id!=5)
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
						if($amount>$balance)
						{ 
							$this->session->set_flashdata('error', 'Amount you have entered is more than your current balance');
							front_redirect('withdraw/'.$cur, 'refresh');
						}
						if($amount < $currency->min_withdraw_limit)
						{
							$this->session->set_flashdata('error','Amount you have entered is less than minimum withdrawl limit');
							front_redirect('withdraw/'.$cur, 'refresh');
						}
						elseif($amount>$currency->max_withdraw_limit)
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
								'bank_id'=>$Bank_id,
								'crypto_address'=>$address,
								'transfer_amount'=>$total,
								'datetime'=>date("Y-m-d h:i:s"),
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

								if($id!=5)
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
			$this->session->set_flashdata('error','Something Went Wrong. Please try again.');
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
							$this->session->set_flashdata('error','Your Crypto Deposit Failed. Please try again.');
						}
						
						else
						{
							$this->session->set_flashdata('success','Your Crypto Deposit successfully completed');
						}
						front_redirect('buy_crypto', 'refresh');
					} 
					else 
					{
						$this->session->set_flashdata('error', 'Unable to submit your Fiat Deposit request. Please try again');
						front_redirect('buy_crypto', 'refresh');
					}
				}
	        }
        }
        else
        {
        	$this->session->set_flashdata('error','Something Went Wrong. Please try again.');
			front_redirect('buy_crypto', 'refresh');
        }
	} 

	function getfailureresponse_wyre($userid)
	{		
		$this->session->set_flashdata('error','Something Went Wrong. Please try again.');
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
			$this->session->set_flashdata('success','Ticket Closed');
			front_redirect('support', 'refresh');
		}
		else{
			$this->session->set_flashdata('error','Something Went Wrong. Please try again.');
			front_redirect('support_reply/'.$code, 'refresh');
		}

	}


function apply_to_list(){

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
				front_redirect('applytolist', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Error occur!! Please try again');
				front_redirect('applytolist', 'refresh');
			}
		}
		$data['site_common'] = site_common();
		$meta = $this->common_model->getTableData('meta_content', array('link' => 'coin_request'))->row();
		$data['action'] = front_url() . 'applytolist';
		$data['heading'] = $meta->heading;
		$data['title'] = $meta->title;
		$data['meta_keywords'] = $meta->meta_keywords;
		$data['meta_description'] = $meta->meta_description;
$this->load->view('front/user/apply_to_list', $data);	
}

function phone_verification(){

	$user_id=$this->session->userdata('user_id');


	$data['site_common'] = site_common();
		$meta = $this->common_model->getTableData('meta_content', array('link' => 'coin_request'))->row();
		$data['heading'] = $meta->heading;
		$data['title'] = $meta->title;
		$data['meta_keywords'] = $meta->meta_keywords;
		$data['meta_description'] = $meta->meta_description;
		 //$data['countries'] = $this->common_model->getTableData('countries',array('phone_number!='=>null),'','','','','','','',array('phone_number','groupby'))->result(); 

		 $data['countries'] = $this->common_model->getTableData('countries',array('phone_number!='=>null),'','','','','','','',array('country_name','orderby ASC'))->result(); 

        if(isset($_REQUEST['submitphone'])){



        	$otp=$this->input->post('otpcode');
        	$number=$this->input->post('phonenumber');
        	$country=$this->input->post('country');

        	$userst=$this->common_model->getTableData('countries',array('id'=>$country))->row();

        	$userdet=$this->common_model->getTableData('users',array('id'=>$user_id))->row();


            if($otp==$userdet->phone_verifycode){ 

             $data2=array('phoneverified'=>"verified",
            'bidex_phone'=>$userst->phone_number."-".$number);
           
            $result=$this->common_model->updateTableData('users',array('id'=>$user_id),$data2);
            
            $this->session->set_flashdata('success', 'Your Mobile Number has been bound');
				front_redirect('account', 'refresh');
           
         } else {

           $this->session->set_flashdata('error', 'Wrongly entered the code,Your phone Number Unverfied');
				//front_redirect('account', 'refresh');


         }

       }  



$this->load->view('front/user/phone_verification', $data);

}

function phoneupdate(){


      $user_id=$this->session->userdata('user_id');
       
      $userdet=$this->common_model->getTableData('users',array('id'=>$user_id))->row();

                $code=mt_rand(100000,999999);
                $phcode=$this->input->post('country');
                $number=$this->input->post('phonenumber');
                $codesession=array('code'=>$code,
                  'phcode'=>$phcode,
                  'number'=>$number);

                // check to see if we are creating the user
					$email_template = 'phone_verification';
					$site_common      =   site_common();
					$fb_link = $site_common['site_settings']->facebooklink;
                    $tw_link = $site_common['site_settings']->twitterlink;               
                    $md_link = $site_common['site_settings']->youtube_link;
                    $ld_link = $site_common['site_settings']->linkedin_link;

					$special_vars = array(
					'###USERNAME###' => $userdet->bidex_username,
					'###CODE###' =>$code,
					//'###LINK###' => front_url().'verify_user/'.$activation_code,
					'###FB###' => $fb_link,
                    '###TW###' => $tw_link,                   
                    '###LD###' => $ld_link,
                    '###MD###' => $md_link

					);

				   $user_email=getUserEmail($user_id);

					$this->email_model->sendMail($user_email, '', '', $email_template, $special_vars);

                /*$this->session->set_userdata($codesession);

                    $data = ['phone' => $phcode.$number, 'text' => "CrazyCoin:Your phone number verification code " .$code];

                $this->sendSMS($data);*/
    
                $data1=array('phone_verifycode'=>$code);
                $result=$this->common_model->updateTableData('users',array('id'=>$user_id),$data1);
       
                if($result==1){
                    echo 1;
                  }else{ 
                    echo 0;
                    }
                exit;
     
}




}