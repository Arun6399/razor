<?php
/**
 * Trade_pairs class
 * @package Spiegel Technologies
 * @subpackage Coinchairs
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Trade_pairs extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function tradepairs_ajax()
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
            3=>'buy_rate_value',            
            4=>'sell_rate_value',
            5=>'min_trade_amount',
            6=>'status',
            7=>'status',
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
            $like = " WHERE b.currency_name LIKE '%".$search."%' OR b.currency_symbol LIKE '%".$search."%' OR c.currency_symbol LIKE '%".$search."%' OR c.currency_name LIKE '%".$search."%' OR a.min_trade_amount LIKE '%".$search."%'";

            $query = 'SELECT a.*, b.currency_name as from_currency, b.currency_symbol as from_currency_symbol, c.currency_name as to_currency, c.currency_symbol as to_currency_symbol FROM `bidex_trade_pairs` as `a` JOIN `bidex_currency` as `b` ON `a`.`from_symbol_id` = `b`.`id` JOIN `bidex_currency` as `c` ON `a`.`to_symbol_id` = `c`.`id`'.$like." LIMIT ".$start.",".$length;

            $countquery = $this->db->query('SELECT a.*, b.currency_name as from_currency, b.currency_symbol as from_currency_symbol, c.currency_name as to_currency, c.currency_symbol as to_currency_symbol FROM `bidex_trade_pairs` as `a` JOIN `bidex_currency` as `b` ON `a`.`from_symbol_id` = `b`.`id` JOIN `bidex_currency` as `c` ON `a`.`to_symbol_id` = `c`.`id`'.$like);

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_name as from_currency, b.currency_symbol as from_currency_symbol, c.currency_name as to_currency, c.currency_symbol as to_currency_symbol FROM `bidex_trade_pairs` as `a` JOIN `bidex_currency` as `b` ON `a`.`from_symbol_id` = `b`.`id` JOIN `bidex_currency` as `c` ON `a`.`to_symbol_id` = `c`.`id` LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_name as from_currency, b.currency_symbol as from_currency_symbol, c.currency_name as to_currency, c.currency_symbol as to_currency_symbol FROM `bidex_trade_pairs` as `a` JOIN `bidex_currency` as `b` ON `a`.`from_symbol_id` = `b`.`id` JOIN `bidex_currency` as `c` ON `a`.`to_symbol_id` = `c`.`id`');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }

		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				if ($result->status == 1) {
					$status = '<label class="label label-info">Activated</label>';
					$extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-activate this Pair","class"=>"poper");
					$changeStatus = anchor(admin_url().'trade_pairs/status/' . $result->id . '/0','<i class="fa fa-unlock text-primary"></i>',$extra);
				} else {
					$status = '<label class="label label-danger">De-Activated</label>';
					$extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this Pair","class"=>"poper");
					$changeStatus = anchor(admin_url().'trade_pairs/status/' . $result->id . '/1','<i class="fa fa-lock text-primary"></i>',$extra);
				}

				$edit = '&nbsp;&nbsp;&nbsp;<a href="' . admin_url() . 'trade_pairs/edit/' . $result->id . '" data-placement="top" data-toggle="popover" data-content="Edit this Pair" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
				$link="'".admin_url().'trade_pairs/delete/'.$result->id."'";

				$delete = '<a onclick="deleteaction('.$link.');" data-placement="top" data-toggle="popover" data-content="If you delete the pair? you cant revert back." class="poper"><i class="fa fa-trash-o text-danger"></i></a>&nbsp;&nbsp;&nbsp;';
				
					$data[] = array(
					    $i, 
						$result->from_currency . '/'. $result->to_currency,
						$result->from_currency_symbol . '/'. $result->to_currency_symbol,
						$result->from_currency . '='.to_decimal($result->buy_rate_value).' '.$result->to_currency,
						$result->from_currency . '='.to_decimal($result->sell_rate_value).' '.$result->to_currency,
						$result->min_trade_amount,
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
	// list
	function index() {
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Get the pairs list pages
		$joins = array('currency as b'=>'a.from_symbol_id = b.id','currency as c'=>'a.to_symbol_id = c.id');
		$data['pair'] = $this->common_model->getJoinedTableData('trade_pairs as a',$joins,'','a.*,b.currency_name as from_currency,b.currency_symbol as from_currency_symbol,c.currency_name as to_currency,c.currency_symbol as to_currency_symbol');
		//echo $this->db->last_query();
		$data['title'] = 'Trade Pairs Management';
		
		$data['meta_keywords'] = 'Trade Pairs Management';
		$data['meta_description'] = 'Trade Pairs Management';
		$data['main_content'] = 'trade_pairs/trade_pairs';
		$data['view'] = 'view_all';
		$data['action'] = '';
		$this->load->view('administrator/admin_template', $data); 
	}

	// Add
	function add() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('from_name', 'From Symbol', 'required|xss_clean');
		$this->form_validation->set_rules('to_name', 'To Symbol', 'required|xss_clean');
		//$this->form_validation->set_rules('buy_rate_value', 'Buy Rate', 'required|numeric|xss_clean');
		//$this->form_validation->set_rules('sell_rate_value', 'Sell Rate', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('trade_min_amt', 'Trade Minimum', 'required|numeric|xss_clean');
		//$this->form_validation->set_rules('commision_type', 'Commision Type', 'required|xss_clean');		
		//$this->form_validation->set_rules('commision_value', 'Commision Value', 'required|numeric|xss_clean');		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$insertData=array();
				$from_symbol_id=$this->input->post('from_name');
				$to_symbol_id=$this->input->post('to_name');
				$from_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$from_symbol_id));
				
				$to_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$to_symbol_id));
				
				$is_exist_pair=$this->common_model->get_pair_exist($from_symbol_id,$to_symbol_id);
				$exist_record=$is_exist_pair->result_array();
				if(!empty($exist_record)){
					$this->session->set_flashdata('error', 'Already exists this pair. Create something new pair.');
					admin_redirect('trade_pairs/add', 'refresh');
				}else if($from_symbol_details->num_rows()==1&&$to_symbol_details->num_rows()==1)
				{
					$getfrom = $from_symbol_details->row();
					$getto = $to_symbol_details->row();
					$from = strtolower($getfrom->currency_symbol);
					$to = strtolower($getto->currency_symbol);
					$from_desc = strtolower($getfrom->currency_name);
					$to_desc = strtolower($getto->currency_name);
					$insertData['from_symbol_id'] = $from_symbol_id;
					$insertData['to_symbol_id'] = $to_symbol_id;
					$insertData['buy_rate_value'] = $this->input->post('buy_rate_value');
					$insertData['sell_rate_value'] = $this->input->post('sell_rate_value');
					$insertData['min_trade_amount'] = $this->input->post('trade_min_amt');
					//$insertData['market_price'] = $this->input->post('market_price');
					//$insertData['commision_type'] = $this->input->post('commision_type');
					//$insertData['commision_value'] = $this->input->post('commision_value');
					$insertData['status'] = $this->input->post('status');
					$insertData['data_fetch'] = $this->input->post('data_fetch');

					$insertData['created'] = gmdate(time());					
					// Prepare to insert Data
					$insert = $this->common_model->insertTableData('trade_pairs', $insertData);

					
					$folder_prop = $this->folder($from."_".$to);
					$file_create = json_decode($folder_prop->success);
					$file_create_trade = json_decode($folder_prop->trade_success);
					$insertDatas = array(
                        'name' =>strtoupper($from.$to),
                        'exchange_traded' => 'Bluerico',
                        'description' => strtoupper($from_desc)." / ".strtoupper($to_desc),
                        'timezone' =>'India/Kolkata',
                        'type' => 'crypto'
						);
						$insert_file = $this->common_model->insertTableData('coins_symbols', $insertDatas);
					if($file_create == 1 && $file_create_trade ==1 && $insert)
					{						
						$this->session->set_flashdata('success', 'Trade pair has been added successfully with Json file created!');
						admin_redirect('trade_pairs', 'refresh');
					}
					else 
					{
						$this->session->set_flashdata('success', 'Trade pair has been added successfully with Depthchart and Json file created!');
						admin_redirect('trade_pairs', 'refresh');
					}
					
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to add the trade pair !');
					admin_redirect('trade_pairs/add', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('trade_pairs/add', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'trade_pairs/add';
		
		$data['title'] = 'Add Trade Pair';
		$data['meta_keywords'] = 'Add Trade Pair';
		$data['meta_description'] = 'Add Trade Pair';
		$data['main_content'] = 'trade_pairs/trade_pairs';
		$data['view'] = 'add';
		$data['currency']=$this->common_model->getTableData('currency', array('status' => 1));
		$this->load->view('administrator/admin_template', $data);
	}
	public function folder($foldername) 
	{ 
		$json = array();
		$directory = FCPATH . 'depthchart';
		$tradechart_directory = FCPATH . 'chart';
		if (!is_dir($directory))
		{
			mkdir($directory , 0777);
			$my_file = $foldername.'.json';			
            $handle = fopen($my_file, 'w');
            chmod($my_file, 0777);
			$json['success'] = '0';
		} 
		else 
		{
			$my_file = $directory."/".$foldername.'.json';
            $handle = fopen($my_file, 'w'); 
            chmod($my_file, 0777);
		    $json['success'] = '1';
		}	
		if (!is_dir($tradechart_directory))
		{
			mkdir($tradechart_directory , 0777);
			$my_file = $foldername.'.json';
            $handle = fopen($my_file, 'w');
            chmod($my_file, 0777);
			$json['trade_success'] = '0';
		}
		else 
		{
			$my_file = $tradechart_directory."/".$foldername.'.json';
            $handle = fopen($my_file, 'w'); 
            chmod($my_file, 0777);
		    $json['trade_success'] = '1';
		}	
		return json_encode($json);
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
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('trade_pairs', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('trade_pairs');
		}
		// Form validation
		$this->form_validation->set_rules('from_name', 'From Symbol', 'required|xss_clean');
		$this->form_validation->set_rules('to_name', 'To Symbol', 'required|xss_clean');
		//$this->form_validation->set_rules('buy_rate_value', 'Buy Rate', 'required|numeric|xss_clean');
		//$this->form_validation->set_rules('sell_rate_value', 'Sell Rate', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('trade_min_amt', 'Trade Minimum', 'required|numeric|xss_clean');
		//$this->form_validation->set_rules('market_price', 'Market Price', 'required|numeric|xss_clean');
		//$this->form_validation->set_rules('commision_type', 'Commision Type', 'required|xss_clean');		
		//$this->form_validation->set_rules('commision_value', 'Commision Value', 'required|numeric|xss_clean');		
		
		if ($this->input->post()) {
			if ($this->form_validation->run())
			{
				$updateData=array();
				$from_symbol_id=$this->input->post('from_name');
				$to_symbol_id=$this->input->post('to_name');
				$from_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$from_symbol_id));
				$to_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$to_symbol_id));
				
				if($from_symbol_details->num_rows()==1 && $to_symbol_details->num_rows()==1 && empty($exist_record))
				{
					$updateData['from_symbol_id'] = $from_symbol_id;
					$updateData['to_symbol_id'] = $to_symbol_id;
					$updateData['buy_rate_value'] = $this->input->post('buy_rate_value');
					$updateData['sell_rate_value'] = $this->input->post('sell_rate_value');
					$updateData['min_trade_amount'] = $this->input->post('trade_min_amt');
					//$updateData['market_price'] = $this->input->post('market_price');
					//$updateData['commision_type'] = $this->input->post('commision_type');
					//$updateData['commision_value'] = $this->input->post('commision_value');
					$updateData['status'] = $this->input->post('status');
					$updateData['data_fetch'] = $this->input->post('data_fetch');			
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('trade_pairs', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Trade pair has been updated successfully!');
						admin_redirect('trade_pairs', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update this pair');
						admin_redirect('trade_pairs/edit/' . $id, 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to update this pair');
					admin_redirect('trade_pairs/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this pair');
				admin_redirect('trade_pairs/edit/' . $id, 'refresh');
			}
			
		}
		$data['pair'] = $isValid->row();
		$data['action'] = admin_url() . 'trade_pairs/edit/' . $id;
		
		$data['title'] = 'Edit Trade Pair';
		$data['meta_keywords'] = 'Edit Trade Pair';
		$data['meta_description'] = 'Edit Trade Pair';
		$data['main_content'] = 'trade_pairs/trade_pairs';
		$data['view'] = 'edit';
		$data['currency']=$this->common_model->getTableData('currency', array('status' => 1));
		$from_symbol = $data['pair']->from_symbol_id;
		$to_symbol = $data['pair']->to_symbol_id;
		$data['to_symbol']=$to_symbol;
		$old_pairs=$this->common_model->getTableData('trade_pairs', array('from_symbol_id' => $from_symbol,'to_symbol_id !='=>$to_symbol),'to_symbol_id')->result_array();
		if($old_pairs)
		{
			$old_pairs = array_column($old_pairs, 'to_symbol_id');
		}
		else
		{
			$old_pairs=array($from_symbol);
		}
		$data['old_pairs']=$this->common_model->getTableData('currency',array('status' => 1),'id,currency_name','','','','','','','',array('id',$old_pairs));//die;
		$this->load->view('administrator/admin_template', $data);
	}
	// Fees list
	function fees_list($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Get the pairs list pages
		$data['trade_fees']=$this->common_model->getTableData('trade_fees', array('pair_id' => $id));
		
		$data['title'] = 'Trade Pairs Fees Management';
		$data['meta_keywords'] = 'Trade Pairs Fees Management';
		$data['meta_description'] = 'Trade Pairs Fees Management';
		$data['main_content'] = 'trade_pairs/trade_pairs';
		$data['view'] = 'view_all_fees';
		$data['id']=$id;
		$this->load->view('administrator/admin_template', $data); 
	}
	// Add Fees
	function add_fees($pair_id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('from_volume', 'From Volume', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('to_volume', 'To Volume', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('maker', 'Fees', 'required|xss_clean|numeric');
		//$this->form_validation->set_rules('taker', 'Taker Fees', 'required|xss_clean|numeric');		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$insertData=array();
				$insertData['from_volume'] = $this->input->post('from_volume');
				$insertData['to_volume'] = $this->input->post('to_volume');
				$insertData['maker'] = $this->input->post('maker');
				$insertData['taker'] = $this->input->post('maker');
				$insertData['pair_id'] = $pair_id;
				// Prepare to insert Data
				$insert = $this->common_model->insertTableData('trade_fees', $insertData);
				if ($insert) {
					$this->session->set_flashdata('success', 'Trade pair fees has been added successfully!');
					admin_redirect('trade_pairs/fees_list/'.$pair_id, 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to add the trade pair fees!');
					admin_redirect('trade_pairs/add_fees/'.$pair_id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('trade_pairs/add_fees/'.$pair_id, 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'trade_pairs/add_fees/'.$pair_id;
		
		$data['title'] = 'Add Trade Pair Fees';
		$data['meta_keywords'] = 'Add Trade Pair Fees';
		$data['meta_description'] = 'Add Trade Pair Fees';
		$data['main_content'] = 'trade_pairs/trade_pairs';
		$data['view'] = 'add_fees';
		//$data['trade_fees']=$this->common_model->getTableData('trade_fees', array('pair_id' => $pair_id));
		$this->load->view('administrator/admin_template', $data);
	}
	// Edit fees
	function fees($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('trade_fees', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('trade_pairs');
		}
		$this->form_validation->set_rules('from_volume', 'From Volume', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('to_volume', 'To Volume', 'required|xss_clean|numeric');
		$this->form_validation->set_rules('maker', 'Fees', 'required|xss_clean|numeric');
		//$this->form_validation->set_rules('taker', 'Taker Fees', 'required|xss_clean|numeric');		
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['from_volume'] = $this->input->post('from_volume');
				$updateData['to_volume'] = $this->input->post('to_volume');
				$updateData['maker'] = $this->input->post('maker');
				$updateData['taker'] = $this->input->post('maker');
				// Prepare to update Data
				$update = $this->common_model->updateTableData('trade_fees', array('id'=>$id), $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Trade pair fees has been updated successfully!');
					admin_redirect('trade_pairs/fees_list/'.$isValid->row('pair_id'), 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update the trade pair fees!');
					admin_redirect('trade_pairs/fees/'.$id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Some data are missing!');
				admin_redirect('trade_pairs/fees/'.$id, 'refresh');
			}
			
		}
		//print_r($isValid->row());die;
		$data['fees'] = $isValid->row();
		$data['action'] = admin_url() . 'trade_pairs/fees/' . $id;
		
		$data['title'] = 'Edit Trade Pair Fees';
		$data['meta_keywords'] = 'Edit Trade Pair Fees';
		$data['meta_description'] = 'Edit Trade Pair Fees';
		$data['main_content'] = 'trade_pairs/trade_pairs';
		$data['view'] = 'edit_fees';
		$data['trade_fees']=$this->common_model->getTableData('trade_fees', array('pair_id' => $id));
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
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('trade_pairs', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('trade_pairs', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Trade pair activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Trade pair de-activated successfully');
				}
				admin_redirect('trade_pairs');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with trade pair status updation');
				admin_redirect('trade_pairs');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this trade pair');
			admin_redirect('trade_pairs');
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
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('trade_pairs', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid 
			$condition = array('id' => $id);

			$rowS = $this->common_model->getTableData('trade_pairs', array('id' => $id))->row(); 

			$from = $rowS->from_symbol_id; $to = $rowS->to_symbol_id;
			$from_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$from))->row();				
			$to_symbol_details=$this->common_model->getTableData('currency', array('status' => 1,'id'=>$to))->row();
			$from_sym = strtolower($from_symbol_details->currency_symbol);
			$to_sym = strtolower($to_symbol_details->currency_symbol);
			$filename = $from_sym."_".$to_sym.'.json';
			$conditions = array('name' => strtoupper($from_sym).strtoupper($to_sym));
			$delete = $this->common_model->deleteTableData('trade_pairs', $condition);
			$delete = $this->common_model->deleteTableData('coins_symbols', $conditions);

			unlink( FCPATH.'chart/'.$filename);
			unlink( FCPATH.'depthchart/'.$filename);

			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Trade pair deleted successfully');
				admin_redirect('trade_pairs');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with trade pair deletion');
				admin_redirect('trade_pairs');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('trade_pairs');
		}	
	}
	// Status change
	function status_fees($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('trade_fees', array('id' => $id));
		if ($isValid->num_rows() > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('trade_fees', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Trade pair fees activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Trade pair fees de-activated successfully');
				}
				admin_redirect('trade_pairs/fees_list/'.$isValid->row('pair_id'));
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with trade pair fees status updation');
				admin_redirect('trade_pairs/fees_list/'.$isValid->row('pair_id'));
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this trade pair');
			admin_redirect('trade_pairs');
		}
	}
	// Delete fees page
	function delete_fees($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('trade_pairs');
		}
		$isValid = $this->common_model->getTableData('trade_fees', array('id' => $id));
		if ($isValid->num_rows() > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('trade_fees', $condition);
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Trade pair fees deleted successfully');
				admin_redirect('trade_pairs/fees_list/'.$isValid->row('pair_id'));
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with trade pair fees deletion');
				admin_redirect('trade_pairs/fees_list/'.$isValid->row('pair_id'));	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('trade_pairs');
		}	
	}
	function get_to_symbol()
	{
		$from_symbol = $this->input->get_post('from_symbol');
		$to_symbol = $this->input->get_post('to_symbol');
		$cond=array('from_symbol_id' => $from_symbol);
		if($to_symbol&&$to_symbol!=0)
		{
			$cond['to_symbol_id !=']=$to_symbol;
		}
		$old_pairs=$this->common_model->getTableData('trade_pairs', $cond,'to_symbol_id')->result_array();
		if($old_pairs)
		{
			$old_pairs = array_column($old_pairs, 'to_symbol_id');
			//array_push($old_pairs,$from_symbol);
			$old_pairs=array($from_symbol);
		}
		else
		{
			$old_pairs=array($from_symbol);
		}
		$pairs=$this->common_model->getTableData('currency',array('status' => 1),'id,currency_name','','','','','','','',array('id',$old_pairs));

		$txt='<option value="">Select</option>';
		foreach($pairs->result() as $cur){
		$txt.='<option value="'.$cur->id.'">'.$cur->currency_name.'</option>';
		}
		echo $txt;
	}
	function delete_this_record(){
		$delete_id = $this->input->get_post('delete_id');
		$result_arr=$this->common_model->get_pair_coin_details($delete_id);
		if(count($result_arr->result())>0){
			$return_arr=array("error"=>1,"desc"=>"Unable to delete this pair!");
		}else{
			$return_arr=array("error"=>0,"desc"=>"");
		}
		//echo $this->db->last_query();		
		echo json_encode($return_arr);
		die;
	}
 }