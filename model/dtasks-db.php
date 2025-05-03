<?php

function check_array_structure($input) {
    $result = true;
    if (!is_array($input)) {
        $result = false;
    } else {
        foreach ($input as $i) {
            if (!is_array($i)) {
                $result = false;
                break;
            }
        }
    }
    return $result;
}

function sanitize_tasks($indexedInputs) {
    $taskList = [];
    $allowedKeys = ['task_name', 'task_weight', 'task_state'];
    
    foreach ($indexedInputs as $taskInput) {
        $receivedKeys = array_keys($taskInput);
        $unexpectedKeys = array_diff($receivedKeys, $allowedKeys);

        if (empty($unexpectedKeys)) {
            $filters = [
                'task_name' => FILTER_SANITIZE_STRING,
                'task_weight' => FILTER_SANITIZE_NUMBER_INT,
                'task_state' => FILTER_SANITIZE_STRING
            ];

            $taskList[] = filter_var_array($taskInput, $filters);
        }
    }
    return $taskList;
}

function insert_task($task) {      
    global $db;
    $stmt = $db->prepare('INSERT INTO day_tasks (task_name, task_weight, task_date, task_state) VALUES (:c1, :c2, :c3, :c4)');
    $stmt->bindValue(':c1', $task['task_name'], SQLITE3_TEXT);
    $stmt->bindValue(':c2', $task['task_weight'], SQLITE3_INTEGER);
    $stmt->bindValue(':c3', date("Y-m-d"), SQLITE3_TEXT);
    
    if ($task['task_state'] === "pending") {
        $stmt->bindValue(':c4', "pending", SQLITE3_TEXT);
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
function modify_task($task) {    
    global $db;
    if ($task['task_state'] !== "idle") {
        
        if ($task['task_state'] === "removed") {
            $stmt = $db->prepare('DELETE FROM day_tasks WHERE task_name = :c1');
            
        } else if ($task['task_state'] === "finished") {
            $stmt = $db->prepare('UPDATE day_tasks SET task_state = "finished" WHERE task_name = :c1');
            
        } else {
            $stmt = $db->prepare('UPDATE day_tasks SET task_state = "pending" WHERE task_name = :c1');
        }
        
        $stmt->bindValue(':c1', $task['task_name'], SQLITE3_TEXT);
        $result = $stmt->execute();
        
        if ($result) {
            echo "Database operation successful.";
        } else {
            echo "Database operation failed: " . $db->lastErrorMsg();
        }
    }
}