<?php

session_start();
$auth = $_SESSION['mkv25_tasklist_auth'];

// add session info
if($auth) {
  $result = $auth;
}
else {
  $result = array(
    'auth' => false,
    'message' => 'Auth info unavailable; please select an appropriate provider'
  );
}

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
