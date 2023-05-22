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

/**
 * Vvveb.
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
function adminer_object() {
	class AdminerVvveb extends Adminer {
		function name() {
			// custom name in title and heading
			return 'Vvveb';
		}

		function credentials() {
			return [DB_HOST, DB_USER, DB_PASS];
		}

		function database() {
			// database name, will be escaped by Adminer
			return DB_NAME;
		}

		function databases($flush = false) {
			//allow only CMS database
			return [DB_NAME];
		}

		function login($login, $password) {
			return true;
		}

		/*
		function tableName($tableStatus) {
			//don't allow admin table access
			if ($tableStatus['Name'] != 'admin') {
				return $tableStatus['Name'];
			}
		}*/

		/*
		function tableName($tableStatus) {
		  return h($tableStatus['Comment']);
		}*/

	/*
	function fieldName($field, $order = 0) {
	  // only columns with comments will be displayed and only the first five in select
	  return ($order <= 5 && !preg_match('~_(md5|sha1)$~', $field['field']) ? h($field['comment']) : '');
	}*/
	}

	return new AdminerVvveb();
}

chdir(__DIR__);

include 'index.php';
