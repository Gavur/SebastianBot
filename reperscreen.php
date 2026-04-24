<<<<<<< HEAD
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Repertuar Ekranı</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background-color: transparent; font-family: 'Inter', sans-serif; display: flex; align-items: flex-end; justify-content: flex-start; }
        
        .fierce-wrapper { margin: 40px; position: relative; display: inline-block; min-width: 320px; opacity: 0; transition: opacity 0.5s ease-in-out; }
        .fierce-wrapper.show { opacity: 1; }

        .badge-live { position: absolute; top: -14px; left: 15px; background: #ef4444; color: white; font-size: 11px; font-weight: 900; padding: 5px 14px; transform: skewX(-15deg); letter-spacing: 2px; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.6); z-index: 10; }
        .badge-live span { display: inline-block; transform: skewX(15deg); }

        .fierce-box-inner { background: linear-gradient(135deg, rgba(25, 0, 35, 0.95), rgba(74, 4, 78, 0.9)); border-top: 3px solid #d946ef; border-bottom: 5px solid #701a75; border-left: 5px solid #d946ef; transform: skewX(-15deg); padding: 22px 45px; box-shadow: 0 10px 30px rgba(217, 70, 239, 0.5), inset 0 0 20px rgba(255, 255, 255, 0.1); color: white; position: relative; overflow: hidden; width: 100%; box-sizing: border-box; }
        .fierce-box-inner::before { content: ''; position: absolute; top: 0; left: -150%; width: 50%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); animation: shine 4s infinite; }
        @keyframes shine { 0% { left: -150%; } 20% { left: 200%; } 100% { left: 200%; } }
        
        .content-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; transition: opacity 0.2s; }
        .rep-title { font-weight: 900; font-size: 26px; text-shadow: 3px 3px 0 #000, 0 0 15px rgba(217, 70, 239, 0.8); white-space: nowrap; max-width: 600px; overflow: hidden; text-overflow: ellipsis; }
        .rep-requester { font-weight: 600; font-size: 15px; color: #f0abfc; margin-top: 6px; display: flex; align-items: center; gap: 8px; text-shadow: 1px 1px 0 #000; }
        .status-icon { background: #fff; color: #701a75; padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        .status-icon.paused { background: #f59e0b; color: #000; }

        .showcase-box { position: absolute; bottom: 170px; left: 40px; background: linear-gradient(135deg, rgba(25, 0, 35, 0.95), rgba(74, 4, 78, 0.9)); border-left: 5px solid #d946ef; border-bottom: 3px solid #d946ef; transform: skewX(-15deg) translateX(-150%); padding: 20px 30px; box-shadow: 0 10px 30px rgba(217, 70, 239, 0.5); color: white; transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.6s; opacity: 0; z-index: 5; }
        .showcase-box.show { transform: skewX(-15deg) translateX(0); opacity: 1; }
        .sc-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; }
        .sc-header { font-weight: 900; font-size: 20px; text-shadow: 2px 2px 0 #000, 0 0 15px rgba(217, 70, 239, 0.8); margin-bottom: 15px; color: #fff; letter-spacing: 2px; }
        .sc-item { margin-bottom: 14px; display: flex; flex-direction: column; gap: 3px; }
        .sc-title { font-size: 18px; font-weight: 800; text-shadow: 1px 1px 0 #000; white-space: nowrap; max-width: 500px; overflow: hidden; text-overflow: ellipsis; color: #e5e5e5; }
        .sc-user { color: #f0abfc; font-size: 13px; font-weight: 600; text-shadow: 1px 1px 0 #000; display: flex; align-items: center; gap: 6px; }

        .rep-list-box { position: absolute; bottom: 170px; left: 40px; background: linear-gradient(135deg, rgba(20, 0, 25, 0.95), rgba(70, 0, 75, 0.9)); border-left: 5px solid #d946ef; border-bottom: 3px solid #d946ef; padding: 20px 35px; box-shadow: 0 10px 40px rgba(217, 70, 239, 0.5); color: white; opacity: 0; transform: skewX(-15deg) translateX(-100px) scale(0.9); transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 10; min-width: 400px; }
        .rep-list-box.show { opacity: 1; transform: skewX(-15deg) translateX(0) scale(1); }
        .rep-list-unskew { transform: skewX(15deg); display: flex; flex-direction: column; }
        .rep-list-header { color: #f0abfc; font-size: 24px; font-weight: 900; text-shadow: 2px 2px 0 #000, 0 0 15px rgba(217, 70, 239, 0.8); margin-bottom: 15px; letter-spacing: 2px; text-align: center; border-bottom: 2px dashed rgba(217, 70, 239, 0.3); padding-bottom: 10px; }
        .rep-list-content { transition: opacity 0.4s ease; display: flex; flex-direction: column; gap: 10px; }
        .rl-item { font-size: 22px; font-weight: 800; display: flex; align-items: center; gap: 10px; text-shadow: 2px 2px 0 #000; }
        .rl-code { color: #d946ef; background: rgba(217, 70, 239, 0.15); padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(217, 70, 239, 0.3); font-variant-numeric: tabular-nums; text-shadow: 1px 1px 0 #000; }

        .anim-shatter { animation: shatterAnim 1s cubic-bezier(0.36, 0, 0.66, -0.56) forwards; }
        @keyframes shatterAnim { 0% { transform: skewX(-15deg) scale(1); filter: blur(0); opacity: 1; } 20% { transform: skewX(-25deg) scale(1.05) translate(10px, -5px); filter: blur(1px); background: linear-gradient(135deg, #d946ef, #701a75); box-shadow: 0 0 40px #d946ef; } 40% { transform: skewX(-5deg) scale(1.1) translate(-10px, 5px); filter: blur(2px); } 60% { transform: skewX(-20deg) scale(1.15) translate(15px, 10px); filter: blur(4px); } 100% { transform: skewX(-15deg) scale(1.3) translate(0, 80px); filter: blur(12px); opacity: 0; } }
        .skip-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) skewX(15deg); font-size: 36px; font-weight: 900; color: #f0abfc; text-shadow: 0 0 20px rgba(217, 70, 239, 0.8), 3px 3px 0 #000; z-index: 20; letter-spacing: 6px; white-space: nowrap; animation: skipTextAnim 1s forwards; }
        @keyframes skipTextAnim { 0% { transform: translate(-50%, -50%) skewX(15deg) scale(0.5); opacity: 0; } 15% { transform: translate(-50%, -50%) skewX(15deg) scale(1.2); opacity: 1; } 30% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } 100% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } }
    </style>
</head>
<body>

    <div class="rep-list-box" id="repListBox">
        <div class="rep-list-unskew">
            <div class="rep-list-header"><i class="fas fa-guitar"></i> REPERTUAR LİSTESİ</div>
            <div class="rep-list-content" id="repListContent"></div>
        </div>
    </div>

    <div class="showcase-box" id="scBox">
        <div class="sc-unskew">
            <div class="sc-header"><i class="fas fa-list-ol"></i> SIRADAKİ İSTEKLER</div>
            <div class="sc-list" id="scList"></div>
        </div>
    </div>

    <div class="fierce-wrapper" id="uiBox">
        <div class="badge-live"><span>CANLI PERFORMANS</span></div>
        <div class="fierce-box-inner" id="uiBoxInner">
            <div class="content-unskew">
                <div class="rep-title" id="stTitle">İstek Bekleniyor...</div>
                <div class="rep-requester" id="stReq">
                    <span class="status-icon" id="stIcon"><i class="fas fa-play"></i></span>
                    <span>Peçete: <span id="stUser">@-</span></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastTriggerTime = 0;
        let isAnimating = false;
        let isAnimatingRep = false;

        async function pollState() {
            try {
                const res = await fetch('api/repertoire_api.php?action=state');
                const data = await res.json();
                if (data.status === 'success') {
                    if (data.settings.trigger_time > lastTriggerTime) {
                        if (lastTriggerTime !== 0) {
                            if (data.settings.trigger_action === 'skip') { playSkipAnimation(data); } 
                            else if (data.settings.trigger_action === 'showcase') { playShowcaseAnimation(data.queue); }
                            else if (data.settings.trigger_action === 'list_repertoire') { playRepertoireListAnimation(); }
                        }
                        lastTriggerTime = data.settings.trigger_time;
                    }
                    if (!isAnimating) { updatePlayerUI(data); }
                }
            } catch (e) {}
        }

        function updatePlayerUI(data) {
            const box = document.getElementById('uiBox');
            if (data.current) {
                box.classList.add('show');
                document.getElementById('stTitle').innerText = data.current.song_name;
                document.getElementById('stUser').innerText = '@' + data.current.username;
                const isPlaying = parseInt(data.settings.is_playing) === 1;
                const icon = document.getElementById('stIcon');
                if(isPlaying) { icon.className = 'status-icon'; icon.innerHTML = '<i class="fas fa-play"></i> Çalıyor'; } 
                else { icon.className = 'status-icon paused'; icon.innerHTML = '<i class="fas fa-pause"></i> Duraklatıldı'; }
            } else { box.classList.remove('show'); }
        }

        function playSkipAnimation(data) {
            isAnimating = true; const box = document.getElementById('uiBox'); const inner = document.getElementById('uiBoxInner'); const content = box.querySelector('.content-unskew');
            const skipText = document.createElement('div'); skipText.className = 'skip-text'; skipText.innerText = 'GEÇİLDİ!'; box.appendChild(skipText);
            content.style.opacity = '0'; inner.classList.add('anim-shatter');
            setTimeout(() => { inner.classList.remove('anim-shatter'); skipText.remove(); content.style.opacity = '1'; isAnimating = false; if (!data.current) box.classList.remove('show'); updatePlayerUI(data); }, 1000);
        }

        function playShowcaseAnimation(queue) {
            if (!queue || queue.length === 0) return;
            const scBox = document.getElementById('scBox'); const scList = document.getElementById('scList'); scList.innerHTML = '';
            queue.slice(0, 3).forEach((q, idx) => { scList.innerHTML += `<div class="sc-item"><div class="sc-title">${idx + 1}. ${q.song_name}</div><div class="sc-user"><i class="fas fa-envelope-open-text" style="font-size:11px;"></i> İstek: @${q.username}</div></div>`; });
            scBox.classList.add('show'); setTimeout(() => { scBox.classList.remove('show'); }, 5000);
        }

        async function playRepertoireListAnimation() {
            if(isAnimatingRep) return; isAnimatingRep = true;
            const res = await fetch('api/repertoire_api.php?action=get_songs'); const data = await res.json();
            if(!data.data || data.data.length === 0) { isAnimatingRep = false; return; }
            const songs = data.data; const chunks = [];
            for(let i=0; i<songs.length; i+=5) { chunks.push(songs.slice(i, i+5)); }
            const repBox = document.getElementById('repListBox'); const repContent = document.getElementById('repListContent');
            repBox.classList.add('show');
            for(let i=0; i<chunks.length; i++) {
                repContent.style.opacity = 0; await new Promise(r => setTimeout(r, 400));
                repContent.innerHTML = chunks[i].map(s => `<div class="rl-item"><span class="rl-code">#${s.id}</span> ${s.song_name}</div>`).join('');
                repContent.style.opacity = 1; await new Promise(r => setTimeout(r, 4600)); // 4.6s bekler
            }
            repBox.classList.remove('show'); setTimeout(() => { isAnimatingRep = false; }, 600);
        }

        setInterval(pollState, 2000);
    </script>
</body>
=======
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Repertuar Ekranı</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background-color: transparent; font-family: 'Inter', sans-serif; display: flex; align-items: flex-end; justify-content: flex-start; }
        
        .fierce-wrapper { margin: 40px; position: relative; display: inline-block; min-width: 320px; opacity: 0; transition: opacity 0.5s ease-in-out; }
        .fierce-wrapper.show { opacity: 1; }

        .badge-live { position: absolute; top: -14px; left: 15px; background: #ef4444; color: white; font-size: 11px; font-weight: 900; padding: 5px 14px; transform: skewX(-15deg); letter-spacing: 2px; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.6); z-index: 10; }
        .badge-live span { display: inline-block; transform: skewX(15deg); }

        .fierce-box-inner { background: linear-gradient(135deg, rgba(25, 0, 35, 0.95), rgba(74, 4, 78, 0.9)); border-top: 3px solid #d946ef; border-bottom: 5px solid #701a75; border-left: 5px solid #d946ef; transform: skewX(-15deg); padding: 22px 45px; box-shadow: 0 10px 30px rgba(217, 70, 239, 0.5), inset 0 0 20px rgba(255, 255, 255, 0.1); color: white; position: relative; overflow: hidden; width: 100%; box-sizing: border-box; }
        .fierce-box-inner::before { content: ''; position: absolute; top: 0; left: -150%; width: 50%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent); animation: shine 4s infinite; }
        @keyframes shine { 0% { left: -150%; } 20% { left: 200%; } 100% { left: 200%; } }
        
        .content-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; transition: opacity 0.2s; }
        .rep-title { font-weight: 900; font-size: 26px; text-shadow: 3px 3px 0 #000, 0 0 15px rgba(217, 70, 239, 0.8); white-space: nowrap; max-width: 600px; overflow: hidden; text-overflow: ellipsis; }
        .rep-requester { font-weight: 600; font-size: 15px; color: #f0abfc; margin-top: 6px; display: flex; align-items: center; gap: 8px; text-shadow: 1px 1px 0 #000; }
        .status-icon { background: #fff; color: #701a75; padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        .status-icon.paused { background: #f59e0b; color: #000; }

        .showcase-box { position: absolute; bottom: 170px; left: 40px; background: linear-gradient(135deg, rgba(25, 0, 35, 0.95), rgba(74, 4, 78, 0.9)); border-left: 5px solid #d946ef; border-bottom: 3px solid #d946ef; transform: skewX(-15deg) translateX(-150%); padding: 20px 30px; box-shadow: 0 10px 30px rgba(217, 70, 239, 0.5); color: white; transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.6s; opacity: 0; z-index: 5; }
        .showcase-box.show { transform: skewX(-15deg) translateX(0); opacity: 1; }
        .sc-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; }
        .sc-header { font-weight: 900; font-size: 20px; text-shadow: 2px 2px 0 #000, 0 0 15px rgba(217, 70, 239, 0.8); margin-bottom: 15px; color: #fff; letter-spacing: 2px; }
        .sc-item { margin-bottom: 14px; display: flex; flex-direction: column; gap: 3px; }
        .sc-title { font-size: 18px; font-weight: 800; text-shadow: 1px 1px 0 #000; white-space: nowrap; max-width: 500px; overflow: hidden; text-overflow: ellipsis; color: #e5e5e5; }
        .sc-user { color: #f0abfc; font-size: 13px; font-weight: 600; text-shadow: 1px 1px 0 #000; display: flex; align-items: center; gap: 6px; }

        .rep-list-box { position: absolute; bottom: 170px; left: 40px; background: linear-gradient(135deg, rgba(20, 0, 25, 0.95), rgba(70, 0, 75, 0.9)); border-left: 5px solid #d946ef; border-bottom: 3px solid #d946ef; padding: 20px 35px; box-shadow: 0 10px 40px rgba(217, 70, 239, 0.5); color: white; opacity: 0; transform: skewX(-15deg) translateX(-100px) scale(0.9); transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 10; min-width: 400px; }
        .rep-list-box.show { opacity: 1; transform: skewX(-15deg) translateX(0) scale(1); }
        .rep-list-unskew { transform: skewX(15deg); display: flex; flex-direction: column; }
        .rep-list-header { color: #f0abfc; font-size: 24px; font-weight: 900; text-shadow: 2px 2px 0 #000, 0 0 15px rgba(217, 70, 239, 0.8); margin-bottom: 15px; letter-spacing: 2px; text-align: center; border-bottom: 2px dashed rgba(217, 70, 239, 0.3); padding-bottom: 10px; }
        .rep-list-content { transition: opacity 0.4s ease; display: flex; flex-direction: column; gap: 10px; }
        .rl-item { font-size: 22px; font-weight: 800; display: flex; align-items: center; gap: 10px; text-shadow: 2px 2px 0 #000; }
        .rl-code { color: #d946ef; background: rgba(217, 70, 239, 0.15); padding: 4px 10px; border-radius: 6px; border: 1px solid rgba(217, 70, 239, 0.3); font-variant-numeric: tabular-nums; text-shadow: 1px 1px 0 #000; }

        .anim-shatter { animation: shatterAnim 1s cubic-bezier(0.36, 0, 0.66, -0.56) forwards; }
        @keyframes shatterAnim { 0% { transform: skewX(-15deg) scale(1); filter: blur(0); opacity: 1; } 20% { transform: skewX(-25deg) scale(1.05) translate(10px, -5px); filter: blur(1px); background: linear-gradient(135deg, #d946ef, #701a75); box-shadow: 0 0 40px #d946ef; } 40% { transform: skewX(-5deg) scale(1.1) translate(-10px, 5px); filter: blur(2px); } 60% { transform: skewX(-20deg) scale(1.15) translate(15px, 10px); filter: blur(4px); } 100% { transform: skewX(-15deg) scale(1.3) translate(0, 80px); filter: blur(12px); opacity: 0; } }
        .skip-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) skewX(15deg); font-size: 36px; font-weight: 900; color: #f0abfc; text-shadow: 0 0 20px rgba(217, 70, 239, 0.8), 3px 3px 0 #000; z-index: 20; letter-spacing: 6px; white-space: nowrap; animation: skipTextAnim 1s forwards; }
        @keyframes skipTextAnim { 0% { transform: translate(-50%, -50%) skewX(15deg) scale(0.5); opacity: 0; } 15% { transform: translate(-50%, -50%) skewX(15deg) scale(1.2); opacity: 1; } 30% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } 100% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } }
    </style>
</head>
<body>

    <div class="rep-list-box" id="repListBox">
        <div class="rep-list-unskew">
            <div class="rep-list-header"><i class="fas fa-guitar"></i> REPERTUAR LİSTESİ</div>
            <div class="rep-list-content" id="repListContent"></div>
        </div>
    </div>

    <div class="showcase-box" id="scBox">
        <div class="sc-unskew">
            <div class="sc-header"><i class="fas fa-list-ol"></i> SIRADAKİ İSTEKLER</div>
            <div class="sc-list" id="scList"></div>
        </div>
    </div>

    <div class="fierce-wrapper" id="uiBox">
        <div class="badge-live"><span>CANLI PERFORMANS</span></div>
        <div class="fierce-box-inner" id="uiBoxInner">
            <div class="content-unskew">
                <div class="rep-title" id="stTitle">İstek Bekleniyor...</div>
                <div class="rep-requester" id="stReq">
                    <span class="status-icon" id="stIcon"><i class="fas fa-play"></i></span>
                    <span>Peçete: <span id="stUser">@-</span></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastTriggerTime = 0;
        let isAnimating = false;
        let isAnimatingRep = false;

        async function pollState() {
            try {
                const res = await fetch('api/repertoire_api.php?action=state');
                const data = await res.json();
                if (data.status === 'success') {
                    if (data.settings.trigger_time > lastTriggerTime) {
                        if (lastTriggerTime !== 0) {
                            if (data.settings.trigger_action === 'skip') { playSkipAnimation(data); } 
                            else if (data.settings.trigger_action === 'showcase') { playShowcaseAnimation(data.queue); }
                            else if (data.settings.trigger_action === 'list_repertoire') { playRepertoireListAnimation(); }
                        }
                        lastTriggerTime = data.settings.trigger_time;
                    }
                    if (!isAnimating) { updatePlayerUI(data); }
                }
            } catch (e) {}
        }

        function updatePlayerUI(data) {
            const box = document.getElementById('uiBox');
            if (data.current) {
                box.classList.add('show');
                document.getElementById('stTitle').innerText = data.current.song_name;
                document.getElementById('stUser').innerText = '@' + data.current.username;
                const isPlaying = parseInt(data.settings.is_playing) === 1;
                const icon = document.getElementById('stIcon');
                if(isPlaying) { icon.className = 'status-icon'; icon.innerHTML = '<i class="fas fa-play"></i> Çalıyor'; } 
                else { icon.className = 'status-icon paused'; icon.innerHTML = '<i class="fas fa-pause"></i> Duraklatıldı'; }
            } else { box.classList.remove('show'); }
        }

        function playSkipAnimation(data) {
            isAnimating = true; const box = document.getElementById('uiBox'); const inner = document.getElementById('uiBoxInner'); const content = box.querySelector('.content-unskew');
            const skipText = document.createElement('div'); skipText.className = 'skip-text'; skipText.innerText = 'GEÇİLDİ!'; box.appendChild(skipText);
            content.style.opacity = '0'; inner.classList.add('anim-shatter');
            setTimeout(() => { inner.classList.remove('anim-shatter'); skipText.remove(); content.style.opacity = '1'; isAnimating = false; if (!data.current) box.classList.remove('show'); updatePlayerUI(data); }, 1000);
        }

        function playShowcaseAnimation(queue) {
            if (!queue || queue.length === 0) return;
            const scBox = document.getElementById('scBox'); const scList = document.getElementById('scList'); scList.innerHTML = '';
            queue.slice(0, 3).forEach((q, idx) => { scList.innerHTML += `<div class="sc-item"><div class="sc-title">${idx + 1}. ${q.song_name}</div><div class="sc-user"><i class="fas fa-envelope-open-text" style="font-size:11px;"></i> İstek: @${q.username}</div></div>`; });
            scBox.classList.add('show'); setTimeout(() => { scBox.classList.remove('show'); }, 5000);
        }

        async function playRepertoireListAnimation() {
            if(isAnimatingRep) return; isAnimatingRep = true;
            const res = await fetch('api/repertoire_api.php?action=get_songs'); const data = await res.json();
            if(!data.data || data.data.length === 0) { isAnimatingRep = false; return; }
            const songs = data.data; const chunks = [];
            for(let i=0; i<songs.length; i+=5) { chunks.push(songs.slice(i, i+5)); }
            const repBox = document.getElementById('repListBox'); const repContent = document.getElementById('repListContent');
            repBox.classList.add('show');
            for(let i=0; i<chunks.length; i++) {
                repContent.style.opacity = 0; await new Promise(r => setTimeout(r, 400));
                repContent.innerHTML = chunks[i].map(s => `<div class="rl-item"><span class="rl-code">#${s.id}</span> ${s.song_name}</div>`).join('');
                repContent.style.opacity = 1; await new Promise(r => setTimeout(r, 4600)); // 4.6s bekler
            }
            repBox.classList.remove('show'); setTimeout(() => { isAnimatingRep = false; }, 600);
        }

        setInterval(pollState, 2000);
    </script>
</body>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
</html>