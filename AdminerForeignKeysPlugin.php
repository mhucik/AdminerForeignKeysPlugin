<?php

class AdminerForeignKeys {
	function backwardKeys($table, $tableName) {
		$connection = connection();

		$database = $connection->query('SELECT DATABASE() AS db;')->fetch_assoc();
		$result = $connection->query(sprintf('SELECT TABLE_NAME,COLUMN_NAME,REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = \'%s\' AND CONSTRAINT_SCHEMA = \'%s\';', $tableName, $database['db']));

		$backwardKeys = [];
		$i = 0;

		if ($result) {
			while ($row = $result->fetch_assoc()) {
				$backwardKeys[$row['TABLE_NAME'] . $i] = [
					'tableName' => $row['TABLE_NAME'],
					'columnName' =>$row['COLUMN_NAME'],
					'referencedColumnName' =>$row['REFERENCED_COLUMN_NAME'],
				];
				$i++;
			}
		}

		ksort($backwardKeys);

		return $backwardKeys;
	}

	function backwardKeysPrint($backwardKeys, $row) {
		foreach ($backwardKeys as $backwardKey) {
			$whereLink = where_link(1, $backwardKey['columnName'], $row[$backwardKey['referencedColumnName']]);
			$link = sprintf('select=%s%s', $backwardKey['tableName'], $whereLink);
			echo sprintf("<a href='%s'>%s</a>\n", h(ME . $link), $backwardKey['tableName']);
		}
	}
}

return new AdminerForeignKeys;
