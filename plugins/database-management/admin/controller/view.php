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

namespace Vvveb\Plugins\DatabaseManagement\Controller;

use Vvveb\Controller\Base;

class View extends Base {
	function index() {
	}

	function iframe() {
		$_GET['driver']   = 'server';
		$_GET['server']   = DB_HOST;
		$_GET['username'] = DB_USER;
		$_GET['db']       = DB_NAME;
		$_GET['password'] = DB_PASS;

		if (! isset($_SESSION['db'])) {
			$_POST['auth'] = ['driver'=> 'server', 'server' => DB_HOST, 'username' => DB_USER, 'db' => DB_NAME, 'pasword' => DB_PASS, 'permanent' => 1];
		}

		require __DIR__ . '/../../adminer/adminer-config.php';

		die();
	}
}
