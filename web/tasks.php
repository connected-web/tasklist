<?php

// read and decode JSON file
$json_file = @file_get_contents('data/tasklist-cache.json');

if(!$json_file) {
  $json_file = json_encode(Array(
    Array(
      'text' => 'No tasks available',
      'dateString' => 'Today',
      'entryDate' => time()
    )
  ));
}

$tasks = Array('tasks' => json_decode($json_file));

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($tasks);
