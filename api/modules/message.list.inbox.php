<?php
require_class("messages.php");

if ($_SESSION['user_id'])
{
	$Message = new Message();
	
	echo $Message->InboxMessages($_SESSION['user_id']);
}