<?php
/**
 * Timer Komutları Çalıştırıcı
 * 
 * Bu script cron job ile her dakika çalıştırılmalıdır:
 * * * * * * php /path/to/SebastianChatBot/cron_timers.php
 * 
 * Aktif timer komutlarını kontrol eder ve süreleri dolmuş olanları chate gönderir.
 */

require_once __DIR__ . '/config.php';

function timerLog($msg)
{
    file_put_contents(__DIR__ . '/timer_log.txt', date('[Y-m-d H:i:s] ') . $msg . PHP_EOL, FILE_APPEND);
}

function sendTimerMessage($message)
{
    $accessToken = getAccessToken();
    if (!$accessToken) {
        timerLog("access_token bulunamadı!");
        return false;
    }

    $postData = json_encode([
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
        timerLog("Mesaj gönderildi: $message");
        return true;
    } else {
        timerLog("HATA ($httpCode): $response");
        return false;
    }
}

try {
    global $db;

    timerLog("=== Cron çalıştı ===");

    // Aktif ve süresi dolmuş timer komutlarını doğrudan MySQL'den çek
    $stmt = $db->query("SELECT * FROM bot_commands WHERE command_type = 'timer' AND is_active = 1 AND timer_interval > 0 AND (last_used_at IS NULL OR TIMESTAMPDIFF(SECOND, last_used_at, NOW()) >= timer_interval * 60)");
    $timers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    timerLog("Gönderilecek timer sayısı: " . count($timers));

    foreach ($timers as $timer) {
        $responseMsg = $timer['response'];
        $responseMsg = str_replace('$(user)', '', $responseMsg);
        $responseMsg = str_replace('$(channel)', 'Kanal', $responseMsg);
        $responseMsg = str_replace('$(count)', ($timer['usage_count'] + 1), $responseMsg);
        $responseMsg = str_replace('$(score)', '', $responseMsg);

        timerLog("Gönderiliyor: {$timer['command_name']} -> $responseMsg");
        if (sendTimerMessage($responseMsg)) {
            $db->prepare("UPDATE bot_commands SET usage_count = usage_count + 1, last_used_at = NOW() WHERE id = ?")->execute([$timer['id']]);
            timerLog("BAŞARILI: {$timer['command_name']}");
        } else {
            timerLog("BAŞARISIZ: {$timer['command_name']}");
        }
    }

} catch (Exception $e) {
    timerLog("HATA: " . $e->getMessage());
}
?>
