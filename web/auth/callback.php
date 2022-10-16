<?php
/**
 * Callback for Opauth
 *
 * An example on how to properly receive auth response of Opauth.
 *
 * Basic steps:
 * 1. Fetch auth response based on callback transport parameter in config.
 * 2. Validate auth response
 * 3. Once auth response is validated the PHP app can then work on the auth response
 *    (e.g. registers or logs user in to the site, save auth data to database, etc.)
 * 
 * php version 7
 * 
 * @category   Callback
 * @package    Tasklist
 * @subpackage Auth
 * @author     John Beech <github@mkv25.net>
 * @license    https://choosealicense.com/no-permission/ UNLICENSED
 * @link       https://mvk25.net/tasklist/
 */

require dirname(__FILE__).'/vendor/autoload.php';

/**
 * Define paths
 */
define('CONF_FILE', dirname(__FILE__).'/'.'opauth.conf.php');

/**
* Load config
*/
if (!file_exists(CONF_FILE)) {
    trigger_error('Config file missing at '.CONF_FILE, E_USER_ERROR);
    exit();
}
require CONF_FILE;

/**
 * Instantiate Opauth with the loaded config but not run automatically
 */
$Opauth = new Opauth($config, false);


/**
 * Format error string as HTML
 * 
 * @param string $type    the type of error
 * @param string $message the message to display
 * 
 * @return string HTML representing a bold, red, error message
 */
function formatError($type, $message)
{
    return '<b style="color: red;">' . $type . ': </b>'. $message . "<br>\n";
}

/**
 * Format success string as HTML
 * 
 * @param string $type    the type of success
 * @param string $message the message to display
 * 
 * @return string HTML representing a bold, red, error message
 */
function formatSuccess($type, $message)
{
    return '<b style="color: green;">' . $type . ': </b>' . $message . "<br>\n";
}

/**
* Fetch auth response, based on transport configuration for callback
*/
$response = null;

switch($Opauth->env['callback_transport']){
case 'session':
    session_start();
    $response = $_SESSION['opauth'];
    unset($_SESSION['opauth']);
    break;
case 'post':
    $response = unserialize(base64_decode($_POST['opauth']));
    break;
case 'get':
    $response = unserialize(base64_decode($_GET['opauth']));
    break;
default:
    $errMessage = 'Unsupported callback_transport.';
    echo formatError('Error', $errMessage);
    break;
}

/**
 * Check if it's an error callback
 */
if (!is_array($response)) {
    echo formatError(
        'Authentication error',
        'Opauth response was not in the expected format.'
    );
} else if (array_key_exists('error', $response)) {
    echo formatError(
        'Authentication error',
        'Opauth returns error auth response.'
    );
} else {
    /**
     * Auth response validation
     *
     * To validate that the auth response received is unaltered,
     * especially auth response that is sent through GET or POST.
     */
    $missingAuthKey
        = empty($response['auth'])
        || empty($response['timestamp'])
        || empty($response['signature'])
        || empty($response['auth']['provider'])
        || empty($response['auth']['uid']);
    if ($missingAuthKey) {
        echo formatError(
            'Invalid auth response',
            'Missing key auth response components.'
        );
    } elseif (!$Opauth->validate(
        sha1(print_r($response['auth'], true)),
        $response['timestamp'],
        $response['signature'], $reason
    )
    ) {
        echo formatError('Invalid auth response', $reason);
    } else {
        header('Location: /tasklist/');
        echo formatSuccess('OK', 'Auth response is validated.');

        /**
         * Store auth info on session
         */

        if (!session_id()) {
            session_start();
        }

        $_SESSION['mkv25_tasklist_auth'] = array(
            'uid' => $response['auth']['uid'],
            'info' => $response['auth']['info'],
            'provider' => $response['auth']['provider']
        );
    }
}

/**
* Auth response dump
*/
echo "<pre>";
echo json_encode($response, JSON_PRETTY_PRINT);
echo "</pre>";
