<?php

require_class("messages.php");


if ($_SESSION['user_id'])
{
	$Message = new Message();
	$Message->from = $_SESSION['user_id'];
	$Message->message = $_POST['message'];
	if (is_array($_POST['to']))
	{
		$Message->to = $_POST['to'];
	}
	else
	{
		$Message->to = objectToArray(json_decode($_POST['to']));
	}
	$Message->parent_id = $_POST['parent_id'];
	
	echo $Message->NewMessage();
}


function objectToArray($d) {
	if (is_object($d)) {
		$d = get_object_vars($d);
	}
	if (is_array($d)) {
		return array_map(__FUNCTION__, $d);
	}
	else {
		return $d;
	}
}
