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

namespace Vvveb\Controller\Tools;

use Vvveb\Controller\Base;

class Backup extends Base {
	public function getTables() {
		return [];
		$table_data = [];

		$query = $this->db->query('SHOW TABLES FROM `' . DB_DATABASE . '`');

		foreach ($query->rows as $result) {
			if (isset($result['Tables_in_' . DB_DATABASE]) && substr($result['Tables_in_' . DB_DATABASE], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$table_data[] = $result['Tables_in_' . DB_DATABASE];
			}
		}

		return $table_data;
	}

	public function getRecords($table, $start = 0, $limit = 100) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query('SELECT * FROM `' . $table . '` LIMIT ' . (int)$start . ',' . (int)$limit);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return [];
		}
	}

	public function getTotalRecords(string $table) : int {
		$query = $this->db->query('SELECT COUNT(*) AS `total` FROM `' . $table . '`');

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	function save() {
		$page   = $this->request->get['page'] ?? 1;
		$tables = $this->getTables();

		foreach ($tables as $table) {
			if ($page == 1) {
				$output .= "TRUNCATE TABLE `$table`;\n\n";
			}
		}

		return $this->index();
	}

	function download() {
		return $this->index();
	}

	function index() {
		$view        = $this->view;
		$backupFiles = glob(DIR_BACKUP . '*');

		foreach ($backupFiles as $index => $file) {
			$name      = basename($file);
			$size      = filesize($file);
			$backups[] = [
				'name'             => $name,
				'key'              => $index,
				'file'             => $file,
				'size_bytes'       => $size,
				'size'             => \Vvveb\formatBytes($size),
				'date_added'       => date('Y/m/d H:i:s', filemtime($file)),
			];
		}

		$view->backups = $backups;
	}
}
