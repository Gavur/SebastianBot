<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takipçiler - BotDash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .followers-layout { flex:1; padding:24px; overflow-y:auto; display:flex; flex-direction:column; gap:20px; }
        .fl-card { background:var(--card-bg); border-radius:16px; border:1px solid var(--border-color); padding:24px; box-shadow:0 8px 24px rgba(0,0,0,0.2); }
        .fl-toolbar { display:flex; gap:12px; align-items:center; flex-wrap:wrap; }
        .fl-search { background:#0f1318; border:1px solid var(--border-color); border-radius:8px; color:white; padding:10px 14px; font-size:14px; outline:none; width:280px; font-family:inherit; transition:border-color .2s; }
        .fl-search:focus { border-color:var(--primary-color); }
        .fl-filter-btn { padding:8px 16px; border:1px solid var(--border-color); border-radius:8px; background:transparent; color:var(--text-secondary); cursor:pointer; font-size:13px; font-weight:600; font-family:inherit; transition:all .2s; }
        .fl-filter-btn.active { background:var(--primary-color); color:#000; border-color:var(--primary-color); }
        .fl-filter-btn:hover:not(.active) { border-color:var(--text-secondary); color:white; }
        .fl-count { margin-left:auto; color:var(--text-secondary); font-size:13px; }
        .fl-table { width:100%; border-collapse:separate; border-spacing:0 4px; margin-top:16px; }
        .fl-table thead th { font-size:11px; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.5px; font-weight:600; text-align:left; padding:8px 14px; }
        .fl-table tbody tr { background:#151a21; cursor:pointer; transition:background .2s; }
        .fl-table tbody tr:hover { background:rgba(83,252,24,0.04); }
        .fl-table tbody td { padding:10px 14px; font-size:13px; vertical-align:middle; }
        .fl-table tbody td:first-child { border-radius:8px 0 0 8px; }
        .fl-table tbody td:last-child { border-radius:0 8px 8px 0; }
        .fl-username { font-weight:700; color:var(--primary-color); }
        .fl-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
        .fl-badge.clean { background:rgba(16,185,129,0.15); color:#34d399; }
        .fl-badge.banned { background:rgba(239,68,68,0.15); color:#f87171; }
        .fl-badge.timeout { background:rgba(234,179,8,0.15); color:#fbbf24; }
        .fl-pagination { display:flex; justify-content:center; gap:6px; margin-top:16px; }
        .fl-page-btn { padding:8px 14px; border:1px solid var(--border-color); border-radius:6px; background:transparent; color:var(--text-secondary); cursor:pointer; font-family:inherit; font-size:13px; transition:all .2s; }
        .fl-page-btn.active { background:var(--primary-color); color:#000; border-color:var(--primary-color); }
        .fl-page-btn:hover:not(.active) { border-color:var(--text-secondary); }
        .fl-empty { text-align:center; padding:40px; color:var(--text-secondary); }
        .fl-empty i { font-size:40px; margin-bottom:12px; opacity:.3; display:block; }

        
        .ud-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:10000; justify-content:center; align-items:center; backdrop-filter:blur(4px); }
        .ud-box { background:#1e1e2d; padding:24px; border-radius:12px; width:560px; max-height:88vh; border:1px solid rgba(255,255,255,0.1); box-shadow:0 10px 25px rgba(0,0,0,0.5); display:flex; flex-direction:column; overflow:hidden; }
        .ud-header { display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #3f3f46; padding-bottom:12px; margin-bottom:16px; }
        .ud-header h3 { margin:0; color:white; font-size:20px; }
        .ud-close { background:transparent; border:none; color:#a1a1aa; cursor:pointer; font-size:16px; transition:color .2s; }
        .ud-close:hover { color:white; }
        .ud-stats { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:16px; }
        .ud-stat { background:#27272a; padding:10px; border-radius:8px; }
        .ud-stat-label { font-size:10px; color:#a1a1aa; text-transform:uppercase; letter-spacing:.5px; }
        .ud-stat-value { font-size:14px; color:white; font-weight:700; margin-top:3px; }
        .ud-section-title { font-size:12px; color:#a1a1aa; font-weight:600; text-transform:uppercase; letter-spacing:.5px; margin:12px 0 8px 0; display:flex; align-items:center; gap:8px; justify-content:space-between; }
        .ud-scrollable { overflow-y:auto; flex:1; max-height:220px; display:flex; flex-direction:column; gap:6px; padding-right:4px; }
        .ud-msg { background:#27272a; padding:8px 10px; border-radius:6px; font-size:12px; border-left:3px solid #3b82f6; }
        .ud-msg-time { color:#a1a1aa; font-size:10px; margin-right:6px; }
        .ud-ban-item { background:#27272a; padding:8px 10px; border-radius:6px; font-size:12px; border-left:3px solid #ef4444; display:flex; justify-content:space-between; }
        .ud-actions { display:flex; gap:10px; border-top:1px solid #3f3f46; padding-top:14px; margin-top:14px; align-items:center; }
        .ud-action-btn { padding:8px 16px; border:none; border-radius:6px; cursor:pointer; font-weight:600; font-size:13px; font-family:inherit; transition:all .2s; }
        .ud-msg-search { background:#27272a; color:white; border:1px solid #3f3f46; padding:4px 8px; border-radius:4px; font-size:11px; outline:none; width:140px; }

        
        .cf-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:11000; justify-content:center; align-items:center; backdrop-filter:blur(4px); }
        .cf-box { background:#1e1e2d; border-radius:12px; padding:25px; width:380px; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.1); }
        .cf-box h3 { color:white; margin:0 0 12px; font-size:18px; }
        .cf-box p { color:#a1a1aa; font-size:14px; margin-bottom:20px; }
        .cf-actions { display:flex; gap:10px; }
        .cf-actions button { flex:1; padding:10px; border:none; border-radius:6px; cursor:pointer; font-weight:bold; font-family:inherit; }
        .timeout-select-row { display:flex; gap:8px; margin-bottom:20px; }
        .timeout-select-row select, .timeout-select-row input { background:#27273a; color:white; border:1px solid #3f3f46; border-radius:6px; padding:8px; flex:1; outline:none; font-family:inherit; }

        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }
        .fl-table tbody tr { animation:fadeIn .3s ease; }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Takipçi Yönetimi</div>
            <div class="user-profile">
                <div class="avatar"><i class="fas fa-robot" style="color:var(--primary-color);"></i></div>
                <span>SebastianBot</span>
            </div>
        </header>
        <div class="followers-layout">
            <div class="fl-card">
                <div class="fl-toolbar">
                    <input type="text" class="fl-search" id="flSearch" placeholder="Kullanıcı ara..." oninput="debounceSearch()">
                    <button class="fl-filter-btn active" data-f="all" onclick="setFilter('all')">Tümü</button>
                    <button class="fl-filter-btn" data-f="clean" onclick="setFilter('clean')"><i class="fas fa-check-circle"></i> Temiz</button>
                    <button class="fl-filter-btn" data-f="banned" onclick="setFilter('banned')"><i class="fas fa-ban"></i> Banlı</button>
                    <button class="fl-filter-btn" data-f="timeout" onclick="setFilter('timeout')"><i class="fas fa-clock"></i> Timeout</button>
                    <span class="fl-count" id="flCount">0 takipçi</span>
                </div>
                <table class="fl-table">
                    <thead><tr><th>Kullanıcı</th><th>Takip Tarihi</th><th>Mesaj</th><th>Durum</th></tr></thead>
                    <tbody id="flBody"><tr><td colspan="4"><div class="fl-empty"><i class="fas fa-users"></i><p>Yükleniyor...</p></div></td></tr></tbody>
                </table>
                <div class="fl-pagination" id="flPagination"></div>
            </div>
        </div>
    </div>

    
    <div class="ud-overlay" id="udModal">
        <div class="ud-box">
            <div class="ud-header">
                <h3 id="udName">Yükleniyor...</h3>
                <button class="ud-close" onclick="closeUD()"><i class="fas fa-times"></i></button>
            </div>
            <div id="udStats" class="ud-stats"></div>
            <div id="udFollowInfo" style="font-size:12px;color:#a1a1aa;margin-bottom:12px;"></div>

            <div class="ud-section-title"><span><i class="fas fa-gavel"></i> Ceza Geçmişi</span></div>
            <div id="udBanHistory" class="ud-scrollable" style="max-height:120px;margin-bottom:8px;"></div>

            <div class="ud-section-title">
                <span><i class="fas fa-comment-dots"></i> Mesaj Geçmişi</span>
                <input type="text" class="ud-msg-search" id="udMsgSearch" placeholder="Mesajlarda ara..." oninput="filterUDMsgs()">
            </div>
            <div id="udMessages" class="ud-scrollable"></div>
            <div id="udActions" class="ud-actions"></div>
        </div>
    </div>

    
    <div class="cf-overlay" id="cfModal">
        <div class="cf-box">
            <h3 id="cfTitle">Onay</h3>
            <p id="cfDesc">Emin misiniz?</p>
            <div id="cfTimeoutRow" class="timeout-select-row" style="display:none;">
                <select id="cfTimeoutDur">
                    <option value="1">1 Dakika</option><option value="5" selected>5 Dakika</option>
                    <option value="10">10 Dakika</option><option value="30">30 Dakika</option>
                    <option value="60">1 Saat</option><option value="1440">1 Gün</option>
                    <option value="custom">Özel Süre</option>
                </select>
                <input type="number" id="cfCustomDur" min="1" max="10080" placeholder="Dakika" style="display:none;">
            </div>
            <div class="cf-actions">
                <button onclick="closeCF()" style="background:#3f3f46;color:white;">İptal</button>
                <button id="cfConfirmBtn" onclick="execCF()" style="background:#ef4444;color:white;">Onayla</button>
            </div>
        </div>
    </div>

    <script>
    let currentFilter = 'all', currentPage = 1, searchTimer = null;
    let cfAction = null, cfUserId = null, udTimerInterval = null;

    document.getElementById('cfTimeoutDur').addEventListener('change', e => {
        document.getElementById('cfCustomDur').style.display = e.target.value === 'custom' ? 'block' : 'none';
    });

    function debounceSearch() { clearTimeout(searchTimer); searchTimer = setTimeout(() => { currentPage = 1; fetchFollowers(); }, 300); }
    function setFilter(f) {
        currentFilter = f; currentPage = 1;
        document.querySelectorAll('.fl-filter-btn').forEach(b => b.classList.toggle('active', b.dataset.f === f));
        fetchFollowers();
    }

    async function fetchFollowers() {
        const search = document.getElementById('flSearch').value.trim();
        const url = `api/get_followers.php?page=${currentPage}&filter=${currentFilter}&search=${encodeURIComponent(search)}`;
        try {
            const res = await fetch(url);
            const data = await res.json();
            if (data.status === 'success') {
                renderTable(data.data);
                renderPagination(data.page, data.total_pages);
                document.getElementById('flCount').textContent = data.total + ' takipçi';
            }
        } catch(e) { console.error(e); }
    }

    function renderTable(rows) {
        const tbody = document.getElementById('flBody');
        if (!rows.length) { tbody.innerHTML = '<tr><td colspan="4"><div class="fl-empty"><i class="fas fa-users"></i><p>Takipçi bulunamadı.</p></div></td></tr>'; return; }
        tbody.innerHTML = rows.map(r => {
            const fd = r.follow_date ? new Date(r.follow_date.replace(' ','T')+'Z') : null;
            const dateStr = fd ? fd.toLocaleDateString('tr-TR') + ' ' + fd.toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'}) : '-';
            let badge = '<span class="fl-badge clean"><i class="fas fa-check-circle"></i> Temiz</span>';
            if (r.is_banned == 1) badge = '<span class="fl-badge banned"><i class="fas fa-ban"></i> Banlı</span>';
            else if (r.remaining_seconds && parseInt(r.remaining_seconds) > 0) badge = '<span class="fl-badge timeout"><i class="fas fa-clock"></i> Timeout</span>';
            return `<tr onclick="openUD('${r.user_id}')"><td><span class="fl-username">${esc(r.username)}</span></td><td style="color:var(--text-secondary)">${dateStr}</td><td style="font-weight:600">${r.message_count}</td><td>${badge}</td></tr>`;
        }).join('');
    }

    function renderPagination(page, totalPages) {
        const div = document.getElementById('flPagination');
        if (totalPages <= 1) { div.innerHTML = ''; return; }
        let html = '';
        const start = Math.max(1, page - 3), end = Math.min(totalPages, page + 3);
        if (page > 1) html += `<button class="fl-page-btn" onclick="goPage(${page-1})"><i class="fas fa-chevron-left"></i></button>`;
        for (let i = start; i <= end; i++) html += `<button class="fl-page-btn ${i===page?'active':''}" onclick="goPage(${i})">${i}</button>`;
        if (page < totalPages) html += `<button class="fl-page-btn" onclick="goPage(${page+1})"><i class="fas fa-chevron-right"></i></button>`;
        div.innerHTML = html;
    }
    function goPage(p) { currentPage = p; fetchFollowers(); }

    
    async function openUD(userId) {
        document.getElementById('udModal').style.display = 'flex';
        document.getElementById('udName').textContent = 'Yükleniyor...';
        document.getElementById('udStats').innerHTML = '';
        document.getElementById('udMessages').innerHTML = '';
        document.getElementById('udBanHistory').innerHTML = '';
        document.getElementById('udActions').innerHTML = '';
        document.getElementById('udFollowInfo').innerHTML = '';
        document.getElementById('udMsgSearch').value = '';
        if (udTimerInterval) clearInterval(udTimerInterval);

        try {
            const res = await fetch('api/get_user_info.php?user_id=' + userId);
            const data = await res.json();
            if (data.status !== 'success') return;
            const u = data.user;
            document.getElementById('udName').textContent = u.username;

            // Status
            let statusHtml = '<span style="color:#10b981"><i class="fas fa-check-circle"></i> Temiz</span>';
            let isTimeout = false, remaining = 0;
            if (u.is_banned == 1) statusHtml = '<span style="color:#ef4444"><i class="fas fa-ban"></i> Kalıcı Ban</span>';
            else if (u.remaining_seconds && parseInt(u.remaining_seconds) > 0) {
                isTimeout = true; remaining = parseInt(u.remaining_seconds);
                statusHtml = '<span id="udTimer" style="color:#fbbf24"><i class="fas fa-stopwatch"></i> Timeout</span>';
            }

            // Follow info
            let followHtml = '';
            if (u.follow_date) {
                const fd = new Date(u.follow_date.replace(' ','T')+'Z');
                followHtml = `<i class="fas fa-calendar"></i> Takip: ${fd.toLocaleDateString('tr-TR')} ${fd.toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'})} · `;
                if (u.followed_during_stream == 1) {
                    const cat = u.followed_stream_category || 'Bilinmiyor';
                    const title = u.followed_stream_title || '';
                    followHtml += `<span style="color:#10b981">Yayındayken</span> (${esc(cat)}${title ? ' - '+esc(title) : ''})`;
                } else { followHtml += '<span style="color:#a1a1aa">Yayın dışı takip</span>'; }
            } else { followHtml = '<span style="color:#a1a1aa">Takip bilgisi yok</span>'; }
            if (data.refollow_count > 0) followHtml += ` · <span style="color:#3b82f6">${data.refollow_count}x yeniden takip</span>`;
            document.getElementById('udFollowInfo').innerHTML = followHtml;

            // Stats grid
            document.getElementById('udStats').innerHTML = `
                <div class="ud-stat"><div class="ud-stat-label">Durum</div><div class="ud-stat-value">${statusHtml}</div></div>
                <div class="ud-stat"><div class="ud-stat-label">Mesaj Sayısı</div><div class="ud-stat-value">${u.message_count}</div></div>
                <div class="ud-stat"><div class="ud-stat-label">Silinen Mesaj</div><div class="ud-stat-value">${u.deleted_message_count}</div></div>
                <div class="ud-stat"><div class="ud-stat-label">Ban Sayısı</div><div class="ud-stat-value" style="color:#ef4444">${u.ban_count}</div></div>
                <div class="ud-stat"><div class="ud-stat-label">Timeout Sayısı</div><div class="ud-stat-value" style="color:#fbbf24">${u.timeout_count}</div></div>
                <div class="ud-stat"><div class="ud-stat-label">Yeniden Takip</div><div class="ud-stat-value" style="color:#3b82f6">${data.refollow_count}</div></div>
            `;

            // Timer
            if (isTimeout) {
                const updateT = () => {
                    if (remaining <= 0) { document.getElementById('udTimer').innerHTML = '<i class="fas fa-check-circle"></i> Süre Bitti'; clearInterval(udTimerInterval); return; }
                    const m = Math.floor(remaining/60), s = remaining%60;
                    document.getElementById('udTimer').innerHTML = `<i class="fas fa-stopwatch"></i> ${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
                    remaining--;
                };
                updateT();
                udTimerInterval = setInterval(updateT, 1000);
            }

            // Ban history
            if (data.ban_history && data.ban_history.length > 0) {
                document.getElementById('udBanHistory').innerHTML = data.ban_history.map(b => {
                    const d = new Date(b.created_at.replace(' ','T')+'Z');
                    const dateStr = d.toLocaleDateString('tr-TR') + ' ' + d.toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'});
                    const typeLabel = b.action_type === 'ban' ? '🔨 Ban' : '⏱️ Timeout';
                    return `<div class="ud-ban-item"><div><strong>${typeLabel}</strong> · ${esc(b.reason||'-')}</div><div style="color:#a1a1aa;font-size:11px">${dateStr}</div></div>`;
                }).join('');
            } else {
                document.getElementById('udBanHistory').innerHTML = '<div style="color:#a1a1aa;font-size:12px;text-align:center;padding:12px 0">Ceza geçmişi yok.</div>';
            }

            // Messages
            if (data.messages.length === 0) {
                document.getElementById('udMessages').innerHTML = '<div style="color:#a1a1aa;font-size:12px;text-align:center;padding:12px 0">Mesaj geçmişi yok.</div>';
            } else {
                document.getElementById('udMessages').innerHTML = data.messages.map(m => {
                    const t = new Date(m.created_at.replace(' ','T')+'Z').toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'});
                    return `<div class="ud-msg ud-msg-item"><span class="ud-msg-time">[${t}]</span>${esc(m.content)}</div>`;
                }).join('');
            }

            // Action buttons
            let actHtml = '';
            if (u.is_banned == 1 || isTimeout) {
                actHtml += `<button class="ud-action-btn" style="background:#10b981;color:white;" onclick="askCF('unban','${userId}','${esc(u.username)}')"><i class="fas fa-unlock"></i> Cezayı Kaldır</button>`;
            }
            if (u.is_banned != 1) {
                actHtml += `<button class="ud-action-btn" style="background:#fbbf24;color:#000;" onclick="askCF('timeout','${userId}','${esc(u.username)}')"><i class="fas fa-clock"></i> Timeout</button>`;
                actHtml += `<button class="ud-action-btn" style="background:#ef4444;color:white;" onclick="askCF('ban','${userId}','${esc(u.username)}')"><i class="fas fa-ban"></i> Banla</button>`;
            }
            document.getElementById('udActions').innerHTML = actHtml;

        } catch(e) { console.error(e); }
    }

    function closeUD() { document.getElementById('udModal').style.display = 'none'; if (udTimerInterval) clearInterval(udTimerInterval); }
    function filterUDMsgs() {
        const term = document.getElementById('udMsgSearch').value.toLowerCase();
        document.querySelectorAll('.ud-msg-item').forEach(m => { m.style.display = m.innerText.toLowerCase().includes(term) ? 'block' : 'none'; });
    }

    
    function askCF(action, userId, username) {
        cfAction = action; cfUserId = userId;
        const modal = document.getElementById('cfModal');
        const timeoutRow = document.getElementById('cfTimeoutRow');
        const btn = document.getElementById('cfConfirmBtn');
        if (action === 'ban') {
            document.getElementById('cfTitle').textContent = 'Kalıcı Ban';
            document.getElementById('cfDesc').textContent = `${username} kullanıcısını kalıcı olarak banlamak istediğinize emin misiniz?`;
            btn.style.background = '#ef4444'; btn.textContent = 'Evet, Banla';
            timeoutRow.style.display = 'none';
        } else if (action === 'timeout') {
            document.getElementById('cfTitle').textContent = 'Timeout At';
            document.getElementById('cfDesc').textContent = `${username} kullanıcısına timeout atmak istediğinize emin misiniz?`;
            btn.style.background = '#fbbf24'; btn.style.color = '#000'; btn.textContent = 'Evet, Timeout At';
            timeoutRow.style.display = 'flex';
        } else if (action === 'unban') {
            document.getElementById('cfTitle').textContent = 'Cezayı Kaldır';
            document.getElementById('cfDesc').textContent = `${username} kullanıcısının cezasını kaldırmak istediğinize emin misiniz?`;
            btn.style.background = '#10b981'; btn.style.color = 'white'; btn.textContent = 'Evet, Kaldır';
            timeoutRow.style.display = 'none';
        }
        modal.style.display = 'flex';
    }
    function closeCF() { document.getElementById('cfModal').style.display = 'none'; cfAction = null; cfUserId = null; }

    async function execCF() {
        if (!cfAction || !cfUserId) return;
        let url = '', body = {};
        if (cfAction === 'ban') {
            url = 'api/ban_user.php'; body = { user_id: cfUserId };
        } else if (cfAction === 'timeout') {
            const sel = document.getElementById('cfTimeoutDur');
            let dur = sel.value === 'custom' ? parseInt(document.getElementById('cfCustomDur').value) : parseInt(sel.value);
            if (!dur || dur < 1) { alert('Geçerli bir süre girin.'); return; }
            url = 'api/ban_user.php'; body = { user_id: cfUserId, duration: dur };
        } else if (cfAction === 'unban') {
            url = 'api/remove_timeout.php'; body = { user_id: cfUserId };
        }
        closeCF();
        try {
            const res = await fetch(url, { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
            const result = await res.json();
            if (result.status === 'success') { openUD(cfUserId); fetchFollowers(); }
            else alert('Hata: ' + (result.message||'Bilinmeyen'));
        } catch(e) { console.error(e); }
    }

    function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    // Init
    fetchFollowers();
    </script>
</body>
</html>
