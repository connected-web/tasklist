<?php

/**
 * GET /forget endpoint
 * 
 * Removes auth information and destroys the session
 * 
 * php version 7
 * 
 * @category   Endpoint
 * @package    Tasklist
 * @subpackage Forget
 * @author     John Beech <github@mkv25.net>
 * @license    https://choosealicense.com/no-permission/ UNLICENSED
 * @link       https://mvk25.net/tasklist/
 */

session_start();
$_SESSION['mkv25_tasklist_auth'] = false;
session_unset();
session_destroy();

$result = array(
  'auth' => false,
  'message' => 'Auth info removed; you have been forgotten'
);

header('Location: /tasklist/');
header('Content-Type: application/json');
echo json_encode($result);
