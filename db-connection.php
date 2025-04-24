<?php
$db = new SQLite3('db-test.db');
$currentDate = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {

    if (isset($_POST['local_tasks'])) {
        
        $localTasks = $_POST['local_tasks'];
        
        $newTasks = [];
        foreach ($localTasks as $task) {
            $obj = new stdClass();
            $obj->taskName = $task['task_name'];
            $obj->taskWeight = $task['task_weight'];
            $obj->taskState = $task['task_state'];
            $newTasks[] = $obj;
        }
        
        foreach ($newTasks as $t) {
            $stmt = $db->prepare('INSERT INTO day_tasks (task_name, task_weight, task_date, task_state) VALUES (:c1, :c2, :c3, :c4)');
            $stmt->bindValue(':c1', $t->taskName, SQLITE3_TEXT);
            $stmt->bindValue(':c2', $t->taskWeight, SQLITE3_INTEGER);
            $stmt->bindValue(':c3', $currentDate, SQLITE3_TEXT);
            if ($t->taskState === "idle") {
                $stmt->bindValue(':c4', "idle", SQLITE3_TEXT);
            } else {
                $stmt->bindValue(':c4', "finished", SQLITE3_TEXT);
            }
            $result = $stmt->execute();
            if ($result) {
                echo "Database insertion successful.";
            } else {
                echo "Database insertion failed: " . $db->lastErrorMsg();
            }
        }
    }
    if (isset($_POST['remote_tasks'])) {
        
        $remoteTasks = $_POST['remote_tasks'];
        
        $modifiedTasks = [];
        foreach ($remoteTasks as $task) {
            if ($task['task_state'] !== "idle") {
                $obj = new stdClass();
                $obj->taskName = $task['task_name'];
                $obj->taskWeight = $task['task_weight'];
                $obj->taskState = $task['task_state'];
                $modifiedTasks[] = $obj;
            }
        }
        
        foreach ($modifiedTasks as $t) {
            if ($t->taskState === "removed") {
                $stmt = $db->prepare('DELETE FROM day_tasks WHERE task_name = :c1');
            } else if ($t->taskState === "finished") {
                $stmt = $db->prepare('UPDATE day_tasks SET task_state = "finished" WHERE task_name = :c1');
            } else {
                $stmt = $db->prepare('UPDATE day_tasks SET task_state = "pending" WHERE task_name = :c1');
            }
            $stmt->bindValue(':c1', $t->taskName, SQLITE3_TEXT);
            $result = $stmt->execute();
            if ($result) {
                echo "Database operation successful.";
            } else {
                echo "Database operation failed: " . $db->lastErrorMsg();
            }
        }
    }
}

$fetchedTasks = [];
$query = $db->query('SELECT * FROM day_tasks');
while ($row = $query->fetchArray()) {
    $obj = new stdClass();
    $obj->taskName = $row['task_name'];
    $obj->taskWeight = $row['task_weight'];
    $row['task_state'] === "pending" ? $obj->taskState = "idle" : $obj->taskState = $row['task_state'];
    $fetchedTasks[] = $obj;
}
?>