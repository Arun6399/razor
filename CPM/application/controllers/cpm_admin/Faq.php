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
 class Faq extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
 	}
 	//list
	function index()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$data['faq'] = $this->common_model->getTableData('faq as faqtbl','','','');
		$data['title'] = 'Faq Management';
		$data['meta_keywords'] = 'Faq Management';
		$data['meta_description'] = 'Faq Management';
		$data['main_content'] = 'faq/faq';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data);
	}
	//create
	function add()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		if ($this->input->post())
		{
			$lang_id= $this->input->post('lang');
			$insertData=array();
			if($lang_id==1)
			{
				$insertData['english_question'] = $this->input->post('addenglish_question');
				$insertData['english_description'] = $this->input->post('addenglish_description');
			}
			else if($lang_id==2)
			{
				$insertData['chinese_question'] = $this->input->post('addchinese_question');
				$insertData['chinese_description'] = $this->input->post('addchinese_description');
			}
			else if($lang_id==3)
			{
				$insertData['russian_question'] = $this->input->post('addrussian_question');
				$insertData['russian_description'] = $this->input->post('addrussian_description');
			}
			else
			{
				$insertData['spanish_question'] = $this->input->post('addspanish_question');
				$insertData['spanish_description'] = $this->input->post('addspanish_description');
			}
			$insertData['status'] = $this->input->post('add_status');
			$insert = $this->common_model->insertTableData('faq', $insertData);
			if ($insert) {
				$this->session->set_flashdata('success', 'Faq has been added successfully!');
				admin_redirect('faq', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Unable to add the new Faq !');
				admin_redirect('faq/add', 'refresh');
			}
		}
		$data['action'] = admin_url().'faq/add';
		$data['title'] = 'Add Faq';
		$data['meta_keywords'] = 'Add Faq';
		$data['meta_description'] = 'Add Faq';
		$data['main_content'] = 'faq/faq';
		$data['view'] = 'add';
		$this->load->view('administrator/admin_template', $data);
	}
	// Edit page
	function edit($id)
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar)
		{
			admin_redirect('admin', 'refresh');
		}
		if ($id == '')
		{
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('faq');
		}
		$isValid = $this->common_model->getTableData('faq', array('id' => $id));
		if ($isValid->num_rows() == 0)
		{
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('faq');
		}
		if ($this->input->post())
		{
			$condition = array('id' => $id);
			$lang_id= $this->input->post('lang');
			$updateData=array();
			if($lang_id==1)
			{
				$updateData['english_question'] = $this->input->post('english_question');
				$updateData['english_description'] = $this->input->post('english_description');
			}
			else if($lang_id==2)
			{
				$updateData['chinese_question'] = $this->input->post('chinese_question');
				$updateData['chinese_description'] = $this->input->post('chinese_description');
			}
			else if($lang_id==3)
			{
				$updateData['russian_question'] = $this->input->post('russian_question');
				$updateData['russian_description'] = $this->input->post('russian_description');
			}
			else
			{
				$updateData['spanish_question'] = $this->input->post('spanish_question');
				$updateData['spanish_description'] = $this->input->post('spanish_description');
			}
			$updateData['status'] = $this->input->post('status');
			$update = $this->common_model->updateTableData('faq',$condition,$updateData);
			if ($update) {
				$this->session->set_flashdata('success', 'Faq has been updated successfully!');
				admin_redirect('faq', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Unable to update this faq');
				admin_redirect('faq/edit/' . $id, 'refresh');
			}
		}
		$data['faq'] = $isValid->row();
		$data['action'] = admin_url().'faq/edit/'.$id;
		$data['title'] = 'Edit faq';
		$data['meta_keywords'] = 'Edit faq';
		$data['meta_description'] = 'Edit faq';
		$data['main_content'] = 'faq/faq';
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
			admin_redirect('faq');
		}
		$isValid = $this->common_model->getTableData('faq', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('faq', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Faq activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Faq de-activated successfully');
				}
				admin_redirect('faq');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with faq status updation');
				admin_redirect('faq');
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this faq');
			admin_redirect('faq');
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
			admin_redirect('faq');
		}
		$isValid = $this->common_model->getTableData('faq', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('faq', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Faq deleted successfully');
				admin_redirect('faq');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with faq deletion');
				admin_redirect('faq');
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('faq');
		}
	}
	// UPDTAED BY VAISHNUDEVI
	function faq_ajax()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$draw = $this->input->get('draw');
		$start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $order = $this->input->get("order");
        $search= $this->input->get("search");
        $search = $search['value'];
        $encrypt_search = encryptIt($search);
        $col = 0;
        $dir = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }
        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }        
        $valid_columns = array(
            0=>'id',
            1=>'english_question',
            2=>'status',           
            3=>'verified'
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if(!empty(trim($order)))
        {
            $this->db->order_by($order, $dir);
        }

		if(!empty($search))
		{
			$this->db->like('english_question',$search);		                 
		}
		$this->db->limit($length,$start);		
		$faqs = $this->db->get("cpm_faq");

        $faq_history_result = $faqs->result();

		$num_rows = count($faq_history_result);

		if(count($faq_history_result)>0)
		{
			foreach($faqs->result() as $faq)
			{
				$i++;
	            if ($faq->status == 1) {
                    $status = '<label class="label label-info">Activated</label>';
                    $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-activate this FAQ","class"=>"poper");
                    $changeStatus = anchor(admin_url().'faq/status/' . $faq->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                } else {
                    $status = '<label class="label label-danger">De-Activated</label>';
                    $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this FAQ","class"=>"poper");
                    $changeStatus = anchor(admin_url().'faq/status/' . $faq->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                }	
	            $edit = '&nbsp;&nbsp;&nbsp;<a href="' . admin_url() . 'faq/edit/' . $faq->id . '" data-placement="top" data-toggle="popover" data-content="Edit this FAQ" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
	            $link="'".admin_url().'faq/delete/'.$faq->id."'";	            
	            $delete = '<a onclick="deleteaction('.$link.');" data-placement="right" data-toggle="popover" data-content="If you delete this FAQ? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';          
				$data[] = array(
				    $i,
					$faq->english_question,						
					$status,
					$changeStatus.$edit.$delete
				);
			}
		}
		else
		{
			$data = array();
		}
		$output = array(
            "draw" => $draw,
            "recordsTotal" => $num_rows,
            "recordsFiltered" => $num_rows,
            "data" => $data,
            "query"=> $tt
        );
		echo json_encode($output);
	}
}