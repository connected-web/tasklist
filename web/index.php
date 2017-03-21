<?php

if($_SERVER['HTTP_HOST'] !== 'localhost' && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")) {
  $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $redirect);
  exit();
}

$template = @file_get_contents('./templates/template.inc.html');
$navigation = @file_get_contents('./templates/navigation.inc.html');

$output = str_replace('{{NAVIGATION}}', $navigation, $template);
$output = str_replace('{{NOW}}', time(), $output);

echo $output;
