<?php declare (strict_types = 1);

namespace App\Database;
use App\Contracts\DatabaseConnectionInterface;
use App\Database\AbstractConnection;
use App\Exception\DatabaseConnectionException;
use mysqli;
use mysqli_driver;

class MYSQLiConnection extends AbstractConnection implements DatabaseConnectionInterface {

	const REQUIRED_CONNECTION_KEYS = [
		'host',
		'dbname',
		'username',
		'password',
		'default_fetch',
	];

	public function connect(): MYSQLiConnection{

		$credentials = $this->parseCredentials($this->credentials);

		try {
			$driver = new mysqli_driver;
			$driver->report_mode = MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR;
			$this->connection = new mysqli(...$credentials);
		} catch (\Throwable $ex) {
			//the $ex->get message will get the pdo message. Makes it standard
			throw new DatabaseConnectionException($ex->getMessage(), $this->credentials, 500);
		}

		return $this;
	}

	public function getConnection(): mysqli {
		return $this->connection;
	}

	protected function parseCredentials(array $credentials): array{
		return [$credentials['host'], $credentials['username'], $credentials['password'], $credentials['dbname']];
	}

}