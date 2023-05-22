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

use function Vvveb\__;
use Vvveb\Controller\Base;
use Vvveb\Sql\AdminSQL;
use Vvveb\System\Images;
use Vvveb\System\User\Role;

class Users extends Base {
	protected $type = 'admin';

	function delete() {
		$admin_id    = $this->request->post['admin_id'] ?? $this->request->get['admin_id'] ?? false;

		if ($admin_id) {
			if (is_numeric($admin_id)) {
				$admin_id = [$admin_id];
			}

			$admins   = new AdminSQL();
			$options  = ['admin_id' => $admin_id] + $this->global;
			$result   = $admins->delete($options);

			if ($result && isset($result['admin'])) {
				$this->view->success[] = __('Admin(s) deleted!');
			} else {
				$this->view->errors[] = __('Error deleting admin!');
			}
		}

		return $this->index();
	}

	private function save() {
	}

	function index() {
		$view      = $this->view;
		$admins    = new AdminSQL();

		$options    =  [
			'type'         => $this->type,
		] + $this->global;

		$results = $admins->getAll($options);

		if ($results['admins']) {
			foreach ($results['admins'] as $id => &$admin) {
				$admin['status_text']      = $admin['status'] == '1' ? __('active') : __('inactive');
				$admin['image']            = Images::image('admin', $admin['image'] ?? '');
				$admin['delete-url']       = \Vvveb\url(['module' => 'admin/users', 'action' => 'delete'] + ['admin_id[]' => $admin['admin_id']]);
			}
		}

		//$permissions = Role::getAll();
		//var_dump($permissions);

		//die();
		$view->users    = $results['admins'];
		$view->count    = $results['count'] ?? 0;
		$view->limit    = $options['limit'];
	}
}
