<?php

//
// Copyright (C) 2006-2020 Next Generation CMS (http://ngcms.ru/)
// Name: docs.php
// Description: Docs viewer
//

// Protect against hack attempts
if (!defined('NGCMS')) {
    exit('HAL');
}

use Michelf\MarkdownExtra;

/**
 * Determine if a given string ends with a given substring.
 *
 * @see https://github.com/laravel/framework/blob/7.x/src/Illuminate/Support/Str.php
 *
 * @param mixed $haystack
 * @param mixed $needles
 *
 * @return bool
 */
function endsWith($haystack, $needles)
{
    foreach ((array) $needles as $needle) {
        if ($needle !== '' && substr($haystack, -strlen($needle)) === (string) $needle) {
            return true;
        }
    }

    return false;
}

/**
 * Determine if a given string starts with a given substring.
 *
 * @see https://github.com/laravel/framework/blob/7.x/src/Illuminate/Support/Str.php
 *
 * @param mixed $haystack
 * @param mixed $needles
 *
 * @return bool
 */
function startsWith($haystack, $needles)
{
    foreach ((array) $needles as $needle) {
        if ((string) $needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0) {
            return true;
        }
    }

    return false;
}

/**
 * Get markdown parser.
 *
 * @return Michelf\MarkdownInterface
 */
function getMarkdownParser()
{
    $parser = new MarkdownExtra();

    // enable nl2br
    $parser->hard_wrap = true;

    /**
     * Url filter.
     *
     * @param string $url
     *
     * @return string
     */
    $parser->url_filter_func = function ($url) {
        global $config;

        $docUrl = $config['admin_url'].'/admin.php?mod=docs';

        if ($url === '') {
            return $docUrl;
        }

        if (startsWith($url, ['http:', 'https:'])) {
            return $url;
        }

        if (endsWith($url, ['.md'])) {
            return $docUrl.'&file='.$url;
        } else {
            return $config['home_url'].'/docs/'.$url;
        }
    };

    return $parser;
}

/**
 * Render markdown.
 *
 * @param string $file
 *
 * @return string|bool
 */
function renderMarkdown($file)
{
    global $main_admin;

    $file = str_replace(['../', './'], '', $file);

    if (mb_strlen($file) > 0 && !startsWith($file, '/') && endsWith($file, '.md')) {
        $paths = [
            root,
            site_root.'docs/',
        ];

        foreach ($paths as $path) {
            if (file_exists($path.$file)) {
                $content = file_get_contents($path.$file);
            }
        }

        if (mb_strlen($content) > 0) {
            $parser = getMarkdownParser();

            return  $parser->transform($content);
        }
    }

    return false;
}

function renderDocs()
{
    global $main_admin, $twig;

    $file = $_REQUEST['file'] ?? 'about.md';

    $menu = renderMarkdown('menu.md');
    $docs = renderMarkdown($file);

    $tVars = [
        'menu' => $menu,
        'docs' => $docs,
    ];

    $xt = $twig->loadTemplate('skins/default/tpl/docs.tpl');

    $main_admin = $xt->render($tVars);
}

renderDocs();
