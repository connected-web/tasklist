<?php

require('./php/FileCache.class.php');

session_start();
$auth = isset($_SESSION['mkv25_tasklist_auth']) ? $_SESSION['mkv25_tasklist_auth'] : false;
$tasks = false;

if(isset($_GET['user'])) {
  // read from supplied cache
  $tasksCacheId = 'tasks-' . $_GET['user'];
  $tasks = FileCache::readDataFromCache($tasksCacheId);
}
else if($auth) {
  // read from authed cache
  $tasksCacheId = 'tasks-' . $auth['provider'] . '-' . $auth['uid'];
  $tasks = FileCache::readDataFromCache($tasksCacheId);
}
else {
  $tasks = array(
    array(
      'text' => 'No tasks stored remotely'
      'dateString' => 'Today',
      'entryDate' => time()
    )
  );
}

// minimum response
if(!$tasks) {
  $tasks = array(
    array(
      'text' => 'No tasks found',
      'dateString' => 'Today',
      'entryDate' => time()
    )
  );
}

// wrap tasks in object
$result = Array('tasks' => $tasks);

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
