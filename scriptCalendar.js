//Author of Task Management System Calendar Page: Tristian Jurgens//

//stores date, renders the view, and stores tasks by date key//
let current = new Date();
let selected = null;
const tasks = {};

//Display for months//
const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];

//Weird way to display dates, makes a unique key for each one in a yyyy-m-d format//
function key(y,m,d)
{ 
    return `${y}-${m+1}-${d}`; 
}

//This is to change the month whenever you hit the next or previous button//
function changeMonth(o)
{ 
    current.setMonth(current.getMonth()+o); generateCalendar(); 
}

//This does the heavy lifting, it generates the calendar ui//
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

//As the name suggests, it adds a task to the date selected//
function addTask()
{
    //Cool function to return an error if you didnt select a date, prevents adding the task//
    if(!selected) return alert("Select a date");

    //input values//
    const title=document.getElementById("taskTitle").value;
    const desc=document.getElementById("taskDesc").value;
    const priority=document.getElementById("taskPriority").value;
    const status=document.getElementById("taskStatus").value;

    //Disallows the use of empty titles, may get rid of//
    if(!title) return;

    if(!tasks[selected]) tasks[selected]=[];

    //This adds the new task//
    tasks[selected].push({title,desc,priority,status});

    //it just an easy was to clear the input fields//
    document.getElementById("taskTitle").value="";
    document.getElementById("taskDesc").value="";

    //refreshes the UI to reflect the tasks added//
    renderTasks(); 
    generateCalendar();
}

//These are the different functions for tasks:
//Deletes a task//
function deleteTask(i)
{
    tasks[selected].splice(i,1);
    renderTasks(); 
    generateCalendar();
}

//The toggle for the tasks, specifically whether it is completed or no //
function toggleTask(i)
{
    const t=tasks[selected][i];
    t.status = t.status==="Completed"?"Todo":"Completed";
    renderTasks(); 
    generateCalendar();
}

// toggle to clear the tasks from the calendar//
function clearTasks()
{
    if(!selected) return;
    tasks[selected]=[];
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
//This is what determines what loads on page load//
generateCalendar();

//Refrences for JS (I had to learn a lot, and a lot of code is learned from these sources): 
//1. https://www.youtube.com/watch?v=ZBJ44LrmwDI
//2. https://medium.com/@bijanrai/create-a-calendar-using-html-css-and-javascript-2a35eb7e5f5a
//3. https://www.youtube.com/watch?v=OcncrLyddAs
//4. https://stackoverflow.com/questions/3260939/month-array-in-javascript-not-pretty
//5. https://blog.avada.io/css/calendars
//Might change: add more bubbles after the last day to make a complete week, like how I did the days before the 1st day
//Also I wanna add more colors to tasks