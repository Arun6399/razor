<?php
/**
 * Contact class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Contact extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
 	function contact_ajax()
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
        if($search=='open')
        {
        	$st = 0;
        }
        elseif($search=='Replied' || $search=='replied')
        {
        	$st = 1;
        }
        else
        {
        	$st = $search;
        }
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
            1=>'created_on',
            2=>'username',           
            3=>'email',
            4=>'subject',
            5=>'status'
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        $like = '';

        

        if(!empty($search))
        { 
            $like = " WHERE username LIKE '%".$search."%' OR email LIKE '%".$search."%' OR subject LIKE '%".$search."%' OR status LIKE '%".$st."%'";

$query = "SELECT * FROM `bidex_contact_us`".$like." ORDER BY id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT * FROM `bidex_contact_us`".$like." ORDER BY id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM `bidex_contact_us` ORDER BY `id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT * FROM `bidex_contact_us` ORDER BY `id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				if($result->status!='1') 
				{
					$edit = '<a href="' . admin_url() . 'contact/edit/' . $result->id . '" data-placement="top" data-toggle="popover" data-content="Reply to this mail." class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
				}
				else{
					$edit="";
				}
				$link="'".admin_url().'contact/delete/'.$result->id."'";
				$delete = '<a onclick="deleteaction('.$link.');" data-placement="right" data-toggle="popover" data-content="If you delete this mail? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';

				$status = ($result->status == 0)?'open':'Replied';		
				
					$data[] = array(
					    $i, 
						date("d-m-Y h:i a", strtotime($result->created_on)),
						$result->username,
						$result->email,
						$result->subject,
						$status,						
						$edit.$delete
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
	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$data['title'] = 'Contact us';
		$data['meta_keywords'] = 'Contact us';
		$data['meta_description'] = 'Contact us';
		$data['main_content'] = 'contact/contact';
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
			admin_redirect('contact');
		}
		$isValid = $this->common_model->getTableData('contact_us', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this mail');
			admin_redirect('contact');
		}
		// Form validation
		$this->form_validation->set_rules('content_description', 'Content Description', 'required|xss_clean');
		if ($this->input->post()) {			
			if ($this->form_validation->run())
			{
				$contact_details = $isValid->row();
				$frommessgae = $contact_details->subject;
				$name = $contact_details->username;
				$email = $contact_details->email;
				$message = $this->input->post('content_description');
				$image = $_FILES['image']['name'];

				if($image!="")
				{	

					$uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$isValid->row('user_id'));
					if($uploadimage1)
					{
						if(is_array($uploadimage1)) {
							$image=$uploadimage1['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage1 );
							$this->session->set_flashdata('error', $errorMsg);
							// admin_redirect('contact/edit/'.$id, 'refresh');
							admin_redirect('contact/edit/' . $id, 'refresh');
						}
					}
					else
					{
						$this->session->set_flashdata('error', 'Problem with support image');
						admin_redirect('contact/edit/'.$id, 'refresh');
					}
					$image=$image;	
				} 
				else 
				{ 
					$image = "";
				}
				//Email 
				$email_template = 'Support_reply';
				$site_common      =   site_common();
                $fb_link = $site_common['site_settings']->facebooklink;
                $tw_link = $site_common['site_settings']->twitterlink;
                $tg_link = $site_common['site_settings']->telegramlink;
                $md_link = $site_common['site_settings']->mediumlink;
                $ld_link = $site_common['site_settings']->linkedinlink;

					$special_vars = array(
					'###USERNAME###' => $name,
					// '###QUESTION###'   => $frommessgae,
					'###MESSAGE###'   => $message,
					'###FB###' => $fb_link,
					'###TW###' => $tw_link,
					'###TG###' => $tg_link,
					'###MD###' => $md_link,
					'###LD###' => $ld_link
					);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				// update as replied
				$updateData=array();
				$updateData['status'] = 1;
				$condition = array('id' => $id);

				$update = $this->common_model->updateTableData('bidex_contact_us', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Message Replied Successfully');
					admin_redirect('contact', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Error while message repling');
					admin_redirect('contact', 'refresh');
				}
				
			}
			else {
				$this->session->set_flashdata('error', 'Message not valid');
				admin_redirect('contact/edit/' . $id, 'refresh');
			}
		}
		$data['contact'] = $isValid->row();
		$data['action'] = admin_url() . 'contact/edit/' . $id;
		$data['title'] = 'Reply Mail';
		$data['meta_keywords'] = 'Reply Mail';
		$data['meta_description'] = 'Reply Mail';
		$data['main_content'] = 'contact/contact';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}

	// Delete page
	function delete($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('contact', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('contact');
		}
		$isValid = $this->common_model->getTableData('contact_us', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('contact_us', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Message deleted successfully');
				admin_redirect('contact');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with message deletion');
				admin_redirect('contact');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this message');
			admin_redirect('contact');
		}	
	}
 }