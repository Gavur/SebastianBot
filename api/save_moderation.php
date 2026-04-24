<?php
require_once '../config.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? '';
$username = $input['username'] ?? '';
$actionType = $input['action_type'] ?? ''; // 'ban', 'timeout', 'unban'
$expiresAtRaw = $input['expires_at'] ?? null;
$reason = $input['reason'] ?? 'Chat Moderation';
$modName = $input['mod_name'] ?? 'System';

if (empty($userId) || empty($actionType)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
    exit;
}

$expiresAt = null;
if ($expiresAtRaw) {

    $expiresAt = date('Y-m-d H:i:s', strtotime($expiresAtRaw));
}

try {
    global $db;

    if ($actionType === 'unban') {
        $db->prepare("UPDATE chat_users SET timeout_expires_at = NULL, is_banned = 0 WHERE user_id = ?")->execute([$userId]);
    } else {
        
        if ($actionType === 'ban') {
            $db->prepare("INSERT INTO chat_users (user_id, username, is_banned, ban_count) VALUES (?, ?, 1, 1) ON DUPLICATE KEY UPDATE username=VALUES(username), is_banned=1, ban_count=ban_count+1")
               ->execute([$userId, $username]);
        } else {
            $db->prepare("INSERT INTO chat_users (user_id, username, timeout_expires_at, timeout_count) VALUES (?, ?, ?, 1) ON DUPLICATE KEY UPDATE username=VALUES(username), timeout_expires_at=VALUES(timeout_expires_at), timeout_count=timeout_count+1")
               ->execute([$userId, $username, $expiresAt]);
        }

        
        $stmtLog = $db->prepare("INSERT INTO ban_records (user_id, username, action_type, created_at, expires_at, moderator_name, reason) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        $stmtLog->execute([$userId, $username, $actionType, $expiresAt, $modName, $reason]);
    }

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    file_put_contents('moderation_debug.txt', date('[Y-m-d H:i:s] ERROR: ') . $e->getMessage() . PHP_EOL, FILE_APPEND);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
