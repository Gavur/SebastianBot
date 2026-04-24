<?php
require_once 'config.php';

// 1. Önce App Access Token (Client Credentials) alıyoruz
$tokenPostData = http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => KICK_CLIENT_ID,
    'client_secret' => KICK_CLIENT_SECRET
]);

$chToken = curl_init('https://id.kick.com/oauth/token');
curl_setopt($chToken, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chToken, CURLOPT_POST, true);
curl_setopt($chToken, CURLOPT_POSTFIELDS, $tokenPostData);
curl_setopt($chToken, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$tokenResponse = curl_exec($chToken);
$tokenHttpCode = curl_getinfo($chToken, CURLINFO_HTTP_CODE);
curl_close($chToken);

$tokenData = json_decode($tokenResponse, true);

if ($tokenHttpCode !== 200 || !isset($tokenData['access_token'])) {
    die("<h1>Hata: App Access Token Alınamadı</h1><pre>" . htmlspecialchars($tokenResponse) . "</pre>");
}

$appAccessToken = $tokenData['access_token'];

// 2. App Access Token ile KICK_BROADCASTER_ID (Senin Kanalın) için Webhook aboneliği başlatıyoruz
$postData = json_encode([
    'method' => 'webhook',
    'broadcaster_user_id' => (int)KICK_BROADCASTER_ID,
    'events' => [
        [
            'name' => 'chat.message.sent',
            'version' => 1
        ],
        [
            'name' => 'channel.followed',
            'version' => 1
        ],
        [
            'name' => 'channel.subscription.new',
            'version' => 1
        ],
        [
            'name' => 'channel.subscription.gifts',
            'version' => 1
        ],
        [
            'name' => 'livestream.status.updated',
            'version' => 1
        ]
    ]
]);

$ch = curl_init('https://api.kick.com/public/v1/events/subscriptions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $appAccessToken
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h1>Webhook Abonelik Sonucu (Ana Kanal İçin)</h1>";
if ($httpCode === 200) {
    echo "<p style='color:green;'>Başarılı! Artık (" . KICK_BROADCASTER_ID . ") ID'li kanaldaki mesajlar webhook.php dosyasına gelecek.</p>";
} else {
    echo "<p style='color:red;'>Hata ($httpCode):</p>";
}
echo "<pre>" . htmlspecialchars($response) . "</pre>";
echo "<br><a href='index.php'>Dashboard'a Dön</a>";
?>
