<?php declare (strict_types = 1);

namespace App\Helper;
use App\Helper\ConfigFiles;
use DateTime;
use DateTimeInterface;
use DateTimezone;

class App {

	private $config = array();

	public function __construct() {
		$this->config = ConfigFiles::get("App");
		//get Config/App.php and load in an array
	}

	public function isDebugMode() {
		if (!isset($this->config['debug'])) {
			return false;
		}
		return $this->config['debug'];
	}

	public function getEnvironment() {
		if (!isset($this->config['env'])) {
			return "production";
		}

		return $this->isTestMode() ? "test" : $this->config['env'];
	}

	public function isRunningFromConsole() {
		//php methods to check if it is running from console
		return php_sapi_name() == "cli" || php_sapi_name() == "phpbpg";
	}

	public function getSeverTime(): DateTimeInterface {
		return new DateTime("now", new DateTimezone("America/Chicago"));
	}

	public function getLogPath() {
		if (!isset($this->config['log_path'])) {
			throw new \Exception("Log path not defined");
		}

		return $this->config['log_path'];
	}

	public function isTestMode() {
		if ($this->isRunningFromConsole() && defined("PHPUNIT_RUNNING") && PHPUNIT_RUNNING == true) {
			return true;
		}
		return false;
	}
}