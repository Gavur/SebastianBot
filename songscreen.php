<<<<<<< HEAD
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Song Screen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background-color: transparent; font-family: 'Inter', sans-serif; display: flex; align-items: flex-end; justify-content: flex-start; }
        
        /* Fierce Box Design */
        .fierce-box {
            margin: 40px;
            background: linear-gradient(135deg, rgba(30, 0, 0, 0.95), rgba(153, 27, 27, 0.9));
            border-top: 3px solid #ef4444;
            border-bottom: 5px solid #450a0a;
            border-left: 5px solid #ef4444;
            transform: skewX(-15deg);
            padding: 16px 40px;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.7), inset 0 0 20px rgba(255, 255, 255, 0.1);
            color: white;
            display: inline-block;
            min-width: 300px;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        
        .fierce-box.show { opacity: 1; }
        
        .fierce-box::before {
            content: ''; position: absolute; top: 0; left: -150%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shine 4s infinite;
        }
        
        @keyframes shine { 0% { left: -150%; } 20% { left: 200%; } 100% { left: 200%; } }
        
        .content-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; transition: opacity 0.2s; }
        
        .song-title { font-weight: 900; font-size: 26px; text-shadow: 3px 3px 0 #000, 0 0 15px rgba(239, 68, 68, 0.8); white-space: nowrap; max-width: 600px; overflow: hidden; text-overflow: ellipsis; }
        
        .song-requester { font-weight: 600; font-size: 15px; color: #fca5a5; margin-top: 6px; display: flex; align-items: center; gap: 8px; text-shadow: 1px 1px 0 #000; }
        
        .status-icon { background: #fff; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        .status-icon.paused { background: #f59e0b; color: #000; }

        /* Showcase Box */
        .showcase-box { position: absolute; bottom: 140px; left: 40px; background: linear-gradient(135deg, rgba(20, 20, 20, 0.95), rgba(80, 0, 0, 0.9)); border-left: 5px solid #ef4444; border-bottom: 3px solid #ef4444; transform: skewX(-15deg) translateX(-150%); padding: 20px 30px; box-shadow: 0 10px 30px rgba(220, 38, 38, 0.5); color: white; transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.6s; opacity: 0; z-index: 5; }
        .showcase-box.show { transform: skewX(-15deg) translateX(0); opacity: 1; }
        .sc-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; }
        .sc-header { font-weight: 900; font-size: 20px; text-shadow: 2px 2px 0 #000, 0 0 15px rgba(239, 68, 68, 0.8); margin-bottom: 15px; color: #fff; letter-spacing: 2px; }
        .sc-item { margin-bottom: 14px; display: flex; flex-direction: column; gap: 3px; }
        .sc-title { font-size: 18px; font-weight: 800; text-shadow: 1px 1px 0 #000; white-space: nowrap; max-width: 500px; overflow: hidden; text-overflow: ellipsis; color: #e5e5e5; }
        .sc-user { color: #fca5a5; font-size: 13px; font-weight: 600; text-shadow: 1px 1px 0 #000; display: flex; align-items: center; gap: 6px; }

        /* Skip Animations */
        .anim-shatter { animation: shatterAnim 1s cubic-bezier(0.36, 0, 0.66, -0.56) forwards; }
        @keyframes shatterAnim {
            0% { transform: skewX(-15deg) scale(1); filter: blur(0); opacity: 1; }
            20% { transform: skewX(-25deg) scale(1.05) translate(10px, -5px); filter: blur(1px); background: linear-gradient(135deg, #dc2626, #7f1d1d); box-shadow: 0 0 40px #dc2626; }
            40% { transform: skewX(-5deg) scale(1.1) translate(-10px, 5px); filter: blur(2px); }
            60% { transform: skewX(-20deg) scale(1.15) translate(15px, 10px); filter: blur(4px); }
            100% { transform: skewX(-15deg) scale(1.3) translate(0, 80px); filter: blur(12px); opacity: 0; }
        }
        .skip-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) skewX(15deg); font-size: 36px; font-weight: 900; color: #ffeb3b; text-shadow: 0 0 20px #ff9800, 3px 3px 0 #000; z-index: 20; letter-spacing: 6px; white-space: nowrap; animation: skipTextAnim 1s forwards; }
        @keyframes skipTextAnim { 0% { transform: translate(-50%, -50%) skewX(15deg) scale(0.5); opacity: 0; } 15% { transform: translate(-50%, -50%) skewX(15deg) scale(1.2); opacity: 1; } 30% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } 100% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } }
    </style>
</head>
<body>

    <div class="showcase-box" id="scBox">
        <div class="sc-unskew">
            <div class="sc-header"><i class="fas fa-list-ol"></i> SIRADAKİ PARÇALAR</div>
            <div class="sc-list" id="scList"></div>
        </div>
    </div>

    <div class="fierce-box" id="uiBox">
        <div class="content-unskew">
            <div class="song-title" id="stTitle">Şarkı Bekleniyor...</div>
            <div class="song-requester" id="stReq">
                <span class="status-icon" id="stIcon"><i class="fas fa-play"></i></span>
                <span>İstek: <span id="stUser">@-</span></span>
            </div>
        </div>
    </div>

    <div id="ytplayer" style="position:absolute; top:-9999px; width:200px; height:200px; opacity:0.01; pointer-events:none;"></div>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        let ytPlayer = null;
        let currentVideoId = null;
        let isPlayerReady = false;
        let lastTriggerTime = 0;
        let isAnimating = false;

        function onYouTubeIframeAPIReady() {
            ytPlayer = new YT.Player('ytplayer', {
                height: '200', width: '200', videoId: '',
                playerVars: { 'playsinline': 1, 'controls': 0, 'disablekb': 1, 'fs': 0, 'rel': 0, 'autoplay': 1 },
                events: {
                    'onReady': () => { isPlayerReady = true; },
                    'onStateChange': (event) => {
                        if (event.data === YT.PlayerState.ENDED) {
                            const fd = new FormData(); fd.append('action', 'ended');
                            fetch('api/song_api.php', {method: 'POST', body: fd});
                        }
                    }
                }
            });
        }

        async function pollState() {
            try {
                const res = await fetch('api/song_api.php?action=state');
                const data = await res.json();
                if (data.status === 'success') {
                    if (data.settings.trigger_time > lastTriggerTime) {
                        if (lastTriggerTime !== 0) {
                            if (data.settings.trigger_action === 'skip') {
                                playSkipAnimation(data);
                            } else if (data.settings.trigger_action === 'showcase') {
                                playShowcaseAnimation(data.queue);
                            }
                        }
                        lastTriggerTime = data.settings.trigger_time;
                    }

                    if (!isAnimating) {
                        updatePlayerUI(data);
                    }
                }
            } catch (e) {}
        }

        function updatePlayerUI(data) {
            const box = document.getElementById('uiBox');
            if (data.current) {
                box.classList.add('show');
                document.getElementById('stTitle').innerText = data.current.video_title;
                document.getElementById('stUser').innerText = '@' + data.current.username;
                
                const isPlaying = parseInt(data.settings.is_playing) === 1;
                const icon = document.getElementById('stIcon');
                if(isPlaying) { icon.className = 'status-icon'; icon.innerHTML = '<i class="fas fa-play"></i> Çalıyor'; } 
                else { icon.className = 'status-icon paused'; icon.innerHTML = '<i class="fas fa-pause"></i> Duraklatıldı'; }

                if (isPlayerReady) {
                    if (currentVideoId !== data.current.video_id) { currentVideoId = data.current.video_id; ytPlayer.loadVideoById(currentVideoId); }
                    const ps = ytPlayer.getPlayerState();
                    if (isPlaying && ps !== YT.PlayerState.PLAYING && ps !== YT.PlayerState.BUFFERING) ytPlayer.playVideo();
                    else if (!isPlaying && ps === YT.PlayerState.PLAYING) ytPlayer.pauseVideo();
                }
            } else { 
                box.classList.remove('show'); 
                if(isPlayerReady) ytPlayer.pauseVideo(); 
                currentVideoId = null;
            }
        }

        function playSkipAnimation(data) {
            isAnimating = true;
            const box = document.getElementById('uiBox');
            const content = box.querySelector('.content-unskew');
            
            const skipText = document.createElement('div');
            skipText.className = 'skip-text';
            skipText.innerText = 'ATLANDI!';
            box.appendChild(skipText);

            content.style.opacity = '0';
            box.classList.add('anim-shatter');

            setTimeout(() => {
                box.classList.remove('anim-shatter');
                skipText.remove();
                content.style.opacity = '1';
                
                isAnimating = false;
                if (!data.current) box.classList.remove('show');
                updatePlayerUI(data);
            }, 1000);
        }

        function playShowcaseAnimation(queue) {
            if (!queue || queue.length === 0) return;
            const scBox = document.getElementById('scBox');
            const scList = document.getElementById('scList');
            
            scList.innerHTML = '';
            queue.slice(0, 3).forEach((q, idx) => {
                scList.innerHTML += `<div class="sc-item">
                    <div class="sc-title">${idx + 1}. ${q.video_title}</div>
                    <div class="sc-user"><i class="fas" style="font-size:11px;"></i> İstek: @${q.username}</div>
                </div>`;
            });

            scBox.classList.add('show');
            setTimeout(() => { scBox.classList.remove('show'); }, 5000);
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
    <title>OBS Song Screen</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background-color: transparent; font-family: 'Inter', sans-serif; display: flex; align-items: flex-end; justify-content: flex-start; }
        
        /* Fierce Box Design */
        .fierce-box {
            margin: 40px;
            background: linear-gradient(135deg, rgba(30, 0, 0, 0.95), rgba(153, 27, 27, 0.9));
            border-top: 3px solid #ef4444;
            border-bottom: 5px solid #450a0a;
            border-left: 5px solid #ef4444;
            transform: skewX(-15deg);
            padding: 16px 40px;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.7), inset 0 0 20px rgba(255, 255, 255, 0.1);
            color: white;
            display: inline-block;
            min-width: 300px;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        
        .fierce-box.show { opacity: 1; }
        
        .fierce-box::before {
            content: ''; position: absolute; top: 0; left: -150%; width: 50%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shine 4s infinite;
        }
        
        @keyframes shine { 0% { left: -150%; } 20% { left: 200%; } 100% { left: 200%; } }
        
        .content-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; transition: opacity 0.2s; }
        
        .song-title { font-weight: 900; font-size: 26px; text-shadow: 3px 3px 0 #000, 0 0 15px rgba(239, 68, 68, 0.8); white-space: nowrap; max-width: 600px; overflow: hidden; text-overflow: ellipsis; }
        
        .song-requester { font-weight: 600; font-size: 15px; color: #fca5a5; margin-top: 6px; display: flex; align-items: center; gap: 8px; text-shadow: 1px 1px 0 #000; }
        
        .status-icon { background: #fff; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-size: 11px; }
        .status-icon.paused { background: #f59e0b; color: #000; }

        /* Showcase Box */
        .showcase-box { position: absolute; bottom: 140px; left: 40px; background: linear-gradient(135deg, rgba(20, 20, 20, 0.95), rgba(80, 0, 0, 0.9)); border-left: 5px solid #ef4444; border-bottom: 3px solid #ef4444; transform: skewX(-15deg) translateX(-150%); padding: 20px 30px; box-shadow: 0 10px 30px rgba(220, 38, 38, 0.5); color: white; transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.6s; opacity: 0; z-index: 5; }
        .showcase-box.show { transform: skewX(-15deg) translateX(0); opacity: 1; }
        .sc-unskew { transform: skewX(15deg); display: flex; flex-direction: column; align-items: flex-start; }
        .sc-header { font-weight: 900; font-size: 20px; text-shadow: 2px 2px 0 #000, 0 0 15px rgba(239, 68, 68, 0.8); margin-bottom: 15px; color: #fff; letter-spacing: 2px; }
        .sc-item { margin-bottom: 14px; display: flex; flex-direction: column; gap: 3px; }
        .sc-title { font-size: 18px; font-weight: 800; text-shadow: 1px 1px 0 #000; white-space: nowrap; max-width: 500px; overflow: hidden; text-overflow: ellipsis; color: #e5e5e5; }
        .sc-user { color: #fca5a5; font-size: 13px; font-weight: 600; text-shadow: 1px 1px 0 #000; display: flex; align-items: center; gap: 6px; }

        /* Skip Animations */
        .anim-shatter { animation: shatterAnim 1s cubic-bezier(0.36, 0, 0.66, -0.56) forwards; }
        @keyframes shatterAnim {
            0% { transform: skewX(-15deg) scale(1); filter: blur(0); opacity: 1; }
            20% { transform: skewX(-25deg) scale(1.05) translate(10px, -5px); filter: blur(1px); background: linear-gradient(135deg, #dc2626, #7f1d1d); box-shadow: 0 0 40px #dc2626; }
            40% { transform: skewX(-5deg) scale(1.1) translate(-10px, 5px); filter: blur(2px); }
            60% { transform: skewX(-20deg) scale(1.15) translate(15px, 10px); filter: blur(4px); }
            100% { transform: skewX(-15deg) scale(1.3) translate(0, 80px); filter: blur(12px); opacity: 0; }
        }
        .skip-text { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) skewX(15deg); font-size: 36px; font-weight: 900; color: #ffeb3b; text-shadow: 0 0 20px #ff9800, 3px 3px 0 #000; z-index: 20; letter-spacing: 6px; white-space: nowrap; animation: skipTextAnim 1s forwards; }
        @keyframes skipTextAnim { 0% { transform: translate(-50%, -50%) skewX(15deg) scale(0.5); opacity: 0; } 15% { transform: translate(-50%, -50%) skewX(15deg) scale(1.2); opacity: 1; } 30% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } 100% { transform: translate(-50%, -50%) skewX(15deg) scale(1); opacity: 1; } }
    </style>
</head>
<body>

    <div class="showcase-box" id="scBox">
        <div class="sc-unskew">
            <div class="sc-header"><i class="fas fa-list-ol"></i> SIRADAKİ PARÇALAR</div>
            <div class="sc-list" id="scList"></div>
        </div>
    </div>

    <div class="fierce-box" id="uiBox">
        <div class="content-unskew">
            <div class="song-title" id="stTitle">Şarkı Bekleniyor...</div>
            <div class="song-requester" id="stReq">
                <span class="status-icon" id="stIcon"><i class="fas fa-play"></i></span>
                <span>İstek: <span id="stUser">@-</span></span>
            </div>
        </div>
    </div>

    <div id="ytplayer" style="position:absolute; top:-9999px; width:200px; height:200px; opacity:0.01; pointer-events:none;"></div>

    <script src="https://www.youtube.com/iframe_api"></script>
    <script>
        let ytPlayer = null;
        let currentVideoId = null;
        let isPlayerReady = false;
        let lastTriggerTime = 0;
        let isAnimating = false;

        function onYouTubeIframeAPIReady() {
            ytPlayer = new YT.Player('ytplayer', {
                height: '200', width: '200', videoId: '',
                playerVars: { 'playsinline': 1, 'controls': 0, 'disablekb': 1, 'fs': 0, 'rel': 0, 'autoplay': 1 },
                events: {
                    'onReady': () => { isPlayerReady = true; },
                    'onStateChange': (event) => {
                        if (event.data === YT.PlayerState.ENDED) {
                            const fd = new FormData(); fd.append('action', 'ended');
                            fetch('api/song_api.php', {method: 'POST', body: fd});
                        }
                    }
                }
            });
        }

        async function pollState() {
            try {
                const res = await fetch('api/song_api.php?action=state');
                const data = await res.json();
                if (data.status === 'success') {
                    if (data.settings.trigger_time > lastTriggerTime) {
                        if (lastTriggerTime !== 0) {
                            if (data.settings.trigger_action === 'skip') {
                                playSkipAnimation(data);
                            } else if (data.settings.trigger_action === 'showcase') {
                                playShowcaseAnimation(data.queue);
                            }
                        }
                        lastTriggerTime = data.settings.trigger_time;
                    }

                    if (!isAnimating) {
                        updatePlayerUI(data);
                    }
                }
            } catch (e) {}
        }

        function updatePlayerUI(data) {
            const box = document.getElementById('uiBox');
            if (data.current) {
                box.classList.add('show');
                document.getElementById('stTitle').innerText = data.current.video_title;
                document.getElementById('stUser').innerText = '@' + data.current.username;
                
                const isPlaying = parseInt(data.settings.is_playing) === 1;
                const icon = document.getElementById('stIcon');
                if(isPlaying) { icon.className = 'status-icon'; icon.innerHTML = '<i class="fas fa-play"></i> Çalıyor'; } 
                else { icon.className = 'status-icon paused'; icon.innerHTML = '<i class="fas fa-pause"></i> Duraklatıldı'; }

                if (isPlayerReady) {
                    if (currentVideoId !== data.current.video_id) { currentVideoId = data.current.video_id; ytPlayer.loadVideoById(currentVideoId); }
                    const ps = ytPlayer.getPlayerState();
                    if (isPlaying && ps !== YT.PlayerState.PLAYING && ps !== YT.PlayerState.BUFFERING) ytPlayer.playVideo();
                    else if (!isPlaying && ps === YT.PlayerState.PLAYING) ytPlayer.pauseVideo();
                }
            } else { 
                box.classList.remove('show'); 
                if(isPlayerReady) ytPlayer.pauseVideo(); 
                currentVideoId = null;
            }
        }

        function playSkipAnimation(data) {
            isAnimating = true;
            const box = document.getElementById('uiBox');
            const content = box.querySelector('.content-unskew');
            
            const skipText = document.createElement('div');
            skipText.className = 'skip-text';
            skipText.innerText = 'ATLANDI!';
            box.appendChild(skipText);

            content.style.opacity = '0';
            box.classList.add('anim-shatter');

            setTimeout(() => {
                box.classList.remove('anim-shatter');
                skipText.remove();
                content.style.opacity = '1';
                
                isAnimating = false;
                if (!data.current) box.classList.remove('show');
                updatePlayerUI(data);
            }, 1000);
        }

        function playShowcaseAnimation(queue) {
            if (!queue || queue.length === 0) return;
            const scBox = document.getElementById('scBox');
            const scList = document.getElementById('scList');
            
            scList.innerHTML = '';
            queue.slice(0, 3).forEach((q, idx) => {
                scList.innerHTML += `<div class="sc-item">
                    <div class="sc-title">${idx + 1}. ${q.video_title}</div>
                    <div class="sc-user"><i class="fas" style="font-size:11px;"></i> İstek: @${q.username}</div>
                </div>`;
            });

            scBox.classList.add('show');
            setTimeout(() => { scBox.classList.remove('show'); }, 5000);
        }

        setInterval(pollState, 2000);
    </script>
</body>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
</html>