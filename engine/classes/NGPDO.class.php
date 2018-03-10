<?php

class NGPDO extends NGDB {

    protected $db               = null;
    protected $qCount           = 0;
    protected $qList            = array();
    protected $softErrors       = false;
    protected $errorSecurity    = 0;
    protected $eventLogger      = null;
    protected $errorHandler     = null;
    protected $dbCharset        = 'UTF8';

    function __construct($params) {
        if (!is_array($params)) {
            throw new Exception('NG_PDO: Parameters lost for constructor');
        }

        // Init params
        if (isset($params['softErrors']))
            $this->softErrors = $params['softErrors'];

        if (isset($params['errorSecurity']))
            $this->errorSecurity = $params['errorSecurity'];

        if (!isset($params['user']))
            throw new Exception('NG_PDO: User is not specified');

        if (!isset($params['pass']))
            throw new Exception('NG_PDO: Password is not specified');

        if (!isset($params['host']))
            throw new Exception('NG_PDO: Host is not specified');

        if (isset($params['eventLogger'])) {
            if (!($params['eventLogger'] instanceof NGEvents))
                throw new Exception('NGPDO: Passed eventLogger is not an instance of NGEvents class');

            $this->eventLogger = $params['eventLogger'];
        } else {
            $this->eventLogger  = NGEngine::getInstance()->getEvents();
        }

        if (isset($params['errorHandler'])) {
            if (!($params['errorHandler'] instanceof NGErrorHandler))
                throw new Exception('NGPDO: Passed eventLogger is not an instance of NGErrorHandler class');

            $this->errorHandler = $params['errorHandler'];
        } else {
            $this->errorHandler = NGEngine::getInstance()->getErrorHandler();
        }

        if (isset($params['charset']))
            $this->dbCharset = $params['charset'];


        // Mark start of DB connection procedure
        $tStart = $this->eventLogger->tickStart();

        try {
            $this->db = new PDO('mysql:host='.$params['host'].(isset($params['db'])?';dbname='.$params['db']:''), $params['user'], $params['pass']);
            $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        } catch(PDOException $e) {
            throw new Exception('NG_PDO: Error connecting to DB ('.$e->getCode().") [".$e->getMessage()."]", $e->getCode());
        }

        // Try to switch CHARSET
        try {
            $this->db->exec("/*!40101 SET NAMES '".$this->dbCharset."' */");
        } catch (PDOException $e) {
            throw new Exception("NG_PDO: Error switching to charset '".$this->dbCharset."' (".$e->getCode().") [".$e->getMessage()."]");
        }

        $this->eventLogger->registerEvent('NG_PDO', '', '* DB Connection established', $this->eventLogger->tickStop($tStart));
        return true;
    }

    function query($sql, $params = array()) {

        $tStart = $this->eventLogger->tickStart();
        $this->qCount++;

        try {
            // Check if we should prepare
            if (is_array($params) && count($params)) {
                $st = $this->db->prepare($sql);
                $st->execute($params);
                $r = $st->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $r = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $this->errorReport('query', $sql, $e);
            $r = null;
        }
        $duration = $this->eventLogger->tickStop($tStart);
        $this->eventLogger->registerEvent('NG_PDO', 'QUERY', $sql, $duration);
        $this->qList []= array('query' => $sql, 'duration' => $duration, 'start' => $tStart);

        return $r;
    }

    function record($sql, $params = array()) {

        $tStart = $this->eventLogger->tickStart();
        $this->qCount++;

        try {
            // Check if we should prepare
            if (is_array($params) && count($params)) {
                $st = $this->db->prepare($sql);
                $st->execute($params);
                $r = $st->fetch(PDO::FETCH_ASSOC);
                $st->closeCursor();
            } else {
                $r = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $this->errorReport('record', $sql, $e);
            $r = null;
        }
        $duration = $this->eventLogger->tickStop($tStart);
        $this->eventLogger->registerEvent('NG_PDO', 'RECORD', $sql, $duration);
        $this->qList []= array('query' => $sql, 'duration' => $duration);

        return $r;
    }


    function exec($sql, $params = array()) {
        $tStart = $this->eventLogger->tickStart();
        $this->qCount++;

        $r = null;
        try {
            // Check if we should prepare
            if (is_array($params) && count($params)) {
                $st = $this->db->prepare($sql);
                $st->execute($params);
                $st->closeCursor();
            } else {
                $r = $this->db->query($sql);
            }
        } catch (PDOException $e) {
            $this->errorReport('exec', $sql, $e);
            $r = null;
        }
        $duration = $this->eventLogger->tickStop($tStart);
        $this->eventLogger->registerEvent('NG_PDO', 'EXEC', $sql, $duration);
        $this->qList []= array('query' => $sql, 'duration' => $duration);
		
        return $r;
    }

    function result($sql, $params = array()) {

        $tStart = $this->eventLogger->tickStart();
        $this->qCount++;

        try {
            // Check if we should prepare
            if (is_array($params) && count($params)) {
                $st = $this->db->prepare($sql);
                $st->execute($params);
                $r = $st->fetch(PDO::FETCH_ASSOC);
                $st->closeCursor();
            } else {
                $r = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $this->errorReport('result', $sql, $e);
            $r = null;
        }
        $duration = $this->eventLogger->tickStop($tStart);
        $this->eventLogger->registerEvent('NG_PDO', 'RESULT', $sql, $duration);
        $this->qList []= array('query' => $sql, 'duration' => $duration);

        if (count($r)) {
            return $r[array_shift(array_keys($r))];
        }
        return null;
    }
	
	function num_rows($st) {
		try {
			$r = $st->fetchColumn();
		} catch (PDOException $e) {
            $this->errorReport('num_rows', $sql, $e);
            $r = null;
        }

		return $r;
	}
	
	function fetch_row($st) {
		try {
			$r = $st->fetch(PDO::FETCH_NUM);
		} catch (PDOException $e) {
            $this->errorReport('fetch_row', $sql, $e);
        }
		return $r;
	}
	
	function lastid($table = '') {
		try {
			if(empty($table)){
				return $id = $this->db->lastInsertId();
			} else {
				$r = $this->record('SHOW TABLE STATUS LIKE \'' . prefix . '_' . $table . '\'');
				return ($r['Auto_increment'] - 1);
			}
		} catch (PDOException $e) {
            $this->errorReport('lastid', $sql, $e);
        }
	}
	
	function affected_rows(){
		try {
			return $id = $this->db->rowCount();
		} catch (PDOException $e) {
            $this->errorReport('affected_rows', $sql, $e);
        }
	}
	
	function close($query){
		try {
			if($this->db != null)
				$this->db = null;
		} catch (PDOException $e) {
            $this->errorReport('close', $sql, $e);
        }
	}
	
	function db_errno() {
		try {
			$this->db->errorInfo()[0];
		} catch (mysqli_sql_exception $e) {
            $this->errorReport('close', $sql, $e);
        }
	}
	
    /**
     * @param $string
     * @return string
     */
    function quote($string)  {
        return mb_substr($this->db->quote($string), 1, -1);
    }

    /**
     * @return string
     */
    function getEngineType() {
        return 'PDO';
    }

    /**
     * @return PDO Instance of PDO driver for low level access
     */
    function getDriver() {
        return $this->db;
    }
	
	/**
     * @return version PDO
     */
	function getVersion() {
        return $this->getDriver()->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
    }
	
    /**
    // Report an SQL error

     * @param $type string Query type
     * @param $query string Query content
     * @param PDOException $e
     */
    function errorReport($type, $query, PDOException $e) {
        $errNo = 'n/a';
        $errMsg = 'n/a';
        if (get_class($e) == 'PDOException') {
            $errNo = $e->getCode();
            $errMsg = $e->getMessage();
        }
        $this->errorHandler->throwError('SQL', array('errNo' => $errNo, 'errMsg' => $errMsg, 'type' => $type, 'query' => $query), $e);
	}

    function getQueryCount() {
        return $this->qCount;
    }

    function getQueryList() {
        return $this->qList;
    }

    // Cursor based operations
    /**
     * @param $query
     * @param array $params
     * @return PDOStatement
     */
    function createCursor($query, array $params = array()) {
        $cursor = $this->db->prepare($query);
		if(is_array($params))
			foreach($params as $key => $value)
				$cursor->bindParam(':'.$key , $value, is_int($value)?PDO::PARAM_INT:PDO::PARAM_STR);
        $cursor->execute();
        return $cursor;
    }

    /**
     * @param PDOStatement $cursor
     * @return mixed
     */
    function fetchCursor($cursor) {
        return $cursor->fetch(PDO::FETCH_ASSOC);
    }

    function closeCursor($cursor) {
        return $cursor->closeCursor();
    }

    function tableExists($name) {
        return is_array($this->record("show tables like :name", array('name' => $name)))?true:false;
    }
}