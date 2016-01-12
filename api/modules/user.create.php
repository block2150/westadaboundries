<?php
require_class("user.php");
require_class("invite.php");


if ($registration_settings == 0)
{
    echo '{"status":"failed","message":"We are sorry but regitration is currently closed.  Please check back again to see if we have opened regitration to everyone."}';
    exit;
}

$validCode = 1;
$code = $_POST["code"];
$Invite = new Invite($code);

if ($Invite->status != "1")
{
    $validCode = 0;
}

if ($registration_settings == 1 && $validCode == 0)
{
    echo '{"status":"failed","message":"We are sorry but registration is only available to those who have an invite code.  Please check back again to see if we have opened regitration to everyone."}';
    exit;
}



{
    $User = new User();

    $User->create($_POST["username"], $_POST["email"], $_POST["password"]);

    if ($User->id)
    {
        echo $User->toJson();
    }
    else
    {
        echo '{"status":"failed","message":"There was a problem trying to create your account.  Please check your information and try again."}';
    }
}