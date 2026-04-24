<?php
session_start();
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Geçersiz istek.']);
    exit;
}

$alert_type = $_POST['alert_type'] ?? '';
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;
$message_template = trim($_POST['message_template'] ?? '');
$duration = (int)($_POST['duration'] ?? 5);
$volume = (int)($_POST['volume'] ?? 50);

$font_family = $_POST['font_family'] ?? 'Inter';
$text_color = $_POST['text_color'] ?? '#ffffff';
$highlight_color = $_POST['highlight_color'] ?? '#53FC18';
$text_style = $_POST['text_style'] ?? 'normal';
$text_effect = $_POST['text_effect'] ?? 'shadow';

if (empty($alert_type) || empty($message_template)) {
    echo json_encode(['status' => 'error', 'message' => 'Lütfen gerekli alanları doldurun.']);
    exit;
}

// Media klasörü kontrolü
$mediaDir = '../alertmedia/';
if (!is_dir($mediaDir)) {
    mkdir($mediaDir, 0777, true);
}

// Mevcut ayarları al
$stmt = $db->prepare("SELECT * FROM alert_settings WHERE alert_type = ?");
$stmt->execute([$alert_type]);
$existingAlert = $stmt->fetch(PDO::FETCH_ASSOC);

$image_path = $existingAlert ? $existingAlert['image_path'] : '';
$audio_path = $existingAlert ? $existingAlert['audio_path'] : '';

// Görsel yükleme
if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['image_file']['tmp_name'];
    $fileName = $_FILES['image_file']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $allowedfileExtensions = ['jpg', 'jpeg', 'gif', 'png', 'webp'];
    if (in_array($fileExtension, $allowedfileExtensions)) {
        $newFileName = $alert_type . '_image_' . time() . '.' . $fileExtension;
        $dest_path = $mediaDir . $newFileName;
        
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $image_path = 'alertmedia/' . $newFileName;
            
            // Eski dosyayı sil
            if ($existingAlert && !empty($existingAlert['image_path']) && file_exists('../' . $existingAlert['image_path'])) {
                unlink('../' . $existingAlert['image_path']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz görsel formatı.']);
        exit;
    }
}

// Ses yükleme
if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['audio_file']['tmp_name'];
    $fileName = $_FILES['audio_file']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $allowedfileExtensions = ['mp3', 'wav', 'ogg'];
    if (in_array($fileExtension, $allowedfileExtensions)) {
        $newFileName = $alert_type . '_audio_' . time() . '.' . $fileExtension;
        $dest_path = $mediaDir . $newFileName;
        
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $audio_path = 'alertmedia/' . $newFileName;
            
            // Eski dosyayı sil
            if ($existingAlert && !empty($existingAlert['audio_path']) && file_exists('../' . $existingAlert['audio_path'])) {
                unlink('../' . $existingAlert['audio_path']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Geçersiz ses formatı.']);
        exit;
    }
}

try {
    $stmt = $db->prepare("
        INSERT INTO alert_settings 
        (alert_type, is_active, message_template, image_path, audio_path, audio_volume, duration_seconds, font_family, text_color, highlight_color, text_style, text_effect) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        is_active = VALUES(is_active),
        message_template = VALUES(message_template),
        image_path = VALUES(image_path),
        audio_path = VALUES(audio_path),
        audio_volume = VALUES(audio_volume),
        duration_seconds = VALUES(duration_seconds),
        font_family = VALUES(font_family),
        text_color = VALUES(text_color),
        highlight_color = VALUES(highlight_color),
        text_style = VALUES(text_style),
        text_effect = VALUES(text_effect)
    ");
    
    $stmt->execute([
        $alert_type,
        $is_active,
        $message_template,
        $image_path,
        $audio_path,
        $volume,
        $duration,
        $font_family,
        $text_color,
        $highlight_color,
        $text_style,
        $text_effect
    ]);
    
    echo json_encode(['status' => 'success', 'message' => 'Ayarlar kaydedildi.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>