<?php

session_start();

// Require all files in the config folder
$path = $_SERVER['DOCUMENT_ROOT']."/core/classes/";
foreach (scandir($path) as $filename) {
    $file = $path . $filename;
    if (is_file($file)) {
        require_once($file);
    }
}

// Require all files in the config folder
$path = $_SERVER['DOCUMENT_ROOT']."/core/config/";
foreach (scandir($path) as $filename) {
    $file = $path . $filename;
    if (is_file($file)) {
        require_once($file);
    }
}

// Require all files in the functions folder
$path = $_SERVER['DOCUMENT_ROOT']."/core/functions/";
foreach (scandir($path) as $filename) {
    $file = $path . $filename;
    if (is_file($file)) {
        require_once($file);
    }
}