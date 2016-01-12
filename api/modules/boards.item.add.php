<?php
require_class("boards.php");

$user_id = $_SESSION["user_id"];

$Boards = new Boards($user_id);

$Boards->board_id = $_POST["board_id"];
$Boards->board = $_POST["board"];
$Boards->file_name = $_POST["file_name"];
$Boards->comments = $_POST["comments"];
$Boards->source = $_POST["source"];
$Boards->source_id = $_POST["source_id"];

$Boards->add();

/**
 *
 * Feed Notification Section
 *
 */



$type = "board.luv.added";
$actor = $_SESSION['user_id'];
$target = $_POST["source_id"];
$parent_id = "";
$kv = array(
	'{source}' => $_POST["source"],
	'{source_id}' => $_POST['source_id'],
	'{file_name}' => $_POST['file_name'],
	'{board}' => $_POST['board'],
	'{board_id}' => $_POST['board_id']
);

$ActivityFeedCreate = new ActivityFeedCreate($type, $actor, $target, $parent_id, $kv);