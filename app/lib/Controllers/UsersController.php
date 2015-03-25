<?php
namespace DigitalGaming;

class UsersController
{
	protected $title = "Profile";
	protected $viewPath;
	
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
	
}
?>
