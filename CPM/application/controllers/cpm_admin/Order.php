<?php
/**
 * Cms class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Order extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
	// list
	function buy() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		$orders = $this->common_model->getTableData('order', array('type'=>'buy'))->result_array();
	   	if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
			$search_string = $_GET['search_string'];
			$like = array("b.currency_symbol"=>$search_string);
			$like_or = array("c.coinchairs_username"=>$search_string, "d.currency_symbol"=>$search_string, "a.send_amount"=>$search_string, "a.receive_amount"=>$search_string, "a.fees"=>$search_string, "a.final_amount"=>$search_string, "a.send_status"=>$search_string);
			$hisjoins = array('fiat_currency as b'=>'a.from_currency = b.id','users as c'=>'a.user_id = c.id', 'currency as d'=>'a.to_currency = d.id');
			$hiswhere = array('type'=>'buy');
			$data['order'] = $this->common_model->getJoinedTableDatas('order as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as from_currency_symbol, d.currency_symbol as to_currency_symbol, c.coinchairs_username as username',$like,'',$like_or,'','',array('a.datecreated', 'DESC'));	
		}
		else {
		$perpage =max_records();
	  	$urisegment=$this->uri->segment(4);  
	   	$base="order/buy";
	   	$total_rows = count($orders);
	   	pageconfig($total_rows,$base,$perpage);
	   	$hisjoins = array('fiat_currency as b'=>'a.from_currency = b.id','users as c'=>'a.user_id = c.id', 'currency as d'=>'a.to_currency = d.id');
		$hiswhere = array('type'=>'buy');
		$data['order'] = $this->common_model->getJoinedTableData('order as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as from_currency_symbol, d.currency_symbol as to_currency_symbol, c.coinchairs_username as username','','','',$urisegment,$perpage,array('a.datecreated', 'DESC'));
		}
		$data['prefix']=get_prefix();
		$data['title'] = 'Order Management';
		$data['meta_keywords'] = 'Order Management';
		$data['meta_description'] = 'Order Management';
		$data['main_content'] = 'order/order';
		$data['view'] = 'buy';
		$this->load->view('administrator/admin_template', $data); 
	}

		function sell() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		$orders = $this->common_model->getTableData('order', array('type'=>'sell'))->result_array();
		if(isset($_GET['search_string1']) && !empty($_GET['search_string1'])){
			$search_string = $_GET['search_string1'];
			$like = array("b.currency_symbol"=>$search_string);
			$like_or = array("c.coinchairs_username"=>$search_string, "d.currency_symbol"=>$search_string, "a.send_amount"=>$search_string, "a.receive_amount"=>$search_string, "a.fees"=>$search_string, "a.final_amount"=>$search_string, "a.send_status"=>$search_string);
			$hisjoins = array('currency as b'=>'a.from_currency = b.id','users as c'=>'a.user_id = c.id', 'fiat_currency as d'=>'a.to_currency = d.id');
			$hiswhere = array('type'=>'sell');
			$data['order'] = $this->common_model->getJoinedTableDatas('order as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as from_currency_symbol, d.currency_symbol as to_currency_symbol, c.coinchairs_username as username',$like,'',$like_or,'','',array('a.datecreated', 'DESC'));
		}
		else {
	   	$perpage =max_records();
	  	$urisegment=$this->uri->segment(4);  
	   	$base="order/sell";
	   	$total_rows = count($orders);
	   	pageconfig($total_rows,$base,$perpage);

	   	$hisjoins = array('currency as b'=>'a.from_currency = b.id','users as c'=>'a.user_id = c.id', 'fiat_currency as d'=>'a.to_currency = d.id');
		$hiswhere = array('type'=>'sell');
		$data['order'] = $this->common_model->getJoinedTableData('order as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as from_currency_symbol, d.currency_symbol as to_currency_symbol, c.coinchairs_username as username','','','',$urisegment,$perpage,array('a.datecreated', 'DESC'));
		}
		$data['prefix']=get_prefix();
		$data['title'] = 'Order Management';
		$data['meta_keywords'] = 'Order Management';
		$data['meta_description'] = 'Order Management';
		$data['main_content'] = 'order/order';
		$data['view'] = 'sell';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Edit page
	function edit($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('cms');
		}
		$data['order'] = $this->common_model->getTableData('order',array('order_id'=>$id));
		$isValid = $data['order'];
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('order');
		}
		$data['order'] = $isValid->row();
		$data['prefix']=get_prefix();
		$data['action'] = admin_url() . 'order/edit/' . $id;
		$data['title'] = 'Edit Order';
		$data['meta_keywords'] = 'Edit Order';
		$data['meta_description'] = 'Edit Order';
		$data['main_content'] = 'order/order';
		$data['view'] = 'edit';
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
			admin_redirect('order');
		}
		$prefix=get_prefix();
		$isValids = $this->common_model->getTableData('order', array('order_id' => $id));
		$isValid = $isValids->num_rows();
		$order = $isValids->row();
		if ($isValid > 0) { 
			$condition = array('order_id' => $id);
			$updateData['send_status'] = $status;
			if($order->type=='buy')
			{
				$amount = $order->receive_amount; // Receive amount
				$user_id = $order->user_id; // User id
				$currency = $order->to_currency; // Balance update to this currency
				$currency_symbol = getcryptocurrency($currency);

				$reserve_amount = getcryptocurrencydetail($currency);
				if((float)$order->receive_amount>(float)$reserve_amount->reserve_Amount)
				{
					$this->session->set_flashdata('error', 'You dont have enough balance to complete this order');
					admin_redirect('order/buy');
					return false;
				}
				
				$balance = getBalance($user_id,$currency); // Fetch old/already having balance
				$finalbalance = $balance+$amount; // old balance + receive amount
				$updatebalance = updateBalance($user_id,$currency,$finalbalance); // Update balance
				// update to reserve amount
				
				$final_reserve_amount = (float)$reserve_amount->reserve_Amount - (float)$order->receive_amount;
				$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $currency);


				// Get user name
				$user = getUserDetails($order->user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($order->user_id);
				// Email
				$email_template = 'Buy';
					$special_vars = array(
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $amount,
					'###CURRENCY###' => $currency_symbol,
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

				$trans_data = array(
					'userId'=>$order->user_id,
					'type'=>'Exchange-Buy',
					'currency'=>$order->from_currency,
					'amount'=>$order->final_amount,
					'profit_amount'=>$order->fees,
					'comment'=>'Exchange'.$order->type.' order #'.$order->order_id,
					'datetime'=>date('Y-m-d h:i:s'),
					'currency_type'=>'fiat',
					);
				$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);

			}
			else if($order->type=='sell')
			{
				if($order->payment_method=='balanceupdate')
				{
				$amount = $order->receive_amount; // Receive amount
				$user_id = $order->user_id; // User id
				$currency = $order->to_currency; // Balance update to this currency
				$currency_symbol = getfiatcurrency($currency);
				$reserve_amount = getfiatcurrencydetail($currency);// fiat currency details
				if((float)$order->receive_amount>(float)$reserve_amount->reserve_Amount)
				{
					$this->session->set_flashdata('error', 'You dont have enough balance to complete this order');
					admin_redirect('order/sell');
					return false;
				}



				$balance = getBalance($user_id,$currency,'fiat'); // Fetch old/already having balance
				$finalbalance = $balance+$amount; // old balance + receive amount
				$updatebalance = updateBalance($user_id,$currency,$finalbalance,'fiat'); // Update balance
				// Get user name
				$user = getUserDetails($order->user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($order->user_id);

				//update reserve amount
				$final_reserve_amount =  (float)$reserve_amount->reserve_Amount - (float)$order->receive_amount;
				$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $currency);
				
				// Email
				$email_template = 'Sell';
					$special_vars = array(
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $amount,
					'###CURRENCY###' => $currency_symbol,
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				}
				else {
				$amount = $order->receive_amount;
				$currency = $order->to_currency;
				$currency_symbol = getfiatcurrency($currency);
				$user = getUserDetails($order->user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($order->user_id);

				//update reserve amount
				$reserve_amount = getfiatcurrencydetail($currency);// fiat currency details
				if((float)$order->receive_amount>(float)$reserve_amount->reserve_Amount)
				{
					$this->session->set_flashdata('error', 'You dont have enough balance to complete this order');
					admin_redirect('order/sell');
					return false;
				}
				$final_reserve_amount =  (float)$reserve_amount->reserve_Amount - (float)$order->receive_amount;
				$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $currency);
				
				// Email
				$email_template = 'Sell_bankwire';
					$special_vars = array(
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $amount,
					'###CURRENCY###' => $currency_symbol,
					'###TRANSACTIONID###' => $this->input->post('transaction_id'),
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				}

				$trans_data = array(
					'userId'=>$order->user_id,
					'type'=>'Exchange-Sell',
					'currency'=>$order->to_currency,
					'amount'=>$order->final_amount,
					'profit_amount'=>$order->fees,
					'comment'=>'Exchange'.$order->type.' order #'.$order->order_id,
					'datetime'=>date('Y-m-d h:i:s'),
					'currency_type'=>'fiat',
					);
				$update_trans = $this->common_model->insertTableData('transaction_history',$trans_data);
			}

			$update = $this->common_model->updateTableData('order', $condition, $updateData);
			if ($update) { // True // Update success
				$this->session->set_flashdata('success', 'Order Completed successfully');
				if($order->type=='buy'){ admin_redirect('order/buy'); }
				else { admin_redirect('order/sell'); }	
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with order completion');
				if($order->type=='buy'){ admin_redirect('order/buy'); }
				else { admin_redirect('order/sell'); }	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this order');
			admin_redirect('order/buy');
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
			admin_redirect('order');
		}

		$prefix=get_prefix();
		$isValids = $this->common_model->getTableData('order', array('order_id' => $id));
		$isValid = $isValids->num_rows();
		$order = $isValids->row();
		if ($isValid > 0) { 
			$condition = array('order_id' => $id);
			$updateData['send_status'] = 'Cancelled';
			if($order->type=='buy')
			{
				$amount = $order->final_amount; // Receive amount
				$user_id = $order->user_id; // User id
				$currency = $order->from_currency; // Balance update to this currency
				$currency_symbol = getcryptocurrency($currency);
				
				$reserve_amount = getfiatcurrencydetail($currency);// fiat currency details
				if((float)$amount>(float)$reserve_amount->reserve_Amount)
				{
					$this->session->set_flashdata('error', 'You dont have enough balance to reject this order');
					admin_redirect('order/buy');
					return false;
				}


				$balance = getBalance($user_id,$currency,'fiat'); // Fetch old/already having balance
				$finalbalance = $balance+$amount; // old balance + receive amount
				$updatebalance = updateBalance($user_id,$currency,$finalbalance,'fiat'); // Update balance

				// update reserve amount
				$final_reserve_amount =  (float)$reserve_amount->reserve_Amount - (float)$amount;
				$new_reserve_amount = updatefiatreserveamount($final_reserve_amount, $currency);

				// Get user name
				$user = getUserDetails($order->user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($order->user_id);
				// Email
				$email_template = 'Buy_Cancel';
					$special_vars = array(
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $amount,
					'###CURRENCY###' => $currency_symbol,
					'###REASON###'   => $this->input->post('mail_content'),
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);

			}
			else if($order->type=='sell')
			{
				$amount = $order->send_amount; // Receive amount
				$user_id = $order->user_id; // User id
				$currency = $order->from_currency; // Balance update to this currency
				$currency_symbol = getfiatcurrency($currency);
				$balance = getBalance($user_id,$currency,'crypto'); // Fetch old/already having balance
				$finalbalance = $balance+$amount; // old balance + receive amount
				$updatebalance = updateBalance($user_id,$currency,$finalbalance,'crypto'); // Update balance
				// Get user name
				$user = getUserDetails($order->user_id);
				$usernames = $prefix.'username';
				$username = $user->$usernames;
				$email = getUserEmail($order->user_id);

				// update to reserve amount
				$reserve_amount = getcryptocurrencydetail($currency);
				if((float)$amount>(float)$reserve_amount->reserve_Amount)
				{
					$this->session->set_flashdata('error', 'You dont have enough balance to reject this order');
					admin_redirect('order/sell');
					return false;
				}
				$final_reserve_amount = (float)$reserve_amount->reserve_Amount - (float)$amount;
				$new_reserve_amount = updatecryptoreserveamount($final_reserve_amount, $currency);
				
				// Email
				$email_template = 'Sell_Cancel';
					$special_vars = array(
					'###USERNAME###' => $username,
					'###AMOUNT###'   => $amount,
					'###CURRENCY###' => $currency_symbol,
					'###REASON###' => $this->input->post('mail_content'),
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
			}

			$update = $this->common_model->updateTableData('order', $condition, $updateData);
			if ($update) { // True // Update success
				$this->session->set_flashdata('success', 'Order Cancelled successfully');
				if($order->type=='buy'){ admin_redirect('order/buy'); }
				else { admin_redirect('order/sell'); }	
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with order cancellation');
				if($order->type=='buy'){ admin_redirect('order/buy'); }
				else { admin_redirect('order/sell'); }	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this order');
			admin_redirect('order/buy');
		}
	}
 }