<?php

declare(strict_types=1);

/**
 * Path helpers so CSS and routes work when the app lives in a subfolder (e.g. /careerlabb/).
 */
if (!function_exists('careerlabb_web_root')) {
    function careerlabb_web_root(): string
    {
        $dir = dirname(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''));
        $dir = trim($dir, '/');
        return $dir === '.' ? '' : $dir;
    }
}

if (!function_exists('careerlabb_asset')) {
    function careerlabb_asset(string $relativePath): string
    {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
        $root = careerlabb_web_root();
        return $root === '' ? '/' . $relativePath : '/' . $root . '/' . $relativePath;
    }
}

if (!function_exists('careerlabb_route')) {
    /** @param string $query e.g. page=accueil */
    function careerlabb_route(string $query): string
    {
        $root = careerlabb_web_root();
        $index = $root === '' ? '/index.php' : '/' . $root . '/index.php';
        $q = ltrim($query, '?');
        return $index . '?' . $q;
    }
}
