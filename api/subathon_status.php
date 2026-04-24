<?php
require_once '../config.php';
header('Content-Type: application/json');

$last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;


$stmt = $db->query("SELECT is_active, timer_style, UNIX_TIMESTAMP(end_time) as end_ts, UNIX_TIMESTAMP(NOW()) as now_ts FROM subathon WHERE id = 1");
$sub = $stmt->fetch(PDO::FETCH_ASSOC);

$events = [];
if ($last_id > 0) {
    $stmtEv = $db->prepare("SELECT id, username, action_type, seconds_added FROM subathon_events WHERE id > ? ORDER BY id ASC LIMIT 10");
    $stmtEv->execute([$last_id]);
    $events = $stmtEv->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmtEv = $db->query("SELECT MAX(id) as max_id FROM subathon_events");
    $maxId = $stmtEv->fetchColumn();
    $events = [['id' => $maxId ?: 0, 'initial' => true]];
}

echo json_encode([
    'status' => 'success',
    'is_active' => (int)$sub['is_active'],
    'timer_style' => $sub['timer_style'] ?? 'neon',
    'end_ts' => (int)$sub['end_ts'],
    'now_ts' => (int)$sub['now_ts'],
    'events' => $events
]);