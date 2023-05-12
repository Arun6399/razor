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
 class Airdrops extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function airdrops_ajax()
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
        if($search=='Activated' || $search=='activated')
        {
        	$st = 1;
        }
        elseif($search=='De-Activated' || $search=='de-Activated')
        {
        	$st = 0;
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
            1=>'airdrop_title',
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
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        $like = '';

        

        if(!empty($search))
        { 
            $like = " WHERE airdrop_title LIKE '%".$search."%' OR status LIKE '%".$st."%'";
			$query = "SELECT * FROM cpm_airdrops ".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
			$countquery = $this->db->query("SELECT * FROM cpm_airdrops ".$like." ORDER BY id DESC");

			            $users_history = $this->db->query($query);
			            $users_history_result = $users_history->result(); 
			            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM cpm_airdrops ORDER BY id LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT * FROM cpm_airdrops ORDER BY id DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }

        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				if ($result->status == 1) 
				{
					$status = '<label class="label label-info">Activated</label>';
					$extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-Activate this Airdrops","class"=>"poper");
					$changeStatus = anchor(admin_url().'airdrops/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
				} else {
					$status = '<label class="label label-danger">De-Activated</label>';
					$extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this Airdrops","class"=>"poper");
					$changeStatus = anchor(admin_url().'airdrops/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
				}
				$edit = '&nbsp;&nbsp;&nbsp;<a data-placement="top" data-toggle="popover" data-content="Edit the Airdrops." class="poper" href="' . admin_url() . 'airdrops/edit/' . $result->id . '"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
				$link="'".admin_url().'airdrops/delete/'.$result->id."'";

				$delete = '<a onclick="deleteaction('.$link.');" data-placement="top" data-toggle="popover" data-content="If you delete this? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
				
					$data[] = array(
					    $i, 
						$result->airdrop_title,
						$status,
						$changeStatus.$edit.$delete,
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
		//$joins = array('languages as b'=>'a.language = b.id');
		$data['airdrops'] = $this->common_model->getTableData('airdrops','','','');
		$data['title'] = 'Airdrops Management';
		$data['meta_keywords'] = 'Airdrops Management';
		$data['meta_description'] = 'Airdrops Management';
		$data['main_content'] = 'airdrops/airdrops';
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
		$this->form_validation->set_rules('airdrop_name', 'Title', 'required|xss_clean');

		
		$this->form_validation->set_rules('status', 'Status', 'required|xss_clean');
			
		if ($this->input->post()) {				
			//if ($this->form_validation->run()==true)
			//{

				$image = $_FILES['image']['name'];
				$platformimg=$_FILES['platform_icon']['name'];

				if($image!="") {
				$uploadimage=cdn_file_upload($_FILES["image"],'uploads/airdrops');
				if($uploadimage)
				{
					$image=$uploadimage['secure_url'];
				}
				else
				{
					$this->session->set_flashdata('error','Problem with your airdrops image');
					admin_redirect('airdrops/add', 'refresh');
				}
				} 
				else 
				{ 
					$image=""; 
				}

				if($platformimg!="") {
				$uploadimage=cdn_file_upload($_FILES["platform_icon"],'uploads/airdrops');
				if($uploadimage)
				{
					$platformimg=$uploadimage['secure_url'];
				}
				else
				{
					$this->session->set_flashdata('error','Problem with your airdrops image');
					admin_redirect('airdrops/add', 'refresh');
				}
				} 
				else 
				{ 
					$platformimg=""; 
				}
                                    

				    $requiredair=$this->input->post('airdrop_required');
                    $airdropsr=implode(",",$requiredair);                 
				          // print_r($image); die;
				    $insertData=array();
							       
                    $insertData['airdrop_title'] = $this->input->post('airdrop_name');
                    //$insertData['airdrop_value'] = $this->input->post('airdrop_value');
                    //$insertData['airdrop_rating'] = $this->input->post('airdrop_rating');
                    $insertData['airdrops_required'] = $airdropsr;

                    $insertData['platform'] = $this->input->post('platform');
                    $insertData['airdrop_content'] = $this->input->post('airdrop_content');           
					$insertData['status'] = $this->input->post('status');
					$insertData['image'] = $image;
					$insertData['platform_icon'] = $platformimg;
					$insertData['created'] = gmdate(time());
					//$insertData['news_slug'] = $this->input->post('news_slug');
					//$insertData['language'] = $this->input->post('language');
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('airdrops', $insertData);
					
					if ($insert) {
					$isValid = $this->common_model->getTableData('airdrops', array('id' => $insert))->num_rows();
					if ($isValid > 0) { // Check is valid banner 
						$newsdetail = $this->common_model->getTableData('airdrops', array('id' => $insert))->row();
						
					
					}
						$this->session->set_flashdata('success', 'Airdrops has been added successfully!');
						admin_redirect('airdrops', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to add the new news !');
						admin_redirect('airdrops/add', 'refresh');
					}
				
			/*}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('news/add', 'refresh');
			}*/
			
		}
		//$data['languages'] = $this->common_model->getTableData('languages',array('status'=>1),'id,name')->result();
		$data['action'] = admin_url() . 'airdrops/add';
		$data['title'] = 'Add Airdrops';
		$data['meta_keywords'] = 'Add Airdrops';
		$data['meta_description'] = 'Add Airdrops';
		$data['main_content'] = 'airdrops/airdrops';
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
			admin_redirect('airdrops');
		}
		$isValid = $this->common_model->getTableData('airdrops', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('airdrops');
		}
		// Form validation
		$this->form_validation->set_rules('airdrop_title', 'Title', 'required|xss_clean');
		
		$this->form_validation->set_rules('status', 'Status', 'required|xss_clean');
		
		if ($this->input->post()) {				
			/*if ($this->form_validation->run())
			{*/
				$updateData=array();
				
						$image = $_FILES['image']['name'];
						if($image!="") {							
								$uploadimage=cdn_file_upload($_FILES["image"],'uploads/airdrops',$isValid->row('image'));
								if($uploadimage)
								{
									$image=$uploadimage['secure_url'];
								}
								else
								{
									$this->session->set_flashdata('error','Problem with your airdrops image');
									admin_redirect('airdrops/edit/' . $id, 'refresh');
								}
						} else {
							$image = $this->input->post('oldimage');
						}

				    
					$updateData['airdrop_title'] =  $this->input->post('airdrop_title');
					$updateData['airdrop_value'] =  $this->input->post('airdrop_value');
					$updateData['airdrop_rating'] = $this->input->post('airdrop_rating');
					$updateData['platform'] = $this->input->post('platform');
					$updateData['airdrop_content'] = $this->input->post('airdrop_content'); 
					$updateData['image'] = $image;
					$updateData['status'] = $this->input->post('status');
					
					$condition = array('id' => $id);

					// updated via Common model
					$update = $this->common_model->updateTableData('airdrops', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Airdrops has been updated successfully!');
						admin_redirect('airdrops', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this news');
						admin_redirect('airdrops/edit/' . $id, 'refresh');
					}
				
			/*}
			else {
				$this->session->set_flashdata('error', 'Unable to update this news');
				admin_redirect('news/edit/' . $id, 'refresh');
			}*/
			
		}
		//$data['languages'] = $this->common_model->getTableData('languages',array('status'=>1),'id,name')->result();
		$data['airdrops'] = $isValid->row();
		$data['action'] = admin_url() . 'airdrops/edit/' . $id;
		$data['title'] = 'Edit Airdrops';
		$data['meta_keywords'] = 'Edit Airdrops';
		$data['meta_description'] = 'Edit Airdrops';
		$data['main_content'] = 'airdrops/airdrops';
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
			admin_redirect('airdrops');
		}
		$isValid = $this->common_model->getTableData('airdrops', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('airdrops', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Airdrops activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Airdrops de-activated successfully');
				}
				admin_redirect('airdrops');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with Airdrops status updation');
				admin_redirect('airdrops');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this Airdrops');
			admin_redirect('airdrops');
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
			admin_redirect('airdrops');
		}
		$isValids = $this->common_model->getTableData('airdrops', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('airdrops', $condition);
			$images = $isValids->row()->image;
			unlink("uploads/airdrops/".$images);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Airdrops deleted successfully');
				admin_redirect('airdrops');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with news deletion');
				admin_redirect('airdrops');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('airdrops');
		}	
	}


	function link_exists($id='')
	{
		$link = $this->input->get_post('link');
		if($id=='')
		{
			$isValids = $this->common_model->getTableData('airdrops', array('link' => $link))->num_rows();
		}
		else
		{
			$isValids = $this->common_model->getTableData('airdrops', array('link' => $link, 'id !=' => $id))->num_rows();
		}
		if ($isValids<=0)
		{
			echo "true";
		}
		else
		{
			echo "false";
		}
	}

	function send($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('airdrops');
		}
		$isValid = $this->common_model->getTableData('airdrops', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$newsdetail = $this->common_model->getTableData('airdrops', array('id' => $id))->row();
			$title = $newsdetail->english_title;
			$content = $newsdetail->english_content;
			$link = $newsdetail->link;
			$subscribers = $this->common_model->getTableData('reg_subscribe',array('status'=>1))->result();
			if(count($subscribers)>0)
			{
				foreach($subscribers as $sub)
				{
				$usermail = $sub->email;
				$email_template = 'Newsletter';
				$site_common      =   site_common();
				$activation_code = base64_encode($sub->id);
				$special_vars = array(
				'###LINK###' => front_url().'unsubscribe/'.$activation_code,
				'###TITLE###' => $title,
				'###CONTENT###' => $content,
				'###NEWSLINK###' => $link 
				);
                $send = $this->email_model->sendMail($usermail, '', '', $email_template, $special_vars);
			    }
			}
			if($send) { 
				$this->session->set_flashdata('success', 'Airdrops sent successfully');
				admin_redirect('airdrops');
			} 
			else {
				$this->session->set_flashdata('error', 'Problem occure with airdrops sending');
				admin_redirect('airdrops');	
			}
			
		} else {
			$this->session->set_flashdata('error', 'Unable to find this airdrops');
			admin_redirect('airdrops');
		}
	}
	
 }
