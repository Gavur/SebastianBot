<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BotDash</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">
    <style>
        .dashboard-content {
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }
        
        .welcome-card {
            background: linear-gradient(135deg, rgba(83, 252, 24, 0.1), rgba(21, 26, 33, 0.8));
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-text h2 {
            font-size: 24px;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        
        .welcome-text p {
            color: var(--text-secondary);
            font-size: 15px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .stat-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-color);
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(83, 252, 24, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 8px;
        }
        
        .stat-card.blue .stat-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .stat-card.purple .stat-icon { background: rgba(168, 85, 247, 0.1); color: #a855f7; }
        .stat-card.pink .stat-icon { background: rgba(236, 72, 153, 0.1); color: #ec4899; }
        
        .stat-title {
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>


    <div class="main-content" style="overflow-y: auto;">

        <header class="topbar">
            <div class="page-title">Dashboard Overview</div>
            <div class="user-profile">
                <div class="avatar">
                    <i class="fas fa-robot" style="color: var(--primary-color);"></i>
                </div>
                <span>SebastianBot</span>
            </div>
        </header>

  
        <div class="dashboard-content">
            
            <div class="welcome-card">
                <div class="welcome-text">
                    <h2>Hoş Geldin, Sebastian! 👋</h2>
                    <p>Yayınların için bot yönetimi ve analizleri tek bir yerden takip et.</p>
                </div>
                <button class="send-btn" style="background: var(--primary-color); color: #000; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px;">
                    <i class="fas fa-play" style="margin-right: 8px;"></i> Botu Başlat
                </button>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-terminal"></i></div>
                    <div class="stat-title">Toplam Komut</div>
                    <div class="stat-value">24</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-icon"><i class="fas fa-comment-dots"></i></div>
                    <div class="stat-title">Bugün Atılan Mesaj</div>
                    <div class="stat-value">1,452</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon"><i class="fas fa-shield-alt"></i></div>
                    <div class="stat-title">Engellenen Kelime</div>
                    <div class="stat-value">86</div>
                </div>
                <div class="stat-card pink">
                    <div class="stat-icon"><i class="fas fa-heart"></i></div>
                    <div class="stat-title">Yeni Takipçi</div>
                    <div class="stat-value">+124</div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
