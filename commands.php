<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bot Komutları - SebastianBot</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">

    <style>
        .commands-layout {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .cmd-form-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .cmd-form-card h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cmd-form-card h3 i {
            color: var(--primary-color);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 16px;
            align-items: start;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            background: #0f1318;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 10px 14px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(83, 252, 24, 0.15);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: flex;
            gap: 16px;
            align-items: end;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--primary-color);
            color: #000;
        }

        .btn-primary:hover {
            background: #45d414;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(83, 252, 24, 0.3);
        }

        .btn-cancel {
            background: #3f3f46;
            color: white;
        }

        .btn-cancel:hover {
            background: #52525b;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: flex-end;
        }

        .keyword-hint {
            background: rgba(83, 252, 24, 0.08);
            border: 1px solid rgba(83, 252, 24, 0.2);
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 12px;
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .keyword-hint code {
            background: rgba(83, 252, 24, 0.15);
            color: var(--primary-color);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .cmd-list-card {
            background-color: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .cmd-list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .cmd-list-header h3 {
            font-size: 18px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cmd-list-header h3 i {
            color: #3b82f6;
        }

        .cmd-search {
            background: #0f1318;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            padding: 8px 14px;
            font-size: 13px;
            outline: none;
            width: 220px;
            transition: border-color 0.2s;
        }

        .cmd-search:focus {
            border-color: var(--primary-color);
        }

        .cmd-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 6px;
        }

        .cmd-table thead th {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            text-align: left;
            padding: 8px 14px;
        }

        .cmd-table tbody tr {
            background: #151a21;
            transition: background 0.2s;
        }

        .cmd-table tbody tr:hover {
            background: rgba(83, 252, 24, 0.04);
        }

        .cmd-table tbody tr.disabled-row {
            opacity: 0.45;
        }

        .cmd-table tbody td {
            padding: 12px 14px;
            font-size: 13px;
            vertical-align: middle;
        }

        .cmd-table tbody td:first-child {
            border-radius: 8px 0 0 8px;
        }

        .cmd-table tbody td:last-child {
            border-radius: 0 8px 8px 0;
        }

        .cmd-name {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 14px;
            font-family: 'Courier New', monospace;
        }

        .cmd-response {
            color: #e2e8f0;
            max-width: 320px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cmd-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .cmd-badge.chat {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }

        .cmd-badge.timer {
            background: rgba(234, 179, 8, 0.15);
            color: #fbbf24;
        }

        .cmd-badge.active {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }

        .cmd-badge.inactive {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .cmd-actions {
            display: flex;
            gap: 6px;
        }

        .cmd-action-btn {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            transition: all 0.2s;
        }

        .cmd-action-btn.edit {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }

        .cmd-action-btn.edit:hover {
            background: rgba(59, 130, 246, 0.3);
        }

        .cmd-action-btn.toggle {
            background: rgba(234, 179, 8, 0.15);
            color: #fbbf24;
        }

        .cmd-action-btn.toggle:hover {
            background: rgba(234, 179, 8, 0.3);
        }

        .cmd-action-btn.delete {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
        }

        .cmd-action-btn.delete:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Timer interval seçici */
        #timerIntervalGroup {
            transition: all 0.3s;
        }

        /* Responsive tablo */
        @media (max-width: 1200px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cmd-table tbody tr {
            animation: fadeIn 0.3s ease;
        }

        /* Onay Modalı */
        .cmd-modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }

        .cmd-modal-box {
            background: #1e1e2d;
            border-radius: 12px;
            padding: 25px;
            width: 380px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .cmd-modal-box h3 {
            color: white;
            margin: 0 0 12px 0;
            font-size: 18px;
        }

        .cmd-modal-box p {
            color: #a1a1aa;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .cmd-modal-actions {
            display: flex;
            gap: 10px;
        }

        .cmd-modal-actions button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-family: inherit;
            transition: opacity 0.2s;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Bot Komutları</div>
            <div class="user-profile">
                <div class="avatar">
                    <i class="fas fa-robot" style="color: var(--primary-color);"></i>
                </div>
                <span>SebastianBot</span>
            </div>
        </header>

        <div class="commands-layout">

            <div class="cmd-form-card" id="cmdFormCard">
                <h3 id="formTitle"><i class="fas fa-plus-circle"></i> Yeni Komut Oluştur</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Komut Adı</label>
                        <input type="text" id="cmdName" placeholder="!discord" />
                    </div>
                    <div class="form-group">
                        <label>Komut Türü</label>
                        <select id="cmdType" onchange="toggleTimerInterval()">
                            <option value="chat">💬 Sohbet Komutu (Kullanıcı tetikler)</option>
                            <option value="timer">⏱️ Zamanlayıcı (Otomatik gönderir)</option>
                        </select>
                    </div>
                    <div class="form-group" id="timerIntervalGroup" style="display:none;">
                        <label>Aralık (Dakika)</label>
                        <input type="number" id="cmdInterval" min="1" max="1440" placeholder="5" value="5" />
                    </div>
                    <div class="form-group">
                        <label>Cooldown (Saniye)</label>
                        <input type="number" id="cmdCooldown" min="0" max="3600" placeholder="60" value="60" />
                    </div>
                </div>

                <div class="form-group" style="margin-top:16px;">
                    <label>Yanıt Mesajı</label>
                    <textarea id="cmdResponse" placeholder="Discord sunucumuza katılın: https://discord.gg/ornek"></textarea>
                </div>

                <div class="keyword-hint">
                    <strong>Kullanılabilir Değişkenler:</strong><br>
                    <code>$(user)</code> → Komutu yazan kullanıcının adını mention eder (ör: @DenizOzceylan)<br>
                    <code>$(channel)</code> → Kanal adını yazar<br>
                    <code>$(count)</code> → Komutun kaç kez kullanıldığını yazar<br>
                    <code>$(score)</code> → Komutu yazan kullanıcının güncel sadakat skorunu yazar
                </div>

                <div class="form-actions">
                    <button class="btn btn-cancel" id="btnCancelEdit" style="display:none;" onclick="cancelEdit()">
                        <i class="fas fa-times"></i> İptal
                    </button>
                    <button class="btn btn-primary" id="btnSubmit" onclick="submitCommand()">
                        <i class="fas fa-plus"></i> Komut Oluştur
                    </button>
                </div>
            </div>

            <div class="cmd-list-card">
                <div class="cmd-list-header">
                    <h3><i class="fas fa-list"></i> Kayıtlı Komutlar</h3>
                    <input type="text" class="cmd-search" id="cmdSearch" placeholder="Komut ara..." oninput="filterCommands()">
                </div>

                <table class="cmd-table">
                    <thead>
                        <tr>
                            <th>Komut</th>
                            <th>Yanıt</th>
                            <th>Tür</th>
                            <th>Cooldown</th>
                            <th>Durum</th>
                            <th>Kullanım</th>
                            <th style="text-align:right;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody id="cmdTableBody">
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-terminal"></i>
                                    <p>Henüz komut oluşturulmadı.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="cmd-modal-overlay" id="cmdModal">
        <div class="cmd-modal-box">
            <h3 id="cmdModalTitle">Onay</h3>
            <p id="cmdModalDesc">Bu komutu silmek istediğinize emin misiniz?</p>
            <div class="cmd-modal-actions">
                <button onclick="closeCmdModal()" style="background:#3f3f46; color:white;">İptal</button>
                <button onclick="confirmCmdAction()" id="cmdModalConfirmBtn" style="background:#ef4444; color:white;">Sil</button>
            </div>
        </div>
    </div>

    <script>
        let allCommands = [];
        let editingId = null;
        let pendingAction = null;
        let pendingId = null;

        // Sayfa yüklendiğinde komutları çek
        fetchCommands();

        function toggleTimerInterval() {
            const type = document.getElementById('cmdType').value;
            document.getElementById('timerIntervalGroup').style.display = type === 'timer' ? 'flex' : 'none';
        }

        async function fetchCommands() {
            try {
                const response = await fetch('api/commands.php');
                const data = await response.json();
                if (data.status === 'success') {
                    allCommands = data.data;
                    renderCommands(allCommands);
                }
            } catch (err) {
                console.error('Komutlar çekilemedi:', err);
            }
        }

        function renderCommands(commands) {
            const tbody = document.getElementById('cmdTableBody');
            const searchTerm = document.getElementById('cmdSearch').value.toLowerCase();

            let filtered = commands;
            if (searchTerm) {
                filtered = commands.filter(c =>
                    c.command_name.toLowerCase().includes(searchTerm) ||
                    c.response.toLowerCase().includes(searchTerm)
                );
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr><td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-terminal"></i>
                            <p>Henüz komut oluşturulmadı.</p>
                        </div>
                    </td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(cmd => {
                const isActive = cmd.is_active == 1;
                const isTimer = cmd.command_type === 'timer';
                const rowClass = isActive ? '' : 'disabled-row';
                const typeBadge = isTimer
                    ? `<span class="cmd-badge timer"><i class="fas fa-clock"></i> ${cmd.timer_interval} dk</span>`
                    : `<span class="cmd-badge chat"><i class="fas fa-comment"></i> Sohbet</span>`;
                const statusBadge = isActive
                    ? `<span class="cmd-badge active"><i class="fas fa-check-circle"></i> Aktif</span>`
                    : `<span class="cmd-badge inactive"><i class="fas fa-pause-circle"></i> Devre Dışı</span>`;
                const toggleIcon = isActive ? 'fa-pause' : 'fa-play';
                const toggleTitle = isActive ? 'Devre Dışı Bırak' : 'Aktifleştir';

                return `
                    <tr class="${rowClass}">
                        <td><span class="cmd-name">${escapeHtml(cmd.command_name)}</span></td>
                        <td><span class="cmd-response" title="${escapeHtml(cmd.response)}">${escapeHtml(cmd.response)}</span></td>
                        <td>${typeBadge}</td>
                        <td style="color:var(--text-secondary); font-size:12px;">${cmd.cooldown || 60}s</td>
                        <td>${statusBadge}</td>
                        <td style="color:var(--text-secondary); font-weight:600;">${cmd.usage_count}</td>
                        <td>
                            <div class="cmd-actions" style="justify-content:flex-end;">
                                <button class="cmd-action-btn edit" title="Düzenle" onclick="startEdit(${cmd.id})"><i class="fas fa-pen"></i></button>
                                <button class="cmd-action-btn toggle" title="${toggleTitle}" onclick="toggleCommand(${cmd.id})"><i class="fas ${toggleIcon}"></i></button>
                                <button class="cmd-action-btn delete" title="Sil" onclick="askDeleteCommand(${cmd.id}, '${escapeHtml(cmd.command_name)}')"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterCommands() {
            renderCommands(allCommands);
        }

        async function submitCommand() {
            const name = document.getElementById('cmdName').value.trim();
            const response = document.getElementById('cmdResponse').value.trim();
            const type = document.getElementById('cmdType').value;
            const interval = document.getElementById('cmdInterval').value;
            const cooldown = document.getElementById('cmdCooldown').value;

            if (!name || !response) {
                alert('Komut adı ve yanıt mesajı zorunludur.');
                return;
            }

            const body = {
                action: editingId ? 'update' : 'create',
                command_name: name,
                response: response,
                command_type: type,
                timer_interval: type === 'timer' ? parseInt(interval) : null,
                cooldown: parseInt(cooldown) || 60
            };

            if (editingId) {
                body.id = editingId;
            }

            try {
                const res = await fetch('api/commands.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                const result = await res.json();

                if (result.status === 'success') {
                    clearForm();
                    fetchCommands();
                } else {
                    alert(result.message || 'Bir hata oluştu.');
                }
            } catch (err) {
                console.error('Komut kaydedilemedi:', err);
                alert('Sunucu hatası.');
            }
        }

        function startEdit(id) {
            const cmd = allCommands.find(c => c.id == id);
            if (!cmd) return;

            editingId = id;
            document.getElementById('cmdName').value = cmd.command_name;
            document.getElementById('cmdName').disabled = true;
            document.getElementById('cmdResponse').value = cmd.response;
            document.getElementById('cmdType').value = cmd.command_type;
            document.getElementById('cmdCooldown').value = cmd.cooldown || 60;
            toggleTimerInterval();
            if (cmd.timer_interval) {
                document.getElementById('cmdInterval').value = cmd.timer_interval;
            }

            document.getElementById('formTitle').innerHTML = '<i class="fas fa-pen"></i> Komut Düzenle';
            document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-save"></i> Kaydet';
            document.getElementById('btnCancelEdit').style.display = 'inline-flex';

            // Forma scroll et
            document.getElementById('cmdFormCard').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelEdit() {
            clearForm();
        }

        function clearForm() {
            editingId = null;
            document.getElementById('cmdName').value = '';
            document.getElementById('cmdName').disabled = false;
            document.getElementById('cmdResponse').value = '';
            document.getElementById('cmdType').value = 'chat';
            document.getElementById('cmdInterval').value = '5';
            document.getElementById('cmdCooldown').value = '60';
            toggleTimerInterval();
            document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Yeni Komut Oluştur';
            document.getElementById('btnSubmit').innerHTML = '<i class="fas fa-plus"></i> Komut Oluştur';
            document.getElementById('btnCancelEdit').style.display = 'none';
        }

        async function toggleCommand(id) {
            try {
                const res = await fetch('api/commands.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'toggle', id: id })
                });
                const result = await res.json();
                if (result.status === 'success') {
                    fetchCommands();
                }
            } catch (err) {
                console.error('Toggle hatası:', err);
            }
        }

        function askDeleteCommand(id, name) {
            pendingAction = 'delete';
            pendingId = id;
            document.getElementById('cmdModalTitle').innerText = 'Komutu Sil';
            document.getElementById('cmdModalDesc').innerText = `"${name}" komutunu kalıcı olarak silmek istediğinize emin misiniz?`;
            document.getElementById('cmdModalConfirmBtn').style.background = '#ef4444';
            document.getElementById('cmdModalConfirmBtn').innerText = 'Evet, Sil';
            document.getElementById('cmdModal').style.display = 'flex';
        }

        function closeCmdModal() {
            document.getElementById('cmdModal').style.display = 'none';
            pendingAction = null;
            pendingId = null;
        }

        async function confirmCmdAction() {
            if (pendingAction === 'delete' && pendingId) {
                try {
                    const res = await fetch('api/commands.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'delete', id: pendingId })
                    });
                    const result = await res.json();
                    if (result.status === 'success') {
                        fetchCommands();
                    }
                } catch (err) {
                    console.error('Silme hatası:', err);
                }
            }
            closeCmdModal();
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }
    </script>
</body>

</html>
