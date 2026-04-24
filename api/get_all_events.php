<?php
require_once '../config.php';
header('Content-Type: application/json');

try {
    global $db;

    $search = $_GET['search'] ?? '';
    $type = $_GET['type'] ?? 'all';
    $range = $_GET['range'] ?? 'all';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 30;
    $offset = ($page - 1) * $perPage;

    $where = "WHERE 1=1";
    $params = [];

    if (!empty($search)) {
        $where .= " AND (e.username LIKE ? OR e.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    if ($type !== 'all') {
        $where .= " AND e.event_type = ?";
        $params[] = $type;
    }

    if ($range === '24h') {
        $where .= " AND e.created_at >= NOW() - INTERVAL 24 HOUR";
    } elseif ($range === '7d') {
        $where .= " AND e.created_at >= NOW() - INTERVAL 7 DAY";
    } elseif ($range === '30d') {
        $where .= " AND e.created_at >= NOW() - INTERVAL 30 DAY";
    } elseif ($range === 'custom' && !empty($_GET['from']) && !empty($_GET['to'])) {
        $where .= " AND e.created_at >= ? AND e.created_at <= ?";
        $params[] = $_GET['from'] . ' 00:00:00';
        $params[] = $_GET['to'] . ' 23:59:59';
    }

    $countStmt = $db->prepare("SELECT COUNT(*) as total FROM channel_events e $where");
    $countStmt->execute($params);
    $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $sql = "SELECT e.*, cu.user_id FROM channel_events e LEFT JOIN chat_users cu ON cu.username = e.username $where ORDER BY e.created_at DESC LIMIT $perPage OFFSET $offset";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $events,
        'total' => $total,
        'page' => $page,
        'total_pages' => ceil($total / $perPage)
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
