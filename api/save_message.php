<?php
require_once '../config.php';

// Sadece POST isteklerini kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}


$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['id'])) {
    http_response_code(400);
    exit("Invalid payload");
}

$messageId = $data['id'];
$senderId = $data['sender_id'] ?? '';
$senderUsername = $data['sender_username'] ?? 'Unknown';
$badges = $data['sender_badges'] ?? '';
$content = $data['content'] ?? '';
$createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');

try {
    global $db;
    
    
    $stmt = $db->prepare("INSERT IGNORE INTO chat_messages (message_id, sender_id, sender_username, sender_badges, content, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$messageId, $senderId, $senderUsername, $badges, $content, $createdAt]);
    
    echo json_encode(['status' => 'success', 'message' => 'Saved']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
