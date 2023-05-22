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

namespace Vvveb\Controller\User;

use \Vvveb\Sql\UserSQL;
use function Vvveb\__;
use Vvveb\Controller\Base;
use Vvveb\System\Images;

class Users extends Base {
	protected $type = 'user';

	function delete() {
		$user_id    = $this->request->post['user_id'] ?? $this->request->get['user_id'] ?? false;

		if ($user_id) {
			if (is_numeric($user_id)) {
				$user_id = [$user_id];
			}

			$users    = new UserSQL();
			$options  = ['user_id' => $user_id] + $this->global;
			$result   = $users->delete($options);

			if ($result && isset($result['user'])) {
				$this->view->success[] = __('User(s) deleted!');
			} else {
				$this->view->errors[] = __('Error deleting user!');
			}
		}

		return $this->index();
	}

	function index() {
		$view     = $this->view;
		$users    = new UserSQL();

		$options    =  [
			'type'         => $this->type,
		] + $this->global;

		$results = $users->getAll($options);

		if ($results['users']) {
			foreach ($results['users'] as $id => &$user) {
				$user['status_text']      = $user['status'] == '1' ? __('active') : __('inactive');
				$user['image']            = Images::image('user', $user['image'] ?? '');
				$user['delete-url']       = \Vvveb\url(['module' => 'user/users', 'action' => 'delete'] + ['user_id[]' => $user['user_id']]);
			}
		}

		$view->users    = $results['users'];
		$view->count    = $results['count'];
		$view->limit    = $options['limit'];
	}
}
