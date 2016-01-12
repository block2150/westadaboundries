<?php


require_class("feed.php");

$user_id = $_POST["user_id"];
if ($user_id == "")
{
	$user_id = $_SESSION["user_id"];
}


$ActivityFeed = new ActivityFeed();
$ActivityFeed->viewer = $_SESSION["user_id"];
$ActivityFeed->user_id = $user_id;

echo $ActivityFeed->listUserFeed();