<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$logFile = __DIR__ . '/http_requests_log.txt';

$getRequests = 0;
$postRequests = 0;

if (file_exists($logFile)) {
    $logContents = file_get_contents($logFile);
    if ($logContents === false) {
        die('Error reading log file.');
    }

    preg_match('/GET=(\d+)/', $logContents, $getMatch);
    preg_match('/POST=(\d+)/', $logContents, $postMatch);

    if (isset($getMatch[1])) {
        $getRequests = (int)$getMatch[1];
    }
    if (isset($postMatch[1])) {
        $postRequests = (int)$postMatch[1];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $getRequests++;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postRequests++;
}
$logData = "GET=$getRequests\nPOST=$postRequests";
if (file_put_contents($logFile, $logData) === false) {
    die('Error writing to log file.');
}
?>
