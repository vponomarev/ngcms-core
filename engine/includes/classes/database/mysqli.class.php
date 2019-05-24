<?php

class _mysqli {

	function connect($host, $user, $pass, $db = '', $noerror = 0) {

		global $lang, $timer;

		$this->queries = 0;
		$this->query_list = array();
		$this->table_list = array();
		$this->error = 0;
		$this->conn_db = $db;
		$this->queryTimer = (isset($timer) && (method_exists($timer, 'stop')));

		$this->connect = @mysqli_connect($host, $user, $pass, $db);
		if (!$this->connect) {
			if (!$noerror) {
				die('<h1>An Error Occurred</h1><hr />' . mysqli_connect_error() . '!');
			}
			$this->error = 1;

			return false;
		}
		@mysqli_query($this->connect, "/*!40101 SET NAMES 'utf8' */");

		return true;
	}

	function select_db($db) {

		return @mysqli_select_db($this->connect, $db);
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
			print "<div style='font: 12px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'><span style='color: red;'>MySQL ERROR [" . $type . "]: " . $query . "</span><br/><span style=\"font: 9px arial;\">(" . mysqli_errno($this->connect) . '): ' . mysqli_error($this->connect) . '</span></div>';
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
		if (!($query = @mysqli_query($this->connect, $sql))) {
			$this->errorReport('select', $sql);

			return array();
		}

		$result = array();

		switch ($assocMode) {
			case -1:
				$am = MYSQLI_NUM;
				break;
			case  1:
				$am = MYSQLI_ASSOC;
				break;
			case  0:
			default:
				$am = MYSQLI_BOTH;
		}

		while ($item = mysqli_fetch_array($query, $am)) {
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
		if (!($query = @mysqli_query($this->connect, $sql))) {
			$this->errorReport('record', $sql);

			return array();
		}

		switch ($assocMode) {
			case -1:
				$am = MYSQLI_NUM;
				break;
			case  1:
				$am = MYSQLI_ASSOC;
				break;
			case  0:
			default:
				$am = MYSQLI_BOTH;
		}

		$item = mysqli_fetch_array($query, $am);

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
		if (!($query = @mysqli_query($this->connect, $sql))) {
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
		if (!($query = @mysqli_query($this->connect, $sql))) {
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
			return @$this->mysqli_result($query, 0);
		}
	}

	function num_fields($query) {

		if (!$query) return false;

		$result = mysqli_num_fields($query);

		return $result;
	}

	function field_name($query, $field_offset) {

		if (!$query) return false;

		$result = mysqli_fetch_field_direct($query, $field_offset);

		return is_object($result) ? $result->name : false;
	}

	function field_type($query, $field_offset) {

		static $types;

		if (!$query) return false;

		$type = mysqli_fetch_field_direct($query, $field_offset)->type;

		if (!isset($types)) {
			$types = array();
			$constants = get_defined_constants(true);
			foreach ($constants['mysqli'] as $c => $n) if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) $types[$n] = $m[1];
		}

		return array_key_exists($type, $types) ? $types[$type] : false;
	}

	function field_len($query, $field_offset) {

		if (!$query) return false;

		$result = mysqli_fetch_field_direct($query, $field_offset);

		return is_object($result) ? $result->length : false;
	}

	function num_rows($query) {

		if (!$query) return false;

		$result = mysqli_num_rows($query);

		return $result;
	}

	function fetch_row($query) {

		if (!$query) return array();

		$result = mysqli_fetch_row($query);

		return $result;
	}
	
	function fetch_array($query, $assocMode = 1) {
		
		if (!$query) return array();
		
		switch ($assocMode) {
			case -1:
				$am = MYSQLI_NUM;
				break;
			case  1:
				$am = MYSQLI_ASSOC;
				break;
			case  0:
			default:
				$am = MYSQLI_BOTH;
		}
		
		$result = mysqli_fetch_array($query, $am);
		
		return $result;
	}
	
	// check if table exists
	function table_exists($table, $forceReload = 0) {

		// Check if data are already saved
		if (getIsSet($this->table_list[$table]) && is_array($this->table_list) && !$forceReload) {
			return $this->table_list[$table] ? 1 : 0;
		}

		if (!($query = @mysqli_query($this->connect, "show tables"))) {
			$this->errorReport('select', "show tables");

			return false;
		}

		while ($item = mysqli_fetch_array($query, MYSQLI_NUM)) {
			$this->table_list[$item[0]] = 1;
		}

		return $this->table_list[$table] ? 1 : 0;
	}

	function affected_rows() {

		return mysqli_affected_rows($this->connect);
	}

	function qcnt() {

		return $this->queries;
	}

	function lastid($table = '') {

		if ($table != '') {
			$row = $this->record("SHOW TABLE STATUS LIKE '" . prefix . "_" . $table . "'");

			return ($row['Auto_increment'] - 1);
		} else {
			return mysqli_insert_id($this->connect);
		}
	}

	function db_errno() {

		return mysqli_errno($this->connect);
	}

	function db_error() {

		return mysqli_error($this->connect);
	}

	function db_quote($string) {

		return mysqli_real_escape_string($this->connect, $string);
	}

	function mysql_version() {

		return mysqli_get_server_info($this->connect);
	}

	function mysqli_result($result, $row, $field = 0) {

		$result->data_seek($row);
		$datarow = $result->fetch_array();

		return $datarow[$field];
	}

	function close() {

		@mysqli_close($this->connect);
	}
}