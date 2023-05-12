<?php
/**
 * Trade History class
 * @package Spiegel Technologies
 * @subpackage zonexh
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */

 class Trade_holdorders extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

    function buy_tradepair_ajax($pair=null)
    {
        /*if($pair!='') {
            $expPair = explode('-', $pair);
            $from = getcurrencydetails($expPair[0]);
            $to = getcurrencydetails($expPair[1]);
            $pair_id = $this->common_model->getTableData('trade_pairs',array('from_symbol_id'=>$from->id,'to_symbol_id'=>$to->id))->row();
        } */

        $formdate= date("Y-m-d", strtotime($pair));

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
            1=>'datetime',
            2=>'user_id',
            3=>'currency_name', 
            4=>'amount',
            5=>'Price',
            6=>'Fee',
            7=>'Total',
            8=>'status'
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

        if($formdate!='') { 


$query ="SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol FROM cpm_coin_order as a JOIN cpm_trade_pairs as b ON a.pair = b.id JOIN cpm_users as c ON a.userId = c.id JOIN cpm_currency as d ON b.from_symbol_id = d.id JOIN cpm_currency as e ON b.to_symbol_id = e.id WHERE a.Type = 'buy' AND a.orderDate='".$formdate."' ORDER BY a.tradetime DESC";


$countquery = $this->db->query("SELECT a.*,d.coin_type,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol FROM cpm_coin_order as a JOIN cpm_trade_pairs as b ON a.pair = b.id JOIN cpm_users as c ON a.userId = c.id JOIN cpm_currency as d ON b.from_symbol_id = d.id JOIN cpm_currency as e ON b.to_symbol_id = e.id WHERE a.Type = 'buy' AND a.orderDate='".$formdate."' ORDER BY a.tradetime DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();



        } else {

            $query = 'SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.Type = "buy" and  a.status="active" and a.orderDate='.$formdate.' ORDER BY `a`.`tradetime` DESC LIMIT '.$start.','.$length;


            $countquery = $this->db->query('SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.Type = "buy" and  a.status="active" and a.orderDate='.$formdate.' ORDER BY `a`.`tradetime` DESC');
            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
         


        // echo "<pre>";    
        // print_r( $users_history->result() );die;
        $tt = $query;
        if($num_rows>0)
        {
            foreach($users_history->result() as $result){
                $i++;               
                
                    $data[] = array(
                        $i, 
                        gmdate("d-m-Y h:i a", strtotime($result->datetime)),
                        getUserDetails($result->userId,'cpm_username'),
                        $result->from_currency_symbol.'-'.$result->to_currency_symbol,
                        //gmdate("d-m-Y h:i:s", strtotime($result->datetime)),
                        $result->Amount." ".$result->from_currency_symbol,
                        $result->Price." ".$result->to_currency_symbol,
                        $result->Fee." ".$result->to_currency_symbol,
                        $result->Total." ".$result->to_currency_symbol,
                        $result->coin_type,
                        ucfirst($result->status)
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

 	function buy_ajax()
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
            1=>'datetime',
            2=>'user_id',
            3=>'currency_name', 
            4=>'amount',
            5=>'Price',
            6=>'Fee',
            7=>'Total',
            8=>'status'
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
            $like = " AND (d.currency_symbol LIKE '%".$search."%' OR e.currency_symbol LIKE '%".$search."%' OR c.cpm_username LIKE '%".$search."%' OR a.amount LIKE '%".$search."%' OR a.Price LIKE '%".$search."%' OR a.Fee LIKE '%".$search."%' OR a.Total LIKE '%".$search."%' OR a.status LIKE '%".$search."%' OR d.coin_type LIKE '%".$search."%')";

$query = "SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status='active' AND  a.Type = 'buy'".$like." ORDER BY a.tradetime DESC LIMIT ".$start.",".$length;


$countquery = $this->db->query("SELECT a.*,d.coin_type,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status='active' AND a.Type = 'buy'".$like." ORDER BY a.tradetime DESC");



            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();


        }
        else
        {
        	$query = 'SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status="active" AND a.Type = "buy" ORDER BY `a`.`tradetime` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status="active" AND a.Type = "buy" ORDER BY `a`.`tradetime` DESC');


        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;				
				
					$data[] = array(
					    $i, 
					    date("d-m-Y h:i a", strtotime($result->datetime)),
						getUserDetails($result->userId,'cpm_username'),
						$result->from_currency_symbol.'-'.$result->to_currency_symbol,
						//gmdate("d-m-Y h:i:s", strtotime($result->datetime)),
						$result->Amount." ".$result->from_currency_symbol,
						$result->Price." ".$result->to_currency_symbol,
						$result->Fee." ".$result->to_currency_symbol,
						$result->Total." ".$result->to_currency_symbol,
                        $result->ordertype,
						ucfirst($result->status)
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

 	function sell_ajax()
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
            1=>'datetime',
            2=>'user_id',
            3=>'currency_name', 
            4=>'amount',
            5=>'Price',
            6=>'Fee',
            7=>'Total',
            8=>'Type',
            9=>'status'
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
            $like = " AND (d.currency_symbol LIKE '%".$search."%' OR e.currency_symbol LIKE '%".$search."%' OR c.cpm_username LIKE '%".$search."%' OR a.amount LIKE '%".$search."%' OR a.Price LIKE '%".$search."%' OR a.Fee LIKE '%".$search."%' OR a.Total LIKE '%".$search."%' OR a.status LIKE '%".$search."%' OR d.coin_type LIKE '%".$search."%')";

$query = "SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status='active' AND a.Type = 'sell'".$like." ORDER BY a.tradetime DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status='active' AND a.Type = 'sell'".$like." ORDER BY a.tradetime DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status="active" AND a.Type = "sell" ORDER BY `a`.`tradetime` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*,d.coin_type, b.from_symbol_id as from_currency_id, b.to_symbol_id as to_currency_id, c.cpm_username as username, d.currency_symbol as from_currency_symbol, e.currency_symbol as to_currency_symbol
FROM cpm_coin_order as a
JOIN cpm_trade_pairs as b ON a.pair = b.id
JOIN cpm_users as c ON a.userId = c.id
JOIN cpm_currency as d ON b.from_symbol_id = d.id
JOIN cpm_currency as e ON b.to_symbol_id = e.id
WHERE a.status="active" AND a.Type = "sell" ORDER BY `a`.`tradetime` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;				
				
					$data[] = array(
					    $i, 
					    date("d-m-Y h:i a", strtotime($result->datetime)),
						getUserDetails($result->userId,'cpm_username'),
						$result->from_currency_symbol.'-'.$result->to_currency_symbol,
						//gmdate("d-m-Y h:i:s", strtotime($result->datetime)),
						$result->Amount." ".$result->from_currency_symbol,
						$result->Price." ".$result->to_currency_symbol,
						$result->Fee." ".$result->to_currency_symbol,
						$result->Total." ".$result->to_currency_symbol,
                        $result->ordertype,
						ucfirst($result->status)
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

 	function buy() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}

	$data['view'] = 'buy';
	$data['title'] = 'Buy Trade Hold Orders';
	$data['meta_keywords'] = 'Buy Trade Hold Orders';
	$data['meta_description'] = 'Buy Trade Hold Orders';
	$data['main_content'] = 'trade_holdorders/trade_holdorders';
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
	$data['main_content'] = 'trade_holdorders/trade_holdorders';
	$this->load->view('administrator/admin_template', $data); 
	}

 }

