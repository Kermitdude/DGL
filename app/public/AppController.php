<?php
namespace DigitalGaming;
	
require_once dirname(__FILE__) . '/../bootstrap.php';

class AppController
{
	const DEFAULT_CONTROLLER     = "DigitalGaming\IndexController";
    const DEFAULT_ACTION         = "index";
	
	protected $controllersArray  = array(
		"DigitalGaming\LoginController", 
		"DigitalGaming\ElementsController",
		"DigitalGaming\GamesController", 
		"DigitalGaming\EmailController", 
		"DigitalGaming\AdminController", 
		"DigitalGaming\WatchController", 
		"DigitalGaming\UsersController", 
		"DigitalGaming\IndexController");
    protected $controller        = self::DEFAULT_CONTROLLER;
    protected $action            = self::DEFAULT_ACTION;
    protected $params            = array();
	
	public $instance;
	
	public function __construct()
	{
        if (isset($_POST['controller'])) 
		{
            $this->setController($_POST['controller']);
        }
        if (isset($_POST['action'])) 
		{
            $this->setAction($_POST['action']);
        }
        if (isset($_POST['params']))
		{
            $this->setParams($_POST['params']);
        }
	}
	
	public function setController($controller)
	{
		$controller = 'DigitalGaming\\' . trim($controller);
		if (!class_exists("$controller"))
		{
			$this->notFound();
		}
        $this->controller = $controller;
        return $this;
	}
     
    public function setAction($action) 
	{
        $reflector = new \ReflectionClass($this->controller);
        if (!$reflector->hasMethod($action)) 
		{
			$this->notFound();
        }
        $this->action = $action;
        return $this;
    }
     
    public function setParams(array $params) {
        $this->params = $params;
        return $this;
    }
	
	public function announce($message)
	{
		call_user_func_array(array(new ElementsController(), "announce"), array($message));
	}
	
	public function notFound()
	{
		$this->announce("That resource was not found on this server.");
		die();
	}
	
	public function run()
	{
		if (in_array($this->controller, $this->controllersArray))  // Screen input
		{
			$this->instance = new $this->controller;
			
			if (is_callable(array($this->instance, $this->action))) // Method is callable
			{
				$return = call_user_func_array(array($this->instance, $this->action), $this->params);
				if ($return) $this->announce($return);
			}
			else $this->notFound();
		}
		else $this->notFound();
	}
}

if (isset($_POST['controller']))
{
	$app = new AppController();
	$app->run();
}
?>