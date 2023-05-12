<?php
/**
 * Pair class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Pair extends CI_Controller {
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
		// Get the pairs list pages
		$joins = array('fiat_currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		//$data['pair'] = $this->common_model->getTableData('pair', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['pair'] = $this->common_model->getJoinedTableData('pair as a',$joins,'','a.*,b.currency_name as from_currency,b.currency_symbol as from_currency_symbol,c.currency_name as to_currency,c.currency_symbol as to_currency_symbol');
		//print_r($data['pair']->result());die;
		$data['title'] = 'Pairs Management';
		$data['meta_keywords'] = 'Pairs Management';
		$data['meta_description'] = 'Pairs Management';
		$data['main_content'] = 'pair/pair';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Add
	function add() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('from_name', 'From Symbol', 'required|xss_clean');
		$this->form_validation->set_rules('to_name', 'To Symbol', 'required|xss_clean');
		$this->form_validation->set_rules('commision_type', 'Commision Type', 'required|xss_clean');		
		$this->form_validation->set_rules('commision_value', 'Commision Value', 'required|numeric|xss_clean');


		$this->form_validation->set_rules('minval_from_currency', 'Minimum value - From currency', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('minval_to_currency', 'Minimum value - To currency', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('maxval_from_currency', 'Maximum value - From currency', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('maxval_to_currency', 'Maximum value - To currency', 'required|numeric|xss_clean');		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$insertData=array();
				$from_symbol_id=$this->input->post('from_name');
				$to_symbol_id=$this->input->post('to_name');
				$from_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$from_symbol_id));
				$to_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$to_symbol_id));
				if($from_symbol_details->num_rows()==1&&$to_symbol_details->num_rows()==1)
				{
					if($this->input->post('minval_from_currency')>$this->input->post('maxval_from_currency'))
					{
						$this->session->set_flashdata('error', 'Minimum value from currency is greater than maximum value from currency');
						admin_redirect('pair/add', 'refresh');
					}
					if($this->input->post('minval_to_currency')>$this->input->post('maxval_to_currency'))
					{
						$this->session->set_flashdata('error', 'Minimum value to currency is greater than maximum value to currency');
						admin_redirect('pair/add/', 'refresh');
					}
					$insertData['from_symbol_id'] = $from_symbol_id;
					$insertData['to_symbol_id'] = $to_symbol_id;
					$insertData['commision_type'] = $this->input->post('commision_type');
					$insertData['commision_value'] = $this->input->post('commision_value');
					$insertData['status'] = $this->input->post('status');

					$insertData['buy_offer_1'] = $this->input->post('buy_offer_1');
					$insertData['buy_offer_2'] = $this->input->post('buy_offer_2');
					$insertData['buy_offer_3'] = $this->input->post('buy_offer_3');
					$insertData['buy_offer_4'] = $this->input->post('buy_offer_4');

					$insertData['sell_offer_1'] = $this->input->post('sell_offer_1');
					$insertData['sell_offer_2'] = $this->input->post('sell_offer_2');
					$insertData['sell_offer_3'] = $this->input->post('sell_offer_3');
					$insertData['sell_offer_4'] = $this->input->post('sell_offer_4');

					$insertData['minval_from_currency'] = $this->input->post('minval_from_currency');
					$insertData['minval_to_currency'] = $this->input->post('minval_to_currency');
					$insertData['maxval_from_currency'] = $this->input->post('maxval_from_currency');
					$insertData['maxval_to_currency'] = $this->input->post('maxval_to_currency');	

					$insertData['online_price'] = $this->input->post('online_price');
					$insertData['created'] = gmdate(time());					
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('pair', $insertData);
					if ($insert) {
						$this->session->set_flashdata('success', 'Exchange pair has been added successfully!');
						admin_redirect('pair', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to add the exchange pair !');
						admin_redirect('pair/add', 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to add the exchange pair !');
					admin_redirect('pair/add', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('pair/add', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'pair/add';
		$data['title'] = 'Add Exchange Pair';
		$data['meta_keywords'] = 'Add Exchange Pair';
		$data['meta_description'] = 'Add Exchange Pair';
		$data['main_content'] = 'pair/pair';
		$data['view'] = 'add';
		$data['currency']=$this->common_model->getTableData('fiat_currency', array('status' => 1));
		$data['fiat_currency']=$this->common_model->getTableData('fiat_currency', array('status' => 1));
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
			admin_redirect('pair');
		}
		$isValid = $this->common_model->getTableData('pair', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('pair');
		}
		// Form validation
		$this->form_validation->set_rules('from_name', 'From Symbol', 'required|xss_clean');
		$this->form_validation->set_rules('to_name', 'To Symbol', 'required|xss_clean');
		$this->form_validation->set_rules('commision_type', 'Commision Type', 'required|xss_clean');		
		$this->form_validation->set_rules('commision_value', 'Commision Value', 'required|numeric|xss_clean');	

		$this->form_validation->set_rules('minval_from_currency', 'Minimum value - From currency', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('minval_to_currency', 'Minimum value - To currency', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('maxval_from_currency', 'Maximum value - From currency', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('maxval_to_currency', 'Maximum value - To currency', 'required|numeric|xss_clean');		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$from_symbol_id=$this->input->post('from_name');
				$to_symbol_id=$this->input->post('to_name');
				$from_symbol_details=$this->common_model->getTableData('fiat_currency', array('status' => 1,'id'=>$from_symbol_id));
				$to_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$to_symbol_id));
				if($from_symbol_details->num_rows()==1&&$to_symbol_details->num_rows()==1)
				{
					if($this->input->post('minval_from_currency')>$this->input->post('maxval_from_currency'))
					{
						$this->session->set_flashdata('error', 'Minimum value from currency is greater than maximum value from currency');
						admin_redirect('pair/edit/' . $id, 'refresh');
					}
					if($this->input->post('minval_to_currency')>$this->input->post('maxval_to_currency'))
					{
						$this->session->set_flashdata('error', 'Minimum value to currency is greater than maximum value to currency');
						admin_redirect('pair/edit/' . $id, 'refresh');
					}
					$updateData['from_symbol_id'] = $from_symbol_id;
					$updateData['to_symbol_id'] = $to_symbol_id;
					$updateData['commision_type'] = $this->input->post('commision_type');
					$updateData['commision_value'] = $this->input->post('commision_value');
					$updateData['status'] = $this->input->post('status');

					$updateData['online_price'] = $this->input->post('online_price');

					$updateData['buy_offer_1'] = $this->input->post('buy_offer_1');
					$updateData['buy_offer_2'] = $this->input->post('buy_offer_2');
					$updateData['buy_offer_3'] = $this->input->post('buy_offer_3');
					$updateData['buy_offer_4'] = $this->input->post('buy_offer_4');
					$updateData['sell_offer_1'] = $this->input->post('sell_offer_1');
					$updateData['sell_offer_2'] = $this->input->post('sell_offer_2');
					$updateData['sell_offer_3'] = $this->input->post('sell_offer_3');
					$updateData['sell_offer_4'] = $this->input->post('sell_offer_4');

					$updateData['minval_from_currency'] = $this->input->post('minval_from_currency');
					$updateData['minval_to_currency'] = $this->input->post('minval_to_currency');
					$updateData['maxval_from_currency'] = $this->input->post('maxval_from_currency');
					$updateData['maxval_to_currency'] = $this->input->post('maxval_to_currency');			
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('pair', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Exchange pair has been updated successfully!');
						admin_redirect('pair', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this pair');
						admin_redirect('pair/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this pair');
					admin_redirect('pair/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this pair');
				admin_redirect('pair/edit/' . $id, 'refresh');
			}
			
		}
		$data['pair'] = $isValid->row();
		$data['action'] = admin_url() . 'pair/edit/' . $id;
		$data['title'] = 'Edit Pair';
		$data['meta_keywords'] = 'Edit Pair';
		$data['meta_description'] = 'Edit Pair';
		$data['main_content'] = 'pair/pair';
		$data['view'] = 'edit';
		$data['currency']=$this->common_model->getTableData('fiat_currency', array('status' => 1));
		$from_symbol = $data['pair']->from_symbol_id;
		$to_symbol = $data['pair']->to_symbol_id;
		$data['to_symbol']=$to_symbol;
		$old_pairs=$this->common_model->getTableData('pair', array('from_symbol_id' => $from_symbol,'to_symbol_id !='=>$to_symbol),'to_symbol_id')->result_array();
		if($old_pairs)
		{
			$old_pairs = array_column($old_pairs, 'to_symbol_id');
			$where_not=array('id',$old_pairs);
		}
		else
		{
			$where_not='';
		}
		//array_push($old_pairs,$from_symbol);
		$data['old_pairs']=$this->common_model->getTableData('currency',array('status' => 1),'id,currency_name','','','','','','','',$where_not);//die;
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
			admin_redirect('pair');
		}
		$isValid = $this->common_model->getTableData('pair', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('pair', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Currency pair activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Currency pair de-activated successfully');
				}
				admin_redirect('pair');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with currency pair status updation');
				admin_redirect('pair');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this currency pair');
			admin_redirect('pair');
		}
	}
	// Delete page
	function delete($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('pair');
		}
		$isValid = $this->common_model->getTableData('pair', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('pair', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Currency pair deleted successfully');
				admin_redirect('pair');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with currency pair deletion');
				admin_redirect('pair');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('pair');
		}	
	}
	function get_to_symbol()
	{
		$from_symbol = $this->input->get_post('from_symbol');
		$to_symbol = $this->input->get_post('to_symbol');
		$cond=array('from_symbol_id' => $from_symbol);
		if($to_symbol&&$to_symbol!=0)
		{
			$cond['to_symbol_id !=']=$to_symbol;
		}
		$old_pairs=$this->common_model->getTableData('pair', $cond,'to_symbol_id')->result_array();
		if($old_pairs)
		{
			$old_pairs = array_column($old_pairs, 'to_symbol_id');
			//print_r($old_pairs);die;
			$arr=array('id',$old_pairs);
		}
		else
		{
			$arr='';
		}
		//print_r($old_pairs);die;
		$pairs=$this->common_model->getTableData('currency',array('status' => 1),'id,currency_name','','','','','','','',$arr);
		$txt='<option value="">Select</option>';
		foreach($pairs->result() as $cur){
		$txt.='<option value="'.$cur->id.'">'.$cur->currency_name.'</option>';
		}
		echo $txt;
	}
 }