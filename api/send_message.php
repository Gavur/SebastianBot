<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['kick_access_token'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$content = $input['content'] ?? '';

if (empty($content)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Message content is required']);
    exit;
}

$postData = [
    'content' => $content,
    'type' => 'bot' 
];

$result = kickApiRequest('chat', 'POST', $postData);

if ($result['code'] === 200) {
    echo json_encode(['status' => 'success', 'data' => json_decode($result['response'], true)]);
} else {
    http_response_code($result['code']);
    echo json_encode(['status' => 'error', 'message' => 'Kick API Error: ' . $result['code'], 'kick_response' => json_decode($result['response'], true)]);
}
?>
