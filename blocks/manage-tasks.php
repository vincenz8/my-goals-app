<?php include 'header.php'; ?>
<div>
    <form action="." method="post" id="taskForm">
        <h3>To-do List</h3>
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
        <div id="taskList" class="task-list">
            <script>
            document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($fetchedTasks as $task): 
                if ($task['task_state'] === "idle") { ?>
                    createRemoteTask("<?= $task['task_name']; ?>", <?= $task['task_weight']; ?>, "<?= $task['task_state']; ?>", "taskList");
            <?php } endforeach; ?>
            });
            </script>
        </div>
        <h3>Finished Tasks</h3>
        <div id="finishedTasks" class="task-list">
            <script>
            document.addEventListener("DOMContentLoaded", function () {
            <?php foreach ($fetchedTasks as $task): 
                if ($task['task_state'] === "finished") { ?>
                    createRemoteTask("<?= $task['task_name']; ?>", <?= $task['task_weight']; ?>, "<?= $task['task_state']; ?>", "finishedTasks");
            <?php } endforeach; ?>
            });
            </script>
        </div>
        <input type="submit" name="update" value="Update">
    </form>
</div>
<?php include 'footer.php';

