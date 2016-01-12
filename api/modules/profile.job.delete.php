<?php

require_class("profile.php");

$Profile = new Profile($_SESSION['user_id']);

$Profile->deleteJob($_POST["id"]);

if ($Profile->id)
{
    echo '{"status":"success","message":"The job you selected has been deleted."}';
}
else
{
    echo '{"status":"failed","message":"There was a problem deleting the job you selected.  Please double check what you have entered and try again.  If you continue to have a problem with feature please use the feedback form below to notify our customer support."}';
}