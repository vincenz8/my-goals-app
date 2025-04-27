// const lettersNumbers = ["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];

const toDoList = document.getElementById('taskList');
const taskForm = document.getElementById('taskForm');
const addTaskField = document.getElementById('addTaskField');
const buttonAdd = document.getElementById('buttonAdd');

const warning = document.createElement('p');
warning.innerHTML = "The task cannot be empty!";
warning.style.color = "red";

const spanDailyScore = document.getElementById('dailyScore');
let dailyScore = 0;

let local_index = 0;
let remote_index = 0;

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
        button.src = './page-resources/circle_tick.svg';
        property.value = "finished";
    } else {
        item.style.textDecoration = 'none';
        button.src = './page-resources/empty_circle.svg';
        property.value = "pending";
    }
    isClicked.switchState();
}

function createButtonRemove() {
        const button = new Image();
        button.src = './page-resources/trashcan.svg';
        button.className = 'button-remove';
        return button; }
    
function createButtonMarkAsDone() {
        const button = new Image();
        button.src = './page-resources/empty_circle.svg';
        button.className = 'button-mark-done';
        return button; }

const isClicked = {
    state: false,
    switchState() {
        !this.state ? this.state = true : this.state = false;
    }
};

function createTask(arrayName, index, taskName, taskWeight, taskState) {
    const newTask = document.createElement('span');
    newTask.innerHTML = taskName;

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
    
    newTask.appendChild(removeButton);
    newTask.appendChild(markAsDoneButton);
    
    return newTask;
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
        
        const newTask = createTask("local_tasks", local_index, taskName.value, taskWeight.value, "pending");

        const input1 = newTask.querySelector(`input[name="local_tasks[${local_index}][task_name]`);
        const input2 = newTask.querySelector(`input[name="local_tasks[${local_index}][task_weight]`);
        const input3 = newTask.querySelector(`input[name="local_tasks[${local_index}][task_state]`);
        
        taskForm.appendChild(input1);
        taskForm.appendChild(input2);
        taskForm.appendChild(input3);

        const removeButton = newTask.querySelector('.button-remove');
        removeButton.addEventListener('click', () => {
            toDoList.removeChild(newTask);
            taskForm.removeChild(input1);
            taskForm.removeChild(input2);
            taskForm.removeChild(input3);
            reindexLocalTasks();
        });
        toDoList.appendChild(newTask);
        
        local_index++;
        taskName.value = "";
    }
}
buttonAdd.addEventListener('click', createLocalTask);

function createRemoteTask(taskName, taskWeight, taskState, parentId) {
    
        const newTask = createTask("remote_tasks", remote_index, taskName, taskWeight, taskState);
        const parentElement = document.getElementById(parentId);
        
        const input1 = newTask.querySelector(`input[name="remote_tasks[${remote_index}][task_name]`);
        const input2 = newTask.querySelector(`input[name="remote_tasks[${remote_index}][task_weight]`);
        const input3 = newTask.querySelector(`input[name="remote_tasks[${remote_index}][task_state]`);
        
        taskForm.appendChild(input1);
        taskForm.appendChild(input2);
        taskForm.appendChild(input3);
        
        const removeButton = newTask.querySelector('.button-remove');
        removeButton.addEventListener('click', () => {
            parentElement.removeChild(newTask);
            input3.value = "removed";
        });
        parentElement.appendChild(newTask);
        
        remote_index++;
}