<?php declare (strict_types = 1);

namespace App\Repository;
use App\Contracts\RepositoryInterface;
use App\Database\QueryBuilder;
use App\Entity\Entity;

abstract class Repository implements RepositoryInterface {
	//repo for all entities in our project
	//these properties will be overriden by those of any entity class that extends this repository
	private $queryBuilder;
	protected static $table;
	protected static $className;

	public function __construct(QueryBuilder $queryBuilder) {
		$this->queryBuilder = $queryBuilder;
	}

	public function find($id):  ? object{
		$result = $this->queryBuilder->table(static::$table)->select()->where('report_id', $id)->Query()->fetchInto(static::$className);
		return $result ? $result[0] : null;
	}

	public function findOneBy(string $field, $value) : object{
		//default select al
		$result = $this->queryBuilder->table(static::$table)->select()->where($field, $value)->Query()->fetchInto(static::$className);
		return $result ? $result[0] : null;
	}

//to get for multiple where clauses [['emai'=>'email'], ['name'=>'name']]. this will be spread to where('email', 'email')->where('name', 'name');
	public function findBy(array $criteria) {
		$this->queryBuilder->table(static::$table)->select();
		foreach ($criteria as $criterion) {
			$this->queryBuilder->where(...$criterion);
		}
		return $this->queryBuilder->Query()->fetchInto(static::$className);
	}

	public function findAll() {
		//default select al
		$results = $this->queryBuilder->table(static::$table)->select()->Query()->fetchInto(static::$className);
		return $results;
	}

	public function sql(string $query) {
		return $this->queryBuilder->raw($query)->fetchInto(static::$className);
	}

//note Entity Interface or abstract Entity will inject instance of any entity class pass here
	public function create(Entity $entity): object{
		$id = $this->queryBuilder->table(static::$table)->create($entity->toArray());
		return $this->find($id);
	}

	public function update(Entity $entity, array $conditions = []): object{
		$this->queryBuilder->table(static::$table)->update($entity->toArray());
		foreach ($conditions as $condition) {
			$this->queryBuilder->where(...$condition);
		}
		//if there is non condition, we run this condition instead.
		$this->queryBuilder->where('report_id', $entity->getId())->Query();
		return $this->find($entity->getId());
	}

	public function delete(Entity $entity, array $conditions = []) {
		$this->queryBuilder->table(static::$table)->delete($entity->toArray());
		foreach ($conditions as $condition) {
			$this->queryBuilder->where(...$condition);
		}
		$this->queryBuilder->where('report_id', $entity->getId())->Query();
		return $this->find($entity->getId());
	}
}