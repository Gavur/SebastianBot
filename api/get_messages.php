<?php
require_once '../config.php';

header('Content-Type: application/json');


$lastId = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

try {
    global $db;
    
    if ($lastId > 0) {
        $stmt = $db->prepare("SELECT * FROM chat_messages WHERE id > ? ORDER BY id ASC LIMIT 50");
        $stmt->execute([$lastId]);
    } else {
        $stmt = $db->prepare("SELECT * FROM (SELECT * FROM chat_messages ORDER BY id DESC LIMIT 50) sub ORDER BY id ASC");
        $stmt->execute();
    }
    
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $messages
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
