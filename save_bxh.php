<?php
// 1. Xử lý CORS Preflight request (Cực kỳ quan trọng khi fetch từ frontend khác cổng/domain)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    exit(0);
}

// Thiết lập header trả về JSON chuẩn
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json; charset=UTF-8');

// Tắt hiển thị lỗi mặc định của PHP để tránh làm hỏng cấu trúc JSON trả về client
ini_set('display_errors', 0);
error_reporting(0);

// Đọc dữ liệu JSON gửi lên từ Javascript
$json_data = file_get_contents('php://input');
$new_player = json_decode($json_data, true);

// Kiểm tra dữ liệu đầu vào
if ($new_player && isset($new_player['name'], $new_player['score'], $new_player['rawTime'])) {
    $file = 'bxh.json';
    
    // Kiểm tra và tạo file nếu chưa tồn tại
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_UNESCAPED_UNICODE));
        @chmod($file, 0666); // Cấp quyền đọc/ghi
    }

    // Đọc dữ liệu cũ từ bxh.json
    $content = file_get_contents($file);
    $current_data = json_decode($content, true) ?: [];

    // Làm sạch và thêm người chơi mới vào mảng
    $current_data[] = [
        "name" => htmlspecialchars(trim($new_player['name'])),
        "score" => floatval($new_player['score']),
        "time" => $new_player['time'] ?? '0s',
        "rawTime" => intval($new_player['rawTime'])
    ];

    // Sắp xếp Top 10 (Điểm cao xếp trước, nếu bằng điểm thì thời gian nhỏ hơn xếp trước)
    usort($current_data, function($a, $b) {
        if ($b['score'] != $a['score']) {
            return $b['score'] <=> $a['score']; // Điểm giảm dần
        }
        return $a['rawTime'] <=> $b['rawTime'];   // Thời gian tăng dần (nhanh hơn là tốt hơn)
    });

    // Giữ lại tối đa 10 người đứng đầu
    $current_data = array_slice($current_data, 0, 10);

    // Ghi file và kiểm tra kết quả
    if (file_put_contents($file, json_encode($current_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
        echo json_encode(["status" => "success", "message" => "Đã lưu thành công"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Không thể ghi vào file bxh.json (Lỗi phân quyền thư mục)"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Dữ liệu JSON không hợp lệ"]);
}
?>
