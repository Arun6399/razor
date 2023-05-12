<?php
/**
 * Language class
 * @package Spiegel Technologies
 * @subpackage smdex
 * @author Pilaventhiran
 * @version 1.0
 * @link http://spiegeltechnologies.com/
 * 
 */
 class Language extends CI_Controller {
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
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$data['languages'] = $this->common_model->getTableData('languages');
		$data['title'] = 'Language Management';
		$data['meta_keywords'] = 'Language Management';
		$data['meta_description'] = 'Language Management';
		$data['main_content'] = 'language/languages';
		$data['view'] = 'view_all';
		$this->load->view('administrator/admin_template', $data); 
	}
	// Add
	function add() {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$this->form_validation->set_rules('name', 'Language Name', 'required|xss_clean');
		$this->form_validation->set_rules('code', 'Language Code', 'required|xss_clean');
		$this->form_validation->set_rules('status', 'Language Status', 'required|xss_clean');	
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$insertData=array();
				$name=$this->input->post('name');
				$code=$this->input->post('code');
				$status=$this->input->post('status');
				$language_details=$this->common_model->getTableData('languages', array('name'=>$name));
				if($language_details->num_rows()==0)
				{
					$seourl=seoUrl($name);
					$insertData['name'] = $name;
					$insertData['seo_url'] = $seourl;
					$insertData['status'] = $this->input->post('status');
					$insertData['created'] = gmdate(time());
					$insert = $this->common_model->insertTableData('languages', $insertData);
					$folder = APPPATH.'language/'.$seourl;
					mkdir($folder, 0777);




					$lang=$this->get_file_as_arr('english');
					$res = $this->writeConfig2($folder."/site_common_lang.php",$lang,$code);
                    //vv

                    $lan = $this->get_file_as_array('english');
                    $langfile = $this->writeConfig($folder."/form_validation_lang.php",$lan);


					//var_dump($res);exit;
					if ($insert) {
						$language=$this->common_model->getTableData('languages','','id','','','','','',array('id','asc'))->row('id');
						$static_content=$this->common_model->getTableData('static_content', array('language'=>$language))->result_array();
						/*foreach($static_content as $stc){
							unset($stc['id']);
							$stc['language']  = $insert;
             			$this->common_model->insertTableData('static_content', $stc);
						}*/
						////vv
						foreach($static_content as $stc){
						    $trans =translate('en',$code,$stc['content']);
                        	$transtitle =translate('en',$code,$stc['title']);
							unset($stc['id']);
							$stc['language']  = $insert;
						if($trans)
						{
							$stc['content']=$trans;
							
					    }
					    if($transtitle){
					    	$stc['title']=$transtitle;
					    	
					    }
             		  
             		       	$this->common_model->insertTableData('static_content', $stc);
             		       	/*echo "<pre>";
							print_r($stc);die;*/

         		          		    	
             		}

						$email_template=$this->common_model->getTableData('email_template', array('language'=>$language))->result_array();

						foreach($email_template as $stc){
							$transname = translate('en',$code,$stc['name']);
							$transsubject = translate('en',$code,$stc['subject']);
							unset($stc['id']);
							$stc['language']  = $insert;
						 if($transname){
						 	$stc['name'] = $transname;
						 }
						 if($transsubject){
						    $stc['subject'] = $transsubject;
						 }

             			    $this->common_model->insertTableData('email_template', $stc);
						}

						/*foreach($email_template as $stc){
							unset($stc['id']);
							$stc['language']  = $insert;
             			$this->common_model->insertTableData('email_template', $stc);
						}*/


						$cms=$this->common_model->getTableData('cms', array('language'=>$language))->result_array();

						foreach($cms as $stc){
							$transheading = translate('en',$code, $stc['heading']);
							$transtitle = translate('en',$code, $stc['title']);
							$transmetakey = translate('en',$code, $stc['meta_keywords']);
							$transmetadesc = translate('en',$code, $stc['meta_description']);
							$transcontentdesc = translate('en',$code, $stc['content_description']);
							unset($stc['id']);
							$stc['language']  = $insert;
						if($transheading){
							$stc['heading'] = $transheading;
							}
						if($transtitle){
							$stc['title'] = $transtitle;
							}
						if($transmetakey){
							$stc['meta_keywords'] = $transmetakey;
							}
						if($transmetadesc){
							$stc['meta_description'] = $transmetadesc;
							}
						if($transcontentdesc){
							$stc['content_description'] = $transcontentdesc;
							}
             			$this->common_model->insertTableData('cms', $stc);
						}


						/*foreach($cms as $stc){
							unset($stc['id']);
							$stc['language']  = $insert;
             			$this->common_model->insertTableData('cms', $stc);
						}*/

						$this->session->set_flashdata('success', 'Language has been added successfully!');
						admin_redirect('language', 'refresh');
					} else {
						$this->session->set_flashdata('error', 'Unable to add the language !');
						admin_redirect('language/add', 'refresh');
					}
				}
				else
				{
					$this->session->set_flashdata('error', 'Unable to add the language !');
					admin_redirect('language/add', 'refresh');
				}
			}
			else {
				$this->session->set_flashdata('error', validation_errors());
				admin_redirect('language/add', 'refresh');
			}
			
		}
		$data['action'] = admin_url() . 'language/add';
		$data['title'] = 'Add Language';
		$data['meta_keywords'] = 'Add Language';
		$data['meta_description'] = 'Add Language';
		$data['main_content'] = 'language/languages';
		$data['view'] = 'add';
		$this->load->view('administrator/admin_template', $data);
	}
	function edit_file($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$isValid = $this->common_model->getTableData('languages', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('language');
		}
		if ($this->input->post())
		{
			$seourl=$isValid->row('seo_url');
			$folder = APPPATH.'language/'.$seourl;
			$res = $this->writeConfig1($folder."/site_common_lang.php",array_unique($this->input->post()));
			if ($res) {
				$this->session->set_flashdata('success', 'Language File has been updated successfully!');
				admin_redirect('language', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Unable to add the language file!');
				admin_redirect('language/edit_file/'.$id, 'refresh');
			}
		}
		$data['lang_input']=$this->get_file_as_arr($isValid->row('seo_url'));
		$data['action'] = admin_url() . 'language/edit_file/'.$id;
		$data['title'] = 'Edit Language File';
		$data['meta_keywords'] = 'Edit Language File';
		$data['meta_description'] = 'Edit Language File';
		$data['main_content'] = 'language/languages';
		$data['view'] = 'edit_file';
		$this->load->view('administrator/admin_template', $data);
	}
	//vv
	function edit_formvalid_file($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$isValid = $this->common_model->getTableData('languages', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('language');
		}
		if ($this->input->post())
		{
			$seourl=$isValid->row('seo_url');
			$folder = APPPATH.'language/'.$seourl;
			$res = $this->writeConfig($folder."/form_validation_lang.php",$this->input->post());
			if ($res) {
				$this->session->set_flashdata('success', 'Language File has been updated successfully!');
				admin_redirect('language', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Unable to add the language file!');
				admin_redirect('language/edit_formvalid_file/'.$id, 'refresh');
			}
		}
		$data['lang_input']=$this->get_file_as_array($isValid->row('seo_url'));
		$data['action'] = admin_url() . 'language/edit_formvalid_file/'.$id;
		$data['title'] = 'Edit Language File';
		$data['meta_keywords'] = 'Edit Language File';
		$data['meta_description'] = 'Edit Language File';
		$data['main_content'] = 'language/languages';
		$data['view'] = 'edit_formvalid_file';
		$this->load->view('administrator/admin_template', $data);
	}
	//vv
	function add_file($id) {
		// Is logged in
		$sessionvar=$this->session->userdata('loggeduser');
		if (!$sessionvar) {
			admin_redirect('admin', 'refresh');
		}
		$isValid = $this->common_model->getTableData('languages', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('language');
		}
		if ($this->input->post())
		{
			$key=$this->input->post('key');
			$value=$this->input->post('value');
			$seourl=$isValid->row('seo_url');
			$folder = APPPATH.'language/'.$seourl;
			$lang_input=$this->get_file_as_arr($seourl);
			if(isset($lang_input[$key]))
			{
				$this->session->set_flashdata('error', 'Key Already Exists!');
				admin_redirect('language/add_file/'.$id, 'refresh');
			}
			$lang_input[$key]=$value;
			$res = $this->writeConfig($folder."/site_common_lang.php",$lang_input);
			if ($res) {
				$this->session->set_flashdata('success', 'Language column in File has been added successfully!');
				admin_redirect('language', 'refresh');
			} else {
				$this->session->set_flashdata('error', 'Unable to add the language column in file!');
				admin_redirect('language/add_file/'.$id, 'refresh');
			}
		}
		$data['action'] = admin_url() . 'language/add_file/'.$id;
		$data['title'] = 'Add Language Column';
		$data['meta_keywords'] = 'Add Language Column';
		$data['meta_description'] = 'Add Language Column';
		$data['main_content'] = 'language/languages';
		$data['view'] = 'add_file';
		$this->load->view('administrator/admin_template', $data);
	}
	public function writeConfig( $filename, $config ) {
	    $fh = fopen($filename, "w");
	    if (!is_resource($fh)) {
	        return false;
	    }
	    fwrite($fh, "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n");
		if(is_array($config))
		{
			foreach ($config as $key => $value) {
				$key = str_replace('.', '', $key);
				// fwrite($fh, sprintf("\$lang['%s'] = \"%s\";\n", $key, $value));
				fwrite($fh, sprintf('$lang["%s"] = "%s";', $key, $value));
				fwrite($fh, "\n");
			}
		}
	    fclose($fh);
	    return true;
	}
	//
	public function writeConfig1( $filename, $config ) {
	    $fh = fopen($filename, "w");
	    if (!is_resource($fh)) {
	        return false;
	    }
	    fwrite($fh, "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n");
		if(is_array($config))
		{
			foreach ($config as $key => $value) {
				$key = str_replace('_', ' ', $key);
				// fwrite($fh, sprintf("\$lang['%s'] = \"%s\";\n", $key, $value));
				fwrite($fh, sprintf('$lang["%s"] = "%s";', $key, $value));
				fwrite($fh, "\n");
			}
		}
	    fclose($fh);
	    return true;
	}
	public function writeConfig2( $filename, $config, $code ) {
	    $fh = fopen($filename, "w");
	    if (!is_resource($fh)) {
	        return false;
	    }
	    fwrite($fh, "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n");
		if(is_array($config))
		{
			foreach ($config as $key => $value) {
				$key = str_replace('.', '', $key);
				$value2=translate("en", $code, $value);
				if(!$value2){
					$value2=$value;
				}
				// fwrite($fh, sprintf("\$lang['%s'] = \"%s\";\n", $key, $value));
				fwrite($fh, sprintf('$lang["%s"] = "%s";', $key, $value2));
				fwrite($fh, "\n");
			}
		}
	    fclose($fh);
	    return true;
	}
	//
	public function get_file_as_arr($file){
		include(APPPATH.'language/'.$file.'/site_common_lang.php');
		// foreach ($lang as $key => $value) {
			// $data[$key] = array(
									// 'name'  => 'lang['.$key.']',
									// 'type'  => 'text',
									// 'value' => $value,
									// 'class' => 'form-control'
								// );
		// }
		return $lang;
	}
	//vv
	public function get_file_as_array($file){
        include(APPPATH.'language/'.$file.'/form_validation_lang.php');

        return $lang;

	}
	//vv

	public function get_sample_language(){
		return array('email'=>'');
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
			admin_redirect('language');
		}
		$isValid = $this->common_model->getTableData('languages', array('id' => $id));
		if ($isValid->num_rows() == 0) {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('language');
		}
		// Form validation
		$this->form_validation->set_rules('name', 'Language Name', 'required|xss_clean');
		$this->form_validation->set_rules('status', 'Status', 'required|xss_clean');	
		if ($this->input->post()) {				
			if ($this->form_validation->run())
			{
				$updateData=array();
				$name=$this->input->post('name');
				$status=$this->input->post('status');
				$updateData['name'] = $name;
				$updateData['status'] = $status;		
				$condition = array('id' => $id);
				// updated via Common model
				$update = $this->common_model->updateTableData('languages', $condition, $updateData);
				if ($update) {
					$this->session->set_flashdata('success', 'Language has been updated successfully!');
					admin_redirect('language', 'refresh');
				} else {
					$this->session->set_flashdata('error', 'Unable to update this language');
					admin_redirect('language/edit/' . $id, 'refresh');
				}
			}
			else
			{
				$this->session->set_flashdata('error', 'Unable to update this language');
				admin_redirect('language/edit/' . $id, 'refresh');
			}
		}
		$data['language'] = $isValid->row();
		$data['action'] = admin_url() . 'language/edit/' . $id;
		$data['title'] = 'Edit Language';
		$data['meta_keywords'] = 'Edit Language';
		$data['meta_description'] = 'Edit Language';
		$data['main_content'] = 'language/languages';
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
			admin_redirect('language');
		}
		$isValid = $this->common_model->getTableData('languages', array('id' => $id))->num_rows();
		if ($isValid > 0) { // Check is valid banner 
			$condition = array('id' => $id);
			$updateData['status'] = $status;
			$update = $this->common_model->updateTableData('languages', $condition, $updateData);
			if ($update) { // True // Update success
				if ($status == 1) {
					$this->session->set_flashdata('success', 'Language activated successfully');
				} else {
					$this->session->set_flashdata('success', 'Language de-activated successfully');
				}
				admin_redirect('language');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occur with language status updation');
				admin_redirect('language');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this language');
			admin_redirect('language');
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
			admin_redirect('language');
		}
		$isValid = $this->common_model->getTableData('languages', array('id' => $id));
		if ($isValid->num_rows() > 0) { // Check is valid 
			$condition = array('id' => $id);
			$delete = $this->common_model->deleteTableData('languages', $condition);
			$language_condition = array('language' => $id);
			$this->common_model->deleteTableData('static_content', $language_condition);
			$this->common_model->deleteTableData('cms', $language_condition);
			$this->common_model->deleteTableData('email_template', $language_condition);
			$this->common_model->deleteTableData('faq', $language_condition);
			$this->common_model->deleteTableData('news', $language_condition);
			$folder = APPPATH.'language/'.$isValid->row('seo_url');
			unlink($folder.'/site_common_lang.php');
			unlink($folder.'/form_validation_lang.php');
			rmdir($folder);//die;
			if ($delete) { // True // Delete success
				$this->session->set_flashdata('success', 'Language deleted successfully');
				admin_redirect('language');
			} else { //False
				$this->session->set_flashdata('error', 'Problem occur with language deletion');
				admin_redirect('language');	
			}
		} else {
			$this->session->set_flashdata('error', 'Unable to find this page');
			admin_redirect('language');
		}	
	}
	function update_language_file()
	{
		$seourl='english';
		$folder = APPPATH.'language/'.$seourl;
		$lang_input=$this->get_file_as_arr($seourl);
		$insertdata=array();
		if($lang_input)
		{
			foreach($lang_input as $langs)
			{
				$langs=str_replace('"','',$langs);
				$keys=str_replace('.','',$langs);
				$insertdata[$keys]=$langs;
			}
		}
		$res = $this->writeConfig($folder."/site_common_lang.php",$insertdata);
	}
//vv

    /*function update_lang_file(){
    	$seourl = 'english';
    	$folder = APPPATH.'language/'.$seourl;
    	$language_input = $this->get_file_as_arrray($seourl);
    	$insertdata = array();
    	if($language_input)
		{
			foreach($language_input as $langs)
			{
				$langs=str_replace('"','',$langs);
				$insertdata[$langs]=$langs;
			}
		}
		$langfile = $this->writeConfig($folder."/form_validation_lang.php",$insertdata);
    

    }*/




 }