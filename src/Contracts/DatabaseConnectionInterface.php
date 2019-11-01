<?php declare (strict_types = 1);

namespace App\Contracts;

Interface DatabaseConnectionInterface {

	/**
	 * To connect to database
	 * @return $connection
	 */
	public function connect();

	/**
	 * assert connected to database
	 * @return boolean
	 */
	public function getConnection();

}