<<<<<<< HEAD
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subathon - BotDash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .sb-layout { flex: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .ly-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .ly-title { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .ly-title i { color: var(--primary-color); }

        #live-timer { font-size: 64px; font-weight: 800; color: var(--primary-color); text-align: center; text-shadow: 0 0 20px rgba(83,252,24,0.3); font-variant-numeric: tabular-nums; margin-bottom: 20px;}

        .switch-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight:600; color:#fff;}
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(20px); }

        .ly-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
        .ly-group { display: flex; flex-direction: column; gap: 6px; }
        .ly-group label { font-size: 12px; color: var(--text-secondary); font-weight: 600; }
        .ly-input { background: #0f1318; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 10px 14px; font-size: 13px; outline: none; font-family: inherit; width:100%; box-sizing:border-box;}
        .ly-input:focus { border-color: var(--primary-color); }

        .ly-btn { border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; justify-content:center;}
        .ly-btn-primary { background: var(--primary-color); color: #000; }
        .ly-btn-secondary { background: #27273a; color: #fff; border: 1px solid #3f3f46;}
        .ly-btn-secondary:hover { background: #3f3f46; }
        .ly-btn-info { background: #3b82f6; color: #fff; }
        .ly-btn-info:hover { background: #2563eb; }

        .modal-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:11000; justify-content:center; align-items:center; backdrop-filter:blur(4px); }
        .modal-box { background:#1e1e2d; border-radius:12px; padding:25px; width:380px; text-align:center; box-shadow:0 10px 24px rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.1); }
        .modal-box h3 { color:white; margin:0 0 12px; font-size:18px; }
        .modal-box p { color:#a1a1aa; font-size:14px; margin-bottom:20px; }
        .modal-actions { display:flex; gap:10px; }
        .modal-actions button { flex:1; padding:10px; border:none; border-radius:6px; cursor:pointer; font-weight:bold; font-family:inherit; transition: opacity 0.2s; }
        .modal-actions button:hover { opacity: 0.9; }

        #live-timer { transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .style-neon { color: #fff; text-shadow: 0 0 10px #53fc18, 0 0 20px #53fc18, 0 0 40px #53fc18; }
        .style-cyberpunk { color: #fff; text-shadow: 2px 2px 0px #f0f, -2px -2px 0px #0ff, 0 0 20px #f0f; font-style: italic; letter-spacing: -2px; }
        .style-fire { color: #ffeb3b; text-shadow: 0 -2px 4px #ff9800, 0 -6px 10px #f44336, 0 -10px 20px #d32f2f; }
        .style-minimal { color: #ffffff; text-shadow: 0 4px 15px rgba(0,0,0,0.5); font-weight: 600; letter-spacing: 2px; }
        @keyframes rgbGlow { 0% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} 33% {text-shadow: 0 0 20px #00ff00; color:#00ff00;} 66% {text-shadow: 0 0 20px #0000ff; color:#0000ff;} 100% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} }
        .style-rgb { animation: rgbGlow 3s linear infinite; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Subathon Yönetimi</div>
            <div class="user-profile"><div class="avatar"><i class="fas fa-robot" style="color: var(--primary-color);"></i></div><span>SebastianBot</span></div>
        </header>

        <div class="sb-layout">
            
        
            <div class="ly-card" style="text-align:center;">
                <h3 class="ly-title" style="justify-content:center;"><i class="fas fa-stopwatch"></i> Sayaç Durumu</h3>
                <div id="live-timer" class="style-neon">00:00:00</div>
                
                <div style="display:flex; justify-content:center; align-items:center; margin-bottom: 30px; gap: 15px;">
                    <label class="switch-wrapper">
                        <div class="switch">
                            <input type="checkbox" id="sub_active" onchange="toggleActive()">
                            <span class="slider"></span>
                        </div>
                        <span id="status-text">Pasif</span>
                    </label>
                    <a href="subhascreen.php" target="_blank" class="ly-btn ly-btn-secondary"><i class="fas fa-desktop"></i> OBS Ekranını Aç</a>
                </div>

                <div style="display:flex; justify-content:center; gap:30px; flex-wrap:wrap; text-align:left;">
                    
                
                    <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05); flex:1; min-width:250px;">
                        <h4 style="color:#a1a1aa; font-size: 13px; margin:0 0 12px 0;">Süreyi Manuel Uzat</h4>
                        <div style="display:flex; gap:10px; margin-bottom:10px;">
                            <button class="ly-btn ly-btn-info" style="flex:1" onclick="addTime(60)"><i class="fas fa-plus"></i> 1 Dk</button>
                            <button class="ly-btn ly-btn-info" style="flex:1" onclick="addTime(600)"><i class="fas fa-plus"></i> 10 Dk</button>
                            <button class="ly-btn ly-btn-info" style="flex:1" onclick="addTime(3600)"><i class="fas fa-plus"></i> 1 Saat</button>
                        </div>
                        <div style="display:flex; gap:5px;">
                            <input type="number" id="custom_minutes" class="ly-input" placeholder="Dakika girin...">
                            <button class="ly-btn ly-btn-primary" onclick="addCustomTime()"><i class="fas fa-plus"></i> Ekle</button>
                        </div>
                    </div>

                    
                    
                    <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05); flex:1; min-width:250px;">
                        <h4 style="color:#a1a1aa; font-size: 13px; margin:0 0 12px 0;">Yeni Sayaca Başla (Reset)</h4>
                        <div style="display:flex; gap:10px; flex-direction:column;">
                            <div style="display:flex; gap:10px;">
                                <button class="ly-btn ly-btn-secondary" style="flex:1" onclick="startNew(1)"><i class="fas fa-play"></i> 1 Saat</button>
                                <button class="ly-btn ly-btn-secondary" style="flex:1" onclick="startNew(12)"><i class="fas fa-play"></i> 12 Saat</button>
                                <button class="ly-btn ly-btn-secondary" style="flex:1" onclick="startNew(24)"><i class="fas fa-play"></i> 24 Saat</button>
                            </div>
                            <div style="font-size:11px; color:#ef4444; margin-top:5px;">* Uyarı: Devam eden sayaç varsa sıfırlanıp üzerine yazılır.</div>
                        </div>
                    </div>
                </div>
            </div>

            
            
            <div class="ly-card">
                <h3 class="ly-title" style="margin-bottom: 24px;"><i class="fas fa-cogs"></i> Süre ve Tasarım Ayarları</h3>
                
                <div style="display:flex; flex-direction:column; gap:20px;">
                    
                    
                
                    <div style="background:#151a21; padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05);">
                        <h4 style="color:var(--primary-color); margin:0 0 15px 0; font-size:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-star"></i> Abonelik Etkinlikleri
                        </h4>
                        <div class="ly-grid">
                            <div class="ly-group"><label>1 Yeni Abone (Sn)</label><input type="number" id="sec_sub" class="ly-input" placeholder="300"></div>
                            <div class="ly-group"><label>1 Yeniden Abone (Sn)</label><input type="number" id="sec_resub" class="ly-input" placeholder="300"></div>
                            <div class="ly-group"><label>1 Hediye Abone (Sn)</label><input type="number" id="sec_gift" class="ly-input" placeholder="300"></div>
                        </div>
                    </div>

                    
                    <div style="background:#151a21; padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05);">
                        <h4 style="color:var(--primary-color); margin:0 0 15px 0; font-size:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-coins"></i> Kicks Bağışları
                        </h4>
                        <div class="ly-grid">
                            <div class="ly-group"><label>Kicks Eşiği (Örn: 100)</label><input type="number" id="kicks_req" class="ly-input" placeholder="100"></div>
                            <div class="ly-group"><label>Eşiğe Verilecek Saniye</label><input type="number" id="sec_kicks" class="ly-input" placeholder="60"></div>
                        </div>
                    </div>

                    
                    <div style="background:#151a21; padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05);">
                        <h4 style="color:var(--primary-color); margin:0 0 15px 0; font-size:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-paint-brush"></i> Görünüm ve Animasyon
                        </h4>
                        <div class="ly-grid">
                            <div class="ly-group">
                                <label>OBS Sayaç Stili</label>
                                <select id="timer_style" class="ly-input">
                                    <option value="neon">Klasik Neon Yeşil</option>
                                    <option value="cyberpunk">Cyberpunk (Pembe/Mavi)</option>
                                    <option value="fire">Ateş (Kırmızı/Turuncu)</option>
                                    <option value="minimal">Minimalist (Sade Beyaz)</option>
                                    <option value="rgb">RGB Oyuncu</option>
                                </select>
                            </div>
                            <div class="ly-group" style="margin-top: 10px; grid-column: 1 / -1;">
                                <button type="button" class="ly-btn ly-btn-info" onclick="triggerShowcase(this)" style="justify-content:center; padding: 12px;"><i class="fas fa-play"></i> OBS'te Bilgi Animasyonunu Oynat</button>
                                <div style="font-size:11px; color:#a1a1aa; margin-top:6px;">* Sayaç üzerinde abonelik ve kicks bağışlarının kaç dakika eklediğini tek seferlik görsel animasyonlarla sırayla gösterir.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 20px; text-align:right;">
                    <button class="ly-btn ly-btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i> Ayarları Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal-overlay" id="cfModal">
        <div class="modal-box">
            <h3>Onay</h3>
            <p id="cfDesc">Emin misiniz?</p>
            <div class="modal-actions">
                <button onclick="closeCF()" style="background:#3f3f46; color:white;">İptal</button>
                <button onclick="execCF()" style="background:#ef4444; color:white;">Yeni Sayaç Başlat</button>
            </div>
        </div>
    </div>

    <script>
        let serverEndTs = 0;
        let serverNowTs = 0;
        let localOffset = 0;
        let pendingResetHours = 0;

        async function fetchData() {
            const res = await fetch('api/subathon.php?action=get');
            const d = await res.json();
            if(d.status === 'success') {
                const data = d.data;
                document.getElementById('sec_sub').value = data.sec_sub;
                document.getElementById('sec_resub').value = data.sec_resub;
                document.getElementById('sec_gift').value = data.sec_gift;
                document.getElementById('kicks_req').value = data.kicks_req;
                document.getElementById('sec_kicks').value = data.sec_kicks;
                document.getElementById('timer_style').value = data.timer_style || 'neon';
                
                const isActive = data.is_active == 1;
                document.getElementById('sub_active').checked = isActive;
                document.getElementById('live-timer').className = 'style-' + (data.timer_style || 'neon');
                document.getElementById('status-text').innerText = isActive ? 'Aktif' : 'Pasif';
                document.getElementById('status-text').style.color = isActive ? '#53fc18' : '#a1a1aa';

                serverEndTs = parseInt(data.end_ts) || 0;
                serverNowTs = parseInt(data.now_ts) || 0;
                localOffset = Math.floor(Date.now() / 1000) - serverNowTs;
            }
        }

        function updateTimer() {
            if(serverEndTs === 0) return;
            const currentRealTs = Math.floor(Date.now() / 1000) - localOffset;
            let diff = serverEndTs - currentRealTs;
            if(diff < 0) diff = 0;

            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;
            document.getElementById('live-timer').innerText = 
                `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }

        setInterval(updateTimer, 1000);
        setInterval(fetchData, 3000); // 3 sn'de bir senkronize et

        document.getElementById('timer_style').addEventListener('change', function(e) {
            document.getElementById('live-timer').className = 'style-' + e.target.value;
        });

        async function sendPost(action, bodyObj) {
            const fd = new FormData();
            fd.append('action', action);
            for(let k in bodyObj) fd.append(k, bodyObj[k]);
            await fetch('api/subathon.php', {method: 'POST', body: fd});
            fetchData();
        }

        function toggleActive() {
            const v = document.getElementById('sub_active').checked ? 1 : 0;
            sendPost('toggle_active', {is_active: v});
        }
        function saveSettings() {
            sendPost('save_settings', {
                sec_sub: document.getElementById('sec_sub').value,
                sec_resub: document.getElementById('sec_resub').value,
                sec_gift: document.getElementById('sec_gift').value,
                kicks_req: document.getElementById('kicks_req').value,
                sec_kicks: document.getElementById('sec_kicks').value,
                timer_style: document.getElementById('timer_style').value
            });
        }
        function addTime(secs) { sendPost('add_time', {seconds: secs}); }
        function addCustomTime() {
            const val = parseInt(document.getElementById('custom_minutes').value);
            if(val > 0) { sendPost('add_time', {seconds: val * 60}); document.getElementById('custom_minutes').value = ''; }
        }
        
        function startNew(hours) {
            pendingResetHours = hours;
            document.getElementById('cfDesc').innerText = hours + " saatlik yepyeni bir sayaca başlamak istediğinize emin misiniz?";
            document.getElementById('cfModal').style.display = 'flex';
        }
        
        function closeCF() {
            document.getElementById('cfModal').style.display = 'none';
            pendingResetHours = 0;
        }
        
        function execCF() {
            if(pendingResetHours > 0) { sendPost('set_time', {hours: pendingResetHours}); }
            closeCF();
        }

        function triggerShowcase(btn) {
            const orgHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Oynatılıyor...';
            btn.disabled = true;
            sendPost('trigger_showcase', {}).then(() => {
                setTimeout(() => {
                    btn.innerHTML = orgHtml;
                    btn.disabled = false;
                }, 15000); // Animasyonun bitmesi için 15sn bekle
            });
        }

        fetchData();
    </script>
</body>
=======
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subathon - BotDash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .sb-layout { flex: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .ly-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .ly-title { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .ly-title i { color: var(--primary-color); }

        #live-timer { font-size: 64px; font-weight: 800; color: var(--primary-color); text-align: center; text-shadow: 0 0 20px rgba(83,252,24,0.3); font-variant-numeric: tabular-nums; margin-bottom: 20px;}

        .switch-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight:600; color:#fff;}
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(20px); }

        .ly-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
        .ly-group { display: flex; flex-direction: column; gap: 6px; }
        .ly-group label { font-size: 12px; color: var(--text-secondary); font-weight: 600; }
        .ly-input { background: #0f1318; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 10px 14px; font-size: 13px; outline: none; font-family: inherit; width:100%; box-sizing:border-box;}
        .ly-input:focus { border-color: var(--primary-color); }

        .ly-btn { border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; justify-content:center;}
        .ly-btn-primary { background: var(--primary-color); color: #000; }
        .ly-btn-secondary { background: #27273a; color: #fff; border: 1px solid #3f3f46;}
        .ly-btn-secondary:hover { background: #3f3f46; }
        .ly-btn-info { background: #3b82f6; color: #fff; }
        .ly-btn-info:hover { background: #2563eb; }

        .modal-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:11000; justify-content:center; align-items:center; backdrop-filter:blur(4px); }
        .modal-box { background:#1e1e2d; border-radius:12px; padding:25px; width:380px; text-align:center; box-shadow:0 10px 24px rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.1); }
        .modal-box h3 { color:white; margin:0 0 12px; font-size:18px; }
        .modal-box p { color:#a1a1aa; font-size:14px; margin-bottom:20px; }
        .modal-actions { display:flex; gap:10px; }
        .modal-actions button { flex:1; padding:10px; border:none; border-radius:6px; cursor:pointer; font-weight:bold; font-family:inherit; transition: opacity 0.2s; }
        .modal-actions button:hover { opacity: 0.9; }

        #live-timer { transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .style-neon { color: #fff; text-shadow: 0 0 10px #53fc18, 0 0 20px #53fc18, 0 0 40px #53fc18; }
        .style-cyberpunk { color: #fff; text-shadow: 2px 2px 0px #f0f, -2px -2px 0px #0ff, 0 0 20px #f0f; font-style: italic; letter-spacing: -2px; }
        .style-fire { color: #ffeb3b; text-shadow: 0 -2px 4px #ff9800, 0 -6px 10px #f44336, 0 -10px 20px #d32f2f; }
        .style-minimal { color: #ffffff; text-shadow: 0 4px 15px rgba(0,0,0,0.5); font-weight: 600; letter-spacing: 2px; }
        @keyframes rgbGlow { 0% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} 33% {text-shadow: 0 0 20px #00ff00; color:#00ff00;} 66% {text-shadow: 0 0 20px #0000ff; color:#0000ff;} 100% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} }
        .style-rgb { animation: rgbGlow 3s linear infinite; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Subathon Yönetimi</div>
            <div class="user-profile"><div class="avatar"><i class="fas fa-robot" style="color: var(--primary-color);"></i></div><span>SebastianBot</span></div>
        </header>

        <div class="sb-layout">
            
        
            <div class="ly-card" style="text-align:center;">
                <h3 class="ly-title" style="justify-content:center;"><i class="fas fa-stopwatch"></i> Sayaç Durumu</h3>
                <div id="live-timer" class="style-neon">00:00:00</div>
                
                <div style="display:flex; justify-content:center; align-items:center; margin-bottom: 30px; gap: 15px;">
                    <label class="switch-wrapper">
                        <div class="switch">
                            <input type="checkbox" id="sub_active" onchange="toggleActive()">
                            <span class="slider"></span>
                        </div>
                        <span id="status-text">Pasif</span>
                    </label>
                    <a href="subhascreen.php" target="_blank" class="ly-btn ly-btn-secondary"><i class="fas fa-desktop"></i> OBS Ekranını Aç</a>
                </div>

                <div style="display:flex; justify-content:center; gap:30px; flex-wrap:wrap; text-align:left;">
                    
                
                    <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05); flex:1; min-width:250px;">
                        <h4 style="color:#a1a1aa; font-size: 13px; margin:0 0 12px 0;">Süreyi Manuel Uzat</h4>
                        <div style="display:flex; gap:10px; margin-bottom:10px;">
                            <button class="ly-btn ly-btn-info" style="flex:1" onclick="addTime(60)"><i class="fas fa-plus"></i> 1 Dk</button>
                            <button class="ly-btn ly-btn-info" style="flex:1" onclick="addTime(600)"><i class="fas fa-plus"></i> 10 Dk</button>
                            <button class="ly-btn ly-btn-info" style="flex:1" onclick="addTime(3600)"><i class="fas fa-plus"></i> 1 Saat</button>
                        </div>
                        <div style="display:flex; gap:5px;">
                            <input type="number" id="custom_minutes" class="ly-input" placeholder="Dakika girin...">
                            <button class="ly-btn ly-btn-primary" onclick="addCustomTime()"><i class="fas fa-plus"></i> Ekle</button>
                        </div>
                    </div>

                    
                    
                    <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05); flex:1; min-width:250px;">
                        <h4 style="color:#a1a1aa; font-size: 13px; margin:0 0 12px 0;">Yeni Sayaca Başla (Reset)</h4>
                        <div style="display:flex; gap:10px; flex-direction:column;">
                            <div style="display:flex; gap:10px;">
                                <button class="ly-btn ly-btn-secondary" style="flex:1" onclick="startNew(1)"><i class="fas fa-play"></i> 1 Saat</button>
                                <button class="ly-btn ly-btn-secondary" style="flex:1" onclick="startNew(12)"><i class="fas fa-play"></i> 12 Saat</button>
                                <button class="ly-btn ly-btn-secondary" style="flex:1" onclick="startNew(24)"><i class="fas fa-play"></i> 24 Saat</button>
                            </div>
                            <div style="font-size:11px; color:#ef4444; margin-top:5px;">* Uyarı: Devam eden sayaç varsa sıfırlanıp üzerine yazılır.</div>
                        </div>
                    </div>
                </div>
            </div>

            
            
            <div class="ly-card">
                <h3 class="ly-title" style="margin-bottom: 24px;"><i class="fas fa-cogs"></i> Süre ve Tasarım Ayarları</h3>
                
                <div style="display:flex; flex-direction:column; gap:20px;">
                    
                    
                
                    <div style="background:#151a21; padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05);">
                        <h4 style="color:var(--primary-color); margin:0 0 15px 0; font-size:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-star"></i> Abonelik Etkinlikleri
                        </h4>
                        <div class="ly-grid">
                            <div class="ly-group"><label>1 Yeni Abone (Sn)</label><input type="number" id="sec_sub" class="ly-input" placeholder="300"></div>
                            <div class="ly-group"><label>1 Yeniden Abone (Sn)</label><input type="number" id="sec_resub" class="ly-input" placeholder="300"></div>
                            <div class="ly-group"><label>1 Hediye Abone (Sn)</label><input type="number" id="sec_gift" class="ly-input" placeholder="300"></div>
                        </div>
                    </div>

                    
                    <div style="background:#151a21; padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05);">
                        <h4 style="color:var(--primary-color); margin:0 0 15px 0; font-size:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-coins"></i> Kicks Bağışları
                        </h4>
                        <div class="ly-grid">
                            <div class="ly-group"><label>Kicks Eşiği (Örn: 100)</label><input type="number" id="kicks_req" class="ly-input" placeholder="100"></div>
                            <div class="ly-group"><label>Eşiğe Verilecek Saniye</label><input type="number" id="sec_kicks" class="ly-input" placeholder="60"></div>
                        </div>
                    </div>

                    
                    <div style="background:#151a21; padding:20px; border-radius:12px; border:1px solid rgba(255,255,255,0.05);">
                        <h4 style="color:var(--primary-color); margin:0 0 15px 0; font-size:14px; display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-paint-brush"></i> Görünüm ve Animasyon
                        </h4>
                        <div class="ly-grid">
                            <div class="ly-group">
                                <label>OBS Sayaç Stili</label>
                                <select id="timer_style" class="ly-input">
                                    <option value="neon">Klasik Neon Yeşil</option>
                                    <option value="cyberpunk">Cyberpunk (Pembe/Mavi)</option>
                                    <option value="fire">Ateş (Kırmızı/Turuncu)</option>
                                    <option value="minimal">Minimalist (Sade Beyaz)</option>
                                    <option value="rgb">RGB Oyuncu</option>
                                </select>
                            </div>
                            <div class="ly-group" style="margin-top: 10px; grid-column: 1 / -1;">
                                <button type="button" class="ly-btn ly-btn-info" onclick="triggerShowcase(this)" style="justify-content:center; padding: 12px;"><i class="fas fa-play"></i> OBS'te Bilgi Animasyonunu Oynat</button>
                                <div style="font-size:11px; color:#a1a1aa; margin-top:6px;">* Sayaç üzerinde abonelik ve kicks bağışlarının kaç dakika eklediğini tek seferlik görsel animasyonlarla sırayla gösterir.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 20px; text-align:right;">
                    <button class="ly-btn ly-btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i> Ayarları Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal-overlay" id="cfModal">
        <div class="modal-box">
            <h3>Onay</h3>
            <p id="cfDesc">Emin misiniz?</p>
            <div class="modal-actions">
                <button onclick="closeCF()" style="background:#3f3f46; color:white;">İptal</button>
                <button onclick="execCF()" style="background:#ef4444; color:white;">Yeni Sayaç Başlat</button>
            </div>
        </div>
    </div>

    <script>
        let serverEndTs = 0;
        let serverNowTs = 0;
        let localOffset = 0;
        let pendingResetHours = 0;

        async function fetchData() {
            const res = await fetch('api/subathon.php?action=get');
            const d = await res.json();
            if(d.status === 'success') {
                const data = d.data;
                document.getElementById('sec_sub').value = data.sec_sub;
                document.getElementById('sec_resub').value = data.sec_resub;
                document.getElementById('sec_gift').value = data.sec_gift;
                document.getElementById('kicks_req').value = data.kicks_req;
                document.getElementById('sec_kicks').value = data.sec_kicks;
                document.getElementById('timer_style').value = data.timer_style || 'neon';
                
                const isActive = data.is_active == 1;
                document.getElementById('sub_active').checked = isActive;
                document.getElementById('live-timer').className = 'style-' + (data.timer_style || 'neon');
                document.getElementById('status-text').innerText = isActive ? 'Aktif' : 'Pasif';
                document.getElementById('status-text').style.color = isActive ? '#53fc18' : '#a1a1aa';

                serverEndTs = parseInt(data.end_ts) || 0;
                serverNowTs = parseInt(data.now_ts) || 0;
                localOffset = Math.floor(Date.now() / 1000) - serverNowTs;
            }
        }

        function updateTimer() {
            if(serverEndTs === 0) return;
            const currentRealTs = Math.floor(Date.now() / 1000) - localOffset;
            let diff = serverEndTs - currentRealTs;
            if(diff < 0) diff = 0;

            const h = Math.floor(diff / 3600);
            const m = Math.floor((diff % 3600) / 60);
            const s = diff % 60;
            document.getElementById('live-timer').innerText = 
                `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }

        setInterval(updateTimer, 1000);
        setInterval(fetchData, 3000); // 3 sn'de bir senkronize et

        document.getElementById('timer_style').addEventListener('change', function(e) {
            document.getElementById('live-timer').className = 'style-' + e.target.value;
        });

        async function sendPost(action, bodyObj) {
            const fd = new FormData();
            fd.append('action', action);
            for(let k in bodyObj) fd.append(k, bodyObj[k]);
            await fetch('api/subathon.php', {method: 'POST', body: fd});
            fetchData();
        }

        function toggleActive() {
            const v = document.getElementById('sub_active').checked ? 1 : 0;
            sendPost('toggle_active', {is_active: v});
        }
        function saveSettings() {
            sendPost('save_settings', {
                sec_sub: document.getElementById('sec_sub').value,
                sec_resub: document.getElementById('sec_resub').value,
                sec_gift: document.getElementById('sec_gift').value,
                kicks_req: document.getElementById('kicks_req').value,
                sec_kicks: document.getElementById('sec_kicks').value,
                timer_style: document.getElementById('timer_style').value
            });
        }
        function addTime(secs) { sendPost('add_time', {seconds: secs}); }
        function addCustomTime() {
            const val = parseInt(document.getElementById('custom_minutes').value);
            if(val > 0) { sendPost('add_time', {seconds: val * 60}); document.getElementById('custom_minutes').value = ''; }
        }
        
        function startNew(hours) {
            pendingResetHours = hours;
            document.getElementById('cfDesc').innerText = hours + " saatlik yepyeni bir sayaca başlamak istediğinize emin misiniz?";
            document.getElementById('cfModal').style.display = 'flex';
        }
        
        function closeCF() {
            document.getElementById('cfModal').style.display = 'none';
            pendingResetHours = 0;
        }
        
        function execCF() {
            if(pendingResetHours > 0) { sendPost('set_time', {hours: pendingResetHours}); }
            closeCF();
        }

        function triggerShowcase(btn) {
            const orgHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Oynatılıyor...';
            btn.disabled = true;
            sendPost('trigger_showcase', {}).then(() => {
                setTimeout(() => {
                    btn.innerHTML = orgHtml;
                    btn.disabled = false;
                }, 15000); // Animasyonun bitmesi için 15sn bekle
            });
        }

        fetchData();
    </script>
</body>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
</html>