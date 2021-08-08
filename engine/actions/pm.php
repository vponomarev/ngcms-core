<?php

//
// Copyright (C) 2006-2020 Next Generation CMS (http://ngcms.ru/)
// Name: pm.php
// Description: Personal messages
// Author: NGCMS Development Team
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

$lang = LoadLang('pm', 'admin');

function pm_send()
{
    global $lang, $userROW;

    $sendto = trim($_REQUEST['sendto']);
    $title = secure_html($_REQUEST['title']);
    $content = $_REQUEST['content'];

    if (!$title || mb_strlen($title) > 50) {
        msg(['type' => 'error', 'text' => $lang['msge_title'], 'info' => $lang['msgi_title']]);

        return;
    }
    if (!$content || mb_strlen($content) > 3000) {
        msg(['type' => 'error', 'text' => $lang['msge_content'], 'info' => $lang['msgi_content']]);

        return;
    }

    if (!isset($_REQUEST['token']) || ($_REQUEST['token'] != genUToken('pm.token'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token']]);

        return;
    }

    $db = NGEngine::getInstance()->getDB();
    $query = 'select * from '.uprefix.'_users where name = :name';
    $params = ['name' => $sendto];
    if (is_numeric($sendto)) {
        $query = 'select * from '.uprefix.'_users where id = :id';
        $params = ['id' => $sendto];
    }

    if ($sendto && ($torow = $db->record($query, $params))) {
        $content = secure_html(trim($content));

        $db->exec('insert into '.uprefix.'_users_pm (from_id, to_id, pmdate, title, content) values (:from_id, :to_id, unix_timestamp(now()), :title, :content)', ['from_id' => $userROW['id'], 'to_id' => $torow['id'], 'title' => $title, 'content' => $content]);
        msg(['text' => $lang['msgo_sent']]);
    } else {
        msg(['type' => 'error', 'text' => $lang['msge_nouser'], 'info' => $lang['msgi_nouser']]);
    }
}

function pm_list()
{
    global $lang, $userROW, $twig;

    $tVars = [
        'entries'   => [],
        'token'     => genUToken('pm.token'),
    ];

    $db = NGEngine::getInstance()->getDB();
    foreach ($db->query('select pm.*, u.id as uid, u.name as uname from '.uprefix.'_users_pm pm left join '.uprefix.'_users u on pm.from_id=u.id where pm.to_id = :id order by pmid desc limit 0, 30', ['id' => $userROW['id']]) as $row) {
        $senderProfileURL = '';
        $senderName = $lang['messaging'];
        if ($row['from_id'] && $row['uid']) {
            $senderProfileURL = checkLinkAvailable('uprofile', 'show') ?
                generateLink('uprofile', 'show', ['name' => $row['uname'], 'id' => $row['uid']]) :
                generateLink('core', 'plugin', ['plugin' => 'uprofile', 'handler' => 'show'], ['name' => $row['uname'], 'id' => $row['uid']]);
            $senderName = $row['uname'];
        } elseif ($row['from_id']) {
            $senderName = $lang['udeleted'];
        }
        $tEntry = [
            'id'               => $row['pmid'],
            'date'             => LangDate('j.m.Y - H:i', $row['pmdate']),
            'title'            => $row['title'],
            'senderID'         => $row['from_id'],
            'senderProfileURL' => $senderProfileURL,
            'senderName'       => $senderName,
            'flags'            => [
                'viewed'        => $row['viewed'] ? true : false,
                'haveSender'    => strlen($senderProfileURL) ? true : false,
            ],
        ];
        $tVars['entries'][] = $tEntry;
    }
    $xt = $twig->loadTemplate('skins/default/tpl/pm/table.tpl');

    return $xt->render($tVars);
}

function pm_read()
{
    global $lang, $userROW, $parse, $twig;

    if (!isset($_REQUEST['pmid'])) {
        msg(['type' => 'error', 'text' => $lang['msge_bad']]);

        return;
    }

    $db = NGEngine::getInstance()->getDB();
    if ($row = $db->record('select * from '.uprefix.'_users_pm where pmid = :pmid and (to_id = :to_id or from_id= :from_id)', ['pmid' => $_REQUEST['pmid'], 'to_id' => $userROW['id'], 'from_id' => $userROW['id']])) {
        $tVars = [
            'id'        => $row['pmid'],
            'token'     => genUToken('pm.token'),
            'title'     => $row['title'],
            'fromID'    => $row['from_id'],
            'toID'      => $row['to_id'],
            'fromName'  => $lang['messaging'],
            'toName'    => $lang['messaging'],
            'content'   => $parse->htmlformatter($parse->smilies($parse->bbcodes($row['content']))),
        ];

        if ($row['from_id'] > 0) {
            $r = locateUserById($row['from_id']);
            $tVars['fromName'] = (isset($r['name'])) ? $r['name'] : $lang['udeleted'];
        }
        if ($row['to_id'] > 0) {
            $r = locateUserById($row['to_id']);
            $tVars['toName'] = (isset($r['name'])) ? $r['name'] : $lang['udeleted'];
        }

        if ((!$row['viewed']) && ($row['to_id'] == $userROW['id'])) {
            // Mark as read ONLY if token is correct
            if (isset($_REQUEST['token']) && ($_REQUEST['token'] == genUToken('pm.token'))) {
                $db->exec('update '.uprefix.'_users_pm set viewed = 1 WHERE pmid = :pmid', ['pmid' => $row['pmid']]);
            } else {
                msg(['type' => 'error', 'text' => $lang['error.security.token']]);
            }
        }
        $xt = $twig->loadTemplate('skins/default/tpl/pm/read.tpl');

        return $xt->render($tVars);
    } else {
        msg(['type' => 'error', 'text' => $lang['msge_bad']]);
    }
}

function pm_reply()
{
    global $config, $lang, $userROW, $twig;

    if (!isset($_REQUEST['pmid'])) {
        msg(['type' => 'error', 'text' => $lang['msge_reply']]);

        return;
    }

    $db = NGEngine::getInstance()->getDB();
    if ($row = $db->record('select * from '.uprefix.'_users_pm where pmid = :pmid and (to_id = :to_id or from_id= :from_id)', ['pmid' => $_REQUEST['pmid'], 'to_id' => $userROW['id'], 'from_id' => $userROW['id']])) {
        if (!is_array($row)) {
            msg(['type' => 'error', 'text' => $lang['msge_reply']]);

            return;
        }

        $reTitle = 'Re:'.$row['title'];
        if (mb_strlen($reTitle) > 50) {
            $reTitle = mb_substr($reTitle, 0, 50);
        }
        $tVars = [
            'id'        => $row['pmid'],
            'title'     => $reTitle,
            'token'     => genUToken('pm.token'),
            'quicktags' => QuickTags('', 'pmmes'),
            'smilies'   => ($config['use_smilies'] == '1') ? InsertSmilies('content', 10) : '',
            'toID'      => $row['from_id'],
            'fromID'    => $row['to_id'],
            'fromName'  => $lang['messaging'],
            'toName'    => $lang['messaging'],
        ];

        if ($row['from_id'] > 0) {
            $r = locateUserById($row['from_id']);
            $tVars['toName'] = (isset($r['name'])) ? $r['name'] : $lang['udeleted'];
        }
        if ($row['to_id'] > 0) {
            $r = locateUserById($row['to_id']);
            $tVars['fromName'] = (isset($r['name'])) ? $r['name'] : $lang['udeleted'];
        }
        $xt = $twig->loadTemplate('skins/default/tpl/pm/reply.tpl');

        return $xt->render($tVars);
    } else {
        msg(['type' => 'error', 'text' => $lang['msge_bad']]);
    }
}

function pm_write()
{
    global $config, $twig;

    $tVars = [
        'quicktags' => QuickTags('', 'pmmes'),
        'smilies'   => ($config['use_smilies'] == '1') ? InsertSmilies('content', 10) : '',
        'token'     => genUToken('pm.token'),
    ];
    $xt = $twig->loadTemplate('skins/default/tpl/pm/write.tpl');

    return $xt->render($tVars);
}

function pm_delete()
{
    global $lang, $userROW;

    if (!isset($_REQUEST['token']) || ($_REQUEST['token'] != genUToken('pm.token'))) {
        msg(['type' => 'error', 'text' => $lang['error.security.token']]);

        return;
    }

    $selected_pm = getIsSet($_REQUEST['selected_pm']);
    if (!$selected_pm || !is_array($selected_pm)) {
        msg(['type' => 'error', 'text' => $lang['msge_select']]);

        return;
    }

    $db = NGEngine::getInstance()->getDB();
    foreach ($selected_pm as $id) {
        $db->exec('delete from '.uprefix.'_users_pm where pmid = :pmid and (from_id= :from_id or to_id= :to_id)', ['pmid' => $id, 'from_id' => $userROW['id'], 'to_id' => $userROW['id']]);
    }
    msg(['text' => $lang['msgo_deleted']]);
}

switch ($action) {
    case 'read':
        $main_admin = pm_read();
        break;
    case 'reply':
        $main_admin = pm_reply();
        break;
    case 'send':
        $main_admin = pm_send();
        break;
    case 'write':
        $main_admin = pm_write();
        break;
    case 'delete':
        $main_admin = pm_delete();
        break;
    default:
        $main_admin = pm_list();
}
