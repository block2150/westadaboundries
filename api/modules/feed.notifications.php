<?php


require_class("feed.php");

$ActivityFeed = new ActivityFeed();
$ActivityFeed->user_id = $_SESSION["user_id"];

echo $ActivityFeed->listNotifications();