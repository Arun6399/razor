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
 class Email_template extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
 	}
 	// UPDTAED BY VAISHNUDEVI
	function email_template_ajax()
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
            1=>'name',
            2=>'status',           
            3=>'status'
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
			$this->db->like('name',$search);		                 
		}
		$this->db->limit($length,$start);		
		$emailTemplateList = $this->db->get("bidex_email_template");

		$emailTemplateLists = $this->db->query("SELECT * FROM bidex_email_template");

        $email_template_result = $emailTemplateList->result();

		$num_rows = $emailTemplateLists->num_rows();

		if(count($email_template_result)>0)
		{
			foreach($emailTemplateList->result() as $email_template)
			{
				$i++;
	            if ($email_template->status == 1)
	            {
	                $status = '<label class="label label-info">Activated</label>';
	                $extra = array('title' => 'De-activate this FAQ');
	                // $changeStatus = anchor(admin_url().'email_template/status/' . $email_template->id . '/0','Deactive',$extra);
	            } else {
	                $status = '<label class="label label-danger">De-Activated</label>';
	                $extra = array('title' => 'Activate this FAQ');
	                // $changeStatus = anchor(admin_url().'email_template/status/' . $email_template->id . '/1','Activate',$extra);
	            }
	            $edit = '<a href="' . admin_url() . 'email_template/edit/' . $email_template->id . '" data-placement="bottom" data-toggle="popover" data-content="Edit this Template" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
	            $del_btn = '<a href="' . admin_url() . 'email_template/view/' . $email_template->id . '" data-placement="top" data-toggle="popover" data-content="View this Template" class="poper"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
				$data[] = array(
				    $i,
					$email_template->name,						
					$status,
				    $edit.$del_btn
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
            "data" => $data
        );
		echo json_encode($output);
	}
	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		//	$joins = array('languages as b'=>'a.language = b.id');
		$data['email_template'] = $this->common_model->getTableData('email_template','','','');
		$data['title'] = 'Email Template Management';
		$data['meta_keywords'] = 'Email Template Management';
		$data['meta_description'] = 'Email Template Management';
		$data['main_content'] = 'email_template/email_template';
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
			admin_redirect('email_template');
		}
		$isValid = $this->common_model->getTableData('email_template', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('email_template');
		}
		// Form validation
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('template', 'Template', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('type', 'Type', 'required');
		if ($this->input->post()) {
			if ($this->form_validation->run())
			{
						// print_r($this->input->post('template')); die;
				$updateData=array();
					$updateData['name'] = $this->input->post('name');
					$updateData['subject'] = $this->input->post('subject');
					$updateData['template'] = $this->input->post('template',false);
					$updateData['created'] = gmdate(time());
					$updateData['status'] = $this->input->post('status');
					$updateData['type'] = $this->input->post('type');
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('email_template', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Email Template has been updated successfully!');
						admin_redirect('email_template', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this email template');
						admin_redirect('email_template/edit/' . $id, 'refresh');
					}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this email_template');
				admin_redirect('email_template/edit/' . $id, 'refresh');
			}
		}
		$data['email_template'] = $isValid->row();
		//$data['languages'] = $this->common_model->getTableData('languages',array('status'=>1),'id,name')->result();
		$data['action'] = admin_url() . 'email_template/edit/' . $id;
		$data['title'] = 'Edit Email Template';
		$data['meta_keywords'] = 'Edit Email Template';
		$data['meta_description'] = 'Edit Email Template';
		$data['main_content'] = 'email_template/email_template';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}
	function view($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('email_template');
		}
		$isValid = $this->common_model->getTableData('email_template', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('email_template');
		}
		$data['email_template'] = $isValid->row();
		$data['emailConfig'] = $this->db->where('id', 1)->get('site_settings')->row();
		// print_r($data['email_template']); die;
		$data['action'] = admin_url() . 'email_template/view/' . $id;
		$data['title'] = 'View Email Template';
		$data['meta_keywords'] = 'View Email Template';
		$data['meta_description'] = 'View Email Template';
		$data['main_content'] = 'email_template/email_template';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data);
	}
 }