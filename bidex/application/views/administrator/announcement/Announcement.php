<?php
/**
 * Pair class
 * @package Spiegel Technologies
 * @subpackage zonexh
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Announcement extends CI_Controller 
 {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
 	}
 	function announcement_ajax()
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
            // 2=>'content',           
            // 3=>'image',
            3=>'link'
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
            $like = " WHERE title LIKE '%".$search."%' OR content LIKE '%".$search."%'";
			$query = "SELECT * FROM `zonexh_announcement`".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
			$countquery = $this->db->query("SELECT * FROM `zonexh_announcement`".$like." ORDER BY id DESC");
            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM `zonexh_announcement` ORDER BY `id` DESC LIMIT '.$start.','.$length;
        	$countquery = $this->db->query('SELECT * FROM `zonexh_announcement` ORDER BY `id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				$edit ='<a href="' . admin_url() . 'announcement/edit/' . $result->id . '" data-placement="bottom" data-toggle="popover" data-content="Edit this content" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';

				$link="'".admin_url().'announcement/delete/'.$result->id."'";

					$delete = '<a onclick="deleteaction('.$link.');" data-placement="top" data-toggle="popover" data-content="If you delete this? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';	
				
					$data[] = array(
					    $i, 
						//substr(strip_tags($result->english_name),0,30),
						$result->title,
						// $result->content,
						// $result->image,
						$result->link,
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
		$data['announcement'] = $this->common_model->getTableData('announcement','','','');
		$data['title'] = 'Announcement';
		$data['meta_keywords'] = 'Announcement';
		$data['meta_description'] = 'Announcement';
		$data['main_content'] = 'announcement/announcement';
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
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('content', 'Content', 'required|xss_clean');
		$this->form_validation->set_rules('link', 'Link', 'required|xss_clean');
		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				
				$image = $_FILES['image']['name'];
				if(!empty($image))
				{							
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/cms');
					if($uploadimage)
					{
						$image=$uploadimage['secure_url'];
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your image');
						admin_redirect('announcement/create', 'refresh');
					}
				} 
				else {
					$image = 'https://res.cloudinary.com/dhpmwq4ln/image/upload/v1605768584/uploads/cms/r2dgjllcesmiwbbc0lry.png';
				}
				
				$updateData=array();
				
					/*$str = $this->input->post('english_title');
					$slug = strtolower(trim(preg_replace('/[\s-]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), '-'));
					$updateData['slug'] 			= $slug;*/
					$updateData['title']= $this->input->post('title'); 
					$updateData['content'] 	= $this->input->post('help_title');
					$updateData['link'] 	= $this->input->post('link');
					$updateData['image'] = $image;
					//$updateData['link'] 	= $this->input->post('help_title');			
				
				// else if($lang_id==2)
				// {
				// 	$updateData['chinese_name'] = $this->input->post('chinesename');
				// 	$updateData['chinese_page'] = $this->input->post('chinesepage');
				// 	$updateData['chinese_content'] = $this->input->post('chinesecontents');	
				// }
	    //         else if($lang_id==3)
	    //         {
	    //         	$updateData['russian_title'] = $this->input->post('english_title');
					// $updateData['russian_page'] = $this->input->post('russianpage');
					// $updateData['russian_content'] = $this->input->post('russiancontents');	
	    //         }
	    //         else
	    //         {
	    //         	$updateData['spanish_title'] = $this->input->post('english_title');
					// $updateData['spanish_page'] = $this->input->post('spanishpage');
					// $updateData['spanish_content'] = $this->input->post('spanishcontents');	
	    //         }	
				$condition = array('id' => $id);
				$update = $this->common_model->insertTableData('announcement', $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Announcement has been updated successfully!');
					admin_redirect('announcement', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this Announcement');
					admin_redirect('announcement/create/', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Help Center');
				admin_redirect('announcement/create/', 'refresh');
			}
		}
		// $data['help_center'] = $this->common_model->getTableData('helpcategory','','','')->result();
		$data['action'] = admin_url() . 'announcement/create';
		$data['title'] = 'Add New Announcement';
		$data['meta_keywords'] = 'Add New Announcement';
		$data['meta_description'] = 'Add New Announcement';
		$data['main_content'] = 'announcement/add_announcement';
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
			admin_redirect('announcement');
		}
		$isValid = $this->common_model->getTableData('announcement', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('announcement');
		}
		// Form validation
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('content', 'Content', 'required|xss_clean');
		$this->form_validation->set_rules('link', 'Link', 'required|xss_clean');

		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				//$lang_id= $this->input->post('category'); 
				$image = $_FILES['image']['name'];
				if($image!="") {							
				$uploadimage=cdn_file_upload($_FILES["image"],'uploads/cms',$isValid->row('image'));
				if($uploadimage)
				{
					$image=$uploadimage['secure_url'];
				}
				else
				{
					$this->session->set_flashdata('error','Problem with your image');
					admin_redirect('announcement/edit/' . $id, 'refresh');
				}
				} 
				else {
					$image = $this->input->post('oldimage');
				}
				
				$updateData=array();
				
				    $updateData['title']= $this->input->post('title');
					$updateData['content'] = $this->input->post('content');
					$updateData['link'] = $this->input->post('link');
					$updateData['image'] = $image;
					//$updateData['link'] 	= $this->input->post('help_title');	
				
				
				// else if($lang_id==2)
				// {
				// 	$updateData['chinese_name'] = $this->input->post('chinesename');
				// 	$updateData['chinese_page'] = $this->input->post('chinesepage');
				// 	$updateData['chinese_content'] = $this->input->post('chinesecontents');	
				// }
     //            else if($lang_id==3)
     //            {
     //            	$updateData['russian_title'] = $this->input->post('english_title');
					// $updateData['russian_page'] = $this->input->post('russianpage');
					// $updateData['russian_content'] = $this->input->post('russiancontents');	
     //            }
     //            else
     //            {
     //            	$updateData['spanish_title'] = $this->input->post('english_title');
					// $updateData['spanish_page'] = $this->input->post('spanishpage');
					// $updateData['spanish_content'] = $this->input->post('spanishcontents');	
     //            }
					//vv
					//$updateData['language'] = $this->input->post('lang');		
					//vv		
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('announcement', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Announcement has been updated successfully!');
						admin_redirect('announcement', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this Announcement');
						admin_redirect('announcement/edit/' . $id, 'refresh');
					}
				
			}
			else {
				//echo validation_errors();die;
				$this->session->set_flashdata('error', 'Unable to update this Announcement');
				admin_redirect('announcement/edit/' . $id, 'refresh');
			}
			
		}
		//vv
		//$data['lang']=$this->common_model->getData('languages',array('status'=>1))->result();
		//vv
		// $data['helpcenter'] = $this->common_model->getTableData('helpcategory','','','')->result();
		$data['announcement'] = $isValid->row();
		$data['action'] = admin_url() . 'announcement/edit/' . $id;
		$data['title'] = 'Edit Announcement';
		$data['meta_keywords'] = 'Edit Announcement';
		$data['meta_description'] = 'Edit Announcement';
		$data['main_content'] = 'announcement/announcement';
		$data['view'] = 'edit';
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
		$isValid = $this->common_model->getTableData('announcement', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('announcement', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Message deleted successfully');
				admin_redirect('announcement');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with message deletion');
				admin_redirect('announcement');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this message');
			admin_redirect('announcement');
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