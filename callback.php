<?php
require_once 'config.php';

if (isset($_GET['error'])) {
    die("Kick Yetkilendirme Hatası: " . htmlspecialchars($_GET['error_description'] ?? $_GET['error']));
}

if (!isset($_GET['code']) || !isset($_GET['state'])) {
    die("Gerekli yetkilendirme parametreleri eksik.");
}

if ($_GET['state'] !== $_SESSION['oauth2state']) {
    unset($_SESSION['oauth2state']);
    die("Geçersiz State (CSRF Koruması). Lütfen tekrar giriş yapmayı deneyin.");
}

if (!isset($_SESSION['pkce_verifier'])) {
    die("PKCE Verifier oturumda bulunamadı. Lütfen tekrar giriş yapmayı deneyin.");
}

$tokenData = exchangeCodeForToken($_GET['code'], $_SESSION['pkce_verifier']);

unset($_SESSION['oauth2state']);
unset($_SESSION['pkce_verifier']);

if ($tokenData && isset($tokenData['access_token'])) {
    $_SESSION['kick_access_token'] = $tokenData['access_token'];
    
    if (isset($tokenData['refresh_token'])) {
        $_SESSION['kick_refresh_token'] = $tokenData['refresh_token'];
    }
    
    saveAccessToken($tokenData['access_token'], $tokenData['refresh_token'] ?? null);
    
    header('Location: /index.php');
    exit;
} else {
    die("Token alınamadı. Lütfen ayarlarınızı ve config dosyanızı kontrol edin.");
}
?>
