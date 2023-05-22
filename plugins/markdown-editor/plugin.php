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
Name: Markdown Editor
Slug: markdown-editor
Category: content
Url: https://www.vvveb.com
Description: Editor and update markdown files as posts
Author: givanz
Version: 0.1
Thumb: markdown-editor.svg
Author url: http://www.vvveb.com
Settings: /admin/?module=plugins/markdown-editor/settings
*/

use function Vvveb\__;
use Vvveb\System\Event;

class MarkdownEditorPlugin {
	function admin() {
		//add admin menu item
		$admin_path = \Vvveb\adminPath();
		Event::on('Vvveb\Controller\Base', 'init-menu', __CLASS__, function ($menu) use ($admin_path) {
			$menu['plugins']['items']['markdown'] = [
				'name'     => __('Markdown Editor'),
				'url'      => $admin_path . '?module=plugins/markdown-editor/settings',
				'icon-img' => PUBLIC_PATH . 'plugins/markdown-editor/markdown-editor.svg',
			];

			return [$menu];
		}, 20);
	}

	function app() {
	}

	function __construct() {
		if (APP == 'admin') {
			$this->admin();
		} else {
			if (APP == 'app') {
				$this->app();
			}
		}
	}
}

$markdownPlugin = new MarkdownEditorPlugin();
