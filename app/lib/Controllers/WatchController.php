<?php
namespace DigitalGaming;

class WatchController
{
	protected $viewPath;
	
	public function __construct()
	{
		$this->viewPath = $_SERVER['DOCUMENT_ROOT'] . "views/Watch/";
	}
	
	public function index()
	{
		include $this->viewPath . 'index.php';
	}	
	
	public function live()
	{
		include $this->viewPath . 'live.php';
	}	
	
	public function recent()
	{
		include $this->viewPath . 'recent.php';
	}	
	
	public function game($game)
	{
		include $this->viewPath . 'index.php';
	}
	
}
?>
