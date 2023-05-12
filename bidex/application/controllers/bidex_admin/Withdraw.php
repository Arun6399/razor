<?php
/**
 * Withdraw class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Withdraw extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		$this->load->library('session'); 
		
 	}
 	function withdraw_ajax()
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
            2=>'datetime',            
            3=>'currency_name',
            4=>'amount',
            5=>'fee',
            6=>'transfer_amount',
            7=>'status',
            8=>'status'
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
            $like = " AND (b.currency_symbol LIKE '%".$search."%' OR c.bidex_username LIKE '%".$search."%' OR a.transaction_id LIKE '%".$search."%' OR a.amount LIKE '%".$search."%')";

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.currency_type='fiat' AND a.user_status='Completed'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;
$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.currency_type='fiat' AND a.user_status='Completed'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id`
JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`currency_type`="fiat" AND `a`.`user_status`="Completed" ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id` JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`currency_type`="fiat" AND `a`.`user_status`="Completed" ORDER BY `a`.`trans_id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
	            $edit = '<a href="' . admin_url() . 'withdraw/view/' . $result->trans_id . '" data-placement="top" data-toggle="popover" data-content="View the withdraw details." class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';				
				
			    $data[] = array(
					    $i, 
						$result->username,
					    $result->datetime,
						$result->currency_symbol,
						$result->amount,
						$result->fee,
						$result->transfer_amount,
						$result->status,
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

 	function cryptowithdraw_ajax()
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
            2=>'datetime',            
            3=>'currency_name',
            4=>'amount',
            5=>'status',
            6=>'status'
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
            $like = " AND (b.currency_symbol LIKE '%".$search."%' OR c.bidex_username LIKE '%".$search."%' OR a.amount LIKE '%".$search."%')";

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id`
JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`currency_type`="crypto" ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id` JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`currency_type`="crypto" ORDER BY `a`.`trans_id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
	            

	            $edit = '<a href="' . admin_url() . 'withdraw/crypto_view/' . $result->trans_id . '" data-placement="top" data-toggle="popover" data-content="View the Crypto withdraw details." class="poper"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';				
				
				if (($timestamp = strtotime($result->datetime)) !== false) {
				    $tt = $result->datetime;
				} else {
				    $tt = date("Y-m-d h:i:s", $result->datetime);
				}
				
					$data[] = array(
					    $i, 
						// getUserDetails($result->user_id,'bidex_username'),
						getUserEmail($result->user_id),
						$tt,
						$result->currency_symbol,
						$result->amount,
						$result->status,
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

	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Withdraw','a.currency_type'=>'fiat','a.user_status'=>'Completed');

		$data['withdraw'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username','','','','','',array('a.trans_id', 'DESC'))->result();
		//echo $this->db->last_query();

		
		$data['prefix'] = get_prefix();
		$data['title'] = 'Withdraw Management';
		$data['meta_keywords'] = 'Withdraw Management';
		$data['meta_description'] = 'Withdraw Management';
		$data['main_content'] = 'withdraw/withdraw';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Withdraw', 'a.trans_id'=>$id,'a.currency_type'=>'fiat','a.user_status'=>'Completed');
		$data['withdraw'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username','','','','','',array('a.trans_id', 'DESC'))->row();
		$data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$data['withdraw']->user_id))->row();
		// echo '<pre>'; print_r($data['user_bankdetails']); die;
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'withdraw/view/' . $id;
		$data['title'] = 'Withdraw Management';
		$data['meta_keywords'] = 'Withdraw Management';
		$data['meta_description'] = 'Withdraw Management';
		$data['main_content'] = 'withdraw/withdraw';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}

	function pay($id)
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('withdraw');
		}
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'Withdraw', 'status'=>'Pending','currency_type'=>'fiat','user_status'=>'Completed'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if ($isValid > 0) 
		{
		    $user_id = $withdraw->user_id; // user id
			$amount = number_format($withdraw->transfer_amount,2,".",""); //withdraw amount with fees
			$currency = $withdraw->currency_id; // Currency id
			$currency_name = getfiatcurrency($withdraw->currency_id);
			
			$prefix = get_prefix();
			$user = getUserDetails($withdraw->user_id);
			$usernames = $prefix.'username';
			$username = $user->$usernames;
			$email = getUserEmail($withdraw->user_id);

			$sitesettings = $this->common_model->getTableData('site_settings',array('id'=>1))->row();
			//echo '<pre>';print_r($sitesettings);
			$generate_batchid = $user_id."ENDY".rand(0,7000);
			$trans_data = array(
				'userId'=>$withdraw->user_id,
				'type'=>'Withdraw',
				'currency'=>$withdraw->currency_id,
				'amount'=>$withdraw->transfer_amount,
				'profit_amount'=>$withdraw->fee,
				'comment'=>'Withdraw #'.$withdraw->trans_id,
				'datetime'=>date('Y-m-d h:i:s'),
				'currency_type'=>'fiat'
			);
		    $update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);
		    // $updateData['transaction_id'] = $generate_batchid;
			$updateData['status']='Completed';
			$updateData['payment_status']='Paid';
			$condition = array('trans_id'=>$id,'type'=>'withdraw');
			$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
			// MAIL FUNCTIONALITY
			$prefix = get_prefix();
			$user = getUserDetails($withdraw->user_id);
			$usernames = $prefix.'username';
			$username = $user->$usernames;
			$email = getUserEmail($withdraw->user_id);
			$currency_name = getfiatcurrency($withdraw->currency_id);

			$email_template = 'Withdraw_Complete';
			$special_vars = array(
			'###USERNAME###' => $username,
			'###AMOUNT###'   => $withdraw->transfer_amount,
			'###CURRENCY###' => $currency_name,
			'###TX###' => $withdraw->transaction_id,
			);
		    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);
			if ($update) 
			{ 
				$this->session->set_flashdata('success', 'Withdraw amount sent to user successfully');
			    admin_redirect('withdraw');
		    } 
		    else 
		    { 
				$this->session->set_flashdata('error', 'Problem occure with send amount to user');
				admin_redirect('withdraw');	
		    }
		    



		}
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this Withdraw');
			admin_redirect('withdraw');
		}		
	

	}


	function pay1($id)
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('withdraw');
		}
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'Withdraw', 'status'=>'Pending','currency_type'=>'fiat','user_status'=>'Completed'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if ($isValid > 0) 
		{
		    $user_id = $withdraw->user_id; // user id
			$amount = number_format($withdraw->transfer_amount,2,".",""); //withdraw amount with fees
			$currency = $withdraw->currency_id; // Currency id
			$currency_name = getfiatcurrency($withdraw->currency_id);
			
			$prefix = get_prefix();
			$user = getUserDetails($withdraw->user_id);
			$usernames = $prefix.'username';
			$username = $user->$usernames;
			$email = getUserEmail($withdraw->user_id);

			$sitesettings = $this->common_model->getTableData('site_settings',array('id'=>1))->row();
			//echo '<pre>';print_r($sitesettings);
			$paypal_clientid = $sitesettings->paypal_clientid;
			$paypal_secretid = $sitesettings->paypal_secretid;
			$paypal_mode = $sitesettings->paypal_mode; // TRUE OR FALSE

			$client_id = decryptIt($paypal_clientid); echo '<br/>';
			//Af2yS9ZJht8Ax-ECi0-AI0giexvwL35hMRJkrXf3GQci_wbKwz3KEC2WB6O9SzgK5fzTdOWkjr_4ryj2;
		    $secret_id = decryptIt($paypal_secretid); echo '<br/>';
		    //EE63jTUWg7JdRrfwA5c-fNX81MFN1w2w5STKjZIdnbeIjlObTM5q_cf3FqLoUWjlxyXR7QO_8-hUCDH_';
		    
		    $url = ($paypal_mode==FALSE)?'https://api.sandbox.paypal.com/v1/oauth2/token':'https://api.paypal.com/v1/oauth2/token';
		    
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
		    curl_setopt($ch, CURLOPT_USERPWD, $client_id . ':' . $secret_id);

		    $headers = array();
		    $headers[] = 'Accept: application/json';
		    $headers[] = 'Accept-Language: en_US';
		    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
		    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		    $result = curl_exec($ch);   
		    curl_close($ch);
		    $curl_response = json_decode($result);
		    if(array_key_exists('error', $curl_response))
		    {
		    	$this->session->set_flashdata('error', 'Some Technical issue occur. Please try again.');
		        admin_redirect('withdraw'.'refresh');
		    }
		    else
		    {
			    $token = $curl_response->access_token; 
			    if($token!='')
			    {
			        $generate_batchid = $user_id."ENDY".rand(0,7000);
			        $payout_url = ($paypal_mode==FALSE)?'https://api.sandbox.paypal.com/v1/payments/payouts':'https://api.paypal.com/v1/payments/payouts';
			        $postmyfields = '{
						  "sender_batch_header":{
						    "sender_batch_id":"'.$generate_batchid.'",
						    "email_subject":"Withdraw Request Confirmed",
						    "email_message":"Thanks for using our service!"
						  },
						  "items":[
						    {
						      "recipient_type":"EMAIL",
						      "amount":{
						        "value":"'.$amount.'",
						        "currency":"USD"
						      },
						      "note": "Thanks for your Contribution!",
						      "sender_item_id": "201403140001",
						      "receiver": "'.$email.'"
						    }
						  ]
						}';
			        $ch1 = curl_init();
			        curl_setopt($ch1, CURLOPT_URL, $payout_url);
			        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			        curl_setopt($ch1, CURLOPT_POST, 1);
			        curl_setopt($ch1, CURLOPT_POSTFIELDS, $postmyfields);

			        $headers1 = array();
			        $headers1[] = 'Content-Type: application/json';
			        $headers1[] = 'Authorization: Bearer '.$token;
			        curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers1);
			        $curl_response = curl_exec($ch1); 
			        $curl_responses = json_decode($curl_response); echo '<pre>';print_r($curl_responses);
			        curl_close($ch1);
			        $status = $curl_responses->batch_header->batch_status;
			        $batchId = $curl_responses->batch_header->payout_batch_id;
			        $sender_btachId = $curl_responses->batch_header->sender_batch_header->sender_batch_id;
			        if($batchId!='' && strtoupper($status)=='PENDING')
			        {
			        	
			        	$trans_data = array(
							'userId'=>$withdraw->user_id,
							'type'=>'Withdraw',
							'currency'=>$withdraw->currency_id,
							'amount'=>$withdraw->transfer_amount,
							'profit_amount'=>$withdraw->fee,
							'comment'=>'Withdraw #'.$withdraw->trans_id,
							'datetime'=>date('Y-m-d h:i:s'),
							'currency_type'=>'fiat',
							'sender_batch_id'=>$sender_btachId
						);
					    $update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);
					    $updateData['transaction_id'] = $generate_batchid;
						$updateData['status']='Completed';
						$updateData['payment_status']='Paid';
						$condition = array('trans_id'=>$id,'type'=>'withdraw');
						$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
						// MAIL FUNCTIONALITY
						$prefix = get_prefix();
						$user = getUserDetails($withdraw->user_id);
						$usernames = $prefix.'username';
						$username = $user->$usernames;
						$email = getUserEmail($withdraw->user_id);
						$currency_name = getfiatcurrency($withdraw->currency_id);

						$email_template = 'Withdraw_Complete';
						$special_vars = array(
						'###USERNAME###' => $username,
						'###AMOUNT###'   => $withdraw->transfer_amount,
						'###CURRENCY###' => $currency_name,
						'###TX###' => $generate_batchid,
						);
						//$email = 'manimegalai@spiegeltechnologies.com';
					    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);
						if ($update) 
						{ 
							$this->session->set_flashdata('success', 'Withdraw amount sent to user successfully');
						    admin_redirect('withdraw');
					    } 
					    else 
					    { 
							$this->session->set_flashdata('error', 'Problem occure with send amount to user');
							admin_redirect('withdraw');	
					    }
			        }
			        else
			        { 
			        	$this->session->set_flashdata('error', 'Payout error occured. Please try again.');
			            admin_redirect('withdraw','refresh');
			        }
		        }
			    else
			    {
			       $this->session->set_flashdata('error', 'Token error. Please try again.');
			       admin_redirect('withdraw','refresh');
			    }
		    }
		}
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this Withdraw');
			admin_redirect('withdraw');
		}		
	}
	
	
	// Status change
	function status($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('withdraw');
		}
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending','currency_type'=>'fiat','user_status'=>'Completed'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if ($isValid > 0) { // Check is valid banner 
			$user_id = $withdraw->user_id; // user id
			$amount = $withdraw->amount; //withdraw amount with fees
			$currency = $withdraw->currency_id; // Currency id
			$currency_name = getfiatcurrency($withdraw->currency_id);
			
			$prefix = get_prefix();
			$user = getUserDetails($withdraw->user_id);
			$usernames = $prefix.'username';
			$username = $user->$usernames;
			$email = getUserEmail($withdraw->user_id);

			// Added to reserve amount
			$reserve_amount = getfiatcurrencydetail($withdraw->currency_id);
			if((float)$reserve_amount->reserve_Amount<$withdraw->transfer_amount)
			{
				$this->session->set_flashdata('error', 'You dont have enough balance to complete this withdraw');
				admin_redirect('withdraw');
				return false;
			}
			$final_reserve_amount = (float)$reserve_amount->reserve_Amount - (float)$withdraw->transfer_amount;
			$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $withdraw->currency_id);


			$email_template = 'Withdraw_Complete';
				$special_vars = array(
				'###USERNAME###' => $username,
				'###AMOUNT###'   => $amount,
				'###CURRENCY###' => $currency_name,
				'###TX###' => $this->input->post('transaction_id'),
				);
			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

			$trans_data = array(
					'userId'=>$withdraw->user_id,
					'type'=>'Withdraw',
					'currency'=>$withdraw->currency_id,
					'amount'=>$withdraw->amount,
					'profit_amount'=>$withdraw->fee,
					'comment'=>'Withdraw #'.$withdraw->trans_id,
					'datetime'=>date('Y-m-d h:i:s'),
					'currency_type'=>'fiat',
					);
			$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);

			$updateData['transaction_id'] = $this->input->post('transaction_id');
			$updateData['status'] = 'Completed';
			$updateData['payment_status'] = 'Paid';
			$condition = array('trans_id' => $id,'type' => 'withdraw');
			$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
			if ($update) { // True // Update success
					$this->session->set_flashdata('success', 'Withdraw amount sent to user successfully');
				admin_redirect('withdraw');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with send amount to user');
				admin_redirect('withdraw');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Withdraw');
			admin_redirect('withdraw');
		}
	}

	function reject($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('withdraw');
		}
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'withdraw', 'status'=>'Pending','currency_type'=>'fiat','user_status'=>'Completed'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if ($isValid > 0) { // Check is valid banner 
			$user_id = $withdraw->user_id; // user id
			$amount = $withdraw->amount; //withdraw amount with fees
			$currency = $withdraw->currency_id; // Currency
			$currency_name = getfiatcurrency($withdraw->currency_id); // Currency name
			// Refund balance to user
			$balance = getBalance($user_id,$currency,'fiat'); // get user bal
			$finalbalance = $balance+$amount; // bal + dep amount
			$updatebalance = updateBalance($user_id,$currency,$finalbalance,'fiat'); // Update balance

			// username & email
			$prefix = get_prefix();
			$user = getUserDetails($withdraw->user_id);
			$usernames = $prefix.'username';
			$username = $user->$usernames;
			$email = getUserEmail($withdraw->user_id);

			// Email
			$email_template = 'Withdraw_Cancel';
				$special_vars = array(
				'###USERNAME###' => $username,
				'###AMOUNT###'   => $amount,
				'###CURRENCY###' => $currency_name,
				'###REASON###'   => $this->input->post('mail_content'),
				);
			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);


			$updateData['status'] = 'Cancelled';
			$condition = array('trans_id' => $id,'type' => 'withdraw');
			$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
			if ($update) { // True // Update success
					$this->session->set_flashdata('success', 'Withdraw Request rejected successfully');
				admin_redirect('withdraw');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with reject the withdraw');
				admin_redirect('withdraw');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Withdraw');
			admin_redirect('withdraw');
		}
	}

	
	function crypto_withdraw() {

	
		$sessionvar=$this->session->userdata('loggeduser');

		// $sessionvar=$this->session->userdata('loggeduser');



		if ($sessionvar=='') {
			admin_redirect('admin', 'refresh');
		}
        
        $hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Withdraw','a.currency_type'=>'crypto');
		$data['deposit'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.'.get_prefix().'username as username','','','','','',array('a.trans_id', 'DESC'))->result();
		//echo $this->db->last_query();
		
		$data['prefix'] = get_prefix();
		$data['title'] = 'Crypto Withdraw Management';
		$data['meta_keywords'] = 'Crypto Withdraw Management';
		$data['meta_description'] = 'Crypto Withdraw Management';
		$data['main_content'] = 'withdraw/crypto_withdraw';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function crypto_view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Withdraw', 'a.trans_id'=>$id,'a.currency_type'=>'crypto');
		$data['deposit'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.'.get_prefix().'username as username','','','','','',array('a.trans_id', 'DESC'))->row();
		// echo '<pre>'; print_r($data['deposit']); die;
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'crypto_withdraw/view/' . $id;
		$data['title'] = 'Crypto Deposit Management';
		$data['meta_keywords'] = 'Crypto Deposit Management';
		$data['meta_description'] = 'Crypto Deposit Management';
		$data['main_content'] = 'withdraw/crypto_withdraw';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}


 }
 
 /**
 * End of the file PAIR.php
 * Location: ./application/controllers/ulawulo/pair.php
 */	
