<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek.']);
    exit;
}

$alert_type = $_POST['alert_type'] ?? '';

if (empty($alert_type)) {
    echo json_encode(['status' => 'error', 'message' => 'Parametre eksik.']);
    exit;
}

try {
    $stmt = $db->prepare("SELECT * FROM alert_settings WHERE alert_type = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$alert_type]);
    $alert = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($alert) {
        $msg = $alert['message_template'];
        $highlight = $alert['highlight_color'] ?? '#53FC18';
        
        $msg = str_replace('{username}', '<span style="color:' . $highlight . '">TestUser</span>', $msg);
        $msg = str_replace('{amount}', '<span style="color:' . $highlight . '">100</span>', $msg);
        $msg = str_replace('{months}', '<span style="color:' . $highlight . '">3</span>', $msg);
        $msg = str_replace('{viewers}', '<span style="color:' . $highlight . '">500</span>', $msg);
        $msg = str_replace('{count}', '<span style="color:' . $highlight . '">5</span>', $msg);
        
        $stmtInsert = $db->prepare("INSERT INTO alert_queue (alert_type, message, image_path, audio_path, audio_volume, duration_seconds, font_family, text_color, highlight_color, text_style, text_effect) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->execute([
            $alert_type,
            $msg,
            $alert['image_path'],
            $alert['audio_path'],
            $alert['audio_volume'],
            $alert['duration_seconds'],
            $alert['font_family'],
            $alert['text_color'],
            $alert['highlight_color'],
            $alert['text_style'],
            $alert['text_effect']
        ]);
        
        echo json_encode(['status' => 'success', 'message' => 'Test alarmı tetiklendi.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Bu alarm türü aktif değil.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>