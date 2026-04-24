<<<<<<< HEAD
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sadakat Programi - SebastianBot</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .loyalty-layout { flex: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .ly-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .ly-title { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .ly-title i { color: var(--primary-color); }

        .ly-grid { display: grid; grid-template-columns: repeat(4, minmax(160px, 1fr)); gap: 12px; }
        .ly-group { display: flex; flex-direction: column; gap: 6px; }
        .ly-group label { font-size: 12px; color: var(--text-secondary); font-weight: 600; }
        .ly-input { background: #0f1318; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 9px 10px; font-size: 13px; outline: none; font-family: inherit; }
        .ly-input:focus { border-color: var(--primary-color); }
        .ly-help { font-size: 12px; color: #a1a1aa; margin-top: 8px; }
        .ly-field-desc { font-size: 11px; color: #8b949e; line-height: 1.45; }
        .ly-preview-box {
            margin-top: 14px;
            background: #11151c;
            border: 1px solid rgba(83,252,24,0.25);
            border-radius: 10px;
            padding: 12px;
        }
        .ly-preview-title { color: var(--primary-color); font-size: 12px; font-weight: 700; margin-bottom: 8px; }
        .ly-preview-line { font-size: 12px; color: #d4d4d8; margin-bottom: 6px; }
        .ly-preview-line strong { color: #fff; }

        .ly-btn { border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; padding: 9px 14px; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; }
        .ly-btn-primary { background: var(--primary-color); color: #000; }
        .ly-btn-secondary { background: #3f3f46; color: #fff; }
        .ly-btn-danger { background: #ef4444; color: #fff; }
        .ly-btn-info { background: #3b82f6; color: #fff; }

        .ly-actions { margin-top: 14px; display: flex; justify-content: flex-end; gap: 8px; }

        .ly-level-form { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; align-items: end; margin-bottom: 14px; }
        .ly-level-list { display: flex; flex-direction: column; gap: 8px; }
        .ly-level-item { background: #151a21; border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 12px; display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; align-items: center; }
        .ly-level-item input { width: 100%; }
        .ly-level-actions { display: flex; gap: 6px; justify-content: flex-end; }

        .ly-toolbar { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 12px; }
        .ly-search { width: 230px; }
        .ly-count { margin-left: auto; font-size: 12px; color: var(--text-secondary); }

        .ly-table { width: 100%; border-collapse: separate; border-spacing: 0 5px; }
        .ly-table thead th { font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .4px; font-weight: 600; text-align: left; padding: 8px 10px; }
        .ly-table tbody tr { background: #151a21; }
        .ly-table tbody td { padding: 10px; font-size: 12px; vertical-align: middle; }
        .ly-table tbody td:first-child { border-radius: 8px 0 0 8px; }
        .ly-table tbody td:last-child { border-radius: 0 8px 8px 0; }

        .ly-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 7px; border-radius: 14px; font-size: 11px; font-weight: 600; }
        .b-vip { background: rgba(234,179,8,.18); color: #fbbf24; }
        .b-sub { background: rgba(168,85,247,.18); color: #c084fc; }
        .b-ban { background: rgba(239,68,68,.18); color: #f87171; }
        .b-timeout { background: rgba(245,158,11,.18); color: #fbbf24; }

        .ly-pagination { margin-top: 14px; display: flex; justify-content: center; gap: 6px; }
        .ly-page-btn { padding: 7px 12px; border: 1px solid var(--border-color); border-radius: 6px; background: transparent; color: var(--text-secondary); font-size: 12px; cursor: pointer; }
        .ly-page-btn.active { background: var(--primary-color); color: #000; border-color: var(--primary-color); }

        .ly-toast-wrap { position: fixed; right: 20px; bottom: 20px; z-index: 20000; display: flex; flex-direction: column; gap: 8px; }
        .ly-toast { background: #18181b; color: #fff; border-left: 4px solid #53fc18; border-radius: 6px; padding: 10px 14px; font-size: 13px; box-shadow: 0 8px 20px rgba(0,0,0,.35); }
        .ly-toast.err { border-left-color: #ef4444; }

        @media (max-width: 1300px) { .ly-grid { grid-template-columns: repeat(2, minmax(160px, 1fr)); } }
        @media (max-width: 860px) { .ly-grid { grid-template-columns: 1fr; } .ly-level-form { grid-template-columns: 1fr; } .ly-level-item { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Sadakat Programı</div>
            <div class="user-profile">
                <div class="avatar"><i class="fas fa-robot" style="color: var(--primary-color);"></i></div>
                <span>SebastianBot</span>
            </div>
        </header>

        <div class="loyalty-layout">
            <div class="ly-card">
                <div class="ly-title"><i class="fas fa-sliders"></i> Skor Hesaplama Ayarları</div>
                <div class="ly-grid">
                    <div class="ly-group">
                        <label>Takip bonusu için gün aralığı</label>
                        <input class="ly-input" type="number" id="follow_days_step" min="1">
                        <div class="ly-field-desc" id="desc_follow_days_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her gün aralığı için bonus (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="follow_days_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_follow_days_bonus_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Mesaj bonusu için mesaj aralığı</label>
                        <input class="ly-input" type="number" id="message_step" min="1">
                        <div class="ly-field-desc" id="desc_message_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her mesaj aralığı için bonus (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="message_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_message_bonus_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Timeout cezası için timeout aralığı</label>
                        <input class="ly-input" type="number" id="timeout_step" min="1">
                        <div class="ly-field-desc" id="desc_timeout_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her timeout aralığı için ceza (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="timeout_penalty_pct" min="0">
                        <div class="ly-field-desc" id="desc_timeout_penalty_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Ban cezası için ban aralığı</label>
                        <input class="ly-input" type="number" id="ban_step" min="1">
                        <div class="ly-field-desc" id="desc_ban_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her ban aralığı için ceza (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="ban_penalty_pct" min="0">
                        <div class="ly-field-desc" id="desc_ban_penalty_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Silinen mesaj cezası için mesaj aralığı</label>
                        <input class="ly-input" type="number" id="deleted_step" min="1">
                        <div class="ly-field-desc" id="desc_deleted_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her silinen mesaj aralığı için ceza (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="deleted_penalty_pct" min="0">
                        <div class="ly-field-desc" id="desc_deleted_penalty_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Abone bonusu (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="subscriber_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_subscriber_bonus_pct"></div>
                    </div>
                    <div class="ly-group">
                        <label>VIP bonusu (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="vip_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_vip_bonus_pct"></div>
                    </div>
                </div>
                <div class="ly-help">Bu ayarlar, kullanıcının toplam mesaj skoruna yüzdesel bonus ve ceza olarak uygulanır. Değerleri değiştirdigin anda aşagıdaki açıklamalar ve örnek skor hesaplaması anlık güncellenir.</div>

                <div class="ly-preview-box">
                    <div class="ly-preview-title"><i class="fas fa-flask"></i> Canlı Hesaplama Özeti</div>
                    <div class="ly-preview-line" id="preview_line_1"></div>
                    <div class="ly-preview-line" id="preview_line_2"></div>
                    <div class="ly-preview-line" id="preview_line_3"></div>
                    <div class="ly-preview-line" id="preview_line_4"></div>
                    <div class="ly-preview-line" id="preview_line_5"></div>
                </div>

                <div class="ly-actions">
                    <button class="ly-btn ly-btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i> Ayarları Kaydet</button>
                </div>
            </div>

            <div class="ly-card">
                <div class="ly-title"><i class="fas fa-layer-group"></i> Seviye Sistemi</div>
                <div class="ly-level-form">
                    <div class="ly-group">
                        <label>Seviye Adı</label>
                        <input class="ly-input" type="text" id="new_level_name" placeholder="Orn: Seviye 4">
                    </div>
                    <div class="ly-group">
                        <label>Gerekli Skor</label>
                        <input class="ly-input" type="number" step="0.01" id="new_required_score" placeholder="2000">
                    </div>
                    <div class="ly-group">
                        <label>Minimum takip süresi (opsiyonel)</label>
                        <select class="ly-input" id="new_min_follow_days">
                            <option value="0" selected>Şart yok</option>
                            <option value="5">En az 5 gün</option>
                            <option value="10">En az 10 gün</option>
                            <option value="30">En az 1 ay (30 gün)</option>
                            <option value="90">En az 3 ay (90 gün)</option>
                            <option value="180">En az 6 ay (180 gün)</option>
                            <option value="365">En az 1 yıl (365 gün)</option>
                        </select>
                    </div>
                    <button class="ly-btn ly-btn-primary" onclick="addLevel()"><i class="fas fa-plus"></i> Seviye Ekle</button>
                </div>
                <div class="ly-level-list" id="levelsContainer"></div>
                <div class="ly-help">Seviyelerde skor koşulu zorunludur. Ek olarak istersen takip süresi koşulu da koyabilirsin. Kurala göre sadece en son eklenen seviye silinebilir; diğer seviyeler düzenlenebilir ama silinemez.</div>
            </div>

            <div class="ly-card">
                <div class="ly-title"><i class="fas fa-ranking-star"></i> Takipçi Sadakat Sıralaması</div>
                <div class="ly-toolbar">
                    <input class="ly-input ly-search" id="searchInput" placeholder="Kullanıcı ara..." oninput="debouncedFetch()">
                    <select class="ly-input" id="sortInput" onchange="onSortChange()">
                        <option value="score">Skor</option>
                        <option value="follow_date">Takip Tarihi</option>
                        <option value="follow_days">Takip Günleri</option>
                        <option value="message_count">Toplam Mesaj</option>
                        <option value="is_subscriber">Abonelik</option>
                        <option value="is_vip">VIP</option>
                        <option value="timeout_count">Timeout Sayısı</option>
                        <option value="ban_count">Ban Sayısı</option>
                        <option value="deleted_message_count">Silinen Mesaj</option>
                        <option value="level_required_score">Seviye</option>
                    </select>
                    <select class="ly-input" id="dirInput" onchange="fetchLoyaltyData()">
                        <option value="DESC">Azalan</option>
                        <option value="ASC">Artan</option>
                    </select>
                    <div class="ly-count" id="totalCount">0 kayıt</div>
                </div>

                <table class="ly-table">
                    <thead>
                        <tr>
                            <th>Kullanıcı</th>
                            <th>Seviye</th>
                            <th>Skor</th>
                            <th>Takip</th>
                            <th>Mesaj</th>
                            <th>Abone/VIP</th>
                            <th>Timeout</th>
                            <th>Ban</th>
                            <th>Silinen</th>
                        </tr>
                    </thead>
                    <tbody id="rankingBody">
                        <tr><td colspan="9" style="text-align:center;color:#a1a1aa;padding:18px">Yükleniyor...</td></tr>
                    </tbody>
                </table>

                <div class="ly-pagination" id="pagination"></div>
            </div>
        </div>
    </div>

    <div class="ly-toast-wrap" id="toastWrap"></div>

    <script>
        let state = {
            page: 1,
            totalPages: 1,
            sort: 'score',
            dir: 'DESC',
            search: '',
            levels: []
        };
        let searchTimer = null;

        function toast(msg, isError = false) {
            const wrap = document.getElementById('toastWrap');
            const t = document.createElement('div');
            t.className = 'ly-toast' + (isError ? ' err' : '');
            t.textContent = msg;
            wrap.appendChild(t);
            setTimeout(() => { t.remove(); }, 2600);
        }

        function esc(v) {
            const d = document.createElement('div');
            d.textContent = v == null ? '' : String(v);
            return d.innerHTML;
        }

        function fillSettings(s) {
            document.getElementById('follow_days_step').value = s.follow_days_step;
            document.getElementById('follow_days_bonus_pct').value = s.follow_days_bonus_pct;
            document.getElementById('message_step').value = s.message_step;
            document.getElementById('message_bonus_pct').value = s.message_bonus_pct;
            document.getElementById('timeout_step').value = s.timeout_step;
            document.getElementById('timeout_penalty_pct').value = s.timeout_penalty_pct;
            document.getElementById('ban_step').value = s.ban_step;
            document.getElementById('ban_penalty_pct').value = s.ban_penalty_pct;
            document.getElementById('deleted_step').value = s.deleted_step;
            document.getElementById('deleted_penalty_pct').value = s.deleted_penalty_pct;
            document.getElementById('subscriber_bonus_pct').value = s.subscriber_bonus_pct;
            document.getElementById('vip_bonus_pct').value = s.vip_bonus_pct;
            updateLiveExplanations();
        }

        function nInt(id, defVal = 1) {
            const v = parseInt(document.getElementById(id).value, 10);
            return Number.isFinite(v) && v > 0 ? v : defVal;
        }

        function nFloat(id, defVal = 0) {
            const v = parseFloat(document.getElementById(id).value);
            return Number.isFinite(v) ? v : defVal;
        }

        function updateLiveExplanations() {
            const followStep = nInt('follow_days_step', 30);
            const followPct = nFloat('follow_days_bonus_pct', 1);
            const msgStep = nInt('message_step', 100);
            const msgPct = nFloat('message_bonus_pct', 5);
            const timeoutStep = nInt('timeout_step', 5);
            const timeoutPct = nFloat('timeout_penalty_pct', 0.5);
            const banStep = nInt('ban_step', 1);
            const banPct = nFloat('ban_penalty_pct', 10);
            const deletedStep = nInt('deleted_step', 20);
            const deletedPct = nFloat('deleted_penalty_pct', 1);
            const subPct = nFloat('subscriber_bonus_pct', 10);
            const vipPct = nFloat('vip_bonus_pct', 15);

            document.getElementById('desc_follow_days_step').textContent = `Kullanici her ${followStep} gun takipte kaldiginda 1 takip periyodu kazanir.`;
            document.getElementById('desc_follow_days_bonus_pct').textContent = `Her takip periyodu, toplam skora +%${followPct.toFixed(3)} bonus ekler.`;
            document.getElementById('desc_message_step').textContent = `Kullanici her ${msgStep} mesajda 1 mesaj periyodu kazanir.`;
            document.getElementById('desc_message_bonus_pct').textContent = `Her mesaj periyodu, toplam skora +%${msgPct.toFixed(3)} bonus ekler.`;
            document.getElementById('desc_timeout_step').textContent = `Kullanici her ${timeoutStep} timeoutta 1 ceza periyodu alir.`;
            document.getElementById('desc_timeout_penalty_pct').textContent = `Her timeout ceza periyodu toplam skordan -%${timeoutPct.toFixed(3)} dusurur.`;
            document.getElementById('desc_ban_step').textContent = `Kullanici her ${banStep} ban kaydinda 1 ceza periyodu alir.`;
            document.getElementById('desc_ban_penalty_pct').textContent = `Her ban ceza periyodu toplam skordan -%${banPct.toFixed(3)} dusurur.`;
            document.getElementById('desc_deleted_step').textContent = `Kullanici her ${deletedStep} silinen mesajda 1 ceza periyodu alir.`;
            document.getElementById('desc_deleted_penalty_pct').textContent = `Her silinen mesaj ceza periyodu toplam skordan -%${deletedPct.toFixed(3)} dusurur.`;
            document.getElementById('desc_subscriber_bonus_pct').textContent = `Kullanici aboneyse toplam skora sabit +%${subPct.toFixed(3)} bonus eklenir.`;
            document.getElementById('desc_vip_bonus_pct').textContent = `Kullanici VIP ise toplam skora sabit +%${vipPct.toFixed(3)} bonus eklenir.`;

            // Ornek: 100 mesajlik taban skor
            const base = 100;
            const msgBonusPctFor100 = (100 / msgStep) * msgPct;
            const normalScore = base * (1 + msgBonusPctFor100 / 100);
            const subScore = base * (1 + (msgBonusPctFor100 + subPct) / 100);
            const vipSubScore = base * (1 + (msgBonusPctFor100 + subPct + vipPct) / 100);

            const timeoutPenaltySample = timeoutPct; // 1 timeout ceza periyodu
            const oneTimeoutScore = base * (1 + (msgBonusPctFor100 - timeoutPenaltySample) / 100);

            const banPenaltySample = banPct; // 1 ban ceza periyodu
            const oneBanScore = base * (1 + (msgBonusPctFor100 - banPenaltySample) / 100);

            document.getElementById('preview_line_1').innerHTML = `<strong>100 mesaj taban skor:</strong> ${base.toFixed(2)}`;
            document.getElementById('preview_line_2').innerHTML = `<strong>Normal kullanıcı (cezasız):</strong> 100 mesajda mesaj bonusuyla yaklaşık <strong>${normalScore.toFixed(2)}</strong> skor`;
            document.getElementById('preview_line_3').innerHTML = `<strong>Abone kullanıcı:</strong> 100 mesajda yaklaşık <strong>${subScore.toFixed(2)}</strong> skor`;
            document.getElementById('preview_line_4').innerHTML = `<strong>Abone + VIP kullanıcı:</strong> 100 mesajda yaklaşık <strong>${vipSubScore.toFixed(2)}</strong> skor`;
            document.getElementById('preview_line_5').innerHTML = `<strong>Ceza etkisi örneği:</strong> 100 mesaj + 1 timeout periyodu: <strong>${oneTimeoutScore.toFixed(2)}</strong>, 100 mesaj + 1 ban periyodu: <strong>${oneBanScore.toFixed(2)}</strong>`;
        }

        function renderLevels(levels) {
            state.levels = levels || [];
            const cont = document.getElementById('levelsContainer');
            if (!levels || levels.length === 0) {
                cont.innerHTML = '<div style="color:#a1a1aa;font-size:13px;">Seviye yok.</div>';
                return;
            }
            const lastId = levels[levels.length - 1].id;

            const followOptions = [
                { value: 0, text: 'Şart yok' },
                { value: 5, text: 'En az 5 gün' },
                { value: 10, text: 'En az 10 gün' },
                { value: 30, text: 'En az 1 ay (30 gün)' },
                { value: 90, text: 'En az 3 ay (90 gün)' },
                { value: 180, text: 'En az 6 ay (180 gün)' },
                { value: 365, text: 'En az 1 yıl (365 gün)' }
            ];

            cont.innerHTML = levels.map(l => {
                const canDelete = Number(l.id) === Number(lastId);
                const selectedFollow = Number(l.min_follow_days || 0);
                const followSelect = `<select class="ly-input" id="lvl_follow_${l.id}">` +
                    followOptions.map(opt => `<option value="${opt.value}" ${opt.value === selectedFollow ? 'selected' : ''}>${opt.text}</option>`).join('') +
                    `</select>`;
                return `
                    <div class="ly-level-item">
                        <input class="ly-input" type="text" id="lvl_name_${l.id}" value="${esc(l.level_name)}">
                        <input class="ly-input" type="number" step="0.01" id="lvl_score_${l.id}" value="${esc(l.required_score)}">
                        ${followSelect}
                        <div class="ly-level-actions">
                            <button class="ly-btn ly-btn-info" onclick="updateLevel(${l.id})"><i class="fas fa-save"></i></button>
                            <button class="ly-btn ${canDelete ? 'ly-btn-danger' : 'ly-btn-secondary'}" ${canDelete ? '' : 'disabled'} title="${canDelete ? 'Seviyeyi sil' : 'Sadece son seviye silinebilir'}" onclick="deleteLevel(${l.id})"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function formatDate(dt) {
            if (!dt) return '-';
            const d = new Date(dt.replace(' ', 'T'));
            if (isNaN(d.getTime())) return '-';
            return d.toLocaleDateString('tr-TR') + ' ' + d.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        }

        function renderRanking(rows, total, page, totalPages) {
            const body = document.getElementById('rankingBody');
            document.getElementById('totalCount').textContent = total + ' kayit';

            if (!rows || rows.length === 0) {
                body.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#a1a1aa;padding:18px">Kayit bulunamadi.</td></tr>';
            } else {
                body.innerHTML = rows.map(r => {
                    const badges = [];
                    if (Number(r.is_subscriber) === 1) badges.push('<span class="ly-badge b-sub"><i class="fas fa-star"></i> Abone</span>');
                    if (Number(r.is_vip) === 1) badges.push('<span class="ly-badge b-vip"><i class="fas fa-gem"></i> VIP</span>');
                    if (badges.length === 0) badges.push('-');

                    const timeoutBadge = Number(r.is_timeout_active) === 1
                        ? '<span class="ly-badge b-timeout"><i class="fas fa-clock"></i> Aktif</span>'
                        : '<span style="color:#a1a1aa">' + esc(r.timeout_count) + '</span>';

                    const banBadge = Number(r.is_banned) === 1
                        ? '<span class="ly-badge b-ban"><i class="fas fa-ban"></i> Banli</span>'
                        : '<span style="color:#a1a1aa">' + esc(r.ban_count) + '</span>';

                    return `
                        <tr>
                            <td style="font-weight:700;color:#e4e4e7">${esc(r.username)}</td>
                            <td><span class="ly-badge" style="background:rgba(59,130,246,.15);color:#60a5fa">${esc(r.level_name)}</span></td>
                        <td style="font-weight:700;color:var(--primary-color)">${Number(r.score).toFixed(2)}
                            ${r.spent_score > 0 ? `<br><span style="font-size:10px;color:#ef4444;font-weight:600;">(-${Number(r.spent_score).toFixed(2)})</span>` : ''}
                        </td>
                            <td>${formatDate(r.follow_date)}<br><span style="color:#71717a;font-size:11px">${esc(r.follow_days)} gun</span></td>
                            <td>${esc(r.message_count)}</td>
                            <td>${badges.join(' ')}</td>
                            <td>${timeoutBadge}</td>
                            <td>${banBadge}</td>
                            <td>${esc(r.deleted_message_count)}</td>
                        </tr>
                    `;
                }).join('');
            }

            state.page = page;
            state.totalPages = totalPages;
            renderPagination();
        }

        function renderPagination() {
            const p = document.getElementById('pagination');
            if (state.totalPages <= 1) {
                p.innerHTML = '';
                return;
            }
            let h = '';
            const start = Math.max(1, state.page - 2);
            const end = Math.min(state.totalPages, state.page + 2);

            if (state.page > 1) h += `<button class="ly-page-btn" onclick="goPage(${state.page - 1})"><i class="fas fa-chevron-left"></i></button>`;
            for (let i = start; i <= end; i++) {
                h += `<button class="ly-page-btn ${i === state.page ? 'active' : ''}" onclick="goPage(${i})">${i}</button>`;
            }
            if (state.page < state.totalPages) h += `<button class="ly-page-btn" onclick="goPage(${state.page + 1})"><i class="fas fa-chevron-right"></i></button>`;
            p.innerHTML = h;
        }

        function goPage(p) {
            state.page = p;
            fetchLoyaltyData();
        }

        async function fetchLoyaltyData() {
            try {
                const q = new URLSearchParams({
                    action: 'bootstrap',
                    page: String(state.page),
                    search: state.search,
                    sort: state.sort,
                    dir: state.dir
                });
                const res = await fetch('api/loyalty.php?' + q.toString());
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');

                fillSettings(data.settings);
                renderLevels(data.levels);
                renderRanking(data.ranking, data.total, data.page, data.total_pages);
            } catch (e) {
                toast('Veriler alinamadi: ' + e.message, true);
            }
        }

        function onSortChange() {
            state.sort = document.getElementById('sortInput').value;
            state.page = 1;
            fetchLoyaltyData();
        }

        function debouncedFetch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                state.search = document.getElementById('searchInput').value.trim();
                state.page = 1;
                fetchLoyaltyData();
            }, 300);
        }

        async function saveSettings() {
            const payload = {
                action: 'save_settings',
                follow_days_step: document.getElementById('follow_days_step').value,
                follow_days_bonus_pct: document.getElementById('follow_days_bonus_pct').value,
                message_step: document.getElementById('message_step').value,
                message_bonus_pct: document.getElementById('message_bonus_pct').value,
                timeout_step: document.getElementById('timeout_step').value,
                timeout_penalty_pct: document.getElementById('timeout_penalty_pct').value,
                ban_step: document.getElementById('ban_step').value,
                ban_penalty_pct: document.getElementById('ban_penalty_pct').value,
                deleted_step: document.getElementById('deleted_step').value,
                deleted_penalty_pct: document.getElementById('deleted_penalty_pct').value,
                subscriber_bonus_pct: document.getElementById('subscriber_bonus_pct').value,
                vip_bonus_pct: document.getElementById('vip_bonus_pct').value
            };

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');
                toast('Ayarlar kaydedildi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Kayit hatasi: ' + e.message, true);
            }
        }

        async function addLevel() {
            const level_name = document.getElementById('new_level_name').value.trim();
            const required_score = document.getElementById('new_required_score').value;
            const min_follow_days = parseInt(document.getElementById('new_min_follow_days').value, 10) || 0;
            if (!level_name || required_score === '') {
                toast('Seviye adi ve skor zorunlu.', true);
                return;
            }

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'add_level', level_name, required_score, min_follow_days })
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');

                document.getElementById('new_level_name').value = '';
                document.getElementById('new_required_score').value = '';
                document.getElementById('new_min_follow_days').value = '0';
                toast('Seviye eklendi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Seviye eklenemedi: ' + e.message, true);
            }
        }

        async function updateLevel(id) {
            const level_name = document.getElementById('lvl_name_' + id).value.trim();
            const required_score = document.getElementById('lvl_score_' + id).value;
            const min_follow_days = parseInt(document.getElementById('lvl_follow_' + id).value, 10) || 0;

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'update_level', id, level_name, required_score, min_follow_days })
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');
                toast('Seviye guncellendi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Seviye guncellenemedi: ' + e.message, true);
            }
        }

        async function deleteLevel(id) {
            if (!confirm('Bu seviyeyi silmek istediginize emin misiniz?')) return;

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_level', id })
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');
                toast('Seviye silindi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Seviye silinemedi: ' + e.message, true);
            }
        }

        document.getElementById('dirInput').addEventListener('change', function() {
            state.dir = this.value;
            state.page = 1;
            fetchLoyaltyData();
        });

        [
            'follow_days_step', 'follow_days_bonus_pct', 'message_step', 'message_bonus_pct',
            'timeout_step', 'timeout_penalty_pct', 'ban_step', 'ban_penalty_pct',
            'deleted_step', 'deleted_penalty_pct', 'subscriber_bonus_pct', 'vip_bonus_pct'
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', updateLiveExplanations);
            }
        });

        fetchLoyaltyData();
    </script>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sadakat Programi - SebastianBot</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .loyalty-layout { flex: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .ly-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
        .ly-title { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .ly-title i { color: var(--primary-color); }

        .ly-grid { display: grid; grid-template-columns: repeat(4, minmax(160px, 1fr)); gap: 12px; }
        .ly-group { display: flex; flex-direction: column; gap: 6px; }
        .ly-group label { font-size: 12px; color: var(--text-secondary); font-weight: 600; }
        .ly-input { background: #0f1318; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 9px 10px; font-size: 13px; outline: none; font-family: inherit; }
        .ly-input:focus { border-color: var(--primary-color); }
        .ly-help { font-size: 12px; color: #a1a1aa; margin-top: 8px; }
        .ly-field-desc { font-size: 11px; color: #8b949e; line-height: 1.45; }
        .ly-preview-box {
            margin-top: 14px;
            background: #11151c;
            border: 1px solid rgba(83,252,24,0.25);
            border-radius: 10px;
            padding: 12px;
        }
        .ly-preview-title { color: var(--primary-color); font-size: 12px; font-weight: 700; margin-bottom: 8px; }
        .ly-preview-line { font-size: 12px; color: #d4d4d8; margin-bottom: 6px; }
        .ly-preview-line strong { color: #fff; }

        .ly-btn { border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; padding: 9px 14px; display: inline-flex; align-items: center; gap: 8px; font-family: inherit; }
        .ly-btn-primary { background: var(--primary-color); color: #000; }
        .ly-btn-secondary { background: #3f3f46; color: #fff; }
        .ly-btn-danger { background: #ef4444; color: #fff; }
        .ly-btn-info { background: #3b82f6; color: #fff; }

        .ly-actions { margin-top: 14px; display: flex; justify-content: flex-end; gap: 8px; }

        .ly-level-form { display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; align-items: end; margin-bottom: 14px; }
        .ly-level-list { display: flex; flex-direction: column; gap: 8px; }
        .ly-level-item { background: #151a21; border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 12px; display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 10px; align-items: center; }
        .ly-level-item input { width: 100%; }
        .ly-level-actions { display: flex; gap: 6px; justify-content: flex-end; }

        .ly-toolbar { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 12px; }
        .ly-search { width: 230px; }
        .ly-count { margin-left: auto; font-size: 12px; color: var(--text-secondary); }

        .ly-table { width: 100%; border-collapse: separate; border-spacing: 0 5px; }
        .ly-table thead th { font-size: 11px; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .4px; font-weight: 600; text-align: left; padding: 8px 10px; }
        .ly-table tbody tr { background: #151a21; }
        .ly-table tbody td { padding: 10px; font-size: 12px; vertical-align: middle; }
        .ly-table tbody td:first-child { border-radius: 8px 0 0 8px; }
        .ly-table tbody td:last-child { border-radius: 0 8px 8px 0; }

        .ly-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 7px; border-radius: 14px; font-size: 11px; font-weight: 600; }
        .b-vip { background: rgba(234,179,8,.18); color: #fbbf24; }
        .b-sub { background: rgba(168,85,247,.18); color: #c084fc; }
        .b-ban { background: rgba(239,68,68,.18); color: #f87171; }
        .b-timeout { background: rgba(245,158,11,.18); color: #fbbf24; }

        .ly-pagination { margin-top: 14px; display: flex; justify-content: center; gap: 6px; }
        .ly-page-btn { padding: 7px 12px; border: 1px solid var(--border-color); border-radius: 6px; background: transparent; color: var(--text-secondary); font-size: 12px; cursor: pointer; }
        .ly-page-btn.active { background: var(--primary-color); color: #000; border-color: var(--primary-color); }

        .ly-toast-wrap { position: fixed; right: 20px; bottom: 20px; z-index: 20000; display: flex; flex-direction: column; gap: 8px; }
        .ly-toast { background: #18181b; color: #fff; border-left: 4px solid #53fc18; border-radius: 6px; padding: 10px 14px; font-size: 13px; box-shadow: 0 8px 20px rgba(0,0,0,.35); }
        .ly-toast.err { border-left-color: #ef4444; }

        @media (max-width: 1300px) { .ly-grid { grid-template-columns: repeat(2, minmax(160px, 1fr)); } }
        @media (max-width: 860px) { .ly-grid { grid-template-columns: 1fr; } .ly-level-form { grid-template-columns: 1fr; } .ly-level-item { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Sadakat Programı</div>
            <div class="user-profile">
                <div class="avatar"><i class="fas fa-robot" style="color: var(--primary-color);"></i></div>
                <span>SebastianBot</span>
            </div>
        </header>

        <div class="loyalty-layout">
            <div class="ly-card">
                <div class="ly-title"><i class="fas fa-sliders"></i> Skor Hesaplama Ayarları</div>
                <div class="ly-grid">
                    <div class="ly-group">
                        <label>Takip bonusu için gün aralığı</label>
                        <input class="ly-input" type="number" id="follow_days_step" min="1">
                        <div class="ly-field-desc" id="desc_follow_days_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her gün aralığı için bonus (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="follow_days_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_follow_days_bonus_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Mesaj bonusu için mesaj aralığı</label>
                        <input class="ly-input" type="number" id="message_step" min="1">
                        <div class="ly-field-desc" id="desc_message_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her mesaj aralığı için bonus (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="message_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_message_bonus_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Timeout cezası için timeout aralığı</label>
                        <input class="ly-input" type="number" id="timeout_step" min="1">
                        <div class="ly-field-desc" id="desc_timeout_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her timeout aralığı için ceza (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="timeout_penalty_pct" min="0">
                        <div class="ly-field-desc" id="desc_timeout_penalty_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Ban cezası için ban aralığı</label>
                        <input class="ly-input" type="number" id="ban_step" min="1">
                        <div class="ly-field-desc" id="desc_ban_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her ban aralığı için ceza (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="ban_penalty_pct" min="0">
                        <div class="ly-field-desc" id="desc_ban_penalty_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Silinen mesaj cezası için mesaj aralığı</label>
                        <input class="ly-input" type="number" id="deleted_step" min="1">
                        <div class="ly-field-desc" id="desc_deleted_step"></div>
                    </div>
                    <div class="ly-group">
                        <label>Her silinen mesaj aralığı için ceza (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="deleted_penalty_pct" min="0">
                        <div class="ly-field-desc" id="desc_deleted_penalty_pct"></div>
                    </div>

                    <div class="ly-group">
                        <label>Abone bonusu (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="subscriber_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_subscriber_bonus_pct"></div>
                    </div>
                    <div class="ly-group">
                        <label>VIP bonusu (%)</label>
                        <input class="ly-input" type="number" step="0.001" id="vip_bonus_pct" min="0">
                        <div class="ly-field-desc" id="desc_vip_bonus_pct"></div>
                    </div>
                </div>
                <div class="ly-help">Bu ayarlar, kullanıcının toplam mesaj skoruna yüzdesel bonus ve ceza olarak uygulanır. Değerleri değiştirdigin anda aşagıdaki açıklamalar ve örnek skor hesaplaması anlık güncellenir.</div>

                <div class="ly-preview-box">
                    <div class="ly-preview-title"><i class="fas fa-flask"></i> Canlı Hesaplama Özeti</div>
                    <div class="ly-preview-line" id="preview_line_1"></div>
                    <div class="ly-preview-line" id="preview_line_2"></div>
                    <div class="ly-preview-line" id="preview_line_3"></div>
                    <div class="ly-preview-line" id="preview_line_4"></div>
                    <div class="ly-preview-line" id="preview_line_5"></div>
                </div>

                <div class="ly-actions">
                    <button class="ly-btn ly-btn-primary" onclick="saveSettings()"><i class="fas fa-save"></i> Ayarları Kaydet</button>
                </div>
            </div>

            <div class="ly-card">
                <div class="ly-title"><i class="fas fa-layer-group"></i> Seviye Sistemi</div>
                <div class="ly-level-form">
                    <div class="ly-group">
                        <label>Seviye Adı</label>
                        <input class="ly-input" type="text" id="new_level_name" placeholder="Orn: Seviye 4">
                    </div>
                    <div class="ly-group">
                        <label>Gerekli Skor</label>
                        <input class="ly-input" type="number" step="0.01" id="new_required_score" placeholder="2000">
                    </div>
                    <div class="ly-group">
                        <label>Minimum takip süresi (opsiyonel)</label>
                        <select class="ly-input" id="new_min_follow_days">
                            <option value="0" selected>Şart yok</option>
                            <option value="5">En az 5 gün</option>
                            <option value="10">En az 10 gün</option>
                            <option value="30">En az 1 ay (30 gün)</option>
                            <option value="90">En az 3 ay (90 gün)</option>
                            <option value="180">En az 6 ay (180 gün)</option>
                            <option value="365">En az 1 yıl (365 gün)</option>
                        </select>
                    </div>
                    <button class="ly-btn ly-btn-primary" onclick="addLevel()"><i class="fas fa-plus"></i> Seviye Ekle</button>
                </div>
                <div class="ly-level-list" id="levelsContainer"></div>
                <div class="ly-help">Seviyelerde skor koşulu zorunludur. Ek olarak istersen takip süresi koşulu da koyabilirsin. Kurala göre sadece en son eklenen seviye silinebilir; diğer seviyeler düzenlenebilir ama silinemez.</div>
            </div>

            <div class="ly-card">
                <div class="ly-title"><i class="fas fa-ranking-star"></i> Takipçi Sadakat Sıralaması</div>
                <div class="ly-toolbar">
                    <input class="ly-input ly-search" id="searchInput" placeholder="Kullanıcı ara..." oninput="debouncedFetch()">
                    <select class="ly-input" id="sortInput" onchange="onSortChange()">
                        <option value="score">Skor</option>
                        <option value="follow_date">Takip Tarihi</option>
                        <option value="follow_days">Takip Günleri</option>
                        <option value="message_count">Toplam Mesaj</option>
                        <option value="is_subscriber">Abonelik</option>
                        <option value="is_vip">VIP</option>
                        <option value="timeout_count">Timeout Sayısı</option>
                        <option value="ban_count">Ban Sayısı</option>
                        <option value="deleted_message_count">Silinen Mesaj</option>
                        <option value="level_required_score">Seviye</option>
                    </select>
                    <select class="ly-input" id="dirInput" onchange="fetchLoyaltyData()">
                        <option value="DESC">Azalan</option>
                        <option value="ASC">Artan</option>
                    </select>
                    <div class="ly-count" id="totalCount">0 kayıt</div>
                </div>

                <table class="ly-table">
                    <thead>
                        <tr>
                            <th>Kullanıcı</th>
                            <th>Seviye</th>
                            <th>Skor</th>
                            <th>Takip</th>
                            <th>Mesaj</th>
                            <th>Abone/VIP</th>
                            <th>Timeout</th>
                            <th>Ban</th>
                            <th>Silinen</th>
                        </tr>
                    </thead>
                    <tbody id="rankingBody">
                        <tr><td colspan="9" style="text-align:center;color:#a1a1aa;padding:18px">Yükleniyor...</td></tr>
                    </tbody>
                </table>

                <div class="ly-pagination" id="pagination"></div>
            </div>
        </div>
    </div>

    <div class="ly-toast-wrap" id="toastWrap"></div>

    <script>
        let state = {
            page: 1,
            totalPages: 1,
            sort: 'score',
            dir: 'DESC',
            search: '',
            levels: []
        };
        let searchTimer = null;

        function toast(msg, isError = false) {
            const wrap = document.getElementById('toastWrap');
            const t = document.createElement('div');
            t.className = 'ly-toast' + (isError ? ' err' : '');
            t.textContent = msg;
            wrap.appendChild(t);
            setTimeout(() => { t.remove(); }, 2600);
        }

        function esc(v) {
            const d = document.createElement('div');
            d.textContent = v == null ? '' : String(v);
            return d.innerHTML;
        }

        function fillSettings(s) {
            document.getElementById('follow_days_step').value = s.follow_days_step;
            document.getElementById('follow_days_bonus_pct').value = s.follow_days_bonus_pct;
            document.getElementById('message_step').value = s.message_step;
            document.getElementById('message_bonus_pct').value = s.message_bonus_pct;
            document.getElementById('timeout_step').value = s.timeout_step;
            document.getElementById('timeout_penalty_pct').value = s.timeout_penalty_pct;
            document.getElementById('ban_step').value = s.ban_step;
            document.getElementById('ban_penalty_pct').value = s.ban_penalty_pct;
            document.getElementById('deleted_step').value = s.deleted_step;
            document.getElementById('deleted_penalty_pct').value = s.deleted_penalty_pct;
            document.getElementById('subscriber_bonus_pct').value = s.subscriber_bonus_pct;
            document.getElementById('vip_bonus_pct').value = s.vip_bonus_pct;
            updateLiveExplanations();
        }

        function nInt(id, defVal = 1) {
            const v = parseInt(document.getElementById(id).value, 10);
            return Number.isFinite(v) && v > 0 ? v : defVal;
        }

        function nFloat(id, defVal = 0) {
            const v = parseFloat(document.getElementById(id).value);
            return Number.isFinite(v) ? v : defVal;
        }

        function updateLiveExplanations() {
            const followStep = nInt('follow_days_step', 30);
            const followPct = nFloat('follow_days_bonus_pct', 1);
            const msgStep = nInt('message_step', 100);
            const msgPct = nFloat('message_bonus_pct', 5);
            const timeoutStep = nInt('timeout_step', 5);
            const timeoutPct = nFloat('timeout_penalty_pct', 0.5);
            const banStep = nInt('ban_step', 1);
            const banPct = nFloat('ban_penalty_pct', 10);
            const deletedStep = nInt('deleted_step', 20);
            const deletedPct = nFloat('deleted_penalty_pct', 1);
            const subPct = nFloat('subscriber_bonus_pct', 10);
            const vipPct = nFloat('vip_bonus_pct', 15);

            document.getElementById('desc_follow_days_step').textContent = `Kullanici her ${followStep} gun takipte kaldiginda 1 takip periyodu kazanir.`;
            document.getElementById('desc_follow_days_bonus_pct').textContent = `Her takip periyodu, toplam skora +%${followPct.toFixed(3)} bonus ekler.`;
            document.getElementById('desc_message_step').textContent = `Kullanici her ${msgStep} mesajda 1 mesaj periyodu kazanir.`;
            document.getElementById('desc_message_bonus_pct').textContent = `Her mesaj periyodu, toplam skora +%${msgPct.toFixed(3)} bonus ekler.`;
            document.getElementById('desc_timeout_step').textContent = `Kullanici her ${timeoutStep} timeoutta 1 ceza periyodu alir.`;
            document.getElementById('desc_timeout_penalty_pct').textContent = `Her timeout ceza periyodu toplam skordan -%${timeoutPct.toFixed(3)} dusurur.`;
            document.getElementById('desc_ban_step').textContent = `Kullanici her ${banStep} ban kaydinda 1 ceza periyodu alir.`;
            document.getElementById('desc_ban_penalty_pct').textContent = `Her ban ceza periyodu toplam skordan -%${banPct.toFixed(3)} dusurur.`;
            document.getElementById('desc_deleted_step').textContent = `Kullanici her ${deletedStep} silinen mesajda 1 ceza periyodu alir.`;
            document.getElementById('desc_deleted_penalty_pct').textContent = `Her silinen mesaj ceza periyodu toplam skordan -%${deletedPct.toFixed(3)} dusurur.`;
            document.getElementById('desc_subscriber_bonus_pct').textContent = `Kullanici aboneyse toplam skora sabit +%${subPct.toFixed(3)} bonus eklenir.`;
            document.getElementById('desc_vip_bonus_pct').textContent = `Kullanici VIP ise toplam skora sabit +%${vipPct.toFixed(3)} bonus eklenir.`;

            // Ornek: 100 mesajlik taban skor
            const base = 100;
            const msgBonusPctFor100 = (100 / msgStep) * msgPct;
            const normalScore = base * (1 + msgBonusPctFor100 / 100);
            const subScore = base * (1 + (msgBonusPctFor100 + subPct) / 100);
            const vipSubScore = base * (1 + (msgBonusPctFor100 + subPct + vipPct) / 100);

            const timeoutPenaltySample = timeoutPct; // 1 timeout ceza periyodu
            const oneTimeoutScore = base * (1 + (msgBonusPctFor100 - timeoutPenaltySample) / 100);

            const banPenaltySample = banPct; // 1 ban ceza periyodu
            const oneBanScore = base * (1 + (msgBonusPctFor100 - banPenaltySample) / 100);

            document.getElementById('preview_line_1').innerHTML = `<strong>100 mesaj taban skor:</strong> ${base.toFixed(2)}`;
            document.getElementById('preview_line_2').innerHTML = `<strong>Normal kullanıcı (cezasız):</strong> 100 mesajda mesaj bonusuyla yaklaşık <strong>${normalScore.toFixed(2)}</strong> skor`;
            document.getElementById('preview_line_3').innerHTML = `<strong>Abone kullanıcı:</strong> 100 mesajda yaklaşık <strong>${subScore.toFixed(2)}</strong> skor`;
            document.getElementById('preview_line_4').innerHTML = `<strong>Abone + VIP kullanıcı:</strong> 100 mesajda yaklaşık <strong>${vipSubScore.toFixed(2)}</strong> skor`;
            document.getElementById('preview_line_5').innerHTML = `<strong>Ceza etkisi örneği:</strong> 100 mesaj + 1 timeout periyodu: <strong>${oneTimeoutScore.toFixed(2)}</strong>, 100 mesaj + 1 ban periyodu: <strong>${oneBanScore.toFixed(2)}</strong>`;
        }

        function renderLevels(levels) {
            state.levels = levels || [];
            const cont = document.getElementById('levelsContainer');
            if (!levels || levels.length === 0) {
                cont.innerHTML = '<div style="color:#a1a1aa;font-size:13px;">Seviye yok.</div>';
                return;
            }
            const lastId = levels[levels.length - 1].id;

            const followOptions = [
                { value: 0, text: 'Şart yok' },
                { value: 5, text: 'En az 5 gün' },
                { value: 10, text: 'En az 10 gün' },
                { value: 30, text: 'En az 1 ay (30 gün)' },
                { value: 90, text: 'En az 3 ay (90 gün)' },
                { value: 180, text: 'En az 6 ay (180 gün)' },
                { value: 365, text: 'En az 1 yıl (365 gün)' }
            ];

            cont.innerHTML = levels.map(l => {
                const canDelete = Number(l.id) === Number(lastId);
                const selectedFollow = Number(l.min_follow_days || 0);
                const followSelect = `<select class="ly-input" id="lvl_follow_${l.id}">` +
                    followOptions.map(opt => `<option value="${opt.value}" ${opt.value === selectedFollow ? 'selected' : ''}>${opt.text}</option>`).join('') +
                    `</select>`;
                return `
                    <div class="ly-level-item">
                        <input class="ly-input" type="text" id="lvl_name_${l.id}" value="${esc(l.level_name)}">
                        <input class="ly-input" type="number" step="0.01" id="lvl_score_${l.id}" value="${esc(l.required_score)}">
                        ${followSelect}
                        <div class="ly-level-actions">
                            <button class="ly-btn ly-btn-info" onclick="updateLevel(${l.id})"><i class="fas fa-save"></i></button>
                            <button class="ly-btn ${canDelete ? 'ly-btn-danger' : 'ly-btn-secondary'}" ${canDelete ? '' : 'disabled'} title="${canDelete ? 'Seviyeyi sil' : 'Sadece son seviye silinebilir'}" onclick="deleteLevel(${l.id})"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function formatDate(dt) {
            if (!dt) return '-';
            const d = new Date(dt.replace(' ', 'T'));
            if (isNaN(d.getTime())) return '-';
            return d.toLocaleDateString('tr-TR') + ' ' + d.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' });
        }

        function renderRanking(rows, total, page, totalPages) {
            const body = document.getElementById('rankingBody');
            document.getElementById('totalCount').textContent = total + ' kayit';

            if (!rows || rows.length === 0) {
                body.innerHTML = '<tr><td colspan="9" style="text-align:center;color:#a1a1aa;padding:18px">Kayit bulunamadi.</td></tr>';
            } else {
                body.innerHTML = rows.map(r => {
                    const badges = [];
                    if (Number(r.is_subscriber) === 1) badges.push('<span class="ly-badge b-sub"><i class="fas fa-star"></i> Abone</span>');
                    if (Number(r.is_vip) === 1) badges.push('<span class="ly-badge b-vip"><i class="fas fa-gem"></i> VIP</span>');
                    if (badges.length === 0) badges.push('-');

                    const timeoutBadge = Number(r.is_timeout_active) === 1
                        ? '<span class="ly-badge b-timeout"><i class="fas fa-clock"></i> Aktif</span>'
                        : '<span style="color:#a1a1aa">' + esc(r.timeout_count) + '</span>';

                    const banBadge = Number(r.is_banned) === 1
                        ? '<span class="ly-badge b-ban"><i class="fas fa-ban"></i> Banli</span>'
                        : '<span style="color:#a1a1aa">' + esc(r.ban_count) + '</span>';

                    return `
                        <tr>
                            <td style="font-weight:700;color:#e4e4e7">${esc(r.username)}</td>
                            <td><span class="ly-badge" style="background:rgba(59,130,246,.15);color:#60a5fa">${esc(r.level_name)}</span></td>
                        <td style="font-weight:700;color:var(--primary-color)">${Number(r.score).toFixed(2)}
                            ${r.spent_score > 0 ? `<br><span style="font-size:10px;color:#ef4444;font-weight:600;">(-${Number(r.spent_score).toFixed(2)})</span>` : ''}
                        </td>
                            <td>${formatDate(r.follow_date)}<br><span style="color:#71717a;font-size:11px">${esc(r.follow_days)} gun</span></td>
                            <td>${esc(r.message_count)}</td>
                            <td>${badges.join(' ')}</td>
                            <td>${timeoutBadge}</td>
                            <td>${banBadge}</td>
                            <td>${esc(r.deleted_message_count)}</td>
                        </tr>
                    `;
                }).join('');
            }

            state.page = page;
            state.totalPages = totalPages;
            renderPagination();
        }

        function renderPagination() {
            const p = document.getElementById('pagination');
            if (state.totalPages <= 1) {
                p.innerHTML = '';
                return;
            }
            let h = '';
            const start = Math.max(1, state.page - 2);
            const end = Math.min(state.totalPages, state.page + 2);

            if (state.page > 1) h += `<button class="ly-page-btn" onclick="goPage(${state.page - 1})"><i class="fas fa-chevron-left"></i></button>`;
            for (let i = start; i <= end; i++) {
                h += `<button class="ly-page-btn ${i === state.page ? 'active' : ''}" onclick="goPage(${i})">${i}</button>`;
            }
            if (state.page < state.totalPages) h += `<button class="ly-page-btn" onclick="goPage(${state.page + 1})"><i class="fas fa-chevron-right"></i></button>`;
            p.innerHTML = h;
        }

        function goPage(p) {
            state.page = p;
            fetchLoyaltyData();
        }

        async function fetchLoyaltyData() {
            try {
                const q = new URLSearchParams({
                    action: 'bootstrap',
                    page: String(state.page),
                    search: state.search,
                    sort: state.sort,
                    dir: state.dir
                });
                const res = await fetch('api/loyalty.php?' + q.toString());
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');

                fillSettings(data.settings);
                renderLevels(data.levels);
                renderRanking(data.ranking, data.total, data.page, data.total_pages);
            } catch (e) {
                toast('Veriler alinamadi: ' + e.message, true);
            }
        }

        function onSortChange() {
            state.sort = document.getElementById('sortInput').value;
            state.page = 1;
            fetchLoyaltyData();
        }

        function debouncedFetch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                state.search = document.getElementById('searchInput').value.trim();
                state.page = 1;
                fetchLoyaltyData();
            }, 300);
        }

        async function saveSettings() {
            const payload = {
                action: 'save_settings',
                follow_days_step: document.getElementById('follow_days_step').value,
                follow_days_bonus_pct: document.getElementById('follow_days_bonus_pct').value,
                message_step: document.getElementById('message_step').value,
                message_bonus_pct: document.getElementById('message_bonus_pct').value,
                timeout_step: document.getElementById('timeout_step').value,
                timeout_penalty_pct: document.getElementById('timeout_penalty_pct').value,
                ban_step: document.getElementById('ban_step').value,
                ban_penalty_pct: document.getElementById('ban_penalty_pct').value,
                deleted_step: document.getElementById('deleted_step').value,
                deleted_penalty_pct: document.getElementById('deleted_penalty_pct').value,
                subscriber_bonus_pct: document.getElementById('subscriber_bonus_pct').value,
                vip_bonus_pct: document.getElementById('vip_bonus_pct').value
            };

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');
                toast('Ayarlar kaydedildi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Kayit hatasi: ' + e.message, true);
            }
        }

        async function addLevel() {
            const level_name = document.getElementById('new_level_name').value.trim();
            const required_score = document.getElementById('new_required_score').value;
            const min_follow_days = parseInt(document.getElementById('new_min_follow_days').value, 10) || 0;
            if (!level_name || required_score === '') {
                toast('Seviye adi ve skor zorunlu.', true);
                return;
            }

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'add_level', level_name, required_score, min_follow_days })
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');

                document.getElementById('new_level_name').value = '';
                document.getElementById('new_required_score').value = '';
                document.getElementById('new_min_follow_days').value = '0';
                toast('Seviye eklendi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Seviye eklenemedi: ' + e.message, true);
            }
        }

        async function updateLevel(id) {
            const level_name = document.getElementById('lvl_name_' + id).value.trim();
            const required_score = document.getElementById('lvl_score_' + id).value;
            const min_follow_days = parseInt(document.getElementById('lvl_follow_' + id).value, 10) || 0;

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'update_level', id, level_name, required_score, min_follow_days })
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');
                toast('Seviye guncellendi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Seviye guncellenemedi: ' + e.message, true);
            }
        }

        async function deleteLevel(id) {
            if (!confirm('Bu seviyeyi silmek istediginize emin misiniz?')) return;

            try {
                const res = await fetch('api/loyalty.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete_level', id })
                });
                const data = await res.json();
                if (data.status !== 'success') throw new Error(data.message || 'Hata');
                toast('Seviye silindi.');
                fetchLoyaltyData();
            } catch (e) {
                toast('Seviye silinemedi: ' + e.message, true);
            }
        }

        document.getElementById('dirInput').addEventListener('change', function() {
            state.dir = this.value;
            state.page = 1;
            fetchLoyaltyData();
        });

        [
            'follow_days_step', 'follow_days_bonus_pct', 'message_step', 'message_bonus_pct',
            'timeout_step', 'timeout_penalty_pct', 'ban_step', 'ban_penalty_pct',
            'deleted_step', 'deleted_penalty_pct', 'subscriber_bonus_pct', 'vip_bonus_pct'
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', updateLiveExplanations);
            }
        });

        fetchLoyaltyData();
    </script>
</body>
</html>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
