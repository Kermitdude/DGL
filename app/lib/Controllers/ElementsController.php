<?php
namespace DigitalGaming;

class ElementsController
{
	protected $viewPath;
	
	public function __construct()
	{
		$this->viewPath = $_SERVER['DOCUMENT_ROOT'] . "elements/";
	}
	
	public function menu()
	{
		include $this->viewPath . 'menu.php';
	}
	
	public function adminMenu()
	{
		include $this->viewPath . 'admin_menu.php';
	}
	
	public function announce($message = "Unknown Error")
	{
		include $this->viewPath . 'announce.php';
	}
	
}
?>
