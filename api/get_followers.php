<?php
require_once '../config.php';
header('Content-Type: application/json');

try {
    global $db;

    $search = $_GET['search'] ?? '';
    $filter = $_GET['filter'] ?? 'all'; // all, banned, timeout, clean
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 25;
    $offset = ($page - 1) * $perPage;

    $where = "WHERE follow_date IS NOT NULL";
    $params = [];

    if (!empty($search)) {
        $where .= " AND username LIKE ?";
        $params[] = "%$search%";
    }

    if ($filter === 'banned') {
        $where .= " AND is_banned = 1";
    } elseif ($filter === 'timeout') {
        $where .= " AND timeout_expires_at IS NOT NULL AND timeout_expires_at > NOW()";
    } elseif ($filter === 'clean') {
        $where .= " AND is_banned = 0 AND (timeout_expires_at IS NULL OR timeout_expires_at <= NOW())";
    }

    // Toplam sayı
    $countStmt = $db->prepare("SELECT COUNT(*) as total FROM chat_users $where");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Takipçileri çek
    $sql = "SELECT *, TIMESTAMPDIFF(SECOND, NOW(), timeout_expires_at) as remaining_seconds FROM chat_users $where ORDER BY follow_date DESC LIMIT $perPage OFFSET $offset";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $followers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $followers,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'total_pages' => ceil($total / $perPage)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
