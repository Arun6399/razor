<?php
/**
 * Withdraw class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Token_request extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		//$withdraw = $this->common_model->getTableData('transactions', array('type'=>'Withdraw','currency_type'=>'fiat','user_status'=>'Completed'))->result_array();
		$hiswhere = array('user_id!='=>'');
		$token = $this->common_model->getTableData('token_request',$hiswhere,'','','','','','',array('id', 'DESC'))->result_array();
		if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
			$search_string = $_GET['search_string'];	
			$like = array("currency_name"=>$search_string);
			$like_or = array("total_amount"=>$search_string,"status"=>$search_string);
			$hiswhere = array('user_id!='=>'');
			$data['token'] = $this->common_model->getTableData('token_request',$hiswhere,'',$like,'',$like_or,'','',array('id', 'DESC'));
		}
		else{
   		$perpage = max_records();
  		$urisegment = $this->uri->segment(4);  
   		$base="token_request/index";
   		$total_rows = count($token);
   		pageconfig($total_rows,$base,$perpage);

		$hiswhere = array('user_id!='=>'');
		$data['token'] = $this->common_model->getTableData('token_request',$hiswhere,'','','','','','',array('id', 'DESC'));
		}
		$data['prefix'] = get_prefix();
		$data['title'] = 'Token request Management';
		$data['meta_keywords'] = 'Token request Management';
		$data['meta_description'] = 'Token request Management';
		$data['main_content'] = 'token_request/token_request';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

	function view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$hiswhere = array('user_id!='=>'','id'=>$id);
		$data['token'] = $this->common_model->getTableData('token_request',$hiswhere,'','','','','','',array('id', 'DESC'))->row();

		if($data['token']->mode=="bank_wire")
		{
		$data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$data['token']->user_id))->row();
	    }
		// echo '<pre>'; print_r($data['user_bankdetails']); die;
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'token_request/view/' . $id;
		$data['title'] = 'Token request Management';
		$data['meta_keywords'] = 'Token request Management';
		$data['meta_description'] = 'Token request Management';
		$data['main_content'] = 'token_request/token_request';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}


 }
 
 /**
 * End of the file PAIR.php
 * Location: ./application/controllers/ulawulo/pair.php
 */	
