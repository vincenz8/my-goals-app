<?php
include 'db-connection.php';
include 'page-elements/header.php';
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <script src="./script.js" defer></script>
        <title>My Goals App</title>
    </head>
    <body>     
        <div id="points" class="points">
            <h4>Points score today: <?php echo $pointsDaily; ?></h4>
            <h4>Points score this week: <?php echo $pointsWeekly; ?></h4>
        </div>
        <div>
            <form method="post" id="taskForm" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']) ?>">
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
                    <?php foreach ($fetchedTasks as $t): 
                        if ($t->taskState === "idle") { ?>
                            createRemoteTask("<?php echo $t->taskName; ?>", <?php echo $t->taskWeight; ?>, "<?php echo $t->taskState; ?>", "taskList");
                    <?php } endforeach; ?>
                    });
                    </script>
                </div>
                <h3>Finished Tasks</h3>
                <div id="finishedTasks" class="task-list">
                    <script>
                    document.addEventListener("DOMContentLoaded", function () {
                    <?php foreach ($fetchedTasks as $t): 
                        if ($t->taskState === "finished") { ?>
                            createRemoteTask("<?php echo $t->taskName; ?>", <?php echo $t->taskWeight; ?>, "<?php echo $t->taskState; ?>", "finishedTasks");
                    <?php } endforeach; ?>
                    });
                    </script>
                </div>
                <input type="submit" name="update" value="Update">
            </form>
        </div>
    </body>
</html>
<?php include 'page-elements/footer.php'; ?>