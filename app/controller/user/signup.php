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

use function Vvveb\__;
use Vvveb\System\User\User;
use Vvveb\System\Validator;

class Signup extends \Vvveb\Controller\Base {
	function index() {
		//$this->checkAlreadyLoggedIn();

		$validator = new Validator(['signup']);

		if ($this->request->post &&
			($this->view->errors = $validator->validate($this->request->post)) === true) {
			//allow only fields that are in the validator list and remove the rest
			$userInfo = $validator->filter($this->request->post);
			$userInfo = User::add($userInfo);

			$this->view->errors = [];

			if ($userInfo) {
				if (is_array($userInfo)) {
					$message = __('User created!');
					$this->session->set('message', $message);
					$this->view->messages[] = $message;

					if (email($userInfo['email'], 'user/signup', $userInfo)) {
					}

					header('Location: /user/');
				} else {
					$this->view->errors[] = __('This email has already been used!');
				}
			} else {
				$this->view->errors[] = __('Error creating user!');
			}
		}
	}
}
