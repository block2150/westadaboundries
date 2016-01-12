<?php
require_class("user.php");

if ($_SESSION['user_id'])
{
	$q = $_POST["q"];
	if ($q == "")
	{
		$q = $_GET["q"];
	}
	
	$User = new User();
	$User->id = $_SESSION['user_id'];
	
	echo $User->Search($q);
}