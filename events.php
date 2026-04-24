<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Etkinlikler - SebastianBot</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="style.css">
<style>
.ev-layout{flex:1;padding:24px;overflow-y:auto;display:flex;flex-direction:column;gap:20px}
.ev-card{background:var(--card-bg);border-radius:16px;border:1px solid var(--border-color);padding:24px;box-shadow:0 8px 24px rgba(0,0,0,.2)}
.ev-toolbar{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.ev-search{background:#0f1318;border:1px solid var(--border-color);border-radius:8px;color:#fff;padding:10px 14px;font-size:14px;outline:none;width:240px;font-family:inherit;transition:border-color .2s}
.ev-search:focus{border-color:var(--primary-color)}
.ev-fbtn{padding:7px 14px;border:1px solid var(--border-color);border-radius:8px;background:transparent;color:var(--text-secondary);cursor:pointer;font-size:12px;font-weight:600;font-family:inherit;transition:all .2s}
.ev-fbtn.active{background:var(--primary-color);color:#000;border-color:var(--primary-color)}
.ev-fbtn:hover:not(.active){border-color:var(--text-secondary);color:#fff}
.ev-count{margin-left:auto;color:var(--text-secondary);font-size:13px}
.ev-date-row{display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:10px}
.ev-date-row input[type=date]{background:#0f1318;border:1px solid var(--border-color);border-radius:6px;color:#fff;padding:6px 10px;font-family:inherit;font-size:13px;outline:none}
.ev-list{display:flex;flex-direction:column;gap:6px;margin-top:16px}
.ev-item{display:flex;align-items:center;gap:14px;padding:12px 16px;border-radius:10px;background:#151a21;transition:background .2s;animation:fadeIn .3s}
.ev-item:hover{background:rgba(83,252,24,.04)}
.ev-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0}
.ev-item.follow .ev-icon{background:rgba(16,185,129,.15);color:#34d399}
.ev-item.refollow .ev-icon{background:rgba(234,179,8,.15);color:#fbbf24}
.ev-item.sub .ev-icon{background:rgba(59,130,246,.15);color:#60a5fa}
.ev-item.resub .ev-icon{background:rgba(56,189,248,.15);color:#38bdf8}
.ev-item.gift .ev-icon{background:rgba(168,85,247,.15);color:#c084fc}
.ev-item.kicks .ev-icon{background:rgba(34,197,94,.15);color:#10b981}
.ev-item.host .ev-icon{background:rgba(244,63,94,.15);color:#f43f5e}
.ev-item.ban .ev-icon,.ev-item.kick .ev-icon{background:rgba(239,68,68,.15);color:#f87171}
.ev-details{flex:1;min-width:0}
.ev-user{font-weight:700;font-size:13px;cursor:pointer;transition:color .2s}
.ev-item.follow .ev-user{color:#34d399}
.ev-item.refollow .ev-user{color:#fbbf24}
.ev-item.sub .ev-user{color:#60a5fa}
.ev-item.resub .ev-user{color:#38bdf8}
.ev-item.gift .ev-user{color:#c084fc}
.ev-item.kicks .ev-user{color:#10b981}
.ev-item.host .ev-user{color:#f43f5e}
.ev-item.ban .ev-user,.ev-item.kick .ev-user{color:#f87171}
.ev-user:hover{text-decoration:underline}
.ev-desc{font-size:12px;color:var(--text-secondary);margin-top:2px}
.ev-time{font-size:11px;color:#71717a;white-space:nowrap}
.ev-empty{text-align:center;padding:40px;color:var(--text-secondary)}
.ev-empty i{font-size:40px;margin-bottom:12px;opacity:.3;display:block}
.ev-pagination{display:flex;justify-content:center;gap:6px;margin-top:16px}
.ev-pg{padding:8px 14px;border:1px solid var(--border-color);border-radius:6px;background:transparent;color:var(--text-secondary);cursor:pointer;font-family:inherit;font-size:13px;transition:all .2s}
.ev-pg.active{background:var(--primary-color);color:#000;border-color:var(--primary-color)}
.ev-pg:hover:not(.active){border-color:var(--text-secondary)}


.ud-overlay{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.8);z-index:10000;justify-content:center;align-items:center;backdrop-filter:blur(4px)}
.ud-box{background:#1e1e2d;padding:24px;border-radius:12px;width:560px;max-height:88vh;border:1px solid rgba(255,255,255,.1);box-shadow:0 10px 25px rgba(0,0,0,.5);display:flex;flex-direction:column;overflow:hidden}
.ud-header{display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #3f3f46;padding-bottom:12px;margin-bottom:16px}
.ud-header h3{margin:0;color:#fff;font-size:20px}
.ud-close{background:transparent;border:none;color:#a1a1aa;cursor:pointer;font-size:16px}
.ud-close:hover{color:#fff}
.ud-stats{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:16px}
.ud-stat{background:#27272a;padding:10px;border-radius:8px}
.ud-stat-label{font-size:10px;color:#a1a1aa;text-transform:uppercase;letter-spacing:.5px}
.ud-stat-value{font-size:14px;color:#fff;font-weight:700;margin-top:3px}
.ud-section-title{font-size:12px;color:#a1a1aa;font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin:12px 0 8px}
.ud-scrollable{overflow-y:auto;flex:1;max-height:220px;display:flex;flex-direction:column;gap:6px;padding-right:4px}
.ud-msg{background:#27272a;padding:8px 10px;border-radius:6px;font-size:12px;border-left:3px solid #3b82f6}
.ud-msg-time{color:#a1a1aa;font-size:10px;margin-right:6px}
.ud-ban-item{background:#27272a;padding:8px 10px;border-radius:6px;font-size:12px;border-left:3px solid #ef4444;display:flex;justify-content:space-between}
.ud-actions{display:flex;gap:10px;border-top:1px solid #3f3f46;padding-top:14px;margin-top:14px}
.ud-action-btn{padding:8px 16px;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:13px;font-family:inherit;transition:all .2s}
@keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
<header class="topbar"><div class="page-title">Etkinlik Geçmişi</div><div class="user-profile"><div class="avatar"><i class="fas fa-robot" style="color:var(--primary-color)"></i></div><span>SebastianBot</span></div></header>
<div class="ev-layout"><div class="ev-card">
<div class="ev-toolbar">
<input type="text" class="ev-search" id="evSearch" placeholder="Kullanıcı veya açıklama ara..." oninput="debounce()">
<button class="ev-fbtn active" data-t="all" onclick="setType('all')">Tümü</button>
<button class="ev-fbtn" data-t="follow" onclick="setType('follow')"><i class="fas fa-heart"></i> Takip</button>
<button class="ev-fbtn" data-t="sub" onclick="setType('sub')"><i class="fas fa-star"></i> Abone</button>
<button class="ev-fbtn" data-t="resub" onclick="setType('resub')"><i class="fas fa-star-half-stroke"></i> Y. Abone</button>
<button class="ev-fbtn" data-t="gift" onclick="setType('gift')"><i class="fas fa-gift"></i> Hediye</button>
<button class="ev-fbtn" data-t="kicks" onclick="setType('kicks')"><i class="fas fa-coins"></i> Kicks</button>
<button class="ev-fbtn" data-t="host" onclick="setType('host')"><i class="fas fa-satellite-dish"></i> Host</button>
<span class="ev-count" id="evCount">0 etkinlik</span>
</div>
<div class="ev-date-row">
<button class="ev-fbtn active" data-r="all" onclick="setRange('all')">Tüm Zamanlar</button>
<button class="ev-fbtn" data-r="24h" onclick="setRange('24h')">Son 24 Saat</button>
<button class="ev-fbtn" data-r="7d" onclick="setRange('7d')">Son 7 Gün</button>
<button class="ev-fbtn" data-r="30d" onclick="setRange('30d')">Son 1 Ay</button>
<button class="ev-fbtn" data-r="custom" onclick="setRange('custom')">Özel Aralık</button>
<input type="date" id="evFrom" style="display:none" onchange="fetchEv()">
<input type="date" id="evTo" style="display:none" onchange="fetchEv()">
</div>
<div class="ev-list" id="evList"><div class="ev-empty"><i class="fas fa-calendar-alt"></i><p>Yükleniyor...</p></div></div>
<div class="ev-pagination" id="evPag"></div>
</div></div>
</div>

<div class="ud-overlay" id="udModal"><div class="ud-box">
<div class="ud-header"><h3 id="udName">Yükleniyor...</h3><button class="ud-close" onclick="closeUD()"><i class="fas fa-times"></i></button></div>
<div id="udStats" class="ud-stats"></div>
<div id="udFollowInfo" style="font-size:12px;color:#a1a1aa;margin-bottom:12px"></div>
<div class="ud-section-title"><i class="fas fa-gavel"></i> Ceza Geçmişi</div>
<div id="udBanHistory" class="ud-scrollable" style="max-height:120px;margin-bottom:8px"></div>
<div class="ud-section-title"><i class="fas fa-comment-dots"></i> Mesaj Geçmişi</div>
<div id="udMessages" class="ud-scrollable"></div>
<div id="udActions" class="ud-actions"></div>
</div></div>

<script>
let curType='all',curRange='all',curPage=1,sTimer=null,udTI=null;
function debounce(){clearTimeout(sTimer);sTimer=setTimeout(()=>{curPage=1;fetchEv()},300)}
function setType(t){curType=t;curPage=1;document.querySelectorAll('[data-t]').forEach(b=>b.classList.toggle('active',b.dataset.t===t));fetchEv()}
function setRange(r){
    curRange=r;curPage=1;
    document.querySelectorAll('[data-r]').forEach(b=>b.classList.toggle('active',b.dataset.r===r));
    document.getElementById('evFrom').style.display=r==='custom'?'inline-block':'none';
    document.getElementById('evTo').style.display=r==='custom'?'inline-block':'none';
    if(r!=='custom')fetchEv();
}
async function fetchEv(){
    const s=document.getElementById('evSearch').value.trim();
    let url=`api/get_all_events.php?page=${curPage}&type=${curType}&range=${curRange}&search=${encodeURIComponent(s)}`;
    if(curRange==='custom'){url+=`&from=${document.getElementById('evFrom').value}&to=${document.getElementById('evTo').value}`}
    try{
        const res=await fetch(url);const data=await res.json();
        if(data.status==='success'){renderList(data.data);renderPag(data.page,data.total_pages);document.getElementById('evCount').textContent=data.total+' etkinlik'}
    }catch(e){console.error(e)}
}
function getEvClass(type,desc){
    if(type==='follow'&&desc&&desc.includes('tekrar'))return'refollow';
    return type;
}
function getIcon(cls){
    const m={follow:'fas fa-heart',refollow:'fas fa-user-plus',sub:'fas fa-star',resub:'fas fa-star-half-stroke',gift:'fas fa-gift',kicks:'fas fa-coins',host:'fas fa-satellite-dish',ban:'fas fa-ban',kick:'fas fa-boot'};
    return m[cls]||'fas fa-bell';
}
function renderList(rows){
    const el=document.getElementById('evList');
    if(!rows.length){el.innerHTML='<div class="ev-empty"><i class="fas fa-calendar-alt"></i><p>Etkinlik bulunamadı.</p></div>';return}
    el.innerHTML=rows.map(r=>{
        const cls=getEvClass(r.event_type,r.description);
        const icon=getIcon(cls);
        const d=new Date(r.created_at.replace(' ','T')+'Z');
        const timeStr=d.toLocaleDateString('tr-TR')+' '+d.toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'});
        const uid=r.user_id||'';
        const clickAttr=uid?`onclick="openUD('${uid}')"`:''
        
        let extraBtn = '';
        if(cls === 'refollow') {
            extraBtn = `<button class="ev-fbtn" style="margin-left:10px; padding:4px 8px; font-size:11px; background:rgba(83,252,24,0.1); color:var(--primary-color); border-color:var(--primary-color);" onclick="triggerManualAlert('refollow', '${esc(r.username)}')"><i class="fa-solid fa-bell"></i> Alarm Çal</button>`;
        }
        
        return`<div class="ev-item ${cls}"><div class="ev-icon"><i class="${icon}"></i></div><div class="ev-details"><div class="ev-user" ${clickAttr}>${esc(r.username)}</div><div class="ev-desc">${esc(r.description)}</div></div><div class="ev-time" style="display:flex; align-items:center;">${timeStr}${extraBtn}</div></div>`;
    }).join('');
}
function renderPag(p,tp){
    const d=document.getElementById('evPag');
    if(tp<=1){d.innerHTML='';return}
    let h='';const s=Math.max(1,p-3),e=Math.min(tp,p+3);
    if(p>1)h+=`<button class="ev-pg" onclick="goP(${p-1})"><i class="fas fa-chevron-left"></i></button>`;
    for(let i=s;i<=e;i++)h+=`<button class="ev-pg ${i===p?'active':''}" onclick="goP(${i})">${i}</button>`;
    if(p<tp)h+=`<button class="ev-pg" onclick="goP(${p+1})"><i class="fas fa-chevron-right"></i></button>`;
    d.innerHTML=h;
}
function goP(p){curPage=p;fetchEv()}

async function openUD(userId){
    document.getElementById('udModal').style.display='flex';
    document.getElementById('udName').textContent='Yükleniyor...';
    ['udStats','udMessages','udBanHistory','udActions','udFollowInfo'].forEach(id=>document.getElementById(id).innerHTML='');
    if(udTI)clearInterval(udTI);
    try{
        const res=await fetch('api/get_user_info.php?user_id='+userId);const data=await res.json();
        if(data.status!=='success')return;
        const u=data.user;
        document.getElementById('udName').textContent=u.username;
        let statusH='<span style="color:#10b981"><i class="fas fa-check-circle"></i> Temiz</span>';
        let isTO=false,rem=0;
        if(u.is_banned==1)statusH='<span style="color:#ef4444"><i class="fas fa-ban"></i> Banlı</span>';
        else if(u.remaining_seconds&&parseInt(u.remaining_seconds)>0){isTO=true;rem=parseInt(u.remaining_seconds);statusH='<span id="udTimer" style="color:#fbbf24"><i class="fas fa-stopwatch"></i> Timeout</span>'}
        let fH='';
        if(u.follow_date){const fd=new Date(u.follow_date.replace(' ','T')+'Z');fH=`<i class="fas fa-calendar"></i> Takip: ${fd.toLocaleDateString('tr-TR')} · `;fH+=u.followed_during_stream==1?`<span style="color:#10b981">Yayındayken</span> (${esc(u.followed_stream_category||'?')})`:'<span style="color:#a1a1aa">Yayın dışı</span>'}
        if(data.refollow_count>0)fH+=` · <span style="color:#3b82f6">${data.refollow_count}x yeniden takip</span>`;
        document.getElementById('udFollowInfo').innerHTML=fH;
        document.getElementById('udStats').innerHTML=`<div class="ud-stat"><div class="ud-stat-label">Durum</div><div class="ud-stat-value">${statusH}</div></div><div class="ud-stat"><div class="ud-stat-label">Mesaj</div><div class="ud-stat-value">${u.message_count}</div></div><div class="ud-stat"><div class="ud-stat-label">Silinen</div><div class="ud-stat-value">${u.deleted_message_count}</div></div><div class="ud-stat"><div class="ud-stat-label">Ban</div><div class="ud-stat-value" style="color:#ef4444">${u.ban_count}</div></div><div class="ud-stat"><div class="ud-stat-label">Timeout</div><div class="ud-stat-value" style="color:#fbbf24">${u.timeout_count}</div></div><div class="ud-stat"><div class="ud-stat-label">Yeniden Takip</div><div class="ud-stat-value" style="color:#3b82f6">${data.refollow_count}</div></div>`;
        if(isTO){const upd=()=>{if(rem<=0){document.getElementById('udTimer').innerHTML='<i class="fas fa-check-circle"></i> Bitti';clearInterval(udTI);return}const m=Math.floor(rem/60),s=rem%60;document.getElementById('udTimer').innerHTML=`<i class="fas fa-stopwatch"></i> ${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;rem--};upd();udTI=setInterval(upd,1000)}
        document.getElementById('udBanHistory').innerHTML=data.ban_history&&data.ban_history.length?data.ban_history.map(b=>{const d=new Date(b.created_at.replace(' ','T')+'Z');return`<div class="ud-ban-item"><div><strong>${b.action_type==='ban'?'🔨 Ban':'⏱️ Timeout'}</strong> · ${esc(b.reason||'-')}</div><div style="color:#a1a1aa;font-size:11px">${d.toLocaleDateString('tr-TR')} ${d.toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'})}</div></div>`}).join(''):'<div style="color:#a1a1aa;font-size:12px;text-align:center;padding:12px 0">Ceza geçmişi yok</div>';
        document.getElementById('udMessages').innerHTML=data.messages.length?data.messages.map(m=>{const t=new Date(m.created_at.replace(' ','T')+'Z').toLocaleTimeString('tr-TR',{hour:'2-digit',minute:'2-digit'});return`<div class="ud-msg"><span class="ud-msg-time">[${t}]</span>${esc(m.content)}</div>`}).join(''):'<div style="color:#a1a1aa;font-size:12px;text-align:center;padding:12px 0">Mesaj yok</div>';
        let act='';
        if(u.is_banned==1||isTO)act+=`<button class="ud-action-btn" style="background:#10b981;color:#fff" onclick="doAction('unban','${userId}')"><i class="fas fa-unlock"></i> Kaldır</button>`;
        if(u.is_banned!=1){act+=`<button class="ud-action-btn" style="background:#fbbf24;color:#000" onclick="doAction('timeout','${userId}')"><i class="fas fa-clock"></i> Timeout</button>`;act+=`<button class="ud-action-btn" style="background:#ef4444;color:#fff" onclick="doAction('ban','${userId}')"><i class="fas fa-ban"></i> Ban</button>`}
        document.getElementById('udActions').innerHTML=act;
    }catch(e){console.error(e)}
}
function closeUD(){document.getElementById('udModal').style.display='none';if(udTI)clearInterval(udTI)}

async function doAction(action,userId){
    let url='',body={};
    if(action==='ban'){if(!confirm('Kalıcı ban atılsın mı?'))return;url='api/ban_user.php';body={user_id:userId}}
    else if(action==='timeout'){const d=prompt('Timeout süresi (dakika):','5');if(!d)return;url='api/ban_user.php';body={user_id:userId,duration:parseInt(d)}}
    else if(action==='unban'){if(!confirm('Ceza kaldırılsın mı?'))return;url='api/remove_timeout.php';body={user_id:userId}}
    try{const r=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)});const res=await r.json();if(res.status==='success')openUD(userId);else alert(res.message||'Hata')}catch(e){console.error(e)}
}

async function triggerManualAlertParams(params) {
    try {
        const res = await fetch('api/trigger_alert_manual.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: params
        });
        const data = await res.json();
        if(data.status === 'success') {
            if(typeof showToast === 'function') {
                showToast('Alarm tetiklendi!');
            } else {
                const tempToast = document.createElement('div');
                tempToast.innerHTML = `<div style="position:fixed;bottom:20px;right:20px;background:#10b981;color:#fff;padding:10px 20px;border-radius:8px;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,0.3);">Alarm çalıyor!</div>`;
                document.body.appendChild(tempToast);
                setTimeout(() => tempToast.remove(), 3000);
            }
        } else {
            console.error('Hata:', data.message);
            alert('Hata: ' + data.message);
        }
    } catch(e) {
        console.error('Bağlantı hatası:', e);
    }
}

function esc(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML}
fetchEv();
</script>
</body></html>
