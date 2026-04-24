<?php
session_start();
require_once '../config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'get') {
    $stmt = $db->query("SELECT *, UNIX_TIMESTAMP(end_time) as end_ts, UNIX_TIMESTAMP(NOW()) as now_ts FROM subathon WHERE id = 1");
    echo json_encode(['status' => 'success', 'data' => $stmt->fetch(PDO::FETCH_ASSOC)]);
    exit;
}

if ($action === 'save_settings') {
    $sub = (int)$_POST['sec_sub'];
    $resub = (int)$_POST['sec_resub'];
    $gift = (int)$_POST['sec_gift'];
    $req = (int)$_POST['kicks_req'];
    $kicks = (int)$_POST['sec_kicks'];
    $style = $_POST['timer_style'] ?? 'neon';
    
    $stmt = $db->prepare("UPDATE subathon SET sec_sub=?, sec_resub=?, sec_gift=?, kicks_req=?, sec_kicks=?, timer_style=? WHERE id=1");
    $stmt->execute([$sub, $resub, $gift, $req, $kicks, $style]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'add_time') {
    $seconds = (int)$_POST['seconds'];
    $db->prepare("UPDATE subathon SET end_time = DATE_ADD(GREATEST(COALESCE(end_time, NOW()), NOW()), INTERVAL ? SECOND) WHERE id = 1")->execute([$seconds]);
    $db->prepare("INSERT INTO subathon_events (username, action_type, seconds_added, created_at) VALUES (?, ?, ?, NOW())")->execute(['Sistem', 'Manuel', $seconds]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'toggle_active') {
    $is_active = (int)$_POST['is_active'];
    $db->prepare("UPDATE subathon SET is_active = ? WHERE id = 1")->execute([$is_active]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'set_time') {
    $hours = (int)$_POST['hours']; 
    $db->prepare("UPDATE subathon SET end_time = DATE_ADD(NOW(), INTERVAL ? HOUR) WHERE id = 1")->execute([$hours]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'trigger_showcase') {
    $stmt = $db->query("SELECT * FROM subathon WHERE id = 1");
    $subData = $stmt->fetch(PDO::FETCH_ASSOC);
    $infoData = json_encode([
        'sub' => (int)$subData['sec_sub'],
        'gift' => (int)$subData['sec_gift'],
        'kicks_req' => (int)$subData['kicks_req'],
        'sec_kicks' => (int)$subData['sec_kicks']
    ]);
    
    $db->prepare("INSERT INTO subathon_events (username, action_type, seconds_added, created_at) VALUES (?, ?, 0, NOW())")->execute([$infoData, 'SHOWCASE']);
    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Geçersiz işlem']);