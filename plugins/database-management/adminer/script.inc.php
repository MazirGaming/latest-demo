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

header('Content-Type: text/javascript; charset=utf-8');

if ($_GET['script'] == 'db') {
	$sums = ['Data_length' => 0, 'Index_length' => 0, 'Data_free' => 0];

	foreach (table_status() as $name => $table_status) {
		json_row("Comment-$name", h($table_status['Comment']));

		if (! is_view($table_status)) {
			foreach (['Engine', 'Collation'] as $key) {
				json_row("$key-$name", h($table_status[$key]));
			}

			foreach ($sums + ['Auto_increment' => 0, 'Rows' => 0] as $key => $val) {
				if ($table_status[$key] != '') {
					$val = format_number($table_status[$key]);
					json_row("$key-$name", ($key == 'Rows' && $val && $table_status['Engine'] == ($sql == 'pgsql' ? 'table' : 'InnoDB')
						? "~ $val"
						: $val
					));

					if (isset($sums[$key])) {
						// ignore innodb_file_per_table because it is not active for tables created before it was enabled
						$sums[$key] += ($table_status['Engine'] != 'InnoDB' || $key != 'Data_free' ? $table_status[$key] : 0);
					}
				} elseif (array_key_exists($key, $table_status)) {
					json_row("$key-$name");
				}
			}
		}
	}

	foreach ($sums as $key => $val) {
		json_row("sum-$key", format_number($val));
	}
	json_row('');
} elseif ($_GET['script'] == 'kill') {
	$connection->query('KILL ' . number($_POST['kill']));
} else { // connect
	foreach (count_tables($adminer->databases()) as $db => $val) {
		json_row("tables-$db", $val);
		json_row("size-$db", db_size($db));
	}
	json_row('');
}

exit; // don't print footer
