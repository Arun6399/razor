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

 class Spot_history extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

   
 	function buy() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}

	$data['view'] = 'buy';
	$data['title'] = 'Buy Trade History';
	$data['meta_keywords'] = 'Buy Trade History';
	$data['meta_description'] = 'Buy Trade History';
	$data['main_content'] = 'spot_history/spot_history';
    $data['buyspots'] =  $this->common_model->getTableData('spotfiat',array('type'=>'buy'))->result();
    $this->load->view('administrator/admin_template', $data); 
	
    }

	function sell() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}

	$data['view'] = 'sell';
	$data['title'] = 'Sell Trade History';
	$data['meta_keywords'] = 'Sell Trade History';
	$data['meta_description'] = 'Sell Trade History';
	$data['main_content'] = 'spot_history/spot_history';
    $data['sellspots'] =  $this->common_model->getTableData('spotfiat',array('type'=>'sell'))->result();
	$this->load->view('administrator/admin_template', $data); 

	}

    function view($id='') {
        // Is logged in
    $sessionvar=$this->session->userdata('loggeduser');
    if (!$sessionvar) {
        admin_redirect('admin', 'refresh');
    }

    $data['view'] = 'view';
    $data['title'] = 'Sell Trade History';
    $data['meta_keywords'] = 'Sell Trade History';
    $data['meta_description'] = 'Sell Trade History';
    $data['main_content'] = 'spot_history/spot_history';
    $data['order'] =  $this->common_model->getTableData('spotfiat',array('id'=>$id))->row();

    if($data['order']->bank_type=='old')
    {
        $data['user_bankdetails'] =  $this->common_model->getTableData('user_bank_details',array('user_id'=>$data['order']->user_id,'currency'=>'7'))->row();
    }
   


    $this->load->view('administrator/admin_template', $data); 

    }


     




 }

