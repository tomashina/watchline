<?php

use Valet\Drivers\BasicValetDriver;

// A production web request must not execute this Herd-only helper.
if (!class_exists(BasicValetDriver::class)) {
    http_response_code(404);
    exit;
}

/**
 * Herd routing for this OpenCart 2.3 installation.
 *
 * Herd does not process Apache rewrite rules, so clean URLs must be forwarded
 * through OpenCart's `_route_` parameter. Sensitive and executable files are
 * also kept out of the local HTTP surface.
 */
class LocalValetDriver extends BasicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return is_file($sitePath . '/config.php')
            && is_file($sitePath . '/index.php')
            && is_dir($sitePath . '/catalog')
            && is_dir($sitePath . '/system');
    }

    public function beforeLoading(string $sitePath, string $siteName, string $uri): void
    {
        parent::beforeLoading($sitePath, $siteName, $uri);

        $path = trim(parse_url($uri, PHP_URL_PATH) ?: '', '/');

        if ($path === ''
            || $path === 'index.php'
            || $path === 'admin'
            || strpos($path, 'admin/') === 0
            || file_exists($sitePath . '/' . $path)
            || isset($_GET['route'])
            || isset($_GET['_route_'])
        ) {
            return;
        }

        $_GET['_route_'] = $path;
        $_REQUEST['_route_'] = $path;
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)
    {
        if ($this->isProtectedUri($uri)) {
            return false;
        }

        return parent::isStaticFile($sitePath, $siteName, $uri);
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        if ($this->isProtectedUri($uri)) {
            return null;
        }

        return parent::frontControllerPath($sitePath, $siteName, $uri);
    }

    private function isProtectedUri(string $uri): bool
    {
        $path = trim(parse_url($uri, PHP_URL_PATH) ?: '', '/');

        if ($path === '') {
            return false;
        }

        foreach (explode('/', $path) as $segment) {
            if ($segment !== '' && $segment[0] === '.') {
                return true;
            }
        }

        if (strpos($path, 'system/') === 0
            || strpos($path, 'vqmod/') === 0
        ) {
            return true;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'php'
            && !in_array($path, ['index.php', 'admin/index.php'], true)
        ) {
            return true;
        }

        return in_array($extension, [
            'cache',
            'csv',
            'ini',
            'log',
            'pem',
            'sql',
            'tpl',
            'twig',
            'yaml',
            'yml',
        ], true);
    }
}
