<?php

/**
 * GET /status
 * 
 * Auth status endpoint; provides auth details about current user.
 * If unauthorised the endpoint returns a list of configured auth methods.
 * 
 * php version 7
 * 
 * @category   Endpoint
 * @package    Tasklist
 * @subpackage Status
 * @author     John Beech <github@mkv25.net>
 * @license    https://choosealicense.com/no-permission/ UNLICENSED
 * @link       https://mvk25.net/tasklist/
 */

session_start();
$authKey = 'mkv25_tasklist_auth';
$auth = isset($_SESSION[$authKey]) ? $_SESSION[$authKey] : false;

$authUnavailableMessage
    = 'Auth info unavailable;'
    . ' please sign in using an appropriate provider.';

/**
 * Summarise a provider as key:property map for JSON
 * 
 * @param string $label name of the auth provider
 * @param string $id    internal config id of the auth provider
 * 
 * @return array a map containing label, id, and auth url
 */
function provider($label, $id)
{
    return array(
      'label' => $label,
      'id' => $id,
      'url' => './auth/' . $id
    );
}

// add session info
if ($auth) {
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
} else {
    $result = array(
    'auth' => false,
    'message' => $authUnavailableMessage,
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
