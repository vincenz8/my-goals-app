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

$fetchedDailyTasks = fetch_daily_tasks();

if ($fetchedDailyTasks) {
    $taskFinished = 0;
    foreach ($fetchedDailyTasks as $task) {
        if ($task['task_state'] == "finished") {
            $taskFinished++;
        }
    }
    if ($taskFinished) {
        $goalProgress = round(($taskFinished / count($fetchedDailyTasks)) * 100);
    } else {
        $goalProgress = "0";
    }
    
    if (date("Y-m-d", strtotime($fetchedDailyTasks[0]['task_date'])) !== date("Y-m-d")) {
        $accumulatedPoints = 0;
        foreach ($fetchedDailyTasks as $task) {
            if ($task['task_state'] === "finished") {
                $accumulatedPoints += calculate_points_ftasks($task);
            } else {
                $accumulatedPoints += calculate_points_ptasks($task);
            }
        }
        insert_points($accumulatedPoints, $fetchedDailyTasks[0]['task_date']);
        move_finished_tasks();
        $fetchedDailyTasks = [];
    }
}

$action = htmlspecialchars($_POST['action']);
if (!$action) {
    $action = htmlspecialchars($_GET['action']);
    if (!$action) {
        $action = 'Manage Tasks'; // assigning default value if NULL or FALSE
    }
}

switch ($action) {
    case 'Progress Tracker':
        include 'pages/progress-tracker.php';
        break;
    default:
        include 'pages/manage-tasks.php';
}