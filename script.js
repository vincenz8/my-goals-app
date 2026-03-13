const toDoList = document.getElementById('taskList');
const taskForm = document.getElementById('taskForm');
const addTaskField = document.getElementById('addTaskField');
const buttonAdd = document.getElementById('buttonAdd');

const taskGroup1l = document.getElementById('weight-1l');
const taskGroup2l = document.getElementById('weight-2l');
const taskGroup3l = document.getElementById('weight-3l');
const taskGroup4l = document.getElementById('weight-4l');

const taskGroup1r = document.getElementById('weight-1r');
const taskGroup2r = document.getElementById('weight-2r');
const taskGroup3r = document.getElementById('weight-3r');
const taskGroup4r = document.getElementById('weight-4r');

const warning = document.createElement('p');
warning.innerHTML = "The task cannot be empty!";
warning.style.color = "red";

const spanDailyScore = document.getElementById('dailyScore');
let dailyScore = 0;

let local_index = 0;
let remote_index = 0;

function showTaskGroup(taskState, taskWeight) {  // Changes display of task groups that have any tasks in it
    if (taskState === "idle" || taskState === "pending") {
        switch (taskWeight) {
            case 1:
                taskGroup1l.style.display = 'inline';
                break;
            case 2:
                taskGroup2l.style.display = 'inline';
                break;
            case 3:
                taskGroup3l.style.display = 'inline';
                break;
            case 4:
                taskGroup4l.style.display = 'inline';
                break;
            default:
                console.log('Error: Invalid task weight.');
        }
    } else if (taskState === "finished") {
        switch (taskWeight) {
            case 1:
                taskGroup1r.style.display = 'inline';
                break;
            case 2:
                taskGroup2r.style.display = 'inline';
                break;
            case 3:
                taskGroup3r.style.display = 'inline';
                break;
            case 4:
                taskGroup4r.style.display = 'inline';
                break;
            default:
                console.log('Error: Invalid task weight.');
        }
    } else {
        console.log('Error: Invalid task state.');
    }
}

function reindexLocalTasks() {
  const inputs = document.querySelectorAll('[name^="local_tasks["]');
  local_index = 0;

  inputs.forEach((input) => {
    const field = input.name.match(/\]\[(.*)\]$/)[1];
    input.name = `local_tasks[${local_index}][${field}]`;
    if (field === "task_state") local_index++;
  });
}

function attributePoints(points) {
    switch (points) {
        case 1:
            dailyScore+=1;
            break;
        case 2:
            dailyScore+=4;
            break;
        case 3:
            dailyScore+=20;
            break;
        case 4:
            dailyScore+=100;
            break;
    }
    spanDailyScore.innerText = dailyScore;
}

function markAsDone(isClicked, item, button, property) {
    if (!isClicked.state) {
        item.style.textDecoration = 'line-through';
        button.src = 'resources/circle_tick.svg';
        property.value = "finished";
    } else {
        item.style.textDecoration = 'none';
        button.src = 'resources/empty_circle.svg';
        property.value = "pending";
    }
    isClicked.switchState();
}

function createButtonRemove() {
        const button = new Image();
        button.src = 'resources/trashcan.svg';
        button.className = 'button-remove';
        return button; }
    
function createButtonMarkAsDone() {
        const button = new Image();
        button.src = 'resources/empty_circle.svg';
        button.className = 'button-mark-done';
        return button; }

const isClicked = {
    state: false,
    switchState() {
        !this.state ? this.state = true : this.state = false;
    }
};

function createTask(arrayName, index, taskName, taskWeight, taskState) {
    /* Generic function for creating a task. It is not called
     * directly and it's used in more specific functions */
   
    const newTask = document.createElement('span');
    const taskNameP = document.createElement('p');
    taskNameP.innerHTML = taskName;

    const inputTaskName = document.createElement('input');
    inputTaskName.type = "hidden";
    inputTaskName.name = `${arrayName}[${index}][task_name]`;
    inputTaskName.value = taskName;

    const inputTaskWeight = document.createElement('input');
    inputTaskWeight.type = "hidden";
    inputTaskWeight.name = `${arrayName}[${index}][task_weight]`;
    inputTaskWeight.value = taskWeight;

    const inputTaskState = document.createElement('input');
    inputTaskState.type = "hidden";
    inputTaskState.name = `${arrayName}[${index}][task_state]`;
    inputTaskState.value = taskState;

    const removeButton = createButtonRemove();
    const markAsDoneButton = createButtonMarkAsDone();
    const circleClicked = Object.create(isClicked);

    newTask.appendChild(inputTaskName);
    newTask.appendChild(inputTaskWeight);
    newTask.appendChild(inputTaskState);
    
    markAsDoneButton.addEventListener('click', () => {
        markAsDone(circleClicked, newTask, markAsDoneButton, inputTaskState);
    });
    if (taskState === "finished") {
        markAsDone(circleClicked, newTask, markAsDoneButton, inputTaskState);
        attributePoints(taskWeight);
    }
    newTask.appendChild(markAsDoneButton);
    newTask.appendChild(taskNameP);
    newTask.appendChild(removeButton);
    
    showTaskGroup(taskState, taskWeight); // Calls function to toggle the display of the HTML 'div' element that will contain the task
    
    return newTask; // Returns a pre-built 'span' element containing all the task parts (text content and buttons)
}

function createLocalTask() {
    const taskName = document.getElementById('taskName');
    taskName.value = String(taskName.value).trim();
    const taskWeight = document.getElementById('taskWeight');
    
    if (taskForm.contains(warning)) {
        taskForm.removeChild(warning); }
    
    if (taskName.value === "") {
        taskForm.insertBefore(warning, addTaskField);
        
    } else {
        
        const newTask = createTask("local_tasks", local_index, taskName.value, parseInt(taskWeight.value), "pending");

        const input1 = newTask.querySelector(`input[name="local_tasks[${local_index}][task_name]`);
        const input2 = newTask.querySelector(`input[name="local_tasks[${local_index}][task_weight]`);
        const input3 = newTask.querySelector(`input[name="local_tasks[${local_index}][task_state]`);
        
        taskForm.appendChild(input1);
        taskForm.appendChild(input2);
        taskForm.appendChild(input3);

        let taskGroup = "";
        switch (parseInt(taskWeight.value)) {
            /* Verifies which HTML secondary group the task 
             * belongs to, according to its weight */
            
            case 1:
                taskGroup = taskGroup1l;
                break;
            case 2:
                taskGroup = taskGroup2l;
                break;
            case 3:
                taskGroup = taskGroup3l;
                break;
            case 4:
                taskGroup = taskGroup4l;
                break;
            default:
                console.log('Error: Unable to identify corresponding task group.');
        }

        const removeButton = newTask.querySelector('.button-remove');
        removeButton.addEventListener('click', () => {
            taskGroup.removeChild(newTask);
            taskForm.removeChild(input1);
            taskForm.removeChild(input2);
            taskForm.removeChild(input3);
            reindexLocalTasks();
        });
        taskGroup.appendChild(newTask);
        
        local_index++;
        taskName.value = "";
    }
}
buttonAdd.addEventListener('click', createLocalTask);

function createRemoteTask(taskName, taskWeight, taskState) {
    
        const newTask = createTask("remote_tasks", remote_index, taskName, taskWeight, taskState);        
        const input1 = newTask.querySelector(`input[name="remote_tasks[${remote_index}][task_name]`);
        const input2 = newTask.querySelector(`input[name="remote_tasks[${remote_index}][task_weight]`);
        const input3 = newTask.querySelector(`input[name="remote_tasks[${remote_index}][task_state]`);
        
        let taskGroup = "";
        switch (taskWeight) {
            /* 'if' statements are required since remote tasks can have different states,
             * depending if they're marked as done or not. Tasks that are marked as done
             * must be placed in the "Finished Tasks" section */
            
            case 1:
            if (taskState === "idle" || taskState === "pending") {
                taskGroup = taskGroup1l;
            } else {
                taskGroup = taskGroup1r;
            }
            break;
            case 2:
            if (taskState === "idle" || taskState === "pending") {
                taskGroup = taskGroup2l;
            } else {
                taskGroup = taskGroup2r;
            }
            break;
            case 3:
            if (taskState === "idle" || taskState === "pending") {
                taskGroup = taskGroup3l;
            } else {
                taskGroup = taskGroup3r;
            }
            break;
            case 4:
            if (taskState === "idle" || taskState === "pending") {
                taskGroup = taskGroup4l;
            } else {
                taskGroup = taskGroup4r;
            }
            break;
            default:
                console.log('Error: Unable to identify corresponding task group.');
        }
        
        taskForm.appendChild(input1);
        taskForm.appendChild(input2);
        taskForm.appendChild(input3);
        
        const removeButton = newTask.querySelector('.button-remove');
        removeButton.addEventListener('click', () => {
            taskGroup.removeChild(newTask);
            input3.value = "removed";
        });
        
        taskGroup.appendChild(newTask);
        
        remote_index++;
}