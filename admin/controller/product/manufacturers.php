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

use Vvveb\Controller\Listing;

class Manufacturers extends Listing {
	protected $type = 'manufacturer';

	protected $list = 'manufacturers';

	protected $module = 'product';
}

/*
use Vvveb\Controller\Base;
use Vvveb\Sql\ManufacturerSQL;
use Vvveb\System\Core\View;
use Vvveb\System\Images;

class Manufacturers extends Base {
	protected $type = 'manufacturer';

	function init() {
		if (isset($this->request->get['type'])) {
			$this->type = $this->request->get['type'];
		}

		return parent::init();
	}

	function delete() {
		$manufacturer_id    = $this->request->post['manufacturer_id'] ?? $this->request->get['manufacturer_id'] ?? false;

		if ($manufacturer_id) {
			if (is_numeric($manufacturer_id)) {
				$manufacturer_id = [$manufacturer_id];
			}

			$manufacturers = new ManufacturerSQL();
			$options       = ['manufacturer_id' => $manufacturer_id] + $this->global;
			$result        = $manufacturers->delete($options);

			if ($result && $result['manufacturer'] > 0) {
				$this->view->success[] = __('Manufacturer(s) deleted!');
			} else {
				$this->view->errors[] = 'Error deleting manufacturer!';
			}
		}

		return $this->index();
	}

	function index() {
		$view          = View :: getInstance();

		$manufacturers = new ManufacturerSQL();

		$page    = $this->request->get['page'] ?? 1;
		$limit   = $this->request->get['limit'] ?? 10;

		$options = [
			'type'        => $this->type,
		] + $this->global;

		$results = $manufacturers->getAll($options);

		foreach ($results['manufacturers'] as $id => &$manufacturer) {
			if (isset($manufacturer['images'])) {
				$manufacturer['images'] = json_decode($manufacturer['images'], 1);

				foreach ($manufacturer['images'] as &$image) {
					$image = Images::image($image, 'manufacturer');
				}
				//	var_dump($manufacturer['images']);
			} else {
				if (isset($manufacturer['image'])) {
					$manufacturer['image'] = Images::image($manufacturer['image'], 'manufacturer');
				}
			}

			$manufacturer['url']        = \Vvveb\url(['module' => 'product/manufacturer', 'manufacturer_id' => $manufacturer['manufacturer_id']]);
			$manufacturer['edit-url']   = \Vvveb\url(['module' => 'product/manufacturer', 'manufacturer_id' => $manufacturer['manufacturer_id']]);
			$manufacturer['delete-url'] = \Vvveb\url(['module' => 'product/manufacturers', 'action' => 'delete', 'manufacturer_id[]' => $manufacturer['manufacturer_id']]);
		}

		$view->manufacturers = $results['manufacturers'];
		$view->count         = $results['count'];
		$view->limit         = $limit;

		//$results['count'] = $Manufacturers->count();
		//$view->count = 10;
		//$view->limit = $Manufacturers->limit;
	}
}
*/
