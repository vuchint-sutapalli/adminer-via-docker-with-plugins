<?php

/** Log all queries to SQL file
* @link https://www.adminer.org/plugins/#use
* @author Jakub Vrana, https://www.vrana.cz/
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerSqlLog extends Adminer\Plugin {
	protected $filename;

	/**
	* @param string $filename defaults to "$database.sql"
	*/
	function __construct($filename = "") {
		$this->filename = $filename;
	}

	function messageQuery($query, $time, $failed = false) {
		$this->log($query, $failed);
	}

	function sqlCommandQuery($query) {
		$this->log($query, false);
	}

	private function log($query) {
		if ($this->filename == "") {
			// We prepend the fixed path '/var/www/html/logs/'
        	$this->filename = "logs/" . Adminer\adminer()->database() . ($_GET["ns"] != "" ? ".$_GET[ns]" : "") . ".sql";
		}
		$timestamp = date('Y-m-d H:i:s');
		// CRITICAL: Determine status based on the $failed parameter
        $status = $failed ? 'FAILED' : 'SUCCESS';

        $metadata = "-- $timestamp | Status: $status\n";
        
        // Ensure the query ends with a semicolon for SQL file integrity
        $cleanQuery = rtrim($query, ";") . ";";

        $logEntry = $metadata . $cleanQuery . "\n\n";

		$fp = fopen($this->filename, "a");
		flock($fp, LOCK_EX);
		fwrite($fp, $logEntry);
		fwrite($fp, "\n\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}

	protected $translations = array(
		'cs' => array('' => 'Zaznamenává všechny příkazy do souboru SQL'),
		'de' => array('' => 'Protokollieren Sie alle Abfragen in einer SQL-Datei'),
		'pl' => array('' => 'Rejestruj wszystkie zapytania do pliku SQL'),
		'ro' => array('' => 'Logați toate interogările în fișierul SQL'),
		'ja' => array('' => '全クエリを SQL ファイルに記録'),
	);
}
