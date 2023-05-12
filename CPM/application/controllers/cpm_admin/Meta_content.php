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
 class Meta_content extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function metacontent_ajax()
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
            1=>'english_title',
            2=>'english_title'
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
            $like = " WHERE english_title LIKE '%".$search."%'";
$query = "SELECT * FROM `cpm_meta_content`".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
$countquery = $this->db->query("SELECT * FROM `cpm_meta_content`".$like." ORDER BY id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT * FROM `cpm_meta_content` ORDER BY `id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT * FROM `cpm_meta_content` ORDER BY `id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				$edit = '<a href="' . admin_url() . 'meta_content/edit/' . $result->id . '" data-placement="bottom" data-toggle="popover" data-content="Edit this meta content" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';		
				
					$data[] = array(
					    $i, 
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
		
		$data['meta_content'] = $this->common_model->getTableData('meta_content');
		$data['title'] = 'Meta Content Management';
		$data['meta_keywords'] = 'Meta Content Management';
		$data['meta_description'] = 'Meta Content Management';
		$data['main_content'] = 'meta_content/meta_content';
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
			admin_redirect('meta_content');
		}
		$isValid = $this->common_model->getTableData('meta_content', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('meta_content');
		}
		// Form validation
		$this->form_validation->set_rules('english_title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('english_meta_keywords', 'Meta Keywords', 'required|xss_clean');
		$this->form_validation->set_rules('english_meta_description', 'Meta Description', 'required|xss_clean');
		$this->form_validation->set_rules('heading', 'Heading', 'required|xss_clean');
		$this->form_validation->set_rules('link', 'Link', 'required|xss_clean');
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$lang_id= $this->input->post('lang');
				if($lang_id==1)
				{
				
					$updateData['english_title'] = $this->input->post('english_title');
					$updateData['english_meta_keywords'] = $this->input->post('english_meta_keywords');
					$updateData['english_meta_description'] = $this->input->post('english_meta_description');
				}
				else if($lang_id==2)
				{
					$updateData['chinese_title'] = $this->input->post('chinese_title');
					$updateData['chinese_meta_keywords'] = $this->input->post('chinese_meta_keywords');
					$updateData['chinese_meta_description'] = $this->input->post('chinese_meta_description');
				}
				else if($lang_id==3)
				{
					$updateData['russian_title'] = $this->input->post('russian_title');
					$updateData['russian_meta_keywords'] = $this->input->post('russian_meta_keywords');
					$updateData['russian_meta_description'] = $this->input->post('russian_meta_description');
				}
				else
				{
					$updateData['spanish_title'] = $this->input->post('spanish_title');
					$updateData['spanish_meta_keywords'] = $this->input->post('spanish_meta_keywords');
					$updateData['spanish_meta_description'] = $this->input->post('spanish_meta_description');
				}
					$updateData['heading'] = $this->input->post('heading');
					$updateData['link'] = $this->input->post('link');			
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('meta_content', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Meta Content has been updated successfully!');
						admin_redirect('meta_content', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this meta content');
						admin_redirect('meta_content/edit/' . $id, 'refresh');
					}
				
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this meta content');
				admin_redirect('meta_content/edit/' . $id, 'refresh');
			}
			
		}
		$data['meta_content'] = $isValid->row();
		$data['action'] = admin_url() . 'meta_content/edit/' . $id;
		$data['title'] = 'Edit Meta Content';
		$data['meta_keywords'] = 'Edit Meta Content';
		$data['meta_description'] = 'Edit Meta Content';
		$data['main_content'] = 'meta_content/meta_content';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}
	
 }