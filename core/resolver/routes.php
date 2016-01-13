<?php

error_reporting(-1);
ini_set('display_errors', 1);

header("HTTP/1.1 200 OK");

require_once($_SERVER['DOCUMENT_ROOT']."/core/init.php");

$view = get_device();

$path = explode('?', $_SERVER['REQUEST_URI']);
$path = explode('/', $path[0]);

//$Debug = 1;
if ($Debug == 1)
{
    echo '<div style="position: absolute; top: 100px; left: 50px;">';
	echo "" . $_SERVER['REQUEST_URI'];
    echo "<br />count(path): " . count($path);
    echo "<br />device: " . $view ;
    echo "<br />path[0]: " . $path[0] ;
    echo "<br />path[1]: " . $path[1] ;
    echo "<br />path[2]: " . $path[2] ;
    echo "<br />path[3]: " . $path[3] ;
    echo "<br />path[4]: " . $path[4] ;
    echo "</div>";
}

// Default Route to Home Page
if ($path[1] == "") {
    if ($_SESSION['user_id'] == "")
    {
        include($_SERVER['DOCUMENT_ROOT']."/views/$view/index.php");
    }
    else
    {
        include($_SERVER['DOCUMENT_ROOT']."/views/$view/community/home.php");
    }
}
// Route for CSS
elseif ($path[1] == "css") {
    header("Content-type: text/css", true);
    include($_SERVER['DOCUMENT_ROOT']."/views/$view/assets/".$_SERVER['REQUEST_URI']);
}
// Route for JS
elseif ($path[1] == "js") {
    header("Content-type: text/javascript", true);
    include($_SERVER['DOCUMENT_ROOT']."/views/$view/assets/".$_SERVER['REQUEST_URI']);
}
// Route for Images
elseif ($path[1] == "images" || $path[1] == "img") {
    include("image_handler.php");
}
// Route for API Moudels
elseif ($path[1] == "Module" || $path[1] == "module" || $path[1] == "Modules" || $path[1] == "modules" || $path[1] == "api") {
	
    if(file_exists($_SERVER['DOCUMENT_ROOT']."/api/modules/".$path[2].".php")) {
	    include($_SERVER['DOCUMENT_ROOT']."/api/modules/".$path[2].".php");
	} else {
	    include($_SERVER['DOCUMENT_ROOT']."/api/modules/".$path[2]."");
	}
	
}
// Route for fonts
elseif ($path[1] == "fonts") {
    header("Content-type: font/opentype", true);
    include($_SERVER['DOCUMENT_ROOT']."/views/$view/assets/".$_SERVER['REQUEST_URI']);
}
// Route for QA Testing Scripts
elseif ($path[1] == "qa") {
   include($_SERVER['DOCUMENT_ROOT']."/qa/init.php");
}
// Route for Messages
elseif ($path[1] == "messages") {
    include($_SERVER['DOCUMENT_ROOT']."/views".$_SERVER['REQUEST_URI']);
}
// Route for Messages
elseif ($path[1] == "logout") {

    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
    header( 'Location: /' );

}
// Routes for Pages
else {
    if(file_exists($_SERVER['DOCUMENT_ROOT']."/views/$view/".$_SERVER['REQUEST_URI'].".php")) {
        include($_SERVER['DOCUMENT_ROOT']."/views/$view/".$_SERVER['REQUEST_URI'].".php");
    }
    else {

        include("user_handler.php");

        if ($UserSite == 1)
        {
            include("public_profile.php");
        }
        else
        {
            include($_SERVER['DOCUMENT_ROOT']."/views/$view/not_found.php");
        }
    }
}