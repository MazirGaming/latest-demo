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

class Subscriptions extends Base {
	function delete() {
		$order_id    = $this->request->post['order_id'] ?? $this->request->get['order_id'] ?? false;

		if ($order_id) {
			if (is_numeric($order_id)) {
				$order_id = [$order_id];
			}

			$orders   = new OrderSQL();
			$options  = ['order_id' => $order_id] + $this->global;
			$result   = $orders->delete($options);

			if ($result && isset($result['order'])) {
				$this->view->success[] = __('Order(s) deleted!');
			} else {
				$this->view->errors[] = __('Error deleting order!');
			}
		}

		return $this->index();
	}

	function index() {
		$view     = View :: getInstance();
		$orders   = new OrderSQL();

		$options = [
		] + $this->global;

		$results = $orders->getAll($options);

		if ($results['orders']) {
			foreach ($results['orders'] as $id => &$order) {
				$order['delete-url'] = \Vvveb\url(['module' => 'order/orders', 'action' => 'delete'] + ['order_id[]' => $order['order_id']]);
			}
		}

		$view->orders = $results['orders'];
		$view->count  = $results['count'];
	}
}
