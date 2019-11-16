<?php

$config['title'] = 'Задача №'.$task->i;

$task->name = htmlspecialchars($_REQUEST['name'] ?? $task->name);
$task->email = htmlspecialchars($_REQUEST['email'] ?? $task->email);
$task->body = htmlspecialchars($_REQUEST['body'] ?? $task->body);

$sent = ($_REQUEST['send'] ?? 0) == 1;
if ($sent)
{
	$task->done = isset($_REQUEST['done']) ? 1 : 0;

	if ($task->check()) {
		$task->save();
		$_SESSION['alert'] = 'Задача изменена!';
		redirect('/');
	} else {
		$alert = $task->error;
	}
}