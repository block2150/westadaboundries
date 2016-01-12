<?php
require_class("portfolio.php");

$user_id = $_SESSION["user_id"];
$portfolio_id = $_SESSION["profile_id"];

$Portfolio = new Portfolio();
$Portfolio->userView($user_id, $portfolio_id);


echo '{"status":"success","message":"Portfolio view saved"}';