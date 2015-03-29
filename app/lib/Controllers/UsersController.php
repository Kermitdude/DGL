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
		$this->ajaxData['success'] = false;
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
	
	/*************************************************************** Users */
	
	public function addUser($name = false, $email = false, $password = false)
	{		
		if ($name && $email && $password)
		{
			$user = new User();
			$user->setName($name);
			$user->setEmail($email);
			$user->setPassword($password);
			
			$success = $user->save();
			$this->ajaxData['success'] = $success;
			
			$this->send();
		}
	}	
	
	public function deleteUser($id = false)
	{		
		if ($id)
		{
			$user = Base\UserQuery::create()->findOneById($id);
			$user->delete();
			
			$this->ajaxData['success'] = $user->isDeleted();
			
			$this->send();	
		}
	}	
	
	public function editUser()
	{		
		if (isset($_POST['pk']))
		{
			if (isset($_POST['value']))
			{
				$user = Base\UserQuery::create()->findOneById($_POST['pk']);
				
				if (isset($_POST['name']))
				{
					if ($_POST['name'] === 'name') $user->setName($_POST['value']);
					elseif ($_POST['name'] === 'email') $user->setEmail($_POST['value']);
					elseif ($_POST['name'] === 'password') $user->setPassword($user->hashPassword($_POST['value']));
				}
				
				$success = $user->save();
				$this->ajaxData['success'] = $success;
			}
		}
				
		$this->send();	
	}		
	
	/*************************************************************** Roles */
	
	public function editRole()
	{		
		if (isset($_POST['pk']))
		{
			if (isset($_POST['value']))
			{
				$role = Base\RoleQuery::create()->findOneById($_POST['pk']);
				
				if (isset($_POST['name']))
				{
					if ($_POST['name'] === 'name') $role->setName($_POST['value']);
					elseif ($_POST['name'] === 'annotation') $role->setAnnotation($_POST['value']);
				}
				
				$success = $role->save();
				$this->ajaxData['success'] = $success;
			}
		}
				
		$this->send();	
	}		
	
	public function deleteRole($id = false)
	{		
		if ($id)
		{
			$role = Base\RoleQuery::create()->findOneById($id);
			$role->delete();
			
			$this->ajaxData['success'] = $role->isDeleted();
			
			$this->send();	
		}
	}	
	
	protected function send()
	{
		echo json_encode($this->ajaxData);		
	}
}
?>
