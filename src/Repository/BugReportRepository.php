<?php declare (strict_types = 1);

namespace App\Repository;
use App\Entity\BugReport;
use App\Repository\Repository;

class BugReportRepository extends Repository {

	protected static $table = "reports";
	protected static $className = BugReport::class;
}