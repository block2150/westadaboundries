<?php

require_class("user.php");
require_class("profile.php");
require_class("mail.php");
require_class("proofs.php");

$inviteName = $_POST["name"];
$inviteEmail = $_POST["email"];

$uploadCode = genPassword();

$User = new User($_SESSION['user_id']);

if ($User->id)
{
    $Proofs = new Proofs();
    $Profile = new Profile($User->id);

    $Proofs->by = $User->id;
    $Proofs->code = $uploadCode;
    $Proofs->name = $inviteName;
    $Proofs->email = $inviteEmail;
    $Proofs->create();

    $Mail = new Mail();

    $kv = array(
        '{UploadCode}' => $uploadCode,
        '{InviteName}' => $inviteName,
        '{InviteEmail}' => $inviteEmail,
        '{InviteEmail}' => $inviteEmail,
        '{Fullname}' => $Profile->fullname,
        '{UserEmail}' => $User->email,
        '{CurrentDomain}' => $_SERVER['SERVER_NAME']
    );

    $Mail->to = $inviteEmail;
    $Mail->from = "block2150@gmail.com";
    $Mail->fromName = "Rryan Riley";
    $Mail->suject = "Fotoluv.com Upload Code";
    $Mail->bodyPath = "/views/messages/email/user.upload.code.html";
    $Mail->arrayKV = $kv;
    $MailStatus = $Mail->Send();

    if ($MailStatus == "success")
    {
        echo '{"status":"success","message":"Your Fotoluv.com upload code was successfully sent to '.$inviteName.'"}';
    }
    else
    {
        echo '{"status":"failed","message":"'.$MailStatus.'"}';
    }
}
else
{
    echo '{"status":"failed","message":"Sorry, but there was a problem trying to send this upload code.  Please check the information you entered and try again."}';
}
