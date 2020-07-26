<?php

//
// Copyright (C) 2006-2014 Next Generation CMS (http://ngcms.ru/)
// Name: upgrade.php
// Description: General DB upgrade tool
// Author: NGCMS Development Team
//

@include_once 'core.php';

// Upgrade matrix
$upgradeMatrix = [
    1   => [
        'insert into '.prefix."_config (name, value) values ('database.engine.revision', '1')",
    ],
    2   => [
        'alter table '.prefix.'_news add column content_delta text after content',
        'alter table '.prefix.'_news add column content_source int default 0 after content_delta',
        'update '.prefix."_config set value=2 where name='database.engine.revision'",
        'update '.prefix."_config set value='".engineVersion."' where name='database.engine.version'",
    ],
    3 => [
        'alter table '.prefix.'_news drop column content_delta, drop column content_source',
        'update '.prefix."_config set value=3 where name='database.engine.revision'",
    ],
];

$cv = getCurrentDBVersion();
if ($cv < minDBVersion) {
    // Proceed with upgrade
    echo 'Going to upgrade DB revision from '.$cv.' to '.minDBVersion."..<br/>\n";
    doUpgrade($cv + 1, minDBVersion);
} else {
    echo 'No upgrade needed!';
}

function getCurrentDBVersion(): int
{
    $db = NGEngine::getInstance()->getDB();
    $dbv = $db->record('select * from '.prefix.'_config where name = "database.engine.revision"');

    if (!is_array($dbv)) {
        // DB was created before starting version-tracking
        return 0;
    }

    return $dbv['value'];
}

function doUpgrade($fromVersion, $toVersion)
{
    global $upgradeMatrix;

    $db = NGEngine::getInstance()->getDB();
    for ($i = $fromVersion; $i <= $toVersion; $i++) {
        echo 'Upgrade to revision: '.$i."<br/>\n";
        foreach ($upgradeMatrix[$i] as $k => $v) {
            echo '# ['.$v.'] ...';
            $res = $db->exec($v);
            if ($res == null) {
                echo "<br/><div style='color: red; background-color: #EEEEEE;'><b>ERROR!<br/>Cannot proceed with upgrade, need manual DB fix.</b></div>";

                return;
            }
            echo "Ok<br/>\n";
        }
    }
    echo "Return back to <a href='admin.php'>ADMIN PAGE</a>";
}
