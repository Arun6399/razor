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
 class Team extends CI_Controller {
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
		$data['team'] = $this->common_model->getTableData('team_members',array('status'=>1));
		$data['title'] = 'Team Management';
		
		$data['meta_keywords'] = 'Team Management';
		$data['meta_description'] = 'Team Management';
		$data['main_content'] = 'team/team';
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
		$this->form_validation->set_rules('designation', 'Designation', 'required|xss_clean');	
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
					$this->session->set_flashdata('error','Problem with your team image');
					admin_redirect('team/add', 'refresh');
				}
				} 
				else 
				{ 
					$image=""; 
				}

				$insertData=array();
				$insertData['name'] = $this->input->post('name');
				$insertData['designation'] = $this->input->post('designation');
				$insertData['image'] = $image;
				$insertData['fb_link'] = $this->input->post('fb_link');
				$insertData['twitter_link'] = $this->input->post('twitter_link');
				$updateData['linkedin_link'] = $this->input->post('linkedin_link');
				$insertData['status'] = $this->input->post('status');
				$insertData['added_date'] = gmdate(time());					
				// Prepare to insert Data
				$insert = $this->common_model->insertTableData('team_members', $insertData);
				if ($insert) {
					$this->session->set_flashdata('success', 'Team has been added successfully!');
					admin_redirect('team', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to add the team !');
					admin_redirect('team/add', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('team/add', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'team/add';
		
		$data['title'] = 'Add Team';
		$data['meta_keywords'] = 'Add Team';
		$data['meta_description'] = 'Add Team';
		$data['main_content'] = 'team/team';
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
			admin_redirect('team');
		}
		$isValid = $this->common_model->getTableData('team_members', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('team');
		}
		// Form validation
		$this->form_validation->set_rules('name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('designation', 'Designation', 'required|xss_clean');
		
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
						$this->session->set_flashdata('error','Problem with your team image');
						admin_redirect('team/edit/' . $id, 'refresh');
					}
				} else {
					$image = $this->input->post('oldimage');
				}

				$updateData=array();
				$updateData['name'] = $this->input->post('name');
				$updateData['designation'] = $this->input->post('designation');
				$updateData['image'] = $image;
				$updateData['fb_link'] = $this->input->post('fb_link');
				$updateData['twitter_link'] = $this->input->post('twitter_link');
				$updateData['linkedin_link'] = $this->input->post('linkedin_link');
				$updateData['status'] = $this->input->post('status');
				$condition = array('id' => $id);
				$update = $this->common_model->updateTableData('team_members', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Team has been updated successfully!');
					admin_redirect('team', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this team');
					admin_redirect('team/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this team');
				admin_redirect('team/edit/' . $id, 'refresh');
			}
			
		}
		$data['team'] = $isValid->row();
		$data['action'] = admin_url() . 'team/edit/' . $id;
		$data['title'] = 'Edit Team';
		$data['meta_keywords'] = 'Edit Team';
		$data['meta_description'] = 'Edit Team';
		$data['main_content'] = 'team/team';
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
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('team_members', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('team_members', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Team activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Team de-activated successfully');
				}
				admin_redirect('team');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with team status updation');
				admin_redirect('team');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this team');
			admin_redirect('team');
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
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('team_members', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('team_members', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Team deleted successfully');
				admin_redirect('team');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with team deletion');
				admin_redirect('team');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('team');
		}	
	}
 }