<<<<<<< HEAD
<?php
session_start();
require_once 'config.php';

// Alert tipleri ve varsayılanları
$alertTypes = [
    'follow' => [
        'title' => 'Yeni Takipçi',
        'icon' => 'fa-solid fa-heart',
        'vars' => '{username}',
        'default_msg' => '{username} aileye katıldı!'
    ],
    'refollow' => [
        'title' => 'Tekrar Takip Edenler',
        'icon' => 'fa-solid fa-user-plus',
        'vars' => '{username}',
        'default_msg' => '{username} tekrar aramıza katıldı!'
    ],
    'sub' => [
        'title' => 'Yeni Abone',
        'icon' => 'fa-solid fa-star',
        'vars' => '{username}',
        'default_msg' => '{username} abone oldu!'
    ],
    'resub' => [
        'title' => 'Abonelik Yenileme',
        'icon' => 'fa-solid fa-star-half-stroke',
        'vars' => '{username}, {months}',
        'default_msg' => '{username} {months}. ayında aboneliğini yeniledi!'
    ],
    'gift' => [
        'title' => 'Hediye Abonelik',
        'icon' => 'fa-solid fa-gift',
        'vars' => '{username}, {count}',
        'default_msg' => '{username} {count} kişiye abonelik hediye etti!'
    ],
    'kicks' => [
        'title' => 'Kicks Gönderimi',
        'icon' => 'fa-solid fa-coins',
        'vars' => '{username}, {amount}',
        'default_msg' => '{username} {amount} Kicks gönderdi!'
    ],
    'host' => [
        'title' => 'Host',
        'icon' => 'fa-solid fa-satellite-dish',
        'vars' => '{username}, {viewers}',
        'default_msg' => '{username} {viewers} izleyiciyle host attı!'
    ]
];

// Veritabanından tüm ayarları çek
$stmt = $db->query("SELECT * FROM alert_settings");
$dbAlerts = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dbAlerts[$row['alert_type']] = $row;
}

// Ayarları birleştir
$alerts = [];
foreach ($alertTypes as $type => $info) {
    if (isset($dbAlerts[$type])) {
        $alerts[$type] = $dbAlerts[$type];
    } else {
        $alerts[$type] = [
            'is_active' => 1,
            'message_template' => $info['default_msg'],
            'image_path' => '',
            'audio_path' => '',
            'audio_volume' => 50,
            'duration_seconds' => 5,
            'font_family' => 'Inter',
            'text_color' => '#ffffff',
            'highlight_color' => '#53FC18',
            'text_style' => 'normal',
            'text_effect' => 'shadow'
        ];
    }
    $alerts[$type]['info'] = $info;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Alarm Ayarları - SebastianBot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .main-content { flex: 1; padding: 30px; overflow-y: auto; height: 100vh; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-size: 24px; font-weight: 700; color: #fff; }
        .alert-card { background: var(--bg-panel); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; display: flex; flex-direction: column; gap: 20px; }
        .alert-card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 5px; }
        .alert-title { font-size: 18px; font-weight: 600; color: var(--primary-color); display: flex; align-items: center; gap: 10px; }
        .alert-card form { display: flex; flex-direction: column; gap: 24px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 13px; color: #a1a1aa; font-weight: 500; }
        .form-input { background: #09090b; border: 1px solid var(--border-color); color: #fff; padding: 10px 12px; border-radius: 6px; font-family: inherit; width: 100%; box-sizing: border-box; }
        .form-input:focus { outline: none; border-color: var(--primary-color); }
        .form-row { display: flex; flex-wrap: wrap; gap: 15px; }
        .form-row .form-group { flex: 1; min-width: 140px; }
        .file-upload-box { border: 2px dashed var(--border-color); padding: 15px; border-radius: 8px; text-align: center; cursor: pointer; background: rgba(255,255,255,0.01); transition: all 0.2s; box-sizing: border-box; }
        .file-upload-box:hover { border-color: var(--primary-color); background: rgba(83, 252, 24, 0.05); }
        .file-upload-box i { font-size: 24px; color: var(--text-muted); margin-bottom: 8px; }
        .file-preview { display: flex; align-items: center; gap: 10px; margin-top: 10px; background: #18181b; padding: 10px; border-radius: 6px; border: 1px solid var(--border-color); box-sizing: border-box; }
        .file-preview img { max-width: 40px; max-height: 40px; border-radius: 4px; }
        .file-preview .file-name { font-size: 12px; color: #e4e4e7; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn { background: var(--primary-color); color: #000; border: none; padding: 12px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; box-sizing: border-box; }
        .btn:hover { opacity: 0.9; }
        .btn-obs { background: #1e1e20; color: #fff; border: 1px solid var(--border-color); text-decoration: none; padding: 10px 15px; border-radius: 6px; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; margin-left: auto; }
        .btn-obs:hover { background: #27272a; }
        .btn-test { background: #3b82f6; color: #fff; border: none; padding: 12px 15px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; box-sizing: border-box; }
        .btn-test:hover { opacity: 0.9; background: #2563eb; }
        .switch-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; }
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(20px); }
        .toast-notification { position: fixed; bottom: 30px; right: 30px; padding: 15px 25px; border-radius: 8px; color: #fff; font-weight: 500; display: flex; align-items: center; gap: 12px; transform: translateX(120%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 9999; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .toast-notification.show { transform: translateX(0); }
        .toast-success { background: #166534; border: 1px solid #22c55e; }
        .toast-error { background: #991b1b; border: 1px solid #ef4444; }
        .variables-hint { font-size: 12px; color: #a1a1aa; margin-top: 4px; }
        .variables-hint code { color: #60a5fa; background: rgba(96, 165, 250, 0.1); padding: 2px 5px; border-radius: 4px; }
        .volume-control { display: flex; align-items: center; gap: 10px; }
        .volume-control input[type=range] { flex: 1; accent-color: var(--primary-color); }
        .volume-value { font-size: 12px; color: #a1a1aa; min-width: 35px; }
        .live-preview-box { background: #000; border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; text-align: center; overflow: hidden; display: flex; align-items: center; justify-content: center; min-height: 100px; box-sizing: border-box; }
        .preview-text { font-size: 24px; font-weight: 800; color: #fff; transition: all 0.3s; }
        .effect-shadow { text-shadow: 2px 2px 0 #000, -2px -2px 0 #000, 2px -2px 0 #000, -2px 2px 0 #000, 0 4px 15px rgba(0,0,0,0.8); }
        .effect-neon { text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 20px var(--highlight-color), 0 0 40px var(--highlight-color); }
        .effect-none { text-shadow: none; }
        .grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; align-items: start; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
                <h1 class="page-title">OBS Alarm Ayarları</h1>
                <a href="alscreen.php" target="_blank" class="btn-obs">
                    <i class="fa-solid fa-desktop"></i> OBS Ekranını Aç
                </a>
            </div>

            <div class="grid-container">
                <?php foreach ($alerts as $type => $alert): ?>
                <div class="alert-card">
                    <div class="alert-card-header">
                        <div class="alert-title">
                            <i class="<?= $alert['info']['icon'] ?>"></i> <?= $alert['info']['title'] ?>
                        </div>
                        <label class="switch-wrapper">
                            <div class="switch">
                                <input type="checkbox" id="<?= $type ?>_active" <?= $alert['is_active'] ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </div>
                            Aktif
                        </label>
                    </div>

                    <form id="<?= $type ?>Form" onsubmit="saveAlert(event, '<?= $type ?>')">
                        <input type="hidden" name="alert_type" value="<?= $type ?>">

                        <div class="form-group">
                            <label>Alarm Mesajı</label>
                            <textarea class="form-input" id="<?= $type ?>_message_template" name="message_template" rows="2" required oninput="updatePreview('<?= $type ?>')"><?= htmlspecialchars($alert['message_template']) ?></textarea>
                            <div class="variables-hint">Değişkenler: <code><?= $alert['info']['vars'] ?></code></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Yazı Tipi</label>
                                <select class="form-input" id="<?= $type ?>_font_family" name="font_family" onchange="updatePreview('<?= $type ?>')">
                                    <option value="Inter" <?= $alert['font_family']=='Inter'?'selected':'' ?>>Inter</option>
                                    <option value="Arial" <?= $alert['font_family']=='Arial'?'selected':'' ?>>Arial</option>
                                    <option value="Impact" <?= $alert['font_family']=='Impact'?'selected':'' ?>>Impact</option>
                                    <option value="Roboto" <?= $alert['font_family']=='Roboto'?'selected':'' ?>>Roboto</option>
                                    <option value="Comic Sans MS" <?= $alert['font_family']=='Comic Sans MS'?'selected':'' ?>>Comic Sans MS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Yazı Stili</label>
                                <select class="form-input" id="<?= $type ?>_text_style" name="text_style" onchange="updatePreview('<?= $type ?>')">
                                    <option value="normal" <?= $alert['text_style']=='normal'?'selected':'' ?>>Normal</option>
                                    <option value="italic" <?= $alert['text_style']=='italic'?'selected':'' ?>>İtalik</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Yazı Rengi</label>
                                <input type="color" class="form-input" id="<?= $type ?>_text_color" name="text_color" value="<?= htmlspecialchars($alert['text_color']) ?>" style="padding:2px; height:40px; cursor:pointer;" oninput="updatePreview('<?= $type ?>')">
                            </div>
                            <div class="form-group">
                                <label>Vurgu Rengi</label>
                                <input type="color" class="form-input" id="<?= $type ?>_highlight_color" name="highlight_color" value="<?= htmlspecialchars($alert['highlight_color']) ?>" style="padding:2px; height:40px; cursor:pointer;" oninput="updatePreview('<?= $type ?>')">
                            </div>
                            <div class="form-group">
                                <label>Yazı Efekti</label>
                                <select class="form-input" id="<?= $type ?>_text_effect" name="text_effect" onchange="updatePreview('<?= $type ?>')">
                                    <option value="none" <?= $alert['text_effect']=='none'?'selected':'' ?>>Yok</option>
                                    <option value="shadow" <?= $alert['text_effect']=='shadow'?'selected':'' ?>>Kalın Gölge</option>
                                    <option value="neon" <?= $alert['text_effect']=='neon'?'selected':'' ?>>Neon Parlama</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Canlı Önizleme</label>
                            <div class="live-preview-box">
                                <div id="<?= $type ?>_live-preview-text" class="preview-text effect-shadow"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Görsel (PNG, GIF)</label>
                            <div class="file-upload-box" onclick="document.getElementById('<?= $type ?>_image_upload').click()">
                                <i class="fa-regular fa-image"></i>
                                <div style="font-size:13px;color:#a1a1aa">Görsel seçmek için tıklayın</div>
                                <input type="file" id="<?= $type ?>_image_upload" name="image_file" accept="image/png, image/gif, image/jpeg, image/webp" style="display:none" onchange="previewFile('<?= $type ?>', 'image')">
                            </div>
                            <div id="<?= $type ?>_image_preview" class="file-preview" style="<?= empty($alert['image_path']) ? 'display:none;' : '' ?>">
                                <?php if(!empty($alert['image_path'])): ?>
                                    <img src="<?= htmlspecialchars($alert['image_path']) ?>" alt="Preview">
                                    <div class="file-name"><?= basename($alert['image_path']) ?></div>
                                <?php else: ?>
                                    <img src="" alt="Preview">
                                    <div class="file-name">Yeni görsel</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ses (MP3, WAV)</label>
                            <div class="file-upload-box" onclick="document.getElementById('<?= $type ?>_audio_upload').click()">
                                <i class="fa-solid fa-music"></i>
                                <div style="font-size:13px;color:#a1a1aa">Ses dosyası seçmek için tıklayın</div>
                                <input type="file" id="<?= $type ?>_audio_upload" name="audio_file" accept="audio/mpeg, audio/wav, audio/ogg" style="display:none" onchange="previewFile('<?= $type ?>', 'audio')">
                            </div>
                            <div id="<?= $type ?>_audio_preview" class="file-preview" style="<?= empty($alert['audio_path']) ? 'display:none;' : '' ?>">
                                <i class="fa-solid fa-volume-high" style="color:#a1a1aa; padding:0 10px;"></i>
                                <div class="file-name"><?= !empty($alert['audio_path']) ? basename($alert['audio_path']) : 'Yeni ses' ?></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Süre (Saniye)</label>
                                <input type="number" class="form-input" name="duration" value="<?= (int)$alert['duration_seconds'] ?>" min="1" max="60" required>
                            </div>
                            <div class="form-group">
                                <label>Ses Seviyesi</label>
                                <div class="volume-control">
                                    <input type="range" name="volume" min="0" max="100" value="<?= (int)$alert['audio_volume'] ?>" oninput="document.getElementById('<?= $type ?>_vol_val').innerText = this.value + '%'">
                                    <span class="volume-value" id="<?= $type ?>_vol_val"><?= (int)$alert['audio_volume'] ?>%</span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
                            <button type="submit" class="btn" id="<?= $type ?>_saveBtn">
                                <i class="fa-solid fa-floppy-disk"></i> Ayarları Kaydet
                            </button>
                            
                            <button type="button" class="btn-test" onclick="triggerTestAlert('<?= $type ?>')">
                                <i class="fa-solid fa-play"></i> Test Alarmı Çal
                            </button>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

        </div>

    <div id="toast" class="toast-notification"></div>

    <script>
        const alertTypes = <?php echo json_encode(array_keys($alertTypes)); ?>;

        function updatePreview(type) {
            const template = document.getElementById(type + '_message_template').value;
            const font = document.getElementById(type + '_font_family').value;
            const style = document.getElementById(type + '_text_style').value;
            const color = document.getElementById(type + '_text_color').value;
            const highlight = document.getElementById(type + '_highlight_color').value;
            const effect = document.getElementById(type + '_text_effect').value;
            
            const previewEl = document.getElementById(type + '_live-preview-text');
            
            let previewHtml = template.replace('{username}', `<span style="color:${highlight}">${'Deniz Özceylan'}</span>`);
            previewHtml = previewHtml.replace('{amount}', `<span style="color:${highlight}">100</span>`);
            previewHtml = previewHtml.replace('{months}', `<span style="color:${highlight}">3</span>`);
            previewHtml = previewHtml.replace('{viewers}', `<span style="color:${highlight}">500</span>`);
            previewHtml = previewHtml.replace('{count}', `<span style="color:${highlight}">5</span>`);
            
            previewEl.innerHTML = previewHtml;
            previewEl.style.fontFamily = font + ', sans-serif';
            previewEl.style.fontStyle = style;
            previewEl.style.color = color;
            
            previewEl.style.setProperty('--highlight-color', highlight);
            previewEl.className = 'preview-text effect-' + effect;
        }
        
        window.addEventListener('DOMContentLoaded', () => {
            alertTypes.forEach(type => updatePreview(type));
        });

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.className = 'toast-notification ' + (isError ? 'toast-error' : 'toast-success');
            toast.innerHTML = `<i class="fa-solid ${isError ? 'fa-circle-xmark' : 'fa-circle-check'}"></i> ${message}`;
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }

        function previewFile(alertType, type) {
            const input = document.getElementById(alertType + '_' + type + '_upload');
            const preview = document.getElementById(alertType + '_' + type + '_preview');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                preview.style.display = 'flex';
                preview.querySelector('.file-name').innerText = file.name;
                
                if (type === 'image') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.querySelector('img').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        }

        async function saveAlert(e, type) {
            e.preventDefault();
            const btn = document.getElementById(type + '_saveBtn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Kaydediliyor...';
            btn.disabled = true;

            const form = document.getElementById(type + 'Form');
            const formData = new FormData(form);
            formData.append('is_active', document.getElementById(type + '_active').checked ? 1 : 0);

            try {
                const res = await fetch('api/save_alert.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.status === 'success') {
                    showToast('Alarm ayarları başarıyla kaydedildi!');
                } else {
                    showToast(data.message || 'Bir hata oluştu!', true);
                }
            } catch (err) {
                showToast('Sunucu bağlantı hatası!', true);
            } finally {
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Ayarları Kaydet';
                btn.disabled = false;
            }
        }

        async function triggerTestAlert(type) {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Test Ediliyor...';
            btn.disabled = true;

            // Önce ayarları arka planda sessizce kaydet ki API "aktif değil" hatası vermesin
            try {
                const form = document.getElementById(type + 'Form');
                const formData = new FormData(form);
                formData.append('is_active', document.getElementById(type + '_active').checked ? 1 : 0);
                await fetch('api/save_alert.php', { method: 'POST', body: formData });
            } catch(e) {}

            try {
                const res = await fetch('api/trigger_test_alert.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `alert_type=${encodeURIComponent(type)}`
                });
                const data = await res.json();
                if(data.status === 'success') {
                    showToast('Test alarmı tetiklendi!');
                } else {
                    showToast('Hata: ' + data.message, true);
                }
            } catch(e) {
                showToast('Bağlantı hatası!', true);
            } finally {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        }
    </script>
</body>
=======
<?php
session_start();
require_once 'config.php';

// Alert tipleri ve varsayılanları
$alertTypes = [
    'follow' => [
        'title' => 'Yeni Takipçi',
        'icon' => 'fa-solid fa-heart',
        'vars' => '{username}',
        'default_msg' => '{username} aileye katıldı!'
    ],
    'refollow' => [
        'title' => 'Tekrar Takip Edenler',
        'icon' => 'fa-solid fa-user-plus',
        'vars' => '{username}',
        'default_msg' => '{username} tekrar aramıza katıldı!'
    ],
    'sub' => [
        'title' => 'Yeni Abone',
        'icon' => 'fa-solid fa-star',
        'vars' => '{username}',
        'default_msg' => '{username} abone oldu!'
    ],
    'resub' => [
        'title' => 'Abonelik Yenileme',
        'icon' => 'fa-solid fa-star-half-stroke',
        'vars' => '{username}, {months}',
        'default_msg' => '{username} {months}. ayında aboneliğini yeniledi!'
    ],
    'gift' => [
        'title' => 'Hediye Abonelik',
        'icon' => 'fa-solid fa-gift',
        'vars' => '{username}, {count}',
        'default_msg' => '{username} {count} kişiye abonelik hediye etti!'
    ],
    'kicks' => [
        'title' => 'Kicks Gönderimi',
        'icon' => 'fa-solid fa-coins',
        'vars' => '{username}, {amount}',
        'default_msg' => '{username} {amount} Kicks gönderdi!'
    ],
    'host' => [
        'title' => 'Host',
        'icon' => 'fa-solid fa-satellite-dish',
        'vars' => '{username}, {viewers}',
        'default_msg' => '{username} {viewers} izleyiciyle host attı!'
    ]
];

// Veritabanından tüm ayarları çek
$stmt = $db->query("SELECT * FROM alert_settings");
$dbAlerts = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dbAlerts[$row['alert_type']] = $row;
}

// Ayarları birleştir
$alerts = [];
foreach ($alertTypes as $type => $info) {
    if (isset($dbAlerts[$type])) {
        $alerts[$type] = $dbAlerts[$type];
    } else {
        $alerts[$type] = [
            'is_active' => 1,
            'message_template' => $info['default_msg'],
            'image_path' => '',
            'audio_path' => '',
            'audio_volume' => 50,
            'duration_seconds' => 5,
            'font_family' => 'Inter',
            'text_color' => '#ffffff',
            'highlight_color' => '#53FC18',
            'text_style' => 'normal',
            'text_effect' => 'shadow'
        ];
    }
    $alerts[$type]['info'] = $info;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Alarm Ayarları - SebastianBot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .main-content { flex: 1; padding: 30px; overflow-y: auto; height: 100vh; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .page-title { font-size: 24px; font-weight: 700; color: #fff; }
        .alert-card { background: var(--bg-panel); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; display: flex; flex-direction: column; gap: 20px; }
        .alert-card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 5px; }
        .alert-title { font-size: 18px; font-weight: 600; color: var(--primary-color); display: flex; align-items: center; gap: 10px; }
        .alert-card form { display: flex; flex-direction: column; gap: 24px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-group label { font-size: 13px; color: #a1a1aa; font-weight: 500; }
        .form-input { background: #09090b; border: 1px solid var(--border-color); color: #fff; padding: 10px 12px; border-radius: 6px; font-family: inherit; width: 100%; box-sizing: border-box; }
        .form-input:focus { outline: none; border-color: var(--primary-color); }
        .form-row { display: flex; flex-wrap: wrap; gap: 15px; }
        .form-row .form-group { flex: 1; min-width: 140px; }
        .file-upload-box { border: 2px dashed var(--border-color); padding: 15px; border-radius: 8px; text-align: center; cursor: pointer; background: rgba(255,255,255,0.01); transition: all 0.2s; box-sizing: border-box; }
        .file-upload-box:hover { border-color: var(--primary-color); background: rgba(83, 252, 24, 0.05); }
        .file-upload-box i { font-size: 24px; color: var(--text-muted); margin-bottom: 8px; }
        .file-preview { display: flex; align-items: center; gap: 10px; margin-top: 10px; background: #18181b; padding: 10px; border-radius: 6px; border: 1px solid var(--border-color); box-sizing: border-box; }
        .file-preview img { max-width: 40px; max-height: 40px; border-radius: 4px; }
        .file-preview .file-name { font-size: 12px; color: #e4e4e7; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .btn { background: var(--primary-color); color: #000; border: none; padding: 12px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; box-sizing: border-box; }
        .btn:hover { opacity: 0.9; }
        .btn-obs { background: #1e1e20; color: #fff; border: 1px solid var(--border-color); text-decoration: none; padding: 10px 15px; border-radius: 6px; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; margin-left: auto; }
        .btn-obs:hover { background: #27272a; }
        .btn-test { background: #3b82f6; color: #fff; border: none; padding: 12px 15px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; box-sizing: border-box; }
        .btn-test:hover { opacity: 0.9; background: #2563eb; }
        .switch-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; }
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(20px); }
        .toast-notification { position: fixed; bottom: 30px; right: 30px; padding: 15px 25px; border-radius: 8px; color: #fff; font-weight: 500; display: flex; align-items: center; gap: 12px; transform: translateX(120%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); z-index: 9999; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .toast-notification.show { transform: translateX(0); }
        .toast-success { background: #166534; border: 1px solid #22c55e; }
        .toast-error { background: #991b1b; border: 1px solid #ef4444; }
        .variables-hint { font-size: 12px; color: #a1a1aa; margin-top: 4px; }
        .variables-hint code { color: #60a5fa; background: rgba(96, 165, 250, 0.1); padding: 2px 5px; border-radius: 4px; }
        .volume-control { display: flex; align-items: center; gap: 10px; }
        .volume-control input[type=range] { flex: 1; accent-color: var(--primary-color); }
        .volume-value { font-size: 12px; color: #a1a1aa; min-width: 35px; }
        .live-preview-box { background: #000; border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; text-align: center; overflow: hidden; display: flex; align-items: center; justify-content: center; min-height: 100px; box-sizing: border-box; }
        .preview-text { font-size: 24px; font-weight: 800; color: #fff; transition: all 0.3s; }
        .effect-shadow { text-shadow: 2px 2px 0 #000, -2px -2px 0 #000, 2px -2px 0 #000, -2px 2px 0 #000, 0 4px 15px rgba(0,0,0,0.8); }
        .effect-neon { text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 20px var(--highlight-color), 0 0 40px var(--highlight-color); }
        .effect-none { text-shadow: none; }
        .grid-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; align-items: start; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
                <h1 class="page-title">OBS Alarm Ayarları</h1>
                <a href="alscreen.php" target="_blank" class="btn-obs">
                    <i class="fa-solid fa-desktop"></i> OBS Ekranını Aç
                </a>
            </div>

            <div class="grid-container">
                <?php foreach ($alerts as $type => $alert): ?>
                <div class="alert-card">
                    <div class="alert-card-header">
                        <div class="alert-title">
                            <i class="<?= $alert['info']['icon'] ?>"></i> <?= $alert['info']['title'] ?>
                        </div>
                        <label class="switch-wrapper">
                            <div class="switch">
                                <input type="checkbox" id="<?= $type ?>_active" <?= $alert['is_active'] ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </div>
                            Aktif
                        </label>
                    </div>

                    <form id="<?= $type ?>Form" onsubmit="saveAlert(event, '<?= $type ?>')">
                        <input type="hidden" name="alert_type" value="<?= $type ?>">

                        <div class="form-group">
                            <label>Alarm Mesajı</label>
                            <textarea class="form-input" id="<?= $type ?>_message_template" name="message_template" rows="2" required oninput="updatePreview('<?= $type ?>')"><?= htmlspecialchars($alert['message_template']) ?></textarea>
                            <div class="variables-hint">Değişkenler: <code><?= $alert['info']['vars'] ?></code></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Yazı Tipi</label>
                                <select class="form-input" id="<?= $type ?>_font_family" name="font_family" onchange="updatePreview('<?= $type ?>')">
                                    <option value="Inter" <?= $alert['font_family']=='Inter'?'selected':'' ?>>Inter</option>
                                    <option value="Arial" <?= $alert['font_family']=='Arial'?'selected':'' ?>>Arial</option>
                                    <option value="Impact" <?= $alert['font_family']=='Impact'?'selected':'' ?>>Impact</option>
                                    <option value="Roboto" <?= $alert['font_family']=='Roboto'?'selected':'' ?>>Roboto</option>
                                    <option value="Comic Sans MS" <?= $alert['font_family']=='Comic Sans MS'?'selected':'' ?>>Comic Sans MS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Yazı Stili</label>
                                <select class="form-input" id="<?= $type ?>_text_style" name="text_style" onchange="updatePreview('<?= $type ?>')">
                                    <option value="normal" <?= $alert['text_style']=='normal'?'selected':'' ?>>Normal</option>
                                    <option value="italic" <?= $alert['text_style']=='italic'?'selected':'' ?>>İtalik</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Yazı Rengi</label>
                                <input type="color" class="form-input" id="<?= $type ?>_text_color" name="text_color" value="<?= htmlspecialchars($alert['text_color']) ?>" style="padding:2px; height:40px; cursor:pointer;" oninput="updatePreview('<?= $type ?>')">
                            </div>
                            <div class="form-group">
                                <label>Vurgu Rengi</label>
                                <input type="color" class="form-input" id="<?= $type ?>_highlight_color" name="highlight_color" value="<?= htmlspecialchars($alert['highlight_color']) ?>" style="padding:2px; height:40px; cursor:pointer;" oninput="updatePreview('<?= $type ?>')">
                            </div>
                            <div class="form-group">
                                <label>Yazı Efekti</label>
                                <select class="form-input" id="<?= $type ?>_text_effect" name="text_effect" onchange="updatePreview('<?= $type ?>')">
                                    <option value="none" <?= $alert['text_effect']=='none'?'selected':'' ?>>Yok</option>
                                    <option value="shadow" <?= $alert['text_effect']=='shadow'?'selected':'' ?>>Kalın Gölge</option>
                                    <option value="neon" <?= $alert['text_effect']=='neon'?'selected':'' ?>>Neon Parlama</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Canlı Önizleme</label>
                            <div class="live-preview-box">
                                <div id="<?= $type ?>_live-preview-text" class="preview-text effect-shadow"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Görsel (PNG, GIF)</label>
                            <div class="file-upload-box" onclick="document.getElementById('<?= $type ?>_image_upload').click()">
                                <i class="fa-regular fa-image"></i>
                                <div style="font-size:13px;color:#a1a1aa">Görsel seçmek için tıklayın</div>
                                <input type="file" id="<?= $type ?>_image_upload" name="image_file" accept="image/png, image/gif, image/jpeg, image/webp" style="display:none" onchange="previewFile('<?= $type ?>', 'image')">
                            </div>
                            <div id="<?= $type ?>_image_preview" class="file-preview" style="<?= empty($alert['image_path']) ? 'display:none;' : '' ?>">
                                <?php if(!empty($alert['image_path'])): ?>
                                    <img src="<?= htmlspecialchars($alert['image_path']) ?>" alt="Preview">
                                    <div class="file-name"><?= basename($alert['image_path']) ?></div>
                                <?php else: ?>
                                    <img src="" alt="Preview">
                                    <div class="file-name">Yeni görsel</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ses (MP3, WAV)</label>
                            <div class="file-upload-box" onclick="document.getElementById('<?= $type ?>_audio_upload').click()">
                                <i class="fa-solid fa-music"></i>
                                <div style="font-size:13px;color:#a1a1aa">Ses dosyası seçmek için tıklayın</div>
                                <input type="file" id="<?= $type ?>_audio_upload" name="audio_file" accept="audio/mpeg, audio/wav, audio/ogg" style="display:none" onchange="previewFile('<?= $type ?>', 'audio')">
                            </div>
                            <div id="<?= $type ?>_audio_preview" class="file-preview" style="<?= empty($alert['audio_path']) ? 'display:none;' : '' ?>">
                                <i class="fa-solid fa-volume-high" style="color:#a1a1aa; padding:0 10px;"></i>
                                <div class="file-name"><?= !empty($alert['audio_path']) ? basename($alert['audio_path']) : 'Yeni ses' ?></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Süre (Saniye)</label>
                                <input type="number" class="form-input" name="duration" value="<?= (int)$alert['duration_seconds'] ?>" min="1" max="60" required>
                            </div>
                            <div class="form-group">
                                <label>Ses Seviyesi</label>
                                <div class="volume-control">
                                    <input type="range" name="volume" min="0" max="100" value="<?= (int)$alert['audio_volume'] ?>" oninput="document.getElementById('<?= $type ?>_vol_val').innerText = this.value + '%'">
                                    <span class="volume-value" id="<?= $type ?>_vol_val"><?= (int)$alert['audio_volume'] ?>%</span>
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 10px;">
                            <button type="submit" class="btn" id="<?= $type ?>_saveBtn">
                                <i class="fa-solid fa-floppy-disk"></i> Ayarları Kaydet
                            </button>
                            
                            <button type="button" class="btn-test" onclick="triggerTestAlert('<?= $type ?>')">
                                <i class="fa-solid fa-play"></i> Test Alarmı Çal
                            </button>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>

        </div>

    <div id="toast" class="toast-notification"></div>

    <script>
        const alertTypes = <?php echo json_encode(array_keys($alertTypes)); ?>;

        function updatePreview(type) {
            const template = document.getElementById(type + '_message_template').value;
            const font = document.getElementById(type + '_font_family').value;
            const style = document.getElementById(type + '_text_style').value;
            const color = document.getElementById(type + '_text_color').value;
            const highlight = document.getElementById(type + '_highlight_color').value;
            const effect = document.getElementById(type + '_text_effect').value;
            
            const previewEl = document.getElementById(type + '_live-preview-text');
            
            let previewHtml = template.replace('{username}', `<span style="color:${highlight}">${'Deniz Özceylan'}</span>`);
            previewHtml = previewHtml.replace('{amount}', `<span style="color:${highlight}">100</span>`);
            previewHtml = previewHtml.replace('{months}', `<span style="color:${highlight}">3</span>`);
            previewHtml = previewHtml.replace('{viewers}', `<span style="color:${highlight}">500</span>`);
            previewHtml = previewHtml.replace('{count}', `<span style="color:${highlight}">5</span>`);
            
            previewEl.innerHTML = previewHtml;
            previewEl.style.fontFamily = font + ', sans-serif';
            previewEl.style.fontStyle = style;
            previewEl.style.color = color;
            
            previewEl.style.setProperty('--highlight-color', highlight);
            previewEl.className = 'preview-text effect-' + effect;
        }
        
        window.addEventListener('DOMContentLoaded', () => {
            alertTypes.forEach(type => updatePreview(type));
        });

        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.className = 'toast-notification ' + (isError ? 'toast-error' : 'toast-success');
            toast.innerHTML = `<i class="fa-solid ${isError ? 'fa-circle-xmark' : 'fa-circle-check'}"></i> ${message}`;
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }

        function previewFile(alertType, type) {
            const input = document.getElementById(alertType + '_' + type + '_upload');
            const preview = document.getElementById(alertType + '_' + type + '_preview');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                preview.style.display = 'flex';
                preview.querySelector('.file-name').innerText = file.name;
                
                if (type === 'image') {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.querySelector('img').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            }
        }

        async function saveAlert(e, type) {
            e.preventDefault();
            const btn = document.getElementById(type + '_saveBtn');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Kaydediliyor...';
            btn.disabled = true;

            const form = document.getElementById(type + 'Form');
            const formData = new FormData(form);
            formData.append('is_active', document.getElementById(type + '_active').checked ? 1 : 0);

            try {
                const res = await fetch('api/save_alert.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                if (data.status === 'success') {
                    showToast('Alarm ayarları başarıyla kaydedildi!');
                } else {
                    showToast(data.message || 'Bir hata oluştu!', true);
                }
            } catch (err) {
                showToast('Sunucu bağlantı hatası!', true);
            } finally {
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Ayarları Kaydet';
                btn.disabled = false;
            }
        }

        async function triggerTestAlert(type) {
            const btn = event.currentTarget;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Test Ediliyor...';
            btn.disabled = true;

            // Önce ayarları arka planda sessizce kaydet ki API "aktif değil" hatası vermesin
            try {
                const form = document.getElementById(type + 'Form');
                const formData = new FormData(form);
                formData.append('is_active', document.getElementById(type + '_active').checked ? 1 : 0);
                await fetch('api/save_alert.php', { method: 'POST', body: formData });
            } catch(e) {}

            try {
                const res = await fetch('api/trigger_test_alert.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `alert_type=${encodeURIComponent(type)}`
                });
                const data = await res.json();
                if(data.status === 'success') {
                    showToast('Test alarmı tetiklendi!');
                } else {
                    showToast('Hata: ' + data.message, true);
                }
            } catch(e) {
                showToast('Bağlantı hatası!', true);
            } finally {
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            }
        }
    </script>
</body>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
</html>