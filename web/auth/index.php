<?php
/**
 * Opauth Index
 *
 * This page instantiates Opauth, and redirects on successful auth.
 * This strategy is also implemented as a JSON endpoint.
 * For this example, Opauth config is loaded from a separate file: opauth.conf.php
 * 
 * php version 7
 * 
 * @category   Index
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
    trigger_error('Config file missing at '. CONF_FILE, E_USER_ERROR);
    exit();
}
require CONF_FILE;

/**
 * Format errors as JSON
 * 
 * @param Array $data the data to encode
 * 
 * @return JSON encoded data
 */
function displayErrors($data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}

/**
 * Handle errors by capturing details to $setupErrors
 * 
 * @param int    $errno      the error number
 * @param string $errstr     the error string
 * @param string $errfile    the error file
 * @param int    $errline    the error line
 * @param string $errcontext the error context
 * 
 * @return void
 */
function handleErrors($errno, $errstr, $errfile, $errline, $errcontext)
{
    $setupErrors[] = array(
    'no' => $errno,
    'str' => $errstr,
    'file' => $errfile,
    'line' => $errline,
    'context' => $errcontext
    );
}

/**
 * Instantiate Opauth with the loaded config
 */
$setupErrors = array();
set_error_handler(handleErrors);
$Opauth = new Opauth($config);
restore_error_handler();

?>
<html>
<body>
  <pre><?php displayErrors($setupErrors); ?></pre>
    <p>Log in with:</p>
    <ul>
        <li><a href="./facebook" class="login facebook">Facebook</a></li>
        <li><a href="./google" class="login google">Google</a></li>
        <!-- <li><a href="./twitter" class="login twitter">Twitter</a></li> -->
        <li><a href="./github" class="login github">Github</a></li>
    </ul>
</body>
</html>
