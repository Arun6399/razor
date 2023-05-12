<?php
/**
 * Pair class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Token extends CI_Controller {
 	public function __construct() {
 		parent::__construct();
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('url', 'language', 'text'));
		
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
			admin_redirect('email_template');
		}
		$isValid = $this->common_model->getTableData('token_page', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('cms');
		}
		// Form validation
		$this->form_validation->set_rules('sec1_title', 'Title', 'required');

		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
						// print_r($this->input->post('template')); die;

				$updateData=array();

				    $image1 = $_FILES['sec3_image1']['name'];
						if($image1!="") {							
								$uploadimage1=cdn_file_upload($_FILES["sec3_image1"],'uploads/token',$isValid->row('sec3_image1'));
								if($uploadimage1)
								{
									$image1=$uploadimage1['secure_url'];
								}
								else
								{
									$this->session->set_flashdata('error','Problem with your token image');
									admin_redirect('token/edit/' . $id, 'refresh');
								}
						} else {
							$image1 = $this->input->post('oldimage1');
						}

					$image2 = $_FILES['sec3_image2']['name'];
						if($image2!="") {							
								$uploadimage2=cdn_file_upload($_FILES["sec3_image2"],'uploads/token',$isValid->row('sec3_image2'));
								if($uploadimage2)
								{
									$image2=$uploadimage2['secure_url'];
								}
								else
								{
									$this->session->set_flashdata('error','Problem with your token image');
									admin_redirect('token/edit/' . $id, 'refresh');
								}
						} else {
							$image2 = $this->input->post('oldimage2');
						}


					$image3 = $_FILES['sec3_image3']['name'];
						if($image3!="") {							
								$uploadimage3=cdn_file_upload($_FILES["sec3_image3"],'uploads/token',$isValid->row('sec3_image3'));
								if($uploadimage3)
								{
									$image3=$uploadimage3['secure_url'];
								}
								else
								{
									$this->session->set_flashdata('error','Problem with your token image');
									admin_redirect('token/edit/' . $id, 'refresh');
								}
						} else {
							$image3 = $this->input->post('oldimage3');
						}


					$image4 = $_FILES['sec3_image4']['name'];
						if($image4!="") {							
								$uploadimage4=cdn_file_upload($_FILES["sec3_image4"],'uploads/token',$isValid->row('sec3_image4'));
								if($uploadimage4)
								{
									$image4=$uploadimage4['secure_url'];
								}
								else
								{
									$this->session->set_flashdata('error','Problem with your token image');
									admin_redirect('token/edit/' . $id, 'refresh');
								}
						} else {
							$image4 = $this->input->post('oldimage4');
						}

                    $lablename1 = implode(",",$_POST['lable_name1']);
                    $lablevalue1 = implode(",",$_POST['lable_value1']);
                   
                    $lablename2 = implode(",",$_POST['lable_name2']);
                    $lablevalue2 = implode(",",$_POST['lable_value2']);


					$updateData['sec1_title'] = $this->input->post('sec1_title');
					$updateData['sec1_sub_title'] = $this->input->post('sec1_sub_title');
					$updateData['sec1_description1'] = $_POST['sec1_description1'];
					$updateData['sec1_description2'] = $_POST['sec1_description2'];
					$updateData['sec1_description3'] = $_POST['sec1_description3'];
					$updateData['sec1_description4'] = $_POST['sec1_description4'];
					$updateData['sec2_title'] = $this->input->post('sec2_title');
					$updateData['sec2_sub_title'] = $this->input->post('sec2_sub_title');
					$updateData['sec2_description1'] = $_POST['sec2_description1'];
					$updateData['sec3_title'] = $this->input->post('sec3_title');
					$updateData['sec3_sub_title'] = $this->input->post('sec3_sub_title');
					$updateData['sec3_description1'] = $this->input->post('sec3_description1');
					$updateData['sec3_description2'] = $this->input->post('sec3_description2');
					$updateData['sec3_description3'] = $this->input->post('sec3_description3');
					$updateData['sec3_image1'] = $image1;
					$updateData['sec3_image2'] = $image2;
                    $updateData['sec3_image3'] = $image3;
                    $updateData['sec3_image4'] = $image4;
                    $updateData['chart1_title'] = $this->input->post('chart1_title');
                    $updateData['chart2_title'] = $this->input->post('chart2_title');

                    $updateData['lable_name1'] = $lablename1;
                    $updateData['lable_value1'] = $lablevalue1;
                    $updateData['lable_name2'] = $lablename2;
                    $updateData['lable_value2'] = $lablevalue2;
                    
                    $updateData['sec2_btn_text'] = $this->input->post('sec2_btn_text');
					$updateData['created'] = gmdate(time());
							
					$condition = array('id' => $id);
					// updated via Common model
					$update = $this->common_model->updateTableData('token_page', $condition, $updateData);
					if ($update) {
						$this->session->set_flashdata('success', 'Token page has been updated successfully!');
						admin_redirect('token/edit/'.$id, 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to update token page');
						admin_redirect('token/edit/'.$id, 'refresh');
					}
				
			}
			else {
				$this->session->set_flashdata('error', 'Unable to update token page');
				admin_redirect('token/edit/' . $id, 'refresh');
			}
			
		}
		$data['token'] = $isValid->row();
		//$data['languages'] = $this->common_model->getTableData('languages',array('status'=>1),'id,name')->result();
		$data['action'] = admin_url() . 'token/edit/' . $id;
		$data['title'] = 'Edit Token Page';
		$data['meta_keywords'] = 'Edit Token Page';
		$data['meta_description'] = 'Edit Token Page';
		$data['main_content'] = 'token/token';
		$data['view'] = 'edit';
		$this->load->view('administrator/admin_template', $data);
	}	
 }