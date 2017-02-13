<?php

require('./php/FileCache.class.php');

if(isset($_GET['local'])) {
  // read from local file
  $json_file = str_replace('\r', '', file_get_contents(__DIR__ . '/../state/tasklist.json'));
  $tasks = json_decode($json_file);
}
else {
  // read from cache
  $tasksCacheId = 'tasks';
  $fiveMinutes = 5 * 60;
  if(FileCache::ageOfCache($tasksCacheId) < $fiveMinutes) {
    $tasks = FileCache::readDataFromCache($tasksCacheId);
  }
  else {
    // read and decode JSON file
    $json_file = @file_get_contents('https://rawgit.com/connected-web/tasklist/master/state/tasklist.json');
    $tasks = json_decode($json_file);
    FileCache::storeDataInCache($tasks, $tasksCacheId);
  }
}

// minimum response
if(!$tasks) {
  $tasks = (Array(
    Array(
      'text' => 'No tasks available',
      'dateString' => 'Today',
      'entryDate' => time()
    )
  ));
}

// wrap tasks in object
$result = Array('tasks' => $tasks);

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
