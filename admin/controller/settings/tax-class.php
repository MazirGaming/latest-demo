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

namespace Vvveb\Controller\Settings;

use Vvveb\Controller\Crud;
use Vvveb\Sql\Tax_RateSQL;
use Vvveb\Sql\Tax_RuleSQL;

class TaxClass extends Crud {
	protected $type = 'tax_class';

	protected $controller = 'tax-class';

	protected $module = 'settings';

	function save() {
		$tax_rule     = $this->request->post['tax_rule'] ?? [];
		$tax_class_id = $this->request->get['tax_class_id'] ?? false;

		if ($tax_class_id) {
			$taxRules = new Tax_RuleSQL();
			$taxRules->add(['tax_class_id' => $tax_class_id, 'tax_rule' => $tax_rule]);
		}

		parent::save();
	}

	function index() {
		parent::index();
		$tax_class_id = $this->request->get['tax_class_id'] ?? false;

		if ($tax_class_id) {
			$taxRules              = new Tax_RuleSQL();
			$taxRates              = new Tax_RateSQL();
			$tax_rates             = $taxRates->getAll(['tax_class_id' => $tax_class_id]);
			$this->view->tax_rates = $tax_rates['tax_rate'];

			$tax_rules             = $taxRules->getAll(['tax_class_id' => $tax_class_id]);
			$this->view->tax_rules = $tax_rules['tax_rule'];
		}

		$this->view->tax_class_id = $tax_class_id;
		$this->view->based   	    = ['shipping' =>'Shipping Address', 'payment' => 'Payment Address', 'store' => 'Store Address'];
	}
}
