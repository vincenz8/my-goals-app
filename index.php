<?php

$db = new SQLite3('db-test.db');

require 'model/dtasks-db.php';
require 'model/ftasks-db.php';
require 'model/points-db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update'])) {

    if (isset($_POST['local_tasks']) && check_array_structure($_POST['local_tasks'])) {
        $localTasks = sanitize_tasks($_POST['local_tasks']);
        foreach ($localTasks as $task) {
            insert_task($task);
        }
    }
    
    if (isset($_POST['remote_tasks']) && check_array_structure($_POST['remote_tasks'])) {
        $remoteTasks = sanitize_tasks($_POST['remote_tasks']);
        foreach ($remoteTasks as $task) {
            modify_task($task);
        }
    }
    header("Location: index.php");
    exit();
}

$fetchedTasks = fetch_tasks();

if ($fetchedTasks) {
    $taskFinished = 0;
    foreach ($fetchedTasks as $task) {
        if ($task['task_state'] == "finished") {
            $taskFinished++;
        }
    }
    if ($taskFinished) {
        $goalProgress = round(($taskFinished / count($fetchedTasks)) * 100);
    } else {
        $goalProgress = "0";
    }
    
    if (date("Y-m-d", strtotime($fetchedTasks[0]['task_date'])) !== date("Y-m-d")) {
        $accumulatedPoints = 0;
        foreach ($fetchedTasks as $task) {
            if ($task['task_state'] === "finished") {
                $accumulatedPoints += calculate_points_ftasks($task);
            } else {
                $accumulatedPoints += calculate_points_ptasks($task);
            }
        }
        insert_points($accumulatedPoints, $fetchedTasks[0]['task_date']);
        move_finished_tasks();
        $fetchedTasks = [];
    }
}

$action = htmlspecialchars($_POST['action']);
if (!$action) {
    $action = htmlspecialchars($_GET['action']);
    if (!$action) {
        $action = 'manage-tasks'; // assigning default value if NULL or FALSE
    }
}

switch ($action) {
    default:
        include 'blocks/manage-tasks.php';
}