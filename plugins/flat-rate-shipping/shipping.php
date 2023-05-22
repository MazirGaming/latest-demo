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

/*
Name: Cash on delivery payment method
Slug: cash-on-delivery-payment
Category: payment
Url: https://www.vvveb.com
Description: Adds cash on delivery payment method on checkout page
Author: givanz
Version: 0.1
Thumb: cash.svg
Author url: http://www.vvveb.com
*/

namespace Vvveb\Plugins\FlatRateShipping;

use Vvveb\System\ShippingMethod;

class Shipping extends ShippingMethod {
	public function getMethod() {
		$method_data = [
			'name'         => 'flat-rate',
			'title'        => 'Flat rate',
			'description'  => 'Fixed shipping rate',
			'cost' 	       => 1,
			'terms'        => '',
			'tax'          => 1,
			'vat'          => 1,
			'zone_id'      => 1,
			'price'	       => 7,
			'tax_class_id' => 1,
		];

		$cost = 7;
		$text = '';

		if ($this->cart->getSubtotal() > 100) {
			$method_data['price'] = 0;
			$text                 = 'Free shipping';
		}

		$this->cart->addTotal('flat-rate-shipping','Flat rate shipping', $method_data['price'], $text);
		$this->cart->addTax('flat-rate-shipping', $method_data['price'], $method_data['tax_class_id']);

		return $method_data;
	}
}
