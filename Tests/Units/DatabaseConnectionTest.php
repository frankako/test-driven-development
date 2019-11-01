<?php declare (strict_types = 1);

namespace Test\Units;
use App\Contracts\DatabaseConnectionInterface;
use App\Database\MYSQLiConnection;
use App\Database\PDOConnection;
use App\Exception\MissingArgumentException;
use App\Helper\ConfigFiles;
use PHPUnit\Framework\TestCase;

class DatabaseConnectiontest extends TestCase {
	private $pdoHandler;

	public function setup(): void{

		$credentials = $this->getCredentials("pdo");
		$this->pdoHandler = (new PDOConnection($credentials))->connect();
		parent::setup();
	}

	public function testItIsInstanceOfDbConnectionInterface() {
		self::assertInstanceOf(DatabaseConnectionInterface::class, $this->pdoHandler);

		return $this->pdoHandler;
	}

/** @depends testItIsInstanceOfDbConnectionInterface */
	public function testItIsAValidPdoConnection(DatabaseConnectionInterface $hander) {
		self::assertInstanceOf(\PDO::class, $hander->getConnection());
	}

	public function testItCanConnectToDbWithPdo() {
		$credentials = $this->getCredentials("pdo");
		$pdo = (new PDOConnection($credentials))->connect();
		self::assertNotEmpty($pdo);
	}

	public function testItCanConnectToDbWithMysqli() {
		$credentials = $this->getCredentials("mysqli");
		$mysqli = (new MYSQLiConnection($credentials))->connect();
		self::assertInstanceOf(MYSQLiConnection::class, $mysqli);
	}

	public function testItThrowsMissingArgumentExceptionWithoutKeysOrWrongKeys() {
		self::expectException(MissingArgumentException::class);

		$credentials = []; //assert if you try to connect without or with wrong credentials, we get this exception
		$pdoHandler = (new PDOConnection($credentials))->connect();
	}

	private function getCredentials($db) {

		return array_merge(
			ConfigFiles::get("Database", $db),
			['dbname', 'bug_app_testing']
		);
	}
}