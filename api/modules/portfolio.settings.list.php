<?php
require_class("user.php");
require_class("portfolio.php");

$user_id = $_SESSION["user_id"];


if ($_SESSION["profile_username"] != "")
{
    $User = new User();
    $User->getCommunityProfile($_SESSION["profile_username"]);
    $user_id = $User->id;
}

$portfolio = new Portfolio($user_id);

echo $portfolio->toJson();