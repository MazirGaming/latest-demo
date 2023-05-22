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

use function Vvveb\__;
use Vvveb\Controller\Crud;
use Vvveb\Sql\CountrySQL;
use Vvveb\Sql\Zone_GroupSQL;
use Vvveb\Sql\ZoneSQL;

class ZoneGroup extends Crud {
	protected $type = 'zone_group';

	protected $controller = 'zone-group';

	protected $module = 'localization';

	function save() {
		$zone          = $this->request->post['zone'] ?? [];
		$zone_group_id = $this->request->get['zone_group_id'] ?? false;

		if ($zone_group_id) {
			$zoneGroup = new Zone_GroupSQL();
			$result    = $zoneGroup->addZones(['zone_group_id' => $zone_group_id, 'zone_to_zone_group' => $zone]);

			if ($result && isset($result['zone_to_zone_group'])) {
				$successMessage        = __('Zone saved!');
				$this->view->success[] = $successMessage;
				$this->view->errors    = [];
			} else {
				$this->view->errors[] = __('Error saving!');
			}
		}

		parent::save();
	}

	function zones() {
		$country_id = $this->request->get['country_id'] ?? false;
		$zones      = [];

		if ($country_id) {
			$zone                  = new ZoneSQL();
			$options               = $this->global;
			$options['status'] 	   = 1;
			$options['country_id'] = $country_id;
			unset($options['limit']);
			$zones	               = $zone->getAll($options)['zone'] ?? [];
		}

		$this->response->setType('json');
		$this->response->output($zones);
		//return [];
	}

	function index() {
		parent::index();
		$zone_group_id = $this->request->get['zone_group_id'] ?? false;

		$zones = [];

		if ($zone_group_id) {
			$zoneGroup  = new Zone_GroupSQL();
			$zones	     = $zoneGroup->getZones(['zone_group_id' => $zone_group_id])['zones'] ?? [];
		}
		$this->view->zones         = $zones;
		$this->view->zone_group_id = $zone_group_id;

		$countryModel      = new CountrySQL();
		$options           = $this->global;
		$options['status'] = 1;
		unset($options['limit']);
		$country	 = $countryModel->getAll($options);

		$this->view->countries = $country['country'] ?? [];

		$admin_path             = \Vvveb\adminPath();
		$controllerPath         = $admin_path . 'index.php?module=localization/zone-group';
		$this->view->zonesUrl   = "$controllerPath&action=zones";
	}
}
