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
 class News extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function news_ajax()
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
            1=>'english_title',
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
            $like = " WHERE english_title LIKE '%".$search."%' OR status LIKE '%".$st."%'";
			$query = "SELECT * FROM cpm_news ".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
			$countquery = $this->db->query("SELECT * FROM cpm_news ".$like." ORDER BY id DESC");

			            $users_history = $this->db->query($query);
			            $users_history_result = $users_history->result(); 
			            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM cpm_news ORDER BY id LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT * FROM cpm_news ORDER BY id DESC');
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
					$extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-Activate this News","class"=>"poper");
					$changeStatus = anchor(admin_url().'news/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
				} else {
					$status = '<label class="label label-danger">De-Activated</label>';
					$extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this News","class"=>"poper");
					$changeStatus = anchor(admin_url().'news/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
				}
				$edit = '&nbsp;&nbsp;&nbsp;<a data-placement="top" data-toggle="popover" data-content="Edit the News." class="poper" href="' . admin_url() . 'news/edit/' . $result->id . '"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
				$link="'".admin_url().'news/delete/'.$result->id."'";

				$delete = '<a onclick="deleteaction('.$link.');" data-placement="top" data-toggle="popover" data-content="If you delete this? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
				
					$data[] = array(
					    $i, 
						$result->english_title,
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
		$data['news'] = $this->common_model->getTableData('news','','','');
		$data['title'] = 'News Management';
		$data['meta_keywords'] = 'News Management';
		$data['meta_description'] = 'News Management';
		$data['main_content'] = 'news/news';
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
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'required|xss_clean');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required|xss_clean');
		$this->form_validation->set_rules('heading', 'Heading', 'required|xss_clean');
		//$this->form_validation->set_rules('contents', 'Content', 'required|xss_clean');
		//$this->form_validation->set_rules('link', 'Link', 'required|xss_clean');
		$this->form_validation->set_rules('status', 'Status', 'required|xss_clean');
		//$this->form_validation->set_rules('language', 'Language', 'required|xss_clean');
			
		if ($this->input->post()) {				
			//if ($this->form_validation->run()==true)
			//{

				$image = $_FILES['image']['name'];
				if($image!="") {
				$uploadimage=cdn_file_upload($_FILES["image"],'uploads/news');
				if($uploadimage)
				{
					$image=$uploadimage['secure_url'];
				}
				else
				{
					$this->session->set_flashdata('error','Problem with your news image');
					admin_redirect('news/add', 'refresh');
				}
				} 
				else 
				{ 
					$image=""; 
				}

				// print_r($image); die;
				$insertData=array();
				$lang = $this->input->post('lang');
			    if($lang==1)
			    {
			    	$language = 'english';
			    }
			    elseif($lang==2)
			    {
			    	$language = 'chinese';
			    }
			    elseif($lang==3)
			    {
			    	$language = 'russian';
			    }
			    else
			    {
			    	$language = 'spanish';
			    }
				$title=$this->input->post($language.'_title');
				$meta_keywords=$this->input->post($language.'_meta_keywords');
				$meta_description=$this->input->post($language.'_meta_description');
				 $content=$this->input->post($language.'_content');

					$insertData[$language.'_title'] = $title;
					$insertData[$language.'_meta_keywords'] = $meta_keywords;
					$insertData[$language.'_meta_description'] = $meta_description;
			        $insertData[$language.'_content'] = $content;
					$insertData['image'] = $image;
					$insertData[$language.'_heading'] = $this->input->post($language.'_heading');

					if($this->input->post('contents') !='')
					{
					$insertData['content'] = $this->input->post('contents');
				    }
				    if($this->input->post('link') !='')
				    {
					$insertData['link'] = str_replace(" ","_",$this->input->post('link'));
				    }
					$insertData['status'] = $this->input->post('status');
					$insertData['created'] = gmdate(time());
					$insertData['news_slug'] = $this->input->post('news_slug');
					//$insertData['language'] = $this->input->post('language');
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('news', $insertData);
					if ($insert) {
					$isValid = $this->common_model->getTableData('news', array('id' => $insert))->num_rows();
					if ($isValid > 0) { // Check is valid banner 
						$newsdetail = $this->common_model->getTableData('news', array('id' => $insert))->row();
						$title = $newsdetail->english_title;
						$content = $newsdetail->english_content;
						$link = $newsdetail->link;
						//$subscribers = $this->common_model->getTableData('reg_subscribe',array('status'=>1))->result();
						// if(count($subscribers)>0)
						// {
						// 	foreach($subscribers as $sub)
						// 	{
						// 	$usermail = $sub->email;
						// 	$email_template = 'Newsletter';
						// 	$site_common      =   site_common();
						// 	$activation_code = base64_encode($sub->id);
						// 	$special_vars = array(
						// 	'###LINK###' => front_url().'unsubscribe/'.$activation_code,
						// 	'###TITLE###' => $title,
						// 	'###CONTENT###' => $content,
						// 	'###NEWSLINK###' => $link
						// 	);			
						// 	$send = $this->email_model->sendMail($usermail, '', '', $email_template, $special_vars);
						//     }
						// }	
					}
						$this->session->set_flashdata('success', 'News has been added successfully!');
						admin_redirect('news', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to add the new news !');
						admin_redirect('news/add', 'refresh');
					}
				
			/*}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('news/add', 'refresh');
			}*/
			
		}
		//$data['languages'] = $this->common_model->getTableData('languages',array('status'=>1),'id,name')->result();
		$data['action'] = admin_url() . 'news/add';
		$data['title'] = 'Add News';
		$data['meta_keywords'] = 'Add News';
		$data['meta_description'] = 'Add News';
		$data['main_content'] = 'news/news';
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
			admin_redirect('faq');
		}
		$isValid = $this->common_model->getTableData('news', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('faq');
		}
		// Form validation
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'required|xss_clean');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required|xss_clean');
		$this->form_validation->set_rules('heading', 'Heading', 'required|xss_clean');
		//$this->form_validation->set_rules('contents', 'Content', 'required|xss_clean');
		//$this->form_validation->set_rules('link', 'Link', 'required|xss_clean');
		$this->form_validation->set_rules('status', 'Status', 'required|xss_clean');
		//$this->form_validation->set_rules('language', 'Language', 'required|xss_clean');
		if ($this->input->post()) {				
			/*if ($this->form_validation->run())
			{*/
				$updateData=array();
				
						$image = $_FILES['image']['name'];
						if($image!="") {							
								$uploadimage=cdn_file_upload($_FILES["image"],'uploads/news',$isValid->row('image'));
								if($uploadimage)
								{
									$image=$uploadimage['secure_url'];
								}
								else
								{
									$this->session->set_flashdata('error','Problem with your news image');
									admin_redirect('news/edit/' . $id, 'refresh');
								}
						} else {
							$image = $this->input->post('oldimage');
						}
				    $lang = $this->input->post('lang');
				    if($lang==1)
				    {
				    	$language = 'english';
				    }
				    elseif($lang==2)
				    {
				    	$language = 'chinese';
				    }
				    elseif($lang==3)
				    {
				    	$language = 'russian';
				    }
				    else
				    {
				    	$language = 'spanish';
				    }
				
					$updateData[$language.'_title'] = $this->input->post($language.'_title');
					$updateData[$language.'_meta_keywords'] = $this->input->post($language.'_meta_keywords');
					$updateData[$language.'_meta_description'] = $this->input->post($language.'_meta_description');
					$updateData[$language.'_content'] = $this->input->post($language.'_content');
					$updateData['status'] = $this->input->post('status');
					$updateData[$language.'_heading'] = $this->input->post($language.'_heading');
					if($this->input->post('contents') !='')
					{
					$updateData['content'] = $this->input->post('contents');
				    }
				    if($this->input->post('link') !='')
				    {
					$updateData['link'] = str_replace(" ","_",$this->input->post('link'));
				    }
					$updateData['image'] = $image;
					$updateData['news_slug'] = $this->input->post('news_slug');	
					//$updateData['language'] = $this->input->post('language');		
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('news', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'News has been updated successfully!');
						admin_redirect('news', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this news');
						admin_redirect('news/edit/' . $id, 'refresh');
					}
				
			/*}
			else {
				$this->session->set_flashdata('error', 'Unable to update this news');
				admin_redirect('news/edit/' . $id, 'refresh');
			}*/
			
		}
		//$data['languages'] = $this->common_model->getTableData('languages',array('status'=>1),'id,name')->result();
		$data['news'] = $isValid->row();
		$data['action'] = admin_url() . 'news/edit/' . $id;
		$data['title'] = 'Edit News';
		$data['meta_keywords'] = 'Edit News';
		$data['meta_description'] = 'Edit News';
		$data['main_content'] = 'news/news';
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
			admin_redirect('news');
		}
		$isValid = $this->common_model->getTableData('news', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('news', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'News activated successfully');
				} else {
					$this->session->set_flashdata('success', 'News de-activated successfully');
				}
				admin_redirect('news');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with news status updation');
				admin_redirect('news');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this news');
			admin_redirect('news');
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
			admin_redirect('news');
		}
		$isValids = $this->common_model->getTableData('news', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('news', $condition);
			$images = $isValids->row()->image;
			unlink("uploads/news/".$images);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'News deleted successfully');
				admin_redirect('news');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with news deletion');
				admin_redirect('news');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('news');
		}	
	}


	function link_exists($id='')
	{
		$link = $this->input->get_post('link');
		if($id=='')
		{
			$isValids = $this->common_model->getTableData('news', array('link' => $link))->num_rows();
		}
		else
		{
			$isValids = $this->common_model->getTableData('news', array('link' => $link, 'id !=' => $id))->num_rows();
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
			admin_redirect('news');
		}
		$isValid = $this->common_model->getTableData('news', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$newsdetail = $this->common_model->getTableData('news', array('id' => $id))->row();
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
				$this->session->set_flashdata('success', 'News sent successfully');
				admin_redirect('news');
			} 
			else {
				$this->session->set_flashdata('error', 'Problem occure with news sending');
				admin_redirect('news');	
			}
			
		} else {
			$this->session->set_flashdata('error', 'Unable to find this news');
			admin_redirect('news');
		}
	}
	
 }
