<?php

require('./php/FileCache.class.php');

// read from cache
$tasksCacheId = 'tasks';
$fiveMinutes = 5 * 60;
if(FileCache::ageOfCache($tasksCacheId) < $fiveMinutes) {
  $tasks = FileCache::readDataFromCache($tasksCacheId);
}
else {
  // read and decode JSON file
  $json_file = @file_get_contents('https://rawgit.com/connected-web/tasklist/master/state/tasklist.json');
  if(!$json_file) {
    $json_file = json_encode(Array(
      Array(
        'text' => 'No tasks available',
        'dateString' => 'Today',
        'entryDate' => time()
      )
    ));
  }
  $tasks = json_decode($json_file);
  FileCache::storeDataInCache($tasks, $tasksCacheId);
}

// wrap tasks in object
$result = Array('tasks' => $tasks);

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
