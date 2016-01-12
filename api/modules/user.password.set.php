<?php


require_class("user.php");

$User = new User($_SESSION['user_id']);

$CurrentPassword = $_POST["current"];
$NewPassword = $_POST["new"];

if ($User->id)
{
    if ($CurrentPassword != $User->password)
    {
        echo '{"status":"failed","message":"Sorry, but the current password you typed does not match the one we have on file.  Please make sure the current password you enter is the one you use to access your account."}';
    }
    else
    {
    	$User->setPassword($NewPassword);
        echo '{"status":"success","message":"Your password has been successfully changed."}';
    }
}
else
{
    echo '{"status":"failed","message":"There was a problem trying to save your information to our system.  Please double check what you have entered and try again.  If you continue to have a problem with feature please use the feedback form below to notify our customer support."}';
}