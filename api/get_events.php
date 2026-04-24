<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    global $db;
    
    // Son 5 dakikadaki son 50 etkinliği getir, en yenisi en üstte
    $stmt = $db->query("SELECT * FROM channel_events WHERE created_at >= NOW() - INTERVAL 5 MINUTE ORDER BY id DESC LIMIT 50");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'data' => $events
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
