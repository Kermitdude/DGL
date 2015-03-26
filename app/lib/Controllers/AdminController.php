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
		$userQuery = Base\UserQuery::create()->orderByCreatedAt();
		$dateFormat = "Y-m-d H:i:s";
		$usersTable;
		
		foreach ($userQuery as $user) 
		{
			$usersTable[] =  array(
				"name" => $user->getName(), 
				"email" => $user->getEmail(), 
				"created_at" => $user->getCreatedAt()->format($dateFormat));
		}
		$usersTable = json_encode($usersTable);
		include $this->viewPath . 'users.php';
	}
	
	public function roles()
	{
		$roleQuery = Base\RoleQuery::create()->orderByCreatedAt();
		$dateFormat = "Y-m-d H:i:s";
		$rolesTable;
		
		foreach ($roleQuery as $role) 
		{
			$rolesTable[] =  array(
				"name" => $role->getName(), 
				"annotation" => $role->getAnnotation(), 
				"created_at" => $role->getCreatedAt()->format($dateFormat));
		}
		$rolesTable = json_encode($rolesTable);
		include $this->viewPath . 'roles.php';
	}
	
	public function permissions()
	{
		$permissionQuery = Base\PermissionQuery::create()->orderByName();
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
