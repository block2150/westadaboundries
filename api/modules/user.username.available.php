<?php

$variable = mysql_real_escape_string($_POST["variable"]);

$query = "SELECT * FROM users WHERE username = '$variable'";

$result = mysql_query($query, $connection);

if(!empty($result)){
    $username = mysql_fetch_assoc($result);
}

if($username == ""){
    echo '{"status":"available"}';
} else {
    echo '{"status":"taken"}';
}
