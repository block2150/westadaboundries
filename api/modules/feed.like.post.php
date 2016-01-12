<?php

require_class("feed.php");

$ActivityFeed = new ActivityFeed();

$feed_id = $_POST["feed_id"];
$user_id = $_SESSION['user_id'];
$like = $_POST["like"];

$ActivityFeed->LikePost($user_id, $feed_id, $like);