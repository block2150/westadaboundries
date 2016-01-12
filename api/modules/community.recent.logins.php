<?php


require_class("community.php");
$Community = new Community();
$Community->recentLogins();

echo json_encode($Community->logins);