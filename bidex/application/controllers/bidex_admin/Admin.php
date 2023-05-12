<?php
/**
 * Admin Class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
class Admin extends CI_Controller {
	// Constructor function 
	public function __construct()
	{	
		parent::__construct();		
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation'));
		$this->load->helper(array('url', 'language'));
		$this->load->library('session'); 
		
		 
	}
	// Index function - Loads to login
	public function index()
	{
		$this->login();
	}
	function delete_block_userip($id)
	{		
		$condition = array('id' => $id);
        $delete = $this->common_model->deleteTableData('page_handling', $condition);
        $this->session->set_flashdata('success', 'Ip Deleted Successfully');
	    admin_redirect('admin/block_userip', 'refresh');
	}
	
	
	// Admin login 
	function login()
	{
		//If Already logged in
		$sessionvar=$this->session->userdata('loggeduser');

		if($sessionvar!="")
		{	

			admin_redirect('admin/dashboard', 'refresh');
		}		
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('patterncode', 'pattern code', 'trim|required|xss_clean');
		// When Post
		if ($this->input->post()) { 
		if ($this->form_validation->run()) { 
			// Login credentials
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$remember = $this->input->post('remember');
			$patterncode = $this->input->post('patterncode');
			// Login using Ion Auth
			$login = $this->common_model->getTableData('admin',array('email_id'=>encryptIt($email),'password'=>encryptIt($password),'code'=>strrev($patterncode)));
			//echo $this->db->last_query();die;
			if ($login->num_rows()==1) { // Valid
				$activitdata = array(  'admin_email'	=> $login->row()->email_id,
						'admin_id'      => $login->row()->id,
                        'date'      	=> gmdate(time()),
                        'ip_address'	=> $_SERVER['REMOTE_ADDR'],
                        'activity'  	=> 'Login Sucess',
                        'browser_name'  => $_SERVER['HTTP_USER_AGENT'] );
        		$this->common_model->insertTableData('admin_activity',$activitdata);
				$this->load->helper('cookie');
				if(isset($remember) && !empty($remember))
				{
				   setcookie('admin_login_email', $email, time() + (86400 * 30), "/");
				   setcookie('admin_login_password', $password, time() + (86400 * 30), "/");
				   setcookie('admin_login_remember', $remember, time() + (86400 * 30), "/");
				}
				else
				{
					setcookie("admin_login_email", "", time() - 3600);
					setcookie("admin_login_password", "", time() - 3600);
					setcookie("admin_login_remember", "", time() - 3600);
				}
				//echo "<pre>";
				//print_r($login->row());die;
				//$this->common_model->last_activity($email,'Admin Login');
				$session_data = array('loggeduser'=> $login->row('id'));

  // print_r($session_data);

				// exit();
				$this->session->set_userdata($session_data);
			    $this->session->set_flashdata('success', 'You\'re logged in successfully!');
				admin_redirect('admin/dashboard', 'refresh');
			} else { // Invalid 
				// $logins = $this->common_model->getTableData('admin',array('email_id'=>encryptIt($email)));
				// if($logins->num_rows()==1)
				// {
				$activitdata = array(  'admin_email'	=> encryptIt($email),
						'admin_id'      => 1,
                        'date'      	=> gmdate(time()),
                        'ip_address'	=> $_SERVER['REMOTE_ADDR'],
                        'activity'  	=> 'Login Failed',
                        'browser_name'  => $_SERVER['HTTP_USER_AGENT'] );
        		$this->common_model->insertTableData('admin_activity',$activitdata);
				//}
				$this->session->set_flashdata('error', 'Invalid email or password or pattern!');
				admin_redirect('admin', 'refresh');
			}
		} else { // Form validations
			$this->session->set_flashdata('error', 'Problem with your email , password & pattern!');
			admin_redirect('admin', 'refresh');
		}	
		}
		// echo 'test1'.$this->form_validation->run(); print_r(validation_errors());  die;
		$data['action'] = admin_url() . 'admin/login';
		$data['title'] = 'Admin Login';
		$data['type']='login';
		$data['meta_keywords'] = 'Admin Login Keywords';
		$data['meta_description'] = 'Admin Login Description';
		$this->load->view('administrator/admin/login', $data);
	}


	//Sub admin login

	function subadmin()
	{
		// echo "hiii"; exit;
		//If Already logged in
		$sessionvar=$this->session->userdata('loggeduser');

		if($sessionvar!="")
		{	

			admin_redirect('admin/super_form', 'refresh');
		}		
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean');
		$this->form_validation->set_rules('patterncode', 'pattern code', 'trim|required|xss_clean');
		// When Post
		if ($this->input->post()) { 
		if ($this->form_validation->run()) { 
			// Login credentials
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$remember = $this->input->post('remember');
			$patterncode = $this->input->post('patterncode');
			// Login using Ion Auth
			$login = $this->common_model->getTableData('admin',array('email_id'=>encryptIt($email),'password'=>encryptIt($password),'code'=>strrev($patterncode)));
			//echo $this->db->last_query();die;
			if ($login->num_rows()==1) { // Valid
				$activitdata = array(  'admin_email'	=> $login->row()->email_id,
						'admin_id'      => $login->row()->id,
                        'date'      	=> gmdate(time()),
                        'ip_address'	=> $_SERVER['REMOTE_ADDR'],
                        'activity'  	=> 'Login Sucess',
                        'browser_name'  => $_SERVER['HTTP_USER_AGENT'] );
        		$this->common_model->insertTableData('admin_activity',$activitdata);
				$this->load->helper('cookie');
				if(isset($remember) && !empty($remember))
				{
				   setcookie('admin_login_email', $email, time() + (86400 * 30), "/");
				   setcookie('admin_login_password', $password, time() + (86400 * 30), "/");
				   setcookie('admin_login_remember', $remember, time() + (86400 * 30), "/");
				}
				else
				{
					setcookie("admin_login_email", "", time() - 3600);
					setcookie("admin_login_password", "", time() - 3600);
					setcookie("admin_login_remember", "", time() - 3600);
				}
				//echo "<pre>";
				//print_r($login->row());die;
				//$this->common_model->last_activity($email,'Admin Login');
				$session_data = array('loggeduser'=> $login->row('id'));

  // print_r($session_data);

				// exit();
				$this->session->set_userdata($session_data);
			    $this->session->set_flashdata('success', 'You\'re logged in successfully!');
				admin_redirect('admin/dashboard', 'refresh');
			} else { // Invalid 
				// $logins = $this->common_model->getTableData('admin',array('email_id'=>encryptIt($email)));
				// if($logins->num_rows()==1)
				// {
				$activitdata = array(  'admin_email'	=> encryptIt($email),
						'admin_id'      => 1,
                        'date'      	=> gmdate(time()),
                        'ip_address'	=> $_SERVER['REMOTE_ADDR'],
                        'activity'  	=> 'Login Failed',
                        'browser_name'  => $_SERVER['HTTP_USER_AGENT'] );
        		$this->common_model->insertTableData('admin_activity',$activitdata);
				//}
				$this->session->set_flashdata('error', 'Invalid email or password or pattern!');
				admin_redirect('admin', 'refresh');
			}
		} else { // Form validations
			$this->session->set_flashdata('error', 'Problem with your email , password & pattern!');
			admin_redirect('admin', 'refresh');
		}	
		}
		// echo 'test1'.$this->form_validation->run(); print_r(validation_errors());  die;
		$data['action'] = admin_url() . 'admin/subadmin';
		$data['title'] = 'Admin Login';
		$data['type']='subadmin';
		$data['meta_keywords'] = 'Sub Admin Login Keywords';
		$data['meta_description'] = 'Sub Admin Login Description';
		$this->load->view('administrator/admin/subadmin', $data);
	}



// subadmin profit added


		function super_form($id='')
		{
			$sessionvar=$this->session->userdata('loggeduser');

			// $update = $this->common_model->getTableData('coin_profit',array('No'=>$id))->row();
				$ids = $id;

		// if ($sessionvar==2) {

			if($this->input->post())
			{
				$coin_id = $this->input->post('coin_id');
				$currency = $this->input->post('currency_id');
				$week_number = $this->input->post('week_number');
				$week_year = $this->input->post('week_year');
				$weekly_profit = $this->input->post('weekly_profit');
				$month = $this->input->post('month');
				$month_year = $this->input->post('monthly_year');
				$monthly_profit= $this->input->post('monthly_profit');
				$yearly_year = $this->input->post('yearly_year');
				$yearly_profit = $this->input->post('yearly_profit');

				
				$data = array(
					"currency_id"=>$currency,
					"week_number"=>$week_number,
					"week_year"=>$week_year,
					"weekly_profit"=>$weekly_profit,
					"month"=>$month,
					"monthly_year"=>$month_year,
					"monthly_profit"=>$monthly_profit,
					"yearly_year"=>$yearly_year,
					"yearly_profit"=>$yearly_profit
				);

		// print_r($data);exit;




	$coin_profit = $this->common_model->getTableData('coin_profit',array('No'=>$coin_id))->num_rows();


		if($coin_profit>0)
		{

			$update = $this->common_model->updateTableData('coin_profit',array('No'=>$coin_id),$data);
				admin_redirect('admin/super_coinprofit', 'refresh');

		}
		else
		{
			$profit = $this->common_model->insertTableData('coin_profit',$data);

				admin_redirect('admin/super_form', 'refresh');

		}
		


				
			}

			$data['currencies'] = $this->common_model->customQuery("select * from bidex_currency where status='1' and currency_symbol in ('BTC','ETH')")->result();


			$data['edit'] = $this->common_model->getTableData('coin_profit',array('No'=>$id))->row();


$data['edite'] = getcurrencydetail($data['edit']->currency_id);
			// print_r($data['edite']);exit;

		// Get the list pages
		$data['action'] = admin_url() . 'admin/super_form';
			$data['siteSettings'] = $this->common_model->getTableData('site_settings', array('id' => 1))->row();
			$data['title'] = 'Site Settings';
			$data['meta_keywords'] = 'Site Settings';
			$data['meta_description'] = 'Site Settings';
			$data['main_content'] = 'admin/super_form';
			$data['admin_id']=$sessionvar;
			$this->load->view('administrator/admin_template', $data);

		// }

		}


     //admin wallet balance added manually

		function admin_wallet()
		{


			if($this->input->post())
			{

				$currency = $this->input->post('currency_id');
				$balance = $this->input->post('update_balance');

				$update = updateadminBalance(1,$currency,$balance);

			}





			$data['currencies'] = $this->common_model->customQuery("select * from bidex_currency where status='1' ")->result();

			$data['action'] = admin_url() . 'admin/admin_wallet';
			$data['siteSettings'] = $this->common_model->getTableData('site_settings', array('id' => 1))->row();
			$data['title'] = 'Site Settings';
			$data['meta_keywords'] = 'Site Settings';
			$data['meta_description'] = 'Site Settings';
			$data['main_content'] = 'admin/admin_wallet';
			$data['admin_id']=$sessionvar;
			$this->load->view('administrator/admin_template', $data);
		}



	// Forget Password
	function forget_password() {
		//If Already logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if($sessionvar!="")
		{	
			admin_redirect('admin/dashboard', 'refresh');
		}
		// Form validation
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		// Post value
		if ($this->input->post()) {
			if ($this->form_validation->run()) {
				$email = $this->input->post('email');
				$identity = $this->common_model->getTableData('admin',array('email_id'=>$email));
				// Check email on DB
				if ($identity->num_rows()==0) {
						$this->session->set_flashdata('error', 'It looks like you doesn\'t in our database.');
						admin_redirect('admin/forget_password', 'refresh');
				}
				else
				{
						$to      	= $email;
						$email_template = 1;
						$special_vars = array(						
						'###USERNAME###' => $identity->row('admin_name'),
						'###PASSWORD###' => decryptIt($identity->row('password'))
						);	
					if ($this->email_model->sendMail($to, '', '', $email_template, $special_vars)) {
						$this->session->set_flashdata('success', 'We have send you an email for reset your password');
						admin_redirect('admin', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Problem occure with your forget password');
						admin_redirect('admin/forget_password', 'refresh');
					}
				}
			} else { // Form validation error
				$this->session->set_flashdata('error', 'Problem with your email');
				admin_redirect('admin/forget_password', 'refresh');
			}
		}
		$data['action'] = admin_url() . 'admin/forget_password';
		$data['title'] = 'Forget Password';
		$data['type']='forget';
		$data['meta_keywords'] = 'Forget Password Keywords';
		$data['meta_description'] = 'Forget Password Description';
		$this->load->view('administrator/admin/login', $data);
	}

	function dashboard_ajax()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$draw = $this->input->get('draw');
		$start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
        $search = $search['value'];
        $encrypt_search = encryptIt($search);
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
            1=>'user_id',
            2=>'date',           
            3=>'ip_address',
            4=>'activity'
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
        $like = '';

        

        if(!empty($search))
        { 
            $like = " WHERE b.bidex_email LIKE '%".$search."%' OR c.bidex_type LIKE '%".$encrypt_search."%' OR a.activity LIKE '%".$search."%' OR a.ip_address LIKE '%".$search."%'";

$query = "SELECT a.*,b.bidex_email,c.bidex_type FROM `bidex_user_activity` as a INNER JOIN bidex_users as b INNER JOIN bidex_history as c on a.user_id=b.id AND b.id=c.user_id ".$like." ORDER BY a.act_id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT a.*,b.bidex_email,c.bidex_type FROM `bidex_user_activity` as a INNER JOIN bidex_users as b INNER JOIN bidex_history as c on a.user_id=b.id AND b.id=c.user_id".$like." ORDER BY a.act_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*,b.bidex_email,c.bidex_type FROM `bidex_user_activity` as a INNER JOIN bidex_users as b INNER JOIN bidex_history as c on a.user_id=b.id AND b.id=c.user_id ORDER BY a.act_id DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*,b.bidex_email,c.bidex_type FROM `bidex_user_activity` as a INNER JOIN bidex_users as b INNER JOIN bidex_history as c on a.user_id=b.id AND b.id=c.user_id ORDER BY a.act_id DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $activity){
				$i++;				
					$data[] = array(
					    $i, 
						getUserEmail($activity->user_id),
						date('d-m-Y h:i a',$activity->date),
						$activity->ip_address,
						$activity->activity
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
	// Admin Dashboard
	function dashboard()
	{
		//If login
		$sessionvar=$this->session->userdata('loggeduser');

		// print_r($sessionvar.'============');exit;

		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		else {
			
            $user                   = $this->common_model->getTableData('users', array());
            $data['num_users']      = $user->num_rows();
			$exchange_orders        = $this->common_model->getTableData('transactions', array('type'=>'Deposit','admin_status'=>0), 'count(trans_id) as tot');
            $data['exchange_orders']      = $exchange_orders->row('tot');
            $sell_orders           = $this->common_model->getTableData('coin_order', array('Type'=>'sell'), 'count(trade_id) as tot');
            $data['sell_orders']  = $sell_orders->row('tot');            
            $buy_orders           = $this->common_model->getTableData('coin_order', array('Type'=>'buy'), 'count(trade_id) as tot');
            $data['buy_orders']  = $buy_orders->row('tot');
            $user_act               = $this->common_model->getTableData('user_activity','', '', '', '', '', '', '500', array('act_id', 'DESC'));
            $data['user_activity']  = $user_act->result();
			$chartdata=array();
			$chartdata[0]=array();
			$chartdata[1]=array();
			$chartdata[2]=array();
			$categories=array();
			$count=1;
			for($i=6;$i>=0;$i--)
			{
				$data0=array($count,date('d-M',strtotime('-'.$i.' days')));
				$chartdata[0][]=$data0;
				$date1=date('Y-m-d',strtotime('-'.$i.' days'));
				$data1=array($count,intval($this->dashboard_chart_users($date1)));
				$data2=array($count,intval($this->dashboard_chart_sitevisits($date1)));
				$chartdata[1][]=$data1;
				$chartdata[2][]=$data2;
				$count++;
			}
			//$query='SELECT `affiliate_id`,COUNT(`affiliate_id`) AS `value_occurrence` FROM `click_history` GROUP BY `affiliate_id` ORDER BY `value_occurrence` DESC LIMIT 9';
			$sitevisits=$this->common_model->getTableData('site_visits','','browser AS label,COUNT(browser) AS data','', '', '', '', '', array('data', 'DESC'),array('browser'));
			
			$site_visit = array();
			foreach($sitevisits->result() as $site)
			{
				if($site->data>2&&count($site_visit)<3)
				{
					array_push($site_visit, $site);
				}
			}
			// echo "<pre>";
			// print_r($site_visit);die;
			$data['sitevisits']=$site_visit;
			$data['chartdata']=$chartdata;
			$data['title'] = 'Dashboard';
			$data['meta_keywords'] = 'Dashboard';
			$data['meta_description'] = 'Dashboard';
			$data['main_content'] = 'admin/dashboard';
			// echo "<pre>";
			// print_r($data);die;
			$this->load->view('administrator/admin_template', $data); 
		}
	}
	function dashboard_chart_users($date1)
	{
		$date1=date_create($date1);
		$date=date_time_set($date1, 00, 00, 00);
		$date_start = strtotime("midnight", strtotime(date_format($date, 'Y-m-d H:i:s')));
		$date_end   = strtotime("tomorrow", $date_start) - 1;
		$this->db->where('created_on >=', $date_start);
		$this->db->where('created_on <=', $date_end);
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	function dashboard_chart_sitevisits($date1)
	{
		$this->db->where('date_added', $date1);
		$this->db->from('site_visits');
		return $this->db->count_all_results();
	}

	// function coin_profit_details($date1,$currency)
	// {
	// 	$daily="SELECT SUM(`theftAmount`) as `total` FROM `bidex_coin_theft` where `date`='$date1' AND `theftCurrency`='$currency'";
	// 	return $this->db->query($daily)->row('total');
	// }
	function coin_profit_details($date1,$currency)
{
    
    $daily="SELECT SUM(`profit_amount`) as `total` FROM `bidex_transaction_history` where datetime LIKE '%$date1%' AND `currency`='$currency'";
    return $this->db->query($daily)->row('total');
}

	// Change password
	function change_password() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('old_password', 'Old password', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		
		if ($this->input->post()) {
			if ($this->form_validation->run()) {
			$oldPassword = $this->input->post('old_password');
			$password = $this->input->post('password');
			$identity = $this->common_model->getTableData('admin',array('id'=>$sessionvar,'password'=>encryptIt($oldPassword)));
			if($identity->num_rows()>0)
			{
				$array=array('password'=>encryptIt($password));
				$change = $this->common_model->updateTableData('admin',array('id'=>$sessionvar),$array);
				// $this->ion_auth->messages();
				// $this->ion_auth->errors();
				if ($change) {
					$this->session->set_flashdata('success', 'Your password has been changed successfully');
					//$this->logout();
					admin_redirect('admin/change_password', 'refresh');	
				} else {
					$this->session->set_flashdata('error', 'Error occured while changing password');
					admin_redirect('admin/change_password', 'refresh');	
				}
			}
			else
			{
				$this->session->set_flashdata('error', 'Old Password is not valid');
				admin_redirect('admin/change_password', 'refresh');
			}
			} else {
				$this->session->set_flashdata('error', 'Old password and new password required.');
				admin_redirect('admin/change_password', 'refresh');
			}
		}
		$data['action'] = admin_url() . 'admin/change_password';
		$data['user'] = $this->common_model->getTableData('admin', array('id' => $sessionvar))->row();
		$data['title'] = 'Change Password';
		$data['meta_keywords'] = 'Change Password';
		$data['meta_description'] = 'Change Password';
		$data['main_content'] = 'admin/change_password';
		$this->load->view('administrator/admin_template', $data);
	}

	function wyre_settings()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		} else {			
			if ($this->input->post()) {
				$updateData['public_key'] 		= encryptIt($this->input->post('public_key'));
				$updateData['secret_key'] 		= encryptIt($this->input->post('secret_key'));
				$updateData['account_id'] 		= encryptIt($this->input->post('account_id'));
				$updateData['redirect_url'] 	= $this->input->post('redirect_url');
				$updateData['failure_url'] 		= $this->input->post('failure_url');
				$updateData['mode'] 		    = $this->input->post('mode');
				$update = $this->common_model->updateTableData('wyre_settings', array('id' => 1), $updateData);
				
				if ($update) {
					$this->session->set_flashdata('success', 'Wyre settings updated successfully.');
					admin_redirect('admin/wyre_settings', 'refresh');	
				} else {
					$this->session->set_flashdata('error', 'Problem with your Wyre settings updation.');
					admin_redirect('admin/wyre_settings', 'refresh');
				}
			}
			$data['action'] = admin_url() . 'admin/wyre_settings';
			$data['wyreSettings'] = $this->common_model->getTableData('wyre_settings', array('id' => 1))->row();
			$data['title'] = 'Wyre Settings';
			$data['meta_keywords'] = 'Wyre Settings';
			$data['meta_description'] = 'Wyre Settings';
			$data['main_content'] = 'admin/wyre_settings';
			$data['admin_id']=$sessionvar;
			$this->load->view('administrator/admin_template', $data);
		}
	}

	function asset_list()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		} 

		if (isset($_POST['asset_pri'])) 
		{
			$updateData['high_cost'] 		= $this->input->post('english_high_cost');
			$updateData['high_time'] 		= $this->input->post('english_high_days');
			$updateData['standard_cost'] 		= $this->input->post('english_standard_cost');
			$updateData['standard_time'] 		= $this->input->post('english_standard_days');
			$updateData['low_cost'] 		= $this->input->post('english_low_cost');
			$updateData['low_time'] 		= $this->input->post('english_low_days');
			$updateData['status'] 		= 1;
			$updateData['date_updated'] 		= date("Y-m-d h:i:s");

			$update = $this->common_model->updateTableData('asset_list', array('id' => 1), $updateData);
			if ($update) {
					$this->session->set_flashdata('success', 'Asset Priority settings updated successfully.');
					admin_redirect('admin/asset_list', 'refresh');	
			} else {
					$this->session->set_flashdata('error', 'Problem with your Asset Priority settings updation.');
					admin_redirect('admin/asset_list', 'refresh');
			}
		}
		$data['action'] = admin_url() . 'admin/asset_list';
		$data['siteSettings'] = $this->common_model->getTableData('asset_list', array('id' => 1))->row();
		$data['title'] = 'Asset Priority Settings';
		$data['meta_keywords'] = 'Asset Priority Settings';
		$data['meta_description'] = 'Asset Priority Settings';
		$data['main_content'] = 'admin/asset_list';
		$data['admin_id']=$sessionvar;
		$this->load->view('administrator/admin_template', $data);
	}
	
	// Site settings
	function site_settings() {
		// If login
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		} else {
			$this->form_validation->set_rules('site_name', 'Site Name', 'required|xss_clean');
			// Post the values
			if ($this->input->post()) {
				
					$updateData['site_name'] 		= $this->input->post('site_name');
					$updateData['copy_right_text'] 	= $this->input->post('copy_right_text');
					$updateData['address'] 	= $this->input->post('address');
					$updateData['city'] 	= $this->input->post('city');
					$updateData['state'] 	= $this->input->post('state');
					$updateData['country'] 	= $this->input->post('country');
					$updateData['zip'] 	= $this->input->post('zip');
					$updateData['contactno'] 	= $this->input->post('contactno');
					$updateData['altcontactno'] 	= $this->input->post('altcontactno');
				
				
				$updateData['site_email'] 	= $this->input->post('site_email');
				$updateData['google_captcha_secretkey'] 	= $this->input->post('google_captcha_secretkey');
				$updateData['google_captcha_sitekey'] 	= $this->input->post('google_captcha_sitekey');
				$updateData['trade_execution_type'] 	= $this->input->post('trade_execution_type');
				$updateData['referral_bonus_type'] 	= $this->input->post('referral_bonus_type');				
				$updateData['signup_bonus'] 		= $this->input->post('signup_bonus');
				$updateData['signup_bonus_type'] 		= $this->input->post('signup_bonus_type');
				$updateData['referral_bonus_type'] 	= $this->input->post('referral_bonus_type');
				$updateData['referral_bonus'] 		= $this->input->post('referral_bonus');
				$updateData['cryptocompare_apikey'] 		= $this->input->post('cryptocompare_apikey');
				$updateData['twilio_sender'] 		= $this->input->post('twilio_sender');
				$updateData['twilio_token'] 		= $this->input->post('twilio_token');
				$updateData['twilio_number'] 		= $this->input->post('twilio_number');
				
				$updateData['facebooklink'] 	= $this->input->post('facebooklink');
				$updateData['telegramlink'] 	= $this->input->post('telegramlink');
				$updateData['twitterlink'] 	= $this->input->post('twitterlink');
				$updateData['linkedin_link'] 	= $this->input->post('linkedin_link');
				$updateData['instagram_link'] 	= $this->input->post('instagram_link');
				$updateData['pinterest_link'] 	= $this->input->post('pinterest_link');
				$updateData['dribble_link'] 	= $this->input->post('dribble_link');
				$updateData['youtube_link'] 	= $this->input->post('youtube_link');
				$updateData['medium_link'] 	= $this->input->post('medium_link');
				$updateData['reddit_link'] 	= $this->input->post('reddit_link');




				$updateData['withdraw_limit_1'] 	= $this->input->post('withdraw_limit_1');

				$updateData['smtp_email'] 		= encryptIt($this->input->post('smtp_user'));
				$updateData['smtp_password'] 	= encryptIt($this->input->post('smtp_pass'));
                $updateData['smtp_host'] 		= encryptIt($this->input->post('smtp_host'));
                $updateData['smtp_port'] 		= encryptIt($this->input->post('smtp_port'));
				$updateData['login_status'] 	= $this->input->post('login_status');
				$updateData['newuser_reg_status'] 	= $this->input->post('newuser_reg_status');
				$updateData['verify_user_cash_status'] 		= $this->input->post('verify_user_cash_status');
				$updateData['unverify_user_cash_status'] 	= $this->input->post('unverify_user_cash_status');
				$updateData['tradehistory_via_api'] 	= $this->input->post('tradehistory_via_api');
				$updateData['paypal_mode'] 		= $this->input->post('paypal_mode');

				$updateData['paypal_username'] 		= encryptIt($this->input->post('paypal_username'));
				$updateData['paypal_password'] 		= encryptIt($this->input->post('paypal_password'));
				$updateData['paypal_signature'] 		= encryptIt($this->input->post('paypal_signature'));

				/*$updateData['social_profile'] 		= $this->input->post('social_profile');*/

				$updateData['coin_profit_setting'] 	= $this->input->post('coin_profit_setting');
				$updateData['coinprofit_setting'] 	= $this->input->post('coinprofit_setting');
				
				
				if ($_FILES["site_logo"]["name"] != '') {
					$uploadimage=cdn_file_upload($_FILES["site_logo"],'uploads/logo',getSiteLogo());
					if($uploadimage)
					{
						//$imgname=$uploadimage['secure_url'];
						if(is_array($uploadimage)) {
							$imgname=$uploadimage['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							admin_redirect('admin/site_settings', 'refresh');
						}
						$updateData['site_logo'] = $imgname;
					}
					else
					{
						$this->session->set_flashdata('error', 'Problem with your site logo');
						admin_redirect('admin/site_settings', 'refresh');
					}
				}

				if ($_FILES["site_favicon"]["name"] != '') {
					$uploadimage1=cdn_file_upload($_FILES["site_favicon"],'uploads/logo',getSiteFavIcon());
					if($uploadimage1)
					{
						//$imgname1=$uploadimage1['secure_url'];
						if(is_array($uploadimage1)) {
							$imgname1=$uploadimage1['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage1 );
							$this->session->set_flashdata('error', $errorMsg);
							admin_redirect('admin/site_settings', 'refresh');
						}
						$updateData['site_favicon'] = $imgname1;
					}
					else
					{
						$this->session->set_flashdata('error', 'Problem with your site favicon');
						admin_redirect('admin/site_settings', 'refresh');
					}    				
				}
				
				$update = $this->common_model->updateTableData('site_settings', array('id' => 1), $updateData);
				if(getAdminDetails($sessionvar,'code')!=$this->input->post('patterncode'))
				{
					$this->common_model->updateTableData('admin', array('id' => $sessionvar), array('code'=>strrev($this->input->post('patterncode'))));
				}

				if ($update) {
					$this->session->set_flashdata('success', 'Site settings updated successfully.');
					admin_redirect('admin/site_settings', 'refresh');	
				} else {
					$this->session->set_flashdata('error', 'Problem with your site settings updation.');
					admin_redirect('admin/site_settings', 'refresh');
				}
				
			}
			
			$data['action'] = admin_url() . 'admin/site_settings';
			$data['siteSettings'] = $this->common_model->getTableData('site_settings', array('id' => 1))->row();
			$data['title'] = 'Site Settings';
			$data['meta_keywords'] = 'Site Settings';
			$data['meta_description'] = 'Site Settings';
			$data['main_content'] = 'admin/site_settings';
			$data['admin_id']=$sessionvar;
			$this->load->view('administrator/admin_template', $data);
		}	
	}
	// Admin Logout
	function logout() {
		$this->session->sess_destroy();
		admin_redirect('admin', 'refresh');
	}


	function subadmin_logout()
	{
		$this->session->sess_destroy();
		admin_redirect('admin/subadmin', 'refresh');
		
	}


	// Admin Login History
	function login_history() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		$data['login_history'] = $this->common_model->getTableData('admin_activity',array('admin_id' => $sessionvar),'','','','','','500');
		
		$data['view'] = 'view_all';
		$data['title'] = 'Admin Login History';
		$data['meta_keywords'] = 'Admin Login History';
		$data['meta_description'] = 'Admin Login History';
		$data['main_content'] = 'admin/login_history';
		$this->load->view('administrator/admin_template', $data); 
	}

	function bank_details()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		$data['bank'] = $this->common_model->getTableData('admin_bank_details');
		// print_r($data['bank']); die;
		$data['view'] = 'view_all';
		$data['title'] = 'Admin Bank Details';
		$data['meta_keywords'] = 'Admin Bank Details';
		$data['meta_description'] = 'Admin Bank Details';
		$data['main_content'] = 'admin/bank_details';
		$this->load->view('administrator/admin_template', $data); 
	}
	
	function edit_bank_details($currency_symbol)
	{
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	$currency_id = $this->common_model->getTableData("currency",array('currency_symbol'=>$currency_symbol))->row('id');
	$isValid = $this->common_model->getTableData('admin_bank_details', array('currency' => $currency_id));
	
	// Form validation
	$this->form_validation->set_rules('bank_account_name', 'Bank Account Name', 'required|xss_clean');
	$this->form_validation->set_rules('bank_swift', 'Bank Swift', 'required|xss_clean');
	$this->form_validation->set_rules('bank_account_number', 'Bank Account Number', 'required|xss_clean');
	$this->form_validation->set_rules('bank_country', 'Bank Country', 'required|xss_clean');
	$this->form_validation->set_rules('bank_address', 'Bank Address', 'required|xss_clean');
	$this->form_validation->set_rules('bank_city', 'Bank City', 'required|xss_clean');
	$this->form_validation->set_rules('bank_postalcode', 'Bank Postal Code', 'required|xss_clean');
	$this->form_validation->set_rules('bank_name', 'Bank Name', 'required|xss_clean');
	//$this->form_validation->set_rules('currency', 'Currency', 'required|xss_clean');
	if ($this->input->post()) {				
		if ($this->form_validation->run())
		{
			$Data = array
		       (
		       	'currency' => $this->input->post('currency'),
		       	'bank_account_number' => $this->input->post('bank_account_number'),
		       	'bank_swift' => $this->input->post('bank_swift'),
		       	'bank_account_name' => $this->input->post('bank_account_name'),
		       	'bank_name' => $this->input->post('bank_name'),
		       	'bank_address' => $this->input->post('bank_address'),
		       	'bank_postalcode' => $this->input->post('bank_postalcode'),
		       	'bank_city' => $this->input->post('bank_city'),
		       	'bank_country' => $this->input->post('bank_country')
			   );
            
            $check = $this->common_model->getTableData('admin_bank_details', array('currency'=>$currency_id))->row();
		    
		    if(count($check)>0)
		    {
		    	$update = $this->common_model->updateTableData('admin_bank_details',array('currency'=>$currency_id),$Data);
		    	if($update)
		    	{
		    	$this->session->set_flashdata('success','Bank Details has been updated successfully!');
				admin_redirect('admin/bank_details', 'refresh');
			    }

		    }
		    else
		    {
		    	$insert = $this->common_model->insertTableData('admin_bank_details',$Data);
		    	if($insert)
		    	{
		    	$this->session->set_flashdata('success','Bank Details has been date_added successfully!');
				admin_redirect('admin/bank_details', 'refresh');
			    }
		    }
		}
		else {
			$this->session->set_flashdata('error', 'Unable to update this bank detail');
			admin_redirect('admin/edit_bank_details/' . $currency_symbol, 'refresh');
		}
		
	}
	$data['bank'] = $isValid->row();
	$data['currency'] = $this->common_model->getTableData('currency', array('status' => 1,'type'=>'fiat'))->result();
	$data['countries'] = $this->common_model->getTableData('countries')->result();
	$data['action'] = admin_url() . 'admin/edit_bank_details/' . $currency_symbol;
	$data['title'] = 'Edit Bank Details';
	$data['meta_keywords'] = 'Edit Bank Details';
	$data['meta_description'] = 'Edit Bank Details';
	$data['main_content'] = 'admin/bank_details';
	$data['view'] = 'edit';
	$this->load->view('administrator/admin_template', $data);
	}

	function activity_log()
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$log = $this->common_model->getTableData('audit')->result_array();
		$perpage =10;
  		$urisegment=$this->uri->segment(4);  
   		$base="admin/activity_log";
   		$total_rows = count($log);
   		pageconfig($total_rows,$base,$perpage);
		$data['log'] = $this->common_model->getTableData('audit','', '', '', '', '',$urisegment,$perpage,array('audit_id', 'DESC'));
		$data['title'] = 'Activity Log';
		$data['meta_keywords'] = 'Activity Log';
		$data['meta_description'] = 'Activity Log';
		$data['main_content'] = 'admin/activity_log';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function view_activitylog($id)
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$data['log'] = $this->common_model->getTableData('audit',array('audit_id'=>$id))->row();
		$data['title'] = 'Activity Log';
		$data['meta_keywords'] = 'Activity Log';
		$data['meta_description'] = 'Activity Log';
		$data['main_content'] = 'admin/activity_log';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}

	function coinprofit_ajax()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$draw = $this->input->get('draw');
		$start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
        $search = $search['value'];
        $encrypt_search = encryptIt($search);
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
            1=>'user_id',
            2=>'type',           
            3=>'currency_name',
            4=>'profit_amount',
            5=>'comment',
            6=>'datetime'
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
        $like = '';

        

        if(!empty($search))
        { 
            $like = " WHERE b.bidex_username LIKE '%".$search."%' OR a.type LIKE '%".$search."%' OR a.profit_amount LIKE '%".$search."%' OR c.currency_symbol LIKE '%".$search."%' OR a.comment LIKE '%".$search."%'";

$query = "SELECT a.*, b.bidex_username as username,c.currency_symbol FROM bidex_transaction_history as a JOIN bidex_users as b ON a.userId = b.id JOIN bidex_currency as c ON c.id = a.currency".$like." ORDER BY a.id DESC LIMIT ".$start.",".$length;


$countquery = $this->db->query("SELECT a.*, b.bidex_username as username,c.currency_symbol FROM bidex_transaction_history as a JOIN bidex_users as b ON a.userId = b.id JOIN bidex_currency as c ON c.id = a.currency".$like." ORDER BY a.id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.bidex_username as username,c.currency_symbol FROM bidex_transaction_history as a JOIN bidex_users as b ON a.userId = b.id JOIN bidex_currency as c ON c.id = a.currency ORDER BY `a`.`id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.bidex_username as username,c.currency_symbol FROM bidex_transaction_history as a JOIN bidex_users as b ON a.userId = b.id JOIN bidex_currency as c ON c.id = a.currency ORDER BY `a`.`id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;

				if($result->currency_type=='fiat')
				{
					$currency_name = getfiatcurrency($result->currency);
				}
				else
				{
					$currency_name = getcryptocurrency($result->currency);
				}
				$profit_total = $result->profit_amount + $result->bonus_amount;				
				
					$data[] = array(
					    $i, 
						// $result->username,
						getUserEmail($result->userId),
						$result->type,
						$currency_name,
						number_format($profit_total,8),
						$result->comment,
						gmdate("d-m-Y h:i a", strtotime($result->datetime))
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

	function coin_profit()
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$data['title'] = 'Coin Profit';
		$data['meta_keywords'] = 'Coin Profit';
		$data['meta_description'] = 'Coin Profit';
		$data['main_content'] = 'admin/coin_profit';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}



	function super_coinprofit($currency= ' ')
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$daily="SELECT DATE(datetime) as dateval, SUM(profit_amount) as total, SUM(bonus_amount) as bonus FROM bidex_transaction_history where currency='$currency' AND currency_type='$type' GROUP BY YEAR(datetime), MONTH(datetime), DATE(datetime)";
		$data['daily']=$this->db->query($daily);

		// $weekly="SELECT  WEEK(`datetime`) as week_number,YEAR(datetime) as yname,SUM(`profit_amount`) as total, SUM(bonus_amount) as bonus FROM `bidex_transaction_history` where currency='$currency' GROUP BY WEEK(`datetime`)";

		// print_r($currency);exit;

		if($currency==1){

			// print_r('ifff');exit;


		// $data['weekly'] = $this->common_model->getTableData('super_weekly_profit',array('currency_id'=>$currency),'btc_week_number,btc_week_year,btc_week_total')->result();

		$data['weekly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'week_number,week_year,weekly_profit,No')->result();

		// print_r($data['weekly']);exit;


		$data['monthly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'month,monthly_year,monthly_profit,No')->result();

		$data['yearly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'yearly_year,yearly_profit,No')->result();

		
		// print_r($data['yearly']);exit;



		}

		else
		{

			// print_r('else');exit;


		$data['weekly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'week_number,week_year,weekly_profit,No')->result();

		$data['monthly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'month,monthly_year,monthly_profit,No')->result();
				// echo '<pre>'; print_r($data['weekly']); die;
		$data['yearly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'yearly_year,yearly_profit,No')->result();

		}
		// echo '<pre>'; print_r($data['weekly']); die;


		// $data['weekly']=$this->db->query($weekly);


		// $monthly="SELECT MONTHNAME(datetime) as moname,YEAR(datetime) as yname, SUM(profit_amount) as total, SUM(bonus_amount) as bonus FROM bidex_transaction_history where currency='$currency' GROUP BY YEAR(datetime), MONTH(datetime)";
		// $data['monthly']=$this->db->query($monthly);


		// $yearly="SELECT YEAR(datetime) as yname, SUM(profit_amount) as total, SUM(bonus_amount) as bonus FROM bidex_transaction_history where currency='$currency' GROUP BY YEAR(datetime)";

		// $data['yearly']=$this->db->query($yearly);

			$chartdata=array();
			$chartdata[0]=array();
			$chartdata[1]=array();
			$categories=array();
			$count=1;
			for($i=30;$i>=0;$i--)
			{
				$data0=array($count,date('d-M',strtotime('-'.$i.' days')));
				$chartdata[0][]=$data0;
				$date1=date('Y-m-d',strtotime('-'.$i.' days'));
				$data1=array($count,$this->coin_profit_details($date1,$currency));
				$chartdata[1][]=$data1;
				$count++;
			}
		
		$data['chartdata']=$chartdata;
		$data['currency'] = $currency;
		$data['type'] = $type;
		$data['fi_cu'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1))->result();
		$ar = array('currency_symbol', array('BTC','ETH'));
		$data['cu'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','','','','',$ar)->result();
		// echo $this->db->last_query();
		// echo '<pre>'; print_r($data['cu']); die;

		// $data['cu'] = $this->common_model->getTableData('currency',array('currency_symbol'=>'digital','status'=>1))->result();

		$data['title'] = 'Coin Profit';
		$data['meta_keywords'] = 'Coin Profit';
		$data['meta_description'] = 'Coin Profit';
		$data['main_content'] = 'admin/super_coinprofit';
		$data['view'] = 'report';
		
		$data['currency_symbol'] = $this->common_model->getTableData('currency', array('id' => $currency,'status'=>1))->row('currency_symbol');
		
		$this->load->view('administrator/admin_template', $data); 
	}















	function coin_profit_report($currency="1", $type="fiat")
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$daily="SELECT DATE(datetime) as dateval, SUM(profit_amount) as total, SUM(bonus_amount) as bonus FROM bidex_transaction_history where currency='$currency' AND currency_type='$type' GROUP BY YEAR(datetime), MONTH(datetime), DATE(datetime)";
		$data['daily']=$this->db->query($daily);

		

		if($currency==1){

			// print_r('ifff');exit;


		// $data['weekly'] = $this->common_model->getTableData('super_weekly_profit',array('currency_id'=>$currency),'btc_week_number,btc_week_year,btc_week_total')->result();

		$data['weekly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'week_number,week_year,weekly_profit,No')->result();

		// print_r($data['weekly']);exit;


		$data['monthly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'month,monthly_year,monthly_profit,No')->result();

		$data['yearly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'yearly_year,yearly_profit,No')->result();

		
		// print_r($data['yearly']);exit;



		}

		else
		{

			// print_r('else');exit;


		$data['weekly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'week_number,week_year,weekly_profit,No')->result();

		$data['monthly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'month,monthly_year,monthly_profit,No')->result();
				// echo '<pre>'; print_r($data['weekly']); die;
		$data['yearly'] = $this->common_model->getTableData('coin_profit',array('currency_id'=>$currency),'yearly_year,yearly_profit,No')->result();

		}
	

			$chartdata=array();
			$chartdata[0]=array();
			$chartdata[1]=array();
			$categories=array();
			$count=1;
			for($i=30;$i>=0;$i--)
			{
				$data0=array($count,date('d-M',strtotime('-'.$i.' days')));
				$chartdata[0][]=$data0;
				$date1=date('Y-m-d',strtotime('-'.$i.' days'));
				$data1=array($count,$this->coin_profit_details($date1,$currency));
				$chartdata[1][]=$data1;
				$count++;
			}
		
		$data['chartdata']=$chartdata;
		$data['currency'] = $currency;
		$data['type'] = $type;
		$data['fi_cu'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1))->result();
		// $ar = array('currency_symbol', array('BTC','ETH'));
		// $data['cu'] = $this->common_model->getTableData('currency',array('status'=>1),'','','','','','','','','',$ar)->result();
		// echo $this->db->last_query();
		// echo '<pre>'; print_r($data['cu']); die;

		$data['cu'] = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1))->result();

		// echo '<pre>'; print_r($data); die;
		$data['title'] = 'Coin Profit';
		$data['meta_keywords'] = 'Coin Profit';
		$data['meta_description'] = 'Coin Profit';
		$data['main_content'] = 'admin/coin_profit_report';
		$data['view'] = 'report';
		/*if($type=="fiat")
		{
			$data['currency_symbol'] = $this->common_model->getTableData('fiat_currency', array('id' => $currency))->row('currency_symbol');
		}
		else
		{
			$data['currency_symbol'] = $this->common_model->getTableData('currency', array('id' => $currency))->row('currency_symbol');
		}*/
		$data['currency_symbol'] = $this->common_model->getTableData('currency', array('id' => $currency,'status'=>1))->row('currency_symbol');
		
		$this->load->view('administrator/admin_template', $data); 
	}

	function coin_profit_report_ajax($currency="1", $type="fiat")
	{

		$daily="SELECT DATE(datetime) as dateval, SUM(profit_amount) as total FROM bidex_transaction_history where currency='$currency' AND currency_type='$type' GROUP BY YEAR(datetime), MONTH(datetime), DATE(datetime)";
		$data['daily']=$this->db->query($daily);

		$data['input_daily'] = '';
		if($data['daily']->num_rows() > 0){
			$i = 1;
			foreach(array_reverse($data['daily']->result()) as $result) {
				$data['input_daily'] .= '<tr>';
				$data['input_daily'] .= '<td>' . $i . '</td>';
				$data['input_daily'] .= '<td>' . $result->dateval . '</td>';
				$data['input_daily'] .= '<td>100</td>';
				$data['input_daily'] .= '</tr>';
				$i++;

				// ' . $result->total . '
			}
		}
		else
		{
			$data['input_daily'] .= '<tr><td colspan="3">No Daily Coin Profit!</td></tr>';
		}

		$weekly="SELECT  WEEK(`datetime`) as week_number,YEAR(datetime) as yname,SUM(`profit_amount`) as total FROM `bidex_transaction_history` where currency='$currency' AND currency_type='$type' GROUP BY WEEK(`datetime`)";

		$data['weekly']=$this->db->query($weekly);

		$data['input_weekly'] = '';
		if($data['weekly']->num_rows() > 0){
			$j = 1;
			foreach(array_reverse($data['weekly']->result()) as $result1) {
				$data['input_weekly'] .= '<tr>';
				$data['input_weekly'] .= '<td>' . $j . '</td>';
				$data['input_weekly'] .= '<td>' . $result1->week_number . '</td>';
				$data['input_weekly'] .= '<td>' . $result1->yname . '</td>';
				$data['input_weekly'] .= '<td>' . $result1->total . '</td>';
				$data['input_weekly'] .= '</tr>';
				$j++;
			}
		}
		else
		{
			$data['input_weekly'] .= '<tr><td colspan="4">No Weekly Coin Profit!</td></tr>';
		}


		$monthly="SELECT MONTHNAME(datetime) as moname,YEAR(datetime) as yname, SUM(profit_amount) as total FROM bidex_transaction_history where currency='$currency' AND currency_type='$type' GROUP BY YEAR(datetime), MONTH(datetime)";
		$data['monthly']=$this->db->query($monthly);

		$data['input_monthly'] = '';
		if($data['monthly']->num_rows() > 0){
			$k = 1;
			foreach(array_reverse($data['monthly']->result()) as $result2) {
				$data['input_monthly'] .= '<tr>';
				$data['input_monthly'] .= '<td>' . $k . '</td>';
				$data['input_monthly'] .= '<td>' . $result2->moname . '</td>';
				$data['input_monthly'] .= '<td>' . $result2->yname . '</td>';
				$data['input_monthly'] .= '<td>' . $result2->total . '</td>';
				$data['input_monthly'] .= '</tr>';
				$k++;
			}
		}
		else
		{
			$data['input_monthly'] .= '<tr><td colspan="4">No monthly Coin Profit!</td></tr>';
		}

		$yearly="SELECT YEAR(datetime) as yname, SUM(profit_amount) as total FROM bidex_transaction_history where currency='$currency' AND currency_type='$type' GROUP BY YEAR(datetime)";

		$data['yearly']=$this->db->query($yearly);

		$data['input_yearly'] = '';
		if($data['yearly']->num_rows() > 0){
			$l = 1;
			foreach(array_reverse($data['yearly']->result()) as $result3) {
				$data['input_yearly'] .= '<tr>';
				$data['input_yearly'] .= '<td>' . $l . '</td>';
				$data['input_yearly'] .= '<td>' . $result3->yname . '</td>';
				$data['input_yearly'] .= '<td>' . $result3->total . '</td>';
				$data['input_yearly'] .= '</tr>';
				$l++;
			}
		}
		else
		{
			$data['input_yearly'] .= '<tr><td colspan="3">No Yearly Coin Profit!</td></tr>';
		}

		if($type=="fiat")
		{
			$data['currency_name'] = getfiatcurrency($currency);
		}
		else
		{
			$data['currency_name'] = getcryptocurrency($currency);
		}

		$data['currency'] = $currency;
		$data['type'] = $type;
		// print_r($data); die;
		echo json_encode($data);
	}

	function ip_ajax()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$draw = $this->input->get('draw');
		$start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
        $search = $search['value'];
        //$encrypt_search = encryptIt($search);
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
            1=>'ip',
            2=>'access_date',
            3=>'access_date'
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
        $like = '';
        if(!empty($search))
        { 
            $like = " WHERE ip LIKE '%".$search."%' OR access_date LIKE '%".$search."%'";

			$query = "SELECT * FROM bidex_page_handling".$like." ORDER BY id DESC LIMIT ".$start.",".$length;


			$countquery = $this->db->query("SELECT * FROM bidex_page_handling".$like." ORDER BY id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM bidex_page_handling ORDER BY id DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT * FROM bidex_page_handling ORDER BY id DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $blocks){
				$i++;
				$link="'".admin_url().'admin/delete_block_userip/'.$blocks->id."'";
				$delete = '<a onclick="deleteaction('.$link.');" data-placement="bottom" data-toggle="popover" data-content="Delete this IP" class="poper"><i class="fa fa-trash-o text-danger"></i></a>';	
				
					$data[] = array(
					    $i, 
						$blocks->ip,
						gmdate("d-m-Y h:i a", strtotime($blocks->access_date)),
						$delete
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

	function block_userip()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		if($_POST)
		{
			$this->form_validation->set_rules('ip', 'IP Address', 'required|xss_clean');
			if ($this->form_validation->run())
			{
				$insertdata = array(
					'access_date' => date('Y-m-d h:i:s'),
					'ip'          => $this->input->post('ip'),
					);
				$insert = $this->common_model->insertTableData('page_handling', $insertdata);
				if ($insert) {
					$this->session->set_flashdata('success', 'IP Address blocked successfully!');
					admin_redirect('admin/block_userip', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to block this IP!');
					admin_redirect('admin/block_userip', 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error', 'Data missing!');
				admin_redirect('admin/block_userip', 'refresh');
			}
		}
		$data['block_ip'] = $this->common_model->getTableData('page_handling')->result();
		$data['action'] = admin_url() . 'admin/block_userip';
		$data['title'] = 'Block User IP';
		$data['meta_keywords'] = 'Block User IP';
		$data['meta_description'] = 'Block User IP';
		$data['main_content'] = 'admin/block_user_ip';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function users_transferbalance()
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$transfers = $this->common_model->getTableData('transfer_history')->result_array();
		if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
			$search_string = $_GET['search_string'];
			$like = array("b.currency_symbol"=>$search_string);
			$like_or = array("c.bidex_username"=>$search_string, "b.currency_name"=>$search_string, "a.amount"=>$search_string, "a.from_account"=>$search_string, "a.to_account"=>$search_string);
			$hisjoins = array('currency as b'=>'a.currency = b.id','users as c'=>'a.user_id = c.id');
			$data['transfers'] = $this->common_model->getJoinedTableDatas('transfer_history as a',$hisjoins,'','a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username',$like,'',$like_or,'','',array('a.id', 'DESC'));	
		}
		else {
   		$perpage = max_records();
  		$urisegment=$this->uri->segment(4);  
   		$base="admin/users_transferbalance";
   		$total_rows = count($transfers);
   		pageconfig($total_rows,$base,$perpage);

		$hisjoins = array('currency as b'=>'a.currency = b.id','users as c'=>'a.user_id = c.id');
		$data['transfers'] = $this->common_model->getJoinedTableData('transfer_history as a',$hisjoins,'','a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username','','','',$urisegment,$perpage,array('a.id', 'DESC'));
		}
		$data['title'] = 'Users Transfer Balance';
		$data['meta_keywords'] = 'Users Transfer Balance';
		$data['meta_description'] = 'Users Transfer Balance';
		$data['main_content'] = 'admin/users_transferbalance';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}



	function withdraw_coin_confirm($id)
	{
	
		$id = base64_decode($id);

		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		$data['site_common'] = site_common();
		$site = $data['site_common'];
		$data['site_settings'] = $site['site_settings'];
		$admin_withdraw_confirmation = $data['site_settings']->admin_withdraw_confirmation;
		
		if($admin_withdraw_confirmation==0)
		{
			$this->session->set_flashdata('error', 'Invalid activity in our site');
			admin_redirect('withdraw/crypto_withdraw', 'refresh');
		}
		//print_r($id); die;

		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw','currency_type'=>'crypto', 'user_status'=>'Completed'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();


  //        print_r($withdraw);
		// exit();
        
        $eth_id = $this->common_model->getTableData("currency",array("currency_symbol"=>"ETH"))->row('id');
        $eth_admin_balance = getadminbalance(1,$eth_id);
		

		$bnb_id = $this->common_model->getTableData("currency",array("currency_symbol"=>"BNB"))->row('id');
        $bnb_admin_balance = getadminbalance(1,$bnb_id);

        $tron_id = $this->common_model->getTableData("currency",array("currency_symbol"=>"TRX"))->row('id');
        $tron_admin_balance = getadminbalance(1,$tron_id);
		

		if($isValid > 0)
		{
			 $fromid 	= $withdraw->user_id;
			 $fromuser  = $this->common_model->getTableData('users',array('id'=>$fromid))->row();
			 //print_r($fromuser);
			 //$fromacc   = getUserEmail($fromid);

			 $fromacc = getAddress($fromid,'40');

			if($withdraw->status=='Completed')
			{
				$this->session->set_flashdata('error', 'Your withdraw request already confirmed');
				admin_redirect('withdraw/crypto_withdraw', 'refresh');
			}
			else if($withdraw->status=='Cancelled')
			{
				$this->session->set_flashdata('error', 'Your withdraw request already cancelled');
				admin_redirect('withdraw/crypto_withdraw', 'refresh');
			}
			else {
					$amount 		= $withdraw->transfer_amount;
					$address 		= $withdraw->crypto_address;
					$currency 		= $withdraw->currency_id;
					$tagid = $withdraw->destination_tag;
					$coin_name  	= getcryptocurrencys($currency);
					$coin_symbol  	= getcryptocurrency($currency);
					// $coin_name = strtolower($coin_name);
					// $coin_name = str_replace(" ","",$coin_name);
					
					/*New code 26-6-18*/
					//$from_address1 = getAddress($withdraw->user_id,$withdraw->currency_id);
					$currency_det = getcryptocurrencydetail($currency);

					if($currency_det->crypto_type_other != '')
					{
						$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
						foreach($crypto_other_type_arr as $val)
						{
							if($val=='eth' && $withdraw->crypto_type == $val) // TOKEN
							{   
								$mini_balance = "0.005";
								if($eth_admin_balance <= $mini_balance)
								{
									$this->session->set_flashdata('error','Your Ethereum Balance is low so you did not able to withdraw');
									admin_redirect('withdraw/crypto_withdraw', 'refresh');
								}
								else
								{

								}
							}


							if($val=='bsc' && $withdraw->crypto_type == $val) // TOKEN
							{
								$mini_balance = "0.005";
								if($bnb_admin_balance <= $mini_balance)
								{
									$this->session->set_flashdata('error','Your BNB Balance is low so you did not able to withdraw');
									admin_redirect('withdraw/crypto_withdraw', 'refresh');
								}
								else
								{

								}
							}

							if($val=='tron' && $withdraw->crypto_type == $val) // TOKEN
							{
								$mini_balance = "2";
								if($tron_admin_balance <= $mini_balance)
								{
									$this->session->set_flashdata('error','Your TRX Balance is low so you did not able to withdraw');
									admin_redirect('withdraw/crypto_withdraw', 'refresh');
								}
								else
								{

								}
							}
						}
					} else {
						if($currency_det->crypto_type=='eth') // TOKEN
						{   
							$mini_balance = "0.005";
							if($eth_admin_balance <= $mini_balance)
							{
								$this->session->set_flashdata('error','Your Ethereum Balance is low so you did not able to withdraw');
								admin_redirect('withdraw/crypto_withdraw', 'refresh');
							}
							else
							{

							}
						}


						if($currency_det->crypto_type=='bsc') // TOKEN
						{
							$mini_balance = "0.005";
							if($bnb_admin_balance <= $mini_balance)
							{
								$this->session->set_flashdata('error','Your BNB Balance is low so you did not able to withdraw');
								admin_redirect('withdraw/crypto_withdraw', 'refresh');
							}
							else
							{

							}
						}

						if($currency_det->crypto_type=='tron') // TOKEN
						{
							$mini_balance = "2";
							if($tron_admin_balance <= $mini_balance)
							{
								$this->session->set_flashdata('error','Your TRX Balance is low so you did not able to withdraw');
								admin_redirect('withdraw/crypto_withdraw', 'refresh');
							}
							else
							{

							}
						}
					}


                   echo $coin_symbol;
                    $from_address1 = getadminAddress(1,$coin_symbol);
                    
					

					$user_address = getAddress($withdraw->user_id,$withdraw->currency_id);
					
					/*End 26-6-18*/
					// echo "<br>";
					// echo "Coin Name : - ".$coin_name;
					// echo "<br>";
					// echo "From Address : - ".$from_address1;
					// echo "<br>";
					// echo "To Address : - ".$address;
					// echo "<br>";
					//exit;


                            // echo $currency_det->crypto_type_other;
                            // exit();


					if($currency_det->crypto_type_other != '')
					{

						// echo "test";
						// exit();

						$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
						foreach($crypto_other_type_arr as $val)
						{



							if($val=="tron" && $withdraw->crypto_type == $val)
							{
								echo "tron";
								$from_address1 = getadminAddress(1, 'TRX');
								$private_key = getadmintronPrivate(1);
								$crypto_type_other = array('crypto'=>$val,'tron_private'=>$private_key);
								$wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1,$crypto_type_other);
							}
							else if($val=="bsc" && $withdraw->crypto_type == $val)
							{
								// echo "test_one";
								// exit();
								echo "bsc";
								$from_address1 = getadminAddress(1, 'BNB');
								$crypto_type_other = array('crypto'=>$val);
							    $wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1,$crypto_type_other);

							}
							else if($val=="eth" && $withdraw->crypto_type == $val)
							{

								 // echo "test_two";
								 // exit();
								echo "eth";
								$from_address1 = getadminAddress(1, 'ETH');
								$crypto_type_other = array('crypto'=>$val);
								$wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1,$crypto_type_other);
							}
						}
					} else {
						if($currency_det->crypto_type=="tron")
						{
							$private_key = getadmintronPrivate(1);
							$crypto_type_other = array('crypto'=>$currency_det->crypto_type,'tron_private'=>$private_key);
							$wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1,$crypto_type_other);
						}
						else
						{
							$crypto_type_other = array('crypto'=>$currency_det->crypto_type);
							$wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1,$crypto_type_other);
						}
					}

					//$wallet_bal = 100;
                    
                    $coin_type = $currency_det->coin_type;
                    $coin_decimal = $currency_det->currency_decimal;
                    $decimal_places = coin_decimal($coin_decimal);
                    
					//print_r($wallet_bal);  die();
					//$address
					$wallet_bal = number_format((float)$wallet_bal,8,'.','');
					$amount = number_format($amount,8,'.','');
                    //$wallet_bal = getBalance($withdraw->user_id,$currency,'crypto');

					echo "From Balance : ".$wallet_bal;
					echo "<br>";
					echo "Withdraw Amountsss :".$amount; 

					// exit();
				
                 
					if($wallet_bal >= $amount)
					{
						if($coin_type=="coin")
						{
							switch ($coin_name) 
							{
								case 'Ethereum':
									$from_address = trim($from_address1);
									$to = trim($address);	
									$amount1 = $amount * 1000000000000000000;
								// $amount1 =  rtrim(sprintf("%u", $amounts), ".");
									$GasLimit = 21000;
									$Gwei = $this->check_ethereum_functions('eth_gasPrice',"Ethereum");
									$GasPrice = $Gwei;
									$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
									//$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1);
									/*$trans_det 		= array('address'=>$address,'amount'=>(float)$amount,'comment'=>'Admin Confirms Withdraw');*/
									break;

									case 'BNB':
									$from_address = trim($from_address1);
									$to = trim($address);	
									$amount1 = $amount * 1000000000000000000;
									$GasLimit = 120000;
									/*$Gwei = $this->check_ethereum_functions('eth_gasPrice',"BNB");
									$GasPrice = $Gwei;*/
									$GasPrice = 30000000000;
									
									$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
									
									break;

									case 'Tron':
									$from_address = trim($from_address1);
									$to = trim($address);	
									$amount1 = $amount * 1000000;
									$privateKey = getadmintronPrivate(1);
									$trans_det 		= array('fromAddress'=>$from_address,'toAddress'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),"privateKey"=>$privateKey);
								//print_r($trans_det); exit;

									break;

									case "Ripple":
									$xrp_tag_det = $this->common_model->getTableData('crypto_address', array('user_id' => $fromid))->row();
									$from_address = trim($from_address1);
										$to = trim($address);
									$trans_det = array('fromacc' => $fromacc, 'toaddress' => $to, 'amount' => (float) $amount, 'tagid' => $xrp_tag_det->payment_id, 'destag' => $tagid, 'secret' => $xrp_tag_det->auto_gen, 'comment' => 'Admin Confirms Withdraw', 'comment_to' => 'Completed');

									break;	
															
								default:
									$trans_det 		= array('address'=>$address,'amount'=>(float)$amount,'comment'=>'Admin Confirms Withdraw');

									//$trans_det 		= array('fromacc'=>$fromacc,'toaddress'=>$address,'amount'=>(float)$amount,'minconf'=>1,'comment'=>'Admin Confirms Withdraw','comment_to'=>'Completed');
									break;
							}
							// echo $amount;
							// echo "<br>";
							// echo $decimal_places;
							// echo "<br>";
							// print_r($trans_det);
							$send_money_res = $this->local_model->make_transfer($coin_name,$trans_det);
					    }
					    else
					    { 
// echo $currency_det->crypto_type_other;
//  exit();

							if($currency_det->crypto_type_other != '')
							{
								$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
								foreach($crypto_other_type_arr as $val)
								{
									if($val=='eth' && $withdraw->crypto_type == $val){
										$from_address = trim($from_address1);
										$to = trim($address);	
										$amount1 = $amount * $decimal_places;
										//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
										$GasLimit = 70000;
										//$Gwei = 30 * 1000000000;
										$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"Ethereum");
										$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
									}
									elseif($val=='bsc' && $withdraw->crypto_type == $val){


										$coin_decimal = $currency_det->bsc_currency_decimal;
										$decimal_places = coin_decimal($coin_decimal);
										$from_address = trim($from_address1);
										$to = trim($address);	
										$amount1 = $amount * $decimal_places;
										//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
										$GasLimit = 120000;
										$GasPrice = 30 * 1000000000;
										//$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"BNB");


										$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);



										// print_r($trans_det);
										// exit();
									}
									elseif($val=='tron' && $withdraw->crypto_type == $val){
										$coin_decimal = $currency_det->trx_currency_decimal;
										$decimal_places = coin_decimal($coin_decimal);
										$amount1 = $amount * $decimal_places;
										$from_address = trim($from_address1);
										$to = trim($address);
										$privateKey = getadmintronPrivate(1);
										$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),'privateKey'=>$privateKey);
										//print_r($trans_det); exit;
		
										/*$from_address = trim($from_address1);
										$to = trim($address);	
										$amount1 = $amount * $decimal_places;
										$trans_det 		= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>(float)$amount1);*/
									}
								}
								// echo $amount;
								// echo "<br>";
								// echo $decimal_places;
								// echo "<br>";
								// print_r($trans_det);
								// echo "<br/>";
								// //echo $val;
								// exit;
								$send_money_res = $this->local_model->make_transfer($coin_name,$trans_det,$withdraw->crypto_type);
							} else {
								if($currency_det->crypto_type=='eth'){
									$from_address = trim($from_address1);
									$to = trim($address);	
									$amount1 = $amount * $decimal_places;
									//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
									$GasLimit = 70000;
									//$Gwei = 30 * 1000000000;
									$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"Ethereum");
									$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
								}
								elseif($currency_det->crypto_type=='bsc'){
									$from_address = trim($from_address1);
									$to = trim($address);	
									$amount1 = $amount * $decimal_places;
									//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
									$GasLimit = 120000;
									$GasPrice = 30 * 1000000000;
									//$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"BNB");
									$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
								}
								elseif($currency_det->crypto_type=='tron'){
	
									$amount1 = $amount * $decimal_places;
									$from_address = trim($from_address1);
									$to = trim($address);
									$privateKey = getadmintronPrivate(1);
									$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),'privateKey'=>$privateKey);
									//print_r($trans_det); exit;
	
									/*$from_address = trim($from_address1);
									$to = trim($address);	
									$amount1 = $amount * $decimal_places;
									$trans_det 		= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>(float)$amount1);*/
								}
								// echo $amount;
								// echo "<br>";
								// echo $decimal_places;
								// echo "<br>";
								// print_r($trans_det);
								// echo "<br/>";
								// echo $val;
								// echo "teeeeeeeet";
								// exit();
								$send_money_res = $this->local_model->make_transfer($coin_name,$trans_det,$currency_det->crypto_type);
							}

					    }

						

						$updateData  = array('status'=>"Completed",'wallet_txid'=>$send_money_res);
						$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'crypto');
						$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
						
						////////////////////SEND EMAIL
						$ua=$this->getBrowser();
						$yourbrowser= $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'];
						$to      	= getUserEmail($fromid);
						$email_template = 34;
						$username=get_prefix().'username';
						$site_common      =   site_common();
						$currency_name = getcryptocurrency($withdraw->currency_id);
						$fb_link = $site_common['site_settings']->facebooklink;
						$tw_link = $site_common['site_settings']->twitterlink;
						$tw_link = $site_common['site_settings']->coinmarket;
						$tg_link = $site_common['site_settings']->telegramlink;
						$md_link = $site_common['site_settings']->youtubelink;
						//$md_link = $site_common['site_settings']->mediumlink;
						$ld_link = $site_common['site_settings']->linkedin_link;

						$special_vars = array(
						'###AMOUNT###' => $withdraw->transfer_amount,
						'###CURRENCY###' => $currency_name,
						'###ADDRESS###' => $withdraw->crypto_address,
						'###TX###' => $withdraw->wallet_txid
						);
						$this->email_model->sendMail($to, '', '', $email_template,$special_vars);
						/////////////////////////END SEND EMAIL
						
						// Reserve amount
						$reserve_amount = getcryptocurrencydetail($withdraw->currency_id);
						$final_reserve_amount = (float)$reserve_amount->reserve_Amount + (float)$amount;
						$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $withdraw->currency_id);


						if($coin_name !="bitcoin")
                        {
		                $admin_balance = getadminBalance(1,$withdraw->currency_id); // get admin balance
		                $admin_bal = $admin_balance - $withdraw->transfer_amount;
		                updateadminBalance(1,$withdraw->currency_id,$admin_bal); // update balance in admin wallet
		                }
                        
						if($currency_det->crypto_type_other != '')
						{
							$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
							foreach($crypto_other_type_arr as $val)
							{
								if($coin_type=="token" && $val=='eth')
								{
									$admin_bal = getadminBalance(1,2); // get admin balance
									$admin_up = $admin_bal + $withdraw->fee;
									updateadminBalance(1,2,$admin_up);
								}
								elseif($coin_type=="token" && $val=='bsc')
								{
									$admin_bal = getadminBalance(1,4); // get admin balance
									$admin_up = $admin_bal + $withdraw->fee;
									updateadminBalance(1,4,$admin_up);
								}
								elseif($coin_type=="token" && $val=='tron')
								{
									$admin_bal = getadminBalance(1,5); // get admin balance
									$admin_up = $admin_bal + $withdraw->fee;
									updateadminBalance(1,5,$admin_up);
								}
							}
						} else {
							if($coin_type=="token" && $currency_det->crypto_type=='eth')
							{
								$admin_bal = getadminBalance(1,2); // get admin balance
								$admin_up = $admin_bal + $withdraw->fee;
								updateadminBalance(1,2,$admin_up);
							}
							elseif($coin_type=="token" && $currency_det->crypto_type=='bsc')
							{
								$admin_bal = getadminBalance(1,4); // get admin balance
								$admin_up = $admin_bal + $withdraw->fee;
								updateadminBalance(1,4,$admin_up);
							}
							elseif($coin_type=="token" && $currency_det->crypto_type=='tron')
							{
								$admin_bal = getadminBalance(1,5); // get admin balance
								$admin_up = $admin_bal + $withdraw->fee;
								updateadminBalance(1,5,$admin_up);
							}
							else
							{
								$admin_bal = getadminBalance(1,$withdraw->currency_id); // get admin balance
								$admin_up = $admin_bal + $withdraw->fee;
								updateadminBalance(1,$withdraw->currency_id,$admin_up);

							}
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


						$trans_datas = array(
				                'userid'=>$withdraw->user_id,
				                'crypto_address'=>$user_address,
				                'type'=>'userwithdraw',
				                'amount'=>(float)$withdraw->transfer_amount,
				                'currency_symbol'=>$coin_symbol,
				                'status'=>'Completed',
				                'date_created'=>date('Y-m-d H:i:s'),
				                'currency_id'=>$withdraw->currency_id,
				                'txn_id'=>$withdraw->trans_id
				                );
				        $insert = $this->common_model->insertTableData('admin_wallet_logs',$trans_datas);
						//exit;

						if($update){
						$this->session->set_flashdata('success', 'Successfully confirmed the withdraw request');
						admin_redirect('withdraw/crypto_withdraw', 'refresh');
						}
						else
						{
							$this->session->set_flashdata('error', 'Some error occured please try again later !!');
							admin_redirect('withdraw/crypto_withdraw', 'refresh');
						}
					}
					else
					{
						$this->session->set_flashdata('error', 'Not enough balance to proceed the withdraw request amount');
						admin_redirect('withdraw/crypto_withdraw', 'refresh');
					}
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'Invalid withdraw confirmation');
			admin_redirect('withdraw/crypto_withdraw', 'refresh');
		}
	}




	// function withdraw_coin_confirm($id)
// 	{
        
	
// 		 $id = base64_decode($id);
// 		  $this->load->library('session'); 

// 		  // echo "test"; 

// 		  // exit();

// 		 $sessionvar=$this->session->userdata('loggeduser');

// 		// exit();

// 		if (!$sessionvar) {
// 			admin_redirect('admin', 'refresh');
// 		}
		
// 		$data['site_common'] = site_common();
// 		$site = $data['site_common'];
// 		$data['site_settings'] = $site['site_settings'];

	
// 		// $admin_withdraw_confirmation = $data['site_settings']->admin_withdraw_confirmation;

// 		// // echo "test";

// 		// // exit();
		
// 		// if($admin_withdraw_confirmation==0)
// 		// {
// 		// 	$this->session->set_flashdata('error', 'Invalid activity in our site');
// 		// 	admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 		// }


// 		// print_r($id); die;

// 		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw','currency_type'=>'crypto', 'user_status'=>'Completed'));

// 		// print_r($isValids);
// 		// exit();



//   //        echo $this->db->last_query();

// 		// exit();
// 		$isValid = $isValids->num_rows();
// 		$withdraw = $isValids->row();

//   //   print_r($withdraw);

// 		// exit();
        
//         $eth_id = $this->common_model->getTableData("currency",array("currency_symbol"=>"ETH"))->row('id');
//         $eth_admin_balance = getadminbalance(1,$eth_id);
		

// 		$bnb_id = $this->common_model->getTableData("currency",array("currency_symbol"=>"BNB"))->row('id');
//         $bnb_admin_balance = getadminbalance(1,$bnb_id);

//         $tron_id = $this->common_model->getTableData("currency",array("currency_symbol"=>"TRX"))->row('id');
//         $tron_admin_balance = getadminbalance(1,$tron_id);
		
// 		if($isValid > 0)
// 		{


// // echo "test";

// // exit();

// 			 $fromid 	= $withdraw->user_id;
// 			 $fromuser  = $this->common_model->getTableData('users',array('id'=>$fromid))->row();
// 			 //print_r($fromuser);
// 			 //$fromacc   = getUserEmail($fromid);

// 			 $fromacc = getAddress($fromid,'40');

// 			if($withdraw->status=='Completed')

// 			{

// 			// 				echo "test";

// 			// exit();
// 				$this->session->set_flashdata('error', 'Your withdraw request already confirmed');
// 				admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 			}
// 			else if($withdraw->status=='Cancelled')
// 			{

// 				// echo "test_new";
// 				// exit();
// 				$this->session->set_flashdata('error', 'Your withdraw request already cancelled');
// 				admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 			}
// 			else {

//    // echo "test_therr";
//    // exit();

// 					$amount 		= $withdraw->transfer_amount;
// 					$address 		= $withdraw->crypto_address;
// 					$currency 		= $withdraw->currency_id;
// 					$tagid = $withdraw->destination_tag;



// 					$coin_name  	= getcryptocurrencys($currency);
// 					$coin_symbol  	= getcryptocurrency($currency);
// 					// $coin_name = strtolower($coin_name);
// 					// $coin_name = str_replace(" ","",$coin_name);
					
// 					/*New code 26-6-18*/
// 					//$from_address1 = getAddress($withdraw->user_id,$withdraw->currency_id);
// 					$currency_det = getcryptocurrencydetail($currency);


// 					// print_r($currency_det);

// 					// exit();


          

// 					// echo $currency_det->crypto_type_other;
// 					// exit();




// 					if($currency_det->crypto_type_other != '')
// 					{
// 						$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
// 						foreach($crypto_other_type_arr as $val)
// 						{
// 							if($val=='eth' && $withdraw->crypto_type == $val) // TOKEN
// 							{   
// 								$mini_balance = "0.005";
// 								if($eth_admin_balance <= $mini_balance)
// 								{
// 								$this->session->set_flashdata('error','Your Ethereum Balance is low so you did not able to withdraw');
// 									// echo "test_new";

// 									// exit();
// 									admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 								}
// 								else
// 								{

// 								}
// 							}


// 							if($val=='bsc' && $withdraw->crypto_type == $val) // TOKEN
// 							{
// 								$mini_balance = "0.005";
// 								if($bnb_admin_balance <= $mini_balance)
// 								{
// 									$this->session->set_flashdata('error','Your BNB Balance is low so you did not able to withdraw');
// 									admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 								}
// 								else
// 								{

// 								}
// 							}

// 							if($val=='tron' && $withdraw->crypto_type == $val) // TOKEN
// 							{
// 								$mini_balance = "2";
// 								if($tron_admin_balance <= $mini_balance)
// 								{
// 									$this->session->set_flashdata('error','Your TRX Balance is low so you did not able to withdraw');
// 									admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 								}
// 								else
// 								{

// 								}
// 							}
// 						}
// 					} else {



// 						// echo "test_newd";
// 						// exit();


// 						if($currency_det->crypto_type=='eth') // TOKEN
// 						{   
// 							$mini_balance = "0.005";
// 							if($eth_admin_balance <= $mini_balance)
// 							{
// 								$this->session->set_flashdata('error','Your Ethereum Balance is low so you did not able to withdraw');
// 								admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 							}
// 							else
// 							{

// 							}
// 						}


// 						if($currency_det->crypto_type=='bsc') // TOKEN
// 						{
// 							$mini_balance = "0.005";
// 							if($bnb_admin_balance <= $mini_balance)
// 							{
// 								$this->session->set_flashdata('error','Your BNB Balance is low so you did not able to withdraw');
// 								admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 							}
// 							else
// 							{

// 							}
// 						}

// 						if($currency_det->crypto_type=='tron') // TOKEN
// 						{
// 							$mini_balance = "2";
// 							if($tron_admin_balance <= $mini_balance)
// 							{
// 								$this->session->set_flashdata('error','Your TRX Balance is low so you did not able to withdraw');
// 								admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 							}
// 							else
// 							{

// 							}
// 						}
// 					}


//                  $from_address1 = getadminAddress(1,$coin_symbol);

          
// 				 $user_address = getAddress($withdraw->user_id,$withdraw->currency_id);		
					
// 					/*End 26-6-18*/
// 					// echo "<br>";
// 					// echo "Coin Name : - ".$coin_name;
// 					// echo "<br>";
// 					// echo "From Address : - ".$from_address1;
// 					// echo "<br>";
// 					// echo "To Address : - ".$address;
// 					// echo "<br>";

//      //          echo "test_new";

// 					// echo $currency_det->crypto_type_other;
// 				 // exit();
// 					if($currency_det->crypto_type_other != '')
// 					{
// 						$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
                      
//       //                    print_r($crypto_other_type_arr);
// 						// exit();
// 						foreach($crypto_other_type_arr as $val)
// 						{
// 							if($val=="tron" && $withdraw->crypto_type == $val)
// 							{


// 								$private_key = getadmintronPrivate(1);
// 								$crypto_type_other = array('crypto'=>$val,'tron_private'=>$private_key);

                              
// 								$wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1,$crypto_type_other);
// 							}
// 							else
// 							{


// 								// echo $val;
// 								// echo "test";
// 								// exit();
// 							  $crypto_type_other = array('crypto'=>$val);

// 						  if($val== 'bsc'){

// 						  	// echo "test_new";
// 						  	// exit();
// 								$admin_addres_new=getadminAddress(1,'BNB');

// 							}else if($val == 'eth'){
// 								$admin_addres_new=getadminAddress(1,'ETH');

// 							}else{


// 								$admin_addres_new=getadminAddress(1,'TRX');

// 								// exit();


// 								// print_r($crypto_type_other);
// 								// exit();

// 							}


// 								// echo $coin_name;
// 								// echo "------";

// 								// echo $from_address1;
// 								// echo "------";
//         //                            $from_address1='0x28fddae2c3a6d9dcbb0b4e1d113e416256049e67';

// 								// print_r($crypto_type_other);

// 								$wallet_bal = $this->local_model->wallet_balance($coin_name,$admin_addres_new,$crypto_type_other);


// 								if($wallet_bal > 0){
// 									break;
// 								}

							
			
// 							}
// 						}


// 					} else {
// 						if($currency_det->crypto_type=="tron")
// 						{
// 							$private_key = getadmintronPrivate(1);
// 							$crypto_type_other = array('crypto'=>$currency_det->crypto_type,'tron_private'=>$private_key);
// 							$wallet_bal 	= $this->local_model->wallet_balance($coin_name, $from_address1,$crypto_type_other);
// 						}
// 						else
// 						{


//                              $currency_det->crypto_type;

//          //                      echo"---";
// 							  // echo "test_new";

// 							  // exit();




// 							$crypto_type_other = array('crypto'=>$currency_det->crypto_type);


// 							//  print_r($crypto_type_other);



// 							// exit();

// 						    $wallet_bal = $this->local_model->wallet_balance($coin_name,$admin_addres_new,$crypto_type_other);
                              
//        //                        echo "-----";

//        //                        echo $wallet_bal;

// 							// exit();
// 						}
// 					}

// 					//$wallet_bal = 100;
                    
//                   $coin_type = $currency_det->coin_type;
//                   $coin_decimal = $currency_det->currency_decimal;
//                   $decimal_places = coin_decimal($coin_decimal);

             
// 					// print_r($wallet_bal);  die();
// 					//$address
// 				   $wallet_bal = number_format((float)$wallet_bal,8,'.','');	

// 			       $amount = number_format($amount,8,'.','');

			      	
// 			      	// echo "Amount ".$amount;
// 			      	// echo "<br>";

//                    //$wallet_bal = getBalance($withdraw->user_id,$currency,'crypto');
// 					/*echo "From Balance : ".$wallet_bal;
// 					echo "<br>";
// 					echo "Withdraw Amount :".$amount; exit;*/
// 					// echo $coin_name;
// 					// exit();

// 					 // echo $wallet_bal;

// 					 // echo"------";

// 					 // echo $amount;

// 					 // exit();
                 
// 					if($wallet_bal >= $amount)
// 					{

// 						if($coin_type=="coin")
// 						{


// 						switch ($coin_name) 
// 						{
// 							case 'Ethereum':
// 								 $from_address = trim($from_address1);
// 								 $to = trim($address);	
// 				                $amount1 = $amount * 1000000000000000000;
// 				               // $amount1 =  rtrim(sprintf("%u", $amounts), ".");
// 				                $GasLimit = 21000;

// 				               // exit();
// 								 $Gwei = $this->check_ethereum_functions('eth_gasPrice',"Ethereum");


// 								$GasPrice = $Gwei;
// 								$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
// 								//$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1);
// 								/*$trans_det 		= array('address'=>$address,'amount'=>(float)$amount,'comment'=>'Admin Confirms Withdraw');*/
// 								break;

// 								case 'BNB':
// 								 $from_address = trim($from_address1);
// 								 $to = trim($address);	
// 				                $amount1 = $amount * 1000000000000000000;
// 				                $GasLimit = 120000;

// 								/*$Gwei = $this->check_ethereum_functions('eth_gasPrice',"BNB");
// 								$GasPrice = $Gwei;*/
// 								$GasPrice = 30000000000;
								
// 								$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
								
// 								break;

// 								case 'Tron':
// 								$from_address = trim($from_address1);
// 								$to = trim($address);	
// 				                $amount1 = $amount * 1000000;
// 				                $privateKey = getadmintronPrivate(1);
// 								$trans_det 		= array('fromAddress'=>$from_address,'toAddress'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),"privateKey"=>$privateKey);
//                                //print_r($trans_det); exit;

// 								break;

// 								case "Ripple":
// 								$xrp_tag_det = $this->common_model->getTableData('crypto_address', array('user_id' => $fromid))->row();
// 								$from_address = trim($from_address1);
// 									$to = trim($address);
// 								$trans_det = array('fromacc' => $from_address, 'toaddress' => $to, 'amount' => (float) $amount, 'tagid' => $xrp_tag_det->payment_id, 'destag' => $tagid, 'secret' => $xrp_tag_det->auto_gen, 'comment' => 'Admin Confirms Withdraw', 'comment_to' => 'Completed');

// 								break;	
														
// 							default:
// 								$trans_det 		= array('address'=>$address,'amount'=>(float)$amount,'comment'=>'Admin Confirms Withdraw');

// 								//$trans_det 		= array('fromacc'=>$fromacc,'toaddress'=>$address,'amount'=>(float)$amount,'minconf'=>1,'comment'=>'Admin Confirms Withdraw','comment_to'=>'Completed');
// 								break;
// 						}
// 					    }
// 					    else
// 					    { 

// 					    	// echo "test_test";
// 					    	// exit();


// 							if($currency_det->crypto_type_other != '')
// 							{

                        

// 								$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
// 								foreach($crypto_other_type_arr as $val)
// 								{
// 									if($val=='eth'){


// 										$from_1=getadminAddress(1,'ETH');
// 										 $from_address = trim($from_1);

// 										$to = trim($address);	
// 										$amount1 = $amount * $decimal_places;
// 										//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
// 										$GasLimit = 70000;
// 										//$Gwei = 30 * 1000000000;
// 										$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"Ethereum");
// 										$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
// 									}
// 									else if($val=='bsc'){
//                                        echo  $from_2=getadminAddress(1,'BNB');

//                                        exit();
// 										$from_address = trim($from_2);
// 										$to = trim($address);	
// 										$amount1 = $amount * $decimal_places;
// 										//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
// 										$GasLimit = 120000;
// 										$GasPrice = 30 * 1000000000;
// 										//$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"BNB");
// 										$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
// 									}
// 									else if($val=='tron'){
		
// 										$amount1 = $amount * $decimal_places;
// 										$from_address = trim($from_address1);
// 										$to = trim($address);
// 										$privateKey = getadmintronPrivate(1);
// 										$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),'privateKey'=>$privateKey);
// 										//print_r($trans_det); exit;
		
// 										/*$from_address = trim($from_address1);
// 										$to = trim($address);	
// 										$amount1 = $amount * $decimal_places;
// 										$trans_det 		= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>(float)$amount1);*/
// 									}
// 								}

// 							} else {
// 								if($currency_det->crypto_type=='eth'){
// 									$from_address = trim($from_address1);
// 									$to = trim($address);	
// 									$amount1 = $amount * $decimal_places;
// 									//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
// 									$GasLimit = 70000;
// 									//$Gwei = 30 * 1000000000;
// 									$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"Ethereum");
// 									$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
// 								}
// 								elseif($currency_det->crypto_type=='bsc'){
// 									$from_address = trim($from_address1);
// 									$to = trim($address);	
// 									$amount1 = $amount * $decimal_places;
// 									//$amount1 =  rtrim(sprintf("%u", $amounts), ".");
// 									$GasLimit = 120000;
// 									$GasPrice = 30 * 1000000000;

// 									//$GasPrice = $this->check_ethereum_functions('eth_gasPrice',"BNB");
// 									$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);
// 								}
// 								elseif($currency_det->crypto_type=='tron'){
	
// 									$amount1 = $amount * $decimal_places;
// 									$from_address = trim($from_address1);
// 									$to = trim($address);
// 									$privateKey = getadmintronPrivate(1);
// 									$trans_det 	= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>rtrim(sprintf("%.0f", $amount1), "."),'privateKey'=>$privateKey);
// 									//print_r($trans_det); exit;
	
// 									/*$from_address = trim($from_address1);
// 									$to = trim($address);	
// 									$amount1 = $amount * $decimal_places;
// 									$trans_det 		= array('owner_address'=>$from_address,'to_address'=>$to,'amount'=>(float)$amount1);*/
// 								}
// 							}

// 					    }

// 						echo $amount;
// 						echo "<br>";
// 						echo $decimal_places;
// 						echo "<br>";

// 						echo '<pre>';print_r($trans_det);
				        
	
// 						$send_money_res = $this->local_model->make_transfer($coin_name,$trans_det);



// 						// print_r($send_money_res);

// 						// exit();

// 						$updateData  = array('status'=>"Completed",'wallet_txid'=>$send_money_res);
// 						$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'crypto');
// 						$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
						
// 						////////////////////SEND EMAIL
// 						$ua=$this->getBrowser();
// 						$yourbrowser= $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'];
// 						$to      	= getUserEmail($fromid);
// 						$email_template = 34;
// 						$username=get_prefix().'username';
// 						$site_common      =   site_common();
// 						$currency_name = getcryptocurrency($withdraw->currency_id);
// 						$fb_link = $site_common['site_settings']->facebooklink;
// 						$tw_link = $site_common['site_settings']->twitterlink;
// 						$tw_link = $site_common['site_settings']->coinmarket;
// 						$tg_link = $site_common['site_settings']->telegramlink;
// 						$md_link = $site_common['site_settings']->youtubelink;
// 						//$md_link = $site_common['site_settings']->mediumlink;
// 						$ld_link = $site_common['site_settings']->linkedin_link;

// 						$special_vars = array(
// 						'###AMOUNT###' => $withdraw->transfer_amount,
// 						'###CURRENCY###' => $currency_name,
// 						'###ADDRESS###' => $withdraw->crypto_address,
// 						'###TX###' => $withdraw->wallet_txid
// 						);
// 						$this->email_model->sendMail($to, '', '', $email_template,$special_vars);
// 						/////////////////////////END SEND EMAIL
						
// 						// Reserve amount
// 						$reserve_amount = getcryptocurrencydetail($withdraw->currency_id);
// 						$final_reserve_amount = (float)$reserve_amount->reserve_Amount + (float)$amount;
// 						$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $withdraw->currency_id);


// 						if($coin_name !="bitcoin")
//                         {
// 		                $admin_balance = getadminBalance(1,$withdraw->currency_id); // get admin balance
// 		                $admin_bal = $admin_balance - $withdraw->transfer_amount;
// 		                updateadminBalance(1,$withdraw->currency_id,$admin_bal); // update balance in admin wallet
// 		                }
                        
// 						if($currency_det->crypto_type_other != '')
// 						{
// 							$crypto_other_type_arr = explode('|',$currency_det->crypto_type_other);
// 							foreach($crypto_other_type_arr as $val)
// 							{
// 								if($coin_type=="token" && $val=='eth')
// 								{
// 									$admin_bal = getadminBalance(1,2); // get admin balance
// 									$admin_up = $admin_bal + $withdraw->fee;
// 									updateadminBalance(1,2,$admin_up);
// 								}
// 								elseif($coin_type=="token" && $val=='bsc')
// 								{
// 									$admin_bal = getadminBalance(1,4); // get admin balance
// 									$admin_up = $admin_bal + $withdraw->fee;
// 									updateadminBalance(1,4,$admin_up);
// 								}
// 								elseif($coin_type=="token" && $val=='tron')
// 								{
// 									$admin_bal = getadminBalance(1,5); // get admin balance
// 									$admin_up = $admin_bal + $withdraw->fee;
// 									updateadminBalance(1,5,$admin_up);
// 								}
// 							}
// 						} else {
// 							if($coin_type=="token" && $currency_det->crypto_type=='eth')
// 							{
// 								$admin_bal = getadminBalance(1,2); // get admin balance
// 								$admin_up = $admin_bal + $withdraw->fee;
// 								updateadminBalance(1,2,$admin_up);
// 							}
// 							elseif($coin_type=="token" && $currency_det->crypto_type=='bsc')
// 							{
// 								$admin_bal = getadminBalance(1,4); // get admin balance
// 								$admin_up = $admin_bal + $withdraw->fee;
// 								updateadminBalance(1,4,$admin_up);
// 							}
// 							elseif($coin_type=="token" && $currency_det->crypto_type=='tron')
// 							{
// 								$admin_bal = getadminBalance(1,5); // get admin balance
// 								$admin_up = $admin_bal + $withdraw->fee;
// 								updateadminBalance(1,5,$admin_up);
// 							}
// 							else
// 							{
// 								$admin_bal = getadminBalance(1,$withdraw->currency_id); // get admin balance
// 								$admin_up = $admin_bal + $withdraw->fee;
// 								updateadminBalance(1,$withdraw->currency_id,$admin_up);

// 							}
// 						}


// 						// add to transaction history
// 						$trans_data = array(
// 							'userId'=>$withdraw->user_id,
// 							'type'=>'Withdraw',
// 							'currency'=>$withdraw->currency_id,
// 							'amount'=>$withdraw->amount,
// 							'profit_amount'=>$withdraw->fee,
// 							'comment'=>'Withdraw #'.$withdraw->trans_id,
// 							'datetime'=>date('Y-m-d h:i:s'),
// 							'currency_type'=>'crypto',
// 						);
// 						$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);


// 						$trans_datas = array(
// 				                'userid'=>$withdraw->user_id,
// 				                'crypto_address'=>$user_address,
// 				                'type'=>'userwithdraw',
// 				                'amount'=>(float)$withdraw->transfer_amount,
// 				                'currency_symbol'=>$coin_symbol,
// 				                'status'=>'Completed',
// 				                'date_created'=>date('Y-m-d H:i:s'),
// 				                'currency_id'=>$withdraw->currency_id,
// 				                'txn_id'=>$withdraw->trans_id
// 				                );
// 				        $insert = $this->common_model->insertTableData('admin_wallet_logs',$trans_datas);
// 						//exit;

// 						if($update){
// 						$this->session->set_flashdata('success', 'Successfully confirmed the withdraw request');
// 						admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 						}
// 						else
// 						{
// 							$this->session->set_flashdata('error', 'Some error occured please try again later !!');
// 							admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 						}
// 					}
// 					else
// 					{
// 						// echo "not have fund";
// 						// exit();
// 						$this->session->set_flashdata('error', 'Not enough balance to proceed the withdraw request amount');
// 						admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 					}
// 			}
// 		}
// 		else
// 		{
// 			$this->session->set_flashdata('error', 'Invalid withdraw confirmation');
// 			admin_redirect('withdraw/crypto_withdraw', 'refresh');
// 		}
// 	}



	function withdraw_coin_cancel($id)
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$data['site_common'] = site_common();
		$site = $data['site_common'];
		$data['site_settings'] = $site['site_settings'];
		// $admin_withdraw_confirmation = $data['site_settings']->admin_withdraw_confirmation;
		// if($admin_withdraw_confirmation==0)
		// {
		// 	$this->session->set_flashdata('error', 'Invalid activity in our site');
		// 	admin_redirect('withdraw/crypto_withdraw', 'refresh');
		// }
		$id = base64_decode($id);
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'user_status'=>'Completed','currency_type'=>'crypto'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if($isValid > 0)
		{
			if($withdraw->status=='Completed')
			{
				$this->session->set_flashdata('error', 'Your withdraw request already confirmed');
				admin_redirect('withdraw/crypto_withdraw', 'refresh');
			}
			else if($withdraw->status=='Cancelled')
			{
				 $this->session->set_flashdata('error', 'Your withdraw request already cancelled');

				admin_redirect('withdraw/crypto_withdraw', 'refresh');
			}
			else {
					
				
				$currency 	= $withdraw->currency_id;
				$amount     = $withdraw->amount;
				// Update Balance
				$balance 		= getBalance($withdraw->user_id,$currency,'crypto'); // get user bal
				$finalbalance 	= $balance+$amount; // bal + dep amount
				$updatebalance  = updateBalance($withdraw->user_id,$currency,$finalbalance,'crypto'); 

				// Update table
				$updateData['status'] = 'Cancelled';
				$condition = array('trans_id' => $id,'type' => 'withdraw','currency_type'=>'crypto','user_status'=>'Completed');
				$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
				$this->session->set_flashdata('success', 'Successfully cancelled the withdraw request.');
				admin_redirect('withdraw/crypto_withdraw', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'Invalid withdraw confirmation');
			admin_redirect('withdraw/crypto_withdraw', 'refresh');
		}
	}
    
    function userbank_ajax()
    {
    	$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$draw = $this->input->get('draw');
		$start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
        $search = $search['value'];
        if($search=='open')
        {
        	$st = 0;
        }
        elseif($search=='Replied' || $search=='replied')
        {
        	$st = 1;
        }
        else
        {
        	$st = $search;
        }
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
            1=>'user_id',
            2=>'currency',           
            3=>'bank_name',
            4=>'bank_account_number',
            5=>'bank_account_name',
            6=>'status',
            7=>'status'
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
        $like = '';

        

        if(!empty($search))
        { 
            $like = " WHERE c.bidex_fname LIKE '%".$search."%' OR a.bank_name LIKE '%".$search."%' OR a.bank_account_number LIKE '%".$search."%' OR a.bank_account_name LIKE '%".$search."%' OR a.status LIKE '%".$st."%'";

$query = "SELECT a.*, c.bidex_fname as username FROM bidex_user_bank_details as a JOIN bidex_users as c ON a.user_id = c.id ".$like." ORDER BY a.id DESC LIMIT ".$start.",".$length;


$countquery = $this->db->query("SELECT a.*, c.bidex_fname as username FROM bidex_user_bank_details as a JOIN bidex_users as c ON a.user_id = c.id ".$like." ORDER BY a.id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, c.bidex_fname as username FROM bidex_user_bank_details as a JOIN bidex_users as c ON a.user_id = c.id  ORDER BY a.id DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, c.bidex_fname as username FROM bidex_user_bank_details as a JOIN bidex_users as c ON a.user_id = c.id  ORDER BY a.id DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				$edit = '<a href="' . admin_url() . 'admin/view/' . $result->id . '" data-placement="top" data-toggle="popover" data-content="Edit this Bank details." class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
				if($result->status=='Verified'){
					$status = '<label class="label label-info">Verified</label>';
				}
				elseif($result->status=='Pending'){
					$status = '<label class="label label-primary">Pending</label>';
				}
				elseif($result->status=='Rejected'){
					$status = '<label class="label label-info">Rejected</label>';
				}

				

				$currency_symbol = getfiatcurrency($result->currency);		
				
					$data[] = array(
					    $i, 
						$result->username,
						//$currency_symbol,
						$result->bank_name,
						$result->bank_account_number,
						$result->bank_account_name,
						$status,
						$edit
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


	function user_bank_details()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

	    $data['view'] 				= 'view_all';
	    $data['title'] 				= 'User Bank Details';
	    $data['meta_keywords'] 		= 'User Bank Details';
	    $data['meta_description'] 	= 'User Bank Details';
	    $data['main_content'] 		= 'admin/user_bank_details';

	    $this->load->view('administrator/admin_template', $data); 
	}

	//user bank details view in admin side
	function view($id)
	{
		$sessionvar  = $this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

	    $data['currency'] 			= $this->common_model->getTableData('currency', array('status' => 1,'type'=>'fiat'))->result();
	    $data['user_bankdetails'] 	= $this->common_model->getTableData('user_bank_details', array('id'=>$id))->row();

	    $data['prefix'] 			= get_prefix();
	    $data['action'] 			= admin_url() . 'admin/user_bank_status/' . $id;

	    $data['title'] 				= 'Deposit Management';
	    $data['meta_keywords'] 		= 'Deposit Management';
	    $data['meta_description'] 	= 'Deposit Management';
	    $data['main_content'] 		= 'admin/user_bank_details';
	    $data['view'] 				= 'view';
	    $this->load->view('administrator/admin_template', $data); 
	}

	// user bank Status change
	function user_bank_status($id, $status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('user_bank_details');
		}

		$data['user_bankdetails'] 	= $this->common_model->getTableData('user_bank_details', array('id'=>$id))->row();

		$data['site_common'] 			= site_common();
		$site 							= $data['site_common'];
		$data['site_settings'] 			= $site['site_settings'];

		$id = base64_decode($id);

		$isValids = $this->common_model->getTableData('user_bank_details', array('id' => $id));
		$isValid = $isValids->num_rows();
		$bank = $isValids->row();

		if($isValid > 0)
		{
			if($bank->status =="Verified")
			{
				$this->session->set_flashdata('error', 'Your user bank details request already confirmed');
				admin_redirect('admin/user_bank_details', 'refresh');
			}
			else if($bank->status =="Rejected")
			{
				$this->session->set_flashdata('error', 'Your user bank details request already cancelled');
				admin_redirect('admin/user_bank_details', 'refresh');
			} 
			else 
			{		
				
				if($status==1)
			    {
				$updateData['status'] = "Verified";			
				$condition = array('id' => $id);
				$update = $this->common_model->updateTableData('user_bank_details', $condition, $updateData);
				if($update)
				{
                $email = getUserEmail($bank->user_id);
                $userdetail = getUserDetails($bank->user_id);
                if($userdetail->usertype==1)
                {
                  $username = $userdetail->bidex_username;
                }
                else
                {
                  $username = $userdetail->company_name;
                }
				$email_template = 'Bank_Complete';
                $site_common      =   site_common();
				$special_vars = array(
				'###USERNAME###' => $username
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);


				}
				$this->session->set_flashdata('success', 'Successfully verified the user bank detail request.');
				admin_redirect('admin/user_bank_details', 'refresh');
			    }
			    else
			    {
			    $updateData['status'] = "Rejected";
				$updateData['reason'] = ($this->input->post())?$this->input->post('mail_content'):'-';				
				$condition = array('id' => $id);
				$update = $this->common_model->updateTableData('user_bank_details', $condition, $updateData);
                if($update)
                {

                $email = getUserEmail($bank->user_id);
                $userdetail = getUserDetails($bank->user_id);
                if($userdetail->usertype==1)
                {
                  $username = $userdetail->bidex_username;
                }
                else
                {
                  $username = $userdetail->company_name;
                }
				$email_template = 'Bank_Reject';
                $site_common      =   site_common();
				$special_vars = array(
				'###USERNAME###' => $username,
				'###REASON###' => $this->input->post('mail_content')
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);

                }
				$this->session->set_flashdata('error', 'Successfully rejected the user bank detail request.');
				admin_redirect('admin/user_bank_details', 'refresh');

			    }			
				
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'Invalid Bank detail confirmation');
			admin_redirect('admin/user_bank_details', 'refresh');
		}		
	}

	function withdraw_admin_confirm($id)

	{	


		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar)
		{
		 admin_redirect('admin', 'refresh');
		}
		$id = base64_decode($id);
	    $isValids = $this->common_model->getTableData('admin_transactions', array('trans_id' => $id, 'type' => 'withdraw'));
	    $isValid = $isValids->num_rows();
	    $withdraw = $isValids->row();


	    if ($isValid > 0) {
	        $currency_id = $withdraw->currency_id;
	        $user_id = $withdraw->user_id;
	        $fromacc = getadminAddress($user_id, $currency_id);
	        if ($withdraw->status == 'Completed') {
	            $this->session->set_flashdata('error', 'Your withdraw request already confirmed');
	            admin_redirect('admin_wallet', 'refresh');
	        } else if ($withdraw->status == 'Cancelled') {
	            $this->session->set_flashdata('error', 'Your withdraw request already cancelled');
	            admin_redirect('admin_wallet', 'refresh');
	        } else {
	            $amount = $withdraw->amount;
	            $address = $withdraw->crypto_address;
	            $currency = $withdraw->currency_id;
	            $coin_name      = getcryptocurrencys($currency);
	            $coin_new_name = $coin_name;
	            $coin_symbol = getcryptocurrency($currency);
	            $coin_name = str_replace(" ", "", $coin_name);
	            $from_address1 = getadminAddress($user_id, $currency_id);
	            $currency_det = getcryptocurrencydetail($currency_id);
	            $coin_type = $currency_det->coin_type;
                $decimal_places = coin_decimal($currency_det->currency_decimal);
                
               
	            if ($coin_name != "") {

	               $wallet_bal = $this->local_model->wallet_balance($coin_name, $from_address1);
	                
	                if ($wallet_bal >= $amount) 
	                {/**/
	                    if($coin_type=="coin")
						{
		                    switch ($coin_name) 
		                    {
		                        case 'Ethereum':
		                            $from_address = trim($from_address1);
		                            $to = trim($address);
		                            $amount1 = $amount * 1000000000000000000;
		                            $GasLimit = 21000;
		                            $Gwei = 30 * 1000000000;
		                            $GasPrice = $this->check_ethereum_functions('eth_gasPrice');
		                            $trans_det = array('from' => $from_address, 'to' => $to, 'value' => (float) $amount1, 'gas' => (float) $GasLimit, 'gasPrice' => (float) $GasPrice);
		       
		                            break;
		                        default:
		                            $trans_det = array('address' => $address, 'amount' => (float) $amount, 'comment' => 'Admin Confirms Withdraw');
		                            break;
		                    }
	                    }
	                    else
					    {
							$from_address = trim($from_address1);
							$to = trim($address);	
			                $amounts = $amount * $decimal_places;
			                $GasLimit = 70000;
							$Gwei = 30 * 1000000000;
							$GasPrice = $this->check_ethereum_functions('eth_gasPrice');
							$trans_det 		= array('from'=>$from_address,'to'=>$to,'value'=>(float)$amount1,'gas'=>(float)$GasLimit,'gasPrice'=>(float)$GasPrice);

					    }
	                    
	                     $send_money_res = $this->local_model->make_transfer($coin_name, $trans_det);
	                     if($send_money_res!="" || $send_money_res!="error")
	                     {

	                        $updateData = array('status' => "Completed", 'hash_txid' => $send_money_res,'description' => $send_money_res);
	                        $condition = array('trans_id' => $id, 'type' => 'Withdraw');
	                        $update = $this->common_model->updateTableData('admin_transactions', $condition, $updateData);

	                        if ($update) {
	                            $this->session->set_flashdata('success', "Successfully Withdraw Amount");
	                            admin_redirect('admin_wallet', 'refresh');
	                        } else {
	                           $this->session->set_flashdata('error', "Error Withdraw Amount");
	                           admin_redirect('admin_wallet', 'refresh');
	                        }
	                     }
	                     else
	                     {
	                         $this->session->set_flashdata('error', "Error Withdraw Amount");
	                         admin_redirect('admin_wallet', 'refresh');
	                     }
	                    
	                } else {
	                    $this->session->set_flashdata('error', 'Not enough balance to proceed the withdraw request amount');
	                    admin_redirect('admin_wallet', 'refresh');
	                }
	            }
	        }
	    }
                                

	}

	function withdraw_admin_cancel($id)
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$id = base64_decode($id);

		$isValids = $this->common_model->getTableData('admin_transactions', array('trans_id' => $id, 'type' =>'withdraw'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();

		if($isValid > 0)
		{
			if($withdraw->status=='Completed')
			{
				$this->session->set_flashdata('error', 'Your withdraw request already confirmed');
				admin_redirect('admin_wallet', 'refresh');
			}
			else if($withdraw->status=='Cancelled')
			{
				 $this->session->set_flashdata('error', 'Your withdraw request already cancelled');

				admin_redirect('admin_wallet', 'refresh');
			}
			else {
					
				
				$currency 	= $withdraw->currency_id;
				$amount     = $withdraw->amount;
				// Update Balance
				$balance 		= getadminBalance($withdraw->user_id,$currency); // get admin bal
				$finalbalance 	= $balance+$amount; // bal + dep amount
				$updatebalance  = updateadminBalance($withdraw->user_id,$currency,$finalbalance); 

				// Update table
				$updateData['status'] = 'Cancelled';
				$condition = array('trans_id' => $id,'type' => 'withdraw');
				$update = $this->common_model->updateTableData('admin_transactions', $condition, $updateData);
				$this->session->set_flashdata('success', 'Successfully cancelled the withdraw request.');
				admin_redirect('admin_wallet', 'refresh');
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'Invalid withdraw confirmation');
			admin_redirect('admin_wallet', 'refresh');
		}
	}

	function login_history_ajax() {

		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

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
            1=>'admin_email',
            2=>'ip_address',
            3=>'browser_name',
            4=>'activity',
            5=>'date'
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

            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                	
                    $this->db->like($sterm,$search);
                }
                else
                {
                	
                    $this->db->or_like($sterm,$search);

                }
                $x++;
            }                 
        }
        $this->db->limit($length,$start);
        $this->db->where(array('admin_id'=>$sessionvar));
        $login_history = $this->db->get("admin_activity");

		$num_rows = $this->common_model->getTableData('admin_activity',array('admin_id' => $sessionvar),'','','','','','')->num_rows();

		
		foreach($login_history->result() as $login){
			$i++;

			$data[] = array($i, 
							decryptIt($login->admin_email),
							$login->ip_address,
							$login->browser_name,
							$login->activity,
							gmdate("d-m-Y h:i a", $login->date));

		}
		$output = array(
            "draw" => $draw,
            "recordsTotal" => $num_rows,
            "recordsFiltered" => $num_rows,
            "data" => $data
        );
		echo json_encode($output);

		
	}

function kyc_settings() {

	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	if(isset($_POST['kyc_level1'])) {

		unset($_POST['kyc_level1']);
		$level1 = $this->common_model->updateTableData('kyc_settings',array('id'=>1),$_POST);
		$this->session->set_flashdata('success', 'Verified updated successfully');
	} else if(isset($_POST['kyc_level2'])) { 
		unset($_POST['kyc_level2']);
		$level2 = $this->common_model->updateTableData('kyc_settings',array('id'=>2),$_POST);
		$this->session->set_flashdata('success', 'Verified Plus updated successfully');
	}

	$data['action'] = admin_url() . 'admin/kyc_settings';
	$data['siteSettings'] = $this->common_model->getTableData('site_settings', array('id' => 1))->row();
	$data['kycSettings'] = $this->common_model->getTableData('kyc_settings')->result();
	// print_r($data['kycSettings']);die;
	$data['title'] = 'KYC Settings';
	$data['meta_keywords'] = 'KYC Settings';
	$data['meta_description'] = 'KYC Settings';
	$data['main_content'] = 'admin/kyc_settings';
	$data['admin_id']=$sessionvar;
	$this->load->view('administrator/admin_template', $data);

}

function market_settings() {
		// If login
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		} else {
			$this->form_validation->set_rules('new_listings', 'New Title', 'required|xss_clean');
			// Post the values
			if ($this->input->post()) {
				
					$updateData['new_listings'] 		= $this->input->post('new_listings');
					$updateData['smd_usd'] 	= $this->input->post('smd_usd');
					
				
				
				
				

				
				
				$update = $this->common_model->updateTableData('market_settings', array('id' => 1), $updateData);
				

				if ($update) {
					$this->session->set_flashdata('success', 'Market settings updated successfully.');
					admin_redirect('admin/market_settings', 'refresh');	
				} else {
					$this->session->set_flashdata('error', 'Problem with your Market settings updation.');
					admin_redirect('admin/market_settings', 'refresh');
				}
				
			}
			
			$data['action'] = admin_url() . 'admin/market_settings';
			$data['siteSettings'] = $this->common_model->getTableData('market_settings', array('id' => 1))->row();
			$data['title'] = 'Market Settings';
			$data['meta_keywords'] = 'Market Settings';
			$data['meta_description'] = 'Market Settings';
			$data['main_content'] = 'admin/market_settings';
			$data['admin_id']=$sessionvar;
			$this->load->view('administrator/admin_template', $data);
		}	
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
		//echo "Get Account ===========> ".$get_account;
		}
		else if($value=='eth_blockNumber')
		{
		$parameter = "";
		$get_blockNumber = $this->$model_name->eth_blockNumber($parameter);
		//echo "Get Block Number ===========> ".$get_blockNumber;
		}
		else if($value=='eth_getLogs')
		{
		$parameter = "";
		$getLogs = $this->$model_name->eth_getLogs($parameter);
		//echo "Get Logs ===========> ".$getLogs;
		}
		else if($value=='eth_getBalance')
		{
		$parameter = "0x8936c1af634e0a1c3c6ac6bf4af7f1e37a565d14";
		$getBalance = $this->$model_name->eth_getBalance($parameter);
		//echo "Get Balance ===========> ".$getBalance;
		}
		else if($value=='eth_getTransactionCount')
		{
		$parameter = "0x8936c1af634e0a1c3c6ac6bf4af7f1e37a565d14";
		$getcount = $this->$model_name->eth_getTransactionCount($parameter);
		//echo "Get TransactionCount ===========> ".$getcount;
		}
		else if($value=='eth_gasPrice')
		{
			$parameter = "";
			$gas_price = $this->$model_name->eth_gasPrice($parameter);
			return $gas_price;
		}

	}

	function getBrowser() { 
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
  
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
		  $platform = 'linux';
		}elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		  $platform = 'mac';
		}elseif (preg_match('/windows|win32/i', $u_agent)) {
		  $platform = 'windows';
		}
  
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)){
		  $bname = 'Internet Explorer';
		  $ub = "MSIE";
		}elseif(preg_match('/Firefox/i',$u_agent)){
		  $bname = 'Mozilla Firefox';
		  $ub = "Firefox";
		}elseif(preg_match('/OPR/i',$u_agent)){
		  $bname = 'Opera';
		  $ub = "Opera";
		}elseif(preg_match('/Chrome/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
		  $bname = 'Google Chrome';
		  $ub = "Chrome";
		}elseif(preg_match('/Safari/i',$u_agent) && !preg_match('/Edge/i',$u_agent)){
		  $bname = 'Apple Safari';
		  $ub = "Safari";
		}elseif(preg_match('/Netscape/i',$u_agent)){
		  $bname = 'Netscape';
		  $ub = "Netscape";
		}elseif(preg_match('/Edge/i',$u_agent)){
		  $bname = 'Edge';
		  $ub = "Edge";
		}elseif(preg_match('/Trident/i',$u_agent)){
		  $bname = 'Internet Explorer';
		  $ub = "MSIE";
		}
  
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
	  ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
		  // we have no matching number just continue
		}
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
		  //we will have two since we are not using 'other' argument yet
		  //see if version is before or after the name
		  if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
			  $version= $matches['version'][0];
		  }else {
			  $version= $matches['version'][1];
		  }
		}else {
		  $version= $matches['version'][0];
		}
  
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
  
		return array(
		  'userAgent' => $u_agent,
		  'name'      => $bname,
		  'version'   => $version,
		  'platform'  => $platform,
		  'pattern'    => $pattern
		);
	  }


	function coinprofit_report($currency="1", $type="fiat")
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$daily="SELECT DATE(datetime) as dateval, SUM(profit_amount) as total, SUM(bonus_amount) as bonus FROM bidex_transaction_history where currency='$currency' AND currency_type='$type' GROUP BY YEAR(datetime), MONTH(datetime), DATE(datetime)";
		$data['daily']=$this->db->query($daily);

		$weekly="SELECT  WEEK(`datetime`) as week_number,YEAR(datetime) as yname,SUM(`profit_amount`) as total, SUM(bonus_amount) as bonus FROM `bidex_transaction_history` where currency='$currency' GROUP BY WEEK(`datetime`)";

		$data['weekly']=$this->db->query($weekly);


		$monthly="SELECT MONTHNAME(datetime) as moname,YEAR(datetime) as yname, SUM(profit_amount) as total, SUM(bonus_amount) as bonus FROM bidex_transaction_history where currency='$currency' GROUP BY YEAR(datetime), MONTH(datetime)";
		$data['monthly']=$this->db->query($monthly);


		$yearly="SELECT YEAR(datetime) as yname, SUM(profit_amount) as total, SUM(bonus_amount) as bonus FROM bidex_transaction_history where currency='$currency' GROUP BY YEAR(datetime)";

		$data['yearly']=$this->db->query($yearly);

			$chartdata=array();
			$chartdata[0]=array();
			$chartdata[1]=array();
			$categories=array();
			$count=1;
			for($i=30;$i>=0;$i--)
			{
				$data0=array($count,date('d-M',strtotime('-'.$i.' days')));
				$chartdata[0][]=$data0;
				$date1=date('Y-m-d',strtotime('-'.$i.' days'));
				$data1=array($count,$this->coin_profit_details($date1,$currency));
				$chartdata[1][]=$data1;
				$count++;
			}
		
		$data['chartdata']=$chartdata;
		$data['currency'] = $currency;
		$data['type'] = $type;
		$data['fi_cu'] = $this->common_model->getTableData('currency',array('type'=>'fiat','status'=>1))->result();
		$data['cu'] = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1))->result();
		// echo '<pre>'; print_r($data); die;
		$data['title'] = 'Coin Profit';
		$data['meta_keywords'] = 'Coin Profit';
		$data['meta_description'] = 'Coin Profit';
		$data['main_content'] = 'admin/coinprofit_report';
		$data['view'] = 'report';
		/*if($type=="fiat")
		{
			$data['currency_symbol'] = $this->common_model->getTableData('fiat_currency', array('id' => $currency))->row('currency_symbol');
		}
		else
		{
			$data['currency_symbol'] = $this->common_model->getTableData('currency', array('id' => $currency))->row('currency_symbol');
		}*/
		$data['currency_symbol'] = $this->common_model->getTableData('currency', array('id' => $currency,'status'=>1))->row('currency_symbol');
		
		$this->load->view('administrator/admin_template', $data); 
	}  


}