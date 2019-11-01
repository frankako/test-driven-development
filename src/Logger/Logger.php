<?php declare (strict_types = 1);

namespace App\Logger;
use App\Contracts\LoggerInterface;
use App\Helper\App;
use App\Logger\LogLevel;

class Logger implements LoggerInterface {

	public function emergency($message, array $context = array()) {
		$this->addRecords(LogLevel::EMERGENCY, $message, $context);
	}

	public function critical($message, array $context = array()) {
		$this->addRecords(LogLevel::CRITICAL, $message, $context);
	}

	public function alert($message, array $context = array()) {
		$this->addRecords(LogLevel::ALERT, $message, $context);
	}

	public function warning($message, array $context = array()) {
		$this->addRecords(LogLevel::WARNING, $message, $context);
	}

	public function error($message, array $context = array()) {
		$this->addRecords(LogLevel::ERROR, $message, $context);
	}

	public function info($message, array $context = array()) {
		$this->addRecords(LogLevel::INFO, $message, $context);
	}

	public function debug($message, array $context = array()) {
		$this->addRecords(LogLevel::DEBUG, $message, $context);
	}

	public function notice($message, array $context = array()) {
		$this->addRecords(LogLevel::NOTICE, $message, $context);
	}

	public function log(string $level, string $message, array $context = array()) {

		$obj = new \ReflectionClass(LogLevel::class);

		if (!in_array($level, $obj->getConstants())) {
			throw new InvalidLogLevelArgument("The log level name {$level} is not found");
		}

		$this->addRecords($level, $message, $context);

	}

	public function addRecords(string $level, string $message, array $context = []) {
		$app = new App();

		$date = $app->getSeverTime()->format("Y-m-d H:i:s");
		$env = $app->getEnvironment();
		$logPath = $app->getLogPath();

		$details = sprintf("%s - Level: %s - Message : %s - Context: %s", $date, $level, $message, json_encode($context)) . PHP_EOL;

		$filename = sprintf("%s/%s%s.php", $logPath, $env, date('j-n-Y'));

		file_put_contents($filename, $details, FILE_APPEND);
	}

}