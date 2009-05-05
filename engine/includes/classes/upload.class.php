<?php

//
// Copyright (C) 2006-2008 Next Generation CMS (http://ngcms.ru/)
// Name: upload.class.php
// Description: Files/Images upload managment
// Author: Vitaly Ponomarev
//

function get_item_dir($type){
	global $config;
	switch($type){
		case "image":	return $config['images_dir'];
		case "file":	return $config['files_dir'];
		case "avatar":	return $config['avatars_dir'];
		case "photo":	return $config['photos_dir'];
		default:		return false;
	}
}



class file_managment {

	// CONSTRUCTOR
	function file_managment(){
		return;
	}

	// Get limits
	function get_limits($type){
		global $config;

		$this->filetype = $type;
		switch($type){
			case "image":	$this->required_type = explode(",",str_replace(' ','',$config['images_ext']));
							$this->max_size = $config['images_max_size']*1024;
							$this->tname	= "images";
							$this->dname	= $config['images_dir'];
							$this->uname	= $config['images_url'];
							$this->tcat		= 0;
							break;
			case "file":	$this->required_type = explode(",",str_replace(' ','',$config['files_ext']));
							$this->max_size = $config['files_max_size']*1024;
							$this->tname	= "files";
							$this->dname	= $config['files_dir'];
							$this->uname	= $config['files_url'];
							$this->tcat		= 0;
							break;
			case "avatar":	$this->required_type = explode(",",str_replace(' ','',$config['images_ext']));
							$this->max_size = $config['avatar_max_size']*1024;
							$this->tname	= "images";
							$this->dname	= $config['avatars_dir'];
							$this->uname	= $config['avatars_url'];
							$this->tcat		= 1;
							break;
			case "photo":	$this->required_type = explode(",",str_replace(' ','',$config['images_ext']));
							$this->max_size = $config['photos_max_size']*1024;
							$this->tname	= "images";
							$this->dname	= $config['photos_dir'];
							$this->uname	= $config['photos_url'];
							$this->tcat		= 2;
							break;
			default:		return false;
		}
		return true;
	}

	// fetch selected URL into temp directory
	function file_fetch_url($url){
		global $lang;

		if ((!($tmpn = tempnam(ini_get('upload_tmp_dir'),'upload_')))||(!($f = fopen($tmpn, 'w')))) {
			msg(array("type" => "error", "text" => $lang['msge_tempfile']));
			return;
		}

		if ($data = @file_get_contents($url)) {
			// Data were read
			fwrite($f, $data);
			fclose($f);
			$filename = end(explode("/", $url));
			return array($tmpn, $filename, strlen($data));
		} else {
			// Unable to fetch content (URL)
		}
		return false;
	}

	// * type		- file type (image / file / avatar / photo)
	// * category	- category where to put file
	// * http_var	- name of HTTP variable to transfer file
	// * htt_varnum - number of file that is uploaded in group (via 1 variable)
	// * replace	- 'replace if present' flag
	// * randprefix	- add random prefix to file
	// * randname	- make a random file name
	// * manual		- manual upload mode. File name is sent via "manualfile"
	// *  url			- upload URL instead of file
	// *  manualfile	- file name for manual upload
	// *  manualtmp		- TEMP file where manual uploaded file is [temporally] stored
	function file_upload($param){
		global $config, $lang, $mysql, $userROW;

		//print "CALL file_upload -> upload(".$param['http_var']."//".$param['http_varnum'].")<br>\n<pre>"; var_dump($param); print "</pre><br>\n";

		$http_var		= $param['http_var'];
		$http_varnum	= intval($param['http_varnum']);

		if ($param['manual']) {
			if ($param['url']) {
				if (is_array($fetch_result = $this->file_fetch_url($param['url']))) {
					$fname = $param['manualfile']?$param['manualfile']:$fetch_result[1];	// override file name if needed
					$ftmp  = $fetch_result[0];
					$fsize = filesize($ftmp);
				} else {
					return 0;
				}
			} else {
				$fname = $param['manualfile'];
				$ftmp  = $param['manualtmp'];
				$fsize = filesize($ftmp);
			}
		} else {
			if ((is_int($http_varnum))&&is_array($_FILES[$http_var]['name'])){
				$fname	= $_FILES[$http_var]['name'][$http_varnum];
				$fsize	= $_FILES[$http_var]['size'][$http_varnum];
				$ftype	= $_FILES[$http_var]['type'][$http_varnum];
				$ftmp	= $_FILES[$http_var]['tmp_name'][$http_varnum];
				$ferr	= $_FILES[$http_var]['error'][$http_varnum];
			} else {
				// in case of one upload we may set a manual filename
				$fname	= ($param['manualfile'])?$param['manualfile']:$_FILES[$http_var]['name'];
				$fsize	= $_FILES[$http_var]['size'];
				$ftype	= $_FILES[$http_var]['type'];
				$ftmp	= $_FILES[$http_var]['tmp_name'];
				$ferr	= $_FILES[$http_var]['error'];
			}
		}
		//print "PROCESS: fname=".$fname."<br> fsize=".$fsize."<br>ftype=".$ftype."<br>ftmp=".$ftmp."<br>ferr=".$ferr."<br>\n";
		//print "Param: <pre>"; var_dump($_FILES); print "</pre><br>\n";

		// Check limits
		if (!$this->get_limits($param['type'])) {
			msg(array("type" => "error", "text" => $lang['msge_badtype']));
			return 0;
		}

		// * File size
		if ($fsize > $this->max_size) {
			msg(array("type" => "error", "text" => $lang['msge_size'], "info" => sprintf($lang['msgi_size'], Formatsize($this->max_size))));
			return 0;
		}

		// Check for existance of temp file
		if (!$ftmp || !file_exists($ftmp)) {
			msg(array("type" => "error", "text" => $lang['msge_ftmp']));
			return 0;
		}

		$fil = explode(".", strtolower($fname));
		$ext = count($fil)?array_pop($fil):'';

		// * File type
		if (array_search($ext, $this->required_type) === FALSE) {
			msg(array("type" => "error", "text" => $lang['msge_ext'], "info" => sprintf($lang['msgi_ext'], join(",",$this->required_type))));
			return;
		}
		// Process file name
		$fil = trim(str_replace(array(' ','\\','/',chr(0)),array('_', ''),join(".",$fil)));

		$parse = new parse();
		$fil = $parse->translit($fil);


		$fname = $fil.($ext?'.'.$ext:'');

		// Save original file name
		$origFname = $fname;

		// Create random prefix if requested
		$prefix = '';
		if ($param['randprefix']) {
			$try = 0;
			do {
				$prefix = sprintf("%04u",rand(1,9999));
				$try++;
			} while (($try < 100) && (file_exists($this->dname.$param['category']."/".$prefix.'_'.$fname) || (is_array($row = $mysql->record("select * from ".prefix."_".$this->tname." where name = ".db_squote($prefix.'_'.$fname)." and folder= ".db_squote($param['category']))))));

			if ($try == 100) {
				// Can't create RAND name - all values are occupied
				msg(array("type" => "error", "text" => $lang['msge_errrand']));
				return;
			}
			$fname = $prefix.'_'.$fname;
		}

		$replace_id = 0;
		// Now we have correct filename. Let's check for dups
		if (is_array($row = $mysql->record("select * from ".prefix."_".$this->tname." where name = ".db_squote($fname)." and folder= ".db_squote($param['category']))) || file_exists($this->dname.$param['category']."/".$fname)) {
			// Found file. Check if 'replace' flag is present and user have enough privilleges
			if ($param['replace']) {
				if (!(($row['user'] == $userROW['name']) || ($userROW['status'] == 1) || ($userROW['status'] == 2))) {
					msg(array("type" => "error", "text" => $lang['msge_permrepl']));
					return 0;
				}
			} else {
				msg(array("type" => "error", "text" => $lang['msge_exists'], "info" => $lang['msgi_exists']));
				return 0;
			}
			if (is_array($row))
				$replace_id = $row['id'];
		}

		// We're ready to move file into target directory
		if (!is_dir($this->dname.$param['category'])) {
			// Category dir doesn't exists
			msg(array("type" => "error", "text" => $lang['msge_catnexists']."(".$this->dname.$param['category'].")"));
			return 0;
		}


		// Now let's upload file
		if ($param['manual']) {
			if (!copy($ftmp, $this->dname.$param['category']."/".$fname)) {
				unlink($ftmp);
				msg(array("type" => "error", "text" => $lang['msge_errmove']));
				return 0;
			}
		} else {
			if (!move_uploaded_file($ftmp, $this->dname.$param['category']."/".$fname)) {
				msg(array("type" => "error", "text" => $lang['msge_errmove']. "(".$ftpm." => ".$this->dname.$param['category']."/".$fname.")"));
				return 0;
			}
		}

		// Set correct permissions
		@chmod($this->dname.$param['category']."/".$fname, 0644);

		// Create record in SQL DB (or replace old)
		if ($replace_id) {
			$mysql->query("update ".prefix."_".$this->tname." set name= ".db_squote($fname).", folder=".db_squote($param['category']).", date=unix_timestamp(now()), user=".db_squote($userROW['name']).", owner_id=".db_squote($userROW['id'])." where id = ".$replace_id);
			return array($replace_id, $fname);
		} else {
			$mysql->query("insert into ".prefix."_".$this->tname." (name, orig_name, folder, date, user, owner_id, category) values (".db_squote($fname).",".db_squote($origFname).",".db_squote($param['category']).", unix_timestamp(now()), ".db_squote($userROW['name']).",".db_squote($userROW['id']).", ".$this->tcat.")");
			$rowID = $mysql->record("select LAST_INSERT_ID() as id");
			return is_array($rowID)?array($rowID['id'], $fname):0;
		}
	}

	// Delete file
	// * type		- file type (image / file / avatar / photo)
	// * category		- category from that file should be deleted
	// * id			- ID of file to delete
	// * name		- filename to delete [if no ID specified]
	function file_delete($param){
		global $mysql, $lang, $userROW;

		// Check limits
		if (!$this->get_limits($param['type'])) {
			msg(array("type" => "error", "text" => $lang['msge_badtype']));
			return 0;
		}

		// Find file
		if ($param['id']) {
			$limit = "id = ".db_squote($param['id']);
		} else {
			if (!$param['category']) $param['category'] = 'default';
			$limit = "name = ".db_squote($param['name'])." and folder =".db_squote($param['category']);
		}


		if (is_array($row = $mysql->record("select * from ".prefix."_".$this->tname." where ".$limit))) {
			// Check permissions
			if (!(($row['owner_id'] == $userROW['id'])||($userROW['status'] == 1)||($userROW['status'] == 2))) {
				msg(array("type" => "error", "text" => $lang['msge_permdel']));
				return 0;
			}

			// Check if thumb file exists & delete it
			if ($row['preview'] && file_exists($this->dname.$row['folder'].'/thumb/'.$row['name'])) {
				if (!@unlink($this->dname.$row['folder'].'/thumb/'.$row['name'])) {
					msg(array("type" => "error", "text" => sprintf($lang['msge_delete'], $row['folder'].'/thumb/'.$row['name'])));
				}
			}

			// Check if file file exists & delete it
			if (file_exists($this->dname.$row['folder'].'/'.$row['name'])) {
				if (!@unlink($this->dname.$row['folder'].'/'.$row['name'])) {
					msg(array("type" => "error", "text" => sprintf($lang['msge_delete'], $row['folder'].'/thumb/'.$row['name'])));
					return 0;
				}
			}

			$mysql->query("delete from ".prefix."_".$this->tname." where id = ".db_squote($row['id']));
			return 1;
		} else {
			msg(array("type" => "error", "text" => $lang['msge_nofile'].", id=".$param['id']));
			return 0;
		}
	}


	// Rename a file within one category
	// * type			- file type (image / file / avatar / photo)
	// * category		- category where to put file
	// * move			- FLAG [ 1 - move mode, 0 - rename mode ]
	// * newcategory	- new category [ TO MOVE FILE ]
	// * id				- ID of file to delete
	// * name			- filename to rename [if no ID specified]
	// * newname		- new filename
	function file_rename($param) {
		global $mysql, $lang, $config, $parse;

		if (defined('DEBUG')) { print "CALL file_rename(): <pre>"; var_dump($param); print "</pre><br>\n"; }

		// Check limits
		if (!$this->get_limits($param['type'])) {
			msg(array("type" => "error", "text" => $lang['msge_badtype']));
			return 0;
		}

		// Find file
		if (!$param['category']) $param['category'] = 'default';
		if ($param['move']) {
			if (!$param['newcategory']) $param['newcategory'] = 'default';
		}

		if ($param['id']) {
			$limit = "id = ".db_squote($param['id']);
		} else {
			$limit = "name = ".db_squote($param['name'])." and folder=".db_squote($param['category']);
		}


		if (is_array($row = $mysql->record("select * from ".prefix."_".$this->tname." where ".$limit))) {

			if ($param['move']) {
				if ($param['newcategory']) {
					$param['newcategory'] = trim(str_replace(array(' ','\\','/',chr(0)),array('_', ''),$param['newcategory']));
				} else {
					$param['newcategory'] = 'default';
				}
				if (!$param['newname']) $param['newname'] = $row['name'];
			}

			$newname = trim(str_replace(array(' ','\\','/',chr(0)),array('_', ''),$param['newname']));
			$nnames = explode('.', $newname);
			$ext = array_pop($nnames);
			if (array_search($ext, $this->required_type) === FALSE) {
				msg(array("type" => "error", "text" => $lang['msge_ext'], "info" => sprintf($lang['msgi_ext'], $config['images_ext'])));
				return 0;
			}

			$newname = $parse->translit(implode(".",$nnames)).".".$ext;

			// Check for DUP
			if (is_array($mysql->record("select * from ".prefix."_".$this->tname." where folder=".db_squote($param['move']?$param['newcategory']:$row['folder'])." and name=".db_squote($newname)))) {
				msg(array("type" => "error", "text" => $lang['msge_renexists']));
				return 0;
			}

			// Check if we have enough access and all required directories are created
			if (!is_writable($this->dname.$row['folder'].'/'.$row['name'])) {
				msg(array("type" => "error", "text" => $lang['msge_permoper']));
				return 0;
			}

			if ($param['move'] && !is_dir($this->dname.$param['newcategory'])) {
				msg(array("type" => "error", "text" => $lang['msge_catnexists']));
				return 0;
			}

			if ($param['move']) {
				// MOVE action
				if (copy($this->dname.$row['folder'].'/'.$row['name'], $this->dname.$param['newcategory'].'/'.$newname)) {
					unlink($this->dname.$row['folder'].'/'.$row['name']);
					$mysql->query("update ".prefix."_".$this->tname." set name=".db_squote($newname).", orig_name=".db_squote($newname).", folder=".db_squote($param['newcategory'])." where id = ".$row['id']);
					if (file_exists($this->dname.$row['folder'].'/thumb/'.$row['name'])) {
						copy($this->dname.$row['folder'].'/thumb/'.$row['name'], $this->dname.$param['newcategory'].'/thumb/'.$newname);
						unlink($this->dname.$row['folder'].'/thumb/'.$row['name']);
					}
					return 1;
				} else {
					msg(array("type" => "error", "text" => $lang['msge_copy']));
					return 0;
				}
			} else {
				// RENAME action
				if (rename($this->dname.$row['folder'].'/'.$row['name'], $this->dname.$row['folder'].'/'.$newname)) {
					msg(array("text" => $lang['msgo_renamed']));
					$mysql->query("update ".prefix."_".$this->tname." set name=".db_squote($newname).", orig_name=".db_squote($newname)." where id = ".$row['id']);
					if (file_exists($this->dname.$row['folder'].'/thumb/'.$row['name'])) {
						rename($this->dname.$row['folder'].'/thumb/'.$row['name'], $this->dname.$row['folder'].'/thumb/'.$newname);
					}
					return 1;
				}
			}

		}
		msg(array("type" => "error", "text" => $lang['msge_rename']));
		return 0;
	}


	// Create new directory/category
	// * type		- file type (image / file / avatar / photo)
	// * category	- category where to put file
	function category_create($type, $category){
		global $lang, $parse;

		if (($dir = get_item_dir($type)) === false) {
			print "No";
			return;
		}

		$category = $parse->translit(trim(str_replace(array(' ','\\','/',chr(0)),array('_', ''),$category)));

		if (is_dir($dir.$category)) {
			msg(array("type" => "error", "text" => $lang['msge_catexists'], "info" => $lang['msgi_catexists']));
			return;
		}

		if (@mkdir($dir.$category,0777) && (($type != "image") || @mkdir($dir.$category.'/thumb', 0777))) {
			msg(array("text" => $lang['msgo_catcreated']));
		} else {
			msg(array("type" => "error", "text" => $lang['msge_create']));
		}
	}

	// Delete a category
	// * type		- file type (image / file / avatar / photo)
	// * category	- category where to put file
	function category_delete($type, $category){
		global $mysql, $lang;

		if (($dir = get_item_dir($type)) === false) {
			return;
		}
		$category = trim(str_replace(array(' ','\\','/',chr(0)),array('_', ''),$category));

		if ($category && is_dir($dir.$category)) {
			if ($this->count_dir($dir.$category)) {
				msg(array("type" => "error", "text" => $lang['msge_delfiles']));
				return;
			}
			if (is_dir($dir.$category.'/thumb')) {
				@rmdir($dir.$category.'/thumb');
			}

			if (@rmdir($dir.$category)) {
				msg(array("text" => $lang['msgo_catdeleted']));
			} else {
				msg(array("type" => "error", "text" => $lang['msge_delcat']."( '".$dir.$category."' )"));
			}
			return;
		}
		msg(array("text" => $lang['msgo_catdeleted']));
	}

	function count_dir($dir){
		if ($d = @opendir($dir)) {
			$cnt = 0;
			while(($file = readdir($d)) !== false)
				if ($file != '.' && $file != '..' && is_file($dir.'/'.$file))
					$cnt++;
			closedir($d);
			return $cnt;
		}
		return false;
	}


}



// ======================================================================= //
// Image managment class                                                   //
// ======================================================================= //
class image_managment{
	function image_managment(){
		return;
	}

	// Get image size. Return an array with params:
	// index 0 - image type (same as in getimagesize())
	// index 1 - image width
	// index 2 - image height
	function get_size($fname){
		if (is_array($info = @getimagesize($fname))) {
			return array($info[2], $info[0], $info[1]);
		}
		return NULL;
	}

	function create_thumb($dir, $file, $sizeX, $sizeY, $quality = 0){
		global $lang;
		$fname = $dir.'/'.$file;

		//print "CALL create_thumb($dir, $file, $sizeX, $sizeY)<br>\n";

		// Check if we have a directory for thumb
		if (!is_dir($dir.'/thumb')) {
			if (!@mkdir($dir.'/thumb', 0777)) {
			msg(array("type" => "error", "text" => $lang['msge_thumbdir']));
			return;
			}
		}

		// Check if file exists and we can get it's image size
		if (!file_exists($fname) || !is_array($sz=@getimagesize($fname))) {
			return 0;
		}
		$origX	= $sz[0];
		$origY	= $sz[1];
		$origType	= $sz[2];

		if (!(($sizeX>0) && ($sizeY>0) && ($origX>0) && ($origY>0))) {
			return;
		}

		// Calculate resize factor
		$factor = max ($origX / $sizeX, $origY / $sizeY);

		// Don't enlarge picture without need
		if ($factor < 1) $factor = 1;

		// Check if we can open this type of image and open it
		$cmd = 'imagecreatefrom';
		switch ($origType) {
			case 1: $cmd .= 'gif';	break;
			case 2: $cmd .= 'jpeg';	break;
			case 3: $cmd .= 'png';	break;
			case 6: $cmd .= 'bmp';	break;
		}

		if (!$cmd || !function_exists($cmd)) {
			msg(array("type" => "error", "text" => str_replace('{func}', $cmd, $lang['msge_unsuppthumb'])));
			return;
		}

		switch ($origType) {
			case 1: $img = @imagecreatefromgif($fname);	break;
			case 2: $img = @imagecreatefromjpeg($fname);	break;
			case 3: $img = @imagecreatefrompng($fname);	break;
			case 6: $img = @imagecreatefrombmp($fname);	break;
		}

		if (!$img) {
			msg(array("type" => "error", "text" => $lang['msge_imgopenerr']));
			return;
		}

		// Calculate thumb size and create an empty object for it
		$newX = round($origX / $factor);
		$newY = round($origY / $factor);

		$newimg = imagecreatetruecolor($newX, $newY);

		// Prepare for transparency
		$oTColor = imagecolortransparent($img);
		if ($oTColor >= 0 && $oTColor < imagecolorstotal($img)) {
			$TColor = imagecolorsforindex($img, $oTColor);
			$nTColor = imagecolorallocate($newimg, $TColor['red'], $TColor['green'], $TColor['blue']);
			imagefill($newimg, 0, 0, $nTColor);
			imagecolortransparent($newimg, $nTColor);
		}

		// Resize image
		imagecopyresampled($newimg, $img, 0,0,0,0,$newX, $newY, $origX, $origY);

		// Try to write resized image
		switch ($origType) {
			case 1: $res = @imagegif($newimg, $dir.'/thumb/'.$file);		break;
			case 2: $res = @imagejpeg($newimg, $dir.'/thumb/'.$file, ($quality>=10 && $quality<=100)?$quality:80);		break;
			case 3: $res = @imagepng($newimg, $dir.'/thumb/'.$file);		break;
			case 6: $res = @imagebmp($newimg, $dir.'/thumb/'.$file);		break;
		}

		// Set correct permissions to file
		@chmod($dir.'/thumb/'.$file, 0644);

		if (!$res) {
			msg(array("type" => "error", "text" => $lang['msge_thumbcreate']));
			return;
		}
		return 1;
	}


	// Transformate original image
	// * image			- filename of original image
	// * stamp			- FLAG if we need to add a stamp
	// ** stampfile		- filename of stamp file
	// ** stamp_transparency - %% of transparency of added stamp [ default: 40 ]
	// * shadow			- FLAG if we need to add a shadow
	// * outquality		- with what quality we should write resulting file (for JPEG) [ default: 80 ]
	// * outfile		- filename to write a result [ default: original file ]
	function image_transform($param){
	//function add_stamp($image, $stamp, $transparency = 40, $quality = 80){
		global $config, $lang;

		// LOAD ORIGINAL IMAGE
		// Check if file exists and we can get it's image size
		if (!file_exists($param['image']) || !is_array($sz=@getimagesize($param['image']))) {
			return 0;
		}

		$origX	= $sz[0];
		$origY	= $sz[1];
		$origType	= $sz[2];

		// Check if we can open this type of image and open it
		$cmd = 'imagecreatefrom';
		switch ($origType) {
			case 1: $cmd .= 'gif';	break;
			case 2: $cmd .= 'jpeg';	break;
			case 3: $cmd .= 'png';	break;
			case 6: $cmd .= 'bmp';	break;
		}

		if (!$cmd || !function_exists($cmd)) {
			msg(array("type" => "error", "text" => $lang['msge_unsuppstamp']));
			return;
		}

		switch ($origType) {
			case 1: $img = @imagecreatefromgif($param['image']);	break;
			case 2: $img = @imagecreatefromjpeg($param['image']);	break;
			case 3: $img = @imagecreatefrompng($param['image']);	break;
			case 6: $img = @imagecreatefrombmp($param['image']);	break;
		}

		if (!$img) {
			msg(array("type" => "error", "text" => $lang['msge_imgopenerr']));
			return;
		}

		if ($param['stamp']) {
			// LOAD STAMP IMAGE
			if (!file_exists($param['stampfile']) || !is_array($sz=@getimagesize($param['stampfile']))) {
				return 0;
			}

			$stampX	= $sz[0];
			$stampY	= $sz[1];
			$stampType	= $sz[2];

			// Check if we can open this type of image and open it
			$cmd = 'imagecreatefrom';
			switch ($origType) {
				case 1: $cmd .= 'gif';	break;
				case 2: $cmd .= 'jpeg';	break;
				case 3: $cmd .= 'png';	break;
				case 6: $cmd .= 'bmp';	break;
			}

			if (!$cmd || !function_exists($cmd)) {
				msg(array("type" => "error", "text" => $lang['msge_unsuppstamp']));
				return;
			}

			switch ($stampType) {
				case 1: $stamp = @imagecreatefromgif($param['stampfile']);	break;
				case 2: $stamp = @imagecreatefromjpeg($param['stampfile']);	break;
				case 3: $stamp = @imagecreatefrompng($param['stampfile']);	break;
				case 6: $stamp = @imagecreatefrombmp($param['stampfile']);	break;
			}

			if (!$stamp) {
				msg(array("type" => "error", "text" => $lang['msge_openstamp']));
				return;
			}

			// BOTH FILES ARE LOADED
			$destX = $origX - $stampX - 10;
			$destY = $origY - $stampY - 10;
			if (($destX<0)||($destY<0)) {
				msg(array("type" => "error", "text" => $lang['msge_stampimgsml']));
				return;
			}

			if (($param['stamp_transparency'] < 1) || ($param['stamp_transparency'] > 100)) {
				$param['stamp_transparency'] = 40;
			}

			imageCopyMerge($img, $stamp, $destX, $destY, 0, 0, $stampX, $stampY, $param['stamp_transparency']);
		}


		if ($param['shadow']) {
			$newX			=	$origX + 5;
			$newY			=	$origY + 5;
			$newimg			=	imagecreatetruecolor($newX, $newY);


			$background		=	array("r" => 255, "g" => 255, "b" => 255);
			$step_offset	=	array("r" => ($background["r"] / 10), "g" => ($background["g"] / 10), "b" => ($background["b"] / 10));
			$current_color	=	$background;

			for ($i = 0; $i <= 5; $i++) {
				$colors[$i] = @imagecolorallocate($newimg, round($current_color["r"]), round($current_color["g"]), round($current_color["b"]));
				$current_color["r"] -= $step_offset["r"];
				$current_color["g"] -= $step_offset["g"];
				$current_color["b"] -= $step_offset["b"];
			}

			imagefilledrectangle($newimg, 0,0, $newX, $newY, $colors[0]);

			for ($i = 0; $i <= 5; $i++) {
				@imagefilledrectangle($newimg, 5, 5, $newX - $i, $newY - $i, $colors[$i]);
			}
			imagecopymerge($newimg, $img, 0, 0, 0, 0, $origX, $origY, 100);
			$img = $newimg;
		}

		// WRITE A RESULT FILE
		if (($param['outquality'] < 10)||($param['outquality'] > 100)) {
			$param['outquality'] = 80;
		}

		if (!$param['outfile']) $param['outfile'] = $param['image'];

		switch ($origType) {
			case 1: $res = @imagegif($img,  $param['outfile']);		break;
			case 2: $res = @imagejpeg($img, $param['outfile'], $param['outquality']);		break;
			case 3: $res = @imagepng($img,  $param['outfile']);		break;
			case 6: $res = @imagebmp($img,  $param['outfile']);		break;
		}
		if (!$res) {
			msg(array("type" => "error", "text" => $lang['msge_stampcreate']));
			return;
		}
		return 1;
	}
}
