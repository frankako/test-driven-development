<?php declare (strict_types = 1);

namespace App\Database;
use App\Database\QueryBuilder;
use App\Exception\InvalidArgumentException;

class MYSQLiQueryBuilder extends QueryBuilder {

	private $resultSet;
	private $result;
	const PARAM_TYPE_INT = 'i';
	const PARAM_TYPE_STRING = 's';
	const PARAM_TYPE_DOUBLE = 'd';

	public function get() {
		if (!$this->resultSet) {
			$this->resultSet = $this->statement->get_result();
			//get_result() is mysqli method.
		}
		$this->result = $this->resultSet->fetch_all(MYSQLI_ASSOC);
		return $this->result;
	}

	public function count() {
		if (!$this->resultSet) {
			$this->get();
		}

		return $this->resultSet ? $this->resultSet->num_rows : false;
	}

	public function lastInsertId() {
		return $this->connection->insert_id;
		//mysqli method
	}

	public function prepare($query) {
		return $this->connection->prepare($query);
	}

	public function execute($statement) {
		if (!$statement) {
			throw new InvalidArgumentException("Mysqli statement is false");
		}

		if ($this->bindings) {
			$bindings = $this->parseBindings($this->bindings);

			$reflection = new \ReflectionClass('mysqli_stmt');
			$method = $reflection->getMethod('bind_param');
			$method->invokeArgs($statement, $bindings);
		}

		$statement->execute();
		$this->bindings = [];
		$this->placeholders = [];

		return $statement;
	}

	public function parseBindings(array $params) {
		//we count param to assert bindings.
		$count = count($params);

		if ($count == 0) {
			return $this->bindings;
		}

		$bindingTypes = $this->getBindingTypes();

		$bindings[] = &$bindingTypes;

		for ($index = 0; $index < $count; $index++) {
			$bindings[] = &$params[$index];
		}
//binding will be ['isdi', 'int', 'string', 'double', 'int'] as it is passed
		return $bindings;
	}

	public function getBindingTypes() {
		$bindingTypes = [];

		foreach ($this->bindings as $binding) {
			if (is_int($binding)) {
				$bindingTypes[] = self::PARAM_TYPE_INT;
			}

			if (is_string($binding)) {
				$bindingTypes[] = self::PARAM_TYPE_STRING;
			}

			if (is_float($binding)) {
				$bindingTypes[] = self::PARAM_TYPE_DOUBLE;
			}
		}
//['i', 's', 'd'] will be 'isd'
		return implode('', $bindingTypes);
	}

	public function fetchInto($className) {
		$result = [];
		$this->resultSet = $this->statement->get_result();
		while ($object = $resultSet->fetch_object($className)) {
			$result[] = $object;
		}
		return $this->result = $result;
	}

	public function beginTransaction() {
		return $this->connection->begin_transaction();
	}

	public function affected() {
		$this->statement->store_result(); //mysqli method store_result().
		return $this->statement->affected_rows;
	}
}