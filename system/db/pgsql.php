<?php

/**
 * Vvveb
 *
 * Copyright (C) 2022  Ziadin Givan
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Vvveb\System\Db;

use Vvveb\System\Event;

class Pgsql extends DBDriver {
	private static $link = null;

	//public $error;

	private $stmt;

	private $last_res;

	public $affected_rows = 0;

	public $num_rows = 0;

	public $insert_id = null;

	private static $persistent = false;

	public $prefix = ''; //'vv_';

	public $quote  = '"';

	public static function version() {
		if (self :: $link) {
			return pg_version(self :: $link);
		}
	}

	public static function info() {
		if (self :: $link) {
			return pg_version(self :: $link);
		}
	}

	public function error() {
		if (self :: $link) {
			return pg_last_error(self :: $link) ?? '';
		}
	}

	public function get_result($stmt) {
		return $stmt;
		$result = new \SQLite3Stmt($stmt);

		return $result;
	}

	public function __construct($host = DB_HOST, $dbname = DB_NAME, $user = DB_USER, $pass = DB_PASS,  $prefix = DB_PREFIX) {
		if (! self :: $link) {
			//port 5432 for direct pgsql connection 6432 for pgbouncer
			$port           = 5432;
			$connect_string = "host=$host port=$port dbname=$dbname  user=$user password=$pass";

			if (self :: $persistent) {
				self :: $link = pg_pconnect($connect_string);
			} else {
				self :: $link = pg_connect($connect_string);
			}

			if (self :: $link) {
				//				pg_set_error_verbosity(self :: $link, PGSQL_ERRORS_VERBOSE);
			} else {
			}
		}

		return self :: $link;
	}

	/*
	 * Get all columns for a table used for sanitizing input
	 */
	function getColumnsMeta($tableName) {
		$sql =
		"SELECT data_type as t, column_name as name, column_default as d, is_nullable as n FROM information_schema.columns WHERE table_name ='$tableName'";

		if ($result = $this->query($sql)) {
			//$columns = $result->fetch_all(MYSQLI_ASSOC);
			$columns = [];

			while ($row = pg_fetch_assoc($result)) {
				$columns[] = $row;
			}

			/* free result set */
			return $columns;
		} else {
		}

		return false;
	}

	public function fetchAll($result) {
		if (pg_num_rows($result)) {
			return pg_fetch_all($result);
		} else {
			return [];
		}
	}

	public function query($sql, $parameters = []) {
		$result = false;
		//var_dump($this->debugSql($sql, $params, $paramTypes));

		try {
			if ($parameters) {
				$this->last_res = @pg_query_params(self :: $link, $sql, $parameters);
			} else {
				$this->last_res = @pg_query(self :: $link, $sql);
			}

			if ($this->last_res == false) {
				$errorMessage = pg_last_error(self :: $link);

				throw new \Exception($errorMessage);
			}

			return $this->last_res;
		} catch (\Exception $e) {
			$message = $e->getMessage() . "\n$sql\n";

			throw new \Exception($message, $e->getCode());
		}

		return $result;
	}

	public function multi_query($sql) {
		return $this->query($sql);
	}

	public function close() {
		return pg_close(self :: $link);
	}

	public function get_one($query, $parameters = null) {
		$res = $this->exec($query, $parameters);

		if (pg_num_rows($res)) {
			return pg_fetch_result($res, 0, 0);
		} else {
			return false;
		}
	}

	function get_row($query, $parameters) {
		$res = $this->exec($query . ' LIMIT 1', $parameters);

		if ($res === null) {
			$res = $this->last_res;
		}

		return pg_fetch_assoc($res);
	}

	public function get_all($query, $parameters = null) {
		$res = $this->exec($query, $parameters);

		if (pg_num_rows($res)) {
			return pg_fetch_all($res);
		} else {
			return false;
		}
	}

	function fetch_row($res = null) {
		if ($res === null) {
			$res = $this->last_res;
		}

		return pg_fetch_assoc($res);
	}

	// Prepare
	public function execute($sql, $params = [], $paramTypes = []) {
		list($sql, $params) = Event::trigger(__CLASS__,__FUNCTION__, $sql, $params);
		//save orig sql for debugging info
		$origSql = $sql;

		list($parameters, $types) = $this->paramsToQmark($sql, $params, $paramTypes, '$');
		/*
		if ($this->connect()) {
			if (DEBUG == true) {
				error_log( $this->debugSql($origSql, $params, $paramTypes));
			}
		}*/
		//         error_log($this->parse_query($query, $parameters) . print_r($parameters, 1));

		//return $this->last_res = pg_query(self :: $link, $this->parse_query($query, $parameters));
		//echo ($this->debugSql($origSql, $params, $paramTypes)) . '<br>';
		//echo($this->debugSql($sql, $params, $paramTypes)) . '<br>';

		if ($parameters) {
			$this->last_res = pg_query_params(self :: $link, $sql, $parameters);
		} else {
			$this->last_res = pg_query(self :: $link, $sql);
		}

		if ($this->last_res != false) {
			error_log('pgsql error: ' . pg_result_error($this->last_res));
		}

		return $this->last_res;

		try {
			//echo $origSql;
			//echo $sql;
			$stmt = self::$link->prepare($sql);
		} catch (\Exception $e) {
			$message = $e->getMessage() . "\n" . $this->debugSql($origSql, $params, $paramTypes, '$') . "\n - " . $origSql;

			throw new \Exception($message, $e->getCode());
		}

		if ($stmt && ! empty($paramTypes)) {
			foreach ($parameters as $key => $value) {
				$type  = $types[$key] ?? 's';
				$type  = ($type == 'i') ? SQLITE3_INTEGER : SQLITE3_TEXT;
				$index = (int)$key + 1;
				$stmt->bindValue($index, $value, $type);
			}
		} else {
			if (DEBUG) {
				error_log((self :: $link->lastErrorMsg ?? '') . ' ' . $this->debugSql($origSql, $params, $paramTypes, '$'));
			}
		}

		if (DEBUG) {
			error_log($this->debugSql($origSql, $params, $paramTypes));
		}

		if ($stmt) {
			try {
				if ($result = $stmt->execute()) {
					$this->affected_rows = self :: $link->changes();
					$this->insert_id     = self :: $link->lastInsertRowID();

					return $result;
				} else {
					error_log(print_r($result, 1));
					error_log($this->debugSql($sql, $params, $paramTypes));
				}
			} catch (\Exception $e) {
				$message = $e->getMessage() . "\n" . $origSql . "\n" . $this->debugSql($origSql, $params, $paramTypes, '$') . "\n" . print_r($parameters, 1) . $types;

				throw new \Exception($message, $e->getCode());
			}
		} else {
			error_log(print_r($stmt, 1));
			error_log($this->debugSql($origSql, $params, $paramTypes));
		}

		return $stmt;
	}

	// Bind
	public function bind($param, $value, $type = null) {
		$this->stmt->bindValue($param, $value, $type);
	}
}
