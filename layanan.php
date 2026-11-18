<?php
$page_title = 'Layanan — AGNIA';
$page_desc  = 'Konsultasi & strategi IT, business automation & AI, pengembangan aplikasi & integrasi, serta laporan keuangan & dashboard dari AGNIA.';
$page_key   = 'layanan';
$page_path  = 'layanan';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sections.php';

$details = [
    'konsultasi' => [
        'Kami mulai dari masalah bisnis, bukan dari teknologi. Bersama tim Anda, kami memetakan proses, data, dan KPI untuk menemukan use case yang paling bernilai, lalu menyusun roadmap yang realistis.',
        ['Discovery Workshop 90 menit bersama pemilik proses', 'Pemetaan proses bisnis dan sumber data', 'Penetapan baseline dan KPI keberhasilan', 'Roadmap IT &amp; AI bertahap sesuai prioritas'],
        'Laporan temuan, daftar use case berprioritas, dan roadmap implementasi.',
    ],
    'automation' => [
        'Alur kerja yang berulang kami ubah menjadi otomatis. AI membaca data, menemukan risiko dan peluang, lalu mengirim rekomendasi ke orang yang tepat, di kanal yang sudah mereka pakai seperti WhatsApp.',
        ['AI reasoning untuk analisis dan Next Best Action', 'Notifikasi dan ringkasan harian lewat WhatsApp', 'Laporan otomatis terjadwal per peran', 'Human-in-the-loop: keputusan tetap di tim Anda'],
        'Workflow otomatis yang berjalan di atas sistem Anda, lengkap dengan monitoring.',
    ],
    'aplikasi' => [
        'Ketika solusi siap pakai tidak cocok, kami membangun aplikasi sesuai cara kerja tim Anda: web, mobile, sistem internal, dan integrasi antar aplikasi yang sudah ada.',
        ['Aplikasi web, dashboard, dan portal', 'Aplikasi mobile Android &amp; iOS', 'Integrasi CRM, ERP, spreadsheet, dan database', 'API &amp; service layer yang siap untuk AI'],
        'Aplikasi yang berjalan di produksi, dokumentasi, dan dukungan perawatan.',
    ],
    'laporan' => [
        'Laporan keuangan dan operasional tidak perlu lagi direkap manual. Kami membangun pipeline data, rekonsiliasi otomatis, dan cockpit per peran, sehingga pimpinan melihat kondisi terkini kapan saja.',
        ['Laba rugi, neraca, dan arus kas otomatis', 'Anggaran vs realisasi per unit atau cabang', 'Analisis biaya dan peringatan dini dengan AI', 'Executive cockpit dengan akses per peran'],
        'Dashboard dan laporan yang terhubung ke data sumber, bukan file rekap.',
    ],
];
?>

<?php page_hero('Layanan', 'Satu mitra, dari strategi sampai <em>sistem berjalan</em>', 'Satu tim untuk strategi, automation, dan pengembangan aplikasi. Kami membantu Anda memilih proses yang tepat, membangunnya, lalu memastikan dampaknya terukur.',
    '<div class="ctas"><a class="btn p" href="kontak#form">Jadwalkan Discovery Workshop ' . arrow() . '</a><a class="btn s" href="./#cara-kerja">Lihat cara kerja</a></div>'); ?>

<section class="svc-detail">
  <div class="wrap">
    <nav class="subnav" aria-label="Daftar layanan">
      <?php foreach (SERVICES as [$id, $ic, $title]): ?>
      <a href="layanan#<?= $id ?>"><?= icon($ic, 18) ?><?= $title ?></a>
      <?php endforeach; ?>
    </nav>

    <?php foreach (SERVICES as $i => [$id, $ic, $title, $desc, $items]):
        [$long, $scope, $output] = $details[$id]; ?>
    <article class="sd" id="<?= $id ?>">
      <div class="sd-head">
        <div class="itile"><?= icon($ic, 26) ?></div>
        <span class="sd-no">0<?= $i + 1 ?></span>
      </div>
      <div class="sd-body">
        <h2><?= $title ?></h2>
        <p class="lead"><?= $long ?></p>
        <div class="sd-cols">
          <div>
            <h3>Yang kami kerjakan</h3>
            <ul class="checks"><?php foreach ($scope as $s): ?><li><?= check(18) ?><?= $s ?></li><?php endforeach; ?></ul>
          </div>
          <div class="sd-out">
            <h3>Hasil yang Anda terima</h3>
            <p><?= $output ?></p>
            <a class="link" href="kontak?topik=<?= $id ?>#form">Diskusikan kebutuhan ini <?= arrow(16) ?></a>
          </div>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>
</section>

<?php
section_pov();
section_cta();
require __DIR__ . '/includes/footer.php';
