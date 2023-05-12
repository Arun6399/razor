<?php
/**
 * Cms class
 * @package Spiegel Technologies
 * @subpackage moonex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 *
 */
 class Cms extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
 	}
 	// UPDTAED BY VAISHNUDEVI
	function cms_ajax()
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
            1=>'english_heading',
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
			$this->db->like('english_heading',$search);		                 
		}
		$this->db->limit($length,$start);		
		$cmsList = $this->db->get("cpm_cms");

        $cms_list_result = $cmsList->result();

		$num_rows = count($cms_list_result);

		if(count($cms_list_result)>0)
		{
			foreach($cmsList->result() as $cms)
			{
				$i++;
	            if ($cms->status == 1) {
                    $status = '<label class="label label-info">Activated</label>';
                    $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-activate this CMS","class"=>"poper");
                    $changeStatus = anchor(admin_url().'cms/status/' . $cms->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                } else {
                    $status = '<label class="label label-danger">De-Activated</label>';
                    $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this CMS","class"=>"poper");
                    $changeStatus = anchor(admin_url().'cms/status/' . $cms->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                }
                $edit = '&nbsp;&nbsp;&nbsp;<a href="' . admin_url() . 'cms/edit/' . $cms->id . '" data-placement="top" data-toggle="popover" data-content="Edit this CMS" class="poper"><i class="fa fa-pencil text-primary"></i></a>';	          
				$data[] = array(
				    $i,
					$cms->english_heading,						
					$status,
					$changeStatus.$edit
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
		//$joins = array('languages as b'=>'a.language = b.id');
		$data['cms'] = $this->common_model->getTableData('cms','','','');
		$data['title'] = 'CMS Management';
		$data['meta_keywords'] = 'CMS Management';
		$data['meta_description'] = 'CMS Management';
		$data['main_content'] = 'cms/cms';
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
		//print_r($_POST); exit;
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('cms');
		}
		$isValid = $this->common_model->getTableData('cms', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('cms');
		}
		/*//vv
		$condition=array('id'=>$id);
		$data['result']=$this->common_model->getData('coinchairs_cms',$condition)->row();
		$data['category']=$this->common_model->getData('coinchairs_languages',array('status'=>1))->result();
        //vv
		$this->load->view('administrator/cms/cms',$data);
		//vv*/
		// Form validation
		$this->form_validation->set_rules('heading', 'Heading', 'required|xss_clean');
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'required|xss_clean');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required|xss_clean');
		$this->form_validation->set_rules('content_description', 'Content Description', 'required');
		if($this->input->post()) {
			if ($this->form_validation->run())
			{
				 //$lang_id= $this->input->post('lang');
				$updateData=array();
				/*if($lang_id==1)
				{*/
				$updateData['english_heading'] = $this->input->post('heading');
				$updateData['english_title'] = $this->input->post('title');
				$updateData['english_meta_keywords'] = $this->input->post('meta_keywords');
				$updateData['english_meta_description'] = $this->input->post('meta_description');
				$updateData['english_content_description'] = $_POST['content_description'];
			//}
			/*if($lang_id==2)
				{
				$updateData['chinese_heading'] = $this->input->post('chineseheading');
				$updateData['chinese_title'] = $this->input->post('chinesetitle');
				$updateData['chinese_meta_keywords'] = $this->input->post('chinesemeta_keywords');
				$updateData['chinese_meta_description'] = $this->input->post('chinesemeta_description');
				$updateData['chinese_content_description'] = $this->input->post('chinesecontent_description');
			}
			if($lang_id==3)
				{
				$updateData['russian_heading'] = $this->input->post('russianheading');
				$updateData['russian_title'] = $this->input->post('russiantitle');
				$updateData['russian_meta_keywords'] = $this->input->post('russianmeta_keywords');
				$updateData['russian_meta_description'] = $this->input->post('russianmeta_description');
				$updateData['russian_content_description'] = $this->input->post('russiancontent_description');
			}
			else
			{
				$updateData['spanish_heading'] = $this->input->post('spanishheading');
				$updateData['spanish_title'] = $this->input->post('spanishtitle');
				$updateData['spanish_meta_keywords'] = $this->input->post('spanishmeta_keywords');
				$updateData['spanish_meta_description'] = $this->input->post('spanishmeta_description');
				$updateData['spanish_content_description'] = $this->input->post('spanishcontent_description');
			}*/
				//vv
				//$updateData['language']=$this->input->post('dropcat');
				//vv
				$updateData['status'] = $this->input->post('status');
				$condition = array('id' => $id);
				// updated via Common model
				$update = $this->common_model->updateTableData('cms', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'CMS has been updated successfully!');
					admin_redirect('cms', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this cms');
					admin_redirect('cms/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this cms');
				admin_redirect('cms/edit/' . $id, 'refresh');
			}
		}
		//$data['languages']=$this->common_model->getData('languages',array('status'=>1))->result();
        //vv
		//$this->load->view('administrator/cms/cms',$data);
		//vv
		$data['cms'] = $isValid->row();
		$data['action'] = admin_url() . 'cms/edit/' . $id;
		$data['title'] = 'Edit CMS';
		$data['meta_keywords'] = 'Edit CMS';
		$data['meta_description'] = 'Edit CMS';
		$data['main_content'] = 'cms/cms';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}
	function update($id)
	{
        // Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		//print_r($_POST); exit;
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('cms');
		}
        $array = array('status' => 0, 'msg' => '');
		$this->form_validation->set_rules('heading', 'Heading', 'required|xss_clean');
		$this->form_validation->set_rules('title', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('meta_keywords', 'Meta Keywords', 'required|xss_clean');
		$this->form_validation->set_rules('meta_description', 'Meta Description', 'required|xss_clean');
		if($this->input->post()) {
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['english_heading'] = $this->input->post('heading');
				$updateData['english_title'] = $this->input->post('title');
				$updateData['english_meta_keywords'] = $this->input->post('meta_keywords');
				$updateData['english_meta_description'] = $this->input->post('meta_description');
				$updateData['english_content_description'] = $_POST['content_description'];
				$updateData['status'] = $this->input->post('status');
				$condition = array('id' => $id);
				// updated via Common model
				$update = $this->common_model->updateTableData('cms', $condition, $updateData);
				if ($update) {
					/*$this->session->set_flashdata('success', 'CMS has been updated successfully!');
					admin_redirect('cms', 'refresh');*/
					$array['status'] = 1;
					$array['msg'] = 'CMS has been updated successfully!';
				} else {
					/*$this->session->set_flashdata('error', 'Unable to update this cms');
					admin_redirect('cms/edit/' . $id, 'refresh');*/
					$array['status'] = 0;
					$array['msg'] = 'Unable to update this cms';
				}
			}
			else {
				/*$this->session->set_flashdata('error', 'Unable to update this cms');
				admin_redirect('cms/edit/' . $id, 'refresh');*/
				    $array['status'] = 0;
					$array['msg'] = 'Unable to update this cms';
			}
		}
		die(json_encode($array));
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
			admin_redirect('cms');
		}
		$isValid = $this->common_model->getTableData('cms', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('cms', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'CMS activated successfully');
				} else {
					$this->session->set_flashdata('success', 'CMS de-activated successfully');
				}
				admin_redirect('cms');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with cms status updation');
				admin_redirect('cms');
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this cms');
			admin_redirect('cms');
		}
	}
	function dash($request='GET',$port='9980', $postfields=null)
	{
			// $var = $this->encryption->encrypt('7555');
			// print_R($var.'<br/>');
			// $vars = $this->encryption->decrypt($var);
			// print_R($vars); die;
		// error_reporting(-1);
		// ini_set('display_errors', 1);
		// For ripple
	   // $var = $this->encryption->encrypt('rGuishqRtQ6NVpsqyxyQzMmFSDLyyA1fsU');
	   // // print_R($var); die;
	   // $vars = $this->encryption->decrypt('619bd4e25821460efe34f73917c5ab73a39f565c0517888028faa268fab30b7fd555e3b1df81ae54e714dabed074151a5e8c670748326611b7e9e13fef064988Y02R3ubBdkA+XFwEP2KclqQ7/HeE1R8A/pPbdXSVuMiHKB4+XdZIUHYGO1L3jen6T841PJMoQGFh++GBHVNWuQ==');
	   // echo '<pre>';
	   //  // print_r($var.'<br/>');
	   // print_r($vars); die;
			// For Ethereum
				// $this->address = '0xBe8BdA659E70e847dccD73a5910dC44d952Daea4';
		// print_R($this->node_dir); die;
	 //    $this->output 		= array();
		// $this->return_var 	= -1;
		// aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
		// $this->wallet_ip = '85.10.193.246';
		// $this->wallet_port = '8545';
		// $this->address = '0xF7a937E2fa91587087381E7DAc526016E3Be623F';
		// $this->wallet_dir 	= FCPATH.'wallets';
		// $this->node_dir 	= ($this->local_model->get_valid_server())?'/usr/bin/node':'/usr/local/bin/node';
	 //    $result = exec('cd '.$this->wallet_dir.'/eth_ethc; '.$this->node_dir.' get_accounts.js '.$this->wallet_ip.' '.$this->wallet_port.' '.$this->address, $this->output, $this->return_var);
	 //    print_R($this->output); die;
		//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
	    // print_r($this->output); die;
	    // echo getAddress('24','1');
	    // $var = $this->encryption->encrypt('9980');
	    // print_r($var);
	    // $vars = $this->encryption->decrypt($var);
	    // print_r($vars); die;
	    // $st = $this->local_model->validatate_address('gamecredits', 'GeogG2GYBxgpmnVPfpYiJHFqXERiAwLfih');
	    // print_r($st); die;
		// $c = curl_init();
		// curl_setopt($c, CURLOPT_URL, '85.10.200.73:'.$port.'/wallet');
		// // For debugging, set URL to http://httpbin.org/post and read output
		// //curl_setopt($c, CURLOPT_URL, 'http://httpbin.org/post');
		// curl_setopt($c, CURLOPT_CUSTOMREQUEST, $request);
		// curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($c, CURLOPT_USERAGENT, 'Sia-Agent');
		// if (!strcasecmp($request, 'POST')) {
		// 	curl_setopt($c, CURLOPT_POST, count($postfields));
		// 	curl_setopt($c, CURLOPT_POSTFIELDS, $postfields);
		// }
		// $data = curl_exeresultc($c);
		// print_R($info = curl_getinfo($c));
		// print_r($data); die;
		// curl_close($c);
		// $json = json_decode($data);
		// // Throw any non-JSON string as error
		// if (json_last_error() != JSON_ERROR_NONE) {
		// 	//throw new \Exception($data);
		// 	return (object) array('Success' => '0'); // standard response
		// }
		// return $json;
 	}
 }