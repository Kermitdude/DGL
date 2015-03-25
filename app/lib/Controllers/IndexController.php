<?php
namespace DigitalGaming;

class IndexController
{
	protected $title = "Digital Gaming League";
	protected $viewPath;
	
	public function __construct()
	{
		$this->viewPath = $_SERVER['DOCUMENT_ROOT'] . "views/Index/";
	}
	
	public function index()
	{
		include $this->viewPath . 'home.php';
	}
	
}
?>
