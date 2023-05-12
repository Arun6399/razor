<?php
/**
 * Trade_pairs class
 * @package Getcrypex
 * @subpackage Bluerico
 * @author Pilaventhiran
 * @version 1.0
 * @link http://getcrypex.com/
 * 
 */
 class Trade extends CI_Controller {
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
		$data['trade'] = $this->common_model->getTableData('trade',array('type'=>'buy'));
		$data['title'] = 'Trade Management';
		
		$data['meta_keywords'] = 'Trade Management';
		$data['meta_description'] = 'Trade Management';
		$data['main_content'] = 'trade/trade';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Add
	function add() {

		error_reporting(0);
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}


		$this->form_validation->set_rules('english_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('english_position', 'Position', 'required|xss_clean');


		if ($this->input->post()) {	


			if ($this->form_validation->run())
			{
				$image = $_FILES['image']['name'];
				if($image!="") {
				$uploadimage=cdn_file_upload($_FILES["image"],'uploads/testimonial');
				if($uploadimage)
				{
					$image=$uploadimage['secure_url'];
				}
				else
				{
					$this->session->set_flashdata('error','Problem with your trade image');
					admin_redirect('trade/add', 'refresh');
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
				/*else if($lang_id==2)
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
				}*/
				$insertData['image'] = $image;
				$insertData['status'] = $this->input->post('status');
				$insertData['added_date'] = gmdate(time());					
				// Prepare to insert Data
				$insert = $this->common_model->insertTableData('trade', $insertData);
			//exit;
				if ($insert) {
					$this->session->set_flashdata('success', 'Trade has been added successfully!');
					admin_redirect('trade', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to add the trade !');
					admin_redirect('trade/add', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('trade/add', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'trade/add';
		
		$data['title'] = 'Add Trade';
		$data['meta_keywords'] = 'Add Trade';
		$data['meta_description'] = 'Add Trade';
		$data['main_content'] = 'trade/trade';
		$data['view'] = 'add';
		$this->load->view('administrator/admin_template', $data);
	}
	// Edit page
	function edit($id) {

		error_reporting(0);
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('trade');
		}
		$isValid = $this->common_model->getTableData('trade', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('trade');
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
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/trade',$isValid->row('image'));
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your trade image');
						admin_redirect('trade/edit/' . $id, 'refresh');
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
				$update = $this->common_model->updateTableData('trade', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Trade has been updated successfully!');
					admin_redirect('trade', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this Trade');
					admin_redirect('trade/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Trade');
				admin_redirect('trade/edit/' . $id, 'refresh');
			}
			
		}
		$data['trade'] = $isValid->row();
		$data['action'] = admin_url() . 'trade/edit/' . $id;
		$data['title'] = 'Edit Testimonials';
		$data['meta_keywords'] = 'Edit Testimonials';
		$data['meta_description'] = 'Edit Tstimonials';
		$data['main_content'] = 'trade/trade';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}

	function view($id) {

          error_reporting(0);

		    //$isValid = $this->common_model->getTableData('wallet',array('user_id' => $id))->row();

		    $data['trade'] = $this->common_model->getTableData('trade',array('id' => $id))->row();


		    //$data['userdetails'] = $this->common_model->getTableData('users', array('id' => $isValid->user_id), get_prefix().'username')->row();		

		

			$data['title'] = 'Trade Management';

			$data['meta_keywords'] = 'Trade Management';

			$data['meta_description'] = 'Trade Management';

			$data['main_content'] = 'trade/trade';

			$data['view']='view_trade';

			$this->load->view('administrator/admin_template',$data); 

	}

     function trade_history($id) {

     	//error_reporting(0);

		    //$isValid = $this->common_model->getTableData('wallet',array('user_id' => $id))->row();

		   $data['historytrade'] = $this->common_model->getTableData('tradeorder',array('tradeid' => $id))->result();

			$data['title'] = 'Trade History Management';

			$data['meta_keywords'] = 'Trade History Management';

			$data['meta_description'] = 'Trade History Management';

			$data['main_content'] = 'trade/trade';

			$data['view']='trade_history';

			$this->load->view('administrator/admin_template',$data); 


	}

     function selltrade() {

		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$data['selltrade'] = $this->common_model->getTableData('trade',array('type'=>'sell'));
		$data['title'] = 'Trade Management';
		
		$data['meta_keywords'] = 'Trade Management';
		$data['meta_description'] = 'Trade Management';
		$data['main_content'] = 'trade/trade';
		$data['view'] = 'selltrade';
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
			admin_redirect('trade');
		}
		$isValid = $this->common_model->getTableData('trade', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('trade', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Trade activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Trade de-activated successfully');
				}
				admin_redirect('trade');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Trade status updation');
				admin_redirect('trade');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Trade');
			admin_redirect('trade');
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
			admin_redirect('trade');
		}
		$isValid = $this->common_model->getTableData('trade', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);

			$get=$this->common_model->getTableData('trade', array('id' => $id))->row();

			$crypto=$get->cryptocurrency;
			$user_id=$get->user_id;

			if($get->tradestatus!='completed'){
			//$price=$get->crptobalance;
	        $balance = getscrowBalance($get->user_id,$get->cryptocurrency,'crypto');
            $current='0';
            updatescrowBalance($user_id,$crypto,$current,'crypto');
            $getwalletbalance=getBalance($user_id,$crypto,'crypto');
            $updatewallet=$getwalletbalance+$balance;
            updateBalance($user_id,$crypto,$updatewallet,'crypto');
        }


           // $update_scrow=$updatebalance+$coin;
           // updatescrowBalance($user_id,$crypto, $update_scrow,'crypto');	
			$delete = $this->common_model->deleteTableData('trade', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Trade deleted successfully');
				admin_redirect('trade');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Trade deletion');
				admin_redirect('trade');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('trade');
		}	
	}
 }