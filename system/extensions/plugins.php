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

namespace Vvveb\System\Extensions;

use \Vvveb\System\Cache;
use Vvveb\System\Event;

class Plugins extends Extensions {
	//static protected $url = 'http://plugins.vvveb.com/api/plugins.json';
	static protected $url = 'https://plugins.vvveb.com';

	static protected $feedUrl = 'https://plugins.vvveb.com/feed/plugins';

	static protected $extension = 'plugin';

	static protected $loaded  = false;

	static protected $baseDir = DIR_PLUGINS;

	static protected $plugins = [];

	static protected $categories = [];

	static function getInfo($content, $name = false) {
		$params               = parent::getInfo($content, $name);
		$params['status']     = 'inactive';

		if (isset($params['thumb'])) {
			$params['thumb_url'] = PUBLIC_PATH . 'plugins/' . $name . '/' . $params['thumb'];
		} else {
			//$params['thumb_url'] = PUBLIC_PATH . 'plugins/plugin.svg';
		}

		return $params;
	}

	static function loadPlugin($pluginName) {
		$file = DIR_PLUGINS . $pluginName . '/plugin.php';

		if (file_exists($file)) {
			return include $file;
		}

		return false;
	}

	static function activate($pluginName, $site_id = SITE_ID) {
		if (! $pluginName) {
			return;
		}

		$file = DIR_PLUGINS . $pluginName . '/plugin.php';

		if (file_exists($file)) {
			$key    = "plugins.$site_id.$pluginName.status";
			$return = \Vvveb\set_config($key, 'active');
			Event :: trigger(__CLASS__, __FUNCTION__, $pluginName);

			$cacheDriver = Cache :: getInstance();
			$cacheKey    = "plugins_list_$site_id";
			$cacheDriver->delete('vvveb', $cacheKey);

			return $return;
		}
	}

	static function deactivate($pluginName, $site_id = SITE_ID) {
		if (! $pluginName) {
			return;
		}
		/*
		$key    = "plugins.$site_id.$pluginName.status";
		$return = \Vvveb\set_config($key, 'inactive');
		*/
		$key    = "plugins.$site_id.$pluginName";
		$return = \Vvveb\unset_config($key, []);
		Event :: trigger(__CLASS__, __FUNCTION__, $pluginName);

		$cacheDriver = Cache :: getInstance();
		$cacheKey    = "plugins_list_$site_id";
		$cacheDriver->delete('vvveb', $cacheKey);

		return $return;
	}

	static function install2($pluginName) {
		//copy plugin public folder to public/plugins folder
		//rcopy(DIR_PLUGINS . "$pluginName/public", DIR_PUBLIC . "public/plugins/$pluginName");
	}

	static function uninstall($pluginName) {
		//remove public folder from public/plugins/$pluginName
		//rrmdir(DIR_PUBLIC . "public/plugins/$pluginName");
		Event :: trigger(__CLASS__, __FUNCTION__, $pluginName);
	}

	static function getList($site_id = SITE_ID, $category = false) {
		$cacheDriver = Cache :: getInstance();
		$cacheKey    = "plugins_list_$site_id";

		if (! $category && $result = $cacheDriver->get('vvveb', $cacheKey)) {
			return $result;
		} else {
			$pluginList   = parent :: getListInfo(DIR_PLUGINS . '/*/plugin.php');
			$pluginConfig = [];

			if ($site_id) {
				$pluginConfig = \Vvveb\config("plugins.$site_id", []);
			}

			if (is_array($pluginConfig)) {
				$pluginList = array_replace_recursive($pluginList, $pluginConfig);
			}

			//set default name to show the plugin as broken if is missing
			array_walk($pluginList, function (&$val, $key) use (&$pluginList, $category) {
				if (! isset($val['name'])) {
					$val['slug'] = $key;
					$val['name'] = sprintf('[%s]', $key);
					$val['status'] = 'broken';
				}

				if ($category && (! isset($val['category']) || ($val['category'] != $category))) {
					unset($pluginList[$key]);
				}
			});

			if (! $category) {
				$cacheDriver->set('vvveb', $cacheKey, $pluginList);
				$cacheDriver->set('vvveb', "{$cacheKey}_categories", static :: $categories);
			}

			return $pluginList;
		}
	}

	static function getCategories($site_id = SITE_ID) {
		if (! static :: $categories) {
			$cacheDriver          = Cache :: getInstance();
			$cacheKey             = "plugins_list_{$site_id}_categories";
			static :: $categories = $cacheDriver->get('vvveb', $cacheKey);
		}

		return static :: $categories;
	}

	static function loadPlugins($site_id = SITE_ID) {
		if (static :: $loaded) {
			return;
		}
		static :: $loaded = true;

		$plugins = static::getList($site_id);

		foreach ($plugins as $name => $plugin) {
			if ((isset($plugin['status']) && $plugin['status'] == 'active')
				&& file_exists($plugin['file'])) {
				include $plugin['file'];
			}
		}
	}

	static function getMarketListOld($params = []) {
		$cacheDriver = Cache :: getInstance();

		$params['action'] = 'query_plugins';
		$query            = http_build_query($params);

		$cacheKey = md5($query);

		if ($result = $cacheDriver->get('vvveb', $cacheKey)) {
			return $result;
		} else {
			$ch = curl_init(static :: $feedUrl);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result, true);

			$cacheDriver->set($cacheKey, $result);

			return $result;
		}
	}
}
