<?php

class NGEvents
{
    protected $eventList = array();
    protected $startTime;

    function __construct()
    {
        // Init event processing
        $this->startTime = microtime(true);
    }

    // Register event
    function registerEvent($group, $plugin = null, $info = null, $duration = null)
    {
        $timeStamp = round(microtime(true) - $this->startTime, 2);
        if (is_array($group)) {
            $group['timestamp'] = $timeStamp;
            $this->eventList []= $group;
        } else {
            $this->eventList []= array(
                'timestamp'     => $timeStamp,
                'plugin'        => $plugin,
                'info'          => $info,
                'duration'      => $duration,
            );
        }
    }

    public function getEventList()
    {
        return $this->eventList;
    }

    public function tickStart()
    {
        return microtime(true);
    }

    public function tickStop($t)
    {
        return round(microtime(true) - $t, 2);
    }
}
