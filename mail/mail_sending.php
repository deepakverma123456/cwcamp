<?php
/********************************** End session Start**********************/
require_once("class.phpmailer.php"); // for smtp mailing system


class mailSending
{
	/**
	Mail function
	used to send mail using smtp
	@param $to   to address
	@param $subject  subject of mail
	@param $message  message of mail	 
	@param $from from address
	*/
	function send_mail($to,$toName,$subject,$message,$from, $fromName)
	{
		
		$mail = new PHPMailer(); //create mailing object	

	    $mail->Host = "ssl://smtp.gmail.com"; // SMTP servers
		$mail->Port = 465;
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = "manoj.kumar@srmtechsol.com";  // SMTP username
		$mail->Password = "priya_2016"; // SMTP password
		
		
		$mail->IsSMTP();                                   // send via SMTP
		

		$mail->From     = $from;
		$mail->FromName = $fromName;		
		$mail->AddAddress($to, $toName);
		//$mail->AddReplyTo($from,$toName);
		$mail->WordWrap = 50;     
		
		$mail->IsHTML(true);  // send as HTML
		$mail->Subject  =  $subject;
		$mail->Body     = $message;
		
		if(!$mail->Send())
		{
		  return false;	   
		}
		else
		{
			return true;
		}
						
	}
	

}


// create object

?>