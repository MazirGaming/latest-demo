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

namespace Vvveb\Controller\Editor;

use function Vvveb\__;
use Vvveb\Controller\Base;
use function Vvveb\sanitizeFileName;
use Vvveb\Sql\PostSQL;
use Vvveb\Sql\ProductSQL;
use Vvveb\System\CacheManager;
use Vvveb\System\Core\View;
use Vvveb\System\Event;
use Vvveb\System\Sites;

class Editor extends Base {
	private $themeConfig = [];

	private $skipFolders = ['src', 'backup', 'import'];

	private $skipFiles = [];

	function __construct() {
		$this->loadThemeConfig();
	}

	function getThemeFolder() {
		return DIR_THEMES . DS . Sites::getTheme() ?? 'default';
	}

	function loadThemeConfig() {
		$config = $this->getThemeFolder() . DS . 'theme.php';

		if (file_exists($config)) {
			$this->themeConfig = include $config;
		} else {
			$this->themeConfig = [];
		}
	}

	function loadTemplateList() {
		$list = $this->themeConfig['pages'] ?? [];

		$pages       = $list + \Vvveb\getTemplateList();
		list($pages) = Event::trigger(__CLASS__, __FUNCTION__, $pages);

		return $pages;
	}

	function index() {
		$view               = View::getInstance();
		$view->pages        = $this->loadTemplateList();
		$view->themeBaseUrl = PUBLIC_PATH . 'themes/' . (Sites::getTheme() ?? 'default') . '/';
		$themeFolder        = $this->getThemeFolder();
		$files              = [];

		$glob = glob("$themeFolder/components/*.js", GLOB_BRACE);

		foreach ($glob as &$file) {
			$files[] = str_replace($themeFolder, $view->themeBaseUrl, $file);
		}
		$view->themeComponents = $files;

		$glob = glob("$themeFolder/sections/*.js", GLOB_BRACE);

		foreach ($glob as $file) {
			$files[] = str_replace($themeFolder, $view->themeBaseUrl, $file);
		}
		$view->themeSections = $files;

		$glob = glob("$themeFolder/inputs/*.js", GLOB_BRACE);

		foreach ($glob as &$file) {
			$files[] = str_replace($themeFolder, $view->themeBaseUrl, $file);
		}

		if (isset($this->request->get['url'])) {
			$name     = $url      = $this->request->get['url'];
			$template = $this->request->get['template'] ?? \Vvveb\getUrlTemplate($url);
			$filename = $template;
			$file     = $template;
			$title    = \Vvveb\humanReadable($url);

			if ($url == '/') {
				$title = __('Homepage');
				$name  = 'homepage-live';
			}

			$current_page = ['name' => $name, 'file' => $file, 'url' => $url, 'title' => $title, 'folder' => '', 'className' => 'page'];
			$view->pages  = [$name => $current_page] + $view->pages;
		}

		$view->themeInputs        = $files;
		$admin_path               = \Vvveb\adminPath();
		$mediaControllerPath      = $admin_path . 'index.php?module=media/media';
		$controllerPath           = $admin_path . 'index.php?module=editor/editor';

		$this->view->scanUrl     = "$mediaControllerPath&action=scan";
		$this->view->uploadUrl   = "$mediaControllerPath&action=upload";
		$this->view->saveUrl     = "$controllerPath&action=save";
		$this->view->deleteUrl   = "$controllerPath&action=delete";
		$this->view->renameUrl   = "$controllerPath&action=rename";
		$view->templates         = \Vvveb\getTemplateList();
		$view->folders           = \Vvveb\getThemeFolderList();
	}

	function getComponent($html, $options) {
	}

	function backup($page) {
		$themeFolder  = $this->getThemeFolder() . DS;
		$backupFolder = $themeFolder . 'backup' . DS;
		$page         = str_replace('.html', '', sanitizeFileName($page));
		$backupName   =  str_replace(DS, '-', $page) . '|' . date('Y-m-d_H:i:s') . '.html';
		$file 		      = $themeFolder . $page . '.html';

		if (is_dir($backupFolder)) {
			if (file_exists($file)) {
				$content = file_get_contents($themeFolder . $page . '.html');

				return file_put_contents($backupFolder . $backupName, $content);
			}
		}

		return false;
	}

	function saveElements($elements) {
		$products   = new ProductSQL();
		$posts      = new PostSQL();
		$components = [];

		foreach ($elements as $element) {
			$component = $element['component'];
			$type      = $element['type'];
			$id        = $element['id'];
			$fields    = $element['fields'];

			//todo: check and load components from plugins
			include_once DIR_ROOT . "app/component/$component.php";
			$componentName =  "\Vvveb\Component\\$component";

			if (! isset($components[$component])) {
				$components[$component] = new $componentName();
			}
			$components[$component]->editorSave($id, $fields, $type);
		}
		/*
		switch ($type) {
			case 'product':
				$product_content = [];

				foreach ($fields as $field) {
					$name  = $field['name'];
					$value = $field['value'];

					if ($name == 'name' || $name == 'content') {
						$product_content[$name] = $value;
					} else {
						$product[$name] = $value;
					}
				}

				//$product_content['product_id'] = $id;
				$product_content['language_id'] = 1;

				$product['product_content'][]     = $product_content;
				$result                           = $products->edit(['product' => $product, 'product_id' => $id]);

			break;

			case 'post':
				$post_content = [];

				foreach ($fields as $field) {
					$name  = $field['name'];
					$value = $field['value'];

					if ($name == 'name' || $name == 'content') {
						$post_content[$name] = $value;
					} else {
						$post[$name] = $value;
					}
				}
				//$post['post_content']['post_id'] = $id;
				$post_content['language_id'] = 1;
				$post['post_content'][]      = $post_content;

				$result = $posts->edit(['post' => $post, 'post_id' => $id]);

			break;
		} */

		return true;
	}

	function delete() {
		$file        = sanitizeFileName($this->request->post['file']);
		$themeFolder = $this->getThemeFolder();
		//echo $themeFolder . DS . $file;
		header('Content-type: application/json; charset=utf-8');

		if (unlink($themeFolder . DS . $file)) {
			$message = ['success' => true, 'message' => __('File deleted!')];
		} else {
			$message = ['success' => false, 'message' => __('Error deleting file!')];
		}

		echo json_encode($message);

		die();
	}

	function rename() {
		$file        = sanitizeFileName($this->request->post['file']);
		$newfile     = sanitizeFileName($this->request->post['newfile']);
		$duplicate   =  $this->request->post['duplicate'] ?? false;
		$themeFolder = $this->getThemeFolder();

		header('Content-type: application/json; charset=utf-8');
		$currentFile = $themeFolder . DS . $file;
		$targetFile  =  $themeFolder . DS . $newfile;

		if ($duplicate) {
			if (copy($currentFile, $targetFile)) {
				$message = ['success' => true, 'message' => __('File copied!')];
			} else {
				$message = ['success' => false, 'message' => __('Error copying file!')];
			}
		} else {
			if (rename($currentFile, $targetFile)) {
				$message = ['success' => true, 'message' => __('File renamed!')];
			} else {
				$message = ['success' => false, 'message' => __('Error renaming file!')];
			}
		}

		echo json_encode($message);

		die();
	}

	function save() {
		$page                 = sanitizeFileName($_POST['file']);
		$content              =$this->request->post['html'] ?? false;
		$startTemplateUrl     = $this->request->post['startTemplateUrl'] ?? false;
		$elements             = $this->request->post['elements'] ?? false;
		$setTemplate          = $this->request->post['setTemplate'] ?? false;

		header('Content-type: application/json; charset=utf-8');
		$view         = View::getInstance();
		$view->noJson = true;
		$success      = false;
		$text      		 = '';

		$themeFolder = $this->getThemeFolder();

		if ($startTemplateUrl && ! $content) {
			$baseUrl = '/themes/' . (Sites::getTheme() ?? 'default') . '/';
			$content = file_get_contents($themeFolder . DS . $startTemplateUrl);
			$content = preg_replace('@<base href[^>]+>@', '<base href="' . $baseUrl . '">', $content);
		}

		if ($elements) {
			if ($this->saveElements($elements)) {
				$success   = true;
				$text      = __('Elements saved!') . "\n";
			} else {
				$success   = false;
				$text      = __('Error saving elements!') . "\n";
			}
		}

		if (! $startTemplateUrl) {
			if ($this->backup($page)) {
			} else {
				$success   = false;
				$text .= __('Error saving backup!') . "\n";
			}
		}

		if (file_put_contents($themeFolder . DS . $page, $content)) {
			$success = true;
			$text .= __('File saved!');
		}

		if (CacheManager::clearCompiledFiles('app') && CacheManager::delete()) {
		}

		$message   = ['success' => $success, 'message' => $text];
		echo json_encode($message);

		die();

		return false;
	}
}
