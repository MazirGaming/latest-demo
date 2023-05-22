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
use Vvveb\System\Event;
use Vvveb\System\Images;

class Order extends ComponentBase {
	public static $defaultOptions = [
		'user_id'          => 'url',
		'order_id'         => 'url',
		'limit'            => ['url', 4],
	];

	public $options = [];

	function results() {
		$orders = new \Vvveb\Sql\OrderSQL();

		$results = $orders->get($this->options);

		foreach ($results['products'] as $id => &$product) {
			$product['url'] = htmlentities(\Vvveb\url('product/product/index', $product));

			if (isset($product['images'])) {
				$product['images'] = json_decode($product['images'], true);

				foreach ($product['images'] as &$image) {
					$image['image'] = Images::image($image['image'], 'product');
				}
			}

			if (isset($product['image']) && $product['image']) {
				$product['image'] =Images::image($product['image'], 'product');
				//$product['images'][] = ['image' => Images::image($product['image'], 'product')];
			}
		}
		//\Vvveb\dd($results);
		list($results) = Event :: trigger(__CLASS__,__FUNCTION__, $results);

		return $results;
	}
}
