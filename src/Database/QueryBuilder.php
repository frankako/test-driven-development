<?php declare (strict_types = 1);

namespace App\Database;
use App\Contracts\DatabaseConnectionInterface;
use App\Exception\NotFoundException;

abstract class QueryBuilder {

	protected $connection;
	protected $table;
	protected $placeholders = array(); // a = ?
	protected $statement;
	protected $bindings = array(); //bindings is "a" which is value of a = a
	protected $operation = self::DML_TYPE_SELECT; //the type of DML
	protected $fields;

	const OPERATORS = ['=', '>', '<', '>=', '<=', '<>'];
	const PLACEHOLDERSIGN = '?';
	const COLUMN = '*';
	const DML_TYPE_SELECT = 'SELECT';
	const DML_TYPE_UPDATE = 'UPDATE';
	const DML_TYPE_DELETE = 'DELETE';
	const DML_TYPE_INSERT = 'INSERT';

	use getQuery;

	public function __construct(DatabaseConnectionInterface $databaseConnection) {
		$this->connection = $databaseConnection->getConnection();
	}

	public function table($name) {
		$this->table = $name;
		return $this;
	}

	public function where($column, $operator, $value = null) {
		if (!in_array($operator, self::OPERATORS)) {

			if ($value == null) {
				$value = $operator;
				$operator = self::OPERATORS[0];
			} else {
				throw new NotFoundException('Operator is invalid');
			}
		}

		$this->parseWhere([$column => $value], $operator);
		// $query = $this->prepare($this->theQuery($this->operation));
		// //for query test $this->query = $this->theQuery($this->operation)
		// //operation from chained select is inject in theQuery;
		// $this->statement = $this->execute($query); //note $query is also stmt.

		return $this;
	}

//we move query so it can work for mysqli
	public function Query() {
		$query = $this->prepare($this->theQuery($this->operation));
		$this->statement = $this->execute($query);
		return $this;
	}

	private function parseWhere(array $conditions, $operator) {
		foreach ($conditions as $column => $value) {
			$this->placeholders[] = sprintf("%s %s %s", $column, $operator, self::PLACEHOLDERSIGN);
			$this->bindings[] = $value;
		}
	}

	public function select($fields = self::COLUMN) {
		$this->operation = self::DML_TYPE_SELECT; //if select in chained, operation is SELECT
		$this->fields = $fields;
		return $this;
	}

//create does not have a 'where' so we run query $query
	public function create(array $data) {
		$this->fields = '`' . implode('`, `', array_keys($data)) . '`';
		foreach ($data as $value) {
			$this->placeholders[] = self::PLACEHOLDERSIGN;
			$this->bindings[] = $value;
		}

		$query = $this->prepare($this->theQuery(self::DML_TYPE_INSERT));
		$this->statement = $this->execute($query);
		return $this->lastInsertId();
	}

//update has a 'where' all set
	public function update(array $data) {
		$this->fields = [];
		$this->operation = self::DML_TYPE_UPDATE;

		foreach ($data as $column => $value) {
			$this->fields[] = sprintf("%s %s %s", $column, self::OPERATORS[0], "'$value'");
		}
		return $this;
	}

//delete has a 'where' and table all set
	public function delete() {
		$this->operation = self::DML_TYPE_DELETE;
		return $this;
	}

//direct query
	public function raw($query) {
		$query = $this->prepare($query);
		$this->statement = $this->execute($query);
		return $this;
	}

//we have the fileds and value to find by, we just get the first
	public function findOneBy($field, $value) {
		return $this->where($field, $value)->first();
	}

//record for id given
	public function find($customid, $id = NULL) {
		if ($id === NULL) {
			$id = $customid;
			$customid = "id";
		}

		return $this->where($customid, $id)->first();
	}

//we get the first post from the data
	public function first() {
		return $this->count() ? $this->get()[0] : NULL;
	}

	abstract public function get(); //return all records
	abstract public function count(); //return row count
	abstract public function lastInsertId(); //return insert id
	abstract public function prepare($query); //prepare query
	abstract public function execute($statement); //execute query
	abstract public function fetchInto($className); //fetch into class. If attributes are not declared in class, database attributes will be set.
	abstract public function beginTransaction();
	abstract public function affected();

//role back the database to its original state before query execution
	public function rollback(): void{
		$this->connection->rollback();
	}

}