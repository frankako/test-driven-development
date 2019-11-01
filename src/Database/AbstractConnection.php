<?php declare (strict_types = 1);

namespace App\Database;
use App\Exception\MissingArgumentException;

abstract class AbstractConnection {

	protected $connection;
	protected $credentials = [];

	const REQUIRED_CONNECTION_KEYS = [];

	public function __construct(array $credentials) {
		$this->credentials = $credentials;

		if (!$this->checkRequiredKeys($this->credentials)) {
			throw new MissingArgumentException(
				sprintf("Database connection requires %s", implode(", ", static::REQUIRED_CONNECTION_KEYS))
			);
		}
	}

	private function checkRequiredKeys(array $credentials): bool{
		$matches = array_intersect(static::REQUIRED_CONNECTION_KEYS, array_keys($credentials));

		if (count($matches) !== count(static::REQUIRED_CONNECTION_KEYS)) {
			return false;
		}
		return true;
	}

	abstract protected function parseCredentials(array $credentials): array;
}