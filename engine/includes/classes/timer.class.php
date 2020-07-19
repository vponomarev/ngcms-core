<?php

//
// Copyright (C) 2006-2016 Next Generation CMS (http://ngcms.ru/)
// Name: timer.class.php
// Description: Time measurer class
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

class microTimer
{
    // CONSTRUCTOR
    public function __construct()
    {
        $this->events = [];
    }

    public function start()
    {
        list($usec, $sec) = explode(' ', microtime());
        $this->script_start = (float) $sec + (float) $usec;
        $this->last_event = $this->script_start;
    }

    public function stop($points = 3)
    {
        list($usec, $sec) = explode(' ', microtime());
        $script_end = (float) $sec + (float) $usec;
        $elapsed_time = round($script_end - $this->script_start, $points);

        return $elapsed_time;
    }

    // CLEAR event list
    public function clearEvents()
    {
        $this->events = [];
    }

    // REGISTER measurment
    public function registerEvent($eventName, $eventParams = '')
    {
        $current_time = $this->stop(4);
        $delta = $current_time - $this->last_event;
        if ($delta < 0) {
            $delta = 0;
        }

        array_push($this->events, [$current_time, sprintf('%7.3f', $delta), $eventName, $eventParams, memory_get_usage(), memory_get_peak_usage()]);
        $this->last_event = $current_time;
    }

    // Return a list of events
    public function returnEvents()
    {
        return $this->events;
    }

    // Print events
    public function printEvents($html = 0)
    {
        $out = ($html) ? "<table class='timeProfiler'>\n<tr><td><b>Time</b></td><td><b>Delta</b></td><td><b>Event</b></td><td><b>Memory (now/peak)</b></td><td><b>Desc</b></td></tr>\n" : '';
        foreach ($this->events as $v) {
            $out .= ($html) ? ('<tr><td>'.sprintf('%7.3f', $v[0]).'</td><td>'.$v[1].'</td><td>'.$v[2].'</td><td>'.sprintf('%7.3f Mb / %7.3f Mb', $v[4] / 1024 / 1024, $v[5] / 1024 / 1024).'</td><td>'.$v[3]."</td></tr>\n") : $v[0]."\t".$v[1]."\t".$v[2]."\t".$v[3]."\t".$v[4].' / '.$v[5]."\n";
        }
        $out .= (($html) ? '</table>' : '')."\n";

        return $out;
    }
}
