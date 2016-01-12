<?php

require_class("messages.php");

if ($_SESSION['user_id'])
{
	$Message = new Message();	
	echo $Message->DeleteMessage($_SESSION['user_id'], $_POST['message_id']);
}