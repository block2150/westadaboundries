<?php


require_class("user.php");

$User = new User();

$User->login($_POST["login"], $_POST["password"]);

if ($User->id)
{
    echo $User->toJson();
}
else
{
    echo '{"status":"failed","message":"Your login was not successful.  Please double check your login information and try again."}';
}