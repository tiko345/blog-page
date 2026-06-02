<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<header class="header">
    <h2>Chronicle</h2>
    <nav class="nav">
        <a href="#" id="menu-toggle">☰</a>
        <ul class="nav-links1">
            <li><a href="./index.php" class="<?php echo isset($currentPage) && $currentPage === 'home' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="./articles.php" class="<?php echo isset($currentPage) && $currentPage === 'articles' ? 'active' : ''; ?>">Blog</a></li>
            <li><a<?php if (isset($_SESSION['user_id'])): ?> href="./user_dashboard.php" 
                <?php else: ?> href="./register.php" 
                <?php endif; ?> class="<?php echo isset($currentPage) && $currentPage === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="./templates/logout.php" class="logout">Logout</a></li>
            <?php else: ?>
                <li><a href="./register.php" class="register">Register</a></li>
            <?php endif; ?>
        </ul>
        <ul class="nav-links2">
            <li><a href="#" id="theme-toggle">◑</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="./user_dashboard.php" class="user"> <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></a></li>
                <li><a href="./templates/logout.php" class="logout">Logout</a></li>
            <?php else: ?>
            <li><a href="./register.php">Sign in</a></li>
            <li><a href="./register.php" class="register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>