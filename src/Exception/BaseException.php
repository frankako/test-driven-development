<?php declare (strict_types = 1);

namespace App\Exception;

class BaseException extends \Exception {

	private $data = [];

	public function __construct(string $message = "", array $data = [], $code = 0, Throwable $previous = null) {
		$this->data = $data;
		//overrriding the parent exception class
		parent::__construct($message, $code, $previous);
	}

	//get and set data
	public function setData($key, $value): void{
		$this->data[$key] = $value;
	}

	public function getExtraData(): array{
		if (count($this->data) == 0) {return $this->data;}
		return json_decode(json_encode($this->data), true);
	}
}