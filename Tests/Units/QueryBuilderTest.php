<?php declare (strict_types = 1);

namespace Test\Units;
use App\Database\MYSQLiConnection;
use App\Database\MYSQLiQueryBuilder;
use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Helper\ConfigFiles;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase {
	private $builder;
	private $sql;
	public function setup(): void{

		$credentials = $this->getCredentials("pdo");
		$sqlcredentials = $this->getCredentials("mysqli");
		$this->sql = new MYSQLiQueryBuilder((new MYSQLiConnection($sqlcredentials))->connect());
		$this->builder = new PDOQueryBuilder((new PDOConnection($credentials))->connect());
		// $this->queryBuilder = new QueryBuilder((new PDOConnection($credentials))->connect());
		parent::setup();

	}

	// public function testBindings() {
	// 	$query = $this->queryBuilder->where('name', 'peter')->where("type", "=", "6");
	// 	self::assertIsArray($query->getPlaceholders());
	// 	self::assertIsArray($query->getBindings());
	// 	var_dump($query->getPlaceholders(), $query->getBindings());
	// }

	public function testItCanPerformRawQuery() {
		$result = $this->sql->raw("select * from reports where report_id = 4")->get();
		self::assertNotNull($result);
		self::assertIsArray($result);
	}

	public function testItCanPerformSelectQuery() {
		$result = $this->sql->table('reports')->select('*')->where('report_id', 2)->get();
		self::assertNotEmpty($result);
		self::assertIsArray($result);
	}

	public function testItCanPerformSelectQueryWithMultipleWhereClauses() {
		$result = $this->sql->table('reports')->select('*')->where('email', '=', 'jmorris@mail.com')->get();
		self::assertNotEmpty($result);
		self::assertIsArray($result);
	}

	private function getCredentials($db) {
		return array_merge(
			ConfigFiles::get("Database", $db),
			["dbname" => "bug_app_testing"]
		);
	}

	public function testItCanCreateRecords() {

		$result = $this->sql->table('reports')->create([
			'report_type' => 'morris report',
			'email' => 'jmorris@mail.com',
			'link' => 'http://infosys.com',
		]);

		self::assertNotNull($result);
	}

	public function testItCanUpdateRecords() {
		$result = $this->sql->table('reports')->update(['report_type' => 'Cors Headers', 'link' => 'https://morecors.com'])->where('report_id', 3);
		$report = $this->sql->table('reports')->select('*')->where('link', '=', 'https://morecors.com')->get();
		self::assertNotEmpty($report);
		self::assertIsArray($report);
	}

	public function testItCanDeleteRecords() {
		$result = $this->sql->table('reports')->delete()->where('report_id', 27)->affected();
		self::assertSame(1, $result);
	}

	public function testItCanFindBy() {
		$result = $this->sql->table('reports')->select('*')->where('report_type', 'Cors Headers')->get();
		self::assertNotEmpty($result);
		self::assertIsArray($result);
	}

	public function testItCanFind() {
		$result = $this->sql->table('reports')->select('*')->where('report_id', 23)->get();
		self::assertNotEmpty($result);
		self::assertIsArray($result);
	}

}