<?php
/**
  * @return bool|PDO
 */
	function dbConnect()
	{

	$config = require(__DIR__ . '/config.php');

	try {
		$connection = new PDO('mysql:host=' . $config['hostname'] . ';dbname=' . $config['database'], $config['username'], $config['password']);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		return $connection;

	} catch (PDOException $e) {
				echo "Fout bij verbinding met de database: " . $e->getMessage();
					exit;
	}	return false;
}

/**
 * @param
 *
 * @return int
 */
function getTotalCountries( $connection ) {
	$sql       = "SELECT COUNT(*) as total FROM country";
	$statement = $connection->query($sql);

	return (int) $statement->fetchColumn();
}

/**
 * @param \PDO
 * @param int
 * @param int
 *
 * @return array
 */
function getCountries($connection, $page = 0, $pagesize = 10) {

	$page = (int) $page;

	$sql = 'SELECT * FROM `country`';

	$total = getTotalCountries($connection);

	$num_pages = (int) round($total / $pagesize);

	$offset = ($page - 1) * $pagesize;

	$sql .= ' LIMIT ' . $pagesize . ' OFFSET ' .  $offset;

	$statement = $connection->query($sql);

	return [
			'statement' => $statement,
			'total'     => $total,
			'pages'     => $num_pages,
			'page'      => $page
	];
}
