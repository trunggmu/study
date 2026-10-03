<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Cho phép gọi từ mọi nguồn
header('Access-Control-Allow-Methods: POST');

$json_data = file_get_contents('php://input');
$new_player = json_decode($json_data, true);

if ($new_player) {
    $file = 'bxh.json';
    
    // Kiểm tra và tạo file nếu chưa tồn tại
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_UNESCAPED_UNICODE));
        chmod($file, 0666); // Cấp quyền đọc/ghi cho file
    }

    $content = file_get_contents($file);
    $current_data = json_decode($content, true) ?: [];

    // Thêm người chơi
    $current_data[] = $new_player;

    // Sắp xếp Top 10
    usort($current_data, function($a, $b) {
        if ($b['score'] != $a['score']) {
            return $b['score'] <=> $a['score'];
        }
        return $a['rawTime'] <=> $b['rawTime'];
    });

    $current_data = array_slice($current_data, 0, 10);

    // Ghi file và kiểm tra kết quả
    if (file_put_contents($file, json_encode($current_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
        echo json_encode(["status" => "success", "message" => "Đã lưu"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Không thể ghi vào file bxh.json"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Dữ liệu không hợp lệ"]);
}
?>