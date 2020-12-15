<?php 
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;
	
	require './vendor/autoload.php';
	
	$MailTo = new PHPMailer(TRUE); 
	
	try {
		
		//Server settings
		$MailTo->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
		$MailTo->Timeout  =   10;
		$MailTo->isSMTP();                                            // Send using SMTP
		$MailTo->Host       = 'intmail.rtarf.mi.th';                    // Set the SMTP server to send through
		$MailTo->SMTPAuth   = true;                                   // Enable SMTP authentication
		$MailTo->Username   = 'mildoc@rtarf.mi.th';                     // SMTP username
		$MailTo->Password   = 'xje7Cjma';                               // SMTP password
		$MailTo->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
		$MailTo->Port       = 587;                                    // TCP port to connect to
		//$MailTo->SMTPOptions = array('ssl' => array('verify_peer' => false,'verify_peer_name' => false,'allow_self_signed' => true));

		//Recipients
		$MailTo->setFrom('noreply@rtarf.mi.th', 'Information');
		$MailTo->addAddress('pichet.v@rtarf.mi.th');
		// $MailTo->addReplyTo('mildoc@rtarf.mi.th', 'Information');
		// $MailTo->addCC('cc@example.com');
		// $MailTo->addBCC('bcc@example.com');

		// Attachments
		// $MailTo->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $MailTo->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		// Content
		$MailTo->isHTML(true);                                  // Set email format to HTML
		$MailTo->Subject = 'Subject';
		$MailTo->Body    = 'Body';
		$MailTo->AltBody = 'This is the body in plain text for non-HTML mail clients';


			if($MailTo->send()){
				echo "Message sented. Mailer ";
			} else {
				echo $MailTo->ErrorInfo;
			}

		} catch (Exception $e) {          
			//var_dump($e);
			echo "Message could not be sent. Mailer Error: {$e}";
		}

?>