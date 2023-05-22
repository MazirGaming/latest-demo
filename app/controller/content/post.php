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

class Post extends Base {
	public $type = 'post';

	function addComment() {
		if (isset($this->request->post['email'])) {
			$comments  = new \Vvveb\Sql\CommentSQL();
			$result    = $comments->add(['comment' => $this->request->post + ['date' => date('Y-m-d H:i:s')]]);

			$this->response->setType('json');
			$this->response->output($result);

			return false;
		}

		return false;
	}

	function index() {
		//check if post component is loaded for the page,
		//if not then post does not exist or post component is not added/configured on the page
		/*
		$post = $this->view->post[0] ?? [];
		$page = Components::get('page');
		if (!$post) {
			//$this->notFound();
		}*/

		if ($this->request->get['slug']) {
			$postSql = new \Vvveb\Sql\PostSQL();
			$options = $this->global + ['slug' => $this->request->get['slug'], 'type' => $this->type];
			$post    = $postSql->get($options);

			if ($post) {
				$this->request->get['post_id'] = $post['post_id'];
			} else {
				$message = __('Post not found!');
				$this->notFound(true, 404, ['message' => $message, 'title' => $message]);
			}
		}
	}
}
