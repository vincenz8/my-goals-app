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

function build_sanitized_task($rawName, $rawWeight, $rawState) {
    $taskName = strip_tags(trim($rawName));
    $taskWeight = filter_var($rawWeight, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 4]
    ]);
    $taskState = strtolower(strip_tags(trim($rawState)));
    
    if (!empty($taskName) && $taskWeight && !empty($taskState)) {
        $sanitized_task = [
            'task_name' => $taskName,
            'task_weight' => $taskWeight,
            'task_state' => $taskState
        ];
    } else {
        $sanitized_task = [];
    }
    return $sanitized_task;
}

function sanitize_tasks($indexedInputs) {
    $taskList = [];
    $allowedKeys = ['task_name', 'task_weight', 'task_state'];
    
    foreach ($indexedInputs as $taskInput) {
        $receivedKeys = array_keys($taskInput);
        $unexpectedKeys = array_diff($receivedKeys, $allowedKeys);
        $missingKeys = array_diff($allowedKeys, $receivedKeys);

        if (empty($unexpectedKeys) && empty($missingKeys)) {
            $sanitized_task = build_sanitized_task($taskInput['task_name'], $taskInput['task_weight'], $taskInput['task_state']);
            $sanitized_task ? $taskList[] = $sanitized_task : false;
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