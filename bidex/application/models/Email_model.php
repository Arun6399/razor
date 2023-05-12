<?php
/**
* Email_model Class
* @package Spiegel Technologies
* @subpackage Coinchairs
* @category Model
* @author Pialventhiran
* @version 1.0
* @link http://spiegeltechnologies.com/
* 
*/

class Email_model extends CI_Model 
{
	// Constructor function
	function __construct() 
	{
	parent::__construct();
	} 	

	//Send email function 
	/**
	* @access private
	* @param array(),  
	* @return email deliver report
	* 
	*/

	function sendMail($to = '', $from_email = '', $from_name = '', $email_template = '',$special_vars = array(), $cc = '', $bcc = '', $type = 'html' ) 
	{

		// Loads the email library
		$this->load->library(array('email'));

		$emailConfig = $this->db->where('id', 1)->get('site_settings')->row(); // Fetch from DB
		//$admindetails = $this->db->where('id', 1)->get('users')->row(); // Fetch from DB		
		$smtp_host = decryptIt($emailConfig->smtp_host);// SMTP host URL
		$smtp_port = decryptIt($emailConfig->smtp_port);// SMTP port number
		$smtp_user = decryptIt($emailConfig->smtp_email);// SMTP email address
		$smtp_pass = decryptIt($emailConfig->smtp_password); //exit; // SMTP password	

		$special_vars['###COPYRIGHT###'] = $emailConfig->copy_right_text;
		$special_vars['###ADDRESS###'] = $emailConfig->address.','.$emailConfig->city.','.$emailConfig->state.','.$emailConfig->country.','.$emailConfig->zip;
		$special_vars['###SITEEMAIL###'] = $emailConfig->site_email;
		$special_vars['###SITELOGO###'] = getSiteLogo();
		$special_vars['###SITENAME###'] = $emailConfig->site_name;
		$special_vars['###SITELINK###'] = base_url();
		$special_vars['###ABOUTUS###'] = base_url().'home#about-bg';
		$special_vars['###PRIVACY###'] = base_url().'cms/privacy-policy';
		$special_vars['###TERMS###'] = base_url().'cms/terms-and-conditions';
		$special_vars['###CONTACTUS###'] = base_url().'contact_us';
  
        /* Edited by Manimegs */
		if($emailConfig->facebooklink!='')
		{
			$fblink = $special_vars['###FACEBOOKLINK###'] = $emailConfig->facebooklink;
			$fbimag = $special_vars['###FACEBOOKIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/intozvq0177rftxsbhob.png";

			$special_vars['<td>###FBMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$fblink.'" target="_blank"><img alt="" border="0" height="34" src="'.$fbimag.'" width="34" /></a></td>';

		}
		else
	    {
	    	$special_vars['<td>###FBMODEL###</td>'] = '';
	    }
		if($emailConfig->twitterlink!='')
		{
			$twlink = $special_vars['###TWITTERLINK###'] = $emailConfig->twitterlink;
			$twimag = $special_vars['###TWITTERIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/ly7jiql3qwkampbfkx3e.png";

			$special_vars['<td>###TWITMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$twlink.'" target="_blank"><img alt="" border="0" height="34" src="'.$twimag.'" width="34" /></a></td>';
		}
		else
	    {
	    	$special_vars['<td>###TWITMODEL###</td>'] = '';
	    }
	    
	    if($emailConfig->telegramlink!='')
	    {
			$tellink = $special_vars['###TELEGRAMLINK###'] = $emailConfig->telegramlink;
			$telimag = $special_vars['###TELEGRAMIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/tt6v6fgy5hoycn9uwp5f.png";

			$special_vars['<td>###TELEMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$tellink.'" target="_blank"><img alt="" border="0" height="34" src="'.$telimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###TELEMODEL###</td>'] = '';
	    }
	    if($emailConfig->googlelink!='')
	    {
			$goolink = $special_vars['###GOOGLELINK###'] = $emailConfig->googlelink;
			$gooimag = $special_vars['###GOOGLEIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/nsneghfafa2wtygh23ca.png";

			$special_vars['<td>###GOOGLEMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$goolink.'" target="_blank"><img alt="" border="0" height="34" src="'.$gooimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###GOOGLEMODEL###</td>'] = '';
	    }
	    if($emailConfig->youtube_link!='')
	    {
			$youlink = $special_vars['###YOUTUBELINK###'] = $emailConfig->youtube_link;
			$youimag = $special_vars['###YOUTUBEIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/rmageljnvvx7at2amdxd.png";

			$special_vars['<td>###YOUMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$youlink.'" target="_blank"><img alt="" border="0" height="34" src="'.$youimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###YOUMODEL###</td>'] = '';
	    }
	    
	    if($emailConfig->linkedin_link!='')
	    {
			$linklink = $special_vars['###LINKEDINLINK###'] = $emailConfig->linkedin_link;
			$linkimag = $special_vars['###LINKEDINIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/araz3ba7rerveqymitvz.png";

			$special_vars['<td>###LINKMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$linklink.'" target="_blank"><img alt="" border="0" height="34" src="'.$linkimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###LINKMODEL###</td>'] = '';
	    }
	    
	    if($emailConfig->instagram_link!='')
	    {
			$instalink = $special_vars['###INSTAGRAMLINK###'] = $emailConfig->instagram_link;
			$instaimag = $special_vars['###INSTAGRAMIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850139/ytwtj8ctbilacom93lxm.png";

			$special_vars['<td>###INSTAMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$instalink.'" target="_blank"><img alt="" border="0" height="34" src="'.$instaimag.'" width="34" /></a></td>';

	    }
	    else
	    {
	    	$special_vars['<td>###INSTAMODEL###</td>'] = '';
	    }

	    if($emailConfig->pinterest_link!='')
	    {
			$pinlink = $special_vars['###PINTERESTNLINK###'] = $emailConfig->pinterest_link;
			$pinimag = $special_vars['###PINTERESTIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850241/ak8m7vtewpmzj7bujhbt.png";

			$special_vars['<td>###PINMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$pinlink.'" target="_blank"><img alt="" border="0" height="34" src="'.$pinimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###PINMODEL###</td>'] = '';
	    }

	    if($emailConfig->dribble_link!='')
	    {
			$driblink = $special_vars['###DRIBBLELINK###'] = $emailConfig->dribble_link;
			$driblimag = $special_vars['###DRIBBLEIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/xbsz5ghte4bkkg2kbyjp.png";
			$special_vars['<td>###DRIBMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$driblink.'" target="_blank"><img alt="" border="0" height="34" src="'.$driblimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###DRIBMODEL###</td>'] = '';
	    }
        
        if($emailConfig->medium_link!='')
	    {
			$mediumlink = $special_vars['###MEDIUMLINK###'] = $emailConfig->medium_link;
			$mediumimag = $special_vars['###MEDIUMIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/xbsz5ghte4bkkg2kbyjp.png";
			$special_vars['<td>###MEDIUMMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$mediumlink.'" target="_blank"><img alt="" border="0" height="34" src="'.$mediumimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###MEDIUMMODEL###</td>'] = '';
	    }
	    if($emailConfig->reddit_link!='')
	    {
			$redditlink = $special_vars['###REDDITLINK###'] = $emailConfig->reddit_link;
			$redditimag = $special_vars['###REDDITIMAGE###'] = "https://res.cloudinary.com/spiegel/image/upload/v1605850140/xbsz5ghte4bkkg2kbyjp.png";
			$special_vars['<td>###REDDITMODEL###</td>'] = '<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="'.$redditlink.'" target="_blank"><img alt="" border="0" height="34" src="'.$redditimag.'" width="34" /></a></td>';
	    }
	    else
	    {
	    	$special_vars['<td>###REDDITMODEL###</td>'] = '';
	    }
		$from_email = decryptIt($emailConfig->smtp_email);
		if($from_name == '')
		$from_name = $emailConfig->site_name;
		$this->email->clear();
		$config = array(
		'protocol' => 'smtp',
		'smtp_host' => $smtp_host,
		'smtp_port' => $smtp_port,
		'smtp_user' => trim($smtp_user),
		'smtp_pass' => trim($smtp_pass),
		'mailtype' => $type,
		'smtp_crypto' => 'ssl',
		'charset' => 'utf-8'
		);

		// $config['crlf'] = "\r\n";
		$config['newline'] = "\r\n";
		// $config['priority'] = 1;



		// Email config initialize
		$this->email->initialize($config);
		if ( ! empty($smtp_host) && ! empty($smtp_port) && ! empty($smtp_user) && ! empty($smtp_pass)) 
		{ 
			if(is_numeric($email_template))	
				$emailTemplate = $this->db->where('id', $email_template)->get('email_template');
			else
				$emailTemplate = $this->db->where('name', $email_template)->get('email_template');

			if ($emailTemplate->num_rows() > 0) 
			{
				$emailTemplate = $emailTemplate->row();
				// Subject

				$subject = strtr($emailTemplate->subject, $special_vars);
				// message content
				$message = strtr($emailTemplate->template, $special_vars);


				//Working Code
				$this->email->to($to);
				$this->email->from($from_email,$from_name);
				if ($cc != '') 
				{
					$this->email->cc($cc);
				}
				if ($bcc != '') 
				{	
					$this->email->bcc($bcc);
				} 
				$this->email->subject($subject);
				$this->email->message($message);

				// $this->email->subject('Test');
				// $this->email->message('Test');
				$send=$this->email->send();
				// die;
				if (!$send) 
				{   
				  // echo  "Mail not sent";

				  //  exit();
					$this->email->clear();
					
					return false;
				} 
				else 
				{
				  // echo   "mail sent";

				   //  exit();
					return true;
				}
				return true;
			} 
			else 
			{
				exit('Email template not configured please contact support team');
			}	 
		} 
		else 
		{ 
			exit('SMTP not configured please contact support team');
		}
	}

}

/**
* End of the file email_model.php
* Location: ./application/models/email_model.php
*/ 
