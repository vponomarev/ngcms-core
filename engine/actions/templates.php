<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: templates.php
// Description: Manage/Edit templates
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

LoadLang('templates', 'admin', 'templates');

//
// Preload templates version files
//
function loadTemplateVersions()
{
    $tDir = root.'../templates';
    $tlist = [];
    if ($dRec = opendir($tDir)) {
        while (($dName = readdir($dRec)) !== false) {
            if (($dName == '.') || ($dName == '..')) {
                continue;
            }
            if (is_dir($tDir.'/'.$dName) && file_exists($vfn = $tDir.'/'.$dName.'/version') && (filesize($vfn)) && ($vf = @fopen($vfn, 'r'))) {
                $tRec = ['name' => $dName];
                while (!feof($vf)) {
                    $line = fgets($vf);
                    if (preg_match("/^(.+?) *\: *(.+?) *$/i", trim($line), $m)) {
                        if (in_array(strtolower($m[1]), ['id', 'title', 'author', 'version', 'reldate', 'plugins', 'image', 'imagepreview'])) {
                            $tRec[strtolower($m[1])] = $m[2];
                        }
                    }
                }
                fclose($vf);
                if (isset($tRec['id']) && isset($tRec['title'])) {
                    array_push($tlist, $tRec);
                }
            }
        }
        closedir($dRec);
    }

    return $tlist;
}

//
// ================================================================== //
//                              CODE                                  //
// ================================================================== //
//

$tVars = [
    'home_url' => home,
    'token'    => genUToken('admin.templates'),
];

$tVars['siteTemplates'] = [];

$tlist = loadTemplateVersions();
foreach ($tlist as $tver) {
    $tVars['siteTemplates'][] = $tver;
}

$xt = $twig->loadTemplate('skins/default/tpl/templates.tpl');
$main_admin = $xt->render($tVars);
