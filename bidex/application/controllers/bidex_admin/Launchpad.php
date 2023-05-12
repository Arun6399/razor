<?php
/**
 * Coin request class
 * @package Spiegel Technologies
 * @subpackage ixtokens
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
*/
class Launchpad extends CI_Controller 
{
 	public function __construct() 
 	{
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
	// list
	function index() 
	{
		// Is logged in
		$sessionvar 				= $this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$data['prefix'] 			= get_prefix();
		$coin_request 				= $this->common_model->getTableData('launchpad')->result_array();
		$perpage 					= 10;
  		$urisegment 				= $this->uri->segment(4);  
   		$base 						= "launchpad/index";
   		$total_rows 				= count($coin_request);
   		pageconfig($total_rows,$base,$perpage);

   		$hisjoins 					= array('users as b'=>'a.user_id = b.id');
		$data['coin_request'] 		= $this->common_model->getJoinedTableData('launchpad as a',$hisjoins,'','a.*, b.bidex_username as username','','','',$urisegment,$perpage,array('a.coin_id', 'DESC'));
		$data['title'] 				= 'Coin Requests';
		$data['meta_keywords'] 		= 'Coin Requests';
		$data['meta_description'] 	= 'Coin Requests';
		$data['main_content'] 		= 'launchpad/launchpad';
		$data['view'] 				= 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	// View page
	function view($id) 
	{
		// Is logged in
		$sessionvar 	= $this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') 
		{
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('coin_request');
		}
		$hisjoins 	= array('users as b'=>'a.user_id = b.id');
		$hiswhere 	= array('a.coin_id'=>$id);
		$isValid 	= $this->common_model->getJoinedTableData('launchpad as a',$hisjoins,$hiswhere,'a.*, b.bidex_username as username');
		if ($isValid->num_rows() == 0) 
		{
			$this->session->set_flashdata('error', 'Unable to find this coin request');
			admin_redirect('coin_request');
		}
		$data['prefix'] 		  = get_prefix();
		$data['coin_request'] 	  = $isValid->row();
		$data['action'] 		  = admin_url() . 'launchpad/update_coin_request_status/' . $id;
		$data['title'] 			  = 'View Coin requests';
		$data['meta_keywords'] 	  = 'View Coin requests';
		$data['meta_description'] = 'View Coin requests';
		$data['main_content'] 	  = 'launchpad/launchpad';
		$data['view'] 			  = 'view';
		$this->load->view('administrator/admin_template', $data);
	}	

	// UPDTAED BY VAISHNUDEVI

	function update_coin_request_status($id)
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('cms');
		}
        $array = array('status' => 0, 'msg' => '');
		$this->form_validation->set_rules('status', 'status', 'required|xss_clean');
		if($this->input->post('status') == 2)
		{
			$this->form_validation->set_rules('reject_reason', 'reject_reason', 'required|xss_clean');
		}
		if($this->input->post()) 
		{
			if ($this->form_validation->run())
			{
				$isValids = $this->common_model->getTableData('launchpad', array('coin_id' => $id));
				$num_rows = $isValids->num_rows();
				if ($num_rows > 0) 
				{
					$isValids = $isValids->row();					
					$updateData=array();
					$updateData['status'] 				= $this->input->post('status');
					$updateData['rejection_reason']     = '';
					if($this->input->post('status') == "2")
					{						
						$updateData['rejection_reason'] = $this->input->post('reject_reason');						
					}
					$condition 							= array('coin_id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('launchpad', $condition, $updateData);
					$this->session->set_flashdata('success', 'Changed this Launchpad Status');
						admin_redirect('launchpad/view/' . $id, 'refresh');
					// if ($update) 
					// {
					
					// 	$email 		= $isValids->email;
					// 	$username 	= $isValids->username;
					// 	$coin_name 	= $isValids->coin_name;
					// 	// Email
					// 	$email_template = 'Coin_accept';
					// 	$msg = 'User Coin Request Accepted Sucessfully';
					// 	if($this->input->post('status') == "2")
					// 	{
					// 		$email_template = 'Coin_cancel';
					// 		$msg = 'User Coin Request Rejected Sucessfully';
					// 	}
					// 	$site_common      =   site_common();                
					// 	$special_vars = array(
					// 			'###USERNAME###' => $username,
					// 			'###COIN###' 	 => $coin_name,		
					// 		);
					// 	$this->email_model->sendMail($email, '', '', $email_template,$special_vars);

					// 	$this->session->set_flashdata('success', $msg);
					// 	admin_redirect('launchpad', 'refresh');
					// } else {
					// 	$this->session->set_flashdata('error', 'Unable to Change this Launchpad Status');
					// 	admin_redirect('launchpad/view/' . $id, 'refresh');
					// }
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to this Launchpad');
					admin_redirect('launchpad', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to Change this Launchpad Status');
				admin_redirect('launchpad/view/' . $id, 'refresh');
			}
		}
		else {
				$this->session->set_flashdata('error', 'Unable to Change this Launchpad Status');
				admin_redirect('launchpad/view/' . $id, 'refresh');
			}
	}	
	//server side datatable
	function coin_request_ajax()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$draw 			= $this->input->get('draw');
		$start 			= intval($this->input->get("start"));
        $length 		= intval($this->input->get("length"));
        $order 			= $this->input->get("order");
        $search 		= $this->input->get("search");
        $search 		= $search['value'];
        $encrypt_search = encryptIt($search);
        $col 			= 0;
        $dir 			= "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col 	= $o['column'];
                $dir 	= $o['dir'];
            }
        }
        if($dir != "asc" && $dir != "desc")
        {
            $dir 		= "desc";
        }        
        $valid_columns 	= array(
            0=>'id',
            1=>'username',
            2=>'coin_name',           
            3=>'coin_symbol'
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order 		= $valid_columns[$col];
        }
        if(!empty(trim($order)))
        {
            $this->db->order_by("coin_id", $dir);
        }
		if(!empty($search))
		{
			$this->db->like('username',$search);
			$this->db->or_like('coin_name',$search);
			$this->db->or_like('coin_symbol',$search);
		}		
		$this->db->limit($length,$start);		
		$coin_requests = $this->db->get("bidex_launchpad");
		$num_rows 			= count($coin_requests->result());
		if($num_rows > 0)
		{
			foreach($coin_requests->result() as $coin_request)
			{
				$i++;	           
                $view = '<a href="' . admin_url() . 'launchpad/view/' . $coin_request->coin_id . '" title="View this Request"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';	          
				$data[] = array(
				    $i,
					$coin_request->username,						
					$coin_request->coin_name,
					$coin_request->coin_symbol,
					$changeStatus.$view
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
}