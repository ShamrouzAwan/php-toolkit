<?php
/**
 * Minimal platform renderer — stubs plugin_render() for standalone use.
 * Outputs a complete HTML page with the platform's CSS variables so plugins
 * look correct without the full Awan Tools platform present.
 */
defined('AWAN') or die('Direct access denied.');

/**
 * Override plugin_render from _sdk.php — renders a full HTML page.
 */
function plugin_render(string $title, string $content, array $opts = []): void {
    $description = htmlspecialchars($opts['description'] ?? '', ENT_QUOTES, 'UTF-8');
    $canonical   = htmlspecialchars($opts['canonical']   ?? '', ENT_QUOTES, 'UTF-8');
    $og_image    = htmlspecialchars($opts['og_image']    ?? '', ENT_QUOTES, 'UTF-8');
    $safe_title  = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $safe_title ?></title>
<?php if ($description): ?><meta name="description" content="<?= $description ?>"><?php endif; ?>
<?php if ($canonical): ?><link rel="canonical" href="<?= $canonical ?>"><?php endif; ?>
<?php if ($og_image): ?><meta property="og:image" content="<?= $og_image ?>"><?php endif; ?>
<style>
/* ── Platform CSS Variables (light mode default) ── */
:root {
    --color-background:       #f5f7fa;
    --color-surface:          #ffffff;
    --color-surface-alt:      #f0f2f5;
    --color-border:           #e2e6ea;
    --color-text:             #1a202c;
    --color-text-muted:       #6b7280;
    --color-text-subtle:      #9ca3af;
    --color-primary:          #4f46e5;
    --color-primary-hover:    #4338ca;
    --color-primary-light:    #ede9fe;
    --color-primary-text:     #ffffff;
    --color-success:          #10b981;
    --color-success-light:    #d1fae5;
    --color-danger:           #ef4444;
    --color-danger-light:     #fee2e2;
    --color-warning:          #f59e0b;
    --color-warning-light:    #fef3c7;
    --color-info:             #3b82f6;
    --color-info-light:       #dbeafe;
    --radius-small:           4px;
    --radius-medium:          8px;
    --radius-large:           12px;
    --shadow-sm:              0 1px 2px rgba(0,0,0,.06);
    --shadow-md:              0 4px 6px rgba(0,0,0,.07);
    --font-sans:              -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    --font-mono:              'Fira Code', 'JetBrains Mono', Consolas, monospace;
}

/* ── Dark mode ── */
@media (prefers-color-scheme: dark) {
    :root {
        --color-background:       #0f1117;
        --color-surface:          #1a1d27;
        --color-surface-alt:      #22263a;
        --color-border:           #2e3347;
        --color-text:             #e8eaf0;
        --color-text-muted:       #8b93a8;
        --color-text-subtle:      #5a6278;
        --color-primary:          #7c6ef0;
        --color-primary-hover:    #6c5ce7;
        --color-primary-light:    #2a2550;
        --color-primary-text:     #ffffff;
        --color-success:          #34d399;
        --color-success-light:    #0d3327;
        --color-danger:           #f87171;
        --color-danger-light:     #3b1515;
        --color-warning:          #fbbf24;
        --color-warning-light:    #3b2a0a;
        --color-info:             #60a5fa;
        --color-info-light:       #1a2e4a;
    }
}

/* ── Reset & Base ── */
*, *::before, *::after { box-sizing: border-box; }
html { height: 100%; }
body {
    margin: 0;
    font-family: var(--font-sans);
    font-size: 14px;
    line-height: 1.5;
    color: var(--color-text);
    background: var(--color-background);
    min-height: 100%;
}

/* ── Platform Nav ── */
.plat-nav {
    background: var(--color-surface);
    border-bottom: 1px solid var(--color-border);
    padding: 0 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    height: 56px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow-sm);
}
.plat-nav-logo {
    font-weight: 700;
    font-size: 16px;
    color: var(--color-primary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}
.plat-nav-sep { flex: 1; }
.plat-nav-link {
    color: var(--color-text-muted);
    text-decoration: none;
    font-size: 13px;
    padding: 4px 10px;
    border-radius: var(--radius-small);
    transition: color .15s, background .15s;
}
.plat-nav-link:hover {
    color: var(--color-text);
    background: var(--color-surface-alt);
}
.plat-nav-back {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--color-text-muted);
    text-decoration: none;
    font-size: 13px;
    padding: 4px 10px;
    border-radius: var(--radius-small);
    border: 1px solid var(--color-border);
    transition: color .15s, background .15s;
}
.plat-nav-back:hover { background: var(--color-surface-alt); color: var(--color-text); }

/* ── Main layout ── */
.plat-main {
    max-width: 1280px;
    margin: 0 auto;
    padding: 24px;
}

/* ── Platform components ── */
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
}
.page-header-left {}
.page-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--color-text);
    margin: 0 0 2px;
}
.page-subtitle {
    font-size: 13px;
    color: var(--color-text-muted);
}
.page-header-actions { display: flex; gap: 8px; align-items: center; }

/* ── Buttons ── */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: var(--radius-small);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid transparent;
    transition: background .15s, color .15s, border-color .15s;
    text-decoration: none;
    line-height: 1;
}
.btn-primary {
    background: var(--color-primary);
    color: var(--color-primary-text);
}
.btn-primary:hover { background: var(--color-primary-hover); }
.btn-ghost {
    background: transparent;
    color: var(--color-text-muted);
    border-color: var(--color-border);
}
.btn-ghost:hover { background: var(--color-surface-alt); color: var(--color-text); }
.btn-sm { padding: 5px 10px; font-size: 12px; }
.btn-danger { background: var(--color-danger); color: #fff; }
.btn-danger:hover { opacity: .9; }

/* ── Cards ── */
.card {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-medium);
    overflow: hidden;
}
.card-header {
    padding: 14px 16px;
    border-bottom: 1px solid var(--color-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.card-title { font-size: 15px; font-weight: 600; color: var(--color-text); }
.card-body { padding: 16px; }
.card-footer {
    padding: 12px 16px;
    border-top: 1px solid var(--color-border);
    background: var(--color-surface-alt);
}

/* ── Alerts ── */
.alert {
    padding: 12px 16px;
    border-radius: var(--radius-small);
    font-size: 13px;
    margin-bottom: 16px;
}
.alert-danger { background: var(--color-danger-light); color: var(--color-danger); }
.alert-success { background: var(--color-success-light); color: var(--color-success); }
.alert-info { background: var(--color-info-light); color: var(--color-info); }
.alert-warning { background: var(--color-warning-light); color: var(--color-warning); }

/* ── Form elements ── */
input, select, textarea {
    font-family: var(--font-sans);
    font-size: 13px;
    color: var(--color-text);
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-small);
    padding: 7px 10px;
    outline: none;
    transition: border-color .15s;
    width: 100%;
}
input:focus, select:focus, textarea:focus { border-color: var(--color-primary); }
label { font-size: 13px; font-weight: 500; color: var(--color-text); display: block; margin-bottom: 4px; }
select { appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 8px center; background-size: 16px; padding-right: 32px; }

/* ── Tables ── */
table { width: 100%; border-collapse: collapse; font-size: 13px; }
th { text-align: left; font-weight: 600; padding: 10px 12px; background: var(--color-surface-alt); border-bottom: 1px solid var(--color-border); color: var(--color-text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
td { padding: 10px 12px; border-bottom: 1px solid var(--color-border); }
tr:last-child td { border-bottom: none; }

/* ── Badges ── */
.badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 600; }
.badge-primary { background: var(--color-primary-light); color: var(--color-primary); }
.badge-success { background: var(--color-success-light); color: var(--color-success); }
.badge-danger  { background: var(--color-danger-light);  color: var(--color-danger);  }

/* ── Utility ── */
.text-muted { color: var(--color-text-muted); }
.mt-0 { margin-top: 0; } .mt-1 { margin-top: 8px; } .mt-2 { margin-top: 16px; } .mt-3 { margin-top: 24px; }
.mb-0 { margin-bottom: 0; } .mb-1 { margin-bottom: 8px; } .mb-2 { margin-bottom: 16px; }
code, pre { font-family: var(--font-mono); font-size: 12px; }
pre { background: var(--color-surface-alt); border: 1px solid var(--color-border); border-radius: var(--radius-small); padding: 12px; overflow: auto; }
</style>
</head>
<body>
<nav class="plat-nav">
    <a href="/" class="plat-nav-logo">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        Awan Tools
    </a>
    <span class="plat-nav-sep"></span>
    <a href="/" class="plat-nav-back">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        All Plugins
    </a>
</nav>
<main class="plat-main">
<?= $content ?>
</main>
</body>
</html>
<?php
    exit;
}
