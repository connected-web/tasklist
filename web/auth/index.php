<?php
/**
 * Opauth example
 *
 * This is an example on how to instantiate Opauth
 * For this example, Opauth config is loaded from a separate file: opauth.conf.php
 *
 */

require(dirname(__FILE__).'/vendor/autoload.php');

/**
 * Define paths
 */
define('CONF_FILE', dirname(__FILE__).'/'.'opauth.conf.php');

/**
* Load config
*/
if (!file_exists(CONF_FILE)){
	trigger_error('Config file missing at '.CONF_FILE, E_USER_ERROR);
	exit();
}
require CONF_FILE;

function displayErrors($data) {
  return json_encode($data, JSON_PRETTY_PRINT);
}

function handleErrors($errno, $errstr, $errfile, $errline, $errcontext) {
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
$Opauth = new Opauth( $config );
restore_error_handler();

?>
<html>
<body>
  <pre><?php displayErrors($setupErrors); ?></pre>
	<p>Log in with:</p>
	<ul>
		<li><a href="./facebook" class="login facebook">Facebook</a></li>
		<li><a href="./google" class="login google">Google</a></li>
		<li><a href="./twitter" class="login twitter">Twitter</a></li>
		<li><a href="./github" class="login github">Github</a></li>
	</ul>
</body>
</html>
