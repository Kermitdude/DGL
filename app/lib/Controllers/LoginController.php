<?php
namespace DigitalGaming;

class LoginController
{	
	protected $ajaxData;
	
	public function login($username = false, $password = false)
	{
		if ($username)
		{
			if ($password)
			{
				$user = new User();
				
				$success = $user->login($username, $password);
				
				$this->ajaxData['success'] = $success;
				
				if ($success) $this->onSuccess();
				else $this->onFail();
			}
		}	
	}

	public function logout()
	{
		$user = new User();
		$user->logout();
	}
	
	protected function onSuccess()
	{
		// Update session
		Session::update();
		$this->ajaxData['stuff'] = $_SESSION;
		
		$this->send();
	}
	
	protected function onFail()
	{
		// Update session
		Session::end();
		
		$this->send();
	}
	
	protected function send()
	{
		echo json_encode($this->ajaxData);		
	}
}
?>
