<?php

//
// ======= FUNCTIONS for FILE MANAGMENT ========
//

// Get count of file in directory
function count_dir($dir)
{
    if ($d = @opendir($dir)) {
        $cnt = 0;
        while (($file = readdir($d)) !== false) {
            if ($file != '.' && $file != '..' && is_file($dir.'/'.$file)) {
                $cnt++;
            }
        }
    }

    return $cnt;
}

// Delete an image
function manage_delete($type)
{
    global $mysql, $lang, $fmanager;

    $ok = 0;
    $err = 0;
    $files = getIsSet($_REQUEST['files']);
    if (is_array($files)) {
        foreach ($files as $file) {
            if ($fmanager->file_delete(['type' => $type, 'id' => $file])) {
                $ok++;
            } else {
                $err++;
            }
        }
    }
    if ($ok && !$err) {
        msg(['text' => sprintf($lang['msgo_deleted'], $ok)]);
    } elseif ($ok && $err) {
        msg(['type' => 'error', 'text' => sprintf($lang['msge_deleted'], $ok, $ok + $err)]);
    } elseif (!$ok && !$err) {
        msg(['type' => 'error', 'text' => $lang['msge_nodel']]);
    }
}

// Move images/files
function manage_move($type)
{
    global $mysql, $lang, $fmanager;

    $fmanager->get_limits($type);
    $dir = $fmanager->dname;

    if (defined('DEBUG')) {
        echo "CALL manage_move($type) [dir: $dir]<br>\n";
    }

    $ok = 0;
    $fail = 0;
    $files = getIsSet($_REQUEST['files']);
    $category = getIsSet($_REQUEST['category']);
    if ($files && is_dir($dir.$category) && is_writable($dir.$category)) {
        foreach ($files as $file) {
            if ($fmanager->file_rename(['type' => $type, 'id' => $file, 'move' => 1, 'newcategory' => $category])) {
                $ok++;
            } else {
                $fail++;
            }
        }
        msg(['text' => $lang['msgo_moved']]);
    } else {
        msg(['type' => 'error', 'text' => $lang['msge_move']]);
    }
}

// Manage file/image action
function manage_upload($type)
{
    global $config, $mysql, $fmanager, $lang;

    $subdirectory = ($_REQUEST['category']) ? $_REQUEST['category'] : 'default';
    $subdirectory = str_replace(['\\', '/', chr(0)], [''], $subdirectory);

    $fmanager->get_limits($type);
    $dir = $fmanager->dname;

    if ($subdirectory == 'default' && !is_dir($dir.$subdirectory)) {
        @mkdir($dir.$subdirectory, 0777);
        if ($type == 'image') {
            @mkdir($dir.$subdirectory.'/thumb', 0777);
        }
    }

    if (!is_dir($dir.$subdirectory)) {
        msg(['type' => 'error', 'text' => $lang['msge_catnexists'].' ('.$subdirectory.')']);

        return;
    }

    $imanager = new image_managment();

    // PREPARE a list for upload
    $filelist = [];

    // LOAD URL LIST
    if (is_array($urls = getIsSet($_REQUEST['userurl']))) {
        // If URL upload
        foreach ($urls as $url) {
            array_push($filelist, ['type' => $type, 'category' => $subdirectory, 'manual' => 1, 'url' => $url, 'replace' => getIsSet($_REQUEST['replace']) ? 1 : 0, 'randprefix' => getIsSet($_REQUEST['rand']) ? 1 : 0]);
        }
    }

    // LOAD ATTACHED FILE LIST
    if (getIsSet($_FILES['userfile']['name']) && is_array($_FILES['userfile']['name'])) {
        foreach ($_FILES['userfile']['name'] as $i => $v) {
            if ($v != '') {
                array_push($filelist, ['type' => $type, 'category' => $subdirectory, 'http_var' => 'userfile', 'http_varnum' => $i, 'replace' => getIsSet($_REQUEST['replace']) ? 1 : 0, 'randprefix' => getIsSet($_REQUEST['rand']) ? 1 : 0]);
            }
        }
    }

    foreach ($filelist as $fparam) {
        // Save uploaded file
        $up = $fmanager->file_upload($fparam);
        // Continue cycle if file was not uploaded
        if (!is_array($up)) {
            //print "UP failed<br>\n";
            continue;
        }

        if ($type == 'image') {
            // Check if we need image transformation
            $mkThumb = (($config['thumb_mode'] == 2) || (!$config['thumb_mode'] && $_REQUEST['thumb'])) ? 1 : 0;
            $mkStamp = (($config['stamp_mode'] == 2) || (!$config['stamp_mode'] && $_REQUEST['stamp'])) ? 1 : 0;
            $mkShadow = (($config['shadow_mode'] == 2) || (!$config['shadow_mode'] && $_REQUEST['shadow'])) ? 1 : 0;

            // Make thumb if required
            $thumb = 0;

            // Prepare params for STAMP. In older versions we don't store extension and support only .png files, in
            // newer - store stampFileName with extension and support .png and .png
            $stampFileName = '';
            if (file_exists(root.'trash/'.$config['wm_image'].'.png')) {
                $stampFileName = root.'trash/'.$config['wm_image'].'.png';
            } elseif (file_exists(root.'trash/'.$config['wm_image'])) {
                $stampFileName = root.'trash/'.$config['wm_image'];
            }

            if ($mkThumb) {
                $tsx = intval($config['thumb_size_x']) ? intval($config['thumb_size_x']) : intval($config['thumb_size']);
                $tsy = intval($config['thumb_size_y']) ? intval($config['thumb_size_y']) : intval($config['thumb_size']);
                if (($tsx < 10) || ($tsx > 1000)) {
                    $tsx = 150;
                }
                if (($tsy < 10) || ($tsy > 1000)) {
                    $tsy = 150;
                }
                $thumb = $imanager->create_thumb($config['images_dir'].$subdirectory, $up[1], $tsx, $tsy, $config['thumb_quality']);
                if ($thumb) {
                    // If we created thumb - check if we need to transform it
                    $stampThumb = ($mkStamp && $config['stamp_place'] && ($stampFileName != '')) ? 1 : 0;
                    $shadowThumb = ($mkShadow && $config['shadow_place']) ? 1 : 0;
                    if ($shadowThumb || $stampThumb) {
                        $stamp = $imanager->image_transform(
                            [
                                'image'              => $dir.$subdirectory.'/thumb/'.$up[1],
                                'stamp'              => $stampThumb,
                                'stamp_transparency' => $config['wm_image_transition'],
                                'shadow'             => $shadowThumb,
                                'stampfile'          => $stampFileName,
                            ]
                        );
                    }
                }
            }

            $stampOrig = ($mkStamp && ($config['stamp_place'] != 1) && ($stampFileName != '')) ? 1 : 0;
            $shadowOrig = ($mkShadow && ($config['shadow_place'] != 1)) ? 1 : 0;

            if ($shadowOrig || $stampOrig) {
                $stamp = $imanager->image_transform(
                    [
                        'image'              => $dir.$subdirectory.'/'.$up[1],
                        'stamp'              => $stampOrig,
                        'stamp_transparency' => $config['wm_image_transition'],
                        'shadow'             => $shadowOrig,
                        'stampfile'          => $stampFileName,
                    ]
                );
            }

            // Now write info about image into DB
            if (is_array($sz = $imanager->get_size($dir.$subdirectory.'/'.$up[1]))) {
                $fmanager->get_limits($type);

                // Gather filesize for thumbinals
                $thumb_size_x = 0;
                $thumb_size_y = 0;
                if (is_array($thumb) && is_readable($dir.$subdirectory.'/thumb/'.$up[1]) && is_array($szt = $imanager->get_size($dir.$subdirectory.'/thumb/'.$up[1]))) {
                    $thumb_size_x = $szt[1];
                    $thumb_size_y = $szt[2];
                }
                $mysql->query('update '.prefix.'_'.$fmanager->tname.' set width='.db_squote($sz[1]).', height='.db_squote($sz[2]).', preview='.db_squote(is_array($thumb) ? 1 : 0).', p_width='.db_squote($thumb_size_x).', p_height='.db_squote($thumb_size_y).', stamp='.db_squote(is_array($stamp) ? 1 : 0).' where id = '.db_squote($up[0]));
            }
        }
    }
}

//
// Show list
//
function manage_showlist($type)
{
    global $config, $mysql, $tpl, $mod, $lang, $userROW, $fmanager, $langMonths, $PHP_SELF;

    // Load admin page based cookies
    $admCookie = admcookie_get();

    $cstart = abs(isset($_REQUEST['page']) && $_REQUEST['page'] ? intval($_REQUEST['page']) : 0);
    $start_from = abs(isset($_REQUEST['start_from']) && $_REQUEST['page'] ? intval($_REQUEST['start_from']) : 0);

    if (!$cstart) {
        $cstart = 1;
    }
    $npp = isset($_REQUEST['npp']) ? intval($_REQUEST['npp']) : intval($admCookie[$type]['pp']);
    if (($npp < 1) || ($npp > 500)) {
        $npp = 20;
    }

    // - Save into cookies current value
    $admCookie[$type]['pp'] = $npp;
    admcookie_set($admCookie);

    if (!$start_from) {
        $start_from = ($cstart - 1) * $npp;
    }

    // Filter category if we work with images
    if ($type == 'image') {
        $filter = ['category = 0'];
    } else {
        $filter = [];
    }

    if (isset($_REQUEST['author']) && $_REQUEST['author']) {
        array_push($filter, 'user = '.db_squote($_REQUEST['author']));
    }

    if (isset($_REQUEST['category']) && $_REQUEST['category']) {
        array_push($filter, 'folder = '.db_squote($_REQUEST['category']));
    }

    if ($userROW['status'] > 2) {
        array_push($filter, 'owner_id='.db_squote($userROW['id']));
    }

    if (isset($_REQUEST['postdate']) && $_REQUEST['postdate'] && preg_match('/^(\d{4})(\d{2})$/', $_REQUEST['postdate'], $match)) {
        array_push($filter, '(month(from_unixtime(date)) = '.db_squote($match[2]).' and year(from_unixtime(date)) = '.db_squote($match[1]).')');
    }

    // Determine SQL table / directory for files
    $fmanager->get_limits($type);
    $dir = $fmanager->dname;

    // Show only images, that are not linked to any DataStorage
    array_push($filter, 'linked_ds = 0');
    $limit = (count($filter) ? 'where '.implode(' and ', $filter) : '');
    $query['sql'] = 'select * from '.prefix.'_'.$fmanager->tname.' '.$limit.' order by date desc limit '.$start_from.', '.$npp;
    $query['count'] = 'select count(*) as cnt from '.prefix.'_'.$fmanager->tname.' '.$limit;

    $nCount = 0;
    foreach ($mysql->select($query['sql']) as $row) {
        $nCount++;
        $folder = $row['folder'] ? $row['folder'].'/' : '';
        $fname = $fmanager->dname.$folder.$row['name'];
        $fileurl = $fmanager->uname.'/'.$folder.$row['name'];
        $thumburl = $fmanager->uname.'/'.$folder.'thumb/'.$row['name'];
        if (is_readable($fname)) {
            $fsize = FormatSize(filesize($fname));
        } else {
            $fsize = '-';
        }

        list($html_thumb, $html_file, $html_preview) = str_replace(
            ['{file_url}', '{thumb_url}', '{file_name}', '{fsize}', '{image_height}', '{image_width}'],
            [$fileurl, $thumburl, $row['name'], $fsize, $row['height'], $row['width']],
            [$lang['insert_thumb'], $lang['insert_file'], $lang['insert_preview']]
        );

        $addtime = LangDate('d.m.Y', $row['date']);
        $rename = ($userROW['status'] == 1 || $userROW['status'] == 2 || (is_array($userROW) && ($row['ownerID'] == $userROW['id']))) ? '<a href="?mod='.($type == 'image' ? 'images' : 'files')."&amp;subaction=rename&amp;of=$row[name]&amp;category=$row[folder]&amp;id=$row[id]\" onclick=\"if(ren=window.prompt('$lang[name]:','$row[name]')){ window.location.href=this.href+'&rf='+ren; } return false;\">".'<img src="'.skins_url.'/images/rename.png" border="0"/></a>' : '';
        if ($type == 'image') {
        } else {
            $file_link = '<a href="'.$fileurl.'" title="'.$row['name'].'" target="_blank">'.$row['orig_name'].'</a> ';
        }

        $tpl->template('entries', tpl_actions.$mod);
        $tvars['vars'] = [
            'php_self'     => $PHP_SELF,
            'rename'       => $rename,
            'view_thumb'   => $row['preview'] ? $row['view_thumb'] : '',
            'file_link'    => $file_link,
            'file_name'    => $row['orig_name'],
            'id'           => $row['id'],
            'width'        => $row['width'],
            'height'       => $row['height'],
            'preview_size' => (($type == 'image') && $row['p_width'] && $row['p_height']) ? $row['p_width'].' x '.$row['p_height'] : '',
            'preview_img'  => (($type == 'image') && $row['preview']) ? '<img src="'.$thumburl.'"/><br/>' : '',
            'p_width'      => $row['p_width'],
            'p_height'     => $row['p_height'],
            'size'         => $fsize,
            'folder'       => $row['folder'],
            'user'         => $row['user'],
        ];

        if ($type == 'image') {
            $tvars['vars']['insert_file'] = '<a href="javascript:insertimage(\''.$html_file.'\', \''.$_REQUEST['ifield'].'\')" title="Insert file"><img src="'.skins_url.'/images/insert_image.png" border="0"/></a>';
            $tvars['vars']['insert_thumb'] = $row['preview'] ? '<a href="javascript:insertimage(\''.$html_thumb.'\', \''.$_REQUEST['ifield'].'\')"><img src="'.skins_url.'/images/insert_thumb.png" border="0"/></a>' : '';
            $tvars['vars']['insert_preview'] = $row['preview'] ? '<a href="javascript:insertimage(\''.$html_preview.'\', \''.$_REQUEST['ifield'].'\')"><img src="'.skins_url.'/images/insert_preview.png" border="0"/></a>' : '';

            $tvars['vars']['view_file'] = '<a target="_blank" href="'.$fileurl.'"><img src="'.skins_url.'/images/insert_image.png" border="0"/></a>';
            $tvars['vars']['view_thumb'] = $row['preview'] ? '<a target="_blank" href="'.$thumburl.'"><img src="'.skins_url.'/images/insert_thumb.png" border="0"/></a>' : '';
            $tvars['vars']['edit_link'] = '?mod=images&subaction=editForm&id='.$row['id'].
                ($_REQUEST['author'] ? '&author='.$_REQUEST['author'] : '').
                ($_REQUEST['category'] ? '&category='.$_REQUEST['category'] : '').
                ($_REQUEST['postdate'] ? '&postdate='.$_REQUEST['postdate'] : '').
                ($_REQUEST['page'] ? '&page='.$_REQUEST['page'] : '').
                ($_REQUEST['npp'] ? '&npp='.$_REQUEST['npp'] : '');
        } else {
            $tvars['vars']['insert_file'] = '<a href="javascript:insertimage(\''.$html_file.'\', \''.$_REQUEST['ifield'].'\')">'.$lang['insert'].'</a>';
        }

        $tvars['regx']['#\[preview\](.+?)\[/preview\]#is'] = $_COOKIE['img_preview'] ? '$1' : '';

        if (($type == 'image') && ($row['preview'])) {
            //			$tvars['vars']['preview_img']  = '';
            //			$tvars['vars']['preview_size'] = '';
        } else {
            //			$tvars['vars']['preview_img']  = '';
            //			$tvars['vars']['preview_size'] = '';
        }

        $tpl->vars('entries', $tvars);
        $entries .= $tpl->show('entries');
    }

    $dateslist = '';
    foreach ($mysql->select("SELECT DISTINCT FROM_UNIXTIME(date,'%Y%m') as monthes, COUNT(date) AS cnt FROM ".prefix.'_'.$fmanager->tname.' GROUP BY monthes ORDER BY monthes DESC') as $row) {
        if (preg_match('/^(\d{4})(\d{2})$/', $row['monthes'], $match)) {
            $dateslist .= '<option value="'.$row['monthes'].'"'.($row['monthes'] == $_REQUEST['postdate'] ? ' selected' : '').'>'.$langMonths[$match[2] - 1].' '.$match[1].'</option>';
        }
    }

    $authorlist = '';
    if ($userROW['status'] == 4) {
        // Just commentors. They will see only their files
        $authorlist = '<option value="'.$userROW['name'].'">'.$userROW['name'].'</option>';
    } else {
        foreach ($mysql->select('select user, owner_id, count(id) cnt from '.prefix.'_'.$fmanager->tname.' where (linked_ds = 0) and (linked_id = 0) group by owner_id, user order by user') as $row) {
            $authorlist .= '<option value="'.$row['user'].'"'.($row['user'] == $_REQUEST['author'] ? ' selected' : '').'>'.$row['user'].'('.$row['cnt'].")</option>\n";
        }
    }

    $pagesss = '';
    if (is_array($pcnt = $mysql->record($query['count']))) {
        $itemCount = $pcnt['cnt'];
        $pagesCount = ceil($itemCount / $npp);

        if ($pagesCount) {
            $pagesss = generateAdminPagelist(['current' => $cstart, 'count' => $pagesCount, 'url' => admin_url.'/admin.php?mod='.$type.'s&action=list'.($_REQUEST['npp'] ? '&npp='.$npp : '').($_REQUEST['author'] ? '&author='.$_REQUEST['author'] : '').($_REQUEST['category'] ? '&category='.$_REQUEST['category'] : '').($_REQUEST['postdate'] ? '&postdate='.$_REQUEST['postdate'] : '').'&page=%page%']);
        }
    }

    if (!$nCount) {
        $entries = '<tr><td colspan=7><p align=center><b>'.$lang['not_found'].'</b></p></td></tr>';
    }

    // Check if dir exists
    $dName = ($type == 'image') ? images_dir : files_dir;
    if (!is_dir($dName) || !is_readable($dName)) {
        msg(
            [
                'type' => 'error',
                'text' => str_replace(
                    '{dirname}',
                    $dName,
                    $lang['error.dir.'.$type.'s']
                ),
                'info' => str_replace(
                    '{dirname}',
                    $dName,
                    $lang['error.dir.'.$type.'s#desc']
                ),
            ],
            1
        );

        $dirlist = 'n/a';
        $dirlistcat = 'n/a';
    } else {
        $dirlistS = ListDirs($type.'s', false, 0, 'categorySelect');
        $dirlist = ListDirs($type.'s', false, 0);
        $dirlistcat = ListDirs($type.'s', (isset($_REQUEST['category']) && $_REQUEST['category']) ? $_REQUEST['category'] : '');
    }

    // Prepare list of available extensions
    $listExt = '';
    foreach (preg_split('#, *#', $config[($type == 'image' ? 'images' : 'files').'_ext']) as $eI) {
        $listExt .= '*.'.$eI.';';
    }

    $tpl->template('table', tpl_actions.$mod);
    $tvars['vars'] = [
        'php_self'       => $PHP_SELF,
        'dateslist'      => $dateslist,
        'dirlist'        => $dirlist,
        'dirlistS'       => $dirlistS,
        'authorlist'     => $authorlist,
        'npp'            => $npp,
        'entries'        => $entries,
        'pagesss'        => $pagesss,
        'dirlistcat'     => $dirlistcat,
        'listExt'        => $listExt,
        'descExt'        => $lang['uploadify_'.($type == 'image' ? 'images' : 'files')],
        'maxSize'        => intval($config[($type == 'image' ? 'images' : 'files').'_max_size'] * 1024),
        'area'           => (isset($area) && $area) ? $area : '',
        'shadow_mode'    => $config['shadow_mode'] ? 'disabled' : '',
        'stamp_mode'     => $config['stamp_mode'] ? 'disabled' : '',
        'thumb_mode'     => $config['thumb_mode'] ? 'disabled' : '',
        'shadow_checked' => ($config['shadow_mode'] == 2) ? ' checked' : '',
        'stamp_checked'  => ($config['stamp_mode'] == 2) ? ' checked' : '',
        'thumb_checked'  => ($config['thumb_mode'] == 2) ? ' checked' : '',
        'box_preview'    => (isset($_COOKIE['img_preview']) && $_COOKIE['img_preview'] ? ' checked="checked"' : ''),
    ];

    $tvars['regx']['#\[preview\](.+?)\[/preview\]#is'] = (isset($_COOKIE['img_preview']) && $_COOKIE['img_preview']) ? '$1' : '';

    // Create auth cookie
    $tvars['vars']['authcookie'] = $userROW['authcookie'];

    if ($userROW['status'] < '3') {
        $tvars['vars']['[status]'] = '';
        $tvars['vars']['[/status]'] = '';
    } else {
        $tvars['regx']["#\[status\].*?\[/status\]#si"] = '';
    }
    $tpl->vars('table', $tvars);

    return $tpl->show('table');
}

//
// Edit image / files form
//
function manage_editForm($type, $id)
{
    global $config, $mysql, $tpl, $mod, $lang, $userROW, $fmanager, $langMonths, $PHP_SELF;

    // Determine SQL table / directory for files
    $fmanager->get_limits($type);
    $dir = $fmanager->dname;

    $tvars = [];
    switch ($type) {
        case 'image':
            if ($irow = $mysql->record('select * from '.prefix.'_images where id = '.db_squote($id))) {
                $folder = $irow['folder'] ? $irow['folder'].'/' : '';
                $fname = $fmanager->dname.$folder.$irow['name'];
                $thumbname = $fmanager->dname.$folder.'thumb/'.$irow['name'];
                $fileurl = $fmanager->uname.'/'.$folder.$irow['name'];
                $thumburl = $fmanager->uname.'/'.$folder.'thumb/'.$irow['name'];

                $fsize = is_readable($fname) ? FormatSize(@filesize($fname)) : '-';
                $thumbsize = is_readable($thumbname) ? FormatSize(@filesize($thumbname)) : '-';

                $tvars['vars'] = [
                    'php_self'       => $PHP_SELF,
                    'id'             => $irow['id'],
                    'name'           => $irow['name'],
                    'orig_name'      => $irow['orig_name'],
                    'date'           => strftime('%d.%m.%Y %H:%M', $irow['date']),
                    'author'         => $irow['user'],
                    'width'          => $irow['width'],
                    'height'         => $irow['height'],
                    'size'           => $fsize,
                    'description'    => $irow['description'],
                    'category'       => $irow['folder'],
                    'fileurl'        => $fileurl,
                    'thumburl'       => $thumburl,
                    'preview_width'  => $irow['p_width'],
                    'preview_height' => $irow['p_height'],
                    'preview_size'   => $thumbsize,
                    'thumb_quality'  => $config['thumb_quality'],
                    'thumb_size_x'   => $config['thumb_size'],
                    'thumb_size_y'   => $config['thumb_size'],
                    'r_author'       => $_REQUEST['author'],
                    'r_category'     => $_REQUEST['category'],
                    'r_postdate'     => $_REQUEST['postdate'],
                    'r_page'         => $_REQUEST['page'],
                    'r_npp'          => $_REQUEST['npp'],

                    'link_back' => '?mod=images&action=list'.
                        ($_REQUEST['author'] ? '&author='.$_REQUEST['author'] : '').
                        ($_REQUEST['category'] ? '&category='.$_REQUEST['category'] : '').
                        ($_REQUEST['postdate'] ? '&postdate='.$_REQUEST['postdate'] : '').
                        ($_REQUEST['page'] ? '&page='.$_REQUEST['page'] : '').
                        ($_REQUEST['npp'] ? '&npp='.$_REQUEST['npp'] : ''),

                ];
                $tvars['regx']['#\[have_stamp\](.*?)\[\/have_stamp\]#is'] = $irow['stamp'] ? '$1' : '';
                $tvars['regx']['#\[no_stamp\](.*?)\[\/no_stamp\]#is'] = $irow['stamp'] ? '' : '$1';

                if ($irow['preview']) {
                    $tvars['vars']['preview_status'] = 'есть';
                    $tvars['regx']['#\[preview\](.+?)\[\/preview\]#is'] = '$1';
                } else {
                    $tvars['vars']['preview_status'] = 'нет';
                    $tvars['regx']['#\[preview\](.+?)\[\/preview\]#is'] = '';
                }
            }
    }

    $tpl->template('edit', tpl_actions.$mod);
    $tpl->vars('edit', $tvars);

    return $tpl->show('edit');
}

function manage_editApply($type, $id)
{
    global $config, $fmanager, $mysql;

    // Получаем данные об изображении
    if (!($irow = $mysql->record('select * from '.prefix.'_images where id = '.db_squote($id)))) {
        return false;
    }

    // Переименование
    if ($_POST['newname']) {
        if ($fmanager->file_rename(['type' => $type, 'id' => $id, 'move' => 0, 'newname' => $_POST['newname']])) {
            // OK. Reload image data
            if (!($irow = $mysql->record('select * from '.prefix.'_images where id = '.db_squote($id)))) {
                return false;
            }
        }
    }

    // Инициализация обрабочика изображений
    $imanager = new image_managment();

    // Наложение штампа на оригинальную картинку
    if ($_POST['createStamp'] && !$irow['stamp']) {
        $stampFileName = '';

        if (file_exists(root.'trash/'.$config['wm_image'].'.png')) {
            $stampFileName = root.'trash/'.$config['wm_image'].'.png';
        } elseif (file_exists(root.'trash/'.$config['wm_image'])) {
            $stampFileName = root.'trash/'.$config['wm_image'];
        }

        if ($stamp = $imanager->image_transform(
            [
                'image' => $config['images_dir'].$irow['folder'].'/'.$irow['name'],
                'stamp' => 1,
                'stamp_transparency' => $config['wm_image_transition'],
                'stampfile' => $stampFileName,
            ]
        )) {
            $tsx = $stamp[0];
            $tsy = $stamp[1];

            $irow['stamp'] = 1;
            $mysql->query('update '.prefix.'_images set stamp = 1 where id = '.db_squote($irow['id']));
            //print "STAMP added to original img: ".var_export($stamp, true);
        }
    }

    // Создание/изменение уменьшенной копии (preview)
    if ($_POST['flagPreview']) {
        //print "Create thumb<br/>\n";
        $tsx = intval($_POST['thumbSizeX']);
        $tsy = intval($_POST['thumbSizeY']);
        if (($tsx < 10) || ($tsx > 1000)) {
            $tsx = 150;
        }
        if (($tsy < 10) || ($tsy > 1000)) {
            $tsy = 150;
        }

        $tq = intval($_POST['thumbQuality']);
        if (($tq < 10) || ($tq > 100)) {
            $tq = 80;
        }

        $thumb = $imanager->create_thumb($config['images_dir'].$irow['folder'], $irow['name'], $tsx, $tsy, $tq);
        //print "Status: ".var_export($thumb, true)." <br/>\n";
        if ($thumb) {
            // If we created thumb - check if we need to transform it
            $stampThumb = ($_POST['flagStamp'] && !$irow['stamp']) ? 1 : 0;
            $shadowThumb = ($_POST['flagShadow']) ? 1 : 0;

            $tsx = $thumb[0];
            $tsy = $thumb[1];
            if ($shadowThumb || $stampThumb) {
                //print "call transform: `".$config['images_dir'].$irow['folder'].'/thumb/'.$irow['name']."`<br/>\n";

                $stampFileName = '';
                if (file_exists(root.'trash/'.$config['wm_image'].'.png')) {
                    $stampFileName = root.'trash/'.$config['wm_image'].'.png';
                } elseif (file_exists(root.'trash/'.$config['wm_image'])) {
                    $stampFileName = root.'trash/'.$config['wm_image'];
                }

                if ($stamp = $imanager->image_transform(
                    [
                        'image' => $config['images_dir'].$irow['folder'].'/thumb/'.$irow['name'],
                        'stamp' => $stampThumb,
                        'stamp_transparency' => $config['wm_image_transition'],
                        'shadow' => $shadowThumb,
                        'stampfile' => $stampFileName,
                    ]
                )) {
                    $tsx = $stamp[0];
                    $tsy = $stamp[1];
                    //	print "TRANSFORM: OK<br/>\n";
                }
            }
            // Update Thumb params
            $mysql->query('update '.prefix.'_images set p_width = '.intval($tsx).', p_height='.db_squote($tsy).', preview=1 where id = '.db_squote($irow['id']));
        }
    }

    // Update description (if changed)
    if ($irow['description'] != $_POST['description']) {
        $mysql->query('update '.prefix.'_images set description = '.db_squote($_POST['description']).' where id = '.db_squote($irow['id']));
    }

    msg(['text' => 'Изображение отредактировано']);
    manage_editForm('image', $irow['id']);

    // print "<pre>".var_export($_POST, true)."</pre>";
}
