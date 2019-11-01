<?php declare (strict_types = 1);

namespace App\Contracts;
use App\Entity\Entity;

interface RepositoryInterface {

	public function find(int $id):  ? object;
	public function findOneBy(string $field, $value) : object;
	public function findBy(array $criteria);
	public function findAll();
	public function sql(string $sql);
//any entity that is passed in this function will create an object
	public function create(Entity $entity): object;
	public function update(Entity $entity, array $vaidation = []): object;

	public function delete(Entity $entity, array $condition = []); //when we delete, we no longer have the entity

}

?>