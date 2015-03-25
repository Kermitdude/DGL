<?php
namespace DigitalGaming;

class GamesController
{
	protected $title = "Digital Gaming League";
	protected $viewPath;
	
	public function __construct()
	{
		$this->viewPath = $_SERVER['DOCUMENT_ROOT'] . "views/Games/";
	}
	
	public function index()
	{
		include $this->viewPath . 'index.php';
	}
	
}
?>
