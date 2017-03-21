<?php

require('./php/FileCache.class.php');

session_start();
$auth = isset($_SESSION['mkv25_tasklist_auth']) ? $_SESSION['mkv25_tasklist_auth'] : false;
$tasks = false;

if($auth) {
  // read from cache
  $tasksCacheId = 'tasks-' . $auth['provider'] . '-' . $auth['uid'];
  $tasks = FileCache::readDataFromCache($tasksCacheId);

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
}

else {
  $tasks = array(
    array(
      'text' => 'Session storage unavailable',
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
