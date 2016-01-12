<?php
/**
 *
 * Feed Notification Section
 *
 */
 
$type = "feed.comment.post";
$actor = $_SESSION['user_id'];
$target = $_POST["target"];
$parent_id = $_POST["parent_id"];
$comment = makeLinks($_POST["comment"]);

$kv = array(
	'{comment}' => $comment
);
$ActivityFeedCreate = new ActivityFeedCreate($type, $actor, $target, $parent_id, $kv);