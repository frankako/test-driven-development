<?php declare (strict_types = 1);

namespace App\Entity;

abstract class Entity {

/**
 * int for all entities
 * @return $id
 */
	abstract public function getId(): int;

/**
 * data array for all entities values
 * @return $data
 */
	abstract public function toArray(): array;

}