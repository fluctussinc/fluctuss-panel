<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    die("
    <html>
    <h2> You must be logged in to continue.</h2>
    </html>
    ");
}

$folderPath = "../xa550-serverside0000-55880sa51f000000000sa98ds41009as89d41w06694981a89fd89g8r4h8rt4hj9rt4h89erg1fa0dsf1gqer1yjr5819ey4j1156a0d0000";
$fileName = "submissions.txt";
$filePath = realpath($folderPath . DIRECTORY_SEPARATOR . $fileName);

if (!file_exists($filePath)) {
    die("Submissions file not found.");
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=submissions.csv');

$fp = fopen('php://output', 'w');
$fileHandle = fopen($filePath, 'r');
if ($fileHandle === false) {
    die("Could not open the file for reading.");
}

while (($line = fgets($fileHandle)) !== false) {
    fputcsv($fp, explode("\n", $line));
}

fclose($fileHandle);
fclose($fp);
exit;
?>
