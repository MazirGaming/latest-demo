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

use function Vvveb\__;
use function Vvveb\availableCurrencies;
use function Vvveb\availableLanguages;
use function Vvveb\filter;
use function Vvveb\setLanguage;
use Vvveb\Sql\LanguageSQL;
use Vvveb\Sql\SiteSQL;
use Vvveb\System\Core\FrontController;
use Vvveb\System\Core\Request;
use Vvveb\System\Core\View;
use Vvveb\System\Event;
use Vvveb\System\Extensions\Plugins;
use Vvveb\System\Functions\Str;
use Vvveb\System\Session;
use Vvveb\System\Sites;
use Vvveb\System\User\Admin;

#[\AllowDynamicProperties]
class Base {
	public $view;

	public $request;

	public $sesssion;

	public $global;

	protected function setSite($id = false) {
		//if no id set default
		if ($id) {
			$site  = Sites::getSiteById($id);
		} else {
			$site = Sites::getDefault();
		}

		$this->session->set('site', $site);
		$this->session->set('site_url', $site['host']);
		$this->session->set('site', $site['id']);
		$this->session->set('state', $site['state']);
		$site = $site['id'];

		return $site;
	}

	protected function customPosts() {
		//custom posts -- add to menu
		$default_custom_posts =
		[
			'post' => [
				'type'        => 'post',
				'plural'      => 'posts',
				'icon'        => 'ion-ios-photos-outline',
			],
			'page' => [
				'type'        => 'page',
				'plural'      => 'pages',
				'icon'        => 'ion-ios-list-outline',
			],
		];

		$custom_posts_types             = \Vvveb\get_setting('custom_posts_types', $default_custom_posts);
		list($custom_posts_types)       = Event::trigger(__CLASS__, __FUNCTION__, $custom_posts_types);

		$custom_post_menu = \Vvveb\config('custom-post-menu', []);
		$posts_menu       = [];

		foreach ($custom_posts_types as $type => $settings) {
			if ($type == 'page') {
				continue;
			}
			$posts_menu[$type] = $custom_post_menu;

			$posts_menu[$type]['name']                   =
			$posts_menu[$type]['items']['posts']['name'] =
			_(ucfirst($settings['plural']));

			$posts_menu[$type]['icon']     = $settings['icon'] ?? '';
			$posts_menu[$type]['icon-img'] = $settings['icon-img'] ?? '';
			$posts_menu[$type]['url'] .= "&type=$type";

			foreach ($posts_menu[$type]['items'] as $item => &$values) {
				if (isset($values['url'])) {
					$values['url'] .= "&type=$type";
				}
			}
		}

		return $posts_menu;
	}

	protected function customProducts() {
		//custom products -- add to menu
		$default_custom_products =
		[
			'product' => [
				'type'   => 'product',
				'plural' => 'products',
				'icon'   => 'ion-ios-pricetag-outline',
			],
		];

		$custom_products_types             = \Vvveb\get_setting('custom_products_types', $default_custom_products);
		list($custom_products_types)       = Event::trigger(__CLASS__, __FUNCTION__, $custom_products_types);

		$custom_product_menu = \Vvveb\config('custom-product-menu', []);
		$products_menu       = [];

		foreach ($custom_products_types as $type => $settings) {
			if ($type == 'page') {
				continue;
			}
			$products_menu[$type] = $custom_product_menu;

			$products_menu[$type]['name']                      =
			$products_menu[$type]['items']['products']['name'] =
			_(ucfirst($settings['plural']));

			$products_menu[$type]['icon']     = $settings['icon'] ?? '';
			$products_menu[$type]['icon-img'] = $settings['icon-img'] ?? '';
			$products_menu[$type]['url'] .= "&type=$type";

			foreach ($products_menu[$type]['items'] as $item => &$values) {
				if (isset($values['url'])) {
					$values['url'] .= "&type=$type";
				}
			}
		}

		return $products_menu;
	}

	/*
	 * Permission check for each module/action
	 */
	function permissions() {
		$module     = strtolower(FrontController::getModuleName());
		$action     = strtolower(FrontController::getActionName());
		$action     = $action ? '/' . $action : '';
		$permission = $module . $action;

		//if current module/action does not have permission then show permission denied page
		if (! Admin::hasPermission($permission)) {
			$message       = __('Your role does not have permission to access this action!');
			$this->error[] = $message;

			die($this->notFound(true, 403, $message));
		}

		//get current controller methods to check for permission
		$methods = get_class_methods($this);
		//$methods = array_map(fn ($value) => "$module/$value", $methods);
		$methods = array_map(function ($value) use ($module) {return "$module/$value"; }, $methods);

		//check if controller requires additional permission check
		if (isset($this->additionalPermissionCheck)) {
			$methods = array_merge($methods, $this->additionalPermissionCheck);
		}

		$permissions = Admin::hasPermission($methods);

		//set a permission array only with action keys for easier permission check in html
		$this->modulePermissions = $permissions;

		foreach ($permissions as $permission => &$value) {
			$key                     = str_replace("$module/", '', $permission);
			$actionPermissions[$key] = $value;
		}
		$this->actionPermissions = $actionPermissions;
	}

	function getPermissionsFromUrl(&$array, &$permissions) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				if (isset($v['url'])) {
					if (isset($v['module'])) {
						$permissions[$v['url']] = ($v['module'] ?? '') . (isset($v['action']) ? '/' . $v['action'] : '');
					} else {
						$permissions[$v['url']] = \Vvveb\pregMatch('/module=([^&$]+)/', $v['url'], 1);
					}
				}
				$this->getPermissionsFromUrl($v, $permissions);
			}
		}
	}

	function setPermissionsFromUrl(&$array, &$permissions) {
		foreach ($array as $k => &$v) {
			if (is_array($v)) {
				if (isset($v['url'])) {
					$url = $v['url'];

					if (isset($permissions[$url])) {
						$v['permission'] = $permissions[$url];
					}
				}
				$this->setPermissionsFromUrl($v, $permissions);
			}
		}
	}

	function init() {
		if (! $this->session->get('csrf')) {
			$this->session->set('csrf', Str::random());
		}
		//$this->session->delete('csrf');

		$this->session = Session::getInstance();
		$this->request = Request::getInstance();
		$language      = $this->session->get('language') ?? 'en_US';
		setLanguage($language);

		$admin = Admin::current();

		if (! $admin) {
			return $this->requireLogin();
		}

		if (($site = ($this->request->post['site'] ?? false)) && is_numeric($site)) {
			$this->setSite($site);
		}
		$site = $this->session->get('site');

		if (! $site) {
			$this->setSite();
		}

		if (($language = ($this->request->post['language'] ?? false)) && ! is_array($language)) {
			$language = filter('/[A-Za-z_-]+/', $language, 50);
			$this->session->set('language', $language);
			$languages = availableLanguages();
			$this->session->set('language_id', $languages[$language]['language_id'] ?? 1);
		}

		if (($currency = ($this->request->post['currency'] ?? false)) && ! is_array($currency)) {
			$currency = filter('/[A-Za-z_-]+/', $currency, 50);
			$this->session->set('currency', $currency);
			$currencies = availableCurrencies();
			$this->session->set('currency_id', $currencies[$currency]['currency_id'] ?? 1);
		}

		if ($state = ($this->request->post['state'] ?? false)) {
			if (Sites::setSiteDataById($site, 'state', $state)) {
				$this->session->set('state', $state);
			}
		}

		$page        = $this->request->get['page'] ?? 1;
		$limit       = $this->request->get['limit'] ?? 10;
		$language    = $this->session->get('language') ?? 'en_US';
		$currency    = $this->session->get('currency') ?? 'USD';
		$language_id = $this->session->get('language_id') ?? 1;
		$currency_id = $this->session->get('currency_id') ?? 1;

		$this->global['site_id']        = $site;
		//$this->global['user_id']        = $admin['admin_id'];
		$this->global['admin_id']       = $admin['admin_id'];
		$this->global['state']          = $state;
		$this->global['page']           = $page;
		$this->global['start']          = ($page - 1) * $limit;
		$this->global['limit']          = $limit;
		$this->global['language_id']    = $language_id;
		$this->global['currency_id']    = $currency_id;
		$this->global['language']       = $language;
		$this->global['currency']       = $currency;

		//Check permissions
		if (get_class($this) != 'Vvveb\Controller\Error403') {
			$this->permissions();
		}

		//load plugins for active site
		if (! isset($admin['safemode'])) {
			Plugins :: loadPlugins($site);
		}

		$view = View :: getInstance();

		if (isset($this->request->get['errors'])) {
			$view->errors[] = htmlentities($this->request->get['errors']);
		}

		if ($errors = $this->session->get('errors')) {
			$view->errors[] = $errors;
			$this->session->delete('errors');
		}

		if (isset($this->request->get['success'])) {
			$view->success[] = htmlentities($this->request->get['success']);
		}

		if ($success = $this->session->get('success')) {
			$view->success[] = $success;
			$this->session->delete('success');
		}

		$menu             = \Vvveb\config('admin-menu', []);

		//don't initialize menu items for CLI
		if (defined('CLI')) {
			return;
		}

		$languages           = new languageSQL();
		$langs               = $languages->getAll(['status' => 1]);

		$view->languagesList = $langs['language'] ?? [];

		$sites             = new SiteSQL();
		//$view->sites       = $sites->getAll();

		//send to view for button visibillity check
		$this->view->actionPermissions = $this->actionPermissions ?? [];
		$this->view->modulePermissions = $this->modulePermissions ?? [];

		//custom posts -- add to menu
		$posts_menu = $this->customPosts();
		$menu       = \Vvveb\array_insert_array_after('edit', $menu, $posts_menu);

		//products -- add to menu
		$products_menu = $this->customProducts();
		$menu          = \Vvveb\array_insert_array_after('sales', $menu, $products_menu);

		list($menu)       = Event::trigger(__CLASS__, __FUNCTION__ . '-menu', $menu);
		//var_dump($menu);

		$urls = [];
		$this->getPermissionsFromUrl($menu, $urls);
		$permissions = Admin::hasPermission($urls);
		//$urls        = array_map(fn ($value) => $value ? ($permissions[$value] ?? false) : false, $urls);
		$urls        = array_map(function ($value) use ($permissions) { return $value ? ($permissions[$value] ?? false) : false; }, $urls);
		$this->setPermissionsFromUrl($menu, $urls);

		//$this->my_walk_recursive($menu, $urls);

		/*
				$counter = 0;
				array_walk_recursive($menu, function ($item, $key) use (&$permissions, &$counter) {
					if ($key == 'module') {
						$permissions[$counter++] = $item;
					}
				});
				//var_dump($permissions);
		*/
		$view->menu       = $menu;

		$view->mediaPath  = PUBLIC_PATH . 'media';
		$view->publicPath = PUBLIC_PATH . 'media';
	}

	protected function redirect($url = '/', $parameters = []) {
		$redirect = \Vvveb\url($url, $parameters);

		if ($redirect) {
			$url = $redirect;
		}

		$this->session->close();

		return header("Location: $url");
	}

	/**
	 * Call this method if the action requires login, if the user is not logged in, a login form will be shown.
	 *
	 */
	protected function requireLogin() {
		//return \Vvveb\System\Core\FrontController::redirect('user/login');
		//$view = view :: getInstance();
		$admin_path         = \Vvveb\adminPath();
		$this->view->action = "$admin_path/?module=user/login";
		$this->view->template('user/login.html');

		die($this->view->render());
	}

	/**
	 * Shows a "Not found", "Internal server error" or "Permission denied" page.
	 *
	 * @param unknown_type $code
	 * @param mixed $statusCode
	 * @param mixed $service
	 * @param mixed $message
	 */
	protected function notFound($service = false, $statusCode = 404, $message = false) {
		return FrontController::notFound($service, $statusCode, $message);
	}

	/**
	 * Generates the documentation link for current page.
	 *
	 * @param unknown_type $code
	 * @param null|mixed $module
	 * @param null|mixed $action
	 */
	protected function getDocUrlForPage($module = null, $action = null) {
		$module = $module ?? $this->request->get['module'] ?? '';
		$action = $action ?? $this->request->get['origaction'] ?? '';
		$type   = $type ?? $this->request->get['type'] ?? '';
		$action = $action ? '/' . $action : '';
		$type   = $type ? '/' . $type : '';
		$url    = 'https://docs.vvveb.com/';

		$documentionList             = include DIR_SYSTEM . 'data/documentation-map.php';

		if (isset($documentionList[$module . $action . $type])) {
			$url .= $documentionList[$module . $action . $type];
		} else {
			$url .= str_replace('/', '-', $module . $action . $type);
		}

		return $url;
	}

	function goToHelp() {
		$url = $this->getDocUrlForPage();

		return header("Location: $url");

		die($url);
	}
}
