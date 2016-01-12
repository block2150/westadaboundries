<?php


require_class("images.php");

$image_id = $_POST["id"];
$description = $_POST["description"];

$image = New Images($image_id);
$image->description = $description;
$image->category_id = 1;
$image->update();

echo '{"status":"success","message":"Image was moved to your portfolio succesfully"}';
