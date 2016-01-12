<?php


require_class("user.php");
require_class("profile.php");

$user_id = $_SESSION['user_id'];


if ($_SESSION["profile_username"] != "")
{
    $User = New User();
    $User->getCommunityProfile($_SESSION["profile_username"]);

    $user_id = $User->id;
}

$Profile = new Profile($user_id);

echo $Profile->toJson();