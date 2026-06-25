<?php
/**
 * Standalone router for Awan Tools plugins in Replit.
 * Run with: php -S 0.0.0.0:5000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = '/' . trim($uri, '/');

define('AWAN', true);
define('AWAN_ROOT', __DIR__);
define('PLUGINS_PATH', __DIR__);

// ── Static asset passthrough ──────────────────────────────────────────────────
if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|map)$/', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        return false; // serve statically by PHP built-in server
    }
}

// ── Bootstrap helper (called once, ensures stubs + symlinks exist) ────────────
function ensure_bootstrap(): void {
    static $done = false;
    if ($done) return;
    $done = true;

    // Plugins use paths like __DIR__ . '/../../_bootstrap.php'
    // From workspace/json-tools/, that resolves to /home/runner/_bootstrap.php
    // We create symlinks at runtime to make those paths work.
    $root    = dirname(__DIR__); // /home/runner
    $project = __DIR__;         // /home/runner/workspace

    // /home/runner/_bootstrap.php → workspace/_bootstrap.php
    $tb = $root . '/_bootstrap.php';
    if (!file_exists($tb) && !is_link($tb)) {
        @symlink($project . '/_bootstrap.php', $tb);
    }

    // /home/runner/_core → workspace/_core
    $tc = $root . '/_core';
    if (!file_exists($tc) && !is_link($tc)) {
        @symlink($project . '/_core', $tc);
    }

    // /home/runner/plugins/_sdk.php → workspace/_sdk.php
    $pd = $root . '/plugins';
    if (!is_dir($pd)) @mkdir($pd, 0755, true);
    $ts = $pd . '/_sdk.php';
    if (!file_exists($ts) && !is_link($ts)) {
        @symlink($project . '/_sdk.php', $ts);
    }
}

// ── Plugin routes: /plugins/{slug}/ ──────────────────────────────────────────
if (preg_match('#^/plugins/([a-z0-9_-]+)/?$#', $uri, $m)) {
    $slug = $m[1];
    $plugin_index = __DIR__ . '/' . $slug . '/index.php';
    if (file_exists($plugin_index)) {
        ensure_bootstrap();
        // Pre-load our bootstrap so stubs + plugin_render are defined before
        // the plugin's own require_once calls (which use require_once, so they
        // will be no-ops once already included).
        require_once __DIR__ . '/_bootstrap.php';
        require $plugin_index;
        return;
    }
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404</title></head><body><h1>Plugin not found: '
        . htmlspecialchars($slug) . '</h1><p><a href="/">Back to plugins</a></p></body></html>';
    return;
}

// ── Home: plugin listing ──────────────────────────────────────────────────────
if ($uri === '/' || $uri === '/plugins' || $uri === '/plugins/') {
    require __DIR__ . '/_home.php';
    return;
}

// ── Fallback 404 ─────────────────────────────────────────────────────────────
http_response_code(404);
echo '<!DOCTYPE html><html><head><title>404</title></head><body><h1>404 Not Found</h1><p><a href="/">Back to plugins</a></p></body></html>';
