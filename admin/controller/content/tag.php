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

namespace Vvveb\Controller\Content;

use function Vvveb\__;
use Vvveb\Controller\Base;

class Tag extends Base {
	protected $type = 'tag';

	function save() {
		$taxonomy_item_id = $this->request->get['taxonomy_item_id'] ?? false;
		$tag              = $this->request->post['tag'] ?? false;

		if ($taxonomy_item_id && $tag) {
			$tags   = new \Vvveb\Sql\categorySQL();
			$result = $tags->editCategory(['taxonomy_item_array' => $tag, 'taxonomy_item_id' => $taxonomy_item_id]);

			if ($result && isset($result['category'])) {
				$this->view->success[] = __('Tag saved!');
			} else {
				$this->view->errors[] = __('Error saving!');
			}
		}

		return $this->index();
	}

	function index() {
		$tags             = new \Vvveb\Sql\categorySQL();
		$taxonomy_item_id = $this->request->get['taxonomy_item_id'] ?? false;

		$view                  = $this->view;
		$admin_path            = \Vvveb\adminPath();
		$controllerPath        = $admin_path . 'index.php?module=media/media';
		$view->scanUrl         = "$controllerPath&action=scan";
		$view->uploadUrl       = "$controllerPath&action=upload";

		$options = [
			'type'                     => $this->type,
			'taxonomy_item_id'         => $taxonomy_item_id,
		] + $this->global;
		unset($options['user_id']);

		$view->tag = $tags->getCategoryBySlug($options);
	}
}
