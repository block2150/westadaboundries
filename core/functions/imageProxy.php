<?php

function imageProxy($pic)
{
    if (file_exists($pic) && is_readable($pic)) {
		$ext = substr($pic, -3);
		switch (strtolower($ext)) {
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