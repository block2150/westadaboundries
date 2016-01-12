<?php

require_class("profile.php");

$profile = new Profile();
$profile->getFeatured();

echo $profile->toJson();