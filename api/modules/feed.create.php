<?php

$type = $_POST["type"];
$actor = $_SESSION['user_id'];
$target = $_POST["target"];
$parent_id = $_POST["parent_id"];
$kv = array();

$ActivityFeedCreate = new ActivityFeedCreate($type, $actor, $target, $parent_id, $kv);