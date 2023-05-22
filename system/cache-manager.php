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

namespace Vvveb\System;

class CacheManager {
	public static function clearFrontend() {
	}

	public static function clearDatabase() {
	}

	public static function clearCompiledFiles($app = false, $site_id = false, $theme = false, $module = false) {
		$name = DIR_COMPILED_TEMPLATES;

		if ($app) {
			$name .= "{$app}_";
		}

		if ($site_id) {
			$name .= "{$site}_id_";
		}

		if ($theme) {
			$name .= "{$theme}_";
		}

		if ($module) {
			$name .= $module;
		}

		$name .= '*';

		$files = glob($name);

		if ($files) {
			foreach ($files as $file) {
				if ($file[0] === '.') {
					continue;
				}

				if (! @unlink($file)) {
					clearstatcache(false, $file);
				}
			}
		}

		return true;
	}

	public static function clearObjectCache($namespace = '') {
		$cacheDriver = Cache::getInstance();

		return $cacheDriver->delete($namespace);
	}

	public static function clearPageCache($namespace = '') {
		$pageCache = PageCache::getInstance();
		$pageCache->purge($namespace);
	}

	public static function delete($namespace = '') {
		self :: clearObjectCache($namespace);
		//self :: clearCompiledFiles();
		self :: clearPageCache($namespace);

		return true;
	}
}
