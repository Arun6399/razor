<?php
/**
 * Trade_pairs class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Testimonials extends CI_Controller {
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
		$data['testimonials'] = $this->common_model->getTableData('testimonials',array(''));
		$data['title'] = 'Testimonials Management';
		
		$data['meta_keywords'] = 'Testimonials Management';
		$data['meta_description'] = 'Testimonials Management';
		$data['main_content'] = 'testimonials/testimonials';
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
		$this->form_validation->set_rules('name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('position', 'Position', 'required|xss_clean');	
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
					$this->session->set_flashdata('error','Problem with your testimonials image');
					admin_redirect('testimonials/add', 'refresh');
				}
				} 
				else 
				{ 
					$image=""; 
				}
				$lang_id= $this->input->post('lang');
				$insertData=array();
				
				if($lang_id==1)
				{
					$insertData['english_name'] = $this->input->post('english_name');
					$insertData['english_position'] = $this->input->post('english_position');
					$insertData['english_comments'] = $this->input->post('english_comments');
				}
				else if($lang_id==2)
				{
					$insertData['chinese_name'] = $this->input->post('chinese_name');
					$insertData['chinese_position'] = $this->input->post('chinese_position');
					$insertData['chinese_comments'] = $this->input->post('chinese_comments');
				}
				else if($lang_id==3)
				{
					$insertData['russian_name'] = $this->input->post('russian_name');
					$insertData['russian_position'] = $this->input->post('russian_position');
					$insertData['russian_comments'] = $this->input->post('russian_comments');
				}
				else 
				{
					$insertData['spanish_name'] = $this->input->post('spanish_name');
					$insertData['spanish_position'] = $this->input->post('spanish_position');
					$insertData['spanish_comments'] = $this->input->post('spanish_comments');
				}
				$insertData['image'] = $image;
				$insertData['status'] = $this->input->post('status');
				$insertData['added_date'] = gmdate(time());					
				// Prepare to insert Data
				$insert = $this->common_model->insertTableData('testimonials', $insertData);
				if ($insert) {
					$this->session->set_flashdata('success', 'Testimonials has been added successfully!');
					admin_redirect('testimonials', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to add the testimonials !');
					admin_redirect('testimonials/add', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('testimonials/add', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'testimonials/add';
		
		$data['title'] = 'Add Testimonials';
		$data['meta_keywords'] = 'Add Testimonials';
		$data['meta_description'] = 'Add Testimonials';
		$data['main_content'] = 'testimonials/testimonials';
		$data['view'] = 'add';
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
			admin_redirect('testimonials');
		}
		$isValid = $this->common_model->getTableData('testimonials', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('testimonials');
		}
		// Form validation
		$this->form_validation->set_rules('english_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('english_position', 'Position', 'required|xss_clean');
		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				
                $image = $_FILES['image']['name'];
				if($image!="") 
				{
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/currency',$isValid->row('image'));
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your testimonials image');
						admin_redirect('testimonials/edit/' . $id, 'refresh');
					}
				} 
				else 
				{
					$image = $this->input->post('oldimage');
				}
				$condition = array('id' => $id);
				$lang_id= $this->input->post('lang');

				$updateData=array();
				if($lang_id==1)
				{
					$updateData['english_name'] = $this->input->post('english_name');
					$updateData['english_position'] = $this->input->post('english_position');
					$updateData['english_comments'] = $this->input->post('english_comments');
				}
				else if($lang_id==2)
				{
					$updateData['chinese_name'] = $this->input->post('chinese_name');
					$updateData['chinese_position'] = $this->input->post('chinese_position');
					$updateData['chinese_comments'] = $this->input->post('chinese_comments');
				}
				else if($lang_id==3)
				{
					$updateData['russian_name'] = $this->input->post('russian_name');
					$updateData['russian_position'] = $this->input->post('russian_position');
					$updateData['russian_comments'] = $this->input->post('russian_comments');
				}
				else
				{
					$updateData['spanish_name'] = $this->input->post('spanish_name');
					$updateData['spanish_position'] = $this->input->post('spanish_position');
					$updateData['spanish_comments'] = $this->input->post('spanish_comments');
				}
				$updateData['image'] = $image;
				$updateData['status'] = $this->input->post('status');
				$condition = array('id' => $id);
				$update = $this->common_model->updateTableData('testimonials', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Testimonials has been updated successfully!');
					admin_redirect('testimonials', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this testimonials');
					admin_redirect('testimonials/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this testimonials');
				admin_redirect('testimonials/edit/' . $id, 'refresh');
			}
			
		}
		$data['testimonials'] = $isValid->row();
		$data['action'] = admin_url() . 'testimonials/edit/' . $id;
		$data['title'] = 'Edit Testimonials';
		$data['meta_keywords'] = 'Edit Testimonials';
		$data['meta_description'] = 'Edit Tstimonials';
		$data['main_content'] = 'testimonials/testimonials';
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
			admin_redirect('testimonials');
		}
		$isValid = $this->common_model->getTableData('testimonials', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('testimonials', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Testimonials activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Testimonials de-activated successfully');
				}
				admin_redirect('testimonials');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with testimonials status updation');
				admin_redirect('testimonials');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this testimonials');
			admin_redirect('testimonials');
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
			admin_redirect('testimonials');
		}
		$isValid = $this->common_model->getTableData('testimonials', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('testimonials', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Testimonials deleted successfully');
				admin_redirect('testimonials');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with testimonials deletion');
				admin_redirect('testimonials');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('testimonials');
		}	
	}
 }