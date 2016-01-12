<?php

// define absolute path to image folder
$image_folder = "/views/".$view."/assets/img";

// get the image name from the query string
// and make sure it's not trying to probe your file system

$tmp = str_replace("/images", "", $_SERVER['REQUEST_URI']);
$pic = $_SERVER['DOCUMENT_ROOT'].$image_folder.str_replace("/img", "", $tmp);


if ($Debug == 1)
{
    echo '<div style="position: absolute; top: 400px; left: 50px;">';
    echo "" . $_SERVER['REQUEST_URI'];
    echo "<br />pic: " . $pic ;
    echo "</div>";
}

if (file_exists($pic) && is_readable($pic)) {
    // get the filename extension
    $ext = substr($pic, -3);
    // set the MIME type
    switch ($ext) {
        case 'jpg':
            $mime = 'image/jpeg';
            break;
        case 'gif':
            $mime = 'image/gif';
            break;
        case 'png':
            $mime = 'image/png';
            break;
        default:
            $mime = false;
    }
    // if a valid MIME type exists, display the image
    // by sending appropriate headers and streaming the file


    if ($Debug == 1)
    {
        echo '<div style="position: absolute; top: 600px; left: 50px;">';
        echo "<br />mime: " . $mime ;
        echo "<br />file: " . $pic ;
        echo "</div>";
    } else {
        if ($mime) {
            header('Content-type: '.$mime);
            header('Content-length: '.filesize($pic));
            $file = @ fopen($pic, 'rb');
            if ($file) {
                fpassthru($file);
                exit;
            }
        }
    }
}