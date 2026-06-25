<?php
defined('AWAN') or require_once __DIR__ . '/../../_bootstrap.php';
require_once __DIR__ . '/../../plugins/_sdk.php';
require_once AWAN_ROOT . '/_core/Plugin.php';

$slug = 'network-toolkit';

/* ─── Sidebar groups + tools ─────────────────────────────────────────────── */
$groups = [
    'IP Tools' => [
        ['myip',      'My IP Address',     'Your public IP',          'globe'],
        ['ipgeo',     'IP Geolocation',    'IP → location / ISP',     'map'],
        ['asnlookup', 'ASN Lookup',        'IP → ASN / org info',     'hash'],
        ['ipv4val',   'IPv4 Validator',    'Check IPv4 format',       'shield'],
        ['ipv6val',   'IPv6 Validator',    'Check IPv6 format',       'shield'],
        ['ip4int',    'IPv4 ↔ Integer',    'Dotted ↔ int32',          'arrows'],
        ['iptype',    'IP Type Checker',   'Private / public / class','tag'],
        ['cidr',      'CIDR Calculator',   'Prefix length → mask',    'calc'],
        ['subnet',    'Subnet Calculator', 'Network / broadcast / IPs','calc'],
        ['iprange',   'IP Range Info',     'First / last / count',    'range'],
        ['cidr2range','CIDR → Range',      'Expand CIDR to IPs',      'expand'],
        ['range2cidr','Range → CIDR',      'Compress range to CIDRs', 'compress'],
        ['netcalc',   'Net / Bcast / Wild','Broadcast & wildcard mask','network'],
    ],
    'DNS Tools' => [
        ['dns',      'DNS Lookup',         'Any record type',         'search'],
        ['dns_a',    'A Records',          'IPv4 address records',    'record'],
        ['dns_aaaa', 'AAAA Records',       'IPv6 address records',    'record'],
        ['dns_mx',   'MX Records',         'Mail exchange records',   'mail'],
        ['dns_txt',  'TXT Records',        'Text / verification',     'text'],
        ['dns_ns',   'NS Records',         'Name server records',     'server'],
        ['dns_cname','CNAME Records',      'Canonical name alias',    'link'],
        ['dns_soa',  'SOA Records',        'Start of authority',      'record'],
        ['dns_srv',  'SRV Records',        'Service location',        'record'],
        ['dns_spf',  'SPF Records',        'Sender Policy Framework', 'shield'],
        ['dns_dmarc','DMARC Records',      'Domain-based msg auth',   'shield'],
        ['rdns',     'Reverse DNS',        'IP → hostname (PTR)',     'reverse'],
        ['dns_zone',        'DNS Zone Viewer',   'All records at once',      'list'],
        ['dns_propagation', 'DNS Propagation',  'Check across resolvers',   'signal'],
        ['dns_formatter',   'DNS Record Format','Format & explain records',  'text'],
    ],
    'Domain Tools' => [
        ['domain_parser',    'Domain Parser',     'Parse domain parts',      'parse'],
        ['domain_extractor', 'Domain Extractor',  'Find domains in text',    'extract'],
        ['tld_extractor',    'TLD Extractor',     'Extract TLD from domain', 'tag'],
        ['subdomain',        'Subdomain Extractor','Pull out subdomain',      'layers'],
        ['domain_ip',        'Domain → IP',       'Resolve domain to IPs',   'resolve'],
        ['whois',            'WHOIS / RDAP',      'Domain registration info','info'],
        ['reverse_ip',          'Reverse IP Lookup',   'IP → ISP / org / host',    'reverse'],
        ['domain_age',          'Domain Age',          'Registration date & age',  'calendar'],
        ['domain_expiry',       'Domain Expiry',       'Expiry date & countdown',  'calendar'],
        ['domain_availability', 'Domain Availability', 'Is domain available?',     'check'],
    ],
    'URL Tools' => [
        ['url_parser',    'URL Parser',       'Dissect URL components',  'parse'],
        ['url_enc',       'URL Encoder',      'Encode URI component',    'encode'],
        ['url_dec',       'URL Decoder',      'Decode URI component',    'decode'],
        ['url_qb',        'Query Builder',    'Build query string',      'build'],
        ['url_qp',        'Query Parser',     'Parse query string',      'parse'],
        ['url_extractor', 'URL Extractor',    'Find URLs in text',       'extract'],
        ['url_slug',      'Slug Generator',   'Text → URL-friendly slug','slug'],
        ['url_redirect',  'Redirect Checker', 'Check URL redirect',      'redirect'],
        ['url_opener',    'URL Opener Tester','Test if a URL opens OK',   'open'],
        ['canonical_url', 'Canonical URL',    'Find canonical link tag',  'link'],
    ],
    'HTTP Tools' => [
        ['http_status',       'HTTP Status Codes',  'Complete status reference', 'list'],
        ['http_status_check', 'HTTP Status Check',  'Live URL → status code',   'check'],
        ['content_type',      'Content-Type Check', 'URL content-type header',  'file'],
        ['http_headers',  'HTTP Header Viewer','Fetch response headers',   'headers'],
        ['ua_parser',     'User Agent Parser', 'Parse browser / OS / bot', 'ua'],
        ['mime',          'MIME Type Lookup',  'Extension ↔ MIME type',   'file'],
        ['cookie_parser', 'Cookie Parser',     'Parse Set-Cookie headers', 'cookie'],
        ['sec_headers',   'Security Headers',  'Check HSTS / CSP / etc.', 'shield'],
        ['cache_headers', 'Cache Headers',     'Cache-Control & ETag info','cache'],
        ['redirect_chain','Redirect Chain',    'Trace all hops/redirects', 'chain'],
        ['response_time', 'Response Time',     '3-ping avg latency check', 'time'],
    ],
    'SSL / TLS Tools' => [
        ['ssl_cert',    'Certificate Checker','Full SSL cert details',    'cert'],
        ['ssl_expiry',  'Expiry Checker',     'Days until cert expires',  'calendar'],
        ['ssl_chain',   'Chain Viewer',       'Certificate chain info',   'chain'],
        ['cert_decoder',  'Certificate Decoder', 'Raw cert field decoder',   'decode'],
        ['csr_decoder',   'CSR Decoder',         'Decode a CSR PEM',         'decode'],
        ['csr_generator', 'CSR Generator',       'Generate new CSR + key',   'build'],
        ['pem_decoder',   'PEM Cert Decoder',    'Decode pasted cert PEM',   'cert'],
    ],
    'Email Tools' => [
        ['email_headers','Email Header Analyzer','Parse raw email headers', 'mail'],
        ['spf_check',   'SPF Checker',        'SPF record lookup',        'shield'],
        ['dkim_check',  'DKIM Checker',       'DKIM key lookup',          'key'],
        ['dmarc_check', 'DMARC Checker',      'DMARC policy lookup',      'shield'],
        ['mx_lookup',   'MX Lookup',          'Mail server resolution',   'mail'],
    ],
    'Connectivity' => [
        ['availability', 'Availability Checker','Is website reachable?',  'check'],
        ['server_info',  'Server Info',        'Server & tech headers',   'server'],
        ['http_method',  'HTTP Method Tester', 'Test allowed methods',    'method'],
        ['robots',       'Robots.txt Viewer',  'Crawl rules file',        'robot'],
        ['sitemap',      'Sitemap Checker',    'XML sitemap viewer',      'sitemap'],
        ['security_txt', 'Security.txt',       '/.well-known/security.txt','lock'],
        ['common_ports', 'Common Ports Ref',   'Well-known port list',    'ports'],
        ['port_lookup',    'Port Lookup',         'Search ports by name/num', 'search'],
        ['open_redirect',  'Open Redirect Check', 'Detect cross-domain redir','redirect'],
    ],
];

/* ─── SVG icon set ───────────────────────────────────────────────────────── */
function nt_icon(string $key): string {
    $icons = [
        'globe'    => '<path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        'map'      => '<polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/>',
        'hash'     => '<line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/><line x1="10" y1="3" x2="8" y2="21"/><line x1="16" y1="3" x2="14" y2="21"/>',
        'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'arrows'   => '<polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>',
        'tag'      => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/>',
        'calc'     => '<rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="8" y2="18"/><line x1="12" y1="10" x2="12" y2="18"/><line x1="16" y1="10" x2="16" y2="18"/>',
        'range'    => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
        'expand'   => '<polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>',
        'compress' => '<polyline points="4 14 10 14 10 20"/><polyline points="20 10 14 10 14 4"/><line x1="10" y1="14" x2="3" y2="21"/><line x1="21" y1="3" x2="14" y2="10"/>',
        'network'  => '<rect x="2" y="2" width="8" height="8" rx="1"/><rect x="14" y="2" width="8" height="8" rx="1"/><rect x="8" y="14" width="8" height="8" rx="1"/><path d="M6 10v4h12v-4"/>',
        'search'   => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'record'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
        'mail'     => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>',
        'text'     => '<line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/>',
        'server'   => '<rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>',
        'link'     => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
        'reverse'  => '<polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.1"/>',
        'list'     => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>',
        'parse'    => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
        'extract'  => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>',
        'layers'   => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
        'resolve'  => '<circle cx="12" cy="12" r="10"/><polyline points="12 8 12 12 14 14"/>',
        'info'     => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
        'encode'   => '<path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>',
        'decode'   => '<polyline points="20 6 9 17 4 12"/>',
        'build'    => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
        'slug'     => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
        'redirect' => '<polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 0 0-4-4H4"/>',
        'headers'  => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/>',
        'ua'       => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',
        'file'     => '<path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/>',
        'cookie'   => '<circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/>',
        'cert'     => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><line x1="6" y1="11" x2="6.01" y2="11"/><line x1="10" y1="11" x2="18" y2="11"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'chain'    => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
        'key'      => '<path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>',
        'check'    => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'method'   => '<polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/>',
        'robot'    => '<rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="15" x2="8" y2="17"/><line x1="16" y1="15" x2="16" y2="17"/>',
        'sitemap'  => '<rect x="2" y="3" width="6" height="4" rx="1"/><rect x="8" y="9" width="8" height="4" rx="1"/><rect x="2" y="17" width="6" height="4" rx="1"/><rect x="14" y="17" width="6" height="4" rx="1"/><path d="M5 7v10"/><path d="M5 12h7"/><path d="M12 11v6"/><path d="M17 13v4"/>',
        'lock'     => '<rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'ports'    => '<rect x="2" y="6" width="4" height="12" rx="1"/><rect x="10" y="6" width="4" height="12" rx="1"/><rect x="18" y="6" width="4" height="12" rx="1"/><line x1="6" y1="12" x2="10" y2="12"/><line x1="14" y1="12" x2="18" y2="12"/>',
        'time'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'cache'    => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
        'default'  => '<circle cx="12" cy="12" r="10"/>',
    ];
    $d = $icons[$key] ?? $icons['default'];
    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $d . '</svg>';
}

/* ─── HTML panel helpers ─────────────────────────────────────────────────── */
function nt_toolbar(string $title, string $hint, string $right = ''): string {
    return '<div class="nt-toolbar"><div class="nt-toolbar-left"><span class="nt-tool-title">' . htmlspecialchars($title) . '</span>' .
        ($hint ? '<span class="nt-hint">' . htmlspecialchars($hint) . '</span>' : '') .
        '</div>' . ($right ? '<div class="nt-toolbar-right">' . $right . '</div>' : '') . '</div>';
}

// Standard API lookup panel: text input + button + result area
function nt_api_panel(string $id, string $title, string $hint, string $placeholder, string $fn, string $btnLabel = 'Lookup', string $extra = ''): string {
    $lkSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>';
    return '<div id="nt-' . $id . '" class="nt-panel">' .
        nt_toolbar($title, $hint) .
        '<div class="nt-body"><div class="nt-lookup-row"><input type="text" id="' . $id . '-in" placeholder="' . htmlspecialchars($placeholder) . '" onkeydown="if(event.key===\'Enter\')NT.' . $fn . '()">' .
        $extra .
        '<button class="nt-btn nt-btn-primary" onclick="NT.' . $fn . '()">' . $lkSvg . ' ' . htmlspecialchars($btnLabel) . '</button></div>' .
        '<div id="' . $id . '-out" class="nt-result"></div></div></div>';
}

// Client-side tool: textarea/input → output
function nt_cs_panel(string $id, string $title, string $hint, string $placeholder, string $fn, bool $textarea = false): string {
    $inputHtml = $textarea
        ? '<textarea class="nt-ta" id="' . $id . '-in" placeholder="' . htmlspecialchars($placeholder) . '" rows="5" oninput="NT.' . $fn . '()"></textarea>'
        : '<div class="nt-lookup-row"><input type="text" id="' . $id . '-in" placeholder="' . htmlspecialchars($placeholder) . '" oninput="NT.' . $fn . '()"></div>';
    return '<div id="nt-' . $id . '" class="nt-panel">' .
        nt_toolbar($title, $hint) .
        '<div class="nt-body">' . $inputHtml .
        '<div id="' . $id . '-out" class="nt-result"></div></div></div>';
}

ob_start();
?>
<link rel="stylesheet" href="/plugins/<?= $slug ?>/assets/network-toolkit.css">

<?php
$cat_meta = [
    'IP Tools'        => ['icon' => 'globe',   'desc' => 'Geolocation, CIDR, subnet, validators',      'color' => '#0ea5e9'],
    'DNS Tools'       => ['icon' => 'search',  'desc' => 'Records, propagation, zone viewer',           'color' => '#8b5cf6'],
    'Domain Tools'    => ['icon' => 'info',    'desc' => 'WHOIS, RDAP, domain age & availability',      'color' => '#f59e0b'],
    'URL Tools'       => ['icon' => 'link',    'desc' => 'Parse, encode, decode, slugify & extract',    'color' => '#10b981'],
    'HTTP Tools'      => ['icon' => 'headers', 'desc' => 'Status codes, headers, UA parser, MIME',      'color' => '#ef4444'],
    'SSL / TLS Tools' => ['icon' => 'cert',    'desc' => 'SSL checker, expiry, CSR & PEM decoder',      'color' => '#06b6d4'],
    'Email Tools'     => ['icon' => 'mail',    'desc' => 'SPF, DKIM, DMARC, MX & header analyser',     'color' => '#f97316'],
    'Connectivity'    => ['icon' => 'network', 'desc' => 'Availability, ports, robots.txt & sitemap',   'color' => '#6366f1'],
];
?>

<div class="nt-app">

<!-- ── Search Bar ──────────────────────────────────────────────────────── -->
<div class="nt-search-bar">
  <div class="nt-search-wrap">
    <svg class="nt-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input id="nt-search" type="text" placeholder="Search 82 network tools…" autocomplete="off" oninput="NT.searchTools(this.value)" onfocus="if(this.value)NT.searchTools(this.value)" onblur="setTimeout(function(){NT.closeSearch()},180)">
    <div id="nt-search-drop" class="nt-search-drop"></div>
  </div>
</div>

<!-- ── Category Cards ──────────────────────────────────────────────────── -->
<div class="nt-cat-cards" id="nt-cat-cards">
<?php foreach ($groups as $groupName => $tools):
    $m   = $cat_meta[$groupName] ?? ['icon' => 'default', 'desc' => '', 'color' => 'var(--color-primary)'];
    $cnt = count($tools);
?>
<button class="nt-cat-card" data-cat="<?= htmlspecialchars($groupName, ENT_QUOTES) ?>" onclick='NT.showCategory(<?= json_encode($groupName) ?>)'>
    <span class="nt-cat-card-icon" style="color:<?= $m['color'] ?>"><?= nt_icon($m['icon']) ?></span>
    <span class="nt-cat-card-body">
        <span class="nt-cat-card-name"><?= htmlspecialchars($groupName) ?></span>
        <span class="nt-cat-card-count"><?= $cnt ?> tools</span>
        <span class="nt-cat-card-desc"><?= htmlspecialchars($m['desc']) ?></span>
    </span>
</button>
<?php endforeach; ?>
</div>

<!-- ── Category Nav Pills ──────────────────────────────────────────────── -->
<div class="nt-cat-nav" id="nt-cat-nav">
<?php foreach ($groups as $groupName => $tools): ?>
<button class="nt-cat-pill" data-cat="<?= htmlspecialchars($groupName, ENT_QUOTES) ?>" onclick='NT.showCategory(<?= json_encode($groupName) ?>)'><?= htmlspecialchars($groupName) ?></button>
<?php endforeach; ?>
</div>

<!-- ── Tool Grid ────────────────────────────────────────────────────────── -->
<div class="nt-tool-grid-wrap" id="nt-tool-grid-wrap" style="display:none">
  <div class="nt-tool-grid" id="nt-tool-grid"></div>
</div>

<!-- ── Workspace ────────────────────────────────────────────────────────── -->
<div class="nt-workspace" id="nt-workspace" style="display:none">
<div class="nt-workspace-bar">
  <div class="nt-breadcrumb">
    <button class="nt-back-btn" onclick="NT.backToGrid()"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg> Back</button>
    <span class="nt-bc-sep">/</span>
    <span class="nt-bc-cat" id="nt-bc-cat"></span>
    <span class="nt-bc-sep">/</span>
    <span class="nt-bc-tool" id="nt-bc-tool"></span>
  </div>
  <button class="nt-btn nt-btn-sm" onclick="NT.clearCurrent()">Clear</button>
</div>
<div class="nt-workspace-body" id="nt-workspace-body">

<!-- ══════════════ IP TOOLS ══════════════════════════════════════════════ -->

<!-- My IP -->
<div id="nt-myip" class="nt-panel">
<?= nt_toolbar('My IP Address', 'Your current public IP address as seen by the server') ?>
<div class="nt-body">
    <button class="nt-btn nt-btn-primary" style="align-self:flex-start" onclick="NT.runMyIP()">
        <?= nt_icon('globe') ?> Detect My IP
    </button>
    <div id="myip-out" class="nt-result"></div>
</div>
</div>

<!-- IP Geolocation -->
<?= nt_api_panel('ipgeo','IP Geolocation','IP address → country / city / ISP / coordinates','1.1.1.1 or domain.com','runIPGeo') ?>

<!-- ASN Lookup -->
<?= nt_api_panel('asnlookup','ASN Lookup','IP address → autonomous system number & organisation','8.8.8.8 or domain.com','runASN') ?>

<!-- IPv4 Validator -->
<?= nt_cs_panel('ipv4val','IPv4 Validator','Check whether an IP address is valid IPv4 format','192.168.1.1','runIPv4Val') ?>

<!-- IPv6 Validator -->
<?= nt_cs_panel('ipv6val','IPv6 Validator','Check whether an address is valid IPv6 format','2001:db8::1','runIPv6Val') ?>

<!-- IPv4 ↔ Integer -->
<div id="nt-ip4int" class="nt-panel">
<?= nt_toolbar('IPv4 ↔ Integer Converter','Convert between dotted-decimal IPv4 and 32-bit unsigned integer') ?>
<div class="nt-body">
    <div>
        <div class="nt-field-label">IPv4 Address → Integer</div>
        <div class="nt-lookup-row">
            <input type="text" id="ip4int-ip" placeholder="192.168.1.1" oninput="NT.runIP4toInt()">
        </div>
        <div id="ip4int-out1" class="nt-result" style="margin-top:8px"></div>
    </div>
    <div>
        <div class="nt-field-label">Integer → IPv4 Address</div>
        <div class="nt-lookup-row">
            <input type="text" id="ip4int-int" placeholder="3232235777" oninput="NT.runIntToIP4()">
        </div>
        <div id="ip4int-out2" class="nt-result" style="margin-top:8px"></div>
    </div>
</div>
</div>

<!-- IP Type Checker -->
<?= nt_cs_panel('iptype','IP Type Checker','Detect private / public / reserved / class / loopback / multicast','10.0.0.1 or 8.8.8.8','runIPType') ?>

<!-- CIDR Calculator -->
<div id="nt-cidr" class="nt-panel">
<?= nt_toolbar('CIDR Calculator','Enter CIDR notation to calculate network details') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="cidr-in" placeholder="192.168.1.0/24" oninput="NT.runCIDR()">
    </div>
    <div id="cidr-out" class="nt-result"></div>
</div>
</div>

<!-- Subnet Calculator -->
<div id="nt-subnet" class="nt-panel">
<?= nt_toolbar('Subnet Calculator','IP address + prefix length → full subnet details') ?>
<div class="nt-body">
    <div class="nt-inline-row">
        <input type="text" id="subnet-ip" placeholder="192.168.1.55" style="flex:1" oninput="NT.runSubnet()">
        <span style="font-weight:600;color:var(--color-text-muted)">/</span>
        <input type="number" id="subnet-pfx" placeholder="24" min="0" max="32" style="width:70px" oninput="NT.runSubnet()">
    </div>
    <div id="subnet-out" class="nt-result"></div>
</div>
</div>

<!-- IP Range Info -->
<div id="nt-iprange" class="nt-panel">
<?= nt_toolbar('IP Range Info','Enter start and end IPs to get range size, CIDR list, broadcast') ?>
<div class="nt-body">
    <div class="nt-inline-row">
        <input type="text" id="iprange-start" placeholder="Start IP: 10.0.0.1" style="flex:1" oninput="NT.runIPRange()">
        <span style="color:var(--color-text-muted)">—</span>
        <input type="text" id="iprange-end"   placeholder="End IP:   10.0.0.50" style="flex:1" oninput="NT.runIPRange()">
    </div>
    <div id="iprange-out" class="nt-result"></div>
</div>
</div>

<!-- CIDR to Range -->
<div id="nt-cidr2range" class="nt-panel">
<?= nt_toolbar('CIDR → IP Range','Expand CIDR block to first / last IP and usable host count') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="cidr2range-in" placeholder="10.0.0.0/22" oninput="NT.runCIDR2Range()">
    </div>
    <div id="cidr2range-out" class="nt-result"></div>
</div>
</div>

<!-- Range to CIDR -->
<div id="nt-range2cidr" class="nt-panel">
<?= nt_toolbar('Range → CIDR List','Compress an IP range into the minimum set of CIDR blocks') ?>
<div class="nt-body">
    <div class="nt-inline-row">
        <input type="text" id="range2cidr-start" placeholder="Start: 192.168.1.0" style="flex:1" oninput="NT.runRange2CIDR()">
        <span style="color:var(--color-text-muted)">—</span>
        <input type="text" id="range2cidr-end"   placeholder="End: 192.168.1.63"  style="flex:1" oninput="NT.runRange2CIDR()">
    </div>
    <div id="range2cidr-out" class="nt-result"></div>
</div>
</div>

<!-- Net/Bcast/Wildcard -->
<div id="nt-netcalc" class="nt-panel">
<?= nt_toolbar('Network / Broadcast / Wildcard','Calculate network address, broadcast address and wildcard mask') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="netcalc-in" placeholder="172.16.10.5/20" oninput="NT.runNetCalc()">
    </div>
    <div id="netcalc-out" class="nt-result"></div>
</div>
</div>

<!-- ══════════════ DNS TOOLS ══════════════════════════════════════════════ -->

<!-- DNS Lookup (generic) -->
<div id="nt-dns" class="nt-panel">
<?= nt_toolbar('DNS Lookup','Look up any DNS record type for a domain') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="dns-in" placeholder="example.com" onkeydown="if(event.key==='Enter')NT.runDNS()">
        <select id="dns-type" style="padding:8px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text);font-size:13px">
            <option>A</option><option>AAAA</option><option>MX</option><option>TXT</option>
            <option>NS</option><option>CNAME</option><option>SOA</option><option>SRV</option><option>ANY</option>
        </select>
        <button class="nt-btn nt-btn-primary" onclick="NT.runDNS()"><?= nt_icon('search') ?> Lookup</button>
    </div>
    <div id="dns-out" class="nt-result"></div>
</div>
</div>

<?php
// Generate individual DNS type panels
$dnsTypes = [
    ['dns_a',    'A Records',    'IPv4 address records for a domain',          'A',     'example.com'],
    ['dns_aaaa', 'AAAA Records', 'IPv6 address records for a domain',          'AAAA',  'example.com'],
    ['dns_mx',   'MX Records',   'Mail exchange servers (priority ordered)',    'MX',    'gmail.com'],
    ['dns_txt',  'TXT Records',  'Text records (SPF, DKIM, verification...)',  'TXT',   'example.com'],
    ['dns_ns',   'NS Records',   'Authoritative name servers for the domain',  'NS',    'example.com'],
    ['dns_cname','CNAME Records','Canonical name aliases',                     'CNAME', 'www.example.com'],
    ['dns_soa',  'SOA Records',  'Start of authority (serial / TTL / admin)',  'SOA',   'example.com'],
    ['dns_srv',  'SRV Records',  'Service location (SIP, XMPP, etc.)',         'SRV',   '_sip._tcp.example.com'],
];
foreach ($dnsTypes as [$id, $title, $hint, $type, $ph]):
    $fn = 'runDNSType_' . strtolower(str_replace('-', '_', $id));
?>
<div id="nt-<?= $id ?>" class="nt-panel">
<?= nt_toolbar($title, $hint) ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="<?= $id ?>-in" placeholder="<?= $ph ?>" onkeydown="if(event.key==='Enter')NT.callDNS('<?= $id ?>','<?= $type ?>')">
        <button class="nt-btn nt-btn-primary" onclick="NT.callDNS('<?= $id ?>','<?= $type ?>')"><?= nt_icon('search') ?> Lookup</button>
    </div>
    <div id="<?= $id ?>-out" class="nt-result"></div>
</div>
</div>
<?php endforeach; ?>

<!-- SPF Records -->
<div id="nt-dns_spf" class="nt-panel">
<?= nt_toolbar('SPF Records','TXT records starting with v=spf1 — Sender Policy Framework') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="dns_spf-in" placeholder="example.com" onkeydown="if(event.key==='Enter')NT.runSPFDNS()">
        <button class="nt-btn nt-btn-primary" onclick="NT.runSPFDNS()"><?= nt_icon('search') ?> Lookup</button>
    </div>
    <div id="dns_spf-out" class="nt-result"></div>
</div>
</div>

<!-- DMARC Records -->
<div id="nt-dns_dmarc" class="nt-panel">
<?= nt_toolbar('DMARC Records','TXT records at _dmarc.{domain} — Domain-based Message Authentication') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="dns_dmarc-in" placeholder="example.com" onkeydown="if(event.key==='Enter')NT.runDMARCDNS()">
        <button class="nt-btn nt-btn-primary" onclick="NT.runDMARCDNS()"><?= nt_icon('search') ?> Lookup</button>
    </div>
    <div id="dns_dmarc-out" class="nt-result"></div>
</div>
</div>

<!-- Reverse DNS -->
<?= nt_api_panel('rdns','Reverse DNS (PTR)','IP address → hostname reverse DNS lookup','8.8.8.8','runRDNS') ?>

<!-- DNS Zone Viewer -->
<?= nt_api_panel('dns_zone','DNS Zone Viewer','All major DNS record types for a domain in one view','example.com','runDNSZone','View Zone') ?>

<!-- DNS Propagation Checker -->
<div id="nt-dns_propagation" class="nt-panel">
<?= nt_toolbar('DNS Propagation Checker','Check if your DNS changes have propagated across multiple global resolvers') ?>
<div class="nt-body">
    <div class="nt-inline-row">
        <input type="text" id="dns_propagation-in" placeholder="example.com" style="flex:3" onkeydown="if(event.key==='Enter')NT.runDNSPropagation()">
        <select id="dns_propagation-type" style="padding:8px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)">
            <option value="A">A</option>
            <option value="AAAA">AAAA</option>
            <option value="MX">MX</option>
            <option value="TXT">TXT</option>
            <option value="NS">NS</option>
            <option value="CNAME">CNAME</option>
        </select>
        <button class="nt-btn nt-btn-primary" onclick="NT.runDNSPropagation()"><?= nt_icon('search') ?> Check</button>
    </div>
    <div id="dns_propagation-out" class="nt-result"></div>
</div>
</div>

<!-- DNS Record Formatter -->
<div id="nt-dns_formatter" class="nt-panel">
<?= nt_toolbar('DNS Record Formatter','Paste raw DNS record output and get it formatted with explanations') ?>
<div class="nt-body">
    <textarea class="nt-ta" id="dns_formatter-in" placeholder="Paste raw DNS records here, e.g.:&#10;example.com. 300 IN A 93.184.216.34&#10;example.com. 3600 IN MX 10 mail.example.com.&#10;example.com. 3600 IN TXT &quot;v=spf1 include:_spf.example.com ~all&quot;" rows="7" oninput="NT.runDNSFormatter()"></textarea>
    <div id="dns_formatter-out" class="nt-result"></div>
</div>
</div>

<!-- ══════════════ DOMAIN TOOLS ═══════════════════════════════════════════ -->

<!-- Domain Parser -->
<?= nt_cs_panel('domain_parser','Domain Parser','Parse a URL or domain into protocol / subdomain / SLD / TLD / path / port','https://www.example.co.uk/page?q=1','runDomainParser') ?>

<!-- Domain Extractor -->
<?= nt_cs_panel('domain_extractor','Domain Extractor','Extract all domains and hostnames from a block of text','Paste text containing links and domains here...','runDomainExtractor',true) ?>

<!-- TLD Extractor -->
<?= nt_cs_panel('tld_extractor','TLD Extractor','Extract the top-level domain (TLD) from any domain or URL','https://www.amazon.co.uk/','runTLDExtractor') ?>

<!-- Subdomain Extractor -->
<?= nt_cs_panel('subdomain','Subdomain Extractor','Extract just the subdomain portion from a full domain or URL','blog.api.example.com','runSubdomain') ?>

<!-- Domain to IP -->
<?= nt_api_panel('domain_ip','Domain → IP','Resolve a domain name to its IPv4 and IPv6 addresses','example.com','runDomainIP') ?>

<!-- WHOIS / RDAP -->
<?= nt_api_panel('whois','WHOIS / RDAP Lookup','Domain registration info via RDAP protocol (registrar / dates / nameservers)','example.com','runWHOIS') ?>

<!-- Reverse IP Lookup -->
<?= nt_api_panel('reverse_ip','Reverse IP Lookup','IP address → ISP / organisation / hosting provider / reverse DNS','8.8.8.8','runReverseIP') ?>

<!-- Domain Age -->
<?= nt_api_panel('domain_age','Domain Age Checker','Look up the registration date and age of a domain via RDAP','example.com','runDomainAge') ?>

<!-- Domain Expiry -->
<?= nt_api_panel('domain_expiry','Domain Expiry Checker','Check when a domain expires and how many days remain via RDAP','example.com','runDomainExpiry') ?>

<!-- Domain Availability -->
<?= nt_api_panel('domain_availability','Domain Availability','Check if a domain name is available to register','example-domain-check.com','runDomainAvailability','Check') ?>

<!-- ══════════════ URL TOOLS ══════════════════════════════════════════════ -->

<!-- URL Parser -->
<?= nt_cs_panel('url_parser','URL Parser','Dissect a URL into all its components — protocol, host, path, query, fragment','https://user:pass@example.com:8080/path?a=1#anchor','runURLParser') ?>

<!-- URL Encoder -->
<?= nt_cs_panel('url_enc','URL Encoder','Percent-encode special characters for safe use in URLs','Hello World! Special chars: &, =, ?','runURLEnc',true) ?>

<!-- URL Decoder -->
<?= nt_cs_panel('url_dec','URL Decoder','Decode percent-encoded URI components back to plain text','Hello%20World%21%20Special%20chars%3A%20%26%2C%20%3D%2C%20%3F','runURLDec',true) ?>

<!-- URL Query Builder -->
<div id="nt-url_qb" class="nt-panel">
<?= nt_toolbar('URL Query Builder','Build a URL query string from key-value pairs') ?>
<div class="nt-body">
    <div>
        <div class="nt-field-label">Base URL (optional)</div>
        <div class="nt-lookup-row"><input type="text" id="url_qb-base" placeholder="https://example.com/search" oninput="NT.runURLQB()"></div>
    </div>
    <div>
        <div class="nt-field-label">Parameters <button class="nt-btn nt-btn-sm" style="margin-left:8px" onclick="NT.addQBRow()">+ Add Param</button></div>
        <div id="url_qb-params" style="display:flex;flex-direction:column;gap:6px;margin-top:6px"></div>
    </div>
    <div id="url_qb-out" class="nt-result"></div>
</div>
</div>

<!-- URL Query Parser -->
<?= nt_cs_panel('url_qp','URL Query Parser','Parse a URL or query string and list all key-value parameters','https://example.com/search?q=hello+world&page=2&sort=desc','runURLQP') ?>

<!-- URL Extractor -->
<?= nt_cs_panel('url_extractor','URL Extractor','Find and extract all URLs from a block of text','Paste HTML, emails, or any text with embedded links here...','runURLExtractor',true) ?>

<!-- URL Slug Generator -->
<?= nt_cs_panel('url_slug','URL Slug Generator','Convert any text into a clean, URL-friendly slug','My Blog Post Title: How to Build Great APIs!','runURLSlug') ?>

<!-- URL Redirect Checker -->
<?= nt_api_panel('url_redirect','URL Redirect Checker','Check if a URL issues an HTTP redirect and where it goes','https://example.com','runURLRedirect') ?>

<!-- URL Opener Tester -->
<div id="nt-url_opener" class="nt-panel">
<?= nt_toolbar('URL Opener Tester','Test whether a URL is reachable and preview the final destination in a new tab') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="url_opener-in" placeholder="https://example.com" onkeydown="if(event.key==='Enter')NT.runURLOpener()">
        <button class="nt-btn nt-btn-primary" onclick="NT.runURLOpener()"><?= nt_icon('open') ?> Test &amp; Open</button>
    </div>
    <div id="url_opener-out" class="nt-result"></div>
</div>
</div>

<!-- Canonical URL Checker -->
<?= nt_api_panel('canonical_url','Canonical URL Checker','Find the <link rel="canonical"> tag declared by a web page','https://example.com','runCanonicalURL','Check') ?>

<!-- ══════════════ HTTP TOOLS ═════════════════════════════════════════════ -->

<!-- HTTP Status Codes (reference) -->
<div id="nt-http_status" class="nt-panel">
<?= nt_toolbar('HTTP Status Code Reference','Complete list of HTTP status codes with descriptions') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="http_status-in" placeholder="Filter by code or description..." oninput="NT.filterHTTPStatus()">
    </div>
    <div id="http_status-out" class="nt-result"></div>
</div>
</div>

<!-- HTTP Header Viewer -->
<?= nt_api_panel('http_headers','HTTP Header Viewer','Fetch all response headers from any URL','https://example.com','runHTTPHeaders') ?>

<!-- User Agent Parser -->
<?= nt_cs_panel('ua_parser','User Agent Parser','Parse any User-Agent string to identify browser, OS, device and bot status','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','runUAParser') ?>

<!-- MIME Type Lookup -->
<div id="nt-mime" class="nt-panel">
<?= nt_toolbar('MIME Type Lookup','Search MIME types by extension or type name') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="mime-in" placeholder="html, json, mp4, pdf, or text/html..." oninput="NT.runMIMELookup()">
    </div>
    <div id="mime-out" class="nt-result"></div>
</div>
</div>

<!-- Cookie Parser -->
<?= nt_cs_panel('cookie_parser','Cookie Parser','Parse a Set-Cookie header or cookie string into its fields','sessionid=abc123; Path=/; HttpOnly; Secure; SameSite=Strict; Expires=Thu, 01 Jan 2026 00:00:00 GMT','runCookieParser',true) ?>

<!-- Security Headers -->
<?= nt_api_panel('sec_headers','Security Header Checker','Check which security headers (HSTS, CSP, X-Frame-Options…) are present','https://example.com','runSecurityHeaders') ?>

<!-- Cache Headers -->
<?= nt_api_panel('cache_headers','Cache Headers','Inspect Cache-Control, ETag, Expires, Vary and CDN cache status','https://example.com','runCacheHeaders') ?>

<!-- Redirect Chain -->
<?= nt_api_panel('redirect_chain','Redirect Chain','Follow all HTTP redirects from a URL and show each hop','https://example.com','runRedirectChain','Trace') ?>

<!-- Response Time -->
<?= nt_api_panel('response_time','Website Response Time','Measure response latency (3 pings, avg / min / max)','https://example.com','runResponseTime','Measure') ?>

<!-- HTTP Status Check (live) -->
<?= nt_api_panel('http_status_check','HTTP Status Check','Fetch a URL and return its live HTTP status code and response time','https://example.com','runHTTPStatusCheck','Check') ?>

<!-- Content-Type Checker -->
<?= nt_api_panel('content_type','Content-Type Checker','Retrieve the Content-Type, MIME type, charset and encoding from a URL','https://example.com','runContentType','Check') ?>

<!-- ══════════════ SSL / TLS TOOLS ═════════════════════════════════════════ -->

<!-- SSL Certificate Checker -->
<div id="nt-ssl_cert" class="nt-panel">
<?= nt_toolbar('SSL Certificate Checker','Full SSL/TLS certificate details for any domain') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="ssl_cert-in" placeholder="example.com" onkeydown="if(event.key==='Enter')NT.callSSL('ssl_cert','full')">
        <input type="number" id="ssl_cert-port" placeholder="443" min="1" max="65535" style="width:80px;padding:8px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text);font-family:monospace">
        <button class="nt-btn nt-btn-primary" onclick="NT.callSSL('ssl_cert','full')"><?= nt_icon('search') ?> Check</button>
    </div>
    <div id="ssl_cert-out" class="nt-result"></div>
</div>
</div>

<!-- SSL Expiry -->
<div id="nt-ssl_expiry" class="nt-panel">
<?= nt_toolbar('SSL Expiry Checker','Check SSL certificate expiry date and days remaining') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="ssl_expiry-in" placeholder="example.com" onkeydown="if(event.key==='Enter')NT.callSSL('ssl_expiry','expiry')">
        <button class="nt-btn nt-btn-primary" onclick="NT.callSSL('ssl_expiry','expiry')"><?= nt_icon('search') ?> Check</button>
    </div>
    <div id="ssl_expiry-out" class="nt-result"></div>
</div>
</div>

<!-- SSL Chain Viewer -->
<div id="nt-ssl_chain" class="nt-panel">
<?= nt_toolbar('SSL Chain Viewer','View the full certificate chain for a domain') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="ssl_chain-in" placeholder="example.com" onkeydown="if(event.key==='Enter')NT.callSSL('ssl_chain','chain')">
        <button class="nt-btn nt-btn-primary" onclick="NT.callSSL('ssl_chain','chain')"><?= nt_icon('search') ?> View Chain</button>
    </div>
    <div id="ssl_chain-out" class="nt-result"></div>
</div>
</div>

<!-- Certificate Decoder -->
<div id="nt-cert_decoder" class="nt-panel">
<?= nt_toolbar('Certificate Decoder','Decode all fields from an SSL certificate') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="cert_decoder-in" placeholder="example.com" onkeydown="if(event.key==='Enter')NT.callSSL('cert_decoder','decode')">
        <button class="nt-btn nt-btn-primary" onclick="NT.callSSL('cert_decoder','decode')"><?= nt_icon('search') ?> Decode</button>
    </div>
    <div id="cert_decoder-out" class="nt-result"></div>
</div>
</div>

<!-- CSR Decoder -->
<div id="nt-csr_decoder" class="nt-panel">
<?= nt_toolbar('CSR Decoder','Decode a Certificate Signing Request (CSR) PEM to view subject, key type and bits') ?>
<div class="nt-body">
    <textarea class="nt-ta" id="csr_decoder-in" placeholder="Paste your CSR PEM here:&#10;-----BEGIN CERTIFICATE REQUEST-----&#10;MIIByjCCATMCAQAwga...&#10;-----END CERTIFICATE REQUEST-----" rows="8"></textarea>
    <div class="nt-lookup-row" style="margin-top:8px">
        <button class="nt-btn nt-btn-primary" onclick="NT.runCSRDecoder()"><?= nt_icon('decode') ?> Decode CSR</button>
    </div>
    <div id="csr_decoder-out" class="nt-result"></div>
</div>
</div>

<!-- CSR Generator -->
<div id="nt-csr_generator" class="nt-panel">
<?= nt_toolbar('CSR Generator','Generate a new Certificate Signing Request (CSR) and private key') ?>
<div class="nt-body">
    <div class="nt-kv" style="margin-bottom:12px">
        <table style="width:100%;border-collapse:collapse">
            <tr><td style="padding:5px 8px;width:160px;color:var(--color-text-muted);font-size:13px">Common Name (CN) *</td><td><input type="text" id="csr_gen-cn" placeholder="example.com" style="width:100%;padding:7px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)"></td></tr>
            <tr><td style="padding:5px 8px;color:var(--color-text-muted);font-size:13px">Organisation</td><td><input type="text" id="csr_gen-org" placeholder="Acme Corp" style="width:100%;padding:7px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)"></td></tr>
            <tr><td style="padding:5px 8px;color:var(--color-text-muted);font-size:13px">Org Unit</td><td><input type="text" id="csr_gen-ou" placeholder="IT Department" style="width:100%;padding:7px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)"></td></tr>
            <tr><td style="padding:5px 8px;color:var(--color-text-muted);font-size:13px">City / Locality</td><td><input type="text" id="csr_gen-city" placeholder="London" style="width:100%;padding:7px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)"></td></tr>
            <tr><td style="padding:5px 8px;color:var(--color-text-muted);font-size:13px">State / Province</td><td><input type="text" id="csr_gen-state" placeholder="England" style="width:100%;padding:7px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)"></td></tr>
            <tr><td style="padding:5px 8px;color:var(--color-text-muted);font-size:13px">Country (2-letter)</td><td><input type="text" id="csr_gen-country" placeholder="US" maxlength="2" style="width:100%;padding:7px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)"></td></tr>
            <tr><td style="padding:5px 8px;color:var(--color-text-muted);font-size:13px">Key Size</td><td>
                <select id="csr_gen-bits" style="padding:7px 10px;border:1px solid var(--color-border);border-radius:5px;background:var(--color-background);color:var(--color-text)">
                    <option value="2048" selected>2048-bit RSA (recommended)</option>
                    <option value="4096">4096-bit RSA (stronger)</option>
                </select>
            </td></tr>
        </table>
    </div>
    <div class="nt-lookup-row">
        <button class="nt-btn nt-btn-primary" onclick="NT.runCSRGenerator()"><?= nt_icon('build') ?> Generate CSR + Key</button>
    </div>
    <div id="csr_generator-out" class="nt-result"></div>
</div>
</div>

<!-- PEM Certificate Decoder -->
<div id="nt-pem_decoder" class="nt-panel">
<?= nt_toolbar('PEM Certificate Decoder','Paste a PEM-encoded certificate to decode all its fields') ?>
<div class="nt-body">
    <textarea class="nt-ta" id="pem_decoder-in" placeholder="Paste your PEM certificate here:&#10;-----BEGIN CERTIFICATE-----&#10;MIIFazCCA1OgAwIBAgIRAIIQz7DSQONZRGPgu2OCiwAwDQYJKoZIhvcNAQELBQAw...&#10;-----END CERTIFICATE-----" rows="8"></textarea>
    <div class="nt-lookup-row" style="margin-top:8px">
        <button class="nt-btn nt-btn-primary" onclick="NT.runPEMDecoder()"><?= nt_icon('cert') ?> Decode Certificate</button>
    </div>
    <div id="pem_decoder-out" class="nt-result"></div>
</div>
</div>

<!-- ══════════════ EMAIL TOOLS ════════════════════════════════════════════ -->

<!-- Email Header Analyzer -->
<div id="nt-email_headers" class="nt-panel">
<?= nt_toolbar('Email Header Analyzer','Paste raw email headers to parse routing, delays & authentication') ?>
<div class="nt-body">
    <textarea class="nt-ta" id="email_headers-in" placeholder="Paste raw email headers here...&#10;Delivered-To: user@example.com&#10;Received: from mail.example.com...&#10;From: sender@example.com&#10;To: user@example.com&#10;Subject: Hello&#10;DKIM-Signature: v=1; a=rsa-sha256..." rows="8" oninput="NT.runEmailHeaders()"></textarea>
    <div id="email_headers-out" class="nt-result"></div>
</div>
</div>

<!-- SPF Checker -->
<?= nt_api_panel('spf_check','SPF Checker','Look up the SPF TXT record for a domain and parse its directives','example.com','runSPFCheck') ?>

<!-- DKIM Checker -->
<div id="nt-dkim_check" class="nt-panel">
<?= nt_toolbar('DKIM Checker','Look up DKIM public key records by domain and selector') ?>
<div class="nt-body">
    <div class="nt-inline-row">
        <input type="text" id="dkim_check-domain" placeholder="Domain: example.com" style="flex:2" onkeydown="if(event.key==='Enter')NT.runDKIMCheck()">
        <input type="text" id="dkim_check-sel" placeholder="Selector: default" style="flex:1" onkeydown="if(event.key==='Enter')NT.runDKIMCheck()">
        <button class="nt-btn nt-btn-primary" onclick="NT.runDKIMCheck()"><?= nt_icon('search') ?> Lookup</button>
    </div>
    <div id="dkim_check-out" class="nt-result"></div>
</div>
</div>

<!-- DMARC Checker -->
<?= nt_api_panel('dmarc_check','DMARC Checker','Look up DMARC policy at _dmarc.{domain} and parse directives','example.com','runDMARCCheck') ?>

<!-- MX Lookup -->
<?= nt_api_panel('mx_lookup','MX Lookup','Get mail exchange servers with priority and resolved IP addresses','example.com','runMXLookup') ?>

<!-- ══════════════ CONNECTIVITY TOOLS ═════════════════════════════════════ -->

<!-- Availability -->
<?= nt_api_panel('availability','Website Availability Checker','Check if a website is reachable and responding','https://example.com','runAvailability','Check') ?>

<!-- Server Info -->
<?= nt_api_panel('server_info','Server Information','Detect web server, framework, CDN provider and response time','https://example.com','runServerInfo') ?>

<!-- HTTP Method Tester -->
<?= nt_api_panel('http_method','HTTP Method Tester','Test which HTTP methods (GET, POST, PUT, DELETE…) are accepted','https://example.com','runHTTPMethod','Test Methods') ?>

<!-- Robots.txt -->
<?= nt_api_panel('robots','Robots.txt Viewer','Fetch and display the robots.txt file from a domain','example.com or https://example.com','runRobots','Fetch') ?>

<!-- Sitemap -->
<?= nt_api_panel('sitemap','Sitemap Checker','Find and parse the XML sitemap for a domain','example.com or https://example.com','runSitemap','Check') ?>

<!-- Security.txt -->
<?= nt_api_panel('security_txt','Security.txt Checker','Check for a .well-known/security.txt file on a domain','example.com','runSecurityTxt','Check') ?>

<!-- Common Ports Reference -->
<div id="nt-common_ports" class="nt-panel">
<?= nt_toolbar('Common Ports Reference','Well-known TCP/UDP port numbers and their services') ?>
<div class="nt-body">
    <div class="nt-lookup-row">
        <input type="text" id="common_ports-in" placeholder="Filter by port number or service name..." oninput="NT.filterPorts()">
    </div>
    <div id="common_ports-out" class="nt-result"></div>
</div>
</div>

<!-- Port Lookup -->
<?= nt_cs_panel('port_lookup','Port Number Lookup','Look up a specific port number or service name','80, 443, ssh, ftp, mysql, redis...','runPortLookup') ?>

<!-- Open Redirect Check -->
<?= nt_api_panel('open_redirect','Open Redirect Check','Test a URL for open redirect vulnerability — detects cross-domain redirects','https://example.com/redirect?url=https://other.com','runOpenRedirect','Check') ?>

</div><!-- /.nt-workspace-body -->
</div><!-- /.nt-workspace -->

<!-- ── Related Tools ────────────────────────────────────────────────────── -->
<div class="nt-related" id="nt-related" style="display:none">
  <div class="nt-related-head">Related Tools</div>
  <div class="nt-related-grid" id="nt-related-grid"></div>
</div>

</div><!-- /.nt-app -->

<script>
/* ═══════════════════════ NT NAMESPACE ════════════════════════════════════ */
var NT = {};

/* ─── Groups data (PHP-injected) ─────────────────────────────────────────── */
NT._groups = <?php
$gjs = [];
foreach ($groups as $gName => $gTools) {
    $gjs[$gName] = array_map(function($t) {
        return ['id' => $t[0], 'name' => $t[1], 'hint' => $t[2], 'svg' => nt_icon($t[3])];
    }, $gTools);
}
echo json_encode($gjs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
?>;

NT._catOf = {}; // id → category name
(function() {
    Object.keys(NT._groups).forEach(function(cat) {
        NT._groups[cat].forEach(function(t) { NT._catOf[t.id] = cat; });
    });
})();

/* ─── Navigation ─────────────────────────────────────────────────────────── */
NT.showCategory = function(catName) {
    NT._curCat = catName;
    // Activate cat card + pill
    document.querySelectorAll('.nt-cat-card').forEach(function(c) {
        c.classList.toggle('active', c.dataset.cat === catName);
    });
    document.querySelectorAll('.nt-cat-pill').forEach(function(p) {
        p.classList.toggle('active', p.dataset.cat === catName);
    });
    // Scroll active pill into view
    var activePill = document.querySelector('.nt-cat-pill.active');
    if (activePill) activePill.scrollIntoView({block:'nearest',inline:'nearest'});
    // Render tool grid
    var tools = NT._groups[catName] || [];
    var html = tools.map(function(t) {
        return '<button class="nt-tool-card" data-id="' + NT.esc(t.id) + '" onclick="NT.show(\'' + NT.esc(t.id) + '\')">' +
            '<span class="nt-tool-card-icon">' + t.svg + '</span>' +
            '<span class="nt-tool-card-text">' +
                '<span class="nt-tool-card-name">' + NT.esc(t.name) + '</span>' +
                '<span class="nt-tool-card-hint">' + NT.esc(t.hint) + '</span>' +
            '</span></button>';
    }).join('');
    document.getElementById('nt-tool-grid').innerHTML = html;
    // Show tool grid, hide workspace + related
    document.getElementById('nt-tool-grid-wrap').style.display = '';
    document.getElementById('nt-workspace').style.display = 'none';
    document.getElementById('nt-related').style.display = 'none';
    // Scroll to tool grid
    document.getElementById('nt-tool-grid-wrap').scrollIntoView({behavior:'smooth',block:'start'});
};

NT.show = function(id) {
    var cat = NT._catOf[id] || NT._curCat || Object.keys(NT._groups)[0];
    NT._curCat = cat;
    NT._cur = id;
    // Activate cat card + pill
    document.querySelectorAll('.nt-cat-card').forEach(function(c) {
        c.classList.toggle('active', c.dataset.cat === cat);
    });
    document.querySelectorAll('.nt-cat-pill').forEach(function(p) {
        p.classList.toggle('active', p.dataset.cat === cat);
    });
    // Activate panel
    document.querySelectorAll('.nt-panel').forEach(function(p) { p.classList.remove('active'); });
    var panel = document.getElementById('nt-' + id);
    if (panel) panel.classList.add('active');
    // Mark active tool card (if grid already rendered for this cat)
    document.querySelectorAll('.nt-tool-card').forEach(function(c) {
        c.classList.toggle('active', c.dataset.id === id);
    });
    // Update breadcrumb
    var tools  = NT._groups[cat] || [];
    var tool   = tools.find(function(t){ return t.id === id; });
    document.getElementById('nt-bc-cat').textContent  = cat;
    document.getElementById('nt-bc-tool').textContent = tool ? tool.name : id;
    // Show workspace, render tool grid in background for back button
    var tools2 = NT._groups[cat] || [];
    var html = tools2.map(function(t) {
        return '<button class="nt-tool-card' + (t.id === id ? ' active' : '') + '" data-id="' + NT.esc(t.id) + '" onclick="NT.show(\'' + NT.esc(t.id) + '\')">' +
            '<span class="nt-tool-card-icon">' + t.svg + '</span>' +
            '<span class="nt-tool-card-text">' +
                '<span class="nt-tool-card-name">' + NT.esc(t.name) + '</span>' +
                '<span class="nt-tool-card-hint">' + NT.esc(t.hint) + '</span>' +
            '</span></button>';
    }).join('');
    document.getElementById('nt-tool-grid').innerHTML = html;
    document.getElementById('nt-tool-grid-wrap').style.display = 'none';
    document.getElementById('nt-workspace').style.display = '';
    // Render related tools
    NT._renderRelated(id, cat);
    // Scroll to workspace
    document.getElementById('nt-workspace').scrollIntoView({behavior:'smooth',block:'start'});
};

NT.backToGrid = function() {
    document.getElementById('nt-workspace').style.display = 'none';
    document.getElementById('nt-related').style.display = 'none';
    document.getElementById('nt-tool-grid-wrap').style.display = '';
    document.getElementById('nt-tool-grid-wrap').scrollIntoView({behavior:'smooth',block:'start'});
};

NT._renderRelated = function(id, cat) {
    var tools   = NT._groups[cat] || [];
    var related = tools.filter(function(t){ return t.id !== id; }).slice(0, 4);
    if (!related.length) { document.getElementById('nt-related').style.display = 'none'; return; }
    var html = related.map(function(t) {
        return '<button class="nt-tool-card" data-id="' + NT.esc(t.id) + '" onclick="NT.show(\'' + NT.esc(t.id) + '\')">' +
            '<span class="nt-tool-card-icon">' + t.svg + '</span>' +
            '<span class="nt-tool-card-text">' +
                '<span class="nt-tool-card-name">' + NT.esc(t.name) + '</span>' +
                '<span class="nt-tool-card-hint">' + NT.esc(t.hint) + '</span>' +
            '</span></button>';
    }).join('');
    document.getElementById('nt-related-grid').innerHTML = html;
    document.getElementById('nt-related').style.display = '';
};

NT.searchTools = function(q) {
    var drop = document.getElementById('nt-search-drop');
    if (!q || q.length < 2) { drop.className = 'nt-search-drop'; return; }
    q = q.toLowerCase();
    var results = [];
    Object.keys(NT._groups).forEach(function(cat) {
        NT._groups[cat].forEach(function(t) {
            if (t.name.toLowerCase().indexOf(q) !== -1 || t.hint.toLowerCase().indexOf(q) !== -1 || cat.toLowerCase().indexOf(q) !== -1) {
                results.push({id: t.id, name: t.name, hint: t.hint, cat: cat, svg: t.svg});
            }
        });
    });
    if (!results.length) {
        drop.innerHTML = '<div class="nt-search-no-results">No tools found for "' + NT.esc(q) + '"</div>';
    } else {
        drop.innerHTML = results.slice(0,8).map(function(r) {
            return '<div class="nt-search-result" onclick="NT.show(\'' + NT.esc(r.id) + '\');document.getElementById(\'nt-search\').value=\'\';NT.closeSearch()">' +
                '<span class="nt-search-result-icon">' + r.svg + '</span>' +
                '<span><div class="nt-search-result-name">' + NT.esc(r.name) + '</div>' +
                '<div class="nt-search-result-cat">' + NT.esc(r.cat) + '</div></span>' +
                '</div>';
        }).join('');
    }
    drop.className = 'nt-search-drop open';
};

NT.closeSearch = function() {
    document.getElementById('nt-search-drop').className = 'nt-search-drop';
};

NT.clearCurrent = function() {
    if (!NT._cur) return;
    var p = document.getElementById('nt-' + NT._cur);
    if (!p) return;
    p.querySelectorAll('input, textarea').forEach(function(el){ el.value = ''; });
    p.querySelectorAll('.nt-result').forEach(function(el){ el.innerHTML = ''; });
    p.querySelectorAll('.nt-output-val').forEach(function(el){ el.textContent = ''; el.className = 'nt-output-val placeholder'; });
    if (NT._cur === 'http_status') NT.renderHTTPStatus();
    if (NT._cur === 'common_ports') NT.renderPorts();
    if (NT._cur === 'mime') NT.renderMIMETable();
};

/* ─── Core helpers ───────────────────────────────────────────────────────── */
NT.esc = function(s) {
    return String(s == null ? '' : s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
};

NT.cp = function(text, btn) {
    if (!text) return;
    navigator.clipboard.writeText(text).then(function() {
        if (!btn) return;
        var old = btn.innerHTML;
        btn.classList.add('copied'); btn.textContent = '✓ Copied';
        setTimeout(function(){ btn.classList.remove('copied'); btn.innerHTML = old; }, 2000);
    }).catch(function(){});
};

NT.setHTML = function(id, html) {
    var el = document.getElementById(id);
    if (el) el.innerHTML = html;
};

NT.loading = function(id) {
    NT.setHTML(id, '<div class="nt-loading"><div class="nt-spinner"></div>Fetching…</div>');
};

NT.err = function(id, msg) {
    NT.setHTML(id, '<div class="nt-error"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>' + NT.esc(msg) + '</div>');
};

NT.badge = function(text, type) {
    return '<span class="nt-badge nt-badge-' + type + '"><span class="nt-dot"></span>' + NT.esc(String(text)) + '</span>';
};

NT.kv = function(rows, title) {
    var h = '<div class="nt-kv">';
    if (title) h += '<div class="nt-kv-head">' + NT.esc(title) + '</div>';
    h += '<table>';
    rows.forEach(function(r) {
        var val = (r.length > 2 && r[2]) ? r[1] : NT.esc(String(r[1] == null ? '—' : r[1]));
        h += '<tr><td>' + NT.esc(r[0]) + '</td><td>' + val + '</td></tr>';
    });
    h += '</table></div>';
    return h;
};

NT.pre = function(text, maxLen) {
    var t = String(text || '');
    if (maxLen && t.length > maxLen) t = t.slice(0, maxLen) + '\n… (truncated)';
    return '<div class="nt-pre">' + NT.esc(t) + '</div>';
};

NT.api = async function(action, q, extra) {
    var url = '/plugins/network-toolkit/api?action=' + encodeURIComponent(action) + '&q=' + encodeURIComponent(q || '');
    if (extra) url += '&extra=' + encodeURIComponent(extra);
    var r = await fetch(url);
    if (!r.ok) throw new Error('HTTP ' + r.status + ' from API');
    var data = await r.json();
    if (data && data.error) throw new Error(data.error);
    return data;
};

/* ─── IP Math Helpers ────────────────────────────────────────────────────── */
NT.ip4ToInt = function(ip) {
    var p = (ip || '').trim().split('.');
    if (p.length !== 4) return NaN;
    var n = 0;
    for (var i = 0; i < 4; i++) {
        var b = parseInt(p[i], 10);
        if (isNaN(b) || b < 0 || b > 255 || p[i].trim() === '') return NaN;
        n = (n * 256 + b) | 0;
    }
    return n >>> 0;
};
NT.intToIP4 = function(n) {
    n = n >>> 0;
    return [(n >>> 24), (n >>> 16 & 255), (n >>> 8 & 255), (n & 255)].join('.');
};
NT.cidrMask = function(p) {
    if (p < 0 || p > 32) return NaN;
    return p === 0 ? 0 : ((~0 << (32 - p)) >>> 0);
};
NT.maskToCIDR = function(mask) {
    var m = mask >>> 0, n = 0;
    while (n < 32 && (m & (0x80000000 >>> n))) n++;
    return n;
};
NT.isValidIPv4 = function(ip) {
    return /^((25[0-5]|2[0-4]\d|1?\d{1,2})\.){3}(25[0-5]|2[0-4]\d|1?\d{1,2})$/.test((ip||'').trim());
};
NT.isValidIPv6 = function(ip) {
    var s = (ip||'').trim();
    // Basic IPv6 regex — covers most forms including :: compressed
    return /^(([0-9a-fA-F]{1,4}:){7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]+|::(ffff(:0{1,4})?:)?((25[0-5]|(2[0-4]|1?\d)?\d)\.){3}(25[0-5]|(2[0-4]|1?\d)?\d)|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1?\d)?\d)\.){3}(25[0-5]|(2[0-4]|1?\d)?\d))$/.test(s);
};
NT.ipClass = function(n) {
    n = n >>> 0;
    if ((n & 0x80000000) === 0) return 'A';
    if ((n & 0xC0000000) === 0x80000000) return 'B';
    if ((n & 0xE0000000) === 0xC0000000) return 'C';
    if ((n & 0xF0000000) === 0xE0000000) return 'D (Multicast)';
    return 'E (Reserved/Experimental)';
};
NT._privateRanges = [
    {n:0x0A000000,m:0xFF000000,label:'Private (10.0.0.0/8)',     tag:'priv'},
    {n:0xAC100000,m:0xFFF00000,label:'Private (172.16.0.0/12)', tag:'priv'},
    {n:0xC0A80000,m:0xFFFF0000,label:'Private (192.168.0.0/16)',tag:'priv'},
    {n:0x7F000000,m:0xFF000000,label:'Loopback (127.0.0.0/8)',  tag:'loop'},
    {n:0xA9FE0000,m:0xFFFF0000,label:'Link-local (169.254.0.0/16)',tag:'rsv'},
    {n:0xE0000000,m:0xF0000000,label:'Multicast (224.0.0.0/4)', tag:'rsv'},
    {n:0xF0000000,m:0xF0000000,label:'Reserved (240.0.0.0/4)',  tag:'rsv'},
    {n:0x64400000,m:0xFFC00000,label:'Shared (100.64.0.0/10)',  tag:'rsv'},
    {n:0xC0000000,m:0xFFFFFF00,label:'IETF Protocol (192.0.0.0/24)',tag:'rsv'},
    {n:0xC0000200,m:0xFFFFFF00,label:'TEST-NET-1 (192.0.2.0/24)',tag:'rsv'},
    {n:0xC6336400,m:0xFFFFFF00,label:'TEST-NET-2 (198.51.100.0/24)',tag:'rsv'},
    {n:0xCB007100,m:0xFFFFFF00,label:'TEST-NET-3 (203.0.113.0/24)',tag:'rsv'},
];
NT.getIPMeta = function(ip) {
    var n = NT.ip4ToInt(ip);
    if (isNaN(n)) return null;
    var matched = NT._privateRanges.filter(function(r){ return (n & r.m) >>> 0 === r.n >>> 0; });
    var priv = matched.some(function(r){ return r.tag === 'priv'; });
    var loop = matched.some(function(r){ return r.tag === 'loop'; });
    return {n:n, class:NT.ipClass(n), private:priv, loopback:loop, reserved:matched.length>0, public:!matched.length, ranges:matched};
};

/* ─── Subnet card HTML ───────────────────────────────────────────────────── */
NT.subnetCards = function(cards) {
    return '<div class="nt-subnet-grid">' + cards.map(function(c){
        return '<div class="nt-subnet-card"><div class="nt-subnet-card-label">' + NT.esc(c[0]) + '</div><div class="nt-subnet-card-val">' + NT.esc(c[1]) + '</div></div>';
    }).join('') + '</div>';
};

/* ══════════════ IP TOOL HANDLERS ════════════════════════════════════════ */
NT.runMyIP = async function() {
    NT.loading('myip-out');
    try {
        var d = await NT.api('my_ip', '');
        NT.setHTML('myip-out', NT.kv([
            ['Your Public IP', '<strong style="font-size:20px;font-family:monospace">' + NT.esc(d.ip) + '</strong>', true],
        ], 'Public IP Address'));
    } catch(e) { NT.err('myip-out', e.message); }
};

NT.runIPGeo = async function() {
    var q = document.getElementById('ipgeo-in').value.trim();
    if (!q) return;
    NT.loading('ipgeo-out');
    try {
        var d = await NT.api('ip_geo', q);
        NT.setHTML('ipgeo-out', NT.kv([
            ['Query IP',   d.query],
            ['Country',    (d.country || '—') + (d.countryCode ? ' (' + d.countryCode + ')' : '')],
            ['Region',     d.regionName || '—'],
            ['City',       (d.city || '—') + (d.zip ? ', ' + d.zip : '')],
            ['Coordinates',d.lat && d.lon ? d.lat + ', ' + d.lon : '—'],
            ['Timezone',   d.timezone || '—'],
            ['ISP',        d.isp || '—'],
            ['Organisation',d.org || '—'],
            ['AS',         d.as || '—'],
            ['AS Name',    d.asname || '—'],
            ['Mobile',     d.mobile ? 'Yes' : 'No'],
            ['Proxy/VPN',  d.proxy ? 'Yes' : 'No'],
            ['Hosting',    d.hosting ? 'Yes' : 'No'],
        ], 'IP Geolocation — ' + d.query));
    } catch(e) { NT.err('ipgeo-out', e.message); }
};

NT.runASN = async function() {
    var q = document.getElementById('asnlookup-in').value.trim();
    if (!q) return;
    NT.loading('asnlookup-out');
    try {
        var d = await NT.api('ip_geo', q);
        NT.setHTML('asnlookup-out', NT.kv([
            ['Query IP',    d.query],
            ['AS Number',   d.as || '—'],
            ['AS Name',     d.asname || '—'],
            ['ISP',         d.isp || '—'],
            ['Organisation',d.org || '—'],
            ['Country',     (d.country || '—') + (d.countryCode ? ' (' + d.countryCode + ')' : '')],
            ['Hosting',     d.hosting ? 'Yes' : 'No'],
            ['Proxy/VPN',   d.proxy ? 'Yes' : 'No'],
        ], 'ASN Information — ' + d.query));
    } catch(e) { NT.err('asnlookup-out', e.message); }
};

NT.runIPv4Val = function() {
    var ip = document.getElementById('ipv4val-in').value.trim();
    if (!ip) { NT.setHTML('ipv4val-out',''); return; }
    var valid = NT.isValidIPv4(ip);
    var h = NT.badge(valid ? 'Valid IPv4' : 'Invalid IPv4', valid ? 'ok' : 'error') + ' ';
    if (valid) {
        var m = NT.getIPMeta(ip);
        h += NT.badge('Class ' + m.class, 'info') + ' ';
        if (m.private)  h += NT.badge('Private', 'warn') + ' ';
        if (m.loopback) h += NT.badge('Loopback', 'warn') + ' ';
        if (m.reserved && !m.private && !m.loopback) h += NT.badge('Reserved', 'warn') + ' ';
        if (m.public)   h += NT.badge('Public', 'ok') + ' ';
        h += '<br><br>' + NT.kv([
            ['Input',      ip],
            ['Valid',      'Yes'],
            ['Class',      m.class],
            ['Type',       m.private ? 'Private' : m.loopback ? 'Loopback' : m.reserved ? 'Reserved/Special' : 'Public'],
            ['Integer',    m.n + ' (0x' + m.n.toString(16).padStart(8,'0').toUpperCase() + ')'],
            ['Binary',     m.n.toString(2).padStart(32,'0').replace(/(.{8})/g,'$1 ').trim()],
        ]);
        if (m.ranges.length) h += '<div style="margin-top:8px;font-size:12px;color:var(--color-text-muted)">RFC ranges: ' + m.ranges.map(function(r){ return NT.esc(r.label); }).join(', ') + '</div>';
    } else {
        h += '<br><br>' + NT.kv([['Input', ip],['Valid','No'],['Reason','Does not match x.x.x.x where each octet is 0–255']]);
    }
    NT.setHTML('ipv4val-out', h);
};

NT.runIPv6Val = function() {
    var ip = document.getElementById('ipv6val-in').value.trim();
    if (!ip) { NT.setHTML('ipv6val-out',''); return; }
    var valid = NT.isValidIPv6(ip);
    var h = NT.badge(valid ? 'Valid IPv6' : 'Invalid IPv6', valid ? 'ok' : 'error');
    h += '<br><br>' + NT.kv([
        ['Input', ip],
        ['Valid', valid ? 'Yes' : 'No'],
        ['Length', ip.replace(/[^0-9a-fA-F]/g,'').length + ' hex digits (of up to 32)'],
    ]);
    if (valid) {
        h += '<br>' + NT.kv([
            ['Compressed form', ip],
            ['Has ::', ip.includes('::') ? 'Yes (compressed zeros)' : 'No'],
        ]);
    }
    NT.setHTML('ipv6val-out', h);
};

NT.runIP4toInt = function() {
    var ip = document.getElementById('ip4int-ip').value.trim();
    if (!ip) { NT.setHTML('ip4int-out1',''); return; }
    if (!NT.isValidIPv4(ip)) { NT.setHTML('ip4int-out1', NT.badge('Invalid IPv4','error')); return; }
    var n = NT.ip4ToInt(ip);
    NT.setHTML('ip4int-out1', NT.kv([
        ['IPv4',     ip],
        ['Decimal',  n],
        ['Hex',      '0x' + n.toString(16).padStart(8,'0').toUpperCase()],
        ['Octal',    '0' + n.toString(8)],
        ['Binary',   n.toString(2).padStart(32,'0').replace(/(.{8})/g,'$1 ').trim()],
    ]));
};

NT.runIntToIP4 = function() {
    var s = document.getElementById('ip4int-int').value.trim();
    if (!s) { NT.setHTML('ip4int-out2',''); return; }
    var n;
    if (/^0x/i.test(s)) n = parseInt(s, 16);
    else if (/^0[0-7]+$/.test(s)) n = parseInt(s, 8);
    else n = parseInt(s, 10);
    if (isNaN(n) || n < 0 || n > 4294967295) { NT.setHTML('ip4int-out2', NT.badge('Invalid integer (must be 0–4294967295)','error')); return; }
    var ip = NT.intToIP4(n);
    NT.setHTML('ip4int-out2', NT.kv([
        ['Input Integer', n + ' (0x' + (n>>>0).toString(16).padStart(8,'0').toUpperCase() + ')'],
        ['IPv4 Address',  ip],
        ['Binary',        (n>>>0).toString(2).padStart(32,'0').replace(/(.{8})/g,'$1 ').trim()],
    ]));
};

NT.runIPType = function() {
    var ip = document.getElementById('iptype-in').value.trim();
    if (!ip) { NT.setHTML('iptype-out',''); return; }
    if (!NT.isValidIPv4(ip)) { NT.setHTML('iptype-out', NT.badge('Invalid IPv4 address','error')); return; }
    var m = NT.getIPMeta(ip);
    var tags = '<div class="nt-ip-type-tags">';
    tags += '<span class="nt-ip-tag nt-ip-tag-class">Class ' + m.class + '</span>';
    if (m.private)  tags += '<span class="nt-ip-tag nt-ip-tag-priv">Private</span>';
    if (m.public)   tags += '<span class="nt-ip-tag nt-ip-tag-pub">Public</span>';
    if (m.loopback) tags += '<span class="nt-ip-tag nt-ip-tag-loop">Loopback</span>';
    if (m.reserved && !m.private && !m.loopback) tags += '<span class="nt-ip-tag nt-ip-tag-rsv">Reserved / Special Use</span>';
    tags += '</div>';
    var h = '<div class="nt-ip-type-result"><div class="nt-ip-type-main">' + NT.esc(ip) + '</div>' + tags + '</div><br>';
    h += NT.kv([
        ['Class',        m.class],
        ['Private',      m.private ? 'Yes (RFC 1918)' : 'No'],
        ['Public',       m.public ? 'Yes' : 'No'],
        ['Loopback',     m.loopback ? 'Yes' : 'No'],
        ['Reserved',     m.reserved ? 'Yes' : 'No'],
        ['Integer',      m.n],
    ]);
    if (m.ranges.length) {
        h += '<br><div class="nt-section-head" style="margin-top:0">RFC / IANA Assignments</div><br>';
        h += NT.kv(m.ranges.map(function(r){ return [r.tag, r.label]; }));
    }
    NT.setHTML('iptype-out', h);
};

NT.runCIDR = function() {
    var s = document.getElementById('cidr-in').value.trim();
    if (!s) { NT.setHTML('cidr-out',''); return; }
    var parts = s.split('/');
    if (parts.length !== 2) { NT.setHTML('cidr-out', NT.badge('Enter in CIDR format: 192.168.1.0/24','error')); return; }
    var ip = parts[0].trim(), pfx = parseInt(parts[1].trim(), 10);
    if (!NT.isValidIPv4(ip) || isNaN(pfx) || pfx < 0 || pfx > 32) { NT.setHTML('cidr-out', NT.badge('Invalid CIDR notation','error')); return; }
    var n       = NT.ip4ToInt(ip);
    var mask    = NT.cidrMask(pfx);
    var netAddr = (n & mask) >>> 0;
    var bcast   = (netAddr | (~mask >>> 0)) >>> 0;
    var first   = pfx < 31 ? (netAddr + 1) >>> 0 : netAddr;
    var last    = pfx < 31 ? (bcast - 1) >>> 0 : bcast;
    var hosts   = pfx >= 31 ? Math.pow(2, 32-pfx) : Math.max(0, bcast - netAddr - 1);
    NT.setHTML('cidr-out', NT.subnetCards([
        ['Network Address',   NT.intToIP4(netAddr)],
        ['Subnet Mask',       NT.intToIP4(mask)],
        ['Wildcard Mask',     NT.intToIP4(~mask >>> 0)],
        ['Broadcast Address', NT.intToIP4(bcast)],
        ['First Usable Host', NT.intToIP4(first)],
        ['Last Usable Host',  NT.intToIP4(last)],
        ['Usable Hosts',      hosts.toLocaleString()],
        ['Total Addresses',   Math.pow(2, 32-pfx).toLocaleString()],
        ['CIDR',              ip + '/' + pfx],
        ['IP Range',          NT.intToIP4(netAddr) + ' — ' + NT.intToIP4(bcast)],
        ['Prefix Length',     '/' + pfx + ' (' + pfx + ' bits)'],
        ['Host Bits',         (32-pfx) + ' bits'],
    ]));
};

NT.runSubnet = function() {
    var ip = document.getElementById('subnet-ip').value.trim();
    var pfx = parseInt(document.getElementById('subnet-pfx').value.trim(), 10);
    if (!ip || isNaN(pfx)) { NT.setHTML('subnet-out',''); return; }
    if (!NT.isValidIPv4(ip) || pfx < 0 || pfx > 32) { NT.setHTML('subnet-out', NT.badge('Invalid IP or prefix','error')); return; }
    var n    = NT.ip4ToInt(ip);
    var mask = NT.cidrMask(pfx);
    var net  = (n & mask) >>> 0;
    var bc   = (net | (~mask >>> 0)) >>> 0;
    var hosts = pfx >= 31 ? Math.pow(2,32-pfx) : Math.max(0, bc - net - 1);
    NT.setHTML('subnet-out', NT.subnetCards([
        ['Host IP',           NT.intToIP4(n)],
        ['Network Address',   NT.intToIP4(net)],
        ['Subnet Mask',       NT.intToIP4(mask)],
        ['Wildcard Mask',     NT.intToIP4(~mask>>>0)],
        ['Broadcast Address', NT.intToIP4(bc)],
        ['First Usable',      NT.intToIP4(pfx<31?(net+1)>>>0:net)],
        ['Last Usable',       NT.intToIP4(pfx<31?(bc-1)>>>0:bc)],
        ['Usable Hosts',      hosts.toLocaleString()],
        ['Total Addresses',   Math.pow(2,32-pfx).toLocaleString()],
        ['CIDR',              NT.intToIP4(net) + '/' + pfx],
        ['IP Class',          NT.ipClass(n)],
        ['Prefix Length',     '/' + pfx],
    ]));
};

NT.runIPRange = function() {
    var s = document.getElementById('iprange-start').value.trim();
    var e = document.getElementById('iprange-end').value.trim();
    if (!s || !e) { NT.setHTML('iprange-out',''); return; }
    if (!NT.isValidIPv4(s) || !NT.isValidIPv4(e)) { NT.setHTML('iprange-out', NT.badge('Invalid IP address','error')); return; }
    var sn = NT.ip4ToInt(s), en = NT.ip4ToInt(e);
    if (sn > en) { NT.setHTML('iprange-out', NT.badge('Start IP must be ≤ End IP','error')); return; }
    var count = (en - sn + 1) >>> 0;
    NT.setHTML('iprange-out', NT.kv([
        ['Start IP',   s],
        ['End IP',     e],
        ['Total IPs',  count.toLocaleString()],
        ['Usable Hosts', Math.max(0,count-2).toLocaleString() + ' (excluding network + broadcast)'],
        ['Int Range',  sn + ' – ' + en],
    ]));
};

NT.runCIDR2Range = function() {
    var s = document.getElementById('cidr2range-in').value.trim();
    if (!s) { NT.setHTML('cidr2range-out',''); return; }
    var parts = s.split('/');
    if (parts.length !== 2) { NT.setHTML('cidr2range-out', NT.badge('Enter CIDR notation: 10.0.0.0/22','error')); return; }
    var ip = parts[0].trim(), pfx = parseInt(parts[1].trim(), 10);
    if (!NT.isValidIPv4(ip) || isNaN(pfx) || pfx < 0 || pfx > 32) { NT.setHTML('cidr2range-out', NT.badge('Invalid CIDR','error')); return; }
    var n    = NT.ip4ToInt(ip);
    var mask = NT.cidrMask(pfx);
    var net  = (n & mask) >>> 0;
    var bc   = (net | (~mask>>>0)) >>> 0;
    var total = Math.pow(2, 32-pfx);
    NT.setHTML('cidr2range-out', NT.kv([
        ['CIDR Block',        s],
        ['First IP',          NT.intToIP4(net)],
        ['Last IP',           NT.intToIP4(bc)],
        ['First Usable',      pfx<31 ? NT.intToIP4((net+1)>>>0) : NT.intToIP4(net)],
        ['Last Usable',       pfx<31 ? NT.intToIP4((bc-1)>>>0) : NT.intToIP4(bc)],
        ['Total Addresses',   total.toLocaleString()],
        ['Usable Hosts',      pfx<31 ? Math.max(0,total-2).toLocaleString() : total.toLocaleString()],
        ['Subnet Mask',       NT.intToIP4(mask)],
        ['Wildcard Mask',     NT.intToIP4(~mask>>>0)],
    ]));
};

NT.runRange2CIDR = function() {
    var s = document.getElementById('range2cidr-start').value.trim();
    var e = document.getElementById('range2cidr-end').value.trim();
    if (!s || !e) { NT.setHTML('range2cidr-out',''); return; }
    if (!NT.isValidIPv4(s) || !NT.isValidIPv4(e)) { NT.setHTML('range2cidr-out', NT.badge('Invalid IP address','error')); return; }
    var sn = NT.ip4ToInt(s), en = NT.ip4ToInt(e);
    if (sn > en) { NT.setHTML('range2cidr-out', NT.badge('Start must be ≤ End','error')); return; }
    // Compute minimum CIDR covering
    var cidrs = [], cur = sn;
    while (cur <= en) {
        var maxPfx = 32;
        while (maxPfx > 0) {
            var tryMask = NT.cidrMask(maxPfx - 1);
            var tryBase = (cur & tryMask) >>> 0;
            if (tryBase !== cur) break;
            var tryBC   = (cur | (~tryMask>>>0)) >>> 0;
            if (tryBC > en) break;
            maxPfx--;
        }
        cidrs.push(NT.intToIP4(cur) + '/' + maxPfx);
        var blockSz = Math.pow(2, 32 - maxPfx);
        cur = (cur + blockSz) >>> 0;
        if (cur === 0 || cidrs.length > 64) break;
    }
    var h = NT.kv([['Start IP',s],['End IP',e],['Total IPs',(en-sn+1).toLocaleString()],['CIDR Blocks',cidrs.length]]);
    h += '<br><div class="nt-section-head">CIDR Blocks (' + cidrs.length + ')</div><br>';
    h += '<div class="nt-record-list">' + cidrs.map(function(c){ return '<div class="nt-record-item">' + NT.esc(c) + '</div>'; }).join('') + '</div>';
    NT.setHTML('range2cidr-out', h);
};

NT.runNetCalc = function() {
    var s = document.getElementById('netcalc-in').value.trim();
    if (!s) { NT.setHTML('netcalc-out',''); return; }
    var parts = s.split('/');
    if (parts.length !== 2) { NT.setHTML('netcalc-out', NT.badge('Enter CIDR: 172.16.10.5/20','error')); return; }
    var ip = parts[0].trim(), pfx = parseInt(parts[1].trim(), 10);
    if (!NT.isValidIPv4(ip) || isNaN(pfx) || pfx < 0 || pfx > 32) { NT.setHTML('netcalc-out', NT.badge('Invalid CIDR','error')); return; }
    var n    = NT.ip4ToInt(ip);
    var mask = NT.cidrMask(pfx);
    var net  = (n & mask) >>> 0;
    var bc   = (net | (~mask>>>0)) >>> 0;
    NT.setHTML('netcalc-out', NT.kv([
        ['Network Address',   NT.intToIP4(net)],
        ['Broadcast Address', NT.intToIP4(bc)],
        ['Subnet Mask',       NT.intToIP4(mask)],
        ['Wildcard Mask',     NT.intToIP4(~mask>>>0)],
        ['Prefix Length',     '/' + pfx],
        ['Host IP',           NT.intToIP4(n)],
    ]));
};

/* ══════════════ DNS TOOL HANDLERS ══════════════════════════════════════ */
NT._renderDNSRecords = function(records, type) {
    if (!records || !records.length) return '<div class="nt-empty"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>No ' + type + ' records found</div>';
    return '<div class="nt-record-list">' + records.map(function(r) {
        var lines = [];
        var t = r.type || type;
        if (r.ip)       lines.push('<strong>Address:</strong> ' + NT.esc(r.ip));
        if (r.ipv6)     lines.push('<strong>Address:</strong> ' + NT.esc(r.ipv6));
        if (r.target)   lines.push('<strong>Target:</strong> '  + NT.esc(r.target));
        if (r.exchange) lines.push('<strong>Exchange:</strong> ' + NT.esc(r.exchange));
        if (r.pri != null)    lines.push('<strong>Priority:</strong> ' + NT.esc(r.pri));
        if (r.txt)      lines.push('<strong>TXT:</strong> ' + NT.esc(r.txt));
        if (r.entries)  lines.push('<strong>TXT:</strong> ' + NT.esc(Array.isArray(r.entries) ? r.entries.join('') : r.entries));
        if (r.mname)    lines.push('<strong>Primary NS:</strong> ' + NT.esc(r.mname));
        if (r.rname)    lines.push('<strong>Responsible:</strong> ' + NT.esc(r.rname));
        if (r.serial)   lines.push('<strong>Serial:</strong> '     + NT.esc(r.serial));
        if (r.refresh)  lines.push('<strong>Refresh:</strong> '    + NT.esc(r.refresh) + 's');
        if (r.retry)    lines.push('<strong>Retry:</strong> '      + NT.esc(r.retry) + 's');
        if (r.expire)   lines.push('<strong>Expire:</strong> '     + NT.esc(r.expire) + 's');
        if (r.minimum_ttl) lines.push('<strong>Min TTL:</strong> ' + NT.esc(r.minimum_ttl) + 's');
        if (r.port)     lines.push('<strong>Port:</strong> '       + NT.esc(r.port));
        if (r.weight)   lines.push('<strong>Weight:</strong> '     + NT.esc(r.weight));
        if (r.nsdname)  lines.push('<strong>NS:</strong> '         + NT.esc(r.nsdname));
        if (r.ttl != null) lines.push('<strong>TTL:</strong> '     + NT.esc(r.ttl) + 's');
        if (!lines.length) lines.push(NT.esc(JSON.stringify(r)));
        return '<div class="nt-record-item"><span class="nt-record-type">' + NT.esc(t) + '</span>' + lines.join(' &nbsp;·&nbsp; ') + '</div>';
    }).join('') + '</div>';
};

NT.callDNS = async function(id, type) {
    var domain = document.getElementById(id + '-in').value.trim();
    if (!domain) return;
    NT.loading(id + '-out');
    try {
        var d = await NT.api('dns', domain, type);
        if (d.error) throw new Error(d.error);
        var h = NT.kv([['Domain',d.domain],['Type',d.type],['Records Found',d.count]]) + '<br>' + NT._renderDNSRecords(d.records, type);
        NT.setHTML(id + '-out', h);
    } catch(e) { NT.err(id + '-out', e.message); }
};

NT.runDNS = async function() {
    var domain = document.getElementById('dns-in').value.trim();
    var type   = document.getElementById('dns-type').value;
    if (!domain) return;
    NT.loading('dns-out');
    try {
        var d = await NT.api('dns', domain, type);
        if (d.error) throw new Error(d.error);
        var h = NT.kv([['Domain',d.domain],['Type',d.type],['Records',d.count]]) + '<br>' + NT._renderDNSRecords(d.records, type);
        NT.setHTML('dns-out', h);
    } catch(e) { NT.err('dns-out', e.message); }
};

NT.runSPFDNS = async function() {
    var domain = document.getElementById('dns_spf-in').value.trim();
    if (!domain) return;
    NT.loading('dns_spf-out');
    try {
        var d = await NT.api('spf', domain);
        var h = NT.kv([
            ['Domain', d.domain],
            ['SPF Found', d.found ? NT.badge('Yes','ok') : NT.badge('No','error'), true],
            ['SPF Record', d.spf || '—'],
        ]);
        if (d.spf) {
            var parts = d.spf.split(/\s+/);
            h += '<br><div class="nt-record-list">' + parts.map(function(p){
                return '<div class="nt-record-item"><span class="nt-record-type">SPF</span>' + NT.esc(p) + '</div>';
            }).join('') + '</div>';
        }
        NT.setHTML('dns_spf-out', h);
    } catch(e) { NT.err('dns_spf-out', e.message); }
};

NT.runDMARCDNS = async function() {
    var domain = document.getElementById('dns_dmarc-in').value.trim();
    if (!domain) return;
    NT.loading('dns_dmarc-out');
    try {
        var d = await NT.api('dmarc', domain);
        var h = NT.kv([
            ['Domain',        d.domain],
            ['DMARC Domain',  d.dmarc_domain],
            ['DMARC Found',   d.found ? NT.badge('Yes','ok') : NT.badge('No','error'), true],
            ['DMARC Record',  d.dmarc || '—'],
        ]);
        if (d.dmarc) {
            var tags = d.dmarc.split(';').map(function(t){ return t.trim(); }).filter(Boolean);
            h += '<br><div class="nt-record-list">' + tags.map(function(t){
                return '<div class="nt-record-item"><span class="nt-record-type">DMARC</span>' + NT.esc(t) + '</div>';
            }).join('') + '</div>';
        }
        NT.setHTML('dns_dmarc-out', h);
    } catch(e) { NT.err('dns_dmarc-out', e.message); }
};

NT.runRDNS = async function() {
    var q = document.getElementById('rdns-in').value.trim();
    if (!q) return;
    NT.loading('rdns-out');
    try {
        var d = await NT.api('rdns', q);
        var h = NT.kv([
            ['IP Address', d.ip],
            ['Hostname',   d.resolved ? NT.badge(d.host, 'ok') : NT.badge('No PTR record found', 'warn'), true],
            ['Resolved',   d.resolved ? 'Yes' : 'No'],
        ]);
        NT.setHTML('rdns-out', h);
    } catch(e) { NT.err('rdns-out', e.message); }
};

NT.runDNSZone = async function() {
    var domain = document.getElementById('dns_zone-in').value.trim();
    if (!domain) return;
    NT.loading('dns_zone-out');
    try {
        var d = await NT.api('dns_zone', domain);
        if (d.error) throw new Error(d.error);
        var h = '<div style="margin-bottom:12px">' + NT.kv([['Domain',d.domain]]) + '</div>';
        var types = Object.keys(d.zone);
        if (!types.length) { h += NT.badge('No records found','warn'); NT.setHTML('dns_zone-out',h); return; }
        types.forEach(function(t) {
            h += '<div class="nt-section-head" style="margin:12px 0 8px">' + NT.esc(t) + ' Records (' + d.zone[t].length + ')</div>';
            h += NT._renderDNSRecords(d.zone[t], t);
        });
        NT.setHTML('dns_zone-out', h);
    } catch(e) { NT.err('dns_zone-out', e.message); }
};

/* ══════════════ DOMAIN TOOL HANDLERS ═══════════════════════════════════ */
NT._parseDomain = function(input) {
    var s = input.trim();
    var protocol = '';
    var m = s.match(/^(https?:\/\/)/i);
    if (m) { protocol = m[1]; s = s.slice(m[1].length); }
    var port = '';
    var portM = s.match(/:(\d+)(\/|$)/);
    if (portM) { port = portM[1]; }
    var pathQ = s.indexOf('/');
    var host = pathQ > -1 ? s.slice(0, pathQ) : s.split('?')[0];
    host = host.split(':')[0];
    var parts = host.split('.');
    var tld = parts.length > 1 ? parts.slice(-1)[0] : '';
    var tld2 = parts.length > 2 && parts[parts.length-2].length <= 3 ? parts.slice(-2).join('.') : tld;
    var subdomain = parts.length > (tld2.includes('.') ? 3 : 2) ? parts.slice(0, parts.length - (tld2.includes('.')?3:2)).join('.') : '';
    var sld = parts.length > 1 ? parts[parts.length - (tld2.includes('.')?3:2)] || '' : parts[0];
    return {protocol:protocol||'(none)', host:host, subdomain:subdomain||'(none)', sld:sld, tld:tld2, port:port||'(default)', path:s.replace(host,'').split('?')[0]||'/'};
};

NT.runDomainParser = function() {
    var v = document.getElementById('domain_parser-in').value.trim();
    if (!v) { NT.setHTML('domain_parser-out',''); return; }
    var p = NT._parseDomain(v);
    NT.setHTML('domain_parser-out', NT.kv([
        ['Input',     v],
        ['Protocol',  p.protocol],
        ['Host',      p.host],
        ['Subdomain', p.subdomain],
        ['SLD (Domain)', p.sld],
        ['TLD',       p.tld],
        ['Port',      p.port],
        ['Path',      p.path],
    ]));
};

NT.runDomainExtractor = function() {
    var text = document.getElementById('domain_extractor-in').value;
    if (!text) { NT.setHTML('domain_extractor-out',''); return; }
    var re = /(?:https?:\/\/)?(?:www\.)?([a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*\.[a-zA-Z]{2,})/g;
    var found = {}, match;
    while ((match = re.exec(text)) !== null) found[match[0].toLowerCase()] = 1;
    var domains = Object.keys(found);
    if (!domains.length) { NT.setHTML('domain_extractor-out', NT.badge('No domains found','warn')); return; }
    NT.setHTML('domain_extractor-out',
        NT.kv([['Domains found', domains.length]]) + '<br>' +
        '<div class="nt-record-list">' + domains.map(function(d){ return '<div class="nt-record-item">' + NT.esc(d) + '</div>'; }).join('') + '</div>');
};

NT.runTLDExtractor = function() {
    var v = document.getElementById('tld_extractor-in').value.trim();
    if (!v) { NT.setHTML('tld_extractor-out',''); return; }
    var p = NT._parseDomain(v);
    NT.setHTML('tld_extractor-out', NT.kv([['Input',v],['Full Host',p.host],['TLD',p.tld]]));
};

NT.runSubdomain = function() {
    var v = document.getElementById('subdomain-in').value.trim();
    if (!v) { NT.setHTML('subdomain-out',''); return; }
    var p = NT._parseDomain(v);
    NT.setHTML('subdomain-out', NT.kv([['Input',v],['Host',p.host],['Subdomain',p.subdomain],['Root Domain',p.sld+'.'+p.tld]]));
};

NT.runDomainIP = async function() {
    var q = document.getElementById('domain_ip-in').value.trim();
    if (!q) return;
    NT.loading('domain_ip-out');
    try {
        var d = await NT.api('domain_ip', q);
        var h = NT.kv([
            ['Domain',   d.domain],
            ['IPv4',     d.ipv4 && d.ipv4.length ? d.ipv4.join(', ') : '(none)'],
            ['IPv6',     d.ipv6 && d.ipv6.length ? d.ipv6.join(', ') : '(none)'],
        ]);
        NT.setHTML('domain_ip-out', h);
    } catch(e) { NT.err('domain_ip-out', e.message); }
};

NT.runWHOIS = async function() {
    var q = document.getElementById('whois-in').value.trim();
    if (!q) return;
    NT.loading('whois-out');
    try {
        var d = await NT.api('whois', q);
        // RDAP response
        var rows = [['Object Name', d.ldhName || d.name || d.handle || q]];
        if (d.status)  rows.push(['Status', Array.isArray(d.status)?d.status.join(', '):d.status]);
        if (d.events) {
            d.events.forEach(function(e) { rows.push([e.eventAction||'Event', e.eventDate ? e.eventDate.split('T')[0] : '—']); });
        }
        if (d.entities) {
            d.entities.forEach(function(e) {
                var role = (e.roles || ['contact']).join(', ');
                var fn2 = '';
                if (e.vcardArray && e.vcardArray[1]) {
                    e.vcardArray[1].forEach(function(v){ if(v[0]==='fn') fn2=v[3]; if(v[0]==='org'&&!fn2) fn2=v[3]; });
                }
                if (fn2) rows.push([role, fn2]);
            });
        }
        if (d.nameservers) {
            rows.push(['Nameservers', d.nameservers.map(function(ns){ return ns.ldhName||ns; }).join(', ')]);
        }
        if (d.links) {
            var self = d.links.find(function(l){ return l.rel==='self'; });
            if (self) rows.push(['RDAP URL', self.href]);
        }
        NT.setHTML('whois-out', NT.kv(rows, 'WHOIS / RDAP — ' + q));
    } catch(e) { NT.err('whois-out', e.message); }
};

NT.runReverseIP = async function() {
    var q = document.getElementById('reverse_ip-in').value.trim();
    if (!q) return;
    NT.loading('reverse_ip-out');
    try {
        var d = await NT.api('reverse_ip', q);
        NT.setHTML('reverse_ip-out', NT.kv([
            ['IP Address',    d.ip || d.query],
            ['Reverse DNS',   d.rdns || '—'],
            ['ISP',           d.isp || '—'],
            ['Organisation',  d.org || '—'],
            ['AS',            d.as || '—'],
            ['AS Name',       d.asname || '—'],
            ['Country',       d.country || '—'],
            ['City',          d.city || '—'],
            ['Hosting',       d.hosting ? 'Yes' : 'No'],
            ['Proxy/VPN',     d.proxy ? 'Yes' : 'No'],
            ['Mobile',        d.mobile ? 'Yes' : 'No'],
        ], 'Reverse IP — ' + (d.ip||q)));
    } catch(e) { NT.err('reverse_ip-out', e.message); }
};

/* ══════════════ URL TOOL HANDLERS ══════════════════════════════════════ */
NT.runURLParser = function() {
    var v = document.getElementById('url_parser-in').value.trim();
    if (!v) { NT.setHTML('url_parser-out',''); return; }
    try {
        var u = new URL(v.includes('://') ? v : 'https://' + v);
        NT.setHTML('url_parser-out', NT.kv([
            ['Full URL',   v],
            ['Protocol',   u.protocol],
            ['Username',   u.username || '(none)'],
            ['Password',   u.password || '(none)'],
            ['Hostname',   u.hostname],
            ['Port',       u.port || '(default for ' + u.protocol + ')'],
            ['Host',       u.host],
            ['Pathname',   u.pathname],
            ['Search',     u.search || '(none)'],
            ['Hash',       u.hash || '(none)'],
            ['Origin',     u.origin],
        ]));
    } catch(err) { NT.setHTML('url_parser-out', NT.badge('Invalid URL: ' + err.message, 'error')); }
};

NT.runURLEnc = function() {
    var v = document.getElementById('url_enc-in').value;
    if (!v) { NT.setHTML('url_enc-out',''); return; }
    var enc = encodeURIComponent(v);
    NT.setHTML('url_enc-out', NT.kv([
        ['Input',               v],
        ['encodeURIComponent()', enc],
        ['encodeURI()',          encodeURI(v)],
    ]));
};

NT.runURLDec = function() {
    var v = document.getElementById('url_dec-in').value;
    if (!v) { NT.setHTML('url_dec-out',''); return; }
    try {
        var dec1 = decodeURIComponent(v);
        var dec2 = v; try { dec2 = decodeURI(v); } catch(e2){}
        NT.setHTML('url_dec-out', NT.kv([
            ['Input',               v],
            ['decodeURIComponent()', dec1],
            ['decodeURI()',          dec2],
        ]));
    } catch(e) { NT.setHTML('url_dec-out', NT.badge('Invalid encoding: ' + e.message,'error')); }
};

NT._qbRows = [];
NT.addQBRow = function() {
    NT._qbRows.push({key:'',val:''});
    NT._renderQBRows();
};
NT._renderQBRows = function() {
    var c = document.getElementById('url_qb-params');
    if (!c) return;
    c.innerHTML = NT._qbRows.map(function(r,i){
        return '<div class="nt-qb-row"><input type="text" placeholder="Key" value="' + NT.esc(r.key) + '" oninput="NT._qbRows['+i+'].key=this.value;NT.runURLQB()"><input type="text" placeholder="Value" value="' + NT.esc(r.val) + '" oninput="NT._qbRows['+i+'].val=this.value;NT.runURLQB()"><button class="nt-qb-remove" onclick="NT._qbRows.splice('+i+',1);NT._renderQBRows();NT.runURLQB()">×</button></div>';
    }).join('');
};
NT.runURLQB = function() {
    var base = (document.getElementById('url_qb-base')||{}).value || '';
    var params = new URLSearchParams();
    NT._qbRows.forEach(function(r){ if(r.key) params.append(r.key, r.val); });
    var qs = params.toString();
    var full = qs ? (base ? base + (base.includes('?')?'&':'?') + qs : '?' + qs) : base;
    NT.setHTML('url_qb-out', NT.kv([
        ['Query String', qs || '(empty)'],
        ['Full URL',     full || '(empty)'],
    ]));
};

NT.runURLQP = function() {
    var v = document.getElementById('url_qp-in').value.trim();
    if (!v) { NT.setHTML('url_qp-out',''); return; }
    var qs = v.includes('?') ? v.split('?')[1].split('#')[0] : v;
    try {
        var p = new URLSearchParams(qs);
        var rows = [];
        p.forEach(function(val, key){ rows.push([key, val]); });
        if (!rows.length) { NT.setHTML('url_qp-out', NT.badge('No query parameters found','warn')); return; }
        NT.setHTML('url_qp-out', NT.kv([['Params found', rows.length],['Raw Query', qs]]) + '<br>' + NT.kv(rows, 'Parameters'));
    } catch(e) { NT.setHTML('url_qp-out', NT.badge('Parse error: ' + e.message,'error')); }
};

NT.runURLExtractor = function() {
    var text = document.getElementById('url_extractor-in').value;
    if (!text) { NT.setHTML('url_extractor-out',''); return; }
    var re = /https?:\/\/[^\s<>"'`\)\]]+/gi;
    var found = {}, m;
    while ((m = re.exec(text)) !== null) found[m[0]] = 1;
    var urls = Object.keys(found);
    if (!urls.length) { NT.setHTML('url_extractor-out', NT.badge('No URLs found','warn')); return; }
    NT.setHTML('url_extractor-out',
        NT.kv([['URLs found', urls.length]]) + '<br>' +
        '<div class="nt-record-list">' + urls.map(function(u){ return '<div class="nt-record-item" style="word-break:break-all">' + NT.esc(u) + '</div>'; }).join('') + '</div>');
};

NT.runURLSlug = function() {
    var v = document.getElementById('url_slug-in').value;
    if (!v) { NT.setHTML('url_slug-out',''); return; }
    var slug = v.toLowerCase()
        .replace(/[àáâãäå]/g,'a').replace(/[èéêë]/g,'e').replace(/[ìíîï]/g,'i')
        .replace(/[òóôõö]/g,'o').replace(/[ùúûü]/g,'u').replace(/[ñ]/g,'n')
        .replace(/[^a-z0-9\s-]/g,'').replace(/[\s_-]+/g,'-').replace(/^-+|-+$/g,'');
    NT.setHTML('url_slug-out', NT.kv([['Input',v],['Slug',slug],['Length',slug.length + ' chars']]));
};

NT.runURLRedirect = async function() {
    var q = document.getElementById('url_redirect-in').value.trim();
    if (!q) return;
    NT.loading('url_redirect-out');
    try {
        var d = await NT.api('url_redirect', q);
        var codeClass = d.code >= 300 && d.code < 400 ? 'warn' : (d.code >= 200 && d.code < 300 ? 'ok' : 'error');
        NT.setHTML('url_redirect-out', NT.kv([
            ['URL',       d.url],
            ['HTTP Code', NT.badge(d.code + (d.code>=300&&d.code<400?' (Redirect)':' (No Redirect)'), codeClass), true],
            ['Redirects', d.redirects ? 'Yes' : 'No'],
            ['Location',  d.location || '—'],
        ]));
    } catch(e) { NT.err('url_redirect-out', e.message); }
};

NT.runURLOpener = async function() {
    var q = document.getElementById('url_opener-in').value.trim();
    if (!q) return;
    if (!/^https?:\/\//i.test(q)) q = 'https://' + q;
    NT.loading('url_opener-out');
    try {
        var d = await NT.api('http_status_check', q);
        var codeClass = d.code >= 200 && d.code < 300 ? 'ok' : d.code >= 400 ? 'error' : 'warn';
        var reachable = d.code >= 200 && d.code < 400;
        var rows = [
            ['URL',           d.url],
            ['HTTP Status',   NT.badge(d.code + (reachable ? ' — reachable' : ' — unreachable'), codeClass), true],
            ['Response Time', d.time_ms + ' ms'],
        ];
        if (d.server)       rows.push(['Server',  d.server]);
        if (d.content_type) rows.push(['Type',    d.content_type]);
        var h = NT.kv(rows, 'URL Opener Test');
        if (reachable) {
            h += '<div style="margin-top:10px"><a href="' + NT.esc(d.url) + '" target="_blank" rel="noopener noreferrer" class="nt-btn nt-btn-primary" style="display:inline-flex;align-items:center;gap:6px;text-decoration:none">Open URL in New Tab ↗</a></div>';
        }
        NT.setHTML('url_opener-out', h);
    } catch(e) { NT.err('url_opener-out', e.message); }
};

NT.runCanonicalURL = async function() {
    var q = document.getElementById('canonical_url-in').value.trim();
    if (!q) return;
    NT.loading('canonical_url-out');
    try {
        var d = await NT.api('canonical_url', q);
        if (d.error) throw new Error(d.error);
        var rows = [['URL', d.url]];
        if (d.canonical) {
            var isSelf = d.canonical === d.url || d.canonical === d.final_url;
            rows.push(['Canonical URL', d.canonical]);
            rows.push(['Self-referencing', isSelf ? NT.badge('Yes — canonical matches this URL', 'ok') : NT.badge('No — points to a different URL', 'warn'), true]);
            if (d.final_url && d.final_url !== d.url) rows.push(['Final URL (after redirects)', d.final_url]);
        } else {
            rows.push(['Canonical Tag', NT.badge('Not found — no <link rel="canonical"> present', 'warn'), true]);
        }
        if (d.http_code) rows.push(['HTTP Status', d.http_code]);
        NT.setHTML('canonical_url-out', NT.kv(rows, 'Canonical URL Check'));
    } catch(e) { NT.err('canonical_url-out', e.message); }
};

/* ══════════════ HTTP TOOL HANDLERS ══════════════════════════════════════ */
NT._httpStatuses = {
    '1xx Informational': {
        100:['Continue','The client should continue the request'],
        101:['Switching Protocols','Server is switching protocols per client request'],
        102:['Processing','Server has received and is processing the request'],
        103:['Early Hints','Return headers before final response'],
    },
    '2xx Success': {
        200:['OK','Request succeeded'],
        201:['Created','Resource created'],
        202:['Accepted','Request accepted for processing'],
        204:['No Content','Request succeeded, no content to return'],
        206:['Partial Content','Partial GET fulfilled'],
        207:['Multi-Status','Multiple status for multiple sub-requests'],
        208:['Already Reported','Members already reported'],
        226:['IM Used','Instance manipulations fulfilled'],
    },
    '3xx Redirection': {
        301:['Moved Permanently','Resource moved permanently to new URL'],
        302:['Found','Resource temporarily at different URL'],
        303:['See Other','Redirect to different resource via GET'],
        304:['Not Modified','Cached version is still valid'],
        307:['Temporary Redirect','Same as 302 but method must not change'],
        308:['Permanent Redirect','Same as 301 but method must not change'],
    },
    '4xx Client Errors': {
        400:['Bad Request','Malformed request syntax'],
        401:['Unauthorized','Authentication required'],
        402:['Payment Required','Payment needed'],
        403:['Forbidden','Server refuses request'],
        404:['Not Found','Resource not found'],
        405:['Method Not Allowed','HTTP method not allowed'],
        406:['Not Acceptable','Response not acceptable per Accept headers'],
        408:['Request Timeout','Server timed out waiting for request'],
        409:['Conflict','Request conflicts with server state'],
        410:['Gone','Resource permanently deleted'],
        411:['Length Required','Content-Length header required'],
        412:['Precondition Failed','Precondition in headers evaluated to false'],
        413:['Content Too Large','Request body too large'],
        414:['URI Too Long','URI is too long'],
        415:['Unsupported Media Type','Payload format not supported'],
        416:['Range Not Satisfiable','Cannot satisfy Range header'],
        418:["I'm a teapot",'RFC 2324 Easter egg'],
        422:['Unprocessable Content','Request well-formed but semantic errors'],
        423:['Locked','Resource is locked'],
        425:['Too Early','Request may be replayed'],
        429:['Too Many Requests','Rate limit exceeded'],
        451:['Unavailable For Legal Reasons','Censored or blocked by legal request'],
    },
    '5xx Server Errors': {
        500:['Internal Server Error','Generic server error'],
        501:['Not Implemented','Server lacks functionality'],
        502:['Bad Gateway','Invalid response from upstream'],
        503:['Service Unavailable','Server temporarily unavailable'],
        504:['Gateway Timeout','Upstream did not respond in time'],
        505:['HTTP Version Not Supported','HTTP version not supported'],
        507:['Insufficient Storage','Not enough storage'],
        508:['Loop Detected','Infinite loop detected'],
        510:['Not Extended','Further extensions required'],
        511:['Network Authentication Required','Network login required'],
    },
};

NT.renderHTTPStatus = function(filter) {
    filter = (filter||'').toLowerCase();
    var h = '<div class="nt-status-grid">';
    Object.keys(NT._httpStatuses).forEach(function(group) {
        var codes = NT._httpStatuses[group];
        var rows = Object.keys(codes).filter(function(c){
            if (!filter) return true;
            return c.includes(filter) || codes[c][0].toLowerCase().includes(filter) || codes[c][1].toLowerCase().includes(filter);
        });
        if (!rows.length) return;
        h += '<div><div class="nt-status-group-title">' + NT.esc(group) + '</div><table class="nt-status-table">';
        rows.forEach(function(c) {
            h += '<tr><td>' + c + '</td><td>' + NT.esc(codes[c][0]) + '</td><td>' + NT.esc(codes[c][1]) + '</td></tr>';
        });
        h += '</table></div>';
    });
    h += '</div>';
    NT.setHTML('http_status-out', h);
};

NT.filterHTTPStatus = function() {
    NT.renderHTTPStatus(document.getElementById('http_status-in').value);
};

NT.runHTTPHeaders = async function() {
    var q = document.getElementById('http_headers-in').value.trim();
    if (!q) return;
    NT.loading('http_headers-out');
    try {
        var d = await NT.api('http_headers', q);
        if (d.error) throw new Error(d.error);
        var rows = [['URL',d.url],['HTTP Status',d.code],['Response Time',d.time_ms + ' ms']];
        Object.keys(d.headers || {}).forEach(function(k){ rows.push([k, d.headers[k]]); });
        NT.setHTML('http_headers-out', NT.kv(rows, 'Response Headers'));
    } catch(e) { NT.err('http_headers-out', e.message); }
};

NT._parseUA = function(ua) {
    var result = {browser:'Unknown',browserVer:'',os:'Unknown',osVer:'',device:'Desktop',bot:false,engine:'Unknown',engineVer:''};
    if (!ua) return result;
    // Bot detection
    if (/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|linkedinbot|twitterbot|whatsapp|applebot|pingdom|uptimerobot/i.test(ua)) {
        result.bot = true; result.device = 'Bot / Crawler';
    }
    // OS
    if (/Windows NT 11/i.test(ua))        { result.os = 'Windows'; result.osVer = '11'; }
    else if (/Windows NT 10/i.test(ua))   { result.os = 'Windows'; result.osVer = '10'; }
    else if (/Windows NT 6\.3/i.test(ua)) { result.os = 'Windows'; result.osVer = '8.1'; }
    else if (/Windows NT 6\.2/i.test(ua)) { result.os = 'Windows'; result.osVer = '8'; }
    else if (/Windows NT 6\.1/i.test(ua)) { result.os = 'Windows'; result.osVer = '7'; }
    else if (/Windows/i.test(ua))          { result.os = 'Windows'; }
    else if (/iPhone/.test(ua))           { result.os = 'iOS'; result.device = 'Mobile (iPhone)'; var m=ua.match(/OS ([\d_]+)/); if(m) result.osVer=m[1].replace(/_/g,'.'); }
    else if (/iPad/.test(ua))             { result.os = 'iPadOS'; result.device = 'Tablet (iPad)'; var m2=ua.match(/OS ([\d_]+)/); if(m2) result.osVer=m2[1].replace(/_/g,'.'); }
    else if (/Android/.test(ua))          { result.os = 'Android'; var m3=ua.match(/Android ([\d.]+)/); if(m3) result.osVer=m3[1]; if(/Mobile/.test(ua)) result.device='Mobile'; else result.device='Tablet'; }
    else if (/Mac OS X/.test(ua))         { result.os = 'macOS'; var m4=ua.match(/Mac OS X ([\d_]+)/); if(m4) result.osVer=m4[1].replace(/_/g,'.'); }
    else if (/Linux/.test(ua))             { result.os = 'Linux'; }
    else if (/CrOS/.test(ua))             { result.os = 'ChromeOS'; }
    // Browser
    if (/Edg\//i.test(ua))               { var m=ua.match(/Edg\/([\d.]+)/); result.browser='Edge'; result.browserVer=m?m[1]:''; }
    else if (/OPR\//i.test(ua))           { var m2=ua.match(/OPR\/([\d.]+)/); result.browser='Opera'; result.browserVer=m2?m2[1]:''; }
    else if (/Chrome\//i.test(ua))        { var m3=ua.match(/Chrome\/([\d.]+)/); result.browser='Chrome'; result.browserVer=m3?m3[1]:''; }
    else if (/Firefox\//i.test(ua))       { var m4=ua.match(/Firefox\/([\d.]+)/); result.browser='Firefox'; result.browserVer=m4?m4[1]:''; }
    else if (/Safari\//i.test(ua))        { var m5=ua.match(/Version\/([\d.]+)/); result.browser='Safari'; result.browserVer=m5?m5[1]:''; }
    else if (/MSIE|Trident/i.test(ua))    { result.browser='Internet Explorer'; }
    // Engine
    if (/Blink/i.test(ua))               result.engine = 'Blink';
    else if (/Gecko\/[\d]/i.test(ua))    result.engine = 'Gecko';
    else if (/AppleWebKit/i.test(ua))    { result.engine = 'WebKit'; var m6=ua.match(/AppleWebKit\/([\d.]+)/); if(m6) result.engineVer=m6[1]; }
    else if (/Trident/i.test(ua))        result.engine = 'Trident';
    return result;
};

NT.runUAParser = function() {
    var ua = document.getElementById('ua_parser-in').value.trim();
    if (!ua) { NT.setHTML('ua_parser-out',''); return; }
    var r = NT._parseUA(ua);
    var h = '<div class="nt-ua-grid">';
    [['Browser', r.browser + (r.browserVer?' '+r.browserVer:'')],
     ['OS', r.os + (r.osVer?' '+r.osVer:'')],
     ['Device', r.device],
     ['Bot / Crawler', r.bot?'Yes':'No'],
     ['Rendering Engine', r.engine + (r.engineVer?' '+r.engineVer:'')],
     ['UA Length', ua.length + ' characters'],
    ].forEach(function(c){
        h += '<div class="nt-ua-card"><div class="nt-ua-card-label">' + NT.esc(c[0]) + '</div><div class="nt-ua-card-val">' + NT.esc(c[1]) + '</div></div>';
    });
    h += '</div><br>' + NT.kv([['Raw User-Agent', ua]]);
    NT.setHTML('ua_parser-out', h);
};

NT._mimes = [
    ['html','text/html','Web page'],
    ['htm','text/html','Web page'],
    ['css','text/css','Stylesheet'],
    ['js','text/javascript','JavaScript'],
    ['mjs','text/javascript','JavaScript module'],
    ['json','application/json','JSON data'],
    ['xml','application/xml','XML data'],
    ['csv','text/csv','CSV spreadsheet'],
    ['txt','text/plain','Plain text'],
    ['md','text/markdown','Markdown'],
    ['pdf','application/pdf','PDF document'],
    ['zip','application/zip','ZIP archive'],
    ['gz','application/gzip','Gzip archive'],
    ['tar','application/x-tar','TAR archive'],
    ['rar','application/vnd.rar','RAR archive'],
    ['7z','application/x-7z-compressed','7-Zip archive'],
    ['octet-stream','application/octet-stream','Binary data'],
    ['jpg','image/jpeg','JPEG image'],
    ['jpeg','image/jpeg','JPEG image'],
    ['png','image/png','PNG image'],
    ['gif','image/gif','GIF image'],
    ['webp','image/webp','WebP image'],
    ['svg','image/svg+xml','SVG vector image'],
    ['ico','image/x-icon','Browser icon'],
    ['bmp','image/bmp','Bitmap image'],
    ['tiff','image/tiff','TIFF image'],
    ['avif','image/avif','AVIF image'],
    ['mp4','video/mp4','MPEG-4 video'],
    ['webm','video/webm','WebM video'],
    ['ogg','video/ogg','OGG video'],
    ['avi','video/x-msvideo','AVI video'],
    ['mov','video/quicktime','QuickTime video'],
    ['mkv','video/x-matroska','Matroska video'],
    ['mp3','audio/mpeg','MP3 audio'],
    ['wav','audio/wav','WAV audio'],
    ['ogg-a','audio/ogg','OGG audio'],
    ['flac','audio/flac','FLAC audio'],
    ['aac','audio/aac','AAC audio'],
    ['woff','font/woff','WOFF font'],
    ['woff2','font/woff2','WOFF2 font'],
    ['ttf','font/ttf','TrueType font'],
    ['otf','font/otf','OpenType font'],
    ['form-data','multipart/form-data','Form upload'],
    ['form-urlenc','application/x-www-form-urlencoded','Form data'],
    ['xls','application/vnd.ms-excel','Excel (legacy)'],
    ['xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','Excel (XLSX)'],
    ['doc','application/msword','Word (legacy)'],
    ['docx','application/vnd.openxmlformats-officedocument.wordprocessingml.document','Word (DOCX)'],
    ['ppt','application/vnd.ms-powerpoint','PowerPoint (legacy)'],
    ['pptx','application/vnd.openxmlformats-officedocument.presentationml.presentation','PowerPoint (PPTX)'],
    ['wasm','application/wasm','WebAssembly'],
    ['atom','application/atom+xml','Atom feed'],
    ['rss','application/rss+xml','RSS feed'],
];

NT.renderMIMETable = function(filter) {
    filter = (filter||'').toLowerCase();
    var rows = NT._mimes.filter(function(m){
        return !filter || m[0].includes(filter) || m[1].includes(filter) || m[2].toLowerCase().includes(filter);
    });
    if (!rows.length) { NT.setHTML('mime-out', NT.badge('No MIME types match','warn')); return; }
    var h = '<div class="nt-kv"><table class="nt-mime-table"><thead><tr><th>MIME Type</th><th>Extension</th><th>Description</th></tr></thead><tbody>';
    rows.forEach(function(m){ h += '<tr><td>' + NT.esc(m[1]) + '</td><td>.' + NT.esc(m[0]) + '</td><td>' + NT.esc(m[2]) + '</td></tr>'; });
    h += '</tbody></table></div>';
    NT.setHTML('mime-out', h);
};

NT.runMIMELookup = function() {
    NT.renderMIMETable(document.getElementById('mime-in').value);
};

NT.runCookieParser = function() {
    var raw = document.getElementById('cookie_parser-in').value.trim();
    if (!raw) { NT.setHTML('cookie_parser-out',''); return; }
    var parts = raw.split(';').map(function(p){ return p.trim(); });
    var first = parts[0].split('=');
    var name  = first[0].trim(), value = first.slice(1).join('=');
    var attrs = {};
    parts.slice(1).forEach(function(p){
        var kv = p.split('='); var k = kv[0].trim().toLowerCase(); var v = kv.slice(1).join('=').trim();
        attrs[k] = v || true;
    });
    NT.setHTML('cookie_parser-out', NT.kv([
        ['Cookie Name',  name],
        ['Value',        value],
        ['Path',         attrs['path'] || '(not set)'],
        ['Domain',       attrs['domain'] || '(not set)'],
        ['Expires',      attrs['expires'] || '(session)'],
        ['Max-Age',      attrs['max-age'] || '(not set)'],
        ['Secure',       attrs['secure'] ? NT.badge('Yes','ok') : NT.badge('No','warn'), true],
        ['HttpOnly',     attrs['httponly'] ? NT.badge('Yes','ok') : NT.badge('No','warn'), true],
        ['SameSite',     attrs['samesite'] || '(not set)'],
    ], 'Cookie: ' + name));
};

NT.runSecurityHeaders = async function() {
    var q = document.getElementById('sec_headers-in').value.trim();
    if (!q) return;
    NT.loading('sec_headers-out');
    try {
        var d = await NT.api('security_headers', q);
        if (d.error) throw new Error(d.error);
        var present = d.checks.filter(function(c){ return c.present; }).length;
        var total   = d.checks.length;
        var scoreClass = present >= 6 ? 'ok' : present >= 3 ? 'warn' : 'error';
        var h = NT.kv([['URL',d.url],['HTTP Status',d.code],['Security Headers',NT.badge(present+' / '+total, scoreClass)],true]) + '<br>';
        h += '<div class="nt-sec-header-list">';
        d.checks.forEach(function(c) {
            var icon = c.present
                ? '<svg class="nt-sec-icon" style="color:#16a34a" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>'
                : '<svg class="nt-sec-icon" style="color:#dc2626" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
            h += '<div class="nt-sec-header-item">' + icon + '<div><div class="nt-sec-header-name">' + NT.esc(c.name) + '</div>' +
                (c.value ? '<div class="nt-sec-header-val">' + NT.esc(c.value) + '</div>' : '') + '</div></div>';
        });
        h += '</div>';
        NT.setHTML('sec_headers-out', h);
    } catch(e) { NT.err('sec_headers-out', e.message); }
};

NT.runCacheHeaders = async function() {
    var q = document.getElementById('cache_headers-in').value.trim();
    if (!q) return;
    NT.loading('cache_headers-out');
    try {
        var d = await NT.api('cache_headers', q);
        if (d.error) throw new Error(d.error);
        var rows = [['URL',d.url],['HTTP Status',d.code]];
        if (Object.keys(d.cache_headers||{}).length === 0) {
            rows.push(['Cache Headers','(none found)']);
        } else {
            Object.keys(d.cache_headers).forEach(function(k){ rows.push([k, d.cache_headers[k]]); });
        }
        NT.setHTML('cache_headers-out', NT.kv(rows,'Cache Headers'));
    } catch(e) { NT.err('cache_headers-out', e.message); }
};

NT.runRedirectChain = async function() {
    var q = document.getElementById('redirect_chain-in').value.trim();
    if (!q) return;
    NT.loading('redirect_chain-out');
    try {
        var d = await NT.api('redirect_chain', q);
        if (d.error) throw new Error(d.error);
        var h = NT.kv([['Original URL',d.original],['Final URL',d.final],['Total Hops',d.hops]]) + '<br>';
        h += '<div class="nt-chain">';
        d.chain.forEach(function(step, i) {
            var codeStr = step.code || '0';
            var cls = codeStr >= 500 ? 'c5xx' : codeStr >= 400 ? 'c4xx' : codeStr >= 300 ? 'c3xx' : codeStr >= 200 ? 'c2xx' : 'c0xx';
            h += '<div class="nt-chain-step"><span class="nt-chain-step-code ' + cls + '">' + codeStr + '</span><span class="nt-chain-step-url">' + NT.esc(step.url) + '</span>';
            if (step.time_ms) h += '<span style="font-size:11px;color:var(--color-text-muted);white-space:nowrap">' + step.time_ms + 'ms</span>';
            h += '</div>';
            if (step.location) h += '<div class="nt-chain-arrow">↓ ' + NT.esc(step.location) + '</div>';
        });
        h += '</div>';
        NT.setHTML('redirect_chain-out', h);
    } catch(e) { NT.err('redirect_chain-out', e.message); }
};

NT.runResponseTime = async function() {
    var q = document.getElementById('response_time-in').value.trim();
    if (!q) return;
    NT.loading('response_time-out');
    try {
        var d = await NT.api('response_time', q);
        if (d.error) throw new Error(d.error);
        var rating = d.avg_ms < 200 ? NT.badge('Fast','ok') : d.avg_ms < 600 ? NT.badge('Average','warn') : NT.badge('Slow','error');
        NT.setHTML('response_time-out', NT.kv([
            ['URL',       d.url],
            ['Average',   d.avg_ms + ' ms &nbsp;' + rating, true],
            ['Minimum',   d.min_ms + ' ms'],
            ['Maximum',   d.max_ms + ' ms'],
            ['Samples',   d.times_ms ? d.times_ms.join(' ms, ') + ' ms' : '3'],
        ],'Response Time (3 pings)'));
    } catch(e) { NT.err('response_time-out', e.message); }
};

/* ══════════════ SSL TOOL HANDLERS ═══════════════════════════════════════ */
NT.callSSL = async function(id, view) {
    var domainEl = document.getElementById(id + '-in') || document.getElementById('ssl_cert-in');
    var portEl   = document.getElementById('ssl_cert-port');
    var domain   = (domainEl ? domainEl.value.trim() : '').replace(/^https?:\/\//i,'').split('/')[0];
    var port     = portEl ? (parseInt(portEl.value)||443) : 443;
    if (!domain) return;
    NT.loading(id + '-out');
    try {
        var d = await NT.api('ssl_cert', domain, port);
        if (d.error) throw new Error(d.error);
        var now = Math.floor(Date.now()/1000);
        var daysLeft = Math.max(0, Math.floor((d.valid_to_ts - now) / 86400));
        var expClass = daysLeft <= 0 ? 'error' : daysLeft <= 14 ? 'error' : daysLeft <= 30 ? 'warn' : 'ok';
        var expBadge = NT.badge(d.expired ? 'Expired' : daysLeft + ' days remaining', expClass);

        if (view === 'expiry') {
            var pct = Math.min(100, Math.max(0, Math.round(daysLeft / 365 * 100)));
            var barColor = expClass === 'ok' ? '#22c55e' : expClass === 'warn' ? '#f59e0b' : '#ef4444';
            var h = NT.kv([
                ['Host',           d.host + ':' + d.port],
                ['Expires',        d.valid_to + ' &nbsp;' + expBadge, true],
                ['Days Remaining', daysLeft],
                ['Issued',         d.valid_from],
                ['Subject CN',     d.subject.CN || '—'],
                ['Issuer',         (d.issuer.O || d.issuer.CN || '—')],
            ],'SSL Expiry — ' + d.host);
            h += '<div class="nt-cert-expiry-bar" style="margin-top:10px"><div class="nt-cert-expiry-fill" style="width:' + pct + '%;background:' + barColor + '"></div></div>';
            h += '<div style="font-size:11px;color:var(--color-text-muted);margin-top:4px">Validity used: ' + (100-pct) + '% of ~365 days</div>';
            NT.setHTML(id + '-out', h);
        } else if (view === 'chain') {
            var h2 = NT.kv([['Host',d.host],['Chain Length',d.chain_length]],'Certificate Chain');
            (d.chain||[]).forEach(function(cert,i){
                h2 += '<br><div class="nt-section-head">Certificate ' + (i+1) + (i===0?' (End-Entity)':i===d.chain_length-1?' (Root CA)':' (Intermediate)') + '</div>';
                h2 += NT.kv([
                    ['Subject CN',  cert.subject.CN||cert.subject.O||'—'],
                    ['Issuer',      cert.issuer.O||cert.issuer.CN||'—'],
                    ['Expires',     cert.valid_to],
                ]);
            });
            NT.setHTML(id + '-out', h2);
        } else if (view === 'decode') {
            var rows = [
                ['Host', d.host + ':' + d.port],
                ['Subject CN', d.subject.CN||'—'], ['Subject O', d.subject.O||'—'],
                ['Subject OU', d.subject.OU||'—'], ['Subject C', d.subject.C||'—'],
                ['Issuer CN', d.issuer.CN||'—'], ['Issuer O', d.issuer.O||'—'],
                ['Valid From', d.valid_from], ['Valid To', d.valid_to],
                ['Serial Number', d.serial], ['Version', d.version],
                ['Signature Algorithm', d.signature_alg], ['Chain Length', d.chain_length],
            ];
            if (d.extensions && d.extensions.subjectAltName) rows.push(['Subject Alt Names', d.extensions.subjectAltName]);
            if (d.extensions && d.extensions.keyUsage) rows.push(['Key Usage', d.extensions.keyUsage]);
            if (d.extensions && d.extensions.extendedKeyUsage) rows.push(['Extended Key Usage', d.extensions.extendedKeyUsage]);
            NT.setHTML(id + '-out', NT.kv(rows, 'Certificate Decoded — ' + d.host));
        } else {
            // Full view
            var h3 = NT.kv([
                ['Host',           d.host + ':' + d.port],
                ['Status',         NT.badge(d.expired?'Expired':daysLeft+' days left', expClass), true],
                ['Subject CN',     d.subject.CN||'—'],
                ['Subject Organisation', d.subject.O||'—'],
                ['Issuer',         d.issuer.O||d.issuer.CN||'—'],
                ['Issued By CN',   d.issuer.CN||'—'],
                ['Valid From',     d.valid_from],
                ['Valid To',       d.valid_to],
                ['Days Remaining', daysLeft],
                ['Serial',         d.serial],
                ['Signature Alg',  d.signature_alg],
                ['Chain Length',   d.chain_length],
            ],'SSL Certificate — ' + d.host);
            if (d.san) h3 += '<br>' + NT.kv([['Subject Alt Names', d.san]],'SANs');
            NT.setHTML(id + '-out', h3);
        }
    } catch(e) { NT.err(id + '-out', e.message); }
};

/* ══════════════ EMAIL TOOL HANDLERS ══════════════════════════════════════ */
NT.runEmailHeaders = function() {
    var raw = document.getElementById('email_headers-in').value.trim();
    if (!raw) { NT.setHTML('email_headers-out',''); return; }
    // Parse headers (fold continuation lines)
    var unfolded = raw.replace(/\r\n([ \t])/g,' $1').replace(/\n([ \t])/g,' $1');
    var lines = unfolded.split(/\r?\n/);
    var headers = [];
    lines.forEach(function(line) {
        var idx = line.indexOf(':');
        if (idx > 0) {
            var k = line.slice(0, idx).trim();
            var v = line.slice(idx+1).trim();
            headers.push([k,v]);
        }
    });
    if (!headers.length) { NT.setHTML('email_headers-out', NT.badge('No headers found — paste raw headers including field names like "From:", "Received:", etc.','warn')); return; }
    var important = ['From','To','Subject','Date','Message-ID','Reply-To','Return-Path','X-Mailer','X-Spam-Score','DKIM-Signature','ARC-Authentication-Results'];
    var keyHeaders = headers.filter(function(h){ return important.includes(h[0]); });
    var receivedHops = headers.filter(function(h){ return h[0]==='Received'; });
    var authResults = headers.find(function(h){ return h[0]==='Authentication-Results'; });
    var h = '<div class="nt-section-head">Key Headers</div><br>';
    h += NT.kv(keyHeaders.length ? keyHeaders : headers.slice(0,10),'');
    if (receivedHops.length) {
        h += '<br><div class="nt-section-head">Received Chain (' + receivedHops.length + ' hops — top = latest)</div><br>';
        h += '<div class="nt-record-list">' + receivedHops.map(function(r,i){
            return '<div class="nt-record-item"><span class="nt-record-type">Hop ' + (i+1) + '</span>' + NT.esc(r[1]) + '</div>';
        }).join('') + '</div>';
    }
    if (authResults) {
        h += '<br><div class="nt-section-head">Authentication Results</div><br>';
        h += NT.pre(authResults[1]);
    }
    h += '<br><div class="nt-section-head">All Headers (' + headers.length + ')</div><br>';
    h += NT.kv(headers,'');
    NT.setHTML('email_headers-out', h);
};

NT.runSPFCheck = async function() {
    var q = document.getElementById('spf_check-in').value.trim();
    if (!q) return;
    NT.loading('spf_check-out');
    try {
        var d = await NT.api('spf', q);
        var h = NT.kv([
            ['Domain',    d.domain],
            ['SPF Found', d.found ? NT.badge('Yes — SPF record present','ok') : NT.badge('No — no SPF record found','error'), true],
            ['SPF Record',d.spf || '—'],
        ]);
        if (d.spf) {
            var parts = d.spf.split(/\s+/);
            h += '<br><div class="nt-section-head">SPF Directives</div><br>';
            h += '<div class="nt-record-list">' + parts.map(function(p){ return '<div class="nt-record-item">' + NT.esc(p) + '</div>'; }).join('') + '</div>';
        }
        NT.setHTML('spf_check-out', h);
    } catch(e) { NT.err('spf_check-out', e.message); }
};

NT.runDKIMCheck = async function() {
    var domain   = (document.getElementById('dkim_check-domain')||{}).value.trim().replace(/^https?:\/\//i,'').split('/')[0];
    var selector = (document.getElementById('dkim_check-sel')||{}).value.trim() || 'default';
    if (!domain) return;
    NT.loading('dkim_check-out');
    try {
        var d = await NT.api('dkim', domain, selector);
        NT.setHTML('dkim_check-out', NT.kv([
            ['Domain',      d.domain],
            ['Selector',    d.selector],
            ['DKIM Domain', d.dkim_domain],
            ['Found',       d.found ? NT.badge('Yes — DKIM key found','ok') : NT.badge('No — DKIM record not found','error'), true],
            ['DKIM Record', d.dkim ? d.dkim.slice(0,300) + (d.dkim.length>300?'…':'') : '—'],
        ]));
    } catch(e) { NT.err('dkim_check-out', e.message); }
};

NT.runDMARCCheck = async function() {
    var q = document.getElementById('dmarc_check-in').value.trim();
    if (!q) return;
    NT.loading('dmarc_check-out');
    try {
        var d = await NT.api('dmarc', q);
        var h = NT.kv([
            ['Domain',       d.domain],
            ['DMARC Domain', d.dmarc_domain],
            ['Found',        d.found ? NT.badge('Yes','ok') : NT.badge('No','error'), true],
            ['DMARC Record', d.dmarc || '—'],
        ]);
        if (d.dmarc) {
            var tags = d.dmarc.split(';').map(function(t){ return t.trim(); }).filter(Boolean);
            h += '<br><div class="nt-section-head">DMARC Tags</div><br>';
            h += '<div class="nt-record-list">' + tags.map(function(t){ return '<div class="nt-record-item">' + NT.esc(t) + '</div>'; }).join('') + '</div>';
        }
        NT.setHTML('dmarc_check-out', h);
    } catch(e) { NT.err('dmarc_check-out', e.message); }
};

NT.runMXLookup = async function() {
    var q = document.getElementById('mx_lookup-in').value.trim();
    if (!q) return;
    NT.loading('mx_lookup-out');
    try {
        var d = await NT.api('mx', q);
        var h = NT.kv([['Domain',d.domain],['MX Records',d.count]]) + '<br>';
        if (!d.mx || !d.mx.length) { h += NT.badge('No MX records found','warn'); NT.setHTML('mx_lookup-out',h); return; }
        h += '<div class="nt-record-list">';
        d.mx.forEach(function(mx) {
            h += '<div class="nt-record-item"><span class="nt-record-type">MX ' + NT.esc(mx.priority) + '</span>';
            h += '<strong>' + NT.esc(mx.host) + '</strong>';
            if (mx.ip && mx.ip.length) h += ' &nbsp; <span style="color:var(--color-text-muted)">[' + NT.esc(mx.ip.join(', ')) + ']</span>';
            h += '</div>';
        });
        h += '</div>';
        NT.setHTML('mx_lookup-out', h);
    } catch(e) { NT.err('mx_lookup-out', e.message); }
};

/* ══════════════ CONNECTIVITY HANDLERS ═══════════════════════════════════ */
NT.runAvailability = async function() {
    var q = document.getElementById('availability-in').value.trim();
    if (!q) return;
    NT.loading('availability-out');
    try {
        var d = await NT.api('availability', q);
        var statusClass = d.available ? 'ok' : 'error';
        var h = NT.kv([
            ['URL',       d.url],
            ['Status',    NT.badge(d.available ? 'Online — reachable' : 'Offline — unreachable', statusClass), true],
            ['HTTP Code', d.code || '(no response)'],
            ['Response Time', d.time_ms + ' ms'],
            ['Server',    d.server || '(not disclosed)'],
        ],'Website Availability');
        NT.setHTML('availability-out', h);
    } catch(e) { NT.err('availability-out', e.message); }
};

NT.runServerInfo = async function() {
    var q = document.getElementById('server_info-in').value.trim();
    if (!q) return;
    NT.loading('server_info-out');
    try {
        var d = await NT.api('server_info', q);
        var rows = [
            ['URL',           d.url],
            ['HTTP Status',   d.code],
            ['Response Time', d.time_ms + ' ms'],
            ['Server',        d.server || '(not disclosed)'],
            ['X-Powered-By',  d['x-powered-by'] || '(not disclosed)'],
            ['Content-Type',  d['content-type'] || '—'],
            ['Via',           d.via || '—'],
            ['CF-Ray',        d['cf-ray'] || '—'],
            ['X-Cache',       d['x-cache'] || '—'],
        ];
        var cdnHints = [];
        var all = d.all || {};
        if (all['cf-ray']) cdnHints.push('Cloudflare');
        if (all['x-amz-cf-id'] || all['x-amz-request-id']) cdnHints.push('AWS CloudFront');
        if (all['x-fastly-request-id']) cdnHints.push('Fastly');
        if (all['x-vercel-id']) cdnHints.push('Vercel');
        if (all['x-cache']&&all['x-cache'].toLowerCase().includes('varnish')) cdnHints.push('Varnish');
        if (all['x-powered-by']&&/next\.js/i.test(all['x-powered-by'])) cdnHints.push('Next.js');
        if (cdnHints.length) rows.push(['Detected Tech', cdnHints.join(', ')]);
        NT.setHTML('server_info-out', NT.kv(rows,'Server Information'));
    } catch(e) { NT.err('server_info-out', e.message); }
};

NT.runHTTPMethod = async function() {
    var q = document.getElementById('http_method-in').value.trim();
    if (!q) return;
    NT.loading('http_method-out');
    try {
        var d = await NT.api('http_method', q);
        if (d.error) throw new Error(d.error);
        var h = NT.kv([['URL', d.url]],'HTTP Method Test Results') + '<br>';
        h += '<div class="nt-record-list">' + d.results.map(function(r){
            var cl = r.code > 0 && r.code < 405 ? 'ok' : r.code === 405 ? 'error' : r.error ? 'error' : 'warn';
            var label = r.error ? 'Error' : r.code + '';
            return '<div class="nt-record-item"><span class="nt-record-type">' + NT.esc(r.method) + '</span>' + NT.badge(label, cl) + (r.error ? ' ' + NT.esc(r.error) : '') + '</div>';
        }).join('') + '</div>';
        NT.setHTML('http_method-out', h);
    } catch(e) { NT.err('http_method-out', e.message); }
};

NT.runRobots = async function() {
    var q = document.getElementById('robots-in').value.trim();
    if (!q) return;
    NT.loading('robots-out');
    try {
        var d = await NT.api('robots', q);
        var h = NT.kv([
            ['URL',   d.url],
            ['Found', d.found ? NT.badge('Yes','ok') : NT.badge('No — robots.txt not found','error'), true],
            ['HTTP',  d.code || '—'],
        ]);
        if (d.found && d.content) h += '<br>' + NT.pre(d.content, 8192);
        NT.setHTML('robots-out', h);
    } catch(e) { NT.err('robots-out', e.message); }
};

NT.runSitemap = async function() {
    var q = document.getElementById('sitemap-in').value.trim();
    if (!q) return;
    NT.loading('sitemap-out');
    try {
        var d = await NT.api('sitemap', q);
        var h = NT.kv([
            ['Sitemap URL', d.url],
            ['Found',       d.found ? NT.badge('Yes','ok') : NT.badge('No — sitemap not found','error'), true],
            ['URL Count',   d.url_count || 0],
        ]);
        if (d.found && d.urls && d.urls.length) {
            h += '<br><div class="nt-section-head">URLs (' + d.urls.length + (d.url_count>d.urls.length ? ' of ' + d.url_count : '') + ')</div><br>';
            h += '<div class="nt-record-list">' + d.urls.slice(0,30).map(function(u){ return '<div class="nt-record-item" style="word-break:break-all">' + NT.esc(u) + '</div>'; }).join('') + '</div>';
        }
        NT.setHTML('sitemap-out', h);
    } catch(e) { NT.err('sitemap-out', e.message); }
};

NT.runSecurityTxt = async function() {
    var q = document.getElementById('security_txt-in').value.trim();
    if (!q) return;
    NT.loading('security_txt-out');
    try {
        var d = await NT.api('security_txt', q);
        var h = NT.kv([
            ['URL',   d.url],
            ['Found', d.found ? NT.badge('Yes — security.txt present','ok') : NT.badge('No — security.txt not found','warn'), true],
        ]);
        if (d.found && d.content) h += '<br>' + NT.pre(d.content, 4096);
        NT.setHTML('security_txt-out', h);
    } catch(e) { NT.err('security_txt-out', e.message); }
};

/* ─── Common Ports ───────────────────────────────────────────────────────── */
NT._ports = [
    [20,'FTP','Data transfer','TCP'],[21,'FTP','Control (command)','TCP'],[22,'SSH','Secure Shell','TCP'],
    [23,'Telnet','Unencrypted remote','TCP'],[25,'SMTP','Email transfer','TCP'],
    [53,'DNS','Domain name resolution','TCP/UDP'],[67,'DHCP','Server (assigns IPs)','UDP'],
    [68,'DHCP','Client','UDP'],[69,'TFTP','Trivial file transfer','UDP'],
    [80,'HTTP','Web traffic','TCP'],[110,'POP3','Email retrieval','TCP'],
    [119,'NNTP','Usenet newsgroups','TCP'],[123,'NTP','Network time protocol','UDP'],
    [143,'IMAP','Email (sync)','TCP'],[161,'SNMP','Network management','UDP'],
    [179,'BGP','Border gateway protocol','TCP'],[194,'IRC','Internet relay chat','TCP'],
    [389,'LDAP','Directory services','TCP'],[443,'HTTPS','Secure web traffic','TCP'],
    [445,'SMB','Windows file sharing','TCP'],[465,'SMTPS','Secure SMTP (legacy)','TCP'],
    [514,'Syslog','System log messages','UDP'],[587,'SMTP Submission','Email submission','TCP'],
    [636,'LDAPS','Secure LDAP','TCP'],[993,'IMAPS','Secure IMAP','TCP'],
    [995,'POP3S','Secure POP3','TCP'],[1080,'SOCKS','SOCKS proxy','TCP'],
    [1194,'OpenVPN','VPN tunnel','UDP'],[1433,'MSSQL','Microsoft SQL Server','TCP'],
    [1521,'Oracle DB','Oracle database','TCP'],[2082,'cPanel','Web hosting HTTP','TCP'],
    [2083,'cPanel','Web hosting HTTPS','TCP'],[2181,'ZooKeeper','Coordination service','TCP'],
    [2375,'Docker','Docker API (unsecured)','TCP'],[2376,'Docker','Docker API (TLS)','TCP'],
    [3000,'Node/React','Dev server (common)','TCP'],[3306,'MySQL','MySQL database','TCP'],
    [3389,'RDP','Remote Desktop Protocol','TCP'],[4222,'NATS','Messaging system','TCP'],
    [5000,'Flask/Dev','Common dev server','TCP'],[5432,'PostgreSQL','PostgreSQL database','TCP'],
    [5672,'RabbitMQ','AMQP message broker','TCP'],[5900,'VNC','Remote desktop','TCP'],
    [6379,'Redis','Redis in-memory store','TCP'],[6443,'Kubernetes','API server','TCP'],
    [7474,'Neo4j','Graph database HTTP','TCP'],[8080,'HTTP-Alt','Alternative HTTP','TCP'],
    [8443,'HTTPS-Alt','Alternative HTTPS','TCP'],[8888,'Jupyter','Notebook server','TCP'],
    [9092,'Kafka','Message streaming','TCP'],[9200,'Elasticsearch','HTTP API','TCP'],
    [9300,'Elasticsearch','Inter-node transport','TCP'],[9418,'Git','Git protocol','TCP'],
    [11211,'Memcached','In-memory caching','TCP/UDP'],[15672,'RabbitMQ','Management UI','TCP'],
    [27017,'MongoDB','MongoDB database','TCP'],[50070,'Hadoop','HDFS NameNode','TCP'],
];

NT.renderPorts = function(filter) {
    filter = (filter||'').toLowerCase();
    var rows = NT._ports.filter(function(p){
        return !filter || String(p[0]).includes(filter) || p[1].toLowerCase().includes(filter) || p[2].toLowerCase().includes(filter);
    });
    if (!rows.length) { NT.setHTML('common_ports-out', NT.badge('No ports match filter','warn')); return; }
    var h = '<div class="nt-ports-grid">';
    rows.forEach(function(p){
        h += '<div class="nt-port-card"><span class="nt-port-num">' + p[0] + '</span><div><div class="nt-port-name">' + NT.esc(p[1]) + '</div><div class="nt-port-proto">' + NT.esc(p[2]) + ' · ' + NT.esc(p[3]) + '</div></div></div>';
    });
    h += '</div>';
    NT.setHTML('common_ports-out', h);
};

NT.filterPorts = function() {
    NT.renderPorts(document.getElementById('common_ports-in').value);
};

NT.runPortLookup = function() {
    var v = document.getElementById('port_lookup-in').value.trim().toLowerCase();
    if (!v) { NT.setHTML('port_lookup-out',''); return; }
    var n = parseInt(v, 10);
    var results = NT._ports.filter(function(p){
        return String(p[0]) === v || (!isNaN(n) && p[0] === n) || p[1].toLowerCase().includes(v) || p[2].toLowerCase().includes(v);
    });
    if (!results.length) { NT.setHTML('port_lookup-out', NT.badge('No matching ports found','warn')); return; }
    NT.setHTML('port_lookup-out', NT.kv(results.map(function(p){ return [String(p[0]), p[1] + ' — ' + p[2] + ' (' + p[3] + ')']; }),'Port Lookup Results'));
};


/* ══════════════ DNS PROPAGATION & FORMATTER HANDLERS ══════════════ */
NT.runDNSPropagation = async function() {
    var q    = document.getElementById('dns_propagation-in').value.trim();
    var type = document.getElementById('dns_propagation-type').value;
    if (!q) return;
    NT.loading('dns_propagation-out');
    try {
        var d = await NT.api('dns_propagation', q, type);
        if (d.error) throw new Error(d.error);
        var h = NT.kv([['Domain', d.domain], ['Record Type', d.type]], 'DNS Propagation Check') + '<br>';
        h += '<div class="nt-record-list">';
        d.results.forEach(function(r) {
            var status = r.error ? NT.badge('Error: ' + r.error, 'error') : r.answers.length ? NT.badge(r.answers.length + ' record(s)', 'ok') : NT.badge('No records', 'warn');
            h += '<div class="nt-record-item"><span class="nt-record-type">' + NT.esc(r.resolver) + '</span>' + status;
            if (r.answers && r.answers.length) {
                h += '<div style="font-size:12px;color:var(--color-text-muted);margin-top:4px">' + r.answers.map(function(a){ return NT.esc(a); }).join(' &nbsp;·&nbsp; ') + '</div>';
            }
            h += '</div>';
        });
        h += '</div>';
        NT.setHTML('dns_propagation-out', h);
    } catch(e) { NT.err('dns_propagation-out', e.message); }
};

NT.runDNSFormatter = function() {
    var raw = document.getElementById('dns_formatter-in').value.trim();
    if (!raw) { NT.setHTML('dns_formatter-out', ''); return; }
    var lines = raw.split(/\r?\n/).filter(function(l){ return l.trim() && !l.trim().startsWith(';'); });
    var typeInfo = {
        'A':    'IPv4 address record — maps hostname to an IPv4 address',
        'AAAA': 'IPv6 address record — maps hostname to an IPv6 address',
        'MX':   'Mail Exchange — directs email to mail servers',
        'TXT':  'Text record — used for SPF, DKIM, DMARC, domain verification',
        'NS':   'Nameserver — delegates DNS zone to a nameserver',
        'CNAME':'Canonical Name — alias pointing to another hostname',
        'SOA':  'Start of Authority — zone metadata and serial number',
        'PTR':  'Pointer record — reverse DNS (IP to hostname)',
        'SRV':  'Service locator — specifies host/port for a service',
        'CAA':  'Certification Authority Authorization — allowed CAs',
    };
    var parsed = [];
    lines.forEach(function(line) {
        var m = line.match(/^(\S+)\s+(\d+)?\s*(IN\s+)?(\w+)\s+(.+)$/i);
        if (!m) { parsed.push({raw: line, error: true}); return; }
        parsed.push({name: m[1], ttl: m[2] || '—', type: (m[4]||'').toUpperCase(), data: m[5].trim(), raw: line});
    });
    var grouped = {};
    parsed.forEach(function(r) { if (!r.error) { var k = r.type || 'OTHER'; if (!grouped[k]) grouped[k] = []; grouped[k].push(r); } });
    var h = '';
    Object.keys(grouped).forEach(function(type) {
        var recs = grouped[type];
        h += '<div class="nt-section-head" style="margin:12px 0 6px">' + NT.esc(type) + ' Records (' + recs.length + ')</div>';
        if (typeInfo[type]) h += '<div style="font-size:12px;color:var(--color-text-muted);margin-bottom:8px">' + NT.esc(typeInfo[type]) + '</div>';
        h += '<div class="nt-record-list">';
        recs.forEach(function(r) {
            h += '<div class="nt-record-item"><span class="nt-record-type">' + NT.esc(r.type) + '</span>';
            h += '<strong>' + NT.esc(r.name) + '</strong> <span style="color:var(--color-text-muted)">→</span> <code style="font-size:12px">' + NT.esc(r.data) + '</code>';
            h += '<div style="font-size:11px;color:var(--color-text-muted)">TTL: ' + NT.esc(r.ttl) + '</div></div>';
        });
        h += '</div>';
    });
    if (!h) h = NT.badge('No valid DNS records found', 'warn');
    NT.setHTML('dns_formatter-out', h);
};

/* ══════════════ DOMAIN AGE / EXPIRY / AVAILABILITY HANDLERS ════════ */
NT.runDomainAge = async function() {
    var q = document.getElementById('domain_age-in').value.trim();
    if (!q) return;
    NT.loading('domain_age-out');
    try {
        var d = await NT.api('domain_age', q);
        if (d.error) throw new Error(d.error);
        var rows = [
            ['Domain',       d.domain],
            ['Created',      d.created || '—'],
            ['Last Updated', d.updated  || '—'],
            ['Expires',      d.expires  || '—'],
            ['Age',          d.age_days !== null ? d.age_years + ' years (' + d.age_days + ' days)' : '—'],
        ];
        if (d.status && d.status.length) rows.push(['Status', d.status.join(', ')]);
        NT.setHTML('domain_age-out', NT.kv(rows, 'Domain Age — ' + d.domain));
    } catch(e) { NT.err('domain_age-out', e.message); }
};

NT.runDomainExpiry = async function() {
    var q = document.getElementById('domain_expiry-in').value.trim();
    if (!q) return;
    NT.loading('domain_expiry-out');
    try {
        var d = await NT.api('domain_expiry', q);
        if (d.error) throw new Error(d.error);
        var expClass = d.expired ? 'error' : d.days_until_expiry <= 30 ? 'error' : d.days_until_expiry <= 90 ? 'warn' : 'ok';
        var expBadge = d.expired ? NT.badge('Expired', 'error') : NT.badge(d.days_until_expiry + ' days remaining', expClass);
        var rows = [
            ['Domain',         d.domain],
            ['Created',        d.created || '—'],
            ['Expires',        d.expires ? (d.expires + ' &nbsp;' + expBadge) : '—', d.expires ? true : false],
            ['Days Remaining', d.days_until_expiry !== null ? d.days_until_expiry : '—'],
        ];
        if (d.status && d.status.length) rows.push(['Status', d.status.join(', ')]);
        NT.setHTML('domain_expiry-out', NT.kv(rows, 'Domain Expiry — ' + d.domain));
    } catch(e) { NT.err('domain_expiry-out', e.message); }
};

NT.runDomainAvailability = async function() {
    var q = document.getElementById('domain_availability-in').value.trim();
    if (!q) return;
    NT.loading('domain_availability-out');
    try {
        var d = await NT.api('domain_availability', q);
        if (d.error) throw new Error(d.error);
        var avBadge = d.available ? NT.badge('Available — not registered', 'ok') : NT.badge('Taken — domain is registered', 'error');
        var rows = [['Domain', d.domain], ['Status', avBadge, true], ['Note', d.note || '—']];
        if (d.status && d.status.length) rows.push(['Registry Status', d.status.join(', ')]);
        NT.setHTML('domain_availability-out', NT.kv(rows, 'Domain Availability'));
    } catch(e) { NT.err('domain_availability-out', e.message); }
};

/* ══════════════ HTTP STATUS CHECK / CONTENT-TYPE HANDLERS ════════ */
NT.runHTTPStatusCheck = async function() {
    var q = document.getElementById('http_status_check-in').value.trim();
    if (!q) return;
    NT.loading('http_status_check-out');
    try {
        var d = await NT.api('http_status_check', q);
        if (d.error) throw new Error(d.error);
        var code = d.code;
        var codeClass = code >= 500 ? 'error' : code >= 400 ? 'error' : code >= 300 ? 'warn' : code >= 200 ? 'ok' : 'warn';
        var allSt = {};
        Object.values(NT._httpStatuses).forEach(function(g){ Object.assign(allSt, g); });
        var statusName = allSt[code] ? allSt[code][0] : '';
        var rows = [
            ['URL',           d.url],
            ['HTTP Status',   NT.badge(code + (statusName ? ' ' + statusName : ''), codeClass), true],
            ['Response Time', d.time_ms + ' ms'],
        ];
        if (d.server)       rows.push(['Server',       d.server]);
        if (d.content_type) rows.push(['Content-Type', d.content_type]);
        if (d.location)     rows.push(['Redirect To',  d.location]);
        NT.setHTML('http_status_check-out', NT.kv(rows, 'HTTP Status Check'));
    } catch(e) { NT.err('http_status_check-out', e.message); }
};

NT.runContentType = async function() {
    var q = document.getElementById('content_type-in').value.trim();
    if (!q) return;
    NT.loading('content_type-out');
    try {
        var d = await NT.api('content_type', q);
        if (d.error) throw new Error(d.error);
        var codeClass = d.code >= 200 && d.code < 300 ? 'ok' : d.code >= 400 ? 'error' : 'warn';
        NT.setHTML('content_type-out', NT.kv([
            ['URL',              d.url],
            ['HTTP Status',      NT.badge(d.code + '', codeClass), true],
            ['Content-Type',     d.content_type || '(not set)'],
            ['MIME Type',        d.mime_type || '—'],
            ['Charset',          d.charset || '(not specified)'],
            ['Content-Encoding', d.content_encoding || '(none)'],
            ['Content-Length',   d.content_length ? d.content_length + ' bytes' : '(not specified)'],
        ], 'Content-Type — ' + d.url));
    } catch(e) { NT.err('content_type-out', e.message); }
};

/* ══════════════ OPEN REDIRECT HANDLER ══════════════ */
NT.runOpenRedirect = async function() {
    var q = document.getElementById('open_redirect-in').value.trim();
    if (!q) return;
    NT.loading('open_redirect-out');
    try {
        var d = await NT.api('open_redirect', q);
        if (d.error) throw new Error(d.error);
        var vulnBadge = d.potentially_vulnerable
            ? NT.badge('Potentially Vulnerable — cross-domain redirect', 'error')
            : d.redirects ? NT.badge('Redirects within same domain — likely safe', 'warn')
            : NT.badge('No redirect detected — not vulnerable', 'ok');
        var rows = [
            ['URL',        d.url],
            ['HTTP Code',  d.code || '(no response)'],
            ['Redirects',  d.redirects ? 'Yes' : 'No'],
            ['Assessment', vulnBadge, true],
        ];
        if (d.location)    rows.push(['Redirect Location', d.location]);
        if (d.origin_host) rows.push(['Origin Host',       d.origin_host]);
        if (d.dest_host)   rows.push(['Destination Host',  d.dest_host]);
        if (d.cross_domain !== undefined) rows.push(['Cross-Domain', d.cross_domain ? NT.badge('Yes', 'error') : NT.badge('No', 'ok'), true]);
        NT.setHTML('open_redirect-out', NT.kv(rows, 'Open Redirect Check'));
    } catch(e) { NT.err('open_redirect-out', e.message); }
};

/* ══════════════ CSR / PEM / CERTIFICATE HANDLERS ════════════ */
NT.runCSRDecoder = async function() {
    var pem = document.getElementById('csr_decoder-in').value.trim();
    if (!pem) return;
    NT.loading('csr_decoder-out');
    try {
        var d = await NT.api('csr_decode', pem);
        if (d.error) throw new Error(d.error);
        var rows = [], s = d.subject || {};
        if (s.commonName)             rows.push(['Common Name (CN)',  s.commonName]);
        if (s.organizationName)       rows.push(['Organisation (O)',  s.organizationName]);
        if (s.organizationalUnitName) rows.push(['Org Unit (OU)',     s.organizationalUnitName]);
        if (s.countryName)            rows.push(['Country (C)',       s.countryName]);
        if (s.stateOrProvinceName)    rows.push(['State/Province',    s.stateOrProvinceName]);
        if (s.localityName)           rows.push(['Locality (L)',      s.localityName]);
        if (s.emailAddress)           rows.push(['Email',             s.emailAddress]);
        if (d.key && d.key.type)      rows.push(['Key Type',          d.key.type]);
        if (d.key && d.key.bits)      rows.push(['Key Size',          d.key.bits + ' bits']);
        if (!rows.length)             rows.push(['Subject',           '(empty)']);
        NT.setHTML('csr_decoder-out', NT.kv(rows, 'CSR Decoded'));
    } catch(e) { NT.err('csr_decoder-out', e.message); }
};

NT.runCSRGenerator = async function() {
    var cn      = (document.getElementById('csr_gen-cn')      || {}).value.trim();
    var org     = (document.getElementById('csr_gen-org')     || {}).value.trim();
    var ou      = (document.getElementById('csr_gen-ou')      || {}).value.trim();
    var city    = (document.getElementById('csr_gen-city')    || {}).value.trim();
    var state   = (document.getElementById('csr_gen-state')   || {}).value.trim();
    var country = (document.getElementById('csr_gen-country') || {}).value.trim();
    var bits    = (document.getElementById('csr_gen-bits')    || {}).value || '2048';
    if (!cn) { NT.setHTML('csr_generator-out', NT.badge('Please enter a Common Name (CN)', 'warn')); return; }
    NT.loading('csr_generator-out');
    try {
        var url = '/plugins/network-toolkit/api?action=csr_generate&q=' + encodeURIComponent(cn) + '&extra=' + bits +
            '&org=' + encodeURIComponent(org) + '&ou=' + encodeURIComponent(ou) +
            '&city=' + encodeURIComponent(city) + '&state=' + encodeURIComponent(state) + '&country=' + encodeURIComponent(country);
        var resp = await fetch(url);
        var d = await resp.json();
        if (d.error) throw new Error(d.error);
        var h = NT.kv([['Common Name', d.cn], ['Key Type', 'RSA ' + d.bits + '-bit']], 'Generated CSR');
        if (d.warning) h += '<div style="font-size:12px;margin:8px 0;padding:8px;background:rgba(245,158,11,.1);border-radius:4px">&#x26A0; ' + NT.esc(d.warning) + '</div>';
        h += '<div class="nt-section-head" style="margin:12px 0 6px">Certificate Signing Request (CSR)</div>' + NT.pre(d.csr);
        h += '<div class="nt-section-head" style="margin:12px 0 6px">Private Key — Keep Secret!</div>';
        h += '<div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);border-radius:5px;padding:8px;margin-bottom:6px;font-size:12px;color:var(--color-text-muted)">&#x26A0; Never share your private key. Store it securely.</div>';
        h += NT.pre(d.private_key);
        NT.setHTML('csr_generator-out', h);
    } catch(e) { NT.err('csr_generator-out', e.message); }
};

NT.runPEMDecoder = async function() {
    var pem = document.getElementById('pem_decoder-in').value.trim();
    if (!pem) return;
    NT.loading('pem_decoder-out');
    try {
        var d = await NT.api('pem_decode', pem);
        if (d.error) throw new Error(d.error);
        var expClass = d.expired ? 'error' : d.days_remaining <= 30 ? 'error' : d.days_remaining <= 90 ? 'warn' : 'ok';
        var expBadge = NT.badge(d.expired ? 'Expired' : d.days_remaining + ' days left', expClass);
        var s = d.subject || {}, i = d.issuer || {}, rows = [];
        if (s.CN) rows.push(['Subject CN',  s.CN]);
        if (s.O)  rows.push(['Subject Org', s.O]);
        if (s.C)  rows.push(['Country',     s.C]);
        if (i.CN) rows.push(['Issuer CN',   i.CN]);
        if (i.O)  rows.push(['Issuer Org',  i.O]);
        rows.push(['Valid From',     d.valid_from]);
        rows.push(['Valid To',       d.valid_to + ' &nbsp;' + expBadge, true]);
        rows.push(['Days Remaining', d.days_remaining]);
        if (d.serial)        rows.push(['Serial Number',    d.serial]);
        if (d.signature_alg) rows.push(['Signature Alg',    d.signature_alg]);
        if (d.san)           rows.push(['Subject Alt Names', d.san]);
        if (d.key_usage)     rows.push(['Key Usage',         d.key_usage]);
        if (d.ext_key_usage) rows.push(['Ext Key Usage',     d.ext_key_usage]);
        NT.setHTML('pem_decoder-out', NT.kv(rows, 'PEM Certificate Decoded'));
    } catch(e) { NT.err('pem_decoder-out', e.message); }
};

/* ─── Init ─────────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function() {
    // Pre-render static reference panels
    NT.renderHTTPStatus();
    NT.renderMIMETable();
    NT.renderPorts();
    // Init query builder with 2 rows
    NT._qbRows = [{key:'',val:''},{key:'',val:''}];
    NT._renderQBRows();
    // Auto-detect IP in background
    NT.runMyIP();
    // Start on IP Tools category
    NT.showCategory('IP Tools');
});
</script>
<?php
$content = ob_get_clean();
plugin_render('Network Toolkit', $content, [
    'slug'        => $slug,
    'desc'        => '82 network utilities — IP calculators, DNS lookups, WHOIS/RDAP, URL tools, HTTP/SSL tools, CSR generator, email tools, redirect chain tracer and more.',
    'stylesheet'  => '/plugins/' . $slug . '/assets/network-toolkit.css',
]);
