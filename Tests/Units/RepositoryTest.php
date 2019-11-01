<?php declare (strict_types = 1);

namespace Tests\Units;

use App\Entity\BugReport;
use App\Helper\DbQueryBuilderFactory;
use App\Repository\BugReportRepository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase {

	private $queryBuilder;
	private $bugReportRepository;

	public function setUp(): void{

		$this->queryBuilder = DbQueryBuilderFactory::make('database', 'pdo', ['db_name', 'bug_app_testing']);
		$this->queryBuilder->beginTransaction();

		$this->bugReportRepository = new BugReportRepository($this->queryBuilder);

	}

	private function createBugReport(): BugReport{
		$bugReport = new BugReport();
		$bugReport->setReportType('Type 2')->setLink('https://test.com')->setMessage('This is a dummy text')->setEmail('email@test.com');
		return $this->bugReportRepository->create($bugReport);
	}

	public function testItCanCreateRecordWithEntity() {

		$newBugReport = $this->createBugReport();

		self::assertInstanceOf(BugReport::class, $newBugReport);
		self::assertNotNull($newBugReport->getId());
		self::assertSame('Type 2', $newBugReport->getReportType());
		self::assertSame('This is a dummy text', $newBugReport->getMessage());
		self::assertSame('https://test.com', $newBugReport->getLink());
		self::assertSame('email@test.com', $newBugReport->getEmail());
	}

	public function testItCanFindByCriteria() {
		$newBugReport = $this->createBugReport();
		$report = $this->bugReportRepository->findBy([
			['report_type', '=', 'Type 2'],
			['email', 'email@test.com'],
		]);
		self::assertIsArray($report);
		self::assertNotEmpty($report);
		$bugReport = $report[0];
		self::assertSame('Type 2', $bugReport->getReportType());
		self::assertSame('email@test.com', $bugReport->getEmail());
	}

	public function testItCanUpdateGivenEntity() {
		$newBugReport = $this->createBugReport();
		$bugReport = $this->bugReportRepository->find($newBugReport->getId());

		$bugReport->setMessage('The updated message')->setLink('https://test.com-update');
		$updatedReport = $this->bugReportRepository->update($bugReport);

		self::assertNotNull($updatedReport->getId());
		self::assertSame('The updated message', $updatedReport->getMessage());
		self::assertSame('https://test.com-update', $updatedReport->getLink());

	}

	public function testItCanDeleteGivenEntity() {
		$newbugReport = $this->createBugReport();
		$deletedBugReport = $this->bugReportRepository->delete($newbugReport);
		self::assertNull($deletedBugReport);
	}

	public function tearDown(): void{
		$this->queryBuilder->table('reports')->rollback();
		parent::tearDown(); // this will roll back all database transactions
	}
}
