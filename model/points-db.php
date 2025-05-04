<?php

function calculate_points_ftasks($task) {
    $score = 0;
    switch ($task['task_weight']) {
        case 1:
            $score++;
            break;
        case 2:
            $score += 4;
            break;
        case 3:
            $score += 20;
            break;
        case 4:
            $score += 100;
            break;
    }
    return $score;
}

function calculate_points_ptasks($task) {
    $score = 0;
    switch ($task['task_weight']) {
        case 2:
            $score -= 2;
            break;
        case 3:
            $score -= 10;
            break;
        case 4:
            $score -= 50;
            break;
    }
    return $score;
}

function insert_points($score, $date) {
    global $db;
    $stmt = $db->prepare('INSERT INTO points (score, date) VALUES (:c1, :c2)');
    $stmt->bindValue(':c1', $score, SQLITE3_INTEGER);
    $stmt->bindValue(':c2', $date, SQLITE3_TEXT);
    $stmt->execute();
}