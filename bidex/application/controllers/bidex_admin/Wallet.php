<?php
/**
 * Banners class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Wallet extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function wallet_ajax()
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
            1=>'bidex_email',
            2=>'verified',
            3=>'verified',            
            4=>'verified',
            5=>'verified'
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
        	if(strlen($search) > '4') {
        		$str = substr($search, 0, 4);
        		$str1 = substr($search, 4, strlen($search));
        		$like = " AND (users.bidex_email LIKE '%".$str1."%' AND history.bidex_type LIKE '%".encryptIt($str)."%')";
        	} 
        	if(strlen($search) <= '4') {
        		$str = substr($search, 0, strlen($search));
        		$like = " AND (users.bidex_email LIKE '%%' OR history.bidex_type LIKE '%".encryptIt($str)."%')";
        	}
            //$like = "AND bidex_users.bidex_email LIKE '%".$search."%'"; 
        }
        
        $query = "SELECT * FROM `bidex_users` AS users INNER JOIN bidex_history AS history INNER JOIN bidex_wallet AS wallet on users.id=history.user_id AND users.id=wallet.user_id WHERE wallet.status=1 ".$like." ORDER BY users.created_on DESC LIMIT ".$start.",".$length;
        $tt = $query;
        $users_history = $this->db->query($query);
        $users_history_result = $users_history->result(); 
        
		$num_rows = count($users_history_result);

		//To Find Total
		$query_total = "SELECT * FROM `bidex_users` AS users INNER JOIN bidex_history AS history INNER JOIN bidex_wallet AS wallet on users.id=history.user_id AND users.id=wallet.user_id WHERE wallet.status=1 ".$like." ORDER BY users.created_on DESC";
		$users_history_total = $this->db->query($query_total);
        $num_rows = $users_history_total->num_rows();

		if(count($users_history_result)>0)
		{
			foreach($users_history->result() as $users){
				$i++;
				$email = getUserEmail($users->user_id);
	            $wallet_det = $this->common_model->getTableData("wallet",array('user_id'=>$users->id))->row();

				if($wallet_det->status==1)
				{
					if ($users->verified == 1) {
						$status = '<label class="label label-info">Activated</label>';
					} else {
						$status = '<label class="label label-danger">De-Activated</label>';
					}

					$userbalance = '<a href="' . admin_url() . 'wallet/view/' . $users->id . '" data-placement="right" data-toggle="popover" data-content="View User Balance" class="poper"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp';
					$crypto_address = '<a href="' . admin_url() . 'wallet/view_address/' . $users->id . '" data-placement="bottom" data-toggle="popover" data-content="View User Crypto Address" class="poper"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';

					$link="'".admin_url().'wallet/delete/'.$users->id."'";

					$delete = '<a onclick="deleteaction('.$link.');" data-placement="top" data-toggle="popover" data-content="If you delete this? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
					$data[] = array(
					    $i, 
					    
						$email,
						$status,
						$userbalance,
						$crypto_address,
						$delete
					);
			    }
			    				
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

	// Manage gateways 
	function index() {

		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$data['users'] = $this->common_model->getTableData('users','',get_prefix().'username,id,verified', '', '', '', '', '', array('created_on', 'DESC'))->result();
		
		$data['title'] = 'Wallet Management';
		$data['meta_keywords'] = 'Wallet Management';
		$data['meta_description'] = 'Wallet Management';
		$data['main_content'] = 'wallets/wallets';
		$data['view']='view_all';
		$this->load->view('administrator/admin_template',$data); 

	}
	function view($id) {

		    $isValid = $this->common_model->getTableData('wallet',array('user_id' => $id))->row();
		    $data['userdetails'] = $this->common_model->getTableData('users', array('id' => $id), get_prefix().'username')->row();		
			if(!empty($isValid))
			{$data['wallets'] = $isValid;
			//$dsfvv = unserialize($isValid->crypto_amount);
			/*$fiat_amount=$dsfvv['Exchange AND Trading']['21'];*/
			$fiat_amount = unserialize($isValid->crypto_amount);
			$crypto_amount=unserialize($isValid->crypto_amount);}
			$fiat1 = $this->common_model->getTableData('currency',array('type'=>'fiat'),'id')->result_array();
			$fiat2 = $this->common_model->getTableData('currency',array('type'=>'fiat'),'currency_symbol')->result_array();
			$a = array_column($fiat1, 'id');
			$b = array_column($fiat2, 'currency_symbol');
			$data['fiat_currency'] = array_combine($a, $b);
			$crypto1 = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1),'id')->result_array();
			$crypto2 = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1),'currency_symbol')->result_array();
			$a1 = array_column($crypto1, 'id');
			$b1 = array_column($crypto2, 'currency_symbol');
			$data['crypto_currency'] = array_combine($a1, $b1);
			$data['urid'] = $id;
			$data['title'] = 'Wallet Management';
			$data['meta_keywords'] = 'Wallet Management';
			$data['meta_description'] = 'Wallet Management';
			$data['main_content'] = 'wallets/wallets';
			$data['view']='view_wallet';
			$this->load->view('administrator/admin_template',$data); 

	}

	function view_address($id) {

		    $isValid = $this->common_model->getTableData('crypto_address',array('user_id' => $id))->row();
		    $data['userdetails'] = $this->common_model->getTableData('users', array('id' => $id), get_prefix().'username')->row();		
			if(!empty($isValid))
			{$data['wallets'] = $isValid;
			$crypto_address=unserialize($isValid->address);}
            $data['userid'] = $id;
			$crypto1 = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1),'id')->result_array();
			$crypto2 = $this->common_model->getTableData('currency',array('type'=>'digital','status'=>1),'currency_symbol')->result_array();
			$a1 = array_column($crypto1, 'id');
			$b1 = array_column($crypto2, 'currency_symbol');
			$data['crypto_address'] = array_combine($a1, $b1);
			
			
			$data['title'] = 'Wallet Management';
			$data['meta_keywords'] = 'Wallet Management';
			$data['meta_description'] = 'Wallet Management';
			$data['main_content'] = 'wallets/wallets';
			$data['view']='view_address';
			$this->load->view('administrator/admin_template',$data); 

	}

	function get_address($userid)
	{
		$currency_symbol = $this->input->post('currency_symbol');
		$currency = $this->common_model->getTableData('currency',array('currency_symbol'=>$currency_symbol))->row();

	    $coin_address = getAddress($userid,$currency->id);
					
		$data['img'] =	"https://chart.googleapis.com/chart?cht=qr&chs=280x280&chl=$coin_address&choe=UTF-8&chld=L";
		$data['address'] = $coin_address;
		echo json_encode($data);
	}

	

	function status($id,$status) {
		// Is logged in
		if (!$this->ion_auth->logged_in()) {
			admin_redirect('admin');
		}	
		// Is admin logged in
		if (!$this->ion_auth->admin_logged_in()) {
			redirect('/', 'refresh');
		}
		
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('wallet/index');
		}
		$isValid = $this->common_model->getTableData('wallets', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid gateway 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('wallets', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == "active") {
					$this->session->set_flashdata('success', 'Wallet Activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Wallet De-activated successfully');
				}
				admin_redirect('wallet/index');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Wallet status updation');
				admin_redirect('wallet/index');
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Wallet');
			admin_redirect('wallet/index');
		}
	}

	// Delete Gateway
	function delete($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) 
		{
			admin_redirect('admin', 'refresh');

		}
		
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('wallet/index');
		}
		$isValid = $this->common_model->getTableData('wallet', array('user_id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('user_id' => $id);
			$delete = $this->common_model->updateTableData('wallet', $condition,array('status'=>0));
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Wallet Deleted successfully');
				admin_redirect('wallet/index');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Wallet status deletion');
				admin_redirect('wallet/index');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Wallet');
			admin_redirect('wallet/index');
		}	
	}

	// Edit Gateway
	function edit($id) {
		// Is logged in
		if (!$this->ion_auth->logged_in()) {
			admin_redirect('admin');
		}	
		// Is admin logged in
		if (!$this->ion_auth->admin_logged_in()) {
			redirect('/', 'refresh');
		}
		$bank_id = $this->session->userdata('bank_parent_id');
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('wallet/view/'.$bank_id);
		}
		$isValid = $this->common_model->getTableData('wallet', array('bank_id' => $id));

		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this bank');
			admin_redirect('wallet/view/'.$bank_id);
		}
		// Form validation
		$this->form_validation->set_rules('acc_name', 'Account Name', 'required|xss_clean');
		$this->form_validation->set_rules('acc_number', 'Account Number', 'required|xss_clean');
		$this->form_validation->set_rules('acc_branch', 'Branch', 'required|xss_clean');
		
		// Post 
		if ($this->input->post()) {

			if ($this->form_validation->run()) { // Validation run TRUE with Post data
				
				$acc_name        = $this->input->post('acc_name');
				$acc_number      = $this->input->post('acc_number');
				$acc_branch      = $this->input->post('acc_branch');

				$condition       = array('bank_id' => $id);
				
				$updateData['acc_name']   = $acc_name;
				$updateData['acc_number'] = $acc_number;
				$updateData['acc_branch'] = $acc_branch;

				// Bank updated via Common model
				$update = $this->common_model->updateTableData('wallet', $condition, $updateData);
				if ($update) { // True
					$this->session->set_flashdata('success', 'Your bank updated successfully');
					admin_redirect('wallet/view/'.$bank_id);
				} else { // False
					$this->session->set_flashdata('error', 'Problem with your bank update request');
					admin_redirect('wallet/edit/' . $id, 'refresh');
				}
				
			} else {
				$this->session->set_flashdata('error', 'Unable to update this bank');
				admin_redirect('wallet/edit/' . $id, 'refresh');
			}	 
		}
		$data['wallets']          = $isValid->row();
		
		$data['action']           = admin_url() . 'wallet/edit/' . $id;
		$data['title']            = 'Edit bank';
		$data['meta_keywords']    = 'Edit bank';
		$data['meta_description'] = 'Edit bank';
		$data['main_content']     = 'wallets/edit';
		$this->load->view('administrator/admin_template', $data);
	}
 }

/**
 * End of the file banners.php
 * Location: ./application/controllers/ulawulo/banner.php
 */
