//Author of Task Management System Calendar Page: Tristian Jurgens//
//so much has changed...//

//stores date, renders the view, and stores tasks by date key, now also looks at the userID//
//added stuff for new buttons//
let current = new Date();
let selected = null;
let currentUserId = null;
let showTasks = true;
let showSidebarTasks = true;
let dyslexiaMode = false;
let highContrastMode = false;
const tasks = {};

// Months//
const months = 
[
    "January","February","March","April","May","June",
    "July","August","September","October","November","December"
];

//Must login before being able to load the calendar, as loading it beforehand does not work well//
fetch("getUser.php")
    .then(res => res.json())
    .then(data => 
    {
        if (!data.loggedIn) 
        {
            alert("You are not logged in");
            window.location.href = "SignIn/login.php";
            return;
        }

        currentUserId = data.user_id;
        console.log("Logged in as user:", currentUserId);

        loadAllTasks(); 
    })
    .catch(err => console.error("User session error:", err));


//Date key, changed again because of database//
function key(y, m, d) 
{
    const mm = String(m + 1).padStart(2, "0");
    const dd = String(d).padStart(2, "0");
    return `${y}-${mm}-${dd}`;
}

//This is to change the month whenever you hit the next or previous button//
function changeMonth(o) 
{
    current.setMonth(current.getMonth() + o);
    generateCalendar();
}

//This does the heavy lifting, it generates the calendar ui, has been changed to look better//
//I have had to change this so  many times//
function generateCalendar() 
{
    const m=current.getMonth(), y=current.getFullYear();

    //Updates the label at the top of the calendar, ie the month and year//
    document.getElementById("monthLabel").textContent = months[m] + " " + y;

    const tbody=document.querySelector("#calendar tbody");
    tbody.innerHTML="";

    //This Finds out the first day of the month loaded and the total number of days//
    const first=new Date(y,m,1);
    //The reason I added numbers was so tha the week starts on Monday, however it can be adjusted for a different date//
    let start=(first.getDay()+6)%7;
    //This is just the total days in the month//
    const days=new Date(y,m+1,0).getDate();

    let row=document.createElement("tr");

    //This adds empty cells before the first day of the month, it looks weird without it//
    for(let i=0;i<start;i++) 
    {
        row.appendChild(document.createElement("td"));
    }

    //loop for the month, essentially checking if we hit 7 days before starting a next line, the tr is for the table//
    for(let d=1;d<=days;d++)
    {
        //this just checks if we have filled in the row and goes to the next one
        if(row.children.length===7)
        { 
            tbody.appendChild(row); row=document.createElement("tr"); 
        }

        const td=document.createElement("td");
        td.textContent=d;

        //simple way to click a day//
        td.onclick=()=>selectDate(d,m,y,td);

        //highlights the date today//
        const today=new Date();
        if(d===today.getDate()&&m===today.getMonth()&&y===today.getFullYear())
        {
            td.classList.add("today");
        } 

        //displays the tasks for the day chosen//
        const k=key(y,m,d);
        if (showTasks) 
        {
            (tasks[k] || []).forEach(t =>
            {
                const s = document.createElement("span");
                s.className = `task ${t.status.toLowerCase()}`;
                s.textContent = `${t.title} (${t.username})`;
                td.appendChild(s);
            });
        }

        row.appendChild(td);
    }

    //this just adds the row to the table//
    tbody.appendChild(row);
}


//This is what allows you to select a date on the calendar//
function selectDate(d,m,y,el)
{
    selected=key(y,m,d);
    document.getElementById("selectedDate").textContent=`${months[m]} ${d}, ${y}`;
    renderTasks();

    //highlights the selected cell//
    document.querySelectorAll("td").forEach(td=>td.classList.remove("selected"));
    el.classList.add("selected");
}


//adds a task, changed to include reminders and to have the new sql stuff//
function addTask() 
{
    //Cool function to return an error if you didnt select a date, prevents adding the task//
    if (!selected) return alert("Select a date");

    //input values, changed to now have a reminder//
    const title = document.getElementById("taskTitle").value;
    const desc = document.getElementById("taskDesc").value;
    const priority = document.getElementById("taskPriority").value;
    const status = document.getElementById("taskStatus").value;
    const reminder = document.getElementById("taskReminder").value;

    //Disallows the use of empty titles, may get rid of//
    if (!title) return;

    //new stuff to add it to the SQL, must match what is in the SQL, otherwise doesnt save//
    const data = 
    {
        user_id: currentUserId,
        title,
        description: desc,
        priority,
        status,
        date: selected,
        reminder_time: reminder  
    };

    //allows you to add the task to the database//
    fetch("saveTask.php", 
    {
        method: "POST",
        headers: 
        {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })

    .then(res => res.json())
    .then(response => 
    {
        console.log(response);

        if (!tasks[selected]) tasks[selected] = [];

        //This adds the new task//
        tasks[selected].push(
        {
            title,
            desc,
            priority,
            status,
            reminder
        });

        //it just an easy way to clear the input fields//
        document.getElementById("taskTitle").value = "";
        document.getElementById("taskDesc").value = "";

        //refreshes the UI to reflect the tasks added//
        renderTasks();
        generateCalendar();
    })
    //error finding//
    .catch(err => console.error(err));
}

//This is to delete tasks from the backend, no longer the just the frontend//
//changed and deleted the original, changed for id//
function deleteTask(id) 
{
    for (const date in tasks) 
    {
        const index = tasks[date].findIndex(t => t.id === id);

        if (index !== -1) 
        {
            const task = tasks[date][index];

            fetch("deleteTask.php", 
            {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: task.id })
            })
            .then(res => res.json())
            .then(data => 
            {
                tasks[date].splice(index, 1);
                renderTasks();
                generateCalendar();
            })
            .catch(err => console.error(err));

            return;
        }
    }
}

//These are the different functions for tasks//

//The toggle for the tasks, specifically whether it is completed or not//
//changed for id instead of i because of backend//
function toggleTask(id) 
{
    for (const date in tasks) 
    {
        const task = tasks[date].find(t => t.id === id);

        if (task) 
        {
            task.status = task.status === "Completed" ? "To-do" : "Completed";

            fetch("updateTasks.php", 
            {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(
                {
                    id: task.id,
                    status: task.status
                })
            });

            generateCalendar();
            return;
        }
    }
}

// toggle to clear the tasks from the calendar//
function clearTasks() 
{
    if(!selected) return;
    tasks[selected] = [];
    renderTasks();
    generateCalendar();
}

//This renders the task data for the date selected//
//so much error fixing//
function renderTasks(filter = "") 
{
    const list = document.getElementById("taskList");
    list.innerHTML = "";

    const query = filter.toLowerCase();

    const allTasks = Object.values(tasks).flat();

    const filtered = allTasks.filter(t =>
        t.title.toLowerCase().includes(query) ||
        (t.desc || "").toLowerCase().includes(query) ||
        t.status.toLowerCase().includes(query) ||
        t.priority.toLowerCase().includes(query)
    );
    

    if (filtered.length === 0) 
    {
        list.innerHTML = "<li>No tasks found</li>";
        return;
    }

    filtered.forEach(task => 
    {
        const li = document.createElement("li");

        li.innerHTML = 
        `
            <strong>${task.title}</strong><br>
            User: ${task.username}<br>
            ${task.desc}<br>
            Priority: ${task.priority}<br>
            Status: ${task.status}
        `;

        const toggle = document.createElement("button");
        toggle.textContent = "Toggle";
        toggle.onclick = () => toggleTask(task.id);

        const del = document.createElement("button");
        del.textContent = "Delete";
        del.onclick = () => deleteTask(task.id);

        li.appendChild(toggle);
        li.appendChild(del);

        list.appendChild(li);
    });
}

//New code, loads the data from the database using the php files, backend//
function loadAllTasks()
{
    fetch("getAllTasks.php")
        .then(res => res.json())
        .then(data =>
        {
            if (!data.success)
            {
                alert(data.error);
                return;
            }

            data.data.forEach(task =>
            {
                const date = task.task_date;

                if (!tasks[date])
                {
                    tasks[date] = [];
                }

                tasks[date].push(
                {
                    id: task.id,
                    title: task.title,
                    desc: task.description,
                    priority: task.priority,
                    status: task.status,
                    reminder: task.reminder_time,
                    username: task.username
                });
            });

            generateCalendar();
        })
        .catch(err => console.error(err));
}

//logout function, stupid easy//
window.logout = function () 
{
    console.log("logout clicked");

    fetch("logout.php")
        .then(res => res.json())
        .then(data => 
        {
            console.log(data);

            if (data.success) 
            {
                window.location.href = "SignIn/login.php";
            }
        })
        .catch(err => console.error(err));
};

// Ask for permission once//
//this is the notifications that sometimes work?//
if (Notification.permission !== "granted") 
{
    Notification.requestPermission();
}

//double checks that reminder times//
function checkReminders() 
{
    const now = new Date();

    Object.keys(tasks).forEach(dateKey => 
    {
        tasks[dateKey].forEach(task => 
        {
            if (!task.reminder || task.notified) return;

            const reminderTime = new Date(task.reminder);

            if (now >= reminderTime) 
            {

                if (Notification.permission === "granted") 
                {
                    new Notification("Task Reminder", 
                    {
                        body: task.title + " is due now!"
                    });
                }

                //only to prevent multiple notifications, as I got spammed at one point
                task.notified = true;
            }
        });
    });
}

// run every 30 seconds, this is temporary for reminders//
setInterval(checkReminders, 30000);

function searchTasks() 
{
    const query = document.getElementById("taskSearch").value;
    renderTasks(query);
}

//just a toggle button//
function toggleAllTasksVisibility() 
{
    showTasks = !showTasks;
    generateCalendar();
}

//another toggle//
function toggleSidebarTasks() 
{
    showSidebarTasks = !showSidebarTasks;

    const list = document.getElementById("taskList");

    if (showSidebarTasks) 
    {
        list.style.display = "block";
    } 
    else 
    {
        list.style.display = "none";
    }
}

//toggle//
function toggleDyslexiaFont() 
{
    dyslexiaMode = !dyslexiaMode;

    if (dyslexiaMode) 
    {
        document.body.classList.add("dyslexia-font");
    } 
    else 
    {
        document.body.classList.remove("dyslexia-font");
    }
}

function toggleHighContrast() 
{
    highContrastMode = !highContrastMode;

    document.body.classList.toggle("high-contrast", highContrastMode);

    localStorage.setItem("highContrastMode", highContrastMode);
}
function loadAllTasks()
{
    fetch("getAllTasks.php")
        .then(res => res.json())
        .then(data =>
        {
            if (!data.success)
            {
                alert(data.error || "Failed to load tasks");
                return;
            }

            // Clear current tasks
            for (const k in tasks)
            {
                delete tasks[k];
            }

            // Load tasks into calendar structure
            data.data.forEach(task =>
            {
                const date = task.task_date;

                if (!tasks[date])
                {
                    tasks[date] = [];
                }

                tasks[date].push(
                {
                    id: task.id,
                    title: task.title,
                    desc: task.description,
                    priority: task.priority,
                    status: task.status,
                    reminder: task.reminder_time,
                    username: task.username
                });
            });

            generateCalendar();

            // If a date is already selected, refresh sidebar
            if (selected)
            {
                renderTasks();
            }
        })
        .catch(err =>
        {
            console.error("Admin task load error:", err);
        });
}

//makes sure order is good//
document.addEventListener("DOMContentLoaded", () => 
{
    generateCalendar();
});


//Refrences for JS (I had to learn a lot, and a lot of code is learned from these sources): 
//1. https://www.youtube.com/watch?v=ZBJ44LrmwDI
//2. https://medium.com/@bijanrai/create-a-calendar-using-html-css-and-javascript-2a35eb7e5f5a
//3. https://www.youtube.com/watch?v=OcncrLyddAs
//4. https://stackoverflow.com/questions/3260939/month-array-in-javascript-not-pretty
//5. https://blog.avada.io/css/calendars
//6. https://www.youtube.com/watch?v=tq0ghtZsHJ0 (this is just how to insert into mySQL)