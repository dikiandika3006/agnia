<?php
$page_title = 'Solusi — AGNIA';
$page_desc  = 'Sales Force Intelligence, AI untuk laporan keuangan, dan Sistem Manajemen Toko: solusi AGNIA yang siap diterapkan di atas sistem Anda.';
$page_key   = 'solusi';
$page_path  = 'solusi';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sections.php';

$sols = [
    ['sfi', 'gauge', 'Sales Force Intelligence', 'Target, risiko, dan prioritas harian tim sales lewat WhatsApp.'],
    ['keuangan', 'calc', 'AI untuk Keuangan', 'Pencatatan, rekonsiliasi, dan laporan keuangan otomatis.'],
    ['toko', 'store', 'Sistem Manajemen Toko', 'Aplikasi buatan AGNIA untuk pemilik toko.'],
];
?>

<?php page_hero('Solusi', 'Solusi yang <em>siap diterapkan</em>, diukur dari hasilnya', 'Setiap solusi dibangun di atas sistem yang sudah Anda punya, dimulai lewat Proof of Value 6 minggu, dan diukur dari baseline yang disepakati bersama.'); ?>

<section class="sol-pick">
  <div class="wrap">
    <div class="sol-grid">
      <?php foreach ($sols as [$id, $ic, $t, $d]): ?>
      <a class="card sol" href="solusi#<?= $id ?>">
        <div class="itile"><?= icon($ic, 24) ?></div>
        <div><b><?= $t ?></b><span><?= $d ?></span></div>
        <?= arrow(18) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php section_sfi(); ?>

<section class="sfi-benefits">
  <div class="wrap">
    <div class="center"><?= eyebrow_title('Untuk setiap peran', 'Satu sistem, <em>tiga sudut pandang</em>') ?></div>
    <div class="role-grid">
      <div class="card role"><div class="itile"><?= icon('users', 24) ?></div><h3>Account Executive</h3><p>Ringkasan pagi berisi target, akun prioritas, dan follow-up yang jatuh tempo. Tidak perlu membuka banyak aplikasi.</p></div>
      <div class="card role"><div class="itile"><?= icon('layers', 24) ?></div><h3>Kepala cabang</h3><p>Melihat performa tim, akun berisiko, dan pipeline cabang, lalu tahu siapa yang perlu dibantu hari ini.</p></div>
      <div class="card role"><div class="itile"><?= icon('chart', 24) ?></div><h3>Pimpinan</h3><p>Executive cockpit dengan run-rate, proyeksi, dan risiko lintas cabang dalam satu layar.</p></div>
    </div>
  </div>
</section>

<?php section_finance(); ?>

<section class="apps toko-sec" id="toko">
  <div class="wrap">
    <?php section_showcase(); ?>
  </div>
</section>

<?php
section_cta();
require __DIR__ . '/includes/footer.php';
