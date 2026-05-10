<!--Author of Task Management System About Page: Ben Phan
    HTML for the TMS About page

    NOTE: Still need to check file paths for buttons (i believe done)
    NOTE: Server rendering edit 5/7/26
    Might update team roles later -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!--Name might change later-->
    <title>About | Task Management System</title>
    <link rel="stylesheet" href="aboutStyle.css">
</head>

<body>

    <!--Top navigation bar (pulled from navbar.html)-->
    <?php include 'navbar.php'; ?>

    <!--Page title-->
    <div class="page-banner">
        <h1>About Task Management System</h1>
    </div>

    <div class="divider"></div>

    <!--Image section-->
    <div class="image-section">
        <div class="image-box">
            <img src="calendarImage.jpg" alt="TMS calendar" />
        </div>
    </div>

    <div class="divider"></div>

    <!--About section-->
    <div class="about-info">

        <!--Left side-->
        <div class="left-side">
            <div class="section-title">What is Task Management System?</div>

            <div class="desc-box">
                <p>
                    Task Management System (TMS) is a group project we worked on to keep track
                    of assignments, deadlines, and everything else that starts piling up during the week.
                    Before this, most of us were using different apps or just writing stuff down randomly,
                    which honestly got confusing after a while.
                </p>

                <br>

                <p>
                    We made this mainly because it was getting way too easy to forget things,
                    especially after switching to Canvas. Between classes, due dates, and random tasks,
                    stuff would slip through, so this was our way of trying to stay on top of it.
                </p>
            </div>
        </div>

        <!--Right side (features)-->
        <div class="right-side">

            <div class="tms-feature">
                <span class="icon">🌐</span>
                <div>
                    <strong>Accessible From Anywhere</strong>
                    <p>Works on your phone or laptop, so you can check things wherever you are.</p>
                </div>
            </div>

            <div class="tms-feature">
                <span class="icon">🔄</span>
                <div>
                    <strong>Real-Time Updates</strong>
                    <p>Updates show up right away, which helps when something changes last minute.</p>
                </div>
            </div>

            <div class="tms-feature">
                <span class="icon">⭐</span>
                <div>
                    <strong>Task Priorities</strong>
                    <p>You can organize tasks so the more important ones don't get lost.</p>
                </div>
            </div>

        </div>
    </div>

    <div class="divider"></div>

    <!--Team section-->
    <div class="team-section">
        <h2>Meet the Team</h2>

        <div class="team-grid">

            <div class="member-card">
                <div class="avatar">B.P.</div>
                <h3>Ben Phan</h3>
                <p>Worked on the About page and layout</p>
            </div>

            <div class="member-card">
                <div class="avatar">T.J.</div>
                <h3>Tristian Jurgens</h3>
                <p>Handled the calendar and helped with the homepage</p>
            </div>

            <div class="member-card">
                <div class="avatar">D.B.</div>
                <h3>Dane Boyd</h3>
                <p>Worked on forms and dashboard features</p>
            </div>

            <div class="member-card">
                <div class="avatar">H.A.</div>
                <h3>Hayden Arceneaux</h3>
                <p>Did the contact page and some styling work</p>
            </div>

        </div>
    </div>

    <!--Footer (pulled from footer.html)-->
    <?php include 'footer.html'; ?>

    <!--Scroll button-->
    <button id="scrollButton" onclick="scrollToTop()">↑ Top</button>

    <script src="aboutScript.js"></script>

</body>
</html>