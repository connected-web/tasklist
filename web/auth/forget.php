<?php

session_start();
$_SESSION['mkv25_tasklist_auth'] = false;
session_unset();
session_destroy();

$result = array(
  'auth' => false,
  'message' => 'Auth info removed; you have been forgotten'
);

header( 'Location: /tasklist/' );
header('Content-Type: application/json');
echo json_encode($result);
