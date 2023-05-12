<?php
/**
 * Partners class
 * @package Spiegel Technologies	
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologgies.com/
 * 
 */
 class Partners extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
 	function partners_ajax()
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
        if($search=='De-Activated' || $search=='de-activated')
        {
        	$st = 0;
        }
        elseif($search=='Activated' || $search=='activated')
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
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        $like = '';

        

        if(!empty($search))
        { 
            $like = " WHERE name LIKE '%".$search."%' OR status LIKE '%".$st."%'";

$query = "SELECT * FROM `bidex_partners`".$like." ORDER BY id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT * FROM `bidex_partners`".$like." ORDER BY id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM `bidex_partners` ORDER BY `id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT * FROM `bidex_partners` ORDER BY `id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				if ($result->status == 1) {
                    $status = '<label class="label label-info">Activated</label>';
                    $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-activate this partner","class"=>"poper");
                    $changeStatus = anchor(admin_url().'partners/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                } else {
                    $status = '<label class="label label-danger">De-Activated</label>';
                    $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this partner","class"=>"poper");
                    $changeStatus = anchor(admin_url().'partners/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                }

                $edit = '&nbsp;&nbsp;&nbsp;<a href="' . admin_url() . 'partners/edit/' . $result->id . '" data-placement="top" data-toggle="popover" data-content="Edit this Partner" class="poper" title=""><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                $link="'".admin_url().'partners/delete/'.$result->id."'";

                $delete = '<a onclick="deleteaction('.$link.');" data-placement="bottom" data-toggle="popover" data-content="If you delete this? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
				
					$data[] = array(
					    $i, 
						$result->name,
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
	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		// Get the list pages
		$data['partners'] = $this->common_model->getTableData('partners', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Partners Management';
		$data['meta_keywords'] = 'Partners Management';
		$data['meta_description'] = 'Partners Management';
		$data['main_content'] = 'partners/partners';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Add
	function add() 
	{		
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('name', 'Partner Name', 'required|xss_clean');
		$this->form_validation->set_rules('link', 'Partner Link', 'required|xss_clean');
		

		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				$image = $_FILES['image']['name'];

				if($image!="") 
				{
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/currency');

					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your currency image');
						admin_redirect('partners/add', 'refresh');
					}
				} 
				else 
				{ 
					$image=""; 
				}

				$insertData 						= array();
				$name 						= $this->input->post('name');
				$link 					= $this->input->post('link');
				$details 							= $this->db->query("SELECT * FROM `bidex_partners` WHERE `name` = '".$name."'");

				if($details->num_rows()==0)
				{
					
					$insertData['name']      = $name;
					$insertData['link']    = $link;
					$insertData['status'] 			= $this->input->post('status');
					$insertData['updated_on'] 			= gmdate(time());
					$insertData['image'] 			= $image;
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('partners', $insertData);
					     

				        
						if($insert){

						$this->session->set_flashdata('success', 'Partner has been added successfully!');

						admin_redirect('partners', 'refresh');
					}

					} 
					else 
					{
						$this->session->set_flashdata('error', 'Unable to add the Partner !');
						admin_redirect('partners/add', 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to add the Partner !');
					admin_redirect('partners/add', 'refresh');
				}
			}
			

		
		$data = array();
		$data['action'] = admin_url() . 'partners/add';
		$data['title'] = 'Add Partner';
		$data['meta_keywords'] = 'Add Partner';
		$data['meta_description'] = 'Add Partner';
		$data['main_content'] = 'partners/partners';
		$data['view'] = 'add';
		$data['partners']=$this->common_model->getTableData('partners', array('status' => 1));
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
			admin_redirect('partners');
		}
		$isValid = $this->common_model->getTableData('partners', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('partners');
		}
		// Form validation
		$this->form_validation->set_rules('name', 'Partner Name', 'required|xss_clean');
		$this->form_validation->set_rules('link', 'Partner Link', 'required|xss_clean');
		
		if ($this->input->post()) {				
			
			//echo "<pre>";print_r($_POST); exit;

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
						$this->session->set_flashdata('error','Problem with your Partner image');
						admin_redirect('partners/edit/' . $id, 'refresh');
					}
				} else {
					$image = $this->input->post('oldimage');
				}

				$updateData=array();
				$name 						= $this->input->post('name');
				$link 					= $this->input->post('link');
				$details=$this->common_model->getTableData('partners',array('name'=>$name),'','','','');
				if($details->num_rows()==0||$details->row('id')==$id)
				{
					
					$updateData['name'] = $name;
					$updateData['link'] = $name;
					$updateData['status'] = $this->input->post('status');
					$updateData['image'] = $image;
					$condition = array('id' => $id);
					// updated via Common model
					//echo $this->db->last_query();die;
					$update = $this->common_model->updateTableData('partners', $condition, $updateData);

					if ($update) {
						$this->session->set_flashdata('success', 'Partner has been updated successfully!');
						admin_redirect('partners', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this Partner');
						admin_redirect('partners/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this Partner');
					admin_redirect('partners/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Partner');
				admin_redirect('partners/edit/' . $id, 'refresh');
			}
			
		}
		
		$data['partners'] = $isValid->row();
		$data['action'] = admin_url() . 'partners/edit/' . $id;
		$data['title'] = 'Edit Partner';
		$data['meta_keywords'] = 'Edit Partner';
		$data['meta_description'] = 'Edit Partner';
		$data['main_content'] = 'partners/partners';
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
			admin_redirect('partners');
		}
		$isValid = $this->common_model->getTableData('partners', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('partners', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Partner activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Partner de-activated successfully');
				}
				admin_redirect('partners');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with partner status updation');
				admin_redirect('partners');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this partner');
			admin_redirect('partners');
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
			admin_redirect('partners');
		}
		$isValid = $this->common_model->getTableData('partners', array('id' => $id))->num_rows();
		if ($isValid > 0) 
		{ 
			$rowS = $this->common_model->getTableData('partners', array('id' => $id))->row();
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('partners', $condition);
			if ($delete) 
			{ 				
							
				$this->session->set_flashdata('success', 'Partner deleted successfully');
				admin_redirect('partners');
			} 
			else 
			{ //False
				$this->session->set_flashdata('error', 'Problem occure with Partner deletion');
				admin_redirect('partners');	
			}
		} 
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('partners');
		}	
	}
 }