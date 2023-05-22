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
use function Vvveb\__;
use function Vvveb\download;
use Vvveb\System\Event;
use Vvveb\System\Functions\Str;
use Vvveb\System\Import\Rss;

abstract class Extensions {
	static protected $extensions = [];

	static protected $categories = [];

	static protected $extension = 'extension';

	static protected $baseDir = 'extension';

	const KEY_VALUE_REGEX = '/^([\w ]+):\s+(.+)$/m';

	static function getUrl($url) {
		$cacheDriver = Cache :: getInstance();
		$cacheKey    = md5($url);

		if ($result = $cacheDriver->get('vvveb', $cacheKey)) {
			return $result;
		} else {
			//try with file get contents
			$context_options = [];
			$context         = stream_context_create($context_options);
			$result          = file_get_contents($url, false, $context);

			if ($result) {
				$cacheDriver->set('vvveb', $cacheKey, $result);

				return $result;
			} else {
				//try with curl
				$ch = curl_init($url);

				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);

				if ($result) {
					$cacheDriver->set('vvveb', $cacheKey, $result);

					return $result;
				} else {
					throw new \ErrorException('Curl error: ' . curl_errno($ch) . ' - ' . curl_error($ch));

					return [];
				}
			}
		}
	}

	static function unzip($file) {
		// get the absolute path to $file
		$path = pathinfo(realpath($file), PATHINFO_DIRNAME);

		$zip = new \ZipArchive();
		$res = $zip->open($file);

		if ($res === true) {
			$zip->extractTo($path);
			$zip->close();

			return true;
		}

		return false;
	}

	static function getParams($comments) {
		$results = [];

		if (preg_match_all(static :: KEY_VALUE_REGEX, $comments, $matches)) {
			$matches[1] = array_map(function ($key) {
				return str_replace(' ','-',strtolower($key));
			}, $matches[1]);

			$results = array_combine($matches[1], $matches[2]);
		}

		return $results;
	}

	static function getComments($content) {
		//$content = file_get_contents($file);
		$docComments = [];

		foreach (token_get_all($content) as $entry) {
			if ($entry[0] == T_DOC_COMMENT || $entry[0] == T_COMMENT) {
				$docComments[] = $entry[1];
			}
		}

		return implode("\n", $docComments);
	}

	static function getInfo($content, $name = false) {
		$comments = static :: getComments($content);
		$params   = static ::  getParams($comments);

		if (isset($params['status'])) {
			unset($params['status']);
		}

		if (isset($params['category']) && $name) {
			static :: $categories[$params['category']][] = $name;
		}

		return $params;
	}

	static function getListInfo($path) {
		if (isset(static :: $extensions[static :: $extension])) {
			return static :: $extensions[static :: $extension];
		} else {
			static :: $extensions[static :: $extension] = [];
		}

		$list    = glob($path);

		foreach ($list as $file) {
			$content                                             = file_get_contents($file);
			$dir                                                 = Str::match('@(.+)/[a-z]+.\w+$@', $file);
			$folder                                              = Str::match('@/([^/]+)/[a-z]+.\w+$@', $file);
			$info                                                = static::getInfo($content, $folder);
			$info['file']                                        = $file;
			$info['folder']                                      = $folder;
			$info['import']                                      = file_exists($dir . DS . 'import');
			static :: $extensions[static :: $extension][$folder] = $info;
		}

		return static :: $extensions[static :: $extension];
	}

	static function getCategories() {
		return static :: $categories;
	}

	static function download($url) {
		//$temp = tmpfile();
		$f    = false;
		$temp = tempnam(sys_get_temp_dir(), 'vvveb_plugin');

		if ($content = download($url)) {
			$f  = file_put_contents($temp, $content, LOCK_EX);

			return $temp;
		}

		return $f;
	}

	static function install($extensionZipFile, $validate = true) {
		$extension   = static :: $extension;
		$success     = true;
		$fileCheck   = "$extension.php";

		$zip = new \ZipArchive();

		if ($zip->open($extensionZipFile) === true) {
			$info       = false;
			$folderName = $zip->getNameIndex(0);
			//check if first entry is a directory
			if (substr($folderName, -1, 1) != DS) {
				throw new \Exception(sprintf('%s zip must have only %s folder!', $extension));
			}

			for ($i = 0; ($i < $zip->numFiles && $success == true); $i++) {
				$file = $zip->getNameIndex($i);

				//check if all files inside the extension folder
				if ($validate && (strpos($file, $folderName) === false)) {
					throw new \Exception(sprintf(__('%s zip should not have other files than %s folder!'), $extension, $extension));
				}

				if (strpos($file, $fileCheck) !== false) {
					$content = $zip->getFromName($file);
					$info    = static::getInfo($content);

					if ($folderName == ($info['slug'] . '/')) {
						// Unzip Path
						if ($zip->extractTo(static :: $baseDir)) {
							$success = $info['slug'];
						} else {
							$success = false;
						}
					} else {
						throw new \Exception(sprintf(__('%s slug `%s` does not match folder %s!'), $extension, $info['slug'], $folderName));
					}

					break;
				}
			}

			$zip->close();

			if (! $info) {
				throw new \Exception(sprintf(__('No `%s.php` info found inside zip!', $extension)));
			}
		} else {
			throw new \Exception(__('File is not a valid zip archive!'));
		}

		//copy public folder to public/extensions/$extensionName
		Event :: trigger(__CLASS__, __FUNCTION__, $extensionZipFile, $success);

		return $success;
	}

	static function marketUrl() {
		return static :: $url;
	}

	static function getMarketList($params = []) {
		$query            = http_build_query($params);
		$content          = static :: getUrl(static :: $feedUrl . '?' . $query);

		if ($content) {
			$rss  = new Rss($content);

			$result[static :: $extension . 's'] = $rss->get(1, 10);
			$result['count']                    = $rss->value('count');

			return $result;
		}

		return [];
	}
}
