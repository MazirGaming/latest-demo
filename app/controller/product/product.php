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

namespace Vvveb\Controller\Product;

use function Vvveb\__;
use Vvveb\Controller\Base;

class Product extends Base {
	public $type = 'product';

	function index() {
		if (isset($this->request->get['slug']) && $slug = $this->request->get['slug']) {
			$productSql = new \Vvveb\Sql\ProductSQL();
			$product    = $productSql->get(['slug' => $slug, 'type' => $this->type] + $this->global);

			if ($product) {
				$this->request->get['product_id'] = $product['product_id'];
			} else {
				$message = __('Product not found!');
				$this->notFound(true, 404, ['message' => $message, 'title' => $message]);
			}
		}
	}
}
