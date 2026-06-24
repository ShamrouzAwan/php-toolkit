/* ============================================================
   Frontend Code Toolkit — ft-tools.js
   All tools run entirely in the browser. No external APIs.
   ============================================================ */
var FT = (function () {
    'use strict';

    /* ──────────────────────────────────────────────────────────
       STATE
    ────────────────────────────────────────────────────────── */
    var _autoRefresh    = true;
    var _debounceTimer  = null;
    var _activeTab      = 'playground';
    var _activeEditor   = 'html';

    /* ──────────────────────────────────────────────────────────
       DOM HELPERS
    ────────────────────────────────────────────────────────── */
    function el(id) { return document.getElementById(id); }
    function gv(id) { var e = el(id); return e ? e.value : ''; }
    function sv(id, v) { var e = el(id); if (e) e.value = v; }
    function setHTML(id, v) { var e = el(id); if (e) e.innerHTML = v; }

    function htmlEsc(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ──────────────────────────────────────────────────────────
       TAB SWITCHING
    ────────────────────────────────────────────────────────── */
    function switchTab(id) {
        _activeTab = id;
        document.querySelectorAll('.ft-tab-btn').forEach(function (b) {
            b.classList.toggle('active', b.dataset.tab === id);
        });
        document.querySelectorAll('.ft-tab-panel').forEach(function (p) {
            p.classList.toggle('active', p.id === 'ft-tab-' + id);
        });
    }

    /* ──────────────────────────────────────────────────────────
       PLAYGROUND
    ────────────────────────────────────────────────────────── */
    var DEFAULT_HTML = '<h1>Hello, Playground!</h1>\n<p>Edit the HTML, CSS and JS tabs, then watch the preview update live.</p>\n<button id="btn">Click me</button>';
    var DEFAULT_CSS  = 'body {\n    font-family: system-ui, sans-serif;\n    padding: 28px;\n    background: #f9fafb;\n    color: #111827;\n}\nh1 {\n    color: #4f46e5;\n    margin: 0 0 10px;\n}\np {\n    color: #6b7280;\n    margin: 0 0 16px;\n}\nbutton {\n    padding: 9px 18px;\n    background: #4f46e5;\n    color: #fff;\n    border: none;\n    border-radius: 8px;\n    cursor: pointer;\n    font-size: 14px;\n}\nbutton:hover { background: #4338ca; }';
    var DEFAULT_JS   = '// JavaScript runs here\ndocument.getElementById(\'btn\')\n  .addEventListener(\'click\', function () {\n    alert(\'Hello from the playground!\');\n  });';

    function switchEditor(type) {
        _activeEditor = type;
        ['html', 'css', 'js'].forEach(function (t) {
            var ta  = el('ft-' + t + '-editor');
            var btn = document.querySelector('[data-ed="' + t + '"]');
            if (ta)  ta.style.display  = t === type ? 'block' : 'none';
            if (btn) btn.classList.toggle('active', t === type);
        });
    }

    function debouncePreview() {
        if (!_autoRefresh) return;
        clearTimeout(_debounceTimer);
        _debounceTimer = setTimeout(runPreview, 350);
    }

    function runPreview() {
        var html  = gv('ft-html-editor');
        var css   = gv('ft-css-editor');
        var js    = gv('ft-js-editor');
        var doc   = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>' + css + '<\/style><\/head><body>' + html + '<scr' + 'ipt>' + js + '<\/scr' + 'ipt><\/body><\/html>';
        var frame = el('ft-preview-frame');
        if (frame) frame.srcdoc = doc;
    }

    function beautifyAll() {
        sv('ft-html-editor', _formatHTML(gv('ft-html-editor')));
        sv('ft-css-editor',  _formatCSS(gv('ft-css-editor')));
        sv('ft-js-editor',   _formatJS(gv('ft-js-editor')));
        runPreview();
    }

    function minifyAll() {
        sv('ft-html-editor', _minifyHTML(gv('ft-html-editor')));
        sv('ft-css-editor',  _minifyCSS(gv('ft-css-editor')));
        sv('ft-js-editor',   _minifyJS(gv('ft-js-editor')));
        runPreview();
    }

    function resetAll() {
        sv('ft-html-editor', DEFAULT_HTML);
        sv('ft-css-editor',  DEFAULT_CSS);
        sv('ft-js-editor',   DEFAULT_JS);
        runPreview();
    }

    function downloadAll() {
        var html = gv('ft-html-editor');
        var css  = gv('ft-css-editor');
        var js   = gv('ft-js-editor');
        var full = '<!DOCTYPE html>\n<html>\n<head>\n<meta charset="UTF-8">\n<style>\n' + css + '\n</style>\n</head>\n<body>\n' + html + '\n<script>\n' + js + '\n<\/script>\n</body>\n</html>';
        dlText(full, 'index.html', 'text/html');
    }

    function copyOutput() {
        var html = gv('ft-html-editor');
        var css  = gv('ft-css-editor');
        var js   = gv('ft-js-editor');
        var full = '<!DOCTYPE html>\n<html>\n<head>\n<meta charset="UTF-8">\n<style>\n' + css + '\n</style>\n</head>\n<body>\n' + html + '\n<script>\n' + js + '\n<\/script>\n</body>\n</html>';
        copyText(full, el('ft-copy-btn'));
    }

    function fullscreenPreview() {
        var html = gv('ft-html-editor');
        var css  = gv('ft-css-editor');
        var js   = gv('ft-js-editor');
        var doc  = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>' + css + '<\/style><\/head><body>' + html + '<scr' + 'ipt>' + js + '<\/scr' + 'ipt><\/body><\/html>';
        var blob = new Blob([doc], { type: 'text/html' });
        var url  = URL.createObjectURL(blob);
        window.open(url, '_blank');
        setTimeout(function () { URL.revokeObjectURL(url); }, 15000);
    }

    /* ──────────────────────────────────────────────────────────
       HTML TOOLS
    ────────────────────────────────────────────────────────── */
    function runHtml(tool) {
        var input  = gv('ht-' + tool + '-in');
        var result = '';
        try {
            switch (tool) {
                case 'format': result = _formatHTML(input); break;
                case 'minify': result = _minifyHTML(input); break;
                case 'encode': result = _encodeEntities(input); break;
                case 'decode': result = _decodeEntities(input); break;
                case 'strip':  result = _stripTags(input); break;
                case 'tomd':   result = _htmlToMd(input); break;
            }
        } catch (e) { result = '// Error: ' + e.message; }
        sv('ht-' + tool + '-out', result);
    }

    function genTable() {
        var rows     = Math.max(1, Math.min(20, parseInt(gv('ht-table-rows') || '3', 10)));
        var cols     = Math.max(1, Math.min(10, parseInt(gv('ht-table-cols') || '4', 10)));
        var withHead = el('ht-table-head') && el('ht-table-head').checked;
        var c, r, out = '<table border="1" cellpadding="8" cellspacing="0">\n';
        if (withHead) {
            out += '  <thead>\n    <tr>\n';
            for (c = 1; c <= cols; c++) out += '      <th>Header ' + c + '</th>\n';
            out += '    </tr>\n  </thead>\n';
        }
        out += '  <tbody>\n';
        for (r = 1; r <= rows; r++) {
            out += '    <tr>\n';
            for (c = 1; c <= cols; c++) out += '      <td>Row ' + r + ', Col ' + c + '</td>\n';
            out += '    </tr>\n';
        }
        out += '  </tbody>\n</table>';
        sv('ht-table-out', out);
    }

    function _formatHTML(html) {
        if (!html.trim()) return '';
        var INDENT   = '  ';
        var VOID     = /^(area|base|br|col|embed|hr|img|input|link|meta|param|source|track|wbr)$/i;
        html = html.replace(/>\s*</g, '>\n<').trim();
        var lines = html.split('\n').map(function (l) { return l.trim(); }).filter(Boolean);
        var out = [], depth = 0;
        lines.forEach(function (line) {
            var tag      = (line.match(/^<\/?([a-z][a-z0-9-]*)/i) || [])[1] || '';
            var isClose  = /^<\//.test(line);
            var isSelf   = /\/\s*>$/.test(line);
            var isVoid   = VOID.test(tag);
            var hasClose = !isClose && tag && new RegExp('<\\/' + tag + '\\s*>', 'i').test(line);
            if (isClose) depth = Math.max(0, depth - 1);
            out.push(INDENT.repeat(depth) + line);
            if (!isClose && !isSelf && !isVoid && tag && !hasClose) depth++;
        });
        return out.join('\n');
    }

    function _minifyHTML(html) {
        if (!html.trim()) return '';
        return html
            .replace(/<!--(?!\[if)[\s\S]*?-->/g, '')
            .replace(/\s{2,}/g, ' ')
            .replace(/>\s+</g, '><')
            .trim();
    }

    function _encodeEntities(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function _decodeEntities(text) {
        var ta = document.createElement('textarea');
        ta.innerHTML = text;
        return ta.value;
    }

    function _stripTags(html) {
        return html.replace(/<[^>]*>/g, '').replace(/\s{2,}/g, ' ').trim();
    }

    function _htmlToMd(html) {
        if (!html.trim()) return '';
        return html
            .replace(/<h([1-6])[^>]*>([\s\S]*?)<\/h\1>/gi, function (_, l, c) {
                return '#'.repeat(+l) + ' ' + _stripTags(c).trim() + '\n\n';
            })
            .replace(/<strong[^>]*>([\s\S]*?)<\/strong>/gi, '**$1**')
            .replace(/<b[^>]*>([\s\S]*?)<\/b>/gi, '**$1**')
            .replace(/<em[^>]*>([\s\S]*?)<\/em>/gi, '*$1*')
            .replace(/<i[^>]*>([\s\S]*?)<\/i>/gi, '*$1*')
            .replace(/<s[^>]*>([\s\S]*?)<\/s>/gi, '~~$1~~')
            .replace(/<del[^>]*>([\s\S]*?)<\/del>/gi, '~~$1~~')
            .replace(/<code[^>]*>([\s\S]*?)<\/code>/gi, '`$1`')
            .replace(/<pre[^>]*>([\s\S]*?)<\/pre>/gi, '```\n$1\n```\n')
            .replace(/<a[^>]+href="([^"]*)"[^>]*>([\s\S]*?)<\/a>/gi, '[$2]($1)')
            .replace(/<img[^>]+src="([^"]*)"[^>]*alt="([^"]*)"[^>]*\/?>/gi, '![$2]($1)')
            .replace(/<img[^>]+src="([^"]*)"[^>]*\/?>/gi, '![]($1)')
            .replace(/<hr[^>]*\/?>/gi, '\n---\n')
            .replace(/<br[^>]*\/?>/gi, '  \n')
            .replace(/<li[^>]*>([\s\S]*?)<\/li>/gi, '- $1\n')
            .replace(/<\/?(ul|ol|div|section|article|header|footer|main|nav)[^>]*>/gi, '\n')
            .replace(/<blockquote[^>]*>([\s\S]*?)<\/blockquote>/gi, function (_, c) {
                return _stripTags(c).split('\n').map(function (l) { return '> ' + l; }).join('\n') + '\n\n';
            })
            .replace(/<p[^>]*>([\s\S]*?)<\/p>/gi, '$1\n\n')
            .replace(/<[^>]*>/g, '')
            .replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&#39;/g, "'")
            .replace(/\n{3,}/g, '\n\n')
            .trim();
    }

    /* ──────────────────────────────────────────────────────────
       CSS TOOLS
    ────────────────────────────────────────────────────────── */
    function runCss(tool) {
        var input  = gv('ct-' + tool + '-in');
        var result = '';
        try {
            switch (tool) {
                case 'format':   result = _formatCSS(input); break;
                case 'minify':   result = _minifyCSS(input); break;
                case 'validate': result = _validateCSS(input); break;
                case 'toscss':   result = _cssToScss(input); break;
                case 'vars':     result = _extractVars(input); break;
            }
        } catch (e) { result = '/* Error: ' + e.message + ' */'; }
        sv('ct-' + tool + '-out', result);
    }

    function _formatCSS(css) {
        if (!css.trim()) return '';
        var depth = 0, out = '', i = 0;
        css = css.trim();
        while (i < css.length) {
            var c = css[i];
            if (c === '/' && css[i + 1] === '*') {
                var end = css.indexOf('*/', i + 2);
                if (end === -1) { out += css.slice(i); break; }
                out += css.slice(i, end + 2) + '\n' + '    '.repeat(depth);
                i = end + 2; continue;
            }
            if (c === '{') {
                out = out.trimEnd() + ' {\n' + '    '.repeat(depth + 1);
                depth++; i++; continue;
            }
            if (c === '}') {
                out = out.trimEnd() + '\n' + '    '.repeat(Math.max(0, depth - 1)) + '}\n';
                depth = Math.max(0, depth - 1);
                if (depth === 0) out += '\n'; else out += '    '.repeat(depth);
                i++; continue;
            }
            if (c === ';') { out += ';\n' + '    '.repeat(depth); i++; continue; }
            if (c === '\n' || c === '\r') {
                // skip blank lines inside blocks, allow them between rules
                var j = i + 1;
                while (j < css.length && (css[j] === '\n' || css[j] === '\r' || css[j] === ' ' || css[j] === '\t')) j++;
                i = j; continue;
            }
            if ((c === ' ' || c === '\t') && out.length && (out[out.length - 1] === '\n' || out.endsWith('    '.repeat(depth)))) {
                i++; continue;
            }
            out += c; i++;
        }
        return out.replace(/\n{3,}/g, '\n\n').trim();
    }

    function _minifyCSS(css) {
        if (!css.trim()) return '';
        return css
            .replace(/\/\*[\s\S]*?\*\//g, '')
            .replace(/\s+/g, ' ')
            .replace(/\s*([{};:,>+~])\s*/g, '$1')
            .replace(/;}/g, '}')
            .replace(/#([0-9a-f])\1([0-9a-f])\2([0-9a-f])\3\b/gi, '#$1$2$3')
            .trim();
    }

    function _validateCSS(css) {
        if (!css.trim()) return 'Error: Empty input.';
        var errors = [];
        var opens  = (css.match(/\{/g) || []).length;
        var closes = (css.match(/\}/g) || []).length;
        if (opens !== closes) errors.push('Unbalanced braces: ' + opens + ' opening, ' + closes + ' closing.');
        var quotes = (css.match(/"/g) || []).length;
        if (quotes % 2 !== 0) errors.push('Unclosed double-quote string.');
        var squotes = (css.match(/'/g) || []).length;
        if (squotes % 2 !== 0) errors.push('Unclosed single-quote string.');
        if (css.includes('!importantt')) errors.push('Typo: "!importantt" (double-t).');
        if (!errors.length) {
            return '✓ No structural errors found.\n\nNote: This is a basic structural check. For full validation, use the W3C CSS Validator at jigsaw.w3.org/css-validator/.';
        }
        return '✗ Issues found:\n' + errors.map(function (e) { return '  • ' + e; }).join('\n');
    }

    function _cssToScss(css) {
        if (!css.trim()) return '';
        var out = '// Converted from CSS\n// Add nesting manually where desired\n\n';
        out += css.replace(/\/\*([\s\S]*?)\*\//g, function (_, c) {
            return '//' + c.replace(/\n/g, '\n//');
        });
        return out.trim();
    }

    function _extractVars(css) {
        if (!css.trim()) return '';
        var vars = {};
        css.replace(/(--[a-z][a-z0-9-]*)\s*:\s*([^;}{]+)/gi, function (_, name, val) {
            vars[name.trim()] = val.trim();
        });
        css.replace(/var\(\s*(--[^,)\s]+)/g, function (_, name) {
            if (!vars[name.trim()]) vars[name.trim()] = '/* not declared */';
        });
        if (!Object.keys(vars).length) return '/* No CSS custom properties found */';
        var out = ':root {\n';
        Object.keys(vars).sort().forEach(function (v) {
            out += '    ' + v + ': ' + vars[v] + ';\n';
        });
        out += '}';
        return out;
    }

    /* ──────────────────────────────────────────────────────────
       JS TOOLS
    ────────────────────────────────────────────────────────── */
    function runJs(tool) {
        var inId   = tool === 'escape' || tool === 'unescape' ? 'jt-escape-in' : 'jt-' + tool + '-in';
        var outId  = tool === 'escape' || tool === 'unescape' ? 'jt-escape-out' : 'jt-' + tool + '-out';
        var input  = gv(inId);
        var result = '';
        try {
            switch (tool) {
                case 'format':   result = _formatJS(input); break;
                case 'minify':   result = _minifyJS(input); break;
                case 'syntax':   result = _checkSyntax(input); break;
                case 'escape':   result = _escapeJS(input); break;
                case 'unescape': result = _unescapeJS(input); break;
            }
        } catch (e) { result = '// Error: ' + e.message; }
        sv(outId, result);
    }

    function convertVarNames() {
        var input = gv('jt-convert-in');
        var style = gv('jt-convert-style') || 'camel';
        var lines = input.split('\n');
        var out   = lines.map(function (line) {
            return line.trim() ? _convertName(line.trim(), style) : '';
        }).join('\n');
        sv('jt-convert-out', out);
    }

    function clearConsole() {
        setHTML('jt-runner-out', '<div class="ft-console-empty">Output will appear here after running…</div>');
    }

    function runJsConsole() {
        var code = gv('jt-runner-in');
        var out  = el('jt-runner-out');
        if (!out) return;
        out.innerHTML = '<div style="font-size:12px;opacity:.6;padding:6px">Running…</div>';

        var captureScript = [
            'var _logs=[];',
            'var console={',
            '  log:function(){_logs.push({t:"log",v:Array.from(arguments).map(function(a){try{return typeof a==="object"?JSON.stringify(a,null,2):String(a);}catch(e){return String(a);}}).join(" ")});},',
            '  error:function(){_logs.push({t:"err",v:Array.from(arguments).map(String).join(" ")});},',
            '  warn:function(){_logs.push({t:"warn",v:Array.from(arguments).map(String).join(" ")});},',
            '  info:function(){_logs.push({t:"info",v:Array.from(arguments).map(String).join(" ")});}',
            '};',
            'try{(function(){',
            code,
            '})();}catch(e){_logs.push({t:"err",v:"Uncaught "+e.constructor.name+": "+e.message});}',
            'window.parent.postMessage({type:"ft-console",logs:_logs},"*");'
        ].join('\n');

        var iframe = document.createElement('iframe');
        iframe.style.cssText = 'position:absolute;width:1px;height:1px;opacity:0;pointer-events:none;left:-9999px;';
        iframe.setAttribute('sandbox', 'allow-scripts');
        document.body.appendChild(iframe);

        var timeout = setTimeout(function () {
            iframe.remove();
            out.innerHTML = '<div class="ft-console-err">Timeout: script took too long (&gt;5s).</div>';
        }, 5000);

        function msgHandler(e) {
            if (!e.data || e.data.type !== 'ft-console') return;
            clearTimeout(timeout);
            window.removeEventListener('message', msgHandler);
            setTimeout(function () { iframe.remove(); }, 100);
            var logs = e.data.logs || [];
            if (!logs.length) {
                out.innerHTML = '<div class="ft-console-empty">Script ran with no output.</div>';
                return;
            }
            out.innerHTML = logs.map(function (l) {
                var cls = l.t === 'err' ? 'ft-console-err' : l.t === 'warn' ? 'ft-console-warn' : 'ft-console-log';
                return '<div class="' + cls + '">' + htmlEsc(String(l.v)) + '</div>';
            }).join('');
        }
        window.addEventListener('message', msgHandler);
        iframe.srcdoc = '<!DOCTYPE html><html><head></head><body><scr' + 'ipt>' + captureScript + '<\/scr' + 'ipt></body></html>';
    }

    function _countBraces(str, ch) {
        var count = 0, inStr = false, sc = '';
        for (var i = 0; i < str.length; i++) {
            var c = str[i];
            if (inStr) { if (c === '\\') { i++; continue; } if (c === sc) inStr = false; continue; }
            if (c === '"' || c === "'" || c === '`') { inStr = true; sc = c; continue; }
            if (c === ch) count++;
        }
        return count;
    }

    function _formatJS(code) {
        if (!code.trim()) return '';
        var INDENT = '    ', depth = 0, result = [];
        var lines = code.replace(/\r\n/g, '\n').split('\n').map(function (l) { return l.trim(); });
        lines.forEach(function (line) {
            if (!line) { result.push(''); return; }
            var opens  = _countBraces(line, '{') + _countBraces(line, '(') + _countBraces(line, '[');
            var closes = _countBraces(line, '}') + _countBraces(line, ')') + _countBraces(line, ']');
            var startClose = /^[})\]]/.test(line);
            if (startClose) depth = Math.max(0, depth - 1);
            result.push(INDENT.repeat(depth) + line);
            var net = opens - closes;
            if (net > 0) depth += net;
            else if (net < 0 && !startClose) depth = Math.max(0, depth + net);
        });
        return result.join('\n').replace(/\n{3,}/g, '\n\n');
    }

    function _minifyJS(code) {
        if (!code.trim()) return '';
        var inStr = false, sc = '', clean = '', i = 0;
        while (i < code.length) {
            var c = code[i];
            if (inStr) {
                clean += c;
                if (c === '\\') { clean += code[++i] || ''; i++; continue; }
                if (c === sc) inStr = false;
                i++; continue;
            }
            if (c === '"' || c === "'" || c === '`') { inStr = true; sc = c; clean += c; i++; continue; }
            if (c === '/' && code[i + 1] === '*') {
                var end = code.indexOf('*/', i + 2);
                i = end === -1 ? code.length : end + 2; clean += ' '; continue;
            }
            if (c === '/' && code[i + 1] === '/') {
                var nl = code.indexOf('\n', i);
                i = nl === -1 ? code.length : nl; continue;
            }
            clean += c; i++;
        }
        return clean.replace(/\s+/g, ' ').trim();
    }

    function _checkSyntax(code) {
        if (!code.trim()) return '// Empty input — nothing to check.';
        try {
            new Function(code);
            return '✓ No syntax errors detected.\n\nNote: This checks parse-time syntax only (using the browser engine). Runtime errors will only appear when the code executes.';
        } catch (e) {
            return '✗ Syntax Error:\n\n  ' + e.message + '\n\nHint: Check for missing brackets, quotes, or semicolons near the reported location.';
        }
    }

    function _escapeJS(str) {
        return str
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"')
            .replace(/\n/g, '\\n')
            .replace(/\r/g, '\\r')
            .replace(/\t/g, '\\t')
            .replace(/\u2028/g, '\\u2028')
            .replace(/\u2029/g, '\\u2029');
    }

    function _unescapeJS(str) {
        return str
            .replace(/\\n/g, '\n')
            .replace(/\\r/g, '\r')
            .replace(/\\t/g, '\t')
            .replace(/\\'/g, "'")
            .replace(/\\"/g, '"')
            .replace(/\\\\/g, '\\')
            .replace(/\\u([0-9a-f]{4})/gi, function (_, h) { return String.fromCharCode(parseInt(h, 16)); });
    }

    function _convertName(text, style) {
        if (!text.trim()) return text;
        var words = text.trim()
            .replace(/([A-Z])/g, ' $1')
            .replace(/[_\-\s]+/g, ' ')
            .trim().toLowerCase().split(/\s+/).filter(Boolean);
        if (!words.length) return text;
        switch (style) {
            case 'camel':     return words[0] + words.slice(1).map(function (w) { return w[0].toUpperCase() + w.slice(1); }).join('');
            case 'pascal':    return words.map(function (w) { return w[0].toUpperCase() + w.slice(1); }).join('');
            case 'snake':     return words.join('_');
            case 'kebab':     return words.join('-');
            case 'screaming': return words.join('_').toUpperCase();
            default:          return words.join(' ');
        }
    }

    /* ──────────────────────────────────────────────────────────
       DIFF CHECKER
    ────────────────────────────────────────────────────────── */
    function runDiff() {
        var a   = gv('diff-a').split('\n');
        var b   = gv('diff-b').split('\n');
        var out = el('diff-out');
        if (!out) return;

        var max = Math.max(a.length, b.length);
        var same = 0, added = 0, removed = 0, changed = 0;
        var lines = [];

        for (var i = 0; i < max; i++) {
            var aLine = i < a.length ? a[i] : null;
            var bLine = i < b.length ? b[i] : null;
            var num   = i + 1;
            if (aLine === bLine) {
                same++;
                lines.push('<div class="ft-dl ft-dl-same"><span class="ft-dn">' + num + '</span><span class="ft-dt">' + htmlEsc(aLine || '') + '</span></div>');
            } else if (aLine === null) {
                added++;
                lines.push('<div class="ft-dl ft-dl-add"><span class="ft-dn">+' + num + '</span><span class="ft-dt">' + htmlEsc(bLine) + '</span></div>');
            } else if (bLine === null) {
                removed++;
                lines.push('<div class="ft-dl ft-dl-rem"><span class="ft-dn">-' + num + '</span><span class="ft-dt">' + htmlEsc(aLine) + '</span></div>');
            } else {
                changed++;
                lines.push('<div class="ft-dl ft-dl-rem"><span class="ft-dn">~' + num + '</span><span class="ft-dt">' + htmlEsc(aLine) + '</span></div>');
                lines.push('<div class="ft-dl ft-dl-add"><span class="ft-dn">~' + num + '</span><span class="ft-dt">' + htmlEsc(bLine) + '</span></div>');
            }
        }

        var stats = '<div class="ft-diff-stats">'
            + '<span class="ft-ds ft-ds-same">= ' + same + ' unchanged</span>'
            + (added   ? '<span class="ft-ds ft-ds-add">+ ' + added   + ' added</span>'   : '')
            + (removed ? '<span class="ft-ds ft-ds-rem">− ' + removed + ' removed</span>' : '')
            + (changed ? '<span class="ft-ds ft-ds-chg">~ ' + changed + ' changed</span>' : '')
            + '</div>';

        out.innerHTML = stats + '<div class="ft-diff-lines">' + lines.join('') + '</div>';
    }

    function clearDiff() {
        sv('diff-a', ''); sv('diff-b', '');
        setHTML('diff-out', '<div class="ft-diff-empty">Paste two pieces of text above and click Compare to see the diff.</div>');
    }

    /* ──────────────────────────────────────────────────────────
       COPY / DOWNLOAD HELPERS
    ────────────────────────────────────────────────────────── */
    function copyEl(id, btn) {
        var e = el(id);
        if (!e) return;
        copyText(e.value !== undefined ? e.value : e.textContent, btn);
    }

    function dlEl(id, filename) {
        var e = el(id);
        if (!e) return;
        var ext   = (filename.split('.').pop() || '').toLowerCase();
        var mimes = { html: 'text/html', css: 'text/css', js: 'application/javascript', md: 'text/markdown', txt: 'text/plain', scss: 'text/x-scss' };
        dlText(e.value !== undefined ? e.value : e.textContent, filename, mimes[ext] || 'text/plain');
    }

    function clearTool(inId, outId) {
        sv(inId, ''); sv(outId, '');
    }

    function copyText(text, btn) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(function () {
            if (btn) {
                var orig = btn.innerHTML;
                btn.innerHTML = '✓ Copied!';
                btn.classList.add('ft-copied');
                setTimeout(function () { btn.innerHTML = orig; btn.classList.remove('ft-copied'); }, 1600);
            }
        }).catch(function () {
            var ta = document.createElement('textarea');
            ta.value = text; ta.style.cssText = 'position:fixed;opacity:0;top:-9999px';
            document.body.appendChild(ta); ta.focus(); ta.select();
            try { document.execCommand('copy'); } catch (_) {}
            document.body.removeChild(ta);
        });
    }

    function dlText(text, filename, mime) {
        var a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([text], { type: mime || 'text/plain' }));
        a.download = filename; a.click();
        setTimeout(function () { URL.revokeObjectURL(a.href); }, 8000);
    }

    /* ──────────────────────────────────────────────────────────
       INIT
    ────────────────────────────────────────────────────────── */
    function init() {
        var arToggle = el('ft-auto-refresh');
        if (arToggle) {
            _autoRefresh = arToggle.checked;
            arToggle.addEventListener('change', function () { _autoRefresh = this.checked; });
        }

        ['html', 'css', 'js'].forEach(function (t) {
            var ta = el('ft-' + t + '-editor');
            if (ta) ta.addEventListener('input', debouncePreview);
        });

        if (el('ft-html-editor') && !el('ft-html-editor').value.trim()) sv('ft-html-editor', DEFAULT_HTML);
        if (el('ft-css-editor')  && !el('ft-css-editor').value.trim())  sv('ft-css-editor',  DEFAULT_CSS);
        if (el('ft-js-editor')   && !el('ft-js-editor').value.trim())   sv('ft-js-editor',   DEFAULT_JS);

        runPreview();
    }

    document.addEventListener('DOMContentLoaded', init);

    /* ──────────────────────────────────────────────────────────
       PUBLIC API
    ────────────────────────────────────────────────────────── */
    return {
        switchTab:        switchTab,
        switchEditor:     switchEditor,
        runPreview:       runPreview,
        beautifyAll:      beautifyAll,
        minifyAll:        minifyAll,
        resetAll:         resetAll,
        downloadAll:      downloadAll,
        copyOutput:       copyOutput,
        fullscreenPreview:fullscreenPreview,
        runHtml:          runHtml,
        genTable:         genTable,
        runCss:           runCss,
        runJs:            runJs,
        convertVarNames:  convertVarNames,
        clearConsole:     clearConsole,
        runJsConsole:     runJsConsole,
        runDiff:          runDiff,
        clearDiff:        clearDiff,
        copyEl:           copyEl,
        dlEl:             dlEl,
        clearTool:        clearTool,
    };
})();
