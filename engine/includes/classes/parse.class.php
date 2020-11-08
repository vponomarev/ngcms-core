<?php

//
// Copyright (C) 2006-2012 Next Generation CMS (http://ngcms.ru/)
// Name: parse.class.php
// Description: Parsing and formatting routines
// Author: Vitaly Ponomarev, Alexey Zinchenko
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

class parse
{
    public function slashes($content)
    {
        return (get_magic_quotes_gpc()) ? $content : addslashes($content);
    }

    public function userblocks($content)
    {
        global $config, $lang, $userROW;
        if (!$config['blocks_for_reg']) {
            return $content;
        }

        return preg_replace("#\[hide\]\s*(.*?)\s*\[/hide\]#is", is_array($userROW) ? '$1' : str_replace('{text}', $lang['not_logged'], $lang['not_logged_html']), $content);
    }

    // Scan URL and normalize it to convert to absolute path
    // Check for XSS
    public function normalize_url($url)
    {
        if (((mb_substr($url, 0, 1) == '"') && (mb_substr($url, -1, 1) == '"')) ||
            ((mb_substr($url, 0, 1) == "'") && (mb_substr($url, -1, 1) == "'"))
        ) {
            $url = mb_substr($url, 1, mb_strlen($url) - 2);
        }

        // Check for XSS attack
        $urlXSS = str_replace([ord(0), ord(9), ord(10), ord(13), ' ', "'", '"', ';'], '', $url);
        if (preg_match('/^javascript:/isu', $urlXSS)) {
            return false;
        }

        // Add leading "http://" if needed
        if (!preg_match("#^(http|ftp|https|news)\://#iu", $url)) {
            $url = 'http://'.$url;
        }

        return $url;
    }

    // Parse BB-tag params
    public function parseBBCodeParams($paramLine)
    {

        // Start scanning
        // State:
        // 0 - waiting for name
        // 1 - scanning name
        // 2 - waiting for '='
        // 3 - waiting for value
        // 4 - scanning value
        // 5 - complete
        $state = 0;
        // 0 - no quotes activated
        // 1 - single quotes activated
        // 2 - double quotes activated
        $quotes = 0;

        $keyName = '';
        $keyValue = '';
        $errorFlag = 0;

        $keys = [];

        for ($sI = 0; $sI < mb_strlen($paramLine); $sI++) {
            // act according current state
            $x = $paramLine[$sI];

            switch ($state) {
                case 0:
                    if ($x == "'") {
                        $quotes = 1;
                        $state = 1;
                        $keyName = '';
                    } elseif ($x == "'") {
                        $quotes = 2;
                        $state = 1;
                        $keyName = '';
                    } elseif ((($x >= 'A') && ($x <= 'Z')) || (($x >= 'a') && ($x <= 'z'))) {
                        $state = 1;
                        $keyName = $x;
                    }
                    break;
                case 1:
                    if ((($quotes == 1) && ($x == "'")) || (($quotes == 2) && ($x == '"'))) {
                        $quotes = 0;
                        $state = 2;
                    } elseif ((($x >= 'A') && ($x <= 'Z')) || (($x >= 'a') && ($x <= 'z'))) {
                        $keyName .= $x;
                    } elseif ($x == '=') {
                        $state = 3;
                    } elseif (($x == ' ') || ($x == chr(9))) {
                        $state = 2;
                    } else {
                        $erorFlag = 1;
                    }
                    break;
                case 2:
                    if ($x == '=') {
                        $state = 3;
                    } elseif (($x == ' ') || ($x == chr(9))) {
                    } else {
                        $errorFlag = 1;
                    }
                    break;
                case 3:
                    if ($x == "'") {
                        $quotes = 1;
                        $state = 4;
                        $keyValue = '';
                    } elseif ($x == '"') {
                        $quotes = 2;
                        $state = 4;
                        $keyValue = '';
                    } elseif ((($x >= 'A') && ($x <= 'Z')) || (($x >= 'a') && ($x <= 'z'))) {
                        $state = 4;
                        $keyValue = $x;
                    }
                    break;
                case 4:
                    if ((($quotes == 1) && ($x == "'")) || (($quotes == 2) && ($x == '"'))) {
                        $quotes = 0;
                        $state = 5;
                    } elseif (!$quotes && (($x == ' ') || ($x == chr(9)))) {
                        $state = 5;
                    } else {
                        $keyValue .= $x;
                    }
                    break;
            }

            // Action in case when scanning is complete
            if ($state == 5) {
                $keys[mb_strtolower($keyName)] = $keyValue;
                $state = 0;
            }
        }

        // If we finished and we're in stete "scanning value" - register this field
        if ($state == 4) {
            $keys[mb_strtolower($keyName)] = $keyValue;
            $state = 0;
        }

        // If we have any other state - report an error
        if ($state) {
            $errorFlag = 1; // print "EF ($state)[".$paramLine."].";
        }

        if ($errorFlag) {
            return -1;
        }

        return $keys;
    }

    public function bbcodes($content)
    {
        global $lang, $config, $userROW, $SYSTEM_FLAGS;

        if (!$config['use_bbcodes']) {
            return $content;
        }

        // Special BB tag [code] - blocks all other tags inside
        while (preg_match("#\[code\](.+?)\[/code\]#isu", $content, $res)) {
            $content = str_replace($res[0], '<pre>'.str_replace(['[', '<'], ['&#91;', '&lt;'], $res[1]).'</pre>', $content);
        }

        //$content	=	preg_replace("#\[code\](.+?)\[/code\]#is", "<pre>$1</pre>",$content);

        $content = preg_replace("#\[quote\]\s*(.*?)\s*\[/quote\]#is", '<blockquote><b>'.$lang['bb_quote'].'</b><br />$1</blockquote>', $content);
        $content = preg_replace("#\[quote=(.*?)\]\s*(.*?)\s*\[/quote\]#is", '<blockquote><b>$1 '.$lang['bb_wrote'].'</b><br />$2</blockquote>', $content);

        $content = preg_replace("#\[acronym\]\s*(.*?)\s*\[/acronym\]#is", '<acronym>$1</acronym>', $content);
        $content = preg_replace('#\[acronym=([^\"]+?)\]\s*(.*?)\s*\[/acronym\]#is', '<acronym title="$1">$2</acronym>', $content);

        $content = preg_replace("#\[email\]\s*([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,20})\s*\[/email\]#i", '<a href="mailto:$1">$1</a>', $content);
        $content = preg_replace("#\[email\s*=\s*\&quot\;([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,20})\s*\&quot\;\s*\](.*?)\[\/email\]#i", '<a href="mailto:$1">$2</a>', $content);
        $content = preg_replace("#\[email\s*=\s*([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,20})\s*\](.*?)\[\/email\]#i", '<a href="mailto:$1">$2</a>', $content);
        $content = preg_replace("#\[s\](.*?)\[/s\]#is", '<s>$1</s>', $content);
        $content = preg_replace("#\[b\](.+?)\[/b\]#is", '<b>$1</b>', $content);
        $content = preg_replace("#\[i\](.+?)\[/i\]#is", '<i>$1</i>', $content);
        $content = preg_replace("#\[u\](.+?)\[/u\]#is", '<u>$1</u>', $content);
        $content = preg_replace("#\[p\](.+?)\[/p\]#is", '<p>$1</p>', $content);
        $content = preg_replace("#\[ul\](.*?)\[/ul\]#is", '<ul>$1</ul>', $content);
        $content = preg_replace("#\[li\](.*?)\[/li\]#is", '<li>$1</li>', $content);
        $content = preg_replace("#\[ol\](.*?)\[/ol\]#is", '<ol>$1</ol>', $content);
        $content = preg_replace("#\[left\](.*?)\[/left\]#is", '<p style="text-align: left">$1</p>', $content);
        $content = preg_replace("#\[right\](.*?)\[/right\]#is", '<p style="text-align: right">$1</p>', $content);
        $content = preg_replace("#\[center\](.*?)\[/center\]#is", '<p style="text-align: center">$1</p>', $content);
        $content = preg_replace("#\[justify\](.*?)\[/justify\]#is", '<p style="text-align: justify">$1</p>', $content);
        $content = preg_replace("#\[br\]#is", '<br/>', $content);

        // Process spoilers
        while (preg_match("#\[spoiler\](.*?)\[/spoiler\]#isu", $content, $null)) {
            $content = preg_replace("#\[spoiler\](.*?)\[/spoiler\]#is", '<div class="spoiler"><div class="sp-head" onclick="toggleSpoiler(this.parentNode, this);"><b></b>'.$lang['bb_spoiler'].'</div><div class="sp-body">$1</div></div>', $content);
        }

        while (preg_match("#\[spoiler=\"(.+?)\"\](.*?)\[/spoiler\]#isu", $content, $null)) {
            $content = preg_replace("#\[spoiler=\"(.+?)\"\](.*?)\[/spoiler\]#is", '<div class="spoiler"><div class="sp-head" onclick="toggleSpoiler(this.parentNode, this);"><b></b>$1</div><div class="sp-body">$2</div></div>', $content);
        }

        // Process Images
        // Possible format:
        // '[img' + ( '=' + URL) + flags + ']' + alt + '[/url]'
        // '[img' + flags ']' + url + '[/url]'
        // Allower flags:
        // width
        // height
        // border
        // hspace
        // vspace
        // align: 'left', 'right', 'center'
        // class: anything
        // alt: anything
        // title: anything

        if (preg_match_all("#\[img(\=| *)(.*?)\](.*?)\[\/img\]#isu", $content, $pcatch, PREG_SET_ORDER)) {
            $rsrc = [];
            $rdest = [];
            // Scan all IMG tags
            foreach ($pcatch as $catch) {

                // Init variables
                list($line, $null, $paramLine, $alt) = $catch;
                array_push($rsrc, $line);

                // Check for possible error in case of using "]" within params/url
                // Ex: [url="file[my][super].avi" target="_blank"]F[I]LE[/url] is parsed incorrectly
                if ((mb_strpos($alt, ']') !== false) && (mb_strpos($alt, '"') !== false)) {
                    // Possible bracket error. Make deep analysis
                    $jline = $paramLine.']'.$alt;
                    $brk = 0;
                    $jlen = mb_strlen($jline);
                    for ($ji = 0; $ji < $jlen; $ji++) {
                        if ($jline[$ji] == '"') {
                            $brk = !$brk;
                            continue;
                        }

                        if ((!$brk) && ($jline[$ji] == ']')) {
                            // Found correct delimiter
                            $paramLine = mb_substr($jline, 0, $ji);
                            $alt = mb_substr($jline, $ji + 1);
                            break;
                        }
                    }
                }

                $outkeys = [];

                // Make a parametric line with url
                if (trim($paramLine)) {
                    // Parse params
                    $keys = $this->parseBBCodeParams((($null == '=') ? 'src=' : '').$paramLine);
                } else {
                    // No params to scan
                    $keys = [];
                }

                // Get URL
                $urlREF = $this->validateURL((!isset($keys['src']) || !$keys['src']) ? $alt : $keys['src']);

                // Return an error if BB code is bad
                if ((!is_array($keys)) || ($urlREF === false)) {
                    array_push($rdest, '[INVALID IMG BB CODE]');
                    continue;
                }

                $keys['alt'] = $alt;

                // Now let's compose a resulting URL
                $outkeys[] = 'src="'.$urlREF.'"';

                // Now parse allowed tags and add it into output line
                foreach ($keys as $kn => $kv) {
                    switch ($kn) {
                        case 'width':
                        case 'height':
                        case 'hspace':
                        case 'vspace':
                        case 'border':
                            $outkeys[] = $kn.'="'.intval($kv).'"';
                            break;
                        case 'align':
                            if (in_array(mb_strtolower($kv), ['left', 'right', 'middle', 'top', 'bottom'])) {
                                $outkeys[] = $kn.'="'.mb_strtolower($kv).'"';
                            }
                            break;
                        case 'class':
                            $v = str_replace([ord(0), ord(9), ord(10), ord(13), ' ', "'", '"', ';', ':', '<', '>', '&', '[', ']'], '', $kv);
                            $outkeys[] = $kn.'="'.$v.'"';
                            break;
                        case 'alt':
                        case 'title':
                            $v = str_replace(['"', '[', ']', ord(0), ord(9), ord(10), ord(13), ':', '<', '>', '&'], ["'", '%5b', '%5d', ''], $kv);
                            $outkeys[] = $kn.'="'.$v.'"';
                            break;
                    }
                }
                // Fill an output replacing array
                array_push($rdest, '<img '.(implode(' ', $outkeys)).' />');
            }
            $content = str_replace($rsrc, $rdest, $content);
        }

        // Авто-подсветка URL'ов в тексте новости [ пользуемся обработчиком тега [url] ]
        $content = preg_replace("#(^|\s)((http|https|news|ftp)://\w+[^\s\[\]\<]+)#i", '$1[url]$2[/url]', $content);

        // Process URLS
        // Possible format:
        // '[url' + ( '=' + URL) + flags + ']' + Name + '[/url]'
        // '[url' + flags ']' + url + '[/url]'
        // Allower flags:
        // target: anything
        // class: anything
        // title: anything
        // external: yes/no - flag if link is opened via external page or not

        if (preg_match_all("#\[url(\=| *)(.*?)\](.*?)\[\/url\]#isu", $content, $pcatch, PREG_SET_ORDER)) {
            $rsrc = [];
            $rdest = [];
            // Scan all URL tags
            foreach ($pcatch as $catch) {

                // Init variables
                list($line, $null, $paramLine, $alt) = $catch;
                array_push($rsrc, $line);

                // Check for possible error in case of using "]" within params/url
                // Ex: [url="file[my][super].avi" target="_blank"]F[I]LE[/url] is parsed incorrectly
                if ((mb_strpos($alt, ']') !== false) && (mb_strpos($alt, '"') !== false)) {
                    // Possible bracket error. Make deep analysis
                    $jline = $paramLine.']'.$alt;
                    $brk = 0;
                    $jlen = mb_strlen($jline);
                    for ($ji = 0; $ji < $jlen; $ji++) {
                        if ($jline[$ji] == '"') {
                            $brk = !$brk;
                            continue;
                        }

                        if ((!$brk) && ($jline[$ji] == ']')) {
                            // Found correct delimiter
                            $paramLine = mb_substr($jline, 0, $ji);
                            $alt = mb_substr($jline, $ji + 1);
                            break;
                        }
                    }
                }

                $outkeys = [];

                // Make a parametric line with url
                if (trim($paramLine)) {
                    // Parse params
                    $keys = $this->parseBBCodeParams((($null == '=') ? 'href=' : '').$paramLine);
                } else {
                    // No params to scan
                    $keys = [];
                }

                // Return an error if BB code is bad
                if (!is_array($keys)) {
                    array_push($rdest, '[INVALID URL BB CODE]');
                    continue;
                }

                // Check for EMPTY URL
                $urlREF = $this->validateURL((!$keys['href']) ? $alt : $keys['href']);

                if ($urlREF === false) {
                    // EMPTY, SKIP
                    array_push($rdest, $alt);
                    continue;
                }

                // Now let's compose a resulting URL
                $outkeys[] = 'href="'.$urlREF.'"';

                // Check if we have external URL
                $flagExternalURL = false;

                $dn = parse_url($urlREF);
                if (strlen($dn['host']) && !in_array($dn['host'], $SYSTEM_FLAGS['mydomains'])) {
                    $flagExternalURL = true;
                }

                // Check for rel=nofollow request for external links

                if ($config['url_external_nofollow'] && $flagExternalURL) {
                    $outkeys[] = 'rel="nofollow"';
                }

                if ($config['url_external_target_blank'] && $flagExternalURL && !isset($keys['target'])) {
                    $outkeys[] = 'target="_blank"';
                }

                // Now parse allowed tags and add it into output line
                foreach ($keys as $kn => $kv) {
                    switch ($kn) {
                        case 'class':
                        case 'target':
                            $v = str_replace([ord(0), ord(9), ord(10), ord(13), ' ', "'", '"', ';', ':', '<', '>', '&', '[', ']'], '', $kv);
                            $outkeys[] = $kn.'="'.$v.'"';
                            break;
                        case 'title':
                            $v = str_replace(['"', '[', ']', ord(0), ord(9), ord(10), ord(13), ':', '<', '>', '&'], ["'", '%5b', '%5d', ''], $kv);
                            $outkeys[] = $kn.'="'.$v.'"';
                            break;
                    }
                }
                // Fill an output replacing array
                array_push($rdest, '<a '.(implode(' ', $outkeys)).'>'.$alt.'</a>');
            }
            $content = str_replace($rsrc, $rdest, $content);
        }

        // Обработка кириллических символов для украинского языка
        $content = str_replace(['[CYR_I]', '[CYR_i]', '[CYR_E]', '[CYR_e]', '[CYR_II]', '[CYR_ii]'], ['&#1030;', '&#1110;', '&#1028;', '&#1108;', '&#1031;', '&#1111;'], $content);

        while (preg_match("#\[color=([^\]]+)\](.+?)\[/color\]#isu", $content, $res)) {
            $nl = $this->color(['style' => $res[1], 'text' => $res[2]]);
            $content = str_replace($res[0], $nl, $content);
        }

        return $content;
    }

    public function validateURL($url)
    {

        // Check for empty url
        if (trim($url) == '') {
            return false;
        }

        // Make replacement of dangerous symbols
        if (preg_match('#^(http|https|ftp)://(.+)$#u', $url, $mresult)) {
            return $mresult[1].'://'.str_replace([':', "'", '"', '[', ']'], ['%3A', '%27', '%22', '%5b', '%5d'], $mresult[2]);
        }

        // Process special `magnet` links
        if (preg_match('#^(magnet\:\?)(.+)$#u', $url, $mresult)) {
            return $mresult[1].str_replace([' ', "'", '"'], ['%20', '%27', '%22'], $mresult[2]);
        }

        return str_replace([':', "'", '"'], ['%3A', '%27', '%22'], $url);
    }

    public function htmlformatter($content)
    {
        global $config;

        if (!$config['use_htmlformatter']) {
            return $content;
        }

        $content = preg_replace('|<br />\s*<br />|', "\n\n", $content);
        $content = str_replace(["\r\n", "\r"], "\n", $content);
        $content = preg_replace("/\n\n+/", "\n\n", $content);
        $content = preg_replace('/\n/', '<br />', $content);
        $content = preg_replace('!<p>\s*(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)\s*</p>!', '$1', $content);
        $content = preg_replace('|<p>(<li.+?)</p>|', '$1', $content);
        $content = preg_replace('|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $content);
        $content = str_replace('</blockquote></p>', '</p></blockquote>', $content);
        $content = preg_replace('!<p>\s*(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)!', '$1', $content);
        $content = preg_replace('!(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)\s*</p>!', '$1', $content);
        $content = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $content);
        $content = preg_replace('!(</?(?:table|thead|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|math|p|h[1-6])[^>]*>)\s*<br />!', '$1', $content);
        $content = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)!', '$1', $content);
        $content = preg_replace_callback(
            "/<code>(.*?)<\/code>/s",
            function ($match) {
                return phphighlight($match[1]);
            },
            $content
        );
        $content = str_replace("\n</p>\n", '</p>', $content);

        return $content;
    }

    public function smilies($content)
    {
        global $config;

        if (!$config['use_smilies']) {
            return $content;
        }

        $smilies_arr = explode(',', $config['smilies']);
        foreach ($smilies_arr as $null => $smile) {
            $smile = trim($smile);
            $find[] = "':$smile:'";
            $replace[] = "<img class=\"smilies\" alt=\"$smile\" src=\"".skins_url."/smilies/$smile.png\" />";
        }

        return preg_replace($find, $replace, $content);
    }

    public function nameCheck($name)
    {
        return preg_match('#^[a-z0-9\_\-\.]+$#miu', $name);
    }

    public function translit($content, $allowDash = 0, $allowSlash = 0)
    {

        // $allowDash is not used any more

        $utf2enS = ['А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Ґ' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'jo', 'Є' => 'e', 'Ж' => 'zh', 'З' => 'z', 'И' => 'i', 'І' => 'i', 'Й' => 'i', 'Ї' => 'i', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o', 'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ў' => 'u', 'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c', 'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sz', 'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya'];
        $utf2enB = ['а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'є' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'і' => 'i', 'й' => 'i', 'ї' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sz', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', '&quot;' => '', '&amp;' => '', 'µ' => 'u', '№' => 'num'];

        $content = trim(strip_tags($content));
        $content = strtr($content, $utf2enS);
        $content = strtr($content, $utf2enB);

        $content = str_replace([' - '], ['-'], $content);
        $content = preg_replace("/\s+/ms", '-', $content);
        $content = preg_replace('/[ ]+/', '-', $content);

        $content = preg_replace("/[^a-z0-9_\-\.".($allowSlash ? '\/' : '').']+/mi', '', $content);
        $content = preg_replace('#-(-)+#', '-', $content);

        return $content;
    }

    public function color($arr)
    {
        $style = $arr['style'];
        $text = $arr['text'];
        $style = str_replace('&quot;', '', $style);
        $style = preg_replace("/[&\(\)\.\%\[\]<>\'\"]/", '', preg_replace('#^(.+?)(?:;|$)#', '$1', $style));
        $style = preg_replace("/[^\d\w\#\s]/s", '', $style);

        return '<span style="color:'.$style.'">'.$text.'</span>';
    }

    // Functions for HTML truncator
    public function joinAttributes($attributes)
    {
        $alist = [];
        foreach ($attributes as $aname => $aval) {
            $mark = (mb_strpos($aval, '"') === false) ? '"' : "'";
            $alist[] = $aname.'='.$mark.$aval.$mark;
        }

        return implode(' ', $alist);
    }

    public function truncateHTML($text, $size = 50, $finisher = '...')
    {
        $len = mb_strlen($text);

        if ($len <= $size) {
            return $text;
        }

        $textLen = 0;
        $position = -1;
        $tagNameStartPos = 0;
        $tagNameEndPos = 0;
        $openTagList = [];

        // Stateful machine status
        // 0 - scanning text
        // 1 - scanning tag name
        // 2 - scanning tag content
        // 3 - scanning tag attribute value
        // 4 - waiting for tag close mark
        $state = 0;

        // 0 - no quotes active
        // 1 - single quotes active
        // 2 - double quotes active
        $quoteType = 0;

        // Flag if 'tag close symbol' is used
        $closeFlag = 0;

        while ((($position + 1) < $len) && ($textLen < $size)) {
            $position++;
            $char = $text[$position];
            //	printf("%03u[%u][%03u][%02u] %s\n", $position, $state, $textLen, count($openTagList), $char);

            switch ($state) {
                    // Scanning text
                case 0:
                    // '<' - way to starting tag
                    if ($char == '<') {
                        $state = 1;
                        $tagNameStartPos = $position + 1;
                        break;
                    }
                    $textLen++;

                    break;
                case 1:
                    // If this is a space/tab - tag name is finished
                    if (($char == ' ') || ($char == "\t")) {
                        $tagNameLen = $position - $tagNameStartPos;
                        $state = 2;
                        break;
                    }

                    // Activity on tag close flag
                    if ($char == '/') {
                        if ($tagNameStartPos == $position) {
                            break;
                        }

                        $tagNameLen = $position - $tagNameStartPos + 1;
                        $state = 4;
                        break;
                    }

                    // Action on tag closing
                    if ($char == '>') {
                        $tagNameLen = $position - $tagNameStartPos;
                        $tagName = mb_substr($text, $tagNameStartPos, $tagNameLen);
                        //		print "openTag[1]: $tagName\n";

                        // Closing tag
                        if ($tagName[0] == '/') {
                            if ((count($openTagList)) && ($openTagList[count($openTagList) - 1] == mb_substr($tagName, 1))) {
                                array_pop($openTagList);
                            }
                        } else {
                            // Opening tag
                            if (mb_substr($tagName, -1, 1) != '/') {
                                // And not closed at the same time
                                array_push($openTagList, $tagName);
                            }
                        }
                        $state = 0;
                        break;
                    }

                    // Tag name may contain only english letters
                    if (!((($char >= 'A') && ($char <= 'Z')) || (($char >= 'a') && ($char <= 'z')))) {
                        $state = 0;
                        break;
                    }
                    break;
                case 2:
                    // Activity on tag close flag
                    if ($char == '/') {
                        $state = 4;
                        break;
                    }

                    // Action on tag closing
                    if ($char == '>') {
                        $tagName = mb_substr($text, $tagNameStartPos, $tagNameLen);
                        //		print "openTag: $tagName\n";

                        // Closing tag
                        if ((count($openTagList)) && ($openTagList[count($openTagList) - 1] == mb_substr($tagName, 1))) {
                            if ($openTagList[count($openTagList)] == mb_substr($tagName, 1)) {
                                array_pop($openTagList);
                            }
                        } else {
                            // Opening tag
                            if (mb_substr($tagName, -1, 1) != '/') {
                                // And not closed at the same time
                                array_push($openTagList, $tagName);
                            }
                        }
                        $state = 0;
                        break;
                    }

                    // Action on quote
                    if (($char == '"') || ($char == "'")) {
                        $quoteType = ($char == '"') ? 2 : 1;
                        $state = 3;
                        break;
                    }
                    break;
                case 3:
                    // Act only on quote
                    if ((($char == '"') && ($quoteType == 2)) || (($char == "'") && ($quoteType == 1))) {
                        $state = 2;
                        break;
                    }
                    break;
                case 4:
                    // Only spaces or tag close mark is accepted
                    if (($char == ' ') || ($char == "\t")) {
                        break;
                    }

                    if ($char == '>') {
                        $tagName = mb_substr($text, $tagNameStartPos, $tagNameLen);
                        //			print "openTag: $tagName\n";

                        // Closing tag
                        if ($tagName[0] != '/') {
                            if ((count($openTagList)) && ($openTagList[count($openTagList) - 1] == mb_substr($tagName, 1))) {
                                array_pop($openTagList);
                            }
                        } else {
                            // Opening tag
                            if (mb_substr($tagName, -1, 1) != '/') {
                                // And not closed at the same time
                                array_push($openTagList, $tagName);
                            }
                        }
                        $state = 0;
                        break;
                    }

                    // Wrong symbol [ this is wholy text ]
                    $state = 0;
                    break;
            }
        }

        $output = mb_substr($text, 0, $position + 1).((($position + 1) != $len) ? $finisher : '');

        // Check if we have opened tags
        while ($tag = array_pop($openTagList)) {
            $output .= '</'.$tag.'>';
        }

        return $output;
    }

    // Process [attach] BB code
    public function parseBBAttach($content, $db, $templateVariables = [])
    {
        global $config;

        $dataCache = [];
        if (preg_match_all("#\[attach(\#\d+){0,1}\](.*?)\[\/attach\]#isu", $content, $pcatch, PREG_SET_ORDER)) {
            $rsrc = [];
            $rdest = [];

            foreach ($pcatch as $catch) {

                // Find attach UID
                if ($catch[1] != '') {
                    $uid = mb_substr($catch[1], 1);
                    $title = $catch[2];
                } else {
                    $uid = $catch[2];
                }

                if (is_numeric($uid)) {
                    array_push($rsrc, $catch[0]);
                    $rec = [];
                    if (is_array($dataCache[$uid])) {
                        $rec = $dataCache[$uid];
                    } else {
                        $rec = $db->record('select * from '.prefix.'_files where id = '.db_squote($uid));
                        if (is_array($rec)) {
                            $dataCache[$uid] = $rec;
                        }
                    }
                    if (is_array($rec)) {
                        // Generate file ULR
                        $fname = ($rec['storage'] ? $config['attach_dir'] : $config['files_dir']).$rec['folder'].'/'.$rec['name'];
                        $fsize = (file_exists($fname) && ($fsize = @filesize($fname))) ? Formatsize($fsize) : 'n/a';

                        $params = [
                            'url'   => ($rec['storage'] ? $config['attach_url'] : $config['files_url']).'/'.$rec['folder'].'/'.$rec['name'],
                            'title' => ($title == '') ? $rec['orig_name'] : $title,
                            'size'  => $fsize,
                        ];
                        array_push($rdest, str_replace(['{url}', '{title}', '{size}'], [$params['url'], $params['title'], $params['size']], $templateVariables['bbcodes']['attach.format']));
                    } else {
                        array_push($rdest, $templateVariables['bbcodes']['attach.nonexist']);
                    }
                }
            }
            $content = str_replace($rsrc, $rdest, $content);
        }

        // Scan for separate {attach#ID.url}, {attach#ID.size}, {attach#ID.name}, {attach#ID.ext}
        if (preg_match_all("#\{attach\#(\d+)\.(url|size|name|ext|fname)\}#isu", $content, $pcatch, PREG_SET_ORDER)) {
            $rsrc = [];
            $rdest = [];

            foreach ($pcatch as $catch) {
                if (is_numeric($uid = $catch[1])) {
                    array_push($rsrc, $catch[0]);
                    $rec = [];
                    if (is_array($dataCache[$uid])) {
                        $rec = $dataCache[$uid];
                    } else {
                        $rec = $db->record('select * from '.prefix.'_files where id = '.db_squote($uid));
                        if (is_array($rec)) {
                            $dataCache[$uid] = $rec;
                        }
                    }
                    if (is_array($rec)) {
                        // Generate file ULR
                        $fname = ($rec['storage'] ? $config['attach_dir'] : $config['files_dir']).$rec['folder'].'/'.$rec['name'];

                        // Decide what to do
                        switch ($catch[2]) {
                            case 'url':
                                array_push($rdest, ($rec['storage'] ? $config['attach_url'] : $config['files_url']).'/'.$rec['folder'].'/'.$rec['name']);
                                break;
                            case 'size':
                                array_push($rdest, (file_exists($fname) && ($fsize = @filesize($fname))) ? Formatsize($fsize) : 'n/a');
                                break;
                            case 'name':
                                $dots = explode('.', $rec['orig_name']);
                                if (count($dots) > 1) {
                                    array_pop($dots);
                                }
                                array_push($rdest, implode('.', $dots));
                                break;
                            case 'ext':
                                $dots = explode('.', $rec['orig_name']);
                                if (count($dots) > 1) {
                                    array_push($rdest, array_pop($dots));
                                } else {
                                    array_push($rdest, '');
                                }

                                break;
                            case 'fname':
                                array_push($rdest, $rec['orig_name']);
                                break;
                        }
                    } else {
                        array_push($rdest, $templateVariables['bbcodes']['attach.nonexist']);
                    }
                }
            }
            $content = str_replace($rsrc, $rdest, $content);
        }

        return $content;
    }
}
