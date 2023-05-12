<?php
/**
 * Deposit class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Deposit extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
 	function deposit_ajax()
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
            2=>'transaction_id',
            3=>'datetime',            
            4=>'currency_name',
            5=>'amount',
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

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Deposit' AND a.currency_type='fiat'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Deposit' AND a.currency_type='fiat'".$like." ORDER BY a.trans_id DESC");
$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Deposit' AND a.currency_type='fiat'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id`
JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Deposit" AND `a`.`currency_type`="fiat" ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id` JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Deposit" AND `a`.`currency_type`="fiat" ORDER BY `a`.`trans_id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;

				if($result->transaction_id =='')
	            {
	              $transaction_id = '-';
	            }
	            else
	            {
	             $transaction_id = $result->transaction_id;
	            }
	            

	            $edit = '<a href="' . admin_url() . 'deposit/view/' . $result->trans_id . '" data-placement="top" data-toggle="popover" data-content="View the Deposit details." class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';				
				
					$data[] = array(
					    $i, 
						getUserDetails($result->user_id,'bidex_username'),
						$transaction_id,
						gmdate("d-m-Y h:i a", $result->datetime),
						$result->currency_symbol,
						number_format($result->amount,2),
						number_format($result->transfer_amount,2),
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

 	function cryptodeposit_ajax()
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

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Deposit' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM bidex_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Deposit' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id`
JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Deposit" AND `a`.`currency_type`="crypto" ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username FROM `bidex_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id` JOIN `bidex_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Deposit" AND `a`.`currency_type`="crypto" ORDER BY `a`.`trans_id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;

				if($result->transaction_id =='')
	            {
	              $transaction_id = '-';
	            }
	            else
	            {
	             $transaction_id = $result->transaction_id;
	            }
	            

	            $edit = '<a href="' . admin_url() . 'deposit/crypto_view/' . $result->trans_id . '" data-placement="top" data-toggle="popover" data-content="View the Crypto Deposit details." class="poper"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';				
				
				if (($timestamp = strtotime($result->datetime)) !== false) {
				    $tt = $result->datetime;
				} else {
				    $tt = date("Y-m-d h:i:s", $result->datetime);
				}

					$data[] = array(
					    $i, 
						getUserDetails($result->user_id,'bidex_username'),
						// gmdate("d-m-Y h:i a", $result->datetime),
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
		$hiswhere = array('a.type'=>'Deposit','a.currency_type'=>'fiat');
		$data['deposit'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username','','','','','',array('a.trans_id', 'DESC'))->result();
		//echo $this->db->last_query();
		
		$data['prefix'] = get_prefix();
		$data['title'] = 'Deposit Management';
		$data['meta_keywords'] = 'Deposit Management';
		$data['meta_description'] = 'Deposit Management';
		$data['main_content'] = 'deposit/deposit';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Deposit', 'a.trans_id'=>$id,'a.currency_type'=>'fiat');
		$data['deposit'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_username as username','','','','','',array('a.trans_id', 'DESC'))->row();
		//echo $this->db->last_query();
		//$data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details', array('id'=>$data['deposit']->bank_id,'user_id'=>$data['deposit']->user_id))->row();
		$data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details', array('id'=>$data['deposit']->bank_id))->row();
		//echo $this->db->last_query();
		// echo '<pre>'; print_r($data['deposit']); die;

		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'deposit/view/' . $id;
		
		$data['title'] = 'Deposit Management';
		$data['meta_keywords'] = 'Deposit Management';
		$data['meta_description'] = 'Deposit Management';
		$data['main_content'] = 'deposit/deposit';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}
	
	
	// Status change
	function status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('deposit');
		}
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'deposit', 'status'=>'Pending','currency_type'=>'fiat'));
		$isValid = $isValids->num_rows();
		$deposit = $isValids->row();
		if ($isValid > 0) { // Check is valid banner 
			$user_id = $deposit->user_id; // user id
			$amount = $deposit->transfer_amount; //deposit amount with fees
			$currency = $deposit->currency_id; // Currency id
			$tx = $deposit->transaction_id;
			$currency_name = getcryptocurrency($deposit->currency_id);
			$balance = getBalance($user_id,$currency,'fiat'); // get user bal
			// print_r($balance); die;
			$finalbalance = $balance+$amount; // bal + dep amount
			$updatebalance = updateBalance($user_id,$currency,$finalbalance,'fiat'); // Update balance
			$prefix = get_prefix();
			$user = getUserDetails($deposit->user_id);
			$usernames = $prefix.'username';
			$username = $user->$usernames;
			$email = getUserEmail($deposit->user_id);

			$email_template = 'Deposit_Complete';
				$special_vars = array(
				'###USERNAME###' => $username,
				'###AMOUNT###'   => number_format($amount,2),
				'###CURRENCY###' => $currency_name,
				'###TX###' => $tx,
				);
			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

			$trans_data = array(
					'userId'=>$deposit->user_id,
					'type'=>'Deposit',
					'currency'=>$deposit->currency_id,
					'amount'=>$deposit->amount,
					'profit_amount'=>$deposit->fee,
					'comment'=>'Deposit #'.$deposit->trans_id,
					'datetime'=>date('Y-m-d h:i:s'),
					'currency_type'=>'fiat',
					);
			$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);

			$updateData['status'] = 'Completed';
			$updateData['payment_status'] = 'Paid';
			$condition = array('trans_id' => $id,'type' => 'deposit');
			$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
			if ($update) { // True // Update success
					$this->session->set_flashdata('success', 'Deposit amount sent to user successfully');
				admin_redirect('deposit');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with send amount to user');
				admin_redirect('deposit');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Deposit');
			admin_redirect('deposit');
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
			admin_redirect('deposit');
		}
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'deposit', 'status'=>'Pending','currency_type'=>'fiat'));
		$isValid = $isValids->num_rows();
		$deposit = $isValids->row();
		if ($isValid > 0) { // Check is valid banner 
			$user_id = $deposit->user_id; // user id
			$amount = $deposit->transfer_amount; //deposit amount with fees
			$currency = $deposit->currency_id; // Currency
			$tx = $deposit->transaction_id;
			$currency_name = getfiatcurrency($deposit->currency_id); // Currency name
			$prefix = get_prefix();
			$user = getUserDetails($deposit->user_id);
			$usernames = $prefix.'username';
			$username = $user->$usernames;
			$email = getUserEmail($deposit->user_id);

			// Added to reserve amount
			$reserve_amount = getfiatcurrencydetail($deposit->currency_id);
			if((float)$reserve_amount->reserve_Amount<$deposit->amount)
			{
				$this->session->set_flashdata('error', 'You dont have enough balance to reject this deposit');
				admin_redirect('deposit');
				return false;
			}
			$final_reserve_amount = (float)$reserve_amount->reserve_Amount - (float)$deposit->amount;
			$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $deposit->currency_id);

			// Email
			$email_template = 'Deposit_Cancel';
				$special_vars = array(
				'###USERNAME###' => $username,
				'###AMOUNT###'   => number_format($amount,2),
				'###CURRENCY###' => $currency_name,
				'###REASON###'   => $this->input->post('mail_content'),
				'###TX###'   => $tx,
				);
			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);


			$updateData['status'] = 'Cancelled';
			$condition = array('trans_id' => $id,'type' => 'deposit');
			$update = $this->common_model->updateTableData('transactions', $condition, $updateData);
			if ($update) { // True // Update success
					$this->session->set_flashdata('success', 'Deposit amount rejected successfully');
				admin_redirect('deposit');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with reject the deposit');
				admin_redirect('deposit');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Deposit');
			admin_redirect('deposit');
		}
	}

	function dash()
	{
		$keyword = "test";
		$base = "next";
		echo base_url('search?s='.$keyword).$base;
	}


		function crypto_deposit() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Deposit','a.admin_status'=>0,'a.currency_type'=>'crypto');
		$data['deposit'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username','','','','','',array('a.trans_id', 'DESC'))->result();
		
		
		$data['prefix'] = get_prefix();
		
		$data['title'] = 'Crypto Deposit Management';
		$data['meta_keywords'] = 'Crypto Deposit Management';
		$data['meta_description'] = 'Crypto Deposit Management';
		$data['main_content'] = 'deposit/crypto_deposit';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function crypto_view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Deposit', 'a.trans_id'=>$id);
		$data['deposit'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.bidex_username as username','','','','','',array('a.trans_id', 'DESC'))->row();
		// echo '<pre>'; print_r($data['deposit']); die;
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'crypto_deposit/view/' . $id;
		
		$data['title'] = 'Crypto Deposit Management';
		$data['meta_keywords'] = 'Crypto Deposit Management';
		$data['meta_description'] = 'Crypto Deposit Management';
		$data['main_content'] = 'deposit/crypto_deposit';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}


	/*New code add manual deposit 15-5-18*/
	function add()
	{
		$this->form_validation->set_rules('user_id', 'User Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('trans_id', 'Transaction Id', 'trim|required|xss_clean');
		$this->form_validation->set_rules('amount', 'Amount', 'trim|required|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');

		if($this->input->post())
		{
			if($this->form_validation->run())
			{
				$deposit_data = array(
					'user_id' => $this->input->post('user_id'),
					'transaction_id' => $this->input->post('trans_id'),
					'amount' => $this->input->post('amount'),
					'description' => $this->input->post('description'),
					'type' => 'Deposit',
					'transfer_amount' => $this->input->post('amount'),
					'currency_id'=>24,
					'status'=>'Completed',
					'payment_status'=>'Paid',
					'user_status'=>'Completed',
					'currency_type'=>'fiat',
					'datetime' =>gmdate(time())
					);
				$id=$this->common_model->insertTableData('transactions', $deposit_data);
				if($id)
				{
					$balance = getBalance($this->input->post('user_id'),24,'fiat'); // get user bal
					// print_r($balance); die;
					$finalbalance = ($balance+($this->input->post('amount'))); // bal + dep amount
					$updatebalance = updateBalance($this->input->post('user_id'),24,$finalbalance,'fiat'); // Update balance

					$prefix = get_prefix();
					$user = getUserDetails($this->input->post('user_id'));
					$usernames = $prefix.'username';
					$username = $user->$usernames;
					$email = getUserEmail($this->input->post('user_id'));

					$email_template = 'Deposit_Complete';
						$special_vars = array(
						'###USERNAME###' => $username,
						'###AMOUNT###'   => number_format($this->input->post('amount'),2),
						'###CURRENCY###' => 'USD',
						'###TX###' => $this->input->post('trans_id'),
						);
					$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

					$trans_data = array(
							'userId'=>$this->input->post('user_id'),
							'type'=>'Deposit',
							'currency'=>24,
							'amount'=>$this->input->post('amount'),
							/*'profit_amount'=>$deposit->fee,*/
							'comment'=>'Deposit #'.$this->input->post('trans_id'),
							'datetime'=>date('Y-m-d h:i:s'),
							'currency_type'=>'fiat',
							);
					$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);


					$this->session->set_flashdata('success', 'Your Manual Deposit process successfully completed');
					admin_redirect('deposit');
				}
				else {
					$this->session->set_flashdata('error', 'Error occur!! Please try again');
					admin_redirect('deposit');
				}
			}
			else
			{
				$this->session->set_flashdata('error', validation_errors());
				admin_redirect('deposit');
			}	
		}

		$data['users'] = $this->common_model->getTableData('users',array('verified'=>1))->result();
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() .'deposit/add';
		
		$data['title'] = 'Add Deposit Management';
		$data['meta_keywords'] = 'Add Deposit Management';
		$data['meta_description'] = 'Add Deposit Management';
		$data['main_content'] = 'deposit/deposit';
		$data['view'] = 'add';
		$this->load->view('administrator/admin_template', $data); 
	}
	/*end 15-5-18*/

 }
 
 /**
 * End of the file PAIR.php
 * Location: ./application/controllers/ulawulo/pair.php
 */	
