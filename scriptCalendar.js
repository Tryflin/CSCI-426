//Reference: https://www.youtube.com/watch?v=OcncrLyddAs, very helpful video, however it only shows how to display a calender, not to interact//
//Overall, had to look up a lot for this code to work...//
//SOME concepts taken from: https://www.youtube.com/watch?v=ZBJ44LrmwDI and https://medium.com/@bijanrai/create-a-calendar-using-html-css-and-javascript-2a35eb7e5f5a//

//stores date, renders the veiw, and stroes tasks by date key //
let current = new Date();
let selected = null;
const tasks = {};

//Display for months //
const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];

//Weird way to display dates, makes a key for each one //
function key(y,m,d)
{ 
    return `${y}-${m+1}-${d}`; 
}

//This is to change the month whenever you hit the next or previous button //
function changeMonth(o)
{ 
    current.setMonth(current.getMonth()+o); generateCalendar(); 
}

//This does the heavy lifting, it generates the calendar ui //
function generateCalendar()
{
    const m=current.getMonth(), y=current.getFullYear();
    document.getElementById("monthLabel").textContent = months[m] + " " + y;

    const tbody=document.querySelector("#calendar tbody");
    tbody.innerHTML="";

    const first=new Date(y,m,1);
    let start=(first.getDay()+6)%7;
    const days=new Date(y,m+1,0).getDate();

    let row=document.createElement("tr");

    for(let i=0;i<start;i++) 
    {
        row.appendChild(document.createElement("td"));
    }

    for(let d=1;d<=days;d++)
    {
        if(row.children.length===7)
        { 
            tbody.appendChild(row); row=document.createElement("tr"); 
        }

        const td=document.createElement("td");
        td.textContent=d;
        td.onclick=()=>selectDate(d,m,y,td);

        const today=new Date();
        if(d===today.getDate()&&m===today.getMonth()&&y===today.getFullYear())
        {
            td.classList.add("today");
        } 

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

    tbody.appendChild(row);
}

//This is what allows you to select a date on the calendar //
function selectDate(d,m,y,el)
{
    selected=key(y,m,d);
    document.getElementById("selectedDate").textContent=`${months[m]} ${d}, ${y}`;
    renderTasks();

    document.querySelectorAll("td").forEach(td=>td.classList.remove("selected"));
    el.classList.add("selected");
}

//As the name suggests, it adds a task to the date selected //
function addTask()
{
    //Cool function to return an error if you didnt select a date //
    if(!selected) return alert("Select a date");

    const title=document.getElementById("taskTitle").value;
    const desc=document.getElementById("taskDesc").value;
    const priority=document.getElementById("taskPriority").value;
    const status=document.getElementById("taskStatus").value;

    if(!title) return;

    if(!tasks[selected]) tasks[selected]=[];

    tasks[selected].push({title,desc,priority,status});

    document.getElementById("taskTitle").value="";
    document.getElementById("taskDesc").value="";

    renderTasks(); generateCalendar();
}

//Deletes a task //
function deleteTask(i)
{
    tasks[selected].splice(i,1);
    renderTasks(); generateCalendar();
}

//The toggle for the tasks, specifically whether it is completed or not //
function toggleTask(i)
{
    const t=tasks[selected][i];
    t.status = t.status==="Completed"?"Todo":"Completed";
    renderTasks(); generateCalendar();
}

// toggle to clear the tasks from the calendar//
function clearTasks()
{
    if(!selected) return;
    tasks[selected]=[];
    renderTasks(); generateCalendar();
}

//This renders the task data for the date selected //
function renderTasks()
{
    const list=document.getElementById("taskList");
    list.innerHTML="";

    (tasks[selected]||[]).forEach((t,i)=>
    {
        const li=document.createElement("li");

        li.innerHTML=`
        <strong>${t.title}</strong>
        <span class='task-meta'>${t.priority} | ${t.status}</span>
        <span>${t.desc}</span>
        `;

        const toggle=document.createElement("button");
        toggle.textContent="Toggle";
        toggle.onclick=()=>toggleTask(i);

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