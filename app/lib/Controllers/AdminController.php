<?php
namespace DigitalGaming;

class AdminController
{
	protected $viewPath;
	
	public function __construct()
	{
		$this->viewPath = $_SERVER['DOCUMENT_ROOT'] . "views/Admin/";
	}
	
	public function index()
	{
		include $this->viewPath . 'index.php';
	}
	
	public function users()
	{
		$userQuery = Base\UserQuery::create()->find();
		$dateFormat = "F j, Y, g:i a";
		$usersTable;
		$rolesList;
		
		// Get roles
		$roleQuery = Base\RoleQuery::create()->find();
		$i = 1;
				
		foreach ($roleQuery as $role) 
		{
			$rolesList[] = array( 'value' => $role->getName(), 'text' => $role->getName());
			$i++;
		}
		
		// Get roles for each user
		foreach ($userQuery as $user) 
		{
			$roles = $user->getRoles();
			$userRoles = [];
			foreach ($roles as $role)
			{
				$userRoles[] = $role->getName();
			}
			
			$usersTable[] =  array(
				"id" => $user->getId(), 
				"name" => $user->getName(), 
				"email" => $user->getEmail(), 
				"created_at" => $user->getCreatedAt()->format($dateFormat),
				"roles" => $userRoles
				);
		}
		
		$rolesList = json_encode($rolesList);
		$usersTable = json_encode($usersTable);
		
		include $this->viewPath . 'users.php';
	}
	
	public function roles()
	{
		$roleQuery = Base\RoleQuery::create()->find();
		$rolesTable;
		
		foreach ($roleQuery as $role) 
		{
			$rolesTable[] =  array(
				"id" => $role->getId(), 
				"name" => $role->getName(), 
				"annotation" => $role->getAnnotation(),
				"users" => $role->countUsers());
		}
		
		$rolesTable = json_encode($rolesTable);
		include $this->viewPath . 'roles.php';
	}
	
	public function permissions()
	{
		$permissionQuery = Base\PermissionQuery::create()->find();
		$permissionsTable;
		
		foreach ($permissionQuery as $permission) 
		{
			$permissionsTable[] =  array(
				"name" => $permission->getName(), 
				"annotation" => $permission->getAnnotation());
		}
		$permissionsTable = json_encode($permissionsTable);
		include $this->viewPath . 'permissions.php';
	}
	
}
?>
