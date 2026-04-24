<?php
session_start();
require_once '../config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'state') {
    $stmt = $db->query("SELECT * FROM repertoire_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $stmtPlay = $db->query("SELECT * FROM repertoire_requests WHERE status = 'playing' LIMIT 1");
    $current = $stmtPlay->fetch(PDO::FETCH_ASSOC);
    
    $stmtQ = $db->query("SELECT * FROM repertoire_requests WHERE status = 'pending' ORDER BY id ASC");
    $queue = $stmtQ->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['status' => 'success', 'settings' => $settings, 'current' => $current, 'queue' => $queue]);
    exit;
}

if ($action === 'get_songs') {
    $stmt = $db->query("SELECT id, song_name FROM repertoire_songs ORDER BY id ASC");
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    exit;
}

if ($action === 'save_settings') {
    $reqCmd = trim($_POST['request_command'] ?? '!peçete');
    $listCmd = trim($_POST['list_command'] ?? '!repertuar');
    $cost = (float)($_POST['request_cost'] ?? 0);
    $active = (int)($_POST['is_active'] ?? 0);
    $db->prepare("UPDATE repertoire_settings SET request_command=?, list_command=?, request_cost=?, is_active=? WHERE id=1")->execute([$reqCmd, $listCmd, $cost, $active]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'add_song') {
    $name = trim($_POST['song_name']);
    if ($name) $db->prepare("INSERT INTO repertoire_songs (song_name) VALUES (?)")->execute([$name]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'delete_song') {
    $id = (int)$_POST['id'];
    $db->prepare("DELETE FROM repertoire_songs WHERE id = ?")->execute([$id]);
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'play') {
    $stmt = $db->query("SELECT id FROM repertoire_requests WHERE status = 'playing' LIMIT 1");
    if (!$stmt->fetch()) {
        $stmt = $db->query("SELECT id FROM repertoire_requests WHERE status = 'pending' ORDER BY id ASC LIMIT 1");
        $next = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($next) $db->prepare("UPDATE repertoire_requests SET status = 'playing' WHERE id = ?")->execute([$next['id']]);
    }
    $db->exec("UPDATE repertoire_settings SET is_playing = 1 WHERE id = 1");
    echo json_encode(['status' => 'success']);
    exit;
}

if ($action === 'pause') { $db->exec("UPDATE repertoire_settings SET is_playing = 0 WHERE id = 1"); echo json_encode(['status' => 'success']); exit; }

if ($action === 'skip' || $action === 'ended') {
    $db->exec("UPDATE repertoire_requests SET status = 'played' WHERE status = 'playing'");
    $stmt = $db->query("SELECT id FROM repertoire_requests WHERE status = 'pending' ORDER BY id ASC LIMIT 1");
    $next = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($next) { $db->prepare("UPDATE repertoire_requests SET status = 'playing' WHERE id = ?")->execute([$next['id']]); $db->exec("UPDATE repertoire_settings SET is_playing = 1 WHERE id = 1"); } 
    else { $db->exec("UPDATE repertoire_settings SET is_playing = 0 WHERE id = 1"); }
    if ($action === 'skip') { $db->exec("UPDATE repertoire_settings SET trigger_action = 'skip', trigger_time = UNIX_TIMESTAMP() WHERE id = 1"); }
    echo json_encode(['status' => 'success']); exit;
}

if ($action === 'delete_request') { $id = (int)$_POST['id']; $db->prepare("DELETE FROM repertoire_requests WHERE id = ?")->execute([$id]); echo json_encode(['status' => 'success']); exit; }
if ($action === 'showcase') { $db->exec("UPDATE repertoire_settings SET trigger_action = 'showcase', trigger_time = UNIX_TIMESTAMP() WHERE id = 1"); echo json_encode(['status' => 'success']); exit; }

echo json_encode(['status' => 'error']);