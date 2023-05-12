<?php
/**
 * Currency class
 * @package Spiegel Technologies

 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Currency extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function currency_ajax()
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
            1=>'currency_name',
            2=>'currency_symbol',
            3=>'type',
            4=>'coin_type',
            5=>'sort_order',
            6=>'status',
            7=>'status'
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
            $like = "WHERE (bidex_currency.currency_name LIKE '%".$search."%' OR bidex_currency.currency_symbol LIKE '%".$search."%' OR bidex_currency.type LIKE '%".$search."%' OR bidex_currency.coin_type LIKE '%".$search."%')"; 
        }
        
        $query = "SELECT * FROM `bidex_currency` ".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
        $users_history = $this->db->query($query);
        $users_history_result = $users_history->result(); 
        
		$num_rows = count($users_history_result);

		if(count($users_history_result)>0)
		{
			foreach($users_history->result() as $users){
				$i++;
				if ($users->status == 1) {
                            $status = '<label class="label label-info">Activated</label>';
                            $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-activate this currency","class"=>"poper");
                            $changeStatus = anchor(admin_url().'currency/status/' . $users->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
                        } else {
                            $status = '<label class="label label-danger">De-Activated</label>';
                            $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this currency","class"=>"poper");
                            $changeStatus = anchor(admin_url().'currency/status/' . $users->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
                        }

                    $edit = '&nbsp;&nbsp;&nbsp;<a href="' . admin_url() . 'currency/edit/' . $users->id . '" data-placement="top" data-toggle="popover" data-content="Edit this currency" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
                    $link="'".admin_url().'currency/delete/'.$users->id."'";
                    
                    $delete = '<a onclick="deleteaction('.$link.');" data-placement="bottom" data-toggle="popover" data-content="If you delete the currency? you cant revert back." class="poper"
><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';

//$sort = '<a href="javascript:void(0);" title="Double Click here to change the order." class="sorting_my editable editable-click" data-pk="'.$users->id.'">'.$users->sort_order.'</a>';

				$data[] = array(
					    $i, 
						$users->currency_name,
						$users->currency_symbol,
						$users->type,
						ucfirst($users->coin_type),
						$users->sort_order,
						$status,
						$changeStatus.$edit.$delete
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

 	function currency_text()
 	{
 		$sortorder = $_POST['currency_text'];
 		$currency_id = $_POST['currency_id'];

 		$update = $this->common_model->updateTableData('currency',array('id'=>$currency_id),array('sort_order'=>$sortorder));
 		if($update)
 		{
 			echo true;
 		}
 		else
 		{
 			echo false;
 		}
 	}

	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		// Get the list pages
		$data['currency'] = $this->common_model->getTableData('currency', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Currency Management';
		$data['meta_keywords'] = 'Currency Management';
		$data['meta_description'] = 'Currency Management';
		$data['main_content'] = 'currency/currency';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Add
	function add() 
	{		
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('currency_name', 'Currency Name', 'required|xss_clean');
		$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'required|xss_clean');
		$this->form_validation->set_rules('min_withdraw_limit', 'Minimum Withdraw Limit', 'required|xss_clean');
		$this->form_validation->set_rules('max_withdraw_limit', 'Maximum Withdraw Limit', 'required|xss_clean');
		$this->form_validation->set_rules('withdraw_fees_type', 'Withdraw Type', 'required|xss_clean');
		$this->form_validation->set_rules('withdraw_fees', 'Withdraw Fees', 'required|xss_clean');

		if($this->input->post('currency_type')=='fiat'){
			$this->form_validation->set_rules('min_deposit_limit', 'Minimum Deposit Limit', 'required|xss_clean');
			$this->form_validation->set_rules('max_deposit_limit', 'Maximum Deposit Limit', 'required|xss_clean');
			$this->form_validation->set_rules('deposit_fees_type', 'Deposit Type', 'required|xss_clean');
			$this->form_validation->set_rules('deposit_fees', 'Deposit Fees', 'required|xss_clean');
		}

		//$this->form_validation->set_rules('reserve_Amount', 'Reserve Amount', 'required|xss_clean');
		//$this->form_validation->set_rules('online_usdprice', 'USD Exchange Rate', 'required|xss_clean');

		if ($this->input->post()) 
		{				
			if ($this->form_validation->run())
			{
				$image = $_FILES['image']['name'];

				if($image!="") 
				{
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/currency');

					if($uploadimage)
					{
						//$image=$uploadimage['secure_url'];
						if(is_array($uploadimage)) {
							$image=$uploadimage['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							admin_redirect('currency/add', 'refresh');
						}
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your currency image');
						admin_redirect('currency/add', 'refresh');
					}
				} 
				else 
				{ 
					$image=""; 
				}

				$insertData 						= array();
				$currency_name 						= $this->input->post('currency_name');
				$currency_symbol 					= $this->input->post('currency_symbol');
				$details 							= $this->db->query("SELECT * FROM `bidex_currency` WHERE `currency_name` = '".$currency_name."' OR `currency_symbol` LIKE '%".$currency_symbol."'");

				if($details->num_rows()==0)
				{
					
					$insertData['currency_name']      = $currency_name;
					$insertData['currency_symbol']    = $currency_symbol;
					$insertData['reserve_Amount']     = ($this->input->post('reserve_Amount')!='')?$this->input->post('reserve_Amount'):0;
					$insertData['online_usdprice']    = ($this->input->post('online_usdprice')!='')?$this->input->post('online_usdprice'):0;
					$insertData['usdpice_ref_site']    = ($this->input->post('usdpice_ref_site')!='')?$this->input->post('usdpice_ref_site'):'';
					$insertData['marketcap_link']    = $this->input->post('marketcap_link');
					$insertData['coin_link']    = $this->input->post('coin_link');
					$insertData['twitter_link']    = $this->input->post('twitter_link');
					$insertData['withdraw_fees']      = $this->input->post('withdraw_fees');
					$insertData['maker_fee'] 		  = $this->input->post('maker_fee');
					$insertData['taker_fee'] 		  = $this->input->post('taker_fee');
					$insertData['withdraw_fees_type'] = $this->input->post('withdraw_fees_type');
					$insertData['min_withdraw_limit'] = $this->input->post('min_withdraw_limit');
					$insertData['max_withdraw_limit'] = $this->input->post('max_withdraw_limit');

					if($this->input->post('currency_type')=='fiat')
					{
						$updateData['deposit_fees'] 	 = $this->input->post('deposit_fees');
						$updateData['deposit_fees_type'] = $this->input->post('deposit_fees_type');
						$updateData['min_deposit_limit'] = $this->input->post('min_deposit_limit');
						$updateData['max_deposit_limit'] = $this->input->post('max_deposit_limit');
					}

					$insertData['status'] 			= $this->input->post('status');
					//$insertData['token_bac_value'] 	= $this->input->post('bac_token');
					$insertData['type'] 			= $this->input->post('currency_type');
					$insertData['created'] 			= gmdate(time());
					$insertData['image'] 			= $image;
					$insertData['contract_address'] = $this->input->post('contract_address');
					$insertData['currency_decimal'] = $this->input->post('currency_decimal');
					$insertData['sort_order'] = $this->input->post('sorting_order');
					//$insertData['expiry_date'] = date("Y-m-d",strtotime($this->input->post('expiry_date')));

					if($this->input->post('assets_types')==1)
					{
                       $insertData['coin_type']  = "coin";
                       $insertData['asset_type'] = 1;
					}
					else
					{
					   $insertData['coin_type']  = "token";
					   $insertData['asset_type'] = 0;
					}
					
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('currency', $insertData);
					if ($insert) 
					{
						/*** Admin Side -- Crypto coin based index add in admin_wallet table ***/
						/*** Start Process ***/

						$admin_id  = $this->session->userdata('loggeduser');

				       	$whers_con =   "id=".$admin_id;

						$get_admin =   $this->common_model->getrow("admin_wallet", $whers_con);

						//$currency_symbol = "ZTC";

						$update = array();

				        if(!empty($get_admin)) 
				        {
				            $admin_currency_address 		= json_decode($get_admin->addresses, true); 
				            $admin_currency_balance 		= json_decode($get_admin->balance, true); 
				            $admin_currency_wallet_balance 	= json_decode($get_admin->wallet_balance, true); 

				            $admin_currency_address[$currency_symbol] 		 = "";
				            $admin_currency_balance[$currency_symbol] 		 = 0;
				            $admin_currency_wallet_balance[$currency_symbol] = 0;

				            $update['addresses']   		=   json_encode($admin_currency_address);
				            $update['balance']   		=   json_encode($admin_currency_balance);
				            $update['wallet_balance']   =   json_encode($admin_currency_wallet_balance);

				            $condition 					=   array('id' => $admin_id);
				        	$exc       					=   $this->common_model->updateTableData('admin_wallet', $condition, $update);

				        }
				        else
				        {
				        	$admin_currency_address[$currency_symbol] 		 = "";
				            $admin_currency_balance[$currency_symbol] 		 = 0;
				            $admin_currency_wallet_balance[$currency_symbol] = 0;

				            $$update['addresses']   		=   json_encode($admin_currency_address);
				            $update['balance']   		=   json_encode($admin_currency_balance);
				            $update['wallet_balance']   =   json_encode($admin_currency_wallet_balance);
				            $update['user_id']          =   $admin_id;
				            $update['XRP_tag']          =   0;
				            $update['XRP_secret']       =   0;

				          

				        	$insert = $this->common_model->insertTableData('admin_wallet', $update);
							if ($insert) 
							{
								//print_r($insert);
							}
				            
				        }      

				        
						/*** End Process ***/


						$unserialise = array();
						$array = array($insert=>0);						
						$tb_val=$this->db->dbprefix('crypto_address');
				        $this->db->query("ALTER TABLE ".$tb_val." ADD ".($currency_symbol.'_status')." tinyint(1) NOT NULL");

				        /** UPDATE ALL USERS **/
				        $userdetails = $this->common_model->getTableData('crypto_address')->result();
						foreach($userdetails as $user_details) 
						{
							/** UPDATE address **/
							$coin_address = $user_details->address;
							$userid = $user_details->user_id;
							$unserialise = unserialize($coin_address);							
							$serialise = $unserialise+$array;
							$update_address = serialize($serialise);
							$data = array('address'=>$update_address);
							$wheres = array('user_id'=>$userid);
			                $update_data = $this->common_model->updateTableData("crypto_address",$wheres,$data);
						}
                          
						$wallet_array = array($insert=>0);
						$walletdetails = $this->common_model->getTableData('wallet')->result();
						foreach($walletdetails as $wallet) 
						{
							$coin_wallet = $wallet->crypto_amount;
							$userid = $wallet->user_id;
							$unserialise = unserialize($coin_wallet);							
							$serialise = $unserialise['Exchange AND Trading']+$wallet_array;
                            $serialise_final = array('Exchange AND Trading'=>$serialise);
							$update_wallet = serialize($serialise_final);
							$data = array('crypto_amount'=>$update_wallet);
							$wheres = array('user_id'=>$userid);
			                $update_data = $this->common_model->updateTableData("wallet",$wheres,$data);
						}

						$this->session->set_flashdata('success', 'Currency has been added successfully!');

						admin_redirect('currency', 'refresh');

					} 
					else 
					{
						$this->session->set_flashdata('error', 'Unable to add the currency !');
						admin_redirect('currency/add', 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to add the currencys !');
					admin_redirect('currency/add', 'refresh');
				}
			}
			else 
			{
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('currency/add', 'refresh');
			}
			
		}
		
		$data['action'] = admin_url() . 'currency/add';
		$data['title'] = 'Add Currency';
		$data['meta_keywords'] = 'Add Currency';
		$data['meta_description'] = 'Add Currency';
		$data['main_content'] = 'currency/currency';
		$data['view'] = 'add';
		$data['currency']=$this->common_model->getTableData('currency', array('status' => 1));
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
			admin_redirect('currency');
		}
		$isValid = $this->common_model->getTableData('currency', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('currency');
		}
		else
		{
			$edit_currency = $isValid->row();
			$data['curn_type'] = $edit_currency->coin_type;
		}
		// Form validation
		$this->form_validation->set_rules('currency_name', 'Currency Name', 'required|xss_clean');
		$this->form_validation->set_rules('currency_symbol', 'Currency Symbol', 'required|xss_clean');	
		//$this->form_validation->set_rules('reserve_Amount', 'Reserve Amount', 'required|xss_clean');
		//$this->form_validation->set_rules('online_usdprice', 'USD Exchange Rate', 'required|xss_clean');
		$this->form_validation->set_rules('min_withdraw_limit', 'Minimum Withdraw Limit', 'required|xss_clean');
		$this->form_validation->set_rules('max_withdraw_limit', 'Maximum Withdraw Limit', 'required|xss_clean');
		$this->form_validation->set_rules('withdraw_fees_type', 'Withdraw Type', 'required|xss_clean');
		$this->form_validation->set_rules('withdraw_fees', 'Withdraw Fees', 'required|xss_clean');

		if($this->input->post('currency_type')=='fiat'){
		$this->form_validation->set_rules('min_deposit_limit', 'Minimum Deposit Limit', 'required|xss_clean');
		$this->form_validation->set_rules('max_deposit_limit', 'Maximum Deposit Limit', 'required|xss_clean');
		$this->form_validation->set_rules('deposit_fees_type', 'Deposit Type', 'required|xss_clean');
		$this->form_validation->set_rules('deposit_fees', 'Deposit Fees', 'required|xss_clean');
		}
		
		if ($this->input->post()) {				
			
			//echo "<pre>";print_r($_POST); exit;
			if ($this->form_validation->run())
			{
				$image = $_FILES['image']['name'];
				if($image!="") {
					$uploadimage=cdn_file_upload($_FILES["image"],'uploads/currency',$isValid->row('image'));
					if($uploadimage)
					{
						//$image=$uploadimage['secure_url'];
						if(is_array($uploadimage)) {
							$image=$uploadimage['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage );
							$this->session->set_flashdata('error', $errorMsg);
							admin_redirect('currency/edit/' . $id, 'refresh');
						}
					}
					else
					{
						$this->session->set_flashdata('error','Problem with your currency image');
						admin_redirect('currency/edit/' . $id, 'refresh');
					}
				} else {
					$image = $this->input->post('oldimage');
				}

				$updateData=array();
				$currency_name=$this->input->post('currency_name');
				$currency_symbol=$this->input->post('currency_symbol');
				$details=$this->common_model->getTableData('currency',array('currency_name'=>$currency_name),'','','','');//array('currency_symbol'=>$currency_symbol)
				//echo $this->db->last_query();die;
				if($details->num_rows()==0||$details->row('id')==$id)
				{
					
					$updateData['currency_name'] = $currency_name;
					$updateData['currency_symbol'] = $currency_symbol;
					$updateData['status'] = $this->input->post('status');
					$updateData['online_usdprice'] = ($this->input->post('online_usdprice')!='')?$this->input->post('online_usdprice'):'0';
					$updateData['usdpice_ref_site']    = ($this->input->post('usdpice_ref_site')!='')?$this->input->post('usdpice_ref_site'):'';
					$updateData['marketcap_link']    = $this->input->post('marketcap_link');
					$updateData['coin_link']    = $this->input->post('coin_link');
					$updateData['twitter_link']    = $this->input->post('twitter_link');
					$updateData['reserve_Amount'] = ($this->input->post('reserve_Amount')!='')?$this->input->post('reserve_Amount'):'0';	
					$updateData['expiry_date'] = date("Y-m-d",strtotime($this->input->post('expiry_date')));
					$updateData['withdraw_fees'] = $this->input->post('withdraw_fees');
					$updateData['maker_fee'] = $this->input->post('maker_fee');
					$updateData['taker_fee'] = $this->input->post('taker_fee');
					$updateData['withdraw_fees_type'] = $this->input->post('withdraw_fees_type');
					$updateData['min_withdraw_limit'] = $this->input->post('min_withdraw_limit');
					$updateData['max_withdraw_limit'] = $this->input->post('max_withdraw_limit');
					if($this->input->post('currency_type')=='fiat'){
					$updateData['deposit_fees'] = $this->input->post('deposit_fees');
					$updateData['deposit_fees_type'] = $this->input->post('deposit_fees_type');
					$updateData['min_deposit_limit'] = $this->input->post('min_deposit_limit');
					$updateData['max_deposit_limit'] = $this->input->post('max_deposit_limit');
					}
					$updateData['type'] = $this->input->post('currency_type');
					$updateData['image'] = $image;
					$updateData['contract_address'] = $this->input->post('contract_address');
					$updateData['currency_decimal'] = $this->input->post('currency_decimal');
					$updateData['show_decimal'] = $this->input->post('show_decimal');
					//$updateData['token_bac_value'] = $this->input->post('bac_token');
					$updateData['sort_order'] = $this->input->post('sorting_order');
					if($this->input->post('assets_types')==1)
					{
                       $updateData['coin_type'] = "coin";
                       $updateData['asset_type'] = 1;
					}
					else
					{
					   $updateData['coin_type'] = "token";
					   $updateData['asset_type'] = 0;
					}

					$updateData['deposit_status'] = $this->input->post('deposit_status');
					$updateData['withdraw_status'] = $this->input->post('withdraw_status');

					$condition = array('id' => $id);
					// updated via Common model
					//echo $this->db->last_query();die;
					$update = $this->common_model->updateTableData('currency', $condition, $updateData);

					if ($update) {
						$this->session->set_flashdata('success', 'Currency has been updated successfully!');
						admin_redirect('currency', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this currency');
						admin_redirect('currency/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this currency');
					admin_redirect('currency/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this currency');
				admin_redirect('currency/edit/' . $id, 'refresh');
			}
			
		}
		
		$data['currency'] = $isValid->row();
		$data['action'] = admin_url() . 'currency/edit/' . $id;
		$data['title'] = 'Edit Currency';
		$data['meta_keywords'] = 'Edit Currency';
		$data['meta_description'] = 'Edit Currency';
		$data['main_content'] = 'currency/currency';
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
			admin_redirect('currency');
		}
		$isValid = $this->common_model->getTableData('currency', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('currency', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Currency activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Currency de-activated successfully');
				}
				admin_redirect('currency');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with currency status updation');
				admin_redirect('currency');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this currency');
			admin_redirect('currency');
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
			admin_redirect('currency');
		}
		$isValid = $this->common_model->getTableData('currency', array('id' => $id))->num_rows();
		if ($isValid > 0) 
		{ 
			$rowS = $this->common_model->getTableData('currency', array('id' => $id))->row();
		    $currency_symbol = $rowS->currency_symbol; 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('currency', $condition);
			if ($delete) 
			{ 				
				$tb_val=$this->db->dbprefix('crypto_address');
				$this->db->query("ALTER TABLE ".$tb_val." DROP COLUMN ".($currency_symbol.'_status'));				
				$this->session->set_flashdata('success', 'Currency deleted successfully');
				admin_redirect('currency');
			} 
			else 
			{ //False
				$this->session->set_flashdata('error', 'Problem occure with currency deletion');
				admin_redirect('currency');	
			}
		} 
		else 
		{
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('currency');
		}	
	}
 }