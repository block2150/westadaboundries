<?php

require_class("feed.php");

$Feed = new ActivityFeed();

$user_id = $_POST["user_id"];
$type_id = $_POST["type_id"];
$type = $_POST["type"];
$comments = $_POST["comments"];
$reported_by = $_SESSION['reported_by'];

$Feed->reportAbuse($user_id, $type_id, $type, $comments, $reported_by);
