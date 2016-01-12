<?php


require_class("profile.php");

$Profile = new Profile($_SESSION['user_id']);
$Profile->user_id = $_SESSION['user_id'];
$Profile->fullname = $_POST["profile-name"];
$Profile->location = $_POST["profile-location"];
$Profile->summary = $_POST["profile-summary"];
$Profile->type = $_POST["profile-type"];
$Profile->birthdate = $_POST["profile-year"] . "-" . $_POST["profile-month"] . "-" . $_POST["profile-day"];
$Profile->kv = $_POST;

$Profile->create();



/**
 *
 * Feed Notification Section
 *
 */

$type = "profile.updated";
$actor = $_SESSION['user_id'];
$target = "";
$parent_id = "";
$kv = array();

$ActivityFeedCreate = new ActivityFeedCreate($type, $actor, $target, $parent_id, $kv);

echo $Profile->toJson();