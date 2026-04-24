<?php
require_once '../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['kick_access_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? '';

if (empty($userId)) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

$payload = [
    'broadcaster_user_id' => (int)KICK_BROADCASTER_ID,
    'user_id' => (int)$userId
];

$result = kickApiRequest('moderation/bans', 'DELETE', $payload);
$httpCode = $result['code'];

if ($httpCode === 200 || $httpCode === 204) {
    global $db;
    $db->prepare("UPDATE chat_users SET timeout_expires_at = NULL, is_banned = 0 WHERE user_id = ?")->execute([$userId]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'API Error: ' . $httpCode, 'response' => json_decode($result['response'], true)]);
}
?>
