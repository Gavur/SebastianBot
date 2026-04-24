<?php
require_once '../config.php';
header('Content-Type: application/json');

$username = trim($_GET['username'] ?? '');

if (empty($username)) {
    echo json_encode(['status' => 'error', 'message' => 'Kullanıcı adı gerekli.']);
    exit;
}

try {
    global $db;
    
    // Kullanıcı kontrolü
    $stmtUser = $db->prepare("SELECT * FROM chat_users WHERE username = ?");
    $stmtUser->execute([$username]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        
        $user = ['username' => $username, 'message_count' => 0];
    }

    // Son 10 mesaj
    $stmtMsg = $db->prepare("SELECT content, created_at FROM chat_messages WHERE sender_username = ? ORDER BY created_at DESC LIMIT 10");
    $stmtMsg->execute([$username]);
    $messages = $stmtMsg->fetchAll(PDO::FETCH_ASSOC);

    
    $stmtBans = $db->prepare("SELECT action_type, reason, created_at, moderator_name FROM ban_records WHERE username = ? ORDER BY created_at DESC LIMIT 5");
    $stmtBans->execute([$username]);
    $bans = $stmtBans->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'user' => $user,
        'messages' => $messages,
        'bans' => $bans
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
