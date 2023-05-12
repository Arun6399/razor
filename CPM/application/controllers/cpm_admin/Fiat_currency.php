<?php
/**
 * Currency class
 * @package Spiegel Technologies
 * @subpackage moonex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Fiat_currency extends CI_Controller {
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
		// Get the list pages
		$data['fiat_currency'] = $this->common_model->getTableData('fiat_currency', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Fiat Currency Management';
		$data['meta_keywords'] = 'Fiat Currency Management';
		$data['meta_description'] = 'Fiat Currency Management';
		$data['main_content'] = 'fiat_currency/fiat_currency';
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
		$this->form_validation->set_rules('currency_name', 'Currency Name', 'required|xss_clean');
		$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'required|xss_clean');

		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$image = $_FILES['image']['name'];
				if($image!="") {
				$uploadimage=cdn_file_upload($_FILES["image"],'uploads/currency');
				if($uploadimage)
				{
					$image=$uploadimage['secure_url'];
				}
				else
				{
					$this->session->set_flashdata('error','Problem with your currency image');
					admin_redirect('fiat_currency/add', 'refresh');
				}
				} 
				else 
				{ 
					$image=""; 
				}



				$insertData=array();
				$currency_name=$this->input->post('currency_name');
				$currency_symbol=$this->input->post('currency_symbol');
				$details=$this->common_model->getTableData('fiat_currency',array('currency_name'=>$currency_name),'','','',array('currency_symbol'=>$currency_symbol));
				if($details->num_rows()==0)
				{
					$insertData['currency_name'] = $currency_name;
					$insertData['image'] = $image;
					// $insertData['min_deposit_limit'] = $this->input->post('min_deposit_limit');
					// $insertData['max_deposit_limit'] = $this->input->post('max_deposit_limit');
					// $insertData['min_withdraw_limit'] = $this->input->post('min_withdraw_limit');
					// $insertData['max_withdraw_limit'] = $this->input->post('max_withdraw_limit');
					// $insertData['deposit_fees_type'] = $this->input->post('deposit_fees_type');
					// $insertData['deposit_fees'] = $this->input->post('deposit_fees');
					// $insertData['deposit_max_fees'] = $this->input->post('deposit_max_fees');
					// $insertData['reserve_Amount'] = $this->input->post('reserve_Amount');

					// $insertData['withdraw_fees_type'] = $this->input->post('withdraw_fees_type');
					// $insertData['withdraw_fees'] = $this->input->post('withdraw_fees');
					$insertData['currency_symbol'] = $currency_symbol;
					$insertData['status'] = $this->input->post('status');
					$insertData['created'] = gmdate(time());					
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('fiat_currency', $insertData);
					if ($insert) {
						$this->session->set_flashdata('success', 'Fiat Currency has been added successfully!');
						admin_redirect('fiat_currency', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to add the fiat currency !');
						admin_redirect('fiat_currency/add', 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to add the currency !');
					admin_redirect('fiat_currency/add', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('fiat_currency/add', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'fiat_currency/add';
		$data['title'] = 'Add Fiat Currency';
		$data['meta_keywords'] = 'Add Fiat Currency';
		$data['meta_description'] = 'Add Fiat Currency';
		$data['main_content'] = 'fiat_currency/fiat_currency';
		$data['view'] = 'add';
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
			admin_redirect('fiat_currency');
		}
		$isValid = $this->common_model->getTableData('fiat_currency', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('fiat_currency');
		}
		// Form validation
		$this->form_validation->set_rules('currency_name', 'Currency Name', 'required|xss_clean');
		$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'required|xss_clean');	
	
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$image = $_FILES['image']['name'];
				if($image!="") {
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/currency',$isValid->row('image'));
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your currency image');
						admin_redirect('fiat_currency/edit/' . $id, 'refresh');
					}
				} else {
					$image = $this->input->post('oldimage');
				}

				$updateData=array();
				$currency_name=$this->input->post('currency_name');
				$currency_symbol=$this->input->post('currency_symbol');
				$details=$this->common_model->getTableData('fiat_currency',array('currency_name'=>$currency_name),'','','',array('currency_symbol'=>$currency_symbol));
				// echo $this->db->last_query();die;
				if($details->num_rows()==0||$details->row('id')==$id)
				{
					$updateData['currency_name'] = $currency_name;
					$updateData['currency_symbol'] = $currency_symbol;
					$updateData['image'] = $image;
					// $updateData['min_deposit_limit'] = $this->input->post('min_deposit_limit');
					// $updateData['max_deposit_limit'] = $this->input->post('max_deposit_limit');
					// $updateData['min_withdraw_limit'] = $this->input->post('min_withdraw_limit');
					// $updateData['max_withdraw_limit'] = $this->input->post('max_withdraw_limit');
					// $updateData['deposit_fees_type'] = $this->input->post('deposit_fees_type');
					// $updateData['deposit_fees'] = $this->input->post('deposit_fees');
					// $updateData['deposit_max_fees'] = $this->input->post('deposit_max_fees');
					// $updateData['reserve_Amount'] = $this->input->post('reserve_Amount');

					// $updateData['withdraw_fees_type'] = $this->input->post('withdraw_fees_type');
					// $updateData['withdraw_fees'] = $this->input->post('withdraw_fees');
					$updateData['status'] = $this->input->post('status');			
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('fiat_currency', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Fiat Currency has been updated successfully!');
						admin_redirect('fiat_currency', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this fiat currency');
						admin_redirect('fiat_currency/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this fiat currency');
					admin_redirect('fiat_currency/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this fiat currency');
				admin_redirect('fiat_currency/edit/' . $id, 'refresh');
			}
			
		}
		$data['fiat_currency'] = $isValid->row();
		$data['action'] = admin_url() . 'fiat_currency/edit/' . $id;
		$data['title'] = 'Edit Fiat Currency';
		$data['meta_keywords'] = 'Edit Fiat Currency';
		$data['meta_description'] = 'Edit Fiat Currency';
		$data['main_content'] = 'fiat_currency/fiat_currency';
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
			admin_redirect('fiat_currency');
		}
		$isValid = $this->common_model->getTableData('fiat_currency', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('fiat_currency', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Fiat Currency activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Fiat Currency de-activated successfully');
				}
				admin_redirect('fiat_currency');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with fiat currency status updation');
				admin_redirect('fiat_currency');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this fiat currency');
			admin_redirect('fiat_currency');
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
			admin_redirect('fiat_currency');
		}
		$isValid = $this->common_model->getTableData('fiat_currency', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('fiat_currency', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Fiat Currency deleted successfully');
				admin_redirect('fiat_currency');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with fiat currency deletion');
				admin_redirect('fiat_currency');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('fiat_currency');
		}	
	}
 }