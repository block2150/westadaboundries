<?php


require_class("user.php");

$User = new User($_SESSION['user_id']);

$User->email = $_POST["email"];
$User->save();

if ($User->id)
{
    echo '{"status":"success","message":"Your account information as been saved."}';
}
else
{
    echo '{"status":"failed","message":"There was a problem trying to save your information to our system.  Please double check what you have entered and try again.  If you continue to have a problem with feature please use the feedback form below to notify our customer support."}';
}