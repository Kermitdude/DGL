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
		include $this->viewPath . 'users.php';
	}
	
}
?>
