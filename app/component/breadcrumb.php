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

namespace Vvveb\Component;

use Vvveb\System\Component\ComponentBase;
use Vvveb\System\Core\Request;
use Vvveb\System\Event;

class Breadcrumb extends ComponentBase {
	public static $defaultOptions = [
	];

	public $options = [];

	function cacheKey() {
		//disable caching
		return false;
	}

	function results() {
		$request = Request::getInstance();
		$route   = $request->get['route'] ?? '';
		$slug    = $request->get['slug'] ?? '';

		switch ($route) {
			//product page
			case 'product/product/index':
				$breadcrumb = [
					['text' => 'home', 'url' => '/'],
					['text' => 'category', 'url' => '/cat/toys'],
					['text' => $slug, 'url' => false],
				];

			break;
			//product category page
			case 'product/category/index':
				$breadcrumb = [
					['text' => 'home', 'url' => '/'],
					['text' => $slug, 'url' => false],
				];

			break;
			//shop page
			case 'product/index':
				$breadcrumb = [
					['text' => 'home', 'url' => '/'],
					['text' => 'shop', 'url' => false],
				];

			break;

			default:
		}

		$results = [
			'breadcrumb' => $breadcrumb,
		];

		list($results) = Event :: trigger(__CLASS__,__FUNCTION__, $results);

		return $results;
	}
}
