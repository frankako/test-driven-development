<?php declare (strict_types = 1);

namespace App\Exception;
use App\Helper\App;
use Throwable;

class ExceptionHandler {

	public function handle(Throwable $exceptions) {

		$app = new App();

		if ($app->isDebugMode()) {
			var_dump($exceptions);
		} else {
			echo "This should not have happened. Please contact support!";
		}
		exit;
	}

	public function convertWarningsAndNoticesToException($severity, $message, $file, $line) {
		throw new \ErrorException($severity, $severity, $message, $file, $line);
	}
}

set_error_handler([App\Exception\ExceptionHandler(), "convertWarningsAndNoticesToException"]);
set_exception_handler([App\Exception\ExceptionHandler(), "handle"]);