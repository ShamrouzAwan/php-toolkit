# Plugin Examples & Code Cookbook

All working code patterns for building Awan Tools plugins. Copy-paste ready.

---

## 1. Minimal Plugin (No Database, No Auth)

The simplest possible plugin — a single-page utility tool.

### `plugin.json`

```json
{
    "name": "Word Counter",
    "slug": "word-counter",
    "version": "1.0.0",
    "description": "Count words, characters, sentences, and reading time for any text.",
    "author": "Your Name",
    "author_url": "https://yoursite.com",
    "license": "MIT",
    "min_php": "8.0",
    "offered": 4,
    "requires_login": false,
    "stores_user_data": false,
    "analytics_enabled": true,
    "categories": ["Text Tools", "Utilities"],
    "keywords": ["word counter", "character counter", "reading time"],
    "icon": "<svg viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><line x1=\"8\" y1=\"6\" x2=\"21\" y2=\"6\"/><line x1=\"8\" y1=\"12\" x2=\"21\" y2=\"12\"/><line x1=\"8\" y1=\"18\" x2=\"21\" y2=\"18\"/><line x1=\"3\" y1=\"6\" x2=\"3.01\" y2=\"6\"/><line x1=\"3\" y1=\"12\" x2=\"3.01\" y2=\"12\"/><line x1=\"3\" y1=\"18\" x2=\"3.01\" y2=\"18\"/></svg>"
}
```

### `index.php`

```php
<?php
defined('AWAN') or die();
require_once __DIR__ . '/../../_bootstrap.php';
require_once __DIR__ . '/../../plugins/_sdk.php';

$slug = 'word-counter';
plugin_track('plugin_view', '/plugins/word-counter/');

ob_start();
?>
<div class="wc-wrap">

    <div class="wc-hero">
        <div class="wc-hero-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
            </svg>
            Word Counter
        </div>
        <div class="wc-hero-subtitle">Count words, characters, sentences, and reading time instantly.</div>
    </div>

    <div class="card">
        <div class="card-body">
            <textarea id="wc-input" class="form-input" rows="8"
                placeholder="Paste or type your text here…"
                oninput="WC.count()"></textarea>
            <div id="wc-stats" class="wc-stats" style="display:none">
                <div class="wc-stat"><span id="wc-words">0</span><label>Words</label></div>
                <div class="wc-stat"><span id="wc-chars">0</span><label>Characters</label></div>
                <div class="wc-stat"><span id="wc-sentences">0</span><label>Sentences</label></div>
                <div class="wc-stat"><span id="wc-read">0 min</span><label>Read Time</label></div>
            </div>
        </div>
    </div>

</div>
<?php
$content = ob_get_clean();

$css = '<link rel="stylesheet" href="/plugins/word-counter/assets/wc.css?v=' . filemtime(__DIR__ . '/assets/wc.css') . '">';
$js  = '<script src="/plugins/word-counter/assets/wc.js?v=' . filemtime(__DIR__ . '/assets/wc.js') . '"></script>';

plugin_render('Word Counter', $css . $content . $js);
```

### `on_activate.php`

```php
<?php
defined('AWAN') or die();
// No database tables required for this plugin.
```

### `on_deactivate.php`

```php
<?php
defined('AWAN') or die();
// Nothing to do on deactivation.
```

### `on_uninstall.php`

```php
<?php
defined('AWAN') or die();
// No tables were created, nothing to drop.
```

---

## 2. Plugin With Database Tables

A plugin that stores data — uses lifecycle hooks to create/drop tables.

### `on_activate.php`

```php
<?php
defined('AWAN') or die();
// $db is injected by Plugin::runHook()

$notesTable = plugin_table('my-notes', 'items');

$db->query("CREATE TABLE IF NOT EXISTS {$notesTable} (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id    INTEGER NOT NULL,
    title      VARCHAR(255) NOT NULL,
    content    TEXT         DEFAULT NULL,
    created_at TEXT         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT         DEFAULT NULL
)");
```

### `on_uninstall.php`

```php
<?php
defined('AWAN') or die();
// Drop all tables this plugin created.
$notesTable = plugin_table('my-notes', 'items');
$db->query("DROP TABLE IF EXISTS {$notesTable}");
```

### Reading & Writing in `index.php`

```php
<?php
defined('AWAN') or die();
require_once __DIR__ . '/../../_bootstrap.php';
require_once __DIR__ . '/../../plugins/_sdk.php';

// Require login for this plugin
plugin_requires_login('my-notes');

$slug  = 'my-notes';
$table = plugin_table($slug, 'items');
$userId = $auth->id();

// Handle POST — create a note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::verifyCsrf();
    $title   = plugin_input('title');
    $content = plugin_input('content');

    if ($title !== '') {
        $db->insert($table, [
            'user_id'    => $userId,
            'title'      => $title,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        plugin_flash_success('Note saved!');
    } else {
        plugin_flash_danger('Title is required.');
    }
    plugin_redirect($slug);
}

// Fetch notes for current user
$notes = $db->fetchAll("SELECT * FROM {$table} WHERE user_id = ? ORDER BY created_at DESC", [$userId]);

ob_start();
?>
<div class="mn-wrap">

    <!-- Flash messages from platform session -->
    <?php if ($msg = Session::getFlash('success')): ?>
        <div class="alert alert-success"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = Session::getFlash('danger')): ?>
        <div class="alert alert-danger"><?= e($msg) ?></div>
    <?php endif; ?>

    <form method="POST" class="card" style="margin-bottom:20px">
        <?= Security::csrfField() ?>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Title <span class="req">*</span></label>
                <input type="text" name="title" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-input" rows="4"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Note</button>
        </div>
    </form>

    <?php foreach ($notes as $note): ?>
    <div class="card" style="margin-bottom:12px">
        <div class="card-body">
            <strong><?= e($note['title']) ?></strong>
            <p style="color:var(--color-text-muted);font-size:13px"><?= e($note['content']) ?></p>
            <small style="color:var(--color-text-muted)"><?= fdate($note['created_at']) ?></small>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($notes)): ?>
        <div class="alert alert-info">No notes yet. Create your first one above.</div>
    <?php endif; ?>

</div>
<?php
$content = ob_get_clean();
plugin_render('My Notes', $content);
```

---

## 3. Login-Gated Plugin

```php
<?php
defined('AWAN') or die();
require_once __DIR__ . '/../../_bootstrap.php';
require_once __DIR__ . '/../../plugins/_sdk.php';

// Redirect to /login if not authenticated
plugin_requires_login('my-plugin');

// From here, $auth->check() is always true
echo "Hello, " . e($auth->name());
```

---

## 4. Sending Email From a Plugin

```php
// Simple plain-text email
plugin_send_email('user@example.com', 'Welcome!', '<p>Thanks for signing up.</p>');

// Branded HTML email with a CTA button
plugin_email_html(
    to:      $auth->email(),
    subject: 'Your report is ready',
    title:   'Report Ready',
    body:    '<p>Your weekly analytics report has been generated.</p>',
    ctaText: 'View Report',
    ctaUrl:  siteUrl('/plugins/analytics/report')
);
```

---

## 5. Tracking Analytics Events

```php
// Track any custom event — shows up in the admin analytics panel
plugin_track('tool_used', '/plugins/my-plugin/', [
    'plugin_slug' => 'my-plugin',
]);

// Or more specific events
plugin_track('file_downloaded', '/plugins/github-toolkit/', [
    'plugin_slug' => 'github-toolkit',
]);
```

---

## 6. Flash Messages

```php
// Set flash message (survives one redirect)
plugin_flash_success('Your changes were saved.');
plugin_flash_danger('Something went wrong.');
plugin_flash_info('Processing in background…');

plugin_redirect('my-plugin');

// Read flash in the template
<?php if ($msg = Session::getFlash('success')): ?>
    <div class="alert alert-success"><?= e($msg) ?></div>
<?php endif; ?>
```

---

## 7. Reading Settings

```php
// Read any platform setting
$siteName    = $settings->get('site_name', 'My Site');
$siteEmail   = $settings->get('site_email', '');
$timezone    = $settings->get('timezone', 'UTC');
$isAnalytics = $settings->get('analytics_enabled', '1') === '1';

// Read plugin-specific setting stored by your plugin
$myOption = $settings->get('my_plugin_option', 'default_value');

// Write a setting
$settings->set('my_plugin_last_run', date('Y-m-d H:i:s'), 'my-plugin');
```

---

## 8. Input Sanitization

```php
// Sanitize POST input (strips tags, trims whitespace)
$name  = plugin_input('name');               // POST by default
$query = plugin_input('q', '', 'GET');       // GET parameter
$limit = Security::sanitizeInt($_GET['n'] ?? 10);
$email = Security::sanitizeEmail($_POST['email'] ?? '');

// Validate
if (!Security::validateEmail($email)) {
    plugin_flash_danger('Invalid email address.');
    plugin_redirect('my-plugin');
}

// Sanitize a slug
$slug = Security::sanitizeSlug($_POST['slug'] ?? '');
// "My Plugin Name!" → "my-plugin-name"
```

---

## 9. CSRF Protection

```php
// In the HTML form
<form method="POST">
    <?= Security::csrfField() ?>
    <!-- form fields -->
</form>

// At the top of the POST handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Security::verifyCsrf(); // dies with 419 if token mismatch
    // safe to process form now
}
```

---

## 10. Rate Limiting

```php
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$key = 'my_plugin_submit_' . $ip;

if (!Security::checkRateLimit($key, maxAttempts: 10, window: 3600)) {
    jsonResponse(['error' => 'Too many requests. Try again later.'], 429);
}

// On success, optionally clear the counter
Security::clearRateLimit($key);
```

---

## 11. JSON API Responses

```php
// Return JSON from a plugin (for AJAX endpoints)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $query = $data['query'] ?? '';

    if (empty($query)) {
        jsonResponse(['error' => 'Query is required.'], 400);
    }

    // process...
    jsonResponse(['result' => 'success', 'data' => $result]);
}
```

---

## 12. Registering a Scheduled Task

Call this in `on_activate.php`:

```php
<?php
defined('AWAN') or die();
require_once __DIR__ . '/../../plugins/_sdk.php';

plugin_register_task(
    slug:     'my-plugin-cleanup',
    name:     'My Plugin Cleanup',
    desc:     'Removes old processed items from the my-plugin table.',
    interval: 86400, // every 24 hours
    callback: function () use ($db) {
        $table = plugin_table('my-plugin', 'items');
        $db->query("DELETE FROM {$table} WHERE created_at < datetime('now', '-30 days')");
    }
);
```

---

## 13. Rendering a Sub-View File

For large plugins, split HTML into view files:

```php
// views/results.php
// File: my-plugin/views/results.php
<div class="mp-results">
    <?php foreach ($items as $item): ?>
        <div class="card"><?= e($item['name']) ?></div>
    <?php endforeach; ?>
</div>
```

```php
// In index.php — render the view and pass variables
$html = plugin_view('my-plugin', 'results', [
    'items' => $items,
]);
```

---

## 14. Related Tools Sidebar

```php
// At the top of index.php, get related plugin HTML
$related_html = plugin_related_html('my-plugin');

// Then at the bottom of the page content
echo $related_html;
```

---

## 15. Building Plugin URLs

```php
// URL to plugin root: /plugins/my-plugin/
$root = plugin_url('my-plugin');

// URL to a sub-path: /plugins/my-plugin/results/
$resultsUrl = plugin_url('my-plugin', 'results/');

// URL to an asset: /plugins/my-plugin/assets/logo.png
$logoUrl = plugin_asset('my-plugin', 'logo.png');

// Redirect to a plugin page
plugin_redirect('my-plugin', 'success');
```

---

## 16. Database Query Patterns

```php
// Fetch a single row
$user = $db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);

// Fetch multiple rows
$items = $db->fetchAll("SELECT * FROM {$table} WHERE user_id = ? ORDER BY created_at DESC LIMIT 20", [$userId]);

// Check if a row exists
if ($db->exists($table, 'user_id = ? AND slug = ?', [$userId, $slug])) {
    // already exists
}

// Count rows
$total = $db->count($table, 'user_id = ?', [$userId]);

// Insert
$newId = $db->insert($table, [
    'user_id'    => $userId,
    'title'      => $title,
    'created_at' => date('Y-m-d H:i:s'),
]);

// Update
$affected = $db->update($table,
    ['title' => $newTitle, 'updated_at' => date('Y-m-d H:i:s')],
    'id = ? AND user_id = ?',
    [$id, $userId]
);

// Delete
$db->delete($table, 'id = ? AND user_id = ?', [$id, $userId]);

// Transactions
$db->beginTransaction();
try {
    $db->insert($tableA, [...]);
    $db->update($tableB, [...], 'id = ?', [$id]);
    $db->commit();
} catch (Throwable $e) {
    $db->rollback();
    plugin_flash_danger('Transaction failed.');
}

// Raw query (complex joins, custom SQL)
$stmt = $db->query(
    "SELECT p.*, COUNT(c.id) as comment_count
     FROM {$postsTable} p
     LEFT JOIN {$commentsTable} c ON c.post_id = p.id
     WHERE p.user_id = ?
     GROUP BY p.id
     ORDER BY p.created_at DESC",
    [$userId]
);
$rows = $stmt->fetchAll();
```

---

## 17. Checking Auth State

```php
// Is anyone logged in?
if ($auth->check()) {
    $userId   = $auth->id();
    $email    = $auth->email();
    $name     = $auth->name();
    $username = $auth->username();
    $user     = $auth->user(); // full user row as array
}

// Role checks
if ($auth->isAdmin()) {
    // admin or super_admin
}
if ($auth->isSuperAdmin()) {
    // super_admin only
}
if ($auth->hasRole('writer')) {
    // has 'writer' role
}
```

---

## 18. HTML Escape Output

```php
// Always escape user data before outputting in HTML
echo e($user['name']);           // safe
echo $user['name'];              // UNSAFE — XSS risk

// Or in attributes
echo '<a href="' . e($url) . '">' . e($label) . '</a>';
```

---

## 19. Helper Functions

```php
// Format a date
echo fdate('2024-06-23 14:00:00');         // "Jun 23, 2024"
echo fdate('2024-06-23', 'Y/m/d');        // "2024/06/23"

// Build an absolute site URL
$url = siteUrl('/plugins/my-plugin/');     // "https://example.com/plugins/my-plugin/"

// Redirect
redirect('/login');                        // simple redirect
redirect(siteUrl('/dashboard'), 301);     // permanent redirect

// Require login (redirect if not authenticated)
requireLogin('/plugins/my-plugin/');

// Require admin role (redirect + 403 if not admin)
requireAdmin();

// Require super admin role
requireSuperAdmin();

// Return JSON response and exit
jsonResponse(['ok' => true]);
jsonResponse(['error' => 'Not found'], 404);

// Detect bots (exclude from analytics)
if (!isBot()) {
    plugin_track('tool_view', '/plugins/my-plugin/');
}
```

---

## 20. Bootstrap Hook (`_bootstrap.php`)

If your plugin needs to run code on **every request** (register shortcodes, add hooks, etc.), create a `_bootstrap.php` in your plugin root. It runs automatically for all active plugins on every page load.

```php
<?php
// my-plugin/_bootstrap.php
defined('AWAN') or die();

// Example: register a custom shortcode
Shortcode::register('my_plugin_link', function (array $attrs) {
    $url = plugin_url('my-plugin');
    return '<a href="' . e($url) . '" class="btn btn-outline btn-sm">Open My Plugin</a>';
});

// Example: add a notification badge on admin panel login
// (Check DB, set a notification, etc.)
```

> **Warning:** `_bootstrap.php` runs on every single request across the whole platform. Keep it extremely lightweight — no heavy DB queries, no slow file I/O.

---

## 21. Full Plugin Directory Structure (Advanced)

```
my-plugin/
├── plugin.json          # Manifest (required)
├── index.php            # Main page (required)
├── on_activate.php      # Lifecycle: install (required)
├── on_deactivate.php    # Lifecycle: deactivate (required)
├── on_uninstall.php     # Lifecycle: delete + drop tables (required)
├── _bootstrap.php       # Optional: runs on every request when active
├── README.md            # Optional but recommended
├── assets/
│   ├── my-plugin.css    # Plugin styles
│   └── my-plugin.js     # Plugin JavaScript
└── views/               # Optional: sub-view PHP templates
    ├── list.php
    ├── detail.php
    └── settings.php
```
