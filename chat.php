<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat - SebastianBot</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <header class="topbar">
            <div class="page-title">Live Chat Moderation</div>
            <div class="user-profile">
                <div class="avatar">
                    <i class="fas fa-robot" style="color: var(--primary-color);"></i>
                </div>
                <span>SebastianBot</span>
            </div>
        </header>

        <div class="chat-dashboard">

            <div class="chat-window">
                <div class="chat-header">
                    <div class="chat-header-title">
                        <div class="live-indicator"></div>
                        <span>Live Chat Stream</span>
                    </div>
                    <div class="chat-header-actions">
                        <button class="send-btn" title="Clear Chat"><i class="fas fa-trash-alt"
                                style="font-size: 14px;"></i></button>
                        <button class="send-btn" title="Pause Chat"><i class="fas fa-pause"
                                style="font-size: 14px;"></i></button>
                    </div>
                </div>

                <div class="chat-messages" id="chatContainer">

                </div>

                <div class="chat-input-container">
                    <div class="chat-input-wrapper">
                        <input type="text" id="chatInput" class="chat-input"
                            placeholder="Sohbete mesaj gönder (Bot olarak)...">
                        <button class="send-btn" onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>

            <div class="events-panel">

                <div class="stats-row" style="margin-bottom: 10px;">
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label"><i class="fas fa-video text-red-500"></i> Durum</span>
                        <span class="stat-value" id="statStatus" style="font-size: 14px;">Çevrimdışı</span>
                    </div>
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label"><i class="fas fa-users text-blue-500"></i> İzleyici</span>
                        <span class="stat-value" id="statViewers" style="font-size: 16px;">0</span>
                    </div>
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label"><i class="fas fa-clock text-green-500"></i> Uptime</span>
                        <span class="stat-value" id="statUptime"
                            style="font-size: 16px; font-variant-numeric: tabular-nums;">00:00:00</span>
                    </div>
                </div>

                <div class="stats-row">
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label" style="font-size: 10px;"><i
                                class="fas fa-comment-dots text-yellow-500"></i> Son 5 Saat Mesaj</span>
                        <span class="stat-value" id="statMsgTotal" style="font-size: 16px;">0</span>
                    </div>
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label" style="font-size: 10px;"><i
                                class="fas fa-user-tag text-purple-500"></i> Farklı Kullanıcı (5s)</span>
                        <span class="stat-value" id="statMsgUsers" style="font-size: 16px;">0</span>
                    </div>
                </div>

                <div class="stats-row" style="margin-top: 10px;">
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label" style="font-size: 10px;"><i class="fas fa-heart text-pink-500"></i>
                            Toplam Takipçi</span>
                        <span class="stat-value" id="statFollowTotal" style="font-size: 16px;">0</span>
                    </div>
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label" style="font-size: 10px;"><i
                                class="fas fa-user-plus text-green-500"></i> Yeni Takip (24s)</span>
                        <span class="stat-value" id="statFollowNew" style="font-size: 16px;">0</span>
                    </div>
                    <div class="stat-box" style="padding: 12px;">
                        <span class="stat-label" style="font-size: 10px;"><i class="fas fa-sync-alt text-blue-500"></i>
                            Tekrar Takip (24s)</span>
                        <span class="stat-value" id="statFollowRe" style="font-size: 16px;">0</span>
                    </div>
                </div>

                <div class="panel-card">
                    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
                        <div><i class="fas fa-bolt" style="color: #eab308;"></i> Son Etkinlikler</div>
                    </div>

                    <div style="padding:0 10px; margin-bottom:15px;">
                        <input type="text" id="eventSearch" oninput="fetchEvents()"
                            placeholder="Kullanıcı veya etkinlik ara..."
                            style="width:100%; padding:8px; border-radius:4px; border:1px solid #3f3f46; background:#18181b; color:white; margin-bottom:10px; font-size:12px;">

                        <div style="display:flex; gap:5px; flex-wrap:wrap; margin-bottom:10px;" id="filterButtons">
                            <button onclick="setEventFilter('all')" class="evt-btn active" data-filter="all"
                                style="flex:1 1 22%; background:#3b82f6; color:white; border:none; padding:4px; border-radius:4px; cursor:pointer; font-size:11px;">Tümü</button>
                            <button onclick="setEventFilter('follow')" class="evt-btn" data-filter="follow"
                                style="flex:1 1 22%; background:#27273a; color:white; border:none; padding:4px; border-radius:4px; cursor:pointer; font-size:11px;">Takip</button>
                            <button onclick="setEventFilter('sub')" class="evt-btn" data-filter="sub"
                                style="flex:1 1 22%; background:#27273a; color:white; border:none; padding:4px; border-radius:4px; cursor:pointer; font-size:11px;">Abone</button>
                            <button onclick="setEventFilter('resub')" class="evt-btn" data-filter="resub"
                                style="flex:1 1 22%; background:#27273a; color:white; border:none; padding:4px; border-radius:4px; cursor:pointer; font-size:11px;">Y. Abone</button>
                            <button onclick="setEventFilter('gift')" class="evt-btn" data-filter="gift"
                                style="flex:1 1 22%; background:#27273a; color:white; border:none; padding:4px; border-radius:4px; cursor:pointer; font-size:11px;">Hediye</button>
                            <button onclick="setEventFilter('kicks')" class="evt-btn" data-filter="kicks"
                                style="flex:1 1 22%; background:#27273a; color:white; border:none; padding:4px; border-radius:4px; cursor:pointer; font-size:11px;">Kicks</button>
                            <button onclick="setEventFilter('host')" class="evt-btn" data-filter="host"
                                style="flex:1 1 22%; background:#27273a; color:white; border:none; padding:4px; border-radius:4px; cursor:pointer; font-size:11px;">Host</button>
                        </div>
                    </div>

                    <div class="event-list" id="eventList" style="max-height: 250px; overflow-y: auto;">
                        <div style="color:#a1a1aa; font-size:13px; text-align:center; padding: 20px 0;">
                            Yeni etkinlikler bekleniyor...
                        </div>
                    </div>
                </div>

                <!-- Aktif Timeoutlar Paneli -->
                <div class="panel-card" style="margin-top: 15px;">
                    <div class="panel-header">
                        <i class="fas fa-user-clock" style="color: #f59e0b;"></i> Aktif Timeoutlar
                    </div>
                    <div class="event-list" id="timeoutList">
                        <div style="color:#a1a1aa; font-size:13px; text-align:center; padding: 20px 0;">
                            Cezalı kullanıcı yok.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="modModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; justify-content:center; align-items:center; backdrop-filter: blur(4px);">
        <div
            style="background:#1e1e2d; border-radius:12px; padding:25px; width:350px; text-align:center; box-shadow:0 10px 25px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1);">
            <h3 id="modalTitle" style="color:white; margin-top:0; font-size:18px; margin-bottom:15px;">Onay</h3>
            <p id="modalDesc" style="color:#a1a1aa; font-size:14px; margin-bottom:20px;">Bu işlemi yapmak istediğinize
                emin misiniz?</p>

            <div id="timeoutInputContainer" style="display:none; margin-bottom:25px; text-align:left;">
                <label style="color:#a1a1aa; font-size:12px; display:block; margin-bottom:5px;">Susturma Süresi:</label>
                <div style="display:flex; gap:10px;">
                    <select id="timeoutDuration"
                        style="background:#27273a; color:white; border:1px solid #3f3f46; border-radius:6px; padding:8px; flex:1; outline:none; font-family:inherit;">
                        <option value="1">1 Dakika</option>
                        <option value="5" selected>5 Dakika</option>
                        <option value="10">10 Dakika</option>
                        <option value="30">30 Dakika</option>
                        <option value="60">1 Saat</option>
                        <option value="1440">1 Gün</option>
                        <option value="custom">Özel Süre</option>
                    </select>
                    <input type="number" id="customTimeoutInput" min="1" max="10080" placeholder="Dakika"
                        style="display:none; background:#27273a; color:white; border:1px solid #3f3f46; border-radius:6px; padding:8px; width:90px; outline:none; font-family:inherit;">
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; gap:10px;">
                <button onclick="closeModal()"
                    style="flex:1; background:#3f3f46; color:white; border:none; padding:10px; border-radius:6px; cursor:pointer; font-weight:bold; transition: background 0.2s;">İptal</button>
                <button onclick="confirmAction()"
                    style="flex:1; background:#00e701; color:black; border:none; padding:10px; border-radius:6px; cursor:pointer; font-weight:bold; transition: opacity 0.2s;">Evet,
                    Onaylıyorum</button>
            </div>
        </div>
    </div>

    <div id="userInfoModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:10000; justify-content:center; align-items:center; backdrop-filter: blur(4px);">
        <div
            style="background:#1e1e2d; padding:20px; border-radius:8px; width:450px; max-height:85vh; border:1px solid rgba(255,255,255,0.1); box-shadow:0 10px 25px rgba(0,0,0,0.5); display:flex; flex-direction:column;">
            <div
                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; border-bottom:1px solid #3f3f46; padding-bottom:10px;">
                <h3 id="uiModalUsername" style="margin:0; color:white; font-size:18px;">Yükleniyor...</h3>
                <button onclick="closeUserInfoModal()"
                    style="background:transparent; border:none; color:#a1a1aa; cursor:pointer; font-size:16px; transition: color 0.2s;"
                    onmouseover="this.style.color='white'" onmouseout="this.style.color='#a1a1aa'"><i
                        class="fas fa-times"></i></button>
            </div>

            <div id="uiModalStats" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:20px;">
                <div style="color:white; grid-column:1/-1; text-align:center;">Yükleniyor...</div>
            </div>

            <h4
                style="color:#a1a1aa; font-size:13px; margin:0 0 10px 0; border-bottom:1px solid #27272a; padding-bottom:5px; display:flex; justify-content:space-between; align-items:center;">
                <span><i class="fas fa-comment-dots"></i> Son Mesajları</span>
                <input type="text" id="uiModalSearch" placeholder="Mesajlarda ara..." oninput="filterUserMessages()"
                    style="background:#27272a; color:white; border:1px solid #3f3f46; padding:4px 8px; border-radius:4px; font-size:11px; outline:none; width:150px;">
            </h4>
            <div id="uiModalMessages"
                style="display:flex; flex-direction:column; gap:8px; overflow-y:auto; flex:1; padding-right:5px; margin-bottom:15px; max-height:200px;">
                
            </div>
            <div id="uiModalActions"
                style="display:flex; flex-direction:column; gap:10px; border-top:1px solid #3f3f46; padding-top:15px; align-items:stretch;">
                
            </div>
        </div>
    </div>

    <style>
        .message.system {
            background-color: rgba(245, 158, 11, 0.05);
            border-left: 3px solid #f59e0b;
            font-style: italic;
            font-size: 13px;
            color: #d4d4d8;
            padding: 8px 14px;
        }
        .message.system i {
            margin-right: 8px;
            color: #f59e0b;
        }
    </style>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        const chatContainer = document.getElementById('chatContainer');
        const CHATROOM_ID = YOURCHATROOMID; // Senin özel chatroom ID'n
        let lastMessageId = 0;
        let localRoles = {}; // VIP, OG, Mod rollerini saklar

        async function fetchLocalRoles() {
            try {
                const res = await fetch('api/get_roles.php');
                const data = await res.json();
                if (data.status === 'success') {
                    data.data.forEach(u => {
                        localRoles[u.user_id] = {
                            vip: u.is_vip == 1,
                            og: u.is_og == 1,
                            moderator: u.is_moderator == 1
                        };
                    });
                }
            } catch(e) {}
        }
        fetchLocalRoles();

        function renderBadges(badgesStr, userId) {
            try {
                const badges = typeof badgesStr === 'string' ? JSON.parse(badgesStr) : badgesStr;
                let html = '';
                const userRoles = localRoles[userId] || {};

                // Kick API'den gelen rozetleri takip et (duplikasyon önleme)
                const kickHas = { broadcaster: false, moderator: false, vip: false, subscriber: false, og: false };

                if (Array.isArray(badges)) {
                    badges.forEach(badge => {
                        if (badge.type === 'broadcaster') {
                            kickHas.broadcaster = true;
                            html += `<span class="user-badge badge-mod" style="background:#ef4444;" title="Yayıncı"><i class="fas fa-video"></i></span> `;
                        }
                        if (badge.type === 'moderator') {
                            kickHas.moderator = true;
                            html += `<span class="user-badge badge-mod" title="Moderatör"><i class="fas fa-shield-halved"></i></span> `;
                        }
                        if (badge.type === 'vip') {
                            kickHas.vip = true;
                            html += `<span class="user-badge badge-vip" title="VIP"><i class="fas fa-gem"></i></span> `;
                        }
                        if (badge.type === 'og') {
                            kickHas.og = true;
                            html += `<span class="user-badge" style="background:#d97706; color:#fff; padding:2px 5px; border-radius:4px; font-size:10px; font-weight:700;" title="OG">OG</span> `;
                        }
                        if (badge.type === 'subscriber' || badge.type === 'founder') {
                            kickHas.subscriber = true;
                            html += `<span class="user-badge badge-sub" title="${badge.type === 'founder' ? 'Kurucu Abone' : 'Abone'}">★</span> `;
                        }
                    });
                }

                // Lokal DB rolleri — Kick'ten gelmediyse ekle
                if (userRoles.moderator && !kickHas.moderator) {
                    html += `<span class="user-badge badge-mod" title="Moderatör"><i class="fas fa-shield-halved"></i></span> `;
                }
                if (userRoles.vip && !kickHas.vip) {
                    html += `<span class="user-badge badge-vip" title="VIP"><i class="fas fa-gem"></i></span> `;
                }
                if (userRoles.og && !kickHas.og) {
                    html += `<span class="user-badge" style="background:#d97706; color:#fff; padding:2px 5px; border-radius:4px; font-size:10px; font-weight:700;" title="OG">OG</span> `;
                }

                return html;
            } catch (e) {
                return '';
            }
        }

        // Yeni mesajı UI'a ekler
        function appendMessage(msg) {
            let dateObj;
            if (msg.created_at.includes('T') || msg.created_at.includes('+') || msg.created_at.includes('Z')) {
                dateObj = new Date(msg.created_at);
            } else {
                dateObj = new Date(msg.created_at.replace(' ', 'T') + 'Z');
            }
            
            const time = dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const badgesHtml = renderBadges(msg.sender_badges, msg.sender_id);

            // Mesaj özel tasarımı
            let messageClass = "message";
            let nameColor = "#6ee7b7"; // Varsayılan renk

            const msgUUID = msg.message_id || msg.id;

            // XSS koruması ve linkleri tıklanabilir yapma
            const div = document.createElement('div');
            div.textContent = msg.content;
            let parsedContent = div.innerHTML;

            const urlRegex = /(https?:\/\/[^\s]+)/g;
            parsedContent = parsedContent.replace(urlRegex, function (url) {
                return '<a href="' + url + '" target="_blank" style="color: #3b82f6; text-decoration: underline;">' + url + '</a>';
            });

            const messageHtml = `
                <div class="${messageClass}" id="msg-${msgUUID}">
                    <div class="message-header">
                        <span class="timestamp">${time}</span>
                        ${badgesHtml}
                        <span class="username" style="color: ${nameColor}; cursor: pointer; text-decoration: underline;" onclick="openUserInfoModal('${msg.sender_id}')" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">${msg.sender_username}</span>
                        
                        <div class="mod-actions" style="display: inline-block; margin-left: 10px; opacity: 0.7;">
                            <button onclick="openModal('delete', '${msgUUID}', null)" title="Mesajı Sil" style="background:transparent; border:none; color:#ef4444; cursor:pointer; font-size:12px; padding:0 3px; transition: color 0.2s;"><i class="fas fa-trash"></i></button>
                            <button onclick="openModal('timeout', null, '${msg.sender_id}')" title="5 Dk Sustur" style="background:transparent; border:none; color:#f59e0b; cursor:pointer; font-size:12px; padding:0 3px; transition: color 0.2s;"><i class="fas fa-clock"></i></button>
                            <button onclick="openModal('ban', null, '${msg.sender_id}')" title="Kalıcı Banla" style="background:transparent; border:none; color:#dc2626; cursor:pointer; font-size:12px; padding:0 3px; transition: color 0.2s;"><i class="fas fa-ban"></i></button>
                        </div>
                    </div>
                    <div class="message-content">
                        ${parsedContent}
                    </div>
                </div>
            `;

            chatContainer.insertAdjacentHTML('beforeend', messageHtml);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Sistem mesajlarını UI'a ekler
        function appendSystemMessage(content, icon = 'info-circle') {
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const msgUUID = 'sys-' + Date.now();
            
            const messageHtml = `
                <div class="message system" id="msg-${msgUUID}">
                    <div class="message-header">
                        <span class="timestamp">${time}</span>
                    </div>
                    <div class="message-content">
                        <i class="fas fa-${icon}"></i> ${content}
                    </div>
                </div>
            `;

            chatContainer.insertAdjacentHTML('beforeend', messageHtml);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        let currentModAction = null;
        let currentModMsgId = null;
        let currentModUserId = null;

        const modModal = document.getElementById('modModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalDesc = document.getElementById('modalDesc');
        const timeoutContainer = document.getElementById('timeoutInputContainer');
        const timeoutSelect = document.getElementById('timeoutDuration');
        const customTimeoutInput = document.getElementById('customTimeoutInput');

        // Select değiştiğinde özel süre kutusunu göster/gizle
        timeoutSelect.addEventListener('change', function (e) {
            if (e.target.value === 'custom') {
                customTimeoutInput.style.display = 'block';
                customTimeoutInput.focus();
            } else {
                customTimeoutInput.style.display = 'none';
            }
        });

        function openModal(action, messageId, userId) {
            currentModAction = action;
            currentModMsgId = messageId;
            currentModUserId = userId;

            // Timeout seçeneği açıldıysa süreyi sıfırla ve göster
            if (action === 'timeout') {
                timeoutContainer.style.display = 'block';
                timeoutSelect.value = '5'; // Varsayılan 5 dk
                customTimeoutInput.style.display = 'none';
                customTimeoutInput.value = '';
            } else {
                timeoutContainer.style.display = 'none';
            }

            if (action === 'delete') {
                modalTitle.innerHTML = '<i class="fas fa-trash" style="color:#ef4444;"></i> Mesajı Sil';
                modalDesc.innerText = 'Bu mesajı chatten silmek istediğinize emin misiniz?';
            } else if (action === 'timeout') {
                modalTitle.innerHTML = '<i class="fas fa-clock" style="color:#f59e0b;"></i> Kullanıcıyı Sustur';
                modalDesc.innerText = 'Kullanıcı chatten ne kadar süre uzaklaştırılsın?';
            } else if (action === 'ban') {
                modalTitle.innerHTML = '<i class="fas fa-ban" style="color:#dc2626;"></i> Kullanıcıyı Banla';
                modalDesc.innerText = 'Bu kullanıcıyı kanaldan KALICI OLARAK banlamak istediğinize emin misiniz?';
            } else if (action === 'unban') {
                modalTitle.innerHTML = '<i class="fas fa-unlock" style="color:#10b981;"></i> Cezayı Kaldır';
                modalDesc.innerText = 'Kullanıcının cezasını kaldırmak istediğinize emin misiniz?';
            }

            modModal.style.display = 'flex';
        }

        function closeModal() {
            modModal.style.display = 'none';
        }

        async function confirmAction() {
            const action = currentModAction;
            const messageId = currentModMsgId;
            const userId = currentModUserId;

            // Timeout süresini hesapla
            let finalDuration = null;
            if (action === 'timeout') {
                if (timeoutSelect.value === 'custom') {
                    finalDuration = parseInt(customTimeoutInput.value);
                    if (!finalDuration || finalDuration < 1) finalDuration = 5; // Hatalı girilirse 5 dk yap
                } else {
                    finalDuration = parseInt(timeoutSelect.value);
                }
            }

            closeModal(); // Modalı hemen kapat

            try {
                let url = '';
                let body = {};

                if (action === 'delete') {
                    url = 'api/delete_message.php';
                    body = { message_id: messageId };
                } else if (action === 'timeout' || action === 'ban') {
                    url = 'api/ban_user.php';
                    body = {
                        user_id: userId,
                        duration: action === 'timeout' ? finalDuration : null
                    };
                } else if (action === 'unban') {
                    url = 'api/remove_timeout.php';
                    body = { user_id: userId };
                }

                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    if (action === 'delete') {
                        const el = document.getElementById('msg-' + messageId);
                        if (el) {
                            el.style.opacity = '0.5';
                            el.querySelector('.message-content').innerText = '<Mesaj silindi>';
                        }
                    }
                    if (action === 'unban' || action === 'timeout') {
                        fetchActiveTimeouts();
                    }
                    console.log('Başarılı:', action);
                } else {
                    console.error('Hata:', result.message);
                }
            } catch (error) {
                console.error('İşlem başarısız!', error);
            }
        }

        // Sağ paneldeki Son Etkinlikler listesini günceller
        const eventList = document.getElementById('eventList');
        const timeoutList = document.getElementById('timeoutList');
        let currentEventFilter = 'all';

        function setEventFilter(filterType) {
            currentEventFilter = filterType;

            // Butonların renklerini ayarla
            const buttons = document.querySelectorAll('.evt-btn');
            buttons.forEach(btn => {
                if (btn.dataset.filter === filterType) {
                    btn.style.background = '#3b82f6'; // Aktif mavi
                } else {
                    btn.style.background = '#27273a'; // Pasif gri
                }
            });

            fetchEvents();
        }

        async function fetchEvents() {
            try {
                const response = await fetch('api/get_events.php');
                const data = await response.json();

                if (data.status === 'success') {
                    eventList.innerHTML = ''; // Listeyi temizle
                    const searchTerm = document.getElementById('eventSearch').value.toLowerCase();

                    let filteredData = currentEventFilter === 'all'
                        ? data.data
                        : data.data.filter(evt => evt.event_type === currentEventFilter);

                    // Arama kelimesine göre filtrele
                    if (searchTerm) {
                        filteredData = filteredData.filter(evt =>
                            evt.username.toLowerCase().includes(searchTerm) ||
                            evt.description.toLowerCase().includes(searchTerm)
                        );
                    }

                    if (filteredData.length === 0) {
                        eventList.innerHTML = '<div style="color:#a1a1aa; font-size:13px; text-align:center; padding: 20px 0;">Kayıt yok.</div>';
                        return;
                    }

                    filteredData.forEach(evt => {
                        let cls = evt.event_type;
                        if (cls === 'follow' && evt.description && evt.description.includes('tekrar')) {
                            cls = 'refollow';
                        }
                        
                        let iconHtml = '';
                        if (cls === 'follow') {
                            iconHtml = '<i class="fas fa-heart" style="color:#34d399"></i>';
                        } else if (cls === 'refollow') {
                            iconHtml = '<i class="fas fa-user-plus" style="color:#fbbf24"></i>';
                        } else if (cls === 'sub') {
                            iconHtml = '<i class="fas fa-star" style="color:#60a5fa"></i>';
                        } else if (cls === 'resub') {
                            iconHtml = '<i class="fas fa-star-half-stroke" style="color:#38bdf8"></i>';
                        } else if (cls === 'gift') {
                            iconHtml = '<i class="fas fa-gift" style="color:#c084fc"></i>';
                        } else if (cls === 'kicks') {
                            iconHtml = '<i class="fas fa-coins" style="color:#10b981"></i>';
                        } else if (cls === 'host') {
                            iconHtml = '<i class="fas fa-satellite-dish" style="color:#f43f5e"></i>';
                        } else {
                            iconHtml = '<i class="fas fa-bell" style="color:#a1a1aa"></i>';
                        }

                        const d = new Date(evt.created_at);
                        d.setHours(d.getHours() + 1);
                        const timeText = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                        const eventHtml = `
                            <div class="event-item ${cls}" style="animation: fadeIn 0.5s;">
                                <div class="event-icon">${iconHtml}</div>
                                <div class="event-details">
                                    <div class="event-user">${evt.username}</div>
                                    <div class="event-desc">${evt.description}</div>
                                </div>
                                <div class="event-time">${timeText}</div>
                            </div>
                        `;

                        eventList.insertAdjacentHTML('beforeend', eventHtml);
                    });
                }
            } catch (error) {
                console.error("Etkinlikler çekilemedi:", error);
            }
        }

        // Aktif Timeoutları çeker ve kalan süreyi hesaplar
        async function fetchActiveTimeouts() {
            try {
                const response = await fetch('api/get_active_timeouts.php');
                const data = await response.json();

                if (data.status === 'success') {
                    if (data.data.length === 0) {
                        timeoutList.innerHTML = '<div style="color:#a1a1aa; font-size:13px; text-align:center; padding: 20px 0;">Cezalı kullanıcı yok.</div>';
                        return;
                    }

                    timeoutList.innerHTML = '';

                    data.data.forEach(t => {
                        const remainingSecs = parseInt(t.remaining_seconds, 10);
                        if (remainingSecs <= 0) return;

                        const diffMins = Math.floor(remainingSecs / 60);
                        const diffSecs = remainingSecs % 60;

                        const timeStr = `${diffMins.toString().padStart(2, '0')}:${diffSecs.toString().padStart(2, '0')}`;

                        const html = `
                            <div class="event-item" style="justify-content:space-between; padding: 10px 15px; border-left: 3px solid #f59e0b; background: rgba(245, 158, 11, 0.05); margin-bottom: 5px;">
                                <div style="display:flex; flex-direction:column; gap:3px;">
                                    <span style="color:#e4e4e7; font-size:14px; font-weight:bold; cursor:pointer;" onclick="openUserInfoModal('${t.user_id}')" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#e4e4e7'">${t.username}</span>
                                    <span style="color:#a1a1aa; font-size:11px;">Susturuldu</span>
                                </div>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <span style="color:#ef4444; font-size:14px; font-weight:bold; font-variant-numeric:tabular-nums; background: rgba(239, 68, 68, 0.1); padding: 4px 8px; border-radius: 4px;">
                                        <i class="fas fa-stopwatch" style="margin-right:4px;"></i>${timeStr}
                                    </span>
                                    <button onclick="openModal('unban', null, '${t.user_id}')" title="Cezayı Kaldır" style="background:#10b981; border:none; color:white; cursor:pointer; padding: 6px 10px; border-radius: 4px; font-size: 12px; transition: 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                        <i class="fas fa-unlock"></i> Aç
                                    </button>
                                </div>
                            </div>
                        `;
                        timeoutList.insertAdjacentHTML('beforeend', html);
                    });
                }
            } catch (error) {
                console.error("Timeoutlar çekilemedi:", error);
            }
        }

        async function fetchHistory() {
            try {
                const response = await fetch('api/get_messages.php?last_id=0');
                const data = await response.json();

                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(msg => {
                        appendMessage(msg);
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                }
            } catch (error) {
                console.error("Geçmiş çekilemedi:", error);
            }
        }

        let streamStartTime = null;

        async function fetchStreamStats() {
            try {
                const response = await fetch('api/get_stream_stats.php');
                const data = await response.json();

                if (data.status === 'success') {
                    document.getElementById('statMsgTotal').innerText = data.stats.total_messages;
                    document.getElementById('statMsgUsers').innerText = data.stats.unique_users;
                    document.getElementById('statFollowTotal').innerText = data.stats.total_followers;
                    document.getElementById('statFollowNew').innerText = data.stats.new_followers_24h;
                    document.getElementById('statFollowRe').innerText = data.stats.refollowers_24h;

                    const s = data.stream;
                    if (s.is_live) {
                        document.getElementById('statStatus').innerHTML = '<span style="color:#10b981;">Yayında</span><br><span style="font-size:11px;color:#a1a1aa;">' + s.category + '</span>';
                        document.getElementById('statViewers').innerText = s.viewers;
                        streamStartTime = s.started_at ? new Date(s.started_at) : new Date();
                    } else {
                        document.getElementById('statStatus').innerHTML = '<span style="color:#ef4444;">Çevrimdışı</span>';
                        document.getElementById('statViewers').innerText = '0';
                        document.getElementById('statUptime').innerText = '00:00:00';
                        streamStartTime = null;
                    }
                }
            } catch (err) {
                console.error("Stream stats error:", err);
            }
        }

        // Başlangıçta verileri çek
        fetchHistory();
        fetchEvents();
        fetchActiveTimeouts();
        fetchStreamStats();

        // Her 5 saniyede bir yeni etkinlikleri kontrol et
        setInterval(() => {
            fetchEvents();
        }, 5000);

        // Her 60 saniyede bir yayın verilerini güncelle
        setInterval(() => {
            fetchStreamStats();
        }, 60000);

        // Her saniye aktif timeout sayaçlarını ve uptime'ı güncelle
        setInterval(() => {
            fetchActiveTimeouts();
            if (streamStartTime) {
                const diff = Math.floor((new Date() - streamStartTime) / 1000);
                if (diff >= 0) {
                    const h = Math.floor(diff / 3600);
                    const m = Math.floor((diff % 3600) / 60);
                    const s = diff % 60;
                    document.getElementById('statUptime').innerText =
                        `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                }
            }
        }, 1000);
        // BURAYI KENDİ CHATROOM ID'N İLE DEĞİŞTİR
        const pusher = new Pusher('PUSHERIDNIZ', {
            cluster: 'us2',
            forceTLS: true
        });

        const chatChannel = pusher.subscribe(`chatrooms.${CHATROOM_ID}.v2`);

        chatChannel.bind('App\\Events\\ChatMessageEvent', function (data) {
            const badgesStr = JSON.stringify(data.sender.identity ? data.sender.identity.badges : []);
            const msgObj = {
                id: data.id,
                created_at: data.created_at,
                sender_id: data.sender.id,
                sender_username: data.sender.username,
                sender_badges: badgesStr,
                content: data.content
            };

            appendMessage(msgObj);

            fetch('api/save_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: data.id,
                    sender_id: data.sender.id,
                    sender_username: data.sender.username,
                    sender_badges: badgesStr,
                    content: data.content,
                    created_at: data.created_at
                })
            }).catch(e => console.error("Kaydetme hatası:", e));
        });

        // 2. Moderasyon Etkinlikleri (Ban/Timeout/Unban)
        chatChannel.bind('App\\Events\\UserBannedEvent', function (data) {
            console.log('UserBannedEvent:', data);
            const expiresAt = data.expires_at ? new Date(data.expires_at) : null;
            const createdAt = new Date(); // Şu anki zamanı baz al
            
            let durationText = '';
            let action = 'kalıcı olarak banlandı';
            let icon = 'ban';

            if (expiresAt) {
                const diffMs = expiresAt - createdAt;
                const diffMins = Math.max(1, Math.round(diffMs / 60000));
                durationText = `${diffMins} dakika süreliğine `;
                action = 'sohbetten uzaklaştırıldı (timeout)';
                icon = 'clock';
            }

            const modName = data.banned_by ? data.banned_by.username : 'Sistem';
            const targetUser = data.user || { id: data.user_id, username: data.username || data.user_username };
            const content = `<strong>${modName}</strong>, <strong>${targetUser.username}</strong> isimli kullanıcıyı ${durationText}${action}.`;
            appendSystemMessage(content, icon);
            
            fetch('api/save_moderation.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    user_id: targetUser.id,
                    username: targetUser.username,
                    action_type: expiresAt ? 'timeout' : 'ban',
                    expires_at: data.expires_at, // ISO formatında
                    mod_name: modName,
                    reason: 'Kick Chat Action'
                })
            }).then(r => r.json()).then(res => {
                console.log("Moderasyon Kayıt Yanıtı:", res);
                // Kayıt sonrası listeyi güncelle
                setTimeout(fetchActiveTimeouts, 500); 
            });
        });

        chatChannel.bind('App\\Events\\UserUnbannedEvent', function (data) {
            console.log('UserUnbannedEvent:', data);
            const modName = data.unbanned_by ? data.unbanned_by.username : 'Sistem';
            const targetUser = data.user || { id: data.user_id, username: data.username || data.user_username };
            const content = `<strong>${modName}</strong>, <strong>${targetUser.username}</strong> isimli kullanıcının yasağını kaldırdı.`;
            appendSystemMessage(content, 'unlock');
            
            // Veritabanında yasağı kaldır
            fetch('api/save_moderation.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    user_id: targetUser.id,
                    action_type: 'unban'
                })
            }).then(r => r.json()).then(res => {
                console.log("Unban Kayıt Yanıtı:", res);
                setTimeout(fetchActiveTimeouts, 500); 
            });
        });

        chatChannel.bind('App\\Events\\MessageDeletedEvent', function (data) {
            console.log('MessageDeletedEvent:', data);
            const el = document.getElementById('msg-' + data.message.id);
            if (el) {
                el.style.opacity = '0.5';
                const contentEl = el.querySelector('.message-content');
                if (contentEl) contentEl.innerText = '<Mesaj silindi>';
            }
        });

        async function sendChatMessage() {
            const input = document.getElementById('chatInput');
            const content = input.value.trim();

            if (content !== '') {
                input.value = '';

                try {
                    const response = await fetch('api/send_message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ content: content })
                    });

                    const result = await response.json();

                    if (result.status !== 'success') {
                        console.error('Mesaj gönderilemedi:', result);
                        alert('Mesaj gönderilemedi. Hata: ' + (result.message || 'Bilinmeyen hata'));
                    }
                } catch (error) {
                    console.error('API isteği başarısız:', error);
                }
            }
        }

        document.getElementById('chatInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                sendChatMessage();
            }
        });

        let uiModalTimer = null;

        function filterUserMessages() {
            const term = document.getElementById('uiModalSearch').value.toLowerCase();
            const msgs = document.querySelectorAll('.ui-modal-msg-item');
            msgs.forEach(m => {
                if (m.innerText.toLowerCase().includes(term)) m.style.display = 'block';
                else m.style.display = 'none';
            });
        }

        function openUserInfoModal(userId) {
            document.getElementById('userInfoModal').style.display = 'flex';
            document.getElementById('uiModalStats').innerHTML = '<div style="color:white; grid-column:1/-1; text-align:center;">Yükleniyor...</div>';
            document.getElementById('uiModalMessages').innerHTML = '';
            document.getElementById('uiModalUsername').innerText = 'Yükleniyor...';
            document.getElementById('uiModalActions').innerHTML = '';
            document.getElementById('uiModalSearch').value = '';

            if (uiModalTimer) clearInterval(uiModalTimer);

            fetch('api/get_user_info.php?user_id=' + userId)
                .then(r => r.json())
                .then(data => {
                    if (data.status !== 'success') return;

                    const u = data.user;
                    document.getElementById('uiModalUsername').innerText = u.username;

                    let statusHtml = '<span style="color:#10b981;"><i class="fas fa-check-circle"></i> Temiz</span>';
                    let isTimeout = false;
                    let remaining = 0;

                    if (u.is_banned == 1) {
                        statusHtml = '<span style="color:#ef4444;"><i class="fas fa-ban"></i> Kalıcı Banlı</span>';
                    } else if (u.remaining_seconds && parseInt(u.remaining_seconds, 10) > 0) {
                        statusHtml = '<span style="color:#f59e0b;" id="uiModalStatusTimer"><i class="fas fa-clock"></i> Susturulmuş</span>';
                        isTimeout = true;
                        remaining = parseInt(u.remaining_seconds, 10);
                    }

                    let followHtml = 'Yayın Kapalıyken';
                    if (u.follow_date) {
                        const fd = new Date(u.follow_date);
                        followHtml = fd.toLocaleDateString() + ' ' + fd.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        if (u.followed_during_stream == 1) {
                            const cat = u.followed_stream_category || 'Yayındayken';
                            const title = u.followed_stream_title ? ` title="${u.followed_stream_title}"` : '';
                            followHtml += `<br><span style="font-size:10px; color:#f59e0b; margin-top:2px; display:inline-block;"${title}><i class="fas fa-video"></i> ${cat}</span>`;
                        } else {
                            followHtml += `<br><span style="font-size:10px; color:#a1a1aa; margin-top:2px; display:inline-block;"><i class="fas fa-moon"></i> Yayın Kapalıyken</span>`;
                        }
                    } else {
                        followHtml = 'Bilinmiyor';
                    }

                    document.getElementById('uiModalStats').innerHTML = `
                        <div style="background:#27272a; padding:10px; border-radius:6px; display:flex; flex-direction:column; gap:4px;">
                            <div style="font-size:11px; color:#a1a1aa;">Durum</div>
                            <div style="font-size:13px; font-weight:bold;">${statusHtml}</div>
                        </div>
                        <div style="background:#27272a; padding:10px; border-radius:6px; display:flex; flex-direction:column; gap:4px;">
                            <div style="font-size:11px; color:#a1a1aa;">Takip Tarihi</div>
                            <div style="font-size:13px; color:white; font-weight:bold;">${followHtml}</div>
                        </div>
                        <div style="background:#27272a; padding:10px; border-radius:6px; display:flex; flex-direction:column; gap:4px;">
                            <div style="font-size:11px; color:#a1a1aa;">Toplam Mesaj</div>
                            <div style="font-size:13px; color:white; font-weight:bold;">${u.message_count}</div>
                        </div>
                        <div style="background:#27272a; padding:10px; border-radius:6px; display:flex; flex-direction:column; gap:4px;">
                            <div style="font-size:11px; color:#a1a1aa;">Silinen Mesaj</div>
                            <div style="font-size:13px; color:#ef4444; font-weight:bold;">${u.deleted_message_count}</div>
                        </div>
                        <div style="background:#27272a; padding:10px; border-radius:6px; display:flex; flex-direction:column; gap:4px;">
                            <div style="font-size:11px; color:#a1a1aa;">Toplam Timeout</div>
                            <div style="font-size:13px; color:#f59e0b; font-weight:bold;">${u.timeout_count}</div>
                        </div>
                        <div style="background:#27272a; padding:10px; border-radius:6px; display:flex; flex-direction:column; gap:4px;">
                            <div style="font-size:11px; color:#a1a1aa;">Toplam Ban</div>
                            <div style="font-size:13px; color:#ef4444; font-weight:bold;">${u.ban_count}</div>
                        </div>
                    `;

                    // Butonları Ayarla
                    let actionsHtml = '';
                    
                    const isVip = u.is_vip == 1;
                    const isOg = u.is_og == 1;
                    const isMod = u.is_moderator == 1;
                    
                    actionsHtml += `
                        <div style="display:flex; gap:10px; width:100%; border-bottom:1px solid #3f3f46; padding-bottom:10px;">
                            <button onclick="toggleRole('${userId}', '${u.username}', 'vip', '${isVip ? 'remove' : 'add'}')" style="flex:1; background:${isVip ? '#3f3f46' : '#eab308'}; color:white; border:none; padding:8px; border-radius:6px; cursor:pointer; font-size:12px; font-weight:bold;"><i class="fas fa-star"></i> ${isVip ? 'VIP Kaldır' : 'VIP Yap'}</button>
                            <button onclick="toggleRole('${userId}', '${u.username}', 'og', '${isOg ? 'remove' : 'add'}')" style="flex:1; background:${isOg ? '#3f3f46' : '#d97706'}; color:white; border:none; padding:8px; border-radius:6px; cursor:pointer; font-size:12px; font-weight:bold;"><i class="fas fa-crown"></i> ${isOg ? 'OG Kaldır' : 'OG Yap'}</button>
                            <button onclick="toggleRole('${userId}', '${u.username}', 'moderator', '${isMod ? 'remove' : 'add'}')" style="flex:1; background:${isMod ? '#3f3f46' : '#3b82f6'}; color:white; border:none; padding:8px; border-radius:6px; cursor:pointer; font-size:12px; font-weight:bold;"><i class="fas fa-sword"></i> ${isMod ? 'Moderatör Kaldır' : 'Moderatör Yap'}</button>
                        </div>
                    `;

                    actionsHtml += '<div style="display:flex; gap:10px; width:100%;">';
                    if (u.is_banned == 1 || isTimeout) {
                        actionsHtml += `
                            <button onclick="closeUserInfoModal(); openModal('unban', null, '${userId}')" style="flex:1; background:#10b981; color:white; border:none; padding:10px; border-radius:6px; cursor:pointer; font-weight:bold; transition: background 0.2s;"><i class="fas fa-unlock"></i> Cezayı Kaldır</button>
                        `;
                    } else {
                        actionsHtml += `
                            <button onclick="closeUserInfoModal(); openModal('timeout', null, '${userId}')" style="flex:1; background:#f59e0b; color:white; border:none; padding:10px; border-radius:6px; cursor:pointer; font-weight:bold; transition: background 0.2s;"><i class="fas fa-clock"></i> Timeout At</button>
                            <button onclick="closeUserInfoModal(); openModal('ban', null, '${userId}')" style="flex:1; background:#ef4444; color:white; border:none; padding:10px; border-radius:6px; cursor:pointer; font-weight:bold; transition: background 0.2s;"><i class="fas fa-ban"></i> Banla</button>
                        `;
                    }
                    actionsHtml += '</div>';
                    
                    document.getElementById('uiModalActions').innerHTML = actionsHtml;

                    // Canlı Sayaç
                    if (isTimeout) {
                        const updateTimer = () => {
                            if (remaining <= 0) {
                                document.getElementById('uiModalStatusTimer').innerHTML = '<i class="fas fa-check-circle"></i> Süre Bitti';
                                clearInterval(uiModalTimer);
                                return;
                            }
                            const m = Math.floor(remaining / 60);
                            const s = remaining % 60;
                            document.getElementById('uiModalStatusTimer').innerHTML = `<i class="fas fa-stopwatch"></i> ${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                            remaining--;
                        };
                        updateTimer();
                        uiModalTimer = setInterval(updateTimer, 1000);
                    }

                    let msgsHtml = '';
                    if (data.messages.length === 0) {
                        msgsHtml = '<div style="color:#a1a1aa; font-size:13px; text-align:center; padding: 20px 0;">Mesaj geçmişi yok.</div>';
                    } else {
                        data.messages.forEach(m => {
                            const t = new Date(m.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            msgsHtml += `
                                <div class="ui-modal-msg-item" style="background:#27272a; padding:10px; border-radius:6px; font-size:13px; border-left:3px solid #3b82f6;">
                                    <span style="color:#a1a1aa; font-size:11px; margin-right:8px;">[${t}]</span>
                                    <span style="color:#e4e4e7;">${m.content}</span>
                                </div>
                            `;
                        });
                    }
                    document.getElementById('uiModalMessages').innerHTML = msgsHtml;
                })
                .catch(err => console.error("Kullanıcı bilgisi çekilemedi:", err));
        }

        function closeUserInfoModal() {
            document.getElementById('userInfoModal').style.display = 'none';
            if (uiModalTimer) clearInterval(uiModalTimer);
        }

        async function toggleRole(userId, username, role, action) {
            try {
                const res = await fetch('api/toggle_role.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ user_id: userId, username: username, role: role, action: action })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    if (!localRoles[userId]) localRoles[userId] = {};
                    localRoles[userId][role] = (action === 'add');
                    
                    // Reload modal directly to show updated buttons
                    openUserInfoModal(userId);
                } else {
                    alert('Hata: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                alert('Rol güncellenirken bir hata oluştu.');
            }
        }
    </script>
</body>

</html>