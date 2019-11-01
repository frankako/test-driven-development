<?php declare (strict_types = 1);

namespace Tests\Units;

use App\Helper\App;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase {

	public function testItCanAssertInstanceOfApp() {
		self::assertInstanceOf(App::class, new App);
	}

	public function testItCanGetBasicApplicationDataSet() {
		$app = new App();
		self::assertTrue($app->isRunningFromConsole());
		self::assertSame("test", $app->getEnvironment());
		self::assertNotNull($app->getLogPath());
		self::assertInstanceOf(\DateTime::class, $app->getSeverTime());
	}
}