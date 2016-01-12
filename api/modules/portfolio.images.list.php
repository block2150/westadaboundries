<?php

require_class("user.php");
require_class("images.php");



if ($_SESSION["profile_username"] != "")
{
    $User = New User();
    $User->getCommunityProfile($_SESSION["profile_username"]);

    $user_id = $User->id;
}
else
{
	$user_id = $_POST["user_id"];
}

if ($user_id == "")
{
	$user_id = $_SESSION['user_id'];
}

$category_id = $_POST["category_id"];

$image = new Images();
$image->user_id = $user_id;
if ($category_id != "")
{
	$image->category_id;
	$list = $image->listByCategory();
}
else
{
	$list = $image->all();
}

if ($list == null)
{
    echo '{"status":"failed","message":"There were no images found for this portfolio."}';
}
else
{
    echo json_encode($list);
}