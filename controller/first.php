<?php

if (isset($_GET['q']) && is_string($_GET['q']))
{
	$q = $_GET['q'];
}
elseif ($_SERVER['REQUEST_URI'] == '/index.php')
{
	redirect('.');
}
elseif (isset($_SERVER['REQUEST_URI']))
{
	$request_path = strtok($_SERVER['REQUEST_URI'], '?');
	$base_path_len = strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/'));
	$q = substr(urldecode($request_path), $base_path_len + 1);
	if ($q == basename($_SERVER['PHP_SELF'])) {
		$q = '';
	}
} else {
	$q = '';
}

$q = preg_replace("!(\s*)(?:\.html?|\.php)$!", "$1", $q);
$q = preg_replace("!//+!", "/", "/$q");

// * * *

function redirect($where, $code = 302) {
	if ($where == 404) {
		$where = '/404';
		$code = 404;
	}
	switch ($code) {
		case 301: $info = 'Moved Permanently'; break;
		case 302: $info = 'Found'; break;
		case 303: $info = 'See Other'; break;
		case 304: $info = 'Not Modified'; break;
		case 305: $info = 'Use Proxy'; break;
		case 307: $info = 'Temporary Redirect'; break;
		case 404: $info = 'Not Found'; break;
		default: $info = ''; break;
	}
	header("HTTP/1.1 $code $info");
	header('Location: '.$where);
	exit();
}

session_start();

require_once('admin-logged.php');
if (($_SESSION['admin'] ?? 0) && (($_SESSION['logged'] ?? 0) < ($admin_logged ?? 0))) {
	$_SESSION['admin'] = 0;
}

if (strlen($_SESSION['alert'] ?? '')) {
	$alert = $_SESSION['alert'];
	$_SESSION['alert'] = '';
}

if ($q == '/') {
	require_once('controller/index.php');
	$config['body'] = 'view/index.html';
}

if (ctype_digit(substr($q, 1).'')) {
	if (!($_SESSION['admin'] ?? 0)) {
		redirect('/login');
	}

	$task = new Task(substr($q, 1));
	if (!$task->i) {
		redirect('/');
	}

	require_once('controller/task-edit.php');
	$config['body'] = 'view/task-edit.html';
}

if ($q == '/new') {
	require_once('controller/task-new.php');
	$config['body'] = 'view/task-new.html';
}

if ($q == '/login') {
	require_once('controller/login.php');
	$config['body'] = 'view/login.html';
}

if ($q == '/exit') {
	$_SESSION['admin'] = 0;
	file_put_contents('../admin-logged.php', '<?php $admin_logged='.time().';');
	redirect('/');
}

require_once('view/design.html');
