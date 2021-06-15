<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: upload.class.php
// Description: Files/Images upload managment
// Author: Vitaly Ponomarev
//

function get_item_dir($type)
{
    global $config;
    switch ($type) {
        case 'image':
            return $config['images_dir'];
        case 'file':
            return $config['files_dir'];
        case 'avatar':
            return $config['avatars_dir'];
        case 'photo':
            return $config['photos_dir'];
        default:
            return false;
    }
}

class file_managment
{
    // CONSTRUCTOR
    public function __construct()
    {
        // Load additional LANG file
        $lang = loadLang('files');
    }

    // Get limits
    public function get_limits($type)
    {
        global $config;

        $this->filetype = $type;
        switch ($type) {
            case 'image':
                $this->required_type = explode(',', str_replace(' ', '', $config['images_ext']));
                $this->max_size = $config['images_max_size'] * 1024;
                $this->max_x = intval($config['images_max_x']);
                $this->max_y = intval($config['images_max_y']);
                $this->dim_act = intval($config['images_dim_action']);
                $this->tname = 'images';
                $this->dname = $config['images_dir'];
                $this->uname = $config['images_url'];
                $this->tcat = 0;
                break;
            case 'file':
                $this->required_type = explode(',', str_replace(' ', '', $config['files_ext']));
                $this->max_size = $config['files_max_size'] * 1024;
                $this->tname = 'files';
                $this->dname = $config['files_dir'];
                $this->uname = $config['files_url'];
                $this->tcat = 0;
                break;
            case 'avatar':
                $this->required_type = explode(',', str_replace(' ', '', $config['images_ext']));
                $this->max_size = $config['avatar_max_size'] * 1024;
                $this->tname = 'images';
                $this->dname = $config['avatars_dir'];
                $this->uname = $config['avatars_url'];
                $this->tcat = 1;
                break;
            case 'photo':
                $this->required_type = explode(',', str_replace(' ', '', $config['images_ext']));
                $this->max_size = $config['photos_max_size'] * 1024;
                $this->tname = 'images';
                $this->dname = $config['photos_dir'];
                $this->uname = $config['photos_url'];
                $this->tcat = 2;
                break;
            default:
                return false;
        }

        return true;
    }

    // fetch selected URL into temp directory
    public function file_fetch_url($url)
    {
        global $lang;

        if ((!($tmpn = tempnam(ini_get('upload_tmp_dir'), 'upload_'))) || (!($f = fopen($tmpn, 'w')))) {
            msg(['type' => 'error', 'text' => $lang['upload.error.tempcreate']]);

            return;
        }

        if ($data = @file_get_contents($url)) {
            // Data were read
            fwrite($f, $data);
            fclose($f);
            $filename = end(explode('/', $url));

            return [$tmpn, $filename, mb_strlen($data)];
        } else {
            // Unable to fetch content (URL)
        }

        return false;
    }

    // * type		- file type (image / file / avatar / photo)
    // * category	- category where to put file
    // * http_var	- name of HTTP variable to transfer file
    // * htt_varnum - number of file that is uploaded in group (via 1 variable)
    // * dsn		- FLAG: store data (files/images) in Data Storage Network (BTREE)
    // *** IS SET:
    //   * linked_ds - id of data storage to link this file
    //   * linked_id - id of item in data stodage to link this file
    // *** IS NOT SET:
    //   * replace	- 'replace if present' flag
    //   * randprefix	- add random prefix to file
    //   * randname	- make a random file name
    // * manual		- manual upload mode. File name is sent via "manualfile"
    // *  url			- upload URL instead of file
    // *  manualfile	- file name for manual upload
    // *  manualtmp		- TEMP file where manual uploaded file is [temporally] stored
    // * plugin		- ID of plugin that owns this file
    // * pidentity	- ID of plugin's identity that owns this file
    // * description- description for image
    // * rpc		- flag: if set, returning result is made in RPC style [ default - not set ]
    public function file_upload($param)
    {
        global $config, $lang, $mysql, $userROW;

        $lang = loadLang('files');

        // Normalize category (to make it possible to have empty category)
        $wCategory = ($param['category'] != '') ? ($param['category'].'/') : '';

        //print "CALL file_upload -> upload(".$param['http_var']."//".$param['http_varnum'].")<br>\n<pre>"; var_dump($param); print "</pre><br>\n";

        $http_var = getIsSet($param['http_var']);
        $http_varnum = intval(getIsSet($param['http_varnum']));

        if ($param['manual']) {
            if ($param['url']) {
                if (is_array($fetch_result = $this->file_fetch_url($param['url']))) {
                    $fname = $param['manualfile'] ? $param['manualfile'] : $fetch_result[1];    // override file name if needed
                    $ftmp = $fetch_result[0];
                    $fsize = filesize($ftmp);
                } else {
                    return 0;
                }
            } else {
                $fname = getIsSet($param['manualfile']);
                $ftmp = getIsSet($param['manualtmp']);
                $fsize = filesize($ftmp);
            }
        } else {
            if ((is_int($http_varnum)) && is_array($_FILES[$http_var]['name'])) {
                $fname = $_FILES[$http_var]['name'][$http_varnum];
                $fsize = $_FILES[$http_var]['size'][$http_varnum];
                $ftype = $_FILES[$http_var]['type'][$http_varnum];
                $ftmp = $_FILES[$http_var]['tmp_name'][$http_varnum];
                $ferr = $_FILES[$http_var]['error'][$http_varnum];
            } else {
                // in case of one upload we may set a manual filename
                $fname = ($param['manualfile']) ? $param['manualfile'] : $_FILES[$http_var]['name'];
                $fsize = $_FILES[$http_var]['size'];
                $ftype = $_FILES[$http_var]['type'];
                $ftmp = $_FILES[$http_var]['tmp_name'];
                $ferr = $_FILES[$http_var]['error'];
            }
        }
        // Check limits
        if (!$this->get_limits($param['type'])) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 301, 'errorText' => str_replace('{fname}', $fname, $lang['upload.error.type'])];
            } else {
                msg(['type' => 'error', 'text' => str_replace('{fname}', $fname, $lang['upload.error.type'])]);

                return 0;
            }
        }

        //print "PROCESS: fname=".$fname."<br> fsize=".$fsize."<br>ftype=".$ftype."<br>ftmp=".$ftmp."<br>ferr=".$ferr."<br>this->dname=".$this->dname."<br/>\n";
        //print "Param: <pre>"; var_dump($_FILES); print "</pre><br>\n";

        // * File size
        if ($fsize > $this->max_size) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 302, 'errorText' => str_replace('{fname}', $fname, $lang['upload.error.size']), 'errorDescription' => str_replace('{size}', Formatsize($this->max_size), $lang['upload.error.size#info'])];
            } else {
                msg(['type' => 'error', 'text' => str_replace('{fname}', $fname, $lang['upload.error.size']), 'info' => str_replace('{size}', Formatsize($this->max_size), $lang['upload.error.size#info'])]);

                return 0;
            }
        }

        // Check for existance of temp file
        if (!$ftmp || !file_exists($ftmp)) {
            if (getIsSet($param['rpc'])) {
                return ['status' => 0, 'errorCode' => 303, 'errorText' => var_export($_FILES, true).str_replace('{fname}', $fname, $lang['upload.error.losttemp'])];
            } else {
                msg(['type' => 'error', 'text' => str_replace('{fname}', $fname, $lang['upload.error.losttemp'])]);

                return 0;
            }
        }

        // ** IMAGES :: Check maximum image size & resize if needed
        if ($param['type'] == 'image') {
            $im = new image_managment();
            $s = $im->get_size($ftmp);
            if (!is_array($s)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 301, 'errorText' => str_replace('{fname}', $fname, $lang['upload.error.type'])];
                } else {
                    msg(['type' => 'error', 'text' => str_replace('{fname}', $fname, $lang['upload.error.type'])]);

                    return 0;
                }
            }

            // Check size
            if ((($this->max_x > 0) && ($s[1] > $this->max_x)) || (($this->max_y > 0) && ($s[2] > $this->max_y))) {
                // !! OVERSIZED !!
                if (!$this->dim_act) {
                    // REJECT
                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 317, 'errorText' => str_replace(['{fname}', '{maxx}', '{maxy}'], [$fname, $this->max_x, $this->max_y], $lang['upload.error.imgsize'])];
                    } else {
                        msg(['type' => 'error', 'text' => str_replace('{fname}', $fname, $lang['upload.error.imgsize'])]);

                        return 0;
                    }
                }
                // Try to resize
                $resizeResult = $im->image_transform(['rpc' => $param['rpc'], 'image' => $ftmp, 'resize' => ['x' => $this->max_x, 'y' => $this->max_y]]);
                //return array('status' => 0, 'errorCode' => 999, 'errorText' => "X/MAXX::".$s[1]."/".$this->max_x.", Y/MAXY:".$s[2]."/".$this->max_y." NX/NY:".$resizeResult['data']['x']."/".$resizeResult['data']['y']);

                // Check results
                // ** RPC
                if ($param['rpc'] && ((!is_array($resizeResult)) || (!$resizeResult['status']))) {
                    return $resizeResult;
                }
                // ** Normal call
                if (!is_array($resizeResult)) {
                    msg(['type' => 'error', 'text' => str_replace('{fname}', $fname, $lang['upload.error.imgsize'])]);

                    return;
                }
            }
        }

        $fil = explode('.', mb_strtolower($fname));
        $ext = count($fil) ? array_pop($fil) : '';

        // * File type [ don't allow to upload PHP files in any case ]
        if ((array_search(mb_strtolower($ext), $this->required_type) === false) || (array_search(mb_strtolower($ext), ['php', 'pht', 'phtml', 'php3', 'php4', 'php5']) !== false)) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 304, 'errorText' => str_replace('{fname}', $fname, $lang['upload.error.ext']), 'errorDescription' => str_replace('{ext}', implode(', ', $this->required_type), $lang['upload.error.ext#info'])];
            } else {
                msg(['type' => 'error', 'text' => str_replace('{fname}', $fname, $lang['upload.error.ext']), 'info' => str_replace('{ext}', implode(',', $this->required_type), $lang['upload.error.ext#info'])]);

                return 0;
            }
        }

        // Manage multiple extensions
        if (!$config['allow_multiext']) {
            $fil = [implode('_', $fil)];
        }

        $parse = new parse();

        $fil = trim(str_replace([' ', '\\', '/', chr(0)], ['_', ''], implode('.', $fil)));
        $fil = $parse->translit($fil);

        $fname = $fil.($ext ? '.'.$ext : '');

        // Save original file name
        $origFname = $fname;

        // DSN - Data Storage Network. Store data in BTREE if requested
        if ($param['dsn']) {
            // Check if directory for DSN exists
            $wDir = $config['attach_dir'];

            if (!is_dir($wDir)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 305, 'errorText' => str_replace('{dir}', $wDir, $lang['upload.error.dsn'])];
                } else {
                    msg(['type' => 'error', 'text' => 'No access to DSN directory `'.$wDir.'`']);

                    return 0;
                }
            }

            // Determine storage tree
            $fn_md5 = md5($fname);
            $dir1 = mb_substr($fn_md5, 0, 2);
            $dir2 = mb_substr($fn_md5, 2, 2);

            $wDir .= '/'.$dir1;
            if (!is_dir($wDir) && !@mkdir($wDir, 0777)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 306, 'errorText' => str_replace('{dir}', $wDir, $lang['upload.error.dircreate'])];
                } else {
                    msg(['type' => 'error', 'text' => str_replace('{dir}', $wDir, $lang['upload.error.dircreate'])]);

                    return 0;
                }
            }

            $wDir .= '/'.$dir2;
            if (!is_dir($wDir) && !@mkdir($wDir, 0777)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 307, 'errorText' => str_replace('{dir}', $wDir, $lang['upload.error.dircreate'])];
                } else {
                    msg(['type' => 'error', 'text' => str_replace('{dir}', $wDir, $lang['upload.error.dircreate'])]);

                    return 0;
                }
            }

            // Now let's find empty slot
            $i = 0;
            $xDir = '';
            while ($i < 999) {
                $i++;
                $xDir = sprintf('%03u', $i);
                if (is_dir($wDir.'/'.$xDir)) {
                    $xDir = '';
                    continue;
                }

                // Fine. Create this dir ... but check for simultaneous run
                if (!@mkdir($wDir.'/'.$xDir, 0777)) {
                    if (is_dir($wDir.'/'.$xDir)) {
                        continue;
                    }

                    // Unable to create dir
                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 308, 'errorText' => str_replace('{dir}', $wDir.'/'.$xDir, $lang['upload.error.dircreate'])];
                    } else {
                        msg(['type' => 'error', 'text' => str_replace('{dir}', $wDir.'/'.$xDir, $lang['upload.error.dircreate'])]);

                        return 0;
                    }
                } else {
                    break;
                }
            }
            if (!$xDir) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 309, 'errorText' => str_replace('{dir}', $wDir, $lang['upload.error.dsn_no_slots'])];
                } else {
                    msg(['type' => 'error', 'text' => str_replace('{dir}', $wDir, $lang['upload.error.dsn_no_slots'])]);

                    return 0;
                }
            }

            $wDir .= '/'.$xDir;

            // Now let's upload file
            if ($param['manual']) {
                if (!copy($ftmp, $wDir.'/'.$fname)) {
                    // Remove empty dir
                    rmdir($wDir);

                    // Delete file
                    unlink($ftmp);

                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 310, 'errorText' => $lang['upload.error.move']];
                    } else {
                        msg(['type' => 'error', 'text' => $lang['upload.error.move']]);

                        return 0;
                    }
                }
            } else {
                if (!move_uploaded_file($ftmp, $wDir.'/'.$fname)) {
                    // Remove empty dir
                    rmdir($wDir);

                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 311, 'errorText' => $lang['upload.error.move'].'('.$ftmp.' => '.$this->dname.$wCategory.$fname.')'];
                    } else {
                        msg(['type' => 'error', 'text' => $lang['upload.error.move'].'('.$ftmp.' => '.$this->dname.$wCategory.$fname.')']);

                        return 0;
                    }
                }
            }

            // Set correct permissions
            chmod($wDir.'/'.$fname, 0644);

            // Create record in SQL DB (or replace old)
            $mysql->query('insert into '.prefix.'_'.$this->tname.' '.
                '(name, storage, orig_name, folder, date, user, owner_id, category, linked_ds, linked_id, plugin, pidentity, description) '.
                'values ('.db_squote($fname).', 1,'.db_squote($origFname).','.db_squote($dir1.'/'.$dir2.'/'.$xDir).', unix_timestamp(now()), '.db_squote($userROW['name']).','.db_squote($userROW['id']).', '.$this->tcat.', '.db_squote($param['linked_ds']).', '.db_squote($param['linked_id']).', '.db_squote($param['plugin']).', '.db_squote($param['pidentity']).', '.db_squote($param['description']).')');
            $rowID = $mysql->record('select LAST_INSERT_ID() as id');

            // SQL error
            if (!is_array($rowID)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 312, 'errorText' => $lang['upload.error.sql']];
                } else {
                    msg(['type' => 'error', 'text' => $lang['upload.error.sql']]);

                    return 0;
                }
            }
            if ($param['rpc']) {
                return ['status' => 1, 'errorCode' => 0, 'errorText' => $lang['upload.complete'], 'data' => ['id' => $rowID['id'], 'name' => $fname, 'location' => $dir1.'/'.$dir2.'/'.$xDir]];
            } else {
                return [$rowID['id'], $fname, $dir1.'/'.$dir2.'/'.$xDir];
            }
        }

        // Create random prefix if requested
        $prefix = '';
        if ($param['randprefix']) {
            $try = 0;
            do {
                $prefix = sprintf('%04u', rand(1, 9999));
                $try++;
            } while (($try < 100) && (file_exists($this->dname.$wCategory.$prefix.'_'.$fname) || (is_array($row = $mysql->record('select * from '.prefix.'_'.$this->tname.' where name = '.db_squote($prefix.'_'.$fname).' and folder= '.db_squote($param['category']))))));

            if ($try == 100) {
                // Can't create RAND name - all values are occupied
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 313, 'errorText' => $lang['upload.error.rand']];
                } else {
                    msg(['type' => 'error', 'text' => $lang['upload.error.rand']]);

                    return 0;
                }
            }
            $fname = $prefix.'_'.$fname;
        }

        $replace_id = 0;
        $row = '';
        // Now we have correct filename. Let's check for dups
        if (is_array($row = $mysql->record('select * from '.prefix.'_'.$this->tname.' where name = '.db_squote($fname).' and folder= '.db_squote($param['category']))) || file_exists($this->dname.$wCategory.$fname)) {
            // Found file. Check if 'replace' flag is present and user have enough privilleges
            if ($param['replace']) {
                if (!(($row['user'] == $userROW['name']) || ($userROW['status'] == 1) || ($userROW['status'] == 2))) {
                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 314, 'errorText' => $lang['upload.error.perm.replace']];
                    } else {
                        msg(['type' => 'error', 'text' => $lang['upload.error.perm.replace']]);

                        return 0;
                    }
                }
            } else {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 315, 'errorText' => $lang['upload.error.exists'], 'errorDescription' => $lang['upload.error.exists#info']];
                } else {
                    msg(['type' => 'error', 'text' => $lang['upload.error.exists'], 'info' => $lang['upload.error.exists#info']]);

                    return 0;
                }
            }
            if (is_array($row)) {
                $replace_id = $row['id'];
            }
        }

        // We're ready to move file into target directory
        if (!is_dir($this->dname.$param['category'])) {
            // SPECIAL processing for "default" category
            if ($param['category'] == 'default') {
                @mkdir($this->dname.$param['category'], 0777);
                if ($param['type'] == 'image') {
                    @mkdir($this->dname.$subdirectory.'/thumb', 0777);
                }
            } else {
                // Category dir doesn't exists
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 316, 'errorText' => str_replace('{category}', $param['category'], $lang['upload.error.catnexists'])];
                } else {
                    msg(['type' => 'error', 'text' => str_replace('{category}', $param['category'], $lang['upload.error.catnexists'])]);

                    return 0;
                }
            }
        }

        // Now let's upload file
        if ($param['manual']) {
            if (!copy($ftmp, $this->dname.$wCategory.$fname)) {
                unlink($ftmp);

                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 310, 'errorText' => $lang['upload.error.move']];
                } else {
                    msg(['type' => 'error', 'text' => $lang['upload.error.move']]);

                    return 0;
                }
            }
        } else {
            if (!move_uploaded_file($ftmp, $this->dname.$wCategory.$fname)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 310, 'errorText' => $lang['upload.error.move'].'('.$ftmp.' => '.$this->dname.$wCategory.$fname.')'];
                } else {
                    msg(['type' => 'error', 'text' => $lang['upload.error.move'].'('.$ftmp.' => '.$this->dname.$wCategory.$fname.')']);

                    return 0;
                }
            }
        }

        // Set correct permissions
        chmod($this->dname.$wCategory.$fname, 0644);

        // Create record in SQL DB (or replace old)
        if ($replace_id) {
            // Delete old THUMB (if exists)
            if ($row['preview'] && ($param['type'] == 'image') && is_file($this->dname.$param['category'].'/thumb/'.$row['name'])) {
                @unlink($this->dname.$param['category'].'/thumb/'.$row['name']);
            }

            $mysql->query('update '.prefix.'_'.$this->tname.' set '.
                'name= '.db_squote($fname).', '.
                'folder='.db_squote($param['category']).', '.
                'date=unix_timestamp(now()), '.
                'user='.db_squote($userROW['name']).', '.
                'owner_id='.db_squote($userROW['id']).
                (($param['type'] == 'image') ? ', preview = 0, p_width = 0, p_height = 0' : '').
                ' where id = '.$replace_id);
            if ($param['rpc']) {
                return ['status' => 1, 'errorCode' => 0, 'errorText' => $lang['upload.complete'], 'data' => ['id' => $replace_id, 'name' => $fname, 'category' => $wCategory]];
            } else {
                return [$replace_id, $fname, $wCategory];
            }
        } else {
            $mysql->query('insert into '.prefix.'_'.$this->tname.' (name, orig_name, folder, date, user, owner_id, category) values ('.db_squote($fname).','.db_squote($origFname).','.db_squote($param['category']).', unix_timestamp(now()), '.db_squote($userROW['name']).','.db_squote($userROW['id']).', '.$this->tcat.')');
            $rowID = $mysql->record('select LAST_INSERT_ID() as id');

            // SQL error
            if (!is_array($rowID)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 312, 'errorText' => $lang['upload.error.sql']];
                } else {
                    msg(['type' => 'error', 'text' => $lang['upload.error.sql']]);

                    return 0;
                }
            }
            if ($param['rpc']) {
                return ['status' => 1, 'errorCode' => 0, 'errorText' => $lang['upload.complete'], 'data' => ['id' => $rowID['id'], 'name' => $fname, 'category' => $wCategory]];
            } else {
                return [$rowID['id'], $fname, $wCategory];
            }
        }
    }

    // Delete file
    // * type		- file type (image / file / avatar / photo)
    // * category		- category from that file should be deleted
    // * id			- ID of file to delete
    // * name		- filename to delete [if no ID specified]
    public function file_delete($param)
    {
        global $mysql, $lang, $userROW, $config;

        // Check limits
        if (!$this->get_limits($param['type'])) {
            msg(['type' => 'error', 'text' => $lang['upload.error.type']]);

            return 0;
        }

        // Find file
        if ($param['id']) {
            $limit = 'id = '.db_squote($param['id']);
        } else {
            if (!$param['category']) {
                $param['category'] = 'default';
            }
            $limit = 'name = '.db_squote($param['name']).' and folder ='.db_squote($param['category']);
        }

        if (is_array($row = $mysql->record('select * from '.prefix.'_'.$this->tname.' where '.$limit))) {
            // Check permissions
            if (!(($row['owner_id'] == $userROW['id']) || ($userROW['status'] == 1) || ($userROW['status'] == 2))) {
                msg(['type' => 'error', 'text' => $lang['upload.error.perm.delete']]);

                return 0;
            }

            $storageDir = ($row['storage'] ? $config['attach_dir'] : $this->dname).$row['folder'];

            // Check if thumb file exists & delete it
            if ($row['preview'] && file_exists($storageDir.'/thumb/'.$row['name'])) {
                if (!@unlink($storageDir.'/thumb/'.$row['name'])) {
                    msg(['type' => 'error', 'text' => str_replace('{file}', $row['folder'].'/thumb/'.$row['name'], $lang['upload.error.delete'])]);
                }
            }

            // Check if file file exists & delete it
            if (file_exists($storageDir.'/'.$row['name'])) {
                if (!@unlink($storageDir.'/'.$row['name'])) {
                    msg(['type' => 'error', 'text' => str_replace('{file}', $row['folder'].'/'.$row['name'], $lang['upload.error.delete'])]);

                    return 0;
                }
                // Now try to delete empty storage directory [ ONLY for DSN ]
                if ($row['storage']) {
                    @rmdir($storageDir);
                }
            }

            $mysql->query('delete from '.prefix.'_'.$this->tname.' where id = '.db_squote($row['id']));

            return 1;
        } else {
            msg(['type' => 'error', 'text' => $lang['upload.error.nofile'].', id='.$param['id']]);

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
    public function file_rename($param)
    {
        global $mysql, $lang, $config, $parse;

        if (defined('DEBUG')) {
            echo 'CALL file_rename(): <pre>';
            var_dump($param);
            echo "</pre><br>\n";
        }

        // Check limits
        if (!$this->get_limits($param['type'])) {
            msg(['type' => 'error', 'text' => $lang['upload.error.type']]);

            return 0;
        }

        // Find file
        if (!$param['category']) {
            $param['category'] = 'default';
        }
        if ($param['move']) {
            if (!$param['newcategory']) {
                $param['newcategory'] = 'default';
            }
        }

        if ($param['id']) {
            $limit = 'id = '.db_squote($param['id']);
        } else {
            $limit = 'name = '.db_squote($param['name']).' and folder='.db_squote($param['category']);
        }

        if (is_array($row = $mysql->record('select * from '.prefix.'_'.$this->tname.' where '.$limit))) {
            if ($param['move']) {
                if ($param['newcategory']) {
                    $param['newcategory'] = trim(str_replace([' ', '\\', '/', chr(0)], ['_', ''], $param['newcategory']));
                } else {
                    $param['newcategory'] = 'default';
                }
                if (!$param['newname']) {
                    $param['newname'] = $row['name'];
                }
            }

            $newname = trim(str_replace([' ', '\\', '/', chr(0)], ['-', ''], $param['newname']));
            $nnames = explode('.', $newname);
            $ext = array_pop($nnames);
            if (array_search($ext, $this->required_type) === false) {
                msg(['type' => 'error', 'text' => $lang['upload.error.ext'], 'info' => str_replace('{ext}', implode(',', $this->required_type), $lang['upload.error.ext#info'])]);

                return 0;
            }

            $newname = $parse->translit(implode('.', $nnames)).'.'.$ext;

            // Check for DUP
            if (is_array($mysql->record('select * from '.prefix.'_'.$this->tname.' where folder='.db_squote($param['move'] ? $param['newcategory'] : $row['folder']).' and name='.db_squote($newname)))) {
                msg(['type' => 'error', 'text' => $lang['upload.error.renexists']]);

                return 0;
            }

            // Check if we have enough access and all required directories are created
            if (!is_writable($this->dname.$row['folder'].'/'.$row['name'])) {
                msg(['type' => 'error', 'text' => $lang['upload.error.sysperm.access']]);

                return 0;
            }

            if ($param['move'] && !is_dir($this->dname.$param['newcategory'])) {
                msg(['type' => 'error', 'text' => str_replace('{category}', $param['newcategory'], $lang['upload.error.catnexists'])]);

                return 0;
            }

            if ($param['move']) {
                // MOVE action
                if (copy($this->dname.$row['folder'].'/'.$row['name'], $this->dname.$param['newcategory'].'/'.$newname)) {
                    unlink($this->dname.$row['folder'].'/'.$row['name']);
                    $mysql->query('update '.prefix.'_'.$this->tname.' set name='.db_squote($newname).', orig_name='.db_squote($newname).', folder='.db_squote($param['newcategory']).' where id = '.$row['id']);
                    if (file_exists($this->dname.$row['folder'].'/thumb/'.$row['name'])) {
                        copy($this->dname.$row['folder'].'/thumb/'.$row['name'], $this->dname.$param['newcategory'].'/thumb/'.$newname);
                        unlink($this->dname.$row['folder'].'/thumb/'.$row['name']);
                    }

                    return 1;
                } else {
                    msg(['type' => 'error', 'text' => $lang['upload.error.copy']]);

                    return 0;
                }
            } else {
                // RENAME action
                if (rename($this->dname.$row['folder'].'/'.$row['name'], $this->dname.$row['folder'].'/'.$newname)) {
                    msg(['text' => $lang['upload.renamed']]);
                    $mysql->query('update '.prefix.'_'.$this->tname.' set name='.db_squote($newname).', orig_name='.db_squote($newname).' where id = '.$row['id']);
                    if (file_exists($this->dname.$row['folder'].'/thumb/'.$row['name'])) {
                        rename($this->dname.$row['folder'].'/thumb/'.$row['name'], $this->dname.$row['folder'].'/thumb/'.$newname);
                    }

                    return 1;
                }
            }
        }
        msg(['type' => 'error', 'text' => $lang['upload.error.rename']]);

        return 0;
    }

    // Create new directory/category
    // * type		- file type (image / file / avatar / photo)
    // * category	- category where to put file
    public function category_create($type, $category)
    {
        global $lang, $parse;

        if (($dir = get_item_dir($type)) === false) {
            echo 'No';

            return;
        }

        $category = $parse->translit(trim(str_replace([' ', '\\', '/', chr(0)], ['-', ''], $category)));
        if (is_dir($dir.$category)) {
            msg(['type' => 'error', 'text' => $lang['upload.error.catexists'], 'info' => $lang['upload.error.catexists#info']]);

            return;
        }

        if (@mkdir($dir.$category, 0777) && (($type != 'image') || @mkdir($dir.$category.'/thumb', 0777))) {
            msg(['text' => $lang['upload.catcreated']]);
        } else {
            msg(['type' => 'error', 'text' => $lang['upload.error.catcreate']]);
        }
    }

    // Delete a category
    // * type		- file type (image / file / avatar / photo)
    // * category	- category where to put file
    public function category_delete($type, $category)
    {
        global $mysql, $lang;

        if (($dir = get_item_dir($type)) === false) {
            return;
        }
        $category = trim(str_replace([' ', '\\', '/', chr(0)], ['_', ''], $category));

        if ($category && is_dir($dir.$category)) {
            if ($this->count_dir($dir.$category)) {
                msg(['type' => 'error', 'text' => $lang['upload.error.catnotempty']]);

                return;
            }
            if (is_dir($dir.$category.'/thumb')) {
                @rmdir($dir.$category.'/thumb');
            }

            if (@rmdir($dir.$category)) {
                msg(['text' => $lang['upload.catdeleted']]);
            } else {
                msg(['type' => 'error', 'text' => str_replace('{dir}', $dir.$category, $lang['upload.error.delcat'])]);
            }

            return;
        }
        msg(['text' => $lang['upload.catdeleted']]);
    }

    public function count_dir($dir)
    {
        if ($d = @opendir($dir)) {
            $cnt = 0;
            while (($file = readdir($d)) !== false) {
                if ($file != '.' && $file != '..' && is_file($dir.'/'.$file)) {
                    $cnt++;
                }
            }
            closedir($d);

            return $cnt;
        }

        return false;
    }
}

// ======================================================================= //
// Image managment class                                                   //
// ======================================================================= //
class image_managment
{
    // Get image size. Return an array with params:
    // index 0 - image type (same as in getimagesize())
    // index 1 - image width
    // index 2 - image height
    public function get_size($fname)
    {
        if (is_array($info = @getimagesize($fname))) {
            return [$info[2], $info[0], $info[1]];
        }

        return null;
    }

    // Params:
    //	rpc			- flag if we're called via RPC call
    public function create_thumb($dir, $file, $sizeX, $sizeY, $quality = 0, $param = [])
    {
        global $lang;
        $fname = $dir.'/'.$file;

        //print "CALL create_thumb($dir, $file, $sizeX, $sizeY)<br>\n";

        // Check if we have a directory for thumb
        if (!is_dir($dir.'/thumb')) {
            if (!@mkdir($dir.'/thumb', 0777)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 351, 'errorText' => $lang['upload.error.sysperm.thumbdir']];
                }
                msg(['type' => 'error', 'text' => $lang['upload.error.sysperm.thumbdir']]);

                return false;
            }
        }

        // Check if file exists and we can get it's image size
        if (!file_exists($fname) || !is_array($sz = @getimagesize($fname))) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 352, 'errorText' => $lang['upload.error.open'].$fname];
            }
            msg(['type' => 'error', 'text' => $lang['upload.error.open']]);

            return false;
        }
        $origX = $sz[0];
        $origY = $sz[1];
        $origType = $sz[2];

        if (!(($sizeX > 0) && ($sizeY > 0) && ($origX > 0) && ($origY > 0))) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 353, 'errorText' => $lang['upload.error.imgdetermine'].$fname];
            }

            return false;
        }

        // Calculate resize factor
        $factor = max($origX / $sizeX, $origY / $sizeY);

        // Don't enlarge picture without need
        if ($factor < 1) {
            $factor = 1;
        }

        // Check if we can open this type of image and open it
        $cmd = 'imagecreatefrom';
        switch ($origType) {
            case 1:
                $cmd .= 'gif';
                break;
            case 2:
                $cmd .= 'jpeg';
                break;
            case 3:
                $cmd .= 'png';
                break;
            case 6:
                $cmd .= 'bmp';
                break;
        }

        if (!$cmd || !function_exists($cmd)) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 354, 'errorText' => str_replace('{func}', $cmd, $lang['upload.error.libformat'])];
            }
            msg(['type' => 'error', 'text' => str_replace('{func}', $cmd, $lang['upload.error.libformat'])]);

            return;
        }

        switch ($origType) {
            case 1:
                $img = @imagecreatefromgif($fname);
                break;
            case 2:
                $img = @imagecreatefromjpeg($fname);
                break;
            case 3:
                $img = @imagecreatefrompng($fname);
                break;
            case 6:
                $img = @imagecreatefrombmp($fname);
                break;
        }

        if (!$img) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 355, 'errorText' => $lang['upload.error.open']];
            }
            msg(['type' => 'error', 'text' => $lang['upload.error.open']]);

            return false;
        }

        // Calculate thumb size and create an empty object for it
        $newX = round($origX / $factor);
        $newY = round($origY / $factor);

        $newimg = imagecreatetruecolor($newX, $newY);

        // Prepare for transparency // NON-ALPHA transparency
        $oTColor = imagecolortransparent($img);
        if ($oTColor >= 0 && $oTColor < imagecolorstotal($img)) {
            $TColor = imagecolorsforindex($img, $oTColor);
            $nTColor = imagecolorallocate($newimg, $TColor['red'], $TColor['green'], $TColor['blue']);
            imagefill($newimg, 0, 0, $nTColor);
            imagecolortransparent($newimg, $nTColor);
        } else {
            // Check for ALPHA transparency in PNG
            if ($origType == 3) {
                imagealphablending($newimg, false);
                $nTColor = imagecolorallocatealpha($newimg, 0, 0, 0, 127);
                imagefill($newimg, 0, 0, $nTColor);
                imagesavealpha($newimg, true);
            }
        }

        // Resize image
        imagecopyresampled($newimg, $img, 0, 0, 0, 0, $newX, $newY, $origX, $origY);

        // Try to write resized image
        switch ($origType) {
            case 1:
                $res = @imagegif($newimg, $dir.'/thumb/'.$file);
                break;
            case 2:
                $res = @imagejpeg($newimg, $dir.'/thumb/'.$file, ($quality >= 10 && $quality <= 100) ? $quality : 80);
                break;
            case 3:
                $res = @imagepng($newimg, $dir.'/thumb/'.$file);
                break;
            case 6:
                $res = @imagebmp($newimg, $dir.'/thumb/'.$file);
                break;
        }

        // Set correct permissions to file
        @chmod($dir.'/thumb/'.$file, 0644);

        if (!$res) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 356, 'errorText' => $lang['upload.error.thumbcreate']];
            }
            msg(['type' => 'error', 'text' => $lang['upload.error.thumbcreate']]);

            return false;
        }
        if ($param['rpc']) {
            return ['status' => 1, 'errorCode' => 0, 'errorText' => $lang['upload.complete'], 'data' => ['x' => $newX, 'y' => $newY]];
        }

        return [$newX, $newY];
    }

    // Transformate original image
    // * image			- filename of original image
    // * stamp			- FLAG if we need to add a stamp
    // * resize			- Array for image resize
    // ** x					- size X
    // ** y					- size Y
    // ** stampfile		- filename of stamp file
    // ** stamp_transparency - %% of transparency of added stamp [ default: 40 ]
    // ** stamp_noerror	- don't generate an error if it was not possible to add stamp
    // * shadow			- FLAG if we need to add a shadow
    // * outquality		- with what quality we should write resulting file (for JPEG) [ default: 80 ]
    // * outfile		- filename to write a result [ default: original file ]
    // * rpc			- flag shows if call is made via RPC call
    public function image_transform($param)
    {

        //function add_stamp($image, $stamp, $transparency = 40, $quality = 80){
        global $config, $lang;

        // LOAD ORIGINAL IMAGE
        // Check if file exists and we can get it's image size
        if (!file_exists($param['image']) || !is_array($sz = @getimagesize($param['image']))) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 401, 'errorText' => $lang['upload.error.open'].' '.$param['image']];
            }
            msg(['type' => 'error', 'text' => $lang['upload.error.open']]);

            return 0;
        }

        $origX = $sz[0];
        $origY = $sz[1];
        $origType = $sz[2];

        // Check if we can open this type of image and open it
        $cmd = 'imagecreatefrom';
        switch ($origType) {
            case 1:
                $cmd .= 'gif';
                break;
            case 2:
                $cmd .= 'jpeg';
                break;
            case 3:
                $cmd .= 'png';
                break;
            case 6:
                $cmd .= 'bmp';
                break;
        }

        if (!$cmd || !function_exists($cmd)) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 402, 'errorText' => str_replace('{func}', $cmd, $lang['upload.error.libformat'])];
            }
            msg(['type' => 'error', 'text' => str_replace('{func}', $cmd, $lang['upload.error.libformat'])]);

            return;
        }

        switch ($origType) {
            case 1:
                $img = @imagecreatefromgif($param['image']);
                break;
            case 2:
                $img = @imagecreatefromjpeg($param['image']);
                break;
            case 3:
                $img = @imagecreatefrompng($param['image']);
                break;
            case 6:
                $img = @imagecreatefrombmp($param['image']);
                break;
        }

        if (!$img) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 403, 'errorText' => $lang['upload.error.open']];
            }
            msg(['type' => 'error', 'text' => $lang['upload.error.open']]);

            return;
        }

        // Check if resize of original file is requested
        if (isset($param['resize']) && is_array($param['resize']) && ($param['resize']['x'] > 0) && ($param['resize']['y'] > 0)) {
            // Calculate ratio and new X/Y sizes
            $ratio = min($param['resize']['x'] / $origX, $param['resize']['y'] / $origY);
            $newX = round($origX * $ratio);
            $newY = round($origY * $ratio);

            // Create image area
            $newImg = imagecreatetruecolor($newX, $newY);

            // Preserver transparency
            if (($origType == 1) || ($origType == 3)) {
                imagecolortransparent($newImg, imagecolorallocatealpha($newImg, 0, 0, 0, 127));
                imagealphablending($newImg, false);
                imagesavealpha($newImg, true);
            }

            // Resize image
            imagecopyresampled($newImg, $img, 0, 0, 0, 0, $newX, $newY, $origX, $origY);

            // Save new information into current image
            $img = $newImg;
            $origX = $newX;
            $origY = $newY;
        }

        if ($param['stamp']) {
            // LOAD STAMP IMAGE
            if (!file_exists($param['stampfile']) || !is_array($sz = @getimagesize($param['stampfile']))) {
                if (!$param['stamp_noerror']) {
                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 404, 'errorText' => $lang['upload.error.openstamp']];
                    }
                    msg(['type' => 'error', 'text' => $lang['upload.error.openstamp']]);
                }

                return 0;
            }

            $stampX = $sz[0];
            $stampY = $sz[1];
            $stampType = $sz[2];

            // Check if we can open this type of image and open it
            $cmd = 'imagecreatefrom';
            switch ($origType) {
                case 1:
                    $cmd .= 'gif';
                    break;
                case 2:
                    $cmd .= 'jpeg';
                    break;
                case 3:
                    $cmd .= 'png';
                    break;
                case 6:
                    $cmd .= 'bmp';
                    break;
            }

            if (!$cmd || !function_exists($cmd)) {
                if ($param['rpc']) {
                    return ['status' => 0, 'errorCode' => 402, 'errorText' => str_replace('{func}', $cmd, $lang['upload.error.libformat'])];
                }
                msg(['type' => 'error', 'text' => str_replace('{func}', $cmd, $lang['upload.error.libformat'])]);

                return;
            }

            switch ($stampType) {
                case 1:
                    $stamp = @imagecreatefromgif($param['stampfile']);
                    break;
                case 2:
                    $stamp = @imagecreatefromjpeg($param['stampfile']);
                    break;
                case 3:
                    $stamp = @imagecreatefrompng($param['stampfile']);
                    break;
                case 6:
                    $stamp = @imagecreatefrombmp($param['stampfile']);
                    break;
            }

            if (!$stamp) {
                if (!$param['stamp_noerror']) {
                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 405, 'errorText' => $lang['upload.error.openstamp']];
                    }
                    msg(['type' => 'error', 'text' => $lang['upload.error.openstamp']]);
                }

                return;
            }

            // BOTH FILES ARE LOADED
            $destX = $origX - $stampX - 10;
            $destY = $origY - $stampY - 10;
            if (($destX < 0) || ($destY < 0)) {
                if (!$param['stamp_noerror']) {
                    if ($param['rpc']) {
                        return ['status' => 0, 'errorCode' => 406, 'errorText' => $lang['upload.error.stampsize']];
                    }
                    msg(['type' => 'error', 'text' => $lang['upload.error.stampsize']]);
                }

                return;
            }

            if (($param['stamp_transparency'] < 1) || ($param['stamp_transparency'] > 100)) {
                $param['stamp_transparency'] = 40;
            }

            if ($stampType == 3) {
                $this->imagecopymerge_alpha($img, $stamp, $destX, $destY, 0, 0, $stampX, $stampY, $param['stamp_transparency']);
            } else {
                imagecopymerge($img, $stamp, $destX, $destY, 0, 0, $stampX, $stampY, $param['stamp_transparency']);
            }
        }

        $newX = $origX;
        $newY = $origY;
        if ($param['shadow']) {
            $newX = $origX + 5;
            $newY = $origY + 5;
            $newimg = imagecreatetruecolor($newX, $newY);

            $background = ['r' => 255, 'g' => 255, 'b' => 255];
            $step_offset = ['r' => ($background['r'] / 10), 'g' => ($background['g'] / 10), 'b' => ($background['b'] / 10)];
            $current_color = $background;

            for ($i = 0; $i <= 5; $i++) {
                $colors[$i] = @imagecolorallocate($newimg, round($current_color['r']), round($current_color['g']), round($current_color['b']));
                $current_color['r'] -= $step_offset['r'];
                $current_color['g'] -= $step_offset['g'];
                $current_color['b'] -= $step_offset['b'];
            }

            imagefilledrectangle($newimg, 0, 0, $newX, $newY, $colors[0]);

            for ($i = 0; $i <= 5; $i++) {
                @imagefilledrectangle($newimg, 5, 5, $newX - $i, $newY - $i, $colors[$i]);
            }
            imagecopymerge($newimg, $img, 0, 0, 0, 0, $origX, $origY, 100);
            $img = $newimg;
        }

        // WRITE A RESULT FILE
        if (($param['outquality'] < 10) || ($param['outquality'] > 100)) {
            $param['outquality'] = 80;
        }

        if (!$param['outfile']) {
            $param['outfile'] = $param['image'];
        }

        switch ($origType) {
            case 1:
                $res = @imagegif($img, $param['outfile']);
                break;
            case 2:
                $res = @imagejpeg($img, $param['outfile'], $param['outquality']);
                break;
            case 3:
                $res = @imagepng($img, $param['outfile']);
                break;
            case 6:
                $res = @imagebmp($img, $param['outfile']);
                break;
        }
        if (!$res) {
            if ($param['rpc']) {
                return ['status' => 0, 'errorCode' => 407, 'errorText' => $lang['upload.error.addstamp']];
            }
            msg(['type' => 'error', 'text' => $lang['upload.error.addstamp']]);

            return;
        }

        if ($param['rpc']) {
            return ['status' => 1, 'errorCode' => 0, 'errorText' => $lang['upload.complete'], 'data' => ['x' => $newX, 'y' => $newY]];
        }

        return [$newX, $newY];
    }

    public function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
    {
        if (!isset($pct)) {
            return false;
        }
        $pct /= 100;
        // Get image width and height
        $w = imagesx($src_im);
        $h = imagesy($src_im);
        // Turn alpha blending off
        imagealphablending($src_im, false);
        // Find the most opaque pixel in the image (the one with the smallest alpha value)
        $minalpha = 127;
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $alpha = (imagecolorat($src_im, $x, $y) >> 24) & 0xFF;
                if ($alpha < $minalpha) {
                    $minalpha = $alpha;
                }
            }
        }
        //loop through image pixels and modify alpha for each
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                //get current alpha value (represents the TANSPARENCY!)
                $colorxy = imagecolorat($src_im, $x, $y);
                $alpha = ($colorxy >> 24) & 0xFF;
                //calculate new alpha
                if ($minalpha !== 127) {
                    $alpha = 127 + 127 * $pct * ($alpha - 127) / (127 - $minalpha);
                } else {
                    $alpha += 127 * $pct;
                }
                //get the color index with new alpha
                $alphacolorxy = imagecolorallocatealpha($src_im, ($colorxy >> 16) & 0xFF, ($colorxy >> 8) & 0xFF, $colorxy & 0xFF, $alpha);
                //set pixel with the new color + opacity
                if (!imagesetpixel($src_im, $x, $y, $alphacolorxy)) {
                    return false;
                }
            }
        }
        // The image copy
        imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
    }
}
