<?

class Database
{
	private $db = null;
	private $res = null;

	public function __construct()
	{
		global $config;

		$this->db = new PDO('mysql:host='.$config['db-host'].';charset=UTF8mb4;dbname='.$config['db-name'],
			$config['db-user'],
			$config['db-pass']);

		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	public function query($query)
	{
		try {
			$this->res = $this->db->prepare($query);
			$this->res->execute();
			return TRUE;
		} catch (PDOException $ex) {
			echo '<p class="error-db">'.$ex->getMessage().'<br />'.$query.'</p>';
			return FALSE;
		}
	}

	public function fetch()
	{
		return $this->res->fetch(PDO::FETCH_ASSOC);
	}

	public function result($query)
	{
		$this->query($query);
		if ($this->res !== FALSE && $this->res->rowCount() > 0) {
			$array = $this->res->fetch(PDO::FETCH_NUM);
			return $array[0];
		}
		return FALSE;
	}

	public function insert($table, $data)
	{
		$query = "INSERT INTO $table (";
		$values = array();
		foreach ($data as $key=>$value) {
			$query.= $key.',';
			$values[] = ':'.$key;
		}
		$query = substr($query, 0, strlen($query) - 1);
		$query.= ') VALUES ('.implode(',', $values).')';

		$r = $this->db->prepare($query);
		foreach ($data as $key=>$value) {
			if (is_bool($value)) {
				$r->bindValue(':'.$key, $value, PDO::PARAM_BOOL);
			} else {
				$r->bindValue(':'.$key, $value);
			}
		}

		try {
			return $r->execute();
		} catch (PDOException $ex) {
			echo '<p class="error-db">'.$ex->getMessage().'<br />'.$query.'</p>';
			return FALSE;
		}
	}

	public function insert_id()
	{
		return $this->db->lastInsertId();
	}

	public function update($table, $data, $where)
	{
		$query = "UPDATE $table SET ";
		if (is_array($data)) {
			$a = array();
			foreach ($data as $key => $value) {
				$a[] = is_int($key) ? $value : $key.'=:'.$key;
			}
			$query.= implode(',', $a);
		} else {
			$query.= $data;
		}	
		$query.= ' WHERE ';
		if (is_array($where)) {
			$a = array();
			foreach ($where as $key => $value) {
				$a[] = is_int($key) ? $value : $key.'=:w_'.$key;
			}
			$query.= implode(' AND ', $a);
		} else {
			$query.= $where;
		}

		$r = $this->db->prepare($query);

		if (is_array($data)) {
			foreach ($data as $key=>$value) {
				if (!is_int($key)) {
					if (is_bool($value)) {
						$r->bindValue(':'.$key, $value, PDO::PARAM_BOOL);
					} else {
						$r->bindValue(':'.$key, $value);
					}
				}
			}
		}

		if (is_array($where)) {
			foreach ($where as $key=>$value) {
				if (!is_int($key)) {
					if (is_bool($value)) {
						$r->bindValue(':w_'.$key, $value, PDO::PARAM_BOOL);
					} else {
						$r->bindValue(':w_'.$key, $value);
					}
				}
			}
		}

		try {
			return $r->execute();
		} catch (PDOException $ex) {
			echo '<p class="error-db">'.$ex->getMessage().'<br />'.$query.'<br />'.var_dump($where).'</p>';
			return FALSE;
		}
	}
}