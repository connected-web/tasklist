<?php

require('./php/FileCache.class.php');

session_start();
$auth = isset($_SESSION['mkv25_tasklist_auth']) ? $_SESSION['mkv25_tasklist_auth'] : false;

function check($key) {
  return isset($_POST[$key]);
}

if($auth) {
  if(check('entryDate') && check('dateString') && check('text')) {
    // read from cache
    $tasksCacheId = 'tasks-' . $auth['provider'] . '-' . $auth['uid'];
    $tasks = FileCache::readDataFromCache($tasksCacheId);
    if(!$tasks) {
      $tasks = array();
    }

    // read from POST
    $entryDate = floatval($_POST['entryDate']);
    $dateString = $_POST['dateString'];
    $text = $_POST['text'];

    $updatedTasks = array();
    $success = false;
    foreach($tasks as $index => $task) {
      if(round($task['entryDate'], 2) == round($entryDate, 2) && $task['dateString'] == $dateString && $task['text'] == $text) {
        // remove this task
        $success = true;
      }
      else {
        $updatedTasks[] = $task;
      }
    }

    // write to cache
    FileCache::storeDataInCache($updatedTasks, $tasksCacheId);

    // report back
    $result = array(
      'message' => ($success) ? 'Entry has been removed from the user cache' : 'Unable to find matching entry in cache',
      'data' => array(
        'entryDate' => $entryDate,
        'dateString' => $dateString,
        'text' => $text
      ),
      'success' => $success
    );
  }
  else {
    // report error
    http_response_code(406);
    $result = array(
      'message' => 'Expected parameters not found in request',
      'error' => true
    );
  }
}
else {
  // report error
  http_response_code(401);
  $result = array(
    'message' => 'Unable to delete data, not authorised',
    'error' => true
  );
}

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
