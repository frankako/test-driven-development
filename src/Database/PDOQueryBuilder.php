<?php declare (strict_types = 1);

namespace App\Database;
use App\Database\QueryBuilder;

class PDOQueryBuilder extends QueryBuilder {

	public function get() {
		return $this->statement->fetchAll(); //we do not pass the mode because we have fetch::obj as default fetch mode.
	}

	public function count() {
		return $this->statement->rowCount();
	}

	public function lastInsertId() {
		return $this->connection->lastInsertId();
	}

	public function prepare($query) {
		return $this->connection->prepare($query);
	}

	public function execute($statement) {
		$statement->execute($this->bindings);
		//we empty bindings and placeholders for next query.
		$this->bindings = [];
		$this->placeholders = [];

		return $statement; //we return statement used for fetch
	}

	public function fetchInto($className) {
		return $this->statement->fetchAll(\PDO::FETCH_OBJ, $className);
	}

	public function beginTransaction() {
		return $this->connection->beginTransaction();
	}

//rowCount for pdo is the same as affected rows. In mysqli there is num_rows and affected rows.
	public function affected() {
		return $this->count();
	}

}