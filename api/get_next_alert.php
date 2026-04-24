<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    // En eski alarmı al
    $stmt = $db->query("SELECT * FROM alert_queue ORDER BY id ASC LIMIT 1");
    $alert = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($alert) {
        
        $stmtDelete = $db->prepare("DELETE FROM alert_queue WHERE id = ?");
        $stmtDelete->execute([$alert['id']]);

        echo json_encode([
            'status' => 'success',
            'has_alert' => true,
            'alert' => $alert
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'has_alert' => false
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Veritabanı hatası'
    ]);
}
?>