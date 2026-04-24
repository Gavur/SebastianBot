<?php
require_once '../config.php';
header('Content-Type: application/json');

try {
    global $db;
    $stmt = $db->query("SELECT user_id, is_vip, is_og, is_moderator FROM chat_users WHERE is_vip = 1 OR is_og = 1 OR is_moderator = 1");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $data]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
