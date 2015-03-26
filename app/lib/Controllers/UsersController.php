<?php
namespace DigitalGaming;

class UsersController
{
	protected $title = "Profile";
	protected $viewPath;
	protected $ajaxData;
	
	public $user;
	
	public function __construct()
	{
		$this->viewPath = $_SERVER['DOCUMENT_ROOT'] . "views/Users/";
	}
	
	public function index()
	{
		include $this->viewPath . 'index.php';
	}
	
	public function profile($name = false)
	{
		if (!$name) // no parameters given 
		{
			if (isset($_SESSION['username']))
			{
				$name = $_SESSION['username']; // default to own profile
			}
			else
			{
				return "You must be logged in for this, you silly git.";
			}
		}
		
		$user = (new User)->getUserObject($name);
		
		include $this->viewPath . 'profile.php';
	}
	
	public function addUser()
	{		
		if (isset($_POST['name']))
		{
			if (isset($_POST['email']))
			{
				if (isset($_POST['password']))
				{
					
					$user = new User();
					$user->setName($_POST['name']);
					$user->setEmail($_POST['email']);
					$user->setPassword($user->hashPassword($_POST['password']));
					
					$success = $user->save();
					$this->ajaxData['success'] = $success;
					
					$this->send();	
				}
			}
		}
	}	
	
	public function deleteUser()
	{		
		if (isset($_POST['id']))
		{
			$user = Base\UserQuery::create()->findOneById($_POST['id']);
			$user->delete();
			
			$this->ajaxData['success'] = $user->isDeleted();
			
			$this->send();	
		}
	}		
	
	protected function send()
	{
		echo json_encode($this->ajaxData);		
	}
}
?>
