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

namespace Vvveb\Controller;

use Vvveb\System\Sites;

#[\AllowDynamicProperties]
class Index extends Base {
	/**
	 * Homepage.
	 *
	 */
	function index() {
		//die();
		/*
		 $ifs = ['category.children > 0','category.children > 0 && posts_count > 0','category.children > 0 && posts_count > 0   ||   gigi > 0'];
		 foreach ($ifs as $if) {
			 echo vtplIfCondition(null, null, $if) . "<br><br>\n\n";
		 }
		
		
		die();*/
		//check if homepage has a different template than default
		$site = Sites :: getSiteData();

		if (isset($site['template']) && $site['template']) {
			return $site['template'];
		}
	}
}
