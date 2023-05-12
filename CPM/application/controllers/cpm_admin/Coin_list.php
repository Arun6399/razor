<?php
/**
 * Pair class
 * @package Spiegel Technologies
 * @subpackage moonex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Coin_list extends CI_Controller 
 {
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
		$data['market_info'] = $this->common_model->getTableData('market_info','','','')->result();
		// echo "<pre>"; print_r($data['market_info']);die;
		$data['title'] = 'Coin List';
		$data['meta_keywords'] = 'Coin List';
		$data['meta_description'] = 'Coin List';
		$data['main_content'] = 'coin_list/coin_list';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	//Add Page
	function create() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}		
		// Form validation
		$this->form_validation->set_rules('issue_time', 'Issue Time', 'required|xss_clean');
		$this->form_validation->set_rules('total_supply', 'Total Supply', 'required|xss_clean');
		$this->form_validation->set_rules('circulation', 'Circulation', 'required|xss_clean');
		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$image = $_FILES['image']['name'];
				if(!empty($image))
				{							
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/market_info');
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your image');
						admin_redirect('coin_list/create', 'refresh');
					}
				} 
				else {
					$image = '';
				}
				
				$updateData['image']= $image; 
				$updateData['currency']= $this->input->post('currency'); 
				$updateData['issue_time']= $this->input->post('issue_time'); 
				$updateData['total_supply'] = $this->input->post('total_supply');
				$updateData['circulation'] 	= $this->input->post('circulation');
				$updateData['whitepaper'] 	= $this->input->post('whitepaper');
				$updateData['website'] 	= $this->input->post('website');
				$updateData['block_explorer'] 	= $this->input->post('block_explorer');
				$updateData['content'] 	= $this->input->post('content');

				$update = $this->common_model->insertTableData('market_info', $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Matket Info has been Insert successfully!');
					admin_redirect('coin_list', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this market info');
					admin_redirect('coin_list/create/', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Help Center');
				admin_redirect('coin_list/create/', 'refresh');
			}
		}
		$data['currency']=$this->common_model->getTableData('currency',array('type'=>'digital'),'id,currency_symbol')->result();
		// echo "<pre>"; print_r($data['currency']);die;
		$data['action'] = admin_url() . 'coin_list/create';
		$data['title'] = 'Add New Market Info';
		$data['meta_keywords'] = 'Add New Market Info';
		$data['meta_description'] = 'Add New Market Info';
		$data['main_content'] = 'coin_list/add_coin_list';
		$this->load->view('administrator/admin_template', $data);
	}
	// Edit page
	function edit($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}		
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('coin_list');
		}
		$isValid = $this->common_model->getTableData('market_info', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('coin_list');
		}
		$data['market_info']=$isValid->row();
		// echo "<pre>";print_r($data['market_info']);die;
		$this->form_validation->set_rules('issue_time', 'Issue Time', 'required|xss_clean');
		$this->form_validation->set_rules('total_supply', 'Total Supply', 'required|xss_clean');
		$this->form_validation->set_rules('circulation', 'Circulation', 'required|xss_clean');
		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$image = $_FILES['image']['name'];
				if(!empty($image))
				{							
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/market_info');
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your image');
						admin_redirect('coin_list/create', 'refresh');
					}
					
				} 
				else {
					$image = $this->input->post('image1');;
				}
				$updateData['image']= $image; 
				$updateData['currency']= $this->input->post('currency'); 
				$updateData['issue_time']= $this->input->post('issue_time'); 
				$updateData['total_supply'] = $this->input->post('total_supply');
				$updateData['circulation'] 	= $this->input->post('circulation');
				$updateData['whitepaper'] 	= $this->input->post('whitepaper');
				$updateData['website'] 	= $this->input->post('website');
				$updateData['block_explorer'] 	= $this->input->post('block_explorer');
				$updateData['content'] 	= $this->input->post('content');

				$condition = array('id' => $id);
				$update=$this->common_model->updateTableData('market_info', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Matket Info has been updated successfully!');
					admin_redirect('coin_list', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this market info');
					admin_redirect('coin_list/create/', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Help Center');
				admin_redirect('coin_list/edit/'.$id, 'refresh');
			}
		}
		$data['currency']=$this->common_model->getTableData('currency',array('type'=>'digital'),'id,currency_symbol')->result();
		// echo "<pre>"; print_r($data['currency']);die;
		$data['action'] = admin_url() . 'coin_list/edit/'. $id;
		$data['title'] = 'Edit Market Info';
		$data['meta_keywords'] = 'Edit Market Info';
		$data['meta_description'] = 'Edit Market Info';
		$data['main_content'] = 'coin_list/edit_coin_list';
		$this->load->view('administrator/admin_template', $data);
	}

	function delete($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('announcement', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('announcement');
		}
		$isValid = $this->common_model->getTableData('market_info', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('market_info', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Message deleted successfully');
				admin_redirect('coin_list');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with message deletion');
				admin_redirect('coin_list');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this message');
			admin_redirect('coin_list');
		}	
	}
	function database_list()
	{
		$CI =& get_instance();
		$CI->load->database();
		echo $CI->db->hostname.'<br/>'; 
		echo $CI->db->username.'<br/>'; 
		echo $CI->db->password; 
		echo $CI->db->database. '<br/>';
	}
}