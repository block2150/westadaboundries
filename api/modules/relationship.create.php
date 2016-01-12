<?php

require_class("user.php");
require_class("mail.php");
require_class("relationship.php");


$note = $_POST["note"];


$actor = $_SESSION['user_id'];

$target = $_POST["profile_id"];
if ($target == "")
{
	$target = $_SESSION['profile_id'];
}

$Actor = new User($actor);
$Target = new User($target);

if ($Target->id)
{
    $Relationship = new Relationship();

    $Relationship->actor = $actor;
    $Relationship->target = $target;
    $Relationship->note = $note;
    $Relationship->status = 1; // Pending
    $Relationship->create();


    $Mail = new Mail();

    $kv = array(
        '{ActorFullname}' => $Actor->fullname,
        '{ActorUsername}' => $Actor->username,
        '{ActorTypeName}' => $Actor->type_name,
        '{Note}' => $note,
        '{TargetFullname}' => $Target->fullname,
        '{CurrentDomain}' => $_SERVER['SERVER_NAME']
    );

    $Mail->to = $Target->email;
    $Mail->from = "block2150@gmail.com";
    $Mail->fromName = "Fotoluv Connections";
    $Mail->suject = $Actor->fullname . " wants to connect with you on Fotoluv.com";
    $Mail->bodyPath = "/views/messages/email/connect.invite.html";
    $Mail->arrayKV = $kv;
    $MailStatus = $Mail->Send();

    if ($MailStatus == "success")
    {
        echo '{"status":"success","message":"Your Fotoluv.com invetation was successfully sent to '.$Target->fullname.'"}';
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
