<?php 
    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<link rel="stylesheet" href="global.css">

<nav>
    <span class="logo">Task Management System</span>

    <ul>
        <li><a href="calendar.php">Calendar</a></li>
        <li><a href="index.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>

    <?php if (isset($_SESSION['userID'])): ?>

        <a href="logout.php" class="nav-button">
            <?= htmlspecialchars($_SESSION['username']) ?>
        </a>

    <?php else: ?>

        <a href="signup.php" class="nav-button">
            Sign Up
        </a>

    <?php endif; ?>
</nav>