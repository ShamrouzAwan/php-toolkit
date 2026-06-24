<?php
defined('AWAN') or die();
require_once __DIR__ . '/../../_bootstrap.php';
require_once __DIR__ . '/../../plugins/_sdk.php';
require_once AWAN_ROOT . '/_core/Plugin.php';

$slug      = 'frontend-toolkit';
$_manifest = plugin_manifest($slug);
plugin_track('plugin_view', '/plugins/frontend-toolkit/', ['plugin_slug' => $slug]);
$related_html = plugin_related_html($slug) ?? '';

/* ── Icons ───────────────────────────────────────────────────────────── */
$IC = [
    'code'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
    'play'     => '<svg viewBox="0 0 24 24" fill="currentColor" width="13" height="13"><polygon points="5 3 19 12 5 21 5 3"/></svg>',
    'copy'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>',
    'dl'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>',
    'trash'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>',
    'magic'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M15 4V2"/><path d="M15 16v-2"/><path d="M8 9h2"/><path d="M20 9h2"/><path d="M17.8 11.8 19 13"/><path d="M15 9h.01"/><path d="M17.8 6.2 19 5"/><path d="M3 21l9-9"/><path d="M12.2 6.2 11 5"/></svg>',
    'compress' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/></svg>',
    'refresh'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>',
    'check'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><polyline points="20 6 9 17 4 12"/></svg>',
    'reset'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>',
    'run'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>',
    'diff'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M9 3H5a2 2 0 0 0-2 2v4"/><path d="M9 21H5a2 2 0 0 1-2-2v-4"/><path d="M21 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v4"/><path d="M15 21h4a2 2 0 0 0 2-2v-4"/></svg>',
    'table'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M3 15h18"/><path d="M9 3v18"/></svg>',
    'convert'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M7 16V4m0 0L3 8m4-4 4 4"/><path d="M17 8v12m0 0 4-4m-4 4-4-4"/></svg>',
    'validate' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
    'eye'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>',
];

/* ── Tabs ────────────────────────────────────────────────────────────── */
$tabs = [
    ['id'=>'playground', 'label'=>'Playground',   'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>'],
    ['id'=>'html',       'label'=>'HTML Tools',   'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M4 3l2 16 6 2 6-2 2-16z"/><path d="M8 8h8"/><path d="M8 12h8"/><path d="M9 16h6"/></svg>'],
    ['id'=>'css',        'label'=>'CSS Tools',    'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>'],
    ['id'=>'js',         'label'=>'JS Tools',     'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>'],
    ['id'=>'diff',       'label'=>'Diff Checker', 'icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="13" height="13"><path d="M9 3H5a2 2 0 0 0-2 2v4"/><path d="M9 21H5a2 2 0 0 1-2-2v-4"/><path d="M21 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v4"/><path d="M15 21h4a2 2 0 0 0 2-2v-4"/></svg>'],
];

ob_start();
?>
<div class="ft-wrap">

    <!-- ═══════════════════════════════════════════════════════
         HERO
    ════════════════════════════════════════════════════════ -->
    <div class="ft-hero">
        <div class="ft-hero-title"><?= $IC['code'] ?> Frontend Code Toolkit</div>
        <div class="ft-hero-subtitle">HTML, CSS &amp; JS utilities — all browser-based, no backend needed. Format, minify, encode, live-preview, and compare code instantly.</div>
        <div class="ft-hero-stats">
            <span class="ft-hstat"><strong>20</strong> tools</span>
            <span class="ft-hstat"><strong>5</strong> categories</span>
            <span class="ft-hstat">100% browser-based</span>
            <span class="ft-hstat">No signup needed</span>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         LAYOUT: Main + Sidebar
    ════════════════════════════════════════════════════════ -->
    <div class="ft-layout">

        <!-- ── Main ──────────────────────────────────────── -->
        <div class="ft-main">

            <!-- Tab nav -->
            <div class="ft-tab-nav">
                <?php foreach ($tabs as $i => $t): ?>
                <button class="ft-tab-btn<?= $i === 0 ? ' active' : '' ?>" data-tab="<?= $t['id'] ?>" onclick="FT.switchTab('<?= $t['id'] ?>')">
                    <?= $t['icon'] ?> <?= htmlspecialchars($t['label']) ?>
                </button>
                <?php endforeach; ?>
            </div>

            <!-- ── PLAYGROUND TAB ─────────────────────── -->
            <div id="ft-tab-playground" class="ft-tab-panel active">

                <!-- Toolbar -->
                <div class="ft-pg-toolbar">
                    <button class="ft-btn ft-btn-primary" onclick="FT.runPreview()"><?= $IC['run'] ?> Run</button>
                    <label class="ft-ar-label">
                        <input type="checkbox" id="ft-auto-refresh" checked>
                        <span>Auto-refresh</span>
                    </label>
                    <div class="ft-pg-sep"></div>
                    <button class="ft-btn" onclick="FT.beautifyAll()"><?= $IC['magic'] ?> Beautify All</button>
                    <button class="ft-btn" onclick="FT.minifyAll()"><?= $IC['compress'] ?> Minify All</button>
                    <button class="ft-btn" onclick="FT.resetAll()"><?= $IC['reset'] ?> Reset</button>
                    <div class="ft-pg-sep"></div>
                    <button id="ft-copy-btn" class="ft-btn" onclick="FT.copyOutput()"><?= $IC['copy'] ?> Copy HTML</button>
                    <button class="ft-btn" onclick="FT.downloadAll()"><?= $IC['dl'] ?> Download</button>
                    <button class="ft-btn" onclick="FT.fullscreenPreview()"><?= $IC['eye'] ?> Open Preview</button>
                </div>

                <!-- Split: Editor | Preview -->
                <div class="ft-pg-split">

                    <!-- Editor panel -->
                    <div class="ft-pg-editor">
                        <div class="ft-editor-tabs">
                            <button class="ft-ed-tab active" data-ed="html" onclick="FT.switchEditor('html')">
                                <span class="ft-ed-dot" style="background:#e44d26"></span> HTML
                            </button>
                            <button class="ft-ed-tab" data-ed="css" onclick="FT.switchEditor('css')">
                                <span class="ft-ed-dot" style="background:#264de4"></span> CSS
                            </button>
                            <button class="ft-ed-tab" data-ed="js" onclick="FT.switchEditor('js')">
                                <span class="ft-ed-dot" style="background:#f7df1e"></span> JS
                            </button>
                        </div>
                        <textarea id="ft-html-editor" class="ft-editor-area" spellcheck="false" placeholder="HTML code here..."></textarea>
                        <textarea id="ft-css-editor"  class="ft-editor-area" spellcheck="false" placeholder="CSS styles here..." style="display:none"></textarea>
                        <textarea id="ft-js-editor"   class="ft-editor-area" spellcheck="false" placeholder="JavaScript here..." style="display:none"></textarea>
                    </div>

                    <!-- Preview panel -->
                    <div class="ft-pg-preview">
                        <div class="ft-preview-bar">
                            <span style="font-size:11.5px;font-weight:600;color:var(--color-text-muted)">Preview</span>
                        </div>
                        <iframe id="ft-preview-frame" class="ft-preview-iframe" sandbox="allow-scripts" title="Live Preview"></iframe>
                    </div>
                </div>
            </div><!-- /#ft-tab-playground -->

            <!-- ── HTML TOOLS TAB ─────────────────────── -->
            <div id="ft-tab-html" class="ft-tab-panel">
                <div class="ft-tool-grid">

                    <!-- HTML Formatter -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['magic'] ?> HTML Formatter</div>
                            <div class="ft-tool-card-desc">Beautify and indent HTML markup</div>
                        </div>
                        <textarea id="ht-format-in" class="ft-textarea" rows="6" placeholder="Paste HTML here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runHtml('format')">Format</button>
                            <button class="ft-btn" onclick="FT.copyEl('ht-format-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('ht-format-out','formatted.html')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ht-format-in','ht-format-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ht-format-out" class="ft-textarea ft-textarea-out" rows="6" readonly placeholder="Formatted output..."></textarea>
                    </div>

                    <!-- HTML Minifier -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['compress'] ?> HTML Minifier</div>
                            <div class="ft-tool-card-desc">Compress HTML by removing whitespace and comments</div>
                        </div>
                        <textarea id="ht-minify-in" class="ft-textarea" rows="6" placeholder="Paste HTML here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runHtml('minify')">Minify</button>
                            <button class="ft-btn" onclick="FT.copyEl('ht-minify-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('ht-minify-out','minified.html')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ht-minify-in','ht-minify-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ht-minify-out" class="ft-textarea ft-textarea-out" rows="6" readonly placeholder="Minified output..."></textarea>
                    </div>

                    <!-- HTML Encode -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['convert'] ?> HTML Encoder</div>
                            <div class="ft-tool-card-desc">Encode special characters as HTML entities (&amp;lt;, &amp;amp;…)</div>
                        </div>
                        <textarea id="ht-encode-in" class="ft-textarea" rows="5" placeholder="Text or HTML to encode..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runHtml('encode')">Encode</button>
                            <button class="ft-btn" onclick="FT.copyEl('ht-encode-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ht-encode-in','ht-encode-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ht-encode-out" class="ft-textarea ft-textarea-out" rows="5" readonly placeholder="Encoded output..."></textarea>
                    </div>

                    <!-- HTML Decode -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['convert'] ?> HTML Decoder</div>
                            <div class="ft-tool-card-desc">Decode HTML entities back to readable characters</div>
                        </div>
                        <textarea id="ht-decode-in" class="ft-textarea" rows="5" placeholder="&amp;lt;p&amp;gt;Hello&amp;lt;/p&amp;gt;..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runHtml('decode')">Decode</button>
                            <button class="ft-btn" onclick="FT.copyEl('ht-decode-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ht-decode-in','ht-decode-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ht-decode-out" class="ft-textarea ft-textarea-out" rows="5" readonly placeholder="Decoded output..."></textarea>
                    </div>

                    <!-- Strip Tags -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['trash'] ?> Strip HTML Tags</div>
                            <div class="ft-tool-card-desc">Remove all HTML tags, leaving plain text only</div>
                        </div>
                        <textarea id="ht-strip-in" class="ft-textarea" rows="5" placeholder="Paste HTML here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runHtml('strip')">Strip Tags</button>
                            <button class="ft-btn" onclick="FT.copyEl('ht-strip-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ht-strip-in','ht-strip-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ht-strip-out" class="ft-textarea ft-textarea-out" rows="5" readonly placeholder="Plain text output..."></textarea>
                    </div>

                    <!-- HTML to Markdown -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['convert'] ?> HTML → Markdown</div>
                            <div class="ft-tool-card-desc">Convert HTML markup to Markdown syntax</div>
                        </div>
                        <textarea id="ht-tomd-in" class="ft-textarea" rows="5" placeholder="&lt;h1&gt;Title&lt;/h1&gt;&lt;p&gt;Text&lt;/p&gt;..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runHtml('tomd')">Convert</button>
                            <button class="ft-btn" onclick="FT.copyEl('ht-tomd-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('ht-tomd-out','output.md')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ht-tomd-in','ht-tomd-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ht-tomd-out" class="ft-textarea ft-textarea-out" rows="5" readonly placeholder="Markdown output..."></textarea>
                    </div>

                    <!-- HTML Table Generator (full width) -->
                    <div class="ft-tool-card ft-span-2">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['table'] ?> HTML Table Generator</div>
                            <div class="ft-tool-card-desc">Generate a ready-to-use HTML table with custom rows and columns</div>
                        </div>
                        <div class="ft-gen-row">
                            <label class="ft-gen-label">Rows</label>
                            <input type="number" id="ht-table-rows" class="ft-input-sm" value="3" min="1" max="20" style="width:64px">
                            <label class="ft-gen-label">Cols</label>
                            <input type="number" id="ht-table-cols" class="ft-input-sm" value="4" min="1" max="10" style="width:64px">
                            <label class="ft-gen-label ft-checkbox-label">
                                <input type="checkbox" id="ht-table-head" checked> With &lt;thead&gt;
                            </label>
                            <button class="ft-btn ft-btn-primary" onclick="FT.genTable()"><?= $IC['table'] ?> Generate</button>
                            <button class="ft-btn" onclick="FT.copyEl('ht-table-out',this)"><?= $IC['copy'] ?> Copy</button>
                        </div>
                        <textarea id="ht-table-out" class="ft-textarea ft-textarea-out" rows="8" readonly placeholder="Generated table HTML..."></textarea>
                    </div>

                </div>
            </div><!-- /#ft-tab-html -->

            <!-- ── CSS TOOLS TAB ──────────────────────── -->
            <div id="ft-tab-css" class="ft-tab-panel">
                <div class="ft-tool-grid">

                    <!-- CSS Formatter -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['magic'] ?> CSS Formatter</div>
                            <div class="ft-tool-card-desc">Beautify and indent CSS with consistent spacing</div>
                        </div>
                        <textarea id="ct-format-in" class="ft-textarea" rows="7" placeholder="Paste CSS here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runCss('format')">Format</button>
                            <button class="ft-btn" onclick="FT.copyEl('ct-format-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('ct-format-out','style.css')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ct-format-in','ct-format-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ct-format-out" class="ft-textarea ft-textarea-out" rows="7" readonly placeholder="Formatted CSS..."></textarea>
                    </div>

                    <!-- CSS Minifier -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['compress'] ?> CSS Minifier</div>
                            <div class="ft-tool-card-desc">Remove comments, whitespace and shorten color values</div>
                        </div>
                        <textarea id="ct-minify-in" class="ft-textarea" rows="7" placeholder="Paste CSS here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runCss('minify')">Minify</button>
                            <button class="ft-btn" onclick="FT.copyEl('ct-minify-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('ct-minify-out','style.min.css')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ct-minify-in','ct-minify-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ct-minify-out" class="ft-textarea ft-textarea-out" rows="7" readonly placeholder="Minified CSS..."></textarea>
                    </div>

                    <!-- CSS Validator -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['validate'] ?> CSS Validator</div>
                            <div class="ft-tool-card-desc">Basic structural validation — balanced braces, syntax checks</div>
                        </div>
                        <textarea id="ct-validate-in" class="ft-textarea" rows="7" placeholder="Paste CSS to validate..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runCss('validate')">Validate</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ct-validate-in','ct-validate-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ct-validate-out" class="ft-textarea ft-textarea-out" rows="7" readonly placeholder="Validation results..."></textarea>
                    </div>

                    <!-- CSS → SCSS -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['convert'] ?> CSS → SCSS</div>
                            <div class="ft-tool-card-desc">Convert CSS to SCSS comment style, ready for nesting</div>
                        </div>
                        <textarea id="ct-toscss-in" class="ft-textarea" rows="7" placeholder="Paste CSS here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runCss('toscss')">Convert</button>
                            <button class="ft-btn" onclick="FT.copyEl('ct-toscss-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('ct-toscss-out','style.scss')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ct-toscss-in','ct-toscss-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ct-toscss-out" class="ft-textarea ft-textarea-out" rows="7" readonly placeholder="SCSS output..."></textarea>
                    </div>

                    <!-- CSS Variable Extractor (full width) -->
                    <div class="ft-tool-card ft-span-2">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['magic'] ?> CSS Variable Extractor</div>
                            <div class="ft-tool-card-desc">Find all CSS custom properties (--variables) and generate a :root block</div>
                        </div>
                        <textarea id="ct-vars-in" class="ft-textarea" rows="5" placeholder="Paste CSS with var(--*) usages or declarations..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runCss('vars')">Extract Variables</button>
                            <button class="ft-btn" onclick="FT.copyEl('ct-vars-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('ct-vars-in','ct-vars-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="ct-vars-out" class="ft-textarea ft-textarea-out" rows="5" readonly placeholder=":root { --your-vars: here; }"></textarea>
                    </div>

                </div>
            </div><!-- /#ft-tab-css -->

            <!-- ── JS TOOLS TAB ───────────────────────── -->
            <div id="ft-tab-js" class="ft-tab-panel">
                <div class="ft-tool-grid">

                    <!-- JS Formatter -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['magic'] ?> JS Formatter</div>
                            <div class="ft-tool-card-desc">Re-indent JavaScript code based on brace structure</div>
                        </div>
                        <textarea id="jt-format-in" class="ft-textarea" rows="7" placeholder="Paste JS here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runJs('format')">Format</button>
                            <button class="ft-btn" onclick="FT.copyEl('jt-format-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('jt-format-out','script.js')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('jt-format-in','jt-format-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="jt-format-out" class="ft-textarea ft-textarea-out" rows="7" readonly placeholder="Formatted JS..."></textarea>
                    </div>

                    <!-- JS Minifier -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['compress'] ?> JS Minifier</div>
                            <div class="ft-tool-card-desc">Strip comments and collapse whitespace from JavaScript</div>
                        </div>
                        <textarea id="jt-minify-in" class="ft-textarea" rows="7" placeholder="Paste JS here..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runJs('minify')">Minify</button>
                            <button class="ft-btn" onclick="FT.copyEl('jt-minify-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn" onclick="FT.dlEl('jt-minify-out','script.min.js')"><?= $IC['dl'] ?></button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('jt-minify-in','jt-minify-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="jt-minify-out" class="ft-textarea ft-textarea-out" rows="7" readonly placeholder="Minified JS..."></textarea>
                    </div>

                    <!-- Syntax Checker -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['validate'] ?> Syntax Checker</div>
                            <div class="ft-tool-card-desc">Detect JavaScript syntax errors using browser engine</div>
                        </div>
                        <textarea id="jt-syntax-in" class="ft-textarea" rows="7" placeholder="Paste JS to check..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runJs('syntax')">Check Syntax</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('jt-syntax-in','jt-syntax-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="jt-syntax-out" class="ft-textarea ft-textarea-out" rows="7" readonly placeholder="Syntax check results..."></textarea>
                    </div>

                    <!-- JS Escape / Unescape -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['convert'] ?> JS Escape / Unescape</div>
                            <div class="ft-tool-card-desc">Escape special characters for use in JS strings</div>
                        </div>
                        <textarea id="jt-escape-in" class="ft-textarea" rows="5" placeholder="Text or JS code..."></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runJs('escape')">Escape</button>
                            <button class="ft-btn" onclick="FT.runJs('unescape')">Unescape</button>
                            <button class="ft-btn" onclick="FT.copyEl('jt-escape-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('jt-escape-in','jt-escape-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="jt-escape-out" class="ft-textarea ft-textarea-out" rows="5" readonly placeholder="Output..."></textarea>
                    </div>

                    <!-- Variable Name Converter -->
                    <div class="ft-tool-card">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['convert'] ?> Variable Name Converter</div>
                            <div class="ft-tool-card-desc">Convert between camelCase, snake_case, kebab-case, PascalCase</div>
                        </div>
                        <textarea id="jt-convert-in" class="ft-textarea" rows="4" placeholder="my variable name&#10;myVariableName&#10;my-variable-name"></textarea>
                        <div class="ft-tool-actions">
                            <select id="jt-convert-style" class="ft-select">
                                <option value="camel">camelCase</option>
                                <option value="pascal">PascalCase</option>
                                <option value="snake">snake_case</option>
                                <option value="kebab">kebab-case</option>
                                <option value="screaming">SCREAMING_SNAKE</option>
                            </select>
                            <button class="ft-btn ft-btn-primary" onclick="FT.convertVarNames()">Convert</button>
                            <button class="ft-btn" onclick="FT.copyEl('jt-convert-out',this)"><?= $IC['copy'] ?> Copy</button>
                            <button class="ft-btn ft-btn-danger" onclick="FT.clearTool('jt-convert-in','jt-convert-out')"><?= $IC['trash'] ?></button>
                        </div>
                        <textarea id="jt-convert-out" class="ft-textarea ft-textarea-out" rows="4" readonly placeholder="Converted names..."></textarea>
                    </div>

                    <!-- JS Console Runner (full width) -->
                    <div class="ft-tool-card ft-span-2">
                        <div class="ft-tool-card-head">
                            <div class="ft-tool-card-title"><?= $IC['run'] ?> JS Console Runner</div>
                            <div class="ft-tool-card-desc">Execute JavaScript in a sandboxed iframe and capture console output</div>
                        </div>
                        <textarea id="jt-runner-in" class="ft-textarea" rows="7" placeholder="// JavaScript to run&#10;console.log('Hello!');&#10;console.log(2 + 2);&#10;console.warn('Watch out!');"></textarea>
                        <div class="ft-tool-actions">
                            <button class="ft-btn ft-btn-primary" onclick="FT.runJsConsole()"><?= $IC['run'] ?> Run</button>
                            <button class="ft-btn" onclick="FT.clearConsole()"><?= $IC['trash'] ?> Clear Output</button>
                        </div>
                        <div id="jt-runner-out" class="ft-console-output">
                            <div class="ft-console-empty">Output will appear here after running…</div>
                        </div>
                    </div>

                </div>
            </div><!-- /#ft-tab-js -->

            <!-- ── DIFF TAB ───────────────────────────── -->
            <div id="ft-tab-diff" class="ft-tab-panel">
                <div class="ft-diff-wrap">
                    <div class="ft-diff-editors">
                        <div class="ft-diff-col">
                            <div class="ft-diff-col-label">Original / Left</div>
                            <textarea id="diff-a" class="ft-textarea ft-diff-ta" placeholder="Paste original text or code here..."></textarea>
                        </div>
                        <div class="ft-diff-col">
                            <div class="ft-diff-col-label">Modified / Right</div>
                            <textarea id="diff-b" class="ft-textarea ft-diff-ta" placeholder="Paste modified text or code here..."></textarea>
                        </div>
                    </div>
                    <div class="ft-diff-toolbar">
                        <button class="ft-btn ft-btn-primary" onclick="FT.runDiff()"><?= $IC['diff'] ?> Compare</button>
                        <button class="ft-btn" onclick="FT.clearDiff()"><?= $IC['trash'] ?> Clear</button>
                    </div>
                    <div id="diff-out" class="ft-diff-result">
                        <div class="ft-diff-empty">Paste two pieces of text above and click Compare to see the diff.</div>
                    </div>
                </div>
            </div><!-- /#ft-tab-diff -->

        </div><!-- /.ft-main -->

    </div><!-- /.ft-layout -->

    <?php if ($related_html): ?>
    <?= $related_html ?>
    <?php endif; ?>

</div><!-- /.ft-wrap -->
<?php
$content = ob_get_clean();

$css = '<link rel="stylesheet" href="/plugins/frontend-toolkit/assets/ft-tools.css?v=' . filemtime(__DIR__ . '/assets/ft-tools.css') . '">';
$js  = '<script src="/plugins/frontend-toolkit/assets/ft-tools.js?v=' . filemtime(__DIR__ . '/assets/ft-tools.js') . '"></script>';

plugin_render('Frontend Code Toolkit — HTML, CSS & JS Utilities', $css . $content . $js);
