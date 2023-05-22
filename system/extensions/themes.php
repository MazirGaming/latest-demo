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

use \Vvveb\System\Sites;
use Vvveb\System\Functions\Str;

class Themes extends Extensions {
	static protected $extension = 'theme';

	static protected $baseDir = DIR_THEMES;

	//static protected $url = 'http://themes.vvveb.com/api/themes.json';
	static protected $url = 'https://themes.vvveb.com';

	static protected $feedUrl = 'https://themes.vvveb.com/feed/themes';

	static protected $themes = [];

	static protected $categories = [];

	static function getInfo($content, $name = false) {
		$params               = parent::getInfo($content, $name);
		$params['screenshot'] = PUBLIC_PATH . 'themes/' . $name . '/screenshot.png';

		return $params;
	}

	static function getList($path = '') {
		$activeTheme = Sites::getTheme() ?? 'default';
		$list        = glob(DIR_ROOT . '/public/themes/*/index.html');

		foreach ($list as $file) {
			$folder      = Str::match('@/([^/]+)/[a-z]+.\w+$@', $file);
			$dir         = Str::match('@(.+)/[a-z]+.\w+$@', $file);
			$themeConfig = $dir . DS . 'theme.php';

			$theme           = [];
			$theme['file']   = $file;
			$theme['folder'] = $folder;
			$theme['import'] = false;
			$theme['author'] = 'n/a';

			if (file_exists($themeConfig)) {
				$content         = file_get_contents($themeConfig);
				$theme           = static::getInfo($content, $folder) + $theme;
				$theme['import'] = file_exists($dir . DS . 'import');
			}

			$theme['name']       = $theme['name'] ?? ucfirst($theme['folder']);
			$theme['screenshot'] = $theme['screenshot'] ?? '/../media/placeholder.svg';

			$themes[$folder] = $theme;

			if ($theme['active'] = ($activeTheme == $theme['folder'])) {
				unset($themes[$activeTheme]);
				$themes = [$activeTheme => $theme] + $themes;
			}
		}

		static :: $extensions[static :: $extension] = $themes;

		return $themes;
	}

	static function getMarketListOld($params = []) {
		$cacheDriver = Cache :: getInstance();

		$params['action'] = 'query_themes';

		$query = http_build_query($params);

		$cacheKey = md5($query);

		if ($result = $cacheDriver->get($cacheKey)) {
			return $result;
		} else {
			$ch = curl_init(self :: $feedUrl);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($result, true);

			$cacheDriver->set('vvveb', $cacheKey, $result);

			return $result;
		}
	}
}
