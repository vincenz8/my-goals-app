<?php include 'header.php'; ?>
<form action="." method="post" id="taskForm">
    <div id="addTaskField" style="display: inline">
        <input type="text" id="taskName" placeholder="What to do?">
        <select id="taskWeight" name="taskWeight">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>
        <button type="button" id="buttonAdd">Add Task</button>
    </div>
    <div class="container">
        <div id="taskList" class="task-list">
            <h3>To-do List</h3>
            <script>
            document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($fetchedTasks as $task): 
                if ($task['task_state'] === "idle") { ?>
                    createRemoteTask("<?= $task['task_name']; ?>", <?= $task['task_weight']; ?>, "<?= $task['task_state']; ?>", "taskList");
            <?php } endforeach; ?>
            });
            </script>
        </div>
        <div id="finishedTasks" class="task-list">
            <h3>Finished Tasks</h3>
            <script>
            document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($fetchedTasks as $task): 
                if ($task['task_state'] === "finished") { ?>
                    createRemoteTask("<?= $task['task_name']; ?>", <?= $task['task_weight']; ?>, "<?= $task['task_state']; ?>", "finishedTasks");
            <?php } endforeach; ?>
            });
            </script>
        </div>
    </div>
    <input type="submit" name="update" value="Update">
</form>
<?php include 'footer.php';

