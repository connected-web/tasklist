<?php

/**
 * Delete task
 * 
 * POST /delete : delete a task for a user
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

        $updatedTasks = array();
        $success = false;
        foreach ($tasks as $index => $task) {
            if ($task['dateString'] == $dateString && $task['text'] == $text) {
                // remove this task
                $success = true;
            } else {
                $updatedTasks[] = $task;
            }
        }

        // write to cache
        if ($success) {
            FileCache::storeDataInCache($updatedTasks, $tasksCacheId);
        }

        // report back
        $result = array(
            'message' => ($success)
                ? 'Entry has been removed from the user cache'
                : 'Unable to find a matching entry in the user cache',
            'data' => array(
                'entryDate' => $entryDate,
                'dateString' => $dateString,
                'text' => $text
            ),
            'success' => $success
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
        'message' => 'Unable to delete data, not authorised',
        'error' => true
    );
}

// outpout JSON file
header('Content-Type: application/json');
echo json_encode($result);
