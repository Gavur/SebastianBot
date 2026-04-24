<<<<<<< HEAD
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Alarm Ekranı</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: transparent; 
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #alert-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            opacity: 0;
            transform: translateY(50px) scale(0.95);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
        }

        #alert-container.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        #alert-image {
            max-width: 300px;
            max-height: 300px;
            object-fit: contain;
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.5));
        }

        #alert-message {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            padding: 10px 20px;
        }

        
        .effect-shadow { text-shadow: 2px 2px 0 #000, -2px -2px 0 #000, 2px -2px 0 #000, -2px 2px 0 #000, 0 4px 15px rgba(0,0,0,0.8); }
        .effect-neon { text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 20px var(--highlight-color), 0 0 40px var(--highlight-color); }
        .effect-none { text-shadow: none; }
    </style>
</head>
<body>

    <div id="alert-container">
        <img id="alert-image" src="" alt="" style="display:none;">
        <div id="alert-message"></div>
    </div>

    
    <audio id="alert-audio"></audio>

    <script>
        let isPlaying = false;
        const alertContainer = document.getElementById('alert-container');
        const alertImage = document.getElementById('alert-image');
        const alertMessage = document.getElementById('alert-message');
        const alertAudio = document.getElementById('alert-audio');

        async function fetchNextAlert() {
            if (isPlaying) return;

            try {
                const response = await fetch('api/get_next_alert.php');
                const data = await response.json();

                if (data.status === 'success' && data.has_alert) {
                    playAlert(data.alert);
                } else {
                    
                    setTimeout(fetchNextAlert, 2000);
                }
            } catch (err) {
                console.error("Alarm çekilirken hata oluştu:", err);
                setTimeout(fetchNextAlert, 5000);
            }
        }

        function playAlert(alertData) {
            isPlaying = true;

            if (alertData.image_path) {
                alertImage.src = alertData.image_path + '?v=' + new Date().getTime(); 
                alertImage.style.display = 'block';
            } else {
                alertImage.style.display = 'none';
                alertImage.src = '';
            }

            // Metin ayarı
            alertMessage.innerHTML = alertData.message;
            alertMessage.style.fontFamily = (alertData.font_family || 'Inter') + ', sans-serif';
            alertMessage.style.color = alertData.text_color || '#ffffff';
            alertMessage.style.fontStyle = alertData.text_style || 'normal';
            
            const highlight = alertData.highlight_color || '#53FC18';
            alertMessage.style.setProperty('--highlight-color', highlight);
            
            const effect = alertData.text_effect || 'shadow';
            alertMessage.className = 'effect-' + effect;

            // Ses ayarı
            if (alertData.audio_path) {
                alertAudio.src = alertData.audio_path + '?v=' + new Date().getTime();
                alertAudio.volume = (alertData.audio_volume || 50) / 100;
                alertAudio.loop = true;
                alertAudio.play().catch(e => console.log("Ses çalma hatası:", e));
            }

            // Ekranda göster
            alertContainer.classList.add('show');

            // Süre bitiminde gizle
            const durationMs = (alertData.duration_seconds || 5) * 1000;
            
            setTimeout(() => {
                alertContainer.classList.remove('show');
                
                
                setTimeout(() => {
                    alertAudio.pause();
                    alertAudio.currentTime = 0;
                    isPlaying = false;
                    fetchNextAlert();
                }, 600); // 0.5s CSS transition süresi + 100ms buffer
                
            }, durationMs);
        }

        // Sayfa yüklendiğinde döngüyü başlat
        window.addEventListener('load', () => {
            fetchNextAlert();
        });
    </script>
</body>
=======
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBS Alarm Ekranı</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: transparent; 
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #alert-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            opacity: 0;
            transform: translateY(50px) scale(0.95);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
        }

        #alert-container.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        #alert-image {
            max-width: 300px;
            max-height: 300px;
            object-fit: contain;
            margin-bottom: 20px;
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.5));
        }

        #alert-message {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            padding: 10px 20px;
        }

        
        .effect-shadow { text-shadow: 2px 2px 0 #000, -2px -2px 0 #000, 2px -2px 0 #000, -2px 2px 0 #000, 0 4px 15px rgba(0,0,0,0.8); }
        .effect-neon { text-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 20px var(--highlight-color), 0 0 40px var(--highlight-color); }
        .effect-none { text-shadow: none; }
    </style>
</head>
<body>

    <div id="alert-container">
        <img id="alert-image" src="" alt="" style="display:none;">
        <div id="alert-message"></div>
    </div>

    
    <audio id="alert-audio"></audio>

    <script>
        let isPlaying = false;
        const alertContainer = document.getElementById('alert-container');
        const alertImage = document.getElementById('alert-image');
        const alertMessage = document.getElementById('alert-message');
        const alertAudio = document.getElementById('alert-audio');

        async function fetchNextAlert() {
            if (isPlaying) return;

            try {
                const response = await fetch('api/get_next_alert.php');
                const data = await response.json();

                if (data.status === 'success' && data.has_alert) {
                    playAlert(data.alert);
                } else {
                    
                    setTimeout(fetchNextAlert, 2000);
                }
            } catch (err) {
                console.error("Alarm çekilirken hata oluştu:", err);
                setTimeout(fetchNextAlert, 5000);
            }
        }

        function playAlert(alertData) {
            isPlaying = true;

            if (alertData.image_path) {
                alertImage.src = alertData.image_path + '?v=' + new Date().getTime(); 
                alertImage.style.display = 'block';
            } else {
                alertImage.style.display = 'none';
                alertImage.src = '';
            }

            // Metin ayarı
            alertMessage.innerHTML = alertData.message;
            alertMessage.style.fontFamily = (alertData.font_family || 'Inter') + ', sans-serif';
            alertMessage.style.color = alertData.text_color || '#ffffff';
            alertMessage.style.fontStyle = alertData.text_style || 'normal';
            
            const highlight = alertData.highlight_color || '#53FC18';
            alertMessage.style.setProperty('--highlight-color', highlight);
            
            const effect = alertData.text_effect || 'shadow';
            alertMessage.className = 'effect-' + effect;

            // Ses ayarı
            if (alertData.audio_path) {
                alertAudio.src = alertData.audio_path + '?v=' + new Date().getTime();
                alertAudio.volume = (alertData.audio_volume || 50) / 100;
                alertAudio.loop = true;
                alertAudio.play().catch(e => console.log("Ses çalma hatası:", e));
            }

            // Ekranda göster
            alertContainer.classList.add('show');

            // Süre bitiminde gizle
            const durationMs = (alertData.duration_seconds || 5) * 1000;
            
            setTimeout(() => {
                alertContainer.classList.remove('show');
                
                
                setTimeout(() => {
                    alertAudio.pause();
                    alertAudio.currentTime = 0;
                    isPlaying = false;
                    fetchNextAlert();
                }, 600); // 0.5s CSS transition süresi + 100ms buffer
                
            }, durationMs);
        }

        // Sayfa yüklendiğinde döngüyü başlat
        window.addEventListener('load', () => {
            fetchNextAlert();
        });
    </script>
</body>
>>>>>>> Add backend API endpoints for chat, alerts, bans, and more
</html>