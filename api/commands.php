<?php
require_once '../config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    global $db;

    // GET: Tüm komutları listele
    if ($method === 'GET') {
        $stmt = $db->query("SELECT * FROM bot_commands ORDER BY command_name ASC");
        $commands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $commands]);
        exit;
    }

    // POST: Yeni komut ekle veya güncelle
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? 'create';

        if ($action === 'create') {
            $name = trim($input['command_name'] ?? '');
            $response = trim($input['response'] ?? '');
            $type = $input['command_type'] ?? 'chat';
            $interval = ($type === 'timer' && isset($input['timer_interval'])) ? (int)$input['timer_interval'] : null;
            $cooldown = isset($input['cooldown']) ? (int)$input['cooldown'] : 60;

            if (empty($name) || empty($response)) {
                echo json_encode(['status' => 'error', 'message' => 'Komut adı ve yanıt zorunludur.']);
                exit;
            }

            if (strpos($name, '!') !== 0) {
                $name = '!' . $name;
            }

            $stmt = $db->prepare("INSERT INTO bot_commands (command_name, response, command_type, timer_interval, cooldown) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $response, $type, $interval, $cooldown]);
            echo json_encode(['status' => 'success', 'message' => 'Komut oluşturuldu.', 'id' => $db->lastInsertId()]);

        } elseif ($action === 'update') {
            $id = (int)($input['id'] ?? 0);
            $response = trim($input['response'] ?? '');
            $type = $input['command_type'] ?? 'chat';
            $interval = ($type === 'timer' && isset($input['timer_interval'])) ? (int)$input['timer_interval'] : null;
            $cooldown = isset($input['cooldown']) ? (int)$input['cooldown'] : 60;

            if ($id <= 0 || empty($response)) {
                echo json_encode(['status' => 'error', 'message' => 'Geçersiz parametre.']);
                exit;
            }

            $stmt = $db->prepare("UPDATE bot_commands SET response = ?, command_type = ?, timer_interval = ?, cooldown = ? WHERE id = ?");
            $stmt->execute([$response, $type, $interval, $cooldown, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Komut güncellendi.']);

        } elseif ($action === 'toggle') {
            $id = (int)($input['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID.']);
                exit;
            }
            $stmt = $db->prepare("UPDATE bot_commands SET is_active = NOT is_active WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Komut durumu değiştirildi.']);

        } elseif ($action === 'delete') {
            $id = (int)($input['id'] ?? 0);
            if ($id <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Geçersiz ID.']);
                exit;
            }
            $stmt = $db->prepare("DELETE FROM bot_commands WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success', 'message' => 'Komut silindi.']);

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Geçersiz aksiyon.']);
        }
        exit;
    }

    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);

} catch (PDOException $e) {
    // Duplicate entry kontrolü
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => 'Bu komut adı zaten kullanımda.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
