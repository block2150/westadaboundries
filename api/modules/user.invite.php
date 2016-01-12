<?php

require_class("user.php");
require_class("mail.php");
require_class("invite.php");

$inviteName = $_POST["name"];
$inviteEmail = $_POST["email"];


$inviteCode = genPassword();

$User = new User($_SESSION['user_id']);

if ($User->id)
{
    $Invite = new Invite();

    $Invite->by = $_SESSION['user_id'];
    $Invite->code = $inviteCode;
    $Invite->name = $inviteName;
    $Invite->email = $inviteEmail;
    $Invite->create();


    $Mail = new Mail();

    $kv = array(
        '{InviteCode}' => $inviteCode,
        '{InviteName}' => $inviteName,
        '{InviteEmail}' => $inviteEmail,
        '{UserEmail}' => $User->email,
        '{CurrentDomain}' => $_SERVER['SERVER_NAME']
    );

    $Mail->to = $inviteEmail;
    $Mail->from = "block2150@gmail.com";
    $Mail->fromName = "Rryan Riley";
    $Mail->suject = "Fotoluv.com Invitation";
    $Mail->bodyPath = "/views/messages/email/user.invite.html";
    $Mail->arrayKV = $kv;
    $MailStatus = $Mail->Send();

    if ($MailStatus == "success")
    {
        echo '{"status":"success","message":"Your Fotoluv.com invetation was successfully sent to '.$inviteName.'"}';
    }
    else
    {
        echo '{"status":"failed","message":"'.$MailStatus.'"}';
    }
}
else
{
    echo '{"status":"failed","message":"Sorry, but there was a problem trying to send this invitation.  Please check the information you entered and try again."}';
}
