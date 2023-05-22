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

namespace Vvveb\Controller;

use \Vvveb\System\Core\FrontController;
use \Vvveb\System\Functions\Str;
use function Vvveb\availableCurrencies;
use function Vvveb\availableLanguages;
use function Vvveb\filter;
use function Vvveb\setLanguage;
use function Vvveb\siteSettings;
use Vvveb\System\Core\View;
use Vvveb\System\User\Admin;
use Vvveb\System\User\User;

#[\AllowDynamicProperties]
class Base {
	function init() {
		if (! $this->session->get('csrf')) {
			$this->session->set('csrf', Str::random());
		}

		//check if theme preview
		$theme = $this->request->get['theme'] ?? false;

		if ($theme) {
			//check if admin user to allow theme preview
			$admin = Admin::current();

			if ($admin) {
				$this->view->setTheme($theme);
			}
		}

		$user = User::current();

		if ($language = ($this->request->post['language'] ?? false)) {
			$language = filter('/[A-Za-z_-]+/', $language, 50);
			$this->session->set('language', $language);
			$languages = availableLanguages();
			$this->session->set('language_id', $languages[$language]['language_id'] ?? 1);
		}

		if ($currency = ($this->request->post['currency'] ?? false)) {
			$currency = filter('/[A-Za-z_-]+/', $currency, 50);
			$this->session->set('currency', $currency);
			$currencies = availableCurrencies();
			$this->session->set('currency_id', $currencies[$currency]['currency_id'] ?? 1);
		}

		$site 		     = siteSettings();
		$language    = $this->session->get('language') ?? 'en_US';
		$currency    = $this->session->get('currency') ?? 'USD';
		$language_id = $this->session->get('language_id') ?? 1;
		$currency_id = $this->session->get('currency_id') ?? 1;

		$this->global['site_id']        = SITE_ID ?? 1;
		$this->global['user_id']        = $user['user_id'] ?? false;
		$this->global['language_id']    = $language_id;
		$this->global['currency_id']    = $currency_id;
		$this->global['language']       = $language;
		$this->global['currency']       = $currency;
		$this->global['site']       	   = $site;

		setLanguage($language);

		$this->view->global = $this->global;
		$this->initEcommerce();

		if (\Vvveb\isEditor()) {
			$this->view->errors[]  = 'This is a dummy error message!';
			$this->view->success[] = 'This is a dummy success message!';
			$this->view->info[]    = 'This is a dummy info message!';
			$this->view->message[] = 'This is a dummy message!';
		}
	}

	function initEcommerce() {
	}

	function redirect($url = '/', $parameters = []) {
		$redirect = \Vvveb\url($url, $parameters);

		if ($redirect) {
			$url = $redirect;
		}

		if ($this->session) {
			$this->session->close();
		}

		return header("Location: $url");
	}

	/**
	 * Call this method if the action requires login, if the user is not logged in, a login form will be shown.
	 *
	 */
	function requireLogin() {
		$view = view :: getInstance();
		$view :: template('/login.html');

		die(view :: getInstance()->render());
	}

	/**
	 * Call this function if the requeste information was not found, for example if the specifed news, image, profile etc is not found then call this function.
	 * It shows a "Not found" page and it also send 404 http status code, this is usefull for search engines etc.
	 *
	 * @param unknown_type $code
	 * @param mixed $service
	 * @param mixed $statusCode
	 * @param null|mixed $message
	 */
	function notFound($service = false, $statusCode = 404, $message = null) {
		return FrontController::notFound($service, $statusCode , $message);
	}
}
