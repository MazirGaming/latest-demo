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

use function Vvveb\availableCurrencies;

class Currency {
	private $driver;

	private $currencies;

	public static function getInstance($options = []) {
		static $inst = null;

		if ($inst === null) {
			$inst   = new Currency($options);
		}

		return $inst;
	}

	public function __construct($options = []) {
		$this->currencies = availableCurrencies();
	}

	public function format($number, string $currency, $value = 0, bool $format = true) {
		return "$currency $number";

		if (! isset($this->currencies[$currency])) {
			return '';
		}

		$symbol_left   = $this->currencies[$currency]['symbol_left'];
		$symbol_right  = $this->currencies[$currency]['symbol_right'];
		$decimal_place = $this->currencies[$currency]['decimal_place'];

		if (! $value) {
			$value = $this->currencies[$currency]['value'];
		}

		$amount = $value ? (float)$number * $value : (float)$number;

		$amount = round($amount, $decimal_place);

		if (! $format) {
			return $amount;
		}

		$string = '';

		if ($symbol_left) {
			$string .= $symbol_left;
		}

		$string .= number_format($amount, $decimal_place, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

		if ($symbol_right) {
			$string .= $symbol_right;
		}

		return $string;
	}

	public function convert($value, string $from, string $to) : float {
		if (isset($this->currencies[$from])) {
			$from = $this->currencies[$from]['value'];
		} else {
			$from = 1;
		}

		if (isset($this->currencies[$to])) {
			$to = $this->currencies[$to]['value'];
		} else {
			$to = 1;
		}

		return $value * ($to / $from);
	}
}
