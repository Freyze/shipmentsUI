<!DOCTYPE html>
<html lang="en">

<body>
<h1>Della parser start</h1>

<form method="post" enctype="multipart/form-data">

    <br />
    <label>Start url</label>
    <br />
    <input type="text" name="business-id" size="150" required>
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
        unlink(PARSER_PATH.PARSER_LOCK_FILE);
        unlink(API_PATH.API_LOCK_FILE);
    }

}

if (isset($_POST['start-url'])) {

    if (file_exists(PARSER_PATH.PARSER_LOCK_FILE)) exit("Парсер уже запущен! Сначала его необходимо остановить!");
    if (file_exists(API_PATH.API_LOCK_FILE)) exit("Залив на API уже запущен! Сначала его необходимо остановить!");

    // https://regex101.com/r/KD74tO/1
    $urlPattern = '/della\.ua\/search\/[^\.]+[0-9]+l100\.html/';
    if (preg_match($urlPattern, $_POST['start-url'])) {
        startParser($_POST['start-url']);
    } else {
        exit("Invalid start url!");
    }

    sleep(5);

    startApi();
}

function startParser($url) {

    echo shell_exec('cd '.PARSER_PATH);

    $content = 'Parser is worked';
    file_put_contents(PARSER_PATH.PARSER_LOCK_FILE, $content);

    echo shell_exec('node index.js '.$url);

}

function startApi() {

    echo shell_exec('cd '.API_PATH);

    $content = 'API is worked';
    file_put_contents(API_PATH.API_LOCK_FILE, $content);

    echo shell_exec('php main.php');
}