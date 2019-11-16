<?php

class Task
{
	public $i = 0;
	public $name = '';
	public $email = '';
	public $body = '';
	private $old_body = '';
	public $done = 0;
	private $red = 0;
	public $error = '';

	public function __construct($id = 0)
	{
		if ($id > 0)
		{
			global $config;
			$db = $config['db'];

			$db->query('SELECT * FROM task WHERE i='.$id);
			if ($row = $db->fetch()) {
				$this->i = $id;
				$this->name = $row['name'];
				$this->email = $row['email'];
				$this->body = $row['body'];
				$this->old_body = $row['body'];
				$this->done = $row['done'];
				$this->red = $row['red'];
			}
		}
	}

	public static function is_mail($mail) {
		$user = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\`\|\{\}~\']{1,100}';
		$domain = '(?:(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.?){2,100}';
		$ipv4 = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
		$ipv6 = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';

		return preg_match("/^$user@($domain|(\[($ipv4|$ipv6)\]))$/", $mail);
	}

	public function check()
	{
		if (strlen($this->name) < 5)
		{
			$this->error = 'Поле "Имя" должно быть заполнено!';
			return false;
		}
		if (strlen($this->email) < 5 || !$this->is_mail($this->email))
		{
			$this->error = 'Поле "Почта" должно содержать почтовый ящик!';
			return false;
		}
		if (strlen($this->body) < 5) {
			$this->error = 'Задача должна быть поставлена!';
			return false;
		}
		return true;
	}

	public function save()
	{
		global $config;
		$db = $config['db'];

		if ($this->i == 0) {
			$db->insert('task', ['name'=>$this->name, 'email'=>$this->email, 'body'=>$this->body, 'done'=>$this->done, 'red'=>$this->red]);
			$this->i = $db->insert_id();
		} else {
			if ($this->body != $this->old_body) {
				$this->red = 1;
			}
			$db->update('task', array('name'=>$this->name, 'email'=>$this->email, 'body'=>$this->body, 'done'=>$this->done, 'red'=>$this->red), array('i'=>$this->i));
		}
	}

	public static function count()
	{
		global $config;
		return $config['db']->result('SELECT COUNT(*) FROM task');
	}
}