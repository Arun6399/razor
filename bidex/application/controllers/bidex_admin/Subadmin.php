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
 class Subadmin extends CI_Controller {
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

		$data['subadmin'] = $this->common_model->customQuery("select * from xera_admin where id!='1' and role='2'");
		$data['title'] = 'Subadmin';
		$data['meta_keywords'] = 'Subadmin';
		$data['meta_description'] = 'Subadmin';
		$data['main_content'] = 'subadmin/view_subadmin';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function edit($id)
	{

		$sessionvar=$this->session->userdata('loggeduser');

		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('subadmin');
		}

		$isValid = $this->common_model->getTableData('admin', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this subadmin details');
			admin_redirect('subadmin');
		}

		$data['row'] = $isValid->row();



		if ($this->input->post()) {
			
				$updateData=array();

				$updateData['admin_name'] = $this->input->post('name');	
				
				$updateData['email_id'] = encryptIt($this->input->post('emailid'));

				$updateData['password'] = encryptIt($this->input->post('password'));	

				$updateData['status'] = $this->input->post('status');	

				$privilege = $this->input->post('privilege');	

				$updateData['permissions']  = implode('&&&',$privilege);

				


				$condition = array('id' => $id);
				// updated via Common model
				$update = $this->common_model->updateTableData('admin', $condition, $updateData);

				if(getAdminDetails($id,'code')!=$this->input->post('patterncode'))
				{
					$this->common_model->updateTableData('admin', array('id' => $id), array('code'=>strrev($this->input->post('patterncode'))));
				}

				

				if ($update) {
					$this->session->set_flashdata('success', 'Subadmin details has been updated successfully!');
					admin_redirect('subadmin', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this subadmin detail');
					admin_redirect('subadmin/edit/' . $id, 'refresh');
				}
			
			
		}
	
		$data['action'] = admin_url() . 'subadmin/edit/' . $id;
		$data['title'] = 'Edit Sub Admin Details';
		$data['meta_keywords'] = 'Edit Sub Admin Details';
		$data['meta_description'] = 'Edit Sub Admin Details';
		$data['main_content'] = 'subadmin/edit_subadmin';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
		
	}

	function add($id)
	{

		$sessionvar=$this->session->userdata('loggeduser');

		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}



		if ($this->input->post()) {
			
				$insert=array();

				$insert['admin_name'] = $this->input->post('name');	
				
				$insert['email_id'] = encryptIt($this->input->post('emailid'));

				$insert['password'] = encryptIt($this->input->post('password'));	

				$insert['status'] = $this->input->post('status');	

				$privilege = $this->input->post('privilege');	

				$insert['permissions']  = implode('&&&',$privilege);


				$insert['role'] = '2';
				
				// updated via Common model
				$insert = $this->common_model->insertTableData('admin',$insert);

				$id = $this->db->insert_id();

				if(getAdminDetails($id,'code')!=$this->input->post('patterncode'))
				{
					$this->common_model->updateTableData('admin', array('id' => $id), array('code'=>strrev($this->input->post('patterncode'))));
				}

				

				if ($insert) {
					$this->session->set_flashdata('success', 'Subadmin details has been inserted successfully!');
					admin_redirect('subadmin', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to insert this subadmin detail');
					admin_redirect('subadmin/add/', 'refresh');
				}
			
			
		}
	
		$data['action'] = admin_url() . 'subadmin/add/';
		$data['title'] = 'Add Sub Admin Details';
		$data['meta_keywords'] = 'Add Sub Admin Details';
		$data['meta_description'] = 'Add Sub Admin Details';
		$data['main_content'] = 'subadmin/add_subadmin';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
		
	}


	
 }