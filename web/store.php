<?php

/**
 * Store task
 * 
 * POST /store : store a task for a user
 * 
 * php version 7
 * 
 * @category   Endpoint
 * @package    Tasklist
 * @subpackage Tasks
 * @author     John Beech <github@mkv25.net>
 * @license    https://choosealicense.com/no-permission/ UNLICENSED
 * @link       https://mvk25.net/tasklist/
 */

require './php/FileCache.class.php';

session_start();
$authKey = 'mkv25_tasklist_auth';
$auth = isset($_SESSION[$authKey]) ? $_SESSION[$authKey] : false;

/**
 * Check that POST key exists
 * 
 * @param string $key the key to check for
 * 
 * @return bool true if key exists in $_POST
 */
function check($key)
{
    return isset($_POST[$key]);
}

/**
 * Sanitize incoming values from user
 * 
 * @param string $string the value to sanitize
 * 
 * @return string the sanitized value
 */
function sanitize($string)
{
    return html_entity_decode($string);
}

if ($auth) {
    if (check('entryDate') && check('dateString') && check('text')) {
        // read from cache
        $tasksCacheId = 'tasks-' . $auth['provider'] . '-' . $auth['uid'];
        $tasks = FileCache::readDataFromCache($tasksCacheId);
        if (!$tasks) {
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
    } else {
        // report error
        http_response_code(406);
        $result = array(
        'message' => 'Expected parameters not found in request',
        'error' => true
        );
    }
} else {
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
