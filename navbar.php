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
    session_start();
     if(isset($_SESSION['username'])) {
         echo '<a href="calendar.html" class="nav-button">'
        . htmlspecialchars($_SESSION['username']) .
        '</a>';
    } else{
        echo '<a href="signup.php" class="nav-button">Get Started</a>';
    }
    ?>
</nav>
