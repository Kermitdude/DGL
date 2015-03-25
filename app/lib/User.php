<?php

namespace DigitalGaming;

use DigitalGaming\Base\User as BaseUser;

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
class User extends BaseUser
{
	public function __get($name) 
	{
        return $this->$name;
    }
	
	public function getUserObject($username)
	{
		if ($username)
		{
			$user = Base\UserQuery::create()->findOneByName($username);
			return $user;
		}
	}
	
	public function hashPassword($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
	
	public function login($username, $password)
	{
		$user = $this->getUserObject($username);
		if ($user)
		{
			$auth = password_verify($password, $user->password);
		
			Session::set('userid', $user->id);
			Session::set('username', $user->name);
			
			return $auth;
		}
		return false;
	}
	
	public function logout()
	{
		Session::end();
	}
	
	public function isLogged()
	{
		return Session::get('username');
	}
	
    public static function isAuthorized($perm) 
	{
		return true;
    }
}
