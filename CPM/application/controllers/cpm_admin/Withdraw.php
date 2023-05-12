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
            $like = " AND (b.currency_symbol LIKE '%".$search."%' OR c.cpm_username LIKE '%".$search."%' OR a.transaction_id LIKE '%".$search."%' OR a.amount LIKE '%".$search."%')";

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM cpm_transactions as a JOIN cpm_currency as b ON a.currency_id=b.id JOIN cpm_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.currency_type='fiat' AND a.user_status='Completed'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;
$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM cpm_transactions as a JOIN cpm_currency as b ON a.currency_id=b.id JOIN cpm_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.currency_type='fiat' AND a.user_status='Completed'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM `cpm_transactions` as `a` JOIN `cpm_currency` as `b` ON `a`.`currency_id`=`b`.`id`
JOIN `cpm_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`currency_type`="fiat" AND `a`.`user_status`="Completed" ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM `cpm_transactions` as `a` JOIN `cpm_currency` as `b` ON `a`.`currency_id`=`b`.`id` JOIN `cpm_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`currency_type`="fiat" AND `a`.`user_status`="Completed" ORDER BY `a`.`trans_id` DESC');
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
						ucfirst(getUserEmail($result->user_id)),
						date("d-m-Y h:i a", $result->datetime),
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



 	// Crypto Fiat Withdraw Ajax

 	 	function cryptofiat_withdraw_ajax()
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
            4=>'currency_name',
            5=>'amount',
            6=>'fee',
            7=>'transfer_amount',
            8=>'status',
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
        $like = '';

        if(!empty($search))
        { 
            $like = " AND (b.currency_symbol LIKE '%".$search."%' OR c.cpm_username LIKE '%".$search."%' OR a.transaction_id LIKE '%".$search."%' OR a.amount LIKE '%".$search."%')";

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM cpm_transactions as a JOIN cpm_currency as b ON a.currency_id=b.id JOIN cpm_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.payment_method='Crypto-Fiat' AND a.user_status='Completed'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;
$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM cpm_transactions as a JOIN cpm_currency as b ON a.currency_id=b.id JOIN cpm_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND a.payment_method='Crypto-Fiat' AND a.user_status='Completed'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM `cpm_transactions` as `a` JOIN `cpm_currency` as `b` ON `a`.`currency_id`=`b`.`id`
JOIN `cpm_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`payment_method`="Crypto-Fiat" AND `a`.`user_status`="Completed" ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username FROM `cpm_transactions` as `a` JOIN `cpm_currency` as `b` ON `a`.`currency_id`=`b`.`id` JOIN `cpm_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`payment_method`="Crypto-Fiat" AND `a`.`user_status`="Completed" ORDER BY `a`.`trans_id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
	            $edit = '<a href="' . admin_url() . 'withdraw/cryptofiat_view/' . $result->trans_id . '" data-placement="top" data-toggle="popover" data-content="View the withdraw details." class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';				
				
			    $data[] = array(
					    $i, 
						$result->username,
						gmdate("d-m-Y h:i a", strtotime($result->datetime)),
						$result->currency_symbol,
						getcurrencySymbol($result->fiat_currency),
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
            $like = " AND (b.currency_symbol LIKE '%".$search."%' OR c.cpm_username LIKE '%".$search."%' OR a.amount LIKE '%".$search."%')";

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.cpm_username as username FROM cpm_transactions as a JOIN cpm_currency as b ON a.currency_id=b.id JOIN cpm_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND `a`.`payment_method`!='Crypto-Fiat' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.cpm_username as username FROM cpm_transactions as a JOIN cpm_currency as b ON a.currency_id=b.id JOIN cpm_users as c ON a.user_id=c.id WHERE a.type='Withdraw' AND `a`.`payment_method`!='Crypto-Fiat' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.cpm_username as username FROM `cpm_transactions` as `a` JOIN `cpm_currency` as `b` ON `a`.`currency_id`=`b`.`id`
JOIN `cpm_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw"  AND `a`.`payment_method`!="Crypto-Fiat"   AND `a`.`currency_type`="crypto" ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, c.cpm_username as username FROM `cpm_transactions` as `a` JOIN `cpm_currency` as `b` ON `a`.`currency_id`=`b`.`id` JOIN `cpm_users` as `c` ON `a`.`user_id`=`c`.`id` WHERE `a`.`type`="Withdraw" AND `a`.`payment_method`!="Crypto-Fiat" AND `a`.`currency_type`="crypto" ORDER BY `a`.`trans_id` DESC');
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
				
					$data[] = array(
					    $i, 
						ucfirst(getUserEmail($result->user_id)),
						$result->datetime,
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
		$hiswhere = array('a.type'=>'Withdraw','a.currency_type'=>'fiat');

		$data['withdraw'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username','','','','','',array('a.trans_id', 'DESC'))->result();
		// echo $this->db->last_query();
		// exit();

		
		$data['prefix'] = get_prefix();
		$data['title'] = 'Withdraw Management';
		$data['meta_keywords'] = 'Withdraw Management';
		$data['meta_description'] = 'Withdraw Management';
		$data['main_content'] = 'withdraw/withdraw';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}


	// Crypto To Fiat Withdraw
	function cryptofiat_withdraw() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Withdraw','a.payment_method'=>'Crypto-Fiat');

		$data['withdraw'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username','','','','','',array('a.trans_id', 'DESC'))->result();
		

		
		$data['prefix'] = get_prefix();
		$data['title'] = 'Withdraw Management';
		$data['meta_keywords'] = 'Withdraw Management';
		$data['meta_description'] = 'Withdraw Management';
		$data['main_content'] = 'withdraw/cryptofiat_withdraw';
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
		$data['withdraw'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username','','','','','',array('a.trans_id', 'DESC'))->row();
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


	function cryptofiat_view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$hisjoins = array('currency as b'=>'a.currency_id = b.id','users as c'=>'a.user_id = c.id');
		$hiswhere = array('a.type'=>'Withdraw', 'a.trans_id'=>$id,'a.payment_method'=>'Crypto-Fiat','a.user_status'=>'Completed');
		$data['withdraw'] = $this->common_model->getJoinedTableData('transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.cpm_username as username','','','','','',array('a.trans_id', 'DESC'))->row();
		$data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$data['withdraw']->user_id,'currency'=>$data['withdraw']->fiat_currency))->row();
		// echo '<pre>'; print_r($data['user_bankdetails']); die;
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'withdraw/view/' . $id;
		$data['title'] = 'Withdraw Management';
		$data['meta_keywords'] = 'Withdraw Management';
		$data['meta_description'] = 'Withdraw Management';
		$data['main_content'] = 'withdraw/cryptofiat_withdraw';
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



		function crypto_fiat_confirm($id)
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('withdraw/cryptofiat_withdraw');
		}
		$isValids = $this->common_model->getTableData('transactions', array('trans_id' => $id, 'type' =>'Withdraw', 'status'=>'Pending','payment_method'=>'Crypto-Fiat','user_status'=>'Completed'));
		$isValid = $isValids->num_rows();
		$withdraw = $isValids->row();
		if ($isValid > 0) 
		{
		    $user_id = $withdraw->user_id; // user id
			$amount = $withdraw->transfer_amount; //withdraw amount with fees
			$currency = $withdraw->currency_id; // Currency id
			$currency_name = getcryptocurrency($withdraw->currency_id);
			
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
				'currency_type'=>'Crypto-Fiat'
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
			'###TX###' => $withdraw->transaction_id,
			);
		    $this->email_model->sendMail($email, '', '', $email_template, $special_vars);
			if ($update) 
			{ 
				$this->session->set_flashdata('success', 'Withdraw amount sent to user successfully');
			    admin_redirect('withdraw/cryptofiat_withdraw');
		    } 
		    else 
		    { 
				$this->session->set_flashdata('error', 'Problem occure with send amount to user');
				admin_redirect('withdraw/cryptofiat_withdraw');	
		    }
		}
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this Withdraw');
			admin_redirect('withdraw/cryptofiat_withdraw');
		}		
	

	}




	function paypal_pay($id) 
	{
		// Is logged in
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
		if (!$sessionvar) {
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
