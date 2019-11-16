<?php

$config['title'] = 'Новая задача';

$task = new Task();

$task->name = htmlspecialchars($_REQUEST['name'] ?? '');
$task->email = htmlspecialchars($_REQUEST['email'] ?? '');
$task->body = htmlspecialchars($_REQUEST['body'] ?? '');

$sent = ($_REQUEST['send'] ?? 0) == 1;

if ($sent) {
	if ($task->check()) {
		$task->save();
		$_SESSION['alert'] = 'Задача создана!';
		redirect('/');
	} else {
		$alert = $task->error;
	}
}