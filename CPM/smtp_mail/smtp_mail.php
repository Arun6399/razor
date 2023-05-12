<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';
 
//Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
	//$mail->Host       = 'smtpout.secureserver.net';
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                          //SMTP password
	$mail->Username   = 'support@smdex.io';                     //SMTP username
    $mail->Password   = 'F72mtTBU';       
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above nd 587 ENCRYPTION_STARTTLS

    //Recipients
    $mail->setFrom('support@smdex.io', 'Mailer');
    $mail->addAddress('sakykk3@gmail.com', 'Ram');     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = '<p>​</p>
<meta content="text/html; charset=utf-8" http-equiv="Content-type" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
<meta content="IE=edge" http-equiv="X-UA-Compatible" />
<meta content="date=no" name="format-detection" />
<meta content="address=no" name="format-detection" />
<meta content="telephone=no" name="format-detection" />
<meta name="x-apple-disable-message-reformatting" />
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&amp;display=swap" rel="stylesheet" />
<title></title>
<table bgcolor="#f2f2f2" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td align="center" valign="top">
			<table border="0" cellpadding="0" cellspacing="0" class="mobile-shell" width="650">
				<tbody>
					<tr>
						<td class="td container" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; margin:0; font-weight:normal; padding:55px 0px;"><!-- Header -->
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td bgcolor="#ffffff" class="p30-15 tbrr" style="padding: 15px; border-radius:12px 12px 0px 0px;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<th class="column-top" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;" width="145">
												<table border="0" cellpadding="0" cellspacing="0" width="100%">
													<tbody>
														<tr>
															<td class="img m-center" style="font-size:0pt; line-height:0pt; text-align:center;"><a href="###SITELINK###" style="color: #0792E5; font-size:25px; font-family: Montserrat, sans-serif; font-weight: 600;text-decoration: none;"><img src="###SITELOGO###" style="width: 200px;max-width: 100%;height:auto;" /></a></td>
														</tr>
													</tbody>
												</table>
												</th>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td class="fluid-img" style="font-size:0pt; line-height:0pt; text-align:left; padding: 1px 0px 1px 0px; background-color: #0792E5;">&nbsp;</td>
								</tr>
							</tbody>
						</table>
						<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td style="padding-bottom: 10px;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tbody>
											<tr>
												<td class="h1 pb25" style="color:#444444; font-family: Montserrat, sans-serif; font-size:35px; line-height:42px; text-align:center; padding-bottom:25px;">Message received successfully</td>
											</tr>
											<tr>
												<td class="text-center pb25" style="color:#666666; font-family:Arial,sans-serif; font-size:16px; line-height:30px; text-align:center; padding-bottom:25px;">
												<p>Hello&nbsp;###USERNAME###,</p>

												<p>We have received your message, we will reply soon.</p>

												<p>Best Regards,</p>

												<p>The SMDEX Exchange Team</p>
												</td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr>
						<td bgcolor="#ffffff" class="p30-15 bbrr" style="padding: 50px 30px; border-radius:0px 0px 12px 12px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tbody>
								<tr>
									<td align="center" style="padding-bottom: 30px;">
									<table border="0" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###FACEBOOKLINK###" target="_blank"><img border="0" height="34" src="###FACEBOOKIMAGE###" width="34" /></a></td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###TWITTERLINK###" target="_blank"><img border="0" height="34" src="###TWITTERIMAGE###" width="34" /></a></td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###TELEGRAMLINK###" target="_blank"><img border="0" height="34" src="###TELEGRAMIMAGE###" width="34" /></a></td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###LINKEDINLINK###" target="_blank"><img border="0" height="34" src="###LINKEDINIMAGE###" width="34" /></a></td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###INSTAGRAMLINK###" target="_blank"><img border="0" height="34" src="###INSTAGRAMIMAGE###" width="34" /></a></td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###YOUTUBELINK###" target="_blank"><img border="0" height="34" src="###YOUTUBEIMAGE###" width="34" /></a></td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###MEDIUMLINK###" target="_blank"><img border="0" height="34" src="https://smdex.io/assets/front/img/medium.png" width="34" /></a></td>
												<td class="img" style="font-size:0pt; line-height:0pt; text-align:left;" width="55"><a href="###REDDITLINK###" target="_blank"><img border="0" height="34" src="https://smdex.io/assets/front/img/reddit1.png" width="34" /></a></td>
											</tr>
										</tbody>
									</table>
									</td>
								</tr>
								<tr>
									<td class="text-footer1 pb10" style="color:#999999; font-family:Arial,sans-serif; font-size:14px; line-height:20px; text-align:center; padding-bottom:10px;">###COPYRIGHT###</td>
								</tr>
								<tr>
									<td class="text-footer2" style="color:#999999; font-family:Arial,sans-serif; font-size:12px; line-height:26px; text-align:center;">###ADDRESS###</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table></td>
		</tr>
	</tbody>
</table>

<div id="simple-translate">
<div>
<div 79993c5b-3487-436c-9c2b-0b0303a817c4="" class="simple-translate-button isShow" height:="" icons="" left:="" moz-extension:="" style="background-image: url(" top:="" width:="">&nbsp;</div>

<div class="simple-translate-panel " style="width: 300px; height: 200px; top: 0px; left: 0px; font-size: 13px; background-color: rgb(255, 255, 255);">
<div class="simple-translate-result-wrapper" style="overflow: hidden;">
<div class="simple-translate-move" draggable="true">&nbsp;</div>

<div class="simple-translate-result-contents">
<p class="simple-translate-result" style="color: rgb(0, 0, 0);">&nbsp;</p>

<p class="simple-translate-candidate" style="color: rgb(115, 115, 115);">&nbsp;</p>
</div>
</div>
</div>
</div>
</div>
';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>