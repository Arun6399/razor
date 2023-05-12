<?php
/**
 * Coin request class
 * @package Spiegel Technologies
 * @subpackage zonexh
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
*/
class Coin_request extends CI_Controller 
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
		$coin_request 				= $this->common_model->getTableData('add_coin')->result_array();
		$perpage 					= 10;
  		$urisegment 				= $this->uri->segment(4);  
   		$base 						= "coin_request/index";
   		$total_rows 				= count($coin_request);
   		pageconfig($total_rows,$base,$perpage);

   		//$hisjoins 					= array('users as b'=>'a.user_id = b.id');
		$data['coin_request'] 		= $this->common_model->getTableData('add_coin','','','','','',$urisegment,$perpage,array('coin_id', 'DESC'));
		$data['title'] 				= 'Coin Requests';
		$data['meta_keywords'] 		= 'Coin Requests';
		$data['meta_description'] 	= 'Coin Requests';
		$data['main_content'] 		= 'coin_request/coin_request';
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
		//$hisjoins 	= array('users as b'=>'a.user_id = b.id');
		//$hiswhere 	= array('coin_id'=>$id);
		$isValid 	= $this->common_model->getTableData('add_coin',array('coin_id'=>$id));
		if ($isValid->num_rows() == 0) 
		{
			$this->session->set_flashdata('error', 'Unable to find this coin request');
			admin_redirect('coin_request');
		}
		$data['prefix'] 		  = get_prefix();
		$data['coin_request'] 	  = $isValid->row();
		$data['action'] 		  = admin_url() . 'coin_request/update_coin_request_status/' . $id;
		$data['title'] 			  = 'View Coin requests';
		$data['meta_keywords'] 	  = 'View Coin requests';
		$data['meta_description'] = 'View Coin requests';
		$data['main_content'] 	  = 'coin_request/coin_request';
		$data['view'] 			  = 'view';
		$this->load->view('administrator/admin_template', $data);
	}	

	function create() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}		
		// Form validation
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean');
		$this->form_validation->set_rules('emailaddress', 'Email Address', 'required|xss_clean');
		$this->form_validation->set_rules('contact', 'Contact', 'required|xss_clean');
		$this->form_validation->set_rules('project_name', 'Project Name', 'required|xss_clean');
		$this->form_validation->set_rules('coin_name', 'Coin Name', 'required|xss_clean');
		//$this->form_validation->set_rules('erc_token', 'ERC Token', 'required|xss_clean');
		//$this->form_validation->set_rules('smart_address', 'Smart Address', 'required|xss_clean');
		$this->form_validation->set_rules('webste_link', 'Webste Link', 'required|xss_clean');
		$this->form_validation->set_rules('prjct_whitepaper', 'Prjct Whitepaper', 'required|xss_clean');
		$this->form_validation->set_rules('coin_token', 'Coin Token', 'required|xss_clean');
		$this->form_validation->set_rules('market_price', 'Market Price', 'required|xss_clean');
		$this->form_validation->set_rules('supply_coin', 'Supply Coin', 'required|xss_clean');
		$this->form_validation->set_rules('coin_exchange', 'Coin Exchange', 'required|xss_clean');
		$this->form_validation->set_rules('bounty_user', 'Bounty User', 'required|xss_clean');
		$this->form_validation->set_rules('listing_btc', 'Listing Btc', 'required|xss_clean');
		$this->form_validation->set_rules('withdraw_fees_type', 'Withdraw Fees Type', 'required|xss_clean');
		$this->form_validation->set_rules('withdraw_fees', 'Withdraw Fees', 'required|xss_clean');
		$this->form_validation->set_rules('maker_fee', 'Maker Fee', 'required|xss_clean');
		$this->form_validation->set_rules('taker_fee', 'Taker Fee', 'required|xss_clean');
		$this->form_validation->set_rules('min_withdraw_limit', 'Minimum Withdraw Limit', 'required|xss_clean');
		$this->form_validation->set_rules('max_withdraw_limit', 'Maximum Withdraw Limit', 'required|xss_clean');
		$this->form_validation->set_rules('marketcap_link', 'Marketcap Link', 'required|xss_clean');
		$this->form_validation->set_rules('coin_link', 'Coin Link', 'required|xss_clean');
		$this->form_validation->set_rules('twitter_link', 'Twitter Link', 'required|xss_clean');
		$this->form_validation->set_rules('sorting_order', 'Sorting Order', 'required|xss_clean');
		if($this->input->post('currency_type')=='fiat'){
			$this->form_validation->set_rules('min_deposit_limit', 'Minimum Deposit Limit', 'required|xss_clean');
			$this->form_validation->set_rules('max_deposit_limit', 'Maximum Deposit Limit', 'required|xss_clean');
			$this->form_validation->set_rules('deposit_fees_type', 'Deposit Type', 'required|xss_clean');
			$this->form_validation->set_rules('deposit_fees', 'Deposit Fees', 'required|xss_clean');
		}
		$this->form_validation->set_rules('currency_name', 'Currency Name', 'required|xss_clean');
		$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'required|xss_clean');
		if ($this->input->post()) 
		{	

			if ($this->form_validation->run())
			{
				
			
				   $updateData=array();

					$crypto_type_other = implode('|',$this->input->post('crypto_type_other'));
					$updateData['email']= $this->input->post('email'); 
					$updateData['emailaddress'] 	= $this->input->post('emailaddress');
					$updateData['contact'] 	= $this->input->post('contact');
					$updateData['project_name'] = $this->input->post('project_name');
					$updateData['coin_name'] 	= $this->input->post('coin_name');
					$updateData['token_type']= $crypto_type_other;
					$updateData['erc_token']= $this->input->post('erc_token'); 
					$updateData['smart_address'] 	= $this->input->post('eth_smart_address');
					$updateData['trx_smart_address'] 	= $this->input->post('trx_smart_address');
					$updateData['bsc_smart_address'] 	= $this->input->post('bsc_smart_address');
					$updateData['webste_link'] 	= $this->input->post('webste_link');
					$updateData['prjct_whitepaper'] = $this->input->post('prjct_whitepaper');
					$updateData['coin_token'] 	= $this->input->post('coin_token');
					$updateData['market_price']= $this->input->post('market_price'); 
					$updateData['supply_coin'] 	= $this->input->post('supply_coin');
					$updateData['coin_exchange'] 	= $this->input->post('coin_exchange');
					$updateData['bounty_user'] = $this->input->post('bounty_user');
					$updateData['listing_btc'] 	= $this->input->post('listing_btc');
					$updateData['withdraw_fees_type'] 	= $this->input->post('withdraw_fees_type');
					$updateData['withdraw_fees']= $this->input->post('withdraw_fees'); 
					$updateData['maker_fee'] 	= $this->input->post('maker_fee');
					$updateData['taker_fee'] 	= $this->input->post('taker_fee');
					$updateData['min_withdraw_limit'] = $this->input->post('min_withdraw_limit');
					$updateData['max_withdraw_limit'] 	= $this->input->post('max_withdraw_limit');
					$updateData['marketcap_link'] 	= $this->input->post('marketcap_link');
					$updateData['coin_link'] 	= $this->input->post('coin_link');
					$updateData['twitter_link'] = $this->input->post('twitter_link');
					$updateData['sorting_order'] 	= $this->input->post('sorting_order');
					$updateData['added_date'] 	= date("Y-m-d H:i:s");
					$currency_name 						= $this->input->post('currency_name');
				    $currency_symbol 					= $this->input->post('currency_symbol');
					$updateData['currency_name']      = $currency_name;
					$updateData['currency_symbol']    = $currency_symbol;
					$updateData['type'] 			= $this->input->post('currency_type');
					if($this->input->post('currency_type')=='fiat')
					{
						$updateData['deposit_fees'] 	 = $this->input->post('deposit_fees');
						$updateData['deposit_fees_type'] = $this->input->post('deposit_fees_type');
						$updateData['min_deposit_limit'] = $this->input->post('min_deposit_limit');
						$updateData['max_deposit_limit'] = $this->input->post('max_deposit_limit');
					}
					if($this->input->post('assets_types')==1)
					{
                       $updateData['coin_type']  = "coin";
                       $updateData['asset_type'] = 1;
					}
					else
					{
					   $updateData['coin_type']  = "token";
					   $updateData['asset_type'] = 0;
					}
					$updateData['currency_decimal'] = $this->input->post('eth_currency_decimal');
					$updateData['bsc_currency_decimal'] = $this->input->post('bsc_currency_decimal');
					$updateData['trx_currency_decimal'] = $this->input->post('trx_currency_decimal');
					//print_r($updateData); exit;
				
	
				//$condition = array('id' => $id);
				$update = $this->common_model->insertTableData('add_coin', $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Coin Request has been updated successfully!');
					admin_redirect('coin_request', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this Coin Request');
					admin_redirect('coin_request/create/', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this Coin Request');
				admin_redirect('coin_request/create/', 'refresh');
			}
		}
		// $data['help_center'] = $this->common_model->getTableData('helpcategory','','','')->result();
		$data['action'] = admin_url() . 'coin_request/create';
		$data['title'] = 'Add New Coin Request';
		$data['meta_keywords'] = 'Add New Coin Request';
		$data['meta_description'] = 'Add New Coin Request';
		$data['main_content'] = 'coin_request/add_coin_request';
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
				$isValids = $this->common_model->getTableData('add_coin', array('coin_id' => $id));
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
					$update = $this->common_model->updateTableData('add_coin', $condition, $updateData);

					
					if ($update) 
					{
					
						$email 		= $isValids->email;
						$username 	= $isValids->username;
						$coin_name 	= $isValids->coin_name;
						// Email
						$email_template = 'Coin_accept';
						$msg = 'User Coin Request Accepted Sucessfully';
						if($this->input->post('status') == "1")
						{

                             
                          $getaddcoin = $this->common_model->getTableData('add_coin', array('coin_id' => $id))->row();

                                                  
                          $insertData['currency_name'] =   $getaddcoin->currency_name;
                          $insertData['currency_symbol'] = $getaddcoin->currency_symbol;
                          $insertData['asset_type'] = $getaddcoin->asset_type;
                          $insertData['crypto_type'] = 'eth';
						  $insertData['crypto_type_other'] = strtolower($getaddcoin->token_type);
                          $insertData['type'] = $getaddcoin->type;
                          $insertData['coin_type'] = $getaddcoin->coin_type;
                          $insertData['status'] = "1";
                          $insertData['verify_request'] = "1";
                          $insertData['deposit_currency'] = $getaddcoin->currency_name;
                          $insertData['coin_link'] = $getaddcoin->coin_link ;                 
                          $insertData['contract_address'] = $getaddcoin->smart_address;
						  $insertData['trx_contract_address'] = $getaddcoin->trx_smart_address;
						  $insertData['bsc_contract_address'] = $getaddcoin->bsc_smart_address;
                          $insertData['withdraw_fees_type'] = $getaddcoin->withdraw_fees_type;
                          $insertData['withdraw_fees'] = $getaddcoin->withdraw_fees ;
                          $insertData['maker_fee']= $getaddcoin->maker_fee;
                          $insertData['taker_fee']= $getaddcoin->taker_fee;
                          $insertData['min_withdraw_limit']= $getaddcoin->min_withdraw_limit;
                          $insertData['max_withdraw_limit']= $getaddcoin->max_withdraw_limit;
                          $insertData['marketcap_link']= $getaddcoin->marketcap_link;
                          $insertData['twitter_link']= $getaddcoin->twitter_link;
                          $insertData['min_deposit_limit']= $getaddcoin->min_deposit_limit;
                          $insertData['max_deposit_limit']= $getaddcoin->max_deposit_limit;
                          $insertData['deposit_fees_type']= $getaddcoin->deposit_fees_type;
                          $insertData['deposit_fees']= $getaddcoin->deposit_fees;
						  //$insertData['currency_decimal']= $getaddcoin->currency_decimal;
						  $insertData['currency_decimal']= ($getaddcoin->currency_decimal != '')?$getaddcoin->currency_decimal:'18';
						  $insertData['trx_currency_decimal']= $getaddcoin->trx_currency_decimal;
						  $insertData['bsc_currency_decimal']= $getaddcoin->bsc_currency_decimal;

						//   echo "<pre>";
						//   print_r($insertData); exit;

						$field_check = $this->db->query("SHOW COLUMNS FROM bidex_crypto_address LIKE '".$getaddcoin->currency_symbol."_status'");
						$field_exists = (count($field_check->result()))?TRUE:FALSE;

						if($field_exists == FALSE)
                          $this->db->query(" ALTER TABLE bidex_crypto_address ADD ".$getaddcoin->currency_symbol."_status INT(11) NOT NULL");

                          //ALTER TABLE `bidex_crypto_address` ADD `PPD_status` INT(11) NOT NULL AFTER `BUSD_status`; 
                      
                          $this->common_model->insertTableData('currency', $insertData);

						}
						if($this->input->post('status') == "2")
						{
							$email_template = 'Coin_cancel';
							$msg = 'User Coin Request Rejected Sucessfully';
						}
						$site_common      =   site_common();                
						$special_vars = array(
								'###USERNAME###' => $email,
								'###COIN###' 	 => $coin_name,		
							);
						$this->email_model->sendMail($email, '', '', $email_template,$special_vars);

						$this->session->set_flashdata('success', $msg);
						admin_redirect('coin_request', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to Change this Coin Request Status');
						admin_redirect('coin_request/view/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to this Coin Request');
					admin_redirect('coin_request', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to Change this Coin Request Status');
				admin_redirect('coin_request/view/' . $id, 'refresh');
			}
		}
		else {
				$this->session->set_flashdata('error', 'Unable to Change this Coin Request Status');
				admin_redirect('coin_request/view/' . $id, 'refresh');
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
            1=>'email',
            2=>'coin_name',           
            3=>'coin_token'
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
			$this->db->like('email',$search);
			$this->db->or_like('coin_name',$search);
			$this->db->or_like('coin_token',$search);
		}		
		$this->db->limit($length,$start);		
		$coin_requests = $this->db->get("bidex_add_coin");
		$num_rows 			= count($coin_requests->result());
		if($num_rows > 0)
		{
			foreach($coin_requests->result() as $coin_request)
			{
				$i++;	           
                $view = '<a href="' . admin_url() . 'coin_request/view/' . $coin_request->coin_id . '" title="View this Request"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';	          
				$data[] = array(
				    $i,
					$coin_request->email,						
					$coin_request->coin_name,
					$coin_request->coin_token,
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