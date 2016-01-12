<?php
/**
 *
 * Feed Notification Section
 *
 */
 
$user_id = $_POST["user_id"];
$update = makeLinks($_POST["update"]);

if ($user_id == "")
{
	$type = "feed.share.update";
	$actor = $_SESSION['user_id'];
	$target = "";
	$parent_id = "";
	$kv = array(
		'{update}' => $update
	);
}
else
{
	$type = "feed.share.user";
	$actor = $_SESSION['user_id'];
	$target = $user_id;
	$parent_id = "";
	$kv = array(
		'{update}' => $update
	);
}
$ActivityFeedCreate = new ActivityFeedCreate($type, $actor, $target, $parent_id, $kv);