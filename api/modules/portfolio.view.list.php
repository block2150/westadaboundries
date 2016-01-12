<?php
require_class("portfolio.php");

$user_id = $_SESSION["user_id"];

$Portfolio = new Portfolio($_SESSION["user_id"]);
echo $Portfolio->listViews();
