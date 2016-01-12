<?php

require_class("images.php");


$clearProfileImage = $_POST["clearProfileImage"];

$image = New Images($_POST["id"]);
$image->delete($clearProfileImage);

echo '{"status":"failed","message":"There were no images found for this portfolio."}';
