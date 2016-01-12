<?php
/**
 * Fotolov.com QA Test
 * Name: User Login
 * Author: Ryan Riley
 * Date: 6/4/13
 * Time: 9:25 AM
 * Desc: This script will simlute the login request via the APIs.  The test will
 * perform the follwing steps:
 *
 * 1) Login with username
 * 2) Login wiht email address
 *
 * The login will be made with the username of "Block2150" and the email of "block2150@gmail.comn"
 * and it's assocaited password.  A successful test should return a JSON object from the server
 * with the usrs details. When loging in with the username we will expect the email address to be
 * returned and vise versa.  A failed response will return a JSON object with the status of failed.
 *
 */


try
{
    // Start First Test
    log_m('Login into fotoluv.com with the username of "block2150"');

    $url = "http://".$_SERVER['SERVER_NAME']."/api/user.login.php";
    $data = array('login' => 'block2150', 'password' => 'beaver');
    $exptected = "block2150@gmail.com";

    $results = sendPost($url, $data);

    if (strpos($results, $exptected) > 0) {
        log_success("response: " .$results);
    }
    else{
        log_failed("response: " .$results);
    }

    // Start Second Test
    log_m('Login into fotoluv.com with the username of "block2150@gmail.com"');

    $url = "http://".$_SERVER['SERVER_NAME']."/api/user.login.php";
    $data = array('login' => 'block2150@gmail.com', 'password' => 'beaver');
    $exptected = "block2150";

    $results = sendPost($url, $data);

    if (strpos($results, $exptected) > 0) {
        log_success("response: " .$results);
    }
    else{
        log_failed("response: " .$results);
    }
}
catch (Exception $e)
{
    log_failed($e->getMessage());
}