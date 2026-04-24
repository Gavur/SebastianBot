<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['kick_access_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$messageId = $input['message_id'] ?? '';

if (empty($messageId)) {
    echo json_encode(['status' => 'error', 'message' => 'Message ID is required']);
    exit;
}

$result = kickApiRequest('chat/' . $messageId, 'DELETE');

if ($result['code'] === 204 || $result['code'] === 200) {
    global $db;
    
    // Mesajı kimin attığını bulup istatistiğini güncelle
    $stmt = $db->prepare("SELECT sender_id FROM chat_messages WHERE message_id = ?");
    $stmt->execute([$messageId]);
    $msgRow = $stmt->fetch();
    
    if ($msgRow && $msgRow['sender_id']) {
        $updateStmt = $db->prepare("UPDATE chat_users SET deleted_message_count = deleted_message_count + 1 WHERE user_id = ?");
        $updateStmt->execute([$msgRow['sender_id']]);
    }

    // Mesajı DB'den sil
    $stmt = $db->prepare("DELETE FROM chat_messages WHERE message_id = ?");
    $stmt->execute([$messageId]);

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'API Error: ' . $result['code'], 'response' => json_decode($result['response'], true)]);
}
?>
