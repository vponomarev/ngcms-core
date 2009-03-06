<?php

class mysql {

	function connect($host, $user, $pass, $db, $noerror = 0) {
		global $lang, $timer;
		
		$this->queries = 0;
		$this->query_list = array();
		$this->error = 0;
		$this->conn_db = $db;
		$this->queryTimer = (isset($timer) && (method_exists($timer, 'stop')));

		$this->connect = @mysql_connect($host, $user, $pass, true) or die('<h1>An Error Occurred</h1><hr />Unable to connect to the database!');
		@mysql_query("/*!40101 SET NAMES 'cp1251' */", $this->connect);
		if (!@mysql_select_db($db)) {
			if (!$noerror) {
				die('<h1>An Error Occurred</h1><hr />Unable to find the database <i>'.$db.'</i>!');
			}                                       	
			$this->error = 1;
		}	
	}

	function select($sql, $assocMode = 0) {
	        global $timer;
	        if ($this->queryTimer) { $tX = $timer->stop(4); }	

		$this->queries++;
		$query = mysql_query($sql, $this->connect) or msg(array("type" => "error", "text" => "Error! Bad select query! [".$sql."]"));
		$result = array();

		while($item = mysql_fetch_array($query, $assocMode?MYSQL_ASSOC:MYSQL_BOTH)) {
			$result[] = $item;
		}

		if ($this->queryTimer) { $tX = '[ '.($timer->stop(4) - $tX).' ] '; } else { $tX = ''; }	
		array_push ($this->query_list, $tX.$sql);

		return $result;
	}

	function record($sql) {
	        global $timer;
	        if ($this->queryTimer) { $tX = $timer->stop(4); }	

		$this->queries++;
		$query = @mysql_query($sql, $this->connect) or msg(array("type" => "error", "text" => "Error! Bad record query! [$sql]"));
		$item = mysql_fetch_array($query);

		if ($this->queryTimer) { $tX = '[ '.($timer->stop(4) - $tX).' ] '; } else { $tX = ''; }	
		array_push ($this->query_list, $tX.$sql);

		return $item;
	}

	function query($sql) {
		global $timer;

	        if ($this->queryTimer) { $tX = $timer->stop(4); }	
		$this->queries++;
		$query = @mysql_query($sql, $this->connect) or msg(array("type" => "error", "text" => "Error! Bad query! [$sql]"));
		if ($this->queryTimer) { $tX = '[ '.($timer->stop(4) - $tX).' ] '; } else { $tX = ''; }	
		array_push ($this->query_list, $tX.$sql);

		return $query;
	}

	function result($sql) {
	        global $timer;
	        if ($this->queryTimer) { $tX = $timer->stop(4); }	

		$this->queries++;
		$query = @mysql_query($sql, $this->connect) or msg(array("type" => "error", "text" => "Error! Bad result query!"));

		if ($this->queryTimer) { $tX = '[ '.($timer->stop(4) - $tX).' ] '; } else { $tX = ''; }	
		array_push ($this->query_list, $tX.$sql);

		if ($query) {
			return @mysql_result($query, 0);
		}
	}

	// check if table exists
	function table_exists($table) {

		if (is_array($this->table_list)) {
			return $this->table_list[$table]?1:0;
		}	
		$list = mysql_list_tables($this->conn_db, $this->connect);
		if (!$list) { return 0; }

		$this->table_list=array();
		while ($row = mysql_fetch_row($list)) {
			$this->table_list[$row[0]]=1;
		}
		return $this->table_list[$table]?1:0;
	}

	function getone($result) {
		$this->queries++;
		array_push ($this->query_list, $sql);
	
		foreach ($result as $value=>$description) {
			$output = $description;
		}

		return $output;
	}

	function affected_rows() {
		return mysql_affected_rows($this->connect);
	}

	function fieldname($result, $number) {
		$fieldname = @mysql_field_name($result, $number);

		return $fieldname;
	}

	function fields($result) {

		if (!$result) {
			return 0;
		}

		$num = @mysql_num_fields($result);

		return $num;
	}

	function rows($result) {

		if (!$result) {
			return 0;
		}

		$num = @mysql_num_rows($result);

		return $num;
	}

	function qcnt() {
		return $this->queries;
	}

	function lastid($table) {

		$row = $this->record("SHOW TABLE STATUS LIKE '".prefix."_".$table."'");

		return ($row['Auto_increment'] - 1);
	}

	function free($result) {
		@mysql_free_result($result);
	}

	function close() {
		@mysql_close($this->connect);
	}
}
