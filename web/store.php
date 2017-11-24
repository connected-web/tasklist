<?php

require('./php/FileCache.class.php');

session_start();
$auth = isset($_SESSION['mkv25_tasklist_auth']) ? $_SESSION['mkv25_tasklist_auth'] : false;

function check($key) {
  return isset($_POST[$key]);
}

function sanitize($string) {
  return html_entity_decode($string);
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
    $dateString = sanitize($_POST['dateString']);
    $text = sanitize($_POST['text']);

    // add new task
    $tasks[] = array(
      'entryDate' => $entryDate,
      'dateString' => $dateString,
      'text' => $text
    );

    // write to cache
    FileCache::storeDataInCache($tasks, $tasksCacheId);

    // report back
    $result = array(
      'message' => 'Entry has been added to user cache',
      'data' => array(
        'entryDate' => $entryDate,
        'dateString' => $dateString,
        'text' => $text
      ),
      'success' => true
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
    'message' => 'Unable to store data, not authorised',
    'error' => true
  );
}

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
