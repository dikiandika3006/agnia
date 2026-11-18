<?php
/* =========================================================
   AGNIA — konfigurasi situs
   Ubah nilai di bawah ini sebelum website online.
   ========================================================= */

date_default_timezone_set('Asia/Jakarta');

const SITE_NAME    = 'AGNIA';
const SITE_TAGLINE = 'AI for a Greater Impact';
const STORAGE_DIR  = __DIR__ . '/../storage';

/* Pengaturan bisa diubah lewat halaman /admin (disimpan di storage/settings.json).
   Nilai di bawah ini hanya default jika belum pernah disimpan dari admin. */
const SETTINGS_DEFAULT = [
    'site_url'       => '',             // contoh: https://agnia.co.id (untuk canonical & og:url)
    'whatsapp'       => '628170845807', // format internasional tanpa +
    'email'          => '',             // contoh: halo@agnia.co.id
    'linkedin'       => '',             // contoh: https://www.linkedin.com/company/agnia
    'location'       => 'Indonesia',
    'form_recipient' => '',             // kosong = pakai email di atas
    'ga_id'          => '',             // Google tag: G-XXXX atau GTM-XXXX
];

function settings(): array
{
    static $s = null;
    if ($s === null) {
        $file = STORAGE_DIR . '/settings.json';
        $saved = is_file($file) ? json_decode((string)file_get_contents($file), true) : null;
        $s = array_merge(SETTINGS_DEFAULT, is_array($saved) ? array_intersect_key($saved, SETTINGS_DEFAULT) : []);
    }
    return $s;
}

define('SITE_URL', settings()['site_url']);
define('CONTACT_WHATSAPP', settings()['whatsapp']);
define('CONTACT_EMAIL', settings()['email']);
define('CONTACT_LINKEDIN', settings()['linkedin']);
define('CONTACT_LOCATION', settings()['location'] !== '' ? settings()['location'] : 'Indonesia');
define('GA_ID', preg_match('/^(G|GTM|GT)-[A-Z0-9]{4,15}$/', settings()['ga_id']) ? settings()['ga_id'] : '');
define('FORM_RECIPIENT', settings()['form_recipient'] !== '' ? settings()['form_recipient'] : CONTACT_EMAIL);

/** Screenshot Sistem Manajemen Toko (diunggah lewat admin), atau '' jika belum ada. */
function showcase_image(): string
{
    foreach (['png', 'jpg', 'webp'] as $ext) {
        $f = __DIR__ . '/../assets/images/sistem-toko.' . $ext;
        if (is_file($f)) {
            return 'assets/images/sistem-toko.' . $ext . '?v=' . filemtime($f);
        }
    }
    return '';
}

/* ---------- Helper ---------- */

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/** Link WhatsApp dengan pesan awal. Jika nomor belum diisi, arahkan ke form kontak. */
function wa_link(string $text = 'Halo AGNIA, saya ingin berdiskusi tentang kebutuhan bisnis saya.'): string
{
    if (CONTACT_WHATSAPP === '') {
        return 'kontak#form';
    }
    return 'https://wa.me/' . CONTACT_WHATSAPP . '?text=' . rawurlencode($text);
}

function wa_attrs(): string
{
    return CONTACT_WHATSAPP === '' ? '' : ' target="_blank" rel="noopener"';
}

/** Ikon garis dari assets/icons (SVG inline, warna mengikuti currentColor). */
function icon(string $name, int $size = 24, float $stroke = 0, string $class = 'ic'): string
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $file = __DIR__ . '/../assets/icons/' . basename($name) . '.svg';
        $svg  = is_file($file) ? file_get_contents($file) : '';
        $svg  = preg_replace('#<metadata>.*?</metadata>#s', '', $svg);
        $svg  = preg_replace('#\s+xmlns:c2pa="[^"]*"#', '', $svg);
        $cache[$name] = $svg;
    }
    $svg = $cache[$name];
    if ($svg === '') {
        return '';
    }
    $svg = preg_replace('#\swidth="\d+"#', ' width="' . $size . '"', $svg, 1);
    $svg = preg_replace('#\sheight="\d+"#', ' height="' . $size . '"', $svg, 1);
    if ($stroke > 0) {
        $svg = preg_replace('#stroke-width="[\d.]+"#', 'stroke-width="' . $stroke . '"', $svg, 1);
    }
    return str_replace('<svg ', '<svg class="' . e($class) . '" aria-hidden="true" focusable="false" ', $svg);
}

function check(int $size = 16): string
{
    return icon('check', $size, 2.2, 'ic ic-check');
}

function arrow(int $size = 18): string
{
    return icon('arrow', $size, 2, 'ic ic-arrow');
}

/** Navigasi utama: [url, label, key halaman aktif] */
const NAV = [
    ['layanan',          'Layanan',    'layanan'],
    ['./#aplikasi',      'Aplikasi',   'aplikasi'],
    ['solusi',           'Solusi',     'solusi'],
    ['./#cara-kerja',    'Cara Kerja', 'cara-kerja'],
    ['tentang',          'Tentang',    'tentang'],
    ['kontak',           'Kontak',     'kontak'],
];
