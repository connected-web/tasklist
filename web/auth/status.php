<?php

session_start();
$auth = isset($_SESSION['mkv25_tasklist_auth']) ? $_SESSION['mkv25_tasklist_auth'] : false;

function provider($label, $id) {
  return array(
    'label' => $label,
    'id' => $id,
    'url' => './auth/' . $id
  );
}

// add session info
if($auth) {
  $result = array(
    'auth' => $auth,
    'message' => 'Auth info found; you have been signed in!',
    'providers' => array(
      array(
        'label' => 'Sign out',
        'id' => 'sign-out',
        'url' => './auth/forget'
      )
    )
  );
}
else {
  $result = array(
    'auth' => false,
    'message' => 'Auth info unavailable; please select an appropriate provider.',
    'providers' => array(
      provider('Facebook', 'facebook'),
      provider('Twitter', 'twitter'),
      provider('Google', 'google'),
      provider('GitHub', 'github')
    )
  );
}

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
