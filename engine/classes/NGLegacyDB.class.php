<?php

//
// Implements LEGACY DB backward compatibility
class NGLegacyDB
{
    // StandAlone mode:
    // - true   :: Creates independent connection to DB
    // - false  :: Reuses current connection to DB
    protected $isStandalone = true;
    /**
     * @var $db NGPDO Instance of PDO connection
     */
    protected $db;

    function __construct($standAlone = true)
    {
        $this->isStandalone = $standAlone;
    }

    function connect($host, $user, $pass, $db = '', $noerror = 0)
    {
        // Legacy compatibility
        if (!$this->isStandalone) {
            $this->db = NGEngine::getInstance()->getDB();
        } else {
            $this->db = new NGPDO(array('host' => $host, 'user' => $user, 'pass' => $pass, 'db' => $db));
        }
    }

    function select_db($db)
    {
        return $this->db->exec("use ".$db);
    }

    function select($sql, $assocMode = 1)
    {
        return $this->db->query($sql);
    }

    function record($sql, $assocMode = 1)
    {
        return $this->db->record($sql);
    }

    function query($sql)
    {
        return $this->db->exec($sql);
    }

    function result($sql)
    {
        return $this->db->result($sql);
    }

    function db_quote($string)
    {
        return $this->db->quote($string);
    }

    function qcnt()
    {
        return $this->db->getQueryCount();
    }
    
    function num_rows($query)
    {
        return $this->db->num_rows($query);
    }
    
    function fetch_row($query)
    {
        return $this->db->fetch_row($query);
    }
    
    function affected_rows()
    {
        return $this->db->affected_rows();
    }
    
    function lastid($table = '')
    {
        return $this->db->lastid($table);
    }
    
    function mysql_version()
    {
        return $this->db->getVersion();
    }

    function close($query)
    {
        return $this->db->close($query);
    }
    
    function db_errno()
    {
        return $this->db->db_errno();
    }
    
    function __get($name)
    {
        if ($name == 'query_list') {
            $qList = array();
            foreach ($this->db->getQueryList() as $r) {
                $qList []= sprintf('%6.2f %6.2f %s', $r['start'], $r['duration'], $r['query']);
            }
            return $qList;
        }
        return null;
    }
}
