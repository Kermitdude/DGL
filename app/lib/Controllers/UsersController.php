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
				
				if ($user)
				{
					if (isset($_POST['name']))
					{
						if ($_POST['name'] === 'name') $user->setName($_POST['value']);
						elseif ($_POST['name'] === 'email') $user->setEmail($_POST['value']);
						elseif ($_POST['name'] === 'password') $user->setPassword($user->hashPassword($_POST['value']));
						elseif ($_POST['name'] === 'roles')
						{
							// create Role objects from ID and add them to user
							$roleCollection = Base\RoleQuery::create()->findPks($_POST['value']);
							$user->setRoles($roleCollection);	
							$this->ajaxData['success'] = true;				
						}
					}
					
					$success = $user->save();
					if (!$this->ajaxData['success']) $this->ajaxData['success'] = $success;
				}
				
				else $this->ajaxData['errors'][] = "User " . $_POST['pk'] . " not found";
			}
		}
				
		$this->send();	
	}		
	
	/*************************************************************** Roles */
		
	public function addRole($name = false, $annotation = false)
	{		
		if ($name && $annotation)
		{
			$role = new Role();
			$role->setName($name);
			$role->setAnnotation($annotation);
			
			$success = $role->save();
			$this->ajaxData['success'] = $success;
			
			$this->send();
		}
		else $this->ajaxData['errors'][] = 'Insufficient parameters'; 
	}	
	
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
					elseif ($_POST['name'] === 'permissions')
					{
						// create Role objects from ID and add them to user
						$permsCollection = Base\PermissionQuery::create()->findPks($_POST['value']);
						$role->setPermissions($permsCollection);	
						$this->ajaxData['success'] = true;				
					}
				}
				
				$success = $role->save();
				if (!$this->ajaxData['success']) $this->ajaxData['success'] = $success;
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
	
	/*************************************************************** Permissions */
	
	public function addPermission($name = false, $annotation = false)
	{		
		if ($name && $annotation)
		{
			$permission = new Permission();
			$permission->setName($name);
			$permission->setAnnotation($annotation);
			
			$success = $permission->save();
			$this->ajaxData['success'] = $success;
			
			$this->send();
		}
		else $this->ajaxData['errors'][] = 'Insufficient parameters'; 
	}	
	
	public function editPermission()
	{		
		if (isset($_POST['pk']))
		{
			if (isset($_POST['value']))
			{
				$permission = Base\PermissionQuery::create()->findOneById($_POST['pk']);
				
				if (isset($_POST['name']))
				{
					if ($_POST['name'] === 'name') $permission->setName($_POST['value']);
					elseif ($_POST['name'] === 'annotation') $permission->setAnnotation($_POST['value']);
				}
				
				$success = $permission->save();
				$this->ajaxData['success'] = $success;
			}
		}
		
		$this->send();	
	}		
	
	public function deletePermission($id = false)
	{	
		if ($id)
		{
			$permission = Base\PermissionQuery::create()->findOneById($id);
			if ($permission) $permission->delete();
			
			$this->ajaxData['success'] = $permission->isDeleted();
		}
			
		$this->send();	
	}	
	
	protected function send()
	{
		echo json_encode($this->ajaxData);		
	}
}
?>
