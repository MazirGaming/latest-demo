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

namespace Vvveb\Controller\Product;

use function Vvveb\__;
use Vvveb\Controller\Base;
use function Vvveb\humanReadable;
use function Vvveb\sanitizeHTML;
use Vvveb\Sql\CategorySQL;
use Vvveb\Sql\ProductSQL;
use Vvveb\System\CacheManager;
use Vvveb\System\Core\View;
use Vvveb\System\Images;
use Vvveb\System\Sites;
use Vvveb\System\Validator;

class Product extends Base {
	protected $type = 'product';

	private function taxonomies($product_id = false) {
		//get taxonomies for product type
		$taxonomies = new \Vvveb\Sql\taxonomySQL();
		$results    = $taxonomies->getTaxonomies(
			['product_type'    => $this->type]
		);

		//get taxonomies content
		if ($results) {
			$taxonomy_itemSql = new CategorySQL();

			$options =  [
				'taxonomy'   => 'product',
				'start'      => 0,
				'limit'      => 100,
			] + $this->global;

			if ($product_id) {
				$options['product_id'] = $product_id;
			}

			foreach ($results as $id => &$taxonomy_item) {
				$taxonomy_item['taxonomy_item'] = [];
				//for tags don't retrive taxonomies if no product id provided
				if ($taxonomy_item['type'] != 'tags' || $product_id) {
					$taxonomy_item['taxonomy_item'] = $taxonomy_itemSql->getCategories($options + ['taxonomy_id' => $id, 'type' => $taxonomy_item['type']]);
				}
			}
		}

		return $results;
	}

	function categoriesAutocomplete() {
		$categories = new \Vvveb\Sql\CategorySQL();

		$results = $categories->getCategories([
			'start'       => 0,
			'limit'       => 10,
			'language_id' => 1,
			'site_id'     => 1,
			'search'      => '%' . trim($this->request->get['text']) . '%',
		]
		);

		foreach ($results['categories'] as $category) {
			$search[$category['taxonomy_item_id']] = $category['name'];
		}

		$view         = $this->view;
		$view->noJson = true;

		$this->response->setType('json');
		$this->response->output($search);

		return false;
	}

	function manufacturersAutocomplete() {
		$manufacturers = new \Vvveb\Sql\ManufacturerSQL();

		$options = [
			'start'       => 0,
			'limit'       => 10,
			'search'      => '%' . trim($this->request->get['text']) . '%',
		] + $this->global;

		$results = $manufacturers->getAll($options);

		$search = [];

		foreach ($results['manufacturer'] as $manufacturer) {
			$manufacturer['image']                    = Images::image($manufacturer['image'], 'manufacturer');
			$search[$manufacturer['manufacturer_id']] = '<img width="32" height="32" src="' . $manufacturer['image'] . '"> ' . $manufacturer['name'];
		}

		//echo json_encode($search);
		$this->response->setType('json');
		$this->response->output($search);

		return false;
	}

	function vendorsAutocomplete() {
		$vendors = new \Vvveb\Sql\VendorSQL();

		$options = [
			'start'       => 0,
			'limit'       => 10,
			'search'      => '%' . trim($this->request->get['text']) . '%',
		] + $this->global;

		$results = $vendors->getAll($options);

		$search = [];

		foreach ($results['vendor'] as $vendor) {
			$vendor['image']               = Images::image($vendor['image'], 'vendor');
			$search[$vendor['vendor_id']]  = '<img width="32" height="32" src="' . $vendor['image'] . '"> ' . $vendor['name'];
		}

		//echo json_encode($search);
		$this->response->setType('json');
		$this->response->output($search);

		return false;
	}

	function productsAutocomplete() {
		$products = new \Vvveb\Sql\ProductSQL();

		$options = [
			'start'       => 0,
			'limit'       => 10,
			'search'      => trim($this->request->get['text']),
		] + $this->global;

		$results = $products->getAll($options);

		$search = [];

		foreach ($results['products'] as $product) {
			$product['image']               = Images::image($product['image'], 'product');
			$search[$product['product_id']] = '<img width="32" height="32" src="' . $product['image'] . '"> ' . $product['name'];
		}

		//echo json_encode($search);
		$this->response->setType('json');
		$this->response->output($search);

		return false;
	}

	function getThemeFolder() {
		return DIR_THEMES . DS . Sites::getTheme() ?? 'default';
	}

	function productImages() {
	}

	function index() {
		$view = $this->view;

		/* Media modal configuration */
		$admin_path      = \Vvveb\adminPath();
		$controllerPath  = $admin_path . 'index.php?module=media/media';
		$view->scanUrl   = "$controllerPath&action=scan";
		$view->uploadUrl = "$controllerPath&action=upload";
		$theme           = Sites::getTheme() ?? 'default';
		$view->themeCss  = PUBLIC_PATH . "themes/$theme/css/admin-post-editor.css";

		$productOptions   = [];
		$product_id       = $this->request->get['product_id'] ?? $this->request->product['product_id'] ?? false;
		$products         = new ProductSQL();

		if ($product_id) {
			$productOptions['product_id'] = (int)$product_id;
		} else {
			if (isset($this->request->get['slug'])) {
				$productOptions['slug'] = $this->request->get['slug'];
			}
		}

		if ($productOptions) {
			$product = $products->get($productOptions + $this->global);

			if (! $product) {
				$message = sprintf(__('%s not found!'), humanReadable(__($this->type)));
				$this->notFound(false, 404, ['message' => $message, 'title' => $message]);
			}

			//featured image
			if (isset($product['image'])) {
				$product['image_url'] = Images::image($product['image'], 'product');
			}

			//gallery
			if (isset($product['images'])) {
				$product['images'] = Images::images($product['images'], 'product');
			}

			//$productImages = $products->getImages($productOptions);
			$view->data['status'] = [0 => __('Disabled'), 1 => __('Enabled')];
		} else {
			$product['image_url'] = Images::image('', 'product');
		}

		if (isset($product['date_modified'])) {
			$product['date_modified'] = str_replace(' ', 'T', $product['date_modified']);
		} else {
			$product['date_modified'] = date("Y-m-d\TH:i:s", isset($product['date_modified']) && $product['date_modified'] ? strtotime($product['date_modified']) : time());
		}

		if (isset($product['product_content'][1]['slug'])) {
			//$product['url'] = \Vvveb\url("content/{$this->type}/index", ['slug'=> $product['product_content'][1]['slug']]);
			$product['url'] = \Vvveb\url("content/{$product['type']}/index", $product);
		}

		$this->type = $product['type'] ?? $this->type;
		$type_name  = humanReadable(__($this->type));

		$defaultTemplate = \Vvveb\getCurrentTemplate();
		$template        = isset($product['template']) && $product['template'] ? $product['template'] : $defaultTemplate;
		$themeFolder     = $this->getThemeFolder();

		if (isset($product['url'])) {
			$design_url            = $admin_path . \Vvveb\url(['module' => 'editor/editor', 'url' => $product['url'], 'template' => $template], false, false);
			$product['design_url'] = $design_url;
		}

		if (! file_exists($themeFolder . DS . $template)) {
			if ($template == $defaultTemplate) {
				$view->template_missing = sprintf(__('Template missing, choose existing template or <a href="%s" target="_blank">create global template</a> for %s.'), $design_url, $type_name);
			} else {
				$view->template_missing = sprintf(__('Template missing, <a href="%s" target="_blank">create template</a> for this  %s.'), $design_url , $type_name);
			}
		}

		$data     		      = $products->getData($product);

		$data['subtract'] = [1 => __('Yes'), 0 => __('No')]; //Subtract stock options
		$view->set($data);

		$product['url']        = isset($product['product_content'][1]['slug']) ? \Vvveb\url('product/product/index', ['slug'=> $product['product_content'][1]['slug']]) : '';
		$defaultTemplate       = \Vvveb\getCurrentTemplate();
		$template              = (isset($product['template']) && $product['template']) ? $product['template'] : $defaultTemplate;
		$product['design_url'] = $admin_path . \Vvveb\url(['module' => 'editor/editor', 'template' => $template, 'url' => $product['url']], false, false);

		$view->product             = $product;
		$view->taxonomies          = $this->taxonomies($product['product_id'] ?? false);
		$view->status              = ['publish', 'draft', 'pending', 'private', 'password'];
		$view->templates           = \Vvveb\getTemplateList(false, ['email']);
		$validator                 = new Validator(['product']);
		$view->validatorJson       = $validator->getJSON();
	}

	function save() {
		$validator = new Validator(['product']);

		$product    = $this->request->post;
		$product_id = $this->request->get['product_id'] ?? $this->request->get->post['product_id'] ?? false;

		foreach ($product['product_content'] as &$desc) {
			$desc['content'] = sanitizeHTML($desc['content']);
		}

		//if (($this->view->errors = $validator->validate($product)) === true)
		{
			$products = new ProductSQL();
			//var_dump($product['product_image']);
			$publicPath               = \Vvveb\publicMediaUrlPath() . 'media/';
			$product['product_image'] = $product['product_image'] ?? [];

			foreach ($product['product_image'] as &$image) {
				$image = str_replace($publicPath,'', $image);
			}

			$product = $product + $this->global;
			$products->productImage(['product_id' => $product_id, 'product_image' => $product['product_image']]);

			if ($product_id) {
				$productId = (int)$product_id;

				$data   = ['product' => $product, 'product_id' => $productId, 'site_id' => $this->global['site_id']];
				$result = $products->edit($data);

				if ($result >= 0) {
					//CacheManager::delete('product');
					CacheManager::delete();
					$this->view->success = ['Product saved!'];
				} else {
					$this->view->errors = [$products->error];
				}
			} else {
				$data   = ['product' => $product, 'site_id' => $this->global['site_id']];
				$result = $products->add($data);

				if (! $result['product']) {
					$this->view->errors = [$products->error];
				} else {
					$product_id = $result['product'];
					$products->productImage(['product_id' => $product_id, 'product_image' => $product['product_image']]);

					CacheManager::delete('product');
					$successMessage        = __('Product saved!');
					$this->view->success[] = $successMessage;
					$this->redirect(['module' => 'product/product', 'product_id' => $product_id, 'success' => $successMessage]);
				}
			}
		}

		$this->index();
	}

	function draft() {
	}

	function preview() {
	}
}
