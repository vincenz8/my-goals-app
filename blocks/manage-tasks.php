<?php include 'header.php'; ?>
<span class="separator x-ax"><hr></span>
<form action="." method="post" id="taskForm">
    <div id="taskMenu" class="input-params">
        <input type="submit" class="cursor-pointer" name="update" value="Update">
        <div id="addTaskField">
            <input type="text" id="taskName" placeholder="What to do?">
            <select id="taskWeight" name="taskWeight">
                <option value="1">Unimportant</option>
                <option value="2">Important but non-urgent</option>
                <option value="3">Urgent but unimportant</option>
                <option value="4">Urgent and important</option>
            </select>
        </div>
        <button type="button" id="buttonAdd" class="cursor-pointer">Add Task</button>
    </div>
    <div class="task-container">
        <div id="taskList" class="task-list">
            <h1>To-do List</h1>
            <div id="weight-4l" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-4l')">Urgent and important</h2>
            </div>
            <div id="weight-3l" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-3l')">Urgent but unimportant</h2>
            </div>
            <div id="weight-2l" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-2l')">Important but non-urgent</h2>
            </div>
            <div id="weight-1l" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-1l')">Unimportant</h2>
            </div>
            <script>
            document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($fetchedTasks as $task): 
                if ($task['task_state'] === "idle") { ?>
                    createRemoteTask("<?= $task['task_name']; ?>", <?= $task['task_weight']; ?>, "<?= $task['task_state']; ?>");
            <?php } endforeach; ?>
            });
            </script>
        </div>
        <hr>
        <div id="finishedTasks" class="task-list">
            <h1>Finished Tasks</h1>
            <div id="weight-4r" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-4r')">Urgent and important</h2>
            </div>
            <div id="weight-3r" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-3r')">Urgent but unimportant</h2>
            </div>
            <div id="weight-2r" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-2r')">Important but non-urgent</h2>
            </div>
            <div id="weight-1r" class="task-weight-group">
                <h2 class="cursor-default" onclick="toggleHideTasks('weight-1r')">Unimportant</h2>
            </div>
            <script>
            document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($fetchedTasks as $task): 
                if ($task['task_state'] === "finished") { ?>
                    createRemoteTask("<?= $task['task_name']; ?>", <?= $task['task_weight']; ?>, "<?= $task['task_state']; ?>");
            <?php } endforeach; ?>
            });
            </script>
        </div>
    </div>
    <div class="instructions">
        <h1>How to create a task?</h1>
        <span>
            <p><b>1. </b>Type the description of the task you'd like to create in the "What to do?" field.</p>
            <p><b>2. </b>Select one of four relevancy categories for the task:</p>
            <ul>
                <li>Unimportant (lowest), worth 1 point;</li><br>
                <li>Important but non-urgent, worth 4 points;</li><br>
                <li>Urgent but unimportant, worth 20 points;</li><br>
                <li>Urgent and important (highest), worth 100 points.</li>
            </ul>
            <p><b>3. </b>Press 'Add Task' to create the task locally, then press 'Update' to upload it to the database.</p>
            <p><b>IMPORTANT! </b>If you only press 'Add Task' and reload the page, the task will disappear.</p><br>
        </span>
    </div>
    <span class="separator x-ax"><hr></span>
</form>
<?php include 'footer.php';

