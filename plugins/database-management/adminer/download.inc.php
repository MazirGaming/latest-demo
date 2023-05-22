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

$TABLE  = $_GET['download'];
$fields = fields($TABLE);
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . friendly_url("$TABLE-" . implode('_', $_GET['where'])) . '.' . friendly_url($_GET['field']));
$select = [idf_escape($_GET['field'])];
$result = $driver->select($TABLE, $select, [where($_GET, $fields)], $select);
$row    = ($result ? $result->fetch_row() : []);
echo $driver->value($row[0], $fields[$_GET['field']]);

exit; // don't output footer
