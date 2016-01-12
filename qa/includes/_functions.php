<?php

function log_m($message)
{
    $date = date('d-m-Y H:i:s');
    echo '<div class="row-fluid log_m"><div class="span2 indent">'.$date.'</div><div class="span10">'.$message.'</div></div>';
}
function log_success($message = '')
{
    $date = date('d-m-Y H:i:s');
    echo '<div class="row-fluid log_m"><div class="span2 indent">'.$date.'</div><div class="span10"><span class="label label-success">Success</span> <i>'.$message.'</i></div></div>';
}

function log_failed($message = '')
{
    $date = date('d-m-Y H:i:s');
    echo '<div class="row-fluid log_m"><div class="span2 indent">'.$date.'</div><div class="span10"><span class="label label-important">Failed</span> <i>'.$message.'</i></div></div>';
}
function log_header($title, $message)
{
    echo '<h3>'.$title.' <small>'.$message.'</small></h3>';
}
function getTestHeaders($file,$filename)
{
    echo '<div class="hero-unit test-header hide">';
    $headers = file_get_contents($file);
    printTestHeaders($headers, "Name");
    echo '<b>Test Script: '.$filename.'</b><br />';
    printTestHeaders($headers, "Author");
    printTestDesc($headers);
}
function printTestDesc($headers)
{
    echo '<br><blockquote>';
    $start = strpos($headers, "Desc: ");
    $end = strpos($headers, "*/");
    $desc = substr($headers, $start + 6, ($end-2) - ($start));
    $desc = str_replace("*/", "", $desc);
    echo str_replace("*", "<br>", $desc)."<br />";
    echo '</blockquote></div>';
    echo '<pre>';
}
function getTestFooter($teststart, $testend)
{
    echo '</pre>';
    echo '<div class="alert alert-block test-footer hide">';
    echo "This process used " . rutime($testend, $teststart, "utime") . " ms for its computations and ";
    echo " spent " . rutime($testend, $teststart, "stime") . " ms in system calls";
    echo '</div>';
}

function printTestHeaders($headers, $text)
{
    $regex = "/\* ".$text.":(.*?)\\n/";
    if(preg_match_all($regex,$headers,$match)) {
        echo '<b>'.$text.": ".$match[1][0].'</b><br />';
    }
}

function rutime($ru, $rus, $index)
{
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
    -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

function sendPost($url,$data)
{
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
}