<?php

if($_SERVER['HTTP_HOST'] !== 'localhost' && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")) {
  $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $redirect);
  exit();
}

if(isset($_GET['user'])) {
  $body = @file_get_contents('./templates/user.inc.html');
  $body = str_replace('{{USER_REF}}', $_GET['user'], $body);
}
else {
  $body = @file_get_contents('./templates/main.inc.html');
}
$navigation = @file_get_contents('./templates/navigation.inc.html');

$output = $body;
$output = str_replace('{{NAVIGATION}}', $navigation, $output);
$output = str_replace('{{NOW}}', time(), $output);

echo $output;
