<?php defined('SYSPATH') or die('No direct access allowed.');

abstract class Auth extends Kohana_Auth
{
    public function login($username, $password, $remember = FALSE)
    {
	$dir = APPPATH.'cache/tracker';

	$ip = $_SERVER['REMOTE_ADDR'];
	if ( ! $ip)
	    return FALSE;

	$arr = explode('.', $ip);
	$keep = implode('/', arr::flatten(array($dir, $arr)));
	unset($arr[3]);
	$keepdir = implode('/', arr::flatten(array($dir, $arr)));
	if( ! is_dir($keepdir))
	{
	    mkdir($keepdir, 0755, TRUE);
	}
	if ( ! is_file($keep))
	{
	    file_put_contents($keep, '1');
	} else
	{
	    $stat = stat($keep);
	    if($stat['mtime'] > time()-1200)
	    {
		$x = file_get_contents($keep);
		$x = (int) trim($x);
		if ($x >= 3)
		{
		    throw new HTTP_Exception_403(__('Too many login attempts. Please wait 20 mins until next login attempt.'));
		    return FALSE;
		}

		file_put_contents($keep, $x+1);
	    }else
	    {
		file_put_contents($keep, '1');
	    }
	}
	$login = parent::login($username, $password, $remember);
	if ($login)
	{
	    unlink($keep);
	}
	return $login;
    }
}
