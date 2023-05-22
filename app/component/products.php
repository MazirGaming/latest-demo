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

namespace Vvveb\Component;

use Vvveb\System\Cart\Currency;
use Vvveb\System\Cart\Tax;
use Vvveb\System\Component\ComponentBase;
use Vvveb\System\Event;
use Vvveb\System\Images;

class Products extends ComponentBase {
	public static $defaultOptions = [
		'start'                        => 0,
		'source'                       => 'autocomplete',
		'page'                         => 1,
		'language_id'                  => null,
		'site_id'                      => null,
		'limit'                        => 4,
		'type'                         => 'product',
		'parent'                       => null,
		'id_manufacturer'              => NULL,
		'order_by'                     => 'url',
		'direction'                    => ['url', 'asc'],
		'taxonomy_item_id'             => NULL,
		'include_image_gallery'        => true,
		'product_id'                   => [],
		'manufacturer_id'              => [],
		'category'                     => [],
		'search'                       => null,
		'slug'                         => null,
	];

	public $options = [];

	function __construct($options) {
		return parent::__construct($options);
	}

	function results() {
		$products = new \Vvveb\Sql\ProductSQL();

		if ($page = $this->options['page']) {
			$this->options['start'] = ($page - 1) * $this->options['limit'];
		}

		if (isset($this->options['product_id']) && $this->options['source'] == 'autocomplete') {
			$this->options['product_id'] = array_keys($this->options['product_id']);
		} else {
			$this->options['product_id'] = [];
		}

		if (isset($this->options['order_by']) &&
				! in_array($this->options['order_by'], ['price', 'date_added', 'date_modified'])) {
			unset($this->options['order_by']);
		}

		if (isset($this->options['direction']) &&
				! in_array($this->options['direction'], ['asc', 'desc'])) {
			unset($this->options['direction']);
		}

		//if only one slug is provided then add it to array
		if (isset($this->options['slug']) && ! is_array($this->options['slug'])) {
			$this->options['slug'] = [$this->options['slug']];
		}

		$results = $products->getAll($this->options) + $this->options;

		if ($results && isset($results['products'])) {
			$tax      = Tax::getInstance();
			$currency = Currency::getInstance();

			foreach ($results['products'] as $id => &$product) {
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

				$product['price_tax']           = $tax->addTaxes($product['price'], $product['tax_class_id']);
				$product['price_tax_formatted'] = $currency->format($product['price_tax'], '$');
				$product['price_formatted']     = $currency->format($product['price'], '$');
			}
		}

		list($results) = Event :: trigger(__CLASS__,__FUNCTION__, $results);

		return $results;
	}
}
