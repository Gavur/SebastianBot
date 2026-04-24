<?php
require_once '../config.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

try {
    global $db;

    if ($method === 'GET') {
        $isExport = isset($_GET['export']) && $_GET['export'] === 'true';
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $search = $_GET['search'] ?? '';
        $filterChannel = $_GET['channel'] ?? '';
        $filterMod = $_GET['mod'] ?? '';
        $filterEv = $_GET['evidence'] ?? ''; // 'yes' or 'no'
        
        $sortBy = $_GET['sort'] ?? 'created_at';
        $sortDir = strtoupper($_GET['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
        
        $allowedSorts = ['username', 'reason', 'original_channel', 'moderator_name', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $where .= " AND (username LIKE ? OR original_channel LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if (!empty($filterChannel)) {
            $where .= " AND original_channel = ?";
            $params[] = $filterChannel;
        }
        if (!empty($filterMod)) {
            $where .= " AND moderator_name = ?";
            $params[] = $filterMod;
        }
        if ($filterEv === 'yes') {
            $where .= " AND evidence_messages IS NOT NULL AND evidence_messages != '[]' AND evidence_messages != ''";
        } elseif ($filterEv === 'no') {
            $where .= " AND (evidence_messages IS NULL OR evidence_messages = '[]' OR evidence_messages = '')";
        }

        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM shared_bans $where");
        $countStmt->execute($params);
        $total = (int)$countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $limitClause = $isExport ? "" : "LIMIT $perPage OFFSET $offset";
        $stmt = $db->prepare("SELECT * FROM shared_bans $where ORDER BY $sortBy $sortDir $limitClause");
        $stmt->execute($params);
        $bans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($bans as &$ban) {
            if ($ban['evidence_messages']) {
                $ban['evidence_messages'] = json_decode($ban['evidence_messages'], true);
            } else {
                $ban['evidence_messages'] = [];
            }
        }
        $chStmt = $db->query("SELECT DISTINCT original_channel FROM shared_bans WHERE original_channel != ''");
        $filterChannels = $chStmt->fetchAll(PDO::FETCH_COLUMN);
        
        $modStmt = $db->query("SELECT DISTINCT moderator_name FROM shared_bans WHERE moderator_name != ''");
        $filterMods = $modStmt->fetchAll(PDO::FETCH_COLUMN);

        $reasonStmt = $db->query("SELECT reason, COUNT(*) as count FROM shared_bans GROUP BY reason");
        $reasonStats = $reasonStmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success', 
            'data' => $bans,
            'total' => $total,
            'page' => $page,
            'total_pages' => ceil($total / $perPage),
            'filterChannels' => $filterChannels,
            'filterMods' => $filterMods,
            'reasonStats' => $reasonStats
        ]);
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';

        if ($action === 'add') {
            $username = trim($input['username'] ?? '');
            $reason = trim($input['reason'] ?? '');
            $moderatorName = trim($input['moderator_name'] ?? '');
            $originalChannel = trim($input['original_channel'] ?? '');
            $evidenceMessages = $input['evidence_messages'] ?? [];
            
            if (empty($username) || empty($reason) || empty($originalChannel)) {
                throw new Exception('Kullanıcı adı, sebep ve kanal zorunludur.');
            }

            $evidenceJson = json_encode($evidenceMessages, JSON_UNESCAPED_UNICODE);

            $stmt = $db->prepare("INSERT INTO shared_bans (username, reason, moderator_name, original_channel, evidence_messages) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE reason=VALUES(reason), moderator_name=VALUES(moderator_name), original_channel=VALUES(original_channel), evidence_messages=VALUES(evidence_messages)");
            $stmt->execute([$username, $reason, $moderatorName, $originalChannel, $evidenceJson]);
            
            echo json_encode(['status' => 'success']);
        } elseif ($action === 'edit') {
            $id = (int)($input['id'] ?? 0);
            $reason = trim($input['reason'] ?? '');
            $moderatorName = trim($input['moderator_name'] ?? '');
            $originalChannel = trim($input['original_channel'] ?? '');
            
            if ($id <= 0 || empty($reason) || empty($originalChannel)) {
                throw new Exception('Sebep ve kanal zorunludur.');
            }

            $stmt = $db->prepare("UPDATE shared_bans SET reason = ?, moderator_name = ?, original_channel = ? WHERE id = ?");
            $stmt->execute([$reason, $moderatorName, $originalChannel, $id]);
            
            echo json_encode(['status' => 'success']);
        } elseif ($action === 'delete') {
            $id = (int)($input['id'] ?? 0);
            if ($id <= 0) {
                throw new Exception('Geçersiz ID.');
            }
            $stmt = $db->prepare("DELETE FROM shared_bans WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(['status' => 'success']);
        } elseif ($action === 'bulk_delete') {
            $ids = $input['ids'] ?? [];
            if (!is_array($ids) || empty($ids)) {
                throw new Exception('Silinecek kayıt seçilmedi.');
            }
            $placeholders = str_repeat('?,', count($ids) - 1) . '?';
            $stmt = $db->prepare("DELETE FROM shared_bans WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            echo json_encode(['status' => 'success']);
        } elseif ($action === 'bulk_import') {
            $bans = $input['bans'] ?? [];
            if (!is_array($bans) || empty($bans)) {
                throw new Exception('İçe aktarılacak geçerli ban kaydı bulunamadı.');
            }

            $stmt = $db->prepare("INSERT INTO shared_bans (username, reason, moderator_name, original_channel, evidence_messages, created_at) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE reason=VALUES(reason), moderator_name=VALUES(moderator_name), original_channel=VALUES(original_channel), evidence_messages=VALUES(evidence_messages)");
            
            $count = 0;
            foreach ($bans as $b) {
                if (empty($b['username']) || empty($b['reason']) || empty($b['original_channel'])) continue;
                
                $evMsg = isset($b['evidence_messages']) ? (is_array($b['evidence_messages']) ? json_encode($b['evidence_messages'], JSON_UNESCAPED_UNICODE) : $b['evidence_messages']) : '[]';
                $createdAt = !empty($b['created_at']) ? $b['created_at'] : date('Y-m-d H:i:s');
                
                $stmt->execute([
                    $b['username'],
                    $b['reason'],
                    $b['moderator_name'] ?? '',
                    $b['original_channel'],
                    $evMsg,
                    $createdAt
                ]);
                $count++;
            }
            
            echo json_encode(['status' => 'success', 'imported' => $count]);
        } else {
            throw new Exception('Geçersiz işlem.');
        }
    } else {
        throw new Exception('Geçersiz metod.');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
