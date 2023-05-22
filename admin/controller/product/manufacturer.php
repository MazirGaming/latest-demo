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

use Vvveb\Controller\Crud;

class Manufacturer extends Crud {
	protected $type = 'manufacturer';

	protected $module = 'product';
}

/*
use Vvveb\Controller\Base;
use Vvveb\Sql\manufacturerSQL;
use Vvveb\System\Core\View;
use Vvveb\System\Images;

class Manufacturer extends Base {
	function save() {
		$manufacturer_id = $this->request->get['manufacturer_id'] ?? false;
		$manufacturer    = $this->request->post['manufacturer'] ?? false;

		if ($manufacturer) {
			$manufacturers = new manufacturerSQL();

			$options       = ['manufacturer' => $manufacturer] + $this->global;

			if ($manufacturer_id) {
				$options['manufacturer_id'] = $manufacturer_id;
				$result                     = $manufacturers->editManufacturer($options);
			} else {
				$result        = $manufacturers->addManufacturer($options);
			}

			if ($result && $result['manufacturer'] > 0) {
				$successMessage        = __('Manufacturer saved!');
				$this->view->success[] = $successMessage;

				if (! $manufacturer_id) {
					$this->redirect(['module' => 'product/manufacturer', 'manufacturer_id' => $result['manufacturer'], 'success' => $successMessage]);
				}
			} else {
				$this->view->errors[] = __('Error saving!');
			}
		}

		return $this->index();
	}

	function index() {
		$view                = View :: getInstance();
		$manufacturers       = new manufacturerSQL();
		$manufacturer_id     = $this->request->get['manufacturer_id'] ?? false;
		$admin_path          = \Vvveb\adminPath();

		$controllerPath        = $admin_path . 'index.php?module=media/media';
		$view->scanUrl         = "$controllerPath&action=scan";
		$view->uploadUrl       = "$controllerPath&action=upload";

		$options = [
			'manufacturer_id'         => $manufacturer_id,
		] + $this->global;
		unset($options['user_id']);

		$manufacturer = $manufacturers->getManufacturer($options);

		if (isset($manufacturer['image'])) {
			$manufacturer['image_url'] = Images::image($manufacturer['image'], 'post');
		}

		$this->view->manufacturer = $manufacturer;
	}
}
*/
