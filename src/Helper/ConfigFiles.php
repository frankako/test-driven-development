<?php declare (strict_types = 1);

namespace App\Helper;

class ConfigFiles {

	public static function getFileContent(string $filename): array{

		$fileContent = [];
		try {
			$path = realpath(sprintf(__DIR__ . '/../Config/%s.php', $filename));

			if (file_exists($path)) {
				$fileContent = require $path;
			}

		} catch (\Throwable $ex) {
			throw new NotFoundException(
				sprintf("The file name %s was not found", $filename)
			);
		}

		return $fileContent;
	}

	public static function get(string $filename, string $key = null): array{
		$fileContent = [];

		$fileContent = self::getFileContent($filename);

		if ($key == null) {
			return $fileContent;
		} else {
			return isset($fileContent[$key]) ? $fileContent[$key] : [];
		}
	}
}