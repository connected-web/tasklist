<?php

require('./php/FileCache.class.php');

session_start();
$auth = isset($_SESSION['mkv25_tasklist_auth']) ? $_SESSION['mkv25_tasklist_auth'] : false;

if(isset($_GET['local'])) {
  // read from local file
  $json_file = str_replace('\r', '', file_get_contents(__DIR__ . '/../state/tasklist.json'));
  $tasks = json_decode($json_file);
}
else {
  // read from cache
  $tasksCacheId = 'tasks';
  $fiveMinutes = 5 * 60;
  if(FileCache::ageOfCache($tasksCacheId) < $fiveMinutes && FileCache::ageOfCache($tasksCacheId) !== false) {
    header('x-tasklist: Read from Cache ' . FileCache::ageOfCache($tasksCacheId));
    $tasks = FileCache::readDataFromCache($tasksCacheId);
  }
  else {
    // read and decode JSON file
    header('x-tasklist: Read and decode JSON file');
    $json_file = @file_get_contents('https://rawgit.com/connected-web/tasklist/master/state/tasklist.json');
    $tasks = json_decode($json_file);
    FileCache::storeDataInCache($tasks, $tasksCacheId);
  }
}

// minimum response
if(!$tasks) {
  $tasks = array(
    array(
      'text' => 'No tasks available',
      'dateString' => 'Today',
      'entryDate' => time()
    )
  );
}

// wrap tasks in object
$result = Array('tasks' => $tasks);

// add session info
if($auth) {
  $result['tasks'][] = array(
    'text' => 'Session storage available',
    'dateString' => 'Today',
    'entryDate' => time()
  );
}
else {
  $result['tasks'][] = array(
    'text' => 'Session storage unavailable',
    'dateString' => 'Today',
    'entryDate' => time()
  );
}

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
