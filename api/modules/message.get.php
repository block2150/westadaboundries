<?php
require_class("messages.php");

if ($_SESSION['user_id'])
{
	$Message = new Message();
	
	$Message->Read($_SESSION['user_id'], $_POST['message_id']);
	
	echo $Message->GetMessage($_POST['message_id']);
}