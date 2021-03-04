<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// function to send mail on request of forgot password 
function forgetMailService($data)
{
	$ci 	  		= &get_instance();
	//$from_email	= $ci->common_model->get_admin_email();
	$from_email ='nisha.mishra@srmtechsol.com';
	$from_name		= 'Cartwire Admin';	
	$to				= $data['email'];		
	$subject		= "Forget Password Service";
	$url 	  		= base_url();
	$message  		= "";
	$message 		='<table class="body-wrap" style="padding-top:20px;font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
            
            <tr>
                <td valign="top"></td>
                <td style="vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                    <div style="box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-radius: 5px 5px 0 0;box-shadow: 1px -3px 7px 1px #494949;font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;  background-color: #fff; margin: 0; border: 1px solid #C62E25;" bgcolor="#fff">
                            <tr>
                                <td align="center" style="padding:10px 0px;border-radius:3px 3px 0px 0px; background-color:#C62E25"><img src="http://192.200.12.168/cwcamp/assets/images/logo_cartwire.png"></td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <table width="100%" cellpadding="10" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tr>
                                            <td>
                                                Hi '.ucwords($data['name']).',
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                We have successfully recovered your password, see below.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                Password: '.$data["password"].'
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td valign="top" align="left">
                                                <a href="'.base_url().'" style="font-size: 12px; color: #666;text-decoration: underline" base_url>Click here to login</a><br/>
                                                <span style="font-size: 11px; color: #666;"> If the link is not click-able, copy and paste the url given below in the address bar of your browser:<br/>
                                                </span>
                                                <span style="font-size: 11px; color: rgb(29,154,214);">'.base_url().'</span>
                                            </td>
                                            
                                        </tr>
                                        
                                        <tr>
                                            <td valign="top">
                                                &mdash; Team CartWire
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div  style="box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                            <table width="100%">
                                <tr >
                                    <td align="center" valign="top"><a base_url href="http://twitter.com/mail_gun" style="font-size: 12px; color: #999; text-decoration: underline; margin: 0;">'._COPYRIGHT_.'</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                <td valign="top"></td>
            </tr>
        </table>';

	include('mail/mail_sending.php'); 
	$mail = new mailSending;			
	return $mail->send_mail($to, '', $subject, $message, $from_email, $from_name);	
}

// function to send mail for registered user
function send_registration_confirmation($data)
{
	$ci 			= &get_instance();
	//$from_email	= $ci->common_model->get_admin_email();
	$from_email ='nisha.mishra@srmtechsol.com';
	//$created_by	= $ci->common_model->get_admin_email($data['created_by']);
	$from_name		= 'Cartwire Admin';	
	$to				= $data['email'];		
	$subject 	 	= "New User Registration";
	$message  		= "";
	$message 		='<table class="body-wrap" style="padding-top:20px;font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
            
            <tr>
                <td valign="top"></td>
                <td style="vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                    <div style="box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-radius: 5px 5px 0 0;box-shadow: 1px -3px 7px 1px #494949;font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;  background-color: #fff; margin: 0; border: 1px solid #C62E25;" bgcolor="#fff">
                            <tr>
                                <td align="center" style="padding:10px 0px;border-radius:3px 3px 0px 0px; background-color:#C62E25"><img src="http://192.200.12.168/cwcamp//assets/images/logo_cartwire.png"></td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <table width="100%" cellpadding="10" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tr>
                                            <td>
                                                Hi '.ucwords($data['name']).',
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                As per our record, please receive your Cartwire campaign account credentials below.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                Email: '.$data["email"].'<br/>
                                                Password: '.$data["password"].'
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td valign="top" align="left">
                                                <a href="'.base_url().'" style="font-size: 12px; color: #666;text-decoration: underline" base_url>Click here to login</a><br/>
                                                <span style="font-size: 11px; color: #666;"> If the link is not click-able, copy and paste the url given below in the address bar of your browser:<br/>
                                                </span>
                                                <span style="font-size: 11px; color: rgb(29,154,214);">'.base_url().'</span>
                                            </td>
                                            
                                        </tr>
                                       
                                        <tr>
                                            <td valign="top">
                                                &mdash; Team CartWire
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div  style="box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                            <table width="100%">
                                <tr >
                                    <td align="center" valign="top"><a base_url href="http://twitter.com/mail_gun" style="font-size: 12px; color: #999; text-decoration: underline; margin: 0;">'._COPYRIGHT_.'</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                <td valign="top"></td>
            </tr>
        </table>';
      
	include('mail/mail_sending.php'); 
	$mail = new mailSending;			
	return $mail->send_mail($to, '', $subject, $message, $from_email, $from_name);
}
// function to send mail for records updation of existing user
function user_records_updation($data)
{
	$ci 			= &get_instance();
	//$from_email	= $ci->common_model->get_admin_email();
	$from_email ='nisha.mishra@srmtechsol.com';
	//$created_by	= $ci->common_model->get_admin_email($data['created_by']);
	$from_name		= 'Cartwire Admin';	
	$to				= $data['email'];		
	$subject 	 	= "Profile Updated";
	$message  		= "";
	$message 		='<table class="body-wrap" style="padding-top:20px;font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
            
            <tr>
                <td valign="top"></td>
                <td style="vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                    <div style="box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-radius: 5px 5px 0 0;box-shadow: 1px -3px 7px 1px #494949;font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px;  background-color: #fff; margin: 0; border: 1px solid #C62E25;" bgcolor="#fff">
                            <tr>
                                <td align="center" style="padding:10px 0px;border-radius:3px 3px 0px 0px; background-color:#C62E25"><img src="http://192.200.12.168/cwcamp//assets/images/logo_cartwire.png"></td>
                            </tr>
                            <tr>
                                <td valign="top">
                                    <table width="100%" cellpadding="10" cellspacing="0" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tr>
                                            <td>
                                                Hi '.ucwords($data['name']).',
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                 Your Cartwire campaign account details has been updated, login to view the changes.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                                Email: '.$data["email"].'<br/>
                                                Password: '.$data["password"].'
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td valign="top" align="left">
                                                <a href="'.base_url().'" style="font-size: 12px; color: #666;text-decoration: underline" base_url>Click here to login</a><br/>
                                                <span style="font-size: 11px; color: #666;"> If the link is not click-able, copy and paste the url given below in the address bar of your browser:<br/>
                                                </span>
                                                <span style="font-size: 11px; color: rgb(29,154,214);">'.base_url().'</span>
                                            </td>
                                            
                                        </tr>
                                     
                                        <tr>
                                            <td valign="top">
                                                &mdash; Team CartWire
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div  style="box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                            <table width="100%">
                                <tr >
                                    <td align="center" valign="top"><a base_url href="http://twitter.com/mail_gun" style="font-size: 12px; color: #999; text-decoration: underline; margin: 0;">'._COPYRIGHT_.'</a></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
                <td valign="top"></td>
            </tr>
        </table>';
      
	include('mail/mail_sending.php'); 
	$mail = new mailSending;			
	return $mail->send_mail($to, '', $subject, $message, $from_email, $from_name);
}

// function to send mail for registered user
function send_testmail()
{
	$ci = &get_instance();

	$from_email	= $ci->common_model->get_admin_email();
	$from_name	= 'cwcamp.com';
	$name		= "Manoj";
	$to			= "mnjdv9@gmail.com";
	$subject 	= "Registration Confirmation";
	$url 	    = base_url().'user/index';
	
	$message  = "";
	
	$message .= '<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">';		
	$message .= '<tr>';
	$message .= '<td valign="top" align="left" style="padding-top:5px;">';
	$message .= 'Dear '.ucwords($name).'';				
	$message .= '</td></tr>';
	$message .= '<tr>';
	$message .= '<td valign="top" align="left" style="padding-top:5px;">';
	$message .= "<p>You have successfully registered on 99advisors.com </p>";
	$message .= '</td></tr>';
	$message .= '<tr>';
	$message .= '<td valign="top" align="left" style="padding-top:5px;">';
	$message .= "<a href='".$url."'>Click Here</a> to login.<p>If the link is not clickable please copy and paste it to your browser: <br />".$url."</p>";
	$message .= '</td></tr>';
	$message .= '</table>';
	
	include('mail/mail_sending.php');
	$mail = new mailSending;			
	return $mail->send_mail($to, '', $subject, $message, $from_email, $from_name);
}
