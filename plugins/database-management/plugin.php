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

/*
Name: Database Management
Slug: database-management
Category: tools
Url: http://www.vvveb.com
Description: Database management tool using adminer (adminer.org)
Thumb: database-management.svg
Author: givanz
Version: 0.1
Author url: http://www.vvveb.com
Settings: /admin/?module=plugins/database-management/view
*/

/**
This plugin uses Adminer
 
Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
use function Vvveb\__;
use Vvveb\System\Core\View;
use Vvveb\System\Event;

class DatabaseManagementPlugin {
	function admin() {
		//add admin menu item
		$admin_path = \Vvveb\adminPath();
		Event::on('Vvveb\Controller\Base', 'init-menu', __CLASS__, function ($menu) use ($admin_path) {
			$menu['plugins']['items']['database-management'] = [
				'name'     => __('Database Management'),
				'module'   => 'plugins/database-management/view',
				'url'      => $admin_path . '?module=plugins/database-management/view',
				'icon-img' => PUBLIC_PATH . 'plugins/database-management/database-management.svg',
			];

			return [$menu];
		}, 20);

		Event::on('Vvveb\System\Core\View', 'render', __CLASS__, function () {
		}, 20);
	}

	function __construct() {
		if (APP == 'admin') {
			$this->admin();
		}
	}
}

$databaseManagementPlugin = new DatabaseManagementPlugin();
