<?php
/**
 * Minimal stub bootstrap for running Awan Tools plugins standalone in Replit.
 * Mirrors the interface of the real Awan Tools platform bootstrap so plugins
 * work without modification.
 */

defined('AWAN')      or define('AWAN',      true);
defined('AWAN_DEBUG') or define('AWAN_DEBUG', false);
define('AWAN_START', microtime(true));

// Path constants (mirror _config.php)
defined('AWAN_ROOT')    or define('AWAN_ROOT',    __DIR__);
defined('PLUGINS_PATH') or define('PLUGINS_PATH', __DIR__);
defined('STORAGE_PATH') or define('STORAGE_PATH', __DIR__ . '/.storage');
defined('LOGS_PATH')    or define('LOGS_PATH',    __DIR__ . '/.storage/logs');
defined('UPLOADS_PATH') or define('UPLOADS_PATH', __DIR__ . '/.storage/uploads');
defined('ADMIN_ROLE')   or define('ADMIN_ROLE',   'admin');
defined('SUPER_ROLE')   or define('SUPER_ROLE',   'superadmin');

// ─── Stub: Database ───────────────────────────────────────────────────────────
class Database {
    private static ?self $instance = null;
    public static function getInstance(): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function fetch(string $sql, array $params = []): array|false { return false; }
    public function fetchAll(string $sql, array $params = []): array { return []; }
    public function insert(string $table, array $data): int { return 0; }
    public function update(string $table, array $data, string $where = '', array $params = []): int { return 0; }
    public function execute(string $sql, array $params = []): bool { return true; }
    public function exists(string $table, string $where = '', array $params = []): bool { return false; }
    public function count(string $table, string $where = '', array $params = []): int { return 0; }
    public function delete(string $table, string $where = '', array $params = []): int { return 0; }
}

// ─── Stub: Session ────────────────────────────────────────────────────────────
class Session {
    public static function start(): void {}
    public static function flash(string $key, string $msg): void {}
    public static function get(string $key, mixed $default = null): mixed { return $default; }
    public static function set(string $key, mixed $value): void {}
    public static function has(string $key): bool { return false; }
    public static function remove(string $key): void {}
    public static function destroy(): void {}
    public static function regenerate(): void {}
}

// ─── Stub: Security ──────────────────────────────────────────────────────────
class Security {
    public static function sanitize(mixed $v): mixed { return $v; }
    public static function csrfToken(): string { return bin2hex(random_bytes(16)); }
    public static function verifyCsrf(string $token): bool { return true; }
    public static function hashPassword(string $password): string { return password_hash($password, PASSWORD_DEFAULT); }
    public static function verifyPassword(string $password, string $hash): bool { return false; }
}

// ─── Stub: Logger ─────────────────────────────────────────────────────────────
class Logger {
    private static ?self $instance = null;
    public static function getInstance(mixed $db = null): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function info(string $msg, array $ctx = []): void {}
    public function error(string $msg, array $ctx = []): void {}
    public function warning(string $msg, array $ctx = []): void {}
    public function debug(string $msg, array $ctx = []): void {}
}

// ─── Stub: Settings ──────────────────────────────────────────────────────────
class Settings {
    private static ?self $instance = null;
    private array $values = [
        'site_name'         => 'Awan Tools',
        'site_url'          => '',
        'analytics_enabled' => '0',
        'language'          => 'en',
        'timezone'          => 'UTC',
    ];
    public static function getInstance(mixed $db = null): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function get(string $key, mixed $default = null): mixed {
        return $this->values[$key] ?? $default;
    }
    public function set(string $key, mixed $value): void {
        $this->values[$key] = $value;
    }
    public function all(): array { return $this->values; }
}

// ─── Stub: Auth ───────────────────────────────────────────────────────────────
class Auth {
    private static ?self $instance = null;
    public static function getInstance(mixed $db = null, mixed $settings = null): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function check(): bool { return false; }
    public function id(): ?int { return null; }
    public function user(): ?array { return null; }
    public function hasRole(string $role): bool { return false; }
    public function login(array $user): void {}
    public function logout(): void {}
}

// ─── Stub: Theme ─────────────────────────────────────────────────────────────
class Theme {
    private static ?self $instance = null;
    public static function getInstance(mixed $db = null, mixed $settings = null): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function template(string $name): string { return ''; }
    public function get(string $key, mixed $default = null): mixed { return $default; }
}

// ─── Stub: Plugin ─────────────────────────────────────────────────────────────
class Plugin {
    public static function get(string $slug): ?array { return null; }
    public static function isActive(string $slug): bool { return true; }
}

// ─── Stub: Lang ──────────────────────────────────────────────────────────────
class Lang {
    private static ?self $instance = null;
    public static function getInstance(string $lang = 'en'): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function t(string $key, array $replace = []): string { return $key; }
    public function get(string $key, mixed $default = ''): mixed { return $default; }
}

// ─── Stub: Mailer ─────────────────────────────────────────────────────────────
class Mailer {
    private static ?self $instance = null;
    public static function getInstance(mixed $settings = null): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function send(string $to, string $subject, string $body, bool $isHtml = true): bool { return false; }
    public static function html(string $siteName, string $title, string $body, string $ctaText = '', string $ctaUrl = ''): string {
        return "<html><body><h1>{$title}</h1>{$body}</body></html>";
    }
}

// ─── Stub: Scheduler ─────────────────────────────────────────────────────────
class Scheduler {
    public static function register(string $slug, string $name, string $desc, int $interval, callable $callback): void {}
}

// ─── Stub: Seo ───────────────────────────────────────────────────────────────
class Seo {
    private static ?self $instance = null;
    public static function getInstance(mixed $settings = null): static {
        if (!self::$instance) self::$instance = new static();
        return self::$instance;
    }
    public function meta(string $key, mixed $default = ''): mixed { return $default; }
}

// ─── Stub: Shortcode ─────────────────────────────────────────────────────────
class Shortcode {
    public static function registerDefaults(): void {}
    public static function parse(string $content): string { return $content; }
}

// ─── Stub: Totp ──────────────────────────────────────────────────────────────
class Totp {
    public static function verify(string $secret, string $code): bool { return false; }
}

// ─── Helper functions ─────────────────────────────────────────────────────────

function redirect(string $url, int $code = 302): void {
    header("Location: $url", true, $code);
    exit;
}

function requireLogin(string $redirect = ''): void {}

function requireAdmin(): void {}

function requireSuperAdmin(): void {}

function renderError(int $code, string $title, string $message): string {
    return "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>{$code} {$title}</title></head>"
         . "<body><div style='padding:2rem;font-family:sans-serif'>"
         . "<h1>{$code} " . htmlspecialchars($title) . "</h1>"
         . "<p>" . htmlspecialchars($message) . "</p>"
         . "<a href='/'>Go Home</a>"
         . "</div></body></html>";
}

function jsonResponse(array $data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/** HTML-escape a string for output. */
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/** Format a datetime string. */
function fdate(string $datetime, string $format = 'M j, Y'): string {
    try {
        return (new DateTime($datetime))->format($format);
    } catch (Throwable $e) {
        return $datetime;
    }
}

/** Return absolute site URL. */
function siteUrl(string $path = ''): string {
    global $settings;
    $base = rtrim($settings->get('site_url', ''), '/');
    if (!$base) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $base   = $scheme . '://' . $host;
    }
    return $path !== '' ? $base . '/' . ltrim($path, '/') : $base;
}

/** Bot/crawler detection based on User-Agent string. */
function isBot(): bool {
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    if (empty($ua)) return true;
    $patterns = [
        'bot', 'spider', 'crawl', 'slurp', 'wget', 'curl/', 'python-requests',
        'scrapy', 'java/', 'libwww', 'httpunit', 'nutch', 'go-http-client',
        'phpcrawl', 'archive.org', 'heritrix', 'yandexbot', 'baiduspider',
        'ahrefsbot', 'semrushbot', 'dotbot', 'mj12bot', 'petalbot',
        'facebookexternalhit', 'linkedinbot', 'twitterbot', 'whatsapp',
        'applebot', 'googlebot', 'bingbot', 'duckduckbot',
    ];
    foreach ($patterns as $p) {
        if (str_contains($ua, $p)) return true;
    }
    return false;
}

// ─── Initialize globals ───────────────────────────────────────────────────────
$db       = Database::getInstance();
$settings = Settings::getInstance($db);
$auth     = Auth::getInstance($db, $settings);
$theme    = Theme::getInstance($db, $settings);
$lang     = Lang::getInstance($settings->get('language', 'en'));
$mailer   = Mailer::getInstance($settings);
$seo      = Seo::getInstance($settings);
$logger   = Logger::getInstance($db);

Shortcode::registerDefaults();
Session::start();
date_default_timezone_set($settings->get('timezone', 'UTC'));

// Load the standalone page renderer (defines plugin_render before _sdk.php does)
require_once __DIR__ . '/_platform.php';
