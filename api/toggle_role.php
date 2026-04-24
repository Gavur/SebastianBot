<?php
require_once '../config.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$userId = $input['user_id'] ?? null;
$username = $input['username'] ?? null;
$role = $input['role'] ?? null; // 'vip', 'og', 'moderator'
$action = $input['action'] ?? null; // 'add' or 'remove'

if (!$userId || !$role || !$action) {
    echo json_encode(['status' => 'error', 'message' => 'Eksik parametre.']);
    exit;
}

$validRoles = ['vip' => 'is_vip', 'og' => 'is_og', 'moderator' => 'is_moderator'];
if (!isset($validRoles[$role])) {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz rol.']);
    exit;
}

$column = $validRoles[$role];
$value = ($action === 'add') ? 1 : 0;

try {
    global $db;
    
    
    if ($username) {
        $stmt = $db->prepare("INSERT IGNORE INTO chat_users (user_id, username) VALUES (?, ?)");
        $stmt->execute([$userId, $username]);
    }
    
    $stmt = $db->prepare("UPDATE chat_users SET {$column} = ? WHERE user_id = ?");
    $stmt->execute([$value, $userId]);
    
    echo json_encode(['status' => 'success', 'message' => 'Rol güncellendi.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
