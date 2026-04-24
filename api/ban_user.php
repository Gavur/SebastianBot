<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['kick_access_token'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? '';
$duration = $input['duration'] ?? null; // Timeout duration in minutes, null for permanent ban

if (empty($userId)) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

$payload = [
    'broadcaster_user_id' => (int)KICK_BROADCASTER_ID,
    'user_id' => (int)$userId,
    'reason' => 'Banned from Dashboard'
];

if ($duration !== null) {
    $payload['duration'] = (int)$duration;
    $payload['reason'] = 'Timeout from Dashboard';
}

$result = kickApiRequest('moderation/bans', 'POST', $payload);
$httpCode = $result['code'];
$response = $result['response'];

if ($httpCode === 200 || $httpCode === 204) {
    global $db;
    
    // Veritabanında kullanıcı kaydı yoksa geçici oluştur
    $stmt = $db->prepare("INSERT IGNORE INTO chat_users (user_id, username) VALUES (?, '')");
    $stmt->execute([$userId]);
    
    $actionType = $duration === null ? 'ban' : 'timeout';
    $reasonText = $duration === null ? 'Kalıcı Ban (Dashboard)' : "$duration Dakika Timeout (Dashboard)";
    
    // chat_users tablosunu güncelle
    if ($duration === null) {
        $db->prepare("UPDATE chat_users SET is_banned = 1, ban_count = ban_count + 1 WHERE user_id = ?")->execute([$userId]);
    } else {
        $db->prepare("UPDATE chat_users SET timeout_expires_at = DATE_ADD(NOW(), INTERVAL ? MINUTE), timeout_count = timeout_count + 1 WHERE user_id = ?")->execute([$duration, $userId]);
    }
    
    // ban_records tablosuna logla
    if ($duration === null) {
        $stmtLog = $db->prepare("INSERT INTO ban_records (user_id, username, action_type, created_at, expires_at, moderator_name, reason) VALUES (?, (SELECT username FROM chat_users WHERE user_id = ? LIMIT 1), ?, NOW(), NULL, 'Dashboard', ?)");
        $stmtLog->execute([$userId, $userId, $actionType, $reasonText]);
    } else {
        $stmtLog = $db->prepare("INSERT INTO ban_records (user_id, username, action_type, created_at, expires_at, moderator_name, reason) VALUES (?, (SELECT username FROM chat_users WHERE user_id = ? LIMIT 1), ?, NOW(), DATE_ADD(NOW(), INTERVAL ? MINUTE), 'Dashboard', ?)");
        $stmtLog->execute([$userId, $userId, $actionType, $duration, $reasonText]);
    }

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'API Error: ' . $httpCode, 'response' => json_decode($response, true)]);
}
?>
