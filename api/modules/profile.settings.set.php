<?php

require_class("profile.php");

$user_id = $_SESSION["user_id"];
$setting = $_POST["setting"];
$status = $_POST["status"];

$Profile = new Profile($user_id);

switch ($setting) {
    case "public-profile";
        $Profile->public_profile = $status;
        break;
}

$Profile->update();

echo '{"status":"success","message":"The portfolio settings where saved."}';