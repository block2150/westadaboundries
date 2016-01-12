<?php

$UserSite = 0;

$sql = "select * from users where username = '$path[1]'";
$result = mysql_query($sql, $connection);
if(!empty($result)){
    $user = mysql_fetch_assoc($result);
    if ($path[1] == $user["username"])
    {
        $_SESSION['site_user_id'] = $user["uuid"];
        $_SESSION['site_user_username'] = $user["username"];
        $UserSite = 1;
    }
}