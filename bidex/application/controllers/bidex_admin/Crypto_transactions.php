<?php
/**
 * Banners class
 * @package Spiegel Technologies
 * @subpackage ixtokens
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Crypto_transactions extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
 	}
 	function index() {

		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// $data['users'] = $this->common_model->getTableData('transactions','',get_prefix().'username,id,verified', '', '', '', '', '', array('created_on', 'DESC'))->result();
		
		$data['title'] = 'Wallet Management';
		$data['meta_keywords'] = 'Wallet Management';
		$data['meta_description'] = 'Wallet Management';
		$data['main_content'] = 'transactions/crypto_transactions';
		$data['view']='view_all';
		$this->load->view('administrator/admin_template',$data); 
	}

	


	

 	function deposit_ajaxcrypto()
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
            1=>'user_id',
            // 2=>'transaction_id',
            3=>'datetime',            
            4=>'currency_name',
            5=>'amount',
            //6=>'transfer_amount',
            7=>'Type',
            8=>'status',
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
            $like = " AND (b.currency_symbol LIKE '%".$search."%' OR c.bidex_email LIKE '%".$search."%' OR a.transaction_id LIKE '%".$search."%' OR a.amount LIKE '%".$search."%')";

$query = "SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_email as email FROM bidex_admin_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Deposit' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC LIMIT ".$start.",".$length;


$countquery = $this->db->query("SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign, c.bidex_email as email FROM bidex_admin_transactions as a JOIN bidex_currency as b ON a.currency_id=b.id JOIN bidex_users as c ON a.user_id=c.id WHERE a.type='Deposit' AND a.currency_type='crypto'".$like." ORDER BY a.trans_id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign FROM `bidex_admin_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id` WHERE `a`.`user_id`=1  ORDER BY `a`.`trans_id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign FROM `bidex_admin_transactions` as `a` JOIN `bidex_currency` as `b` ON `a`.`currency_id`=`b`.`id` WHERE `a`.`user_id`=1 ORDER BY `a`.`trans_id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$enc_email = getAdminDetails('1','email_id');
					$adminmail = decryptIt($enc_email);
				$i++;

				if($result->transaction_id =='')
	            {
	              $transaction_id = '-';
	            }
	            else
	            {
	             $transaction_id = $result->transaction_id;
	            }
	            

	            $edit = '<a href="' . admin_url() . 'crypto_transactions/view/' . $result->trans_id . '" data-placement="top" data-toggle="popover" data-content="View the Deposit details." class="poper"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';				
				
					$data[] = array(
					    $i, 
					    $result->crypto_address,
						// decryptIt($enc_email),
						// $transaction_id,
						date("d-m-Y h:i a", $result->datetime),
						$result->currency_symbol,
						// number_format($result->amount,2),
						$result->amount,
						$result->type,
						$result->status,
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



	function view($id){
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$hisjoins = array('currency as b'=>'a.currency_id = b.id');
		$hiswhere = array('a.trans_id'=>$id,'a.user_id'=>1);
		$data['deposit'] = $this->common_model->getJoinedTableData('admin_transactions as a',$hisjoins,$hiswhere,'a.*,b.currency_symbol as currency_symbol, b.currency_name as currency_name, b.currency_sign as currency_sign','','','','','',array('a.trans_id', 'DESC'))->row();
		// echo $this->db->last_query();
		// exit();
		//$data['user_bankdetails'] = $this->common_model->getTableData('user_bank_details', array('id'=>$data['deposit']->bank_id,'user_id'=>$data['deposit']->user_id))->row();
		// $data['admin_bankdetails'] = $this->common_model->getTableData('admin_bank_details', array('id'=>$data['deposit']->bank_id))->row();
		//echo $this->db->last_query();
		// echo '<pre>'; print_r($data['deposit']); die;

		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'deposit/view/' . $id;
		
		$data['title'] = 'Deposit Management';
		$data['meta_keywords'] = 'Deposit Management';
		$data['meta_description'] = 'Deposit Management';
		$data['main_content'] = 'transactions/crypto_transactions';
		$data['view'] = 'view';
		$this->load->view('administrator/admin_template', $data); 
	}



















 }