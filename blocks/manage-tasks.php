<?php include 'header.php'; ?>
<span class="separator x-ax">
    <hr>
</span>
<form action="." method="post" id="taskForm">
    <div id="taskMenu">
        <input type="submit" class="custom" name="update" value="Update">
        <div id="addTaskField">
            <input type="text" id="taskName" class="custom" placeholder="What to do?">
            <select id="taskWeight" name="taskWeight" class="param">
                <option value="1">Unimportant</option>
                <option value="2">Important but non-urgent</option>
                <option value="3">Urgent but unimportant</option>
                <option value="4">Urgent and important</option>
            </select>
        </div>
        <button type="button" id="buttonAdd">Add Task</button>
    </div>
    <div class="task-container">
        <div id="taskList" class="task-list">
            <h3>To-do List</h3>
            <div id="weight-1l" class="task-weight-group">
                <h4>Unimportant</h4>
            </div>
            <div id="weight-2l" class="task-weight-group">
                <h4>Important but non-urgent</h4>
            </div>
            <div id="weight-3l" class="task-weight-group">
                <h4>Urgent but unimportant</h4>
            </div>
            <div id="weight-4l" class="task-weight-group">
                <h4>Urgent and important</h4>
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
            <h3>Finished Tasks</h3>
            <div id="weight-1r" class="task-weight-group">
                <h4>Unimportant</h4>
            </div>
            <div id="weight-2r" class="task-weight-group">
                <h4>Important but non-urgent</h4>
            </div>
            <div id="weight-3r" class="task-weight-group">
                <h4>Urgent but unimportant</h4>
            </div>
            <div id="weight-4r" class="task-weight-group">
                <h4>Urgent and important</h4>
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
</form>
<?php include 'footer.php';

