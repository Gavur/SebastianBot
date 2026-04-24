<?php
require_once '../config.php';
header('Content-Type: application/json');

$userId = $_GET['user_id'] ?? '';

if (empty($userId)) {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
    exit;
}

try {
    global $db;
    
    // Kullanıcı bilgilerini al
    $stmt = $db->prepare("SELECT *, TIMESTAMPDIFF(SECOND, NOW(), timeout_expires_at) as remaining_seconds FROM chat_users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        $user = [
            'username' => 'Bilinmiyor',
            'message_count' => 0,
            'ban_count' => 0,
            'timeout_count' => 0,
            'deleted_message_count' => 0,
            'is_banned' => 0,
            'timeout_expires_at' => null,
            'remaining_seconds' => null,
            'follow_date' => null,
            'followed_during_stream' => 0,
            'followed_stream_category' => null
        ];
        
        $stmtName = $db->prepare("SELECT sender_username FROM chat_messages WHERE sender_id = ? LIMIT 1");
        $stmtName->execute([$userId]);
        $nameRow = $stmtName->fetch();
        if ($nameRow) {
            $user['username'] = $nameRow['sender_username'];
        }
    }
    
    // Son 50 mesajını al
    $stmtMsg = $db->prepare("SELECT content, created_at FROM chat_messages WHERE sender_id = ? ORDER BY created_at DESC LIMIT 50");
    $stmtMsg->execute([$userId]);
    $messages = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);

    // Ban/Timeout geçmişini al
    $stmtBans = $db->prepare("SELECT action_type, reason, created_at, expires_at, moderator_name FROM ban_records WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
    $stmtBans->execute([$userId]);
    $banHistory = $stmtBans->fetchAll(PDO::FETCH_ASSOC);

    // Yeniden takip sayısını al
    $stmtRefollow = $db->prepare("SELECT COUNT(*) as cnt FROM channel_events WHERE username = ? AND event_type = 'follow' AND description LIKE '%tekrar%'");
    $stmtRefollow->execute([$user['username']]);
    $refollowRow = $stmtRefollow->fetch(PDO::FETCH_ASSOC);
    $refollowCount = $refollowRow ? (int)$refollowRow['cnt'] : 0;
    
    echo json_encode([
        'status' => 'success',
        'user' => $user,
        'messages' => $messages,
        'ban_history' => $banHistory,
        'refollow_count' => $refollowCount
    ]);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
