<?php

namespace DigitalGaming;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Session
{
	public function __construct()
	{
		$this->update();
	}
	
	public static function update()
	{
		// Regenerate session
		if(!isset($_SESSION)) session_start();
		session_regenerate_id(true);
		
		$recycle = 1800; // seconds before regenerating session ID
		$timeout = 1200; // seconds before expiring session
		
		 // recycle session
		if (isset($_SESSION['LAST_RECYCLE'])) 
		{
			
			if ( (time() - $_SESSION['LAST_RECYCLE']) > $recycle)
			{ 
				session_regenerate_id(true); 
				$_SESSION['LAST_RECYCLE'] = time();
			}
			
		}
		else $_SESSION['LAST_RECYCLE'] = time();
		
		// expire session
		if (isset($_SESSION['LAST_UPDATE']))
		{
			if ( (time() - $_SESSION['LAST_UPDATE']) > $timeout)
			{
				Session::end();
			}
			
		}
		$_SESSION['LAST_UPDATE'] = time(); 
	}

	public static function end()
	{
		if(isset($_SESSION))
		{
			session_unset();
			session_destroy();
		}
	}

	public static function set($key, $value)
	{           
		return $_SESSION[$key] = $value;
	}

	public static function get($key)
	{
		if(!isset($_SESSION[$key])) {
			return false;
		}

		return $_SESSION[$key];         
	}
}
