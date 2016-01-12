<?php


require_class("relationship.php");

$Relationship = new Relationship($_SESSION['user_id']);

echo $Relationship->listRelationships();