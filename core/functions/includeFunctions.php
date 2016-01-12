<?php

function portfolio_template($path) {

    $view = get_device();
    $include_path = $_SERVER['DOCUMENT_ROOT']."/views/".$view."/templates/".$path.".php";

    if(file_exists($include_path)) {
        include($include_path);
    }
    else {
    }
}

function includes($path) {

    $view = get_device();
    $include_path = $_SERVER['DOCUMENT_ROOT']."/views/".$view."/includes/_".$path.".php";

    if(file_exists($include_path)) {
        include($include_path);
    }
    else {
    }
}

function widget($path) {

    $view = get_device();
    $include_path = $_SERVER['DOCUMENT_ROOT']."/views/".$view."/widgets/_widget.".$path.".php";

    if(file_exists($include_path)) {
        include($include_path);
    }
    else {
    }
}

function include_nav()
{
    if ($_SESSION['user_id'] == "")
    {
        includes("nav.public");
    }
    else
    {
        includes("nav.private");
    }

}

function require_class($path) {
    $include_path = $_SERVER['DOCUMENT_ROOT']."/api/classes/".$path;

    if(file_exists($include_path)) {
        require($include_path);
    }
    else {
    }
}

function makeLinks($str) {
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	$urls = array();
	$urlsToReplace = array();
	if(preg_match_all($reg_exUrl, $str, $urls)) {
		$numOfMatches = count($urls[0]);
		$numOfUrlsToReplace = 0;
		for($i=0; $i<$numOfMatches; $i++) {
			$alreadyAdded = false;
			$numOfUrlsToReplace = count($urlsToReplace);
			for($j=0; $j<$numOfUrlsToReplace; $j++) {
				if($urlsToReplace[$j] == $urls[0][$i]) {
					$alreadyAdded = true;
				}
			}
			if(!$alreadyAdded) {
				array_push($urlsToReplace, $urls[0][$i]);
			}
		}
		$numOfUrlsToReplace = count($urlsToReplace);
		for($i=0; $i<$numOfUrlsToReplace; $i++) {
			$str = str_replace($urlsToReplace[$i], "<a href=\"".$urlsToReplace[$i]."\" target=\"_blank\">".$urlsToReplace[$i]."</a> ", $str);
		}
		return $str;
	} else {
		return $str;
	}
}