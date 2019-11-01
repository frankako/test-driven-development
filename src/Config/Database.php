<?php declare (strict_types = 1);

return [

	'pdo' => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'dbname' => 'bug_app',
		'default_fetch' => PDO::FETCH_OBJ,
	],

	'mysqli' => [
		'host' => 'localhost',
		'username' => 'root',
		'password' => '',
		'dbname' => 'bug_app',
		'default_fetch' => MYSQLI_ASSOC,
	],

];
