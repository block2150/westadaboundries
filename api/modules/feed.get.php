<?php


require_class("feed.php");

$ActivityFeed = new ActivityFeed();
$ActivityFeed->viewer = $_SESSION["user_id"];
$ActivityFeed->user_id = $_SESSION["user_id"];

echo $ActivityFeed->getFeed($_POST["feed_id"]);