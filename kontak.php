<?php
require_once __DIR__ . '/includes/config.php';

$topics = [
    'workshop'   => 'Discovery Workshop',
    'konsultasi' => 'Konsultasi & Strategi IT',
    'automation' => 'Business Automation & AI',
    'aplikasi'   => 'Pengembangan Aplikasi & Integrasi',
    'laporan'    => 'Laporan Keuangan & Dashboard',
    'keuangan'   => 'AI untuk Keuangan',
    'sfi'        => 'Sales Force Intelligence',
    'toko'       => 'Sistem Manajemen Toko',
    'lainnya'    => 'Lainnya',
];

session_start();
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

$errors = [];
$old    = ['nama' => '', 'organisasi' => '', 'email' => '', 'telepon' => '', 'topik' => $_GET['topik'] ?? 'workshop', 'pesan' => ''];
if (!isset($topics[$old['topik']])) {
    $old['topik'] = 'workshop';
}
$sent = isset($_GET['terkirim']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $_) {
        $old[$k] = trim((string)($_POST[$k] ?? ''));
    }
    $honeypot = trim((string)($_POST['website'] ?? ''));

    if (!hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? ''))) {
        $errors['form'] = 'Sesi formulir kedaluwarsa. Silakan kirim ulang.';
    }
    if (mb_strlen($old['nama']) < 2)                         $errors['nama']    = 'Mohon isi nama Anda.';
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL))   $errors['email']   = 'Alamat email belum valid.';
    if ($old['telepon'] !== '' && !preg_match('/^[0-9+\-\s()]{8,20}$/', $old['telepon'])) $errors['telepon'] = 'Nomor telepon belum valid.';
    if (!isset($topics[$old['topik']]))                      $errors['topik']   = 'Pilih topik.';
    if (mb_strlen($old['pesan']) < 10)                       $errors['pesan']   = 'Ceritakan sedikit kebutuhan Anda (min. 10 karakter).';
    if (empty($_POST['setuju']))                             $errors['setuju']  = 'Mohon setujui kebijakan privasi.';
    foreach ($old as $k => $v) {
        if (mb_strlen($v) > ($k === 'pesan' ? 3000 : 200)) $errors[$k] = 'Isian terlalu panjang.';
    }

    if (!$errors) {
        if ($honeypot === '') {
            $dir = __DIR__ . '/storage';
            if (!is_dir($dir)) {
                mkdir($dir, 0750, true);
            }
            $fh = fopen($dir . '/pesan-kontak.csv', 'ab');
            if ($fh) {
                if (filesize($dir . '/pesan-kontak.csv') === 0) {
                    fputcsv($fh, ['waktu', 'nama', 'organisasi', 'email', 'telepon', 'topik', 'pesan']);
                }
                // Cegah formula injection saat dibuka di Excel
                $safe = array_map(fn($v) => preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v, $old);
                fputcsv($fh, [date('c'), $safe['nama'], $safe['organisasi'], $safe['email'], $safe['telepon'], $topics[$safe['topik']], $safe['pesan']]);
                fclose($fh);
            }
            if (FORM_RECIPIENT !== '' && function_exists('mail')) {
                $body = "Nama: {$old['nama']}\nOrganisasi: {$old['organisasi']}\nEmail: {$old['email']}\nTelepon: {$old['telepon']}\nTopik: {$topics[$old['topik']]}\n\n{$old['pesan']}\n";
                $replyTo = str_replace(["\r", "\n"], '', $old['email']);
                @mail(FORM_RECIPIENT, '=?UTF-8?B?' . base64_encode('[Website AGNIA] ' . $topics[$old['topik']]) . '?=', $body,
                    "Content-Type: text/plain; charset=UTF-8\r\nReply-To: {$replyTo}");
            }
        }
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        header('Location: kontak?terkirim=1#form', true, 303);
        exit;
    }
}

$page_title = 'Kontak — AGNIA';
$page_desc  = 'Jadwalkan Discovery Workshop 90 menit bersama AGNIA, atau hubungi kami lewat WhatsApp dan email.';
$page_key   = 'kontak';
$page_path  = 'kontak';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sections.php';

function field_err(array $errors, string $k): string
{
    return isset($errors[$k]) ? '<span class="err" id="err-' . $k . '">' . e($errors[$k]) . '</span>' : '';
}
function aria_err(array $errors, string $k): string
{
    return isset($errors[$k]) ? ' aria-invalid="true" aria-describedby="err-' . $k . '"' : '';
}
?>

<?php page_hero('Kontak', 'Mulai dari <em>satu proses</em> bernilai tinggi', 'Ceritakan proses yang ingin Anda perbaiki. Kami akan menghubungi Anda untuk menjadwalkan Discovery Workshop 90 menit, online atau tatap muka.'); ?>

<section class="contact" id="form">
  <div class="wrap">
    <div class="contact-grid">
      <div class="card form-card">
        <?php if ($sent): ?>
        <div class="success" role="status">
          <div class="itile"><?= icon('check', 28, 2.4) ?></div>
          <h2>Terima kasih, pesan Anda sudah kami terima.</h2>
          <p>Tim AGNIA akan menghubungi Anda dalam 1–2 hari kerja untuk menjadwalkan Discovery Workshop.</p>
          <div class="ctas">
            <a class="btn p" href="./">Kembali ke beranda</a>
            <?php if (CONTACT_WHATSAPP !== ''): ?><a class="btn s" href="<?= e(wa_link()) ?>"<?= wa_attrs() ?>>Chat WhatsApp</a><?php endif; ?>
          </div>
        </div>
        <?php else: ?>
        <h2>Jadwalkan Discovery Workshop</h2>
        <p class="muted">Kolom bertanda * wajib diisi.</p>
        <?php if (isset($errors['form'])): ?><div class="alert" role="alert"><?= e($errors['form']) ?></div><?php endif; ?>
        <?php if ($errors && !isset($errors['form'])): ?><div class="alert" role="alert">Ada isian yang perlu diperbaiki.</div><?php endif; ?>
        <form method="post" action="kontak#form" novalidate>
          <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
          <div class="hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
          <div class="fgrid2">
            <label class="f"><span>Nama *</span><input type="text" name="nama" value="<?= e($old['nama']) ?>" autocomplete="name" required<?= aria_err($errors, 'nama') ?>><?= field_err($errors, 'nama') ?></label>
            <label class="f"><span>Organisasi / perusahaan</span><input type="text" name="organisasi" value="<?= e($old['organisasi']) ?>" autocomplete="organization"></label>
            <label class="f"><span>Email *</span><input type="email" name="email" value="<?= e($old['email']) ?>" autocomplete="email" required<?= aria_err($errors, 'email') ?>><?= field_err($errors, 'email') ?></label>
            <label class="f"><span>Nomor WhatsApp</span><input type="tel" name="telepon" value="<?= e($old['telepon']) ?>" autocomplete="tel" placeholder="08xx"<?= aria_err($errors, 'telepon') ?>><?= field_err($errors, 'telepon') ?></label>
          </div>
          <label class="f"><span>Topik *</span>
            <select name="topik"<?= aria_err($errors, 'topik') ?>>
              <?php foreach ($topics as $k => $v): ?><option value="<?= e($k) ?>"<?= $old['topik'] === $k ? ' selected' : '' ?>><?= e($v) ?></option><?php endforeach; ?>
            </select><?= field_err($errors, 'topik') ?>
          </label>
          <label class="f"><span>Proses yang ingin diperbaiki *</span>
            <textarea name="pesan" rows="5" placeholder="Contoh: laporan penjualan cabang masih direkap manual setiap minggu…" required<?= aria_err($errors, 'pesan') ?>><?= e($old['pesan']) ?></textarea><?= field_err($errors, 'pesan') ?>
          </label>
          <label class="cb"><input type="checkbox" name="setuju" value="1"<?= !empty($_POST['setuju']) ? ' checked' : '' ?><?= aria_err($errors, 'setuju') ?>><span>Saya setuju data ini dipakai AGNIA untuk menghubungi saya, sesuai <a href="kebijakan-privasi">kebijakan privasi</a>.</span></label>
          <?= field_err($errors, 'setuju') ?>
          <button class="btn p" type="submit">Kirim pesan <?= arrow() ?></button>
        </form>
        <?php endif; ?>
      </div>

      <aside class="contact-side">
        <div class="card info">
          <h3>Hubungi langsung</h3>
          <ul class="clist">
            <li><div class="ti"><?= icon('msg', 20) ?></div><div><b>WhatsApp</b>
              <?php if (CONTACT_WHATSAPP !== ''): ?><a href="<?= e(wa_link()) ?>"<?= wa_attrs() ?>>+<?= e(CONTACT_WHATSAPP) ?></a><?php else: ?><span>Segera tersedia</span><?php endif; ?></div></li>
            <li><div class="ti"><?= icon('send', 20) ?></div><div><b>Email</b>
              <?php if (CONTACT_EMAIL !== ''): ?><a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a><?php else: ?><span>Segera tersedia</span><?php endif; ?></div></li>
            <?php if (CONTACT_LINKEDIN !== ''): ?>
            <li><div class="ti"><?= icon('globe', 20) ?></div><div><b>LinkedIn</b><a href="<?= e(CONTACT_LINKEDIN) ?>" target="_blank" rel="noopener">AGNIA</a></div></li>
            <?php endif; ?>
            <li><div class="ti"><?= icon('flag', 20) ?></div><div><b>Lokasi</b><span><?= e(CONTACT_LOCATION) ?></span></div></li>
          </ul>
        </div>
        <div class="card info dark">
          <h3>Dalam workshop 90 menit</h3>
          <ul class="checks">
            <li><?= icon('check', 18, 2.4) ?>Menentukan pemilik proses dan pengambil keputusan</li>
            <li><?= icon('check', 18, 2.4) ?>Memahami alur kerja nyata hari ini</li>
            <li><?= icon('check', 18, 2.4) ?>Menetapkan data awal (baseline) untuk Proof of Value</li>
          </ul>
          <div class="meta"><span><?= icon('clock', 18) ?>90 menit</span><span><?= icon('globe', 18) ?>Online atau tatap muka</span></div>
        </div>
      </aside>
    </div>
  </div>
</section>

<section class="faq">
  <div class="wrap narrow">
    <div class="center"><?= eyebrow_title('Pertanyaan umum', 'Sebelum <em>memulai</em>') ?></div>
    <div class="faq-list">
      <details><summary>Apakah kami harus mengganti sistem yang sudah ada?</summary><p>Tidak. Kami membangun automation dan aplikasi di atas sistem yang sudah Anda pakai, seperti spreadsheet, CRM, database, atau aplikasi akuntansi.</p></details>
      <details><summary>Apa itu Proof of Value 6 minggu?</summary><p>Program singkat untuk membuktikan dampak satu use case: minggu 1 pemetaan dan KPI, minggu 2–5 membangun dan menguji bersama user, minggu 6 mengukur hasil. Setelah itu Anda memutuskan lanjut atau tidak.</p></details>
      <details><summary>Bagaimana dengan keamanan data kami?</summary><p>Data tetap milik Anda. Akses diatur per peran, dan kami menerapkan prinsip Undang-Undang Pelindungan Data Pribadi (UU PDP) dalam setiap proyek.</p></details>
      <details><summary>Apakah cocok untuk UMKM atau yayasan?</summary><p>Ya. Kami bekerja dengan UMKM dan toko, yayasan dan lembaga non-profit, hingga perusahaan dengan banyak cabang. Ruang lingkup disesuaikan dengan kebutuhan dan skala Anda.</p></details>
      <details><summary>Apakah Discovery Workshop berbayar?</summary><p>Hubungi kami untuk detail. Kami akan menjelaskan format dan ruang lingkupnya sebelum workshop dijadwalkan.</p></details>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php';
