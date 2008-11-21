<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: timer.class.php
// Description: Time measurer class
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

class microTimer {

	// CONSTRUCTOR
	function microTimer(){
		$this->events = array();
	}

	function start() {
		list($usec, $sec) = explode(' ', microtime());
		$this->script_start = (float) $sec + (float) $usec;
	}

	function stop($points = 2) {
		list($usec, $sec) = explode(' ', microtime());
		$script_end = (float) $sec + (float) $usec;
		$elapsed_time = round($script_end - $this->script_start, $points);

		return $elapsed_time;
	}

	// CLEAR event list
	function clearEvents(){ $this->events = array(); }

	// REGISTER measurment
	function registerEvent($eventName, $eventParams = ''){
		array_push($this->events, array( $this->stop(4), $eventName, $eventParams ));
	}

	// Return a list of events
	function returnEvents(){ return $this->events; }

	// Print events
	function printEvents($html = 0){
		$out = ($html)?"<table>\n":'';
		foreach ($this->events as $v) {
			$out .= ($html)?('<tr><td>'.$v[0].'</td><td>'.$v[1].'</td><td>'.$v[2]."</td></tr>\n"):$v[0]."\t".$v[1]."\t".$v[2]."\n";
		}
		$out .= (($html)?"</table>":'')."\n";
		return $out;
	}
}
