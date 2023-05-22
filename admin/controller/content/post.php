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

namespace Vvveb\Controller\Content;

use function Vvveb\__;
use Vvveb\Controller\Base;
use function Vvveb\humanReadable;
use function Vvveb\sanitizeHTML;
use Vvveb\Sql\categorySQL;
use Vvveb\Sql\PostSQL;
use Vvveb\System\CacheManager;
use Vvveb\System\Core\View;
use Vvveb\System\Images;
use Vvveb\System\Sites;
use Vvveb\System\Validator;

class Post extends Base {
	protected $type = 'post';

	private function taxonomies($post_id = false) {
		//get taxonomies for post type
		$taxonomies = new \Vvveb\Sql\taxonomySQL();
		$results    = $taxonomies->getTaxonomies(
			['post_type'    => $this->type]
		);

		//get taxonomies content
		if ($results) {
			$taxonomy_itemSql = new categorySQL();

			$options =  [
				'taxonomy'   => 'post',
				'start'      => 0,
				'limit'      => 100,
			] + $this->global;

			if ($post_id) {
				$options['post_id'] = $post_id;
			}

			foreach ($results as $id => &$taxonomy_item) {
				$taxonomy_item['taxonomy_item'] = [];
				//for tags don't retrive taxonomies if no post id provided
				if ($taxonomy_item['type'] != 'tags' || $post_id) {
					$options                        = ['taxonomy_id' => $id, 'type' => $taxonomy_item['type']] + $options;
					$taxonomy_item['taxonomy_item'] = $taxonomy_itemSql->getCategories($options);
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
			'search'      => '%' . $this->request->get['text'] . '%',
		]
		);

		foreach ($results['categories'] as $category) {
			$search[$category['taxonomy_item_id']] = $category['name'];
		}

		$view         = $this->view;
		$view->noJson = true;

		//echo json_encode($search);
		$this->response->setType('json');
		$this->response->output($search);

		return false;
	}

	function getThemeFolder() {
		return DIR_THEMES . DS . Sites::getTheme() ?? 'default';
	}

	function index() {
		$view = $this->view;

		$admin_path          = \Vvveb\adminPath();
		$postOptions         = [];
		$post                = [];
		$post_id             = $this->request->get['post_id'] ?? $this->request->post['post_id'] ?? false;

		$controllerPath        = $admin_path . 'index.php?module=media/media';
		$view->scanUrl         = "$controllerPath&action=scan";
		$view->uploadUrl       = "$controllerPath&action=upload";
		$theme                 = Sites::getTheme() ?? 'default';
		$view->themeCss        = PUBLIC_PATH . "themes/$theme/css/admin-post-editor.css";

		if ($post_id) {
			$postOptions['post_id'] = (int)$post_id;
		} else {
			if (isset($this->request->get['slug'])) {
				$postOptions['slug'] = $this->request->get['slug'];
			}
		}

		if (isset($this->request->get['type'])) {
			$this->type = $this->request->get['type'];
		}

		if ($postOptions) {
			$posts = new PostSQL();

			//get all languages
			unset($postOptions['language_id']);
			$postOptions['type'] = $this->type;
			$post                = $posts->get($postOptions);

			if (! $post) {
				$message = sprintf(__('%s not found!'), humanReadable(__($this->type)));
				$this->notFound(false, 404, ['message' => $message, 'title' => $message]);
			}

			//featured image
			if (isset($post['image'])) {
				$post['image_url'] = Images::image($post['image'], 'post');
			}
			//$view->tags = $posts->postTags($options);
			//$view->categories = $posts->postCategories($options);
		} else {
			$post['image_url'] = Images::image('','post');
		}

		if (isset($post['date_modified'])) {
			$post['date_modified'] = str_replace(' ', 'T', $post['date_modified']);
		} else {
			$post['date_modified'] = date("Y-m-d\TH:i:s", isset($post['date_modified']) && $post['date_modified'] ? strtotime($post['date_modified']) : time());
		}

		if (isset($post['post_content'][1]['slug'])) {
			//$post['url'] = \Vvveb\url("content/{$this->type}/index", ['slug'=> $post['post_content'][1]['slug']]);
			$post['url'] = \Vvveb\url("content/{$post['type']}/index", $post);
		}

		$this->type = $post['type'] ?? $this->type;
		$type_name  = humanReadable(__($this->type));

		$defaultTemplate = \Vvveb\getCurrentTemplate();
		$template        = isset($post['template']) && $post['template'] ? $post['template'] : $defaultTemplate;
		$themeFolder     = $this->getThemeFolder();

		if (isset($post['url'])) {
			$design_url         = $admin_path . \Vvveb\url(['module' => 'editor/editor', 'url' => $post['url'], 'template' => $template], false, false);
			$post['design_url'] = $design_url;
		}

		if (! file_exists($themeFolder . DS . $template)) {
			if ($template == $defaultTemplate) {
				$view->template_missing = sprintf(__('Template missing, choose existing template or <a href="%s" target="_blank">create global template</a> for %s.'), $design_url, $type_name);
			} else {
				$view->template_missing = sprintf(__('Template missing, <a href="%s" target="_blank">create template</a> for this  %s.'), $design_url , $type_name);
			}
		}

		if ($this->type != 'page') {
			$view->taxonomies = $this->taxonomies($post['post_id'] ?? false);
		}

		$view->post                = $post;
		$view->status              = ['publish' => 'Publish', 'draft' => 'Draft', 'pending' => 'Pending', 'private' => 'Private', 'password' => 'Password'];
		$view->templates           = \Vvveb\getTemplateList(false, ['email']);
		$validator                 = new Validator(['post']);
		$view->validatorJson       = $validator->getJSON();
		$view->type                = __($this->type);
		$view->type_name           = $type_name;
		$view->posts_list_url      =  \Vvveb\url(['module' => 'content/posts', 'type' => $this->type]);
	}

	function save() {
		$validator = new Validator(['post']);
		$view      = view :: getInstance();
		$post_id   = $this->request->get['post_id'] ?? $this->request->post['post_id'] ?? false;

		if (isset($this->request->get['type'])) {
			$this->type          = $this->request->get['type'];
		}

		if (($errors = $validator->validate($this->request->post)) === true) {
			$posts = new PostSQL();

			$post = [];
			//process tags
			if (isset($post['post']['tag'])) {
				foreach ($post['post']['tag'] as $tag) {
					//existing tag add to post taxonomy_item list
					if (is_numeric($tag)) {
						$post['post']['taxonomy_item'][] = $tag;
					} else {
						//add new taxonomy_item
					}
				}
			}

			$post['post']                  = $this->request->post;
			$post['post']['date_modified'] =  str_replace('T', ' ', $post['post']['date_modified']);

			foreach ($post['post']['post_content'] as &$desc) {
				$desc['content'] = sanitizeHTML($desc['content']);
			}

			$post['post'] = $this->global + $post['post'];

			if ($post_id) {
				$post['post_id']                     = (int)$post_id;
				$result                              = $posts->edit($this->global);

				if ($result >= 0) {
					$this->view->success[] = ucfirst($this->type) . ' ' . __('saved') . '!';
					//CacheManager::delete('post');
					CacheManager::delete();
				} else {
					$this->view->errors = [$posts->error];
				}
			} else {
				$post['post']['type'] = $this->type;
				$return               = $posts->add($this->global + ['post' =>  $post['post']]);
				$id                   = $return['post'];

				if (! $id) {
					$view->errors = [$posts->error];
				} else {
					CacheManager::delete('post');
					$message         = ucfirst($this->type) . ' ' . __('saved') . '!';
					$view->success[] = $message;
					$this->redirect(['module'=>'content/post', 'post_id' => $id, 'type' => $this->type, 'success' => $message]);
				}
			}
		} else {
			$view->errors = $errors;
		}

		$this->index();
	}
}
