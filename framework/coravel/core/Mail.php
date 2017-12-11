<?php

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail 
{	
	protected $mail = null;

	function __construct($to,$name){
		$this->mail = new PHPMailer(true);
		// $this->mail->SMTPDebug = 1; 
        $this->mail->isSMTP();                                      // Set mailer to use SMTP
		$this->mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$this->mail->SMTPAuth = true;                               // Enable SMTP authentication
		$this->mail->Username = 'username';                 // SMTP username
		$this->mail->Password = 'password';                           // SMTP password
		$this->mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
		$this->mail->Port = 465;   
        //Recipients
        $this->mail->setFrom('no-reply@gmail.net', 'name');
        $this->mail->addAddress($to, $name);     // Add a recipient
        //Content
        $this->mail->isHTML(true);          
	}
	public static function to($to,$name=null){
		return new Mail($to,$name);
	}
	public function cc($addCC){
		$this->mail->addCC = $addCC;
		return $this;
	}
	public function bcc($addBCC){
		$this->mail->addBCC = $addBCC;
		return $this;
	}
	public function subject($subject){
		$this->mail->Subject = $subject;
		return $this;
	}
	public function message($message){
		$this->mail->Body = $message;
		return $this;
	}

	public function send(){

		$this->mail->send();
	}
	
}
