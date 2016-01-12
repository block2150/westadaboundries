<?php


require_class("images.php");

$storeFolder = 'data/users';
$userFolder = $_SESSION['user_id'];

$targetPath = $_SERVER['DOCUMENT_ROOT'] . "/" . $storeFolder . "/" . substr($userFolder, 0, 1) . "/";

if (!file_exists($targetPath)) {
    mkdir($targetPath);
}
$targetPath = $_SERVER['DOCUMENT_ROOT'] . "/" . $storeFolder . "/" . substr($userFolder, 0, 1) . "/" . substr($userFolder, 0, 2) . "/";

if (!file_exists($targetPath)) {
    mkdir($targetPath);
}

$targetPath = $_SERVER['DOCUMENT_ROOT'] . "/" . $storeFolder . "/" . substr($userFolder, 0, 1) . "/" . substr($userFolder, 0, 2) . "/" . $userFolder . "/";

if (!file_exists($targetPath)) {
    mkdir($targetPath);
}

if (!empty($_FILES)) {

    $image = New Images();
    $image->user_id = $_SESSION['user_id'];
    $image->file_name = $_FILES['file']['name'];
    $image->add();

    $tempFile = $_FILES['file']['tmp_name'];

    $targetFile =  $targetPath . $_FILES['file']['name'];


    move_uploaded_file($tempFile,$targetFile);

    $src_image = $targetFile;
    $dest_image = $targetPath . "icon_" . $_FILES['file']['name'];
    square_crop($src_image, $dest_image, 50, 90);

    $src_image = $targetFile;
    $dest_image = $targetPath . "small_" . $_FILES['file']['name'];
    square_crop($src_image, $dest_image, 125, 90);

    $dest_image = $targetPath . "thumb_" . $_FILES['file']['name'];
    square_crop($src_image, $dest_image, 250, 90);

    $dest_image = $targetPath . "profile_" . $_FILES['file']['name'];
    square_crop($src_image, $dest_image, 400, 90);
	

    echo '{"status":"success","message":"File upload path: ' . $targetFile .'"}';
}




function square_crop($src_image, $dest_image, $thumb_size = 64, $jpg_quality = 90) {

    // Get dimensions of existing image
    $image = getimagesize($src_image);

    // Check for valid dimensions
    if( $image[0] <= 0 || $image[1] <= 0 ) return false;

    // Determine format from MIME-Type
    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

    // Import image
    switch( $image['format'] ) {
        case 'jpg':
            $image_data = imagecreatefromjpeg($src_image);
            break;
        case 'jpeg':
            $image_data = imagecreatefromjpeg($src_image);
            break;
        case 'png':
            $image_data = imagecreatefrompng($src_image);
            break;
        case 'gif':
            $image_data = imagecreatefromgif($src_image);
            break;
        default:
            // Unsupported format
            return false;
            break;
    }

    // Verify import
    if( $image_data == false ) return false;

    // Calculate measurements
    if( $image[0] > $image[1] )
    {
        // For landscape images
        $x_offset = ($image[0] - $image[1]) / 2;
        $y_offset = 0;
        $square_size = $image[0] - ($x_offset * 2);
    } else {
        // For portrait and square images
        $x_offset = 0;
        $y_offset = ($image[1] - $image[0]) / 2;
        $square_size = $image[1] - ($y_offset * 2);
    }


    // Resize and crop
    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
    if( imagecopyresampled(
        $canvas,
        $image_data,
        0,
        0,
        $x_offset,
        $y_offset,
        $thumb_size,
        $thumb_size,
        $square_size,
        $square_size
    )) {
        // Create thumbnail
        switch( strtolower(preg_replace('/^.*\./', '', $dest_image)) ) {
            case 'jpg':
                return imagejpeg($canvas, $dest_image, $jpg_quality);
                break;
            case 'jpeg':
                return imagejpeg($canvas, $dest_image, $jpg_quality);
                break;
            case 'png':
                return imagepng($canvas, $dest_image);
                break;
            case 'gif':
                return imagegif($canvas, $dest_image);
                break;
            default:
                // Unsupported format
                return false;
                break;
        }

    } else {
        return false;
    }

}