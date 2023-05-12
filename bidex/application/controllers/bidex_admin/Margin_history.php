<?php
/**
 * Trade History class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */

 class Margin_history extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function margin() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	// Page config
	$trade_his_array = $this->common_model->getTableData('swap_order', array('swap_type'=>'receive'))->result_array();
	if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
		$search_string = $_GET['search_string'];
		$like = array("d.currency_symbol"=>$search_string);
		$like_or = array("c.coinchairs_username"=>$search_string, "a.swap_amount"=>$search_string, "a.rate"=>$search_string, "a.swap_status"=>$search_string);		
		$hisjoins = array('users as c'=>'a.user_id = c.id', 'currency as d'=>'a.currency = d.id','coin_order as e'=>'a.margin_order = e.trade_id');
		$hiswhere = array('swap_type'=>'receive');
		$data['trade_history'] = $this->common_model->getJoinedTableDatas('swap_order as a',$hisjoins,$hiswhere,'a.*, c.coinchairs_username as username, d.currency_symbol,e.Price',$like,'',$like_or,'','',array('a.swap_id', 'DESC'));
	}
	else {
   	$perpage =max_records();
  	$urisegment=$this->uri->segment(4);  
   	$base="margin_history/margin";
   	$total_rows = count($trade_his_array);
   	pageconfig($total_rows,$base,$perpage);
   	$hisjoins = array('users as c'=>'a.user_id = c.id', 'currency as d'=>'a.currency = d.id','coin_order as e'=>'a.margin_order = e.trade_id');
	$hiswhere = array('swap_type'=>'receive');
	$data['trade_history'] = $this->common_model->getJoinedTableData('swap_order as a',$hisjoins,$hiswhere,'a.*, c.coinchairs_username as username, d.currency_symbol,e.Price','','','',$urisegment,$perpage,array('a.swap_id', 'DESC'));
	}
	// echo '<pre>'; print_r($data['trade_history']); die;
	$data['view'] = 'margin';
	$data['title'] = 'Margin History';
	$data['meta_keywords'] = 'Margin History';
	$data['meta_description'] = 'Margin History';
	$data['main_content'] = 'margin_history/margin_history';
	$this->load->view('administrator/admin_template', $data); 
	}

	function lending() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	// Page config
	$trade_his_array = $this->common_model->getTableData('swap_order', array('swap_type'=>'offer'))->result_array();
	if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
		$search_string = $_GET['search_string'];
		$like = array("d.currency_symbol"=>$search_string);
		$like_or = array("c.coinchairs_username"=>$search_string, "a.swap_amount"=>$search_string, "a.rate"=>$search_string, "a.swap_status"=>$search_string);		
		$hisjoins = array('users as c'=>'a.user_id = c.id', 'currency as d'=>'a.currency = d.id');
		$hiswhere = array('swap_type'=>'offer');
		$data['trade_history'] = $this->common_model->getJoinedTableDatas('swap_order as a',$hisjoins,$hiswhere,'a.*, c.coinchairs_username as username, d.currency_symbol',$like,'',$like_or,'','',array('a.swap_id', 'DESC'));
	}
	else {
   	$perpage =max_records();
  	$urisegment=$this->uri->segment(4);  
   	$base="margin_history/margin";
   	$total_rows = count($trade_his_array);
   	pageconfig($total_rows,$base,$perpage);
   	$hisjoins = array('users as c'=>'a.user_id = c.id', 'currency as d'=>'a.currency = d.id');
	$hiswhere = array('swap_type'=>'offer');
	$data['trade_history'] = $this->common_model->getJoinedTableData('swap_order as a',$hisjoins,$hiswhere,'a.*, c.coinchairs_username as username, d.currency_symbol','','','',$urisegment,$perpage,array('a.swap_id', 'DESC'));
	}
	// echo '<pre>'; print_r($data['trade_history']); die;
	$data['view'] = 'lending';
	$data['title'] = 'Lending History';
	$data['meta_keywords'] = 'Lending History';
	$data['meta_description'] = 'Lending History';
	$data['main_content'] = 'margin_history/margin_history';
	$this->load->view('administrator/admin_template', $data); 
	}

 }

