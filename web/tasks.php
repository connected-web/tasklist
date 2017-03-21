<?php

require('./php/FileCache.class.php');

session_start();
$auth = isset($_SESSION['mkv25_tasklist_auth']) ? $_SESSION['mkv25_tasklist_auth'] : false;
$tasks = false;

if($auth) {
  // read from cache
  $tasksCacheId = 'tasks-' . $auth['provider'] . '-' . $auth['uid'];
  $tasks = FileCache::readDataFromCache($tasksCacheId);
}

/*
  header('x-tasklist: Read and decode JSON file');
   $json_file = @file_get_contents('https://rawgit.com/connected-web/tasklist/master/state/tasklist.json');
   $tasks = json_decode($json_file);
   FileCache::storeDataInCache($tasks, $tasksCacheId);
*/

// minimum response
if(!$tasks) {
  $tasks = array(
    array(
      'text' => 'No tasks entered',
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
