<?php
require_once 'config.php';

// Güvenlik: Zaten giriş yapılmışsa dashboard'a at
if (isset($_SESSION['kick_access_token'])) {
    header('Location: /index.php');
    exit;
}

// Kick OAuth Giriş URL'sini al ve yönlendir
$loginUrl = getKickLoginUrl();
header('Location: ' . $loginUrl);
exit;
?>
