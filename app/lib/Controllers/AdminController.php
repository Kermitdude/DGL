<?php
namespace DigitalGaming;

class AdminController
{
	public $locked;
	protected $viewPath;
	
	public function __construct()
	{
		$this->viewPath = $_SERVER['DOCUMENT_ROOT'] . "views/Admin/";
		$this->locked = array(
			"index",
			"users",
			"roles",
			"permissions",
		);		
	}
	
	public function index()
	{
		include $this->viewPath . 'index.php';
	}
	
	public function users()
	{
		$dateFormat = "F j, Y, g:i a";
		$usersTable = [];
		$rolesList = [];
		
		// Get roles
		$roleQuery = Base\RoleQuery::create()->find();
				
		foreach ($roleQuery as $role) 
		{
			$rolesList[] = array( 'value' => $role->getId(), 'text' => $role->getName());
		}
		
		// Get roles for each user
		$userQuery = Base\UserQuery::create()->find();
		foreach ($userQuery as $user) 
		{
			$roles = $user->getRoles();
			$userRoles = [];
			foreach ($roles as $role)
			{
				$userRoles[] = $role->getId();
			}
			
			$usersTable[] =  array(
				"id" => $user->getId(), 
				"name" => $user->getName(), 
				"email" => $user->getEmail(), 
				"created_at" => $user->getCreatedAt()->format($dateFormat),
				"roles" => $userRoles
				);
		}
		
		$userStats = json_encode(User::getRecentRegistrations());
		$rolesList = json_encode($rolesList);
		$usersTable = json_encode($usersTable);
		
		include $this->viewPath . 'users.php';
	}
	
	public function roles()
	{
		$roleQuery = Base\RoleQuery::create()->find();
		$rolesTable = [];
		$permsList = [];
		
		// Get list of all permissions
		$permsQuery = Base\PermissionQuery::create()->find();
				
		foreach ($permsQuery as $perm) 
		{
			$permsList[] = array( 'value' => $perm->getId(), 'text' => $perm->getName());
		}
		
		// Get role details
		foreach ($roleQuery as $role) 
		{
			$rolePerms = [];
			$perms = $role->getPermissions();
			foreach ($perms as $perm)
			{
				$rolePerms[] = $perm->getId();
			}
			
			$rolesTable[] =  array(
				"id" => $role->getId(), 
				"name" => $role->getName(), 
				"annotation" => $role->getAnnotation(),
				"users" => $role->countUsers(),
				"permissions" => $rolePerms);
		}
		
		$permsList = json_encode($permsList);
		$rolesTable = json_encode($rolesTable);
		include $this->viewPath . 'roles.php';
	}
	
	public function permissions()
	{
		$permissionQuery = Base\PermissionQuery::create()->find();
		$permissionsTable = [];
		
		foreach ($permissionQuery as $permission) 
		{
			$permissionsTable[] =  array(
				"id" => $permission->getId(), 
				"name" => $permission->getName(), 
				"annotation" => $permission->getAnnotation(),
				"roles" => $permission->countRoles());
		}
		$permissionsTable = json_encode($permissionsTable);
		include $this->viewPath . 'permissions.php';
	}
	
}
?>
