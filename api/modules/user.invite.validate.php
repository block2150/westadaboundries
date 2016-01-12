<?php

require_class("invite.php");

$code = $_POST["code"];

$Invite = new Invite($code);

if ($Invite->status == "0")
{
    $Invite->status = 1;
    $Invite->update();
    echo '{"status":"success","message":"Your invite code has been validated"}';
}
else
{
    if ($Invite->status == "1")
    {
        echo '{"status":"failed","message":"Sorry but the invite code you entered has already been used.  Please check your information and try again.  If you keep having this problem, you might need to ask for a different invite code."}';
    }
    else
    {
        echo '{"status":"failed","message":"Sorry but there was a problem with the invite code you entered.   Please check your code and try again."}';
    }
}