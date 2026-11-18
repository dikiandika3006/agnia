<?php
/* =========================================================
   AGNIA — halaman admin sederhana
   - Pengaturan kontak & situs  (storage/settings.json)
   - Pesan masuk dari form kontak (storage/pesan-kontak.csv)
   - Screenshot Sistem Manajemen Toko (assets/images/sistem-toko.*)
   - Ganti password            (storage/admin.json)
   ========================================================= */

require __DIR__ . '/../includes/config.php';

header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store');
header('X-Frame-Options: DENY');

session_name('AGNIAADMIN');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => dirname($_SERVER['SCRIPT_NAME']) . '/',
    'httponly' => true,
    'samesite' => 'Strict',
    'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();

const ADMIN_FILE    = STORAGE_DIR . '/admin.json';
const SETTINGS_FILE = STORAGE_DIR . '/settings.json';
const MSG_FILE      = STORAGE_DIR . '/pesan-kontak.csv';
const ATTEMPT_FILE  = STORAGE_DIR . '/login-attempts.json';
const IMG_DIR       = __DIR__ . '/../assets/images';
const MAX_ATTEMPTS  = 5;
const LOCK_SECONDS  = 900;
const IDLE_SECONDS  = 7200;

if (!is_dir(STORAGE_DIR)) {
    mkdir(STORAGE_DIR, 0750, true);
}

/* ---------- Helper ---------- */

function read_json(string $file): array
{
    $d = is_file($file) ? json_decode((string)file_get_contents($file), true) : null;
    return is_array($d) ? $d : [];
}

function write_file(string $file, string $data): bool
{
    $tmp = $file . '.tmp' . bin2hex(random_bytes(3));
    if (file_put_contents($tmp, $data, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, $file);
}

function write_json(string $file, array $data): bool
{
    return write_file($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

function is_local(): bool
{
    return in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
}

function flash(string $msg, string $type = 'ok'): void
{
    $_SESSION['flash'] = [$type, $msg];
}

function go(string $page = ''): never
{
    header('Location: ./' . ($page ? '?p=' . $page : ''), true, 303);
    exit;
}

function csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">';
}

function logged_in(): bool
{
    if (empty($_SESSION['admin'])) {
        return false;
    }
    if (time() - ($_SESSION['last'] ?? 0) > IDLE_SECONDS) {
        $_SESSION = [];
        return false;
    }
    $_SESSION['last'] = time();
    return true;
}

function attempts(): array
{
    $all = read_json(ATTEMPT_FILE);
    $ip  = $_SERVER['REMOTE_ADDR'] ?? '?';
    $a   = $all[$ip] ?? ['n' => 0, 't' => 0];
    if (time() - $a['t'] > LOCK_SECONDS) {
        $a = ['n' => 0, 't' => 0];
    }
    return $a;
}

function set_attempts(?array $a): void
{
    $all = read_json(ATTEMPT_FILE);
    $ip  = $_SERVER['REMOTE_ADDR'] ?? '?';
    foreach ($all as $k => $v) { // bersihkan catatan lama
        if (time() - ($v['t'] ?? 0) > LOCK_SECONDS) {
            unset($all[$k]);
        }
    }
    if ($a === null) {
        unset($all[$ip]);
    } else {
        $all[$ip] = $a;
    }
    write_json(ATTEMPT_FILE, $all);
}

function password_problem(string $pw, string $confirm): string
{
    if (mb_strlen($pw) < 10) return 'Password minimal 10 karakter.';
    if ($pw !== $confirm)    return 'Konfirmasi password tidak sama.';
    return '';
}

/** @return array{0: array, 1: array<int, array>} header & baris pesan (terbaru di atas) */
function read_messages(): array
{
    if (!is_file(MSG_FILE)) {
        return [[], []];
    }
    $rows = [];
    $fh = fopen(MSG_FILE, 'rb');
    flock($fh, LOCK_SH);
    $head = fgetcsv($fh, null, ',', '"', '\\') ?: [];
    while (($r = fgetcsv($fh, null, ',', '"', '\\')) !== false) {
        if ($r !== [null]) {
            $rows[] = $r;
        }
    }
    flock($fh, LOCK_UN);
    fclose($fh);
    return [$head, array_reverse($rows)];
}

function row_id(array $r): string
{
    return substr(hash('sha256', implode("\x1f", $r)), 0, 16);
}

function normalize_wa(string $v): string
{
    $d = preg_replace('/\D+/', '', $v);
    if ($d === '') return '';
    if (str_starts_with($d, '0')) $d = '62' . substr($d, 1);
    elseif (str_starts_with($d, '8')) $d = '62' . $d;
    return $d;
}

function valid_url(string $v): bool
{
    return (bool)filter_var($v, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $v);
}

/* ---------- Aksi (POST) ---------- */

$admin    = read_json(ADMIN_FILE);
$has_pass = !empty($admin['hash']);
$action   = $_POST['action'] ?? '';
$errors   = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals(csrf(), (string)($_POST['csrf'] ?? ''))) {
        flash('Sesi kedaluwarsa, silakan coba lagi.', 'err');
        go($_GET['p'] ?? '');
    }

    // Buat password pertama kali — hanya dari komputer lokal (Laragon)
    if ($action === 'setup' && !$has_pass) {
        if (!is_local()) {
            flash('Password admin hanya bisa dibuat pertama kali dari komputer lokal.', 'err');
            go();
        }
        $err = password_problem((string)($_POST['password'] ?? ''), (string)($_POST['confirm'] ?? ''));
        if ($err) {
            flash($err, 'err');
            go();
        }
        write_json(ADMIN_FILE, ['hash' => password_hash($_POST['password'], PASSWORD_DEFAULT), 'updated' => date('c')]);
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        $_SESSION['last']  = time();
        flash('Password admin berhasil dibuat.');
        go();
    }

    if ($action === 'login' && $has_pass) {
        $a = attempts();
        if ($a['n'] >= MAX_ATTEMPTS) {
            flash('Terlalu banyak percobaan. Coba lagi dalam ' . ceil((LOCK_SECONDS - (time() - $a['t'])) / 60) . ' menit.', 'err');
            go();
        }
        if (password_verify((string)($_POST['password'] ?? ''), $admin['hash'])) {
            set_attempts(null);
            if (password_needs_rehash($admin['hash'], PASSWORD_DEFAULT)) {
                $admin['hash'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                write_json(ADMIN_FILE, $admin);
            }
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            $_SESSION['last']  = time();
            go();
        }
        set_attempts(['n' => $a['n'] + 1, 't' => time()]);
        flash('Password salah.', 'err');
        go();
    }

    if ($action === 'logout') {
        $_SESSION = [];
        session_destroy();
        header('Location: ./', true, 303);
        exit;
    }

    if (!logged_in()) {
        go();
    }

    switch ($action) {
        case 'save_settings':
            $in = [
                'site_url'       => rtrim(trim((string)($_POST['site_url'] ?? '')), '/'),
                'whatsapp'       => normalize_wa((string)($_POST['whatsapp'] ?? '')),
                'email'          => trim((string)($_POST['email'] ?? '')),
                'linkedin'       => trim((string)($_POST['linkedin'] ?? '')),
                'location'       => trim((string)($_POST['location'] ?? '')),
                'form_recipient' => trim((string)($_POST['form_recipient'] ?? '')),
                'ga_id'          => strtoupper(trim((string)($_POST['ga_id'] ?? ''))),
            ];
            if ($in['site_url'] !== '' && !valid_url($in['site_url']))                          $errors[] = 'Alamat website harus diawali https:// atau http://';
            if ($in['whatsapp'] !== '' && !preg_match('/^62\d{8,13}$/', $in['whatsapp']))        $errors[] = 'Nomor WhatsApp belum valid (contoh: 0812xxxxxxx).';
            if ($in['email'] !== '' && !filter_var($in['email'], FILTER_VALIDATE_EMAIL))        $errors[] = 'Email belum valid.';
            if ($in['form_recipient'] !== '' && !filter_var($in['form_recipient'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email penerima pesan belum valid.';
            if ($in['linkedin'] !== '' && !valid_url($in['linkedin']))                          $errors[] = 'Link LinkedIn harus diawali https://';
            if ($in['ga_id'] !== '' && !preg_match('/^(G|GTM|GT)-[A-Z0-9]{4,15}$/', $in['ga_id']))      $errors[] = 'ID Google Analytics belum valid (contoh: G-ABC123XYZ atau GTM-ABC1234).';
            if (mb_strlen($in['location']) > 100)                                                $errors[] = 'Lokasi terlalu panjang.';
            if ($errors) {
                $_SESSION['old']  = $in;
                flash(implode(' ', $errors), 'err');
            } elseif (write_json(SETTINGS_FILE, $in + ['updated' => date('c')])) {
                flash('Pengaturan disimpan. Perubahan langsung tampil di website.');
            } else {
                flash('Gagal menyimpan. Pastikan folder storage bisa ditulis.', 'err');
            }
            go('pengaturan');

        case 'delete_msg':
            $ids = array_map('strval', (array)($_POST['id'] ?? []));
            [$head, $rows] = read_messages();
            $keep = array_filter($rows, fn($r) => !in_array(row_id($r), $ids, true));
            $deleted = count($rows) - count($keep);
            $fh = fopen('php://temp', 'w+b');
            if ($head) fputcsv($fh, $head, ',', '"', '\\');
            foreach (array_reverse($keep) as $r) fputcsv($fh, $r, ',', '"', '\\');
            rewind($fh);
            write_file(MSG_FILE, stream_get_contents($fh));
            fclose($fh);
            flash($deleted . ' pesan dihapus.');
            go('pesan');

        case 'upload_image':
            $f = $_FILES['gambar'] ?? null;
            if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
                flash($f && in_array($f['error'], [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true) ? 'File terlalu besar.' : 'Pilih file gambar terlebih dahulu.', 'err');
                go('gambar');
            }
            if ($f['size'] > 5 * 1024 * 1024) {
                flash('Ukuran maksimal 5 MB.', 'err');
                go('gambar');
            }
            $info = @getimagesize($f['tmp_name']);
            $map  = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/webp' => 'webp'];
            if (!$info || !isset($map[$info['mime']])) {
                flash('Format harus PNG, JPG, atau WebP.', 'err');
                go('gambar');
            }
            foreach ($map as $ext) {
                @unlink(IMG_DIR . '/sistem-toko.' . $ext);
            }
            if (move_uploaded_file($f['tmp_name'], IMG_DIR . '/sistem-toko.' . $map[$info['mime']])) {
                flash('Screenshot berhasil diunggah (' . $info[0] . ' × ' . $info[1] . ' px).');
            } else {
                flash('Gagal menyimpan gambar.', 'err');
            }
            go('gambar');

        case 'delete_image':
            foreach (['png', 'jpg', 'webp'] as $ext) {
                @unlink(IMG_DIR . '/sistem-toko.' . $ext);
            }
            flash('Screenshot dihapus. Website kembali menampilkan gambar contoh.');
            go('gambar');

        case 'change_password':
            if (!password_verify((string)($_POST['current'] ?? ''), $admin['hash'] ?? '')) {
                flash('Password lama salah.', 'err');
                go('akun');
            }
            $err = password_problem((string)($_POST['password'] ?? ''), (string)($_POST['confirm'] ?? ''));
            if ($err) {
                flash($err, 'err');
                go('akun');
            }
            write_json(ADMIN_FILE, ['hash' => password_hash($_POST['password'], PASSWORD_DEFAULT), 'updated' => date('c')]);
            session_regenerate_id(true);
            flash('Password berhasil diganti.');
            go('akun');
    }
    go();
}

/* ---------- Unduh CSV ---------- */

if (isset($_GET['unduh']) && logged_in()) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="pesan-kontak-' . date('Ymd') . '.csv"');
    echo "\xEF\xBB\xBF"; // BOM agar terbaca benar di Excel
    readfile(MSG_FILE);
    exit;
}

/* ---------- Tampilan ---------- */

$auth  = logged_in();
$page  = $auth ? ($_GET['p'] ?? 'pengaturan') : 'login';
$pages = ['pengaturan' => ['Pengaturan', 'compass'], 'pesan' => ['Pesan masuk', 'msg'], 'gambar' => ['Screenshot', 'monitor'], 'akun' => ['Akun', 'shield']];
if ($auth && !isset($pages[$page])) {
    $page = 'pengaturan';
}
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$s = $_SESSION['old'] ?? settings();
unset($_SESSION['old']);
[$head, $msgs] = $auth ? read_messages() : [[], []];
?><!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Admin — AGNIA</title>
<link rel="icon" href="../assets/favicon/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="../css/agnia-tokens.css">
<link rel="stylesheet" href="admin.css?v=1">
</head>
<body>

<?php if (!$auth): ?>
<main class="auth">
  <div class="auth-card">
    <img src="../assets/logo/logo-horizontal-warna.svg" alt="AGNIA" width="150" height="40">
    <?php if ($flash): ?><div class="flash <?= e($flash[0]) ?>" role="alert"><?= e($flash[1]) ?></div><?php endif; ?>

    <?php if ($has_pass): ?>
      <h1>Masuk admin</h1>
      <form method="post" action="./">
        <?= csrf_field() ?><input type="hidden" name="action" value="login">
        <label class="f"><span>Password</span><input type="password" name="password" autocomplete="current-password" required autofocus></label>
        <button class="btn p" type="submit">Masuk</button>
      </form>
    <?php elseif (is_local()): ?>
      <h1>Buat password admin</h1>
      <p class="muted">Ini pertama kali admin dibuka. Buat password minimal 10 karakter.</p>
      <form method="post" action="./">
        <?= csrf_field() ?><input type="hidden" name="action" value="setup">
        <label class="f"><span>Password baru</span><input type="password" name="password" minlength="10" autocomplete="new-password" required autofocus></label>
        <label class="f"><span>Ulangi password</span><input type="password" name="confirm" minlength="10" autocomplete="new-password" required></label>
        <button class="btn p" type="submit">Simpan password</button>
      </form>
    <?php else: ?>
      <h1>Admin belum disiapkan</h1>
      <p class="muted">Demi keamanan, password admin harus dibuat dulu di komputer lokal (Laragon), lalu file <code>storage/admin.json</code> ikut diunggah ke server.</p>
    <?php endif; ?>
    <a class="back" href="../">← Kembali ke website</a>
  </div>
</main>

<?php else: ?>
<header class="top">
  <a class="brand" href="./"><img src="../assets/logo/logo-horizontal-warna.svg" alt="AGNIA" width="120" height="32"><span>Admin</span></a>
  <div class="top-r">
    <a class="ghost" href="../" target="_blank" rel="noopener">Lihat website ↗</a>
    <form method="post" action="./"><?= csrf_field() ?><input type="hidden" name="action" value="logout"><button class="ghost" type="submit">Keluar</button></form>
  </div>
</header>

<div class="shell">
  <nav class="side" aria-label="Menu admin">
    <?php foreach ($pages as $k => [$label, $ic]): ?>
    <a href="?p=<?= $k ?>"<?= $page === $k ? ' aria-current="page"' : '' ?>><?= icon($ic, 18) ?><?= e($label) ?><?php if ($k === 'pesan' && $msgs): ?><b class="badge"><?= count($msgs) ?></b><?php endif; ?></a>
    <?php endforeach; ?>
  </nav>

  <main class="content">
    <?php if ($flash): ?><div class="flash <?= e($flash[0]) ?>" role="status"><?= e($flash[1]) ?></div><?php endif; ?>

    <?php if ($page === 'pengaturan'): ?>
    <h1>Pengaturan</h1>
    <p class="muted">Kosongkan kolom yang belum ada. Tombol WhatsApp otomatis diarahkan ke form kontak bila nomornya kosong.</p>
    <form class="panel" method="post" action="./?p=pengaturan">
      <?= csrf_field() ?><input type="hidden" name="action" value="save_settings">
      <h2>Kontak</h2>
      <div class="grid2">
        <label class="f"><span>Nomor WhatsApp</span><input type="tel" name="whatsapp" value="<?= e($s['whatsapp']) ?>" placeholder="0812xxxxxxx"><small>Boleh ditulis 08…, otomatis diubah ke format 62…</small></label>
        <label class="f"><span>Email</span><input type="email" name="email" value="<?= e($s['email']) ?>" placeholder="halo@agnia.co.id"></label>
        <label class="f"><span>LinkedIn</span><input type="url" name="linkedin" value="<?= e($s['linkedin']) ?>" placeholder="https://www.linkedin.com/company/…"></label>
        <label class="f"><span>Lokasi</span><input type="text" name="location" value="<?= e($s['location']) ?>" placeholder="Indonesia"></label>
      </div>
      <h2>Situs</h2>
      <div class="grid2">
        <label class="f"><span>Alamat website (domain)</span><input type="url" name="site_url" value="<?= e($s['site_url']) ?>" placeholder="https://agnia.co.id"><small>Dipakai untuk SEO & pratinjau link.</small></label>
        <label class="f"><span>Email penerima pesan form</span><input type="email" name="form_recipient" value="<?= e($s['form_recipient']) ?>" placeholder="Kosong = pakai email di atas"><small>Butuh fitur kirim email aktif di hosting.</small></label>
        <label class="f"><span>ID Google Analytics</span><input type="text" name="ga_id" value="<?= e($s['ga_id']) ?>" placeholder="G-XXXXXXXXXX / GTM-XXXXXXX"><small>Measurement ID atau ID Google tag. Kosong = tanpa pelacakan.</small></label>
      </div>
      <div class="actions"><button class="btn p" type="submit">Simpan pengaturan</button></div>
    </form>

    <?php elseif ($page === 'pesan'): ?>
    <div class="h-row">
      <div><h1>Pesan masuk</h1><p class="muted"><?= count($msgs) ?> pesan dari form kontak, terbaru di atas.</p></div>
      <?php if ($msgs): ?><a class="btn s" href="?unduh=1">Unduh CSV</a><?php endif; ?>
    </div>
    <?php if (!$msgs): ?>
      <div class="panel empty"><?= icon('msg', 32) ?><p>Belum ada pesan.</p></div>
    <?php else: ?>
      <form method="post" action="./?p=pesan" id="del-form" onsubmit="return confirm('Hapus pesan yang dipilih?')">
        <?= csrf_field() ?><input type="hidden" name="action" value="delete_msg">
        <div class="msgs">
          <?php foreach ($msgs as $r):
              [$t, $nama, $org, $email, $tel, $topik, $pesan] = array_pad($r, 7, '');
              $ts = strtotime($t); ?>
          <article class="panel msg">
            <label class="pick"><input type="checkbox" name="id[]" value="<?= e(row_id($r)) ?>"><span class="sr">Pilih</span></label>
            <div class="msg-b">
              <div class="msg-h">
                <b><?= e($nama) ?></b><?php if ($org !== ''): ?><span class="org"> · <?= e($org) ?></span><?php endif; ?>
                <span class="chip"><?= e($topik) ?></span>
                <time datetime="<?= e($t) ?>"><?= $ts ? date('d M Y, H.i', $ts) : e($t) ?></time>
              </div>
              <p><?= nl2br(e($pesan)) ?></p>
              <div class="msg-c">
                <a href="mailto:<?= e($email) ?>"><?= icon('send', 15) ?><?= e($email) ?></a>
                <?php if ($tel !== ''): $wa = normalize_wa($tel); ?>
                <a href="https://wa.me/<?= e($wa) ?>" target="_blank" rel="noopener"><?= icon('msg', 15) ?><?= e($tel) ?></a>
                <?php endif; ?>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        </div>
        <div class="actions sticky"><button class="btn danger" type="submit">Hapus yang dipilih</button></div>
      </form>
    <?php endif; ?>

    <?php elseif ($page === 'gambar'): $shot = showcase_image(); ?>
    <h1>Screenshot Sistem Manajemen Toko</h1>
    <p class="muted">Tampil di kotak "Karya Kami" (Beranda & Solusi). Ukuran disarankan 1040 × 660 px, PNG/JPG/WebP, maks 5 MB.</p>
    <div class="panel">
      <div class="preview">
        <?php if ($shot): ?>
          <img src="../<?= e($shot) ?>" alt="Screenshot saat ini">
        <?php else: ?>
          <div class="ph"><?= icon('monitor', 36) ?><span>Belum ada screenshot — website menampilkan gambar contoh.</span></div>
        <?php endif; ?>
      </div>
      <form method="post" action="./?p=gambar" enctype="multipart/form-data" class="upload">
        <?= csrf_field() ?><input type="hidden" name="action" value="upload_image">
        <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
        <label class="f"><span><?= $shot ? 'Ganti gambar' : 'Pilih gambar' ?></span><input type="file" name="gambar" accept="image/png,image/jpeg,image/webp" required></label>
        <button class="btn p" type="submit">Unggah</button>
      </form>
      <?php if ($shot): ?>
      <form method="post" action="./?p=gambar" onsubmit="return confirm('Hapus screenshot?')">
        <?= csrf_field() ?><input type="hidden" name="action" value="delete_image">
        <button class="link-danger" type="submit">Hapus screenshot</button>
      </form>
      <?php endif; ?>
    </div>

    <?php elseif ($page === 'akun'): ?>
    <h1>Akun</h1>
    <p class="muted">Ganti password admin. Sesi otomatis berakhir setelah 2 jam tidak aktif.</p>
    <form class="panel narrow" method="post" action="./?p=akun">
      <?= csrf_field() ?><input type="hidden" name="action" value="change_password">
      <label class="f"><span>Password lama</span><input type="password" name="current" autocomplete="current-password" required></label>
      <label class="f"><span>Password baru</span><input type="password" name="password" minlength="10" autocomplete="new-password" required><small>Minimal 10 karakter.</small></label>
      <label class="f"><span>Ulangi password baru</span><input type="password" name="confirm" minlength="10" autocomplete="new-password" required></label>
      <div class="actions"><button class="btn p" type="submit">Ganti password</button></div>
    </form>
    <?php endif; ?>
  </main>
</div>
<?php endif; ?>
</body>
</html>
