<<<<<<< HEAD
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repertuar (Peçete) - SebastianBot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .sq-layout { flex: 1; padding: 24px; overflow-y: auto; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start;}
        @media(max-width: 1100px) { .sq-layout { grid-template-columns: 1fr; } }
        .sq-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .sq-title { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .sq-title i { color: var(--primary-color); }
        .switch-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight:600; color:#fff; }
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(20px); }
        .sq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .sq-group { display: flex; flex-direction: column; gap: 6px; }
        .sq-group label { font-size: 12px; color: var(--text-secondary); font-weight: 600; }
        .sq-input { background: #0f1318; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 10px 14px; font-size: 13px; outline: none; font-family: inherit; width:100%; box-sizing:border-box; }
        .sq-input:focus { border-color: var(--primary-color); }
        .sq-btn { border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; justify-content:center; transition: opacity .2s; }
        .sq-btn:hover { opacity: 0.8; }
        .sq-btn-primary { background: var(--primary-color); color: #000; }
        .sq-btn-secondary { background: #27273a; color: #fff; border: 1px solid #3f3f46; }
        .sq-btn-danger { background: #ef4444; color: #fff; }
        .sq-btn-info { background: #3b82f6; color: #fff; }
        .now-playing { background: linear-gradient(135deg, rgba(83, 252, 24, 0.05), rgba(0, 0, 0, 0.5)); border: 1px solid rgba(83, 252, 24, 0.2); border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .np-info { display: flex; flex-direction: column; gap: 6px; }
        .np-label { font-size: 11px; color: var(--primary-color); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .np-title { font-size: 20px; font-weight: 800; color: #fff; }
        .np-user { font-size: 13px; color: #a1a1aa; }
        .q-table { width: 100%; border-collapse: collapse; }
        .q-table th { text-align: left; padding: 10px; font-size: 11px; color: var(--text-secondary); text-transform: uppercase; border-bottom: 1px solid var(--border-color); position:sticky; top:0; background:var(--card-bg); z-index:10;}
        .q-table td { padding: 12px 10px; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .q-table tr:hover { background: rgba(255,255,255,0.02); }
        .sq-toast-wrap { position: fixed; right: 20px; bottom: 20px; z-index: 20000; display: flex; flex-direction: column; gap: 8px; }
        .sq-toast { background: #18181b; color: #fff; border-left: 4px solid var(--primary-color); border-radius: 6px; padding: 12px 16px; font-size: 13px; box-shadow: 0 8px 20px rgba(0,0,0,.35); opacity: 0; transform: translateY(20px); transition: all 0.3s ease; font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .sq-toast.show { opacity: 1; transform: translateY(0); }
        .sq-toast.err { border-left-color: #ef4444; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Repertuar Yönetimi (Canlı Müzik)</div>
            <div class="user-profile"><div class="avatar"><i class="fas fa-robot" style="color: var(--primary-color);"></i></div><span>SebastianBot</span></div>
        </header>

        <div class="sq-layout">
            <!-- Sol Sütun: Ayarlar ve Repertuar Listesi -->
            <div style="display:flex; flex-direction:column; gap:20px;">
                <!-- Modül Ayarları -->
                <div class="sq-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                        <h3 class="sq-title" style="margin:0;"><i class="fas fa-cogs"></i> Modül Ayarları</h3>
                        <label class="switch-wrapper">
                            <div class="switch"><input type="checkbox" id="is_active"><span class="slider"></span></div>
                            <span id="status-text">Aktif Değil</span>
                        </label>
                    </div>
                    <div class="sq-grid">
                        <div class="sq-group"><label>İstek (Peçete) Komutu</label><input type="text" id="req_cmd" class="sq-input" placeholder="!peçete"></div>
                        <div class="sq-group"><label>Liste Gosterme Komutu</label><input type="text" id="list_cmd" class="sq-input" placeholder="!repertuar"></div>
                        <div class="sq-group"><label>Sadakat Skoru Bedeli</label><input type="number" id="req_cost" class="sq-input" placeholder="50"></div>
                        <div class="sq-group" style="justify-content: flex-end;"><button class="sq-btn sq-btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i> Ayarları Kaydet</button></div>
                    </div>
                </div>

                <!-- Repertuar Listesi -->
                <div class="sq-card" style="flex:1; display:flex; flex-direction:column;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
                        <h3 class="sq-title" style="margin:0;"><i class="fas fa-list"></i> Şarkı Repertuarım</h3>
                        <span style="font-size:12px; color:#a1a1aa;">Kod ile talep edilecek</span>
                    </div>
                    <div style="display:flex; gap:10px; margin-bottom:16px;">
                        <input type="text" id="newSongName" class="sq-input" placeholder="Yeni parça adını yazın..." onkeypress="if(event.key==='Enter') addSong()">
                        <button class="sq-btn sq-btn-primary" onclick="addSong()"><i class="fas fa-plus"></i> Ekle</button>
                    </div>
                    <div style="flex:1; overflow-y:auto; max-height:400px; border:1px solid rgba(255,255,255,0.05); border-radius:8px;">
                        <table class="q-table">
                            <thead><tr><th style="width:50px;">Kodu</th><th>Parça Adı</th><th style="text-align:right;">İşlem</th></tr></thead>
                            <tbody id="repBody"><tr><td colspan="3"><div class="q-empty">Yükleniyor...</div></td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sağ Sütun: Gelen İstekler -->
            <div class="sq-card" style="display:flex; flex-direction:column; height: 100%;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                    <h3 class="sq-title" style="margin:0;"><i class="fas fa-envelope-open-text"></i> Gelen İstekler (Peçeteler)</h3>
                    <div style="display:flex; gap:10px;">
                        <button class="sq-btn sq-btn-info" onclick="doAction('showcase')"><i class="fas fa-list-ol"></i> Ekranda Göster</button>
                        <a href="reperscreen.php" target="_blank" class="sq-btn sq-btn-secondary"><i class="fas fa-desktop"></i> OBS Ekranını Aç</a>
                    </div>
                </div>

                <div class="now-playing">
                    <div class="np-info">
                        <div class="np-label" id="npStateLabel">Duraklatıldı</div>
                        <div class="np-title" id="npTitle">İstek Bekleniyor...</div>
                        <div class="np-user" id="npUser">-</div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <button class="sq-btn sq-btn-primary" id="btnPlayPause" onclick="togglePlay()"><i class="fas fa-play"></i> Çal</button>
                        <button class="sq-btn sq-btn-secondary" onclick="doAction('skip')"><i class="fas fa-forward-step"></i> Geç</button>
                    </div>
                </div>

                <div style="flex:1; overflow-y:auto;">
                    <table class="q-table">
                        <thead><tr><th>#</th><th>Kod</th><th>Parça Adı</th><th>İsteyen</th><th style="text-align:right;">Sil</th></tr></thead>
                        <tbody id="qBody"><tr><td colspan="5"><div class="q-empty">Yükleniyor...</div></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sq-toast-wrap" id="toastWrap"></div>

    <script>
        let isPlaying = false;

        function showToast(msg, isError = false) {
            const wrap = document.getElementById('toastWrap');
            const t = document.createElement('div');
            t.className = 'sq-toast' + (isError ? ' err' : '');
            t.innerHTML = (isError ? '<i class="fas fa-times-circle" style="color:#ef4444;"></i> ' : '<i class="fas fa-check-circle" style="color:var(--primary-color);"></i> ') + msg;
            wrap.appendChild(t);
            setTimeout(() => { t.classList.add('show'); }, 10);
            setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 300); }, 3000);
        }

        async function fetchState() {
            try {
                const res = await fetch('api/repertoire_api.php?action=state');
                const data = await res.json();
                if(data.status === 'success') {
                    document.getElementById('req_cmd').value = data.settings.request_command;
                    document.getElementById('list_cmd').value = data.settings.list_command;
                    document.getElementById('req_cost').value = data.settings.request_cost;
                    const active = parseInt(data.settings.is_active) === 1;
                    document.getElementById('is_active').checked = active;
                    document.getElementById('status-text').innerText = active ? 'Aktif' : 'Aktif Değil';
                    document.getElementById('status-text').style.color = active ? '#53fc18' : '#a1a1aa';

                    isPlaying = parseInt(data.settings.is_playing) === 1;
                    const btn = document.getElementById('btnPlayPause');
                    const lbl = document.getElementById('npStateLabel');
                    if(isPlaying) {
                        btn.innerHTML = '<i class="fas fa-pause"></i> Duraklat';
                        lbl.innerText = 'Şu An Çalınan Peçete';
                        lbl.style.color = '#53fc18';
                    } else {
                        btn.innerHTML = '<i class="fas fa-play"></i> Çal';
                        lbl.innerText = 'Duraklatıldı';
                        lbl.style.color = '#f59e0b';
                    }

                    if (data.current) {
                        document.getElementById('npTitle').innerText = data.current.song_name;
                        document.getElementById('npUser').innerHTML = `<i class="fas fa-user"></i> @${data.current.username} <span style="color:#60a5fa; font-size:11px;">(Kod: #${data.current.song_id})</span>`;
                    } else {
                        document.getElementById('npTitle').innerText = "Sıra Boş, İstek Bekleniyor...";
                        document.getElementById('npUser').innerText = "-";
                    }

                    const tb = document.getElementById('qBody');
                    if(data.queue.length === 0) { tb.innerHTML = '<tr><td colspan="5"><div class="q-empty">Sırada bekleyen peçete yok.</div></td></tr>'; } 
                    else {
                        tb.innerHTML = data.queue.map((q, i) => `<tr><td style="color:#a1a1aa;">${i+1}</td><td style="color:#60a5fa; font-weight:bold;">#${q.song_id}</td><td style="color:#fff; font-weight:600;">${q.song_name}</td><td style="color:#a1a1aa;">@${q.username}</td><td style="text-align:right;"><button class="sq-btn sq-btn-danger" style="padding:4px 8px;" onclick="delReq(${q.id})"><i class="fas fa-trash"></i></button></td></tr>`).join('');
                    }
                }
            } catch(e) {}
        }

        async function fetchSongs() {
            try {
                const res = await fetch('api/repertoire_api.php?action=get_songs');
                const data = await res.json();
                if(data.status === 'success') {
                    const tb = document.getElementById('repBody');
                    if(data.data.length === 0) { tb.innerHTML = '<tr><td colspan="3"><div class="q-empty">Repertuar boş.</div></td></tr>'; } 
                    else {
                        tb.innerHTML = data.data.map(s => `<tr><td style="color:var(--primary-color); font-weight:bold;">#${s.id}</td><td style="color:#fff;">${s.song_name}</td><td style="text-align:right;"><button class="sq-btn sq-btn-secondary" style="padding:4px 8px;" onclick="delSong(${s.id})"><i class="fas fa-trash" style="color:#ef4444;"></i></button></td></tr>`).join('');
                    }
                }
            } catch(e) {}
        }

        function saveSettings() {
            const fd = new FormData();
            fd.append('action', 'save_settings');
            fd.append('request_command', document.getElementById('req_cmd').value);
            fd.append('list_command', document.getElementById('list_cmd').value);
            fd.append('request_cost', document.getElementById('req_cost').value);
            fd.append('is_active', document.getElementById('is_active').checked ? 1 : 0);
            fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(()=> { fetchState(); showToast("Ayarlar kaydedildi!"); });
        }

        function addSong() {
            const n = document.getElementById('newSongName');
            if(n.value.trim() === '') return;
            const fd = new FormData(); fd.append('action', 'add_song'); fd.append('song_name', n.value.trim());
            fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(()=> { n.value = ''; fetchSongs(); showToast("Parça eklendi!"); });
        }

        function delSong(id) { if(confirm("Parçayı repertuardan silmek istediğinize emin misiniz?")) { const fd = new FormData(); fd.append('action', 'delete_song'); fd.append('id', id); fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(()=> { fetchSongs(); showToast("Parça silindi!"); }); } }
        function delReq(id) { const fd = new FormData(); fd.append('action', 'delete_request'); fd.append('id', id); fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(fetchState); }
        function togglePlay() { doAction(isPlaying ? 'pause' : 'play'); }
        function doAction(act) { const fd = new FormData(); fd.append('action', act); fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(() => { fetchState(); if(act === 'showcase') showToast("Sıradaki parçalar ekranda gösteriliyor!"); }); }

        setInterval(fetchState, 3000);
        fetchState();
        fetchSongs();
    </script>
</body>
=======
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repertuar (Peçete) - SebastianBot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .sq-layout { flex: 1; padding: 24px; overflow-y: auto; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; align-items: start;}
        @media(max-width: 1100px) { .sq-layout { grid-template-columns: 1fr; } }
        .sq-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 24px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .sq-title { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .sq-title i { color: var(--primary-color); }
        .switch-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight:600; color:#fff; }
        .switch { position: relative; display: inline-block; width: 40px; height: 20px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--border-color); transition: .4s; border-radius: 20px; }
        .slider:before { position: absolute; content: ""; height: 14px; width: 14px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(20px); }
        .sq-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .sq-group { display: flex; flex-direction: column; gap: 6px; }
        .sq-group label { font-size: 12px; color: var(--text-secondary); font-weight: 600; }
        .sq-input { background: #0f1318; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 10px 14px; font-size: 13px; outline: none; font-family: inherit; width:100%; box-sizing:border-box; }
        .sq-input:focus { border-color: var(--primary-color); }
        .sq-btn { border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; padding: 10px 16px; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; justify-content:center; transition: opacity .2s; }
        .sq-btn:hover { opacity: 0.8; }
        .sq-btn-primary { background: var(--primary-color); color: #000; }
        .sq-btn-secondary { background: #27273a; color: #fff; border: 1px solid #3f3f46; }
        .sq-btn-danger { background: #ef4444; color: #fff; }
        .sq-btn-info { background: #3b82f6; color: #fff; }
        .now-playing { background: linear-gradient(135deg, rgba(83, 252, 24, 0.05), rgba(0, 0, 0, 0.5)); border: 1px solid rgba(83, 252, 24, 0.2); border-radius: 12px; padding: 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .np-info { display: flex; flex-direction: column; gap: 6px; }
        .np-label { font-size: 11px; color: var(--primary-color); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .np-title { font-size: 20px; font-weight: 800; color: #fff; }
        .np-user { font-size: 13px; color: #a1a1aa; }
        .q-table { width: 100%; border-collapse: collapse; }
        .q-table th { text-align: left; padding: 10px; font-size: 11px; color: var(--text-secondary); text-transform: uppercase; border-bottom: 1px solid var(--border-color); position:sticky; top:0; background:var(--card-bg); z-index:10;}
        .q-table td { padding: 12px 10px; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .q-table tr:hover { background: rgba(255,255,255,0.02); }
        .sq-toast-wrap { position: fixed; right: 20px; bottom: 20px; z-index: 20000; display: flex; flex-direction: column; gap: 8px; }
        .sq-toast { background: #18181b; color: #fff; border-left: 4px solid var(--primary-color); border-radius: 6px; padding: 12px 16px; font-size: 13px; box-shadow: 0 8px 20px rgba(0,0,0,.35); opacity: 0; transform: translateY(20px); transition: all 0.3s ease; font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .sq-toast.show { opacity: 1; transform: translateY(0); }
        .sq-toast.err { border-left-color: #ef4444; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Repertuar Yönetimi (Canlı Müzik)</div>
            <div class="user-profile"><div class="avatar"><i class="fas fa-robot" style="color: var(--primary-color);"></i></div><span>SebastianBot</span></div>
        </header>

        <div class="sq-layout">
            <!-- Sol Sütun: Ayarlar ve Repertuar Listesi -->
            <div style="display:flex; flex-direction:column; gap:20px;">
                <!-- Modül Ayarları -->
                <div class="sq-card">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                        <h3 class="sq-title" style="margin:0;"><i class="fas fa-cogs"></i> Modül Ayarları</h3>
                        <label class="switch-wrapper">
                            <div class="switch"><input type="checkbox" id="is_active"><span class="slider"></span></div>
                            <span id="status-text">Aktif Değil</span>
                        </label>
                    </div>
                    <div class="sq-grid">
                        <div class="sq-group"><label>İstek (Peçete) Komutu</label><input type="text" id="req_cmd" class="sq-input" placeholder="!peçete"></div>
                        <div class="sq-group"><label>Liste Gosterme Komutu</label><input type="text" id="list_cmd" class="sq-input" placeholder="!repertuar"></div>
                        <div class="sq-group"><label>Sadakat Skoru Bedeli</label><input type="number" id="req_cost" class="sq-input" placeholder="50"></div>
                        <div class="sq-group" style="justify-content: flex-end;"><button class="sq-btn sq-btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i> Ayarları Kaydet</button></div>
                    </div>
                </div>

                <!-- Repertuar Listesi -->
                <div class="sq-card" style="flex:1; display:flex; flex-direction:column;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 16px;">
                        <h3 class="sq-title" style="margin:0;"><i class="fas fa-list"></i> Şarkı Repertuarım</h3>
                        <span style="font-size:12px; color:#a1a1aa;">Kod ile talep edilecek</span>
                    </div>
                    <div style="display:flex; gap:10px; margin-bottom:16px;">
                        <input type="text" id="newSongName" class="sq-input" placeholder="Yeni parça adını yazın..." onkeypress="if(event.key==='Enter') addSong()">
                        <button class="sq-btn sq-btn-primary" onclick="addSong()"><i class="fas fa-plus"></i> Ekle</button>
                    </div>
                    <div style="flex:1; overflow-y:auto; max-height:400px; border:1px solid rgba(255,255,255,0.05); border-radius:8px;">
                        <table class="q-table">
                            <thead><tr><th style="width:50px;">Kodu</th><th>Parça Adı</th><th style="text-align:right;">İşlem</th></tr></thead>
                            <tbody id="repBody"><tr><td colspan="3"><div class="q-empty">Yükleniyor...</div></td></tr></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sağ Sütun: Gelen İstekler -->
            <div class="sq-card" style="display:flex; flex-direction:column; height: 100%;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
                    <h3 class="sq-title" style="margin:0;"><i class="fas fa-envelope-open-text"></i> Gelen İstekler (Peçeteler)</h3>
                    <div style="display:flex; gap:10px;">
                        <button class="sq-btn sq-btn-info" onclick="doAction('showcase')"><i class="fas fa-list-ol"></i> Ekranda Göster</button>
                        <a href="reperscreen.php" target="_blank" class="sq-btn sq-btn-secondary"><i class="fas fa-desktop"></i> OBS Ekranını Aç</a>
                    </div>
                </div>

                <div class="now-playing">
                    <div class="np-info">
                        <div class="np-label" id="npStateLabel">Duraklatıldı</div>
                        <div class="np-title" id="npTitle">İstek Bekleniyor...</div>
                        <div class="np-user" id="npUser">-</div>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <button class="sq-btn sq-btn-primary" id="btnPlayPause" onclick="togglePlay()"><i class="fas fa-play"></i> Çal</button>
                        <button class="sq-btn sq-btn-secondary" onclick="doAction('skip')"><i class="fas fa-forward-step"></i> Geç</button>
                    </div>
                </div>

                <div style="flex:1; overflow-y:auto;">
                    <table class="q-table">
                        <thead><tr><th>#</th><th>Kod</th><th>Parça Adı</th><th>İsteyen</th><th style="text-align:right;">Sil</th></tr></thead>
                        <tbody id="qBody"><tr><td colspan="5"><div class="q-empty">Yükleniyor...</div></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sq-toast-wrap" id="toastWrap"></div>

    <script>
        let isPlaying = false;

        function showToast(msg, isError = false) {
            const wrap = document.getElementById('toastWrap');
            const t = document.createElement('div');
            t.className = 'sq-toast' + (isError ? ' err' : '');
            t.innerHTML = (isError ? '<i class="fas fa-times-circle" style="color:#ef4444;"></i> ' : '<i class="fas fa-check-circle" style="color:var(--primary-color);"></i> ') + msg;
            wrap.appendChild(t);
            setTimeout(() => { t.classList.add('show'); }, 10);
            setTimeout(() => { t.classList.remove('show'); setTimeout(() => t.remove(), 300); }, 3000);
        }

        async function fetchState() {
            try {
                const res = await fetch('api/repertoire_api.php?action=state');
                const data = await res.json();
                if(data.status === 'success') {
                    document.getElementById('req_cmd').value = data.settings.request_command;
                    document.getElementById('list_cmd').value = data.settings.list_command;
                    document.getElementById('req_cost').value = data.settings.request_cost;
                    const active = parseInt(data.settings.is_active) === 1;
                    document.getElementById('is_active').checked = active;
                    document.getElementById('status-text').innerText = active ? 'Aktif' : 'Aktif Değil';
                    document.getElementById('status-text').style.color = active ? '#53fc18' : '#a1a1aa';

                    isPlaying = parseInt(data.settings.is_playing) === 1;
                    const btn = document.getElementById('btnPlayPause');
                    const lbl = document.getElementById('npStateLabel');
                    if(isPlaying) {
                        btn.innerHTML = '<i class="fas fa-pause"></i> Duraklat';
                        lbl.innerText = 'Şu An Çalınan Peçete';
                        lbl.style.color = '#53fc18';
                    } else {
                        btn.innerHTML = '<i class="fas fa-play"></i> Çal';
                        lbl.innerText = 'Duraklatıldı';
                        lbl.style.color = '#f59e0b';
                    }

                    if (data.current) {
                        document.getElementById('npTitle').innerText = data.current.song_name;
                        document.getElementById('npUser').innerHTML = `<i class="fas fa-user"></i> @${data.current.username} <span style="color:#60a5fa; font-size:11px;">(Kod: #${data.current.song_id})</span>`;
                    } else {
                        document.getElementById('npTitle').innerText = "Sıra Boş, İstek Bekleniyor...";
                        document.getElementById('npUser').innerText = "-";
                    }

                    const tb = document.getElementById('qBody');
                    if(data.queue.length === 0) { tb.innerHTML = '<tr><td colspan="5"><div class="q-empty">Sırada bekleyen peçete yok.</div></td></tr>'; } 
                    else {
                        tb.innerHTML = data.queue.map((q, i) => `<tr><td style="color:#a1a1aa;">${i+1}</td><td style="color:#60a5fa; font-weight:bold;">#${q.song_id}</td><td style="color:#fff; font-weight:600;">${q.song_name}</td><td style="color:#a1a1aa;">@${q.username}</td><td style="text-align:right;"><button class="sq-btn sq-btn-danger" style="padding:4px 8px;" onclick="delReq(${q.id})"><i class="fas fa-trash"></i></button></td></tr>`).join('');
                    }
                }
            } catch(e) {}
        }

        async function fetchSongs() {
            try {
                const res = await fetch('api/repertoire_api.php?action=get_songs');
                const data = await res.json();
                if(data.status === 'success') {
                    const tb = document.getElementById('repBody');
                    if(data.data.length === 0) { tb.innerHTML = '<tr><td colspan="3"><div class="q-empty">Repertuar boş.</div></td></tr>'; } 
                    else {
                        tb.innerHTML = data.data.map(s => `<tr><td style="color:var(--primary-color); font-weight:bold;">#${s.id}</td><td style="color:#fff;">${s.song_name}</td><td style="text-align:right;"><button class="sq-btn sq-btn-secondary" style="padding:4px 8px;" onclick="delSong(${s.id})"><i class="fas fa-trash" style="color:#ef4444;"></i></button></td></tr>`).join('');
                    }
                }
            } catch(e) {}
        }

        function saveSettings() {
            const fd = new FormData();
            fd.append('action', 'save_settings');
            fd.append('request_command', document.getElementById('req_cmd').value);
            fd.append('list_command', document.getElementById('list_cmd').value);
            fd.append('request_cost', document.getElementById('req_cost').value);
            fd.append('is_active', document.getElementById('is_active').checked ? 1 : 0);
            fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(()=> { fetchState(); showToast("Ayarlar kaydedildi!"); });
        }

        function addSong() {
            const n = document.getElementById('newSongName');
            if(n.value.trim() === '') return;
            const fd = new FormData(); fd.append('action', 'add_song'); fd.append('song_name', n.value.trim());
            fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(()=> { n.value = ''; fetchSongs(); showToast("Parça eklendi!"); });
        }

        function delSong(id) { if(confirm("Parçayı repertuardan silmek istediğinize emin misiniz?")) { const fd = new FormData(); fd.append('action', 'delete_song'); fd.append('id', id); fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(()=> { fetchSongs(); showToast("Parça silindi!"); }); } }
        function delReq(id) { const fd = new FormData(); fd.append('action', 'delete_request'); fd.append('id', id); fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(fetchState); }
        function togglePlay() { doAction(isPlaying ? 'pause' : 'play'); }
        function doAction(act) { const fd = new FormData(); fd.append('action', act); fetch('api/repertoire_api.php', {method:'POST', body:fd}).then(() => { fetchState(); if(act === 'showcase') showToast("Sıradaki parçalar ekranda gösteriliyor!"); }); }

        setInterval(fetchState, 3000);
        fetchState();
        fetchSongs();
    </script>
</body>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
</html>