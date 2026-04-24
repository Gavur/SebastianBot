<<<<<<< HEAD
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subathon Timer (OBS)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap" rel="stylesheet">
    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background-color: transparent; font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; }
        .timer-container { position: relative; text-align: center; }
        #sub-timer { font-size: 100px; font-weight: 800; font-variant-numeric: tabular-nums; transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        
        /* Styles */
        .style-neon { color: #fff; text-shadow: 0 0 10px #53fc18, 0 0 20px #53fc18, 0 0 40px #53fc18; }
        .style-neon.bump { transform: scale(1.1); text-shadow: 0 0 20px #fff, 0 0 40px #53fc18, 0 0 60px #53fc18; }
        .style-neon-event { color: #fff; text-shadow: 0 0 10px #000, 0 0 15px #53fc18; }

        .style-cyberpunk { color: #fff; text-shadow: 2px 2px 0px #f0f, -2px -2px 0px #0ff, 0 0 20px #f0f; font-style: italic; letter-spacing: -2px; }
        .style-cyberpunk.bump { transform: scale(1.15) skewX(-10deg); text-shadow: 4px 4px 0px #f0f, -4px -4px 0px #0ff, 0 0 40px #fff; }
        .style-cyberpunk-event { color: #0ff; text-shadow: 2px 2px 0px #f0f, 0 0 10px #000; font-style: italic; }

        .style-fire { color: #ffeb3b; text-shadow: 0 -2px 4px #ff9800, 0 -6px 10px #f44336, 0 -10px 20px #d32f2f; }
        .style-fire.bump { transform: scale(1.1); text-shadow: 0 -4px 10px #ffeb3b, 0 -10px 20px #ff9800, 0 -20px 40px #f44336; }
        .style-fire-event { color: #ffeb3b; text-shadow: 0 2px 5px #f44336, 0 0 10px #000; }

        .style-minimal { color: #ffffff; text-shadow: 0 4px 15px rgba(0,0,0,0.5); font-weight: 600; letter-spacing: 2px; }
        .style-minimal.bump { transform: translateY(-10px) scale(1.05); color: #53fc18; text-shadow: 0 8px 25px rgba(83,252,24,0.5); }
        .style-minimal-event { color: #53fc18; font-weight: 600; text-shadow: 0 2px 5px rgba(0,0,0,0.8); }

        @keyframes rgbGlow { 0% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} 33% {text-shadow: 0 0 20px #00ff00; color:#00ff00;} 66% {text-shadow: 0 0 20px #0000ff; color:#0000ff;} 100% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} }
        .style-rgb { animation: rgbGlow 3s linear infinite; }
        .style-rgb.bump { transform: scale(1.15) rotate(2deg); }
        .style-rgb-event { color: #fff; text-shadow: 0 0 10px #000, 0 0 15px #00ff00; }
        
        .subathon-badge {
            position: relative;
            display: inline-block;
            margin-top: -15px;
            padding: 8px 30px 8px 44px; 
            background: linear-gradient(90deg, #7f1d1d 0%, #dc2626 50%, #7f1d1d 100%);
            color: #fff;
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 14px;
            transform: skewX(-20deg);
            border-top: 2px solid #f87171;
            border-bottom: 3px solid #450a0a;
            z-index: 10;
            animation: fiercePulse 2s infinite ease-in-out;
        }
        .subathon-badge span {
            display: inline-block;
            transform: skewX(20deg); 
            text-shadow: 3px 3px 0px #000, 0 0 10px rgba(255,255,255,0.6);
        }
        @keyframes fiercePulse {
            0% { box-shadow: 0 0 15px rgba(220, 38, 38, 0.6), inset 0 0 10px rgba(255, 255, 255, 0.2); filter: brightness(1); }
            50% { box-shadow: 0 0 35px rgba(239, 68, 68, 1), inset 0 0 20px rgba(255, 255, 255, 0.6); filter: brightness(1.2); }
            100% { box-shadow: 0 0 15px rgba(220, 38, 38, 0.6), inset 0 0 10px rgba(255, 255, 255, 0.2); filter: brightness(1); }
        }

        #events-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; pointer-events: none; z-index: 50; }
        .event-anim { position: absolute; font-size: 32px; font-weight: 800; animation: bounceAround 2s ease-out forwards; white-space: nowrap; }
        
        @keyframes bounceAround {
            0% { transform: translate(0, 0) scale(0.3); opacity: 0; }
            15% { transform: translate(-60px, -40px) scale(1.3) rotate(-10deg); opacity: 1; }
            35% { transform: translate(50px, -80px) scale(1.1) rotate(10deg); opacity: 1; }
            55% { transform: translate(-30px, -120px) scale(1.2) rotate(-5deg); opacity: 1; }
            80% { transform: translate(20px, -150px) scale(1) rotate(5deg); opacity: 0.8; }
            100% { transform: translate(0, -180px) scale(0.5); opacity: 0; }
        }

        .showcase-text {
            font-size: 36px;
            font-weight: 800;
            color: #ffeb3b; 
            text-shadow: 0 4px 15px rgba(0,0,0,0.8), 0 0 15px #ff9800;
            white-space: nowrap;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 100%;
            margin-bottom: 25px;
            opacity: 0;
        }

        .anim-fade-in { animation: scFadeIn 0.5s forwards; }
        .anim-shake-out { animation: scShakeOut 1s forwards; }
        .anim-shatter-out { animation: scShatterOut 1.2s forwards; }

        @keyframes scFadeIn { 0% { opacity: 0; transform: translateX(-50%) translateY(20px); } 100% { opacity: 1; transform: translateX(-50%) translateY(0); } }
        @keyframes scShakeOut {
            0% { opacity: 1; transform: translateX(-50%); } 15% { transform: translateX(calc(-50% - 15px)); } 30% { transform: translateX(calc(-50% + 15px)); }
            45% { transform: translateX(calc(-50% - 15px)); } 60% { transform: translateX(calc(-50% + 15px)); } 75% { transform: translateX(calc(-50% - 15px)); }
            100% { opacity: 0; transform: translateX(-50%) scale(0.8); }
        }
        @keyframes scShatterOut {
            0% { opacity: 1; transform: translateX(-50%) scale(1); filter: blur(0); letter-spacing: normal; }
            40% { opacity: 0.9; transform: translateX(-50%) scale(1.1); filter: blur(3px); letter-spacing: 8px; }
            100% { opacity: 0; transform: translateX(-50%) scale(1.3) translateY(-30px); filter: blur(15px); letter-spacing: 25px; }
        }
    </style>
</head>
<body>

    <div class="timer-container" id="main-cont" style="display:none;">
        <div id="showcase-container"></div>
        <div id="events-container"></div>
        <div id="sub-timer" class="style-neon">00:00:00</div>
        <div class="subathon-badge"><span>SUBATHON!</span></div>
    </div>

    <script>
        let serverEndTs = 0;
        let serverNowTs = 0;
        let localOffset = 0;
        let lastEventId = 0;
        let currentStyle = 'neon';

        async function fetchStatus() {
            try {
                const res = await fetch('api/subathon_status.php?last_id=' + lastEventId);
                const data = await res.json();
                
                if(data.status === 'success') {
                    if(data.is_active === 1) {
                        document.getElementById('main-cont').style.display = 'block';
                    } else {
                        document.getElementById('main-cont').style.display = 'none';
                    }

                    serverEndTs = data.end_ts;
                    serverNowTs = data.now_ts;
                    localOffset = Math.floor(Date.now() / 1000) - serverNowTs;

                    if(data.timer_style && data.timer_style !== currentStyle) {
                        document.getElementById('sub-timer').classList.remove('style-' + currentStyle);
                        currentStyle = data.timer_style;
                        document.getElementById('sub-timer').classList.add('style-' + currentStyle);
                    }

                    // Events Check
                    if (data.events && data.events.length > 0) {
                        data.events.forEach(ev => {
                            if(!ev.initial) {
                                if (ev.action_type === 'SHOWCASE') {
                                    playShowcaseSequence(ev.username);
                                } else if (ev.action_type.startsWith('SHOWCASE:')) {
                                    playShowcaseSequence(ev.action_type.substring(9));
                                } else {
                                    spawnEvent(ev.seconds_added, ev.username, ev.action_type);
                                }
                            }
                            lastEventId = Math.max(lastEventId, ev.id);
                        });
                    }
                }
            } catch(e) {}
        }

        function playShowcaseSequence(infoDataStr) {
            try {
                const info = JSON.parse(infoDataStr);
                const formatTime = (seconds) => {
                    let parts = [];
                    const h = Math.floor(seconds / 3600);
                    const rem = seconds % 3600;
                    const m = Math.floor(rem / 60);
                    const s = rem % 60;
                    if(h > 0) parts.push(`${h} Saat`);
                    if(m > 0) parts.push(`${m} Dk`);
                    if(s > 0 || parts.length === 0) parts.push(`${s} Sn`);
                    return parts.join(' ');
                };
                
                const scCont = document.getElementById('showcase-container');
                const runMsg = (msg, outClass, delayOffset) => {
                    setTimeout(() => {
                        const el = document.createElement('div');
                        el.className = 'showcase-text anim-fade-in';
                        el.innerText = msg;
                        scCont.appendChild(el);
                        setTimeout(() => {
                            el.className = `showcase-text ${outClass}`;
                            setTimeout(() => el.remove(), 1500); 
                        }, 4000); 
                    }, delayOffset);
                };

                runMsg(`1 Abone = ${formatTime(info.sub)}`, 'anim-shake-out', 0);
                runMsg(`${info.kicks_req} Kicks = ${formatTime(info.sec_kicks)}`, 'anim-shatter-out', 5000);
                runMsg(`1 Hediye = ${formatTime(info.gift)}`, 'anim-shatter-out', 10000);
            } catch (e) { console.error("Animasyon Parse Hatası", e); }
        }

        function spawnEvent(seconds, user, action) {
            const el = document.createElement('div');
            el.className = 'event-anim style-' + currentStyle + '-event';
            
            let timeParts = [];
            const h = Math.floor(seconds / 3600);
            const rem = seconds % 3600;
            const m = Math.floor(rem / 60);
            const s = rem % 60;
            
            if(h > 0) timeParts.push(`${h} Saat`);
            if(m > 0) timeParts.push(`${m} Dk`);
            if(s > 0 || timeParts.length === 0) timeParts.push(`${s} Sn`);
            const timeStr = timeParts.join(' ');
            
            el.innerText = `+${timeStr} (${user} - ${action})`;
            document.getElementById('events-container').appendChild(el);
            
            const timerEl = document.getElementById('sub-timer');
            timerEl.classList.add('bump');
            setTimeout(() => timerEl.classList.remove('bump'), 300);
            setTimeout(() => el.remove(), 2000); // 2s boyunca seker ve kaybolur
        }

        function tick() {
            if(serverEndTs === 0) return;
            const currentRealTs = Math.floor(Date.now() / 1000) - localOffset;
            let diff = serverEndTs - currentRealTs;
            if(diff < 0) diff = 0;
            const h = Math.floor(diff / 3600); const m = Math.floor((diff % 3600) / 60); const s = diff % 60;
            document.getElementById('sub-timer').innerText = `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }
        setInterval(tick, 1000);
        setInterval(fetchStatus, 2000);
        fetchStatus();
    </script>
</body>
=======
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subathon Timer (OBS)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap" rel="stylesheet">
    <style>
        body, html { margin: 0; padding: 0; width: 100%; height: 100%; overflow: hidden; background-color: transparent; font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; }
        .timer-container { position: relative; text-align: center; }
        #sub-timer { font-size: 100px; font-weight: 800; font-variant-numeric: tabular-nums; transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        
        /* Styles */
        .style-neon { color: #fff; text-shadow: 0 0 10px #53fc18, 0 0 20px #53fc18, 0 0 40px #53fc18; }
        .style-neon.bump { transform: scale(1.1); text-shadow: 0 0 20px #fff, 0 0 40px #53fc18, 0 0 60px #53fc18; }
        .style-neon-event { color: #fff; text-shadow: 0 0 10px #000, 0 0 15px #53fc18; }

        .style-cyberpunk { color: #fff; text-shadow: 2px 2px 0px #f0f, -2px -2px 0px #0ff, 0 0 20px #f0f; font-style: italic; letter-spacing: -2px; }
        .style-cyberpunk.bump { transform: scale(1.15) skewX(-10deg); text-shadow: 4px 4px 0px #f0f, -4px -4px 0px #0ff, 0 0 40px #fff; }
        .style-cyberpunk-event { color: #0ff; text-shadow: 2px 2px 0px #f0f, 0 0 10px #000; font-style: italic; }

        .style-fire { color: #ffeb3b; text-shadow: 0 -2px 4px #ff9800, 0 -6px 10px #f44336, 0 -10px 20px #d32f2f; }
        .style-fire.bump { transform: scale(1.1); text-shadow: 0 -4px 10px #ffeb3b, 0 -10px 20px #ff9800, 0 -20px 40px #f44336; }
        .style-fire-event { color: #ffeb3b; text-shadow: 0 2px 5px #f44336, 0 0 10px #000; }

        .style-minimal { color: #ffffff; text-shadow: 0 4px 15px rgba(0,0,0,0.5); font-weight: 600; letter-spacing: 2px; }
        .style-minimal.bump { transform: translateY(-10px) scale(1.05); color: #53fc18; text-shadow: 0 8px 25px rgba(83,252,24,0.5); }
        .style-minimal-event { color: #53fc18; font-weight: 600; text-shadow: 0 2px 5px rgba(0,0,0,0.8); }

        @keyframes rgbGlow { 0% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} 33% {text-shadow: 0 0 20px #00ff00; color:#00ff00;} 66% {text-shadow: 0 0 20px #0000ff; color:#0000ff;} 100% {text-shadow: 0 0 20px #ff0000; color:#ff0000;} }
        .style-rgb { animation: rgbGlow 3s linear infinite; }
        .style-rgb.bump { transform: scale(1.15) rotate(2deg); }
        .style-rgb-event { color: #fff; text-shadow: 0 0 10px #000, 0 0 15px #00ff00; }
        
        .subathon-badge {
            position: relative;
            display: inline-block;
            margin-top: -15px;
            padding: 8px 30px 8px 44px; 
            background: linear-gradient(90deg, #7f1d1d 0%, #dc2626 50%, #7f1d1d 100%);
            color: #fff;
            font-size: 24px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 14px;
            transform: skewX(-20deg);
            border-top: 2px solid #f87171;
            border-bottom: 3px solid #450a0a;
            z-index: 10;
            animation: fiercePulse 2s infinite ease-in-out;
        }
        .subathon-badge span {
            display: inline-block;
            transform: skewX(20deg); 
            text-shadow: 3px 3px 0px #000, 0 0 10px rgba(255,255,255,0.6);
        }
        @keyframes fiercePulse {
            0% { box-shadow: 0 0 15px rgba(220, 38, 38, 0.6), inset 0 0 10px rgba(255, 255, 255, 0.2); filter: brightness(1); }
            50% { box-shadow: 0 0 35px rgba(239, 68, 68, 1), inset 0 0 20px rgba(255, 255, 255, 0.6); filter: brightness(1.2); }
            100% { box-shadow: 0 0 15px rgba(220, 38, 38, 0.6), inset 0 0 10px rgba(255, 255, 255, 0.2); filter: brightness(1); }
        }

        #events-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; pointer-events: none; z-index: 50; }
        .event-anim { position: absolute; font-size: 32px; font-weight: 800; animation: bounceAround 2s ease-out forwards; white-space: nowrap; }
        
        @keyframes bounceAround {
            0% { transform: translate(0, 0) scale(0.3); opacity: 0; }
            15% { transform: translate(-60px, -40px) scale(1.3) rotate(-10deg); opacity: 1; }
            35% { transform: translate(50px, -80px) scale(1.1) rotate(10deg); opacity: 1; }
            55% { transform: translate(-30px, -120px) scale(1.2) rotate(-5deg); opacity: 1; }
            80% { transform: translate(20px, -150px) scale(1) rotate(5deg); opacity: 0.8; }
            100% { transform: translate(0, -180px) scale(0.5); opacity: 0; }
        }

        .showcase-text {
            font-size: 36px;
            font-weight: 800;
            color: #ffeb3b; 
            text-shadow: 0 4px 15px rgba(0,0,0,0.8), 0 0 15px #ff9800;
            white-space: nowrap;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 100%;
            margin-bottom: 25px;
            opacity: 0;
        }

        .anim-fade-in { animation: scFadeIn 0.5s forwards; }
        .anim-shake-out { animation: scShakeOut 1s forwards; }
        .anim-shatter-out { animation: scShatterOut 1.2s forwards; }

        @keyframes scFadeIn { 0% { opacity: 0; transform: translateX(-50%) translateY(20px); } 100% { opacity: 1; transform: translateX(-50%) translateY(0); } }
        @keyframes scShakeOut {
            0% { opacity: 1; transform: translateX(-50%); } 15% { transform: translateX(calc(-50% - 15px)); } 30% { transform: translateX(calc(-50% + 15px)); }
            45% { transform: translateX(calc(-50% - 15px)); } 60% { transform: translateX(calc(-50% + 15px)); } 75% { transform: translateX(calc(-50% - 15px)); }
            100% { opacity: 0; transform: translateX(-50%) scale(0.8); }
        }
        @keyframes scShatterOut {
            0% { opacity: 1; transform: translateX(-50%) scale(1); filter: blur(0); letter-spacing: normal; }
            40% { opacity: 0.9; transform: translateX(-50%) scale(1.1); filter: blur(3px); letter-spacing: 8px; }
            100% { opacity: 0; transform: translateX(-50%) scale(1.3) translateY(-30px); filter: blur(15px); letter-spacing: 25px; }
        }
    </style>
</head>
<body>

    <div class="timer-container" id="main-cont" style="display:none;">
        <div id="showcase-container"></div>
        <div id="events-container"></div>
        <div id="sub-timer" class="style-neon">00:00:00</div>
        <div class="subathon-badge"><span>SUBATHON!</span></div>
    </div>

    <script>
        let serverEndTs = 0;
        let serverNowTs = 0;
        let localOffset = 0;
        let lastEventId = 0;
        let currentStyle = 'neon';

        async function fetchStatus() {
            try {
                const res = await fetch('api/subathon_status.php?last_id=' + lastEventId);
                const data = await res.json();
                
                if(data.status === 'success') {
                    if(data.is_active === 1) {
                        document.getElementById('main-cont').style.display = 'block';
                    } else {
                        document.getElementById('main-cont').style.display = 'none';
                    }

                    serverEndTs = data.end_ts;
                    serverNowTs = data.now_ts;
                    localOffset = Math.floor(Date.now() / 1000) - serverNowTs;

                    if(data.timer_style && data.timer_style !== currentStyle) {
                        document.getElementById('sub-timer').classList.remove('style-' + currentStyle);
                        currentStyle = data.timer_style;
                        document.getElementById('sub-timer').classList.add('style-' + currentStyle);
                    }

                    // Events Check
                    if (data.events && data.events.length > 0) {
                        data.events.forEach(ev => {
                            if(!ev.initial) {
                                if (ev.action_type === 'SHOWCASE') {
                                    playShowcaseSequence(ev.username);
                                } else if (ev.action_type.startsWith('SHOWCASE:')) {
                                    playShowcaseSequence(ev.action_type.substring(9));
                                } else {
                                    spawnEvent(ev.seconds_added, ev.username, ev.action_type);
                                }
                            }
                            lastEventId = Math.max(lastEventId, ev.id);
                        });
                    }
                }
            } catch(e) {}
        }

        function playShowcaseSequence(infoDataStr) {
            try {
                const info = JSON.parse(infoDataStr);
                const formatTime = (seconds) => {
                    let parts = [];
                    const h = Math.floor(seconds / 3600);
                    const rem = seconds % 3600;
                    const m = Math.floor(rem / 60);
                    const s = rem % 60;
                    if(h > 0) parts.push(`${h} Saat`);
                    if(m > 0) parts.push(`${m} Dk`);
                    if(s > 0 || parts.length === 0) parts.push(`${s} Sn`);
                    return parts.join(' ');
                };
                
                const scCont = document.getElementById('showcase-container');
                const runMsg = (msg, outClass, delayOffset) => {
                    setTimeout(() => {
                        const el = document.createElement('div');
                        el.className = 'showcase-text anim-fade-in';
                        el.innerText = msg;
                        scCont.appendChild(el);
                        setTimeout(() => {
                            el.className = `showcase-text ${outClass}`;
                            setTimeout(() => el.remove(), 1500); 
                        }, 4000); 
                    }, delayOffset);
                };

                runMsg(`1 Abone = ${formatTime(info.sub)}`, 'anim-shake-out', 0);
                runMsg(`${info.kicks_req} Kicks = ${formatTime(info.sec_kicks)}`, 'anim-shatter-out', 5000);
                runMsg(`1 Hediye = ${formatTime(info.gift)}`, 'anim-shatter-out', 10000);
            } catch (e) { console.error("Animasyon Parse Hatası", e); }
        }

        function spawnEvent(seconds, user, action) {
            const el = document.createElement('div');
            el.className = 'event-anim style-' + currentStyle + '-event';
            
            let timeParts = [];
            const h = Math.floor(seconds / 3600);
            const rem = seconds % 3600;
            const m = Math.floor(rem / 60);
            const s = rem % 60;
            
            if(h > 0) timeParts.push(`${h} Saat`);
            if(m > 0) timeParts.push(`${m} Dk`);
            if(s > 0 || timeParts.length === 0) timeParts.push(`${s} Sn`);
            const timeStr = timeParts.join(' ');
            
            el.innerText = `+${timeStr} (${user} - ${action})`;
            document.getElementById('events-container').appendChild(el);
            
            const timerEl = document.getElementById('sub-timer');
            timerEl.classList.add('bump');
            setTimeout(() => timerEl.classList.remove('bump'), 300);
            setTimeout(() => el.remove(), 2000); // 2s boyunca seker ve kaybolur
        }

        function tick() {
            if(serverEndTs === 0) return;
            const currentRealTs = Math.floor(Date.now() / 1000) - localOffset;
            let diff = serverEndTs - currentRealTs;
            if(diff < 0) diff = 0;
            const h = Math.floor(diff / 3600); const m = Math.floor((diff % 3600) / 60); const s = diff % 60;
            document.getElementById('sub-timer').innerText = `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }
        setInterval(tick, 1000);
        setInterval(fetchStatus, 2000);
        fetchStatus();
    </script>
</body>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
</html>