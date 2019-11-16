<?

$count = Task::count();
$limit = 3;

$pages = ceil($count / $limit);

$page = preg_replace('@[^0-9]+@', '', $_REQUEST['page'] ?? ($_SESSION['page'] ?? 1));
$sort = preg_replace('@[^0-9]+@', '', $_REQUEST['sort'] ?? ($_SESSION['sort'] ?? 1));

$_SESSION['page'] = $page;
$_SESSION['sort'] = $sort;

switch ($sort) {
	case 1: $order = 'name'; break;
	case 11: $order = 'name DESC'; break;
	case 2: $order = 'email'; break;
	case 12: $order = 'email DESC'; break;
	case 3: $order = 'body'; break;
	case 13: $order = 'body DESC'; break;
	case 4: $order = 'done,red'; break;
	case 14: $order = 'done DESC,red DESC'; break;
	default: $order = ''; $sort = 1;
}

$link = '?';
if ($sort > 1) {
	$link.= 'sort='.$sort.'&';
}

$rows = array();
$config['db']->query('SELECT * FROM task'.(strlen($order) ? ' ORDER BY '.$order : '').' LIMIT '.(($page-1)*$limit).','.$limit);
while ($i = $config['db']->fetch()) {
	$rows[] = $i;
}

?>