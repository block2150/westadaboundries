<?php

$type = "user.joined";
$feedText = file_get_contents($_SERVER['DOCUMENT_ROOT']."/views/messages/feeds/$type.html");

$type = getFeedHeaders($feedText, "Type");
$content_title = getFeedHeaders($feedText, "Title");
$content_body = getFeedBody($feedText);

echo '{"type":"'.$type.'","content_title":"'.$content_title.'","content_body":"'.$content_body.'"}';

function getFeedHeaders($headers, $text)
{
    $regex = "/\* ".$text.":(.*?)\\n/";
    if(preg_match_all($regex,$headers,$match)) {
        return $match[1][0];
    }
}

function getFeedBody($text)
{
	$getStart = strpos($text, "*/");	
	return substr($text, $getStart + 3);
}