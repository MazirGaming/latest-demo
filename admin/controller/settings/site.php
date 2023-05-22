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

use function Vvveb\__;
use Vvveb\Controller\Base;
use Vvveb\Sql\SiteSQL;
use Vvveb\System\CacheManager;
use Vvveb\System\Extensions\Themes;
use Vvveb\System\Images;
use Vvveb\System\Sites;
use Vvveb\System\Validator;

class Site extends Base {
	function add() {
	}

	function save() {
		$siteValidator 		    = new Validator(['site']);
		$settingsValidator	  = new Validator(['site-settings']);

		$view      = $this->view;
		$site 	    = $this->request->post['site'] ?? [];
		$settings  = $this->request->post['settings'] ?? [];

		if (($errors = $siteValidator->validate($site)) === true &&
			($errors = $settingsValidator->validate($settings)) === true) {
			$sites = new SiteSQL();

			if (! isset($site['key']) || ! $site['key']) {
				$site['key'] = strtolower($site['name']);
			}

			if (isset($this->request->get['site_id'])) {
				$data['site_id']  = (int)$this->request->get['site_id'];
				$site['settings'] = json_encode($settings);
				$data['site']     = $site;
				$site['id']       = $data['site_id'];
				$result           = $sites->edit($data);

				//Sites::saveSite($site);
				unset($site['settings']);
				Sites::setSiteDataById($data['site_id'], null, $site);

				if ($result >= 0) {
					//CacheManager::delete('site');
					CacheManager::delete();
					$message             = __('Site saved!');
					$this->view->success = [$message];
					$this->redirect(['module'=>'settings/sites', 'success'=> $message]);
				} else {
					$this->view->errors = [$sites->error];
				}
			} else {
				$return     = $sites->add(['site' => $site]);
				$id         = $return['site'];
				$site['id'] = $id;
				Sites::saveSite($site);

				if (! $id) {
					$view->errors = [$sites->error];
				} else {
					//CacheManager::delete('site');
					CacheManager::delete();
					$message       = __('Site saved!');
					$view->success = [$message];
					$this->redirect(['module'=>'settings/sites', 'success'=> $message]);
				}
			}
		} else {
			$view->errors = $errors;
		}

		$this->index();
	}

	function index() {
		$themeList = Themes:: getList();

		$site_id                   = $this->request->get['site_id'] ?? null;
		$view                      = $this->view;
		$view->themeList           = $themeList;
		$view->templateList        = \Vvveb\getTemplateList(false, ['email']);
		$site                      = [];

		if ($site_id) {
			$siteSql             = new SiteSQL();
			$site                = $siteSql->get(['site_id' => $site_id]);
		}

		$view->site          = $site;
		$view->setting       = json_decode($site['settings'], true);

		foreach (['favicon', 'logo', 'logo-sticky', 'logo-dark', 'logo-dark-sticky'] as $img) {
			if (isset($view->setting[$img])) {
				$view->setting[$img . '-src'] = Images::image($view->setting[$img], '');
			}
		}

		$admin_path          = \Vvveb\adminPath();

		$controllerPath        = $admin_path . 'index.php?module=media/media';
		$view->scanUrl         = "$controllerPath&action=scan";
		$view->uploadUrl       = "$controllerPath&action=upload";
	}
}
