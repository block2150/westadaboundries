<?php
require_class("portfolio.php");


if ($_SESSION['user_contributor'] == "1") {	
	
	$user_id = $_POST["id"];
	

	$portfolio = new Portfolio($user_id);	
	$portfolio->toggleFeatured();
	
	echo '{"status":"success","message":"The portfolio you requested has been featured succesfully."}';
}
else
{
	echo '{"status":"success","message":"Permission denied."}';
}

