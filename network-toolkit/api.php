<?php
/**
 * Network Toolkit — PHP API backend
 * Accessible at /plugins/network-toolkit/api?action=...&q=...&extra=...
 */
defined('AWAN') or define('AWAN', true);

header('Content-Type: application/json; charset=utf-8');
header('X-Robots-Tag: noindex');
header('Cache-Control: no-store');

$action = trim($_GET['action'] ?? '');
$q      = trim($_GET['q']      ?? '');
$extra  = trim($_GET['extra']  ?? '');

if (!$action) { echo json_encode(['error' => 'No action specified']); exit; }

/* ─── HTTP fetch helper ──────────────────────────────────────────────────── */
function nt_fetch(string $url, array $opts = []): array {
    $timeout = (int)($opts['timeout'] ?? 12);
    $method  = strtoupper($opts['method'] ?? 'GET');
    $follow  = $opts['follow'] ?? true;
    $extraH  = $opts['headers'] ?? [];

    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_FOLLOWLOCATION => $follow,
            CURLOPT_MAXREDIRS      => $follow ? 10 : 0,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HEADER         => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; AwanTools/1.0; +https://awantools.site)',
            CURLOPT_HTTPHEADER     => $extraH,
            CURLOPT_ENCODING       => '',
        ]);
        $raw   = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        $info  = curl_getinfo($ch);
        curl_close($ch);

        if ($errno || $raw === false) {
            return ['error' => $errmsg ?: 'cURL error', 'body' => '', 'headers' => [], 'raw_headers' => '', 'code' => 0, 'time' => 0];
        }

        $hSize = $info['header_size'];
        $rawH  = substr($raw, 0, $hSize);
        $body  = substr($raw, $hSize);
        $hdrs  = nt_parse_headers($rawH);

        return ['body' => $body, 'headers' => $hdrs, 'raw_headers' => trim($rawH), 'code' => (int)$info['http_code'], 'time' => (float)$info['total_time'], 'final_url' => $info['url'] ?? $url, 'error' => null];
    }

    // Fallback: file_get_contents
    $ctx = stream_context_create([
        'http' => ['method' => $method, 'timeout' => $timeout, 'follow_location' => $follow ? 1 : 0, 'ignore_errors' => true, 'user_agent' => 'Mozilla/5.0 (compatible; AwanTools/1.0)'],
        'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
    ]);
    $t0   = microtime(true);
    $body = @file_get_contents($url, false, $ctx);
    $t1   = microtime(true);
    $rawH = implode("\r\n", $http_response_header ?? []);
    $hdrs = nt_parse_headers($rawH);
    return ['body' => $body ?: '', 'headers' => $hdrs, 'raw_headers' => $rawH, 'code' => 0, 'time' => $t1 - $t0, 'error' => $body === false ? 'Failed to fetch' : null];
}

function nt_parse_headers(string $raw): array {
    $out = [];
    foreach (explode("\n", $raw) as $line) {
        $line = trim($line);
        if (strpos($line, ':') !== false) {
            [$k, $v] = explode(':', $line, 2);
            $k = strtolower(trim($k));
            $v = trim($v);
            // For duplicate headers store last value; or accumulate for set-cookie
            if ($k === 'set-cookie') {
                $out[$k]   = isset($out[$k]) ? $out[$k] . "\n" . $v : $v;
            } else {
                $out[$k] = $v;
            }
        }
    }
    return $out;
}

function nt_clean_host(string $q): string {
    $q = preg_replace('#^https?://#i', '', $q);
    $q = explode('/', $q)[0];
    return strtolower(trim($q));
}

function nt_clean_url(string $q): string {
    if (!preg_match('#^https?://#i', $q)) $q = 'https://' . $q;
    return $q;
}

function nt_dns(string $domain, int $type): array {
    $r = @dns_get_record($domain, $type);
    return is_array($r) ? $r : [];
}

/* ─── Actions ────────────────────────────────────────────────────────────── */
switch ($action) {

    /* ── My IP ── */
    case 'my_ip':
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ip = trim(explode(',', $ip)[0]);
        echo json_encode(['ip' => $ip]);
        break;

    /* ── IP Geolocation / ASN ── */
    case 'ip_geo':
        if (!$q) { echo json_encode(['error' => 'No IP or domain specified']); break; }
        $target = $q;
        // Resolve domain to IP if needed
        if (!filter_var($q, FILTER_VALIDATE_IP)) {
            $resolved = @gethostbyname(nt_clean_host($q));
            $target = ($resolved && $resolved !== nt_clean_host($q)) ? $resolved : $q;
        }
        $r = nt_fetch("http://ip-api.com/json/{$target}?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,asname,mobile,proxy,hosting,query");
        if ($r['error']) { echo json_encode(['error' => $r['error']]); break; }
        $data = json_decode($r['body'], true) ?? [];
        if (($data['status'] ?? '') === 'fail') { echo json_encode(['error' => $data['message'] ?? 'Lookup failed']); break; }
        echo json_encode($data);
        break;

    /* ── DNS lookup ── */
    case 'dns':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $typeStr = strtoupper($extra ?: 'A');
        $map = ['A' => DNS_A, 'AAAA' => DNS_AAAA, 'MX' => DNS_MX, 'TXT' => DNS_TXT, 'NS' => DNS_NS,
                'CNAME' => DNS_CNAME, 'SOA' => DNS_SOA, 'SRV' => DNS_SRV, 'PTR' => DNS_PTR,
                'CAA' => DNS_CAA, 'ANY' => DNS_ALL];
        $dnsConst = $map[$typeStr] ?? DNS_A;
        $records  = nt_dns($q, $dnsConst);
        echo json_encode(['type' => $typeStr, 'domain' => $q, 'count' => count($records), 'records' => $records]);
        break;

    /* ── DNS Zone Viewer (all types) ── */
    case 'dns_zone':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $zone = [];
        foreach (['A' => DNS_A, 'AAAA' => DNS_AAAA, 'MX' => DNS_MX, 'TXT' => DNS_TXT,
                  'NS' => DNS_NS, 'CNAME' => DNS_CNAME, 'SOA' => DNS_SOA, 'SRV' => DNS_SRV, 'CAA' => DNS_CAA] as $t => $c) {
            $recs = nt_dns($q, $c);
            if ($recs) $zone[$t] = $recs;
        }
        echo json_encode(['domain' => $q, 'zone' => $zone]);
        break;

    /* ── Reverse DNS ── */
    case 'rdns':
        if (!$q) { echo json_encode(['error' => 'No IP specified']); break; }
        $host = @gethostbyaddr($q);
        echo json_encode(['ip' => $q, 'host' => $host, 'resolved' => ($host !== false && $host !== $q)]);
        break;

    /* ── Domain → IP ── */
    case 'domain_ip':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $host   = nt_clean_host($q);
        $a    = nt_dns($host, DNS_A);
        $aaaa = nt_dns($host, DNS_AAAA);
        $ip4  = array_values(array_unique(array_column($a, 'ip')));
        $ip6  = array_values(array_unique(array_column($aaaa, 'ipv6')));
        if (empty($ip4) && empty($ip6)) {
            $fb = @gethostbyname($host);
            if ($fb && $fb !== $host) $ip4 = [$fb];
        }
        echo json_encode(['domain' => $host, 'ipv4' => $ip4, 'ipv6' => $ip6]);
        break;

    /* ── WHOIS/RDAP ── */
    case 'whois':
        if (!$q) { echo json_encode(['error' => 'No domain or IP specified']); break; }
        $target = nt_clean_host($q);
        // Try IP RDAP first, then domain RDAP
        if (filter_var($target, FILTER_VALIDATE_IP)) {
            $url = "https://rdap.arin.net/registry/ip/{$target}";
        } else {
            $url = "https://rdap.org/domain/{$target}";
        }
        $r = nt_fetch($url, ['timeout' => 12]);
        if ($r['error'] || $r['code'] >= 400) {
            // fallback to IANA
            $tld = strtolower(substr(strrchr($target, '.'), 1));
            $r2  = nt_fetch("https://rdap.iana.org/domain/{$tld}");
            if (!$r2['error'] && $r2['code'] < 400) { echo json_encode(json_decode($r2['body'], true)); break; }
            echo json_encode(['error' => 'RDAP lookup failed. The registry may not support RDAP for this TLD.']); break;
        }
        $data = json_decode($r['body'], true);
        echo json_encode($data ?: ['error' => 'Invalid RDAP response']);
        break;

    /* ── Reverse IP ── */
    case 'reverse_ip':
        if (!$q) { echo json_encode(['error' => 'No IP specified']); break; }
        $ip = filter_var($q, FILTER_VALIDATE_IP) ? $q : (@gethostbyname(nt_clean_host($q)) ?: $q);
        $r  = nt_fetch("http://ip-api.com/json/{$ip}?fields=status,message,query,isp,org,as,asname,hosting,proxy,mobile,country,regionName,city");
        if ($r['error']) { echo json_encode(['error' => $r['error']]); break; }
        $data = json_decode($r['body'], true) ?? [];
        $host = @gethostbyaddr($ip);
        $data['rdns'] = ($host && $host !== $ip) ? $host : null;
        $data['ip']   = $ip;
        echo json_encode($data);
        break;

    /* ── HTTP Headers ── */
    case 'http_headers':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => false, 'timeout' => 12]);
        if ($r['error'] || empty($r['headers'])) {
            $r = nt_fetch($url, ['method' => 'GET', 'follow' => false, 'timeout' => 12]);
        }
        echo json_encode(['url' => $url, 'code' => $r['code'], 'headers' => $r['headers'],
            'raw' => $r['raw_headers'], 'time_ms' => (int)round($r['time'] * 1000), 'error' => $r['error']]);
        break;

    /* ── Security Headers ── */
    case 'security_headers':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => true, 'timeout' => 12]);
        if ($r['error'] || empty($r['headers'])) $r = nt_fetch($url, ['method' => 'GET', 'follow' => true, 'timeout' => 12]);
        $h = $r['headers'];
        $checks = [
            ['key' => 'strict-transport-security', 'name' => 'Strict-Transport-Security',   'critical' => true],
            ['key' => 'content-security-policy',   'name' => 'Content-Security-Policy',      'critical' => true],
            ['key' => 'x-frame-options',            'name' => 'X-Frame-Options',              'critical' => true],
            ['key' => 'x-content-type-options',     'name' => 'X-Content-Type-Options',       'critical' => true],
            ['key' => 'referrer-policy',             'name' => 'Referrer-Policy',              'critical' => false],
            ['key' => 'permissions-policy',          'name' => 'Permissions-Policy',           'critical' => false],
            ['key' => 'cross-origin-opener-policy',  'name' => 'Cross-Origin-Opener-Policy',   'critical' => false],
            ['key' => 'cross-origin-embedder-policy','name' => 'Cross-Origin-Embedder-Policy', 'critical' => false],
            ['key' => 'x-xss-protection',            'name' => 'X-XSS-Protection (deprecated)','critical' => false],
        ];
        foreach ($checks as &$c) {
            $c['present'] = isset($h[$c['key']]);
            $c['value']   = $h[$c['key']] ?? null;
        }
        echo json_encode(['url' => $url, 'code' => $r['code'], 'checks' => $checks, 'all_headers' => $h, 'error' => $r['error']]);
        break;

    /* ── Cache Headers ── */
    case 'cache_headers':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => true, 'timeout' => 12]);
        if ($r['error'] || empty($r['headers'])) $r = nt_fetch($url, ['method' => 'GET', 'follow' => true, 'timeout' => 12]);
        $h = $r['headers'];
        $cacheKeys = ['cache-control','expires','etag','last-modified','vary','age','pragma','x-cache','cf-cache-status','surrogate-control'];
        $cacheH = [];
        foreach ($cacheKeys as $k) { if (isset($h[$k])) $cacheH[$k] = $h[$k]; }
        echo json_encode(['url' => $url, 'code' => $r['code'], 'cache_headers' => $cacheH, 'all_headers' => $h, 'error' => $r['error']]);
        break;

    /* ── Redirect Chain ── */
    case 'redirect_chain':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $current = nt_clean_url($q);
        $chain   = [];
        for ($i = 0; $i < 12; $i++) {
            $r = nt_fetch($current, ['method' => 'HEAD', 'follow' => false, 'timeout' => 8]);
            if ($r['error']) { $chain[] = ['url' => $current, 'code' => 0, 'location' => null, 'error' => $r['error']]; break; }
            $loc = $r['headers']['location'] ?? null;
            $chain[] = ['url' => $current, 'code' => $r['code'], 'location' => $loc, 'time_ms' => (int)round($r['time'] * 1000)];
            if ($r['code'] < 300 || $r['code'] >= 400 || !$loc) break;
            if (!preg_match('#^https?://#i', $loc)) {
                $p   = parse_url($current);
                $loc = ($p['scheme'] ?? 'https') . '://' . ($p['host'] ?? '') . $loc;
            }
            $current = $loc;
        }
        echo json_encode(['original' => nt_clean_url($q), 'final' => $current, 'hops' => count($chain), 'chain' => $chain]);
        break;

    /* ── HTTP Method Tester ── */
    case 'http_method':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $methods = ['GET','HEAD','POST','PUT','DELETE','OPTIONS','PATCH'];
        $results = [];
        foreach ($methods as $m) {
            $r = nt_fetch($url, ['method' => $m, 'follow' => false, 'timeout' => 6]);
            $results[] = ['method' => $m, 'code' => $r['code'], 'error' => $r['error']];
        }
        echo json_encode(['url' => $url, 'results' => $results]);
        break;

    /* ── Website Availability ── */
    case 'availability':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $t0  = microtime(true);
        $r   = nt_fetch($url, ['method' => 'HEAD', 'follow' => true, 'timeout' => 15]);
        if ($r['error'] || $r['code'] === 0) $r = nt_fetch($url, ['method' => 'GET', 'follow' => true, 'timeout' => 15]);
        $ms  = (int)round((microtime(true) - $t0) * 1000);
        echo json_encode(['url' => $url, 'code' => $r['code'], 'available' => (!$r['error'] && $r['code'] >= 200 && $r['code'] < 500),
            'time_ms' => $ms, 'error' => $r['error'], 'server' => $r['headers']['server'] ?? null]);
        break;

    /* ── Website Response Time ── */
    case 'response_time':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $times = [];
        for ($i = 0; $i < 3; $i++) {
            $t0 = microtime(true);
            $r  = nt_fetch($url, ['method' => 'HEAD', 'follow' => true, 'timeout' => 12]);
            $times[] = (int)round((microtime(true) - $t0) * 1000);
        }
        echo json_encode(['url' => $url, 'times_ms' => $times, 'avg_ms' => (int)round(array_sum($times) / 3), 'min_ms' => min($times), 'max_ms' => max($times)]);
        break;

    /* ── Server Information ── */
    case 'server_info':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => true, 'timeout' => 12]);
        if ($r['error'] || empty($r['headers'])) $r = nt_fetch($url, ['method' => 'GET', 'follow' => true, 'timeout' => 12]);
        $h = $r['headers'];
        echo json_encode(['url' => $url, 'code' => $r['code'], 'time_ms' => (int)round($r['time'] * 1000),
            'server' => $h['server'] ?? null, 'x-powered-by' => $h['x-powered-by'] ?? null,
            'content-type' => $h['content-type'] ?? null, 'via' => $h['via'] ?? null,
            'cf-ray' => $h['cf-ray'] ?? null, 'x-cache' => $h['x-cache'] ?? null,
            'x-request-id' => $h['x-request-id'] ?? null, 'alt-svc' => $h['alt-svc'] ?? null,
            'all' => $h, 'error' => $r['error']]);
        break;

    /* ── SSL Certificate ── */
    case 'ssl_cert':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $host = nt_clean_host($q);
        $port = (int)($extra ?: 443);
        if (!function_exists('openssl_x509_parse')) { echo json_encode(['error' => 'OpenSSL extension not available on this server']); break; }
        $ctx  = stream_context_create(['ssl' => ['capture_peer_cert' => true, 'capture_peer_cert_chain' => true, 'verify_peer' => false, 'verify_peer_name' => false, 'SNI_enabled' => true, 'peer_name' => $host]]);
        $sock = @stream_socket_client("ssl://{$host}:{$port}", $errno, $errstr, 12, STREAM_CLIENT_CONNECT, $ctx);
        if (!$sock) { echo json_encode(['error' => "TLS handshake failed: {$errstr} (errno {$errno})"]); break; }
        $params = stream_context_get_params($sock);
        fclose($sock);
        $cert  = $params['options']['ssl']['peer_certificate'] ?? null;
        $chain = $params['options']['ssl']['peer_certificate_chain'] ?? [];
        if (!$cert) { echo json_encode(['error' => 'Could not retrieve certificate from server']); break; }
        $info = openssl_x509_parse($cert);
        $pem  = '';
        openssl_x509_export($cert, $pem);
        $chainInfo = [];
        foreach ($chain as $cc) {
            $ci = openssl_x509_parse($cc);
            $chainInfo[] = ['subject' => $ci['subject'] ?? [], 'issuer' => $ci['issuer'] ?? [], 'valid_to' => date('Y-m-d H:i:s', $ci['validTo_time_t'] ?? 0)];
        }
        $now = time();
        $validTo = $info['validTo_time_t'] ?? 0;
        echo json_encode([
            'host'           => $host, 'port' => $port,
            'subject'        => $info['subject'] ?? [],
            'issuer'         => $info['issuer'] ?? [],
            'valid_from'     => date('Y-m-d H:i:s', $info['validFrom_time_t'] ?? 0),
            'valid_to'       => date('Y-m-d H:i:s', $validTo),
            'valid_from_ts'  => $info['validFrom_time_t'] ?? 0,
            'valid_to_ts'    => $validTo,
            'days_remaining' => $validTo ? max(0, (int)floor(($validTo - $now) / 86400)) : 0,
            'expired'        => $validTo < $now,
            'serial'         => $info['serialNumberHex'] ?? '',
            'version'        => $info['version'] ?? '',
            'signature_alg'  => $info['signatureTypeSN'] ?? '',
            'san'            => $info['extensions']['subjectAltName'] ?? '',
            'extensions'     => $info['extensions'] ?? [],
            'chain'          => $chainInfo,
            'chain_length'   => count($chainInfo),
            'pem'            => $pem,
        ]);
        break;

    /* ── Robots.txt ── */
    case 'robots':
        if (!$q) { echo json_encode(['error' => 'No domain/URL specified']); break; }
        $host = nt_clean_host($q);
        foreach (["https://{$host}/robots.txt", "http://{$host}/robots.txt"] as $url) {
            $r = nt_fetch($url, ['timeout' => 10]);
            if (!$r['error'] && $r['code'] === 200) {
                echo json_encode(['url' => $url, 'found' => true, 'code' => 200, 'content' => substr($r['body'], 0, 16384)]);
                exit;
            }
        }
        echo json_encode(['url' => "https://{$host}/robots.txt", 'found' => false, 'code' => $r['code'] ?? 0]);
        break;

    /* ── Sitemap ── */
    case 'sitemap':
        if (!$q) { echo json_encode(['error' => 'No domain/URL specified']); break; }
        $host = nt_clean_host($q);
        $candidates = ["https://{$host}/sitemap.xml", "https://{$host}/sitemap_index.xml"];
        // Check robots.txt for sitemap directive
        $rb = nt_fetch("https://{$host}/robots.txt", ['timeout' => 6]);
        if (!$rb['error'] && $rb['code'] === 200) {
            preg_match_all('/^Sitemap:\s*(.+)$/im', $rb['body'], $sm);
            if (!empty($sm[1])) array_unshift($candidates, ...array_map('trim', $sm[1]));
        }
        $candidates = array_unique($candidates);
        foreach ($candidates as $url) {
            $r = nt_fetch($url, ['timeout' => 10]);
            if (!$r['error'] && $r['code'] === 200) {
                preg_match_all('/<loc>(.*?)<\/loc>/is', $r['body'], $locs);
                $urls = array_map('trim', $locs[1]);
                echo json_encode(['url' => $url, 'found' => true, 'url_count' => count($urls), 'urls' => array_slice($urls, 0, 50), 'raw' => substr($r['body'], 0, 8192)]);
                exit;
            }
        }
        echo json_encode(['url' => $candidates[0] ?? '', 'found' => false]);
        break;

    /* ── Security.txt ── */
    case 'security_txt':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $host = nt_clean_host($q);
        foreach (["https://{$host}/.well-known/security.txt", "https://{$host}/security.txt"] as $url) {
            $r = nt_fetch($url, ['timeout' => 8]);
            if (!$r['error'] && $r['code'] === 200) {
                echo json_encode(['url' => $url, 'found' => true, 'content' => substr($r['body'], 0, 8192)]);
                exit;
            }
        }
        echo json_encode(['url' => "https://{$host}/.well-known/security.txt", 'found' => false]);
        break;

    /* ── SPF check ── */
    case 'spf':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $domain = nt_clean_host($q);
        $recs   = nt_dns($domain, DNS_TXT);
        $spf    = null;
        $allTxt = [];
        foreach ($recs as $r) {
            $txt = $r['txt'] ?? ($r['entries'][0] ?? '');
            $allTxt[] = $txt;
            if (str_starts_with($txt, 'v=spf1') || str_starts_with($txt, 'v=SPF1')) $spf = $txt;
        }
        echo json_encode(['domain' => $domain, 'found' => (bool)$spf, 'spf' => $spf, 'all_txt' => $allTxt]);
        break;

    /* ── DMARC check ── */
    case 'dmarc':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $domain      = nt_clean_host($q);
        $dmarcDomain = "_dmarc.{$domain}";
        $recs        = nt_dns($dmarcDomain, DNS_TXT);
        $dmarc       = null;
        foreach ($recs as $r) {
            $txt = $r['txt'] ?? ($r['entries'][0] ?? '');
            if (stripos($txt, 'v=DMARC1') === 0) { $dmarc = $txt; break; }
        }
        echo json_encode(['domain' => $domain, 'dmarc_domain' => $dmarcDomain, 'found' => (bool)$dmarc, 'dmarc' => $dmarc, 'records' => $recs]);
        break;

    /* ── DKIM check ── */
    case 'dkim':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $domain   = nt_clean_host($q);
        $selector = $extra ?: 'default';
        $dkimD    = "{$selector}._domainkey.{$domain}";
        $recs     = nt_dns($dkimD, DNS_TXT);
        $dkim     = null;
        foreach ($recs as $r) {
            $txt = $r['txt'] ?? ($r['entries'][0] ?? '');
            if (stripos($txt, 'v=DKIM1') !== false || stripos($txt, 'k=rsa') !== false || stripos($txt, 'p=') !== false) { $dkim = $txt; break; }
        }
        echo json_encode(['domain' => $domain, 'selector' => $selector, 'dkim_domain' => $dkimD, 'found' => (bool)$dkim, 'dkim' => $dkim, 'records' => $recs]);
        break;

    /* ── MX Lookup ── */
    case 'mx':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $domain = nt_clean_host($q);
        $recs   = nt_dns($domain, DNS_MX);
        usort($recs, fn($a, $b) => ($a['pri'] ?? 0) <=> ($b['pri'] ?? 0));
        $out = [];
        foreach ($recs as $r) {
            $mx   = $r['target'] ?? $r['exchange'] ?? '';
            $ips  = nt_dns($mx, DNS_A);
            $out[] = ['priority' => $r['pri'] ?? 0, 'host' => $mx, 'ip' => array_column($ips, 'ip')];
        }
        echo json_encode(['domain' => $domain, 'count' => count($out), 'mx' => $out]);
        break;

    /* ── URL Redirect Check ── */
    case 'url_redirect':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => false, 'timeout' => 10]);
        echo json_encode(['url' => $url, 'code' => $r['code'], 'location' => $r['headers']['location'] ?? null,
            'redirects' => ($r['code'] >= 300 && $r['code'] < 400), 'error' => $r['error']]);
        break;

    /* ── DNS Propagation Checker ── */
    case 'dns_propagation':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $typeStr = strtoupper($extra ?: 'A');
        $dnsTypeMap = ['A' => DNS_A, 'AAAA' => DNS_AAAA, 'MX' => DNS_MX, 'TXT' => DNS_TXT, 'NS' => DNS_NS, 'CNAME' => DNS_CNAME];
        $dnsConst = $dnsTypeMap[$typeStr] ?? DNS_A;
        $resolvers = [
            ['name' => 'Google Public DNS',   'url' => "https://dns.google/resolve?name={$q}&type={$typeStr}"],
            ['name' => 'Cloudflare (1.1.1.1)','url' => "https://cloudflare-dns.com/dns-query?name={$q}&type={$typeStr}"],
            ['name' => 'Quad9',               'url' => "https://dns.quad9.net:5053/dns-query?name={$q}&type={$typeStr}"],
        ];
        $results = [];
        foreach ($resolvers as $res) {
            $r = nt_fetch($res['url'], ['timeout' => 8, 'headers' => ['Accept: application/dns-json']]);
            $data = (!$r['error'] && $r['body']) ? (json_decode($r['body'], true) ?? []) : [];
            $answers = [];
            foreach (($data['Answer'] ?? []) as $ans) { if (isset($ans['data'])) $answers[] = $ans['data']; }
            $errMsg = $r['error'] ? $r['error'] : (($data['Status'] ?? 0) !== 0 ? 'NXDOMAIN' : null);
            $results[] = ['resolver' => $res['name'], 'answers' => $answers, 'error' => $errMsg];
        }
        $sysRecs = nt_dns($q, $dnsConst);
        $sysAnswers = [];
        foreach ($sysRecs as $rec) { $sysAnswers[] = $rec['ip'] ?? $rec['ipv6'] ?? $rec['target'] ?? (isset($rec['txt']) ? $rec['txt'] : (isset($rec['entries'][0]) ? $rec['entries'][0] : '')); }
        $results[] = ['resolver' => 'System Resolver', 'answers' => array_filter($sysAnswers), 'error' => null];
        echo json_encode(['domain' => $q, 'type' => $typeStr, 'results' => $results]);
        break;

    /* ── Domain Age Checker ── */
    case 'domain_age':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $target = nt_clean_host($q);
        $r = nt_fetch("https://rdap.org/domain/{$target}", ['timeout' => 12]);
        if ($r['error'] || $r['code'] >= 400) { echo json_encode(['error' => 'RDAP lookup failed — domain may not be registered or RDAP not supported for this TLD']); break; }
        $data = json_decode($r['body'], true);
        if (!$data) { echo json_encode(['error' => 'Invalid RDAP response']); break; }
        $created = null; $updated = null; $expires = null;
        foreach (($data['events'] ?? []) as $e) {
            $action = strtolower($e['eventAction'] ?? '');
            $date   = isset($e['eventDate']) ? substr($e['eventDate'], 0, 10) : null;
            if (in_array($action, ['registration','domain registration'])) $created = $date;
            elseif (in_array($action, ['last changed','expiration'])) { if ($action === 'expiration') $expires = $date; else $updated = $date; }
        }
        $ageDays = null; $ageYears = null;
        if ($created) {
            $ct = strtotime($created);
            $ageDays  = (int)floor((time() - $ct) / 86400);
            $ageYears = round($ageDays / 365.25, 1);
        }
        echo json_encode(['domain' => $target, 'created' => $created, 'updated' => $updated, 'expires' => $expires, 'age_days' => $ageDays, 'age_years' => $ageYears, 'status' => $data['status'] ?? [], 'registrar' => null]);
        break;

    /* ── Domain Expiry Checker ── */
    case 'domain_expiry':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $target = nt_clean_host($q);
        $r = nt_fetch("https://rdap.org/domain/{$target}", ['timeout' => 12]);
        if ($r['error'] || $r['code'] >= 400) { echo json_encode(['error' => 'RDAP lookup failed — domain may not be registered or RDAP not supported for this TLD']); break; }
        $data = json_decode($r['body'], true);
        if (!$data) { echo json_encode(['error' => 'Invalid RDAP response']); break; }
        $created = null; $expires = null;
        foreach (($data['events'] ?? []) as $e) {
            $action = strtolower($e['eventAction'] ?? '');
            $date   = isset($e['eventDate']) ? substr($e['eventDate'], 0, 10) : null;
            if (in_array($action, ['registration','domain registration'])) $created = $date;
            if ($action === 'expiration') $expires = $date;
        }
        $daysUntilExpiry = null; $expired = false;
        if ($expires) {
            $et = strtotime($expires);
            $daysUntilExpiry = (int)floor(($et - time()) / 86400);
            $expired = $daysUntilExpiry < 0;
        }
        echo json_encode(['domain' => $target, 'created' => $created, 'expires' => $expires, 'days_until_expiry' => $daysUntilExpiry, 'expired' => $expired, 'status' => $data['status'] ?? []]);
        break;

    /* ── Domain Availability Checker ── */
    case 'domain_availability':
        if (!$q) { echo json_encode(['error' => 'No domain specified']); break; }
        $domain = nt_clean_host($q);
        $r = nt_fetch("https://rdap.org/domain/{$domain}", ['timeout' => 10]);
        if ($r['code'] === 404 || ($r['code'] >= 400 && $r['code'] < 500)) {
            $a = nt_dns($domain, DNS_A); $ns = nt_dns($domain, DNS_NS);
            $hasDNS = !empty($a) || !empty($ns);
            echo json_encode(['domain' => $domain, 'available' => !$hasDNS, 'rdap_code' => $r['code'], 'has_dns' => $hasDNS, 'note' => $hasDNS ? 'DNS records exist — likely registered but RDAP unavailable' : 'No registry record found — domain appears to be available']);
            break;
        }
        if ($r['error']) { echo json_encode(['error' => 'Lookup failed: ' . $r['error']]); break; }
        $data = json_decode($r['body'], true) ?? [];
        echo json_encode(['domain' => $domain, 'available' => false, 'rdap_code' => $r['code'], 'has_dns' => true, 'status' => $data['status'] ?? [], 'note' => 'Domain is registered']);
        break;

    /* ── HTTP Status Checker (live URL) ── */
    case 'http_status_check':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => false, 'timeout' => 10]);
        if ($r['error'] || $r['code'] === 0) $r = nt_fetch($url, ['method' => 'GET', 'follow' => false, 'timeout' => 10]);
        $location = $r['headers']['location'] ?? null;
        echo json_encode(['url' => $url, 'code' => $r['code'], 'time_ms' => (int)round($r['time'] * 1000), 'location' => $location, 'server' => $r['headers']['server'] ?? null, 'content_type' => $r['headers']['content-type'] ?? null, 'error' => $r['error']]);
        break;

    /* ── Content-Type Checker ── */
    case 'content_type':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => true, 'timeout' => 10]);
        if ($r['error'] || empty($r['headers'])) $r = nt_fetch($url, ['method' => 'GET', 'follow' => true, 'timeout' => 10]);
        $ct = $r['headers']['content-type'] ?? null;
        $parts = $ct ? explode(';', $ct) : [];
        $mime = trim($parts[0] ?? '');
        $charset = null;
        foreach ($parts as $p) { if (stripos(trim($p), 'charset') !== false) { $bits = explode('=', $p, 2); $charset = trim($bits[1] ?? ''); } }
        echo json_encode(['url' => $url, 'code' => $r['code'], 'content_type' => $ct, 'mime_type' => $mime, 'charset' => $charset, 'content_length' => $r['headers']['content-length'] ?? null, 'content_encoding' => $r['headers']['content-encoding'] ?? null, 'error' => $r['error']]);
        break;

    /* ── Open Redirect Checker ── */
    case 'open_redirect':
        if (!$q) { echo json_encode(['error' => 'No URL specified']); break; }
        $url = nt_clean_url($q);
        $r = nt_fetch($url, ['method' => 'HEAD', 'follow' => false, 'timeout' => 10]);
        $code = $r['code'];
        $location = $r['headers']['location'] ?? null;
        $isRedirect = ($code >= 300 && $code < 400 && $location);
        $originHost = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        $destHost   = $location ? strtolower(parse_url($location, PHP_URL_HOST) ?? '') : '';
        $crossDomain = $isRedirect && $destHost && $destHost !== $originHost;
        echo json_encode(['url' => $url, 'code' => $code, 'redirects' => $isRedirect, 'location' => $location, 'origin_host' => $originHost, 'dest_host' => $destHost ?: null, 'cross_domain' => $crossDomain, 'potentially_vulnerable' => $crossDomain, 'error' => $r['error']]);
        break;

    /* ── CSR Decoder ── */
    case 'csr_decode':
        if (!$q) { echo json_encode(['error' => 'No CSR provided']); break; }
        if (!function_exists('openssl_csr_get_subject')) { echo json_encode(['error' => 'OpenSSL extension not available on this server']); break; }
        $pem = (strpos($q, '-----BEGIN') === false)
            ? "-----BEGIN CERTIFICATE REQUEST-----\n" . wordwrap(trim($q), 64, "\n", true) . "\n-----END CERTIFICATE REQUEST-----"
            : $q;
        $subject = @openssl_csr_get_subject($pem, false);
        if (!$subject) { echo json_encode(['error' => 'Invalid CSR — paste a PEM-encoded Certificate Signing Request (-----BEGIN CERTIFICATE REQUEST-----)']); break; }
        $pubKey = @openssl_csr_get_public_key($pem, false);
        $keyInfo = [];
        if ($pubKey) {
            $det = @openssl_pkey_get_details($pubKey);
            if ($det) $keyInfo = ['bits' => $det['bits'], 'type' => match($det['type'] ?? -1) { OPENSSL_KEYTYPE_RSA => 'RSA', OPENSSL_KEYTYPE_EC => 'EC', OPENSSL_KEYTYPE_DSA => 'DSA', default => 'Unknown' }];
        }
        echo json_encode(['subject' => $subject, 'key' => $keyInfo]);
        break;

    /* ── PEM Certificate Decoder (pasted input) ── */
    case 'pem_decode':
        if (!$q) { echo json_encode(['error' => 'No certificate provided']); break; }
        if (!function_exists('openssl_x509_parse')) { echo json_encode(['error' => 'OpenSSL extension not available']); break; }
        $pem = (strpos($q, '-----BEGIN') === false)
            ? "-----BEGIN CERTIFICATE-----\n" . wordwrap(trim($q), 64, "\n", true) . "\n-----END CERTIFICATE-----"
            : $q;
        $info = @openssl_x509_parse($pem);
        if (!$info) { echo json_encode(['error' => 'Invalid certificate — paste a PEM-encoded certificate (-----BEGIN CERTIFICATE-----)']); break; }
        $now = time(); $validTo = $info['validTo_time_t'] ?? 0;
        echo json_encode([
            'subject' => $info['subject'] ?? [], 'issuer' => $info['issuer'] ?? [],
            'valid_from' => date('Y-m-d H:i:s', $info['validFrom_time_t'] ?? 0),
            'valid_to'   => date('Y-m-d H:i:s', $validTo),
            'days_remaining' => $validTo ? max(0, (int)floor(($validTo - $now) / 86400)) : 0,
            'expired' => $validTo < $now,
            'serial' => $info['serialNumberHex'] ?? '', 'version' => $info['version'] ?? '',
            'signature_alg' => $info['signatureTypeSN'] ?? '',
            'san' => $info['extensions']['subjectAltName'] ?? '',
            'key_usage' => $info['extensions']['keyUsage'] ?? '',
            'ext_key_usage' => $info['extensions']['extendedKeyUsage'] ?? '',
        ]);
        break;

    /* ── CSR Generator ── */
    case 'csr_generate':
        if (!function_exists('openssl_pkey_new')) { echo json_encode(['error' => 'OpenSSL extension not available']); break; }
        $cn      = trim($q ?: 'example.com');
        $org     = trim($_GET['org']     ?? '');
        $ou      = trim($_GET['ou']      ?? '');
        $city    = trim($_GET['city']    ?? '');
        $state   = trim($_GET['state']   ?? '');
        $country = strtoupper(substr(trim($_GET['country'] ?? 'US'), 0, 2));
        $bits    = in_array((int)($extra ?: 2048), [2048, 4096]) ? (int)($extra ?: 2048) : 2048;
        $dn = ['commonName' => $cn];
        if ($org)     $dn['organizationName']       = $org;
        if ($ou)      $dn['organizationalUnitName'] = $ou;
        if ($city)    $dn['localityName']            = $city;
        if ($state)   $dn['stateOrProvinceName']     = $state;
        if ($country) $dn['countryName']             = $country;
        $privKey = @openssl_pkey_new(['private_key_bits' => $bits, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        if (!$privKey) { echo json_encode(['error' => 'Failed to generate private key']); break; }
        $csr = @openssl_csr_new($dn, $privKey, ['digest_alg' => 'sha256']);
        if (!$csr) { echo json_encode(['error' => 'Failed to generate CSR']); break; }
        $csrPem = ''; openssl_csr_export($csr, $csrPem);
        $keyPem = ''; openssl_pkey_export($privKey, $keyPem);
        echo json_encode(['csr' => $csrPem, 'private_key' => $keyPem, 'cn' => $cn, 'bits' => $bits, 'warning' => 'Keep your private key secret — never share it. This is generated server-side for convenience only.']);
        break;

    case 'canonical_url':
        if (!$q) { echo json_encode(['error' => 'URL required']); break; }
        if (!preg_match('#^https?://#i', $q)) $q = 'https://' . $q;
        $res = nt_fetch($q, ['timeout' => 12]);
        if (!empty($res['error'])) { echo json_encode(['error' => $res['error']]); break; }
        $body      = $res['body'] ?? '';
        $final_url = $res['final_url'] ?? $q;
        $canonical = null;
        if (preg_match('/<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']+)["\'][^>]*>/i', $body, $m)) {
            $canonical = $m[1];
        } elseif (preg_match('/<link[^>]+href=["\']([^"\']+)["\'][^>]+rel=["\']canonical["\'][^>]*>/i', $body, $m)) {
            $canonical = $m[1];
        }
        echo json_encode(['url' => $q, 'final_url' => $final_url, 'canonical' => $canonical, 'http_code' => $res['code'] ?? 0]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => "Unknown action: {$action}"]);
}
