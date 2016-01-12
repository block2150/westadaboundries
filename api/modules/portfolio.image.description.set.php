<?php


require_class("images.php");

$image_id = $_POST["id"];
$description = $_POST["description"];

$image = New Images($image_id);
$image->description = $description;

$image->update();

echo '{"status":"success","message":"There were no images found for this portfolio."}';
