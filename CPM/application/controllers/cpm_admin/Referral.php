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
 class Referral extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

    /* referral bonus */
    function referral_ajax()
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
            1=>'cpm_email',
            2=>'referer',
            3=>'signup_bonus',
            4=>'referral_bonus',
            5=>'status',
            6=>'status'
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
        $like = "AND (wallet_txid LIKE '%".$search."%')"; 
        $query = "SELECT * FROM `cpm_transaction_history` ".$like." ORDER BY id DESC LIMIT ".$start.",".$length;
    $users_history = $this->db->query($query);
    $users_history_result = $users_history->result(); 

    $num_rows = count($users_history_result);
    }
    else
    {


        $query = "SELECT * FROM `cpm_transaction_history` WHERE type='Referral' ORDER BY id DESC LIMIT ".$start.",".$length;

        $countquery = $this->db->query("SELECT * FROM `cpm_transaction_history` WHERE type='Referral'");
        $users_history = $this->db->query($query);
        $users_history_result = $users_history->result(); 
        $num_rows = $countquery->num_rows();

    }

		if(count($users_history_result)>0)
		{
			foreach($users_history->result() as $users){
				$i++;
                $email = getUserEmail($users->userId);
				
                
                $userrecs = $this->common_model->getTableData('users',array('id'=>$users->userId))->row();
                $referral_recs = $this->common_model->getTableData('users',array('parent_referralid'=>$userrecs->referralid))->row();
                $ref_mail = getUserEmail($referral_recs->id);


				$data[] = array(
					    $i, 
						$ref_mail,
                        $email, 	 			
						$users->amount,
						$users->wallet_txid, 
                        $users->datetime, 			
						
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

		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		

        $data['referral_history'] = $this->common_model->getTableData('transaction_history',array('type'=>'Referral'),'','','','','','',array('id','DESC'))->result();  
		// Get the list pages
		$data['users'] = $this->common_model->getTableData('users', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Referral Commission Management';
		$data['meta_keywords'] = 'Referral Commission Management';
		$data['meta_description'] = 'Referral Commission Management';
		$data['main_content'] = 'bonus/referral';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}

}