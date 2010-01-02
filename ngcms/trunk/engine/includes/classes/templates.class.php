<?php

class tpl {

	var $data	=	array();
	var $root	=	'.';
	var $ext	=	'.tpl';
	var $da_vr	=	array();
	var $execTime = 0;
	var $execCount = 0;

	function template($name, $dir, $file = '') {
		global $lang;

		// Prepare to calculate exec time
		list($usec, $sec) = explode(' ', microtime());
		$timeStart = (float) $sec + (float) $usec;

		if (is_dir($dir)) {
			$this -> root = $dir;
		}
		else {
			die(sprintf($lang['msge_no_tpldir'], $dir));
		}

		$nn		=	$name;
		$ext	=	$this -> ext;
		$name	=	$dir.'/'.$name.$ext;

		if ($file) {
			$name = $dir.$file;
		}

		if (!is_file($name)) {
			die(sprintf($lang['msgå_no_tpl'], $name));
		}

		$fp		=	fopen($name,'r');
		$data	=	filesize($name)?fread($fp,filesize($name)):'';
		fclose($fp);

		$this -> data[$nn] = $data;

		// Save calculate exec time
		list($usec, $sec) = explode(' ', microtime());
		$timeStop = (float) $sec + (float) $usec;

		$this->execTime += ($timeStop - $timeStart);
	}

	// Params [array]:
	// codeExec	- flag to execute ( php eval() ) code
	// inline	- flag: inline data. If set $nn is treated as data, not a template file name
	function vars($nn, $vars = array(), $params = array()) {
		global $lang, $userROW, $config, $PHP_SELF;

		// Prepare to calculate exec time
		list($usec, $sec) = explode(' ', microtime());
		$timeStart = (float) $sec + (float) $usec;

		$data = (isset($params['inline']) && $params['inline'])?$nn:$this->data[$nn];
		if (isset($params['codeExec']) && $params['codeExec'])
			$data = (eval(' ?>'.$this->data[$nn].'<?php '));

		preg_match_all('/(?<=\{)l_(.*?)(?=\})/i', $data, $larr);

		foreach ($larr[0] as $k => $v) {
			$name_larr = substr($v, 2);
			//if (!isset($lang[$name_larr])) { print "[LLOST: ".$name_larr."]"; }
			$data = str_replace('{'.$v.'}', isset($lang[$name_larr])?$lang[$name_larr]:'[LANG_LOST:'.$name_larr.']', $data);
		}

		preg_match_all('/\[isplugin (.+?)\](.+?)\[\/isplugin\]/is', $data, $parr);
		foreach ($parr[0] as $k => $v) {
			//print "ISPLUG '$k' => '$v' [".$parr[1][$k]."][".$parr[2][$k]."]<br>\n";
			$data = str_replace($v,getPluginStatusActive($parr[1][$k])?$parr[2][$k]:'', $data);
		}

		preg_match_all('/\[isnplugin (.+?)\](.+?)\[\/isnplugin\]/is', $data, $parr);
		foreach ($parr[0] as $k => $v) {
			$data = str_replace($v,getPluginStatusActive($parr[1][$k])?'':$parr[2][$k], $data);
		}

		preg_match_all('/(?<=\{)plugin_(.*?)(?=\})/i', $data, $parr);

		foreach ($parr[0] as $k => $v) {
			$name_parr = substr($v, 7);
			if (preg_match('/^(.+)\_/', $name_parr, $match))
				$name_parr = $match[1];

			if (!getPluginStatusActive($name_parr)) {
				$data = str_replace('{'.$v.'}', '', $data);
			}
		}

		if ($PHP_SELF && $PHP_SELF == "admin.php") {
			preg_match_all('/(?<=\{)c_(.*?)(?=\})/i', $data, $carr);

			foreach ($carr[0] as $k => $v) {
				$name_carr = substr($v, 2);
				$data = str_replace('{'.$v.'}', $config[$name_carr], $data);
			}
		}

		if ($vars['vars']) {
			foreach ($vars['vars'] as $id => $var) {
				if (substr($id,0,1) == '[') {
					$data = str_replace($id, $var, $data);
				}
				else {
					$data = str_replace('{'.$id.'}', $var, $data);
				}
			}
		}

		if (isset($vars['regx']) && is_array($vars['regx'])) {
			foreach ($vars['regx'] as $id => $var) {
				$data = preg_replace($id, $var, $data);
			}
		}
		$data = str_replace('{skins_url}', skins_url, $data);
		$data = str_replace('{tpl_url}', tpl_url, $data);
		$data = str_replace('{admin_url}', admin_url, $data);

		if (isset($params['inline']) && $params['inline'])
			return $data;

		$this -> da_vr[$nn] = $data;

		// Save calculate exec time
		list($usec, $sec) = explode(' ', microtime());
		$timeStop = (float) $sec + (float) $usec;

		$this->execTime += ($timeStop - $timeStart);
		$this->execCount++;
	}

	function show($name) {
		$ret = $this -> da_vr[$name];
		$this -> da_vr[$name] = $this -> data[$name];
		return $ret;
	}
}
?>