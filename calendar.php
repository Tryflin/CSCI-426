<!--Author of Task Management System Calendar Page: Tristian Jurgens-->
<?php
session_start();
require_once 'db.php';
 ?>
<!DOCTYPE html>
<html lang="en">

    <!-- Header -->
    <head>
        <meta name="author" content="Tristian" />
        <meta name="description" content="Calender" />
        <meta charset="UTF-8" />

        <title>Calendar</title>

        <!--Link to style sheet-->
        <link rel="stylesheet" href="styleCalendar.css" />
        <link rel="stylesheet" href="global.css">
        <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/opendyslexic">
    </head>

    <!-- Goes at the bottom of the page. Its essentially all the new features -->
    <div id="bottomControls">
        <button onclick="toggleAllTasksVisibility()">Toggle Calendar Tasks</button>

        <button onclick="toggleSidebarTasks()">Toggle Sidebar Tasks</button>

        <button onclick="toggleDyslexiaFont()">Dyslexia Font</button>

        <button onclick="toggleHighContrast()">High Contrast</button>
    </div>

    <!-- Body -->
    <body>

        <?php include 'navbar.php'; ?>

        <!-- Tasks -->
        <aside>
            <!--This displays the currently selected date with a message telling the user to select a date for it to change-->
            <h2 id="selectedDate">Select a date</h2>

            <!--added task reminders-->
            <label for="taskReminder">Reminder Time:</label>
            <input type="datetime-local" id="taskReminder" class="task-input" />
            <input id="taskTitle" class="task-input" placeholder="Title" />
            <input id="taskDesc" class="task-input" placeholder="Description" />
            <input id="taskSearch" class="task-input" placeholder="Search tasks..." oninput="searchTasks()" />
            
            <select name="optionPriority" id="taskPriority">
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
            </select>

            <select name="optionStatus" id="taskStatus">
                <option>To-do</option>
                <option>In Progress</option>
                <option>Completed</option>
            </select>

            <!--Allows you to add a task by calling the JS function-->
            <button onclick="addTask()">Add Task</button>

            <!--Allows you to clear all the tasks-->
            <button class="secondary" onclick="clearTasks()">Clear Day</button>

            <ul id="taskList"></ul>
        </aside>

        <main>
            <section>
                <!--Navigation-->
                <!--Switches between months-->
                <section class="calendar-header">
                    <!--Goes to the previous month-->
                    <button onclick="changeMonth(-1)">←</button>

                    <!--Displays the current month and year-->
                    <strong id="monthLabel"></strong>

                    <!--Goes to next month-->
                    <button onclick="changeMonth(1)">→</button>
                    
                </section>

                <!--Table names for days-->
                <table id="calendar">
                    <thead>
                        <tr>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </section>
        </main>
        <!--Link to JavaScript-->
        <script>
            const userID = <?php echo json_encode($_SESSION['userID'] ?? null); ?>;

            console.log("Logged in as user:", userID);

            if (!userID) 
            {
                console.error("No logged in user found.");
            }
        </script>
        <script src="calendarScript.js"></script>
    </body>
</html>

<!--Refrences:
1. HW2
2.  https://www.geeksforgeeks.org/web-templates/design-a-calendar-using-html-and-css/ 
3.  https://medium.com/@bijanrai/create-a-calendar-using-html-css-and-javascript-2a35eb7e5f5a-->
