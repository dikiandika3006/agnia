<?php
$page_title = 'AGNIA — IT Consulting & AI Automation';
$page_key   = 'home';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sections.php';

$stats = [
    ['brain', '88%', 'organisasi sudah memakai AI di setidaknya satu fungsi bisnis, tetapi baru 7% yang menerapkannya penuh di seluruh organisasi.', 'McKinsey, The State of AI 2025', 'a'],
    ['trend', '+40%', 'kualitas hasil kerja, dan 25% lebih cepat, saat AI dipakai pada tugas yang memang cocok untuk AI.', 'Harvard Business School &amp; BCG, 2023', 'b'],
    ['support', '50%', 'kasus layanan pelanggan diperkirakan ditangani AI pada 2027, naik dari 30% saat ini.', 'Salesforce, State of Service 2025 (proyeksi)', 'c'],
];
?>

<section class="hero">
  <div class="wrap hero-grid">
    <div class="txt">
      <div class="eyebrow">IT Consulting &amp; AI Automation</div>
      <div class="rule"></div>
      <h1>Mengubah Operasi Digital Menjadi <em>Dampak Bisnis yang Terukur</em></h1>
      <div class="rule"></div>
      <p class="lead">Kami konsultan IT yang merancang dan membangun aplikasi serta automation berbasis AI, di atas sistem yang sudah Anda punya. Mulai dari satu proses, buktikan dampaknya, lalu perluas.</p>
      <div class="ctas">
        <a class="btn p" href="kontak#form">Jadwalkan Discovery Workshop <?= arrow() ?></a>
        <a class="btn s" href="layanan">Lihat layanan</a>
      </div>
      <ul class="trust">
        <li><?= icon('check', 18, 2.2) ?>Tanpa ganti sistem lama</li>
        <li><?= icon('check', 18, 2.2) ?>Proof of Value 6 minggu</li>
        <li><?= icon('check', 18, 2.2) ?>Data tetap milik Anda</li>
      </ul>
    </div>

    <div class="hero-visual" aria-hidden="true">
      <img class="hero-art" src="assets/images/hero-a.svg" alt="" width="640" height="520">
    </div>
  </div>
  <svg class="wave" viewBox="0 0 1440 140" preserveAspectRatio="none" aria-hidden="true"><defs><linearGradient id="wv" x1="0" x2="1"><stop offset="0" stop-color="#0052FF"/><stop offset=".5" stop-color="#4D8DFF" stop-opacity=".45"/><stop offset="1" stop-color="#4D8DFF" stop-opacity="0"/></linearGradient><linearGradient id="wv2" x1="0" x2="1"><stop offset="0" stop-color="#DCE8FF"/><stop offset="1" stop-color="#EEF4FF" stop-opacity="0"/></linearGradient></defs><path d="M0 70 C 380 130, 860 140, 1440 96 L1440 140 L0 140 Z" fill="url(#wv2)"/><path d="M0 104 C 330 138, 760 142, 1160 124 L1160 129 C 760 148, 330 146, 0 116 Z" fill="url(#wv)"/></svg>
</section>

<section class="stats">
  <div class="wrap">
    <div class="sh"><div class="bar"></div><h2 class="title">Dunia Bergerak Menuju<br><em>Intelligent Organization</em></h2></div>
    <p class="lead">Organisasi tidak lagi melihat AI sebagai eksperimen teknologi, tetapi sebagai cara baru untuk meningkatkan produktivitas, pelayanan, dan pengambilan keputusan. Tantangannya ada di penerapan.</p>
    <div class="sgrid">
      <?php foreach ($stats as [$ic, $v, $p, $src, $cls]): ?>
      <div class="sc <?= $cls ?>">
        <div class="top">
          <div class="ring"><?= icon($ic, 34, 1.6) ?></div>
          <div><div class="v"><?= $v ?></div><p><?= $p ?></p></div>
        </div>
        <div class="src">Sumber: <?= $src ?></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="qbar">
      <?= icon('quote', 40, 2.4) ?>
      <div class="qt">AI bukan menggantikan tim Anda.<br class="d"> AI <em>memperkuat organisasi digital</em> agar bergerak lebih cepat.</div>
    </div>
  </div>
</section>

<?php
section_services();
section_apps();
section_sfi();
section_finance();
section_pov();
section_before_after();
section_why();
section_cta();
require __DIR__ . '/includes/footer.php';
