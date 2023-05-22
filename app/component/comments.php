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

namespace Vvveb\Component;

use Vvveb\Sql\CommentSQL;
use Vvveb\System\Component\ComponentBase;
use Vvveb\System\Event;
use function Vvveb\url;

class Comments extends ComponentBase {
	public static $defaultOptions = [
		'post_id'       => 'url',
		'slug'          => 'url',
		'post_title'    => null, //include post title (for recent comments etc)
		'status'        => 1, //approved comments
		'start'         => 0,
		'limit'         => 10,
	];

	//called when fetching data, when cache expires
	function results() {
		$comments = new CommentSQL();
		$results  = $comments->getAll($this->options);

		if ($results && isset($results['comment'])) {
			foreach ($results['comment'] as $id => &$comment) {
				$url            = url('content/post/index', $comment);
				$comment['url'] =  $url . '#comment-' . $comment['comment_id'];
			}
		}

		list($results) = Event :: trigger(__CLASS__,__FUNCTION__, $results);

		return $results;
	}

	//called on each request
	function request($results) {
	}
}
