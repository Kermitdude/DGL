<?php
namespace DigitalGaming;

class EmailController
{
	protected $ajaxData;
	
	public function __construct()
	{
		$this->ajaxData['success'] = false;
	}
	
	
	public function sendPassword($id = false)
	{
		if ($id)
		{
			$user = UserQuery::create()->findOneById($id);
			$name = $user->getName();
			$email = $user->getEmail();
		
			if ($email && $name)
			{
				$mail = $this->getMailer();
				
				$mail->addAddress($email, $name);    
				$mail->Subject = 'Here is the subject';
				$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
				$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

				if(!$mail->send()) 
				{
					$this->ajaxData['errors'][] = $mail->ErrorInfo;
					
				} 
				else 
				{
					$this->ajaxData['name'] = $name;
					$this->ajaxData['success'] = true;
				}		
			} 
			else $this->ajaxData['errors'][] = "No email found for that user";
		}
		else $this->ajaxData['errors'][] = "No ID specified";
		
		$this->send();
	}
	
	protected function getMailer()
	{
		$mail = new \PHPMailer;

		$mail->isSMTP();                                            
		$mail->STMPDebug = false;
		$mail->Host = 'smtp.gmail.com'; 				             
		$mail->SMTPAuth = true;                                      
		$mail->Username = 'toolclanblr@gmail.com';                 
		$mail->Password = 'uppercase4';                              
		$mail->SMTPSecure = 'tls';                                   
		$mail->Port = 587;                                           
		$mail->From = 'toolclanblr@gmail.com';
		$mail->FromName = 'Mailer';
		
		return $mail;
	}
	
	protected function send()
	{
		echo json_encode($this->ajaxData);		
	}
}
?>
