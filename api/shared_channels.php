<?php
require_once '../config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    global $db;

    if ($method === 'GET') {
        $stmt = $db->query("SELECT * FROM shared_channels ORDER BY created_at DESC");
        $channels = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $channels]);
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';

        if ($action === 'add') {
            $name = trim($input['channel_name'] ?? '');
            $reasons = $input['accepted_reasons'] ?? [];
            if (empty($name)) {
                throw new Exception('Kanal adı boş olamaz.');
            }
            $stmt = $db->prepare("INSERT INTO shared_channels (channel_name, accepted_reasons) VALUES (?, ?)");
            $stmt->execute([$name, json_encode($reasons, JSON_UNESCAPED_UNICODE)]);
            echo json_encode(['status' => 'success']);
        } elseif ($action === 'edit') {
            $id = (int)($input['id'] ?? 0);
            $name = trim($input['channel_name'] ?? '');
            $reasons = $input['accepted_reasons'] ?? [];
            if ($id <= 0 || empty($name)) {
                throw new Exception('Geçersiz ID veya boş kanal adı.');
            }
            $stmt = $db->prepare("UPDATE shared_channels SET channel_name = ?, accepted_reasons = ? WHERE id = ?");
            $stmt->execute([$name, json_encode($reasons, JSON_UNESCAPED_UNICODE), $id]);
            echo json_encode(['status' => 'success']);
        } elseif ($action === 'delete') {
            $id = (int)($input['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('Geçersiz ID.');
            }
            $stmt = $db->prepare("DELETE FROM shared_channels WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } else {
            throw new Exception('Geçersiz işlem.');
        }
    } else {
        throw new Exception('Geçersiz metod.');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
