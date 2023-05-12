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
 class Request_coins extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$coins_request = $this->common_model->getTableData('currency',array('verify_request'=>0))->result_array();
		if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
			$search_string = $_GET['search_string'];	
			$like = array("currency_symbol"=>$search_string);
			$like_or = array("username"=>$search_string, "currency_name"=>$search_string, "online_usdprice"=>$search_string, "max_supply"=>$search_string);
			$where = array('status'=>0);
			$data['coins_request'] = $this->common_model->getTableData('currency',array('verify_request'=>0),'',$like,'',$like_or,'','',array('id', 'DESC'));
		}
		else{
   		$perpage = max_records();
  		$urisegment = $this->uri->segment(4);  
   		$base="coins_request/index";
   		$total_rows = count($coins_request);
   		pageconfig($total_rows,$base,$perpage);
   		//$where = array('status'=>0);
		$data['coins_request'] = $this->common_model->getTableData('currency',array('verify_request'=>0));
		}
		$data['prefix'] = get_prefix();
		$data['title'] = 'Currency request Management';
		$data['meta_keywords'] = 'Currency request Managementt';
		$data['meta_description'] = 'Currency request Management';
		$data['main_content'] = 'request_coins/request_coins';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$where = array('verify_request'=>0);
		$data['coins_request'] = $this->common_model->getTableData('currency', array('id'=>$id))->row();
		// echo '<pre>'; print_r($data['user_bankdetails']); die;
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'request_coins/view/' . $id;
		$data['title'] = 'Currency request Management';
		$data['meta_keywords'] = 'Currency request Management';
		$data['meta_description'] = 'Currency request Management';
		$data['main_content'] = 'request_coins/request_coins';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
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
			admin_redirect('request_coins');
		}
		$isValids = $this->common_model->getTableData('currency', array('id' => $id));
		$isValid = $isValids->num_rows();
		$coins_request = $isValids->row();
		if ($isValid > 0) { // Check is valid banner 
			$username = $coins_request->username;
			$email = $coins_request->email;
			$coin = $coins_request->currency_name;
			$updateData['verify_request'] = 1;
			$updateData['status'] = 1;
			$condition = array('id' => $id);
			$update = $this->common_model->updateTableData('currency', $condition, $updateData);
			/*$email_template = 'Coin_accept';
			$special_vars = array(
			'###USERNAME###' => $username,
			'###COIN###' => $coin
			);
			//-----------------
			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);*/
			if ($update) { // True // Update success
					$this->session->set_flashdata('success', 'Coin request accepted successfully');
				admin_redirect('request_coins');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with accept coin request');
				admin_redirect('request_coins');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this coin request');
			admin_redirect('request_coins');
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
			admin_redirect('request_coins');
		}
		$isValids = $this->common_model->getTableData('currency', array('id' => $id));
		$isValid = $isValids->num_rows();
		$coins_request = $isValids->row();
		if ($isValid > 0) { // Check is valid banner 
			$username = $coins_request->username;
			$email = $coins_request->email;
			$coin = $coins_request->currency_name;
			$updateData['verify_request'] = 2;
			$condition = array('id' => $id);
			$update = $this->common_model->updateTableData('currency', $condition, $updateData);

		/*	$email_template = 'Coin_cancel';
			$special_vars = array(
			'###USERNAME###' => $username,
			'###COIN###' => $coin
			);
			//-----------------
			$this->email_model->sendMail($email, '', '', $email_template, $special_vars);*/
			
			if ($update) { // True // Update success
					$this->session->set_flashdata('success', 'Coin request cancelled successfully');
				admin_redirect('request_coins');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with cancel coin request');
				admin_redirect('request_coins');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this coin request');
			admin_redirect('request_coins');
		}
	}

 }
 
 /**
 * End of the file PAIR.php
 * Location: ./application/controllers/ulawulo/pair.php
 */	
