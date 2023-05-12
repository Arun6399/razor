<?php
/**
 * Users class
 * @package Spiegel Technologies
 * @subpackage zonexh
 * @category Controllers
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Users extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
 	public function block()
	{
		$cip = get_client_ip();
		$match_ip = $this->common_model->getTableData('page_handling',array('ip'=>$cip))->row();
		if($match_ip > 0)
		{
		return 1;
		}
		else
		{
		return 0;
		}
	}

	public function block_ip()
	{
		$this->load->view('front/common/blockips');
	}

    // UPDTAED BY MANIMEGS
	function users_ajax()
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
            1=>'cpm_username',
            2=>'cpm_email',
            3=>'created_on',
            4=>'randcode',
            5=>'verify_level2_status',
            6=>'verified',
            7=>'verified'
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
  //       if(!empty($search))
  //       { 
  //           $like = "AND (cpm_users.cpm_username LIKE '%".$search."%' OR cpm_users.cpm_email LIKE '%".$search."%' OR cpm_history.zonexh_type LIKE '%".$encrypt_search."%')"; 
  //       }
        
  //       $query = "SELECT * FROM `cpm_users` INNER JOIN `cpm_history` on `cpm_users`.id=`cpm_history`.user_id WHERE `cpm_users`.verified=1 ".$like." ORDER BY `created_on` DESC LIMIT ".$start.",".$length;

  //       $users_history = $this->db->query($query);
  //       $users_history_result = $users_history->result(); 
        
		// $num_rows = count($users_history_result);
		  if(!empty($search))
    { 
        $like = "AND (cpm_users.cpm_username LIKE '%".$search."%' OR cpm_users.cpm_email LIKE '%".$search."%' OR cpm_history.cpm_type LIKE '%".$encrypt_search."%')"; 
        $query = "SELECT * FROM `cpm_users` INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=1 ".$like." ORDER BY created_on DESC LIMIT ".$start.",".$length;
    $users_history = $this->db->query($query);
    $users_history_result = $users_history->result(); 

    $num_rows = count($users_history_result);
    }
    else
    {


        $query = "SELECT * FROM `cpm_users` WHERE cpm_users.verified=1 ORDER BY id ASC LIMIT ".$start.",".$length;

        $countquery = $this->db->query("SELECT * FROM `cpm_users` INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=1 ORDER BY cpm_users.id ASC");
        $users_history = $this->db->query($query);
        $users_history_result = $users_history->result(); 
        $num_rows = $countquery->num_rows();

    }
 

		if(count($users_history_result)>0)
		{


			foreach($users_history->result() as $users){

				
                  
				$i++;
				$email = getUserEmail($users->id);
				// $username = getUserName()

				if ($users->randcode == 'enable') 
				{
	                $tfa = '<label class="label label-info">Enabled</label>';
	            } else {
	                $tfa = '<label class="label label-danger">Disabled</label>';
	            }

	            if ($users->randcode == 'enable') 
	            {
	                $extra1 = array("data-placement"=>"top",'data-toggle'=>"popover","data-content"=>"De-activate TFA","class"=>"poper");
	                $tfa_Status = anchor(admin_url().'users/tfa_status/' . $users->id . '/disable','<i class="fa fa-unlock text-primary"></i>',$extra1);
	            } else {
	                $extra1 = array("data-placement"=>"top",'data-toggle'=>"popover","data-content"=>"Activate TFA","class"=>"poper");
	                $tfa_Status = anchor(admin_url().'users/tfa_status/' . $users->id . '/enable','<i class="fa fa-lock text-primary"></i>',$extra1);
	            }

	            if ($users->verified == 1) 
	            {
	                $status = '<label class="label label-info">Activated</label>';
	                $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-activate this user","class"=>"poper");
	                $changeStatus = anchor(admin_url().'users/status/' . $users->id . '/0','Deactive',$extra);
	            } else {
	                $status = '<label class="label label-danger">De-Activated</label>';
	                $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this user","class"=>"poper");
	                $changeStatus = anchor(admin_url().'users/status/' . $users->id . '/1','Activate',$extra);
	            }
	            if ($users->verify_level2_status == 'Completed') {
	                $kyc = '<label class="label label-info">Verified</label>';
	            }else if ($users->verify_level2_status == 'Rejected') {
	                $kyc = '<label class="label label-warning">Rejected</label>';
	            } else {
	                if(trim($users->photo_id_1) != '' && trim($users->photo_id_2) != '')
	                $kyc = '<label class="label label-primary">Pending</label>';
	                else
	                $kyc = '<label class="label label-danger">Unverified</label>';
	            }

				$data[] = array(
					    $i, 
						$users->cpm_username,
						$email,
						date('d-m-Y h:i a',$users->created_on),
						$users->user_type,
					
						$tfa_Status,
						$kyc,
						$status,
						// $changeStatus
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

	function unusers_ajax()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}

		$draw = $this->input->get('draw');
		// $start = intval($this->input->get("start"));
  //       $length = intval($this->input->get("length"));
		$start = 0;
		$length=10;
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
            1=>'cpm_username',
            2=>'cpm_email',
            3=>'created_on',            
            4=>'verified',
            5=>'verified'
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
  //       if(!empty($search))
  //       { 
  //           $like = "AND (cpm_users.cpm_username LIKE '%".$search."%' OR cpm_users.cpm_email LIKE '%".$search."%' OR cpm_history.cpm_type LIKE '%".$encrypt_search."%')"; 
  //       }
        
  //       $query = "SELECT * FROM `cpm_users` INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=0 ".$like." ORDER BY created_on DESC LIMIT ".$start.",".$length;
  //       $tt = $query;
  //       $users_history = $this->db->query($query);
  //       $users_history_result = $users_history->result(); 
        
		// $num_rows = count($users_history_result);
		if(!empty($search))
{
$like = "AND (cpm_users.cpm_username LIKE '%".$search."%' OR cpm_users.cpm_email LIKE '%".$search."%' OR cpm_history.cpm_type LIKE '%".$encrypt_search."%')";
$query = "SELECT *,cpm_users.cpm_username FROM cpm_users INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=0 ".$like." ORDER BY cpm_users.id DESC LIMIT ".$start.",".$length;
$countquery = $this->db->query("SELECT * FROM cpm_users INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=0 ".$like." ORDER BY cpm_users.id DESC");
$users_history = $this->db->query($query);
$users_history_result = $users_history->result();
$num_rows = $countquery->num_rows();
}
else
{
$query = "SELECT *,cpm_users.cpm_username FROM cpm_users INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=0 ".$like." ORDER BY cpm_users.id DESC LIMIT ".$start.",".$length;
$countquery = $this->db->query("SELECT * FROM cpm_users INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=0 ORDER BY cpm_users.id DESC");
$users_history = $this->db->query($query);
$users_history_result = $users_history->result();
$num_rows = $countquery->num_rows();
}


		if(count($users_history_result)>0)
		{
			foreach($users_history->result() as $users){
				
				$i++;
				$email = getUserEmail($users->id);
	            if ($users->verified == 1) 
	            {
	                $status = '<label class="label label-info">Activated</label>';
	                $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"De-activate this user","class"=>"poper");
	                $changeStatus = anchor(admin_url().'users/status/' . $users->id . '/0','Deactive',$extra);
	            } else {
	                $status = '<label class="label label-danger">De-Activated</label>';
	                $extra = array("data-placement"=>"bottom",'data-toggle'=>"popover","data-content"=>"Activate this user","class"=>"poper");
	                $changeStatus = anchor(admin_url().'users/status/' . $users->id . '/1','Activate',$extra);
	            }
	            

				$data[] = array(
					    $i, 
						$users->cpm_username,
						$email,
						date('d-m-Y h:i a',$users->created_on),
						$users->user_type,
						$status,
						$changeStatus
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

	function kyc_ajax()
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
            1=>'cpm_username',
            2=>'cpm_email',
            3=>'created_on',            
            4=>'verified',
            5=>'verified'
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
            $like = "AND (cpm_users.cpm_username LIKE '%".$search."%' OR cpm_users.cpm_email LIKE '%".$search."%' OR cpm_history.cpm_type LIKE '%".$encrypt_search."%')"; 
        }
        
        $query = "SELECT * FROM `cpm_users` INNER JOIN cpm_history on cpm_users.id=cpm_history.user_id WHERE cpm_users.verified=1 AND cpm_users.verify_level2_status!='' ".$like." ORDER BY cpm_users.verify_level2_date DESC LIMIT ".$start.",".$length;


        $tt = $query;
        $users_history = $this->db->query($query);
        $users_history_result = $users_history->result(); 
        
		$num_rows = count($users_history_result);

		if(count($users_history_result)>0)
		{
			foreach($users_history->result() as $users){
				$i++;
				$email = getUserEmail($users->id);
	            if($users->photo_1_status==3 && $users->photo_2_status==3 && $users->photo_3_status==3)
	            {
	                $level = '2';
	                $status = 'Level-'.$level.'&nbsp; &nbsp;&nbsp; &nbsp;'.'<label class="label label-info">Verified</label>';
	                
	            }
	            else
	            {
	                $level = '2';
	                if($users->photo_1_status==2 && $users->photo_2_status==2 && $users->photo_3_status==2)
	                {
	                    $status = 'Level-'.$level.'&nbsp; &nbsp;&nbsp; &nbsp;'.'<label class="label label-info">Rejected</label>';
	                    
	                }
	                else
	                {
	                  $status = 'Level-'.$level.'&nbsp; &nbsp;&nbsp; &nbsp;'.'<label class="label label-primary">Pending</label>'; 
	                }
	            }
	            $changeStatus = '<a href="' . admin_url() . 'users/verification_view/' . $users->id . '" data-placement="bottom" data-toggle="popover" data-content="Edit this user" class="poper"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';
	            

				$data[] = array(
					    $i, 
						$users->cpm_username,
						$email,
						date('d-m-Y h:i a',$users->created_on),
						$status,
						$changeStatus
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



 	// verified users
 	function index() 
 	{
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	// Page config
	$data['users'] = $this->common_model->getTableData('users',array('verified'=>1))->result();
	
	
   /*	$data['users'] = $this->common_model->getTableData('users','', '', '', '', '',$urisegment,$perpage,array('created_on', 'DESC'));*/
   	
   	
	   	$data['prefix'] = get_prefix();
		$data['view'] = 'list';
		$data['title'] = 'Users Management';
		$data['meta_keywords'] = 'Users Management';
		$data['meta_description'] = 'Users Management';
		$data['main_content'] = 'users/users';
		$this->load->view('administrator/admin_template', $data); 
	}


	//unverified users
	function unverified_users() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	// Page config
	$data['users'] = $this->common_model->getTableData('users',array('verified'=>0))->result();
	

	$data['prefix'] = get_prefix();
	$data['view'] = 'unverified';
	$data['title'] = 'Users Management';
	$data['meta_keywords'] = 'Users Management';
	$data['meta_description'] = 'Users Management';
	$data['main_content'] = 'users/users';
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
			admin_redirect('users');
		}
		$isValid = $this->common_model->getTableData('users', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('users');
		}
		// Form validation
		//$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
		//$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
		//$this->form_validation->set_rules('country', 'Country', 'required|xss_clean');
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$updateData['first_name'] = $this->input->post('first_name');
				$updateData['last_name'] = $this->input->post('last_name');	
				$updateData['country'] = $this->input->post('country');			
				$condition = array('id' => $id);
				// updated via Common model
				$update = $this->common_model->updateTableData('users', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'User details has been updated successfully!');
					admin_redirect('users', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update user details');
					admin_redirect('users/edit/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update this user');
				admin_redirect('users/edit/' . $id, 'refresh');
			}
			
		}
		$data['users'] = $isValid->row();
		$data['prefix'] = get_prefix();
		$data['action'] = admin_url() . 'users/edit/' . $id;
		$data['title'] = 'Edit User';
		$data['meta_keywords'] = 'Edit User';
		$data['meta_description'] = 'Edit User';
		$data['main_content'] = 'users/users';
		$data['view'] = 'edit';
		$data['countries'] = $this->common_model->getTableData('countries')->result();
		$data['bank_details'] = $this->common_model->getTableData('user_bank_details', array('user_id'=>$data['users']->id))->result();
		$this->load->view('administrator/admin_template', $data);
	}



	// Manage users 
	function verification() {
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Page config
		$data['users'] = $this->common_model->getTableData('users', array('verify_level2_status !='=>'','verified'=>1))->result();
		
		$data['prefix'] = get_prefix();
		$data['view'] = 'view_all';
		$data['title'] = 'Users Management';
		$data['meta_keywords'] = 'Users Management';
		$data['meta_description'] = 'Users Management';
		$data['main_content'] = 'users/users';
		$this->load->view('administrator/admin_template', $data); 
	}
	// User status change
	function status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users');
		}
		$isValid = $this->common_model->getTableData('users', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['verified'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'User activated successfully');
				} else {
					$this->session->set_flashdata('success', 'User de-activated successfully');
					$this->session->set_flashdata('useractivated', $id);
				}
				admin_redirect('users');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with user status updation');
				admin_redirect('users');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users');
		}
	}


	function tfa_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users');
		}
		$isValid = $this->common_model->getTableData('users', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['randcode'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 'enable') {
					$this->session->set_flashdata('success', 'User TFA activated successfully');
				} else {
					$this->session->set_flashdata('success', 'User TFA de-activated successfully');
					$this->session->set_flashdata('useractivated', $id);
				}
				admin_redirect('users');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with user status updation');
				admin_redirect('users');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users');
		}
	}
	// Edit page
		// Edit page
	function verification_view($id='')
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValid = $this->common_model->getTableData('users', array('id' => $id,'verify_level2_status !='=>'','verified'=>1));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('users/verification');
		}
		
		$data['prefix'] = get_prefix();
		$data['users'] = $isValid->row();
		$data['action'] = admin_url() . 'users/edit/' . $id;
		$data['title'] = 'Edit Users';
		$data['meta_keywords'] = 'Edit Users';
		$data['meta_description'] = 'Edit Users';
		$data['main_content'] = 'users/users';
		$data['view'] = 'verification_view';
		$this->load->view('administrator/admin_template', $data);
	}

	function verify_level2_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['verify_level2_status'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				if ($status == 'Completed') {
				$users = $isValids->row();
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Complete';
				$site_common      =   site_common();                
				$special_vars = array(
				'###USERNAME###' => $username,
				'###STATUS###' => $status,
				'###LEVEL###' => '2'				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User Level-2 verification successfully Completed');
				}
				admin_redirect('users');
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user Level-2 verification');
				admin_redirect('users/verification');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}


		function verify_level2_reject($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['verify_level2_status'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				if ($status == 'Rejected') {
				$users = $isValids->row();
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Reject';
					$special_vars = array(
					'###USERNAME###' => $username,
					'###STATUS###' => $status,
					'###LEVEL###' => '2',
					'###REASON###'   => $this->input->post('reject_mail_content'),
					);
				$this->email_model->sendMail($email, '', '', $email_template, $special_vars);
				$this->session->set_flashdata('success', 'User Level-2 verification successfully Rejected');
				}
				admin_redirect('users/verification');
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user Level-2 verification');
				admin_redirect('users/verification');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}

	function verify_photo1_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['photo_1_status'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				
				$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==3 && $user_det->photo_2_status==3 && $user_det->photo_3_status==3)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Completed";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);
			        //CHECK THE KYC STATUS
			        $kyc_status = $this->common_model->getTableData('users', array('id'=>$user_det->id,'photo_1_status'=>'3','photo_2_status'=>'3','photo_3_status'=>3))->row();
			        if($kyc_status)
			        {
			        	$update_kyc = $this->common_model->updateTableData('users', array('id'=>$user_det->id), array('kyc_status'=>1));
			        }
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Complete';
                $site_common      =   site_common();
               
				$special_vars = array(
				'###USERNAME###' => $username,
				'###STATUS###' => $status,
				'###LEVEL###' => 'Photo id 1'
				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User photo id 1 verification successfully Completed');
				
				admin_redirect('users/verification_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user photo id 1 verification');
				admin_redirect('users/verification_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}


		function verify_photo1_reject($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['photo_1_status'] = $status;
			$updateData['photo_1_reason'] = $this->input->post('reject_mail_content');
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==2 && $user_det->photo_2_status==2 && $user_det->photo_3_status==2)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Rejected";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Reject';
				$site_common      =   site_common();
                
				$special_vars = array(
				'###USERNAME###' => $username,
				'###LEVEL###' => 'Photo id 1',
				'###REASON###'   => $this->input->post('reject_mail_content')
				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User photo id 1 verification successfully Rejected');
				
				admin_redirect('users/verification_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user photo id 1 verification');
				admin_redirect('users/verification_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}

	function verify_photo2_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['photo_2_status'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				
				$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==3 && $user_det->photo_2_status==3 && $user_det->photo_3_status==3)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Completed";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);

			        //CHECK THE KYC STATUS
			        $kyc_status = $this->common_model->getTableData('users', array('id'=>$user_det->id,'photo_1_status'=>'3','photo_2_status'=>'3','photo_3_status'=>3))->row();
			        if($kyc_status)
			        {
			        	$update_kyc = $this->common_model->updateTableData('users', array('id'=>$user_det->id), array('kyc_status'=>1));
			        }
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Complete';
                $site_common      =   site_common();
                
				$special_vars = array(
				'###USERNAME###' => $username,
				'###STATUS###' => $status,
				'###LEVEL###' => 'Photo id 2'
				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User photo id 2 verification successfully Completed');
				
				admin_redirect('users/verification_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user photo id 2 verification');
				admin_redirect('users/verification_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}


		function verify_photo2_reject($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['photo_2_status'] = $status;
			$updateData['photo_2_reason'] = $this->input->post('reject_mail_content');
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==2 && $user_det->photo_2_status==2 && $user_det->photo_3_status==2)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Rejected";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Reject';
                $site_common      =   site_common();
               
				$special_vars = array(
				'###USERNAME###' => $username,
				'###LEVEL###' => 'Photo id 2',
				'###REASON###'   => $this->input->post('reject_mail_content')
				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User photo id 2 verification successfully Rejected');
				
				admin_redirect('users/verification_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user photo id 2 verification');
				admin_redirect('users/verification_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}

		function verify_photo3_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['photo_3_status'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				
				$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==3 && $user_det->photo_2_status==3 && $user_det->photo_3_status==3)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Completed";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);
			        //CHECK THE KYC STATUS
			        $kyc_status = $this->common_model->getTableData('users', array('id'=>$user_det->id,'photo_1_status'=>'3','photo_2_status'=>'3','photo_3_status'=>3))->row();
			        if($kyc_status)
			        {
			        	$update_kyc = $this->common_model->updateTableData('users', array('id'=>$user_det->id), array('kyc_status'=>1));
			        }
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Complete';
                $site_common      =   site_common();
               
				$special_vars = array(
				'###USERNAME###' => $username,
				'###STATUS###' => $status,
				'###LEVEL###' => 'Photo id 3'
				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User photo id 3 verification successfully Completed');
				
				admin_redirect('users/verification_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user photo id 3 verification');
				admin_redirect('users/verification_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}


		function verify_photo3_reject($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['photo_3_status'] = $status;
			$updateData['photo_3_reason'] = $this->input->post('reject_mail_content');
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==2 && $user_det->photo_2_status==2 && $user_det->photo_3_status==2)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Rejected";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Reject';
                $site_common      =   site_common();
                
				$special_vars = array(
				'###USERNAME###' => $username,
				'###LEVEL###' => 'Photo id 3',
				'###REASON###'   => $this->input->post('reject_mail_content')
				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User photo id 3 verification successfully Rejected');
				
				admin_redirect('users/verification_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user photo id 3 verification');
				admin_redirect('users/verification_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}

	function verify_level3_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		$users = $isValids->row();
		if($users->verify_level2_status!='Completed')
		{
			$this->session->set_flashdata('error', 'Please do the level-2 verification & then continue');
			admin_redirect('users/verification');
		}
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['verification_level'] = '3';
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				if ($status == 'Completed') {
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Complete';
				$site_common      =   site_common();
                
				$special_vars = array(
				'###USERNAME###' => $username,
				'###STATUS###' => $status,
				'###LEVEL###' => '3'
				
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);
				$this->session->set_flashdata('success', 'User Level-3 verification successfully Completed');
				}
				admin_redirect('users/verification');
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with user Level-3 verification');
				admin_redirect('users/verification');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/verification');
		}
	}

	function subscribe()
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) 
		{
			admin_redirect('admin', 'refresh');

		}
		$data['subscribe'] = $this->common_model->getTableData('reg_subscribe')->result();
		$data['action'] = admin_url() . 'admin/subscribe';
		$data['title'] = 'admin';
		$data['meta_keywords'] = 'admin';
		$data['meta_description'] = 'admin';
		$data['main_content'] = 'admin/subscribe';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data);
	}


	// Subscriber User status change
	function subscribe_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/subscribe');
		}
		$isValid = $this->common_model->getTableData('reg_subscribe', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('reg_subscribe', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Subscriber activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Subscriber de-activated successfully');
					$this->session->set_flashdata('useractivated', $id);
				}
				admin_redirect('users/subscribe');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with subscriber status updation');
				admin_redirect('users/subscribe');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this subscriber');
			admin_redirect('users/subscribe');
		}
	}


	 function payment() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	// Page config
	$users = $this->common_model->getTableData('users',array('verified'=>1))->result_array();
	
	if(isset($_GET['search_string']) && !empty($_GET['search_string'])){
			$search_string = $_GET['search_string'];
			$like = array("zonexh_username"=>$search_string);	
			$data['users'] = $this->common_model->getTableDatas('users','', '', $like, '', '','','',array('created_on', 'DESC'));
	}
	else {
   	$perpage =max_records();
  	$urisegment=$this->uri->segment(4);  
   	$base="users/payment";
   	$total_rows = count($users);
   	pageconfig($total_rows,$base,$perpage);
   	$data['users'] = $this->common_model->getTableData('users','', '', '', '', '',$urisegment,$perpage,array('created_on', 'DESC'));
   	}
   	
   	$data['prefix'] = get_prefix();
	$data['view'] = 'payment';
	$data['title'] = 'Payment Management';
	$data['meta_keywords'] = 'Payment Management';
	$data['meta_description'] = 'Payment Management';
	$data['main_content'] = 'users/users';
	$this->load->view('administrator/admin_template', $data); 
	}

	// shasta payment api is_secure flag status change
	function payment_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/payment');
		}
		$isValid = $this->common_model->getTableData('users', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['shasta_secure'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', '3D secure activated successfully for this user');
				} else {
					$this->session->set_flashdata('success', '3D secure de-activated successfully for this user');
				}
				admin_redirect('users/payment');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with 3D secure status updation');
				admin_redirect('users/payment');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/payment');
		}
	}

		//company_verification
	function company_verification() {
		// Is logged in
	$sessionvar=$this->session->userdata('loggeduser');
	if (!$sessionvar) {
		admin_redirect('admin', 'refresh');
	}
	// Page config
	$users = $this->common_model->getTableData('users',array('verified'=>1,'usertype'=>2))->result_array();
	
	if(isset($_GET['search_strings']) && !empty($_GET['search_strings'])){
			$search_string = $_GET['search_strings'];
			$like = array("zonexh_username"=>$search_string);	
			$data['users'] = $this->common_model->getTableDatas('users',array('verified'=>1,'usertype'=>2), '', $like, '', '','','',array('created_on', 'DESC'));
	}
	else {
   	$perpage =max_records();
  	$urisegment=$this->uri->segment(4);  
   	$base="users/company_verification";
   	$total_rows = count($users);
   	pageconfig($total_rows,$base,$perpage);
   	$data['users'] = $this->common_model->getTableData('users',array('verified'=>1,'usertype'=>2), '', '', '', '',$urisegment,$perpage,array('created_on', 'DESC'));
   	}

	$data['prefix'] = get_prefix();
	$data['view'] = 'company_verification';
	$data['title'] = 'Company Verification';
	$data['meta_keywords'] = 'Company Verification';
	$data['meta_description'] = 'Company Verification';
	$data['main_content'] = 'users/users';
	$this->load->view('administrator/admin_template', $data); 
	}


	function verify_company_status($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/company_verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['company_status'] = $status;
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				
				/*$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==3 && $user_det->photo_2_status==3 && $user_det->photo_3_status==3)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Completed";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Complete';
                $site_common      =   site_common();
                $fb_link = $site_common['site_settings']->facebooklink;
                $tw_link = $site_common['site_settings']->twitterlink;
                $tg_link = $site_common['site_settings']->telegramlink;
                $md_link = $site_common['site_settings']->mediumlink;
                $ld_link = $site_common['site_settings']->linkedinlink;
				$special_vars = array(
				'###USERNAME###' => $username,
				'###STATUS###' => $status,
				'###LEVEL###' => 'Photo id 1',
				'###FB###' => $fb_link,
				'###TW###' => $tw_link,
				'###TG###' => $tg_link,
				'###MD###' => $md_link,
				'###LD###' => $ld_link
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);*/
				$this->session->set_flashdata('success', 'Company Details verification successfully Completed');
				
				admin_redirect('users/company_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with Company Details verification verification');
				admin_redirect('users/company_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/company_verification');
		}
	}


		function verify_company_reject($id,$status) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Check is valid data 
		if ($id == '' || $status == '') { 
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/company_verification');
		}
		$isValids = $this->common_model->getTableData('users', array('id' => $id));
		$isValid = $isValids->num_rows();
		if ($isValid > 0) { 
			$condition = array('id' => $id);
			$updateData['company_status'] = $status;
			//$updateData['photo_1_reason'] = $this->input->post('reject_mail_content');
			$update = $this->common_model->updateTableData('users', $condition, $updateData);
			if ($update) { 
				/*$users = $isValids->row();
				$user_det = $this->common_model->getTableData('users', array('id' => $id))->row();
				if($user_det->photo_1_status==2 && $user_det->photo_2_status==2 && $user_det->photo_3_status==2)
				{
					$cond = array('id' => $user_det->id);
			        $udata['verify_level2_status'] = "Rejected";
			        $update = $this->common_model->updateTableData('users', $cond, $udata);
				}
				$prefix = get_prefix();
				$usernm = $prefix.'username';
				$username = $users->$usernm;
				$email = getUserEmail($users->id);
				// Email
				$email_template = 'Verification_Reject';
				$site_common      =   site_common();
                $fb_link = $site_common['site_settings']->facebooklink;
                $tw_link = $site_common['site_settings']->twitterlink;
                $tg_link = $site_common['site_settings']->telegramlink;
                $md_link = $site_common['site_settings']->mediumlink;
                $ld_link = $site_common['site_settings']->linkedinlink;
				$special_vars = array(
				'###USERNAME###' => $username,
				'###LEVEL###' => 'Photo id 1',
				'###REASON###'   => $this->input->post('reject_mail_content'),
				'###FB###' => $fb_link,
				'###TW###' => $tw_link,
				'###TG###' => $tg_link,
				'###MD###' => $md_link,
				'###LD###' => $ld_link
				);
				$this->email_model->sendMail($email, '', '', $email_template,$special_vars);*/
				$this->session->set_flashdata('success', 'Company Details verification successfully Rejected');
				
				admin_redirect('users/company_view/'.$id);
			} else { 
				$this->session->set_flashdata('error', 'Problem occure with Company Details verification');
				admin_redirect('users/company_view/'.$id);	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this user');
			admin_redirect('users/company_verification');
		}
	}


	function company_view($id)
	{
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('users/company_verification');
		}
		$isValid = $this->common_model->getTableData('users', array('id' => $id,'verified'=>1));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('users/company_verification');
		}
		
		$data['prefix'] = get_prefix();
		$data['users'] = $isValid->row();
		$data['action'] = admin_url() . 'users/company_view/' . $id;
		$data['title'] = 'Company Details';
		$data['meta_keywords'] = 'Company Details';
		$data['meta_description'] = 'Company Details';
		$data['main_content'] = 'users/users';
		$data['view'] = 'company_view';
		$this->load->view('administrator/admin_template', $data);
	}

	


 }
 
 /**
 * End of the file users.php
 * Location: ./application/controllers/ulawulo/users.php
 */	
