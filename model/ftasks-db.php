<?php

function fetch_tasks() {
    global $db;
    $fetchedTasks = [];
    $query = $db->query('SELECT * FROM day_tasks');
    while ($row = $query->fetchArray()) {
        $row['task_state'] === "pending" ? $row['task_state'] = "idle" : false;
        $fetchedTasks[] = $row;
    }
    return $fetchedTasks;
}

function move_finished_tasks() {
    global $db;
    $db->exec('INSERT INTO finished_tasks (task_name, task_weight, task_date, task_state) SELECT * FROM day_tasks WHERE task_state = "finished"');
    $db->exec('DELETE FROM day_tasks');
}