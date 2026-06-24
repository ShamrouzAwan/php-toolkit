# Contributing to Awan Tools Plugins

Thank you for your interest in contributing! This repository is the public plugin directory for [Awan Tools](https://awantools.site) — a self-hosted SaaS/tools platform built in PHP 8.2.

---

## How to Contribute

### 1. Fork and Clone

```bash
git clone https://github.com/your-username/awan-plugins.git
cd awan-plugins
```

### 2. Create Your Plugin

Follow the full plugin structure documented in [README.md](README.md). Every plugin must have at minimum:

```
your-plugin-slug/
├── plugin.json         # Required: manifest
├── index.php           # Required: main page handler
├── on_activate.php     # Required: runs on install
├── on_deactivate.php   # Required: runs on deactivation
└── on_uninstall.php    # Required: runs on deletion
```

See [EXAMPLES.md](EXAMPLES.md) for working code patterns.

### 3. Plugin Requirements Checklist

Before submitting a pull request, verify:

- [ ] `plugin.json` is valid JSON with all required fields (`name`, `slug`, `version`, `description`, `author`)
- [ ] The slug is lowercase, hyphens only, unique (matches the folder name)
- [ ] All PHP files begin with `defined('AWAN') or die();`
- [ ] All user input is sanitized using `Security::sanitize()` or `plugin_input()`
- [ ] All output is HTML-escaped using `e()` before echoing
- [ ] CSRF tokens are verified for any form that writes data (`Security::csrfField()` + `Security::verifyCsrf()`)
- [ ] Plugin tables are prefixed with `plg_<slug>_` (use `plugin_table()`)
- [ ] `on_uninstall.php` drops all tables the plugin created
- [ ] Assets are placed in `assets/` and referenced via `plugin_asset()`
- [ ] The plugin works correctly when activated and deactivated multiple times
- [ ] No hardcoded secrets, credentials, or absolute paths
- [ ] Tested on PHP 8.0+
- [ ] A README.md inside the plugin folder explaining what it does

### 4. Code Style

- **PHP:** Follow PSR-12. Use 4-space indentation. Type-hint function parameters and return types where possible.
- **JavaScript:** Plain ES6+. No build tools or bundlers. Keep it self-contained in `assets/`.
- **CSS:** Use the platform CSS variables (see [UI-PREFERENCES.md](UI-PREFERENCES.md)). Prefix all classes with your plugin's short prefix (e.g., `ft-`, `gh-`, `jt-`).

### 5. Submit a Pull Request

- Branch name: `plugin/<slug>` for new plugins, `fix/<slug>-<issue>` for bug fixes
- PR title: `[plugin] Add <Plugin Name>` or `[fix] <Plugin Name>: brief description`
- PR description: what the plugin does, screenshots if UI-based, any new DB tables it creates

---

## Bug Reports & Feature Requests

Open an issue with:

- **Bug:** Steps to reproduce, expected vs actual behavior, PHP version
- **Feature:** What problem it solves, rough API design, why it belongs as a plugin

---

## Plugin Ideas Welcome

Not sure what to build? Here are categories with demand:

- Productivity tools (timers, calculators, converters)
- Developer utilities (encoders, hashers, regex testers)
- Text tools (word counters, diff, Markdown preview)
- Data tools (CSV parsers, JSON viewers, chart generators)
- Media tools (image compressors, color pickers, SVG editors)
- SEO/marketing tools

---

## Code of Conduct

Be respectful. No spam plugins, no data collection without consent, no malicious code.
Plugins that phone home, track users without opt-in, or embed ads will be rejected.

---

## Questions?

Open a GitHub Discussion or reach out at [shamrouzawan.com](https://shamrouzawan.com).
