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
 class Offers extends CI_Controller {
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
		
		$data['offers'] = $this->common_model->getTableData('offers');
		$data['title'] = 'Offers';
		$data['meta_keywords'] = 'Offers';
		$data['meta_description'] = 'Offers';
		$data['main_content'] = 'offers/offers';
		$data['view'] = 'view_all';
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
			admin_redirect('offers');
		}
		$isValid = $this->common_model->getTableData('offers', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('offers');
		}
		// Form validation
		$this->form_validation->set_rules('type', 'Type', 'required|xss_clean');
		$this->form_validation->set_rules('offer_1', 'Offer 1', 'required|xss_clean');
		$this->form_validation->set_rules('offer_2', 'Offer 2', 'required|xss_clean');
		$this->form_validation->set_rules('offer_3', 'Offer 3', 'required|xss_clean');
		$this->form_validation->set_rules('offer_4', 'Offer 4', 'required|xss_clean');
		$this->form_validation->set_rules('change_time', 'Change time', 'required|xss_clean');

		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
					$updateData['type'] = $this->input->post('type');
					$updateData['offer_1'] = $this->input->post('offer_1');
					$updateData['offer_2'] = $this->input->post('offer_2');	
					$updateData['offer_3'] = $this->input->post('offer_3');
					$updateData['offer_4'] = $this->input->post('offer_4');
					$updateData['change_time'] = $this->input->post('change_time');	
					$updateData['updated_date'] = gmdate(time());		
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('offers', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Offer has been updated successfully!');
						admin_redirect('offers', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this offer');
						admin_redirect('offers/edit/' . $id, 'refresh');
					}
				
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this offer');
				admin_redirect('offers/edit/' . $id, 'refresh');
			}
			
		}
		$data['offers'] = $isValid->row();
		$data['action'] = admin_url() . 'offers/edit/' . $id;
		$data['title'] = 'Edit Offer';
		$data['meta_keywords'] = 'Edit Offer';
		$data['meta_description'] = 'Edit Offer';
		$data['main_content'] = 'offers/offers';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}
	
 }