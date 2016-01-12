<?php

require_class("user.php");
require_class("mail.php");
require_class("relationship.php");

$actor = $_POST["user_id"];
$target = $_SESSION['user_id'];
$status = $_POST['status'];

$Relationship = new Relationship();
$Relationship->actor = $actor;
$Relationship->target = $target;
$Relationship->status = $status;
$Relationship->update();


$User = new User($_SESSION['user_id']);
$User->getInviteCount();


if ($status == 3)
{
	$Actor = new User($actor);
	$Target = new User($target);

    $Mail = new Mail();

    $kv = array(
        '{ActorFullname}' => $Actor->fullname,
        '{ActorUsername}' => $Actor->username,
        '{ActorTypeName}' => $Actor->type_name,
        '{Note}' => $note,
        '{TargetFullname}' => $Target->fullname,
        '{TargetUsername}' => $Target->username,
        '{CurrentDomain}' => $_SERVER['SERVER_NAME']
    );

    $Mail->to = $Actor->email;
    $Mail->from = "block2150@gmail.com";
    $Mail->fromName = "Fotoluv Connections";
    $Mail->suject = $Target->fullname . " accepted your invite connetion on Fotoluv.com";
    $Mail->bodyPath = "/views/messages/email/connect.accepted.html";
    $Mail->arrayKV = $kv;
    $MailStatus = $Mail->Send();
	
	
	$type = "relationship.connected";
	$actor = $actor;
	$target = $target;
	$parent_id = "";
	$kv = array();
	
	$ActivityFeedCreate = new ActivityFeedCreate($type, $actor, $target, $parent_id, $kv);
}