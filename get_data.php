<?php
header('Content-Type: application/json; charset=utf-8');

$dataFile = 'data.json';

if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    // Kiểm tra nếu file trống thì trả về mảng JSON rỗng
    if (empty(trim($content))) {
        echo json_encode([], JSON_UNESCAPED_UNICODE);
    } else {
        echo $content;
    }
} else {
    echo json_encode([], JSON_UNESCAPED_UNICODE);
}
?>
