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
 class Static_content extends CI_Controller 
 {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
 	}
 	function staticcontent_ajax()
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
            //1=>'english_name',
            2=>'english_title',           
            3=>'english_page'
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
            $like = " WHERE english_title LIKE '%".$search."%' OR english_page LIKE '%".$search."%'";
			$query = "SELECT * FROM `bidex_static_content`".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
			$countquery = $this->db->query("SELECT * FROM `bidex_static_content`".$like." ORDER BY id DESC");
            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM `bidex_static_content` ORDER BY `id` DESC LIMIT '.$start.','.$length;
        	$countquery = $this->db->query('SELECT * FROM `bidex_static_content` ORDER BY `id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				$edit ='<a href="' . admin_url() . 'static_content/edit/' . $result->id . '" data-placement="bottom" data-toggle="popover" data-content="Edit this content" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';	
				
					$data[] = array(
					    $i, 
						//substr(strip_tags($result->english_name),0,30),
						$result->english_title,
						$edit
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
		$data['static_content'] = $this->common_model->getTableData('static_content','','','');
		$data['title'] = 'Static Content Management';
		$data['meta_keywords'] = 'Static Content Management';
		$data['meta_description'] = 'Static Content Management';
		$data['main_content'] = 'static_content/static_content';
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
		$this->form_validation->set_rules('english_title', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('englishpage', 'Page', 'required|xss_clean');
		$this->form_validation->set_rules('englishcontents', 'Contents', 'required|xss_clean');
		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				$lang_id= $this->input->post('lang'); 
				$image = $_FILES['image']['name'];
				if(!empty($image))
				{							
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/cms');
					if($uploadimage)
					{
						//$image=$uploadimage['secure_url'];
						if(is_array($uploadimage)) {
							$image=$uploadimage['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							admin_redirect('static_content/create', 'refresh');
						}
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your image');
						admin_redirect('static_content/create', 'refresh');
					}
				} 
				else {
					$image = 'https://res.cloudinary.com/dhpmwq4ln/image/upload/v1605768584/uploads/cms/r2dgjllcesmiwbbc0lry.png';
				}
				
				$updateData=array();
				if($lang_id==1)
				{
					/*$str = $this->input->post('english_title');
					$slug = strtolower(trim(preg_replace('/[\s-]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), '-'));
					$updateData['slug'] 			= $slug;*/
					$updateData['english_title'] 	= $this->input->post('english_title');
					$updateData['english_page'] 	= $this->input->post('englishpage');
					$updateData['english_content'] 	= $this->input->post('englishcontents');
					$updateData['image'] = $image;			
				}
				else if($lang_id==2)
				{
					$updateData['chinese_name'] = $this->input->post('chinesename');
					$updateData['chinese_page'] = $this->input->post('chinesepage');
					$updateData['chinese_content'] = $this->input->post('chinesecontents');	
				}
	            else if($lang_id==3)
	            {
	            	$updateData['russian_title'] = $this->input->post('english_title');
					$updateData['russian_page'] = $this->input->post('russianpage');
					$updateData['russian_content'] = $this->input->post('russiancontents');	
	            }
	            else
	            {
	            	$updateData['spanish_title'] = $this->input->post('english_title');
					$updateData['spanish_page'] = $this->input->post('spanishpage');
					$updateData['spanish_content'] = $this->input->post('spanishcontents');	
	            }	
				$condition = array('id' => $id);
				$update = $this->common_model->insertTableData('static_content', $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Static Content has been updated successfully!');
					admin_redirect('static_content', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this Static Content');
					admin_redirect('static_content/create/', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Static Content');
				admin_redirect('static_content/create/', 'refresh');
			}
		}
		$data['action'] = admin_url() . 'static_content/create';
		$data['title'] = 'Add New  Static Content';
		$data['meta_keywords'] = 'Add New Static Content';
		$data['meta_description'] = 'Add New Static Content';
		$data['main_content'] = 'static_content/add_static_content';
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
			admin_redirect('static_content');
		}
		$isValid = $this->common_model->getTableData('static_content', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('static_content');
		}
		// Form validation
		$this->form_validation->set_rules('english_title', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('englishpage', 'Page', 'required|xss_clean');
		$this->form_validation->set_rules('englishcontents', 'Contents', 'required|xss_clean');
		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$lang_id= $this->input->post('lang'); 
				$image = $_FILES['image']['name'];
				if($image!="") {							
				$uploadimage=cdn_file_upload($_FILES["image"],'uploads/cms',$isValid->row('image'));
				if($uploadimage)
				{
					//$image=$uploadimage['secure_url'];
					if(is_array($uploadimage)) {
							$image=$uploadimage['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							admin_redirect('static_content/edit/' . $id, 'refresh');
						}
				}
				else
				{
					$this->session->set_flashdata('error','Problem with your image');
					admin_redirect('static_content/edit/' . $id, 'refresh');
				}
				} 
				else {
					$image = $this->input->post('oldimage');
				}
				
				$updateData=array();
				if($lang_id==1)
				{
				
					$updateData['english_title'] = $this->input->post('english_title');
					$updateData['english_page'] = $this->input->post('englishpage');
					$updateData['english_content'] = $this->input->post('englishcontents');
					$updateData['image'] = $image;
				
				}
				else if($lang_id==2)
				{
					$updateData['chinese_name'] = $this->input->post('chinesename');
					$updateData['chinese_page'] = $this->input->post('chinesepage');
					$updateData['chinese_content'] = $this->input->post('chinesecontents');	
				}
                else if($lang_id==3)
                {
                	$updateData['russian_title'] = $this->input->post('english_title');
					$updateData['russian_page'] = $this->input->post('russianpage');
					$updateData['russian_content'] = $this->input->post('russiancontents');	
                }
                else
                {
                	$updateData['spanish_title'] = $this->input->post('english_title');
					$updateData['spanish_page'] = $this->input->post('spanishpage');
					$updateData['spanish_content'] = $this->input->post('spanishcontents');	
                }
					//vv
					//$updateData['language'] = $this->input->post('lang');		
					//vv		
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('static_content', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Static Content has been updated successfully!');
						admin_redirect('static_content', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this Static Content');
						admin_redirect('static_content/edit/' . $id, 'refresh');
					}
				
			}
			else {
				//echo validation_errors();die;
				$this->session->set_flashdata('error', 'Unable to update this Static Content');
				admin_redirect('static_content/edit/' . $id, 'refresh');
			}
			
		}
		//vv
		//$data['lang']=$this->common_model->getData('languages',array('status'=>1))->result();
		//vv
		$data['static_content'] = $isValid->row();
		$data['action'] = admin_url() . 'static_content/edit/' . $id;
		$data['title'] = 'Edit Static Content';
		$data['meta_keywords'] = 'Edit Static Content';
		$data['meta_description'] = 'Edit Static Content';
		$data['main_content'] = 'static_content/static_content';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
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