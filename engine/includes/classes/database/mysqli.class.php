<?php

class _mysqli
{
    public function connect($host, $user, $pass, $db = '', $noerror = 0)
    {
        global $lang, $timer;

        $this->queries = 0;
        $this->query_list = [];
        $this->table_list = [];
        $this->error = 0;
        $this->conn_db = $db;
        $this->queryTimer = (isset($timer) && (method_exists($timer, 'stop')));

        $this->connect = @mysqli_connect($host, $user, $pass, $db);
        if (!$this->connect) {
            if (!$noerror) {
                exit('<h1>An Error Occurred</h1><hr />'.mysqli_connect_error().'!');
            }
            $this->error = 1;

            return false;
        }
        @mysqli_query($this->connect, "/*!40101 SET NAMES 'utf8' */");

        return true;
    }

    public function select_db($db)
    {
        return @mysqli_select_db($this->connect, $db);
    }

    // Report an SQL error
    // $type	- query type
    // $query	- text of the query
    public function errorReport($type, $query)
    {
        global $userROW, $lang, $config;

        if (($config['sql_error_show'] == 2) ||
            (($config['sql_error_show'] == 1) && (is_array($userROW))) ||
            (($config['sql_error_show'] == 0) && (is_array($userROW)) && ($userROW['status'] == 1))
        ) {
            echo "<div style='font: 12px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'><span style='color: red;'>MySQL ERROR [".$type.']: '.$query.'</span><br/><span style="font: 9px arial;">('.mysqli_errno($this->connect).'): '.mysqli_error($this->connect).'</span></div>';
        } else {
            echo "<div style='font: 12px verdana; background-color: #EEEEEE; border: #ABCDEF 1px solid; margin: 1px; padding: 3px;'><span style='color: red;'>MySQL ERROR [".$type."]: *** (you don't have a permission to see this error) ***</span></span></div>";
        }
    }

    public function select($sql, $assocMode = 1)
    {
        global $timer;
        if ($this->queryTimer) {
            $tX = $timer->stop(4);
        }

        $this->queries++;
        if (!($query = @mysqli_query($this->connect, $sql))) {
            $this->errorReport('select', $sql);

            return [];
        }

        $result = [];

        switch ($assocMode) {
            case -1:
                $am = MYSQLI_NUM;
                break;
            case 1:
                $am = MYSQLI_ASSOC;
                break;
            case 0:
            default:
                $am = MYSQLI_BOTH;
        }

        while ($item = mysqli_fetch_array($query, $am)) {
            $result[] = $item;
        }

        if ($this->queryTimer) {
            $tX = '[ '.round($timer->stop(4) - $tX, 4).' ] ';
        } else {
            $tX = '';
        }
        array_push($this->query_list, $tX.$sql);

        return $result;
    }

    public function record($sql, $assocMode = 1)
    {
        global $timer;
        if ($this->queryTimer) {
            $tX = $timer->stop(4);
        }

        $this->queries++;
        if (!($query = @mysqli_query($this->connect, $sql))) {
            $this->errorReport('record', $sql);

            return [];
        }

        switch ($assocMode) {
            case -1:
                $am = MYSQLI_NUM;
                break;
            case 1:
                $am = MYSQLI_ASSOC;
                break;
            case 0:
            default:
                $am = MYSQLI_BOTH;
        }

        $item = mysqli_fetch_array($query, $am);

        if ($this->queryTimer) {
            $tX = '[ '.round($timer->stop(4) - $tX, 4).' ] ';
        } else {
            $tX = '';
        }
        array_push($this->query_list, $tX.$sql);

        return $item;
    }

    public function query($sql)
    {
        global $timer;

        if ($this->queryTimer) {
            $tX = $timer->stop(4);
        }
        $this->queries++;
        if (!($query = @mysqli_query($this->connect, $sql))) {
            $this->errorReport('query', $sql);

            return [];
        }

        if ($this->queryTimer) {
            $tX = '[ '.round($timer->stop(4) - $tX, 4).' ] ';
        } else {
            $tX = '';
        }
        array_push($this->query_list, $tX.$sql);

        return $query;
    }

    public function result($sql)
    {
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
            $tX = '[ '.($timer->stop(4) - $tX).' ] ';
        } else {
            $tX = '';
        }
        array_push($this->query_list, $tX.$sql);

        if ($query) {
            return @$this->mysqli_result($query, 0);
        }
    }

    public function num_fields($query)
    {
        if (!$query) {
            return false;
        }

        $result = mysqli_num_fields($query);

        return $result;
    }

    public function field_name($query, $field_offset)
    {
        if (!$query) {
            return false;
        }

        $result = mysqli_fetch_field_direct($query, $field_offset);

        return is_object($result) ? $result->name : false;
    }

    public function field_type($query, $field_offset)
    {
        static $types;

        if (!$query) {
            return false;
        }

        $type = mysqli_fetch_field_direct($query, $field_offset)->type;

        if (!isset($types)) {
            $types = [];
            $constants = get_defined_constants(true);
            foreach ($constants['mysqli'] as $c => $n) {
                if (preg_match('/^MYSQLI_TYPE_(.*)/', $c, $m)) {
                    $types[$n] = $m[1];
                }
            }
        }

        return array_key_exists($type, $types) ? $types[$type] : false;
    }

    public function field_len($query, $field_offset)
    {
        if (!$query) {
            return false;
        }

        $result = mysqli_fetch_field_direct($query, $field_offset);

        return is_object($result) ? $result->length : false;
    }

    public function num_rows($query)
    {
        if (!$query) {
            return false;
        }

        $result = mysqli_num_rows($query);

        return $result;
    }

    public function fetch_row($query)
    {
        if (!$query) {
            return [];
        }

        $result = mysqli_fetch_row($query);

        return $result;
    }

    public function fetch_array($query, $assocMode = 1)
    {
        if (!$query) {
            return [];
        }

        switch ($assocMode) {
            case -1:
                $am = MYSQLI_NUM;
                break;
            case 1:
                $am = MYSQLI_ASSOC;
                break;
            case 0:
            default:
                $am = MYSQLI_BOTH;
        }

        $result = mysqli_fetch_array($query, $am);

        return $result;
    }

    // check if table exists
    public function table_exists($table, $forceReload = 0)
    {

        // Check if data are already saved
        if (getIsSet($this->table_list[$table]) && is_array($this->table_list) && !$forceReload) {
            return $this->table_list[$table] ? 1 : 0;
        }

        if (!($query = @mysqli_query($this->connect, 'show tables'))) {
            $this->errorReport('select', 'show tables');

            return false;
        }

        while ($item = mysqli_fetch_array($query, MYSQLI_NUM)) {
            $this->table_list[$item[0]] = 1;
        }

        return $this->table_list[$table] ? 1 : 0;
    }

    public function affected_rows()
    {
        return mysqli_affected_rows($this->connect);
    }

    public function qcnt()
    {
        return $this->queries;
    }

    public function lastid($table = '')
    {
        if ($table != '') {
            $row = $this->record("SHOW TABLE STATUS LIKE '".prefix.'_'.$table."'");

            return $row['Auto_increment'] - 1;
        } else {
            return mysqli_insert_id($this->connect);
        }
    }

    public function db_errno()
    {
        return mysqli_errno($this->connect);
    }

    public function db_error()
    {
        return mysqli_error($this->connect);
    }

    public function db_quote($string)
    {
        return mysqli_real_escape_string($this->connect, $string);
    }

    public function mysql_version()
    {
        return mysqli_get_server_info($this->connect);
    }

    public function mysqli_result($result, $row, $field = 0)
    {
        $result->data_seek($row);
        $datarow = $result->fetch_array();

        return $datarow[$field];
    }

    public function close()
    {
        @mysqli_close($this->connect);
    }
}
