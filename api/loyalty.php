<?php
require_once '../config.php';
header('Content-Type: application/json');

function asInt($v, $default = 0) {
    if ($v === null || $v === '') {
        return $default;
    }
    return (int)$v;
}

function asFloat($v, $default = 0.0) {
    if ($v === null || $v === '') {
        return $default;
    }
    return (float)$v;
}

function getLoyaltySettings($db) {
    $stmt = $db->query("SELECT * FROM loyalty_settings WHERE id = 1 LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return [
            'id' => 1,
            'follow_days_step' => 30,
            'follow_days_bonus_pct' => 1.0,
            'message_step' => 100,
            'message_bonus_pct' => 5.0,
            'timeout_step' => 5,
            'timeout_penalty_pct' => 0.5,
            'ban_step' => 1,
            'ban_penalty_pct' => 10.0,
            'deleted_step' => 20,
            'deleted_penalty_pct' => 1.0,
            'subscriber_bonus_pct' => 10.0,
            'vip_bonus_pct' => 15.0
        ];
    }
    return $row;
}

function computeScore($u, $s) {
    $base = (float)$u['message_count'];
    $followDays = (int)$u['follow_days'];

    $followStep = max(1, (int)$s['follow_days_step']);
    $messageStep = max(1, (int)$s['message_step']);
    $timeoutStep = max(1, (int)$s['timeout_step']);
    $banStep = max(1, (int)$s['ban_step']);
    $deletedStep = max(1, (int)$s['deleted_step']);

    $followBonus = floor($followDays / $followStep) * (float)$s['follow_days_bonus_pct'];
    $messageBonus = floor(((int)$u['message_count']) / $messageStep) * (float)$s['message_bonus_pct'];

    $subscriberBonus = ((int)$u['is_subscriber'] === 1) ? (float)$s['subscriber_bonus_pct'] : 0.0;
    $vipBonus = ((int)$u['is_vip'] === 1) ? (float)$s['vip_bonus_pct'] : 0.0;

    $timeoutPenalty = floor(((int)$u['timeout_count']) / $timeoutStep) * (float)$s['timeout_penalty_pct'];
    $banPenalty = floor(((int)$u['ban_count']) / $banStep) * (float)$s['ban_penalty_pct'];
    $deletedPenalty = floor(((int)$u['deleted_message_count']) / $deletedStep) * (float)$s['deleted_penalty_pct'];

    $totalPct = $followBonus + $messageBonus + $subscriberBonus + $vipBonus - $timeoutPenalty - $banPenalty - $deletedPenalty;
    $score = $base * (1 + ($totalPct / 100));

    $spent = (float)($u['spent_score'] ?? 0);
    $finalScore = $score - $spent;

    if ($finalScore < 0) {
        $finalScore = 0;
    }

    return [
        'score' => round($finalScore, 2),
        'base_score' => round($base, 2),
        'spent_score' => round($spent, 2),
        'total_multiplier_pct' => round($totalPct, 3)
    ];
}

function findLevel($score, $followDays, $levels) {
    $current = null;
    foreach ($levels as $lvl) {
        $minFollowDays = isset($lvl['min_follow_days']) ? (int)$lvl['min_follow_days'] : 0;
        $followOk = ($minFollowDays <= 0) || ($followDays >= $minFollowDays);
        if ($score >= (float)$lvl['required_score'] && $followOk) {
            $current = $lvl;
        }
    }
    return $current;
}

try {
    global $db;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_GET['action'] ?? 'bootstrap';

        if ($action !== 'bootstrap') {
            throw new Exception('Geçersiz action');
        }

        $page = max(1, asInt($_GET['page'] ?? 1, 1));
        $perPage = 25;
        $search = trim($_GET['search'] ?? '');
        $sort = $_GET['sort'] ?? 'score';
        $dir = strtoupper($_GET['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

        $settings = getLoyaltySettings($db);

        $levelStmt = $db->query("SELECT * FROM loyalty_levels ORDER BY required_score ASC, id ASC");
        $levels = $levelStmt->fetchAll(PDO::FETCH_ASSOC);

        $where = "WHERE cu.follow_date IS NOT NULL";
        $params = [];
        if ($search !== '') {
            $where .= " AND cu.username LIKE ?";
            $params[] = "%$search%";
        }

        $sql = "
            SELECT
                cu.user_id,
                cu.username,
                cu.follow_date,
                cu.message_count,
                cu.ban_count,
                cu.timeout_count,
                cu.deleted_message_count,
                cu.is_banned,
                cu.timeout_expires_at,
                cu.is_vip,
                cu.is_subscriber,
                cu.spent_score,
                DATEDIFF(NOW(), cu.follow_date) AS follow_days,
                CASE WHEN cu.timeout_expires_at IS NOT NULL AND cu.timeout_expires_at > NOW() THEN 1 ELSE 0 END AS is_timeout_active,
                TIMESTAMPDIFF(SECOND, NOW(), cu.timeout_expires_at) AS timeout_remaining_seconds
            FROM chat_users cu
            $where
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rows = [];
        foreach ($users as $u) {
            $scoreInfo = computeScore($u, $settings);
            $lvl = findLevel($scoreInfo['score'], (int)$u['follow_days'], $levels);

            $u['score'] = $scoreInfo['score'];
            $u['base_score'] = $scoreInfo['base_score'];
        $u['spent_score'] = $scoreInfo['spent_score'];
            $u['total_multiplier_pct'] = $scoreInfo['total_multiplier_pct'];
            $u['level_name'] = $lvl ? $lvl['level_name'] : 'Seviye Yok';
            $u['level_required_score'] = $lvl ? (float)$lvl['required_score'] : 0;
            $rows[] = $u;
        }

        $sortable = [
            'username', 'follow_date', 'follow_days', 'message_count', 'is_subscriber', 'is_vip',
            'timeout_count', 'ban_count', 'deleted_message_count', 'score', 'level_required_score',
            'is_timeout_active', 'is_banned'
        ];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'score';
        }

        usort($rows, function($a, $b) use ($sort, $dir) {
            $va = $a[$sort] ?? null;
            $vb = $b[$sort] ?? null;

            if ($sort === 'follow_date') {
                $va = $va ? strtotime($va) : 0;
                $vb = $vb ? strtotime($vb) : 0;
            }

            if (is_numeric($va) && is_numeric($vb)) {
                if ((float)$va === (float)$vb) {
                    return strcasecmp((string)$a['username'], (string)$b['username']);
                }
                $cmp = ((float)$va < (float)$vb) ? -1 : 1;
            } else {
                $cmp = strcasecmp((string)$va, (string)$vb);
            }

            return $dir === 'ASC' ? $cmp : -$cmp;
        });

        $total = count($rows);
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;
        $pagedRows = array_slice($rows, $offset, $perPage);

        echo json_encode([
            'status' => 'success',
            'settings' => $settings,
            'levels' => $levels,
            'ranking' => $pagedRows,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'sort' => $sort,
            'dir' => $dir
        ]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';

        if ($action === 'save_settings') {
            $payload = [
                'follow_days_step' => max(1, asInt($input['follow_days_step'] ?? 30, 30)),
                'follow_days_bonus_pct' => max(0, asFloat($input['follow_days_bonus_pct'] ?? 1, 1)),
                'message_step' => max(1, asInt($input['message_step'] ?? 100, 100)),
                'message_bonus_pct' => max(0, asFloat($input['message_bonus_pct'] ?? 5, 5)),
                'timeout_step' => max(1, asInt($input['timeout_step'] ?? 5, 5)),
                'timeout_penalty_pct' => max(0, asFloat($input['timeout_penalty_pct'] ?? 0.5, 0.5)),
                'ban_step' => max(1, asInt($input['ban_step'] ?? 1, 1)),
                'ban_penalty_pct' => max(0, asFloat($input['ban_penalty_pct'] ?? 10, 10)),
                'deleted_step' => max(1, asInt($input['deleted_step'] ?? 20, 20)),
                'deleted_penalty_pct' => max(0, asFloat($input['deleted_penalty_pct'] ?? 1, 1)),
                'subscriber_bonus_pct' => max(0, asFloat($input['subscriber_bonus_pct'] ?? 10, 10)),
                'vip_bonus_pct' => max(0, asFloat($input['vip_bonus_pct'] ?? 15, 15))
            ];

            $stmt = $db->prepare("UPDATE loyalty_settings SET
                follow_days_step = ?,
                follow_days_bonus_pct = ?,
                message_step = ?,
                message_bonus_pct = ?,
                timeout_step = ?,
                timeout_penalty_pct = ?,
                ban_step = ?,
                ban_penalty_pct = ?,
                deleted_step = ?,
                deleted_penalty_pct = ?,
                subscriber_bonus_pct = ?,
                vip_bonus_pct = ?
                WHERE id = 1");

            $stmt->execute([
                $payload['follow_days_step'],
                $payload['follow_days_bonus_pct'],
                $payload['message_step'],
                $payload['message_bonus_pct'],
                $payload['timeout_step'],
                $payload['timeout_penalty_pct'],
                $payload['ban_step'],
                $payload['ban_penalty_pct'],
                $payload['deleted_step'],
                $payload['deleted_penalty_pct'],
                $payload['subscriber_bonus_pct'],
                $payload['vip_bonus_pct']
            ]);

            echo json_encode(['status' => 'success', 'message' => 'Ayarlar kaydedildi.']);
            exit;
        }

        if ($action === 'add_level') {
            $name = trim($input['level_name'] ?? '');
            $required = asFloat($input['required_score'] ?? 0, 0);
            $minFollowDays = asInt($input['min_follow_days'] ?? 0, 0);
            if ($minFollowDays < 0) {
                $minFollowDays = 0;
            }
            $minFollowDays = $minFollowDays > 0 ? $minFollowDays : null;

            if ($name === '') {
                throw new Exception('Seviye adı zorunludur.');
            }

            $lastScore = $db->query("SELECT COALESCE(MAX(required_score), -1) FROM loyalty_levels")->fetchColumn();
            if ($required < (float)$lastScore) {
                throw new Exception('Yeni seviyenin skoru son seviyeden düşük olamaz.');
            }

            $stmt = $db->prepare("INSERT INTO loyalty_levels (level_name, required_score, min_follow_days) VALUES (?, ?, ?)");
            $stmt->execute([$name, $required, $minFollowDays]);

            echo json_encode(['status' => 'success', 'message' => 'Seviye eklendi.']);
            exit;
        }

        if ($action === 'update_level') {
            $id = asInt($input['id'] ?? 0, 0);
            $name = trim($input['level_name'] ?? '');
            $required = asFloat($input['required_score'] ?? 0, 0);
            $minFollowDays = asInt($input['min_follow_days'] ?? 0, 0);
            if ($minFollowDays < 0) {
                $minFollowDays = 0;
            }
            $minFollowDays = $minFollowDays > 0 ? $minFollowDays : null;

            if ($id <= 0 || $name === '') {
                throw new Exception('Geçersiz seviye verisi.');
            }

            $stmt = $db->prepare("UPDATE loyalty_levels SET level_name = ?, required_score = ?, min_follow_days = ? WHERE id = ?");
            $stmt->execute([$name, $required, $minFollowDays, $id]);

            echo json_encode(['status' => 'success', 'message' => 'Seviye güncellendi.']);
            exit;
        }

        if ($action === 'delete_level') {
            $id = asInt($input['id'] ?? 0, 0);
            if ($id <= 0) {
                throw new Exception('Geçersiz seviye ID.');
            }

            $maxId = (int)$db->query("SELECT COALESCE(MAX(id), 0) FROM loyalty_levels")->fetchColumn();
            if ($id !== $maxId) {
                throw new Exception('Sadece en son eklenen seviye silinebilir.');
            }

            $count = (int)$db->query("SELECT COUNT(*) FROM loyalty_levels")->fetchColumn();
            if ($count <= 1) {
                throw new Exception('En az 1 seviye kalmalıdır.');
            }

            $stmt = $db->prepare("DELETE FROM loyalty_levels WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['status' => 'success', 'message' => 'Seviye silindi.']);
            exit;
        }

        throw new Exception('Geçersiz action.');
    }

    throw new Exception('Method not allowed');
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
