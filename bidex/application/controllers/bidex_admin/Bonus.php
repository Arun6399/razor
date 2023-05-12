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
 class Bonus extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}

 	function bonus_ajax()
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
            1=>'bidex_username',
            2=>'bidex_email',
            3=>'referer',
            4=>'signup_bonus',
            5=>'referral_bonus',
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
        $like = "AND (bidex_users.bidex_username LIKE '%".$search."%' OR bidex_users.bidex_email LIKE '%".$search."%' OR bidex_history.bidex_type LIKE '%".$encrypt_search."%')"; 
        $query = "SELECT * FROM `bidex_users` INNER JOIN bidex_history on bidex_users.id=bidex_history.user_id WHERE bidex_users.verified=1 ".$like." ORDER BY created_on DESC LIMIT ".$start.",".$length;
    $users_history = $this->db->query($query);
    $users_history_result = $users_history->result(); 

    $num_rows = count($users_history_result);
    }
    else
    {


        $query = "SELECT * FROM `bidex_users` WHERE bidex_users.verified=1 ORDER BY id ASC LIMIT ".$start.",".$length;

        $countquery = $this->db->query("SELECT * FROM `bidex_users` INNER JOIN bidex_history on bidex_users.id=bidex_history.user_id WHERE bidex_users.verified=1 ORDER BY bidex_users.id ASC");
        $users_history = $this->db->query($query);
        $users_history_result = $users_history->result(); 
        $num_rows = $countquery->num_rows();

    }

		if(count($users_history_result)>0)
		{
			foreach($users_history->result() as $users){
				$i++;
                $email = getUserEmail($users->id);
				if ($users->verified == 1) {
                            $status = '<label class="label label-info">Activated</label>';
                            
                            
                        } else {
                            $status = '<label class="label label-danger">De-Activated</label>';
                            
                           
                        }

                    $edit = '<a href="' . admin_url() . 'bonus/view/' . $users->id . '" data-placement="top" data-toggle="popover" data-content="View this Template" class="poper"><i class="fa fa-eye text-primary"></i></a>&nbsp;&nbsp;&nbsp;';                
                    
                



				$data[] = array(
					    $i, 
						$users->bidex_username,
						$email,						
						$users->smd_balance,
						20 - $users->smd_balance,
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


	// list
	function index() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		
		// Get the list pages
		$data['users'] = $this->common_model->getTableData('users', '', '', '', '', '', '', '', array('id', 'DESC'));
		$data['title'] = 'Bonus Management';
		$data['meta_keywords'] = 'Bonus Management';
		$data['meta_description'] = 'Bonus Management';
		$data['main_content'] = 'bonus/bonus';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
    function view($id) {
        // Is logged in
        $sessionvar=$this->session->userdata('loggeduser');
        if (!$sessionvar) {
            admin_redirect('admin', 'refresh');
        }
        // Is valid
        if ($id == '') {
            $this->session->set_flashdata('error', 'Invalid request');
            admin_redirect('bonus');
        }
        $isValid = $this->common_model->getTableData('users', array('id' => $id));
        // $isValid1 = $this->common_model->getTableData('referral_commission', array('user_id' => $id,'status'=>'1'));
        // $isValid2 = $this->common_model->getTableData('referral_commission', array('user_id' => $id,'status'=>'0'));
        $data['usage_count']= $this->common_model->customQuery("SELECT COUNT(status) AS total FROM `bidex_referral_commission` WHERE user_id = $id AND status = '1' ")->row();
        $data['remaining_count']=$this->common_model->customQuery("SELECT COUNT(status) AS total FROM `bidex_referral_commission` WHERE user_id = $id AND status = '0' ")->row();
        if ($isValid->num_rows() == 0) {
            $this->session->set_flashdata('error', 'Unable to find this page');
            admin_redirect('bonus');
        }
        $data['bonus'] = $isValid->row();
        // $data['bonus_commission'] = $isValid1->row();
        // $data['bonus_commission'] = $isValid2->row();
        $data['emailConfig'] = $this->db->where('id', 1)->get('site_settings')->row();
        // print_r($data['email_template']); die;
        $data['action'] = admin_url() . 'bonus/view/' . $id;
        $data['title'] = 'View Bonus List';
        $data['meta_keywords'] = 'View Bonus List';
        $data['meta_description'] = 'View Referral Bonus List';
        $data['main_content'] = 'bonus/bonus';
        $data['view'] = 'view';
        $this->load->view('administrator/admin_template', $data);
    }
}