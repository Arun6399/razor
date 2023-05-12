<?php
/**
 * Banner class
 * @package Spiegel Technologies
 * @subpackage zonexh
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Banner extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
 	function banner_ajax()
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
            1=>'title',
            //2=>'expiry_date',
            3=>'status',
            4=>'status'
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
            $like = " WHERE position LIKE '%".$search."%'";

			$query = "SELECT * FROM `bidex_banners`".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
			$countquery = $this->db->query("SELECT * FROM `bidex_banners`".$like." ORDER BY id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM `bidex_banners` ORDER BY `id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT * FROM `bidex_banners` ORDER BY `id` DESC');
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
                    $extra = array("data-placement"=>"top",'data-toggle'=>"popover","data-content"=>"De-Activate this Banner","class"=>"poper");
                    $changeStatus = anchor(admin_url().'banner/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                } else {
                    $status = '<label class="label label-danger">De-Activated</label>';
                    $extra = array("data-placement"=>"top",'data-toggle'=>"popover","data-content"=>"Activate this Banner","class"=>"poper");
                    $changeStatus = anchor(admin_url().'banner/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                }
                $edit = '&nbsp;&nbsp;&nbsp;<a href="' . admin_url() . 'banner/edit/' . $result->id . '" data-content="Edit this Banner to update the details" class="poper" data-placement="bottom" data-toggle="popover"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';

                $link="'".admin_url().'banner/delete/'.$result->id."'";
                
                $delete = '<a onclick="deleteaction('.$link.');" class="poper" data-toggle="popover" data-content="If you Delete this Banner? You cant revert it back."><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';	

                $expiry = date("d-m-Y",strtotime($result->expiry_date));	
				
					$data[] = array(
					    $i, 
						$result->title,
						//$expiry,
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
		$data['banners'] = $this->common_model->getTableData('banners', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Banners Management';
		$data['meta_keywords'] = 'Banners Management';
		$data['meta_description'] = 'Banners Management';
		$data['main_content'] = 'banners/banners';
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
		$this->form_validation->set_rules('banner_name', 'Banner Name', 'required|xss_clean');
	

		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				$insertData=array();
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
						$this->session->set_flashdata('error','Problem with your banner image');
						admin_redirect('banner/add', 'refresh');
					}
				} 
				else 
				{ 
					$image=""; 
				}

				
					$insertData['status'] 			= $this->input->post('status');
					$insertData['title']		= $this->input->post('banner_name');
					$insertData['image'] 			= $image;
					
					// Prepare to insert Data
					$insert 						= $this->common_model->insertTableData('banners', $insertData);				        
					if($insert)
					{
						$this->session->set_flashdata('success', 'Banner has been added successfully!');
						admin_redirect('banner', 'refresh');
					}
					else {
					$this->session->set_flashdata('error', 'Unable to add the Banner !');
					admin_redirect('banner/add', 'refresh');
				}
				
				
			}
			else
			{
				$this->session->set_flashdata('error', 'Unable to add the Banner !');
				admin_redirect('banner/add', 'refresh');
			}
		}		
		$data = array();
		$data['action'] = admin_url() . 'banner/add';
		$data['title'] = 'Add Banner';
		$data['meta_keywords'] = 'Add Banner';
		$data['meta_description'] = 'Add Banner';
		$data['main_content'] = 'banners/banners';
		$data['view'] = 'add';
		//$data['partners']=$this->common_model->getTableData('banners', array('status' => 1));
		$this->load->view('administrator/admin_template', $data);
	}
	// Edit page
	function edit($id) 
	{
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('banner');
		}
		$isValid = $this->common_model->getTableData('banners', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('banner');
		}
		// Form validation
		$this->form_validation->set_rules('banner_name', 'Banner Name', 'required|xss_clean');
		
		if ($this->input->post()) 
		{				
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
						$this->session->set_flashdata('error','Problem with your banner image');
						admin_redirect('currency/edit/' . $id, 'refresh');
					}
				} else {
					$image = $this->input->post('oldimage');
				}
			

				$updateData=array();
				
				$details 				= $this->common_model->getTableData('banners',array('id'=>$id),'','','','');
				if($details->num_rows()==0||$details->row('id')==$id)
				{
					
				
					$updateData['status'] = $this->input->post('status');
					$updateData['title'] 			= $this->input->post('banner_name');
				
					$updateData['image'] = $image;
					
					$condition = array('id' => $id);
					// updated via Common model
					//echo $this->db->last_query();die;
					$update = $this->common_model->updateTableData('banners', $condition, $updateData);

					if ($update) {
						$this->session->set_flashdata('success', 'Banner has been updated successfully!');
						admin_redirect('banner', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this Banner');
						admin_redirect('banner/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this Banner');
					admin_redirect('banner/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Banner');
				admin_redirect('banner/edit/' . $id, 'refresh');
			}
			
		}
		
		$data['banners'] = $isValid->row();
		$data['action'] = admin_url() . 'banner/edit/' . $id;
		$data['title'] = 'Edit Banner';
		$data['meta_keywords'] = 'Edit Banner';
		$data['meta_description'] = 'Edit Banner';
		$data['main_content'] = 'banners/banners';
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
			admin_redirect('banner');
		}
		$isValid = $this->common_model->getTableData('banners', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('banners', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Banner activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Banner de-activated successfully');
				}
				admin_redirect('banner');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occur with banner status updation');
				admin_redirect('banner');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this banner');
			admin_redirect('banner');
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
			admin_redirect('banner');
		}
		$isValid = $this->common_model->getTableData('banners', array('id' => $id))->num_rows();
		if ($isValid > 0) 
		{ 
			$rowS = $this->common_model->getTableData('banners', array('id' => $id))->row();
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('banners', $condition);
			if ($delete) 
			{ 				
							
				$this->session->set_flashdata('success', 'Banner deleted successfully');
				admin_redirect('banner');
			} 
			else 
			{ //False
				$this->session->set_flashdata('error', 'Problem occur with Banner deletion');
				admin_redirect('banner');	
			}
		} 
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('banner');
		}	
	}
 }