<?php


require_class("profile.php");


$Profile = new Profile($_SESSION['user_id']);

echo $Profile->kvJson();