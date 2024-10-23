<?php
$jsonFile = 'visitors.json';
$data = null;
$lockFile = $jsonFile . '.lock';

if (file_exists($jsonFile)) {
    $file = fopen($jsonFile, 'r+');
    if (flock($file, LOCK_EX)) {
        $data = json_decode(fread($file, filesize($jsonFile)), true);
        $currentMonth = (int)date('n');
        $data['datasets'][0]['data'][$currentMonth - 1] += 1;
        fseek($file, 0);
        fwrite($file, json_encode($data));
        ftruncate($file, ftell($file));
        flock($file, LOCK_UN);
    }
    
    fclose($file);
} else {
    $data = [
        "labels" => ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        "datasets" => [
            [
                "label" => "Number of Users",
                "data" => array_fill(0, 12, 0)
            ]
        ]
    ];
    file_put_contents($jsonFile, json_encode($data));
}
?>
