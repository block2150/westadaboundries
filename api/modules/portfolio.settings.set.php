<?php
require_class("portfolio.php");

$user_id = $_SESSION["user_id"];
$setting = $_POST["setting"];
$status = $_POST["status"];

$portfolio = New Portfolio($user_id);

switch ($setting) {
    case "public-portfolio";
        $portfolio->public_portfolio = $status;
        break;
    case "show-descriptions":
        $portfolio->show_descriptions = $status;
        break;
}

$portfolio->set();

echo '{"status":"success","message":"The portfolio settings where saved."}';