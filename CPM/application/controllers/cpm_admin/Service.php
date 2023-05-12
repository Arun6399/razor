<?php
/**
 * Currency class
 * @package Getcrypex
 * @subpackage Bluerico
 * @author Pilaventhiran
 * @version 1.0
 * @link http://getcrypex.com/
 * 
 */
 class Service extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		$this->load->library('cloudinarylib');
		
 	}
	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		// Get the list pages
		$data['service'] = $this->common_model->getTableData('service', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Service Management';
		$data['meta_keywords'] = 'Service Management';
		$data['meta_description'] = 'service Management';
		$data['main_content'] = 'service/service';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}



		function fiat() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		// Get the list pages
		$data['fiat'] = $this->common_model->getTableData('cpm_fiat_currency', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Fiat Management';
		$data['meta_keywords'] = 'Fiat Management';
		$data['meta_description'] = 'Fiat Management';
		$data['main_content'] = 'service/service';
		$data['view'] = 'fiat';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Add
	function add() 
	{		
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('service_name', 'Service Name', 'required|xss_clean');
		$this->form_validation->set_rules('service_currency', 'Currency', 'required|xss_clean');
		$this->form_validation->set_rules('service_commission', 'Commission', 'required|xss_clean');
		

		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				

				$insertData 						= array();
				$service_name 						= $this->input->post('service_name');
				$service_currency 					= $this->input->post('service_currency');
				$details 							= $this->db->query("SELECT * FROM `cpm_service` WHERE `service_name` = '".$service_name."' OR `currency` LIKE '%".$service_currency."'");
                   
                  
				if($details->num_rows()!=0)
				{
                         
					
					$insertData['service_name']      = $service_name;
					$insertData['currency']    = $service_currency;
					$insertData['commission']     = $this->input->post('service_commission');									
					$insertData['status'] 			= $this->input->post('status');
					
					
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('service', $insertData);
				    //echo $this->db->last_query();
				    //die;
					//echo "<br/>";
					if ($insert) {
						$this->session->set_flashdata('success', 'service has been add successfully!');
						admin_redirect('service', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to add this service');
						admin_redirect('service/add/' . $id, 'refresh');
					}
					
				}
				else
				{  
					$this->session->set_flashdata('error', 'Unable to add the currencys !');
					admin_redirect('service/add', 'refresh');
				}
			}
			else 
			{
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('service/add', 'refresh');
			}
			
		}
	
		$data['action'] = admin_url() . 'service/add';
		$data['title'] = 'Add Service';
		$data['meta_keywords'] = 'Add Service';
		$data['meta_description'] = 'Add Service';
		$data['main_content'] = 'service/service';
		$data['view'] = 'add';
		$data['service']=$this->common_model->getTableData('service', array('status' => 1));
		$this->load->view('administrator/admin_template', $data);
	}

		function add_fiat() 
	{		
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('currency_name', 'Currency Name', 'required|xss_clean');

		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				

				$insertData 						= array();
				$currency_name 						= $this->input->post('currency_name');
				$currency_symbol 					= $this->input->post('currency_symbol');

                $details=$this->common_model->getTableData('cpm_fiat_currency', array('currency_name' => $currency_name));
                  
				if($details->num_rows()==0)
				{
					
					$insertData['currency_name']      = $this->input->post('currency_name');
					$insertData['currency_symbol']    = $this->input->post('currency_symbol');
					$insertData['status']             = $this->input->post('status');


					
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('cpm_fiat_currency', $insertData);

					if ($insert) {
						$this->session->set_flashdata('success', 'service has been add successfully!');
						admin_redirect('service/fiat', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to add this service');
						admin_redirect('service/fiat' . $id, 'refresh');
					}
					
				}
				else
				{  
					$this->session->set_flashdata('error', 'Unable to add the currencys !');
					admin_redirect('service/fiat', 'refresh');
				}
			}
			else 
			{
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('service/fiat', 'refresh');
			}
			
		}
	
		$data['action'] = admin_url() . 'service/add_fiat';
		$data['title'] = 'Add Fiat';
		$data['meta_keywords'] = 'Add Fiat Currency';
		$data['meta_description'] = 'Add Service';
		$data['main_content'] = 'service/service';
		$data['view'] = 'add_fiat';
		$data['service']=$this->common_model->getTableData('service', array('status' => 1));
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
			admin_redirect('service');
		}
		$isValid = $this->common_model->getTableData('service', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('service');
		}
		// Form validation
		$this->form_validation->set_rules('service_name', 'Service Name', 'required|xss_clean');
		$this->form_validation->set_rules('service_currency', 'Currency', 'required|xss_clean');	
		
		if ($this->input->post()) {				
			
			//echo "<pre>";print_r($_POST); exit;

			if ($this->form_validation->run())
			{
				
				/*$image = $_FILES['image']['name'];
				if($image!="") {
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/service',$isValid->row('image'));
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your service image');
						admin_redirect('service/edit/' . $id, 'refresh');
					}
				} else {
					$image = $this->input->post('oldimage');
				} */

				$updateData=array();
				$service_name=$this->input->post('service_name');
				$service_currency=$this->input->post('service_currency');
				$service_commission=$this->input->post('service_commission');
				$details=$this->common_model->getTableData('service',array('service_name'=>$service_currency),'','','','');//array('currency_symbol'=>$currency_symbol)
				//echo $this->db->last_query();die;
				if($details->num_rows()==0||$details->row('id')==$id)
				{
					
					$updateData['service_name'] = $service_name;
					$updateData['currency'] = $service_currency;
					$updateData['commission'] = $service_commission;
					$updateData['status'] = $this->input->post('status');
					
					$condition = array('id' => $id);
					// updated via Common model
					//echo $this->db->last_query();die;
					$update = $this->common_model->updateTableData('service', $condition, $updateData);

					if ($update) {
						$this->session->set_flashdata('success', 'service has been updated successfully!');
						admin_redirect('service', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this service');
						admin_redirect('service/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this service');
					admin_redirect('service/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this service');
				admin_redirect('service/edit/' . $id, 'refresh');
			}
			
		}
		
		$data['service'] = $isValid->row();
		$data['action'] = admin_url() . 'service/edit/' . $id;
		$data['title'] = 'Edit Service';
		$data['meta_keywords'] = 'Edit Service';
		$data['meta_description'] = 'Edit Service';
		$data['main_content'] = 'service/service';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}

	function edit_fiat($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('service');
		}
		$isValid = $this->common_model->getTableData('cpm_fiat_currency', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('service');
		}
		// Form validation

		if ($this->input->post()) {				
			
			//echo "<pre>";print_r($_POST); exit;

			if ($this->form_validation->run())
			{
				
				/*$image = $_FILES['image']['name'];
				if($image!="") {
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/service',$isValid->row('image'));
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your service image');
						admin_redirect('service/edit/' . $id, 'refresh');
					}
				} else {
					$image = $this->input->post('oldimage');
				} */

				$updateData=array();
				$service_name=$this->input->post('service_name');
				$service_currency=$this->input->post('service_currency');
				$service_commission=$this->input->post('service_commission');
				$details=$this->common_model->getTableData('service',array('service_name'=>$service_currency),'','','','');//array('currency_symbol'=>$currency_symbol)
				//echo $this->db->last_query();die;
				if($details->num_rows()==0||$details->row('id')==$id)
				{
					
					$updateData['service_name'] = $service_name;
					$updateData['currency'] = $service_currency;
					$updateData['commission'] = $service_commission;
					$updateData['status'] = $this->input->post('status');
					
					$condition = array('id' => $id);
					// updated via Common model
					//echo $this->db->last_query();die;
					$update = $this->common_model->updateTableData('service', $condition, $updateData);

					if ($update) {
						$this->session->set_flashdata('success', 'service has been updated successfully!');
						admin_redirect('service', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this service');
						admin_redirect('service/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this service');
					admin_redirect('service/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this service');
				admin_redirect('service/edit/' . $id, 'refresh');
			}
			
		}
		
		$data['service'] = $isValid->row();
		$data['action'] = admin_url() . 'service/edit/' . $id;
		$data['title'] = 'Edit Service';
		$data['meta_keywords'] = 'Edit Service';
		$data['meta_description'] = 'Edit Service';
		$data['main_content'] = 'service/service';
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
			admin_redirect('service');
		}
		$isValid = $this->common_model->getTableData('service', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('service', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Service activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Service de-activated successfully');
				}
				admin_redirect('service');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Service status updation');
				admin_redirect('service');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Service');
			admin_redirect('service');
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
			admin_redirect('service');
		}
		$isValid = $this->common_model->getTableData('service', array('id' => $id))->num_rows();
		if ($isValid > 0) 
		{ 
			$rowS = $this->common_model->getTableData('service', array('id' => $id))->row();
		    $currency = $rowS->currency; 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('service', $condition);
			if ($delete) 
			{ 				
				//$tb_val=$this->db->dbprefix('crypto_address');
				//$this->db->query("ALTER TABLE ".$tb_val." DROP COLUMN ".($currency_symbol.'_status'));				
				$this->session->set_flashdata('success', 'Service deleted successfully');
				admin_redirect('service');
			} 
			else 
			{ //False
				$this->session->set_flashdata('error', 'Problem occure with service deletion');
				admin_redirect('service');	
			}
		} 
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('service');
		}	
	}


		function delete_fiat($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('service');
		}
		$isValid = $this->common_model->getTableData('cpm_fiat_currency', array('id' => $id))->num_rows();
		if ($isValid > 0) 
		{ 
			$rowS = $this->common_model->getTableData('cpm_fiat_currency', array('id' => $id))->row();
		    $currency = $rowS->currency; 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('cpm_fiat_currency', $condition);
			if ($delete) 
			{ 				
				//$tb_val=$this->db->dbprefix('crypto_address');
				//$this->db->query("ALTER TABLE ".$tb_val." DROP COLUMN ".($currency_symbol.'_status'));				
				$this->session->set_flashdata('success', 'Service deleted successfully');
				admin_redirect('service/fiat');
			} 
			else 
			{ //False
				$this->session->set_flashdata('error', 'Problem occure with service deletion');
				admin_redirect('service/fiat');	
			}
		} 
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('service/fiat');
		}	
	}






 }