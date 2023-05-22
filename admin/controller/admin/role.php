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

namespace Vvveb\Controller\Admin;

use Vvveb\Controller\Base;
use Vvveb\Sql\RoleSQL;
use Vvveb\System\User\Role as RoleList;

class Role extends Base {
	protected $type = 'role';

	function index() {
		$role_id = $this->request->get['role_id'] ?? false;

		$tree = [];
		RoleList::mkmap(DIR_APP . 'controller', $tree);
		//echo '<pre>' . print_r($tree, 1) . '</pre>';
		$this->view->tree = $tree['controller'];
		//var_dump($this->view->tree);

		$controllers             = RoleList::getControllerList();
		$this->view->controllers = $controllers;
		//var_dump($controllers);

		$role             = new RoleSQL();
		$this->view->role = $role->get(['role_id' => $role_id]);

		if ($this->view->role) {
			$this->view->role['permissions'] = json_decode($this->view->role['permissions'], true);
		}
	}

	function save() {
		$allow       = $this->request->post['allow'] ?? false;
		$deny        = $this->request->post['deny'] ?? false;
		$permissions = ['deny' => $allow, 'allow' => $deny];

		$role_id = $this->request->get['role_id'] ?? false;

		$role = new RoleSQL();
		$role->edit(['role_id' => $role_id, 'role' => ['permissions' => json_encode($permissions)]]);

		return $this->index();
	}
}
