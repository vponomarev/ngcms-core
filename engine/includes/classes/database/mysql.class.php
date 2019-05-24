<?php

class mysql {

	function connect($host, $user, $pass, $db = '', $noerror = 0) {

		global $lang, $timer;

		$this->queries = 0;
		$this->query_list = array();
		$this->table_list = array();
		$this->error = 0;
		$this->conn_db = $db;
		$this->queryTimer = (isset($timer) && (method_exists($timer, 'stop')));

		$this->connect = @mysql_connect($host, $user, $pass, true);
		if (!$this->connect) {
			if (!$noerror) {
				die('<h1>An Error Occurred</h1><hr />Unable to connect to the database!');
			}
			$this->error = 1;

			return false;
		}
		@mysql_query("/*!40101 SET NAMES 'utf8' */", $this->connect);
		if (!empty($db) && !@mysql_select_db($db)) {
			if (!$noerror) {
				die('<h1>An Error Occurred</h1><hr />Unable to find the database <i>' . $db . '</i>!');
			}
			$this->error = 2;

			return false;
		}

		return true;
	}

	function select_db($db) {

		return @mysql_select_db($db);
	}

	// Report an SQL error
	// $type	- query type
	// $query	- text of the query
	function errorReport($type, $query) {

		global $userROW, $lang, $config;

		if (($config['sql_error_show'] == 2) ||
			(($config['sql_error_show'] == 1) && (is_array($userROW))) ||
			(($config['sql_error_show'] == 0) && (is_array($userROW)) && ($userROW['status'] == 1))
		) {
			print "<div style='font: 12px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'><span style='color: red;'>MySQL ERROR [" . $type . "]: " . $query . "</span><br/><span style=\"font: 9px arial;\">(" . mysql_errno($this->connect) . '): ' . mysql_error($this->connect) . '</span></div>';
		} else {
			print "<div style='font: 12px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'><span style='color: red;'>MySQL ERROR [" . $type . "]: *** (you don't have a permission to see this error) ***</span></span></div>";
		}
	}

	function select($sql, $assocMode = 1) {

		global $timer;
		if ($this->queryTimer) {
			$tX = $timer->stop(4);
		}

		$this->queries++;
		if (!($query = @mysql_query($sql, $this->connect))) {
			$this->errorReport('select', $sql);

			return array();
		}

		$result = array();

		switch ($assocMode) {
			case -1:
				$am = MYSQL_NUM;
				break;
			case  1:
				$am = MYSQL_ASSOC;
				break;
			case  0:
			default:
				$am = MYSQL_BOTH;
		}

		while ($item = mysql_fetch_array($query, $am)) {
			$result[] = $item;
		}

		if ($this->queryTimer) {
			$tX = '[ ' . round($timer->stop(4) - $tX, 4) . ' ] ';
		} else {
			$tX = '';
		}
		array_push($this->query_list, $tX . $sql);

		return $result;
	}

	function record($sql, $assocMode = 1) {

		global $timer;
		if ($this->queryTimer) {
			$tX = $timer->stop(4);
		}

		$this->queries++;
		if (!($query = @mysql_query($sql, $this->connect))) {
			$this->errorReport('record', $sql);

			return array();
		}
		switch ($assocMode) {
			case -1:
				$am = MYSQL_NUM;
				break;
			case  1:
				$am = MYSQL_ASSOC;
				break;
			case  0:
			default:
				$am = MYSQL_BOTH;
		}

		$item = mysql_fetch_array($query, $am);

		if ($this->queryTimer) {
			$tX = '[ ' . round($timer->stop(4) - $tX, 4) . ' ] ';
		} else {
			$tX = '';
		}
		array_push($this->query_list, $tX . $sql);

		return $item;
	}

	function query($sql) {

		global $timer;

		if ($this->queryTimer) {
			$tX = $timer->stop(4);
		}
		$this->queries++;
		if (!($query = @mysql_query($sql, $this->connect))) {
			$this->errorReport('query', $sql);

			return array();
		}

		if ($this->queryTimer) {
			$tX = '[ ' . round($timer->stop(4) - $tX, 4) . ' ] ';
		} else {
			$tX = '';
		}
		array_push($this->query_list, $tX . $sql);

		return $query;
	}

	function result($sql) {

		global $timer;
		if ($this->queryTimer) {
			$tX = $timer->stop(4);
		}

		$this->queries++;
		if (!($query = @mysql_query($sql, $this->connect))) {
			$this->errorReport('result', $sql);

			return false;
		}

		if ($this->queryTimer) {
			$tX = '[ ' . ($timer->stop(4) - $tX) . ' ] ';
		} else {
			$tX = '';
		}
		array_push($this->query_list, $tX . $sql);

		if ($query) {
			return @mysql_result($query, 0);
		}
	}

	function num_fields($query) {

		if (!$query) return false;

		$result = mysql_num_fields($query);

		return $result;
	}

	function field_name($query, $field_offset) {

		if (!$query) return false;

		$result = mysql_field_name($query, $field_offset);

		return $result;
	}

	function field_type($query, $field_offset) {

		if (!$query) return false;

		$result = mysql_field_type($query, $field_offset);

		return $result;
	}

	function field_len($query, $field_offset) {

		if (!$query) return false;

		$result = mysql_field_len($query, $field_offset);

		return $result;
	}

	function num_rows($query) {

		if (!$query) return false;

		$result = mysql_num_rows($query);

		return $result;
	}

	function fetch_row($query) {

		if (!$query) return array();

		$result = mysql_fetch_row($query);

		return $result;
	}
	
	function fetch_array($query, $assocMode = 1) {
		
		if (!$query) return array();
		
		switch ($assocMode) {
			case -1:
				$am = MYSQL_NUM;
				break;
			case  1:
				$am = MYSQL_ASSOC;
				break;
			case  0:
			default:
				$am = MYSQL_BOTH;
		}
		
		$result = mysql_fetch_array($query, $am);
		
		return $result;
	}
	
	// check if table exists
	function table_exists($table, $forceReload = 0) {

		// Check if data are already saved
		if (getIsSet($this->table_list[$table]) && is_array($this->table_list) && !$forceReload) {
			return $this->table_list[$table] ? 1 : 0;
		}

		if (!($query = @mysql_query("show tables", $this->connect))) {
			$this->errorReport('select', "show tables");

			return false;
		}

		while ($item = mysql_fetch_array($query, MYSQL_NUM)) {
			$this->table_list[$item[0]] = 1;
		}

		return $this->table_list[$table] ? 1 : 0;
	}

	function affected_rows() {

		return mysql_affected_rows($this->connect);
	}

	function qcnt() {

		return $this->queries;
	}

	function db_errno() {

		return mysql_errno($this->connect);
	}

	function db_error() {

		return mysql_error($this->connect);
	}

	function db_quote($string) {

		return mysql_real_escape_string($string);
	}

	function mysql_version() {

		return mysql_get_server_info();
	}

	function lastid($table = '') {

		if ($table != '') {
			$row = $this->record("SHOW TABLE STATUS LIKE '" . prefix . "_" . $table . "'");

			return ($row['Auto_increment'] - 1);
		} else {
			return mysql_insert_id($this->connect);
		}
	}

	function close() {

		@mysql_close($this->connect);
	}
}