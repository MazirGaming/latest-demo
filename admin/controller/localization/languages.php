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

namespace Vvveb\Controller\Localization;

use Vvveb\Controller\Listing;

class Languages extends Listing {
	protected $type = 'language';

	protected $list = 'languages';

	protected $module = 'localization';
}

/*

use function Vvveb\__;
use Vvveb\Controller\Base;
use Vvveb\Sql\LanguageSQL;

class Languages extends Base {
	function delete() {
		$languages                      = new LanguageSQL();
		$language_id                    = $this->request->language['language_id'] ?? $this->request->get['language_id'] ?? false;

		if ($language_id) {
			if (is_numeric($language_id)) {
				$language_id = [$language_id];
			}

			$languages   = new LanguageSQL();
			$options     = [
				'language_id' => $language_id,
			] + $this->global;

			$result  = $languages->delete($options);

			if ($result && $result['language'] > 0) {
				$this->view->success[] = __('Language deleted!');
			} else {
				$this->view->errors[] = __('Error deleting language!');
			}
		}

		return $this->index();
	}

	function add() {
	}

	function index() {
		$languagesList             = include DIR_SYSTEM . 'data/languages-list.php';
		$this->view->languagesList = $languagesList;

		$languages                      = new LanguageSQL();
		$results                        = $languages->getAll();
		$public_path                    = \Vvveb\publicUrlPath();

		foreach ($results as $id => &$language) {
			if (isset($language['image'])) {
				$language['image'] = Images::image($language['image'], 'language');
			}

			$shortcode = \Vvveb\filter('/\w+/',$language['locale']);

			$language['img']        = $public_path . 'img/flags/' . $shortcode . '.png';
			$url                    = ['module' => 'localization/language', 'language_id' => $language['language_id']];
			$admin_path             = \Vvveb\adminPath();
			$language['url']        = \Vvveb\url($url);
			$language['edit_url']   = $language['url'];
			$language['delete_url'] = \Vvveb\url(['module' => 'localization/languages', 'action' => 'delete'] + $url + ['language_id[]' => $language['language_id']]);
		}

		$this->view->installedLanguages = $results;
	}
}
*/
