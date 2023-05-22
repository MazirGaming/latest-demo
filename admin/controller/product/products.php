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
use Vvveb\Sql\ProductSQL;
use Vvveb\System\Core\View;
use Vvveb\System\Images;

class Products extends Base {
	protected $type = 'product';

	//check for other modules permission like post and editor to enable links like save/delete etc
	protected $additionalPermissionCheck = ['product/product/save'];

	function init() {
		if (isset($this->request->get['type'])) {
			$this->type = $this->request->get['type'];
		}

		return parent::init();
	}

	function delete() {
		$product_id    = $this->request->post['product_id'] ?? $this->request->get['product_id'] ?? false;

		if ($product_id) {
			if (is_numeric($product_id)) {
				$product_id = [$product_id];
			}

			$products = new ProductSQL();
			$options  = ['product_id' => $product_id] + $this->global;
			$result   = $products->delete($options);

			if ($result && isset($result['product'])) {
				$this->view->success[] = __('Product(s) deleted!');
			} else {
				$this->view->errors[] = __('Error deleting product!');
			}
		}

		return $this->index();
	}

	function index() {
		$view     = View :: getInstance();
		$products = new ProductSQL();

		$page         = $this->request->get['page'] ?? 1;
		$limit        = $this->request->get['limit'] ?? 10;
		$this->filter = $this->request->get['filter'] ?? [];

		$options = [
			'type'        => $this->type,
			//'include_manufacturer' => true,
			'include_discount'     => true,
			'include_special'      => true,
			'include_reward'       => true,
			'include_manufacturer' => false,
			//'include_stock_status' => true,
		] + $this->global + $this->filter;

		$results = $products->getAll($options);

		$defaultTemplate = "product/{$this->type}.html";

		foreach ($results['products'] as $id => &$product) {
			if (isset($product['images'])) {
				$product['images'] = json_decode($product['images'], 1);

				foreach ($product['images'] as &$image) {
					$image = Images::image($image, 'product');
				}
				//	var_dump($product['images']);
			} else {
				if (isset($product['image'])) {
					$product['image'] = Images::image($product['image'], 'product');
				}
			}

			$template              = (isset($product['template']) && $product['template']) ? $product['template'] : $defaultTemplate;
			$product['url']        = \Vvveb\url(['module' => 'product/product', 'product_id' => $product['product_id']]);
			$product['edit-url']   = \Vvveb\url(['module' => 'product/product', 'product_id' => $product['product_id']]);
			$product['delete-url'] = \Vvveb\url(['module' => 'product/products', 'action' => 'delete', 'product_id[]' => $product['product_id']]);
			$product['view-url']   =  \Vvveb\url('product/product/index', $product);
			$admin_path            = \Vvveb\config('admin.path', 'admin') . '/';
			$product['design-url'] = '/' . $admin_path . \Vvveb\url(['module' => 'editor/editor', 'url' => $product['view-url'], 'template' => $template], false, false);
		}

		$view->filter           = $this->filter;
		$view->products         = $results['products'];
		$view->count            = $results['count'];
		$view->limit            = $limit;
	}
}
