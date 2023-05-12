<?php
/**
 * Api class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Api extends CI_Controller {
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
		$data['category']=$this->common_model->getTableData('api_category');
		
		
		$joins = array('languages as b'=>'a.language = b.id');
		$data['category'] = $this->common_model->getJoinedTableData('api_category as a',$joins,'','a.*,b.name as languagename');
		
		$data['title'] = 'API Category Management';
		$data['meta_keywords'] = 'API Category Management';
		$data['meta_description'] = 'API Category Management';
		$data['main_content'] = 'api/api';
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
			admin_redirect('api');
		}
		$isValid = $this->common_model->getTableData('api_category', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('api');
		}
		// Form validation
		$this->form_validation->set_rules('name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('language', 'Language', 'required|xss_clean');
		$this->form_validation->set_rules('api_content', 'Content', 'required|xss_clean');	
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['name'] = $this->input->post('name');
				$updateData['language'] = $this->input->post('language');
				$updateData['content'] = $this->input->post('api_content');	
				$condition = array('id' => $id);
				$update = $this->common_model->updateTableData('api_category', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Category has been updated successfully!');
					admin_redirect('api', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this category');
					admin_redirect('api/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this category');
				admin_redirect('api/edit/' . $id, 'refresh');
			}
			
		}
		$data['category'] = $isValid->row();
		$data['action'] = admin_url() . 'api/edit/' . $id;
		$data['title'] = 'Edit API Category';
		$data['meta_keywords'] = 'Edit API Category';
		$data['meta_description'] = 'Edit API Category';
		$data['main_content'] = 'api/api';
		$data['view'] = 'edit';
		$data['language']=$this->common_model->getTableData('languages', array('status' => 1));
		$this->load->view('administrator/admin_template', $data);
	}
	// sub content list
	function content() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Get the pairs list pages
		$data['category']=$this->common_model->getTableData('api_content');
		
		
		$joins = array('languages as b'=>'a.language = b.id','api_category as c'=>'a.category_id = c.id');
		$data['category'] = $this->common_model->getJoinedTableData('api_content as a',$joins,'','a.*,b.name as languagename,c.name as categoryname');
		
		$data['title'] = 'API Sub Category Management';
		$data['meta_keywords'] = 'API Sub Category Management';
		$data['meta_description'] = 'API Sub Category Management';
		$data['main_content'] = 'api/api';
		$data['view'] = 'view_all_sub';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Edit content page
	function edit_sub($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('content');
		}
		$isValid = $this->common_model->getTableData('api_content', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('content');
		}
		// Form validation
		$this->form_validation->set_rules('name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('language', 'Language', 'required|xss_clean');
		$this->form_validation->set_rules('category_id', 'Category', 'required|xss_clean');
		$this->form_validation->set_rules('api_content', 'Content', 'required|xss_clean');	
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['name'] = $this->input->post('name');
				$updateData['language'] = $this->input->post('language');
				$updateData['content'] = $this->input->post('api_content');	
				$updateData['category_id'] = $this->input->post('category_id');	
				$condition = array('id' => $id);
				$update = $this->common_model->updateTableData('api_content', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Sub Category has been updated successfully!');
					admin_redirect('api/content', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this sub category');
					admin_redirect('api/edit_sub/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this sub category');
				admin_redirect('api/edit_sub/' . $id, 'refresh');
			}
			
		}
		$data['category'] = $isValid->row();
		$data['action'] = admin_url() . 'api/edit_sub/' . $id;
		$data['title'] = 'Edit API Sub Category';
		$data['meta_keywords'] = 'Edit API Sub Category';
		$data['meta_description'] = 'Edit API Sub Category';
		$data['main_content'] = 'api/api';
		$data['view'] = 'edit_sub';
		$data['language']=$this->common_model->getTableData('languages', array('status' => 1));
		$data['categories']=$this->common_model->getTableData('api_category', array('language' => $data['category']->language));
		$this->load->view('administrator/admin_template', $data);
	}
	// Add content
	function add_sub() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Form validation
		$this->form_validation->set_rules('name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('language', 'Language', 'required|xss_clean');
		$this->form_validation->set_rules('category_id', 'Category', 'required|xss_clean');
		$this->form_validation->set_rules('api_content', 'Content', 'required|xss_clean');	
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['name'] = $this->input->post('name');
				$updateData['language'] = $this->input->post('language');
				$updateData['content'] = $this->input->post('api_content');	
				$updateData['category_id'] = $this->input->post('category_id');
				$categoryname=$this->common_model->getTableData('api_category', array('id' => $this->input->post('category_id')),'name')->row('name');
				$updateData['param'] = seoUrl($categoryname).'-'.seoUrl($this->input->post('name'));
				$update = $this->common_model->insertTableData('api_content', $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Sub Category has been added successfully!');
					admin_redirect('api/content', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to add this sub category');
					admin_redirect('api/add_sub', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to add this sub category');
				admin_redirect('api/add_sub', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'api/add_sub';
		$data['title'] = 'Add API Sub Category';
		$data['meta_keywords'] = 'Add API Sub Category';
		$data['meta_description'] = 'Add API Sub Category';
		$data['main_content'] = 'api/api';
		$data['view'] = 'add_sub';
		$data['language']=$this->common_model->getTableData('languages', array('status' => 1));
		$data['categories']=$this->common_model->getTableData('api_category');
		$this->load->view('administrator/admin_template', $data);
	}
	// Delete page
	function delete_sub($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('api/content');
		}
		$isValid = $this->common_model->getTableData('api_content', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('api_content', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Api sub category deleted successfully');
				admin_redirect('api/content');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Api sub category deletion');
				admin_redirect('api/content');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('api/content');
		}	
	}
	//coding section
	// sub content list
	function coding() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Get the pairs list pages
		$data['category']=$this->common_model->getTableData('api_code');
		
		
		$joins = array('api_content as b'=>'a.content_id = b.id','api_category as c'=>'b.category_id = c.id');
		$data['category'] = $this->common_model->getJoinedTableData('api_code as a',$joins,'','a.*,b.name as contentname,c.name as categoryname');
		
		$data['title'] = 'API Code Management';
		$data['meta_keywords'] = 'API Code Management';
		$data['meta_description'] = 'API Code Management';
		$data['main_content'] = 'api/api';
		$data['view'] = 'view_all_code';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Edit content page
	function edit_code($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('content');
		}
		$joins = array('api_content as b'=>'a.content_id = b.id','api_category as c'=>'b.category_id = c.id');
		$isValid = $this->common_model->getJoinedTableData('api_code as a',$joins,array('a.id' => $id),'a.*,b.name as contentname,c.name as categoryname,c.id as category_id');
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('content');
		}
		// Form validation
		$this->form_validation->set_rules('content_id', 'Sub Category', 'required|xss_clean');
		$this->form_validation->set_rules('node_js', 'Node Js Code', 'required|xss_clean');
		$this->form_validation->set_rules('phython', 'Phython Code', 'required|xss_clean');
		$this->form_validation->set_rules('ruby', 'Ruby Code', 'required|xss_clean');
		$this->form_validation->set_rules('php', 'Php Code', 'required|xss_clean');	
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['content_id'] = $this->input->post('content_id');
				$updateData['node_js'] = $this->input->post('node_js');
				$updateData['phython'] = $this->input->post('phython');	
				$updateData['ruby'] = $this->input->post('ruby');
				$updateData['php'] = $this->input->post('php');
				$condition = array('id' => $id);
				$update = $this->common_model->updateTableData('api_code', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'API code has been updated successfully!');
					admin_redirect('api/coding', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this api code');
					admin_redirect('api/edit_code/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this api code');
				admin_redirect('api/edit_code/' . $id, 'refresh');
			}
			
		}
		$data['category'] = $isValid->row();
		$data['action'] = admin_url() . 'api/edit_code/' . $id;
		$data['title'] = 'Edit API Code';
		$data['meta_keywords'] = 'Edit API Code';
		$data['meta_description'] = 'Edit API Code';
		$data['main_content'] = 'api/api';
		$data['view'] = 'edit_code';
		$data['categories']=$this->common_model->getTableData('api_category');
		$data['subcategory']=$this->common_model->getTableData('api_content', array('category_id' => $data['category']->category_id));
		$this->load->view('administrator/admin_template', $data);
	}
	// Add content
	function add_code() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Form validation
		$this->form_validation->set_rules('content_id', 'Sub Category', 'required|xss_clean');
		$this->form_validation->set_rules('node_js', 'Node Js Code', 'required|xss_clean');
		$this->form_validation->set_rules('phython', 'Phython Code', 'required|xss_clean');
		$this->form_validation->set_rules('ruby', 'Ruby Code', 'required|xss_clean');
		$this->form_validation->set_rules('php', 'Php Code', 'required|xss_clean');
		if ($this->input->post()) {	
// echo "<pre>";
// print_r($this->input->post());die;		
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['content_id'] = $this->input->post('content_id');
				$updateData['node_js'] = $this->input->post('node_js');
				$updateData['phython'] = $this->input->post('phython');	
				$updateData['ruby'] = $this->input->post('ruby');
				$updateData['php'] = $this->input->post('php');
				$update = $this->common_model->insertTableData('api_code', $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'API code has been added successfully!');
					admin_redirect('api/coding', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to add this API code');
					admin_redirect('api/add_code', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to add this API code');
				admin_redirect('api/add_code', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'api/add_code';
		$data['title'] = 'Add API Code';
		$data['meta_keywords'] = 'Add API Code';
		$data['meta_description'] = 'Add API Code';
		$data['main_content'] = 'api/api';
		$data['view'] = 'add_code';
		$data['categories']=$this->common_model->getTableData('api_category');
		$data['subcategory']=$this->common_model->getTableData('api_content', array('category_id' => $data['categories']->result()[0]->id));
		$this->load->view('administrator/admin_template', $data);
	}
	// Delete page
	function delete_code($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('api/coding');
		}
		$isValid = $this->common_model->getTableData('api_code', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('api_code', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Api code deleted successfully');
				admin_redirect('api/coding');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Api code deletion');
				admin_redirect('api/coding');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('api/coding');
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
		$old_pairs=$this->common_model->getTableData('trade_pairs', $cond,'to_symbol_id')->result_array();
		if($old_pairs)
		{
			$old_pairs = array_column($old_pairs, 'to_symbol_id');
			array_push($old_pairs,$from_symbol);
		}
		else
		{
			$old_pairs=array($from_symbol);
		}
		//print_r($old_pairs);die;
		$pairs=$this->common_model->getTableData('currency',array('status' => 1),'id,currency_name','','','','','','','',array('id',$old_pairs));
		$txt='<option value="">Select</option>';
		foreach($pairs->result() as $cur){
		$txt.='<option value="'.$cur->id.'">'.$cur->currency_name.'</option>';
		}
		echo $txt;
	}
 }