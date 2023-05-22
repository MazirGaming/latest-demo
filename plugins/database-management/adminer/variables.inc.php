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

$status = isset($_GET['status']);
page_header($status ? lang('Status') : lang('Variables'));

$variables = ($status ? show_status() : show_variables());

if (! $variables) {
	echo "<p class='message'>" . lang('No rows.') . "\n";
} else {
	echo "<table cellspacing='0'>\n";

	foreach ($variables as $key => $val) {
		echo '<tr>';
		echo "<th><code class='jush-" . $jush . ($status ? 'status' : 'set') . "'>" . h($key) . '</code>';
		echo '<td>' . h($val);
	}
	echo "</table>\n";
}
