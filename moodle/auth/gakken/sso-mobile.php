<?php

global $CFG, $USER, $SESSION, $DB;

require('../../config.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/group/lib.php');
require_once($CFG->dirroot."/lib/enrollib.php");

// Check request headers
$headers = apache_request_headers();

if (!isset($headers['api-app']) || !isset($headers['api-app-key'])) {
	http_response_code(200);
	echo "headers not set";
	return;
}
if (!$headers['api-app'] || !$headers['api-app-key']) {
	http_response_code(200);
	echo "headers not set";
	return;
}

// Check get params
if (!isset($_GET['email']) || !isset($_GET['redirect'])) {
	http_response_code(401);
	echo "params not set";
	return;
}
if (!$_GET['email'] || !$_GET['redirect']) {
	http_response_code(401);
	echo "params not set";
	return;
}

if (!preg_match('/^https:\/\/p2kb\.sejawat\.co\.id\/moodle\/mod\//', $_GET['redirect'])) {
	http_response_code(500);
	return;
}

$app = $headers['api-app'];
$key = $headers['api-app-key'];

// Check if authorized
$allowedApps = [
	'mobile-test' => 'gk-secretYB3hMpEoMfD7lQssO503T11LQY5V3B77',
	'mobile-android' => 'gk-secretpMNknvaLSIg8ZFiQmuoQ7TH0qDq1fo6f'
];
if (!isset($allowedApps[$app])) {
	http_response_code(401);
	return;
}
if ($allowedApps[$app] != $key) {
	http_response_code(401);
	return;
}

$email = urldecode($_GET['email']);

$authplugin = get_auth_plugin('gakken');
if ($authplugin->user_login($email)) {
	$user = get_complete_user_data('username', $email);
	complete_user_login($user);
	header('Location: ' . urldecode($_GET['redirect']), true, 303);
   	die();
}
else echo "email not found";
