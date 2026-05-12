<!--Author of Task Management System About Page: Ben Phan
    Navbar split into smaller file  -->


<link rel="stylesheet" href="global.css">

<nav>
    <span class="logo">Task Management System</span>

    <ul>
        <li><a href="calendar.php">Calendar</a></li>
        <li><a href="index.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>

    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>
    
    <?php if (isset($_SESSION['userID'])): ?>
    
        <a href="calendar.php" class="nav-button">
            User #<?= htmlspecialchars($_SESSION['userID']) ?>
        </a>
    
        <?php if ($currentPage === 'calendar.php'): ?>
    
            <button onclick="logout()" class="nav-button logout-btn">
                Logout
            </button>
    
        <?php endif; ?>
    
    <?php else: ?>
    
        <a href="signup.php" class="nav-button">Get Started</a>
    
    <?php endif; ?>
</nav>
