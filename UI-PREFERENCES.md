# UI Preferences & Design System

This document defines the exact CSS variables, design patterns, component styles, and visual conventions used across all Awan Tools plugins. **All new plugins must follow these guidelines exactly** so every tool feels native to the platform.

The platform ships `awan.css` globally — your plugin CSS should only layer on top of it, never override base variables.

---

## 1. CSS Custom Properties (Design Tokens)

These variables are defined by the platform and available everywhere. Use them instead of hardcoded colors or sizes.

### Colors — Light Mode (default)

```css
/* Backgrounds */
--color-background:     #f1f5f9;   /* page background */
--color-surface:        #ffffff;   /* card / panel surface */
--color-card:           #ffffff;   /* card background (same as surface) */

/* Text */
--color-text:           #0f172a;   /* primary text */
--color-text-secondary: #64748b;   /* secondary text */
--color-text-muted:     #94a3b8;   /* muted / placeholder text */

/* Brand */
--color-primary:        #6366f1;   /* indigo-500 — buttons, links, highlights */
--color-primary-hover:  #4f46e5;   /* indigo-600 — hover state */
--color-primary-light:  #eef2ff;   /* indigo-50  — subtle tints */

/* Borders */
--color-border:         #e2e8f0;   /* default border */
--color-border-strong:  #cbd5e1;   /* stronger border / divider */

/* Status */
--color-success:        #10b981;   /* green — success states */
--color-danger:         #ef4444;   /* red — error / destructive */
--color-warning:        #f59e0b;   /* amber — warnings */
--color-info:           #3b82f6;   /* blue — informational */

/* Sidebar (admin) */
--color-sidebar:            #0f172a;
--color-sidebar-text:       #94a3b8;
--color-sidebar-text-active:#f8fafc;
--color-sidebar-active-bg:  #334155;
```

### Colors — Dark Mode

When `[data-theme="dark"]` is set on `<html>`, the platform overrides:

```css
[data-theme="dark"] {
    --color-background:     #0f172a;
    --color-surface:        #1e293b;
    --color-card:           #1e293b;
    --color-text:           #f1f5f9;
    --color-text-secondary: #94a3b8;
    --color-text-muted:     #64748b;
    --color-border:         #334155;
    --color-border-strong:  #475569;
    --color-primary-light:  rgba(99,102,241,.15);
}
```

**Always test your plugin in dark mode.** Use `var(--color-*)` everywhere — never hardcode `#fff` or `#000`.

### Typography

```css
--font-family:        -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
--font-size-base:     14px;
--font-family-mono:   'JetBrains Mono', 'Fira Code', Consolas, monospace;  /* not a CSS var, use directly */
```

### Border Radius

```css
--radius-small:   4px;     /* inputs, small buttons, tags */
--radius-medium:  8px;     /* cards, panels, large buttons */
--radius-full:    9999px;  /* pills, avatars */
```

---

## 2. Platform Component Classes

These classes are shipped in `awan.css` and are available to all plugins.

### Cards

```html
<div class="card">
    <div class="card-header">
        <span class="card-title">Title</span>
    </div>
    <div class="card-body">
        Content here
    </div>
    <div class="card-footer">
        Footer actions
    </div>
</div>
```

### Buttons

```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-ghost">Ghost</button>
<button class="btn btn-outline">Outline</button>

<!-- Sizes -->
<button class="btn btn-primary btn-sm">Small</button>
<button class="btn btn-primary btn-lg">Large</button>
```

### Badges

```html
<span class="badge badge-primary">Primary</span>
<span class="badge badge-success">Active</span>
<span class="badge badge-danger">Error</span>
<span class="badge badge-warning">Warning</span>
<span class="badge badge-neutral">Neutral</span>
```

### Alerts

```html
<div class="alert alert-success">Success message</div>
<div class="alert alert-danger">Error message</div>
<div class="alert alert-warning">Warning message</div>
<div class="alert alert-info">Info message</div>
```

### Forms

```html
<div class="form-group">
    <label class="form-label">Label <span class="req">*</span></label>
    <input type="text" class="form-input" placeholder="…">
    <div class="form-hint">Helper text</div>
</div>

<div class="form-group">
    <label class="form-label">Textarea</label>
    <textarea class="form-input" rows="4"></textarea>
</div>

<div class="form-group">
    <label class="form-label">Select</label>
    <select class="form-input">
        <option>Option 1</option>
    </select>
</div>
```

### Tables

```html
<div class="table-wrap">
    <table class="table">
        <thead>
            <tr><th>Name</th><th>Status</th></tr>
        </thead>
        <tbody>
            <tr><td>Row data</td><td><span class="badge badge-success">Active</span></td></tr>
        </tbody>
    </table>
</div>
```

---

## 3. Plugin Layout Conventions

Every plugin page is structured the same way:

```html
<div class="[prefix]-wrap">

    <!-- Hero: title + subtitle + optional stat badges -->
    <div class="[prefix]-hero">
        <div class="[prefix]-hero-title">
            [ICON SVG] Plugin Name
        </div>
        <div class="[prefix]-hero-subtitle">
            One-line description of what this plugin does.
        </div>
        <div class="[prefix]-hero-stats">
            <span class="[prefix]-hstat"><strong>N</strong> tools</span>
            <span class="[prefix]-hstat">Browser-based</span>
        </div>
    </div>

    <!-- Tab Navigation (if plugin has multiple sections) -->
    <div class="[prefix]-tab-nav">
        <button class="[prefix]-tab-btn active" data-tab="tab1" onclick="P.switchTab('tab1')">
            [ICON] Tab One
        </button>
        <button class="[prefix]-tab-btn" data-tab="tab2" onclick="P.switchTab('tab2')">
            [ICON] Tab Two
        </button>
    </div>

    <!-- Tab Panels -->
    <div id="[prefix]-tab-tab1" class="[prefix]-tab-panel active">
        <!-- content -->
    </div>
    <div id="[prefix]-tab-tab2" class="[prefix]-tab-panel">
        <!-- content -->
    </div>

</div>
```

### Hero CSS Pattern

```css
.[prefix]-hero {
    background: linear-gradient(135deg, rgba(79,70,229,.07) 0%, rgba(99,102,241,.03) 100%);
    border: 1px solid rgba(99,102,241,.2);
    border-radius: var(--radius-medium, 10px);
    padding: 22px 26px 18px;
    margin-bottom: 16px;
}
.[prefix]-hero-title {
    font-size: 20px; font-weight: 800;
    color: var(--color-text);
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 6px;
}
.[prefix]-hero-title svg { color: var(--color-primary); }
.[prefix]-hero-subtitle {
    font-size: 13px; color: var(--color-text-muted);
    line-height: 1.5; margin-bottom: 14px;
}
.[prefix]-hero-stats { display: flex; flex-wrap: wrap; gap: 7px; }
.[prefix]-hstat {
    font-size: 11.5px; font-weight: 500;
    color: var(--color-text-muted);
    background: rgba(255,255,255,.75);
    border: 1px solid rgba(99,102,241,.3);
    border-radius: 999px;
    padding: 3px 11px;
}
.[prefix]-hstat strong { color: var(--color-primary); font-weight: 700; }
```

### Tab Navigation CSS Pattern

```css
.[prefix]-tab-nav {
    display: flex;
    gap: 3px;
    background: rgba(99,102,241,.06);
    border: 1.5px solid rgba(99,102,241,.18);
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 18px;
    overflow-x: auto;
    scrollbar-width: none;
}
.[prefix]-tab-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; font-size: 12.5px; font-weight: 600;
    border: none; border-radius: 8px;
    background: transparent; color: var(--color-text-muted);
    cursor: pointer; transition: all .15s;
    white-space: nowrap; flex: 1; justify-content: center;
}
.[prefix]-tab-btn:hover { color: var(--color-text); background: rgba(99,102,241,.1); }
.[prefix]-tab-btn.active {
    color: var(--color-primary);
    background: var(--color-background);
    box-shadow: 0 1px 6px rgba(0,0,0,.13), 0 0 0 1px rgba(99,102,241,.15);
    font-weight: 700;
}
.[prefix]-tab-panel { display: none; }
.[prefix]-tab-panel.active { display: block; }
```

---

## 4. Tool Card Pattern

Used inside tool grid sections:

```html
<div class="[prefix]-tool-grid">
    <div class="[prefix]-tool-card">
        <div class="[prefix]-tool-card-title">
            [ICON SVG] Tool Name
        </div>
        <div class="[prefix]-tool-card-desc">Brief description of what this does.</div>
        <textarea class="[prefix]-textarea" rows="5" placeholder="Input…"></textarea>
        <div class="[prefix]-tool-actions">
            <button class="[prefix]-btn [prefix]-btn-primary">Run</button>
            <button class="[prefix]-btn">Copy</button>
        </div>
        <textarea class="[prefix]-textarea [prefix]-textarea-out" rows="5" readonly placeholder="Output…"></textarea>
    </div>
</div>
```

```css
.[prefix]-tool-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}
.[prefix]-tool-card {
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-medium, 10px);
    padding: 16px;
    display: flex; flex-direction: column; gap: 10px;
    transition: border-color .15s, box-shadow .15s;
}
.[prefix]-tool-card:hover {
    border-color: rgba(99,102,241,.35);
    box-shadow: 0 2px 10px rgba(99,102,241,.07);
}
.[prefix]-tool-card-title {
    font-size: 13.5px; font-weight: 700; color: var(--color-text);
    display: flex; align-items: center; gap: 7px; margin-bottom: 3px;
}
.[prefix]-tool-card-title svg { color: var(--color-primary); }
.[prefix]-tool-card-desc { font-size: 12px; color: var(--color-text-muted); line-height: 1.45; }
```

---

## 5. Button Conventions

```css
.[prefix]-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-small, 5px);
    background: var(--color-surface); color: var(--color-text);
    cursor: pointer; transition: all .12s; white-space: nowrap;
}
.[prefix]-btn svg { width: 12px; height: 12px; flex-shrink: 0; }
.[prefix]-btn:hover {
    border-color: var(--color-primary);
    color: var(--color-primary);
    background: rgba(99,102,241,.07);
}
.[prefix]-btn-primary {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: #fff;
}
.[prefix]-btn-primary:hover { opacity: .88; color: #fff; }
.[prefix]-btn-danger { color: #dc2626; border-color: rgba(220,38,38,.3); background: transparent; }
.[prefix]-btn-danger:hover { background: rgba(220,38,38,.07); border-color: #dc2626; }
.[prefix]-btn-sm { padding: 3px 9px; font-size: 11.5px; }
```

---

## 6. Textarea / Input Conventions

```css
.[prefix]-textarea {
    width: 100%; box-sizing: border-box; resize: vertical;
    font-family: 'JetBrains Mono', 'Fira Code', Consolas, monospace;
    font-size: 12px; line-height: 1.65; padding: 10px 12px;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-small, 6px);
    background: var(--color-background); color: var(--color-text);
    outline: none; transition: border-color .12s;
}
.[prefix]-textarea:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}
.[prefix]-textarea-out {
    background: var(--color-surface);
    cursor: default;
}
.[prefix]-textarea-out:focus { border-color: var(--color-border); box-shadow: none; }
```

---

## 7. Responsive Breakpoints

All plugins must be fully responsive. Use these breakpoints:

```css
/* Tablet: collapse 2-column grids */
@media (max-width: 860px) {
    .[prefix]-tool-grid { grid-template-columns: 1fr; }
}

/* Mobile: stack split layouts */
@media (max-width: 700px) {
    .[prefix]-split { flex-direction: column; height: auto; }
    .[prefix]-tab-btn { padding: 7px 10px; font-size: 12px; flex: none; }
}

/* Small mobile: hide icon labels */
@media (max-width: 480px) {
    .[prefix]-hero { padding: 14px 14px; }
    .[prefix]-tab-btn svg { display: none; }
    .[prefix]-tab-btn { padding: 7px 8px; font-size: 11.5px; }
}
```

---

## 8. SVG Icon Style

All icons across the platform use the same SVG convention:

```html
<!-- Stroke-based icons (most common) -->
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
     stroke-linecap="round" stroke-linejoin="round" width="13" height="13">
    <!-- paths -->
</svg>

<!-- Filled icons (GitHub logo, etc.) -->
<svg viewBox="0 0 24 24" fill="currentColor" width="13" height="13">
    <!-- paths -->
</svg>
```

- Always use `currentColor` so icons inherit the text color of their container
- Standard size for tab icons: `13×13`
- Standard size for card title icons: `14×14`  
- Standard size for hero icons: `20×20`

---

## 9. CSS Naming Convention

Every plugin uses a short 2–3 letter prefix for all its CSS classes to avoid collisions:

| Plugin | Prefix |
|--------|--------|
| github-toolkit | `gh-` |
| frontend-toolkit | `ft-` |
| json-tools | `jt-` |
| xml-tools | `xt-` |
| scannable-codes | `sc-` |
| **your-plugin** | `xx-` (pick unique) |

Never use unprefixed class names. Never override platform classes like `.card`, `.btn`, `.badge`.

---

## 10. Asset Cache-Busting

Always add `filemtime()` to asset links so changes load immediately:

```php
$css = '<link rel="stylesheet" href="/plugins/my-plugin/assets/style.css?v='
     . filemtime(__DIR__ . '/assets/style.css') . '">';
$js  = '<script src="/plugins/my-plugin/assets/script.js?v='
     . filemtime(__DIR__ . '/assets/script.js') . '"></script>';
```

Platform serves assets with `Cache-Control: public, max-age=86400` (24 hours).
Without the version parameter, users will see stale files after updates.

---

## 11. Dark Mode Code Blocks

When rendering diff or code output that has hardcoded colors, always add dark-mode overrides:

```css
/* Example: diff colors */
.[prefix]-dl-add .ft-dt { color: #166534; }        /* light mode: dark green */
.[prefix]-dl-rem .ft-dt { color: #991b1b; }        /* light mode: dark red */

[data-theme="dark"] .[prefix]-dl-add .ft-dt { color: #4ade80; }  /* dark mode: bright green */
[data-theme="dark"] .[prefix]-dl-rem .ft-dt { color: #f87171; }  /* dark mode: bright red */
```

---

## 12. JavaScript Namespace Convention

All plugin JavaScript is wrapped in a module object to avoid global scope pollution:

```js
const MY_PREFIX = (function () {
    // private state
    let state = {};

    function init() {
        // setup on DOMContentLoaded
    }

    function switchTab(id) {
        // tab switching logic
    }

    // expose only what's needed
    return { init, switchTab };
})();

document.addEventListener('DOMContentLoaded', () => MY_PREFIX.init());
```

Use the plugin's uppercase prefix as the global name (e.g., `FT`, `GH`, `JT`, `SC`).
