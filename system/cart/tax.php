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

namespace Vvveb\System\Cart;

use Vvveb\Sql\Tax_ClassSQL;

class Tax {
	private $driver;

	private $taxRates;

	public static function getInstance($options = []) {
		static $inst = null;

		if ($inst === null) {
			$inst   = new Tax($options);
		}

		return $inst;
	}

	public function __construct($options = []) {
	}

	public function setZoneRules($country_id, $zone_id, $based = 'store') {
		$taxClass = new Tax_ClassSQL();
		$taxRules = $taxClass->getZoneRules(['country_id' => $country_id, 'zone_id' => $zone_id, 'based' => $based]);

		foreach ($taxRules['tax_rule'] as $rate) {
			$this->taxRates[$rate['tax_class_id']][$rate['tax_rate_id']] = [
				'tax_rate_id' => $rate['tax_rate_id'],
				'name'        => $rate['name'],
				'rate'        => $rate['rate'],
				'type'        => $rate['type'],
				'priority'    => $rate['priority'],
				'based'       => $based,
			];
		}
	}

	public function addTaxes($value, $taxClassId, $onlyTax = false) {
		$amount = 0;

		foreach ($this->taxRates[$taxClassId] as $taxRate) {
			if ($taxRate['type'] == 'f') {//fixed
				$amount += $taxRate['rate'];
			} elseif ($taxRate['type'] == 'p') {//percent
				$amount += ($value / 100 * $taxRate['rate']);
			}
		}

		if ($onlyTax) {
			return $amount;
		}

		return $value + $amount;
	}

	public function getRates($value, $taxClassId) {
		$taxRate_data = [];

		if (isset($this->taxRates[$taxClassId])) {
			foreach ($this->taxRates[$taxClassId] as $taxRate) {
				if (isset($taxRate_data[$taxRate['tax_rate_id']])) {
					$amount = $taxRate_data[$taxRate['tax_rate_id']]['amount'];
				} else {
					$amount = 0;
				}

				if ($taxRate['type'] == 'f') {
					$amount += $taxRate['rate'];
				} elseif ($taxRate['type'] == 'p') {
					$amount += ($value / 100 * $taxRate['rate']);
				}

				$taxRate_data[$taxRate['tax_rate_id']] = [
					'tax_rate_id'  => $taxRate['tax_rate_id'],
					'name'         => $taxRate['name'],
					'rate'         => $taxRate['rate'],
					'type'         => $taxRate['type'],
					'amount'       => $amount,
				];
			}
		}

		return $taxRate_data;
	}

	public function clear() {
		$this->taxRates = [];
	}
}
