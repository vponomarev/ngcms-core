<?php

//
// Copyright (C) 2009 Next Generation CMS (http://ngcms.ru/)
// Name: uhandler.class.php
// Description: URL handler class
// Author: Vitaly Ponomarev
//

/*
 First: each plugin registers it's list of supported commands and
 list & types of accepted commands.

 Each plugin may register a list of it's supported commands with specified params


 params = array with supported params
		'vars'			=> array ( <VARIABLES> )
			'<NAME>'	=> array ( <PARAMS> )
				'matchRegex'	= matching REGEX
				'descr'			= description
		'descr'			= description

*/
class urlLibrary {
	// Constructor
	function urlLibrary(){
		global $config;

		$this->CMD = array();
		$this->configLoaded = false;
		$this->fatalError = false;
		$this->configFileName = confroot . 'urlconf.php';
	}

	// Load config from DISK
	function loadConfig(){
		// Check if config already loaded
		if ($this->configLoaded) {
			return true;
		}

		// Try to read config file
		if (is_file($this->configFileName)) {
			// Include REC
			include $this->configFileName;
			if (!isset($urlLibrary)) {
				$this->fatalError = 1;
				return false;
			}
			$this->CMD = $urlLibrary;
		}
		$this->configLoaded = true;
		return true;
	}

	// Save config to DISK
	function saveConfig(){
		// No save if config file is not loaded
		if (!$this->configLoaded)
			return false;

		// Try to write config file
		if (($f = fopen($this->configFileName, 'w')) === FALSE) {
			// Error
			$this->fatalError = true;
			return false;
		}

		fwrite($f, '<?php'."\n".'$urlLibrary = '.var_export($this->CMD, true).';');
		fclose($f);
		return true;
	}

	// Register supported commands
	function registerCommand($plugin, $cmd, $params){
		if (!$this->loadConfig()) {
			return false;
		}

		$this->CMD[$plugin][$cmd] = $params;
		return true;
	}

	// Remove recently registered command
	function removeCommand($plugin, $cmd){
		if (!$this->loadConfig()) {
			return false;
		}

		// Check if command exists
		if (isset($this->CMD[$plugin][$cmd])) {
			unset($this->CMD[$plugin][$cmd]);

			// Check if there're no more commands for this plugin
			if (is_array($this->CMD[$plugin]) && (!count($this->CMD[$plugin]))) {
				unset($this->CMD[$plugin]);
			}
		}
		return true;
	}

	// Fetch command data
	function fetchCommand($plugin, $cmd) {
		return isset($this->CMD[$plugin][$cmd])?$this->CMD[$plugin][$cmd]:false;
	}

	// Extract line with most matching language
	function extractLangRec($data, $pl = '') {
		global $config;

		if (!is_array($data))
			return false;

		if ($pl == '')
			$pl = $config['default_lang'];

		if (isset($data[$pl]))
			return $data[$pl];

		return isset($data['english'])?$data['english']:$data[0];
	}
}





/*
 urlLibrary - manages a list of possible actions that are supported by different plugins
 urlHandler - manages a configuration that will be used for URL matching/catching


 Supported function:
  * registerHandler() - register new handler in internal library
	pluginName			- name of the plugin
	pluginHandler		- name of the handler
	flagPrimary			- FLAG: primary record for this type of handlerName/pluginName. Will be used
                	  	for generation
	flagFailContinue	- FLAG: continue scanning if called function returned 'NOT FOUND'/'FAIL'.
 						May be used for merging static & news - try to show static, if no page
						is found - try to show news.
	callbackFunc		- function to call in case of successfull CATCH (it's return value is checked if
 						variable flagFailContinue is set



 Format of handlers:

 pluginName			- name of plugin
 handlerName		- name of handler in plugin
 flagPrimary		- FLAG: primary record for this type of handlerName/pluginName. Will be used
                	  for generation
 flagFailContinue	- FLAG: continue scanning if called function returned 'NOT FOUND'/'FAIL'.
 					  May be used for merging static & news - try to show static, if no page
					  is found - try to show news.
 callbackFunc		- function to call in case of successfull CATCH (it's return value is checked if
 					  variable flagFailContinue is set
 rstyle				- variables to REWRITE STYLE URL's [ Regex STYLE ]
  regex				- REGEX to catch URL
  regexMap			- MAPPING for converting URL with REGEX to variables
  reqCheck			- array with param check. In case if not very strict regex is used
  setVars			- array with defining additional variables
  genrMAP			- generation _rewrite_ MAP
 ostyle				- variables to OLD STYLE URL's [ Old STYLE ]
  reqCheck			- array with param check.
  setVars			- array with defining additional variables
  genoMap			- generation _old_ MAP

## regexMap - array:
    regexVarID => varName
   example:
 * regexMap => array (
    1 => 'id',
    2 => 'name',
   )

## reqCheck - array:
    varName => varRequiredValue
   example:
*   reqCheck => array (
     'mode' => 'sync'
	)

## setVars - array:
    varName => array( TYPE, VALUE )
     TYPE = 0, VALUE = static text
     TYPE = 1, VALUE = name of already defined variable to use

   example:
*  setVars => array (
    'action' => array ( 0, 'plugin' ),
	'plugin' => array ( 0, 'sync' ),
	'pcmd'   => array ( 1, 'name' ),
   )

## genrMAP - array of concatinated values:
	row: array ( TYPE, VALUE )
	 TYPE = 0, VALUE = static text
	 TYPE = 1, VALUE = name of variable

   example:
*  genrMAP => array (
    array ( 0, 'sync/' ),
	array ( 1, 'name'  )
   )
   // will generate URL like 'sync/HERE_IS_A_NAME'

## genoMAP - array of params to set
    varName => array ( TYPE, VALUE )
	 TYPE = 0, VALUE = static text
	 TYPE = 1, VALUE = name of variable

   example:
*  genrMAP => array (
    'action' => array ( 0, 'sync' ),
	'name' => array ( 1, 'name'  )
   )
   // will generate URL like '?action=sync&name=HERE_IS_A_NAME'



*/


class urlHandler {
	// constructor
	function urlHandler() {
		global $config;

		$this->hList		= array();
		$this->configLoaded	= false;
		$this->configFileName = confroot . 'rewrite.php';
	}

	// Populate handler record from HTTP submit interface
	function populateHandler($ULIB, $data){
		//
		// First - find references from URL library
		if (!isset($data['pluginName']) || !isset($data['handlerName']) || !isset($ULIB->CMD[$data['pluginName']][$data['handlerName']])) {
			return array(array(1, 'No match with URL library'.var_export($data, true)), false);
		}

		// Command catched
		$cmd = $ULIB->CMD[$data['pluginName']][$data['handlerName']];

		// Scan 'regex' line & prepare output regex

		// Stateful machine status
		// 0 - scanning text
		// 1 - scanning tag name
		$state = 0;
		$dataStartPos = 0;

		$pos = 0;
		$rcmd = $data['regex'];
		$len = strlen($rcmd);

		$variativeID = 0;
		$variative	= 0;
		$closeit	= false;

		$genmap		= array();

		while ($pos < $len) {
			switch ($state) {
				case '0':	if ($rcmd[$pos] == '[') {
								// Begin of variative block
								$newVariative	= ++$variativeID;
								$closeit		= true;
							}
							if ($rcmd[$pos] == ']') {
								$newVariative	= 0;
								$closeit		= true;
							}

							if ($rcmd[$pos] == '{') {
								$state			= 1;
								$closeit		= true;
								$newVariative	= $variative;
							}
							if ($closeit) {
								$closeit = false;

								if ($pos > $dataStartPos) {
									$text = substr($rcmd, $dataStartPos, $pos - $dataStartPos);
									$genmap[] = array(0, $text, $variative);
								}
								$variative	= $newVariative;
								$dataStartPos = $pos+1;
								break;
							}
							break;
				case '1':	if ($rcmd[$pos] == '}') {
								// End of the variable
								$text = substr($rcmd, $dataStartPos, $pos - $dataStartPos);
								$genmap[] = array(1, $text, $variative);
								$dataStartPos = $pos+1;
								$state = 0;
								break;
							}
							break;
			}
			$pos++;
		}

		// Check for last block
		if ($state) {
			// ERROR - not finished variable !!
			return array(array(2, 'Variable is not closed'), false);
		} else if ($variative) {
			// ERROR - not closed variative block !!
			return array(array(3, 'Variative block is not closed'), false);
		} else {
			if ($dataStartPos < $pos) {
				$text = substr($rcmd, $dataStartPos, $pos - $dataStartPos);
				$genmap[] = array(0, $text, 0);
			}
		}

		//print "Incoming cmd: [".$rcmd."]<br/><pre>";
		//print var_export($genmap, true);
		//print "</pre>";

		// Now we can generate processing REGEX
		$regex = '#^';
		$regexMAP = array();
		$paramNum = 1;
		$vmatch = 0;
		foreach ($genmap as $rec) {
			if ($rec[2] != $vmatch) {
				if (!$vmatch) {
					$vmatch = $rec[2];
					$regex .= '(?:';
				} else if (!$rec[2]) {
					$vmatch = 0;
					$regex .= '){0,1}';
				} else {
					$vmatch = $rec[2];
					$regex .= '){0,1}(?:';
				}
			}
			if ($rec[0]) {
				if (!isset($cmd['vars'][$rec[1]])) {
					return array(array(4, 'Variable "'.$rec[1].'" is unknown'), false);
				}
				$regex .= '(' . $cmd['vars'][$rec[1]]['matchRegex'] . ')';
				$regexMAP[$paramNum++] = $rec[1];
			} else {
				$regex .= $rec[1];
			}
		}

		// If we have closing variative block at the end - process it
		if ($vmatch) {
			$vmatch = 0;
			$regex .= '){0,1}';
		}
		// Closing REGEX tag
		$regex .= '$#';

		//print "Outgoing regex: [".$regex."]<br/><pre>";
		//print var_export($regexMAP, true);
		//print "</pre>";

		// Prepare outgoing structure
		$dcfg = array(
			'pluginName'	=> $data['pluginName'],
			'handlerName'	=> $data['handlerName'],
			'flagPrimary'	=> $data['flagPrimary']?true:false,
			'flagFailContinue'	=> $data['flagFailContinue']?true:false,
			'flagDisabled'	=> $data['flagDisabled']?true:false,
			'rstyle'		=> array(
				'rcmd'		=> $rcmd,
				'regex'		=> $regex,
				'regexMap'	=> $regexMAP,
				'reqCheck'	=> array(),
				'setVars'	=> array(),
				'genrMAP'	=> $genmap,
			),
		);

		//print "<b><u>Output data struct:</u></b> <br/><pre>\n";
		//print var_export($dcfg, true);
		//print "</pre>";

		return array(array(0,0), $dcfg);
	}

	// register handler
	function registerHandler($position, $handler) {
		if ($position == 0) { array_unshift($this->hList, $handler); }
		else if ($position == -1) { array_push($this->hList, $handler); }
		else {
			$tdata = array_slice($this->hList, 0, $position) + array($handler) + array_slice($this->hList, $position+1);
			$this->hList = $tdata;
		}
		return true;
	}

	// Load config from DISK
	function loadConfig(){

		// Try to read config file
		if (is_file($this->configFileName)) {
			// Include REC
			include $this->configFileName;
			if (!isset($handlerList) || !isset($handlerPrimary)) {
				$this->fatalError = 1;
				return false;
			}
			$this->hList = $handlerList;
			$this->hPrimary = $handlerPrimary;
		}
		$this->configLoaded = true;
		return true;
	}

	// Save config to DISK
	function saveConfig(){
		// No save if config file is not loaded
		if (!$this->configLoaded)
			return false;

		// Try to write config file
		if (($f = fopen($this->configFileName, 'w')) === FALSE) {
			// Error
			$this->fatalError = true;
			return false;
		}

		// Generate helper $handlerPrimary table
		$hPrimary = array();
		foreach ($this->hList as $hId => $hData) {
			if (isset($hPrimary[$hData['pluginName']][$hData['handlerName']])) {
				if (!$hPrimary[$hData['pluginName']][$hData['handlerName']][1] && ($hData['flagPrimary'])) {
					$hPrimary[$hData['pluginName']][$hData['handlerName']] = array($hId, $hData['flagPrimary']);
				}
			} else {
				$hPrimary[$hData['pluginName']][$hData['handlerName']] = array($hId, $hData['flagPrimary']);
			}
		}

		fwrite($f, '<?php'."\n".'$handlerList = '.var_export($this->hList, true).";\n".'$handlerPrimary = '.var_export($hPrimary, true).';');
		fclose($f);
		return true;
	}

	// RUN callback functions
	function run($url = null, $debug = false){
		// Firstly don't check for priority

		if ($url == null)
			$url = $_SERVER['REQUEST_URI'];

		if ($debug)
			print "urlHandler :: RUN(".$url.")<br>\n";

		$catchCount = 0;

		foreach($this->hList as $h) {
			if ($debug)
				print "&raquo; ".($h['flagDisabled']?'<b><font color="red">DISABLED</font></b> ':'')."Scan [".$h['pluginName']."][".$h['handlerName']."] ReGEX check [ <b><font color=blue>".$h['rstyle']['regex']." </font></b>]<br>\n";

			// Skip disabled records
			if ($h['flagDisabled'])
				continue;

			if (preg_match($h['rstyle']['regex'], $url, $scan)) {
				$result = array( '0' => $scan[0]);

				foreach ($scan as $k => $v)
					if (isset($h['rstyle']['regexMap'][$k]))
						$result[$h['rstyle']['regexMap'][$k]] = urldecode($v);

				if ($debug)
					print "Find match with REGex <b><font color=blue>".$h['rstyle']['regex']."</font></b>, params: <pre>".var_export($result, true)."</pre><br>\n";

				if (!isset($h['callback']))
					$h['callback'] = '_MASTER_URL_PROCESSOR';

				$skip = array ('FFC' => $h['flagFailContinue']?true:false);
				call_user_func($h['callback'], $h['pluginName'], $h['handlerName'], $result, &$skip);

				if (isset($skip['fail']))
					continue;

				return ++$catchCount;
			}

		}
		return $catchCount;
	}

}