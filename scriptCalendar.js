//Author of Task Management System Calendar Page: Tristian Jurgens//
//so much has changed...//

//stores date, renders the view, and stores tasks by date key, now also looks at the userID//
let current = new Date();
let selected = null;
let currentUserId = null;
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
            window.location.href = "login.html";
            return;
        }

        currentUserId = data.user_id;
        console.log("Logged in as user:", currentUserId);

        loadTasksFromDB(); 
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
        (tasks[k]||[]).forEach(t=>
        {
            const s=document.createElement("span");
            s.className=`task ${t.status.toLowerCase()}`;
            s.textContent=t.title;
            td.appendChild(s);
        });

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


//These are the different functions for tasks//
//Deletes a task//
function deleteTask(i) 
{
    tasks[selected].splice(i, 1);
    renderTasks();
    generateCalendar();
}

//The toggle for the tasks, specifically whether it is completed or not//
function toggleTask(i) 
{
    const t = tasks[selected][i];
    t.status = t.status === "Completed" ? "To-do" : "Completed";
    renderTasks();
    generateCalendar();
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
function renderTasks()
{
    const list=document.getElementById("taskList");
    list.innerHTML="";

    //Loop through each task for the selected date
    (tasks[selected]||[]).forEach((t,i)=>
    {
        const li=document.createElement("li");

        //structure//
        li.innerHTML=
        `
            <strong>${t.title}</strong>
            <span class='task-meta'>${t.priority} | ${t.status}</span>
            <span>${t.desc}</span>
        `;

        //toggle button//
        const toggle=document.createElement("button");
        toggle.textContent="Toggle";
        toggle.onclick=()=>toggleTask(i);

        //delete button//
        const del=document.createElement("button");
        del.textContent="Delete";
        del.onclick=()=>deleteTask(i);

        li.appendChild(toggle);
        li.appendChild(del);

        list.appendChild(li);
    });
}

//New code, loads the data from the database using the php files, backend//
function loadTasksFromDB() 
{
    fetch("getTasks.php")
    .then(res => res.json())
    .then(data => 
    {

        //reset memory?//
        for (const k in tasks) 
        {
            delete tasks[k];
        }

        //Convert DB rows into grouped structure by date//
        data.forEach(t => 
        {
            const dateKey = t.task_date;

            if (!tasks[dateKey]) 
            {
                tasks[dateKey] = [];
            }

            tasks[dateKey].push(
            {
                id: t.id,   
                title: t.title,
                desc: t.description,
                priority: t.priority,
                status: t.status,
                reminder: t.reminder_time
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
                window.location.href = "login.html";
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

//This is to delete tasks from the backend, no longer the just the frontend//
function deleteTask(i) 
{
    const task = tasks[selected][i];

    //this is similar to the other one, allows you to delete the task from database//
    fetch("deleteTask.php", 
    {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(
        {
            id: task.id
        })
    })

    .then(res => res.json())
    .then(data => 
    {
        console.log(data);

        tasks[selected].splice(i, 1);
        renderTasks();
        generateCalendar();
    })
    .catch(err => console.error(err));
}
//Refrences for JS (I had to learn a lot, and a lot of code is learned from these sources): 
//1. https://www.youtube.com/watch?v=ZBJ44LrmwDI
//2. https://medium.com/@bijanrai/create-a-calendar-using-html-css-and-javascript-2a35eb7e5f5a
//3. https://www.youtube.com/watch?v=OcncrLyddAs
//4. https://stackoverflow.com/questions/3260939/month-array-in-javascript-not-pretty
//5. https://blog.avada.io/css/calendars
//6. https://www.youtube.com/watch?v=tq0ghtZsHJ0 (this is just how to insert into mySQL)