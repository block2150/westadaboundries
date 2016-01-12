<?php
require_class("messages.php");

if ($_SESSION['user_id'])
{
	$Message = new Message();
	
	echo $Message->Unread($_SESSION['user_id']);
}