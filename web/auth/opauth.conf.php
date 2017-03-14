<?php

require(dirname(__FILE__).'/secrets.php');

$config = array(
/**
 * Path where Opauth is accessed.
 *  - Begins and ends with /
 *  - eg. if Opauth is reached via http://example.org/auth/, path is '/auth/'
 *  - if Opauth is reached via http://auth.example.org/, path is '/'
 */
	'path' => '/tasklist/auth/',

/**
 * Callback URL: redirected to after authentication, successful or otherwise
 */
	'callback_url' => '{path}callback.php',

/**
 * A random string used for signing of $auth response.
 */
	'security_salt' => $OPAUTH_SECURITY_SALT,
/**
 * Strategy
 * Refer to individual strategy's documentation on configuration requirements.
 */
	'Strategy' => array(
		// Define strategies and their respective configs here

		'Facebook' => array(
			'app_id' => $FACEBOOK_APP_ID,
			'app_secret' => $FACEBOOK_APP_SECRET
		),

		'Google' => array(
			'client_id' => $GOOGLE_CLIENT_ID,
			'client_secret' => $GOOGLE_CLIENT_SECRET
		),

		'Twitter' => array(
			'key' => $TWITTER_AUTH_KEY,
			'secret' => $TWITTER_AUTH_SECRET
		),

    'GitHub' => array(
      'client_id' => $GITHUB_CLIENT_ID,
      'client_secret' => $GITHUB_CLIENT_SECRET
    )

	),
);
