<?php
/**
 * Standalone home page — lists all available plugins.
 */
defined('AWAN') or define('AWAN', true);
defined('AWAN_ROOT') or define('AWAN_ROOT', __DIR__);
defined('PLUGINS_PATH') or define('PLUGINS_PATH', __DIR__);

// Discover plugins from directories that have a plugin.json
$plugins = [];
foreach (glob(__DIR__ . '/*/plugin.json') as $manifest_file) {
    $dir  = dirname($manifest_file);
    $slug = basename($dir);
    // Skip hidden / system dirs
    if (str_starts_with($slug, '_') || str_starts_with($slug, '.')) continue;
    $manifest = json_decode(file_get_contents($manifest_file), true);
    if (!$manifest) continue;
    $manifest['_slug'] = $slug;
    $manifest['_dir']  = $dir;
    $plugins[] = $manifest;
}
usort($plugins, fn($a, $b) => strcmp($a['name'] ?? '', $b['name'] ?? ''));

$total_tools = array_sum(array_column($plugins, 'offered'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Awan Tools — Plugin Collection</title>
<style>
/* ── Platform CSS Variables ── */
:root {
    --color-background: #f5f7fa;
    --color-surface: #ffffff;
    --color-surface-alt: #f0f2f5;
    --color-border: #e2e6ea;
    --color-text: #1a202c;
    --color-text-muted: #6b7280;
    --color-primary: #4f46e5;
    --color-primary-hover: #4338ca;
    --color-primary-light: #ede9fe;
    --radius-small: 4px;
    --radius-medium: 8px;
    --radius-large: 12px;
    --shadow-sm: 0 1px 2px rgba(0,0,0,.06);
    --shadow-md: 0 4px 12px rgba(0,0,0,.08);
    --font-sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
@media (prefers-color-scheme: dark) {
    :root {
        --color-background: #0f1117;
        --color-surface: #1a1d27;
        --color-surface-alt: #22263a;
        --color-border: #2e3347;
        --color-text: #e8eaf0;
        --color-text-muted: #8b93a8;
        --color-primary: #7c6ef0;
        --color-primary-hover: #6c5ce7;
        --color-primary-light: #2a2550;
    }
}
*, *::before, *::after { box-sizing: border-box; }
body { margin: 0; font-family: var(--font-sans); font-size: 14px; line-height: 1.5; color: var(--color-text); background: var(--color-background); }

/* Nav */
.nav { background: var(--color-surface); border-bottom: 1px solid var(--color-border); padding: 0 24px; display: flex; align-items: center; height: 56px; box-shadow: var(--shadow-sm); position: sticky; top: 0; z-index: 100; }
.nav-logo { font-weight: 700; font-size: 16px; color: var(--color-primary); text-decoration: none; display: flex; align-items: center; gap: 8px; }

/* Hero */
.hero { text-align: center; padding: 56px 24px 40px; }
.hero h1 { font-size: 32px; font-weight: 800; margin: 0 0 10px; letter-spacing: -.5px; }
.hero p { color: var(--color-text-muted); font-size: 15px; margin: 0; }
.hero-stats { display: flex; justify-content: center; gap: 32px; margin-top: 28px; }
.hero-stat-val { font-size: 28px; font-weight: 800; color: var(--color-primary); display: block; }
.hero-stat-lbl { font-size: 12px; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: .06em; }

/* Grid */
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px 60px; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }

/* Card */
.plugin-card {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-large);
    padding: 24px;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: box-shadow .2s, border-color .2s, transform .15s;
    box-shadow: var(--shadow-sm);
}
.plugin-card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--color-primary);
    transform: translateY(-2px);
}
.card-top { display: flex; align-items: flex-start; gap: 14px; }
.card-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-medium);
    background: var(--color-primary-light);
    display: flex; align-items: center; justify-content: center;
    color: var(--color-primary);
    flex-shrink: 0;
}
.card-icon svg { width: 24px; height: 24px; }
.card-meta { flex: 1; min-width: 0; }
.card-name { font-size: 15px; font-weight: 700; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.card-version { font-size: 11px; color: var(--color-text-muted); }
.card-desc { font-size: 13px; color: var(--color-text-muted); line-height: 1.55; flex: 1; }
.card-footer { display: flex; align-items: center; justify-content: space-between; }
.card-cats { display: flex; gap: 5px; flex-wrap: wrap; }
.cat-badge {
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    background: var(--color-primary-light);
    color: var(--color-primary);
}
.card-arrow { color: var(--color-text-muted); }

/* Section heading */
.section-heading { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--color-text-muted); margin: 0 0 16px; padding-bottom: 8px; border-bottom: 1px solid var(--color-border); }
</style>
</head>
<body>

<nav class="nav">
    <a href="/" class="nav-logo">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"/><line x1="12" y1="22" x2="12" y2="15.5"/><polyline points="22 8.5 12 15.5 2 8.5"/></svg>
        Awan Tools
    </a>
</nav>

<div class="hero">
    <h1>Plugin Collection</h1>
    <p>A collection of self-contained productivity and developer tools</p>
    <div class="hero-stats">
        <div>
            <span class="hero-stat-val"><?= count($plugins) ?></span>
            <span class="hero-stat-lbl">Plugins</span>
        </div>
        <div>
            <span class="hero-stat-val"><?= $total_tools ?></span>
            <span class="hero-stat-lbl">Total Tools</span>
        </div>
    </div>
</div>

<div class="container">
    <p class="section-heading">Available Plugins</p>
    <div class="grid">
    <?php foreach ($plugins as $plugin):
        $slug  = $plugin['_slug'];
        $icon  = $plugin['icon'] ?? '';
        $cats  = $plugin['categories'] ?? (isset($plugin['category']) ? [$plugin['category']] : []);
        $desc  = $plugin['description'] ?? '';
        if (strlen($desc) > 120) $desc = substr($desc, 0, 117) . '…';
    ?>
    <a href="/plugins/<?= htmlspecialchars($slug) ?>/" class="plugin-card">
        <div class="card-top">
            <div class="card-icon"><?= $icon ?: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>' ?></div>
            <div class="card-meta">
                <div class="card-name"><?= htmlspecialchars($plugin['name'] ?? $slug) ?></div>
                <div class="card-version">v<?= htmlspecialchars($plugin['version'] ?? '1.0.0') ?> &middot; <?= htmlspecialchars($plugin['author'] ?? '') ?></div>
            </div>
        </div>
        <div class="card-desc"><?= htmlspecialchars($desc) ?></div>
        <div class="card-footer">
            <div class="card-cats">
                <?php foreach (array_slice($cats, 0, 2) as $cat): ?>
                <span class="cat-badge"><?= htmlspecialchars($cat) ?></span>
                <?php endforeach; ?>
            </div>
            <span class="card-arrow">→</span>
        </div>
    </a>
    <?php endforeach; ?>
    </div>
</div>

</body>
</html>
