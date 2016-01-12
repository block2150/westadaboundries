<?php
/**
 * Fotolov.com QA Test
 * Name: API Username Available
 * Author: Ryan Riley
 * Date: 6/4/13
 * Time: 9:25 AM
 * Desc: This script will run a check to see if the API user.username.avaiable is performing
 * correctly.  It will test the following:
 *
 * 1. Send in a username that is aleady in use (block2150) and get a status of taken
 * 2. Send in a username that is not already in use (fdjafkdlsafrew) and get a status of available
 *
 * In order for this script to pass both test must succed.
 *
 */


try
{
    // Start First Test
    log_m("Checking to see if the username block2150 is taken.  A successful response would be taken");

    $url = "http://".$_SERVER['SERVER_NAME']."/api/user.username.available.php";
    $data = array('variable' => 'block2150');
    $exptected = "taken";

    $results = sendPost($url, $data);

    if (strpos($results, $exptected) > 0) {
        log_success("The username block2150 is already taken: " .$results);
    }
    else{
        log_failed("response: " .$results);
    }

    // Start Second Test
    log_m("Checking to see if the username fdjafkdlsafrew is taken.  A successful response would be available");

    $url = "http://".$_SERVER['SERVER_NAME']."/api/user.username.available.php";
    $data = array('variable' => 'fdjafkdlsafrew');
    $exptected = "available";

    $results = sendPost($url, $data);

    if (strpos($results, $exptected) > 0) {
        log_success("The username fdjafkdlsafrew is available: " .$results);
    }
    else{
        log_failed("response: " .$results);
    }
}
catch (Exception $e)
{
    log_failed($e->getMessage());
}