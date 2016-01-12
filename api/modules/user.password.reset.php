<?php

require_class("user.php");
require_class("mail.php");

$email = mysql_real_escape_string($_POST["email"]);

$query = "SELECT * FROM users WHERE email = '$email'";

$result = mysql_query($query, $connection);

if(!empty($result)){
    $username = mysql_fetch_assoc($result);
}

$User = new User($username['id']);

if ($User->id)
{
    $User->setPassword(genPassword());

    $Mail = new Mail();

    $kv = array(
        '{Password}' => $User->password,
        '{CurrentDomain}' => $_SERVER['SERVER_NAME']
    );

    $Mail->to = $User->email;
    $Mail->from = "block2150@gmail.com";
    $Mail->fromName = "Rryan Riley";
    $Mail->suject = "Password reset from Fotoluv.com";
    $Mail->bodyPath = "/views/messages/email/reset.password.html";
    $Mail->arrayKV = $kv;

    $MailStatus = $Mail->Send();

    if ($MailStatus == "success")
    {
        echo '{"status":"success","message":"Your new password is waiting for you in your email!"}';
    }
    else
    {
        echo '{"status":"failed","message":"$MailStatus"}';
    }
}
else
{
    echo '{"status":"failed","message":"Sorry, but we were not able to find your email.  Check your information and please try again."}';
}
