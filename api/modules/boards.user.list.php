<?php
require_class("user.php");
require_class("boards.php");

$user_id = $_SESSION["user_id"];

$Boards = new Boards($user_id);

echo json_encode($Boards->boards);