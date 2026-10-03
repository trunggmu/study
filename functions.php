<?php
$jsonFile = 'data.json';

function getAllData() {
    global $jsonFile;
    if (!file_exists($jsonFile)) return [];
    return json_decode(file_get_contents($jsonFile), true) ?? [];
}

function saveData($data) {
    global $jsonFile;
    return file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
?>