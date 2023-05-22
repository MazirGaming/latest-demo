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

namespace Vvveb\Controller\Checkout;

use function Vvveb\__;
use Vvveb\Controller\Base;
use Vvveb\Controller\User\LoginTrait;
use function Vvveb\email;
use function Vvveb\prefixArrayKeys;
use Vvveb\Sql\CountrySQL;
use Vvveb\Sql\ZoneSQL;
use Vvveb\System\Cart\Cart;
use Vvveb\System\Cart\Order;
use Vvveb\System\Core\View;
use Vvveb\System\Payment;
use Vvveb\System\Shipping;
use Vvveb\System\Validator;
use function Vvveb\url;

class Checkout extends Base {
	use LoginTrait;

	function zones() {
		$country_id = $this->request->get['country_id'] ?? false;
		$zones      = [];

		if ($country_id) {
			$zone              = new ZoneSQL();
			$options           = $this->global;
			$options['status'] = 1;
			unset($options['limit']);
			$options['country_id'] = $country_id;
			$zones	                = $zone->getAll($options)['zone'] ?? [];
		}

		$this->response->setType('json');
		$this->response->output($zones);
	}

	private function data() {
		$countryModel      = new CountrySQL();
		$options           = $this->global;
		$options['status'] = 1;
		unset($options['limit']);
		$country	              = $countryModel->getAll($options);
		$this->view->countries = $country['country'] ?? [];

		//set zones for default store country
		/*
		$zone  = new ZoneSQL();
		$zones	 = $countryModel->getAll($options);

		$options['country_id'] = $country_id;
		$this->view->zones = $zones['zone'] ?? [];
		*/
		$this->view->zonesUrl   = url(['module' => 'checkout/checkout', 'action' => 'zones']);
	}

	function index() {
		if (isset($this->request->post['login'])) {
			return $this->login();
		}

		$payment  = Payment::getInstance();
		$shipping = Shipping::getInstance();
		$order    = Order::getInstance();

		$this->view->payment  = $payment->getMethods();
		$this->view->shipping = $shipping->getMethods();
		$this->data();
		//$this->view->set($order->getData());

		if ($this->request->post) {
			$rules = ['checkout'];

			//not logged in validate guest fields
			if (! $this->global['user_id']) {
				$rules[] = 'guest';
			}

			//billing address address check
			if (empty($this->request->post['billing_address_id'])) {
				//not logged in check guest fields
				$rules[] = 'checkout_billing';

				if (isset($this->request->post['billing_address'])) {
					$this->request->post += prefixArrayKeys('billing_', $this->request->post['billing_address']);
				}
			}

			//shipping address is selected
			if (! empty($this->request->post['no_shipping'])) {
				//not logged in check guest fields
				$rules[] = 'checkout_shipping';

				if (isset($this->request->post['shipping_address'])) {
					$this->request->post += prefixArrayKeys('shipping_', $this->request->post['shipping_address']);
				}
			}

			$validator = new Validator($rules); //, 'checkout_payment', 'checkout_shipping']);

			$checkoutInfo             = $validator->filter($this->request->post);
//			var_dump($checkoutInfo);

			if (email($checkoutInfo['email'], 'order/new', $checkoutInfo)) {
			}

			if (($errors = $validator->validate($this->request->post)) === true) {
				$cart = Cart :: getInstance();
				//allow only fields that are in the validator list and remove the rest

				$checkoutInfo             = $validator->filter($this->request->post);
				$checkoutInfo['products'] = $cart->getAll();
				$checkoutInfo['totals']   = $cart->getTotals();

				$checkoutInfo += $this->global;

				if (! $checkoutInfo['user_id']) {
					unset($checkoutInfo['user_id']); //if anonymous then unset user_id
				}

				$order = $order->add($checkoutInfo);

				if (email($checkoutInfo['email'], 'order/new', $checkoutInfo)) {
				}

				$this->view->errors = [];

				if ($order && is_array($order)) {
					$this->view->messages[] = __('Order placed!');
					$this->session->set('order', $order);
					$cart->empty();

					return $this->redirect('checkout/confirm/index');
				} else {
					$this->view->errors[] = __('Error creating checkout!');
				}
			} else {
				$this->view->errors = $errors;
			}
		}
	}

	function old() {
		$cart = Cart::getInstance();

		if (isset($this->request->get['product_id'])) {
			$cart->add($this->request->get['product_id']);
		}

		$results = $cart->getAll();

		$validator = new Validator(['checkout']);

		if ($this->request->post &&
			($valid = $validator->validate($this->request->post)) === true) {
		}

		//$this->view->products = $results['products'];
		//$this->view->count = $results['count'];

		//$this->view->
	}
}
