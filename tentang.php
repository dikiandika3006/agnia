<?php
$page_title = 'Tentang AGNIA';
$page_desc  = 'AGNIA adalah konsultan IT yang membawa automation dan AI ke setiap proyek, mengubah operasi digital menjadi dampak bisnis yang terukur.';
$page_key   = 'tentang';
$page_path  = 'tentang';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sections.php';
?>

<?php page_hero('Tentang AGNIA', 'AI for a <em>Greater Impact</em>', 'AGNIA adalah konsultan IT yang membawa automation dan AI ke setiap proyek. Kami membantu organisasi mengubah operasi digital menjadi dampak bisnis yang terukur, tanpa harus mengganti sistem yang sudah berjalan.'); ?>

<section class="about">
  <div class="wrap">
    <div class="about-grid">
      <div>
        <?= eyebrow_title('Kenapa kami ada', 'Tantangan AI ada di <em>penerapan</em>, bukan di teknologinya') ?>
      </div>
      <div class="about-txt">
        <p class="lead">Banyak organisasi sudah mencoba AI, tetapi hanya sedikit yang benar-benar merasakan dampaknya di operasi sehari-hari. Data tersebar, proses masih manual, dan hasil proyek sulit diukur.</p>
        <p>Kami percaya AI paling bernilai ketika ditempatkan di proses yang tepat, terhubung ke data yang benar, dan dipakai oleh orang yang membutuhkannya. Karena itu kami tidak berhenti di rekomendasi: kami merancang, membangun, dan merawat sistemnya bersama tim Anda.</p>
        <p>Tim AGNIA menggabungkan kemampuan pengembangan aplikasi, automation, AI, serta pemahaman akuntansi dan keuangan, sehingga solusi yang kami bangun masuk akal secara teknis maupun bisnis.</p>
      </div>
    </div>

    <div class="mv">
      <div class="card mvc"><div class="itile"><?= icon('target', 24) ?></div><h3>Misi</h3><p>Membantu organisasi bergerak dari <em>digital operation</em> menuju <em>intelligent operation</em> dengan langkah kecil yang terukur.</p></div>
      <div class="card mvc"><div class="itile"><?= icon('shield', 24) ?></div><h3>Prinsip</h3><p>Manusia tetap memutuskan, data tetap milik Anda, dan setiap proyek diukur dari baseline yang disepakati.</p></div>
      <div class="card mvc"><div class="itile"><?= icon('spark', 24) ?></div><h3>Pendekatan</h3><p>Mulai dari satu proses bernilai tinggi, buktikan dampaknya dalam 6 minggu, lalu perluas ke area berikutnya.</p></div>
    </div>
  </div>
</section>

<?php
section_why();
section_before_after();
section_cta();
require __DIR__ . '/includes/footer.php';
