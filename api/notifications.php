<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $db->query("SELECT * FROM chat_notifications");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['action']) && $input['action'] === 'save') {
        $event_type = $input['event_type'] ?? '';
        $message_template = $input['message_template'] ?? '';
        $is_active = isset($input['is_active']) ? (int)$input['is_active'] : 1;

        if (empty($event_type)) {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz olay türü.']);
            exit;
        }

        try {
            $stmt = $db->prepare("INSERT INTO chat_notifications (event_type, message_template, is_active) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE message_template = ?, is_active = ?");
            $stmt->execute([$event_type, $message_template, $is_active, $message_template, $is_active]);
            echo json_encode(['status' => 'success', 'message' => 'Bildirim ayarı kaydedildi.']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz işlem.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek.']);
}
