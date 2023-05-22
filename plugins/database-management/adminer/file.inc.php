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

if ($_GET['file'] == 'favicon.ico') {
	header('Content-Type: image/x-icon');
	echo lzw_decompress(compile_file('../adminer/static/favicon.ico', 'lzw_compress'));
} elseif ($_GET['file'] == 'default.css') {
	header('Content-Type: text/css; charset=utf-8');
	echo lzw_decompress(compile_file('../adminer/static/default.css;../externals/jush/jush.css', 'minify_css'));
} elseif ($_GET['file'] == 'functions.js') {
	header('Content-Type: text/javascript; charset=utf-8');
	echo lzw_decompress(compile_file('../adminer/static/functions.js;static/editing.js', 'minify_js'));
} elseif ($_GET['file'] == 'jush.js') {
	header('Content-Type: text/javascript; charset=utf-8');
	echo lzw_decompress(compile_file('../externals/jush/modules/jush.js;../externals/jush/modules/jush-textarea.js;../externals/jush/modules/jush-txt.js;../externals/jush/modules/jush-js.js;../externals/jush/modules/jush-sql.js;../externals/jush/modules/jush-pgsql.js;../externals/jush/modules/jush-sqlite.js;../externals/jush/modules/jush-mssql.js;../externals/jush/modules/jush-oracle.js;../externals/jush/modules/jush-simpledb.js', 'minify_js'));
} else {
	header('Content-Type: image/gif');

	switch ($_GET['file']) {
		case 'plus.gif': echo compile_file('../adminer/static/plus.gif');

break;

		case 'cross.gif': echo compile_file('../adminer/static/cross.gif');

break;

		case 'up.gif': echo compile_file('../adminer/static/up.gif');

break;

		case 'down.gif': echo compile_file('../adminer/static/down.gif');

break;

		case 'arrow.gif': echo compile_file('../adminer/static/arrow.gif');

break;
	}
}

exit;
