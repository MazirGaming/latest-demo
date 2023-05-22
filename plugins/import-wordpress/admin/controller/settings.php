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

namespace Vvveb\Plugins\ImportWordpress\Controller;

use \Vvveb\System\Import\Rss;
use \Vvveb\System\Import\Sql;
use \Vvveb\System\Import\Xml;
use function Vvveb\__;
use Vvveb\Controller\Base;

class Settings extends Base {
	private $cats = [];

	private $postTypes = ['post', 'page', 'attachment'];

	function processPost($posts) {
		//var_dump($posts);
	}

	function processPage($posts) {
	}

	function processAttachment($posts) {
	}

	function import($file) {
		$rss  = new Rss(file_get_contents($file));

		foreach ($this->postTypes as $postType) {
			$posts = $rss->get(1, 10, [['wp:post_type' => $postType]]);
			$fn    = 'process' . ucfirst($postType);
			$this->$fn($posts);
		}
	}

	function importFile($file, $name = '') {
		$result = false;

		if ($file) {
			try {
				// use temorary file, php cleans temporary files on request finish.
				$result = $this->import($file);
			} catch (\Exception $e) {
				$error                = $e->getMessage();
				$this->view->errors[] = $error;
			}
		}

		if ($result) {
			$successMessage          = sprintf(__('Import `%s` was successful!'), $name);
			$this->view->success[]   = $successMessage;
		} else {
			$errorMessage           = sprintf(__('Failed to import `%s` file!'), $name);
			$this->view->errors[]   = $errorMessage;
		}
	}

	function upload() {
		$files = $this->request->files;

		//check for uploaded files
		if ($files) {
			foreach ($files as $file) {
				$this->importFile($file['tmp_name'], $file['name']);
			}
		}

		//check if filename is given (from cli)
		$file = $this->request->post['file'] ?? false;

		if (is_array($file)) {
			foreach ($file as $f) {
				$this->importFile($f, basename($f));
			}
		} else {
			if ($file) {
				$this->importFile($file, basename($file));
			}
		}

		return $this->index();
	}

	function index() {
		//$this->xml = new Xml();
		//$this->sql = new Sql();

		//$this->import();
	}
}
