<?php

$config['title'] = 'Вход';

$login = $_REQUEST['login'] ?? '';
$password = $_REQUEST['password'] ?? '';
$sent = ($_REQUEST['send'] ?? 0) == 1;

if ($sent) {
	if ($login == 'admin' && $password == '123') {
		$_SESSION['admin'] = 1;
		$_SESSION['logged'] = time();
		file_put_contents('../admin-logged.php', '<?php $admin_logged='.time().';');
		redirect('/');
	} elseif (strlen($login) == 0) {
		$alert = 'Введите адрес электронной почты';
	} elseif (strlen($password) == 0) {
		$alert = 'Введите пароль';
	} else {
		$alert = 'Реквизиты входа не верны';
	}
}

