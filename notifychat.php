<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bildirim Mesajları - SebastianBot</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
        }

        .event-card {
            background: var(--bg-panel);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .event-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .event-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .event-variables {
            font-size: 12px;
            color: var(--text-muted);
            background: rgba(255,255,255,0.03);
            padding: 8px;
            border-radius: 6px;
        }

        .event-variables code {
            color: #60a5fa;
            background: rgba(96, 165, 250, 0.1);
            padding: 2px 4px;
            border-radius: 4px;
            font-family: monospace;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-input {
            background: #09090b;
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 10px;
            border-radius: 6px;
            font-family: inherit;
            resize: vertical;
            min-height: 80px;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            background: var(--primary-color);
            color: #000;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:hover { opacity: 0.9; }

        .switch-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--border-color);
            transition: .4s;
            border-radius: 20px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 14px;
            width: 14px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider { background-color: var(--primary-color); }
        input:checked + .slider:before { transform: translateX(20px); }

    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <div>
                <h1 class="page-title">Bildirim Mesajları</h1>
                <div style="color: var(--text-muted); font-size: 14px; margin-top: 5px;">
                    Kanalda gerçekleşen olaylar sonrası botun chate otomatik yazacağı mesajları düzenleyin.
                </div>
            </div>
        </div>

        <div class="events-grid" id="eventsGrid">
        </div>
    </div>

    <div id="toastContainer" style="position:fixed; bottom:20px; right:20px; display:flex; flex-direction:column; gap:10px; z-index:9999;"></div>

    <script>
        const EVENT_TYPES = [
            {
                id: 'follow',
                title: 'Yeni Takipçi',
                icon: 'fa-user-plus',
                variables: ['{username}'],
                default_msg: 'Hoş geldin @{username}, takip ettiğin için teşekkürler!'
            },
            {
                id: 'refollow',
                title: 'Tekrar Takip Eden',
                icon: 'fa-user-check',
                variables: ['{username}'],
                default_msg: 'Tekrardan hoş geldin @{username}!'
            },
            {
                id: 'subscription',
                title: 'Yeni Abone',
                icon: 'fa-star',
                variables: ['{username}', '{months}'],
                default_msg: 'Aramıza hoş geldin @{username}! {months} aylık aboneliğin için teşekkürler.'
            },
            {
                id: 'resubscription',
                title: 'Abonelik Yenileme',
                icon: 'fa-sync-alt',
                variables: ['{username}', '{months}'],
                default_msg: '@{username} aboneliğini yeniledi! ({months}. ay) Desteğin için teşekkürler!'
            },
            {
                id: 'kicks',
                title: 'Kicks Bağışı',
                icon: 'fa-coins',
                variables: ['{username}', '{amount}'],
                default_msg: '@{username} {amount} Kicks gönderdi! Kesene bereket!'
            },
            {
                id: 'timeout',
                title: 'Timeout Atılınca',
                icon: 'fa-clock',
                variables: ['{username}', '{duration}', '{reason}'],
                default_msg: '@{username} {duration} saniyeliğine susturuldu. Sebep: {reason}'
            },
            {
                id: 'untimeout',
                title: 'Timeout Kaldırılınca',
                icon: 'fa-comment-dots',
                variables: ['{username}'],
                default_msg: '@{username} adlı kullanıcının cezası kaldırıldı.'
            },
            {
                id: 'ban',
                title: 'Banlanınca',
                icon: 'fa-gavel',
                variables: ['{username}', '{reason}'],
                default_msg: '@{username} kanaldan yasaklandı. Sebep: {reason}'
            },
            {
                id: 'unban',
                title: 'Ban Kaldırılınca',
                icon: 'fa-unlock',
                variables: ['{username}'],
                default_msg: '@{username} adlı kullanıcının banı kaldırıldı.'
            }
        ];

        let dbNotifications = [];

        async function fetchNotifications() {
            try {
                const res = await fetch('api/notifications.php');
                const data = await res.json();
                if (data.status === 'success') {
                    dbNotifications = data.data;
                    renderEvents();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderEvents() {
            const grid = document.getElementById('eventsGrid');
            grid.innerHTML = '';

            EVENT_TYPES.forEach(event => {
                const dbEntry = dbNotifications.find(n => n.event_type === event.id);
                const isActive = dbEntry ? dbEntry.is_active == 1 : true;
                const message = dbEntry ? dbEntry.message_template : event.default_msg;

                const varsHtml = event.variables.map(v => `<code>${v}</code>`).join(' ');

                const cardHtml = `
                    <div class="event-card">
                        <div class="event-card-header">
                            <div class="event-title"><i class="fas ${event.icon}"></i> ${event.title}</div>
                            <label class="switch-wrapper">
                                <span style="color:var(--text-muted);">Aktif</span>
                                <div class="switch">
                                    <input type="checkbox" id="active_${event.id}" ${isActive ? 'checked' : ''}>
                                    <span class="slider"></span>
                                </div>
                            </label>
                        </div>
                        <div class="event-variables">
                            Kullanılabilir değişkenler: ${varsHtml}
                        </div>
                        <div class="form-group">
                            <textarea id="msg_${event.id}" class="form-input" placeholder="${event.default_msg}">${message}</textarea>
                        </div>
                        <button class="btn" onclick="saveNotification('${event.id}')">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </div>
                `;
                grid.innerHTML += cardHtml;
            });
        }

        function showToast(msg, type = 'success') {
            const cont = document.getElementById('toastContainer');
            const t = document.createElement('div');
            const color = type === 'success' ? '#53fc18' : '#ef4444';
            t.style.background = '#18181b';
            t.style.borderLeft = `4px solid ${color}`;
            t.style.color = '#fff';
            t.style.padding = '12px 20px';
            t.style.borderRadius = '4px';
            t.style.boxShadow = '0 4px 12px rgba(0,0,0,0.5)';
            t.style.fontSize = '14px';
            t.style.opacity = '0';
            t.style.transform = 'translateY(20px)';
            t.style.transition = 'all 0.3s ease';
            t.innerHTML = msg;
            cont.appendChild(t);

            setTimeout(() => {
                t.style.opacity = '1';
                t.style.transform = 'translateY(0)';
            }, 10);

            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateY(20px)';
                setTimeout(() => t.remove(), 300);
            }, 3000);
        }

        async function saveNotification(eventId) {
            const isActive = document.getElementById(`active_${eventId}`).checked ? 1 : 0;
            const message = document.getElementById(`msg_${eventId}`).value.trim();

            if (!message) {
                showToast('Mesaj içeriği boş olamaz.', 'error');
                return;
            }

            try {
                const res = await fetch('api/notifications.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'save',
                        event_type: eventId,
                        message_template: message,
                        is_active: isActive
                    })
                });
                const data = await res.json();
                if (data.status === 'success') {
                    showToast(data.message, 'success');
                } else {
                    showToast('Hata: ' + data.message, 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Bir hata oluştu.', 'error');
            }
        }

        // Init
        fetchNotifications();
    </script>
</body>
</html>
