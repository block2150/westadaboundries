<?php

require_class("images.php");

$image_id = $_POST["id"];

$image = New Images($image_id);
$image->user_image_set();



$type = "portfolio.profile.image";
$actor = $_SESSION['user_id'];
$target = "";
$parent_id = "";
$kv = array();

$ActivityFeedCreate = new ActivityFeedCreate($type, $actor, $target, $parent_id, $kv);

echo '{"status":"success","message":"There were no images found for this portfolio."}';
