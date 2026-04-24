<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    global $db;

    // 1. Kick API'den Yayın Durumunu Çek
    $tokensFile = '../kick_tokens.json';
    $streamData = [
        'is_live' => false,
        'viewers' => 0,
        'started_at' => null,
        'category' => 'Bilinmiyor'
    ];

    $accessToken = getAccessToken();
    if ($accessToken) {
        $url = "https://api.kick.com/public/v1/channels?broadcaster_user_id=" . KICK_BROADCASTER_ID;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $accessToken,
            "Accept: application/json"
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            $data = json_decode($response, true);
            if (!empty($data['data']) && count($data['data']) > 0) {
                $channel = $data['data'][0];
                if (isset($channel['stream']) && $channel['stream']['is_live']) {
                    $streamData['is_live'] = true;
                    $streamData['viewers'] = $channel['stream']['viewer_count'] ?? 0;
                    $streamData['started_at'] = $channel['stream']['start_time'] ?? null;
                    $streamData['category'] = $channel['category']['name'] ?? 'Bilinmiyor';
                }
            }
        }
    }

    
    $msgStats = [
        'total_messages' => 0,
        'unique_users' => 0,
        'total_followers' => 0,
        'new_followers_24h' => 0,
        'refollowers_24h' => 0
    ];

    $stmtMsg = $db->query("SELECT COUNT(*) as total FROM chat_messages WHERE created_at >= NOW() - INTERVAL 5 HOUR");
    $rowMsg = $stmtMsg->fetch(PDO::FETCH_ASSOC);
    if ($rowMsg) {
        $msgStats['total_messages'] = (int)$rowMsg['total'];
    }

    $stmtUsers = $db->query("SELECT COUNT(DISTINCT sender_id) as total FROM chat_messages WHERE created_at >= NOW() - INTERVAL 5 HOUR");
    $rowUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC);
    if ($rowUsers) {
        $msgStats['unique_users'] = (int)$rowUsers['total'];
    }

    $stmtFollowers = $db->query("SELECT COUNT(*) as total FROM chat_users WHERE follow_date IS NOT NULL");
    $rowFollowers = $stmtFollowers->fetch(PDO::FETCH_ASSOC);
    if ($rowFollowers) {
        $msgStats['total_followers'] = (int)$rowFollowers['total'];
    }

    $stmtNewFollowers = $db->query("SELECT COUNT(*) as total FROM channel_events WHERE event_type = 'follow' AND description NOT LIKE '%tekrar%' AND created_at >= NOW() - INTERVAL 24 HOUR");
    $rowNewFollowers = $stmtNewFollowers->fetch(PDO::FETCH_ASSOC);
    if ($rowNewFollowers) {
        $msgStats['new_followers_24h'] = (int)$rowNewFollowers['total'];
    }

    $stmtReFollowers = $db->query("SELECT COUNT(*) as total FROM channel_events WHERE event_type = 'follow' AND description LIKE '%tekrar%' AND created_at >= NOW() - INTERVAL 24 HOUR");
    $rowReFollowers = $stmtReFollowers->fetch(PDO::FETCH_ASSOC);
    if ($rowReFollowers) {
        $msgStats['refollowers_24h'] = (int)$rowReFollowers['total'];
    }

    echo json_encode([
        'status' => 'success',
        'stream' => $streamData,
        'stats' => $msgStats
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
