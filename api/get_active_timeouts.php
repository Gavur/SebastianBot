<?php
require_once '../config.php';
header('Content-Type: application/json');

try {
    global $db;
    $stmt = $db->query("SELECT user_id, username, TIMESTAMPDIFF(SECOND, NOW(), timeout_expires_at) as remaining_seconds FROM chat_users WHERE timeout_expires_at > NOW() ORDER BY timeout_expires_at ASC");
    $timeouts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $timeouts]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
