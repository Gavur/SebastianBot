<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Paylaşılan Banlar - SebastianBot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .sb-layout {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .sb-section-title {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sb-stats-row {
            display: flex;
            gap: 16px;
            margin-bottom: 8px;
        }

        .sb-stat-box {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sb-stat-box .title {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sb-stat-box .value {
            font-size: 24px;
            font-weight: 800;
            color: white;
            margin-top: 4px;
        }

        .sb-stat-box .icon {
            font-size: 28px;
            color: var(--primary-color);
            opacity: 0.8;
        }

        /* Channels Row */
        .sb-channels-container {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .sb-channel-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 160px;
            max-width: 220px;
            flex-direction: column;
            align-items: flex-start;
            flex-shrink: 0;
        }

        .sb-channel-card .top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 12px;
        }

        .sb-channel-card .icon {
            color: var(--primary-color);
            font-size: 18px;
            flex-shrink: 0;
        }

        .sb-channel-card .name {
            font-weight: 600;
            font-size: 14px;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sb-channel-card .actions {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .sb-channel-card .action-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px;
            opacity: 0.7;
            transition: opacity .2s;
        }

        .sb-channel-card .action-btn:hover {
            opacity: 1;
        }

        .sb-channel-card .reasons-badges {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            margin-top: 8px;
            width: 100%;
            overflow: hidden;
        }

        .sb-channel-card .reason-badge {
            font-size: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
            color: #a1a1aa;
            white-space: nowrap;
        }

        .sb-add-channel-btn {
            background: rgba(83, 252, 24, 0.1);
            border: 1px dashed var(--primary-color);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--primary-color);
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .2s;
            min-width: 140px;
            flex-shrink: 0;
            text-align: center;
        }

        .sb-add-channel-btn:hover {
            background: rgba(83, 252, 24, 0.2);
        }

        /* Bans Table */
        .sb-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .sb-toolbar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }

        .sb-search {
            background: #0f1318;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            outline: none;
            width: 220px;
            transition: border-color .2s;
        }

        .sb-search:focus {
            border-color: var(--primary-color);
        }

        .sb-select {
            background: #0f1318;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            outline: none;
        }

        .sb-btn {
            background: var(--primary-color);
            color: #000;
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            transition: opacity .2s;
        }

        .sb-btn:hover {
            opacity: 0.9;
        }

        .sb-btn-danger {
            background: #ef4444;
            color: white;
        }

        .sb-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 4px;
        }

        .sb-table thead th {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: .5px;
            font-weight: 600;
            text-align: left;
            padding: 8px 14px;
            cursor: pointer;
            user-select: none;
        }

        .sb-table thead th:hover {
            color: white;
        }

        .sb-table tbody tr {
            background: #151a21;
            transition: background .2s;
        }

        .sb-table tbody tr:hover {
            background: rgba(83, 252, 24, 0.04);
        }

        .sb-table tbody td {
            padding: 12px 14px;
            font-size: 13px;
            vertical-align: middle;
        }

        .sb-table tbody td:first-child {
            border-radius: 8px 0 0 8px;
        }

        .sb-table tbody td:last-child {
            border-radius: 0 8px 8px 0;
            text-align: right;
        }

        .sb-empty {
            text-align: center;
            padding: 40px;
            color: var(--text-secondary);
        }

        .sb-empty i {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: .3;
            display: block;
        }

        .sb-badge {
            background: #27273a;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #a1a1aa;
            border: 1px solid #3f3f46;
        }

        .sb-badge.evidence {
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            border-color: #3b82f6;
            cursor: pointer;
        }

        /* Modals */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }

        .modal-box {
            background: #1e1e2d;
            padding: 24px;
            border-radius: 12px;
            width: 560px;
            max-height: 90vh;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
        }

        .modal-box.sm {
            width: 400px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #3f3f46;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .modal-header h3 {
            margin: 0;
            color: white;
            font-size: 18px;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: #a1a1aa;
            cursor: pointer;
            font-size: 16px;
        }

        .modal-close:hover {
            color: white;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            color: #a1a1aa;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            background: #27273a;
            border: 1px solid #3f3f46;
            border-radius: 8px;
            color: white;
            padding: 10px;
            font-size: 14px;
            outline: none;
            font-family: inherit;
        }

        .form-input:focus {
            border-color: var(--primary-color);
        }

        .form-textarea {
            width: 100%;
            background: #27273a;
            border: 1px solid #3f3f46;
            border-radius: 8px;
            color: white;
            padding: 10px;
            font-size: 13px;
            outline: none;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }

        .form-textarea:focus {
            border-color: var(--primary-color);
        }

        .reason-opts {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }

        .reason-opt {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #d4d4d8;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all .2s;
        }

        .reason-opt:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .reason-opt.active {
            background: var(--primary-color);
            color: #000;
            border-color: var(--primary-color);
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #d4d4d8;
            cursor: pointer;
        }

        .msg-list {
            background: #0f1318;
            border: 1px solid #3f3f46;
            border-radius: 8px;
            max-height: 200px;
            overflow-y: auto;
            padding: 8px;
            margin-top: 8px;
        }

        .msg-item {
            display: flex;
            gap: 8px;
            padding: 6px;
            border-radius: 6px;
            transition: background .2s;
        }

        .msg-item:hover {
            background: #27273a;
        }

        .msg-item input {
            margin-top: 4px;
            cursor: pointer;
        }

        .msg-item-content {
            flex: 1;
            font-size: 12px;
            color: #d4d4d8;
        }

        .msg-item-time {
            color: #71717a;
            font-size: 10px;
            margin-right: 6px;
        }

        .btn-row {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
        }

        .btn-cancel {
            background: #3f3f46;
            color: white;
        }

        .btn-save {
            background: var(--primary-color);
            color: #000;
        }

        .fetch-row {
            display: flex;
            gap: 8px;
        }

        .fetch-row .form-input {
            flex: 1;
        }

        .fetch-btn {
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .ev-view-msg {
            background: #27272a;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 11px;
            margin-top: 4px;
            border-left: 2px solid #ef4444;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 16px;
        }

        .page-btn {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 12px;
            transition: all .2s;
        }

        .page-btn.active {
            background: var(--primary-color);
            color: #000;
            border-color: var(--primary-color);
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Paylaşılan Banlar (Ortak Ban Havuzu)</div>
            <div class="user-profile">
                <div class="avatar"><i class="fas fa-robot" style="color:var(--primary-color)"></i></div>
                <span>SebastianBot</span>
            </div>
        </header>
        <div class="sb-layout">

            <div class="sb-stats-row">
                <div class="sb-stat-box">
                    <div>
                        <div class="title">Ortak Banlanan Kullanıcı</div>
                        <div class="value" id="statTotalBans">0</div>
                    </div>
                    <i class="fas fa-gavel icon"></i>
                </div>
                <div class="sb-stat-box" style="flex: 2;">
                    <div style="width:100%">
                        <div class="title" style="margin-bottom:8px;">Sebep İstatistikleri</div>
                        <div id="statReasons"
                            style="display:flex; gap:12px; flex-wrap:wrap; font-size:12px; color:#d4d4d8;">Yükleniyor...
                        </div>
                    </div>
                </div>
                <div class="sb-stat-box">
                    <div>
                        <div class="title">Ağa Dahil Kanal Sayısı</div>
                        <div class="value" id="statTotalChannels">0</div>
                    </div>
                    <i class="fas fa-network-wired icon"></i>
                </div>
            </div>

            <!-- Channels Row -->
            <div>
                <div class="sb-section-title">
                    <span><i class="fas fa-network-wired"></i> Ortak Kanallar</span>
                    <input type="text" class="sb-search" id="channelSearch" placeholder="Kanal ara..."
                        oninput="filterChannels()"
                        style="width: 200px; padding: 6px 10px; font-size: 12px; height: 32px;">
                </div>
                <div style="font-size: 12px; color: var(--text-secondary); margin-bottom: 12px; background: rgba(0,0,0,0.2); padding: 8px 12px; border-radius: 8px;"
                    id="channelStats">
                </div>
                <div class="sb-channels-container" id="channelsList">

                </div>
            </div>

            <div class="sb-card">
                <div class="sb-section-title"><span><i class="fas fa-gavel"></i> Ortak Ban Listesi</span></div>
                <div class="sb-toolbar">
                    <input type="text" class="sb-search" id="banSearch" placeholder="Kullanıcı veya kanal ara..."
                        oninput="debounceSearch()">

                    <select class="sb-select" id="filterChannel" onchange="applyFilters()">
                        <option value="">Tüm Kanallar</option>
                    </select>

                    <select class="sb-select" id="filterMod" onchange="applyFilters()">
                        <option value="">Tüm Moderatörler</option>
                    </select>

                    <select class="sb-select" id="filterEv" onchange="applyFilters()">
                        <option value="">Delil Durumu</option>
                        <option value="yes">Delilli</option>
                        <option value="no">Delilsiz</option>
                    </select>

                    <button class="sb-btn" onclick="openBanModal()" style="margin-left:auto"><i class="fas fa-plus"></i>
                        Yeni Ekle</button>
                    <input type="file" id="importJsonFile" accept=".json" style="display:none;"
                        onchange="handleFileImport(event)">
                    <button class="sb-btn" onclick="document.getElementById('importJsonFile').click()"><i
                            class="fas fa-file-import"></i> Toplu İçe Aktar (JSON)</button>
                    <div style="position: relative; display: inline-block;">
                        <button class="sb-btn" onclick="toggleExportMenu()"><i class="fas fa-download"></i> Dışa
                            Aktar</button>
                        <div id="exportMenu"
                            style="display:none; position:absolute; top:100%; right:0; background:#1e1e2d; border:1px solid #3f3f46; border-radius:8px; padding:8px; z-index:100; min-width:120px; margin-top:4px;">
                            <div style="padding:6px 10px; cursor:pointer; color:#d4d4d8; font-size:12px; border-radius:4px;"
                                onmouseover="this.style.background='#27273a'"
                                onmouseout="this.style.background='transparent'" onclick="exportBans('csv')">CSV Aktar
                            </div>
                            <div style="padding:6px 10px; cursor:pointer; color:#d4d4d8; font-size:12px; border-radius:4px;"
                                onmouseover="this.style.background='#27273a'"
                                onmouseout="this.style.background='transparent'" onclick="exportBans('json')">JSON Aktar
                            </div>
                            <div style="padding:6px 10px; cursor:pointer; color:#d4d4d8; font-size:12px; border-radius:4px;"
                                onmouseover="this.style.background='#27273a'"
                                onmouseout="this.style.background='transparent'" onclick="exportBans('txt')">TXT Aktar
                            </div>
                        </div>
                    </div>
                    <button class="sb-btn sb-btn-danger" id="bulkDeleteBtn" onclick="bulkDelete()"
                        style="display:none;"><i class="fas fa-trash"></i> Seçilileri Sil</button>
                </div>
                <table class="sb-table">
                    <thead>
                        <tr>
                            <th style="width:30px;"><input type="checkbox" id="selectAllBans"
                                    onchange="toggleSelectAll(this)"></th>
                            <th onclick="setSort('username')">Kullanıcı <i class="fas fa-sort"></i></th>
                            <th onclick="setSort('reason')">Sebep <i class="fas fa-sort"></i></th>
                            <th onclick="setSort('original_channel')">Kanal <i class="fas fa-sort"></i></th>
                            <th onclick="setSort('moderator_name')">Moderatör <i class="fas fa-sort"></i></th>
                            <th onclick="setSort('created_at')">Tarih <i class="fas fa-sort"></i></th>
                            <th>Delil</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="bansBody">
                        <tr>
                            <td colspan="8">
                                <div class="sb-empty"><i class="fas fa-globe"></i>
                                    <p>Yükleniyor...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination" id="banPagination"></div>
            </div>

        </div>
    </div>

    <div class="modal-overlay" id="channelModal">
        <div class="modal-box sm">
            <div class="modal-header">
                <h3 id="chModalTitle">Kanal Ekle</h3>
                <button class="modal-close" onclick="closeChannelModal()"><i class="fas fa-times"></i></button>
            </div>
            <input type="hidden" id="chIdInput">
            <div class="form-group">
                <label>Kanal Adı</label>
                <input type="text" id="chNameInput" class="form-input" placeholder="Örn: pqueen">
            </div>
            <div class="form-group">
                <label>Kabul Edilen Ban Sebepleri</label>
                <div style="font-size:11px; color:#a1a1aa; margin-bottom:8px;">Bu kanal ortak havuzdan hangi sebeplerle
                    banlananları kendi sisteminde banlasın?</div>
                <div class="checkbox-group" id="channelReasonsList">

                </div>
            </div>
            <div class="btn-row">
                <button class="btn btn-cancel" onclick="closeChannelModal()">İptal</button>
                <button class="btn btn-save" onclick="saveChannel()">Kaydet</button>
            </div>
        </div>
    </div>


    <div class="modal-overlay" id="banModal">
        <div class="modal-box" style="overflow-y:auto">
            <div class="modal-header">
                <h3 id="banModalTitle">Ortak Ban Ekle</h3>
                <button class="modal-close" onclick="closeBanModal()"><i class="fas fa-times"></i></button>
            </div>

            <input type="hidden" id="banIdInput">

            <div class="form-group" id="fetchGroup">
                <label>Kullanıcı Adı (Sorgula)</label>
                <div class="fetch-row">
                    <input type="text" id="banUsername" class="form-input" placeholder="Banlanacak kullanıcı adı">
                    <button class="fetch-btn" onclick="fetchUserInfo()"><i class="fas fa-search"></i> Bul</button>
                </div>
                <div id="banUserSummary" style="font-size:11px; color:#a1a1aa; margin-top:6px; display:none;"></div>
            </div>

            <div class="form-group" id="editUserGroup" style="display:none;">
                <label>Kullanıcı Adı</label>
                <input type="text" id="banUsernameEdit" class="form-input" disabled>
            </div>

            <div class="form-group">
                <label>Olayın Yaşandığı Kanal</label>
                <select id="banOriginalChannel" class="form-input">
                    <option value="">Seçiniz...</option>
                </select>
            </div>

            <div class="form-group">
                <label>Ban Sebebi</label>
                <div class="reason-opts">
                    <div class="reason-opt" onclick="setPredefinedReason('Nefret söylemi')">Nefret söylemi</div>
                    <div class="reason-opt" onclick="setPredefinedReason('Hakaret')">Hakaret</div>
                    <div class="reason-opt" onclick="setPredefinedReason('Spam')">Spam</div>
                    <div class="reason-opt" onclick="setPredefinedReason('Dolandırıcılık')">Dolandırıcılık</div>
                    <div class="reason-opt" onclick="setPredefinedReason('Tehdit')">Tehdit</div>
                </div>
                <textarea id="banReason" class="form-textarea"
                    placeholder="Neden banlandı? (Özel sebep yazabilirsiniz)"></textarea>
            </div>

            <div class="form-group">
                <label>Moderatör Adı</label>
                <input type="text" id="banModName" class="form-input" placeholder="İşlemi yapan moderatör">
            </div>

            <div class="form-group" id="evidenceGroup" style="display:none;">
                <label>Delil Mesajlar (İsteğe Bağlı)</label>
                <div style="font-size:11px; color:#a1a1aa; margin-bottom:4px;">Kullanıcının son 10 mesajı. Kanıt olarak
                    eklemek istediklerinizi seçin.</div>
                <div class="msg-list" id="evidenceList"></div>
            </div>

            <div class="btn-row">
                <button class="btn btn-cancel" onclick="closeBanModal()">İptal</button>
                <button class="btn btn-save" onclick="saveBan()">Kaydet</button>
            </div>
        </div>
    </div>


    <div class="modal-overlay" id="viewBanModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3 id="vbName">Detaylar</h3>
                <button class="modal-close" onclick="closeViewBanModal()"><i class="fas fa-times"></i></button>
            </div>
            <div style="font-size:13px; color:#d4d4d8; margin-bottom:12px;">
                <strong style="color:var(--primary-color)">Sebep:</strong> <span id="vbReason"></span><br><br>
                <strong style="color:var(--primary-color)">Kanal:</strong> <span class="sb-badge"
                    id="vbChannel"></span><br><br>
                <strong style="color:var(--primary-color)">Moderatör:</strong> <span id="vbMod"></span><br><br>
                <strong style="color:var(--primary-color)">Tarih:</strong> <span id="vbDate"></span>
            </div>
            <div id="vbEvidenceContainer" style="display:none; margin-top:16px;">
                <strong style="color:var(--primary-color); font-size:13px;">Delil Mesajlar:</strong>
                <div id="vbEvidenceList" style="margin-top:8px; max-height:200px; overflow-y:auto; padding-right:4px;">
                </div>
            </div>
        </div>
    </div>


    <div class="modal-overlay" id="importModal">
        <div class="modal-box" style="width: 800px;">
            <div class="modal-header">
                <h3>İçe Aktarımı Onayla</h3>
                <button class="modal-close" onclick="closeImportModal()"><i class="fas fa-times"></i></button>
            </div>
            <div style="font-size:13px; color:#d4d4d8; margin-bottom:12px;" id="importSummary"></div>
            <div
                style="max-height: 400px; overflow-y: auto; background: #0f1318; border: 1px solid #3f3f46; border-radius: 8px;">
                <table class="sb-table" style="margin: 0;">
                    <thead style="position: sticky; top: 0; background: #0f1318; z-index: 10;">
                        <tr>
                            <th>Kullanıcı</th>
                            <th>Sebep</th>
                            <th>Kanal</th>
                            <th>Moderatör</th>
                            <th>Delil</th>
                        </tr>
                    </thead>
                    <tbody id="importTableBody"></tbody>
                </table>
            </div>
            <div class="btn-row">
                <button class="btn btn-cancel" onclick="closeImportModal()">İptal Et</button>
                <button class="btn btn-save" onclick="confirmImport()">Onayla ve Aktar</button>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1, searchTimer = null;
        let sortBy = 'created_at', sortDir = 'DESC';
        let allChannels = [];
        let currentBans = [];

        // Predefined reasons
        const predefinedReasons = ['Nefret söylemi', 'Hakaret', 'Spam', 'Dolandırıcılık', 'Tehdit'];

        function setPredefinedReason(reason) {
            document.getElementById('banReason').value = reason;
        }

        // Channels
        async function fetchChannels() {
            try {
                const res = await fetch('api/shared_channels.php');
                const data = await res.json();
                if (data.status === 'success') {
                    allChannels = data.data;
                    document.getElementById('statTotalChannels').textContent = allChannels.length;
                    renderChannels(data.data);
                    populateChannelSelect(data.data);
                }
            } catch (e) { console.error(e); }
        }

        function renderChannels(channels) {
            // Stats calculation
            let reasonStatsMap = { 'Tümü': {total:0, withEv:0, noEv:0} };
            channels.forEach(c => {
                if (!c.accepted_reasons) { reasonStatsMap['Tümü'].total++; reasonStatsMap['Tümü'].noEv++; return; }
                try {
                    const parsed = JSON.parse(c.accepted_reasons);
                    let hasReasons = false;
                    if (Array.isArray(parsed) && parsed.length > 0) {
                        parsed.forEach(r => {
                            if (!reasonStatsMap[r]) reasonStatsMap[r] = {total:0, withEv:0, noEv:0};
                            reasonStatsMap[r].total++;
                            reasonStatsMap[r].noEv++;
                        });
                        hasReasons = true;
                    } else if (typeof parsed === 'object' && parsed !== null && Object.keys(parsed).length > 0) {
                        Object.keys(parsed).forEach(r => {
                            if (!reasonStatsMap[r]) reasonStatsMap[r] = {total:0, withEv:0, noEv:0};
                            reasonStatsMap[r].total++;
                            if (parsed[r].requires_evidence) {
                                reasonStatsMap[r].withEv++;
                            } else {
                                reasonStatsMap[r].noEv++;
                            }
                        });
                        hasReasons = true;
                    }
                    if (!hasReasons) { reasonStatsMap['Tümü'].total++; reasonStatsMap['Tümü'].noEv++; }
                } catch (e) { reasonStatsMap['Tümü'].total++; reasonStatsMap['Tümü'].noEv++; }
            });

            let statsHtml = Object.entries(reasonStatsMap)
                .filter(([k, v]) => v.total > 0)
                .map(([k, v]) => {
                    let evTxtArr = [];
                    if (v.withEv > 0) evTxtArr.push(`${v.withEv} delilli`);
                    if (v.noEv > 0) evTxtArr.push(`${v.noEv} delilsiz`);
                    let evTxt = evTxtArr.length > 0 ? ` <span style="font-size:10px; opacity:0.8;">(${evTxtArr.join(', ')})</span>` : '';
                    return `<span style="margin-right:16px; display:inline-block;"><strong style="color:var(--primary-color)">${esc(k)}:</strong> ${v.total} kanal${evTxt}</span>`;
                })
                .join('');
            document.getElementById('channelStats').innerHTML = statsHtml;

            const s = document.getElementById('channelSearch').value.trim().toLowerCase();
            const filtered = channels.filter(c => c.channel_name.toLowerCase().includes(s));

            const cont = document.getElementById('channelsList');
            let html = '';

            html += `<div class="sb-add-channel-btn" onclick="openChannelModal()"><i class="fas fa-plus"></i><br>Kanal Ekle</div>`;

            filtered.forEach(c => {
                let reasonsHtml = '';
                if (c.accepted_reasons) {
                    try {
                        const parsed = JSON.parse(c.accepted_reasons);
                        if (Array.isArray(parsed) && parsed.length > 0) {
                            reasonsHtml = `<div style="display:flex; flex-direction:column; gap:2px; font-size:11px; color:#a1a1aa; margin-top:12px; width:100%;">` +
                                parsed.map(r => `<div>- ${esc(r)}</div>`).join('') +
                                `</div>`;
                        } else if (typeof parsed === 'object' && parsed !== null && Object.keys(parsed).length > 0) {
                            reasonsHtml = `<div style="display:flex; flex-direction:column; gap:2px; font-size:11px; color:#a1a1aa; margin-top:12px; width:100%;">` +
                                Object.keys(parsed).map(r => {
                                    const o = parsed[r];
                                    const evReq = o.requires_evidence ? ` <span style="color:#60a5fa; font-size:9px;">(Delil sınırı: ${o.min_evidence})</span>` : '';
                                    return `<div>- ${esc(r)}${evReq}</div>`;
                                }).join('') +
                                `</div>`;
                        } else {
                            reasonsHtml = `<div style="display:flex; flex-direction:column; gap:2px; font-size:11px; color:#a1a1aa; margin-top:12px; width:100%;"><div>- Tüm Sebepler</div></div>`;
                        }
                    } catch (e) {
                        reasonsHtml = `<div style="display:flex; flex-direction:column; gap:2px; font-size:11px; color:#a1a1aa; margin-top:12px; width:100%;"><div>- Tüm Sebepler</div></div>`;
                    }
                } else {
                    reasonsHtml = `<div style="display:flex; flex-direction:column; gap:2px; font-size:11px; color:#a1a1aa; margin-top:12px; width:100%;"><div>- Tüm Sebepler</div></div>`;
                }

                html += `<div class="sb-channel-card" style="align-items:flex-start; min-height: 100%;">
            <div class="top-row">
                <i class="fas fa-tv icon"></i>
                <span class="name" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#fff;" title="${esc(c.channel_name)}">${esc(c.channel_name)}</span>
                <div class="actions">
                    <button class="action-btn" onclick="openEditChannelModal(${c.id})" style="color:#60a5fa"><i class="fas fa-edit"></i></button>
                    <button class="action-btn" onclick="deleteChannel(${c.id}, '${esc(c.channel_name)}')" style="color:#ef4444"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            ${reasonsHtml}
            <div style="margin-top:auto; width:100%; padding-top:16px;">
                <a href="https://kick.com/${esc(c.channel_name)}" target="_blank" style="display:block; text-align:center; background:rgba(83, 252, 24, 0.1); color:var(--primary-color); padding:6px; border-radius:6px; font-size:11px; text-decoration:none; font-weight:600; transition:background 0.2s;" onmouseover="this.style.background='rgba(83, 252, 24, 0.2)'" onmouseout="this.style.background='rgba(83, 252, 24, 0.1)'"><i class="fas fa-external-link-alt"></i> Kanala Git</a>
            </div>
        </div>`;
            });
            cont.innerHTML = html;
        }

        function filterChannels() {
            renderChannels(allChannels);
        }

        function renderChannelModalReasons(existingData = {}) {
            const cont = document.getElementById('channelReasonsList');
            let h = '';
            let isArray = Array.isArray(existingData);
            const allOpts = [...predefinedReasons, 'Diğer (Özel)'];

            allOpts.forEach((r, idx) => {
                let isChecked = false;
                let reqEv = false;
                let minEv = 1;

                if (isArray) {
                    isChecked = existingData.includes(r);
                } else if (existingData[r]) {
                    isChecked = true;
                    reqEv = existingData[r].requires_evidence || false;
                    minEv = existingData[r].min_evidence || 1;
                }

                h += `
                <div class="ch-reason-row" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 8px; border-radius: 6px; margin-bottom: 4px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <label class="checkbox-label" style="margin:0; font-weight:600;">
                            <input type="checkbox" class="ch-reason-cb" value="${esc(r)}" ${isChecked ? 'checked' : ''} onchange="toggleEvOptions(${idx})"> ${esc(r)}
                        </label>
                        <div id="ev_opts_${idx}" style="display:${isChecked ? 'flex' : 'none'}; align-items:center; gap:8px; font-size:11px;">
                            <label style="color:#a1a1aa; margin:0; cursor:pointer;"><input type="checkbox" class="ch-req-ev-cb" ${reqEv ? 'checked' : ''}> Delil Zorunlu</label>
                            <div style="display:flex; align-items:center; gap:4px;">
                                Min: <input type="number" class="ch-min-ev-input form-input" value="${minEv}" min="1" style="width:50px; padding:2px 6px; font-size:11px; height:24px;">
                            </div>
                        </div>
                    </div>
                </div>`;
            });
            cont.innerHTML = h;
        }

        function toggleEvOptions(idx) {
            const row = document.getElementById('ev_opts_' + idx);
            const cb = row.parentElement.querySelector('.ch-reason-cb');
            row.style.display = cb.checked ? 'flex' : 'none';
        }

        function openChannelModal() {
            document.getElementById('chModalTitle').textContent = 'Kanal Ekle';
            document.getElementById('chIdInput').value = '';
            document.getElementById('chNameInput').value = '';
            renderChannelModalReasons({});
            document.getElementById('channelModal').style.display = 'flex';
        }

        function openEditChannelModal(id) {
            const channel = allChannels.find(c => c.id == id);
            if (!channel) return;

            document.getElementById('chModalTitle').textContent = 'Kanal Düzenle';
            document.getElementById('chIdInput').value = channel.id;
            document.getElementById('chNameInput').value = channel.channel_name;

            let existing = {};
            if (channel.accepted_reasons) {
                try {
                    existing = JSON.parse(channel.accepted_reasons);
                } catch (e) { }
            }
            renderChannelModalReasons(existing);
            document.getElementById('channelModal').style.display = 'flex';
        }

        function closeChannelModal() { document.getElementById('channelModal').style.display = 'none'; }

        async function saveChannel() {
            const id = document.getElementById('chIdInput').value;
            const name = document.getElementById('chNameInput').value.trim();
            if (!name) return;

            const payloadReasons = {};
            document.querySelectorAll('.ch-reason-row').forEach(row => {
                const cb = row.querySelector('.ch-reason-cb');
                if (cb && cb.checked) {
                    const rName = cb.value;
                    const reqEv = row.querySelector('.ch-req-ev-cb').checked;
                    const minEv = parseInt(row.querySelector('.ch-min-ev-input').value) || 1;
                    payloadReasons[rName] = { requires_evidence: reqEv, min_evidence: minEv };
                }
            });

            const action = id ? 'edit' : 'add';
            const payload = { action, channel_name: name, accepted_reasons: payloadReasons };
            if (id) payload.id = parseInt(id);

            try {
                const res = await fetch('api/shared_channels.php', { method: 'POST', body: JSON.stringify(payload), headers: { 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.status === 'success') { closeChannelModal(); fetchChannels(); } else alert(data.message);
            } catch (e) { console.error(e); }
        }

        async function deleteChannel(id, name) {
            if (!confirm(`'${name}' kanalını silmek istediğinize emin misiniz?`)) return;
            try {
                const res = await fetch('api/shared_channels.php', { method: 'POST', body: JSON.stringify({ action: 'delete', id: id }), headers: { 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.status === 'success') fetchChannels(); else alert(data.message);
            } catch (e) { console.error(e); }
        }

        function populateChannelSelect(channels) {
            const sel1 = document.getElementById('banOriginalChannel');
            sel1.innerHTML = '<option value="">Seçiniz...</option>' + channels.map(c => `<option value="${esc(c.channel_name)}">${esc(c.channel_name)}</option>`).join('');
        }


        // Bans
        function debounceSearch() { clearTimeout(searchTimer); searchTimer = setTimeout(() => { currentPage = 1; fetchBans(); }, 300); }
        function applyFilters() { currentPage = 1; fetchBans(); }
        function setSort(col) {
            if (sortBy === col) { sortDir = sortDir === 'ASC' ? 'DESC' : 'ASC'; }
            else { sortBy = col; sortDir = 'DESC'; }
            fetchBans();
        }

        async function fetchBans() {
            const s = document.getElementById('banSearch').value.trim();
            const fCh = document.getElementById('filterChannel').value;
            const fMod = document.getElementById('filterMod').value;
            const fEv = document.getElementById('filterEv').value;

            const url = `api/shared_bans.php?page=${currentPage}&search=${encodeURIComponent(s)}&channel=${encodeURIComponent(fCh)}&mod=${encodeURIComponent(fMod)}&evidence=${fEv}&sort=${sortBy}&dir=${sortDir}`;

            try {
                const res = await fetch(url);
                const data = await res.json();
                if (data.status === 'success') {
                    document.getElementById('statTotalBans').textContent = data.total;

                    // Reason stats
                    let rHtml = '';
                    if (data.reasonStats && data.reasonStats.length > 0) {
                        const total = data.reasonStats.reduce((sum, r) => sum + parseInt(r.count), 0);
                        data.reasonStats.forEach(r => {
                            const pct = Math.round((r.count / total) * 100);
                            rHtml += `<div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); padding:4px 8px; border-radius:6px; flex-grow:1; text-align:center;">
                        <div style="font-weight:600; color:#fff; font-size:14px;">${r.count}</div>
                        <div style="opacity:0.7">${esc(r.reason)} (%${pct})</div>
                    </div>`;
                        });
                    } else {
                        rHtml = 'Veri yok';
                    }
                    document.getElementById('statReasons').innerHTML = rHtml;

                    renderBans(data.data);
                    renderPagination(data.page, data.total_pages);
                    updateFilterDropdowns(data.filterChannels, data.filterMods);
                    updateBulkBtn();
                }
            } catch (e) { console.error(e); }
        }

        function toggleExportMenu() {
            const m = document.getElementById('exportMenu');
            m.style.display = m.style.display === 'none' ? 'block' : 'none';
        }

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#exportMenu') && !e.target.closest('button[onclick="toggleExportMenu()"]')) {
                document.getElementById('exportMenu').style.display = 'none';
            }
        });

        let pendingImportBans = [];

        function handleFileImport(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    const data = JSON.parse(e.target.result);
                    if (!Array.isArray(data)) {
                        alert('Geçersiz dosya formatı. JSON bir dizi içermelidir.');
                        return;
                    }
                    pendingImportBans = data.filter(b => b.username && b.reason && b.original_channel);
                    if (pendingImportBans.length === 0) {
                        alert('Dosyada geçerli ban kaydı bulunamadı. Lütfen JSON yapısının dışa aktarılanla aynı olduğundan emin olun.');
                        return;
                    }

                    document.getElementById('importSummary').innerHTML = `<strong>${pendingImportBans.length}</strong> adet ban kaydı içe aktarılacak. Onaylıyor musunuz?`;

                    const tbody = document.getElementById('importTableBody');
                    tbody.innerHTML = pendingImportBans.slice(0, 100).map(b => {
                        const evCount = b.evidence_messages ? (typeof b.evidence_messages === 'string' ? JSON.parse(b.evidence_messages).length : b.evidence_messages.length) : 0;
                        const evTxt = evCount > 0 ? `<span style="color:#60a5fa">${evCount} Mesaj</span>` : `<span style="color:#71717a">Yok</span>`;
                        return `<tr>
                    <td style="color:#f87171; font-weight:600;">${esc(b.username)}</td>
                    <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${esc(b.reason)}</td>
                    <td><span class="sb-badge">${esc(b.original_channel)}</span></td>
                    <td>${esc(b.moderator_name || '-')}</td>
                    <td>${evTxt}</td>
                </tr>`;
                    }).join('') + (pendingImportBans.length > 100 ? `<tr><td colspan="5" style="text-align:center; color:#a1a1aa;">+ ${pendingImportBans.length - 100} kayıt daha...</td></tr>` : '');

                    document.getElementById('importModal').style.display = 'flex';
                } catch (err) {
                    alert('JSON dosyası okunamadı veya parse edilemedi.');
                    console.error(err);
                }
            };
            reader.readAsText(file);

            event.target.value = '';
        }

        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
            pendingImportBans = [];
        }

        async function confirmImport() {
            if (pendingImportBans.length === 0) return;

            try {
                const res = await fetch('api/shared_bans.php', {
                    method: 'POST',
                    body: JSON.stringify({ action: 'bulk_import', bans: pendingImportBans }),
                    headers: { 'Content-Type': 'application/json' }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert(`${data.imported} adet ban kaydı başarıyla içe aktarıldı.`);
                    closeImportModal();
                    fetchBans(); // Refresh table
                } else {
                    alert('İçe aktarım sırasında hata: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('İçe aktarım işlemi başarısız oldu.');
            }
        }

        async function exportBans(format) {
            document.getElementById('exportMenu').style.display = 'none';

            const s = document.getElementById('banSearch').value.trim();
            const fCh = document.getElementById('filterChannel').value;
            const fMod = document.getElementById('filterMod').value;
            const fEv = document.getElementById('filterEv').value;

            const url = `api/shared_bans.php?export=true&search=${encodeURIComponent(s)}&channel=${encodeURIComponent(fCh)}&mod=${encodeURIComponent(fMod)}&evidence=${fEv}&sort=${sortBy}&dir=${sortDir}`;

            try {
                const res = await fetch(url);
                const data = await res.json();
                if (data.status === 'success') {
                    const bans = data.data;
                    if (bans.length === 0) { alert('Dışa aktarılacak veri yok.'); return; }

                    let content = '';
                    let mime = 'text/plain';
                    let ext = format;

                    if (format === 'json') {
                        content = JSON.stringify(bans, null, 2);
                        mime = 'application/json';
                    } else if (format === 'csv') {
                        const headers = ['Kullanıcı Adı', 'Sebep', 'Ana Kanal', 'Delil', 'Banlayan', 'Tarih'];
                        content = headers.join(',') + '\n';
                        bans.forEach(b => {
                            const evCount = b.evidence_messages ? (typeof b.evidence_messages === 'string' ? JSON.parse(b.evidence_messages).length : b.evidence_messages.length) : 0;
                            const hasEv = evCount > 0 ? `Var (${evCount} mesaj)` : 'Yok';
                            const row = [
                                `"${b.username}"`,
                                `"${b.reason.replace(/"/g, '""')}"`,
                                `"${b.original_channel}"`,
                                `"${hasEv}"`,
                                `"${b.moderator_name}"`,
                                `"${b.created_at}"`
                            ];
                            content += row.join(',') + '\n';
                        });
                        mime = 'text/csv';
                    } else if (format === 'txt') {
                        bans.forEach(b => {
                            const evCount = b.evidence_messages ? (typeof b.evidence_messages === 'string' ? JSON.parse(b.evidence_messages).length : b.evidence_messages.length) : 0;
                            const hasEv = evCount > 0 ? `Var (${evCount} mesaj)` : 'Yok';
                            content += `Kullanıcı: ${b.username}\nSebep: ${b.reason}\nAna Kanal: ${b.original_channel}\nDelil: ${hasEv}\nBanlayan: ${b.moderator_name}\nTarih: ${b.created_at}\n-----------------------------------\n`;
                        });
                    }

                    const blob = new Blob([content], { type: mime });
                    const urlObj = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = urlObj;
                    a.download = `ortak_banlar_${new Date().toISOString().slice(0, 10)}.${ext}`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(urlObj);
                }
            } catch (e) { console.error(e); alert('Dışa aktarma hatası.'); }
        }

        function updateFilterDropdowns(channels, mods) {
            const chSel = document.getElementById('filterChannel');
            const curCh = chSel.value;
            chSel.innerHTML = '<option value="">Tüm Kanallar</option>' + channels.map(c => `<option value="${esc(c)}" ${curCh === c ? 'selected' : ''}>${esc(c)}</option>`).join('');

            const modSel = document.getElementById('filterMod');
            const curMod = modSel.value;
            modSel.innerHTML = '<option value="">Tüm Moderatörler</option>' + mods.map(m => `<option value="${esc(m)}" ${curMod === m ? 'selected' : ''}>${esc(m)}</option>`).join('');
        }

        function renderBans(bans) {
            const tb = document.getElementById('bansBody');
            document.getElementById('selectAllBans').checked = false;

            if (!bans.length) { tb.innerHTML = '<tr><td colspan="8"><div class="sb-empty"><i class="fas fa-globe"></i><p>Ortak ban bulunamadı.</p></div></td></tr>'; currentBans = []; return; }

            currentBans = bans;

            tb.innerHTML = bans.map((b, idx) => {
                const d = new Date(b.created_at.replace(' ', 'T') + 'Z');
                const evCount = b.evidence_messages ? b.evidence_messages.length : 0;
                const evBadge = evCount > 0 ? `<span class="sb-badge evidence" onclick="viewBan(${idx})"><i class="fas fa-paperclip"></i> ${evCount} Mesaj</span>` : '-';

                return `<tr>
            <td><input type="checkbox" class="ban-cb" value="${b.id}" onchange="updateBulkBtn()"></td>
            <td style="cursor:pointer; font-weight:700; color:#f87171;" onclick="viewBan(${idx})">${esc(b.username)}</td>
            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="${esc(b.reason)}">${esc(b.reason)}</td>
            <td><span class="sb-badge">${esc(b.original_channel)}</span></td>
            <td>${esc(b.moderator_name)}</td>
            <td style="color:var(--text-secondary)">${d.toLocaleDateString('tr-TR')}</td>
            <td>${evBadge}</td>
            <td style="text-align:right;">
                <button style="background:transparent; border:none; color:#60a5fa; cursor:pointer; padding:6px;" onclick="openEditBanModal(${idx})"><i class="fas fa-edit"></i></button>
                <button style="background:transparent; border:none; color:#ef4444; cursor:pointer; padding:6px;" onclick="deleteBan(${b.id}, '${esc(b.username)}')"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
            }).join('');
        }

        function renderPagination(p, tp) {
            const d = document.getElementById('banPagination');
            if (tp <= 1) { d.innerHTML = ''; return; }
            let h = ''; const s = Math.max(1, p - 3), e = Math.min(tp, p + 3);
            if (p > 1) h += `<button class="page-btn" onclick="goPage(${p - 1})"><i class="fas fa-chevron-left"></i></button>`;
            for (let i = s; i <= e; i++) h += `<button class="page-btn ${i === p ? 'active' : ''}" onclick="goPage(${i})">${i}</button>`;
            if (p < tp) h += `<button class="page-btn" onclick="goPage(${p + 1})"><i class="fas fa-chevron-right"></i></button>`;
            d.innerHTML = h;
        }
        function goPage(p) { currentPage = p; fetchBans(); }

        // Bulk delete
        function toggleSelectAll(el) {
            document.querySelectorAll('.ban-cb').forEach(cb => cb.checked = el.checked);
            updateBulkBtn();
        }
        function updateBulkBtn() {
            const checked = document.querySelectorAll('.ban-cb:checked').length;
            document.getElementById('bulkDeleteBtn').style.display = checked > 0 ? 'inline-flex' : 'none';
        }

        async function bulkDelete() {
            const cbs = document.querySelectorAll('.ban-cb:checked');
            if (cbs.length === 0) return;
            if (!confirm(`Seçili ${cbs.length} banı silmek istediğinize emin misiniz?`)) return;

            const ids = Array.from(cbs).map(cb => parseInt(cb.value));
            try {
                const res = await fetch('api/shared_bans.php', { method: 'POST', body: JSON.stringify({ action: 'bulk_delete', ids }), headers: { 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.status === 'success') fetchBans(); else alert(data.message);
            } catch (e) { console.error(e); }
        }

        async function deleteBan(id, username) {
            if (!confirm(`'${username}' için ortak banı silmek istediğinize emin misiniz?`)) return;
            try {
                const res = await fetch('api/shared_bans.php', { method: 'POST', body: JSON.stringify({ action: 'delete', id: id }), headers: { 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.status === 'success') fetchBans(); else alert(data.message);
            } catch (e) { console.error(e); }
        }

        // Add/Edit Ban Modal
        function openBanModal() {
            document.getElementById('banModalTitle').textContent = 'Yeni Ortak Ban Ekle';
            document.getElementById('banIdInput').value = '';

            document.getElementById('fetchGroup').style.display = 'block';
            document.getElementById('editUserGroup').style.display = 'none';

            document.getElementById('banUsername').value = '';
            document.getElementById('banReason').value = '';
            document.getElementById('banModName').value = '';
            document.getElementById('banOriginalChannel').value = '';

            document.getElementById('evidenceGroup').style.display = 'none';
            document.getElementById('evidenceList').innerHTML = '';
            document.getElementById('banUserSummary').style.display = 'none';

            document.getElementById('banModal').style.display = 'flex';
        }

        function openEditBanModal(index) {
            const b = currentBans[index];
            if (!b) return;

            document.getElementById('banModalTitle').textContent = 'Ortak Banı Düzenle';
            document.getElementById('banIdInput').value = b.id;

            document.getElementById('fetchGroup').style.display = 'none';
            document.getElementById('editUserGroup').style.display = 'block';
            document.getElementById('banUsernameEdit').value = b.username;

            document.getElementById('banReason').value = b.reason;
            document.getElementById('banModName').value = b.moderator_name;
            document.getElementById('banOriginalChannel').value = b.original_channel;

            document.getElementById('evidenceGroup').style.display = 'none'; 

            document.getElementById('banModal').style.display = 'flex';
        }

        function closeBanModal() { document.getElementById('banModal').style.display = 'none'; }

        let fetchedMessages = [];
        async function fetchUserInfo() {
            const username = document.getElementById('banUsername').value.trim();
            if (!username) return;

            const sumDiv = document.getElementById('banUserSummary');
            const evGrp = document.getElementById('evidenceGroup');
            const evList = document.getElementById('evidenceList');

            sumDiv.style.display = 'block'; sumDiv.textContent = 'Aranıyor...';

            try {
                const res = await fetch(`api/search_user_history.php?username=${encodeURIComponent(username)}`);
                const data = await res.json();
                if (data.status === 'success') {
                    sumDiv.innerHTML = `<span style="color:#10b981"><i class="fas fa-check"></i> Bulundu: Toplam ${data.user.message_count} mesaj, ${data.bans.length} önceki ceza.</span>`;

                    fetchedMessages = data.messages || [];
                    if (fetchedMessages.length > 0) {
                        evGrp.style.display = 'block';
                        evList.innerHTML = fetchedMessages.map((m, i) => {
                            const t = new Date(m.created_at.replace(' ', 'T') + 'Z').toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
                            return `<label class="msg-item">
                        <input type="checkbox" value="${i}" class="ev-check">
                        <div class="msg-item-content"><span class="msg-item-time">[${t}]</span> ${esc(m.content)}</div>
                    </label>`;
                        }).join('');
                    } else {
                        evGrp.style.display = 'block';
                        evList.innerHTML = '<div style="color:#a1a1aa; padding:8px;">Kullanıcının geçmiş mesajı bulunamadı.</div>';
                    }
                } else {
                    sumDiv.innerHTML = `<span style="color:#ef4444"><i class="fas fa-exclamation-triangle"></i> Hata: ${data.message}</span>`;
                }
            } catch (e) { console.error(e); }
        }

        async function saveBan() {
            const id = document.getElementById('banIdInput').value;
            const isEdit = !!id;

            const username = isEdit ? document.getElementById('banUsernameEdit').value : document.getElementById('banUsername').value.trim();
            const reason = document.getElementById('banReason').value.trim();
            const mod = document.getElementById('banModName').value.trim();
            const channel = document.getElementById('banOriginalChannel').value;

            if (!reason || !channel || (!isEdit && !username)) { alert('Lütfen zorunlu alanları (Kullanıcı Adı, Sebep, Kanal) doldurun.'); return; }

            const payload = { action: isEdit ? 'edit' : 'add', reason, moderator_name: mod, original_channel: channel };

            if (isEdit) {
                payload.id = parseInt(id);
            } else {
                payload.username = username;
                const checks = document.querySelectorAll('.ev-check:checked');
                payload.evidence_messages = Array.from(checks).map(c => fetchedMessages[parseInt(c.value)]);
            }

            try {
                const res = await fetch('api/shared_bans.php', { method: 'POST', body: JSON.stringify(payload), headers: { 'Content-Type': 'application/json' } });
                const data = await res.json();
                if (data.status === 'success') { closeBanModal(); fetchBans(); } else alert(data.message);
            } catch (e) { console.error(e); }
        }

        // View Ban Detail
        function viewBan(index) {
            const b = currentBans[index];
            if (!b) return;

            document.getElementById('vbName').textContent = b.username;
            document.getElementById('vbReason').textContent = b.reason;
            document.getElementById('vbChannel').textContent = b.original_channel;
            document.getElementById('vbMod').textContent = b.moderator_name || '-';

            const d = new Date(b.created_at.replace(' ', 'T') + 'Z');
            document.getElementById('vbDate').textContent = d.toLocaleDateString('tr-TR') + ' ' + d.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });

            const evCont = document.getElementById('vbEvidenceContainer');
            const evList = document.getElementById('vbEvidenceList');

            if (b.evidence_messages && b.evidence_messages.length > 0) {
                evCont.style.display = 'block';
                evList.innerHTML = b.evidence_messages.map(m => {
                    const t = m.created_at ? new Date(m.created_at.replace(' ', 'T') + 'Z').toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' }) : '';
                    return `<div class="ev-view-msg"><span style="color:#71717a; font-size:10px;">[${t}]</span> ${esc(m.content)}</div>`;
                }).join('');
            } else {
                evCont.style.display = 'none';
                evList.innerHTML = '';
            }

            document.getElementById('viewBanModal').style.display = 'flex';
        }
        function closeViewBanModal() { document.getElementById('viewBanModal').style.display = 'none'; }

        function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML }

        // Init
        fetchChannels();
        fetchBans();
    </script>
</body>

</html>