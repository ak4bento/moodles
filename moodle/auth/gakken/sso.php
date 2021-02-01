<?php

global $CFG, $USER, $SESSION, $DB;

require('../../config.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/group/lib.php');
require_once($CFG->dirroot."/lib/enrollib.php");

if (!isset($_COOKIE['gksession']))
	return;
if (!$_COOKIE['gksession'])
	return;

$web_url = "http://sejawat.co.id";
$status_url = $web_url . '/app/rest/status';
// var_dump($status_url);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $status_url);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_COOKIE, "gksession=" . $_COOKIE['gksession']);

$response = curl_exec($curl);
curl_close($curl);
$accounts_user = json_decode($response);
// return var_dump($accounts_user);
if ($accounts_user->status == 'false')
	return;
if ($USER->id != 0)
	if ($USER->email == $accounts_user->userEmail) {
		header('Location: ' . urldecode($_GET['redirect']), true, 302);
		die();
	}

$authplugin = get_auth_plugin('gakken');
if ($authplugin->user_login($accounts_user->userEmail)) {
	$user = get_complete_user_data('username', $accounts_user->userEmail);
	complete_user_login($user);
	header('Location: ' . urldecode($_GET['redirect']), true, 302);
	die();
}
