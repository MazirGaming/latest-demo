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

class Cache {
	private $driver;

	private $expire;

	public static function getInstance() {
		static $inst = null;

		if ($inst === null) {
			$driver = \Vvveb\config(APP . '.cache.driver', 'file');
			$inst   = new Cache($driver);
		}

		return $inst;
	}

	public function __construct($driver, $expire = 3600) {
		$class = '\\Vvveb\\System\\Cache\\' . $driver;

		$this->expire = $expire;

		if (class_exists($class)) {
			$options      = \Vvveb\config(APP . '.cache', []);
			$this->driver = new $class($options);
		} else {
			throw new \Exception("Error: Could not load cache driver '$driver'!");
		}

		return $this->driver;
	}

	public function get($namespace, $key) {
		return $this->driver->get($namespace, $key);
	}

	public function set($namespace, $key, $value, $expiration = 0) {
		$expiration = $expiration ?? $this->expiration;

		return $this->driver->set($namespace, $key, $value, $expiration);
	}

	// cache the results of the callback retrive if exists or save if expired
	public function cache($namespace, $key, $callback, $expiration = 0) {
		if ($value = $this->driver->get($namespace, $key)) {
			return $value;
		}
		$value = $callback();

		$expiration = $expiration ?? $this->expiration;

		if ($this->driver->set($namespace, $key, $value, $expiration)) {
		}

		return $value;
	}

	public function getMulti($namespace, $key, $serverKey = false) {
		return $this->driver->getMulti($namespace, $key, $serverKey);
	}

	public function setMulti($namespace, $items, $expiration = 0, $serverKey = false) {
		$expiration = $expiration ? $expiration : $this->expiration;

		return $this->driver->setMulti($namespace, $items, $expiration, $serverKey);
	}

	public function delete($namespace, $key = false) {
		return $this->driver->delete($namespace, $key);
	}

	public function purge($namespace = '') {
		return $this->driver->delete($namespace);
	}
}
