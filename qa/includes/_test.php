<?php
?>

    <h3>
        Automated Test Scripts

        <div id="verbose-container" class="pull-right">
            <div id="verbosetitle"><small>Verbose:</small></div>
            <div id="verbose" class="switch switch-small">
                <input type="checkbox" />
            </div>
        </div>
    </h3>
<?php


// Basic Error Display
error_reporting(1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Require all files in the config folder


$path = $_SERVER['DOCUMENT_ROOT']."/qa/tests/";

echo '<div id="test-list" class="hide">';
echo "<blockquote>";
foreach (scandir($path) as $filename) {
    $file = $path . $filename;
    if (is_file($file)) {
        echo $filename ."<br>";
    }
}
echo "</blockquote>";
echo "</div>";

foreach (scandir($path) as $filename) {
    $file = $path . $filename;
    if (is_file($file)) {
        getTestHeaders($file, $filename);
        $teststart = getrusage();
        log_m("<b>". $filename ."</b>");
        require_once($file);
        $testend = getrusage();
        getTestFooter($teststart, $testend);
    }
}

$ru = getrusage();

echo '<br><div class="alert alert-info">';
echo '<h4>QA execution summary</h4>';
echo "This process used " . rutime($ru, $rustart, "utime") . " ms for its computations and ";
echo " spent " . rutime($ru, $rustart, "stime") . " ms in system calls";
echo '</div>';