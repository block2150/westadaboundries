<?php

require_class("profile.php");

$Profile = new Profile($_SESSION['user_id']);

$id = $_POST["id"];
$name = $_POST["name"];
$location = $_POST["location"];
$jobdate = $_POST["year"] . "-" . $_POST["month"] . "-" . $_POST["day"];
$details = $_POST["details"];

$Profile->setJob($id, $name, $location, $jobdate, $details);

if ($Profile->user_id)
{
    echo '{"status":"success","message":"The job experience you entered has been saved."}';
}
else
{
    echo '{"status":"failed","message":"There was a problem trying to save the information to our system.  Please double check what you have entered and try again.  If you continue to have a problem with feature please use the feedback form below to notify our customer support."}';
}