<?php

/**
 * Tasklist HTML Index
 * 
 * The launch page for the html/js web interface
 * 
 * php version 7
 * 
 * @category   Index
 * @package    Tasklist
 * @subpackage Tasks
 * @author     John Beech <github@mkv25.net>
 * @license    https://choosealicense.com/no-permission/ UNLICENSED
 * @link       https://mvk25.net/tasklist/
 */

if ($_SERVER['HTTP_HOST'] !== 'localhost'
    && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")
) {
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}

if (isset($_GET['user'])) {
    $body = @file_get_contents('./templates/user.inc.html');
    $body = str_replace('{{USER_REF}}', $_GET['user'], $body);
} else {
    $body = @file_get_contents('./templates/main.inc.html');
}
$navigation = @file_get_contents('./templates/navigation.inc.html');

$output = $body;
$output = str_replace('{{NAVIGATION}}', $navigation, $output);
$output = str_replace('{{NOW}}', time(), $output);

echo $output;
