<?php declare (strict_types = 1);

namespace App\Helper;
use App\Database\MYSQLiConnection;
use App\Database\MYSQLiQueryBuilder;
use App\Database\PDOConnection;
use App\Database\PDOQueryBuilder;
use App\Database\QueryBuilder;
use App\Helper\App;
use App\Helper\ConfigFiles;

class DbQueryBuilderFactory {
	public static function make(string $credentialFile = 'database', string $connectionType = 'pdo', array $option = []): QueryBuilder{

		$connection = null;
//we merge credentials with available options. if there is no option we continue
		$credentials = array_merge(ConfigFiles::get($credentialFile, $connectionType), $option);

		switch ($connectionType) {
		case 'pdo':
			$connection = (new PDOConnection($credentials))->connect();
			return new PDOQueryBuilder($connection);
			break;
		case 'mysqli':
			$connection = (new MYSQLiConnection($credentials))->connect();
			return new MYSQLiQueryBuilder($connection);
			break;

		default:
			throw new DatabaseConnectionException("connection type is not recognized internally", ["type" => $connectionType]);
		}
	}

	public static function get(): QueryBuilder{
		$app = new App();

		if ($app->isTestMode()) {
			return self::make('database', 'pdo', ['db_name' => 'bug_app_testing']);
		}

		return self::make();
	}
	//check this. such that when in console, we get testing database.
}