<?php
/**
 * Support class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Support extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
 	}
 	function support_ajax()
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
        if($search=='Not Replied' || $search=='not replied')
        {
        	$st = 1;
        }
        elseif($search=='Replied' || $search=='replied')
        {
        	$st = 0;
        }
        else
        {
        	$st = $search;
        }
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
            1=>'created_on',
            2=>'fname',
            3=>'subject',
            4=>'status',
            5=>'status'
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
            $like = " AND c.bidex_fname LIKE '%".$search."%' OR a.subject LIKE '%".$search."%' OR a.status LIKE '%".$st."%'";

$query = "SELECT a.*, c.bidex_fname as fname FROM bidex_support as a JOIN bidex_users as c ON a.user_id = c.id WHERE parent_id = 0".$like." ORDER BY a.id DESC LIMIT ".$start.",".$length;

$countquery = $this->db->query("SELECT a.*, c.bidex_fname as fname FROM bidex_support as a JOIN bidex_users as c ON a.user_id = c.id WHERE parent_id = 0".$like." ORDER BY id DESC");

            $users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();
        }
        else
        {
        	$query = 'SELECT a.*, c.bidex_fname as fname FROM bidex_support as a JOIN bidex_users as c ON a.user_id = c.id WHERE parent_id = 0 ORDER BY `id` DESC LIMIT '.$start.','.$length;

        	$countquery = $this->db->query('SELECT a.*, c.bidex_fname as fname FROM bidex_support as a JOIN bidex_users as c ON a.user_id = c.id WHERE parent_id = 0 ORDER BY `id` DESC');
        	$users_history = $this->db->query($query);
            $users_history_result = $users_history->result(); 
            $num_rows = $countquery->num_rows();            
        }
        $tt = $query;
		if($num_rows>0)
		{
			foreach($users_history->result() as $result){
				$i++;
				

				$status = ($result->status=='0')?'Replied':'Not Replied';	
				$edit = '<a data-placement="top" data-toggle="popover" data-content="Reply to this support mail." class="poper" href="' . admin_url() . 'support/reply/' . $result->id . '"><i class="fa fa-pencil text-primary"></i></a>&nbsp;&nbsp;&nbsp;';	
				
					$data[] = array(
					    $i, 
						gmdate("d-m-Y h:i a", $result->created_on),
						$result->fname,
						$result->subject,
						$status,
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
		$data['prefix'] = get_prefix();

		$data['title'] = 'Support Management';
		$data['meta_keywords'] = 'Support Management';
		$data['meta_description'] = 'Support Management';
		$data['main_content'] = 'support/support';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Reply page
	function reply($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		// Is valid
		if ($id == '') {
			$this->session->set_flashdata('error', 'Invalid request');
			admin_redirect('support');
		}
		$isValid = $this->common_model->getTableData('support', array('id' => $id));
		$ticket_det = $isValid->row();
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('support');
		}
		// Form validation
		$this->form_validation->set_rules('message', 'Message', 'required|xss_clean');
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$image = $_FILES['image']['name'];
				if($image!="")
				{							
					$uploadimage1=cdn_file_upload($_FILES["image"],'uploads/user/'.$isValid->row('user_id'));
					if($uploadimage1)
					{
						//$image=$uploadimage1['secure_url'];
						if(is_array($uploadimage1)) {
							$image=$uploadimage1['secure_url'];
						} else {
							$errorMsg = current( (Array)$uploadimage1 );
							$this->session->set_flashdata('error', $errorMsg);
							admin_redirect('support/reply/' . $id, 'refresh');
						}
					}
					else
					{
						$this->session->set_flashdata('error', 'Problem with support image');
						front_redirect('support/reply/'.$id, 'refresh');
					}
					$image=$image;	
				} 
				else 
				{ 
					$image = "";
				}
				$insertsData['status'] = '0';
				$updates = $this->common_model->updateTableData('support',array('id'=>$id),$insertsData);
				if($updates) {
					$updateData=array();
					$updateData['parent_id'] = $id;
					$updateData['image'] = $image;
					$updateData['message'] = $this->input->post('message');
					$updateData['created_on'] = gmdate(time());
					$updateData['status'] = '0';			
					// updated via Common model
					$update = $this->common_model->insertTableData('support', $updateData);
					if ($update) {

						$email_template_user   	= 'Support_reply';
						$site_common      	=   site_common();
		                $fb_link 		  	= $site_common['site_settings']->facebooklink;
		                $tw_link 		  	= $site_common['site_settings']->twitterlink;
		                $tg_link 			= $site_common['site_settings']->telegramlink;
		                $yt_link 			= $site_common['site_settings']->youtube_link;
		                $ld_link 			= $site_common['site_settings']->linkedin_link;

		                $usermail = getUserEmail($ticket_det->user_id);
		                $username = getUserDetails($ticket_det->user_id,'bidex_username');
		                $message = $this->input->post('message');
				
						$special_vars_user 		= array(
								'###SITELINK###' 		=> front_url(),
								'###SITENAME###' 		=> $site_common['site_settings']->site_name,
								'###USERNAME###' 		=> $username,
								'###LINK###' 			=> base_url().'support_reply/'.$ticket_det->ticket_id,
								'###MESSAGE###'  		=> "<span style='color: #500050;'>".$message . "</spna><br>",
								'###FACEBOOKLINK###'    => $fb_link,
								'###TWITTERLINK###'     => $tw_link,
								'###TELEGRAMLINK###'    => $tg_link,
								'###YOUTUBELINK###'      => $yt_link,
								'###LINKEDINLINK###'    => $ld_link
						);
						$this->email_model->sendMail($usermail, '', '', $email_template_user, $special_vars_user);

						$this->session->set_flashdata('success', 'Replied to the contact support');
						admin_redirect('support', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to reply this support');
						admin_redirect('support/reply/' . $id, 'refresh');
					}
				}
				else{
					$this->session->set_flashdata('error', 'Unable to reply this support');
					admin_redirect('support/reply/' . $id, 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', 'Unable to reply this support');
				admin_redirect('support/reply/' . $id, 'refresh');
			}
			
		}
		$data['prefix'] = get_prefix();
		$data['support'] = $isValid->row();
		$data['replies'] = $this->common_model->getTableData('support', array('parent_id' => $id))->result();
		$data['action'] = admin_url() . 'support/reply/' . $id;
		$data['title'] = 'Reply Support';
		$data['meta_keywords'] = 'Reply Support';
		$data['meta_description'] = 'Reply Support';
		$data['main_content'] = 'support/support';
		$data['view'] = 'reply';
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
			admin_redirect('cms');
		}
		$isValid = $this->common_model->getTableData('cms', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('cms', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'CMS activated successfully');
				} else {
					$this->session->set_flashdata('success', 'CMS de-activated successfully');
				}
				admin_redirect('cms');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occure with cms status updation');
				admin_redirect('cms');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this cms');
			admin_redirect('cms');
		}
	}	
 }