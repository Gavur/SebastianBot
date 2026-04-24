<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-icon">DÖ</div>
        <div class="logo-text">SebastianBot</div>
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
            <a href="index.php" class="nav-link">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'chat.php') ? 'active' : ''; ?>">
            <a href="chat.php" class="nav-link">
                <i class="fas fa-comment-dots"></i>
                <span>Live Chat</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'commands.php') ? 'active' : ''; ?>">
            <a href="commands.php" class="nav-link">
                <i class="fas fa-terminal"></i>
                <span>Commands</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'followers.php') ? 'active' : ''; ?>">
            <a href="followers.php" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Followers</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loyalty.php') ? 'active' : ''; ?>">
            <a href="loyalty.php" class="nav-link">
                <i class="fas fa-ranking-star"></i>
                <span>Loyalty</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'events.php') ? 'active' : ''; ?>">
            <a href="events.php" class="nav-link">
                <i class="fas fa-calendar-alt"></i>
                <span>Events</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'timers.php') ? 'active' : ''; ?>">
            <a href="timers.php" class="nav-link">
                <i class="fas fa-clock"></i>
                <span>Timers</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'moderation.php') ? 'active' : ''; ?>">
            <a href="moderation.php" class="nav-link">
                <i class="fas fa-shield-alt"></i>
                <span>Moderation</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
            <a href="settings.php" class="nav-link">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'notifychat.php') ? 'active' : ''; ?>">
            <a href="notifychat.php" class="nav-link">
                <i class="fas fa-bullhorn"></i>
                <span>Bildirim Mesajları</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'subhaton.php') ? 'active' : ''; ?>">
            <a href="subhaton.php" class="nav-link">
                <i class="fas fa-stopwatch"></i>
                <span>Subathon</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'songque.php') ? 'active' : ''; ?>">
            <a href="songque.php" class="nav-link">
                <i class="fas fa-music"></i>
                <span>Şarkı İstek</span>
            </a>
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'repertoire.php') ? 'active' : ''; ?>">
            <a href="repertoire.php" class="nav-link">
                <i class="fas fa-guitar"></i>
                <span>Repertuar (Peçete)</span>
            </a>
        </li>
        <li style="margin: 15px 0; border-top: 1px solid rgba(255, 255, 255, 0.1);"></li>
        <li style="padding: 0 20px 10px; font-size: 11px; text-transform: uppercase; color: #a1a1aa; font-weight: 600; letter-spacing: 0.5px;">
            Ortak İşlemler
        </li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'shareban.php') ? 'active' : ''; ?>">
            <a href="shareban.php" class="nav-link">
                <i class="fas fa-globe"></i>
                <span>Paylaşılan Banlar</span>
            </a>
        </li>
    </ul>
</div>
