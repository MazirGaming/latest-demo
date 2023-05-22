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

namespace Vvveb\Controller\Order;

use function Vvveb\__;
use Vvveb\Controller\Base;
use Vvveb\Sql\OrderSQL;
use Vvveb\System\Core\View;
use Vvveb\System\Images;
use Vvveb\System\Validator;

class Order extends Base {
	protected $type = 'order';

	function index() {
		$view = View :: getInstance();

		if (isset($this->request->get['order_id'])) {
			$options = ['order_id' => (int)$this->request->get['order_id'], 'type' => $this->type];

			$orders = new OrderSQL();
			$order  = $orders->get($options);

			foreach ($order['products'] as $id => &$product) {
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
			//\Vvveb\dd($order);

			$view->order    = $order;
			//var_dump($view->order);
			//$view->tags = $orders->orderTags($options);
			//$view->categories = $orders->orderCategories($options);
		}

		//$validator = new Validator(['order']);
		//$view->validatorJson = $validator -> getJSON();
	}

	function save() {
		$validator = new Validator(['order']);
		$view      = view :: getInstance();

		if (($errors = $validator->validate($_POST)) === true) {
			$orders = new OrderSQL();

			//$order = ['order' => array('title' => $_POST['title'], 'content' =>  $_POST['content'])/*, 'id_order' => (int)$this->request->get['order_id']*/];

			if (isset($this->request->get['order_id'])) {
				$order['order_id'] = (int)$this->request->get['order_id'];
				//$order['type'] = $this->type;
				$order['order_array'] = $_POST;
				$result               = $orders->editOrder($order);

				if ($result >= 0) {
					$view->success = [__('Order saved')];
				} else {
					$view->errors = [$orders->error];
				}
			} else {
				$id = $orders->add($order);

				if (! $id) {
					$view->errors = [$orders->error];
				} else {
					$view->success = __('Order saved!');
				}
			}
		} else {
			$view->errors = $errors;
		}

		$this->index();
	}
}
