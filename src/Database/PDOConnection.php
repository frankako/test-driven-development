<?php declare (strict_types = 1);

namespace App\Database;
use App\Contracts\DatabaseConnectionInterface;
use App\Database\AbstractConnection;
use App\Exception\DatabaseConnectionException;
use PDO;
use PDOException;

class PDOConnection extends AbstractConnection implements DatabaseConnectionInterface {

	const REQUIRED_CONNECTION_KEYS = [
		'driver',
		'host',
		'username',
		'password',
		'dbname',
		'default_fetch',
	];

	public function connect(): PDOConnection{
		$credentials = $this->parseCredentials($this->credentials);

		try {
			$this->connection = new PDO(...$credentials);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $this->credentials['default_fetch']);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
		} catch (PDOException $ex) {
			throw new DatabaseConnectionException($ex->getMessage(), $this->credentials, 500);
		}

		return $this;
	}

	public function getConnection(): PDO {
		return $this->connection;
	}

	protected function parseCredentials(array $credentials): array{

		$dsn = sprintf("%s:" . self::REQUIRED_CONNECTION_KEYS[1] . "=%s;" . self::REQUIRED_CONNECTION_KEYS[4] . "=%s",
			$credentials['driver'],
			$credentials['host'],
			$credentials['dbname']);

		return [$dsn, $credentials['username'], $credentials['password']];
	}

}