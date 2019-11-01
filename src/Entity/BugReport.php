<?php declare (strict_types = 1);

namespace App\Entity;
use App\Entity\Entity;

class BugReport extends Entity {

	private $report_id;
	private $report_type;
	private $email;
	private $link;
	private $message;
	private $created_at;

	public function toArray(): array{

		return [
			'report_type' => $this->getReportType(),
			'email' => $this->getEmail(),
			'link' => $this->getLink(),
			'message' => $this->getMessage(),
			'created_at' => date("Y-m-d H:i:s"), //set date.
		];
	}

	public function getId(): int {
		return $this->report_id;
	}

	public function setReportType(string $report_type) {
		$this->report_type = $report_type;
		return $this;
	}

	public function setEmail(string $email) {
		$this->email = $email;
		return $this;
	}

//link is nullbale
	public function setLink( ? string $link) {
		$this->link = $link;
		return $this;
	}

	public function setMessage(string $message) {
		$this->message = $message;
		return $this;
	}

	public function getReportType() : string {
		return $this->report_type;
	}

	public function getEmail(): string {
		return $this->email;
	}

	public function getLink():  ? string {
		return $this->link;
	}

	public function getMessage() : string {
		return $this->message;
	}

//because we will be setting the date. we would just need to get it
	public function getCreatedAt(): string {
		return $this->created_at;
	}

}