<?php
/**
 * Tfa class
 * @package Spiegel Technologies
 * @subpackage Bluerico
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Tfa extends CI_Controller {
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
		
		$users = $this->common_model->getTableData('users',array('verified'=>1))->result_array();
		if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
			$search_string = $_GET['search_string'];
			$like = array("bidex_username"=>$search_string);
			$like_or = array("randcode"=>$search_string);
			$data['users'] = $this->common_model->getTableData('users',array('verified'=>1),'', $like, '', $like_or,'','',array('created_on', 'DESC'));
		}
		else{
	   	$perpage =10;
	  	$urisegment=$this->uri->segment(4);  
	   	$base="tfa/index";
	   	$total_rows = count($users);
	   	pageconfig($total_rows,$base,$perpage);
	   	$data['users'] = $this->common_model->getTableData('users',array('verified'=>1),'', '', '', '',$urisegment,$perpage,array('created_on', 'DESC'));
	   	}
	   	$data['prefix'] = get_prefix();
		$data['title'] = 'Users TFA Management';
		$data['meta_keywords'] = 'Users TFA Management';
		$data['meta_description'] = 'Users TFA Management';
		$data['main_content'] = 'tfa/tfa';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	// Disable TFA
	function status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users');
		}
		$isValid = $this->common_model->getTableData('users', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['randcode'] = 'disable';
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { // True // Update success
					$this->session->set_flashdata('success', 'User TFA is successfully disabled to the selected user');
					$this->session->set_flashdata('useractivated', $id);
					admin_redirect('tfa');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with user tfa status updation');
				admin_redirect('tfa');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('tfa');
		}
	}
 }