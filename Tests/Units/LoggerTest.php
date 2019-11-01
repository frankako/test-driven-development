<?php declare (strict_types = 1);

namespace Test\Units;
use App\Contracts\LoggerInterface;
use App\Helper\App;
use App\Logger\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase {

	private $loger;

	public function setup(): void{
		$this->logger = new Logger();
		parent::setup();
	}

	public function testItCanAssertInstanceOfLoggerInterface() {
		self::assertInstanceOf(LoggerInterface::class, $this->logger);
	}

	public function testItCanCreateLogLevels() {

		$this->logger->error("This is an error");

		$app = new App();

		$fileName = sprintf("%s/%s%s.php", $app->getLogPath(), "test", date("j-n-Y"));

		self::assertFileExists($fileName);
		$getLoggedContent = file_get_contents($fileName);

		//we check if our log level is logged in the log file
		self::assertStringContainsString("This is an error", $getLoggedContent);
		unlink($fileName);

		self::assertFileNotExists($fileName);

	}

}