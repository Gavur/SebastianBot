<?php
require_once 'config.php';

// Hata loglamayı aktif et
ini_set('display_errors', 1);
error_reporting(E_ALL);

function debugLog($msg)
{
    file_put_contents('webhook_log.txt', date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
}

function getAvailableLoyaltyScore($userId, $db) {
    $stmt = $db->prepare("SELECT * FROM chat_users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$u) return 0;
    
    $stmtSet = $db->query("SELECT * FROM loyalty_settings WHERE id = 1 LIMIT 1");
    $s = $stmtSet->fetch(PDO::FETCH_ASSOC);
    if (!$s) return 0;
    
    $base = (float)$u['message_count'];
    $followDays = !empty($u['follow_date']) ? floor((time() - strtotime($u['follow_date'])) / 86400) : 0;
    
    $followStep = max(1, (int)$s['follow_days_step']);
    $messageStep = max(1, (int)$s['message_step']);
    $timeoutStep = max(1, (int)$s['timeout_step']);
    $banStep = max(1, (int)$s['ban_step']);
    $deletedStep = max(1, (int)$s['deleted_step']);

    $totalPct = (floor($followDays / $followStep) * (float)$s['follow_days_bonus_pct']) +
                (floor(((int)$u['message_count']) / $messageStep) * (float)$s['message_bonus_pct']) +
                (((int)$u['is_subscriber'] === 1) ? (float)$s['subscriber_bonus_pct'] : 0.0) +
                (((int)$u['is_vip'] === 1) ? (float)$s['vip_bonus_pct'] : 0.0) -
                (floor(((int)$u['timeout_count']) / $timeoutStep) * (float)$s['timeout_penalty_pct']) -
                (floor(((int)$u['ban_count']) / $banStep) * (float)$s['ban_penalty_pct']) -
                (floor(((int)$u['deleted_message_count']) / $deletedStep) * (float)$s['deleted_penalty_pct']);

    $score = $base * (1 + ($totalPct / 100));
    return max(0, ($score < 0 ? 0 : $score) - (float)($u['spent_score'] ?? 0));
}

/**
 * Kick API üzerinden bot mesajı gönderir
 */
function sendBotMessage($message, $retry = true)
{
    $accessToken = getAccessToken();
    if (!$accessToken) {
        debugLog("sendBotMessage: access_token bulunamadı!");
        return false;
    }

    $postData = json_encode([
        'broadcaster_user_id' => (int)KICK_BROADCASTER_ID,
        'chatroom_id' => (int)KICK_BROADCASTER_ID,
        'content' => $message,
        'type' => 'bot'
    ]);

    $ch = curl_init('https://api.kick.com/public/v1/chat');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 || $httpCode === 201) {
        debugLog("sendBotMessage: Mesaj gönderildi -> $message");
        return true;
    } elseif ($httpCode === 401 && $retry) {
        debugLog("sendBotMessage: 401 Unauthorized. Token yenileniyor...");
        if (refreshKickToken()) {
            return sendBotMessage($message, false);
        } else {
            debugLog("sendBotMessage: Token yenileme başarısız!");
            return false;
        }
    } else {
        debugLog("sendBotMessage: HATA ($httpCode) -> $response");
        return false;
    }
}

/**
 * Chat bildirimlerini tetikler
 */
function triggerNotification($eventType, $variables)
{
    global $db;
    try {
        $stmt = $db->prepare("SELECT message_template FROM chat_notifications WHERE event_type = ? AND is_active = 1 LIMIT 1");
        $stmt->execute([$eventType]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['message_template'])) {
            $msg = $row['message_template'];
            foreach ($variables as $key => $val) {
                $msg = str_replace('{' . $key . '}', $val, $msg);
            }
            sendBotMessage($msg);
            debugLog("Notification sent for event '$eventType': $msg");
        }
    } catch (Exception $e) {
        debugLog("triggerNotification Error: " . $e->getMessage());
    }
}

function triggerSubathon($user, $type, $amount = 1) {
    global $db;
    try {
        $stmt = $db->query("SELECT * FROM subathon WHERE id = 1 AND is_active = 1");
        $subData = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$subData) return;
        
        $seconds = 0;
        $actionLabel = '';
        
        if ($type === 'sub') { $seconds = (int)$subData['sec_sub']; $actionLabel = 'Abone'; }
        elseif ($type === 'gift') { $seconds = (int)$subData['sec_gift'] * $amount; $actionLabel = "$amount Hediye"; }
        elseif ($type === 'resub') { $seconds = (int)$subData['sec_resub']; $actionLabel = 'Yeniden Abone'; }
        elseif ($type === 'kicks') {
            $kicksReq = max(1, (int)$subData['kicks_req']);
            $seconds = round(($amount / $kicksReq) * (int)$subData['sec_kicks']);
            $actionLabel = "$amount Kicks";
        }
        
        if ($seconds > 0) {
            $db->prepare("UPDATE subathon SET end_time = DATE_ADD(GREATEST(COALESCE(end_time, NOW()), NOW()), INTERVAL ? SECOND) WHERE id = 1")->execute([$seconds]);
            $db->prepare("INSERT INTO subathon_events (username, action_type, seconds_added, created_at) VALUES (?, ?, ?, NOW())")->execute([$user, $actionLabel, $seconds]);
        }
    } catch (Exception $e) {
        debugLog("Subathon Error: " . $e->getMessage());
    }
}

debugLog("Webhook endpoint called.");

// Kick.com'dan gelen istek sadece POST olmalıdır.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    debugLog("Method Not Allowed: " . $_SERVER['REQUEST_METHOD']);
    http_response_code(405);
    exit("Method Not Allowed");
}

// Tüm Headerları logla
$headers = getallheaders();
debugLog("Headers: " . json_encode($headers));

// Kick webhook headerlarını kontrol et
$eventType = '';
foreach ($headers as $key => $value) {
    if (strtolower($key) === 'kick-event-type') {
        $eventType = $value;
    }
}

$validEvents = [
    'chat.message.sent', 
    'channel.followed', 
    'channel.subscription.new', 
    'channel.subscription.gifts',
    'channel.subscription.renewal',
    'chat.kicks_sent',
        'channel.hosted',
    'channel.moderation.timeout',
    'channel.moderation.untimeout',
    'channel.moderation.ban',
    'channel.moderation.unban',
    'moderation.banned',
    'moderation.unbanned'
];

if (!in_array($eventType, $validEvents)) {
    debugLog("Ignored event type: " . $eventType);
    http_response_code(200);
    exit("Ignored event type");
}

// Gelen JSON verisini oku
$payload = file_get_contents('php://input');
debugLog("Payload: " . $payload);
$data = json_decode($payload, true);

if (!$data) {
    debugLog("Invalid payload structure.");
    http_response_code(400);
    exit("Invalid payload");
}

try {
    global $db;

    if ($eventType === 'chat.message.sent') {
        if (!isset($data['message_id']))
            throw new Exception("No message_id");
        $messageId = $data['message_id'];
        $senderId = $data['sender']['user_id'] ?? $data['sender']['id'] ?? '';
        $senderUsername = $data['sender']['username'] ?? 'Unknown';
        $content = $data['content'] ?? '';

        $badges = '[]';
        $isSubscriber = 0;
        if (isset($data['sender']['identity']['badges'])) {
            $badges = json_encode($data['sender']['identity']['badges']);
            foreach ($data['sender']['identity']['badges'] as $badge) {
                if (isset($badge['type']) && ($badge['type'] === 'subscriber' || $badge['type'] === 'founder')) {
                    $isSubscriber = 1;
                    break;
                }
            }
        }
        $createdAt = date('Y-m-d H:i:s', strtotime($data['created_at'] ?? 'now'));

        // Mesajı kaydet
        $stmt = $db->prepare("INSERT IGNORE INTO chat_messages (message_id, sender_id, sender_username, sender_badges, content, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$messageId, $senderId, $senderUsername, $badges, $content, $createdAt]);

        $isCommand = (strpos(trim($content), '!') === 0);

        
        
        if ($senderId) {
            if (!$isCommand) {
                $stmtUser = $db->prepare("INSERT INTO chat_users (user_id, username, message_count, is_subscriber) VALUES (?, ?, 1, ?) ON DUPLICATE KEY UPDATE username=VALUES(username), message_count=message_count+1, is_subscriber=VALUES(is_subscriber)");
                $stmtUser->execute([$senderId, $senderUsername, $isSubscriber]);
            } else {
                $stmtUser = $db->prepare("INSERT INTO chat_users (user_id, username, message_count, is_subscriber) VALUES (?, ?, 0, ?) ON DUPLICATE KEY UPDATE username=VALUES(username), is_subscriber=VALUES(is_subscriber)");
                $stmtUser->execute([$senderId, $senderUsername, $isSubscriber]);
            }
        }

        debugLog("Successfully saved chat message: $content");

        if (strpos($content, '!') === 0) {
            $cmdParts = explode(' ', $content);
            $cmdName = strtolower(trim($cmdParts[0]));
            
        
            $stmtCmd = $db->prepare("SELECT * FROM bot_commands WHERE command_name = ? AND is_active = 1 AND command_type = 'chat' AND (last_used_at IS NULL OR TIMESTAMPDIFF(SECOND, last_used_at, NOW()) >= COALESCE(cooldown, 60)) LIMIT 1");
            $stmtCmd->execute([$cmdName]);
            $cmd = $stmtCmd->fetch(PDO::FETCH_ASSOC);
            
            if ($cmd) {
                // Değişkenleri değiştir
                $responseMsg = $cmd['response'];
                $responseMsg = str_replace('$(user)', '@' . $senderUsername, $responseMsg);
                $responseMsg = str_replace('$(channel)', $data['broadcaster']['username'] ?? 'Kanal', $responseMsg);
                $responseMsg = str_replace('$(count)', ($cmd['usage_count'] + 1), $responseMsg);
                
                if (strpos($responseMsg, '$(score)') !== false) {
                    $userScore = getAvailableLoyaltyScore($senderId, $db);
                    $responseMsg = str_replace('$(score)', round($userScore, 2), $responseMsg);
                }
                
                // Kullanım sayısını ve son kullanım zamanını güncelle (tek sorgu, atomik)
                $db->prepare("UPDATE bot_commands SET usage_count = usage_count + 1, last_used_at = NOW() WHERE id = ?")->execute([$cmd['id']]);
                
                // Kick API'ye mesaj gönder
                sendBotMessage($responseMsg);
                debugLog("Bot command triggered: $cmdName -> $responseMsg");
            }
        }
        
        
        $stmtSong = $db->query("SELECT * FROM song_settings WHERE id = 1");
        $songSettings = $stmtSong->fetch(PDO::FETCH_ASSOC);
        
        if ($songSettings && $songSettings['is_active'] == 1) {
            $songCmd = strtolower(trim($songSettings['command_name']));
            if ($songCmd !== '' && strpos(strtolower($content), $songCmd) === 0) {
                $parts = explode(' ', $content);
                if (count($parts) > 1) {
                    $url = $parts[1];
                    // URL'den YouTube ID çıkart
                    if (preg_match('/(?:v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
                        $videoId = $matches[1];
                        $cost = (float)$songSettings['request_cost'];
                        $canAfford = true;
                        
                        if ($cost > 0) {
                            $availScore = getAvailableLoyaltyScore($senderId, $db);
                            if ($availScore < $cost) {
                                $canAfford = false;
                                sendBotMessage("@{$senderUsername}, bu şarkıyı istemek için yeterli sadakat skorun yok! (Gerekli: {$cost}, Senin: " . round($availScore, 2) . ")");
                            }
                        }
                        
                        if ($canAfford) {
                            $noembed = @file_get_contents("https://noembed.com/embed?url=https://www.youtube.com/watch?v={$videoId}");
                            $title = $noembed ? (json_decode($noembed, true)['title'] ?? "Bilinmeyen Şarkı") : "Bilinmeyen Şarkı";
                            
                            if ($cost > 0) {
                                $stmtUserScore = $db->prepare("SELECT spent_score FROM chat_users WHERE user_id = ?");
                                $stmtUserScore->execute([$senderId]);
                                $userRow = $stmtUserScore->fetch(PDO::FETCH_ASSOC);
                                $newSpent = ($userRow ? (float)$userRow['spent_score'] : 0.0) + $cost;
                                $db->prepare("UPDATE chat_users SET spent_score = ? WHERE user_id = ?")->execute([$newSpent, $senderId]);
                            }
                            
                            $db->prepare("INSERT INTO song_requests (username, video_id, video_title, status, created_at) VALUES (?, ?, ?, 'pending', NOW())")->execute([$senderUsername, $videoId, $title]);
                            sendBotMessage("@{$senderUsername}, \"{$title}\" sıraya eklendi!");
                        }
                    } else {
                        sendBotMessage("@{$senderUsername}, lütfen geçerli bir YouTube linki girin.");
                    }
                }
            }
        }
        
        $stmtRep = $db->query("SELECT * FROM repertoire_settings WHERE id = 1");
        $repSettings = $stmtRep->fetch(PDO::FETCH_ASSOC);
        
        if ($repSettings && $repSettings['is_active'] == 1) {
            $reqCmd = strtolower(trim($repSettings['request_command']));
            $listCmd = strtolower(trim($repSettings['list_command']));
            $contentLower = strtolower(trim($content));
            
            if ($listCmd !== '' && $contentLower === $listCmd) {
                $db->exec("UPDATE repertoire_settings SET trigger_action = 'list_repertoire', trigger_time = UNIX_TIMESTAMP() WHERE id = 1");
                sendBotMessage("@{$senderUsername}, repertuar listesi ekranda gösteriliyor!");
            } elseif ($reqCmd !== '' && strpos($contentLower, $reqCmd) === 0) {
                $songCodeStr = trim(substr($contentLower, strlen($reqCmd)));
                $songCode = (int)$songCodeStr;
                
                if ($songCode > 0) {
                    $stmtCheck = $db->prepare("SELECT * FROM repertoire_songs WHERE id = ?");
                    $stmtCheck->execute([$songCode]);
                    $songInfo = $stmtCheck->fetch(PDO::FETCH_ASSOC);
                    
                    if ($songInfo) {
                        $cost = (float)$repSettings['request_cost'];
                        $canAfford = true;
                        if ($cost > 0) {
                            $availScore = getAvailableLoyaltyScore($senderId, $db);
                            if ($availScore < $cost) { $canAfford = false; sendBotMessage("@{$senderUsername}, bu isteği göndermek için yeterli skorun yok! (Gerekli: {$cost})"); }
                        }
                        if ($canAfford) {
                            if ($cost > 0) {
                                $stmtUserScore = $db->prepare("SELECT spent_score FROM chat_users WHERE user_id = ?"); $stmtUserScore->execute([$senderId]);
                                $userRow = $stmtUserScore->fetch(PDO::FETCH_ASSOC); $newSpent = ($userRow ? (float)$userRow['spent_score'] : 0.0) + $cost;
                                $db->prepare("UPDATE chat_users SET spent_score = ? WHERE user_id = ?")->execute([$newSpent, $senderId]);
                            }
                            $db->prepare("INSERT INTO repertoire_requests (username, song_id, song_name, status, created_at) VALUES (?, ?, ?, 'pending', NOW())")->execute([$senderUsername, $songCode, $songInfo['song_name']]);
                            sendBotMessage("@{$senderUsername}, {$songCode} numaralı \"{$songInfo['song_name']}\" istek (peçete) sırasına eklendi!");
                        }
                    } else { sendBotMessage("@{$senderUsername}, {$songCode} numaralı parça repertuarda bulunamadı."); }
                }
            }
        }

    } elseif ($eventType === 'channel.followed') {
        $followerId = $data['follower']['user_id'] ?? '';
        $follower = $data['follower']['username'] ?? 'Yeni Takipçi';
        $createdAt = date('Y-m-d H:i:s');
        
        $desc = 'takip etti';
        $isLive = 0;
        $category = null;
        $streamTitle = null;
        
        // Yayın durumunu kontrol et
        if (file_exists('stream_status.json')) {
            $streamStatus = json_decode(file_get_contents('stream_status.json'), true);
            if (!empty($streamStatus['is_live'])) {
                $isLive = 1;
                $streamTitle = $streamStatus['title'] ?? null;
                $category = $streamStatus['category'] ?? null;
                $desc .= ' (Yayındayken)';
            }
        }

        if ($followerId) {
            // Kullanıcı daha önce takip etmiş mi diye kontrol et
            $stmtCheck = $db->prepare("SELECT follow_date FROM chat_users WHERE user_id = ?");
            $stmtCheck->execute([$followerId]);
            $userRow = $stmtCheck->fetch();
            
            if ($userRow && $userRow['follow_date'] !== null) {
                $desc = str_replace('takip etti', 'tekrar takip etti', $desc);
                triggerNotification('refollow', ['username' => $follower]);
            } else {
                triggerNotification('follow', ['username' => $follower]);
            }
            
            // Kullanıcıyı veritabanına ekle/güncelle (Takip tarihi)
            $stmtUser = $db->prepare("INSERT INTO chat_users (user_id, username, follow_date, followed_during_stream, followed_stream_category, followed_stream_title) VALUES (?, ?, NOW(), ?, ?, ?) ON DUPLICATE KEY UPDATE username=VALUES(username), follow_date=VALUES(follow_date), followed_during_stream=VALUES(followed_during_stream), followed_stream_category=VALUES(followed_stream_category), followed_stream_title=VALUES(followed_stream_title)");
            $stmtUser->execute([$followerId, $follower, $isLive, $category, $streamTitle]);
        } else {
            triggerNotification('follow', ['username' => $follower]);
        }
        
        $stmt = $db->prepare("INSERT INTO channel_events (event_type, username, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute(['follow', $follower, $desc]);
        
        debugLog("Successfully saved follow event: $follower ($desc)");
        
    } elseif ($eventType === 'livestream.status.updated') {
        $statusData = [
            'is_live' => $data['is_live'] ?? false,
            'title' => $data['title'] ?? '',
            'category' => $data['category']['name'] ?? null
        ];
        file_put_contents('stream_status.json', json_encode($statusData));
        debugLog("Stream status updated: " . ($statusData['is_live'] ? "LIVE" : "OFFLINE"));
        
    } elseif ($eventType === 'channel.subscription.new') {
        $subscriber = $data['subscriber']['username'] ?? 'Yeni Abone';
        $stmt = $db->prepare("INSERT INTO channel_events (event_type, username, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute(['sub', $subscriber, 'Abone oldu!']);
        triggerNotification('subscription', ['username' => $subscriber, 'months' => '1']);
        triggerSubathon($subscriber, 'sub');
        debugLog("Successfully saved sub event: $subscriber");

    } elseif ($eventType === 'channel.subscription.gifts') {
        $gifter = $data['gifter']['username'] ?? 'Gizli Kahraman';
        $count = $data['gifted_subscriptions'] ?? 1;
        $stmt = $db->prepare("INSERT INTO channel_events (event_type, username, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute(['gift', $gifter, "$count kişiye abonelik hediye etti!"]);
        triggerSubathon($gifter, 'gift', $count);
        debugLog("Successfully saved gift sub event: $gifter");

    } elseif ($eventType === 'channel.subscription.renewal') {
        $subscriber = $data['subscriber']['username'] ?? 'Abone';
        $months = $data['duration'] ?? $data['months'] ?? 2;
        $stmt = $db->prepare("INSERT INTO channel_events (event_type, username, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute(['resub', $subscriber, "$months. ayında aboneliğini yeniledi!"]);
        triggerNotification('resubscription', ['username' => $subscriber, 'months' => $months]);
        triggerSubathon($subscriber, 'resub');
        debugLog("Successfully saved resub event: $subscriber");

    } elseif ($eventType === 'chat.kicks_sent') {
        $sender = $data['sender']['username'] ?? 'Kullanıcı';
        $amount = $data['kicks'] ?? $data['amount'] ?? 1;
        $stmt = $db->prepare("INSERT INTO channel_events (event_type, username, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute(['kicks', $sender, "$amount Kicks gönderdi!"]);
        triggerNotification('kicks', ['username' => $sender, 'amount' => $amount]);
        triggerSubathon($sender, 'kicks', $amount);
        debugLog("Successfully saved kicks event: $sender");

    } elseif ($eventType === 'channel.hosted') {
        $hoster = $data['hoster']['username'] ?? $data['channel']['username'] ?? 'Biri';
        $viewers = $data['viewers'] ?? 0;
        $stmt = $db->prepare("INSERT INTO channel_events (event_type, username, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute(['host', $hoster, "$viewers izleyiciyle host attı!"]);
        triggerNotification('host', ['username' => $hoster, 'viewers' => $viewers]);
        debugLog("Successfully saved host event: $hoster");

    } elseif ($eventType === 'channel.moderation.timeout' || $eventType === 'channel.moderation.ban' || $eventType === 'moderation.banned') {
        $userObj = $data['banned_user'] ?? $data['user'] ?? null;
        $user = $userObj['username'] ?? 'Kullanıcı';
        $userId = $userObj['user_id'] ?? null;
        $meta = $data['metadata'] ?? $data;
        $reason = $meta['reason'] ?? 'Belirtilmedi';
        $expiresAtRaw = $meta['expires_at'] ?? null;
        $expiresAt = $expiresAtRaw ? date('Y-m-d H:i:s', strtotime($expiresAtRaw)) : null;
        $modName = $data['moderator']['username'] ?? 'Sistem';

        $isTimeout = $expiresAt !== null;
        
        if ($userId) {
            // chat_users güncelle
            if (!$isTimeout) {
                $db->prepare("UPDATE chat_users SET is_banned = 1, ban_count = ban_count + 1 WHERE user_id = ?")->execute([$userId]);
            } else {
                $db->prepare("UPDATE chat_users SET timeout_expires_at = ?, timeout_count = timeout_count + 1 WHERE user_id = ?")->execute([$expiresAt, $userId]);
            }
            
            // ban_records logla
            $stmtLog = $db->prepare("INSERT INTO ban_records (user_id, username, action_type, created_at, expires_at, moderator_name, reason) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
            $stmtLog->execute([$userId, $user, $isTimeout ? 'timeout' : 'ban', $expiresAt, $modName, $reason]);
        }

        if ($isTimeout) {
            // Süreyi hesapla (dakika bazında)
            $duration = 'belirsiz';
            if ($expiresAt) {
                $diff = strtotime($expiresAt) - time();
                $duration = round($diff / 60);
            }
            triggerNotification('timeout', ['username' => $user, 'duration' => $duration, 'reason' => $reason]);
        } else {
            triggerNotification('ban', ['username' => $user, 'reason' => $reason]);
        }
        
    } elseif ($eventType === 'channel.moderation.untimeout' || $eventType === 'channel.moderation.unban' || $eventType === 'moderation.unbanned') {
        $userObj = $data['user'] ?? $data['unbanned_user'] ?? null;
        $user = $userObj['username'] ?? 'Kullanıcı';
        $userId = $userObj['user_id'] ?? null;
        
        if ($userId) {
            $db->prepare("UPDATE chat_users SET timeout_expires_at = NULL, is_banned = 0 WHERE user_id = ?")->execute([$userId]);
        }
        
        triggerNotification('unban', ['username' => $user]);
    }

    http_response_code(200);
    echo "OK";

} catch (Exception $e) {
    debugLog("DB/Webhook Error: " . $e->getMessage());
    error_log("Webhook Error: " . $e->getMessage());
    http_response_code(500);
    echo "Server Error";
}
?>