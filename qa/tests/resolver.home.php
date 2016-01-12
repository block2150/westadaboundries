<?php
/**
 * Fotolov.com QA Test
 * Name: Resolve Home Page
 * Author: Ryan Riley
 * Date: 6/4/13
 * Time: 9:25 AM
 * Desc: This this test will help do determin if the home page resolves correctly and without errors.
 * Each page is tagged with a special comment that the resolver will look for.  This tag  is:
 *
 *   qa:fotolov.com
 *
 * The following steps will be taken to execute this test:
 *
 * 1. Make an HTTP request to / on the current domain.
 * 2. Search the response for the above tag
 * 3. Show pass or failed message based on response
 *
 */

try {
    $url = "http://".$_SERVER['SERVER_NAME']."/";
    $exptected = "qa:fotolove.com";

    log_m("Checking for $exptected on the home page at the following URL: " .$url);

    $results = file_get_contents($url);

    if (strpos($results, $exptected) > 0) {
        log_success("Found $expected on character ".strpos($results, $exptected)." of the file.");
    }
    else{
        log_failed();
    }
} catch (Exception $e) {
    log_failed($e->getMessage());
}