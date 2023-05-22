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

page_header(lang('Privileges'));

echo '<p class="links"><a href="' . h(ME) . 'user=">' . lang('Create user') . '</a>';

$result = $connection->query('SELECT User, Host FROM mysql.' . (DB == '' ? 'user' : 'db WHERE ' . q(DB) . ' LIKE Db') . ' ORDER BY Host, User');
$grant  = $result;

if (! $result) {
	// list logged user, information_schema.USER_PRIVILEGES lists just the current user too
	$result = $connection->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");
}

echo "<form action=''><p>\n";
hidden_fields_get();
echo "<input type='hidden' name='db' value='" . h(DB) . "'>\n";
echo($grant ? '' : "<input type='hidden' name='grant' value=''>\n");
echo "<table cellspacing='0'>\n";
echo '<thead><tr><th>' . lang('Username') . '<th>' . lang('Server') . "<th></thead>\n";

while ($row = $result->fetch_assoc()) {
	echo '<tr' . odd() . '><td>' . h($row['User']) . '<td>' . h($row['Host']) . '<td><a href="' . h(ME . 'user=' . urlencode($row['User']) . '&host=' . urlencode($row['Host'])) . '">' . lang('Edit') . "</a>\n";
}

if (! $grant || DB != '') {
	echo '<tr' . odd() . "><td><input name='user' autocapitalize='off'><td><input name='host' value='localhost' autocapitalize='off'><td><input type='submit' value='" . lang('Edit') . "'>\n";
}

echo "</table>\n";
echo "</form>\n";
