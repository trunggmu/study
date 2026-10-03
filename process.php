<?php
require_once 'functions.php';
$data = getAllData();
$action = $_POST['action'] ?? '';

if ($action == 'save_question') {
    $category = trim($_POST['category']);
    $id = $_POST['question_id'];
    
    // Thu thập 4 lựa chọn vào mảng
    $options = [$_POST['opt0'], $_POST['opt1'], $_POST['opt2'], $_POST['opt3']];
    
    // Xử lý biến chữ cái A,B,C,D thành nội dung văn bản
    $ans_letter = strtoupper(trim($_POST['answer'])); // Chuyển thành chữ hoa (A, B, C, D)
    $ans_map = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
    
    // Nếu nhập đúng A,B,C,D thì lấy nội dung, nếu không thì giữ nguyên văn bản nhập vào
    if (array_key_exists($ans_letter, $ans_map)) {
        $final_answer = $options[$ans_map[$ans_letter]];
    } else {
        $final_answer = $_POST['answer']; // Phòng hờ trường hợp bạn vẫn muốn nhập văn bản
    }

    $newQuestion = [
        "question" => $_POST['question'],
        "options" => $options,
        "answer" => $final_answer
    ];

    if ($id !== "") {
        $data[$category][$id] = $newQuestion;
    } else {
        $data[$category][] = $newQuestion;
    }
    saveData($data);
} 
elseif ($action == 'save_json_bulk') {
    // Xử lý cập nhật hàng loạt bằng JSON
    $category = trim($_POST['category']);
    $questionsJson = $_POST['questions_json'] ?? '[]';
    
    $decodedQuestions = json_decode($questionsJson, true);
    
    // Kiểm tra xem JSON có hợp lệ và là một mảng hay không
    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedQuestions)) {
        // Ghi đè hoặc thêm mới toàn bộ mảng câu hỏi cho chuyên mục này
        $data[$category] = $decodedQuestions;
        saveData($data);
    }
}
elseif ($action == 'delete') {
    // Xử lý xóa câu hỏi
    $category = $_POST['category'] ?? '';
    $id = $_POST['question_id'] ?? '';

    if (isset($data[$category][$id])) {
        unset($data[$category][$id]);
        // Re-index lại mảng để tránh lỗi thiếu chỉ mục số nguyên trong JSON Array
        $data[$category] = array_values($data[$category]);
        
        // Nếu chuyên mục không còn câu hỏi nào, bạn có thể chọn xóa luôn key chuyên mục (tùy chọn)
        if (empty($data[$category])) {
            unset($data[$category]);
        }
        
        saveData($data);
    }
}

// Trả về phản hồi chuẩn hoặc chuyển hướng (ở đây dùng HTTP 200 OK để Javascript nhận diện thành công)
http_response_code(200);
exit;
?>