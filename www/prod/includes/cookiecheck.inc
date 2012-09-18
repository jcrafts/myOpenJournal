<?php

function CookieIsSet($location)
{
	if (isset($_COOKIE['username']))
	{
		header('Location: '.$location);
		return 1;
	}
	return 0;
}

function Cookie_IsSet()
{
	if (isset($_COOKIE['username']))
	{
		return 1;
	}
	return 0;
}

function CookieIsNotSet($location)
{
	if (!isset($_COOKIE['username']))
	{
		if($location=='login.php')
		{
			$_SESSION['login_request']=true;
		}
		header('Location: '.$location);
		return 1;
	}
	return 0;
}

function Cookie_IsNotSet()
{
	if (isset($_COOKIE['username']))
	{
		return 0;
	}
	return 1;
}



?>
