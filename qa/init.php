<?php

$rustart = getrusage();
session_start();
$Debug = 1;

require($_SERVER['DOCUMENT_ROOT']."/qa/includes/_functions.php");
include($_SERVER['DOCUMENT_ROOT']."/qa/includes/_header.php");

?>


    <div class="page-header">
        <h2>Quality Assurence for Fotoluv.com</h2>
    </div>


    <ul class="nav nav-tabs" id="qaTabs">
        <li><a href="#test" data-toggle="tab">Test Scripts</a></li>
        <li><a href="#session" data-toggle="tab">Session Variables</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="test"><?php include($_SERVER['DOCUMENT_ROOT']."/qa/includes/_test.php"); ?></div>
        <div class="tab-pane" id="session"><?php include($_SERVER['DOCUMENT_ROOT']."/qa/includes/_dump.php"); ?></div>
    </div>

    <script>
        $(function () {
            $('#qaTabs a:first').tab('show');
        })
    </script>

<?php

require($_SERVER['DOCUMENT_ROOT']."/qa/includes/_page.footer.php");
