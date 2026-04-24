<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('Europe/Istanbul');

// Kick.com API Kimlik Bilgileri
define('KICK_CLIENT_ID', 'YOUROWNID');
define('KICK_CLIENT_SECRET', 'YOUROWNKEY');
define('KICK_BROADCASTER_ID', 'YOUROWNBROADCESTERID'); // Kanal sahibinin (Broadcaster) User ID'si
define('KICK_OAUTH_SCOPES', 'user:read channel:read chat:write events:subscribe moderation:ban moderation:chat_message:manage');


// Kendi local veya sunucu adresine göre burayı düzenle. Kick App ayarlarındakiyle BİREBİR aynı olmalıdır.
define('KICK_REDIRECT_URI', 'https://callbackurlhere');

// Veritabanı Ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'DBNAME');
define('DB_USER', 'USER');
define('DB_PASS', 'PASSWORDHERE');


try {
    $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
    $db->exec("SET time_zone = '+03:00'");


    $db->exec("CREATE TABLE IF NOT EXISTS chat_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        message_id VARCHAR(255) UNIQUE,
        sender_id VARCHAR(255),
        sender_username VARCHAR(255),
        sender_badges TEXT,
        content TEXT,
        created_at DATETIME
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS channel_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_type VARCHAR(50),
        username VARCHAR(255),
        description TEXT,
        created_at DATETIME
    )");

    // Kullanıcı istatistikleri ve durum tablosu
    $db->exec("CREATE TABLE IF NOT EXISTS chat_users (
        user_id VARCHAR(255) PRIMARY KEY,
        username VARCHAR(255),
        follow_date DATETIME NULL,
        message_count INT DEFAULT 0,
        ban_count INT DEFAULT 0,
        timeout_count INT DEFAULT 0,
        is_banned TINYINT(1) DEFAULT 0,
        timeout_expires_at DATETIME NULL,
        deleted_message_count INT DEFAULT 0,
        is_subscriber TINYINT(1) DEFAULT 0,
        is_vip TINYINT(1) DEFAULT 0,
        is_og TINYINT(1) DEFAULT 0,
        is_moderator TINYINT(1) DEFAULT 0
    )");
    try {
        $db->exec("ALTER TABLE chat_users ADD COLUMN is_subscriber TINYINT(1) DEFAULT 0");
        $db->exec("ALTER TABLE chat_users ADD COLUMN is_vip TINYINT(1) DEFAULT 0");
        $db->exec("ALTER TABLE chat_users ADD COLUMN is_og TINYINT(1) DEFAULT 0");
        $db->exec("ALTER TABLE chat_users ADD COLUMN is_moderator TINYINT(1) DEFAULT 0");
    } catch(PDOException $e) {}

    // Ban/Timeout geçmişi tablosu
    $db->exec("CREATE TABLE IF NOT EXISTS ban_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(255),
        username VARCHAR(255),
        action_type VARCHAR(50), -- 'ban' veya 'timeout'
        created_at DATETIME,
        expires_at DATETIME NULL,
        moderator_name VARCHAR(255),
        reason TEXT
    )");

    // bot_commands tablosuna cooldown ve last_used_at sütunlarını ekle
    try {
        $db->exec("ALTER TABLE bot_commands ADD COLUMN cooldown INT DEFAULT 60");
        $db->exec("ALTER TABLE bot_commands ADD COLUMN last_used_at DATETIME DEFAULT NULL");
    } catch(PDOException $e) {
        // Zaten varsa hata verecek, görmezden gelebiliriz.
    }

    // Bot ayarları tablosu (token vb. güvenli saklama)
    $db->exec("CREATE TABLE IF NOT EXISTS bot_settings (
        setting_key VARCHAR(100) PRIMARY KEY,
        setting_value TEXT NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Ortak ban havuzu kanalları
    $db->exec("CREATE TABLE IF NOT EXISTS shared_channels (
        id INT AUTO_INCREMENT PRIMARY KEY,
        channel_name VARCHAR(100) NOT NULL UNIQUE,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    try {
        $db->exec("ALTER TABLE shared_channels ADD COLUMN accepted_reasons JSON DEFAULT NULL");
    } catch(PDOException $e) {}

    // Ortak ban havuzu listesi
    $db->exec("CREATE TABLE IF NOT EXISTS shared_bans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL,
        reason TEXT,
        moderator_name VARCHAR(100),
        original_channel VARCHAR(100),
        evidence_messages JSON,
        ban_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY(username)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Bot bildirim ayarları
    $db->exec("CREATE TABLE IF NOT EXISTS chat_notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        event_type VARCHAR(50) NOT NULL UNIQUE,
        message_template TEXT NOT NULL,
        is_active TINYINT(1) DEFAULT 1,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Sadakat sistemi ayarları
    $db->exec("CREATE TABLE IF NOT EXISTS loyalty_settings (
        id TINYINT PRIMARY KEY,
        follow_days_step INT NOT NULL DEFAULT 30,
        follow_days_bonus_pct DECIMAL(8,3) NOT NULL DEFAULT 1.000,
        message_step INT NOT NULL DEFAULT 100,
        message_bonus_pct DECIMAL(8,3) NOT NULL DEFAULT 5.000,
        timeout_step INT NOT NULL DEFAULT 5,
        timeout_penalty_pct DECIMAL(8,3) NOT NULL DEFAULT 0.500,
        ban_step INT NOT NULL DEFAULT 1,
        ban_penalty_pct DECIMAL(8,3) NOT NULL DEFAULT 10.000,
        deleted_step INT NOT NULL DEFAULT 20,
        deleted_penalty_pct DECIMAL(8,3) NOT NULL DEFAULT 1.000,
        subscriber_bonus_pct DECIMAL(8,3) NOT NULL DEFAULT 10.000,
        vip_bonus_pct DECIMAL(8,3) NOT NULL DEFAULT 15.000,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Sadakat seviyeleri
    $db->exec("CREATE TABLE IF NOT EXISTS loyalty_levels (
        id INT AUTO_INCREMENT PRIMARY KEY,
        level_name VARCHAR(100) NOT NULL,
        required_score DECIMAL(12,2) NOT NULL,
        min_follow_days INT NULL DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    try {
        $db->exec("ALTER TABLE loyalty_levels ADD COLUMN min_follow_days INT NULL DEFAULT NULL");
    } catch(PDOException $e) {}

    // Tek satırlık ayar kaydı
    $db->exec("INSERT IGNORE INTO loyalty_settings (id) VALUES (1)");

    // Varsayılan seviye kaydı
    $levelCount = (int)$db->query("SELECT COUNT(*) FROM loyalty_levels")->fetchColumn();
    if ($levelCount === 0) {
        $db->exec("INSERT INTO loyalty_levels (level_name, required_score) VALUES
            ('Seviye 1', 0),
            ('Seviye 2', 500),
            ('Seviye 3', 1500)");
    }

    // Subathon Tabloları
    $db->exec("CREATE TABLE IF NOT EXISTS subathon (
        id TINYINT PRIMARY KEY,
        is_active TINYINT(1) DEFAULT 0,
        end_time DATETIME NULL,
        sec_sub INT DEFAULT 300,
        sec_resub INT DEFAULT 300,
        sec_gift INT DEFAULT 300,
        kicks_req INT DEFAULT 100,
        sec_kicks INT DEFAULT 60,
        timer_style VARCHAR(50) DEFAULT 'neon',
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $db->exec("INSERT IGNORE INTO subathon (id) VALUES (1)");

    try {
        $db->exec("ALTER TABLE subathon ADD COLUMN timer_style VARCHAR(50) DEFAULT 'neon'");
    } catch(PDOException $e) {}

    $db->exec("CREATE TABLE IF NOT EXISTS subathon_events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255),
        action_type VARCHAR(50),
        seconds_added INT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // Şarkı İstek Tabloları
    $db->exec("CREATE TABLE IF NOT EXISTS song_settings (
        id TINYINT PRIMARY KEY,
        is_active TINYINT(1) DEFAULT 0,
        command_name VARCHAR(50) DEFAULT '!istek',
        request_cost DECIMAL(12,2) DEFAULT 0,
        is_playing TINYINT(1) DEFAULT 0,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $db->exec("INSERT IGNORE INTO song_settings (id) VALUES (1)");

    $db->exec("CREATE TABLE IF NOT EXISTS song_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255),
        video_id VARCHAR(50),
        video_title VARCHAR(255),
        status ENUM('pending', 'playing', 'played') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    try {
        $db->exec("ALTER TABLE song_settings ADD COLUMN trigger_action VARCHAR(50) DEFAULT NULL");
        $db->exec("ALTER TABLE song_settings ADD COLUMN trigger_time BIGINT DEFAULT 0");
    } catch(PDOException $e) {}

    try {
        $db->exec("ALTER TABLE chat_users ADD COLUMN spent_score DECIMAL(12,2) DEFAULT 0");
    } catch(PDOException $e) {}

    // Repertuar Tabloları (Canlı Müzik / Peçete)
    $db->exec("CREATE TABLE IF NOT EXISTS repertoire_settings (
        id TINYINT PRIMARY KEY,
        is_active TINYINT(1) DEFAULT 0,
        request_command VARCHAR(50) DEFAULT '!peçete',
        list_command VARCHAR(50) DEFAULT '!repertuar',
        request_cost DECIMAL(12,2) DEFAULT 0,
        is_playing TINYINT(1) DEFAULT 0,
        trigger_action VARCHAR(50) DEFAULT NULL,
        trigger_time BIGINT DEFAULT 0,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    $db->exec("INSERT IGNORE INTO repertoire_settings (id) VALUES (1)");

    $db->exec("CREATE TABLE IF NOT EXISTS repertoire_songs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        song_name VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $db->exec("CREATE TABLE IF NOT EXISTS repertoire_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255),
        song_id INT,
        song_name VARCHAR(255),
        status ENUM('pending', 'playing', 'played') DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

/**
 * Veritabanından access_token'ı çeker
 */
function getAccessToken() {
    global $db;
    try {
        $stmt = $db->prepare("SELECT setting_value FROM bot_settings WHERE setting_key = 'access_token' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['setting_value'] : null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Token'ı veritabanına kaydeder
 */
function saveAccessToken($accessToken, $refreshToken = null) {
    global $db;
    $stmt = $db->prepare("INSERT INTO bot_settings (setting_key, setting_value) VALUES ('access_token', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
    $stmt->execute([$accessToken]);
    
    if ($refreshToken) {
        $stmt2 = $db->prepare("INSERT INTO bot_settings (setting_key, setting_value) VALUES ('refresh_token', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        $stmt2->execute([$refreshToken]);
    }
}



define('KICK_SCOPES', 'user:read channel:read chat:write events:subscribe moderation:ban moderation:chat_message:manage');

define('KICK_OAUTH_URL', 'https://id.kick.com/oauth/authorize');
define('KICK_TOKEN_URL', 'https://id.kick.com/oauth/token');

/**
 * Rastgele bir PKCE Code Verifier oluşturur
 */
function generateCodeVerifier($length = 64)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-._~';
    $verifier = '';
    for ($i = 0; $i < $length; $i++) {
        $verifier .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $verifier;
}

/**
 * Code Verifier kullanarak S256 Code Challenge oluşturur
 */
function generateCodeChallenge($verifier)
{
    $hash = hash('sha256', $verifier, true);
    return rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
}

/**
 * Kick OAuth Login URL'sini oluşturur
 */
function getKickLoginUrl()
{
    // Güvenlik için CSRF state oluştur
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth2state'] = $state;

    // PKCE (Proof Key for Code Exchange) oluştur
    $codeVerifier = generateCodeVerifier();
    $_SESSION['pkce_verifier'] = $codeVerifier;

    $codeChallenge = generateCodeChallenge($codeVerifier);

    $params = [
        'response_type' => 'code',
        'client_id' => KICK_CLIENT_ID,
        'redirect_uri' => KICK_REDIRECT_URI,
        'scope' => KICK_SCOPES,
        'state' => $state,
        'code_challenge' => $codeChallenge,
        'code_challenge_method' => 'S256'
    ];

    return KICK_OAUTH_URL . '?' . http_build_query($params);
}

/**
 * Geri dönen Authorization Code'u Access Token'a çevirir
 */
function exchangeCodeForToken($code, $verifier)
{
    $postData = http_build_query([
        'grant_type' => 'authorization_code',
        'client_id' => KICK_CLIENT_ID,
        'client_secret' => KICK_CLIENT_SECRET,
        'redirect_uri' => KICK_REDIRECT_URI,
        'code_verifier' => $verifier,
        'code' => $code
    ]);

    $ch = curl_init(KICK_TOKEN_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        return json_decode($response, true);
    } else {
        error_log("Kick OAuth Token Error: " . $response);
        return false;
    }
}

/**
 * Mevcut Refresh Token'ı kullanarak yeni bir Access Token alır
 */
function refreshKickToken()
{
    global $db;
    try {
        $stmt = $db->prepare("SELECT setting_value FROM bot_settings WHERE setting_key = 'refresh_token' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || empty($row['setting_value'])) return false;
        
        $refreshToken = $row['setting_value'];
        
        $postData = http_build_query([
            'grant_type' => 'refresh_token',
            'client_id' => KICK_CLIENT_ID,
            'client_secret' => KICK_CLIENT_SECRET,
            'refresh_token' => $refreshToken
        ]);

        $ch = curl_init(KICK_TOKEN_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $data = json_decode($response, true);
            if (isset($data['access_token'])) {
                saveAccessToken($data['access_token'], $data['refresh_token'] ?? null);
                $_SESSION['kick_access_token'] = $data['access_token']; // Session'ı da güncelle
                return $data['access_token'];
            }
        }
        return false;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Kick API'sine istek atar, 401 alırsa otomatik token yeniler ve tekrar dener.
 */
function kickApiRequest($endpoint, $method = 'GET', $data = null) {
    $accessToken = getAccessToken();
    if (!$accessToken && isset($_SESSION['kick_access_token'])) {
        $accessToken = $_SESSION['kick_access_token'];
    }

    $url = 'https://api.kick.com/public/v1/' . ltrim($endpoint, '/');
    
    $attemptRequest = function($token) use ($url, $method, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ];

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return ['code' => $httpCode, 'response' => $response];
    };

    // İlk deneme
    $result = $attemptRequest($accessToken);

    // 401 hatası (Unauthorized) alınırsa token yenile ve tekrar dene
    if ($result['code'] === 401) {
        $newToken = refreshKickToken();
        if ($newToken) {
            $result = $attemptRequest($newToken);
        }
    }

    return $result;
}
?>