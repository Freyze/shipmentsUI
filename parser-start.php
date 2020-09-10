<!DOCTYPE html>
<html lang="en">

<body>
<h1>Della parser start</h1>

<form method="post" enctype="multipart/form-data">

    <br />
    <label>Start url</label>
    <br />
    <input type="text" name="start-url" size="150" required>
    <br />
    <input type="checkbox" name="rewrite-files" value="enable"> Исключить НДС
    <br />
    <input type="checkbox" name="rewrite-files" value="enable"> Исключить безнал
    <br />
    <input type="submit" value="Start">

</form>

<form method="post" enctype="multipart/form-data">

    <br />
    <input type="hidden" name="stop" value="stop">
    <input type="submit" value="Stop">

</form>
</body>
</html>

<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

ignore_user_abort(true);
set_time_limit(0);

ini_set('memory_limit', '4096M');

define('PARSER_PATH', 'C:\\xampp\\htdocs\\della\\');
define('API_PATH', 'C:\\xampp\\htdocs\\lardi-api\\');

define('PARSER_LOCK_FILE', 'parser.lock');
define('API_LOCK_FILE', 'api.lock');

if (isset($_POST['stop'])) {

    if ($_POST['stop'] == "stop") {

        if (unlink(PARSER_PATH.PARSER_LOCK_FILE)) {
            echo "<br>Парсер будет остановлен в ближайшие пару минут!";
        } else {
            echo "<br>Парсер не запущен!";
        }

        if (unlink(API_PATH.API_LOCK_FILE))  {
            echo "<br>Залив на API lardi-trans будет остановлен в ближайшие пару минут!";
        } else {
            echo "<br>Залив на API lardi-trans не запущен!";
        }

    }

}

if (isset($_POST['start-url'])) {

//    if (file_exists(PARSER_PATH.PARSER_LOCK_FILE)) exit("<br>Парсер уже запущен! Сначала его необходимо остановить!");
    if (file_exists(API_PATH.API_LOCK_FILE)) exit("<br>Залив на API уже запущен! Сначала его необходимо остановить!");

    // https://regex101.com/r/KD74tO/1
    $urlPattern = '/della\.ua\/search\/[^\.]+[0-9]+l100\.html/';
    if (preg_match($urlPattern, $_POST['start-url'])) {
//        startParser($_POST['start-url']);
//        sleep(10);
        startApi();

//        while (true) {
            sleep(3600);
//        }
    } else {
        exit("<br>Invalid start url!");
    }


}

function startParser($url) {

    $content = 'Parser is worked';
    file_put_contents(PARSER_PATH.PARSER_LOCK_FILE, $content);

    $timeNow = time();
    $cmd = '"START /b node ' .PARSER_PATH. 'index.js ' .$url. ' > parse_log_'.$timeNow.'.txt"';
    echo "<br>Starting parser with command: $cmd";

//    popen($cmd, 'r');

    $descriptorspec = array(
        array("pipe","r"),
        array("pipe","w"),
        array("pipe","w")
    );
    $proc = proc_open($cmd, $descriptorspec, $pipes);
//    proc_close($proc);
//    echo "'$handle'; " . gettype($handle) . "\n";
//    $read = fread($handle, 2096);
//    echo $read;
//    pclose($handle);
}

function startApi() {

    $content = 'API is worked';
    file_put_contents(API_PATH.API_LOCK_FILE, $content);

    $timeNow = time();
    $cmd = '"START /b php '.API_PATH. 'main.php > api_log_'.$timeNow.'.txt"';
    echo "<br>Starting api with command: $cmd";
//    var_dump(shell_exec($cmd));
//    popen($cmd, 'r');

    $descriptorspec = array(
        array("pipe","r"),
        array("pipe","w"),
        array("pipe","w")
    );
    $proc = proc_open($cmd, $descriptorspec, $pipes);

}